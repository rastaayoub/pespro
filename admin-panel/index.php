<?
define('BASEPATH', true);
define('IS_ADMIN', true);
include('../system/config.php');

/* Define allowed pages */
$action = array(
		'users' => 1,
		'blacklist' => 1,
		'bank' => 1,
		'settings' => 1,
		'sites' => 1,
		'packs' => 1,
		'p_packs' => 1,
		'coupons' => 1,
		'sales' => 1,
		'search' => 1,
		'faq' => 1,
		'blog' => 1,
		'requests' => 1,
		'refset' => 1,
		'regset' => 1,
		'surfset' => 1,
		'surfset' => 1,
		'newsletter' => 1,
		'dashboard' => 1,
		'gateways' => 1,
		'transfers' => 1,
		'reports' => 1,
		'captcha' => 1,
		'fbset' => 1,
		'twset' => 1,
		'ytset' => 1,
		'top_users' => 1,
		'banners' => 1,
		'proofs' => 1,
		'mailset' => 1,
		'rewards' => 1
	);

include('sections/core.php');
?>
<html>
<head><title>PES Pro - Admin Panel</title>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="author" content="MafiaNet (c) MN-Shop.net">
	<link rel="stylesheet" href="css/fullcss.css">
	<!--[if IE 8]><link rel="stylesheet" href="css/fonts/font-awesome-ie7.css"><![endif]-->

	<script src="js/mylibs/polyfills/fulljs.js"></script>
	<!--[if lt IE 9]><script src="js/mylibs/polyfills/selectivizr-min.js"></script><![endif]-->
	<!--[if lt IE 10]><script src="js/mylibs/polyfills/excanvas.js"></script><![endif]-->
	<!--[if lt IE 10]><script src="js/mylibs/polyfills/classlist.js"></script><![endif]-->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js"></script>
	<!--[if gt IE 8]><!-->
	<script src="http://cdnjs.cloudflare.com/ajax/libs/lodash.js/0.4.2/lodash.min.js"></script>
	<!--<![endif]-->
	<!--[if lt IE 9]><script src="http://documentcloud.github.com/underscore/underscore.js"></script><![endif]-->

	<!-- General Scripts -->
	<script src="js/mylibs/forms/jquery.validate.js"></script>
	<script src="js/mylibs/fulljs.js"></script>
	<script src="js/mylibs/forms/fulljs.js"></script>
	<script src="js/mylibs/charts/fulljs.js"></script>
	<script src="js/mylibs/fullstats/fulljs.js"></script>
	<script src="js/mango.js"></script>
	<script src="js/plugins.js"></script>
	<script src="js/script.js"></script>
