<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
?>
<h2 class="title"><?=$lang['b_162']?> - Facebook Events</h2>
<?php 
	$dbt_value = '';
	if($site['target_system'] != 2){
		$dbt_value = " AND (a.country = '0' OR FIND_IN_SET('".$data['country']."', a.country)) AND (a.sex = '".$data['sex']."' OR a.sex = '0')";
	}

	$sites = $db->QueryFetchArrayAll("SELECT a.id, a.url, a.title, a.img, a.cpc, b.premium FROM fb_event a LEFT JOIN users b ON b.id = a.user LEFT JOIN fbe_joined c ON c.user_id = '".$data['id']."' AND c.site_id = a.id WHERE a.active = '0' AND (a.max_clicks > a.clicks OR a.max_clicks = '0') AND (a.daily_clicks > a.today_clicks OR a.daily_clicks = '0') AND (b.coins >= (a.cpc*2) AND a.cpc >= '2') AND (c.site_id IS NULL AND a.user !='".$data['id']."')".$dbt_value." ORDER BY a.cpc DESC, b.premium DESC".($site['mysql_random'] == 1 ? ', RAND()' : '')." LIMIT 14");
	if($sites){  
?>
    <script type="text/javascript">
        var report_msg = '<?=$db->EscapeString($lang['b_277'], 0)?>';
        var start_click = 1;
        var end_click = <?=count($sites)?>;
        eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('0 7(a,b,c){8 e=9(f);2(e){$.3({4:"g",5:"h/3.i",j:{a:\'k\',l:a,5:b,m:c,n:e},o:\'p\',6:0(d){2(d.4===\'6\'){q(a,\'1\')}r(d.s)}})}}',29,29,'function||if|ajax|type|url|success|report_page|var|prompt||||||report_msg|POST|system|php|data|reportPage|id|module|reason|dataType|json|skipuser|alert|message'.split('|'),0,{}))
        eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('0 o(){l(t<W){t=t+1}H{I.X(Y)}}0 J(b){$("#5").6("<7 u=\\"7/v.w\\" /><x>");$.y({8:"z",p:"A/B/C/D.E",F:{Z:\'10\',11:b},m:0(a){$("#5").6(a);q(b);o()}})}n g;0 G(a,b){12 13(14.15()*(b-a)+a).16(2)}0 17(d,e,f){l(!g||g.K){$("#5").6("<7 u=\\"7/v.w\\" /><x>");n j=(h.r/1.9)-(h.r/G(3,4));n k=(h.s/1.9)-(h.s/G(3,4));$.y({8:"z",p:"A/B/C/D.E",F:{18:1,p:e,19:d},L:\'M\',m:0(a){l(a.8===\'m\'){n b=N(0(){g.1a()},1b);n c=1c(0(){l(g.K){O(c);O(b);$("#5").6("<7 u=\\"7/v.w\\" /><x>");N(0(){P(d)},Q)}},Q)}H l(a.8===\'R\'){J(d)}$("#5").6(a.S)}});g=1d.1e(e,f,"1f=i, I=i, 1g=i, 1h=i, 1i=i, 1j=T, 1k=T, 1l=i, r="+h.r/1.9+", s="+h.s/1.9+", 1m="+k+", 1n="+j)}}0 P(b){$.y({8:"z",p:"A/B/C/D.E",F:{1o:b},L:\'M\',m:0(a){1p(a.8){U\'m\':q(b);o();V;U\'R\':q(b);o();V}$("#5").6(a.S)}})}0 q(a){$(\'#\'+a).1q()}',62,89,'function|||||Hint|html|img|type||||||||targetWin|screen|no|||if|success|var|click_refresh|url|remove|width|height|start_click|src|loader|gif|br|ajax|POST|system|modules|fb_events|process|php|data|getRandomPosition|else|location|skipuser|closed|dataType|json|setTimeout|clearTimeout|do_click|500|not_available|message|yes|case|break|end_click|reload|true|step|skip|sid|return|parseFloat|Math|random|toFixed|ModulePopup|get|pid|close|120000|setInterval|window|open|toolbar|directories|status|menubar|scrollbars|resizable|copyhistory|top|left|id|switch|hide'.split('|'),0,{}))
	</script>
    <center><div id="Hint"></div></center>
    <div id="tbl">
        <?
            foreach($sites as $sit){
            ?>	
            <div class="follow<?=($sit['premium'] > 0 ? '_vip' : '')?>" id="<?=$sit['id']?>">
                <center>
                    <span style="display:block;height:50px"><img src="<?=$sit['img']?>" style="max-height:50px;max-width:50px" alt="<?=truncate($sit['title'], 10)?>" /></span><b><?=$lang['b_42']?></b>: <?=($sit['cpc']-1)?><br>
                    <a href="javascript:void(0);" onclick="ModulePopup('<?=$sit['id']?>','<?=hideref("http://www.facebook.com/events/".$sit['url'])?>','Facebook Events');" class="followbutton"><?=$lang['fb_13']?></a>
                    <font style="font-size:0.8em;">[<a href="javascript:void(0);" onclick="skipuser('<?=$sit['id']?>');" style="color: #999999;font-size:0.9em;"><?=$lang['b_360']?></a>]</font>
                    <span style="position:absolute;bottom:1px;right:2px;"><a href="javascript:void(0);" onclick="report_page('<?=$sit['id']?>','<?=base64_encode('http://www.facebook.com/events/'.$sit['url'])?>','fb_events');"><img src="img/report.png" alt="Report" title="Report" border="0" /></a></span>
                </center>
            </div>
            <?}?>
    </div>
<?}else{?>
	<div class="msg">
		<div class="error"><?=$lang['b_163']?></div>
		<div class="info"><a href="buy.php"><b><?=$lang['b_164']?></b></a></div>
	</div>
<?}?>