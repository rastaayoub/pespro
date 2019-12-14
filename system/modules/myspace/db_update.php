<?php
if(!mysql_num_rows(mysql_query("SHOW TABLES LIKE 'myspace'"))){executeSql("system/modules/myspace/sql.sql");}

// Remove content
@unlink(realpath(dirname(__FILE__)).'/sql.sql');
@unlink(realpath(dirname(__FILE__)).'/db_update.php');
?>