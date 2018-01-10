<?php
	require "assets/Includes/header.php";
?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIM Gaming Staff Control Panel</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/user.css">
</head>

<body>
<?php 
	if (isset($_SESSION['login']))
	if ($_SESSION['login'] == true && $staff_power < 10) {
	echo '
    <header>
        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navcol-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button><a class="navbar-brand navbar-link" href="#"><span class="glyphicon glyphicon-knight"></span><span class="text-title">AIM Gaming Staff</span></a></div>
                <div class="collapse navbar-collapse" id="navcol-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="active" role="presentation"><a href="#">Forums </a></li>
                        <li role="presentation"><a href="#">Server Control Panel</a></li>
                        <li role="presentation"><a href="#">Contact Webmaster</a></li>
                        <li class="active" role="presentation"><a href="/auth/logout">Logged in as: '.$staff_name.', Logout Here </a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <ul class="nav nav-pills categories">
        <li class="active"><a href="#">Home </a></li>
        <li><a href="announcements">Announcements </a></li>
        <li><a href="staff_list">Staff List</a></li>
        <li><a href="mailer">Mailer </a></li>
    </ul>
    <div class="container post">
        <div class="row">
            <div class="col-md-6 post-title">
                <h1>Code of Conduct</h1>
                <p class="author"><strong>Exile</strong> <span class="text-muted">Owner </span></p>
            </div>
            <div class="col-md-6 col-md-offset-0 post-body">
                <ul>
                    <li>1 IP per staff member</li>
                    <li>Respect all staff members</li>
                    <li>Arguments within staff department must remain private</li>
                    <li>Report any issues within the staff department to the higher ups</li>
                </ul>
                <figure><img class="img-thumbnail" src="assets/img/champ_riv.jpg">
                    <figcaption>Unleash the beast</figcaption>
                </figure>
            </div>
        </div>
    </div>
    <footer>
        <h5>AIM Gaming© 2016</h5></footer>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>';} ?>
</body>

</html>
