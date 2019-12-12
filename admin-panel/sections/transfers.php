<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$pagina = (isset($_GET['p']) ? $_GET['p'] : 0);
$limit = 20;
$start = (is_numeric($pagina) && $pagina > 0 ? ($pagina-1)*$limit : 0);

if(isset($_GET['group']) && is_numeric($_GET['group'])){
	$rec = $db->EscapeString($_GET['group']);
	$custom = " WHERE `receiver`='".$rec."'";
}else{
	$custom = "";
}

$total_pages = $db->QueryGetNumRows("SELECT id FROM `c_transfers`".$custom);
include('../system/libs/apaginate.php');

$total = $db->QueryGetNumRows("SELECT id FROM `c_transfers`");
?>
<section id="content" class="container_12 clearfix ui-sortable">
	<h1 class="grid_12">Coins Transfers<?=(isset($_GET['group']) && is_numeric($_GET['group']) ? ' - '.$_GET['group'] : '')?></h1>
	<div class="grid_9">
		<div class="box">
			<table class="styled">
				<thead>
					<tr>
						<th width="25">ID</th>
						<th>Sender</th>
						<th>Receiver ID</th>
						<th>Coins</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>
<?
  $sql = $db->Query("SELECT * FROM `c_transfers`".$custom." ORDER BY `date` DESC LIMIT ".$start.",".$limit."");
  for($j=1; $tra = $db->FetchArray($sql); $j++)
{
?>	
					<tr>
						<td><?=$tra['id']?></td>
						<td><?=$tra['sender']?></td>
						<td><a href="index.php?x=<?=(isset($_GET['group']) && is_numeric($_GET['group']) ? 'users&edit=' : 'transfers&group=').$tra['receiver']?>"><?=$tra['receiver']?></a></td>
						<td><?=$tra['coins']?></td>
						<td><?=date('d M Y - H:i:s', $tra['date'])?></td>
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
	<div class="grid_3">
		<div class="box">
			<div class="header">
				<h2>Total Transfers</h2>
			</div>
            <div class="content"><br />
				<div class="full-stats">
					<div class="stat list" data-list='[{"val":<?=($total == '' ? '0' : $total)?>,"format":"0","title":"Total"}]'></div>
				</div>
			</div>
		</div>
	</div>
</section>