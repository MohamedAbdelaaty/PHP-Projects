<?php
	session_start();
	require "includes/db.php";
	require "includes/func/functions.php";
	$counter = 0;
	$_SESSION['final_order'] = '';
	if(!isset($_GET['confirm'])) {
		$_SESSION['order_check'] = '';
		$_SESSION['total'] = 0;
	} else {
		foreach ($_SESSION['order_check'] as $ord) {
			$_SESSION['final_order'] .= $ord;
		}
		$_SESSION['final_order_conf'] = mysqli_real_escape_string($conn,$_SESSION['final_order']);
		$order = $_SESSION['final_order'];
		settype($order, "string");
		$sql = "INSERT INTO orders (name,email,number,total,payment_method) VALUES ('$_SESSION[name]','$_SESSION[email]','$_SESSION[number]','$_SESSION[total]','$_SESSION[pm]')";
		$run_sql = mysqli_query($conn,$sql);
		$sql = "UPDATE orders set order='$order' WHERE name='".$_SESSION['name']."'";
		$run_sql = mysqli_query($conn,$sql);
		if (! $run_sql) {
			die('Could not enter data: '.mysqli_error($conn));
		}
	}
	if (!isset($_SESSION['total'])) {
		$_SESSION['total'] = 0;
	}
	if (isset($_POST['nameF']) && isset($_POST['nameL'])) {
		$_SESSION['name'] = $_POST['nameF'].' '.$_POST['nameL'];
		$_SESSION['number'] = $_POST['number'];
		$_SESSION['email'] = $_POST['email'];
	} else if (isset($_SESSION['name']) && isset($_POST['PM'])) {
		$order = array();
		foreach ($_POST['order'] as $selectedOption) {
			$order[] = $selectedOption;
		}
		$_SESSION['order'] = $order;
		$_SESSION['addition_info'] = $_POST['instruct'];
		$_SESSION['pm'] = $_POST['PM'];
	} else if (isset($_SESSION['addition_info']) && isset($_POST['action'])) {
		foreach ($_SESSION['order'] as $ord) {
			if ($_POST["$ord"] > 0) {
				$sql = "SELECT * FROM menu WHERE code='$ord'";
				$run_sql= mysqli_query($conn,$sql);
				while ($rows=mysqli_fetch_assoc($run_sql)) {
					$order_confirm[] = $rows['name'].'<br><strong> quantity </strong> '.$_POST["$ord"].'<br>';
					$price= $rows['price'];
					settype($price, "integer");
					settype($_POST["$ord"], "integer");
					$_SESSION['total'] += $_POST["$ord"]*$rows['price'];
				}
			}
		}
		$_SESSION['order_check'] = $order_confirm;
	} else if (isset($_GET['cancel'])) {
		unset($_SESSION['name']);
		if (isset($_SESSION['order'])) {
			unset($_SESSION['order']);
		}
		if (isset($_SESSION['total'])) {
			unset($_SESSION['total']);
		}
		if (isset($_SESSION['additional_info'])) {
			unset($_SESSION['additional_info']);
		}
		if (isset($_SESSION['order_check'])) {
			unset($_SESSION['order_check']);
			unset($_SESSION['final_order']);
			unset($_SESSION['email']);
			unset($_SESSION['number']);
		}
	}
	
?>

<html>

<script>
	
</script>

<head>
	<title>Saudies</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="includes/css/user.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Tangerine">
	<link rel="stylesheet" href="bootstrap-select-master/dist/css/bootstrap-select.css">
	<script src="bootstrap-select-master/dist/js/bootstrap-select.js"></script>
</head>

