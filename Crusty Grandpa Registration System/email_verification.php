<?php
	session_start();
	require "includes/handles/functions.php";
	require "includes/db/db.php";
	if (!isset($_SESSION['read_timer'])) {
		die ('Rules not read! Redirecting in 5 seconds...<meta http-equiv="refresh" content="5; url=rules.php" />');
	}
	if (time() - $_SESSION['read_timer'] < 10) {
		echo 'did you really read the rules in: '.(time() - $_SESSION['read_timer']).' seconds? Redirecting in 5 seconds...';
		unset($_SESSION['read_timer']);
		die('<meta http-equiv="refresh" content="5; url=rules.php" />');
	}
	if (!isset($_SESSION['email_confirmation'])) {
		$to= $_SESSION['email'];
		$_SESSION['email_confirmation'] = rand(8000000, 1000000000);
		require 'includes/mail/PHPMailerAutoload.php';

		$mail = new PHPMailer;

		//$mail->SMTPDebug = 3;                               	// Enable verbose debug output

		$mail->isSMTP();                                      	// Set mailer to use SMTP
		$mail->Host = '';  										// Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               	// Enable SMTP authentication
		$mail->Username = '';                					// SMTP username
		$mail->Password = '';                       			// SMTP password
		$mail->SMTPSecure = 'tls';                           	// Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    	// TCP port to connect to
	
		$mail->setFrom('', 'E-Mail Confirmation');
		$mail->addAddress($to);    								// Add a recipient
	
		$mail->isHTML(true);                                  	// Set email format to HTML
	
		$mail->Subject = 'CG E-mail confirmation for '.$_SESSION['display_name'];
		$mail->Body    = 'Thank you for registering for CG community, please confirm your email using the confirmation code found below.<br>
				Your confirmation code:'.$_SESSION['email_confirmation'];

		if(!$mail->send()) {
			echo "Connection to SMTP server failed. Registration cannot be completed at this time, please try again later."; exit;
		}
	}
	if (isset($_POST['code'])) {
		$code = escape_input($_POST['code']);
		if ($code == $_SESSION['email_confirmation']) {
			$_SESSION['email_verified'] = true;
			$returned = '<strong style="color:green">Validated, redirecting in 3 seconds.</strong><meta http-equiv="refresh" content="3;url=http://register.crustygrandpa.com/account_verification.php">';
		} else {
			$returned = '<strong style="color:red">Invalid Code</strong><br>';
		}
	} else {
		$returned = '';
	}
?>

<html>

<head>
	<title>Email confirmation</title>
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
				<li class="active"><a href="#">Email Verification</a></li>
			</ul>
		</div>
	</nav>
	<div class="container">
		<div class="row">
			<div class="col-lg-offset-2 col-lg-8">
				<div class="head">Confirm Your Email</div>
				<div class="pan-body">
					You have been sent an E-Mail at <?php echo $_SESSION['email'] ?> with a confirmation code to verify your email, please enter the code below. Remember to check your spam folder.<br><br><br>
					<form method="POST">
						<div class="row">
							<div class="col-md-4">
								Confirmation Code:
							</div>
							<div class="col-md-8">
								<input type="number" name="code" style="width:100%"></input>
							</div>
							<hr>
							<?php echo $returned ?>
							<button class="btn btn-primary btn-block">Validate</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>