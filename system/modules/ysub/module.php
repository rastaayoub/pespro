<?if(! defined('BASEPATH') ){ exit('Unable to view file.'); }?>
<h2 class="title"><?=$lang['b_162']?> - <?=$lang['ysub_10']?></h2>
<?
$dbt_value = '';
if($site['target_system'] != 2){
	$dbt_value = " AND (a.country = '0' OR FIND_IN_SET('".$data['country']."', a.country)) AND (a.sex = '".$data['sex']."' OR a.sex = '0')";
}

$sites = $db->QueryFetchArrayAll("SELECT a.id, a.url, a.title, a.y_av, a.cpc, b.premium FROM ysub a LEFT JOIN users b ON b.id = a.user LEFT JOIN ysubed c ON c.user_id = '".$data['id']."' AND c.site_id = a.id WHERE a.active = '0' AND (a.max_clicks > a.clicks OR a.max_clicks = '0') AND (a.daily_clicks > a.today_clicks OR a.daily_clicks = '0') AND (b.coins >= a.cpc AND a.cpc >= '2') AND (c.site_id IS NULL AND a.user !='".$data['id']."')".$dbt_value." ORDER BY a.cpc DESC, b.premium DESC".($site['mysql_random'] == 1 ? ', RAND()' : '')." LIMIT 14");

if(empty($site['yt_api'])){
	echo ($data['admin'] > 0 ? '<div class="msg"><div class="error"><a href="admin-panel/index.php?x=ytset">To enable this module you have to configure it on Admin Panel -> Settings -> Youtube Settings!</a></div></div>' : '<div class="msg"><div class="error">That section is currently unavailable!</div></div>');
}elseif($sites){
?>
<p class="infobox"><?=$lang['ysub_11']?></p>
<script>
	var report_msg = '<?=$db->EscapeString($lang['b_277'], 0)?>';
	var start_click = 1;
	var end_click = <?=count($sites)?>;
	eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('0 7(a,b,c){8 e=9(f);2(e){$.3({4:"g",5:"h/3.i",j:{a:\'k\',l:a,5:b,m:c,n:e},o:\'p\',6:0(d){2(d.4===\'6\'){q(a,\'1\')}r(d.s)}})}}',29,29,'function||if|ajax|type|url|success|report_page|var|prompt||||||report_msg|POST|system|php|data|reportPage|id|module|reason|dataType|json|skipuser|alert|message'.split('|'),0,{}))
	eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('0 q(){m(r<T){r=r+1}U{H.V(W)}}0 X(b){$("#5").6("<7 s=\\"7/t.u\\" /><v>");$.w({h:"x",y:"A/B/C/D.E",F:{Y:\'Z\',10:b},k:0(a){$("#5").6(a);n(b);q()}})}l 8;0 G(a,b){11 12(13.14()*(b-a)+a).15(2)}0 16(a,b,c){m(!8||8.I){$("#5").6("<7 s=\\"7/t.u\\" /><v>");l f=(d.o/1.9)-(d.o/G(3,4));l g=(d.p/1.9)-(d.p/G(3,4));$.w({h:"x",y:"A/B/C/D.E",F:{17:1,18:a},J:\'K\',k:0(z){m(z.h===\'k\'){l i=L(0(){8.19()},1a);l j=1b(0(){m(8.I){M(i);M(j);$("#5").6("<7 s=\\"7/t.u\\" /><v>");L(0(){N(a)},O)}},O)}$("#5").6(z.P)}});8=1c.1d(b,c,"1e=e, H=e, 1f=e, 1g=e, 1h=e, 1i=Q, 1j=Q, 1k=e, o="+d.o/1.9+", p="+d.p/1.9+", 1l="+g+", 1m="+f)}}0 N(b){$.w({h:"x",y:"A/B/C/D.E",F:{1n:b},J:\'K\',k:0(a){1o(a.h){R\'k\':n(b);q();S;R\'1p\':n(b);S}$("#5").6(a.P)}})}0 n(a){$(\'#\'+a).1q()}',62,89,'function|||||Hint|html|img|targetWin|||||screen|no|||type|||success|var|if|remove|width|height|click_refresh|start_click|src|loader|gif|br|ajax|POST|url||system|modules|ysub|process|php|data|getRandomPosition|location|closed|dataType|json|setTimeout|clearTimeout|do_click|500|message|yes|case|break|end_click|else|reload|true|skipuser|step|skip|sid|return|parseFloat|Math|random|toFixed|ModulePopup|get|pid|close|30000|setInterval|window|open|toolbar|directories|status|menubar|scrollbars|resizable|copyhistory|top|left|id|switch|not_available|hide'.split('|'),0,{}))
</script>
<center><div id="Hint"></div></center>
<div id="getpoints">
<?foreach($sites as $sit){?>	
<div class="follow<?=($sit['premium'] > 0 ? '_vip' : '')?>" id="<?=$sit['id']?>">
	<center>
		<img src="<?=$sit['y_av']?>" border="0" alt="<?=$sit['title']?>" width="48" height="48" class="follower"><br><b><?=$lang['b_42']?></b>: <?=($sit['cpc']-1)?><br>
		<a href="javascript:void(0);" onclick="ModulePopup('<?=$sit['id']?>','<?=hideref($sit['url'])?>','Youtube');" class="followbutton"><?=$lang['ysub_13']?></a>
		<font style="font-size:0.8em;">[<a href="javascript:void(0);" onclick="skipuser('<?=$sit['id']?>');" style="color: #666666;font-size:0.9em;"><?=$lang['b_360']?></a>]</font>
		<span style="position:absolute;bottom:1px;right:2px;"><a href="javascript:void(0);" onclick="report_page('<?=$sit['id']?>','<?=base64_encode($sit['url'])?>','ysub');"><img src="img/report.png" alt="Report" title="Report" border="0" /></a></span>
	</center>
</div>
<?}?>
</div>
<?}else{?>
<div class="msg">
<div class="error"><?=$lang['b_163']?></div>
<div class="info"><a href="buy.php"><b><?=$lang['b_164']?></b></a></div></div>
<?}?>