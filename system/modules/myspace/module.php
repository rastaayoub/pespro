<?
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	if(isset($_GET['remAcc'])){
		$db->Query("DELETE FROM `myspace_accounts` WHERE `user`='".$data['id']."'");
	}
?>
<h2 class="title"><?=$lang['b_162']?> - MySpace Connections</h2>
<?
	$check_acc = $db->QueryFetchArray("SELECT account_username FROM `myspace_accounts` WHERE `user`='".$data['id']."' LIMIT 1");
	if(empty($check_acc['account_username'])){
		$msg = '<div class="msg"><div class="info">'.$lang['myspace_15'].'</div></div>';
		if(isset($_POST['submit']) && !empty($_POST['mys_url'])){
			$mys_url = $db->EscapeString($_POST['mys_url']);

			$html = get_data('https://new.myspace.com/'.strtolower($mys_url));

			$doc = new DOMDocument();
			@$doc->loadHTML($html);

			$metas = $doc->getElementsByTagName('meta');
			for ($i = 0; $i < $metas->length; ++$i){
				$meta = $metas->item($i);
				if($meta->getAttribute('property') == 'og:image')
					$mys_image = $meta->getAttribute('content');
				if($meta->getAttribute('property') == 'og:type')
					$mys_type = $meta->getAttribute('content');
				if($meta->getAttribute('property') == 'og:url')
					$mys_canon_url = $meta->getAttribute('content');
			}
				
			if(!empty($mys_canon_url)){
				$mys_canon_url = explode('/', $mys_canon_url);
				$mys_url = $mys_canon_url[3];
			}

			if(empty($mys_image) || $mys_type != 'profile' || empty($mys_url)){
				$msg = '<div class="msg"><div class="error">'.$lang['myspace_06'].'</div></div>';
			}else{
				$db->Query("INSERT INTO `myspace_accounts`(`user`,`account_username`)VALUES('".$data['id']."','".$mys_url."')");
				redirect("p.php?p=myspace");
			}
		}
?>
	<form method="post">
		<input type="text" name="mys_url" class="l_form" style="width:320px" onfocus="if(this.value == '<?=$lang['myspace_04']?>') { this.value = ''; }" onblur="if(this.value=='') { this.value = this.defaultValue }" value="<?=$lang['myspace_04']?>" />
		<input type="submit" class="gbut" name="submit" value="<?=$lang['b_58']?>" />
	</form><?=$msg?>
<?
}else{
$dbt_value = '';
if($site['target_system'] != 2){
	$dbt_value = " AND (a.country = '0' OR FIND_IN_SET('".$data['country']."', a.country)) AND (a.sex = '".$data['sex']."' OR a.sex = '0')";
}

$sites = $db->QueryFetchArrayAll("SELECT a.id, a.url, a.title, a.image, a.cpc, b.premium FROM myspace a LEFT JOIN users b ON b.id = a.user LEFT JOIN myspaced c ON c.user_id = '".$data['id']."' AND c.site_id = a.id WHERE a.active = '0' AND (a.max_clicks > a.clicks OR a.max_clicks = '0') AND (a.daily_clicks > a.today_clicks OR a.daily_clicks = '0') AND (b.coins >= a.cpc AND a.cpc >= '2') AND (c.site_id IS NULL AND a.user !='".$data['id']."')".$dbt_value." ORDER BY a.cpc DESC, b.premium DESC".($site['mysql_random'] == 1 ? ', RAND()' : '')." LIMIT 14");
if($sites){
?>
<p class="infobox" style="text-align:right"><b><?=$lang['myspace_14']?>:</b> <?=$check_acc['account_username']?> - <a href="p.php?p=myspace&remAcc" style="color:red"><b>X</b></a></p>
<script>
	msg1 = '<?=mysql_escape_string($lang['myspace_09'])?>';
	msg2 = '<?=mysql_escape_string($lang['myspace_10'])?>';
	msg3 = '<?=mysql_escape_string($lang['myspace_11'])?>';
	msg4 = '<?=mysql_escape_string($lang['myspace_12'])?>';
	msg5 = '<?=mysql_escape_string($lang['myspace_13'])?>';
	msg6 = '<?=mysql_escape_string($lang['b_300'])?>';
	var report_msg1 = '<?=mysql_escape_string($lang['b_277'])?>';
	var report_msg2 = '<?=mysql_escape_string($lang['b_236'])?>';
	var report_msg3 = '<?=mysql_escape_string($lang['b_237'])?>';
	var report_msg4 = '<?=mysql_escape_string(lang_rep($lang['b_252'], array('-NUM-' => $site['report_limit'])))?>';
	var hideref = '<?=hideref('', $site['hideref'], ($site['revshare_api'] != '' ? $site['revshare_api'] : 0))?>';
	var start_click = 1;
	var end_click = <?=count($sites)?>;
	eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('4 7(a,b,c){8 e=9(f);g(e){$.h({i:"j",5:"k/l.m",n:o,p:"q="+a+"&5="+b+"&r="+c+"&s="+e,t:4(d){u(d){6\'1\':0(v);w(a,\'1\');3;6\'2\':0(x);3;y:0(z);3}}})}}',36,36,'alert|||break|function|url|case|report_page|var|prompt||||||report_msg1|if|ajax|type|POST|system|report|php|cache|false|data|id|module|reason|success|switch|report_msg2|skipuser|report_msg4|default|report_msg3'.split('|'),0,{}))
	eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('3 x(){r(y<W){y=y+1}z{P.X(Y)}}3 Z(b){$("#7").8("<9 A=\\"9/B.C\\" /><D>");$.E({F:"G",H:"I/J/s/K.L",M:"10=11&12="+b,t:3(a){$("#7").8(a);u(b);x()}})}h n;3 13(d,e,f,g,i,m){r(n&&!n.Q){}z{h j=(o.v/2)-(o.v/4);h k=(o.w/2)-(o.w/4);h l=14+\'15://16.s.17/\'+e;$("#7").8("<9 A=\\"9/B.C\\" /><D>");$.E({F:"G",H:"I/J/s/K.L",M:"18=1&19="+d+"&R="+m,t:3(a){r(!1a(a)){$("#7").8("<0 6=\\"q\\"><0 6=\\"1b\\">"+1c+"</0></0>");h b=1d(3(){n.1e()},1f);h c=1g(3(){r(n.Q){S(c);S(b);T(d,g,i,m)}},1h)}z{$("#7").8("<0 6=\\"q\\"><0 6=\\"N\\">"+1i+"</0></0>")}}});n=1j.1k(l,f,"1l=p, P=p, 1m=p, 1n=p, 1o=p, 1p=U, 1q=U, 1r=p, v="+o.v/2+", w="+o.w/2+", 1s="+k+", 1t="+j)}}3 T(b,c,e,d){$("#7").8("<9 A=\\"9/B.C\\" /><D>");$.E({F:"G",H:"I/J/s/K.L",1u:1v,M:"1w="+b+"&R="+d,t:3(a){1x(a){V\'1\':$("#7").8("<0 6=\\"q\\"><0 6=\\"t\\">"+1y+" <b>"+c+"</b>"+1z+"</0></0>");u(b);x();O;V\'5\':$("#7").8("<0 6=\\"q\\"><0 6=\\"N\\">"+1A+"</0></0>");u(b);O;1B:$("#7").8("<0 6=\\"q\\"><0 6=\\"N\\">"+1C+"</0></0>");O}}})}3 u(a){1D.1E(a).1F.1G="1H"}',62,106,'div|||function|||class|Hint|html|img||||||||var||||||targetWin|screen|no|msg|if|myspace|success|remove|width|height|click_refresh|start_click|else|src|loader|gif|br|ajax|type|POST|url|system|modules|process|php|data|error|break|location|closed|mys_user|clearTimeout|do_click|yes|case|end_click|reload|true|skipuser|step|skip|sid|ModulePopup|hideref|https|new|com|get|pid|isNaN|info|msg1|setTimeout|close|35000|setInterval|1000|msg2|window|open|toolbar|directories|status|menubar|scrollbars|resizable|copyhistory|top|left|cache|false|id|switch|msg4|msg5|msg6|default|msg3|document|getElementById|style|display|none'.split('|'),0,{}))
