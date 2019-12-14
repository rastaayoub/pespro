<?php
if(file_exists(realpath(dirname(__FILE__)).'/db_update.php')){
	include_once(realpath(dirname(__FILE__)).'/db_update.php');
}

register_filter('site_menu','inst_likes_site_menu');
function inst_likes_site_menu($menu) {
    $selected = "";
    if(isset($_GET["p"]) && $_GET["p"] == "inst_likes")
    {
        $selected = 'selected';
    }
    else
    {
        $selected = 'value="inst_likes"';
    }
	return $menu . '<option '.$selected.'>Instagram Likes</a>';
}

register_filter('top_menu_earn','inst_likes_top_menu');
function inst_likes_top_menu($menu) {
	$selected = (isset($_GET["p"]) && $_GET["p"] == "inst_likes" ? ' active' : '');
	return $menu . '<div class="ucp_link'.$selected.'"><a href="p.php?p=inst_likes">Instagram Likes</a></div>';
}

register_filter('add_site_select','inst_likes_add_select');
function inst_likes_add_select($menu) {
    return $menu . "<option value='inst_likes'>Instagram Likes</option>";
}

register_filter('inst_likes_info','inst_likes_info');
function inst_likes_info($type) {
    if($type == "db")
    {
        return "inst_likes";
    }
    else if($type == "type")
    {
        return "Instagram";
    }
	else if($type == "name")
    {
        return "Instagram Likes";
    }
}

register_filter('inst_likes_dtf','inst_likes_dtf');
function inst_likes_dtf($type) {
    return "inst_likes";
}

register_filter('stats','inst_likes_stats');
function inst_likes_stats($stats) {
	global $db;
	$sql = $db->Query("SELECT module_name,value FROM `web_stats` WHERE `module_id`='inst_likes'");
	if($db->GetNumRows($sql) == 0){
		$clicks = $db->QueryFetchArray("SELECT SUM(`clicks`) AS `clicks` FROM `inst_likes`");
		$clicks = ($clicks['clicks'] > 0 ? $clicks['clicks'] : 0);
		$db->Query("INSERT INTO `web_stats` (`module_id`,`module_name`,`value`)VALUES('inst_likes','Instagram Likes','".$clicks."')");
	}else{
		$result = $db->FetchArray($sql);
		$clicks = ($result['value'] > 0 ? $result['value'] : 0);
	}

    $stat = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `inst_likes`");
    return $stats . "<tr><td>".$result['module_name']."</td><td>".number_format($stat['total'])."</td><td>".number_format($clicks)."</td></tr>";
}

register_filter('tot_clicks','inst_likes_tot_clicks');
function inst_likes_tot_clicks($stats) {
	global $db;
    $clicks = $db->QueryFetchArray("SELECT value FROM `web_stats` WHERE `module_id`='inst_likes'");
    if(empty($clicks['value']) && $clicks['value'] != '0'){
		$clicks = $db->QueryFetchArray("SELECT SUM(`clicks`) AS `value` FROM `inst_likes`");
	}
	return $stats += ($clicks['value'] > 0 ? $clicks['value'] : 0);
}

register_filter('tot_sites','inst_likes_tot_sites');
function inst_likes_tot_sites($stats) {
	global $db;
    $clicks = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `inst_likes`");
    return $stats += $clicks['total'];
}

register_filter('module_tables','inst_likes_module_tables');
function inst_likes_module_tables($table) {
	return $table.'inst_likes---inst_liked(||)';
}

//Admin
register_filter('admin_s_sites','inst_likes_admin_clicks');
function inst_likes_admin_clicks($stats) {
	global $db;
	$clicks = $db->QueryFetchArray("SELECT value FROM `web_stats` WHERE `module_id`='inst_likes'");
	$clicks = ($clicks['value'] > 0 ? $clicks['value'] : 0);
	$today_clicks = $db->QueryFetchArray("SELECT SUM(today_clicks) AS value FROM `user_clicks` WHERE `module`='inst_likes'");
	$today_clicks = ($today_clicks['value'] > 0 ? $today_clicks['value'] : 0);
	$active = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `inst_likes`");
	$active = $active['total'];
	$inactive = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `inst_likes` WHERE `active`!='0'");
	$inactive = $inactive['total'];
	return $stats . '<div class="full-stats">
							<h2 class="center">Instagram Likes</h2>
							<div class="stat circular" data-valueFormat="0,0" data-list=\'[{"title":"Pages","val":'.$active.',"percent":'.round((($active - $inactive)/$active)*100, 0).'},{"title":"Clicks","val":'.$clicks.',"percent":0},{"title":"Today Clicks","val":'.$today_clicks.',"percent":0}]\'></div>
						</div>';
}

register_filter('admin_s_menu','inst_likes_admin_menu');
function inst_likes_admin_menu($menu) {
	return $menu . '<li><a href="index.php?x=sites&s=inst_likes">Instagram Likes</a></li>';
}
?>