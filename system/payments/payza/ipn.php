<?php
define('BASEPATH', true);
require('../../config.php');

//The value is the url address of IPN V2 handler and the identifier of the token string 
define('IPN_V2_HANDLER', 'https://secure.payza.com/ipn2.ashx');
define('TOKEN_IDENTIFIER', 'token=');
define('LOG_FILE', './ipn.log');

// get the token from Payza
$token = urlencode($_POST['token']);

//preappend the identifier string "token=" 
$token = TOKEN_IDENTIFIER.$token;

/**
 * 
 * Sends the URL encoded TOKEN string to the Payza's IPN handler
 * using cURL and retrieves the response.
 * 
 * variable $response holds the response string from the Payza's IPN V2.
 */

$response = '';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, IPN_V2_HANDLER);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $token);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 120);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);

curl_close($ch);

if(strlen($response) > 0)
{
	if(urldecode($response) == "INVALID TOKEN")
	{
		error_log(date('[Y-m-d H:i e] '). "Invalid token: $response" . PHP_EOL, 3, LOG_FILE);
	}
	else
	{
		//urldecode the received response from Payza's IPN V2
		$response = urldecode($response);
		
		//split the response string by the delimeter "&"
		$aps = explode("&", $response);

		//define an array to put the IPN information
		$info = array();

		foreach ($aps as $ap)
		{
			//put the IPN information into an associative array $info
			$ele = explode("=", $ap);
			$info[$ele[0]] = $ele[1];
		}

		//setting information about the transaction from the IPN information array
		$receivedMerchantEmailAddress = $info['ap_merchant'];
		$transactionStatus = $info['ap_status'];
		$testModeStatus = $info['ap_test'];
		$AmountReceived = $info['ap_totalamount'];
		$transactionID = $info['ap_referencenumber'];
		$currency = $info['ap_currency'];
		$customerEmailAddress = $info['ap_custemailaddress'];
		$myCustom = $info['apc_1'];
		
		
		if ($receivedMerchantEmailAddress == $site['payza'] && $transactionStatus == 'Success' && $testModeStatus != '1') {
			$get_data = explode('|', $myCustom); 
			
			$user = $db->QueryFetchArray("SELECT id,login FROM `users` WHERE `id`='".$get_data[0]."' LIMIT 1");
			if($site['payza_auto'] == 1){
				if($db->QueryGetNumRows("SELECT * FROM `transactions` WHERE `gateway`='payza' AND `trans_id`='".$transactionID."' LIMIT 1") == 0){
					$db->Query("INSERT INTO `transactions` (`user`, `user_id`, `money`, `gateway`, `date`, `payer_email`, `user_ip`, `trans_id`) VALUES('".$user['login']."','".$user['id']."', '".$AmountReceived."', 'payza', NOW(), '".$customerEmailAddress."', '".$get_data[2]."', '".$transactionID."')");
					if($user['id'] > 0){
						$db->Query("UPDATE `users` SET `account_balance`=`account_balance`+'".$AmountReceived."' WHERE `id`='".$user['id']."'");
					}
				}
			}else{
				if($db->QueryGetNumRows("SELECT * FROM `transactions` WHERE `gateway`='payza' AND `trans_id`='".$transactionID."' LIMIT 1") == 0){
					$db->Query("INSERT INTO `transactions` (`user`, `user_id`, `money`, `gateway`, `date`, `paid`, `payer_email`, `user_ip`, `trans_id`) VALUES('".$user['login']."','".$user['id']."', '".$AmountReceived."', 'payza', NOW(), '0', '".$customerEmailAddress."', '".$get_data[2]."', '".$transactionID."')");
				}
			}
		}
	}
}
?>