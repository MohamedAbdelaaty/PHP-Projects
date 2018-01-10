<?php
	session_start();
	require "includes/ts3admin/lib/ts3admin.class.php";
	require "includes/handles/header.php";
	require "includes/handles/loginchecker.php";
	require "includes/db/db.php";

	$ts3Admin = new ts3admin(''/* Server IP */, 10011);

	$username=""; // Server Query Username
	$password=""; // Server Query Password
	$ts3_Port = 9987; // TS3 Server Port

	// GET method for number of players NOTE: This is a must unless you want to make your own verificaition method for the input ($_GET['players'] % 5 == 0)
	if (isset($_GET['players'])) {
		$players = $_GET['players'];
	} else {
		$players = 25;
	}

	// TODO: add a $plays % 5 scan, if != 0, create one more channel (to account for all players)
	$number_of_channels = $players/5;

	if (!isset($_SESSION['username'])) {
		die ("Not logged in");
	}
	if (isset($_GET['create']) && $_SESSION['access_level'] >= 9) {
		if($ts3Admin->getElement('success', $ts3Admin->connect())) {
			$ts3Admin->login($username,$password);

			$ts3Admin->selectServer($ts3_Port);
			$ts3Admin->setName('EternityBot');
			$month = date('m');
			$day = date('d');
			$year = date('o');

			$data = array (
				'CHANNEL_NAME' => 'Bracket A - '.$month.'/'.$day.'/'.$year,
				'CHANNEL_MAXCLIENTS' => 0,
				'CHANNEL_MAXFAMILYCLIENTS' => 0,
				'CHANNEL_FLAG_MAXCLIENTS_UNLIMITED' => 0,
				'CHANNEL_FLAG_MAXFAMILYCLIENTS_UNLIMITED' => 0,
				'CHANNEL_FLAG_PERMANENT' => 1,
				'CPID' => 423
			);

			$create_channel = $ts3Admin->channelCreate($data);
			$channel_id= $create_channel['data']['cid'];
			$id = 2;
			$sql = "INSERT INTO tourney_bracket (id,bracket_cid) VALUES ('$id','$channel_id')";
			$run_sql = mysqli_query($conn,$sql);
			for ($i = 1; $i <= $number_of_channels; $i++) {
				$data_sub = array (
					'CHANNEL_NAME' => 'Team '.$i,
					'CHANNEL_PASSWORD' => 'TourneyRoom',
					'CHANNEL_MAXCLIENTS' => 5,
					'CHANNEL_MAXFAMILYCLIENTS' => 5,
					'CHANNEL_FLAG_MAXCLIENTS_UNLIMITED' => 0,
					'CHANNEL_FLAG_MAXFAMILYCLIENTS_UNLIMITED' => 0,
					'CHANNEL_CODEC_QUALITY' => 6,
					'CHANNEL_FLAG_PERMANENT' => 1,
					'CPID' => $create_channel['data']['cid']
				);
				$create_team_channels = $ts3Admin->channelCreate($data_sub);
			}

		} else {
			echo 'Connection Failed';
		}
	} elseif (isset($_GET['destroy']) && $_SESSION['access_level'] >= 9) {
		if($ts3Admin->getElement('success', $ts3Admin->connect())) {
			$ts3Admin->login($username,$password);

			$ts3Admin->selectServer($ts3_Port);
			$ts3Admin->setName('EternityBot');
			$sql = "SELECT bracket_cid FROM tourney_bracket WHERE id=2";
			$run_sql = mysqli_query($conn,$sql);
			$row = mysqli_fetch_assoc($run_sql);
			$channel_delete = $ts3Admin->channelDelete($row['bracket_cid'], 1);
			$sql = "DELETE FROM tourney_bracket WHERE id=2";
			$run_sql = mysqli_query($conn,$sql);
		} else {
			echo 'Connection Failed';
		}
	} else {
		echo 'nope';
	}
