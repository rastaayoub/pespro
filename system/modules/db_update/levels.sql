CREATE TABLE IF NOT EXISTS `levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` int(11) NOT NULL DEFAULT '0',
  `requirements` int(11) NOT NULL DEFAULT '0',
  `free_bonus` int(11) NOT NULL DEFAULT '0',
  `vip_bonus` int(11) NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `requirements` (`requirements`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

INSERT IGNORE INTO `levels` (`id`, `level`, `requirements`, `free_bonus`, `vip_bonus`, `image`) VALUES
(1, 1, 0, 40, 80, 'files/levels/Level_1.png'),
(2, 2, 500, 50, 100, 'files/levels/Level_2.png'),
(3, 3, 2000, 60, 120, 'files/levels/Level_3.png'),
(4, 4, 5000, 70, 140, 'files/levels/Level_4.png'),
(5, 5, 10000, 85, 170, 'files/levels/Level_5.png'),
(6, 6, 20000, 100, 200, 'files/levels/Level_6.png'),
(7, 7, 35000, 125, 250, 'files/levels/Level_7.png'),
(8, 8, 60000, 160, 320, 'files/levels/Level_8.png'),
(9, 9, 100000, 200, 400, 'files/levels/Level_9.png');