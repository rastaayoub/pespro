<?php
if(!$db->QueryGetNumRows("SHOW KEYS FROM `twitter_retweeted` WHERE Key_name = 'site_id'")){$db->Query("ALTER TABLE `twitter_retweeted` ADD INDEX (`site_id`)");}

if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'twitter_retweet'")){
	executeSql("system/modules/twitter_retweet/sql.sql");
}else{
	@unlink(realpath(dirname(__FILE__)).'/sql.sql');
	@unlink(realpath(dirname(__FILE__)).'/db_update.php');
}
?>