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

error_reporting(E_PARSE);
session_start();
include_once("../../includes/product_gui.php");


#######################################################
### READ CURRENT CATEGORIES INTO MEMORY		       ###
#######################################################
		$match = 0;
		$tablename = "cart_category";

		$result = mysql_list_tables("$db_name");
		$i = 0;
		while ($i < mysql_num_rows ($result)) {
			$tb_names[$i] = mysql_tablename ($result, $i);
			if ($tb_names[$i] == $tablename) {
				$match = 1;
			}
			$i++;
		}

		if ($match == 1) {
			$catFlag = 1;
			$result = mysql_query("SELECT category FROM cart_category");
			$numberRows = mysql_num_rows($result);
			$a=0;
			$catcount=0;
			while ($row = mysql_fetch_array ($result)) {
				$a++;
				$dbcats[$a] = $row["category"];
				if (strlen($dbcats[$a]) > 2) {
					$catcount++;
				}
			}
		} else {
			$catFlag = 0;
			$catcount = 0;
		}


#######################################################
### Locate Number of Sku's In database		    ###
#######################################################

		$match = 0;
		$tablename = "cart_products";

		$result = mysql_list_tables("$db_name");
		$i = 0;
		while ($i < mysql_num_rows ($result)) {
			$tb_names[$i] = mysql_tablename ($result, $i);
			if ($tb_names[$i] == $tablename) {
				$match = 1;
			}
			$i++;
		}

		if ($match == 1) {
			$result = mysql_query("SELECT PRIKEY FROM cart_products");
			$numProducts = mysql_num_rows($result);
		} else {
			$numProducts = 0;
		}


#######################################################
### IF THE 'cart_options' TABLE DOES NOT EXIST; CREATE
### NOW SO THAT THE OPTIONS CAN BE SAVE WITHOUT THIS
### ROUTINE IN EVERY SINGLE MODULES
#######################################################

		$match = 0;
		$tablename = "cart_options";

		$result = mysql_list_tables("$db_name");
		$i = 0;
		while ($i < mysql_num_rows ($result)) {
			$tb_names[$i] = mysql_tablename ($result, $i);
			if ($tb_names[$i] == $tablename) {
				$match = 1;
			}
			$i++;
		}

		// if ($match == 1) { mysql_query("DROP TABLE cart_options"); }

		if ($match != 1) {
         # Create cart_options table w/default data inserted now
         include_once("shopping_cart/includes/cart_options.dbtable.php");
		}


#######################################################
/// v4.7 (Beta 1)
### IF THE 'cart_options' TABLE DOES EXIST, BUT
### DOES NOT CONTAIN NEW INTERNATIONAL FIELDS, CREATE
### THEM NOW SO THAT THE OPTIONS CAN BE SAVED WITHOUT
### THIS ROUTINE IN EVERY SINGLE MODULE
### ---------------------------------------------------
$selTbl = mysql_query("SELECT * FROM cart_options");
$fetch = mysql_fetch_array($selTbl);

if ($fetch['BIZ_COUNTRY'] == "") {
   //No Intl opts found, so add columns and insert defaults
   mysql_query("ALTER TABLE cart_options ADD COLUMN PAYMENT_CURRENCY_TYPE VARCHAR(5)");
   mysql_query("UPDATE cart_options SET PAYMENT_CURRENCY_TYPE='USD'");
   mysql_query("ALTER TABLE cart_options ADD COLUMN PAYMENT_CURRENCY_SIGN CHAR(12)");
   mysql_query("UPDATE cart_options SET PAYMENT_CURRENCY_SIGN='\$'");
   mysql_query("ALTER TABLE cart_options ADD COLUMN LOCAL_COUNTRY BLOB)");
   mysql_query("UPDATE cart_options SET LOCAL_COUNTRY='UNITED STATES - US:n:'");
   mysql_query("ALTER TABLE cart_options ADD COLUMN DISPLAY_STATE VARCHAR(255)");
   mysql_query("UPDATE cart_options SET DISPLAY_STATE='usmenu'");
   mysql_query("ALTER TABLE cart_options ADD COLUMN BIZ_COUNTRY VARCHAR(255)");
   mysql_query("UPDATE cart_options SET BIZ_COUNTRY='United States'");
   mysql_query("ALTER TABLE cart_options ADD COLUMN CHARGE_VAT VARCHAR(5)");
   mysql_query("UPDATE cart_options SET CHARGE_VAT='no'");
   mysql_query("ALTER TABLE cart_options ADD COLUMN VAT_REG VARCHAR(255)");
   mysql_query("UPDATE cart_options SET VAT_REG='vatnum'");
}


