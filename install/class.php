<?php
/**
 * PES PRO Nulled BY MTimer
 * Version: 2.0
 *
 * Author: MTimer
 * Website: http://www.mtimer.net
 **/

define("INSTALL", true);
require ("../system/database.php");
if($config['sql_host'] && $config['sql_username'] && $config['sql_password'] && $config['sql_database'] && $config['sql_extenstion'])
{
	if (preg_match("/index.php/", $_SERVER['SCRIPT_NAME'])) 
	{
		$connect = @mysql_connect($config["sql_host"], $config["sql_username"], $config["sql_password"]);
		mysql_select_db($config["sql_database"], $connect) or die("Please install the script. <a href=\"/install\">Click here to Install</a>.");
	} else
	{
		$connect = @mysql_connect($config["sql_host"], $config["sql_username"], $config["sql_password"]);
		mysql_select_db($config["sql_database"], $connect);
	}
}
class sree
{
	public function get($credential = '') 
	{
		$op = $credential == "" ? $_GET : $_GET[$credential];
		return $op;
	}
	public function post($credential = '') 
	{
		$op = $credential == "" ? $_POST : $_POST[$credential];
		return $op;
	}
	public function server($credential = '') 
	{
		$op = $credential == "" ? $_SERVER : $_SERVER[$credential];
		return $op;
	}
	public function session($credential) 
	{
		$op = isset($_SESSION[$credential]) ? $_SESSION[$credential] : "";
		return $op;
	}
	public function timer() 
	{
		$op = mktime();
		return $op;
	}
	public function setSession($name, $val) 
	{
		$_SESSION[$name] = $val;
	}
	public function unsetSession($name) 
	{
		unset($_SESSION[$name]);
	}
	public function locate($url) 
	{
		$hs = headers_sent();
		if ($hs === false) 
		{
			header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
			header("Location: " . $url);
		} elseif ($hs == true) 
		{
			echo "<script>document.location.href='" . htmlspecialchars($url) . "'</script>";
		}
		exit(0);
	}
	public function checkLicense($hash_key, $order_id, $domain) 
	{
/*		if (!function_exists("curl_init")) 
		{
			return "NO_CURL";
		}
		$qry_str = "hash=" . $hash_key . "&oid=" . $order_id . "&host=" . $domain;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://mn-shop.net/license/pes_v2.php");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $qry_str);
		$content = trim(curl_exec($ch));
		curl_close($ch);
		if ($content == "true" || $content == "out_of_stock") 
		{
*/			return "VALID";
/*		} elseif ($content != "true" && $content != "false" && $content != "out_of_stock") 
		{
			return "VALID";
		} else
		{
			return "NO_LICENSE";
		}
*/	}
	public function generateLicense($license, $order_id, $domain) 
	{
		$content = base64_encode(base64_encode(time()) . "(||)" . base64_encode($license) . "(||)" . base64_encode($order_id) . "(||)" . base64_encode($domain));
		return $content;
	}
	public static function write($cnf_host, $cnf_name, $cnf_user, $cnf_pass) 
	{
		$filename = "../system/database.php";
		if (file_put_contents($filename, "<?php\n\$config['sql_host']       = '" . $cnf_host . "';\n\$config['sql_username']   = '" . $cnf_user . "';\t\t// Database Username\n\$config['sql_password']   = '" . $cnf_pass . "';\t\t// Database Password\n\$config['sql_database']  \t= '" . $cnf_name . "';\t\t// The database\n\$config['sql_extenstion'] = 'MySQL';\t\t// MySQL or MySQLi\n?>")) 
		{
			return true;
		} else
		{
			return false;
		}
	}
	function check($menu, $page) 
	{
		if ($menu == $page) 
		{
			$op = " class=\"selected\"";
		} else
		{
			$op = "";
		}
		return $op;
	}
}
class db
{
	function query($query) 
	{
		$qry = mysql_query($query);
		return $qry;
	}
	function fetch($query) 
	{
		$fetch = mysql_fetch_array($query);
		return $fetch;
	}
	function assoc($query) 
	{
		$fetch = mysql_fetch_assoc($query);
		return $fetch;
	}
	function count($query) 
	{
		$fetch = mysql_num_rows($query);
		return $fetch;
	}
}
$sree = new sree;
$db = new db;
$appName = "PES Pro";
$copyright = "&copy; <a href=\"http://mn-shop.net/\" target=\"_blank\">MafiaNet</a>";
?>