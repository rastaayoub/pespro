<?
define('BASEPATH', true);
require_once("../../config.php");
if(!$is_online){
	redirect('../../../index.php');
	exit;
}

$s_host = parse_url($site['site_url']);

if($_GET['cash'] != '' && is_numeric($_GET['cash'])){
	$cash = ($_GET['cash'] < 1 ? 1 : $_GET['cash']);
	$cash = number_format($cash, 2, '.', '');
}else{
	redirect('../../../index.php');
	exit;
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>Redirecting...</title>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<style>body{background: #fff;font: 13px Trebuchet MS, Arial, Helvetica, Sans-Serif;color: #333;line-height: 160%;margin: 0;padding: 0;text-align: center;}h1{font-size: 200%;font-weight: normal}.centerdiv{position: absolute;top: 50%; left: 50%; width: 340px; height: 200px;margin-top: -100px; margin-left: -160px;}</style>
<script type="text/javascript">
	setTimeout('document.payzaform.submit()',1000);
</script>
</head>
<body>
<div class="centerdiv"><h1>Connecting to Payza <img src="<?=$site['site_url']?>/img/go_loader.gif" /></h1></div>
<form name="payzaform" method="post" action="https://secure.payza.com/checkout">
    <input type="hidden" name="ap_merchant" value="<?=$site['payza']?>"/>
    <input type="hidden" name="ap_purchasetype" value="service"/>
    <input type="hidden" name="ap_itemname" value="<?='Add Funds - '.$data['login'].' - '.$s_host['host']?>"/>
    <input type="hidden" name="ap_amount" value="<?=$cash?>"/>
    <input type="hidden" name="ap_currency" value="<?=($site['currency_code'] == '' ? 'USD' : $site['currency_code'])?>"/>
    <input type="hidden" name="ap_quantity" value="1"/>
    <input type="hidden" name="ap_returnurl" value="<?=$site['site_url']?>/bank.php?success"/>
    <input type="hidden" name="ap_cancelurl" value="<?=$site['site_url']?>/bank.php?cancel"/>
	<input type="hidden" name="ap_alerturl" value="<?=$site['site_url']?>/system/payments/payza/ipn.php"/>
    <input type="hidden" name="apc_1" value="<?=($data['id'].'|'.$cash.'|'.VisitorIP())?>"/>
	<input type="hidden" name="ap_ipnversion" value="2"/>
</form>
</body>
</html>