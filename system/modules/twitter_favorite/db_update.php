<?php
if(!$db->QueryGetNumRows("SHOW KEYS FROM `twitter_favorited` WHERE Key_name = 'site_id'")){$db->Query("ALTER TABLE `twitter_favorited` ADD INDEX (`site_id`)");}

if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'twitter_favorite'")){
	executeSql("system/modules/twitter_favorite/sql.sql");
}else{
	@unlink(realpath(dirname(__FILE__)).'/sql.sql');
	@unlink(realpath(dirname(__FILE__)).'/db_update.php');
}
?>