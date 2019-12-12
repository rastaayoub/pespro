<?php
if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'reverbnation'")){executeSql("system/modules/reverbnation/sql.sql");}
if(!$db->QueryGetNumRows("SHOW KEYS FROM `reverbnation_done` WHERE Key_name = 'site_id'")){$db->Query("ALTER TABLE `reverbnation_done` ADD INDEX (`site_id`)");}
if($db->QueryGetNumRows("SHOW KEYS FROM `reverbnation_done` WHERE Key_name = 'user_id'")){$db->Query("ALTER TABLE `reverbnation_done` DROP INDEX `user_id`");}
if(!$db->QueryGetNumRows("SHOW KEYS FROM `reverbnation_done` WHERE Key_name = 'unique_id'")){$db->Query("ALTER TABLE `reverbnation_done` ADD UNIQUE `unique_id` (`user_id`, `site_id`) COMMENT '';");}


// Remove content
@unlink(realpath(dirname(__FILE__)).'/sql.sql');
@unlink(realpath(dirname(__FILE__)).'/db_update.php');
?>