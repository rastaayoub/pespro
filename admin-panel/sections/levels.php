<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

$mesaj = '';
if(isset($_GET['del']) && is_numeric($_GET['del'])){
	$del = $db->EscapeString($_GET['del']);
	$db->Query("DELETE FROM `levels` WHERE `id`='".$del."'");
}elseif(isset($_GET['edit'])){
	$edit = $db->EscapeString($_GET['edit']);
	$pack = $db->QueryFetchArray("SELECT * FROM `levels` WHERE `id`='".$edit."'");
	if(isset($_POST['submit'])){
		$requirements = $db->EscapeString($_POST['requirements']);
		$level = $db->EscapeString($_POST['level']);
		$free_bonus = $db->EscapeString($_POST['free_bonus']);
		$vip_bonus = $db->EscapeString($_POST['vip_bonus']);
		$image = $db->EscapeString($_POST['image']);
		
		$db->Query("UPDATE `levels` SET `requirements`='".$requirements."', `level`='".$level."', `free_bonus`='".$free_bonus."', `vip_bonus`='".$vip_bonus."', `image`='".$image."' WHERE `id`='".$edit."'");

		$mesaj = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Level was successfully edited!</div>';
	}
}elseif(isset($_GET['add'])){
	$MAX_SIZE = 500;	// Max banner size in kb
	function getExtension($str) {
		if($str == 'image/jpeg'){
			return 'jpg';
		}elseif($str == 'image/png'){
			return 'png';
		}elseif($str == 'image/gif'){
			return 'gif';
		}
	}

	if(isset($_POST['submit'])){
		$tmpFile = $_FILES['image']['tmp_name'];
		$b_info = getimagesize($tmpFile);
		$extension = getExtension($b_info['mime']);

		$requirements = $db->EscapeString($_POST['requirements']);
		$level = $db->EscapeString($_POST['level']);
		$free_bonus = $db->EscapeString($_POST['free_bonus']);
		$vip_bonus = $db->EscapeString($_POST['vip_bonus']);
	
		if($db->QueryGetNumRows("SELECT * FROM `levels` WHERE `level`='".$level."' OR `requirements`='".$requirements."' LIMIT 1") > 0){
			$mesaj = '<div class="alert error"><span class="icon"></span><b>ERROR:</b> This level already exists!</div>';
		}elseif($b_info['mime'] != 'image/jpeg' && $b_info['mime'] != 'image/png' && $b_info['mime'] != 'image/gif'){
			$mesaj = '<div class="alert error"><span class="icon"></span><b>ERROR:</b> Your image must be png, gif or jpg!</div>';
		}elseif($b_info[0] > 32 && $b_info[1] > 32){
			$mesaj = '<div class="alert error"><span class="icon"></span><b>ERROR:</b> Your image cannot be bigger than 32x32 px!</div>';
		}elseif(filesize($tmpFile) > $MAX_SIZE*1024){
			$mesaj = '<div class="alert error"><span class="icon"></span><b>ERROR:</b> Your image must have under '.$MAX_SIZE.' KB!</div>';
		}else{
			$image_name = 'Level_'.$level.'.'.$extension;
			$copied = copy($tmpFile, BASE_PATH.'/files/levels/'.$image_name);

			if(!$copied){
				$mesaj = '<div class="alert error"><span class="icon"></span><b>ERROR:</b> Image wasn\'t uploaded, make sure that you set files permissions to 777 for "files/levels/"!</div>';
			}elseif(!is_numeric($requirements) || !is_numeric($level) || !is_numeric($free_bonus) || !is_numeric($vip_bonus)){
				$mesaj = '<div class="alert error"><span class="icon"></span><strong>Error!</strong> You have to complete all fields!</div>';
			}else{
				$db->Query("INSERT INTO `levels` (level, requirements, free_bonus, vip_bonus, image) VALUES('".$level."', '".$requirements."', '".$free_bonus."', '".$vip_bonus."', 'files/levels/".$image_name."')");

				$mesaj = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Level was successfully added!</div>';
			}
		}
	}
}
if(isset($_GET['edit']) && $pack['id'] != ''){
?>
<section id="content" class="container_12 clearfix"><?=$mesaj?>
	<div class="grid_12">
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Edit Level</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Level</strong></label>
					<div><input type="text" name="level" value="<?=(isset($_POST['level']) ? $_POST['level'] : $pack['level'])?>" placeholder="1" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Image</strong></label>
					<div><input type="text" name="image" value="<?=(isset($_POST['image']) ? $_POST['image'] : $pack['image'])?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Required Exchanges</strong></label>
					<div><input type="text" name="requirements" value="<?=(isset($_POST['requirements']) ? $_POST['requirements'] : $pack['requirements'])?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Free Bonus</strong><small>Daily bonus for free users</small></label>
					<div><input type="text" name="free_bonus" value="<?=(isset($_POST['free_bonus']) ? $_POST['free_bonus'] : $pack['free_bonus'])?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>VIP Bonus</strong><small>Daily bonus for VIP users</small></label>
					<div><input type="text" name="vip_bonus" value="<?=(isset($_POST['vip_bonus']) ? $_POST['vip_bonus'] : $pack['vip_bonus'])?>" required="required" /></div>
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
		<form method="post" enctype="multipart/form-data" class="box">
			<div class="header">
				<h2>Add Reward</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Level</strong></label>
					<div><input type="text" name="level" placeholder="1" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Image</strong></label>
					<div><input type="file" name="image" /></div>
				</div>
				<div class="row">
					<label><strong>Required Exchanges</strong></label>
					<div><input type="text" name="requirements" placeholder="100" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Free Bonus</strong><small>Daily bonus for free users</small></label>
					<div><input type="text" name="free_bonus" placeholder="20" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>VIP Bonus</strong><small>Daily bonus for VIP users</small></label>
					<div><input type="text" name="vip_bonus" placeholder="40" required="required" /></div>
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
	<h1 class="grid_12">Levels</h1>
	<div class="grid_12">
		<div class="box">
			<table class="styled">
				<thead>
					<tr>
						<th></th>
						<th>Level</th>
						<th>Requirements</th>
						<th>Free Bonus</th>
						<th>VIP Bonus</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
<?
  $levels = $db->QueryFetchArrayAll("SELECT * FROM `levels` ORDER BY `id` ASC");
  if(empty($levels)){
	echo '<td colspan="6"><center>Nothing found</center></td>';
  }
 
  foreach($levels as $level){
?>	
					<tr>
						<td width="20"><img src="<?=$site['site_url'].'/'.$level['image']?>" alt="" title="Level <?=$level['level']?>" width="16" /></td>
						<td>Level <b><?=$level['level']?></b></td>
						<td><?=number_format($level['requirements'])?> exchanges</td>
						<td><?=number_format($level['free_bonus'])?> coins</td>
						<td><?=number_format($level['vip_bonus'])?> coins</td>
						<td class="center">
							<a href="index.php?x=levels&edit=<?=$level['id']?>" class="button small grey tooltip" data-gravity=s title="Edit"><i class="icon-pencil"></i></a>
							<a href="index.php?x=levels&del=<?=$level['id']?>" class="button small grey tooltip" data-gravity=s title="Remove"><i class="icon-remove"></i></a>
						</td>
					 </tr>
<?}?>
				</tbody>
			</table>
		</div>
	</div>
</section>
<?}?>