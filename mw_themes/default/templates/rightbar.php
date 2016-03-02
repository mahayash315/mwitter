<?php
/**
 * The template for rightbar
 *
 * local variables:
 *   $recommendedUsers:User[]
 */ 
?>
<div class="rightbar">
	<div class="recommended-users">
		<div class="panel panel-default">
			<div class="panel-body">
				<h4>おすすめユーザ</h4>
				<div class="list-group">
					<?php foreach ($recommendedUsers as $user) { ?>
					<a href="<?= DOCUMENT_PATH."/user?uid=".$user->uid ?>" class="list-group-item recommended-user">
						<div class="icon">
							<img src="holder.js/48x48" />
						</div>
						<div class="name">
							<span><?= $user->dispName ?></span>
						</div>
					</a>
					<?php } ?>
				</div>
			</div>
			<div class="panel-footer">
			</div>
		</div>
	</div>
</div>