<?php
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	require "Includes/header.php";
	if ($staff_name != "") {
		if ($staff_power <= 1) {
			if (isset($_POST["chname"])) {
				$ts3_VirtualServer = TeamSpeak3::factory("serverquery://QUERY USERNAME:QUERY PASSWORD@SERVER IP:10011/?server_port=9987&nickname=AIMBot");
				if ($_POST["chtype"] == "Voice") {
					$top_cid = $ts3_VirtualServer->channelCreate(array(
						"channel_name" => $_POST['chname'],
						"channel_topic" => "",
						"channel_codec" => TeamSpeak3::CODEC_OPUS_VOICE,
						"channel_flag_permanent" => TRUE,
						"cpid" => $_POST['schid'],
					));
				} else {
					$top_cid = $ts3_VirtualServer->channelCreate(array(
						"channel_name" => $_POST['chname'],
						"channel_topic" => "",
						"channel_codec" => TeamSpeak3::CODEC_OPUS_MUSIC,
						"channel_flag_permanent" => TRUE,
						"cpid" => $_POST['schid'],
					));
				}
			}
		}
	}
?>
<html>
	<head>
		<title>Create a Teamspeak 3 Channel</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	</head>
	<body>
		<div class="container">
			<div class="jumbotron">
				<h1>Create a new channel</h1>
			</div>
			<form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
				<div class="form-group">
					<label for="chname">Channel Name:</label>
					<input type="text" class="form-control" name="chname" placeholder="Channel Name to Create" required></input>
				</div>
				<div class="form-group">
					<label for="schid">Sub-Channel ID:</label>
					<input type="Text" class="form-control" name="schid" placeholder="eg: '66'" required></input>
				</div>
				<div class="radio">
					<label><input type="radio" name="chtype" value = "Voice" required>Voice</label>
					<label><input type="radio" name="chtype" value = "Music" required>Musc</label>
				<div>
				<hr>
  				<button type="submit" class="btn btn-default">Create</button>
			</form>
		</div>
	</body>
</html>
