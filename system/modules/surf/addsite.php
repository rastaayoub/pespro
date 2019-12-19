<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$url = $db->EscapeString($_POST['url']);
$title = $db->EscapeString(truncate($_POST['title'], 100), 1);

if(empty($title) || empty($url)){
	$msg = '<div class="msg"><div class="error">'.$lang['b_25'].'</div></div>';
}elseif(!preg_match('/^(http|https):\/\/[a-z0-9_]+([\-\.]{1}[a-z_0-9]+)*\.[_a-z]{2,5}'.'((:[0-9]{1,5})?\/.*)?$/i', $url)){
	$msg = '<div class="msg"><div class="error">'.$lang['b_27'].'</div></div>';
}elseif(substr($url,-4) == '.exe') {
	$msg = '<div class="msg"><div class="error"><b>ERROR:</b> This URL is not allowed, don\'t try to add .exe files!</div></div>';
}elseif(blacklist_check($url, 2) == 1){
	$msg = '<div class="msg"><div class="error">'.lang_rep($lang['b_296'], array('-URL-' => $url)).'</div></div>';
}else{
	$sql = $db->Query("SELECT id,confirm FROM `surf` WHERE `url`='".$url."' AND `user`='".$data['id']."' LIMIT 1");

	if($db->GetNumRows($sql) > 0){
		$sit = $db->FetchArray($sql);
		if($sit['confirm'] == 0){
			$msg = '<div class="msg"><div class="error">'.$lang['surf_01'].'</div></div>';
		}else{
			if($site['surf_type'] != 2){
				redirect('c_surf.php?id='.$sit['id']);
			}else{
				$db->Query("UPDATE `surf` SET `confirm`='0' WHERE `id`='".$sit['id']."'");
				$msg = '<div class="msg"><div class="success">'.$lang['surf_14'].'</div></div>';
			}
		}
	}else{
		if($db->QueryGetNumRows("SELECT id FROM `surf` WHERE `url`='".$url."'") > 0){
			$msg = '<div class="msg"><div class="error">'.$lang['surf_01'].'</div></div>';
		}else{
			$db->Query("INSERT INTO `surf` (user, url, title, max_clicks, daily_clicks, cpc, confirm, country, sex) VALUES('".$data['id']."', '".$url."', '".$title."', '".$max_clicks."', '".$daily_clicks."', '".$cpc."', '".($site['surf_type'] == 2 ? 0 : 1)."', '".$country."', '".$gender."') ");
			if($site['surf_type'] != 2){
				$sid = mysql_insert_id();
				redirect('c_surf.php?id='.$sid);
			}else{
				$msg = '<div class="msg"><div class="success">'.$lang['surf_14'].'</div></div>';
			}
			$error = 0;
		}
	}
}
?>