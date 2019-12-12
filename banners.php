<?php
include('header.php');
if(!$is_online || $site['banner_system'] == 0){
	redirect('index.php');
}
if($site['banner_system'] == 2 && $data['premium'] == 0){
?>
<div class="content t-left"><h2 class="title"><?=$lang['b_173']?></h2>
	<div class="msg"><div class="error"><?=$lang['b_234']?></div></div>
</div>
<?
}else{
if(isset($_GET['add'])){

$msg = '';
if(isset($_POST['submit'])){
	$url = $db->EscapeString($_POST['url']);
	$pack = $db->EscapeString($_POST['pack']);
	$pack = $db->QueryFetchArray("SELECT * FROM `ad_packs` WHERE `id`='".$pack."'");

	$MAX_SIZE = 500;	// Max banner size in kb
	function getExtension($str) {
		if($str == 'image/jpeg'){
			return 'jpg';
		}elseif($str == 'image/png'){
			return 'png';
		}elseif($str == 'image/gif'){
			return 'gif';
		}
	}
	function random_string($length) {
		$key = '';
		$keys = array_merge(range(0, 9), range('a', 'z'));
		for ($i = 0; $i < $length; $i++) {
			$key .= $keys[array_rand($keys)];
		}
		return $key;
	}

	if(!empty($url) && !empty($pack) && $_FILES['cons_image']['name']){
		$tmpFile = $_FILES['cons_image']['tmp_name'];
		$b_info = getimagesize($tmpFile);
		$extension = getExtension($b_info['mime']);
		
		if($pack['id'] == ''){
			$msg = '<div class="msg"><div class="error">'.$lang['b_168'].'</div></div>';
		}elseif($pack['price'] > $data['account_balance']){
			$msg = '<div class="msg"><div class="error">'.$lang['b_99'].'</div></div>';
		}elseif(!preg_match("|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i", $url) || substr($url,-4) == '.exe'){
			$msg = '<div class="msg"><div class="error">'.$lang['b_167'].'</div></div>';
		}elseif($b_info['mime'] != 'image/jpeg' && $b_info['mime'] != 'image/png' && $b_info['mime'] != 'image/gif'){
			$msg = '<div class="msg"><div class="error">'.$lang['b_171'].'</div></div>';
		}elseif($pack['type'] == 0 && ($b_info[0] != '468' && $b_info[1] != '60')){
			$msg = '<div class="msg"><div class="error">'.lang_rep($lang['b_338'], array('-SIZE-' => '468x60')).'</div></div>';
		}elseif($pack['type'] == 1 && $b_info[0] != '728' && $b_info[1] != '90'){
			$msg = '<div class="msg"><div class="error">'.lang_rep($lang['b_338'], array('-SIZE-' => '728x90')).'</div></div>';
		}elseif(filesize($tmpFile) > $MAX_SIZE*1024){
			$msg = '<div class="msg"><div class="error">'.lang_rep($lang['b_305'], array('-SIZE-' => $MAX_SIZE)).'</div></div>';
		}else{	
			$image_name = 'b-'.$data['id'].'_'.($pack['type'] == 1 ? '728x90' : '648x60').'_'.random_string(rand(7,14)).'.'.$extension;
			$copied = copy($tmpFile, dirname( __FILE__ )."/files/banners/".$image_name);

			if(!$copied){
				$msg = '<div class="msg"><div class="error"><b>ERROR:</b> Banner wasn\'t uploaded, please contact your site admin!</div></div>';
			}else{
				$banner = '/files/banners/'.$image_name;
				$expiration = ($pack['days']*86400)+time();
				$db->Query("UPDATE `users` SET `account_balance`=`account_balance`-'".$pack['price']."' WHERE `id`='".$data['id']."'");
				$db->Query("UPDATE `ad_packs` SET `bought`=`bought`+'1' WHERE `id`='".$pack['id']."'");
				$db->Query("INSERT INTO `banners` (user, banner_url, site_url, expiration, type) VALUES('".$data['id']."', '".$banner."', '".$url."', '".$expiration."', '".$pack['type']."')");
				
				if($site['paysys'] == 1 && $data['ref'] > 0 && $data['ref_paid'] == 1 && $pack['price'] > 0){
					$commission = number_format(($pack['price']/100) * $site['ref_sale'], 2);
					affiliate_commission($data['ref'], $data['id'], $commission, 'banner_purchase');	
				}
				
				$msg = '<div class="msg"><div class="success">'.$lang['b_170'].'</div></div>';
			}
		}
	}else{
		$msg = '<div class="msg"><div class="error">'.$lang['b_25'].'</div></div>';
	}
}
?>
<script type="text/javascript"> function getOptions(){var b=$("#type").val();$('#bPacks').hide();$('#load').show();$.get('system/ajax.php?a=bannerPacks&type='+b,function(a){$('#bPacks').html(a);$('#load').hide();$('#bPacks').show()})} </script>
<div class="content t-left">
	<h2 class="title"><?=$lang['b_173']?></h2>
	<div class="infobox" style="text-align:center"><div class="ucp_link<?=(!isset($_GET['add']) ? ' active' : '')?>" style="margin-right:5px;display:inline-block"><a href="banners.php"><?=$lang['b_179']?></a></div><div class="ucp_link<?=(isset($_GET['add']) ? ' active' : '')?>" style="margin-right:5px;display:inline-block"><a href="banners.php?add"><?=$lang['b_173']?></a></div></div><br /><?=$msg?>
	<form method="post" enctype="multipart/form-data">
		<p>
			<label><?=$lang['b_174']?></label> <small style="float:right"><?=$lang['b_34']?></small><br/>
			<input class="text-max" type="text" value="http://" name="url" />
		</p>
		<p>
			<label><?=$lang['b_175']?></label> <small style="float:right"><?=$lang['b_176']?></small>
			<div style="background:#efefef;padding:8px;color:#000;border-radius:3px"><input type="file" name="cons_image" /></div> 
		</p>
		<p>
			<label><?=$lang['b_339']?></label> <br />
			<select name="type" id="type" class="styled" onchange="getOptions()"><option value="0">468x60</option><option value="1">728x90</option></select>
		</p>
		<p>
			<label><?=$lang['b_177']?></label> <br />
			<select name="pack" class="styled" id="bPacks">
			<?
			$packs = $db->QueryFetchArrayAll("SELECT * FROM `ad_packs` WHERE `type`='0' ORDER BY `price` ASC");
			foreach($packs as $pack){
				echo '<option value="'.$pack['id'].'" '.(isset($_POST['pack']) && $_POST['pack'] == $pack['id'] ? ' selected' : '').'">'.$pack['days'].' '.$lang['b_178'].' - '.get_currency_symbol($site['currency_code']).$pack['price'].'</option>';
			}
			?>
			</select>
			<span id="load" style="display:none"><img src="img/ajax-loader.gif" alt="" /> <?=$lang['b_301']?>...</span>
		</p>
		<p>
			<input type="submit" name="submit" class="gbut" value="<?=$lang['b_58']?>" />
		</p>
	</form>
</div>	
<?
}elseif(isset($_GET['edit'])){
$id = $db->EscapeString($_GET['edit']);
$banner = $db->QueryFetchArray("SELECT * FROM `banners` WHERE `id`='".$id."' AND `user`='".$data['id']."'");

if(empty($banner['id'])){
	redirect('banners.php');
}

if(isset($_POST['delete'])){
    $db->Query("DELETE FROM `banners` WHERE `id`='".$id."' AND `user`='".$data['id']."'");
	redirect('banners.php');
}elseif(isset($_POST['update'])){
	$pack = $db->EscapeString($_POST['pack']);
	$pack = $db->QueryFetchArray("SELECT * FROM `ad_packs` WHERE `id`='".$pack."'");

	if(empty($pack['id'])){
		$msg = '<div class="msg"><div class="error">'.$lang['b_168'].'</div></div>';
	}elseif($pack['price'] > $data['account_balance']){
		$msg = '<div class="msg"><div class="error">'.$lang['b_99'].'</div></div>';
	}elseif(!empty($banner['id']) && $banner['type'] == $pack['type']){
		$banner['expiration'] = ($banner['expiration'] > 0 ? ($pack['days']*86400)+$banner['expiration'] : ($pack['days']*86400)+time());
		$db->Query("UPDATE `users` SET `account_balance`=`account_balance`-'".$pack['price']."' WHERE `id`='".$data['id']."'");
		$db->Query("UPDATE `ad_packs` SET `bought`=`bought`+'1' WHERE `id`='".$pack['id']."'");
		$db->Query("UPDATE `banners` SET `expiration`='".$banner['expiration']."' WHERE `id`='".$id."' AND `user`='".$data['id']."'");
		
		if($site['paysys'] == 1 && $data['ref'] > 0 && $data['ref_paid'] == 1 && $pack['price'] > 0){
			$commission = number_format(($pack['price']/100) * $site['ref_sale'], 2);
			affiliate_commission($data['ref'], $data['id'], $commission, 'banner_update');
		}
		
		$msg = '<div class="msg"><div class="success">'.$lang['b_74'].'</div></div>';
	}
}
?>
<div class="content t-left"><h2 class="title"><?=$lang['b_179']?></h2><?=$msg?>
	<form method="post">
			<div class="infobox"><b><?=$lang['b_186']?>:</b> <?=($banner['expiration'] == 0 ? 'Expired' : date('d-m-Y H:i', $banner['expiration']))?></div>
			<p>
				<label><?=$lang['b_187']?></label><br />
				<select name="pack" class="styled">
				<?
					$packs = $db->QueryFetchArrayAll("SELECT * FROM `ad_packs` WHERE `type`='".$banner['type']."' ORDER BY `price` ASC");
					foreach($packs as $pack){echo '<option value="'.$pack['id'].'"'.(isset($_POST["pack"]) && $_POST["pack"] == $pack['id'] ? ' selected' : '').'>'.$pack['days'].' '.$lang['b_178'].' - '.get_currency_symbol($site['currency_code']).$pack['price'].'</option>';}
				?>
				</select>
			</p>
			<p>
				<input type="submit" name="update" class="gbut" value="<?=$lang['b_188']?>" />
				<input type="submit" name="delete" class="bbut" onclick="return confirm('<?=$lang['b_80']?>');" value="<?=$lang['b_81']?>" />
			</p>
	</form>
</div>
<?}else{?>
<div class="content t-left"><h2 class="title"><?=$lang['b_179']?></h2>
<div class="infobox" style="text-align:center"><div class="ucp_link<?=(!isset($_GET['add']) ? ' active' : '')?>" style="margin-right:5px;display:inline-block"><a href="banners.php"><?=$lang['b_179']?></a></div><div class="ucp_link<?=(isset($_GET['add']) ? ' active' : '')?>" style="margin-right:5px;display:inline-block"><a href="banners.php?add"><?=$lang['b_173']?></a></div></div>
<table cellpadding="5" class="table">
	<thead>
		<tr><td><?=$lang['b_182']?></td><td width="75px"><?=$lang['b_183']?></td><td><?=$lang['b_184']?></td><td><?=$lang['b_339']?></td><td><?=$lang['b_75']?></td><td><?=$lang['b_185']?></td></tr>
	</thead>
	<tbody>
<?
$banners = $db->QueryFetchArrayAll("SELECT * FROM `banners` WHERE `user`='".$data['id']."'");
foreach($banners as $banner){
$x = 1; $x++;
$status = ($banner['expiration'] != 0 ? '<font color="green">'.$lang['b_180'].'</font>' : ($mysite['status'] == 2 ? '<font color="red"><b>'.$lang['b_78'].'</b></font>' : '<font color="red">'.$lang['b_181'].'</font>'));
$color = ($x%2) ? 3 : 1;
?>
    <tr class="c_<?=$color?>"><td><a href="<?=$banner['site_url']?>" title="<?=$banner['site_url']?>" target="_blank"><img src="<?=$site['site_url'].$banner['banner_url']?>" width="280" border="0" /></a></td><td><?=number_format($banner['views'])?></td><td><?=number_format($banner['clicks'])?></td><td align="center"><?=($banner['type'] == 1 ? '728x90' : '468x60')?></td><td><?=$status?></td><td align="center"><?if($banner['status'] != 2){?><a href="banners.php?edit=<?=$banner['id']?>"><?=$lang['b_96']?></a><?}?></td></tr>
<?}?>
	</tbody>
</table>
</div>
<?}}include('footer.php');?>