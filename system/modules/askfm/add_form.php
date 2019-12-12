<?php
	define('BASEPATH', true);
	require_once('../../config.php');
?>
<p>
	<label><?=$lang['askfmlike_url']?></label> <small style="float:right"><?=$lang['askfmlike_url_desc']?></small><br/>
	<input class="text-max" type="text" placeholder="http://ask.fm/YOURUSERNAME/answers/ANSWER-ID" name="url" />
</p>