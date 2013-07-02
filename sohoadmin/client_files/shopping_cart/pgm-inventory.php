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

// Each Sku has an inventory number associated with it, let's subtract the
// qty of each sku ordered and set the display option to "off" if we reach
// zero.  By default, each product has an inventory number of  50,000 and
// can be set to any number by the end-user.

$tmp_keyid = split(";", $CART_KEYID);
$tmp_qty = split(";", $CART_QTY);
$tmp_skuno = split(";", $CART_SKUNO);
$tmp_catno = split(";", $CART_CATNO);

$tmp_name = split(";", $CART_PRODNAME);
$tmp_subcat = split(";", $CART_SUBCAT);
$tmp_varname = split(";", $CART_VARNAME);

$tmp_price = split(";", $CART_UNITPRICE);
$tmp_sub = split(";", $CART_UNITSUBTOTAL);

$line_items = count($tmp_qty);	// Count the number of array vars we have after split
$line_items = $line_items - 2;	// Subtract 2 because (a)we start count at 0 (b) we always have a trailing semi-colon;


for ($z=0;$z<=$line_items;$z++) {

	$result = mysql_query("SELECT OPTION_INVENTORY_NUM, OPTION_DISPLAY  FROM cart_products WHERE PRIKEY = '$tmp_keyid[$z]'");
	$inv = mysql_fetch_array($result);

	if ($inv[OPTION_INVENTORY_NUM] != "") {
			$new_inv_num = $inv[OPTION_INVENTORY_NUM] - $tmp_qty[$z];
			$new_display_setting = $inv[OPTION_DISPLAY];
	}

// Un-comment this to make products auto-set to "do not display" once their inventory runs out
// Removed by customer request for v4.9 r33...seems like it'd more desireable for everyone this way --- better to show as "out of stock" than not show it at all
// May have to add this back as a configureable option if enough people make the case for it
//	if ($new_inv_num <= 0) {
//			$new_display_setting = "N";
//	}

	mysql_query("UPDATE cart_products SET OPTION_INVENTORY_NUM = '$new_inv_num', OPTION_DISPLAY = '$new_display_setting' WHERE PRIKEY = '$tmp_keyid[$z]'");

}

?>