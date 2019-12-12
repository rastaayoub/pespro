<?php
if($db->QueryGetNumRows("SHOW TABLES LIKE 'askfm_like'") < 1) {
	executeSql("system/modules/askfm/db.sql");
}else{
	@unlink(realpath(dirname(__FILE__)).'/db.sql');
	@unlink(realpath(dirname(__FILE__)).'/db_update.php');
}
?>