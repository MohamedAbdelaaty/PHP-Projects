<!DOCTYPE HTML>
<?php
	session_start();
	require 'includes/db/db.php';
	if (!isset($_SESSION['display_name'])) {
		die('Not logged in <a href="register/">Click here to register</a>');
	}

	// Enabling and disabling 2FA - Note it is inactive since i never got to rework the login system
	// Initially this was run through Emails (Was the plan at least)
	if (isset($_GET['2_step'])) {
		if ($_GET['2_step'] == 'enable') {
			$check_for_2_step = "SELECT * FROM members WHERE email='$_SESSION[email]'";
			$run = mysqli_query($conn,$check_for_2_step);
			$rows = mysqli_fetch_assoc($run);
			if ($rows['two_step'] != 1) {
				$sql = "UPDATE members SET two_step='1' WHERE email='$_SESSION[email]'";
				$run_sql = mysqli_query($conn,$sql);
				$returned = '<strong style="color:green">Changed two step authentication settings successfully</strong>';
				$_SESSION['two_step_auth'] = 1;
			} else {
				$returned ='<strong style="color:red">ERROR: Two step authentication is already enabled</strong>';
			}
		} else if ($_GET['2_step'] == 'disable') {
			$check_for_2_step = "SELECT * FROM members WHERE email='$_SESSION[email]'";
			$run = mysqli_query($conn,$check_for_2_step);
			$rows = mysqli_fetch_assoc($run);
			if ($rows['two_step'] != 0) {
				$sql = "UPDATE members SET two_step='0' WHERE email='$_SESSION[email]'";
				$run_sql = mysqli_query($conn,$sql);
				$returned = '<strong style="color:green">Changed two step authentication settings successfully</strong>';
				$_SESSION['two_step_auth'] = 0;
			} else {
				$returned ='<strong style="color:red">ERROR: Two step authentication is already disabled</strong>';
			}
		}
	} else {
		$returned ='';
	}

	// Password Change
	if (isset($_POST['old_pass'])) {

		// Custom wrapper for Google Recaptcha
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$data = array('secret' => '' /* Site Secret Key */, 'response' => $_POST['g-recaptcha-response']);

		$options = array(
			'http' => array(
		        	'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
	        		'method'  => 'POST',
	        		'content' => http_build_query($data)
			)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE) { die ('ReCapcha Error'); }
		$explodedData = json_decode($result, true);
		if ( $explodedData['success'] == 1) {
		$password = hash('sha512',$_POST['old_pass']);
		$sql = "SELECT * FROM members WHERE email='$_SESSION[email]'";
		$run_sql = mysqli_query($conn, $sql);
		$rows = mysqli_fetch_assoc($run_sql);
			if ($rows['password'] == $password) {
				$password_new = hash('sha512',$_POST['new_pass']);
				$mysql = "UPDATE members SET password='$password_new' WHERE email='$_SESSION[email]'";
				$run_sql_2 = mysqli_query($conn,$mysql);
				$returned_sql = '<strong style="color:green">Password updated successfully</strong>';
			} else {
				$returned_sql = '<strong style="color:red">Password Missmatch! Unable to change account password</strong>';
			}
		} else {
			$returned_sql = '<strong style="color:red">Recaptcha Error: '.$explodedData['error-codes'][0].'</strong>';
		}
	} else {
		$returned_sql = '';
	}

?>


