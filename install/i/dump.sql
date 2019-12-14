--
-- Table structure for table `activity_rewards`
--

CREATE TABLE IF NOT EXISTS `activity_rewards` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `exchanges` int(255) NOT NULL DEFAULT '0',
  `reward` int(255) NOT NULL DEFAULT '0',
  `type` int(32) NOT NULL DEFAULT '0',
  `claims` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_rewards_claims`
--

CREATE TABLE IF NOT EXISTS `activity_rewards_claims` (
  `reward_id` int(255) NOT NULL DEFAULT '0',
  `user_id` int(255) NOT NULL DEFAULT '0',
  `date` int(255) NOT NULL DEFAULT '0',
  KEY `reward_id` (`reward_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ad_packs`
--

CREATE TABLE IF NOT EXISTS `ad_packs` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `days` int(255) NOT NULL DEFAULT '0',
  `bought` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `ad_packs` (`id`, `price`, `days`, `bought`) VALUES
(1, '1.00', 7, 1),
(2, '2.00', 15, 0),
(3, '3.50', 30, 0);

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE IF NOT EXISTS `banners` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user` int(255) NOT NULL DEFAULT '0',
  `banner_url` varchar(255) NOT NULL,
  `site_url` varchar(255) NOT NULL,
  `views` int(255) NOT NULL DEFAULT '0',
  `clicks` int(255) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `expiration` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ban_reasons`
--

CREATE TABLE IF NOT EXISTS `ban_reasons` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user` int(255) NOT NULL DEFAULT '0',
  `reason` text NOT NULL,
  `date` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `blacklist`
--

