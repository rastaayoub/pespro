<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$url = $db->EscapeString($_POST['url']);

if(empty($url)){
	$msg = '<div class="msg"><div class="error">'.$lang['b_25'].'</div></div>';
}else{
	include('system/libs/TwitterAPIExchange.php');
	$settings = array(
		'oauth_access_token' => $site['twitter_token'],
		'oauth_access_token_secret' => $site['twitter_token_secret'],
		'consumer_key' => $site['twitter_consumer_key'],
		'consumer_secret' => $site['twitter_consumer_secret']
	);
	
	$tw_url = 'https://api.twitter.com/1.1/users/show.json';
	$getfield = '?screen_name='.$url;
	$requestMethod = 'GET';
	$twitter = new TwitterAPIExchange($settings);
	$twitter = $twitter->setGetfield($getfield)
				 ->buildOauth($tw_url, $requestMethod)
				 ->performRequest();
	$twitter = json_decode($twitter, true);

	if($db->QueryGetNumRows("SELECT id FROM `twitter` WHERE `url`='".$twitter['screen_name']."'") > 0){
		$msg = '<div class="msg"><div class="error">'.$lang['twt_03'].'</div></div>';
	}elseif(empty($twitter['id']) || empty($twitter['screen_name'])){
		$msg = '<div class="msg"><div class="error">'.$lang['twt_06'].'</div></div>';
	}elseif($twitter['protected'] == 1){
		$msg = '<div class="msg"><div class="error">'.$lang['twt_20'].'</div></div>';
	}else{
		$db->Query("INSERT INTO `twitter` (user, url, title, t_img, t_id, max_clicks, daily_clicks, cpc, country, sex) VALUES('".$data['id']."', '".$twitter['screen_name']."', '".$twitter['name']."', '".$twitter['profile_image_url_https']."', '".$twitter['id']."', '".$max_clicks."', '".$daily_clicks."', '".$cpc."', '".$country."', '".$gender."') ");
		$msg = '<div class="msg"><div class="success">'.$lang['twt_04'].'</div></div>';
		$error = 0;
	}
}
?>