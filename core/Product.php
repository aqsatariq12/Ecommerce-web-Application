<?php
require_once __DIR__ . "/../config/database.php";

class Product
{
    public static function getNewArrivals()
    {
        global $pdo;
        $sql = "SELECT 
            p.id,
            p.category_id,
            p.name,
            p.slug,
            p.description,
            p.price,
            p.stock,
            p.image,
            c.name AS category_name
        FROM products p
        INNER JOIN categories c 
            ON p.category_id = c.id
        WHERE p.status = 1
        AND c.status = 1
        ORDER BY p.created_at DESC
        LIMIT 8";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getByCategory($categoryId)
    {
        global $pdo;

        $sql = "SELECT
                p.id,
                p.category_id,
                p.name,
                p.slug,
                p.description,
                p.price,
                p.stock,
                p.image,
                c.name AS category_name,
                c.slug AS category_slug

            FROM products p

            INNER JOIN categories c
                ON c.id = p.category_id

            WHERE p.category_id = :category_id
            AND p.status = 1
            AND c.status = 1

            ORDER BY p.created_at DESC";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'category_id' => $categoryId
        ]);

        return $stmt->fetchAll();
    }

    public static function getById($id)
    {
        global $pdo;

        $sql = "SELECT
                p.id,
                p.category_id,
                p.name,
                p.slug,
                p.description,
                p.price,
                p.stock,
                p.image,
                c.name AS category_name
            FROM products p
            INNER JOIN categories c
                ON p.category_id = c.id
            WHERE p.id = :id
            AND p.status = 1
            AND c.status = 1
            LIMIT 1";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getBestSelling()
    {
        global $pdo;

        $sql = "SELECT
                p.id,
                p.category_id,
                p.name,
                p.slug,
                p.description,
                p.price,
                p.stock,
                p.image,
                c.name AS category_name,
                SUM(oi.quantity) AS total_sold

            FROM order_items oi

            INNER JOIN orders o
                ON oi.order_id = o.id

            INNER JOIN products p
                ON oi.product_id = p.id

            INNER JOIN categories c
                ON p.category_id = c.id

            WHERE p.status = 1
            AND c.status = 1
            AND o.order_status != 'cancelled'

            GROUP BY
                p.id,
                p.category_id,
                p.name,
                p.slug,
                p.description,
                p.price,
                p.stock,
                p.image,
                c.name

            ORDER BY total_sold DESC

            LIMIT 8";

        $stmt = $pdo->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function getOneProductPerCategory($excludeProductId = null)
    {
        global $pdo;

        $sql = "SELECT
                p.id,
                p.category_id,
                p.name,
                p.slug,
                p.description,
                p.price,
                p.stock,
                p.image,
                c.name AS category_name
            FROM products p
            INNER JOIN categories c
                ON p.category_id = c.id
            WHERE p.status = 1
            AND c.status = 1";

        if ($excludeProductId) {
            $sql .= " AND p.id != :exclude_product_id";
        }

        $sql .= " AND p.id = (
                    SELECT MIN(p2.id)
                    FROM products p2
                    WHERE p2.category_id = p.category_id
                    AND p2.status = 1
                )
                ORDER BY c.name ASC";

        $stmt = $pdo->prepare($sql);

        if ($excludeProductId) {
            $stmt->execute([
                'exclude_product_id' => $excludeProductId
            ]);
        } else {
            $stmt->execute();
        }

        return $stmt->fetchAll();
    }



    public static function getAllGroupedByCategory()
    {
        global $pdo;

        $sql = "SELECT
                p.id,
                p.category_id,
                p.name,
                p.slug,
                p.description,
                p.price,
                p.stock,
                p.image,
                c.name AS category_name,
                c.slug AS category_slug

            FROM products p

            INNER JOIN categories c
                ON p.category_id = c.id

            WHERE p.status = 1
            AND c.status = 1

            ORDER BY
                c.name ASC,
                p.created_at DESC";

        $stmt = $pdo->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll();
    }
}
?>