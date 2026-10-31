<!DOCTYPE html>
<html lang="en" ng-app="login">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>ASC Aggregates and Construction Materials | Login</title>

	<!-- Existing stylesheets -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/animate.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/animation.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-select.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/login.css">

	<!-- Bootstrap Icons for the password eye -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

	<!-- Fonts and icons -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/font/fonts.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/icon/style.css">

	<!-- SweetAlert -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/sweetalert2/sweetalert2.min.css">

	<!-- Favicon -->
	<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/favicon.png">

</head>

<body class="asc-login-body">

	<div id="wrapper" ng-controller="login-controller">

		<main class="login-page">

			<!-- Full-screen construction background -->
			<div class="login-background" aria-hidden="true"></div>

			<!-- Dark overlay -->
			<div class="login-bg-overlay" aria-hidden="true"></div>

			<!-- Centered login area -->
			<section class="login-overlay">

				<div class="login-card">


					<div class="login-heading">
						<h1>Login to Account</h1>
						<p>Enter your username and password to continue.</p>
					</div>

					<!-- AngularJS AJAX login -->
					<form name="loginForm" ng-submit="loginSubmit($event)" novalidate>

						<!-- Username -->
						<div class="login-form-group">
							<label for="username">
								Username <span class="required">*</span>
							</label>

							<input type="text" id="username" name="username" ng-model="login_user"
								placeholder="Enter your username" autocomplete="username" required>
						</div>

						<!-- Password -->
						<div class="login-form-group">
							<label for="password">
								Password <span class="required">*</span>
							</label>


							<div class="password-wrapper">

								<input type="{{ showPassword ? 'text' : 'password' }}" id="password" name="password"
									ng-model="login_pass" placeholder="Enter your password"
									autocomplete="current-password" required>

								<button type="button" class="toggle-password" ng-click="togglePassword()"
									ng-attr-aria-label="{{ showPassword ? 'Hide password' : 'Show password' }}"
									ng-attr-title="{{ showPassword ? 'Hide password' : 'Show password' }}">
									<i ng-class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'" aria-hidden="true"></i>
								</button>

							</div>

						</div>

						<!-- Login button -->
						<button type="submit" class="login-button" ng-disabled="isLoggingIn">

							<span ng-if="!isLoggingIn">Login</span>
							<span ng-if="isLoggingIn">Signing in...</span>

						</button>

					</form>

					<!-- Footer -->
					<div class="login-footer">
						<p>ASC Aggregates and Construction Materials</p>
						<span>Reliable Supply • Building a Stronger Tomorrow</span>
					</div>

				</div>

			</section>

		</main>

	</div>

	<!-- JavaScript dependencies: load AngularJS before the controller -->
	<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>

	<script src="<?php echo base_url(); ?>assets/js/sweetalert2/sweetalert2.min.js"></script>

	<script src="<?php echo base_url(); ?>assets/js/angular-1.8.2/angular.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/angular-1.8.2/angular-route.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/angular-1.8.2/angular-sanitize.min.js"></script>

	<!-- Existing AngularJS login controller -->
	<script src="<?php echo base_url(); ?>assets/ng-controllers/login.js"></script>

</body>

</html>
