<?php

define('DOCUMENT_ROOT', dirname($_SERVER['SCRIPT_FILENAME']));
define('DOCUMENT_PATH',
	substr(($documentPath = dirname($_SERVER['SCRIPT_NAME'])), -1) === '/' ?
		substr($documentPath, 0, strlen($documentPath)-1) :
		$documentPath);
define('INCLUDE_ROOT', DOCUMENT_ROOT.'/mw_includes');
define('THEME_DIR', DOCUMENT_ROOT.'/mw_themes');
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_USER', 'mwitter');
define('DB_PASS', 'mwitter');
define('DB_NAME', 'mwitter');
define('DB_CHARSET', 'utf8');

?>
