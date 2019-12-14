<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

$surfType = ($data['premium'] > 0 && isset($site['vip_surf_type']) ? $site['vip_surf_type'] : $site['surf_type']);

if($surfType == 0){
	redirect('index.php');
}elseif($surfType == 2){
	if($site['target_system'] != 2){
		$dbt_value = " AND (a.country = '0' OR FIND_IN_SET('".$data['country']."', a.country)) AND (a.sex = '".$data['sex']."' OR a.sex = '0')";
	}
	$check = $db->QueryFetchArray("SELECT COUNT(a.id) AS total FROM surf a LEFT JOIN users b ON b.id = a.user LEFT JOIN surfed c ON c.user_id = '".$data['id']."' AND c.site_id = a.id WHERE a.active = '0' AND (a.max_clicks > a.clicks OR a.max_clicks = '0') AND (a.daily_clicks > a.today_clicks OR a.daily_clicks = '0') AND (b.coins >= a.cpc AND a.cpc >= '2') AND (c.site_id IS NULL AND a.user !='".$data['id']."')".$dbt_value." ORDER BY a.cpc DESC, b.premium DESC LIMIT 1");
if($check['total'] == 0){
	echo '<div class="msg"><div class="error">'.$lang['b_163'].'</div><div class="info"><a href="buy.php"><b>'.$lang['b_164'].'</b></a></div></div>';
}else{
?>
<script type="text/javascript">
var msg1 = '<?=$db->EscapeString($lang['surf_09'])?>';
var msg2 = '<?=$db->EscapeString($lang['surf_10'])?>';
var msg3 = '<?=$db->EscapeString($lang['surf_11'])?>';
var msg4 = '<?=$db->EscapeString($lang['b_163'])?>';
var msg5 = '<?=$db->EscapeString($lang['surf_12'])?>';
var msg6 = '<?=$db->EscapeString($lang['b_156'])?>';
var report_msg = '<?=$db->EscapeString($lang['b_277'], 0)?>';
var hideref = '<?=hideref('', $site['hideref'], ($site['revshare_api'] != '' ? $site['revshare_api'] : 0))?>';
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('6 5;6 J=(f.i/2)-(f.i/4);6 K=(f.k/2)-(f.k/4);6 L=\'1k=g,v=g,1l=g,1m=g,1n=g,1o=M,1p=M,1q=g,i=\'+f.i/2+\',k=\'+f.k/2+\',1r=\'+K+\',1s=\'+J;3 1t(){8(5){N()}j{O();w()}}3 O(){5=1u.1v(\'P:Q\',\'1w\'+R.1x(R.1y()*1z),L);x()}3 x(){8(!5||5.y){}j{$.l({m:"z",h:"A/S/B/T.C",U:D,E:"1A=1",n:3(a){1B(a){1C\'1D\':V();W;1E:6 b=1F.1G(a);8(F!=\'\'){6 c=F+b[\'X\']}j{6 c=F+b[\'h\']}5.v.Y(c);Z(b[\'G\'],b[\'10\']);$(\'#o\').11();$(\'#o\').9(\'<7 12="1H:#1I;1J:1K 1L;13:#14;i:1M;1N:1O;15-1P:1Q"><16 17="H">\'+b[\'10\']+\'</16> <a 18="\'+b[\'h\']+\'" 1R="1S" 12="13:#14"><b>\'+b[\'19\']+\'</b></a> <a 18="1T:1U(0);" 1V="1a(\\\'\'+b[\'G\']+\'\\\',\\\'\'+b[\'X\']+\'\\\',\\\'B\\\');"><1b 1W="1b/1X.1Y" 1Z="1c" 19="1c" 15="0" /></a><20 />\'+21+\' \'+b[\'22\']+\' \'+23+\'</7>\');W}}});$(\'#p\').9(24)}}6 q;3 Z(b,c){q=I(3(){5.v.Y(\'P:Q\');$.l({m:"z",h:"A/S/B/T.C",U:D,E:"25=1&G="+b,n:3(a){x()}})},(c*1d));r((c-1),1)}3 s(){r(0,0);q=1e(q);5=D}6 t;3 r(a,b){8(a>0){$(\'#H\').9(a);t=I(\'r(\'+(a-1)+\', 1);\',1d)}j{$(\'#H\').9(\'0\')}8(b==0){t=1e(t)}}3 V(){5.1f();$(\'#o\').1g();$(\'#p\').1g();$(\'#1h\').11();$(\'#1h\').9(\'<7 u="1i"><7 u="26">\'+27+\'</7></7>\');s()}3 N(){8(5){5.1f();$(\'#p\').9(1j);s()}}3 w(){8(!5||5.y||5.y==\'28\'){$(\'#o\').9(\'<7 u="1i"><7 u="29">\'+2a+\'</7></7>\');$(\'#p\').9(1j);s()}j{I(3(){w()},2b)}}3 1a(a,b,c){6 e=2c(2d);8(e){$.l({m:"z",h:"A/l.C",E:{a:\'2e\',17:a,h:b,2f:c,2g:e},2h:\'2i\',n:3(d){8(d.m===\'n\'){2j(a,\'1\')}2k(d.2l)}})}}',62,146,'|||function||surfWindow|var|div|if|html||||||screen|no|url|width|else|height|ajax|type|success|surfInfo|surfButton|exe_count|displayCountdown|stopExec|exe_cd|class|location|checkWin|openWin|closed|POST|system|surf|php|false|data|hideref|sid|countDown|setTimeout|aLeft|aTop|surfWindowParams|yes|closeWin|emptyWindow|about|blank|Math|modules|process|cache|noSites|break|eurl|replace|startExec|time|show|style|color|171717|border|span|id|href|title|report_page|img|Report|1000|clearTimeout|close|hide|surfHint|msg|msg2|toolbar|directories|status|menubar|scrollbars|resizable|copyhistory|top|left|startSurf|window|open|pespro_|floor|random|1e10|get|switch|case|NO_SITE|default|jQuery|parseJSON|background|efefef|margin|2px|auto|280px|padding|4px|radius|3px|target|_blank|javascript|void|onclick|src|report|png|alt|br|msg1|cpc|msg6|msg3|complete|info|msg4|undefined|error|msg5|500|prompt|report_msg|reportPage|module|reason|dataType|json|skipuser|alert|message'.split('|'),0,{}))
</script>
<h2 class="title"><?=$lang['b_162']?> - Traffic Exchange</h2>
<div class="infobox"><?=$lang['surf_13']?></div><br />
<div id="surfHint" style="display:none"></div><div id="surfInfo" style="display:none"></div>
<button id="surfButton" class="bbut" style="color:#fff" onclick="javascript:startSurf()"><?=$lang['surf_10']?></button>
<?}}else{?>
<h2 class="title"><?=$lang['b_162']?> - Traffic Exchange</h2>
<?
$dbt_value = '';
if($site['target_system'] != 2){
	$dbt_value = " AND (a.country = '0' OR FIND_IN_SET('".$data['country']."', a.country)) AND (a.sex = '".$data['sex']."' OR a.sex = '0')";
}

$sites = $db->QueryFetchArrayAll("SELECT a.id, a.url, a.title, a.cpc, b.premium FROM surf a LEFT JOIN users b ON b.id = a.user LEFT JOIN surfed c ON c.user_id = '".$data['id']."' AND c.site_id = a.id WHERE a.confirm = '0' AND  a.active = '0' AND (a.max_clicks > a.clicks OR a.max_clicks = '0') AND (a.daily_clicks > a.today_clicks OR a.daily_clicks = '0') AND (b.coins >= a.cpc AND a.cpc >= '2') AND (c.site_id IS NULL AND a.user !='".$data['id']."')".$dbt_value." ORDER BY a.cpc DESC, b.premium DESC".($site['mysql_random'] == 1 ? ', RAND()' : '')." LIMIT 14");
if($sites){
?>
<script type="text/javascript">
	var report_msg = '<?=$db->EscapeString($lang['b_277'], 0)?>';
	var base = '<?=$site['site_url']?>';
	var start_click = 1;
	var end_click = <?=count($sites)?>;
	eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('0 7(a,b,c){8 e=9(f);2(e){$.3({4:"g",5:"h/3.i",j:{a:\'k\',l:a,5:b,m:c,n:e},o:\'p\',6:0(d){2(d.4===\'6\'){q(a,\'1\')}r(d.s)}})}}',29,29,'function||if|ajax|type|url|success|report_page|var|prompt||||||report_msg|POST|system|php|data|reportPage|id|module|reason|dataType|json|skipuser|alert|message'.split('|'),0,{}))
	eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('3 8(){o(4<p){4=4+1}q{r.s(t)}}3 u(a){v=w(x+"/9.c?y="+a+"&z="+A.B(),"C");5(a)}3 D(b){d.e("6").f.g="E";$("#6").h("<i F=\\"2\\" G=\\"0\\" H=\\"I\\" J=\\"7\\" K=\\"L\\"><j><k><7><l M=2><b>N... <m O=\\"m/n-P.Q\\"></b></l></7></k></j></i><R>");$.n({S:"T",U:"V/W/9/X.c?Y=Z",10:"11="+b,12:3(a){$("#6").h(a)}});5(b)}3 5(a){d.e(a).f.g="13";8()}',62,66,'|||function|start_click|remove|txtHint|center|click_refresh|surf|||php|document|getElementById|style|display|html|table|tr|td|font|img|ajax|if|end_click|else|location|reload|true|opensite|childWindow|open|base|sid|rand|Math|random|View|skipuser|block|cellpadding|cellspacing|width|500|align|class|maintable|size|Skipping|src|loader|gif|br|type|GET|url|system|modules|process|step|skip|data|id|success|none'.split('|'),0,{}))
