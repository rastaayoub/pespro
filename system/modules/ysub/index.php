<?php
register_filter('index_icons','ysub_icon');
function ysub_icon($icons) {
	global $is_online;

	if($is_online){
		$icons .= '<a href="p.php?p=ysub"><img class="exchange_icon" src="img/icons/ysub.png" alt="YSub" /></a>';
	} else {
		$icons .= '<img class="exchange_icon" src="img/icons/ysub.png" alt="YSub" />';
	}
	
	return $icons;
}
            
register_filter('top_menu_earn','ysub_top_menu');
function ysub_top_menu($menu) {
	$selected = (isset($_GET["p"]) && $_GET["p"] == "ysub" ? ' active' : '');
	return $menu . '<div class="ucp_link'.$selected.'"><a href="p.php?p=ysub">Youtube Subscribers</a></div>';
}

register_filter('site_menu','ysub_site_menu');
function ysub_site_menu($menu) {
    $selected = "";
    if(isset($_GET["p"]) && $_GET["p"] == "ysub")
    {
        $selected = 'selected';
    }
    else
    {
        $selected = 'value="ysub"';
    }
	return $menu . '<option '.$selected.'>Youtube Subscribers</a>';
}

register_filter('ysub_info','ysub_info');
function ysub_info($type) {
    if($type == "db")
    {
        return "ysub";
    }
    else if($type == "type")
    {
        return "Youtube Subscribers";
    }
	else if($type == "name")
    {
        return "Youtube Subscribers";
    }
}

register_filter('ysub_dtf','ysub_dtf');
function ysub_dtf($type) {
    return "ysub";
}

register_filter('add_site_select','ysub_add_select');
function ysub_add_select($menu) {
    return $menu . "<option value='ysub'>Youtube Subscribers</option>";
}

register_filter('stats','ysub_stats');
function ysub_stats($stats) {
	global $db;
	$sql = $db->Query("SELECT module_name,value FROM `web_stats` WHERE `module_id`='ysub'");
	if($db->GetNumRows($sql) == 0){
		$clicks = $db->QueryFetchArray("SELECT SUM(`clicks`) AS `clicks` FROM `ysub`");
		$clicks = ($clicks['clicks'] > 0 ? $clicks['clicks'] : 0);
		$db->Query("INSERT INTO `web_stats` (`module_id`,`module_name`,`value`)VALUES('ysub','Youtube Subscribers','".$clicks."')");
	}else{
		$result = $db->FetchArray($sql);
		$clicks = ($result['value'] > 0 ? $result['value'] : 0);
	}

    $stat = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `ysub`");
    return $stats . "<tr><td>".$result['module_name']."</td><td>".number_format($stat['total'])."</td><td>".number_format($clicks)."</td></tr>";
}

register_filter('tot_sites','ysub_tot_sites');
function ysub_tot_sites($stats) {
	global $db;
    $clicks = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `ysub`");
    return $stats += $clicks['total'];
}

register_filter('module_tables','ysub_module_tables');
function ysub_module_tables($table) {
	return $table.'ysub---ysubed(||)';
}

//Admin
register_filter('admin_s_sites','ysub_admin_clicks');
function ysub_admin_clicks($stats) {
	global $db;
	$clicks = $db->QueryFetchArray("SELECT value FROM `web_stats` WHERE `module_id`='ysub'");
	$clicks = ($clicks['value'] > 0 ? $clicks['value'] : 0);
	$today_clicks = $db->QueryFetchArray("SELECT SUM(today_clicks) AS value FROM `user_clicks` WHERE `module`='ysub'");
	$today_clicks = ($today_clicks['value'] > 0 ? $today_clicks['value'] : 0);
	$active = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `ysub`");
	$active = $active['total'];
	$inactive = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `ysub` WHERE `active`!='0'");
	$inactive = $inactive['total'];
	return $stats . '<div class="full-stats">
							<h2 class="center">Youtube Subscribers</h2>
							<div class="stat circular" data-valueFormat="0,0" data-list=\'[{"title":"Pages","val":'.$active.',"percent":'.round((($active - $inactive)/$active)*100, 0).'},{"title":"Clicks","val":'.$clicks.',"percent":0},{"title":"Today Clicks","val":'.$today_clicks.',"percent":0}]\'></div>
						</div>';
}

register_filter('admin_s_menu','ysub_admin_menu');
function ysub_admin_menu($menu) {
	return $menu . '<li><a href="index.php?x=sites&s=ysub">YT Subs</a></li>';
}
?>