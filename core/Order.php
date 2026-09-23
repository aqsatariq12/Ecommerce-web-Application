<?php

require_once __DIR__ . '/../config/database.php';

class Order
{
    public static function create(
        $userId,
        $cartItems,
        $address,
        $shippingMethod,
        $paymentMethod
    ) {
        global $pdo;

        // Calculate subtotal from database cart items
        $subtotal = 0;

        foreach ($cartItems as $item) {
            $subtotal += $item['unit_price'] * $item['quantity'];
        }

        // Shipping cost
        $shippingCost = $shippingMethod['cost'];

        // Grand total
        $total = $subtotal + $shippingCost;

        // Generate order number
        $orderNumber = 'CW-' . date('YmdHis') . '-' . random_int(100, 999);

        // Payment status
        $paymentStatus = 'pending';

        // Order status
        $orderStatus = 'processing';

        try {

            $pdo->beginTransaction();

            /*
             * 1. Create order
             */

            $sql = "INSERT INTO orders (
                        user_id,
                        billing_full_name,
                        billing_phone,
                        billing_country,
                        billing_address_line1,
                        billing_address_line2,
                        billing_city,
                        billing_state,
                        billing_postcode,
                        order_number,
                        subtotal_amount,
                        shipping_method_id,
                        shipping_method_name,
                        shipping_cost,
                        total_amount,
                        payment_method,
                        payment_status,
                        order_status
                    )
                    VALUES (
                        :user_id,
                        :billing_full_name,
                        :billing_phone,
                        :billing_country,
                        :billing_address_line1,
                        :billing_address_line2,
                        :billing_city,
                        :billing_state,
                        :billing_postcode,
                        :order_number,
                        :subtotal_amount,
                        :shipping_method_id,
                        :shipping_method_name,
                        :shipping_cost,
                        :total_amount,
                        :payment_method,
                        :payment_status,
                        :order_status
                    )";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                'user_id' => $userId,

                'billing_full_name' => $address['full_name'],
                'billing_phone' => $address['phone'],
                'billing_country' => $address['country'],
                'billing_address_line1' => $address['address_line1'],
                'billing_address_line2' => $address['address_line2'] ?? null,
                'billing_city' => $address['city'],
                'billing_state' => $address['state'],
                'billing_postcode' => $address['postcode'],

                'order_number' => $orderNumber,

                'subtotal_amount' => $subtotal,

                'shipping_method_id' => $shippingMethod['id'],
                'shipping_method_name' => $shippingMethod['name'],
                'shipping_cost' => $shippingCost,

                'total_amount' => $total,

                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'order_status' => $orderStatus
            ]);

            /*
             * Get newly created order ID
             */

            $orderId = $pdo->lastInsertId();

            /*
             * 2. Check stock, reduce stock and insert order items
             */

            $itemSql = "INSERT INTO order_items (
                order_id,
                product_id,
                quantity,
                unit_price,
                subtotal
            )
            VALUES (
                :order_id,
                :product_id,
                :quantity,
                :unit_price,
                :subtotal
            )";

            $itemStmt = $pdo->prepare($itemSql);


            /*
             * Update product stock
             */

            $stockSql = "UPDATE products
             SET stock = stock - :quantity
             WHERE id = :product_id
             AND stock >= :quantity";

            $stockStmt = $pdo->prepare($stockSql);


            foreach ($cartItems as $item) {

                $quantity = (int) $item['quantity'];
                $productId = (int) $item['product_id'];

                /*
                 * Reduce stock only if enough stock is available
                 */

                $stockStmt->execute([
                    'quantity' => $quantity,
                    'product_id' => $productId
                ]);

                /*
                 * If no row was updated,
                 * product does not have enough stock.
                 */

                if ($stockStmt->rowCount() === 0) {

                    throw new Exception(
                        "Insufficient stock for product: " .
                        $item['name']
                    );
                }


                /*
                 * Calculate item subtotal
                 */

                $itemSubtotal =
                    $item['unit_price'] * $quantity;


                /*
                 * Insert order item
                 */

                $itemStmt->execute([
                    'order_id' => $orderId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $itemSubtotal
                ]);
            }

            /*
             * Everything successful
             */

            $pdo->commit();

            return [
                'id' => $orderId,
                'order_number' => $orderNumber,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total
            ];

        } catch (Exception $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $e;
        }
    }

    public static function getByOrderNumberAndUserId($orderNumber, $userId)
    {
        global $pdo;

        $sql = "SELECT *
            FROM orders
            WHERE order_number = :order_number
            AND user_id = :user_id
            LIMIT 1";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'order_number' => $orderNumber,
            'user_id' => $userId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public static function getItems($orderId)
    {
        global $pdo;

        $sql = "SELECT
                oi.id,
                oi.order_id,
                oi.product_id,
                oi.quantity,
                oi.unit_price,
                oi.subtotal,

                p.name,
                p.slug,
                p.image

            FROM order_items oi

            INNER JOIN products p
                ON oi.product_id = p.id

            WHERE oi.order_id = :order_id

            ORDER BY oi.id ASC";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'order_id' => $orderId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function getByUserId($userId)
    {
        global $pdo;

        $sql = "SELECT 
                id,
                order_number,
                subtotal_amount,
                shipping_method_name,
                shipping_cost,
                total_amount,
                payment_method,
                payment_status,
                order_status,
                created_at
            FROM orders
            WHERE user_id = :user_id
            ORDER BY id DESC";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function cancel($orderId, $userId)
    {
        global $pdo;

        try {

            $pdo->beginTransaction();

            /*
             * Get the order
             */

            $orderSql = "SELECT id, order_status
                     FROM orders
                     WHERE id = :order_id
                     AND user_id = :user_id
                     LIMIT 1";

            $orderStmt = $pdo->prepare($orderSql);

            $orderStmt->execute([
                'order_id' => $orderId,
                'user_id' => $userId
            ]);

            $order = $orderStmt->fetch();

            if (!$order) {
                throw new Exception("Order not found.");
            }


            /*
             * Only processing orders can be cancelled
             */

            if ($order['order_status'] !== 'processing') {
                throw new Exception(
                    "Only processing orders can be cancelled."
                );
            }


            /*
             * Get order items
             */

            $itemsSql = "SELECT product_id, quantity
                     FROM order_items
                     WHERE order_id = :order_id";

            $itemsStmt = $pdo->prepare($itemsSql);

            $itemsStmt->execute([
                'order_id' => $orderId
            ]);

            $items = $itemsStmt->fetchAll();


            /*
             * Restore stock
             */

            $stockSql = "UPDATE products
                     SET stock = stock + :quantity
                     WHERE id = :product_id";

            $stockStmt = $pdo->prepare($stockSql);

            foreach ($items as $item) {

                $stockStmt->execute([
                    'quantity' => (int) $item['quantity'],
                    'product_id' => (int) $item['product_id']
                ]);
            }


            /*
             * Cancel order
             */

            $cancelSql = "UPDATE orders
                      SET order_status = 'cancelled'
                      WHERE id = :order_id
                      AND user_id = :user_id
                      AND order_status = 'processing'";

            $cancelStmt = $pdo->prepare($cancelSql);

            $cancelStmt->execute([
                'order_id' => $orderId,
                'user_id' => $userId
            ]);

            if ($cancelStmt->rowCount() === 0) {
                throw new Exception(
                    "Order could not be cancelled."
                );
            }


            /*
             * Everything successful
             */

            $pdo->commit();

            return true;

        } catch (Exception $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $e;
        }
    }
}