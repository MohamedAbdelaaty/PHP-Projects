<?php
	session_start();
	session_unset();
	session_destroy();
	echo '<meta charset="UTF-8">
        	<meta http-equiv="refresh" content="1;url=http://staff.aimgaming.tk/auth/">
		<script type="text/javascript">
			window.location.href = "http://staff.aimgaming.tk/auth/"
		</script>';