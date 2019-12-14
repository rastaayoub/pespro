<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$url = $db->EscapeString($_POST['url']);
$title = $db->EscapeString($_POST['title']);

if(empty($title) || empty($url)){
	$msg = '<div class="msg"><div class="error">'.$lang['b_25'].'</div></div>';
}else{
	if(!preg_match("|^http(s)?:\/\/soundcloud.com\/.*$|is", $url)){
		$msg = '<div class="msg"><div class="error">'.$lang['soundcloud_likes_04'].'</div></div>';
	}else{
		
		$options = array(
			CURLOPT_URL => $url,
			CURLOPT_USERAGENT => 'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_BINARYTRANSFER => true,
			CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
			CURLOPT_TIMEOUT => 5
		);
		
		$track_get = get_data($url, 5, '', $options);
		$match = array();
		preg_match('/og:url" content="(.*)"><meta property="og:title"/is', $track_get, $match);
		$track_url = $match[1];
		preg_match('/url" content="soundcloud:\/\/sounds:([^\D]+)">/is', $track_get, $match);
		$track_id = $match[1];

		if(empty($track_url) || empty($track_id)){
			$msg = '<div class="msg"><div class="error">'.$lang['soundcloud_likes_02'].'</div></div>';
		}elseif($db->QueryGetNumRows("SELECT * FROM `soundcloud_likes` WHERE `url`='".$info['url']."'") > 0){
			$msg = '<div class="msg"><div class="error">'.$lang['soundcloud_likes_01'].'</div></div>';
		}else{
			$db->Query("INSERT INTO `soundcloud_likes` (user, url, title, track_id, max_clicks, daily_clicks, cpc, country, sex) VALUES('".$data['id']."', '".$track_url."', '".$title."', '".$track_id."', '".$max_clicks."', '".$daily_clicks."', '".$cpc."', '".$country."', '".$gender."') ");
			$msg = '<div class="msg"><div class="success">'.$lang['soundcloud_likes_03'].'</div></div>';
			$error = 0;
		}
	}
}
?>