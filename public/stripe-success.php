<?php

require_once '../core/Middleware.php';
Middleware::customer();

require_once '../core/Auth.php';
require_once '../core/Cart.php';
require_once '../core/Shipping.php';
require_once '../core/Session.php';
require_once '../core/Address.php';
require_once '../core/Order.php';
require_once '../config/stripe.php';

Session::start();

$user = Auth::user();

/*
 * Get Stripe Checkout Session ID
 */

$sessionId = $_GET['session_id'] ?? '';

if ($sessionId === '') {
    die("Invalid Stripe session.");
}

try {

    /*
     * Retrieve the Stripe Checkout Session
     */

    $stripeSession = \Stripe\Checkout\Session::retrieve(
        $sessionId
    );

    /*
     * Make sure payment was actually completed
     */

    if ($stripeSession->payment_status !== 'paid') {

        die("Payment was not completed.");
    }

    /*
     * Get payment intent ID
     *
     * This will be saved as transaction_id
     */

    $transactionId = $stripeSession->payment_intent;

    /*
     * Get cart
     */

    $cartItems = Cart::getCartItems($user['id']);

    if (empty($cartItems)) {
        die("Your cart is empty.");
    }

    /*
     * Get saved address
     */

    $address = Address::getByUserId($user['id']);

    if (!$address) {
        die("Billing address not found.");
    }

    /*
     * Get selected shipping method
     */

    $selectedShippingId = Session::get('shipping_id');

    $shippingMethod = null;

    if ($selectedShippingId) {

        $shippingMethod = Shipping::getMethodById(
            $selectedShippingId
        );
    }

    if (!$shippingMethod) {
        die("Invalid shipping method.");
    }

    /*
     * Create order
     *
     * Order::create() will:
     *
     * - calculate fresh prices
     * - check stock
     * - reduce stock
     * - create order
     * - create order items
     */

    $order = Order::create(
        $user['id'],
        $cartItems,
        $address,
        $shippingMethod,
        'stripe'
    );

    /*
     * Update payment information
     */

    require_once '../config/database.php';

    $sql = "UPDATE orders
            SET payment_status = 'completed',
                transaction_id = :transaction_id
            WHERE id = :order_id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'transaction_id' => $transactionId,
        'order_id' => $order['id']
    ]);

    /*
     * Clear cart
     */

    Cart::clearCart($user['id']);

    /*
     * Remove shipping selection
     */

    Session::remove('shipping_id');

    /*
     * Redirect to order confirmation
     */

    header(
        "Location: order-confirmation.php?order=" .
        urlencode($order['order_number'])
    );

    exit;

} catch (Exception $e) {

    die(
        "Payment was successful, but the order could not be created: " .
        $e->getMessage()
    );
}