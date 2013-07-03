<?php

/**
 * @author
 * Web Design Enterprise
 * Phone: 786.234.6361
 * Website: www.webdesignenterprise.com
 * E-mail: info@webdesignenterprise.com
 * 
 * @copyright
 * This work is licensed under the Creative Commons Attribution-Noncommercial-No Derivative Works 3.0 United States License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/legalcode
 *
 * Be aware, violating this license agreement could result in the prosecution and punishment of the infractor.
 *
 * © 2002-2009 Web Design Enterprise Corp. All rights reserved.
 * THERE IS NO WARRANTY FOR THE PROGRAM. THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES PROVIDE THE PROGRAM "AS IS" WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. 
 * THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE PROGRAM IS WITH YOU. SHOULD THE PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF ALL NECESSARY SERVICING, REPAIR OR CORRECTION.
 */
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
error_reporting(E_PARSE);


if ( $do == "chargeit" ) {
	
	# Create a nicely-formatted list of products for description field
   $pp_desc_string = '';
   $product_nameArr = explode(';', $_SESSION['CART_PRODNAME']);
   $product_qtyArr = explode(';', $_SESSION['CART_QTY']);
   $pCount = count($product_nameArr);
   for ( $p = 0; $p < $pCount; $p++ ) {
      if ( $product_qtyArr[$p] > 0 ) {
         $pp_desc_string .= '('.$product_qtyArr[$p].') '.$product_nameArr[$p].' | ';
      }
   }
   $pp_desc_string = rtrim($pp_desc_string, ' |'); // trim trailing |	

	$a = new PaypalPro;
	$a->setParameter("USER", $cartpref->get('paypalpro_username'));
	$a->setParameter("PWD", $cartpref->get('paypalpro_password'));
	$a->setParameter("SIGNATURE", $cartpref->get('paypalpro_api'));
	
	$a->setParameter("AMT", urlencode($TOTAL_SALE));
	$a->setParameter("CURRENCYCODE", $dType);
	
	$a->setParameter("CREDITCARDTYPE", urlencode($CC_TYPE));
	$a->setParameter("ACCT", urlencode($CC_NUM));
	$a->setParameter("EXPDATE", $CC_MON.$CC_YEAR);
	$a->setParameter("CVV2", urlencode(trim($CC_AVS)));
	
	$a->setParameter("FIRSTNAME", htmlspecialchars($CC_NAMEF));
	$a->setParameter("LASTNAME", htmlspecialchars($CC_NAMEL));
	$a->setParameter("STREET", htmlspecialchars($BADDRESS1.$BADDRESS2));
	$a->setParameter("CITY", htmlspecialchars($BCITY));
	$a->setParameter("STATE", htmlspecialchars(substr($BSTATE, 0, 2)));
	$a->setParameter("ZIP", htmlspecialchars($BZIPCODE));
	$a->setParameter("COUNTRYCODE", substr($BCOUNTRY, -2, 2));
	
	$a->setParameter("SHIPTONAME", htmlspecialchars($_SESSION['SFIRSTNAME'])." ".urlencode($_SESSION['SLASTNAME']));
	$a->setParameter("LASTNAME", htmlspecialchars($_SESSION['SLASTNAME']));
	$a->setParameter("SHIPTOSTREET", htmlspecialchars($_SESSION['SADDRESS1'].' '.$_SESSION['SADDRESS2']));
	$a->setParameter("SHIPTOCITY", htmlspecialchars($_SESSION['SCITY']));
	$a->setParameter("SHIPTOSTATE", htmlspecialchars(substr($_SESSION['SSTATE'], 0, 2)));
	$a->setParameter("SHIPTOZIP", htmlspecialchars($_SESSION['SZIPCODE']));
	$a->setParameter("SHIPTOCOUNTRYCODE", substr($BCOUNTRY, -2, 2)); // String starts out like: UNITED STATES - US
	
	$a->setParameter("EMAIL", htmlspecialchars($BEMAILADDRESS));
	$a->setParameter("PHONENUM", htmlspecialchars($BPHONE));	
	
	$a->setParameter("DSGUID", rand(1,9999));
	$a->setParameter("DESC", 'Order '.$ORDER_NUMBER); // Paypal picky about this
//	$a->setParameter("DESC", htmlspecialchars(substr($pp_desc_string, 0, 150))); // Paypal limits to 255
	
	if ( $_SESSION['CUR_USER'] != '' && $_SESSION['PHP_AUTH_USER'] != '' && $cartpref->get('admin-testmode-status') == 'on' ) {
		$a->toSandbox();
	}
	
	function print_pptrans_for_testing($line) {
		global $a;
		echo '<pre>';
		print_r($a->resArray);
		print_r($a);
		echo '</pre>';
		echo '<p>'.__FILE__.':'.$line.'</p>'; exit; 
	}
//	if ( $_SERVER['REMOTE_ADDR'] == '50.79.231.201' ) { print_pptrans_for_testing(__LINE__); }
	
//	if ( $_SERVER['REMOTE_ADDR'] == '50.79.231.201' ) { 
//		if ( $_SERVER['REMOTE_ADDR'] == '50.79.231.201' ) { print_pptrans_for_testing(__LINE__); }
//	}	
	
	switch ($a->process()) {
		case 1:
//		if ( $_SERVER['REMOTE_ADDR'] == '50.79.231.201' ) { print_pptrans_for_testing(__LINE__); }
		include("pgm-show_invoice.php");
      	exit;
		break;

		case 2:
//		if ( $_SERVER['REMOTE_ADDR'] == '50.79.231.201' ) { print_pptrans_for_testing(__LINE__); }
		echo "<div align=\"center\" style=\"border: 1px solid red; background-color: #F7DFDF;\" class=\"text\"><br>\n";
      	echo " Unable to complete transaction. Your credit card has not been charged.<br>";
      	echo " Declined Reason: ".$a->getResponseReasonTex()."<br><br>\n";
      	echo "</div><br>\n";
		break;
			
		case 3:
//		if ( $_SERVER['REMOTE_ADDR'] == '50.79.231.201' ) { print_pptrans_for_testing(__LINE__); }
		echo "<div align=\"center\" style=\"border: 1px solid red; background-color: #F7DFDF;\" class=\"text\"><br>\n";
      	echo " Unable to complete transaction. Your credit card has not been charged.<br>";
      	echo " Error Message: ".$a->getResponseReasonTex()."<br><br>\n";
      	echo "</div><br>\n";
		break;
	}

}

