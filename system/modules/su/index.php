<?php
register_filter('index_icons','su_icon');
function su_icon($icons) {
	global $is_online;

	if($is_online){
		$icons .= '<a href="p.php?p=su"><img class="exchange_icon" src="img/icons/stumbleupon.png" alt="StumbleUpon" /></a>';
	} else {
		$icons .= '<img class="exchange_icon" src="img/icons/stumbleupon.png" alt="StumbleUpon" />';
	}
	
	return $icons;
}

register_filter('site_menu','su_site_menu');
function su_site_menu($menu) {
    $selected = "";
    if(isset($_GET["p"]) && $_GET["p"] == "su")
    {
        $selected = 'selected';
    }
    else
    {
        $selected = 'value="su"';
    }
	return $menu . '<option '.$selected.'>Stumbleupon Followers</a>';
}

register_filter('top_menu_earn','su_top_menu');
function su_top_menu($menu) {
	$selected = (isset($_GET["p"]) && $_GET["p"] == "su" ? ' active' : '');
	return $menu . '<div class="ucp_link'.$selected.'"><a href="p.php?p=su">Stumbleupon Followers</a></div>';
}

register_filter('add_site_select','su_add_select');
function su_add_select($menu) {
    return $menu . "<option value='su'>Stumbleupon Followers</option>";
}

register_filter('su_info','su_info');
function su_info($type) {
    if($type == "db")
    {
        return "stumble";
    }
    else if($type == "type")
    {
        return "Stumbleupon";
    }
	else if($type == "name")
    {
        return "Stumbleupon Followers";
    }
}

register_filter('stumble_dtf','su_dtf');
function su_dtf($type) {
    return "su";
}

register_filter('stats','su_stats');
function su_stats($stats) {
	global $db;
	$sql = $db->Query("SELECT module_name,value FROM `web_stats` WHERE `module_id`='stumble'");
	if($db->GetNumRows($sql) == 0){
		$clicks = $db->QueryFetchArray("SELECT SUM(`clicks`) AS `clicks` FROM `stumble`");
		$clicks = ($clicks['clicks'] > 0 ? $clicks['clicks'] : 0);
		$db->Query("INSERT INTO `web_stats` (`module_id`,`module_name`,`value`)VALUES('stumble','Stumbleupon Followers','".$clicks."')");
	}else{
		$result = $db->FetchArray($sql);
		$clicks = ($result['value'] > 0 ? $result['value'] : 0);
	}

    $stat = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `stumble`");
    return $stats . "<tr><td>".$result['module_name']."</td><td>".number_format($stat['total'])."</td><td>".number_format($clicks)."</td></tr>";
}

register_filter('tot_sites','su_tot_sites');
function su_tot_sites($stats) {
	global $db;
    $clicks = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `stumble`");
    return $stats += $clicks['total'];
}

register_filter('module_tables','su_module_tables');
function su_module_tables($table) {
	return $table.'stumble---stumbled(||)';
}

//Admin
register_filter('admin_s_sites','su_admin_clicks');
function su_admin_clicks($stats) {
	global $db;
	$clicks = $db->QueryFetchArray("SELECT value FROM `web_stats` WHERE `module_id`='stumble'");
	$clicks = ($clicks['value'] > 0 ? $clicks['value'] : 0);
	$today_clicks = $db->QueryFetchArray("SELECT SUM(today_clicks) AS value FROM `user_clicks` WHERE `module`='stumble'");
	$today_clicks = ($today_clicks['value'] > 0 ? $today_clicks['value'] : 0);
	$active = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `stumble`");
	$active = $active['total'];
	$inactive = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `stumble` WHERE `active`!='0'");
	$inactive = $inactive['total'];
	return $stats . '<div class="full-stats">
							<h2 class="center">Stumbleupon Followers</h2>
							<div class="stat circular" data-valueFormat="0,0" data-list=\'[{"title":"Pages","val":'.$active.',"percent":'.round((($active - $inactive)/$active)*100, 0).'},{"title":"Clicks","val":'.$clicks.',"percent":0},{"title":"Today Clicks","val":'.$today_clicks.',"percent":0}]\'></div>
						</div>';
}

register_filter('admin_s_menu','su_admin_menu');
function su_admin_menu($menu) {
	return $menu . '<li><a href="index.php?x=sites&s=su">Stumbleupon Followers</a></li>';
}
?>