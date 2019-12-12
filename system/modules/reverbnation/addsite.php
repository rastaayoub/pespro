<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$url = $db->EscapeString($_POST['url']);
$title = $db->EscapeString(truncate($_POST['title'], 100), 1);

if(empty($title) || empty($url)){
	$msg = '<div class="msg"><div class="error">'.$lang['b_25'].'</div></div>';
}elseif(!preg_match('|^http(s)?://www.reverbnation.com/(.*)?$|i', $url) || preg_match('|^http(s)?://www.reverbnation.com/fan/(.*)?$|i', $url)){
	$msg = '<div class="msg"><div class="error">'.$lang['reverbnation_16'].' http://www.reverbnation.com/USERNAME</div></div>';
}else{
	$html = get_data($url);

	$doc = new DOMDocument();
	@$doc->loadHTML($html);

	$metas = $doc->getElementsByTagName('meta');
	for ($i = 0; $i < $metas->length; ++$i){
		$meta = $metas->item($i);
		if($meta->getAttribute('name') == 'image_src')
			$rev_image = $meta->getAttribute('content');
		if($meta->getAttribute('property') == 'og:type')
			$rev_type = $meta->getAttribute('content');
	}

	preg_match('|href="/page_object/page_object_fans/artist_(.*?)"|i', $html, $content);
	preg_match('/([\d]+)/', $content[1], $match);
	$artist_id = $match[0];
	
	if($rev_type != 'band' || empty($artist_id)){
		$msg = '<div class="msg"><div class="error">'.$lang['reverbnation_02'].'</div></div>';
	}elseif($db->QueryGetNumRows("SELECT * FROM `reverbnation` WHERE `artist_id`='".$artist_id."'") > 0){
		$msg = '<div class="msg"><div class="error">'.$lang['reverbnation_01'].'</div></div>';
	}else{
		$db->Query("INSERT INTO `reverbnation` (user, url, artist_id, title, image, max_clicks, daily_clicks, cpc, country, sex) VALUES('".$data['id']."', '".$url."', '".$artist_id."', '".$title."', '".$rev_image."', '".$max_clicks."', '".$daily_clicks."', '".$cpc."', '".$country."', '".$gender."') ");
		$msg = '<div class="msg"><div class="success">'.$lang['reverbnation_03'].'</div></div>';
		$error = 0;
	}
}
?>