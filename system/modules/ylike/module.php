<?if(! defined('BASEPATH') ){ exit('Unable to view file.'); }?>
<h2 class="title"><?=$lang['b_162']?> - Youtube Like</h2>
<?
$dbt_value = '';
if($site['target_system'] != 2){
	$dbt_value = " AND (a.country = '0' OR FIND_IN_SET('".$data['country']."', a.country)) AND (a.sex = '".$data['sex']."' OR a.sex = '0')";
}

$sites = $db->QueryFetchArrayAll("SELECT a.id, a.url, a.title, a.cpc, b.premium FROM ylike a LEFT JOIN users b ON b.id = a.user LEFT JOIN yliked c ON c.user_id = '".$data['id']."' AND c.site_id = a.id WHERE a.active = '0' AND (a.max_clicks > a.clicks OR a.max_clicks = '0') AND (a.daily_clicks > a.today_clicks OR a.daily_clicks = '0') AND (b.coins >= a.cpc AND a.cpc >= '2') AND (c.site_id IS NULL AND a.user !='".$data['id']."')".$dbt_value." ORDER BY a.cpc DESC, b.premium DESC".($site['mysql_random'] == 1 ? ', RAND()' : '')." LIMIT 14");
if($sites){
?>
<script>
	msg1 = '<?=mysql_escape_string($lang['ylike_09'])?>';
	msg2 = '<?=mysql_escape_string($lang['ylike_10'])?>';
	msg3 = '<?=mysql_escape_string($lang['ylike_11'])?>';
	msg4 = '<?=mysql_escape_string($lang['ylike_12'])?>';
	msg5 = '<?=mysql_escape_string($lang['ylike_13'])?>';
	msg6 = '<?=mysql_escape_string($lang['b_300'])?>';
	var report_msg1 = '<?=mysql_escape_string($lang['b_277'])?>';
	var report_msg2 = '<?=mysql_escape_string($lang['b_236'])?>';
	var report_msg3 = '<?=mysql_escape_string($lang['b_237'])?>';
	var report_msg4 = '<?=mysql_escape_string(lang_rep($lang['b_252'], array('-NUM-' => $site['report_limit'])))?>';
	var hideref = '<?=hideref('', $site['hideref'], ($site['revshare_api'] != '' ? $site['revshare_api'] : 0))?>';
	var start_click = 1;
	var end_click = <?=count($sites)?>;
	eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('4 7(a,b,c){8 e=9(f);g(e){$.h({i:"j",5:"k/l.m",n:o,p:"q="+a+"&5="+b+"&r="+c+"&s="+e,t:4(d){u(d){6\'1\':0(v);w(a,\'1\');3;6\'2\':0(x);3;y:0(z);3}}})}}',36,36,'alert|||break|function|url|case|report_page|var|prompt||||||report_msg1|if|ajax|type|POST|system|report|php|cache|false|data|id|module|reason|success|switch|report_msg2|skipuser|report_msg4|default|report_msg3'.split('|'),0,{}))
	eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('3 x(){q(y<V){y=y+1}z{P.W(X)}}3 Y(b){$("#7").8("<9 A=\\"9/B.C\\" /><D>");$.E({F:"G",r:"H/I/J/K.L",M:"Z=10&11="+b,s:3(a){$("#7").8(a);t(b);x()}})}h m;3 12(d,e,f,g,i){q(m&&!m.Q){}z{h j=(n.u/2)-(n.u/4);h k=(n.w/2)-(n.w/4);h l=13+"14://15.16.17/18?v="+e;$("#7").8("<9 A=\\"9/B.C\\" /><D>");$.E({F:"G",r:"H/I/J/K.L",M:"19=1&r="+e+"&1a="+d,s:3(a){q(!1b(a)){$("#7").8("<0 6=\\"p\\"><0 6=\\"1c\\">"+1d+"</0></0>");h b=1e(3(){m.1f()},1g);h c=1h(3(){q(m.Q){R(c);R(b);S(d,g,i)}},1i)}z{$("#7").8("<0 6=\\"p\\"><0 6=\\"N\\">"+1j+"</0></0>")}}});m=1k.1l(l,f,"1m=o, P=o, 1n=o, 1o=o, 1p=o, 1q=T, 1r=T, 1s=o, u="+n.u/2+", w="+n.w/2+", 1t="+k+", 1u="+j)}}3 S(b,c,e){$("#7").8("<9 A=\\"9/B.C\\" /><D>");$.E({F:"G",r:"H/I/J/K.L",1v:1w,M:"1x="+b,s:3(a){1y(a){U\'1\':$("#7").8("<0 6=\\"p\\"><0 6=\\"s\\">"+1z+" <b>"+c+"</b>"+1A+"</0></0>");t(b);x();O;U\'5\':$("#7").8("<0 6=\\"p\\"><0 6=\\"N\\">"+1B+"</0></0>");t(b);O;1C:$("#7").8("<0 6=\\"p\\"><0 6=\\"N\\">"+1D+"</0></0>");O}}})}3 t(a){1E.1F(a).1G.1H="1I"}',62,107,'div|||function|||class|Hint|html|img||||||||var|||||targetWin|screen|no|msg|if|url|success|remove|width||height|click_refresh|start_click|else|src|loader|gif|br|ajax|type|POST|system|modules|ylike|process|php|data|error|break|location|closed|clearTimeout|do_click|yes|case|end_click|reload|true|skipuser|step|skip|sid|ModulePopup|hideref|http|www|youtube|com|watch|get|pid|isNaN|info|msg1|setTimeout|close|30000|setInterval|1000|msg2|window|open|toolbar|directories|status|menubar|scrollbars|resizable|copyhistory|top|left|cache|false|id|switch|msg4|msg5|msg6|default|msg3|document|getElementById|style|display|none'.split('|'),0,{}))
</script>
<center><div id="Hint"></div></center>
<div id="getpoints">
<?foreach($sites as $sit){?>	
<div class="follow<?=($sit['premium'] > 0 ? '_vip' : '')?>" id="<?=$sit['id']?>">
	<center>
		<img src="http://img.youtube.com/vi/<?=$sit['url']?>/1.jpg" width="48" height="48" alt="<?=truncate($sit['title'], 10)?>" title="<?=truncate($sit['title'], 50)?>" border="0" /><br /><b><?=$lang['b_42']?></b>: <?=($sit['cpc']-1)?><br>
		<a href="javascript:void(0);" onclick="ModulePopup('<?=$sit['id']?>','<?=$sit['url']?>','Youtube','<?=($sit['cpc']-1)?>','1');" class="followbutton"><?=$lang['ylike_05']?></a>
		<font style="font-size:0.8em;">[<a href="javascript:void(0);" onclick="skipuser('<?=$sit['id']?>');" style="color: #666666;font-size:0.9em;"><?=$lang['ylike_07']?></a>]</font>
		<span style="position:absolute;bottom:1px;right:2px;"><a href="javascript:void(0);" onclick="report_page('<?=$sit['id']?>','<?=base64_encode('http://www.youtube.com/watch?v='.$sit['url'])?>','ylike');"><img src="img/report.png" alt="Report" title="Report" border="0" /></a></span>
	</center>
</div>
<?}?>
</div>
<?}else{?>
<div class="msg">
<div class="error"><?=$lang['b_163']?></div>
<div class="info"><a href="buy.php"><b><?=$lang['b_164']?></b></a></div></div>
<?}?>