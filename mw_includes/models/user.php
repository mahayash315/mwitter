<?php

require_once("__includes__.php");
require_once(dirname(__FILE__)."/../controllers/controller.php");
require_once(dirname(__FILE__)."/../controllers/authenticated_controller.php");

class User extends Model implements JsonSerializable {
	private static $TBL = 'users';
	private static $TBL_AUTH = 'auths';

	var $uid;
	var $firstName;
	var $lastName;
	var $dispName;
	var $createdAt;
	var $updatedAt;

	private $authRetrieved = false;
	private $authUsername;
	private $authToken;
	private $authTokenExpireAt;

	private function retrieveAuth() {
		if (!$this->authRetrieved && !is_null($this->uid)) {
			$uid = $this->uid;

			global $DBC;
			$stmt = $DBC->query("SELECT ".self::project(self::$PROJ_AUTH)." FROM `".self::$TBL_AUTH."` WHERE `uid`=${uid}");
			if ($stmt->rowCount() == 1) {
				$cursor = $stmt->fetch(PDO::FETCH_ASSOC);
				$this->authUsername = $cursor['username'];
				$this->authToken = $cursor['token'];
				$this->authTokenExpireAt = $cursor['token_expire_at']; // FIXME: もうちょっとうまい実装できないのかな
			} else {
				throw new RecordNotFoundException('Auth not found with uid='.$uid);
			}

			$this->authRetrieved = true;
		}
	}

	public function jsonSerialize() {
		return [
			'uid' => $this->uid,
			'first_name' => $this->firstName,
			'last_name' => $this->lastName,
			'disp_name' => $this->dispName,
			'created_at' => DateUtil::to_iso8601($this->createdAt),
			'updated_at' => DateUtil::to_iso8601($this->updatedAt),
		];
	}

	public static function fromCursor($cursor) {
		return new User([
			'uid' => $cursor['uid'],
			'first_name' => $cursor['first_name'],
			'last_name' => $cursor['last_name'],
			'disp_name' => $cursor['disp_name'],
			'created_at' => $cursor['created_at'],
			'updated_at' => $cursor['updated_at'],
			]);
	}

	public static function byId($uid) {
		global $DBC;
		$stmt = $DBC->query("SELECT ".self::project(self::$PROJ_USER)." FROM `".self::$TBL."` WHERE `uid`=${uid}");
		if ($stmt->rowCount() == 1) {
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			return self::fromCursor($result);
		} else {
			throw new RecordNotFoundException('User not found with uid='.$uid);
		}
	}

	public static function byUsername($username) {
		global $DBC;
		$stmt = $DBC->query("SELECT `uid` FROM `".self::$TBL_AUTH."` WHERE `username`='${username}'");
		if ($stmt->rowCount() == 1) {
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			return self::byId($result['uid']);
		} else {
			throw new RecordNotFoundException('User not found with username="'.$username.'", password="'.$password.'"');
		}
	}

	public static function byToken($token) {
		global $DBC;
		$stmt = $DBC->query("SELECT `uid` FROM `".self::$TBL_AUTH."` WHERE `token`='${token}' AND `token_expire_at` > now()");
		if ($stmt->rowCount() == 1) {
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			return self::byId($result['uid']);
		} else {
			throw new RecordNotFoundException('User not found with username="'.$username.'", password="'.$password.'"');
		}
	}

	public static function userExists($username) {
		try {
			self::byUsername($username);
			return true;
		} catch (RecordNotFoundException $e) {
			return false;
		}
	}

	public static function me() {
		$controller = Controller::getInstance();
		if ($controller instanceof AuthenticatedController) {
			return $controller->__getUser();
		}
		return NULL;
	}

