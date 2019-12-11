<?php
include('header.php');
if(!$is_online){?>
<div class="content t-left">
	<p><?=$lang['index_desc']?></p><br />
	<p><?=$lang['index_desc_1']?></p>
	<div class="exchange_container" style="text-align:center">
		<h2><?=$lang['b_91']?></h2>
		<?=$lang['b_92']?><br />
		<a href="register.php"><?=hook_filter('index_icons',"")?></a><br /><br />
		<a class="bbut" href="register.php"><?=$lang['b_165']?></a>
	</div>
</div>
<?
}else{
$warn_active = 0;
if($data['warn_message'] != ''){
	$warn_active = 1;
	if($data['warn_expire'] < time()){
		$db->Query("UPDATE `users` SET `warn_message`='', `warn_expire`='0' WHERE `id`='".$data['id']."'");
		$warn_active = 0;
	}
}

$total_clicks = $db->QueryFetchArray("SELECT SUM(`total_clicks`) AS `clicks` FROM `user_clicks` WHERE `uid`='".$data['id']."'");
?>
<script type="text/javascript"> function open_popup(a,b,c,d){var e=(screen.width-c)/2;var f=(screen.height-d)/2;var g='width='+c+', height='+d;g+=', top='+f+', left='+e;g+=', directories=no';g+=', location=no';g+=', menubar=no';g+=', resizable=no';g+=', scrollbars=no';g+=', status=no';g+=', toolbar=no';newwin=window.open(a,b,g);if(window.focus){newwin.focus()}return false} </script>
<div class="content"><h2 class="title"><?=$lang['b_83'].' '.$data['login']?></h2>
	<?if($warn_active){?><div class="msg"><div class="error"><?=$data['warn_message']?></div></div><?}elseif($site['c_show_msg'] == 1 && $site['c_text_index'] != ''){?><a href="buy.php"><div class="msg"><div class="info"><?=$site['c_text_index']?></div></div></a><?}?>
	<div class="index_info">
		<table style="text-align:left">
			<tr>
				<td rowspan="2"><img src="http://www.gravatar.com/avatar/<?=md5(strtolower(trim($data['email'])))?>?s=45" style="border:1px solid;border-radius:4px"></td>
				<td width="210"><b><?=$lang['b_42']?>:</b> <font color="#d1a44b"><?=number_format($data['coins'])?></font></td>
				<td width="210"><b><?=$lang['b_290']?>:</b> <font color="#fff"><?=number_format($total_clicks['clicks'])?></font></td>
			</tr>
			<tr>
				<td width="210"><b><?=$lang['b_291']?>:</b> <font color="#98ca33"><?=$data['account_balance'].' '.get_currency_symbol($site['currency_code'])?></font></td>
				<td width="210"><b><?=$lang['b_192']?>:</b> <?=($data['premium'] > 0 ? '<font color="gold">'.$lang['b_194'] : '<font color="#fff">'.$lang['b_193'])?></font></td>
			</tr>
		</table>
	</div>
	<div class="exchange_container">
		<div class="exchange_content">
			<h2><?=$lang['b_84']?></h2>
			<?=$lang['b_85']?><br />
			<?=hook_filter('index_icons',"")?><br /><br />
			<a href="edit_acc.php"><div class="exchange_div"><?=$lang['b_86']?></div></a>
			<a href="logout.php"><div class="exchange_div"><?=$lang['b_87']?></div></a>
		</div>
		<div class="aff_url_block"><?=$lang['b_117']?><br /> <input class="text big" type="text" value="<?=$site['site_url']?>/?ref=<?=$data['id']?>" size="40" onclick="this.select()" readonly="true" style="margin-bottom:10px" /><br /> 
			<a href="#" onclick="open_popup('http://www.facebook.com/sharer/sharer.php?u=<?=$site['site_url']?>/?ref=<?=$data['id']?>','Facebook Share',600,300); return false;"><div class="share_button">Share on Facebook</div></a> <a href="#" onclick="open_popup('http://twitter.com/intent/tweet?text=Get+free+Twitter+followers+for+your+profile:+<?=$site['site_url']?>/?ref=<?=$data['id']?>','Twitter Share',520,280); return false;"><div class="share_button">Share on Twitter</div></a>
		</div>
	</div>			
</div>
<?if($site['fb_fan_url'] != ''){?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id; js.async = true;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div style="margin:0 0 20px 20px;float:left;position:relative;border:1px solid #000;border-radius:8px;background:#17262d;width:715px;">
	<div id="club_menu_div" style="position:absolute;z-index:2;margin-left:245px;top:0px;background:#17262d;text-align:center;padding:5px;border-radius:8px;width:220px">
		<a href="http://www.facebook.com/<?=$site['fb_fan_url']?>" target="_blank" style="text-decoration:none;color:#efefef"><b><?=$lang['b_90']?></b></a>   
	</div>
	<div style="position:absolute;width:270px;height:20px;left:220px;z-index:2;bottom:0px;border-radius:10px;border-top:1px solid #999;background:#17262d;text-align:center;padding:5px;">  
		<fb:like href="http://www.facebook.com/<?=$site['fb_fan_url']?>" send="true" layout="button_count" width="150" show_faces="false"></fb:like>
	</div>
	<div style="overflow:hidden;position:relative;width:691px;height:90px;border:1px solid #CCCCCC;display:inline-block;border-radius:10px;margin:10px;">
		<div style="position:absolute;">
			<div style="background:url(theme/<?=$site['theme']?>/images/bg-sign.jpg) repeat scroll right top;width:695px;height:90px;font-size:21px;text-align:center;display:table-cell;vertical-align:middle"><a href="http://www.facebook.com/<?=$site['fb_fan_url']?>" target="_blank" style="color:#fff;text-decoration:none">fb.com/<?=$site['fb_fan_url']?></a></div>
		</div>
	</div>
</div>
<?}}
include('footer.php');?>