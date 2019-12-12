<?php
if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'fb_share'")){
	executeSql("system/modules/fb_share/db.sql");
}else{
	@unlink(realpath(dirname(__FILE__)).'/db.sql');
	@unlink(realpath(dirname(__FILE__)).'/db_update.php');
}
?>