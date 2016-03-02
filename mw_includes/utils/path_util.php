<?php

require_once('__includes__.php');

class PathUtil {
	public static function joinPaths() {
	    $paths = array();

	    foreach (func_get_args() as $arg) {
	        if ($arg !== '') { $paths[] = $arg; }
	    }

	    return preg_replace('#/+#','/',join('/', $paths));
	}
}

?>