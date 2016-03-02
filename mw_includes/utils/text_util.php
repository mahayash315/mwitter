<?php

require_once("__includes__.php");

class TextUtil {
	public static function camelize($str, $lcc=false) {
		$str = ucwords($str, '_');
		if ($lcc === true) {
			$str = lcfirst($str);
		}
		return str_replace('_', '', $str);
	}

	public static function snakize($str) {
		$str = preg_replace('/[a-z]+(?=[A-Z])|[A-Z]+(?=[A-Z][a-z])/', '\0_', $str);
		return strtolower($str);
	}

	public static function quote($str, $q="'") {
		return $q.$str.$q;
	}

	public static function quote_function($q="'") {
		return function ($str) use ($q) {
			return self::quote($str, $q);
		};
	}

	public static function startsWith($heystack, $needle) {
		return $needle === "" || strpos($heystack, $needle) === 0;
	}

	public static function endsWith($heystack, $needle) {
		return $needle === "" || substr($heystack, -strlen($needle)) === $needle;
	}
}


?>