<?php
	define('BASEPATH', true);
	include('../../config.php');
?>
<p>
	<label><?=$lang['soundcloud_likes_url']?></label> <small style="float:right"><?=$lang['soundcloud_likes_url_desc']?></small><br/>
	<input class="text-max" type="text" name="url" placeholder="https://soundcloud.com/USERNAME/TRACK-TITLE" />
</p>
<p>
	<label><?=$lang['soundcloud_likes_title']?></label> <small style="float:right"><?=$lang['soundcloud_likes_title_desc']?></small><br/>
	<input class="text-max" type="text" name="title" placeholder="Track Name" />
</p>