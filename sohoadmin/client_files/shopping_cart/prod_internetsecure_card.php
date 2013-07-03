<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.5
##
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugzilla.soholaunch.com
## Release Notes:	sohoadmin/build.dat.php
###############################################################################

##############################################################################
## COPYRIGHT NOTICE
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
   $TRAN_TYPE = 'AUTH_CAPTURE';
   # Target url and script
   
//   $scHost = "secure.internetsecure.com";
   $scHost = "anet.internetsecure.com";
   $scPath = "/process.cgi";

   # Data to pass to gateway
   $EXPDATE = "".$CC_MON."/".$CC_YEAR."";
   $scData = array();
   $scData['x_version'] = "3.1";
   $scData['x_delim_data'] = "TRUE";
   $scData['x_test_request'] = "FALSE";
   $scData['x_relay_response'] = "FALSE";
   $scData['x_login'] = $IS_acctkey;
   $scData['x_tran_key'] = '123456';

   $scData['x_amount'] = $TOTAL_SALE;
   $scData['x_card_num'] = trim($CC_NUM);
   $scData['x_exp_date'] = $EXPDATE;
   $scData['x_card_code'] = trim($CC_AVS);
   $scData['x_type'] = $TRAN_TYPE;

   $scData['x_cust_id'] = $_SESSION['ORDER_NUMBER'];
   $scData['x_invoice_num'] = $_SESSION['ORDER_NUMBER'];
   $scData['x_delim_char'] = '|';
   
   //echo testArray($_SESSION);
   #added by Cameron Allen to pass IP address to authorize for security
   $scData['x_customer_ip'] = $_SERVER['REMOTE_ADDR'];

   $scData['x_ship_to_first_name'] = $_SESSION['SFIRSTNAME'];
   $scData['x_ship_to_last_name'] = $_SESSION['SLASTNAME'];
   $scData['x_ship_to_address'] = $_SESSION['SADDRESS1']." ".$_SESSION['SADDRESS2'];
   $scData['x_ship_to_city'] = $_SESSION['SCITY'];
   $scData['x_ship_to_state'] = $_SESSION['SSTATE'];
   $scData['x_ship_to_zip'] = $_SESSION['SZIPCODE'];
   $scData['x_ship_to_country'] = $_SESSION['SCOUNTRY'];
   $scData['x_ship_to_company'] = $_SESSION['BCOMPANY'];

   $scData['x_first_name'] = $CC_NAMEF;
   $scData['x_last_name'] = $CC_NAMEL;

   $scData['x_phone'] = $BPHONE;
   $scData['x_email'] = $BEMAILADDRESS;

   $scData['x_address'] = $BADDRESS1;
   $scData['x_city'] = $BCITY;
   $scData['x_state'] = $BSTATE;
   $scData['x_zip'] = $BZIPCODE;
   $scData['x_country'] = $BCOUNTRY;

   // Include socket connection class and instantiate object
   //::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
   $fsock_class = $_SESSION['docroot_path']."/sohoadmin/program/includes/remote_actions/class-fsockit.php";
   //echo "$fsock_class"; exit;
   if ( !include($fsock_class) ) {
      echo lang("Error").": ".lang("Could not include socket class")."!";
      exit;
   }

   # New socket connection object
   $scSocket = new fsockit($scHost, $scPath, $scData);

   // Connect and grab result array
   $scRez = $scSocket->curlput("https");

//   echo phpinfo();
//   echo $scRez['raw']; exit;

   # Split response into line-by-line array
//   $tok = strtok($scRez['raw'], ",");
//   while( !$tok == FALSE ){
//      $scResult[] = $tok;
//      $tok = strtok(",");
//   }

   $tok = strtok($scRez['raw'], "|");
   while( !$tok == FALSE ){
      $scResult[] = $tok;
      $tok = strtok("|");
   }

//   # Display formatted response for testing
//   foreach ( $scResult as $line=>$val ) {
//      echo $val."<br>";
//   }
////   exit;

   # Was transaction declined?
   if ( strtoupper($scResult[0]) != "1" ) {

      # Declined: show error message a cc form
      echo "<div align=\"center\" style=\"border: 1px solid red; background-color: #F7DFDF;\" class=\"text\"><br>\n";
      echo " ".lang("Unable to complete transaction").". ".lang("Your credit card has not been charged").".<br>";
      echo " Error ".$scResult[2].": ".$scResult[3]."<br><br>\n";
     // echo " ".$AUTHORIZE['AN_ACCTID']." | ".$AUTHORIZE['AN_ACCTKEY']."<br><br>";
      echo "</div><br>\n";

   } else {

      # Accepted: show final invoice & 'thank you'
      //echo "Accepted:<hr>";
      include("pgm-show_invoice.php");
      exit;
   }

}



echo "<!-- END SYSTEM / START FRAME PULL -->\n";

for ($x=1;$x<=2500;$x++) {
	echo "\n\n";
}

echo "<!-- END SYSTEM -->\n";

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

   if (document.pay_internetsecure.CC_NAME.value == "") { check = 1; }
   if (document.pay_internetsecure.CC_TYPE.value == "") { check = 1; }
   if (document.pay_internetsecure.CC_NUM.value == "") { check = 1; }
   if (document.pay_internetsecure.CC_AVS.value == "") { check = 1; }

   if (check != 1) {
      document.pay_internetsecure.submit();
   } else {
      alert('<?php echo lang("YOU DID NOT COMPLETE ALL REQUIRED FIELDS").'.\n'.lang("PLEASE MAKE CORRECTIONS BEFORE CONTINUING")."."; ?>');
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
<form name="pay_internetsecure" method="post" action="pgm-payment_gateway.php">
<input type="hidden" name="INTERNETSECURE_FLAG" value="1">
<input type="hidden" name="PAY_TYPE" value="internetsecure">
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
     <? echo lang("Security Code"); ?>:
     </td>

     <!---CC_AVS--->
     <td align="left" valign="middle">
      <input type="text" name="CC_AVS" class="cctext" style='WIDTH: 50px;'>
     </td>
    </tr>
    <tr>
     <td colspan="2" class="text" align="center" bgcolor="<? echo $OPTIONS[DISPLAY_HEADERBG]; ?>">
      <input type="button" value=" Process Order &gt;&gt;" class="FormLt1" name="button" onClick="document.pay_internetsecure.submit()">
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