<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

$mesaj = '';
if(isset($_GET['del']) && is_numeric($_GET['del'])){
	$del = $db->EscapeString($_GET['del']);
	$db->Query("DELETE FROM `activity_rewards` WHERE `id`='".$del."'");
}elseif(isset($_GET['edit'])){
	$edit = $db->EscapeString($_GET['edit']);
	$pack = $db->QueryFetchArray("SELECT * FROM `activity_rewards` WHERE `id`='".$edit."'");
	if(isset($_POST['submit'])){
		$exchanges = $db->EscapeString($_POST['exchanges']);
		$reward = $db->EscapeString($_POST['reward']);
		$type = $db->EscapeString($_POST['type']);
		$type = ($type < 0 ? 0 : $type > 1 ? 1 : $type);
		
		$db->Query("UPDATE `activity_rewards` SET `exchanges`='".$exchanges."', `reward`='".$reward."', `type`='".$type."' WHERE `id`='".$edit."'");

		$mesaj = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Reward was successfully edited!</div>';
	}
}elseif(isset($_GET['add'])){
	if(isset($_POST['submit'])){
		$exchanges = $db->EscapeString($_POST['exchanges']);
		$reward = $db->EscapeString($_POST['reward']);
		$type = $db->EscapeString($_POST['type']);
		$type = ($type < 0 ? 0 : $type > 1 ? 1 : $type);
	
		if(!empty($exchanges) && is_numeric($reward)){
			$db->Query("INSERT INTO `activity_rewards` (exchanges, reward, type) VALUES('".$exchanges."', '".$reward."', '".$type."')");

			$mesaj = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Reward was successfuly added!</div>';
		}else{
			$mesaj = '<div class="alert error"><span class="icon"></span><strong>Error!</strong> You have to complete all fields!</div>';
		}
	}
}
if(isset($_GET['edit']) && $pack['id'] != ''){
?>
<section id="content" class="container_12 clearfix"><?=$mesaj?>
	<div class="grid_12">
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Edit Reward</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Required Exchanges</strong></label>
					<div><input type="text" name="exchanges" value="<?=(isset($_POST['exchanges']) ? $_POST['exchanges'] : $pack['exchanges'])?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Reward Type</strong></label>
					<div><select name="type"><option value="0">Coins</option><option value="1"<?=($pack['type'] == 1 ? ' selected' : '')?>>VIP Days</option></select></div>
				</div>
				<div class="row">
					<label><strong>Reward</strong></label>
					<div><input type="text" name="reward" value="<?=(isset($_POST['reward']) ? $_POST['reward'] : $pack['reward'])?>" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="submit" value="Submit" />
				</div>
			</div>
        </form>
	</div>
</section>
<?}elseif(isset($_GET['add'])){?>
<section id="content" class="container_12 clearfix"><?=$mesaj?>
	<div class="grid_12">
		<form method="post" class="box">
			<div class="header">
				<h2>Add Reward</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Required Exchanges</strong></label>
					<div><input type="text" name="exchanges" placeholder="250" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Reward Type</strong></label>
					<div><select name="type"><option value="0">Coins</option><option value="1">VIP Days</option></select></div>
				</div>
				<div class="row">
					<label><strong>Reward</strong></label>
					<div><input type="text" name="reward" placeholder="50" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="submit" value="Submit" />
				</div>
			</div>
        </form>
	</div>
</section>
<?
}elseif(isset($_GET['claims'])){
	$pagina = (isset($_GET['p']) ? $_GET['p'] : 0);
	$limit = 20;
	$start = (is_numeric($pagina) && $pagina > 0 ? ($pagina-1)*$limit : 0);

	$total_pages = $db->QueryGetNumRows("SELECT * FROM activity_rewards_claims");
	include('../system/libs/apaginate.php');
?>
<section id="content" class="container_12 clearfix ui-sortable">
	<h1 class="grid_12">Claims (<?=number_format($total_pages)?>)</h1>
	<div class="grid_12">
		<div class="box">
			<table class="styled">
				<thead>
					<tr>
						<th width="25">#</th>
						<th>User ID</th>
						<th>Reward</th>
						<th>Requirements</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>
<?
  $j = $start;
  $packs = $db->QueryFetchArrayAll("SELECT a.*, b.exchanges, b.reward, b.type, c.login FROM activity_rewards_claims a LEFT JOIN activity_rewards b ON b.id = a.reward_id LEFT JOIN users c ON c.id = a.user_id ORDER BY a.date DESC LIMIT ".$start.",".$limit."");
  foreach($packs as $pack){
  $j++;
?>	
					<tr>
						<td><?=$j?></td>
						<td><a href="index.php?x=users&edit=<?=$pack['user_id']?>"><?=$pack['login']?></a></td>
						<td>User received <b><?=number_format($pack['reward']).' '.($pack['type'] == 1 ? 'VIP Days' : 'Coins')?></b></td>
						<td><?=number_format($pack['exchanges'])?> exchanges</td>
						<td><?=date('Y M d - H:i', $pack['date'])?></td>
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
<?}else{?>
<section id="content" class="container_12 clearfix ui-sortable">
	<h1 class="grid_12">Rewards</h1>
	<div class="grid_12">
		<div class="box">
			<table class="styled">
				<thead>
					<tr>
						<th width="25">ID</th>
						<th>Requirements</th>
						<th>Reward</th>
						<th>Claims</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
<?
  $packs = $db->QueryFetchArrayAll("SELECT * FROM `activity_rewards` ORDER BY `id` ASC");
  foreach($packs as $pack){
?>	
					<tr>
						<td><?=$pack['id']?></td>
						<td>User must have <b><?=number_format($pack['exchanges'])?> exchanges</b></td>
						<td><?=number_format($pack['reward']).' '.($pack['type'] == 1 ? 'VIP Days' : 'Coins')?></td>
						<td><?=number_format($pack['claims'])?> claims</td>
						<td class="center">
							<a href="index.php?x=rewards&edit=<?=$pack['id']?>" class="button small grey tooltip" data-gravity=s title="Edit"><i class="icon-pencil"></i></a>
							<a href="index.php?x=rewards&del=<?=$pack['id']?>" class="button small grey tooltip" data-gravity=s title="Remove"><i class="icon-remove"></i></a>
						</td>
					 </tr>
<?}?>
				</tbody>
			</table>
		</div>
	</div>
</section>
<?}?>