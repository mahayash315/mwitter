<?php

require_once(dirname(__FILE__)."/../__includes__.php");

class WelcomeController extends SessionedController {

	function __before__($action) {
		parent::__before__($action);

		header("Content-Type: application/json; charset=utf-8");
	}

	function getCheckSignupUsername() {
		$username = $_GET['username'] ?: NULL;
		
		$result = false;
		if (!is_null($username)) {
			try {
				User::byUsername($username);
			} catch (RecordNotFoundException $e) {
				$result = true;
			}
		}

		echo json_encode([
			'result' => $result
			]);
	}

	function postLogin() {
		$json_string = file_get_contents('php://input');
		$json = json_decode($json_string, true);

		$username = $json['username'] ?: NULL;
		$password = $json['password'] ?: NULL;
		if (is_null($username) || is_null($password)) {
			throw new Exception("Null username or password provided.");
		}

		try {
			$user = User::login($username, $password);
			echo json_encode([
				'result' => true,
				'auth_token' => $user->getAuthToken(),
				'auth_token_expire_at' => DateUtil::to_iso8601($user->getAuthTokenExpireAt()),
				]);
		} catch (RecordNotFoundException $e) {
			echo json_encode([
				'result' => false,
				'auth_token' => NULL,
				'auth_token_expire_at' => NULL,
				]);
		}
	}

}

?>