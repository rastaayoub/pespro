<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$mesaj = '';
if(isset($_POST['submit'])){
	$posts = $db->EscapeString($_POST['set']);
	foreach ($posts as $key => $value){
		if($site[$key] != $value){
			if($key == 'convert_enabled' || $key == 'allow_withdraw' || $key == 'proof_required'){
				$value = ($value > 1 ? 1 : ($value < 0 ? 0 : $value));
			}

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
			if($key == 'aff_reg_days' || $key == 'convert_rate' || $key == 'min_convert' || $key == 'aff_req_clicks'){
				$value = ($value < 0 ? 0 : (!is_numeric($value) ? $site[$key] : $value));
			}elseif($key == 'pay_min'){
				$value = ($value < 0 ? 0.00 : number_format($value, 2));
			}

			$db->Query("UPDATE `site_config` SET `config_value`='".$value."' WHERE `config_name`='".$key."'");
			$site[$key] = $value;
		}
	}
	
	$mesaj = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Settings successfully changed</div>';
}
?>
<section id="content" class="container_12 clearfix"><?=$mesaj?>
	<div class="grid_6">
		<form action="" method="post" class="box">
			<div class="header">
				<h2>General Settings</h2>
			</div>
				<div class="content">
					<div class="row">
						<label><strong>Payment Currency</strong><small>This currency is used on<br />whole website</small></label>
						<div><select name="set[currency_code]"><option value="USD">USD</option><option value="EUR"<?=($site['currency_code'] == 'EUR' ? ' selected' : '')?>>EUR</option><option value="GBP"<?=($site['currency_code'] == 'GBP' ? ' selected' : '')?>>GBP</option><option value="AUD"<?=($site['currency_code'] == 'AUD' ? ' selected' : '')?>>AUD</option><option value="HUF"<?=($site['currency_code'] == 'HUF' ? ' selected' : '')?>>HUF</option><option value="JPY"<?=($site['currency_code'] == 'JPY' ? ' selected' : '')?>>JPY</option><option value="PLN"<?=($site['currency_code'] == 'PLN' ? ' selected' : '')?>>PLN</option></select></div>
					</div>
					<div class="row">
						<label><strong>Coins to Cash</strong><small>Allow users to convert coins<br />into account balance cash</small></label>
						<div><select name="set[convert_enabled]"><option value="1">Enabled</option><option value="0"<?=($site['convert_enabled'] == 0 ? ' selected' : '')?>>Disabled</option></select></div>
					</div>
					<div class="row">
						<label><strong>Withdraw Money</strong><small>Allow users to withdraw money<br />from account balance</small></label>
						<div><select name="set[allow_withdraw]"><option value="1">Enabled</option><option value="0"<?=($site['allow_withdraw'] == 0 ? ' selected' : '')?>>Disabled</option></select></div>
					</div>
					<div class="row">
						<label><strong>Proof Required</strong><small>Allow users to withdraw again<br />only if proof was uploaded</small></label>
						<div><select name="set[proof_required]"><option value="1">Enabled</option><option value="0"<?=($site['proof_required'] == 0 ? ' selected' : '')?>>Disabled</option></select></div>
					</div>
                </div>
				<div class="actions">
					<div class="right">
						<input type="submit" value="Submit" name="submit" />
					</div>
				</div>
		</form>
	</div>
	<div class="grid_6">
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Other Settings</h2>
			</div>
				<div class="content">
					<div class="row">
						<label><strong>Conversion Rate</strong><small>How many coins for $ 1.00</small></label>
						<div><input type="text" name="set2[convert_rate]" value="<?=$site['convert_rate']?>" required="required" /></div>
					</div>
					<div class="row">
						<label><strong>Minimum Coins</strong><small>Minimum coins amount to be converted</small></label>
						<div><input type="text" name="set2[min_convert]" value="<?=$site['min_convert']?>" maxlength="5" required="required" /></div>
					</div>
					<div class="row">
						<label><strong>Minimum Payout $</strong><small>Minimum payout amount</small></label>
						<div><input type="text" name="set2[pay_min]" value="<?=$site['pay_min']?>" required="required" /></div>
					</div>
					<div class="row">
						<label><strong>Registration Days</strong><small>Required days from registration, for payout</small></label>
						<div><input type="text" name="set2[aff_reg_days]" value="<?=$site['aff_reg_days']?>" required="required" /></div>
					</div>
					<div class="row">
						<label><strong>Required Clicks</strong><small>Minimum clicks required for payout</small></label>
						<div><input type="text" name="set2[aff_req_clicks]" value="<?=$site['aff_req_clicks']?>" required="required" /></div>
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