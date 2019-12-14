<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$mesaj = '';
if(isset($_POST['edit_paypal'])){
	$posts = $db->EscapeString($_POST['set']);
	foreach ($posts as $key => $value){
		if($site[$key] != $value){
			if($key == 'paypal_status' || $key == 'paypal_auto'){
				$value = ($value > 2 ? 2 : ($value < 0 ? 0 : $value));
			}

			$db->Query("UPDATE `site_config` SET `config_value`='".$value."' WHERE `config_name`='".$key."'");
			$site[$key] = $value;
		}
	}
	
	$mesaj = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Settings successfully changed</div>';
}
if(isset($_POST['edit_payza'])){
	$posts = $db->EscapeString($_POST['set2']);
	foreach ($posts as $key => $value){
		if($site[$key] != $value){
			if($key == 'payza_status' || $key == 'payza_auto'){
				$value = ($value > 2 ? 2 : ($value < 0 ? 0 : $value));
			}

			$db->Query("UPDATE `site_config` SET `config_value`='".$value."' WHERE `config_name`='".$key."'");
			$site[$key] = $value;
		}
	}
	
	$mesaj = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Settings successfully changed</div>';
}
?>
<section id="content" class="container_12"><?=$mesaj?>
	<div class="grid_6">
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Paypal</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Paypal Email</strong></label>
					<div><input type="text" name="set[paypal]" value="<?=$site['paypal']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Add Funds</strong><small>If set "Manually", you have to<br />approve each transaction.</small></label>
					<div><select name="set[paypal_auto]"><option value="0">Manually</option><option value="1"<?=($site['paypal_auto'] == 1 ? ' selected' : '')?>>Automatically</option></select></div>
				</div>
				<div class="row">
					<label><strong>Status</strong></label>
					<div><select name="set[paypal_status]"><option value="0">Disabled</option><option value="1"<?=($site['paypal_status'] == 1 ? ' selected' : '')?>>Receive & Send</option><option value="2"<?=($site['paypal_status'] == 2 || isset($_POST['paypal_status']) && $_POST['paypal_status'] == 2 ? ' selected' : '')?>>Receive Only</option></select></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="edit_paypal" value="Submit" />
				</div>
			</div>
		</form>
	</div>
	<div class="grid_6">
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Payza</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Payza Email</strong></label>
					<div><input type="text" name="set2[payza]" value="<?=$site['payza']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>IPN Security Code</strong></label>
					<div><input type="text" name="set2[payza_security]" value="<?=$site['payza_security']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Add Funds</strong><small>If set "Manually", you have to<br />approve each transaction.</small></label>
					<div><select name="set2[payza_auto]"><option value="0">Manually</option><option value="1"<?=($site['payza_auto'] == 1 ? ' selected' : '')?>>Automatically</option></select></div>
				</div>
				<div class="row">
					<label><strong>Status</strong></label>
					<div><select name="set2[payza_status]"><option value="0">Disabled</option><option value="1"<?=($site['payza_status'] == 1 ? ' selected' : '')?>>Receive & Send</option><option value="2"<?=($site['payza_status'] == 2 || isset($_POST['payza_status']) && $_POST['payza_status'] == 2 ? ' selected' : '')?>>Receive Only</option></select></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="edit_payza" value="Submit" />
				</div>
			</div>
		</form>
	</div>
</section>
<section id="content" class="container_12 clearfix">
	<div class="grid_6">
		<div class="box">
			<div class="header">
				<h2>Paypal Instrunctions</h2>
			</div>
			<div class="content">
				<p><b>PayPal Email</b> = Here you have to add your PayPal email</p>
				<p><b>PayPal Status</b> = Enable or Disable this payment gateway</p>
			</div>
		</div>
	</div>
	<div class="grid_6">
		<div class="box">
			<div class="header">
				<h2>Payza Instrunctions</h2>
			</div>
			<div class="content">
				<p>1) Login on your Payza account, go to <i>Manage Business Profiles</i> -> <i>Add</i> and create an business profile for your website</p>
				<p>2) Go to <i>Manage websites</i> -> <i>Add Website</i> and submit your website for review</p>
				<p>3) After your website was approved by Payza, go  tp <i>IPN Advanced Integration</i> -> <i>IPN Setup</i>, write your Transaction PIN and click <i>Access</i>
				<p>4) Click on <i>Edit</i>, in row with your business profile created at step 1, and complete with:
					<ul>
						<li><b>IPN Status</b> = Enabled</li>
						<li><b>Enable IPN Version 2</b> = Enabled</li>
						<li><b>Allow Encrypted Payment Details</b> = Disabled</li>
						<li><b>Alert URL:</b><br>
						<input type="text" value="<?=$site['site_url']?>/system/payments/payza/ipn.php" onclick="this.select()" style="width:300px" /></li>
						<li><b>Test Mode</b> = Disabled</li>
					</ul>
				</p>
				<p> 5) Come back here and complete <i>Payza Email</i> with your payza business email selected at step 1 and <i>IPN Security Code</i> with code from <i>IPN Setup</i> -> <i>IPN Security Code</i>
			</div>
		</div>
	</div>
</section>