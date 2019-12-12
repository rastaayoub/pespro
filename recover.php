<?php
include('header.php');
if($is_online){redirect('index.php');}
if($site['captcha_sys'] == 0){
	include('captcha.php');
}elseif($site['captcha_sys'] == 1){
	include('system/libs/recaptcha/autoload.php');
}elseif($site['captcha_sys'] == 2){
	include('system/libs/solvemedialib.php');
}

$mesaj = '';
if(isset($_GET['hash']) && $_GET['hash'] > 0 && is_numeric($_GET['hash'])){
	$hash = $db->EscapeString($_GET['hash']);
	$rec = $db->QueryFetchArray("SELECT id,login,email FROM `users` WHERE `rec_hash`='".$hash."' LIMIT 1");
	
	$captcha_valid = 1;
	if($site['captcha_sys'] != 3){
		$captcha_valid = 0;
		if($site['captcha_sys'] == 1){
			$recaptcha = new \ReCaptcha\ReCaptcha($site['recaptcha_sec']);
			$recaptcha = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
		
			if($recaptcha->isSuccess()){
				$captcha_valid = 1;
			}else{
				$recaptcha_error = $resp->error;
				$captcha_valid = 0;
			}
		}elseif($site['captcha_sys'] == 2){
			$solvemedia_response = solvemedia_check_answer($site['solvemedia_v'],$_SERVER["REMOTE_ADDR"],$_POST["adcopy_challenge"],$_POST["adcopy_response"],$site['solvemedia_h']);
			if(!$solvemedia_response->is_valid){
				$recaptcha_error = $solvemedia_response->error;
				$captcha_valid = 0;
			}else{
				$captcha_valid = 1;
			}
		}else{
			if(check_captcha($_POST['captcha'])){
				$captcha_valid = 1;
			}else{
				$captcha_valid = 0;
			}
		}
	}

	if(empty($rec['id'])){
		redirect('recover.php');
	}else{
		if(isset($_POST['change'])) {
			$pass1 = $_POST['pass1'];
			$pass2 = $_POST['pass2'];
			if(!$captcha_valid){
				$mesaj = '<div class="msg"><div class="error">'.$lang['b_54'].'</div></div>';
			}elseif(!checkPwd($pass1,$pass2)) {
				$mesaj = '<div class="error">'.$lang['b_63'].'</div>';
			}else{
				$passc = MD5($pass1);
				$db->Query("UPDATE `users` SET `pass`='".$passc."', `rec_hash`='0' WHERE `email`='".$rec['email']."'");
				$mesaj = '<div class="success">'.$lang['b_64'].'</div>';
			}
		}
?>
<div class="content t-left"><h2 class="title"><?=$lang['b_68']?></h2>
<div class="msg"><?=$mesaj?></div>
	<div class="infobox">
		<form id="form" method="post">
			<p>
				<label><?=$lang['b_71']?></label><br />
				<input class="text big" name="pass1" type="password" value="" required="required" />
			</p>
			<p>
				<label><?=$lang['b_72']?></label><br />
				<input class="text big" name="pass2" type="password" value="" required="required" />
			</p>
			<p>
			<?
				if($site['captcha_sys'] != 3){
			?>
			<p>
				<?
				if($site['captcha_sys'] == 1){
					echo '<script src="https://www.google.com/recaptcha/api.js"></script><div class="g-recaptcha" data-sitekey="'.$site['recaptcha_pub'].'"></div>';
				}elseif($site['captcha_sys'] == 2){
					echo solvemedia_get_html($site['solvemedia_c']);
				}else{
				?>
				<label><?=$lang['b_51']?></label><br />
				<span style="background:#efefef;padding:7px;border-radius:3px;display:inline-block"><img src="captcha.php?img=<?=time();?>" alt="" /></span>
				<span style="margin-left:2px;display:inline;position:absolute"><input class="l_form" type="text" value="" name="captcha" required="required" /></span>
				<?}?>
			</p>
			<?}?>
			</p>
			<p>
			<input class="gbut" type="submit" name="change" value="<?=$lang['b_58']?>" />
			</p>
		</form>
	</div>
</div>
<?
	}
}else{

if(isset($_POST['send'])) {
	$email = $db->EscapeString($_POST['email']);
	$rec = $db->QueryFetchArray("SELECT id,login FROM `users` WHERE `email`='".$email."' LIMIT 1");

	$captcha_valid = 1;
	if($site['captcha_sys'] != 3){
		$captcha_valid = 0;
		if($site['captcha_sys'] == 1){
			$recaptcha = new \ReCaptcha\ReCaptcha($site['recaptcha_sec']);
			$recaptcha = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
		
			if($recaptcha->isSuccess()){
				$captcha_valid = 1;
			}else{
				$recaptcha_error = $resp->error;
				$captcha_valid = 0;
			}
		}elseif($site['captcha_sys'] == 2){
			$solvemedia_response = solvemedia_check_answer($site['solvemedia_v'],$_SERVER["REMOTE_ADDR"],$_POST["adcopy_challenge"],$_POST["adcopy_response"],$site['solvemedia_h']);
			if(!$solvemedia_response->is_valid){
				$recaptcha_error = $solvemedia_response->error;
				$captcha_valid = 0;
			}else{
				$captcha_valid = 1;
			}
		}else{
			if(check_captcha($_POST['captcha'])){
				$captcha_valid = 1;
			}else{
				$captcha_valid = 0;
			}
		}
	}

	if(!$captcha_valid){
		$mesaj = '<div class="error">'.$lang['b_54'].'</div>';
	}elseif($_POST['email'] == ""){
		$mesaj = '<div class="error">'.$lang['b_65'].'</div>';
	}elseif(empty($rec['login'])){
		$mesaj = '<div class="error">'.$lang['b_110'].'</div>';
	}else{
		$newhash = rand(100000,9999999);
		$db->Query("UPDATE `users` SET `rec_hash`='".$newhash."' WHERE `email`='".$email."'");
		
		$subject = $lang['b_15'];
		$recover_url = $site['site_url'].'/recover.php?hash='.$newhash;

		require('system/libs/PHPMailer/PHPMailerAutoload.php');
		$mailer = new PHPMailer();
		
		if($site['mail_delivery_method'] == 1){
			$mailer->isSMTP();
			$mailer->Host = $site['smtp_host'];
			$mailer->Port = $site['smtp_port'];

			if(!empty($site['smtp_auth'])){
				$mailer->SMTPSecure = $site['smtp_auth'];
			}
			$mailer->SMTPAuth = (empty($site['smtp_username']) || empty($site['smtp_password']) ? false : true);
			if(!empty($site['smtp_username']) && !empty($site['smtp_password'])){
				$mailer->Username = $site['smtp_username'];
				$mailer->Password = $site['smtp_password'];
			}
		}

		$mailer->AddAddress($email, $rec['login']);
		$mailer->SetFrom((!empty($site['noreply_email']) ? $site['noreply_email'] : $site['site_email']), $site['site_name']);
		$mailer->Subject = $subject;
		$mailer->MsgHTML('<html>
							<body style="font-family: Verdana; color: #333333; font-size: 12px;">
								<table style="width: 400px; margin: 0px auto;">
									<tr style="text-align: center;">
										<td style="border-bottom: solid 1px #cccccc;"><h1 style="margin: 0; font-size: 20px;"><a href="'.$site['site_url'].'" style="text-decoration:none;color:#333333"><b>'.$site['site_name'].'</b></a></h1><h2 style="text-align: right; font-size: 14px; margin: 7px 0 10px 0;">'.$subject.'</h2></td>
									</tr>
									<tr style="text-align: justify;">
										<td style="padding-top: 15px; padding-bottom: 15px;">
											Hello '.$rec['login'].',
											<br /><br />
											You asked to recover your password.<br />To get your new password, access this URL:<br />
											<a href="'.$recover_url.'">'.$recover_url.'</a>
										</td>
									</tr>
									<tr style="text-align: right; color: #777777;">
										<td style="padding-top: 10px; border-top: solid 1px #cccccc;">
											Best Regards!
										</td>
									</tr>
								</table>
							</body>
						</html>');
		$mailer->Send();

		$mesaj = '<div class="success">'.$lang['b_111'].'</div>';
	}
}?>
<div class="content t-left"><h2 class="title"><?=$lang['b_112']?></h2><div class="msg"><?echo $mesaj;?></div>
	<div class="infobox">
		<form id="form" method="post">
			<p>
				<label><?=$lang['b_70']?></label><br />
				<input class="text big" name="email" type="email" value="" required="required" />
			</p>
			<?
				if($site['captcha_sys'] != 3){
			?>
			<p>
				<?
				if($site['captcha_sys'] == 1){
					echo '<script src="https://www.google.com/recaptcha/api.js"></script><div class="g-recaptcha" data-sitekey="'.$site['recaptcha_pub'].'"></div>';
				}elseif($site['captcha_sys'] == 2){
					echo solvemedia_get_html($site['solvemedia_c']);
				}else{
				?>
				<label><?=$lang['b_51']?></label><br />
				<span style="background:#efefef;padding:7px;border-radius:3px;display:inline-block"><img src="captcha.php?img=<?=time();?>" alt="" /></span>
				<span style="margin-left:2px;display:inline;position:absolute"><input class="l_form" type="text" value="" name="captcha" required="required" /></span>
				<?}?>
			</p>
			<?}?>
			<p>
			<input class="gbut" type="submit" name="send" value="<?=$lang['b_52']?>" />
			</p>
		</form>
	</div>
</div>
<?}
include('footer.php');?>