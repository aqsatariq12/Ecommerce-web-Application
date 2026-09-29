<?php

require_once '../core/Middleware.php';
Middleware::customer();

require_once '../core/Auth.php';
require_once '../core/Cart.php';
require_once '../core/Shipping.php';
require_once '../core/Session.php';
require_once '../config/stripe.php';

Session::start();

$user = Auth::user();

/*
 * Get cart
 */
$cartItems = Cart::getCartItems($user['id']);

if (empty($cartItems)) {
    die("Your cart is empty.");
}

/*
 * Get selected shipping method
 */
$selectedShippingId = Session::get('shipping_id');

$shippingMethod = null;

if ($selectedShippingId) {
    $shippingMethod = Shipping::getMethodById($selectedShippingId);
}

if (!$shippingMethod) {
    die("Invalid shipping method.");
}

/*
 * Calculate subtotal
 */
$subtotal = 0;

foreach ($cartItems as $item) {
    $subtotal +=
        (float) $item['unit_price'] *
        (int) $item['quantity'];
}

/*
 * Shipping cost
 */
$shippingCost = (float) $shippingMethod['cost'];

/*
 * Grand total
 */
$total = $subtotal + $shippingCost;

/*
 * Stripe amount is in cents
 *
 * Example:
 * $100.00 = 10000 cents
 */
$amountInCents = (int) round($total * 100);

try {

    /*
     * Create Stripe Checkout Session
     */
    $checkoutSession = \Stripe\Checkout\Session::create([

        'mode' => 'payment',

        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',

                'product_data' => [
                    'name' => 'ClothWear Order',
                ],

                'unit_amount' => $amountInCents,
            ],

            'quantity' => 1,
        ]],

        'customer_email' => $user['email'],

        'success_url' =>
            'http://clothwear.local/public/stripe-success.php?session_id={CHECKOUT_SESSION_ID}',

        'cancel_url' =>
            'http://clothwear.local/public/checkout.php',

        'metadata' => [
            'user_id' => (string) $user['id'],
        ],
    ]);

    /*
     * Redirect customer to Stripe
     */
    header(
        "Location: " . $checkoutSession->url
    );

    exit;

} catch (Exception $e) {

    die(
        "Stripe payment could not be started: " .
        $e->getMessage()
    );
}