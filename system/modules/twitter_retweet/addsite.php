<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$url = $db->EscapeString($_POST['url']);
$title = $db->EscapeString($_POST['title']);

if(empty($url) || empty($title)){
	$msg = '<div class="msg"><div class="error">'.$lang['b_25'].'</div></div>';
}else{
	include('system/libs/TwitterAPIExchange.php');
	$settings = array(
		'oauth_access_token' => $site['twitter_token'],
		'oauth_access_token_secret' => $site['twitter_token_secret'],
		'consumer_key' => $site['twitter_consumer_key'],
		'consumer_secret' => $site['twitter_consumer_secret']
	);
	
	$tw_url = 'https://api.twitter.com/1.1/statuses/lookup.json';
	$twitter = new TwitterAPIExchange($settings);
	$twitter = $twitter->setGetfield('?id='.$url)
				 ->buildOauth($tw_url, 'GET')
				 ->performRequest();
	$twitter = json_decode($twitter, true);

	if($db->QueryGetNumRows("SELECT id FROM `twitter_retweet` WHERE `tweet_id`='".$twitter[0]['id']."'") > 0){
		$msg = '<div class="msg"><div class="error">'.$lang['twitter_02'].'</div></div>';
	}elseif(empty($twitter[0]['id']) || empty($twitter[0]['user']['screen_name'])){
		$msg = '<div class="msg"><div class="error">'.$lang['twitter_03'].'</div></div>';
	}else{
		$db->Query("INSERT INTO `twitter_retweet` (user, url, tweet_id, title, max_clicks, daily_clicks, cpc, country, sex) VALUES('".$data['id']."', 'https://twitter.com/".$twitter[0]['user']['screen_name']."/status/".$twitter[0]['id']."', '".$twitter[0]['id']."', '".$title."', '".$max_clicks."', '".$daily_clicks."', '".$cpc."', '".$country."', '".$gender."') ");
		$msg = '<div class="msg"><div class="success">'.$lang['twitter_01'].'</div></div>';
		$error = 0;
	}
}
?>