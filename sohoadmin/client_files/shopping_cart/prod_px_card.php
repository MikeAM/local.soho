<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.5
##      
## Author: 			Mike Johnston [mike.johnston@soholaunch.com]                 
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugzilla.soholaunch.com 
## Release Notes:	sohoadmin/build.dat.php
###############################################################################

##############################################################################
## COPYRIGHT NOTICE                                                     
## Copyright 1999-2003 Soholaunch.com, Inc. and Mike Johnston 

## Copyright 2003-2007 Soholaunch.com, Inc.

## All Rights Reserved.  
##                                                                        
## This script may be used and modified in accordance to the license      
## agreement attached (license.txt) except where expressly noted within      
## commented areas of the code body. This copyright notice and the comments  
## comments above and below must remain intact at all times.  By using this 
## code you agree to indemnify Soholaunch.com, Inc, its coporate agents   
## and affiliates from any liability that might arise from its use.                                                        
##                                                                           
## Selling the code for this program without prior written consent is       
## expressly forbidden and in violation of Domestic and International 
## copyright laws.  		                                           
###############################################################################

// All this garbage is here to screw up hack jobs that may try
// to crack the encryption script.  
//
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! WARNING !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//
// REMEMBER: THIS DOES NOT MAKE THE CREDIT CARD INFORMATION SAFE AND IS A BASIC ENCRYPTION
// ROUTINE THAT IS EASILY BROKEN. SOHOLAUNCH IN NO WAY GUARANTEES THE SECURITY USING THIS 
// SYSTEM. IT IS SIMPLY HERE AS A VISUAL ASSURANCE MEASURE FOR YOUR CUSTOMER.  THE ONLY WAY 
// TO INSURE TOTAL SECURITY IS TO USE A 3RD PARTY CREDIT CARD PROCESSING GATEWAY SYSTEM WITH
// SSL CERTS INSTALLED. THE CODE JUST BELOW THIS PARAGRAPH AND THE JAVASCRIPT CODE SHOULD NOT
// BE MODIFIED.  THE ECHO STATEMENTS ARE THERE TO SIMPLY KEEP "SOURCE CODE VIEWERS" FROM
// TAKING A QUICK PEAK AT THE JAVASCRIPT ROUTINE AND IS DESIGNED TO THROW THEM OFF TRACK.
// HOWEVER, IF THEY DO FIND THE ROUTINE, YOUR CREDIT CARD DATA IS STILL STORED IN YOUR
// DATABASE TABLE IN ENCRYPTED MODE -- MAKE SURE THAT THING IS SECURE!
//

// AGAIN, THIS IS NOTHING MORE THAN VERY, VERY, VERY BASIC ENCRYPTION AND IT IS NOT GUARANTEED
// TO BE EVEN 50% FOOL-PROOF. 
//
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

error_reporting(E_PARSE);



/*---------------------------------------------------------------------------------------------------------*
   ______ __                                 ______                  __
  / ____// /_   ____ _ _____ ____ _ ___     / ____/____ _ _____ ____/ /
 / /    / __ \ / __ `// ___// __ `// _ \   / /    / __ `// ___// __  / 
/ /___ / / / // /_/ // /   / /_/ //  __/  / /___ / /_/ // /   / /_/ /  
\____//_/ /_/ \__,_//_/    \__, / \___/   \____/ \__,_//_/    \__,_/   
                          /____/    
/*---------------------------------------------------------------------------------------------------------*/

