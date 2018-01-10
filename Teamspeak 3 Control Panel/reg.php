<?php
	session_start();
	require "includes/ts3admin/lib/ts3admin.class.php";
	require "includes/handles/header.php";
	require "includes/handles/loginchecker.php";
	require "includes/handles/functions.php";
	require "includes/db/db.php";
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://USERNAME:PASSWORD@SERVER_IP:QUERY_PORT/?server_port=9987&nickname=Legacy");
	if (!isset($_SESSION['username'])) {
		die ("Not logged in");
	}

	$ts3Admin = new ts3admin('' /*Server IP*/, 10011);
	$username=""; // query username
	$password=""; // query password
	$ts3_Port = 9987;

	// Second connection to server
	$tsAdmin = new ts3admin('' /*Server IP*/, 10011);

	$sql = "SELECT * FROM members";
	$run_sql = mysqli_query($conn,$sql);
	$counter = mysqli_num_rows($run_sql);

	// search by teamspeak 3 name
	if (isset($_GET['tsn'])) {
		if($tsAdmin->getElement('success', $tsAdmin->connect())) {
			$tsAdmin->login($username,$password);
			$tsAdmin->setName('Legacy');
			$tsAdmin->selectServer($ts3_Port);
			$found = $tsAdmin->clientDbFind($_GET['tsn'],false);

			$info_found = array();

			foreach ($found as $f) {
				foreach ($f as $ff) {
					$info = $tsAdmin->clientDbInfo($ff['cldbid']);
					$info_found['uid'.$ff['cldbid']] = $info['data']['client_unique_identifier'];
					$info_found['name'.$ff['cldbid']] = $info['data']['client_nickname'];
				}
			}
		} else {
			die("connection failed");
		}

		// search with teamspeak 3 UID
	} elseif (isset($_GET['ts3uid'])) {
		if($tsAdmin->getElement('success', $tsAdmin->connect())) {
			$tsAdmin->login($username,$password);
			$tsAdmin->setName("VisionBot");
			$tsAdmin->selectServer($ts3_Port);
			$foundFromUID = $tsAdmin->clientDbFind($_GET['ts3uid'], true);

			$info_found = array();
			$cldbidFromUID = $foundFromUID['data']['cldbid'];
		//	print_r($foundFromUID);
		} else {
			die ("connection to ts3 server failed");
		}
	}
?>

<html>

