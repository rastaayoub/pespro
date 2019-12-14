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

			$x = get_data('https://www.googleapis.com/youtube/v3/channels?part=contentDetails,snippet&forUsername='.$yt_user.'&key='.$site['yt_api']);
			$x = json_decode($x, true);

			if(empty($x['pageInfo']['totalResults'])) {
				$x = get_data('https://www.googleapis.com/youtube/v3/channels?part=contentDetails,snippet&id='.$yt_user.'&key='.$site['yt_api']);
				$x = json_decode($x, true);
			}
			
			$acc_id = $x['items'][0]['id'];
			$fav_id = $x['items'][0]['contentDetails']['relatedPlaylists']['favorites'];

			if(empty($acc_id)){
				$msg = '<div class="msg"><div class="error">'.$lang['yfav_06'].'</div></div>';
			}else{
				$x = get_data('https://www.googleapis.com/youtube/v3/channels?part=contentDetails&id='.$acc_id.'&key='.$site['yt_api']);
				$x = json_decode($x, true);
				$fav_id = $x['items'][0]['contentDetails']['relatedPlaylists']['favorites'];
				if(!empty($fav_id)){
					$db->Query("INSERT INTO `yfav_accounts`(`user`,`account_name`,`account_id`,`fav_id`)VALUES('".$data['id']."','".$yt_user."','".$acc_id."','".$fav_id."')");
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
	var report_msg = '<?=$db->EscapeString($lang['b_277'], 0)?>';
	var start_click = 1;
	var end_click = <?=count($sites)?>;
	eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('0 7(a,b,c){8 e=9(f);2(e){$.3({4:"g",5:"h/3.i",j:{a:\'k\',l:a,5:b,m:c,n:e},o:\'p\',6:0(d){2(d.4===\'6\'){q(a,\'1\')}r(d.s)}})}}',29,29,'function||if|ajax|type|url|success|report_page|var|prompt||||||report_msg|POST|system|php|data|reportPage|id|module|reason|dataType|json|skipuser|alert|message'.split('|'),0,{}))
	eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('0 s(){o(t<V){t=t+1}W{I.X(Y)}}0 Z(b){$("#5").6("<7 u=\\"7/v.w\\" /><x>");$.y({i:"z",A:"B/C/D/E.F",G:{10:\'11\',12:b},l:0(a){$("#5").6(a);p(b);s()}})}n 8;0 H(a,b){13 14(15.16()*(b-a)+a).17(2)}0 18(d,e,f,m){o(!8||8.J){$("#5").6("<7 u=\\"7/v.w\\" /><x>");n j=(g.q/1.9)-(g.q/H(3,4));n k=(g.r/1.9)-(g.r/H(3,4));$.y({i:"z",A:"B/C/D/E.F",G:{19:1,K:m,1a:d},L:\'M\',l:0(a){o(a.i===\'l\'){n b=N(0(){8.1b()},1c);n c=1d(0(){o(8.J){O(c);O(b);$("#5").6("<7 u=\\"7/v.w\\" /><x>");N(0(){P(d,m)},Q)}},Q)}$("#5").6(a.R)}});8=1e.1f(e,f,"1g=h, I=h, 1h=h, 1i=h, 1j=h, 1k=S, 1l=S, 1m=h, q="+g.q/1.9+", r="+g.r/1.9+", 1n="+k+", 1o="+j)}}0 P(b,d){$.y({i:"z",A:"B/C/D/E.F",G:{1p:b,K:d},L:\'M\',l:0(a){1q(a.i){T\'l\':p(b);s();U;T\'1r\':p(b);U}$("#5").6(a.R)}})}0 p(a){$(\'#\'+a).1s()}',62,91,'function|||||Hint|html|img|targetWin||||||||screen|no|type|||success||var|if|remove|width|height|click_refresh|start_click|src|loader|gif|br|ajax|POST|url|system|modules|yfav|process|php|data|getRandomPosition|location|closed|fav_id|dataType|json|setTimeout|clearTimeout|do_click|500|message|yes|case|break|end_click|else|reload|true|skipuser|step|skip|sid|return|parseFloat|Math|random|toFixed|ModulePopup|get|pid|close|30000|setInterval|window|open|toolbar|directories|status|menubar|scrollbars|resizable|copyhistory|top|left|id|switch|not_available|hide'.split('|'),0,{}))
</script>
<center><div id="Hint"></div></center>
<div id="getpoints">
<?foreach($sites as $sit){?>	
<div class="follow<?=($sit['premium'] > 0 ? '_vip' : '')?>" id="<?=$sit['id']?>">
	<center>
		<img src="http://img.youtube.com/vi/<?=$sit['url']?>/1.jpg" width="48" height="48" alt="<?=truncate($sit['title'], 10)?>" title="<?=truncate($sit['title'], 50)?>" border="0" /><br /><b><?=$lang['b_42']?></b>: <?=($sit['cpc']-1)?><br>
		<a href="javascript:void(0);" onclick="ModulePopup('<?=$sit['id']?>','<?=hideref('http://www.youtube.com/watch?v='.$sit['url'])?>','Youtube','<?=$check_acc['fav_id']?>');" class="followbutton"><?=$lang['yfav_05']?></a>
		<font style="font-size:0.8em;">[<a href="javascript:void(0);" onclick="skipuser('<?=$sit['id']?>');" style="color: #666666;font-size:0.9em;"><?=$lang['b_360']?></a>]</font>
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