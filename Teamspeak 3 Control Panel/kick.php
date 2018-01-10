<?php
	/*

		Kick Page

	*/
	session_start();
	require "includes/ts3admin/lib/ts3admin.class.php";
	require "includes/handles/header.php";
	require "includes/handles/loginchecker.php";
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://USERNAME:PASSWORD@SERVER_IP:QUERY_PORT/?server_port=9987&nickname=Legacy");
	if (isset($_SESSION['username'])) {
		if ($_SESSION['access'] >= 7) {
			if (isset($_GET['Dbid'])) {
				$ts3_VirtualServer->clientGetByDbid($_GET['Dbid'])->kick(TeamSpeak3::KICK_SERVER, "Requested Through Control Panel");
				echo '<meta http-equiv="refresh" content="0; url=/" />';
			}
		}
	} else {
		die("You cannot access this page");
	}
