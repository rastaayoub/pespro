<?php
define('BASEPATH', true);
require('../../config.php');

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
	$value = urlencode(stripslashes($value));
	$req .= "&$key=$value";
}

// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.1\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Host: www.paypal.com\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

// assign posted variables to local variables
$item_name = $_POST['item_name'];
$item_number = $_POST['item_number'];
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];
$custom = $_POST['custom'];

if($fp){
	fputs ($fp, $header . $req);
	while (!feof($fp)) {
		$res = fgets ($fp, 1024);
		if (strcmp ($res, "VERIFIED") == 0) {
			$get_data = explode('|', $custom); 

			if($payment_status == 'Completed' && $payment_amount >= $site['vip_subscription_price']){
				$user = $db->QueryFetchArray("SELECT id,login,premium,ref FROM `users` WHERE `id`='".$get_data[0]."' LIMIT 1");
				if(!empty($user['id']) && $db->QueryGetNumRows("SELECT * FROM `transactions` WHERE `gateway`='paypal' AND `trans_id`='".$txn_id."' LIMIT 1") == 0){
					$db->Query("INSERT INTO `transactions` (`user`, `user_id`, `type`, `money`, `gateway`, `date`, `payer_email`, `user_ip`, `trans_id`) VALUES('".$user['login']."','".$user['id']."', '1', '".$payment_amount."', 'paypal', NOW(), '".$payer_email."', '".$get_data[1]."', '".$txn_id."')");
					if($user['id'] > 0){
						$premium = ($user['premium'] == 0 ? (time()+2592000) : (2592000+$user['premium']));
						$db->Query("UPDATE `users` SET `coins`=`coins`+'".$site['vip_monthly_coins']."', `premium`='".$premium."' WHERE `id`='".$user['id']."'");			
						$db->Query("INSERT INTO `user_transactions` (`user_id`,`type`,`value`,`cash`,`date`)VALUES('".$user['id']."','1','30','".$site['vip_subscription_price']."','".time()."')");
					}
					
					if(!empty($user['ref'])) {
						$affiliate = $db->QueryFetchArray("SELECT id FROM `users` WHERE `id`='".$user['ref']."' LIMIT 1");
						if(!empty($affiliate['id'])) {
							$commission = number_format(($payment_amount/100) * $site['ref_sale'], 2);
							affiliate_commission($affiliate['id'], $user['id'], $commission, 'vip_purchase');
						}
					}
				}
			}
		}
	}
	fclose ($fp);
}
?>