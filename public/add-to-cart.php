<?php

require_once '../core/Middleware.php';
Middleware::customer();

require_once '../core/Auth.php';
require_once '../core/Cart.php';
require_once '../core/Session.php';

Session::start();


// Get product ID
$productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);


// Get quantity
$quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);


// Validate product ID
if (!$productId) {

    Session::setFlash('error', 'Invalid product.');

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


    // Success message
    Session::setFlash(
        'success',
        'Item added to cart successfully!'
    );


    // Redirect back to the page
    $redirect = $_POST['redirect'] ?? 'index.php';

    header("Location: " . $redirect);
    exit;


} catch (Exception $e) {

    // Error message
    Session::setFlash(
        'error',
        $e->getMessage()
    );


    // Redirect back to the page
    $redirect = $_POST['redirect'] ?? 'index.php';

    header("Location: " . $redirect);
    exit;

}
