-- ----------------------------
-- Table structure for `livestream`
-- ----------------------------
DROP TABLE IF EXISTS `livestream`;
CREATE TABLE `livestream` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `user` text NOT NULL,
  `title` text,
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;