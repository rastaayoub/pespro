<?php
    define('BASEPATH', true);
    require('../../config.php');
    if(!$is_online){ echo '<div class="msg"><div class="error">'.$lang['fb_share_06'].'</div></div>'; exit; }

    function get_shares($url){
        global $site;
	$get = get_data('https://graph.facebook.com/?ids='.urlencode($url).'&fields=engagement&access_token='.$site['fb_app_id'].'|'.$site['fb_app_secret']); 
	$result = json_decode($get, true);

	return (!isset($result[$url]['engagement']['share_count']) ? '0' : $result[$url]['engagement']['share_count']);
    }

    if(isset($_POST['get']) && $_POST['pid'] > 0){
        $pid = $db->EscapeString($_POST['pid']);
        $sit = $db->QueryFetchArray("SELECT url FROM `fb_share` WHERE `id`='".$pid."'");
        $key = get_shares($sit['url']);

        $result    = $db->Query("INSERT INTO `module_session` (`user_id`,`page_id`,`ses_key`,`module`,`timestamp`)VALUES('".$data['id']."','".$pid."','".$key."','fb_share','".time()."') ON DUPLICATE KEY UPDATE `ses_key`='".$key."'");

        $msg = ($result ? '<div class="msg"><div class="info">'.$lang['fb_22'].'</div></div>' : '<div class="msg"><div class="error">'.$lang['fb_08'].'</div></div>');
        $type = ($result ? 'success' : 'error');

        $resultData = array('message' => $msg, 'type' => $type);

        header('Content-type: application/json');
        echo json_encode($resultData);
    }elseif(isset($_POST['step']) && $_POST['step'] == "skip" && is_numeric($_POST['sid']) && !empty($data['id'])){
        $id = $db->EscapeString($_POST['sid']);

        if($db->QueryGetNumRows("SELECT site_id FROM `fb_shared` WHERE `user_id`='".$data['id']."' AND `site_id`='".$id."' LIMIT 1") == 0){
            $db->Query("INSERT INTO `fb_shared` (user_id, site_id) VALUES('".$data['id']."', '".$id."')");
            echo '<div class="msg"><div class="info">'.$lang['b_359'].'</div></div>';
        }
    }elseif(isset($_POST['id'])){
        $id = $db->EscapeString($_POST['id']);
        $sit = $db->QueryFetchArray("SELECT a.id,a.user,a.url,a.cpc,b.id AS uid,b.coins FROM fb_share a JOIN users b ON b.id = a.user WHERE a.id = '".$id."' LIMIT 1");

        if(empty($sit['uid']) || empty($sit['id']) || empty($data['id']) || $sit['coins'] < $sit['cpc'] || $sit['cpc'] < 2){
            $msg = '<div class="msg"><div class="error">'.$lang['b_300'].'</div></div>';
            $type = 'not_available';
        }else{
            $mod_ses = $db->QueryFetchArray("SELECT ses_key FROM `module_session` WHERE `user_id`='".$data['id']."' AND `page_id`='".$sit['id']."' AND `module`='fb_share' LIMIT 1");
            sleep(3);
            $ses_key = get_shares($sit['url']);

            if($mod_ses['ses_key'] != '' && $ses_key > $mod_ses['ses_key']){
                $check = $db->QueryGetNumRows("SELECT site_id FROM `fb_shared` WHERE `user_id`='".$data['id']."' AND `site_id`='".$sit['id']."' LIMIT 1");

                if($check == 0){
                    $db->Query("UPDATE `users` SET `coins`=`coins`+'".($sit['cpc']-1)."' WHERE `id`='".$data['id']."'");
                    $db->Query("UPDATE `users` SET `coins`=`coins`-'".$sit['cpc']."' WHERE `id`='".$sit['user']."'");
                    $db->Query("UPDATE `fb_share` SET `clicks`=`clicks`+'1', `today_clicks`=`today_clicks`+'1' WHERE `id`='".$id."'");
                    $db->Query("UPDATE `web_stats` SET `value`=`value`+'1' WHERE `module_id`='fb_share'");
                    $db->Query("INSERT INTO `fb_shared` (user_id, site_id) VALUES('".$data['id']."','".$sit['id']."')");
                    $db->Query("UPDATE `module_session` SET `ses_key`='".$ses_key."' WHERE (`page_id`='".$sit['id']."' AND `module`='fb_share') AND `ses_key`='".($ses_key-1)."'");
                    $db->Query("INSERT INTO `user_clicks` (`uid`,`module`,`total_clicks`,`today_clicks`)VALUES('".$data['id']."','fb_share','1','1') ON DUPLICATE KEY UPDATE `total_clicks`=`total_clicks`+'1', `today_clicks`=`today_clicks`+'1'");

                    $msg = '<div class="msg"><div class="success">'.lang_rep($lang['b_358'], array('-NUM-' => ($sit['cpc']-1))).'</div></div>';
                    $type = 'success';
                }else{
                    $msg = '<div class="msg"><div class="error">'.$lang['b_300'].'</div></div>';
                    $type = 'not_available';
                }
            }else{
                $msg = '<div class="msg"><div class="error">'.$lang['fb_23'].' '.$ses_key.'</div></div>';
                $type = 'error';
            }
        }

        $resultData = array('message' => $msg, 'type' => $type);

        header('Content-type: application/json');
        echo json_encode($resultData);
    }
    $db->Close();
?>