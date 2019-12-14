<?php
register_filter('index_icons','lkd_icon');
function lkd_icon($icons) {
	global $is_online;

	if($is_online){
		$icons .= '<a href="p.php?p=linkedin"><img class="exchange_icon" src="img/icons/linkedin.png" alt="Linkedin" /></a>';
	} else {
		$icons .= '<img class="exchange_icon" src="img/icons/linkedin.png" alt="Linkedin" />';
	}
	
	return $icons;
}

register_filter('top_menu_earn','lkd_top_menu');
function lkd_top_menu($menu) {
	$selected = (isset($_GET["p"]) && $_GET["p"] == "linkedin" ? ' active' : '');
	return $menu . '<div class="ucp_link'.$selected.'"><a href="p.php?p=linkedin">LinkedIn Share</a></div>';
}

register_filter('site_menu','lkd_site_menu');
function lkd_site_menu($menu) {
    $selected = "";
    if(isset($_GET["p"]) && $_GET["p"] == "linkedin")
    {
        $selected = 'selected';
    }
    else
    {
        $selected = 'value="linkedin"';
    }
	return $menu . '<option '.$selected.'>LinkedIn Share</a>';
}

register_filter('linkedin_info','lkd_info');
function lkd_info($type) {
    if($type == "db")
    {
        return "linkedin";
    }
    else if($type == "type")
    {
        return "Linkedin";
    }
	else if($type == "name")
    {
        return "Linkedin Share";
    }
}

register_filter('linkedin_dtf','linkedin_dtf');
function linkedin_dtf($type) {
    return "linkedin";
}

register_filter('add_site_select','lkd_add_select');
function lkd_add_select($menu) {
    return $menu . "<option value='linkedin'>LinkedIn Share</option>";
}

register_filter('stats','lkd_stats');
function lkd_stats($stats) {
	global $db;
	$sql = $db->Query("SELECT module_name,value FROM `web_stats` WHERE `module_id`='linkedin'");
	if($db->GetNumRows($sql) == 0){
		$clicks = $db->QueryFetchArray("SELECT SUM(`clicks`) AS `clicks` FROM `linkedin`");
		$clicks = ($clicks['clicks'] > 0 ? $clicks['clicks'] : 0);
		$db->Query("INSERT INTO `web_stats` (`module_id`,`module_name`,`value`)VALUES('linkedin','LinkedIn Shares','".$clicks."')");
	}else{
		$result = $db->FetchArray($sql);
		$clicks = ($result['value'] > 0 ? $result['value'] : 0);
	}

    $stat = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `linkedin`");
    return $stats . "<tr><td>".$result['module_name']."</td><td>".number_format($stat['total'])."</td><td>".number_format($clicks)."</td></tr>";
}

register_filter('tot_sites','lkd_tot_sites');
function lkd_tot_sites($stats) {
	global $db;
    $clicks = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `linkedin`");
    return $stats += $clicks['total'];
}

register_filter('module_tables','lkd_module_tables');
function lkd_module_tables($table) {
	return $table.'linkedin---linked_done(||)';
}

//Admin
register_filter('admin_s_sites','lkd_admin_clicks');
function lkd_admin_clicks($stats) {
	global $db;
	$clicks = $db->QueryFetchArray("SELECT value FROM `web_stats` WHERE `module_id`='linkedin'");
	$clicks = ($clicks['value'] > 0 ? $clicks['value'] : 0);
	$today_clicks = $db->QueryFetchArray("SELECT SUM(today_clicks) AS value FROM `user_clicks` WHERE `module`='linkedin'");
	$today_clicks = ($today_clicks['value'] > 0 ? $today_clicks['value'] : 0);
	$active = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `linkedin`");
	$active = $active['total'];
	$inactive = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `linkedin` WHERE `active`!='0'");
	$inactive = $inactive['total'];
	return $stats . '<div class="full-stats">
							<h2 class="center">LinkedIn Share</h2>
							<div class="stat circular" data-valueFormat="0,0" data-list=\'[{"title":"Pages","val":'.$active.',"percent":'.round((($active - $inactive)/$active)*100, 0).'},{"title":"Clicks","val":'.$clicks.',"percent":0},{"title":"Today Clicks","val":'.$today_clicks.',"percent":0}]\'></div>
						</div>';
}

register_filter('admin_s_menu','lkd_admin_menu');
function lkd_admin_menu($menu) {
	return $menu . '<li><a href="index.php?x=sites&s=linkedin">LinkedIn Share</a></li>';
}
?>