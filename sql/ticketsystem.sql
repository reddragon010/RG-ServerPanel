# Sequel Pro dump
# Version 2492
# http://code.google.com/p/sequel-pro
#
# Host: localhost (MySQL 5.1.51)
# Database: s_website
# Generation Time: 2011-02-21 15:36:15 +0100
# ************************************************************

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table ticket_categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ticket_categories`;

CREATE TABLE `ticket_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

LOCK TABLES `ticket_categories` WRITE;
/*!40000 ALTER TABLE `ticket_categories` DISABLE KEYS */;
INSERT INTO `ticket_categories` (`id`,`name`)
VALUES
	(1,'Fehler (Klasse)'),
	(2,'Fehler (Instanz)'),
	(3,'Fehler (Welt)'),
	(4,'Fehler (Sonstiges)'),
	(5,'Account'),
	(6,'Website'),
	(7,'Beschwerde'),
	(8,'Anregung');

/*!40000 ALTER TABLE `ticket_categories` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ticket_replies
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ticket_replies`;

CREATE TABLE `ticket_replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `ticket_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table ticket_tickets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ticket_tickets`;

CREATE TABLE `ticket_tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `character_id` int(11) DEFAULT NULL,
  `realm_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;






/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
