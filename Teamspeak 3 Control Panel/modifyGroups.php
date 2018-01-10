<?php
	session_start();
	require "includes/ts3admin/lib/ts3admin.class.php";
	require "includes/handles/header.php";
	require "includes/handles/loginchecker.php";
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	$ts3 = TeamSpeak3::factory("serverquery://USERNAME:PASSWORD@SERVER_IP:QUERY_PORT/?server_port=9987&nickname=Legacy");
	if (isset($_GET['dbid']) && isset($_GET['group'])) {
		$groups = array();
		$groupsOfClient = $ts3->clientGetServerGroupsByDbid($_GET['dbid']);
		foreach ($groupsOfClient as $group) {
			$groups[] = $group['sgid'];
		}
		if (in_array($_GET['group'], $groups)) {
			$ts3->serverGroupClientDel($_GET['group'],$_GET['dbid']);
		} else {
			$ts3->serverGroupClientAdd($_GET['group'],$_GET['dbid']);
		}
	}
