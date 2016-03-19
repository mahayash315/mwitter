-- DROP
DROP TABLE IF EXISTS `auths`;


-- TABLE CREATION
CREATE TABLE `auths` (
  `uid`				INT(16) PRIMARY KEY,
  `username`		VARCHAR(45) NOT NULL,
  `password`		TEXT NOT NULL,
  `token`			VARCHAR(36) NOT NULL,
  `token_expire_at`	DATETIME NOT NULL,
  `created_at`		DATETIME NOT NULL,
  `updated_at`  	DATETIME NOT NULL
);
