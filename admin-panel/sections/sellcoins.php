<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

$mesaj = '';
if(isset($_GET['del']) && is_numeric($_GET['del'])){
	$del = $db->EscapeString($_GET['del']); 
	$db->Query("DELETE FROM `sell_coins` WHERE `id`='".$del."'");
}
?>
<section id="content" class="container_12 clearfix ui-sortable">
	<h1 class="grid_12">Coins Packs</h1>
	<div class="grid_12">
		<div class="box">
			<table class="styled">
				<thead>
					<tr>
						<th width="25">ID</th>
						<th>Seller</th>
						<th>Coins</th>
						<th>Price</th>
						<th>Coin Value</th>
						<th>Added Time</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
<?php
	$packs = $db->QueryFetchArrayAll("SELECT a.*, b.login FROM sell_coins a LEFT JOIN users b ON b.id = a.seller_id WHERE a.sold = '0' ORDER BY a.id ASC");
	foreach($packs as $pack) {
?>	
					<tr>
						<td><?=$pack['id']?></td>
						<td><a href="index.php?x=users&edit=<?=$pack['seller_id']?>"><?=$pack['login']?></a></td>
						<td><?=number_format($pack['coins'], 0)?> coins</td>
						<td>$<?=$pack['price']?></td>
						<td>$<?=$pack['coin_value']?></td>
						<td><?=date('d M Y - H:i', $pack['added_time'])?></td>
						<td class="center">
							<a href="index.php?x=sellcoins&del=<?=$pack['id']?>" class="button small grey tooltip" data-gravity=s title="Remove"><i class="icon-remove"></i></a>
						</td>
					 </tr>
<?}?>
				</tbody>
			</table>
		</div>
	</div>
</section>