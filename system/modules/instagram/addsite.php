<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$url = $db->EscapeString($_POST['url']);

if(empty($url)){
	$msg = '<div class="msg"><div class="error">'.$lang['b_25'].'</div></div>';
}else{
	$get = strip_tags(get_data('https://www.instagram.com/'.$url.'/'));
	preg_match('/window._sharedData = (.*?)};/i', $get, $match);
	$json = json_decode($match[1].'}', true);

	if($json['entry_data']['ProfilePage'][0]['graphql']['user']['is_private']){
		$msg = '<div class="msg"><div class="error">'.$lang['inst_01'].'</div></div>';
	}elseif(empty($json['entry_data']['ProfilePage'][0]['graphql']['user']['id'])){
		$msg = '<div class="msg"><div class="error">'.$lang['inst_03'].'</div></div>';
	}else{
		if($db->QueryGetNumRows("SELECT id FROM `instagram` WHERE `inst_id`='".$db->EscapeString($json['entry_data']['ProfilePage'][0]['graphql']['user']['id'])."' LIMIT 1") > 0){
			$msg = '<div class="msg"><div class="error">'.$lang['inst_02'].'</div></div>';
		}else{
			$db->Query("INSERT INTO `instagram` (user, inst_id, url, title, p_av, max_clicks, daily_clicks, cpc, country, sex) VALUES('".$data['id']."', '".$db->EscapeString($json['entry_data']['ProfilePage'][0]['graphql']['user']['id'])."', '".$db->EscapeString($json['entry_data']['ProfilePage'][0]['graphql']['user']['username'])."', '".$db->EscapeString($json['entry_data']['ProfilePage'][0]['graphql']['user']['full_name'])."', '".$db->EscapeString($json['entry_data']['ProfilePage'][0]['graphql']['user']['profile_pic_url'])."', '".$max_clicks."', '".$daily_clicks."', '".$cpc."', '".$country."', '".$gender."') ");
            $msg = '<div class="msg"><div class="success">'.$lang['inst_04'].'</div></div>';
			$error = 0;
		}
	}
}
?>