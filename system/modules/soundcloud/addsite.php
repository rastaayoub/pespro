<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$url = $db->EscapeString($_POST['url']);

if(empty($url)){
	$msg = '<div class="msg"><div class="error">'.$lang['b_25'].'</div></div>';
}else{
	$x  = get_data("http://api.soundcloud.com/users/".$url.".json?consumer_key=".$site['scf_api']);
	$x = json_decode($x, true);

	if($db->QueryGetNumRows("SELECT id FROM `scf` WHERE `url`='".$url."'") > 0){
		$msg = '<div class="msg"><div class="error">'.$lang['scf_03'].'</div></div>';
	}elseif(empty($x['id']) || empty($x['permalink'])){
		$msg = '<div class="msg"><div class="error">'.$lang['scf_05'].'</div></div>';
	}else{
		$db->Query("INSERT INTO `scf` (user, url, title, s_av, s_id, max_clicks, daily_clicks, cpc, country, sex) VALUES('".$data['id']."', '".$x['permalink']."', '".$x['username']."', '".$x['avatar_url']."', '".$x['id']."', '".$max_clicks."', '".$daily_clicks."', '".$cpc."', '".$country."', '".$gender."') ");
		$msg = '<div class="msg"><div class="success">'.$lang['scf_04'].'</div></div>';
		$error = 0;
	}
}
?>