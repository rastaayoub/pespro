<?
define('BASEPATH', true);
require('../../config.php');
if(!$is_online){exit;}

function check_fan($rev_id,$rev_user){
	$html = get_data('http://www.reverbnation.com/page_object/page_object_fans/artist_'.$rev_id);
	if(preg_match('| href="/fan/'.$rev_user.'"|', $html)){
		return true;
	}
	return false;
}

if(isset($_POST['get']) && !empty($_POST['rev_user']) && $_POST['pid'] > 0){
	$pid = $db->EscapeString($_POST['pid']);
	
	if($db->QueryGetNumRows("SELECT * FROM `reverbnation` WHERE `id`='".$pid."' LIMIT 1") > 0){
		echo '1';
	}
}elseif(isset($_POST['step']) && $_POST['step'] == "skip" && is_numeric($_POST['sid']) && !empty($data['id'])){
	$uid = $db->EscapeString($_POST['sid']);
	if($db->QueryGetNumRows("SELECT site_id FROM `reverbnation_done` WHERE `user_id`='".$data['id']."' AND `site_id`='".$uid."' LIMIT 1") == 0){
		$db->Query("INSERT INTO `reverbnation_done` (user_id, site_id) VALUES('".$data['id']."', '".$uid."')");
		echo '<div class="msg"><div class="info">'.$lang['reverbnation_08'].'</div></div>';
	}
}

if(isset($_POST['id']) && !empty($_POST['rev_user'])){
	$uid = $db->EscapeString($_POST['id']);
	$sit = $db->QueryFetchArray("SELECT a.id,a.user,a.artist_id,a.cpc,b.id AS uid,b.coins FROM reverbnation a JOIN users b ON b.id = a.user WHERE a.id = '".$uid."' LIMIT 1");

	if(empty($sit['uid']) || empty($sit['id']) || empty($data['id']) || $sit['coins'] < $sit['cpc'] || $sit['cpc'] < 2){
		echo '5';
	}else{
		if(check_fan($sit['artist_id'],$_POST['rev_user'])){
			if($db->QueryGetNumRows("SELECT site_id FROM `reverbnation_done` WHERE `site_id`='".$uid."' AND `user_id`='".$data['id']."' LIMIT 1") == 0) {
				$db->Query("UPDATE `users` SET `coins`=`coins`+'".($sit['cpc']-1)."' WHERE `id`='".$data['id']."'");
				$db->Query("UPDATE `users` SET `coins`=`coins`-'".$sit['cpc']."' WHERE `id`='".$sit['user']."'");
				$db->Query("UPDATE `reverbnation` SET `clicks`=`clicks`+'1', `today_clicks`=`today_clicks`+'1' WHERE `id`='".$uid."'");
				$db->Query("UPDATE `web_stats` SET `value`=`value`+'1' WHERE `module_id`='reverbnation'");
				$db->Query("INSERT INTO `reverbnation_done` (user_id, site_id) VALUES('".$data['id']."', '".$uid."')");
				$db->Query("INSERT INTO `user_clicks` (`uid`,`module`,`total_clicks`,`today_clicks`)VALUES('".$data['id']."','reverbnation','1','1') ON DUPLICATE KEY UPDATE `total_clicks`=`total_clicks`+'1', `today_clicks`=`today_clicks`+'1'");

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