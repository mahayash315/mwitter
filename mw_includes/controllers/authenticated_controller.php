<?php

require_once("__includes__.php");

class UnauthenticatedException extends Exception { }

class AuthenticatedController extends SessionedController {

	protected $user;

	function __before__($action) {
		parent::__before__($action);

		try {
			$this->__authenticate();
		} catch (UnauthenticatedException $e) {
			$this->onUnauthenticated($action);
			exit(401);
		}
	}

	function __authenticate() {
		try {
			if (isset($_SESSION['uid'])) {
				$this->user = User::byId($_SESSION['uid']);
			} else {
				$headers = apache_request_headers();
				if (isset($headers['Authorization'])) {
					if (TextUtil::startsWith($headers['Authorization'], "Bearer ")) {
						$token = trim(substr($headers['Authorization'], strlen("Bearer ")));
						$this->user = User::byToken($token);
					} else {
						throw new Exception("Unknown Authorization header: ".$headers['Authorization']);
					}
				}
			}
		} catch (RecordNotFoundException $e) {
			throw new UnauthenticatedException("user not found with uid=".$_SESSION['uid']);
		}
		if (is_null($this->user)) {
			throw new UnauthenticatedException("user not logged in");
		}
	}

	function __getUser() {
		return $this->user;
	}

	function onUnauthenticated($action) {
		// 401 Unauthorized
		http_response_code(401);
	}

}


?>