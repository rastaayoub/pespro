<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

$mesaj = '';
if(isset($_GET['del']) && is_numeric($_GET['del'])){
	$del = $db->EscapeString($_GET['del']);
	$db->Query("DELETE FROM `ad_packs` WHERE `id`='".$del."'");
}elseif(isset($_GET['b_del']) && is_numeric($_GET['b_del'])){
	$del = $db->EscapeString($_GET['b_del']);
	$db->Query("DELETE FROM `banners` WHERE `id`='".$del."'");
}elseif(isset($_GET['edit'])){
	$edit = $db->EscapeString($_GET['edit']);
	$pack = $db->QueryFetchArray("SELECT * FROM `ad_packs` WHERE `id`='".$edit."'");
	if(isset($_POST['submit'])){
		$days = $db->EscapeString($_POST['days']);
		$price = $db->EscapeString($_POST['price']);
		$type = $db->EscapeString($_POST['type']);
		$type = ($type < 0 ? 0 : ($type > 1 ? 1 : $type));
	
		$db->Query("UPDATE `ad_packs` SET `days`='".$days."', `price`='".$price."', `type`='".$type."' WHERE `id`='".$edit."'");
		$mesaj = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Banner Ads pack was successfully edited!</div>';
	}
}elseif(isset($_GET['b_edit'])){
	$edit = $db->EscapeString($_GET['b_edit']);
	$banner = $db->QueryFetchArray("SELECT * FROM `banners` WHERE `id`='".$edit."'");
	if(isset($_POST['submit'])){
		$banner_url	= $db->EscapeString($_POST['b_url']);
		$site_url = $db->EscapeString($_POST['s_url']);
	
		$db->Query("UPDATE `banners` SET `banner_url`='".$banner_url."', `site_url`='".$site_url."' WHERE `id`='".$edit."'");
		$mesaj = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Banner was successfully edited!</div>';
	}
}elseif(isset($_GET['add'])){
	if(isset($_POST['submit'])){
		$days = $db->EscapeString($_POST['days']);
		$price = $db->EscapeString($_POST['price']);
		$type = $db->EscapeString($_POST['type']);
		$type = ($type < 0 ? 0 : ($type > 1 ? 1 : $type));
	
		if(is_numeric($days) && $days > 0 && is_numeric($price) && $price > 0){
			$db->Query("INSERT INTO `ad_packs` (days, price, type) VALUES('".$days."', '".$price."', '".$type."')");
			$mesaj = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Banner Ads pack was successfuly added!</div>';
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
						<label><strong>Days</strong></label>
						<div><input type="text" name="days" value="<?=(isset($_POST['days']) ? $_POST['days'] : $pack['days'])?>" required="required" /></div>
					</div>
					<div class="row">
						<label><strong>Price (cash)</strong></label>
						<div><input type="text" name="price" value="<?=(isset($_POST['price']) ? $_POST['price'] : $pack['price'])?>" required="required" /></div>
					</div>
					<div class="row">
						<label><strong>Banner Size</strong></label>
						<div><select name="type"><option value="0">Size 1 - 468x60</option><option value="1"<?=(isset($_POST['type']) && $_POST['type'] == 1 ? ' selected' : ($pack['type'] == 1 ? ' selected' : ''))?>>Size 2 - 728x90</option></select></div>
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
<?}elseif(isset($_GET['b_edit']) && $banner['id'] != ''){?>
<section id="content" class="container_12 clearfix" data-sort=true><?=$mesaj?>
	<div class="grid_12">
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Edit Banner</h2>
			</div>
				<div class="content">
					<div class="row">
						<label><strong>Expiration date</strong></label>
						<div><input type="text" value="<?=($banner['expiration'] == 0 ? 'Expired' : date('d-m-Y H:i', $banner['expiration']))?>" disabled /></div>
					</div>
					<div class="row">
						<label><strong>Banner URL</strong></label>
						<div><input type="text" name="b_url" value="<?=(isset($_POST['b_url']) ? $_POST['b_url'] : $banner['banner_url'])?>" required="required" /></div>
					</div>
					<div class="row">
						<label><strong>Site URL</strong></label>
						<div><input type="text" name="s_url" value="<?=(isset($_POST['s_url']) ? $_POST['s_url'] : $banner['site_url'])?>" required="required" /></div>
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
<section id="content" class="container_12 clearfix" data-sort=true><?=$mesaj?>
	<div class="grid_12">
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Add Pack</h2>
			</div>
				<div class="content">
					<div class="row">
						<label><strong>Days</strong></label>
						<div><input type="text" name="days" value="0" required="required" /></div>
					</div>
					<div class="row">
						<label><strong>Price (cash)</strong></label>
						<div><input type="text" name="price" value="0.00" required="required" /></div>
					</div>
					<div class="row">
						<label><strong>Banner Size</strong></label>
						<div><select name="type"><option value="0">Size 1 - 468x60</option><option value="1">Size 2 - 728x90</option></select></div>
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
<?}elseif(isset($_GET['packs'])){?>
<section id="content" class="container_12 clearfix ui-sortable" data-sort=true>
		<h1 class="grid_12">Banner Packs</h1>
			<div class="grid_12">
				<div class="box">
                    <table class="styled">
                        <thead>
                            <tr>
                                <th width="25">Pack ID</th>
                                <th>Days</th>
                                <th>Price (cash)</th>
								<th>Banner Size</th>
								<th>Bought</th>
								<th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
<?
$packs = $db->QueryFetchArrayAll("SELECT * FROM `ad_packs` ORDER BY `id` ASC");
foreach($packs as $pack){
?>	
                            <tr>
                                <td><?=$pack['id']?></td>
                                <td><?=$pack['days']?> days</td>
								<td><?=$pack['price'].' '.get_currency_symbol($site['currency_code'])?></td>
								<td><?=($pack['type'] == 1 ? 'Size 2 - 728x90' : 'Size 1 - 468x60')?></td>
								<td><?=$pack['bought']?> times</td>
								<td class="center">
									<a href="index.php?x=banners&edit=<?=$pack['id']?>" class="button small grey tooltip" data-gravity=s title="Edit"><i class="icon-pencil"></i></a>
									<a href="index.php?x=banners&del=<?=$pack['id']?>" class="button small grey tooltip" data-gravity=s title="Remove"><i class="icon-remove"></i></a>
								</td>
                            </tr>
<?}?>
                        </tbody>
                    </table>
				</div>
			</div>
		</section>
<?}else{?>
<section id="content" class="container_12 clearfix ui-sortable" data-sort=true>
	<h1 class="grid_12">Banners</h1>
	<div class="grid_12">
		<div class="box">
			<table class="styled">
				<thead>
					<tr>
						<th>ID</th>
						<th>User ID</th>
						<th>Banner</th>
						<th>Impressions</th>
						<th>Clicks</th>
						<th>Size</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
<?
$pagina = (isset($_GET['p']) ? $_GET['p'] : 0);
$limit = 15;
$start = (is_numeric($pagina) && $pagina > 0 ? ($pagina-1)*$limit : 0);

$total_pages = $db->QueryGetNumRows("SELECT * FROM `banners`");
include('../system/libs/apaginate.php');

$banners = $db->QueryFetchArrayAll("SELECT * FROM `banners` ORDER BY `id` ASC LIMIT ".$start.",".$limit."");
foreach($banners as $banner){
?>	
					<tr>
						<td><?=$banner['id']?></td>
						<td><a href="index.php?x=users&edit=<?=$banner['user']?>"><?=$banner['user']?></a></td>
						<td><a href="<?=$banner['site_url']?>" title="<?=$banner['site_url']?>" target="_blank"><img src="<?=$banner['banner_url']?>" width="234" border="0" /></a></td>
						<td><?=number_format($banner['views'])?></td>
						<td><?=number_format($banner['clicks'])?></td>
						<td><?=($banner['type'] == 1 ? '728x90' : '468x60')?></td>
						<td><?=($banner['expiration'] != 0 ? '<font color="green">'.$lang['b_180'].'</font>' : ($mysite['status'] == 2 ? '<font color="red"><b>'.$lang['b_78'].'</b></font>' : '<font color="red">'.$lang['b_181'].'</font>'))?></td>
						<td class="center">
							<a href="index.php?x=banners&b_edit=<?=$banner['id']?>" class="button small grey tooltip" data-gravity=s title="Edit"><i class="icon-pencil"></i></a>
							<a href="index.php?x=banners&b_del=<?=$banner['id']?>" onclick="return confirm('You sure you want to delete this banner?');" class="button small grey tooltip" data-gravity=s title="Remove"><i class="icon-remove"></i></a>
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