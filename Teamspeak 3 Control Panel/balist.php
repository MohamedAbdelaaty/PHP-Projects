<?php
	session_start();
	require "includes/ts3admin/lib/ts3admin.class.php";
	require "includes/handles/header.php";
	require "includes/handles/loginchecker.php";
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://USERNAME:PASSWORD@SERVER_IP:QUERY_PORT/?server_port=SERVER_PORT&nickname=Legacy");
	$counter = 1;

	// kill page if client doesnt have high enough access level to view ban list
	if ($_SESSION['access'] < 9) {
		die("Insufficient Permissions");
	}

	// gets bans
	$bans = $ts3_VirtualServer->banList();
	//print_r($bans);
?>

<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
.alert {
	margin-top: 100px;
}

a {
	color: cyan;
	transition: color .4s;
}

a:hover {
	color: yellow;
}

</style>

<body class="bg">
	<header class="navbar navbar-inverse navbar-static-top">
		<div class="container-fluid">
			<a href="index.php" class="navbar-brand">Crusty Control Panel</a>
			<ul class="nav navbar-nav">
				<li><a href="/">Currently Online</a></li>
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
						echo '<li class="active"><a href="#">Ban List</a></li>
						<li><a href="admin.php">Admin</a></li>';
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
	<div class="container-fluid-cust">
		<div class="head">
			Ban List
		</div>
		<div class="pan-body">
			<table class="table">
				<thead>
					<tr>
						<th>ip</th>
						<th>uid</th>
						<th>Last Nickname</th>
						<th>duration</th>
						<th>invokername</th>
						<th>reason</th>
						<th>Remove</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach($bans as $ban) {
						if ((strpos(strtolower($ban['reason']), 'vpn') === false) && (strpos(strtolower($ban['reason']), 'proxy') === false)) {
							echo '<tr>';
							echo '<td>'.$ban['ip'].'</td>';
							echo '<td>'.$ban['uid'].'</td>';
							echo '<td>'.$ban['lastnickname'].'</td>';
							echo '<td>'.$ban['duration'].'</td>';
							echo '<td>'.$ban['invokername'].'</td>';
							echo '<td>'.$ban['reason'].'</td>';
							echo '<td><a class="btn btn-xs btn-danger" href="rem_ban.php?banid='.$ban['banid'].'">Remove</a></td>';
							echo '</tr>';
						}
					}
					?>
				</tbody>
			</table>
		</div>
		<hr>
		<div class="head">
			VPN/Proxy Bans
		</div>
		<div class="pan-body">
			<table class="table">
				<thead>
					<tr>
						<th>ip</th>
						<th>invokername</th>
						<th>reason</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach($bans as $ban) {
						if ((strpos(strtolower($ban['reason']), 'vpn') !== false) || (strpos(strtolower($ban['reason']), 'proxy') !== false)) {
							echo '<tr>';
							echo '<td>'.$ban['ip'].'</td>';
							echo '<td>'.$ban['invokername'].'</td>';
							echo '<td>'.$ban['reason'].'</td>';
							echo '</tr>';
						}
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</body>

</html>
