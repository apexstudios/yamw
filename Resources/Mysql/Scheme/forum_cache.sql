CREATE TABLE IF NOT EXISTS `{TABLE_NAME}` (
  `id` bigint(20) NOT NULL,
  `cached_content` mediumtext,
  `previewtext` text NOT NULL,
  `last_modified` int(11) NOT NULL,
  UNIQUE KEY `pid` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
