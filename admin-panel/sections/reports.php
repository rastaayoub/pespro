<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
if(isset($_GET['del_page']) && is_numeric($_GET['del_page'])){
	$del = $db->EscapeString($_GET['del_page']); 
	$report = $db->QueryFetchArray("SELECT page_id,module FROM `reports` WHERE `id`='".$del."'");
	if(!empty($report['page_id']) && !empty($report['module'])){
		if($db->QueryGetNumRows("SELECT id FROM `".$report['module']."` WHERE `id`='".$report['page_id']."'") > 0){
			$db->Query("DELETE FROM `".$report['module']."` WHERE `id`='".$report['page_id']."'");
		}
		$db->Query("UPDATE `reports` SET `status`='1' WHERE `id`='".$del."'");
	}
}elseif(isset($_GET['ban_page'])){
	$ban = $db->EscapeString($_GET['ban_page']); 
	$report = $db->QueryFetchArray("SELECT page_id,module FROM `reports` WHERE `id`='".$ban."'");
	if(!empty($report['page_id']) && !empty($report['module'])){
		if($db->QueryGetNumRows("SELECT id FROM `".$report['module']."` WHERE `id`='".$report['page_id']."'") > 0){
			$db->Query("UPDATE `".$report['module']."` SET `active`='2' WHERE `id`='".$report['page_id']."'");
		}
		$db->Query("UPDATE `reports` SET `status`='3' WHERE `id`='".$ban."'");
	}
}elseif(isset($_GET['ign'])){
	$id = $db->EscapeString($_GET['ign']);
	$db->Query("UPDATE `reports` SET `status`='2' WHERE `id`='".$id."'");
}

if(isset($_GET['completed'])){
	$db_value = " WHERE `status`!='0'";
}else{
	$db_value = " WHERE `status`='0'";
}

if(isset($_POST['ignore_reports'])){
	$selected = $db->EscapeString($_POST['report']);
	foreach ($selected as $key => $value){
		$db->Query("UPDATE `reports` SET `status`='2' WHERE `id`='".$key."'");
	}
}elseif(isset($_POST['delete_pages'])){
	$selected = $db->EscapeString($_POST['report']);
	foreach ($selected as $key => $value){
		$report = $db->QueryFetchArray("SELECT page_id,module FROM `reports` WHERE `id`='".$key."'");
		$db->Query("UPDATE `reports` SET `status`='1' WHERE `id`='".$key."'");
		if(!empty($report['page_id']) && !empty($report['module'])){
			$db->Query("DELETE FROM `".$report['module']."` WHERE `id`='".$report['page_id']."'");
		}
	}
}elseif(isset($_POST['ban_pages'])){
	$selected = $db->EscapeString($_POST['report']);
	foreach ($selected as $key => $value){
		$report = $db->QueryFetchArray("SELECT page_id,module FROM `reports` WHERE `id`='".$key."'");
		$db->Query("UPDATE `reports` SET `status`='3' WHERE `id`='".$key."'");
		if(!empty($report['page_id']) && !empty($report['module'])){
			$db->Query("UPDATE `".$report['module']."` SET `active`='2' WHERE `id`='".$report['page_id']."'");
		}
	}
}

$pagina = (isset($_GET['p']) ? $_GET['p'] : 0);
$limit = 20;
$start = (is_numeric($pagina) && $pagina > 0 ? ($pagina-1)*$limit : 0);

$total_pages = $db->QueryGetNumRows("SELECT id FROM `reports`".$db_value);
include('../system/libs/apaginate.php');
?>
<section id="content" class="container_12 clearfix ui-sortable">
	<h1 class="grid_12">Reports</h1>
	<div class="grid_12">
		<div class="box"><form method="POST">
			<table class="styled">
				<thead>
					<tr>
						<?if(!isset($_GET['completed'])){?><th></th><?}?>
						<th>Page ID</th>
						<th>Page URL</th>
						<th>Owner ID</th>
						<th>Reported by</th>
						<th>Module</th>
						<th>Status</th>
						<th>Reason</th>
						<th>Date</th>
						<?if(!isset($_GET['completed'])){?><th width="90">Actions</th><?}?>
					</tr>
				</thead>
				<tbody>
