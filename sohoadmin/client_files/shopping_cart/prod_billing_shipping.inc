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

$cartpref = new userdata("cart");

?>

<script type="text/javascript" language="javascript">
function same_info() {

	document.CHECKOUT1.SFIRSTNAME.value = document.CHECKOUT1.BFIRSTNAME.value;
	document.CHECKOUT1.SLASTNAME.value = document.CHECKOUT1.BLASTNAME.value;
	document.CHECKOUT1.SCOMPANY.value = document.CHECKOUT1.BCOMPANY.value;
	document.CHECKOUT1.SADDRESS1.value = document.CHECKOUT1.BADDRESS1.value;
	document.CHECKOUT1.SADDRESS2.value = document.CHECKOUT1.BADDRESS2.value;
	document.CHECKOUT1.SCITY.value = document.CHECKOUT1.BCITY.value;
	document.CHECKOUT1.SPHONE.value = document.CHECKOUT1.BPHONE.value;
	document.CHECKOUT1.SCOUNTRY.value = document.CHECKOUT1.BCOUNTRY.value;
	document.CHECKOUT1.SSTATE.value = document.CHECKOUT1.BSTATE.value;
	document.CHECKOUT1.SZIPCODE.value = document.CHECKOUT1.BZIPCODE.value;

}
</script>

<script type="text/javascript" language="javascript">
function check_data() {

	var err_read = "";

	// Check Billing Data
	// ----------------------------------------------------

	if (document.CHECKOUT1.BFIRSTNAME.value == "") {
		err_read = err_read+"Billing First Name\n";
	}
	
	if (document.CHECKOUT1.BLASTNAME.value == "") {
		err_read = err_read+"Billing Last Name\n";
	}
	
	if (document.CHECKOUT1.BADDRESS1.value == "") {
		err_read = err_read+"Billing Address\n";
	}
	
	if (document.CHECKOUT1.BCITY.value == "") {
		err_read = err_read+"Billing City\n";
	}
	
	if (document.CHECKOUT1.BSTATE.value == "") {
		err_read = err_read+"Billing State\n";
	}
	
<?php
if ( $OPTIONS['DISPLAY_ZIP'] != "noshow" ) {
?>
	if (document.CHECKOUT1.BZIPCODE.value == "" || document.CHECKOUT1.BZIPCODE.value == " " ) {
		err_read = err_read+"Billing Zip Code\n";
	}
<?php
}
?>
	
	if (document.CHECKOUT1.BPHONE.value == "") {
		err_read = err_read+"Billing Phone Number\n";
	}

	// Now Check Shipping Data
	// ----------------------------------------------------

	if (document.CHECKOUT1.SFIRSTNAME.value == "") {
		err_read = err_read+"\nShipping First Name\n";
	}
	if (document.CHECKOUT1.SLASTNAME.value == "") {
		err_read = err_read+"Shipping Last Name\n";
	}
	if (document.CHECKOUT1.SADDRESS1.value == "") {
		err_read = err_read+"Shipping Address\n";
	}
	if (document.CHECKOUT1.SCITY.value == "") {
		err_read = err_read+"Shipping City\n";
	}
	if (document.CHECKOUT1.SSTATE.value == "") {
		err_read = err_read+"Shipping State\n";
	}
	if (document.CHECKOUT1.SZIPCODE.value == "" || document.CHECKOUT1.SZIPCODE.value == " ") {
		err_read = err_read+"Shipping Zip Code\n";
	}
	if (document.CHECKOUT1.SPHONE.value == "") {
		err_read = err_read+"Shipping Phone Number\n";
	}

	if (err_read != "") {
		alert("<?php echo lang("You have left the following required fields blank"); ?>:\n\n"+err_read);
	} else {
		document.CHECKOUT1.submit();
	}

}

function check_n_send() {
   
   <?
   if ( $cartpref->get("disable_shipping") == "yes" ) {
      echo "same_info();\n";
   }
   ?>
   
   document.CHECKOUT1.submit();
}

</SCRIPT>

<style>
.shipping_row {
   <?
   if ( $cartpref->get("disable_shipping") == "yes" ) {
      echo "display: none;\n";
   }
   ?>
}
</style>

<FORM NAME="CHECKOUT1" METHOD=POST ACTION="pgm-checkout.php">
<INPUT TYPE=HIDDEN NAME=customer_active VALUE="Y">

