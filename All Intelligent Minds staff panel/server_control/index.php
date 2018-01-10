<html>
	<head>
		<title>AIM Gaming Control Panel</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	</head>
	<body>
		<div class=container>
			<div class=jumbotron>
				<h1>Teamspeak3 Control Panel</h1>
				<a href="token_create.php" class="btn btn-info">Generate Permission Key</a>

			</div>
			<div class=rows>
				<div class=col-md-2>
					<a href="Create Channels.php" class="btn btn-primary" style="width:100%">Create Channel</a>
				</div>
				<div class=col-md-2>
					<a href="#" class="btn btn-primary disabled" style="width:100%">Assign Server Group</a>
				</div>
				<div class=col-md-2>
					<a href="List_Clients.php" class="btn btn-primary" style="width:100%">Display Users</a>
				</div>
				<div class=col-md-2>
					<a href="kick_user.php" class="btn btn-warning" style="width:100%">Kick User</a>
				</div>
				<div class=col-md-2>
					<a href="delete_channel.php" class="btn btn-danger" style="width:100%">Delete Channel</a>
				</div>
				<div class=col-md-2>
					<a href="ban_client.php" class="btn btn-danger" style="width:100%">Ban User</a>
				</div>
		</div>
	</body>
</html>