<?
define('BASEPATH', true);
include('../../config.php');
?>
<p>
	<label><?=$lang['fbp_url']?></label> <small style="float:right"><?=$lang['fbp_url_desc']?></small><br/>
	<input class="text-max" type="text" name="url" placeholder="https://www.facebook.com/YourPageName/photos/a.PHOTO_ID" />
</p>
<p>
	<label><?=$lang['fbp_title']?></label> <small style="float:right"><?=$lang['fbp_title_desc']?></small><br/>
	<input class="text-max" type="text" value="" name="title" maxlength="30" />
</p>