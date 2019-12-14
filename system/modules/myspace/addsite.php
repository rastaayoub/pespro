<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$url = $db->EscapeString($_POST['url']);

if(empty($url)){
	$msg = '<div class="msg"><div class="error">'.$lang['b_25'].'</div></div>';
}else{
	$html = get_data('https://new.myspace.com/'.strtolower($url));

	$doc = new DOMDocument();
	@$doc->loadHTML($html);

	$metas = $doc->getElementsByTagName('meta');
	for ($i = 0; $i < $metas->length; ++$i){
		$meta = $metas->item($i);
		if($meta->getAttribute('property') == 'og:image')
			$mys_image = $meta->getAttribute('content');
		if($meta->getAttribute('property') == 'og:type')
			$mys_type = $meta->getAttribute('content');
		if($meta->getAttribute('property') == 'og:url')
			$mys_url = $meta->getAttribute('content');
	}
	
	if(!empty($mys_url)){
		$mys_url = explode('/', $mys_url);
		$url = $mys_url[3];
	}

	preg_match('|href="/page_object/page_object_fans/artist_(.*?)"|i', $html, $content);
	preg_match('/([\d]+)/', $content[1], $match);
	$artist_id = $match[0];
	
	if($mys_type != 'profile' || empty($mys_image)){
		$msg = '<div class="msg"><div class="error">'.$lang['myspace_02'].'</div></div>';
	}elseif($db->QueryGetNumRows("SELECT * FROM `myspace` WHERE `url`='".$url."'") > 0){
		$msg = '<div class="msg"><div class="error">'.$lang['myspace_01'].'</div></div>';
	}else{
		$db->Query("INSERT INTO `myspace` (user, url, title, image, max_clicks, daily_clicks, cpc, country, sex) VALUES('".$data['id']."', '".$url."', '".$url."', '".$mys_image."', '".$max_clicks."', '".$daily_clicks."', '".$cpc."', '".$country."', '".$gender."') ");
		$msg = '<div class="msg"><div class="success">'.$lang['myspace_03'].'</div></div>';
		$error = 0;
	}
}
?>