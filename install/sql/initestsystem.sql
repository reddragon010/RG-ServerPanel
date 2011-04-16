-- ----------------------------
-- Table structure for `bosses`
-- ----------------------------

DROP TABLE IF EXISTS `bosses`;

CREATE TABLE `bosses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `instance_id` int(11) NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `icon` varchar(255) DEFAULT NULL,
  `test_start` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `test_end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

LOCK TABLES `bosses` WRITE;
/*!40000 ALTER TABLE `bosses` DISABLE KEYS */;
INSERT INTO `bosses` (`id`,`name`,`Instance_id`,`status`,`icon`,`test_start`,`test_end`,`comment`)
VALUES
	(1,'Flame Leviathan',1,1,'','0000-00-00 00:00:00','0000-00-00 00:00:00',''),
	(2,'Ignis the Furnace Master',1,2,NULL,'2011-02-17 00:00:00','2011-02-20 00:00:00',''),
	(3,'Razorscale',1,2,NULL,'2011-02-21 00:00:00','2011-02-28 00:00:00','Test3'),
	(4,'XT-002 Deconstructor',1,0,NULL,'0000-00-00 00:00:00','2011-02-17 05:01:37',''),
	(5,'The Assembly of Iron',1,0,NULL,'0000-00-00 00:00:00','2011-02-17 05:01:37',''),
	(6,'Kologarn',1,0,NULL,'0000-00-00 00:00:00','2011-02-17 05:01:37',''),
	(7,'Auriaya',1,0,NULL,'0000-00-00 00:00:00','2011-02-17 05:01:37',NULL),
	(8,'Mimiron',1,0,NULL,'0000-00-00 00:00:00','2011-02-17 05:01:37',NULL),
	(9,'Freya',1,0,NULL,'0000-00-00 00:00:00','2011-02-17 05:01:37',NULL),
	(10,'Thorim',1,0,NULL,'0000-00-00 00:00:00','2011-02-17 05:01:37',NULL),
	(11,'Hodir',1,0,NULL,'0000-00-00 00:00:00','2011-02-17 05:01:37',NULL),
	(12,'General Vezax',1,0,NULL,'0000-00-00 00:00:00','2011-02-17 05:01:37',NULL),
	(13,'Yogg-Saron',1,0,NULL,'0000-00-00 00:00:00','2011-02-17 05:01:37',NULL),
	(14,'Algalon the Observer',1,0,NULL,'0000-00-00 00:00:00','2011-02-17 05:01:37',NULL);

/*!40000 ALTER TABLE `bosses` ENABLE KEYS */;
UNLOCK TABLES;

-- ----------------------------
-- Table structure for `instances`
-- ----------------------------

DROP TABLE IF EXISTS `instances`;

CREATE TABLE `instances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

LOCK TABLES `instances` WRITE;
/*!40000 ALTER TABLE `instances` DISABLE KEYS */;
INSERT INTO `instances` (`id`,`name`,`icon`)
VALUES
	(1,'Ulduar',NULL),
	(2,'Eye Of Eternity',NULL);

/*!40000 ALTER TABLE `instances` ENABLE KEYS */;
UNLOCK TABLES;