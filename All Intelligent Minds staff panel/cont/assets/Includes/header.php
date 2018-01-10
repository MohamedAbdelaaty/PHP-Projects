<?php
	session_start();
	date_default_timezone_set(America/New_York);
	$server = 'localhost';
	$user = 'emaillog';
	$password = '4ytuha2un';
	$db = 'zadmin_emaillog';
	
	$conn = mysqli_connect($server,$user,$password,$db);
	
	if(!$conn){
		die("Connection Failed!: ".mysqli_connect_error());
	}
	$ip = $_SERVER['REMOTE_ADDR'];
	$sql = "SELECT * FROM staff_list WHERE IP='$ip'";
	$run_sql = mysqli_query($conn,$sql);
	while ($rows = mysqli_fetch_assoc($run_sql)) {
		$staff_name = $rows['Name'];
		$role_sql = "SELECT * FROM staff_roles WHERE role_code = '$rows[Role]'";
		$run_role = mysqli_query($conn,$role_sql);
		$role = mysqli_fetch_assoc($run_role);
		$staff_role = $role['role_name'];
		$staff_email = $rows['staff_email'];
		$staff_power = $rows['power_level'];
	}
	if ($staff_name == '') {
		echo '        
	<meta charset="UTF-8">
        	<meta http-equiv="refresh" content="1;url=http://staff.aimgaming.tk/error/unauthorized">
        	<script type="text/javascript">
            		window.location.href = "http://staff.aimgaming.tk/error/unauthorized"
        	</script>';
	}
	if (!isset($_SESSION['login'])) {
		echo '        
			<meta charset="UTF-8">
        		<meta http-equiv="refresh" content="1;url=http://staff.aimgaming.tk/auth/">
			<script type="text/javascript">
				window.location.href = "http://staff.aimgaming.tk/auth/"
			</script>';
	}
	function assign_power_level ($role) {
		switch ($role) {
			case 773928:
				$power_level = 1;
				break;
			case 334782:
				$power_level = 1;
				break;
			case 634419:
				$power_level = 2;
				break;
			case 589112:
				$power_level = 3;
				break;
			case 889153:
				$power_level = 4;
				break;
			case 993281:
				$power_level = 5;
				break;
			case 228730:
				$power_level = 9;
				break;
			case 000113:
				$power_level = 5;
				break;
			case 339182:
				$power_level = 9;
				break;
			case 114781:
				$power_level = 4;
				break;
			case 771827:
				$power_level = 5;
				break;
			case 889103:
				$power_level = 9;
				break;
			case 009740:
				$power_level = 4;
				break;
		}
		return $power_level;
	}


?>