<?php
	session_start();
	if (isset($_SESSION['ProxyPass'])) {
		if (!$_SESSION['ProxyPass'] == true) {
			die ("Proxy check failed");
		}
	} else {
		die ('Proxy verification not found, please click <a href="/">here</a>');
	}
	if (!isset($_SESSION['dbid'])) {
		die ("DBID not found, please try again later!<br>Regards<br>Your Dev Team");
	}
?>

<html>

<style>
.navbar {
	margin: 0px;
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
}

.pan-body {
	margin: 0px;
	padding: 30px 40px;
	background-color:rgba(50, 50, 51,0.7);
	transition: background-color 1s;
}

.pan-body:hover {
	background-color:rgba(50, 50, 51,1);
}


.bg {
	background: url("tourney/bg1.jpg") no-repeat center;
	background-size: cover;
	background-attachment: fixed;
	text-align: center;
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

<head>
	<title>Register for PUG</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>	
</head>

<body class=bg>
	<nav class="navbar navbar-inverse navbar-static-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="#">Crusty Registration</a>
			</div>
			<ul class="nav navbar-nav">
				<li class="active"><a href="#">Register</a></li>
			</ul>
		</div>
	</nav>
	<div class="container-fluid container-fluid-cust">
		<div class="row">
			<div class="col-lg-offset-3 col-lg-6">
				<div class="head">Register for CG Teamspeak</div>
				<div class="pan-body">
				<form action="rules.php" method="POST">
	    				<div class="form-group">
						<div class="row">
	    	  					<label for="email" class="col-md-2">Email:* </label>
							<div class="col-md-10">
	    	  						<input type="email" name="email" class="form-control" id="email" placeholder="Enter email" required>
							</div>
						</div>
	    				</div>
	    				<div class="form-group">
						<div class="row">
	    	  					<label for="email" class="col-md-2">Display Name:* </label>
							<div class="col-md-10">
	    	  						<input type="text" name="name" class="form-control" placeholder="Enter a display name">
							</div>
						</div>
	    				</div>
	    				<div class="form-group">
						<div class="row">
	    	  					<label for="pwd" class="col-md-2">Password:* </label>
							<div class="col-md-10">
	    	  						<input type="password" name="password" class="form-control" id="pwd" placeholder="Enter password" required>
							</div>
						</div>
	    				</div>
	    				<div class="form-group">
						<div class="row">
	    	  					<label for="email" class="col-md-2">LoL-Summoner: </label>
							<div class="col-md-10">
	    	  						<input type="text" name="lol_sum" class="form-control" placeholder="Enter your summoner name if available, otherwise leave blank.">
							</div>
						</div>
	    				</div>
	    				<div class="form-group">
						<div class="row">
	    	  					<label for="email" class="col-md-2">LoL-king: </label>
							<div class="col-md-10">
	    	  						<input type="text" name="lol-king" class="form-control" placeholder="Enter your lol-king profile link if your league name contains a special character">
							</div>
						</div>
	    				</div>
	    				<div class="form-group">
	    	  				<label for="dbid">How did you hear about us? If through a friend, please write their name. *</label>
	    	  				<textarea type="text" name="reference" class="form-control" rows="3" required></textarea>
	   	 			</div>
	   	 			<button type="submit" class="btn btn-primary btn-block">Continue</button>
	  			</form>
				</div>
			</div>
		</div>
	</div>
</body>

</html>