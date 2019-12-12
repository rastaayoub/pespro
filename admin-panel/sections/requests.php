<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

$msg = '';
if(isset($_GET['pay']) && is_numeric($_GET['pay'])){
	$pid = $db->EscapeString($_GET['pay']);
	$req = $db->QueryFetchArray("SELECT id,user,amount,paid,gateway FROM `requests` WHERE `id`='".$pid."'");
	if(!empty($req['id'])){
		if($req['paid'] != 1){
			if($req['gateway'] == 'accb'){
				$db->Query("UPDATE `users` SET `account_balance`=`account_balance`+'".$req['amount']."' WHERE `id`='".$req['user']."'");
			}
			$db->Query("UPDATE `requests` SET `paid`='1' WHERE `id`='".$pid."'");
		}
		$msg = '<div class="alert success">Request marked as paid!</a></div>';
	}else{
		$msg = '<div class="alert error">This request doesn\'t exist!</a></div>';
	}
}
if(isset($_GET['reject']) && is_numeric($_GET['reject'])){
	$pid = $db->EscapeString($_GET['reject']);
	$rej = $db->QueryFetchArray("SELECT id,reason FROM `requests` WHERE `id`='".$pid."'");
	if(empty($rej['id'])){
		redirect("index.php?x=requests");
	}

	if(isset($_POST['reject'])){
		if($_POST['reason'] != ''){
			$reason = $db->EscapeString($_POST['reason']);
			$db->Query("UPDATE `requests` SET `paid`='2', `reason`='".$reason."' WHERE `id`='".$pid."'");
			$msg = '<div class="alert error">Request marked as rejected!</a></div>';
		}else{
			$msg = '<div class="alert error">Please write the reason!</a></div>';
		}
	}
?>
<section id="content" class="container_12 clearfix"><?=$msg?>
	<div class="grid_12">
		<form method="post" class="box">
			<div class="header">
				<h2>Reject Request</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Reason</strong><small>Why do you want to reject that request?</small></label>
					<div><textarea name="reason"><?=(isset($_POST['reason']) ? $_POST['reason'] : $rej['reason'])?></textarea></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" value="Reject" name="reject" />
				</div>
			</div>
		</form>
	</div>
</section>
<?
}elseif(isset($_GET['info']) && is_numeric($_GET['info'])){
	$pid = $db->EscapeString($_GET['info']);
	$info = $db->QueryFetchArray("SELECT * FROM `requests` WHERE `id`='".$pid."'");
	if(empty($info['id'])){
		redirect("index.php?x=requests");
	}

	$user = $db->QueryFetchArray("SELECT * FROM `users` WHERE `id`='".$info['user']."'");
	$refs = $db->QueryGetNumRows("SELECT id FROM `users` WHERE `ref`='".$user['id']."'");
	$refp = $db->QueryGetNumRows("SELECT id FROM `users` WHERE `ref`='".$user['id']."' AND `ref_paid`='1'");
	$trans = $db->QueryFetchArray("SELECT id, COUNT(id) AS total, SUM(money) AS money FROM `transactions` WHERE `user_id`='".$user['id']."'");
	$conv = $db->QueryFetchArray("SELECT COUNT(`id`) AS `total`, SUM(`coins`) AS `coins`, SUM(`cash`) AS `money` FROM `coins_to_cash` WHERE `user`='".$user['id']."'");
?>
<section id="content" class="container_12 clearfix">
	<div class="grid_6">
		<div class="box">
			<div class="header">
				<h2>Payout Request</h2>
			</div>
            <div class="content">
				<p><strong>User ID:</strong> <a href="index.php?x=users&edit=<?=$info['user']?>"><?=$info['user']?></a></p>
				<p><strong>Payment Gateway:</strong> <?=ucfirst($info['gateway'])?></p>
				<p><strong>Payment Email:</strong> <?=$info['paypal']?></p>
				<p><strong>Payment Amount:</strong> <font color="green"><b><?=get_currency_symbol($site['currency_code']).$info['amount']?></b></font></p>
				<hr>
				<p align="center">
					<a href="index.php?x=requests&pay=<?=$info['id']?>" class="button small grey tooltip" title="Accept"><i class="icon-ok"></i></a>
					<a href="index.php?x=requests&reject=<?=$info['id']?>" class="button small grey tooltip" title="Reject"><i class="icon-remove"></i></a>
				</p>
			</div>
		</div>
	</div>
	<div class="grid_6">
		<div class="box">
			<div class="header">
				<h2>User Info</h2>
			</div>
            <div class="content">
				<p><strong>Username:</strong> <a href="index.php?x=users&edit=<?=$user['id']?>"><?=$user['login']?></a></p>
				<p><strong>User Referrals:</strong> <a href="index.php?x=users&refid=<?=$user['id']?>"><?=number_format($refs)?></a> (<?=number_format($refp)?> with paid commission)</p>
				<p><strong>Paid Requests:</strong> <?=$db->QueryGetNumRows("SELECT id FROM `requests` WHERE `user`='".$user['id']."' AND `paid`='1'")?></p>
				<p><strong>Added Funds:</strong> <font color="green"><b><?=get_currency_symbol($site['currency_code']).number_format($trans['money'], 2)?></b></font> (<?=number_format($trans['total'])?> transactions)</p>
				<p><strong>Converted Coins:</strong> <?=number_format($conv['coins'])?> coins => <font color="green"><b><?=get_currency_symbol($site['currency_code']).number_format($conv['money'], 2)?></b></font> (<?=$conv['total']?> conversions)</p>
			</div>
		</div>
	</div>
</section>
<?}else{?>
<section id="content" class="container_12 clearfix ui-sortable"><?=$msg?>
	<h1 class="grid_12">Payment Requests</h1>
	<div class="grid_12">
		<div class="box">
			<table class="styled">
				<thead>
					<tr>
						<th width="10">#</th>
						<th>User ID</th>
						<th>Amount</th>
						<th>Payment Email</th>
						<th>Date</th>
						<th>Gateway</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
<?
$pagina = (isset($_GET['p']) ? $_GET['p'] : 0);
$limit = 20;
$start = (is_numeric($pagina) && $pagina > 0 ? ($pagina-1)*$limit : 0);

$total_pages = $db->QueryGetNumRows("SELECT id FROM `requests` WHERE `gateway`!='accb'");
include('../system/libs/apaginate.php');

$reqs = $db->QueryFetchArrayAll("SELECT * FROM `requests` WHERE `gateway`!='accb' ORDER BY `date` DESC LIMIT ".$start.",".$limit."");
foreach($reqs as $req){
?>	
					<tr>
						<td><a href="index.php?x=requests&info=<?=$req['id']?>"><?=$req['id']?></a></td>
						<td><a href="index.php?x=users&edit=<?=$req['user']?>"><?=$req['user']?></a></td>
						<td><?=$req['amount'].' '.get_currency_symbol($site['currency_code'])?></td>
						<td><?=(!empty($req['paypal']) ? $req['paypal'] : 'N/A')?></td>
						<td><?=$req['date']?></td>
						<td><?=ucfirst($req['gateway'])?></td>
						<td><?if($req['paid'] == 0){?><font color="orange">Waiting<?}elseif($req['paid'] == 2){?><font color="red">Rejected<?}else{?><font color="green">Paid<?}?></font></td>
						<td class="center">
							<a href="index.php?x=requests&pay=<?=$req['id']?>" class="button small grey tooltip" title="Accept"><i class="icon-ok"></i></a>
							<a href="index.php?x=requests&reject=<?=$req['id']?>" class="button small grey tooltip" title="Reject"><i class="icon-remove"></i></a>
						</td>
					</tr>
<?}?>
				</tbody>
			</table>
			<?if($total_pages > $limit){?>
			<div class="dataTables_wrapper">
			<div class="footer">
				<div class="dataTables_paginate paging_full_numbers">
					<a class="first paginate_button" href="<?=GetHref('p=1')?>">First</a>
					<?=(($pagina <= 1 || $pagina == '') ? '<a class="previous paginate_button">&laquo;</a>' : '<a class="previous paginate_button" href="'.GetHref('p='.($pagina-1)).'">&laquo;</a>')?>
					<span><?=$pagination?></span>
					<?=(($pagina >= $lastpage) ? '<a class="next paginate_button">&raquo;</a>' : '<a class="next paginate_button" href="'.GetHref('p='.($pagina == 0 ? 2 : $pagina+1)).'">&raquo;</a>')?>
					<a class="last paginate_button" href="<?=GetHref('p='.$lastpage)?>">Last</a>
				</div>
			</div>
			</div>
			<?}?>
		</div>
	</div>
</section>
<?}?>