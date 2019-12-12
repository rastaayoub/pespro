<?
define('BASEPATH', true);
include('../../config.php');
include('../../libs/TwitterAPIExchange.php');
if(!$is_online){exit;}

$settings = array(
    'oauth_access_token' => $site['twitter_token'],
    'oauth_access_token_secret' => $site['twitter_token_secret'],
    'consumer_key' => $site['twitter_consumer_key'],
    'consumer_secret' => $site['twitter_consumer_secret']
);

if(isset($_POST['get']) && $_POST['pid'] > 0){
	$pid = $db->EscapeString($_POST['pid']);
	$sit = $db->QueryFetchArray("SELECT url FROM `twitter` WHERE `id`='".$pid."' LIMIT 1");

	$url = 'https://api.twitter.com/1.1/users/show.json';
	$twitter = new TwitterAPIExchange($settings);
	$key = $twitter->setGetfield('?screen_name='.$sit['url'])
				 ->buildOauth($url, 'GET')
				 ->performRequest();
	$key = json_decode($key, true);
	$key = $key['followers_count'];

	$result	= $db->Query("INSERT INTO `module_session` (`user_id`,`page_id`,`ses_key`,`module`,`timestamp`)VALUES('".$data['id']."','".$pid."','".$key."','twitter','".time()."') ON DUPLICATE KEY UPDATE `ses_key`='".$key."'");

	$msg = ($result ? '<div class="msg"><div class="info">'.$lang['twt_13'].'</div></div>' : '<div class="msg"><div class="error">We cannot contact Twitter...</div></div>');
	$type = ($result ? 'success' : 'error');

	$resultData = array('message' => $msg, 'type' => $type);

	header('Content-type: application/json');
	echo json_encode($resultData);
}elseif(isset($_POST['step']) && $_POST['step'] == "skip" && is_numeric($_POST['sid']) && !empty($data['id'])){
	$id = $db->EscapeString($_POST['sid']);
	if($db->QueryGetNumRows("SELECT site_id FROM `followed` WHERE `user_id`='".$data['id']."' AND `site_id`='".$id."' LIMIT 1") == 0){
		$db->Query("INSERT INTO `followed` (user_id, site_id) VALUES('".$data['id']."', '".$id."')");
		echo '<div class="msg"><div class="info">'.$lang['b_359'].'</div></div>';
	}
}elseif(isset($_POST['id']) && !empty($data['id'])){
	$uid = $db->EscapeString($_POST['id']);
	$sit = $db->QueryFetchArray("SELECT a.id,a.user,a.url,a.cpc,b.id AS uid,b.coins FROM twitter a JOIN users b ON b.id = a.user WHERE a.id = '".$uid."' LIMIT 1");

	if(empty($sit['uid']) || empty($sit['id']) || empty($data['id']) || $sit['coins'] < $sit['cpc'] || $sit['cpc'] < 2){
		$msg = '<div class="msg"><div class="error">'.$lang['b_300'].'</div></div>';
		$type = 'not_available';
	}else{
		$mod_ses = $db->QueryFetchArray("SELECT ses_key FROM `module_session` WHERE `user_id`='".$data['id']."' AND `page_id`='".$sit['id']."' AND `module`='twitter' LIMIT 1");

		$url = 'https://api.twitter.com/1.1/users/show.json';
		$twitter = new TwitterAPIExchange($settings);
		$followers = $twitter->setGetfield('?screen_name='.$sit['url'])
					 ->buildOauth($url, 'GET')
					 ->performRequest();
		$followers = json_decode($followers, true);

		if($mod_ses['ses_key'] != '' && $followers['followers_count'] > $mod_ses['ses_key']){
			if ($db->QueryGetNumRows("SELECT site_id FROM `followed` WHERE `site_id`='".$uid."' AND `user_id`='".$data['id']."' LIMIT 1") == 0) {
				$db->Query("UPDATE `users` SET `coins`=`coins`+'".($sit['cpc']-1)."' WHERE `id`='".$data['id']."'");
				$db->Query("UPDATE `users` SET `coins`=`coins`-'".$sit['cpc']."' WHERE `id`='".$sit['user']."'");
				$db->Query("UPDATE `twitter` SET `clicks`=`clicks`+'1', `today_clicks`=`today_clicks`+'1' WHERE `id`='".$uid."'");
				$db->Query("UPDATE `web_stats` SET `value`=`value`+'1' WHERE `module_id`='twitter'");
				$db->Query("INSERT INTO `followed` (user_id, site_id) VALUES('".$data['id']."', '".$uid."')");
				$db->Query("UPDATE `module_session` SET `ses_key`='".$followers['followers_count']."' WHERE (`page_id`='".$sit['id']."' AND `module`='twitter') AND `ses_key`='".($followers['followers_count']-1)."'");
				$db->Query("INSERT INTO `user_clicks` (`uid`,`module`,`total_clicks`,`today_clicks`)VALUES('".$data['id']."','twitter','1','1') ON DUPLICATE KEY UPDATE `total_clicks`=`total_clicks`+'1', `today_clicks`=`today_clicks`+'1'");

				$msg = '<div class="msg"><div class="success">'.lang_rep($lang['b_358'], array('-NUM-' => ($sit['cpc']-1))).'</div></div>';
				$type = 'success';
			}else{
				$msg = '<div class="msg"><div class="error">'.$lang['b_300'].'</div></div>';
				$type = 'not_available';
			}
		}else{
			$msg = '<div class="msg"><div class="error">'.$lang['twt_17'].'</div></div>';
			$type = 'error';
		}
	}

	$resultData = array('message' => $msg, 'type' => $type);

	header('Content-type: application/json');
	echo json_encode($resultData);
}
$db->Close();
?>