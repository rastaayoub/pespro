<?php
if(file_exists(realpath(dirname(__FILE__)).'/db_update.php')){
	include_once(realpath(dirname(__FILE__)).'/db_update.php');
}

register_filter('index_icons','ylike_icon');
function ylike_icon($icons) {
	global $is_online;

	if($is_online){
		$icons .= '<a href="p.php?p=ylike"><img class="exchange_icon" src="img/icons/ylike.png" alt="YLike" /></a>';
	} else {
		$icons .= '<img class="exchange_icon" src="img/icons/ylike.png" alt="YLike" />';
	}
	
	return $icons;
}

register_filter('site_menu','ylike_site_menu');
function ylike_site_menu($menu) {
    $selected = "";
    if(isset($_GET["p"]) && $_GET["p"] == "ylike")
    {
        $selected = 'selected';
    }
    else
    {
        $selected = 'value="ylike"';
    }
	return $menu . '<option '.$selected.'>Youtube Likes</a>';
}

register_filter('ylike_info','ylike_info');
function ylike_info($type) {
    if($type == "db")
    {
        return "ylike";
    }
    else if($type == "type")
    {
        return "Youtube Likes";
    }
	else if($type == "name")
    {
        return "Youtube Likes";
    }
}

register_filter('ylike_dtf','ylike_dtf');
function ylike_dtf($type) {
    return "ylike";
}

register_filter('add_site_select','ylike_add_select');
function ylike_add_select($menu) {
    return $menu . "<option value='ylike'>Youtube Likes</option>";
}

register_filter('stats','ylike_stats');
function ylike_stats($stats) {
	global $db;
	$sql = $db->Query("SELECT module_name,value FROM `web_stats` WHERE `module_id`='ylike'");
	if($db->GetNumRows($sql) == 0){
		$clicks = $db->QueryFetchArray("SELECT SUM(`clicks`) AS `clicks` FROM `ylike`");
		$clicks = ($clicks['clicks'] > 0 ? $clicks['clicks'] : 0);
		$db->Query("INSERT INTO `web_stats` (`module_id`,`module_name`,`value`)VALUES('ylike','Youtube Likes','".$clicks."')");
	}else{
		$result = $db->FetchArray($sql);
		$clicks = ($result['value'] > 0 ? $result['value'] : 0);
	}

    $stat = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `ylike`");
    return $stats . "<tr><td>".$result['module_name']."</td><td>".number_format($stat['total'])."</td><td>".number_format($clicks)."</td></tr>";
}

register_filter('tot_sites','ylike_tot_sites');
function ylike_tot_sites($stats) {
	global $db;
    $clicks = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `ylike`");
    return $stats += $clicks['total'];
}

register_filter('module_tables','ylike_module_tables');
function ylike_module_tables($table) {
	return $table.'ylike---yliked(||)';
}

//Admin
register_filter('admin_s_sites','ylike_admin_clicks');
function ylike_admin_clicks($stats) {
	global $db;
	$clicks = $db->QueryFetchArray("SELECT value FROM `web_stats` WHERE `module_id`='ylike'");
	$clicks = ($clicks['value'] > 0 ? $clicks['value'] : 0);
	$today_clicks = $db->QueryFetchArray("SELECT SUM(today_clicks) AS value FROM `user_clicks` WHERE `module`='ylike'");
	$today_clicks = ($today_clicks['value'] > 0 ? $today_clicks['value'] : 0);
	$active = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `ylike`");
	$active = $active['total'];
	$inactive = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `ylike` WHERE `active`!='0'");
	$inactive = $inactive['total'];
	return $stats . '<div class="full-stats">
							<h2 class="center">Youtube Likes</h2>
							<div class="stat circular" data-valueFormat="0,0" data-list=\'[{"title":"Pages","val":'.$active.',"percent":'.round((($active - $inactive)/$active)*100, 0).'},{"title":"Clicks","val":'.$clicks.',"percent":0},{"title":"Today Clicks","val":'.$today_clicks.',"percent":0}]\'></div>
						</div>';
}

register_filter('admin_s_menu','ylike_admin_menu');
function ylike_admin_menu($menu) {
	return $menu . '<li><a href="index.php?x=sites&s=ylike">YouTube Likes</a></li>';
}
?>