<?


// ===========================================================================
// If this needs to return to the verify order details step, the EDIT_INFO
// flag var will be turned on because this is being accessed from the 'edit'
// option in Step 4
// ===========================================================================

if ($EDIT_INFO == "ON") {
	echo "<INPUT TYPE=HIDDEN NAME=EDIT_INFO VALUE=\"ON\">\n";
	echo "<INPUT TYPE=HIDDEN NAME=\"STEP\" VALUE=\"3\">\n";
} else {
	echo "<INPUT TYPE=HIDDEN NAME=\"STEP\" VALUE=\"3\">\n";
}

?>

<table border="0" cellspacing="0" cellpadding="5" class="smtext" id="billing_shipping_form">



<?

if ($st_err == 1) {
	echo "<TR><TD COLSPAN=2 ALIGN=CENTER VALIGN=MIDDLE class=text BGCOLOR=WHITE><font color=red>".lang("The state you selected to ship your order to does not appear to be valid.")."  ".lang("Please correct and re-submit your information.")."</font></TD></TR>";
}

if ($email_err == 1) {
	echo "<TR><TD COLSPAN=2 ALIGN=CENTER VALIGN=MIDDLE class=text BGCOLOR=WHITE><font color=red>".lang("The email address you provided is not a valid email address.")." ".lang("Please correct and re-submit your information.")."</font></TD></TR>";
}

// -------------------------------------------------------------------------------
// Only show remember me feature to "New Customers"
// -------------------------------------------------------------------------------


if (eregi("Y", $OPTIONS['DISPLAY_REMEMBERME']) && !session_is_registered("REPEATCUSTOMER")) {

	echo " <tr>\n";
	echo "  <td colspan=\"2\" style=\"font-weight:bold\">".lang("Customer Registration")."</td>\n";
	echo " </tr>\n";
	echo " <TR>\n";
	echo "  <TD COLSPAN=\"2\">\n";

	if ( strtoupper($REMEMBERME) == "ON" || strtoupper($_SESSION['REMEMBERME']) == "ON" ) { $chk_rememberme = " checked"; } else { $chk_rememberme = ""; }
	echo "   <INPUT TYPE=\"checkbox\" NAME=\"REMEMBERME\" VALUE=\"ON\"".$chk_rememberme.">\n";
	echo "   ".lang("Yes, I want you to remember my Billing &amp; Shipping Information the next time I purchase something.")."\n";
	echo "  </TD>\n";
	echo " </TR>\n";
	echo " <TR>\n";
	echo "  <TD>\n";
	echo "   <B>".lang("Choose a password")."</B>:<BR>\n";
   echo "   <INPUT TYPE=\"password\" NAME=\"BPASSWORD\" SIZE=\"25\" class=\"tfield\" VALUE=\"".$BPASSWORD."\">\n";
	echo "  </TD>\n";
	echo "<TD ALIGN=\"LEFT\" VALIGN=\"MIDDLE\"><B>".lang("Verify your password")."</B>:<BR>\n";
	echo ' <INPUT TYPE="password" NAME="BVERIFYPW" SIZE="25" STYLE="font-family: Arial; font-size: 9pt; width: 275px;" VALUE="'.$BVERIFYPW.'">
	    </TD>
	  </TR>
	  <TR ALIGN="CENTER">
	    <TD COLSPAN="2">';

	if ($pwerror == 1) {
		echo "<FONT COLOR=RED>".lang("The passwords that you entered do not match each other. Please check the spelling and re-submit.")."</font><BR><BR>";
	}
	if ($rm_err == 1) {
		echo "<FONT COLOR=RED>".lang("You have elected to register as a customer but did not choose a password for your account. Please do so now.")."</font><BR><BR>";
	}

	echo "<FONT COLOR=\"#999999\"><I>(".lang("If you are not using the customer registration feature, you may leave the password fields blank").")</I></FONT></TD></TR>";

} // End Remember Me Row



// -------------------------------------------------------------------------------
// Build dynamic display options, like 'local country' and such
// -------------------------------------------------------------------------------
$result = mysql_query("SELECT * FROM cart_options");
$OPTIONS = mysql_fetch_array($result);

