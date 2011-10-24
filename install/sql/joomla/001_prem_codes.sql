CREATE TABLE `jos_rgpremium_codes` (
  `userid` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `used` tinyint(3) NOT NULL,
  `for` varchar(255) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;