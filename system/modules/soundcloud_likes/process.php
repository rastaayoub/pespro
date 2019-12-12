<?php
define('BASEPATH', true);
require('../../config.php');
if(!$is_online){exit;}

function getCount($url){
	global $site;
	
	$options = array(
		CURLOPT_URL => 'http://api.soundcloud.com/tracks/'.$url.'?client_id='.$site['scf_api'],
		CURLOPT_USERAGENT => 'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_BINARYTRANSFER => true,
		CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
		CURLOPT_TIMEOUT => 5
	);

	$url = get_data('', 5, 'NO_HEADER', $options);
	$return = json_decode($url, true);
	return $return['favoritings_count'];
}

if(isset($_POST['get']) && $_POST['pid'] > 0){
	$pid = $db->EscapeString($_POST['pid']);
	$sit = $db->QueryFetchArray("SELECT track_id FROM `soundcloud_likes` WHERE `id`='".$pid."' LIMIT 1");
	$key = getCount($sit['track_id']);

	$return	= $db->Query("INSERT INTO `module_session` (`user_id`,`page_id`,`ses_key`,`module`,`timestamp`)VALUES('".$data['id']."','".$pid."','".$key."','soundcloud_likes','".time()."') ON DUPLICATE KEY UPDATE `ses_key`='".$key."'");

	$msg = ($return ? '<div class="msg"><div class="info">'.$lang['soundcloud_likes_06'].'</div></div>' : '<div class="msg"><div class="error">'.$lang['soundcloud_likes_07'].'</div></div>');
	$type = ($return ? 'success' : 'error');

	$resultData = array('message' => $msg, 'type' => $type);

	header('Content-type: application/json');
	echo json_encode($resultData);
}elseif(isset($_POST['step']) && $_POST['step'] == "skip" && is_numeric($_POST['sid']) && !empty($data['id'])){
	$uid = $db->EscapeString($_POST['sid']);
	if($db->QueryGetNumRows("SELECT site_id FROM `soundcloud_liked` WHERE `user_id`='".$data['id']."' AND `site_id`='".$uid."' LIMIT 1") == 0){
		$db->Query("INSERT INTO `soundcloud_liked` (user_id, site_id) VALUES('".$data['id']."', '".$uid."')");
		echo '<div class="msg"><div class="info">'.$lang['b_359'].'</div></div>';
	}
}

if(isset($_POST['id'])){
	$uid = $db->EscapeString($_POST['id']);
	$sit = $db->QueryFetchArray("SELECT a.id,a.user,a.track_id,a.cpc,b.id AS uid,b.coins FROM soundcloud_likes a JOIN users b ON b.id = a.user WHERE a.id = '".$uid."' LIMIT 1");

	if(empty($sit['uid']) || empty($sit['id']) || empty($data['id']) || $sit['coins'] < $sit['cpc'] || $sit['cpc'] < 2){
		$msg = '<div class="msg"><div class="error">'.$lang['b_300'].'</div></div>';
		$type = 'not_available';
	}else{
		$mod_ses = $db->QueryFetchArray("SELECT ses_key FROM `module_session` WHERE `user_id`='".$data['id']."' AND `page_id`='".$sit['id']."' AND `module`='soundcloud_likes'");
		$ses_key = getCount($sit['track_id']);

		if($mod_ses['ses_key'] != '' && $ses_key > $mod_ses['ses_key']){
			if($db->QueryGetNumRows("SELECT site_id FROM `soundcloud_liked` WHERE `site_id`='".$uid."' AND `user_id`='".$data['id']."' LIMIT 1") == 0) {
				$db->Query("UPDATE `users` SET `coins`=`coins`+'".($sit['cpc']-1)."' WHERE `id`='".$data['id']."'");
				$db->Query("UPDATE `users` SET `coins`=`coins`-'".$sit['cpc']."' WHERE `id`='".$sit['user']."'");
				$db->Query("UPDATE `soundcloud_likes` SET `clicks`=`clicks`+'1', `today_clicks`=`today_clicks`+'1' WHERE `id`='".$uid."'");
				$db->Query("UPDATE `web_stats` SET `value`=`value`+'1' WHERE `module_id`='soundcloud_likes'");
				$db->Query("INSERT INTO `soundcloud_liked` (user_id, site_id) VALUES('".$data['id']."', '".$uid."')");
				$db->Query("UPDATE `module_session` SET `ses_key`='".$ses_key."' WHERE (`page_id`='".$sit['id']."' AND `module`='soundcloud_likes') AND `ses_key`='".($ses_key-1)."'");
				$db->Query("INSERT INTO `user_clicks` (`uid`,`module`,`total_clicks`,`today_clicks`)VALUES('".$data['id']."','soundcloud_likes','1','1') ON DUPLICATE KEY UPDATE `total_clicks`=`total_clicks`+'1', `today_clicks`=`today_clicks`+'1'");

				$msg = '<div class="msg"><div class="success">'.lang_rep($lang['b_358'], array('-NUM-' => ($sit['cpc']-1))).'</div></div>';
				$type = 'success';
			}else{
				$msg = '<div class="msg"><div class="error">'.$lang['b_300'].'</div></div>';
				$type = 'not_available';
			}
		}else{
			$msg = '<div class="msg"><div class="error">'.$lang['soundcloud_likes_08'].'</div></div>';
			$type = 'error';
		}
	}

	$resultData = array('message' => $msg, 'type' => $type);

	header('Content-type: application/json');
	echo json_encode($resultData);
}

$db->Close();
?>