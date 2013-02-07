CREATE TABLE IF NOT EXISTS `hcaw_staff` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `draft` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `position` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `cached_description` text,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
