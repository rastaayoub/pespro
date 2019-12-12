<?php
include('header.php');
if(!$is_online || $site['refsys'] != 1){
	redirect('index.php');
}

$refs = $db->QueryFetchArray("SELECT COUNT(*) AS `total` FROM `users` WHERE `ref`='".$data['id']."'");
$commissions = $db->QueryFetchArray("SELECT COUNT(*) AS `total` FROM `affiliate_transactions` WHERE `user`='".$data['id']."'");
?>
<div class="content"><h2 class="title"><?=(isset($_GET['commissions']) ? $lang['b_340'] : $lang['b_121'])?></a> <span style="float:right"><?=(isset($_GET['commissions']) ? '<a href="referrals.php">'.$lang['b_121'].'</a>' : '<a href="referrals.php?commissions">'.$lang['b_340'].'</a>')?></span></h2>
	<div class="infobox" style="width:200px;margin:10px auto;display:inline-block"><b><?=$lang['b_119']?>: <span style="color:blue"><?=$refs['total']?></span></b></div>
	<div class="infobox" style="width:200px;margin:10px auto;display:inline-block"><b><?=$lang['b_340']?>: <span style="color:blue"><?=$commissions['total']?></span></b></div>
	<?if($site['paysys'] == 1){?><div class="infobox" style="width:200px;margin:10px auto;display:inline-block"><b><?=$lang['b_255']?>: <span style="color:green"><?=$data['account_balance'].' '.get_currency_symbol($site['currency_code'])?></span></b></div><?}?>
	<hr class="styled" />
<?
if(isset($_GET['commissions'])){
	$num = $db->QueryGetNumRows("SELECT * FROM `affiliate_transactions` WHERE `user`='".$data['id']."'");
	$pages = floor($num/20+1);
	$begin = ($_GET['p'] >= 0 && is_numeric($_GET['p']) ? $_GET['p']*20 : 0);
	$commissions = $db->QueryFetchArrayAll("SELECT a.id, a.referral, a.commission, a.type, a.date, b.login FROM affiliate_transactions a LEFT JOIN users b ON b.id = a.referral WHERE a.user = '".$data['id']."' ORDER BY a.date DESC LIMIT ".$begin.",20");
?>
	<table class="table">
		<thead>
			<tr>
				<td width="20">#</td>
				<td><?=$lang['b_342']?></td>
				<td><?=$lang['b_341']?></td>
				<td><?=$lang['b_31']?></td>
				<td><?=$lang['b_106']?></td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td>#</td>
				<td><?=$lang['b_342']?></td>
				<td><?=$lang['b_341']?></td>
				<td><?=$lang['b_31']?></td>
				<td><?=$lang['b_106']?></td>
			</tr>
		</tfoot>
		<tbody>
		<?
			if(empty($commissions)){
				echo '<tr><td colspan="5">'.$lang['b_250'].'</td></tr>';
			}

			foreach($commissions as $commission){
		?>	
			<tr>
				<td><?=$commission['id']?></td>
				<td><?=$commission['login']?></td>
				<td><font color="green"><?=get_currency_symbol($site['currency_code']).$commission['commission']?></font></td>
				<td><?=$commission['type']?></td>
				<td><?=date('d M Y H:i', $commission['date'])?></td>
			</tr>
		<?}?>
		</tbody>
	</table>
<?
if($pages > 1){
	if($num >= 0) {
		$pagination = '';
		if($begin/20 == 0) {
			$pagination .= '<img src="theme/'.$site['theme'].'/images/black_arrow_left.png" />';
		}else{
			$pagination .= '<a href="referrals.php?commissions&p='.($begin/20-1).'"><img src="theme/'.$site['theme'].'/images/black_arrow_left.png" /></a>';
		}

		$pagination .= '&nbsp;&nbsp; '.($begin+1).' - '.($begin+20 >= $num ? $num : $begin+20).' &nbsp;&nbsp;';
		
		if($begin+20 >= $num) {
			$pagination .= '<img src="theme/'.$site['theme'].'/images/black_arrow_right.png" />';
		}else{
			$pagination .= '<a href="referrals.php?commissions&p='.($begin/20+1).'"><img src="theme/'.$site['theme'].'/images/black_arrow_right.png" /></a>';
		}
	}
?>
	<div class="infobox">
		<div style="float:left;"><?=$pagination?></div>
		<div style="float:right;">
			<b><?=($begin+1)?> - <?=($begin+20 >= $num ? $num : $begin+20)?></b> <?=lang_rep($lang['b_126'], array('-NUM-' => $num))?>
		</div>
		<div style="display:block;clear:both;"></div>
	</div>
<?}}else{
	$num = $db->QueryGetNumRows("SELECT * FROM `users` WHERE `ref`='".$data['id']."'");
	$pages = floor($num/20+1);
	$begin = ($_GET['p'] >= 0 && is_numeric($_GET['p']) ? $_GET['p']*20 : 0);
	$users = $db->QueryFetchArrayAll("SELECT id,login,signup,ref_paid FROM `users` WHERE `ref`='".$data['id']."' ORDER BY `signup` DESC LIMIT ".$begin.",20");
?>
	<table class="table">
		<thead>
			<tr>
				<td width="20">#</td>
				<td><?=$lang['b_122']?></td>
				<td><?=$lang['b_106']?></td>
				<td><?=$lang['b_244']?></td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td>#</td>
				<td><?=$lang['b_122']?></td>
				<td><?=$lang['b_106']?></td>
				<td><?=$lang['b_244']?></td>
			</tr>
		</tfoot>
		<tbody>
		<?
			if(empty($users)){
				echo '<tr><td colspan="4">'.$lang['b_250'].'</td></tr>';
			}

			foreach($users as $user){
		?>	
			<tr>
				<td><?=$user['id']?></td>
				<td><?=$user['login']?></td>
				<td><?=$user['signup']?></td>
				<td><?=($user['ref_paid'] == 1 ? $lang['b_124'] : $lang['b_125'])?></td>
			</tr>
		<?}?>
		</tbody>
	</table>
<?
if($pages > 1){
	if($num >= 0) {
		$pagination = '';
		if($begin/20 == 0) {
			$pagination .= '<img src="theme/'.$site['theme'].'/images/black_arrow_left.png" />';
		}else{
			$pagination .= '<a href="?p='.($begin/20-1).'"><img src="theme/'.$site['theme'].'/images/black_arrow_left.png" /></a>';
		}

		$pagination .= '&nbsp;&nbsp; '.($begin+1).' - '.($begin+20 >= $num ? $num : $begin+20).' &nbsp;&nbsp;';
		
		if($begin+20 >= $num) {
			$pagination .= '<img src="theme/'.$site['theme'].'/images/black_arrow_right.png" />';
		}else{
			$pagination .= '<a href="?p='.($begin/20+1).'"><img src="theme/'.$site['theme'].'/images/black_arrow_right.png" /></a>';
		}
	}
?>
	<div class="infobox">
		<div style="float:left;"><?=$pagination?></div>
		<div style="float:right;">
			<b><?=($begin+1)?> - <?=($begin+20 >= $num ? $num : $begin+20)?></b> <?=lang_rep($lang['b_126'], array('-NUM-' => $num))?>
		</div>
		<div style="display:block;clear:both;"></div>
	</div>
<?}}?>
</div>
<?include('footer.php');?>