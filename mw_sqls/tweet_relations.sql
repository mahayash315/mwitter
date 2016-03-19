-- DROP
DROP table IF EXISTS `tweet_relations`;
DROP procedure IF EXISTS `handle_tweet_insert`;
DROP procedure IF EXISTS `handle_tweet_delete`;


-- TABLE CREATION
CREATE TABLE `tweet_relations` (
	tid		INT(11) PRIMARY KEY,
	lft 	DECIMAL(21,0),
	rgt		DECIMAL(21,0)
);


-- Procedure to insert a tweet
DELIMITER //
CREATE PROCEDURE `handle_tweet_insert`(
	IN _tid INT,
	IN _parent_tid INT
)
BEGIN
	-- rollback when an exception occurres
	DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;

	-- check if the entry exists
	SELECT @c := COUNT(*) FROM `tweet_relations` WHERE tid = _tid;
	IF @c > 0 THEN
		SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = 'Entry already exists in tweet_relations table';
	END IF;

	-- begin transaction
	START TRANSACTION;

	IF _parent_tid IS NOT NULL THEN
		SELECT @myRight := rgt FROM `tweet_relations` WHERE tid = _parent_tid FOR UPDATE;
	ELSE
		SELECT @myRight := COALESCE(max(rgt) + 1, 0) FROM `tweet_relations` FOR UPDATE;
	END IF;

	UPDATE `tweet_relations` SET rgt = rgt + 2 WHERE rgt >= @myRight;
	UPDATE `tweet_relations` SET lft = lft + 2 WHERE lft >= @myRight;

	INSERT INTO `tweet_relations` (tid, lft, rgt) VALUES(_tid, @myRight, @myRight + 1);

	-- commit
	COMMIT;
END
//
DELIMITER ;


-- Procedure to delete a tweet
DELIMITER //
CREATE PROCEDURE `handle_tweet_delete`(
	IN _tid INT
)
BEGIN
	-- rollback when an exception occurres
	DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;

	-- begin transaction
	START TRANSACTION;

	SELECT @myLeft := lft, @myRight := rgt, @myWidth := rgt - lft + 1 FROM `tweet_relations` WHERE tid = _tid FOR UPDATE;

	DELETE FROM `tweet_relations` WHERE lft BETWEEN @myLeft AND @myRight;

	UPDATE `tweet_relations` SET rgt = rgt - @myWidth WHERE rgt > @myRight;
	UPDATE `tweet_relations` SET lft = lft - @myWidth WHERE lft > @myRight;

	-- commit
	COMMIT;
END
//
DELIMITER ;