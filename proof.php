<?
include('header.php');
if($site['allow_withdraw'] != 1){redirect('index.php');}
?>
<link rel="stylesheet" type="text/css" href="js/lightbox/jquery.lightbox.css">
<script type="text/javascript" src="js/lightbox/jquery.lightbox.min.js"></script>
<script type="text/javascript"> $(function() { $('a.proof_image').lightBox({  txtImage: '<?=$lang['b_303']?>', fixedNavigation:true, imageLoading: 'js/lightbox/images/lightbox-ico-loading.gif', imageBtnClose: 'js/lightbox/images/lightbox-btn-close.png', imageBtnPrev: 'js/lightbox/images/lightbox-btn-prev.png', imageBtnNext: 'js/lightbox/images/lightbox-btn-next.png' }); }) </script>
<div class="content t-left">
<?
if(isset($_GET['upload'])){
	$is_valid = 0;
	if($_GET['upload'] > 0 && is_numeric($_GET['upload'])){
		$request = $db->EscapeString($_GET['upload']);
		$check_valid = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `requests` WHERE (`user`='".$data['id']."' AND `id`='".$request."') AND `proof`='0'");
		
		if($check_valid['total'] > 0){
			$is_valid = 1;
		}
	}

	if(isset($_POST['submit'])){
		$request = $db->EscapeString($is_valid ? $_GET['upload'] : $_POST['request']);
		$request = $db->QueryFetchArray("SELECT * FROM `requests` WHERE (`user`='".$data['id']."' AND `id`='".$request."') AND `proof`='0' LIMIT 1");

		$MAX_SIZE = 750;	// Max image size in kb
		function getExtension($str) {
			if($str == 'image/jpeg'){
				return 'jpg';
			}elseif($str == 'image/png'){
				return 'png';
			}elseif($str == 'image/gif'){
				return 'gif';
			}
		}

		if(!empty($request) && $_FILES['cons_image']['name']){
			$tmpFile = $_FILES['cons_image']['tmp_name'];
			$b_info = getimagesize($tmpFile);
			$extension = getExtension($b_info['mime']);
			
			if($request['id'] == ''){
				echo '<div class="msg"><div class="error">'.$lang['b_304'].'</div></div>';
			}elseif($b_info['mime'] != 'image/jpeg' && $b_info['mime'] != 'image/png' && $b_info['mime'] != 'image/gif'){
				echo '<div class="msg"><div class="error">'.$lang['b_171'].'</div></div>';
			}elseif(filesize($tmpFile) > $MAX_SIZE*1024){
				echo '<div class="msg"><div class="error">'.lang_rep($lang['b_305'], array('-SIZE-' => $MAX_SIZE)).'</div></div>';
			}else{
				$image_name = 'p-'.MD5($data['id'].'_'.$request['id'].'_'.time()).'.'.$extension;
				$copied = copy($tmpFile, dirname( __FILE__ )."/files/proofs/".$image_name);

				if(!$copied){
					echo '<div class="msg"><div class="error"><b>ERROR:</b> Image wasn\'t uploaded, please contact site admin!</div></div>';
				}else{
					$proof = '/files/proofs/'.$image_name;
					$db->Query("INSERT INTO `payment_proofs` (p_id, u_id, proof, proof_date, approved) VALUES('".$request['id']."', '".$data['id']."', '".$proof."', '".time()."', '0')");
					$db->Query("UPDATE `requests` SET `proof`='1' WHERE `id`='".$request['id']."'");

					echo '<div class="msg"><div class="success">'.$lang['b_306'].'</div></div>';
				}
			}
		}else{
			echo '<div class="msg"><div class="error">'.$lang['b_25'].'</div></div>';
		}
	}
	if($db->QueryGetNumRows("SELECT id FROM `requests` WHERE `user`='".$data['id']."' AND `proof`='0' LIMIT 1") > 0){
?>
	<h2 class="title"><?=$lang['b_307']?></h2>
	<form method="post" enctype="multipart/form-data">
		<p>
			<label><?=$lang['b_308']?></label> <small style="float:right"><?=$lang['b_309']?></small>
			<div class="upload_input"><input type="file" name="cons_image" /></div> 
		</p>
		<?if(!$is_valid){?>
		<p>
			<label><?=$lang['b_310']?></label> <small style="float:right"><?=$lang['b_311']?></small>
			<div class="upload_input"><select name="request" class="styled">
			<?
				$requests = $db->QueryFetchArrayAll("SELECT id, amount, UNIX_TIMESTAMP(date) AS date FROM `requests` WHERE `user`='".$data['id']."' AND (`paid`='1' AND `proof`='0') ORDER BY `date` ASC");
				foreach($requests as $request){
					echo '<option value="'.$request['id'].'" '.(isset($_POST['request']) && $_POST['request'] == $request['id'] ? ' selected' : '').'>'.$lang['b_312'].' #'.$request['id'].' - '.$lang['b_103'].': '.$request['amount'].' '.get_currency_symbol($site['currency_code']).' - '.$lang['b_106'].': '.date('d M Y', $request['date']).'</option>';
				}
			?>
			</select></div>
		</p>
		<?}?>
		<p><input type="submit" name="submit" class="gbut" value="<?=$lang['b_58']?>" /></p>
	</form>
	<div style="display:block;clear:both;"></div><br />
<?}else{?>
	<h2 class="title"><?=$lang['b_307']?></h2>
	<div class="msg"><div class="error">Your don't have any payout received yet!</div></div>
<?
}
	$proofs = $db->QueryFetchArrayAll("SELECT a.id, a.user, a.amount, UNIX_TIMESTAMP(a.date) AS date, b.proof, b.approved, c.login FROM requests a LEFT JOIN payment_proofs b ON b.p_id = a.id LEFT JOIN users c ON c.id = a.user WHERE a.paid = '1' AND a.user = '".$data['id']."' ORDER BY a.date DESC");
	if($proofs){
?>
	<h2 class="title"><?=$lang['b_313']?></h2>
	<?
		foreach($proofs as $proof){
	?>
		<div class="proof_wrapper">
		  <?if($proof['approved'] == 0){?><div class="proof_username" style="color:blue"><?=$lang['b_260']?></div><?}else{?><div class="proof_username" style="color:green"><?=$lang['b_319']?></div><?}?>
		  <div class="proof_imgwrapper"><? if($proof['proof'] == ''){ ?><a href="proof.php?upload=<?=$proof['id']?>"><img src="/img/upload_proof.png" align="absmiddle" height="65" width="90" title="<?=($lang['b_272'].': '.$proof['login'].' - '.$lang['b_103'].': $'.$proof['amount'].' - '.date('d M Y', $proof['date']))?>"></a><?}else{?><a class="proof_image" href="<?=$proof['proof']?>" title="<?=($lang['b_272'].': '.$proof['login'].' - '.$lang['b_103'].': $'.$proof['amount'].' - '.date('d M Y', $proof['date']))?>"><img class="proof_img" src="<?=$proof['proof']?>" align="absmiddle" height="65" width="90"></a><?}?></div>
		  <div class="proof_username"><?=$proof['login']?></div>
		  <div class="proof_amount"><?=get_currency_symbol($site['currency_code']).$proof['amount']?></div>
		</div>
<?
		}
	}
}else{
	$db_custom = '';
	if(isset($_GET['month'])){
		$db_custom = " AND MONTH(a.date) = '".date('m')."'";
	}

	$p_data = $db->QueryFetchArray("SELECT COUNT(a.id) AS total, SUM(a.amount) AS money FROM requests a LEFT JOIN payment_proofs b ON b.p_id = a.id AND b.approved = '1' LEFT JOIN users c ON c.id = a.user WHERE a.paid = '1'".$db_custom);
	$bpp = 24;
	$page = intval($_GET['p']);
	$begin = ($page >= 0 ? ($page*$bpp) : 0);
	$proofs = $db->QueryFetchArrayAll("SELECT a.user, a.amount, UNIX_TIMESTAMP(a.date) AS date, b.proof, c.login FROM requests a LEFT JOIN payment_proofs b ON b.p_id = a.id AND b.approved = '1' LEFT JOIN users c ON c.id = a.user WHERE a.paid = '1'".$db_custom." ORDER BY a.date DESC LIMIT ".$begin.", ".$bpp."");
?>
<script type="text/javascript">
function topOrder() {
	var oid = document.getElementById("oid").value;
	if(oid == '1') {
		location.href = "<?=$site['site_url']?>/proof.php?month";
	}else{
		location.href = "<?=$site['site_url']?>/proof.php";
	}
	return false;
}
</script>
	<h2 class="title"><?=$lang['b_303']?> - <a href="proof.php?upload"><?=$lang['b_316']?></a> <span style="float:right"><select id="oid" onchange="topOrder()"><option value="0"><?=$lang['b_275']?></option><option value="1"<?=(isset($_GET['month']) ? ' selected' : '')?>><?=$lang['b_274']?></option></select></span></h2>
	<center>
		<div class="infobox" style="width:250px;margin:-5px 3px 9px;display:inline-block"><b><?=$lang['b_321']?>: <span style="font-weight:620;color:blue"><?=$p_data['total']?></span></b></div>
		<div class="infobox" style="width:250px;margin:-5px 4px 9px;display:inline-block"><b><?=$lang['b_322']?>: <span style="font-weight:620;color:green"><?=get_currency_symbol($site['currency_code'])?> <?=(empty($p_data['money']) ? '0.00' : $p_data['money'])?></span></b></div>
	</center>
	<div style="clear:both"></div>
	<?
		foreach($proofs as $proof){
	?>
		<div class="proof_wrapper">
		  <div class="proof_username"><?=$proof['login']?></div>
		  <div class="proof_imgwrapper"><? if($proof['proof'] == ''){ ?><img src="/img/no_proof.png" align="absmiddle" height="65" width="90" title="<?=($lang['b_272'].': '.$proof['login'].' - '.$lang['b_103'].': $'.$proof['amount'].' - '.date('d M Y', $proof['date']))?>"><?}else{?><a class="proof_image" href="<?=$proof['proof']?>" title="<?=($lang['b_272'].': '.$proof['login'].' - '.$lang['b_103'].': $'.$proof['amount'].' - '.date('d M Y', $proof['date']))?>"><img class="proof_img" src="<?=$proof['proof']?>" align="absmiddle" height="65" width="90"></a><?}?></div>
		  <div class="proof_date"><?=date('d M Y', $proof['date'])?></div>
		  <div class="proof_amount"><?=get_currency_symbol($site['currency_code']).$proof['amount']?></div>
		</div>
	<?
		}if(ceil($p_data['total']/$bpp) > 1){
	?>
	<div class="infobox">
		<div style="float:left;"><?=lang_rep($lang['b_314'], array('-NUM-' => $p_data['total']))?></div>
		<div style="float:right;">
		<?
			if($p_data['total'] >= 0) {
				if($begin/$bpp == 0) {
					$left = '<img src="theme/pes/images/black_arrow_left.png" />';
				}else{
					$left = '<a href="?p='.($begin/$bpp-1).'"><img src="theme/pes/images/black_arrow_left.png" /></a>';
				}
				if($begin+$bpp >= $p_data['total']) {
					$right = '<img src="theme/pes/images/black_arrow_right.png" />';
				}else{
					$right = '<a href="?p='.($begin/$bpp+1).'"><img src="theme/pes/images/black_arrow_right.png" /></a>';
				}
				
				echo $left.'&nbsp;&nbsp; '.$begin.' - '.($begin+$bpp > $p_data['total'] ? $p_data['total'] : $begin+$bpp).' &nbsp;&nbsp;'.$right;
			}
		?>
		</div>
		<div style="display:block;clear:both;"></div>
	</div>
	<?}}?>
</div>
<?include('footer.php');?>