<!DOCTYPE HTML>
<?php
	session_start();
?>
<html>
	<head>
		<title>CG Tournaments</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
	</head>
	<body class="landing">
		<div id="page-wrapper">

			<!-- Header -->
				<header id="header">
					<h1 id="logo"><a href="index.html">CG Tournaments</a></h1>
					<nav id="nav">
						<ul>
							<li class="active"><a href="#">Home</a></li>
							<li><a href="#">Support</a></li><!-- Discontinued [Sorry] -->
							<?php

								// Check if current visitor is logged in to know what to display
								if (!isset($_SESSION['display_name'])) {
									echo '
									<li><a href="register/" class="button">Sign Up</a></li>
									<li><a href="auth/" class="button special">Sign in</a></li>';
								} else {
									echo '
									<li>
										<a href="#">Account</a>
										<ul>
											<li><a href="profile.php">Profile</a></li>
											<li><a href="#">League of Legends Account</a></li><!-- Discontinued; However, is available during registration and in the client panel -->
											<li><a href="#">Two Step Authentication</a></li><!-- Discontinued -->
											<li><a href="#">Teamspeak 3 Identities</a></li><!-- Discontinued; However, is available during registration and in the client panel -->
											<li><a href="auth/logout.php">Logout</a></li>
										</ul>
									</li>
									';
								}
							?>
						</ul>
					</nav>
				</header>

			<!-- Banner -->
				<section id="banner">
					<div class="content">
						<header>
							<h2>CG LoL Tournaments</h2>
							<p>Welcome to the official Crusty Grandpa tournaments home page for league of legends.<br>
							We host league of legends tournaments weekly on saturdays, starting at ~7:30 PM EST<br>
							Come join the fun and win rewards!</p>
						</header>
						<span class="image"><img src="images/ahri_intro.jpg" alt="" /></span>
					</div>
					<a href="#one" class="goto-next scrolly">Next</a>
				</section>

			<!-- One -->
				<section id="one" class="spotlight style1 bottom">
					<span class="image fit main"><img src="images/TriumphRyze.jpg" alt="" /></span>
					<div class="content">
						<div class="container">
							<div class="row">
								<div class="4u 12u$(medium)">
									<header>
										<h2>Requirements to participate in the tournament</h2>
										<p>We at Crusty Grandpa try to ensure a fair and friendly environment for our tournaments. Which means we have certain expectations of our participants</p>
									</header>
								</div>
								<div class="4u 12u$(medium)">
									<p>Playing in our tournaments means that you:
										<ul>
											<li>Agree to the community rules</li>
											<li>Agree to not smurf and play on your primary league of legends account</li>
											<li>Agree to respect all staff members and players alike</li>
											<li>Have downloaded Teamspeak 3 and are willing to use it</li>
											<li>Have a ranked account in Season 7 for League of Legends</li>
										</ul>
									</p>
								</div>
								<div class="4u$ 12u$(medium)">
									<p>Some useful links:<br>
										<a href="http://teamspeak.com/downloads.html#client" class="button default">TS3 Download</a>
										<a href="https://signup.leagueoflegends.com/en/signup/redownload" class="button default">LoL Download</a>
										<hr><strong>Please note that our tournaments are for the NA region <u>ONLY</u></strong>
									</p>
								</div>
							</div>
						</div>
					</div>
					<a href="#two" class="goto-next scrolly">Next</a>
				</section>

			<!-- Two -->
				<section id="two" class="spotlight style2 right">
					<span class="image fit main"><img src="images/ahri.jpg" alt="" /></span>
					<div class="content">
						<header>
							<h2>Rewards</h2>
							<p>Tournament Rewards and Community Coins</p>
						</header>
						<p>In 2016, Riot Games discontinued sponsorships and prizing for online tournaments. We at Crusty Grandpa prize our own tournaments using community coins.
						With community coins you could purchase a variety of items, ranging from RP cards to various gift cards.
						<strong><u>Note:</u> The coin prizes are for every player of the team</strong>

						<ul>
							<li>First Place: 50 Coins along side a temporary icon and a channel</li>
							<li>First Place: 25 Coins</li>
							<li>First Place: 10 Coins</li>
							<li>First Place: 5 Coins</li>
						</ul>
						</p>
						<ul class="actions">
							<li><a href="http://coins.crustygrandpa.com" class="button special">Learn More</a></li>
						</ul>
					</div>
					<a href="#three" class="goto-next scrolly">Next</a>
				</section>

			<!-- Three -->
				<section id="three" class="spotlight style3 left">
					<span class="image fit main bottom"><img src="images/yasuo.jpg" alt="" /></span>
					<div class="content">
						<header>
							<h2>Process</h2>
							<p>The way the tournament works!</p>
						</header>
						<p>We at Crusty Grandpa host the tournaments as a solo queue style. You register for the tournament, once we have enough members registered,
						you will be placed in a team, with 4 other teammates.<br>Team changing is prohibited. If you do arrive late, you could potentially still participate
						in the tournament if another player decides to leave.</p>
					</div>
					<a href="#four" class="goto-next scrolly">Next</a>
				</section>

			<!-- Four -->
				<section id="four" class="wrapper style1 special fade-up">
					<div class="container">
						<header class="major">
							<h2>Last Week Winners</h2>
							<p>The following members were victorious during last week's tournament</p>
						</header>
						<div class="box alt">
							<div class="major">
								<ul class="actions">
									<li>Obviously</li>
									<li>Support Welfare</li>
									<li>PUG Crazfight</li>
									<li>BadNameOG</li>
									<li>PUG Pheonixs</li>
								</ul>
							</div>
						</div>
						<footer class="major">
							<ul class="actions">
								<li><a href="#" class="button">Register</a></li>
							</ul>
						</footer>
					</div>
				</section>

			<!-- Footer -->
				<footer id="footer">
					<ul class="icons">
						<li><a href="#" class="icon alt fa-twitter"><span class="label">Twitter</span></a></li>
						<li><a href="#" class="icon alt fa-facebook"><span class="label">Facebook</span></a></li>
						<li><a href="#" class="icon alt fa-linkedin"><span class="label">LinkedIn</span></a></li>
						<li><a href="#" class="icon alt fa-instagram"><span class="label">Instagram</span></a></li>
						<li><a href="#" class="icon alt fa-github"><span class="label">GitHub</span></a></li>
						<li><a href="#" class="icon alt fa-envelope"><span class="label">Email</span></a></li>
					</ul>
					<ul class="copyright">
						<li>&copy; Crusty Grandpa. All rights reserved.</li>
					</ul>
				</footer>

		</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/jquery.dropotron.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>
