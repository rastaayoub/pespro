<?php
if(file_exists(realpath(dirname(__FILE__)).'/db_update.php')){
	include_once(realpath(dirname(__FILE__)).'/db_update.php');
}

register_filter('top_menu_earn','yfav_top_menu');
function yfav_top_menu($menu) {
	$selected = (isset($_GET["p"]) && $_GET["p"] == "yfav" ? ' active' : '');
	return $menu . '<div class="ucp_link'.$selected.'"><a href="p.php?p=yfav">Youtube Favorites</a></div>';
}

register_filter('site_menu','yfav_site_menu');
function yfav_site_menu($menu) {
    $selected = "";
    if(isset($_GET["p"]) && $_GET["p"] == "yfav")
    {
        $selected = 'selected';
    }
    else
    {
        $selected = 'value="yfav"';
    }
	return $menu . '<option '.$selected.'>Youtube Favorites</a>';
}

register_filter('yfav_info','yfav_info');
function yfav_info($type) {
    if($type == "db")
    {
        return "yfav";
    }
    else if($type == "type")
    {
        return "Youtube Favorites";
    }
	else if($type == "name")
    {
        return "Youtube Favorites";
    }
}

register_filter('yfav_dtf','yfav_dtf');
function yfav_dtf($type) {
    return "yfav";
}

register_filter('add_site_select','yfav_add_select');
function yfav_add_select($menu) {
    return $menu . "<option value='yfav'>Youtube Favorites</option>";
}

register_filter('stats','yfav_stats');
function yfav_stats($stats) {
	global $db;
	$sql = $db->Query("SELECT module_name,value FROM `web_stats` WHERE `module_id`='yfav'");
	if($db->GetNumRows($sql) == 0){
		$clicks = $db->QueryFetchArray("SELECT SUM(`clicks`) AS `clicks` FROM `yfav`");
		$clicks = ($clicks['clicks'] > 0 ? $clicks['clicks'] : 0);
		$db->Query("INSERT INTO `web_stats` (`module_id`,`module_name`,`value`)VALUES('yfav','Youtube Favorites','".$clicks."')");
	}else{
		$result = $db->FetchArray($sql);
		$clicks = ($result['value'] > 0 ? $result['value'] : 0);
	}

    $stat = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `yfav`");
    return $stats . "<tr><td>".$result['module_name']."</td><td>".number_format($stat['total'])."</td><td>".number_format($clicks)."</td></tr>";
}

register_filter('tot_sites','yfav_tot_sites');
function yfav_tot_sites($stats) {
	global $db;
    $clicks = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `yfav`");
    return $stats += $clicks['total'];
}

register_filter('module_tables','yfav_module_tables');
function yfav_module_tables($table) {
	return $table.'yfav---yfaved(||)';
}

//Admin
register_filter('admin_s_sites','yfav_admin_clicks');
function yfav_admin_clicks($stats) {
	global $db;
	$clicks = $db->QueryFetchArray("SELECT value FROM `web_stats` WHERE `module_id`='yfav'");
	$clicks = ($clicks['value'] > 0 ? $clicks['value'] : 0);
	$today_clicks = $db->QueryFetchArray("SELECT SUM(today_clicks) AS value FROM `user_clicks` WHERE `module`='yfav'");
	$today_clicks = ($today_clicks['value'] > 0 ? $today_clicks['value'] : 0);
	$active = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `yfav`");
	$active = $active['total'];
	$inactive = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `yfav` WHERE `active`!='0'");
	$inactive = $inactive['total'];
	return $stats . '<div class="full-stats">
							<h2 class="center">Youtube Favorites</h2>
							<div class="stat circular" data-valueFormat="0,0" data-list=\'[{"title":"Pages","val":'.$active.',"percent":'.round((($active - $inactive)/$active)*100, 0).'},{"title":"Clicks","val":'.$clicks.',"percent":0},{"title":"Today Clicks","val":'.$today_clicks.',"percent":0}]\'></div>
						</div>';
}

register_filter('admin_s_menu','yfav_admin_menu');
function yfav_admin_menu($menu) {
	return $menu . '<li><a href="index.php?x=sites&s=yfav">YouTube Favorites</a></li>';
}
?>