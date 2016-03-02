<?php require_once(THEME_ROOT."/functions.php"); ?>
<html>
<head>
	<title>TEST</title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="<?= THEME_PATH ?>/css/style.css" />
	<script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script src="<?= THEME_PATH ?>/js/holder.min.js"></script>
	<script src="<?= THEME_PATH ?>/js/jquery.hack.js"></script>
	<script src="<?= THEME_PATH ?>/js/mwitter.js"></script>
</head>
<body>
	<?php the_header(); ?>
	<div id="main">
		<section id="welcome-cover">
			<div class="container narrow">
				<div class="row">
					<div class="col-xs-12 col-xs-offset-0 col-md-8">
						<div id="welcome-text">
							<h1 class="heading">Just a shallow copy of Twitter</h1>
							<p class="sub-heading">
							A totally stupid twitter-like web application implemented in PHP without using any f**king framework. Do not use this web site as it's still under construction.
							</p>
						</div>
					</div>
					<div class="col-xs-12 col-md-4">
						<div id="welcome-form">
							<form id="login-form" action="login" method="post">
								<div class="form-group">
									<input type="text" name="username" class="form-control" placeholder="Username">
								</div>
								<div class="form-group">
									<input type="password" name="password" class="form-control" placeholder="Password">
								</div>
								<div class="small">
									<a href="#">パスワードを忘れた場合はこちら</a>
								</div>
								<button type="submit" class="btn btn-primary btn-block">Login</button>
							</form>
							<div class="separator">
								<span>or</span>
							</div>
							<form id="signup-form" action="signup" method="get">
								<button type="submit" class="btn btn-default btn-block">Sign up</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<section id="welcome-users">
			<div class="container narrow">
				<div class="header">
					<h2>People who use Mwitter</h2>
					<p>They are just crazy.</p>
				</div>
				<div class="row">
					<div class="col-sm-6 col-md-4">
						<div class="thumbnail">
							<div class="wrapper">
								<img class="portrait" src="<?= THEME_PATH ?>/img/welcome_user_1.jpg" />
							</div>
							<div class="caption">
								<h3>Jade Cooper</h3>
								<p>"Mwitter is just.. just amazing. We used to use tools like Email, SMS, LINE, or such chatting tools, but now we use Mwitter as a replacement of them. With Mwitter, we can interact with someone unknown without any hesitation. That's something other SNS cannot realize."</p>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-4">
						<div class="thumbnail">
							<div class="wrapper">
								<img class="portrait" src="<?= THEME_PATH ?>/img/welcome_user_2.jpg" />
							</div>
							<div class="caption">
								<h3>Bobby Johnson</h3>
								<p>"When I was a kid, the Internet did not exist. Now we take its existence for granted , and some heavily rely on it. In such a society, SNS services like Twitter, Facebook, and Mwitter are definitely demanded for people, and even for pets. It's simply amazing that pets can utilize this service."</p>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-4">
						<div class="thumbnail">
							<div class="wrapper">
								<img src="<?= THEME_PATH ?>/img/welcome_user_3.jpg" />
							</div>
							<div class="caption">
								<h3>Kitty</h3>
								<p>to be interviewed and printed</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<?php the_footer(); ?>
</body>
</html>