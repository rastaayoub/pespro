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
<section id="content" class="container_12 clearfix"><?=$mesaj?>
	<div class="grid_6">
		<form action="" method="post" class="box">
			<div class="header">
				<h2>General Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Time Type</strong><small>If you select "Based on CPC",<br />time will be CPC x Time</small></label>
					<div><select name="set[surf_time_type]"><option value="0">Fixed Time</option><option value="1"<?=($site['surf_time_type'] == 1 && !isset($_POST['surf_time_type']) ? ' selected' : (isset($_POST['surf_time_type']) && $_POST['surf_time_type'] == 1 ? ' selected' : ''))?>>Based on CPC</option></select></div>
				</div>
				<div class="row">
					<label><strong>Traffic Exchange time</strong></label>
					<div><input type="text" name="set[surf_time]" value="<?=$site['surf_time']?>" title="How many seconds?" required /></div>
				</div>
				<div class="row">
					<label><strong>Traffic Exchange type</strong></label>
					<div><select name="set[surf_type]"><option value="0">Auto-Surf</option><option value="1"<?=($site['surf_type'] == 1 && !isset($_POST['surf_type']) ? ' selected' : (isset($_POST['surf_type']) && $_POST['surf_type'] == 1 ? ' selected' : ''))?>>Manual-Surf</option><option value="2"<?=($site['surf_type'] == 2 && !isset($_POST['surf_type']) ? ' selected' : (isset($_POST['surf_type']) && $_POST['surf_type'] == 2 ? ' selected' : ''))?>>Popup-Surf</option></select></div>
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
					<label><strong>Skip pages with frame breaker</strong><small>Not applicable for "Popup-Surf"</small></label>
					<div><select name="set2[surf_fb_skip]"><option value="0">No</option><option value="1"<?=($site['surf_fb_skip'] == 1 ? ' selected' : '')?>>Yes</option></select></div>
				</div>
				<div class="row">
					<label><strong>Focus window required</strong><small>Not applicable for "Popup-Surf"</small></label>
					<div><select name="set2[surf_fc_req]"><option value="0">No</option><option value="1"<?=($site['surf_fc_req'] == 1 ? ' selected' : '')?>>Yes</option></select></div>
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