<?php
	session_start();
	require "includes/ts3admin/lib/ts3admin.class.php";
	require "includes/handles/header.php";
	require "includes/handles/loginchecker.php";
	require "includes/handles/functions.php";
	require "includes/db/db.php";
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://USERNAME:PASSWORD@SERVER_IP:QUERY_PORT/?server_port=9987&nickname=Legacy");
	if ($_SESSION['access'] < 7) {
		die ("Insufficient Permissions, Please log on an authorized account");
	}
$ts3Admin = new ts3admin('' /* Server IP */, 10011 /* Query Port */);
	$username=""; // Query Username
	$password=""; // Query Password
	$ts3_Port = 9987; // Server Port

	$ip = $_SERVER['REMOTE_ADDR'];
	$tsAdmin = new ts3admin('' /* Server IP */, 10011 /* Query Port */);
	if($tsAdmin->getElement('success', $tsAdmin->connect())) {
		$tsAdmin->login($username,$password);

		$tsAdmin->selectServer($ts3_Port);

		$clients = $tsAdmin->clientList("-ip -groups");

		$tsAdmin->setName('Legacy'.rand(1000,9999));

		$counter = 0;

		foreach ($clients['data'] as $client) {
			if ($client['connection_client_ip'] == $ip) {
				$counter++;
			}
		}
		if ($counter > 0 && $counter < 2) {
			foreach ($clients['data'] as $client) {
				if ($client['connection_client_ip'] == $ip) {
					if ((strpos($client['client_servergroups'], ',307') === false) ) {
						die("Admin panel Access is not allowed");
					}
				}
			}
		} else {
			die("No connection found or multiple connections found. Access denied.");
		}
	} else {
		echo "Connection to server failed";
	}
	if (isset($_GET['id'])) {
		$id = escape_input($_GET['id']);
		$sql = "SELECT * FROM members WHERE id='$id'";
		$run_sql = mysqli_query($conn,$sql);
		$rows = mysqli_fetch_assoc($run_sql);

		$display_name = $rows['display_name'];
		$email = $rows['email'];
		$register_ip = $rows['ip'];
		$reference = $rows['ref'];
		$time = $rows['reg_date'];

		$uid = array();
		$dbid = array();
		$sql_1 = "SELECT * FROM ts3_identities WHERE profile='$email'";
		$run_sql_1 = mysqli_query($conn,$sql_1);
		while ($rows_1 = mysqli_fetch_assoc($run_sql_1)) {
			$uid[] = $rows_1['uid'];
			$dbid[] = $rows_1['dbid'];
		}

		$summoner_name = array();
		$summoner_id = array();
		$summoner_rank = array();
		$sql_2 = "SELECT * FROM lol_accounts WHERE profile='$email'";
		$run_sql_2 = mysqli_query($conn,$sql_2);
		while ($rows_2 = mysqli_fetch_assoc($run_sql_2)) {
			$summoner_name[] = $rows_2['account_name'];
			$summoner_id[] = $rows_2['account_id'];
			$summoner_rank[] = $rows_2['account_rank'];

		}

	} else {
		die ("Listen you nig, use it as it is meant to be used");
	}

	if (isset($_GET['action'])) {
		if ($_GET['action'] == 'tourney_ban') {
			$sql1 = "SELECT * FROM members WHERE id='$_GET[id]'";
			$run_sql = mysqli_query($conn,$sql1);
			$rows = mysqli_fetch_assoc($run_sql);

			if ($rows['tourney_banned'] != 1) {
				$amount_of_identities = count($dbid);
				for ($i = 0; $i < $amount_of_identities; $i++) {
					$ts3_VirtualServer->serverGroupClientAdd(66,$dbid[$i]);
				}
				$sql = "UPDATE members SET tourney_banned='1' WHERE id='$_GET[id]'";
				mysqli_query($conn,$sql);
				$returned = '<strong style="color:green">Client has been tournament banned successfully.</strong>';
			} else {
				$returned = '<strong style="color:red">Failed to ban client, client is already tournament banned.</strong>';
			}

		} elseif ($_GET['action'] == 'ban') {
			if ($_SESSION['access'] >= 12) {

				$sql1 = "SELECT * FROM members WHERE id='$_GET[id]'";
				$run_sql = mysqli_query($conn,$sql1);
				$rows = mysqli_fetch_assoc($run_sql);

				if ($rows['banned'] != 1) {
					$amount_of_identities = count($dbid);
					$kekAdmin = new ts3admin('66.70.198.65', 10011);
					$username="ServerReg";
					$password="xqZJwrnt";
					$ts3_Port = 9987;
					if($kekAdmin->getElement('success', $kekAdmin->connect())) {
						$kekAdmin->login($username,$password);
						$kekAdmin->selectServer($ts3_Port);
						$kekAdmin->setName('EternityBot');
						for ($i = 0; $i < $amount_of_identities; $i++) {
							$info = $kekAdmin->clientDbInfo($dbid[$i]);
							$rules = array(
								'ip' => $info['data']['client_lastip'],
								'uid' => $info['data']['client_unique_identifier']
							);
							$ts3_VirtualServer->banCreate($rules,0,'Requested through control panel');
						}
					}
					$sql = "UPDATE members SET banned='1' WHERE id='$_GET[id]'";
					mysqli_query($conn,$sql);
					$returned = '<strong style="color:green">Client banned successfully. Please remember, to unban the client you need to remove the ban from the ban list as well.</strong>';
				} else {
					$returned = '<strong style="color:red">Cannot ban client, client is already banned</strong>';
				}
			}
		} elseif ($_GET['action'] == 'account_delete') {
			if ($_SESSION['access'] >= 12) {
				$sql = "DELETE FROM members WHERE id='$_GET[id]'";
				mysqli_query($conn,$sql);
				$sql1 = "DELETE FROM ts3_identities WHERE profile='$email'";
				mysqli_query($conn,$sql1);
				$sql2 = "DELETE FROM lol_accounts WHERE profile='$email'";
				mysqli_query($conn,$sql2);
				echo '<meta http-equiv="refresh" content="0; url=/reg.php" />';
			}
		} elseif ($_GET['action'] == 'tourney_suspend') {
			$sql1 = "SELECT * FROM members WHERE id='$_GET[id]'";
			$run_sql = mysqli_query($conn,$sql1);
			$rows = mysqli_fetch_assoc($run_sql);

			if ($rows['tourney_sus'] != 1) {
				$amount_of_identities = count($dbid);
				for ($i = 0; $i < $amount_of_identities; $i++) {
					$ts3_VirtualServer->serverGroupClientAdd(65,$dbid[$i]);
					$sql = "UPDATE members SET tourney_sus='1' WHERE id='$_GET[id]'";
					mysqli_query($conn,$sql);
				}
				$returned = '<strong style="color:green">Client has been suspended from PUG tournaments</strong>';
			} else {
				$returned = '<strong style="color:red">Failed! ERR MSG: Client is already suspended</strong>';
			}

		} elseif ($_GET['action'] == 'tourney_suspend_remove') {

			$sql1 = "SELECT * FROM members WHERE id='$_GET[id]'";
			$run_sql = mysqli_query($conn,$sql1);
			$rows = mysqli_fetch_assoc($run_sql);

			if ($rows['tourney_sus'] != 0) {
				$sql = "UPDATE members SET tourney_sus='0' WHERE id='$_GET[id]'";
				mysqli_query($conn,$sql);
				$amount_of_identities = count($dbid);
				for ($i = 0; $i < $amount_of_identities; $i++) {
					$ts3_VirtualServer->serverGroupClientDel(65,$dbid[$i]);
				}
				$returned = '<strong style="color:green">Successfully removed tournament suspension from client</strong>';
			} else {
				$returned = '<strong style="color:red">Failed to suspend client. ERR MSG: Client already suspended</strong>';
			}

		} elseif ($_GET['action'] == 'tourney_ban_remove') {

			$sql1 = "SELECT * FROM members WHERE id='$_GET[id]'";
			$run_sql = mysqli_query($conn,$sql1);
			$rows = mysqli_fetch_assoc($run_sql);
			if ($rows['tourney_banned'] != 0) {
				$sql = "UPDATE members SET tourney_banned='0' WHERE id='$_GET[id]'";
				mysqli_query($conn,$sql);
				$amount_of_identities = count($dbid);
				for ($i = 0; $i < $amount_of_identities; $i++) {
					$ts3_VirtualServer->serverGroupClientDel(66,$dbid[$i]);
				}
				$returned = '<strong style="color:green">Successfully tournament unbanned client</strong>';
			} else {
				$returned = '<strong style="color:red">Failed. ERR MSG: Client is not tournament banned</strong>';
			}

		} elseif ($_GET['action'] == 'ban_remove') {

			$sql1 = "SELECT * FROM members WHERE id='$_GET[id]'";
			$run_sql = mysqli_query($conn,$sql1);
			$rows = mysqli_fetch_assoc($run_sql);
			if ($rows['banned'] != 0) {
				$sql = "UPDATE members SET banned='0' WHERE id='$_GET[id]'";
				mysqli_query($conn,$sql);
				$returned = '<strong style="color:green">Successfully Unbanned client. You need to manually remove the ban from the teamspeak 3 ban list though.</strong>';
			} else {
				$returned = '<strong style="color:red">Failed to ban client. <br>ERR MSG: Client is already banned</strong>';
			}
		}
	} else {
		$returned = '';
	}

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
	margin-bottom: 50px;
}

