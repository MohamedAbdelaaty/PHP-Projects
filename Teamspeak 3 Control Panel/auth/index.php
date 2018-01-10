<?php
	session_start();
	require "../includes/ts3admin/lib/ts3admin.class.php";
	require "../includes/handles/header.php";
	require "../includes/db/db.php";
	require "../includes/handles/functions.php";
	if (isset($_SESSION['username'])) {
		die("Already logged in");
	}
?>

<html>

<head>
	<title>Staff Control Panel Login</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>

<style>
	body {
		text-align: center;
		background: url("../xd2.jpg") no-repeat;
		background-size: cover;
	}
</style>

<body>
	<br><br>
	<div class="container">
		<div class=row>
			<div class="col-lg-offset-3 col-lg-6">
				<form class="panel-group form-horizontal" role="form" method="POST" action="login.php">
					<div class="panel panel-default">
            					<div class="panel-heading">Login</div>
            					<div class="panel-body">
              						<div class="form-group">
                						<label for="username" class="control-label col-sm-3">Username</label>
                  						<div class="col-sm-9">
                    							<input type="text" name="username" id="username" class="form-control" placeholder="username">
                  						</div>
              						</div>
              						<div class="form-group">
                						<label for="password" class="control-label col-sm-3">Password</label>
                  						<div class="col-sm-9">
                    							<input type="password" name="password" id="password" class="form-control" placeholder="password">
                  						</div>
              						</div>
              						<div class="form-group">
                  						<div class="col-sm-12">
                    							<input type="submit" value="Login" class="btn btn-success btn-block">
                  						</div>
              						</div>
            					</div>
          				</div>
        			</form>
				<div class="panel panel-default">
            				<div class="panel-heading">Announcements</div>
            				<div class="panel-body">
												<ul>
													<li>
														Random thing here (Could work off a database and a place on the panel to add an announcement. Will add it upon request).
													</li>
												</ul>
           				</div>
         			</div>
			</div>
		</div>
	</div>
</body>

</html>
