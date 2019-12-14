<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
?>
<h2 class="title"><?=$lang['b_162']?> - Soundcloud Likes</h2>
<div class="infobox"><div class="ucp_link" style="margin-right:5px;display:inline-block"><a href="p.php?p=soundcloud">Soundcloud Followers</a></div><div class="ucp_link active" style="margin-right:5px;display:inline-block"><a href="p.php?p=soundcloud_likes">Soundcloud Likes</a></div></div><br />
<?php
	$dbt_value = '';
	if($site['target_system'] != 2){
		$dbt_value = " AND (a.country = '0' OR FIND_IN_SET('".$data['country']."', a.country)) AND (a.sex = '".$data['sex']."' OR a.sex = '0')";
	}

	$sites = $db->QueryFetchArrayAll("SELECT a.id, a.url, a.title, a.track_id, a.cpc, b.premium FROM soundcloud_likes a LEFT JOIN users b ON b.id = a.user LEFT JOIN soundcloud_liked c ON c.user_id = '".$data['id']."' AND c.site_id = a.id WHERE a.active = '0' AND (a.max_clicks > a.clicks OR a.max_clicks = '0') AND (a.daily_clicks > a.today_clicks OR a.daily_clicks = '0') AND (b.coins >= a.cpc AND a.cpc >= '2') AND (c.site_id IS NULL AND a.user !='".$data['id']."')".$dbt_value." ORDER BY a.cpc DESC, b.premium DESC".($site['mysql_random'] == 1 ? ', RAND()' : '')." LIMIT 14");

	if($site['scf_api'] == ''){
		echo ($data['admin'] > 0 ? '<div class="msg"><div class="error"><a href="admin-panel/index.php?x=settings">To enable this module you have to add Soundcloud customer key on Admin -> Settings!</a></div></div>' :'<div class="msg"><div class="error">This section is currently unavailable!</div></div>');
	}elseif($sites){
?>
<script type="text/javascript">
	var report_msg = '<?=$db->EscapeString($lang['b_277'], 0)?>';
	var start_click = 1;
	var end_click = <?=count($sites)?>;
	eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('0 7(a,b,c){8 e=9(f);2(e){$.3({4:"g",5:"h/3.i",j:{a:\'k\',l:a,5:b,m:c,n:e},o:\'p\',6:0(d){2(d.4===\'6\'){q(a,\'1\')}r(d.s)}})}}',29,29,'function||if|ajax|type|url|success|report_page|var|prompt||||||report_msg|POST|system|php|data|reportPage|id|module|reason|dataType|json|skipuser|alert|message'.split('|'),0,{}))
	eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('0 t(){p(u<V){u=u+1}W{J.X(Y)}}0 Z(b){$("#7").8("<g v=\\"g/w.x\\" /><y>");$.z({m:"A",B:"C/D/E/F.G",H:{10:\'11\',12:b},n:0(a){$("#7").8(a);q(b);t()}})}o h;0 I(a,b){13 14(15.16()*(b-a)+a).17(2)}0 18(d,e,f){p(!h||h.K){$("#7").8("<g v=\\"g/w.x\\" /><y>");o j=(i.r/1.9)-(i.r/I(3,4));o k=(i.s/1.9)-(i.s/I(3,4));$.z({m:"A",B:"C/D/E/F.G",H:{19:1,1a:d},L:\'M\',n:0(a){p(a.m===\'n\'){o b=N(0(){h.1b()},1c);o c=1d(0(){p(h.K){O(c);O(b);$("#7").8("<g v=\\"g/w.x\\" /><y>");N(0(){P(d)},Q)}},Q)}$("#7").8(a.R)}});h=1e.1f(e,f,"1g=l, J=l, 1h=l, 1i=l, 1j=l, 1k=S, 1l=S, 1m=l, r="+i.r/2.6+", s="+i.s/5.5+", 1n="+k+", 1o="+j)}}0 P(b){$.z({m:"A",B:"C/D/E/F.G",H:{1p:b},L:\'M\',n:0(a){1q(a.m){T\'n\':q(b);t();U;T\'1r\':q(b);U}$("#7").8(a.R)}})}0 q(a){$(\'#\'+a).1s()}',62,91,'function|||||||Hint|html||||||||img|targetWin|screen|||no|type|success|var|if|remove|width|height|click_refresh|start_click|src|loader|gif|br|ajax|POST|url|system|modules|soundcloud_likes|process|php|data|getRandomPosition|location|closed|dataType|json|setTimeout|clearTimeout|do_click|500|message|yes|case|break|end_click|else|reload|true|skipuser|step|skip|sid|return|parseFloat|Math|random|toFixed|ModulePopup|get|pid|close|210000|setInterval|window|open|toolbar|directories|status|menubar|scrollbars|resizable|copyhistory|top|left|id|switch|not_available|hide'.split('|'),0,{}))
</script>
<div id="Hint"></div>
<div id="getpoints">
<?
	foreach($sites as $sit){
?>	
<div class="follow<?=($sit['premium'] > 0 ? '_vip' : '')?>" id="<?=$sit['id']?>">
	<center>
		<img src="img/icons/soundcloud.png" width="48" height="48" alt="<?=truncate($sit['title'], 10)?>" title="<?=truncate($sit['title'], 10)?>" border="0" /><br /><b><?=$lang['b_42']?></b>: <?=($sit['cpc']-1)?><br>
		<a href="javascript:void(0);" onclick="ModulePopup('<?=$sit['id']?>','<?=hideref('https://w.soundcloud.com/player/?url=https://api.soundcloud.com/tracks/'.$sit['track_id'].'&auto_play=true&buying=false&liking=true&download=false&sharing=false&show_artwork=false&show_comments=false&show_playcount=false&show_user=false&hide_related=false&visual=false&start_track=0&callback=true')?>','Soundcloud Likes');" class="followbutton"><?=$lang['soundcloud_likes_05']?></a>
		<font style="font-size:0.8em;">[<a href="javascript:void(0);" onclick="skipuser('<?=$sit['id']?>');" style="color: #666666;font-size:0.9em;"><?=$lang['b_360']?></a>]</font>
		<span style="position:absolute;bottom:1px;right:2px;"><a href="javascript:void(0);" onclick="report_page('<?=$sit['id']?>','<?=base64_encode($sit['url'])?>','soundcloud_likes');"><img src="img/report.png" alt="Report" title="Report" border="0" /></a></span>
	</center>
</div>
<?}?>
</div>
<?}else{?>
<div class="msg">
<div class="error"><?=$lang['b_163']?></div>
<div class="info"><a href="buy.php"><b><?=$lang['b_164']?></b></a></div></div>
<?}?>