//040406 - Pull Currency Info
$localNat = $OPTIONS[LOCAL_COUNTRY];
$dispState = $OPTIONS[DISPLAY_STATE];
$dispZip = $OPTIONS[DISPLAY_ZIP];

$noShow = "";
if ( $dispState == "noshow" ) { $noShow = " colspan=\"2\""; }
if ( eregi("STATE~", $OPTIONS['DISPLAY_REQUIRED']) ) { $reqSate = "<font color=\"#D70000\"><b>*</b></font>"; }

$noZip = "";
if ( $OPTIONS['DISPLAY_ZIP'] == "noshow" ) { $noZip = " colspan=\"2\""; }
if ( eregi("ZIPCODE~", $OPTIONS['DISPLAY_REQUIRED']) ) { $reqZip = "<font color=\"#D70000\"><b>*</b>"; }

function getErr( $txt, $err, $return = "echo" ) {
   global $err_read;
   $chkThis = $err_read;

   if ( eregi($err,$chkThis) ) {
      $rez = "$txt</font>";
   } else {
      $rez = "</font>$txt";
   }

   if ( $return == "echo" ) {
      echo $rez;
   } else {
      return $rez;
   }
}


function getStates( $field ) {

   $stateOpts = "";
   global $dispState;
   global ${$field};
   
   if ($dispState != "tfield" && $dispState != "noshow") {

      $stateOpts .= "  <select name=\"".$field."\" id=\"".$field."\">\n";
      $stateOpts .= "  <option value=\"\">".lang('Please choose a state')."...</option>\n";

      # US States?
      if ( eregi("us", $dispState) ) {
         //Display state drop-down
         $filename = "us_states.dat";
         $file = fopen("$filename", "r") or DIE(lang("Error").": ".lang("Could not open state data")." (us_states.dat).");
      	$tmp_data = fread($file,filesize($filename));
         fclose($file);

         $stateDat = split("\n", $tmp_data);
         $numOpts = count($stateDat);

         for ($s=0;$s < $numOpts;$s++) {
            $stateDat[$s] = rtrim($stateDat[$s]);
            $sel = "";
            if ($stateDat[$s] == ${$field}) { $sel = " selected:".${$field}; }
            $stateOpts .= "   <option value=\"".strtoupper($stateDat[$s])."\"".$sel.">$stateDat[$s]</option>\n";
         }
      }

      # US Territories?
      if ( eregi("terr", $dispState) ) {
         $filename = "us_territories.dat";
         $file = fopen("$filename", "r") or DIE(lang("Error").": ".lang("Could not open state data")." (".$filename.").");
      	$tmp_data = fread($file,filesize($filename));
         fclose($file);

         $stateDat = split("\n", $tmp_data);
         $numOpts = count($stateDat);

         for ($s=0;$s < $numOpts;$s++) {
            $stateDat[$s] = rtrim($stateDat[$s]);
            $sel = "";
            if ($stateDat[$s] == ${$field}) { $sel = " selected"; }
            $stateOpts .= "   <option value=\"".strtoupper($stateDat[$s])."\"".$sel.">$stateDat[$s]</option>\n";
         }
      }

      # Canadian provinces?
      if ( eregi("can", $dispState) ) {

         //Display province drop-down
         $filename = "can_prov.dat";
         $file = fopen("$filename", "r") or DIE(lang("Error").": ".lang("Could not open province data")." (can_prov.dat).");
      	$tmp_data = fread($file,filesize($filename));
         fclose($file);

         $stateDat = split("\n", $tmp_data);
         $numOpts = count($stateDat);

         if ( $dispState == "uscanmenu" ) {$stateOpts .= "   <option value=\"\" style='background:#cccccc;'> </option>\n";}

         for ($s=0;$s < $numOpts;$s++) {
            $stateDat[$s] = rtrim($stateDat[$s]);
            $sel = "";
            if ($stateDat[$s] == ${$field}) { $sel = " selected"; }

            $stateOpts .= "   <option value=\"".strtoupper($stateDat[$s])."\"".$sel.">$stateDat[$s]</option>\n";
         }
      }

      if ( eregi("kangaroo", $dispState) ) {

         //Display province drop-down
         $filename = "aus_states.dat";
         $file = fopen("$filename", "r") or DIE(lang("Error").": ".lang("Could not open state data")." (aus_states.dat).");
         $tmp_data = fread($file,filesize($filename));
         fclose($file);

         $stateDat = split("\n", $tmp_data);
         $numOpts = count($stateDat);

         for ($s=0;$s < $numOpts;$s++) {
            $stateDat[$s] = rtrim($stateDat[$s]);
            $sel = "";
            if ($stateDat[$s] == ${$field}) { $sel = " selected"; }

            $stateOpts .= "   <option value=\"".strtoupper($stateDat[$s])."\"".$sel.">$stateDat[$s]</option>\n";
         }
      }

      $stateOpts .= "  </select>\n";

   } elseif ($dispState == "tfield") {
      $stateOpts .= "<input type='text' name='".$field."' class=\"tfield\" VALUE=".$$field.">\n";
   } elseif ($dispState == "noshow") {
      $stateOpts .= "<input type='hidden' name='".$field."' VALUE=".$$field.">\n";
   }

   echo $stateOpts;
}

