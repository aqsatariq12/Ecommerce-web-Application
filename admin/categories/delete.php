<?php

require_once '../../core/Middleware.php';
require_once '../../config/database.php';
require_once '../../core/Session.php';

Middleware::admin();
Session::start();


// ONLY ALLOW POST REQUEST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Session::setFlash('error', 'Invalid delete request.');
    header('Location: index.php');
    exit;
}


// GET CATEGORY ID
$categoryId = (int) ($_POST['category_id'] ?? 0);

if ($categoryId <= 0) {
    Session::setFlash('error', 'Invalid category ID.');
    header('Location: index.php');
    exit;
}


// FETCH CATEGORY
$sql = "SELECT id, name, image
        FROM categories
        WHERE id = :id
        LIMIT 1";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':id' => $categoryId
]);

$category = $stmt->fetch();


// CATEGORY NOT FOUND
if (!$category) {
    Session::setFlash('error', 'Category not found.');
    header('Location: index.php');
    exit;
}


// CHECK IF CATEGORY HAS PRODUCTS
$sql = "SELECT COUNT(*) 
        FROM products
        WHERE category_id = :category_id";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':category_id' => $categoryId
]);

$productCount = (int) $stmt->fetchColumn();


// DON'T DELETE CATEGORY IF PRODUCTS EXIST
if ($productCount > 0) {

    Session::setFlash(
        'error',
        'Cannot delete this category because it has products assigned to it.'
    );

    header('Location: index.php');
    exit;
}


// DELETE CATEGORY
$sql = "DELETE FROM categories
        WHERE id = :id";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':id' => $categoryId
]);


// DELETE CATEGORY IMAGE
if (!empty($category['image'])) {

    $imagePath =
        '../../public/uploads/categories/' . $category['image'];

    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}


// SUCCESS MESSAGE
Session::setFlash(
    'success',
    'Category deleted successfully.'
);

header('Location: index.php');
exit;