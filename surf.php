<?php
	define('BASEPATH', true);
	require_once('system/config.php');
	if(!$is_online){
		redirect('index.php');
	}

	$surfType = ($data['premium'] > 0 && isset($site['vip_surf_type']) ? $site['vip_surf_type'] : $site['surf_type']);

	if($surfType == 2){
		redirect('index.php');
	}

	$x = parse_url($site['site_url']);

	if(isset($_GET['skip']) && is_numeric($_GET['skip']))
	{
		$skip = $db->EscapeString($_GET['skip']);
		if($db->QueryGetNumRows("SELECT * FROM `surfed` WHERE `site_id`='".$skip."' AND `user_id`='".$data['id']."' LIMIT 1") == 0)
		{
			$db->Query("INSERT INTO `surfed` (user_id, site_id) VALUES('".$data['id']."', '".$skip."')");
		}
	}

	$skip_msg = (isset($_GET['skip']) && isset($_GET['bd']) ? 'Previous website was skipped because frame breaker was detected!' : '');

	if($site['banner_system'] != 0)
	{
		$banner = $db->QueryFetchArray("SELECT id,banner_url FROM `banners` WHERE `expiration`>'0' ORDER BY rand() LIMIT 1");

		if(!empty($banner['id']))
		{
			$db->Query("UPDATE `banners` SET `views`=`views`+'1' WHERE `id`='".$banner['id']."'");
			$banner_code = '<a href="'.$site['site_url'].'/go_banner.php?go='.$banner['id'].'" target="_blank"><img src="'.$banner['banner_url'].'" width="468" height="60" border="0" onerror="this.src=\'/img/surf/banneraderror.png\';" /></a>';
		}
	}

	$dbt_value = '';
	if($site['target_system'] != 2){
		$dbt_value = " AND (a.country = '0' OR FIND_IN_SET('".$data['country']."', a.country)) AND (a.sex = '".$data['sex']."' OR a.sex = '0')";
	}

	$sit['id'] = 0;
	if($surfType != 1)
	{
		$sit = $db->QueryFetchArray("SELECT a.id, a.url, a.title, a.cpc FROM surf a LEFT JOIN users b ON b.id = a.user LEFT JOIN surfed c ON c.user_id = '".$data['id']."' AND c.site_id = a.id WHERE (a.confirm = '0' AND a.active = '0') AND (a.max_clicks > a.clicks OR a.max_clicks = '0') AND (a.daily_clicks > a.today_clicks OR a.daily_clicks = '0') AND (b.coins >= a.cpc AND a.cpc >= '2') AND (c.site_id IS NULL AND a.user !='".$data['id']."')".$dbt_value." ORDER BY a.cpc DESC, b.premium DESC, RAND() LIMIT 1");
	}
	elseif(isset($_GET['sid']) && is_numeric($_GET['sid']))
	{
		$sid = $db->EscapeString($_GET['sid']);
		if($db->QueryGetNumRows("SELECT * FROM `surfed` WHERE `site_id`='".$sid."' AND `user_id`='".$data['id']."' LIMIT 1") == 0)
		{
			$sit = $db->QueryFetchArray("SELECT a.id, a.url, a.title, a.cpc FROM surf a LEFT JOIN users b ON b.id = a.user LEFT JOIN surfed c ON c.user_id = '".$data['id']."' AND c.site_id = a.id WHERE a.id = '".$sid."' AND (a.confirm = '0' AND a.active = '0') AND (a.max_clicks > a.clicks OR a.max_clicks = '0') AND (a.daily_clicks > a.today_clicks OR a.daily_clicks = '0') AND (b.coins >= a.cpc AND a.cpc >= '2') AND (c.site_id IS NULL AND a.user !='".$data['id']."')".$dbt_value." LIMIT 1");
		}
	}

	if($site['surf_time_type'] == 1)
	{
		$surf_time = ($site['surf_time']*($sit['cpc']-1));
	}
	else
	{
		$surf_time = $site['surf_time'];
	}

	if($sit['id'] > 0)
	{
		$key = $surf_time+time();
		
		$result	= $db->Query("INSERT INTO `module_session` (`user_id`,`page_id`,`ses_key`,`module`,`timestamp`)VALUES('".$data['id']."','".$sit['id']."','".$key."','surf','".time()."') ON DUPLICATE KEY UPDATE `ses_key`='".$key."'");
	}
?>
<html style="overflow-y: hidden;">
<head><title><?=(!empty($sit['title']) ? $sit['title'].' - '.$site['site_name'] : $site['site_name'])?></title>
    <link rel="stylesheet" href="system/modules/surf/css.css" type="text/css">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
	<script type="text/javascript" src="system/modules/surf/js.js"></script>