#######################################################
/// v4.7 (Beta 2.7)
### Increase char limit on payment_processing_type
### so that it can contain any combination of methods
### ---------------------------------------------------
if ( mysql_fieldlen( $selTbl, 3 ) < 8 ) {
   $increase_length = "ALTER TABLE cart_options MODIFY PAYMENT_PROCESSING_TYPE VARCHAR(255)";
   mysql_query("$increase_length") || die("Could not increase length of PAYMENT_PROCESSING_TYPE field in cart_options table!!");
}

#######################################################
/// v4.8 (Build 01)
### Check for and add field for Zip Code
### so that it can contain any combination of methods
### ---------------------------------------------------
if ( !mysql_query("select DISPLAY_ZIP from cart_options") ) {
   mysql_query("ALTER TABLE cart_options ADD COLUMN DISPLAY_ZIP VARCHAR(150)");
   mysql_query("UPDATE cart_options SET DISPLAY_ZIP='zippostal'");
   mysql_query("ALTER TABLE cart_options ADD COLUMN DISPLAY_REQUIRED BLOB");
   mysql_query("UPDATE cart_options SET DISPLAY_REQUIRED='zippostal'");
}


//echo "Field[38] = '".mysql_field_name($selTbl, 38)."'<br>\n";
//echo "Field[39] = '".mysql_field_name($selTbl, 39)."'<br>\n";
//echo "Field[41] = '".mysql_field_name($selTbl, 41)."'<br>\n";
//echo "Field[42] = '".mysql_field_name($selTbl, 42)."'<br>\n";
//echo "Field[43] = '".mysql_field_name($selTbl, 43)."'<br>\n";

