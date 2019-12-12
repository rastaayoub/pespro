<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$mesaj = '';
if(isset($_POST['submit'])){
	$posts = $db->EscapeString($_POST['set']);
	foreach ($posts as $key => $value){
		if($site[$key] != $value){
			if($key == 'auto_country'){
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
			if($key == 'reg_coins'){
				$value = ($value < 0 ? 0 : $value);
			}elseif($key == 'reg_cash'){
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
		<form method="post" class="box">
			<div class="header">
				<h2>General Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Registration</strong></label>
					<div><select name="set[reg_status]"><option value="0">Enabled</option><option value="1"<?=($site['reg_status'] != 0 ? ' selected' : '')?>>Disabled</option></select></div>
				</div>
				<div class="row">
					<label><strong>Auto-Select country</strong><small>Prevents selecting fake countries at registration</small></label>
					<div><select name="set[auto_country]"><option value="0">Disabled</option><option value="1"<?=($site['auto_country'] == 1 ? ' selected' : '')?>>Enabled</option></select></div>
				</div>
				<div class="row">
					<label><strong>1 account per IP</strong></label>
					<div><select name="set[more_per_ip]"><option value="0">Yes</option><option value="1"<?=($site['more_per_ip'] != 0 ? ' selected' : '')?>>No</option></select></div>
				</div>
				<div class="row">
					<label><strong>Email Confirmation</strong></label>
					<div><select name="set[reg_reqmail]"><option value="0">Enabled</option><option value="1"<?=($site['reg_reqmail'] != 0 ? ' selected' : '')?>>Disabled</option></select></div>
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
		<form method="post" class="box">
			<div class="header">
				<h2>Other Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Coins on Signup</strong><small>Coins added after registration</small></label>
					<div><input type="text" name="set2[reg_coins]" value="<?=$site['reg_coins']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Cash on Signup</strong><small>Money added after registration</small></label>
					<div><input type="text" name="set2[reg_cash]" value="<?=$site['reg_cash']?>" required /></div>
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