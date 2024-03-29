CREATE TABLE IF NOT EXISTS `list_countries` (
  `id` int(11) NOT NULL auto_increment,
  `country` varchar(255) NOT NULL default '',
  `code` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin2 AUTO_INCREMENT=1 ;

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