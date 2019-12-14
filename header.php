<?
$starttime = microtime(true);
define('BASEPATH', true);
include('system/config.php');

if($site['maintenance'] > 0){$site['site_name'] .= ' - '.$lang['b_01']; if($data['admin'] < 1){redirect('maintenance');}}
if(!$is_online && isset($_SERVER['HTTP_REFERER']) && !isset($_COOKIE['PESRefSource'])){
	setcookie("PESRefSource", $db->EscapeString($_SERVER['HTTP_REFERER']), time()+1800);
}

if(isset($_GET['unsubscribe']) && isset($_GET['um'])){
	$uid = $db->EscapeString($_GET['unsubscribe']);
	$um = $db->EscapeString($_GET['um']);
	if($db->QueryGetNumRows("SELECT id FROM `users` WHERE `id`='".$uid."' AND MD5(`email`)='".$um."'") > 0){
		$db->Query("UPDATE `users` SET `newsletter`='0' WHERE `id`='".$uid."' AND MD5(`email`)='".$um."'");
		echo '<center><b style="color:green">You was successfully unsubscribed from newsletters!</b></center>';
		redirect('index.php');
		exit;
	}
}

$errMsg = '';
if(isset($_POST['connect'])) {
	if(blacklist_check(VisitorIP(), 3)){
		$errMsg = '<div class="msg"><div class="error">'.lang_rep($lang['b_295'], array('-IP-' => VisitorIP())).'</div></div>';
	}else{
		$login = $db->EscapeString($_POST['login']);
		$pass = MD5($_POST['pass']);
		$data = $db->QueryFetchArray("SELECT id,login,banned,activate FROM `users` WHERE (`login`='".$login."' OR `email`='".$login."') AND `pass`='".$pass."'");

		if($data['banned'] > 0){
			$errMsg = '<div class="msg"><div class="error">'.$lang['b_02'].'</div></div>';
			$sql = $db->Query("SELECT id,reason FROM `ban_reasons` WHERE `user`='".$data['id']."'");
			if($db->GetNumRows($sql) > 0){
				$ban = $db->FetchArray($sql);
				if(!empty($ban['reason'])){
					$_SESSION['PES_Banned'] = $data['id'];
					redirect('banned.php?id='.$data['id']);
				}
			}
		}elseif($data['activate'] > 0){
			$errMsg = '<a href="register.php?resend" title="Click here" style="text-decoration:none;color:#a32326"><div class="msg"><div class="error">'.$lang['b_03'].'</div></div></a>';
		}elseif($data['id'] != '') {
			if(isset($_POST['remember'])){
				setcookie('PESAutoLogin', 'ses_user='.$data['login'].'&ses_hash='.$pass, time()+604800, '/');
			}
			$db->Query("UPDATE `users` SET `log_ip`='".VisitorIP()."', `online`=NOW() WHERE `id`='".$data['id']."'");
			$_SESSION['EX_login'] = $data['id'];
			redirect('index.php');
		}else{
			$errMsg = '<div class="msg"><div class="error">'.$lang['b_04'].'</div></div>';
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title><?=$site['site_name']?></title>
<meta http-equiv="Content-type" content="text/html; charset=<?=$conf['lang_charset']?>" />
<meta name="description" content="<?=$site['site_description']?>" />
<meta name="keywords" content="free twitter followers, free facebook likes, twitter followers, facebook likes, get free followers, follower exchange, social media exchange, stumbleupon followers, social exchange system, digg exchange, free youtube views, youtube views" />
<meta name="author" content="MafiaNet (c) MN-Shop.net" />
<meta name="version" content="<?=$config['version']?>" />
<link rel="stylesheet" href="theme/<?=$site['theme']?>/style.css?v=<?=$config['version']?>" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<!--[if lte IE 9]><script src="js/jquery.placeholder.min.js"></script><![endif]-->
<?if($is_online){?><script type="text/javascript"> $(document).ready(function() { $.ajaxSetup({ cache: false }); setInterval( function() { $('#c_coins').load('system/ajax.php?a=getCoins'); }, 10000); }); </script><?}?>
</head>
<body>
<div class="header">
	<div class="header-content">
		<a class="logo" href="<?=$site['site_url']?>"><img src="theme/<?=$site['theme']?>/images/logo.png" alt="PES Pro" border="0" /></a>
        <ul id="navigation">
		<?if(!$is_online){?>
			<?if($site['reg_status'] == 0){?><li><a href="register.php"><?=$lang['b_05']?></a></li><?}?>
            <li><a href="faq.php"><?=$lang['b_06']?></a></li>
		<?}else{?>
			<li><a href="buy.php"><?=$lang['b_07']?></a></li>
			<li><a href="vip.php"><?=$lang['b_08']?></a></li>
			<?if($site['daily_bonus'] > 0){?><li><a href="rewards.php"><?=$lang['b_326']?></a></li><?}?>
			<li><a href="coupons.php"><?=$lang['b_10']?></a></li>
			<?if($site['transfer_status'] != 1){?><li><a href="transfer.php"><?=$lang['b_11']?></a></li><?}?>
			<?if($site['refsys'] == 1){?><li><a href="refer.php"><?=$lang['b_12']?></a></li><?}?>
		<?}?>
			<li><a href="blog.php"><?=$lang['b_287']?></a></li>
        </ul>
	</div>
</div>
<div class="container">
	<div class="main">
	<div class="sidebar">
	<?if(!$is_online){?>
		<div class="signin">
            <h2 class="title"><?=$lang['b_13']?></h2>
            <form method="post" action="">
				<input class="login login_user" name="login" type="text" placeholder="<?=$lang['b_14']?>" />
				<input class="login login_password" name="pass" type="password" placeholder="<?=$lang['b_15']?>" />	 						
				<input type="checkbox" name="remember" /> <span style="color:#fff;"><?=$lang['b_229']?></span>
				<?=$errMsg?>
				<div class="buttons">
					<input class="gbut" name="connect" value="<?=$lang['b_13']?>" type="submit" /><br /><br />
					<span style="float:right;display:inline"><a href="recover.php" style="font-size:10px"><?=$lang['b_16']?></a></span>
				</div>						  				  
			</form>
        </div>
		<div style="clear:both"></div>
		<div class="sideblock">
			<p class="user_count"><? $sql = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `users`"); echo number_format($sql['total']); ?></p> <?=$lang['b_230']?>
		</div>
		<? 
			$sql = $db->Query("SELECT a.uid, SUM(a.today_clicks) AS clicks, b.login FROM user_clicks a LEFT JOIN users b ON b.id = a.uid WHERE b.login != '' GROUP BY a.uid ORDER BY clicks DESC LIMIT 3");
			if($db->GetNumRows($sql) >= 3){
		?>
		<div class="home_top">
			<table class="table">
				<thead>
					<tr><td colspan="2"><?=$lang['b_239']?></td></tr>
				</thead>
				<tbody>
					<?
						$j = 0;
						foreach($db->FetchArrayAll($sql) as $top){
							$j++;
							echo '<tr><td><center><img src="img/place/place_'.$j.'.png" width="15" height="15" alt="'.$j.'" border="0" /></center></td><td>'.$top['login'].'</td></tr>';
						}
					?>
				</tbody>
			</table>
		</div>
		<?}?>
	<?}else{?>
	<div class="sideblock_ucp">
		<h2><?=lang_rep($lang['b_17'], array('-NUM-' => '&nbsp;<p class="coincount" id="c_coins">'.number_format($data['coins']).'</p>&nbsp;'))?></h2>
	</div>
	<div class="sideblock_ucp" style="margin-top:-3px">
		<h2><?=lang_rep($lang['b_261'], array('-NUM-' => '&nbsp;<a href="bank.php" title="'.$lang['b_256'].'" style="text-decoration:none"><p class="accBalance">'.$data['account_balance'].' '.get_currency_symbol($site['currency_code']).'</p></a>&nbsp;'))?></h2>
	</div>
	<div class="ucp_menu"> 
		<div class="ucp_inner">
			<h2><?=$lang['b_18']?></h2>
			<div class="ucp_link"><a href="addurl.php"> <?=$lang['b_19']?></a></div>
			<div class="ucp_link"><a href="mysites.php"> <?=$lang['b_20']?></a></div>
			<?if($site['banner_system'] != 0){?><div class="ucp_link"><a href="banners.php"> <?=$lang['b_189']?></a></div><?}?>
			<div class="ucp_link"><a href="bank.php" style="color:gold"> <?=$lang['b_256']?></a></div>
		</div>
	</div>
	<div class="ucp_menu"> 
		<div class="ucp_inner">
			<h2><?=$lang['b_22']?></h2>
			<?=hook_filter('top_menu_earn',"")?>
		</div>
	</div>
<?}?></div>