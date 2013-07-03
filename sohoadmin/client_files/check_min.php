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
## Copyright 1999-2003 Soholaunch.com, Inc.  All Rights Reserved.
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

$skuno_array = explode(';', $_SESSION['CART_SKUNO']);
$qty_array = explode(';', $_SESSION['CART_QTY']);
$unitsub_array = explode(';',$_SESSION['CART_UNITSUBTOTAL']);
array_pop($skuno_array);
array_pop($qty_array);
array_pop($unitsub_array);
$thecount = count($skuno_array);

if($thecount > 0) {
	$xox = 0;
	
	while($xox < $thecount) {
		$skutotalcount = '';
		$indcount = 0;	
		$ZOOM = array_keys($skuno_array, $skuno_array[$xox]);
		$zc = 1;
		$firstoccurnce = $ZOOM['0'];
		$zcount = count($ZOOM);
		$xa = 0;
		while($xa < $zcount) {
			$arkey = $ZOOM[$xa];
			$skutotalcount = $skutotalcount + $qty_array[$arkey];
			$xa++;
		}

		//while($zc < $zcount){
			//$arkey = $ZOOM[$zc];
			//$skutotalcount = $skutotalcount + $qty_array[$arkey];
			
			/////
				$qty_disc_qry=mysql_query('select required_qty from qty_discounts where sku=\''.$skuno_array[$xox].'\'');
				$crtdsc = mysql_fetch_assoc($qty_disc_qry);
				$skutotalcount." ".$crtdsc['required_qty']."<br/>";
//					$zc++;
//		}
				$skuno_array[$xox];
				if($skutotalcount < $crtdsc['required_qty']) {
					$prodname = mysql_query('select PRIKEY, PROD_NAME from cart_products where PROD_SKU=\''.$skuno_array[$xox].'\'');
					$prodres=mysql_fetch_assoc($prodname);
					$productname = $prodres['PROD_NAME'];

					//$URLredirect = "pgm-add_cart.php?action=view&id=".$prodres['PRIKEY'];
					$QTYAR = '';
					foreach($qty_array as $t5=>$t6) {
						if($t6!='') {
							$QTYAR .= "QTYUPDATE".$t5."=".$t6."&";
						}
					}
					$URLredirect = "pgm-add_cart.php?id=1&goto_checkout=no&price=blue;100&qty=SID";
					$URLredirect = "pgm-add_cart.php?ACTION=update&".$QTYAR."=SID";
					$minmsg = "The product, ".$productname.", is only sold in quantities of ".$crtdsc['required_qty']." or more.";
					echo "<script language=\"javascript\">\n ";
					echo "alert('".$minmsg."'); \n";
					
					echo "document.location.href='".$URLredirect."'; \n";
					echo "</script> \n";
				}


		$xox++;
	}
}


?>