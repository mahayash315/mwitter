-- DROP
DROP TABLE IF EXISTS `users`;


-- TABLE CREATION
CREATE TABLE `users` (
  `uid`			INT(16) PRIMARY KEY AUTO_INCREMENT,
  `first_name`	VARCHAR(45) NULL,
  `last_name`	VARCHAR(45) NULL,
  `disp_name`	VARCHAR(45) NULL,
  `created_at`	DATETIME NOT NULL,
  `updated_at`  DATETIME NOT NULL
);
