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
				<h2>Twitter Module Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Consumer key</strong></label>
					<div><input type="text" name="set[twitter_consumer_key]" value="<?=$site['twitter_consumer_key']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Consumer secret</strong></label>
					<div><input type="text" name="set[twitter_consumer_secret]" value="<?=$site['twitter_consumer_secret']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Access token</strong></label>
					<div><input type="text" name="set[twitter_token]" value="<?=$site['twitter_token']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Access token secret</strong></label>
					<div><input type="text" name="set[twitter_token_secret]" value="<?=$site['twitter_token_secret']?>" required="required" /></div>
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
				<p>1) Login on your Twitter account, go to <i><a href="https://dev.twitter.com/apps" target="_blank">Developers Center</a></i>, click on <i>Create a new Application</i>, complete <i>Name</i> with your site name, <i>Description</i> with what you want, <i>Website URL</i> with your site url, accept TOS, complete Captcha and click <i>Create your Twitter Application</i></p>
				<p>2) After your app was created, copy <i>Consumer key</i> and <i>Consumer secret</i> from Twitter and paste them here (on inputs with the same name)</p>
				<p>3) Click on <i>Create my Access Token</i></p>
				<p>4) Copy <i>Access token</i> and <i>Access token secret</i> from Twitter and paste them here (on inputs with the same name). Save settings and you're done!</p>
			</div>
		</form>
	</div>
</section>