<?php
	require "assets/Includes/header.php";
	if (isset($_GET['edit_id'])) {
		$sql = "SELECT * FROM announcements WHERE id='$_GET[edit_id]'";
		$run_sql = mysqli_query($conn,$sql);
	}
	if (isset($_POST['title'])) {
		$updateSQL = "UPDATE announcements SET Title='$_POST[title]' WHERE id='$_GET[edit_id]'";
		$run_update = mysqli_query($conn,$updateSQL);
		$updateSQL = "UPDATE announcements SET content='$_POST[content]' WHERE id='$_GET[edit_id]'";
		$run_update = mysqli_query($conn,$updateSQL);
		echo '	
		<meta charset="UTF-8">
        	<meta http-equiv="refresh" content="1;url=http://staff.aimgaming.tk/cont/announcements">
        	<script type="text/javascript">
            		window.location.href = "http://staff.aimgaming.tk/cont/announcements"
        	</script>';
	}
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Announcement</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/user.css">
</head>

<style>
	input {
		width:100%;
		height: 60px;
		text-align:center;
		border:none;
		font-size:30px;
		color:blue;
	}
	textarea {
		width:100%;
		height: 100px;
		text-align:center;
		color:blue;
		font-size:15px;
	}
	textarea:focus {
		color:green;
	}
	.btn {
		height:60px;
	}
</style>

<body>

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
                        <li class="active" role="presentation"><a href="/auth/logout">Logged in as: <?php echo $staff_name ?>, Logout Here </a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <ul class="nav nav-pills categories">
        <li><a href="index">Home </a></li>
        <li class="active"><a href="announcements">Announcements </a></li>
        <li><a href="staff_list">Staff List</a></li>
        <li><a href="mailer">Mailer </a></li>
    </ul>
	<div class="container">
		<?php
			while ($rows = mysqli_fetch_assoc($run_sql)) {
				$content = $rows['content'];
				$title = $rows['Title'];
			}
		?>
		<form action="edit?edit_id=<?php echo $_GET['edit_id'] ?>" method="POST">
			<label for="title">Title</label>
			<input name="title" value="<?php echo $title ?>"></input><br>
			<label for="content">Content </label>
			<textarea name="content"><?php echo $content ?></textarea><hr>
			<button type="submit" class="btn btn-block btn-primary">Submit</button>
		</form>
	</div>
    <footer>
        <h5>AIM Gaming© 2016</h5></footer>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>


</html>