<?php
	session_start();
	require '../includes/db/db.php';
	if (isset($_SESSION['display_name'])) {
		die('Already logged in');
	}
	if (isset($_POST['email']) && isset($_POST['password'])) {
		// Custom wrapper for google recaptcha that i made
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$data = array('secret' => '6LfXBSEUAAAAAEfMFLITGQC0iW3m4ry5XcyR5cfc', 'response' => $_POST['g-recaptcha-response']);

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
			// Verify login information
			// Could escape html characters too but, didnt think aobut it at the time.
			// Also strip string
			$email = mysqli_real_escape_string($conn, $_POST['email']);
			$password = hash('sha512', $_POST['password']);
			$sql = "SELECT * FROM members WHERE email='$email' AND password='$password'";
			$run_sql = mysqli_query($conn, $sql);
			if (mysqli_num_rows($run_sql) !== 0) {
				$rows = mysqli_fetch_assoc($run_sql);
				$_SESSION['display_name'] = $rows['display_name'];
				$_SESSION['email'] = $rows['email'];
				$_SESSION['profile_id'] = $rows['id'];
				$_SESSION['two_step_auth'] = $rows['two_step'];
				$returned ='<meta http-equiv="refresh" content="0; url=http://tournament.crustygrandpa.com" />';
			} else {
				$returned ='<strong style="color:red">Invalid Login</strong>';
			}
		} else {
			$returned = '<strong style="color:red">Recaptcha Error: '.$explodedData['error-codes'][0].'</strong>';
		}
	} else {
		$returned = '';
	}

?>

<html>

<head>
	<title>Login to CG Tournaments</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<!--[if lte IE 8]><script src="../assets/js/ie/html5shiv.js"></script><![endif]-->
	<link rel="stylesheet" href="../assets/css/main.css" />
	<!--[if lte IE 9]><link rel="stylesheet" href="../assets/css/ie9.css" /><![endif]-->
	<!--[if lte IE 8]><link rel="stylesheet" href="../assets/css/ie8.css" /><![endif]-->
	<script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<style>
.pad {
	padding: 2em 4em;
}
</style>

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
							<li><a href="../register/" class="button">Sign Up</a></li>
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
									</ul>
								</li>
							';
						}
					?>
				</ul>
				</nav>
			</header>
		<section class="pad">
			<h3>Login to CG Tournament Page</h3>
			<form method="post">
				<div class="row uniform 50%">
					<?php
						echo $returned;
					?>
					<div class="12u$">
						<input type="email" name="email" placeholder="Enter your Email" required></input>
					</div>
					<div class="12u$">
						<input type="password" name="password" placeholder="Enter your password" required></input>
					</div>
					<div class="12u$">
						<div class="g-recaptcha" data-sitekey="6LfXBSEUAAAAAEzppaKVe3lLJ4BeSpM_Elq2YknT" data-theme="dark"></div>
					</div>
					<div class="12u$">
						<input type="submit" value="Login" class="special fit" /></li>
					</div>
				</div>
			</form>
		</section>
	</div>

</body>

</html>
