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
require_once("../../../includes/product_gui.php");
# Make sure session is restored (Mantis #4)
//if ( strlen(lang("Order Date")) < 4 ) {
//   include("includes/config-global.php"); // Re-registers all global & session info
//}

//include("../includes/login.php");
//include("../includes/db_connect.php");

$CSVFILE = "";

########################################################
### CONNECT TO APPROPRIATE TABLE AND GET ALL DATA    ###
########################################################

	# Show incomplete orders?
	$QUERY = str_replace("\'", "'", $_POST['QUERY']);

	$result = mysql_query($QUERY);
	$N_FIELDS = mysql_num_fields($result) - 1;

	// ----------------------------------------------
	// Insert Field Names as First Line of CSV data
	// ----------------------------------------------
		
	for ($x=0;$x<=$N_FIELDS;$x++) {
		if ($x != $N_FIELDS) {
			$CSVFILE .= mysql_field_name($result, $x);
			$CSVFILE .= ",";
		} else {
			$CSVFILE .= mysql_field_name($result, $x);
			$CSVFILE .= "\n";
		}
	} // End For Loop

	// ----------------------------------------------
	// Place each record into CSV file variable
	// ----------------------------------------------

	while ($row = mysql_fetch_array ($result)) {

		$record_date = split("/", $row['ORDER_DATE']);
		$int_record_date = $record_date[2].$record_date[0].$record_date[1];

		if ( ($row['TRANSACTION_STATUS'] != "Purged") ){

			if ( $_POST['int_start'] <= $int_record_date && $_POST['int_end'] >= $int_record_date) {
//		 echo $_POST['int_start']." ".$int_record_date."<br>";
				for ($x=0;$x<=$N_FIELDS;$x++) {
					$THIS_FIELD_NAME = mysql_field_name($result, $x);
					$THIS_DATA = $row[$THIS_FIELD_NAME];


					//$THIS_DATA = eregi_replace(";", ",", $THIS_DATA);
					$THIS_DATA = str_replace("\n", "", $THIS_DATA);	// Kill Internal CR from Unix or Windows
					$THIS_DATA = str_replace("\r", ",", $THIS_DATA);
					$THIS_DATA = str_replace('"', '\"', $THIS_DATA);

					if(preg_match('/[,]/i', $THIS_DATA)){
						$CSVFILE .= "\"".$THIS_DATA."\"";
					} else {
						$CSVFILE .= $THIS_DATA;
					}							
					
					
					if ($x != $N_FIELDS) {
						$CSVFILE .= ",";
					} else {
						$CSVFILE .= "\n";
					}
					
				} // End For Loop
			}
		}
		
	} // End While Loop

	// ----------------------------------------------
	// Clean up char that will cause spreadsheets to
	// "Over-React" and format the import
	// ----------------------------------------------	
	
	// ----------------------------------------------
	// Force Feed the Download Action Now
	// ----------------------------------------------
	
	$today = date("Y-m-d");
	$local_name = "CART_INVOICE_DATA($today).csv";
	$local_name = $local_name;
	header("Cache-Control: maxage=1"); //In seconds
	header("Pragma: public");
	header("Content-Type: application/x-octet-stream"); 
	header( "Content-Disposition: attachment; filename=\"$local_name\""); 
	echo $CSVFILE; 
	
?>