<?php

require_once __DIR__ . '/../config/database.php';

class Deal
{
    // ==================================================
    // GET ACTIVE DEALS
    // Used on storefront
    // ==================================================

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


    // ==================================================
    // GET ALL DEALS
    // Used in admin
    // ==================================================

    public static function getAllDeals()
    {
        global $pdo;

        $sql = "SELECT
                    d.id,
                    d.product_id,
                    d.title,
                    d.subtitle,
                    d.old_price,
                    d.countdown_until,
                    d.background_image,
                    d.button_text,
                    d.button_link,
                    d.status,
                    d.created_at,

                    p.name AS product_name,
                    p.price AS product_price,
                    p.image AS product_image

                FROM deals d

                INNER JOIN products p
                    ON d.product_id = p.id

                ORDER BY d.id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }


    // ==================================================
    // GET SINGLE DEAL
    // Used in admin edit
    // ==================================================

    public static function getDealById($id)
    {
        global $pdo;

        $sql = "SELECT
                    d.id,
                    d.product_id,
                    d.title,
                    d.subtitle,
                    d.old_price,
                    d.countdown_until,
                    d.background_image,
                    d.button_text,
                    d.button_link,
                    d.status,
                    d.created_at,

                    p.name AS product_name,
                    p.price AS product_price,
                    p.image AS product_image

                FROM deals d

                INNER JOIN products p
                    ON d.product_id = p.id

                WHERE d.id = :id

                LIMIT 1";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch();
    }


    // ==================================================
    // CREATE DEAL
    // ==================================================

    public static function create($data)
    {
        global $pdo;

        $sql = "INSERT INTO deals (
                    product_id,
                    title,
                    subtitle,
                    old_price,
                    countdown_until,
                    background_image,
                    button_text,
                    button_link,
                    status
                )

                VALUES (
                    :product_id,
                    :title,
                    :subtitle,
                    :old_price,
                    :countdown_until,
                    :background_image,
                    :button_text,
                    :button_link,
                    :status
                )";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            ':product_id' => $data['product_id'],
            ':title' => $data['title'],
            ':subtitle' => $data['subtitle'],
            ':old_price' => $data['old_price'],
            ':countdown_until' => $data['countdown_until'],
            ':background_image' => $data['background_image'],
            ':button_text' => $data['button_text'],
            ':button_link' => $data['button_link'],
            ':status' => $data['status']
        ]);
    }


    // ==================================================
    // UPDATE DEAL
    // ==================================================

    public static function update($id, $data)
    {
        global $pdo;

        $sql = "UPDATE deals

                SET
                    product_id = :product_id,
                    title = :title,
                    subtitle = :subtitle,
                    old_price = :old_price,
                    countdown_until = :countdown_until,
                    background_image = :background_image,
                    button_text = :button_text,
                    button_link = :button_link,
                    status = :status

                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':product_id' => $data['product_id'],
            ':title' => $data['title'],
            ':subtitle' => $data['subtitle'],
            ':old_price' => $data['old_price'],
            ':countdown_until' => $data['countdown_until'],
            ':background_image' => $data['background_image'],
            ':button_text' => $data['button_text'],
            ':button_link' => $data['button_link'],
            ':status' => $data['status']
        ]);
    }


    // ==================================================
    // DELETE DEAL
    // ==================================================

    public static function delete($id)
    {
        global $pdo;

        $sql = "DELETE FROM deals
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }


    // ==================================================
    // UPDATE STATUS
    // ==================================================

    public static function updateStatus($id, $status)
    {
        global $pdo;

        $sql = "UPDATE deals

                SET status = :status

                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':status' => $status
        ]);
    }
}