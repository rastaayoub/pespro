<?
include('header.php');
if(isset($_GET['referrers'])){
	$db_custom = '';
	if(!isset($_GET['total']) && !isset($_GET['week'])){
		$db_custom = " AND MONTH(a.signup) = '".date('m')."'";
	}elseif(isset($_GET['week'])){
		$db_custom = " AND YEARWEEK(a.signup) = YEARWEEK(CURRENT_DATE)";
	}
?>
<script type="text/javascript">
function topOrder() {
	var oid = document.getElementById("oid").value;
	if(oid == '1') {
		var url = "<?=$site['site_url']?>/stats.php?referrers&total";
	}else if(oid == '2'){
		var url = "<?=$site['site_url']?>/stats.php?referrers&week";
	}else{
		var url = "<?=$site['site_url']?>/stats.php?referrers";
	}
	location.href= url;
	return false;
}
</script>
<div class="content">
    <h2 class="title"><?=$lang['b_271']?> <span style="float:right"><select id="oid" onchange="topOrder()"><option value="0"><?=$lang['b_274']?></option><option value="2"<?=(isset($_GET['week']) ? ' selected' : '')?>><?=$lang['b_323']?></option><option value="1"<?=(isset($_GET['total']) ? ' selected' : '')?>><?=$lang['b_275']?></option></select></span></h2>
	<div class="infobox"><div class="ucp_link" style="margin-right:5px;display:inline-block"><a href="stats.php"><?=$lang['b_82']?></a></div><div class="ucp_link active" style="margin-right:5px;display:inline-block"><a href="stats.php?referrers"><?=$lang['b_271']?></a></div></div>
	<table class="table">
		<thead>
			<tr><td>#</td><td><?=$lang['b_272']?></td><td><?=$lang['b_273']?></td></tr>
		</thead>
		<tbody>
		<?
			$refs = $db->QueryFetchArrayAll("SELECT a.ref, COUNT(a.id) AS total, b.login FROM users a LEFT JOIN users b ON b.id = a.ref WHERE (a.ref != '0' AND a.ref_paid = 1) AND b.login != ''".$db_custom." GROUP BY a.ref ORDER BY total DESC LIMIT 15");	
			$j = 0;
			foreach($refs as $ref){
				++$j;
				echo '<tr><td>'.($j == 1 ? '<font color="#FFD700"><b>'.$j.'</b></font>' : ($j == 2 ? '<font color="#E3E4E5"><b>'.$j.'</b></font>' : ($j == 3 ? '<font color="#C9AE5D"><b>'.$j.'</b></font>' : $j))).'</td><td>'.$ref['login'].'</td><td>'.number_format($ref['total']).'</td></tr>';
			}
			if($j == 0){
				echo '<tr><td colspan="3">'.$lang['b_250'].'</td></tr>';
			}
		?>
		</tbody>
		<tfoot>
			<tr><td colspan="3"><b><?=$lang['b_271']?></b></td></tr>
		</tfoot>
	</table>
</div>
<?
}else{
if($site['banner_system'] != 0){
	$banner_stats = $db->QueryFetchArray("SELECT COUNT(*) AS `total`, SUM(`views`) AS `views`, SUM(`clicks`) AS `clicks` FROM `banners`");
}
if($site['allow_withdraw'] == 1){
	$total_paid = $db->QueryFetchArray("SELECT COUNT(*) AS `payouts`, SUM(`amount`) AS `total` FROM `requests` WHERE `paid`='1'");
}
?>
<div class="content">
    <h2 class="title"><?=$lang['b_82']?></h2>
    <div class="infobox"><div class="ucp_link active" style="margin-right:5px;display:inline-block"><a href="stats.php"><?=$lang['b_82']?></a></div><div class="ucp_link" style="margin-right:5px;display:inline-block"><a href="stats.php?referrers"><?=$lang['b_271']?></a></div></div>
	<table class="table">
		<thead>
			<tr><td><?=$lang['b_135']?></td><td><?=$lang['b_136']?></td><td><?=$lang['b_137']?></td><td><?=$lang['b_138']?></td></tr>
		</thead>
		<tbody id="members_stats">
			<tr id="loading_stats"><td colspan="4"><img src="img/loader.gif" alt="Please wait..." /></td></tr>
		</tbody>
		<tfoot>
			<tr><td colspan="4"><b><?=$lang['b_139']?></b></td></tr>
		</tfoot>
	</table>

	<table class="table">
		<thead>
			<tr><td width="40%"><?=$lang['b_31']?></td><td width="30%"><?=$lang['b_140']?></td><td width="30%"><?=$lang['b_141']?></td></tr>
		<thead>
		<tbody id="page_stats_body">
			<tr id="loading_stats"><td colspan="3"><img src="img/loader.gif" alt="Please wait..." /></td></tr>
		</tbody>
		<tfoot>
			<tr id="page_stats_foot"></tr>
			<tr><td colspan="4"><b><?=$lang['b_140']?></b></td></tr>
		</tfoot>
	</table>
	<? 
		$tops = $db->QueryFetchArrayAll("SELECT a.uid, SUM(a.today_clicks) AS clicks, b.login FROM user_clicks a LEFT JOIN users b ON b.id = a.uid WHERE b.login != '' GROUP BY a.uid ORDER BY clicks DESC LIMIT 3");
		if($tops){
	?>
	<table class="table">
		<thead>
			<tr><td><img src="img/place/place_1.png" height="20px" alt="1" border="0" /></td><td><img src="img/place/place_2.png" height="20px" alt="2" border="0" /></td><td><img src="img/place/place_3.png" height="20px" alt="3" border="0" /></td></tr>
		</thead>
		<tbody>
			<tr><?
				foreach($tops as $top){
					echo '<td>'.$top['login'].'</td>';
				}
			?></tr>
		</tbody>
		<tfoot>
			<tr><td colspan="3"><b><?=$lang['b_239']?></b></td></tr>
		</tfoot>
	</table>
	<?}if($site['allow_withdraw'] == 1){?>
	<table class="table">
		<thead>
			<tr><td><?=$lang['b_321']?></td><td><?=$lang['b_322']?></td></tr>
		</thead>
		<tbody>
			<tr><td><?=number_format($total_paid['payouts'])?></td><td><?=get_currency_symbol($site['currency_code']).number_format($total_paid['total'], 2)?></td></tr>
		</tbody>
		<tfoot>
			<tr><td colspan="3"><b><?=$lang['b_320']?></b></td></tr>
		</tfoot>
	</table>
	<?}if($site['banner_system'] != 0){?>
	<table class="table">
		<thead>
			<tr><td><?=$lang['banners_02']?></td><td><?=$lang['banners_03']?></td><td><?=$lang['banners_04']?></td></tr>
		</thead>
		<tbody>
			<tr><td><?=number_format($banner_stats['total'])?></td><td><?=number_format($banner_stats['views'])?></td><td><?=number_format($banner_stats['clicks'])?></td></tr>
		</tbody>
		<tfoot>
			<tr><td colspan="3"><b><?=$lang['banners_01']?></b></td></tr>
		</tfoot>
	</table>
	<?}?>
</div>
<script>
	$.getJSON('system/ajax.php?a=getStats', function (c) {
		$('#members_stats').html(c['members']);
		$('#page_stats_body').html(c['pages_body']);
		$('#page_stats_foot').html(c['pages_foot']);
	});
</script>
<?}include('footer.php');?>