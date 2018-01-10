<?php
	include 'Includes/connection.php';
	$ip = $_SERVER['REMOTE_ADDR'];
	$sql = "SELECT * FROM staff_list WHERE IP='$ip'";
	$run_sql = mysqli_query($conn,$sql);
	while ($rows = mysqli_fetch_assoc($run_sql)) {
		$staff_name = $rows['Name'];
		$staff_role = $rows['Role'];
		$staff_email = $rows['staff_email'];
		$staff_power = $rows['power_level'];
	}
?>