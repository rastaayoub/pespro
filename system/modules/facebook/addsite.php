<?php
    if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
    $url = $db->EscapeString($_POST['url']);
    $title = $db->EscapeString(truncate($_POST['title'], 100), 1);

    if(empty($title) || empty($url))
	{
        $msg = '<div class="msg"><div class="error">'.$lang['b_25'].'</div></div>';
    }
	elseif(!preg_match('/^(http|https):\/\/[a-z0-9_]+([\-\.]{1}[a-z_0-9]+)*\.[_a-z]{2,5}'.'((:[0-9]{1,5})?\/.*)?$/i', $url))
	{
        $msg = '<div class="msg"><div class="error">'.$lang['b_27'].'</div></div>';
    }
	else
	{
        if($db->QueryGetNumRows("SELECT id FROM `facebook` WHERE `url` LIKE '".$url."' LIMIT 1") > 0)
		{
            $msg = '<div class="msg"><div class="error">'.$lang['fb_05'].'</div></div>';
        }
		elseif(preg_match("|^http(s)?://(www.)?facebook.([a-z]+)/photo.php(.*)?$|i", $url))
		{
            $msg = '<div class="msg"><div class="error">'.$lang['fb_01'].'</div></div>';
        }
		elseif(preg_match("|^http(s)?://(www.)?facebook.([a-z]+)/browse/fanned_pages/(.*)?$|i", $url))
		{
            $msg = '<div class="msg"><div class="error">'.$lang['fb_34'].'</div></div>';
        }
		else
		{
			$fb_type = (preg_match("%^http(s)?://(www.)?facebook.([a-z]+)/(.*)?$%i", $url) ? 1 : 0);

            function fb_check($url)
			{
                global $site;
				$data = get_data('https://graph.facebook.com/?ids='.urlencode($url).'&fields=fan_count,id&rand='.rand(1000,9999).'&access_token='.$site['fb_app_id'].'|'.$site['fb_app_secret']);
                $likes = json_decode($data, true);

                return (!isset($likes[$url]['fan_count']) ? false : $likes[$url]['id']);
            }
			
			$fb_id = 0;
			if($fb_type == 1)
			{
				$fb_id = fb_check($url);
			}

            if($fb_type == 1 && !$fb_id)
			{
                $msg = '<div class="msg"><div class="error">'.$lang['fb_34'].'</div></div>';
            }
			elseif($fb_type == 0 && blacklist_check($url, 2) == 1)
			{
                $msg = '<div class="msg"><div class="error">'.lang_rep($lang['b_296'], array('-URL-' => $url)).'</div></div>';
            }
			elseif(preg_match("/m.facebook.com/i", $url))
			{
				$msg = '<div class="msg"><div class="error">'.$lang['fb_33'].'</div></div>';
			}
			elseif($fb_type == 1 && $db->QueryGetNumRows("SELECT id FROM `facebook` WHERE `url` LIKE '%".$url."%' AND `type`='1' LIMIT 1") > 0)
			{
                $msg = '<div class="msg"><div class="error">'.$lang['fb_05'].'</div></div>';
            }
			else
			{
                $db->Query("INSERT INTO `facebook` (user, url, fb_id, title, max_clicks, daily_clicks, cpc, type, country, sex) VALUES('".$data['id']."', '".$url."', '".$fb_id."', '".$title."', '".$max_clicks."', '".$daily_clicks."', '".$cpc."', '".$fb_type."', '".$country."', '".$gender."') ");
                $msg = '<div class="msg"><div class="success">'.$lang['fb_02'].'</div></div>';
                $error = 0;
            }
        }
    }
?>