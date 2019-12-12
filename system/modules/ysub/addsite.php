<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$url = $db->EscapeString($_POST['url']);

if(empty($url)){
	$msg = '<div class="msg"><div class="error">'.$lang['b_25'].'</div></div>';
}else{
	$x = get_data('https://www.googleapis.com/youtube/v3/channels?part=contentDetails,statistics,snippet&forUsername='.$url.'&key='.$site['yt_api']);
	$x = json_decode($x, true);

	if(empty($x['pageInfo']['totalResults'])) {
		$x = get_data('https://www.googleapis.com/youtube/v3/channels?part=contentDetails,statistics,snippet&id='.$url.'&key='.$site['yt_api']);
		$x = json_decode($x, true);
	}
	
	$yt_url = (empty($x['items'][0]['id']) ? '' : 'http://www.youtube.com/channel/'.$x['items'][0]['id']);
	$yt_name = (empty($x['items'][0]['snippet']['title']) ? '' : $x['items'][0]['snippet']['title']);
	$yt_image = (empty($x['items'][0]['snippet']['thumbnails']['default']['url']) ? '' : $x['items'][0]['snippet']['thumbnails']['default']['url']);

	if(empty($yt_url) || empty($url)){
		$msg = '<div class="msg"><div class="error">'.$lang['ysub_04'].'</div></div>';
	}elseif($db->QueryGetNumRows("SELECT id FROM `ysub` WHERE `url`='".$yt_url."'") > 0){
		$msg = '<div class="msg"><div class="error">'.$lang['ysub_02'].'</div></div>';
	}else{
		$db->Query("INSERT INTO `ysub` (user, url, title, y_av, max_clicks, daily_clicks, cpc, country, sex) VALUES('".$data['id']."', '".$yt_url."', '".$url."', '".$yt_image."', '".$max_clicks."', '".$daily_clicks."', '".$cpc."', '".$country."', '".$gender."') ");
		$msg = '<div class="msg"><div class="success">'.$lang['ysub_03'].'</div></div>';
		$error = 0;
	}
}
?>