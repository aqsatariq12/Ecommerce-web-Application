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
             * 2. Insert order items
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

            foreach ($cartItems as $item) {

                $itemSubtotal =
                    $item['unit_price'] * $item['quantity'];

                $itemStmt->execute([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
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

    $sql = "UPDATE orders
            SET order_status = 'cancelled'
            WHERE id = :order_id
            AND user_id = :user_id
            AND order_status = 'processing'";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'order_id' => $orderId,
        'user_id' => $userId
    ]);

    if ($stmt->rowCount() === 0) {
        throw new Exception(
            "Order cannot be cancelled."
        );
    }

    return true;
}
}