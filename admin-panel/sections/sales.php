<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

if(isset($_GET['add']) && is_numeric($_GET['add'])){
	$add = $db->EscapeString($_GET['add']); 
	$trans = $db->QueryFetchArray("SELECT id,user,money,paid FROM `transactions` WHERE `id`='".$add."'");
	if($trans['paid'] != 1){
		$user = $db->QueryFetchArray("SELECT id FROM `users` WHERE `login`='".$trans['user']."'");
		if($user['id'] > 0){
			$db->Query("UPDATE `users` SET `account_balance`=`account_balance`+'".$trans['money']."' WHERE `id`='".$user['id']."'");
		}
		$db->Query("UPDATE `transactions` SET `paid`='1' WHERE `id`='".$add."'");
		$mesaj = '<div class="alert success"><span class="icon"></span><strong>SUCCESS:</strong> Transaction was approved and <i>'.$trans['user'].'</i> received '.$trans['points'].($trans['type'] == 1 ? ' VIP Days' : ' coins').'!</div>';
	}else{
		$mesaj = '<div class="alert error"><span class="icon"></span><strong>ERROR:</strong> Transaction already approved!</div>';
	}
}

$type = 0;
$db_special = '';
if(isset($_GET['pending'])){
	$type = 1;
	$db_special = " WHERE `paid`='0'";
}

$pagina = (isset($_GET['p']) ? $_GET['p'] : 0);
$limit = 20;
$start = (is_numeric($pagina) && $pagina > 0 ? ($pagina-1)*$limit : 0);

$total_pages = $db->QueryGetNumRows("SELECT id FROM `transactions`".$db_special);
include('../system/libs/apaginate.php');
?>
<section id="content" class="container_12 clearfix"><?=$mesaj?>
	<h1 class="grid_12">Added Funds</h1>
	<div class="grid_12">
		<div class="box">
			<table class="styled">
				<thead>
					<tr>
						<th width="25">#</th>
						<th>User</th>
						<th>Transaction ID</th>
						<th>IP</th>
						<th>Money</th>
						<th>Gateway</th>
						<th>Status</th>
						<th>Date</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
<?
  $trans = $db->QueryFetchArrayAll("SELECT * FROM `transactions`".$db_special." ORDER BY `date` DESC LIMIT ".$start.",".$limit."");
  foreach($trans as $tra){
?>	
					<tr>
						<td><?=$tra['id']?></td>
						<td><?=(!empty($tra['user_id']) ? '<a href="index.php?x=users&edit='.$tra['user_id'].'">'.$tra['user'].'</a>' : $tra['user'])?></td>
						<td><?=(empty($tra['trans_id']) ? 'N/A' : $tra['trans_id'])?></td>
						<td><?=(empty($tra['user_ip']) ? 'N/A' : '<a href="index.php?x=users&s_type=2&su='.$tra['user_ip'].'">'.$tra['user_ip'].'</a>')?></td>
						<td><?=$tra['money'].' '.get_currency_symbol($site['currency_code'])?></td>
						<td><?=ucfirst($tra['gateway'])?></td>
						<td><?=($tra['paid'] == 1 ? '<font color="green"><b>Added</b></font>' : '<b>Pending</b>')?></td>
						<td><?=$tra['date']?></td>
						<td align="center"><?=($tra['paid'] == 0 ? '<a href="index.php?x=sales&add='.$tra['id'].'" style="text-decoration:none;color:green"><b>Approve</b></a>' : 'N/A')?></td>
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