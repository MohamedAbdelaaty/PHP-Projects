<?php
	session_start();
	require "includes/ts3admin/lib/ts3admin.class.php";
	require "includes/handles/header.php";
	require "includes/handles/loginchecker.php";
	require "includes/handles/functions.php";
	require "includes/db/db.php";
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://USERNAME:PASSWORD@SERVER_IP:QUERY_PORT/?server_port=9987&nickname=Legacy");

	// Checks access level obtained from the database when they login
	if ($_SESSION['access'] < 10) {
		die ("Insufficient Permissions, Please log on an adminstrator account");
	}

	$ts3Admin = new ts3admin('' /*Server IP*/, 10011 /*Server Query Port (Default is 10011)*/);
	$username="ServerReg";
	$password="xqZJwrnt";
	$ts3_Port = 9987;

	$ip = $_SERVER['REMOTE_ADDR'];
$tsAdmin = new ts3admin('' /*Server IP*/, 10011 /*Query Port*/);
	if($tsAdmin->getElement('success', $tsAdmin->connect())) {
		$tsAdmin->login($username,$password);

		$tsAdmin->selectServer($ts3_Port);

		$clients = $tsAdmin->clientList("-ip -groups");

		$tsAdmin->setName('EternityBot'.rand(1000,9999));
		$counter = 0;
		foreach ($clients['data'] as $client) {
			if ($client['connection_client_ip'] == $ip) {
				$counter++;
			}
		}

		// checks if client is connected to teamspeak and checks that it is only 1 client
		if ($counter > 0 && $counter < 2) {
			// goes through all clients conencted to the server to find a match for the give IP
			foreach ($clients['data'] as $client) {

				if ($client['connection_client_ip'] == $ip) {
					// checks if they have group with SGID = 307
					if ((strpos($client['client_servergroups'], ',307') === false) ) {
						// otherwise the site dies with a message "Admin panel access is not allowed"
						die("Admin panel access is not allowed");

					}

				}

			}

		} else {
			// if the client is not connected or has more than one identity connected
			die("Please connect to teamspeak first!");

		}
	} else {
		// if connection to server query fails
		echo "Connection to server failed";

	}


	// verifies that the user has sufficient access level to add a new panel user and that data has been sent/form has been submitted for it to run the following code
	if ($_SESSION['access'] >= 11 && isset($_POST['username']) && isset($_POST['uid']) && isset($_POST['dn']) && isset($_POST['dep']) && isset($_POST['pow'])) {
		$user = mysqli_real_escape_string($conn, escape_input($_POST['username']));

		// check if username exists already
		$sql = "SELECT * FROM auth_user WHERE username='$user'";

		//runs query into db
		$run_sql = mysqli_query($conn,$sql);

		// checks the number of lines returned from the query
		$check1 = mysqli_num_rows($run_sql);

		// if it is not = to 0, then an account exists. Note the possible return values should be 0 or 1
		if ($check1 != 0) {
			// User friendly output
			$returned = '<div class="alert alert-danger alert-dismissable">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<strong>Failed!</strong> Selected username is already in use, try another.
				</div>';

		} else {
			// otherwise, the username is clear to use

			// display name check
			$dn = escape_input($_POST['dn']);
			$sql_2 = "SELECT * FROM auth_user WHERE display_name='$dn'";

			// run query into db
			$run_sql_2 = mysqli_query($conn,$sql_2);

			// checks amount of rows returned
			$check2 = mysqli_num_rows($run_sql_2);

			// checks if the display name is currently in USERNAME
			if ($check2 != 0) {
				// user friendly return
				$returned = '<div class="alert alert-danger alert-dismissable">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<strong>Failed!</strong> Selected display name is already in use, try another.
				</div>';

			} else {

				// otherwise place data into db
				$name = mysqli_real_escape_string($conn, escape_input($_POST['dn']));
				$depart = mysqli_real_escape_string($conn, escape_input($_POST['dep']));
				$power = mysqli_real_escape_string($conn, escape_input($_POST['pow']));
				$ban = mysqli_real_escape_string($conn, escape_input($_POST['ban']));
				$uid = mysqli_real_escape_string($conn, escape_input($_POST['uid']));

				// power level of 9 is pretty high overall, you can customize what each level does yourself if you wish. 10 can see the admin panel (still needs the teamspeak3 group to work), and 11 can add panel users.
				// for levels 10 and above you will have to make your own method, i personally manually entered them into the db for security reasons
				if ($power > 9) {
					$power = 4;
					$level_ex = '<strong style="color:red">Your selected power level '.$power.' is above what you can assign. The power level assigned defauled to 4.</strong>';
				} else {
					$level_ex = 'Assigned power level of '.$power.' has been auto approved';
				}

				// random password (all numbers, very insecure; thats y you force the requirement to change their password when they login. (i can create the function for you upon request))
				$password = rand(50000000000, 80000000000);
				$password_enc = hash('sha512',$password); // sha512 encryption for the password (This is not salted. Can change pasword storage algorithm to include salted passwords upon request xD)

				// insert query
				$sql_3="INSERT INTO auth_user (username,display_name,ts3uid,department,access_level,ban_time, req_pass_change) VALUES ('$user','$dn','$uid','$depart','$power','$ban',1)";

				// insert query
				$run_sql_3 = mysqli_query($conn,$sql_3);

				// i split the username table from the username table. You can store them both in the same table if you want
				$sql_3 = "INSERT INTO auth_pass (password) VALUES ('$password_enc')";
				$run_sql_3 = mysqli_query($conn,$sql_3);

				// User friendly return
				$returned = '<div class="alert alert-success alert-dismissable">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					Successfully created a new access account with the following details:<br>
					<strong>Username: </strong>'.$user.'<br>
					<strong>Password: </strong>'.$password.'</br>
				</div>';

			}

		}

	} else {
		// if no data has been sent, the return is blank.
		$returned = '';
	}

	// account deletion from panel users
	if (isset($_GET['id'])) {
		// get user with that ID. NOTE: the user ID field is unique, meaning no two users will ahve the same ID
		$sql = "SELECT * FROM auth_user WHERE id='$_GET[id]'";
		$run_sql = mysqli_query($conn,$sql);

		// scan through the table till user is found
		while ($rows = mysqli_fetch_assoc($run_sql)) {
			// check user access level vs the current client's access level
			if ($rows['access_level'] < $_SESSION['access']) {
				// if it is higher, it means they have the perm to remove this user from panel access. Therefore, delete the user
				// TODO: Add a confirmation message (maybe using JS or add a different page to process account deletions. I did it like this for simplicity since no real harm can be done (accounts are easy to make).)
				$new_sql = "DELETE FROM auth_user WHERE id='$_GET[id]'";
				$run = mysqli_query($conn,$new_sql);

				// I ensured that the ID in auth_user and auth_pass match manually for simplicity
				$new_sql = "DELETE FROM auth_pass WHERE id='$_GET[id]'";
				$run = mysqli_query($conn,$new_sql);
			} else {
				// if the current user doesnt have high enough access level to delete the user, kill the page. (Very non-user friendly, but like, they shouldnt try to delete someone higher than them anyway)
				die ("Insufficient permissions");
			}
		}
	}
