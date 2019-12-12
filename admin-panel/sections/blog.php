<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
if(isset($_GET['del']) && is_numeric($_GET['del'])){
	$del = $db->EscapeString($_GET['del']);
	$db->Query("DELETE FROM `blog` WHERE `id`='".$del."'");
	$db->Query("DELETE FROM `blog_comments` WHERE `bid`='".$del."'");
}
$mesaj = '';
if(isset($_GET['add'])){
	if(isset($_POST['addblog']) && $_POST['title'] != '' && $_POST['content'] != ''){
		$title = $db->EscapeString($_POST['title']);
		$content = $db->EscapeString($_POST['content']);

		if(empty($content) || empty($title)){
			$mesaj = '<div class="alert error"><span class="icon"></span><strong>Error!</stront> Please complete all fields!</div>';
		}else{
			$db->Query("INSERT INTO `blog` (`author`,`title`,`content`,`timestamp`)VALUES('".$data['id']."','".$title."','".$content."','".time()."')");
			$mesaj = '<div class="alert success"><span class="icon"></span> Blog was successfuly added!</div>';
		}
	}
}elseif(isset($_GET['edit'])){
	$id = $db->EscapeString($_GET['edit']);
	$edit = $db->QueryFetchArray("SELECT * FROM `blog` WHERE `id`='".$id."' LIMIT 1");
}

