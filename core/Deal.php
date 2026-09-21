<?php

require_once __DIR__ . '/../config/database.php';

class Deal
{
    public static function getActiveDeals()
    {
        global $pdo;

        $sql = "SELECT
                    d.id,
                    d.title,
                    d.subtitle,
                    d.old_price,
                    d.countdown_until,
                    d.background_image,
                    d.button_text,
                    d.button_link,

                    p.id AS product_id,
                    p.name AS product_name,
                    p.price AS product_price,
                    p.image AS product_image

                FROM deals d

                INNER JOIN products p
                    ON d.product_id = p.id

                WHERE d.status = 1
                AND p.status = 1

                ORDER BY d.id ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}