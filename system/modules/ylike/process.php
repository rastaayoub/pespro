<?
define('BASEPATH', true);
require('../../config.php');
if(!$is_online){exit;}

function get_likes($url){
	$url = get_data('https://gdata.youtube.com/feeds/api/videos/' . $url . '?v=2&fields=yt:rating&alt=json');
	$entry = json_decode($url); 
	return $entry->{'entry'}->{'yt$rating'}->{'numLikes'};
}

if(isset($_POST['get']) && $_POST['pid'] > 0){
	$pid = $db->EscapeString($_POST['pid']);
	$sit = $db->QueryFetchArray("SELECT url FROM `ylike` WHERE `id`='".$pid."' LIMIT 1");
	$key = get_likes($sit['url']);

	$result	= $db->Query("INSERT INTO `module_session` (`user_id`,`page_id`,`ses_key`,`module`,`timestamp`)VALUES('".$data['id']."','".$pid."','".$key."','ylike','".time()."') ON DUPLICATE KEY UPDATE `ses_key`='".$key."'");

	if($result){
		echo '1';
	}
}elseif(isset($_POST['step']) && $_POST['step'] == "skip" && is_numeric($_POST['sid']) && !empty($data['id'])){
	$uid = $db->EscapeString($_POST['sid']);
	if($db->QueryGetNumRows("SELECT site_id FROM `yliked` WHERE `user_id`='".$data['id']."' AND `site_id`='".$uid."' LIMIT 1") == 0){
		$db->Query("INSERT INTO `yliked` (user_id, site_id) VALUES('".$data['id']."', '".$uid."')");
		echo '<div class="msg"><div class="info">'.$lang['ylike_08'].'</div></div>';
	}
}

if(isset($_POST['id'])){
	$uid = $db->EscapeString($_POST['id']);
	$sit = $db->QueryFetchArray("SELECT a.id,a.user,a.url,a.cpc,b.id AS uid,b.coins FROM ylike a JOIN users b ON b.id = a.user WHERE a.id = '".$uid."' LIMIT 1");

	if(empty($sit['uid']) || empty($sit['id']) || empty($data['id']) || $sit['coins'] < $sit['cpc'] || $sit['cpc'] < 2){
		echo '5';
	}else{
		$mod_ses = $db->QueryFetchArray("SELECT ses_key FROM `module_session` WHERE `user_id`='".$data['id']."' AND `page_id`='".$sit['id']."' AND `module`='ylike' LIMIT 1");
		$ses_key = get_likes($sit['url']);

		if($mod_ses['ses_key'] != '' && $ses_key > $mod_ses['ses_key']){
			if($db->QueryGetNumRows("SELECT site_id FROM `yliked` WHERE `site_id`='".$uid."' AND `user_id`='".$data['id']."' LIMIT 1") == 0) {
				$db->Query("UPDATE `users` SET `coins`=`coins`+'".($sit['cpc']-1)."' WHERE `id`='".$data['id']."'");
				$db->Query("UPDATE `users` SET `coins`=`coins`-'".$sit['cpc']."' WHERE `id`='".$sit['user']."'");
				$db->Query("UPDATE `ylike` SET `clicks`=`clicks`+'1', `today_clicks`=`today_clicks`+'1' WHERE `id`='".$uid."'");
				$db->Query("UPDATE `web_stats` SET `value`=`value`+'1' WHERE `module_id`='ylike'");
				$db->Query("INSERT INTO `yliked` (user_id, site_id) VALUES('".$data['id']."', '".$uid."')");
				$db->Query("UPDATE `module_session` SET `ses_key`='".$ses_key."' WHERE (`page_id`='".$sit['id']."' AND `module`='ylike') AND `ses_key`='".($ses_key-1)."'");
				$db->Query("INSERT INTO `user_clicks` (`uid`,`module`,`total_clicks`,`today_clicks`)VALUES('".$data['id']."','ylike','1','1') ON DUPLICATE KEY UPDATE `total_clicks`=`total_clicks`+'1', `today_clicks`=`today_clicks`+'1'");

				echo '1';
			}else{
				echo '5';
			}
		}else{
			echo '0';
		}
	}
}
$db->Close();
?>