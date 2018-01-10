<?php
	session_start();
	require 'includes/func/functions.php';
	require 'includes/db/db.php';
	// limit 3 tries
	if (!isset($_SESSION['strike'])) {
		$_SESSION['strike'] = 0;
	}
?>

<html>

<head>
	<title>AIM-Gaming Staff Application</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">-->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<style>
	optgroup.cat-head {
		color:red;
	}
	body {
		background-color:black;
		background:url(http://staff.aimgaming.tk/cont/assets/img/ryze_4.jpg) no-repeat center;
		background-size: cover;
		background-attachment: fixed;
	}
	.applied {
		background-color:navy;
		padding: 20px 20px;
		text-align:center;
		border-radius:10px;
		color:white;
	}
	.denied {
		background-color:red;
		padding: 20px 20px;
		text-align:center;
		border-radius:10px;
		color:white;
	}
</style>

<body>
  <header class="navbar navbar-inverse navbar-static-top">
    <div class="container">
      <a href="/apply/" class="navbar-brand">AIM Gaming Staff Application</a>
    </div>
  </header>
  <div class="container">
	<div class="row">
		<div class="col-lg-8 col-lg-offset-2">
		<?php
		if (!isset($_SESSION['applied']))
		if ($_SESSION['strike'] < 3) 
		echo '
			<form class="panel-group form-horizontal" role="form" action="?task=apply" method="POST">
				<div class="panel panel-default">
					<div class="panel-heading">Apply for Staff</div>
					<div class="panel-body">
						<div class="form-group">
							<label for="name" class="control-label col-sm-3">Name</label>
							<div class="col-sm-9">
								<input type="text" id="Name" name="name" class="form-control" placeholder="Name" required>
							</div>
						</div>
						<div class="form-group">
							<label for="name" class="control-label col-sm-3">Forum Profile Link</label>
							<div class="col-sm-9">
								<input type="text" id="Name" name="purl" class="form-control" placeholder="Profile Link" required>
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="control-label col-sm-3">E-Mail</label>
							<div class="col-sm-9">
								<input type="email" id="email" name="email" class="form-control" placeholder="Private Email Address" required>
							</div>
						</div>				  
						<div class="form-group">
							<label for="role" class="control-label col-sm-3">Role</label>
							<div class="col-sm-9">
								<select class="selectpicker form-control" name="role" required>
									<option value="">Select Staff Role</option>
									<optgroup class="cat-head" label="TeamSpeak 3 Positions">
										<optgroup label="Adminstration Department">
											<option value="334782">Community Admin						</option>
										</optgroup>
										<optgroup label="Moderation Department">
											<option value="228730">Moderator in Training					</option>
										</optgroup>
										<optgroup label="Event Coordination Department">
											<option value="339182">Event Coordinator in Training				</option>
										</optgroup>
										<optgroup label="Recruiting Department">
											<option value="889103">Recruiter in Training					</option>
										</optgroup>
									</optgroup>
									<optgroup class="cat-head" label="Discord Positions">
										<optgroup label="Adminstration Department">
											<option value="DV">Developer							</option>
										</optgroup>
										<optgroup label="Moderation Department">
											<option value="MD">Moderator							</option>
										</optgroup>
										<optgroup label="Event Coordination Department">
											<option value="EC">Event Coordinator						</option>
										</optgroup>
										<optgroup label="Recruiting Department">
											<option value="RC">Recruiter							</option>
										</optgroup>
									</optgroup>
									<optgroup class="cat-head" label="IT-Department">
										<optgroup label="Automation Department">
											<option value="BD">Bot Developer						</option>
										</optgroup>
										<optgroup label="Information Security Department">
											<option value="SA">Security Advisor						</option>
										</optgroup>
									</optgroup>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="abilities" class="control-label col-sm-3">Special Snowflake?</label>
							<div class="col-sm-9">
								<textarea type="text" name="abilities" class="form-control" placeholder="What makes you special? Why should we accept you?" required></textarea>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label for="IP" class="control-label col-sm-3">IP Address</label>
							<div class="col-sm-9">
								<input type="text" name="IP" class="form-control" placeholder="Please Enter your real IP address, can be found by googling \'what is my ip address\'" value="'.$_SERVER['REMOTE_ADDR'].'" required></textarea>
								<details>We need your IP address so we can verify your connection to our staff control panel and ensure there is no unauthorized usage of your account. Please verify that the IP address you are connecting from is your actual home IP.<br> <strong>Leaving this blank will result in denial of application. For more information contact an admin.</strong><hr><strong><u>NOTE:</u> you can find your IP by googling "What is my ip address"</strong></details>
							</div>
						</div>
						<div class="form-group">
							<label for="timezone" class="control-label col-sm-3">Time Zone</label>
							<div class="col-sm-9">
								<input type="text" name="timezone" class="form-control" placeholder="Enter timezone code eg. (EST, (GMT works too))." required>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<div class="col-sm-12">
								<button type="submit" name="confirmID" value="1" class="btn btn-primary btn-block">Apply</button>
							</div>
						</div>
					</div>
				</div>
			</form>';
			else {
				echo '<div class="denied">Application rejected due to invalid input</div>';
			}
			else {
				echo '<div class="applied">Thank you for your application</div>';
			}
			?>
		</div>
	</div>
  </div>
</body>
<?php
	if (isset($_GET['task'])) {
		if ($_GET['task'] == 'apply') {
			if (isset($_POST['confirmID'])) {
				if ($_POST['confirmID'] == '1') {
					if (ver_input(strtolower($_POST['name'])) && ver_input(strtolower($_POST['purl'])) && ver_input(strtolower($_POST['email'])) && ver_input(strtolower($_POST['role'])) && ver_input(strtolower($_POST['abilities'])) && ver_input(strtolower($_POST['timezone'])) && ver_input(strtolower($_POST['IP']))){
						$name = mysqli_real_escape_string($conn, $_POST['name']);
						$email = mysqli_real_escape_string($conn, $_POST['email']);
						$role =	mysqli_real_escape_string($conn, $_POST['role']);
						$abilities = mysqli_real_escape_string($conn, $_POST['abilities']);
						$tz = mysqli_real_escape_string($conn, $_POST['timezone']);
						$ip = mysqli_real_escape_string($conn, $_POST['IP']);
						$purl = mysqli_real_escape_string($conn, $_POST['purl']);
						$approval = '<strong style="color:orange">Awaiting Approval</strong>';
						$power_level = 11;
						$role_data='11232';
						$_SESSION['applied'] = true;
						$sql = "INSERT INTO staff_list (Name, Contact, Role, Approval, power_level) VALUES ('$name','$email', '$role_data','$approval', '$power_level')";
						$run_sql = mysqli_query($conn,$sql);
						$sql = "INSERT INTO staff_applications (name, email, role, ip, speciality, timezone, profile) VALUES ('$name','$email','$role','$ip','$abilities','$tz','$purl')";
						$run_sql = mysqli_query($conn,$sql);

					} else {
						echo '<script>alert("one or more unauthorized characters detected, 1 strike has been added.")</script>';
						$_SESSION['strike']++;
					}
				} else {
					echo '<script>alert("Invalid ID")</script>';
				}
			} else {
				echo '<script>alert("Invalid Data")</script>';
			}
		} else {
			echo '<script>alert("Invalid Task")</script>';
		}
	}
?>
</html>