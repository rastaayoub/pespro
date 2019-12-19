<?if(! defined('BASEPATH') ){ exit('Unable to view file.'); }?>
<h2 class="title"><?=$lang['b_162']?> - Retweet</h2>
<div class="infobox"><div class="ucp_link" style="margin-right:5px;display:inline-block"><a href="p.php?p=twitter">Twitter Followers</a></div><div class="ucp_link active" style="margin-right:5px;display:inline-block"><a href="p.php?p=retweet">Twitter Tweet</a></div></div><br />
<?
$dbt_value = '';
if($site['target_system'] != 2){
	$dbt_value = " AND (a.country = '0' OR FIND_IN_SET('".$data['country']."', a.country)) AND (a.sex = '".$data['sex']."' OR a.sex = '0')";
}

$sites = $db->QueryFetchArrayAll("SELECT a.id, a.url, a.title, a.cpc, b.premium FROM retweet a LEFT JOIN users b ON b.id = a.user LEFT JOIN retweeted c ON c.user_id = '".$data['id']."' AND c.site_id = a.id WHERE a.active = '0' AND (a.max_clicks > a.clicks OR a.max_clicks = '0') AND (a.daily_clicks > a.today_clicks OR a.daily_clicks = '0') AND (b.coins >= a.cpc AND a.cpc >= '2') AND (c.site_id IS NULL AND a.user !='".$data['id']."')".$dbt_value." ORDER BY a.cpc DESC, b.premium DESC".($site['mysql_random'] == 1 ? ', RAND()' : '')." LIMIT 15");
if($sites){
?>
<script type="text/javascript">
	var report_msg1 = '<?=mysql_escape_string($lang['b_277'])?>';
	var report_msg2 = '<?=mysql_escape_string($lang['b_236'])?>';
	var report_msg3 = '<?=mysql_escape_string($lang['b_237'])?>';
	var report_msg4 = '<?=mysql_escape_string(lang_rep($lang['b_252'], array('-NUM-' => $site['report_limit'])))?>';
	var start_click = 1;
	var end_click = <?=count($sites)?>;
	eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('4 7(a,b,c){8 e=9(f);g(e){$.h({i:"j",5:"k/l.m",n:o,p:"q="+a+"&5="+b+"&r="+c+"&s="+e,t:4(d){u(d){6\'1\':0(v);w(a,\'1\');3;6\'2\':0(x);3;y:0(z);3}}})}}',36,36,'alert|||break|function|url|case|report_page|var|prompt||||||report_msg1|if|ajax|type|POST|system|report|php|cache|false|data|id|module|reason|success|switch|report_msg2|skipElement|report_msg4|default|report_msg3'.split('|'),0,{}))
	eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('0 8(){C(9<D){9=9+1}E{F.G(H)}}0 I(b,c){$("#3").4("<5 d=\\"5/n.o\\" /><J>");$.e({f:"p",6:"h/q/r/s.i",t:"K=L&M="+b,j:0(a){$("#3").4(a);k(b);8()}})}(0($){$(l).N(0(){$.O("P://Q.R.S/T.U",0(){V.W.X("Y",0(a){m b=a.Z.d;m c=u(b);v(c.6)})})})}(10));0 u(a){a=a.11("+").12(" ");m b={},7,w=/[?&]?([^=]+)=([^&]*)/g;13((7=w.14(a))){b[x(7[1])]=x(7[2])}15 b}0 v(b){l.y("3").z.A="16";$("#3").4("<5 d=\\"5/n.o\\" />");$.e({f:"p",6:"h/q/r/s.i",t:"17="+b,j:0(a){$("#3").4(a);k(b);8();B()}})}0 k(a){l.y(a).z.A="18"}0 B(){$.e({f:"19",6:"h/1a.i",1b:1c,j:0(a){$("#1d").4(a)}})}',62,76,'function|||Hint|html|img|url|tokens|click_refresh|start_click||||src|ajax|type||system|php|success|remove|document|var|loader|gif|POST|modules|retweet|process|data|getQueryParams|click_callback|re|decodeURIComponent|getElementById|style|display|refresh_coins|if|end_click|else|location|reload|true|skipElement|br|step|skip|sid|ready|getScript|http|platform|twitter|com|widgets|js|twttr|events|bind|tweet|target|jQuery|split|join|while|exec|return|block|id|none|GET|uCoins|cache|false|c_coins'.split('|'),0,{}))
</script>
<center><div id="Hint"></div></center>
<div id="tbl">
<?
  foreach($sites as $sit)
{
?>	
<div class="tbl tbl-content<?=($sit['premium'] > 0 ? '_vip' : '')?>" id="<?=$sit['id']?>">
	<a href="javascript://" class="close" onclick="skipElement('<?=$sit['id']?>','1');">x</a>
	<div><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?=$sit['id']?>" data-text="<?=$sit['title']?> <?=$sit['url']?>" data-count="horizontal">Tweet</a></div>
	<div class="title"><a href="<?=$sit['url']?>" target="_blank" style="color:blue;"><?=truncate($sit['title'], 20)?></a></div>
	<div class="points"><?=$lang['b_42']?>: <b><span id="<?=$sit['id']?>coins"><?=($sit['cpc']-1)?></span></b></div>
	<span style="position:absolute;bottom:1px;right:2px;"><a href="javascript:void(0);" onclick="report_page('<?=$sit['id']?>','<?=base64_encode($sit['url'])?>','retweet');"><img src="img/report.png" alt="Report" title="Report" border="0" /></a></span>
</div>
<?}?>
</div>
<?}else{?>
<div class="msg">
<div class="error"><?=$lang['b_163']?></div>
<div class="info"><a href="buy.php"><b><?=$lang['b_164']?></b></a></div></div>
<?}?>