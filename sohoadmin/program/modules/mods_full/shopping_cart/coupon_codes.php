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
error_reporting('E_PARSE');
session_start();

require_once('../../../includes/product_gui.php');
//mysql_query("drop table cart_coupons");


if(!table_exists("cart_coupons")){
	create_table("cart_coupons");
}


if($_GET['delete_key']!=''){
	mysql_query("delete from cart_coupons where prikey='".$_GET['delete_key']."'");	
}

if($_POST['coupon_name']!=''){

	$coupon_products = '';
	foreach($_POST['coupon_products'] as $cval){
		if($cval == 'all'){
			$coupon_products = 'all';
		}

		if($coupon_products != 'all'){
			$coupon_products .= $cval.';';
		}
	}
	if($coupon_products != 'all'){
		$coupon_products = preg_replace('/;$/', '', $coupon_products);
	}

	$expdate = $_POST['expiration_year'].'/'.$_POST['expiration_month'].'/'.$_POST['expiration_day'];
	if($_POST['prikey']!=''){
		mysql_query("update cart_coupons set code='".$_POST['coupon_name']."', products='".$coupon_products."', discount_type='".$_POST['disc_type']."', discount_amount='".$_POST['discount_amount']."', expiration='".$expdate."', disc_per='".$_POST['disc_per']."' where prikey='".$_POST['prikey']."'");
	} else {
		mysql_query("insert into cart_coupons (code, products, categories, discount_type, discount_amount, cart_min, expiration, disc_per) values('".$_POST['coupon_name']."', '".$coupon_products."', '', '".$_POST['disc_type']."', '".$_POST['discount_amount']."', '0', '".$expdate."', '".$_POST['disc_per']."')");	
	}

}


