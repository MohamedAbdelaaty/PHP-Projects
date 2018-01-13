<?php
	session_start();
	require "includes/ts3admin/lib/ts3admin.class.php";
	require "includes/handles/header.php";
	require "includes/db/db.php";
	require "includes/handles/loginchecker.php";
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://USERNAME:PASSWORD@SERVER_IP:QUERY_PORT/?server_port=9987&nickname=Legacy");
	if (!isset($_SESSION['username'])) {
		die ("Not logged in");
	}
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
a {
	color: yellow;
	transition: color .4s;
}
a:hover {
	color: cyan;
}
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
				<?php
				if ($_SESSION['access'] > 5) {
					echo '<li><a href="/">Currently Online</a></li>';
				}
				?>
				<li><a href="reg.php">Members</a></li>
				<li class="active"><a href="#">Teamspeak Members</a></li>
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
						<li><a href="settings.php">Account Settings</a></li>
    						<li><a href="auth/logout.php">Logout</a></li>
    					</ul>
  				</li>
			</ul>
		</div>
	</header>
	<div class="container">
		<div class="head">Teamspeak 3 Members</div>
		<div class="pan-body">
			<table class="table">
				<thead>
					<th>DBID</th>
					<th>Name</th>
					<th>Client UID</th>
					<!--<th>League of Legends Summoner</th>-->
				</thead>
				<tbody>
					<?php
					$data = $ts3_VirtualServer->serverGroupGetById(7)->clientList(); // SGID 7 is the member group
					foreach($data as $dat) {
						echo '<tr>';
						echo '<td>'.$dat['cldbid'].'</td>';
						echo '<td><a href="admin_tools.php?dbid='.$dat['cldbid'].'">'.$dat['client_nickname'].'</a></td>';
						echo '<td>'.$dat['client_unique_identifier'].'</td>';
						echo '<tr>';
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</body>

</html>