<html>
	<head>
		<title>CG Tourney Profile Page</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8">
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<script src='https://www.google.com/recaptcha/api.js'></script>
    </script>	</head>
	<body>
		<div id="page-wrapper">

			<!-- Header -->
				<header id="header">
					<h1 id="logo"><a href="index.html">CG Tournaments</a></h1>
					<nav id="nav">
						<ul>
							<li><a href="/">Home</a></li>
							<li><a href="#">Support</a></li>
						<?php
							if (!isset($_SESSION['display_name'])) {
								echo '
								<li><a href="#" class="button">Sign Up</a></li>
								<li><a href="#" class="button special">Sign In</a></li>';
							} else {
								echo '
									<li>
										<a href="#">Account</a>
										<ul>
											<li><a href="#">Profile</a></li>
											<li><a href="#">League of Legends Account</a></li>
											<li><a href="#">Two Step Authentication</a></li>
											<li><a href="#">Teamspeak 3 Identities</a></li>
											<li><a href="auth/logout.php">Logout</a></li>
										</ul>
									</li>
									';
							}
						?>
						</ul>
					</nav>
				</header>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">
						<header class="major">
							<?php echo $returned.$returned_sql ?>
							<h2>Profile</h2>
							<p>Welcome to your profile page <?php echo $_SESSION['display_name'] ?>!</p>
						</header>
						<div class="row 150%">
							<div class="4u 12u$(medium)">

								<!-- Sidebar -->
									<section id="sidebar">
										<section>
											<h3>Your TS3 Identities</h3>
											<?php
												$sql = "SELECT * FROM ts3_identities WHERE profile='$_SESSION[email]'";
												$run_sql = mysqli_query($conn, $sql);
												while ($rows = mysqli_fetch_assoc($run_sql)) {
													echo '<p> Database ID: '.$rows['dbid'].'<br> UID: '.$rows['uid'].'</p><br><br>';
												}
											?>
											<footer>
												<ul class="actions">
													<li><a href="#" class="button">Add an Identity</a></li>
												</ul>
											</footer>
										</section>
										<hr />
										<section>
											<a href="https://na.leagueoflegends.com" class="image fit"><img src="images/ahri.jpg" alt="" /></a>
											<h3>Your league of legends Accounts</h3>
											<?php
												$sql1 = "SELECT * FROM lol_accounts WHERE profile='$_SESSION[email]'";
												$run_sql1 = mysqli_query($conn,$sql1);
												if (mysqli_num_rows($run_sql1) !== 0) {
													while ($rows1 = mysqli_fetch_assoc($run_sql1)) {
														echo '<p>Summoner Name: '.$rows1['account_name'].'<br>Summoner ID: '.$rows1['account_id'].'<br>Rank: '.$rows1['account_rank'].'</p><br><br>';
													}
												} else {
													echo 'None added';
												}
											?>
											<footer>
												<ul class="actions">
													<li><a href="#" class="button">Add an account</a></li>
												</ul>
											</footer>
										</section>
									</section>

							</div>
							<div class="8u$ 12u$(medium) important(medium)">

								<!-- Content -->
									<section id="content">
										<a href="https://crustygrandpa.com" class="image fit"><img src="https://register.crustygrandpa.com/tourney/bg1.jpg" alt="" /></a>
										<h3>Change your password</h3>
								<form method="post">
									<div class="row uniform 50%">
										<div class="6u 12u$(xsmall)">
											<input type="password" name="old_pass" value="" placeholder="Current Password" required />
										</div>
										<div class="6u$ 12u$(xsmall)">
											<input type="password" name="new_pass" value="" placeholder="New Password" required />
										</div>
										<div class="g-recaptcha" data-sitekey="6LfsKCEUAAAAAEtPHwAyU7ImXQSx6gFLFeIuTXK9" data-theme="dark"></div>
										<div class="12u$">
											<input type="submit" value="Update Password" class="special fit" />
										</div>
									</div>
								</form>
										<h3>Two Step Authentication - Under construction</h3>
										<?php
											if ($_SESSION['two_step_auth'] == 1) {
												echo '<strong style="color:green">Two step authentication is enabled! <a href="?2_step=disable">Click here to disable two-step authentication</a></strong>';
											} else {
												echo '<strong style="color:red">Two step authentication is currently disabled. <a href="?2_step=enable">Click here to enable 2-step authentication</a></strong>';
											}
										?>
									</section>

							</div>
						</div>
					</div>
				</div>

			<!-- Footer -->
				<footer id="footer">
					<ul class="icons">
						<li><a href="#" class="icon alt fa-twitter"><span class="label">Twitter</span></a></li>
						<li><a href="#" class="icon alt fa-facebook"><span class="label">Facebook</span></a></li>
						<li><a href="#" class="icon alt fa-linkedin"><span class="label">LinkedIn</span></a></li>
						<li><a href="#" class="icon alt fa-instagram"><span class="label">Instagram</span></a></li>
						<li><a href="#" class="icon alt fa-github"><span class="label">GitHub</span></a></li>
						<li><a href="#" class="icon alt fa-envelope"><span class="label">Email</span></a></li>
					</ul>
					<ul class="copyright">
						<li>&copy; Crusty Grandpa. All rights reserved.</li>
					</ul>
				</footer>

		</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/jquery.dropotron.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>
