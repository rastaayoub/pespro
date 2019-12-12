<?php
$starttime = microtime(true);
define('BASEPATH', true);
include('system/config.php');

if($site['maintenance'] > 0){$site['site_name'] .= ' - '.$lang['b_01']; if($data['admin'] < 1){redirect('maintenance');}}

if(!$is_online && isset($_SERVER['HTTP_REFERER']) && !isset($_COOKIE['PESRefSource'])){
	$main_domain = parse_url($site['site_url']);
	$http_referer = parse_url($_SERVER['HTTP_REFERER']);
	if($http_referer['host'] != $main_domain['host']){
		setcookie('PESRefSource', $db->EscapeString($_SERVER['HTTP_REFERER']), time()+1800);
	}
}

if(!$is_online && isset($_GET['ref']) && is_numeric($_GET['ref']) && $site['splash_page'] == 1){
	if(file_exists('theme/'.$site['theme'].'/splash.php')){
		include('theme/'.$site['theme'].'/splash.php');
		exit;
	}
}

if(isset($_GET['unsubscribe']) && isset($_GET['um'])){
	$uid = $db->EscapeString($_GET['unsubscribe']);
	$um = $db->EscapeString($_GET['um']);
	if($db->QueryGetNumRows("SELECT id FROM `users` WHERE `id`='".$uid."' AND MD5(`email`)='".$um."'") > 0){
		$db->Query("UPDATE `users` SET `newsletter`='0' WHERE `id`='".$uid."' AND MD5(`email`)='".$um."'");
		echo '<center><b style="color:green">You was successfully unsubscribed from newsletters!</b></center>';
		redirect('index.php');
	}
}