</script>
<div id="txtHint" style="display:none;"></div>
<div id="getpoints">
<?
  foreach($sites as $sit){
?>	
<div class="follow<?=($sit['premium'] > 0 ? '_vip' : '')?>" id="<?=$sit['id']?>">
	<center>
		<?=truncate($sit['title'], 11)?><br /><br /><b><?=$lang['b_42']?></b>: <?=($sit['cpc']-1)?><br>
		<a href="javascript:void(0);" onclick="opensite('<?=$sit['id']?>');" class="followbutton"><?=$lang['surf_04']?></a>
		<font style="font-size:0.8em;">[<a href="javascript:void(0);" onclick="skipuser('<?=$sit['id']?>');" style="color: #999999;font-size:0.9em;"><?=$lang['b_360']?></a>]</font>
		<span style="position:absolute;bottom:1px;right:2px;"><a href="javascript:void(0);" onclick="report_page('<?=$sit['id']?>','<?=base64_encode($sit['url'])?>','surf');"><img src="img/report.png" alt="Report" title="Report" border="0" /></a></span>
	</center>
</div>
<?}?>
</div>
<?}else{?>
<div class="msg">
<div class="error"><?=$lang['b_163']?></div>
<div class="info"><a href="buy.php"><b><?=$lang['b_164']?></b></a></div></div>
<?}}?>