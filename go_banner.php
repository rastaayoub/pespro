<?php
define('BASEPATH', true);
include('system/config.php');

$id = $db->EscapeString($_GET['go']);
$banners = $db->QueryFetchArray("SELECT site_url FROM `banners` WHERE `id`='".$id."' LIMIT 1");
$db->Query("UPDATE `banners` SET `clicks`=`clicks`+'1' WHERE `id`='".$id."'");
redirect($banners['site_url']);
?>