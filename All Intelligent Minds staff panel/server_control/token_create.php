<?php
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	require 'Includes/header.php';
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://QUERY USERNAME:QUERY PASSWORd@SERVER IP:10011/?server_port=9987&nickname=AIMBot");

	// recover your account permissions
	switch ($staff_power) {
		case 0:
			$arr_ServerGroup = $ts3_VirtualServer->serverGroupGetByName("Server Admin");
			$ts3_PrivilegeKey = $arr_ServerGroup->privilegeKeyCreate();
			echo $ts3_PrivilegeKey;
			break;
		case 1:
			$arr_ServerGroup = $ts3_VirtualServer->serverGroupGetByName("Head Moderator");
			$ts3_PrivilegeKey = $arr_ServerGroup->privilegeKeyCreate();
			echo $ts3_PrivilegeKey;
			break;
		case 2:
			break;
		default:
			echo "Unable to process your request";
			break;
	}
	
?>