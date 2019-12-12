<?php
if($db->QueryGetNumRows("SHOW TABLES LIKE 'inst_likes'") < 1){
	executeSql("system/modules/inst_likes/db.sql");
}else{
	@unlink(realpath(dirname(__FILE__)).'/db.sql');
	@unlink(realpath(dirname(__FILE__)).'/db_update.php');
}
?>