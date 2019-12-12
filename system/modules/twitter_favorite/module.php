<?if(! defined('BASEPATH') ){ exit('Unable to view file.'); }?>
<h2 class="title"><?=$lang['b_162']?> - Twitter Favorite</h2>
<?
$dbt_value = '';
if($site['target_system'] != 2){
	$dbt_value = " AND (a.country = '0' OR FIND_IN_SET('".$data['country']."', a.country)) AND (a.sex = '".$data['sex']."' OR a.sex = '0')";
}

$sites = $db->QueryFetchArrayAll("SELECT a.id, a.url, a.tweet_id, a.cpc, b.premium FROM twitter_favorite a LEFT JOIN users b ON b.id = a.user LEFT JOIN twitter_favorited c ON c.user_id = '".$data['id']."' AND c.site_id = a.id WHERE a.active = '0' AND (a.max_clicks > a.clicks OR a.max_clicks = '0') AND (a.daily_clicks > a.today_clicks OR a.daily_clicks = '0') AND (b.coins >= a.cpc AND a.cpc >= '2') AND (c.site_id IS NULL AND a.user !='".$data['id']."')".$dbt_value." ORDER BY a.cpc DESC, b.premium DESC".($site['mysql_random'] == 1 ? ', RAND()' : '')." LIMIT 14");

if(empty($site['twitter_token']) || empty($site['twitter_token_secret']) || empty($site['twitter_consumer_key']) || empty($site['twitter_consumer_secret'])){
	echo ($data['admin'] > 0 ? '<div class="msg"><div class="error"><a href="admin-panel/index.php?x=twset">To enable this module you have to configure it on Admin Panel -> Settings -> Twitter Settings!</a></div></div>' : '<div class="msg"><div class="error">That section is currently unavailable!</div></div>');
}elseif($sites){
?>
<script type="text/javascript">
	var report_msg = '<?=$db->EscapeString($lang['b_277'], 0)?>';
	var start_click = 1;
	var end_click = <?=count($sites)?>;
	eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('0 7(a,b,c){8 e=9(f);2(e){$.3({4:"g",5:"h/3.i",j:{a:\'k\',l:a,5:b,m:c,n:e},o:\'p\',6:0(d){2(d.4===\'6\'){q(a,\'1\')}r(d.s)}})}}',29,29,'function||if|ajax|type|url|success|report_page|var|prompt||||||report_msg|POST|system|php|data|reportPage|id|module|reason|dataType|json|skipuser|alert|message'.split('|'),0,{}))
	eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('0 s(){n(t<T){t=t+1}U{H.V(W)}}0 X(b){$("#5").6("<7 u=\\"7/v.w\\" /><x>");$.y({i:"z",o:"A/B/C/D.E",F:{Y:\'Z\',10:b},l:0(a){$("#5").6(a);p(b);s()}})}m 8;0 G(a,b){11 12(13.14()*(b-a)+a).15(2)}0 16(d,e,f){n(!8||8.I){$("#5").6("<7 u=\\"7/v.w\\" /><x>");m j=(g.q/1.9)-(g.q/G(3,4));m k=(g.r/1.9)-(g.r/G(3,4));$.y({i:"z",o:"A/B/C/D.E",F:{17:1,o:e,18:d},J:\'K\',l:0(a){n(a.i===\'l\'){m b=L(0(){8.19()},1a);m c=1b(0(){n(8.I){M(c);M(b);$("#5").6("<7 u=\\"7/v.w\\" /><x>");L(0(){N(d)},O)}},O)}$("#5").6(a.P)}});8=1c.1d(e,f,"1e=h, H=h, 1f=h, 1g=h, 1h=h, 1i=Q, 1j=Q, 1k=h, q="+g.q/1.9+", r="+g.r/1.9+", 1l="+k+", 1m="+j)}}0 N(b){$.y({i:"z",o:"A/B/C/D.E",F:{1n:b},J:\'K\',l:0(a){1o(a.i){R\'l\':p(b);s();S;R\'1p\':p(b);S}$("#5").6(a.P)}})}0 p(a){$(\'#\'+a).1q()}',62,89,'function|||||Hint|html|img|targetWin||||||||screen|no|type|||success|var|if|url|remove|width|height|click_refresh|start_click|src|loader|gif|br|ajax|POST|system|modules|twitter_favorite|process|php|data|getRandomPosition|location|closed|dataType|json|setTimeout|clearTimeout|do_click|500|message|yes|case|break|end_click|else|reload|true|skipuser|step|skip|sid|return|parseFloat|Math|random|toFixed|ModulePopup|get|pid|close|30000|setInterval|window|open|toolbar|directories|status|menubar|scrollbars|resizable|copyhistory|top|left|id|switch|not_available|hide'.split('|'),0,{}))
</script>
<div id="Hint"></div>
<div id="getpoints">
<?
  foreach($sites as $sit){
?>	
<div class="follow<?=($sit['premium'] > 0 ? '_vip' : '')?>" id="<?=$sit['id']?>">
	<center>
		<img src="img/icons/tweet.png" border="0" alt="" title="<?=$sit['title']?>" width="48" height="48" class="follower"><br><b><?=$lang['b_42']?></b>: <?=($sit['cpc']-1)?><br>
		<a href="javascript:void(0);" onclick="ModulePopup('<?=$sit['id']?>','<?=hideref('https://twitter.com/intent/favorite?tweet_id='.$sit['tweet_id'])?>','Retweet','<?=($sit['cpc']-1)?>','1');" class="followbutton"><?=$lang['twitter_08']?></a>
		<font style="font-size:0.8em;">[<a href="javascript:void(0);" onclick="skipuser('<?=$sit['id']?>');remove('<?=$sit['id']?>');" style="color: #999999;font-size:0.9em;"><?=$lang['b_360']?></a>]</font>
		<span style="position:absolute;bottom:1px;right:2px;"><a href="javascript:void(0);" onclick="report_page('<?=$sit['id']?>','<?=base64_encode($sit['url'])?>','twitter_favorite');"><img src="img/report.png" alt="Report" title="Report" border="0" /></a></span>
	</center>
</div>
<?}?>
</div>
<?}else{?>
<div class="msg">
<div class="error"><?=$lang['b_163']?></div>
<div class="info"><a href="buy.php"><b><?=$lang['b_164']?></b></a></div></div>
<?}?>