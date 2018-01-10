<?php
	if (!isset($_SESSION['username'])) { // verifies session is active
		die ('<meta http-equiv="refresh" content="0; url=auth/" />Not logged in');
	}
