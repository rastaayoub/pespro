<?
define('BASEPATH', true);
include('../../config.php');
if(!$is_online){exit;}

if(isset($_POST['step']) && $_POST['step'] == "skip" && is_numeric($_POST['sid']) && !empty($data['id'])){
	$id = $db->EscapeString($_POST['sid']);
	if($db->QueryGetNumRows("SELECT site_id FROM `retweeted` WHERE `user_id`='".$data['id']."' AND `site_id`='".$id."' LIMIT 1") == 0){
		$db->Query("INSERT INTO `retweeted` (user_id, site_id) VALUES('".$data['id']."', '".$id."')");
		echo '<div class="msg"><div class="info">'.$lang['retwt_03'].'</div></div>';
	}
}

if(isset($_POST['id'])){
	$id = $db->EscapeString($_POST['id']);
	$sit = $db->QueryFetchArray("SELECT a.id,a.user,a.cpc,b.id AS uid,b.coins FROM retweet a JOIN users b ON b.id = a.user WHERE a.id = '".$id."' LIMIT 1");

	if(empty($sit['uid']) || empty($sit['id']) || empty($data['id']) || $sit['coins'] < $sit['cpc'] || $sit['cpc'] < 2){
		echo '<div class="msg"><div class="error">'.$lang['b_300'].'</div></div>';
	}else{
		if($db->QueryGetNumRows("SELECT site_id FROM `retweeted` WHERE `user_id`='".$data['id']."' AND `site_id`='".$sit['id']."' LIMIT 1") == 0){
			$db->Query("UPDATE `users` SET `coins`=`coins`+'".($sit['cpc']-1)."' WHERE `id`='".$data['id']."'");
			$db->Query("UPDATE `users` SET `coins`=`coins`-'".$sit['cpc']."' WHERE `id`='".$sit['user']."'");
			$db->Query("UPDATE `retweet` SET `clicks`=`clicks`+'1', `today_clicks`=`today_clicks`+'1' WHERE `id`='".$id."'");
			$db->Query("UPDATE `web_stats` SET `value`=`value`+'1' WHERE `module_id`='retweet'");
			$db->Query("INSERT INTO `retweeted` (user_id, site_id) VALUES('".$data['id']."','".$sit['id']."')");
			$db->Query("INSERT INTO `user_clicks` (`uid`,`module`,`total_clicks`,`today_clicks`)VALUES('".$data['id']."','retweet','1','1') ON DUPLICATE KEY UPDATE `total_clicks`=`total_clicks`+'1', `today_clicks`=`today_clicks`+'1'");

			echo '<div class="msg"><div class="success">'.lang_rep($lang['retwt_05'], array('-NUM-' => ($sit['cpc']-1))).'</div></div>';
		}else{
			echo '<div class="msg"><div class="error">'.$lang['retwt_04'].'</div></div>';
		}
	}
}
$db->Close();
?>