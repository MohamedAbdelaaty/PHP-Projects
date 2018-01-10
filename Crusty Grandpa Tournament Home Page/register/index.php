<?php
	session_start();
	require '../includes/db/db.php';
	require '../includes/ritoGems/php-riot-api.php';
	require '../includes/ritoGems/FileSystemCache.php';
	require "../includes/ts3admin/lib/ts3admin.class.php";
	require_once("../includes/libraries/TeamSpeak3/TeamSpeak3.php");
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://USERNAME:PASSWORD@SERVER_IP:QUERY_PORT/?server_port=9987&nickname=Legacy");

	$tsAdmin = new ts3admin('' /* Server IP */, 10011);

	$username=""; // Query Username
	$password=""; // Query Password
	$ts3_Port = 9987; // Server Port

	$riot = new riotapi('na'); // initializing riot API
	$cache = new riotapi('na', new FileSystemCache('cache/'));

	if (isset($_SESSION['display_name'])) {
		die('Already logged in');
	}

	// Check if step one is through
	if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_ver']) && isset($_POST['ts3uid']) && isset($_POST['accountName']) && isset($_POST['accountLink'])) {
		// Custom wrapper i made for google recaptcha
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$data = array('secret' => '' /* Secret Key  */, 'response' => $_POST['g-recaptcha-response']);

		$options = array(
			'http' => array(
		        	'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
	        		'method'  => 'POST',
	        		'content' => http_build_query($data)
			)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE) { die ('ReCapcha Error'); }
		$explodedData = json_decode($result, true);

		// on success of recaptcha
		if ( $explodedData['success'] == 1) {
			// check that the passwords match
			if ($_POST['password'] == $_POST['password_ver']) {
				$email = mysqli_real_escape_string($conn, $_POST['email']);

				// scan for email duplicates
				$sql = "SELECT * FROM members WHERE email='$email'";
				$run_sql = mysqli_query($conn,$sql);

				// if no duplicates found
				if (mysqli_num_rows($run_sql) === 0) {
					// scan for league of legends account name
					$account_name = mysqli_real_escape_string($conn,$_POST['accountName']);
					$mysql = "SELECT * FROM lol_accounts WHERE account_name='$account_name'";
					$run_sql = mysqli_query($conn,$mysql);
					if (mysqli_num_rows($run_sql) !== 0) {
						die ('The league of legends account you have entered has been previously registered.');
					}

					// scan for UID duplicates
					$uid = mysqli_real_escape_string($conn,$_POST['ts3uid']);
					$mysql_2 = "SELECT * FROM ts3_identities WHERE uid='$uid'";
					$run_sql_2 = mysqli_query($conn,$mysql_2);

					if (mysqli_num_rows($run_sql_2) !== 0) {
						die ('Teamspeak 3 identity is already registered');
					}

					// email verification, League of Legends account verification and session initializations
					$_SESSION['account_verification'] = rand(1000000000,10000000000);
					$_SESSION['registration_email'] = $email;
					$_SESSION['registration_password'] = hash('sha512', $_POST['password']); // SHA512 Encryption for the password
					$_SESSION['registration_display_name'] = mysqli_real_escape_string($conn,$_POST['dn']);
					$_SESSION['registration_uid'] = $_POST['ts3uid'];
					$_SESSION['registration_league_account_name'] = mysqli_real_escape_string($conn,$_POST['accountName']);
					$_SESSION['registration_league_account_profile'] = mysqli_real_escape_string($conn,$_POST['accountLink']);
					$_SESSION['registration_ip'] = $_SERVER['REMOTE_ADDR'];

					$to = $_SESSION['registration_email'];
					$_SESSION['email_confirmation'] = rand(8000000, 1000000000);
					require '../includes/mail/PHPMailerAutoload.php';

					$mail = new PHPMailer;

					//$mail->SMTPDebug = 3;                               	// Enable verbose debug output

					$mail->isSMTP();                                      	// Set mailer to use SMTP
					$mail->Host = '';  // Specify main and backup SMTP servers
					$mail->SMTPAuth = true;                               	// Enable SMTP authentication
					$mail->Username = '';                 // SMTP username
					$mail->Password = '';                       // SMTP password
					$mail->SMTPSecure = 'tls';                           	// Enable TLS encryption, `ssl` also accepted
					$mail->Port = 587;                                    	// TCP port to connect to

				  $mail->setFrom('' /* Sender Email */, 'E-Mail Confirmation' /* Title */);
					$mail->addAddress($to);    				// Add a recipient

					$mail->isHTML(true);                                  	// Set email format to HTML

					$mail->Subject = 'CG E-mail confirmation for '.$_SESSION['registration_display_name'];
					$mail->Body    = 'Thank you for registering for CG community, please confirm your email using the confirmation code found below.<br>
					Your confirmation code:'.$_SESSION['email_confirmation'];

					if(!$mail->send()) {
						echo "Connection to SMTP server failed. Registration cannot be completed at this time, please try again later."; exit;
					}
					$returned ='';

				} else {
					$returned = '<strong style="color:red">Email previously registered, if you forgot your password, please request a password reset email.</strong>';
					session_unset();
					session_regenerate_id(TRUE);
					session_destroy();
				}

			} else {
				$returned ='<strong style="color:red">Passwords do not match</strong>';
			}
		} else {
			$returned = '<strong style="color:red">Recaptcha Error: '.$explodedData['error-codes'][0].'</strong>';
		}
	} else {
		$returned = '';
	}

	// Email confirmation code entered by user. Check if the registration Email session is set (ensure that they went through the previous step) and check that the codes match
	// Could add bruteforce protection but, cloudflare auto filters requests like those so...
	if (isset($_POST['emailCode']) && isset($_SESSION['registration_email'])) {
		if ($_POST['emailCode'] == $_SESSION['email_confirmation']) {
			$_SESSION['email_confirmed'] = TRUE;
		} else {
			$returned = '<strong style="color:red">Invalid code</strong>';
		}
	}

	// Insert data into DB
	if (isset($_SESSION['email_confirmed']) && isset($_SESSION['account_verification_confirmation']) && isset($_POST['identityCode'])) {
		if ((int)$_POST['identityCode'] === $_SESSION['identity_code']) {
			$day = date("d");
			$month = date("m");
			$year = date("o");
			$date = $month.'/'.$day.'/'.$year.' '.date("h:i:sa");
			$sql = "INSERT INTO members (display_name,email,password,ip,ref,reg_date) VALUES ('$_SESSION[registration_display_name]','$_SESSION[registration_email]','$_SESSION[registration_password]','$_SESSION[registration_ip]','Tourney Registration','$date')";
			$run_sql = mysqli_query($conn,$sql);
			if($tsAdmin->getElement('success', $tsAdmin->connect())) {
				$tsAdmin->login($username,$password);

				$tsAdmin->selectServer($ts3_Port);

				$info = $tsAdmin->clientDbFind($_SESSION['registration_uid'], true);
				print_r($info);
				$cldbid = $info['data'][0]['cldbid'];
				echo $cldbid;
				$sql1 = "INSERT INTO ts3_identities (uid,dbid,profile) VALUES ('$_SESSION[registration_uid]','$cldbid','$_SESSION[registration_email]')";
				$run_sql = mysqli_query($conn,$sql1);
				$rank = $_SESSION['summoner_tier'].' '.$_SESSION['summoner_division'];
				$sql2 = "INSERT INTO lol_accounts (account_name,account_id,account_rank,profile) VALUES ('$_SESSION[registration_league_account_name]','$_SESSION[summoner_id]','$rank','$_SESSION[registration_email]')";
				$run_sql = mysqli_query($conn,$sql2);
				session_unset();
				session_regenerate_id(TRUE);
				session_destroy();
				$returned = '<strong style="color:green">Registration complete, <a href="../auth/">Click here to log in</a></strong>';
			} else {
				die("Connection to server failed");
			}
		} else {
			$returned = '<strong style="color:red">Invalid Code</strong>';
		}
	}

