<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
if(isset($_GET['s'])){
	$account = $_GET['s'];
}else{
	$account = 'facebook';
}

$pagina = (isset($_GET['p']) ? $_GET['p'] : '');
$limit = 20;
$start = (is_numeric($pagina) && $pagina > 0 ? ($pagina-1)*$limit : 0);

$title = hook_filter($account.'_info','type');
?>
<section id="content" class="container_12 clearfix ui-sortable">
	<h1 class="grid_12">Pages - <?=$title?></h1>
	<div class="grid_12">
		<form method="get" class="box">
		<input type="hidden" name="x" value="sites" /> 
		<input type="hidden" name="s" value="<?=$account?>" />
			<div class="header">
				<h2>Search Page</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Search</strong></label>
					<div><input type="text" name="sp" required="required" /></div>
				</div>	
				<div class="row">
					<label><strong>By</strong></label>
					<div><select name="s_type"><option value="1">URL</option><option value="0">User</option><option value="2">ID</option></select></div>
				</div>				     
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" value="Search" />
				</div>
			</div>
        </form>
	</div>
	<div class="grid_12">
<?
if(file_exists('modules/'.$account.'/sites.php')){
	include('modules/'.$account.'/sites.php');
}else{
	redirect('index.php');
}
?>	
	</div>
</section>