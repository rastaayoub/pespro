<?
define('BASEPATH', true);
require('../../config.php');
if(!$is_online){
	redirect('../../../index.php');
}

$s_host = parse_url($site['site_url']);

if($_GET['cash'] != '' && is_numeric($_GET['cash'])){
	$cash = ($_GET['cash'] < 1 ? 1 : $_GET['cash']);
	$cash = number_format($cash, 2, '.', '');
}else{
	redirect('../../../index.php');
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>Redirecting...</title>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<style>body{background: #fff;font: 13px Trebuchet MS, Arial, Helvetica, Sans-Serif;color: #333;line-height: 160%;margin: 0;padding: 0;text-align: center;}h1{font-size: 200%;font-weight: normal}.centerdiv{position: absolute;top: 50%; left: 50%; width: 340px; height: 200px;margin-top: -100px; margin-left: -160px;}</style>
<script type="text/javascript">
	setTimeout('document.paypalform.submit()',1000);
</script>
</head>
<body>
<div class="centerdiv"><h1>Connecting to Paypal <img src="<?=$site['site_url']?>/img/go_loader.gif" /></h1></div>
<form name="paypalform" action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="<?=$site['paypal']?>">
<input type="hidden" name="item_name" value="<?='Add Funds - '.$data['login'].' - '.$s_host['host']?>">
<input type="hidden" name="custom" value="<?=($data['id'].'|'.$cash.'|'.VisitorIP())?>">
<input type="hidden" name="amount" value="<?=$cash?>">
<input type="hidden" name="currency_code" value="<?=($site['currency_code'] == '' ? 'USD' : $site['currency_code'])?>">
<input type="hidden" name="button_subtype" value="services">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="no_shipping" value="2">
<input type="hidden" name="rm" value="1">
<input type="hidden" name="return" value="<?=$site['site_url']?>/bank.php?success">
<input type="hidden" name="cancel_return" value="<?=$site['site_url']?>/bank.php?cancel">
<input type="hidden" name="notify_url" value="<?=$site['site_url']?>/system/payments/paypal/ipn.php">
</form>
</body>
</html>