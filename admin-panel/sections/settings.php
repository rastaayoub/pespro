<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$mesaj = '';
if(isset($_POST['submit'])){
	$posts = $db->EscapeString($_POST['set']);
	foreach ($posts as $key => $value){
		if($site[$key] != $value){
			if($key == 'hideref' || $key == 'target_system'){
				$value = ($value > 2 ? 2 : ($value < 0 ? 0 : $value));
			}elseif($key == 'mysql_random'){
				$value = ($value > 1 ? 1 : ($value < 0 ? 0 : $value));
			}elseif($key == 'report_limit'){
				$value = ($value < 0 ? 0 : $value);
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
			if($key == 'transfer_fee'){
				$value = ($value > 100 ? 100 : ($value < 0 ? 0 : $value));
			}elseif($key == 'free_cpc' || $key == 'premium_cpc'){
				$value = ($value < 2 ? 2 : $value);
			}

			$db->Query("UPDATE `site_config` SET `config_value`='".$value."' WHERE `config_name`='".$key."'");
			$site[$key] = $value;
		}
	}
	
	$mesaj = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Settings successfully changed</div>';
}
?>
<script>
	function dereferSys() {
		var sSys = $('#derefer').val();
		switch(sSys) {
			case '2':
				$('#revshare').show();
				break;
			default:
				$('#revshare').hide();
		}
	}
</script>
<section id="content" class="container_12 clearfix"><?=$mesaj?>
	<div class="grid_6">
		<form action="" method="post" class="box">
			<div class="header">
				<h2>General Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Site Title</strong></label>
					<div><input type="text" name="set[site_name]" value="<?=$site['site_name']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Site Description</strong></label>
					<div><textarea name="set[site_description]" required><?=$site['site_description']?></textarea></div>
				</div>
				<div class="row">
					<label><strong>Site URL</strong><small>Without trailing slash</small></label>
					<div><input type="text" name="set[site_url]" value="<?=$site['site_url']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Default Language</strong></label>
					<div><select name="set[def_lang]"><?=$set_def_lang?></select></div>
				</div>
				<div class="row">
					<label><strong>Theme</strong></label>
					<div><select name="set[theme]"><?=$set_def_theme?></select></div>
				</div>
				<div class="row">
					<label><strong>Maintenance Mode</strong></label>
					<div><select name="set[maintenance]"><option value="0">Inactive</option><option value="1"<?=($site['maintenance'] != 0 ? ' selected' : '')?>>Active</option></select></div>
				</div>	
				<div class="row">
					<label><strong>Progress (%)</strong><small>Maintenance Progress</small></label>
					<div><input type="text" name="set[m_progress]" value="<?=$site['m_progress']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>FB Fanpage Username</strong></label>
					<div><input type="text" name="set[fb_fan_url]" value="<?=$site['fb_fan_url']?>" /></div>
				</div>
				<div class="row">
					<label><strong>Twitter Username</strong></label>
					<div><input type="text" name="set[m_twitter]" value="<?=$site['m_twitter']?>" /></div>
				</div>
				<div class="row">
					<label><strong>Anonymous referring</strong></label>
					<div><select name="set[hideref]" id="derefer" onchange="dereferSys()"><option value="0">Disabled</option><option value="1"<?=($site['hideref'] == 1 ? ' selected' : '')?>>HideRef</option><option value="2"<?=($site['hideref'] == 2 ? ' selected' : '')?>>RevShare</option></select></div>
				</div>
				<div class="row" id="revshare"<?=($site['hideref'] == 2 ? '' : ' style="display:none"')?>>
					<label><strong>RevShare API Key</strong><small><a href="http://rs.hideref.org/" target="_blank">Click here to get your API key</a></small></label>
					<div><input type="text" name="set[revshare_api]" value="<?=$site['revshare_api']?>"  placeholder="For RevShare anonymous"/></div>
				</div>
				<div class="row">
					<label><strong>Targeting System</strong><small>Clicks based on Countries & Genders</small></label>
					<div><select name="set[target_system]"><option value="0">Enabled</option><option value="2"<?=($site['target_system'] == 2 ? ' selected' : '')?>>Disabled</option><option value="1"<?=($site['target_system'] == 1 ? ' selected' : '')?>>VIP Only</option></select></div>
				</div>
				<div class="row">
					<label><strong>Active Reports Limit</strong><small>Set 0 to disable</small></label>
					<div><input type="text" name="set[report_limit]" value="<?=$site['report_limit']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Randomize Module Results</strong><small>Will increase your server usage</small></label>
					<div><select name="set[mysql_random]"><option value="0">Disabled</option><option value="1"<?=($site['mysql_random'] == 1 ? ' selected' : '')?>>Enabled</option></select></div>
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
					<label><strong>Max CPC (free users)</strong></label>
					<div><input type="text" name="set2[free_cpc]" value="<?=$site['free_cpc']?>" maxlength="3" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Max CPC (vip users)</strong></label>
					<div><input type="text" name="set2[premium_cpc]" value="<?=$site['premium_cpc']?>" maxlength="3" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Daily clicks limit for free users</strong><small>Set 0 to disable</small></label>
					<div><input type="text" name="set2[clicks_limit]" value="<?=$site['clicks_limit']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Daily Bonus (free users)</strong></label>
					<div><input type="text" name="set2[daily_bonus]" value="<?=$site['daily_bonus']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Daily Bonus (vip users)</strong></label>
					<div><input type="text" name="set2[daily_bonus_vip]" value="<?=$site['daily_bonus_vip']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Clicks for Bonus</strong></label>
					<div><input type="text" name="set2[crf_bonus]" value="<?=$site['crf_bonus']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Enable Blog Comments</strong></label>
					<div><select name="set2[blog_comments]"><option value="0">Disabled</option><option value="1"<?=($site['blog_comments'] == 1 ? ' selected' : '')?>>Enabled</option></select></div>
				</div>
				<div class="row">
					<label><strong>Change info limit (free)</strong></label>
					<div><input type="text" name="set2[c_c_limit]" value="<?=$site['c_c_limit']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Change info limit (vip)</strong></label>
					<div><input type="text" name="set2[c_v_limit]" value="<?=$site['c_v_limit']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Banner Ads System</strong></label>
					<div><select name="set2[banner_system]"><option value="0">Disabled</option><option value="1"<?=($site['banner_system'] == 1 ? ' selected' : '')?>>Enabled</option><option value="2"<?=($site['banner_system'] == 2 ? ' selected' : '')?>>Only for VIP</option></select></div>
				</div>
				<div class="row">
					<label><strong>Transfer Coins</strong></label>
					<div><select name="set2[transfer_status]"><option value="0">Enabled</option><option value="1"<?=($site['transfer_status'] == 1 ? ' selected' : '')?>>Disabled</option><option value="2"<?=($site['transfer_status'] == 2 ? ' selected' : '')?>>Only for VIP</option></select></div>
				</div>
				<div class="row">
					<label><strong>Transfer Fee (xx %)</strong></label>
					<div><input type="text" name="set2[transfer_fee]" value="<?=$site['transfer_fee']?>" maxlength="3" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Analytics ID</strong><small>Google Analytics tracking ID</small></label>
					<div><input type="text" name="set2[analytics_id]" value="<?=$site['analytics_id']?>" placeholder="Leave blank to disable!" /></div>
				</div>
				<?=hook_filter('admin_u_settings',"")?>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" value="Submit" name="usubmit" />
				</div>
			</div>
		</form>
	</div>
</section>