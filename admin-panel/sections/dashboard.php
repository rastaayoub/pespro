<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	$reg_today = $db->QueryFetchArray("SELECT COUNT(*) AS `total` FROM `users` WHERE DATE(`signup`)='".date('Y-m-d')."'");
	$online = $db->QueryFetchArray("SELECT COUNT(*) AS `total` FROM `users` WHERE (".time()."-UNIX_TIMESTAMP(`online`)) < 1800");
	$on_today = $db->QueryFetchArray("SELECT COUNT(*) AS `total` FROM `users` WHERE DATE(`online`) = DATE(NOW())");
	$premium = $db->QueryFetchArray("SELECT COUNT(*) AS `total`, SUM(`premium`-'".time()."') AS `premium` FROM `users` WHERE `premium`>'0'");
	$banned = $db->QueryFetchArray("SELECT COUNT(*) AS `total` FROM `users` WHERE `banned`='1'");
	$transactions = $db->QueryFetchArray("SELECT COUNT(CASE WHEN `type`='0' THEN 1 END) AS `coins`, COUNT(CASE WHEN `type`='1' THEN 1 END) AS `vip`, COUNT(CASE WHEN `paid`='0' THEN 1 END) AS `waiting` FROM `transactions`");
	$reports = $db->QueryFetchArray("SELECT COUNT(CASE WHEN `status`>'0' THEN 1 END) AS `checked`, COUNT(CASE WHEN `status`='0' THEN 1 END) AS `unchecked` FROM `reports`");
	$today_click = $db->QueryFetchArray("SELECT SUM(`today_clicks`) AS `today` FROM `user_clicks` WHERE `today_clicks`>'0'");
	$total_clicks = $db->QueryFetchArray("SELECT SUM(`value`) AS `total` FROM `web_stats`");

	if($site['allow_withdraw'] == 1){
		$waiting = $db->QueryFetchArray("SELECT COUNT(*) AS `total`, SUM(`amount`) AS `amount` FROM `requests` WHERE `paid`='0'");
		$paid = $db->QueryFetchArray("SELECT COUNT(*) AS `total`, SUM(`amount`) AS `amount` FROM `requests` WHERE `paid`='1'");
		$rejected = $db->QueryFetchArray("SELECT COUNT(*) AS `total`, SUM(`amount`) AS `amount` FROM `requests` WHERE `paid`='2'");
	}

	$users = $db->QueryFetchArray("SELECT COUNT(`id`) AS `users`, SUM(`coins`) AS `coins`, SUM(`account_balance`) AS `cash` FROM `users`");
	$total_vip = round(($premium['premium']/86400), 0);

	$total_income = $db->QueryFetchArray("SELECT SUM(money) AS money FROM transactions");
	$total_income = (!empty($total_income['money']) ? $total_income['money'] : 0);
	$income_month = $db->QueryFetchArray("SELECT SUM(money) AS money FROM transactions WHERE MONTH(date) = '".date('m')."' AND YEAR(date) = '".date('Y')."'");
	$income_month = (!empty($income_month['money']) ? $income_month['money'] : 0);
	$income_today = $db->QueryFetchArray("SELECT SUM(money) AS money FROM transactions WHERE DATE(date) = DATE(NOW())");
	$income_today = (!empty($income_today['money']) ? $income_today['money'] : 0);
