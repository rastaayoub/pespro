<?php
if(!$db->QueryGetNumRows("SHOW TABLES LIKE 'fb_event'")){
	executeSql("system/modules/fb_events/db.sql");
}else{
	@unlink(realpath(dirname(__FILE__)).'/db.sql');
	@unlink(realpath(dirname(__FILE__)).'/db_update.php');
}
?>