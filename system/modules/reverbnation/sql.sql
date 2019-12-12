CREATE TABLE IF NOT EXISTS `reverbnation` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user` int(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `artist_id` int(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `clicks` int(255) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `cpc` int(11) NOT NULL DEFAULT '2',
  `country` varchar(64) NOT NULL DEFAULT '0',
  `sex` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `reverbnation_done` (
  `user_id` int(255) NOT NULL,
  `site_id` int(255) NOT NULL,
  UNIQUE KEY `unique_id` (`user_id`,`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `reverbnation_accounts` (
  `user` int(255) NOT NULL DEFAULT '0',
  `account_name` varchar(255) NOT NULL,
  `account_username` varchar(255) NOT NULL,
  KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;