<?php
	session_start();
	require "includes/ts3admin/lib/ts3admin.class.php";
	require "includes/handles/header.php";
	require "includes/db/db.php";
	require "includes/handles/loginchecker.php";
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");

	// For future usage, include this in the header to simplify things xD
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://USERNAME:PASSWORD@SERVER_IP:QUERY_PORT/?server_port=SERVER_PORT&nickname=Legacy");
	$counter = 1;
?>

<html>

<head>
	<title>CG Staff Announcements</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<!-- Custon CSS -->
<style>
.navbar {
	margin: 0px;
	margin-bottom: 50px;
}

.container-fluid-cust {
	color: white;
	margin: 0px;
	padding: 30px 15px;
}

.head {
	margin:0px;
	padding: 15px 10px;
	color: white;
	background-color: #5a5b5b;
}

.pan-body {
	margin: 0px;
	padding: 30px 40px;
	background-color:rgba(50, 50, 51,0.7);
	transition: background-color 1s;
}
th {
	color: white;
}
td {
	color: white;
}

.pan-body:hover {
	background-color:rgba(50, 50, 51,1);
}

a {
	color: white;
	transition: color 0.5s;
}

a:hover {
	color: red;
}

.bg {
	background: url("xd2.jpg") no-repeat center;
	background-size: cover;
	background-attachment: fixed;
	text-align: center;
}

input[type=text] {
	background-color: transparent;
	border: none;
	border-bottom: 1px solid white;
	border-radius: 0px;
	outline: none;
	color: white;
}
input[type=password] {
	background-color: transparent;
	border: none;
	border-bottom: 1px solid white;
	border-radius: 0px;
	color: white;
}
input[type=email] {
	background-color: transparent;
	border: none;
	border-bottom: 1px solid white;
	border-radius: 0px;
	color: white;
}
input[type=number] {
	background-color: transparent;
	border: none;
	border-bottom: 1px solid white;
	border-radius: 0px;
	color: white;
}
textarea[type=text] {
	background-color: transparent;
	border: 1px solid white;
	border-bottom: 1px solid white;
	border-radius: 0px;
	color: white;
}
</style>

<body class="bg">
	<header class="navbar navbar-inverse navbar-static-top">
		<div class="container-fluid">
			<a href="index.php" class="navbar-brand">Crusty Control Panel</a>
			<ul class="nav navbar-nav">
				<li><a href="/">Currently Online</a></li>
				<li><a href="reg.php">Members</a></li>
				<li><a href="members.php">Teamspeak Members</a></li>
				<li class="active"><a href="#">Announcements</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php
					if ($_SESSION['access'] >= 9) {
						echo '<li><a href="ban_appeals.php">Ban Appeals</a></li>';
					}
					if ($_SESSION['access'] >= 10) {
						echo '<li><a href="balist.php">Ban List</a></li><li><a href="admin.php">Admin</a></li>';
					}
				?>
				<li class="dropdown">
	      	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="caret"></span></a>
	      	<ul class="dropdown-menu">
						<li><a href="#">Messages</a></li>
						<li><a href="settings.php">Account Settings</a></li>
	        	<li><a href="auth/logout.php">Logout</a></li>
	        </ul>
	      </li>
			</ul>
		</div>
	</header>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-4">
					<div class="head">High Priorty Announcements</div>
					<div class="pan-body">
						<table class="table">
								<thead>
									<tr>
										<th>Title</th>
										<th>Author</th>
										<th>Priority</th>
									</tr>
								</thead>
								<tbody>
								<?php
									if ($_SESSION['access'] >= 4) {
										$sql = "SELECT * FROM staff_announcement WHERE priority='High' ORDER BY id DESC";
										$run_sql = mysqli_query($conn,$sql);
										if (mysqli_num_rows($run_sql) !== 0) {
											while ($rows = mysqli_fetch_assoc($run_sql)) {
												echo '<tr>';
												echo '<td><a href="content.php?id='.$rows['id'].'">'.$rows['title'].'</a></td>';
												echo '<td>'.$rows['author'].'</td>';
												echo '<td><strong style="color:red">'.$rows['priority'].'</strong></td>';
												echo '</tr>';
											}
										} else {
											echo '<tr>
												<td>No data found</td>
												<td>No data found</td>
												<td>No data found</td>
												</tr>';
										}
									} else {
										die("insufficient Permissions");
									}
								?>
								</tbody>
						</table>
					</div>
			</div>
			<div class="col-lg-4">
					<div class="head">Medium Priorty Announcements</div>
					<div class="pan-body">
						<table class="table">
								<thead>
									<tr>
									<th>Title</th>
										<th>Author</th>
									<th>Priority</th>
									</tr>
								</thead>
								<tbody>
								<?php
									if ($_SESSION['access'] >= 4) {
										$sql = "SELECT * FROM staff_announcement WHERE priority='Medium' ORDER BY id DESC";
										$run_sql = mysqli_query($conn,$sql);
										if (mysqli_num_rows($run_sql) !== 0) {
											while ($rows = mysqli_fetch_assoc($run_sql)) {
												echo '<tr>';
												echo '<td><a href="content.php?id='.$rows['id'].'">'.$rows['title'].'</a></td>';
												echo '<td>'.$rows['author'].'</td>';
												echo '<td><strong style="color:orange">'.$rows['priority'].'</strong></td>';
												echo '</tr>';
											}
										} else {
											echo '<tr>
												<td>No data found</td>
												<td>No data found</td>
												<td>No data found</td>
												</tr>';
										}
									} else {
										die("insufficient Permissions");
									}
								?>
								</tbody>
						</table>
					</div>
			</div>
			<div class="col-lg-4">
				<div class="head">Low Priorty Announcements</div>
				<div class="pan-body">
					<table class="table ">
						<thead>
							<tr>
								<th>Title</th>
								<th>Author</th>
								<th>Priority</th>
							</tr>
						</thead>
						<tbody>
						<?php
							if ($_SESSION['access'] >= 4) {
								// you will need to modify the query based on your needs/database
								$sql = "SELECT * FROM staff_announcement WHERE priority='Low' ORDER BY id DESC";
								$run_sql = mysqli_query($conn,$sql);
								if (mysqli_num_rows($run_sql) !== 0) {
									while ($rows = mysqli_fetch_assoc($run_sql)) {
										echo '<tr>';
										echo '<td><a href="content.php?id='.$rows['id'].'">'.$rows['title'].'</a></td>';
										echo '<td>'.$rows['author'].'</td>';
										echo '<td><strong style="color:blue">'.$rows['priority'].'</strong></td>';
										echo '</tr>';
									}
								} else {
									echo '<tr>
										<td>No data found</td>
										<td>No data found</td>
										<td>No data found</td>
										</tr>';
								}
							} else {
								die("insufficient Permissions");
							}
						?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</body>

</html>
