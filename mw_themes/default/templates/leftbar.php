<?php
/**
 * The template for leftbar
 *
 * local variables:
 *   $user: User
 */ 
?>
<div class="leftbar">
	<div class="me">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="cover-image">
					<img src="holder.js/270x80" />
				</div>
				<div class="body">
					<div class="icon">
						<img src="holder.js/64x64" class="img-rounded" />
					</div>
					<div class="info">
						<div class="disp-name"><?= $user->dispName ?></div>
						<div class="real-name"><?= $user->firstName ?>&nbsp;<?= $user->lastName ?></div>
					</div>
				</div>
				<div class="tail">
					<ul class="nav nav-pills nav-justified">
						<li role="presentation"><a href="">#</a></li>
						<li role="presentation"><a href="">#</a></li>
						<li role="presentation"><a href="">#</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="trends">
		<div class="panel panel-default">
			<div class="panel-body">
				trends
			</div>
		</div>
	</div>
</div>