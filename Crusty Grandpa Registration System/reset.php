<?php
	// To reset registration process
	session_start();
	session_unset();
	session_regenerate_id(true);
	session_destroy();