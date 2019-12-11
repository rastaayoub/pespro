<?
include('header.php');
if(!$is_online || !isset($_GET['x']) || !isset($_GET['t'])){
	redirect('index.php');
}

$id = $db->EscapeString($_GET['x']);
$type = hook_filter($_GET['t'].'_info', 'type');
$table = hook_filter($_GET['t'].'_info', 'db');

if($table == 'db'){
	redirect('index.php');
}

$mysite = $db->QueryFetchArray("SELECT * FROM `".$table."` WHERE `id`='".$id."' AND `user`='".$data['id']."' AND `active`!='2'");
if(!$mysite){
	redirect('mysites.php?p='.$_GET['t']);
}

$target_system = true;
if($site['target_system'] == 1){
	if($data['premium'] > 0){
		$target_system = true;
	}else{
		$target_system = false;
	}
}elseif($site['target_system'] == 2){
	$target_system = false;
}

$countries = $db->QueryFetchArrayAll("SELECT * FROM `list_countries` ORDER BY country");

$msg = '';
$maxcpc = ($data['premium'] > 0 ? $site['premium_cpc'] : $site['free_cpc']);
if(isset($_POST['delete'])){
    $db->Query("DELETE FROM `".$table."` WHERE `id`='".$id."' AND `user`='".$data['id']."'");
	redirect('mysites.php?p='.$_GET['t']);
}elseif(isset($_POST['update'])){
	$title = $db->EscapeString($_POST['title'], 1);
	$cpc = $db->EscapeString($_POST['cpc']);
	$status = $db->EscapeString($_POST['active']);
	$gender = $db->EscapeString($_POST['gender']);
	$gender = ($target_system ? $gender : 0);
	$daily_clicks = $db->EscapeString($_POST['daily_clicks']);
	$daily_clicks = ($_POST['daily_clicks_switch'] == 1 ? (is_numeric($daily_clicks) && $daily_clicks > 0 ? $daily_clicks : 0) : 0);
	$max_clicks = $db->EscapeString($_POST['max_clicks']);
	$max_clicks = ($_POST['max_clicks_switch'] == 1 ? (is_numeric($max_clicks) && $max_clicks > $mysite['clicks'] ? $max_clicks : 0) : 0);
	$country = $db->EscapeString($_POST['country']);
	$country = ($target_system ? $country : 0);
	$sCountries = ($target_system ? ($country == 0 ? 0 : $_POST['countries']) : 0);

	$ctrs = array();
	foreach($countries as $row) {
		$ctrs[] = $row['code'];
	}

	$cSelected = '';
	if(!empty($sCountries)){
		foreach ($sCountries as $a=>$value) 
		{
			if(in_array($value, $ctrs)) {
				$cSelected .= $value.',';
			}
		}
	}
	$cSelected = (empty($cSelected) ? 0 : $cSelected);

	if($cpc < 2 || $cpc > $maxcpc || !is_numeric($cpc)){
		$msg = '<div class="msg"><div class="error">'.lang_rep($lang['b_29'], array('-MIN-' => '2', '-MAX-' => $maxcpc)).'</div></div>';
	}elseif($gender < 0 || $gender > 2) {
		$msg = '<div class="msg"><div class="error">'.$lang['b_219'].'</div></div>';
	}elseif(empty($cSelected) && $country != '0') {
		$msg = '<div class="msg"><div class="error">'.$lang['b_220'].'</div></div>';
	}elseif($status == 2){
		$msg = '<div class="msg"><div class="error">'.$lang['b_73'].'</div></div>';
	}else{
		$db->Query("UPDATE `".$table."` SET `title`='".$title."', `daily_clicks`='".$daily_clicks."', `max_clicks`='".$max_clicks."', `cpc`='".$cpc."', `active`='".$status."', `country`='".$cSelected."', `sex`='".$gender."' WHERE `id`='".$id."' AND `user`='".$data['id']."'");
		$mysite = $db->QueryFetchArray("SELECT * FROM `".$table."` WHERE `id`='".$id."' AND `user`='".$data['id']."' AND `active`!='2'");
		$msg = '<div class="msg"><div class="success">'.$lang['b_74'].'</div></div>';
	}
}
?>
<link rel="stylesheet" href="js/multiselect/chosen.css" type="text/css" />
<div class="content">
	<h2 class="title"><?=$lang['b_212']?> - <?=$type?></h2><?=$msg?>
	<form method="post">
		<table style="text-align:left">
            <tr>
				<td class="t-left"><?=$lang['b_32']?></td>
				<td style="padding-left:30px"><input type="text" class="l_form" disabled="disabled" value="<?=$mysite['url']?>"/></td>
			</tr>
			<tr>
				<td class="t-left"><?=$lang['b_33']?></td>
				<td style="padding-left:30px"><input type="text" class="l_form" name="title" value="<?=$mysite['title']?>"/></td>
			</tr>
			<tr>
				<td class="t-left"><?=$lang['b_36']?></td>
				<td style="padding-left:30px"><select name="cpc" class="styled"><?for($cpc = 2; $cpc <= $maxcpc; $cpc++) { echo '<option value="'.$cpc.'"'.($mysite['cpc'] == $cpc ? ' selected' : '').'>'.$cpc.' '.$lang['b_156'].'</option>';}?></select></td>
            </tr>
			<tr>
				<td class="t-left"><?=$lang['b_347']?></td>
				<td style="padding-left:30px">
					<input type="text" name="daily_clicks" id="daily_clicks" value="<?=($mysite['daily_clicks'] == 0 ? '' : $mysite['daily_clicks'])?>" style="border-radius:3px;padding:3px 2px;width:60px;"<?=($mysite['daily_clicks'] == 0 ? 'disabled ' : '')?>/>
					<select name="daily_clicks_switch" id="dailyLimitSelect"><option value="0"><?=$lang['b_77']?></option><option value="1"<?=($mysite['daily_clicks'] > 0 ? ' selected' : '')?>><?=$lang['b_76']?></option></select>
				</td>
			</tr>
			<tr>
				<td class="t-left"><?=$lang['b_348']?></td>
				<td style="padding-left:30px">
					<input type="text" name="max_clicks" id="max_clicks" value="<?=($mysite['max_clicks'] == 0 ? '' : $mysite['max_clicks'])?>" style="border-radius:3px;padding:3px 2px;width:60px;"<?=($mysite['max_clicks'] == 0 ? 'disabled ' : '')?>/>
					<select name="max_clicks_switch" id="totalLimitSelect"><option value="0"><?=$lang['b_77']?></option><option value="1"<?=($mysite['max_clicks'] > 0 ? ' selected' : '')?>><?=$lang['b_76']?></option></select>
				</td>
			</tr>
			<tr>
				<td class="t-left"><?=$lang['b_75']?></td>
				<td style="padding-left:30px"><select name="active" class="styled"><option value="0"><?=$lang['b_76']?></option><option value="1"<?=(isset($_POST['active']) && $_POST['active'] == 1 ? ' selected' : ($mysite['active'] == 1 ? ' selected' : ''))?>><?=$lang['b_77']?></option></select></td>
			</tr>
			<?if($target_system){?>
			<tr>
				<td class="t-left"><?=$lang['b_213']?></td>
				<td style="padding-left:30px"><select name="gender" class="styled"><option value="0"><?=$lang['b_214']?></option><option value="1"<?=($mysite['sex'] == 1 ? ' selected' : '')?>><?=$lang['b_215']?></option><option value="2"<?=($mysite['sex'] == 2 ? ' selected' : '')?>><?=$lang['b_216']?></option></select> <?=$lang['b_217']?> <select name="country" class="styled" id="select-countries"><option value="0"><?=$lang['b_218']?></option><option value="1"<?=($mysite['country'] != '0' ? ' selected' : '')?>><?=$lang['b_344']?></option></select></td>
			</tr>
			<tr>
				<td></td>
				<td id="target-select" style="<?=($mysite['country'] == '0' ? 'display:none;' : '')?>padding-left:30px">
					<select id="choseCountries" data-placeholder="<?=$lang['b_345']?>..." name="countries[]" multiple>
						<?
							if($mysite['country'] != '0'){
								$sCountries = explode(',', $mysite['country']);
								$sc = array();
								foreach($sCountries as $c){
									$sc[] = $c;
								}
							}else{
								$sc = 0;
							}
							
							foreach($countries as $country){
								echo '<option value="'.$country['code'].'"'.(in_array($country['code'],$sc) ? ' selected' : '').'>'.$country['country'].'</option>';
							}
						?>
					</select>
				</td>
			</tr>
			<?}?>
			<tr>
				<td></td>
				<td style="padding-left:30px"><br>
					<input class="gbut" name="update" type="submit" value="<?=$lang['b_79']?>" />
					<input type="submit" name="delete" class="bbut" onclick="return confirm('<?=$lang['b_80']?>');" value="<?=$lang['b_81']?>" />
				</td>
			</tr>
		</table>
	</form>
</div>
<script type="text/javascript" src="js/multiselect/chosen.jquery.js"></script>
<script type="text/javascript">
	$(".styled").chosen({
		disable_search: true,
		width: "176px"
	});
	$("#dailyLimitSelect, #totalLimitSelect").chosen({
		disable_search: true,
		width: "105px"
	});
	$("#choseCountries").chosen({
		disable_search_threshold: 10,
		max_selected_options: 15,
		width: "390px"
	});
  
	$('#select-countries').live('change', function (e) {
        if ($(this).val() == '0') {
            $('#target-select').hide()
        } else {
            $('#target-select').show()
        }
	});
	$('#dailyLimitSelect').live('change', function (e) {
        if ($(this).val() == '0') {
			$("#daily_clicks").prop('disabled', true).val('');
        } else {
			$("#daily_clicks").prop('disabled', false).val('100');
        }
	});
	$('#totalLimitSelect').live('change', function (e) {
        if ($(this).val() == '0') {
			$("#max_clicks").prop('disabled', true).val('');
        } else {
			$("#max_clicks").prop('disabled', false).val('<?=($mysite['clicks'] < 1000 ? 1000 : $mysite['clicks']+1000)?>');
        }
	});
</script>
<?include('footer.php');?>