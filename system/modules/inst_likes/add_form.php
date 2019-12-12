<?
define('BASEPATH', true);
include('../../config.php');
?>
<p>
	<label><?=$lang['inst_likes_url']?></label> <small style="float:right"><?=$lang['inst_likes_url_desc']?></small><br/>
	<input class="text-max" type="text" value="http://" name="url" />
</p>
<p>
	<label><?=$lang['inst_likes_title']?></label> <small style="float:right"><?=$lang['inst_likes_title_desc']?></small><br/>
	<input class="text-max" type="text" value="" name="title" maxlength="30" />
</p>