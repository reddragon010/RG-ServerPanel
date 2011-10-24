CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DELIMITER //

CREATE TRIGGER comments_before_insert_created_at BEFORE INSERT ON comments
FOR EACH ROW
BEGIN
	SET NEW.created_at = CURRENT_TIMESTAMP;
END//

DELIMITER ;