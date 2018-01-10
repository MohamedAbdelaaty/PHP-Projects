<?php
	session_start();
	require "../includes/db.php";
	require "../includes/func/functions.php";
	if (isset($_POST['user'])) {
		$username = escape_input($_POST['user']);
		$counter = 0;
		$password = hash('sha512' , $_POST['pass']);
		$sql = "SELECT * FROM auth WHERE username = '$username'";
		$run_sql = mysqli_query($conn,$sql);
		while ($rows = mysqli_fetch_assoc($run_sql)) {
			$counter++;
		}
		$sql = "SELECT * FROM auth WHERE username = '$username'";
		$run_sql = mysqli_query($conn,$sql);
		if ($counter != 0) {
			while ($rows = mysqli_fetch_assoc($run_sql)) {
				if ($rows['password'] == $password) {
					$_SESSION['username'] = $rows['username'];
					$_SESSION['logged_in'] = 'true';
					$_SESSION['power_level'] = $rows['power'];
				} else {
					echo '<script>alert("invalid Login")</script>';
				}
			}
		} else {
			echo '<script>alert("invalid login")</script>';
		}
	}
?>
	
<html>

<head>
	<title>Saudies</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="../includes/css/user.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="js/jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Tangerine">
</head>

<style>

.login {
	padding: 130px 25px;
}

</style>

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
					<li><a href="../">Home</a></li>
					<li><a href="../status.php">Status</a></li>
					<li><a href="../menu.php">Menu</a></li>		
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="active"><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
				</ul>
			</div>
		</div>
	</nav>
<?php
	if (!isset($_SESSION['logged_in']))
	echo '
	<div class="container-fluid menu">
		<div class="container centered-cust">
			<div class="row">
				<div class="col-md-8 col-md-offset-2 login">
					<div class="panel panel-primary">
						<div class="panel-heading">Login</div>
						<div class="panel-body">
							<form role="form" method="post">
								<div class="form-group">
									<label for="exampleInputEmail1">Username</label>
									<input type="text" class="form-control" name="user" placeholder="Username">
								</div>
								<div class="form-group">
									<label for="subject">Password</label>
									<input type="password" class="form-control" name="pass" placeholder="Password">
								</div>
								<button type="submit" class="btn btn-primary">Login</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>';
	else {
		echo '<div class="jumbotron"><h1 class="upsize">You are already logged in</h1></div>';
	}
	?>
	<footer>
		<div class="centered-cust">
			<h3 class="copyrights">Saudies© 2017</h3>
		</div>
</body>

</html>