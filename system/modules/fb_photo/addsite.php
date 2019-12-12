<?php
    if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
    $url = $db->EscapeString($_POST['url']);
    $title = $db->EscapeString($_POST['title']);

    if(empty($title) || empty($url)){
        $msg = '<div class="msg"><div class="error">'.$lang['b_25'].'</div></div>';
    }elseif(!preg_match('/https?:\/\/(www.)?facebook\.com\/([a-zA-Z0-9_.\- ]*)\/([a-zA-Z0-9_\- ]*)\/([a-zA-Z0-9_\.\-]*)\/([a-zA-Z0-9_\-]*)/i', $url)){
        $msg = '<div class="msg"><div class="error">'.$lang['fb_17'].'</div></div>';
    }else{
        function getPhotoId($url) {
            preg_match('/https?:\/\/(www.)?facebook\.com\/([a-zA-Z0-9_.\- ]*)\/([a-zA-Z0-9_\- ]*)\/([a-zA-Z0-9_\.\-]*)\/([a-zA-Z0-9_\-]*)/i', $url, $tmp);
            return isset($tmp[5]) ? $tmp[5] : false;  
        }

        $pid = getPhotoId($url);      

        if(!$pid){
            $msg = '<div class="msg"><div class="error">'.$lang['fb_17'].'</div></div>';
        }elseif($db->QueryGetNumRows("SELECT * FROM `fb_photo` WHERE `p_id`='".$pid."'") > 0){
            $msg = '<div class="msg"><div class="error">'.$lang['fb_18'].'</div></div>';
        }else{
			function get_img($id){
				global $site;
				$url = get_data('https://graph.facebook.com/'.$id.'?fields=picture&access_token='.$site['fb_app_id'].'|'.$site['fb_app_secret']);
				$result = json_decode($url, true);
				return $result['picture'];
			}

			$img = get_img($pid);
			if(empty($img)){
				$msg = '<div class="msg"><div class="error">'.$lang['fb_19'].'</div></div>';
			}else{
				$db->Query("INSERT INTO `fb_photo` (user, p_id, url, title, img, max_clicks, daily_clicks, cpc, country, sex) VALUES('".$data['id']."', '".$pid."', '".$url."', '".$title."', '".$img."', '".$max_clicks."', '".$daily_clicks."', '".$cpc."', '".$country."', '".$gender."') ");
				$msg = '<div class="msg"><div class="success">'.$lang['fb_20'].'</div></div>';
				$error = 0;
			}
		}
    }
?>