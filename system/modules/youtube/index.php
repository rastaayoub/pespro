<?php
register_filter('index_icons','yt_icon');
function yt_icon($icons) {
	global $is_online;

	if($is_online){
		$icons .= '<a href="p.php?p=youtube"><img class="exchange_icon" src="img/icons/youtube.png" alt="Youtube" /></a>';
	} else {
		$icons .= '<img class="exchange_icon" src="img/icons/youtube.png" alt="Youtube" />';
	}
	
	return $icons;
}
            
register_filter('top_menu_earn','yt_top_menu');
function yt_top_menu($menu) {
	$selected = (isset($_GET["p"]) && $_GET["p"] == "youtube" ? ' active' : '');
	return $menu . '<div class="ucp_link'.$selected.'"><a href="p.php?p=youtube">Youtube Views</a></div>';
}

register_filter('site_menu','yt_site_menu');
function yt_site_menu($menu) {
    $selected = "";
    if(isset($_GET["p"]) && $_GET["p"] == "youtube")
    {
        $selected = 'selected';
    }
    else
    {
        $selected = 'value="youtube"';
    }
	return $menu . '<option '.$selected.'>Youtube Views</a>';
}

register_filter('youtube_info','yt_info');
function yt_info($type) {
    if($type == "db")
    {
        return "youtube";
    }
    else if($type == "type")
    {
        return "Youtube";
    }
	else if($type == "name")
    {
        return "Youtube Views";
    }
}

register_filter('youtube_dtf','youtube_dtf');
function youtube_dtf($type) {
    return "youtube";
}

register_filter('add_site_select','yt_add_select');
function yt_add_select($menu) {
    return $menu . "<option value='youtube'>Youtube Views</option>";
}


register_filter('stats','yt_stats');
function yt_stats($stats) {
	global $db;
	$sql = $db->Query("SELECT module_name,value FROM `web_stats` WHERE `module_id`='youtube'");
	if($db->GetNumRows($sql) == 0){
		$clicks = $db->QueryFetchArray("SELECT SUM(`clicks`) AS `clicks` FROM `youtube`");
		$clicks = ($clicks['clicks'] > 0 ? $clicks['clicks'] : 0);
		$db->Query("INSERT INTO `web_stats` (`module_id`,`module_name`,`value`)VALUES('youtube','Youtube Views','".$clicks."')");
	}else{
		$result = $db->FetchArray($sql);
		$clicks = ($result['value'] > 0 ? $result['value'] : 0);
	}

    $stat = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `youtube`");
    return $stats . "<tr><td>".$result['module_name']."</td><td>".number_format($stat['total'])."</td><td>".number_format($clicks)."</td></tr>";
}

register_filter('tot_sites','yt_tot_sites');
function yt_tot_sites($stats) {
	global $db;
    $clicks = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `youtube`");
    return $stats += $clicks['total'];
}

register_filter('module_tables','yt_module_tables');
function yt_module_tables($table) {
	return $table.'youtube---viewed(||)';
}

//Admin
register_filter('admin_s_sites','yt_admin_clicks');
function yt_admin_clicks($stats) {
	global $db;
	$clicks = $db->QueryFetchArray("SELECT value FROM `web_stats` WHERE `module_id`='youtube'");
	$clicks = ($clicks['value'] > 0 ? $clicks['value'] : 0);
	$today_clicks = $db->QueryFetchArray("SELECT SUM(today_clicks) AS value FROM `user_clicks` WHERE `module`='youtube'");
	$today_clicks = ($today_clicks['value'] > 0 ? $today_clicks['value'] : 0);
	$active = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `youtube`");
	$active = $active['total'];
	$inactive = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `youtube` WHERE `active`!='0'");
	$inactive = $inactive['total'];
	return $stats . '<div class="full-stats">
							<h2 class="center">Youtube Views</h2>
							<div class="stat circular" data-valueFormat="0,0" data-list=\'[{"title":"Pages","val":'.$active.',"percent":'.round((($active - $inactive)/$active)*100, 0).'},{"title":"Clicks","val":'.$clicks.',"percent":0},{"title":"Today Clicks","val":'.$today_clicks.',"percent":0}]\'></div>
						</div>';
}

register_filter('admin_s_menu','yt_admin_menu');
function yt_admin_menu($menu) {
	return $menu . '<li><a href="index.php?x=sites&s=youtube">YouTube Views</a></li>';
}
?>