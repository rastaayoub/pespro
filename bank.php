<?php
include('header.php');
if(!$is_online){ redirect('index.php'); }

$msg = '';
if(isset($_GET['success'])){
	$msg = '<div class="msg"><div class="success">Thank you for your purchase!</div></div>';
}elseif(isset($_GET['cancel'])){
	$msg = '<div class="msg"><div class="error">Your transaction has been canceled!</div></div>';
}
?>
<div class="content"><h2 class="title"><?=$lang['b_255']?></h2>
<?
if(isset($_GET['convert']) && $site['convert_enabled'] == 1){
	$refs = $db->QueryGetNumRows("SELECT id FROM `coins_to_cash` WHERE `user`='".$data['id']."'");
	if(isset($_POST['submit'])){
		$coins = $db->EscapeString($_POST['coins']);

		if(!is_numeric($coins)){
			$msg = '<div class="msg"><div class="error">'.$lang['b_253'].'</div></div>';
		}elseif($data['coins'] < $coins){
			$msg = '<div class="msg"><div class="error">'.$lang['b_146'].'</div></div>';
		}elseif($coins < $site['min_convert']){
			$msg = '<div class="msg"><div class="error">'.lang_rep($lang['b_265'], array('-MIN-' => $site['min_convert'])).'</div></div>';
		}else{
			$cash = floor(((1/$site['convert_rate'])*$coins) * 100) / 100;
			
			if($cash > 0) {
				$db->Query("UPDATE `users` SET `coins`=`coins`-'".$coins."', `account_balance`=`account_balance`+'".$cash."' WHERE `id`='".$data['id']."'");
				$db->Query("INSERT INTO `coins_to_cash` (user, coins, cash, conv_rate, date) VALUES('".$data['id']."', '".$coins."', '".$cash."', '".$site['convert_rate']."', '".time()."')");
			}

			$msg = '<div class="msg"><div class="success">'.lang_rep($lang['b_266'], array('-NUM-' => $coins, '-CASH-' => get_currency_symbol($site['currency_code']).' '.$cash)).'</div></div>';
		}
	}
	echo $msg;
?>
	<div class="infobox" style="width:250px;margin:10px auto;display:inline-block"><b><?=$lang['b_247']?>: <span style="font-weight:600;color:blue"><?=$refs?></span></b></div>
	<div class="infobox" style="width:250px;margin:10px auto;display:inline-block"><b><?=$lang['b_255']?>: <span style="font-weight:600;color:green"><?=get_currency_symbol($site['currency_code'])?> <?=$data['account_balance']?></span></b></div>
<div class="infobox t-left">
<center><div class="ucp_link<?=(!isset($_GET['convert']) && !isset($_GET['withdraw']) ? ' active' : '')?>" style="margin-right:5px;display:inline-block"><a href="bank.php"><?=$lang['b_256']?></a></div><?if($site['convert_enabled'] == 1){?><div class="ucp_link<?=(isset($_GET['convert']) ? ' active' : '')?>" style="margin-right:5px;display:inline-block"><a href="bank.php?convert"><?=$lang['b_268']?></a></div><?}?><?if($site['allow_withdraw'] == 1){?><div class="ucp_link<?=(isset($_GET['withdraw']) ? ' active' : '')?>" style="margin-right:5px;display:inline-block"><a href="bank.php?withdraw"><?=$lang['b_97']?></a></div><?}?></center><hr>
<script type="text/javascript">
	function get_amount(value){
		if(value > 0) {
			var amount = Math.floor((<?=(1/$site['convert_rate'])?>*value)*100)/100;
			$('#amount-final').html('<?=$lang['b_269']?> <b><?=get_currency_symbol($site['currency_code'])?> '+ amount +'</b>');
		}
	}
</script><?=$msg?>
<form method="post">
    <p>
		<label><?=$lang['b_267']?></label><br/>
		<input class="text big" type="text" value="<?=$site['convert_rate']?>" name="coins" oninput="get_amount(this.value)" maxlength="7" />
	</p>
	<p>
		<div id="amount-final"><?=$lang['b_269']?> <b><?=get_currency_symbol($site['currency_code'])?> 1.00</b></div>
	</p>
    <p>
		<input type="submit" class="gbut" value="<?=$lang['b_58']?>" name="submit" />
	</p>
</form>
</div>
<h2 class="title" style="margin-top:20px"><?=$lang['b_257']?></h2>
	<table class="table">
		<thead>
			<tr>
				<td>#</td>
				<td><?=$lang['b_103']?></td>
				<td><?=$lang['b_42']?></td>
				<td><?=$lang['b_106']?></td>
				<td><?=$lang['b_75']?></td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td>#</td>
				<td><?=$lang['b_103']?></td>
				<td><?=$lang['b_42']?></td>
				<td><?=$lang['b_106']?></td>
				<td><?=$lang['b_75']?></td>
			</tr>
		</tfoot>
		<tbody>
<?
  $trans = $db->QueryFetchArrayAll("SELECT id,coins,cash,date FROM `coins_to_cash` WHERE `user`='".$data['id']."' ORDER BY `date` DESC LIMIT 10");
  if(!$trans){ echo '<tr><td colspan="6" align="center"><b>'.$lang['b_250'].'</b></td><tr>';}else{
  foreach($trans as $tran){
?>	
			<tr>
				<td><?=$tran['id']?></td>
				<td><?=$tran['cash'].' '.get_currency_symbol($site['currency_code'])?></td>
				<td><?=$tran['coins']?></td>
				<td><?=date('Y-m-d h:i',$tran['date'])?></td>
				<td><font color="green"><b><?=$lang['b_259']?></b></font></td>
			</tr><?}}?>
		</tbody>
	</table>
<?
}elseif(isset($_GET['withdraw']) && $site['allow_withdraw'] == 1){
	$refs = $db->QueryGetNumRows("SELECT id FROM `requests` WHERE `user`='".$data['id']."' AND `gateway`!='accb'");

	$can_withdraw = 1;
	if($site['proof_required'] == 1){
		$check = $db->QueryGetNumRows("SELECT a.id FROM requests a LEFT JOIN payment_proofs b ON b.p_id = a.id WHERE (a.user = '".$data['id']."' AND a.paid = '1') AND (a.proof = '0' OR b.approved = '0')");
		if($check > 0){
			$can_withdraw = 0;
		}
	}
	
	if(isset($_POST['submit']) && $can_withdraw){
		$cash = $db->EscapeString($_POST['cash']);
		$pemail = $db->EscapeString($_POST['email']);
		$gateway = $db->EscapeString($_POST['gateway']);

		$valid = false;
		if($gateway == 'paypal' && $site['paypal_status'] == 1){
			$valid = true;
		}elseif($gateway == 'payeer' && $site['payeer_status'] == 1){
			$valid = true;
		}
		
		if($valid){
			if(!is_numeric($cash) || $cash < $site['pay_min']){
				$msg = '<div class="msg"><div class="error">'.lang_rep($lang['b_98'], array('-MIN-' => $site['pay_min'])).'</div></div>';
			}elseif(time()-(86400*$site['aff_reg_days']) < strtotime($data['signup'])){
				$msg = '<div class="msg"><div class="error">'.lang_rep($lang['b_243'], array('-DAYS-' => $site['aff_reg_days'])).'</div></div>';
			}elseif($_POST['cash'] > $data['account_balance']){
				$msg = '<div class="msg"><div class="error">'.$lang['b_99'].'</div></div>';
			}elseif(!isEmail($_POST['email'])){
				$msg = '<div class="msg"><div class="error">'.$lang['b_100'].'</div></div>';
			}else{
				$clicks = $db->QueryFetchArray("SELECT SUM(`total_clicks`) AS `total_clicks` FROM `user_clicks` WHERE `uid`='".$data['id']."'");
				if($clicks['total_clicks'] < $site['aff_req_clicks']){
					$msg = '<div class="msg"><div class="error">'.lang_rep($lang['b_324'], array('-NUM-' => $site['aff_req_clicks'], '-DONE-' => $clicks['total_clicks'])).'</div></div>';
				}else{
					$db->Query("INSERT INTO `requests` (user, paypal, amount, date, gateway) VALUES('".$data['id']."', '".$pemail."', '".$cash."', NOW(), '".$gateway."')");
					$db->Query("UPDATE `users` SET `account_balance`=`account_balance`-'".$cash."' WHERE `id`='".$data['id']."'");			
					$msg = '<div class="msg"><div class="success">'.$lang['b_101'].'</div></div>';
				}
			}
		}
	}
?>
	<div class="infobox" style="width:250px;margin:10px auto;display:inline-block"><b><?=$lang['b_247']?>: <span style="font-weight:600;color:blue"><?=$refs?></span></b></div>
	<div class="infobox" style="width:250px;margin:10px auto;display:inline-block"><b><?=$lang['b_255']?>: <span style="font-weight:600;color:green"><?=get_currency_symbol($site['currency_code'])?> <?=$data['account_balance']?></span></b></div>
<div class="infobox t-left">
<center><div class="ucp_link<?=(!isset($_GET['convert']) && !isset($_GET['withdraw']) ? ' active' : '')?>" style="margin-right:5px;display:inline-block"><a href="bank.php"><?=$lang['b_256']?></a></div><?if($site['convert_enabled'] == 1){?><div class="ucp_link<?=(isset($_GET['convert']) ? ' active' : '')?>" style="margin-right:5px;display:inline-block"><a href="bank.php?convert"><?=$lang['b_268']?></a></div><?}?><?if($site['allow_withdraw'] == 1){?><div class="ucp_link<?=(isset($_GET['withdraw']) ? ' active' : '')?>" style="margin-right:5px;display:inline-block"><a href="bank.php?withdraw"><?=$lang['b_97']?></a></div><?}?></center><hr>
<?
if($can_withdraw){
	echo $msg;
?>
	<form method="post">
		<p>
			<label><?=$lang['b_103']?> (<?=get_currency_symbol($site['currency_code'])?>)</label><br/>
			<input class="text big" type="text" value="<?=$site['pay_min']?>" name="cash" maxlength="7" />
		</p>
		<p>
			<label><?=$lang['b_226']?></label><br/>
			<select name="gateway" id="gateway" onchange="setSelect()" style="padding:4px;width:226px">
				<?if($site['paypal_status'] == 1){?><option value="paypal">PayPal</option><?}?>
				<?if($site['payeer_status'] == 1){?><option value="payeer">Payeer</option><?}?>
			</select>
		</p>
		<p>
			<label><?=$lang['b_104']?></label><br/>
			<input class="text big" type="text" value="<?=$data['email']?>" name="email" />
		</p>
		<p>
			<input type="submit" class="gbut" value="<?=$lang['b_58']?>" name="submit" />
		</p>
	</form>
<?}else{?>
	<div class="msg"><div class="error"><?=$lang['b_318']?></div></div>
<?}?>
</div>
<div class="infobox t-left"><?=lang_rep($lang['b_102'], array('-SUM-' => $site['pay_min']))?></div>
<h2 class="title" style="margin-top:20px"><?=$lang['b_257']?></h2>
	<table class="table">
		<thead>
			<tr>
				<td width="20">#</td>
				<td><?=$lang['b_103']?></td>
				<td><?=$lang['b_104']?></td>
				<td><?=$lang['b_258']?></td>
				<td><?=$lang['b_106']?></td>
				<td><?=$lang['b_75']?></td>
				<td><?=$lang['b_315']?></td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td>#</td>
				<td><?=$lang['b_103']?></td>
				<td><?=$lang['b_105']?></td>
				<td><?=$lang['b_258']?></td>
				<td><?=$lang['b_106']?></td>
				<td><?=$lang['b_75']?></td>
				<td><?=$lang['b_315']?></td>
			</tr>
		</tfoot>
		<tbody>
<?
$requests = $db->QueryFetchArrayAll("SELECT id,amount,paypal,date,paid,gateway,reason,proof FROM `requests` WHERE `user`='".$data['id']."' AND `gateway`!='accb' ORDER BY `date` DESC LIMIT 10");
if(!$requests){ 
	echo '<tr><td colspan="7" align="center"><b>'.$lang['b_250'].'</b></td><tr>';
}else{
	foreach($requests as $request){
?>	
			<tr>
				<td><?=$request['id']?></td>
				<td><?=$request['amount'].' '.get_currency_symbol($site['currency_code'])?></td>
				<td><?=(!empty($request['paypal']) ? $request['paypal'] : 'N/A')?></td>
				<td><?=ucfirst($request['gateway'])?></td>
				<td><?=$request['date']?></td>
				<td><?if($request['paid'] == 0){?><font color="orange"><?=$lang['b_107']?><?}elseif($request['paid'] == 2){?><font color="red" title="<?=$request['reason']?>"><?=$lang['b_108']?><?}else{?><font color="green"><?=$lang['b_109']?><?}?></font></td>
				<td><?if($request['proof'] == 0 && $request['paid'] == 1){?><a href="proof.php?upload=<?=$request['id']?>"><?=$lang['b_316']?></a><?}elseif($request['proof'] == 1 && $request['paid'] == 1){?><font color="green"><?=$lang['b_317']?></font><?}else{?>N/A<?}?></td>
			</tr>
<?}}?>
		</tbody>
	</table>
<?php
}else{
	$refs = $db->QueryGetNumRows("SELECT id FROM `transactions` WHERE `user_id`='".$data['id']."'");
	if(isset($_POST['submit']) && isset($_POST['gateway'])){
		$cash = $db->EscapeString($_POST['cash']);
		$gateway = $db->EscapeString($_POST['gateway']);
		
		$minimum = 1;
		if($_POST['gateway'] == 'paypal'){
			$minimum = $site['paypal_minimum'];
		}elseif($_POST['gateway'] == 'payeer'){
			$minimum = $site['payeer_minimum'];
		}
		
		if(!is_numeric($cash)){
			$msg = '<div class="msg"><div class="error">'.$lang['b_253'].'</div></div>';
		}elseif($cash < $minimum){
			$msg = '<div class="msg"><div class="error">'.lang_rep($lang['b_254'], array('-MIN-' => get_currency_symbol($site['currency_code']).' '.number_format($minimum, 2))).'</div></div>';
		}else{
			$redurl = $site['site_url'].'/system/payments/'.$gateway.'/add_cash.php?cash='.$cash;
			redirect($redurl);
		}
	}
?>
	<div class="infobox" style="width:250px;margin:10px auto;display:inline-block"><b><?=$lang['b_247']?>: <span style="font-weight:600;color:blue"><?=$refs?></span></b></div>
	<div class="infobox" style="width:250px;margin:10px auto;display:inline-block"><b><?=$lang['b_255']?>: <span style="font-weight:600;color:green"><?=get_currency_symbol($site['currency_code'])?> <?=$data['account_balance']?></span></b></div>
<div class="infobox t-left">
<center><div class="ucp_link<?=(!isset($_GET['convert']) && !isset($_GET['withdraw']) ? ' active' : '')?>" style="margin-right:5px;display:inline-block"><a href="bank.php"><?=$lang['b_256']?></a></div><?if($site['convert_enabled'] == 1){?><div class="ucp_link<?=(isset($_GET['convert']) ? ' active' : '')?>" style="margin-right:5px;display:inline-block"><a href="bank.php?convert"><?=$lang['b_268']?></a></div><?}?><?if($site['allow_withdraw'] == 1){?><div class="ucp_link<?=(isset($_GET['withdraw']) ? ' active' : '')?>" style="margin-right:5px;display:inline-block"><a href="bank.php?withdraw"><?=$lang['b_97']?></a></div><?}?></center><hr>
<?=$msg?>
<form method="post">
    <p>
		<label><?=$lang['b_256']?> (<?=get_currency_symbol($site['currency_code'])?>)</label><br/>
		<input class="text big" type="text" name="cash" maxlength="7" placeholder="0.00" />
	</p>
	<p>
		<label><?=$lang['b_226']?></label><br/>
		<select name="gateway" style="padding:4px;width:226px">
			<?if($site['paypal_status'] >= 1){?><option value="paypal">Paypal</option><?}?>
			<?if($site['payeer_status'] >= 1){?><option value="payeer">Payeer</option><?}?>
		</select>
	</p>
    <p>
		<input type="submit" class="gbut" value="<?=$lang['b_58']?>" name="submit" />
	</p>
</form>
</div>
<h2 class="title" style="margin-top:20px"><?=$lang['b_257']?></h2>
	<table class="table">
		<thead>
			<tr>
				<td width="20">#</td>
				<td><?=$lang['b_103']?></td>
				<td><?=$lang['b_104']?></td>
				<td><?=$lang['b_258']?></td>
				<td><?=$lang['b_106']?></td>
				<td><?=$lang['b_75']?></td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td>#</td>
				<td><?=$lang['b_103']?></td>
				<td><?=$lang['b_104']?></td>
				<td><?=$lang['b_258']?></td>
				<td><?=$lang['b_106']?></td>
				<td><?=$lang['b_75']?></td>
			</tr>
		</tfoot>
		<tbody>
<?php
  $trans = $db->QueryFetchArrayAll("SELECT id,money,gateway,date,paid,payer_email FROM `transactions` WHERE `user_id`='".$data['id']."' ORDER BY `date` DESC LIMIT 10");
  if(!$trans){ echo '<tr><td colspan="6" align="center"><b>'.$lang['b_250'].'</b></td><tr>';}else{
  foreach($trans as $tran){
?>	
			<tr>
				<td><?=$tran['id']?></td>
				<td><?=$tran['money'].' '.get_currency_symbol($site['currency_code'])?></td>
				<td><?=(empty($tran['payer_email']) ? 'N/A' : $tran['payer_email'])?></td>
				<td><?=ucfirst($tran['gateway'])?></td>
				<td><?=$tran['date']?></td>
				<td><?=($tran['paid'] == 1 ? '<font color="green"><b>'.$lang['b_259'].'</b></font>' : '<font color="yellow"><b>'.$lang['b_260'].'</b></font>')?></td>
			</tr><?}}?>
		</tbody>
	</table>
<?}?>
</div>
<?include('footer.php');?>