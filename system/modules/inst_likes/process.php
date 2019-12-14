<?php
define('BASEPATH', true);
require('../../config.php');
if(!$is_online){exit;}

function get_likes($url){
	$get = strip_tags(get_data($url));
	preg_match('/window._sharedData = (.*?)};/i', $get, $match);
	$json = json_decode($match[1].'}', true);
	$result = $json['entry_data']['PostPage'][0]['graphql']['shortcode_media']['edge_media_preview_like']['count'];

	if(empty($result)) {
		$result = 0;
	}

	return $result;
}

if(isset($_POST['get']) && $_POST['pid'] > 0){
	$pid = $db->EscapeString($_POST['pid']);
	$sit = $db->QueryFetchArray("SELECT url FROM `inst_likes` WHERE `id`='".$pid."'");
	$key = get_likes($sit['url']);

	$result	= $db->Query("INSERT INTO `module_session` (`user_id`,`page_id`,`ses_key`,`module`,`timestamp`)VALUES('".$data['id']."','".$pid."','".$key."','inst_likes','".time()."') ON DUPLICATE KEY UPDATE `ses_key`='".$key."'");

	$msg = ($result ? '<div class="msg"><div class="info">'.$lang['inst_likes_08'].'</div></div>' : '<div class="msg"><div class="error">'.$lang['inst_likes_09'].'</div></div>');
	$type = ($result ? 'success' : 'error');

	$resultData = array('message' => $msg, 'type' => $type);

	header('Content-type: application/json');
	echo json_encode($resultData);
}elseif(isset($_POST['step']) && $_POST['step'] == "skip" && is_numeric($_POST['sid']) && !empty($data['id'])){
	$id = $db->EscapeString($_POST['sid']);

	if($db->QueryGetNumRows("SELECT site_id FROM `inst_liked` WHERE `user_id`='".$data['id']."' AND `site_id`='".$id."' LIMIT 1") == 0){
		$db->Query("INSERT INTO `inst_liked` (user_id, site_id) VALUES('".$data['id']."', '".$id."')");
		echo '<div class="msg"><div class="info">'.$lang['b_359'].'</div></div>';
	}
}

if(isset($_POST['id'])){
	$uid = $db->EscapeString($_POST['id']);
	$sit = $db->QueryFetchArray("SELECT a.id,a.user,a.url,a.cpc,b.id AS uid,b.coins FROM inst_likes a JOIN users b ON b.id = a.user WHERE a.id = '".$uid."' LIMIT 1");

	if(empty($sit['uid']) || empty($sit['id']) || empty($data['id']) || $sit['coins'] < $sit['cpc'] || $sit['cpc'] < 2){
		$msg = '<div class="msg"><div class="error">'.$lang['b_300'].'</div></div>';
		$type = 'not_available';
	}else{
		$mod_ses = $db->QueryFetchArray("SELECT ses_key FROM `module_session` WHERE `user_id`='".$data['id']."' AND `page_id`='".$sit['id']."' AND `module`='inst_likes'");
		$ses_key = get_likes($sit['url']);
		
		$valid = false;
		if($mod_ses['ses_key'] != '' && $ses_key > $mod_ses['ses_key']){
			$valid = true;
		}
		
		if($valid){	
			if($db->QueryGetNumRows("SELECT site_id FROM `inst_liked` WHERE `site_id`='".$uid."' AND `user_id`='".$data['id']."'") == 0) {
				$db->Query("UPDATE `users` SET `coins`=`coins`+'".($sit['cpc']-1)."' WHERE `id`='".$data['id']."'");
				$db->Query("UPDATE `users` SET `coins`=`coins`-'".$sit['cpc']."' WHERE `id`='".$sit['user']."'");
				$db->Query("UPDATE `inst_likes` SET `clicks`=`clicks`+'1', `today_clicks`=`today_clicks`+'1' WHERE `id`='".$uid."'");
				$db->Query("UPDATE `web_stats` SET `value`=`value`+'1' WHERE `module_id`='inst_likes'");
				$db->Query("INSERT INTO `inst_liked` (user_id, site_id) VALUES('".$data['id']."', '".$uid."')");
				$db->Query("UPDATE `module_session` SET `ses_key`='".$ses_key."' WHERE (`page_id`='".$sit['id']."' AND `module`='inst_likes') AND `ses_key`='".($ses_key-1)."'");
				$db->Query("INSERT INTO `user_clicks` (`uid`,`module`,`total_clicks`,`today_clicks`)VALUES('".$data['id']."','inst_likes','1','1') ON DUPLICATE KEY UPDATE `total_clicks`=`total_clicks`+'1', `today_clicks`=`today_clicks`+'1'");

				$msg = '<div class="msg"><div class="success">'.lang_rep($lang['b_358'], array('-NUM-' => ($sit['cpc']-1))).'</div></div>';
				$type = 'success';
			}else{
				$msg = '<div class="msg"><div class="error">'.$lang['b_300'].'</div></div>';
				$type = 'not_available';
			}
		}else{
			$msg = '<div class="msg"><div class="error">'.$lang['inst_likes_12'].'</div></div>';
			$type = 'error';
		}
	}

	$resultData = array('message' => $msg, 'type' => $type);

	header('Content-type: application/json');
	echo json_encode($resultData);
}
$db->Close();
?>