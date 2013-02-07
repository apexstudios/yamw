CREATE TABLE IF NOT EXISTS `{TABLE_NAME}` (
  `mid` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `menuname` varchar(255) NOT NULL,
  PRIMARY KEY (`mid`),
  UNIQUE KEY `menuname` (`menuname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9
