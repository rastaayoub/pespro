<?php
define('BASEPATH', true);
require('../../config.php');

define('DEBUG', 0);
define('LOG_FILE', './payeer.log');

if (!in_array($_SERVER['REMOTE_ADDR'], array('185.71.65.92', '185.71.65.189'))) {
	if(DEBUG == true) {
		error_log(date('[Y-m-d H:i e] '). "Invalid query server! ".$_SERVER['REMOTE_ADDR']." Valid servers are 185.71.65.92 and 185.71.65.189" . PHP_EOL, 3, LOG_FILE);
     }
} else {
	$arHash = array($_POST['m_operation_id'],
			$_POST['m_operation_ps'],
			$_POST['m_operation_date'],
			$_POST['m_operation_pay_date'],
			$_POST['m_shop'],
			$_POST['m_orderid'],
			$_POST['m_amount'],
			$_POST['m_curr'],
			$_POST['m_desc'],
			$_POST['m_status'],
			$site['payeer_secret']);
	$sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));

	$payment_id			= $db->EscapeString($_POST['m_operation_id']);
	$payee_account 		= $db->EscapeString($_POST['m_shop']);
	$payment_amount 	= $db->EscapeString($_POST['m_amount']);
	$payment_units		= $db->EscapeString($_POST['m_curr']);
	$item_id					= $db->EscapeString($_POST['m_orderid']);
	$item_desc 				= $db->EscapeString($_POST['m_desc']);
	$txn_id					= $db->EscapeString($_POST['m_operation_id']);

	if($sign_hash == $_POST['m_sign'])
	{
		$get_data = explode('|', base64_decode($item_desc)); 
		$user = $db->QueryFetchArray("SELECT id,login FROM `users` WHERE `id`='".$get_data[0]."' LIMIT 1");

		if($site['payeer_auto'] == 1)
		{
			if($db->QueryGetNumRows("SELECT * FROM `transactions` WHERE `gateway`='payeer' AND `trans_id`='".$txn_id."' LIMIT 1") == 0)
			{
				$db->Query("INSERT INTO `transactions` (`user`, `user_id`, `money`, `gateway`, `date`, `payer_email`, `user_ip`, `trans_id`) VALUES('".$user['login']."','".$user['id']."', '".$payment_amount."', 'payeer', NOW(), '".$payer_account."', '".$get_data[2]."', '".$txn_id."')");
				
				if($user['id'] > 0)
				{
					$db->Query("UPDATE `users` SET `account_balance`=`account_balance`+'".$payment_amount."' WHERE `id`='".$user['id']."'");	
				}
			} 
			else
			{
				if (DEBUG == true) {
					error_log(date('[Y-m-d H:i e] '). "Payment already processed: #".$payment_id . PHP_EOL, 3, LOG_FILE);
				}
			}
		}
		else
		{
			if($db->QueryGetNumRows("SELECT * FROM `transactions` WHERE `gateway`='payeer' AND `trans_id`='".$txn_id."' LIMIT 1") == 0){
				$db->Query("INSERT INTO `transactions` (`user`, `user_id`, `money`, `gateway`, `date`, `paid`, `payer_email`, `user_ip`, `trans_id`) VALUES('".$user['login']."','".$user['id']."', '".$payment_amount."', 'payeer', NOW(), '0', '".$payer_account."', '".$get_data[2]."', '".$txn_id."')");
			}
		}

		echo $_POST['m_orderid'].'|success';
	} else {
		if(DEBUG == true) {
			error_log(date('[Y-m-d H:i e] '). "Invalid Transaction hash! ".$txn_id." " . PHP_EOL, 3, LOG_FILE);
		}
	}
}
?>
