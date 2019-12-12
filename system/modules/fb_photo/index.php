<?php
if(file_exists(realpath(dirname(__FILE__)).'/db_update.php')){
	include_once(realpath(dirname(__FILE__)).'/db_update.php');
}

register_filter('site_menu','fbp_site_menu');
function fbp_site_menu($menu) {
    $selected = "";
    if(isset($_GET["p"]) && $_GET["p"] == "fb_photo")
    {
        $selected = 'selected';
    }
    else
    {
        $selected = 'value="fb_photo"';
    }
	return $menu . '<option '.$selected.'>Facebook Photo Likes</a>';
}

register_filter('fb_photo_info','fbp_info');
function fbp_info($type) {
    if($type == "db")
    {
        return "fb_photo";
    }
    else if($type == "type")
    {
        return "Facebook Photo Likes";
    }
	else if($type == "name")
    {
        return "FB Photo Likes";
    }
}

register_filter('fb_photo_dtf','fb_photo_dtf');
function fb_photo_dtf($type) {
    return "fb_photo";
}

register_filter('add_site_select','fbp_add_select');
function fbp_add_select($menu) {
    return $menu . "<option value='fb_photo'>Facebook Photo Likes</option>";
}

register_filter('stats','fbp_stats');
function fbp_stats($stats) {
	global $db;
	$sql = $db->Query("SELECT module_name,value FROM `web_stats` WHERE `module_id`='fb_photo'");
	if($db->GetNumRows($sql) == 0){
		$clicks = $db->QueryFetchArray("SELECT SUM(`clicks`) AS `clicks` FROM `fb_photo`");
		$clicks = ($clicks['clicks'] > 0 ? $clicks['clicks'] : 0);
		$db->Query("INSERT INTO `web_stats` (`module_id`,`module_name`,`value`)VALUES('fb_photo','Facebook Photo Likes','".$clicks."')");
	}else{
		$result = $db->FetchArray($sql);
		$clicks = ($result['value'] > 0 ? $result['value'] : 0);
	}

    $stat = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `fb_photo`");
    return $stats . "<tr><td>".$result['module_name']."</td><td>".number_format($stat['total'])."</td><td>".number_format($clicks)."</td></tr>";
}

register_filter('tot_sites','fbp_tot_sites');
function fbp_tot_sites($stats) {
	global $db;
    $clicks = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `fb_photo`");
    return $stats += $clicks['total'];
}

register_filter('module_tables','fbp_module_tables');
function fbp_module_tables($table) {
	return $table.'fb_photo---fbp_liked(||)';
}

//Admin
register_filter('admin_s_sites','fbp_admin_sites');
function fbp_admin_sites($stats) {
	global $db;
	$clicks = $db->QueryFetchArray("SELECT value FROM `web_stats` WHERE `module_id`='fb_photo'");
	$clicks = ($clicks['value'] > 0 ? $clicks['value'] : 0);
	$today_clicks = $db->QueryFetchArray("SELECT SUM(today_clicks) AS value FROM `user_clicks` WHERE `module`='fb_photo'");
	$today_clicks = ($today_clicks['value'] > 0 ? $today_clicks['value'] : 0);
	$active = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `fb_photo`");
	$active = $active['total'];
	$inactive = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `fb_photo` WHERE `active`!='0'");
	$inactive = $inactive['total'];
	return $stats . '<div class="full-stats">
							<h2 class="center">FB Photo Likes</h2>
							<div class="stat circular" data-valueFormat="0,0" data-list=\'[{"title":"Pages","val":'.$active.',"percent":'.round((($active - $inactive)/$active)*100, 0).'},{"title":"Clicks","val":'.$clicks.',"percent":0},{"title":"Today Clicks","val":'.$today_clicks.',"percent":0}]\'></div>
						</div>';
}

register_filter('admin_s_menu','fbp_admin_menu');
function fbp_admin_menu($menu) {
	return $menu . '<li><a href="index.php?x=sites&s=fb_photo">Facebook Photo</a></li>';
}
?>