if ( $do == "chargeit" ) {
   
   $result = mysql_query("SELECT * FROM cart_dps");
   $DPS = mysql_fetch_array($result);
   
   $CCNAMEFULL = $CC_NAMEF." ".$CC_NAMEL;   
   $DIS_YEAR = substr($CC_YEAR, 2, 4);
	 
   $cmdDoTxnTransaction .= "<Txn>";
   $cmdDoTxnTransaction .= "<PostUsername>".$DPS['DPS_USERNAME']."</PostUsername>"; #Insert your DPS Username here
   $cmdDoTxnTransaction .= "<PostPassword>".$DPS['DPS_PASSWORD']."</PostPassword>"; #Insert your DPS Password here
   $cmdDoTxnTransaction .= "<Amount>".$ORDER_TOTAL."</Amount>";
   $cmdDoTxnTransaction .= "<CardHolderName>".$CCNAMEFULL."</CardHolderName>";
   $cmdDoTxnTransaction .= "<CardNumber>".$CC_NUM."</CardNumber>";
   $cmdDoTxnTransaction .= "<DateExpiry>".$CC_MON.$DIS_YEAR."</DateExpiry>";
   $cmdDoTxnTransaction .= "<InputCurrency>".$dType."</InputCurrency>";
   $cmdDoTxnTransaction .= "<TxnType>Purchase</TxnType>";
   $cmdDoTxnTransaction .= "</Txn>";
   			  
   $URL = "www.paymentexpress.com/pxpost.aspx";

   			 
   $ch = curl_init(); 
   curl_setopt($ch, CURLOPT_URL,"https://".$URL);
   curl_setopt($ch, CURLOPT_POST, 1);
   curl_setopt($ch, CURLOPT_POSTFIELDS,$cmdDoTxnTransaction);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);	
   $result = curl_exec ($ch); 
   curl_close ($ch);
   			   
   parse_xml($result);
}
   function parse_xml($data)
   {
   $xml_parser = xml_parser_create();
   xml_parse_into_struct($xml_parser, $data, $vals, $index);
   xml_parser_free($xml_parser);
   	
   $params = array();
   $level = array();
   foreach ($vals as $xml_elem) {
   if ($xml_elem['type'] == 'open') {
   if (array_key_exists('attributes',$xml_elem)) {
   list($level[$xml_elem['level']],$extra) = array_values($xml_elem['attributes']);
   } 
   else {
   $level[$xml_elem['level']] = $xml_elem['tag'];
   }
   }
   if ($xml_elem['type'] == 'complete') {
   $start_level = 1;
   $php_stmt = '$params';
   			
   while($start_level < $xml_elem['level']) {
   $php_stmt .= '[$level['.$start_level.']]';
   $start_level++;
   }
   $php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
   eval($php_stmt);
   }
   }
   	 
   // Uncommenting this block wi ll display the entire array and show all values returned.
//   echo "<pre>";
//   print_r ($params);
//   echo "</pre>";

   		
   $success = $params[TXN][SUCCESS]; 
   $error = $params[TXN][RESPONSETEXT];
   $CardHolderName	= $params[TXN][$success][CARDHOLDERNAME];
   $TransId	= $params[TXN][$success][TRANSACTIONID];
   $Amount	= $params[TXN][$success][AMOUNT];
   $TxnType = $params[TXN][$success][TXNTYPE]; 
   $CardNumber	= $params[TXN][$success][CARDNUMBER];
   $DateExpiry	= $params[TXN][$success][DATEEXPIRY];
   $CardHolderResponseText	= $params[TXN][$success][CARDHOLDERRESPONSETEXT];
   $CardHolderResponseDescription = $params[TXN][$success][CARDHOLDERRESPONSEDESCRIPTION];
   $MerchantResponseText = $params[TXN][$success][MERCHANTRESPONSETEXT];
   $DPSTxnRef = $params[TXN][$success][DPSTXNREF];
   
   if($success == 0){
      # Declined: show error message a cc form
      echo "<div align=\"center\" style=\"border: 1px solid red; background-color: #F7DFDF;\" class=\"text\"><br>\n";
      echo " ".lang("Unable to complete transaction").". ".lang("Your credit card has not been charged").".<br><br>";
      echo " ".lang("Error").": <b>".$error."</b><br><br>\n";
      echo "</div><br>\n";
      
   } else {
      # Accepted: show final invoice & 'thank you'
      include("pgm-show_invoice.php");
      exit;
   }



   	
   }

?>

<script language="javascript">
var astring=":aAb`BcVCd/eXDfEYg FZhi?jGk|HlmI,nJo@TKpqL.WMrsNt!uvwOx<yPz>0QR12~3S4;^567U89%$#*()-_=+È‚‰Â‡ÁÍÎÓÏ≈…Ê∆ÙˆÚ˚˘÷‹¢£•É·Ì«¸Ò—™∫ø¨Ωº°´ª¶'";

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

   if (document.pay_authorize.CC_NAME.value == "") { check = 1; }
   if (document.pay_authorize.CC_TYPE.value == "") { check = 1; }
   if (document.pay_authorize.CC_NUM.value == "") { check = 1; }
   if (document.pay_authorize.CC_AVS.value == "") { check = 1; }

   if (check != 1) {
      document.pay_authorize.submit();
   } else {
      alert("".lang("YOU DID NOT COMPLETE ALL REQUIRED FIELDS").".\n".lang("PLEASE MAKE CORRECTIONS BEFORE CONTINUING").".");
   }

}
</script>
<style>
.cctext {
   font-family: Courier New, Courier, mono;
   /* font-family: Verdna, Arial, Helvetica, Sans-serif; */
   font-size: 12px;
   color: #2E2E2E;
   letter-spacing: 2px;
   padding-left: 2px;
}
</style>
<form name="pay_dps" method="post" action="pgm-payment_gateway.php">
<input type="hidden" name="DPS_FLAG" value="1">
<input type="hidden" name="PAY_TYPE" value="DPS">
<input type="hidden" name="do" value="chargeit">
<input type="hidden" name="ORDER_NUMBER" value="<? echo $ORDER_NUMBER; ?>">

