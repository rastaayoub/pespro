<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$mesaj = '';
if(isset($_POST['submit'])){
	$yt_api = $db->EscapeString($_POST['yt_api']);

	if(!empty($yt_api)){
		$db->Query("UPDATE `site_config` SET `config_value`='".$yt_api."' WHERE `config_name`='yt_api'");
		$mesaj = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Settings successfully changed</div>';
	}else{
		$mesaj = '<div class="alert error"><span class="icon"></span><strong>Error!</strong> Please complete all fields</div>';
	}
}
?>
<section id="content" class="container_12 clearfix"><?=$mesaj?>
	<div class="grid_6">
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Youtube Module Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Youtube API Key</strong></label>
					<div><input type="text" name="yt_api" value="<?=(isset($_POST['yt_api']) ? $_POST['yt_api'] : $site['yt_api'])?>" required="required" /></div>
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
				<h2>Info</h2>
			</div>
			<div class="content">
				<p>1) Login into your Youtube account, go to <i><a href="https://code.google.com/apis/console/" target="_blank">Google APIs Console</a></i> and if appear something like "Start using the Google APIs console", click on <i>Create project...</i> else jump to step 2</p>
				<p>2) In the left menu click on <i>Services</i>, go to bottom of the page and enable <i>YouTube Data API v3</i></p>
				<p>3) In the left menu click on <i>API Access</i>, copy <i>API key</i> and paste it on <i>Youtube API Key</i>, here on settings.
				<p>4) Save changes and you're done, your Youtube module is now active</p>
			</div>
		</form>
	</div>
</section>