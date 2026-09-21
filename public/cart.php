<?php

require_once '../core/Middleware.php';
Middleware::customer();

require_once '../core/Auth.php';
require_once '../core/Cart.php';
require_once '../core/Shipping.php';
require_once '../core/Session.php';

$user = Auth::user();
Session::start();
$shippingMethods = Shipping::getActiveMethods();
$selectedShippingId = Session::get('shipping_id');

if (!$selectedShippingId && !empty($shippingMethods)) {
	$selectedShippingId = $shippingMethods[0]['id'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	// UPDATE SHIPPING
	if (isset($_POST['update_shipping'])) {

		try {

			$shippingId = filter_input(
				INPUT_POST,
				'shipping',
				FILTER_VALIDATE_INT
			);

			if (!$shippingId) {
				throw new Exception("Please select a shipping method.");
			}

			$shippingMethod = Shipping::getMethodById($shippingId);

			if (!$shippingMethod) {
				throw new Exception("Invalid shipping method.");
			}

			Session::set('shipping_id', $shippingMethod['id']);

			header("Location: cart.php");
			exit;

		} catch (Exception $e) {

			die($e->getMessage());
		}
	}

	// UPDATE CART
	if (isset($_POST['update_cart'])) {

		try {

			$quantities = $_POST['quantity'] ?? [];
			$cartItemIds = $_POST['cart_item_id'] ?? [];

			foreach ($cartItemIds as $cartItemId) {

				$cartItemId = filter_var(
					$cartItemId,
					FILTER_VALIDATE_INT
				);

				if (!$cartItemId) {
					continue;
				}

				$quantity = $quantities[$cartItemId] ?? 1;

				$quantity = filter_var(
					$quantity,
					FILTER_VALIDATE_INT
				);

				if (!$quantity || $quantity < 1) {
					$quantity = 1;
				}

				Cart::updateCartItem(
					$user['id'],
					$cartItemId,
					$quantity
				);
			}

			header("Location: cart.php");
			exit;

		} catch (Exception $e) {

			die($e->getMessage());
		}
	}


	// REMOVE CART ITEM
	if (isset($_POST['remove_cart_item'])) {

		try {

			$cartItemId = filter_input(
				INPUT_POST,
				'remove_cart_item',
				FILTER_VALIDATE_INT
			);

			if (!$cartItemId) {
				throw new Exception("Invalid cart item.");
			}

			Cart::removeCartItem(
				$user['id'],
				$cartItemId
			);

			header("Location: cart.php");
			exit;

		} catch (Exception $e) {

			die($e->getMessage());
		}
	}
}


// GET CART ITEMS
$cartItems = Cart::getCartItems($user['id']);

// GET SELECTED SHIPPING METHOD
$selectedShippingId = Session::get('shipping_id');

$shippingMethod = null;

if ($selectedShippingId) {

	$shippingMethod = Shipping::getMethodById(
		$selectedShippingId
	);
}

if (!$shippingMethod && !empty($shippingMethods)) {

	$shippingMethod = $shippingMethods[0];

	Session::set(
		'shipping_id',
		$shippingMethod['id']
	);
}

$shippingCost = $shippingMethod['cost'] ?? 0;


// CALCULATE SUBTOTAL
$subtotal = 0;

foreach ($cartItems as $item) {

	$subtotal +=
		$item['unit_price'] * $item['quantity'];
}


// CALCULATE TOTAL
$total = $subtotal + $shippingCost;

?>
<!DOCTYPE html>
<html lang="en">


<!-- molla/cart.php  22 Nov 2019 09:55:06 GMT -->

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
					<h1 class="page-title">Shopping Cart<span>Shop</span></h1>
				</div><!-- End .container -->
			</div><!-- End .page-header -->
			<nav aria-label="breadcrumb" class="breadcrumb-nav">
				<div class="container">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php">Home</a></li>
						<li class="breadcrumb-item"><a href="#">Shop</a></li>
						<li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
					</ol>
				</div><!-- End .container -->
			</nav><!-- End .breadcrumb-nav -->

			<div class="page-content">
				<div class="cart">
					<div class="container">
						<div class="row">
							<div class="col-lg-9">
								<form action="cart.php" method="POST">

									<table class="table table-cart table-mobile">
										<thead>
											<tr>
												<th>Product</th>
												<th>Price</th>
												<th>Quantity</th>
												<th>Total</th>
												<th></th>
											</tr>
										</thead>


										<tbody>

											<?php if (empty($cartItems)): ?>

												<tr>

													<td colspan="5" class="text-center py-5">

														<h3>Your cart is empty</h3>

														<p class="mb-3">
															You haven't added any products to your cart yet.
														</p>

														<a href="products.php" class="btn btn-outline-primary-2">
															<span>CONTINUE SHOPPING</span>
														</a>

													</td>

												</tr>

											<?php else: ?>

												<?php foreach ($cartItems as $item): ?>

													<?php

													$itemTotal =
														$item['unit_price'] * $item['quantity'];

													?>

													<tr>

														<!-- Product -->
														<td class="product-col">

															<div class="product">

																<figure class="product-media">

																	<a
																		href="product-detail.php?id=<?= (int) $item['product_id'] ?>">

																		<img src="uploads/products/<?= htmlspecialchars($item['image']) ?>"
																			alt="<?= htmlspecialchars($item['name']) ?>">

																	</a>

																</figure>


																<h3 class="product-title">

																	<a
																		href="product-detail.php?id=<?= (int) $item['product_id'] ?>">

																		<?= htmlspecialchars($item['name']) ?>

																	</a>

																</h3>

															</div>

														</td>


														<!-- Price -->
														<td class="price-col">

															$<?= number_format($item['unit_price'], 2) ?>

														</td>




														<!-- Quantity -->

														<td class="quantity-col">

															<div class="cart-product-quantity">

																<input type="hidden" name="cart_item_id[]"
																	value="<?= (int) $item['id'] ?>">

																<input type="number" name="quantity[<?= (int) $item['id'] ?>]"
																	class="form-control" value="<?= (int) $item['quantity'] ?>"
																	min="1" max="<?= (int) $item['stock'] ?>" step="1"
																	data-decimals="0" required>

															</div>

														</td>



														<!-- Total -->
														<td class="total-col">

															$<?= number_format($itemTotal, 2) ?>

														</td>


														<!-- Remove -->
														<td class="remove-col">

															<input type="hidden" name="remove_cart_item_id" value="">

															<button type="submit" name="remove_cart_item"
																value="<?= (int) $item['id'] ?>" class="btn-remove">

																<i class="icon-close"></i>

															</button>

														</td>

													</tr>

												<?php endforeach; ?>

											<?php endif; ?>

										</tbody>


									</table><!-- End .table table-wishlist -->
									<div class="cart-bottom">

										<button type="submit" name="update_cart" class="btn btn-outline-dark-2">
											<span>UPDATE CART</span><i class="icon-refresh"></i>
										</button>

									</div>
								</form>

								<!-- End .cart-bottom -->
							</div><!-- End .col-lg-9 -->
							<aside class="col-lg-3">
								<div class="summary summary-cart">
									<h3 class="summary-title">Cart Total</h3><!-- End .summary-title -->
		<form action="cart.php" method="POST">

									<table class="table table-summary">
										<tbody>
											<tr class="summary-subtotal">
												<td>Subtotal:</td>
												<td> $<?= number_format($subtotal, 2) ?>
												</td>
											</tr><!-- End .summary-subtotal -->
											<tr class="summary-shipping">
												<td>Shipping:</td>
												<td>
													$<?= number_format($shippingCost, 2) ?>
												</td>
											</tr>


	<?php foreach ($shippingMethods as $method): ?>

		<tr class="summary-shipping-row">

			<td>
				<div class="custom-control custom-radio">

					<input
						type="radio"
						id="shipping-<?= (int) $method['id'] ?>"
						name="shipping"
						value="<?= (int) $method['id'] ?>"
						class="custom-control-input"
						<?= $selectedShippingId == $method['id'] ? 'checked' : '' ?>
						onchange="this.form.submit()"
					>

					<label
						class="custom-control-label"
						for="shipping-<?= (int) $method['id'] ?>">

						<?= htmlspecialchars($method['name']) ?>

					</label>

				</div>
			</td>

			<td>
				$<?= number_format($method['cost'], 2) ?>
			</td>

		</tr>

	<?php endforeach; ?>

	<input type="hidden" name="update_shipping" value="1">
<tr class="summary-total">
												<td>GRAND TOTAL:</td>
												<td>
													$<?= number_format($total, 2) ?>
												</td>
											</tr><!-- End .summary-total -->
											</tbody>
									</table>
</form>

											
										<!-- End .table table-summary -->

									<a href="checkout.php" class="btn btn-outline-primary-2 btn-order btn-block">PROCEED
										TO CHECKOUT</a>
								</div><!-- End .summary -->

								<a href="products.php" class="btn btn-outline-dark-2 btn-block mb-3"><span>CONTINUE
										SHOPPING</span><i class="icon-refresh"></i></a>
							</aside><!-- End .col-lg-3 -->
						</div><!-- End .row -->
					</div><!-- End .container -->
				</div><!-- End .cart -->
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
	<script src="assets/js/bootstrap-input-spinner.js"></script>
	<!-- Main JS File -->
	<script src="assets/js/main.js"></script>
</body>


<!-- molla/cart.php  22 Nov 2019 09:55:06 GMT -->

</html>