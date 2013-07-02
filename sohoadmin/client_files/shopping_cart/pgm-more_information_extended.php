<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

$THIS_DISPLAY .= "<script language=\"javascript\">\n";
$THIS_DISPLAY .= "function emptycheck(fcount){ \n";
$THIS_DISPLAY .= "	var thisval = \"\";\n";
$THIS_DISPLAY .= "	var countvar = 0;\n";
$THIS_DISPLAY .= "	var runningcount = 0;\n";
$THIS_DISPLAY .= "	while(countvar < fcount){\n";
$THIS_DISPLAY .= "		countvar++;\n";
$THIS_DISPLAY .= "		thisval = document.getElementById(\"qty\"+countvar).value;\n";
$THIS_DISPLAY .= "		runningcount = parseInt(runningcount) + parseInt(thisval);\n";
$THIS_DISPLAY .= "	}\n";
$THIS_DISPLAY .= "	if(runningcount > 0){\n";
$THIS_DISPLAY .= "		document.addcart.submit();\n";
$THIS_DISPLAY .= "	} else {\n";
$THIS_DISPLAY .= "		document.getElementById('qty1').focus();\n";
$THIS_DISPLAY .= "	}\n";
$THIS_DISPLAY .= "} \n";
$THIS_DISPLAY .= "</script> \n";

eval(hook("pgm-more_information.php:above_moreinfo-pricing_table"));
eval(hook("pgm-more_information_extended.php:addninfocontent"));
$THIS_DISPLAY .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" id=\"moreinfo-pricing\">\n";
$THIS_DISPLAY .= " <tr>\n";

$THIS_DISPLAY .= "  <th class=\"smtext\" align=\"left\">".lang("Product")."</td>\n";
$THIS_DISPLAY .= " </tr>\n";


// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Start Calculation of display based on single unit price or multiple variants
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$cssarray = unserialize($OPTIONS['CSS']);
$table_bgcolor = $cssarray['table_bgcolor'];
$table_textcolor = $cssarray['table_textcolor'];

$formcount = 0;

