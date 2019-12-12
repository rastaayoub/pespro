<?php
	/* Error Reporting */
	error_reporting(0);
	ini_set('display_errors', 0);

	/* Server configuration & optimisation */
	ini_set('implicit_flush', 1);
	set_time_limit(0);

	/* Starting session */
	session_start();

	/* Starting compression */
	ob_start();

	/* Define Base Path */
	define('BASE_PATH', realpath(dirname(__FILE__).'/..'));

	/* Define Database Extension (MySQL or MySQLi) */
	$config['sql_extenstion']  = 'MySQL';

	/* Include required files */
	require_once(BASE_PATH.'/system/database.php');
	require_once(BASE_PATH.'/system/libs/functions.php');
	require_once(BASE_PATH.'/system/libs/database/'.$config['sql_extenstion'].'.php');

	/* Database connection */
	$db = new MySQLConnection($config['sql_host'], $config['sql_username'], $config['sql_password'], $config['sql_database']);
	$db->Connect();

	unset($config['sql_password']);

	/* Run first update, before loading settings */
	if(file_exists(BASE_PATH.'/system/modules/db_update/runFirstUpdate.php')){
		include(BASE_PATH.'/system/modules/db_update/runFirstUpdate.php');
	}

	/* Website settings */
	$config['version'] = '2.2.7';

	$site = array();
	$configs = $db->QueryFetchArrayAll("SELECT config_name,config_value FROM `site_config`");
	foreach ($configs as $con)
	{
		$site[$con['config_name']] = $con['config_value']; 
	}

	unset($configs); 

	/* Website Theme */
	$site['theme'] = (!empty($site['theme']) && file_exists(BASE_PATH.'/theme/'.$site['theme'].'/cnf.php') ? $site['theme'] : 'default');
	include(BASE_PATH.'/theme/'.$site['theme'].'/cnf.php');
	if(defined('IS_ADMIN')){
		foreach(glob(BASE_PATH.'/theme/*/cnf.php') as $tm){
			include($tm);
			
			$selected = (isset($_POST['set']['theme']) && $_POST['set']['theme'] == $theme['code'] ? ' selected' : (!isset($_POST['set']['theme']) && $site['theme'] == $theme['code'] ? ' selected' : '')); 
			$set_def_theme .= '<option value="'.$theme['code'].'"'.$selected.'>'.$theme['name'].'</option>';
		}
	}

	/* Include modules */
	foreach(glob(BASE_PATH.'/system/modules/*/index.php') as $plugin) {  
		include($plugin);  
	}

	/* Cron */
	include('cron/cron.php');

	/* User Session */
	$is_online = false;
	if(isset($_SESSION['EX_login'])){
		$ses_id = $db->EscapeString($_SESSION['EX_login']);
		$data	= $db->QueryFetchArray("SELECT *,UNIX_TIMESTAMP(`online`) AS `online` FROM `users` WHERE `id`='".$ses_id."' AND `banned`='0' LIMIT 1");
		$is_online = true;
		if(empty($data['id'])){
			session_destroy();
			$is_online = false;
		}elseif($data['online']+60 < time() && defined('IS_AJAX')){
			$db->Query("UPDATE `users` SET `online`=NOW() WHERE `id`='".$data['id']."'");
			$_SESSION['EX_login'] = $data['id'];
		}
	}elseif(isset($_COOKIE['PESAutoLogin'])){
		$sesCookie = $db->EscapeString($_COOKIE['PESAutoLogin'], 0);

		$ses_user 	= '';
		$ses_hash 	= '';
		$sesCookie_exp = explode('&', $sesCookie);
		foreach($sesCookie_exp as $sesCookie_part){
			$find_ses_exp = explode('=', $sesCookie_part);
			if($find_ses_exp[0] == 'ses_user'){
				$ses_user = $db->EscapeString($find_ses_exp[1]);
			}elseif($find_ses_exp[0] == 'ses_hash'){
				$ses_hash = $db->EscapeString($find_ses_exp[1]);
			}
		}
		
		if(!empty($ses_user) && !empty($ses_hash)){
			$data = $db->QueryFetchArray("SELECT *,UNIX_TIMESTAMP(`online`) AS `online` FROM `users` WHERE (`login`='".$ses_user."' OR `email`='".$ses_user."') AND (`pass`='".$ses_hash."' AND `banned`='0') LIMIT 1");
			if(empty($data['id'])){
				unset($_COOKIE['PESAutoLogin']); 
			}else{
				$_SESSION['EX_login'] = $data['id'];
				$is_online = true;
			}
		}else{
			unset($_COOKIE['PESAutoLogin']); 
		}
	}

	/* Referral System */
	if(isset($_GET['ref']) && is_numeric($_GET['ref'])){setcookie("PlusREF", $db->EscapeString($_GET['ref']), time()+3600);}
	if($is_online && !defined('IS_AJAX')){
		if($data['ref'] > 0 && $data['ref_paid'] != 1){
			$ref_valid = $db->QueryFetchArray("SELECT SUM(`total_clicks`) AS `clicks` FROM `user_clicks` WHERE `uid`='".$data['id']."' LIMIT 1");
			if($ref_valid['clicks'] >= $site['aff_click_req']){
				$db->Query("UPDATE `users` SET `coins`=`coins`+'".$site['ref_coins']."' WHERE `id`='".$data['ref']."'");
				$db->Query("UPDATE `users` SET `ref_paid`='1' WHERE `id`='".$data['id']."'");
				
				if($site['paysys'] == 1 && $site['ref_cash'] > 0){
					affiliate_commission($data['ref'], $data['id'], $site['ref_cash'], 'referral_activity');
				}
			}
		}
		if($data['premium'] > 0 && $data['premium'] < time()){
			$db->Query("UPDATE `users` SET `premium`='0' WHERE `id`='".$data['id']."'");
		}
	}

	/* Language system */
	$lang_select = '';
	if(defined('IS_ADMIN')){ $set_def_lang = ''; }
	$CONF['language'] = ($site['def_lang'] != '' && file_exists('languages/'.$site['def_lang'].'/index.php') ? $site['def_lang'] : 'en');
	foreach(glob(BASE_PATH.'/languages/*/index.php') as $langname){
		$langarray[] = str_replace(array(BASE_PATH.'/languages/', '/index.php'), '', $langname);
		include($langname);
		
		if(defined('IS_ADMIN')){
			$selected = (isset($_POST['set']['def_lang']) && $_POST['set']['def_lang'] == $c_lang['code'] ? ' selected' : (!isset($_POST['set']['def_lang']) && $site['def_lang'] == $c_lang['code'] ? ' selected' : '')); 
			if($c_lang['active'] != 0){
				$set_def_lang .= '<option value="'.$c_lang['code'].'"'.$selected.'>'.$c_lang['lang'].'</option>';
			}
		}
		
		if(isset($_GET['peslang'])){
			$selected = ($_GET['peslang'] == $c_lang['code'] ? ' selected' : '');
		}elseif(isset($_COOKIE['peslang'])){
			$selected = ($_COOKIE['peslang'] == $c_lang['code'] ? ' selected' : '');
		}else{
			$selected = ($CONF['language'] == $c_lang['code'] ? ' selected' : '');
		}
		
		if($c_lang['active'] != 0){
			$lang_select .= '<option value="'.$c_lang['code'].'"'.$selected.'>'.$c_lang['lang'].'</option>';
		}
	}

	if(isset($_GET['peslang'])){
		if(in_array($_GET['peslang'], $langarray)){
			setcookie('peslang', $_GET['peslang'], time()+360000);
			$CONF['language'] = $_GET['peslang'];
		}
	} elseif (isset($_COOKIE['peslang']) && $_COOKIE['peslang'] != ''){
		$CONF['language'] = $_COOKIE['peslang'];
	}

	// Load main language
	if($CONF['language'] != 'en') {
		if(file_exists(BASE_PATH.'/languages/en/base/lang.php')){ 
			include(BASE_PATH.'/languages/en/base/lang.php'); 
		}

		if(file_exists(BASE_PATH.'/languages/en/modules')){ 
			foreach(glob(BASE_PATH.'/languages/en/modules/*.php') as $langfile) {  
				include($langfile);  
			}  
		}
	}

	// Load selected language
	foreach(glob(BASE_PATH.'/languages/'.$CONF['language'].'/*/*.php') as $langfile) {  
		include($langfile);  
	}  

	/* Run second update, after loading settings */
	if(file_exists(BASE_PATH.'/system/modules/db_update/runSecondUpdate.php')){
		include(BASE_PATH.'/system/modules/db_update/runSecondUpdate.php');
	}

	/* Remove Footer Branding */
	if(file_exists(BASE_PATH.'/system/copyright.php')) {
		include('copyright.php');
	}

	$conf['lang_charset'] = (!empty($c_lang[$CONF['language'].'_charset']) ? $c_lang[$CONF['language'].'_charset'] : 'UTF-8');
	header('Content-type: text/html; charset='.$conf['lang_charset']);

	/* All rights reserved (c) MafiaNet - MN-Shop.com */
?>