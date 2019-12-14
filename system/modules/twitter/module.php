<?if(! defined('BASEPATH') ){ exit('Unable to view file.'); }?>
<h2 class="title"><?=$lang['b_162']?> - Twitter</h2>
<div class="infobox"><div class="ucp_link active" style="margin-right:5px;display:inline-block"><a href="p.php?p=twitter">Twitter Followers</a></div><div class="ucp_link" style="margin-right:5px;display:inline-block"><a href="p.php?p=retweet">Twitter Tweet</a></div></div><br />
<?
$dbt_value = '';
if($site['target_system'] != 2){
	$dbt_value = " AND (a.country = '0' OR FIND_IN_SET('".$data['country']."', a.country)) AND (a.sex = '".$data['sex']."' OR a.sex = '0')";
}

$sites = $db->QueryFetchArrayAll("SELECT a.id, a.url, a.cpc, b.premium FROM twitter a LEFT JOIN users b ON b.id = a.user LEFT JOIN followed c ON c.user_id = '".$data['id']."' AND c.site_id = a.id WHERE a.active = '0' AND (a.max_clicks > a.clicks OR a.max_clicks = '0') AND (a.daily_clicks > a.today_clicks OR a.daily_clicks = '0') AND (b.coins >= a.cpc AND a.cpc >= '2') AND (c.site_id IS NULL AND a.user !='".$data['id']."')".$dbt_value." ORDER BY a.cpc DESC, b.premium DESC".($site['mysql_random'] == 1 ? ', RAND()' : '')." LIMIT 14");

if(empty($site['twitter_token']) || empty($site['twitter_token_secret']) || empty($site['twitter_consumer_key']) || empty($site['twitter_consumer_secret'])){
	echo ($data['admin'] > 0 ? '<div class="msg"><div class="error"><a href="admin-panel/index.php?x=twset">To enable this module you have to configure it on Admin Panel -> Settings -> Twitter Settings!</a></div></div>' : '<div class="msg"><div class="error">That section is currently unavailable!</div></div>');
}elseif($sites){
?>
<script type="text/javascript">
	msg1 = '<?=mysql_escape_string($lang['twt_17'])?>';
	msg2 = '<?=mysql_escape_string($lang['twt_18'])?>';
	msg3 = '<?=mysql_escape_string($lang['twt_19'])?>';
	msg4 = '<?=mysql_escape_string($lang['twt_13'])?>';
	msg5 = 'We cannot contact Twitter...';
	msg6 = '<?=mysql_escape_string($lang['b_300'])?>';
	var report_msg1 = '<?=mysql_escape_string($lang['b_277'])?>';
	var report_msg2 = '<?=mysql_escape_string($lang['b_236'])?>';
	var report_msg3 = '<?=mysql_escape_string($lang['b_237'])?>';
	var report_msg4 = '<?=mysql_escape_string(lang_rep($lang['b_252'], array('-NUM-' => $site['report_limit'])))?>';
	var hideref = '<?=hideref('', $site['hideref'], ($site['revshare_api'] != '' ? $site['revshare_api'] : 0))?>';
	var start_click = 1;
	var end_click = <?=count($sites)?>;
	eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('4 7(a,b,c){8 e=9(f);g(e){$.h({i:"j",5:"k/l.m",n:o,p:"q="+a+"&5="+b+"&r="+c+"&s="+e,t:4(d){u(d){6\'1\':0(v);w(a,\'1\');3;6\'2\':0(x);3;y:0(z);3}}})}}',36,36,'alert|||break|function|url|case|report_page|var|prompt||||||report_msg1|if|ajax|type|POST|system|report|php|cache|false|data|id|module|reason|success|switch|report_msg2|skipuser|report_msg4|default|report_msg3'.split('|'),0,{}))
	eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('3 w(){q(x<U){x=x+1}y{O.V(W)}}3 X(b,c){$("#7").8("<9 z=\\"9/A.B\\" /><C>");$.D({E:"F",r:"G/H/I/J.K",L:"Y=Z&10="+b,s:3(a){$("#7").8(a);t(b);w()}})}h m;3 11(d,e,f,g,i){q(m&&!m.P){}y{h j=(n.u/2)-(n.u/4);h k=(n.v/2)-(n.v/4);h l=12+e;$("#7").8("<9 z=\\"9/A.B\\" /><C>");$.D({E:"F",r:"G/H/I/J.K",L:"13=1&r="+e+"&14="+d,s:3(a){q(!15(a)){$("#7").8("<0 6=\\"p\\"><0 6=\\"16\\">"+17+"</0></0>");h b=18(3(){m.19()},1a);h c=1b(3(){q(m.P){Q(c);Q(b);R(d,g,i)}},1c)}y{$("#7").8("<0 6=\\"p\\"><0 6=\\"M\\">"+1d+"</0></0>")}}});m=1e.1f(l,f,"1g=o, O=o, 1h=o, 1i=o, 1j=o, 1k=S, 1l=S, 1m=o, u="+n.u/2+", v="+n.v/2+", 1n="+k+", 1o="+j)}}3 R(b,c,e){$("#7").8("<9 z=\\"9/A.B\\" /><C>");$.D({E:"F",r:"G/H/I/J.K",1p:1q,L:"1r="+b,s:3(a){1s(a){T\'1\':$("#7").8("<0 6=\\"p\\"><0 6=\\"s\\">"+1t+" <b>"+c+"</b>"+1u+"</0></0>");t(b);w();N;T\'5\':$("#7").8("<0 6=\\"p\\"><0 6=\\"M\\">"+1v+"</0></0>");t(b);N;1w:$("#7").8("<0 6=\\"p\\"><0 6=\\"M\\">"+1x+"</0></0>");N}}})}3 t(a){1y.1z(a).1A.1B="1C"}',62,101,'div|||function|||class|Hint|html|img||||||||var|||||targetWin|screen|no|msg|if|url|success|remove|width|height|click_refresh|start_click|else|src|loader|gif|br|ajax|type|POST|system|modules|twitter|process|php|data|error|break|location|closed|clearTimeout|do_click|yes|case|end_click|reload|true|skipuser|step|skip|sid|ModulePopup|hideref|get|pid|isNaN|info|msg4|setTimeout|close|30000|setInterval|1000|msg5|window|open|toolbar|directories|status|menubar|scrollbars|resizable|copyhistory|top|left|cache|false|id|switch|msg2|msg3|msg6|default|msg1|document|getElementById|style|display|none'.split('|'),0,{}))
