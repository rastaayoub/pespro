<?
define('BASEPATH', true);
include('../../config.php');
?>
<p>
	<label><?=$lang['twitter_url']?></label> <small style="float:right"><?=$lang['twitter_url_desc']?></small><br/>
	<input class="text-max" type="text" value="" name="url" />
</p>
<p>
	<label><?=$lang['twitter_title']?></label> <small style="float:right"><?=$lang['twitter_title_desc']?></small><br/>
	<input class="text-max" type="text" value="" name="title" maxlength="140" />
</p>