echo "<!-- END SYSTEM / START FRAME PULL -->\n";

for ($x=1;$x<=2500;$x++) {
	echo "\n\n";
}

echo "<!-- END SYSTEM -->\n";

?>

<script language="javascript">
var astring=":aAb`BcVCd/eXDfEYg FZhi?jGk|HlmI,nJo@TKpqL.WMrsNt!uvwOx<yPz>0QR12~3S4;^567U89%$#*()-_=+éâäåàçêëîìÅÉæÆôöòûùÖÜ¢£¥áíÇüñÑªº¿¬œŒ¡«»Š'";

function encrypt(lstring){
   retstr=""
   for ( var i=0; i<lstring.length; i++ ) {
      aNum=astring.indexOf(lstring.substring(i,i+1),0)
      aNum=aNum^25
      retstr=retstr+astring.substring(aNum,aNum+1)
   }
   return retstr
}

function onClick(){
   var check = 0;
   if(document.pay_pal_pro.CC_NAME.value == "") check = 1;
   if(document.pay_pal_pro.CC_TYPE.value == "") check = 1;
   if(document.pay_pal_pro.CC_NUM.value == "") check = 1;
   if(document.pay_pal_pro.CC_AVS.value == "") check = 1;

   if(check != 1) document.pay_pal_pro.submit();
   else alert("YOU DID NOT COMPLETE ALL REQUIRED FIELDS.\nPLEASE MAKE CORRECTIONS BEFORE CONTINUING.");
}
</script>
<style>
.cctext {font-family: Courier New, Courier, mono; font-size: 12px; color: #2E2E2E; letter-spacing: 2px; padding-left: 2px;}
</style>

<form name="pay_pal_pro" method="post" action="pgm-payment_gateway.php">
<input type="hidden" name="PAYPALPRO_FLAG" value="1">
<input type="hidden" name="PAY_TYPE" value="PAYPALPRO">
<input type="hidden" name="do" value="chargeit">
<input type="hidden" name="ORDER_NUMBER" value="<? echo $ORDER_NUMBER; ?>">

<!---#####################################################--->
<!---        Required Info for Gateway Function           --->
<!---#####################################################--->

<!---TOTAL_SALE--->
<input type="hidden" name="TOTAL_SALE" value="<? echo $ORDER_TOTAL; ?>">
<input type="hidden" name="caddy1" value="<? echo $BADDRESS1; ?>">
<input type="hidden" name="caddy2" value="<? echo $BADDRESS2; ?>">
<input type="hidden" name="ccity" value="<? echo $BCITY; ?>">
<input type="hidden" name="cstate" value="<? echo $BSTATE; ?>">
<input type="hidden" name="czip" value="<? echo $BZIPCODE; ?>">
<input type="hidden" name="ccountry" value="<? echo $BCOUNTRY; ?>">
<input type="hidden" name="cphone" value="<? echo $BPHONE; ?>">
<input type="hidden" name="cemail" value="<? echo $BEMAILADDRESS; ?>">

<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td align="center" valign="top">
   <table border="0" cellspacing="0" cellpadding="5"  align="center" bgcolor="<? echo $OPTIONS[DISPLAY_CARTBG]; ?>">
    <tr>
     <td colspan="2" class="text" align="left" bgcolor="<? echo $OPTIONS[DISPLAY_HEADERBG]; ?>">
      &nbsp;
     </td>
    </tr>

    <tr>
     <td colspan="2" class="text" align="left">
      <font color="red">
      <? echo lang("The total amount of your purchase"); ?>,
      <? echo $dSign; ?><? echo $ORDER_TOTAL; ?>,
      <? echo lang("will be charged to your credit card."); ?>
      </font>
     </td>
    </tr>

    <!---CC_NAME--->
    <tr>
     <td align="left" valign="top" class="text" width="30%">
      <? echo $lang["First Name"]; ?>:
     </td>
     <td align="left" valign="top" class="text" width="70%">
      <input type="text" name="CC_NAMEF" class="cctext" style='width: 250px;' value="<? echo "$BFIRSTNAME"; ?>">
     </td>
    </tr>
    <tr>
     <td align="left" valign="top" class="text" width="30%">
      <? echo $lang["Last Name"]; ?>:
     </td>
     <td align="left" valign="top" class="text" width="70%">
      <input type="text" name="CC_NAMEL" class="cctext" style='width: 250px;' value="<? echo "$BLASTNAME"; ?>">
     </td>
    </tr>

    <!--- CC_TYPE --->
    <tr>
     <td class="text">
      <? echo $lang["Credit Card Type"]; ?>:
     </td>
     <td class="text">
      <select name="CC_TYPE" class="cctext" style='width: 75px;'>
	   	<?
	   	$tmp = split(";", $OPTIONS[PAYMENT_CREDIT_CARDS]);
	   	$tmp_cnt = count($tmp);
	
	   	for ($x=0;$x<=$tmp_cnt;$x++) {
	   		if ($tmp[$x] != "") {
	   			echo "<OPTION VALUE=\"$tmp[$x]\">$tmp[$x]</OPTION>\n";
	   		}
	   	}
	   	?>
      </select>
     </td>
    </tr>
    <!---CC_NUM--->
    <tr>
     <td class="text">
      <? echo $lang["Credit Card Number"]; ?>:
     </td>
     <td class="text">
      <input type="text" name="CC_NUM" class="cctext" style='width: 250px;'>
     </td>
    </tr>

    <tr>
     <td class="text">
      <? echo $lang["Credit Card Expiration Date"]; ?>:
     </td>
     <td class="text">
      <? echo $lang["Month"]; ?>:

      <!---CC_MON--->
      <select name="CC_MON" class="cctext">
   	<?
   	$this_month = date("m");
   	for ($x=1;$x<=12;$x++) {
   		$show = $x;
   		if ($x < 10) { $show = "0".$x; }
   		if ($show == $this_month) { $SEL = "SELECTED"; } else { $SEL = ""; }
   		echo "<OPTION VALUE=\"$show\" $SEL>$show</OPTION>\n";
   	}
   	?>
      </select>
      <!---CC_YEAR--->
      &nbsp;&nbsp;Year:
      <select name="CC_YEAR" class="cctext">
   	<?
   	$this_year = date("Y");			// Start from current year and go 10 years forward.
   	$last_year = $this_year + 10;
   	for ($x=$this_year;$x<=$last_year;$x++) {
   		echo "<OPTION VALUE=\"$x\">$x</OPTION>\n";
   	}
   	?>
      </select>
     </td>
    </tr>
    <tr>
     <td align="left" class="text">
      3-Digit <? echo $lang["Security Code"]; ?>:
     </td>

     <!---CC_AVS--->
     <td align="left" valign="middle">
      <input type="text" name="CC_AVS" class="cctext" style='WIDTH: 50px;'>
     </td>
    </tr>
    <tr>
     <td colspan="2" class="text" align="center" bgcolor="<? echo $OPTIONS[DISPLAY_HEADERBG]; ?>">
      <input id="pppro-submit" type="button" value=" Process Order &gt;&gt;" class="FormLt1" name="button" onClick="pppro_submit();">
     </td>
    </tr>
   </table>
   <br><br>
  </td>
 </tr>
 <tr>
  <td align="center" class="text">
   <? echo $lang["How to find your security code"]; ?>:<br>
   <img src="avs_graphic.gif" width="516" height="130">
  </td>
 </tr>
</table>
</form>
<script type="text/javascript">
function pppro_submit() {
	document.getElementById('pppro-submit').value = 'Please wait. Your order is processing...';
	document.getElementById('pppro-submit').disabled = true;
	document.pay_pal_pro.submit();
}
</script>


<?php
//$cartPref;
//echo '<pre>';
//echo $cartpref->get('paypalpro_mode');
//echo '</pre>';

class PaypalPro {

//	var $gateway_url = "https://api-3t.sandbox.paypal.com/nvp";
	var $gateway_url = "https://api-3t.paypal.com/nvp";
	
   var $field_string;
   var $fields = array();

   var $response_string;
   var $response = array();
   var $resArray = array();
   var $reqArray = array();
   	
	function PaypalPro() {
		
		$this->setParameter('METHOD', 'doDirectPayment');
		$this->setParameter('VERSION', '52.0');
		$this->setParameter('PAYMENTACTION', 'Sale');
	}
   
	function setParameter($field, $value) {
		$this->fields["$field"] = $value;
	}
	
	function toSandbox() {
		global $cartpref;
		$this->gateway_url = 'https://api-3t.sandbox.paypal.com/nvp';
		$this->setParameter("USER", $cartpref->get('pp-sandbox-username'));
		$this->setParameter("PWD", $cartpref->get('pp-sandbox-password'));
		$this->setParameter("SIGNATURE", $cartpref->get('pp-sandbox-apisig'));
	}

	function process() {
		global $apiSign, $apiEndPoint, $useProxy, $proxyHost, $proxyPort;
	
		foreach($this->fields as $key => $value) {
			$this->field_string .= "$key=".urlencode($value)."&";
      }
      	
		$ch = curl_init($this->gateway_url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim($this->field_string, "& "));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);

		$this->response_string = urldecode(curl_exec($ch));

		$this->resArray = $this->deformatNVP($this->response_string);
		$this->reqArray = $this->deformatNVP($this->field_string);
		
		if(curl_errno($ch)) {
			$this->response['Error_Reason_Text'] = curl_error($ch);
			return 3;
		}
      	else curl_close($ch);
      	
      	if(strtoupper($this->resArray["ACK"]) != "SUCCESS") {
      		$this->response['Error_Reason_Text'] = "Transaction declined";
		  	return 2;
		} else return 1;
	}

	function deformatNVP($nvpstr) {
		$intial=0;
	 	$nvpArray = array();
		while(strlen($nvpstr)){
			$keypos = strpos($nvpstr,'=');
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);
			$keyval = substr($nvpstr,$intial,$keypos);
			$valval = substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			$nvpArray[urldecode($keyval)] = urldecode( $valval);
			$nvpstr = substr($nvpstr,$valuepos+1,strlen($nvpstr));
	     }
		return $nvpArray;
	}
	
	function getTransID() {
		return $this->resArray["TRANSACTIONID"];
	}
	
	function getResponseReasonTex() {
		return $this->resArray['L_LONGMESSAGE0'];
	}
}

?>