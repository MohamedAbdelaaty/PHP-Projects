<?php
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://QUERY USERNAME:QUERY PASSWORD@SERVER IP:10011/?server_port=9987&nickname=AIMBot");
	if(isset($_POST['chid'])){
		$ts3_VirtualServer->channelDelete($_POST['chid'],$force=true);
	}
?>
<html>
	<head>
		<title>Delete Channel</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	</head>
	<body>
		<div class="container">
			<div class="jumbotron">
				<h1>Delete a Channel</h1>
			</div>
			<form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
				<div class="form-group">
					<label for="chid">Channel ID:</label>
					<input type="text" class="form-control" name="chid" placeholder="Channel ID" required></input>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="confirmation" value="confirm" required> Confirm
					</label>
				</div><hr>
				<button type="submit" class="btn btn-danger">Delete</button>

			</form>
		</div>

	</body>
</html>