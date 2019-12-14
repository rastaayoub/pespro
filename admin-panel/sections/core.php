<?php
/**
 * PES PRO Nulled BY MTimer
 * Version: 2.0
 *
 * Author: MTimer
 * Website: http://www.mtimer.net
 **/

if (!defined("BASEPATH")) 
{
	exit("Unable to view file.");
}
if ($is_online && $data["admin"] == 0) 
{
	redirect($site["site_url"]);
	exit;
} elseif (!$is_online) 
{
	redirect("login.php");
	exit;
}
if (isset($_GET["version"])) 
{
	echo "Nulled BY MTimer";
	exit;
}
if (isset($_GET["changelog"])) 
{
	echo "Donate paypal: mtimercms@hotmail.com\nWebmoney: Z369907552397 R374005435218";
	exit;
}
$page_name = "dashboard";
if (isset($_GET["x"]) && isset($action[$_GET["x"]])) 
{
	$page_name = $_GET["x"];
}
function generateLicense($license, $order_id) 
{
	$domain = str_replace("www.", "", $_SERVER["HTTP_HOST"]);
	$content = base64_encode(base64_encode(time()) . "(||)" . base64_encode($license) . "(||)" . base64_encode($order_id) . "(||)" . base64_encode($domain));
	return $content;
}
function decodeLicense($license) 
{
	$license = base64_decode($license);
	$license = explode("(||)", $license);
	$result = array();
	$result["date"] = base64_decode($license[0]);
	$result["hash_key"] = base64_decode($license[1]);
	$result["order_id"] = base64_decode($license[2]);
	$result["domain"] = base64_decode($license[3]);
	return $result;
}
function checkLicense($hash_key, $order_id, $domain) 
{
/*	if (!function_exists("curl_init")) 
	{
		return "NO_CURL";
	}
	if (empty($domain)) 
	{
		$domain = str_replace("www.", "", $_SERVER["HTTP_HOST"]);
	}
	$qry_str = "hash=" . $hash_key . "&oid=" . $order_id . "&host=" . $domain;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://mn-shop.net/license/pes_v2.php");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $qry_str);
	$content = trim(curl_exec($ch));
	curl_close($ch);
	if ($content == "true" || $content == "out_of_stock") 
	{
*/		return "VALID";
/*	} elseif ($content != "true" && $content != "false" && $content != "out_of_stock") 
	{
		return "VALID";
	} else
	{
		return "NO_LICENSE";
	}*/
}
$licenseStatus = 0;
if (!empty($site["license_file"])) 
{
	$license = decodeLicense($site["license_file"]);
	if (empty($license["date"]) || $license["date"] < (time() - 86400)) 
	{
		$checkLicense = checkLicense($license["hash_key"], $license["order_id"]);
		if ($checkLicense == "VALID") 
		{
			$licenseStatus = 1;
			$licenseFile = generateLicense($license["hash_key"], $license["order_id"]);
			$updateLicense = $db->Query("UPDATE `site_config` SET `config_value`='" . $licenseFile . "' WHERE `config_name`='license_file'");
		}
	} else
	{
		$licenseStatus = 1;
	}
}
if ($licenseStatus == 0) 
{
	$resultMSG = "";
	if (isset($_POST["license"])) 
	{
		if (empty($_POST["license"]) || empty($_POST["order_id"])) 
		{
			$resultMSG = "<p style=\"color:red\">Please complete all fiels!</p>";
		} else
		{
			$sLicense = preg_replace("/\\s+/", "", $_POST["license"]);
			$sOrderID = preg_replace("/[^0-9]/", "", $_POST["order_id"]);
			$license_check = checkLicense(MD5($sLicense), $sOrderID);
			if ($license_check == "NO_CURL") 
			{
				$resultMSG = "<p style=\"color:red\">cURL function must be Enabled on your server!</p>";
			} elseif ($license_check == "VALID") 
			{
				$licenseFile = generateLicense(MD5($sLicense), $sOrderID);
				$db->Query("UPDATE `site_config` SET `config_value`='" . $licenseFile . "' WHERE `config_name`='license_file'");
				redirect("index.php");
			} else
			{
				$resultMSG = "<p style=\"color:red\"><b>Invalid license!</b> This license doesn't exists or was closed. Contact our support for more details!</p>";
			}
		}
	}
	echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n<title>PES Pro - Check License</title>\n<style> body{background:#e3e3e3 url(img/extra/login_bg.png)}a{text-decoration:none;color:blue}.form_box{background:url(img/extra/login_textbox.png);width:302px;height:37px;border:0;padding:0 0 0 7px;font-size:14px}.box{margin:120px auto 0;background:#ededed;width:350px;text-align:center;padding-top:10px;font-family:\"Lucida Sans Unicode\",\"Lucida Grande\",sans-serif;background-image:url(img/extra/login_box.png);border:1px solid #ccc;-moz-border-radius:10px;-webkit-border-radius:10px;border-radius:10px}.button{text-decoration:none;font-size:12px;padding:0 20px 2px 20px;cursor:pointer;border:1px solid #b8b8b8;-moz-border-radius:5px;-webkit-border-radius:5px;border-radius:5px;box-sizing:content-box;background-color:#FFF;background-image:url(img/extra/login_button.png);background-position:bottom;height:26px}.button:hover{border-color:#737373}.footer{font-family:Helvetica;font-size:12px;text-align:center;margin-top:5px;color:#a3a3a3;text-shadow:#c9c9c9 0 1px 0}.footer a{color:#4c4c4c;text-decoration:none}.footer a:hover{text-decoration:underline}.form_text{font-size:14px;text-align:left;color:#656565;font-family:Helvetica;margin-top:8px;padding-left:20px}.info{font:12px/1 \"Lucida Grande\",\"Lucida Sans Unicode\",\"Lucida Sans\",Geneva,Verdana,sans-serif;text-align:left;padding-left:20px;padding-right:5px;margin-bottom:20px;margin-top:5px} </style>\n</head>\n<body>\n\t<div class=\"box\">";
	echo $resultMSG;
	echo "\t\t<form method=\"post\">\n\t\t\t<div class=\"form_text\">Order ID</div>\n\t\t\t<input name=\"order_id\" type=\"text\" id=\"key\" class=\"form_box\" size=\"50\"><br /><br />\n\t\t\t<div class=\"form_text\">Serial Key</div>\n\t\t\t<input name=\"license\" type=\"text\" id=\"key\" class=\"form_box\" size=\"50\"><br /><br />\n\t\t\t<input class=\"button\" type=\"submit\" value=\"Unlock Admin\" /><br /><br />\n\t\t\t<div class=\"info\">If you are seeing this screen means that you haven't added your Serial Key. You can find your Serial Key and Order ID at <a href=\"https://mn-shop.net/account/download\" target=\"_blank\">MN-Shop.net/account/download</a></div>\n\t\t</form>\n\t</div>\n\t<div class=\"footer\">All right reserved &copy; ";
	echo date("Y");
	echo " <a href=\"http://mn-shop.net\">MN-Shop.net</a></div>\n</body>\n</html>\n";
	exit;
}
?>