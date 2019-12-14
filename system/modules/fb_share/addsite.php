<?php
    if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
    $url = $db->EscapeString($_POST['url']);
    $title = $db->EscapeString($_POST['title']);

    if(empty($title) || empty($url)){
        $msg = '<div class="msg"><div class="error">'.$lang['b_25'].'</div></div>';
    }elseif(!preg_match('/^(http|https):\/\/[a-z0-9_]+([\-\.]{1}[a-z_0-9]+)*\.[_a-z]{2,9}'.'((:[0-9]{1,5})?\/.*)?$/i', $url)){
        $msg = '<div class="msg"><div class="error">'.$lang['b_27'].'</div></div>';
    }elseif(blacklist_check($url, 2) == 1){
        $msg = '<div class="msg"><div class="error">'.lang_rep($lang['b_296'], array('-URL-' => $url)).'</div></div>';
    }else{
        if($db->QueryGetNumRows("SELECT id FROM `fb_share` WHERE `url`='".$url."' LIMIT 1") > 0){
            $msg = '<div class="msg"><div class="error">'.$lang['fb_05'].'</div></div>';
        }elseif(preg_match("|^http(s)?://(www.)?facebook.([a-z]+)/(.*)?$|i", $url)){
            $msg = '<div class="msg"><div class="error">'.$lang['fb_35'].'</div></div>';
        }else{

            function fb_check($url){
				global $site;
				$get = get_data('https://graph.facebook.com/?ids='.urlencode($url).'&fields=engagement&access_token='.$site['fb_app_id'].'|'.$site['fb_app_secret']); 
				$result = json_decode($get, true);

				return ($result[$url]['id'] == $url ? 1 : 0);
			}

            if(fb_check($url) == 0){
                $msg = '<div class="msg"><div class="error">'.$lang['fb_06'].'</div></div>';
            }else{
                $db->Query("INSERT INTO `fb_share` (user, url, title, max_clicks, daily_clicks, cpc, country, sex) VALUES('".$data['id']."', '".$url."', '".$title."', '".$max_clicks."', '".$daily_clicks."', '".$cpc."', '".$country."', '".$gender."') ");
                $msg = '<div class="msg"><div class="success">'.$lang['fb_02'].'</div></div>';
                $error = 0;
            }
        }
    }
?>