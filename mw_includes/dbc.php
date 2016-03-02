<?php

require_once(dirname(__FILE__)."/../mw_defs.php");

class DBC {
	protected $pdo;

	function DBC() {
		// PDO インスタンス生成
		$this->pdo = new PDO(
			DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET,
			DB_USER,
			DB_PASS,
			array(
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_EMULATE_PREPARES => false,
			)
		);
	}

	/**
	 * クエリする
	 */
	public function query($q) {
		// var_dump($q);
		return $this->pdo->query($q);
	}

	public function execute($q, $args=array()) {
		$stmt = $this->pdo->prepare($q);
		// var_dump($stmt); var_dump($args);
		return $stmt->execute($args);
	}

	public function lastInsertId($name=NULL) {
		return $this->pdo->lastInsertId($name);
	}

	public function quote($str) {
		return $this->pdo->quote($str);
	}

	public function beginTransaction() {
		$this->pdo->beginTransaction();
	}

	public function commit() {
		$this->pdo->commit();
	}

	public function rollBack() {
		$this->pdo->rollBack();
	}
}

$DBC = new DBC();

?>