<!---#####################################################--->
<!---        Required Info for Gateway Function           --->
<!---#####################################################--->

<!---TOTAL_SALE--->
<input type="hidden" name="TOTAL_SALE" value="<? echo $ORDER_TOTAL; ?>">
<!---caddy1--->
<input type="hidden" name="caddy1" value="<? echo $BADDRESS1; ?>">
<!---caddy2--->
<input type="hidden" name="caddy2" value="<? echo $BADDRESS2; ?>">
<!---ccity--->
<input type="hidden" name="ccity" value="<? echo $BCITY; ?>">
<!---cstate--->
<input type="hidden" name="cstate" value="<? echo $BSTATE; ?>">
<!---czip--->
<input type="hidden" name="czip" value="<? echo $BZIPCODE; ?>">
<!---ccountry--->
<input type="hidden" name="ccountry" value="<? echo $BCOUNTRY; ?>">
<!---cphone--->
<input type="hidden" name="cphone" value="<? echo $BPHONE; ?>">
<!---cemail--->
<input type="hidden" name="cemail" value="<? echo $BEMAILADDRESS; ?>">

<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td align="center" valign="top">
   <table border="0" cellspacing="0" cellpadding="5"  style='border: 1px solid black;' align="center" bgcolor="<? echo $OPTIONS[DISPLAY_CARTBG]; ?>">
    <tr>
     <td colspan="2" class="text" align="left" bgcolor="<? echo $OPTIONS[DISPLAY_HEADERBG]; ?>">
      &nbsp;
     </td>
    </tr>
    
    <tr>
     <td colspan="2" class="text" align="left">
      <font color="red">
      <?php echo lang("The total amount of your purchase"); ?>, 
      <?php $dSign = $OPTIONS['PAYMENT_CURRENCY_SIGN']; ?>
      <?php echo $dSign.$ORDER_TOTAL; ?>,
      <?php echo lang("will be charged to your credit card."); ?>
      </font>
     </td>
    </tr>

    <!---CC_NAME--->
    <tr> 
     <td align="left" valign="top" class="text" width="30%">
      <? echo lang("First Name"); ?>:
     </td>
     <td align="left" valign="top" class="text" width="70%">
      <input type="text" name="CC_NAMEF" class="cctext" style='width: 250px;' value="<? echo "$BFIRSTNAME"; ?>">
     </td>
    </tr>
    <tr>    
     <td align="left" valign="top" class="text" width="30%">
      <? echo lang("Last Name"); ?>:
     </td>
     <td align="left" valign="top" class="text" width="70%">
      <input type="text" name="CC_NAMEL" class="cctext" style='width: 250px;' value="<? echo "$BLASTNAME"; ?>">
     </td>
    </tr>
    
    <!--- CC_TYPE --->
    <tr>  
     <td class="text">
      <? echo lang("Credit Card Type"); ?>:
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
      <? echo lang("Credit Card Number"); ?>:
     </td>
     <td class="text">
      <input type="text" name="CC_NUM" class="cctext" style='width: 250px;'>
     </td>
    </tr>
    
    <tr>  
     <td class="text">
      <? echo lang("Credit Card Expiration Date"); ?>:
     </td>
     <td class="text">
      <? echo lang("Month"); ?>: 
      
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
      3-Digit <? echo lang("Security Code"); ?>:
     </td>
     
     <!---CC_AVS--->
     <td align="left" valign="middle">
      <input type="text" name="CC_AVS" class="cctext" style='WIDTH: 50px;'>
     </td>
    </tr>
    <tr>
     <td colspan="2" class="text" align="center" bgcolor="<? echo $OPTIONS[DISPLAY_HEADERBG]; ?>">
      <input type="button" value=" Process Order &gt;&gt;" class="FormLt1" name="button" onClick="document.pay_dps.submit()">
     </td>
    </tr>    
   </table>
   <br><br>
  </td>
 </tr>
 <tr>
  <td align="center" class="text">
   <? echo lang("How to find your security code"); ?>:<br>
   <img src="avs_graphic.gif" width="516" height="130"> 
  </td>
 </tr>
