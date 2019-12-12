<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml">
<head><title><?=$site['site_name']?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="<?=$site['site_description']?>">
<meta name="keywords" content="social media exchange,get more twitter followers,free linkedin shares,free twitter followers,follower exchange,stumbleupon followers,top social exchange site,best social exchange site,get more youtube views,get more youtube likes,free youtube views">
<meta property="og:title" content="<?=$site['site_name']?> - Free Social Media Exchange">
<meta property="og:type" content="website">
<meta property="og:url" content="<?=$site['site_url']?>">
<meta property="og:image" content="<?=$site['site_url']?>/theme/<?=$site['theme']?>/images/splash.png">
<meta property="og:site_name" content="<?=$site['site_name']?>">
<meta property="og:description" content="<?=$site['site_description']?>">
<meta http-equiv="refresh" content="45; url=<?=$site['site_url']?>">
<link href="http://fonts.googleapis.com/css?family=Rokkitt:400,700" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$site['site_url']?>/theme/<?=$site['theme']?>/style.css" type="text/css" />
<link rel="stylesheet" href="<?=$site['site_url']?>/theme/<?=$site['theme']?>/splash.css" type="text/css" />
</head>
<body>
	<div class="head_logo">
		<a href="<?=$site['site_url']?>"><img src="<?=$site['site_url']?>/theme/<?=$site['theme']?>/images/logo.png" alt="<?=$site['site_name']?>" border="0" /></a>
	</div>
    <div class="splash">
		<h2><?=$lang['s_02']?></h2>
		<div align="center"><a href="<?=$site['site_url']?>/register.php" target="_blank"><?=hook_filter('index_icons',"")?></a><br></div>
		<div align="center">
			<ol style="font-size:18px;line-height:26px;margin:20px 2px 0px 5px;text-align:left">
				<?=$lang['s_01']?>
			</ol>
			<h2 style="margin-left:20px;text-align:left;font-size:19px"><?=lang_rep($lang['s_06'], array('-COINS-' => $site['reg_coins'], '-CASH-' => $site['reg_cash']))?></h2>
		</div>
		<div class="splash_image"><img src="<?=$site['site_url']?>/theme/<?=$site['theme']?>/images/splash.png" border="0"></div>
		<h2 style="text-align:left;margin-left:20px;margin-bottom:0px;margin-top:20px; font-size:18px"><b><span style="font-size:22px;"><?=$lang['s_03']?></span></b> - <?=$lang['s_04']?></h2>
		<h2 style="margin-top:5px;text-align:left;margin-left:20px"> <strong><span style="color:#63b129;"><?=$site['ref_coins']?></span> <?=$lang['b_42']?> + <span style="color:#63b129;"><?=get_currency_symbol($site['currency_code']).$site['ref_cash']?></span> + <span style="color:#63b129;"><?=$site['ref_sale']?>% </span><?=$lang['s_05']?></strong>!</h2><br>
	  
		<div align="center">
		   <a class="gbut" href="<?=$site['site_url']?>/register.php"><strong><?=$lang['b_165']?></strong></a>
		</div>
    </div>
<? if(!empty($site['analytics_id'])){ ?>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?=$site['analytics_id']?>']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script><?}?>
<!-- PES Pro v<?=$config['version']?> - Powered by www.MN-Shop.net -->
</body>
</html>