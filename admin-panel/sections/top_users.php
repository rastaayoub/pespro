<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
$tops = $db->QueryFetchArrayAll("SELECT uid, SUM(`total_clicks`) AS `clicks` FROM `user_clicks` GROUP BY uid ORDER BY `clicks` DESC LIMIT 20");
?>
<section id="content" class="container_12 clearfix ui-sortable" data-sort=true>
	<h1 class="grid_12">Top 20 Users</h1>
	<div class="grid_12">
		<div class="box">
			<table class="styled">
				<thead>
					<tr>
						<th>#</th>
						<th>Username</th>
						<th>Total Clicks</th>
						<th>Email</th>
						<th>Country</th>
						<th>Coins</th>
						<th width="90">Actions</th>
					</tr>
				</thead>
				<tbody>
<?
$j = 0;
foreach($tops as $top){
	$j++;
	$user = $db->QueryFetchArray("SELECT id,login,email,country,coins FROM `users` WHERE `id`='".$top['uid']."'");
?>	
					<tr>
						<td><?=$j?></td>
						<td><a href="index.php?x=users&edit=<?=$user['id']?>"><?=$user['login']?></a></td>
						<td><b><?=number_format($top['clicks'])?></b></td>
						<td><?=$user['email']?></td>
						<td><?=($user['country'] == '0' ? 'N/A' : get_country($user['country']))?></td>
						<td><?=number_format($user['coins'])?></td>
						<td class="center">
							<a href="index.php?x=users&edit=<?=$user['id']?>" class="button small grey tooltip" data-gravity=s title="Edit"><i class="icon-pencil"></i></a>
							<a href="index.php?x=users&del=<?=$user['id']?>" onclick="return confirm('You sure you want to delete this user?');" class="button small grey tooltip" data-gravity=s title="Remove"><i class="icon-remove"></i></a>
						</td>
					</tr>
<?}?>
				</tbody>
			</table>
		</div>
	</div>
</section>