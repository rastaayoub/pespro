<?php
	define('BASEPATH', true);
	include('../../config.php');
?>
<p>
	<label><?=$lang['fb_url']?></label> <small style="float:right"><?=$lang['fb_url_desc']?></small><br/>
	<input class="text-max" type="text" placeholder="http://www.facebook.com/PAGE_NAME" name="url" />
</p>
<p>
	<label><?=$lang['fb_title']?></label> <small style="float:right"><?=$lang['fb_title_desc']?></small><br/>
	<input class="text-max" type="text" value="" name="title" maxlength="30" />
</p>