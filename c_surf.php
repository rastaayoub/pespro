<?php
include('header.php');
if(!$is_online){
	redirect('index.php');
}

$id = $db->EscapeString($_GET['id']);

$x = $db->QueryFetchArray("SELECT id,url FROM `surf` WHERE `id`='".$id."' AND `confirm`>'0'");
if(empty($x['id'])){
	redirect('index.php');
}
?>
<div class="content">		
<script>
var counterName='countDown';
function start(){ displayCountdown(15-1);}
loaded(start);
var pageLoaded=0;
window.onload=function(){pageLoaded=1;}
function loaded(functionName){
	if(document.getElementById && document.getElementById(counterName) != null){
		functionName();
	}else{
		if(!pageLoaded){
			setTimeout('loaded('+functionName+')',100);
		}
	}
}
function displayCountdown(counter){
	if(counter <= 0){
		$("#countOutcome").html('<font size=2><b><?=$lang['b_44']?>...</b></font>');
		$.ajax({
			type: "POST",
			url: "system/modules/surf/c_surf.php",
			data: "data=<?=$x['id']?>", 
			success: function(msg){
				$("#countOutcome").html(msg);
			}
		});
	}else{
		document.getElementById(counterName).innerHTML=counter;
		setTimeout('displayCountdown('+(counter-1)+');',1000);
	}
}
</script>
<div class="infobox"><table cellpadding="2" cellspacing="0" width="98%" align="center" class="maintable"><tr><td><center><b><?=$lang['b_45']?></b><br><br><span id="countOutcome"><font size="3"><b><?=lang_rep($lang['b_46'], array('-TIME-' => '<span id="countDown">0</span>'))?></b></font></span><br><iframe src="<?=$x['url']?>" width="540" height="250"></iframe></center><br /></td></tr></table></td></tr></table>	</div>
</div>
<?include('footer.php');?>