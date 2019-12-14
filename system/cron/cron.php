<?
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
?>