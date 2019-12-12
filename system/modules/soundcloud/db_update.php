<?php
if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'scf'")){executeSql("system/modules/soundcloud/sql.sql");}
$db->Query("INSERT IGNORE INTO `site_config` (`config_name`, `config_value`) VALUES ('scf_api', '')");
if(!$db->Query("SELECT title FROM scf")){$db->Query("ALTER TABLE `scf` ADD `title` VARCHAR( 255 ) NOT NULL AFTER `user`");}
if($db->Query("SELECT s_name FROM scf")){$db->Query("UPDATE `scf` SET `title`=`s_name`");}
if($db->Query("SELECT s_name FROM scf")){$db->Query("ALTER TABLE `scf` CHANGE `s_name` `url` VARCHAR( 255 ) NOT NULL");}
if(!$db->QueryGetNumRows("SHOW KEYS FROM `scf_done` WHERE Key_name = 'site_id'")){$db->Query("ALTER TABLE `scf_done` ADD INDEX (`site_id`)");}
if($db->QueryGetNumRows("SHOW KEYS FROM `scf_done` WHERE Key_name = 'user_id'")){$db->Query("ALTER TABLE `scf_done` DROP INDEX `user_id`");}
if(!$db->QueryGetNumRows("SHOW KEYS FROM `scf_done` WHERE Key_name = 'unique_id'")){$db->Query("ALTER TABLE `scf_done` ADD UNIQUE `unique_id` (`user_id`, `site_id`) COMMENT '';");}


// Remove content
@unlink(realpath(dirname(__FILE__)).'/sql.sql');
@unlink(realpath(dirname(__FILE__)).'/db_update.php');
?>