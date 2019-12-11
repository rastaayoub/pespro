<?
include('header.php');
if(!$is_online){
	redirect('index.php');
}

$mesaj = '';
if(isset($_POST['change_pass'])){
	if (!checkPwd($_POST['password'],$_POST['password2'])) {
		$mesaj = '<div class="msg"><div class="error">'.$lang['b_63'].'</div></div><br>';
	}else{
		$enpass = MD5($_POST['password']);
		$db->Query("UPDATE `users` SET `pass`='".$enpass."' WHERE `id`='".$data['id']."'");
		$mesaj = '<div class="msg"><div class="success">'.$lang['b_64'].'</div></div><br>';
	}
}elseif(isset($_POST['change_email'])){
	$email = $db->EscapeString($_POST['email']);
	$subs = $db->EscapeString($_POST['subscribe']);
	$subs = ($subs != 0 && $subs != 1 ? 1 : $subs);

	if(!isEmail($email)) {
		$mesaj = '<div class="msg"><div class="error">'.$lang['b_65'].'</div></div><br>';
	}elseif($db->QueryGetNumRows("SELECT id FROM `users` WHERE `email`='".$email."' LIMIT 1") > 0 && $data['email'] != $email){
		$mesaj = '<div class="msg"><div class="error">'.$lang['b_66'].'</div></div><br>';
	}else{
		$db->Query("UPDATE `users` SET `email`='".$email."', `newsletter`='".$subs."' WHERE `id`='".$data['id']."'");
		$mesaj = '<div class="msg"><div class="success">'.$lang['b_67'].'</div></div><br>';
	}
}

$change_limit = ($data['premium'] > 0 ? $site['c_v_limit'] : $site['c_c_limit']);
if(isset($_POST['change_info'])){
	$gender = $db->EscapeString($_POST['gender']);
	$country = $db->EscapeString($_POST['country']);
	
	$sql = $db->Query("SELECT code FROM `list_countries` ORDER BY country");
	$ctrs = array();
	while ($row = $db->FetchArray($sql)) {
		$ctrs[] = $row['code'];
	}

	if($gender != 1 && $gender != 2){
		$mesaj = '<div class="msg"><div class="error">'.$lang['b_208'].'</div></div><br>';
	}elseif(!in_array($country, $ctrs) || $country == '0'){
		$mesaj = '<div class="msg"><div class="error">'.$lang['b_209'].'</div></div><br>';
	}elseif($data['c_changes'] >= $change_limit){
		$mesaj = '<div class="msg"><div class="error">'.lang_rep($lang['b_222'], array('-NUM-' => $change_limit)).'</div></div><br>';
	}else{
		$db->Query("UPDATE `users` SET `country`='".$country."', `c_changes`=`c_changes`+'1', `sex`='".$gender."' WHERE `id`='".$data['id']."'");
		$mesaj = '<div class="msg"><div class="success">'.$lang['b_211'].'</div></div><br>';
	}
}
?>
<div class="content">
	<h2 class="title"><?=$lang['b_69']?></h2><?=$mesaj?>
	<div class="infobox t-left">
		<form method="post">
			<p>
				<input class="text big" type="email" value="<?=$data['email']?>" name="email" /> <input type="submit" class="gbut" value="<?=$lang['b_58']?>" name="change_email" />
			</p>
			<p>
				<?=$lang['b_245']?> <input type="radio" name="subscribe" value="1" <?=(!isset($_POST['subscribe']) && $data['newsletter'] == 1 ? 'checked="checked" ' : (isset($_POST['subscribe']) && $_POST['subscribe'] == 1 ? 'checked="checked" ' : ''))?>/> Yes <input type="radio" name="subscribe" value="0" <?=(!isset($_POST['subscribe']) && $data['newsletter'] == 0 ? 'checked="checked" ' : (isset($_POST['subscribe']) && $_POST['subscribe'] == 0 ? 'checked="checked" ' : ''))?>/> No
			</p>
		</form>
	</div>	<br />
	<h2 class="title"><?=$lang['b_206']?></h2>
	<div class="infobox t-left">
		<form method="post">
			<p>
				<b><?=$lang['b_202']?></b><br>
				<select name="gender" class="styled"<?=($data['c_changes'] >= $change_limit && $data['sex'] != 0 ? ' disabled' : '')?>><option value="0"></option><option value="1"<?=($data['sex'] == 1 ? ' selected' : '')?>><?=$lang['b_203']?></option><option value="2"<?=($data['sex'] == 2 ? ' selected' : '')?>><?=$lang['b_204']?></option></select>
			</p>
			<p>
				<b><?=$lang['b_201']?></b><br>
				<select name="country" class="styled"<?=($data['c_changes'] >= $change_limit && $data['country'] != '0' ? ' disabled' : '')?>><option value="0"></option><? $countries = $db->QueryFetchArrayAll("SELECT country,code FROM `list_countries` ORDER BY country ASC"); foreach($countries as $country){ echo '<option value="'.$country['code'].'"'.($data['country'] == $country['code'] ? ' selected' : '').'>'.$country['country'].'</option>';}?></select>
				<br><small><?=lang_rep($lang['b_207'], array('-NUM-' => ($change_limit-$data['c_changes']) < 0 ? '0' : ($change_limit-$data['c_changes'])))?></small>
			</p>
			<?if($data['c_changes'] < $change_limit || $data['sex'] == '0' || $data['country'] == '0'){?>
			<p>
				<input type="submit" class="gbut" value="<?=$lang['b_58']?>" name="change_info" />
			</p>
			<?}?>
		</form>
	</div>	<br />
	<h2 class="title"><?=$lang['b_68']?></h2>
	<div class="infobox t-left">
		<form method="post">
			 <p>
				 <b><?=$lang['b_71']?></b> <br />
				 <input class="text big" type="password" name="password"/>
			 </p>
			 <p>
				 <b><?=$lang['b_72']?></b> <br/>
				 <input class="text big" type="password" name="password2"/>
			 </p>
			  <p>
				<input type="submit" class="gbut" value="<?=$lang['b_68']?>" name="change_pass" />
			  </p>
		</form>
	</div>
<?
/*if(isset($_POST['del_acc'])){
	$pass = MD5($_POST['pass']);
	if($db->QueryGetNumRows("SELECT id FROM `users` WHERE `id`='".$data['id']."' AND `pass`='".$pass."'") == 0){
		$mesaj = '<div class="msg"><div class="error"><b>ERROR:</b> Wrong password!</div></div><br>';
	}else{
		$db->Query("UPDATE `users` SET `deleted`='1' WHERE `id`='".$data['id']."' AND `pass`='".$pass."'");
		if(isset($_COOKIE['PESAutoLogin'])){
			setcookie('PESAutoLogin', '0', time()-604800);
		}
		session_destroy();
		redirect("index.php");
	}
}*/

/*<br />
	<h2 class="title"><?=$lang['b_276']?></h2>
	<div class="infobox t-left">
		<form method="post">
			 <p>
				 <b><?=$lang['b_15']?></b> <br />
				 <input class="text big" type="password" name="pass"/>
			 </p>
			  <p>
				<input type="submit" class="gbut" value="<?=$lang['b_276']?>" name="del_acc" />
			  </p>
		</form>
	</div>*/
?>
</div>
<?include('footer.php');?>