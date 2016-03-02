<?php
/**
 * The template for a timeline item
 *
 * local variables:
 *   $tweet: the tweet to show
 */
?>
<?php $user = $tweet->getUser(); ?>
<div id="tweet-<?= $tweet->tid ?>" class="tweet">
	<div class="media">
		<div class="media-left icon">
			<img src="holder.js/48x48" class="img-rounded" />
		</div>
		<div class="media-body main">
			<div class="head">
				<span class="disp_name"><?= $user->dispName ?></span>
				<span class=""></span>
				<span class="created_at"><?= $tweet->createdAt ?></span>
			</div>
			<div class="body">
				<?php if ($is_retweet) { ?>
				<blockquote>
				<?php } ?>
				<p><?= $tweet->content ?></p>
				<?php if ($is_retweet) { ?>
				<footer>someone</footer>
				</blockquote>
				<?php } ?>
			</div>
		</div>
	</div>
</div>