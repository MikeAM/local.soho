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

error_reporting(0);
session_start();
track_vars;

##########################################################################
### WE WILL NEED TO KNOW THE DATABASE NAME; UN; PW; ETC TO OPERATE THE  
### REAL-TIME EXECUTION.  THIS IS CONFIGURED IN THE isp.conf FILE      
##########################################################################

include("pgm-cart_config.php");
$dot_com = $this_ip;	// Assign dot_com variable to configured ip address

##########################################################################
### READ SHOPPING CART SETUP OPTIONS
##########################################################################

$result = mysql_query("SELECT * FROM cart_options");
$OPTIONS = mysql_fetch_array($result);

// ------------------------------------------------------------------------------
// THIS SCRIPT IS FOR SILENT POSTING FROM VERISIGN AND WORLDPAY ONLY. IN CASE 
// USER ENCOUNTERS AN ERROR WHEN LINKING BACK TO FINAL INVOICE, WE STILL GOT THE DATA.
// -------------------------------------------------------------------------------

reset($HTTP_POST_VARS);
while (list($name, $value) = each($HTTP_POST_VARS)) {
	$value = stripslashes($value);
	${$name} = $value;
}

// ---------------------------------------------------------------------------
// Find out what type of return this is
// ---------------------------------------------------------------------------

$VERISIGN_CONFIRM = 0;

if ($PNREF != "" && $USER4 == "VERISIGN_GATEWAY") {		// We just identified this as a verisign return post

	// Update Transaction ID and Status
	// ---------------------------------

	mysql_query("UPDATE cart_invoice SET 
		TRANSACTION_ID = '$PNREF', 
		TRANSACTION_STATUS = 'Closed', 
		PAY_METHOD = 'Verisign' WHERE ORDER_NUMBER = '$USER3'");

	
} elseif ($transId != "" && $transStatus != "") {		// We just identified this as a WorldPay return post
	if ($transStatus == "Y") {
	   $transStatus = "Paid";
	} elseif ($transStatus == "C") {
	   $transStatus = "Declined";
	}
	// Update Transaction ID and Status
	// ---------------------------------

	mysql_query("UPDATE cart_invoice SET 
		TRANSACTION_ID = '$transId', 
		TRANSACTION_STATUS = '$transStatus', 
		PAY_METHOD = 'WorldPay' WHERE ORDER_NUMBER = '$cartId'");

	// -----------------------------------------------------------------------
	// Pull Invoice HTML from invoice data table for display of final invoice
	// -----------------------------------------------------------------------

	$result = mysql_query("SELECT INVOICE_HTML FROM cart_invoice WHERE ORDER_NUMBER = '$cartId'");
	$tmp = mysql_fetch_array($result);

	if ($transStatus == "Paid") {
	    $DOWNLOAD_AVAIL = "YES";

	   # Set vars needed for email notify
		$INVOICE = $tmp['INVOICE_HTML'];
		$ORDER_NUMBER = $cartId;
		$ORDER_DATE = date("F j, Y");
		$ORDER_TIME = "";
		$_SESSION['CART_KEYID'] = $_REQUEST['M_CART_KEYID'];
		$_SESSION['CART_QTY'] = $_REQUEST['M_CART_QTY'];
		$_SESSION['CART_SKUNO'] = $_REQUEST['M_CART_SKUNO'];
		$_SESSION['BEMAILADDRESS'] = $tmp['BILLTO_EMAILADDR'];

		include("pgm-inventory.php");
		include("pgm-email_notify.php");
	}
} // End WorldPay Return

?>
