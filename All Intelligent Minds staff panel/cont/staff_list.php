<?php
	require "assets/Includes/header.php";
	require "assets/Includes/func/functions.php";
	if ($staff_name != '' && $staff_power < 7) {
		if (isset($_POST['name'])) {
			$name = escape_input($_POST['name']);
			$email = escape_input($_POST['email']);
			if (isset($_POST['role'])) {
				if ($staff_power > 0) {
					echo '<script>alert("Sorry, you have insufficient permissions to set a staff role")</script>';
				}
			}
			if ($staff_power < 4) {
				$approval_state = '<strong style=color:green>Auto-Approved</strong>';
				switch ($staff_power) {
					case 0:
						$role = escape_input($_POST['role']);
						$power_level = assign_power_level($role);
						break;
					case 1:
						$role = 228730;
						$power_level = 9;
						break;
					case 2:
						$role = 339182;
						$power_level = 9;
						break;
					case 3:
						$role = 889103;
						$power_level = 9;
						break;
				}
			} else {
				$approval_state = '<strong style=color:orange>Awaiting Approval</strong>';
				$role ='Nominated';
				$power_level = 10;

			}
			$sql = "INSERT INTO staff_list (Name, Contact, Role, Approval, ip_of_nominator, power_level) VALUES ('$name','$email', '$role','$approval_state', '$_SERVER[REMOTE_ADDR]', '$power_level')";
			$run_sql = mysqli_query($conn,$sql);
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

<style>
	.block-cust {
		width:100%;
		text-align:center;
	}
</style>

<body>
<?php
	if (isset($_SESSION['login']) && $staff_name < 10)
	if ($_SESSION['login'] == true) {
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
        <li class="active"><a href="#">Staff List</a></li>
        <li><a href="mailer">Mailer </a></li>
    </ul>
    <div class="container post">
	<div class="row">
	<div class="col-lg-8">
        <table class="table table-hover">
          <thead>
            <tr>
              <td>Staff Name</td>
              <td>Role</td>
              <td>Approval</td>
              <td>Contact</td>
            </tr>
          </thead>';
	$sql = "SELECT * FROM staff_list ORDER BY power_level ASC";
	$run_sql = mysqli_query($conn,$sql);
	while ($rows = mysqli_fetch_assoc($run_sql)) {
	echo '
          <tbody>
            <tr>
              <td>'.$rows['Name'].'</td>
              <td>';
		$role_sql = "SELECT * FROM staff_roles WHERE role_code='$rows[Role]'";
		$role_run_sql = mysqli_query($conn,$role_sql);
		while ($role = mysqli_fetch_assoc($role_run_sql)) {
			echo $role['role_name'];
		}
		echo'</td>
              <td>'.$rows['Approval'].'</td>
              <td><a class="btn btn-primary btn-xs disabled">Contact</a></td>
            </tr>
          </tbody> ';
	}
	echo '
        </table>
    </div>
    <div class="col-lg-4">
	<form class="panel-group form-horizontal" role="form" method="POST" action="/cont/staff_list.php">
          <div class="panel panel-default">
            <div class="panel-heading">Nominate Staff</div>
            <div class="panel-body">
              <div class="form-group">
                <label for="name" class="control-label col-sm-4">Staff Name</label>
                  <div class="col-sm-8">
                    <input type="text" name="name" id="name" class="form-control" placeholder="Staff Name to Nominate">
                  </div>
              </div>
              <div class="form-group">
                <label for="mail" class="control-label col-sm-4">Email</label>
                  <div class="col-sm-8">
                    <input type="email" name="email" id="email" class="form-control" placeholder="Private Contact Email">
                  </div>
              </div>
	      <div class="form-group">
                <label for="mail" class="control-label col-sm-4">Role</label>
                  <div class="col-sm-8">
<select class="selectpicker form-control show-tick" name="role" required>
  <option>Select Staff Role</option>
  <optgroup label="Adminstration Department">
    <option value="334782">Community Admin			</option>
    <option value="773928">Head Moderator			</option>
    <option value="634419">Head Event Coordinator		</option>
    <option value="589112">Head Recruiter			</option>
  </optgroup>
  <optgroup label="Moderation Department">
    <option value="889153">Elite Moderator			</option>
    <option value="993281">Moderator				</option>
    <option value="228730">Moderator in Training		</option>
  </optgroup>
  <optgroup label="Event Coordination Department">
    <option value="009740">Elite Event Coordinator		</option>
    <option value="000113">Event Coordinator			</option>
    <option value="339182">Event Coordinator in Training	</option>
  </optgroup>
  <optgroup label="Recruiting Department">
    <option value="114781">Elite Recruiter			</option>
    <option value="771827">Recruiter				</option>
    <option value="889103">Recruiter in Training		</option>
  </optgroup>
</select>

                  </div>
	      </div>
              <div class="form-group">
                  <div class="col-sm-12">
                    <button type="submit" class="btn btn-success btn-block">Nominate</button>
                  </div>
              </div>
            </div>
          </div>
        </form>
    </div>
    </div>
    </div>
    <footer>
        <h5>AIM Gaming© 2016</h5></footer>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>';}
	?>
</body>

</html>
