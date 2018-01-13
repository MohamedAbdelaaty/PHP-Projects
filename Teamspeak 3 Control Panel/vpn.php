<?php
	session_start();
	require "includes/ts3admin/lib/ts3admin.class.php";
	require "includes/handles/header.php";
	require "includes/handles/loginchecker.php";
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://USERNAME:PASSWORD@SERVER_IP:10011/?server_port=9987&nickname=MrCatchYourVPN");
	$content = (double) file_get_contents("http://vpndetect.aimgaming.tk/?ip=".$_GET['ip']);

	if ($content >= 0.9) {
		$rules = array ("ip" => $_GET['ip']);
		$ts3_VirtualServer->banCreate($rules, 0, "Manual Proxy Check Requested Through Panel, Proxy Detected");
	} else {
		echo "VPN Detection Passed";
	}
