<?php
if(!$db->QueryGetNumRows("SHOW KEYS FROM `stumbled` WHERE Key_name = 'site_id'")){$db->Query("ALTER TABLE `stumbled` ADD INDEX (`site_id`)");}
if($db->QueryGetNumRows("SHOW KEYS FROM `stumbled` WHERE Key_name = 'user_id'")){$db->Query("ALTER TABLE `stumbled` DROP INDEX `user_id`");}
if(!$db->QueryGetNumRows("SHOW KEYS FROM `stumbled` WHERE Key_name = 'unique_id'")){$db->Query("ALTER TABLE `stumbled` ADD UNIQUE `unique_id` (`user_id`, `site_id`) COMMENT '';");}

// Remove content
@unlink(realpath(dirname(__FILE__)).'/db_update.php');
?>