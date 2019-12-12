<?php
define('BASEPATH', true);
define('IS_AJAX', true);
require('config.php');

if($is_online){
	if(isset($_GET['a'])){
		if($_GET['a'] == 'getCoins'){
			echo number_format($data['coins']);
		}elseif($_GET['a'] == 'dailyBonus'){
			$cf_bonus = $db->QueryFetchArray("SELECT SUM(`today_clicks`) AS `clicks` FROM `user_clicks` WHERE `uid`='".$data['id']."'");
			$cf_bonus = ($cf_bonus['clicks'] > 0 ? $cf_bonus['clicks'] : 0);
			
			if(($data['daily_bonus']+86400) < time() && $cf_bonus >= $site['crf_bonus']){
				$myLevel = userLevel($data['id'], 0);
				$db->Query("UPDATE `users` SET `coins`=`coins`+'".($data['premium'] > 0 ? $myLevel['vip_bonus'] : $myLevel['free_bonus'])."', `daily_bonus`='".time()."' WHERE `id`='".$data['id']."'");
				echo '1';
			}else{
				echo '0';
			}
		}elseif($_GET['a'] == 'bannerPacks' && is_numeric($_GET['type'])){
			$type = $db->EscapeString($_GET['type']);
			$packs = $db->QueryFetchArrayAll("SELECT * FROM `ad_packs` WHERE `type`='".$type."' ORDER BY `price` ASC");
			foreach($packs as $pack){
				echo '<option value="'.$pack['id'].'">'.$pack['days'].' '.$lang['b_178'].' - '.get_currency_symbol($site['currency_code']).$pack['price'].'</option>';
			}
		}elseif($_GET['a'] == 'getReward' && is_numeric($_GET['rID'])){
			$rID = $db->EscapeString($_GET['rID']);
			$reward = $db->QueryFetchArray("SELECT * FROM `activity_rewards` WHERE `id`='".$rID."' LIMIT 1");

			if(!empty($reward['id'])){
				$total_clicks = $db->QueryFetchArray("SELECT SUM(`total_clicks`) AS `clicks` FROM `user_clicks` WHERE `uid`='".$data['id']."'");

				if($reward['exchanges'] > $total_clicks['clicks']){
					$type = 'error';
					$msg = lang_rep($lang['b_330'], array('-NUM-' => number_format($reward['exchanges'])));
				}elseif($db->QueryGetNumRows("SELECT * FROM `activity_rewards_claims` WHERE `reward_id`='".$reward['id']."' AND `user_id`='".$data['id']."' LIMIT 1") > 0){
					$type = 'error';
					$msg = $lang['b_334'];
				}else{
					if($reward['type'] == 1){
						$premium = ($data['premium'] == 0 ? (time()+(86400*$reward['reward'])) : ((86400*$reward['reward'])+$data['premium']));
						$db->Query("UPDATE `users` SET `premium`='".$premium."' WHERE `id`='".$data['id']."'");
					}else{
						$db->Query("UPDATE `users` SET `coins`=`coins`+'".$reward['reward']."' WHERE `id`='".$data['id']."'");
					}
					$db->Query("UPDATE `activity_rewards` SET `claims`=`claims`+'1' WHERE `id`='".$reward['id']."'");
					$db->Query("INSERT INTO `activity_rewards_claims` (`reward_id`,`user_id`,`date`)VALUES('".$reward['id']."','".$data['id']."','".time()."')");

					$type = 'success';
					$msg = lang_rep($lang['b_335'], array('-REWARD-' => ($reward['reward'].' '.($reward['type'] == 1 ? $lang['b_246'] : $lang['b_156']))));
				}
			}else{
				$type = 'error';
				$msg = $lang['b_333'];
			}

			$resultData = array('message' => $msg, 'type' => $type);

			header('Content-type: application/json');
			echo json_encode($resultData);
		}elseif($_GET['a'] == 'adminStats'){
			$adStats = array(
				'pending_proofs',
				'pending_reports'
			);
			
			foreach ($adStats as $ad_id){
				if($ad_id == 'pending_proofs'){
					$addData = 0;
					if($site['allow_withdraw'] == 1){
						$addData = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `payment_proofs` WHERE `approved`='0'");
						$addData = number_format($addData['total']);
					}
				}elseif($ad_id == 'pending_reports'){
					$addData = $db->QueryFetchArray("SELECT COUNT(*) AS `total` FROM `reports` WHERE `status`='0'");
					$addData = number_format($addData['total']);
				}
			
				$statsData[] = array(
					'class' => $ad_id,
					'data' => $addData
				);
			}
			
			header('Content-type: application/json');
			echo json_encode($statsData);
		}
	}elseif(isset($_POST['a'])){
		if($_POST['a'] == 'reportPage'){
			if(isset($_POST['id']) && isset($_POST['url']) && isset($_POST['module']) && isset($_POST['reason']) && !empty($data['id'])) {
				$pid = $db->EscapeString($_POST['id']);
				$url = $db->EscapeString(base64_decode($_POST['url']));
				$mod = $db->EscapeString($_POST['module']);
				$reason = $db->EscapeString($_POST['reason']);

				$page = $db->QueryFetchArray("SELECT id,user FROM `".$mod."` WHERE `id`='".$pid."' LIMIT 1");
				if(!empty($page['id'])){
					if($db->QueryGetNumRows("SELECT id FROM `reports` WHERE (`page_id`='".$pid."' AND `module`='".$mod."') AND `status`='0' LIMIT 1") > 0){
						$db->Query("UPDATE `reports` SET `count`=`count`+'1' WHERE (`page_id`='".$pid."' AND `module`='".$mod."') AND `status`='0'");
						$msg = $lang['b_236'];
						$type = 'success';
					}elseif($data['admin'] != 1 && $site['report_limit'] > 0 && $db->QueryGetNumRows("SELECT id FROM `reports` WHERE `reported_by`='".$data['id']."' AND `status`='0'") >= $site['report_limit']){
						$msg = lang_rep($lang['b_252'], array('-NUM-' => $site['report_limit']));
						$type = 'error';
					}else{
						$db->Query("INSERT INTO `reports` (`page_id`,`page_url`,`owner_id`,`reported_by`,`reason`,`module`,`timestamp`)VALUES('".$page['id']."','".$url."','".$page['user']."','".$data['id']."','".$reason."','".$mod."','".time()."')");
						$msg = $lang['b_236'];
						$type = 'success';
					}
				}else{
					$msg = $lang['b_237'];
					$type = 'error';
				}
			}else{
				$msg = $lang['b_237'];
				$type = 'error';
			}
			
			$resultData = array('message' => $msg, 'type' => $type);

			header('Content-type: application/json');
			echo json_encode($resultData);
		}
	}
}else{
	if(isset($_GET['a'])){
		if(isset($_GET['data']) && $_GET['a'] == 'checkUser'){
			$aData = $db->EscapeString($_GET['data']);
			$check = (isUserID($aData) ? 1 : 0);
			if($check == 1){
				$check = $db->QueryGetNumRows("SELECT id FROM `users` WHERE `login`='".$aData."' LIMIT 1");
				$check = ($check > 0 ? 0 : 1);
			}

			echo $check;
		}elseif(isset($_GET['data']) && $_GET['a'] == 'checkEmail'){
			$aData = $db->EscapeString($_GET['data']);
			if(!isEmail($aData)){
				echo 0;
			}else{
				$check = $db->QueryGetNumRows("SELECT id FROM `users` WHERE `email`='".$aData."' LIMIT 1");
				echo ($check > 0 ? 0 : 1);
			}
		}elseif($_GET['a'] == 'getSideStats'){
			$sUsers = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `users`");
			$sCash = $db->QueryFetchArray("SELECT SUM(`amount`) AS `total` FROM `requests` WHERE `paid`='1'");
			$sClick = $db->QueryFetchArray("SELECT SUM(`value`) AS `total` FROM `web_stats`");

			$statsData = array(
				'payouts' => get_currency_symbol($site['currency_code']).number_format($sCash['total'], 2),
				'exchanges' => number_format($sClick['total']),
				'members' => number_format($sUsers['total'])
			);
			
			header('Content-type: application/json');
			echo json_encode($statsData);
		}
	}
}

if($_GET['a'] == 'getStats'){
	$online = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `users` WHERE (".time()."-UNIX_TIMESTAMP(`online`)) < 1800");
	$clicks = $db->QueryFetchArray("SELECT SUM(`value`) AS `total` FROM `web_stats`");
	$users = $db->QueryFetchArray("SELECT COUNT(`id`) AS `total`, COUNT(CASE WHEN `banned`='1' THEN 1 END) AS `banned` FROM `users`");

	$pStats = hook_filter('stats',"");
	$pFoot = '<td><b>'.$lang['b_142'].'</b></td><td><b>'.number_format(hook_filter('tot_sites',"")).'</b></td><td><b>'.number_format($clicks['total']).'</b></td>';
	$sMembers = '<tr><td>'.number_format($users['total']-$users['banned']).'</td><td>'.number_format($online['total']).'</td><td>'.number_format($users['banned']).'</td><td>'.number_format($users['total']).'</td></tr>';
	
	$statsData = array(
		'pages_body' => $pStats,
		'pages_foot' => $pFoot,
		'members' => $sMembers
	);
	
	header('Content-type: application/json');
	echo json_encode($statsData);
}
?>