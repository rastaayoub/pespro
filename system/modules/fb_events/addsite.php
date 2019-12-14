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
	elseif(!preg_match("|^http(s)?://(www.)?facebook.com/events/([0-9]+)/(.*)?$|i", $url))
	{
		$msg = '<div class="msg"><div class="error">'.$lang['fb_04'].'</div></div>';
	}
	else
	{
		function getEventId($url)
		{
			$result = explode('/', $url);
			$result = $result[4];

			if(preg_match("|^(.*)?(.*)|i", $result)){
				$id = explode('?', $result);
				$result = (!empty($id[0]) ? $id[0] : $result);
			}

			return $result;
		}
		
		function get_event_pic($id){
			global $site;
			$eventData = get_data('https://graph.facebook.com/'.$id.'?fields=picture&access_token='.$site['fb_app_id'].'|'.$site['fb_app_secret'].'&summary=count');
			$eventData = json_decode($eventData, true);
			return (empty($eventData['picture']['data']['url']) ? '0' : $eventData['picture']['data']['url']);
		}

		$pid = getEventId($url);
		$event_img = get_event_pic($pid);
		if(!empty($pid) && $event_img != 0)
		{
			$msg = '<div class="msg"><div class="error">'.$lang['fb_04'].'</div></div>';
		}
		elseif($db->QueryGetNumRows("SELECT * FROM `fb_event` WHERE `url`='".$pid."' LIMIT 1") > 0)
		{
			$msg = '<div class="msg"><div class="error">'.$lang['fb_15'].'</div></div>';
		}
		else
		{
			$db->Query("INSERT INTO `fb_event` (user, url, title, img, max_clicks, daily_clicks, cpc, country, sex) VALUES('".$data['id']."', '".$pid."', '".$title."', '".$db->EscapeString($event_img)."', '".$max_clicks."', '".$daily_clicks."', '".$cpc."', '".$country."', '".$gender."')");
			$msg = '<div class="msg"><div class="success">'.$lang['fb_14'].'</div></div>';
			$error = 0;
		}
	}
?>