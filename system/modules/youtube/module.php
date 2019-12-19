<?if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$cnf['watch_time'] = 15; // How many seconds do you want to be played each video

if(isset($_GET['a']) && $_GET['a'] == "skip"){
	$id = $db->EscapeString($_GET['id']);
	$sql = $db->Query("SELECT id FROM `youtube` WHERE `id`='".$id."'");
	$sit = $db->GetNumRows($sql);
	if($sit > 0){
		$db->Query("INSERT INTO `viewed` (user_id, site_id) VALUES('".$data['id']."','".$id."')");
	}
}
?>
<h2 class="title"><?=$lang['b_162']?> - Youtube</h2>
<script type="text/javascript">
	var report_msg1 = '<?=mysql_escape_string($lang['b_277'])?>';
	var report_msg2 = '<?=mysql_escape_string($lang['b_236'])?>';
	var report_msg3 = '<?=mysql_escape_string($lang['b_237'])?>';
	var report_msg4 = '<?=mysql_escape_string(lang_rep($lang['b_252'], array('-NUM-' => $site['report_limit'])))?>';
	eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('4 9(a,b,c){f e=g(h);i(e){$.j({k:"l",5:"m/n.6",o:q,r:"7="+a+"&5="+b+"&s="+c+"&t="+e,u:4(d){v(d){8\'1\':0(w);x.y.z="p.6?p=A&a=B&7="+a;3;8\'2\':0(C);3;D:0(E);3}}})}}',41,41,'alert|||break|function|url|php|id|case|report_page||||||var|prompt|report_msg1|if|ajax|type|POST|system|report|cache||false|data|module|reason|success|switch|report_msg2|window|location|href|youtube|skip|report_msg4|default|report_msg3'.split('|'),0,{}))
</script>
<?
$dbt_value = '';
if($site['target_system'] != 2){
	$dbt_value = " AND (a.country = '0' OR FIND_IN_SET('".$data['country']."', a.country)) AND (a.sex = '".$data['sex']."' OR a.sex = '0')";
}

