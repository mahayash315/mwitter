<?php

require_once("__includes__.php");
require_once("authenticated_controller.php");

class TweetController extends AuthenticatedController {

	function __before__($action) {
		parent::__before__($action);
		
		$theme = mw_get_theme();
		define('THEME_ROOT', THEME_DIR.'/'.$theme);
		define('THEME_PATH', DOCUMENT_PATH.substr(THEME_ROOT, strlen(DOCUMENT_ROOT)));
	}

	function __default__($action) {
		if (!mw_theme_file_include($action)) {
			mw_theme_file_include("404");
		}
	}

	function getTweet() {
		mw_theme_file_include("tweet");
	}

	function postTweet() {
		$parent_tid = $_POST['parent_tid'];
		$parent = is_null($parent_tid) ? NULL : mw_get_tweet($parent_tid);
		$content = $_POST['content'];

		mw_tweet($content, $parent);

		header("Location: /".DOCUMENT_PATH."/");
	}

}


?>