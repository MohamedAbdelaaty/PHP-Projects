<?php
	session_start();
	//openssl_random_pseudo_bytes(5)
	require "includes/db.php";
	if (isset($_POST['email'])) {
		$recipient = 'saudies@saudies.ml';
		$subject = strip_tags($_POST['subject']);
		$content = strip_tags($_POST['message']);
		$header = "From: ".strip_tags($_POST['name'])." <".strip_tags($_POST['email'])."> \r\n";
		$header .= "Reply-To: ".strip_tags($_POST['email'])."\r\n"; 
  		$header .= "Return-Path: Saud Khouj <saudkhouj@gmail.com \r\n";
 		$header .= "Organization: Saudies Foods\r\n";
  		$header .= "MIME-Version: 1.0\r\n";
		$retval = mail ($recipient,$subject,$content,$header);
         	if($retval == true ) {
         		echo '<script>alert("Success")</script>';
	        }else {
           		echo '<script>alert("Failed")</script>';
         	}
	}
?>
	
<html>

<head>
	<title>Saudies</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="includes/css/user.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Tangerine">
</head>

<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span> 
				</button>
				<a class="navbar-brand" href="#">Saudies</a>
			</div>
			<div class="collapse navbar-collapse" id="navbar">
				<ul class="nav navbar-nav">
					<li class="active"><a href="#">Home</a></li>
					<li><a href="status.php">Status</a></li>
					<li><a href="menu.php">Menu</a></li>		
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<?php
						if (!isset($_SESSION['logged_in'])) {
							echo '<li><a href="auth/login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>';
						} else if ($_SESSION['power_level'] >= 10){
							echo '<li><a href="admin.php">Admin</a></li>';
 						        echo '<li class="dropdown">
                						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="caret"></span></a>
                							<ul class="dropdown-menu">
                							 <li><a href="auth/logout.php">Logout</a></li>
                							</ul>
              							</li>';
							} else {
							echo '<li><a href="#">Account</a></li>';
						}
					?>
				</ul>
			</div>
		</div>
	</nav>
	
	<div class="jumbotron intro">
		<div class="container centered-cust">
			<h1 class="upsize">Saudies</h1>
		</div>
	</div>
	
	<div class="container-fluid info">
		<div class="container">
				<h1>About</h1>
				<h3>Cooking is a very tedious task, specially for students. For us students, time is a very valuable asset due to the overwhelming workload that we each get assigned, which results in the ordering of food online or wasting time during the day to prepare a satisfying meal. However, what if you could order satisfying homemade food for extrememly low prices? <br>
				For the first time ever, I'm offering homemade food for VEDA Living, Buildings A & B!
				</h3>
				<p>Please order at least 10 minutes prior to pick up</p>
		</div>
	</div>

	<div class="container-fluid menu">
		<div class="container centered-cust">
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<div class="panel panel-primary">
						<div class="panel-heading">Contact Me</div>
						<div class="panel-body">
							<form role="form" method="post">
								<div class="form-group">
									<label for="subject">Name</label>
									<input type="text" class="form-control" name="name" placeholder="Your Name" required>
								</div>
								<div class="form-group">
									<label >Email address</label>
									<input type="email" class="form-control" name="email" placeholder="Enter your email" required>
								</div>
								<div class="form-group">
									<label for="subject">Subject</label>
									<input type="text" class="form-control" name="subject" placeholder="Subject of Email" required>
								</div>
								<div class="form-group">
									<label>Message</label>
									<textarea class="form-control" name="message" rows="3" required></textarea>
								</div>
								<button type="submit" class="btn btn-primary">Send</button>
							</form>
						</div>
					</div>
					<div class="well">
						Or call me at (647) 503-8138
					</div>
				</div>
			</div>
		</div>
	</div>
	<footer>
		<div class="centered-cust">
			<h3 class="copyrights">Saudies© 2017</h3>
		</div>
</body>

</html>