	public static function login($username, $password) {
		global $DBC;
		$stmt = $DBC->query("SELECT `uid`,`token`,`token_expire_at` < now() AS `token_expired` FROM `".self::$TBL_AUTH."` WHERE `username`='${username}' AND `password`='${password}'");
		if ($stmt->rowCount() == 1) {
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$uid = $result['uid'];

			// token 更新
			if (is_null($result['token']) || $result['token_expired'] === 1) {
				$token = UUID::v4();
				$tokenExpireAt = DateUtil::to_mysql_format(time() + (24*60*60)); // FIXME: hard coding

				$DBC->query("UPDATE `".self::$TBL_AUTH."` SET `token`='${token}', `token_expire_at`='${tokenExpireAt}' WHERE `uid`=${uid}");
			}

			return self::byId($uid);
		} else {
			throw new RecordNotFoundException('User not found with username="'.$username.'", password="'.$password.'"');
		}
	}

	public static function signup($username, $password) {
		if (self::userExists($username)) {
			throw new Exception("User ${username} already exists.");
		}

		global $DBC;
		$DBC->beginTransaction();
		try {
			$now = DateUtil::to_mysql_format(time());
			$user = new User([
				'disp_name' => $username,
				'created_at' => $now,
				'updated_at' => $now,
				]);
			$user->save();
			$uid = $user->uid;

			// FIXME: 本当は Auth モデルを作った方がいい
			$sql = "INSERT INTO `".self::$TBL_AUTH."` (uid,username,password,created_at,updated_at) VALUES (${uid}, '${username}', '${password}', '${now}', '${now}')";
			$DBC->execute($sql);

			$DBC->commit();
		} catch (PDOException $e) {
			$DBC->rollBack();
			throw $e;
		}

		return $user;
	}

	public function save() { // TODO: アノテーションで主キーを知らせて基底クラスに save() をまとめる
		global $DBC;
		$dict = $this->toContentValues();

		$q = "";
		if (!isset($this->uid)) {
			$q .= "INSERT INTO `".self::$TBL."` ";
			$q .= "(".implode(",", array_keys($dict)).")";
			$q .= " VALUES (".implode(",", array_values($dict)).")";

			// users 更新
			$stmt = $DBC->execute($q);
			$this->uid = $DBC->lastInsertId();
		} else {
			$q .= "UPDATE `".self::$TBL."` SET ";
			foreach ($dict as $key => $value) {
				$q .= "${key}=${value}";
			}
			$q .= " WHERE `uid`=".$this->uid;
			
			// users 更新
			$stmt = $DBC->execute($q);
		}

		return $this;
	}

	public function getAuthUsername() {
		$this->retrieveAuth();
		return $this->authUsername;
	}

	public function getAuthToken() {
		$this->retrieveAuth();
		return $this->authToken;
	}

	public function getAuthTokenExpireAt() {
		$this->retrieveAuth();
		return $this->authTokenExpireAt;
	}

	public function getTweets($omit_reply=true, $order='DESC', $limit=10, $offset=0, $reference_time=NULL) {
		return Tweet::byUid($this->uid, $omit_reply, $order, $limit, $offset, $reference_time);
	}

	public function getRecommendedUsers($limit=10, $offset=0) {
		global $DBC;

		$q  = "SELECT ".self::project(self::$PROJ_USER)." FROM `".self::$TBL."`";
		$q .= " ORDER BY `created_at` DESC ";
		if (0 < $limit) {
			$q .= " LIMIT ${limit} ";
			if (0 < $offset) {
				$q .= " OFFSET ${offset} ";
			}
		}

		$users = [];
		$stmt = $DBC->query($q);
		foreach ($stmt as $row) {
			$users[] = self::fromCursor($row);
		}

		return $users;
	}

	public function isMe() {
		$me = User::me();
		return ($this->uid == $me->uid);
	}


	private static $PROJ_USER = [
		'uid',
		'first_name',
		'last_name',
		'disp_name',
		'created_at',
		'updated_at',
	];
	private static $PROJ_UID = [
		'uid',
	];
	private static $PROJ_AUTH = [
		'uid',
		'username',
		'password',
		'token',
		'token_expire_at',
		'created_at',
		'updated_at',
	];
}



?>