<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

$mesaj = '';
if(isset($_GET['edit'])){
	$id = $db->EscapeString($_GET['edit']);
	$edit = $db->QueryFetchArray("SELECT * FROM `coupons` WHERE `id`='".$id."'");

	if(isset($_POST['submit']) && !empty($_POST['code']) && !empty($_POST['uses']) && is_numeric($_POST['coins']) && is_numeric($_POST['exchanges'])){
		$code = $db->EscapeString($_POST['code']);
		$uses = $db->EscapeString($_POST['uses']);
		$coins = $db->EscapeString($_POST['coins']);
		$type = $db->EscapeString($_POST['type']);
		$exchanges = $db->EscapeString($_POST['exchanges']);

		$db->Query("UPDATE `coupons` SET `code`='".$code."', `coins`='".$coins."', `uses`='".$uses."', `type`='".$type."', `exchanges`='".$exchanges."' WHERE `id`='".$id."'");
		$mesaj = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Coupon was successfuly updated!</div>';
	}
}elseif(isset($_GET['del']) && is_numeric($_GET['del'])){
	$del = $db->EscapeString($_GET['del']);
	$db->Query("DELETE FROM `coupons` WHERE `id`='".$del."'");
	$db->Query("DELETE FROM `used_coupons` WHERE `coupon_id`='".$del."'");
}elseif(isset($_GET['add'])){
	$n1 = rand(1000,9999);
	$n2 = rand(1000,9999);
	$n3 = rand(1000,9999);
	$n4 = rand(1000,9999);
	$code = $n1."-".$n2."-".$n3."-".$n4;

	if(isset($_POST['add'])){
		$code = $db->EscapeString($_POST['code']);
		$coins = $db->EscapeString($_POST['coins']);
		$uses = $db->EscapeString($_POST['uses']);
		$type = $db->EscapeString($_POST['type']);
		$exchanges = $db->EscapeString($_POST['exchanges']);
	
		if(is_numeric($coins) && $coins > 0 && !empty($uses) && !empty($code) && is_numeric($exchanges)){
			$db->Query("INSERT INTO `coupons`(code, coins, uses, type, exchanges) values('".$code."', '".$coins."', '".$uses."', '".$type."', '".$exchanges."')");
			$mesaj = '<div class="alert success"><span class="icon"></span><strong>SUCCESS:</strong> Your coupon code is: '.$code.'</div>';
		}else{
			$mesaj = '<div class="alert error"><span class="icon"></span><strong>ERROR:</strong> You have to complete all fields!</div>';
		}
	}
}
if(isset($_GET['edit']) && $edit['id'] != ""){
?>
<section id="content" class="container_12 clearfix"><?=$mesaj?>
	<div class="grid_12">
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Edit Coupon</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Code</strong></label>
					<div><input type="text" name="code" value="<?=(isset($_POST['code']) ? $_POST['code'] : $edit['code'])?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Coupon Type</strong><small>Chose between Coins or VIP Days</small></label>
					<div><select name="type"><option value="0">Coins</option><option value="1"<?=(!isset($_POST['type']) && $edit['type'] == 1 ? ' selected' : (isset($_POST['type']) && $_POST['type'] == 1 ? ' selected' : ''))?>>VIP</option></select></div>
				</div>
				<div class="row">
					<label><strong>Coins / VIP Days</strong></label>
					<div><input type="text" name="coins" value="<?=(isset($_POST['coins']) ? $_POST['coins'] : $edit['coins'])?>" /></div>
				</div>
				<div class="row">
					<label><strong>Requirements</strong><small>Exchanges required to use this coupon</small></label>
					<div><input type="text" name="exchanges" value="<?=(isset($_POST['exchanges']) ? $_POST['exchanges'] : $edit['exchanges'])?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Uses</strong><small>(u = unlimited)</small></label>
					<div><input type="text" name="uses" value="<?=(isset($_POST['uses']) ? $_POST['uses'] : $edit['uses'])?>" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" value="Submit" name="submit" />
				</div>
			</div>
		</form>
	</div>
</section>
<?}elseif(isset($_GET['add'])){?>
<section id="content" class="container_12 clearfix"><?=$mesaj?>
	<div class="grid_12">
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Add Coupon</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Coupon Code</strong></label>
					<div><input type="text" name="code" value="<? echo $code;?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Coupon Type</strong><small>Chose between Coins or VIP Days</small></label>
					<div><select name="type"><option value="0">Coins</option><option value="1">VIP</option></select></div>
				</div>
				<div class="row">
					<label><strong>Coins / VIP Days</strong></label>
					<div><input type="text" name="coins" value="10" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Requirements</strong><small>Exchanges required to use this coupon</small></label>
					<div><input type="text" name="exchanges" value="0" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Uses</strong><small>(u = unlimited)</small></label>
					<div><input type="text" name="uses" value="1" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" value="Add" name="add" />
				</div>
			</div>
		</form>
	</div>
</section>
<?}else{?>
<section id="content" class="container_12 clearfix ui-sortable">
	<h1 class="grid_12">Coupons</h1>
	<div class="grid_12">
		<div class="box">
			<table class="styled">
				<thead>
					<tr>
						<th width="10">ID</th>
						<th>Coupon</th>
						<th>Amount</th>
						<th>Requirements</th>
						<th>Available Uses</th>
						<th>Used</th>
						<th width="90">Actions</th>
					</tr>
				</thead>
				<tbody>
<?
$coupons = $db->QueryFetchArrayAll("SELECT * FROM `coupons`");
foreach($coupons as $coupon){
?>	
					<tr>
						<td><?=$coupon['id']?></td>
						<td><?=$coupon['code']?></td>
						<td><?=$coupon['coins'].($coupon['type'] == 1 ? ' VIP days' : ' coins')?></td>
						<td><?=($coupon['exchanges'] > 0 ? $coupon['exchanges'].' exchanges' : 'N/A')?></td>
						<td><?=($coupon['uses'] == 'u' ? 'Unlimited' : $coupon['uses'])?></td>
						<td><?=$coupon['used']?> times</td>
						<td class="center">
							<a href="index.php?x=coupons&edit=<?=$coupon['id']?>" class="button small grey tooltip" data-gravity=s title="Edit"><i class="icon-pencil"></i></a>
							<a href="index.php?x=coupons&del=<?=$coupon['id']?>" class="button small grey tooltip" data-gravity=s title="Remove"><i class="icon-remove"></i></a>
						</td>
					</tr>
<?}?>
				</tbody>
			</table>
		</div>
	</div>
</section>
<?}?>