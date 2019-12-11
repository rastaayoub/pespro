<?php
include('header.php');
if(!$is_online || $site['transfer_status'] == 1){
	redirect('index.php');
}

$minimum_days = 3;		// Minimum days from registration, to transfer coins
$timecheck = ((86400*3)+strtotime($data['signup']));
$valid_transfer = false;
if($site['transfer_status'] == 2 && $data['premium'] > 0){
	$valid_transfer = true;
}elseif($site['transfer_status'] != 2){
	$valid_transfer = true;
}

$msg = '';
if(isset($_POST['submit']) && $timecheck < time() && $valid_transfer) {
	$username = $db->EscapeString($_POST['username']);
	$amount = $db->EscapeString($_POST['amount']);
	$rid = $db->QueryFetchArray("SELECT id FROM `users` WHERE `login`='".$username."' AND `banned`='0' LIMIT 1");
	
	if($data['coins'] < $amount){
		$msg = '<div class="msg"><div class="error">'.$lang['b_146'].'</div></div>';
	}elseif($data['login'] == $username){
		$msg = '<div class="msg"><div class="error">You cannot transfer coins to yourself!</div></div>';
	}elseif($amount < 10 || $amount > $data['coins'] || !is_numeric($amount)){
		$msg = '<div class="msg"><div class="error">'.lang_rep($lang['b_148'], array('-MAX-' => $data['coins'])).'</div></div>';
	}elseif(empty($rid['id'])){
		$msg = '<div class="msg"><div class="error">'.$lang['b_147'].'</div></div>';
	}else{
		$fee = ($site['transfer_fee']/100*$amount);
		$fee = ($fee < 1 ? 1 : $fee);
		$tamount = ($amount-$fee);

		$db->Query("UPDATE `users` SET `coins`=`coins`-'".$amount."' WHERE `id`='".$data['id']."'");
		$db->Query("UPDATE `users` SET `coins`=`coins`+'".round($tamount)."' WHERE `login`='".$username."'");
		$db->Query("INSERT INTO `c_transfers` (`receiver`, `sender`, `coins`, `date`)VALUES('".$rid['id']."', '".$data['login']."', '".$tamount."', '".time()."')");
		$msg = '<div class="msg"><div class="success">'.lang_rep($lang['b_149'], array('-SENT-' => $amount, '-USER-' => $username, '-RECEIVED-' => round($tamount), '-FEE-' => $site['transfer_fee'])).'</div></div> <script> $("#c_coins").html('.($data['coins']-$amount).'); </script>';
	}
}?>
<div class="content t-left"><?=$msg?> 
<h2 class="title"><?=$lang['b_11']?></h2>
<?if($site['transfer_status'] == 2 && $data['premium'] == 0){?>
<div class="msg"><div class="error"><?=$lang['b_231']?></div></div>
<?}elseif($timecheck > time()){?>
<div class="msg"><div class="error"><?=lang_rep($lang['b_228'], array('-DAYS-' => $minimum_days))?></div></div>
<?}else{?>
<div class="infobox">
<form method="post">
	<p>
		<label><?=$lang['b_150']?></label><br />
		<input class="text big" onfocus="if(this.value == '<?=$lang['b_122']?>') { this.value = ''; }" onblur="if(this.value=='') { this.value = this.defaultValue }" value="<?=$lang['b_122']?>" name="username" type="text">
	</p>
	<p>	
		<label><?=$lang['b_151']?></label><br />
		<input class="text big" onfocus="if(this.value == '10') { this.value = ''; }" onblur="if(this.value=='') { this.value = this.defaultValue }" value="10" name="amount" type="text">
	</p>	
	<p>	
		<b><?=$lang['b_152']?>:</b> <?=$site['transfer_fee']?>%
	</p>
		<input type="submit" class="gbut" name="submit" value="<?=$lang['b_52']?>" />
</form>
</div><br><br><?}?>
<h2 class="title"><?=$lang['b_153']?></h2>
<table class="table">
	<thead>
		<tr>
			<td width="35"><?=$lang['b_154']?></td>
			<td><?=$lang['b_155']?></td>
			<td><?=$lang['b_42']?></td>
			<td width="35%"><?=$lang['b_106']?></td>
		</tr>
	</thead>
	<tfoot>
		<tr><td colspan="4"><center><?=$lang['b_153']?></center></td></tr>
	</tfoot>
	<tbody>
<?
$transfers = $db->QueryFetchArrayAll("SELECT id,sender,coins,date FROM `c_transfers` WHERE `receiver`='".$data['id']."' ORDER BY date DESC LIMIT 10");
if(!$transfers){
	echo '<tr><td colspan="4"><center>'.$lang['b_157'].'</center></td></tr>';
}

foreach($transfers as $transfer){
?>	
	<tr>
		<td><?=$transfer['id']?></td>
		<td><?=$transfer['sender']?></td>
		<td><?=$transfer['coins']?> <?=$lang['b_156']?></td>
		<td><?=date('d F Y - H:i:s', $transfer['date'])?></td>
	</tr>
<?}?>
	</tbody>
</table>
</div>	
<?include('footer.php');?>