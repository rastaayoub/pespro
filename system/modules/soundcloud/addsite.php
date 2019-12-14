<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$url = $db->EscapeString($_POST['url']);

function get_sc_data($url){
	if(!function_exists('curl_init')){
        return file_get_contents($url);
    }elseif(!function_exists('file_get_contents')){
        return '';
    }

	$options = array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
		CURLOPT_OAUTH_TOKEN => false,
		CURLOPT_TIMEOUT => 15
	);

	$options[CURLOPT_HTTPHEADER] = array(
		"Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*\/*;q=0.5",
		"Accept-Language: en-us,en;q=0.5",
		"Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7",
		"Cache-Control: must-revalidate, max-age=0",
		"Connection: keep-alive",
		"Keep-Alive: 300",
		"Pragma: public"
	);

	$ch = curl_init();
	curl_setopt_array($ch, $options);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

if(empty($url)){
	$msg = '<div class="msg"><div class="error">'.$lang['b_25'].'</div></div>';
}else{
	$x  = get_sc_data("http://api.soundcloud.com/users/".$url.".json?consumer_key=".$site['scf_api']);
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