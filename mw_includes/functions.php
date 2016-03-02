<?php

require_once(dirname(__FILE__)."/../mw_defs.php");
require_once("models.php");


function mw_controller_include($controller) {
	$controller = PathUtil::joinPaths(dirname($controller), 
		TextUtil::snakize(basename($controller))."_controller");
	$execfile = INCLUDE_ROOT."/controllers/${controller}.php";
	if (file_exists($execfile)) {
		include($execfile);
		return true;
	} else {
		return false;
	}
}

function mw_controller_instantiate($controller) {
	$controller = TextUtil::camelize(basename($controller))."Controller";
	if (class_exists($controller)) {
		new $controller();
		return true;
	} else {
		return false;
	}
}

function mw_get_theme() {
	return "default";
}

function mw_theme_file_include($theme) {
	$execfile = THEME_ROOT."/${theme}.php";
	if (file_exists($execfile)) {
		include($execfile);
		return true;
	} else {
		return false;
	}
}

function mw_get_me() {
	return User::me();
}

function mw_get_user($uid) {
	return User::byId($uid);
}

function mw_get_recommended_users($limit=10, $offset=0) {
	return User::me()->getRecommendedUsers($limit, $offset);
}

function mw_get_user_tweets($user, $order='DESC', $limit=50, $offset=0, $reference_time=NULL) {
	return $user->getTweets($order, $limit, $offset, $reference_time);
}

function mw_get_timeline($order='DESC', $limit=50, $offset=0, $reference_time=NULL) {
	return Tweet::timeline($order, $limit, $offset, $reference_time);
}

function mw_get_tweet($tid) {
	return Tweet::byId($tid);
}

function mw_tweet($content, $parent=NULL) {
	$user = mw_get_user();
	$tweet = Tweet::create(
		$user,
		$content,
		$parent
		);
	return $tweet->save();
}

?>