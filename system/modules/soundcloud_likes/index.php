<?php
if(file_exists(realpath(dirname(__FILE__)).'/db_update.php')){
	include_once(realpath(dirname(__FILE__)).'/db_update.php');
}

register_filter('site_menu','soundcloud_likes_site_menu');
function soundcloud_likes_site_menu($menu) {
    $selected = "";
    if(isset($_GET["p"]) && $_GET["p"] == "soundcloud_likes")
    {
        $selected = 'selected';
    }
    else
    {
        $selected = 'value="soundcloud_likes"';
    }
	return $menu . '<option '.$selected.'>Soundcloud Likes</a>';
}

register_filter('soundcloud_likes_info','soundcloud_likes_info');
function soundcloud_likes_info($type) {
    if($type == "db")
    {
        return "soundcloud_likes";
    }
    else if($type == "type")
    {
        return "Soundcloud Likes";
    }
	else if($type == "name")
    {
        return "Soundcloud Likes";
    }
}

register_filter('soundcloud_likes_dtf','soundcloud_likes_dtf');
function soundcloud_likes_dtf($type) {
    return "soundcloud_likes";
}

register_filter('add_site_select','soundcloud_likes_add_select');
function soundcloud_likes_add_select($menu) {
    return $menu . "<option value='soundcloud_likes'>Soundcloud Likes</option>";
}

register_filter('stats','soundcloud_likes_stats');
function soundcloud_likes_stats($stats) {
	global $db;
	$sql = $db->Query("SELECT module_name,value FROM `web_stats` WHERE `module_id`='soundcloud_likes'");
	if($db->GetNumRows($sql) == 0){
		$clicks = $db->QueryFetchArray("SELECT SUM(`clicks`) AS `clicks` FROM `soundcloud_likes`");
		$clicks = ($clicks['clicks'] > 0 ? $clicks['clicks'] : 0);
		$db->Query("INSERT INTO `web_stats` (`module_id`,`module_name`,`value`)VALUES('soundcloud_likes','Soundcloud Likes','".$clicks."')");
	}else{
		$result = $db->FetchArray($sql);
		$clicks = ($result['value'] > 0 ? $result['value'] : 0);
	}

    $stat = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `soundcloud_likes`");
    return $stats . "<tr><td>".$result['module_name']."</td><td>".number_format($stat['total'])."</td><td>".number_format($clicks)."</td></tr>";
}

register_filter('tot_sites','soundcloud_likes_tot_sites');
function soundcloud_likes_tot_sites($stats) {
	global $db;
    $clicks = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `soundcloud_likes`");
    return $stats += $clicks['total'];
}

register_filter('module_tables','soundcloud_likes_module_tables');
function soundcloud_likes_module_tables($table) {
	return $table.'soundcloud_likes---soundcloud_liked(||)';
}

//Admin
register_filter('admin_s_sites','soundcloud_likes_admin_clicks');
function soundcloud_likes_admin_clicks($stats) {
	global $db;
	$clicks = $db->QueryFetchArray("SELECT value FROM `web_stats` WHERE `module_id`='soundcloud_likes'");
	$clicks = ($clicks['value'] > 0 ? $clicks['value'] : 0);
	$today_clicks = $db->QueryFetchArray("SELECT SUM(today_clicks) AS value FROM `user_clicks` WHERE `module`='soundcloud_likes'");
	$today_clicks = ($today_clicks['value'] > 0 ? $today_clicks['value'] : 0);
	$active = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `soundcloud_likes`");
	$active = $active['total'];
	$inactive = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `soundcloud_likes` WHERE `active`!='0'");
	$inactive = $inactive['total'];
	return $stats . '<div class="full-stats">
							<h2 class="center">Soundcloud Likes</h2>
							<div class="stat circular" data-valueFormat="0,0" data-list=\'[{"title":"Pages","val":'.$active.',"percent":'.round((($active - $inactive)/$active)*100, 0).'},{"title":"Clicks","val":'.$clicks.',"percent":0},{"title":"Today Clicks","val":'.$today_clicks.',"percent":0}]\'></div>
						</div>';
}

register_filter('admin_s_menu','soundcloud_likes_admin_menu');
function soundcloud_likes_admin_menu($menu) {
	return $menu . '<li><a href="index.php?x=sites&s=soundcloud_likes">Soundcloud Likes</a></li>';
}
?>