if(isset($_GET['vid'])){
	$vid = $db->EscapeString($_GET['vid']);
	$sit = $db->QueryFetchArray("SELECT a.id, a.url, a.title, a.cpc FROM youtube a LEFT JOIN users b ON b.id = a.user LEFT JOIN viewed c ON c.user_id = '".$data['id']."' AND c.site_id = a.id WHERE a.id = '".$vid."' AND a.active = '0' AND (b.coins >= a.cpc AND a.cpc >= '2') AND (c.site_id IS NULL AND a.user !='".$data['id']."')".$dbt_value." LIMIT 1");

if(empty($sit['id'])){
	redirect('p.php?p=youtube');
	exit;
}else{
	$key = $cnf['watch_time']+time();
	$db->Query("INSERT INTO `module_session` (`user_id`,`page_id`,`ses_key`,`module`,`timestamp`)VALUES('".$data['id']."','".$vid."','".$key."','youtube','".time()."') ON DUPLICATE KEY UPDATE `ses_key`='".$key."'");
?>			
<script src="js/swfobject.js"></script>
<script type="text/javascript">
	var playing = false;
	var fullyPlayed = false;
	var interval = '';
	var played = 0;
	var length = <?=$cnf['watch_time']?>;
	var response = '<?=$sit['id']?>';
	eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('2 9(){4+=0.1;5=b.m(4);6.7("4").n=b.o(5,c);3(5==c){3(d==e){f();d=g}}}2 f(){$.p("q/r/s/t.u",{v:h});6.7(h).w.x="y"}2 z(a){i=6.7("A");i.B("C","j")}2 j(a){3(a==1){8=g;k=l.D("9()",E)}F{3(8){l.G(k)}8=e}}',43,43,'||function|if|played|roundedPlayed|document|getElementById|playing|YouTubePlaying||Math|length|fullyPlayed|false|YouTubePlayed|true|response|ytplayer|onYouTubePlayerStateChange|interval|window|ceil|innerHTML|min|post|system|modules|youtube|process|php|data|style|visibility|visible|onYouTubePlayerReady|myytplayer|addEventListener|onStateChange|setInterval|100|else|clearInterval'.split('|'),0,{}))
</script>

<div class="infobox" style="margin:0 auto;width: 520px; padding: 10px;"><h3>"<?=$sit['title']?>"</h3>
	<?=lang_rep($lang['yt_04'], array('-TIME-' => $cnf['watch_time'], '-COINS-' => '<b id="n_coins">'.($sit['cpc']-1).' coins</b>'))?><br/><br/>
	<div id="ytPlayer">You need Flash player 8+ and JavaScript enabled to view this video.</div>

<script type="text/javascript">
var params = { allowScriptAccess: "always" };
var atts = { id: "myytplayer" };
swfobject.embedSWF("http://www.youtube.com/v/<?=$sit['url']?>?enablejsapi=1&playerapiid=ytplayer&autoplay=0", "ytPlayer", "425", "356", "8", null, null, params, atts);
</script><br /><br />
	<?=lang_rep($lang['yt_05'], array('-TIME-' => '<span id="played">0</span>/'.$cnf['watch_time']))?> (<a href="p.php?p=youtube&a=skip&id=<?=$sit['id']?>" style="color:blue"><?=$lang['yt_06']?></a>) (<a href="javascript:void(0);" onclick="report_page('<?=$sit['id']?>','<?=base64_encode('http://www.youtube.com/watch?v='.$sit['url'])?>','youtube');"  style="color:red">Report</a>)
	<div id="<?=$sit['id']?>" style="visibility:hidden;padding-top:5px"><a href="p.php?p=youtube" style="font-size:14px;font-weight:600;color:red"><?=$lang['yt_07']?></a></div>
</div>
<?}}else{
$sites = $db->QueryFetchArrayAll("SELECT a.id, a.url, a.title, a.cpc, b.premium FROM youtube a LEFT JOIN users b ON b.id = a.user LEFT JOIN viewed c ON c.user_id = '".$data['id']."' AND c.site_id = a.id WHERE a.active = '0' AND (a.max_clicks > a.clicks OR a.max_clicks = '0') AND (a.daily_clicks > a.today_clicks OR a.daily_clicks = '0') AND (b.coins >= a.cpc AND a.cpc >= '2') AND (c.site_id IS NULL AND a.user !='".$data['id']."')".$dbt_value." ORDER BY a.cpc DESC, b.premium DESC".($site['mysql_random'] == 1 ? ', RAND()' : '')." LIMIT 21");
if($sites){
	foreach($sites as $sit){
?>	
<div class="follow<?=($sit['premium'] > 0 ? '_vip' : '')?>">
	<center>
		<img src="http://img.youtube.com/vi/<?=$sit['url']?>/1.jpg" border="0" alt="<?=$sit['title']?>" title="<?=$sit['title']?>" width="80" class="follower"><br><b><?=$lang['b_42']?></b>: <?=($sit['cpc']-1)?><br>
		<a href="p.php?p=youtube&vid=<?=$sit['id']?>" class="followbutton"><?=$lang['yt_08']?></a>
		<font style="font-size:0.8em;">[<a href="p.php?p=youtube&a=skip&id=<?=$sit['id']?>" style="color: #999999;font-size:0.9em;"><?=$lang['yt_06']?></a>]</font>
		<span style="position:absolute;bottom:1px;right:2px;"><a href="javascript:void(0);" onclick="report_page('<?=$sit['id']?>','<?=base64_encode('http://www.youtube.com/watch?v='.$sit['url'])?>','youtube');"><img src="img/report.png" alt="Report" title="Report" border="0" /></a></span>
	</center>
</div>
<?}}else{?><div class="msg"><div class="error"><?=$lang['b_163']?></div><div class="info"><a href="buy.php"><b><?=$lang['b_164']?></b></a></div></div><?}}?>