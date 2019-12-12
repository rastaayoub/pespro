<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
if(isset($_GET['type']) && $_GET['type'] == 2){
$msg = '';
if(isset($_GET['del']) && is_numeric($_GET['del'])){
	$del = $db->EscapeString($_GET['del']);
	$db->Query("DELETE FROM `blacklist` WHERE `type`='2' AND `id`='".$del."'");
}

if(isset($_POST['add'])){
	$value = $db->EscapeString($_POST['value']);
	
	if($db->QueryGetNumRows("SELECT id FROM `blacklist` WHERE `type`='2' AND `value`='".$value."' LIMIT 1") > 0){
		$msg = '<div class="alert error"><span class="icon"></span><strong>ERROR:</stront> Domain name already added!</div>';
	}elseif(!preg_match('/^[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})/', $value)){
		$msg = '<div class="alert error"><span class="icon"></span><strong>ERROR:</stront> Domain name must be something like <i>domain.com</i></div>';
	}else{
		$db->Query("INSERT INTO `blacklist` (`value`,`type`)VALUES('".$value."','2')");
		$msg = '<div class="alert success"><span class="icon"></span><strong>SUCCESS:</stront> Domain name was successfully added on blacklist!</div>';
	}
}
?>
<section id="content" class="container_12 clearfix ui-sortable"><?=$msg?>
	<h1 class="grid_12">Blacklist</h1>
	<div class="grid_6">
		<form method="POST" class="box">
			<div class="header">
				<h2>Add domain name in blacklist</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Domain name</strong></label>
					<div><input type="text" name="value" placeholder="domain.com" required="required" /></div>
				</div>		     
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="add" value="Add" />
				</div>
			</div>
        </form>
	</div>
	<div class="grid_6">
		<form class="box">
			<div class="header">
				<h2>Info</h2>
			</div>
			<div class="content">
				<p>Here you can add domain names in blacklist. If an domain name is added on blacklist, nobody can't add a web page from the blacklisted domain, in the system (is not applied for all modules, some modules like facebook, twitter followers, youtube, etc. are excluded from blacklist checking system). You can suspend an domain name like <i>domain.com</i></p>	
				<p>Websites already added are not affected.</p>
			</div>
        </form>
	</div>
	<div class="grid_12">
			<div class="box">
				<table class="styled">
					<thead>
						<tr>
							<th>ID</th>
							<th>Domain Name</th>
							<th width="40">Delete</th>
						</tr>
					</thead>
					<tbody>
<?
	$blacklist = $db->QueryFetchArrayAll("SELECT id,value FROM `blacklist` WHERE `type`='2' ORDER BY `id` DESC");
	foreach($blacklist as $black){
?>
						<tr>
							<td><?=$black['id']?></td>
							<td><?=$black['value']?></td>
							<td class="center">
								<a href="index.php?x=blacklist&type=2&del=<?=$black['id']?>" class="button small grey tooltip" data-gravity=s title="Remove"><i class="icon-remove"></i></a>
							</td>
						</tr>
<?}?>
					</tbody>
				</table>
		</div>
	</div>
</section>
<?
}elseif(isset($_GET['type']) && $_GET['type'] == 3){
$msg = '';
if(isset($_GET['del']) && is_numeric($_GET['del'])){
	$del = $db->EscapeString($_GET['del']);
	$db->Query("DELETE FROM `blacklist` WHERE `type`='3' AND `id`='".$del."'");
}

if(isset($_POST['add'])){
	$value = $db->EscapeString($_POST['value']);
	
	if($db->QueryGetNumRows("SELECT id FROM `blacklist` WHERE `type`='3' AND `value`='".$value."' LIMIT 1") > 0){
		$msg = '<div class="alert error"><span class="icon"></span><strong>ERROR:</stront> IP address already added!</div>';
	}elseif(!preg_match('^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}^', $value)){
		$msg = '<div class="alert error"><span class="icon"></span><strong>ERROR:</stront> IP address must be something like <i>255.255.255.255</i></div>';
	}else{
		$db->Query("INSERT INTO `blacklist` (`value`,`type`)VALUES('".$value."','3')");
		$msg = '<div class="alert success"><span class="icon"></span><strong>SUCCESS:</stront> IP address was successfully added on blacklist!</div>';
	}
}
?>
<section id="content" class="container_12 clearfix ui-sortable"><?=$msg?>
	<h1 class="grid_12">Blacklist</h1>
	<div class="grid_6">
		<form method="POST" class="box">
			<div class="header">
				<h2>Add IP in blacklist</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>IP</strong></label>
					<div><input type="text" name="value" placeholder="255.255.255.255" required="required" /></div>
				</div>		     
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="add" value="Add" />
				</div>
			</div>
        </form>
	</div>
	<div class="grid_6">
		<form class="box">
			<div class="header">
				<h2>Info</h2>
			</div>
			<div class="content">
				<p>Here you can add IP's in blacklist. If an IP is added on blacklist, nobody can't register or login on website from blacklisted IP address. You can suspend an IP address like <i>255.255.255.255</i></p>		
			</div>
        </form>
	</div>
	<div class="grid_12">
			<div class="box">
				<table class="styled">
					<thead>
						<tr>
							<th>ID</th>
							<th>IP</th>
							<th width="40">Delete</th>
						</tr>
					</thead>
					<tbody>
<?
	$blacklist = $db->QueryFetchArrayAll("SELECT id,value FROM `blacklist` WHERE `type`='3' ORDER BY `id` DESC");
	foreach($blacklist as $black){
?>
						<tr>
							<td><?=$black['id']?></td>
							<td><?=$black['value']?></td>
							<td class="center">
								<a href="index.php?x=blacklist&type=3&del=<?=$black['id']?>" class="button small grey tooltip" data-gravity=s title="Remove"><i class="icon-remove"></i></a>
							</td>
						</tr>
<?}?>
					</tbody>
				</table>
		</div>
	</div>
</section>
<?
}else{
$msg = '';
if(isset($_GET['del']) && is_numeric($_GET['del'])){
	$del = $db->EscapeString($_GET['del']);
	$db->Query("DELETE FROM `blacklist` WHERE `type`='1' AND `id`='".$del."'");
}

if(isset($_POST['add'])){
	$value = $db->EscapeString($_POST['value']);
	
	if($db->QueryGetNumRows("SELECT id FROM `blacklist` WHERE `type`='1' AND `value`='".$value."' LIMIT 1") > 0){
		$msg = '<div class="alert error"><span class="icon"></span><strong>ERROR:</stront> Email address already added!</div>';
	}elseif(!preg_match('/^(.*)@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})/', $value)){
		$msg = '<div class="alert error"><span class="icon"></span><strong>ERROR:</stront> Email must be something like <i>name@email.com</i> or <i>@email.com</i></div>';
	}else{
		$db->Query("INSERT INTO `blacklist` (`value`,`type`)VALUES('".$value."','1')");
		$msg = '<div class="alert success"><span class="icon"></span><strong>SUCCESS:</stront> Email address was successfully added on blacklist!</div>';
	}
}
?>
<section id="content" class="container_12 clearfix ui-sortable"><?=$msg?>
	<h1 class="grid_12">Blacklist</h1>
	<div class="grid_6">
		<form method="POST" class="box">
			<div class="header">
				<h2>Add email in blacklist</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Email</strong></label>
					<div><input type="text" name="value" placeholder="@email.com" required="required" /></div>
				</div>		     
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="add" value="Add" />
				</div>
			</div>
        </form>
	</div>
	<div class="grid_6">
		<form class="box">
			<div class="header">
				<h2>Info</h2>
			</div>
			<div class="content">
				<p>Here you can add emails in blacklist. If an email is added on blacklist, nobody can't register on website using this email address. You can suspend only one email like <i>name@email.com</i> or you can suspend all emails, adding email in this form: <i>@email.com</i></p>
				<p>If you add something like <i>@email.com</i>, all emails ending with <i>@email.com</i> will not be allowed anymore. Emails already registered are not affected.<p>		
			</div>
        </form>
	</div>
	<div class="grid_12">
			<div class="box">
				<table class="styled">
					<thead>
						<tr>
							<th>ID</th>
							<th>Email</th>
							<th width="40">Delete</th>
						</tr>
					</thead>
					<tbody>
<?
	$blacklist = $db->QueryFetchArrayAll("SELECT id,value FROM `blacklist` WHERE `type`='1' ORDER BY `id` DESC");
	foreach($blacklist as $black){
?>
						<tr>
							<td><?=$black['id']?></td>
							<td><?=$black['value']?></td>
							<td class="center">
								<a href="index.php?x=blacklist&del=<?=$black['id']?>" class="button small grey tooltip" data-gravity=s title="Remove"><i class="icon-remove"></i></a>
							</td>
						</tr>
<?}?>
					</tbody>
				</table>
		</div>
	</div>
</section><?}?>