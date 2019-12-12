<?php
include('header.php');
if($site['captcha_sys'] == 0){
	include('captcha.php');
}elseif($site['captcha_sys'] == 1){
	include('system/libs/recaptcha/autoload.php');
}elseif($site['captcha_sys'] == 2){
	include('system/libs/solvemedialib.php');
}

$mesaj = '';
if(isset($_POST['send'])) {

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
	}elseif(empty($_POST['name'])){
		$mesaj = '<div class="msg"><div class="error">'.$lang['b_55'].'</div></div>';
	}elseif(empty($_POST['email'])){
		$mesaj = '<div class="msg"><div class="error">'.$lang['b_56'].'</div></div>';
	}elseif(empty($_POST['message'])){
		$mesaj = '<div class="msg"><div class="error">'.$lang['b_57'].'</div></div>';
	}else{
		$subject = 'PES Contact'.($is_online && $data['premium'] > 0 ? ' - VIP Member' : '');
		$message = (!empty($data['login']) ? '<b>Sender Username:</b> '.$data['login'].'<br />' : '').'<b>Sender Email:</b> '.$_POST['email'].'<br /> <b>Sender IP:</b> '.$_SERVER['REMOTE_ADDR'].'<br />-------------------------------------<br /> <b>Website URL:</b> '.$site['site_url'].'<br /><br /> ---------------Message---------------<br /><br />'.nl2br($_POST['message']);
		$header = "From: ".$_POST['email']."\r\n".
				  "MIME-Version: 1.0\r\n".
				  "Content-Type: text/html;charset=utf-8";
		mail($site['site_email'],$subject,$message,$header);
		$mesaj = '<div class="msg"><div class="success">'.$lang['b_53'].'</div></div>';
	}
}?>
<div class="content t-left">
<h2 class="title"><?=$lang['b_47']?></h2><?=$mesaj?>
<div class="infobox">
	<form method="post">
		<p class="reg_row_1">
			<label><?=$lang['b_48']?></label> <br/>
			<input class="text big" type="text" value="<?=$data['login']?>" name="name" placeholder="<?=$lang['b_48']?>" required="required" />
		</p>
		<p class="reg_row_2">
			<label><?=$lang['b_49']?></label> <br/>
			<input class="text big" type="email" value="<?=$data['email']?>" name="email" placeholder="<?=$lang['b_49']?>" required="required" />
		</p>
		<p>
			<label><?=$lang['b_50']?></label> <br/>
			<textarea name="message" rows="6" cols="76" required="required"></textarea>
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
			<input type="submit" class="gbut" value="<?=$lang['b_52']?>" name="send" />
		</p>
	</form>
</div>
</div>
<?include('footer.php');?>