<?
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	if(isset($_GET['remAcc'])){
		$db->Query("DELETE FROM `yfav_accounts` WHERE `user`='".$data['id']."'");
	}
?>
<h2 class="title"><?=$lang['b_162']?> - Youtube Favorites</h2>
<?
if(empty($site['yt_api'])){
	echo ($data['admin'] > 0 ? '<div class="msg"><div class="error"><a href="admin-panel/index.php?x=ytset">To enable this module you have to configure it on Admin Panel -> Settings -> Youtube Settings!</a></div></div>' : '<div class="msg"><div class="error">That section is currently unavailable!</div></div>');
}else{
	$check_acc = $db->QueryFetchArray("SELECT account_name,fav_id FROM `yfav_accounts` WHERE `user`='".$data['id']."' LIMIT 1");
	if(empty($check_acc['fav_id'])){
		$msg = '<div class="msg"><div class="info">'.$lang['yfav_15'].'</div></div>';
		if(isset($_POST['submit']) && !empty($_POST['yt_user'])){
			$yt_user = $db->EscapeString($_POST['yt_user']);
			
			$x = get_data('http://gdata.youtube.com/feeds/api/users/'.$yt_user.'?alt=json');
			$x = json_decode($x, true);
			$acc_name = $x['entry']['yt$username']['$t'];
			$acc_id = $x['entry']['link'][0]['href'];
			$acc_id = explode('/', $acc_id);
			$acc_id = $acc_id[4];
			
			if(empty($acc_id)){
				$msg = '<div class="msg"><div class="error">'.$lang['yfav_06'].'</div></div>';
			}else{
				$x = get_data('https://www.googleapis.com/youtube/v3/channels?part=contentDetails&id='.$acc_id.'&key='.$site['yt_api']);
				$x = json_decode($x, true);
				$fav_id = $x['items'][0]['contentDetails']['relatedPlaylists']['favorites'];
				if(!empty($fav_id)){
					$db->Query("INSERT INTO `yfav_accounts`(`user`,`account_name`,`account_id`,`fav_id`)VALUES('".$data['id']."','".$acc_name."','".$acc_id."','".$fav_id."')");
					redirect("p.php?p=yfav");
				}else{
					$msg = '<div class="msg"><div class="error"><b>ERROR:</b> We can\'t find your favorites playlist!</div></div>';
				}
			}
		}
?>
	<form method="post">
		<input class="l_form" onfocus="if(this.value == '<?=$lang['yfav_04']?>') { this.value = ''; }" onblur="if(this.value=='') { this.value = this.defaultValue }" value="<?=$lang['yfav_04']?>" name="yt_user" type="text">
		<input type="submit" class="gbut" name="submit" value="<?=$lang['b_58']?>" />
	</form><?=$msg?>
<?
}else{
$dbt_value = '';
if($site['target_system'] != 2){
	$dbt_value = " AND (a.country = '0' OR FIND_IN_SET('".$data['country']."', a.country)) AND (a.sex = '".$data['sex']."' OR a.sex = '0')";
}

$sites = $db->QueryFetchArrayAll("SELECT a.id, a.url, a.title, a.cpc, b.premium FROM yfav a LEFT JOIN users b ON b.id = a.user LEFT JOIN yfaved c ON c.user_id = '".$data['id']."' AND c.site_id = a.id WHERE a.active = '0' AND (a.max_clicks > a.clicks OR a.max_clicks = '0') AND (a.daily_clicks > a.today_clicks OR a.daily_clicks = '0') AND (b.coins >= a.cpc AND a.cpc >= '2') AND (c.site_id IS NULL AND a.user !='".$data['id']."')".$dbt_value." ORDER BY a.cpc DESC, b.premium DESC".($site['mysql_random'] == 1 ? ', RAND()' : '')." LIMIT 14");
if($sites){
?>
<p class="infobox" style="text-align:right"><b><?=$lang['yfav_14']?>:</b> <?=$check_acc['account_name']?> - <a href="p.php?p=yfav&remAcc" style="color:red"><b>X</b></a></p>
<script>
	msg1 = '<?=mysql_escape_string($lang['yfav_09'])?>';
	msg2 = '<?=mysql_escape_string($lang['yfav_10'])?>';
	msg3 = '<?=mysql_escape_string($lang['yfav_11'])?>';
	msg4 = '<?=mysql_escape_string($lang['yfav_12'])?>';
	msg5 = '<?=mysql_escape_string($lang['yfav_13'])?>';
	msg6 = '<?=mysql_escape_string($lang['b_300'])?>';
	var report_msg1 = '<?=mysql_escape_string($lang['b_277'])?>';
	var report_msg2 = '<?=mysql_escape_string($lang['b_236'])?>';
	var report_msg3 = '<?=mysql_escape_string($lang['b_237'])?>';
	var report_msg4 = '<?=mysql_escape_string(lang_rep($lang['b_252'], array('-NUM-' => $site['report_limit'])))?>';
	var hideref = '<?=hideref('', $site['hideref'], ($site['revshare_api'] != '' ? $site['revshare_api'] : 0))?>';
	var start_click = 1;
	var end_click = <?=count($sites)?>;
	eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('4 7(a,b,c){8 e=9(f);g(e){$.h({i:"j",5:"k/l.m",n:o,p:"q="+a+"&5="+b+"&r="+c+"&s="+e,t:4(d){u(d){6\'1\':0(v);w(a,\'1\');3;6\'2\':0(x);3;y:0(z);3}}})}}',36,36,'alert|||break|function|url|case|report_page|var|prompt||||||report_msg1|if|ajax|type|POST|system|report|php|cache|false|data|id|module|reason|success|switch|report_msg2|skipuser|report_msg4|default|report_msg3'.split('|'),0,{}))
	eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('3 y(){r(z<X){z=z+1}A{Q.Y(Z)}}3 10(b){$("#7").8("<9 B=\\"9/C.D\\" /><E>");$.F({G:"H",s:"I/J/K/L.M",N:"11=12&13="+b,t:3(a){$("#7").8(a);u(b);y()}})}h n;3 14(d,e,f,g,i,m){r(n&&!n.R){}A{h j=(o.w/2)-(o.w/4);h k=(o.x/2)-(o.x/4);h l=15+"16://17.18.19/1a?v="+e;$("#7").8("<9 B=\\"9/C.D\\" /><E>");$.F({G:"H",s:"I/J/K/L.M",N:"1b=1&s="+e+"&1c="+d+"&S="+m,t:3(a){r(!1d(a)){$("#7").8("<0 6=\\"q\\"><0 6=\\"1e\\">"+1f+"</0></0>");h b=1g(3(){n.1h()},1i);h c=1j(3(){r(n.R){T(c);T(b);U(d,g,i,m)}},1k)}A{$("#7").8("<0 6=\\"q\\"><0 6=\\"O\\">"+1l+"</0></0>")}}});n=1m.1n(l,f,"1o=p, Q=p, 1p=p, 1q=p, 1r=p, 1s=V, 1t=V, 1u=p, w="+o.w/2+", x="+o.x/2+", 1v="+k+", 1w="+j)}}3 U(b,c,e,d){$("#7").8("<9 B=\\"9/C.D\\" /><E>");$.F({G:"H",s:"I/J/K/L.M",1x:1y,N:"1z="+b+"&S="+d,t:3(a){1A(a){W\'1\':$("#7").8("<0 6=\\"q\\"><0 6=\\"t\\">"+1B+" <b>"+c+"</b>"+1C+"</0></0>");u(b);y();P;W\'5\':$("#7").8("<0 6=\\"q\\"><0 6=\\"O\\">"+1D+"</0></0>");u(b);P;1E:$("#7").8("<0 6=\\"q\\"><0 6=\\"O\\">"+1F+"</0></0>");P}}})}3 u(a){1G.1H(a).1I.1J="1K"}',62,109,'div|||function|||class|Hint|html|img||||||||var||||||targetWin|screen|no|msg|if|url|success|remove||width|height|click_refresh|start_click|else|src|loader|gif|br|ajax|type|POST|system|modules|yfav|process|php|data|error|break|location|closed|fav_id|clearTimeout|do_click|yes|case|end_click|reload|true|skipuser|step|skip|sid|ModulePopup|hideref|http|www|youtube|com|watch|get|pid|isNaN|info|msg1|setTimeout|close|30000|setInterval|1000|msg2|window|open|toolbar|directories|status|menubar|scrollbars|resizable|copyhistory|top|left|cache|false|id|switch|msg4|msg5|msg6|default|msg3|document|getElementById|style|display|none'.split('|'),0,{}))
</script>
<center><div id="Hint"></div></center>
<div id="getpoints">
<?foreach($sites as $sit){?>	
<div class="follow<?=($sit['premium'] > 0 ? '_vip' : '')?>" id="<?=$sit['id']?>">
	<center>
		<img src="http://img.youtube.com/vi/<?=$sit['url']?>/1.jpg" width="48" height="48" alt="<?=truncate($sit['title'], 10)?>" title="<?=truncate($sit['title'], 50)?>" border="0" /><br /><b><?=$lang['b_42']?></b>: <?=($sit['cpc']-1)?><br>
		<a href="javascript:void(0);" onclick="ModulePopup('<?=$sit['id']?>','<?=$sit['url']?>','Youtube','<?=($sit['cpc']-1)?>','1','<?=$check_acc['fav_id']?>');" class="followbutton"><?=$lang['yfav_05']?></a>
		<font style="font-size:0.8em;">[<a href="javascript:void(0);" onclick="skipuser('<?=$sit['id']?>');" style="color: #666666;font-size:0.9em;"><?=$lang['yfav_07']?></a>]</font>
		<span style="position:absolute;bottom:1px;right:2px;"><a href="javascript:void(0);" onclick="report_page('<?=$sit['id']?>','<?=base64_encode('http://www.youtube.com/watch?v='.$sit['url'])?>','yfav');"><img src="img/report.png" alt="Report" title="Report" border="0" /></a></span>
	</center>
</div>
<?}?>
</div>
<?}else{?>
<div class="msg">
<div class="error"><?=$lang['b_163']?></div>
<div class="info"><a href="buy.php"><b><?=$lang['b_164']?></b></a></div></div>
<?}}}?>