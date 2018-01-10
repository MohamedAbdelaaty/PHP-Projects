<?php
	session_start();
	if (isset($_POST['pass'])) {
		if (hash('sha512',$_POST['pass']) == 'b9e01abc6cd6307f596e77487d0111e3806be31d523a4e812aa4df98f5faef14894560dabe27f108dd9b0b3539e343a8a4a72fc22de5e2e40ec4809c6424c503') {
			$_SESSION['login'] = true;
			$status = '';
		} else {
			$status = '<div class="text-alert">Invalid Access Code!</div><br>';
		}
	} else {
		$status = '';
	}
	if (!isset($_SESSION['login'])) {
		echo '
			<html>
				<head>
					<title>Login</title>
    					<link rel="stylesheet" href="../cont/assets/css/auth.css">
				</head>
				<body>
					<div class="text-center">
						<div>
						<div class="black">Login to AIM-Gaming Staff Control Panel</div>
						<form method="POST" action="/auth/">
							<div class="spaced">
								Username:
							</div>
							<input type="text" name="username" placeholder="Your username">
							<div class="spaced">
								Password:
							</div>
							<input type="password" name="pass" placeholder="Provided Access Code"><hr>
							<button type=submit name=submit>Login</button>
						</form>';
							echo $status;
						echo '
							<div class="black">Request Access Code<br><strong>Status: </strong>Disabled</div>
						</div>
					</div>
				</body>
			</html>
		';
	} else {
		echo '<meta charset="UTF-8">
        		<meta http-equiv="refresh" content="1;url=http://staff.aimgaming.tk/">
			<script type="text/javascript">
				window.location.href = "http://staff.aimgaming.tk/"
			</script>';
	}