#######################################################
### IF THE 'cart_products' TABLE DOES NOT EXIST; CREATE
### NOW SO THAT ADD/EDIT PRODUCTS CAN SAVE WITHOUT THIS
### ROUTINE IN EVERY SINGLE MODULE
#######################################################

		$match = 0;
		$tablename = "cart_products";

		$result = mysql_list_tables("$db_name");
		$i = 0;
		while ($i < mysql_num_rows ($result)) {
			$tb_names[$i] = mysql_tablename ($result, $i);
			if ($tb_names[$i] == $tablename) {
				$match = 1;
			}
			$i++;
		}

		// if ($match == 1) { mysql_query("DROP TABLE cart_products"); }

		if ($match != 1) {

			mysql_db_query("$db_name","CREATE TABLE cart_products (

				PRIKEY INT NOT NULL AUTO_INCREMENT PRIMARY KEY,

				PROD_CATEGORY1 INT(5),
				PROD_CATEGORY2 INT(5),
				PROD_CATEGORY3 INT(5),
				PROD_SKU CHAR(150),
				PROD_CATNO CHAR(150),
				PROD_UNITPRICE CHAR(10),
				PROD_NAME CHAR(255),
				PROD_DESC BLOB,
				PROD_SHIPA CHAR(50),
				PROD_SHIPB CHAR(50),
				PROD_SHIPC CHAR(50),
				PROD_THUMBNAIL CHAR(75),
				PROD_FULLIMAGENAME CHAR(75),

				SUB_CAT1 CHAR(150),
				SUB_CAT2 CHAR(150),
				SUB_CAT3 CHAR(150),
				SUB_CAT4 CHAR(150),
				SUB_CAT5 CHAR(150),
				SUB_CAT6 CHAR(150),

				VARIANT_NAME1 CHAR(150),
				VARIANT_PRICE1 CHAR(10),

				VARIANT_NAME2 CHAR(150),
				VARIANT_PRICE2 CHAR(10),

				VARIANT_NAME3 CHAR(150),
				VARIANT_PRICE3 CHAR(10),

				VARIANT_NAME4 CHAR(150),
				VARIANT_PRICE4 CHAR(10),

				VARIANT_NAME5 CHAR(150),
				VARIANT_PRICE5 CHAR(10),

				VARIANT_NAME6 CHAR(150),
				VARIANT_PRICE6 CHAR(10),

				OPTION_DISPLAY CHAR(1),
				OPTION_CHARGETAX CHAR(1),
				OPTION_CHARGESHIPPING CHAR(1),
				OPTION_KEYWORDS CHAR(255),
				OPTION_SECURITYCODE CHAR(50),
				OPTION_DETAILPAGE CHAR(255),
				OPTION_RECOMMENDSKU CHAR(255),
				OPTION_SHOWATEDIT CHAR(1),
				OPTION_FORMDATA CHAR(75),
				OPTION_FORMDISPLAY CHAR(6),
				OPTION_DOWNLOADFILE CHAR(75),
				OPTION_INVENTORY_NUM INT(75),

				SPECIAL_TAX VARCHAR(15),

				sub_cats BLOB,
				variant_names BLOB,
				variant_prices BLOB,
				num_variants VARCHAR(20),

				full_desc BLOB,
				other_images BLOB

				)");

		} // 51 Fields as of other_images

//	Build not ready for this yet I don't think
// Better fix: Insert defaults, or else you have to save Display Settings once
//		# Make sure there's at least one row in the table (else all the updates everywhere break)
//		$qryStr = 'select * from cart_options';
//		$rez = mysql_query($qryStr);
//		if ( mysql_num_rows($rez) < 1 ) {
//   		$data = array(); // empty on purpose
//   		$qry = new mysql_insert("cart_options", $data);
//   		$qry->insert();
//   	}

#######################################################
/// v4.7 (RC 1)
### Check for SPECIAL_TAX field, which was added
### to prevent vat features from conflicting with
### custom shipping options utilizing PROD_SHIPC
### ---------------------------------------------------

if ( !mysql_query("SELECT SPECIAL_TAX FROM cart_products") ) {
   mysql_query("ALTER TABLE cart_products ADD COLUMN SPECIAL_TAX VARCHAR(15)");
   mysql_query("UPDATE cart_products SET SPECIAL_TAX=' '");
}




# full_desc - Add field to cart_products table (v4.9 r45)
if ( table_exists("cart_products")) {
	$other_imgs = 0;
	$desc_check = 0;
	$rez = mysql_query("show columns FROM cart_products");
	while($cols = mysql_fetch_array($rez)){
		if($cols['Field'] == 'full_desc'){
			$desc_check = 1;
		}

		if($cols['Field'] == 'FULL_DESC'){
			$qry = "ALTER TABLE cart_products CHANGE FULL_DESC full_desc BLOB";
	   	mysql_query($qry);
			$report[] = "full_desc field fixed in cart_products table";
			$desc_check = 1;
		}

		if($cols['Field'] == 'other_images'){
			$other_imgs = 1;
		}

		if($cols['Field'] == 'OTHER_IMAGES'){
			$qry = "ALTER TABLE cart_products CHANGE OTHER_IMAGES other_images BLOB";
	   	mysql_query($qry);
			$report[] = "other_images field fixed in cart_products table";
			$other_imgs = 1;
		}


	}

	if($desc_check==0){
		$qry = "ALTER TABLE cart_products ADD COLUMN full_desc BLOB";
   	mysql_query($qry);
		$report[] = "full_desc field added to cart_products table";
   }

	if($other_imgs==0){
		$qry = "ALTER TABLE cart_products ADD COLUMN other_images BLOB";
   	mysql_query($qry);
		$report[] = "other_images field added to cart_products table";
   }
}


