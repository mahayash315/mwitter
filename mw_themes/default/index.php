<?php require_once(THEME_ROOT."/functions.php"); ?>
<html>
<head>
	<title>TEST</title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo THEME_PATH; ?>/css/style.css" />
	<script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script src="<?php echo THEME_PATH; ?>/js/holder.min.js"></script>
	<script src="<?php echo THEME_PATH; ?>/js/jquery.hack.js"></script>
	<script src="<?php echo THEME_PATH; ?>/js/mwitter.js"></script>
</head>
<body>
	<?php the_header(); ?>
	<div id="main" class="container narrow">
		<section id="timeline">
			<div class="row">
				<div class="col-md-8 col-md-push-4 col-lg-6 col-lg-push-3">
					<?php the_timeline(); ?>
				</div>
				<div class="col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-6 hidden-xs hidden-sm">
					<?php the_leftbar(); ?>
				</div>
				<div class="col-lg-3 hidden-xs hidden-sm hidden-md">
					<?php the_rightbar(); ?>
				</div>
			</div>
		</section>
	</div>
	<?php the_footer(); ?>
</body>
</html>