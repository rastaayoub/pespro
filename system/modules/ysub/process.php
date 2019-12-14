<?
define('BASEPATH', true);
include('../../config.php');
if(!$is_online){exit;}

function get_subs($data, $type = 0, $first = 0) {
	global $site;
	if($type == 1){
		$data = explode('/', $data);
		$data = $data[4];
	}
	$x = get_data('https://www.googleapis.com/youtube/v3/channels?part=statistics&id='.$data."&key=".$site['yt_api']);
	$x = json_decode($x, true);
	
	if($x['items'][0]['statistics']['hiddenSubscriberCount'] == true){
		$result = ($first == 1 ? 0 : 1);
	}else{
		$result = $x['items'][0]['statistics']['subscriberCount'];
	}
	
	return $result;
}  

if(isset($_POST['get']) && $_POST['pid'] > 0 && !empty($data['id'])){
	$pid = $db->EscapeString($_POST['pid']);
	$sit = $db->QueryFetchArray("SELECT url FROM `ysub` WHERE `id`='".$pid."' LIMIT 1");
	$key = get_subs($sit['url'], 1, 1);

	$result	= $db->Query("INSERT INTO `module_session` (`user_id`,`page_id`,`ses_key`,`module`,`timestamp`)VALUES('".$data['id']."','".$pid."','".$key."','ysub','".time()."') ON DUPLICATE KEY UPDATE `ses_key`='".$key."'");

	if($result){
		echo '1';
	}
}elseif(isset($_POST['step']) && $_POST['step'] == "skip" && is_numeric($_POST['sid']) && !empty($data['id'])) {
	$id = $db->EscapeString($_POST['sid']);
	if($db->QueryGetNumRows("SELECT site_id FROM `ysubed` WHERE `user_id`='".$data['id']."' AND `site_id`='".$id."' LIMIT 1") == 0){
		$db->Query("INSERT INTO `ysubed` (user_id, site_id) VALUES('".$data['id']."', '".$id."')");
		echo '<div class="msg"><div class="info">'.$lang['ysub_16'].'</div></div>';
	}
}elseif(isset($_POST['id']) && !empty($data['id'])){
	$id = $db->EscapeString($_POST['id']);
	$yuser = $db->QueryFetchArray("SELECT a.id,a.user,a.url,a.cpc,b.id AS uid,b.coins FROM ysub a JOIN users b ON b.id = a.user WHERE a.id = '".$id."' LIMIT 1");

	if(empty($yuser['uid']) || empty($yuser['id']) || empty($data['id']) || $yuser['coins'] < $yuser['cpc'] || $yuser['cpc'] < 2){
		echo '5';
	}else{
		if($db->QueryGetNumRows("SELECT site_id FROM `ysubed` WHERE `site_id`='".$id."' AND `user_id`='".$data['id']."' LIMIT 1") == 0){
			$mod_ses = $db->QueryFetchArray("SELECT ses_key FROM `module_session` WHERE `user_id`='".$data['id']."' AND `page_id`='".$yuser['id']."' AND `module`='ysub' LIMIT 1");
			$ses_key = get_subs($yuser['url'], 1);

			$valid = false;
			if($mod_ses['ses_key'] != '' && $ses_key > $mod_ses['ses_key']){
				$valid = true;
			}

			if($valid){    
				$db->Query("UPDATE `users` SET `coins`=`coins`+'".($yuser['cpc']-1)."' WHERE `id`='".$data['id']."'");
				$db->Query("UPDATE `ysub` SET `clicks`=`clicks`+'1', `today_clicks`=`today_clicks`+'1' WHERE `id`='".$id."'");
				$db->Query("UPDATE `users` set `coins`=`coins`-'".$yuser['cpc']."' WHERE `id`='".$yuser['user']."'");
				$db->Query("UPDATE `web_stats` SET `value`=`value`+'1' WHERE `module_id`='ysub'");
				$db->Query("INSERT INTO `ysubed` (user_id, site_id) VALUES('".$data['id']."', '".$yuser['id']."')");
				$db->Query("UPDATE `module_session` SET `ses_key`='".$ses_key."' WHERE (`page_id`='".$sit['id']."' AND `module`='ysub') AND `ses_key`='".($ses_key-1)."'");
				$db->Query("INSERT INTO `user_clicks` (`uid`,`module`,`total_clicks`,`today_clicks`)VALUES('".$data['id']."','ysub','1','1') ON DUPLICATE KEY UPDATE `total_clicks`=`total_clicks`+'1', `today_clicks`=`today_clicks`+'1'");

				echo '1';
			}else{
				echo '0';
			}
		}else{
			echo '5';
		}
	}
}
$db->Close();
?>