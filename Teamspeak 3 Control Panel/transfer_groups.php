<?php
	session_start();
	require "includes/ts3admin/lib/ts3admin.class.php";
	require "includes/handles/header.php";
	require "includes/handles/loginchecker.php";
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");

	/*
		Transfer groups from one identity to the other, I should say clone not transfer
	*/

	if (!isset($_GET['dbid1']) && !isset($_GET['dbid2'])) {
		die ('');
	}
	$ts3 = TeamSpeak3::factory("serverquery://USERNAME:PASSWORD@SERVER_IP:QUERY_PORT/?server_port=9987&nickname=Legacy");
	$ts3Admin = new ts3admin('' /* Server IP */, 10011);
	$tsAdmin = new ts3admin('' /* Server IP */, 10011);
	$username="ServerReg";
	$password="xqZJwrnt";
	$ts3_Port = 9987;
	if($ts3Admin->getElement('success', $ts3Admin->connect())) {
		$current_groups_1 = array();
		$current_groups_2 = array();

		$ts3Admin->login($username,$password);
		$ts3Admin->selectServer($ts3_Port);
		$ts3Admin->setName('Legacy');

		$groups = $ts3Admin->serverGroupsByClientID($_GET['dbid1']);

		foreach ($groups['data'] as $group) {
			$current_groups_1[] = $group['sgid'];
		}

		$groups2 = $ts3Admin->serverGroupsByClientID($_GET['dbid2']);

		foreach ($groups2['data'] as $group) {
			$current_groups_2[] = $group['sgid'];
		}

		foreach ($current_groups_1 as $group) {
			if (!in_array($group,$current_groups_2)) {
				$ts3->serverGroupClientAdd($group,$_GET['dbid2']);
			}
		}
	}
?>