?>

<html>

<head>
	<title>Register for CG Tournaments</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<!--[if lte IE 8]><script src="../assets/js/ie/html5shiv.js"></script><![endif]-->
	<link rel="stylesheet" href="../assets/css/main.css" />
	<!--[if lte IE 9]><link rel="stylesheet" href="../assets/css/ie9.css" /><![endif]-->
	<!--[if lte IE 8]><link rel="stylesheet" href="../assets/css/ie8.css" /><![endif]-->
	<script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<style>
.pad {
	padding: 2em 4em;
}
</style>

<body>
	<div id="page-wrapper">
		<!-- Header -->
			<header id="header">
				<h1 id="logo"><a href="index.html">CG Tournaments</a></h1>
				<nav id="nav">
					<ul>
						<li><a href="/">Home</a></li>
						<li><a href="#">Support</a></li>
					<?php
						if (!isset($_SESSION['display_name'])) {
							echo '
							<li><a href="#" class="button">Sign Up</a></li>
							<li><a href="../auth/" class="button special">Sign In</a></li>';
						} else {
							echo '
								<li>
									<a href="#">Account</a>
									<ul>
										<li><a href="#">Profile</a></li>
										<li><a href="#">League of Legends Account</a></li>
										<li><a href="#">Two Step Authentication</a></li>
										<li><a href="#">Teamspeak 3 Identities</a></li>
									</ul>
								</li>
							';
						}
					?>
				</ul>
				</nav>
			</header>
		<section class="pad">
			<?php
			if (!isset($_SESSION['registration_email'])) {
			echo $returned.'<br>
			<h3>Register for CG Tournaments</h3>
			<form method="post">
				<div class="row uniform 50%">
					<h4>General Information</h4>
					<div class="12u$">
						<input type="email" name="email" placeholder="Enter your Email - Will require verification*" required></input>
					</div>
					<div class="12u$">
						<input type="text" name="dn" placeholder="Enter a display name*" required></input>
					</div>
					<div class="12u$">
						<input type="password" name="password" placeholder="Enter your password*" required></input>
					</div>
					<div class="12u$">
						<input type="password" name="password_ver" placeholder="Re-enter your password*" required></input>
					</div>
					<div class="12u$">
						<input type="text" name="ts3uid" placeholder="Enter your teamspeak 3 identity UID - Will require verification*" required></input>
					</div>
					<div class="12u$">
						<hr>
						<h4>League of legends</h4>
					</div>
					<div class="12u$">
						<input type="text" name="accountName" placeholder="Enter your league of legends account name - Will require verification*" required></input>
					</div>
					<div class="12u$">
						<input type="text" name="accountLink" placeholder="Incase our system fails, please provide us with a link to your account on lol-king*" required></input>
					</div>

					<div class="12u$">
						<hr>
						<div class="g-recaptcha" data-sitekey="6Lf-NiEUAAAAABdKIVx1RCYMpiXiGlBy7g5bo3YT" data-theme="dark"></div>
					</div>
					<div class="12u$">
						<input type="submit" value="Register" class="special fit" /></li>
					</div>
				</div>
			</form>';
			} else if (!isset($_SESSION['email_confirmed'])) {
				echo $returned.'<br>
					<h3>Register for CG Tournaments</h3><br>'.$returned.'
					<form method="post">
						<div class="row uniform 50%">
							<h4>Email Confirmation</h4>
							<div class="12u$">
								<input type="text" name="emailCode" placeholder="Enter the code emailed to you*" required></input>
							</div>
							<div class="12u$">
								<input type="submit" value="Confirm Email" class="special fit" /></li>
							</div>
						</div>
					</form>
				';
			} else if (!isset($_SESSION['account_verification_confirmation'])) {

				// League of legends account confirmation
				try {
					$summ = strtolower(str_replace(' ','',$_SESSION['registration_league_account_name']));
					$r = $riot->getSummonerByName($summ);

					$_SESSION['summoner_id'] = $r[$summ]['id'];
					$r = $riot->getLeague($_SESSION['summoner_id'],"entry");
					$_SESSION['summoner_tier'] = $r[$_SESSION['summoner_id']][0]['tier'];
					$_SESSION['summoner_division'] = $r[$_SESSION['summoner_id']][0]['entries'][0]['division'];
				} catch(Exception $e) {
					$str = $_SESSION['registration_league_account_profile'];
	    				$r = explode("http://www.lolking.net/summoner/na/",$str);
					$counter = 0;
					foreach ($r as $rr) {
						if ($counter == 1) {
							$sum_id = explode('/',$rr);
							$summoner_id = $sum_id[0];
						}
						$counter++;
					}
					try {
						$_SESSION['summoner_id'] = $summoner_id;
						$r2 = $riot->getLeague($_SESSION['summoner_id'],"entry");
						$_SESSION['summoner_tier'] = $r2[$_SESSION['summoner_id']][0]['tier'];
						$_SESSION['summoner_division'] = $r2[$_SESSION['summoner_id']][0]['entries'][0]['division'];
					} catch(Exception $ee) {
						die ('Error: '.$ee);
					}
				};

				echo '<strong style="color:red"> Please set one of the following mastery pages or create a new mastery page and set the name to: </strong>'.$_SESSION['account_verification'].'<hr>';
				try {
					$r = $riot->getSummoner($_SESSION['summoner_id'],'masteries');
					foreach ($r as $rr) {
						foreach ($rr as $rrr) {
							foreach ($rrr as $rrrr) {
								echo $counter.' '.$rrrr['name'].'<br>';
								$counter++;
								if (isset($_POST['check'])) {
									if ($rrrr['name'] == $_SESSION['account_verification']) {
										$_SESSION['account_verification_confirmation'] = true;
										$returned = '<strong style="color:green">Verification completed. If you are reading this, press validate again.</strong>';
									} else {
										$returned = '<strong style="color:red">Verification Failed</strong>';
									}
								}
							}
						}
					}
				} catch(Exception $e) {
					die( "Error: " . $e->getMessage());
				};
				echo $returned.'
				<form method="post">
					<div class="row uniform 50%">
						<div class="12u$">
							<input type="submit" name="check" value="Verfy Account" class="special fit" /></li>
						</div>
					</div>
				</form>';
			} else {
				if (!isset($_SESSION['identity_code'])) {
					$_SESSION['identity_code'] = rand(10000000,100000000);
					$ts3_VirtualServer->clientGetByUid($_SESSION['registration_uid'])->poke($_SESSION['identity_code']);
				}

				echo $returned.'
					<form method="post">
						<h4>Confirm your TS3 Identity</h4>
						<div class="12u$">
							<input type="text" name="identityCode" placeholder="Enter the code you have been poked on teamspeak*" required></input>
						</div>
						<div class="row uniform 50%">
							<div class="12u$">
								<input type="submit" value="Verify" class="special fit" />
							</div>
						</div>
					</form>';
			}
			?>
		</section>
	</div>

</body>

</html>
