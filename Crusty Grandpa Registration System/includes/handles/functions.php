<?php	
	function escape_input($input) {
		$length = strlen($input);
		$allowed_chars = array('/','<','>','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','1','2','3','4','5','6','7','8','9','0', ' ', '@','.',':', '', 'A', 'B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		$input_split = str_split($input);
		$accepted_input = '';
		for ($i = 0; $i < $length; $i++) {
			$valid = false;
			for ($ii = 0; $ii < 70; $ii++) {
				if ($input_split[$i] == $allowed_chars[$ii]) {
					$valid = true;
					break;
				}
			}
			if ($valid) {
				$accepted_input = $accepted_input.$input_split[$i];
			}
		}
		return $accepted_input;
	}

	function ver_input($input) {
		$valid = true;
		$length =strlen($input);
		$allowed_chars = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','1','2','3','4','5','6','7','8','9','0', ' ', '@','.',':', '');
		$input_split = str_split($input);
		for ($i = 0; $i < $length; $i++) {
			$valid = false;
			for ($ii = 0; $ii < 40; $ii++) {
				if ($input_split[$i] == $allowed_chars[$ii]) {
					$valid = true;
					break;
				}
			}
			if (!$valid) {
				echo '<script>alert("Char '.$input_split[$i].' is not allowed in '.$input.'")</script>';
				break;
			}
		}
		if ($valid) {
			return true;
		} else if (!$valid) {
			return false;
		}
	}
	
	function register($email, $passowrd, $ip, $references, $dbid) {
		$em = escape_input($email);
		$pass = hash('sha512',$password);
		$addr = $_SERVER['REMOTE_ADDR'];
		$reference = escape_input($references);
		$cldbid = escape_input($dbid);
		$sql = "INSERT INTO members (email, ip, dbid, password, references) VALUES ('$em','$addr','$cldbid','$pass','$reference')";
		$run_sql = mysqli_query($conn,$sql);
	}