<?php
if(file_exists(realpath(dirname(__FILE__)).'/db_update.php')){
	include_once(realpath(dirname(__FILE__)).'/db_update.php');
}

register_filter('admin_u_settings','soundcloud_settings');
function soundcloud_settings($settings)
{
	global $site;
    return $settings .'<div class="row">
						<label><strong>Soundcloud Client ID</strong><small>(<a href="http://soundcloud.com/you/apps" target="_blank">click here</a>)</small></label>
						<div><input type="text" name="set2[scf_api]" value="'.$site['scf_api'].'" required="required" /></div>
					</div>';
}

register_filter('index_icons','soundcloud_icon');
function soundcloud_icon($icons) {
	global $is_online;

	if($is_online){
		$icons .= '<a href="p.php?p=soundcloud"><img class="exchange_icon" src="img/icons/soundcloud.png" alt="Soundcloud" /></a>';
	} else {
		$icons .= '<img class="exchange_icon" src="img/icons/soundcloud.png" alt="Soundcloud" />';
	}
	
	return $icons;
}

register_filter('top_menu_earn','soundcloud_top_menu');
function soundcloud_top_menu($menu) {
	$selected = (isset($_GET["p"]) && $_GET["p"] == "soundcloud" ? ' active' : '');
	return $menu . '<div class="ucp_link'.$selected.'"><a href="p.php?p=soundcloud">Soundcloud Followers</a></div>';
}

register_filter('site_menu','soundcloud_site_menu');
function soundcloud_site_menu($menu) {
    $selected = "";
    if(isset($_GET["p"]) && $_GET["p"] == "soundcloud")
    {
        $selected = 'selected';
    }
    else
    {
        $selected = 'value="soundcloud"';
    }
	return $menu . '<option '.$selected.'>Soundcloud Followers</a>';
}

register_filter('soundcloud_info','soundcloud_info');
function soundcloud_info($type) {
    if($type == "db")
    {
        return "scf";
    }
    else if($type == "type")
    {
        return "Soundcloud";
    }
	else if($type == "name")
    {
        return "Soundcloud Followers";
    }
}

register_filter('scf_dtf','soundcloud_dtf');
function soundcloud_dtf($type) {
    return "soundcloud";
}

register_filter('add_site_select','soundcloud_add_select');
function soundcloud_add_select($menu) {
    return $menu . "<option value='soundcloud'>Soundcloud Followers</option>";
}

register_filter('stats','soundcloud_stats');
function soundcloud_stats($stats) {
	global $db;
	$sql = $db->Query("SELECT module_name,value FROM `web_stats` WHERE `module_id`='scf'");
	if($db->GetNumRows($sql) == 0){
		$clicks = $db->QueryFetchArray("SELECT SUM(`clicks`) AS `clicks` FROM `scf`");
		$clicks = ($clicks['clicks'] > 0 ? $clicks['clicks'] : 0);
		$db->Query("INSERT INTO `web_stats` (`module_id`,`module_name`,`value`)VALUES('scf','Soundcloud Followers','".$clicks."')");
	}else{
		$result = $db->FetchArray($sql);
		$clicks = ($result['value'] > 0 ? $result['value'] : 0);
	}

    $stat = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `scf`");
    return $stats . "<tr><td>".$result['module_name']."</td><td>".number_format($stat['total'])."</td><td>".number_format($clicks)."</td></tr>";
}

register_filter('tot_sites','soundcloud_tot_sites');
function soundcloud_tot_sites($stats) {
	global $db;
    $clicks = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `scf`");
    return $stats += $clicks['total'];
}

register_filter('module_tables','soundcloud_module_tables');
function soundcloud_module_tables($table) {
	return $table.'scf---scf_done(||)';
}

//Admin
register_filter('admin_s_sites','soundcloud_admin_clicks');
function soundcloud_admin_clicks($stats) {
	global $db;
	$clicks = $db->QueryFetchArray("SELECT value FROM `web_stats` WHERE `module_id`='scf'");
	$clicks = ($clicks['value'] > 0 ? $clicks['value'] : 0);
	$today_clicks = $db->QueryFetchArray("SELECT SUM(today_clicks) AS value FROM `user_clicks` WHERE `module`='scf'");
	$today_clicks = ($today_clicks['value'] > 0 ? $today_clicks['value'] : 0);
	$active = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `scf`");
	$active = $active['total'];
	$inactive = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `scf` WHERE `active`!='0'");
	$inactive = $inactive['total'];
	return $stats . '<div class="full-stats">
							<h2 class="center">SoundCloud Followers</h2>
							<div class="stat circular" data-valueFormat="0,0" data-list=\'[{"title":"Pages","val":'.$active.',"percent":'.round((($active - $inactive)/$active)*100, 0).'},{"title":"Clicks","val":'.$clicks.',"percent":0},{"title":"Today Clicks","val":'.$today_clicks.',"percent":0}]\'></div>
						</div>';
}

register_filter('admin_s_menu','soundcloud_admin_menu');
function soundcloud_admin_menu($menu) {
	return $menu . '<li><a href="index.php?x=sites&s=soundcloud">Soundcloud</a></li>';
}
?>