<body>
	<nav class="navbar navbar-inverse navbar-static-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span> 
				</button>
				<a class="navbar-brand" href="#">Saudies</a>
			</div>
			<div class="collapse navbar-collapse" id="navbar">
				<ul class="nav navbar-nav">
					<li><a href="/">Home</a></li>
					<li><a href="status.php">Status</a></li>
					<li><a href="menu.php">Menu</a></li>
					<li class="active"><a href="#">Online Order</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<?php
						if (!isset($_SESSION['logged_in'])) {
							echo '<li><a href="http://saudies.ml/auth/login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>';
						} else if ($_SESSION['power_level'] >= 10){
							echo '<li><a href="admin.php">Admin</a></li>';
 						        echo '<li class="dropdown">
                						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="caret"></span></a>
                							<ul class="dropdown-menu">
                							 <li><a href="auth/logout.php">Logout</a></li>
                							</ul>
              							</li>';						} else {
							echo '<li><a href="#">Account</a></li>';
						}
					?>
				</ul>
			</div>
		</div>
	</nav>
	<?php if (!isset($_SESSION['name'])) {
	echo '
	<div class="container-fluid menu-page">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2">
					<div class="panel panel-primary">
					<div class="panel-heading">Place an order online - Contact Information</div>
					<div class="panel-body">
						<form role="form" method="post" style="color:black;">
							<div class="form-group">
								<div class="row">
									<div class="col-md-6">
										<label for="subject">First Name* </label>
										<input type="text" class="form-control" name="nameF" placeholder="Enter your first name" required>
									</div>
									<div class="col-md-6">
										<label for="subject">Last Name* </label>
										<input type="text" class="form-control" name="nameL" placeholder="Enter your last name" required>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label >Email address* </label>
								<input type="email" class="form-control" name="email" placeholder="Enter your email" required>
							</div>
							<div class="form-group">
								<label >Phone Number* </label>
								<input type="text" class="form-control" name="number" placeholder="Enter your phone number" required>
							</div>
							<button type="submit" class="btn btn-primary btn-block">Continue</button>
						</form>
					</div>
					</div><br><br><br><br>
				</div>
			</div>
		</div>
	</div>';
	} else if (!isset($_SESSION['order'])) {
	echo '
	<div class="container-fluid menu-page">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2">
					<div class="panel panel-primary">
					<div class="panel-heading">Place an order online - Payment Method & Order<hr>
					Ordering as: <strong>'.$_SESSION['name'].'</strong>
					</div>
					<div class="panel-body">
						<form role="form" method="post" style="color:black;">
							<div class="form-group">
								<label for="PM">Payment Method*</label> <br>
								<select class="selectpicker" name="PM">
									<option value="cash">Cash</option>
									<option value="credit">Credit Card</option>
								</select>
							</div>
							<div class="form-group">
								<label for="subject">Order* </label><br>
								<select class="selectpicker"  data-live-search="true" name="order[]" multiple data-selected-text-format="count > 3">
									<optgroup label="Sandwiches">
										<option data-tokens="burger" value="CB">Chicken Burger</option>
										<option data-tokens="burger" value="BB">Beef Burger</option>
										<option data-tokens="kabab" value ="KB">Kabab</option>
									</optgroup>
									<optgroup label="fries">
										<option data-tokens="fries" value="FR">Fries</option>
									</optgroup>
									<optgroup label="Chicken Breasts">
										<option data-tokens="chicken breasts" value="CBR">Chicken Breasts</option>
									</optgroup>
									<optgroup label="Chicken Nuggets">
										<option data-tokens="chicken nuggets" value="4CN">4pc Chicken Nuggets</option>
										<option data-tokens="chicken nuggets" value="8CN">8pc Chicken Nuggets</option>
									</optgroup>
								</select>
							</div>
							<details>
								Select the items you are planning to order, in the next page you will be able to set quantity
							</details>
							<hr>
							<div class="form-group">
								<label>Additional Instructions</label>
								<textarea class="form-control" name="instruct" rows="3"></textarea>
							</div>
							<button type="submit" class="btn btn-primary btn-block">Continue</button>
						</form>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>';
	} else {
		echo '
	<div class="container-fluid menu-page">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2">
					<div class="panel panel-primary">
					<div class="panel-heading">Place an order online - Confirm Order<hr>
					Ordering as: <strong>'.$_SESSION['name'].'</strong>
					</div>
					<div class="panel-body">
						<form role="form" method="post" style="color:black;">';
						echo '<div class="row">';
						foreach ($_SESSION['order'] as $ord) {
							$sql = "SELECT * FROM menu WHERE code='$ord'";
							$run_sql = mysqli_query($conn,$sql);
							while ($rows = mysqli_fetch_assoc($run_sql)) {
								echo '<div class="col-md-6">';
									echo '<strong style="color:blue">'.$rows['name'].'</strong><br>';
									echo 'Quantity:'; 
								echo '<input type="number" name="'.$rows['code'].'" style="width:60px;" class="form-control" required><hr></div>';
							}
						}
						echo '
							</div>
							<br>
							<div id="price">
								For : <br><br><span style="color:blue">';	
							foreach ($_SESSION['order_check'] as $ord) {
								echo $ord;
							}
							echo '</span>
								<strong style="color:red">Price: $'.$_SESSION['total'].'</strong>';
 						if ($_SESSION['total'] > 0) {
							echo '<br><a href="?confirm=true" class="btn btn-primary">Confirm Order</a>';
						}
						echo '
							</div>
							<hr>
							<button type="submit" class="btn btn-info btn-block" name="action" value="checkPrice">Check Price</button>
							<a class="btn btn-danger btn-block" href="?cancel=true">Cancel</a>
						</form>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
		';
	}
	?>
	
	<footer>
		<div class="centered-cust">
			<h3 class="copyrights">Saudies© 2017</h3>
		</div>
</body>

</html>