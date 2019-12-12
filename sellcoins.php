<?php
include('header.php');
if(!$is_online){
	redirect('index.php');
}

$message = '';
if(isset($_GET['del']) && is_numeric($_GET['del']))
{
	$pack_id = $db->EscapeString($_GET['del']);
	$pack = $db->QueryFetchArray("SELECT * FROM `sell_coins` WHERE (`id`='".$pack_id."' AND `seller_id`='".$data['id']."') AND `sold`='0' LIMIT 1");
	
	if(!empty($pack['id']))
	{
		$db->Query("UPDATE `users` SET `coins`=`coins`+'".$pack['coins']."' WHERE `id`='".$data['id']."'");
		$db->Query("DELETE FROM `sell_coins` WHERE `id`='".$pack['id']."'");
		
		$message = '<div class="msg"><div class="success">'.$lang['b_384'].'</div></div>';
	}
}

if(isset($_POST['add']))
{
	$coins = $db->EscapeString($_POST['coins']);
	$price = $db->EscapeString($_POST['price']);
	$coin_value = number_format($price/$coins, 4);
	
	if(!is_numeric($coins) || $data['coins'] < $coins)
	{
		$message = '<div class="msg"><div class="error">'.$lang['b_146'].'</div></div>';
	}
	elseif($coins < $site['sc_min_coins'])
	{
		$message = '<div class="msg"><div class="error">'.lang_rep($lang['b_386'], array('-COINS-' => $site['sc_min_coins'])).'</div></div>';
	}
	elseif(!is_numeric($price) || $price < $site['sc_min_price'])
	{
		$message = '<div class="msg"><div class="error">'.lang_rep($lang['b_387'], array('-PRICE-' => $site['sc_min_price'])).'</div></div>';
	}
	elseif($coin_value < $site['minimum_sc_value'])
	{
		$message = '<div class="msg"><div class="error">'.lang_rep($lang['b_388'], array('-VALUE-' => $site['minimum_sc_value'])).'</div></div>';
	}
	else
	{
		$packs = $db->QueryGetNumRows("SELECT * FROM `sell_coins` WHERE `sold`='0' AND `seller_id`='".$data['id']."'");

		if($data['premium'] == 0 && $packs >= $site['free_sc_limit'])
		{
			$message = '<div class="msg"><div class="error">'.$lang['b_389'].'</div></div>';
		}
		elseif($data['premium'] > 0 && $packs >= $site['vip_sc_limit'])
		{
			$message = '<div class="msg"><div class="error">'.lang_rep($lang['b_390'], array('-LIMIT-' => $site['vip_sc_limit'])).'</div></div>';
		}
		else
		{
			$db->Query("UPDATE `users` SET `coins`=`coins`-'".$coins."' WHERE `id`='".$data['id']."'");
			$db->Query("INSERT INTO `sell_coins`(`seller_id`,`coins`,`price`,`coin_value`,`added_time`)VALUES('".$data['id']."','".$coins."','".$price."','".$coin_value."','".time()."')");

			$message = '<div class="msg"><div class="success">'.$lang['b_385'].'</div></div>';
		}
	}
}
?>
<div class="content t-left">
<h2 class="title"><?=$lang['b_375']?></h2><?=$message?>
<div class="infobox">
<form method="post">
		<table style="text-align:left;">
            <tr>
				<td class="t-left"><?=$lang['b_42']?></td>
				<td style="padding-left:30px"><input type="text" class="l_form" name="coins" placeholder="1000" tabindex="1" /></td>
				<td style="position:absolute; margin: 33px 50px"><input class="gbut" name="add" type="submit" value="<?=$lang['b_376']?>" style="padding: 10px 22px" tabindex="3" /></td>
			</tr>
			<tr>
				<td class="t-left"><?=$lang['b_249']?></td>
				<td style="padding-left:30px"><input type="text" class="l_form" name="price" placeholder="0.00" tabindex="2" /></td>
			</tr>
		</table>
		<small><i><?=lang_rep($lang['b_393'], array('-FEE-' => $site['sc_fees']))?></i></small>
	</form>
</div><br />

<h2 class="title"><?=$lang['b_377']?></h2>
<table cellpadding="5" class="table" style="text-align:center">
	<thead><tr><td>#</td><td><?=$lang['b_42']?></td><td><?=$lang['b_249']?></td><td><?=$lang['b_378']?></td><td><?=$lang['b_106']?></td><td width="60"><?=$lang['b_185']?></td></tr></thead>
	<tbody>
	<?php
		$coins = $db->QueryFetchArrayAll("SELECT * FROM `sell_coins` WHERE `seller_id`='".$data['id']."' AND `sold`='0' ORDER BY `added_time` DESC");

		if(empty($coins))
		{
			echo '<tr class="c_1"><td colspan="6">'.$lang['b_250'].'</td></tr>';
		}

		$x = 1;
		foreach($coins as $coin){
			$x++;
			$color = ($x%2) ? 3 : 1;
	?>
		<tr class="c_<?=$color?>"><td class="t-left"><?=$coin['id']?></td><td><?=number_format($coin['coins'])?></td><td><?=get_currency_symbol($site['currency_code']).number_format($coin['price'], 2)?></td><td><?=get_currency_symbol($site['currency_code']).$coin['coin_value']?></td><td><?=date('d M Y - H:i', $coin['added_time'])?></td><td><a href="sellcoins.php?del=<?=$coin['id']?>" onclick="return confirm('Are you sure you want to remove this coins pack from sale?');"><?=$lang['b_81']?></a></td></tr>
	<?php } ?>
	</tbody>
</table><br />

<h2 class="title"><?=$lang['b_383']?></h2>
<table cellpadding="5" class="table" style="text-align:center">
	<thead><tr><td>#</td><td><?=$lang['b_42']?></td><td><?=$lang['b_249']?></td><td><?=$lang['b_379']?></td><td><?=$lang['b_380']?></td><td><?=$lang['b_381']?></td><td><?=$lang['b_382']?></td></tr></thead>
	<tbody>
	<?php
		$coins = $db->QueryFetchArrayAll("SELECT * FROM `sell_coins` WHERE `seller_id`='".$data['id']."' AND `sold`='1' ORDER BY `sold_time` DESC");

		if(empty($coins))
		{
			echo '<tr class="c_1"><td colspan="7">'.$lang['b_250'].'</td></tr>';
		}

		$x = 1;
		foreach($coins as $coin){
			$x++;
			$color = ($x%2) ? 3 : 1;
	?>
		<tr class="c_<?=$color?>"><td class="t-left"><?=$coin['id']?></td><td><?=number_format($coin['coins'])?></td><td><?=get_currency_symbol($site['currency_code']).number_format($coin['price'], 2)?></td><td><?=get_currency_symbol($site['currency_code']).number_format($coin['fees'], 2)?></td><td><?=get_currency_symbol($site['currency_code']).number_format(($coin['price']-$coin['fees']), 2)?></td><td><?=date('d M Y - H:i', $coin['added_time'])?></td><td><?=date('d M Y - H:i', $coin['sold_time'])?></td></tr>
	<?php } ?>
	</tbody>
</table>
</div>
<?php
	include('footer.php');
?>