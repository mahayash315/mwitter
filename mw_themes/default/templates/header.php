<!-- header -->
<?php $me = mw_get_me(); ?>
<header>
	<div class="container narrow">
		<div class="row">
			<div class="col-xs-6 align-left">
				<div id="logo">
					<h1><a href="<?= DOCUMENT_PATH ?>">Mwitter</a></h1>
				</div>
			</div>
			<div class="col-xs-6 align-right">
				<?php if (!is_null($me)) { ?>
				<div id="user">
					<div class="dropdown pull-right">
						<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							<div class="icon">
								<img src="holder.js/32x32" class="img-rounded" />
							</div>
							<div class="main">
								<span class="name"><?= $me->dispName ?></span>
								<span class="caret"></span>
							</div>
						</button>
						<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
							<li><a href="#">Action</a></li>
							<li><a href="#">Another action</a></li>
							<li><a href="#">Something else here</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="<?= DOCUMENT_PATH."/welcome/logout" ?>">Logout</a></li>
						</ul>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</header>