<?php
if($db->QueryGetNumRows("SHOW TABLES LIKE 'soundcloud_likes'") < 1) {
	executeSql("system/modules/soundcloud_likes/db.sql");
}else{
	@unlink(realpath(dirname(__FILE__)).'/db.sql');
	@unlink(realpath(dirname(__FILE__)).'/db_update.php');
}
?>