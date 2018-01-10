<?php
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://QUERY USERNAME:QUERY PASSWORD@SERVER IP:10011/?server_port=9987&nickname=AIMBot");
	if(isset($_GET['user'])){
		if ($_GET['user'] != '' /* Put your username here so you dont get kicked maybe? */) {
			$ts3_VirtualServer->clientGetByName($_GET['user'])->kick(TeamSpeak3::KICK_SERVER, $_GET['reason']);
		}
	}
?>
<html>
	<head>
		<title>Kick Client</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	</head>
	<body>
		<div class="container">
			<div class="jumbotron">
				<h1>Kick a user cause y not. amirite?</h1>
			</div>
			<form action="<?php $_SERVER['PHP_SELF'] ?>" method="GET">
				<div class="form-group">
					<label for="user">Client's Name:</label>
					<input type="text" class="form-control" name="user" placeholder="Who you kickin" required></input>
				</div>
				<div class="form-group">
					<label for="reason">Reason:</label>
					<input type="text" class="form-control" name="reason" placeholder="y tho?"></input>
				</div>
				<button type="submit" class="btn btn-warning">Kick</button>

			</form>
		</div>

	</body>
</html>