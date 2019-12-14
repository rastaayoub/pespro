<?php
if(file_exists(realpath(dirname(__FILE__)).'/db_update.php')){
	include_once(realpath(dirname(__FILE__)).'/db_update.php');
}

register_filter('site_menu','fb_share_site_menu');
function fb_share_site_menu($menu) {
    $selected = "";
    if(isset($_GET["p"]) && $_GET["p"] == "fb_share")
    {
        $selected = 'selected';
    }
    else
    {
        $selected = 'value="fb_share"';
    }
	return $menu . '<option '.$selected.'>Facebook Share</a>';
}

register_filter('fb_share_info','fb_share_info');
function fb_share_info($type) {
    if($type == "db")
    {
        return "fb_share";
    }
    else if($type == "type")
    {
        return "Facebook Share";
    }
	else if($type == "name")
    {
        return "Facebook Share";
    }
}

register_filter('fb_share_dtf','fb_share_dtf');
function fb_share_dtf($type) {
    return "fb_share";
}

register_filter('add_site_select','fb_share_add_select');
function fb_share_add_select($menu) {
    return $menu . "<option value='fb_share'>Facebook Share</option>";
}

register_filter('stats','fb_share_stats');
function fb_share_stats($stats) {
	global $db;
	$sql = $db->Query("SELECT module_name,value FROM `web_stats` WHERE `module_id`='fb_share'");
	if($db->GetNumRows($sql) == 0){
		$clicks = $db->QueryFetchArray("SELECT SUM(`clicks`) AS `clicks` FROM `fb_share`");
		$clicks = ($clicks['clicks'] > 0 ? $clicks['clicks'] : 0);
		$db->Query("INSERT INTO `web_stats` (`module_id`,`module_name`,`value`)VALUES('fb_share','Facebook Share','".$clicks."')");
	}else{
		$result = $db->FetchArray($sql);
		$clicks = ($result['value'] > 0 ? $result['value'] : 0);
	}

    $stat = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `fb_share`");
    return $stats . "<tr><td>".$result['module_name']."</td><td>".number_format($stat['total'])."</td><td>".number_format($clicks)."</td></tr>";
}

register_filter('tot_sites','fb_share_tot_sites');
function fb_share_tot_sites($stats) {
	global $db;
    $clicks = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `fb_share`");
    return $stats += $clicks['total'];
}

register_filter('module_tables','fb_share_module_tables');
function fb_share_module_tables($table) {
	return $table.'fb_share---fb_shared(||)';
}

//Admin
register_filter('admin_s_sites','fb_share_admin_sites');
function fb_share_admin_sites($stats) {
	global $db;
	$clicks = $db->QueryFetchArray("SELECT value FROM `web_stats` WHERE `module_id`='fb_share'");
	$clicks = ($clicks['value'] > 0 ? $clicks['value'] : 0);
	$today_clicks = $db->QueryFetchArray("SELECT SUM(today_clicks) AS value FROM `user_clicks` WHERE `module`='fb_share'");
	$today_clicks = ($today_clicks['value'] > 0 ? $today_clicks['value'] : 0);
	$active = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `fb_share`");
	$active = $active['total'];
	$inactive = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `fb_share` WHERE `active`!='0'");
	$inactive = $inactive['total'];
	return $stats . '<div class="full-stats">
							<h2 class="center">Facebook Share</h2>
							<div class="stat circular" data-valueFormat="0,0" data-list=\'[{"title":"Pages","val":'.$active.',"percent":'.round((($active - $inactive)/$active)*100, 0).'},{"title":"Clicks","val":'.$clicks.',"percent":0},{"title":"Today Clicks","val":'.$today_clicks.',"percent":0}]\'></div>
						</div>';
}

register_filter('admin_s_menu','fb_share_admin_menu');
function fb_share_admin_menu($menu) {
	return $menu . '<li><a href="index.php?x=sites&s=fb_share">Facebook Share</a></li>';
}
?>