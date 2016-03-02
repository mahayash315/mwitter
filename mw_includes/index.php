<?php

require_once("functions.php");

$controller = $_GET['controller'] ?: "default";

if (!mw_controller_include($controller)) {
	die("Controller could not be loaded: ${controller}.");
}
if (!mw_controller_instantiate($controller)) {
	die("Controller could not be instantiated: ${controller}.");
}
Controller::call();

?>