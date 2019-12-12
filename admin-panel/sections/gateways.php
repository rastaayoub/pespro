<?php
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
if(isset($_POST['edit_payeer'])){
	$posts = $db->EscapeString($_POST['set3']);
	foreach ($posts as $key => $value){
		if($site[$key] != $value){
			if($key == 'payeer_status' || $key == 'payeer_auto'){
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
					<label><strong>Minimum Amount</strong><small>Minimum amount that can<br />be added by the user.</small></label>
					<div><input type="text" name="set[paypal_minimum]" value="<?=$site['paypal_minimum']?>" required="required" /></div>
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

		<form action="" method="post" class="box">
			<div class="header">
				<h2>Payeer</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Merchant ID</strong></label>
					<div><input type="text" name="set3[payeer_key]" value="<?=$site['payeer_key']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Secret key</strong></label>
					<div><input type="text" name="set3[payeer_secret]" value="<?=$site['payeer_secret']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Minimum Amount</strong><small>Minimum amount that can<br />be added by the user.</small></label>
					<div><input type="text" name="set3[payeer_minimum]" value="<?=$site['payeer_minimum']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Add Funds</strong><small>If set "Manually", you have to<br />approve each transaction.</small></label>
					<div><select name="set3[payeer_auto]"><option value="0">Manually</option><option value="1"<?=($site['payeer_auto'] == 1 ? ' selected' : '')?>>Automatically</option></select></div>
				</div>
				<div class="row">
					<label><strong>Status</strong></label>
					<div><select name="set3[payeer_status]"><option value="0">Disabled</option><option value="1"<?=($site['payeer_status'] == 1 ? ' selected' : '')?>>Receive & Send</option><option value="2"<?=($site['payeer_status'] == 2 || isset($_POST['payeer_status']) && $_POST['payeer_status'] == 2 ? ' selected' : '')?>>Receive Only</option></select></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="edit_payeer" value="Submit" />
				</div>
			</div>
		</form>
	</div>

	<div class="grid_6">
		<div class="box">
			<div class="header">
				<h2>Paypal Instructions</h2>
			</div>
			<div class="content">
				<p><b>PayPal Email</b> = Here you have to add your PayPal email</p>
				<p><b>Status</b> = Enable or Disable this payment gateway. Also, you can choose to use it only to receive money (without allowing users to request payments using this payment gateway).</p>
			</div>
		</div>

		<div class="box">
			<div class="header">
				<h2>Payeer Instructions</h2>
			</div>
			<div class="content">
				<p>1) Login on your <a href="https://payeer.com/04641331" target="_blank">Payeer account</a>, go to <i>Dashboard</i> -> <i>Merchant Settings</i> then click on <i>Add Merchant</i>. <strong style="color:red;">ATENTION:</strong> Please copy the <i>Secret key</i>, then past it here into <i>Secret Key</i> field</p>
				<p>2) Complete first form with required info then confirm your website following provided instructions.</p>
				<p>3) Complete <i>Merchant Settings</i> using URL's from bellow then submit your website for approval.</p>
				<p>
					<ul>
						<li><b>Success URL</b><br /><input type="text" value="<?=$site['site_url']?>/bank.php?success" onclick="this.select()" style="width:300px" readonly /></li><br />
						<li><b>Fail URL</b><br /><input type="text" value="<?=$site['site_url']?>/bank.php?cancel" onclick="this.select()" style="width:300px" readonly /></li><br />
						<li><b>Status URL</b><br /><input type="text" value="<?=$site['site_url']?>/system/payments/payeer/ipn.php" onclick="this.select()" style="width:300px" readonly /></li>
					</ul>
				</p>
				<p> 4) Complete field <i>Merchant ID</i> with your Payeer Merchant ID for this website.</p>
			</div>
		</div>
	</div>
</section>