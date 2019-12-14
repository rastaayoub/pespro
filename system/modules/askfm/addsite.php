<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	$url = $db->EscapeString($_POST['url']);

	if(empty($url)){
		$msg = '<div class="msg"><div class="error">'.$lang['b_25'].'</div></div>';
	}elseif(!preg_match("|^http(s)?://ask.fm/([a-z0-9_]+)/answers/([0-9]+)?$|i", $url)){
		$msg = '<div class="msg"><div class="error">'.$lang['askfmlike_01'].'</div></div>';
	}else{
		$answer = get_data($url);
		$result = array(); 
		preg_match('/style="background-image:url\(([^"]*)\)" class="userAvatar/',$answer,$result);
		$image = $result[1];
		preg_match('/<title>([^"]*)<\/title>/',$answer,$result);
		$title = explode('|', $result[1]);
		$title = strip_tags($title[0]);

		if($db->QueryGetNumRows("SELECT id FROM `askfm_like` WHERE `url`='".$url."'") > 0){
			$msg = '<div class="msg"><div class="error">'.$lang['askfmlike_02'].'</div></div>';
		}elseif(empty($url) || empty($title)){
			$msg = '<div class="msg"><div class="error">'.$lang['askfmlike_03'].'</div></div>';
		}else{
			$db->Query("INSERT INTO `askfm_like` (user, url, title, img, cpc) VALUES('".$data['id']."', '".$url."', '".$title."', '".$image."', '".$cpc."') ");
			$msg = '<div class="msg"><div class="success">'.$lang['askfmlike_04'].'</div></div>';
			$error = 0;
		}
	}
?>