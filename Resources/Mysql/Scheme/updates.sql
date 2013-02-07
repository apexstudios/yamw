CREATE TABLE IF NOT EXISTS `hcaw_updates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `draft` tinyint(1) NOT NULL DEFAULT '0',
  `date` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `participators` text,
  `text` mediumtext NOT NULL,
  `cached_text` mediumtext,
  `desc` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