function getNats( $field ) {

	$natOpts = "  <select name=\"".$field."\">";
	global $localNat;
	global ${$field};

	$filename = "countries.dat";
	$file = fopen("$filename", "r") or DIE(lang("Error").": ".lang("Could not open country data")." (contries.dat).");
	$tmp_data = fread($file,filesize($filename));
	fclose($file);

	$natDat = split("\n", $tmp_data);
	$numNats = count($natDat);
	$GLOBALS['countlist'] = $natDat;
	//natDat: T.M.I (for now) format for proper display and usage
	$natNam = "";
	for ($f=0; $f < $numNats; $f++) {
		$tmpSplt = split("::", $natDat[$f]);
		$natNam[$f] = $tmpSplt[0]." - ".$tmpSplt[1];
		$natNam[$f] = strtoupper($natNam[$f]);
	}

	if ( $localNat == "" || $localNat == "No Default Country" ) {

      //Display full country drop-down
		for ($c=0;$c < $numNats;$c++) {
				$sel = "";
			if ($natNam[$c] == ${$field}) { $sel = " selected"; }
				$natOpts .= "   <option value='$natNam[$c]'$sel>$natNam[$c]</option>\n";
			}

		} else {

      //Display limited drop down (might add 'multiple local countries' soon)
      for ($c=0;$c < $numNats;$c++) {
         $sel = "";
				if($_SESSION[$field] == ''){	
					if ( $c == 0 ) { $sel = "selected"; }
				}
				if($_SESSION[$field] == $natNam[$c] && $_SESSION[$field] != ''){
					$sel = "selected";
				}
         
				if ( eregi($natNam[$c].":n:", $localNat) ) {
					$natOpts .= "   <option value='$natNam[$c]' $sel>$natNam[$c]</option>\n";
				}
			}
		}

		$natOpts .= "</select>\n";
		echo $natOpts;
}


?>



<A NAME="NXT"></A>


  <tr>
    <td width="100%" colspan="2" style="font-weight:bold"><? echo lang("Billing Information"); ?></td>
  </TR>

  <?
  /*
   if ($err_read != "") {
      echo "<tr><td align=left colspan=2><font style='color:#ff0000; font-size:12px;'><i>*Please complete all required fields and re-submit.</i></font><br></td></tr>\n";
   }
  */
  ?>

  <TR>
    <TD ALIGN="LEFT" WIDTH="50%" VALIGN="TOP"><B><FONT COLOR="#FF0000">*<? getErr(lang("First Name").":","A"); ?></B><BR>
      <INPUT TYPE="text" NAME="BFIRSTNAME" SIZE="25" class="tfield" VALUE="<? echo $BFIRSTNAME; ?>">
    </TD>
    <TD ALIGN="LEFT" WIDTH="50%" VALIGN="TOP"><B><FONT COLOR="#FF0000">*<? getErr(lang("Last Name").":","B"); ?></B><BR>
      <INPUT TYPE="text" NAME="BLASTNAME" SIZE="25" class="tfield" VALUE="<? echo $BLASTNAME; ?>">
    </TD>
  </TR>
  <TR>
    <TD ALIGN="LEFT" VALIGN="TOP"><B><? echo lang("Company Name"); ?></B> (<? echo lang("Optional"); ?>):<BR>
      <INPUT TYPE="text" NAME="BCOMPANY" SIZE="25" class="tfield" VALUE="<? echo $BCOMPANY; ?>">
    </TD>
    <TD ALIGN="LEFT" VALIGN="TOP">
     <B><FONT COLOR="#FF0000">*<? getErr(lang("Address").":","C"); ?></B>
