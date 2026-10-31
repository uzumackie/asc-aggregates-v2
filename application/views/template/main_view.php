<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US" ng-app="myApp">

<head>
	<meta charset="utf-8">

	<!-- Mobile -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<title>ASC Aggregates and Construction Materials</title>

	<!-- Theme CSS -->
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/animate.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/animation.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/bootstrap.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/bootstrap-select.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/styles.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/main.css'); ?>" <link rel="stylesheet"
		type="text/css" href="<?= base_url(versioned_script_css('assets/js/pace/pace-minimal.min.css')); ?>">

	<!-- Fonts -->
	<link rel="stylesheet" href="<?= base_url('assets/font/fonts.css'); ?>">

	<!-- Icons -->
	<link rel="stylesheet" href="<?= base_url('assets/icon/style.css'); ?>">

	<!-- Favicon -->
	<link rel="shortcut icon" href="<?= base_url('assets/images/favicon.png'); ?>">

	<link rel="apple-touch-icon-precomposed" href="<?= base_url('assets/images/favicon.png'); ?>">

	<!-- Custom CSS LAST -->
	<!-- <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/custom.css'); ?>"> -->
</head>

<body>

	<div id="wrapper">

		<div id="page">

			<div class="layout-wrap">

				<!-- =====================================================
				 LEFT SIDEBAR
				 ====================================================== -->

				<div class="overplay-menu d-lg-none"></div>

				<div class="section-menu-left">

					<div class="box-logo">

						<a href="<?= base_url('main'); ?>" id="site-logo-inner">
							ASC Aggregates and Construction Materials
						</a>

						<div class="button-show-hide">
							<i class="icon-chevron-left"></i>
						</div>

					</div>

					<div class="section-menu-left-wrap">

						<div class="center">

							<div class="center-item">

								<ul>

									<!-- Dashboard -->
									<li class="menu-item active">

										<a href="#!/dashboard" class="menu-item-button">

											<div class="icon">

												<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
													xmlns="http://www.w3.org/2000/svg">

													<path fill-rule="evenodd" clip-rule="evenodd"
														d="M12.2652 3.57566C12.1187 3.42921 11.8813 3.42921 11.7348 3.57566L5.25 10.0605V19.8748C5.25 20.0819 5.41789 20.2498 5.625 20.2498H9V16.1248C9 15.0893 9.83947 14.2498 10.875 14.2498H13.125C14.1605 14.2498 15 15.0893 15 16.1248V20.2498H18.375C18.5821 20.2498 18.75 20.0819 18.75 19.8748V10.0605L12.2652 3.57566ZM20.25 11.5605L21.2197 12.5302C21.5126 12.8231 21.9874 12.8231 22.2803 12.5302C22.5732 12.2373 22.5732 11.7624 22.2803 11.4695L13.3258 2.51499C12.5936 1.78276 11.4064 1.78276 10.6742 2.515L1.71967 11.4695C1.42678 11.7624 1.42678 12.2373 1.71967 12.5302C2.01256 12.8231 2.48744 12.8231 2.78033 12.5302L3.75 11.5605V19.8748C3.75 20.9104 4.58947 21.7498 5.625 21.7498H18.375C19.4105 21.7498 20.25 20.9104 20.25 19.8748V11.5605ZM13.5 20.2498H10.5V16.1248C10.5 15.9177 10.6679 15.7498 10.875 15.7498H13.125C13.3321 15.7498 13.5 15.9177 13.5 16.1248V20.2498Z"
														fill="#111111" />

												</svg>

											</div>

											<div class="text">
												Dashboard
											</div>

										</a>

									</li>


									<!-- start Masterfiles Menu -->
									<li class="menu-item has-children">

										<a href="#" class="menu-item-button">

											<div class="icon">
												<i class="icon-file-plus"></i>
											</div>

											<div class="text">
												Masterfiles
											</div>

										</a>

										<ul class="sub-menu">

											<li class="sub-menu-item">
												<a href="#!/customer">
													<div class="text">Customer</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#!/town">
													<div class="text">Town</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#!/subsidiary">
													<div class="text">Subsidiary</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#!/supplier">
													<div class="text">Supplier</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#!/item">
													<div class="text">Item</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#!/location">
													<div class="text">Location</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#!/truck">
													<div class="text">Truck</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#!/vehicle">
													<div class="text">Vehicle</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#!/driver">
													<div class="text">Driver</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#!/convoy">
													<div class="text">Convoy</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#!/division">
													<div class="text">Division</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#!/category">
													<div class="text">Category</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#!/group">
													<div class="text">Group</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#!/class">
													<div class="text">Class</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#!/checkers">
													<div class="text">Checkers</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#!/standard-km-masterfile">
													<div class="text">Standard KM Masterfile</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#!/fad-path">
													<div class="text">FAD Path</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#!/adjustment-reason">
													<div class="text">Adjustment Reason</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#!/other-charges">
													<div class="text">Other Charges</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#!/price-group">
													<div class="text">Price Group</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#!/posting-setup">
													<div class="text">Posting Setup</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#!/user">
													<div class="text">User</div>
												</a>
											</li>

										</ul>

									</li>

									<!-- Transactions sample -->
									<li class="menu-item has-children">

										<a href="#" class="menu-item-button">

											<div class="icon">
												<i class="icon-layers"></i>
											</div>

											<div class="text">
												Transactions
											</div>

										</a>

										<ul class="sub-menu">

											<li class="sub-menu-item">
												<a href="#">
													<div class="text">
														All Category
													</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#">
													<div class="text">
														Add Category
													</div>
												</a>
											</li>

										</ul>

									</li>


									<!-- Reports -->
									<li class="menu-item has-children">

										<a href="#" class="menu-item-button">

											<div class="icon">
												<i class="icon-layers"></i>
											</div>

											<div class="text">
												Reports
											</div>

										</a>

										<ul class="sub-menu">

											<li class="sub-menu-item">
												<a href="#">
													<div class="text">
														All Category
													</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#">
													<div class="text">
														Add Category
													</div>
												</a>
											</li>

										</ul>

									</li>


									<!-- Attributes -->
									<li class="menu-item has-children">

										<a href="#" class="menu-item-button">

											<div class="icon">
												<i class="icon-archive"></i>
											</div>

											<div class="text">
												Tools
											</div>

										</a>

										<ul class="sub-menu">

											<li class="sub-menu-item">
												<a href="#">
													<div class="text">
														All Attributes
													</div>
												</a>
											</li>

											<li class="sub-menu-item">
												<a href="#">
													<div class="text">
														Add Attributes
													</div>
												</a>
											</li>

										</ul>

									</li>


									<!-- System Logs -->
									<li class="menu-item">

										<a href="<?= base_url('logout'); ?>">

											<div class="icon">
												<i class="icon-log-out"></i>
											</div>

											<div class="text">
												System Logs
											</div>

										</a>

									</li>

									<!-- Help and Support -->
									<li class="menu-item">

										<a href="<?= base_url('logout'); ?>">

											<div class="icon">
												<i class="icon-log-out"></i>
											</div>

											<div class="text">
												Help & Support
											</div>

										</a>

									</li>

								</ul>

							</div>

						</div>

					</div>

				</div>


				<!-- =====================================================
				 RIGHT CONTENT
			====================================================== -->

				<div class="section-content-right">


					<!-- Header -->
					<div class="header-dashboard">

						<div class="wrap">

							<div class="header-left">

								<a href="<?= base_url('main'); ?>">

									<img id="logo_header_mobile" alt="ASC Logo"
										src="<?= base_url('assets/images/logo/logo.svg'); ?>"
										data-light="<?= base_url('assets/images/logo/logo.svg'); ?>"
										data-dark="<?= base_url('assets/images/logo/logo-white.svg'); ?>">

								</a>

							</div>


							<div class="header-grid">


								<!-- Notification -->
								<div class="popup-wrap noti type-header">

									<div class="dropdown">

										<button class="btn btn-secondary dropdown-toggle" type="button"
											data-bs-toggle="dropdown" aria-expanded="false">

											<span class="header-item">

												<span class="dot"></span>

												<i class="icon-bell"></i>

											</span>

										</button>

									</div>

								</div>


								<!-- Message -->
								<div class="popup-wrap message type-header">

									<div class="dropdown">

										<button class="btn btn-secondary dropdown-toggle" type="button"
											data-bs-toggle="dropdown" aria-expanded="false">

											<span class="header-item">
												<i class="icon-message-square"></i>
											</span>

										</button>


										<div class="dropdown-menu dropdown-menu-end has-content">

											<!-- FIXED -->
											<div class="message-item item-1">

												<div class="image">
													<i class="icon-noti-1"></i>
												</div>

												<div>

													<div class="body-title-2">
														Discount available
													</div>

													<div class="text-tiny">
														Notification message
													</div>

												</div>

											</div>


											<!-- FIXED -->
											<div class="message-item item-2">

												<div class="image">
													<i class="icon-noti-2"></i>
												</div>

												<div>

													<div class="body-title-2">
														Account verified
													</div>

												</div>

											</div>


											<!-- FIXED -->
											<div class="message-item item-3">

												<div class="image">
													<i class="icon-noti-3"></i>
												</div>

												<div>

													<div class="body-title-2">
														Order shipped successfully
													</div>

												</div>

											</div>


											<!-- FIXED -->
											<div class="message-item item-4">

												<div class="image">
													<i class="icon-noti-4"></i>
												</div>

												<div>

													<div class="body-title-2">
														Order pending
													</div>

												</div>

											</div>

										</div>

									</div>

								</div>


								<!-- User -->
								<div class="popup-wrap user type-header">

									<div class="dropdown">

										<button class="btn btn-secondary dropdown-toggle" type="button"
											data-bs-toggle="dropdown" aria-expanded="false">

											<span class="header-user wg-user">

												<span class="image">

													<img src="<?= base_url('assets/images/avatar/user-1.png'); ?>"
														alt="User">

												</span>

												<span class="text flex flex-column">

													<span class="name text-main-dark">
														Administrator
													</span>

													<span class="sub text-tiny">
														System Administrator
													</span>

												</span>

											</span>

										</button>


										<div class="dropdown-menu dropdown-menu-end has-content">

											<a href="#" class="user-item">

												<div class="icon">
													<i class="icon-settings"></i>
												</div>

												<div class="body-title-2">
													Setting
												</div>

											</a>

											<a href="<?= base_url('logout'); ?>" class="user-item">

												<div class="icon">
													<i class="icon-log-out"></i>
												</div>

												<div class="body-title-2">
													Log out
												</div>

											</a>

										</div>

									</div>

								</div>

							</div>

						</div>

					</div>


					<!-- =====================================================
					 MAIN CONTENT
				====================================================== -->

					<div class="main-content">

						<div class="main-content-inner">

							<!-- Angular page loaded here -->
							<div ng-view></div>

						</div>


						<div class="bottom-page">

							<div class="body-text">

								Copyright © 2026

								<a href="#">
									ASC Aggregates and Construction Materials
								</a>

								All rights reserved.

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>


	<script>
		window.$base_url = `<?= base_url(); ?>`;
	</script>


	<!-- =====================================================
	 JAVASCRIPT