<head>
	<title>Control Panel Home</title>
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
	outline: none;
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
a {
	color: white;
	transition: color 1s;
}
a:hover {
	color: #c4c4c4;
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
					<?php
					if ($_SESSION['access'] > 5) {
    				echo '<li><a href="/">Currently Online</a></li>';
					}
					?>
					<li class="active"><a href="#">Members</a></li>
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
							<li><a href="settings.php">Account Settings</a></li>
							<li><a href="auth/logout.php">Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</header>
	<!-- Different Ways to search through profiles-->
	<div class="container">
		<div class="head">Profiles</div>
		<div class="pan-body">
			<div class="row">
				<form style="color:white">
					<div class="col-lg-2" style="text-align:left">
						Search by TS3 Name:
					</div>
					<div class="col-lg-10" style="text-align:left">
						<input type="text" name="tsn" required></input>
						<button type="submit" class="btn btn-xs btn-primary">Search</button>
					</div>
				</form>
				<form style="color:white">
					<div class="col-lg-2" style="text-align:left">
						Search by Dis Name:
					</div>
					<div class="col-lg-10" style="text-align:left">
						<input type="text" name="name" required></input>
						<button type="submit" class="btn btn-xs btn-primary">Search</button>
					</div>
				</form>
				<form style="color:white">
					<div class="col-lg-2" style="text-align:left">
						Search by UID:
					</div>
					<div class="col-lg-10" style="text-align:left">
						<input type="text" name="ts3uid" required></input>
						<button type="submit" class="btn btn-xs btn-primary">Search</button>
					</div>
				</form>
				<form style="color:white">
					<div class="col-lg-2" style="text-align:left">
						Search by Email:
					</div>
					<div class="col-lg-10" style="text-align:left">
						<input type="email" name="email" required></input>
						<button type="submit" class="btn btn-xs btn-primary">Search</button>
					</div>
				</form>
			</div>
			<hr>
			<?php
			if (!isset($found) && !isset($_GET['email']) && !isset($_GET['ts3uid']) && !isset($_GET['name'])) {
			echo '
			<table class="table">
				<thead>
					<th>ID</th>
					<th>Email</th>
				<thead>
				<tbody>';
			}
			/*
				Various Search Methonds/Displays
			*/
			// Display all users in database
			if (!isset($found) && !isset($_GET['name']) && !isset($_GET['email'])  && !isset($_GET['ts3uid'])) {
				$sql = "SELECT * FROM members ORDER BY id DESC";
				$run_sql = mysqli_query($conn,$sql);
				while ($rows = mysqli_fetch_assoc($run_sql)) {
					echo '<tr>';
					echo '<td>'.$counter.'</td>';
					$counter--;
					echo '<td><a href="view_profile.php?id='.$rows['id'].'">'.$rows['email'].'</a></td>';
					echo '</tr>';
				}

			// Display Search results based on Teamspeak 3 Name
			} elseif (isset($_GET['tsn'])) {
				echo '<div style="color:white">';
		//		print_r($found);
				echo $found['errors'][0];
				if ($found['errors'][0] == '') {
					$counter = 1;
					echo '<table class="table">
						<thead>
							<th>Entry</th>
							<th>dbid</th>
							<th>UID</th>
							<th>TS3 Name</th>
							<th>Email Found</th>
							<th>Tools</th>
						</thead>
						<tbody>';
					foreach($found as $f) {
						foreach ($f as $ff) {
							echo '<tr>';
							echo '<td>'.$counter.'</td><td>'.$ff['cldbid'].'</td>';
							echo '<td>'.$info_found['uid'.$ff['cldbid']].'</td>';
							echo '<td>'.$info_found['name'.$ff['cldbid']].'</td>';
							$sql = "SELECT * FROM ts3_identities WHERE dbid='$ff[cldbid]'";
							$run_sql = mysqli_query($conn,$sql);
							$rows = mysqli_fetch_assoc($run_sql);
							if (mysqli_num_rows($run_sql) === 0) {
								$email = 'Not Found';
							} else {
								$sql1 = "SELECT * FROM members WHERE email='$rows[profile]'";
								$run_sql1 = mysqli_query($conn,$sql1);
								$r = mysqli_fetch_assoc($run_sql1);
								$email = '<a style="color:cyan" href="view_profile.php?id='.$r['id'].'">'.$rows['profile'].'</a>';
							}
							echo '<td>'.$email.'</td>';
							echo '<td><a target="_blank" class="btn btn-xs btn-success" href="admin_tools.php?dbid='.$ff['cldbid'].'">Tools</a></td>';
							echo '</tr>';
							$counter++;
						}
					}
					echo '</tbody></table>';
				}
				echo '</div>';

			// Display user basedo n e-mail search Query (searched the database to match an email)
			} elseif (isset($_GET['email'])) {
				$email = escape_input($_GET['email']);
				$sql = "SELECT * FROM members WHERE email='$email'";
				$run_sql = mysqli_query($conn,$sql);
				if (mysqli_num_rows($run_sql) === 0) {
					echo 'No entries found';
				} else {
					$counter = 1;
					echo '<table class="table">
						<thead>
							<th>Entry Number</th>
							<th>Email</th>
							<th>Display Name</th>
							<th>TS3 Identities Found</th>
						</thead>
						<tbody>';
					while ($rows = mysqli_fetch_assoc($run_sql)) {
						echo '<td>'.$counter.'</td>';
						echo '<td><a href="view_profile.php?id='.$rows['id'].'" style="color:cyan">'.$rows['email'].'</a></td>';
						echo '<td>'.$rows['display_name'].'</td>';
						$sql1 = "SELECT * FROM ts3_identities WHERE profile='$email'";
						$run_sql1 = mysqli_query($conn,$sql1);
						echo '<td>'.mysqli_num_rows($run_sql1).'</td>';
					}
					echo '</tbody>';
				}
			// Search teamspeak 3 database from the teamspeak 3 unique ID
			} elseif (isset($_GET['ts3uid'])) {
				echo '<div style="color:white">'.$foundFromUID['errors'][0].'</div>';
				if ($foundFromUID['errors'][0] == '') {
					echo '<table class="table">
						<thead>
							<th>client DBID</th>
							<th>Found Profile</th>
						</thead>
						<tbody>';
						$dbidFound = $foundFromUID['data'][0]['cldbid'];
						echo '<tr>';
						echo '<td><a target="_blank" class="btn btn-xs btn-success" href="admin_tools.php?dbid='.$dbidFound.'">'.$dbidFound.'</a></td>';
						echo '<td>';
						$sql = "SELECT * FROM ts3_identities WHERE dbid='$dbidFound'";
						$run_sql = mysqli_query($conn,$sql);
						if (mysqli_num_rows($run_sql) != 0) {
							$rows = mysqli_fetch_assoc($run_sql);
							$sql1 = "SELECT * FROM members WHERE email='$rows[profile]'";
							$run_sql1 = mysqli_query($conn,$sql1);
							$r = mysqli_fetch_assoc($run_sql1);
							echo '<a href="view_profile.php?id='.$r['id'].'" style="color:cyan">'.$rows['profile'].'</a>';
						} else {
							echo 'Not Registered';
						}
						echo '</td></tr></tbody></table>';
				}
			// Search with forums display name	
			} elseif (isset($_GET['name'])) {
				$name = escape_input($_GET['name']);
				$sql = "SELECT * FROM members WHERE display_name LIKE '%$name%'";
				$run_sql = mysqli_query($conn,$sql);
				if (mysqli_num_rows($run_sql) !== 0) {
					echo '<table class="table">
						<thead>
							<th>Display Name</th>
							<th>Email</th>
							<th>TS3 Identities</th>
						</thead>
						<tbody>';
					while ($rows = mysqli_fetch_assoc($run_sql)) {
						echo '<tr><td>'.$rows['display_name'].'</td>';
						echo '<td><a href="view_profile.php?id='.$rows['id'].'" style="color:cyan">'.$rows['email'].'</a></td>';
						$sql1 = "SELECT * FROM ts3_identities WHERE profile='$rows[email]'";
						$mysql_run = mysqli_query($conn,$sql1);
						echo '<td>'.mysqli_num_rows($mysql_run).'</td>';
						echo '</tr>';
					}
					echo '</tbody></table>';
				} else {
					echo '<strong style="color:white">Not found</strong><br>';
				}
			}
			if (!isset($found) && !isset($_GET['name']) && !isset($_GET['email']) && !isset($_GET['ts3uid'])) {
			echo '
				</tbody>
			</table>';
			} else {
				echo '<a href="reg.php">Click here to return to all members page</a>';
			}
			?>
		</div>
	</div>
</body>

</html>
