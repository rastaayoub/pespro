<?php
if(file_exists(realpath(dirname(__FILE__)).'/db_update.php')){
	include_once(realpath(dirname(__FILE__)).'/db_update.php');
}

register_filter('site_menu','twitter_fav_site_menu');
function twitter_fav_site_menu($menu) {
    $selected = "";
    if(isset($_GET["p"]) && $_GET["p"] == "twitter_favorite")
    {
        $selected = 'selected';
    }
    else
    {
        $selected = 'value="twitter_favorite"';
    }
	return $menu . '<option '.$selected.'>Twitter Favorites</a>';
}

register_filter('twitter_favorite_info','twitter_fav_info');
function twitter_fav_info($type) {
    if($type == "db")
    {
        return "twitter_favorite";
    }
    else if($type == "type")
    {
        return "Twitter";
    }
	else if($type == "name")
    {
        return "Twitter Favorites";
    }
}

register_filter('twitter_favorite_dtf','twitter_fav_dtf');
function twitter_fav_dtf($type) {
    return "twitter_favorite";
}

register_filter('add_site_select','twitter_fav_add_select');
function twitter_fav_add_select($menu) {
    return $menu . "<option value='twitter_favorite'>Twitter Favorite</option>";
}

register_filter('stats','twitter_fav_stats');
function twitter_fav_stats($stats) {
	global $db;
	$sql = $db->Query("SELECT module_name,value FROM `web_stats` WHERE `module_id`='twitter_favorite'");
	if($db->GetNumRows($sql) == 0){
		$clicks = $db->QueryFetchArray("SELECT SUM(`clicks`) AS `clicks` FROM `twitter_favorite`");
		$clicks = ($clicks['clicks'] > 0 ? $clicks['clicks'] : 0);
		$db->Query("INSERT INTO `web_stats` (`module_id`,`module_name`,`value`)VALUES('twitter_favorite','Twitter favorite','".$clicks."')");
	}else{
		$result = $db->FetchArray($sql);
		$clicks = ($result['value'] > 0 ? $result['value'] : 0);
	}

    $stat = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `twitter_favorite`");
    return $stats . "<tr><td>".$result['module_name']."</td><td>".number_format($stat['total'])."</td><td>".number_format($clicks)."</td></tr>";
}

register_filter('tot_sites','twitter_fav_tot_sites');
function twitter_fav_tot_sites($stats) {
	global $db;
    $clicks = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `twitter_favorite`");
    return $stats += $clicks['total'];
}

register_filter('module_tables','twitter_fav_module_tables');
function twitter_fav_module_tables($table) {
	return $table.'twitter_favorite---twitter_favorited(||)';
}

//Admin
register_filter('admin_s_sites','twitter_fav_admin_clicks');
function twitter_fav_admin_clicks($stats) {
	global $db;
	$clicks = $db->QueryFetchArray("SELECT value FROM `web_stats` WHERE `module_id`='twitter_favorite'");
	$clicks = ($clicks['value'] > 0 ? $clicks['value'] : 0);
	$today_clicks = $db->QueryFetchArray("SELECT SUM(today_clicks) AS value FROM `user_clicks` WHERE `module`='twitter_favorite'");
	$today_clicks = ($today_clicks['value'] > 0 ? $today_clicks['value'] : 0);
	$active = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `twitter_favorite`");
	$active = $active['total'];
	$inactive = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `twitter_favorite` WHERE `active`!='0'");
	$inactive = $inactive['total'];
	return $stats . '<div class="full-stats">
							<h2 class="center">Twitter Favorite</h2>
							<div class="stat circular" data-valueFormat="0,0" data-list=\'[{"title":"Pages","val":'.$active.',"percent":'.round((($active - $inactive)/$active)*100, 0).'},{"title":"Clicks","val":'.$clicks.',"percent":0},{"title":"Today Clicks","val":'.$today_clicks.',"percent":0}]\'></div>
						</div>';
}

register_filter('admin_s_menu','twitter_fav_admin_menu');
function twitter_fav_admin_menu($menu) {
	return $menu . '<li><a href="index.php?x=sites&s=twitter_favorite">Twitter Favorite</a></li>';
}
?>