CREATE TABLE IF NOT EXISTS `{TABLE_NAME}` (
  `meid` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(5) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `link` tinytext,
  `target` varchar(255) DEFAULT NULL,
  `sort` int(5) NOT NULL,
  PRIMARY KEY (`meid`),
  UNIQUE KEY `name` (`name`),
  KEY `mid` (`mid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
