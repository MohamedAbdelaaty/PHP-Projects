<?php
	session_start();
	require "includes/db.php";
	$sql = "SELECT * FROM status WHERE id = 1";
	$run_sql = mysqli_query($conn,$sql);
	while ($rows = mysqli_fetch_assoc($run_sql)) {
		$status = $rows['status'];
	}
	if (isset($_GET['action'])) {
		if ($_GET['action'] == 'act') {
			$sql = "UPDATE status SET status='Active' WHERE id=1";
			$run_sql = mysqli_query($conn,$sql);
			echo '<meta http-equiv="Refresh" content="0; url=admin.php"> ';
		} else if ($_GET['action'] == 'deact') {
			$sql = "UPDATE status SET status='Inactive' WHERE id=1";
			$run_sql = mysqli_query($conn,$sql);
			echo '<meta http-equiv="Refresh" content="0; url=admin.php"> ';
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
<?php if (isset($_SESSION['logged_in'])) {
		if ($_SESSION['power_level'] >= 10) {
			echo '
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
					<li><a href="menu.php">Menu</a></li>		
				</ul>
				<ul class="nav navbar-nav navbar-right">';
						if (!isset($_SESSION['logged_in'])) {
							echo '<li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>';
						} else if ($_SESSION['power_level'] >= 10){
							echo '<li class="active"><a href="#">Admin</a></li>';
 						        echo '<li class="dropdown">
                						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="caret"></span></a>
                							<ul class="dropdown-menu">
                							 <li><a href="auth/logout.php">Logout</a></li>
                							</ul>
              							</li>';						} else {
							echo '<li><a href="#">Account</a></li>';
						}
						echo '
				</ul>
			</div>
		</div>
	</nav>
		<div class="container-fluid menu-page" style="height:100%">
		<div class="container">
			<div class="row">
				<div class="col-md-3">
					<div class="item-desc">
						Current Status: <strong>'.$status.'</strong>
					</div>';
					if ($status == 'Active') {
						echo '<a href="?action=deact" class="btn btn-danger btn-block">Deactivate</a>';
					} else {
						echo '<a href="?action=act" class="btn btn-primary btn-block">Activate</a>';
					}
echo '
				</div>			
			</div>
		</div>
	</div>
	<footer>
		<div class="centered-cust">
			<h3 class="copyrights">Saudies© 2017</h3>
		</div>';
		} else {
			echo 'Insufficient Perms';
		}
} else {
	echo 'You need to be logged in to do that!';
}
?>
</body>

</html>