====================================================== -->

	<script src="<?= base_url('assets/js/default-theme.js'); ?>"></script>

	<script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>

	<script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>

	<script src="<?= base_url('assets/js/bootstrap-select.min.js'); ?>"></script>

	<script src="<?= base_url('assets/js/zoom.js'); ?>"></script>

	<script src="<?= base_url('assets/js/morris.min.js'); ?>"></script>

	<script src="<?= base_url('assets/js/raphael.min.js'); ?>"></script>

	<script src="<?= base_url('assets/js/morris.js'); ?>"></script>

	<script src="<?= base_url('assets/js/jvectormap.min.js'); ?>"></script>

	<script src="<?= base_url('assets/js/jvectormap-us-lcc.js'); ?>"></script>

	<script src="<?= base_url('assets/js/jvectormap-data.js'); ?>"></script>

	<script src="<?= base_url('assets/js/jvectormap.js'); ?>"></script>

	<script src="<?= base_url('assets/js/apexcharts/apexcharts.js'); ?>"></script>

	<script src="<?= base_url('assets/js/apexcharts/line-chart-1.js'); ?>"></script>

	<script src="<?= base_url('assets/js/apexcharts/line-chart-2.js'); ?>"></script>

	<script src="<?= base_url('assets/js/apexcharts/line-chart-3.js'); ?>"></script>

	<script src="<?= base_url('assets/js/apexcharts/line-chart-4.js'); ?>"></script>

	<script src="<?= base_url('assets/js/apexcharts/line-chart-7.js'); ?>"></script>

	<script defer src="<?= base_url('assets/js/theme-settings.js'); ?>">
	</script>

	<script src="<?= base_url('assets/js/main.js'); ?>"></script>

	<script src="<?= base_url('assets/js/pace/pace.min.js'); ?>"></script>


	<!-- AngularJS -->
	<script src="<?= base_url(versioned_script_css('assets/js/angular-1.8.2/angular.min.js')); ?>"></script>

	<script src="<?= base_url(versioned_script_css('assets/js/angular-1.8.2/angular-route.min.js')); ?>"></script>

	<script src="<?= base_url('assets/js/angular-1.8.2/angular-sanitize.min.js'); ?>"></script>

	<script src="<?= base_url(versioned_script_css('assets/ng-controllers/root.js')); ?>"></script>

	<script src="<?= base_url(versioned_script_css('assets/js/ocLazyLoad/ocLazyLoad.min.js')); ?>"></script>

</body>

</html>