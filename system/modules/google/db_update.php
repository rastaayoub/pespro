<?php
if(!$db->QueryGetNumRows("SHOW KEYS FROM `plused` WHERE Key_name = 'site_id'")){$db->Query("ALTER TABLE `plused` ADD INDEX (`site_id`)");}
if($db->QueryGetNumRows("SHOW KEYS FROM `plused` WHERE Key_name = 'user_id'")){$db->Query("ALTER TABLE `plused` DROP INDEX `user_id`");}
if(!$db->QueryGetNumRows("SHOW KEYS FROM `plused` WHERE Key_name = 'unique_id'")){$db->Query("ALTER TABLE `plused` ADD UNIQUE `unique_id` (`user_id`, `site_id`) COMMENT '';");}

// Remove content
@unlink(realpath(dirname(__FILE__)).'/db_update.php');
?>