#######################################################
### START HTML/JAVASCRIPT CODE
#######################################################
ob_start();

?>
<script language="JavaScript">
<!--
show_hide_layer('NEWSLETTER_LAYER?header','','hide');
show_hide_layer('MAIN_MENU_LAYER?header','','hide');
show_hide_layer('CART_MENU_LAYER?header','','show');
show_hide_layer('DATABASE_LAYER?header','','hide');
show_hide_layer('WEBMASTER_MENU_LAYER?header','','hide');


<?

echo ("

	function navto(a) {
		window.location = a+\"?=SID\";
	}

\n");

?>

//-->
</script>

<style type="text/css">
#shopping_cart_menu th,
#shopping_cart_menu td { 
	padding: 10px 10px 10px 15px;
	border-left: 1px dotted #ccc;
}

#shopping_cart_menu td.first-child,
#shopping_cart_menu th.first-child { border-left: 0px none !important; }

#shopping_cart_menu th {
	text-align: left;
	vertical-align: bottom;
	font-size: 125%;
}
	
#shopping_cart_menu td { 
	vertical-align: top; 
	text-align: left;
}
#view-invoices-container {
	/*background-color: #ccc;*/
	text-align: center !important;
	border-left: none;
}

</style>

<?

$THIS_DISPLAY = "";

// -------------------------------------------------------------------------------
// If there have been no categories created, let's force the user to create some
// categories.  We must have the categories createed before we can enter any
// product information into the database.
// -------------------------------------------------------------------------------

if ($catcount == 0 && !eregi(";INVOICES_YES;", $CUR_USER_ACCESS)) {

//	$THIS_DISPLAY .= "\n\n<TABLE BORDER=0 CELLPADDING=4 CELLSPACING=0 WIDTH=75% CLASS=allBorder><TR>\n";
//	$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=TOP BGCOLOR=RED><font color=white face=Verdana size=2><B>Notice:</TD></TR><TR><TD ALIGN=CENTER VALIGN=TOP><BR>\n";
//	$THIS_DISPLAY .= "<B>No categories have been created to classify products within your shopping cart.</B><BR>\n";
//	$THIS_DISPLAY .= "You must first create categories before any other options can be set for your cart.<BR><BR>\n";
//	$THIS_DISPLAY .= "\n<FORM NAME=CAT METHOD=POST ACTION='shopping_cart/categories.php'>\n";
//	$THIS_DISPLAY .= "<INPUT TYPE=SUBMIT ".$onBtns." VALUE=\"Create Categories\">\n</FORM>\n\n";
//	$THIS_DISPLAY .= "</TD></TR></TABLE><BR><BR>\n\n";

}

// -------------------------------------------------------------------------------
// Since this is the "intro" to all of the shopping cart system, we will present
// an "easy to follow" menu system that mimics the main menu.  Only do this
// however, if categories have been created.
// -------------------------------------------------------------------------------

$THIS_DISPLAY .= "<table border=\"0\" cellspacing=\"0\" id=\"shopping_cart_menu\">\n";

