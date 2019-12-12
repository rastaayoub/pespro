<?php
class Browser {
    public static function detect() { 
        $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']); 

        if(preg_match('/opera/', $userAgent)){ 
            $name = 'opera'; 
        }elseif(preg_match('/webkit/', $userAgent)){ 
            $name = 'webkit'; 
        }elseif(preg_match('/msie/', $userAgent)){ 
            $name = 'msie'; 
        }elseif(preg_match('/mozilla/', $userAgent) && !preg_match('/compatible/', $userAgent)){ 
            $name = 'mozilla'; 
        }else{ 
            $name = 'unknown'; 
        } 

        return $name;
    } 
}

if(isset($_GET['u']) && !empty($_GET['u'])){
	$url = base64_decode($_GET['u']);
	$browser = Browser::detect(); 
	if($browser == 'webkit'){
		echo '<html><head><title>Redirecting...</title><meta http-equiv="refresh" content="0.3; URL='.$url.'"></head></html>';
		exit(0);
	}elseif($browser == 'opera'){
		echo "<script type=\"text/javascript\"> parent.location = 'data:text/html,<html><meta http-equiv=\"refresh\" content=\"0; url=\'".$url."\'\"></html>'; </script><noscript><meta http-equiv=\"refresh\" content=\"0; url=".$url."\"></noscript>";
		exit(0);
	}else{
		echo '<html><head><title>Redirecting...</title><meta http-equiv="refresh" content="0.3; URL='.$url.'"></head></html>';
		exit(0);
	}
}else{
	header("location: index.php");
}
?>