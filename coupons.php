<?php
include('header.php');
if(!$is_online){
	redirect('index.php');
}

$msg = '<div class="msg"><div class="info">'.$lang['b_62'].'</div></div>';
if(isset($_POST['submit'])) {
	$code = $db->EscapeString($_POST['code']);
	
	$ext = $db->QueryFetchArray("SELECT id,coins,uses,type,exchanges FROM `coupons` WHERE `code`='".$code."' AND (`uses`>'0' OR `uses`='u') LIMIT 1");
	$total_clicks = $db->QueryFetchArray("SELECT SUM(`total_clicks`) AS `clicks` FROM `user_clicks` WHERE `uid`='".$data['id']."'");
	$used = $db->QueryGetNumRows("SELECT id FROM `used_coupons` WHERE `user_id`='".$data['id']."' AND `coupon_id`='".$ext['id']."' LIMIT 1");
	if(empty($ext['id']) || $used > 0){
		$msg = '<div class="msg"><div class="error">'.$lang['b_61'].'</div></div>';
	}elseif($ext['exchanges'] != 0 && $ext['exchanges'] > $total_clicks['clicks']){
		$msg = '<div class="msg"><div class="error">'.lang_rep($lang['b_337'], array('-NUM-' => number_format($ext['exchanges']))).'</div></div>';
	}else{
		if($ext['type'] == 1){
			$premium = ($data['premium'] == 0 ? (time()+(86400*$ext['coins'])) : ((86400*$ext['coins'])+$data['premium']));
			$db_add = "`premium`='".$premium."'";
		}else{
			$db_add = "`coins`=`coins`+'".$ext['coins']."'";
		}
		
		$db->Query("UPDATE `users` SET ".$db_add." WHERE `id`='".$data['id']."'");
		$db->Query("UPDATE `coupons` SET ".($ext['uses'] != 'u' ? "`uses`=`uses`-'1', " : '')."`used`=`used`+'1' WHERE `code`='".$code."'");
		$db->Query("INSERT INTO `used_coupons` (user_id, coupon_id) VALUES('".$data['id']."','".$ext['id']."')");
		$msg = '<div class="msg"><div class="msg success">'.lang_rep(($ext['type'] == 1 ? $lang['b_270'] : $lang['b_60']), array('-NUM-' => $ext['coins'])).'</div></div>';
	}
}?>
<div class="content">
<h2 class="title"><?=$lang['b_10']?></h2>
<form method="post">
	<input class="l_form" onfocus="if(this.value == '<?=$lang['b_59']?>') { this.value = ''; }" onblur="if(this.value=='') { this.value = this.defaultValue }" value="<?=$lang['b_59']?>" name="code" type="text">
	<input type="submit" class="gbut" name="submit" value="<?=$lang['b_58']?>" />
</form><?=$msg?><br />
<h2 class="title"><?=$lang['b_365']?></h2>
<table class="table">
	<thead>
		<tr>
			<td width="35"><?=$lang['b_154']?></td>
			<td><?=$lang['b_59']?></td>
			<td><?=$lang['b_327']?></td>
		</tr>
	</thead>
	<tfoot>
		<tr><td colspan="4"><center><?=$lang['b_153']?></center></td></tr>
	</tfoot>
	<tbody>
<?
$coupons = $db->QueryFetchArrayAll("SELECT a.id,b.code,b.coins,b.type FROM used_coupons a LEFT JOIN coupons b ON b.id = a.coupon_id WHERE a.user_id='".$data['id']."' ORDER BY a.id DESC LIMIT 5");
if(!$coupons){
	echo '<tr><td colspan="4"><center>'.$lang['b_250'].'</center></td></tr>';
}

foreach($coupons as $coupon){
?>	
	<tr>
		<td><?=$coupon['id']?></td>
		<td><?=$coupon['code']?></td>
		<td><?=$coupon['coins'].' '.($coupon['type'] == 1 ? $lang['b_246'] : $lang['b_156'])?></td>
	</tr>
<?}?>
	</tbody>
</table>
</div>	
<?include('footer.php');?>