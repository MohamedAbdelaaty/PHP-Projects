<?php
	session_start();
	require "includes/ts3admin/lib/ts3admin.class.php";
	require "includes/handles/header.php";
	require "includes/handles/loginchecker.php";
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://USERNAME:PASSWORD@SERVER_IP:QUERY_PORT/?server_port=9987&nickname=Legacy");

	/*
		This page is created for the sole purpose of removing bans
		Everything is very simple i believe so i wont comment much
	*/
	$counter = 1;
	if (!isset($_SESSION['username'])) {
		die ('<meta http-equiv="refresh" content="0; url=auth/" />Not logged in');
	} elseif ($_SESSION['access'] < 10) {
		die("Insufficient Permissions");
	}

	if (isset($_GET['banid'])) {
		$ts3_VirtualServer->banDelete($_GET['banid']);
		die('<meta http-equiv="refresh" content="0; url=https://panel.crustygrandpa.com/balist.php" />');
	}
?>