$errMsg = '';
if(isset($_POST['connect'])) {
	$ip_address = ip2long(VisitorIP());
	$attempts = $db->QueryFetchArray("SELECT count,time FROM `wrong_logins` WHERE `ip_address`='".$ip_address."'");

	if($attempts['count'] >= $site['login_attempts'] && $attempts['time'] > (time() - (60*$site['login_wait_time']))){
		$errMsg = '<div class="msg"><div class="error">'.lang_rep($lang['b_364'], array('-MIN-' => $site['login_wait_time'])).'</div></div>';
	}elseif(blacklist_check(VisitorIP(), 3)){
		$errMsg = '<div class="msg"><div class="error">'.lang_rep($lang['b_295'], array('-IP-' => VisitorIP())).'</div></div>';
	}else{
		$login = $db->EscapeString($_POST['login']);
		$pass = MD5($_POST['pass']);
		$data = $db->QueryFetchArray("SELECT id,login,banned,activate FROM `users` WHERE (`login`='".$login."' OR `email`='".$login."') AND `pass`='".$pass."'");

		if($data['banned'] > 0){
			$errMsg = '<div class="msg"><div class="error">'.$lang['b_02'].'</div></div>';
			$ban = $db->QueryFetchArray("SELECT reason FROM `ban_reasons` WHERE `user`='".$data['id']."' LIMIT 1");
			if(!empty($ban['reason'])){
				$_SESSION['PES_Banned'] = $data['id'];
				redirect('banned.php?id='.$data['id']);
			}
		}elseif($data['activate'] > 0){
			$errMsg = '<a href="register.php?resend" title="Click here" style="text-decoration:none;color:#a32326"><div class="msg"><div class="error">'.$lang['b_03'].'</div></div></a>';
		}elseif(!empty($data['id'])) {
			$db->Query("UPDATE `users` SET `log_ip`='".VisitorIP()."', `online`=NOW() WHERE `id`='".$data['id']."'");
			$db->Query("DELETE FROM `wrong_logins` WHERE `ip_address`='".$ip_address."'");
			
			// Auto-login user
			if(isset($_POST['remember'])){
				setcookie('PESAutoLogin', 'ses_user='.$data['login'].'&ses_hash='.$pass, time()+604800, '/');
			}
			
			// Set Session
			$_SESSION['EX_login'] = $data['id'];
			
			// Multi-account prevent
			setcookie('PESAccExist', $data['login'], time()+604800, '/');
			
			// Reload page
			redirect('index.php');
		}else{
			$db->Query("INSERT INTO `wrong_logins` (`ip_address`,`count`,`time`) VALUES ('".$ip_address."','1','".time()."') ON DUPLICATE KEY UPDATE `count`=`count`+'1', `time`='".time()."'");

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
<meta name="keywords" content="<?=$site['site_keywords']?>" />
<meta name="author" content="MafiaNet (c) MN-Shop.com" />
<meta name="version" content="<?=$config['version']?>" />
<link rel="stylesheet" href="theme/<?=$site['theme']?>/style.css?v=<?=$config['version']?>" type="text/css" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<script type="text/javascript" src="js/jquery.js"></script>
<!--[if lte IE 9]><script src="js/jquery.placeholder.min.js"></script><![endif]-->
<?if($is_online){?><script type="text/javascript"> $(document).ready(function() { $.ajaxSetup({ cache: false }); setInterval( function() { $('#c_coins').load('system/ajax.php?a=getCoins'); }, 5000); }); function openMenu(id) {var menuID = $('#'+id); if(menuID.is(':visible')) { menuID.slideUp('normal'); } else { menuID.slideDown('normal'); }} </script><?}else{?><script type="text/javascript"> $(document).ready(function() { $.ajaxSetup({ cache: false }); setInterval( function() { $.getJSON('system/ajax.php?a=getSideStats', function (c) { $('.user_count').html(c['members']); $('.exchange_count').html(c['exchanges']); $('.payout_count').html(c['payouts']); }); }, 5000); }); </script><?}?>
</head>
<body>
<div class="header">
	<div class="header-content">
		<a class="logo" href="<?=$site['site_url']?>"><img src="theme/<?=$site['theme']?>/images/logo.png" alt="PES Pro" border="0" /></a>
        <ul id="navigation">
		<?if(!$is_online){?>
            <li><a href="faq.php"><?=$lang['b_06']?></a></li>
			<li><a href="blog.php"><?=$lang['b_287']?></a></li>
			<?if($site['reg_status'] == 0){?><li><a href="register.php" class="btn-red"><?=$lang['b_05']?></a></li>
			<?}}else{?>
			<li><a href="buy.php"><?=$lang['b_07']?></a></li>
			<li><a href="vip.php"><?=$lang['b_08']?></a></li>
			<li><a href="rewards.php"><?=$lang['b_326']?></a></li>
			<li><a href="coupons.php"><?=$lang['b_10']?></a></li>
			<li><a href="horserace.php"><?=$lang['hs_01']?></a></li>
			<?if($site['transfer_status'] != 1){?><li><a href="transfer.php"><?=$lang['b_11']?></a></li><?}?>
			<?if($site['refsys'] == 1){?><li><a href="refer.php"><?=$lang['b_12']?></a></li><?}?>
		<?}?>
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
			<?php
				$sUsers = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `users`");
				$sCash = $db->QueryFetchArray("SELECT SUM(`amount`) AS `total` FROM `requests` WHERE `paid`='1'");
				$sClick = $db->QueryFetchArray("SELECT SUM(`value`) AS `total` FROM `web_stats`");
				echo lang_rep($lang['b_366'], array('-USERS-' => '<p class="user_count">'.number_format($sUsers['total']).'</p>', '-EXCHANGES-' => '<p class="exchange_count">'.number_format($sClick['total']).'</p>', '-CASH-' => '<p class="payout_count">'.get_currency_symbol($site['currency_code']).number_format($sCash['total'], 2).'</p>'))
			?>
		</div>
		<?php
			$tops = $db->QueryFetchArrayAll("SELECT a.uid, SUM(a.today_clicks) AS clicks, b.login FROM user_clicks a LEFT JOIN users b ON b.id = a.uid WHERE b.login != '' GROUP BY a.uid ORDER BY clicks DESC LIMIT 3");
			if(count($tops) >= 3){
		?>
		<div class="home_top">
			<table class="table">
				<thead>
					<tr><td colspan="2"><?=$lang['b_239']?></td></tr>
				</thead>
				<tbody>
					<?
						$j = 0;
						foreach($tops as $top){
							$j++;
							echo '<tr><td><center><img src="img/place/place_'.$j.'.png" width="15" height="15" alt="'.$j.'" border="0" /></center></td><td>'.$top['login'].'</td></tr>';
						}
					?>
				</tbody>
			</table>
		</div>
		<?}?>
	<?}else{?>
	<div class="acm_block"> 
		<div class="acm_title"><span style="position:absolute;margin-top:-17px"><img src="http://www.gravatar.com/avatar/<?=md5(strtolower(trim($data['email'])))?>?s=40" class="acm_avatar" /></span> <span style="margin-left:50px"><?=$data['login']?></span> <span style="float:right"><a href="levels.php"><img src="<?=userLevel($data['id'], 2)?>" title="Level <?=userLevel($data['id'])?>" border="0" /></a></span></div>
		<div class="acm_inner">
			<div class="acm_manager">
				<span class="acm_left_button"><a href="addurl.php"><?=$lang['b_19']?></a></span>
				<span class="acm_right_button"><a href="mysites.php"><?=$lang['b_20']?></a></span>
			</div><hr />
			<div class="acm_data">
				<img src="theme/<?=$site['theme']?>/images/coins.png" />
				<span><?=$lang['b_42']?>: <font color="green"><b id="c_coins"><?=number_format($data['coins'])?></b></font></span>
			</div>
			<div class="acm_data">
				<img src="theme/<?=$site['theme']?>/images/cash.png" />
				<span><?=lang_rep($lang['b_261'], array('-NUM-' => '<font color="green"><b>'.get_currency_symbol($site['currency_code']).' '.$data['account_balance'].'</b></font>'))?></span>
			</div>
			<div class="acm_data">
				<img src="theme/<?=$site['theme']?>/images/vip.png" />
				<span><?=$lang['b_192'].': '.($data['premium'] > 0 ? '<font color="green"><b>'.$lang['b_194'].'</b></font> <small>('.date('d M Y', $data['premium']).')</small>' : '<font color="green"><b>'.$lang['b_193'].'</b></font>')?></span>
			</div><hr />
			<div class="acm_manager">
				<span class="acm_left_button"><a href="bank.php"><?=$lang['b_256']?></a></span>
				<span class="acm_right_button"><?=($site['allow_withdraw'] != 0 ? '<a href="bank.php?withdraw">'.$lang['b_97'].'</a>' : '<a href="vip.php">'.$lang['b_08'].'</a>')?></span>
			</div>
			<div class="acm_manager">
				<span class="acm_left_gray"><?=($site['banner_system'] != 0 ? '<a href="banners.php">'.$lang['b_189'].'</a>' : '<a href="rewards.php">'.$lang['b_326'].'</a>')?></span>
				<span class="acm_right_gray"><a href="sellcoins.php"><?=$lang['b_375']?></a></span>
			</div>
		</div>
	</div>
	<div class="ucp_menu"> 
		<div class="ucp_inner">
			<h2><?=$lang['b_22']?></h2>
			<?=hook_filter('exchange_menu', '')?>
		</div>
	</div>
<?}?></div>