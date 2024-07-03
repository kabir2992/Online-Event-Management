<?php session_start();
if ($_SESSION["role"] != 'C') {
	header('Location: /ems/login.php');
}
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
	data-assets-path="assets/" data-template="vertical-menu-template-free">

<head>
	<meta charset="utf-8" />
	<meta name="viewport"
		content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

	<title>Customer Dashboard</title>

	<meta name="description" content="" />

	<!-- Favicon -->
	<link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

	<style>
		/* Styling Google Translate Element */
		#google_translate_element {
			display: inline-block;
			background-color: #6a5acd;
			/* Blue-purple color */
			padding: 10px;
			border-radius: 5px;
			font-family: Arial, sans-serif;
		}

		/* Styling the dropdown arrow */
		.goog-te-combo {
			background-color: #483d8b;
			/* Darker blue-purple color */
			color: #fff;
			/* White text color */
			border-radius: 3px;
			padding: 5px;
		}

		/* Styling the dropdown menu */
		.goog-te-menu-value {
			background-color: #fff;
			/* White background color */
			color: #000;
			/* Black text color */
		}

		/* Styling the translated text */
		.goog-te-banner-frame.skiptranslate {
			display: none !important;
		}

		/* Styling the tooltip */
		.goog-tooltip {
			background-color: #6a5acd;
			/* Blue-purple color */
			color: #fff;
			/* White text color */
			border-radius: 3px;
		}

		/* Styling the tooltip arrow */
		.goog-tooltip-arrow {
			border-color: #6a5acd transparent;
		}

		/* Styling the tooltip text */
		.goog-tooltip-text {
			color: #fff;
			/* White text color */
		}

		.translate {
			background-color: #f4f5fa !important;
		}

		.goog-te-gadget {
			color: #f4f5fa !important;
		}

		.VIpgJd-ZVi9od-l4eHX-hSRGPd {
			display: none;
		}

		.goog-te-combo {
			background-color: #8A2BE2 !important;
		}
	</style>
	<!-- Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
		rel="stylesheet" />

	<link rel="stylesheet" href="assets/vendor/fonts/materialdesignicons.css" />

	<!-- Menu waves for no-customizer fix -->
	<link rel="stylesheet" href="assets/vendor/libs/node-waves/node-waves.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">

	<!-- Core CSS -->
	<link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
	<link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
	<link rel="stylesheet" href="assets/css/demo.css" />

	<!-- Vendors CSS -->
	<link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
	<link rel="stylesheet" href="../assets/css/chat.css" />

	<!-- Page CSS -->

	<!-- Helpers -->
	<script src="assets/vendor/js/helpers.js"></script>
	<!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
	<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
	<script src="assets/js/config.js"></script>
</head>

<body>
	<!-- Layout wrapper -->
	<div class="layout-wrapper layout-content-navbar">
		<div class="layout-container">
			<!-- Menu -->

			<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
				<div class="app-brand demo">
					<a href="" class="app-brand-link">
						<span class="app-brand-text demo menu-text fw-semibold ms-2">Be'eventful</span>
					</a>
					<a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
						<i class="mdi menu-toggle-icon d-xl-block align-middle mdi-20px"></i>
					</a>
				</div>

				<div class="menu-inner-shadow"></div>
				<div class="translate" id="google_translate_element"></div>

				<script type="text/javascript">
					function googleTranslateElementInit() {
						new google.translate.TranslateElement({
							pageLanguage: 'en'
						}, 'google_translate_element');
					};
				</script>
				<script type="text/javascript"
					src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>



				<ul class="menu-inner py-1">

					<li class="menu-item">
						<a href="/ems/customer/home.php " class="menu-link">
							<i class="menu-icon tf-icons mdi mdi-home-outline"></i>
							<div data-i18n="Calendar">Home</div>
						</a>
					</li>


					<li class="menu-item">
						<a href="cart.php" class="menu-link">
							<i class="menu-icon tf-icons mdi  mdi-cart-outline"></i>
							<div data-i18n="Calendar">My Cart</div>
						</a>
					</li>

					<li class="menu-item">
						<a href="order.php" class="menu-link">
							<i class="menu-icon tf-icons mdi mdi-format-list-checks"></i>
							<div data-i18n="Calendar">My Bookings</div>
						</a>
					</li>
					<li class="menu-item">
						<a href="payment.php" class="menu-link">
							<i class="menu-icon tf-icons mdi mdi-notebook-check-outline"></i>
							<div data-i18n="Calendar">Payment History</div>
						</a>
					</li>

					<li class="menu-item">
						<a href="meetings.php" class="menu-link">
							<i class="menu-icon tf-icons mdi mdi-video-outline"></i>
							<div data-i18n="">Meetings</div>
						</a>
					</li>
				</ul>
			</aside>
			<!-- / Menu -->

			<!-- Layout container -->
			<div class="layout-page">
				<!-- Navbar -->

				<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme shadow"
					id="layout-navbar">
					<div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
						<a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
							<i class="mdi mdi-menu mdi-24px"></i>
						</a>
					</div>

					<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">


						<ul class="navbar-nav flex-row align-items-center ms-auto">
							<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">


								<ul class="navbar-nav flex-row align-items-center ms-auto">
									<li class="">
										<a class="nav-link" href="chat.php">
											<span class="mdi mdi-message-bulleted" style="font-size: 20px; color: blueviolet;"></span>
										</a>

										<!-- Place this tag where you want the button to render. -->
									<li class="menu-item">
										<span class="nav-link text-dark mx-2">
											<?php echo ucfirst(strtolower($_SESSION["name"])); ?>
										</span>
									</li>


									<!-- User -->
									<li class="nav-item navbar-dropdown dropdown-user dropdown">
										<a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
											data-bs-toggle="dropdown">
											<div class="avatar avatar-online">
												<img src="assets/img/avatars/1.png" alt
													class="w-px-40 h-auto rounded-circle" />
											</div>
										</a>
										<ul class="dropdown-menu dropdown-menu-end mt-3 py-2">
											<li>
												<a class="dropdown-item pb-2 mb-1" href="#">
													<div class="d-flex align-items-center">
														<div class="flex-shrink-0 me-2 pe-1">
															<div class="avatar avatar-online">
																<img src="assets/img/avatars/1.png" alt
																	class="w-px-40 h-auto rounded-circle" />
															</div>
														</div>
														<div class="flex-grow-1">
															<h6 class="mb-0">
																<?php echo $_SESSION["name"]; ?>
															</h6>
															<small class="text-muted">Customer</small>
														</div>
													</div>
												</a>
											</li>
											<li>
												<div class="dropdown-divider my-1"></div>
											</li>

											<li>
												<a class="dropdown-item" href="/ems/logout">
													<i class="mdi mdi-power me-1 mdi-20px"></i>
													<span class="align-middle">Log Out</span>
												</a>
											</li>

										</ul>
									</li>
									<!--/ User -->
								</ul>
							</div>
				</nav>

				<!-- / Navbar -->

				<!-- Content wrapper -->
				<div class="content-wrapper">

					<!-- Content -->
					<div class="container-xxl flex-grow-1 container-p-y">