?>
<section id="content" class="container_12 clearfix" data-sort=true>
	<ul class="stats not-on-phone">
		<li>
			<strong><?=number_format($users['users'])?></strong>
			<small>Total Users</small>
			<span <?=($reg_today['total'] > 0 ? 'class="green" ' : '')?>style="margin:4px 0 -10px 0"><?=$reg_today['total']?> today</span>
		</li>
		<li>
			<strong><?=number_format($on_today['total'])?></strong>
			<small>Active Today</small>
		</li>
		<li>
			<strong><?=number_format($transactions['coins']+$transactions['vip'])?></strong>
			<small>Total Sales</small>
			<span <?=($transactions['waiting'] > 0 ? 'class="green" ' : '')?>style="margin:4px 0 -10px 0"><?=$transactions['waiting']?> waiting</span>
		</li>
		<li>
			<strong><?=number_format(hook_filter('tot_sites',""))?></strong>
			<small>Total Pages</small>
		</li>
		<li>
			<strong><?=number_format($total_clicks['total'])?></strong>
			<small>Total Clicks</small>
			<span <?=($today_click['today'] > 0 ? 'class="green" ' : '')?>style="margin:4px 0 -10px 0"><?=number_format($today_click['today'])?> today</span>
		</li>
	</ul>

	<div class="alert note" id="version_alert" style="display:none"><a href="https://mn-shop.com/account/download" target="_blank"><span class="icon"></span><strong>There is a new version of this script available for download! Download latest version from MN-Shop.net!</strong></a></div>

	<h1 class="grid_12 margin-top">Dashboard</h1>
	<div class="grid_7">
		<div class="box">
			<div class="header">
				<h2><img class="icon" src="img/icons/packs/fugue/16x16/users.png" width="16" height="16">Members statistics</h2>
			</div>
			<div class="content">
				<div class="spacer"></div>
				<div class="full-stats">
					<div class="stat hlist" data-list='[{"val":<?=$online['total'].','.($online['total'] > 999 ? '"format":"0,0",' : '')?>"title":"Online Members","color":"green"},{"val":<?=$premium['total'].','.($premium['total'] > 999 ? '"format":"0,0",' : '')?>"title":"VIP Members"},{"val":<?=$banned['total'].','.($banned['total'] > 999 ? '"format":"0,0",' : '')?>"title":"Banned Members","color":"red"},{"val":<?=$reg_today['total'].','.($reg_today['total'] > 999 ? '"format":"0,0",' : '')?>"title":"Registered Today"}]' data-flexiwidth=true></div>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2><img class="icon" src="img/icons/packs/fugue/16x16/dashboard.png" width="16" height="16">Other Stats</h2>
			</div>
			<div class="content">
				<div class="spacer"></div>
				<div class="full-stats">
					<div class="stat hlist" data-list='[{"val":<?=$users['coins'].','.($users['coins'] > 999 ? '"format":"0,0",' : '')?>"title":"Total Coins"},{"val":<?=$total_vip.','.($total_vip > 999 ? '"format":"0,0",' : '')?>"title":"Total VIP Days","color":"red"},{"val":<?=$users['cash']?>,"format":"<?=get_currency_symbol($site['currency_code'])?>0.00","title":"Account Balances","color":"green"}]' data-flexiwidth=true></div>
				</div>
			</div>
		</div>
	</div>
	<div class="grid_5">
		<div class="box">
			<div class="header">
				<h2><img class="icon" src="img/icons/packs/fugue/16x16/coins.png" width="16" height="16">Sales statistics</h2>
			</div>
			<div class="content">
				<div class="spacer"></div>
				<div class="full-stats">
					<div class="stat hlist" data-list='[{"val":<?=$income_today?>,"format":"<?=get_currency_symbol($site['currency_code'])?>0.00","title":"Today Income","color":"green"},{"val":<?=$income_month?>,"format":"<?=get_currency_symbol($site['currency_code'])?>0.00","title":"This Month"},{"val":<?=($total_income)?>,"format":"<?=get_currency_symbol($site['currency_code'])?>0.00","title":"Total Income","color":"red"}]' data-flexiwidth=true></div>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2><img class="icon" src="../img/report.png" width="16" height="16">Reports</h2>
			</div>
			<div class="content">
				<div class="spacer"></div>
				<div class="full-stats">
					<div class="stat hlist" data-list='[{"val":<?=$reports['unchecked'].','.($reports['unchecked'] > 999 ? '"format":"0,0",' : '')?>"title":"Waiting"},{"val":<?=$reports['checked'].','.($reports['checked'] > 999 ? '"format":"0,0",' : '')?>"title":"Checked","color":"green"}]' data-flexiwidth=true></div>
				</div>
			</div>
		</div>
	</div>
	<div class="grid_12">
		<?if($site['allow_withdraw'] == 1){?>
			<div class="box">
				<div class="header">
					<h2><img class="icon" src="img/icons/packs/fugue/16x16/orders.png" width="16" height="16">Payment Requests</h2>
				</div>
				<div class="content">
					<div class="spacer"></div>
					<div class="full-stats">
						<div class="stat hlist" data-list='[{"val":<?=$waiting['total'].','.($waiting['total'] > 999 ? '"format":"0,0",' : '')?>"title":"Waiting"},{"val":<?=$paid['total'].','.($paid['total'] > 999 ? '"format":"0,0",' : '')?>"title":"Paid","color":"green"},{"val":<?=$rejected['total'].','.($rejected['total'] > 999 ? '"format":"0,0",' : '')?>"title":"Rejected","color":"red"}]' data-flexiwidth=true></div>
					</div>
					<div class="full-stats">
						<div class="stat hlist" data-list='[{"val":"<?=number_format($waiting['amount'], 2, '.', '')?>","format":"<?=get_currency_symbol($site['currency_code'])?>0.00","title":"Total Waiting"},{"val":"<?=number_format($paid['amount'], 2, '.', '')?>","format":"<?=get_currency_symbol($site['currency_code'])?>0.00","title":"Total Paid","color":"green"},{"val":"<?=number_format($rejected['amount'], 2, '.', '')?>","format":"<?=get_currency_symbol($site['currency_code'])?>0.00","title":"Total Rejected","color":"red"}]' data-flexiwidth=true></div>
					</div>
				</div>
			</div>
		<?}?>
		<div class="box">
			<div class="header">
				<h2><img class="icon" src="img/icons/packs/fugue/16x16/pages.png" width="16" height="16">Pages</h2>
			</div>
			<div class="content">
				<div class="spacer"></div>
				<?=hook_filter('admin_s_sites','')?>
		</div>
	</div>
</section>