</script>
<center><div id="Hint"></div></center>
<div id="getpoints">
<?foreach($sites as $sit){?>	
<div class="follow<?=($sit['premium'] > 0 ? '_vip' : '')?>" id="<?=$sit['id']?>">
	<center>
		<img src="<?=$sit['image']?>" width="48" height="48" alt="<?=truncate($sit['title'], 10)?>" title="<?=truncate($sit['title'], 50)?>" border="0" /><br /><b><?=$lang['b_42']?></b>: <?=($sit['cpc']-1)?><br>
		<a href="javascript:void(0);" onclick="ModulePopup('<?=$sit['id']?>','<?=$sit['url']?>','MySpace','<?=($sit['cpc']-1)?>','1','<?=$check_acc['account_username']?>');" class="followbutton"><?=$lang['myspace_05']?></a>
		<font style="font-size:0.8em;">[<a href="javascript:void(0);" onclick="skipuser('<?=$sit['id']?>');" style="color: #666666;font-size:0.9em;"><?=$lang['myspace_07']?></a>]</font>
		<span style="position:absolute;bottom:1px;right:2px;"><a href="javascript:void(0);" onclick="report_page('<?=$sit['id']?>','<?=base64_encode($sit['url'])?>','myspace');"><img src="img/report.png" alt="Report" title="Report" border="0" /></a></span>
	</center>
</div>
<?}?>
</div>
<?}else{?>
<div class="msg">
<div class="error"><?=$lang['b_163']?></div>
<div class="info"><a href="buy.php"><b><?=$lang['b_164']?></b></a></div></div>
<?}}?>