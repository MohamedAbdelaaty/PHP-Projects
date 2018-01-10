<?php
	session_start();
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://QUERY USERNAME:QUERY PASSWORD@SERVER IP:10011/?server_port=9987&nickname=MrCatchYourVPN");
	if (isset($_GET['dbid'])) {
		$_SESSION['dbid'] = $_GET['dbid'];
	} else {
		die ("dbid not found");
	}

	// Manual proxy detection, didnt work out too well xD.
	// The issue is that it WILL detect a proxy but, if the user is not using a proxy, the connection would time out.
/*	$check = "Proxy not detected";
	$ports = array(8080,80,81,1080,6588,8000,3128,553,554,4480);
	foreach($ports as $port) {
		if (@fsockopen($_SERVER['REMOTE_ADDR'], $port, $errno, $errstr, 30)) {
			$check = "You are using a proxy!";
		}
	}
	echo "Proxy Check<br>";
	echo $check;*/

	// Used a custom made VPN detector
	// Unfortunately cant share code due to team request
	$content = file_get_contents("http://vpndetect.practicallyuselessgamers.com/detect.php?ip=".$_SERVER['REMOTE_ADDR']);
	if ($content > 0.7) {
		$rules = array (
			'ip' => $_SERVER['REMOTE_ADDR']
		);
		$ts3_VirtualServer->banCreate($rules,0,'VPN Detected');
	} elseif ($content > 0.5 && $content <= 0.7) {
		$ts3_VirtualServer->serverGroupClientAdd(285, $_SESSION['dbid']);
	} else {
		$_SESSION['ProxyPass'] = true;
		
		// Preferably use header("Location: regForm.php");exit;
		echo 'Proxy detection passed!
		Redirecting in 5 seconds...
		<meta http-equiv="refresh" content="5;url=http://register.crustygrandpa.com/regForm.php">';
	}

	
	

