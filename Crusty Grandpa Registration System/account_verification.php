<?php
	session_start();
	require('tourney/ritoGems/php-riot-api.php');
	require('tourney/ritoGems/FileSystemCache.php');
	$riot = new riotapi('na');
	$cache = new riotapi('na', new FileSystemCache('cache/'));
	$counter = 1;
	if (!isset($_SESSION['email_verified'])) {
		die ("Email Validation not found");
	} else {
		if ($_SESSION['email_verified'] !== true) {
			die ("Email Validation not found");
		}
	}

	if ($_SESSION['summoner_name'] == '') {
		die('<meta http-equiv="refresh" content="0;url=http://register.crustygrandpa.com/confirm_registration.php">');
	}
	if (!isset($_SESSION['account_verification'])) {
		$_SESSION['account_verification'] = rand(50000,100000000);
	}
	$ver_success = '';
	$ver_failed = '';
?>

<html>

<head>
	<title>Community Rules</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>	
</head>
<style>
.navbar {
	margin: 0px;
	margin-bottom: 20px;
}

.container-fluid-cust {
	color: white;
	margin: 0px;
	padding: 30px 15px;
}

.head {
	margin:0px;
	padding: 15px 10px;
	background-color: #5a5b5b;
	text-align: center;
}

.pan-body {
	margin: 0px;
	padding: 30px 40px;
	background-color:#323233;
}


.bg {
	background: url("tourney/bg1.jpg") no-repeat center;
	background-size: cover;
	color: white;
	background-attachment: fixed;
}

input[type=text] {
	background-color: transparent;	
	border: none;
	border-bottom: 1px solid white;
	border-radius: 0px;
	outline: none;
	color: white;
}
input[type=password] {
	background-color: transparent;
	border: none;
	border-bottom: 1px solid white;
	border-radius: 0px;
	color: white;
}
input[type=email] {
	background-color: transparent;
	border: none;
	border-bottom: 1px solid white;
	border-radius: 0px;
	color: white;
}
input[type=number] {
	background-color: transparent;
	border: none;
	border-bottom: 1px solid white;
	border-radius: 0px;
	color: white;
}
textarea[type=text] {
	background-color: transparent;
	border: 1px solid white;
	border-bottom: 1px solid white;
	border-radius: 0px;
	color: white;
}
</style>

<body class="bg">
	<nav class="navbar navbar-inverse navbar-static-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="#">Crusty Registration</a>
			</div>
			<ul class="nav navbar-nav">
				<li class="active"><a href="#">League of legends account Verification</a></li>
			</ul>
		</div>
	</nav>
	<div class="container">
		<div class="row">
			<div class="head col-lg-12">Verify your league of legends account</div>
			<div class="pan-body col-lg-12">
				<?php

					echo '<strong style="color:red"> Please set one of the following mastery pages or create a new mastery page and set the name to: </strong>'.$_SESSION['account_verification'].'<hr>';
					if ($_SESSION['summoner_name'] != '') {
					try {
						$r = $riot->getSummoner($_SESSION['summoner_id'],'masteries');
						// Sorry for the poor programming practice, the r represented which internal array i was on
						foreach ($r as $rr) {
							foreach ($rr as $rrr) {
								foreach ($rrr as $rrrr) {
									echo $counter.' '.$rrrr['name'].'<br>';
									$counter++;
									if (isset($_POST['check'])) {
										if ($rrrr['name'] == $_SESSION['account_verification']) {
											$_SESSION['account_verification_confirmation'] = true;
											$ver_success = '<strong style="color:green">Verification Completed</strong><meta http-equiv="refresh" content="3;url=http://register.crustygrandpa.com/confirm_registration.php">';
										} else {
											$ver_failed = '<strong style="color:red">Verification Failed</strong>';
										}
									}
								}
							}
						}
					} catch(Exception $e) {
						die( "Error: " . $e->getMessage());
					};
				}
			?><hr>
			<?php
				if ($ver_success != '') {
					echo $ver_success;
				} else {
					echo $ver_failed;
				}
			?>
			<form method="POST"> 
				<button name="check" value="true" class="btn btn-primary">Verify Account</button>
			</form>
		</div>
	</div>
</body>

</html>