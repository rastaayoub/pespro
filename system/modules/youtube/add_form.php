<?
define('BASEPATH', true);
include('../../config.php');
?>
<p>
	<label><?=$lang['yt_url']?></label> <small style="float:right"><?=$lang['yt_url_desc']?></small><br/>
	<input class="text-max" type="text" value="http://" name="url" />
</p>
<p>
	<label><?=$lang['yt_title']?></label> <small style="float:right"><?=$lang['yt_title_desc']?></small><br/>
	<input class="text-max" type="text" value="" name="title" maxlength="30" />
</p>