<?
if ( $cartpref->get("nopobox_msg") != "no" ) {
   echo "(".lang("No PO Boxes").")\n";
}
?>
      <BR>
      <INPUT TYPE="text" NAME="BADDRESS1" SIZE="25" class="tfield" VALUE="<? echo $BADDRESS1; ?>">
      <BR>
      <INPUT TYPE="text" NAME="BADDRESS2" SIZE="25" class="tfield" VALUE="<? echo $BADDRESS2; ?>">
    </TD>
  </TR>
  <TR>
    <td align="left" valign="top"<? echo $noZip; ?>><b><font color="#FF0000">*<? getErr(lang("City").":","D"); ?></B><BR>
      <INPUT TYPE="text" NAME="BCITY" SIZE="25" class="tfield" VALUE="<? echo $BCITY; ?>">
    </TD>


    <?
    if ($dispState != "noshow") {
      echo "<TD ALIGN=\"LEFT\" VALIGN=\"TOP\">\n";
      echo " <B>".$reqSate.lang("State/Province").":</B><BR>\n";
      getStates("BSTATE");
      echo " \n";
      echo "</TD>\n";
   }
    ?>
  </TR>

  <tr>
      <?

     # How should the Zip/Postal Code field be displayed?
     #------------------------------------------------------
     if ( $OPTIONS['DISPLAY_ZIP'] != "noshow" ) {

       if ( $OPTIONS['DISPLAY_ZIP'] == "postal" ) {
          $showZip = lang("Postal Code");
       } elseif ( $OPTIONS['DISPLAY_ZIP'] == "zip" ) {
          $showZip = lang("Zip Code");
       } elseif ( $OPTIONS['DISPLAY_ZIP'] == "zippostal" ) {
          $showZip = lang("Zip / Postal Code");
       }

       echo "<td align=\"left\" valign=\"top\"><b>".$reqZip."".getErr($showZip.":","F", "return")."</b><br>\n";
       echo " <input type=\"text\" name=\"BZIPCODE\" size=\"25\" class=\"tfield\" value=\"".$BZIPCODE."\">\n";
       echo "</td>\n";
     }

    ?>
  

    <TD ALIGN="LEFT" VALIGN="TOP"<? echo $noShow; ?>><B><FONT COLOR="#FF0000">*</FONT><? echo lang("Country"); ?></B>:<BR>
     <? getNats("BCOUNTRY"); ?>
    </TD>
  </TR>

  <TR>
    <TD ALIGN="LEFT" VALIGN="TOP"><B><FONT COLOR="#FF0000">*<? getErr(lang("Billing Phone Number").":","G"); ?></B><BR>
      <INPUT TYPE="text" NAME="BPHONE" SIZE="25" class="tfield" VALUE="<? echo $BPHONE; ?>">
    </TD>
    <TD ALIGN="LEFT" VALIGN="TOP"><B><FONT COLOR="#FF0000">*</FONT><? echo lang("Email Address"); ?></B>:<BR>
      <INPUT TYPE="text" NAME="BEMAILADDRESS" SIZE="25" class="tfield" VALUE="<? echo $_SESSION['BEMAILADDRESS']; ?>">
      <BR>

	<?

		if ($email_err == 1) {
			echo "<font color=red>*".lang("INVALID EMAIL ADDRESS")."</font><BR>";
		}

	?>

      <FONT>(<I><? echo lang("Used to send a copy of your invoice, and also serves as your username for future purchases."); ?></I>)</FONT></TD>
  </TR>
  <TR>
    <TD>&nbsp;</TD>
    <TD>&nbsp;</TD>
  </TR>
  <TR class="shipping_row">
    <td colspan="2" style="font-weight:bold"><? echo lang("Shipping Information"); ?></td>
  </TR>
  <TR class="shipping_row">

<?

	echo "<INPUT TYPE=HIDDEN NAME=shippingsame VALUE=\"$shippingsame\">\n";
	if ($shippingsame == "1") { $CHECKED = "CHECKED"; } else { $CHECKED = ""; }

