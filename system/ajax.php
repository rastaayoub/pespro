<?
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
				$db->Query("UPDATE `users` SET `coins`=`coins`+'".($data['premium'] > 0 ? $site['daily_bonus_vip'] : $site['daily_bonus'])."', `daily_bonus`='".time()."' WHERE `id`='".$data['id']."'");
				echo '1';
			}else{
				echo '0';
			}
		}elseif($_GET['a'] == 'bannerPacks' && is_numeric($_GET['type'])){
			$type = $db->EscapeString($_GET['type']);
			$packs = $db->QueryFetchArrayAll("SELECT * FROM `ad_packs` WHERE `type`='".$type."' ORDER BY `price` ASC");
			foreach($packs as $pack){
				echo '<option value="'.$pack['id'].'">'.$pack['days'].' '.$lang['b_178'].' - '.$pack['price'].' '.get_currency_symbol($site['currency_code']).'</option>';
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
		}
	}
}

if($_GET['a'] == 'getStats'){
	$users = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `users`");
	$online = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `users` WHERE (".time()."-UNIX_TIMESTAMP(`online`)) < 3600");
	$banned = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `users` WHERE `banned`='1'");
	$clicks = $db->QueryFetchArray("SELECT SUM(`value`) AS `total` FROM `web_stats`");

	$pStats = hook_filter('stats',"");
	$pFoot = '<td><b>'.$lang['b_142'].'</b></td><td><b>'.number_format(hook_filter('tot_sites',"")).'</b></td><td><b>'.number_format($clicks['total']).'</b></td>';
	$sMembers = '<tr><td>'.number_format($users['total']-$banned['total']).'</td><td>'.number_format($online['total']).'</td><td>'.number_format($banned['total']).'</td><td>'.number_format($users['total']).'</td></tr>';
	
	$statsData = array(
		'pages_body' => $pStats,
		'pages_foot' => $pFoot,
		'members' => $sMembers
	);
	
	header('Content-type: application/json');
	echo json_encode($statsData);
}

$db->Close();
?>