</table>
</form>

<?php
if ( $cartpref->get("dps-logo-display") == "white" ) {
	$dps_image_folderStr = 'logos_white';
} else {
	$dps_image_folderStr = 'logos_transparent';
}
?>

<div id="dps-logo-container">
 <p style="text-align: center;font-size: 125%;font-style: italic;"><?php echo lang('This Payment Processed by...'); ?></p>
 <a href="http://www.paymentexpress.com"><img src="https://www.paymentexpress.com/images/<?php echo $dps_image_folderStr; ?>/paymentexpress.gif" alt="Payment Processor" width="236" height="42" border="0"/></a>
 <p class="uline hand" onclick="toggleid('dps-privacy-policy');"><a href="#dps-privacy"><?php echo lang('Read DPS Privacy Policy'); ?></a></p>
</div>

<a name="dps-privacy"></a>
<div id="dps-privacy-policy" style="display: none;text-align: left;">
 <h2>DPS Privacy Policy</h2>

 <strong>Introduction:</strong>
 <p>Direct Payment Solutions (hereinafter referred to as DPS) is committed to protecting your privacy as an internet user whenever you buy goods or services from a supplier who uses the DPS payment facilities. Your supplier will generally be using our facilities in connection with payment by you using a credit card over the internet. We recognize our responsibility to keep confidential at all times any information about you which we acquire in connection with such a transaction, whether directly from yourself or through the supplier. We protect your personal information on the Internet to an equivalent high standard to that of which you would experience through any channels of the bank, such as bank branches, cash machines or on the telephone. Please note however that our responsibility is necessarily limited to protection by us of information which we obtain. We cannot, of course, ourselves control the use or disclosure by your supplier of any information which they obtain from you.</p>

 <strong>Collection of Information:</strong>
 <p>To enable us to provide secure payment facilities we will typically acquire information which may include your name, your credit card numbers (with the expiry date) and your billing address.</p>

 <strong>Use and disclosure of Information:</strong>
 <p>We use the information which you enter onto the payment page to obtain authorization to the transaction from the issuer of the credit card and our own or the supplier's bank and to process the payment. Some details from your transaction (such as your name and email and delivery address, but never your card details) are made available to the supplier through our customer management system, which allows them to keep track of what transactions have been made. Subject to the above, DPS will not disclose the information to third parties, or make any use of it, without your permission (unless we are required to do so by statute or by an authorized body in order to aid the investigation or prevention of crime).</p>

 <strong>Security:</strong>
 <p>DPS is committed to protecting the security of your data. We use a variety of security technologies and procedures to help protect your personal information from unauthorized access, use or disclosure. For example, we store the personal information you provide in computer servers with limited access that are located in controlled facilities secured by the latest in surveillance and security technology. When we transmit sensitive information (such as a credit card numbers) over the internet, we protect it through the use of encryption, such as the Secure Socket Layer (SSL) protocol. Credit card details stored on site are encrypted using 168bit 3DES encryption. DPS is also certified AIS compliant:</p>

 <p>"Direct Payment Solutions has successfully completed the Visa Account Information Security (AIS) validation process to demonstrate compliance with the Visa Global AIS program. The AIS program defines security standards and industry best practices to protect sensitive data such as credit card account information and other transaction and personal details. Adherence to these standards will minimize the possibility of data compromise."</p>

 <table width="80%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td><img src="https://www.paymentexpress.com/images/policyvisa.jpg" width="111" height="67" alt="visa"></td>
    <td valign="top"><strong>AIS Certified</strong><br>
     Please visit <a href="http://www.visa-asia.com/secured/" target="_blank">http://www.visa-asia.com/secured/</a> for more information.</td>
  </tr>
  <tr>
    <td><img src="https://www.paymentexpress.com/images/policymastercard.jpg" width="111" height="67" alt="mastercard"></td>
    <td valign="top"><strong>SDP Certified</strong><br>
     Please visit <a href="http://www.mastercard.com/sdp/ " target="_blank">http://www.mastercard.com/sdp/</a> for more information about SDP</td>
   </tr>
 </table>

</div>