<?php
if(file_exists(realpath(dirname(__FILE__)).'/db_update.php')){
	include_once(realpath(dirname(__FILE__)).'/db_update.php');
}

register_filter('index_icons','surf_icon');
function surf_icon($icons) {
	global $is_online, $site;

	if($is_online){
		$icons .= '<a href="'.($site['surf_type'] == 0 ? 'surf.php' : 'p.php?p=surf').'"><img class="exchange_icon" src="img/icons/feed.png" alt="Hits" /></a>';
	} else {
		$icons .= '<img class="exchange_icon" src="img/icons/feed.png" alt="Hits" />';
	}
	
	return $icons;
}

register_filter('exchange_menu','surf_top_menu');
function surf_top_menu($menu) {
	global $site, $data;
	
	$surfType = ($data['premium'] > 0 && isset($site['vip_surf_type']) ? $site['vip_surf_type'] : $site['surf_type']);
	
	$selected = (isset($_GET["p"]) && $_GET["p"] == "surf" ? ' active' : '');
	if($surfType == 0){
		return $menu . '<div class="ucp_link"><a target="_blank" href="surf.php">Traffic Exchange</a></div>';
	}else{
		return $menu . '<div class="ucp_link'.$selected.'"><a href="p.php?p=surf">Traffic Exchange</a></div>';
	}
}

register_filter('site_menu','surf_site_menu');
function surf_site_menu($menu) {
    $selected = "";
    if(isset($_GET["p"]) && $_GET["p"] == "surf")
    {
        $selected = 'selected';
    }
    else
    {
        $selected = 'value="surf"';
    }
	return $menu . '<option '.$selected.'>Traffic Exchange</a>';
}

register_filter('surf_info','surf_info');
function surf_info($type) {
    if($type == "db")
    {
        return "surf";
    }
    else if($type == "type")
    {
        return "Surf";
    }
	else if($type == "name")
    {
        return "Traffic Exchange";
    }
}

register_filter('surf_dtf','surf_dtf');
function surf_dtf($type) {
    return "surf";
}

register_filter('add_site_select','surf_add_select');
function surf_add_select($menu) {
    return $menu . "<option value='surf'>Traffic Exchange</option>";
}

register_filter('stats','surf_stats');
function surf_stats($stats) {
	global $db;
	$sql = $db->Query("SELECT module_name,value FROM `web_stats` WHERE `module_id`='surf'");
	if($db->GetNumRows($sql) == 0){
		$clicks = $db->QueryFetchArray("SELECT SUM(`clicks`) AS `clicks` FROM `surf`");
		$clicks = ($clicks['clicks'] > 0 ? $clicks['clicks'] : 0);
		$db->Query("INSERT INTO `web_stats` (`module_id`,`module_name`,`value`)VALUES('surf','Traffic Exchange','".$clicks."')");
	}else{
		$result = $db->FetchArray($sql);
		$clicks = ($result['value'] > 0 ? $result['value'] : 0);
	}

    $stat = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `surf`");
    return $stats . "<tr><td>".$result['module_name']."</td><td>".number_format($stat['total'])."</td><td>".number_format($clicks)."</td></tr>";
}

register_filter('tot_sites','surf_tot_sites');
function surf_tot_sites($stats) {
	global $db;
    $clicks = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `surf`");
    return $stats += $clicks['total'];
}

register_filter('module_tables','surf_module_tables');
function surf_module_tables($table) {
	return $table.'surf---surfed(||)';
}

//Admin
register_filter('admin_s_sites','surf_admin_clicks');
function surf_admin_clicks($stats) {
	global $db;
	$clicks = $db->QueryFetchArray("SELECT value FROM `web_stats` WHERE `module_id`='surf'");
	$clicks = ($clicks['value'] > 0 ? $clicks['value'] : 0);
	$today_clicks = $db->QueryFetchArray("SELECT SUM(today_clicks) AS value FROM `user_clicks` WHERE `module`='surf'");
	$today_clicks = ($today_clicks['value'] > 0 ? $today_clicks['value'] : 0);
	$active = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `surf`");
	$active = $active['total'];
	$inactive = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `surf` WHERE `active`!='0'");
	$inactive = $inactive['total'];
	return $stats . '<div class="full-stats">
							<h2 class="center">Traffic Exchange</h2>
							<div class="stat circular" data-valueFormat="0,0" data-list=\'[{"title":"Pages","val":'.$active.',"percent":'.round((($active - $inactive)/$active)*100, 0).'},{"title":"Clicks","val":'.$clicks.',"percent":0},{"title":"Today Clicks","val":'.$today_clicks.',"percent":0}]\'></div>
						</div>';
}

register_filter('admin_s_menu','surf_admin_menu');
function surf_admin_menu($menu) {
	return $menu . '<li><a href="index.php?x=sites&s=surf">Traffic Exchange</a></li>';
}
?>