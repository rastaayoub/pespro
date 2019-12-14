<?php
include('header.php');
if(!$is_online){
	redirect('index.php');
}

$mesaj = '';
if(isset($_POST['vip_coins'])){
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
	$msg = '';
	if(isset($_POST['submit']) && isset($_POST['pid'])){
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
			
			$mesaj = '<div class="msg"><div class="success">'.lang_rep($lang['b_264'], array('-NUM-' => $pack['coins'].' '.$lang['b_246'])).'</div></div>';
		}
	}
?>
<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300italic,700' rel='stylesheet' type='text/css' /><link rel="stylesheet" type="text/css" href="theme/<?=$site['theme']?>/vip.css" />
<div class="content"><h2 class="title"><?=$lang['b_08']?> <span style="float:right"><a href="vip.php?history"><?=$lang['b_247']?></a></span></h2><?=$mesaj?> 
<div class="infobox t-left"><b><?=$lang['b_192']?>:</b> <?=($data['premium'] > 0 ? $lang['b_194'] : $lang['b_193'])?><?if($data['premium'] > 0){?><br /><b><?=$lang['b_159']?>:</b> <?=date('d-m-Y H:i', $data['premium'])?><?}?></div>
	<div class="vip_t_row">
        <div class="first_child">
       		<div class="title_desc_colum"><?=$lang['b_192']?></div>
        </div>
        <ul>
            <li class="desc_colum"><?=$lang['b_195']?></li>
            <li class="desc_colum"><?=$lang['b_196']?></li>
			<li class="desc_colum"><?=$lang['b_223']?></li>
			<?if($site['clicks_limit'] > 0){?><li class="desc_colum"><?=$lang['b_298']?></li><?}?>
			<?if($site['banner_system'] != 0){?><li class="desc_colum"><?=$lang['b_233']?></li><?}?>
			<?if($site['target_system'] != 2){?><li class="desc_colum"><?=$lang['b_242']?></li><?}?>
            <?if($site['transfer_status'] != 1){?><li class="desc_colum"><?=$lang['b_232']?></li><?}?>
            <li class="desc_colum"><?=$lang['b_197']?></li>
            <li class="desc_colum"><?=$lang['b_293']?></li>
			<li class="desc_colum"><?=$lang['b_325']?></li>
            <li class="desc_colum"><?=$lang['b_198']?></li>
    	</ul>
    </div>
    <div class="vip_t_row">
        <div class="first_child">
            <div class="sub_pack"><?=$lang['b_193']?></div>
        </div>
        <ul>
            <li><strong><?=$site['free_cpc']?></strong> <?=$lang['b_36']?></li>
            <li><strong><?=$site['daily_bonus']?></strong> <?=$lang['b_156']?></li>
			<li><strong><?=$site['c_c_limit']?></strong> <?=$lang['b_224']?></li>
			<?if($site['clicks_limit'] > 0){?><li><strong><?=number_format($site['clicks_limit'])?></strong> <?=$lang['b_141']?></li><?}?>
			<?if($site['banner_system'] != 0){?><li class="img_<?=($site['banner_system'] == 2 ? 'no' : 'yes')?>"></li><?}?>
			<?if($site['target_system'] != 2){?><li class="img_<?=($site['target_system'] == 1 ? 'no' : 'yes')?>"></li><?}?>
            <?if($site['transfer_status'] != 1){?><li class="img_<?=($site['transfer_status'] == 2 ? 'no' : 'yes')?>"></li><?}?>
            <li class="img_no"></li>
			<li class="img_no"></li>
			<li class="img_no"></li>
            <li class="img_no"></li>
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
            <li><strong><?=$site['daily_bonus_vip']?></strong> <?=$lang['b_156']?></li>
			<li><strong><?=$site['c_v_limit']?></strong> <?=$lang['b_224']?></li>
			<?if($site['clicks_limit'] > 0){?><li><strong><?=$lang['b_299']?></strong></li><?}?>
			<?if($site['banner_system'] != 0){?><li class="img_yes"></li><?}?>
			<?if($site['target_system'] != 2){?><li class="img_yes"></li><?}?>
            <?if($site['transfer_status'] != 1){?><li class="img_yes"></li><?}?>
            <li class="img_yes"></li>
			<li class="img_yes"></li>
            <li class="img_yes"></li>
			<li class="img_yes"></li>
        </ul>
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
        </div><?}?>
    </div>
</div>
<?}include('footer.php');?>