<?php
include('header.php');
if(!$is_online){
	redirect('index.php');
}
if(isset($_GET['p']) && file_exists('system/modules/'.$_GET['p'].'/module.php')){
	$module = $_GET['p'];
}else{
	redirect('index.php');
}
?>
<div class="content">
<?
$clicks = $db->QueryFetchArray("SELECT SUM(`today_clicks`) AS `total` FROM `user_clicks` WHERE `uid`='".$data['id']."'");

if($data['premium'] == 0 && $site['clicks_limit'] != 0 && $clicks['total'] >= $site['clicks_limit']){
	echo '<a href="vip.php"><div class="msg"><div class="error">'.lang_rep($lang['b_297'], array('-CLICKS-' => $site['clicks_limit'])).'</div></div></a>';
}else{
	include('system/modules/'.$module.'/module.php');
}
?>
</div>
<?include('footer.php');?>