<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$url = $db->EscapeString($_POST['url']);

if(empty($url)){
	$msg = '<div class="msg"><div class="error">'.$lang['b_25'].'</div></div>';
}else{
	$x = get_data('http://gdata.youtube.com/feeds/api/users/'.$url.'?alt=json');
	$x = json_decode($x, true);
	$link = $x['entry']['link'][0]['href'];
	$name = $x['entry']['yt$username']['$t'];
	
	if($db->QueryGetNumRows("SELECT id FROM `ysub` WHERE `url`='".$link."'") > 0){
		$msg = '<div class="msg"><div class="error">'.$lang['ysub_02'].'</div></div>';
	}elseif(empty($link) || empty($name)){
		$msg = '<div class="msg"><div class="error">'.$lang['ysub_04'].'</div></div>';
	}else{
		$db->Query("INSERT INTO `ysub` (user, url, title, y_av, max_clicks, daily_clicks, cpc, country, sex) VALUES('".$data['id']."', '".$link."', '".$name."', '".$x['entry']['media$thumbnail']['url']."', '".$max_clicks."', '".$daily_clicks."', '".$cpc."', '".$country."', '".$gender."') ");
		$msg = '<div class="msg"><div class="success">'.$lang['ysub_03'].'</div></div>';
		$error = 0;
	}
}
?>