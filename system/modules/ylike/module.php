<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	if(isset($_GET['remAcc'])){
		$db->Query("DELETE FROM `ylike_accounts` WHERE `user`='".$data['id']."'");
	}
?>
<h2 class="title"><?=$lang['b_162']?> - Youtube Likes</h2>
<?php
if(empty($site['yt_api'])){
	echo ($data['admin'] > 0 ? '<div class="msg"><div class="error"><a href="admin-panel/index.php?x=ytset">To enable this module you have to configure it on Admin Panel -> Settings -> Youtube Settings!</a></div></div>' : '<div class="msg"><div class="error">That section is currently unavailable!</div></div>');
}else{
	$check_acc = $db->QueryFetchArray("SELECT account_name FROM `ylike_accounts` WHERE `user`='".$data['id']."' LIMIT 1");
	if(empty($check_acc['account_name'])){
		$msg = '<div class="msg"><div class="info">'.$lang['ylike_16'].'</div></div>';
		if(isset($_POST['submit']) && !empty($_POST['yt_user'])){
			$yt_user = $db->EscapeString($_POST['yt_user']);
			
			$x = get_data('https://www.googleapis.com/youtube/v3/channels?part=contentDetails,snippet&forUsername='.$yt_user.'&key='.$site['yt_api']);
			$x = json_decode($x, true);

			if(empty($x['pageInfo']['totalResults'])) {
				$x = get_data('https://www.googleapis.com/youtube/v3/channels?part=contentDetails,snippet&id='.$yt_user.'&key='.$site['yt_api']);
				$x = json_decode($x, true);
			}

			$acc_id = $x['items'][0]['id'];

			if(empty($acc_id)){
				$msg = '<div class="msg"><div class="error">'.$lang['ylike_18'].'</div></div>';
			}else{
				$db->Query("INSERT INTO `ylike_accounts`(`user`,`account_name`,`account_id`)VALUES('".$data['id']."','".$yt_user."','".$acc_id."')");
				redirect("p.php?p=ylike");
			}
		}
?>
	<form method="post">
		<input class="l_form" onfocus="if(this.value == '<?=$lang['ylike_17']?>') { this.value = ''; }" onblur="if(this.value=='') { this.value = this.defaultValue }" value="<?=$lang['ylike_17']?>" name="yt_user" type="text">
		<input type="submit" class="gbut" name="submit" value="<?=$lang['b_58']?>" />
	</form><?=$msg?>
<?
}else{
$dbt_value = '';
if($site['target_system'] != 2){
	$dbt_value = " AND (a.country = '0' OR FIND_IN_SET('".$data['country']."', a.country)) AND (a.sex = '".$data['sex']."' OR a.sex = '0')";
}

$sites = $db->QueryFetchArrayAll("SELECT a.id, a.url, a.title, a.cpc, b.premium FROM ylike a LEFT JOIN users b ON b.id = a.user LEFT JOIN yliked c ON c.user_id = '".$data['id']."' AND c.site_id = a.id WHERE a.active = '0' AND (a.max_clicks > a.clicks OR a.max_clicks = '0') AND (a.daily_clicks > a.today_clicks OR a.daily_clicks = '0') AND (b.coins >= a.cpc AND a.cpc >= '2') AND (c.site_id IS NULL AND a.user !='".$data['id']."')".$dbt_value." ORDER BY a.cpc DESC, b.premium DESC".($site['mysql_random'] == 1 ? ', RAND()' : '')." LIMIT 14");
if($sites){
?>
<p class="infobox" style="text-align:right"><b><?=$lang['ylike_15']?>:</b> <?=$check_acc['account_name']?> - <a href="p.php?p=ylike&remAcc" style="color:red"><b>X</b></a></p>
<script>
	var report_msg = '<?=$db->EscapeString($lang['b_277'], 0)?>';
	var start_click = 1;
	var end_click = <?=count($sites)?>;
	eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('0 7(a,b,c){8 e=9(f);2(e){$.3({4:"g",5:"h/3.i",j:{a:\'k\',l:a,5:b,m:c,n:e},o:\'p\',6:0(d){2(d.4===\'6\'){q(a,\'1\')}r(d.s)}})}}',29,29,'function||if|ajax|type|url|success|report_page|var|prompt||||||report_msg|POST|system|php|data|reportPage|id|module|reason|dataType|json|skipuser|alert|message'.split('|'),0,{}))
	eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('0 s(){n(t<S){t=t+1}T{H.U(V)}}0 W(b){$("#5").6("<7 u=\\"7/v.w\\" /><x>");$.y({i:"z",o:"A/B/C/D.E",F:{X:\'Y\',Z:b},l:0(a){$("#5").6(a);p(b);s()}})}m 8;0 G(a,b){10 11(12.13()*(b-a)+a).14(2)}0 15(d,e,f){n(!8||8.I){$("#5").6("<7 u=\\"7/v.w\\" /><x>");m j=(g.q/1.9)-(g.q/G(3,4));m k=(g.r/1.9)-(g.r/G(3,4));$.y({i:"z",o:"A/B/C/D.E",F:{16:1,o:e,17:d},J:\'K\',l:0(a){n(a.i===\'l\'){m b=L(0(){8.18()},19);m c=1a(0(){n(8.I){M(c);M(b);$("#5").6("<7 u=\\"7/v.w\\" /><x>");L(0(){N(d)},1b)}},1c)}$("#5").6(a.O)}});8=1d.1e(e,f,"1f=h, H=h, 1g=h, 1h=h, 1i=h, 1j=P, 1k=P, 1l=h, q="+g.q/1.9+", r="+g.r/1.9+", 1m="+k+", 1n="+j)}}0 N(b){$.y({i:"z",o:"A/B/C/D.E",F:{1o:b},J:\'K\',l:0(a){1p(a.i){Q\'l\':p(b);s();R;Q\'1q\':p(b);R}$("#5").6(a.O)}})}0 p(a){$(\'#\'+a).1r()}',62,90,'function|||||Hint|html|img|targetWin||||||||screen|no|type|||success|var|if|url|remove|width|height|click_refresh|start_click|src|loader|gif|br|ajax|POST|system|modules|ylike|process|php|data|getRandomPosition|location|closed|dataType|json|setTimeout|clearTimeout|do_click|message|yes|case|break|end_click|else|reload|true|skipuser|step|skip|sid|return|parseFloat|Math|random|toFixed|ModulePopup|get|pid|close|30000|setInterval|1000|500|window|open|toolbar|directories|status|menubar|scrollbars|resizable|copyhistory|top|left|id|switch|not_available|hide'.split('|'),0,{}))
</script>
<center><div id="Hint"></div></center>
<div id="getpoints">
<?foreach($sites as $sit){?>	
<div class="follow<?=($sit['premium'] > 0 ? '_vip' : '')?>" id="<?=$sit['id']?>">
	<center>
		<img src="http://img.youtube.com/vi/<?=$sit['url']?>/1.jpg" width="48" height="48" alt="<?=truncate($sit['title'], 10)?>" title="<?=truncate($sit['title'], 50)?>" border="0" /><br /><b><?=$lang['b_42']?></b>: <?=($sit['cpc']-1)?><br>
		<a href="javascript:void(0);" onclick="ModulePopup('<?=$sit['id']?>','<?=hideref('http://www.youtube.com/watch?v='.$sit['url'])?>','Youtube');" class="followbutton"><?=$lang['ylike_05']?></a>
		<font style="font-size:0.8em;">[<a href="javascript:void(0);" onclick="skipuser('<?=$sit['id']?>');" style="color: #666666;font-size:0.9em;"><?=$lang['b_360']?></a>]</font>
		<span style="position:absolute;bottom:1px;right:2px;"><a href="javascript:void(0);" onclick="report_page('<?=$sit['id']?>','<?=base64_encode('http://www.youtube.com/watch?v='.$sit['url'])?>','ylike');"><img src="img/report.png" alt="Report" title="Report" border="0" /></a></span>
	</center>
</div>
<?}?>
</div>
<?}else{?>
<div class="msg">
<div class="error"><?=$lang['b_163']?></div>
<div class="info"><a href="buy.php"><b><?=$lang['b_164']?></b></a></div></div>
<?}}}?>