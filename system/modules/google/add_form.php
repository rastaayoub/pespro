<?
define('BASEPATH', true);
include('../../config.php');
?>
<p>
	<label><?=$lang['gp_url']?></label> <small style="float:right"><?=$lang['gp_url_desc']?></small><br/>
	<input class="text-max" type="text" value="http://" name="url" />
</p>
<p>
	<label><?=$lang['gp_title']?></label> <small style="float:right"><?=$lang['gp_title_desc']?></small><br/>
	<input class="text-max" type="text" value="" name="title" maxlength="30" />
</p>