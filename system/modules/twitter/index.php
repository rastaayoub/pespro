<?php
register_filter('index_icons','index_icons');
function index_icons($icons) {
	global $is_online;

	if($is_online){
		$icons .= '<a href="p.php?p=twitter"><img class="exchange_icon" src="img/icons/twitter.png" alt="Twitter" /></a>';
	} else {
		$icons .= '<img class="exchange_icon" src="img/icons/twitter.png" alt="Twitter" />';
	}
	
	return $icons;
}

register_filter('top_menu_earn','twitter_top_menu');
function twitter_top_menu($menu) {
	$selected = (isset($_GET["p"]) && $_GET["p"] == "twitter" ? ' active' : '');
	return $menu . '<div class="ucp_link'.$selected.'"><a href="p.php?p=twitter">Twitter</a></div>';
}

register_filter('site_menu','twitter_site_menu');
function twitter_site_menu($menu) {
    $selected = "";
    if(isset($_GET["p"]) && $_GET["p"] == "twitter")
    {
        $selected = 'selected';
    }
    else
    {
        $selected = 'value="twitter"';
    }
	return $menu . '<option '.$selected.'>Twitter Followers</a>';
}

register_filter('twitter_info','twitter_info');
function twitter_info($type) {
    if($type == "db")
    {
        return "twitter";
    }
    else if($type == "type")
    {
        return "Twitter";
    }
	else if($type == "name")
    {
        return "Twitter Followers";
    }
}

register_filter('twitter_dtf','twitter_dtf');
function twitter_dtf($type) {
    return "twitter";
}

register_filter('add_site_select','twitter_add_select');
function twitter_add_select($menu) {
    return $menu . "<option value='twitter'>Twitter Followers</option>";
}

register_filter('stats','twitter_stats');
function twitter_stats($stats) {
	global $db;
	$sql = $db->Query("SELECT module_name,value FROM `web_stats` WHERE `module_id`='twitter'");
	if($db->GetNumRows($sql) == 0){
		$clicks = $db->QueryFetchArray("SELECT SUM(`clicks`) AS `clicks` FROM `twitter`");
		$clicks = ($clicks['clicks'] > 0 ? $clicks['clicks'] : 0);
		$db->Query("INSERT INTO `web_stats` (`module_id`,`module_name`,`value`)VALUES('twitter','Twitter Followers','".$clicks."')");
	}else{
		$result = $db->FetchArray($sql);
		$clicks = ($result['value'] > 0 ? $result['value'] : 0);
	}

    $stat = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `twitter`");
    return $stats . "<tr><td>".$result['module_name']."</td><td>".number_format($stat['total'])."</td><td>".number_format($clicks)."</td></tr>";
}

register_filter('tot_sites','twitter_tot_sites');
function twitter_tot_sites($stats) {
	global $db;
    $clicks = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `twitter`");
    return $stats += $clicks['total'];
}

register_filter('module_tables','twitter_module_tables');
function twitter_module_tables($table) {
	return $table.'twitter---followed(||)';
}

//Admin
register_filter('admin_s_sites','twitter_admin_clicks');
function twitter_admin_clicks($stats) {
	global $db;
	$clicks = $db->QueryFetchArray("SELECT value FROM `web_stats` WHERE `module_id`='twitter'");
	$clicks = ($clicks['value'] > 0 ? $clicks['value'] : 0);
	$today_clicks = $db->QueryFetchArray("SELECT SUM(today_clicks) AS value FROM `user_clicks` WHERE `module`='twitter'");
	$today_clicks = ($today_clicks['value'] > 0 ? $today_clicks['value'] : 0);
	$active = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `twitter`");
	$active = $active['total'];
	$inactive = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `twitter` WHERE `active`!='0'");
	$inactive = $inactive['total'];
	return $stats . '<div class="full-stats">
							<h2 class="center">Twitter Followers</h2>
							<div class="stat circular" data-valueFormat="0,0" data-list=\'[{"title":"Pages","val":'.$active.',"percent":'.round((($active - $inactive)/$active)*100, 0).'},{"title":"Clicks","val":'.$clicks.',"percent":0},{"title":"Today Clicks","val":'.$today_clicks.',"percent":0}]\'></div>
						</div>';
}

register_filter('admin_s_menu','twitter_admin_menu');
function twitter_admin_menu($menu) {
	return $menu . '<li><a href="index.php?x=sites&s=twitter">Twitter</a></li>';
}
?>