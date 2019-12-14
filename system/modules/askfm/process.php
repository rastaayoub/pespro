<?php
    define('BASEPATH', true);
    require_once('../../config.php');
    if(!$is_online){
		exit;
	}

    function getCount($url)
	{
        $answer = get_data($url);
        preg_match('/<a class="counter" href="(.*?)">(.*?)<\/a>/',$answer, $result);

        if($result[1] !== NULL)
		{
            return ($result[2]) ? $result[2] : false;    
        }
    }

	if(isset($_POST['get']) && $_POST['pid'] > 0)
	{
        $pid = $db->EscapeString($_POST['pid']);
        $sit = $db->QueryFetchArray("SELECT url FROM `askfm_like` WHERE `id`='".$pid."' LIMIT 1");
        $key = getCount($sit['url']);

		if(!is_numeric($key)) {
			$msg = '<div class="msg"><div class="error">'.$lang['b_300'].'</div></div>';
			$type = 'not_available';
		} else {
			$result	= $db->Query("INSERT INTO `module_session` (`user_id`,`page_id`,`ses_key`,`module`,`timestamp`)VALUES('".$data['id']."','".$pid."','".$key."','askfm_like','".time()."') ON DUPLICATE KEY UPDATE `ses_key`='".$key."'");
			$msg = ($result ? '<div class="msg"><div class="info">'.$lang['askfmlike_08'].'</div></div>' : '<div class="msg"><div class="error">'.$lang['askfmlike_09'].'</div></div>');
			$type = ($result ? 'success' : 'error');
		}

		$resultData = array('message' => $msg, 'type' => $type);

		header('Content-type: application/json');
		echo json_encode($resultData);
    }
	elseif(isset($_POST['step']) && $_POST['step'] == "skip" && is_numeric($_POST['sid']) && !empty($data['id']))
	{
        $id = $db->EscapeString($_POST['sid']);

        if($db->QueryGetNumRows("SELECT site_id FROM `askfm_liked` WHERE `user_id`='".$data['id']."' AND `site_id`='".$id."'") == 0){
            $db->Query("INSERT INTO `askfm_liked` (user_id, site_id) VALUES('".$data['id']."', '".$id."')");
            echo '<div class="msg"><div class="info">'.$lang['b_359'].'</div></div>';
        }
    }

    if(isset($_POST['id'])){
        $uid = $db->EscapeString($_POST['id']);
		$sit = $db->QueryFetchArray("SELECT a.id,a.user,a.url,a.cpc,b.id AS uid,b.coins FROM askfm_like a JOIN users b ON b.id = a.user WHERE a.id = '".$uid."' LIMIT 1");

		if(empty($sit['uid']) || empty($sit['id']) || empty($data['id']) || $sit['coins'] < $sit['cpc'] || $sit['cpc'] < 2){
			$msg = '<div class="msg"><div class="error">'.$lang['b_300'].'</div></div>';
			$type = 'not_available';
		}else{
			$mod_ses = $db->QueryFetchArray("SELECT ses_key FROM `module_session` WHERE `user_id`='".$data['id']."' AND `page_id`='".$sit['id']."' AND `module`='askfm_like' LIMIT 1");

			if($mod_ses['ses_key'] != '' && getCount($sit['url']) > $mod_ses['ses_key']){
				if($db->QueryGetNumRows("SELECT site_id FROM `askfm_liked` WHERE `site_id`='".$uid."' AND `user_id`='".$data['id']."'") == 0) {
					$db->Query("UPDATE `users` SET `coins`=`coins`+'".($sit['cpc']-1)."' WHERE `id`='".$data['id']."'");
					$db->Query("UPDATE `users` SET `coins`=`coins`-'".$sit['cpc']."' WHERE `id`='".$sit['user']."'");
					$db->Query("UPDATE `askfm_like` SET `clicks`=`clicks`+'1' WHERE `id`='".$uid."'");
					$db->Query("UPDATE `web_stats` SET `value`=`value`+'1' WHERE `module_id`='askfm_like'");
					$db->Query("INSERT INTO `askfm_liked` (user_id, site_id) VALUES('".$data['id']."', '".$uid."')");
					$db->Query("UPDATE `module_session` SET `ses_key`='".$ses_key."' WHERE (`page_id`='".$sit['id']."' AND `module`='askfm_like') AND `ses_key`='".($ses_key-1)."'");
					$db->Query("INSERT INTO `user_clicks` (`uid`,`module`,`total_clicks`,`today_clicks`)VALUES('".$data['id']."','askfm_like','1','1') ON DUPLICATE KEY UPDATE `total_clicks`=`total_clicks`+'1', `today_clicks`=`today_clicks`+'1'");

					$msg = '<div class="msg"><div class="success">'.lang_rep($lang['b_358'], array('-NUM-' => ($sit['cpc']-1))).'</div></div>';
					$type = 'success';
				}else{
					$msg = '<div class="msg"><div class="error">'.$lang['b_300'].'</div></div>';
					$type = 'not_available';
				}
			}else{
				$msg = '<div class="msg"><div class="error">'.$lang['askfmlike_12'].'</div></div>';
				$type = 'error';
			}
		}

		$resultData = array('message' => $msg, 'type' => $type);

		header('Content-type: application/json');
		echo json_encode($resultData);
	}

	$db->Close();
?>