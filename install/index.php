<?php
/**
 * PES PRO Nulled BY MTimer
 * Version: 2.0
 *
 * Author: MTimer
 * Website: http://www.mtimer.net
 **/

error_reporting(0);
session_start();
include ("class.php");
$step = $sree->get("step");
$step = $step == "" ? 0 : $step;
$isPost = $sree->post();
$error = array();
$errorTxt = "";
$vi = $sree->session("validInstall");
$license = $sree->session("validLicense");
if ($step == 0) 
{
	session_destroy();
	session_start();
	$reqUri = $sree->server("SCRIPT_NAME");
	if (phpversion() < "5.2") 
	{
		$error[] = "PHP 5.2 or higher must be installed on your server. It seems you have installed PHP " . phpversion();
	}
	if (!function_exists("curl_init")) 
	{
		$error[] = "cURL must be installed on your server.";
	}
	if (!extension_loaded("gd") || !function_exists("gd_info")) 
	{
		$error[] = "PHP GD library must be installed on your web server.";
	}
/*	if ($reqUri != "/install/index.php") 
	{
		$error[] = "It seems you are Installing the script on a Directory. This script needs to be Installed on a <b>Base Domain</b> or <b>Sub Domain</b> to work properly.";
	}*/
	if (count($error) == 0) 
	{
		$sree->setSession("validInstall", 1);
		$sree->locate("?step=1");
	}
} else if ($vi == 1) 
{
	if ($step != 1 && $license != 1) 
	{
		$sree->locate("?step=1");
	}
	if ($isPost) 
	{
		if ($step == 1) 
		{
			$mn_key = preg_replace("/\\s+/", "", $sree->post("mn_key"));
			$mn_oid = preg_replace("/[^0-9]/", "", $sree->post("mn_order"));
			$http_host = str_replace("www.", "", $sree->server("HTTP_HOST"));
			$license_check = $sree->checkLicense(MD5($mn_key), $mn_oid, $http_host);
			if ($license_check == "NO_CURL") 
			{
				$error[] = "cURL function must be Enabled on your server!";
			} elseif ($license_check == "VALID") 
			{
				$licenseFile = $sree->generateLicense(MD5($mn_key), $mn_oid, $http_host);
				echo $licenseFile;
				$successTxt = "<p class=\"errsuccess\">Licence successfully saved!</p>";
				$sree->setSession("successTxt", $successTxt);
				$sree->setSession("licenseFile", $licenseFile);
				$sree->setSession("validLicense", 1);
				$sree->locate("?step=2");
			} else
			{
				$error[] = "<b>Invalid license!</b> Please try again or contact our support if the error prevails.";
			}
		} elseif ($step == 2) 
		{
			$installHost = $sree->post("dbhost");
			$installName = $sree->post("dbname");
			$installUser = $sree->post("dbuser");
			$installPass = $sree->post("dbpass");
			$preDbHost = $installHost;
			$preDbName = $installName;
			$preDbUser = $installUser;
			$preDbPass = $installPass;
			$connectInstall = @mysql_connect($installHost, $installUser, $installPass);
			if (mysql_select_db($installName, $connectInstall)) 
			{
				if ($sree->write($installHost, $installName, $installUser, $installPass)) 
				{
					$filename = "i/dump.sql";
					$templine = "";
					$lines = file($filename);
					$impError = 0;
					foreach ($lines as $line) 
					{
						if (substr($line, 0, 2) == "--" || $line == "") continue;
						$templine.= $line;
						if (substr(trim($line), -1, 1) == ";") 
						{
							if (mysql_query($templine)) 
							{
							} else
							{
								$impError = 1;
							}
							$templine = "";
						}
					}
					if ($impError == 0) 
					{
						$successTxt = "<p class=\"errsuccess\">Successfully saved Database Settings</p>";
						$sree->setSession("successTxt", $successTxt);
						$sree->locate("?step=3");
					} else
					{
						$error[] = "An error occured while installing the database. Please try again or contact our support if the error prevails.";
					}
				} else
				{
					$error[] = "An error occured while saving the settings. Please make sure, you have set appropriate permissions for <b>system/database.php</b> file. If not, please change permissions to <b>777</b>.";
				}
			} else
			{
				$error[] = "Failed to Connect with your Database. Please make sure you have lodged Correct Database Information.";
			}
		} else if ($step == 3) 
		{
			$site_name = $sree->post("site_name");
			$site_description = $sree->post("site_description");
			$site_location = $sree->post("site_location");
			$site_email = $sree->post("site_email");
			$site_paypal = $sree->post("site_paypal");
			$installHost = "http://" . $sree->server("HTTP_HOST") . "/";
			if ($site_name != "" && $site_description != "" && $site_location != "" && $site_email != "" && $site_paypal != "") 
			{
				$licenseFile = $sree->session("licenseFile");
				$ua = "INSERT IGNORE INTO `site_config` (config_name, config_value) VALUES ('site_name', '" . $site_name . "'),('site_description', '" . $site_description . "'),('site_url', '" . $site_location . "'),('site_email', '" . $site_email . "'),('paypal', '" . $site_paypal . "'),('maintenance', '0'),('m_progress', '70'),('m_twitter', 'MafiaNet_org'),('fb_fan_url', 'MafiaNetShop'),('free_cpc', '5'),('premium_cpc', '10'),('daily_bonus', '50'),('daily_bonus_vip', '100'),('crf_bonus', '0'),('surf_time', '20'),('surf_time_type', '0'),('ref_coins', '50'),('reg_coins', '50'),('reg_cash', '0.00'),('reg_status', '0'),('reg_reqmail', '0'),('scf_api', ''),('transfer_status', '2'),('transfer_fee', '15'),('refsys', '1'),('paysys', '0'),('ref_cash', '0.10'),('ref_sale', '20'),('pay_min', '1.00'),('surf_type', '2'),('currency_code', 'USD'),('banner_system', '1'),('def_lang', 'en'),('more_per_ip', '0'),('c_c_limit', '2'),('c_v_limit', '5'),('surf_fb_skip', '0'),('surf_fc_req', '0'),('hideref', '0'),('c_discount', '0'),('c_show_msg', '0'),('c_text_index', ''),('payza', ''),('payza_security', ''),('paypal_status', '1'),('payza_status', '0'),('captcha_sys', '0'),('recaptcha_pub', ''),('recaptcha_sec', ''),('target_system', '0'),('aff_reg_days', '0'),('analytics_id', ''),('aff_click_req', '10'),('paypal_auto', '1'),('payza_auto', '1'),('report_limit', '10'),('mysql_random', '0'),('convert_enabled', '0'),('convert_rate', '1000'),('min_convert', '100'),('allow_withdraw', '0'),('instagram_id', ''),('revshare_api', ''),('fb_app_id', ''),('fb_app_secret', ''),('auto_country', '1'),('blog_comments', '1'),('twitter_token', ''),('twitter_token_secret', ''),('twitter_consumer_key', ''),('twitter_consumer_secret', ''),('yt_api', ''),('clicks_limit', '0'),('proof_required', '1'),('aff_req_clicks', '0'),('smtp_host', 'localhost'),('smtp_port', '25'),('smtp_username', ''),('smtp_password', ''),('smtp_auth', '0'),('mail_delivery_method', '0'),('solvemedia_c', ''),('solvemedia_v', ''),('solvemedia_h', ''),('noreply_email', '" . $site_email . "'),('license_file', '" . $licenseFile . "');";
				if ($db->query($ua)) 
				{
					$db->query("INSERT IGNORE INTO `p_pack` (`id`, `name`, `days`, `price`) VALUES (1, '14 Days', 14, '2.00'), (2, '30 Days', 30, '3.00'), (3, '60 Days', 60, '5.00'), (4, '120 Days', 120, '9.00'), (5, '180 Days', 180, '12.50'), (6, '365 Days', 365, '25.00')");
//					@mail("admin@mtimer.cn", "[PES Pro] Installation", "Admin Email: " . $site_email . "" . "\n---\n" . "\nSite Location: " . $site_location . "\nPaypal Email: " . $site_paypal . "\n\n---");
					$successTxt = "<p class=\"errsuccess\">Settings added Successfully!</p>";
					$sree->setSession("successTxt", $successTxt);
					$sree->locate("?step=4");
				} else
				{
					$error[] = "Update Failed! An error occurred while trying to update database.";
				}
			} else
			{
				$error[] = "Please complete all fields to continue.";
			}
		} else if ($step == 4) 
		{
			$installAdmName = $sree->post("admname");
			$installAdmMail = $sree->post("admmail");
			$installAdmPass1 = $sree->post("admpass1");
			$installAdmPass2 = $sree->post("admpass2");
			if ($installAdmPass1 != $installAdmPass1) 
			{
				$error[] = "Passwords does not match!";
			} elseif ($installAdmName != "" && $installAdmMail != "" && $installAdmPass1 != "") 
			{
				$encPassword = md5($installAdmPass1);
				$ua = "INSERT INTO `users` (`email`,`login`,`admin`,`IP`,`pass`,`signup`)VALUES('" . $installAdmMail . "', '" . $installAdmName . "', '1', '" . $_SERVER["REMOTE_ADDR"] . "', '" . $encPassword . "', NOW())";
				if ($db->query($ua)) 
				{
					$successTxt = "<p class=\"errsuccess\">Successfully created an Administrator!</p>";
					$sree->setSession("successTxt", $successTxt);
					$sree->locate("?step=5");
				} else
				{
					$error[] = "An Unknown error occured while adding the administrator.<br />Please try again or contact our support if the error prevails.";
				}
			} else
			{
				$error[] = "Please complete all fields!";
			}
		}
	}
} else
{
	$sree->locate("?step=0");
}
if ($step == 2) 
{
	$preDbHost = isset($preDbHost) ? $preDbHost : "localhost";
	$preDbName = isset($preDbName) ? $preDbName : "";
	$preDbUser = isset($preDbUser) ? $preDbUser : "";
	$preDbPass = isset($preDbPass) ? $preDbPass : "";
} else if ($step == 4) 
{
	$preAdmName = isset($preAdmName) ? $preAdmName : "admin";
}
if (count($error) > 0) 
{
	$errorCls = $step == 0 ? "errfailed" : "errfail";
	$errorTxt = "";
	foreach ($error as $err) 
	{
		$errorTxt.= "<p class=\"" . $errorCls . "\" style=\"margin-bottom:5px\">" . $err . "</p>";
	}
}
$sessEror = $sree->session("successTxt");
if ($sessEror != "")
{
	$successTxt = $sessEror != "" ? $sessEror : "";
	$sree->unsetSession("successTxt");
} else $successTxt = "";
echo "<!DOCTYPE html>\n<html>\n<head><title>Install ";
echo $appName;
echo "</title>\n\t<link rel=\"stylesheet\" href=\"style.css\" />\n\t<script type=\"text/javascript\" src=\"install.js\"></script>\n</head><body>";
if ($step == 0) 
{
	echo "<div id=\"box\" class=\"btl bbr\"><h1 class=\"btl\"><img src=\"i/b.gif\" class=\"logo mr10\" /><span style=\"float:right;margin-top:4px\">";
	echo $appName;
	echo " Installer</span></h1>";
	if ($step != 0) 
	{
		if ($errorTxt == "") 
		{
			echo "<p class=\"desc\">Please clear the following errors to start Installing...</p>" . $successTxt;
		} else
		{
			echo $errorTxt;
		}
	} else
	{
		echo "<p class=\"desc\">Please clear the following errors to start Installing...</p>";
	}
	echo "<form method=\"POST\" action=\"\">\n\t<div class=\"form\">";
	echo $errorTxt;
	echo "</div>\n\t<div class=\"stepper bbr\">\n\t\t<div class=\"fll\">Pre-Install Checking...</div>\n\t\t<div class=\"flr\"><input type=\"submit\" value=\"Try again\" class=\"btl bbr\"/></div>\n\t\t<div class=\"cls\"></div>\n\t</div>\n</form></div>\n";
} elseif ($step == 1) 
{
	echo "<div id=\"box\" class=\"btl bbr\">\n\t<h1 class=\"btl\"><img src=\"i/b.gif\" class=\"logo mr10\" /><span style=\"float:right;margin-top:4px\">";
	echo $appName;
	echo " Installer</span></h1>\n";
	if ($errorTxt == "") 
	{
		echo "<p class=\"desc\">PES Pro Full Decoded Nulled BY MTimer</p>" . $successTxt;
	} else
	{
		echo $errorTxt;
	}
	echo "<form method=\"POST\" action=\"\" id=\"installform\">\n\t<div class=\"form\">\n\t\t<div class=\"initem\">\n\t\t\t<div class=\"dib name\">Order ID <img src=\"i/i.png\" title=\"Order ID generated when you bought ";
	echo $appName;
	echo "\" /></div>\n\t\t\t<div class=\"dib value\"><input type=\"text\" name=\"mn_order\" value=\"PES Pro Nulled BY MTimer\" /></div>\n\t\t</div>\n\t\t<div class=\"initem\">\n\t\t\t<div class=\"dib name\">Serial Key <img src=\"i/i.png\" title=\"Serial Key generated when you bought ";
	echo $appName;
	echo "\" /></div>\n\t\t\t<div class=\"dib value\"><input type=\"text\" name=\"mn_key\" value=\"PES Pro Nulled BY MTimer\" /></div>\n\t\t</div>\n\t</div>\n\t<div class=\"stepper bbr\">\n\t\t<div class=\"fll\">Step 1/5 : Licence check</div>\n\t\t<div class=\"flr\"><input type=\"submit\" value=\"Validate & Continue\" class=\"btl bbr\"/></div>\n\t\t<div class=\"cls\"></div>\n\t</div>\n</form></div>\n";
} elseif ($step == 2) 
{
	echo "<div id=\"box\" class=\"btl bbr\">\n\t<h1 class=\"btl\"><img src=\"i/b.gif\" class=\"logo mr10\" /><span style=\"float:right;margin-top:4px\">";
	echo $appName;
	echo " Installer</span></h1>\n";
	if ($errorTxt == "") 
	{
		echo "<p class=\"desc\">Please fill your database details to start Installing.</p>" . $successTxt;
	} else
	{
		echo $errorTxt;
	}
	echo "<form method=\"POST\" action=\"\" onSubmit=\"return validatedb();\" id=\"installform\">\n\t<div class=\"form\">\n\t\t<div class=\"initem\">\n\t\t\t<div class=\"dib name\">Database Host</div>\n\t\t\t<div class=\"dib value\"><input type=\"text\" name=\"dbhost\" value=\"";
	echo $preDbHost;
	echo "\" /></div>\n\t\t</div>\n\t\t<div class=\"initem\">\n\t\t\t<div class=\"dib name\">Database Name</div>\n\t\t\t<div class=\"dib value\"><input type=\"text\" name=\"dbname\" value=\"";
	echo $preDbName;
	echo "\" /></div>\n\t\t</div>\n\t\t<div class=\"initem\">\n\t\t\t<div class=\"dib name\">Database User</div>\n\t\t\t<div class=\"dib value\"><input type=\"text\" name=\"dbuser\" value=\"";
	echo $preDbUser;
	echo "\" /></div>\n\t\t</div>\n\t\t<div class=\"initem\">\n\t\t\t<div class=\"dib name\">Database Pass</div>\n\t\t\t<div class=\"dib value\"><input type=\"text\" name=\"dbpass\" value=\"";
	echo $preDbPass;
	echo "\" /></div>\n\t\t</div>\n\t</div>\n\t<div class=\"stepper bbr\">\n\t\t<div class=\"fll\">Step 2/5 : Database Settings</div>\n\t\t<div class=\"flr\"><input type=\"submit\" value=\"Validate & Continue\" class=\"btl bbr\"/></div>\n\t\t<div class=\"cls\"></div>\n\t</div>\n</form></div>\n";
} elseif ($step == 3) 
{
	echo "<div id=\"box\" class=\"btl bbr\">\n\t<h1 class=\"btl\"><img src=\"i/b.gif\" class=\"spr logo mr10\" /><span style=\"float:right;margin-top:4px\">";
	echo $appName;
	echo " Installer</span></h1>\n";
	if ($errorTxt == "") 
	{
		echo "<p class=\"desc\">Enter your site informations in this Step.</p>" . $successTxt;
	} else
	{
		echo $errorTxt;
	}
    if ($_SERVER["SERVER_NAME"] == "localhost") {
      $sysurl = "http://" . $_SERVER["SERVER_ADDR"] . ($_SERVER["SERVER_PORT"] == 80 ? '' : ':' . $_SERVER["SERVER_PORT"]) . $_SERVER["REQUEST_URI"];
    } else $sysurl = "http://" . $_SERVER["SERVER_NAME"] . ($_SERVER["SERVER_PORT"] == 80 ? '' : ':' . $_SERVER["SERVER_PORT"]) . $_SERVER["REQUEST_URI"];
    $sysurl = str_replace("/install/?step=3", "", $sysurl);
	echo "<form method=\"POST\" action=\"\" id=\"installform\">\n\t<div class=\"form\">\n\t\t<div class=\"initem\">\n\t\t\t<div class=\"dib name\">Site Name</div>\n\t\t\t<div class=\"dib value\"><input type=\"text\" name=\"site_name\" value=\"";
	echo $appName;
	echo "\" /></div>\n\t\t</div>\n\t\t<div class=\"initem\">\n\t\t\t<div class=\"dib name\">Site Description</div>\n\t\t\t<div class=\"dib value\"><input type=\"text\" name=\"site_description\" value=\"Powerful Exchange System PRO\" /></div>\n\t\t</div>\n\t\t<div class=\"initem\">\n\t\t\t<div class=\"dib name\">Site URL</div>\n\t\t\t<div class=\"dib value\"><input type=\"text\" name=\"site_location\" value=\"";
	echo $sysurl;
	echo "\" /></div>\n\t\t</div>\n\t\t<div class=\"initem\">\n\t\t\t<div class=\"dib name\">Contact Email</div>\n\t\t\t<div class=\"dib value\"><input type=\"text\" name=\"site_email\" value=\"\" /></div>\n\t\t</div>\n\t\t<div class=\"initem\">\n\t\t\t<div class=\"dib name\">PayPal Email</div>\n\t\t\t<div class=\"dib value\"><input type=\"text\" name=\"site_paypal\" value=\"\" /></div>\n\t\t</div>\n\t</div>\n\t<div class=\"stepper bbr\">\n\t\t<div class=\"fll\">Step 3/5 : Website Settings</div>\n\t\t<div class=\"flr\"><input type=\"submit\" value=\"Validate & Continue\" class=\"btl bbr\"/></div>\n\t\t<div class=\"cls\"></div>\n\t</div>\n</form></div>\n";
} elseif ($step == 4) 
{
	echo "<div id=\"box\" class=\"btl bbr\">\n\t<h1 class=\"btl\"><img src=\"i/b.gif\" class=\"spr logo mr10\" /><span style=\"float:right;margin-top:4px\">";
	echo $appName;
	echo " Installer</span></h1>\n";
	if ($errorTxt == "") 
	{
		echo "<p class=\"desc\">Create an Administrator account to login on the website and Admin Panel.</p>" . $successTxt;
	} else
	{
		echo $errorTxt;
	}
	echo "<form method=\"POST\" action=\"\" onSubmit=\"return validateuser();\" id=\"installform\">\n\t<div class=\"form\">\n\t\t<div class=\"initem\">\n\t\t\t<div class=\"dib name\">Admin Name</div>\n\t\t\t<div class=\"dib value\"><input type=\"text\" name=\"admname\" value=\"";
	echo $preAdmName;
	echo "\" /></div>\n\t\t</div>\n\t\t<div class=\"initem\">\n\t\t\t<div class=\"dib name\">Admin Email</div>\n\t\t\t<div class=\"dib value\"><input type=\"text\" name=\"admmail\" value=\"admin@";
	echo $_SERVER["HTTP_HOST"];
	echo "\" /></div>\n\t\t</div>\n\t\t<div class=\"initem\">\n\t\t\t<div class=\"dib name\">Admin Password</div>\n\t\t\t<div class=\"dib value\"><input type=\"password\" name=\"admpass1\" value=\"\" /></div>\n\t\t</div>\n\t\t<div class=\"initem\">\n\t\t\t<div class=\"dib name\">Confirm Password</div>\n\t\t\t<div class=\"dib value\"><input type=\"password\" name=\"admpass2\" value=\"\" /></div>\n\t\t</div>\n\t</div>\n\t<div class=\"stepper bbr\">\n\t\t<div class=\"fll\">Step 4/5 : Administrator Account</div>\n\t\t<div class=\"flr\"><input type=\"submit\" value=\"Validate & Continue\" class=\"btl bbr\"/></div>\n\t\t<div class=\"cls\"></div>\n\t</div>\n</form></div>\n";
} elseif ($step == 5) 
{
	echo "<div id=\"box\" class=\"btl bbr\">\n\t<h1 class=\"btl\"><img src=\"i/b.gif\" class=\"spr logo mr10\" /><span style=\"float:right;margin-top:4px\">";
	echo $appName;
	echo " Installer</span></h1>\n";
	if ($errorTxt == "") 
	{
		echo $successTxt;
	} else
	{
		echo $errorTxt;
	}
	echo "<div class=\"formm infosuccess\">Congratulations! You have successfully Installed the ";
	echo $appName;
	echo " and the Site is ready to get Online.<br /><br />Make sure you delete the directory <b>/install</b> to avoid further re-installs or unauthorized access to the service.</div>\n<div class=\"stepper bbr\">\n\t<div class=\"fll\">Step 5/5 : Install Complete</div>\n\t<div class=\"flr\"><input type=\"button\" onClick=\"gotoAdmin()\" value=\"Visit Website\" class=\"btl bbr\"/></div>\n\t<div class=\"cls\"></div>\n</div></div>\n";
}
echo "</body></html>\n";
?>