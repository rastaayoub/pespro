<?if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
if($site['surf_type'] == 0){
	redirect('index.php');
}elseif($site['surf_type'] == 2){
	if($site['target_system'] != 2){
		$dbt_value = " AND (a.country = '0' OR FIND_IN_SET('".$data['country']."', a.country)) AND (a.sex = '".$data['sex']."' OR a.sex = '0')";
	}
	$check = $db->QueryFetchArray("SELECT COUNT(a.id) AS total FROM surf a LEFT JOIN users b ON b.id = a.user LEFT JOIN surfed c ON c.user_id = '".$data['id']."' AND c.site_id = a.id WHERE a.active = '0' AND (a.max_clicks > a.clicks OR a.max_clicks = '0') AND (a.daily_clicks > a.today_clicks OR a.daily_clicks = '0') AND (b.coins >= a.cpc AND a.cpc >= '2') AND (c.site_id IS NULL AND a.user !='".$data['id']."')".$dbt_value." ORDER BY a.cpc DESC, b.premium DESC LIMIT 1");
if($check['total'] == 0){
	echo '<div class="msg"><div class="error">'.$lang['b_163'].'</div><div class="info"><a href="buy.php"><b>'.$lang['b_164'].'</b></a></div></div>';
}else{
?>
<script type="text/javascript">
var msg1 = '<?=mysql_escape_string($lang['surf_09'])?>';
var msg2 = '<?=mysql_escape_string($lang['surf_10'])?>';
var msg3 = '<?=mysql_escape_string($lang['surf_11'])?>';
var msg4 = '<?=mysql_escape_string($lang['b_163'])?>';
var msg5 = '<?=mysql_escape_string($lang['surf_12'])?>';
var msg6 = '<?=mysql_escape_string($lang['b_156'])?>';
var report_msg1 = '<?=mysql_escape_string($lang['b_277'])?>';
var report_msg2 = '<?=mysql_escape_string($lang['b_236'])?>';
var report_msg3 = '<?=mysql_escape_string($lang['b_237'])?>';
var report_msg4 = '<?=mysql_escape_string(lang_rep($lang['b_252'], array('-NUM-' => $site['report_limit'])))?>';
var hideref = '<?=hideref('', $site['hideref'], ($site['revshare_api'] != '' ? $site['revshare_api'] : 0))?>';
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('7 5;7 1h=(f.i/2)-(f.i/4);7 13=(f.r/2)-(f.r/4);7 H=\'2b=h,2a=h,25=h,20=h,1X=h,1W=W,1V=W,1U=h,i=\'+f.i/2+\',r=\'+f.r/2+\',1T=\'+13+\',1S=\'+1h;3 1R(){9(5){1c()}s{M();u()}}3 M(){5=P.Q(\'1Q:1P\',"U",H);B()}3 B(){9(!5||5.C){}s{$.D({E:"F",g:"L/17/I/18.J",K:m,v:"1O=1",x:3(a){S(a){z\'1M\':V();j;X:7 b=1L.1J(a);7 c=1E+b[\'g\'];5=P.Q(c,"U",H);12(b[\'G\'],b[\'14\']);$(\'#p\').16();$(\'#p\').8(\'<6 T="1B:#1z;1x:1v 1s;1e:#1f;i:1p;1o:1n;1j-1N:1m"><1k 1i="y">\'+b[\'14\']+\'</1k> <a 1g="\'+b[\'g\']+\'" 1q="1r" T="1e:#1f"><b>\'+b[\'1d\']+\'</b></a> <a 1g="1t:1u(0);" 1w="1b(\\\'\'+b[\'G\']+\'\\\',\\\'\'+b[\'1y\']+\'\\\',\\\'I\\\');"><1a 1A="1a/19.1C" 1D="11" 1d="11" 1j="0" /></a><1F />\'+1G+\' \'+b[\'1H\']+\' \'+1I+\'</6>\');j}}});$(\'#n\').8(1K)}}7 t;3 12(b,c){t=A(3(){$.D({E:"F",g:"L/17/I/18.J",K:m,v:"1l=1&G="+b,x:3(a){B()}})},(c*O));k((c-1),1)}3 q(){k(0,0);t=15(t);5=m}7 l;3 k(a,b){9(a>0){$(\'#y\').8(a);l=A(\'k(\'+(a-1)+\', 1);\',O)}s{$(\'#y\').8(\'0\')}9(b==0){l=15(l)}}3 V(){5.10();$(\'#p\').Z();$(\'#n\').Z();$(\'#Y\').16();$(\'#Y\').8(\'<6 o="R"><6 o="1Y">\'+1Z+\'</6></6>\');q()}3 1c(){9(5){5.10();$(\'#n\').8(N);q()}}3 u(){9(!5||5.C||5.C==\'21\'){$(\'#p\').8(\'<6 o="R"><6 o="22">\'+23+\'</6></6>\');$(\'#n\').8(N);q()}s{A(3(){u()},24)}}3 1b(a,b,c){7 e=26(27);9(e){$.D({E:"F",g:"L/19.J",K:m,v:"1i="+a+"&g="+b+"&28="+c+"&29="+e,x:3(d){S(d){z\'1\':w(2c);2d(a,\'1\');j;z\'2\':w(2e);j;X:w(2f);j}}})}}',62,140,'|||function||surfWindow|div|var|html|if||||||screen|url|no|width|break|displayCountdown|exe_cd|false|surfButton|class|surfInfo|stopExec|height|else|exe_count|checkWin|data|alert|success|countDown|case|setTimeout|openWin|closed|ajax|type|POST|sid|surfWindowParams|surf|php|cache|system|emptyWindow|msg2|1000|window|open|msg|switch|style|TrafficExchange|noSites|yes|default|surfHint|hide|close|Report|startExec|aTop|time|clearTimeout|show|modules|process|report|img|report_page|closeWin|title|color|171717|href|aLeft|id|border|span|complete|3px|4px|padding|280px|target|_blank|auto|javascript|void|2px|onclick|margin|eurl|efefef|src|background|png|alt|hideref|br|msg1|cpc|msg6|parseJSON|msg3|jQuery|NO_SITE|radius|get|blank|about|startSurf|left|top|copyhistory|resizable|scrollbars|menubar|info|msg4|status|undefined|error|msg5|200|directories|prompt|report_msg1|module|reason|location|toolbar|report_msg2|skipuser|report_msg4|report_msg3'.split('|'),0,{}))
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
	var report_msg1 = '<?=mysql_escape_string($lang['b_277'])?>';
	var report_msg2 = '<?=mysql_escape_string($lang['b_236'])?>';
	var report_msg3 = '<?=mysql_escape_string($lang['b_237'])?>';
	var report_msg4 = '<?=mysql_escape_string(lang_rep($lang['b_252'], array('-NUM-' => $site['report_limit'])))?>';
	var base = '<?=$site['site_url']?>';
	var start_click = 1;
	var end_click = <?=count($sites)?>;
	eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('4 7(a,b,c){8 e=9(f);g(e){$.h({i:"j",5:"k/l.m",n:o,p:"q="+a+"&5="+b+"&r="+c+"&s="+e,t:4(d){u(d){6\'1\':0(v);w(a,\'1\');3;6\'2\':0(x);3;y:0(z);3}}})}}',36,36,'alert|||break|function|url|case|report_page|var|prompt||||||report_msg1|if|ajax|type|POST|system|report|php|cache|false|data|id|module|reason|success|switch|report_msg2|skipuser|report_msg4|default|report_msg3'.split('|'),0,{}))
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
		<font style="font-size:0.8em;">[<a href="javascript:void(0);" onclick="skipuser('<?=$sit['id']?>');" style="color: #999999;font-size:0.9em;"><?=$lang['surf_05']?></a>]</font>
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