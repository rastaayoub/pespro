<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$mesaj = '';
if(isset($_POST['edit_captcha'])){
	$posts = $db->EscapeString($_POST['set']);
	foreach ($posts as $key => $value){
		if($site[$key] != $value){
			if($key == 'captcha_sys'){
				$value = ($value > 3 ? 3 : ($value < 0 ? 0 : $value));
			}

			$db->Query("UPDATE `site_config` SET `config_value`='".$value."' WHERE `config_name`='".$key."'");
			$site[$key] = $value;
		}
	}
	
	$mesaj = '<div class="alert success"><span class="icon"></span><strong>SUCCESS:</strong> Captcha settings successfully saved!</div>';
}
?>
<script>
	function captchaSys() {
		var sSys = $('#captcha_sys').val();
		switch(sSys) {
			case '1':
				$('#reCaptcha').show();
				$('#solveMedia').hide();
				break;
			case '2':
				$('#solveMedia').show();
				$('#reCaptcha').hide();
				break;
			default:
				$('#reCaptcha').hide();
				$('#solveMedia').hide();
		}
	}
</script>
<section id="content" class="container_12"><?=$mesaj?>
	<div class="grid_6">
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Captcha Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Captcha System</strong></label>
					<div><select name="set[captcha_sys]" id="captcha_sys" onchange="captchaSys()"><option value="0">Classic</option><option value="1"<?=($site['captcha_sys'] == 1 ? ' selected' : '')?>>reCaptcha</option><option value="2"<?=($site['captcha_sys'] == 2 ? ' selected' : '')?>>SolveMedia</option><option value="3"<?=($site['captcha_sys'] == 3 ? ' selected' : '')?>>Disabled</option></select></div>
				</div>
				<span id="reCaptcha"<?=($site['captcha_sys'] == 1 ? '' : ' style="display:none"')?>>
					<div class="row">
						<label><strong>ReCaptcha Public Key</strong></label>
						<div><input type="text" name="set[recaptcha_pub]" value="<?=$site['recaptcha_pub']?>" /></div>
					</div>
					<div class="row">
						<label><strong>ReCaptcha Private Key</strong></label>
						<div><input type="text" name="set[recaptcha_sec]" value="<?=$site['recaptcha_sec']?>" /></div>
					</div>
				</span>
				<span id="solveMedia"<?=($site['captcha_sys'] == 2 ? '' : ' style="display:none"')?>>
					<div class="row">
						<label><strong>SolveMedia C-Key</strong></label>
						<div><input type="text" name="set[solvemedia_c]" value="<?=$site['solvemedia_c']?>" /></div>
					</div>
					<div class="row">
						<label><strong>SolveMedia V-Key</strong></label>
						<div><input type="text" name="set[solvemedia_v]" value="<?=$site['solvemedia_v']?>" /></div>
					</div>
					<div class="row">
						<label><strong>SolveMedia H-Key</strong></label>
						<div><input type="text" name="set[solvemedia_h]" value="<?=$site['solvemedia_h']?>" /></div>
					</div>
				</span>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="edit_captcha" value="Submit" />
				</div>
			</div>
		</form>
	</div>
	<div class="grid_6">
		<div class="box">
			<div class="header">
				<h2>Instrunctions</h2>
			</div>
			<div class="content">
				<p><strong>ReCaptcha</strong></p>
				<p><b>1)</b> <a href="https://www.google.com/recaptcha/admin/create" target="_blank">Click Here</a>, complete on "Domain" with your domain name and click on "Create Key"</p>
				<p><b>2)</b> Copy generated "Public Key" and paste this key on "ReCaptcha Public Key"</p>
				<p><b>3)</b> Copy generated "Private Key" and paste this key on "ReCaptcha Private Key"</p>
				<p><b>4)</b> Press on "Submit" and you're done</p><br />
				<p><strong>SolveMedia</strong></p>
				<p><b>1)</b> <a href="https://portal.solvemedia.com/portal/public/signup" target="_blank">Click Here</a>, complete required info and join on this website</p>
				<p><b>2)</b> After you joined on SolveMedia, login and go to <i>Sites</i> then click on <i>Add Site</i></p>
				<p><b>3)</b> Complete with required info and click on <i>Submit</i>. When your website was successfully submited, click on <i>Keys</i> and then configure your SolveMedia captcha system here</p>
			</div>
		</div>
	</div>
</section>