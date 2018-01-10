<?php
	session_start();
	require "includes/ts3admin/lib/ts3admin.class.php";
	require "includes/handles/header.php";
	require "includes/handles/loginchecker.php";
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	if (!isset($_GET['dbid'])) {
		die ('Listen here you nig, use this system as it is meant to be used or i will fking end your bish ass.');
	}
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://QUERY USERNAME:QUERY PASSWORD@SERVER_IP:10011/?server_port=9987&nickname=EternityBot");
	$counter = 1;
	$current_groups = array();
	$ts3Admin = new ts3admin('SERVER IP' , 10011);
	$tsAdmin = new ts3admin('SERVER IP', 10011);
	$username="ServerReg";
	$password="xqZJwrnt";
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

<script>
function modifyGroup(dbid, group) {
	if ( document.getElementById(group).className.match(/(?:^|\s)btn-primary(?!\S)/) ) {
		document.getElementById(group).className="btn btn-danger btn-xs btn-block";
	} else {
		document.getElementById(group).className="btn btn-primary btn-xs btn-block";
	}
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		}
	};
	xhttp.open("GET", "modifyGroups.php?dbid="+dbid+"&group="+group, true);
	xhttp.send();
}
</script>

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
</style>

<body class="bg">
	<div class="container">
		<div class="head">Admin Tools</div>
			<div class="pan-body">
				<strong style="color:white;text-align:left">
				<h2>General Information</h2><br>
				<?php
					if($ts3Admin->getElement('success', $ts3Admin->connect())) {
						$ts3Admin->login($username,$password);
						$ts3Admin->selectServer($ts3_Port);
						$ts3Admin->setName('EternityBot');
						$info = $ts3Admin->clientDbInfo($_GET['dbid']);
						$groups = $ts3Admin->serverGroupsByClientID($_GET['dbid']);
						if ($info['success'] == 1) {
							echo '<div class="row">
								<div class="col-lg-4">
									Client Name
								</div>
								<div class="col-lg-8">';
							echo $info['data']['client_nickname'].'<br>';
							echo '</div>	
							</div>';
							echo '<div class="row">
								<div class="col-lg-4">
									UID
								</div>
								<div class="col-lg-8">';
							echo $info['data']['client_unique_identifier'].'<br>';
							echo '</div>	
							</div>';
							echo '<div class="row">
								<div class="col-lg-4">
									DBID
								</div>
								<div class="col-lg-8">';
							echo $info['data']['client_database_id'].'<br>';
							echo '</div>	
							</div>';
							echo '<div class="row">
								<div class="col-lg-4">
									Description
								</div>
								<div class="col-lg-8">';
							echo $info['data']['client_description'].'<br>';
							echo '</div>	
							</div>';
							echo '<div class="row">
								<div class="col-lg-4">
									Client Last IP 
								</div>
								<div class="col-lg-8">';
							if ($_SESSION['access'] >= 9) {
								if ($info['data']['client_unique_identifier'] != '5e+IoliG5F2L6Ht7/7QboS7tpM4=' && $info['data']['client_unique_identifier'] != 'nKCNAmU2pMH/qUF7mVRH7ejAiGk=' && $info['data']['client_unique_identifier'] != 'vwGyGtYZ+7UVY98RrjL6+kEZJQc=') {
									echo $info['data']['client_lastip'].'<br>';
								} else {
									echo 'Admin accounts are protected';
								}
							} else {
								echo 'Insufficient Permissions';
							}
							echo '</div>	
							</div>';
							echo '<hr><div class="row">
								<div class="col-lg-4">
									Server Groups
								</div>
								<div class="col-lg-8">';
							echo '<ul>';
								foreach ($groups['data'] as $group) {
									$current_groups[] = $group['sgid'];
									echo '<li>'.$group['name'].'</li>';
								}
							echo '</ul></div>	
							</div>';
						} else {
							echo '<strong style="color:white">'.$info['errors'][0].'</strong>';
						}
					}
				?>
				<hr>
				<?php
					if ($_SESSION['access'] >= 9) {
						if($tsAdmin->getElement('success', $tsAdmin->connect())) {
							$tsAdmin->login($username,$password);
							$tsAdmin->selectServer($ts3_Port);
							$tsAdmin->setName('Legacy');
							echo '<h2>Adminstrative Tools</h2>';
							echo '<h3>Group Management</h3>';
							$info = $tsAdmin->serverGroupList();
							$count = 0;
							echo '<div class="row">';
							foreach ($info['data'] as $in) {
								echo '<div class="col-lg-3">';
								$count++;
								if (!in_array($in['sgid'],$current_groups)) {
									echo '<button class="btn btn-xs btn-primary btn-block" id="'.$in['sgid'].'" onclick="modifyGroup('.$_GET['dbid'].','.$in['sgid'].')">'.$in['name'].' ';
								} else {
									echo '<button class="btn btn-xs btn-danger btn-block" id="'.$in['sgid'].'" onclick="modifyGroup('.$_GET['dbid'].','.$in['sgid'].')">'.$in['name'].' ';
								}
							/*	$icon = $tsAdmin->serverGroupGetIconBySGID($in['sgid']);
								
								if ($icon['data'] != '') {
									//echo '<img src="data:image/png;base64,'.$icon["data"].'" /></button>';
								}*/
								echo '</div>';
							}
							echo '</div>';
							echo '<h3>Staff Management</h3>';
							if (in_array(30,$current_groups)) {
								echo '<span style="color:red">Client is currently staff</span><br>';
		
								$admins = array();
								$admins['Bat Admin'] = 11;
								$admins['Queen Admin'] = 185;
								$admins['Community Admin'] = 136;
								$admins['Tech Admin'] = 333;
								
								$moderationDepartment = array();
								$moderationDepartment['Head Moderator'] = 41;
								$moderationDepartment['Moderator'] = 40;
								$moderationDepartment['Moderator IT'] = 18;
								$moderationDepartment[41] = 'Head Moderator';
								$moderationDepartment[40] = 'Moderator';
								$moderationDepartment[18] = 'Moderator IT';

								$organizationDepartment = array();
								$organizationDepartment['Head Organizer'] = 55;
								$organizationDepartment['Organizing Advisor'] = 64;
								$organizationDepartment['Organizer'] = 45;
								$organizationDepartment['Organizer IT'] = 44;
								$organizationDepartment[55] = 'Head Organizer';
								$organizationDepartment[64] = 'Organizing Advisor';
								$organizationDepartment[45] = 'Organizer';
								$organizationDepartment[44] = 'Organizer IT';

								$eventsDepartment = array();
								$eventsDepartment['Head of Events'] = 56;
								$eventsDepartment['Events Advisor'] = 63;
								$eventsDepartment['Event Staff'] = 54;
								$eventsDepartment['Event Staff IT'] = 53;
								$eventsDepartment[56] = 'Head of Events';
								$eventsDepartment[63] = 'Events Advisor';
								$eventsDepartment[54] = 'Event Staff';
								$eventsDepartment[53] = 'Event Staff IT';

								$recruitingDepartment = array();
								$recruitingDepartment['Head Recruiter'] = 195;
								$recruitingDepartment['Recruiting Advisor'] = 196;
								$recruitingDepartment['Recruiter'] = 197;
								$recruitingDepartment['Recruiter IT'] = 198;
								$recruitingDepartment[195] = 'Head Recruiter';
								$recruitingDepartment[196] = 'Recruiting Advisor';
								$recruitingDepartment[197] = 'Recruiter';
								$recruitingDepartment[198] = 'Recruiter IT';

								$minecraftDepartment = array();
								$minecraftDepartment['Minecraft Manager'] = 204;
								$minecraftDepartment ['Minecraft Staff'] = 191;
								$minecraftDepartment[204] = 'Minecraft Manager';
								$minecraftDepartment [191] = 'Minecraft Staff';

								$groups = $tsAdmin->serverGroupsByClientID($_GET['dbid']);
								foreach ($groups['data'] as $group) {
									if (in_array($group['sgid'],$admins)) {
										echo '<br>';
										echo '<span style="color:red">Client is an Admin. Admin accounts/identities cannot be modified through the control panel.</span>';
										echo '<br>';
									}
									if (in_array($group['sgid'],$moderationDepartment)) {
										echo '<br>';
										echo '<span style="color:red">Client is part of the moderation department. Current rank: <u>'.$moderationDepartment[$group['sgid']].'</u></span>';
										if ($group['sgid'] == 41) {
											echo '<hr>Client is currently at the highest rank in this department<hr>';
										}
										echo '<br>';
									}
									if (in_array($group['sgid'],$organizationDepartment)) {
										echo '<br>';
										echo '<span style="color:red">Client is part of the organization department. Current rank: <u>'.$organizationDepartment[$group['sgid']].'</u></span>';
										if ($group['sgid'] == 55) {
											echo '<hr>Client is currently at the highest rank in this department<hr>';
										}
										echo '<br>';
									}
									if (in_array($group['sgid'],$eventsDepartment)) {
										echo '<br>';
										echo '<span style="color:red">Client is part of the events department. Current rank: <u>'.$eventsDepartment[$group['sgid']].'</u></span>';
										if ($group['sgid'] == 56) {
											echo '<hr>Client is currently at the highest rank in this department<hr>';
										}
										echo '<br>';
									}
									if (in_array($group['sgid'],$recruitingDepartment)) {
										echo '<br>';
										echo '<span style="color:red">Client is part of the recruiting department. Current rank: <u>'.$recruitingDepartment[$group['sgid']].'</u></span>';
										if ($group['sgid'] == 195) {
											echo '<hr>Client is currently at the highest rank in this department<hr>';
										}
										echo '<br>';
									}
									if (in_array($group['sgid'],$minecraftDepartment)) {
										echo '<br>';
										echo '<span style="color:red">Client is part of the minecraft department. Current rank: <u>'.$minecraftDepartment[$group['sgid']].'</u></span>';
										if ($group['sgid'] == 204) {
											echo '<hr>Client is currently at the highest rank in this department<hr>';
										}
										echo '<br>';
									}
								}
							} else {
								echo '<span style="color:blue">Client is currently not staff</span>';
							}
							echo '<h3>Client Ban</h3>';
						} else {
							echo 'Connection failed';
						}
					}
				?>				
				</strong>
			</div>
		</div>
	</div>
</body>

</html>
