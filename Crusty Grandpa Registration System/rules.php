<?php
	session_start();
	require('tourney/ritoGems/php-riot-api.php');
	require('tourney/ritoGems/FileSystemCache.php');
	if (isset($_SESSION['ProxyPass'])) {
		if (!$_SESSION['ProxyPass'] == true) {
			die ("Proxy check failed");
		}
	} else {
		die ('Proxy verification not found, please click <a href="/">here</a>');
	}
	if (!isset($_SESSION['email'])) {
		if (isset($_POST['email'])) {
			// set session values for registration process. Used across files
			$_SESSION['email'] = $_POST['email']; 									// email
			$_SESSION['password'] = $_POST['password']; 							// password
			$_SESSION['references'] = $_POST['reference']; 							// referral
			$_SESSION['summoner_name'] = $_POST['lol_sum'];							// League of Legends summoner name
			$_SESSION['display_name'] = $_POST['name'];								// Display name
			
			// League of legends summoner set
			if ($_SESSION['summoner_name'] != '') {
				$riot = new riotapi('na');
				$cache = new riotapi('na', new FileSystemCache('cache/'));
				try {
					
					$summ = strtolower(str_replace(' ','',$_SESSION['summoner_name']));	// remove spaces from display names to match names returned from riot api
					$r = $riot->getSummonerByName($summ); 								// fetch summoner
					
					// summoner information
					$_SESSION['summoner_id'] = $r[$summ]['id'];		
					$r = $riot->getLeague($_SESSION['summoner_id'],"entry");
					$_SESSION['summoner_tier'] = $r[$_SESSION['summoner_id']][0]['tier'];
					$_SESSION['summoner_division'] = $r[$_SESSION['summoner_id']][0]['entries'][0]['division'];
				} catch(Exception $e) {
					// if summoner not found, use lolking link to fetch account ID
					if ($_POST['lol-king'] != '') {
						$str = $_POST['lol-king'];
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
							// try to fetch user through user id
							$_SESSION['summoner_id'] = $summoner_id;
							$r2 = $riot->getLeague($_SESSION['summoner_id'],"entry");
							$_SESSION['summoner_tier'] = $r2[$_SESSION['summoner_id']][0]['tier'];
							$_SESSION['summoner_division'] = $r2[$_SESSION['summoner_id']][0]['entries'][0]['division'];
						} catch(Exception $ee) {
							die ('Error: '.$ee);
						}
						
					} else {
						// if lol king account not provided, kill page
						// a better, more user friendly output might be a better idea here.
						die ("Could not find an account with the account name provided. Additionally, lol-king link is not provided as another method for verification");
					}
				};
			} else {
				// if no summoner name is provided, assume user does not have account
				$_SESSION['summoner_id'] = '';
				$_SESSION['summoner_tier'] = '';
				$_SESSION['summoner_division'] = '';
			}
			$_SESSION['read_timer'] = time();
		} else {
			die("Data Missing");
		}
	} else {
		// read timer to check how long clients took to read the rules
		$_SESSION['read_timer'] = time();
	}
?>

<html>

<head>
	<title>Community Rules</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>	
</head>
<style>
.navbar {
	margin: 0px;
	margin-bottom: 20px;
}

.container-fluid-cust {
	color: white;
	margin: 0px;
	padding: 30px 15px;
}

.head {
	margin:0px;
	padding: 15px 10px;
	background-color: #5a5b5b;
	text-align: center;
}

.pan-body {
	margin: 0px;
	padding: 30px 40px;
	background-color:#323233;
}


.bg {
	background: url("tourney/bg1.jpg") no-repeat center;
	background-size: cover;
	color: white;
	background-attachment: fixed;
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
	<nav class="navbar navbar-inverse navbar-static-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="#">Crusty Registration</a>
			</div>
			<ul class="nav navbar-nav">
				<li class="active"><a href="#">Register</a></li>
			</ul>
		</div>
	</nav>
	<div class="container">
	<div class="row">
	<div class="head col-lg-12">Rules</div>
	<div class="col-lg-12 pan-body">
	<ol>
		<li>You must be registered as a member to be given all the privileges that come with being a member. This includes: Community Events (Tournaments, In-Houses...), moving about in other channels, etc. Membership can be denied when the applicant has displayed behavior that would not comply with our Community Rules.</li>
		<li>Server sniping is NOT ALLOWED. Consider this your ONLY warning. (This means don't come to our community and tell others to go somewhere else! )</li>
		<li>No use of voice changers, raging, trolling, verbal abuse, sexual harassment, degrading language. Stay away from ALL of the negative –isms (racism, sexism etc.).</li>
		<li>No sexually explicit material allowed. We reserve the right to judge based on our own opinion what is inappropriate and to have you cooperate with us in the removal of such materials. (User names, Avatars, Links, etc.)</li>
		<li>No "Extreme" drug related material is permitted. By extreme, we mean things such as but not limited to: 
			<ul>
				<li>Avatars of drug use</li>
				<li>Links to drug related material </li>
				<li>Inappropriate user names. {We reserve the right to judge based on our own opinion what is inappropriate and to have you cooperate with us in the removal of such materials.}</li>
			</ul>
		<li>No screamers. Screamers are links to media (typically gifs) that are designed to disturb, surprise, scare or irritate the recipient.</li>
		<li>No impersonating any staff including Admins or Staff Tags. A Staff Tag is identified as any word in front of your teamspeak name in a square bracket i.e. [word] Name/Name [word]. </li>
		<li>Any use of real world influence including but not limited to: Emotional, Physical, Monetary or Psychological to gain status or affect the community for personal reasons is subject to a ban.</li>
		<li>By connecting, becoming, and staying as a member of Practically Useless Gamers, you consent to being recorded by staff when appropriate. Such recordings may be used as evidence later on in relating moderation issues. Players upon request in tournament may be granted permission to record.
		<li>Practically Useless Gamers does not allow ANY FORM of harassment. When a member is accused and confirmed to have been committing any form of  harassment, a single warning will be given, or immediate disciplinary action will be taken based on the degree of harassment.
		<li>If you feel the need to be a complete cuck and ban yourself, just ask, we'll Hodor for you. {Ozuko ~ Bat Admin}
		<li>Practically Useless Gamers Administration reserves the right to amend, change, or alter any of the rules listed as necessary to ensure a great environment for all members and staff. The rules may be changed without further notice.
		<li>Use Common Sense, these rules are not all encompassing. Anyone found disrespecting either other people, Mods, or Admins on the server will be either kicked or banned.
		<li>Soundboard spam is annoying and obnoxious. Playing a soundboard clip every once in a while is okay, but repeatedly spamming said soundboard just irritates people. Don't do it. Also, if someone asks you not to use a soundboard, you should probably respect their request.
	</ol>
	<form action="email_verification.php" method="POST">
    		<div class="checkbox">
      			<label><input type="checkbox" required> I have Read and Agree to the rules stated above</label>
    		</div>
		<button type="submit" class="btn btn-success btn-block">Confirm Registration</button>
	</form>
	</div>
	</div>
	</div>
</body>

</html>