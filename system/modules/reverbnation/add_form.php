<?
define('BASEPATH', true);
include('../../config.php');
?>
<p>
	<label><?=$lang['reverbnation_url']?></label> <small style="float:right"><?=$lang['reverbnation_url_desc']?></small><br/>
	<input class="text-max" type="text" value="http://" name="url" />
</p>
<p>
	<label><?=$lang['reverbnation_title']?></label> <small style="float:right"><?=$lang['reverbnation_title_desc']?></small><br/>
	<input class="text-max" type="text" value="" name="title" maxlength="30" />
</p>