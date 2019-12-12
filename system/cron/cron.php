<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

/* Define functions */
function write_cron($timestamp, $cron_name, $var_name){
	$filename = realpath(dirname(__FILE__)).'/times/'.$cron_name.'.php';
	$content = file_put_contents($filename, '<? $'.$var_name.'[\'time\'] = \''.$timestamp.'\'; ?>');

	$return = true;
	if(!$content){
		die('<center><b>System ERROR</b><br /><i>system/cron/times/'.$cron_name.'.php</i> must be writable (change permissions to 777)</center>');
		$return = false;
	}
	return $return;
}

/* Times */
$timestamp = time();
$daily_time = strtotime(date('j F Y'));
$hs_time = mktime(date('H'), 0, 0);

/* ---------------Starting Crons------------------ */
$realPath = realpath(dirname(__FILE__));
if(!is_writable($realPath.'/times')){
	die('<center><b>System ERROR</b><br /><i>system/cron/times/</i> directory must be writable (change permissions to 777)</center>');
}


/* Cron 5 minutes */
if(file_exists($realPath.'/times/5min_cron.php')){
	include($realPath.'/times/5min_cron.php');
}

if($cron_5min['time'] < ($timestamp-300)){
	$write = write_cron($timestamp, '5min_cron', 'cron_5min');
	if($write){
		$clear_time = (time()-($site['surf_time_type'] == 1 ? (($site['surf_time']*$site['premium_cpc'])+30) : 120));
		$db->Query("DELETE FROM `module_session` WHERE `timestamp`<'".$clear_time."'");
		$db->Query("DELETE FROM `wrong_logins` WHERE `time`<'".(time()-$site['login_wait_time'])."'");
		$db->Query("UPDATE `banners` SET `expiration`='0' WHERE `expiration`<'".time()."' AND `expiration`!='0'");
	}
}


/* Cron 60 minutes */
if(file_exists($realPath.'/times/1h_cron.php')){
	include($realPath.'/times/1h_cron.php');
}

if($cron_1h['time'] < ($timestamp-3600)){
	$write = write_cron($timestamp, '1h_cron', 'cron_1h');
	if($write){
		$tables = hook_filter('module_tables','');
		$tables = explode('(||)', $tables);

		foreach($tables as $table){
			$z = explode('---', $table);
			if(!empty($z[0])){
				$db->Query("UPDATE ".$z[0]." LEFT JOIN users ON (users.id = ".$z[0].".user AND users.premium = '0') SET ".$z[0].".cpc = '".$site['free_cpc']."' WHERE ".$z[0].".cpc > '".$site['free_cpc']."' AND ".$z[0].".user = users.id");
				$db->Query("DELETE FROM `".$z[0]."` WHERE `user` NOT IN (SELECT `id` FROM `users`) OR `user` IN (SELECT `id` FROM `users` WHERE `banned` = '1')");
			}
			if(!empty($z[0]) && !empty($z[1])){
				$db->Query("DELETE FROM ".$z[1]." WHERE site_id NOT IN (SELECT id FROM ".$z[0].")");
			}
		}
	}
}


/* Daily Cron */
if(file_exists($realPath.'/times/daily_cron.php')){
	include($realPath.'/times/daily_cron.php');
}

if($cron_day['time'] < $daily_time){
	$write = write_cron($daily_time, 'daily_cron', 'cron_day');
	if($write){
		if($cron_day['time'] > 0){
			$db->Query("DELETE FROM `surfed`");
			$db->Query("DELETE FROM `viewed`");
			$db->Query("UPDATE `users` SET `premium`='0' WHERE `premium`>'0' AND `premium`<'".time()."'");
			$db->Query("UPDATE `users` SET `coins`='0' WHERE `coins`<'0'");
			$db->Query("UPDATE `user_clicks` SET `today_clicks`='0' WHERE `today_clicks`>'0'");
		
			// Ad Short Module
			if($db->QueryGetNumRows("SHOW TABLES LIKE 'ad_short_done'")){
				$db->Query("DELETE FROM `ad_short_done`");
			}
			
			// Delete Inactive Users
			if($site['cron_users'] > 0) {
				$del_time = (time() - (86400*$site['cron_users']));
				$db->Query("DELETE FROM `users` WHERE UNIX_TIMESTAMP(`signup`) < '".(time() - 86400)."' AND UNIX_TIMESTAMP(`online`) < '".$del_time."'");
			}
			
			// Update Daily Clicks
			$tables = hook_filter('module_tables','');
			$tables = explode('(||)', $tables);

			foreach($tables as $table){
				$z = explode('---', $table);
				if(!empty($z[0])){
					$db->Query("UPDATE `".$z[0]."` SET `today_clicks`='0' WHERE `today_clicks`>'0'");
				}
			}
		}
	}
}

/* HorseRace Cron */
if(file_exists($realPath.'/times/hs_cron.php')){
	include($realPath.'/times/hs_cron.php');
}

if(($cron_hs['time']+3600) < $hs_time){
	$write = write_cron($hs_time, 'hs_cron', 'cron_hs');
	if($write){
		$hs_round = $db->QueryFetchArray("SELECT * FROM `hs_rounds` WHERE `active`='1' ORDER BY started DESC LIMIT 1");

		if (!empty($hs_round['id']))
		{
			$horses = $db->QueryFetchArrayAll("SELECT `id` FROM `hs_horses` ORDER BY rand()");
			$winner_horse = $horses[0]['id'];

			$hs = '';
			$hsr = 0;
			foreach($horses as $horse){
				$hsr = $hsr + 1;
				$hs .= $horse['id'].($hsr == 5 ? '' : ',');
			}
			
			$db->Query("UPDATE `hs_rounds` SET `horses`='".$hs."', `end_time`='".$hs_time."', `active`='0' WHERE `id`='".$hs_round['id']."'");
			$db->Query("INSERT HIGH_PRIORITY INTO `hs_rounds` (`started`, `end_time`, `active`)VALUES('".$hs_time."', '".($hs_time + 3600)."', 1)");
			
			$win_horse = $db->QueryFetchArray("SELECT * FROM `hs_horses` WHERE `id`='".$winner_horse."'");

			if(!empty($win_horse['id'])){
				$players = unserialize($win_horse['players']);
				
				foreach($players as $player => $key){
					$winner = $db->QueryFetchArray("SELECT * FROM `users` WHERE `id`='".$player."'");

					if(!empty($winner['id'])){
						$won_money = number_format($key['money'] * $win_horse['payment'], 0);

						$db->Query("UPDATE `users` SET `coins`=`coins`+'".$won_money."' WHERE `id`='".$winner['id']."'");
					}
				}
			}
			
			
			$horses = $db->QueryFetchArrayAll("SELECT * FROM `hs_horses`");
			foreach($horses as $horse){
				$new_bet = rand(-2,2);
				$new_change = ($horse['winchance']+($new_bet < 0 ? rand(1,4) : rand(-4,1)));
				$new_change = ($new_change < 5 ? 5 : ($new_change > 45 ? 45 : $new_change));
				$new_bet = ($horse['payment']+($new_bet/10));
				$new_bet = ($new_bet < 1.8 ? 1.8 : ($new_bet > 9 ? 9 : $new_bet));
				
				$db->Query("UPDATE `hs_horses` SET `winchance`='".$new_change."', `payment`='".$new_bet."', `total_tickets`='0', `players`='a:0:{}' WHERE `id`='".$horse['id']."'");
			}
		}
	}
}
?>