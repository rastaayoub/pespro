<?
define('BASEPATH', true);
include('../../config.php');

if(isset($_POST['data'])){
	$x = $db->EscapeString($_POST['data']);
	$site = $db->QueryFetchArray("SELECT id, confirm FROM `surf` WHERE `id`='".$x."'");
	
	if($site['id'] != "" && $x != "" && $site['confirm'] != 0){
		$db->Query("UPDATE `surf` SET `confirm`='0' WHERE `id`='".$site['id']."'");
		echo '<font size=2><b>'.$lang['surf_02'].'</b></font>';
	}else{
		echo '<font size=2><b>'.$lang['surf_03'].'</b></font>';
	}
}
?>