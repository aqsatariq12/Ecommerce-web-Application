<?php
require_once __DIR__ . '/../config/database.php';
class Category
{
    public static function getActiveCategories()
    {
        global $pdo;
        $sql = "SELECT id, name, slug, image FROM categories 
                WHERE status = 1 
                ORDER BY id ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getById($id)
    {
        global $pdo;

        $id = (int) $id;

        $sql = "SELECT
                id,
                name,
                slug,
                image,
                status,
                created_at
            FROM categories
            WHERE categories.id = :category_id
            AND categories.status = 1
            LIMIT 1";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':category_id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>