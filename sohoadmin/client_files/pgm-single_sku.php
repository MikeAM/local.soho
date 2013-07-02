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

$result = mysql_query("SELECT * FROM cart_options");
$OPTIONS = mysql_fetch_array($result);
$result = mysql_query("SELECT PRIKEY, PROD_SKU, PROD_NAME, PROD_DESC, PROD_UNITPRICE, PROD_THUMBNAIL, PROD_FULLIMAGENAME FROM cart_products WHERE PROD_SKU = '$sku_number'");
$PROD = mysql_fetch_array($result);
$qry = "SELECT PRIKEY, PROD_SKU, PROD_NAME, PROD_DESC, PROD_UNITPRICE, PROD_THUMBNAIL, PROD_FULLIMAGENAME FROM cart_products WHERE PROD_SKU = \'$sku_number\'";

$SINGLE_SKU_PROMO_HTML = '';
$module_css = '';
if($shopping_style_include != 1){	
	include_once("sohoadmin/client_files/shopping_cart/pgm-shopping_css.inc.php"); // Defines $module_css
}
$shopping_style_include = 1;

$SINGLE_SKU_PROMO_HTML = $module_css;


// Find thumbnail and deal with it
// --------------------------------------------------
//$imagesrc = chop($PROD[PROD_THUMBNAIL]);
if (strlen($PROD['PROD_THUMBNAIL']) > 2) {
   $imagesrc = chop($PROD[PROD_THUMBNAIL]);
} else {
   $imagesrc = chop($PROD[PROD_FULLIMAGENAME]);
}

//Added 040406 for intl currencies
$dSign = $OPTIONS['PAYMENT_CURRENCY_SIGN'];
$dType = $OPTIONS['PAYMENT_CURRENCY_TYPE'];

$directory = "$doc_root/images";

if (file_exists("$directory/$imagesrc")) {
   $tmparray = getImageSize("$directory/$imagesrc");
   $origw = $tmparray[0];
   $origh = $tmparray[1];
   $WH = "width=$origw height=$origh";

   if ($origw > 114) {
      $calc = 114 / $origw;
      $hcalc = $origh * $calc;
      $nheight = round($hcalc);
      $WH = "width=115 height=$nheight";
   }

   $IMAGE_PLACEMENT = "<a href=\"shopping/pgm-more_information.php?id=$PROD[PRIKEY]&=SID\">";
   $IMAGE_PLACEMENT .= "<img src=\"images/$imagesrc\" $WH border=0 align=left vspace=2 hspace=3 alt=\"".lang("Click Here for Product Details")."!\">";
   $IMAGE_PLACEMENT .= "</a>";

} else {

   $IMAGE_PLACEMENT = "";
   $txt_format = "";

} // End confirm image exists


// ------------------------------------------------------------------------
// BUILD DISPLAY HTML
// ------------------------------------------------------------------------
$SINGLE_SKU_PROMO_HTML .= "<div id=\"shopping_module\">\n";
$SINGLE_SKU_PROMO_HTML .= " <form method=\"get\" action=\"shopping/pgm-more_information.php\">\n";
$SINGLE_SKU_PROMO_HTML .= "  <input type=\"hidden\" name=\"id\" value=\"".$PROD['PRIKEY']."\">\n";

$SINGLE_SKU_PROMO_HTML .= "  <table class=\"shopping-selfcontained_box\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"90%\">\n";
$SINGLE_SKU_PROMO_HTML .= "   <tr>\n";
$SINGLE_SKU_PROMO_HTML .= "    <th>\n";
$SINGLE_SKU_PROMO_HTML .= "     ".$PROD['PROD_NAME']."\n";
$SINGLE_SKU_PROMO_HTML .= "    </th>\n";

$SINGLE_SKU_PROMO_HTML .= "    <th style=\"text-align: right;\">\n";
$SINGLE_SKU_PROMO_HTML .= "     ".$dSign.$PROD['PROD_UNITPRICE']."\n";
$SINGLE_SKU_PROMO_HTML .= "    </th>\n";
$SINGLE_SKU_PROMO_HTML .= "   </tr>\n";

$SINGLE_SKU_PROMO_HTML .= "   <tr>\n";
$SINGLE_SKU_PROMO_HTML .= "    <td colspan=\"2\" align=\"left\" valign=\"top\" class=\"text\">\n";
$SINGLE_SKU_PROMO_HTML .= "     ".$IMAGE_PLACEMENT;
$SINGLE_SKU_PROMO_HTML .= "     ".$PROD['PROD_DESC']."<BR><div align=\"left\">\n";
$SINGLE_SKU_PROMO_HTML .= "     [ <a href=\"shopping/pgm-more_information.php?id=$PROD[PRIKEY]&=SID\">".lang("More Information")."</a> ]</div>\n";

$SINGLE_SKU_PROMO_HTML .= "    </td>\n";
$SINGLE_SKU_PROMO_HTML .= "   </tr>\n";
$SINGLE_SKU_PROMO_HTML .= "   <tr>\n";
$SINGLE_SKU_PROMO_HTML .= "    <td colspan=\"2\" align=\"center\" valign=\"middle\">\n";

$SINGLE_SKU_PROMO_HTML .= "     <input type=\"submit\" class=\"FormLt1\" value=\" ".lang("Buy Now")."! \">\n";

$SINGLE_SKU_PROMO_HTML .= "    </td>\n";
$SINGLE_SKU_PROMO_HTML .= "   </tr>\n";
$SINGLE_SKU_PROMO_HTML .= "  </table>\n";
$SINGLE_SKU_PROMO_HTML .= " </form>\n";
$SINGLE_SKU_PROMO_HTML .= "</div>\n";

?>