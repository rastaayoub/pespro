<?php
include('header.php');

$rewards = $db->QueryFetchArrayAll("SELECT * FROM `activity_rewards` ORDER BY `exchanges` ASC");
if(!$is_online || $site['daily_bonus'] == 0 && empty($rewards)){
	redirect('index.php');
}

function r_time($seconds) {
	$measures = array(
		'day'=>24*60*60,
		'hour'=>60*60,
		'minute'=>60,
		'second'=>1,
	);
	foreach ($measures as $label=>$amount) {
		if ($seconds >= $amount) {  
			$howMany = floor($seconds / $amount);
			return $howMany." ".$label.($howMany > 1 ? "s" : "");
		}
	} 
}  

$cf_bonus = $db->QueryFetchArray("SELECT SUM(`today_clicks`) AS `clicks` FROM `user_clicks` WHERE `uid`='".$data['id']."'");
$cf_bonus = ($cf_bonus['clicks'] > 0 ? $cf_bonus['clicks'] : 0);

if(($data['daily_bonus']+86400) < time()){
?>
<script type="text/javascript">
	var msg1 = '<?=mysql_escape_string(lang_rep($lang['b_38'], array('-NUM-' => ($data['premium'] > 0 ? $site['daily_bonus_vip'] : $site['daily_bonus']))))?>';
	var msg2 = '<?=mysql_escape_string($lang['b_39'])?>';
	function checkBonus(){$("#bonus").hide();$("#loading").show();$.ajax({type:"GET",url:"system/ajax.php?a=dailyBonus",cache:false,success:function(a){if(a==1){$("#loading").hide();$("#txtHint").html('<div class="msg"><div class="success">'+msg1+'</div></div>')}else{$("#loading").hide();$("#txtHint").html('<div class="msg"><div class="error">'+msg2+'</div></div>')}}})}
</script><?}?>
<div class="content">
	<h2 class="title"><?=$lang['b_09']?></h2>
	<h2><?=lang_rep($lang['b_40'], array('-NUM-' => ($data['premium'] > 0 ? $site['daily_bonus_vip'] : $site['daily_bonus'])))?></h2>
	<?if(($data['daily_bonus']+86400) < time()){?>
		<div id="txtHint"></div>
	<?if($cf_bonus < $site['crf_bonus']){?>
		<div class="msg"><div class="error"><?=lang_rep($lang['b_225'], array('-NUM-' => $site['crf_bonus'], '-REM-' => ($site['crf_bonus'] - $cf_bonus)))?></div></div>
	<?}else{?>
		<img src="img/loader.gif" alt="Loading..." title="Loading..." id="loading" style="display:none" />
		<input type="button" id="bonus" class="gbut" name="bonnus" onclick="checkBonus()" value="<?=$lang['b_166']?>" />
	<?}}else{?>
		<div class="msg"><div class="error"><?=lang_rep($lang['b_41'], array('-TIME-' => r_time(($data['daily_bonus']+86400)-time())))?></div></div>
<?
	}
	if($rewards){
		$total_clicks = $db->QueryFetchArray("SELECT SUM(`total_clicks`) AS `clicks` FROM `user_clicks` WHERE `uid`='".$data['id']."'");
		$total_clicks = $total_clicks['clicks'];
?>
	<style>.table tbody tr td{padding:12px 6px;} .claimed, .claimed:hover {cursor:auto;text-shadow:0 0 0;color:#6b6b6b;border:solid 1px #6b6b6b;background:#dddddd;padding:5px 10px;}</style>
	<script type="text/javascript">
		function getReward(rID) {
			$("#claim_"+rID).html('<img src="img/ajax-loader.gif" alt="Loading..." title="Loading..." />');
			$("#rewardMSG").html('<img src="img/loader.gif" alt="Loading..." title="Loading..." />');
			$.getJSON('system/ajax.php?a=getReward&rID='+rID, function (c) {
				if(c['type'] == 'success'){
					$("#claim_"+rID).html('<span class="gbut claimed"><?=$lang['b_331']?></span>');
					$("#rewardMSG").html('<div class="msg"><div class="success">' + c['message'] + '</div></div>')
				} else {
					$("#claim_"+rID).html('<a href="javascript:void(0)" onclick="getReward('+rID+')" class="gbut"><?=$lang['b_329']?></a>');
					$("#rewardMSG").html('<div class="msg"><div class="error">' + c['message'] + '</div></div>')
				}
			});
		}
	</script>
	<h2 class="title"><?=$lang['b_326']?></h2>
	<div id="rewardMSG"><div class="msg"><div class="info"><?=lang_rep($lang['b_336'], array('-NUM-' => $total_clicks))?></div></div></div>
	<table class="table">
		<thead>
			<tr><td>#</td><td><?=$lang['b_328']?></td><td><?=$lang['b_327']?></td><td><?=$lang['b_329']?></td></tr>
		</thead>
		<tbody>
		<?
			foreach($rewards as $reward){
				$claimed = $db->QueryGetNumRows("SELECT * FROM `activity_rewards_claims` WHERE `reward_id`='".$reward['id']."' AND `user_id`='".$data['id']."' LIMIT 1");
		?>
			<tr><td><?=$reward['id']?></td><td style="text-align:left"><?=lang_rep($lang['b_330'], array('-NUM-' => number_format($reward['exchanges'])))?></b></td><td style="color:#98ca33"><b><?=number_format($reward['reward']).' '.($reward['type'] == 1 ? $lang['b_246'] : $lang['b_156'])?></b></td><td id="claim_<?=$reward['id']?>"><?=($claimed > 0 ? '<span class="gbut claimed">'.$lang['b_331'].'</span>' : ($total_clicks >= $reward['exchanges'] ? '<a href="javascript:void(0)" onclick="getReward('.$reward['id'].')" class="gbut">'.$lang['b_329'].'</a>' : '<span class="gbut claimed">'.$lang['b_332'].'</span>'))?></td></tr>
		<?}?>
		</tbody>
		<tfoot>
			<tr><td>#</td><td><?=$lang['b_328']?></td><td><?=$lang['b_327']?></td><td><?=$lang['b_329']?></td></tr>
		</tfoot>
	</table>
<?}?>
</div>	
<?include('footer.php');?>