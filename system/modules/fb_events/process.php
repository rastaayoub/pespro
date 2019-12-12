<?php
define('BASEPATH', true);
require('../../config.php');
if(!$is_online){ exit; }

function get_joins($id){
    global $site;
    $eventData = get_data('https://graph.facebook.com/'.$id.'?fields=attending_count&access_token='.$site['fb_app_id'].'|'.$site['fb_app_secret']);
    $eventData = json_decode($eventData, true);
    return (empty($eventData['attending_count']) ? '0' : $eventData['attending_count']);
}

if(isset($_POST['get']) && $_POST['pid'] > 0){
	$pid = $db->EscapeString($_POST['pid']);
	$sit = $db->QueryFetchArray("SELECT url FROM `fb_event` WHERE `id`='".$pid."'");
	$key = get_joins($sit['url']);

	$result	= $db->Query("INSERT INTO `module_session` (`user_id`,`page_id`,`ses_key`,`module`,`timestamp`)VALUES('".$data['id']."','".$pid."','".$key."','fb_event','".time()."') ON DUPLICATE KEY UPDATE `ses_key`='".$key."'");

	$msg = ($result ? '<div class="msg"><div class="info">'.$lang['fb_10'].'</div></div>' : '<div class="msg"><div class="error">'.$lang['fb_08'].'</div></div>');
	$type = ($result ? 'success' : 'error');

	$resultData = array('message' => $msg, 'type' => $type);

	header('Content-type: application/json');
	echo json_encode($resultData);
}elseif(isset($_POST['step']) && $_POST['step'] == "skip" && is_numeric($_POST['sid']) && !empty($data['id'])){
	$id = $db->EscapeString($_POST['sid']);

	if($db->QueryGetNumRows("SELECT site_id FROM `fbe_joined` WHERE `user_id`='".$data['id']."' AND `site_id`='".$id."' LIMIT 1") == 0){
		$db->Query("INSERT INTO `fbe_joined` (user_id, site_id) VALUES('".$data['id']."', '".$id."')");
		echo '<div class="msg"><div class="info">'.$lang['b_359'].'</div></div>';
	}
}

if(isset($_POST['id'])){
	$id = $db->EscapeString($_POST['id']);
	$sit = $db->QueryFetchArray("SELECT a.id,a.user,a.url,a.cpc,b.id AS uid,b.coins FROM fb_event a JOIN users b ON b.id = a.user WHERE a.id = '".$id."' LIMIT 1");
	
	if(empty($sit['uid']) || empty($sit['id']) || empty($data['id']) || $sit['coins'] < $sit['cpc'] || $sit['cpc'] < 2){
		$msg = '<div class="msg"><div class="error">'.$lang['b_300'].'</div></div>';
		$type = 'not_available';
	}else{
		$mod_ses = $db->QueryFetchArray("SELECT ses_key FROM `module_session` WHERE `user_id`='".$data['id']."' AND `page_id`='".$sit['id']."' AND `module`='fb_event'");
		$ses_key = get_joins($sit['url']);

		if($mod_ses['ses_key'] != '' && $ses_key > $mod_ses['ses_key']){
			$check = $db->QueryGetNumRows("SELECT site_id FROM `fbe_joined` WHERE `user_id`='".$data['id']."' AND `site_id`='".$sit['id']."'");

			if($check == 0){
				$db->Query("UPDATE `users` SET `coins`=`coins`+'".($sit['cpc']-1)."' WHERE `id`='".$data['id']."'");
				$db->Query("UPDATE `users` SET `coins`=`coins`-'".$sit['cpc']."' WHERE `id`='".$sit['user']."'");
				$db->Query("UPDATE `fb_event` SET `clicks`=`clicks`+'1', `today_clicks`=`today_clicks`+'1' WHERE `id`='".$id."'");
				$db->Query("UPDATE `web_stats` SET `value`=`value`+'1' WHERE `module_id`='fb_event'");
				$db->Query("INSERT INTO `fbe_joined` (user_id, site_id) VALUES('".$data['id']."','".$sit['id']."')");
				$db->Query("UPDATE `module_session` SET `ses_key`='".$ses_key."' WHERE (`page_id`='".$sit['id']."' AND `module`='fb_event') AND `ses_key`='".($ses_key-1)."'");
				$db->Query("INSERT INTO `user_clicks` (`uid`,`module`,`total_clicks`,`today_clicks`)VALUES('".$data['id']."','fb_event','1','1') ON DUPLICATE KEY UPDATE `total_clicks`=`total_clicks`+'1', `today_clicks`=`today_clicks`+'1'");

				$msg = '<div class="msg"><div class="success">'.lang_rep($lang['b_358'], array('-NUM-' => ($sit['cpc']-1))).'</div></div>';
				$type = 'success';
			}else{
				$msg = '<div class="msg"><div class="error">'.$lang['b_300'].'</div></div>';
				$type = 'not_available';
			}
		}else{
			$msg = '<div class="msg"><div class="error">'.$lang['fb_03'].'</div></div>';
			$type = 'error';
		}
	}

	$resultData = array('message' => $msg, 'type' => $type);

	header('Content-type: application/json');
	echo json_encode($resultData);
}

$db->Close();
?>