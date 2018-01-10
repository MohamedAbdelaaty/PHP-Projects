<html>
	<head>
		<title>AIM-Gaming Online Clients</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	</head>
	<body>
	<div class=container>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Client Name</th>
					<th>OS</th>
					<th>IP</th>
					<th>Modify Server Groups</th>
					<th>Kick</th>
					<th>Ban</th>
				</tr>
			</thead>
			<tbody>
				<tr>
			<?php
				require_once("libraries/TeamSpeak3/TeamSpeak3.php");
				require "Includes/header.php";
				$ts3_VirtualServer = TeamSpeak3::factory("serverquery://QUERY USERNAME:QUERY PASSWORD@SERVER IP:10011/?server_port=9987&nickname=AIMBot");
	
				// query clientlist from virtual server and filter by platform
				$arr_ClientList = $ts3_VirtualServer->clientList();
	
				// walk through list of clients
				foreach($arr_ClientList as $ts3_Client)
				{
					if ($ts3_Client != "AIMBot") {
						echo '<td>';
 						echo $ts3_Client;
						echo '</td>';
						echo '<td>';
						echo $ts3_Client["client_platform"];
						echo '</td>';
						echo '<td>';
						if ($ts3_Client != "" && $ts3_Client["connection_client_ip"] != "" && $staff_power == 0) { // whitelist yourself
							echo $ts3_Client["connection_client_ip"];
						} else {
							echo "<strong>Requires Super Admin</strong>";
						}
						echo '</td>';
						echo '<td>';
						echo '<a href="#" class="btn btn-xs btn-info">Modify Server Groups</a>';
						echo '</td>';
						echo '<td>';
						if ($ts3_Client != "" && $ts3_Client["connection_client_ip"] != "") { // whitelist yourself
							echo '<a href="kick_user.php?user='.$ts3_Client.'" class="btn btn-xs btn-warning">Kick Client</a>';
						} else {
							echo 'Insufficient Power Level';
						}
						echo '</td>';
						echo '<td>';
						if ($ts3_Client != "" && $ts3_Client["connection_client_ip"] != "") { // whitelist yourself
							echo '<a href="ban_client.php" class="btn btn-xs btn-danger disabled">Ban Client</a>';
						} else {
							echo 'Insufficient Power Level';
						}
						echo '</td>';
						echo '</tr>';
					}
				}
			?>
			</tbody>
		</table>
	</div>
	</body>
</html>