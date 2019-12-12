<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

$mesaj = '';
if(isset($_POST['submit'])){
	$posts = $db->EscapeString($_POST['set']);
	foreach ($posts as $key => $value){
		if($site[$key] != $value){
			$db->Query("UPDATE `site_config` SET `config_value`='".$value."' WHERE `config_name`='".$key."'");
			$site[$key] = $value;
		}
	}
	
	$mesaj = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Settings successfully changed</div>';
}
if(isset($_POST['usubmit'])){
	$posts = $db->EscapeString($_POST['set2']);
	foreach ($posts as $key => $value){
		if($site[$key] != $value){
			$db->Query("UPDATE `site_config` SET `config_value`='".$value."' WHERE `config_name`='".$key."'");
			$site[$key] = $value;
		}
	}
	
	$mesaj = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Settings successfully changed</div>';
}
?>
<script>
	function emailSys() {
		var sSys = $('#mailSys').val();
		switch(sSys) {
			case '1':
				$('#smtpSet').show();
				break;
			default:
				$('#smtpSet').hide();
		}
	}
</script>
<section id="content" class="container_12 clearfix"><?=$mesaj?>
	<div class="grid_6">
		<form method="post" class="box">
			<div class="header">
				<h2>Mailing System</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Mail delivery method</strong><small>Select between PHP mail() function or SMTP</small></label>
					<div><select name="set[mail_delivery_method]" id="mailSys" onchange="emailSys()"><option value="0">PHP Mail()</option><option value="1"<?=($site['mail_delivery_method'] == 1 ? ' selected' : '')?>>SMTP</option></select></div>
				</div>
				<span id="smtpSet"<?=($site['mail_delivery_method'] == 1 ? '' : ' style="display:none"')?>>
					<div class="row">
						<label><strong>SMTP Host</strong><small>Default is localhost</small></label>
						<div><input type="text" name="set[smtp_host]" value="<?=$site['smtp_host']?>" required /></div>
					</div>
					<div class="row">
						<label><strong>SMTP Port</strong><small>Default is 25</small></label>
						<div><input type="text" name="set[smtp_port]" value="<?=$site['smtp_port']?>" required /></div>
					</div>
					<div class="row">
						<label><strong>SMTP Username</strong><small>Not required in most cases when using localhost</small></label>
						<div><input type="text" name="set[smtp_username]" value="<?=$site['smtp_username']?>" /></div>
					</div>
					<div class="row">
						<label><strong>SMTP Password</strong><small>Not required in most cases when using localhost</small></label>
						<div><input type="password" name="set[smtp_password]" value="<?=$site['smtp_password']?>" autocomplete="off" /></div>
					</div>
					<div class="row">
						<label><strong>SMTP Auth</strong><small>TLS required for Gmail SMTP</small></label>
						<div><select name="set[smtp_auth]"><option value="0">N/A</option><option value="ssl"<?=($site['smtp_auth'] == 'ssl' ? ' selected' : '')?>>SSL</option><option value="tls"<?=($site['smtp_auth'] == 'tls' ? ' selected' : '')?>>TLS</option></select></div>
					</div>
				</span>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" value="Submit" name="submit" />
				</div>
			</div>
		</form>
	</div>
	<div class="grid_6">
		<form method="post" class="box">
			<div class="header">
				<h2>eMail Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Contact Email</strong></label>
					<div><input type="text" name="set2[site_email]" value="<?=$site['site_email']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>NoReply Email</strong><small>Used for newsletters and system emails</small></label>
					<div><input type="text" name="set2[noreply_email]" value="<?=$site['noreply_email']?>" required /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" value="Submit" name="usubmit" />
				</div>
			</div>
		</form>
	</div>
</section>