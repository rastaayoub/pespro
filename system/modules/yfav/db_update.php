<?php
$db->Query("INSERT IGNORE INTO `site_config` (`config_name`, `config_value`) VALUES ('yt_api', '')");
if(!$db->QueryGetNumRows("SHOW KEYS FROM `yfaved` WHERE Key_name = 'site_id'")){$db->Query("ALTER TABLE `yfaved` ADD INDEX (`site_id`)");}
if($db->QueryGetNumRows("SHOW KEYS FROM `yfaved` WHERE Key_name = 'user_id'")){$db->Query("ALTER TABLE `yfaved` DROP INDEX `user_id`");}
if(!$db->QueryGetNumRows("SHOW KEYS FROM `yfaved` WHERE Key_name = 'unique_id'")){$db->Query("ALTER TABLE `yfaved` ADD UNIQUE `unique_id` (`user_id`, `site_id`) COMMENT '';");}

if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'yfav'")){
	executeSql("system/modules/yfav/sql.sql");
}else{
	@unlink(realpath(dirname(__FILE__)).'/sql.sql');
	@unlink(realpath(dirname(__FILE__)).'/db_update.php');
}
?>