if ( $PROD['variant_prices'][1] != "") {
$PRICE_OPTIONS .= "<SELECT NAME=price CLASS=smtext>\n";
for ( $z = 1; $z <= $PROD['num_variants']; $z++ ) {    // Loop through 6 variants and see what we have
  if ( $PROD['variant_prices'][$z] != "" ) {
    $PRICE_OPTIONS .= "     <OPTION VALUE=\"".$PROD['variant_names'][$z].";".$PROD['variant_prices'][$z]."\">".$PROD['variant_names'][$z]." - ".$dSign.$PROD['variant_prices'][$z]."</OPTION>\n";
  }
}

$PRICE_OPTIONS .= "</SELECT>\n";
} else {
  $PRICE_OPTIONS .= "".$dSign."".$PROD['PROD_UNITPRICE']."\n<input type=hidden name=\"price\" value=\"".$PROD['PROD_UNITPRICE']."\">\n";   // If not variant; this is a single unitprice
} // Finished Calculating Price Variants
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Now Let's calculate the different sub-categories or single product category
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
if ($PROD['sub_cats'][1] != "") {  // This must be a multiple category selection

  # Restore price variation
  $THIS_DISPLAY_ROW1 = "<td style=\"background-color:#".$OPTIONS['DISPLAY_CARTBG']."; color:".$OPTIONS['DISPLAY_CARTTXT']."; font-weight:strong; padding:0px 2px 0px 2px;\">&nbsp;</td>\n";
  $THIS_DISPLAY_ROW2 = " <td style=\"background-color:#".$OPTIONS['DISPLAY_CARTBG']."; color:".$OPTIONS['DISPLAY_CARTTXT']."; font-weight:strong; padding:0px 2px 0px 2px;\">&nbsp;</td>\n";
  foreach($PROD['variant_prices'] as $varvar=>$varval){
    if ( $PROD['variant_prices'][$varvar] != "" && $PROD['variant_names'][$varvar] != "" ) {
      $THIS_DISPLAY_ROW1 .= "<td style=\"background-color:#".$OPTIONS['DISPLAY_CARTBG']."; color:".$OPTIONS['DISPLAY_CARTTXT']."; font-weight:strong; padding:0px 2px 0px 2px;\">".$PROD['variant_names'][$varvar]."</td>\n";
      $THIS_DISPLAY_ROW2 .= "<td style=\"background-color:#".$OPTIONS['DISPLAY_CARTBG']."; color:".$OPTIONS['DISPLAY_CARTTXT']."; font-weight:strong; padding:0px 2px 0px 2px;\">".$dSign.$varval."</td>\n";
    }
  }

  $THIS_DISPLAY .= "\n\n<form name=\"addcart\" method=\"post\" action=\"pgm-add_cart.php\">\n";
  $THIS_DISPLAY .= "<input type=\"hidden\" name=\"id\" value=\"".$PROD['PRIKEY']."\">\n";
  $THIS_DISPLAY .= "<input type=\"hidden\" name=\"special_var_form\" value=\"yes\">\n";
  $THIS_DISPLAY .= "<input type=\"hidden\" name=\"goto_checkout\" value=\"".$OPTIONS['GOTO_CHECKOUT']."\">\n";
  $THIS_DISPLAY .= "<input type=\"hidden\" name=\"subcat\" value=\"".$PROD['sub_cats'][$z]."\">\n";

  for ( $z = 1; $z <= $PROD['num_variants']; $z++ ) { // Loop through sub-cats and create display HTML this way
    $tmp_cat = "SUB_CAT".$z;

    if ( $PROD['sub_cats'][$z] != "" ) {
      $THIS_DISPLAY2 .= " <tr>\n";
      $THIS_DISPLAY2 .= "  <td class=\"smtext\" align=\"left\">".$PROD['PROD_NAME']." - ".$PROD['sub_cats'][$z]."</td>\n";

      foreach($PROD['variant_prices'] as $varvar=>$varval){
        if ( $PROD['variant_prices'][$varvar] != "" && $PROD['variant_names'][$varvar] != "" ) {
        	++$formcount;
          $THIS_DISPLAY2 .= "  <td class=\"smtext\" align=\"left\"><input id=\"qty".$formcount."\" type=\"text\" SIZE=\"2\" class=smtext name=\"qty~".$PROD['sub_cats'][$z].'~'.$PROD['variant_names'][$varvar].';'.$PROD['variant_prices'][$varvar]."\" value=\"0\">$STUFFS</td>\n";
        }
      }
      $THIS_DISPLAY2 .= " </tr>\n";
    }
  } // End For Loop

  $THIS_DISPLAY .= " <td><table width=\"100%\" border=\"0\" cellspacing=0 cellpadding=0><tr>\n";
  $THIS_DISPLAY .= $THIS_DISPLAY_ROW1;
  $THIS_DISPLAY .= "</tr><tr>\n";
  $THIS_DISPLAY .= $THIS_DISPLAY_ROW2;
  $THIS_DISPLAY .= "</tr>\n";
  $THIS_DISPLAY .= $THIS_DISPLAY2;
  $THIS_DISPLAY .= "</table></td></tr>\n";
  if ( $PROD['OPTION_INVENTORY_NUM'] <= 0 ) {
     $THIS_DISPLAY .= "  <tr><td class=\"smtext\" align=\"right\">".lang("Out of Stock")."</td>\n";
  } else {
     $THIS_DISPLAY .= "  <tr><td class=\"smtext\" align=\"right\"><input TYPE=\"button\" value=\"".lang("Add")."\" onClick=\"emptycheck('".$formcount."');\" class=\"FormLt1\"></td>\n";
  }
  $THIS_DISPLAY .= "</form></tr>\n\n";

} else { // This is a single cat product

  # Restore price variation
  $THIS_DISPLAY_ROW1 = "<td style=\"background-color:#".$OPTIONS['DISPLAY_CARTBG']."; color:".$OPTIONS['DISPLAY_CARTTXT']."; font-weight:strong; padding:0px 2px 0px 2px;\">&nbsp;</td>\n";
  $THIS_DISPLAY_ROW2 = " <td style=\"background-color:#".$OPTIONS['DISPLAY_CARTBG']."; color:".$OPTIONS['DISPLAY_CARTTXT']."; font-weight:strong; padding:0px 2px 0px 2px;\">&nbsp;</td>\n";
  foreach($PROD['variant_prices'] as $varvar=>$varval){
    if ( $PROD['variant_prices'][$varvar] != "" && $PROD['variant_names'][$varvar] != "" ) {
      $THIS_DISPLAY_ROW1 .= "<td style=\"background-color:#".$OPTIONS['DISPLAY_CARTBG']."; color:".$OPTIONS['DISPLAY_CARTTXT']."; font-weight:strong; padding:0px 2px 0px 2px;\">".$PROD['variant_names'][$varvar]."</td>\n";
      $THIS_DISPLAY_ROW2 .= "<td style=\"background-color:#".$OPTIONS['DISPLAY_CARTBG']."; color:".$OPTIONS['DISPLAY_CARTTXT']."; font-weight:strong; padding:0px 2px 0px 2px;\">".$dSign.$varval."</td>\n";
    }
  }

  $THIS_DISPLAY .= "\n\n<form name=\"addcart\" method=\"post\" action=\"pgm-add_cart.php\">\n";
  $THIS_DISPLAY .= "<input type=\"hidden\" name=\"id\" value=\"".$PROD['PRIKEY']."\">\n";
  $THIS_DISPLAY .= "<input type=\"hidden\" name=\"special_var_form\" value=\"yes\">\n";
  $THIS_DISPLAY .= "<input type=\"hidden\" name=\"goto_checkout\" value=\"".$OPTIONS['GOTO_CHECKOUT']."\">\n";
  $THIS_DISPLAY .= "<input type=\"hidden\" name=\"subcat\" value=\"".$PROD['sub_cats'][$z]."\">\n";

//      for ( $z = 1; $z <= $PROD['num_variants']; $z++ ) { // Loop through sub-cats and create display HTML this way
    $tmp_cat = "SUB_CAT".$z;

//        if ( $PROD['sub_cats'][$z] != "" ) {
      $THIS_DISPLAY2 .= " <tr>\n";
      $THIS_DISPLAY2 .= "  <td class=\"smtext\" align=\"left\">".$PROD['PROD_NAME']."</td>\n";

      foreach($PROD['variant_prices'] as $varvar=>$varval){
        if ( $PROD['variant_prices'][$varvar] != "" && $PROD['variant_names'][$varvar] != "" ) {
        	++$formcount;
          $THIS_DISPLAY2 .= "  <td class=\"smtext\" align=\"left\"><input id=\"qty".$formcount."\" type=\"text\" SIZE=\"2\" class=smtext name=\"qty~".$PROD['sub_cats'][$z].'~'.$PROD['variant_names'][$varvar].';'.$PROD['variant_prices'][$varvar]."\" value=\"0\">$STUFFS</td>\n";
        }
      }
      $THIS_DISPLAY2 .= " </tr>\n";
//        }
//      } // End For Loop

  $THIS_DISPLAY .= " <td><table width=\"100%\" border=\"0\" cellspacing=0 cellpadding=0><tr>\n";
  $THIS_DISPLAY .= $THIS_DISPLAY_ROW1;
  $THIS_DISPLAY .= "</tr><tr>\n";
  $THIS_DISPLAY .= $THIS_DISPLAY_ROW2;
  $THIS_DISPLAY .= "</tr>\n";
  $THIS_DISPLAY .= $THIS_DISPLAY2;
  $THIS_DISPLAY .= "</table></td></tr></table>\n";

  $THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td class=\"smtext\">\n";

  if ( $PROD['OPTION_INVENTORY_NUM'] <= 0 ) {
     $THIS_DISPLAY .= "  <tr><td class=\"smtext\" align=\"right\">".lang("Out of Stock")."</td>\n";
  } else {
     $THIS_DISPLAY .= "  <tr><td class=\"smtext\" align=\"right\"><input TYPE=\"button\" value=\"Add\" onClick=\"emptycheck('".$formcount."');\" class=\"FormLt1\"></td>\n";
  }
  $THIS_DISPLAY .= "</form></tr>\n\n";

} // End Sub Cat Check

// If this sku has a custom form attached, notify client that additional info
// will be gathered when user "adds" product to thier shopping cart.

if (eregi(".FORM", $PROD[OPTION_FORMDATA])) {

  $THIS_DISPLAY .= "<tr>\n";
  $THIS_DISPLAY .= "<td colspan=\"4\" class=\"smtext\" align=\"center\"><font color=\"maroon\">\n";
  $THIS_DISPLAY .= "<i>".lang("Details specific to this item will be asked when you add this product to your cart.")."\n";
  $THIS_DISPLAY .= "</td>\n";
  $THIS_DISPLAY .= "</tr>\n";

} // End if Custom Form Function

$THIS_DISPLAY .= "</table>\n";

?>