<?php
include('header.php');
if(!$is_online || $site['refsys'] != 1){
	redirect('index.php');
}

$refs = $db->QueryGetNumRows("SELECT id FROM `users` WHERE `ref`='".$data['id']."' AND `activate`='0'");
?>
<div class="content">
<h2 class="title"><?=$lang['b_12']?></h2>
	<div style="height:175px">
		<div class="aff_block" style="margin-right:21px">
			<div class="aff_content">              
				<div style="margin-bottom:5px;" class="aff_block_title">Info</div>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<b style="font-size:13px"><?=$lang['b_113']?>:</b>
							<ul style="font-size:12px;list-style-type:disc">
								<li style="margin-left:20px;"><?=lang_rep($lang['b_114'], array('-NUM-' => $site['ref_coins']))?></li><?if($site['paysys'] == 1){?>
								<li style="margin-left:20px;"><?=lang_rep($lang['b_115'], array('-SUM-' => $site['ref_cash']))?></li>
								<li style="margin-left:20px;"><?=lang_rep($lang['b_116'], array('-NUM-' => $site['ref_sale']))?></li><?}?>
							</ul>
							<?if($site['aff_click_req'] > 0){?><p style="font-size:12px"><b><?=lang_rep($lang['b_251'], array('-NUM-' => $site['aff_click_req']))?></b></p><?}?>
					</table>
			</div>
		</div>
		<div class="aff_block">
            <div class="aff_content">              
                <div class="aff_block_title"><?=$lang['b_82']?></div>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="50%">
                            <p class="aff_block_p"><?=$lang['b_119']?>:</p>
                            <a class="aff_block_p2" href="referrals.php" style="color:#0b516f"><?=$refs?></a>
                        </td>
                        <td width="50%">
                            <p class="aff_block_p"><?=$lang['b_255']?>:</p>
                            <a class="aff_block_p2" href="bank.php" style="color:#0b516f"><font color="green"><?=get_currency_symbol($site['currency_code'])?> </font><?=$data['account_balance']?></a>
                        </td>
                    </tr>
                </table>
                <div class="aff_content_bottom"><?=$lang['b_117']?></div>
                <center><input type="text" value="<?=$site['site_url']?>/?ref=<?=$data['id']?>" onclick="this.select()" style="font-size:11px;padding:4px;width:270px;margin:0 auto;" readonly="true" /></center>
				<?if($site['paysys'] == 1 && $site['allow_withdraw'] == 1){?><hr><center><input type="button" onClick="location.href='bank.php?withdraw'" class="gbut" value="<?=$lang['b_97']?>"></center><?}?>
			</div>
        </div>
	</div>
	<div style="clear:both"></div>
	<div class="aff_banner_block">
        <div class="aff_banner">              
            <div class="aff_banner_title">Banner (468x60)</div><br> 
				<table width="100%" border="0" cellpadding="3" cellspacing="1">
					<tr>
						<td valign="top" align="center">
							<img src="<?=$site['site_url']?>/promo/banner.png" border="0" />
						</td>
					</tr>
					<tr>    
						<td valign="top" align="center">
							<b><?=$lang['b_118']?></b><br>
							<textarea class="textarea" style="width:550px;height:60px" onclick="this.select()" readonly="true"><a href="<?=$site['site_url']?>/?ref=<?=$data['id']?>" target="_blank"><img src="<?=$site['site_url']?>/promo/banner.png" alt="<?=$site['site_name']?>" border="0" /></a></textarea>
						</td>
					</tr>
					<tr>    
						<td valign="top" align="center">
							<b>BB Code</b><br>
							<textarea class="textarea" style="width:550px;height:30px" onclick="this.select()" readonly="true">[url=<?=$site['site_url']?>/?ref=<?=$data['id']?>][img]<?=$site['site_url']?>/promo/banner.png[/img][/url]</textarea>                        
						</td>
					</tr>                   
				</table>
        </div>
    </div>
</div>
<?include('footer.php');?>