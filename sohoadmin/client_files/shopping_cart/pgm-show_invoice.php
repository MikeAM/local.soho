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


error_reporting(E_PARSE);
session_start();

if ( $_SESSION['ORDER_NUMBER'] == '' && $_SESSION['final_display_reload'] != '') {
	echo $_SESSION['final_display_reload'];
	exit;
}

# Include config script
include("pgm-cart_config.php");

include('pull-policies.inc.php');


foreach($_REQUEST as $name=>$value){
	${$name} = $value;
}

foreach($_SESSION as $name=>$value){
	${$name} = $value;
}

if($_GET['nochex'] > 1){
	$nochex_return = 1;
} 

$ORDER_NUM = $ORDER_NUMBER; // Pesky little var she is

//echo "Order Number: [".$ORDER_NUMBER."]<br>";
//echo "_SESSION[ORDER_NUMBER]: [".$_SESSION['ORDER_NUMBER']."]<br>"; exit;


# Assign dot_com variable to configured ip/url
$dot_com = $this_ip;

$cartpref = new userdata("cart");

##########################################################################

##########################################################################
### READ SHOPPING CART SETUP OPTIONS
##########################################################################

$result = mysql_query("SELECT * FROM cart_options");
$OPTIONS = mysql_fetch_array($result);

$result = mysql_query("SELECT * FROM cart_dps");
$DPS = mysql_fetch_array($result);

$result = mysql_query("SELECT * FROM cart_paypal");
$PAYPAL = mysql_fetch_array($result);

$result = mysql_query("SELECT * FROM cart_innovgate");
$INNOVGATE = mysql_fetch_array($result);

$result = mysql_query("SELECT * FROM cart_paypoint");
$PAYPOINT = mysql_fetch_array($result);

$result = mysql_query("SELECT * FROM cart_authorize");
$AUTHORIZE = mysql_fetch_array($result);

# Newschool
$gateway = new userdata("gateway");


# Format cc num for display
function ccsafe($raw_ccnum) {
   $cc_safe = eregi_replace("[0-9]", "X", substr($raw_ccnum, 0, (strlen($raw_ccnum) - 4))).substr($raw_ccnum, -4);
   return $cc_safe;
}

////////////////////////////////////////////////
////////////////////// Insert Custom Form Data!!
if ( !function_exists('db_string_formatz') ) {
	function db_string_formatz($string) {
	   if ( !get_magic_quotes_gpc() ) {
	      return mysql_real_escape_string($string);
	   } else {
	      return $string;
	   }
	}
}

$form_order_date = date("m/d/Y");

      foreach($_SESSION['formdata'] as $tt=>$ta) {
         $getprodn = mysql_query("select PROD_NAME from cart_products where PRIKEY='".$tt."'");
         $getprodname = mysql_fetch_array($getprodn);
         $TABLE_NAME = 'cart_data_'.eregi_replace(' ', '_', $getprodname['PROD_NAME']);
         $TABLE_NAME = 'UDT_'.$TABLE_NAME;

         $TABLE_NAME = eregi_replace(" ", '_', supersterilize(stripslashes(strtoupper($TABLE_NAME))));
         $tana = $ta;

         /////Make sure table exists, if not create it
         if(!table_exists($TABLE_NAME)) {
            $SQL_CREATE_FTAB = "CREATE TABLE $TABLE_NAME (PRIKEY INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ORDER_NUMBER CHAR(255)";

            while (list($name, $value) = each($ta)) {
               $SQL_CREATE_FTAB .= ", ".eregi_replace(" ", '_', supersterilize(strtoupper(stripslashes(eregi_replace("'", '', $name)))))." BLOB";       // Create all fields as CHAR(255) by default.
            } // End While Loop

            $SQL_CREATE_FTAB .= ", FORM_NUMBER CHAR(255), PURCHASER CHAR(255), ORDER_DATE CHAR(50))";     // Make sure we add the auto_image field to UDT
            mysql_query($SQL_CREATE_FTAB);
         }

         while (list($rname, $rvalue) = each($tana)) {
            $fcount = count($rvalue);
         } // End While Loop

         $xf = 0;
         while($xf < $fcount) {
            $FORM_SQL_INSERT = "INSERT INTO ".$TABLE_NAME." VALUES('', '".$ORDER_NUMBER."'";
						$formstuffs = '';
         		$formstuffs['ORDER_NUMBER'] = $ORDER_NUMBER;

            foreach($tana as $xuu=>$xaa) {
							$the_TABLE = eregi_replace(" ", '_', supersterilize(strtoupper(stripslashes(eregi_replace("'", '', $xuu)))));
							if($xuu == 'fileuploaded'){
								$the_value = db_string_formatz($xaa[$xf]);
							} else {
								$the_value = eregi_replace("_", ' ', db_string_formatz($xaa[$xf]));
							}

							$formstuffs[$the_TABLE] = $the_value;
            }

						$form_x_of_total = $xf + 1;
						$formstuffs['FORM_NUMBER'] = $form_x_of_total." of ".$fcount;
						$formstuffs['PURCHASER'] = $_SESSION['BFIRSTNAME']." ".$_SESSION['BLASTNAME'];
            $formstuffs['ORDER_DATE'] = $form_order_date;

						$frminsrt = new mysql_insert($TABLE_NAME, $formstuffs);

						//$frminsrt->test();
						$frminsrt->insert();

            $xf++;
         }
         unset($_SESSION['formdata'][$tt]);
      }

////////////////////// End Insert Custom Form Data!!
////////////////////////////////////////////////////

/*--------------------------------------------------------------------*
        __  __ _ _
       / _|/ _| (_)
  ___ | |_| |_| |_ _ __   ___
 / _ \|  _|  _| | | '_ \ / _ \
| (_) | | | | | | | | | |  __/
 \___/|_| |_| |_|_|_| |_|\___|

/*--------------------------------------------------------------------*/

$DOWNLOAD_AVAIL = "YES";				// By default, set file download availability to on
############################################################################
### CHECK FOR OFFLINE CREDIT CARD PROCESSING METHOD.
### IF SO, DATABASE CC DATA AND DISPLAY INVOICE -- THANK YOU -- GOODBYE.
############################################################################

