<?include('header.php');?>
<div class="content t-left">
<h2 class="title"><?=$lang['b_349']?></h2>
	<table class="table" style="text-align:center">
		<thead>
			<tr><td></td><td><?=$lang['b_350']?></td><td><?=$lang['b_328']?></td><td><?=$lang['b_351']?></td><td><?=$lang['b_352']?></td></tr>
		</thead>
		<tbody>
			<?
				$levels = $db->QueryFetchArrayAll("SELECT * FROM `levels` ORDER BY level ASC");
				$myLevel = userLevel($data['id']);
				foreach($levels as $level){
					echo '<tr'.($myLevel == $level['level'] ? ' class="c_3"' : '').'><td><img src="'.$level['image'].'"></td><td>Level <b>'.$level['level'].'</b></td><td><b>'.number_format($level['requirements']).' exchanges</b></td><td><font color="#98ca33"><b>'.$level['free_bonus'].' '.$lang['b_42'].'</b></font></td><td style="text-align:center;"><font color="#98ca33"><b>'.$level['vip_bonus'].' '.$lang['b_42'].'</b></font></td></tr>';
				}
			?>
		</tbody>
		<tfoot>
			<tr><td></td><td><?=$lang['b_350']?></td><td><?=$lang['b_328']?></td><td><?=$lang['b_351']?></td><td><?=$lang['b_352']?></td></tr>
		</tfoot>
	</table>
	<div class="infobox" style="text-align: left;">
        <b><?=$lang['b_351']?></b> = <?=$lang['b_353']?><br />
		<b><?=$lang['b_352']?></b> = <?=$lang['b_354']?>
    </div>
</div>
<?include('footer.php');?>