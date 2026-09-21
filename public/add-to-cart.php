<?php

require_once '../core/Middleware.php';
Middleware::customer();

require_once '../core/Auth.php';
require_once '../core/Cart.php';


// Get product ID
$productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);


// Get quantity
$quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);


// Validate product ID
if (!$productId) {

    header("Location: index.php");
    exit;

}


// Validate quantity
if (!$quantity || $quantity < 1) {

    $quantity = 1;

}


// Get logged-in user ID
$userId = Auth::user()['id'];


// Add product to cart
try {

    Cart::addToCart(
        $userId,
        $productId,
        $quantity
    );

    header("Location: cart.php");
    exit;

} catch (Exception $e) {

    die($e->getMessage());

}
