<?php
define('BASEPATH', true);
require('../../config.php');

// CONFIG: Enable debug mode. This means we'll log requests into 'ipn.log' in the same directory.
// Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
// Set this to 0 once you go live or don't require logging.
define('DEBUG', 0);
define('USE_SANDBOX', 0);
define('LOG_FILE', './ipn.log');


// Read POST data
// reading posted data directly from $_POST causes serialization
// issues with array data in POST. Reading raw POST data from input stream instead.
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
	$keyval = explode ('=', $keyval);
	if (count($keyval) == 2)
		$myPost[$keyval[0]] = urldecode($keyval[1]);
}

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc')) {
	$get_magic_quotes_exists = true;
}
foreach ($myPost as $key => $value) {
	if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
		$value = urlencode(stripslashes($value));
	} else {
		$value = urlencode($value);
	}
	$req .= "&$key=$value";
}

// Post IPN data back to PayPal to validate the IPN data is genuine
// Without this step anyone can fake IPN data

if(USE_SANDBOX == true) {
	$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
} else {
	$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
}

$ch = curl_init($paypal_url);
if ($ch == FALSE) {
	return FALSE;
}

curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

if(DEBUG == true) {
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
}

// CONFIG: Optional proxy configuration
//curl_setopt($ch, CURLOPT_PROXY, $proxy);
//curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);

// Set TCP timeout to 30 seconds
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

// CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
// of the certificate as shown below. Ensure the file is readable by the webserver.
// This is mandatory for some environments.

//$cert = __DIR__ . "./cacert.pem";
//curl_setopt($ch, CURLOPT_CAINFO, $cert);

$res = curl_exec($ch);
if (curl_errno($ch) != 0) // cURL error
	{
	if(DEBUG == true) {	
		error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
	}
	curl_close($ch);
	exit;

} else {
		// Log the entire HTTP response if debug is switched on.
		if(DEBUG == true) {
			error_log(date('[Y-m-d H:i e] '). "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
			error_log(date('[Y-m-d H:i e] '). "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);

			// Split response headers and payload
			$tokens = explode("\r\n\r\n", $res); // Avoid passing 2 as the last argument, so the remaining token can be picked up
			$res = trim(end($tokens)); // Set $res to be the last token in the response
		}
		curl_close($ch);
}

// Inspect IPN validation result and act accordingly

if (strcmp ($res, "VERIFIED") == 0) {
	// process payment and mark item as paid.
	$payment_status = $_POST['payment_status'];
	$payment_amount = $_POST['mc_gross'];
	$payment_currency = $_POST['mc_currency'];
	$txn_id = $_POST['txn_id'];
	$receiver_email = $_POST['receiver_email'];
	$payer_email = $_POST['payer_email'];
	$custom = $_POST['custom'];
	
	$get_data = explode('|', $custom); 
		
	if($payment_status == 'Completed'){
		$user = $db->QueryFetchArray("SELECT id,login FROM `users` WHERE `id`='".$get_data[0]."' LIMIT 1");
		if($site['paypal_auto'] == 1){
			if($db->QueryGetNumRows("SELECT * FROM `transactions` WHERE `gateway`='paypal' AND `trans_id`='".$txn_id."' LIMIT 1") == 0){
				$db->Query("INSERT INTO `transactions` (`user`, `user_id`, `money`, `gateway`, `date`, `payer_email`, `user_ip`, `trans_id`) VALUES('".$user['login']."','".$user['id']."', '".$payment_amount."', 'paypal', NOW(), '".$payer_email."', '".$get_data[2]."', '".$txn_id."')");
				if($user['id'] > 0){
					$db->Query("UPDATE `users` SET `account_balance`=`account_balance`+'".$payment_amount."' WHERE `id`='".$user['id']."'");			
				}
			} else if (DEBUG == true) {
				error_log(date('[Y-m-d H:i e] '). "Payment already processed: #$txn_id" . PHP_EOL, 3, LOG_FILE);
			}
		}else{
			if($db->QueryGetNumRows("SELECT * FROM `transactions` WHERE `gateway`='paypal' AND `trans_id`='".$txn_id."' LIMIT 1") == 0){
				$db->Query("INSERT INTO `transactions` (`user`, `user_id`, `money`, `gateway`, `date`, `paid`, `payer_email`, `user_ip`, `trans_id`) VALUES('".$user['login']."','".$user['id']."', '".$payment_amount."', 'paypal', NOW(), '0', '".$payer_email."', '".$get_data[2]."', '".$txn_id."')");
			}
		}
	} else if (DEBUG == true) {
		error_log(date('[Y-m-d H:i e] '). "Invalid Payment Status: $payment_status" . PHP_EOL, 3, LOG_FILE);
	}
	
	if(DEBUG == true) {
		error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
	}
} else if (strcmp ($res, "INVALID") == 0) {
	if(DEBUG == true) {
		error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
	}
} else {
	if(DEBUG == true) {
		error_log(date('[Y-m-d H:i e] '). "Response log: $res" . PHP_EOL, 3, LOG_FILE);
	}
}
?>