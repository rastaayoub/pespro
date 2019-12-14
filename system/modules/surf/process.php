<?
define('BASEPATH', true);
include('../../config.php');
if(!$is_online){exit;}

if($site['surf_type'] == 2){
	if(isset($_POST['get']) && !empty($data['id'])){
		if($site['target_system'] != 2){
			$dbt_value = " AND (a.country = '0' OR FIND_IN_SET('".$data['country']."', a.country)) AND (a.sex = '".$data['sex']."' OR a.sex = '0')";
		}
		$sit = $db->QueryFetchArray("SELECT a.id, a.url, a.title, a.cpc, b.premium FROM surf a LEFT JOIN users b ON b.id = a.user LEFT JOIN surfed c ON c.user_id = '".$data['id']."' AND c.site_id = a.id WHERE a.active = '0' AND (a.max_clicks > a.clicks OR a.max_clicks = '0') AND (a.daily_clicks > a.today_clicks OR a.daily_clicks = '0') AND (b.coins >= a.cpc AND a.cpc >= '2') AND (c.site_id IS NULL AND a.user !='".$data['id']."')".$dbt_value." ORDER BY a.cpc DESC, b.premium DESC, RAND() LIMIT 1");

		if($sit['id'] > 0){
			$surf_time = $site['surf_time'];
			if($site['surf_time_type'] == 1){
				$surf_time = ($site['surf_time']*($sit['cpc']-1));
			}
			$key = ($surf_time+time());
			$result	= $db->Query("INSERT INTO `module_session` (`user_id`,`page_id`,`ses_key`,`module`,`timestamp`)VALUES('".$data['id']."','".$sit['id']."','".$key."','surf','".time()."') ON DUPLICATE KEY UPDATE `ses_key`='".$key."'");

			$arr = array('url' => $sit['url'], 'cpc' => ($sit['cpc']-1), 'sid' => $sit['id'], 'title' => $sit['title'], 'eurl' => base64_encode($sit['url']), 'time' => $surf_time);
			echo json_encode($arr);
		}else{
			echo 'NO_SITE';
		}
	}elseif(isset($_POST['complete']) && isset($_POST['sid']) && !empty($data['id'])){
		$sid = $db->EscapeString($_POST['sid']);
		$sit = $db->QueryFetchArray("SELECT a.id,a.user,a.cpc,b.id AS uid,b.coins FROM surf a JOIN users b ON b.id = a.user WHERE a.id = '".$sid."' LIMIT 1");

		if(!empty($sit['uid']) && !empty($sit['id']) && !empty($data['id']) && $sit['coins'] >= $sit['cpc'] && $sit['cpc'] >= 2){
			$check = $db->QueryGetNumRows("SELECT * FROM `surfed` WHERE `user_id`='".$data['id']."' AND `site_id`='".$sid."' LIMIT 1");

			$mod_ses = $db->QueryFetchArray("SELECT ses_key FROM `module_session` WHERE `user_id`='".$data['id']."' AND `page_id`='".$sit['id']."' AND `module`='surf' LIMIT 1");
			$valid = false;
			if($mod_ses['ses_key'] != '' && ($mod_ses['ses_key']-5) <= time()){
				$valid = true;
			}

			if($valid && $check == 0){
				$db->Query("UPDATE `users` SET `coins`=`coins`+'".($sit['cpc']-1)."' WHERE `id`='".$data['id']."'");
				$db->Query("UPDATE `users` SET `coins`=`coins`-'".$sit['cpc']."' WHERE `id`='".$sit['user']."'");
				$db->Query("UPDATE `surf` SET `clicks`=`clicks`+'1', `today_clicks`=`today_clicks`+'1' WHERE `id`='".$sit['id']."'");
				$db->Query("UPDATE `web_stats` SET `value`=`value`+'1' WHERE `module_id`='surf'");
				$db->Query("INSERT INTO `surfed` (user_id, site_id) VALUES('".$data['id']."', '".$sit['id']."')");
				$db->Query("INSERT INTO `user_clicks` (`uid`,`module`,`total_clicks`,`today_clicks`)VALUES('".$data['id']."','surf','1','1') ON DUPLICATE KEY UPDATE `total_clicks`=`total_clicks`+'1', `today_clicks`=`today_clicks`+'1'");
			}
		}
		echo '1';
	}
}else{
	if(isset($_GET['step']) && $_GET['step'] == "skip" && $_GET['id'] != '' && is_numeric($_GET['id'])){
		$id = $db->EscapeString($_GET['id']);
		if($db->QueryGetNumRows("SELECT * FROM `surfed` WHERE `user_id`='".$data['id']."' AND `site_id`='".$id."' LIMIT 1") == 0){
			$db->Query("INSERT INTO `surfed` (user_id, site_id) VALUES('".$data['id']."', '".$id."')");
			echo '<div class="msg"><div class="info">'.$lang['surf_06'].'</div></div>';
		}
	}

	if(isset($_POST['data'])){
		$sid = $db->EscapeString($_POST['data']);
		$sit = $db->QueryFetchArray("SELECT a.id,a.user,a.cpc,b.id AS uid,b.coins FROM surf a JOIN users b ON b.id = a.user WHERE a.id = '".$sid."' LIMIT 1");

		if(empty($sit['uid']) || empty($sit['id']) || empty($data['id']) || $sit['coins'] < $sit['cpc'] || $sit['cpc'] < 2){
			echo '<div class="errormsg">'.$lang['b_300'].'</div>';
		}else{
			$check = $db->QueryGetNumRows("SELECT * FROM `surfed` WHERE `user_id`='".$data['id']."' AND `site_id`='".$sid."' LIMIT 1");
				
			$mod_ses = $db->QueryFetchArray("SELECT ses_key FROM `module_session` WHERE `user_id`='".$data['id']."' AND `page_id`='".$sit['id']."' AND `module`='surf' LIMIT 1");
			$valid = false;
			if($mod_ses['ses_key'] != '' && ($mod_ses['ses_key']-2) <= time()){
				$valid = true;
			}
			
			if($valid && $check == 0){
				$db->Query("UPDATE `users` SET `coins`=`coins`+'".($sit['cpc']-1)."' WHERE `id`='".$data['id']."'");
				$db->Query("UPDATE `users` SET `coins`=`coins`-'".$sit['cpc']."' WHERE `id`='".$sit['user']."'");
				$db->Query("UPDATE `surf` SET `clicks`=`clicks`+'1', `today_clicks`=`today_clicks`+'1' WHERE `id`='".$sit['id']."'");
				$db->Query("UPDATE `web_stats` SET `value`=`value`+'1' WHERE `module_id`='surf'");
				$db->Query("INSERT INTO `surfed` (user_id, site_id) VALUES('".$data['id']."', '".$sit['id']."')");
				$db->Query("INSERT INTO `user_clicks` (`uid`,`module`,`total_clicks`,`today_clicks`)VALUES('".$data['id']."','surf','1','1') ON DUPLICATE KEY UPDATE `total_clicks`=`total_clicks`+'1', `today_clicks`=`today_clicks`+'1'");

				if(isset($_POST['cpc'])){
					echo '<div class="msg">'.lang_rep($lang['surf_08'], array('-NUM-' => ($sit['cpc']-1))).'</div>';
				}
			}elseif(isset($_POST['cpc'])){
				echo '<div class="errormsg">'.$lang['surf_07'].'</div>';
			}
		}
	}
}
$db->Close();
?>