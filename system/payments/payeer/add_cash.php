<?php
	define('BASEPATH', true);
	require('../../config.php');
	if(!$is_online){
		redirect('../../../index.php');
	}

	$cash = $site['payeer_minimum'];
	if(isset($_GET['cash']) && is_numeric($_GET['cash'])){
		$cash = ($_GET['cash'] < $site['payeer_minimum'] ? $site['payeer_minimum'] : $_GET['cash']);
		$cash = number_format($cash, 2, '.', '');
	}

	$s_orderid = rand(1000,99999);
	$s_amount = number_format($cash, 2, '.', '');
	$s_curr = ($site['currency_code'] == 'EUR' ? 'EUR' : 'USD');
	$s_desc = base64_encode(base64_encode($data['id'].'|'.$cash.'|'.VisitorIP()));

	$arHash = array(
		$site['payeer_key'],
		$s_orderid,
		$s_amount,
		$s_curr,
		$s_desc,
		$site['payeer_secret']
	);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Redirecting...</title>
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	<style>body{background: #fff;font: 13px Trebuchet MS, Arial, Helvetica, Sans-Serif;color: #333;line-height: 160%;margin: 0;padding: 0;text-align: center;}h1{font-size: 200%;font-weight: normal}.centerdiv{position: absolute;top: 50%; left: 50%; width: 340px; height: 200px;margin-top: -100px; margin-left: -160px;}</style>
	<script type="text/javascript">
		setTimeout('document.payeerform.submit()',1000);
	</script>
</head>
<body>
<div class="centerdiv"><h1>Connecting to Payeer <img src="<?=$site['site_url']?>/img/go_loader.gif" /></h1></div>
 <form name="payeerform" method="GET" action="https://payeer.com/merchant/">
  <input type="hidden" name="m_shop" value="<?=$site['payeer_key']?>">
  <input type="hidden" name="m_orderid" value="<?=$s_orderid?>">
  <input type="hidden" name="m_amount" value="<?=$s_amount?>">
  <input type="hidden" name="m_curr" value="<?=$s_curr?>">
  <input type="hidden" name="m_desc" value="<?=$s_desc?>">
  <input type="hidden" name="m_sign" value="<?=strtoupper(hash('sha256', implode(':', $arHash)))?>">
  <input type="hidden" name="m_process" value="send" />
 </form>
</body>
</html>
