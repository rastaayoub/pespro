<?
define('BASEPATH', true);
include('../../config.php');
?>
<p>
	<label><?=$lang['surf_url']?></label> <small style="float:right"><?=$lang['surf_url_desc']?></small><br/>
	<input class="text-max" type="text" value="http://" name="url" />
</p>
<p>
	<label><?=$lang['surf_title']?></label> <small style="float:right"><?=$lang['surf_title_desc']?></small><br/>
	<input class="text-max" type="text" value="" name="title" maxlength="30" />
</p>