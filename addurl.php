<?php
	include('header.php');
	if(!$is_online){
		redirect('index.php');
		exit;
	}
	
	$can_add = true;
	if($site['req_clicks'] > 0 && $data['premium'] == 0)
	{
		$check = $db->QueryFetchArray("SELECT SUM(`total_clicks`) AS `clicks` FROM `user_clicks` WHERE `uid`='".$data['id']."'");
		
		if($check['clicks'] < $site['req_clicks'])
		{
			$can_add = false;
		}
	}

	if($can_add)
	{
		$maxcpc = ($data['premium'] > 0 ? $site['premium_cpc'] : $site['free_cpc']);
		$error = 1;
		$msg = '';

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

		if(isset($_POST['type']))
		{
			$type = $db->EscapeString($_POST['type']);
			$cpc = $db->EscapeString($_POST['cpc']);
			$gender = $db->EscapeString($_POST['gender']);
			$gender = ($target_system ? $gender : 0);
			$daily_clicks = $db->EscapeString($_POST['daily_clicks']);
			$daily_clicks = ($_POST['daily_clicks_switch'] == 1 ? (is_numeric($daily_clicks) && $daily_clicks > 0 ? $daily_clicks : 0) : 0);
			$max_clicks = $db->EscapeString($_POST['max_clicks']);
			$max_clicks = ($_POST['max_clicks_switch'] == 1 ? (is_numeric($max_clicks) && $max_clicks > 0 ? $max_clicks : 0) : 0);
			$ctr = $db->EscapeString($_POST['country']);
			$ctr = ($target_system ? $ctr : 0);
			$sCountries = ($target_system ? ($ctr == 0 ? 0 : $_POST['countries']) : 0);

			$ctrs = array();
			foreach($countries as $row) {
				$ctrs[] = $row['code'];
			}

			$country = '';
			if(!empty($sCountries)){
				foreach ($sCountries as $a=>$value) 
				{
					if(in_array($value, $ctrs)) {
						$country .= $value.',';
					}
				}
			}
			$country = (empty($country) ? 0 : $country);

			if($cpc < 2 || $cpc > $maxcpc || !is_numeric($cpc)){
				$msg = '<div class="msg"><div class="error">'.lang_rep($lang['b_29'], array('-MIN-' => '2', '-MAX-' => $maxcpc)).'</div></div>';
			}elseif($gender < 0 || $gender > 2) {
				$msg = '<div class="msg"><div class="error">'.$lang['b_219'].'</div></div>';
			}elseif(empty($country) && $ctr != '0') {
				$msg = '<div class="msg"><div class="error">'.$lang['b_220'].'</div></div>';
			}else{
				include('system/modules/'.$type.'/addsite.php');
			}
		}
?>
<link rel="stylesheet" href="js/multiselect/chosen.css" type="text/css" />
<div class="content t-left"><h2 class="title"><?=$lang['b_30']?></h2><?=$msg?>
<form method="post">
	<p>
		<label><?=$lang['b_31']?></label> <br/>
        <select class="styled" name="type" id="type">
			<option value='0'></option>
			<?=hook_filter('add_site_select', "")?>
		</select> <span id="load" style="display:none"><img src="img/ajax-loader.gif" alt="" /> <?=$lang['b_301']?>...</span>
	</p>
	<div id="custom_fields"></div>
	<div id="other_fields_msg"><div class="infobox"><center><b><?=$lang['b_302']?></b></center></div></div>
	<span id="other_fields" style="display:none">
		<p>
			<label><?=$lang['b_36']?></label> <br/>
			<select name="cpc" class="styled" style="width:220px">
			<?for($cpc = 2; $cpc <= $maxcpc; $cpc++) { echo (isset($_POST["cpc"]) && $_POST["cpc"] == $cpc ? '<option value="'.$cpc.'" selected>'.$cpc.'</option>' : (!isset($_POST["cpc"]) && $cpc == $maxcpc ? '<option value="'.$cpc.'" selected>'.$cpc.' '.$lang['b_156'].'</option>' : '<option value="'.$cpc.'">'.$cpc.' '.$lang['b_156'].'</option>'));}?></select>
		</p>
		<p>
			<label><?=$lang['b_347']?></label> <br/>
			<input type="text" name="daily_clicks" id="daily_clicks" value="" style="border-radius:3px;padding:3px 2px;width:60px;" disabled />
			<select name="daily_clicks_switch" id="dailyLimitSelect" class="styled"><option value="0"><?=$lang['b_77']?></option><option value="1"><?=$lang['b_76']?></option></select>
		</p>
		<p>
			<label><?=$lang['b_348']?></label> <br/>
			<input type="text" name="max_clicks" id="max_clicks" value="" style="border-radius:3px;padding:3px 2px;width:60px;" disabled />
			<select name="max_clicks_switch" id="totalLimitSelect" class="styled"><option value="0"><?=$lang['b_77']?></option><option value="1"><?=$lang['b_76']?></option></select>
		</p>
		<?if($target_system){?>
		<p>
			<label><?=$lang['b_213']?></label> <br/>
			<select name="gender" class="styled"><option value="0"><?=$lang['b_214']?></option><option value="1"><?=$lang['b_215']?></option><option value="2"><?=$lang['b_216']?></option></select>
			<?=$lang['b_217']?>
			<select name="country" class="styled" id="select-countries"><option value="0"><?=$lang['b_218']?></option><option value="1"><?=$lang['b_344']?></option></select>
		</p>
		<p id="target-select" style="display:none;">
			<select id="choseCountries" data-placeholder="<?=$lang['b_345']?>..." name="countries[]" multiple style="width:400px;">
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
		</p>
		<?}?>
		<p>
			<input type="submit" class="gbut" value="<?=$lang['b_37']?>" />
		</p>
	</span>
</form>
</div>
<script type="text/javascript" src="js/multiselect/chosen.jquery.js"></script>
<script type="text/javascript"> $('#type').live('change',function(e){var b=$("#type").val();if(b=='0'){$('#other_fields_msg').show();$('#custom_fields').hide();$('#other_fields').hide()}else{$('#load').show();$.get('system/modules/'+b+'/add_form.php',function(a){$('#custom_fields').html(a);$('#other_fields_msg').hide();$('#custom_fields').show();$('#other_fields').show();$(".styled").chosen({disable_search:true});$('#load').hide()})}});$("#type").chosen({disable_search:true});$("#choseCountries").chosen({disable_search_threshold:10,max_selected_options:15,width:"345px"});$('#select-countries').live('change',function(e){if($(this).val()=='0'){$('#target-select').hide()}else{$('#target-select').show()}});$('#dailyLimitSelect').live('change',function(e){if($(this).val()=='0'){$("#daily_clicks").prop('disabled',true).val('')}else{$("#daily_clicks").prop('disabled',false).val('100')}});$('#totalLimitSelect').live('change',function(e){if($(this).val()=='0'){$("#max_clicks").prop('disabled',true).val('')}else{$("#max_clicks").prop('disabled',false).val('1000')}}); </script>
<?php
	} else {
?>
	<div class="content t-left"><h2 class="title"><?=$lang['b_30']?></h2>
	<div class="msg"><div class="error"><?=lang_rep($lang['b_394'], array('-CLICKS-' => $site['req_clicks']))?></div></div>
<?php
	}

	include('footer.php');
?>