if ( !eregi(";INVOICES_YES;", $CUR_USER_ACCESS) ) {

   # Hey, you should go do this stuff first...
   if ( $catcount < 1 ) {
      $THIS_DISPLAY .= "   <div class=\"bg_yellow\" style=\"padding: 5px;\">\n";
      $THIS_DISPLAY .= "    <p><b class=\"red\">First thing to do:</b> Go create one or more <a href=\"shopping_cart/categories.php\">product categories</a></p>\n";
      $THIS_DISPLAY .= "    <p>No categories have been created to classify products within your shopping cart.\n";
      $THIS_DISPLAY .= "    You must first create categories before you can add products (because products must be associated with at least one category.</p>\n";
      $THIS_DISPLAY .= "   </div>\n";
   }

   $THIS_DISPLAY .= "<p>".lang("You currently have")." ($numProducts) ".lang("products in")." ($catcount) ".lang("categories")."</p>\n";

	# Pre-build Mouseover script for new v4.7 buttons
	$onBtns = "class=\"btn_edit\" onMouseover=\"this.className='btn_editon';\" onMouseout=\"this.className='btn_edit';\"";

	# col titles
	$THIS_DISPLAY .= "	<tr>\n";
	$THIS_DISPLAY .= "		<th class=\"first-child\">Products</th>\n";
	$THIS_DISPLAY .= "		<th>Checkout Process</th>\n";
	$THIS_DISPLAY .= "		<th>Miscellanous</th>\n";
	$THIS_DISPLAY .= "	</tr>\n";

	$THIS_DISPLAY .= "	<tr>\n";
	$THIS_DISPLAY .= "		<td class=\"first-child\"><button type=\"button\" class=\"greenButton\" onclick=\"document.location.href = 'shopping_cart/products.php';\"><span><span>Add New Products</span></span></button></td>\n";
	$THIS_DISPLAY .= "		<td><button type=\"button\" class=\"blueButton\" onclick=\"navto('shopping_cart/display_settings.php');\" style='font-weight: bold;'><span><span>".lang("Display Settings")."</span></span></button></td>\n";
	$THIS_DISPLAY .= "		<td><button type=\"button\" class=\"blueButton\" onclick=\"navto('shopping_cart/business_information.php');\"><span><span>".lang("Business Information")."</span></span></button></td>\n";
	$THIS_DISPLAY .= "	</tr>\n";
	
	$THIS_DISPLAY .= "	<tr>\n";
	$THIS_DISPLAY .= "		<td class=\"first-child\"><button type=\"button\" class=\"blueButton\" onclick=\"navto('shopping_cart/search_products.php');\"><span><span>".lang("Find/Edit Current Products")."</span></span></button></td>\n";
	$THIS_DISPLAY .= "		<td><button type=\"button\" class=\"blueButton\" onclick=\"navto('shopping_cart/payment_options.php');\"><span><span>".lang("Payment Options")."</span></span></button></td>\n";
	$THIS_DISPLAY .= "		<td><button type=\"button\" class=\"grayButton\" onclick=\"navto('shopping_cart/privacy_policy.php');\"><span><span>".lang("Privacy Policy")."</span></span></button></td>\n";
	$THIS_DISPLAY .= "	</tr>\n";

	$THIS_DISPLAY .= "	<tr>\n";
	$THIS_DISPLAY .= "		<td class=\"first-child\"><button type=\"button\" class=\"blueButton\" onclick=\"navto('shopping_cart/categories.php');\"><span><span>".lang("Product Categories")."</span></span></button></td>\n";
	$THIS_DISPLAY .= "		<td><button type=\"button\" class=\"blueButton\" onclick=\"navto('shopping_cart/shipping_options.php');\"><span><span>".lang("Shipping Options")."</span></span></button></td>\n";
	$THIS_DISPLAY .= "		<td><button type=\"button\" class=\"grayButton\" onclick=\"navto('shopping_cart/shipping_policy.php');\"><span><span>".lang("Shipping Policy")."</span></span></button></td>\n";
	$THIS_DISPLAY .= "	</tr>\n";
	
	$THIS_DISPLAY .= "	<tr>\n";
	$THIS_DISPLAY .= "		<td class=\"first-child\">&nbsp;</td>\n";
	$THIS_DISPLAY .= "		<td><button type=\"button\" class=\"blueButton\" onclick=\"navto('shopping_cart/tax_rates.php');\"><span><span>".lang("Tax Rate Options")."</span></span></button></td>\n";
	$THIS_DISPLAY .= "		<td><button type=\"button\" class=\"grayButton\" onclick=\"navto('shopping_cart/returns_policy.php');\"><span><span>".lang("Returns/Exchanges Policy")."</span></span></button></td>\n";
	$THIS_DISPLAY .= "	</tr>\n";
	
	$THIS_DISPLAY .= "	<tr>\n";
	$THIS_DISPLAY .= "		<td class=\"first-child\">&nbsp;</td>\n";
	$THIS_DISPLAY .= "		<td><button type=\"button\" class=\"blueButton\" onclick=\"navto('shopping_cart/coupon_codes.php');\"><span><span>".lang("Coupon Codes")."</span></span></button></td>\n";
	$THIS_DISPLAY .= "		<td><button type=\"button\" class=\"grayButton\" onclick=\"navto('shopping_cart/other_policies.php');\"><span><span>".lang("Other Policies")."</span></span></button></td>\n";
	$THIS_DISPLAY .= "	</tr>\n";

} // End != 0 catcount statement


