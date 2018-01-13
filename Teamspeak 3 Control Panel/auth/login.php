<?php
	session_start();
	require "../includes/ts3admin/lib/ts3admin.class.php";
	require "../includes/handles/header.php";
	require "../includes/db/db.php";
	require "../includes/handles/functions.php";
	if (isset($_POST['username'])) {
		$username = mysqli_real_escape_string($conn, escape_input());
		$sql = "SELECT * FROM auth_user WHERE username='$username'";
		$run_sql = mysqli_query($conn,$sql);
		while ($rows = mysqli_fetch_assoc($run_sql)) {
			$id = $rows['id'];
			$access = $rows['access_level'];
			$display = $rows['display_name'];
		}
		$sql = "SELECT * FROM auth_pass WHERE id='$id'";
		$run_sql = mysqli_query($conn,$sql);
		while ($rows = mysqli_fetch_assoc($run_sql)) {
			$password = $rows['password'];
		}
		if (hash('sha512',$_POST['password']) == $password) {
			$_SESSION['username'] = $_POST['username'];
			$_SESSION['display'] = $display;
			$_SESSION['access'] = $access;
			$_SESSION['created'] = time();
			
			// log logins
			$file = fopen('../logs/log.txt','a');
			$month = date('m');
			$day = date('d');
			$year = date('o');
			$when = $month.'/'.$day.'/'.$year;
			$time = date("h:i:sa");
			$text = "Client '$display' has authenticated successfully with login: '$_POST[username]'.<br>\n ---> Athentication was from: '$_SERVER[REMOTE_ADDR]' on '$when' at '$time' <br><hr>\n";
			fwrite($file, $text);
			fclose($file);
			echo '<meta http-equiv="refresh" content="0; url=/" />';
		} else {
			// log logins
			$file = fopen('../logs/log.txt','a');
			$month = date('m');
			$day = date('d');
			$year = date('o');
			$when = $month.'/'.$day.'/'.$year;
			$time = date("h:i:sa");
			$text = "Client '$display' has failed authentication using username: '$_POST[username]'.<br>\n ---> Athentication attempt was from: '$_SERVER[REMOTE_ADDR]' on '$when' at '$time' <br><hr>\n";
			fwrite($file, $text);
			fclose($file);
			echo "Invalid";
		}
	} else {
		die ("Data sent is blank. Please try again: https://urlhere.com/auth/");
	}
?>

<html>

<head>
	<title>login</title>
</head>

</html>
