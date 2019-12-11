<?php
define('BASEPATH', true);
include('system/config.php');
if(isset($_COOKIE['PESAutoLogin'])){
	unset($_COOKIE['PESAutoLogin']); 
	setcookie('PESAutoLogin', '', time(), '/');
}
session_destroy();
header('Location: index.php');
?> 