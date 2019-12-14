<?php
$db->Query("INSERT IGNORE INTO `site_config` (`config_name`, `config_value`) VALUES ('fb_app_id', ''),('fb_app_secret', '')");

if(!$db->Query("SELECT fb_id FROM facebook")){
	$db->Query("ALTER TABLE `facebook` ADD `fb_id` VARCHAR( 64 ) NOT NULL DEFAULT '0'");
}

if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'facebook'")){
	executeSql("system/modules/facebook/db.sql");
}else{
	@unlink(realpath(dirname(__FILE__)).'/db.sql');
	@unlink(realpath(dirname(__FILE__)).'/db_update.php');
}
?>