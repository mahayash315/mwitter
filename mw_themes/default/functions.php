<?php

function the_header() {
	include(THEME_ROOT.'/templates/header.php');
}

function the_footer() {
	include(THEME_ROOT.'/templates/footer.php');
}

function the_timeline() {
	$tweets = mw_get_timeline();
	$tweetable = true;
	include(THEME_ROOT.'/templates/timeline.php');
}

function the_timeline_item($tweet) {
	include(THEME_ROOT.'/templates/timeline-item.php');
}

function the_timeline_create($parent=NULL) {
	include(THEME_ROOT.'/templates/timeline-create.php');
}

function the_user($user) {
	$tweets = $user->getTweets();
	$tweetable = false;
	include(THEME_ROOT.'/templates/timeline.php');
}

function the_leftbar($user=NULL) {
	if (is_null($user)) {
		$user = mw_get_me();
	}
	include(THEME_ROOT.'/templates/leftbar.php');
}

function the_rightbar() {
	$recommendedUsers = mw_get_recommended_users();
	include(THEME_ROOT.'/templates/rightbar.php');
}

function the_tweet($tweet) {
	include(THEME_ROOT.'/templates/tweet.php');
}


?>