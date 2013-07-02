<?php

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
session_start();

include_once('../sohoadmin/program/includes/shared_functions.php');
include_once("pgm-cart_config.php");

//include("../sohoadmin/includes/emulate_globals.php");

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
     		for (var i=0;i<lstring.length;i++){
         		aNum=astring.indexOf(lstring.substring(i,i+1),0)
         		aNum=aNum^25
         		retstr=retstr+astring.substring(aNum,aNum+1)}
     		return retstr
	}

	function onClick(){

		var check = 0;

		if (document.OFFLINECARD.CC_NAME.value == "") { check = 1; }
		if (document.OFFLINECARD.CC_TYPE.value == "") { check = 1; }
		if (document.OFFLINECARD.CC_NUM.value == "") { check = 1; }
		if (document.OFFLINECARD.CC_AVS.value == "") { check = 1; }

		if (check != 1) {

			document.OFFLINECARD.VERIFY_CCNUM_CLASS.value = document.OFFLINECARD.CC_NUM.value;
			document.OFFLINECARD.CC_NUM.value=encrypt(document.OFFLINECARD.CC_NUM.value);

			// DEVNOTE: You may remove the alert line or comment it out here
			// if you do not wish to have your customer notified of the instant
			// encryption.  Again, this was designed as a visual assurance piece,
			// not a security piece.

			alert('<?php echo lang("Your Credit Card Data has been Encrypted for transfer").'.\n\n'.lang("Click OK to continue processing")."... ".lang("This may take a few seconds")."."; ?>');

			document.OFFLINECARD.submit();

		} else {

			alert('<?php echo lang("YOU DID NOT COMPLETE ALL REQUIRED FIELDS").'.\n'.lang("PLEASE MAKE CORRECTIONS BEFORE CONTINUING")."."; ?>');

		}

    	}

</script>

<form name="OFFLINECARD" method="POST" action="pgm-show_invoice.php">
<input type="hidden" name="OFFLINE_FLAG" value="1">
<input type="hidden" name="ORDER_NUMBER" value="<?php echo $ORDER_NUMBER; ?>">
<input type="hidden" name="VERIFY_CCNUM_CLASS" value="">
<table width="100%" border="0" cellspacing="0" cellpadding="8"  style='border: 0px inset black;' align="center">
 <tr>
  <td colspan="2" class="text" align="center">
   <font color="red"><b>
   <?php echo lang("The total amount of your purchase"); ?>,
	<?php
	$paysignq = mysql_query("select PAYMENT_CURRENCY_SIGN from cart_options");
	while($ps = mysql_fetch_array($paysignq)) {
		$dSign = $ps['PAYMENT_CURRENCY_SIGN'];
	}
   echo $dSign.$ORDER_TOTAL; ?>,
   <?php echo lang("will be charged to your credit card."); ?>
   </b></font>
  </td>
 </tr>
 <!---<tr>
  <td colspan="2" class="text" align="center">
   ORDER_NUMBER: <?php echo $ORDER_NUMBER; ?><br>
   _SESSION[ORDER_NUMBER]: <?php echo $_SESSION['ORDER_NUMBER']; ?><br>
   thingy: <?php echo $_SESSION['thingy']; ?><br>
   ORDER_TOTAL: <?php echo $ORDER_TOTAL; ?>
  </td>
 </tr>--->
 <tr>
  <td align="left" valign="top" width="50%" class="text">
   <b><?php echo lang("Name as it appears on card"); ?>:</b><br>
   <input type="text" name="CC_NAME" class="text" style='width: 250px;' value="<?php echo "$BFIRSTNAME $BLASTNAME"; ?>">
  </td>
  <td class="text">
   <b><?php echo lang("Credit Card Type"); ?>:</b><br>
   <select name="CC_TYPE" class="text" style='width: 250px;'>

	<?php

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
 <tr>
  <td class="text">
   <b><?php echo lang("Credit Card Number"); ?>:</b>
   <br>
   <input type="text" name="CC_NUM" class="text" style='width: 250px;'>
  </td>
  <td class="text">
   <b><?php echo lang("Credit Card Expiration Date"); ?>:</b>
   <br>
   <?php echo lang("Month"); ?>:
   <select name="CC_MON" class="text">

	<?php

	$this_month = date("m");

	for ($x=1;$x<=12;$x++) {
		$show = $x;
		if ($x < 10) { $show = "0".$x; }
		if ($show == $this_month) { $SEL = "SELECTED"; } else { $SEL = ""; }
		echo "<OPTION VALUE=\"$show\" $SEL>$show</OPTION>\n";
	}

	?>

   </select>

   &nbsp;&nbsp;Year:

   <select name="CC_YEAR" class="text">

	<?php

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
   <b><?php echo lang("Security Code"); ?>:</b>
   <br>
   <input type="text" name="CC_AVS" class="text" style='WIDTH: 250px;'>
  </td>
  <td align="center" valign="middle">
   &nbsp;
  </td>
 </tr>
 <tr>
  <td align="center" valign="middle" colspan="2">
   <input type="button" value=" Complete Order " class="FormLt1" name="button" onClick="onClick()">
  </td>
  <td valign="bottom">
   &nbsp;
  </td>
 </tr>
 <tr>
  <td align="center" colspan="2" class="text">
   <?php echo lang("How to find your security code"); ?>:<br>
   <img src="avs_graphic.gif" width="516" height="130">
  </td>
 </tr>
</table>
</form>