<?php

class Controller {
	private static $sInstance;

	public static function call() {
		if (is_null(self::$sInstance)) {
			throw new Exception("Controller not loaded.");
		}
		$controller = self::$sInstance;

		$method = strtolower($_SERVER['REQUEST_METHOD']);
		$action = $_GET['action'] ?: "index";
		$calls = array(
			TextUtil::camelize("${method}${action}") => array(),
			TextUtil::camelize("${action}") => array(),
			"__default__" => array($action),
			);

		$controller->__before__($action);
		foreach ($calls as $name => $args) {
			if (is_callable(array($controller, $name))) {
				call_user_func_array(array($controller, $name), $args);
				break;
			}
		}
		$controller->__after__($action);
	}

	public static function getInstance() {
		return self::$sInstance;
	}

	function __construct() {
		self::$sInstance = $this;
	}

	function __before__($action) {

	}

	function __default__($action) {
		throw new Exception("action not found: ${action}");
	}

	function __after__($action) {
		
	}
}

class SessionedController extends Controller { // TODO: __before__() から __after__() まで SESSION 確立するのは非効率. SESSION の保持は必要最小限の時間にとどめるべき.

	function __before__($action) {
		parent::__before__($action);
		$this->__start_session();
	}

	function __after__($action) {
		parent::__after__($action);
		$this->__end_session();
	}

	function __start_session() {
		session_start();
	}

	function __end_session() {
		session_write_close();
	}

}

?>