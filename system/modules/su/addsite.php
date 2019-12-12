<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$url = $db->EscapeString($_POST['url']);
$title = $db->EscapeString(truncate($_POST['title'], 100), 1);

if(empty($title) || empty($url)){
	$msg = '<div class="msg"><div class="error">'.$lang['b_25'].'</div></div>';
}elseif(!preg_match('/^(http|https):\/\/[a-z0-9_]+([\-\.]{1}[a-z_0-9]+)*\.[_a-z]{2,5}'.'((:[0-9]{1,5})?\/.*)?$/i', $url)){
	$msg = '<div class="msg"><div class="error">'.$lang['b_27'].'</div></div>';
}else{
	if(!preg_match("|^http(s)?://www.stumbleupon.com/stumbler/(.*)?$|i", $url)){
		$msg = '<div class="msg"><div class="error">'.$lang['su_01'].'</div></div>';
	}else{
		if($db->QueryGetNumRows("SELECT id FROM `stumble` WHERE `url`='".$url."' LIMIT 1") > 0){
			$msg = '<div class="msg"><div class="error">'.$lang['su_02'].'</div></div>';
		}else{
			$db->Query("INSERT INTO `stumble` (user, url, title, max_clicks, daily_clicks, cpc, country, sex) VALUES('".$data['id']."', '".$url."', '".$title."', '".$max_clicks."', '".$daily_clicks."', '".$cpc."', '".$country."', '".$gender."')");
			$msg = '<div class="msg"><div class="success">'.$lang['su_03'].'</div></div>';
			$error = 0;
		}
	}
}
?>