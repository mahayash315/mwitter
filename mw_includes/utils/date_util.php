<?php

require_once("__includes__.php");

class DateUtil {
	private static $TIMEZONE = "Asia/Tokyo";
	private static $ISO8601 = 'Y-m-d\TH:i:s.uO';

	public static function to_mysql_format($date) {
		if (is_numeric($date)) {
			$dt = new DateTime("now", new DateTimeZone(self::$TIMEZONE));
			$dt->setTimestamp($date);
			return $dt->format("Y-m-d H:i:s");
		} else if ($date instanceof DateTime) {
			return $date->format("Y-m-d H:i:s");
		} else {
			throw new Exception("Not implemented.");
		}
	}

	public static function to_iso8601($date) {
		if (is_string($date)) {
			$dt = new DateTime($date, new DateTimeZone(self::$TIMEZONE));
			return $dt->format(self::$ISO8601);
		} else if ($date instanceof DateTime) {
			return $date->format(self::$ISO8601);
		} else {
			throw new Exception("Not implemented.");
		}
	}

	public static function from_iso8601($date) {
		if (is_string($date)) {
			$tz = (substr($date, -1) === 'Z') ? 'Z' : substr($date, -5);
			$tm = substr($date, 0, strlen($date) - strlen($tz));
			if (strlen($tm) == 19) {
				$tm .= ".000000";
			} else if (20 <= strlen($tm) && strlen($tm) < 26) {
				for ($i = strlen($tm); $i < 26; $i++) { $tm .= '0'; }
			}
			$conc = $tm.$tz;
			$dt = DateTime::createFromFormat(self::$ISO8601, $conc, new DateTimeZone(self::$TIMEZONE));
			return $dt;
		} else {
			throw new Exception("Not implemented.");
		}
	}
}



?>