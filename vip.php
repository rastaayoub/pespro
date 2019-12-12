<?php
include('header.php');
if(!$is_online){
	redirect('index.php');
}

$mesaj = '';
if(isset($_POST['vip_coins']) && $site['vip_purchase'] != 1){
	$pid = $db->EscapeString($_POST['cpid']);
	$vc_pack = $db->QueryFetchArray("SELECT * FROM `p_pack` WHERE `id`='".$pid."' AND `type`='1'");
	if(!empty($vc_pack['id'])){
		if($data['coins'] >= $vc_pack['coins_price']){
			$premium = ($data['premium'] == 0 ? (time()+(86400*$vc_pack['days'])) : ((86400*$vc_pack['days'])+$data['premium']));
			$db->Query("UPDATE `users` SET `coins`=`coins`-'".$vc_pack['coins_price']."', `premium`='".$premium."' WHERE `id`='".$data['id']."'");
			$mesaj = '<div class="msg"><div class="success">'.$lang['b_241'].'</div></div>';
		}else{
			$mesaj = '<div class="msg"><div class="error">'.$lang['b_146'].'</div></div>';
		}
	}
}
if(isset($_GET['history'])){
?>
<div class="content"><h2 class="title"><?=$lang['b_247']?> <span style="float:right"><a href="vip.php"><?=$lang['b_08']?></a></span></h2>
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
<?
  $tras = $db->QueryFetchArrayAll("SELECT * FROM `user_transactions` WHERE `user_id`='".$data['id']."' AND `type`='1' ORDER BY `date` DESC");
  if(!$tras){
	echo '<tr><td colspan="5">'.$lang['b_250'].'</td></tr>';
  }
  foreach($tras as $tra){
?>	
			<tr>
				<td><?=$tra['id']?></td>
				<td><?=$tra['value']?> <?=($tra['type'] == 0 ? $lang['b_42'] : $lang['b_246'])?></td>
				<td><?=$tra['cash'].' '.get_currency_symbol($site['currency_code'])?></td>
				<td><?=date('Y-m-d H:i',$tra['date'])?></td>
			</tr>
<?}?>
		</tbody>
	</table>
</div>
<?
}else{
	$biggestBonus = $db->QueryFetchArray("SELECT free_bonus, vip_bonus FROM `levels` ORDER BY `vip_bonus` DESC LIMIT 1");

	$msg = '';
	if(isset($_POST['submit']) && isset($_POST['pid']) && $site['vip_purchase'] != 1){
		$pid = $db->EscapeString($_POST['pid']);
		$pack = $db->QueryFetchArray("SELECT id,days,price FROM `p_pack` WHERE `id`='".$pid."'");
		if(empty($pack['id'])){
			$mesaj = '<div class="msg"><div class="error">'.$lang['b_262'].'</div></div>';
		}elseif($data['account_balance'] < $pack['price']){
			$mesaj = '<div class="msg"><div class="error">'.$lang['b_263'].' <a href="bank.php"><b>'.$lang['b_256'].'...</b></a></div></div>';
		}else{
			$premium = ($data['premium'] == 0 ? (time()+(86400*$pack['days'])) : ((86400*$pack['days'])+$data['premium']));
			$db->Query("UPDATE `users` SET `account_balance`=`account_balance`-'".$pack['price']."', `premium`='".$premium."' WHERE `id`='".$data['id']."'");
			$db->Query("INSERT INTO `user_transactions` (`user_id`,`type`,`value`,`cash`,`date`)VALUES('".$data['id']."','1','".$pack['days']."','".$pack['price']."','".time()."')");

			if($site['paysys'] == 1 && $data['ref'] > 0 && $data['ref_paid'] == 1 && $pack['price'] > 0){
				$commission = number_format(($pack['price']/100) * $site['ref_sale'], 2);
				affiliate_commission($data['ref'], $data['id'], $commission, 'vip_purchase');
			}
			
			$mesaj = '<div class="msg"><div class="success">'.lang_rep($lang['b_264'], array('-NUM-' => $pack['days'].' '.$lang['b_246'])).'</div></div>';
		}
	}
?>
<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300italic,700' rel='stylesheet' type='text/css' /><link rel="stylesheet" type="text/css" href="theme/<?=$site['theme']?>/vip.css" />
<div class="content"><h2 class="title"><?=$lang['b_08']?> <span style="float:right"><a href="vip.php?history"><?=$lang['b_247']?></a></span></h2><?=$mesaj?> 
<div class="membership_block"><?=$lang['b_192']?>:<br /><b><?=($data['premium'] > 0 ? $lang['b_194'] : $lang['b_193'])?></b></div>
<div class="membership_block"><?=$lang['b_159']?>:<br /><b><?=(empty($data['premium']) ? $lang['b_370'] : date('d M Y - H:i', $data['premium']))?></b></div>
<div style="clear:both"></div>
	<div class="vip_t_row">
        <div class="first_child">
       		<div class="title_desc_colum"><?=$lang['b_192']?></div>
        </div>
        <ul>
            <li class="desc_colum"><?=$lang['b_195']?></li>
            <li class="desc_colum"><?=$lang['b_196']?></li>
			<li class="desc_colum"><?=$lang['b_223']?></li>
			<?if($site['vip_purchase'] == 1 && $site['vip_monthly_coins'] > 0){?><li class="desc_colum"><?=$lang['b_368']?></li><?}?>
			<?if($site['req_clicks'] > 0){?><li class="desc_colum"><?=$lang['b_395']?></li><?}?>
			<li class="desc_colum"><?=$lang['b_361']?></li>
			<li class="desc_colum"><?=$lang['b_391']?></li>
			<?if($site['clicks_limit'] > 0){?><li class="desc_colum"><?=$lang['b_298']?></li><?}?>
			<?if($site['banner_system'] != 0){?><li class="desc_colum"><?=$lang['b_233']?></li><?}?>
			<?if($site['target_system'] != 2){?><li class="desc_colum"><?=$lang['b_242']?></li><?}?>
            <?if($site['transfer_status'] != 1){?><li class="desc_colum"><?=$lang['b_232']?></li><?}?>
            <li class="desc_colum"><?=$lang['b_197']?></li>
            <li class="desc_colum"><?=$lang['b_293']?></li>
			<li class="desc_colum"><?=$lang['b_325']?></li>
            <li class="desc_colum"><?=($site['vip_purchase'] == 1 ? $lang['b_367'] : $lang['b_198'])?></li>
    	</ul>
    </div>
    <div class="vip_t_row">
        <div class="first_child">
            <div class="sub_pack"><?=$lang['b_193']?></div>
        </div>
        <ul>
            <li><strong><?=$site['free_cpc']?></strong> <?=$lang['b_36']?></li>
            <li><?=$lang['b_355']?> <strong><?=$biggestBonus['free_bonus']?></strong> <?=$lang['b_156']?></li>
			<li><strong><?=$site['c_c_limit']?></strong> <?=$lang['b_224']?></li>
			<?if($site['vip_purchase'] == 1 && $site['vip_monthly_coins'] > 0){?><li><strong>0</strong> <?=$lang['b_156']?></li><?}?>
			<?if($site['req_clicks'] > 0){?><li><?=('<strong>'.$site['req_clicks'].'</strong> '.$lang['b_396'])?></li><?}?>
			<li><?=($site['surf_type'] == 1 ? 'Manual Surf <small>('.$lang['b_362'].')</small>' : ($site['surf_type'] == 2 ? 'Popup Surf <small>('.$lang['b_363'].')</small>' : 'Auto Surf <small>('.$lang['b_363'].')</small>'))?></li>
			<li><strong><?=$site['free_sc_limit']?></strong> <?=$lang['b_392']?></li>
			<?if($site['clicks_limit'] > 0){?><li><strong><?=number_format($site['clicks_limit'])?></strong> <?=$lang['b_141']?></li><?}?>
			<?if($site['banner_system'] != 0){?><li class="img_<?=($site['banner_system'] == 2 ? 'no' : 'yes')?>"></li><?}?>
			<?if($site['target_system'] != 2){?><li class="img_<?=($site['target_system'] == 1 ? 'no' : 'yes')?>"></li><?}?>
            <?if($site['transfer_status'] != 1){?><li class="img_<?=($site['transfer_status'] == 2 ? 'no' : 'yes')?>"></li><?}?>
            <li class="img_no"></li>
			<li class="img_no"></li>
			<li class="img_no"></li>
			<?if($site['vip_purchase'] == 1){?><li><strong><?=get_currency_symbol($site['currency_code'])?>0.00</strong></li><?}else{?><li class="img_no"></li><?}?>
        </ul>
        <div class="last_child">
        	<div class="price_box">
                <br /><span class="price"><?=$lang['b_193']?></span>
            </div>
        </div>
    </div>
    <div class="vip_t_row">
        <div class="first_child">
            <div class="sub_pack"><?=$lang['b_194']?></div>
        </div>
        <ul>
            <li><strong><?=$site['premium_cpc']?></strong> <?=$lang['b_36']?></li>
            <li><?=$lang['b_355']?> <strong><?=$biggestBonus['vip_bonus']?></strong> <?=$lang['b_156']?></li>
			<li><strong><?=$site['c_v_limit']?></strong> <?=$lang['b_224']?></li>
			<?if($site['vip_purchase'] == 1 && $site['vip_monthly_coins'] > 0){?><li><strong><?=number_format($site['vip_monthly_coins'])?></strong> <?=$lang['b_156']?></li><?}?>
			<?if($site['req_clicks'] > 0){?><li><strong><?=$lang['b_397']?></strong></li><?}?>
			<li><?=($site['vip_surf_type'] == 1 ? 'Manual Surf <small>('.$lang['b_362'].')</small>' : ($site['vip_surf_type'] == 2 ? 'Popup Surf <small>('.$lang['b_363'].')</small>' : 'Auto Surf <small>('.$lang['b_363'].')</small>'))?></li>
			<li><strong><?=$site['vip_sc_limit']?></strong> <?=$lang['b_392']?></li>
			<?if($site['clicks_limit'] > 0){?><li><strong><?=$lang['b_299']?></strong></li><?}?>
			<?if($site['banner_system'] != 0){?><li class="img_yes"></li><?}?>
			<?if($site['target_system'] != 2){?><li class="img_yes"></li><?}?>
            <?if($site['transfer_status'] != 1){?><li class="img_yes"></li><?}?>
            <li class="img_yes"></li>
			<li class="img_yes"></li>
            <li class="img_yes"></li>
			<?if($site['vip_purchase'] == 1){ echo '<li><strong>'.get_currency_symbol($site['currency_code']).$site['vip_subscription_price'].'</strong></li>'; }else{?><li class="img_yes"></li><?}?>
        </ul>
		<?php
			if($site['vip_purchase'] == 1) {
		?>
			<div class="last_child">
				<div class="pay_box">
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
						<input type="hidden" name="cmd" value="_xclick-subscriptions">
						<input type="hidden" name="business" value="<?=$site['paypal']?>">
						<input type="hidden" name="item_name" value="VIP Membership">
						<input type="hidden" name="no_note" value="1">
						<input type="hidden" name="no_shipping" value="1">
						<input type="hidden" name="rm" value="1">
						<input type="hidden" name="return" value="<?=$site['site_url']?>/vip.php?success">
						<input type="hidden" name="cancel_return" value="<?=$site['site_url']?>/vip.php?cancel">
						<input type="hidden" name="src" value="1">
						<input type="hidden" name="a3" value="<?=$site['vip_subscription_price']?>">
						<input type="hidden" name="p3" value="1">
						<input type="hidden" name="t3" value="M">
						<input type="hidden" name="currency_code" value="<?=($site['currency_code'] == '' ? 'USD' : $site['currency_code'])?>">
						<input type="hidden" name="bn" value="PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHosted">
						<input type="hidden" name="custom" value="<?=($data['id'].'|'.VisitorIP())?>">
						<input type="hidden" name="notify_url" value="<?=$site['site_url']?>/system/payments/paypal/vip_ipn.php">
						<input type="submit" name="submit" value="<?=$lang['b_199']?>" class="pay_button" style="margin-top:17px;" />
					</form>
				</div>
			</div>
		<?php
			} else {
		?>
			<div class="last_child">
				<div class="pay_box">
				<form method="POST">
					<span class="pack_select">
						<select name="pid">
						<?
							$cp_check = $db->QueryFetchArray("SELECT COUNT(*) AS `total` FROM `p_pack` WHERE `type`='1'");
							$packs = $db->QueryFetchArrayAll("SELECT id,days,price FROM `p_pack` WHERE `type`='0' ORDER BY `price` ASC");
							foreach($packs as $pack){
								echo '<option value="'.$pack['id'].'">'.$pack['days'].' '.$lang['b_158'].' - '.($site['currency_code'] == '' ? get_currency_symbol('USD') : get_currency_symbol($site['currency_code'])).$pack['price'].'</option>';
							}
						?>
						</select>
					</span><br />
					<input type="submit" name="submit" value="<?=$lang['b_199']?>" class="pay_button" />
				</form>
				</div>
			</div>
			<?if($cp_check['total'] > 0){?>
			<b><?=$lang['b_240']?></b>
			<div class="last_child">
				<div class="pay_box">
				<form method="POST">
					<span class="pack_select">
						<select name="cpid" id="cpid">
						<?
							$packs = $db->QueryFetchArrayAll("SELECT id,days,coins_price FROM `p_pack` WHERE `type`='1' ORDER BY `coins_price` ASC");
							foreach($packs as $pack){
								echo '<option value="'.$pack['id'].'">'.$pack['days'].' '.$lang['b_158'].' - '.$pack['coins_price'].' coins</option>';
							}
						?>
						</select>
					</span><br />
					<input type="submit" name="vip_coins" value="<?=$lang['b_199']?>" class="pay_button" />
				</form>
				</div>
			</div>
		<?php
				}
			}
		?>
    </div>
</div>
<?}include('footer.php');?>