if(isset($_POST['submit'])){
	$content = $db->EscapeString($_POST['content']);
	$title = $db->EscapeString($_POST['title']);

	if(empty($content) || empty($title)){
		$mesaj = '<div class="alert error"><span class="icon"></span><strong>Error!</stront> Please complete all fields!</div>';
	}else{
		$db->Query("UPDATE `blog` SET `content`='".$content."', `title`='".$title."' WHERE `id`='".$id."'");
		$mesaj = '<div class="alert success"><span class="icon"></span><strong>Success!</stront> Blog was successfuly edited!</div>';
	}
}
if(isset($_GET['add'])){
?>
<script type="text/javascript">
	function bbcode(code, tag){
		document.getElementById(code).value += tag; 
	}
</script>
<section id="content" class="container_12 clearfix"><?=$mesaj?>
	<div class="grid_8">
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Add Blog</h2>
			</div>
				<div class="content">
					<div class="row">
						<label><strong>Title</strong></label>
						<div><input type="text" name="title" required="required" /></div>
					</div>
					<div class="row">
						<label><strong>Content</strong></label>
						<div><br />
							<span style="margin:0">
								<input type="button" value="Bold" name="bold" onclick="bbcode('message', '[b][/b]')" style="display:inline-block;" />
								<input type="button" value="Underline" name="underline" onclick="bbcode('message', '[u][/u]')" style="display:inline-block;" />
								<input type="button" value="Italic"  name="italic" onclick="bbcode('message', '[i][/i]')" style="display:inline-block;" />
								<input type="button" value="Link" name="link" onclick="bbcode('message', '[url][/url]')" style="50px;display:inline-block;" />
								<input type="button" value="Image" name="img" onclick="bbcode('message', '[img][/img]')" style="50px;display:inline-block;" />
								<input type="button" value="Code"  name="code" onclick="bbcode('message', '[code][/code]')" style="display:inline-block;" />
								<input type="button" value="Center"  name="center" onclick="bbcode('message', '[center][/center]')" style="display:inline-block;" />
							</span> 
							<textarea name="content" id="message" style="width:450px;height:60px" required="required"></textarea>
						</div>
					</div>										
                </div>
				<div class="actions">
					<div class="right">
						<input type="submit" value="Submit" name="addblog" />
					</div>
				</div>
		</form>
	</div>
	<div class="grid_4">
		<div class="box">
			<div class="header">
				<h2>Info</h2>
			</div>
            <div class="content"><br />
				<p>You can use following BB Code tags:
				<ul>
					<li>Bold: <br /><span style="margin:35px">[b]text[/b] => <b>text</b></span></li>
					<li>Underline: <br /><span style="margin:35px">[u]text[/u] => <u>text</u></span></li>
					<li>Italic: <br /><span style="margin:35px">[i]text[/i] => <i>text</i></span></li>
					<li>Code: <br /><span style="margin:35px">[code]text[/code] => <code>text</code></span></li>
					<li>Link: <br /><span style="margin:35px">[url=http://url.com]text[/url] => <a href="#">text</a></span></li>
				</ul>
				</p>
            </div>
		</div>
	</div>
</section>
<?}elseif(isset($_GET['edit'])){?>
<script type="text/javascript">
	function bbcode(code, tag){
		document.getElementById(code).value += tag; 
	}
</script>
<section id="content" class="container_12 clearfix"><?=$mesaj?>
	<div class="grid_8">
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Edit Blog</h2>
			</div>
				<div class="content">
					<div class="row">
						<label><strong>Title</strong></label>
						<div><input type="text" name="title" value="<?=(isset($_POST['title']) ? $_POST['title'] : $edit['title'])?>" required="required" /></div>
					</div>
					<div class="row">
						<label><strong>Content</strong></label>
						<div><br />
							<span style="margin:0">
								<input type="button" value="Bold" name="bold" onclick="bbcode('message', '[b][/b]')" style="display:inline-block;" />
								<input type="button" value="Underline" name="underline" onclick="bbcode('message', '[u][/u]')" style="display:inline-block;" />
								<input type="button" value="Italic"  name="italic" onclick="bbcode('message', '[i][/i]')" style="display:inline-block;" />
								<input type="button" value="Link" name="link" onclick="bbcode('message', '[url][/url]')" style="50px;display:inline-block;" />
								<input type="button" value="Image" name="img" onclick="bbcode('message', '[img][/img]')" style="50px;display:inline-block;" />
								<input type="button" value="Code"  name="code" onclick="bbcode('message', '[code][/code]')" style="display:inline-block;" />
								<input type="button" value="Center"  name="center" onclick="bbcode('message', '[center][/center]')" style="display:inline-block;" />
							</span> 
							<textarea name="content" id="message" style="width:450px;height:60px" required="required"><?=(isset($_POST['content']) ? $_POST['content'] : htmlspecialchars($edit['content']))?></textarea>
						</div>
					</div>										
                </div>
				<div class="actions">
					<div class="right">
						<input type="submit" value="Submit" name="submit" />
					</div>
				</div>
		</form>
	</div>
	<div class="grid_4">
		<div class="box">
			<div class="header">
				<h2>Info</h2>
			</div>
            <div class="content"><br />
				<p>You can use following BB Code tags:
				<ul>
					<li>Bold: <br /><span style="margin:35px">[b]text[/b] => <b>text</b></span></li>
					<li>Underline: <br /><span style="margin:35px">[u]text[/u] => <u>text</u></span></li>
					<li>Italic: <br /><span style="margin:35px">[i]text[/i] => <i>text</i></span></li>
					<li>Code: <br /><span style="margin:35px">[code]text[/code] => <code>text</code></span></li>
					<li>Link: <br /><span style="margin:35px">[url=http://url.com]text[/url] => <a href="#">text</a></span></li>
				</ul>
				</p>
            </div>
		</div>
	</div>
</section>
<?}else{?>
<section id="content" class="container_12 clearfix ui-sortable">
	<h1 class="grid_12">Blog</h1>
	<div class="grid_12">
		<div class="box">
			<table class="styled">
				<thead>
					<tr>
						<th width="25">ID</th>
						<th>Title</th>
						<th>Content</th>
						<th>Views</th>
						<th>Comments</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
<?
$pagina = (isset($_GET['p']) && is_numeric($_GET['p']) ? $_GET['p'] : 0);
$limit = 20;
$start = (is_numeric($pagina) && $pagina > 0 ? ($pagina-1)*$limit : 0);

$total_pages = $db->QueryGetNumRows("SELECT id FROM blog");
include('../system/libs/apaginate.php');

$blogs = $db->QueryFetchArrayAll("SELECT id,title,content,views FROM blog ORDER BY id DESC LIMIT ".$start.",".$limit."");
foreach($blogs as $blog){
	$comm = $db->QueryGetNumRows("SELECT id FROM `blog_comments` WHERE `bid`='".$blog['id']."'");
?>	
					<tr>
						<td><a href="/blog.php?bid=<?=$blog['id']?>" target="_blank"><?=$blog['id']?></a></td>
						<td><?=truncate($blog['title'], 50)?></td>
						<td><?=truncate(htmlspecialchars($blog['content']), 75)?></td>
						<td><?=number_format($blog['views'])?></td>
						<td><?=number_format($comm)?></td>
						<td class="center">
							<a href="index.php?x=blog&edit=<?=$blog['id']?>" class="button small grey tooltip"><i class="icon-edit"></i></a>
							<a href="index.php?x=blog&del=<?=$blog['id']?>" onclick="return confirm('You sure you want to delete this blog?');" class="button small grey tooltip"><i class="icon-remove"></i></a>
						</td>
					</tr>
<?}?>
				</tbody>
			</table>
			<?if($total_pages > $limit){?>
			<div class="dataTables_wrapper">
			<div class="footer">
				<div class="dataTables_paginate paging_full_numbers">
					<a class="first paginate_button" href="<?=GetHref('p=1')?>">First</a>
					<?=(($pagina <= 1 || $pagina == '') ? '<a class="previous paginate_button">&laquo;</a>' : '<a class="previous paginate_button" href="'.GetHref('p='.($pagina-1)).'">&laquo;</a>')?>
					<span><?=$pagination?></span>
					<?=(($pagina >= $lastpage) ? '<a class="next paginate_button">&raquo;</a>' : '<a class="next paginate_button" href="'.GetHref('p='.($pagina == 0 ? 2 : $pagina+1)).'">&raquo;</a>')?>
					<a class="last paginate_button" href="<?=GetHref('p='.$lastpage)?>">Last</a>
				</div>
			</div>
			</div>
			<?}}?>
		</div>
	</div>
</section>