/// [ View Online Orders/Invoices ]
###----------------------------------------------------
# LOOK FOR SSL CERT SETUP IN CART OPTIONS
$result = mysql_query("SELECT * FROM cart_options");
$OPTIONS = mysql_fetch_array($result);
$get_md5 = mysql_query("SELECT Rank FROM login WHERE PriKey = '1'");
$tmp = mysql_fetch_array($get_md5);
$MD5MATCH = $tmp[Rank];
if ( strlen($OPTIONS['PAYMENT_SSL']) > 4 && strpos($_SESSION['this_ip'],$OPTIONS['PAYMENT_SSL']) != false ) {
	$SECURE_SITE_LINK = $OPTIONS['PAYMENT_SSL'] ."/sohoadmin/program/modules/mods_full/shopping_cart/view_orders.php?SID=".session_id();
} else {
	$SECURE_SITE_LINK = $_SERVER['PHP_SELF'];
	$SECURE_SITE_LINK = eregi_replace("shopping_cart.php", "shopping_cart/view_orders.php", $SECURE_SITE_LINK);
}

# Order count (make sure table exists)
$inv_result = mysql_query('select ORDER_NUMBER from cart_invoice');
$order_count = mysql_num_rows($inv_result);
if ( $order_count < 1 ) { $order_count = 0; }
$THIS_DISPLAY .= "	<tr>\n";
$THIS_DISPLAY .= "		<td colspan=\"3\" class=\"first-child\" id=\"view-invoices-container\">\n";
$THIS_DISPLAY .= "			<p>Number of Orders: [<b>".$order_count."</b>]</p>";
if ( $order_count > 1 ) {
	$THIS_DISPLAY .= "			<button type=\"button\" class=\"greenButton\" onclick=\"navto('".$SECURE_SITE_LINK."');\"><span><span>".lang("View Online Orders/Invoices")." &gt;&gt;</span></span></button>\n";
} else {
	$THIS_DISPLAY .= "			<button type=\"button\" class=\"greenButton\" onclick=\"navto('".$SECURE_SITE_LINK."');\"><span><span>".lang("View Online Orders/Invoices")." &gt;&gt;</span></span></button>\n";
}
$THIS_DISPLAY .= "		</td>\n";
$THIS_DISPLAY .= "	</tr>\n";
$THIS_DISPLAY .= "</table>\n";

echo $THIS_DISPLAY;

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$module = new smt_module($module_html);
$module->add_breadcrumb_link("Shopping Cart Menu", "program/modules/mods_full/shopping_cart.php");
$module->icon_img = "program/includes/images/shopping-icon-med.png";
$module->heading_text = "Shopping Cart Menu";
$module->description_text = "Ready to sell stuff on your website? Click on the buttons below to setup and manage your online store.";
$module->good_to_go();
?>