<?php
	include "cont/assets/Includes/header.php";
?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIM Gaming Staff Control Panel</title>
    <link rel="stylesheet" href="cont/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="cont/assets/css/user.css">
</head>

<body>
    <header class="intro-page">
        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navcol-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                <div class="collapse navbar-collapse" id="navcol-1">
                    <ul class="nav navbar-nav">
                        <li role="presentation"><a href="/cont">Forums </a></li>
                        <li role="presentation"><a href="#">Server Control Panel</a></li>
                        <li role="presentation"><a href="#">Contact Webmaster</a></li>
                        <li class="active" role="presentation"><a href="/auth/logout.php">Logged in as: <?php echo $staff_name ?>, Logout Here </a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <footer>
        <h5>AIM Gaming© 2016</h5></footer>
    <script src="cont/assets/js/jquery.min.js"></script>
    <script src="cont/assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
