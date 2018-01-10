<?php
	session_start();
	require "includes/ts3admin/lib/ts3admin.class.php";
	require "includes/handles/functions.php";
	require "includes/db/db.php";
	
	$ts3Admin = new ts3admin('SERVER IP', 10011);
		
	$username="";
	$password="";
	$ts3_Port = 9987;
	
	// Teamspeak3 Role Ids for the following roles
	$groups = array (
		"GOLD" => 292,
		"BRONZE" => 290,
		"SILVER" => 291,
		"PLATINUM" => 293,
		"DIAMOND" => 294,
		"MASTER" => 295,
		"CHALLENGER" => 296,
		"I" => 297,
		"II" => 298,
		"III" => 299,
		"IV" => 300,
		"V" => 301
	);
	if (isset($_SESSION['ProxyPass'])) {
		if (!$_SESSION['ProxyPass'] == true) {
			die ("Proxy check failed");
		}
	} else {
		die ("Proxy verification not found");
	}

	if (!isset($_SESSION['account_verification_confirmation']) && $_SESSION['summoner_name'] != '') {
		die ('Verification Failed/Skipped for league of legends account');
	}
	
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://QUERY USERNAME:QUERY PASSWORD@SERVER_IP:10011/?server_port=9987&nickname=RegBot");
	$sgid=7;
	if (!isset($_SESSION['read_timer'])) {
		die ('Rules not read! Redirecting in 5 seconds...<meta http-equiv="refresh" content="5; url=rules.php" />');
	}

	if (isset($_SESSION['dbid']) && isset($_SESSION['email']) && isset($_SESSION['password']) && isset($_SESSION['references'])) {
		$dbid = escape_input($_SESSION['dbid']);
		$email = escape_input($_SESSION['email']);
		$data = $_SESSION['password'];
		$password = hash('sha512',$data);
		$addr = $_SERVER['REMOTE_ADDR'];
		$name = escape_input($_SESSION['display_name']);
		$reference = escape_input($_SESSION['references']);
		$cldbid = $dbid;
			
		$client_info = $ts3_VirtualServer->clientGetByDbid($dbid)->infoDb();
		$uid = $client_info['client_unique_identifier'];

		if ($_SESSION['summoner_name'] != '') {
			$summoner_name = $_SESSION['summoner_name'];
			$summoner_id = $_SESSION['summoner_id'];
			$rank = $_SESSION['summoner_tier'].' '.$_SESSION['summoner_division'];
		} else {
			$summoner_name = 'not provided';
			$summoner_id = 'not provided';
			$rank = 'not provided';
		}

		$sql = "SELECT * FROM members WHERE email='$email'";
		$run_sql = mysqli_query($conn,$sql);
		if (mysqli_num_rows($run_sql) !== 0) {
			die ("Email Previously Registered");
		} else {
			$day = date("d");
			$month = date("m");
			$year = date("o");
			$reg_date = $month.'/'.$day.'/'.$year.' '.date("h:i:sa");
			$sql = "SELECT * FROM ts3_identities WHERE dbid='$dbid'";
			$run_sql = mysqli_query($conn,$sql);
			if (mysqli_num_rows($run_sql) !== 0) {
				die("The teamspeak 3 database id you are trying to register as has been previously registered; if you forgot your password, please request a password reset.");
			} else {
				$sql = "INSERT INTO members (email,password,ip,display_name,ref,reg_date) VALUES ('$email','$password','$addr','$name','$reference','$reg_date')";
				
				if ($conn->query($sql) === TRUE) {
					$sql_1 = "INSERT INTO ts3_identities (uid, dbid, profile) VALUES ('$uid','$dbid','$email')";
					if ($conn->query($sql_1) === TRUE) {
						$sql_2 = "INSERT INTO lol_accounts (account_name, account_id, account_rank, profile) VALUES ('$summoner_name','$summoner_id','$rank','$email')";
						if ($conn->query($sql_2) === TRUE) {
							$ts3_VirtualServer->serverGroupClientAdd($sgid,$cldbid);
							
							// Get staff members in channel during registration process
							
							if($ts3Admin->getElement('success', $ts3Admin->connect())) {
								$ts3Admin->login($username,$password);
									
								$ts3Admin->selectServer($ts3_Port);
								
								$channels = array (13,14,162);
								$channel_of_recruitment = 0;
								for ($i = 0; $i < count($channels); $i++) {
								
									$clients = $ts3Admin->channelClientList($channels[$i]);
									foreach ($clients['data'] as $client) {
										if ($client['client_database_id'] == $dbid) {
											$channel_of_recruitment = $channels[$i];
											break;
										}
									}
								
								}
								
								if ($channel_of_recruitment != 0) {
									$clients = $ts3Admin->channelClientList($channel_of_recruitment);
									foreach ($clients['data'] as $client) {
										if ($client['client_database_id'] != $dbid) {
											$groups = $ts3Admin->serverGroupsByClientID($client['client_database_id']);
											foreach  ($groups['data'] as $group) {
												if ($group['sgid'] == 30) {
													$client_information = $ts3Admin->clientInfo($client['clid']);
													$name = $client_information['data']['client_nickname'];
													$idle = (int)($client_information['data']['client_idle_time']/1000);
													$uid = $client_information['data']['client_unique_identifier'];
													$sql = "INSERT INTO registerer (channel,member,dbid,staff_uid,staff_name,idle_timer) VALUES ('$channel_of_recruitment','$dbid','$client[client_database_id]','$uid','$name','$idle')";
													$run_sql = mysqli_query($conn,$sql);
												}
											}
										}
									}
								}
							}
							echo "Registered, you may now close this page";
						} else {
							echo "Registration Failed - You may try again or contact a staff member";
							die();
						}
					} else {
						echo "Registration Failed - You may try again or contact a staff member";
						die(mysqli_error($conn));
					}
				} else {
					echo "Registration Failed - You may try again or contact a staff member";
					die();	
				}
			}
		}
		session_unset();
		session_regenerate_id(true);
		session_destroy();
	} else {
		session_unset();
		session_regenerate_id(true);
		session_destroy();
		die("Registration Process Error - Contact Staff");
	}
?>