<?
  $reports = $db->QueryFetchArrayAll("SELECT * FROM `reports`".$db_value." ORDER BY `".(isset($_GET['completed']) ? 'timestamp' : 'count')."` DESC LIMIT ".$start.",".$limit."");
  if(!$reports){ echo '<tr><td colspan="10" class="center">Nothing found!</td></tr>'; }
  foreach($reports as $report){
?>	
					<tr>
						<?if(!isset($_GET['completed'])){?><td width="10"><input type="checkbox" name="report[<?=$report['id']?>]" /></td><?}?>
						<td><a href="index.php?x=sites&s=<?=hook_filter($report['module'].'_dtf', '')?>&edit=<?=$report['page_id']?>"><?=$report['page_id']?></a></td>
						<td><a href="<?=$report['page_url']?>" target="_blank"><?=truncate($report['page_url'], 35)?></a></td>
						<td><a href="<?=($report['owner_id'] > 0 ? 'index.php?x=users&edit='.$report['owner_id'] : '#')?>"><?=($report['owner_id'] > 0 ? $report['owner_id'] : 'N/A')?></a></td>
						<td><a href="index.php?x=users&edit=<?=$report['reported_by']?>" title="Reported <?=$report['count']?> times"><?=$report['reported_by']?></a></td>
						<td><?=hook_filter($report['module'].'_dtf', '')?></td>
						<td><?=($report['status'] == '0' ? 'Waiting' : ($report['status'] == 2 ? '<font color="blue">Ignored</font>' : ($report['status'] == 3 ? '<font color="orange">Page Banned</font>' : '<font color="red">Page Deleted</font>')))?></td>
						<td><?if(!empty($report['reason'])){?><a id="dialog_normal_btn" rid="<?=$report['id']?>" class="button small grey" href="javascript:void(0);">Click Me</a><div style="display:none;" id="dialog_normal_<?=$report['id']?>" class="dialog_no_auto" title="Reason for Report #<?=$report['id']?>"><p><?=$report['reason']?></p></div><?}else{?>N/A<?}?></td>
						<td><?=date('d M Y H:i', $report['timestamp'])?></td>
						<?if(!isset($_GET['completed'])){?>
						<td class="center">
							<a href="index.php?x=reports&ign=<?=$report['id']?>" onclick="return confirm('You sure you want to ignore this report?');" class="button small grey tooltip" data-gravity=s title="Ignore"><i class="icon-ok-sign"></i></a>
							<a href="index.php?x=reports&ban_page=<?=$report['id']?>" onclick="return confirm('You sure you want to ban this page?');" class="button small grey tooltip" data-gravity=s title="Ban Page"><i class="icon-minus-sign"></i></a>
							<a href="index.php?x=reports&del_page=<?=$report['id']?>" onclick="return confirm('You sure you want to delete this page?');" class="button small grey tooltip" data-gravity=s title="Delete Page"><i class="icon-remove"></i></a>
						</td>
						<?}?>
					</tr>
<?} 
if(!isset($_GET['completed']) && $reports){
?>
					<tr>
						<td colspan="8"></td>
						<td colspan="2" class="center">
							<input type="submit" name="ignore_reports" value="Ignore" onclick="return confirm('You sure you want to ignore these reports?');" class="button small grey tooltip" data-gravity=s title="Ignore Reports" />
							<input type="submit" name="ban_pages" value="Ban" onclick="return confirm('You sure you want to ban these pages?');" class="button small grey tooltip" data-gravity=s title="Ban Pages" />
							<input type="submit" name="delete_pages" value="Delete" onclick="return confirm('You sure you want to delete these pages?');" class="button small grey tooltip" data-gravity=s title="Delete Pages" />
						</td>
<?}?>
					</tr>
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
<script> $$.ready(function(){$(".dialog_no_auto").dialog({autoOpen:false,modal:true,buttons:[{text:"Close",click:function(){$(this).dialog("close")}}]}).find('button').click(function(){$(this).parents('.ui-dialog-content').dialog('close')});$("#dialog_normal_btn").live('click',function(){$("#dialog_normal_"+$(this).attr('rid')).dialog("open");return false})}); </script>