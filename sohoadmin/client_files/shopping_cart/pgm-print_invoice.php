<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }



###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.5
##
## Author: 			Joe Lain [joe.lain@soholaunch.com]
## Homepage:	 	http://info.soholaunch.com
## Bug Reports: 	http://bugz.soholaunch.com
###############################################################################

##############################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2003 Soholaunch.com, Inc. and Mike Johnston
## Copyright 2003-2008 Soholaunch.com, Inc.
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

# Include config script
include("pgm-cart_config.php");


###############################################################################
### Script for printing a clean invoice without all the template junk
### Bug #706 [http://bugz.soholaunch.com/view.php?id=706]
###############################################################################
if(!preg_match('/^(\d)+$/', $_REQUEST['invoice_num'])){
	exit;	
}

$invoice_num = $_REQUEST['invoice_num'];
//$invoice_num = "10008";


$result = mysql_query("SELECT * FROM cart_invoice WHERE ORDER_NUMBER = '$invoice_num'");
$INVOICE = mysql_fetch_array($result);

$result = mysql_query("SELECT * FROM cart_options");
$OPTIONS = mysql_fetch_array($result);


//foreach($INVOICE as $var=>$val){
//   echo "var = (".$var.") val = (".$val.")<br>\n";
//}
echo "<html>\n";
echo "<head>\n";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../runtime.css\">\n";
echo "</head>\n";
echo "<body>\n";

echo "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\" class=\"text\">\n";
echo "<tr><td align=\"left\" valign=\"top\" class=\"text\" width=\"50%\">\n";

	echo $OPTIONS['BIZ_PAYABLE']."<BR>\n";
	echo $OPTIONS['BIZ_ADDRESS_1']."<BR>\n";
		if ($OPTIONS['BIZ_ADDRESS_2'] != "") {
			echo $OPTIONS['BIZ_ADDRESS_2']."<BR>";
		}
	echo $OPTIONS['BIZ_CITY'].", ".$OPTIONS['BIZ_STATE']."  ".$OPTIONS['BIZ_POSTALCODE']."<BR>\n";
	echo $OPTIONS['BIZ_PHONE']."</font>\n";

echo "</td><td align=\"left\" valign=\"top\" class=\"text\" width=\"50%\">\n";

echo "<BR><B>".$lang["Order Date"]."</B>: <font size=\"3\"><tt>".$INVOICE['ORDER_DATE']." &nbsp;&nbsp;".$INVOICE['ORDER_TIME']."</tt></font>\n";
echo "<BR><B>".$lang["Order Number"]."</B>: <font size=\"3\"><tt>".$INVOICE['ORDER_NUMBER']."</tt></font> &nbsp;";

echo "</td>\n";
echo "</tr></table>\n\n";

echo $INVOICE['INVOICE_HTML'];

echo "<script language=\"javascript\">\n";
echo "setTimeout(\"window.print();\", 1000);\n";
echo "</script>\n";

echo "</body>\n";
echo "</html>\n";

?>