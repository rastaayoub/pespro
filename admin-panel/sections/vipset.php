<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$mesaj = '';
if(isset($_POST['submit'])){
	$posts = $db->EscapeString($_POST['set']);
	foreach ($posts as $key => $value){
		if($site[$key] != $value){
			if($key == 'vip_monthly_coins'){
				$value = ($value < 0 ? 0 : $value);
			}elseif($key == 'vip_subscription_price'){
				$value = ($value < 0.01 ? 0.01 : $value);
			}elseif($key == 'vip_purchase'){
				$value = ($value > 1 ? 1 : ($value < 0 ? 0 : $value));
			}

			$db->Query("UPDATE `site_config` SET `config_value`='".$value."' WHERE `config_name`='".$key."'");
			$site[$key] = $value;
		}
	}
	
	$mesaj = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Settings successfully changed</div>';
}
?>
<script type="text/javascript">
	function vipSys() {
		var sSys = $('#vip_purchase').val();
		switch(sSys) {
			case '1':
				$('#vipSet').show();
				break;
			default:
				$('#vipSet').hide();
		}
	}
</script>
<section id="content" class="container_12"><?=$mesaj?>
	<div class="grid_6">
		<form action="" method="post" class="box">
			<div class="header">
				<h2>VIP Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Purchase Options</strong><small>Select between Cash / Coins or PayPal subscription<br />&bull; Cash / Coins = User can purchase VIP with<br /> Account Balance or Coins<br />&bull; PayPal Subscription = User can purchase VIP by<br /> PayPal Subscription</small></label>
					<div><select name="set[vip_purchase]" id="vip_purchase" onchange="vipSys()"><option value="0">Cash / Coins</option><option value="1"<?=($site['vip_purchase'] == 1 ? ' selected' : '')?>>PayPal Subscription</option></select></div>
				</div>
				<span id="vipSet"<?=($site['vip_purchase'] == 1 ? '' : ' style="display:none"')?>>
					<div class="row">
						<label><strong>Subscription Price</strong><small>Default is localhost</small></label>
						<div><input type="text" name="set[vip_subscription_price]" value="<?=$site['vip_subscription_price']?>" required /></div>
					</div>
					<div class="row">
						<label><strong>Monthly Coins</strong><small>Set 0 to disable!</small></label>
						<div><input type="text" name="set[vip_monthly_coins]" value="<?=$site['vip_monthly_coins']?>" required /></div>
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
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Other Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Change info limit (free)</strong><small>How many times free user can change<br /> gender or country</small></label>
					<div><input type="text" name="set[c_c_limit]" value="<?=$site['c_c_limit']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Change info limit (vip)</strong><small>How many times VIP user can change<br /> gender or country</small></label>
					<div><input type="text" name="set[c_v_limit]" value="<?=$site['c_v_limit']?>" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" value="Submit" name="submit" />
				</div>
			</div>
		</form>
	</div>
</section>