.container-fluid-cust {
	color: white;
	margin: 0px;
	padding: 30px 15px;
}

.head {
	margin:0px;
	padding: 15px 10px;
	color: white;
	background-color: #5a5b5b;
}

.pan-body {
	margin: 0px;
	margin-bottom: 30px;
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
	color: white;
	background-attachment: fixed;
}

input[type=text] {
	background-color: transparent;
	border: none;
	border-bottom: 1px solid white;
	border-radius: 0px;
	outline: none;
	color: white;
}
input[type=word] {
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

select {
	background-color: transparent;

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
				<li class="active"><a href="reg.php">Members</a></li>
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
  	</header>

	<div class="container">
		<div class="head">
			<div class="row">
				<div class="col-lg-9">
					<strong>Viewing Profile for: </strong><?php echo $display_name ?><br>Profile ID: <?php echo $id ?>
				</div>
				<div class="col-lg-3">
				<?php
					$sql = "SELECT * FROM members WHERE id='$_GET[id]'";
					$run_sql = mysqli_query($conn,$sql);
					$rows = mysqli_fetch_assoc($run_sql);
					if ($rows['tourney_sus'] != 1) {
						echo '<a href="?id='.$id.'&action=tourney_suspend" class="btn btn-warning btn-xs btn-block">Tourney Suspend Client</a>';
					} else {
						echo '<a href="?id='.$id.'&action=tourney_suspend_remove" class="btn btn-success btn-xs btn-block">Remove Tourney Suspension</a>';
					}
					if ($rows['tourney_banned'] != 1) {
						echo '<a href="?id='.$id.'&action=tourney_ban" class="btn btn-danger btn-xs btn-block">Tourney Ban Client</a>';
					} else {
						echo '<a href="?id='.$id.'&action=tourney_ban_remove" class="btn btn-success btn-xs btn-block">Remove Tourney Ban</a>';
					}
					if ($rows['banned'] != 1) {
						echo '<a href="?id='.$id.'&action=ban" class="btn btn-danger btn-xs btn-block">Ban Client</a>';
					} else {
						echo '<a href="?id='.$id.'&action=ban_remove" class="btn btn-success btn-xs btn-block">Unban</a>';
					}
					echo '<a href="?id='.$id.'&action=account_delete" class="btn btn-danger btn-xs btn-block">Delete Account</a>';

				?>
				</div>
			</div>
		</div>
		<div class="pan-body">
			<div class="row" style="text-align:left">
				<?php
					if ($returned != '') {
						echo $returned.'<hr>';
					}
				?>
				<div class="col-lg-12">
					<strong style="color:cyan"><u>General Profile Information:</u></strong>
				</div>
			</div><br>
			<div class="row" style="text-align:left">
				<div class="col-lg-2">
					E-Mail:
				</div>
				<div class="col-lg-10">
					<?php
						if ($email != '') {
							echo $email;
						} else {
							echo 'Not Found';
						}
					?>
				</div>
				<div class="col-lg-2">
					Display Name:
				</div>
				<div class="col-lg-10">
					<?php
						if ($display_name != '') {
							echo $display_name;
						} else {
							echo 'Not Found';
						}
					?>
				</div>
				<div class="col-lg-2">
					Registration Reference:
				</div>
				<div class="col-lg-10">
					<?php
						if ($reference != '') {
							echo $reference;
						} else {
							echo 'Not Found';
						}
					?>
				</div>
				<div class="col-lg-2">
					Registration Date:
				</div>
				<div class="col-lg-10">
					<?php
						if ($time != '') {
							echo $time;
						} else {
							echo 'Not Found';
						}
					?>
				</div>
				<?php
					if ($_SESSION['access'] >= 9) {
						echo '
							<div class="col-lg-2">
								Registration IP:
							</div>
							<div class="col-lg-10">';
								if ($register_ip != '') {
									if ($id != 102 && $id != 110) {
										echo '<a href="http://whatismyipaddress.com/ip/'.$register_ip.'">'.$register_ip.'</a>';
									} else {
										echo 'Insufficient Permissions';
									}
								} else {
									echo 'Not Found';
								}
							echo '
							</div>
						';
					}
				?>
			</div>
			<hr>
			<strong style="color:cyan"><u>Teamspeak Identities Linked to this Profile:</u></strong> <br><br>
			<div class="row">
				<div class="col-lg-2">
					TS3 UID(s):
				</div>
				<div class="col-lg-10">
					<?php
						$number_of_identities = count($uid);
						if ($number_of_identities != 0) {
							for ($i = 0; $i < $number_of_identities; $i++) {
								echo $uid[$i].'<br>';
							}
						} else {
							echo 'Not Found';
						}
					?>
				</div>
				<div class="col-lg-2">
					TS3 DBID(s):
				</div>
				<div class="col-lg-10">
					<?php
						$number_of_identities = count($dbid);
						if ($number_of_identities != 0) {
							for ($i = 0; $i < $number_of_identities; $i++) {
								echo $dbid[$i].'--> <a target="_blank" href="admin_tools.php?dbid='.$dbid[$i].'" class="btn btn-xs btn-success">Controls</a><br>';
							}
						} else {
							echo 'Not Found';
						}
					?>
				</div>
			</div>
			<hr>
			<strong style="color:cyan"><u>League of Legends accounts linked to this profile:</u></strong> <br><br>
			<div class="row">
				<div class="col-lg-2">
					LoL Summoner:
				</div>
				<div class="col-lg-10">
					<?php
						$number_of_accounts = count($summoner_name);
						if ($number_of_accounts != 0) {
							for ($i = 0; $i < $number_of_accounts; $i++) {
								echo $summoner_name[$i];
							}
						} else {
							echo 'Not Found';
						}
					?>
				</div>
				<div class="col-lg-2">
					LoL ID:
				</div>
				<div class="col-lg-10">
					<?php
						$number_of_accounts = count($summoner_id);
						if ($number_of_accounts != 0) {
							for ($i = 0; $i < $number_of_accounts; $i++) {
								echo $summoner_id[$i];
							}
						} else {
							echo 'Not Found';
						}
					?>
				</div>
				<div class="col-lg-2">
					LoL Rank:
				</div>
				<div class="col-lg-10">
					<?php
						$number_of_accounts = count($summoner_rank);
						if ($number_of_accounts != 0) {
							for ($i = 0; $i < $number_of_accounts; $i++) {
								echo $summoner_rank[$i];
							}
						} else {
							echo 'Not Found';
						}
					?>
				</div>
			</div>
			<hr>
			<?php
				if ($_SESSION['access'] > 10) {
				echo '
					<hr>
					<div class="row">
						<div class="col-lg-2">
							Staff present during registration:
						</div>
						<div class="col-lg-10">';
				$number_of_identities = count($dbid);
				for ($i = 0; $i < $number_of_identities; $i++) {
					$sql = "SELECT * FROM registerer WHERE member='$dbid[$i]'";
					$run_sql = mysqli_query($conn,$sql);

					if (mysqli_num_rows($run_sql) != 0) {
						if($ts3Admin->getElement('success', $ts3Admin->connect())) {
							$ts3Admin->login($username,$password);

							$ts3Admin->selectServer($ts3_Port);
							while ($rows = mysqli_fetch_assoc($run_sql)) {

								$channel = $ts3Admin->channelInfo($rows['channel']);

								echo 'Channel of Registration: '.$channel['data']['channel_name'].'<br>';

								echo 'Staff Name: <strong style="color:red">'.$rows['staff_name'].'</strong><br>';
								echo 'Staff UID: <strong style="color:red">'.$rows['staff_uid'].'</strong><br>';
								echo 'Staff DBID: <strong style="color:red">'.$rows['dbid'].'</strong><br>';
								echo 'Idle Timer (s): <strong style="color:red">'.$rows['idle_timer'].'</strong><br>';
								echo '<hr>';
							}
							break;
						}
					} else {
						echo 'No one, #Triggered';
					}
				}
				echo '
				</div>
			</div>';
			}
			?>
		</div>
	</div>
</body>

</html>
