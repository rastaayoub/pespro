CREATE TABLE IF NOT EXISTS `fb_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `clicks` int(11) NOT NULL DEFAULT '0',
  `today_clicks` int(11) NOT NULL DEFAULT '0',
  `max_clicks` int(11) NOT NULL DEFAULT '0',
  `daily_clicks` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `cpc` int(11) NOT NULL DEFAULT '2',
  `country` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `sex` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `fb_shared` (
  `user_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  UNIQUE KEY `unique_id` (`user_id`,`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;