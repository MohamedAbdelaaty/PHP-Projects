<?php
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://ServerReg:xqZJwrnt@66.70.198.65:10011/?server_port=9987&nickname=ThexdBot");
	$ip = $_SERVER['REMOTE_ADDR'];
	$rules = array (
		'ip' => $ip
	);
	$ts3_VirtualServer->banCreate($rules,5,"You rule 11'd yourself");

?>