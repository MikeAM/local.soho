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

error_reporting('341');
session_cache_limiter('none');
session_start();
include_once("pgm-cart_config.php");
error_reporting('341');

if ( $_SESSION['ORDER_NUMBER'] == '' && $_SESSION['final_display_reload'] != '' && $_REQUEST['PAY_TYPE'] == "CHECK" ) {
	echo $_SESSION['final_display_reload'];
	exit;
}
//echo '<h1>_POST</h1>'.testArray($_POST); exit;

$PAY_TYPE = $_REQUEST['PAY_TYPE'];


# Un-comment for testing
//echo "PAY_TYPE == (".$PAY_TYPE.")<br>\n";

$THIS_DISPLAY = "";
$updated_text = "";

##########################################################################
//   NOTE - 07.20.2004
//
//   Used in combination with a little re-registering action (below),
//   the 'unset' prevents the lang array from getting trashed when
//   visitors reach this script, which functions separately from
//   pgm-template_builder.php where the $lang array is registered
//
//                                                    --Mike Morrison
##########################################################################
unset($lang, $getSpec);
##########################################################################


##########################################################################
### WE WILL NEED TO KNOW THE DATABASE NAME; UN; PW; ETC TO OPERATE THE
### REAL-TIME EXECUTION.  THIS IS CONFIGURED IN THE isp.conf FILE
##########################################################################

function makePaystationSessionID($min=8,$max=8){

  # seed the random number generator - straight from PHP manual
  $seed = (double)microtime()*getrandmax();
  srand($seed);

  # make a string of $max characters with ASCII values of 40-122
  $p=0; while ($p < $max):
    $r=123-(rand()%75);
    $pass.=chr($r);
  $p++; endwhile;

  # get rid of all non-alphanumeric characters
  $pass=ereg_replace("[^a-zA-NP-Z1-9]+","",$pass);

  # if string is too short, remake it
  if (strlen($pass)<$min):
    $pass=makePaystationSessionID($min,$max);
  endif;

  return $pass;

};


// Re-registers all global & session info
if ( strlen(lang("Order Date")) < 4 ) {
   if ( !include("../sohoadmin/includes/config.php") ) {
      echo lang("Could not include config script!"); exit;
   }
   if ( !include($_SESSION['docroot_path']."/sohoadmin/includes/db_connect.php") ) {
      echo lang("Error")." 1: ".lang("Your session has expired. Please go back through the checkout process").".";
      exit;
   }
}

# Assign dot_com variable to configured ip/url
$dot_com = $this_ip;

# Echo session vars for testing
//foreach ( $_SESSION as $var=>$val ) {
//   echo "[$var] = ($val)<br>";
//}

// Include shared functions file
##=====================================
$fun_inc = $_SESSION['docroot_path']."/sohoadmin/program/includes/shared_functions.php";
include_once($fun_inc);
error_reporting(E_PARSE);
# Restore misc cart pref data
$cartpref = new userdata("cart");

# DEFAULT: "Complete Order >>"
if ( $cartpref->get("paypal_btn_text") == "" ) { $cartpref->set("paypal_btn_text", lang("Complete Order")." &gt;&gt;"); }

##########################################################################
### READ SHOPPING CART SETUP OPTIONS
##########################################################################

// General Options
// ==================
$result = mysql_query("SELECT * FROM cart_options");
$OPTIONS = mysql_fetch_array($result);

# Newschool
$gateway = new userdata("gateway");

// Paystation
// ==========
$result = mysql_query("SELECT * FROM cart_paystation");
$PAYSTATION = mysql_fetch_array($result);

// DPS
// ==========
$result = mysql_query("SELECT * FROM cart_dps");
$DPS = mysql_fetch_array($result);

// PayPro
// ==========
$result = mysql_query("SELECT * FROM cart_paypro");
$PAYPRO = mysql_fetch_array($result);

// Eway
// ==========
$result = mysql_query("SELECT * FROM cart_eway");
$EWAY = mysql_fetch_array($result);

// PayPal
// ==========
$result = mysql_query("SELECT * FROM cart_paypal");
$PAYPAL = mysql_fetch_array($result);

// Innovative Gateway
// ====================
$result = mysql_query("SELECT * FROM cart_innovgate");
$getInnov = mysql_fetch_array($result);

// PayPoint USA
// ====================
$result = mysql_query("SELECT * FROM cart_paypoint");
$getStore = mysql_fetch_array($result);

// Authorize.net
// ====================
$result = mysql_query("SELECT * FROM cart_authorize");
$getAuth = mysql_fetch_array($result);

// internetsecure
// ====================
$internetsecure = new userdata("internetsecure");
$IS_acctid = $internetsecure->get("acctid");
$IS_acctkey = $internetsecure->get("acctkey");

// WorldPay
// ==========
$result = mysql_query("SELECT * FROM cart_worldpay");
$getWorld = mysql_fetch_array($result);

$dType = $OPTIONS['PAYMENT_CURRENCY_TYPE'];
$dSign = $OPTIONS['PAYMENT_CURRENCY_SIGN'];
$wpHideCurr = $getWorld['WP_LATER2'];
$wpTest = $getWorld['WP_LATER3'];

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Configure System Variables for Language Version

	if ( $getSpec['df_lang'] == "" ) {
		$language = "english.php";
	} else {
	   $language = $getSpec[df_lang];
	}

	if ( $lang_dir != "" ) {
	   $lang_include = $lang_dir."/".$language;
	} else {
	   $lang_include = "../sohoadmin/language/$language";
	}

	// ---> Un-comment to troubleshoot lang array
	/*
	echo "<font color=\"#ffffff\">\n";
	echo "getSpec[df_lang] = (<b>".$getSpec[df_lang]."</b>)<br><br>\n";
	echo "language = (<b>".$language."</b>)<br><br>\n";
	echo "lang_dir = (<b>".$lang_dir."</b>)<br><br>\n";
	echo "lang_include = (<b>".$lang_include."</b>)<br><br>\n";
	echo "</font>\n";
   */

	include_once("$lang_include");
	error_reporting(E_PARSE);
	/* More lang testing vars
	echo "<font color=\"#2C79EC\">billy = (<b>".$billy."</b>)</font><br><br>\n";
	echo "<font color=\"#ff0000\">lang[\"Pending\"] = (<b>".$lang["Pending"]."</b>)</font><br><br>\n";
	echo "<font color=\"#ff0000\">lang[\"Connecting To PayPal\"] = (<b>".$lang["Connecting To PayPal"]."</b>)</font>\n";
   */

	$_SESSION['getSpec'] = $getSpec;
	$_SESSION['language'] = $language;
	foreach($lang as $lvar=>$lval){
		$_SESSION['lang'][$lvar]=$lval;
	}
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


##########################################################################
### Section Key:
### ------------
###
### 1. Check for INVOICE table and CUSTOMERS table existance; if not create
### 2. If "RememberMe" == On; Add user to CUSTOMERS table
### 3. Add Data to INVOICE TABLE and assign ORDERNUMBER
### 4. Process Payment
###    a. Online credit card processing (gateway or VeriSign)
###    b. Offline credit card processing
###    c. Check/Money Order processing
###
##########################################################################

//
//      ####
//        ##
//        ##
//        ##
//        ##
//        ##
//        ##
//     #######
//


##########################################################################
### CREATE TABLES IF THIS IS THE "FIRST ORDER" -- YEA BABY!
##########################################################################

// mysql_query("DROP TABLE cart_invoice");
// mysql_query("DROP TABLE cart_customers");

$START_INVOICE = "NULL";	// When adding an auto_increment order number to the invoice table, the mySQL value by default should
							// be NULL -- However, we will change this value if this is the FIRST order and we have created the
							// table new so that the order numbers will start at 10000 instead of 1.

// -----------------------------------------------------------
// CREATE "CART_INVOICE" TABLE -- FOR ORDER RETREIVAL, ETC.
// -----------------------------------------------------------
if(!table_exists('cart_invoice')){
	create_table('cart_invoice');
	$START_INVOICE = "10000";
}
//$match = 0;
//$tablename = "cart_invoice";
//$result = mysql_list_tables("$db_name");
//$i = 0;
//while ($i < mysql_num_rows ($result)) {
//	$tb_names[$i] = mysql_tablename ($result, $i);
//	if ($tb_names[$i] == $tablename) {
//		$match = 1;
//	}
//	$i++;
//}
//
//if ($match != 1) {
//
//	$START_INVOICE = "10000";
//
//	mysql_db_query("$db_name","CREATE TABLE $tablename (
//
//		ORDER_NUMBER INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
//		ORDER_DATE CHAR(50),
//		ORDER_TIME CHAR(25),
//
//		PAY_METHOD CHAR(50),
//		CC_TYPE CHAR(10),
//		CC_NUM CHAR(100),
//		CC_AVS CHAR(5),
//		CC_DATE CHAR(25),
//
//		TRANSACTION_STATUS CHAR(50),
//		TRANSACTION_ID CHAR(75),
//
//		BILLTO_FIRSTNAME CHAR(100),
//		BILLTO_LASTNAME CHAR(100),
//		BILLTO_COMPANY CHAR(100),
//		BILLTO_ADDR1 CHAR(100),
//		BILLTO_ADDR2 CHAR(100),
//		BILLTO_CITY CHAR(50),
//		BILLTO_STATE CHAR(50),
//		BILLTO_COUNTRY CHAR(75),
//		BILLTO_ZIPCODE CHAR(20),
//		BILLTO_PHONE CHAR(75),
//		BILLTO_EMAILADDR CHAR(100),
//
//		SHIPTO_FIRSTNAME CHAR(100),
//		SHIPTO_LASTNAME CHAR(100),
//		SHIPTO_COMPANY CHAR(100),
//		SHIPTO_ADDR1 CHAR(100),
//		SHIPTO_ADDR2 CHAR(100),
//		SHIPTO_CITY CHAR(50),
//		SHIPTO_STATE CHAR(50),
//		SHIPTO_COUNTRY CHAR(75),
//		SHIPTO_ZIPCODE CHAR(20),
//		SHIPTO_PHONE CHAR(75),
//
//		INVOICE_HTML BLOB,
//
//		TOTAL_SALE CHAR(50),
//
//		FUTURE1 BLOB,
//		FUTURE2 BLOB
//
//	)");
//
//} // End Create cart_invoice table

// -----------------------------------------------------------
// CREATE "CART_CUSTOMERS" TABLE -- FOR REMEMBER ME FUNCTION
// -----------------------------------------------------------
if(!table_exists('cart_customers')){
	create_table('cart_customers');
}
//$match = 0;
//$tablename = "cart_customers";
//$result = mysql_list_tables("$db_name");
//$i = 0;
//while ($i < mysql_num_rows ($result)) {
//	$tb_names[$i] = mysql_tablename ($result, $i);
//	if ($tb_names[$i] == $tablename) {
//		$match = 1;
//	}
//	$i++;
//}
//
//if ($match != 1) {
//
//	mysql_db_query("$db_name","CREATE TABLE $tablename (
//
//		PRIKEY INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
//
//		USERNAME CHAR(100),
//		PASSWORD CHAR(100),
//
//		BILLTO_FIRSTNAME CHAR(100),
//		BILLTO_LASTNAME CHAR(100),
//		BILLTO_COMPANY CHAR(100),
//		BILLTO_ADDR1 CHAR(100),
//		BILLTO_ADDR2 CHAR(100),
//		BILLTO_CITY CHAR(50),
//		BILLTO_STATE CHAR(50),
//		BILLTO_COUNTRY CHAR(75),
//		BILLTO_ZIPCODE CHAR(20),
//		BILLTO_PHONE CHAR(75),
//		BILLTO_EMAILADDR CHAR(100),
//
//		SHIPTO_FIRSTNAME CHAR(100),
//		SHIPTO_LASTNAME CHAR(100),
//		SHIPTO_COMPANY CHAR(100),
//		SHIPTO_ADDR1 CHAR(100),
//		SHIPTO_ADDR2 CHAR(100),
//		SHIPTO_CITY CHAR(50),
//		SHIPTO_STATE CHAR(50),
//		SHIPTO_COUNTRY CHAR(75),
//		SHIPTO_ZIPCODE CHAR(20),
//		SHIPTO_PHONE CHAR(75),
//
//		FUTURE1 BLOB,
//		FUTURE2 BLOB
//
//	)");
//
//} // End Create cart_invoice table


//
//     #######
//    ##     ##
//          ###
//         ###
//        ###
//      ###
//    ###
//    #########
//

##########################################################################
### IF REMEMBER ME FEATURE IS SELECTED, POPULATE CUSTOMER TABLE WITH
### CUSTOMER INFORMATION
##########################################################################

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Ready all session variables for database insert
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

