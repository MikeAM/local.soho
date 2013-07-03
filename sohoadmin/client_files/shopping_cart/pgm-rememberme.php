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
session_cache_limiter('none'); 
session_start();
track_vars;

##########################################################################
### WE WILL NEED TO KNOW THE DATABASE NAME; UN; PW; ETC TO OPERATE THE  
### REAL-TIME EXECUTION.  THIS IS CONFIGURED IN THE isp.conf FILE      
##########################################################################

include("pgm-cart_config.php");
$dot_com = $this_ip;	// Assign dot_com variable to configured ip address

##########################################################################
### IF CUSTOMERS TABLE EXISTS; FIND THIS GUY OR ERROR BACK TO LOGIN
##########################################################################

$ERROR = "NO";

$match = 0;		
$tablename = "cart_customers";
$result = soho_list_tables();
$i = 0; 
while ($i < mysql_num_rows ($result)) { 
	$tb_names[$i] = mysql_tablename ($result, $i); 
	if ($tb_names[$i] == $tablename) {
		$match = 1;
	}
	$i++;
} 

if ($match != 1) { 

	$ERROR = "YES";

} else {

	$SCUN = strtoupper($SCUN);
	$SCPW = strtoupper($SCPW);	// Make un/pw NON case sensitive.  This is Linux

	$result = mysql_query("SELECT * FROM $tablename WHERE UPPER(USERNAME) = '$SCUN' AND UPPER(PASSWORD) = '$SCPW'");
	$num = mysql_num_rows($result);

	if ($num != 0) {

		$row = mysql_fetch_array($result);
		
		$BFIRSTNAME = $row['BILLTO_FIRSTNAME'];
		$BLASTNAME = $row['BILLTO_LASTNAME'];
		$BCOMPANY = $row['BILLTO_COMPANY'];
		$BADDRESS1 = $row['BILLTO_ADDR1'];
		$BADDRESS2 = $row['BILLTO_ADDR2'];
		$BCITY = $row['BILLTO_CITY'];
		$BSTATE = $row['BILLTO_STATE'];
		$BCOUNTRY = $row['BILLTO_COUNTRY'];
		$BZIPCODE = $row['BILLTO_ZIPCODE'];
		$BPHONE = $row['BILLTO_PHONE'];
		$BEMAILADDRESS = $row['BILLTO_EMAILADDR'];

		$SFIRSTNAME = $row['SHIPTO_FIRSTNAME'];
		$SLASTNAME = $row['SHIPTO_LASTNAME'];
		$SCOMPANY = $row['SHIPTO_COMPANY'];
		$SADDRESS1 = $row['SHIPTO_ADDR1'];
		$SADDRESS2 = $row['SHIPTO_ADDR2'];
		$SCITY = $row['SHIPTO_CITY'];
		$SSTATE = $row['SHIPTO_STATE'];
		$SCOUNTRY = $row['SHIPTO_COUNTRY'];
		$SZIPCODE = $row['SHIPTO_ZIPCODE'];
		$SPHONE = $row['SHIPTO_PHONE'];
		

		// ----------------------------------------------------------------------
		// Register "Remember Me" data into memory now
		// ----------------------------------------------------------------------

		$_SESSION['BPASSWORD'] = $_POST['SCPW'];
		
		if($BFIRSTNAME != ''){ $_SESSION['BFIRSTNAME'] = $BFIRSTNAME; }
		if($BLASTNAME != ''){ $_SESSION['BLASTNAME'] = $BLASTNAME; }
		if($BCOMPANY != ''){ $_SESSION['BCOMPANY'] = $BCOMPANY; }
		if($BADDRESS1 != ''){ $_SESSION['BADDRESS1'] = $BADDRESS1; }
		if($BADDRESS2 != ''){ $_SESSION['BADDRESS2'] = $BADDRESS2; }
		if($BCITY != ''){ $_SESSION['BCITY'] = $BCITY; }
		if($BSTATE != ''){ $_SESSION['BSTATE'] = $BSTATE; }
		if($BCOUNTRY != ''){ $_SESSION['BCOUNTRY'] = $BCOUNTRY; }
		if($BZIPCODE != ''){ $_SESSION['BZIPCODE'] = $BZIPCODE; }
		if($BEMAILADDRESS != ''){ $_SESSION['BEMAILADDRESS'] = $BEMAILADDRESS; }
		if($BPHONE != ''){ $_SESSION['BPHONE'] = $BPHONE; }
		if($SFIRSTNAME != ''){ $_SESSION['SFIRSTNAME'] = $SFIRSTNAME; }
		if($SLASTNAME != ''){ $_SESSION['SLASTNAME'] = $SLASTNAME; }
		if($SCOMPANY != ''){ $_SESSION['SCOMPANY'] = $SCOMPANY; }
		if($SADDRESS1 != ''){ $_SESSION['SADDRESS1'] = $SADDRESS1; }
		if($SADDRESS2 != ''){ $_SESSION['SADDRESS2'] = $SADDRESS2; }
		if($SCITY != ''){ $_SESSION['SCITY'] = $SCITY; }
		if($SSTATE != ''){ $_SESSION['SSTATE'] = $SSTATE; }
		if($SCOUNTRY != ''){ $_SESSION['SCOUNTRY'] = $SCOUNTRY; }
		if($SZIPCODE != ''){ $_SESSION['SZIPCODE'] = $SZIPCODE; }
		if($SPHONE != ''){ $_SESSION['SPHONE'] = $SPHONE; }
		if($REPEATCUSTOMER != ''){ $_SESSION['REPEATCUSTOMER'] = $REPEATCUSTOMER; }
		
		$REPEATCUSTOMER = "YES";

	} else {

		$ERROR = "YES";

	}

}

if ($ERROR != "YES") {
	header("Location: pgm-checkout.php?customernumber=$customernumber&customer_active=Y&=SID");
	exit;
} else {
	header("Location: pgm-checkout.php?customernumber=$customernumber&rem_err=1&=SID");
	exit;
}


?>