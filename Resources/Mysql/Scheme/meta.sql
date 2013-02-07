CREATE TABLE IF NOT EXISTS `hcaw_meta` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'ID for access',
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) NOT NULL COMMENT 'Text to identify in Backend',
  `content` mediumtext NOT NULL,
  `cached_content` mediumtext,
  `last_modified` bigint(20) unsigned NOT NULL,
  `created_on` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
