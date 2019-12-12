<?php
define('BASEPATH', true);
include('../system/config.php');
if($is_online && $data['admin'] == 0){
    redirect($site['site_url']);
}elseif($is_online && $data['admin'] == 1){
	redirect('index.php');
}

$errMsg = '';
if(isset($_POST['connect'])) {
	$login = $db->EscapeString($_POST['login']);
	$pass = MD5($_POST['pass']);
	$data = $db->QueryFetchArray("SELECT id,login,admin FROM `users` WHERE (`login`='".$login."' OR `email`='".$login."') AND `pass`='".$pass."'");

	if(empty($data['id'])){
		$errMsg = 'Wrong username or password!';
	}elseif($data['admin'] == 0){
		$errMsg = 'You cannot acess this area!';
	}elseif($data['id'] != '') {
		if(isset($_POST['remember'])){
			setcookie('PESAutoLogin', 'ses_user='.$data['login'].'&ses_hash='.$pass, time()+604800, '/');
		}
		$db->Query("UPDATE `users` SET `log_ip`='".VisitorIP()."', `online`=NOW() WHERE `id`='".$data['id']."'");
		$_SESSION['EX_login'] = $data['id'];
		redirect('index.php');
	}
}
?>
<html>
<head><title>PES Pro - Admin Panel</title>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="author" content="MafiaNet (c) MN-Shop.com">
	<link rel="stylesheet" href="css/fullcss.css">
	<!--[if IE 8]><link rel="stylesheet" href="css/fonts/font-awesome-ie7.css"><![endif]-->

	<script src="js/mylibs/polyfills/fulljs.js"></script>
	<!--[if lt IE 9]><script src="js/mylibs/polyfills/selectivizr-min.js"></script><![endif]-->
	<!--[if lt IE 10]><script src="js/mylibs/polyfills/excanvas.js"></script><![endif]-->
	<!--[if lt IE 10]><script src="js/mylibs/polyfills/classlist.js"></script><![endif]-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js"></script>
	<!--[if gt IE 8]><!-->
	<script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/0.4.2/lodash.min.js"></script>
	<!--<![endif]-->
	<!--[if lt IE 9]><script src="//documentcloud.github.com/underscore/underscore.js"></script><![endif]-->

	<!-- General Scripts -->
	<script src="js/mylibs/fulljs.js"></script>
	<script src="js/mylibs/forms/fulljs.js"></script>
	<script src="js/mylibs/charts/fulljs.js"></script>
	<script src="js/mylibs/fullstats/fulljs.js"></script>
	<script src="js/mango.js"></script>
	<script src="js/plugins.js"></script>
	<script src="js/script.js"></script>
</head>
<body class="login">
	<div id="loading-overlay"></div>
	<div id="loading"><span>Loading...</span></div>
	<section id="toolbar">
		<div class="container_12">
			<div class="left">
				<ul class="breadcrumb">
					<li><a href="<?=$site['site_url']?>/admin-panel/">PES Admin</a></li>
				</ul>
			</div>
			<div class="right">
				<ul>
					<li><a href="<?=$site['site_url']?>">View Website</a></li>
					<li class="red"><a target="_blank" href="http://mn-shop.com/powerful-exchange-system-pro">PES Pro</a></li>
				</ul>
			</div>
		</div>
	</section>
		<header class="container_12">
		<div class="container">
			<a href="<?=$site['site_url']?>/admin-panel"><img src="img/logo.png" alt="PES Pro" width="181" height="46"></a>
		</div>
	</header>
	<section id="login" class="container_12 clearfix">
		<form method="post" class="box validate">
			<div class="header">
				<h2><span class="icon icon-lock"></span>Login</h2>
			</div>
			<div class="content">
				<?if(!empty($errMsg)){?><div class="login-messages"><div class="message" style="color:#771717"><?=$errMsg?></div></div><?}?>
				
				<div class="form-box">
					<div class="row">
						<label for="login_name">
							<strong>Username / Email</strong>
						</label>
						<div>
							<input tabindex="1" type="text" class="required noerror" name="login" id="login_name" />
						</div>
					</div>
					<div class="row">
						<label for="login_pw">
							<strong>Password</strong>
							<small><a href="<?=$site['site_url']?>/recover.php" id="">Forgot it?</a></small>
						</label>
						<div>
							<input tabindex="2" type="password" class="required noerror" name="pass" id="login_pw" />
						</div>
					</div>
				</div>
			</div>
			<div class="actions">
				<div class="left">
					<div class="rememberme">
						<input tabindex="4" type="checkbox" name="remember" id="remember" /><label for="login_remember">Remember me?</label>
					</div>
				</div>
				<div class="right">
					<input tabindex="3" type="submit" value="Sign In" name="connect" />
				</div>
			</div>
		</form>
	</section>
	<script> $$.loaded(); </script>
</body>
</html>
<? $db->Close(); ob_end_flush(); ?>