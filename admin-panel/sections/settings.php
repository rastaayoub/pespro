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
			}elseif($key == 'transfer_fee'){
				$value = ($value > 100 ? 100 : ($value < 0 ? 0 : $value));
			}elseif($key == 'free_cpc' || $key == 'premium_cpc'){
				$value = ($value < 2 ? 2 : $value);
			}elseif($key == 'sc_fees'){
				$value = ($value > 90 ? 90 : ($value < 0 ? 0 : $value));
			}elseif($key == 'minimum_sc_value'){
				$value = ($value < 0.0001 ? 0.0001 : $value);
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
				<h2>General Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Site Title</strong></label>
					<div><input type="text" name="set[site_name]" value="<?=$site['site_name']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Site Description</strong><small>Website meta description</small></label>
					<div><textarea name="set[site_description]" required><?=$site['site_description']?></textarea></div>
				</div>
				<div class="row">
					<label><strong>Site Keywords</strong><small>Meta keywords separated by comma</small></label>
					<div><textarea name="set[site_keywords]" required><?=$site['site_keywords']?></textarea></div>
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
					<label><strong>Active Reports Limit</strong><small>Set 0 to disable</small></label>
					<div><input type="text" name="set[report_limit]" value="<?=$site['report_limit']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Randomize Module Results</strong><small>Will increase your server usage</small></label>
					<div><select name="set[mysql_random]"><option value="0">Disabled</option><option value="1"<?=($site['mysql_random'] == 1 ? ' selected' : '')?>>Enabled</option></select></div>
				</div>
				<div class="row">
					<label><strong>Analytics ID</strong><small>Google Analytics tracking ID</small></label>
					<div><input type="text" name="set[analytics_id]" value="<?=$site['analytics_id']?>" placeholder="Leave blank to disable!" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" value="Submit" name="submit" />
				</div>
			</div>
		</form>
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Inactive Users</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Delete Inactive Users</strong><small>Delete users inactive for X days (0 = disabled)<br />WARNING: You can't restore removed users!</small></label>
					<div><input type="text" name="set[cron_users]" value="<?=$site['cron_users']?>" maxlength="3" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" value="Submit" name="submit" />
				</div>
			</div>
		</form>
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Login Attempts</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Max login attempts</strong><small>How many times user can try to login before getting blocked!</small></label>
					<div><input type="text" name="set[login_attempts]" value="<?=$site['login_attempts']?>" maxlength="3" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Login Cooldown</strong><small>How many minutes user must way before he can try again to login!</small></label>
					<div><input type="text" name="set[login_wait_time]" value="<?=$site['login_wait_time']?>" maxlength="3" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" value="Submit" name="submit" />
				</div>
			</div>
		</form>
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Coins sales</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Minimum coins</strong><small>Minimum coins required to create coin packs</small></label>
					<div><input type="text" name="set[sc_min_coins]" value="<?=$site['sc_min_coins']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Minimum Price</strong><small>Minimum price required to create coin packs</small></label>
					<div><input type="text" name="set[sc_min_price]" value="<?=$site['sc_min_price']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Max packs for sale (FREE)</strong><small>How many packs can free user have for sale</small></label>
					<div><input type="text" name="set[free_sc_limit]" value="<?=$site['free_sc_limit']?>" maxlength="3" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Max packs for sale (VIP)</strong><small>How many packs can VIP users have for sale</small></label>
					<div><input type="text" name="set[vip_sc_limit]" value="<?=$site['vip_sc_limit']?>" maxlength="3" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Minimum coin value</strong><small>Minimum value of a coin</small></label>
					<div><input type="text" name="set[minimum_sc_value]" value="<?=$site['minimum_sc_value']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Fees</strong><small>Fees supported by the seller</small></label>
					<div><input type="text" name="set[sc_fees]" value="<?=$site['sc_fees']?>" maxlength="2" required="required" /></div>
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
				<h2>Coins per Click Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Max CPC (free users)</strong><small>Maximum CPC for free users</small></label>
					<div><input type="text" name="set[free_cpc]" value="<?=$site['free_cpc']?>" maxlength="3" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Max CPC (vip users)</strong><small>Maximum CPC for VIP users</small></label>
					<div><input type="text" name="set[premium_cpc]" value="<?=$site['premium_cpc']?>" maxlength="3" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" value="Submit" name="submit" />
				</div>
			</div>
		</form>
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Maintenance Mode</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Maintenance Mode</strong><small>Put website in maintenance mode</small></label>
					<div><select name="set[maintenance]"><option value="0">Inactive</option><option value="1"<?=($site['maintenance'] != 0 ? ' selected' : '')?>>Active</option></select></div>
				</div>	
				<div class="row">
					<label><strong>Progress (%)</strong><small>Maintenance Progress</small></label>
					<div><input type="text" name="set[m_progress]" value="<?=$site['m_progress']?>" required /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" value="Submit" name="submit" />
				</div>
			</div>
		</form>
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Website Features</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Required Exchanges</strong><small>Clicks required to be able to add pages</small></label>
					<div><input type="text" name="set[req_clicks]" value="<?=$site['req_clicks']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Enable Blog Comments</strong></label>
					<div><select name="set[blog_comments]"><option value="0">Disabled</option><option value="1"<?=($site['blog_comments'] == 1 ? ' selected' : '')?>>Enabled</option></select></div>
				</div>
				<div class="row">
					<label><strong>Targeting System</strong><small>Clicks based on Countries & Genders</small></label>
					<div><select name="set[target_system]"><option value="0">Enabled</option><option value="2"<?=($site['target_system'] == 2 ? ' selected' : '')?>>Disabled</option><option value="1"<?=($site['target_system'] == 1 ? ' selected' : '')?>>VIP Only</option></select></div>
				</div>
				<div class="row">
					<label><strong>Splash Page</strong><small>Will appear on affiliate URL's</small></label>
					<div><select name="set[splash_page]"><option value="0">Disabled</option><option value="1"<?=($site['splash_page'] == 1 ? ' selected' : '')?>>Enabled</option></select></div>
				</div>
				<div class="row">
					<label><strong>Banner Ads System</strong><small>Allow user to purchase banner ads!</small></label>
					<div><select name="set[banner_system]"><option value="0">Disabled</option><option value="1"<?=($site['banner_system'] == 1 ? ' selected' : '')?>>Enabled</option><option value="2"<?=($site['banner_system'] == 2 ? ' selected' : '')?>>Only for VIP</option></select></div>
				</div>
				<div class="row">
					<label><strong>Transfer Coins</strong><small>Allow user to transfer coins to another user!</small></label>
					<div><select name="set[transfer_status]"><option value="0">Enabled</option><option value="1"<?=($site['transfer_status'] == 1 ? ' selected' : '')?>>Disabled</option><option value="2"<?=($site['transfer_status'] == 2 ? ' selected' : '')?>>Only for VIP</option></select></div>
				</div>
				<div class="row">
					<label><strong>Transfer Fee (xx %)</strong></label>
					<div><input type="text" name="set[transfer_fee]" value="<?=$site['transfer_fee']?>" maxlength="3" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" value="Submit" name="submit" />
				</div>
			</div>
		</form>
		<form action="" method="post" class="box">
			<div class="header">
				<h2>HorseRace Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Ticket Price</strong></label>
					<div><input type="text" name="set[hs_ticket_price]" value="<?=$site['hs_ticket_price']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Max Tickets</strong><small>How many tickets can buy in the same time.</small></label>
					<div><input type="text" name="set[hs_max_tickets]" value="<?=$site['hs_max_tickets']?>" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" value="Submit" name="submit" />
				</div>
			</div>
		</form>
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Other Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Dereferrer System</strong><small>External dereferrer recommended!</small></label>
					<div><select name="set[hideref]"><option value="0">Internal</option><option value="1"<?=($site['hideref'] == 1 ? ' selected' : '')?>>External</option></select></div>
				</div>
				<div class="row">
					<label><strong>Daily clicks limit for free users</strong><small>Set 0 to disable</small></label>
					<div><input type="text" name="set[clicks_limit]" value="<?=$site['clicks_limit']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Clicks for Bonus</strong><small>Required clicks to claim daily bonus</small></label>
					<div><input type="text" name="set[crf_bonus]" value="<?=$site['crf_bonus']?>" required="required" /></div>
				</div>
				<?=hook_filter('admin_u_settings',"")?>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" value="Submit" name="submit" />
				</div>
			</div>
		</form>
	</div>
</section>