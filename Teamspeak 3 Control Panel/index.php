<?php
	session_start();
	require "includes/ts3admin/lib/ts3admin.class.php";
	require "includes/handles/header.php";
	require "includes/handles/loginchecker.php";
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://USERNAME:PASSWORD@SERVER_IP:QUERY_PORT/?server_port=9987&nickname=Legacy");
	$counter = 1;

	/*
		Displays currently online clients
	*/


?>

<html>

<head>
	<title>Control Panel Home</title>
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
			<div class="navbar-header">
 				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a href="index.php" class="navbar-brand">Crusty Control Panel</a>
			</div>
 			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
  				<li class="active"><a href="#">Currently Online</a></li>
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
  					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Welcome back <?php echo $_SESSION['display'] ?>! <span class="caret"></span></a>
    				<ul class="dropdown-menu">
							<li><a href="#">Messages</a></li>
							<li><a href="settings.php">Account Settings</a></li>
    					<li><a href="auth/logout.php">Logout</a></li>
    				</ul>
					</li>
				</ul>
			</div>
		</div>
	</header>
	<div class="container">
		<?php
			// never actually got to finish logs so, this snippet of code is actually pretty useless right now
		if ($_SESSION['access'] >= 11) {
			echo '<div class="alert alert-success alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Congrats!</strong> You are a special snowflake and will not be logged on your actions, fire away coach.
			</div>';
		}
		?>
		<div class="head">Online Clients</div>
		<div class="pan-body">
			<table class="table">
  			<thead>
  				<tr>
						<th>Client Number</th>
						<th>Client Database ID</th>
    				<th>Client</th>
						<?php
						if ($_SESSION['access'] >= 10) {
  						echo '<th>IP</th>';
						}
						?>
      			<!--	<th>UID</th>
						<th>Connection Client</th> -->
						<th>More</th>
						<?php
						if ($_SESSION['access'] >= 7) {
							echo '<th>kick</th>';
							echo '<th>Ban</th>';
						}
						if ($_SESSION['access'] >= 10) {
							//	echo '<th>Proxy</th>';
						}
						?>
   				</tr>
  			</thead>
			<tbody>
			<?php
			if ($_SESSION['access'] >= 4) {
				$arr_ClientList = $ts3_VirtualServer->clientList();
			  //			print_r($arr_ClientList);
				foreach($arr_ClientList as $ts3_Client)
			  {
					if ($ts3_Client['client_platform'] != 'ServerQuery') {
						echo '<tr>';
						echo '<td>'.$counter.'</td>';
						echo '<td>'.$ts3_Client['client_database_id'].'</td>';
						echo '<td>'.$ts3_Client.'</td>';
						if($_SESSION['access'] < 10) {

						} elseif ($ts3_Client["client_unique_identifier"] != 'RtyDh+D3ccger+TOMeGrfXimL0s=') {
							echo '<td><a target="_blank" href="https://whatismyipaddress.com/ip/'.$ts3_Client["connection_client_ip"].'">'.$ts3_Client["connection_client_ip"].'</a></td>';
						} else {
							echo '<td>Protected</td>';
						}
						//	echo '<td>'.$ts3_Client["client_unique_identifier"].'</td>';
						//	echo '<td>'.$ts3_Client["client_platform"].'</td>';
						echo '<td><a target="_blank" href="admin_tools.php?dbid='.$ts3_Client["client_database_id"].'" class="btn btn-xs btn-success">Info</a></td>';
						if ($_SESSION['access'] >= 7) {
							echo '<td><a href="kick.php?Dbid='.$ts3_Client["client_database_id"].'" class="btn btn-xs btn-warning">Kick</a></td>';
							echo '<td><a class="btn btn-xs btn-danger disabled">Ban</a></td>';
						}
						if ($_SESSION['access'] >= 9) {
						//	echo '<td><a target="_blank" href="vpn.php?ip='.$ts3_Client["connection_client_ip"].'"class="btn btn-xs btn-primary">Check</a></td>';
						}
						if ($ts3_Client["client_servergroups"] == '8') {
							echo '<td><strong>GUEST</strong></td>';
						}
						$counter++;
					}
				}
			}
			?>
			</tbody>
		</table>
	</div>
</div>

</body>

</html>
