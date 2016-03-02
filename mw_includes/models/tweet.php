<?php

require_once("__includes__.php");

class Tweet extends Model implements JsonSerializable {
	private static $TBL = 'tweets';
	private static $TBL_REL = 'tweet_relations';

	var $tid;
	var $uid;
	var $parentTid;
	var $content;
	var $createdAt;
	var $updatedAt;

	private $user;

	function __construct(array $data) {
		parent::__construct($data);

		if (!is_null($this->uid)) {
			$this->user = User::byId($this->uid);
		}
	}

	public function jsonSerialize() {
		return [
			'tid' => $this->tid,
			'parent_tid' => $this->parentTid,
			'user' => $this->user,
			'content' => $this->content,
			'created_at' => DateUtil::to_iso8601($this->createdAt),
			'updated_at' => DateUtil::to_iso8601($this->updatedAt),
		];
	}

	public static function fromCursor($cursor) {
		return new Tweet([
			'tid' => $cursor['tid'],
			'uid' => $cursor['uid'],
			'parent_id' => $cursor['parent_id'],
			'content' => $cursor['content'],
			'created_at' => $cursor['created_at'],
			'updated_at' => $cursor['updated_at'],
			]);
	}

	public static function fromJson($json) {
		return new Tweet($json);
	}

	public static function byId($tid) {
		global $DBC;
		$stmt = $DBC->query("SELECT ".self::project(self::$PROJ_TWEET)." FROM `".self::$TBL."` WHERE `tid`=${tid}");
		if ($stmt->rowCount() == 1) {
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			return Tweet::fromCursor($result);
		} else {
			throw new RecordNotFoundException('Tweet not found with tid='.$tid);
		}
	}

	public static function create($user, $content, $parent=NULL) {
		$now = DateUtil::to_mysql_format(time());
		return new Tweet([
			'tid' => NULL,
			'uid' => $user->uid,
			'parent_tid' => isset($parent) ? $parent->tid : NULL,
			'content' => $content,
			'created_at' => $now,
			'updated_at' => $now,
			]);
	}

	public static function createFromJson($user, $json) {
		$now = DateUtil::to_mysql_format(time());
		return new Tweet(array_merge($json, [
			'uid' => $user->uid,
			'created_at' => $now,
			'updated_at' => $now,
			]));
	}

	public static function timeline($order='DESC', $limit=10, $offset=0, $reference_time=NULL) {
		$q  = "SELECT ".self::project(self::$PROJ_TWEET)." FROM `".self::$TBL."`";
		$q .= " WHERE `parent_tid` IS NULL ";
		if (!is_null($reference_time)) {
			$q .= " AND created_at > '".DateUtil::to_mysql_format($reference_time)."' ";
		}
		$q .= " ORDER BY `created_at` ${order} ";
		if (!is_null($limit)) {
			$q .= " LIMIT ${limit} ";
			if (!is_null($offset)) {
				$q .= " OFFSET ${offset} ";
			}
		}

		$tweets = [];

		global $DBC;
		$stmt = $DBC->query($q);
		foreach ($stmt as $row) {
			$tweets[] = Tweet::fromCursor($row);
		}

		return $tweets;
	}

	public static function byUid($uid, $omit_reply=true, $order='DESC', $limit=10, $offset=0, $reference_time=NULL) {
		$q  = "SELECT ".self::project(self::$PROJ_TWEET)." FROM `".self::$TBL."`";
		$q .= " WHERE `uid` = ${uid} ";
		if ($omit_reply === true) {
			$q .= " AND `parent_tid` IS NULL ";
		}
		if (!is_null($reference_time)) {
			$q .= " AND `created_at` > '".DateUtil::to_mysql_format($reference_time)."' ";
		}
		$q .= " ORDER BY `created_at` ${order} ";
		if (!is_null($limit)) {
			$q .= " LIMIT ${limit} ";
			if (!is_null($offset)) {
				$q .= " OFFSET ${offset} ";
			}
		}

		$tweets = [];

		global $DBC;
		$stmt = $DBC->query($q);
		foreach ($stmt as $row) {
			$tweets[] = Tweet::fromCursor($row);
		}

		return $tweets;
	}

	public function save() { // TODO: アノテーションで主キーを知らせて基底クラスに save() をまとめる
		global $DBC;
		$dict = $this->toContentValues();

		$q = "";
		if (!isset($this->tid)) {
			$q .= "INSERT INTO `".self::$TBL."` ";
			$q .= "(".implode(",", array_keys($dict)).")";
			$q .= " VALUES (".implode(",", array_values($dict)).")";

			// tweets 更新
			$stmt = $DBC->execute($q);
			$this->tid = $DBC->lastInsertId();

			// tweet_relations 更新
			$DBC->execute("CALL handle_tweet_insert(?,?)", [$this->tid, $this->parentTid]);
			// FIXME: 本当は insert + handle_tweet_insert() セットでトランザクションすべき
		} else {
			$q .= "UPDATE `".self::$TBL."` SET ";
			foreach ($dict as $key => $value) {
				$q .= "${key}=${value}";
			}
			$q .= " WHERE `tid`=".$this->tid;
			
			// tweets 更新
			$stmt = $DBC->execute($q);
		}

		return $this;
	}

	public function delete() {
		global $DBC;

		$DBC->execute("DELETE FROM `".self::$TBL."` WHERE `tid`=?", [$this->tid]);
		$DBC->execute("CALL handle_tweet_delete(?)", [$this->tid]);

		return $this;
	}

	public function getParent() {
		if (!$this->isRoot()) {
			return Tweet::byId($this->parentTid);
		}
		return NULL;
	}

	public function getChildren() {
		global $DBC;
		$children = [];

		$q  = "SELECT ".self::project(self::$PROJ_TWEET)." FROM";
		$q .= "	 (SELECT node.tid AS tid ";
		$q .= "   FROM `".self::$TBL_REL."` AS node, `".self::$TBL_REL."` AS parent ";
		$q .= "   WHERE node.lft BETWEEN parent.lft+1 AND parent.rgt AND parent.tid = ".$this->tid.") AS t1 ";
		$q .= " NATURAL LEFT JOIN ";
		$q .= "  `".self::$TBL."` as t2";

		$stmt = $DBC->query($q);
		foreach ($stmt as $row) {
			$children[] = Tweet::fromCursor($row);
		}

		return $children;
	}

	public function isRoot() {
		return is_null($this->parentTid);
	}

	public function isLeaf() {
		global $DBC;

		$stmt = $DBC->query("SELECT (1 = rgt-lft) AS b FROM `".self::$TBL_REL."` WHERE `tid`=".$this->tid);
		if ($stmt->rowCount() == 1) {
			$result = $stmt->fetch(FETCH_ASSOC);
			return ($result['b'] == 1);
		} else {
			throw new Exception("unknown db error");
		}
	}

	public function getUser() {
		return $this->user;
	}


	private static $PROJ_TWEET = [
		'tid',
		'uid',
		'parent_tid',
		'content',
		'created_at',
		'updated_at',
	];
}



?>