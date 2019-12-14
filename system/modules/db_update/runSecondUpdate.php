<?php
// Update to 2.0.0
$tables = hook_filter('module_tables','');
$tables = explode('(||)', $tables);

foreach($tables as $table){
	$z = explode('---', $table);
	if(!empty($z[0])){
		if(!$db->Query("SELECT today_clicks FROM ".$z[0]."")){$db->Query("ALTER TABLE `".$z[0]."` ADD `today_clicks` INT( 255 ) NOT NULL DEFAULT '0' AFTER `clicks` , ADD `max_clicks` INT( 255 ) NOT NULL DEFAULT '0' AFTER `today_clicks` , ADD `daily_clicks` INT( 255 ) NOT NULL DEFAULT '0' AFTER `max_clicks`");}
	}
}

// Remove files (if files are not deleted automatically, please delete "system/db_update" folder)
if($db->Connect()){
	eval(base64_decode('QHVubGluayhyZWFscGF0aChkaXJuYW1lKF9fRklMRV9fKSkuJy9ydW5TZWNvbmRVcGRhdGUucGhwJyk7'));
}
?>