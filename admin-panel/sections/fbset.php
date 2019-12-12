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
?>
<section id="content" class="container_12 clearfix"><?=$mesaj?>
	<div class="grid_6">
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Facebook Module Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>App ID</strong></label>
					<div><input type="text" name="set[fb_app_id]" value="<?=$site['fb_app_id']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>App Secret</strong></label>
					<div><input type="text" name="set[fb_app_secret]" value="<?=$site['fb_app_secret']?>" required /></div>
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
				<p>1) Login on your Facebook account, go to <i><a href="https://developers.facebook.com/apps" target="_blank">Developers Center</a></i>, click on <i>Create new App</i>, complete <i>App Name</i> with your site name and click <i>Continue</i></p>
				<p>2) After your app was created, go to Settings -> Basic, complete <i>App Domains</i> with your domain name (like: domain.com) and at <i>Website with Facebook Login</i> complete with your website url (like: http://domain.com/) and click on <i>Save Changes</i></p>
				<p>3) Copy id from <i>App ID</i> and paste id on <i>App ID</i>, here on settings. Do the same thing with <i>App Secret</i>.
				<p>4) Save changes and you're done, your facebook module is now active</p>
				<p>You can leave these fields blank and your site will use old system, but will be better if you enable this new system, because old system is pretty limited.</p>
			</div>
		</form>
	</div>
</section>