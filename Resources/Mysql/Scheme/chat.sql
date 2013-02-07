CREATE TABLE IF NOT EXISTS `{TABLE_NAME}` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `draft` tinyint(1) NOT NULL DEFAULT '0',
  `time` int(15) unsigned NOT NULL,
  `name` int(11) unsigned NOT NULL,
  `text` text NOT NULL,
  `cached_text` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
