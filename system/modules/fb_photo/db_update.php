<?php
if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'fb_photo'")){
	executeSql("system/modules/fb_photo/db.sql");
}else{
	@unlink(realpath(dirname(__FILE__)).'/db.sql');
	@unlink(realpath(dirname(__FILE__)).'/db_update.php');
}
?>