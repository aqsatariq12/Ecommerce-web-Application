<?php
require_once '../core/Middleware.php';

Middleware::guest();

require_once '../core/Auth.php';
Session::start();
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$action = $_POST['action'] ?? '';
	//Login
	if ($action === 'login') {

		$email = trim($_POST['email'] ?? '');
		$password = $_POST['password'] ?? '';

		if ($email === "" || $password === "") {
			$error = "Please enter your email and password";
		} else {
			$result = AUTH::login($email, $password);
			if ($result['success']) {

				if ($result['user']['role'] === "admin") {
					header('Location: ../admin/index.php');
					exit;
				} else {
					header("Location: index.php");
					exit;
				}

			} else {
				$error = $result["message"];
			}
		}
	}
	// REGISTER
	elseif ($action === "register") {

		$name = trim($_POST["name"] ?? "");
		$email = trim($_POST["email"] ?? "");
		$password = trim($_POST["password"] ?? "");
		if ($name === '' || $email === '' || $password === '') {
			$error = 'Please fil in all required fields';
		} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$error = 'Please enter a valid email address';
		} elseif (strlen($password) < 6) {
			$error = 'Password must be atleast 6 characters long';
		} else {
			$result = AUTH::register($name, $email, $password);
			if ($result['success']) {
				$success = $result['message'];
			} else {
				$error = $result['message'];
			}
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">


<!-- molla/login.php  22 Nov 2019 10:04:03 GMT -->

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
			<nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
				<div class="container">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php">Home</a></li>

						<li class="breadcrumb-item active" aria-current="page">Login</li>
					</ol>
				</div><!-- End .container -->
			</nav><!-- End .breadcrumb-nav -->

			<div class="login-page bg-image pt-8 pb-8 pt-md-12 pb-md-12 pt-lg-17 pb-lg-17"
				style="background-image: url('assets/images/backgrounds/login-bg.jpg')">
				<div class="container">
					<div class="form-box">
						<div class="form-tab">
							<ul class="nav nav-pills nav-fill" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="signin-tab-2" data-toggle="tab" href="#signin-2"
										role="tab" aria-controls="signin-2" aria-selected="false">Sign In</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="register-tab-2" data-toggle="tab" href="#register-2"
										role="tab" aria-controls="register-2" aria-selected="true">Register</a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane fade show active" id="signin-2" role="tabpanel"
									aria-labelledby="signin-tab-2">
									<?php if ($error): ?>
										<div class="alert alert-danger">
											<?= htmlspecialchars($error) ?>
										</div>
									<?php endif; ?>
									<?php if ($success): ?>
										<div class="alert alert-success">

											<?= htmlspecialchars($success) ?>
										</div>
									<?php endif; ?>
									<form method="POST" action="">
										<input type="hidden" name="action" value="login">

										<div class="form-group">
											<label for="singin-email-2"> Email address *</label>
											<input type="email" class="form-control" id="singin-email" name="email"
												required>
										</div><!-- End .form-group -->

										<div class="form-group">
											<label for="singin-password">Password *</label>
											<input type="password" class="form-control" id="singin-password"
												name="password" required>
										</div><!-- End .form-group -->

										<div class="form-footer">
											<button type="submit" class="btn btn-outline-primary-2">
												<span>LOG IN</span>
												<i class="icon-long-arrow-right"></i>
											</button>

											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input"
													id="signin-remember-2">
												<label class="custom-control-label" for="signin-remember-2">Remember
													Me</label>
											</div><!-- End .custom-checkbox -->

											<a href="#" class="forgot-link">Forgot Your Password?</a>
										</div><!-- End .form-footer -->
									</form>
									<div class="form-choice">
										<p class="text-center">or sign in with</p>
										<div class="row">
											<div class="col-sm-6">
												<a href="#" class="btn btn-login btn-g">
													<i class="icon-google"></i>
													Login With Google
												</a>
											</div><!-- End .col-6 -->
											<div class="col-sm-6">
												<a href="#" class="btn btn-login btn-f">
													<i class="icon-facebook-f"></i>
													Login With Facebook
												</a>
											</div><!-- End .col-6 -->
										</div><!-- End .row -->
									</div><!-- End .form-choice -->
								</div><!-- .End .tab-pane -->
								<div class="tab-pane fade" id="register-2" role="tabpanel"
									aria-labelledby="register-tab-2">

									<form method="POST">
										<input type="hidden" name="action" value="register">
										<div class="form-group">
											<label for="register-name-2">Your name *</label>

											<input type="text" class="form-control" id="register-name-2" name="name"
												required>
										</div>

										<div class="form-group">
											<label for="register-email-2">Your email address *</label>

											<input type="email" class="form-control" id="register-email-2" name="email"
												required>
										</div>

										<div class="form-group">
											<label for="register-password-2">Password *</label>
											<input type="password" class="form-control" id="register-password-2"
												name="password" required>
										</div><!-- End .form-group -->

										<div class="form-footer">
											<button type="submit" class="btn btn-outline-primary-2">
												<span>SIGN UP</span>
												<i class="icon-long-arrow-right"></i>
											</button>

											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input"
													id="register-policy-2" required>
												<label class="custom-control-label" for="register-policy-2">I agree to
													the <a href="#">privacy policy</a> *</label>
											</div><!-- End .custom-checkbox -->
										</div><!-- End .form-footer -->
									</form>
									<div class="form-choice">
										<p class="text-center">or sign in with</p>
										<div class="row">
											<div class="col-sm-6">
												<a href="#" class="btn btn-login btn-g">
													<i class="icon-google"></i>
													Login With Google
												</a>
											</div><!-- End .col-6 -->
											<div class="col-sm-6">
												<a href="#" class="btn btn-login  btn-f">
													<i class="icon-facebook-f"></i>
													Login With Facebook
												</a>
											</div><!-- End .col-6 -->
										</div><!-- End .row -->
									</div><!-- End .form-choice -->
								</div><!-- .End .tab-pane -->
							</div><!-- End .tab-content -->
						</div><!-- End .form-tab -->
					</div><!-- End .form-box -->
				</div><!-- End .container -->
			</div><!-- End .login-page section-bg -->
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


<!-- molla/login.php  22 Nov 2019 10:04:03 GMT -->

</html>