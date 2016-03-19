-- DROP
DROP procedure IF EXISTS `debug_msg`;


-- DEBUG
CREATE PROCEDURE debug_msg(msg VARCHAR(255))
BEGIN
	SET @enabled = TRUE;

	IF @enabled THEN BEGIN
		select concat("** ", msg) AS '** DEBUG:';
	END; END IF;
END $$