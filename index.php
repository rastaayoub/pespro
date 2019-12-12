<?php
	include('header.php');

	if(!$is_online)
	{
?>
<div class="content t-left">
	<div class="homebox" style="margin-top:0"><?=$lang['index_desc']?></div>
	<div class="homebox"><?=$lang['index_desc_1']?>
	<div class="exchange_container" style="text-align:center">
		<h2><?=$lang['b_91']?></h2>
		<?=$lang['b_92']?><br />
		<a href="register.php"><?=hook_filter('index_icons',"")?></a><br /><br />
		<a class="bbut" href="register.php"><b><?=$lang['b_165']?></b></a>
	</div></div>
</div>
<?php
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
<div class="content"><h2 class="title"><?=$lang['b_83'].' '.$data['login']?></h2>
	<?php
		if($warn_active)
		{
			echo '<div class="msg"><div class="error">'.$data['warn_message'].'</div></div>';
		}
		elseif($site['c_show_msg'] == 1 && $site['c_text_index'] != '')
		{
			echo '<a href="buy.php"><div class="msg"><div class="info">'.$site['c_text_index'].'</div></div></a>';
		} 
	?>
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
			<a href="edit_acc.php" class="blue_div_button"><?=$lang['b_86']?></a><div class="circle-or"><?=$lang['b_369']?></div><a href="logout.php" class="green_div_button"><?=$lang['b_87']?></a>
		</div>
		<div class="aff_url_block"><?=$lang['b_117']?><br /> <input class="text big" type="text" value="<?=$site['site_url']?>/?ref=<?=$data['id']?>" size="40" onclick="this.select()" readonly="true" style="margin-bottom:10px" /><br /> 
			<a href="#" onclick="open_popup('http://www.facebook.com/sharer/sharer.php?u=<?=$site['site_url']?>/?ref=<?=$data['id']?>','Facebook Share',600,300); return false;"><div class="share_button">Share on Facebook</div></a><a href="#" onclick="open_popup('http://twitter.com/intent/tweet?text=Get+free+Twitter+followers+for+your+profile:+<?=$site['site_url']?>/?ref=<?=$data['id']?>','Twitter Share',520,280); return false;"><div class="share_button">Share on Twitter</div></a><a href="#" onclick="open_popup('https://plus.google.com/share?url=<?=$site['site_url']?>/?ref=<?=$data['id']?>','Google Share',600,300); return false;"><div class="share_button">Share on Google</div></a>
		</div>
	</div>			
</div>
<script type="text/javascript"> function open_popup(a,b,c,d){var e=(screen.width-c)/2;var f=(screen.height-d)/2;var g='width='+c+', height='+d;g+=', top='+f+', left='+e;g+=', directories=no';g+=', location=no';g+=', menubar=no';g+=', resizable=no';g+=', scrollbars=no';g+=', status=no';g+=', toolbar=no';newwin=window.open(a,b,g);if(window.focus){newwin.focus()}return false} </script>
<?php
	}

	include('footer.php');
?>