reset($HTTP_SESSION_VARS);
while (list($name, $value) = each($HTTP_SESSION_VARS)) {
	if ( $name != "lang" && $name != "getSpec" && is_array($value) != TRUE) {
	   $value = addslashes($value);
	   ${$name} = $value;
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Combine Phone Number variables into a single variable for databasing.
// Leave this outside the if statements so that both database routines
// can use these vars because whether or not we use the remember me or
// not, we still need this data in the invoice table.
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$this_bphone = "$BPHONE";
$this_sphone = "$SPHONE";

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

# Update secure user (member logins) info if logged-in
if ( strtoupper($_SESSION['REPEATCUSTOMER']) == "YES" ) {
   $qry = "select * from sec_users";
   $qry .= " where MD5CODE = '".$_SESSION['MD5CODE']."'";
   $rez = mysql_query($qry);

   $qry = "show columns from sec_users";
   $rez2 = mysql_query($qry);
   if ( mysql_num_rows($rez) === 1 ) {
      $qry = "update sec_users set";
      while ( $getFld = mysql_fetch_assoc($rez2) ) {
         $fname = $getFld['Field'];
         if ( isset($_SESSION[$fname]) && $fname != "MD5CODE" ) {
            $qry .= " ".$fname." = '".$_SESSION[$fname]."',";
         }
      }

      $qry = substr($qry, 0, -1);
      $qry .= " where MD5CODE = '".$_SESSION['MD5CODE']."'";
      mysql_query($qry);
   }
}


if ( strtoupper($REMEMBERME) == "ON" ) {		// Note: If remember me feature has been turned off OR is in use by returning customer;
						// this var will never be set

	$exist_flag = 0;
	$result = mysql_query("SELECT USERNAME, PASSWORD FROM cart_customers WHERE USERNAME = '$BEMAILADDRESS' AND PASSWORD = '$BPASSWORD'");
	$exist_flag = mysql_num_rows($result);

	if ($exist_flag < 1) {

	mysql_query("INSERT INTO cart_customers VALUES(

		'NULL',

		'$BEMAILADDRESS',
		'$BPASSWORD',

		'$BFIRSTNAME',
		'$BLASTNAME',
		'$BCOMPANY',
		'$BADDRESS1',
		'$BADDRESS2',
		'$BCITY',
		'$BSTATE',
		'$BCOUNTRY',
		'$BZIPCODE',
		'$this_bphone',
		'$BEMAILADDRESS',

		'$SFIRSTNAME',
		'$SLASTNAME',
		'$SCOMPANY',
		'$SADDRESS1',
		'$SADDRESS2',
		'$SCITY',
		'$SSTATE',
		'$SCOUNTRY',
		'$SZIPCODE',
		'$this_sphone',

		' ',
		' '

	)");

	// ------------------------------------------------------------------
	// Place a reminder of the username and password on current invoice
	// ------------------------------------------------------------------

	$HTML = "<BR><TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=5 class=smtext STYLE=\"border: inset BLACK 1px;\">\n";
	$HTML .= "<TR> \n";
	$HTML .= "<TD  BGCOLOR=\"$OPTIONS[DISPLAY_HEADERBG]\"><FONT COLOR=$OPTIONS[DISPLAY_HEADERTXT]><B><FONT FACE=\"Verdana, Arial, Helvetica, sans-serif\">".$lang["Customer Registration"]."</FONT></B></FONT></TD>\n";
	$HTML .= "</TR>\n";
	$HTML .= "<TR> \n";
	$HTML .= "<TD ALIGN=LEFT VALIGN=TOP CLASS=smtext>\n";
	$HTML .= lang("Thanks")." $BFIRSTNAME, ".lang("you are now registered as a preferred customer")."! ".lang("The next time you shop with us, you may login using your username and password for quicker checkout")."!<BR><BR><DIV ALIGN=CENTER><U>".lang("Username")."</U>: <font size=3><TT>$BEMAILADDRESS</TT></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<U>".lang("Password")."</U>: <font size=3><TT>$BPASSWORD</TT></font></DIV>";
	$HTML .= "</TD></TR></TABLE>\n\n";


	$INVOICE .= $HTML;

	$exist_flag = 0;

	// ------------------------------------------------------------------

	} // End Exist Flag Check

} // End Remember Me Check


//
//     #######
//    ##     ##
//           ##
//          ###
//        ####
//          ###
//    ##     ##
//     #######
//

##########################################################################
### PLACE ORDER INFORMATION INTO "cart_invoice" TABLE
##########################################################################

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Generate "on the fly" variables for insertion now.  We will update some
// of these as we continue through the payment process
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$ORDER_DATE = date("m/d/Y");			// Set order date to today-- duh?
$ORDER_TIME = date("g:ia [T]");			// Set order time stamp (Great for stats and tracking issues with orders) TIME ZONES ARE SET BY SERVER ENVIRONMENT

# Set initial status to 'Incomplete' or 'Sent' until we can decide what's happening with order
//echo '['.$PAY_TYPE.']';

if ( $PAY_TYPE != "PAYPOINT" && $PAY_TYPE != "INNOVGATE" && $PAY_TYPE != "AUTHORIZENET" && !eregi("offline", $OPTIONS['PAYMENT_PROCESSING_TYPE']) && $PAY_TYPE != 'transactium' ) {
   $STATUS = "Sent";
} else {
   $STATUS = "Incomplete";
}

$update_string = $ORDER_NUMBER.$BLASTNAME.$ORDER_TOTAL; // For gateway return url (vs. passing raw, un-encrypted order number)
$update_string = md5($update_string); // This should do it


if ($PAY_TYPE == "CHECK") {
	$PTYPE = "Check/Money Order";		// Set transaction id to notify of check/money order pending order
} else {
	if ( $PAY_TYPE == 'EWAY_UK_NZ' || $PAY_TYPE == 'EWAY' ) {
		$PTYPE = 'eWay';
	} elseif ( $PAY_TYPE == 'transactium' ) {
		$PTYPE = ucwords($PAY_TYPE);		
	} else {
		$PTYPE = "Credit Card";
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Write order to invoice data table now
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$this_session = session_id();

$exist_flag = 0;

# Mantis 462
if ( $ORDER_NUMBER != "" ) { $exist_flag = 5; }

// $result = mysql_query("SELECT * FROM cart_invoice WHERE ORDER_NUMBER = '$ORDER_NUMBER'");
// $exist_flag = mysql_num_rows($result);

# Only insert pending invoice
# if we have to leave the site and come back (Mantis #292)
if ( $exist_flag < 1 ) {

// Use this one for troubleshooting
//	   if ( $_SERVER['REMOTE_ADDR'] == "70.89.253.74" ) {
//   	   $qryStr = "INSERT INTO cart_customers VALUES(";
//   	   $qryStr .= "'NULL',	'$BEMAILADDRESS',	'$BPASSWORD', '$BFIRSTNAME', '$BLASTNAME', '$BCOMPANY', '$BADDRESS1', '$BADDRESS2',";
//   	   $qryStr .= "'$BCITY', '$BSTATE', '$BCOUNTRY', '$BZIPCODE', '$this_bphone', '$BEMAILADDRESS', '$SFIRSTNAME', '$SLASTNAME',";
//   	   $qryStr .= "'$SCOMPANY', '$SADDRESS1', '$SADDRESS2', '$SCITY', '$SSTATE', '$SCOUNTRY', '$SZIPCODE', '$this_sphone', ' ', ' ')";
//   	   if ( !mysql_query($qryStr) ) {
//   	      echo "insert failed because: ".mysql_error();
//   	   } else {
//   	      echo "insert seems to have succeeded";
//   	   }
//	      echo $qryStr;
//	   }

   mysql_query("INSERT INTO cart_invoice VALUES(

	'$START_INVOICE',
	'$ORDER_DATE',
	'$ORDER_TIME',

	'$PTYPE',
	'NULL',
	'NULL',
	'NULL',
	'NULL',

	'$STATUS',
	'NULL',

	'$BFIRSTNAME',
	'$BLASTNAME',
	'$BCOMPANY',
	'$BADDRESS1',
	'$BADDRESS2',
	'$BCITY',
	'$BSTATE',
	'$BCOUNTRY',
	'$BZIPCODE',
	'$this_bphone',
	'$BEMAILADDRESS',

	'$SFIRSTNAME',
	'$SLASTNAME',
	'$SCOMPANY',
	'$SADDRESS1',
	'$SADDRESS2',
	'$SCITY',
	'$SSTATE',
	'$SCOUNTRY',
	'$SZIPCODE',
	'$this_sphone',

	'$INVOICE',

	'$ORDER_TOTAL',
	'$this_session',

	'$update_string')") || DIE ("ERROR INSERTING INVOICE DATA");

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// GET CURRENT ORDER NUMBER
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	$ORDER_NUMBER = mysql_insert_id();

	$_SESSION['ORDER_NUMBER'] = $ORDER_NUMBER;
	$_SESSION['ORDER_TIME'] = $ORDER_TIME;
	$_SESSION['ORDER_DATE'] = $ORDER_DATE;


   // Commmented out because: Wait until order is complete
	//include("pgm-email_notify.php");

} // End if necessary to insert pending invoice

if ( $ORDER_NUMBER == "" ) {
   echo "<H2>".lang("An error occurred when assigning your invoice number.")." ".lang("Please try again or contact the webmaster immediately.")."</H2>\n";
   exit;
} else { // $ORDER_NUMBER already present - update cart_invoice with any changes.
  mysql_query("UPDATE cart_invoice SET PAY_METHOD='$PTYPE',TRANSACTION_STATUS='$STATUS',BILLTO_FIRSTNAME='$BFIRSTNAME',BILLTO_LASTNAME='$BLASTNAME',BILLTO_COMPANY='$BCOMPANY',BILLTO_ADDR1='$BADDRESS1',BILLTO_ADDR2='$BADDRESS2',BILLTO_CITY='$BCITY',BILLTO_STATE='$BSTATE',BILLTO_COUNTRY='$BCOUNTRY',BILLTO_ZIPCODE='$BZIPCODE',BILLTO_PHONE='$this_bphone',BILLTO_EMAILADDR='$BEMAILADDRESS',SHIPTO_FIRSTNAME='$SFIRSTNAME',SHIPTO_LASTNAME='$SLASTNAME',SHIPTO_COMPANY='$SCOMPANY',SHIPTO_ADDR1='$SADDRESS1',SHIPTO_ADDR2='$SADDRESS2',SHIPTO_CITY='$SCITY',SHIPTO_STATE='$SSTATE',SHIPTO_COUNTRY='$SCOUNTRY',SHIPTO_ZIPCODE='$SZIPCODE',SHIPTO_PHONE='$this_sphone',INVOICE_HTML='$INVOICE',TOTAL_SALE='$ORDER_TOTAL',FUTURE1='$this_session' WHERE ORDER_NUMBER = '$ORDER_NUMBER' LIMIT 1") || DIE ("ERROR UPDATING INVOICE DATA");
}


// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Now fix slashes in session vars for display
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

reset($_SESSION);
while (list($name, $value) = each($_SESSION)) {
	if ( $name != "lang" && $name != "getSpec" && is_array($value) != TRUE) {
	   $value = stripslashes($value);
	   ${$name} = $value;
	}
}


//
//    ##      ##
//    ##      ##
//    ##      ##
//    ##########
//            ##
//            ##
//            ##
//            ##
//

##########################################################################
### DETRMINE METHOD IN WHICH WE SHOULD PROCESS THIS PAYMENT
##########################################################################

$GATEWAY_ERR = 0;
error_reporting(E_USER_ERROR);
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// A: PROCESS ONLINE CREDIT CARD PAYMENT
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$plugin_paytypes = array();
eval(hook("pgm-payment_gateway.php:plugin_paytypes"));


$nochex = new userdata("nochex");
$nochex_id = $nochex->get("acctid");

if ( in_array($PAY_TYPE, $plugin_paytypes) || $PAY_TYPE=='PAYPALPRO' || $PAY_TYPE=='nochex' || $PAY_TYPE == "CUSTOM_INC" || $PAY_TYPE == "WORLDPAY" || $PAY_TYPE == "PAYPAL" || $PAY_TYPE == "VERISIGN" || $PAY_TYPE == "EWAYATEWAY" || $PAY_TYPE == 'EWAY_UK_NZ' || $PAY_TYPE == "PAYPRO" || $PAY_TYPE == "PAYSTATION" ) {		// This is either a "gateway include", VeriSign, PayPal, or WorldPay

	// ---------------------------------------------------------
	// FRIST CHECK TO SEE IF THIS UTILIZES A CUSTOM PHP INC FILE
	// ---------------------------------------------------------

	if ($PAY_TYPE == "CUSTOM_INC") {

		$filename = chop($OPTIONS['PAYMENT_INCLUDE']);
		$filename = "../media/$filename";

		if (file_exists("$filename")) {

			include("$filename");
			exit;

		} else {

			$THIS_DISPLAY .= "<DIV ALIGN=LEFT CLASS=text><H2><FONT COLOR=RED>CHECKOUT SETUP ERROR:</FONT></H2><FONT COLOR=DARKBLUE>".lang("The checkout system is configured to use a custom gateway include script named")." \"$OPTIONS[PAYMENT_INCLUDE]\", ".lang("but the file can not be found on the server.")." \n";
			$THIS_DISPLAY .= lang("Via 'Payment Options' in the system admin, make sure that you have a current include file selected and try again.")."\n";
			$THIS_DISPLAY .= "<BR><BR></DIV>\n\n";

			$GATEWAY_ERR = 1;
		}

	} // End gateway include

	// ---------------------------------------------------------
	// THE INCLUDE WILL EXIT FOR PROCESSING; THEREFORE, IF IT
	// DOES NOT TAKE OVER, IT MUST BE VERISIGN; WE'LL DOUBLE
	// CHECK IT TO MAKE SURE FIRST THOUGH AND DISPLAY AN ERROR
	// MESSAGE IN CASE THE SETTINGS WHERE NOT COMPLETED PROPERLY
	// ---------------------------------------------------------




	/*----------------------------------------------------------------------------
   __      __       _  _____ _
   \ \    / /      (_)/ ____(_)
    \ \  / /__ _ __ _| (___  _  __ _ _ __
     \ \/ / _ \ '__| |\___ \| |/ _` | '_ \
      \  /  __/ |  | |____) | | (_| | | | |
       \/ \___|_|  |_|_____/|_|\__, |_| |_|
                                __/ |
                               |___/
	/*----------------------------------------------------------------------------*/

	if ($OPTIONS[PAYMENT_VLOGINID] != "" && $OPTIONS[PAYMENT_VPARTNERID] != "" && $GATEWAY_ERR != 1 && $PAY_TYPE == "VERISIGN") {		// Verisign Ready to go!

			$b_state = substr($BSTATE, 0, 2);
			$s_state = substr($SSTATE, 0, 2);	// VeriSign State fields can only be 2 chars
									// We compensate for that when customer enters data

			// ----------------------------------------------------------
			// UPDATE INVOICE TABLE TO INDICATE WE HAVE SENT CUSTOMER
			// TO VERISIGN, NOW WE ARE WAITING FOR THEM TO COME BACK
			// EITHER MANUALLY OR VIA SILENT POST.
			// ----------------------------------------------------------

			mysql_query("UPDATE cart_invoice SET PAY_METHOD = 'Verisign', TRANSACTION_STATUS = 'Sent' WHERE ORDER_NUMBER = '$ORDER_NUMBER'");

			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
			// DEVNOTE:
			//
			// Remember to configure the payflow link settings as outlined in the "How to setup payflow link
			// to work with your site" section in the payment options of the admin tool.  Setting the
			// "Silent Post" and "Redirect" features are key to making this system operate correctly.
			//
			// Also note that this same type of setup can be utilized in your own custom gateway includes
			// for other merchant service providers.  Check the Developer's Network for free include gateway
			// scripts.  As of the documenting of this code, there are already includes in the works for
			// authorize.net and paypal! :)
			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

			echo '

			<HTML>
			<HEAD>

			<LINK rel="stylesheet" href="../runtime.css" type="text/css">

			<SCRIPT language=JavaScript>

				function gateway () {
					window.document.paymentgate.submit();
				}

			</SCRIPT>



			</HEAD>

			<BODY BGCOLOR=DARKBLUE TEXT=DARKBLUE>


			<FORM NAME=paymentgate METHOD="POST" ACTION="https://payflowlink.paypal.com">

			<input type="hidden" name="LOGIN" value="' . $OPTIONS[PAYMENT_VLOGINID] . '">
			<input type="hidden" name="PARTNER" value="' . $OPTIONS[PAYMENT_VPARTNERID] . '">

			<input type="hidden" name="AMOUNT" value="' . $ORDER_TOTAL . '">
			<input type="hidden" name="TYPE" value="S">

			<input type="hidden" name="ADDRESS" value="' . $BADDRESS1 . '">
			<input type="hidden" name="ADDRESSTOSHIP" value="' . $SADDRESS1 . '">
			<input type="hidden" name="CITY" value="' . $BCITY . '">
			<input type="hidden" name="CITYTOSHIP" value="' . $SCITY . '">
			<input type="hidden" name="COMMENT1" value="Order Number: ' . $ORDER_NUMBER . '">
			<input type="hidden" name="NAME" value="' . $BFIRSTNAME . ' ' . $BLASTNAME . '">

			<input type="hidden" name="NAMETOSHIP" value="' . $SFIRSTNAME . ' ' . $SLASTNAME . '">
			<input type="hidden" name="PHONE" value="' . $this_bphone . '">
			<input type="hidden" name="STATE" value="' . $b_state . '">
			<input type="hidden" name="STATETOSHIP" value="' . $s_state . '">
			<input type="hidden" name="ZIP" value="' . $BZIPCODE . '">
			<input type="hidden" name="ZIPTOSHIP" value="' . $SZIPCODE . '">
			<input type="hidden" name="EMAIL" value="' . $BEMAILADDRESS . '">

         			<input type="hidden" name="COUNTRY" value="' . $BCOUNTRY . '">
         		<input type="hidden" name="COUNTRYTOSHIP" value="' . $SCOUNTRY . '">

			<input type="hidden" name="SHOWCONFIRM" value="False">

			<input type="hidden" name="USER1" value="' . $customernumber . '">
			<input type="hidden" name="USER2" value="'. $this_ip .'">
			<input type="hidden" name="USER3" value="'. $ORDER_NUMBER .'">
			<input type="hidden" name="USER4" value="VERISIGN_GATEWAY">
			<input type="hidden" name="USER5" value="'.$_SESSION['CART_KEYID'].'">
			<input type="hidden" name="USER6" value="'.$_SESSION['CART_QTY'].'">
			<input type="hidden" name="USER7" value="'.$_SESSION['CART_SKUNO'].'">
			<input type="hidden" name="USER8" value="">
			<input type="hidden" name="USER9" value="">

			<!-- DISPLAY HOLD MESSAGE TO CUSTOMER -->

			<table border=0 cellpadding=10 cellspacing=0 width=100% height=100%><tr><td align=center valign=middle class=text>

				<table border=0 cellpadding=40 cellspacing=0 bgcolor=#EFEFEF STYLE="border: 2px inset black;"><tr><td align=center valign=middle class=text>

					<font color=red><H3>'.lang("Connecting To VeriSign").'<font size=2><SUP>TM</SUP></font> '.lang("Secure Server").'.</font><BR>'.lang("Please Hold").'...</h3><BR><BR>
					'.lang("If you are not connected automatically within 20 seconds").'<BR><BR><input type="submit" value="'.lang("Click Here").' " class=FormLt1>

				</td></tr></table>

			</td></tr></table>

			<!-- END HOLD MESSAGE DISPLAY -->

			</FORM>

			<SCRIPT language=JavaScript>
				gateway();
			</SCRIPT>

			</BODY></HTML>

			';

			exit;

	} // End Verisign Process
	
	
	# Custom for PayPal Pro
	if ( strlen($cartpref->get('paypalpro_username')) > 3 && strlen($cartpref->get('paypalpro_password')) > 3 && $PAY_TYPE == "PAYPALPRO" ) {
	   /*--------------------------------------------------------------------------------------------------------*
	
	    PayPal Pro
	
	   /*---------------------------------------------------------------------------------------------------------*/
	
	   ######################################################################################
	   // Show Checkout Steps at top
	   ######################################################################################
	   $THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\" class=\"shopping-selfcontained_box\" id=\"checkout-steps\">\n";
	   $THIS_DISPLAY .= " <tr>\n";
	   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
	   $THIS_DISPLAY .= "   <b>".lang("Step")." 1:<br/>".lang("Customer Sign-in")."</b>\n";
	   $THIS_DISPLAY .= "  </td>\n";
	   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
	   $THIS_DISPLAY .= lang("Step")." 2:<br/>".lang("Billing & Shipping")."<br/>".lang("Information")."\n";
	   $THIS_DISPLAY .= "  </td>\n";
	   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
	   $THIS_DISPLAY .= lang("Step")." 3:<br/>".lang("Shipping Options")."\n";
	   $THIS_DISPLAY .= "  </td>\n";
	   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
	   $THIS_DISPLAY .= lang("Step")." 4:<br/>".lang("Verify Order Details")."<br/>\n";
	   $THIS_DISPLAY .= "  </td>\n";
	   # Current step
	   $THIS_DISPLAY .= "  <th align=\"center\" valign=\"top\">\n";
	   $THIS_DISPLAY .= lang("Step")." 5:<br/>".lang("Make Payment")."\n";
	   $THIS_DISPLAY .= "  </th>\n";
	   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
	   $THIS_DISPLAY .= lang("Step")." 6:<br/>".lang("Print Final")."<br/>".lang("Invoice")."\n";
	   $THIS_DISPLAY .= "  </td>\n";
	   $THIS_DISPLAY .= " </tr>\n";
	   $THIS_DISPLAY .= "</table>\n";
	
	   $THIS_DISPLAY .= "<br>\n";
	
	
	   ######################################################################################
	   // Show Credit Card Processing Form
	   ######################################################################################
	   ob_start();
	      include('../sohoadmin/client_files/shopping_cart/prod_paypalpro_card.php');
	      $THIS_DISPLAY .= ob_get_contents();
	   ob_end_clean();
	
	
	} // End paypal pro verification	
	

   if ( strlen($EWAY['EWAY_ID']) > 3 && $GATEWAY_ERR != 1 && $PAY_TYPE == "EWAYATEWAY" ) {

   	/*----------------------------------------------------------------------------
               __      _____   __
             __\ \    / /_\ \ / /
            / -_) \/\/ / _ \ V /
            \___|\_/\_/_/ \_\_|
   	/*----------------------------------------------------------------------------*/

			// ----------------------------------------------------------
			// UPDATE INVOICE TABLE TO INDICATE WE HAVE SENT CUSTOMER
			// TO VERISIGN, NOW WE ARE WAITING FOR THEM TO COME BACK
			// EITHER MANUALLY OR VIA SILENT POST.
			// ----------------------------------------------------------

			mysql_query("UPDATE cart_invoice SET PAY_METHOD = 'eWay', TRANSACTION_STATUS = 'Sent' WHERE ORDER_NUMBER = '$ORDER_NUMBER'");

         $eway_id = $EWAY['EWAY_ID'];
         $return_url = "http://".$_SESSION['docroot_url']."/shopping/pgm-show_invoice.php?meth=eway&status=paid&pporder=".$update_string;
         $cent_total = bcmul($ORDER_TOTAL, '100', 0);

			$THIS_DISPLAY = "<HTML>\n";
			$THIS_DISPLAY .= "<HEAD>\n";

			$THIS_DISPLAY .= "<LINK rel=\"stylesheet\" href=\"../runtime.css\" type=\"text/css\">\n";

			$THIS_DISPLAY .= "<SCRIPT language=JavaScript>\n";

			$THIS_DISPLAY .= " function gateway () {\n";
			$THIS_DISPLAY .= "	window.document.paymentgate.submit();\n";
			$THIS_DISPLAY .= " }\n";
			$THIS_DISPLAY .= "</script>\n";

			$THIS_DISPLAY .= "</head>\n";

			$THIS_DISPLAY .= "<body bgcolor=\"#D3E9EF\" TEXT=\"#336699\">\n";


			$THIS_DISPLAY .= "<form name=paymentgate action=\"https://www.eway.com.au/gateway/payment.asp\" method=\"post\">\n";


         $THIS_DISPLAY .= "   <input type=\"hidden\" name=\"ewayCustomerID\" value=\"$eway_id\">\n";

         $THIS_DISPLAY .= "	<input type=\"hidden\" name=\"ewayTotalAmount\" value=\"$cent_total\">\n";

         $THIS_DISPLAY .= "	<input type=\"hidden\" name=\"ewayCustomerFirstName\" value=\"$BFIRSTNAME\">\n";

         $THIS_DISPLAY .= "	<input type=\"hidden\" name=\"ewayCustomerLastName\" value=\"$BLASTNAME\">\n";

         $THIS_DISPLAY .= "	<input type=\"hidden\" name=\"ewayCustomerEmail\" value=\"$BEMAILADDRESS\">\n";

         $THIS_DISPLAY .= "	<input type=\"hidden\" name=\"ewayCustomerAddress\" value=\"$BADDRESS1\">\n";

         $THIS_DISPLAY .= "	<input type=\"hidden\" name=\"ewayURL\" value=\"$return_url\">\n";

         $THIS_DISPLAY .= "	<input type=\"hidden\" name=\"ewaySiteTitle\" value=\"$this_ip\">\n";


		   $THIS_DISPLAY .= "\n\n<!-- DISPLAY HOLD MESSAGE TO CUSTOMER -->\n\n";

			$THIS_DISPLAY .= "<table border=0 cellpadding=10 cellspacing=0 width=100% height=100%><tr><td align=center valign=middle class=text>\n";

			$THIS_DISPLAY .= "<table border=0 cellpadding=40 cellspacing=0 bgcolor=#EFEFEF STYLE=\"border: 2px inset black;\"><tr><td align=center valign=middle class=text>\n";

			$THIS_DISPLAY .= "<font color=red><H3>".lang("Connecting To eWAY")."<font size=2><SUP>TM</SUP></font> ".lang("Secure Payment Server").".</font><BR>".lang("Please Hold")."...</h3><BR><BR>\n";
			$THIS_DISPLAY .= lang("If you are not connected automatically within 20 seconds")."<BR><BR><input type=\"submit\" value=\"".lang("Click Here")."\" class=FormLt1>\n";

			$THIS_DISPLAY .= "</td></tr></table>\n";

			$THIS_DISPLAY .= "</td></tr></table>\n";

			$THIS_DISPLAY .= "\n\n<!-- END HOLD MESSAGE DISPLAY -->\n\n";

			$THIS_DISPLAY .= "</FORM>\n";

			$THIS_DISPLAY .= "<script language=\"javascript\">window.document.paymentgate.submit()</script>\n";

			$THIS_DISPLAY .= "</BODY>\n";
			$THIS_DISPLAY .= "</HTML>\n";

			echo $THIS_DISPLAY;

			exit;

	} // End eWay (Aus) verification
	
	
	
	if ( $PAY_TYPE == "EWAY_UK_NZ" ) {
	
		// Eway payment in PHP
		$pathvalue="http://www.ewaypayment.com.php5-2.dfw1-2.websitetestlink.com/php/";
		
		if ( $cartpref->get('ewayuk_testmode') == 'on' ) {
	      $ewayurl.="?CustomerID=87654321";
			$ewayurl.="&UserName=TestAccount";
		} else {
	      $ewayurl.="?CustomerID=".$cartpref->get('eway_uk_account_id');
			$ewayurl.="&UserName=".$cartpref->get('eway_uk_username');
		}
					
		$ewayurl.="&Amount=".$ORDER_TOTAL;
		$ewayurl.="&Currency=".$dType;
		$ewayurl.="&PageTitle=Our Shopping Cart";
	   $ewayurl.="&PageDescription=Place Your Order Securely";
		$ewayurl.="&PageFooter=We appreciate your business!";	
		$ewayurl.="&Language=EN";
		$ewayurl.="&CompanyName=".$cartpref->get('ewayuk_companyname');
		
		if ( $cartpref->get('ewayuk_companylogo') != '' ) {
			$ewayurl.="&CompanyLogo=https://".$cartpref->get('ewayuk_companylogo');
		}
		if ( $cartpref->get('ewayuk_pagebanner') != '' ) {
			$ewayurl.="&PageBanner=https://".$cartpref->get('ewayuk_pagebanner');
		}
//		echo $ewayurl; exit;
		
		$ewayurl.="&CustomerFirstName=".$BFIRSTNAME;
		$ewayurl.="&CustomerLastName=".$BLASTNAME;
		$ewayurl.="&CustomerAddress=".$BADDRESS1;
		$ewayurl.="&CustomerCity=".$BCITY;
		$ewayurl.="&CustomerState=".$BSTATE;
		$ewayurl.="&CustomerPostCode=".$BZIPCODE;
		$ewayurl.="&CustomerCountry=".$BCOUNTRY;		
		$ewayurl.="&CustomerEmail=".$BEMAILADDRESS;
		$ewayurl.="&CustomerPhone=".$BPHONE;		
		$ewayurl.="&InvoiceDescription=Order ".$ORDER_NUMBER;
		if ( strtolower($_SERVER['HTTPS']) == 'on' ) { $prefixStr = 'https'; } else { $prefixStr = 'http'; }
		$return_urlStr = '';
		if ($OPTIONS['PAYMENT_SSL'] != ''){
			$return_urlStr = $OPTIONS['PAYMENT_SSL'];
		} else {
			if($_SERVER['SERVER_NAME']!=''){
				$return_urlStr = $prefixStr."://".$_SERVER['SERVER_NAME'];
			} else {
				$return_urlStr = $prefixStr."://".$_SESSION['docroot_url'];
			}
		}
		$ewayurl.="&CancelURL=".$return_urlStr."/shopping/pgm-show_invoice.php?meth=eway_uk_cancelled".urlencode('&')."pporder=".$update_string.urlencode('&')."ordercode=".$ORDER_NUMBER;
		$ewayurl.="&ReturnUrl=".$return_urlStr."/shopping/pgm-show_invoice.php?meth=eway_uk_paid".urlencode('&')."pporder=".$update_string.urlencode('&')."ordercode=".$ORDER_NUMBER;
		$ewayurl.="&MerchantReference=".$ORDER_NUMBER;
		$ewayurl.="&MerchantInvoice=".$ORDER_NUMBER;
		$ewayurl.="&MerchantOption1="; 
		$ewayurl.="&MerchantOption2=";
		$ewayurl.="&MerchantOption3=";
		$ewayurl.="&ModifiableCustomerDetails=true";
			
	   $spacereplace = str_replace(" ", "%20", $ewayurl);	
	   
	   # UK or NZ?
	   if ( strtolower($cartpref->get('eway_uk_or_nz')) == 'uk' ) {
	   	$posturl="https://payment.ewaygateway.com/Request/$spacereplace";
	   } else {
	   	$posturl="https://nz.ewaygateway.com/Request/$spacereplace";
	   }
//	   echo '['.$cartpref->get('eway_uk_or_nz').']<br/>'.$posturl; exit;
		
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
		
		$response = curl_exec($ch);
		
		function fetch_data($string, $start_tag, $end_tag)
		{
			$position = stripos($string, $start_tag);  
			$str = substr($string, $position);  		
			$str_second = substr($str, strlen($start_tag));  		
			$second_positon = stripos($str_second, $end_tag);  		
			$str_third = substr($str_second, 0, $second_positon);  		
			$fetch_data = trim($str_third);		
			return $fetch_data; 
		}
		
		
		$responsemode = fetch_data($response, '<result>', '</result>');
	   $responseurl = fetch_data($response, '<uri>', '</uri>');
	   $errormsg = fetch_data($response, '<error>', '</error>');
		   
		if ( $responsemode=="True" )	{
//			echo '<pre>True: '.$response."</pre>"; exit;
			header("location: ".$responseurl);
			exit;
		} else {
			echo '<div style="width: 700px;margin: 10% auto;paddding: 15px;backgrond-color: #ffdbdb;border: 2px solid #ff0000;text-align: center;">';
			echo '	<h1>Gateway Error</h1><p style="font-size: 150%;font-family: Arial;">Transaction could not be processed because:<br/><em>'.$errormsg.'</em></p>';
			echo '	<p style="text-align: center;"><strong>Please contact us and let us know about this error!</strong><br/>';
			echo '	<a href="http://'.$_SESSION['docroot_url'].'">Return to our website</a></p>';
			echo '</div>'; 
			exit;
//			header("location: ewaysharedpage.php?action=error");
//			exit;
		}
	} // End if eWay UK/NZ

	if (strlen($PAYPRO[PAYPRO_ID]) > 3 && $GATEWAY_ERR != 1 && $PAY_TYPE == "PAYPRO") {

   	/*----------------------------------------------------------------------------
       ___           ___
      | _ \__ _ _  _| _ \_ _ ___
      |  _/ _` | || |  _/ '_/ _ \
      |_| \__,_|\_, |_| |_| \___/
                |__/
   	/*----------------------------------------------------------------------------*/

			$b_state = substr($BSTATE, 0, 2);
			$s_state = substr($SSTATE, 0, 2);	// PayPal State fields can only be 2 chars
									// We compensate for that when customer enters data

			// ----------------------------------------------------------
			// UPDATE INVOICE TABLE TO INDICATE WE HAVE SENT CUSTOMER
			// TO VERISIGN, NOW WE ARE WAITING FOR THEM TO COME BACK
			// EITHER MANUALLY OR VIA SILENT POST.
			// ----------------------------------------------------------

			mysql_query("UPDATE cart_invoice SET PAY_METHOD = 'PayPro', TRANSACTION_STATUS = 'Sent' WHERE ORDER_NUMBER = '$ORDER_NUMBER'");


			$THIS_DISPLAY = "<HTML>\n";
			$THIS_DISPLAY .= "<HEAD>\n";

			$THIS_DISPLAY .= "<LINK rel=\"stylesheet\" href=\"../runtime.css\" type=\"text/css\">\n";

			$THIS_DISPLAY .= "<SCRIPT language=JavaScript>\n";

			$THIS_DISPLAY .= " function gateway () {\n";
			$THIS_DISPLAY .= "	window.document.paymentgate.submit();\n";
			$THIS_DISPLAY .= " }\n";
			$THIS_DISPLAY .= "</script>\n";

			$THIS_DISPLAY .= "</head>\n";

			$THIS_DISPLAY .= "<body bgcolor=\"#D3E9EF\" TEXT=\"#336699\">\n";


			$THIS_DISPLAY .= "<form name=paymentgate action=\"https://www.paypro.co.nz/https/pay.aspx\" method=\"post\">\n";

         // PayPal Configuration
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"MerchantKey\" value=\"".$PAYPRO[PAYPRO_ID]."\">\n";
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"MerchantOrderNo\" value=\"".$ORDER_NUMBER."\">\n";
         $THIS_DISPLAY .= "<input type=\"hidden\" name=\"Mode\" value=\"P\">\n";
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"PurchaseAmount\" value=\"".$ORDER_TOTAL."\">\n";
			// Test card not accepted
			//$THIS_DISPLAY .= "<input type=\"hidden\" name=\"PurchaseAmount\" value=\"20.10\">\n";


		   $THIS_DISPLAY .= "\n\n<!-- DISPLAY HOLD MESSAGE TO CUSTOMER -->\n\n";

			$THIS_DISPLAY .= "<table border=0 cellpadding=10 cellspacing=0 width=100% height=100%><tr><td align=center valign=middle class=text>\n";

			$THIS_DISPLAY .= "<table border=0 cellpadding=40 cellspacing=0 bgcolor=#EFEFEF STYLE=\"border: 2px inset black;\"><tr><td align=center valign=middle class=text>\n";

			$THIS_DISPLAY .= "<font color=red><H3>".lang("Connecting To PayPro")."<font size=2><SUP>TM</SUP></font> ".lang("Secure Payment Server").".</font><BR>".lang("Please Hold")."...</h3><BR><BR>\n";
			$THIS_DISPLAY .= lang("If you are not connected automatically within 20 seconds")."<BR><BR><input type=\"submit\" value=\"".lang("Click Here")."\" class=FormLt1>\n";

			$THIS_DISPLAY .= "</td></tr></table>\n";

			$THIS_DISPLAY .= "</td></tr></table>\n";

			$THIS_DISPLAY .= "\n\n<!-- END HOLD MESSAGE DISPLAY -->\n\n";

			$THIS_DISPLAY .= "</FORM>\n";

			$THIS_DISPLAY .= "<script language=\"javascript\">window.document.paymentgate.submit()</script>\n";

			$THIS_DISPLAY .= "</BODY>\n";
			$THIS_DISPLAY .= "</HTML>\n";

			echo $THIS_DISPLAY;

			exit;

	} // End PayPro verification


	if (strlen($PAYPAL[PAYPAL_EMAIL]) > 3 && $GATEWAY_ERR != 1 && $PAY_TYPE == "PAYPAL") {		// PayPal Ready to go!
   	/*----------------------------------------------------------------------------
       _____            _____      _
      |  __ \          |  __ \    | |
      | |__) |_ _ _   _| |__) |_ _| |
      |  ___/ _` | | | |  ___/ _` | |
      | |  | (_| | |_| | |  | (_| | |
      |_|   \__,_|\__, |_|   \__,_|_|
                   __/ |
                  |___/
   	/*----------------------------------------------------------------------------*/
   	if ( $_SESSION['CUR_USER'] != '' && $_SESSION['PHP_AUTH_USER'] != '' && $cartpref->get('admin-testmode-status') == 'on' ) {
   		$testmodeBool = true;
   	} else {
   		$testmodeBool = false;
   	}

			$b_state = substr($BSTATE, 0, 2);
			$s_state = substr($SSTATE, 0, 2);	// PayPal State fields can only be 2 chars
									// We compensate for that when customer enters data

			// ----------------------------------------------------------
			// UPDATE INVOICE TABLE TO INDICATE WE HAVE SENT CUSTOMER
			// TO VERISIGN, NOW WE ARE WAITING FOR THEM TO COME BACK
			// EITHER MANUALLY OR VIA SILENT POST.
			// ----------------------------------------------------------

			mysql_query("UPDATE cart_invoice SET PAY_METHOD = 'PayPal', TRANSACTION_STATUS = 'Sent' WHERE ORDER_NUMBER = '$ORDER_NUMBER'");

			$THIS_DISPLAY = "<HTML>\n";
			$THIS_DISPLAY .= "<HEAD>\n";

			$THIS_DISPLAY .= "<LINK rel=\"stylesheet\" href=\"../runtime.css\" type=\"text/css\">\n";

			$THIS_DISPLAY .= "<SCRIPT language=JavaScript>\n";

			$THIS_DISPLAY .= " function gateway () {\n";
			$THIS_DISPLAY .= "	window.document.paymentgate.submit();\n";
			$THIS_DISPLAY .= " }\n";
			$THIS_DISPLAY .= "</script>\n";

			$THIS_DISPLAY .= "</head>\n";

			$THIS_DISPLAY .= "<body bgcolor=\"#306FAE\" TEXT=\"#336699\">\n";

			if ( $testmodeBool ) {
				$THIS_DISPLAY .= "<form name=paymentgate action=\"https://www.sandbox.paypal.com/cgi-bin/webscr\" method=\"post\">\n"; //PayPal test url
			} else {
				$THIS_DISPLAY .= "<form name=paymentgate action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\">\n";
			}

			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"custom\" value=\"PAYPAL\">\n";

         // PayPal Configuration
         if ( $testmodeBool ) {
				$THIS_DISPLAY .= "<input type=\"hidden\" name=\"business\" value=\"".$cartpref->get("pp-sandbox-email")."\">\n";
			} else {
				$THIS_DISPLAY .= "<input type=\"hidden\" name=\"business\" value=\"".$PAYPAL['PAYPAL_EMAIL']."\">\n";
			}
			
			if ( strtolower($_SERVER['HTTPS']) == 'on' ) { $prefixStr = 'https'; } else { $prefixStr = 'http'; }
			$return_urlStr = '';
			//if ( $OPTIONS['PAYMENT_SSL'] != '' ) { $return_urlStr = $OPTIONS['PAYMENT_SSL']; } else { $return_urlStr = $prefixStr."://".$_SESSION['docroot_url']; }
			if ($OPTIONS['PAYMENT_SSL'] != ''){
				$return_urlStr = $OPTIONS['PAYMENT_SSL'];
			} else {
				if($_SESSION['this_ip']!=''){
					$return_urlStr = $prefixStr."://".$_SESSION['this_ip'];
				} else {
					$return_urlStr = $prefixStr."://".$_SESSION['docroot_url'];
				}
			}
			
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"cmd\" value=\"_xclick\">\n";
         $THIS_DISPLAY .= "<input type=\"hidden\" name=\"return\" value=\"".$return_urlStr."/shopping/pgm-show_invoice.php?meth=paypal&status=paid&pporder=".$update_string."\">\n";

         // Format cancell id (have to pass it in url, which warrents a little obfiscation)
         $pp_cancel = $ORDER_NUMBER."candis1";
         $pp_cancel = md5($pp_cancel);

         $THIS_DISPLAY .= "<input type=\"hidden\" name=\"cancel_return\" value=\"".$return_urlStr."/shopping/pgm-show_invoice.php?meth=paypal&status=cancelled&pporder=".$update_string."\">\n";
         
         if ( $cartpref->get("paypal_notify_url") != 'disabled' ) {
         	$THIS_DISPLAY .= "<input type=\"hidden\" name=\"notify_url\" value=\"".$return_urlStr."/shopping/pgm-show_invoice.php\">\n";
         }
         
         $THIS_DISPLAY .= "<input type=\"hidden\" name=\"rm\" value=\"2\">\n";
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"currency_code\" value=\"$dType\">\n";
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"bn\" value=\"toolkit-php\">\n";
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"cbt\" value=\"".$cartpref->get("paypal_btn_text")."\">\n";

         // Payment Page Information
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"no_shipping\" value=\"\">\n";
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"no_note\" value=\"0\">\n";
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"cn\" value=\"".lang("Comments")."\">\n";
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"cs\" value=\"\">\n";

         // Product Information
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
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"item_name\" value=\"".$pp_desc_string."\">\n";
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"amount\" value=\"$ORDER_TOTAL\">\n";

         // Shipping and Misc Information

         $THIS_DISPLAY .= "<input type=\"hidden\" name=\"invoice\" value=\"$ORDER_NUMBER\">\n";

         // Customer Information
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"first_name\" value=\"$BFIRSTNAME\">\n";
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"last_name\" value=\"$BLASTNAME\">\n";
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"address1\" value=\"$BADDRESS1\">\n";
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"address2\" value=\"$BADDRESS2\">\n";
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"city\" value=\"$BCITY\">\n";

			$PAY_PAL_STATE = substr($BSTATE,0,2);
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"state\" value=\"$PAY_PAL_STATE\">\n";
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"zip\" value=\"$BZIPCODE\">\n";
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"email\" value=\"$BEMAILADDRESS\">\n";


		   $THIS_DISPLAY .= "\n\n<!-- DISPLAY HOLD MESSAGE TO CUSTOMER -->\n\n";

			$THIS_DISPLAY .= "<table border=\"0\" cellpadding=10 cellspacing=0 width=100% height=100%>\n";
			$THIS_DISPLAY .= " <tr>\n";
			$THIS_DISPLAY .= "  <td align=center valign=middle class=text>\n";

//			$THIS_DISPLAY .= "<table border=\"1\" cellpadding=\"40\" cellspacing=\"0\" bgcolor=\"#EFEFEF\" style=\"border: 2px inset black;\">\n";
//			$THIS_DISPLAY .= " <tr>\n";
//			$THIS_DISPLAY .= "  <td align=center valign=middle class=text>\n";

			$THIS_DISPLAY .= "   <div style=\"width: 450px;background-color: #f8f9fd;border: 1px solid #6699cc;padding: 30px;margin-top: -10%;\">\n";

			$THIS_DISPLAY .= "    <font color=red><H3>".lang("Connecting To PayPal")."<font size=2><SUP>TM</SUP></font> ".lang("Secure Payment Server").".</font><BR>".lang("Please Hold")."...</h3><BR><BR>\n";

			if ( $testmodeBool ) {
				$THIS_DISPLAY .= "<div style=\"padding: 5px;background: #fff6bb;\"><strong>Test mode enabled:</strong> You must click the button manually to go to PayPal. This page will not auto-redirect when test mode is on.</div>\n";
			} else {
				$THIS_DISPLAY .= lang("If you are not connected automatically within 20 seconds");
			}
			
			$THIS_DISPLAY .= "<BR><BR><input type=\"submit\" value=\"".lang("Click Here")."\" class=FormLt1>\n";
			


			$THIS_DISPLAY .= "   </div>\n";

//			$THIS_DISPLAY .= "  </td>\n";
//			$THIS_DISPLAY .= " </tr>\n";
//			$THIS_DISPLAY .= "</table>\n";

			$THIS_DISPLAY .= "</td>\n";
			$THIS_DISPLAY .= "</tr>\n";
			$THIS_DISPLAY .= "</table>\n";

			$THIS_DISPLAY .= "\n\n<!-- END HOLD MESSAGE DISPLAY -->\n\n";

			$THIS_DISPLAY .= "</FORM>\n";

			if ( !$testmodeBool ) {
				$THIS_DISPLAY .= "<script language=\"javascript\">window.setTimeout('gateway()', 300);</script>\n";
			}

			$THIS_DISPLAY .= "</BODY>\n";
			$THIS_DISPLAY .= "</HTML>\n";

			echo $THIS_DISPLAY;

			exit;

	} // End PayPal(TM) verification



	if (strlen($nochex_id) > 3 && $GATEWAY_ERR != 1 && $PAY_TYPE == "nochex") {		// PayPal Ready to go!
		mysql_query("UPDATE cart_invoice SET PAY_METHOD = 'NoChex', TRANSACTION_STATUS = 'Sent' WHERE ORDER_NUMBER = '$ORDER_NUMBER'");
		//nochex gateway
		$THIS_DISPLAY .= "<SCRIPT language=JavaScript>\n";
		$THIS_DISPLAY .= " function nochexgateway() {\n";
		$THIS_DISPLAY .= "	window.document.nochexsubmit.submit();\n";
		$THIS_DISPLAY .= " }\n";
		$THIS_DISPLAY .= "</script>\n";
		
		if ( strtolower($_SERVER['HTTPS']) == 'on' ) { $prefixStr = 'https'; } else { $prefixStr = 'http'; }
		$return_urlStr = '';
		if ( $OPTIONS['PAYMENT_SSL'] != '' ) { $return_urlStr = $OPTIONS['PAYMENT_SSL']; } else { $return_urlStr = $prefixStr."://".$_SESSION['docroot_url']; }
		if ($OPTIONS['PAYMENT_SSL'] != ''){
			$return_urlStr = $OPTIONS['PAYMENT_SSL'];
		} else {
			if($_SERVER['SERVER_NAME']!=''){
				$return_urlStr = $prefixStr."://".$_SERVER['SERVER_NAME'];
			} else {
				$return_urlStr = $prefixStr."://".$_SESSION['docroot_url'];
			}
		}
		$description = str_replace(';', ', ', $_SESSION['CART_PRODNAME']);
		$description = preg_replace('/, $/', '', $description);
		$payment_fields['merchant_id'] = $nochex_id;
		$payment_fields['description'] = $description;
		$payment_fields['order_id'] = $ORDER_NUMBER;
		$payment_fields['success_url'] = $return_urlStr.'/shopping/pgm-show_invoice.php?nochex='.$ORDER_NUMBER;
		$payment_fields['test_success_url']= $return_urlStr.'/shopping/pgm-show_invoice.php?nochex='.$ORDER_NUMBER;
		$payment_fields['cancel_url'] = $return_urlStr.'/shopping/nochex_apc.php?status=cancel&orderid='.$ORDER_NUMBER;
		$payment_fields['callback_url'] = $return_urlStr.'/shopping/nochex_apc.php';
		$payment_fields['amount'] = $_SESSION['ORDER_TOTAL'];
		$payment_fields['billing_fullname'] = $BFIRSTNAME." ".$BLASTNAME;
		$payment_fields['billing_address'] = str_replace('  ', ' ', $BADDRESS1." ".$BADDRESS2." ".$BCITY." ".$BSTATE.", ".$BCOUNTRY);
		$payment_fields['billing_postcode'] = $BZIPCODE;
		$payment_fields['customer_phone_number'] = $BPHONE;
		$payment_fields['email_address'] = $BEMAILADDRESS;
		$THIS_DISPLAY .= "<form name=\"nochexsubmit\" method=post action=\"https://secure.nochex.com/\">\n";
		foreach($payment_fields as $var=>$val){
			$THIS_DISPLAY .= "<input type=hidden name=\"".$var."\" value=\"".$val."\">\n";
		}
		$THIS_DISPLAY .= "<script language=\"javascript\">window.setTimeout('nochexgateway()', 300);</script>\n";
		
		echo $THIS_DISPLAY;
		
		exit;
	}

	eval(hook("pgm-payment_gateway.php:gateway_submit"));



	if (strlen($getWorld[WP_INSTALL_ID]) > 4 && $GATEWAY_ERR != 1 && $PAY_TYPE == "WORLDPAY") {		// WorldPay Ready to go!
   	/*----------------------------------------------------------------------------
      __          __        _     _ _____
      \ \        / /       | |   | |  __ \
       \ \  /\  / /__  _ __| | __| | |__) |_ _ _   _
        \ \/  \/ / _ \| '__| |/ _` |  ___/ _` | | | |
         \  /\  / (_) | |  | | (_| | |  | (_| | |_| |
          \/  \/ \___/|_|  |_|\__,_|_|   \__,_|\__, |
                                                __/ |
                                               |___/
   	/*----------------------------------------------------------------------------*/

      //set and format variables to pass
      $wpInstId = $getWorld[WP_INSTALL_ID];

      $wpAddress = $BADDRESS1;
      if (strlen($BADDRESS2) > 1) { $wpAddress .= "&#10;".$BADDRESS2; }

      $tmpWPC = split(" - ", $BCOUNTRY);
      $wpCountry = $tmpWPC[1];


      // ----------------------------------------------------------
      // Update Invoice Table To Indicate We Have Sent Customer
      // To Worldpay, Now We Are Waiting For Them To Come Back
      // Either Manually Or Via Silent Post.
      // ----------------------------------------------------------

      mysql_query("UPDATE cart_invoice SET PAY_METHOD = 'WorldPay', TRANSACTION_STATUS = 'Pending' WHERE ORDER_NUMBER = '$ORDER_NUMBER'");

      echo '

      // ----------------------------------------------------------

      <HTML>
      <HEAD>

      <LINK rel="stylesheet" href="../runtime.css" type="text/css">

      <SCRIPT language=JavaScript>

         function gateway () {
            window.document.paymentgate.submit();
         }

      </SCRIPT>

      </HEAD>

      <BODY BGCOLOR=DARKBLUE TEXT=DARKBLUE>';

         $THIS_DISPLAY = "<form name=paymentgate action=\"https://select.worldpay.com/wcc/purchase\" method=\"post\">\n";
         $THIS_DISPLAY .= "<input type=\"hidden\" name=\"instId\" value=\"$wpInstId\">\n";
         $THIS_DISPLAY .= "<input type=\"hidden\" name=\"cartId\" value=\"$ORDER_NUMBER\">\n";
         $THIS_DISPLAY .= "<input type=\"hidden\" name=\"amount\" value=\"$ORDER_TOTAL\">\n";
         $THIS_DISPLAY .= "<input type=\"hidden\" name=\"currency\" value=\"$dType\">\n";
         $THIS_DISPLAY .= "<input type=\"hidden\" name=\"desc\" value=\"$BFIRSTNAME $BLASTNAME's Order [Invoice #: $ORDER_NUMBER]\">\n";
         $THIS_DISPLAY .= "<input type=\"hidden\" name=\"name\" value=\"$BFIRSTNAME $BLASTNAME\">\n";
         $THIS_DISPLAY .= "<input type=\"hidden\" name=\"address\" value=\"$wpAddress\">\n";
         $THIS_DISPLAY .= "<input type=\"hidden\" name=\"postcode\" value=\"$BZIPCODE\">\n";
         $THIS_DISPLAY .= "<input type=\"hidden\" name=\"country\" value=\"$wpCountry\">\n";
         $THIS_DISPLAY .= "<input type=\"hidden\" name=\"tel\" value=\"$this_bphone\">\n";
         $THIS_DISPLAY .= "<input type=\"hidden\" name=\"email\" value=\"$BEMAILADDRESS\">\n";

         $THIS_DISPLAY .= "<input type=\"hidden\" name=\"M_CART_KEYID\" value=\"".$_SESSION['CART_KEYID']."\">\n";
         $THIS_DISPLAY .= "<input type=\"hidden\" name=\"M_CART_QTY\" value=\"".$_SESSION['CART_QTY']."\">\n";
         $THIS_DISPLAY .= "<input type=\"hidden\" name=\"M_CART_SKUNO\" value=\"".$_SESSION['CART_SKUNO']."\">\n";

         // Test Mode? - Mantis #0000021
         //------------------------------------------------------------
         if ( $wpTest == "ACCEPT" ) {
            $THIS_DISPLAY .= "<input type=\"hidden\" name=\"testMode\" value=\"100\">\n";
         } elseif ( $wpTest == "DECLINE" || $wpTest == "ON" ) { // "ON" state check for back-compatibility
            $THIS_DISPLAY .= "<input type=\"hidden\" name=\"testMode\" value=\"101\">\n";
         }

         if ( $wpHideCurr == "Yes" ) { $THIS_DISPLAY .= "<input type=\"hidden\" name=\"HideCurrency\">\n"; } // Fix currency type?

         $THIS_DISPLAY .= "<input type=\"hidden\" name=\"fixContact\">\n"; // Always fix contact data


      echo $THIS_DISPLAY;

      echo '

      <!-- DISPLAY HOLD MESSAGE TO CUSTOMER -->

      <table border=0 cellpadding=10 cellspacing=0 width=100% height=100%><tr><td align=center valign=middle class=text>

         <table border=0 cellpadding=40 cellspacing=0 bgcolor=#EFEFEF STYLE="border: 2px inset black;"><tr><td align=center valign=middle class=text>

            <font color=red><H3>Connecting To WorldPay<font size=2><SUP>TM</SUP></font> '.lang("Secure Payment Server").'.</font><BR>'.lang("Please Hold").'...</h3><BR><BR>
            '.lang("If you are not connected automatically within 20 seconds").'<BR><BR><input type="submit" value="'.lang("Click Here").' " class=FormLt1>

         </td></tr></table>

      </td></tr></table>

      <!-- END HOLD MESSAGE DISPLAY -->

      </FORM>

      <SCRIPT language=JavaScript>
         gateway();
      </SCRIPT>

      </BODY></HTML>

      ';

      exit;

	} // End WorldPay(TM) verification

//	$THIS_DISPLAY .= "<div align=left class=text><h2><font color=red>CHECKOUT SETUP CC ERROR:</font></h2><font color=darkblue>".lang("The checkout system is configured to utilize online credit card processing, however, there is no VeriSign")."<SUP>TM</SUP> ".lang("information setup nor is there a")." \n";
//	$THIS_DISPLAY .= lang("custom gateway specified.  One of the other must be setup through 'Payment Options' to use the online credit card checkout system.")."<BR><BR><U>HINT</U>:<BR><BR>".lang("If you do not know what these things mean, login to the admin system, select 'Payment Options' in the Shopping Cart module")." \n";
//	$THIS_DISPLAY .= lang("and select 'Offline Processing' then save your settings.")."  ".lang("This should resolve your issue immediately.")."<BR><BR>(".$getWorld['WP_INSTALL_ID'].")</DIV>\n\n";

	if (strlen($PAYSTATION['PAYSTATION_ID']) > 3 && $GATEWAY_ERR != 1 && $PAY_TYPE == "PAYSTATION") {

   	/*----------------------------------------------------------------------------
		 ___              _        _   _
		| _ \__ _ _  _ __| |_ __ _| |_(_)___ _ _
		|  _/ _` | || (_-<  _/ _` |  _| / _ \ ' \
		|_| \__,_|\_, /__/\__\__,_|\__|_\___/_||_|
		          |__/
   	/*----------------------------------------------------------------------------*/
   		$merchant_ref = urlencode($this_ip);
   		$amount		= ($ORDER_TOTAL * 100);

			// ----------------------------------------------------------
			// UPDATE INVOICE TABLE TO INDICATE WE HAVE SENT CUSTOMER
			// TO PAYSTATION, NOW WE ARE WAITING FOR THEM TO COME BACK
			// EITHER MANUALLY OR VIA SILENT POST.
			// ----------------------------------------------------------

			mysql_query("UPDATE cart_invoice SET PAY_METHOD = 'Paystation', TRANSACTION_STATUS = 'Sent' WHERE ORDER_NUMBER = '$ORDER_NUMBER'");


			$THIS_DISPLAY = "<HTML>\n";
			$THIS_DISPLAY .= "<HEAD>\n";

			$THIS_DISPLAY .= "<LINK rel=\"stylesheet\" href=\"../runtime.css\" type=\"text/css\">\n";

			$THIS_DISPLAY .= "<SCRIPT language=JavaScript>\n";

			$THIS_DISPLAY .= " function gateway () {\n";
			$THIS_DISPLAY .= "	window.document.paymentgate.submit();\n";
			$THIS_DISPLAY .= " }\n";
			$THIS_DISPLAY .= "</script>\n";

			$THIS_DISPLAY .= "</head>\n";

			$THIS_DISPLAY .= "<body bgcolor=\"#D3E9EF\" TEXT=\"#336699\">\n";

			$THIS_DISPLAY .= "<form name=paymentgate action=\"https://www.paystation.co.nz/dart/darthttp.dll?paystation\" method=\"post\">\n";

         // Paystation Configuration
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"pi\" value=\"".$PAYSTATION['PAYSTATION_ID']."\">\n";
			$THIS_DISPLAY .= "<input type=\"hidden\" id=\"ms\" name=\"ms\" value=\"".array_sum(explode(chr(32), microtime()))."\">\n";
         //$THIS_DISPLAY .= "<input type=\"hidden\" name=\"tm\" value=\"T\">\n";
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"merchant_ref\" value=\"".$ORDER_NUMBER."\">\n";
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"am\" value=\"".$amount."\">\n";
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"paystation\" value=\"_empty\">\n";

			// Test card accepted
			//$THIS_DISPLAY .= "<input type=\"hidden\" name=\"am\" value=\"1000\">\n";


		   $THIS_DISPLAY .= "\n\n<!-- DISPLAY HOLD MESSAGE TO CUSTOMER -->\n\n";

			$THIS_DISPLAY .= "<table border=0 cellpadding=10 cellspacing=0 width=100% height=100%><tr><td align=center valign=middle class=text>\n";

			$THIS_DISPLAY .= "<table border=0 cellpadding=40 cellspacing=0 bgcolor=#EFEFEF STYLE=\"border: 2px inset black;\"><tr><td align=center valign=middle class=text>\n";

			$THIS_DISPLAY .= "<font color=red><H3>".lang("Connecting To Paystation")."<font size=2><SUP>TM</SUP></font> ".lang("Secure Payment Server").".</font><BR>".lang("Please Hold")."...</h3><BR><BR>\n";
			$THIS_DISPLAY .= lang("If you are not connected automatically within 20 seconds")."<BR><BR><input type=\"submit\" value=\"".lang("Click Here")."\" class=FormLt1>\n";

			$THIS_DISPLAY .= "</td></tr></table>\n";

			$THIS_DISPLAY .= "</td></tr></table>\n";

			$THIS_DISPLAY .= "\n\n<!-- END HOLD MESSAGE DISPLAY -->\n\n";

			$THIS_DISPLAY .= "</FORM>\n";

			$THIS_DISPLAY .= "<script language=\"javascript\">\n";

			# EXPIRIMENTAL: Randomize ms number every load to prevent transaction in progress errors
			$THIS_DISPLAY .= "var msInt = Math.ceil(1500000000000 * Math.random());\n";

			$THIS_DISPLAY .= "document.getElementById('ms').value = msInt;\n";
//			$THIS_DISPLAY .= "alert('random ms generated');\n";
			$THIS_DISPLAY .= "window.document.paymentgate.submit();\n";
			$THIS_DISPLAY .= "</script>\n";

			$THIS_DISPLAY .= "</BODY>\n";
			$THIS_DISPLAY .= "</HTML>\n";

			echo $THIS_DISPLAY;

			exit;

	} // End Paystation verification

} // End "online" process


if ( eregi("offline", $OPTIONS[PAYMENT_PROCESSING_TYPE]) && eregi("CREDITCARD", $PAY_TYPE) ) {		// This is a live (on site) processing option

   /*----------------------------------------------------------------------------
     ____   __  __ _ _               _____   _____
    / __ \ / _|/ _| (_)             / ____| / ____|
   | |  | | |_| |_| |_ _ __   ___  | |     | |
   | |  | |  _|  _| | | '_ \ / _ \ | |     | |
   | |__| | | | | | | | | | |  __/ | |____ | |____ _
    \____/|_| |_| |_|_|_| |_|\___|  \_____(_)_____(_)
   /*----------------------------------------------------------------------------*/

   // ----------------------------------------------------------
   // DISPLAY CHECKOUT ROUTINE STEPS FOR REFERENCE BY CUSTOMER
   // ----------------------------------------------------------
   $THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\" class=\"shopping-selfcontained_box\" id=\"checkout-steps\">\n";
   $THIS_DISPLAY .= " <tr>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= "   ".lang("Step")." 1:<br/>".lang("Customer Sign-in")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 2:<br/>".lang("Billing & Shipping")."<br/>".lang("Information")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 3:<br/>".lang("Shipping Options")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 4:<br/>".lang("Verify Order Details")."<br/>\n";
   $THIS_DISPLAY .= "  </td>\n";
   # Current step
   $THIS_DISPLAY .= "  <th align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= '	<strong>'.lang("Step")." 5:<br/>".lang("Make Payment")."</strong>\n";
   $THIS_DISPLAY .= "  </th>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 6:<br/>".lang("Print Final")."<br/>".lang("Invoice")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= " </tr>\n";
   $THIS_DISPLAY .= "</table>\n";
   $THIS_DISPLAY .= "<br>\n";

   // ----------------------------------------------------------

   ob_start();
      include("prod_offline_card.inc");
      $THIS_DISPLAY .= ob_get_contents();
   ob_end_clean();




} // End "offline" process

if ( $_REQUEST['PAY_TYPE'] == "DPS" ) {


   /*----------------------------------------------------------------------------------------------------------
                                                      DPS
   /*----------------------------------------------------------------------------------------------------------*/

   ######################################################################################
   // Show Checkout Steps at top
   ######################################################################################
   $THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\" class=\"shopping-selfcontained_box\" id=\"checkout-steps\">\n";
   $THIS_DISPLAY .= " <tr>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= "   <b>".lang("Step")." 1:<br/>".lang("Customer Sign-in")."</b>\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 2:<br/>".lang("Billing & Shipping")."<br/>".lang("Information")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 3:<br/>".lang("Shipping Options")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 4:<br/>".lang("Verify Order Details")."<br/>\n";
   $THIS_DISPLAY .= "  </td>\n";
   # Current step
   $THIS_DISPLAY .= "  <th align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 5:<br/>".lang("Make Payment")."\n";
   $THIS_DISPLAY .= "  </th>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 6:<br/>".lang("Print Final")."<br/>".lang("Invoice")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= " </tr>\n";
   $THIS_DISPLAY .= "</table>\n";

   $THIS_DISPLAY .= "<br>\n";

   ######################################################################################
   // Show Credit Card Processing Form
   ######################################################################################
   ob_start();
      include("prod_px_card.php");
      $THIS_DISPLAY .= ob_get_contents();
   ob_end_clean();


} // End Innovative Gateway verification


if ( strlen($getInnov['IG_USER']) > 3 && strlen($getInnov['IG_PASS']) > 3 && $GATEWAY_ERR != 1 && $PAY_TYPE == "INNOVGATE" ) {    // Innovative Gateway Ready to go!

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

   ######################################################################################
   // Show Checkout Steps at top
   ######################################################################################
   $THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\" class=\"shopping-selfcontained_box\" id=\"checkout-steps\">\n";
   $THIS_DISPLAY .= " <tr>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= "   <b>".lang("Step")." 1:<br/>".lang("Customer Sign-in")."</b>\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 2:<br/>".lang("Billing & Shipping")."<br/>".lang("Information")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 3:<br/>".lang("Shipping Options")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 4:<br/>".lang("Verify Order Details")."<br/>\n";
   $THIS_DISPLAY .= "  </td>\n";
   # Current step
   $THIS_DISPLAY .= "  <th align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 5:<br/>".lang("Make Payment")."\n";
   $THIS_DISPLAY .= "  </th>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 6:<br/>".lang("Print Final")."<br/>".lang("Invoice")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= " </tr>\n";
   $THIS_DISPLAY .= "</table>\n";

   $THIS_DISPLAY .= "<br>\n";

   ######################################################################################
   // Show Credit Card Processing Form
   ######################################################################################
   
   
   ob_start();
      include_once("prod_innov_card.php");
      $THIS_DISPLAY .= ob_get_contents();
   ob_end_clean();


} // End Innovative Gateway verification


if ( strlen($getStore['SC_ACCTID']) > 3 && $GATEWAY_ERR != 1 && $PAY_TYPE == "PAYPOINT" ) {

   /*----------------------------------------------------------------------------------------------------------*
    _____               _____        _         _       _    _   _____
   |  __ \             |  __ \      (_)       | |     | |  | | / ____|   /\
   | |__) |__ _  _   _ | |__) |___   _  _ __  | |_    | |  | || (___    /  \
   |  ___// _` || | | ||  ___// _ \ | || '_ \ | __|   | |  | | \___ \  / /\ \
   | |   | (_| || |_| || |   | (_) || || | | || |_    | |__| | ____) |/ ____ \
   |_|    \__,_| \__, ||_|    \___/ |_||_| |_| \__|    \____/ |_____//_/    \_\
                  __/ |
                 |___/
   /*----------------------------------------------------------------------------------------------------------*/

   ######################################################################################
   // Show Checkout Steps at top
   ######################################################################################
   $THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\" class=\"shopping-selfcontained_box\" id=\"checkout-steps\">\n";
   $THIS_DISPLAY .= " <tr>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= "   <b>".lang("Step")." 1:<br/>".lang("Customer Sign-in")."</b>\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 2:<br/>".lang("Billing & Shipping")."<br/>".lang("Information")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 3:<br/>".lang("Shipping Options")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 4:<br/>".lang("Verify Order Details")."<br/>\n";
   $THIS_DISPLAY .= "  </td>\n";
   # Current step
   $THIS_DISPLAY .= "  <th align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 5:<br/>".lang("Make Payment")."\n";
   $THIS_DISPLAY .= "  </th>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 6:<br/>".lang("Print Final")."<br/>".lang("Invoice")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= " </tr>\n";
   $THIS_DISPLAY .= "</table>\n";

   $THIS_DISPLAY .= "<br>\n";


   ######################################################################################
   // Show Credit Card Processing Form
   ######################################################################################
   ob_start();
      include("prod_paypoint_card.php");
      $THIS_DISPLAY .= ob_get_contents();
   ob_end_clean();


} // End Innovative Gateway verification

if ( strlen($getAuth['AN_ACCTID']) > 3 && strlen($getAuth['AN_ACCTKEY']) > 3 && $GATEWAY_ERR != 1 && $PAY_TYPE == "AUTHORIZENET" ) {

   /*---------------------------------------------------------------------------------------------------------*
      _         _    _               _                         _
     /_\  _  _ | |_ | |_   ___  _ _ (_) ___ ___     _ _   ___ | |_
    / _ \| || ||  _|| ' \ / _ \| '_|| ||_ // -_) _ | ' \ / -_)|  _|
   /_/ \_\\_,_| \__||_||_|\___/|_|  |_|/__|\___|(_)|_||_|\___| \__|

   /*---------------------------------------------------------------------------------------------------------*/

   ######################################################################################
   // Show Checkout Steps at top
   ######################################################################################
   $THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\" class=\"shopping-selfcontained_box\" id=\"checkout-steps\">\n";
   $THIS_DISPLAY .= " <tr>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= "   <b>".lang("Step")." 1:<br/>".lang("Customer Sign-in")."</b>\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 2:<br/>".lang("Billing & Shipping")."<br/>".lang("Information")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 3:<br/>".lang("Shipping Options")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 4:<br/>".lang("Verify Order Details")."<br/>\n";
   $THIS_DISPLAY .= "  </td>\n";
   # Current step
   $THIS_DISPLAY .= "  <th align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 5:<br/>".lang("Make Payment")."\n";
   $THIS_DISPLAY .= "  </th>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 6:<br/>".lang("Print Final")."<br/>".lang("Invoice")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= " </tr>\n";
   $THIS_DISPLAY .= "</table>\n";

   $THIS_DISPLAY .= "<br>\n";


   ######################################################################################
   // Show Credit Card Processing Form
   #####################################################################################
   ob_start();
      include("prod_authorize_card.php");
      $THIS_DISPLAY .= ob_get_contents();
   ob_end_clean();


} // End Authorize.net verification


if ( $PAY_TYPE == "transactium" || $_GET['todo'] == 'transactium_error' || $_GET['todo'] == 'transactium_success' ) {
   /*---------------------------------------------------------------------------------------------------------*
		# TRANSACTIUM
   /*---------------------------------------------------------------------------------------------------------*/
   $THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\" class=\"shopping-selfcontained_box\" id=\"checkout-steps\">\n";
   $THIS_DISPLAY .= " <tr>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= "   <b>".lang("Step")." 1:<br/>".lang("Customer Sign-in")."</b>\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 2:<br/>".lang("Billing & Shipping")."<br/>".lang("Information")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 3:<br/>".lang("Shipping Options")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 4:<br/>".lang("Verify Order Details")."<br/>\n";
   $THIS_DISPLAY .= "  </td>\n";
   # Current step
   $THIS_DISPLAY .= "  <th align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 5:<br/>".lang("Make Payment")."\n";
   $THIS_DISPLAY .= "  </th>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 6:<br/>".lang("Print Final")."<br/>".lang("Invoice")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= " </tr>\n";
   $THIS_DISPLAY .= "</table>\n";
   $THIS_DISPLAY .= "<br>\n";
   
   ob_start();
      include("prod_transactium_card.php");
      $THIS_DISPLAY .= ob_get_contents();
   ob_end_clean();


} // End Authorize.net verification


//internetsecure
if (strlen($IS_acctkey) > 3 && $GATEWAY_ERR != 1 && $PAY_TYPE == "internetsecure" ) {

   /*---------------------------------------------------------------------------------------------------------*/

   ######################################################################################
   // Show Checkout Steps at top
   ######################################################################################
   $THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\" class=\"shopping-selfcontained_box\" id=\"checkout-steps\">\n";
   $THIS_DISPLAY .= " <tr>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= "   <b>".lang("Step")." 1:<br/>".lang("Customer Sign-in")."</b>\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 2:<br/>".lang("Billing & Shipping")."<br/>".lang("Information")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 3:<br/>".lang("Shipping Options")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 4:<br/>".lang("Verify Order Details")."<br/>\n";
   $THIS_DISPLAY .= "  </td>\n";
   # Current step
   $THIS_DISPLAY .= "  <th align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 5:<br/>".lang("Make Payment")."\n";
   $THIS_DISPLAY .= "  </th>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 6:<br/>".lang("Print Final")."<br/>".lang("Invoice")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= " </tr>\n";
   $THIS_DISPLAY .= "</table>\n";

   $THIS_DISPLAY .= "<br>\n";


   ######################################################################################
   // Show Credit Card Processing Form
   ######################################################################################
   ob_start();
      include("prod_internetsecure_card.php");
      $THIS_DISPLAY .= ob_get_contents();
   ob_end_clean();


} // End internetsecure verification



if ( strlen($EWAY['EWAY_ID']) > 3 && $GATEWAY_ERR != 1 && $PAY_TYPE == "EWAY" ) {

   /*---------------------------------------------------------------------------------------------------------*
      __      _____   __
    __\ \    / /_\ \ / /
   / -_) \/\/ / _ \ V /
   \___|\_/\_/_/ \_\_|

   /*---------------------------------------------------------------------------------------------------------*/

   ######################################################################################
   // Show Checkout Steps at top
   ######################################################################################
   $THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\" class=\"shopping-selfcontained_box\" id=\"checkout-steps\">\n";
   $THIS_DISPLAY .= " <tr>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= "   ".lang("Step")." 1:<br/>".lang("Customer Sign-in")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 2:<br/>".lang("Billing & Shipping")."<br/>".lang("Information")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 3:<br/>".lang("Shipping Options")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 4:<br/>".lang("Verify Order Details")."<br/>\n";
   $THIS_DISPLAY .= "  </td>\n";
   # Current step
   $THIS_DISPLAY .= "  <th align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 5:<br/>".lang("Make Payment")."\n";
   $THIS_DISPLAY .= "  </th>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 6:<br/>".lang("Print Final")."<br/>".lang("Invoice")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= " </tr>\n";
   $THIS_DISPLAY .= "</table>\n";

   $THIS_DISPLAY .= "<br>\n";


   ######################################################################################
   // Show Credit Card Processing Form
   ######################################################################################
   ob_start();
      include("prod_eway_card.php");
      $THIS_DISPLAY .= ob_get_contents();
   ob_end_clean();


} // End eWAY verification

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// A: PROCESS CHECK OR MONEY ORDER PAYMENT
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if (eregi("Y", $OPTIONS['PAYMENT_CHECK_ONLY']) || $PAY_TYPE == "CHECK") {			// Process this as a check/money order payment
   $DOWNLOAD_AVAIL = "NO";
   /*----------------------------------------------------------------------------
    __  __                           ____          _
   |  \/  |                         / __ \        | |
   | \  / | ___  _ __   ___ _   _  | |  | |_ __ __| | ___ _ __
   | |\/| |/ _ \| '_ \ / _ \ | | | | |  | | '__/ _` |/ _ \ '__|
   | |  | | (_) | | | |  __/ |_| | | |__| | | | (_| |  __/ |
   |_|  |_|\___/|_| |_|\___|\__, |  \____/|_|  \__,_|\___|_|
                             __/ |
                            |___/
   /*----------------------------------------------------------------------------*/

	// This is the easiest one to deal with, display the invoice; allow printing; update invoice data table
	// and wait on your  money. :)

		// ----------------------------------------------------------
		// DISPLAY CHECKOUT ROUTINE STEPS FOR REFERENCE BY CUSTOMER
		// ----------------------------------------------------------
      $THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\" class=\"shopping-selfcontained_box\" id=\"checkout-steps\">\n";
      $THIS_DISPLAY .= " <tr>\n";
      $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
      $THIS_DISPLAY .= "   <b>".lang("Step")." 1:<br/>".lang("Customer Sign-in")."</b>\n";
      $THIS_DISPLAY .= "  </td>\n";
      $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
      $THIS_DISPLAY .= lang("Step")." 2:<br/>".lang("Billing & Shipping")."<br/>".lang("Information")."\n";
      $THIS_DISPLAY .= "  </td>\n";
      $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
      $THIS_DISPLAY .= lang("Step")." 3:<br/>".lang("Shipping Options")."\n";
      $THIS_DISPLAY .= "  </td>\n";
      $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
      $THIS_DISPLAY .= lang("Step")." 4:<br/>".lang("Verify Order Details")."<br/>\n";
      $THIS_DISPLAY .= "  </td>\n";
      # Current step
      $THIS_DISPLAY .= "  <th align=\"center\" valign=\"top\">\n";
      $THIS_DISPLAY .= lang("Step")." 5:<br/>".lang("Make Payment")."\n";
      $THIS_DISPLAY .= "  </th>\n";
      $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
      $THIS_DISPLAY .= lang("Step")." 6:<br/>".lang("Print Final")."<br/>".lang("Invoice")."\n";
      $THIS_DISPLAY .= "  </td>\n";
      $THIS_DISPLAY .= " </tr>\n";
      $THIS_DISPLAY .= "</table><br/>\n";

		// ----------------------------------------------------------
		// DISPLAY INSTRUCTIONS ON HOW TO PAY FOR THIS ORDER
		// ----------------------------------------------------------

		$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=4 CELLSPACING=0 WIDTH=100% CLASS=text>\n";
		$THIS_DISPLAY .= "<TR><TD ALIGN=LEFT VALIGN=TOP>\n";
		$THIS_DISPLAY .= lang("To pay by check or money order").", <a href=\"#\" onclick=\"window.open('pgm-print_invoice.php?invoice_num=".$ORDER_NUMBER."','print_inv','width=800px, height=600px, resizable');\">".lang("print this page now")."</a>, ".lang("attach it to your check or money order and mail it to the address at the top left of your invoice").". ".lang("Thank you for your order")."!\n";
		$THIS_DISPLAY .= "</TD></TR></TABLE><BR>\n\n";

		// ----------------------------------------------------------
		// DISPLAY PAYABLE NAME AND ADDRESS FOR SENDING PAYMENT
		// ----------------------------------------------------------

		$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=4 CELLSPACING=0 WIDTH=100% CLASS=text>\n";
		$THIS_DISPLAY .= "<TR><TD ALIGN=LEFT VALIGN=TOP class=text WIDTH=50%>\n";

			$THIS_DISPLAY .= "<FONT COLOR=MAROON><B><U>".lang("Make Check/Money Order Payable to")."</U>:</B><BR>$OPTIONS[BIZ_PAYABLE]<BR>\n";
			$THIS_DISPLAY .= "$OPTIONS[BIZ_ADDRESS_1]<BR>\n";
				if ($OPTIONS['BIZ_ADDRESS_2'] != "") {
					$THIS_DISPLAY .= $OPTIONS['BIZ_ADDRESS_2']."<BR>";
				}
			$THIS_DISPLAY .= "$OPTIONS[BIZ_CITY], $OPTIONS[BIZ_STATE]  $OPTIONS[BIZ_POSTALCODE]<BR>\n";
			$THIS_DISPLAY .= "$OPTIONS[BIZ_COUNTRY]<br>\n";
			$THIS_DISPLAY .= "$OPTIONS[BIZ_PHONE]</FONT>\n";

		$THIS_DISPLAY .= "</TD><TD ALIGN=LEFT VALIGN=TOP class=text WIDTH=50%>\n";

			$THIS_DISPLAY .= "<BR><B>".lang("Order Date")."</B>: <font size=3><TT>$ORDER_DATE &nbsp;&nbsp;$ORDER_TIME</TT></FONT><BR><B>".lang("Order Number")."</B>: <font size=3><TT>$ORDER_NUMBER</TT></FONT> &nbsp;";

		$THIS_DISPLAY .= "</TD>\n";
		$THIS_DISPLAY .= "</TR></TABLE><BR>\n\n";

		// ----------------------------------------------------------
		// DISPLAY FINAL INVOICE HTML
		// ----------------------------------------------------------
      if ($OPTIONS['INVOICE_INCLUDE'] != "" && $disOrder != "Cancelled" ){
      	ob_start();
      		include("../media/".$OPTIONS['INVOICE_INCLUDE']);
      		$THIS_DISPLAY .= ob_get_contents();
      	ob_end_clean();
      }
		$THIS_DISPLAY .= $INVOICE;

		$THIS_DISPLAY .= "<br><br><center><H2>".lang("Thank You")."!</H2></center>&nbsp;";

		// ----------------------------------------------------------
		// UPDATE INVOICE TABLE TO INDICATE PENDING CHECK/MONEY ORDER
		// ----------------------------------------------------------

      mysql_query("UPDATE cart_invoice SET PAY_METHOD = 'Check or Money Order', TRANSACTION_STATUS = 'Pending' WHERE ORDER_NUMBER = '$ORDER_NUMBER'");


      ////////////////////////////////////////////////
      ////////////////////// Insert Custom Form Data!!
      function db_string_formatz($string) {
         if ( !get_magic_quotes_gpc() ) {
            return mysql_real_escape_string($string);
         } else {
            return $string;
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
							$the_value = eregi_replace("_", ' ', db_string_formatz($xaa[$xf]));
							$formstuffs[$the_TABLE] = $the_value;

            }
						$form_x_of_total = $xf + 1;
						$formstuffs['FORM_NUMBER'] = $form_x_of_total." of ".$fcount;
						$formstuffs['PURCHASER'] = $_SESSION['BFIRSTNAME']." ".$_SESSION['BLASTNAME'];
            $formstuffs['ORDER_DATE'] = $form_order_date;

						$frminsrt = new mysql_insert($TABLE_NAME, $formstuffs);
						$frminsrt->insert();

            $xf++;
         }

         unset($_SESSION['formdata'][$tt]);
      }

      ////////////////////// End Insert Custom Form Data!!
      ////////////////////////////////////////////////////


      // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
      // EMAIL APPROPRIATE RECIEPTS TO CUSTOMER AND WEBMASTER
      // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
      //$ORDER_NUMBER = $ORDER_NUMBER;

      if ( $cartpref->get("check-sendemail") != "no" ) {
         include("pgm-email_notify.php");
      }

} // End "check/money order" process

##########################################################################

//	   #######   ###    ##    ####
// 	##        ####   ##    ## ##
//	   ##        ## ##  ##    ##  ##
//    ####      ##  ## ##    ##   ##
//    ##        ##   ####    ##  ##
//    ##        ##    ###    ## ##
//    #######   ##     ##    ####

##########################################################################
### BUILD OVERALL TABLE TO PLACE FINAL OUTPUT WITHIN
##########################################################################

$FINAL_DISPLAY = "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" class=\"parent_table\" align=\"center\">\n";
$FINAL_DISPLAY .= "<TR>\n";
$FINAL_DISPLAY .= "<TD ALIGN=CENTER VALIGN=TOP>\n\n$THIS_DISPLAY\n\n</TD>\n";
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
$_SESSION['CART_KEYID'] = $CART_KEYID;
$_SESSION['CART_SKUNO'] = $CART_SKUNO;
$_SESSION['CART_CATNO'] = $CART_CATNO;
$_SESSION['CART_PRODNAME'] = $CART_PRODNAME;
$_SESSION['CART_SUBCAT'] = $CART_SUBCAT;
$_SESSION['CART_VARNAME'] = $CART_VARNAME;
$_SESSION['CART_FORMDATA'] = $CART_FORMDATA;
$_SESSION['CART_UNITPRICE'] = $CART_UNITPRICE;
$_SESSION['CART_QTY'] = $CART_QTY;
$_SESSION['CART_UNITSUBTOTAL'] = $CART_UNITSUBTOTAL;
$_SESSION['WIN_FULL_PATH'] = $WIN_FULL_PATH;
$_SESSION['ORDER_NUMBER'] = $ORDER_NUMBER;
$_SESSION['ORDER_TIME'] = $ORDER_TIME;
$_SESSION['ORDER_TOTAL'] = $ORDER_TOTAL;
$_SESSION['SHIPPING_TOTAL'] = $SHIPPING_TOTAL;
$_SESSION['INVOICE'] = $INVOICE;
$_SESSION['BFIRSTNAME'] = $BFIRSTNAME;
$_SESSION['BLASTNAME'] = $BLASTNAME;
$_SESSION['BCOMPANY'] = $BCOMPANY;
$_SESSION['BADDRESS1'] = $BADDRESS1;
$_SESSION['BADDRESS2'] = $BADDRESS2;
$_SESSION['BCITY'] = $BCITY;
$_SESSION['BZIPCODE'] = $BZIPCODE;
$_SESSION['BSTATE'] = $BSTATE;
$_SESSION['BCOUNTRY'] = $BCOUNTRY;
$_SESSION['BPHONE'] = $BPHONE;
$_SESSION['BEMAILADDRESS'] = $BEMAILADDRESS;
$_SESSION['SFIRSTNAME'] = $SFIRSTNAME;
$_SESSION['SLASTNAME'] = $SLASTNAME;
$_SESSION['SCOMPANY'] = $SCOMPANY;
$_SESSION['SADDRESS1'] = $SADDRESS1;
$_SESSION['SADDRESS2'] = $SADDRESS2;
$_SESSION['SCITY'] = $SCITY;
$_SESSION['SZIPCODE'] = $SZIPCODE;
$_SESSION['SSTATE'] = $SSTATE;
$_SESSION['SCOUNTRY'] = $SCOUNTRY;
$_SESSION['SPHONE'] = $SPHONE;

$module_active = "yes";
include ("pgm-template_builder.php");

#######################################################

echo ("$template_header\n");

	$template_footer = eregi_replace("#CONTENT#", $FINAL_DISPLAY, $template_footer);

echo ("$template_footer\n\n");

echo ("\n\n<SCRIPT language=Javascript>\n     window.focus();\n</SCRIPT>\n\n");

if($_SESSION['ORDER_NUMBER'] != '' && $_REQUEST['PAY_TYPE'] == "CHECK"){
	$fnl_disp = $template_header."\n";
	$fnl_disp .= $template_footer."\n";
	$fnl_disp .= "\n<SCRIPT language=Javascript>\n     window.focus();\n</SCRIPT>\n";
	$_SESSION['final_display_reload'] = '';
	$_SESSION['final_display_reload'] = $fnl_disp;
}

$cart_session['CART_KEYID'] = $CART_KEYID;
$cart_session['CART_SKUNO'] = $CART_SKUNO;
$cart_session['CART_CATNO'] = $CART_CATNO;
$cart_session['CART_PRODNAME'] = $CART_PRODNAME;
$cart_session['CART_SUBCAT'] = $CART_SUBCAT;
$cart_session['CART_VARNAME'] = $CART_VARNAME;
$cart_session['CART_FORMDATA'] = $CART_FORMDATA;
$cart_session['CART_UNITPRICE'] = $CART_UNITPRICE;
$cart_session['CART_QTY'] = $CART_QTY;
$cart_session['CART_UNITSUBTOTAL'] = $CART_UNITSUBTOTAL;
$cart_session['WIN_FULL_PATH'] = $WIN_FULL_PATH;
$cart_session['ORDER_NUMBER'] = $ORDER_NUMBER;
$cart_session['ORDER_TIME'] = $ORDER_TIME;
$cart_session['ORDER_TOTAL'] = $ORDER_TOTAL;
$cart_session['SHIPPING_TOTAL'] = $SHIPPING_TOTAL;
$cart_session['INVOICE'] = $INVOICE;
$cart_session['BFIRSTNAME'] = $BFIRSTNAME;
$cart_session['BLASTNAME'] = $BLASTNAME;
$cart_session['BCOMPANY'] = $BCOMPANY;
$cart_session['BADDRESS1'] = $BADDRESS1;
$cart_session['BADDRESS2'] = $BADDRESS2;
$cart_session['BCITY'] = $BCITY;
$cart_session['BZIPCODE'] = $BZIPCODE;
$cart_session['BSTATE'] = $BSTATE;
$cart_session['BCOUNTRY'] = $BCOUNTRY;
$cart_session['BPHONE'] = $BPHONE;
$cart_session['BEMAILADDRESS'] = $BEMAILADDRESS;
$cart_session['SFIRSTNAME'] = $SFIRSTNAME;
$cart_session['SLASTNAME'] = $SLASTNAME;
$cart_session['SCOMPANY'] = $SCOMPANY;
$cart_session['SADDRESS1'] = $SADDRESS1;
$cart_session['SADDRESS2'] = $SADDRESS2;
$cart_session['SCITY'] = $SCITY;
$cart_session['SZIPCODE'] = $SZIPCODE;
$cart_session['SSTATE'] = $SSTATE;
$cart_session['SCOUNTRY'] = $SCOUNTRY;
$cart_session['SPHONE'] = $SPHONE;



if ( $_REQUEST['PAY_TYPE'] == "CHECK" ) {
   foreach ( $cart_session as $key=>$var ) {
	// Commented out in v4.93 r1 // Cameron comented back in 4.93 r2
	unset($_SESSION[$key]);
   }
}

exit;

?>