<?php
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	require 'Includes/header.php';
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://QUERY USERNAME:QUERY PASSWORD@SERVER IP:10011/?server_port=9987&nickname=AIMBot");
	if (isset($_POST['cldbid'])) {
		if ($staff_name !='') {
			if ($staff_power <= 3) {
				$ts3_VirtualServer->clientBan($_POST['cldbid'],$timeseconds = $_POST['time'],$reason = $_POST['reason']);
			}
		}
	}
?>
<html>
	<head>
		<title>Ban Client</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	</head>
	<body>
		<div class="container">
			<div class="jumbotron">
				<h1>Ban a client</h1>
			</div>
			<form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
				<div class="form-group">
					<label for="cldbid">Client ID:</label>
					<input type="text" class="form-control" name="cldbid" placeholder="Client's ID" required></input>
				</div>
				<div class="form-group">
					<label for="time">Duration (in seconds):</label>
					<input type="text" class="form-control" name="time" placeholder="Duration of the Ban in Seconds" required></input>
				</div>
				<div class="form-group">
					<label for="reason">Reason:</label>
					<input type="text" class="form-control" name="reason" placeholder="Ban Reason?" required></input>
				</div><hr>
				<button type="submit" class="btn btn-danger">Ban</button>
			</form>
		</div>

	</body>
</html>