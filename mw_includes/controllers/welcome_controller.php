<?php

require_once("__includes__.php");

class WelcomeController extends SessionedController {

	function __before__($action) {
		parent::__before__($action);

		$theme = mw_get_theme();
		define('THEME_ROOT', THEME_DIR.'/'.$theme);
		define('THEME_PATH', DOCUMENT_PATH.substr(THEME_ROOT, strlen(DOCUMENT_ROOT)));
	}

	function __default__($action) {
		$page = "welcome/".$action;
		if (!mw_theme_file_include($page)) {
			mw_theme_file_include("404");
		}
	}

	function postLogin() { // FIXME: パスワードを平文で送って平文で保存・照会するのはNG. Challenge Responseを送ってもらうよう変えるべき.
		$username = $_POST['username'] ?: NULL;
		$password = $_POST['password'] ?: NULL;
		if (is_null($username) || is_null($password)) {
			throw new Exception("Null username or password requested.");
		}

		try {
			$user = User::login($username, $password);

			// session 設定
			$_SESSION['uid'] = $user->uid;
		} catch (RecordNotFoundException $e) {
			// TODO: ログイン失敗時処理: リダイレクト？
			throw new Exception("Auth failed");
		}

		// 転送
		$url = DOCUMENT_PATH;
		header("Location: ${url}");
	}

	function logout() {
		unset($_SESSION['uid']);

		// 転送
		$url = DOCUMENT_PATH."/welcome/";
		header("Location: ${url}");
	}

	function postSignup() { // FIXME: パスワードを平文で送って平文で保存・照会するのはNG. Challenge Responseを送ってもらうよう変えるべき.
		$username = $_POST['username'] ?: NULL;
		$password = $_POST['password'] ?: NULL;
		if (is_null($username) || is_null($password)) {
			throw new Exception("Null username or password requested.");
		}

		$user = User::signup($username, $password);

		// session 設定
		$_SESSION['uid'] = $user->uid;

		// 転送
		$url = DOCUMENT_PATH;
		header("Location: ${url}");
	}


}


?>