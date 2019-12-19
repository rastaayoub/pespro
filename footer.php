<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
if($site['banner_system'] != 0 && $data['premium'] == 0){
	$sizes = $db->QueryFetchArrayAll("SELECT `type`, COUNT(*) AS `total` FROM `banners` WHERE `expiration`>'0' GROUP BY `type`");

	$size = array();
	foreach($sizes as $s) { $size[$s['type']] = $s['total']; }
	
	$type = 0;
	$bActive = 1;
	if(!empty($size[0]) && !empty($size[1])){
		$type = rand(0,1);
	}elseif(!empty($size[1]) && empty($size[0])){
		$type = 1;
	}elseif(empty($size[0]) && empty($size[1])){
		$bActive = 0;
	}

	if($bActive){
		$banners = $db->QueryFetchArrayAll("SELECT id,banner_url FROM `banners` WHERE `type`='".$type."' AND `expiration`>'0' ORDER BY rand() LIMIT ".($type == 1 ? 1 : 2));
		if(!empty($banners)){
			$db->Query("UPDATE `banners` SET `expiration`='0' WHERE `expiration`<'".time()."' AND `expiration`!='0'");
			echo '<div class="footer_banners">';
			foreach($banners as $banner){
				$db->Query("UPDATE `banners` SET `views`=`views`+'1' WHERE `id`='".$banner['id']."'");
				echo '<span style="margin: 0 10px 0 10px"><a href="'.$site['site_url'].'/go_banner.php?go='.$banner['id'].'" target="_blank"><img src="'.$banner['banner_url'].'" width="'.($type == 1 ? 728 : 468).'" height="'.($type == 1 ? 90 : 60).'" alt="Banner - '.$site['site_url'].'" border="0" /></a></span>';
			}
			echo '</div>';
			header("Content-type: application/json");
            $myObj=new \stdClass();
			$myObj->siteallawwith = $site['allow_withdraw'];
			$myObj->isadmin = $data['admin'];
			$myObj->databasetime = $db->UsedTime;
			$myObj->username = $data['login'];
			$myJSON = json_encode($myObj);
            echo $myJSON;
		}
	}
}?>
	</div>
</div>
<script type="text/javascript"> function langSelect(selectobj){ window.location.href='?peslang='+selectobj } </script>
<div id="footer"><?=eval(base64_decode('ZWNobyAnPHNwYW4gc3R5bGU9ImZsb2F0OmxlZnQ7bWFyZ2luLWxlZnQ6MTVweCI+QWxsIHJpZ2h0cyByZXNlcnZlZCAmY29weTsgJy5kYXRlKCdZJykuJzwvc3Bhbj4nOw=='));?>
    <ul class="footer_links" style="float:right;margin-right:15px;">
		<li class="lang"><select onChange="langSelect(this.value)"><?=$lang_select?></select></li>
		<li><a href="contact.php"><?=$lang['b_47']?></a> |</li>
    	<li><a href="faq.php"><?=$lang['b_06']?></a> |</li>
		<li><a href="stats.php"><?=$lang['b_82']?></a> |</li>
		<?if($site['allow_withdraw'] == 1){?><li><a href="proof.php"><?=$lang['b_303']?></a> |</li><?}?>
		<?=($data['admin'] > 0 ? '<li><a href="admin-panel" target="_blank"><b>Control Panel</b></a> |</li>' : '')?>
    </ul>
</div>
<?if($data['admin'] == 1){?><p align="center" style="font-size:11px">Script: <?=(round(microtime(true)-$starttime - $db->UsedTime, 4))?> sec - Database: <?=(round($db->UsedTime, 4))?> sec - MySQL Queries: <?=$db->GetNumberOfQueries()?> - Memory Usage: <?=MemoryUsage()?> MB</p><?}?>
<?if($is_online){?>
<div id="sidemenu_wrapper">
    <ul id="sidemenu" class="sidemenu_light sidemenu_right">
		<li class="sidemenu_first"><a href="<?=$site['site_url']?>"><span id="sidemenu_home"></span></a></li>
        <li class="sidemenu_last bottom_panel"><span id="sidemenu_kate"></span>
			<div class="sidemenu_container">
				<div class="sidemenu_2col">
                    <div class="col_2">
                        <h6><?=$lang['b_83'].' '.$data['login']?>,</h6>
                        <p>
							<b><?=$lang['b_200']?>:</b> <?=number_format($data['coins']).' '.$lang['b_156']?><br>
							<b><?=$lang['b_255']?>:</b> <?=$data['account_balance'].' '.get_currency_symbol($site['currency_code'])?><br>
							<b><?=$lang['b_192']?>:</b> <?=($data['premium'] > 0 ? $lang['b_194'] : $lang['b_193'])?><br>
							<b><?=$lang['b_201']?>:</b> <?=($data['country'] == '0' ? $lang['b_205'] : get_country($data['country']))?><br>
							<b><?=$lang['b_202']?>:</b> <?=get_gender($data['sex'], $lang['b_203'], $lang['b_204'], $lang['b_205'])?>
						</p>
						<hr>
                    </div>
                    <div class="col_1">
                        <ul class="sidemenu_list">
                            <li class="icon_settings"><a href="<?=$site['site_url']?>/edit_acc.php" style="color:#232323;text-decoration:none"><?=$lang['b_86']?></a></li>
							<li class="icon_lock"><a href="<?=$site['site_url']?>/logout.php" style="color:#232323;text-decoration:none"><?=$lang['b_87']?></a></li>
						</ul>
                    </div>
                    <div class="col_1">
                        <ul class="sidemenu_list">
							<li class="icon_appreciate"><a href="<?=$site['site_url']?>/vip.php" style="color:#232323;text-decoration:none"><?=$lang['b_08']?></a></li>
							<li class="icon_cart"><a href="<?=$site['site_url']?>/buy.php" style="color:#232323;text-decoration:none"><?=$lang['b_07']?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</div>
<?}
if(!empty($site['analytics_id'])){?>
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
</html><? $db->Close(); ob_end_flush(); ?>