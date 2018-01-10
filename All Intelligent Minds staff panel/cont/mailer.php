<?php
	require "assets/Includes/header.php";
	require "assets/Includes/func/functions.php";
	if ($staff_power < 8) {
		if (isset($_POST['to'])) {
			$recipient = escape_input($_POST['to']);
			$subject = escape_input($_POST['subject']);
			$content = escape_input($_POST['content']);
			$sql = "INSERT INTO email_log (Client, Staff, Subject, Content) VALUES ('$recipient','$staff_name','$subject','$content')";
			$run_sql = mysqli_query($conn,$sql);
			$header = "From: $staff_name <$staff_email> \r\n";
			$header .= "Reply-To: $staff_name <$staff_email>\r\n"; 
  			$header .= "Return-Path: $staff_name <$staff_email>\r\n";
 			$header .= "Organization: All Intelligent Minds Gaming\r\n";
  			$header .= "MIME-Version: 1.0\r\n";
			$header .= "Mailed By: AIM Gaming Mailing Service";
  			$header .= "Content-type: text/plain; charset=iso-8859-1\r\n";
  			$header .= "X-Priority: 1\r\n";
  			$header .= "X-Mailer: PHP". phpversion() ."\r\n";
			$retval = mail ($recipient,$subject,$content,$header);
         		if($retval == true ) {
         			echo '<script>alert("Success")</script>';
	        	}else {
        	    		echo '<script>alert("Failed")</script>';
        	 	}
		}
	}
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
	if (isset($_SESSION['login']) && $staff_power < 10)
	if ($_SESSION['login'] == true)
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
        <li><a href="index">Home </a></li>
        <li><a href="announcements">Announcements </a></li>
        <li><a href="staff_list">Staff List</a></li>
        <li class="active"><a href="#">Mailer </a></li>
    </ul>
    <div class="container post">
        <div class="row">
            <div class="col-lg-6 post-title">
                <h1>Send Official AIM-Gaming E-Mail</h1>
                <p class="author"><strong><?php echo $staff_name ?></strong> <span class="text-muted">'.$staff_email.'</span></p>
            </div>
            <div class="col-lg-6 col-md-offset-0 post-body">
              <form class="send-mail" method="POST">
                Client\'s Email*:<br>
                <input type="email" class="custom-input" placeholder="Client Email" name="to" required></input>
                <hr>
                Subject*:
                <input type="text" class="custom-input" placeholder="Subject of Email" name="subject" required></input>
                <hr>
                Content*:
                <textarea type="text" class="custom-input" name="content" placeholder="Content of Email" required></textarea>
                <hr class="primary">
                <button class="button button-blue fill" name="submit" value="Send" type"submit">Send Email</button>
              </form>
            </div>
        </div>
    </div>
    <footer>
        <h5>AIM Gaming© 2016</h5></footer>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>';
	?>
</body>

</html>
