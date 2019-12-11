<?php 
define('BASEPATH', true);
include('system/config.php');

$mesaj = '';
if(isset($_GET['code']) && $_GET['code'] != 0 && is_numeric($_GET['code'])){
	$code = $db->EscapeString($_GET['code']);
	if($db->QueryGetNumRows("SELECT id FROM `users` WHERE `activate`='".$code."' LIMIT 1") > 0){
		if($site['refsys'] == 1 && $site['aff_click_req'] == 0){
			$ref = $db->QueryFetchArray("SELECT id,ref FROM `users` WHERE `activate`='".$code."'");
			if($ref['ref'] > 0){
				$db->Query("UPDATE `users` SET `coins`=`coins`+'".$site['ref_coins']."' WHERE `id`='".$ref['ref']."'");
				$db->Query("UPDATE `users` SET `ref_paid`='1' WHERE `activate`='".$code."'");
				
				if($site['paysys'] == 1 && $site['ref_cash'] > 0){
					affiliate_commission($ref['ref'], $ref['id'], $site['ref_cash'], 'referral_activity');
				}
			}
		}

		$db->Query("UPDATE `users` SET `activate`='0' WHERE `activate`='".$code."'");
		$mesaj = '<center><b style="color:green">'.$lang['b_23'].'</b></center>';
	}else{
		$mesaj = '<center><b style="color:red">'.$lang['b_24'].'</b></center>';
	}
}else{
	$mesaj = '<center><b style="color:red">'.$lang['b_24'].'</b></center>';
}
?>
<html><title><?=$site['site_name']?></title>
<head>
<meta http-equiv="refresh" content="2; URL=index.php" />
</head>
<body style="background:url(theme/<?=$site['theme']?>/images/bg.png);font-family:calibri;">
<div style="background:#e0e0e0;width:50%;margin:75px auto;padding:25px;border:1px #ffffff solid;border-radius:3px;">
	<?=$mesaj?>
</div>
</body>
</html>