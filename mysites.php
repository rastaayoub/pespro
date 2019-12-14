<?
include('header.php');
if(!$is_online){
	redirect('index.php');
}

$target_system = true;
if($site['target_system'] == 1){
	if($data['premium'] > 0){
		$target_system = true;
	}else{
		$target_system = false;
	}
}elseif($site['target_system'] == 2){
	$target_system = false;
}

$custom = '';
if(isset($_GET['p'])){
	$page = $_GET['p'];
	$table = hook_filter($_GET['p'].'_info', 'db');
	$custom = ($_GET['p'] == 'surf' && $site['surf_type'] != 2 ? " AND `confirm`!='1'" : '');
}else{
	redirect('mysites.php?p=google');
}

if($table == 'db'){
	redirect('index.php');
}

$mysites = $db->QueryFetchArrayAll("SELECT * FROM `".$table."` WHERE `user`='".$data['id']."'".$custom);
?>
<script type="text/javascript"> function goSelect(selectobj){ window.location.href='mysites.php?p='+selectobj; } </script>
<div class="content t-left">
<b><?=$lang['b_93']?>:</b> <select onChange="goSelect(this.value)"><?=hook_filter('site_menu', "")?></select><hr>
<table cellpadding="5" class="table" style="text-align:center">
	<thead><tr><td><?=$lang['b_33']?></td><td><?=$lang['b_94']?></td><td><?=$lang['b_346']?></td><td width="30"><?=$lang['b_95']?></td><td width="60"><?=$lang['b_75']?></td><td width="50"><?=$lang['b_96']?></td></tr></thead>
	<tbody>
<?
$x = 1;
foreach($mysites as $mysite){
	$x++;
	$status = ($mysite['active'] == 0 ? '<font color="green">'.$lang['b_76'].'</font>' : ($mysite['active'] == 2 ? '<font color="red"><b>'.$lang['b_78'].'</b></font>' : '<font color="red">'.$lang['b_77'].'</font>'));
	$color = ($x%2) ? 3 : 1;
?>
    <tr class="c_<?=$color?>"><td class="t-left"><?=truncate($mysite['title'], 30)?></td><td><?=number_format($mysite['clicks']).' / '.($mysite['max_clicks'] == 0 ? '&#8734;' : $mysite['max_clicks'])?></td><td><?=number_format($mysite['today_clicks']).' / '.($mysite['daily_clicks'] == 0 ? '&#8734;' : $mysite['daily_clicks'])?></td><td><?=$mysite['cpc']?></td><td><?=$status?></td><td><?if($mysite['active'] != 2){?><a href="editsite.php?x=<?=$mysite['id']?>&t=<?=$page?>"><?=$lang['b_96']?></a><?}?></td></tr>
<?}?>
	</tbody>
</table>
</div>
<?include('footer.php');?>