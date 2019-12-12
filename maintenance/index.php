<?
define('BASEPATH', true);
include('../system/config.php');
if($site['maintenance'] == 0){
	redirect('../index.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head><title><?=$site['site_name']?> - Under Construction</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
		<link rel="stylesheet" type="text/css" href="css/style.css" />
        <!--[if lte IE 8]>
        <link rel="stylesheet" type="text/css" href="css/style.ie.css" />
        <![endif]-->
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
		<script type="text/javascript" src="scripts/bar.js"></script>
		<script type="text/javascript" src="scripts/countdown.js"></script>
        <script type="text/javascript" src="scripts/twitter.js"></script>
		<script type="text/javascript" src="scripts/jquery.toggleval.js"></script>
        <script type="text/javascript" src="scripts/jquery.form.js"></script>
        <script type="text/javascript" src="scripts/jquery.textshadow.js"></script>
		<script> var tw_user = '<?=$site['m_twitter']?>';</script>
        <script type="text/javascript" src="scripts/custom.js"></script>
		<script type="text/javascript">
		// <![CDATA[
			jQuery(document).ready(function(){
			 jQuery("h1, h2, .progress").textShadow();
			})
		 // ]]>
		</script>
	</head>
	<body>
    <div class="layer">		
        <div id="main">
            <div class="company_name"><a href="index.php" title="">Under Construction</a></div>
			<!--[if lte IE 8]>
            	<div class="light_top"></div>
            <![endif]-->
			<div class="main_box">
				<h1>OUR WEBSITE IS COMING SOON</h1>
				<div class="blackbox">
                	<div class="in">
    					<div id="bar">
                        	<div class="progress"><span class="progressBar" id="pb1"><?=$site['m_progress']?>%</span></div>
                            <p>You know, we are currently under construction. Check out our progress. Please sign up to be notified when we launch new website.</p>
						</div>

                		<div class="notify_but">
                        	<a href="#" id="note"><img src="images/notify_but_1.png" width="146" height="40" alt="notify" id="img" /></a>
							<div id="sendform">
								<form action="FormToEmail.php" method="post" name="form_newsletter" id="form_newsletter">
								<input name="email" id="email" type="text" value="Enter your email address..." maxlength="85" class="required email" />
                        		<input name="submit" type="submit" id="submit" value="" class="submit" />
                                <input type="hidden" name="ajax" id="ajax" value="0" />                                                                
								</form>
								<script type="text/javascript">
								  $("input[name='email']").toggleVal();
								</script>
                                <div id="feedback">
          							<p class="error wrong_email">Incorrect email</p>
						        </div>
                                <p id="success">Your email was sent succesfully!</p>
							</div>
						</div>
                	</div>
				</div>
				<div id="twitter"></div>
			</div>
		</div>
	</div>
    <script type="text/javascript" src="scripts/init_form.js"></script>
	</body>
</html>