<?php
	session_start();
	require "includes/db.php";
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
					<li><a href="/">Home</a></li>
					<li><a href="status.php">Status</a></li>
					<li class="active"><a href="#">Menu</a></li>		
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<?php
						if (!isset($_SESSION['logged_in'])) {
							echo '<li><a href="http://saudies.ml/auth/login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>';
						} else if ($_SESSION['power_level'] >= 10){
							echo '<li><a href="admin.php">Admin</a></li>';
 						        echo '<li class="dropdown">
                						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="caret"></span></a>
                							<ul class="dropdown-menu">
                							 <li><a href="auth/logout.php">Logout</a></li>
                							</ul>
              							</li>';						} else {
							echo '<li><a href="#">Account</a></li>';
						}
					?>
				</ul>
			</div>
		</div>
	</nav>
	
	<div class="jumbotron menu-head">
		<div class="container centered-cust">
			<h1 class="upsize">Menu</h1>
		</div>
	</div>
	<div class="container-fluid menu-page">
		<div class="container">
			<div class="row">
				<div class="col-md-3">
					<div class="item-desc">
						Fries
					</div>
					<img class="menu-item" src="http://saudies.ml/includes/img/menu/fries.jpg"></img>
					<div class="item-desc price">
						$5
					</div>
				</div>				
				<div class="col-md-3">
					<div class="item-desc">
						Chicken & Beef Burger
					</div>
					<img class="menu-item" src="http://saudies.ml/includes/img/menu/burger.jpg"></img>
					<div class="item-desc price">
						$5
					</div>
				</div>				
				<div class="col-md-3">
					<div class="item-desc">
						Kabab
					</div>
					<img class="menu-item" src="http://saudies.ml/includes/img/menu/kabab.jpg"></img>
					<div class="item-desc price">
						$5
					</div>
				</div>				
				<div class="col-md-3">
					<div class="item-desc">
						Chicken Breasts
					</div>
					<img class="menu-item" src="http://saudies.ml/includes/img/menu/chicken_breasts.jpg"></img>
					<div class="item-desc price">
						$5
					</div>
				</div>				
				<div class="col-md-6">
					<div class="item-desc">
						Chicken Nuggets
					</div>
					<img class="menu-item-nug" src="http://saudies.ml/includes/img/menu/nuggets.png"></img>
					<div class="item-desc">
						4 pc: $3 <br>
						8 pc: $5 <br>
					</div>
				</div>
				<div class="col-md-6 nugg-pricing">
					<strong> Additional Info</strong>
					<ul>
						<li>For combo meal, an addition of $3 will be applied</li>
						<li>For extra cheese: +$1</li>
						<li>All the food is halal</li>
					</ul>
					<hr>
					<strong>Payment methods:</strong><br>
					<ul>
						<li>Credit Card</li>
						<li>Cash</li>
					</ul>
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