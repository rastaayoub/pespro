<?php
include('header.php');
if(!isset($_SESSION['PES_Banned']) || !isset($_GET['id']) || $_SESSION['PES_Banned'] != $_GET['id']){
	redirect('index.php');
}

$uid = $db->EscapeString($_GET['id']);
$ban = $db->QueryFetchArray("SELECT id,reason FROM `ban_reasons` WHERE `user`='".$uid."' LIMIT 1");
?>
<div class="content t-left"><h2 class="title">Blocked</h2>
	<p class="infobox" style="color:red"><b>Your account was banned for the following reason:</b></p>
	<p class="infobox"><?=nl2br($ban['reason'])?></p>
</div>
<?include('footer.php');?>