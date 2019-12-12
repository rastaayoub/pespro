<?php
if($db->QueryGetNumRows("SHOW TABLES LIKE 'instagram'") < 1){
	executeSql("system/modules/instagram/db.sql");
}else{
	@unlink(realpath(dirname(__FILE__)).'/db.sql');
	@unlink(realpath(dirname(__FILE__)).'/db_update.php');
}
?>