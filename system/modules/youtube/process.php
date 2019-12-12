<?
define('BASEPATH', true);
include('../../config.php');
if(!$is_online){exit;}

if(isset($_POST['data'])){
	$id = $db->EscapeString($_POST['data']);
	$sit = $db->QueryFetchArray("SELECT a.id,a.user,a.cpc,b.id AS uid,b.coins FROM youtube a JOIN users b ON b.id = a.user WHERE a.id = '".$id."' LIMIT 1");

	if(!empty($sit['uid']) && !empty($sit['id']) && !empty($data['id']) && $sit['coins'] >= $sit['cpc'] && $sit['cpc'] >= 2){
		$check = $db->QueryGetNumRows("SELECT site_id FROM `viewed` WHERE `user_id`='".$data['id']."' AND `site_id`='".$sit['id']."' LIMIT 1");

		$mod_ses = $db->QueryFetchArray("SELECT ses_key FROM `module_session` WHERE `user_id`='".$data['id']."' AND `page_id`='".$sit['id']."' AND `module`='youtube' LIMIT 1");
		$valid = false;
		if($mod_ses['ses_key'] != '' && ($mod_ses['ses_key']-2) <= time()){
			$valid = true;
		}

		if($valid && $check == 0){
			$db->Query("UPDATE `users` SET `coins`=`coins`+'".($sit['cpc']-1)."' WHERE `id`='".$data['id']."'");
			$db->Query("UPDATE `users` SET `coins`=`coins`-'".$sit['cpc']."' WHERE `id`='".$sit['user']."'");
			$db->Query("UPDATE `youtube` SET `clicks`=`clicks`+'1', `today_clicks`=`today_clicks`+'1' WHERE `id`='".$sit['id']."'");
			$db->Query("UPDATE `web_stats` SET `value`=`value`+'1' WHERE `module_id`='youtube'");
			$db->Query("INSERT INTO `viewed` (user_id, site_id) VALUES('".$data['id']."','".$sit['id']."')");
			$db->Query("INSERT INTO `user_clicks` (`uid`,`module`,`total_clicks`,`today_clicks`)VALUES('".$data['id']."','youtube','1','1') ON DUPLICATE KEY UPDATE `total_clicks`=`total_clicks`+'1', `today_clicks`=`today_clicks`+'1'");

			echo ($sit['cpc']-1);
		}
	}else{
		echo '0';
	}
}
$db->Close();
?>