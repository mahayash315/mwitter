<?php

require_once(dirname(__FILE__)."/../dbc.php");
require_once(dirname(__FILE__)."/../utils.php");

class RecordNotFoundException extends Exception { }

class Model {

	protected static function project($columnNames) {
		return join(',', array_map(TextUtil::quote_function('`'), $columnNames));
	}

	function __construct(array $data) {
        foreach($data as $key => $val) {
        	$camelKey = TextUtil::camelize($key, true);
            if(property_exists(get_class($this), $camelKey)) {
                $this->$camelKey = $val;
            }
        }
	}

	public function save() {
		// TODO: アノテーションで主キーを知らせて基底クラスに save() をまとめる
		throw new Exception("not implemented");
	}

	protected function toContentValues() {
		global $DBC;

		$dict = [];
		foreach(get_object_vars($this) as $key => $val) {
			$snakeKey = TextUtil::snakize($key);
			if (!is_null($val)) {
				if(is_string($val)) {
					$dict[$snakeKey] = $DBC->quote($val);
				} else {
					$dict[$snakeKey] = $val;
				}
			}
		}

		return $dict;
	}

}

?>