if($_GET['create'] == 'code' || $_GET['edit_key']!=''){
	$getfromaddyq = mysql_query("select PAYMENT_CURRENCY_SIGN from cart_options");
	$getfromaddy = mysql_fetch_assoc($getfromaddyq);
	$curr_sign = $getfromaddy['PAYMENT_CURRENCY_SIGN'];
	
	$module_html = "<script language=\"javascript\">\n";
	$module_html .= "function createprod(){\n";
	$module_html .= "	var ffail = '';\n";
	$module_html .= "	if(document.getElementById('coupon_name').value == ''){\n";
	$module_html .= "		alert('Please enter a coupon code.');\n";
	$module_html .= "		ffail = 'yes';\n";
	$module_html .= "	}\n";
	$module_html .= "	if(document.getElementById('discount_amount').value == '' && ffail == ''){\n";
	$module_html .= "		alert('A discount amount is required.');\n";
	$module_html .= "		ffail = 'yes';\n";
	$module_html .= "	}\n";

	$module_html .= "	if(ffail == ''){\n";
	$module_html .= "		var prodchecked = ''; \n";
	$module_html .= "		var chks = document.getElementsByName('coupon_products[]'); \n";
	$module_html .= "		for (var i = 0; i < chks.length; i++){ \n";
	$module_html .= "			if(chks[i].checked==true){ \n";
	$module_html .= "				prodchecked = 'yes'; \n";
	$module_html .= "			} \n";	
	$module_html .= "		} \n";
	$module_html .= "		if(prodchecked != 'yes'){\n";
	$module_html .= "			alert('You must select atleast one product.');\n";
	$module_html .= "			ffail = 'yes';\n";
	$module_html .= "		} \n";
	$module_html .= "	} \n";

	$module_html .= "	if(ffail == '' && document.createcoupon.disc_type[0].checked == true){\n";
	$module_html .= "		if(document.getElementById('discount_amount').value < 1 || document.getElementById('discount_amount').value > 99){ \n";
	$module_html .= "			alert('When using percentage-based discounts you must use a discount amount between 1 and 99%.');\n";
	$module_html .= "			ffail = 'yes';\n";
	$module_html .= "		} \n";
	$module_html .= "	} \n";


	$module_html .= "	if(ffail == ''){\n";
	$module_html .= "		var newdate = document.getElementById('expiration_year').value+document.getElementById('expiration_month').value+document.getElementById('expiration_day').value; \n";
	$module_html .= "		if(newdate <= '".date('Ymd')."'){\n";
	$module_html .= "			alert('You must select an expiration date that occurs after today.');\n";
	$module_html .= "			ffail = 'yes';\n";
	$module_html .= "		}\n";
	$module_html .= "	} \n";


//	$module_html .= "	if(document.getElementById('').value == '' && ffail == ''){\n";
//	$module_html .= "		alert('');\n";
//	$module_html .= "		ffail = 'yes';\n";
//	$module_html .= "	}\n";
	$module_html .= "	if(ffail == ''){\n";
	$module_html .= "		document.createcoupon.submit();\n";
	$module_html .= "	}\n";
	$module_html .= "}\n";
	$module_html .= "</script>\n";

	$module_html .= "<div><p>\n";
	$module_html .= "<form name=\"createcoupon\" method=\"POST\" action=\"coupon_codes.php\">\n";
	
	$module_html .= "<p style=\"margin-bottom:0; font-size:12px;\">\nCoupon Code:</p>\n";
	$module_html .= "<p style=\"margin-top:0;\"><input id=\"coupon_name\" type=text name=\"coupon_name\"></p>\n";

	$module_html .= "<p style=\"margin-bottom:0; font-size:12px;\">\nDiscount Amount:</p>\n";
	$module_html .= "<p style=\"margin-top:0;\">\n";	
	$module_html .= "<strong>%</strong><input style=\"padding:0px; margin:0 3 3 0; vertical-align: bottom;\" name=\"disc_type\" value=\"%\" type=\"radio\">&nbsp;&nbsp;<strong>".$curr_sign."</strong><input style=\"padding:0px; margin:0 3 3 0; vertical-align: bottom;\" name=\"disc_type\" value=\"$\" checked=\"checked\" type=\"radio\">\n";
	$module_html .= "<input id=\"discount_amount\" size=\"6\" type=text name=\"discount_amount\">\n";	
	$module_html .= "</p>\n";
	
	
	
	$module_html .= "<p style=\"margin-bottom:0; margin-top:0; font-size:12px;\">\n";	
	
	$module_html .= "<input style=\"padding:0px; margin:3 3 0 0; vertical-align: bottom;\" name=\"disc_per\" value=\"once\" type=\"radio\"> Apply discount to only one item.<br/>\n";
	if($_GET['edit_key'] == ''){
		$ckt = " checked=\"checked\"";
	} else {
		$ckt = "";
	}
	$module_html .= "<input style=\"padding:0px; margin:3 3 0 0; vertical-align: bottom;\" ".$ckt." name=\"disc_per\" value=\"each\" type=\"radio\"> Apply discount to all eligible products.\n";
	$module_html .= "</p>\n";
	
	$module_html .= "<p style=\"margin-bottom:0; font-size:12px;\">\nDiscount Expiration Date:</p>\n";
	$module_html .= "<p style=\"margin-top:0;\">\n";	
	$module_html .= "<select id=\"expiration_month\" name=\"expiration_month\" class=\"text\" style=\"width: 50px;\">\n";

	$module_html .= "<option value=\"01\">Jan</option>\n";
	$module_html .= "<option value=\"02\">Feb</option>\n";
	$module_html .= "<option value=\"03\">Mar</option>\n";
	$module_html .= "<option value=\"04\">Apr</option>\n";
	$module_html .= "<option value=\"05\">May</option>\n";
	$module_html .= "<option value=\"06\">Jun</option>\n";
	$module_html .= "<option value=\"07\">Jul</option>\n";
	$module_html .= "<option value=\"08\">Aug</option>\n";
	$module_html .= "<option value=\"09\">Sept</option>\n";
	$module_html .= "<option value=\"10\">Oct</option>\n";
	$module_html .= "<option value=\"11\">Nov</option>\n";
	$module_html .= "<option value=\"12\">Dec</option>\n";
	$module_html .= "</select>\n";
	
	$module_html .= "<select id=\"expiration_day\" name=\"expiration_day\" class=\"text\" style=\"width: 50px;\">\n";
	$do = 01;
	while($do < 32){
		if(date('d') == $do){ $SEL = " selected=\"selected\" SELECTED"; } else { $SEL = ''; }
		$module_html .= "<option value=\"".sprintf("%02d", $do)."\"".$SEL.">".sprintf("%02d", $do)."</option>\n";
		++$do;
	}
	$module_html .= "</select>\n";
	
	$module_html .= "<select id=\"expiration_year\" name=\"expiration_year\" class=\"text\" style=\"width: 55px;\">\n";
	$do = 1;	
	$dayear = sprintf("%04d", date('Y'));
	$module_html .= "<option value=\"".$dayear."\">".$dayear."</option>\n";
	while($do < 11){
		$dayear = sprintf("%04d", $dayear+1);
		$module_html .= "<option value=\"".$dayear."\">".$dayear."</option>\n";
		++$do;
	}
	$module_html .= "</select>\n";
	$module_html .= "</p>\n";
   
	$module_html .= "<p style=\"margin-bottom:0; font-size:12px;\">\nProducts To Discount:</p>\n";
	$module_html .= "<p style=\"margin-top:0; height:280px; width:600px; overflow: auto; border: 5px solid rgb(238, 238, 238); font-size: 12px;\">\n";
	$prodq = mysql_query("select PRIKEY, PROD_SKU, PROD_NAME from cart_products order by PROD_NAME asc");
	if($_GET['edit_key']==''){
		$editsel = " checked=\"checked\"";
	} else {
		$editsel = "";
		$gprq1 = mysql_query("select * from cart_coupons where prikey='".$_GET['edit_key']."'");
		$gpr1 = mysql_fetch_assoc($gprq1);
		if(!preg_match('/;/', $gpr1['products'])){
			$pra['0'] = $gpr1['products'];	
		} else {
			$pra = explode(';', $gpr1['products']);	
		}
		if(in_array('all', $pra)){
			$editsel = " checked=\"checked\"";
		} else {
			$editsel = '';
		}
	}
	$xax = 0;
	$module_html .= "<label><input style=\"padding:0px; margin:3 3 0 0; vertical-align: bottom;\" name=\"coupon_products[]\" type=\"checkbox\" value=\"all\" ".$editsel.">[ ALL PRODUCTS ]</label><br/>\n";
	while($prods = mysql_fetch_assoc($prodq)){
		++$xax;
		if($_GET['edit_key']!=''){
			if(in_array($prods['PRIKEY'], $pra)){
				$mchecked = " checked=\"checked\"";
			} else {
				$mchecked = '';
			}
		} else {
			$mchecked = '';
		}
		$module_html .= "<label><input style=\"padding:0px; margin:3 3 0 0; vertical-align: bottom;\" name=\"coupon_products[]\" type=\"checkbox\" value=\"".$prods['PRIKEY']."\" ".$mchecked.">".$prods['PROD_NAME']."</label><br/>\n";
	}
	$module_html .= "</p>\n";

	$module_html .= "<script language=\"javascript\">\n";
	if($_GET['edit_key']!=''){
		$gprq = mysql_query("select * from cart_coupons where prikey='".$_GET['edit_key']."'");
		$gpr = mysql_fetch_assoc($gprq);
		$module_html .= "	document.getElementById('coupon_name').value = '".$gpr['code']."';\n";
		$module_html .= "	document.getElementById('discount_amount').value = '".$gpr['discount_amount']."';\n";
		//$module_html .= "	document.getElementById('coupon_products').value = '".$gpr['products']."';\n";


		
		if($gpr['expiration']!=0){
			$expar = explode('/', $gpr['expiration']);
			$module_html .= "	document.getElementById('expiration_year').value = '".$expar['0']."';\n";
			$module_html .= "	document.getElementById('expiration_month').value = '".$expar['1']."';\n";
			$module_html .= "	document.getElementById('expiration_day').value = '".$expar['2']."';\n";
		}
		
		if($gpr['discount_type']=='%'){
			$module_html .= "	document.createcoupon.disc_type[0].checked = true;\n";	
		}

		if($gpr['disc_per']=='once'){
			$module_html .= "	document.createcoupon.disc_per[0].checked = true;\n";	
		} else {
			$module_html .= "	document.createcoupon.disc_per[1].checked = true;\n";	
		}
		//$module_html .= "	document.getElementById('').value = '".$gpr['']."';\n";	
	} else {
		$expar = explode('/', date('Y/m/d', strtotime('+24 hours')));
		$module_html .= "	document.getElementById('expiration_year').value = '".$expar['0']."';\n";
		$module_html .= "	document.getElementById('expiration_month').value = '".$expar['1']."';\n";
		$module_html .= "	document.getElementById('expiration_day').value = '".$expar['2']."';\n";
	}
	$module_html .= "</script>\n";
	if($_GET['edit_key']!=''){
		$module_html .= "<input type=\"hidden\" name=\"prikey\" value=\"".$_GET['edit_key']."\">\n";	
	}

	$module_html .= "<div style=\"margin-top: 20px; text-align: left;\"><button onClick=\"document.location.href='coupon_codes.php';\" class=\"redButton\" type=\"button\"><span><span>[x] CANCEL</span></span></button><button onclick=\"createprod();\" class=\"greenButton\" type=\"button\"><span><span>".lang("SAVE PRODUCT")." &gt;&gt;</span></span></button></div>\n";
	$module_html .= "</form>\n";
	$module_html .= "</p></div>\n";

	
} else {
	
	$module_html = "<div style=\"font-size:13px;\"><p>\n";
	$module_html .= "[<a href=\"coupon_codes.php?create=code\" class=\"sav\"><strong>Create a coupon code</strong></a>]\n";
	$module_html .= "</p>\n";
	
	$ccq = mysql_query("select prikey, code from cart_coupons order by code asc");
	while($ccs = mysql_fetch_assoc($ccq)){
		$module_html .= "<p>\n";
		
		$module_html .= "[<a class=\"del\" href=\"coupon_codes.php?delete_key=".$ccs['prikey']."\">Delete</a>]\n";
		$module_html .= "&nbsp;&nbsp;[<a class=\"sav\" href=\"coupon_codes.php?edit_key=".$ccs['prikey']."\">Edit</a>]\n";
		
		$module_html .= "&nbsp;&nbsp;".$ccs['code']."\n";
		$module_html .= "</p>\n";
	}
	$module_html .= "</div>\n";
}

$module = new smt_module($module_html);
$module->add_breadcrumb_link("Shopping Cart Menu", "program/modules/mods_full/shopping_cart.php");
$module->add_breadcrumb_link("Coupon Codes", "program/modules/mods_full/shopping_cart/coupon_codes.php");
$module->icon_img = "program/includes/images/shopping-icon-large.png";
$module->heading_text = lang("Coupon Codes");
$module->description_text = "Create coupon/promotional codes for certain products.";
$module->good_to_go();

?>
