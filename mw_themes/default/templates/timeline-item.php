<?php
/**
 * The template for a timeline item
 *
 * local variables:
 *   $tweet: the tweet to show
 */
?>
<?php $replies = $tweet->getChildren(); ?>
<li class="timeline-item">
	<?php the_tweet($tweet); ?>

	<ol class="replies">
		<?php foreach ($replies as $reply) { ?>
		<li>
			<?php the_tweet($reply); ?>
		</li>
		<?php } ?>
	</ol>

	<div class="reply">
		<?php the_timeline_create(empty($replies) ? $tweet : end($replies)); ?>
	</div>
</li>