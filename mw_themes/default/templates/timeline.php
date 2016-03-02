<?php
/**
 * The template for timeline
 *
 * local variables:
 *   $tweets:Tweet[] the tweets to show
 *   $tweetable:boolean
 */ 
?>
<ol class="timeline">
	<?php if ($tweetable === true) { ?>
	<li id="tweet-new">
		<?php the_timeline_create(); ?>
	</li>
	<?php } ?>
	<?php foreach ($tweets as $tweet) { ?>
		<?php the_timeline_item($tweet); ?>
	<?php } ?>
</ol>
<script>
<!--
$(document).ready(function() {
	mw.onLoadTimeline();
});
//-->
</script>