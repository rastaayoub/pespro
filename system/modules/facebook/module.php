<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
    $fbType = (isset($_GET['t']) && $_GET['t'] == 'web' ? 0 : 1);

    function get_fb_id($fb_url)
	{
		$url = explode('/', $fb_url);
		$url = (!empty($url[5]) ?$url[5] : $url[3]);

		if(preg_match("|^(.*)#(.*)|i", $url))
		{
			$id = explode('#', $url);
			$url = (!empty($id[0]) ? $id[0] : $url);
		}
		elseif(preg_match("|^(.*)?(.*)|i", $url))
		{
			$id = explode('?', $url);
			$url = (!empty($id[0]) ? $id[0] : $url);
		}

		return $url;
	}
?>
<h2 class="title"><?=$lang['b_162']?> - Facebook Likes</h2>    
<?php 
    $dbt_value = '';
	if($site['target_system'] != 2){
		$dbt_value = " AND (a.country = '0' OR FIND_IN_SET('".$data['country']."', a.country)) AND (a.sex = '".$data['sex']."' OR a.sex = '0')";
	}

	$sites = $db->QueryFetchArrayAll("SELECT a.id, a.url, a.fb_id, a.title, a.cpc, b.premium FROM facebook a LEFT JOIN users b ON b.id = a.user LEFT JOIN liked c ON c.user_id = '".$data['id']."' AND c.site_id = a.id WHERE a.active = '0' AND (a.max_clicks > a.clicks OR a.max_clicks = '0') AND (a.daily_clicks > a.today_clicks OR a.daily_clicks = '0') AND (b.coins >= (a.cpc*2) AND a.cpc >= '2') AND (c.site_id IS NULL AND a.user !='".$data['id']."')".$dbt_value." AND a.type = '".$fbType."' ORDER BY a.cpc DESC, b.premium DESC".($site['mysql_random'] == 1 ? ', RAND()' : '')." LIMIT 14");
	
	if($sites)
	{
?>
    <div id="fb-root"></div>
    <script type="text/javascript">
        var report_msg = '<?=$db->EscapeString($lang['b_277'], 0)?>';
        var start_click = 1;
        var end_click = <?=count($sites)?>;
        eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('0 7(a,b,c){8 e=9(f);2(e){$.3({4:"g",5:"h/3.i",j:{a:\'k\',l:a,5:b,m:c,n:e},o:\'p\',6:0(d){2(d.4===\'6\'){q(a,\'1\')}r(d.s)}})}}',29,29,'function||if|ajax|type|url|success|report_page|var|prompt||||||report_msg|POST|system|php|data|reportPage|id|module|reason|dataType|json|skipuser|alert|message'.split('|'),0,{}))
        <?php
			if($fbType == 0)
			{
		?>
           eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('0 r(){i(s<U){s=s+1}V{D.W(X)}}0 Y(b,d){$("#4").5("<6 t=\\"6/u.v\\" /><x>");$.y({8:"z",l:"m/n/o/A.p",B:{Z:\'10\',11:b},9:0(a){$("#4").5(a);q(b);r()}})}0 C(a,b){12 13(14.15()*(b-a)+a).16(2)}g 7;0 17(d,e,f,w,h){i(!7||7.E){$("#4").5("<6 t=\\"6/u.v\\" /><x />");g j=F.G/2-H/C(1,2);g k=F.I/2-J/C(1,2);$.y({8:"z",l:"m/n/o/A.p",B:{18:2,l:e,19:d},K:\'L\',9:0(a){i(a.8===\'9\'){g b=M(0(){7.1a()},1b);g c=1c(0(){i(7.E){N(c);N(b);$("#4").5("<6 t=\\"6/u.v\\" /><x>");M(0(){O(d)},P)}},P)}$("#4").5(a.Q)}});7=1d.1e(\'m/n/o/1f.p?R=\'+d,f,"1g=3, D=3, 1h=3, 1i=3, 1j=3, 1k=1l, 1m=3, 1n=3, G=H, I=J, 1o="+k+", 1p="+j)}}0 O(b){$.y({8:"z",l:"m/n/o/A.p",B:{R:b},K:\'L\',9:0(a){1q(a.8){S\'9\':q(b);r();T;S\'1r\':q(b);T}$("#4").5(a.Q)}})}0 q(a){$(\'#\'+a).1s()}',62,91,'function|||no|Hint|html|img|targetWin|type|success|||||||var||if|||url|system|modules|facebook|php|remove|click_refresh|start_click|src|loader|gif||br|ajax|POST|process|data|getRandomPosition|location|closed|screen|width|125|height|120|dataType|json|setTimeout|clearTimeout|do_click|500|message|id|case|break|end_click|else|reload|true|skipuser|step|skip|sid|return|parseFloat|Math|random|toFixed|ModulePopup|get|pid|close|30000|setInterval|window|open|like|toolbar|directories|status|menubar|scrollbars|yes|resizable|copyhistory|top|left|switch|not_available|hide'.split('|'),0,{}))
		<?php
			} else {
		?>
            eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('0 t(){o(u<U){u=u+1}V{I.W(X)}}0 Y(b,d){$("#5").7("<8 v=\\"8/w.x\\" /><y>");$.z({g:"A",p:"B/C/D/E.F",G:{Z:\'10\',11:b},m:0(a){$("#5").7(a);q(b);t()}})}n h;0 H(a,b){12 13(14.15()*(b-a)+a).16(2)}0 17(d,e,f){o(!h||h.J){$("#5").7("<8 v=\\"8/w.x\\" /><y />");n j=(i.r/1.9)-(i.r/H(3,4));n k=(i.s/1.9)-(i.s/H(3,4));$.z({g:"A",p:"B/C/D/E.F",G:{18:1,p:e,19:d},K:\'L\',m:0(a){o(a.g===\'m\'){n b=M(0(){h.1a()},1b);n c=1c(0(){o(h.J){N(c);N(b);$("#5").7("<8 v=\\"8/w.x\\" /><y>");M(0(){O(d)},P)}},P)}$("#5").7(a.Q)}});h=1d.1e(e,f,"1f=l, I=l, 1g=l, 1h=l, 1i=l, 1j=R, 1k=R, 1l=l, r="+i.r/1.6+", s="+i.s/1.6+", 1m="+k+", 1n="+j)}}0 O(b){$.z({g:"A",p:"B/C/D/E.F",G:{g:1,1o:b},K:\'L\',m:0(a){1p(a.g){S\'m\':q(b);t();T;S\'1q\':q(b);T}$("#5").7(a.Q)}})}0 q(a){$(\'#\'+a).1r()}',62,90,'function|||||Hint||html|img||||||||type|targetWin|screen|||no|success|var|if|url|remove|width|height|click_refresh|start_click|src|loader|gif|br|ajax|POST|system|modules|facebook|process|php|data|getRandomPosition|location|closed|dataType|json|setTimeout|clearTimeout|do_click|500|message|yes|case|break|end_click|else|reload|true|skipuser|step|skip|sid|return|parseFloat|Math|random|toFixed|ModulePopup|get|pid|close|120000|setInterval|window|open|toolbar|directories|status|menubar|scrollbars|resizable|copyhistory|top|left|id|switch|not_available|hide'.split('|'),0,{}))
		<?php
			}
		?>
    </script>
    <center><div id="Hint"></div></center>
    <div id="tbl">
        <?php
            foreach($sites as $sit){
                if($fbType == 0){
        ?>	
			<div class="follow<?=($sit['premium'] > 0 ? '_vip' : '')?>" id="<?=$sit['id']?>">
				<center>
					<span style="display:block;height:50px"><img src="img/icons/facebook.png" height="48" alt="<?=truncate($sit['title'], 25)?>" title="<?=truncate($sit['title'], 25)?>" /></span><b><?=$lang['b_42']?></b>: <?=($sit['cpc']-1)?><br />
					<a href="javascript:void(0);" onclick="ModulePopup('<?=$sit['id']?>','<?=$sit['url']?>','Like');" class="followbutton"><?=$lang['fb_12']?></a>
					<font style="font-size:0.8em;">[<a href="javascript:void(0);" onclick="skipuser('<?=$sit['id']?>','1');" style="color: #999999;font-size:0.9em;"><?=$lang['b_360']?></a>]</font>
					<span style="position:absolute;bottom:1px;right:2px;"><a href="javascript:void(0);" onclick="report_page('<?=$sit['id']?>','<?=base64_encode($sit['url'])?>','facebook');"><img src="img/report.png" alt="Report" title="Report" border="0" /></a></span>
				</center>
			</div>
			<?php }else{ ?>
			<div class="follow<?=($sit['premium'] > 0 ? '_vip' : '')?>" id="<?=$sit['id']?>">
				<center>
					<span style="display:block;height:50px"><img src="https://graph.facebook.com/<?=(empty($sit['fb_id']) ? get_fb_id($sit['url']) : $sit['fb_id'])?>/picture" height="50" alt="<?=truncate($sit['title'], 25)?>" /></span><b><?=$lang['b_42']?></b>: <?=($sit['cpc']-1)?><br />
					<a href="javascript:void(0);" onclick="ModulePopup('<?=$sit['id']?>','<?=hideref($sit['url'])?>','Facebook');" class="followbutton"><?=$lang['fb_12']?></a>
					<font style="font-size:0.8em;">[<a href="javascript:void(0);" onclick="skipuser('<?=$sit['id']?>','1');" style="color: #999999;font-size:0.9em;"><?=$lang['b_360']?></a>]</font>
					<span style="position:absolute;bottom:1px;right:2px;"><a href="javascript:void(0);" onclick="report_page('<?=$sit['id']?>','<?=base64_encode($sit['url'])?>','facebook');"><img src="img/report.png" alt="Report" title="Report" border="0" /></a></span>
				</center>
			</div>
        <?php }} ?>
    </div>
<?php }else{ ?>
<div class="msg">
	<div class="error"><?=$lang['b_163']?></div>
	<div class="info"><a href="buy.php"><b><?=$lang['b_164']?></b></a></div>
</div>
<?php } ?>