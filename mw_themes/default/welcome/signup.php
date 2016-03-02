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
		<section id="signup">
			<div class="container narrow">
				<div class="row">
					<div class="col-sm-6 col-sm-offset-3 col-md-5 col-md-offset-35 col-lg-5 col-lg-offset-45">
						<div id="signup-wrap">
							<div class="header">
								<h2>Create your account</h2>
								<p>right now.</p>
							</div>
							<div id="signup-form">
								<form action="signup" method="post">
									<div class="textbox">
										<div class="form-group has-feedback">
											<input class="form-control input-lg" type="text" name="username" placeholder="Username" aria-describedby="helpBlock1" />
											<span class="status-ok glyphicon glyphicon-ok form-control-feedback" aria-hidden="true" style="display: none;"></span>
											<span class="status-ng glyphicon glyphicon-remove form-control-feedback" aria-hidden="true" style="display: none;"></span>
											<span id="helpBlock1" class="help-block"></span>
										</div>
										<div class="form-group">
											<input class="form-control input-lg" type="password" name="password" placeholder="Password" aria-describedby="helpBlock2" />
											<span id="helpBlock2" class="help-block">Use at least one letter or numeral.</span>
										</div>
									</div>
									<div class="go">
										<button type="submit" class="btn btn-primary btn-block" disabled>Sign up for Mwitter</button>
									</div>
									<div class="befound">
										<p>By clicking "Sign up for Mwitter", you agree to our <a href="#">terms of service</a> and <a href="#">privacy policy</a>. We will send you account related emails occasionally.</p>
									</div>
								</form>
							</div>
						</div>	
<script>
<!--
$(document).ready(function() {
	mw.onLoadSignup();
});
//-->
</script>
					</div>
				</div>
			</div>
		</section>
	</div>
	<?php the_footer(); ?>
</body>
</html>