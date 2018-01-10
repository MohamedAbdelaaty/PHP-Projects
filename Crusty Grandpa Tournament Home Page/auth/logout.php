<?php
	session_start();
	session_unset();
	session_regenerate_id(TRUE);
	session_destroy();
	echo '<meta http-equiv="refresh" content="0; url=/auth/" />';