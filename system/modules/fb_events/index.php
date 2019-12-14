<?php
if(file_exists(realpath(dirname(__FILE__)).'/db_update.php')){
	include_once(realpath(dirname(__FILE__)).'/db_update.php');
}

register_filter('site_menu','fbevents_site_menu');
function fbevents_site_menu($menu) {
    $selected = "";
    if(isset($_GET["p"]) && $_GET["p"] == "fb_events")
    {
        $selected = 'selected';
    }
    else
    {
        $selected = 'value="fb_events"';
    }
	return $menu . '<option '.$selected.'>Facebook Events</a>';
}

register_filter('fb_events_info','fb_events_info');
function fb_events_info($type) {
    if($type == "db")
    {
        return "fb_events";
    }
    else if($type == "type")
    {
        return "Facebook Events";
    }
	else if($type == "name")
    {
        return "Facebook Events";
    }
}

register_filter('fb_event_dtf','fb_event_dtf');
function fb_event_dtf($type) {
    return "fb_events";
}

register_filter('add_site_select','fbevents_add_select');
function fbevents_add_select($menu) {
    return $menu . "<option value='fb_events'>Facebook Events</option>";
}

register_filter('stats','fbevents_stats');
function fbevents_stats($stats) {
	global $db;
	$sql = $db->Query("SELECT module_name,value FROM `web_stats` WHERE `module_id`='fb_events'");
	if($db->GetNumRows($sql) == 0){
		$clicks = $db->QueryFetchArray("SELECT SUM(`clicks`) AS `clicks` FROM `fb_event`");
		$clicks = ($clicks['clicks'] > 0 ? $clicks['clicks'] : 0);
		$db->Query("INSERT INTO `web_stats` (`module_id`,`module_name`,`value`)VALUES('fb_events','Facebook Events','".$clicks."')");
	}else{
		$result = $db->FetchArray($sql);
		$clicks = ($result['value'] > 0 ? $result['value'] : 0);
	}

    $stat = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `fb_event`");
    return $stats . "<tr><td>".$result['module_name']."</td><td>".number_format($stat['total'])."</td><td>".number_format($clicks)."</td></tr>";
}

register_filter('tot_sites','fbevents_tot_sites');
function fbevents_tot_sites($stats) {
	global $db;
    $clicks = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `fb_event`");
    return $stats += $clicks['total'];
}

register_filter('module_tables','fbevents_module_tables');
function fbevents_module_tables($table) {
	return $table.'fb_event---fbe_joined(||)';
}

//Admin
register_filter('admin_s_sites','fbevents_admin_sites');
function fbevents_admin_sites($stats) {
	global $db;
	$clicks = $db->QueryFetchArray("SELECT value FROM `web_stats` WHERE `module_id`='fb_events'");
	$clicks = ($clicks['value'] > 0 ? $clicks['value'] : 0);
	$today_clicks = $db->QueryFetchArray("SELECT SUM(today_clicks) AS value FROM `user_clicks` WHERE `module`='fb_events'");
	$today_clicks = ($today_clicks['value'] > 0 ? $today_clicks['value'] : 0);
	$active = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `fb_event`");
	$active = $active['total'];
	$inactive = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `fb_event` WHERE `active`!='0'");
	$inactive = $inactive['total'];
	return $stats . '<div class="full-stats">
							<h2 class="center">FB Events</h2>
							<div class="stat circular" data-valueFormat="0,0" data-list=\'[{"title":"Events","val":'.$active.',"percent":'.round((($active - $inactive)/$active)*100, 0).'},{"title":"Joined","val":'.$clicks.',"percent":0},{"title":"Today Joins","val":'.$today_clicks.',"percent":0}]\'></div>
						</div>';
}

register_filter('admin_s_menu','fbevents_admin_menu');
function fbevents_admin_menu($menu) {
	return $menu . '<li><a href="index.php?x=sites&s=fb_events">Facebook Events</a></li>';
}
?>