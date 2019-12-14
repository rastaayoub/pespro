<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

$mesaj = '';
if(isset($_GET['del']) && is_numeric($_GET['del'])){
	$del = $db->EscapeString($_GET['del']); 
	$db->Query("DELETE FROM `c_pack` WHERE `id`='".$del."'");
}elseif(isset($_GET['edit'])){
	$edit = $db->EscapeString($_GET['edit']);
	$pack = $db->QueryFetchArray("SELECT * FROM `c_pack` WHERE `id`='".$edit."'");
	if(isset($_POST['submit'])){
		$name = $db->EscapeString($_POST['name']);
		$coins = $db->EscapeString($_POST['coins']);
		$price = $db->EscapeString($_POST['price']);

		$db->Query("UPDATE `c_pack` SET `name`='".$name."', `coins`='".$coins."', `price`='".$price."' WHERE `id`='".$edit."'");
		$mesaj = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Coins pack was successfully edited!</div>';
	}
}elseif(isset($_GET['add'])){
	if(isset($_POST['submit'])){
		$name = $db->EscapeString($_POST['name']);
		$coins = $db->EscapeString($_POST['points']);
		$price = $db->EscapeString($_POST['price']);

		if($name != '' && is_numeric($coins) && $coins > 0 && is_numeric($price) && $price > 0){
			$db->Query("INSERT INTO `c_pack` (name, coins, price) VALUES('".$name."', '".$coins."', '".$price."')");
			$mesaj = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Coins pack was successfuly added!</div>';
		}else{
			$mesaj = '<div class="alert error"><span class="icon"></span><strong>Error!</strong> You have to complete all fields!</div>';
		}
	}
}elseif(isset($_GET['settings'])){
	if(isset($_POST['submit'])){
		$posts = $db->EscapeString($_POST['set']);
		foreach ($posts as $key => $value){
			if($site[$key] != $value){
				if($key == 'c_discount'){
					$value = ($value > 99 ? 99 : ($value < 0 ? 0 : $value));
				}elseif($key == 'c_show_msg'){
					$value = ($value > 1 ? 1 : ($value < 0 ? 0 : $value));
				}

				$db->Query("UPDATE `site_config` SET `config_value`='".$value."' WHERE `config_name`='".$key."'");
				$site[$key] = $value;
			}
		}

		$mesaj = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Settings successfully changed!</div>';
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
					<label><strong>Coins</strong></label>
					<div><input type="text" name="coins" value="<?=(isset($_POST['coins']) ? $_POST['coins'] : $pack['coins'])?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Price</strong></label>
					<div><input type="text" name="price" value="<?=(isset($_POST['price']) ? $_POST['price'] : $pack['price'])?>" required="required" /></div>
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
					<label for="v1_charrange"><strong>Pack Name</strong></label>
					<div><input type="text" name="name" value="0 Coins" required="required" /></div>
				</div>
				<div class="row">
					<label for="v1_charrange"><strong>Coins Amount</strong></label>
					<div><input type="text" name="points" value="0" required="required" /></div>
				</div>
				<div class="row">
					<label for="v1_charrange"><strong>Price</strong></label>
					<div><input type="text" name="price" value="1.00" required="required" /></div>
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
<?}elseif(isset($_GET['settings'])){?>
<section id="content" class="container_12 clearfix"><?=$mesaj?>
	<div class="grid_12">
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Coins Packs Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Discount %</strong><small>This discount will be applied to all packs</small></label>
					<div><input type="text" name="set[c_discount]" value="<?=$site['c_discount']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Show message on Homepage</strong></label>
					<div><select name="set[c_show_msg]"><option value="0">No</option><option value="1"<?=($site['c_show_msg'] == 1 ? ' selected' : '')?>>Yes</option></select></div>
				</div>
				<div class="row">
					<label><strong>Text on Homepage</strong></label>
					<div><textarea name="set[c_text_index]" required="required"><?=ClearText($site['c_text_index'])?></textarea></div>
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
						<th>Coins</th>
						<th>Price</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
<?
$packs = $db->QueryFetchArrayAll("SELECT * FROM `c_pack` ORDER BY `id` ASC");
foreach($packs as $pack){
?>	
					<tr>
						<td><?=$pack['id']?></td>
						<td><?=$pack['name']?></td>
						<td><?=$pack['coins']?></td>
						<td><?=$pack['price']?> $</td>
						<td class="center">
							<a href="index.php?x=packs&edit=<?=$pack['id']?>" class="button small grey tooltip" data-gravity=s title="Edit"><i class="icon-pencil"></i></a>
							<a href="index.php?x=packs&del=<?=$pack['id']?>" class="button small grey tooltip" data-gravity=s title="Remove"><i class="icon-remove"></i></a>
						</td>
					 </tr>
<?}?>
				</tbody>
			</table>
		</div>
	</div>
</section>
<?}?>