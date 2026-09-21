<?php

require_once '../../core/Middleware.php';
require_once '../../config/database.php';
require_once '../../core/Session.php';

Middleware::admin();
Session::start();


// Only allow POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    Session::setFlash(
        'error',
        'Invalid delete request.'
    );

    header('Location: index.php');
    exit;
}


// Get product ID
$productId = (int) ($_POST['product_id'] ?? 0);


// Validate product ID
if ($productId <= 0) {

    Session::setFlash(
        'error',
        'Invalid product ID.'
    );

    header('Location: index.php');
    exit;
}


// Fetch product
$sql = "SELECT id, name, image
        FROM products
        WHERE id = :id
        LIMIT 1";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':id' => $productId
]);

$product = $stmt->fetch();


// Product not found
if (!$product) {

    Session::setFlash(
        'error',
        'Product not found.'
    );

    header('Location: index.php');
    exit;
}


// Delete product from database
$sql = "DELETE FROM products
        WHERE id = :id";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':id' => $productId
]);


// Delete product image
if (!empty($product['image'])) {

    $imagePath =
        '../../public/uploads/products/'
        . $product['image'];

    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}


// Success message
Session::setFlash(
    'success',
    'Product deleted successfully.'
);


// Redirect back to products
header('Location: index.php');
exit;