<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$url = $db->EscapeString($_POST['url']);
$title = $db->EscapeString(truncate($_POST['title'], 100), 1);

if(empty($title) || empty($url)){
	$msg = '<div class="msg"><div class="error">'.$lang['b_25'].'</div></div>';
}elseif(!preg_match('/^(http|https):\/\/[a-z0-9_]+([\-\.]{1}[a-z_0-9]+)*\.[_a-z]{2,5}'.'((:[0-9]{1,5})?\/.*)?$/i', $url)){
	$msg = '<div class="msg"><div class="error">'.$lang['b_27'].'</div></div>';
}else{
	$get = strip_tags(get_data($url));
	preg_match('/window._sharedData = (.*?)};/i', $get, $match);
	$json = json_decode($match[1].'}', true);

	if(empty($json['entry_data']['PostPage'][0]['graphql']['shortcode_media']['id'])){
		$msg = '<div class="msg"><div class="error">'.$lang['inst_likes_03'].'</div></div>';
	}else{
		$url = 'https://www.instagram.com/p/'.$json['entry_data']['PostPage'][0]['graphql']['shortcode_media']['shortcode'].'/';
		
		if($db->QueryGetNumRows("SELECT id FROM `inst_likes` WHERE `inst_id`='".$db->EscapeString($json['entry_data']['PostPage'][0]['graphql']['shortcode_media']['id'])."' LIMIT 1") > 0){
			$msg = '<div class="msg"><div class="error">'.$lang['inst_likes_02'].'</div></div>';
		}else{
			$db->Query("INSERT INTO `inst_likes` (user, inst_id, url, title, photo, max_clicks, daily_clicks, cpc, country, sex) VALUES('".$data['id']."', '".$db->EscapeString($json['entry_data']['PostPage'][0]['graphql']['shortcode_media']['id'])."', '".$db->EscapeString($url)."', '".$title."', '".$db->EscapeString($json['entry_data']['PostPage'][0]['graphql']['shortcode_media']['display_url'])."', '".$max_clicks."', '".$daily_clicks."', '".$cpc."', '".$country."', '".$gender."') ");
            $msg = '<div class="msg"><div class="success">'.$lang['inst_likes_04'].'</div></div>';
			$error = 0;
		}
	}
}
?>