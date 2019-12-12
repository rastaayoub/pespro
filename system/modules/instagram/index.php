<?php
if(file_exists(realpath(dirname(__FILE__)).'/db_update.php')){
	include_once(realpath(dirname(__FILE__)).'/db_update.php');
}

register_filter('index_icons','instagram_icon');
function instagram_icon($icons) {
	global $is_online;

	if($is_online){
		$icons .= '<a href="p.php?p=instagram"><img class="exchange_icon" src="img/icons/instagram.png" alt="Instagram" /></a>';
	} else {
		$icons .= '<img class="exchange_icon" src="img/icons/instagram.png" alt="Instagram" />';
	}
	
	return $icons;
}

register_filter('site_menu','instagram_site_menu');
function instagram_site_menu($menu) {
    $selected = "";
    if(isset($_GET["p"]) && $_GET["p"] == "instagram")
    {
        $selected = 'selected';
    }
    else
    {
        $selected = 'value="instagram"';
    }
	return $menu . '<option '.$selected.'>Instagram Followers</a>';
}

register_filter('exchange_menu','instagram_top_menu');
function instagram_top_menu($menu) {
    $selected = (isset($_GET["p"]) && $_GET["p"] == "instagram" ? ' active' : '');
    return $menu . '<div class="ucp_link'.$selected.'" onclick="openMenu(\'instagramMenu\'); $(this).find(\'span\').toggleClass(\'expanded\'); return false;">Instagram <span class="collapsed"></span></div>
                <div id="instagramMenu" class="subMenu">
                    <a href="p.php?p=inst_likes">Instagram Likes</a>
                    <a href="p.php?p=instagram">Instagram Followers</a>
                </div>';
}

register_filter('add_site_select','instagram_add_select');
function instagram_add_select($menu) {
    return $menu . "<option value='instagram'>Instagram Followers</option>";
}

register_filter('instagram_info','instagram_info');
function instagram_info($type) {
    if($type == "db")
    {
        return "instagram";
    }
    else if($type == "type")
    {
        return "Instagram";
    }
	else if($type == "name")
    {
        return "Instagram Followers";
    }
}

register_filter('instagram_dtf','instagram_dtf');
function instagram_dtf($type) {
    return "instagram";
}

register_filter('stats','instagram_stats');
function instagram_stats($stats) {
	global $db;
	$sql = $db->Query("SELECT module_name,value FROM `web_stats` WHERE `module_id`='instagram'");
	if($db->GetNumRows($sql) == 0){
		$clicks = $db->QueryFetchArray("SELECT SUM(`clicks`) AS `clicks` FROM `instagram`");
		$clicks = ($clicks['clicks'] > 0 ? $clicks['clicks'] : 0);
		$db->Query("INSERT INTO `web_stats` (`module_id`,`module_name`,`value`)VALUES('instagram','Instagram Followers','".$clicks."')");
	}else{
		$result = $db->FetchArray($sql);
		$clicks = ($result['value'] > 0 ? $result['value'] : 0);
	}

    $stat = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `instagram`");
    return $stats . "<tr><td>".$result['module_name']."</td><td>".number_format($stat['total'])."</td><td>".number_format($clicks)."</td></tr>";
}

register_filter('tot_clicks','instagram_tot_clicks');
function instagram_tot_clicks($stats) {
	global $db;
    $clicks = $db->QueryFetchArray("SELECT value FROM `web_stats` WHERE `module_id`='instagram'");
    if(empty($clicks['value']) && $clicks['value'] != '0'){
		$clicks = $db->QueryFetchArray("SELECT SUM(`clicks`) AS `value` FROM `instagram`");
	}
	return $stats += ($clicks['value'] > 0 ? $clicks['value'] : 0);
}

register_filter('tot_sites','instagram_tot_sites');
function instagram_tot_sites($stats) {
	global $db;
    $clicks = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `instagram`");
    return $stats += $clicks['total'];
}

register_filter('module_tables','instagram_module_tables');
function instagram_module_tables($table) {
	return $table.'instagram---instagram_done(||)';
}

//Admin
register_filter('admin_s_sites','instagram_admin_clicks');
function instagram_admin_clicks($stats) {
	global $db;
	$clicks = $db->QueryFetchArray("SELECT value FROM `web_stats` WHERE `module_id`='instagram'");
	$clicks = ($clicks['value'] > 0 ? $clicks['value'] : 0);
	$today_clicks = $db->QueryFetchArray("SELECT SUM(today_clicks) AS value FROM `user_clicks` WHERE `module`='instagram'");
	$today_clicks = ($today_clicks['value'] > 0 ? $today_clicks['value'] : 0);
	$active = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `instagram`");
	$active = $active['total'];
	$inactive = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `instagram` WHERE `active`!='0'");
	$inactive = $inactive['total'];
	return $stats . '<div class="full-stats">
							<h2 class="center">Instagram Followers</h2>
							<div class="stat circular" data-valueFormat="0,0" data-list=\'[{"title":"Pages","val":'.$active.',"percent":'.round((($active - $inactive)/$active)*100, 0).'},{"title":"Clicks","val":'.$clicks.',"percent":0},{"title":"Today Clicks","val":'.$today_clicks.',"percent":0}]\'></div>
						</div>';
}

register_filter('admin_s_menu','instagram_admin_menu');
function instagram_admin_menu($menu) {
	return $menu . '<li><a href="index.php?x=sites&s=instagram">Instagram Followers</a></li>';
}
?>