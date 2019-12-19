<?php
if(!mysql_num_rows(mysql_query("SHOW TABLES LIKE 'scf'"))){executeSql("system/modules/soundcloud/sql.sql");}
$db->Query("INSERT IGNORE INTO `site_config` (`config_name`, `config_value`) VALUES ('scf_api', '')");
if(!mysql_query("SELECT title FROM scf")){$db->Query("ALTER TABLE `scf` ADD `title` VARCHAR( 255 ) NOT NULL AFTER `user`");}
if(mysql_query("SELECT s_name FROM scf")){$db->Query("UPDATE `scf` SET `title`=`s_name`");}
if(mysql_query("SELECT s_name FROM scf")){$db->Query("ALTER TABLE `scf` CHANGE `s_name` `url` VARCHAR( 255 ) NOT NULL");}

// Remove content
@unlink(realpath(dirname(__FILE__)).'/sql.sql');
@unlink(realpath(dirname(__FILE__)).'/db_update.php');
?>