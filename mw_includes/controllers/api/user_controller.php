<?php

require_once(dirname(__FILE__)."/../__includes__.php");
require_once(dirname(__FILE__)."/../authenticated_controller.php");

class UserController extends AuthenticatedController {

	function __before__($action) {
		parent::__before__($action);

		header("Content-Type: application/json; charset=utf-8");
	}

	function getIndex() {
		$uid = $_GET['uid'] ?: NULL;
		if (is_null($uid)) {
			echo "{}";
			return;
		}
		
		$user = User::byId($uid);
		if (is_null($user)) {
			throw new Exception("User not found, uid=".$uid);
		}

		echo json_encode($user);
	}

	function getMe() {
		$me = User::me();

		if (is_null($me)) {
			throw new Exception("null me");
		}

		echo json_encode($me);
	}

	function getTweets() {
		$uid = $_GET['uid'] ?: NULL;
		$omit_reply = $_GET['omit_reply'] ?: true;
		if (is_null($uid)) {
			echo "{}";
			return;
		}
		
		$user = User::byId($uid);
		if (is_null($user)) {
			throw new Exception("User not found, uid=".$uid);
		}

		$tweets = $user->getTweets($omit_reply);
		echo json_encode($tweets);
	}

}

?>