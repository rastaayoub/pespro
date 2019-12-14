<?php
if(!mysql_num_rows(mysql_query("SHOW TABLES LIKE 'yfav'"))){executeSql("system/modules/yfav/sql.sql");}
$db->Query("INSERT IGNORE INTO `site_config` (`config_name`, `config_value`) VALUES ('yt_api', '')");

// Remove content
@unlink(realpath(dirname(__FILE__)).'/sql.sql');
@unlink(realpath(dirname(__FILE__)).'/db_update.php');
?>