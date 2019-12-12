<?php
require("../system/config.php");
$mailTo = $site['site_email'];
$mailFrom = htmlspecialchars($_POST['email']);
$subject = 'Maintenance Subscription';
$message =  'New Email: '.$mailFrom;
mail($mailTo, $subject, $message);
?>