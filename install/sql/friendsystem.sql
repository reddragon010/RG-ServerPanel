-- ----------------------------
-- Table structure for `friend_token`
-- ----------------------------
DROP TABLE IF EXISTS `friend_token`;
CREATE TABLE `friend_token` (
  `token` varchar(40) NOT NULL,
  `account_id` int(11) NOT NULL,
  `friend_id` int(11) DEFAULT NULL,
  `taken` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;