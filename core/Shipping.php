<?php

require_once __DIR__ . '/../config/database.php';

class Shipping
{
    public static function getActiveMethods()
    {
        global $pdo;

        $sql = "SELECT
                    id,
                    name,
                    cost
                FROM shipping_methods
                WHERE status = 1
                ORDER BY cost ASC";

        $stmt = $pdo->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll();
    }


    public static function getMethodById($shippingId)
    {
        global $pdo;

        $sql = "SELECT
                    id,
                    name,
                    cost
                FROM shipping_methods
                WHERE id = :id
                AND status = 1
                LIMIT 1";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'id' => $shippingId
        ]);

        return $stmt->fetch();
    }
}