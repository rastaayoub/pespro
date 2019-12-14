<?
define('BASEPATH', true);
include('../../config.php');
?>
<p>
	<label><?=$lang['fbe_url']?></label> <small style="float:right"><?=$lang['fbe_url_desc']?></small><br/>
	<input class="text-max" type="text" placeholder="https://www.facebook.com/events/EVENT_ID" name="url" />
</p>
<p>
	<label><?=$lang['fbe_title']?></label> <small style="float:right"><?=$lang['fbe_title_desc']?></small><br/>
	<input class="text-max" type="text" value="" name="title" maxlength="30" />
</p>