<?php
if(file_exists(realpath(dirname(__FILE__)).'/db_update.php')){
    include_once(realpath(dirname(__FILE__)).'/db_update.php');
}

register_filter('index_icons','askfm_like_icon');
function askfm_like_icon($icons) {
    global $is_online, $site;

    if($is_online){
        $icons .= '<a href="p.php?p=askfm"><img class="exchange_icon" src="img/icons/askfm_like.png" alt="Ask.fm Likes" /></a>';
    } else {
        $icons .= '<img class="exchange_icon" src="img/icons/askfm_like.png" alt="Ask.fm Likes" />';
    }
    
    return $icons;
}

register_filter('exchange_menu','askfm_like_top_menu');
function askfm_like_top_menu($menu) {
    $selected = (isset($_GET["p"]) && $_GET["p"] == "askfm" ? ' active' : '');
    return $menu . '<div class="ucp_link'.$selected.'"><a href="p.php?p=askfm">Ask.fm Likes</a></div>';
}

register_filter('site_menu','askfm_like_site_menu');
function askfm_like_site_menu($menu) {
    $selected = "";
    if(isset($_GET["p"]) && $_GET["p"] == "askfm")
    {
        $selected = 'selected';
    }
    else
    {
        $selected = 'value="askfm_like"';
    }
    return $menu . '<option '.$selected.'>Ask.fm Likes</a>';
}

register_filter('add_site_select','askfm_like_add_select');
function askfm_like_add_select($menu) {
    if(isset($_POST["type"]) && $_POST["type"] == "askfm")
    {
        return $menu . "<option value='askfm' selected>Ask.fm Likes</option>";
    }
    else
    {
        return $menu . "<option value='askfm'>Ask.fm Likes</option>";
    }
}

register_filter('askfm_like_info','askfm_like_info');
function askfm_like_info($type) {
    if($type == "db")
    {
        return "askfm_like";
    }
    else if($type == "type")
    {
        return "Ask.fm Likes";
    }
    else if($type == "name")
    {
        return "Ask.fm Likes";
    }
}

register_filter('askfm_like_dtf','askfm_like_dtf');
function askfm_like_dtf($type) {
    return "askfm_like";
}

register_filter('stats','askfm_like_stats');
function askfm_like_stats($stats) {
    global $db;
    $sql = $db->Query("SELECT module_name,value FROM `web_stats` WHERE `module_id`='askfm_like'");
    if($db->GetNumRows($sql) == 0){
        $result = $db->FetchArray($sql);
        $sql = $db->Query("SELECT SUM(`clicks`) AS `clicks` FROM `askfm_like`");
        $clicks = $db->FetchArray($sql);
        $clicks = ($clicks['clicks'] > 0 ? $clicks['clicks'] : 0);
        $db->Query("INSERT INTO `web_stats` (`module_id`,`module_name`,`value`)VALUES('askfm_like','Ask.fm Likes','".$clicks."')");
    }else{
        $result = $db->FetchArray($sql);
        $clicks = ($result['value'] > 0 ? $result['value'] : 0);
    }

    $stat = $db->QueryGetNumRows("SELECT id FROM `askfm_like`");
    return $stats . "<tr><td>".$result['module_name']."</td><td>".number_format($stat)."</td><td>".number_format($clicks)."</td></tr>";
}

register_filter('tot_clicks','askfm_like_tot_clicks');
function askfm_like_tot_clicks($stats) {
    global $db;
    $clicks = $db->FetchArray($db->Query("SELECT value FROM `web_stats` WHERE `module_id`='askfm_like'"));
    if(empty($clicks['value']) && $clicks['value'] != '0'){
        $sql = $db->Query("SELECT SUM(`clicks`) AS `value` FROM `askfm_like`");
        $clicks = $db->FetchArray($sql);
    }
    return $stats += ($clicks['value'] > 0 ? $clicks['value'] : 0);
}

register_filter('tot_sites','askfm_like_tot_sites');
function askfm_like_tot_sites($stats) {
    global $db;
    $clicks = $db->QueryGetNumRows("SELECT id FROM `askfm_like`");
    return $stats += $clicks;
}

register_filter('module_tables','askfm_like_module_tables');
function askfm_like_module_tables($table) {
    return $table.'askfm_like---askfm_liked(||)';
}

//Admin
register_filter('admin_s_sites','askfm_like_admin_clicks');
function askfm_like_admin_clicks($stats) {
    global $db;
    $clicks = $db->FetchArray($db->Query("SELECT value FROM `web_stats` WHERE `module_id`='askfm_like'"));
    $clicks = ($clicks['value'] > 0 ? $clicks['value'] : 0);
    $today_clicks = $db->FetchArray($db->Query("SELECT SUM(today_clicks) AS value FROM `user_clicks` WHERE `module`='askfm_like'"));
    $today_clicks = ($today_clicks['value'] > 0 ? $today_clicks['value'] : 0);
    $active = $db->QueryGetNumRows("SELECT id FROM `askfm_like`");
    $inactive = $db->QueryGetNumRows("SELECT id FROM `askfm_like` WHERE `active`!='0'");
    return $stats . '<div class="full-stats">
                            <h2 class="center">Ask.fm Likes</h2>
                            <div class="stat circular" data-valueFormat="0,0" data-list=\'[{"title":"Pages","val":'.$active.',"percent":'.round((($active - $inactive)/$active)*100, 0).'},{"title":"Clicks","val":'.$clicks.',"percent":0},{"title":"Today Clicks","val":'.$today_clicks.',"percent":0}]\'></div>
                        </div>';
}

register_filter('admin_s_menu','askfm_like_admin_menu');
function askfm_like_admin_menu($menu) {
    return $menu . '<li><a href="index.php?x=sites&s=askfm">Ask.fm Likes</a></li>';
}
?>