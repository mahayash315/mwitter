<?php

require_once("__includes__.php");
require_once("authenticated_controller.php");

class DefaultController extends AuthenticatedController {

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

	function onUnauthenticated($action) {
		// welcome ページへ転送
		$url = DOCUMENT_PATH.'/welcome/logout';
		header("Location: ${url}");
	}

}


?>