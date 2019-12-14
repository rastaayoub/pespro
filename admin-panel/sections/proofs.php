<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
if(isset($_GET['del_proof']) && is_numeric($_GET['del_proof'])){
	$del = $db->EscapeString($_GET['del_proof']);
	$proof = $db->QueryFetchArray("SELECT p_id FROM `payment_proofs` WHERE `id`='".$del."' LIMIT 1");
	
	$db->Query("DELETE FROM `payment_proofs` WHERE `id`='".$del."'");
	$db->Query("UPDATE `requests` SET `proof`='0' WHERE `id`='".$proof['p_id']."'");
}elseif(isset($_GET['accept'])){
	$id = $db->EscapeString($_GET['accept']);
	$db->Query("UPDATE `payment_proofs` SET `approved`='1' WHERE `id`='".$id."'");
}

if(isset($_GET['aproved'])){
	$db_value = " WHERE `approved`!='0'";
}else{
	$db_value = " WHERE `approved`='0'";
}

$pagina = (isset($_GET['p']) ? $_GET['p'] : 0);
$limit = 20;
$start = (is_numeric($pagina) && $pagina > 0 ? ($pagina-1)*$limit : 0);

$total_pages = $db->QueryGetNumRows("SELECT id FROM `payment_proofs`".$db_value);
include('../system/libs/apaginate.php');
?>
<section id="content" class="container_12 clearfix ui-sortable">
	<h1 class="grid_12">Payment Proofs</h1>
	<div class="grid_12">
		<div class="box"><form method="POST">
			<table class="styled full">
				<thead>
					<tr>
						<th width="60">ID</th>
						<th>User ID</th>
						<th>Payout ID</th>
						<th>Proof</th>
						<th>Status</th>
						<th>Date</th>
						<?if(!isset($_GET['aproved'])){?><th width="90">Actions</th><?}?>
					</tr>
				</thead>
				<tbody>
<?
  $proofs = $db->QueryFetchArrayAll("SELECT * FROM `payment_proofs`".$db_value." ORDER BY `proof_date` DESC LIMIT ".$start.",".$limit."");
  if(!$proofs){ echo '<tr><td colspan="10" class="center">Nothing found!</td></tr>'; }
  foreach($proofs as $proof){
?>	
					<tr>
						<td class="center"><b>#<?=$proof['id']?></b></td>
						<td class="center"><a href="index.php?x=users&edit=<?=$proof['u_id']?>"><b>#<?=$proof['u_id']?></b></a></td>
						<td class="center"><a href="index.php?x=requests&info=<?=$proof['p_id']?>"><b>#<?=$proof['p_id']?></b></a></td>
						<td class="center"><a href="<?=$proof['proof']?>" title="Click to enlarge" target="_blank"><img src="<?=$proof['proof']?>" style="max-width:100px;max-height:40px" alt="Click Here" /></a></td>
						<td class="center"><b><?=($proof['approved'] == '0' ? 'Waiting' : 'Approved')?><b></td>
						<td class="center"><b><?=date('d M Y H:i', $proof['proof_date'])?><b></td>
						<?if(!isset($_GET['aproved'])){?>
						<td class="center">
							<a href="index.php?x=proofs&accept=<?=$proof['id']?>" onclick="return confirm('You sure you want to approve this proof?');" class="button medium grey tooltip" title="Approve"><i class="icon-ok-sign"></i></a>
							<a href="index.php?x=proofs&del_proof=<?=$proof['id']?>" onclick="return confirm('You sure you want to delete this proof?');" class="button medium grey tooltip" title="Delete"><i class="icon-remove"></i></a>
						</td>
						<?}?>
					</tr>
					<?}?>
				</tbody>
			</table></form>
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