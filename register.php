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

if(isset($_GET['resend'])){
	if(isset($_POST['resend'])){
		$email = $db->EscapeString($_POST['email']);

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
			$mesaj = '<div class="msg"><div class="error">'.$lang['b_54'].'</div></div>';
		}elseif ($db->QueryGetNumRows("SELECT id FROM `users` WHERE `email`='".$email ."' AND `activate`!='0' LIMIT 1") == 0) {
			$mesaj = '<div class="msg"><div class="error">'.$lang['b_191'].'</div></div>';
		}else{
			require('system/libs/PHPMailer/PHPMailerAutoload.php');

			$row = $db->QueryFetchArray("SELECT login,activate FROM `users` WHERE `email`='".$email ."' AND `activate`!='0' LIMIT 1");

			$mailer = new PHPMailer();
			
			if($site['mail_delivery_method'] == 1){
				$mailer->isSMTP();
				$mailer->Host = $site['smtp_host'];
				$mailer->Port = $site['smtp_port'];

				if(!empty($site['smtp_auth'])){
					$mailer->SMTPSecure = $site['smtp_auth'];
				}
				$mailer->SMTPAuth = (empty($site['smtp_username']) || empty($site['smtp_password']) ? false : true);
				if($mailer->SMTPAuth){
					$mailer->Username = $site['smtp_username'];
					$mailer->Password = $site['smtp_password'];
				}
			}
			
			$mailer->AddAddress($email, $row['login']);
			$mailer->SetFrom((!empty($site['noreply_email']) ? $site['noreply_email'] : $site['site_email']), $site['site_name']);
			$mailer->Subject = $lang['b_130'];
			$mailer->MsgHTML('<html>
								<body style="font-family: Verdana; color: #333333; font-size: 12px;">
									<table style="width: 400px; margin: 0px auto;">
										<tr style="text-align: center;">
											<td style="border-bottom: solid 1px #cccccc;"><h1 style="margin: 0; font-size: 20px;"><a href="'.$site['site_url'].'" style="text-decoration:none;color:#333333"><b>'.$site['site_name'].'</b></a></h1><h2 style="text-align: right; font-size: 14px; margin: 7px 0 10px 0;">'.$lang['b_130'].'</h2></td>
										</tr>
										<tr style="text-align: justify;">
											<td style="padding-top: 15px; padding-bottom: 15px;">
												Hello '.$row['login'].',
												<br />
												<br />
												Click on this link to activate your account:<br />
												<a href="'.$site['site_url'].'/activate.php?code='.$row['activate'].'">'.$site['site_url'].'/activate.php?code='.$row['activate'].'</a>
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

			$mesaj = '<div class="msg"><div class="success">'.$lang['b_190'].'</div></div>';
		}
	}
?>
<div class="content-ex" style="text-align:left"><?=$mesaj?>
	<form action="" method="post">
			<p>
				<label><?=$lang['b_70']?></label><br />
				<input class="text-max" type="email" value="<?=(isset($_POST['email']) ? $db->EscapeString($_POST['email']) : '')?>" name="email" required="required" />
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
				<input type="submit" class="gbut" value="<?=$lang['b_58']?>" name="resend" />
			</p>
	</form>
</div>
<?
}else{
$mesaj = '';
if($site['reg_status'] == 0){
	$IP = VisitorIP();
	$IP = ($IP != '' ? $IP : 0);

	$sql = $db->Query("SELECT code FROM `list_countries` ORDER BY country");
	$ctrs = array();
	while ($row = $db->FetchArray($sql)) {
		$ctrs[] = $row['code'];
	}

	$c_done = 0;
	if($site['auto_country'] == 1){
		$a_country = iptocountry($IP);
		if(in_array($a_country, $ctrs)){
			$c_done = 1;
			$country = $a_country;
		}
	}

	if(isset($_POST['register'])){
		$name = $db->EscapeString($_POST['user']);
		$email = $db->EscapeString($_POST['email']);
		$email2 = $db->EscapeString($_POST['email2']);
		$gender = $db->EscapeString($_POST['gender']);
		$pass1 = $db->EscapeString($_POST['password']);
		$pass2 = $db->EscapeString($_POST['password2']);
		$subs = $db->EscapeString($_POST['subscribe']);
		$subs = ($subs != 0 && $subs != 1 ? 1 : $subs);
		
		if($c_done == 0){
			$country = $db->EscapeString($_POST['country']);
		}
		
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
			$mesaj = '<div class="msg"><div class="error">'.$lang['b_54'].'</div></div>';
		}elseif(!isUserID($name)) {
			$mesaj = '<div class="msg"><div class="error">'.$lang['b_129'].'</div></div>';
		}elseif(!isEmail($email)) {
			$mesaj = '<div class="msg"><div class="error">'.$lang['b_65'] .'</div></div>';
		}elseif(!checkPwd($pass1,$pass2)) {
			$mesaj = '<div class="msg"><div class="error">'.$lang['b_63'].'</div></div>';
		}elseif($email != $email2) {
			$mesaj = '<div class="msg"><div class="error">'.$lang['b_279'] .'</div></div>';
		}elseif($gender != 1 && $gender != 2) {
			$mesaj = '<div class="msg"><div class="error">'.$lang['b_208'].'</div></div>';
		}elseif(blacklist_check($email, 1)) {
			$mesaj = '<div class="msg"><div class="error">'.lang_rep($lang['b_294'], array('-EMAIL-' => $email)).'</div></div>';
		}elseif(blacklist_check($IP, 3)) {
			$mesaj = '<div class="msg"><div class="error">'.lang_rep($lang['b_295'], array('-IP-' => $IP)).'</div></div>';
		}elseif($db->QueryGetNumRows("SELECT id FROM `users` WHERE `login`='".$name."' OR `email`='".$email."' LIMIT 1") > 0) {
			$mesaj = '<div class="msg"><div class="error">'.$lang['b_127'].'</div></div>';
		}elseif($site['more_per_ip'] != 1 && isset($_COOKIE['PESAccExist'])) {
			$mesaj = '<div class="msg"><div class="error"><b>'.$lang['b_128'].'</b></div></div>';
		}elseif($site['more_per_ip'] != 1 && $db->QueryGetNumRows("SELECT id FROM `users` WHERE `IP`='".$IP."' OR `log_ip`='".$IP."' LIMIT 1") > 0) {
			$mesaj = '<div class="msg"><div class="error"><b>'.$lang['b_128'].'</b></div></div>';
		}elseif($c_done == 0 && !in_array($country, $ctrs)) {
			$mesaj = '<div class="msg"><div class="error">'.$lang['b_209'].'</div></div>';
		}else{
			$ref_paid = 0;
			$activate = 0;
			$referal = (isset($_COOKIE['PlusREF']) ? $db->EscapeString($_COOKIE['PlusREF']) : 0);

			if($referal != 0 && $db->QueryGetNumRows("SELECT id FROM `users` WHERE `id`='".$referal."' LIMIT 1") == 0) {
				$referal = 0;
			}

			if($site['reg_reqmail'] == 0){
				require('system/libs/PHPMailer/PHPMailerAutoload.php');
				$activate = rand(1000000, 999999999);
				
				$mailer = new PHPMailer();
				
				if($site['mail_delivery_method'] == 1){
					$mailer->isSMTP();
					$mailer->Host = $site['smtp_host'];
					$mailer->Port = $site['smtp_port'];

					if(!empty($site['smtp_auth'])){
						$mailer->SMTPSecure = $site['smtp_auth'];
					}
					$mailer->SMTPAuth = (empty($site['smtp_username']) || empty($site['smtp_password']) ? false : true);
					if($mailer->SMTPAuth){
						$mailer->Username = $site['smtp_username'];
						$mailer->Password = $site['smtp_password'];
					}
				}
				
				$mailer->AddAddress($email, $name);
				$mailer->SetFrom((!empty($site['noreply_email']) ? $site['noreply_email'] : $site['site_email']), $site['site_name']);
				$mailer->Subject = $lang['b_130'];
				$mailer->MsgHTML('<html>
									<body style="font-family: Verdana; color: #333333; font-size: 12px;">
										<table style="width: 400px; margin: 0px auto;">
											<tr style="text-align: center;">
												<td style="border-bottom: solid 1px #cccccc;"><h1 style="margin: 0; font-size: 20px;"><a href="'.$site['site_url'].'" style="text-decoration:none;color:#333333"><b>'.$site['site_name'].'</b></a></h1><h2 style="text-align: right; font-size: 14px; margin: 7px 0 10px 0;">'.$lang['b_130'].'</h2></td>
											</tr>
											<tr style="text-align: justify;">
												<td style="padding-top: 15px; padding-bottom: 15px;">
													Hello '.$name.',
													<br /><br />
													Click on this link to activate your account:<br />
													<a href="'.$site['site_url'].'/activate.php?code='.$activate.'">'.$site['site_url'].'/activate.php?code='.$activate.'</a>
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
			}else{
				if($referal > 0 && is_numeric($referal) && $site['refsys'] == 1 && $site['aff_click_req'] == 0){
					$user = $db->QueryFetchArray("SELECT id FROM `users` WHERE `id`='".$referal."' LIMIT 1");
					if($user['id'] > 0){
						$add_cash = $site['paysys'] == 1 ? ", `account_balance`=`account_balance`+'".$site['ref_cash']."'" : '';
						$db->Query("UPDATE `users` SET `coins`=`coins`+'".$site['ref_coins']."'".$add_cash." WHERE `id`='".$user['id']."'");
						$ref_paid = 1;
					}
				}
			}

			$passc = MD5($pass1);
			if(isset($_COOKIE['PESRefSource'])){
				$ref_source = $db->EscapeString($_COOKIE['PESRefSource']);
			}else{
				$ref_source = '0';
			}
			
			if(!isset($_COOKIE['PESAccExist'])){
				setcookie('PESAccExist', $name, time()+604800, '/');
			}
			
			$db->Query("INSERT INTO `users`(email,login,country,sex,coins,account_balance,IP,pass,ref,ref_paid,signup,newsletter,activate,ref_source) values('".$email."','".$name."','".$country."','".$gender."','".$site['reg_coins']."','".$site['reg_cash']."','".$IP."','".$passc."','".$referal."','".$ref_paid."',NOW(),'".$subs."','".$activate."','".$ref_source."')");
			$mesaj = '<div class="msg"><div class="success">'.$lang['b_131'].' '.($site['reg_reqmail'] == 0 ? $lang['b_132'] : $lang['b_133']).'</div></div>';
		}
	}
?>
<script type="text/javascript"> function check_username(){var b=$('#username').val();if(b.length<3){$('#username').css('border','2px solid #a50000')}else{$.get("system/ajax.php?a=checkUser",{data:b},function(a){if(a==1){$('#username').css('border','2px solid #00a500')}else{$('#username').css('border','2px solid #a50000')}})}}function check_email(){var b=$('#email').val();if(b.length<3){$('#email').css('border','2px solid #a50000')}else{$.get("system/ajax.php?a=checkEmail",{data:b},function(a){if(a==1){$('#email').css('border','2px solid #00a500')}else{$('#email').css('border','2px solid #a50000')}})}}function check_email2(){var a=new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);var b=$('#email').val();var c=$('#email2').val();if(!a.test(c)){$('#email2').css('border','2px solid #a50000')}else if(b==c){$('#email2').css('border','2px solid #00a500')}else{$('#email2').css('border','2px solid #a50000')}} </script>
<div class="content-ex" style="text-align:left"><?=$mesaj?>
	<form action="" method="post">
			<p class="reg_row_1">
				<label><?=$lang['b_122']?></label><br />
				<input class="text-max" type="text" value="<?=(isset($_POST['user']) ? $db->EscapeString($_POST['user']) : '')?>" name="user" id="username" placeholder="John_Doe" onchange="check_username()" required />
			</p>
			<p class="reg_row_2"> </p>
			<p class="reg_row_1">
				<label><?=$lang['b_70']?></label><br />
				<input class="text-max" type="email" value="<?=(isset($_POST['email']) ? $db->EscapeString($_POST['email']) : '')?>" name="email" id="email" placeholder="email@name.com" onchange="check_email()" required />
			</p>
			<p class="reg_row_2">
				<label><?=$lang['b_278']?></label><br />
				<input class="text-max" type="email" name="email2" id="email2" placeholder="email@name.com" onchange="check_email2()" required />
			</p>
			<p class="reg_row_1">
				<label><?=$lang['b_15']?></label><br />
				<input class="text-max" type="password" value="" name="password" placeholder="<?=$lang['b_15']?>" required />
			</p>
			<p class="reg_row_2">
				<label><?=$lang['b_72']?></label><br />
				<input class="text-max" type="password" value="" name="password2" placeholder="<?=$lang['b_15']?>" required />
			</p>
			<p class="reg_row_1">
				<label><?=$lang['b_202']?></label><br />
				<select name="gender" class="styled" style="height:40px;width:315px">
					<option value="0"></option>
					<option value="1"><?=$lang['b_203']?></option>
					<option value="2"><?=$lang['b_204']?></option>
				</select>
			</p>
			<p class="reg_row_2">
				<label><?=$lang['b_201']?></label><br />
				<select name="country" class="styled" style="height:40px;width:315px" <?=($c_done == 1 ? 'disabled' : '')?>>
					<? 
						if($c_done == 1){
							$ctr = $db->QueryFetchArray("SELECT country,code FROM `list_countries` WHERE `code`='".$country."'"); 
							echo '<option value="'.$ctr['code'].'">'.$ctr['country'].'</option>';
						}else{
							$countries = $db->QueryFetchArrayAll("SELECT country,code FROM `list_countries` ORDER BY country ASC"); 
							echo '<option value="0"></option>';
							foreach($countries as $country){ 
								echo '<option value="'.$country['code'].'">'.$country['country'].'</option>';
							}
						}
					?>
				</select>
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
				<label><?=$lang['b_245']?></label>
				<input type="radio" name="subscribe" value="1" checked="checked" /> <?=$lang['b_124']?> <input type="radio" name="subscribe" value="0" /> <?=$lang['b_125']?>
			</p>
			<p>
				<input type="submit" class="gbut" value="<?=$lang['b_58']?>" name="register" />
			</p>
	</form>
	<span style="float:right"><b><a href="register.php?resend" style="text-decoration:none"><?=$lang['b_227']?></a></b></span>
</div>
<?}else{?>
	<div class="content-ex"><div class="msg"><div class="error"><?=$lang['b_134']?></div></div></div>
<?}}
include('footer.php');?>