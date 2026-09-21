<?php

require_once '../core/Middleware.php';
Middleware::customer();

require_once '../core/Auth.php';
require_once '../core/Cart.php';
require_once '../core/Shipping.php';
require_once '../core/Session.php';
require_once '../core/Address.php';
require_once '../core/Order.php';

Session::start();

$user = Auth::user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$fullName = trim($_POST['full_name'] ?? '');
	$phone = trim($_POST['phone'] ?? '');
	$country = trim($_POST['country'] ?? '');
	$addressLine1 = trim($_POST['address_line1'] ?? '');
	$addressLine2 = trim($_POST['address_line2'] ?? '');
	$city = trim($_POST['city'] ?? '');
	$state = trim($_POST['state'] ?? '');
	$postcode = trim($_POST['postcode'] ?? '');

	$paymentMethod = $_POST['payment_method'] ?? '';

	/*
	 * Validate payment method
	 */

	if (!in_array($paymentMethod, ['cod', 'paypal'], true)) {
		die("Invalid payment method.");
	}

	/*
	 * Validate billing details
	 */

	if (
		$fullName === '' ||
		$phone === '' ||
		$country === '' ||
		$addressLine1 === '' ||
		$city === '' ||
		$state === '' ||
		$postcode === ''
	) {
		die("Please fill in all required billing fields.");
	}

	/*
	 * Save address
	 */

	$addressData = [
		'full_name' => $fullName,
		'phone' => $phone,
		'country' => $country,
		'address_line1' => $addressLine1,
		'address_line2' => $addressLine2,
		'city' => $city,
		'state' => $state,
		'postcode' => $postcode
	];

	$existingAddress = Address::getByUserId($user['id']);

	if ($existingAddress) {

		Address::update(
			$user['id'],
			$addressData
		);

	} else {

		Address::create(
			$user['id'],
			$addressData
		);
	}

	/*
	 * Get cart again from database
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
		$shippingMethod = Shipping::getMethodById(
			$selectedShippingId
		);
	}

	if (!$shippingMethod) {
		die("Invalid shipping method.");
	}

	/*
	 * PayPal will be implemented in the next phase.
	 *
	 * For now only COD creates an order.
	 */

	if ($paymentMethod === 'paypal') {
		die("PayPal payment will be implemented next.");
	}

	/*
	 * Create COD order
	 */

	try {

		$order = Order::create(
			$user['id'],
			$cartItems,
			$addressData,
			$shippingMethod,
			$paymentMethod
		);

		/*
		 * Remove purchased items from cart
		 */

		Cart::clearCart($user['id']);

		/*
		 * Remove shipping selection from session
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
			"Order could not be created: " .
			$e->getMessage()
		);
	}
}

$address = Address::getByUserId($user['id']);

$cartItems = Cart::getCartItems($user['id']);
if (empty($cartItems)) {
	header("Location: cart.php");
	exit;
}

// Calculate Subtotal

$subtotal = 0;

foreach ($cartItems as $item) {
	$subtotal += $item['unit_price'] * $item['quantity'];
}

// Get Selected Shipping


$shippingMethods = Shipping::getActiveMethods();

$selectedShippingId = Session::get('shipping_id');

$shippingMethod = null;

if ($selectedShippingId) {
	$shippingMethod = Shipping::getMethodById($selectedShippingId);
}


//If no valid shipping method is selected,
//use the first active method

if (!$shippingMethod && !empty($shippingMethods)) {

	$shippingMethod = $shippingMethods[0];

	Session::set(
		'shipping_id',
		$shippingMethod['id']
	);
}

$shippingCost = $shippingMethod['cost'] ?? 0;

// Grand Total

$total = $subtotal + $shippingCost;

?>

<!DOCTYPE html>
<html lang="en">


<!-- molla/checkout.php  22 Nov 2019 09:55:06 GMT -->

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Molla - Bootstrap eCommerce Template</title>
	<meta name="keywords" content="HTML5 Template">
	<meta name="description" content="Molla - Bootstrap eCommerce Template">
	<meta name="author" content="p-themes">
	<!-- Favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="assets/images/icons/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="assets/images/icons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="assets/images/icons/favicon-16x16.png">
	<link rel="manifest" href="assets/images/icons/site.html">
	<link rel="mask-icon" href="assets/images/icons/safari-pinned-tab.svg" color="#666666">
	<link rel="shortcut icon" href="assets/images/icons/favicon.ico">
	<meta name="apple-mobile-web-app-title" content="Molla">
	<meta name="application-name" content="Molla">
	<meta name="msapplication-TileColor" content="#cc9966">
	<meta name="msapplication-config" content="assets/images/icons/browserconfig.xml">
	<meta name="theme-color" content="#ffffff">
	<!-- Plugins CSS File -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<!-- Main CSS File -->
	<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
	<div class="page-wrapper">
		<?php include '../includes/header.php'; ?>
		<main class="main">
			<div class="page-header text-center" style="background-image: url('assets/images/page-header-bg.jpg')">
				<div class="container">
					<h1 class="page-title">Checkout<span>Shop</span></h1>
				</div><!-- End .container -->
			</div><!-- End .page-header -->
			<nav aria-label="breadcrumb" class="breadcrumb-nav">
				<div class="container">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php">Home</a></li>
						<li class="breadcrumb-item"><a href="#">Shop</a></li>
						<li class="breadcrumb-item active" aria-current="page">Checkout</li>
					</ol>
				</div><!-- End .container -->
			</nav><!-- End .breadcrumb-nav -->

			<div class="page-content">
				<div class="checkout">
					<div class="container">
						<form action="checkout.php" method="POST">
							<div class="row">
								<div class="col-lg-9">
									<h2 class="checkout-title">Billing Details</h2><!-- End .checkout-title -->
									<div class="row">

										<div class="col-sm-12">

											<label>Full Name *</label>

											<input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars(
												$address['full_name'] ?? $user['name']
											) ?>" required>

										</div>

									</div><!-- End .row -->

									<label>Country *</label>
									<input type="text" class="form-control" name="country"
										value="<?= htmlspecialchars($address['country'] ?? '') ?>" required>

									<label>Street address *</label>

									<input type="text" name="address_line1" class="form-control"
										placeholder="House number and Street name"
										value="<?= htmlspecialchars($address['address_line1'] ?? '') ?>" required>

									<input type="text" name="address_line2" class="form-control"
										placeholder="Apartment, suite, unit etc ..."
										value="<?= htmlspecialchars($address['address_line2'] ?? '') ?>">
									<div class="row">

										<div class="col-sm-6">

											<label>Town / City *</label>

											<input type="text" name="city" class="form-control"
												value="<?= htmlspecialchars($address['city'] ?? '') ?>" required>

										</div>

										<div class="col-sm-6">

											<label>State *</label>

											<input type="text" name="state" class="form-control"
												value="<?= htmlspecialchars($address['state'] ?? '') ?>" required>

										</div>

									</div>
									<div class="row">
										<div class="col-sm-6">

											<label>Postcode / ZIP *</label>

											<input type="text" name="postcode" class="form-control"
												value="<?= htmlspecialchars($address['postcode'] ?? '') ?>" required>

										</div>

										<div class="col-sm-6">

											<label>Phone *</label>

											<input type="tel" name="phone" class="form-control"
												value="<?= htmlspecialchars($address['phone'] ?? '') ?>" required>

										</div>
									</div>

									<label>Email address *</label>
									<input type="email" name="email" class="form-control"
										value="<?= htmlspecialchars($user["email"]) ?>" required>

									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input" id="checkout-create-acc">
										<label class="custom-control-label" for="checkout-create-acc">Create an
											account?</label>
									</div><!-- End .custom-checkbox -->

									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input" id="checkout-diff-address">
										<label class="custom-control-label" for="checkout-diff-address">Ship to a
											different address?</label>
									</div><!-- End .custom-checkbox -->

									<label>Order notes (optional)</label>
									<textarea class="form-control" cols="30" rows="4"
										placeholder="Notes about your order, e.g. special notes for delivery"></textarea>
								</div><!-- End .col-lg-9 -->
								<aside class="col-lg-3">
									<div class="summary">
										<h3 class="summary-title">Your Order</h3><!-- End .summary-title -->

										<table class="table table-summary">
											<thead>
												<tr>
													<th>Product</th>
													<th>Total</th>
												</tr>
											</thead>

											<tbody>
												<?php foreach ($cartItems as $item): ?>

													<tr>
														<td>
															<?= htmlspecialchars($item['name']) ?>

															<span class="product-qty">
																× <?= (int) $item['quantity'] ?>
															</span>
														</td>

														<td>
															$<?= number_format(
																$item['unit_price'] * $item['quantity'],
																2
															) ?>
														</td>
													</tr>

												<?php endforeach; ?>
												<tr class="summary-subtotal">
													<td>Subtotal:</td>
													<td> $
														<?= number_format($subtotal, 2) ?>
													</td>
												</tr><!-- End .summary-subtotal -->
												<tr>
													<td>Shipping:</td>
													<td>
														<?= htmlspecialchars($shippingMethod['name']) ?>

														-
														$<?= number_format($shippingCost, 2) ?>
													</td>
												</tr>
												<tr class="summary-subtotal">
													<td style="font-weight: bold;">GRAND TOTAL:</td>
													<td style="font-weight: bold;">
														$
														<?= number_format($total, 2) ?>
													</td>
												</tr><!-- End .summary-total -->
											</tbody>
										</table><!-- End .table table-summary -->

										<div class="accordion-summary" id="accordion-payment">

											<!-- Cash on Delivery -->

											<div class="card">

												<div class="card-header" id="heading-cod">

													<h2 class="card-title">

														<label for="payment-cod" style="cursor: pointer; margin: 0;">

															<input type="radio" id="payment-cod" name="payment_method"
																value="cod" checked>

															Cash on Delivery

														</label>

													</h2>

												</div>

												<div class="card-body">

													Pay when your order is delivered to you.

												</div>

											</div>


											<!-- PayPal -->

											<div class="card">

												<div class="card-header" id="heading-paypal">

													<h2 class="card-title">

														<label for="payment-paypal" style="cursor: pointer; margin: 0;">

															<input type="radio" id="payment-paypal"
																name="payment_method" value="paypal">

															PayPal

														</label>

													</h2>

												</div>

												<div class="card-body">

													Pay securely using PayPal Sandbox.

												</div>

											</div>

										</div>
										<!-- End .accordion -->

										<button type="submit" 
											class="btn btn-outline-primary-2 btn-order btn-block">
											<span class="btn-text">Place Order</span>
											<span class="btn-hover-text">Proceed to Checkout</span>
										</button>
									</div><!-- End .summary -->
								</aside><!-- End .col-lg-3 -->
							</div><!-- End .row -->
						</form>
					</div><!-- End .container -->
				</div><!-- End .checkout -->
			</div><!-- End .page-content -->
		</main><!-- End .main -->

		<?php include '../includes/footer.php'; ?>
	</div><!-- End .page-wrapper -->
	<button id="scroll-top" title="Back to Top"><i class="icon-arrow-up"></i></button>


	<!-- Plugins JS File -->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<script src="assets/js/jquery.hoverIntent.min.js"></script>
	<script src="assets/js/jquery.waypoints.min.js"></script>
	<script src="assets/js/superfish.min.js"></script>
	<script src="assets/js/owl.carousel.min.js"></script>
	<!-- Main JS File -->
	<script src="assets/js/main.js"></script>
</body>


<!-- molla/checkout.php  22 Nov 2019 09:55:06 GMT -->

</html>