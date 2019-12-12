<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
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

	if($db->QueryGetNumRows("SELECT * FROM `yfav` WHERE `url`='".$vid."' AND `user`='".$data['id']."' LIMIT 1") > 0){
		$msg = '<div class="msg"><div class="error">'.$lang['yfav_01'].'</div></div>';
	}elseif($vid == '0' || $vid == ''){
		$msg = '<div class="msg"><div class="error">'.$lang['yfav_02'].'</div></div>';
	}else{
		$db->Query("INSERT INTO `yfav` (user, url, title, max_clicks, daily_clicks, cpc, country, sex) VALUES('".$data['id']."', '".$vid."', '".$title."', '".$max_clicks."', '".$daily_clicks."', '".$cpc."', '".$country."', '".$gender."') ");
		$msg = '<div class="msg"><div class="success">'.$lang['yfav_03'].'</div></div>';
		$error = 0;
	}
}
?>