</script>
<div id="Hint"></div>
<div id="getpoints">
<?
  foreach($sites as $sit){
?>	
<div class="follow<?=($sit['premium'] > 0 ? '_vip' : '')?>" id="<?=$sit['id']?>">
	<center>
		<img src="img/icons/tweet.png" border="0" alt="@<?=$sit['url']?>" title="@<?=$sit['url']?>" width="48" height="48" class="follower"><br><b><?=$lang['b_42']?></b>: <?=($sit['cpc']-1)?><br>
		<a href="javascript:void(0);" onclick="ModulePopup('<?=$sit['id']?>','https://twitter.com/intent/user?screen_name=<?=$sit['url']?>','Twitter','<?=($sit['cpc']-1)?>','1');" class="followbutton"><?=$lang['twt_12']?></a>
		<font style="font-size:0.8em;">[<a href="javascript:void(0);" onclick="skipuser('<?=$sit['id']?>','<?=$sit['url']?>');remove('<?=$sit['id']?>');" style="color: #999999;font-size:0.9em;"><?=$lang['twt_14']?></a>]</font>
		<span style="position:absolute;bottom:1px;right:2px;"><a href="javascript:void(0);" onclick="report_page('<?=$sit['id']?>','<?=base64_encode('http://twitter.com/'.$sit['url'])?>','twitter');"><img src="img/report.png" alt="Report" title="Report" border="0" /></a></span>
	</center>
</div>
<?}?>
</div>
<?}else{?>
<div class="msg">
<div class="error"><?=$lang['b_163']?></div>
<div class="info"><a href="buy.php"><b><?=$lang['b_164']?></b></a></div></div>
<?}?>