if ($OFFLINE_FLAG == "1") {				// This is being processed as an offline cc transaction

//   foreach ( $_POST as $var=>$val ) {
//      echo $var."=".$val."<br>";
//   }

	function ENCRYPT($string){
//	   echo "<div style=\"border: 1px solid red;\">";
//	   echo "About to encrypt...<br>";
//	   echo "Original: ($string)<br>";
   	$ENCRYPT_KEY = ":aAb`BcVCd/eXDfEYg FZhi?jGk|HlmI,nJo@TKpqL.WMrsNt!uvwOx<yPz>0QR12~3S4;^567U89%$#*()-_=+È‚‰Â‡ÁÍÎÓÏ≈…Ê∆ÙˆÚ˚˘÷‹¢£•É·Ì«¸Ò—™∫ø¨Ωº°´ª¶'";
   	$str = "";
   	$val = strlen($string);
   	for ( $i=0; $i < $val; $i++ ) {
   		$tmp = substr($string, $i, 1);
   		$aNum = strpos($ENCRYPT_KEY, $tmp, 0);
        		$aNum =$aNum^25;
        		$str = $str . substr($ENCRYPT_KEY, $aNum, 1);
        		//echo "Pass #".$i.": (".$str.")<br>";
   	}
   	return $str;
	}

	$NUMBER = ENCRYPT($CC_NUM);

	//echo $VERIFY_CCNUM_CLASS;

	include("prod_validate.class.inc.php");

	$Form = new CreditCardValidationSolution;
//   echo "<br>\$Form->CCVSNumber = {$VERIFY_CCNUM_CLASS}<br>";
   $Form->CCVSNumber = $VERIFY_CCNUM_CLASS;
	$Accept = array('Visa', 'American Express', 'MasterCard','Discover/Novus');

  	if ( !$Form->CCValidationSolution($Accept) ) {

		echo "<HTML><HEAD>\n";

		echo "<LINK rel=\"stylesheet\" href=\"../runtime.css\" type=\"text/css\">\n";

		echo "<TITLE>".lang("CREDIT CARD ERROR")."</TITLE></HEAD><BODY BGCOLOR=MAROON>\n\n";
		$err_code = $Form->CCVSError;
		$err_code = strtoupper($err_code);
      	echo "<CENTER><FONT COLOR=WHITE FACE=VERDANA><H3>$err_code</H3></FONT>\n\n";
		echo "<TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 WIDTH=600 ALIGN=CENTER><TR><TD align=center valign=top>\n\n";

		include("prod_offline_card.inc");

		echo "</TD></TR></TABLE>\n";
		echo "</BODY></HTML>\n";
		exit;

    }

	// CC_VALID CHECK COMPLETE!
	// ---------------------------------------------------------------------


	if (eregi("quixstar_", $customernumber)) {
		$QSTAR_WRITE = eregi_replace("quixstar_", "", $customernumber);
	} else {
		$QSTAR_WRITE = "";
	}

	$cc_first = substr($VERIFY_CCNUM_CLASS, 0, (strlen($VERIFY_CCNUM_CLASS) - 8));
	$cc_last = substr($VERIFY_CCNUM_CLASS, (strlen($VERIFY_CCNUM_CLASS) - 8));

	if($_GET['nochex'] > 1){
		$nochex_return = 1;
	} else {
		mysql_query("UPDATE cart_invoice SET
			CC_TYPE = '$CC_TYPE',
			CC_NUM = '$cc_last',
			CC_AVS = '',
			CC_DATE = '$CC_MON/$CC_YEAR',
			TRANSACTION_STATUS = 'Pending',
			TRANSACTION_ID = '$QSTAR_WRITE',
			PAY_METHOD = 'Offline Credit' WHERE ORDER_NUMBER = '$ORDER_NUMBER'");
	}
   # Mantis #266
   # Originally set to 'NO' here b/c card has not actually been charged yet
   # Now that we're setting it to 'YES' we state that offline processing
   # should not be used with products that allow for file download upon purchase.
   $DOWNLOAD_AVAIL = "YES";

	# Count down the inventory for all products ordered
	# and change display status if we have reached zero.
	include("pgm-inventory.php");

	// -----------------------------------------------------------------------
	// Pull Invoice HTML from invoice data table for display of final invoice
	// -----------------------------------------------------------------------
	$result = mysql_query("SELECT INVOICE_HTML FROM cart_invoice WHERE ORDER_NUMBER = '$ORDER_NUMBER'");
	$tmp = mysql_fetch_array($result);

	$INVOICE = $tmp['INVOICE_HTML'];

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// EMAIL APPROPRIATE RECIEPTS TO CUSTOMER AND WEBMASTER
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	//$ORDER_NUMBER = $ORDER_NUMBER;
	include("pgm-email_notify.php");


}


if ( $INNOVGATE_FLAG == "1" ) {				// This is being processed as an online cc transaction via Innovative Gateway Solutions

   /*----------------------------------------------------------------------------------------------------------
    _____                             _   _               _____       _
   |_   _|                           | | (_)             / ____|     | |
     | |  _ __  _ __   _____   ____ _| |_ ___   _____   | |  __  __ _| |_ _____      ____ _ _   _
     | | | '_ \| '_ \ / _ \ \ / / _` | __| \ \ / / _ \  | | |_ |/ _` | __/ _ \ \ /\ / / _` | | | |
    _| |_| | | | | | | (_) \ V / (_| | |_| |\ V /  __/  | |__| | (_| | ||  __/\ V  V / (_| | |_| |
   |_____|_| |_|_| |_|\___/ \_/ \__,_|\__|_| \_/ \___|   \_____|\__,_|\__\___| \_/\_/ \__,_|\__, |
                                                                                             __/ |
                                                                                            |___/
   /*----------------------------------------------------------------------------------------------------------*/
   # Transaction Approved?
   if ( $innov_response["approval"] != "") {
      $purchase_date = date("Y-m-d");

      // Build MySQL update
      // -----------------------
      $igStuff = "PAY_METHOD = 'Innovative Gateway', TRANSACTION_ID = '$QSTAR_WRITE', ";
      $igStuff .= "CC_TYPE = '$CC_TYPE', CC_NUM = '".ccsafe($CC_NUM)."', ";
      $igStuff .= "CC_DATE = '$CC_MON/$CC_YEAR', TRANSACTION_STATUS = 'Paid'";


      // Update cart_invoice table
      // vvvvvvvvvvvvvvvvvvvvvvvvvvvv
	if($nochex_return != 1){
	   	if ( !mysql_query("UPDATE cart_invoice SET $igStuff WHERE ORDER_NUMBER = '$ORDER_NUMBER'") ) {
	   	   echo lang("Could not update 'cart_invoice' table because")." ".mysql_error().". ".lang("Please contact webmaster").".\n";
	   	   exit;
	   	}
	}
   	// Count down the inventory for all products; change display status if fully depleated (zero inv).
   	include("pgm-inventory.php");

   	// Pull Invoice HTML from invoice data table for display of final invoice
   	// **************************************************************************
   	$result = mysql_query("SELECT INVOICE_HTML FROM cart_invoice WHERE ORDER_NUMBER = '$ORDER_NUMBER'");
   	$tmp = mysql_fetch_array($result);

   	$INVOICE = $tmp['INVOICE_HTML'];

   	## >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
   	## EMAIL APPROPRIATE RECIEPTS TO CUSTOMER AND WEBMASTER
   	## <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
   	//$ORDER_NUMBER = $ORDER_NUMBER;
   	include("pgm-email_notify.php");
   	/*-----------*/

   } // End if transaction approved

} // End processing Innovative Gateway

if ( $_REQUEST['DPS_FLAG'] == "1" ) {				// This is being processed as an online cc transaction via DPS

   //foreach($_REQUEST as $var=>$val){
   //   echo "var = (".$var.") val = (".$val.")<br>";
   //}
   $DPS_FLAG = 1;
   $ORDER_NUMBER = $_REQUEST['ORDER_NUMBER'];
   $CC_NUM = $_REQUEST['CC_NUM'];
   $CC_TYPE = $_REQUEST['CC_TYPE'];
   $CC_MON = $_REQUEST['CC_MON'];
   $CC_YEAR = $_REQUEST['CC_YEAR'];
   $CC_NUM = $_REQUEST['CC_NUM'];

   /*----------------------------------------------------------------------------------------------------------
                                  ___   ___  ___
                                 |   \ | _ \/ __|
                                 | |) ||  _/\__ \
                                 |___/ |_|  |___/
   /*----------------------------------------------------------------------------------------------------------*/

      /*-----------*/
      $purchase_date = date("Y-m-d");

      // Build MySQL update
      // -----------------------
      $igStuff = "PAY_METHOD = 'DPS', TRANSACTION_ID = '$TransId', ";
      $igStuff .= "CC_TYPE = '$CC_TYPE', CC_NUM = '".ccsafe($CC_NUM)."', ";
      $igStuff .= "CC_DATE = '$CC_MON/$CC_YEAR', TRANSACTION_STATUS = 'Paid'";

      //echo "<br/>igStuff(".$igStuff.")<br/>";

      // Update cart_invoice table
      // vvvvvvvvvvvvvvvvvvvvvvvvvvvv
   	if ( !mysql_query("UPDATE cart_invoice SET $igStuff WHERE ORDER_NUMBER = '$ORDER_NUMBER'") ) {
   	   echo lang("Could not update 'cart_invoice' table because")." ".mysql_error().". ".lang("Please contact webmaster").".\n";
   	   exit;
   	}

   	// Count down the inventory for all products; change display status if fully depleated (zero inv).
   	include("pgm-inventory.php");

   	// Pull Invoice HTML from invoice data table for display of final invoice
   	// **************************************************************************
   	$result = mysql_query("SELECT INVOICE_HTML FROM cart_invoice WHERE ORDER_NUMBER = '$ORDER_NUMBER'");
   	$tmp = mysql_fetch_array($result);

   	$INVOICE = $tmp['INVOICE_HTML'];

   	## >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
   	## EMAIL APPROPRIATE RECIEPTS TO CUSTOMER AND WEBMASTER
   	## <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
   	//$ORDER_NUMBER = $ORDER_NUMBER;
   	include("pgm-email_notify.php");
   	/*-----------*/

} // End processing DPS

if ( $EWAY_FLAG == "1" ) {				// This is being processed as an online cc transaction via EWAY

   /*----------------------------------------------------------------------------------------------------------
               __      _____   __
             __\ \    / /_\ \ / /
            / -_) \/\/ / _ \ V /
            \___|\_/\_/_/ \_\_|
   /*----------------------------------------------------------------------------------------------------------*/

      /*-----------*/
      $purchase_date = date("Y-m-d");

      // Build MySQL update
      // -----------------------
      $igStuff = "PAY_METHOD = 'eWAY', TRANSACTION_ID = '$AUTH_CODE', ";
      $igStuff .= "CC_TYPE = '$CC_TYPE', CC_NUM = '".ccsafe($CC_NUM)."', ";
      $igStuff .= "CC_DATE = '$CC_MON/$CC_YEAR', TRANSACTION_STATUS = 'Paid'";


      // Update cart_invoice table
      // vvvvvvvvvvvvvvvvvvvvvvvvvvvv
   	if ( !mysql_query("UPDATE cart_invoice SET $igStuff WHERE ORDER_NUMBER = '$ORDER_NUMBER'") ) {
   	   echo lang("Could not update 'cart_invoice' table because")." ".mysql_error().". ".lang("Please contact webmaster").".\n";
   	   exit;
   	}

   	// Count down the inventory for all products; change display status if fully depleated (zero inv).
   	include("pgm-inventory.php");

   	// Pull Invoice HTML from invoice data table for display of final invoice
   	// **************************************************************************
   	$result = mysql_query("SELECT INVOICE_HTML FROM cart_invoice WHERE ORDER_NUMBER = '$ORDER_NUMBER'");
   	$tmp = mysql_fetch_array($result);

   	$INVOICE = $tmp['INVOICE_HTML'];

   	## >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
   	## EMAIL APPROPRIATE RECIEPTS TO CUSTOMER AND WEBMASTER
   	## <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
   	//$ORDER_NUMBER = $ORDER_NUMBER;
   	include("pgm-email_notify.php");
   	/*-----------*/

} // End processing eWay

if ( $PAYPOINT_FLAG == "1" ) {
   //echo "Order number: [".$_SESSION['ORDER_NUMBER']."]<br>"; exit;
   /*----------------------------------------------------------------------------------------------------------*
    _____               _____        _         _     _    _   _____
   |  __ \             |  __ \      (_)       | |   | |  | | / ____|   /\
   | |__) |__ _  _   _ | |__) |___   _  _ __  | |_  | |  | || (___    /  \
   |  ___// _` || | | ||  ___// _ \ | || '_ \ | __| | |  | | \___ \  / /\ \
   | |   | (_| || |_| || |   | (_) || || | | || |_  | |__| | ____) |/ ____ \
   |_|    \__,_| \__, ||_|    \___/ |_||_| |_| \__|  \____/ |_____//_/    \_\
                  __/ |
                 |___/
   POST /cgi-bin/trans.cgi HTTP/1.0
   Content-type: application/x-www-form-urlencoded
   Content-length: 111
   https://trans.atsbank.com/cgi-bin/trans.cgi
   /*----------------------------------------------------------------------------------------------------------*/

   /*=======================================================================================*
    ___            _  _                _
   |   \  ___  __ | |(_) _ _   ___  __| |
   | |) |/ -_)/ _|| || || ' \ / -_)/ _` |
   |___/ \___|\__||_||_||_||_|\___|\__,_|

   /*=======================================================================================*/
   if ( strtoupper($scResult[0]) != "ACCEPTED" ) {
      echo "<div align=\"center\" style=\"border: 1px solid red;\">\n";
      echo lang("Unable to complete transaction").". ".lang("Your credit card has not been charged").".<br>";
      echo lang("Error").": <b>".$scResult[1]."</b><br><br>\n";
      echo "</div>\n";

      ######################################################################################
      // Show Credit Card Processing Form
      ######################################################################################
//      ob_start();
//         include("prod_paypoint_card.inc");
//         $THIS_DISPLAY .= ob_get_contents();
//      ob_end_clean();
   } else {

      ## Build MySQL update
      ##-----------------------------------------------------------------------------
      $scStuff = "SET PAY_METHOD = 'PayPoint USA', TRANSACTION_ID = '".$scResult[1]."', ";
      $scStuff .= "CC_TYPE = '".$CC_TYPE."', CC_NUM = '".ccsafe($CC_NUM)."', ";
      $scStuff .= "CC_DATE = '".$CC_MON."/".$CC_YEAR."', TRANSACTION_STATUS = 'Paid' ";
      $scStuff .= "WHERE ORDER_NUMBER = '".$ORDER_NUMBER."'";

      //echo "<hr>".$scStuff."<hr>"; exit;

      ## Display raw gateway response (for testing)
      /*******************************************************************
      echo "<b>This came back from PayPoint</b>:<br>\n";
      echo "<textarea style=\"width: 425px; height: 200px;  font-family: Courier New, courier, mono; font-size: 11px;\">";
      echo $scRez['raw'];
      echo "</textarea>\n";
      echo "<br><br>\n";
      echo "<b>Now update cart_invoice table like so</b>:<br>\n";
      echo "<textarea style=\"width: 600px; height: 50px;  font-family: Courier New, courier, mono; font-size: 11px;\">";
      echo $scStuff;
      echo "</textarea>\n";
      exit;
      /*******************************************************************/


      // Update cart_invoice table
      //vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
      if($nochex_return != 1){
	      if ( !mysql_query("UPDATE cart_invoice $scStuff") ) {
	         echo lang("Could not update 'cart_invoice' table because")." ".mysql_error().".<br> ".lang("Please contact webmaster").".\n";
	         exit;
	      }
	}


      // Count down the inventory for all products; change display status if fully depleated (zero inv).
      include("pgm-inventory.php");



      // Pull Invoice HTML from invoice data table for display of final invoice
      // **************************************************************************
      $result = mysql_query("SELECT INVOICE_HTML FROM cart_invoice WHERE ORDER_NUMBER = '$ORDER_NUMBER'");
      $tmp = mysql_fetch_array($result);

      $INVOICE = $tmp['INVOICE_HTML'];

      ## EMAIL APPROPRIATE RECIEPTS TO CUSTOMER AND WEBMASTER
      ##<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
      include("pgm-email_notify.php");


   } // End if transaction was approved/declined


} // End processing PayPoint USA

if ( $AUTHORIZE_FLAG == "1" ) {
   /*----------------------------------------------------------------------------------------------------------*
			               _   _                _                      _
			    /\        | | | |              (_)                    | |
			   /  \  _   _| |_| |__   ___  _ __ _ _______   _ __   ___| |_
			  / /\ \| | | | __| '_ \ / _ \| '__| |_  / _ \ | '_ \ / _ \ __|
			 / ____ \ |_| | |_| | | | (_) | |  | |/ /  __/_| | | |  __/ |_
			/_/    \_\__,_|\__|_| |_|\___/|_|  |_/___\___(_)_| |_|\___|\__|
   POST /cgi-bin/trans.cgi HTTP/1.0
   Content-type: application/x-www-form-urlencoded
   Content-length: 111
   https://certification.authorize.net/gateway/transact.dll
   /*----------------------------------------------------------------------------------------------------------*/

   # Collect and format involved data
   #===================================================================

//   # Target url and script
//   $scHost = "certification.authorize.net";
//   $scPath = "/gateway/transact.dll";
//
//   # Data to pass to gateway
//   $EXPDATE = "".$CC_MON."/".$CC_YEAR."";
//   $scData = array();
//   $scData['x_version'] = "3.1";
//   $scData['x_delim_data'] = "TRUE";
//   $scData['x_relay_response'] = "FALSE";
//   $scData['x_login'] = $AUTHORIZE['AN_ACCTID'];
//   $scData['x_tran_key'] = $AUTHORIZE['AN_ACCKEY'];
//   $scData['x_amount'] = $TOTAL_SALE;
//   $scData['x_card_num'] = trim($CC_NUM);
//   $scData['x_exp_date'] = $EXPDATE;
//   $scData['x_type'] = $TRAN_TYPE;
//   $scData['x_first_name'] = $CC_FNAME;
//   $scData['x_last_name'] = $CC_LNAME;
//
//   // Include socket connection class and instantiate object
//   //::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//   $fsock_class = $_SESSION['docroot_path']."/sohoadmin/program/includes/remote_actions/class-fsockit.php";
//   //echo "$fsock_class"; exit;
//   if ( !include($fsock_class) ) {
//      echo "Error: Could not include socket class!";
//      exit;
//   }
//
//   # New socket connection object
//   $scSocket = new fsockit($scHost, $scPath, $scData);
//
//   // Connect and grab result array
//   $scRez = $scSocket->curlput("https");
//
//   //echo $scRez['raw']; exit;
//
//   # Split response into line-by-line array
//   $tok = strtok($scRez['raw'], ",");
//   while( !$tok == FALSE ){
//      $scResult[] = $tok;
//      $tok = strtok(",");
//   }
//
//   # Display formatted response for testing
////   foreach ( $scResult as $line=>$val ) {
////      echo $val."<br>";
////   }
////   exit;


   /*=======================================================================================*
    ___            _  _                _
   |   \  ___  __ | |(_) _ _   ___  __| |
   | |) |/ -_)/ _|| || || ' \ / -_)/ _` |
   |___/ \___|\__||_||_||_||_|\___|\__,_|

   /*=======================================================================================*/
   if ( strtoupper($scResult[0]) != "1" ) {
      echo "<div align=\"center\" style=\"border: 1px solid red; color: red;\" class=\"text\"><br>\n";
      echo " ".lang("Unable to complete transaction").". ".lang("Your credit card has not been charged").".<br>";
      echo " ".lang("Error")." ".$scResult[2].": ".$scResult[3]."<br><br>\n";
      echo "</div>\n";


      ######################################################################################
      // Show Credit Card Processing Form
      ######################################################################################
      ob_start();
      if ( !include("prod_authorize_card.php") ) {
         echo lang("Unable to include prod_authorize_card.php"); exit;
      }
      $THIS_DISPLAY .= ob_get_contents();
      ob_end_clean();


   } else {

      //echo "Update data table now<br>";
      //echo testArray($scResult);

      ## Build MySQL update
      ##-----------------------------------------------------------------------------
      $scStuff = "SET PAY_METHOD = 'Authorize.net', TRANSACTION_ID = '".$scResult[4]."', ";
      $scStuff .= "CC_TYPE = '".$CC_TYPE."', CC_NUM = '".ccsafe($CC_NUM)."', ";
      $scStuff .= "CC_DATE = '".$CC_MON."/".$CC_YEAR."', TRANSACTION_STATUS = '".$scResult[3]."' ";
      $scStuff .= "WHERE ORDER_NUMBER = '".$ORDER_NUMBER."'";

      //echo "<hr>".$scStuff."<hr>"; exit;

      ## Display raw gateway response (for testing)
      /*******************************************************************
      echo "<b>This came back from PayPoint</b>:<br>\n";
      echo "<textarea style=\"width: 425px; height: 200px;  font-family: Courier New, courier, mono; font-size: 11px;\">";
      echo $scRez['raw'];
      echo "</textarea>\n";
      echo "<br><br>\n";
      echo "<b>Now update cart_invoice table like so</b>:<br>\n";
      echo "<textarea style=\"width: 600px; height: 50px;  font-family: Courier New, courier, mono; font-size: 11px;\">";
      echo $scStuff;
      echo "</textarea>\n";
      exit;
      /*******************************************************************/

	if($_GET['nochex'] > 1){
		$nochex_return = 1;
	} else {
	      // Update cart_invoice table
	      //vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
	      if ( !mysql_query("UPDATE cart_invoice $scStuff") ) {
	         echo lang("Could not update 'cart_invoice' table because")." ".mysql_error().".<br> ".lang("Please contact webmaster").".\n";
	         exit;
	      }
    }

      // Count down the inventory for all products; change display status if fully depleated (zero inv).
      include("pgm-inventory.php");



      // Pull Invoice HTML from invoice data table for display of final invoice
      // **************************************************************************
      $result = mysql_query("SELECT INVOICE_HTML FROM cart_invoice WHERE ORDER_NUMBER = '$ORDER_NUMBER'");
      $tmp = mysql_fetch_array($result);

      $INVOICE = $tmp['INVOICE_HTML'];

      ## EMAIL APPROPRIATE RECIEPTS TO CUSTOMER AND WEBMASTER
      ##<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
      include("pgm-email_notify.php");


   } // End if transaction was approved/declined


} // End processing Authorize.net



if ( $INTERNETSECURE_FLAG == "1" ) {

   # Collect and format involved data
   #===================================================================
   /*=======================================================================================*
    ___            _  _                _
   |   \  ___  __ | |(_) _ _   ___  __| |
   | |) |/ -_)/ _|| || || ' \ / -_)/ _` |
   |___/ \___|\__||_||_||_||_|\___|\__,_|

   /*=======================================================================================*/
   if ( strtoupper($scResult[0]) != "1" ) {
      echo "<div align=\"center\" style=\"border: 1px solid red; color: red;\" class=\"text\"><br>\n";
      echo " ".lang("Unable to complete transaction").". ".lang("Your credit card has not been charged").".<br>";
      echo " ".lang("Error")." ".$scResult[2].": ".$scResult[3]."<br><br>\n";
      echo "</div>\n";


      ######################################################################################
      // Show Credit Card Processing Form
      ######################################################################################
      ob_start();
      if ( !include("prod_internetsecure_card.php") ) {
         echo lang("Unable to include prod_internetsecure_card.php"); exit;
      }
      $THIS_DISPLAY .= ob_get_contents();
      ob_end_clean();


   } else {

      //echo "Update data table now<br>";
      //echo testArray($scResult);

      ## Build MySQL update
      ##-----------------------------------------------------------------------------
      $scStuff = "SET PAY_METHOD = 'internetsecure', TRANSACTION_ID = '".$scResult[4]."', ";
      $scStuff .= "CC_TYPE = '".$CC_TYPE."', CC_NUM = '".ccsafe($CC_NUM)."', ";
      $scStuff .= "CC_DATE = '".$CC_MON."/".$CC_YEAR."', TRANSACTION_STATUS = '".$scResult[3]."' ";
      $scStuff .= "WHERE ORDER_NUMBER = '".$ORDER_NUMBER."'";

      //echo "<hr>".$scStuff."<hr>"; exit;

      ## Display raw gateway response (for testing)
      /*******************************************************************
      echo "<b>This came back from PayPoint</b>:<br>\n";
      echo "<textarea style=\"width: 425px; height: 200px;  font-family: Courier New, courier, mono; font-size: 11px;\">";
      echo $scRez['raw'];
      echo "</textarea>\n";
      echo "<br><br>\n";
      echo "<b>Now update cart_invoice table like so</b>:<br>\n";
      echo "<textarea style=\"width: 600px; height: 50px;  font-family: Courier New, courier, mono; font-size: 11px;\">";
      echo $scStuff;
      echo "</textarea>\n";
      exit;
      /*******************************************************************/


      // Update cart_invoice table
      //vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
      if($nochex_return != 1){
		if ( !mysql_query("UPDATE cart_invoice $scStuff") ) {
			echo lang("Could not update 'cart_invoice' table because")." ".mysql_error().".<br> ".lang("Please contact webmaster").".\n";
			exit;
		}
	}


      // Count down the inventory for all products; change display status if fully depleated (zero inv).
      include("pgm-inventory.php");



      // Pull Invoice HTML from invoice data table for display of final invoice
      // **************************************************************************
      $result = mysql_query("SELECT INVOICE_HTML FROM cart_invoice WHERE ORDER_NUMBER = '$ORDER_NUMBER'");
      $tmp = mysql_fetch_array($result);

      $INVOICE = $tmp['INVOICE_HTML'];

      ## EMAIL APPROPRIATE RECIEPTS TO CUSTOMER AND WEBMASTER
      ##<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
      include("pgm-email_notify.php");


   } // End if transaction was approved/declined


} // End processing internetsecure


##############################################################################

/*--------------------------------------------------------------------*
__      __       _  _____ _
\ \    / /      (_)/ ____(_)
 \ \  / /__ _ __ _| (___  _  __ _ _ __
  \ \/ / _ \ '__| |\___ \| |/ _` | '_ \
   \  /  __/ |  | |____) | | (_| | | | |
    \/ \___|_|  |_|_____/|_|\__, |_| |_|
                             __/ |
                            |___/
/*--------------------------------------------------------------------*/

##############################################################################
### CONFIRM IF THIS IS A VERISIGN RETURN OR GATEWAY RETURN
##############################################################################

// ---------------------------------------------------------------------------
// For anything that came to us, let's kill any added slashes (PHP BI-PRODUCT)
// ---------------------------------------------------------------------------

reset($HTTP_POST_VARS);
while (list($name, $value) = each($HTTP_POST_VARS)) {
	$value = stripslashes($value);
	${$name} = $value;
}

// ---------------------------------------------------------------------------
// Verify if this is a verisign return
// ---------------------------------------------------------------------------

$PAYPRO_FLAG = 0;
$VERISIGN_CONFIRM = 0;
$PAYPAL_FLAG = 0;
$EWAY2_FLAG = 0;

// ---------------------------------------------------------------------------
// Make preperation for PayPal Inclusion
// ---------------------------------------------------------------------------

/*
if ($USER4 == "" && strlen($PAYPAL[PAYPAL_EMAIL]) > 3) {
	$USER4 = "PAYPAL_PAYMENT";
	$PNREF = "PayPal";
	$PAYPAL_FLAG = 1;
}
*/

if ($PNREF != "" && $USER4 == "VERISIGN_GATEWAY") {		// We just identified this as a verisign return post

	// Update Transaction ID and Status
	// ---------------------------------

	if (eregi("quixstar_", $USER1)) {

		$QSTAR_WRITE = eregi_replace("quixstar_", "", $USER1);
		$QSTAR_WRITE = "[".$QSTAR_WRITE."]";

	} else {

		$QSTAR_WRITE = "";

	}

	$tmp_paymethod = "VeriSign";
	$VERISIGN_CONFIRM = 1;

	if ($PAYPAL_FLAG === 1) {
		$tmp_paymethod = "PayPal";
	}

	if($nochex_return!=1){
		mysql_query("UPDATE cart_invoice SET
			TRANSACTION_ID = '$PNREF $QSTAR_WRITE',
			TRANSACTION_STATUS = 'Closed',
			PAY_METHOD = '$tmp_paymethod' WHERE ORDER_NUMBER = '$USER3'");
	}


	// -----------------------------------------------------------------------
	// Pull Invoice HTML from invoice data table for display of final invoice
	// -----------------------------------------------------------------------

	$result = mysql_query("SELECT INVOICE_HTML FROM cart_invoice WHERE ORDER_NUMBER = '$USER3'");
	$tmp = mysql_fetch_array($result);

	$INVOICE = $tmp[INVOICE_HTML];

	// Count down the inventory for all products ordered and change display status
	// if we have reached zero.

	$ORDER_NUMBER = $USER3;
	$ORDER_DATE = date("F j, Y");
	$ORDER_TIME = "";


	$_SESSION['CART_KEYID'] = $USER5;
	$_SESSION['CART_QTY'] = $USER6;
	$_SESSION['CART_SKUNO'] = $USER7;

	include("pgm-inventory.php");
	include("pgm-email_notify.php");

} // End VeriSign Return

/*--------------------------------------------------------------------------*
               __      _____   __
             __\ \    / /_\ \ / /
            / -_) \/\/ / _ \ V /
            \___|\_/\_/_/ \_\_|
/*--------------------------------------------------------------------------*/
##############################################################################
// EWAY RETURN
##############################################################################

## Make sure we don't attempt to show VeriSign Transaction ID
## for EWAY Orders
if ($EWAY2_FLAG == 1) { $VERISIGN_CONFIRM = 0; }

if($pporder == ""){
	$pporder = $_REQUEST['pporder'];
}

if ( isset($meth) && $meth == "eway" && $AUTH_CODE != '' ) {
//	if ( $_SERVER['REMOTE_ADDR'] == '70.89.253.74' ) {echo '<p>'.basename(__FILE__).': '.__LINE__.'</p>'; exit; }
	# for hackers
	if ( $_REQUEST['AUTH_CODE'] != '' ) { echo 'Invalid eWay Authorization Code'; exit; }
	if ( $_SERVER['REMOTE_ADDR'] == '70.89.253.74' ) {echo testArray($_POST).'['.$ORDER_NUMBER.']'; }

//  	$ORDER_NUMBER = $invoice;

   $qry = "UPDATE cart_invoice SET PAY_METHOD = 'eWay', TRANSACTION_STATUS = 'Paid', TRANSACTION_ID = '".$AUTH_CODE."' WHERE ORDER_NUMBER = '$ORDER_NUMBER'";   
   mysql_query($qry);

  	$result = mysql_query("SELECT INVOICE_HTML FROM cart_invoice WHERE ORDER_NUMBER = '$ORDER_NUMBER'");
  	$tmp = mysql_fetch_array($result);

  	$INVOICE = $tmp['INVOICE_HTML'];
  	
//  	echo '<textarea>'.$INVOICE.'</textarea>';
//  	if ( $_SERVER['REMOTE_ADDR'] == '70.89.253.74' ) {echo '<p>'.basename(__FILE__).': '.__LINE__.'</p>'; exit; }

  	include("pgm-inventory.php");
  	include("pgm-email_notify.php");

} // End if eway return


# eWay UK
if ( isset($meth) && ($meth == "eway_uk_paid" || $meth == "eway_uk_cancelled") && $pporder != "" ) {

   if ( $meth == "eway_uk_paid" ) {
   	if ( $_SERVER['REMOTE_ADDR'] == '70.89.253.74' ) {
   		echo testArray($_REQUEST);
   	}
   	
	   if ( $cartpref->get('ewayuk_testmode') == 'on' ) {
	   	$querystring="CustomerID=87654321&UserName=TestAccount&AccessPaymentCode=".$_REQUEST['AccessPaymentCode'];
	   } else {
	   	$querystring="CustomerID=".$cartpref->get('eway_uk_account_id')."&UserName=".$cartpref->get('eway_uk_username')."&AccessPaymentCode=".$_REQUEST['AccessPaymentCode'];
	   }
		
		if ( strtolower($cartpref->get('eway_uk_or_nz')) == 'uk' ) {
			$posturl="https://www.ewaygateway.com/Gateway/UK/Results.aspx?".$querystring;
		} else {
//			$posturl="https://www.ewaygateway.com/Gateway/UK/Results.aspx?".$querystring;
			$posturl="https://nz.ewaygateway.com/Results/".$querystring;
		}
			
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $posturl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		if (CURL_PROXY_REQUIRED == 'True') 
		{
			$proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE') ? false : true;
			curl_setopt ($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
			curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
			curl_setopt ($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
		}
		  
		function fetch_data($string, $start_tag, $end_tag) {
			$position = stripos($string, $start_tag); 		
			$str = substr($string, $position);  		
			$str_second = substr($str, strlen($start_tag)); 	
			$second_positon = stripos($str_second, $end_tag); 	
			$str_third = substr($str_second, 0, $second_positon); 		
			$fetch_data = trim($str_third);		
			return $fetch_data; 
		}
		
		$responseArr = array();
		$response = curl_exec($ch);
		$responseArr['authecode'] = fetch_data($response, '<authCode>', '</authCode>');
		$responseArr['responsecode'] = fetch_data($response, '<responsecode>', '</responsecode>');
		$responseArr['retrunamount'] = fetch_data($response, '<returnamount>', '</returnamount>');		
		$responseArr['trxnnumber'] = fetch_data($response, '<trxnnumber>', '</trxnnumber>');	
		$responseArr['trxnstatus'] = fetch_data($response, '<trxnstatus>', '</trxnstatus>'); 
		$responseArr['trxnresponsemessage'] = fetch_data($response, '<trxnresponsemessage>', '</trxnresponsemessage>');
		
		$responseArr['merchantoption1'] = fetch_data($response, '<merchantoption1>', '</merchantoption1>');
		$responseArr['merchantoption2'] = fetch_data($response, '<merchantoption2>', '</merchantoption2>');
		$responseArr['merchantoption3'] = fetch_data($response, '<merchantoption3>', '</merchantoption3>');
		$responseArr['merchantreference'] = fetch_data($response, '<merchantreference>', '</merchantreference>');
		$responseArr['merchantinvoice'] = fetch_data($response, '<merchantinvoice>', '</merchantinvoice>');   	
		
		$passed_ordernum = $responseArr['merchantreference'];
		if ( $passed_ordernum == '' ) {
			$passed_ordernum = $_GET['ordercode'];
		}
		
		if ( $_SERVER['REMOTE_ADDR'] == '70.89.253.74' ) {
			echo testArray($responseArr);
		}
			
		if ( $responseArr['trxnstatus'] == 'false' ) {
			echo '<div style="width: 700px;margin: 10% auto;paddding: 15px;backgrond-color: #ffdbdb;border: 2px solid #ff0000;text-align: center;">';
			echo '	<h1>Transaction Declined</h1><p style="font-size: 150%;font-family: Arial;">Your transaction was declined by eWay.</p>';
			echo '	<p style="text-align: center;"><strong>If you feel this is in error, please contact us and let us know.</strong><br/>';
			echo '	<a href="http://'.$_SESSION['docroot_url'].'">Redirecting back to our website in 10 seconds...</a></p>';
			echo '</div>'; 
			
			echo "<script type=\"text/javascript\">window.setTimeout(\"window.location.href='http://".$_SESSION['docroot_url']."/shopping';\", 10000);</script>";
			exit;		
		} else {
	   	mysql_query("UPDATE cart_invoice SET PAY_METHOD = 'eWay', TRANSACTION_STATUS = 'Paid' WHERE ORDER_NUMBER = '".$passed_ordernum."'");
			$qryStr = "SELECT INVOICE_HTML FROM cart_invoice WHERE FUTURE2 = '$pporder' OR ORDER_NUMBER = '".$passed_ordernum."'";
	   	$result = mysql_query($qryStr);
	//   	echo $qryStr.'<br/>['.mysql_num_rows($result).']';
	   	$tmp = mysql_fetch_array($result);
	   	include("pgm-inventory.php");
	   	include("pgm-email_notify.php");
	   }


   } else {
      $look_again = split(";", $_SESSION['CART_KEYID']);
      $reId = $look_again[0];
      mysql_query("UPDATE cart_invoice SET PAY_METHOD = 'eWay', TRANSACTION_STATUS = 'Cancelled' WHERE FUTURE2 = '$pporder'");
      header("location: http://$this_ip/shopping/pgm-more_information.php?id=$reId"); // Redirect to item description on cancel (muhahaha)
      exit;
   }
} // End if eway return


/*--------------------------------------------------------------------------*
		 ___              _        _   _
		| _ \__ _ _  _ __| |_ __ _| |_(_)___ _ _
		|  _/ _` | || (_-<  _/ _` |  _| / _ \ ' \
		|_| \__,_|\_, /__/\__\__,_|\__|_\___/_||_|
		          |__/
/*--------------------------------------------------------------------------*/
##############################################################################
// PAYSTATION RETURN
##############################################################################


if ( isset($meth) && $_REQUEST['meth'] == "paystation" ) {
$PAYSTATION_FLAG = 1;
$ORDER_NUMBER = $_REQUEST['merchant_ref'];

   if ( $_REQUEST['ec'] == "0" ) {

   	mysql_query("UPDATE cart_invoice SET PAY_METHOD = 'Paystation', TRANSACTION_STATUS = 'Paid' WHERE ORDER_NUMBER = '$ORDER_NUMBER'");

   	// -----------------------------------------------------------------------
   	// Pull Invoice HTML from invoice data table for display of final invoice
   	// -----------------------------------------------------------------------

   	$result = mysql_query("SELECT INVOICE_HTML FROM cart_invoice WHERE ORDER_NUMBER = '$ORDER_NUMBER'");
   	$tmp = mysql_fetch_array($result);

   	$INVOICE = $tmp['INVOICE_HTML'];

   	// Count down the inventory for all products ordered and change display status
   	// if we have reached zero.
   	include("pgm-inventory.php");

   	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
   	// EMAIL APPROPRIATE RECIEPTS TO CUSTOMER AND WEBMASTER
   	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
   	include("pgm-email_notify.php");


   } else {
      mysql_query("UPDATE cart_invoice SET PAY_METHOD = 'Paystation', TRANSACTION_STATUS = 'Cancelled' WHERE ORDER_NUMBER = '$ORDER_NUMBER'");

      echo "<div align=\"center\" style=\"border: 1px solid red; color: red;\" class=\"text\"><br>\n";
      echo " ".lang("Unable to complete transaction").". ".lang("Your credit card has not been charged").".<br>";
      echo " ".lang("Error")." ".$_REQUEST['ec'].": ".$_REQUEST['em']."<br><br>\n";
      echo "</div>\n";

      //header("location:http://$this_ip/shopping"); // Redirect to shopping on cancel (muhahaha)
      //exit;
   }



} // End if paystation return


/*--------------------------------------------------------------------------*
       ___           ___
      | _ \__ _ _  _| _ \_ _ ___
      |  _/ _` | || |  _/ '_/ _ \
      |_| \__,_|\_, |_| |_| \___/
                |__/
/*--------------------------------------------------------------------------*/
##############################################################################
// PAYPRO RETURN
##############################################################################


if ( isset($meth) && $meth == "paypro" ) {
   $PAYPRO_FLAG = 1;

   if ( $status == "paid" ) {
      //echo "Order # (".$ORDER_NUMBER.")";
      //exit;
      $ORDER_NUMBER = $_POST['MerchantOrderNo'];
      //$ORDER_NUMBER = $invoice;

   	mysql_query("UPDATE cart_invoice SET PAY_METHOD = 'PayPro', TRANSACTION_STATUS = 'Paid' WHERE ORDER_NUMBER = '$ORDER_NUMBER'");

   	// -----------------------------------------------------------------------
   	// Pull Invoice HTML from invoice data table for display of final invoice
   	// -----------------------------------------------------------------------

   	$result = mysql_query("SELECT INVOICE_HTML FROM cart_invoice WHERE ORDER_NUMBER = '$ORDER_NUMBER'");
   	$tmp = mysql_fetch_array($result);

   	$INVOICE = $tmp['INVOICE_HTML'];

   	// Count down the inventory for all products ordered and change display status
   	// if we have reached zero.
   	include("pgm-inventory.php");

   	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
   	// EMAIL APPROPRIATE RECIEPTS TO CUSTOMER AND WEBMASTER
   	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
   	include("pgm-email_notify.php");


   } else {
      $ORDER_NUMBER = $_POST['MerchantOrderNo'];
      mysql_query("UPDATE cart_invoice SET PAY_METHOD = 'PayPro', TRANSACTION_STATUS = 'Cancelled' WHERE ORDER_NUMBER = '$ORDER_NUMBER'");
      header("location:http://$this_ip/shopping/pgm-more_information.php?id=$reId"); // Redirect to item description on cancel (muhahaha)
      exit;
   }



} // End if PayPro return


if ( $_REQUEST['todo'] == 'transactium_success' ) {
	if ( ($_SESSION['HPSID'] != $_REQUEST['hpsid']) || $_SESSION['HPSID'] == ''  ) {
		echo 'Session ids do not match, or other HPSID Transactium error. You may not reload this page.'; exit;
	}
  if($ORDER_NUMBER == ''){
  	$ORDER_NUMBER = $_POST['invoice'];
    if(strlen($ORDER_NUMBER) < 2){
    	$ORDER_NUMBER = $_SESSION['ORDER_NUMBER'];
    }
  }
//  if ( $_SERVER['REMOTE_ADDR'] == '70.89.253.74' ) {echo '<p>'.basename(__FILE__).': '.__LINE__.'</p>'; }

 	mysql_query("UPDATE cart_invoice SET PAY_METHOD = 'Transactium', TRANSACTION_STATUS = 'Paid' WHERE ORDER_NUMBER = '$ORDER_NUMBER'");

 	$result = mysql_query("SELECT INVOICE_HTML FROM cart_invoice WHERE ORDER_NUMBER = '$ORDER_NUMBER'");
 	$tmp = mysql_fetch_array($result);

 	$INVOICE = $tmp['INVOICE_HTML'];

 	include("pgm-inventory.php");
	$cust_email = $tmp['BILLTO_EMAILADDR'];
 	include("pgm-email_notify.php");
}


/*--------------------------------------------------------------------------*
  _____            _____      _
 |  __ \          |  __ \    | |
 | |__) |_ _ _   _| |__) |_ _| |
 |  ___/ _` | | | |  ___/ _` | |
 | |  | (_| | |_| | |  | (_| | |
 |_|   \__,_|\__, |_|   \__,_|_|
              __/ |
             |___/
/*--------------------------------------------------------------------------*/
##############################################################################
// PAYPAL RETURN
##############################################################################

## Make sure we don't attempt to show VeriSign Transaction ID
## for PayPal Orders
if ($PAYPAL_FLAG == 1) { $VERISIGN_CONFIRM = 0; }

if ( isset($meth) && $meth == "paypal" && $pporder != "" ) {

	if ( $status == "paid" ) {
	  if($ORDER_NUMBER == ''){
	  	$ORDER_NUMBER = $_POST['invoice'];
	    if(strlen($ORDER_NUMBER) < 2){
	    	$ORDER_NUMBER = $_SESSION['ORDER_NUMBER'];
	    }
	  }

   	mysql_query("UPDATE cart_invoice SET PAY_METHOD = 'PayPal', TRANSACTION_STATUS = 'Paid' WHERE ORDER_NUMBER = '$ORDER_NUMBER'");

   	// -----------------------------------------------------------------------
   	// Pull Invoice HTML from invoice data table for display of final invoice
   	// -----------------------------------------------------------------------

   	$result = mysql_query("SELECT INVOICE_HTML FROM cart_invoice WHERE ORDER_NUMBER = '$ORDER_NUMBER'");
   	$tmp = mysql_fetch_array($result);

   	$INVOICE = $tmp['INVOICE_HTML'];

   	// Count down the inventory for all products ordered and change display status
   	// if we have reached zero.
   	include("pgm-inventory.php");
		$cust_email = $tmp['BILLTO_EMAILADDR'];
   	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
   	// EMAIL APPROPRIATE RECIEPTS TO CUSTOMER AND WEBMASTER
   	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
   	include("pgm-email_notify.php");


   } else {
      $look_again = split(";", $_SESSION['CART_KEYID']);
      $reId = $look_again[0];
      mysql_query("UPDATE cart_invoice SET PAY_METHOD = 'PayPal', TRANSACTION_STATUS = 'Cancelled' WHERE FUTURE2 = '$pporder'");
      header("location:http://$this_ip/shopping/pgm-more_information.php?id=$reId"); // Redirect to item description on cancel (muhahaha)
      exit;
   }



} // End if PayPal return

eval(hook("pgm-show_invoice.php:gateway_catch"));


/*--------------------------------------------------------------------------*
                _                      _
               | |                    (_)
  ___ _   _ ___| |_ ___  _ __ ___      _ _ __   ___
 / __| | | / __| __/ _ \| '_ ` _ \    | | '_ \ / __|
| (__| |_| \__ \ || (_) | | | | | |  _| | | | | (__
 \___|\__,_|___/\__\___/|_| |_| |_| (_)_|_| |_|\___|

/*--------------------------------------------------------------------------*/
##############################################################################
// CUSTOM PHP INCLUDE RETURN
##############################################################################
if ( $EWAY_FLAG != 1 && $nochex_return != 1 && $AUTHORIZE_FLAG != 1 && $PAYPOINT_FLAG != 1 && $VERISIGN_CONFIRM != 1 && $OFFLINE_FLAG != 1 && $pporder == "" && $INNOVGATE_FLAG != "1" && $PAYPRO_FLAG != "1" && $DPS_FLAG != "1" && $PAYSTATION_FLAG != 1) {		// This must be a gateway include return huh?

	// Update Transaction ID and Status
	// ---------------------------------

	if (eregi("quixstar_", $customernumber)) {

		$QSTAR_WRITE = eregi_replace("quixstar_", "", $customernumber);
		$QSTAR_WRITE = "[".$QSTAR_WRITE."]";

	} else {

		$QSTAR_WRITE = "";

	}

	mysql_query("UPDATE cart_invoice SET
		TRANSACTION_ID = '$TRANSACTION_ID $QSTAR_WRITE',
		TRANSACTION_STATUS = 'Closed',
		PAY_METHOD = '$PAY_METHOD' WHERE ORDER_NUMBER = '$ORDER_NUMBER'");

	// -----------------------------------------------------------------------
	// Pull Invoice HTML from invoice data table for display of final invoice
	// -----------------------------------------------------------------------

	$result = mysql_query("SELECT INVOICE_HTML FROM cart_invoice WHERE ORDER_NUMBER = '$ORDER_NUMBER'");
	$tmp = mysql_fetch_array($result);

	$INVOICE = $tmp[INVOICE_HTML];

	// Count down the inventory for all products ordered and change display status
	// if we have reached zero.

	include("pgm-inventory.php");

} // End gateway include return


if($nochex_return == 1){
      // Count down the inventory for all products; change display status if fully depleated (zero inv).
      include("pgm-inventory.php");
      // Pull Invoice HTML from invoice data table for display of final invoice
      // **************************************************************************
      $result = mysql_query("SELECT INVOICE_HTML FROM cart_invoice WHERE ORDER_NUMBER = '$ORDER_NUMBER'");
      $tmp = mysql_fetch_array($result);

      $INVOICE = $tmp['INVOICE_HTML'];

      ## EMAIL APPROPRIATE RECIEPTS TO CUSTOMER AND WEBMASTER
      ##<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
      include("pgm-email_notify.php");			
}

############################################################################################################################################################
## --------------------------------------------------------------------------------------------------------------------------------------------------------
### SHOW INVOICE AND FINALIZE ORDER
## --------------------------------------------------------------------------------------------------------------------------------------------------------
############################################################################################################################################################

// ----------------------------------------------------------
// DISPLAY CHECKOUT ROUTINE STEPS FOR REFERENCE BY CUSTOMER
// ----------------------------------------------------------
$DISPLAY_HEADER .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\" class=\"shopping-selfcontained_box\" id=\"checkout-steps\">\n";

//    # Testing - echo Step number
//    $DISPLAY_HEADER .= " <tr><td colspan=\"6\" align=\"center\">Step = (".$STEP.")</td></tr>\n";

$DISPLAY_HEADER .= " <tr>\n";
$DISPLAY_HEADER .= "  <td align=\"center\" valign=\"top\">\n";
$DISPLAY_HEADER .= "   ".lang("Step")." 1:<br/>".lang("Customer Sign-in")."\n";
$DISPLAY_HEADER .= "  </td>\n";

$DISPLAY_HEADER .= "  <td align=\"center\" valign=\"top\">\n";
$DISPLAY_HEADER .= lang("Step")." 2:<br/>".lang("Billing & Shipping")."<br/>".lang("Information")."\n";
$DISPLAY_HEADER .= "  </td>\n";

$DISPLAY_HEADER .= "  <td align=\"center\" valign=\"top\">\n";
$DISPLAY_HEADER .= lang("Step")." 3:<br/>".lang("Shipping Options")."\n";
$DISPLAY_HEADER .= "  </td>\n";

$DISPLAY_HEADER .= "  <td align=\"center\" valign=\"top\">\n";
$DISPLAY_HEADER .= lang("Step")." 4:<br/>".lang("Verify Order Details")."<br/>\n";
$DISPLAY_HEADER .= "  </td>\n";

$DISPLAY_HEADER .= "  <td align=\"center\" valign=\"top\">\n";
$DISPLAY_HEADER .= lang("Step")." 5:<br/>".lang("Make Payment")."\n";
$DISPLAY_HEADER .= "  </td>\n";

# Current Step
$DISPLAY_HEADER .= "  <th align=\"center\" valign=\"top\">\n";
$DISPLAY_HEADER .= lang("Step")." 6:<br/>".lang("Print Final")."<br/>".lang("Invoice")."\n";
$DISPLAY_HEADER .= "  </th>\n";

$DISPLAY_HEADER .= " </tr>\n";
$DISPLAY_HEADER .= "</table>\n";

# Print this Page Now
$DISPLAY_HEADER .= "<BR><DIV ALIGN=RIGHT><FONT SIZE=1 FACE=ARIAL class=text><A href=\"#\" onclick=\"window.open('pgm-print_invoice.php?invoice_num=".$ORDER_NUMBER."','print_inv','width=800px, height=600px, resizable');\">".lang("Print this Page Now")."</A></FONT></DIV>\n";

// ----------------------------------------------------------
// DISPLAY BUSINESS INFORMATION
// ----------------------------------------------------------

$THIS_DISPLAY = "";
$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=4 CELLSPACING=0 WIDTH=100% CLASS=text>\n";
$THIS_DISPLAY .= "<TR><TD ALIGN=LEFT VALIGN=TOP class=text WIDTH=50%>\n";

	$THIS_DISPLAY .= "$OPTIONS[BIZ_PAYABLE]<BR>\n";
	$THIS_DISPLAY .= "$OPTIONS[BIZ_ADDRESS_1]<BR>\n";
		if ($OPTIONS[BIZ_ADDRESS_2] != "") {
			$THIS_DISPLAY .= "$OPTIONS[BIZ_ADDRESS_2]<BR>";
		}
	$THIS_DISPLAY .= "$OPTIONS[BIZ_CITY], $OPTIONS[BIZ_STATE]  $OPTIONS[BIZ_POSTALCODE]<BR>\n";
	$THIS_DISPLAY .= "$OPTIONS[BIZ_PHONE]</FONT>\n";

$THIS_DISPLAY .= "</TD><TD ALIGN=LEFT VALIGN=TOP class=text WIDTH=50%>\n";

$THIS_DISPLAY .= "<BR><B>".lang("Order Date")."</B>: <font size=3><TT>$ORDER_DATE &nbsp;&nbsp;$ORDER_TIME</TT></FONT>\n";
$THIS_DISPLAY .= "<BR><B>".lang("Order Number")."</B>: <font size=3><TT>$ORDER_NUMBER</TT></FONT> &nbsp;";

if ($VERISIGN_CONFIRM == 1) {
	$THIS_DISPLAY .= "<BR><B>".lang("VeriSign")."<SUP>TM</SUP> Ref #</B>: <font size=3><TT>$PNREF</TT></FONT> &nbsp;";
}

$THIS_DISPLAY .= "</TD>\n";
$THIS_DISPLAY .= "</TR></TABLE>\n\n";

// ----------------------------------------------------------
// DISPLAY CUSTOM THANK YOU MESSAGE (SET IN BUSINESS OPTS)
// ----------------------------------------------------------

$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=4 CELLSPACING=0 WIDTH=100% CLASS=text>\n";
$THIS_DISPLAY .= "<TR><TD ALIGN=LEFT VALIGN=TOP class=text>\n";

$THIS_DISPLAY .= $OPTIONS[BIZ_INVOICE_HEADER];

$THIS_DISPLAY .= "</TD></TR></TABLE><BR>\n";

// --------------------------------------------------------------
// DOES THIS INVOICE DISPLAY HAVE A PHP INC ASSOCIATED WITH IT?
// --------------------------------------------------------------

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// DEVNOTE:
//
// This is where you can add a custom include file so that if
// you need to do some type of calculation to present to your
// customer based on a product purchase you can do so.
//
// For instance, you may want to generate a un/pw
// to be utilized in a download scenario instead of just letting
// the download happen via this invoice. You could set the
// download flag to "off" and assign a un/pw to the sec_codes
// data table and allow the download from a specific page that
// you have created within the site.  Remember, the security
// codes can be set to expire!  Or, you may simply want to
// database the information into a data table for use some other
// use such as statistics reporting of sku data, etc... Tons of
// flexibility here.
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if ($OPTIONS['INVOICE_INCLUDE'] != "" && $disOrder != "Cancelled" ) {

	ob_start();
		include("../media/".$OPTIONS['INVOICE_INCLUDE']);
		$THIS_DISPLAY .= ob_get_contents();
	ob_end_clean();

}

/*---------------------------------------------------------------------------------------------------------*
 ___                 _            _  _  _____  __  __  _
|_ _| _ _ __ __ ___ (_) __  ___  | || ||_   _||  \/  || |
 | | | ' \\ V // _ \| |/ _|/ -_) | __ |  | |  | |\/| || |__
|___||_||_|\_/ \___/|_|\__|\___| |_||_|  |_|  |_|  |_||____|

# Display final invoice html
/*---------------------------------------------------------------------------------------------------------*/
$this_invoice = eregi_replace("#EFEFEF", "WHITE", $INVOICE);	// For this display, kill grey bg color tag in table for print.

$THIS_DISPLAY .= $this_invoice;

// ----------------------------------------------------------
// SHOW CREDIT CARD INFORMATION (XXXX) FOR VERIFICATION
// TAKES THE LEFT COLUMN OF TWO COLUMN PLACEMENT FOR FILE
// DOWNLOAD IF THE DOWNLOAD EXISTS // ----------------------------------------------------------

$THIS_DISPLAY .= "<BR><TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100% CLASS=text>\n";
$THIS_DISPLAY .= "<TR>\n";


// ----------------------------------------------------------
// This routine is only needed for offline processing
// ----------------------------------------------------------

if ($OFFLINE_FLAG == "1") {

	$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=TOP class=text width=30%>\n";
	$THIS_DISPLAY .= "<B><U>".lang("Payment Method")."</U></B>:<BR><BR>$CC_TYPE\n";

	$THIS_DISPLAY .= "<SCRIPT Language=\"JavaScript\">\n\n";
	$THIS_DISPLAY .= "     var astring=\":aAb`BcVCd/eXDfEYg FZhi?jGk|HlmI,nJo@TKpqL.WMrsNt!uvwOx<yPz>0QR12~3S4;^567U89%$#*()-_=+È‚‰Â‡ÁÍÎÓÏ≈…Ê∆ÙˆÚ˚˘÷‹¢£•É·Ì«¸Ò—™∫ø¨Ωº°´ª¶'\";\n\n";
	$THIS_DISPLAY .= "     function encrypt(lstring){ \n";
	$THIS_DISPLAY .= "     		retstr=\"\" \n";
	$THIS_DISPLAY .= "     		for (var i=0;i<lstring.length;i++){ \n";
	$THIS_DISPLAY .= "	     		aNum=astring.indexOf(lstring.substring(i,i+1),0) \n";
	$THIS_DISPLAY .= "         		aNum=aNum^25 \n";
	$THIS_DISPLAY .= "         		retstr=retstr+astring.substring(aNum,aNum+1)} \n";
	$THIS_DISPLAY .= "    		return retstr \n";
	$THIS_DISPLAY .= "     } \n\n";

	$THIS_DISPLAY .= "     var tmp = \"".$VERIFY_CCNUM_CLASS."\"; \n";
	$THIS_DISPLAY .= "     var atmp = \"".$CC_NUM."\"; \n";
	$THIS_DISPLAY .= "     var ccnum_display = encrypt(tmp); \n";
	$THIS_DISPLAY .= "     var ccnum_adisplay = encrypt(atmp); \n";

	$THIS_DISPLAY .= "     var full_len = ccnum_display.length; \n";
	$THIS_DISPLAY .= "     var tmp = full_len - 4; \n";
	$THIS_DISPLAY .= "     var final_dis = \"\";\n";
	$THIS_DISPLAY .= "	   for (x=1;x<=tmp;x++) { var final_dis = final_dis + \"X\"; }\n";
	//$THIS_DISPLAY .= "     var ccnum_display = final_dis + ccnum_display.substring(tmp,full_len); \n";

	//$THIS_DISPLAY .= "alert('ccnum_display: ('+ccnum_display+')\\nccnum_adisplay: ('+ccnum_adisplay+')\\ncfull_len: ('+full_len+')')\n";

	//$THIS_DISPLAY .= "     document.write(\"<BR>\"+ccnum_display); \n";
	$THIS_DISPLAY .= "</SCRIPT> \n\n";

   # Format cc num for display
   $cc_chop = strlen($VERIFY_CCNUM_CLASS) - 4;
   $cc_postfix = substr($VERIFY_CCNUM_CLASS, -4);
   $cc_prefix = eregi_replace("[0-9]", "X", substr($VERIFY_CCNUM_CLASS, 0, $cc_chop));
   $cc_display = $cc_prefix.$cc_postfix;

	$THIS_DISPLAY .= "<BR>".$cc_display."\n\n";
	$THIS_DISPLAY .= "<BR>$CC_MON/$CC_YEAR<BR><BR>\n\n";
	$THIS_DISPLAY .= "</TD>\n\n";

}


$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=TOP class=text width=70%>\n";
/*---------------------------------------------------------------------------------------------------------*
 ___  _  _        ___                      _                _
| __|(_)| | ___  |   \  ___ __ __ __ _ _  | | ___  __ _  __| |
| _| | || |/ -_) | |) |/ _ \\ V  V /| ' \ | |/ _ \/ _` |/ _` |
|_|  |_||_|\___| |___/ \___/ \_/\_/ |_||_||_|\___/\__,_|\__,_|

# If any line items are a file to be downloaded, place link
# here to download the file now.
/*---------------------------------------------------------------------------------------------------------*/
$tmp = split(";", $_SESSION['CART_SKUNO']);	// Split the sku number data into array (from shopping cart)
$tmp_cnt = count($tmp);			// How many line items are here?

for ($x=0;$x<=$tmp_cnt;$x++) {

	if ($tmp[$x] != "") {		// Don't do blanks (remember we always have an extra with this system

		$result = mysql_query("SELECT OPTION_DOWNLOADFILE, PROD_NAME FROM cart_products WHERE PROD_SKU = '$tmp[$x]'");
		$exist_flag = mysql_num_rows($result);

		if ($exist_flag > 0) {
			$filename = mysql_fetch_array($result);
		}

	} // End Blank Check

	if ( strlen($filename[OPTION_DOWNLOADFILE]) > 4 && $DOWNLOAD_AVAIL != "NO") {	// Place inside of loop, there might be multiple file downloads available

		$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=4 CELLSPACING=0 WIDTH=100% ALIGN=CENTER CLASS=text STYLE='border: 1px inset black;' bgcolor=WHITE>\n";
		$THIS_DISPLAY .= "<TR><TD ALIGN=CENTER VALIGN=TOP class=text><B>".lang("The Product")." <U>".$filename['PROD_NAME']."</U><BR>".lang("is available to download now")."</B>:<BR>\n";
		$THIS_DISPLAY .= "<A HREF=\"../media/".$filename['OPTION_DOWNLOADFILE']."\" target=\"_blank\"><img src=\"../shopping/download_button.gif\" width=127 height=22 hspace=0 vspace=5 border=0></a><BR CLEAR=ALL>\n";
		$THIS_DISPLAY .= "<FONT SIZE=1><U>".lang("Filename")."</U>: [$filename[OPTION_DOWNLOADFILE]]<BR><BR><I>".lang("To download and save the file to your hard-drive, 'Right-Click' on Download Button and select 'Save Target As...'.")." ".lang("When the save dialog appears, make sure you")." \n";
		$THIS_DISPLAY .= lang("remember where you save the file on your hard drive.")."  ".lang("You will also receive an HTML email receipt of this invoice that contains this link as well in case")." \n";
		$THIS_DISPLAY .= lang("you encounter connection problems downloading the file now.")."\n";
		$THIS_DISPLAY .= "</TD></TR></TABLE><BR>\n";
		$filename[OPTION_DOWNLOADFILE] = "";	// Reset var for next loop pass

	}

} // End For Loop

$THIS_DISPLAY .= "</TD></TR></TABLE>\n\n";

// ----------------------------------------------------------
// DISPLAY SHIPPING/RETURNS POLICIES JUST FOR CUSTOMER FYI
// ----------------------------------------------------------

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// DISPLAY SHIPPING POLICY #1
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	# Show Shipping Info on Invoice?
	if ( $cartpref->get("disable_shipping") != "yes" && $shipping_policy_definedBool ) {
		$THIS_DISPLAY .= "<BR>";
		$THIS_DISPLAY .= "<TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=5 class=smtext STYLE='border: inset BLACK 1px;'>\n";
		$THIS_DISPLAY .= "<TR> \n";
		$THIS_DISPLAY .= "<TD BGCOLOR='$OPTIONS[DISPLAY_HEADERBG]'><FONT COLOR=$OPTIONS[DISPLAY_HEADERTXT]><B><FONT FACE=\"Verdana, Arial, Helvetica, sans-serif\">".lang("Shipping Information")."</FONT></B></FONT></TD>\n";
		$THIS_DISPLAY .= "</TR>\n";
		$THIS_DISPLAY .= "<TR> \n";
		$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=TOP>\n";
	
		// ------------------------------------------
		// Read Shipping shipping policy
		// ------------------------------------------
	
		$filename = "$cgi_bin/cart_delivery.txt";
		if (file_exists($filename)) {
			$file = fopen("$filename", "r") or DIE(lang("Error").": ".lang("Could not open shipping policy")." ($filename)");
				$SHIPPING_POLICY = fread($file,filesize($filename));
			fclose($file);
		}
	
		// ------------------------------------------
		// Format textarea data to display as HTML
		// ------------------------------------------
	
		$SHIPPING_POLICY = chop($SHIPPING_POLICY);
		$SHIPPING_POLICY = str_replace("\n", "<BR>", $SHIPPING_POLICY);
	
		// ------------------------------------------
		// Add Policy Text to $THIS_DISPLAY
		// ------------------------------------------
	
		$THIS_DISPLAY .= $SHIPPING_POLICY;
	
	
		$THIS_DISPLAY .= "</TD></TR></TABLE>\n\n";
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// DISPLAY RETURNS/EXCHANGES POLICY #2
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	if ( $return_policy_definedBool ) {
		$THIS_DISPLAY .= "<BR><TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=5 class=smtext STYLE='border: inset BLACK 1px;'>\n";
		$THIS_DISPLAY .= "<TR> \n";
		$THIS_DISPLAY .= "<TD BGCOLOR='$OPTIONS[DISPLAY_HEADERBG]'><FONT COLOR=$OPTIONS[DISPLAY_HEADERTXT]><B><FONT FACE=\"Verdana, Arial, Helvetica, sans-serif\">".lang("Returns & Exchanges")."</FONT></B></FONT></TD>\n";
		$THIS_DISPLAY .= "</TR>\n";
		$THIS_DISPLAY .= "<TR> \n";
		$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=TOP>\n";
	
		// ------------------------------------------
		// Read Policy Statement into Memory
		// ------------------------------------------
	
		$filename = "$cgi_bin/cart_returns.txt";
		if (file_exists($filename)) {
			$file = fopen("$filename", "r") or DIE(lang("Error").": ".lang("Could not open returns policy")." ($filename)");
				$RETURNS_POLICY = fread($file,filesize($filename));
			fclose($file);
		}
	
		// ------------------------------------------
		// Format textarea data to display as HTML
		// ------------------------------------------
	
		$RETURNS_POLICY = chop($RETURNS_POLICY);
		$RETURNS_POLICY = str_replace("\n", "<BR>", $RETURNS_POLICY);
	
		// ------------------------------------------
		// Add Policy Text to $THIS_DISPLAY
		// ------------------------------------------
	
		$THIS_DISPLAY .= $RETURNS_POLICY;
	
		$THIS_DISPLAY .= "</TD></TR></TABLE>\n\n";
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// DISPLAY "OTHER POLICIES" IF EXISTS #3
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	$filename = "$cgi_bin/other_policies.txt";
	if (file_exists($filename)) {

		// ------------------------------------------
		// Read Policy Statement into Memory
		// ------------------------------------------

		$file = fopen("$filename", "r") or DIE(lang("Error").": ".lang("Could not open other policies")." ($filename)");
			$OTHER_POLICY = fread($file,filesize($filename));
		fclose($file);

		if (strlen($OTHER_POLICY) > 10) {	// Other Policy Statement should contain more than 10 chars
			$THIS_DISPLAY .= "<br/>\n";
			$THIS_DISPLAY .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" class=smtext style='border: inset BLACK 1px;'>\n";
			$THIS_DISPLAY .= " <tr> \n";
			$THIS_DISPLAY .= "  <td bgcolor='".$OPTIONS['DISPLAY_HEADERBG']."'>\n";
			$THIS_DISPLAY .= "   <font color=$OPTIONS[DISPLAY_HEADERTXT]><b><font face=\"verdana, Arial, Helvetica, sans-serif\">".$cartpref->get("other_policy_title")."</font></b></font>\n";
			$THIS_DISPLAY .= "  </td>\n";
			$THIS_DISPLAY .= " </tr>\n";
			$THIS_DISPLAY .= " <tr> \n";
			$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\">\n";

			# Format textarea data to display as HTML
			$OTHER_POLICY = chop($OTHER_POLICY);
			$OTHER_POLICY = str_replace("\n", "<br/>", $OTHER_POLICY);

			# Add Policy Text to $THIS_DISPLAY
			$THIS_DISPLAY .= $OTHER_POLICY;

			$THIS_DISPLAY .= "  </td>\n";
			$THIS_DISPLAY .= " </tr>\n";
			$THIS_DISPLAY .= "</table>\n\n";

		} // End if More than 10 Chars

	} // End if File Exists

// ----------------------------------------------------------

$THIS_DISPLAY .= "<br><br><center><H2>".lang("Thank You")."!</H2></center>&nbsp;";


##########################################################################

//	#######   ###    ##    ####
// 	##        ####   ##    ## ##
//	##        ## ##  ##    ##  ##
// 	####      ##  ## ##    ##   ##
// 	##        ##   ####    ##  ##
// 	##        ##    ###    ## ##
// 	#######   ##     ##    ####

##########################################################################
### BUILD OVERALL TABLE TO PLACE FINAL OUTPUT WITHIN
##########################################################################

$FINAL_DISPLAY = "<TABLE BORDER=0 CELLPADDING=2 CELLSPACING=0 WIDTH=612 ALIGN=CENTER>\n";
$FINAL_DISPLAY .= "<TR>\n";
$FINAL_DISPLAY .= "<TD ALIGN=CENTER VALIGN=TOP>\n\n$DISPLAY_HEADER $THIS_DISPLAY\n\n</TD>\n";
$FINAL_DISPLAY .= "</TR>\n\n";

// ----------------------------------------------------------------------------------
// If a business address has been supplied, display at the footer of each shopping
// cart page.  This can be removed if you wish, but studies have shown it instills
// trust among consumers that wish to buy from this web site
// ----------------------------------------------------------------------------------

if ($OPTIONS[BIZ_ADDRESS_1] != "" && $OPTIONS[BIZ_POSTALCODE] != "") {

	$FINAL_DISPLAY .= "<TR><TD ALIGN=CENTER VALIGN=MIDDLE CLASS=smtext>\n";
	$FINAL_DISPLAY .= "<HR WIDTH=100% STYLE='height: 1px; color: $OPTIONS[DISPLAY_HEADERBG];'>\n".lang("Mailing Address").": $OPTIONS[BIZ_ADDRESS_1], ";

		if ($OPTIONS[BIZ_ADDRESS2] != "") {
			$FINAL_DISPLAY .= "$OPTIONS[BIZ_ADDRESS_2], ";
		}

	$FINAL_DISPLAY .= "$OPTIONS[BIZ_CITY], $OPTIONS[BIZ_STATE], $OPTIONS[BIZ_POSTALCODE]\n<HR WIDTH=100% STYLE='height: 1px; color: $OPTIONS[DISPLAY_HEADERBG];'>";
	$FINAL_DISPLAY .= "</TD></TR>\n\n";

}

// ----------------------------------------------------------------------------------

$FINAL_DISPLAY .= "</TABLE>";


###########################################################################
### THE pgm-realtime_builder.php FILE COMPILES THE TEMPLATE DATA AND PAGE
### CONTENT DATA TOGETHER AND PUTS IT OUT AS THE $template_header AND
### $template_footer VARS RESPECTIVELY.  ANY MODIFICATION TO CHANGE THE
### WAY PAGES ARE OUTPUT TO THE SITE VISITOR SHOULD BE MADE WITHIN THE
### realtime_builder.php FILE
###########################################################################

$module_active = "yes";
include ("pgm-template_builder.php");

#######################################################

echo ("$template_header\n");

	$template_footer = eregi_replace("#CONTENT#", $FINAL_DISPLAY, $template_footer);

echo ("$template_footer\n\n");

echo ("\n\n<SCRIPT language=Javascript>\n     window.focus();\n</SCRIPT>\n\n");

eval(hook("pgm-show_invoice.php:bottom-of-output"));

if($_SESSION['ORDER_NUMBER'] != ''){
	$fnl_disp = $template_header."\n";
	$fnl_disp .= $template_footer."\n";
	$fnl_disp .= "\n<SCRIPT language=Javascript>\n     window.focus();\n</SCRIPT>\n";
	$_SESSION['final_display_reload'] = '';
	$_SESSION['final_display_reload'] = $fnl_disp;
}

// Clear cart data since items are bought and paid for
// ----------------------------------------------------
$cart_vars = array();
$cart_vars[] = "CART_KEYID";
$cart_vars[] = "CART_SKUNO";
$cart_vars[] = "CART_CATNO";
$cart_vars[] = "CART_PRODNAME";
$cart_vars[] = "CART_SUBCAT";
$cart_vars[] = "CART_FORMDATA";
$cart_vars[] = "CART_VARNAME";
$cart_vars[] = "CART_UNITPRICE";
$cart_vars[] = "CART_QTY";
$cart_vars[] = "CART_UNITSUBTOTAL";
$cart_vars[] = "ORDER_NUMBER";
$cart_vars[] = "ORDER_TIME";
$cart_vars[] = "ORDER_TOTAL";
$cart_vars[] = "SHIPPING_TOTAL";
$cart_vars[] = "INVOICE";
$cart_vars[] = "BFIRSTNAME";
$cart_vars[] = "BLASTNAME";
$cart_vars[] = "BCOMPANY";
$cart_vars[] = "BADDRESS1";
$cart_vars[] = "BADDRESS2";
$cart_vars[] = "BCITY";
$cart_vars[] = "BZIPCODE";
$cart_vars[] = "BSTATE";
$cart_vars[] = "BCOUNTRY";
$cart_vars[] = "BPHONE";
$cart_vars[] = "BEMAILADDRESS";
$cart_vars[] = "SFIRSTNAME";
$cart_vars[] = "SLASTNAME";
$cart_vars[] = "SCOMPANY";
$cart_vars[] = "SADDRESS1";
$cart_vars[] = "SADDRESS2";
$cart_vars[] = "SCITY";
$cart_vars[] = "SZIPCODE";
$cart_vars[] = "SSTATE";
$cart_vars[] = "SCOUNTRY";
$cart_vars[] = "SPHONE";
$cart_vars[] = "ORDER_DATE";

# Clear session data related to this order (don't do for VeriSign because it seems to break the transaction return processing stuff)
//if ( $USER4 != "VERISIGN_GATEWAY" ) {
   foreach ( $cart_vars as $key=>$var ) {
      unset($_SESSION[$var]);
   }
//}

//echo testArray($_SESSION);

exit;


?>