<?php
require_once '../../services/AuthService.php';

$authService = new AuthService();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$username = $_POST['username'];
	$password = $_POST['password'];

	if ($authService->login($username, $password)) {
		header('Location: /campa/admin/plantation');
		exit();
	} else {
		$error = "Invalid username or password";
	}
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Forest Plantation Planning and Monitoring System</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="images/icons/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="../../public/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="../../public/css/util.css">
	<link rel="stylesheet" type="text/css" href="../../public/css/main.css">
</head>

<body>

	<div class="header-img">

		<img src="../../public/images/Seal_of_Odisha.svg" class="image-org-top">
		<h6>Government Of Odisha</h6>
	</div>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">

				<div class="login100-form-title" style="background-image: url(../../public/images/bg-01.jpg);">
					<div class="flex-logo">
						<img src="../../public/images/odisha-forest.png" class="w-100">
						<!-- <div class="border-left"></div> -->
					</div>

					<span class="login100-form-title-1">
						Forest Plantation Planning and Monitoring System
					</span>
				</div>

				<form class="login100-form validate-form" method="POST" action="">
					<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
					<div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
						<span class="label-input100">Username</span>
						<input class="input100" type="text" id="username" name="username" required placeholder="Enter username">
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100 validate-input m-b-18" data-validate="Password is required">
						<span class="label-input100">Password</span>
						<input class="input100" type="password" id="password" name="password" required placeholder="Enter password">
						<span class="focus-input100"></span>
					</div>

					<div class="flex-sb-m w-full p-b-30">
						<div class="contact100-form-checkbox">
							<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
							<label class="label-checkbox100" for="ckb1">
								Show Password
							</label>
						</div>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn" type="submit">
							Login
						</button>
					</div>

				</form>
			</div>
		</div>


	</div>
	<div class="footer-img">
		<h6>Designed By : </h6>
		<img src="../../public/images/sparc_white.png" class="image-org">
	</div>


	<script>
		document.getElementById('ckb1').addEventListener('change', function() {
			const passwordField = document.getElementById('password');
			if (this.checked) {
				passwordField.type = 'text';
			} else {
				passwordField.type = 'password';
			}
		});
	</script>
</body>

</html>