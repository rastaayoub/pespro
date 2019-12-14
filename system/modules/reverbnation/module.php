<?
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	if(isset($_GET['remAcc'])){
		$db->Query("DELETE FROM `reverbnation_accounts` WHERE `user`='".$data['id']."'");
	}
?>
<h2 class="title"><?=$lang['b_162']?> - ReverbNation Fans</h2>
<?
	$check_acc = $db->QueryFetchArray("SELECT account_name,account_username FROM `reverbnation_accounts` WHERE `user`='".$data['id']."' LIMIT 1");
	if(empty($check_acc['account_username'])){
		$msg = '<div class="msg"><div class="info">'.$lang['reverbnation_15'].'</div></div>';
		if(isset($_POST['submit']) && !empty($_POST['rev_url'])){
			$rev_url = $db->EscapeString($_POST['rev_url']);

			if(!preg_match('|^http(s)?://www.reverbnation.com/fan/(.*)?$|i', $rev_url)){
				$msg = '<div class="msg"><div class="error">'.$lang['reverbnation_16'].' http://www.reverbnation.com/fan/USERNAME</div></div>';
			}else{
				$html = get_data($rev_url);

				$doc = new DOMDocument();
				@$doc->loadHTML($html);

				$metas = $doc->getElementsByTagName('meta');
				for ($i = 0; $i < $metas->length; ++$i){
					$meta = $metas->item($i);
					if($meta->getAttribute('name') == 'description')
						$rev_desc = $meta->getAttribute('content');
					if($meta->getAttribute('name') == 'image_src')
						$rev_image = $meta->getAttribute('content');
					if($meta->getAttribute('property') == 'og:url')
						$rev_url = $meta->getAttribute('content');
				}
				
				$rev_user = explode('/', $rev_url);
				$rev_user = $rev_user[4];
				if(preg_match('/[?]/', $rev_user)){
					$rev_user = explode('?', $rev_user);
					$rev_user = $rev_user[0];
				}
				
				$rev_name = explode(' - ', $rev_desc);
				$rev_name = $rev_name[0];
				
				if(empty($rev_image) || empty($rev_name) || empty($rev_user)){
					$msg = '<div class="msg"><div class="error">'.$lang['reverbnation_06'].'</div></div>';
				}else{
					$db->Query("INSERT INTO `reverbnation_accounts`(`user`,`account_name`,`account_username`)VALUES('".$data['id']."','".$rev_name."','".$rev_user."')");
					redirect("p.php?p=reverbnation");
				}
			}
		}
?>
	<form method="post">
		<input type="text" name="rev_url" class="l_form" style="width:320px" onfocus="if(this.value == '<?=$lang['reverbnation_04']?>') { this.value = ''; }" onblur="if(this.value=='') { this.value = this.defaultValue }" value="<?=$lang['reverbnation_04']?>" />
		<input type="submit" class="gbut" name="submit" value="<?=$lang['b_58']?>" />
	</form><?=$msg?>
<?
}else{
$dbt_value = '';
if($site['target_system'] != 2){
	$dbt_value = " AND (a.country = '0' OR FIND_IN_SET('".$data['country']."', a.country)) AND (a.sex = '".$data['sex']."' OR a.sex = '0')";
}

$sites = $db->QueryFetchArrayAll("SELECT a.id, a.url, a.title, a.image, a.cpc, b.premium FROM reverbnation a LEFT JOIN users b ON b.id = a.user LEFT JOIN reverbnation_done c ON c.user_id = '".$data['id']."' AND c.site_id = a.id WHERE a.active = '0' AND (a.max_clicks > a.clicks OR a.max_clicks = '0') AND (a.daily_clicks > a.today_clicks OR a.daily_clicks = '0') AND (b.coins >= a.cpc AND a.cpc >= '2') AND (c.site_id IS NULL AND a.user !='".$data['id']."')".$dbt_value." ORDER BY a.cpc DESC, b.premium DESC".($site['mysql_random'] == 1 ? ', RAND()' : '')." LIMIT 14");
if($sites){
?>
<p class="infobox" style="text-align:right"><b><?=$lang['reverbnation_14']?>:</b> <?=$check_acc['account_name']?> - <a href="p.php?p=reverbnation&remAcc" style="color:red"><b>X</b></a></p>
<script>
	msg1 = '<?=mysql_escape_string($lang['reverbnation_09'])?>';
	msg2 = '<?=mysql_escape_string($lang['reverbnation_10'])?>';
	msg3 = '<?=mysql_escape_string($lang['reverbnation_11'])?>';
	msg4 = '<?=mysql_escape_string($lang['reverbnation_12'])?>';
	msg5 = '<?=mysql_escape_string($lang['reverbnation_13'])?>';
	msg6 = '<?=mysql_escape_string($lang['b_300'])?>';
	var report_msg1 = '<?=mysql_escape_string($lang['b_277'])?>';
	var report_msg2 = '<?=mysql_escape_string($lang['b_236'])?>';
	var report_msg3 = '<?=mysql_escape_string($lang['b_237'])?>';
	var report_msg4 = '<?=mysql_escape_string(lang_rep($lang['b_252'], array('-NUM-' => $site['report_limit'])))?>';
	var hideref = '<?=hideref('', $site['hideref'], ($site['revshare_api'] != '' ? $site['revshare_api'] : 0))?>';
	var start_click = 1;
	var end_click = <?=count($sites)?>;
	eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('4 7(a,b,c){8 e=9(f);g(e){$.h({i:"j",5:"k/l.m",n:o,p:"q="+a+"&5="+b+"&r="+c+"&s="+e,t:4(d){u(d){6\'1\':0(v);w(a,\'1\');3;6\'2\':0(x);3;y:0(z);3}}})}}',36,36,'alert|||break|function|url|case|report_page|var|prompt||||||report_msg1|if|ajax|type|POST|system|report|php|cache|false|data|id|module|reason|success|switch|report_msg2|skipuser|report_msg4|default|report_msg3'.split('|'),0,{}))
	eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('3 w(){r(x<W){x=x+1}y{P.X(Y)}}3 Z(b){$("#7").8("<9 z=\\"9/A.B\\" /><C>");$.D({E:"F",G:"H/I/J/K.L",M:"10=11&12="+b,s:3(a){$("#7").8(a);t(b);w()}})}h n;3 13(d,e,f,g,i,m){r(n&&!n.Q){}y{h j=(o.u/2)-(o.u/4);h k=(o.v/2)-(o.v/4);h l=14+e;$("#7").8("<9 z=\\"9/A.B\\" /><C>");$.D({E:"F",G:"H/I/J/K.L",M:"15=1&16="+d+"&R="+m,s:3(a){r(!17(a)){$("#7").8("<0 6=\\"q\\"><0 6=\\"18\\">"+19+"</0></0>");h b=1a(3(){n.1b()},1c);h c=1d(3(){r(n.Q){S(c);S(b);T(d,g,i,m)}},1e)}y{$("#7").8("<0 6=\\"q\\"><0 6=\\"N\\">"+1f+"</0></0>")}}});n=1g.1h(l,f,"1i=p, P=p, 1j=p, 1k=p, 1l=p, 1m=U, 1n=U, 1o=p, u="+o.u/2+", v="+o.v/2+", 1p="+k+", 1q="+j)}}3 T(b,c,e,d){$("#7").8("<9 z=\\"9/A.B\\" /><C>");$.D({E:"F",G:"H/I/J/K.L",1r:1s,M:"1t="+b+"&R="+d,s:3(a){1u(a){V\'1\':$("#7").8("<0 6=\\"q\\"><0 6=\\"s\\">"+1v+" <b>"+c+"</b>"+1w+"</0></0>");t(b);w();O;V\'5\':$("#7").8("<0 6=\\"q\\"><0 6=\\"N\\">"+1x+"</0></0>");t(b);O;1y:$("#7").8("<0 6=\\"q\\"><0 6=\\"N\\">"+1z+"</0></0>");O}}})}3 t(a){1A.1B(a).1C.1D="1E"}',62,103,'div|||function|||class|Hint|html|img||||||||var||||||targetWin|screen|no|msg|if|success|remove|width|height|click_refresh|start_click|else|src|loader|gif|br|ajax|type|POST|url|system|modules|reverbnation|process|php|data|error|break|location|closed|rev_user|clearTimeout|do_click|yes|case|end_click|reload|true|skipuser|step|skip|sid|ModulePopup|hideref|get|pid|isNaN|info|msg1|setTimeout|close|35000|setInterval|1000|msg2|window|open|toolbar|directories|status|menubar|scrollbars|resizable|copyhistory|top|left|cache|false|id|switch|msg4|msg5|msg6|default|msg3|document|getElementById|style|display|none'.split('|'),0,{}))