?>

    <TD COLSPAN="2"> <? echo lang("Click Here"); ?>
      <INPUT TYPE="checkbox" NAME="shippingsame" VALUE="1" onclick="same_info();" <? echo $CHECKED; ?>>
      <? echo lang("to use Billing Information."); ?>
<?
if ( $cartpref->get("nopobox_msg") != "no" ) {
   echo lang("Note: we do not ship to P.O. Boxes.")."\n";
}
?>
      </TD>
  </TR>
  <TR class="shipping_row">
    <TD ALIGN="LEFT" WIDTH="50%" VALIGN="TOP">&nbsp;</TD>
    <TD ALIGN="LEFT" WIDTH="50%" VALIGN="TOP">&nbsp;</TD>
  </TR>
  <TR class="shipping_row">
    <TD ALIGN="LEFT" WIDTH="50%" VALIGN="TOP"><B><FONT COLOR="#FF0000">*<? getErr(lang("First Name").":","J"); ?></B><BR>
      <INPUT TYPE="text" NAME="SFIRSTNAME" SIZE="25" class="tfield" VALUE="<? echo $SFIRSTNAME; ?>">
    </TD>
    <TD ALIGN="LEFT" WIDTH="50%" VALIGN="TOP"><B><FONT COLOR="#FF0000">*<? getErr(lang("Last Name").":","K"); ?></B><BR>
      <INPUT TYPE="text" NAME="SLASTNAME" SIZE="25" class="tfield" VALUE="<? echo $SLASTNAME; ?>">
    </TD>
  </TR>
  <TR class="shipping_row">
    <TD ALIGN="LEFT" VALIGN="TOP"><B><? echo lang("Company Name"); ?></B> (<? echo lang("Optional"); ?>):<BR>
      <INPUT TYPE="text" NAME="SCOMPANY" SIZE="25" class="tfield" VALUE="<? echo $SCOMPANY; ?>">
    </TD>
    <TD ALIGN="LEFT" VALIGN="TOP"><B><FONT COLOR="#FF0000">*<? getErr(lang("Address").":","L"); ?></B>
<?
if ( $cartpref->get("nopobox_msg") != "no" ) {
   echo "(".lang("No PO Boxes").")\n";
}
?>
      <BR>
      <INPUT TYPE="text" NAME="SADDRESS1" SIZE="25" class="tfield" VALUE="<? echo $SADDRESS1; ?>">
      <BR>
      <INPUT TYPE="text" NAME="SADDRESS2" SIZE="25" class="tfield" VALUE="<? echo $SADDRESS2; ?>">
    </TD>
  </TR>
  <TR class="shipping_row">
    <td align="left" valign="top"<? echo $noZip; ?>><B><FONT COLOR="#FF0000">*<? getErr(lang("City").":","M"); ?></B><BR>
      <INPUT TYPE="text" NAME="SCITY" SIZE="25" class="tfield" VALUE="<? echo $SCITY; ?>">
    </TD>

<?
    if ($dispState != "noshow") {
      echo "<TD ALIGN=\"LEFT\" VALIGN=\"TOP\">\n";
      echo " <B>".$reqSate.lang("State/Province").":</B><BR>\n";
      getStates("SSTATE");
      echo " \n";
      echo "</TD>\n";
    }
   ?>

  </TR>
  <TR class="shipping_row">
    <?

     # How should the Zip/Postal Code field be displayed?
     #------------------------------------------------------
     if ( $OPTIONS['DISPLAY_ZIP'] != "noshow" ) {

       if ( $OPTIONS['DISPLAY_ZIP'] == "postal" ) {
          $showZip = lang("Postal Code");
       } elseif ( $OPTIONS['DISPLAY_ZIP'] == "zip" ) {
          $showZip = lang("Zip Code");
       } elseif ( $OPTIONS['DISPLAY_ZIP'] == "zippostal" ) {
          $showZip = lang("Zip / Postal Code");
       }

       echo "<td align=\"left\" valign=\"top\"><b>".$reqZip."".getErr($showZip.":","N", "return")."</b><br>\n";
       echo " <input type=\"text\" name=\"SZIPCODE\" size=\"25\" class=\"tfield\" value=\"".$SZIPCODE."\">\n";
       echo "</td>\n";
     }

    ?>
    <TD ALIGN="LEFT" VALIGN="TOP" <? echo $noShow; ?>><B><FONT COLOR="#FF0000">*</FONT><? echo lang("Country"); ?></B>:<BR>
     <? getNats("SCOUNTRY"); ?>
    </TD>
  </TR>
  <TR class="shipping_row">
    <TD><B><FONT COLOR="#FF0000">*<? getErr(lang("Ship-To Phone Number").":","M"); ?></B><BR>
      <INPUT TYPE="text" NAME="SPHONE" SIZE="25" class="tfield" VALUE="<? echo $SPHONE; ?>">
    </TD>
    <TD ALIGN="CENTER" VALIGN="TOP">&nbsp;</TD>
  </TR>

  <TR class="shipping_row">
    <TD>&nbsp;</TD>
    <TD ALIGN="CENTER" VALIGN="MIDDLE">&nbsp;</TD>
  </TR>
  <TR>
    <TD COLSPAN=2 ALIGN="CENTER" VALIGN="MIDDLE">


      <INPUT TYPE=IMAGE SRC="cont_checkout.gif" onclick="check_n_send();" WIDTH=106 HEIGHT=25 BORDER=0 STYLE="cursor: hand;" >

    </TD>
  </TR>
  <TR>
    <TD>&nbsp;</TD>
    <TD ALIGN="CENTER" VALIGN="MIDDLE">&nbsp;</TD>
  </TR>
