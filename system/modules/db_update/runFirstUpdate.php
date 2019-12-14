<?php
/** Update from old settings table (if exists) **/
$db->Query("CREATE TABLE IF NOT EXISTS `site_config` ( `setting_id` int(10) NOT NULL AUTO_INCREMENT, `config_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL, `config_value` text COLLATE utf8_unicode_ci NOT NULL, PRIMARY KEY (`setting_id`), UNIQUE KEY `config_name` (`config_name`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");
if($db->QueryGetNumRows("SHOW TABLES LIKE 'settings'")){
	$configs = $db->QueryFetchArray("SELECT * FROM settings LIMIT 1");

	$query = array();
	foreach($configs as $key => $config){
		if($key != 'id'){
			$query[] = "('".$key."', '".$db->EscapeString($config)."')";
		}
	}

	$query_values = implode(',', $query);
	$db->Query("INSERT IGNORE INTO `site_config` (`config_name`, `config_value`) VALUES ".$query_values);
}

/** Update database **/
$add_config = array();

// Update to 2.0.0
$add_config[] = "('noreply_email', ''),('license_file', '')";

// Update to 1.9.8
if($db->QueryGetNumRows("SHOW KEYS FROM `module_session` WHERE KEY_NAME = 'user_id'") > 0){
	$db->Query("ALTER TABLE `module_session` DROP INDEX `user_id`");
}

if($db->QueryGetNumRows("SHOW KEYS FROM `module_session` WHERE KEY_NAME = 'unique_ses'") < 1){
	$db->Query("ALTER TABLE `module_session` ADD CONSTRAINT `unique_ses` UNIQUE (`user_id`,`page_id`,`module`)");
}

