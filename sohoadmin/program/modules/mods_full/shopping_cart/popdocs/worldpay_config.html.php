<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

require_once("../../../../includes/product_gui.php");

$result = mysql_query("SELECT * FROM cart_options");
$PAYMENT = mysql_fetch_assoc($result);

if(strlen($PAYMENT['PAYMENT_SSL']) > 4){
	$pgateway_link = $PAYMENT['PAYMENT_SSL'];
} else {
	$pgateway_link = "http://".$_SESSION['this_ip'];
}


?>
<html>
<head>
 <title>| ? | - Worldpay Configuration</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="../../../../product_gui.css">
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 10px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.style2 {color: #990000}
.style5 {
	font-size: 11px;
	font-style: italic;
	border-style: solid none none none;
	font-weight: bold;
	color: #565656;
}
.style8 {color: #565656}
.style9 {border-style: solid none none none; font-weight: bold; color: #565656; font-size: 10px;}
.style10 {
	color: #339959;
	font-weight: bold;
}
.style11 {color: #565656}
-->
</style>
</head>
<body>
<table width="450" border=0 align="center" cellpadding="8" cellspacing=0 class="feature_grn" celpadding="0" style="background: #FFFFFF; border: 1px solid #565656;">
 <tr>
  <td colspan="2" class="fgrn_title" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; letter-spacing: 2px;">How to configure WorldPay for your site</td>
 </tr>
 <tr>
  <td colspan="2" class="feature_sub" style="border-style: solid none solid none; border-color: #B5B5B5; background: #DFF6EA;">	First, login to the WorldPay Account Manager via the link provided to
	you by WorldPay (Typically: <a href="https://support.worldpay.com/admin" target="_blank">https://support.worldpay.com/admin</a>)
  </td>
 </tr>
 <tr>
  <td align="right"><b>1.</b></td>
  <td>Select the '<span class="style10">Configure Options</span>' link item at the bottom-right.</td>
 </tr>
 <tr>
  <td align="right" bgcolor="#F8FDFB"><b>2. </b></td>
  <td bgcolor="#F8FDFB">Select the '<span class="style10">Payflow Link Info</span>' option.</td>
 </tr>
 <tr>
  <td align="right"><b>3.</b></td>
  <td>Set the '<span class="style10">Callback URL</span>' to equal:</td>
 </tr>
 <tr>
  <td align="right">&nbsp;</td>
  <td style="padding-top:0px;"><span class="style11"><?php echo $pgateway_link; ?>/shopping/pgm-silent_post.php</span></td>
 </tr>
 <tr>
  <td align="right" bgcolor="#F8FDFB"><b>4. </b></td>
  <td bgcolor="#F8FDFB">Check the '<span class="style10">Callback enabled</span>' box.</td>
 </tr>   
 <tr align="center">
  <td colspan="2" class="feature_grn style5" style="padding-bottom:0px; border-bottom: 0px;">Your WorldPay system is now configured!</td>
 </tr>
 <tr align="center">
  <td colspan="2" class="feature_grn  style9" style="border-top: 0px;">[ <a href="javascript:window.close();" class="del">Close Window</a> ]</td>
 </tr>
</table>
</body>
</html>