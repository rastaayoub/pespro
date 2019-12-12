<?php
define('BASEPATH', true);
include('config.php');
if(!$is_online){exit;}

if(isset($_POST['id']) && isset($_POST['url']) && isset($_POST['module']) && isset($_POST['reason']) && !empty($data['id'])) {
	$pid = $db->EscapeString($_POST['id']);
	$url = $db->EscapeString(base64_decode($_POST['url']));
	$mod = $db->EscapeString($_POST['module']);
	$reason = $db->EscapeString($_POST['reason']);

	$page = $db->QueryFetchArray("SELECT id,user FROM `".$mod."` WHERE `id`='".$pid."'");
	if(!empty($page['id'])){
		if($db->QueryGetNumRows("SELECT id FROM `reports` WHERE (`page_id`='".$pid."' AND `module`='".$mod."') AND `status`='0' LIMIT 1") > 0){
			$db->Query("UPDATE `reports` SET `count`=`count`+'1' WHERE (`page_id`='".$pid."' AND `module`='".$mod."') AND `status`='0'");
			echo '1';
		}elseif($data['admin'] != 1 && $site['report_limit'] > 0 && $db->QueryGetNumRows("SELECT id FROM `reports` WHERE `reported_by`='".$data['id']."' AND `status`='0'") >= $site['report_limit']){
			echo '2';
		}else{
			$db->Query("INSERT INTO `reports` (`page_id`,`page_url`,`owner_id`,`reported_by`,`reason`,`module`,`timestamp`)VALUES('".$page['id']."','".$url."','".$page['user']."','".$data['id']."','".$reason."','".$mod."','".time()."')");
			echo '1';
		}
	}else{
		echo '0';
	}
}else{
	echo '0';
}
?>