</script>
<center><div id="Hint"></div></center>
<div id="getpoints">
<?foreach($sites as $sit){?>	
<div class="follow<?=($sit['premium'] > 0 ? '_vip' : '')?>" id="<?=$sit['id']?>">
	<center>
		<img src="<?=$sit['image']?>" width="48" height="48" alt="<?=truncate($sit['title'], 10)?>" title="<?=truncate($sit['title'], 50)?>" border="0" /><br /><b><?=$lang['b_42']?></b>: <?=($sit['cpc']-1)?><br>
		<a href="javascript:void(0);" onclick="ModulePopup('<?=$sit['id']?>','<?=$sit['url']?>','ReverbNation','<?=($sit['cpc']-1)?>','1','<?=$check_acc['account_username']?>');" class="followbutton"><?=$lang['reverbnation_05']?></a>
		<font style="font-size:0.8em;">[<a href="javascript:void(0);" onclick="skipuser('<?=$sit['id']?>');" style="color: #666666;font-size:0.9em;"><?=$lang['reverbnation_07']?></a>]</font>
		<span style="position:absolute;bottom:1px;right:2px;"><a href="javascript:void(0);" onclick="report_page('<?=$sit['id']?>','<?=base64_encode($sit['url'])?>','reverbnation');"><img src="img/report.png" alt="Report" title="Report" border="0" /></a></span>
	</center>
</div>
<?}?>
</div>
<?}else{?>
<div class="msg">
<div class="error"><?=$lang['b_163']?></div>
<div class="info"><a href="buy.php"><b><?=$lang['b_164']?></b></a></div></div>
<?}}?>