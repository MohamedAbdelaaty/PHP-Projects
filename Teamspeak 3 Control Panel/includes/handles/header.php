<?php

	// checks expiry of session
	if (isset($_SESSION['created'])) {
		if (time() - $_SESSION['created'] > 2400) {
			session_regenerate_id(true);
			session_unset();
			session_destroy();
		} else {
			$_SESSION['created'] = time();
		}
	}

	$ts3Admin = new ts3admin('', 10011); // Initializes connection to teamspeak 3 query
	$username=""; /*Server Query Login Username*/
	$password=""; /*Server Query Login Password*/
	$ts3_Port = 9987; /*Teamspeak 3 port*/

	$ip = $_SERVER['REMOTE_ADDR'];
	$tsAdmin = new ts3admin(''/*Server IP*/, 10011);
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
		if ($counter > 0 && $counter < 2) { // Verifies that they only have 1 identity connected to avoid conflict
			foreach ($clients['data'] as $client) {
				if ($client['connection_client_ip'] == $ip) {
					if ((strpos($client['client_servergroups'], ',306') === false) ) { // checks if they have the group number 306 to verify that they are allowed to access the web panel
						die("Panel Access is not allowed");
					}
				}
			}
		} else {
			die("Either no identities were dectected or you ahve more than 1 identity connected. Unable to verify permissions"); // for more detailed report, split the if statements for counter > 0 and < 2.
		}
	} else {
		die("Connection to server failed");
	}
