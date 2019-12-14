<?php
// This module was disabled due to latest Google updates

/*if(file_exists(realpath(dirname(__FILE__)).'/db_update.php')){
	include_once(realpath(dirname(__FILE__)).'/db_update.php');
}

register_filter('index_icons','ggl_icon');
function ggl_icon($icons) {
	global $is_online;

	if($is_online){
		$icons .= '<a href="p.php?p=google"><img class="exchange_icon" src="img/icons/google.png" alt="Google" /></a>';
	} else {
		$icons .= '<img class="exchange_icon" src="img/icons/google.png" alt="Google" />';
	}
	
	return $icons;
}
       
register_filter('exchange_menu','ggl_top_menu');
function ggl_top_menu($menu) {
	$selected = (isset($_GET["p"]) && $_GET["p"] == "google" ? ' active' : '');
	return $menu . '<div class="ucp_link'.$selected.'"><a href="p.php?p=google">Google +1</a></div>';
}

register_filter('site_menu','ggl_site_menu');
function ggl_site_menu($menu) {
    $selected = "";
    if(isset($_GET["p"]) && $_GET["p"] == "google")
    {
        $selected = 'selected';
    }
    else
    {
        $selected = 'value="google"';
    }
	return $menu . '<option '.$selected.'>Google</a>';
}

register_filter('google_info','ggl_info');
function ggl_info($type) {
    if($type == "db")
    {
        return "google";
    }
    else if($type == "type")
    {
        return "Google";
    }
	else if($type == "name")
    {
        return "Google +1";
    }
}

register_filter('google_dtf','google_dtf');
function google_dtf($type) {
    return "google";
}

register_filter('add_site_select','ggl_add_select');
function ggl_add_select($menu) {
    return $menu . "<option value='google'>Google</option>";
}

register_filter('stats','ggl_stats');
function ggl_stats($stats) {
	global $db;
	$sql = $db->Query("SELECT module_name,value FROM `web_stats` WHERE `module_id`='google'");
	if($db->GetNumRows($sql) == 0){
		$clicks = $db->QueryFetchArray("SELECT SUM(`clicks`) AS `clicks` FROM `google`");
		$clicks = ($clicks['clicks'] > 0 ? $clicks['clicks'] : 0);
		$db->Query("INSERT INTO `web_stats` (`module_id`,`module_name`,`value`)VALUES('google','Google +1','".$clicks."')");
	}else{
		$result = $db->FetchArray($sql);
		$clicks = ($result['value'] > 0 ? $result['value'] : 0);
	}

    $stat = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `google`");
    return $stats . "<tr><td>".$result['module_name']."</td><td>".number_format($stat['total'])."</td><td>".number_format($clicks)."</td></tr>";
}

register_filter('tot_sites','ggl_tot_sites');
function ggl_tot_sites($stats) {
	global $db;
    $clicks = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `google`");
    return $stats += $clicks['total'];
}

register_filter('module_tables','ggl_module_tables');
function ggl_module_tables($table) {
	return $table.'google---plused(||)';
}

//Admin
register_filter('admin_s_sites','ggl_admin_clicks');
function ggl_admin_clicks($stats) {
	global $db;
	$clicks = $db->QueryFetchArray("SELECT value FROM `web_stats` WHERE `module_id`='google'");
	$clicks = ($clicks['value'] > 0 ? $clicks['value'] : 0);
	$today_clicks = $db->QueryFetchArray("SELECT SUM(today_clicks) AS value FROM `user_clicks` WHERE `module`='google'");
	$today_clicks = ($today_clicks['value'] > 0 ? $today_clicks['value'] : 0);
	$active = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `google`");
	$active = $active['total'];
	$inactive = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `google` WHERE `active`!='0'");
	$inactive = $inactive['total'];
	return $stats . '<div class="full-stats">
							<h2 class="center">Google +1</h2>
							<div class="stat circular" data-valueFormat="0,0" data-list=\'[{"title":"Pages","val":'.$active.',"percent":'.round((($active - $inactive)/$active)*100, 0).'},{"title":"Clicks","val":'.$clicks.',"percent":0},{"title":"Today Clicks","val":'.$today_clicks.',"percent":0}]\'></div>
						</div>';
}

register_filter('admin_s_menu','ggl_admin_menu');
function ggl_admin_menu($menu) {
	return $menu . '<li><a href="index.php?x=sites&s=google">Google</a></li>';
}*/
?>