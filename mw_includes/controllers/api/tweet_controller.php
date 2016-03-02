<?php

require_once(dirname(__FILE__)."/../__includes__.php");
require_once(dirname(__FILE__)."/../authenticated_controller.php");

class TweetController extends AuthenticatedController {

	function __before__($action) {
		parent::__before__($action);
		
		header("Content-Type: application/json; charset=utf-8");
	}

	function index() {
		echo "{}";
	}

	function getTimeline() {
		$order = $_GET['order'] ?: 'DESC';
		$limit = $_GET['limit'] ?: NULL;
		$offset = $_GET['offset'] ?: 0;
		$reference_time = $_GET['reference_time'] ?: NULL;
		if ($reference_time != NULL) {
			$reference_time = DateUtil::from_iso8601($reference_time);
		}
		$timeline = Tweet::timeline($order, $limit, $offset, $reference_time);

		echo json_encode($timeline);
	}

	function getTweet() {
		$tid = $_GET['tid'] ?: NULL;
		$tweet = Tweet::byId($tid);

		echo json_encode($tweet);
	}

	function postTweet() {
		$json_string = file_get_contents('php://input');
		$json = json_decode($json_string, true);

		$tweet = Tweet::createFromJson(User::me(), $json);
		$tweet->save();

		echo json_encode($tweet);
	}

}

?>