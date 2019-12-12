<?
define('BASEPATH', true);
include('../../config.php');
?>
<p>
	<label><?=$lang['retwt_url']?></label> <small style="float:right"><?=$lang['retwt_url_desc']?></small><br/>
	<input class="text-max" type="text" value="http://" name="url" />
</p>
<p>
	<label><?=$lang['retwt_title']?></label> <small style="float:right"><?=$lang['retwt_title_desc']?></small><br/>
	<input class="text-max" type="text" value="" name="title" maxlength="140" />
</p>