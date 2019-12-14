<?php
if(file_exists(realpath(dirname(__FILE__)).'/db_update.php')){
	include_once(realpath(dirname(__FILE__)).'/db_update.php');
}

register_filter('index_icons','myspace_icon');
function myspace_icon($icons) {
	global $is_online;

	if($is_online){
		$icons .= '<a href="p.php?p=myspace"><img class="exchange_icon" src="img/icons/myspace.png" alt="MySpace" /></a>';
	} else {
		$icons .= '<img class="exchange_icon" src="img/icons/myspace.png" alt="MySpace" />';
	}
	
	return $icons;
}

register_filter('top_menu_earn','myspace_top_menu');
function myspace_top_menu($menu) {
	$selected = (isset($_GET["p"]) && $_GET["p"] == "myspace" ? ' active' : '');
	return $menu . '<div class="ucp_link'.$selected.'"><a href="p.php?p=myspace">MySpace Connections</a></div>';
}

register_filter('site_menu','myspace_site_menu');
function myspace_site_menu($menu) {
    $selected = "";
    if(isset($_GET["p"]) && $_GET["p"] == "myspace")
    {
        $selected = 'selected';
    }
    else
    {
        $selected = 'value="myspace"';
    }
	return $menu . '<option '.$selected.'>MySpace Connections</a>';
}

register_filter('myspace_info','myspace_info');
function myspace_info($type) {
    if($type == "db")
    {
        return "myspace";
    }
    else if($type == "type")
    {
        return "MySpace Connections";
    }
	else if($type == "name")
    {
        return "MySpace Connections";
    }
}

register_filter('myspace_dtf','myspace_dtf');
function myspace_dtf($type) {
    return "myspace";
}

register_filter('add_site_select','myspace_add_select');
function myspace_add_select($menu) {
    return $menu . "<option value='myspace'>MySpace Connections</option>";
}

register_filter('stats','myspace_stats');
function myspace_stats($stats) {
	global $db;
	$sql = $db->Query("SELECT module_name,value FROM `web_stats` WHERE `module_id`='myspace'");
	if($db->GetNumRows($sql) == 0){
		$clicks = $db->QueryFetchArray("SELECT SUM(`clicks`) AS `clicks` FROM `myspace`");
		$clicks = ($clicks['clicks'] > 0 ? $clicks['clicks'] : 0);
		$db->Query("INSERT INTO `web_stats` (`module_id`,`module_name`,`value`)VALUES('myspace','MySpace Connections','".$clicks."')");
	}else{
		$result = $db->FetchArray($sql);
		$clicks = ($result['value'] > 0 ? $result['value'] : 0);
	}

    $stat = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `myspace`");
    return $stats . "<tr><td>".$result['module_name']."</td><td>".number_format($stat['total'])."</td><td>".number_format($clicks)."</td></tr>";
}

register_filter('tot_sites','myspace_tot_sites');
function myspace_tot_sites($stats) {
	global $db;
    $clicks = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `myspace`");
    return $stats += $clicks['total'];
}

register_filter('module_tables','myspace_module_tables');
function myspace_module_tables($table) {
	return $table.'myspace---myspaced(||)';
}

//Admin
register_filter('admin_s_sites','myspace_admin_clicks');
function myspace_admin_clicks($stats) {
	global $db;
	$clicks = $db->QueryFetchArray("SELECT value FROM `web_stats` WHERE `module_id`='myspace'");
	$clicks = ($clicks['value'] > 0 ? $clicks['value'] : 0);
	$today_clicks = $db->QueryFetchArray("SELECT SUM(today_clicks) AS value FROM `user_clicks` WHERE `module`='myspace'");
	$today_clicks = ($today_clicks['value'] > 0 ? $today_clicks['value'] : 0);
	$active = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `myspace`");
	$active = $active['total'];
	$inactive = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `myspace` WHERE `active`!='0'");
	$inactive = $inactive['total'];
	return $stats . '<div class="full-stats">
							<h2 class="center">MySpace Connections</h2>
							<div class="stat circular" data-valueFormat="0,0" data-list=\'[{"title":"Pages","val":'.$active.',"percent":'.round((($active - $inactive)/$active)*100, 0).'},{"title":"Clicks","val":'.$clicks.',"percent":0},{"title":"Today Clicks","val":'.$today_clicks.',"percent":0}]\'></div>
						</div>';
}

register_filter('admin_s_menu','myspace_admin_menu');
function myspace_admin_menu($menu) {
	return $menu . '<li><a href="index.php?x=sites&s=myspace">MySpace Connections</a></li>';
}
?>