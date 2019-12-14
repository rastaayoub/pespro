<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

function executeSql($sqlFileToExecute){
    $templine = '';
	$lines    = file($sqlFileToExecute);
	$impError = 0;
	foreach($lines as $line) {
		if(substr($line, 0, 2) == '--' || $line == '')
			continue;
		$templine .= $line;
		if (substr(trim($line), -1, 1) == ';') {
			if (!mysql_query($templine)) {
				$impError = 1;
			}
			$templine = '';
		}
	}
    if ($impError == 0) {
        return 'Script is executed succesfully!';
    } else {
        return 'An error occured during SQL importing!';
    }
}

function redirect($location){
    $hs = headers_sent();
    if($hs === false){
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Location: '.$location);
    }elseif($hs == true){
        echo "<script>document.location.href='".htmlspecialchars($location)."'</script>";
    }
    exit(0);
}

function checkPwd($x,$y){
	if(empty($x) || empty($y) ) { return false; }
	if (strlen($x) < 6 || strlen($y) < 6) { return false; }
	if (strcmp($x,$y) != 0) { return false; } 
	return true;
}

function VisitorIP(){ 
    return $_SERVER['REMOTE_ADDR'];
}

function isEmail($email){
	return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? true : false;
}

function isUserID($username){
	return preg_match('/^[a-z\d_]{4,20}$/i', $username) ? true : false;
}

function GetHref($value){
	$qS = preg_replace(array('/p=[^&]*&?/', '/&$/'), array('', ''), $_SERVER['QUERY_STRING']);
	$qS = str_replace('&', '&amp;', $qS);
	
	if (!empty($qS)){
		$qS.= '&amp;';
	}
	
	return '?'.$qS.$value;
}

function ClearText($string){
	if(get_magic_quotes_gpc()){ 
		$string = stripslashes($string); 
	}
	
	return $string;
}

function truncate($str, $length, $trailing='...'){
	if(function_exists('mb_strlen') && function_exists('mb_substr')){
		$length-=mb_strlen($trailing);
		if(mb_strlen($str)> $length){
			return mb_substr($str,0,$length).$trailing;
		}else{
			return $str;
		}
	}else{
		return $str;
	}
} 

function get_data($url, $timeout = 15, $header = array(), $options = array()){
	if(!function_exists('curl_init')){
        return file_get_contents($url);
    }elseif(!function_exists('file_get_contents')){
        return '';
    }

	if(empty($options)){
		$options = array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
			CURLOPT_TIMEOUT => $timeout
		);
	}
	
	if(empty($header)){
		$header = array(
			"Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*\/*;q=0.5",
			"Accept-Language: en-us,en;q=0.5",
			"Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7",
			"Cache-Control: must-revalidate, max-age=0",
			"Connection: keep-alive",
			"Keep-Alive: 300",
			"Pragma: public"
		);
	}

	if($header != 'NO_HEADER'){
		$options[CURLOPT_HTTPHEADER] = $header;
	}
			
	$ch = curl_init();
	curl_setopt_array($ch, $options);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

function reverse_url($url){
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_HEADER,1);
	curl_setopt($ch,CURLOPT_NOBODY,1);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_TIMEOUT,4);
	curl_setopt($ch,CURLOPT_IPRESOLVE,CURL_IPRESOLVE_V4); 
	$result = curl_exec($ch);
	if(!empty($result)){
		return $result;
	}else{
		return null;
	}
}

function BBCode($string){
	$search = array(
			'/(\[b\])(.*?)(\[\/b\])/',
			'/(\[i\])(.*?)(\[\/i\])/',
			'/(\[u\])(.*?)(\[\/u\])/',
			'/(\[ul\])(.*?)(\[\/ul\])/',
			'/(\[li\])(.*?)(\[\/li\])/',
			'/(\[center\])(.*?)(\[\/center\])/',
			'/(\[img\])(.*?)(\[\/img\])/',
			'/(\[url=)(.*?)(\])(.*?)(\[\/url\])/',
			'/(\[url\])(.*?)(\[\/url\])/'
	);
	$replace = array(
			'<b>$2</b>',
			'<em>$2</em>',
			'<u>$2</u>',
			'<ul>$2</ul>',
			'<li>$2</li>',
			'<center>$2</center>',
			'<img src="$2" alt="" />',
			'<a href="$2" target="_blank">$4</a>',
			'<a href="$2" target="_blank">$2</a>'
	);
	return preg_replace($search, $replace, $string);
}
 
function percent($num_amount, $num_total){
	$count = ($num_amount/$num_total)*100;
	return number_format($count,0);
}

