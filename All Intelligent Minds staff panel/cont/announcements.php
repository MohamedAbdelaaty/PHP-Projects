<?php
	require "assets/Includes/header.php";
	require "assets/Includes/func/functions.php";
	if (isset($_POST['title'])) {
		if ($staff_power < 4) {
			$title = escape_input($_POST['title']);
			$content = escape_input($_POST['content']);
			$date = date('l jS \of F Y h:i:s A').' EST';
			$sql = "INSERT INTO announcements (Title, author, role, delete_power, content, date) values ('$title', '$staff_name', '$staff_role', '$staff_power', '$content', '$date')";
			$run_sql = mysqli_query($conn,$sql);
		} else {
			echo '
				<script>alert("Insufficient Perms")</script>
			';
		}
	} else if (isset($_GET['id'])) {
		if ($staff_power <= 3) {
			$id = escape_input($_GET['id']);
			$check_perms = "SELECT delete_power FROM announcements WHERE id='$id'";
			$run_check = mysqli_query($conn,$check_perms);
			$needed_delete_power = mysqli_fetch_assoc($run_check);
			if ($staff_power <= $needed_delete_power ['delete_power']) {
				$sql = "DELETE from announcements WHERE id='$_GET[id]'";
				$run_sql = mysqli_query($conn,$sql);
			} else {
				echo '<script>alert("This post has been created by a staff member of higher power. You have insufficient permissions to delete this post.")</script>';
			}
		} else {
			echo '<script>alert("Sorry, you are unauthorized to delete announcements")</script>';
		}
	} else if (isset($_GET['edit_id'])) {
		echo '<script>alert("Sorry, this section of the control panel has not been coded yet")</script>';
	}
?>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/user.css">
</head>

<style>
	a.box-cust {
		text-decoration:none;
		width:50%;
	}
	.centered {
		text-align:center;
	}
</style>

<body>
<?php
	if (isset($_SESSION['login']) && $staff_power < 10)
	if ($_SESSION['login'] == true) {
echo '
<div id="createPost" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <button type="button" class="close" data-dismiss="modal" style="padding:20px 30px">&times;</button>
      <div class="modal-header text-center text-header-modal">
        <h4 class="modal-title text-white">Create a New Announcement</h4>
      </div>
      <div class="modal-body modal-custom">
        <form class="create-ann" method="POST">
	   <p>Title*:<p>
	   <input class="ann-create text-center" placeholder="Announcement Title" name="title" required></input>
	   <p>Content*:<p>
	   <textarea class="ann-create text-center" placeholder="Content" name="content" required></textarea>
	   <hr>
	   <button type="submit" class="button button-blue fill" name"submit">Submit</button>
	</form>
      </div>
      <div class="modal-footer text-footer-modal">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

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
        <li class="active"><a href="#">Announcements </a></li>
        <li><a href="staff_list">Staff List</a></li>
        <li><a href="mailer">Mailer </a></li>
    </ul>';
	$sql = "SELECT * FROM announcements ORDER BY id DESC";
	$run_sql = mysqli_query($conn, $sql);
	echo '
    <div class="container post">
        <div class="bar">
            <button class="box-cust foo-cust" type="button" data-toggle="modal" data-target="#createPost">Create an Announcement </button><br><br>
	</div>';
	while ($rows = mysqli_fetch_assoc($run_sql)) {
	echo '
	<div class="row">
          <div class="col-md-4 post-title">
              <h1>'.$rows['Title'].'</h1>
              <p class="author"><strong>'.$rows['author'].'</strong> <span class="text-muted">'.$rows['role'].' '.$rows['date'].'</span></p>
          </div>
          <div class="col-md-8 post-body">
              <p>'.$rows['content'].'</p><hr>';
	if ($staff_power <= 0) {
	      echo'<div class="centered"><a href="edit?edit_id='.$rows['id'].'" class="box-cust foo-cust">Edit</a>';
	      echo'<a href="announcements?id='.$rows['id'].'" class="box-cust foo-cust danger">Delete</a></div>';
	}
	echo '
          </div>
	</div>
	<hr>';
    }
echo '
      </div>
    </div>
    <footer>
        <h5>AIM Gaming© 2016</h5></footer>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>';}
	?>
</body>

</html>
