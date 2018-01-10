<?php
	session_start();
	require "includes/db.php";
	$sql = "SELECT * FROM status WHERE id=1";
	$run_sql = mysqli_query($conn,$sql);
	while ($rows = mysqli_fetch_assoc($run_sql)) {
		$status = $rows['status'];
	}
	if ($status == 'Active') {
		$toBeEchoed = '		
		<div class="container shop-status-active">
			Shop Status: <strong>Open</strong>
		</div>';
	} else {
		$toBeEchoed = '		
		<div class="container shop-status-inactive">
			Shop Status: <strong>Closed</strong><hr>
			Timings: <br>
			weekdays: 7pm-11pm (Excluding tuesday, tuesday timing: 8pm - 11pm)<br>
			weekends: 6pm-12am
		</div>';
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
					<li><a href="/">Home</a></li>
					<li class="active"><a href="#">Status</a></li>
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
              							</li>';						} else {
							echo '<li><a href="#">Account</a></li>';
						}
					?>				</ul>
			</div>
		</div>
	</nav>
	
	<div class="jumbotron status">
		<?php echo $toBeEchoed ?>
	</div>
	<footer>
		<div class="centered-cust">
			<h3 class="copyrights">Saudies© 2017</h3>
		</div>
</body>

</html>