</head>
<body style="overflow:hidden; margin:0px; height:100%;">
<div>
<script type="text/javascript">
	var domain = '<?=$x['host']?>';
	var auto_surf = <?=($surfType == 1 ? 0 : 1)?>;
	var sid = '<?=$sit['id']?>';
	var hash = '<?=MD5(rand(1000,9999))?>';
	var barsize = 1;
	var maxbarsize = 250;
	var numbercounter = <?=$surf_time?>;
	var numbercounter_n = <?=$surf_time?>;
	var adtimer = null;
	var focusFlag = 1;
	var fc_override = <?=($site['surf_fc_req'] == 1 ? 0 : 1)?>;
	var fc_skip = <?=($site['surf_fb_skip'] == 1 ? 1 : 0)?>;
	var buster_listener = 1;
	var buster = 0;
	var buster_red = '?skip=<?=$sit['id']?>&bd';
	var surf_file = 'surf.php';
	var can_leave = <?=($sit['id'] == 0 ? 'true' : 'false')?>;
	var report_msg1 = '<?=mysql_escape_string($lang['b_277'])?>';
	var report_msg2 = '<?=mysql_escape_string($lang['b_236'])?>';
	var report_msg3 = '<?=mysql_escape_string($lang['b_237'])?>';
	eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('4 7(a,b,c){8 e=9(f);g(e){$.h({i:"j",5:"k/l.m",n:o,p:"q="+a+"&5="+b+"&r="+c+"&s="+e,t:4(d){u(d){6\'1\':0(v);w(a,\'1\');3;6\'2\':0(x);3;y:0(z);3}}})}}',36,36,'alert|||break|function|url|case|report_page|var|prompt||||||report_msg1|if|ajax|type|POST|system|report|php|cache|false|data|id|module|reason|success|switch|report_msg2|skipuser|report_msg4|default|report_msg3'.split('|'),0,{}))
	window.onbeforeunload = <?=($site['surf_fb_skip'] == 1 ? 'bust' : 'function () {if (can_leave == false) {var a = "";var b = b || window.event;if (b) {b.returnValue = a;}return a;}}')?>;
</script>
    <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td id="surfbar_td" align="center" height="68" valign="top">
          <div style='height:100%;'>
          <center>
            <table style="color:white;" width="100%" border="0" height="100%">
              <tr>
                <td width="380" style="background:url('<?=$site['site_url']?>/theme/<?=$site['theme']?>/images/logo.png') center left no-repeat;">&nbsp;</td>
                <td width="470" class="nowrap" align="center" valign="center">
				  <?if($sit['id'] > 0){?>
                  <div id="loadingdiv"><img src="<?=$site['site_url']?>/img/loader.gif" border="0" alt="Loading..." /></div>
                  <div id="timerdiv" style='display:none;'>
                    <center>
                    <table cellpadding="0" cellspacing="0">
                      <tr>
                        <td>
                          <div style='width:304px;'>
                          <table cellpadding="0" cellspacing="0" width="100%"><tr>
                            <td width="250" style="background:white; border:2px solid #f0f0f0;">
                              <div id="progressbar" style="background:url('/img/surf/progressbar.gif'); width:1px; float:left; height:22px;"></div>
                            </td>
                            <td style='background:#f0f0f0;'>
                              <div style='color:black; font-size:16px; line-height:22px; font-family:arial; font-weight:bold; text-align:center;' id="numbercounterspan"><?=$surf_time?></div>
                            </td>
                          </tr></table>
                          </div>                 
                        </td>
                      </tr>
                    </table>
                    </center>
                  </div>
                  <div id="focusdiv" style='display:none;'>
                    <center>
                      <table cellpadding="0" cellspacing="0" style='color:white;' border="0"><tr>
                        <td width="40"><img src='/img/surf/errormsg.png'></td>
                        <td>
                          <div style="padding:10px;">
                            <b>You need to keep this window in focus.</b><br>
                            <a style="color:white;" href='javascript:void(0);'>Click <u>here</u> to continue</a>
                          </div>
                        </td>
                      </tr></table>
                    </center>
                  </div>
                  <div id="result_msg" style="position:relative; display:none">
                     <table valign="center" align="center" cellpadding="0" cellspacing="0" width="100%"><tbody><tr>
                       <td><div style="color: white; opacity: 1;" id="show_msg"><img src="<?=$site['site_url']?>/img/loader.gif" border="0" alt="Loading..." /></div></td>
                     </tr></tbody></table>
                  </div>
				<?}?>
				</td>
                <td id="bannertd" style="display:none;"><?if(!empty($banner_code)){?><div class='bannerrotator bannerrotator_clickads'><?=$banner_code?></div><?}?></td>
                <td align="right" valign="top">                  
                  <img class="cursor" onclick="buster_listener=0; openInNewWindow('<?=$sit['url']?>')"; src="/img/surf/icon_openadtab.png" align="absmiddle" width="11" height="10" title="Open website in a new tab">
				  <a href="javascript:void(0)" onclick="report_page('<?=$sit['id']?>','<?=base64_encode($sit['url'])?>','surf');"><img src="img/report.png" alt="Report" title="Report" border="0"></a>
				</td>
              </tr>
            </table>
          </center>
          </div>
          <script>
              checkbanner();
              function checkbanner() {
                  var w = $(document).width();
                  if(w>1340) $(getObject('bannertd')).fadeIn('medium');
                  else $(getObject('bannertd')).fadeOut('medium');
              }
              $(window).resize(function() {
                  checkbanner();
              });
			  startbusterbreaker();
			  window.setTimeout(function() {showtimer();}, 0);
          </script>
        </td>
      </tr>
	  <tr><td id="skipped_td"><?if($sit['id'] > 0){?><?=$lang['b_143']?>: <?=number_format($data['coins'])?> | <?=lang_rep($lang['b_144'], array('-NUM-' => ($sit['cpc']-1)))?> | <a href="?skip=<?=$sit['id']?>"><?=$lang['b_145']?></a><?} if(!empty($skip_msg)){?><span style="float:right"><?=$skip_msg?></span><?}?></td></tr>
      <tr style='height:100%;background:white;'>
        <td>
          <iframe id="pes_frame" src="<?=($sit['id'] == '' ? ($surfType != 1 ? 'system/modules/surf/nocoins.html' : 'system/modules/surf/nopage.html') : hideref($sit['url'], ($site['hideref'] == 1 ? 1 : ($site['hideref'] == 2 ? 2 : 0)), (empty($site['revshare_api']) ? 0 : $site['revshare_api'])))?>" frameborder="0" style="width:100%; height:100%; overflow-x:hidden;" vspace="0" hspace="0"></iframe>
        </td>
      </tr>
    </table>  
    </div>
</body>
</html>