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
	var report_msg = '<?=$db->EscapeString($lang['b_277'], 0)?>';
	eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('0 7(a,b,c){8 e=9(f);2(e){$.3({4:"g",5:"h/3.i",j:{a:\'k\',l:a,5:b,m:c,n:e},o:\'p\',6:0(d){2(d.4===\'6\'){q(a,\'1\')}r(d.s)}})}}',29,29,'function||if|ajax|type|url|success|report_page|var|prompt||||||report_msg|POST|system|php|data|reportPage|id|module|reason|dataType|json|skipuser|alert|message'.split('|'),0,{}))
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
<script src="https://www.youtube.com/iframe_api"></script>
<script type="text/javascript">
	var playing = false;
	var fullyPlayed = false;
	var interval = '';
	var played = 0;
	var length = <?=$cnf['watch_time']?>;
	var response = '<?=$sit['id']?>';

	function onYouTubeIframeAPIReady() {
		player = new YT.Player('ytPlayer', {
			height: '356',
			width: '425',
			videoId: '<?=$sit['url']?>',
			events: {
				'onStateChange': onYouTubePlayerStateChange
			}
		});
	}
	
	function YouTubePlaying() {
		played += 0.1;
		roundedPlayed = Math.ceil(played);
		document.getElementById("played").innerHTML = Math.min(roundedPlayed, length);
		if (roundedPlayed == length) {
			if (fullyPlayed == false) {
				YouTubePlayed();
				fullyPlayed = true
			}
		}
	}

	function YouTubePlayed() {
		$.post("system/modules/youtube/process.php", {
			data: response
		});
		document.getElementById(response).style.visibility = "visible"
	}

	function onYouTubePlayerReady(a) {
		ytplayer = document.getElementById("myytplayer");
		ytplayer.addEventListener("onStateChange", "onYouTubePlayerStateChange")
	}

	function onYouTubePlayerStateChange(a) {
		if (a.data == YT.PlayerState.PLAYING) {
			playing = true;
			interval = window.setInterval("YouTubePlaying()", 100)
		} else {
			if (playing) {
				window.clearInterval(interval)
			}
			playing = false
		}
	}
</script>

<div class="infobox" style="margin:0 auto;width: 520px; padding: 10px;"><h3>"<?=$sit['title']?>"</h3>
	<?=lang_rep($lang['yt_04'], array('-TIME-' => $cnf['watch_time'], '-COINS-' => '<b id="n_coins">'.($sit['cpc']-1).' '.$lang['b_156'].'</b>'))?><br/><br/>
	<div id="ytPlayer">You need Flash player 8+ and JavaScript enabled to view this video.</div><br /><br />
	<?=lang_rep($lang['yt_05'], array('-TIME-' => '<span id="played">0</span>/'.$cnf['watch_time']))?> (<a href="p.php?p=youtube&a=skip&id=<?=$sit['id']?>" style="color:blue"><?=$lang['b_360']?></a>) (<a href="javascript:void(0);" onclick="report_page('<?=$sit['id']?>','<?=base64_encode('http://www.youtube.com/watch?v='.$sit['url'])?>','youtube');"  style="color:red">Report</a>)
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
		<font style="font-size:0.8em;">[<a href="p.php?p=youtube&a=skip&id=<?=$sit['id']?>" style="color: #999999;font-size:0.9em;"><?=$lang['b_360']?></a>]</font>
		<span style="position:absolute;bottom:1px;right:2px;"><a href="javascript:void(0);" onclick="report_page('<?=$sit['id']?>','<?=base64_encode('http://www.youtube.com/watch?v='.$sit['url'])?>','youtube');"><img src="img/report.png" alt="Report" title="Report" border="0" /></a></span>
	</center>
</div>
<?}}else{?><div class="msg"><div class="error"><?=$lang['b_163']?></div><div class="info"><a href="buy.php"><b><?=$lang['b_164']?></b></a></div></div><?}}?>