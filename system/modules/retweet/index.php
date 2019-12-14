<?php
if(file_exists(realpath(dirname(__FILE__)).'/db_update.php')){
	include_once(realpath(dirname(__FILE__)).'/db_update.php');
}

register_filter('index_icons','rtt_icon');
function rtt_icon($icons) {
	global $is_online;

	if($is_online){
		$icons .= '<a href="p.php?p=retweet"><img class="exchange_icon" src="img/icons/tweet.png" alt="Tweet" /></a>';
	} else {
		$icons .= '<img class="exchange_icon" src="img/icons/tweet.png" alt="Tweet" />';
	}
	
	return $icons;
}

register_filter('site_menu','rtt_site_menu');
function rtt_site_menu($menu) {
    $selected = "";
    if(isset($_GET["p"]) && $_GET["p"] == "retweet")
    {
        $selected = 'selected';
    }
    else
    {
        $selected = 'value="retweet"';
    }
	return $menu . '<option '.$selected.'>Tweet</a>';
}

register_filter('retweet_info','rtt_info');
function rtt_info($type) {
    if($type == "db")
    {
        return "retweet";
    }
    else if($type == "type")
    {
        return "Tweet";
    }
	else if($type == "name")
    {
        return "Twitter Tweet";
    }
}

register_filter('retweet_dtf','retweet_dtf');
function retweet_dtf($type) {
    return "retweet";
}

register_filter('add_site_select','rtt_add_select');
function rtt_add_select($menu) {
    return $menu . "<option value='retweet'>Twitter Tweet</option>";
}

register_filter('stats','rtt_stats');
function rtt_stats($stats) {
	global $db;
	$sql = $db->Query("SELECT module_name,value FROM `web_stats` WHERE `module_id`='retweet'");
	if($db->GetNumRows($sql) == 0){
		$clicks = $db->QueryFetchArray("SELECT SUM(`clicks`) AS `clicks` FROM `retweet`");
		$clicks = ($clicks['clicks'] > 0 ? $clicks['clicks'] : 0);
		$db->Query("INSERT INTO `web_stats` (`module_id`,`module_name`,`value`)VALUES('retweet','Twitter Tweets','".$clicks."')");
	}else{
		$result = $db->FetchArray($sql);
		$clicks = ($result['value'] > 0 ? $result['value'] : 0);
	}

    $stat = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `retweet`");
    return $stats . "<tr><td>".$result['module_name']."</td><td>".number_format($stat['total'])."</td><td>".number_format($clicks)."</td></tr>";
}

register_filter('tot_sites','rtt_tot_sites');
function rtt_tot_sites($stats) {
	global $db;
    $clicks = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `retweet`");
    return $stats += $clicks['total'];
}

register_filter('module_tables','rtt_module_tables');
function rtt_module_tables($table) {
	return $table.'retweet---retweeted(||)';
}

//Admin
register_filter('admin_s_sites','rtt_admin_clicks');
function rtt_admin_clicks($stats) {
	global $db;
	$clicks = $db->QueryFetchArray("SELECT value FROM `web_stats` WHERE `module_id`='retweet'");
	$clicks = ($clicks['value'] > 0 ? $clicks['value'] : 0);
	$today_clicks = $db->QueryFetchArray("SELECT SUM(today_clicks) AS value FROM `user_clicks` WHERE `module`='retweet'");
	$today_clicks = ($today_clicks['value'] > 0 ? $today_clicks['value'] : 0);
	$active = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `retweet`");
	$active = $active['total'];
	$inactive = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `retweet` WHERE `active`!='0'");
	$inactive = $inactive['total'];
	return $stats . '<div class="full-stats">
							<h2 class="center">Twitter Tweets</h2>
							<div class="stat circular" data-valueFormat="0,0" data-list=\'[{"title":"Pages","val":'.$active.',"percent":'.round((($active - $inactive)/$active)*100, 0).'},{"title":"Clicks","val":'.$clicks.',"percent":0},{"title":"Today Clicks","val":'.$today_clicks.',"percent":0}]\'></div>
						</div>';
}

register_filter('admin_s_menu','rtt_admin_menu');
function rtt_admin_menu($menu) {
	return $menu . '<li><a href="index.php?x=sites&s=retweet">Twitter Tweets</a></li>';
}
?>