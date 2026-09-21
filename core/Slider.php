<?php

require_once __DIR__ . '/../config/database.php';

class Slider
{
    public static function getActiveSliders()
    {
        global $pdo;

        $sql = "SELECT
                    id,
                    title,
                    subtitle,
                    old_price,
                    price,
                    image,
                    button_text,
                    button_link
                FROM sliders
                WHERE status = 1
                ORDER BY id ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}