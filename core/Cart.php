<?php

require_once __DIR__ . '/../config/database.php';

class Cart
{
    public static function getCartByUserId($userId)
    {
        global $pdo;

        $sql = "SELECT *
                FROM carts
                WHERE user_id = :user_id
                LIMIT 1";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'user_id' => $userId
        ]);

        return $stmt->fetch();
    }
    public static function createCart($userId)
    {
        global $pdo;

        $sql = "INSERT INTO carts (user_id)
                VALUES (:user_id)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'user_id' => $userId
        ]);

        return $pdo->lastInsertId();
    }

    public static function addToCart($userId, $productId, $quantity)
    {
        global $pdo;
        $pdo->beginTransaction();

        try {
            $cart = self::getCartByUserId($userId);


            if (!$cart) {

                $cartId = self::createCart($userId);

            } else {

                $cartId = $cart['id'];

            }

            $sql = "SELECT id, price, stock
                    FROM products
                    WHERE id = :product_id
                    AND status = 1
                    LIMIT 1";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                'product_id' => $productId
            ]);

            $product = $stmt->fetch();


            if (!$product) {

                throw new Exception("Product not found.");

            }

            if ($product['stock'] < $quantity) {

                throw new Exception("Not enough stock available.");

            }

            $sql = "SELECT id, quantity
                    FROM cart_items
                    WHERE cart_id = :cart_id
                    AND product_id = :product_id
                    LIMIT 1";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                'cart_id' => $cartId,
                'product_id' => $productId
            ]);

            $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);


            if ($cartItem) {

                $newQuantity = $cartItem['quantity'] + $quantity;


                if ($newQuantity > $product['stock']) {

                    throw new Exception("You cannot add more than available stock.");

                }

                $sql = "UPDATE cart_items
                        SET quantity = :quantity
                        WHERE id = :id";

                $stmt = $pdo->prepare($sql);

                $stmt->execute([
                    'quantity' => $newQuantity,
                    'id' => $cartItem['id']
                ]);

            } else {

                $sql = "INSERT INTO cart_items
                            (cart_id, product_id, quantity, unit_price)
                        VALUES
                            (:cart_id, :product_id, :quantity, :unit_price)";

                $stmt = $pdo->prepare($sql);

                $stmt->execute([
                    'cart_id' => $cartId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $product['price']
                ]);

            }
            $pdo->commit();

            return true;

        } catch (Exception $e) {

            $pdo->rollBack();

            throw $e;

        }
    }

    public static function getCartItems($userId)
    {
        global $pdo;

        $sql = "SELECT
                    ci.id,
                    ci.cart_id,
                    ci.product_id,
                    ci.quantity,
                    ci.unit_price,

                    p.name,
                    p.slug,
                    p.image,
                    p.stock

                FROM cart_items ci

                INNER JOIN carts c
                    ON ci.cart_id = c.id

                INNER JOIN products p
                    ON ci.product_id = p.id

                WHERE c.user_id = :user_id

                ORDER BY ci.id DESC";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'user_id' => $userId
        ]);

        return $stmt->fetchAll();
    }
    public static function updateCartItem($userId, $cartItemId, $quantity)
    {
        global $pdo;

        // Make sure the cart item belongs to the logged-in user
        $sql = "SELECT
                ci.id,
                ci.product_id,
                p.stock
            FROM cart_items ci
            INNER JOIN carts c
                ON ci.cart_id = c.id
            INNER JOIN products p
                ON ci.product_id = p.id
            WHERE ci.id = :cart_item_id
            AND c.user_id = :user_id
            LIMIT 1";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'cart_item_id' => $cartItemId,
            'user_id' => $userId
        ]);

        $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cartItem) {
            throw new Exception("Cart item not found.");
        }

        // Quantity must be at least 1
        if ($quantity < 1) {
            throw new Exception("Quantity must be at least 1.");
        }

        // Quantity cannot be greater than available stock
        if ($quantity > $cartItem['stock']) {
            throw new Exception("Not enough stock available.");
        }

        // Update quantity
        $sql = "UPDATE cart_items
            SET quantity = :quantity
            WHERE id = :cart_item_id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'quantity' => $quantity,
            'cart_item_id' => $cartItemId
        ]);

        return true;
    }


    public static function removeCartItem($userId, $cartItemId)
    {
        global $pdo;

        $sql = "DELETE ci
            FROM cart_items ci
            INNER JOIN carts c
                ON ci.cart_id = c.id
            WHERE ci.id = :cart_item_id
            AND c.user_id = :user_id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'cart_item_id' => $cartItemId,
            'user_id' => $userId
        ]);

        if ($stmt->rowCount() === 0) {
            throw new Exception("Cart item not found.");
        }

        return true;
    }

    public static function clearCart($userId)
{
    global $pdo;

    $sql = "DELETE ci
            FROM cart_items ci
            INNER JOIN carts c
                ON ci.cart_id = c.id
            WHERE c.user_id = :user_id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'user_id' => $userId
    ]);

    return true;
}
}