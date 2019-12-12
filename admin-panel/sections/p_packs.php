<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

$mesaj = '';
if(isset($_GET['del']) && is_numeric($_GET['del'])){
	$del = $db->EscapeString($_GET['del']);
	$db->Query("DELETE FROM `p_pack` WHERE `id`='".$del."'");
}elseif(isset($_GET['edit'])){
	$edit = $db->EscapeString($_GET['edit']);
	$pack = $db->QueryFetchArray("SELECT * FROM `p_pack` WHERE `id`='".$edit."'");
	if(isset($_POST['submit'])){
		$name = $db->EscapeString($_POST['name']);
		$days = $db->EscapeString($_POST['days']);
		$price = $db->EscapeString($_POST['price']);
		$custom_db = ($pack['type'] == 1 ? ", `coins_price`='".$price."'" : ", `price`='".$price."'");

		$db->Query("UPDATE `p_pack` SET `name`='".$name."', `days`='".$days."'".$custom_db." WHERE `id`='".$edit."'");
		
		$mesaj = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> VIP pack was successfully edited!</div>';
	}
}elseif(isset($_GET['add'])){
	if(isset($_POST['submit'])){
		$name = $db->EscapeString($_POST['name']);
		$days = $db->EscapeString($_POST['days']);
		$price = $db->EscapeString($_POST['price']);
		$type = $db->EscapeString($_POST['type']);
		$type = ($type < 0 ? 0 : $type > 1 ? 1 : $type);
	
		if($name != '' && is_numeric($days) && $days > 0 && is_numeric($price) && $price > 0){
			if($type == 1){
				$db->Query("INSERT INTO `p_pack` (name, days, coins_price, type) VALUES('".$name."', '".$days."', '".$price."', '1')");
			}else{
				$db->Query("INSERT INTO `p_pack` (name, days, price, type) VALUES('".$name."', '".$days."', '".$price."', '0')");
			}
			$mesaj = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> VIP pack was successfuly added!</div>';
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
				<h2>Edit Pack</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Pack Name</strong></label>
					<div><input type="text" name="name" value="<?=(isset($_POST['name']) ? $_POST['name'] : $pack['name'])?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Pay with</strong></label>
					<div><input type="text" value="<?=($pack['type'] == 1 ? 'Coins' : 'Money')?>" disabled="disabled" /></div>
				</div>
				<div class="row">
					<label><strong>Days</strong></label>
					<div><input type="text" name="days" value="<?=(isset($_POST['days']) ? $_POST['days'] : $pack['days'])?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Price</strong></label>
					<div><input type="text" name="price" value="<?=(isset($_POST['price']) ? $_POST['price'] : ($pack['type'] == 1 ? $pack['coins_price'] : $pack['price']))?>" required="required" /></div>
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
				<h2>Edit Pack</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Pack Name</strong></label>
					<div><input type="text" name="name" value="0 Days" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Pay with</strong></label>
					<div><select name="type"><option value="0">Money</option><option value="1">Coins</option></select></div>
				</div>
				<div class="row">
					<label><strong>Days</strong></label>
					<div><input type="text" name="days" value="0" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Price</strong></label>
					<div><input type="text" name="price" value="0" required="required" /></div>
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
<?}else{?>
<section id="content" class="container_12 clearfix ui-sortable">
	<h1 class="grid_12">Coins Packs</h1>
	<div class="grid_12">
		<div class="box">
			<table class="styled">
				<thead>
					<tr>
						<th width="25">ID</th>
						<th>Pack Name</th>
						<th>Days</th>
						<th>Price</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
<?
  $packs = $db->QueryFetchArrayAll("SELECT * FROM `p_pack` ORDER BY `id` ASC");
  foreach($packs as $pack){
?>	
					<tr>
						<td><?=$pack['id']?></td>
						<td><?=$pack['name']?></td>
						<td><?=$pack['days']?> days</td>
						<td><?=($pack['type'] == 1 ? $pack['coins_price'].' Coins' : get_currency_symbol($site['currency_code']).' '.$pack['price'])?></td>
						<td class="center">
							<a href="index.php?x=p_packs&edit=<?=$pack['id']?>" class="button small grey tooltip" data-gravity=s title="Edit"><i class="icon-pencil"></i></a>
							<a href="index.php?x=p_packs&del=<?=$pack['id']?>" class="button small grey tooltip" data-gravity=s title="Remove"><i class="icon-remove"></i></a>
						</td>
					 </tr>
<?}?>
				</tbody>
			</table>
		</div>
	</div>
</section>
<?}?>