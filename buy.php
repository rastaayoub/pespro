<?php
include('header.php');
if(!$is_online){
	redirect('index.php');
}
if(isset($_GET['history'])){
?>
<div class="content"><h2 class="title"><?=$lang['b_247']?> <span style="float:right"><a href="buy.php"><?=$lang['b_07']?></a></span></h2>
	<table class="table">
		<thead>
			<tr>
				<td width="20">#</td>
				<td><?=$lang['b_248']?></td>
				<td><?=$lang['b_249']?></td>
				<td><?=$lang['b_106']?></td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td>#</td>
				<td><?=$lang['b_248']?></td>
				<td><?=$lang['b_249']?></td>
				<td><?=$lang['b_106']?></td>
			</tr>
		</tfoot>
		<tbody>
<?php
	$tras = $db->QueryFetchArrayAll("SELECT * FROM `user_transactions` WHERE `user_id`='".$data['id']."' AND `type`='0' ORDER BY `date` DESC");

	if(!$tras){
		echo '<tr><td colspan="5">'.$lang['b_250'].'</td></tr>';
	}
	foreach($tras as $tra){
?>	
			<tr>
				<td><?=$tra['id']?></td>
				<td><?=$tra['value']?> <?=($tra['type'] == 0 ? $lang['b_42'] : $lang['b_246'])?></td>
				<td><?=get_currency_symbol($site['currency_code']).$tra['cash']?></td>
				<td><?=date('d M Y - H:i',$tra['date'])?></td>
			</tr>
<?}?>
		</tbody>
	</table>
</div>
<?
}else{
	$msg = '';
	if(isset($_POST['submit']) && isset($_POST['pack_id'])){
		$pid = $db->EscapeString($_POST['pack_id']);
		$pack = $db->QueryFetchArray("SELECT id,coins,price FROM `c_pack` WHERE `id`='".$pid."'");
		
		$price = ($site['c_discount'] > 0 ? number_format($pack['price'] * ((100-$site['c_discount']) / 100), 2) : $pack['price']);
		if($pack['id'] < 1){
			$msg = '<div class="msg"><div class="error">'.$lang['b_262'].'</div></div>';
		}elseif($data['account_balance'] < $price){
			$msg = '<div class="msg"><div class="error">'.$lang['b_263'].' <a href="bank.php"><b>'.$lang['b_256'].'...</b></a></div></div>';
		}else{
			$db->Query("UPDATE `users` SET `account_balance`=`account_balance`-'".$price."', `coins`=`coins`+'".$pack['coins']."' WHERE `id`='".$data['id']."'");
			$db->Query("INSERT INTO `user_transactions` (`user_id`,`type`,`value`,`cash`,`date`)VALUES('".$data['id']."','0','".$pack['coins']."','".$pack['price']."','".time()."')");

			if($site['paysys'] == 1 && $data['ref'] > 0 && $data['ref_paid'] == 1 && $price > 0){
				$commission = number_format(($price/100) * $site['ref_sale'], 2);
				affiliate_commission($data['ref'], $data['id'], $commission, 'coins_purchase');
			}
			
			$msg = '<div class="msg"><div class="success">'.lang_rep($lang['b_264'], array('-NUM-' => $pack['coins'].' '.$lang['b_156'])).'</div></div>';
		}
	}
	elseif(isset($_POST['user_submit']) && isset($_POST['user_pack_id']))
	{
		$pid = $db->EscapeString($_POST['user_pack_id']);
		$pack = $db->QueryFetchArray("SELECT * FROM `sell_coins` WHERE `id`='".$pid."' AND `sold`='0' AND `seller_id`!='".$data['id']."'");

		if($pack['id'] < 1){
			$msg = '<div class="msg"><div class="error">'.$lang['b_262'].'</div></div>';
		}elseif($data['account_balance'] < $pack['price']){
			$msg = '<div class="msg"><div class="error">'.$lang['b_263'].' <a href="bank.php"><b>'.$lang['b_256'].'...</b></a></div></div>';
		}else{
			$user_fees = (($pack['price']/100) * $site['sc_fees']);
			$user_revenue = ($pack['price'] - $user_fees);
			
			$db->Query("UPDATE `users` SET `account_balance`=`account_balance`-'".$pack['price']."', `coins`=`coins`+'".$pack['coins']."' WHERE `id`='".$data['id']."'");
			$db->Query("UPDATE `users` SET `account_balance`=`account_balance`+'".$user_revenue."' WHERE `id`='".$pack['seller_id']."'");
			$db->Query("UPDATE `sell_coins` SET `buyer_id`='".$data['id']."', `fees`='".$user_fees."', `sold`='1', `sold_time`='".time()."' WHERE `id`='".$pack['id']."'");
			$db->Query("INSERT INTO `user_transactions` (`user_id`,`type`,`value`,`cash`,`date`)VALUES('".$data['id']."','0','".$pack['coins']."','".$pack['price']."','".time()."')");

			if($site['paysys'] == 1 && $data['ref'] > 0 && $data['ref_paid'] == 1 && $pack['price'] > 0){
				$commission = number_format(($pack['price']/100) * $site['ref_sale'], 2);
				affiliate_commission($data['ref'], $data['id'], $commission, 'coins_purchase');
			}
			
			$msg = '<div class="msg"><div class="success">'.lang_rep($lang['b_264'], array('-NUM-' => $pack['coins'].' '.$lang['b_156'])).'</div></div>';
		}
	}
?>
<div class="content"><h2 class="title"><?=$lang['b_07']?> <span style="float:right"><a href="buy.php?history"><?=$lang['b_247']?></a></span></h2><?=$msg?>
<?php
	$packs = $db->QueryFetchArrayAll("SELECT id,coins,price FROM `c_pack` ORDER BY `id` ASC");
	if(!$packs){
		echo '<div class="msg"><div class="error">'.$lang['b_43'].'</div></div>';
	}else{
		foreach($packs as $pack){
			$price = ($site['c_discount'] > 0 ? number_format($pack['price'] * ((100-$site['c_discount']) / 100), 2) : $pack['price']);
?>
	<div class="purchase">
		<div class="purchase-hdr">
			<span style="font-size:16px"><?=number_format($pack['coins']).' '.$lang['b_42'].' = '.($site['currency_code'] == '' ? get_currency_symbol('USD') : get_currency_symbol($site['currency_code'])).$price?></span><br /> 
			<span style="font-size:12px;color:#efefef">1 <?=$lang['b_373']?> = <?=($site['c_discount'] > 0 ? '<s>'.get_currency_symbol($site['currency_code']).number_format($pack['price']/$pack['coins'], 4).'</s> -> '.get_currency_symbol($site['currency_code']).number_format($price/$pack['coins'], 4) : get_currency_symbol($site['currency_code']).number_format($price/$pack['coins'], 4))?> </span>
		</div>
		<form method="POST">
			<input type="hidden" name="pack_id" value="<?=$pack['id']?>" />
			<input type="submit" name="submit" class="gbut" value="<?=$lang['b_199']?>" />
		</form>
	</div>
<?php
		}
	}

	$count = $db->QueryGetNumRows("SELECT * FROM sell_coins WHERE sold = '0' AND `seller_id`!='".$data['id']."'");
	$bpp = 12;
	$page = intval($_GET['p']);
	$begin = ($page >= 0 ? ($page*$bpp) : 0);
	
	$packs = $db->QueryFetchArrayAll("SELECT a.*, b.login FROM sell_coins a LEFT JOIN users b ON b.id = a.seller_id WHERE a.sold = '0' AND a.seller_id != '".$data['id']."' ORDER BY a.coin_value ASC, a.price ASC LIMIT ".$begin.", ".$bpp."");
	if($packs){
		echo '<div style="clear:both"></div><br /><h2 class="title">'.$lang['b_374'].'</h2>';
		
		foreach($packs as $pack){
?>
	<div id="user_pack">
		<div class="hdr">
			<span style="font-size:16px"><?=number_format($pack['coins']).' '.$lang['b_42'].' = '.get_currency_symbol($site['currency_code']).$pack['price']?></span><br /> 
			<span style="font-size:12px;color:#efefef">1 <?=$lang['b_373']?> = <?=get_currency_symbol($site['currency_code']).number_format($pack['price']/$pack['coins'], 4)?> </span>
		</div>
		<form method="POST">
			<span class="seller"><b><?=$lang['b_371']?>:</b> <?=$pack['login']?></span>
			<input type="hidden" name="user_pack_id" value="<?=$pack['id']?>" />
			<input type="submit" name="user_submit" class="gbut" value="<?=$lang['b_199']?>" />
		</form>
	</div>
	<?php
		}
		
		if(ceil($count/$bpp) > 1)
		{
	?>
	<div class="infobox">
		<div style="float:left;"><?=lang_rep($lang['b_372'], array('-NUM-' => $count))?></div>
		<div style="float:right;">
		<?php
			if($count >= 0) {
				$left = '<a href="?p='.($begin/$bpp-1).'"><img src="theme/'.$site['theme'].'/images/black_arrow_left.png" /></a>';
				if($begin/$bpp == 0) {
					$left = '<img src="theme/'.$site['theme'].'/images/black_arrow_left.png" />';
				}
				
				$right = '<a href="?p='.($begin/$bpp+1).'"><img src="theme/'.$site['theme'].'/images/black_arrow_right.png" /></a>';
				if($begin+$bpp >= $count) {
					$right = '<img src="theme/'.$site['theme'].'/images/black_arrow_right.png" />';
				}
				
				echo $left.'&nbsp;&nbsp; '.$begin.' - '.($begin+$bpp > $count ? $count : $begin+$bpp).' &nbsp;&nbsp;'.$right;
			}
		?>
		</div>
		<div style="display:block;clear:both;"></div>
	</div>
	<?php } ?>
<?php } ?>
</div>
<?php
	}

	include('footer.php');
?>