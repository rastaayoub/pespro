<?php
if(file_exists(realpath(dirname(__FILE__)).'/db_update.php')){
	include_once(realpath(dirname(__FILE__)).'/db_update.php');
}

register_filter('index_icons','reverbnation_icon');
function reverbnation_icon($icons) {
	global $is_online;

	if($is_online){
		$icons .= '<a href="p.php?p=reverbnation"><img class="exchange_icon" src="img/icons/reverbnation.png" alt="ReverbNation" /></a>';
	} else {
		$icons .= '<img class="exchange_icon" src="img/icons/reverbnation.png" alt="ReverbNation" />';
	}
	
	return $icons;
}

register_filter('exchange_menu','reverbnation_top_menu');
function reverbnation_top_menu($menu) {
	$selected = (isset($_GET["p"]) && $_GET["p"] == "reverbnation" ? ' active' : '');
	return $menu . '<div class="ucp_link'.$selected.'"><a href="p.php?p=reverbnation">Reverbnation Fans</a></div>';
}

register_filter('site_menu','reverbnation_site_menu');
function reverbnation_site_menu($menu) {
    $selected = "";
    if(isset($_GET["p"]) && $_GET["p"] == "reverbnation")
    {
        $selected = 'selected';
    }
    else
    {
        $selected = 'value="reverbnation"';
    }
	return $menu . '<option '.$selected.'>Reverbnation Fans</a>';
}

register_filter('reverbnation_info','reverbnation_info');
function reverbnation_info($type) {
    if($type == "db")
    {
        return "reverbnation";
    }
    else if($type == "type")
    {
        return "Reverbnation Fans";
    }
	else if($type == "name")
    {
        return "Reverbnation Fans";
    }
}

register_filter('reverbnation_dtf','reverbnation_dtf');
function reverbnation_dtf($type) {
    return "reverbnation";
}

register_filter('add_site_select','reverbnation_add_select');
function reverbnation_add_select($menu) {
    return $menu . "<option value='reverbnation'>Reverbnation Fans</option>";
}

register_filter('stats','reverbnation_stats');
function reverbnation_stats($stats) {
	global $db;
	$sql = $db->Query("SELECT module_name,value FROM `web_stats` WHERE `module_id`='reverbnation'");
	if($db->GetNumRows($sql) == 0){
		$clicks = $db->QueryFetchArray("SELECT SUM(`clicks`) AS `clicks` FROM `reverbnation`");
		$clicks = ($clicks['clicks'] > 0 ? $clicks['clicks'] : 0);
		$db->Query("INSERT INTO `web_stats` (`module_id`,`module_name`,`value`)VALUES('reverbnation','Reverbnation Fans','".$clicks."')");
	}else{
		$result = $db->FetchArray($sql);
		$clicks = ($result['value'] > 0 ? $result['value'] : 0);
	}

    $stat = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `reverbnation`");
    return $stats . "<tr><td>".$result['module_name']."</td><td>".number_format($stat['total'])."</td><td>".number_format($clicks)."</td></tr>";
}

register_filter('tot_sites','reverbnation_tot_sites');
function reverbnation_tot_sites($stats) {
	global $db;
    $clicks = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `reverbnation`");
    return $stats += $clicks['total'];
}

register_filter('module_tables','reverbnation_module_tables');
function reverbnation_module_tables($table) {
	return $table.'reverbnation---reverbnation_done(||)';
}

//Admin
register_filter('admin_s_sites','reverbnation_admin_clicks');
function reverbnation_admin_clicks($stats) {
	global $db;
	$clicks = $db->QueryFetchArray("SELECT value FROM `web_stats` WHERE `module_id`='reverbnation'");
	$clicks = ($clicks['value'] > 0 ? $clicks['value'] : 0);
	$today_clicks = $db->QueryFetchArray("SELECT SUM(today_clicks) AS value FROM `user_clicks` WHERE `module`='reverbnation'");
	$today_clicks = ($today_clicks['value'] > 0 ? $today_clicks['value'] : 0);
	$active = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `reverbnation`");
	$active = $active['total'];
	$inactive = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `reverbnation` WHERE `active`!='0'");
	$inactive = $inactive['total'];
	return $stats . '<div class="full-stats">
							<h2 class="center">Reverbnation Fans</h2>
							<div class="stat circular" data-valueFormat="0,0" data-list=\'[{"title":"Pages","val":'.$active.',"percent":'.round((($active - $inactive)/$active)*100, 0).'},{"title":"Clicks","val":'.$clicks.',"percent":0},{"title":"Today Clicks","val":'.$today_clicks.',"percent":0}]\'></div>
						</div>';
}

register_filter('admin_s_menu','reverbnation_admin_menu');
function reverbnation_admin_menu($menu) {
	return $menu . '<li><a href="index.php?x=sites&s=reverbnation">Reverbnation Fans</a></li>';
}
?>