function get_country($code){
	global $db;
	$code = $db->EscapeString($code);
	$country = $db->QueryFetchArray("SELECT country FROM `list_countries` WHERE `code`='".$code."' LIMIT 1");
	return $country['country'];
}

function get_gender($id, $man='Man', $woman='Woman', $unknow='Unknown'){
	$gender = ($id == 1 ? $man : ($id == 2 ? $woman : $unknow));
	return $gender;
}

function get_currency_symbol($code){
	$code = ($code == 'USD' ? '$' : ($code == 'EUR' ? '&euro;' : ($code == 'GBP' ? '&pound;' : ($code == 'HUF' ? 'Ft' : ($code == 'JPY' ? '&yen;' : $code)))));
	return $code;
}

function hideref($strUrl, $protect = 1, $key = 0){
	if($protect == 0){
		return $strUrl;
	}elseif($protect == 2 && !empty($key)){
		return "http://rs.hideref.org/r/".$key."/".$strUrl;
	}else{
		return "http://hideref.org/?".$strUrl;
	}
}

function blacklist_check($value, $type = 0){
	global $db;
	if($type < 1 || $type > 3){
		return 0;
	}
	
	if($type == 1 || $type == 2){
		$blacklist = $db->QueryFetchArrayAll("SELECT value FROM `blacklist` WHERE `type`='".$type."'");
		foreach($blacklist as $black){    
			if(strpos($value, $black['value'])){
				return 1;
				break;
			}
		}
	}elseif($type == 3){
		$blacklist = $db->QueryGetNumRows("SELECT * FROM `blacklist` WHERE `type`='3' AND `value`='".$value."' LIMIT 1");
		if($blacklist > 0){
			return 1;
		}
	}
	return 0;
}

function iptocountry($ip) {
	$numbers = preg_split("/\./", $ip); 
	if(!is_numeric($numbers[0]) && $numbers[0] >= 0 && $numbers[0] <= 255){
		return false;
	}
	include('ip_files/'.$numbers[0].'.php');
	$code = ($numbers[0] * 16777216) + ($numbers[1] * 65536) + ($numbers[2] * 256) + ($numbers[3]);   
	foreach($ranges as $key => $value){
		if($key <= $code){
			if($ranges[$key][0] >= $code){
				$country = $ranges[$key][1];
				break;
			}
		}
	}
	return (empty($country) ? 'unknown' : $country);
}

function lang_rep($text, $inputs = array()){
	if (empty($inputs) || !is_array($inputs)) return $text;
			
	foreach ($inputs as $search => $replace){
		$text = str_replace($search, $replace, $text);
	}

	return $text;
}

function MemoryUsage($decimals = 2)
{
	$result = 0;

	if(function_exists('memory_get_usage')){
		$result = memory_get_usage() / 1024;
	}else{
		if(function_exists('exec')){
			$output = array();

			if(substr(strtoupper(PHP_OS), 0, 3) == 'WIN'){
				exec('tasklist /FI "PID eq ' . getmypid() . '" /FO LIST', $output);
				$result = preg_replace('/[\D]/', '', $output[5]);
			}else{
				exec('ps -eo%mem,rss,pid | grep ' . getmypid(), $output);
				$output = explode('  ', $output[0]);
				$result = $output[1];
			}
		}
	}

	return number_format(intval($result) / 1024, $decimals, '.', '');
}

function affiliate_commission($user, $referral, $commission, $type = 'unknown', $date) {
	global $db;
	
	$date = (empty($date) ? time() : $date);
	
	if(!empty($user) && !empty($referral) && !empty($commission)){
		$db->Query("UPDATE `users` SET `account_balance`=`account_balance`+'".$commission."' WHERE `id`='".$user."'");
		$db->Query("INSERT INTO `affiliate_transactions` (`user`,`referral`,`commission`,`type`,`date`) VALUES ('".$user."','".$referral."','".$commission."','".$type."','".$date."')");
	}
}

/* Register filters - used for modules */
$filter_events = array();

function hook_filter($event,$content) {
    global $filter_events;
    if(isset($filter_events[$event])){
        foreach($filter_events[$event] as $func){
            if(!function_exists($func)) {
                die('Unknown function: '.$func);
            }
			$content = call_user_func($func,$content);
        }
    }
    return $content;
}

function register_filter($event, $func){
    global $filter_events;
    $filter_events[$event][] = $func;
}

function check_license($email,$domain){
/*	$qry_str = "email=".$email."&host=".$domain;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://mn-shop.net/license/pes_check.php"); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $qry_str);
	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 1);
	$result = trim(curl_exec($ch));
	curl_close($ch);
	if($result != ''){
		return $result;
	}else{
*/		return 'true';
/*	}*/
}
?>