?>

<html>

<head>
	<!-- Basic HTML stuff. Included Bootstrap and some custom css -->
	<title>Control Panel Home</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<!-- Custon CSS - Wrote it in file since i routed through CF DNS and i wanted instant updates. CF caches CSS files and JS files and other includable files to reduce load time and traffic load -->
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

<!-- The body of our admin control panel -->
<body class="bg">
  	<header class="navbar navbar-inverse navbar-static-top">
    		<div class="container-fluid">
      			<a href="index.php" class="navbar-brand">Crusty Control Panel</a>
      			<ul class="nav navbar-nav">
        			<li><a href="/">Currently Online</a></li>
							<li><a href="reg.php">Members</a></li>
							<li><a href="members.php">Teamspeak Members</a></li>
							<li><a href="announcements.php">Announcements</a></li>
      			</ul>
						<ul class="nav navbar-nav navbar-right">
							<?php
								if ($_SESSION['access'] >= 9) {
									echo '<li><a href="ban_appeals.php">Ban Appeals</a></li>';
								}
								if ($_SESSION['access'] >= 10) {
									echo '<li><a href="balist.php">Ban List</a></li><li class="active"><a href="#">Admin</a></li>';
								}
							?>
							<li class="dropdown">
              	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="caret"></span></a>
                <ul class="dropdown-menu">
									<li><a href="#">Messages</a></li> <!-- Never got to finish imbox. Sorry. Will finish it upon request -->
									<li><a href="settings.php">Account Settings</a></li>
                	<li><a href="auth/logout.php">Logout</a></li>
                </ul>
      				</li>
						</ul>
    		</div>
  	</header>
		<div class="container">
			<?php
				// returned data from above (returns nothing if nothing is sent/processed)
				echo $returned;
			?>

			<!-- Users with permission to access the panel -->
			<div class="head">Access List to the Control Panel</div>
			<div class="pan-body">
				<table class="table">
					<thead>
						<th>Name</th>
						<th>Staff Position</th>
						<th>Modify</td>
						<th>Delete Account</th>
					</thead>
					<tbody>
						<?php
							// obtaining data from the database
							$sql = "SELECT * FROM auth_user";
							$run_sql = mysqli_query($conn,$sql);
							while ($rows = mysqli_fetch_assoc($run_sql)) {
								echo '<tr>';
								echo '<td>'.$rows['display_name'].'</td>';
								echo '<td>'.$rows['department'].'</td>';

								// checks current client's access level vs the panel user's access level. If it is higher, displays the modify and delete buttons
								// otherwise displays "no can do"
								if ($_SESSION['access'] > $rows['access_level']) {
									echo '<td><a href="?id='.$rows['id'].'" class="btn btn-xs btn-warning disabled">Modify</a></td>'; // Never got to finish the modification page since i discontinued the project. Doable upon request.
									echo '<td><a href="?id='.$rows['id'].'" class="btn btn-xs btn-danger">Delete</a></td>';
								} else {
									echo '<td>No can do</td>';
									echo '<td>No can do</td>';
								}
								echo '</tr>';
							}
						?>
					</tbody>
				</table>
			</div>
		<?php
		// check current user's access level. If above 10, display the account creation panel
		if ($_SESSION['access'] > 10) {
		echo '
					<form class="panel-group form-horizontal" role="form" method="POST">
            		<div class="head">Create a new access account</div>
            		<div class="pan-body">
              			<div class="form-group">
                			<label for="username" class="control-label col-sm-2">Username</label>
                  			<div class="col-sm-9">
                    				<input type="text" name="username" id="username" class="form-control" placeholder="username">
                  			</div>
              			</div>
              			<div class="form-group">
                			<label for="word" class="control-label col-sm-2">Display Name</label>
                  			<div class="col-sm-9">
                    				<input type="word" name="dn" id="display" class="form-control" placeholder="Display Name">
                  			</div>
              			</div>
              			<div class="form-group">
                			<label for="word" class="control-label col-sm-2">TS3 UID</label>
                  			<div class="col-sm-9">
                    				<input type="word" name="uid" class="form-control">
                  			</div>
              			</div>
				<div class="form-group">
                			<label for="word" class="control-label col-sm-2">Ban Time</label>
                  			<div class="col-sm-9">
                    				<input type="number" name="ban" class="form-control" placeholder="Ban time in seconds">
                  			</div>
              			</div>
				<div class="form-group">
                			<label for="word" class="control-label col-sm-2">Department</label>
                  			<div class="col-sm-9">
                    				<select name="dep" class="form-control" required>
							<option value="Moderating">Moderating</option>
    							<option value="Organizing">Organizing</option>
    							<option value="Events">Community Events</option>
    							<option value="Recruiting">Recruiting</option>
  						</select>
                  			</div>
              			</div>
				<div class="form-group">
                			<label for="word" class="control-label col-sm-2">Power Level</label>
                  			<div class="col-sm-9">
                    				<select name="pow" class="form-control" required>
							<option value="1">1</option>
    							<option value="2">2</option>
    							<option value="3">3</option>
    							<option value="4">4</option>
							<option value="5">5</option>
    							<option value="6">6</option>
    							<option value="7">7</option>
    							<option value="8">8</option>
    							<option value="9">9</option>
  						</select>
                  			</div>
              			</div>

				<hr>
              			<div class="form-group">
                  			<div class="col-sm-12">
                    				<input type="submit" value="Register" class="btn btn-success btn-block">
                  			</div>
              			</div>
          		</div>
        	</form>';
		}
		?>
		<?php
		// login logs
		if ($_SESSION['access'] > 10) {
			$log = file_get_contents('logs/log.txt');
			echo '<div class="head">logs</div>
				<div class="pan-body" style="text-align:left">';
			echo $log;
			echo '</div>';
		}
		?>

	</div>
</body>

</html>
