<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
///////////working

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

//echo testArray($_SESSION['unitsub_array']);
//echo testArray($_SESSION['discount_ammounts']);

unset($_SESSION['discount_ammounts']);
unset($_SESSION['unitsub_array']);

//include_once("../../../includes/product_gui.php");
$skuno_array = explode(';', $_SESSION['CART_SKUNO']);
$qty_array = explode(';', $_SESSION['CART_QTY']);
$unitsub_array = explode(';',$_SESSION['CART_UNITSUBTOTAL']);

array_pop($skuno_array);
array_pop($unitsub_array );
array_pop($qty_array);


$thecount = count($skuno_array);

if($thecount > 0) {
	$xox = 0;
	while($xox < $thecount) {
		//echo testArray($skuno_array);
		//echo $skuno_array[$xox];
		
		$indcount = 0;	
		$ZOOM = array_keys($skuno_array, $skuno_array[$xox]);
		$totcount = 0;


		foreach($ZOOM as $Z1=>$Z2){
			if(isset($qty_array[$Z2])){
			//	echo $Z1."  ".$qty_array[$Z1]."<br/>";
				$totcount = $qty_array[$Z2] + $totcount;		
			}
		}
		

		$zc = 0;
		$firstoccurnce = $ZOOM['0'];
		$zcount = count($ZOOM);
//echo "count:".$zcount;
//echo $totcount."<br/><br/>".$zcount."<br/>";
		$skutotalcount = '';
		while($zc < $zcount){
			$arkey = $ZOOM[$zc];
			$skutotalcount = $skutotalcount + $qty_array[$xox];
			//echo $skutotalcount."<br/><br/>";
			//echo "<br/>";
			/////
				$qty_disc_qry=mysql_query('select * from qty_discounts where sku=\''.$skuno_array[$xox].'\'');
				$crtdsc = mysql_fetch_array($qty_disc_qry);
					//echo testArray($crtdsc);
				$crtnumy = mysql_num_rows($qty_disc_qry);
				if($crtnumy == 0){
					$min_qty = '1';
					$max_qty = '99999999';
					$disc = '0';
					$indcount = '1';
				} else {
					$min_qty = unserialize($crtdsc['min_qty']);
					$max_qty = unserialize($crtdsc['max_qty']);
					$disc = unserialize($crtdsc['disc']);
					$indcount = count($min_qty);
				}
				$xoxx = 0;
				//echo $qty_array[$xox];

				while($xoxx <= $indcount) {
					//echo "<br/><br/><br/>".$totcount." ".$crtdsc['required_qty']."<br/>";
					if($totcount>= $crtdsc['required_qty'] && $disc > 0) {
						if($totcount >= $min_qty[$xoxx] && $totcount <= $max_qty[$xoxx]) {
							if($crtdsc['disc_type']=='%') {
								$thediscount = $disc[$xoxx] * .01;
								$thediscount = $unitsub_array[$xox] * $thediscount;
								
								if($thediscount > 0) {
									$thediscount = -($thediscount);
								}
	
								$unitsub_array[$xox] = $thediscount;
								$discount_ammounts[$xox] = $disc[$xoxx].'%';
							} else {
								$thediscount = $disc[$xoxx];
	
	
								$thediscount = $thediscount * $skutotalcount;
								$unitsub_array[$xox] = -($thediscount);
								
								$discount_ammounts[$xox] = '';
								//$thediscount = $thediscount;
							}
							//echo $thediscount;
							//$unitsub_array[$xoxx];
							//echo $unitsub_array[$xox];
							//$qty_array[$xox];
							//$unitsub_array
							 
							//$_SESSION['SHIPPING_TOTAL'] = 
						}					

					} else {	
						$thediscount = 0;
						$unitsub_array[$xox] = 0;								
						$discount_ammounts[$xox] = 0;
					}
					$zc++;
					$xoxx++;
				}
			/////
			
			 
//			$unitsub_array[$firstoccurnce] = $unitsub_array[$firstoccurnce] + $unitsub_array[$arkey];
//			unset($skuno_array[$arkey]);
//			unset($qty_array[$arkey]);
//			unset($unitsub_array[$arkey]);

			//$zc++;
		}
		$xox++;
	}
}
$_SESSION['unitsub_array'] = $unitsub_array;
$_SESSION['discount_ammounts'] = $discount_ammounts;

?>