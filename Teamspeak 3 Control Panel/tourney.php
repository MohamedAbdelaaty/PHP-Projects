<?php
	/*

		PLEASE NOTE: THIS PAGE IS INCOMPLETE!
		I discontinued this page since tournaments were canceled on the server i was working on this project for.
		IF you are interested, i am willing to finish it up

	*/

	session_start();
	require "includes/ts3admin/lib/ts3admin.class.php";
	require "includes/handles/header.php";
	require "includes/handles/loginchecker.php";
	require "includes/db/db.php";
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://USERNAME:PASSWORD@SERVER_IP:QUERY_PORT/?server_port=9987&nickname=Legacy");
	$counter = 1;
	$ts3Admin = new ts3admin('' /* Server IP */, 10011);

	$username=""; // Query Username
	$password=""; // Query Password
	$ts3_Port = 9987;
?>

<html>

<head>
	<title>Control Panel Home</title>
  <meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<style>
tr {
	transition: color, 1s;
}
tr:hover {
	background-color: grey;
}
.navbar {
	margin: 0px;
	position: fixed;
	width: 100%;
	margin-bottom: 50px;
}

.container-fluid-cust {
	color: white;
	margin: 0px;
	padding: 30px 15px;
}

.head {
	margin:0px;
	margin-top: 70px;
	padding: 15px 10px;
	color: white;
	background-color: #5a5b5b;
}

.pan-body {
	margin: 0px;
	margin-bottom: 50px;
	padding: 30px 40px;
	color: white;
	background-color:rgba(50, 50, 51,0.7);
	transition: background-color 1s;
}
th {
	color: white;
}
td {
	color: white;
}

.pan-body:hover {
	background-color:rgba(50, 50, 51,1);
}


.bg {
	background: url("xd2.jpg") no-repeat center;
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

<body class="bg">
	<header class="navbar navbar-inverse navbar-static-top">
		<div class="container-fluid">
			<a href="index.php" class="navbar-brand">Crusty Control Panel</a>
			<ul class="nav navbar-nav">
  			<li><a href="/">Currently Online</a></li>
				<li><a href="reg.php">Registration</a></li>
				<li><a href="members.php">Teamspeak Members</a></li>
				<li><a href="announcements.php">Announcements</a></li>
				<li class="dropdown">
    			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Org Dep <span class="caret"></span></a>
    			<ul class="dropdown-menu">
						<li class="active"><a href="#">Tournament Controls</a></li>
					</ul>
  			</li>
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
						<li><a href="settings.php">Account Settings</a></li>
    				<li><a href="auth/logout.php">Logout</a></li>
    			</ul>
  			</li>
			</ul>
		</div>
	</header>
	<div class="container">
		<div class="row">
			<div class="col-lg-8">
				<div class="head">Tournament Registration</div>
				<div class="pan-body">
					<table class="table">
						<thead>
							<th>ID</th>
							<th>Name</th>
							<th>Rank</th>
							<th>Sort Level</th>
						</thead>
						<tbody>
					<?php
						if($ts3Admin->getElement('success', $ts3Admin->connect())) {
							$ts3Admin->login($username,$password);

							$ts3Admin->selectServer($ts3_Port);

							$clients = $ts3Admin->channelClientList(417);
					//		print_r($clients);
					//		echo '<br>';

							foreach($clients['data'] as $client) {
								$count = 0;
								$rank = '';
								echo '<tr>';
								echo '<td>'.$counter.'</td>';
								$counter++;
								echo '<td>'.$client['client_nickname'].'</td>';
								$clients2 = $ts3Admin->serverGroupsByClientID($client['client_database_id']);
								foreach($clients2['data'] as $dat) {
									if ($dat['sgid'] >= 290 && $dat['sgid'] <= 301) {
									//	echo $dat['name'].' ';
										if ($count == 0) {
											$rank .= $dat['name'].' ';
										} else {
											$rank .= $dat['name'];
										}
										$count++;
									}
									if ($dat['sgid'] == 30) {
										$rank = 'Staff';
									}
								}
								if (strpos($rank,'Staff') !== false) {
									echo '<td>Staff</td>';
								} else {
									echo '<td>'.$rank.'</td>';
								}
								$rank = strtoupper($rank);
								$sql = "SELECT level FROM rank_system WHERE rank='$rank'";
								$run_sql = mysqli_query($conn,$sql);
								$row = mysqli_fetch_assoc($run_sql);
								if (mysqli_num_rows($run_sql) !== 0) {
									echo '<td>'.$row['level'].'</td>';
								} else {
									echo '<td>N/A</td>';
								}
								echo '</tr>';
							}
						} else {
							echo 'Connection to teamspeak server failed';
						}
					?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="head">Automatic Controls</div>
				<div class="pan-body">
					<a href="#" class="btn btn-success btn-block">Tournament Start</a><hr>
					<a href="#" class="btn btn-primary btn-block">View Tournament Teams</a><hr>
					<a href="#" class="btn btn-danger btn-block">End Tournament</a>
				</div>
				<div class="head">Manual Controls</div>
				<div class="pan-body">
					<a href="#" class="btn btn-primary btn-block">Create Tournament Channels</a><hr>
					<a href="#" class="btn btn-primary btn-block">Run Sort Algorithm</a><hr>
					<a href="#" class="btn btn-primary btn-block">Player Move</a><hr>
					<a href="#" class="btn btn-success btn-block">Submit Results</a><hr>
					<a href="#" class="btn btn-danger btn-block">Reset Ranks</a><hr>
					<a href="#" class="btn btn-danger btn-block">Delete Tournament Channels</a>
				</div>
			</div>
		</div>
	</div>
</body>

</html>
