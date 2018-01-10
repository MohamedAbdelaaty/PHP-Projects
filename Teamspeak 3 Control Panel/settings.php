<?php
	session_start();
	require "includes/ts3admin/lib/ts3admin.class.php";
	require "includes/handles/header.php";
	require "includes/handles/loginchecker.php";
	require "includes/handles/functions.php";
	require "includes/db/db.php";

	/*
		Account Settings Page
	*/

	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://USERNAME:PASSWORD@SERVER_IP:QUERY_PORT/?server_port=9987&nickname=Legacy");
	$id = 0;
	$current_password_encrypted = '';
	if (isset($_POST['username'])) {
		if ($_POST['username'] == $_SESSION['username']) {
			if (isset($_POST['pass_cur'])) {
				$sql = "SELECT * FROM auth_user WHERE username='$_SESSION[username]'";
				$run_sql = mysqli_query($conn,$sql);
				while ($rows = mysqli_fetch_assoc($run_sql)) {
					$id = $rows['id'];
				}
				$sql = "SELECT * FROM auth_pass WHERE id='$id'";
				$run_sql = mysqli_query($conn,$sql);
				while ($rows = mysqli_fetch_assoc($run_sql)) {
					$current_password_encrypted = $rows['password'];
				}

				if (hash('sha512',$_POST['pass_cur']) == $current_password_encrypted) {
					if ($_POST['pass_new'] == $_POST['pass_rep']) {
						$new_password = hash('sha512',$_POST['pass_new']);
						$sql = "UPDATE auth_pass SET password='$new_password' WHERE id='$id'";
						$run_sql = mysqli_query($conn,$sql);
					} else {
						die ("Passwords do not match");
					}
				} else {
					die ("Entered Current password is invalid");
				}

			} else {
				die ("Current password not provided");
			}
		} else {
			die("invalid username");
		}
	}
?>

<html>

<head>
	<title>Control Panel Home</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
  	<header class="navbar navbar-inverse navbar-static-top">
    		<div class="container-fluid">
      			<a href="index.php" class="navbar-brand">Crusty Control Panel</a>
      			<ul class="nav navbar-nav">
				<?php
				if ($_SESSION['access'] > 5) {
        				echo '<li><a href="/">Currently Online</a></li>';
				}
				?>
				<li><a href="reg.php">Members</a></li>
				<li><a href="members.php">Teamspeak Members</a></li>
				<li><a href="announcements.php">Announcements</a></li>
      			</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php
					if ($_SESSION['access'] >= 9) {
						echo '<li><a href="ban_appeals.php">Ban Appeals</a></li>';
					}
					if ($_SESSION['access'] >= 10) {
						echo '<li><a href="balist.php">Ban List</a></li><li><a href="admin.php">Admin</a></li>';
					}
				?>
				<li class="dropdown">
                			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="caret"></span></a>
                			<ul class="dropdown-menu">
						<li><a href="#">Messages</a></li>
						<li class="active"><a href="#">Account Settings</a></li>
                				<li><a href="auth/logout.php">Logout</a></li>
                			</ul>
              			</li>
			</ul>
    		</div>
  	</header>
	<div class="container">
		<form class="panel-group form-horizontal" role="form" method="POST">
			<div class="panel panel-primary">
            			<div class="panel-heading">Modify account settings</div>
            			<div class="panel-body">
              				<div class="form-group">
                				<label for="username" class="control-label col-sm-2">Username</label>
                  				<div class="col-sm-9">
                    					<input type="text" name="username" id="username" class="form-control" placeholder="Please enter your username">
                  				</div>
              				</div>

              				<div class="form-group">
                				<label for="username" class="control-label col-sm-2">Current Password</label>
                  				<div class="col-sm-9">
                    					<input type="password" name="pass_cur" id="pass_cur" class="form-control" placeholder="Please enter current password">
                  				</div>
              				</div>

              				<div class="form-group">
                				<label for="username" class="control-label col-sm-2">New Password</label>
                  				<div class="col-sm-9">
                    					<input type="password" name="pass_new" id="pass_new" class="form-control" placeholder="Please enter a new password">
                  				</div>
              				</div>

              				<div class="form-group">
                				<label for="username" class="control-label col-sm-2">Repeat Password</label>
                  				<div class="col-sm-9">
                    					<input type="password" name="pass_rep" id="pass_rep" class="form-control" placeholder="Please repeat your new password">
                  				</div>
              				</div>

              				<div class="form-group">
                  				<div class="col-sm-12">
                    					<input type="submit" value="Modify" class="btn btn-primary btn-block">
                  				</div>
              				</div>
            			</div>
          		</div>
        	</form>
	</div>
</body>

</html>