if($db->QueryGetNumRows("SHOW KEYS FROM `user_clicks` WHERE KEY_NAME = 'unique_stats'") < 1){
	$db->Query("ALTER TABLE `user_clicks` ADD CONSTRAINT `unique_stats` UNIQUE (`module`,`uid`)");

	if($db->QueryGetNumRows("SHOW KEYS FROM `user_clicks` WHERE KEY_NAME = 'module'") > 0){
		$db->Query("ALTER TABLE `user_clicks` DROP INDEX `module`");
	}
}
if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'activity_rewards'")){$db->Query("CREATE TABLE IF NOT EXISTS `activity_rewards` (`id` int(255) NOT NULL AUTO_INCREMENT, `exchanges` int(255) NOT NULL DEFAULT '0', `reward` int(255) NOT NULL DEFAULT '0', `type` int(32) NOT NULL DEFAULT '0', `claims` int(255) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");}
if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'activity_rewards_claims'")){$db->Query("CREATE TABLE IF NOT EXISTS `activity_rewards_claims` ( `reward_id` int(255) NOT NULL DEFAULT '0', `user_id` int(255) NOT NULL DEFAULT '0', `date` int(255) NOT NULL DEFAULT '0', KEY `reward_id` (`reward_id`), KEY `user_id` (`user_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");}
if(!$db->Query("SELECT exchanges FROM coupons")){$db->Query("ALTER TABLE `coupons` ADD `exchanges` INT( 255 ) NOT NULL DEFAULT '0'");}

// Update to 1.9.7
$add_config[] = "('theme', 'default'),('solvemedia_c', ''),('solvemedia_v', ''),('solvemedia_h', '')";

// Update to 1.9.6
$add_config[] = "('proof_required', '0'),('aff_req_clicks', '0'),('smtp_host', 'localhost'),('smtp_port', '25'),('smtp_username', ''),('smtp_password', ''),('smtp_auth', '0'),('mail_delivery_method', '0')";
if(!$db->Query("SELECT proof FROM requests")){$db->Query("ALTER TABLE `requests` ADD `proof` INT( 11 ) NOT NULL DEFAULT '0'");}

// Update to 1.9.2
$add_config[] = "('clicks_limit', '0')";

// Update to 1.9.0
if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'blacklist'")){$db->Query("CREATE TABLE IF NOT EXISTS `blacklist` (`id` int(255) NOT NULL AUTO_INCREMENT, `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL, `type` int(32) NOT NULL DEFAULT '0', PRIMARY KEY (`id`), KEY `value` (`value`,`type`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");}

// Update to 1.8.9
$add_config[] = "('yt_api', '')";

// Update to 1.8.8
$add_config[] = "('twitter_token', ''),('twitter_token_secret', ''),('twitter_consumer_key', ''),('twitter_consumer_secret', '')";
if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'ad_packs'")){$db->Query("CREATE TABLE IF NOT EXISTS `ad_packs` (`id` int(255) NOT NULL AUTO_INCREMENT, `price` decimal(6,2) NOT NULL DEFAULT '0.00', `days` int(255) NOT NULL DEFAULT '0', `bought` int(255) NOT NULL DEFAULT '0', `type` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");}
if(!$db->Query("SELECT title FROM twitter")){$db->Query("ALTER TABLE `twitter` ADD `title` VARCHAR( 255 ) NOT NULL");}
if($db->Query("SELECT t_name FROM twitter")){$db->Query("UPDATE `twitter` SET `title`=`t_name`");}
if($db->Query("SELECT t_name FROM twitter")){$db->Query("ALTER TABLE `twitter` CHANGE `t_name` `url` VARCHAR( 255 ) NOT NULL");}

// Update to 1.8.7
$add_config[] = "('auto_country', '0'),('blog_comments', '1')";
if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'blog'")){$db->Query("CREATE TABLE IF NOT EXISTS `blog` (`id` int(255) NOT NULL AUTO_INCREMENT, `author` int(255) NOT NULL DEFAULT '0', `title` varchar(255) NOT NULL, `content` text NOT NULL, `timestamp` int(255) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");}
if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'blog_comments'")){$db->Query("CREATE TABLE IF NOT EXISTS `blog_comments` (`id` int(255) NOT NULL AUTO_INCREMENT, `bid` int(255) NOT NULL DEFAULT '0', `author` int(255) NOT NULL DEFAULT '0', `comment` text NOT NULL, `timestamp` int(255) NOT NULL DEFAULT '0', PRIMARY KEY (`id`), KEY `bid` (`bid`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;;");}

// Update to 1.8.5
if($db->Query("SELECT cf_bonus FROM users")){$db->Query("ALTER TABLE `users` DROP `cf_bonus`");}

// Update to 1.8.4
$add_config[] = "('revshare_api', ''),('fb_app_id', ''),('fb_app_secret', '')";
if(!$db->Query("SELECT warn_expire FROM users")){$db->Query("ALTER TABLE `users` ADD `warn_expire` INT( 255 ) NOT NULL DEFAULT '0'");}

// Update to 1.8.2
$add_config[] = "('reg_cash', '0.00'),('surf_time_type', '0')";
if(!$db->Query("SELECT type FROM coupons")){$db->Query("ALTER TABLE `coupons` ADD `type` INT( 11 ) NOT NULL DEFAULT '0'");}

// Update to 1.8.1
$add_config[] = "('allow_withdraw', '0')";
if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'coins_to_cash'")){$db->Query("CREATE TABLE IF NOT EXISTS `coins_to_cash` ( `id` int(255) NOT NULL AUTO_INCREMENT, `user` int(11) NOT NULL, `coins` int(255) NOT NULL DEFAULT '0', `cash` decimal(5,2) NOT NULL DEFAULT '0.00', `conv_rate` int(64) NOT NULL DEFAULT '0', `date` int(255) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");}
if(!$db->Query("SELECT account_balance FROM users")){$db->Query("ALTER TABLE `users` ADD `account_balance` DECIMAL( 5,2 ) NOT NULL DEFAULT '0.00' AFTER `coins`");}
if($db->Query("SELECT cash FROM users")){$db->Query("UPDATE `users` SET `account_balance`=`account_balance`+`cash`");}
if($db->Query("SELECT cash FROM users")){$db->Query("ALTER TABLE `users` DROP `cash`");}
if($db->Query("SELECT pack FROM transactions")){$db->Query("ALTER TABLE `transactions` DROP `pack`");}
if($db->Query("SELECT points FROM transactions")){$db->Query("ALTER TABLE `transactions` DROP `points`");}

// Update to 1.8.0
$add_config[] = "('mysql_random', '0'),('convert_enabled', '0'),('convert_rate', '1000'),('min_convert', '100')";
if(!$db->Query("SELECT reason FROM requests")){$db->Query("ALTER TABLE `requests` ADD `reason` VARCHAR( 255 ) NOT NULL");}
if(!$db->Query("SELECT warn_message FROM users")){$db->Query("ALTER TABLE `users` ADD `warn_message` VARCHAR( 255 ) NOT NULL ");}
if(!$db->Query("SELECT user_id FROM transactions")){$db->Query("ALTER TABLE `transactions` ADD `user_id` INT( 255 ) NOT NULL DEFAULT '0' AFTER `user`");}
if(!$db->Query("SELECT payer_email FROM transactions")){$db->Query("ALTER TABLE `transactions` ADD `payer_email` VARCHAR( 128 ) NULL");}
if(!$db->Query("SELECT user_ip FROM transactions")){$db->Query("ALTER TABLE `transactions` ADD `user_ip` VARCHAR( 64 ) NULL");}
if(!$db->Query("SELECT trans_id FROM transactions")){$db->Query("ALTER TABLE `transactions` ADD `trans_id` VARCHAR( 128 ) NOT NULL");}
if(!$db->Query("SELECT ref_source FROM users")){$db->Query("ALTER TABLE `users` ADD `ref_source` VARCHAR( 255 ) NOT NULL DEFAULT '0'");}
if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'user_transactions'")){$db->Query("CREATE TABLE IF NOT EXISTS `user_transactions` (`id` int(255) NOT NULL AUTO_INCREMENT, `user_id` int(255) NOT NULL DEFAULT '0', `type` int(11) NOT NULL DEFAULT '0', `value` int(255) NOT NULL DEFAULT '0', `cash` decimal(5,2) NOT NULL DEFAULT '0.00', `date` int(255) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");}

// Update to 1.7.2
$add_config[] = "('aff_click_req', '0'),('paypal_auto', '1'),('payza_auto', '1'),('report_limit', '0')";
if(!$db->Query("SELECT ref_paid FROM users")){$db->Query("ALTER TABLE `users` ADD `ref_paid` INT( 11 ) NOT NULL DEFAULT '1' AFTER `ref`");}
if(!$db->Query("SELECT paid FROM transactions")){$db->Query("ALTER TABLE `transactions` ADD `paid` INT( 11 ) NOT NULL DEFAULT '1'");}

// Update to 1.7.1
$add_config[] = "('aff_reg_days', '0'),('analytics_id', '')";
if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'web_stats'")){$db->Query("CREATE TABLE IF NOT EXISTS `web_stats` (`id` int(255) NOT NULL AUTO_INCREMENT, `module_id` varchar(64) NOT NULL, `module_name` varchar(64) NOT NULL, `value` int(255) NOT NULL DEFAULT '0', PRIMARY KEY (`id`), KEY `module_id` (`module_id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");}

// Update to v1.7.0
$add_config[] = "('target_system', '0')";
if($db->Query("SELECT y_name FROM ysub")){$db->Query("ALTER TABLE `ysub` CHANGE `y_name` `title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL");}
if($db->Query("SELECT y_link FROM ysub")){$db->Query("ALTER TABLE `ysub` CHANGE `y_link` `url` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL");}

// Update to v1.6.9
$add_config[] = "('captcha_sys', '0'),('recaptcha_pub', '0'),('recaptcha_sec', '0')";
if(!$db->Query("SELECT coins_price FROM p_pack")){$db->Query("ALTER TABLE `p_pack` ADD `coins_price` INT( 255 ) NOT NULL DEFAULT '0'");}
if(!$db->Query("SELECT type FROM p_pack")){$db->Query("ALTER TABLE `p_pack` ADD `type` INT( 11 ) NOT NULL DEFAULT '0'");}

// Update to v1.6.8
if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'reports'")){$db->Query("CREATE TABLE IF NOT EXISTS `reports` ( `id` int(255) NOT NULL AUTO_INCREMENT, `page_id` int(255) NOT NULL DEFAULT '0', `page_url` varchar(255) NOT NULL DEFAULT '0', `owner_id` int(255) NOT NULL DEFAULT '0', `reported_by` int(255) NOT NULL DEFAULT '0', `reason` varchar(128) NOT NULL, `count` int(64) NOT NULL DEFAULT '1', `module` varchar(64) NOT NULL, `status` int(11) NOT NULL DEFAULT '0', `timestamp` int(255) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");}
if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'user_clicks'")){$db->Query("CREATE TABLE IF NOT EXISTS `user_clicks` ( `uid` int(11) NOT NULL DEFAULT '0', `module` varchar(64) NOT NULL, `total_clicks` int(255) NOT NULL DEFAULT '0', `today_clicks` int(255) NOT NULL DEFAULT '0', KEY `module` (`module`), KEY `uid` (`uid`)) ENGINE=MyISAM DEFAULT CHARSET=latin1;");}

// Update to v1.6.5
if(!$db->Query("SELECT gateway FROM requests")){$db->Query("ALTER TABLE `requests` ADD `gateway` VARCHAR( 64 ) NOT NULL DEFAULT 'paypal'");}
if(!$db->Query("SELECT log_ip FROM users")){$db->Query("ALTER TABLE `users` ADD `log_ip` VARCHAR( 32 ) NOT NULL DEFAULT '0' AFTER `IP`");}
if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'ban_reasons'")){$db->Query("CREATE TABLE IF NOT EXISTS `ban_reasons` (`id` int(255) NOT NULL auto_increment, `user` int(255) NOT NULL default '0', `reason` text NOT NULL, `date` int(255) NOT NULL default '0', PRIMARY KEY (`id`), KEY `user` (`user`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1");}

// Update to v1.6.4
$add_config[] = "('payza', ''),('payza_security', ''),('paypal_status', '1'),('payza_status', '0')";
if(!$db->Query("SELECT gateway FROM transactions")){$db->Query("ALTER TABLE `transactions` ADD `gateway` VARCHAR( 64 ) NOT NULL DEFAULT 'paypal'");}

// Update to v1.6.2
$add_config[] = "('c_discount', '0'),('c_show_msg', '0'),('c_text_index', ''),('hideref', '0')";

// Update to v1.6.0
if(!$db->Query("SELECT newsletter FROM users")){$db->Query("ALTER TABLE `users` ADD `newsletter` INT( 11 ) NOT NULL DEFAULT '1' AFTER `online`");}
if(!$db->QueryGetNumRows("SHOW TABLES LIKE module_session")){$db->Query("CREATE TABLE IF NOT EXISTS `module_session` (`user_id` int(255) NOT NULL default '0', `page_id` int(255) NOT NULL default '0', `ses_key` int(255) NOT NULL default '0', `module` varchar(255) collate utf8_unicode_ci NOT NULL, `timestamp` int(255) NOT NULL default '0', KEY `user_id` (`user_id`,`page_id`,`module`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");}

// Update to v1.5.1
$add_config[] = "('surf_fb_skip', '0'),('surf_fc_req', '0'),('crf_bonus', '0')";

// Update to v1.4.0
$add_config[] = "('c_c_limit', '2'),('c_v_limit', '5')";
if(!$db->Query("SELECT country FROM google")){$db->Query("ALTER TABLE `google` ADD `country` VARCHAR( 64 ) NOT NULL DEFAULT '0', ADD `sex` INT( 11 ) NOT NULL DEFAULT '0'");}
if(!$db->Query("SELECT country FROM linkedin")){$db->Query("ALTER TABLE `linkedin` ADD `country` VARCHAR( 64 ) NOT NULL DEFAULT '0', ADD `sex` INT( 11 ) NOT NULL DEFAULT '0'");}
if(!$db->Query("SELECT country FROM retweet")){$db->Query("ALTER TABLE `retweet` ADD `country` VARCHAR( 64 ) NOT NULL DEFAULT '0', ADD `sex` INT( 11 ) NOT NULL DEFAULT '0'");}
if(!$db->Query("SELECT country FROM stumble")){$db->Query("ALTER TABLE `stumble` ADD `country` VARCHAR( 64 ) NOT NULL DEFAULT '0', ADD `sex` INT( 11 ) NOT NULL DEFAULT '0'");}
if(!$db->Query("SELECT country FROM surf")){$db->Query("ALTER TABLE `surf` ADD `country` VARCHAR( 64 ) NOT NULL DEFAULT '0', ADD `sex` INT( 11 ) NOT NULL DEFAULT '0'");}
if(!$db->Query("SELECT country FROM twitter")){$db->Query("ALTER TABLE `twitter` ADD `country` VARCHAR( 64 ) NOT NULL DEFAULT '0', ADD `sex` INT( 11 ) NOT NULL DEFAULT '0'");}
if(!$db->Query("SELECT country FROM ylike")){$db->Query("ALTER TABLE `ylike` ADD `country` VARCHAR( 64 ) NOT NULL DEFAULT '0', ADD `sex` INT( 11 ) NOT NULL DEFAULT '0'");}
if(!$db->Query("SELECT country FROM youtube")){$db->Query("ALTER TABLE `youtube` ADD `country` VARCHAR( 64 ) NOT NULL DEFAULT '0', ADD `sex` INT( 11 ) NOT NULL DEFAULT '0'");}
if(!$db->Query("SELECT country FROM ysub")){$db->Query("ALTER TABLE `ysub` ADD `country` VARCHAR( 64 ) NOT NULL DEFAULT '0', ADD `sex` INT( 11 ) NOT NULL DEFAULT '0'");}
if(!$db->Query("SELECT country FROM users")){$db->Query("ALTER TABLE `users` ADD `country` VARCHAR( 64 ) NOT NULL DEFAULT '0', ADD `c_changes` INT( 11 ) NOT NULL DEFAULT '0', ADD `sex` INT( 11 ) NOT NULL DEFAULT '0'");}
if(!$db->QueryGetNumRows("SHOW TABLES LIKE list_countries")){executeSql("system/modules/db_update/countries.sql");}

// Update from other versions
$add_config[] = "('more_per_ip', '0'),('def_lang', 'en'),('daily_bonus_vip', '0'),('transfer_status', '0'),('transfer_fee', '10'),('surf_time', '10'),('surf_type', '0'),('banner_system', '1')";
if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'payment_proofs'")){$db->Query("CREATE TABLE IF NOT EXISTS `payment_proofs` (`id` int(255) NOT NULL AUTO_INCREMENT, `p_id` int(255) NOT NULL DEFAULT '0', `u_id` int(255) NOT NULL DEFAULT '0', `proof` varchar(255) COLLATE utf8_unicode_ci NOT NULL, `proof_date` int(255) NOT NULL DEFAULT '0', `approved` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");}
if(!$db->Query("SELECT views FROM blog")){$db->Query("ALTER TABLE `blog` ADD `views` INT( 255 ) NOT NULL DEFAULT '0' AFTER `content`");}
if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'c_transfers'")){$db->Query("CREATE TABLE IF NOT EXISTS `c_transfers` (`id` int(255) NOT NULL auto_increment, `receiver` int(255) NOT NULL default '0', `sender` varchar(255) collate utf8_unicode_ci NOT NULL, `coins` int(255) NOT NULL default '0', `date` int(255) NOT NULL default '0', PRIMARY KEY  (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1");}
if(!$db->Query("SELECT reason FROM reports")){$db->Query("ALTER TABLE `reports` ADD `reason` VARCHAR( 255 ) NOT NULL AFTER `reported_by`");}
if(!$db->Query("SELECT owner_id FROM reports")){$db->Query("ALTER TABLE `reports` ADD `owner_id` INT( 255 ) NOT NULL DEFAULT '0' AFTER `page_url`");}
if(!$db->Query("SELECT count FROM reports")){$db->Query("ALTER TABLE `reports` ADD `count` INT( 64 ) NOT NULL DEFAULT '1' AFTER `reported_by`");}
if(!$db->Query("SELECT country FROM users")){$db->Query("ALTER TABLE `users` ADD `country` VARCHAR( 64 ) NOT NULL DEFAULT '0'");}
if($db->Query("SELECT user FROM surfed")){$db->Query("ALTER TABLE `surfed` CHANGE `user` `user_id` INT( 255 ) NOT NULL");}
if($db->Query("SELECT site FROM surfed")){$db->Query("ALTER TABLE `surfed` CHANGE `site` `site_id` INT( 255 ) NOT NULL");}
if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'banners'")){executeSql("system/modules/db_update/banners.sql");}
if(!$db->Query("SELECT type FROM banners")){$db->Query("ALTER TABLE `banners` ADD `type` INT( 11 ) NOT NULL DEFAULT '0'");}
if(!$db->Query("SELECT type FROM ad_packs")){$db->Query("ALTER TABLE `ad_packs` ADD `type` INT( 11 ) NOT NULL DEFAULT '0'");}
if(!$db->Query("SELECT used FROM coupons")){$db->Query("ALTER TABLE `coupons` ADD `used` INT( 255 ) NOT NULL DEFAULT '0'");}

// Insert new configs
$config_values = implode(',', $add_config);
$db->Query("INSERT IGNORE INTO `site_config` (`config_name`, `config_value`) VALUES ".$config_values);

// Remove files (if files are not deleted automatically, please delete "system/db_update" folder)
if($db->Connect()){
	eval(base64_decode('QHVubGluayhyZWFscGF0aChkaXJuYW1lKF9fRklMRV9fKSkuJy9iYW5uZXJzLnNxbCcpOw0KQHVubGluayhyZWFscGF0aChkaXJuYW1lKF9fRklMRV9fKSkuJy9jb3VudHJpZXMuc3FsJyk7DQpAdW5saW5rKHJlYWxwYXRoKGRpcm5hbWUoX19GSUxFX18pKS4nL3J1bkZpcnN0VXBkYXRlLnBocCcpOw=='));
}
?>