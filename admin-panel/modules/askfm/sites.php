<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

$s_value = '';
if(isset($_GET['sp'])){
	$search = $db->EscapeString($_GET['sp']);
	if($_GET['s_type'] == 1){
		$s_value = ($search != '' ?  " WHERE `url` LIKE '%".$search."%'" : "");
	}elseif($_GET['s_type'] == 2){
		$s_value = ($search != '' ?  " WHERE `id`='".$search."'" : "");
	}else{
		$suser = $db->QueryFetchArray("SELECT id FROM `users` WHERE `login`='".$search."' OR `id`='".$search."'");
		$s_value = ($search != '' ?  " WHERE `user`='".$suser['id']."'" : "");
	}
}

$sql = $db->Query("SELECT id FROM `askfm_like`".$s_value);
$total_pages = $db->GetNumRows($sql);
include('../system/libs/apaginate.php');

$mesaj = '';
if(isset($_GET['edit'])){
	$edit = $db->EscapeString($_GET['edit']);
	$sql = $db->FetchArray($db->Query("SELECT user,url,title,p_av,cpc,active FROM `askfm_like` WHERE `id`='".$edit."'"));
	if(isset($_POST['submit'])){
		$db->Query("UPDATE `askfm_like` SET `user`='".$db->EscapeString($_POST['user'])."', `url`='".$db->EscapeString($_POST['url'])."', `title`='".$db->EscapeString($_POST['title'])."', `img`='".$db->EscapeString($_POST['p_av'])."', `cpc`='".$db->EscapeString($_POST['cpc'])."', `active`='".$db->EscapeString($_POST['status'])."' WHERE `id`='".$edit."'");
		$mesaj = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Page successfully edited!</div>';
	}
}
if(isset($_GET['del']) != "" && is_numeric($_GET['del'])){$del = $db->EscapeString($_GET['del']); $db->Query("DELETE FROM `askfm_like` WHERE `id`='".$del."'"); $db->Query("DELETE FROM `askfm_liked` WHERE `site_id`='".$del."'");}
if(isset($_GET['edit'])){ 
	echo $mesaj;
?>
	<form action="" method="post" class="box">
			<div class="header">
				<h2>Edit Page</h2>
			</div>
				<div class="content">
					<div class="row">
						<label><strong>User</strong></label>
						<div><input type="text" name="user" value="<?=(isset($_POST['user']) ? $_POST['user'] : $sql['user'])?>" required="required" /></div>
					</div>
					<div class="row">
						<label><strong>URL</strong></label>
						<div><input type="text" name="url" value="<?=(isset($_POST['url']) ? $_POST['url'] : $sql['url'])?>" required="required" /></div>
					</div>
					<div class="row">
						<label><strong>Title</strong></label>
						<div><input type="text" name="title" value="<?=(isset($_POST['title']) ? $_POST['title'] : $sql['title'])?>" required="required" /></div>
					</div>
					<div class="row">
						<label><strong>Image</strong></label>
						<div><input type="text" name="p_av" value="<?=(isset($_POST['p_av']) ? $_POST['p_av'] : $sql['img'])?>" required="required" /></div>
					</div>
					<div class="row">
						<label><strong>CPC</strong></label>
						<div><input type="text" name="cpc" value="<?=(isset($_POST['cpc']) ? $_POST['cpc'] : $sql['cpc'])?>" required="required" /></div>
					</div>		
					<div class="row">
						<label><strong>Status</strong></label>
						<div><select name="status"><option value="0">Enabled</option><option value="1"<?=($status == 1 ? ' selected' : '')?>>Disabled</option><option value="2"<?=($status == 2 ? ' selected' : '')?>>Banned</option></select></div>
					</div>	
                </div>
				<div class="actions">
					<div class="right">
						<input type="submit" value="Submit" name="submit" />
					</div>
				</div>
	</form>
<?}else{?>
			<div class="box">
				<table class="styled">
					<thead>
						<tr>
							<th>ID</th>
							<th>User ID</th>
							<th>URL</th>
							<th>Targeting</th>
							<th>Exchanges</th>
							<th>Status</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
<?
  $sql = $db->Query("SELECT * FROM `askfm_like`".$s_value." ORDER BY `id` ASC LIMIT ".$start.",".$limit."");
  $as = $db->FetchArrayAll($sql);
  foreach($as as $sites){
  $rec = ($sites['sex'] == '0' ? 'All Genders' : ($sites['sex'] == 1 ? 'Men' : 'Women')).' </b>|</b> '.($sites['country'] == '0' ? 'All Countries' : 'Selected Countries');
?>	
						<tr>
							<td><?=$sites['id']?></td>
							<td><a href="index.php?x=users&edit=<?=$sites['user']?>"><?=$sites['user']?></a></td>
							<td><a href="<?=$sites['url']?>" target="_blank"><?=truncate($sites['url'], 60)?></a></td>
							<td><?=$rec?></td>
							<td><?=$sites['today_clicks'].' today - '.$sites['clicks'].' total'?></td>
							<td><?=($sites['active'] == 1 ? '<font color="red">Disabled</font>' : ($sites['active'] == 2 ? '<font color="red">Banned</font>' : '<font color="green">Active</font>'))?></td>
							<td class="center">
								<a href="index.php?x=sites&s=<?=$account?>&edit=<?=$sites['id']?>" class="button small grey tooltip" data-gravity=s title="Edit"><i class="icon-pencil"></i></a>
								<a href="index.php?x=sites&s=<?=$account?>&del=<?=$sites['id']?>" class="button small grey tooltip" data-gravity=s title="Remove"><i class="icon-remove"></i></a>
							</td>
						</tr>
<?}?>
					</tbody>
				</table>
<?if($total_pages > $limit){?>
	<div class="dataTables_wrapper">
	<div class="footer">
		<div class="dataTables_paginate paging_full_numbers">
			<a class="first paginate_button" href="index.php?x=sites&s=<?=$account?>&p=1">First</a>
			<?=(($pagina <= 1 || $pagina == '') ? '<a class="previous paginate_button">&laquo;</a>' : '<a class="previous paginate_button" href="index.php?x=sites&s='.$account.'&p='.($pagina-1).'">&laquo;</a>')?>
			<span>
				<?=$pagination?>
			</span>
			<?=(($pagina >= $lastpage) ? '<a class="next paginate_button">&raquo;</a>' : '<a class="next paginate_button" href="index.php?x=sites&s='.$account.'&p='.($pagina == 0 ? 2 : $pagina+1).'">&raquo;</a>')?>
			<a class="last paginate_button" href="index.php?x=sites&s=<?=$account?>&p=<?=$lastpage?>">Last</a>
		</div>
	</div>
	</div>
<?}?></div><?}?>