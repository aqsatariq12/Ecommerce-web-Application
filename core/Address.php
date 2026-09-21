<?php

require_once __DIR__ . '/../config/database.php';

class Address
{
    public static function getByUserId($userId)
    {
        global $pdo;

        $sql = "SELECT *
                FROM addresses
                WHERE user_id = :user_id
                LIMIT 1";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'user_id' => $userId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public static function create($userId, $data)
    {
        global $pdo;

        $sql = "INSERT INTO addresses (
                    user_id,
                    full_name,
                    phone,
                    country,
                    address_line1,
                    address_line2,
                    city,
                    state,
                    postcode
                )
                VALUES (
                    :user_id,
                    :full_name,
                    :phone,
                    :country,
                    :address_line1,
                    :address_line2,
                    :city,
                    :state,
                    :postcode
                )";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'user_id' => $userId,
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'country' => $data['country'],
            'address_line1' => $data['address_line1'],
            'address_line2' => $data['address_line2'] ?? null,
            'city' => $data['city'],
            'state' => $data['state'],
            'postcode' => $data['postcode']
        ]);
    }


    public static function update($userId, $data)
    {
        global $pdo;

        $sql = "UPDATE addresses
                SET
                    full_name = :full_name,
                    phone = :phone,
                    country = :country,
                    address_line1 = :address_line1,
                    address_line2 = :address_line2,
                    city = :city,
                    state = :state,
                    postcode = :postcode
                WHERE user_id = :user_id";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'user_id' => $userId,
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'country' => $data['country'],
            'address_line1' => $data['address_line1'],
            'address_line2' => $data['address_line2'] ?? null,
            'city' => $data['city'],
            'state' => $data['state'],
            'postcode' => $data['postcode']
        ]);
    }
}