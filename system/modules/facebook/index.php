<?php
if(file_exists(realpath(dirname(__FILE__)).'/db_update.php')){
	include_once(realpath(dirname(__FILE__)).'/db_update.php');
}

register_filter('index_icons','fb_icon');
function fb_icon($icons) {
	global $is_online;

	if($is_online){
		$icons .= '<a href="p.php?p=facebook"><img class="exchange_icon" src="img/icons/facebook.png" alt="Facebook" /></a>';
	} else {
		$icons .= '<img class="exchange_icon" src="img/icons/facebook.png" alt="Facebook" />';
	}
	
	return $icons;
}

register_filter('exchange_menu','fb_top_menu');
function fb_top_menu($menu) {
    $selected = (isset($_GET["p"]) && $_GET["p"] == "facebook" ? ' active' : '');
    return $menu . '<div class="ucp_link'.$selected.'" onclick="openMenu(\'facebookMenu\'); $(this).find(\'span\').toggleClass(\'expanded\'); return false;">Facebook <span class="collapsed"></span></div>
                <div id="facebookMenu" class="subMenu">
                    <a href="p.php?p=facebook">Facebook Page Likes</a>
                    <a href="p.php?p=facebook&t=web">Facebook Website Likes</a>
                    <a href="p.php?p=fb_photo">Facebook Photo Likes</a>
                    <a href="p.php?p=fb_event">Facebook Events</a>
                    <a href="p.php?p=fb_share">Facebook Share</a>
                </div>';
}

register_filter('site_menu','fb_site_menu');
function fb_site_menu($menu) {
    $selected = "";
    if(isset($_GET["p"]) && $_GET["p"] == "facebook")
    {
        $selected = 'selected';
    }
    else
    {
        $selected = 'value="facebook"';
    }
	return $menu . '<option '.$selected.'>Facebook Likes</a>';
}

register_filter('facebook_info','fb_info');
function fb_info($type) {
    if($type == "db")
    {
        return "facebook";
    }
    else if($type == "type")
    {
        return "Facebook";
    }
	else if($type == "name")
    {
        return "Facebook Likes";
    }
}

register_filter('facebook_dtf','facebook_dtf');
function facebook_dtf($type) {
    return "facebook";
}

register_filter('add_site_select','fb_add_select');
function fb_add_select($menu) {
    return $menu . "<option value='facebook'>Facebook Likes</option>";
}

register_filter('stats','fb_stats');
function fb_stats($stats) {
	global $db;
	$sql = $db->Query("SELECT module_name,value FROM `web_stats` WHERE `module_id`='facebook'");
	if($db->GetNumRows($sql) == 0){
		$clicks = $db->QueryFetchArray("SELECT SUM(`clicks`) AS `clicks` FROM `facebook`");
		$clicks = ($clicks['clicks'] > 0 ? $clicks['clicks'] : 0);
		$db->Query("INSERT INTO `web_stats` (`module_id`,`module_name`,`value`)VALUES('facebook','Facebook Likes','".$clicks."')");
	}else{
		$result = $db->FetchArray($sql);
		$clicks = ($result['value'] > 0 ? $result['value'] : 0);
	}

    $stat = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `facebook`");
    return $stats . "<tr><td>".$result['module_name']."</td><td>".number_format($stat['total'])."</td><td>".number_format($clicks)."</td></tr>";
}

register_filter('tot_sites','fb_tot_sites');
function fb_tot_sites($stats) {
	global $db;
    $clicks = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `facebook`");
    return $stats += $clicks['total'];
}

register_filter('module_tables','fb_module_tables');
function fb_module_tables($table) {
	return $table.'facebook---liked(||)';
}

//Admin
register_filter('admin_s_sites','fb_admin_sites');
function fb_admin_sites($stats) {
	global $db;
	$clicks = $db->QueryFetchArray("SELECT value FROM `web_stats` WHERE `module_id`='facebook'");
	$clicks = ($clicks['value'] > 0 ? $clicks['value'] : 0);
	$today_clicks = $db->QueryFetchArray("SELECT SUM(today_clicks) AS value FROM `user_clicks` WHERE `module`='facebook'");
	$today_clicks = ($today_clicks['value'] > 0 ? $today_clicks['value'] : 0);
	$active = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `facebook`");
	$active = $active['total'];
	$inactive = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `facebook` WHERE `active`!='0'");
	$inactive = $inactive['total'];
	return $stats . '<div class="full-stats">
							<h2 class="center">Facebook Likes</h2>
							<div class="stat circular" data-valueFormat="0,0" data-list=\'[{"title":"Pages","val":'.$active.',"percent":'.round((($active - $inactive)/$active)*100, 0).'},{"title":"Clicks","val":'.$clicks.',"percent":0},{"title":"Today Clicks","val":'.$today_clicks.',"percent":0}]\'></div>
						</div>';
}

register_filter('admin_s_menu','fb_admin_menu');
function fb_admin_menu($menu) {
	return $menu . '<li><a href="index.php?x=sites&s=facebook">Facebook Likes</a></li>';
}
?>