<?php
define('BASEPATH', true);
include('../../config.php');
if(!$is_online){
	redirect('index.php');
	exit;
}
$id = $db->EscapeString($_GET['id']);
$sit = $db->QueryFetchArray("SELECT url FROM `facebook` WHERE `id`='".$id."' AND `type`='0'");
if(empty($sit['url'])){
	exit;
}
?>
<html>
<head><title>Like</title></head>
<body>
<script>
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

 window.fbAsyncInit = function() {
            FB.init({status: true, cookie: true, xfbml: true});
            FB.Event.subscribe('edge.create', function(response) {
				window.close();
            });
 };
</script>
<center><div class="fb-like" data-href="<?=$sit['url']?>" data-send="false" data-layout="box_count" data-width="100" data-show-faces="false"></div></center>
</body>
</html>