</TABLE>

</FORM>

<?

// Make sure drop down selection boxes show proper selected value
// ----------------------------------------------------------------
$bphone_display = $BPHONE_COUNTRYCODE."-".$BPHONE_AREACODE."-".$BPHONE_PREFIX."-".$BPHONE_SUFFIX;
$sphone_display = $SPHONE_COUNTRYCODE."-".$SPHONE_AREACODE."-".$SPHONE_PREFIX."-".$SPHONE_SUFFIX;
$bphone_display = trim($bphone_display, "-");
$sphone_display = trim($sphone_display, "-");

//$countrylist = $GLOBALS['countlist'];
////echo testArray($countrylist);
//$BCOUNTRY = eregi_replace('( )*$', "", $BCOUNTRY);
//$BCOUNTRY = eregi_replace('\-', '\:\:', $BCOUNTRY);
//$SCOUNTRY = eregi_replace('( )*$', "", $SCOUNTRY);
//$SCOUNTRY = eregi_replace('\-', '\:\:', $SCOUNTRY);
//$BCOUNTRY  = strtoupper($BCOUNTRY);
//$SCOUNTRY  = strtoupper($SCOUNTRY);
//foreach($countrylist as $countryrope){
//	$countryrope = strtoupper($countryrope);
//	if(eregi($BCOUNTRY, $countryrope)){
//		$countryropear = explode('::', $countryrope);
//		$BCOUNTRY = strtoupper($countryropear['0']).'-'.strtoupper($countryropear['1']);
//
//	}
//	if(eregi($SCOUNTRY, $countryrope)){
//		$countryropear = explode('::', $countryrope);
//		$SCOUNTRY = strtoupper($countryropear['0']).'-'.strtoupper($countryropear['1']);
//	}
//}


echo "<script type=\"text/javascript\">\n\n";
if ( $localNat == "" || $localNat == "No Default Country" ) {
	if ($BCOUNTRY != "") {
		echo "     document.CHECKOUT1.BCOUNTRY.options.value = \"$BCOUNTRY\";\n";
	}
	if ($SCOUNTRY != "") {
		echo "     document.CHECKOUT1.SCOUNTRY.options.value = \"$SCOUNTRY\";\n";
	}
}

if ( $bphone_display != "" ) { echo "     document.CHECKOUT1.BPHONE.value = '".$bphone_display."';\n"; }
if ( $sphone_display != "" ) { echo "     document.CHECKOUT1.SPHONE.value = '".$sphone_display."';\n"; }


# State/Province

if ( $BSTATE != "" ) {
   echo "document.CHECKOUT1.BSTATE.value = \"".$BSTATE."\";\n";
} else {
	echo "document.CHECKOUT1.BSTATE.value = \"\";\n";
}



if ( $SSTATE != "" ) {
	echo "document.CHECKOUT1.SSTATE.value = \"".$SSTATE."\";\n";
} else {
	echo "document.CHECKOUT1.SSTATE.value = \"\";\n";
}

echo "\n\n</SCRIPT>\n";

?>