CREATE TABLE IF NOT EXISTS `blacklist` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(32) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `value` (`value`,`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `author` int(255) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `views` int(255) NOT NULL DEFAULT '0',
  `timestamp` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_comments`
--

CREATE TABLE IF NOT EXISTS `blog_comments` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `bid` int(255) NOT NULL DEFAULT '0',
  `author` int(255) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `timestamp` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `bid` (`bid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `coins_to_cash`
--

CREATE TABLE IF NOT EXISTS `coins_to_cash` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `coins` int(255) NOT NULL DEFAULT '0',
  `cash` decimal(10,2) NOT NULL DEFAULT '0.00',
  `conv_rate` int(64) NOT NULL DEFAULT '0',
  `date` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE IF NOT EXISTS `coupons` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `coins` int(255) NOT NULL DEFAULT '0',
  `uses` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `used` int(255) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `exchanges` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `c_pack`
--

CREATE TABLE IF NOT EXISTS `c_pack` (
  `id` int(255) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `coins` int(255) NOT NULL default '0',
  `price` decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `c_transfers`
--

CREATE TABLE IF NOT EXISTS `c_transfers` (
  `id` int(255) NOT NULL auto_increment,
  `receiver` int(255) NOT NULL default '0',
  `sender` varchar(255) collate utf8_unicode_ci NOT NULL,
  `coins` int(255) NOT NULL default '0',
  `date` int(255) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE IF NOT EXISTS `faq` (
  `id` int(255) NOT NULL auto_increment,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `followed`
--

CREATE TABLE IF NOT EXISTS `followed` (
  `user_id` int(255) NOT NULL,
  `site_id` int(255) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `google`
--

CREATE TABLE IF NOT EXISTS `google` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(255) NOT NULL DEFAULT '0',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `clicks` int(255) NOT NULL DEFAULT '0',
  `today_clicks` int(255) NOT NULL DEFAULT '0',
  `max_clicks` int(255) NOT NULL DEFAULT '0',
  `daily_clicks` int(255) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `cpc` int(11) NOT NULL DEFAULT '1',
  `country` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `sex` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `linkedin`
--

CREATE TABLE IF NOT EXISTS `linkedin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(255) NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `clicks` int(255) NOT NULL DEFAULT '0',
  `today_clicks` int(255) NOT NULL DEFAULT '0',
  `max_clicks` int(255) NOT NULL DEFAULT '0',
  `daily_clicks` int(255) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `cpc` int(11) NOT NULL DEFAULT '2',
  `country` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `sex` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `linked_done`
--

CREATE TABLE IF NOT EXISTS `linked_done` (
  `user_id` int(255) NOT NULL,
  `site_id` int(255) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `list_countries`
--

CREATE TABLE IF NOT EXISTS `list_countries` (
  `id` int(11) NOT NULL auto_increment,
  `country` varchar(255) NOT NULL default '',
  `code` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

INSERT INTO `list_countries` (`id`, `country`, `code`) VALUES
(1, 'United States', 'US'),
(2, 'United Kingdom', 'UK'),
(3, 'Norway', 'NO'),
(4, 'Greece', 'GR'),
(5, 'Afghanistan', 'AF'),
(6, 'Albania', 'AL'),
(7, 'Algeria', 'DZ'),
(8, 'American Samoa', 'AS'),
(9, 'Andorra', 'AD'),
(10, 'Angola', 'AO'),
(11, 'Anguilla', 'AI'),
(12, 'Antigua & Barbuda', 'AG'),
(13, 'Antilles, Netherlands', 'AN'),
(182, 'Senegal', 'SN'),
(15, 'Argentina', 'AR'),
(16, 'Armenia', 'AM'),
(17, 'Aruba', 'AW'),
(18, 'Australia', 'AU'),
(19, 'Austria', 'AT'),
(20, 'Azerbaijan', 'AZ'),
(21, 'Bahamas, The', 'BS'),
(22, 'Bahrain', 'BH'),
(23, 'Bangladesh', 'BD'),
(24, 'Barbados', 'BB'),
(25, 'Belarus', 'BY'),
(26, 'Belgium', 'BE'),
(27, 'Belize', 'BZ'),
(28, 'Benin', 'BJ'),
(29, 'Bermuda', 'BM'),
(30, 'Bhutan', 'BT'),
(31, 'Bolivia', 'BO'),
(32, 'Bosnia and Herzegovina', 'BA'),
(33, 'Botswana', 'BW'),
(34, 'Brazil', 'BR'),
(35, 'British Virgin Islands', 'VG'),
(36, 'Brunei Darussalam', 'BN'),
(37, 'Bulgaria', 'BG'),
(38, 'Burkina Faso', 'BF'),
(39, 'Burundi', 'BI'),
(40, 'Cambodia', 'KH'),
(41, 'Cameroon', 'CM'),
(42, 'Canada', 'CA'),
(43, 'Cape Verde', 'CV'),
(44, 'Cayman Islands', 'KY'),
(45, 'Central African Republic', 'CF'),
(46, 'Chad', 'TD'),
(47, 'Chile', 'CL'),
(48, 'China', 'CN'),
(49, 'Colombia', 'CO'),
(50, 'Comoros', 'KM'),
(51, 'Congo', 'CG'),
(52, 'Congo', 'CD'),
(53, 'Cook Islands', 'CK'),
(54, 'Costa Rica', 'CR'),
(55, 'Cote D''Ivoire', 'CI'),
(56, 'Croatia', 'HR'),
(57, 'Cuba', 'CU'),
(58, 'Cyprus', 'CY'),
(59, 'Czech Republic', 'CZ'),
(60, 'Denmark', 'DK'),
(61, 'Djibouti', 'DJ'),
(62, 'Dominica', 'DM'),
(63, 'Dominican Republic', 'DO'),
(64, 'East Timor (Timor-Leste)', 'TP'),
(65, 'Ecuador', 'EC'),
(66, 'Egypt', 'EG'),
(67, 'El Salvador', 'SV'),
(68, 'Equatorial Guinea', 'GQ'),
(69, 'Eritrea', 'ER'),
(70, 'Estonia', 'EE'),
(71, 'Ethiopia', 'ET'),
(72, 'Falkland Islands', 'FK'),
(73, 'Faroe Islands', 'FO'),
(74, 'Fiji', 'FJ'),
(75, 'Finland', 'FI'),
(76, 'France', 'FR'),
(77, 'French Guiana', 'GF'),
(78, 'French Polynesia', 'PF'),
(79, 'Gabon', 'GA'),
(80, 'Gambia, the', 'GM'),
(81, 'Georgia', 'GE'),
(82, 'Germany', 'DE'),
(83, 'Ghana', 'GH'),
(84, 'Gibraltar', 'GI'),
(86, 'Greenland', 'GL'),
(87, 'Grenada', 'GD'),
(88, 'Guadeloupe', 'GP'),
(89, 'Guam', 'GU'),
(90, 'Guatemala', 'GT'),
(91, 'Guernsey and Alderney', 'GG'),
(92, 'Guinea', 'GN'),
(93, 'Guinea-Bissau', 'GW'),
(94, 'Guinea, Equatorial', 'GP'),
(95, 'Guiana, French', 'GF'),
(96, 'Guyana', 'GY'),
(97, 'Haiti', 'HT'),
(179, 'San Marino', 'SM'),
(99, 'Honduras', 'HN'),
(100, 'Hong Kong, (China)', 'HK'),
(101, 'Hungary', 'HU'),
(102, 'Iceland', 'IS'),
(103, 'India', 'IN'),
(104, 'Indonesia', 'ID'),
(105, 'Iran, Islamic Republic of', 'IR'),
(106, 'Iraq', 'IQ'),
(107, 'Ireland', 'IE'),
(108, 'Israel', 'IL'),
(109, 'Ivory Coast (Cote d''Ivoire)', 'CI'),
(110, 'Italy', 'IT'),
(111, 'Jamaica', 'JM'),
(112, 'Japan', 'JP'),
(113, 'Jersey', 'JE'),
(114, 'Jordan', 'JO'),
(115, 'Kazakhstan', 'KZ'),
(116, 'Kenya', 'KE'),
(117, 'Kiribati', 'KI'),
(118, 'Korea, (South) Rep. of', 'KR'),
(119, 'Kuwait', 'KW'),
(120, 'Kyrgyzstan', 'KG'),
(121, 'Lao People''s Dem. Rep.', 'LA'),
(122, 'Latvia', 'LV'),
(123, 'Lebanon', 'LB'),
(124, 'Lesotho', 'LS'),
(125, 'Libyan Arab Jamahiriya', 'LY'),
(126, 'Liechtenstein', 'LI'),
(127, 'Lithuania', 'LT'),
(128, 'Luxembourg', 'LU'),
(129, 'Macao, (China)', 'MO'),
(130, 'Macedonia, TFYR', 'MK'),
(131, 'Madagascar', 'MG'),
(132, 'Malawi', 'MW'),
(133, 'Malaysia', 'MY'),
(134, 'Maldives', 'MV'),
(135, 'Mali', 'ML'),
(136, 'Malta', 'MT'),
(137, 'Martinique', 'MQ'),
(138, 'Mauritania', 'MR'),
(139, 'Mauritius', 'MU'),
(140, 'Mexico', 'MX'),
(141, 'Micronesia', 'FM'),
(142, 'Moldova, Republic of', 'MD'),
(143, 'Monaco', 'MC'),
(144, 'Mongolia', 'MN'),
(145, 'Montenegro', 'CS'),
(146, 'Morocco', 'MA'),
(147, 'Mozambique', 'MZ'),
(148, 'Myanmar (ex-Burma)', 'MM'),
(149, 'Namibia', 'NA'),
(150, 'Nepal', 'NP'),
(151, 'Netherlands', 'NL'),
(152, 'New Caledonia', 'NC'),
(153, 'New Zealand', 'NZ'),
(154, 'Nicaragua', 'NI'),
(155, 'Niger', 'NE'),
(156, 'Nigeria', 'NG'),
(157, 'Northern Mariana Islands', 'MP'),
(159, 'Oman', 'OM'),
(160, 'Pakistan', 'PK'),
(161, 'Palestinian Territory', 'PS'),
(162, 'Panama', 'PA'),
(163, 'Papua New Guinea', 'PG'),
(164, 'Paraguay', 'PY'),
(165, 'Peru', 'PE'),
(166, 'Philippines', 'PH'),
(167, 'Poland', 'PL'),
(168, 'Portugal', 'PT'),
(170, 'Qatar', 'QA'),
(171, 'Reunion', 'RE'),
(172, 'Romania', 'RO'),
(173, 'Russian Federation', 'RU'),
(174, 'Rwanda', 'RW'),
(175, 'Saint Kitts and Nevis', 'KN'),
(176, 'Saint Lucia', 'LC'),
(177, 'St. Vincent & the Grenad.', 'VC'),
(178, 'Samoa', 'WS'),
(180, 'Sao Tome and Principe', 'ST'),
(181, 'Saudi Arabia', 'SA'),
(183, 'Serbia', 'RS'),
(184, 'Seychelles', 'SC'),
(185, 'Singapore', 'SG'),
(186, 'Slovakia', 'SK'),
(187, 'Slovenia', 'SI'),
(188, 'Solomon Islands', 'SB'),
(189, 'Somalia', 'SO'),
(220, 'South Africa', 'ZA'),
(190, 'Spain', 'ES'),
(191, 'Sri Lanka (ex-Ceilan)', 'LK'),
(192, 'Sudan', 'SD'),
(193, 'Suriname', 'SR'),
(194, 'Swaziland', 'SZ'),
(195, 'Sweden', 'SE'),
(196, 'Switzerland', 'CH'),
(197, 'Syrian Arab Republic', 'SY'),
(198, 'Taiwan', 'TW'),
(199, 'Tajikistan', 'TJ'),
(200, 'Tanzania, United Rep. of', 'TZ'),
(201, 'Thailand', 'TH'),
(202, 'Togo', 'TG'),
(203, 'Tonga', 'TO'),
(204, 'Trinidad & Tobago', 'TT'),
(205, 'Tunisia', 'TN'),
(206, 'Turkey', 'TR'),
(207, 'Turkmenistan', 'TM'),
(208, 'Uganda', 'UG'),
(209, 'Ukraine', 'UA'),
(210, 'United Arab Emirates', 'AE'),
(211, 'Uruguay', 'UY'),
(212, 'Uzbekistan', 'UZ'),
(213, 'Vanuatu', 'VU'),
(214, 'Venezuela', 'VE'),
(215, 'Viet Nam', 'VN'),
(216, 'Virgin Islands, U.S.', 'VI'),
(217, 'Yemen', 'YE'),
(218, 'Zambia', 'ZM'),
(219, 'Zimbabwe', 'ZW');

--
-- Table structure for table `module_session`
--

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `module_session` (
  `user_id` int(255) NOT NULL DEFAULT '0',
  `page_id` int(255) NOT NULL DEFAULT '0',
  `ses_key` int(255) NOT NULL DEFAULT '0',
  `module` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(255) NOT NULL DEFAULT '0',
  UNIQUE KEY `unique_ses` (`user_id`,`page_id`,`module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `myspace`
--

CREATE TABLE IF NOT EXISTS `myspace` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user` int(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `clicks` int(255) NOT NULL DEFAULT '0',
  `today_clicks` int(255) NOT NULL DEFAULT '0',
  `max_clicks` int(255) NOT NULL DEFAULT '0',
  `daily_clicks` int(255) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `cpc` int(11) NOT NULL DEFAULT '2',
  `country` varchar(64) NOT NULL DEFAULT '0',
  `sex` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `myspaced`
--

CREATE TABLE IF NOT EXISTS `myspaced` (
  `user_id` int(255) NOT NULL,
  `site_id` int(255) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `myspace_accounts`
--

CREATE TABLE IF NOT EXISTS `myspace_accounts` (
  `user` int(255) NOT NULL DEFAULT '0',
  `account_username` varchar(255) NOT NULL,
  KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payment_proofs`
--

CREATE TABLE IF NOT EXISTS `payment_proofs` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `p_id` int(255) NOT NULL DEFAULT '0',
  `u_id` int(255) NOT NULL DEFAULT '0',
  `proof` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `proof_date` int(255) NOT NULL DEFAULT '0',
  `approved` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `plused`
--

CREATE TABLE IF NOT EXISTS `plused` (
  `user_id` int(255) NOT NULL,
  `site_id` int(255) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `p_pack`
--

CREATE TABLE IF NOT EXISTS `p_pack` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `days` int(255) NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `coins_price` int(255) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `page_id` int(255) NOT NULL DEFAULT '0',
  `page_url` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `owner_id` int(255) NOT NULL DEFAULT '0',
  `reported_by` int(255) NOT NULL DEFAULT '0',
  `reason` varchar(128) CHARACTER SET latin1 NOT NULL,
  `count` int(64) NOT NULL DEFAULT '1',
  `module` varchar(64) CHARACTER SET latin1 NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `timestamp` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `reverbnation`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `reverbnation_done`
--

CREATE TABLE IF NOT EXISTS `reverbnation_done` (
  `user_id` int(255) NOT NULL,
  `site_id` int(255) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reverbnation_accounts`
--

CREATE TABLE IF NOT EXISTS `reverbnation_accounts` (
  `user` int(255) NOT NULL DEFAULT '0',
  `account_name` varchar(255) NOT NULL,
  `account_username` varchar(255) NOT NULL,
  KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE IF NOT EXISTS `requests` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user` int(255) NOT NULL,
  `paypal` varchar(255) NOT NULL,
  `amount` decimal(5,2) NOT NULL DEFAULT '0.00',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `paid` int(11) NOT NULL DEFAULT '0',
  `gateway` varchar(64) NOT NULL DEFAULT 'paypal',
  `reason` varchar(255) NOT NULL,
  `proof` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `retweet`
--

CREATE TABLE IF NOT EXISTS `retweet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(255) NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `clicks` int(255) NOT NULL DEFAULT '0',
  `today_clicks` int(255) NOT NULL DEFAULT '0',
  `max_clicks` int(255) NOT NULL DEFAULT '0',
  `daily_clicks` int(255) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `cpc` int(11) NOT NULL DEFAULT '2',
  `country` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `sex` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `retweeted`
--

CREATE TABLE IF NOT EXISTS `retweeted` (
  `user_id` int(255) NOT NULL,
  `site_id` int(255) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_config`
--

CREATE TABLE IF NOT EXISTS `site_config` (
  `setting_id` int(10) NOT NULL AUTO_INCREMENT,
  `config_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `config_value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`setting_id`),
  UNIQUE KEY `config_name` (`config_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `scf_done`
--

CREATE TABLE IF NOT EXISTS `scf_done` (
  `user_id` int(255) NOT NULL,
  `site_id` int(255) NOT NULL,
  INDEX ( `site_id` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `scf`
--

CREATE TABLE IF NOT EXISTS `scf` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user` int(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `s_av` varchar(255) NOT NULL,
  `s_id` int(255) NOT NULL,
  `clicks` int(255) NOT NULL DEFAULT '0',
  `today_clicks` int(255) NOT NULL DEFAULT '0',
  `max_clicks` int(255) NOT NULL DEFAULT '0',
  `daily_clicks` int(255) NOT NULL DEFAULT '0',
  `cpc` int(11) NOT NULL DEFAULT '2',
  `active` int(11) NOT NULL DEFAULT '0',
  `country` varchar(64) NOT NULL DEFAULT '0',
  `sex` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `stumble`
--

CREATE TABLE IF NOT EXISTS `stumble` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(255) NOT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `clicks` int(255) NOT NULL DEFAULT '0',
  `today_clicks` int(255) NOT NULL DEFAULT '0',
  `max_clicks` int(255) NOT NULL DEFAULT '0',
  `daily_clicks` int(255) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `cpc` int(11) NOT NULL DEFAULT '2',
  `country` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `sex` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `active` (`active`),
  KEY `cpc` (`cpc`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stumbled`
--

CREATE TABLE IF NOT EXISTS `stumbled` (
  `user_id` int(255) NOT NULL,
  `site_id` int(255) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `surf`
--

CREATE TABLE IF NOT EXISTS `surf` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user` int(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `clicks` int(2) NOT NULL DEFAULT '0',
  `today_clicks` int(255) NOT NULL DEFAULT '0',
  `max_clicks` int(255) NOT NULL DEFAULT '0',
  `daily_clicks` int(255) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `cpc` int(11) NOT NULL DEFAULT '2',
  `confirm` int(11) NOT NULL DEFAULT '0',
  `country` varchar(64) NOT NULL DEFAULT '0',
  `sex` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `surfed`
--

CREATE TABLE IF NOT EXISTS `surfed` (
  `user_id` int(255) NOT NULL,
  `site_id` int(255) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` text COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(255) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `gateway` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'paypal',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `paid` int(11) NOT NULL DEFAULT '1',
  `payer_email` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trans_id` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `user_ip` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `twitter`
--

CREATE TABLE IF NOT EXISTS `twitter` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user` int(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `t_id` int(255) NOT NULL,
  `clicks` int(255) NOT NULL DEFAULT '0',
  `today_clicks` int(255) NOT NULL DEFAULT '0',
  `max_clicks` int(255) NOT NULL DEFAULT '0',
  `daily_clicks` int(255) NOT NULL DEFAULT '0',
  `cpc` int(11) NOT NULL DEFAULT '2',
  `active` int(11) NOT NULL DEFAULT '0',
  `country` varchar(64) NOT NULL DEFAULT '0',
  `sex` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `used_coupons`
--

CREATE TABLE IF NOT EXISTS `used_coupons` (
  `id` int(255) NOT NULL auto_increment,
  `user_id` int(255) NOT NULL default '0',
  `coupon_id` int(255) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) DEFAULT NULL,
  `login` varchar(32) NOT NULL,
  `admin` int(11) NOT NULL DEFAULT '0',
  `coins` int(255) NOT NULL DEFAULT '0',
  `account_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `premium` int(255) NOT NULL DEFAULT '0',
  `IP` varchar(32) CHARACTER SET latin1 DEFAULT NULL,
  `log_ip` varchar(32) NOT NULL DEFAULT '0',
  `pass` varchar(32) DEFAULT NULL,
  `ref` int(255) DEFAULT NULL,
  `ref_paid` int(11) NOT NULL DEFAULT '1',
  `signup` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `online` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `newsletter` int(11) NOT NULL DEFAULT '1',
  `promote` int(255) NOT NULL DEFAULT '0',
  `daily_bonus` int(255) NOT NULL DEFAULT '0',
  `activate` int(255) NOT NULL DEFAULT '0',
  `banned` int(11) NOT NULL DEFAULT '0',
  `rec_hash` int(255) NOT NULL DEFAULT '0',
  `country` varchar(64) NOT NULL DEFAULT '0',
  `c_changes` int(11) NOT NULL DEFAULT '0',
  `sex` int(11) NOT NULL DEFAULT '0',
  `warn_message` varchar(255) NOT NULL,
  `warn_expire` int(255) NOT NULL DEFAULT '0',
  `ref_source` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `login` (`login`),
  KEY `banned` (`banned`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_clicks`
--

CREATE TABLE IF NOT EXISTS `user_clicks` (
  `uid` int(11) NOT NULL DEFAULT '0',
  `module` varchar(64) NOT NULL,
  `total_clicks` int(255) NOT NULL DEFAULT '0',
  `today_clicks` int(255) NOT NULL DEFAULT '0',
  UNIQUE KEY `unique_stats` (`module`,`uid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_transactions`
--

CREATE TABLE IF NOT EXISTS `user_transactions` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user_id` int(255) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `value` int(255) NOT NULL DEFAULT '0',
  `cash` decimal(10,2) NOT NULL DEFAULT '0.00',
  `date` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `viewed`
--

CREATE TABLE IF NOT EXISTS `viewed` (
  `user_id` int(255) NOT NULL,
  `site_id` int(255) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `web_stats`
--

CREATE TABLE IF NOT EXISTS `web_stats` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `module_id` varchar(64) NOT NULL,
  `module_name` varchar(64) NOT NULL,
  `value` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yfav`
--

CREATE TABLE IF NOT EXISTS `yfav` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user` int(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `clicks` int(255) NOT NULL DEFAULT '0',
  `today_clicks` int(255) NOT NULL DEFAULT '0',
  `max_clicks` int(255) NOT NULL DEFAULT '0',
  `daily_clicks` int(255) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `cpc` int(11) NOT NULL DEFAULT '2',
  `country` varchar(64) NOT NULL DEFAULT '0',
  `sex` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yfaved`
--

CREATE TABLE IF NOT EXISTS `yfaved` (
  `user_id` int(255) NOT NULL,
  `site_id` int(255) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `yfav_accounts`
--

CREATE TABLE IF NOT EXISTS `yfav_accounts` (
  `user` int(255) NOT NULL DEFAULT '0',
  `account_name` varchar(255) NOT NULL,
  `account_id` varchar(255) NOT NULL,
  `fav_id` varchar(255) NOT NULL,
  KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `youtube`
--

CREATE TABLE IF NOT EXISTS `youtube` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user` int(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `clicks` int(255) NOT NULL DEFAULT '0',
  `today_clicks` int(255) NOT NULL DEFAULT '0',
  `max_clicks` int(255) NOT NULL DEFAULT '0',
  `daily_clicks` int(255) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `cpc` int(11) NOT NULL DEFAULT '2',
  `country` varchar(64) NOT NULL DEFAULT '0',
  `sex` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ysub`
--

CREATE TABLE IF NOT EXISTS `ysub` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user` int(255) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `y_av` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `clicks` int(255) NOT NULL,
  `today_clicks` int(255) NOT NULL DEFAULT '0',
  `max_clicks` int(255) NOT NULL DEFAULT '0',
  `daily_clicks` int(255) NOT NULL DEFAULT '0',
  `cpc` int(11) NOT NULL DEFAULT '2',
  `active` int(11) NOT NULL,
  `country` varchar(64) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `sex` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ysubed`
--

CREATE TABLE IF NOT EXISTS `ysubed` (
  `user_id` int(255) NOT NULL,
  `site_id` int(255) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


-- --------------------------------------------------------

--
-- Table structure for table `ylike`
--

CREATE TABLE IF NOT EXISTS `ylike` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user` int(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `clicks` int(255) NOT NULL DEFAULT '0',
  `today_clicks` int(255) NOT NULL DEFAULT '0',
  `max_clicks` int(255) NOT NULL DEFAULT '0',
  `daily_clicks` int(255) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `cpc` int(11) NOT NULL DEFAULT '2',
  `country` varchar(64) NOT NULL DEFAULT '0',
  `sex` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yliked`
--

CREATE TABLE IF NOT EXISTS `yliked` (
  `user_id` int(255) NOT NULL,
  `site_id` int(255) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;