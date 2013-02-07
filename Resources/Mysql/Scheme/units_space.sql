CREATE TABLE IF NOT EXISTS `hcaw_units_space` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `draft` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `image` text,
  `affiliation` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `layer` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `description` text NOT NULL,
  `cached_description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
