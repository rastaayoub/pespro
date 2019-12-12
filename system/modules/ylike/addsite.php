<?php
if(!defined('BASEPATH')){ exit('Unable to view file.'); }
$url = $db->EscapeString($_POST['url']);
$title = $db->EscapeString(truncate($_POST['title'], 100), 1);

if(empty($title) || empty($url)){
	$msg = '<div class="msg"><div class="error">'.$lang['b_25'].'</div></div>';
}elseif(!preg_match('/^(http|https):\/\/[a-z0-9_]+([\-\.]{1}[a-z_0-9]+)*\.[_a-z]{2,5}'.'((:[0-9]{1,5})?\/.*)?$/i', $url)){
	$msg = '<div class="msg"><div class="error">'.$lang['b_27'].'</div></div>';
}else{
	if(preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url)){
		preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches);
		$vid = str_replace(' ', '', $matches[0]);
	}else{
		$vid = '0';
	}
	
	function check_likes($url){
		global $site;
		$result = get_data('https://www.googleapis.com/youtube/v3/videos?id='.$url.'&key='.$site['yt_api'].'&part=statistics');
		$result = json_decode($result, true);
		if(!empty($result['items'][0]['statistics']['likeCount'])){
			return 1;
		}else{
			return 0;
		}
	}

	if($db->QueryGetNumRows("SELECT * FROM `ylike` WHERE `url`='".$vid."' AND `user`='".$data['id']."' LIMIT 1") > 0){
		$msg = '<div class="msg"><div class="error">'.$lang['ylike_01'].'</div></div>';
	}elseif($vid == '0' || $vid == ''){
		$msg = '<div class="msg"><div class="error">'.$lang['ylike_02'].'</div></div>';
	}elseif(check_likes($vid) == 0){
		$msg = '<div class="msg"><div class="error">'.$lang['ylike_14'].'</div></div>';
	}else{
		$db->Query("INSERT INTO `ylike` (user, url, title, max_clicks, daily_clicks, cpc, country, sex) VALUES('".$data['id']."', '".$vid."', '".$title."', '".$max_clicks."', '".$daily_clicks."', '".$cpc."', '".$country."', '".$gender."') ");
		$msg = '<div class="msg"><div class="success">'.$lang['ylike_03'].'</div></div>';
		$error = 0;
	}
}
?>