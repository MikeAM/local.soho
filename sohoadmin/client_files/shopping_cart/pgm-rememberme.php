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
$result = mysql_list_tables("$db_name");
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
		
		$BFIRSTNAME = $row[BILLTO_FIRSTNAME];
		$BLASTNAME = $row[BILLTO_LASTNAME];
		$BCOMPANY = $row[BILLTO_COMPANY];
		$BADDRESS1 = $row[BILLTO_ADDR1];
		$BADDRESS2 = $row[BILLTO_ADDR2];
		$BCITY = $row[BILLTO_CITY];
		$BSTATE = $row[BILLTO_STATE];
		$BCOUNTRY = $row[BILLTO_COUNTRY];
		$BZIPCODE = $row[BILLTO_ZIPCODE];
		$BPHONE = $row[BILLTO_PHONE];
		$BEMAILADDRESS = $row[BILLTO_EMAILADDR];

		$SFIRSTNAME = $row[SHIPTO_FIRSTNAME];
		$SLASTNAME = $row[SHIPTO_LASTNAME];
		$SCOMPANY = $row[SHIPTO_COMPANY];
		$SADDRESS1 = $row[SHIPTO_ADDR1];
		$SADDRESS2 = $row[SHIPTO_ADDR2];
		$SCITY = $row[SHIPTO_CITY];
		$SSTATE = $row[SHIPTO_STATE];
		$SCOUNTRY = $row[SHIPTO_COUNTRY];
		$SZIPCODE = $row[SHIPTO_ZIPCODE];
		$SPHONE = $row[SHIPTO_PHONE];
		

		// ----------------------------------------------------------------------
		// Register "Remember Me" data into memory now
		// ----------------------------------------------------------------------

		$_SESSION['BPASSWORD'] = $_POST['SCPW'];
		
		
		if (!session_is_registered("BFIRSTNAME")) { session_register("BFIRSTNAME"); }
		if (!session_is_registered("BLASTNAME")) { session_register("BLASTNAME"); }
		if (!session_is_registered("BCOMPANY")) { session_register("BCOMPANY"); }
		if (!session_is_registered("BADDRESS1")) { session_register("BADDRESS1"); }
		if (!session_is_registered("BADDRESS2")) { session_register("BADDRESS2"); }
		if (!session_is_registered("BCITY")) { session_register("BCITY"); }
		if (!session_is_registered("BSTATE")) { session_register("BSTATE"); }
		if (!session_is_registered("BCOUNTRY")) { session_register("BCOUNTRY"); }
		if (!session_is_registered("BZIPCODE")) { session_register("BZIPCODE"); }
		if (!session_is_registered("BEMAILADDRESS")) { session_register("BEMAILADDRESS"); }

		if (!session_is_registered("BPHONE")) { session_register("BPHONE"); }


		if (!session_is_registered("SFIRSTNAME")) { session_register("SFIRSTNAME"); }
		if (!session_is_registered("SLASTNAME")) { session_register("SLASTNAME"); }
		if (!session_is_registered("SCOMPANY")) { session_register("SCOMPANY"); }
		if (!session_is_registered("SADDRESS1")) { session_register("SADDRESS1"); }
		if (!session_is_registered("SADDRESS2")) { session_register("SADDRESS2"); }
		if (!session_is_registered("SCITY")) { session_register("SCITY"); }
		if (!session_is_registered("SSTATE")) { session_register("SSTATE"); }
		if (!session_is_registered("SCOUNTRY")) { session_register("SCOUNTRY"); }
		if (!session_is_registered("SZIPCODE")) { session_register("SZIPCODE"); }

		if (!session_is_registered("SPHONE")) { session_register("SPHONE"); }

		if (!session_is_registered("REPEATCUSTOMER")) { session_register("REPEATCUSTOMER"); }
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