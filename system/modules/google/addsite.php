<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$url = $db->EscapeString($_POST['url']);
$title = $db->EscapeString(truncate($_POST['title'], 100), 1);

if(empty($title) || empty($url)){
	$msg = '<div class="msg"><div class="error">'.$lang['b_25'].'</div></div>';
}elseif(!preg_match('/^(http|https):\/\/[a-z0-9_]+([\-\.]{1}[a-z_0-9]+)*\.[_a-z]{2,5}'.'((:[0-9]{1,5})?\/.*)?$/i', $url)){
	$msg = '<div class="msg"><div class="error">'.$lang['b_27'].'</div></div>';
}elseif(blacklist_check($url, 2) == 1){
	$msg = '<div class="msg"><div class="error">'.lang_rep($lang['b_296'], array('-URL-' => $url)).'</div></div>';
}else{
	if($db->QueryGetNumRows("SELECT id FROM `google` WHERE `url`='".$url."' LIMIT 1") > 0){
		$msg = '<div class="msg"><div class="error">'.$lang['gp_04'].'</div></div>';
	}else{
		$db->Query("INSERT INTO `google` (user, url, title, max_clicks, daily_clicks, cpc, country, sex) VALUES('".$data['id']."', '".strtolower($url)."', '".$title."', '".$max_clicks."', '".$daily_clicks."', '".$cpc."', '".$country."', '".$gender."') ");
		$msg = '<div class="msg"><div class="success">'.$lang['gp_01'].'</div></div>';
		$error = 0;
	}
}
?>