CREATE TABLE IF NOT EXISTS `inst_likes` (
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL,
  `inst_id` text COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) collate utf8_unicode_ci NOT NULL,
  `title` varchar(255) collate utf8_unicode_ci NOT NULL,
  `photo` varchar(255) collate utf8_unicode_ci NOT NULL,
  `clicks` int(11) NOT NULL default '0',
  `today_clicks` int(11) NOT NULL DEFAULT '0',
  `max_clicks` int(11) NOT NULL DEFAULT '0',
  `daily_clicks` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL default '0',
  `cpc` int(11) NOT NULL default '2',
  `country` varchar(64) NOT NULL default '0',
  `sex` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `active` (`active`),
  KEY `cpc` (`cpc`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `inst_liked` (
  `user_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  UNIQUE KEY `unique_id` (`user_id`,`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;