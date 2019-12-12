<?php
	include('header.php');
	if(!$is_online){redirect('index.php');}

	if(isset($_GET['history'])){
		$last_race = $db->QueryFetchArray("SELECT * FROM `hs_rounds` WHERE `active`='0' ORDER BY `end_time` DESC LIMIT 1");
?>
<div class="content">
	<h2 class="title">
		<?php echo $lang['hs_19']; ?>
	</h2>
	<div class="box">
			<center><object id="flashcontent" data="img/horserace/horserace.swf" type="application/x-shockwave-flash" height="300" width="540"><param value="transparent" name="wmode"><param value="horses=Lucky strike,Flash,Blitz,Runner,Thunder&winners=<?=$last_race['horses']?>&race_datum=<?=date('d M Y', $last_race['end_time'])?>&race_tijd=<?=date('H:i', $last_race['end_time'])?>&text_herhaling=<?php echo $lang['hs_02']; ?>" name="flashvars"></object></center>
		</div>
		<table class="table">
			<?php
				function get_horse($id){
					global $db;
					$horse_name = $db->QueryFetchArray("SELECT horse FROM `hs_horses` WHERE `id`='".$id."'");
					return $horse_name['horse'];
				}

				$races = $db->QueryFetchArrayAll("SELECT id,horses,end_time FROM `hs_rounds` WHERE `active`='0' ORDER BY `end_time` DESC LIMIT 5");
				foreach($races as $race){
					$horse_id = explode(',', $race['horses']);
			?>
				<thead><tr><td colspan="5"><?php echo $lang['hs_03']; ?> #<?=$race['id']?> - <?=date('H:i', $race['end_time'])?></td></tr></thead>
				<tr style="text-align:center">
					<td><b>#1 - <?=get_horse($horse_id[0])?></b></td>	
					<td><b>#2 - <?=get_horse($horse_id[1])?></b></td>	
					<td><b>#3 - <?=get_horse($horse_id[2])?></b></td>
					<td><b>#4 - <?=get_horse($horse_id[3])?></b></td>
					<td><b>#5 - <?=get_horse($horse_id[4])?></b></td>
				</tr>
					<tr style="text-align:center">
					<td><img src="img/horserace/horses/horse_<?=$horse_id[0]?>.png" width="90"></td>	
					<td><img src="img/horserace/horses/horse_<?=$horse_id[1]?>.png" width="90"></td>	
					<td><img src="img/horserace/horses/horse_<?=$horse_id[2]?>.png" width="90"></td>
					<td><img src="img/horserace/horses/horse_<?=$horse_id[3]?>.png" width="90"></td>
					<td><img src="img/horserace/horses/horse_<?=$horse_id[4]?>.png" width="90"></td>
				</tr>
				<tr><td style="background:transparent" colspan="5">&nbsp;</td></tr>
			<?php }?>
		</table>
	</div>
</div>
<?php
}else{
	function percent_calc($num_amount, $num_total) {
		if(empty($num_amout) && empty($num_total))
		{
			return '0';
		}
		
		$count = ($num_amount / $num_total) * 100;
		$count = number_format($count, 0);
		return $count;
	}

	$hs_round = $db->QueryFetchArray("SELECT * FROM `hs_rounds` WHERE `active`='1' ORDER BY started DESC LIMIT 1");
	$timeleft = $hs_round['end_time'] - time();

	$last_buy = unserialize($hs_round['buy_timestamps']);

	$message = '';
	if(isset($_POST['submit']) ){
		$tickets = 0;
		foreach($_POST['horse'] as $horse => $key){
			$tickets = $tickets + NumbersOnly($_POST['bet'][$key], 0);
		}
		
		$money = ($tickets * $site['hs_ticket_price']);
		if($tickets == 0){
			$message = '<div class="msg"><div class="error">'.$lang['hs_04'].'</div></div>';
		}elseif($money > $data['coins']){
			$message = '<div class="msg"><div class="error">'.lang_rep($lang['hs_05'], array('-TICKETS-' => $tickets, '-PRICE-' => $site['hs_ticket_price'])).'</div></div>';
		}elseif($tickets > $site['hs_max_tickets']){
			$message = '<div class="msg"><div class="error">'.lang_rep($lang['hs_06'], array('-LIMIT-' => number_format($site['hs_max_tickets']))).'</div></div>';
		}else{
			foreach($_POST['horse'] as $horse => $key){
				if(!empty($_POST['bet'][$key])){
					$key = $db->EscapeString($key);
					$ticket = $db->EscapeString(NumbersOnly($_POST['bet'][$key], 0));
					$horse = $db->QueryFetchArray("SELECT * FROM `hs_horses` WHERE `id`='".$key."'");

					$players = unserialize($horse['players']);
					$players[$data['id']] = array(
						'tickets' => ($ticket + (empty($players[$data['id']]['tickets']) ? 0 : $players[$data['id']]['tickets'])),
						'money' => (($ticket * $site['hs_ticket_price']) + (empty($players[$data['id']]['money']) ? 0 : $players[$data['id']]['money']))
					);

					$db->Query("UPDATE `hs_horses` SET `total_tickets`=`total_tickets`+'".$ticket."', `players`='".serialize($players)."' WHERE `id`='".$key."'");
				}
			}
			
			$last_buy[$data['id']] = time();
			$db->Query("UPDATE `hs_rounds` SET `buy_timestamps`='".serialize($last_buy)."' WHERE `id`='".$hs_round['id']."'");
			$db->Query("UPDATE `users` SET `coins`=`coins`-'".$money."' WHERE `id`='".$data['id']."'");

			$message = '<div class="msg"><div class="success">'.lang_rep($lang['hs_07'], array('-TICKETS-' => $tickets, '-PRICE-' => number_format($money))).'</div></div>';
		}
	}
?>
<div id="main">
	<div class="content">
		<h2 class="title"><?php echo $lang['hs_01']; ?></h2>
		<?php echo $message; ?>
		<div class="infobox">
			<p><?php echo lang_rep($lang['hs_08'], array('-PRICE-' => number_format($site['hs_ticket_price']))); ?></p>
			<p><?php echo lang_rep($lang['hs_09'], array('-TIME-' => date('d M Y - H:00', (time()+3600)))); ?></p>
	</div>
<?php
	if($hs_round['id'] != ''){
?>
	<form method="POST">
		<table class="table" style="margin: 5px 0">
			<?php
				$total_tickets = $db->QueryFetchArray("SELECT SUM(`total_tickets`) AS `tickets` FROM `hs_horses`");
				$horses = $db->QueryFetchArrayAll("SELECT * FROM `hs_horses`");
				foreach($horses as $horse){
					
					$owned_tickets = 0;
					$players = unserialize($horse['players']);
					if(!empty($players[$data['id']]['tickets']))
					{
						$owned_tickets = $players[$data['id']]['tickets'];
					}
			?>
				<thead><tr><td colspan="8"><b><?=$horse['horse']?></b></td></tr></thead>	
				<tr>
				    <td rowspan="3" colspan="1"><img src="img/horserace/horses/horse_<?=$horse['id']?>.png" width="90"></td>
				    <td><img src="img/horserace/chart_curve.png"></td>
				    <td><?php echo $lang['hs_10']; ?></td>
				    <td><div class="hs_progressbar" title="<?php echo $lang['hs_11']; ?>: <?=$horse['speed']?>%"><div class="progress" style="width: <?=$horse['speed']?>%;"><p><?=$horse['speed']?>%</p></div></div></td>
				    <td><img src="img/horserace/chart_bar.png"></td>
				    <td><?php echo $lang['hs_11']; ?></td>
					<td colspan="2">
						<b><?=$horse['winchance']?>%</b>
					</td>
				</tr>
				<tr>
				    <td><img src="img/horserace/stats_health.png"></td>
				    <td><?php echo $lang['hs_12']; ?></td>
				    <td><div class="hs_progressbar" title="<?php echo $lang['hs_12']; ?>: <?=$horse['condition']?>%"><div class="progress" style="width: <?=$horse['condition']?>%;"><p><?=$horse['condition']?>%</p></div></div></td>
				    <td><img src="img/horserace/coins_add.png"></td>
				    <td><?php echo $lang['hs_13']; ?></td>
					<td colspan="2"><?=$horse['payment']?> x <?php echo $lang['hs_14']; ?></td>
				</tr>
				<tr>
				    <td><img src="img/horserace/cog.png"></td>
				    <td><?php echo $lang['hs_15']; ?></td>
				    <td><div class="hs_progressbar" title="<?php echo $lang['hs_15']; ?>: <?=percent_calc($horse['total_tickets'], $total_tickets['tickets'])?>%"><div class="progress" style="width: <?=percent_calc($horse['total_tickets'], $total_tickets['tickets'])?>%;"><p><?=percent_calc($horse['total_tickets'], $total_tickets['tickets'])?>%</p></div></div></td>
					<td><img src="img/horserace/tag_blue.png"></td>
					<td><?php echo $lang['hs_16']; ?></td>
					<td colspan="2"><?=number_format($owned_tickets)?></td>
				</tr>
				<tr>
				  	<td colspan="4"> </td>
				  	<td><img src="img/horserace/tag_blue_add.png"></td>
				  	<td><?php echo $lang['hs_17']; ?></td>
				  	<td>
				  		<input name="horse[<?=$horse['id']?>]" value="<?=$horse['id']?>" type="hidden">
						<input name="bet[<?=$horse['id']?>]" size="2" type="text" placeholder="<?=$site['hs_max_tickets']?>">
				  	</td>
				  	<td>
				  		<input value="<?php echo $lang['hs_18']; ?>" name="submit" class="gbut" style="padding: 2px 5px" type="submit">
				  	</td>
				</tr>
				<tr>
				    <td colspan="8" style="background:transparent">&nbsp;</td>
				</tr>
			<?php } ?>
			</table>
		</form>
	<?php }} ?>
</div>
<?include('footer.php');?>