</head>
<body>
	<section id="toolbar">
		<div class="container_12">
			<div class="left">
				<ul class="breadcrumb">
					<li><a href="<?=$site['site_url']?>/admin-panel/">PES Admin</a></li>
				</ul>
			</div>
			<div class="right">
				<ul>
					<li><a href="<?=$site['site_url']?>">View Website</a></li>
					<li class="red"><a href="<?=$site['site_url']?>/logout.php">Logout</a></li>
				</ul>
			</div>
		</div>
	</section>
	<header class="container_12"><br></header>
	<div role="main" id="main" class="container_12 clearfix">
		<section class="toolbar"><img src="img/logo.png" width="181" height="46" /></section>
		<aside>
			<div class="top">
				<nav><ul class="collapsible accordion">
					<li>
						<a href="javascript:void(0);"><img src="img/icons/packs/fugue/16x16/dashboard.png" alt="" height="16" width="16">Statistics</a>
						<ul>
							<li><a href="index.php">Dashboard</a></li>
							<li><a href="index.php?x=transfers">Coins Transfers</a></li>
						</ul>
					</li>
					<li>
						<a href="javascript:void(0);"><img src="img/icons/packs/fugue/16x16/coins.png" alt="" height="16" width="16">Bank</a>
						<ul>
							<li><a href="index.php?x=sales">Added Funds</a></li>
							<li><a href="index.php?x=sales&pending">Added funds pending</a></li>
							<li><a href="index.php?x=requests">Payment Requests</a></li>
						</ul>
					</li>
					<?if($site['allow_withdraw'] == 1){?>
					<li>
						<a href="javascript:void(0);"><img src="img/icons/packs/fugue/16x16/ads.png" alt="" height="16" width="16">Payment Proofs <span class="badge" id="pending_proofs" style="display:none">0</span></a>
						<ul>
							<li><a href="index.php?x=proofs">Pending Proofs</a></li>
							<li><a href="index.php?x=proofs&aproved">Approved Proofs</a></li>
						</ul>
					</li>
					<?}?>
					<li>
						<a href="javascript:void(0);"><img src="img/icons/packs/fugue/16x16/users.png" alt="" height="16" width="16">Members</a>
						<ul>
							<li><a href="index.php?x=users">All Members</a></li>
							<li><a href="index.php?x=top_users">Top 20 Members</a></li>
							<li><a href="index.php?x=users&today">Registered Today</a></li>
							<li><a href="index.php?x=users&online">Online Members</a></li>
							<li><a href="index.php?x=users&premium">Premium Members</a></li>
							<li><a href="index.php?x=users&banned">Banned Members</a></li>
							<li><a href="index.php?x=users&countries">Countries Overview</a></li>
							<li><a href="index.php?x=users&search">Search Members</a></li>
							<li><a href="index.php?x=users&multi_accounts">Accounts on same IP</a></li>
						</ul>
					</li>
					<li>
						<a href="javascript:void(0);"><img src="img/icons/packs/fugue/16x16/medal.png" alt="" height="16" width="16">Rewards</a>
						<ul>
							<li><a href="index.php?x=rewards">Rewards</a></li>
							<li><a href="index.php?x=rewards&add">Add Reward</a></li>
							<li><a href="index.php?x=rewards&claims">Claims</a></li>
						</ul>
					</li>
					<li>
						<a href="javascript:void(0);"><img src="img/icons/packs/fugue/16x16/pages.png" alt="" height="16" width="16">Pages</a>
						<ul>
							<?=hook_filter('admin_s_menu','')?>
						</ul>
					</li>
					<li>
						<a href="javascript:void(0);"><img src="img/elements/alert-boxes/error.png" alt="" height="16" width="16">Blacklists</a>
						<ul>
							<li><a href="index.php?x=blacklist">Emails</a></li>
							<li><a href="index.php?x=blacklist&type=2">Websites</a></li>
							<li><a href="index.php?x=blacklist&type=3">IP's</a></li>
						</ul>
					</li>
					<li>
						<a href="javascript:void(0);"><img src="img/icons/packs/fugue/16x16/coins.png" alt="" height="16" width="16">Coins packs</a>
						<ul>
							<li><a href="index.php?x=packs">Packs</a></li>
							<li><a href="index.php?x=packs&add">Add Pack</a></li>
							<li><a href="index.php?x=packs&settings">Settings</a></li>
						</ul>
					</li>
					<li>
						<a href="javascript:void(0);"><img src="img/icons/packs/fugue/16x16/medal.png" alt="" height="16" width="16">VIP Packs</a>
						<ul>
							<li><a href="index.php?x=p_packs">Packs</a></li>
							<li><a href="index.php?x=p_packs&add">Add Pack</a></li>
						</ul>
					</li>
					<li>
						<a href="javascript:void(0);"><img src="img/icons/packs/fugue/16x16/ads.png" alt="" height="16" width="16">Banner Ads</a>
						<ul>
							<li><a href="index.php?x=banners">Manage Banners</a></li>
							<li><a href="index.php?x=banners&packs">Manage Packs</a></li>
							<li><a href="index.php?x=banners&add">Add Pack</a></li>
						</ul>
					</li>
					<li>
						<a href="javascript:void(0);"><img src="img/icons/packs/fugue/16x16/cards.png" alt="" height="16" width="16">Coupons</a>
						<ul>
							<li><a href="index.php?x=coupons">Coupons</a></li>
							<li><a href="index.php?x=coupons&add">Add Coupon</a></li>
						</ul>
					</li>
					<li>
						<a href="javascript:void(0);"><img src="<?=$site['site_url']?>/img/report.png" alt="" height="16" width="16">Reports <span class="badge" id="pending_reports" style="display:none">0</span></a>
						<ul>
							<li><a href="index.php?x=reports">Reported Pages</a></li>
							<li><a href="index.php?x=reports&completed">Checked Reports</a></li>
						</ul>
					</li>
					<li>
						<a href="javascript:void(0);"><img src="img/icons/packs/fugue/16x16/balloon.png" alt="" height="16" width="16">Blog</a>
						<ul>
							<li><a href="index.php?x=blog">Manage Blog</a></li>
							<li><a href="index.php?x=blog&add">Add Blog</a></li>
						</ul>
					</li>
					<li>
						<a href="javascript:void(0);"><img src="img/icons/packs/fugue/16x16/question.png" alt="" height="16" width="16">FAQ</a>
						<ul>
							<li><a href="index.php?x=faq">Manage FAQ</a></li>
							<li><a href="index.php?x=faq&add">Add</a></li>
						</ul>
					</li>
					<li>
						<a href="javascript:void(0);"><img src="img/icons/packs/fugue/16x16/plus.png" alt="" height="16" width="16">Other Options</a>
						<ul>
							<li><a href="index.php?x=newsletter">Send Newsletter</a></li>
						</ul>
					</li>
					<li>
						<a href="javascript:void(0);"><img src="img/icons/packs/fugue/16x16/sett.png" alt="" height="16" width="16">Settings</a>
						<ul>
							<li><a href="index.php?x=settings">General Settings</a></li>
							<li><a href="index.php?x=regset">Registration Settings</a></li>
							<li><a href="index.php?x=bank">Bank Settings</a></li>
							<li><a href="index.php?x=mailset">Mailing Settings</a></li>
							<li><a href="index.php?x=gateways">Payment Gateways</a></li>
							<li><a href="index.php?x=captcha">Captcha Settings</a></li>
							<li><a href="index.php?x=refset">Affiliate Settings</a></li>
							<li><a href="index.php?x=fbset">Facebook Settings</a></li>
							<li><a href="index.php?x=twset">Twitter Settings</a></li>
							<li><a href="index.php?x=ytset">Youtube Settings</a></li>
							<li><a href="index.php?x=surfset">Surf Settings</a></li>
						</ul>
					</li>
				</ul></nav>		
			</div>
			<div class="bottom sticky">
				<div class="divider"></div>
				<div style="font-size:11px;margin:10px 15px"><b>Your Version:</b> <span style="float:right"><?=($config['version'] < $l_version ? '<a href="https://mn-shop.net/account/download" target="_blank"><strong style="color:red">'.$config['version'].'</strong></a>' : '<strong style="color:green">'.$config['version'].'</strong>')?></span></div>
				<div style="font-size:11px;margin:10px 15px"><span style="float:right"><a id="changelog_open" href="javascript:void(0);"><strong style="color:blue" id="latest_version"><img src="../img/ajax-loader.gif" border="0" alt="<?=$config['version']?>" /></strong></a></span></div>
			</div>
		</aside>
	<? include('sections/'.$page_name.'.php'); ?>
	</div>
	<div id="changelog" class="dialog_no_auto" title="Changelog" style="display:none;"><div id="changelog_content"><center><img src="../img/ajax-loader.gif" border="0" alt="<?=$config['version']?>" id="changelog_loading" /></center></div></div>
	<script> $$.loaded(); $(document).ready(function() { var current_version = '<?=$config['version']?>'; var auto_refresh = setInterval(function () { $('#latest_version').load('index.php?version=1', function (a) { if (a != 'Nulled BY MTimer') { if (a > current_version) { $('#version_alert').show(); } clearInterval(auto_refresh); } }); }, 1500); function getStats() { $.getJSON('../system/ajax.php?a=adminStats', function (c) { $.each(c, function (a, b) { switch (b['class']) { case 'pending_proofs': $('#pending_proofs').html(b['data']).fadeIn('slow'); break; case 'pending_reports': $('#pending_reports').html(b['data']).fadeIn('slow'); break;}}); setInterval(getStats(), 2500); }); } getStats(); $("#changelog").dialog({autoOpen:false,buttons:[{text:"Close",click:function(){$(this).dialog("close")}}]}).find('button').click(function(){$(this).parents('.ui-dialog-content').dialog('close')});$("#changelog_open").live('click',function(){$("#changelog").dialog("open");$.ajax({ url: 'index.php?changelog=1', timeout: 5000, success: function(a) {$("#changelog_content").html('<textarea style="width:100%;height:220px;">'+a+'</textarea>');}});return false}) }); </script>
	<footer class="container_12">
		<ul class="grid_6">
			<li><a href="http://mn-shop.net/">Shop</a></li>
			<li><a href="http://forum.mn-shop.net/">Support Forum</a></li>
		</ul>
		<span class="grid_6">&copy; <?=date('Y')?> <a href="http://mn-shop.net" target="_blank">MafiaNet</a></span>
	</footer>
</body>
</html>
<? $db->Close(); ob_end_flush(); ?>