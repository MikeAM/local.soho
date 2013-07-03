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

//Grr. No me gusta grimlens.
error_reporting(E_PARSE);
//change
include('../sohoadmin/client_files/cart_discount.php');
unset($total_discounts);
foreach($_SESSION['unitsub_array'] as $cama=>$camb){
  if($camb != ''){
    $total_discounts[] = $camb;
  }
}
//change
$BCOUNTRY = strtoupper($BCOUNTRY);
$SCOUNTRY = strtoupper($SCOUNTRY);
$BSTATE = strtoupper($BSTATE);
$SSTATE = strtoupper($SSTATE);

$HTML = "<TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 WIDTH=100% BORDER=0>\n";
$HTML .= "<TR>\n";
$HTML .= "<TD ALIGN=LEFT VALIGN=TOP CLASS=text>\n";
$HTML .= "<B><U>".lang("BILLING INFORMATION")."</U></B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!-- EDIT --><BR><BR>\n";

$HTML .= "$BFIRSTNAME $BLASTNAME<BR>";

	if ($BCOMPANY != "") { $HTML .= "$BCOMPANY<BR>\n"; }

$HTML .= "$BADDRESS1<BR>\n";

	if ($BADDRESS2 != "") { $HTML .= "$BADDRESS2<BR>\n"; }

$HTML .= "$BCITY, $BSTATE &nbsp;&nbsp; $BZIPCODE<br>\n";

$HTML .= "$BCOUNTRY<BR><BR>\n";

$HTML .= "$BPHONE<BR>\n";
$HTML .= "$BEMAILADDRESS\n";


$HTML .= "</TD>\n";
$HTML .= "<TD ALIGN=LEFT VALIGN=TOP CLASS=text>\n";
$HTML .= "<B><U>".lang("SHIPPING INFORMATION")."</U></B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!-- EDIT --><BR><BR>\n";

$HTML .= "$SFIRSTNAME $SLASTNAME<BR>";

	if ($SCOMPANY != "") { $HTML .= "$SCOMPANY<BR>\n"; }

$HTML .= "$SADDRESS1<BR>\n";

	if ($SADDRESS2 != "") { $HTML .= "$SADDRESS2<BR>\n"; }

$HTML .= "$SCITY, $SSTATE &nbsp;&nbsp; $SZIPCODE<br>\n";

$tmpSC = split(" - ",$SCOUNTRY);
$HTML .= "$tmpSC[0] - $tmpSC[1]<BR><BR>\n";

$HTML .= "$SPHONE<BR>\n";
$HTML .= "</TD>\n";



$HTML .= "</TR>\n";
$HTML .= "</TABLE><BR>\n\n";

# Show view/edit cart link?
$cartpref = new userdata("cart");
if ( $cartpref->get("invoice_viewedit_link") == "yes" ) {
   $HTML .= "\n<!-- EDITCART -->\n";
}

############################################################################################
// Show invoice notice for offline shipping calculation
############################################################################################
if ( $SHIPPING_OPTS['SHIP_METHOD'] == "Offline" ) {
   $HTML .= "<table bgcolor=\"$OPTIONS[DISPLAY_HEADERBG]\" width=\"100%\" border=\"0\" cellpadding=\"6\" cellspacing=\"0\" class=\"text\" style=\"border: 1px solid #000000;\">\n";
   $HTML .= " <tr>\n";
   $HTML .= "  <td align=\"left\">\n";
   $HTML .= "   <font color=\"$OPTIONS[DISPLAY_HEADERTXT]\">\n";
   $HTML .= "   ".$SHIPPING_OPTS[NOTICE];
   $HTML .= "   </font>\n";
   $HTML .= "  </td>\n";
   $HTML .= " </tr>\n";
   $HTML .= "</table>\n";
   $HTML .= "<br>\n";
}

if ( $cartpref->get("invoice_custom_message") == "yes" ) {
	$HTML .= "<div id=\"custom-invoice-message\">".$cartpref->get("invoice_custom_message_text")."</div>\n";
}


############################################################################################
// -----------------------------------------------------------------------------------------
// NOW DISPLAY ACTUAL INVOICE LINE ITEMS
// -----------------------------------------------------------------------------------------
############################################################################################

$HTML .= "<TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 CLASS=text ALIGN=CENTER WIDTH=100% STYLE='border: 1px solid black;' id=\"invoice-parent\">\n";

$tmp_formdata = split(":p:", $CART_FORMDATA);
$tmp_keyid = split(";", $CART_KEYID);
$tmp_qty = split(";", $CART_QTY);
$tmp_skuno = split(";", $CART_SKUNO);
$tmp_catno = split(";", $CART_CATNO);

$tmp_name = split(";", $CART_PRODNAME);
$tmp_subcat = split(";", $CART_SUBCAT);
$tmp_varname = split(";", $CART_VARNAME);

$tmp_price = split(";", $CART_UNITPRICE);

$tmp_sub = split(";", $CART_UNITSUBTOTAL);

$display_subtotal = 0;	// Reset Display sub_total; we'll calculate that on the fly
$line_items = count($tmp_qty);	// Count the number of array vars we have after split
$line_items = $line_items - 2;	// Subtract 2 because (a)we start count at 0 (b) we always have a trailing semi-colon;

# Alternate row bg color
if ($sep == "row-altbg") { $sep = "row-normalbg"; } else { $sep = "row-altbg"; }

/*--Test session data---*
$HTML .= " <tr>\n";
$HTML .= "  <td colspan=\"4\" align=\"left\" class=\"text\">\n";
$HTML .= "   <b>Order Number: </b>".$ORDER_NUMBER."\n";
$HTML .= "  </td>\n";
$HTML .= " </tr>\n";
$HTML .= " <tr>\n";
$HTML .= "  <td colspan=\"4\" align=\"left\" class=\"text\">\n";
$HTML .= "   <b>Order Total: </b>".$ORDER_TOTAL."\n";
$HTML .= "  </td>\n";
$HTML .= " </tr>\n";
/*--Test session data---*/

$HTML .= "<TR>\n";
$HTML .= "<TD ALIGN=LEFT VALIGN=TOP CLASS=text BGCOLOR=$OPTIONS[DISPLAY_HEADERBG] WIDTH=40%><FONT COLOR=$OPTIONS[DISPLAY_HEADERTXT]>\n";
$HTML .= "<B>".lang("Product Name")."\n";
$HTML .= "</TD>\n";
$HTML .= "<TD ALIGN=LEFT VALIGN=TOP CLASS=text BGCOLOR=$OPTIONS[DISPLAY_HEADERBG] WIDTH=100><FONT COLOR=$OPTIONS[DISPLAY_HEADERTXT]>\n";
$HTML .= "<B>".lang("Unit Price")."\n";
$HTML .= "</TD>\n";
$HTML .= "<TD ALIGN=LEFT VALIGN=TOP CLASS=text BGCOLOR=$OPTIONS[DISPLAY_HEADERBG] WIDTH=100><FONT COLOR=$OPTIONS[DISPLAY_HEADERTXT]>\n";
$HTML .= "<B>".lang("Quantity")."\n";
$HTML .= "</TD>\n";
if(count($total_discounts) > 0){
	$HTML .= "<TD ALIGN=LEFT VALIGN=TOP CLASS=text BGCOLOR=$OPTIONS[DISPLAY_HEADERBG] WIDTH=100><FONT COLOR=$OPTIONS[DISPLAY_HEADERTXT]>\n";
	$HTML .= "<B>".lang("Discount")."\n";
	$HTML .= "</TD>\n";
	$HTML .= "<TD ALIGN=LEFT VALIGN=TOP CLASS=text BGCOLOR=$OPTIONS[DISPLAY_HEADERBG] WIDTH=100><FONT COLOR=$OPTIONS[DISPLAY_HEADERTXT]>\n";
} else {
	$HTML .= "<TD ALIGN=LEFT colspan=2 VALIGN=TOP CLASS=text BGCOLOR=$OPTIONS[DISPLAY_HEADERBG] WIDTH=100><FONT COLOR=$OPTIONS[DISPLAY_HEADERTXT]>\n";	
}

$HTML .= "<B>".lang("Sub-Total")."\n";
$HTML .= "</TD>\n";
$HTML .= "</TR>\n";

#change
$discounttotal = 0;
#change
$promo_applied_discount = 0;

for ($z=0;$z<=$line_items;$z++) {

	$display_subtotal = $display_subtotal + $tmp_sub[$z];	// Add Unit Sub Total to Total Sub Total ??? Confused yet?

	if ($sep == "row-altbg") { $sep = "row-normalbg"; } else { $sep = "row-altbg"; }

	if (strlen($tmp_subcat[$z]) > 2) { $tmp_varname[$z] = "- " . $tmp_varname[$z]; } // Format for proper display

	// -----------------------------------------------------------
	// Build display for sku number and catalog number in invoice
	// -----------------------------------------------------------

		$ref_id_display = "<div class=\"invoice-sku_name\">";
		if ($tmp_skuno[$z] != "") { $ref_id_display .= "[<U>Sku</U>: $tmp_skuno[$z]]  "; }
		if ($tmp_catno[$z] != "") { $ref_id_display .= "[<U>Cat</U>: $tmp_catno[$z]]  "; }
		$ref_id_display .= "</div>";
		$ref_id_display = strtoupper($ref_id_display);

	// -----------------------------------------------------------

	$tmp_name[$z] = str_replace("#Q#", "&quot;", $tmp_name[$z]);	// Fix for quotes not appearing in product name

	$HTML .= "<TR>\n";
	$HTML .= "<TD ALIGN=LEFT VALIGN=TOP class=\"text ".$sep."\">\n";

	//Do we need to display special VAT rate?

   
   $specVat = "";
   if ($getVat['SPECIAL_TAX'] != "" && $OPTIONS['CHARGE_VAT'] == "yes" && $OPTIONS['VAT_REG'] != "vatnum") {
      $specVat = "<font color='#7A0000'>(VAT ".$getVat['SPECIAL_TAX']."%)</font>";
   }

	$HTML .= "$ref_id_display<U>".$tmp_name[$z]."</U> ".$specVat."<BR><DIV CLASS=smtext>".$tmp_subcat[$z]." ".$tmp_varname[$z]."\n";
	$HTML .= "<font style=\"font-family:arial,helvetica,sans-serif; font-size:9px; color:darkgreen; font-weight:bold;\">";

	//format form data into line-items
   $form_line = split(":br:", $tmp_formdata[$z]);
   $num_fl = count($form_line);
   

   
   for ($f=0;$f<=$num_fl;$f++) {
      $HTML .= $form_line[$f] . "<br>\n";
      }
      
eval(hook("pgm-more_information.php:dispinv"));

	$HTML .= "</font>";
	$HTML .= "</TD>\n";
	$HTML .= "<TD ALIGN=LEFT VALIGN=TOP class=\"text ".$sep."\">\n";
	$HTML .= $dSign."".sprintf ("%01.2f", $tmp_price[$z])."\n";
	$HTML .= "</TD>\n";
	$HTML .= "<TD ALIGN=LEFT VALIGN=TOP class=\"text ".$sep."\">\n";
	$HTML .= "[".$tmp_qty[$z]."]\n";
	$HTML .= "</TD>\n";
	#CHANGE
	if(count($total_discounts) > 0){
		$HTML .= "<TD ALIGN=LEFT VALIGN=TOP class=\"text ".$sep."\">\n";
		$HTMLz = '';
		if($_SESSION['unitsub_array'][$z] != 0) {
			if($_SESSION['discount_ammounts'][$z] != '') {
				$_SESSION['unitsub_array'][$z] = $_SESSION['unitsub_array'][$z] * -1;
				$HTMLz .= "- ".$dSign.sprintf ("%01.2f", $_SESSION['unitsub_array'][$z]);
				$HTMLz .= " (".$_SESSION['discount_ammounts'][$z].")";
			} else {
				$_SESSION['unitsub_array'][$z] = $_SESSION['unitsub_array'][$z] * -1;
				$HTMLz .= "- ".$dSign.sprintf ("%01.2f", $_SESSION['unitsub_array'][$z]);
				
				//$HTML .= " (".$_SESSION['discount_ammounts'][$z].")";
			}
		}
		$HTML .= $HTMLz;
		$HTML .= "</TD>\n";
	}
	#CHANGE
	if(count($total_discounts) > 0){
		$HTML .= "<TD ALIGN=LEFT VALIGN=TOP class=\"text ".$sep."\">\n";
	} else {
		$HTML .= "<TD colspan=2 ALIGN=LEFT VALIGN=TOP class=\"text ".$sep."\">\n";
	}
	$tmp_sub[$z] = $tmp_sub[$z] - $_SESSION['unitsub_array'][$z];
	$tmp_sub[$z] = sprintf ("%01.2f", $tmp_sub[$z]);
	
	$HTML .= $dSign."$tmp_sub[$z]\n";
	$HTML .= "</TD>\n";

	$HTML .= "</TR>\n";
	#change
	$discounttotal = $_SESSION['unitsub_array'][$z] + $discounttotal;
	#change
	//error_reporting('-1');
	
	if($_POST['promocode'] != ''){
		//echo testArray($_SESSION);
		$pcode = strtoupper(slashthis($_POST['promocode']));
		$findcodeq = mysql_query("select prikey, UPPER(code), products, categories, discount_type, discount_amount, cart_min, expiration, disc_per from cart_coupons where code='".$pcode."' and expiration >= '".date('Y/m/d')."'");
		$findcode = mysql_fetch_assoc($findcodeq);

		if(!preg_match('/;/', $findcode['products'])){
			$prod_coup_ar[] = $findcode['products'];
		} else {
			$prod_coup_ar = explode(';', $findcode['products']);
		}
	
		if(in_array($tmp_keyid[$z], $prod_coup_ar) || $findcode['products'] == 'all'){
			if(($findcode['disc_per']=='once' && $promo_applied_discount=='') || $findcode['disc_per']!='once'){
				if($findcode['discount_type']=='%'){
					if($findcode['disc_per'] == 'once'){
						$promo_applied_discount = ($findcode['discount_amount'] * .01) * ($tmp_sub[$z] / $tmp_qty[$z]);
					} else {
						$promo_applied_discount = (($findcode['discount_amount'] * .01) * $tmp_sub[$z]) + $promo_applied_discount;
					}				
				} else {
					if($findcode['disc_per'] == 'once'){
						$promo_applied_discount = $findcode['discount_amount'];
					} else {
						$promo_applied_discount = ($findcode['discount_amount'] * $tmp_qty[$z]) + $promo_applied_discount;
					}	
				}
			}
			$_SESSION['promo_code'] = $pcode;
		}
	}


}	// End of $z Loop


// ========================================================================================
// DISPLAY ORDER SUB-TOTAL
// ========================================================================================

if ($sep == "row-altbg") { $sep = "row-normalbg"; } else { $sep = "row-altbg"; }
$HTML .= "<TR>\n";
$HTML .= "<TD colspan=5 ALIGN=LEFT VALIGN=MIDDLE class=\"text ".$sep."\">\n";
$HTML .= "<FONT SIZE=1 FACE=ARIAL></FONT>\n";
$HTML .= "</TD></tr>\n";
if(count($total_discounts) > 0){
	$HTML .= "<tr><TD colspan=4 ALIGN=RIGHT VALIGN=MIDDLE class=\"text ".$sep."\">\n";
	$disct = $discounttotal;
	$discounttotal = $discounttotal * -1;
	$display_subtotal=$display_subtotal+$discounttotal;
	$display_subtotal = sprintf ("%01.2f", $display_subtotal);
	$HTML .= "Discounts</B>: </td><TD ALIGN=left VALIGN=MIDDLE class=\"text ".$sep."\">- ".$dSign.sprintf ("%01.2f", $disct)."\n";
	$HTML .= "</TD>\n";
$HTML .= "</tr>\n";
} else {
	//$HTML .= "<tr><TD colspan=3 ALIGN=RIGHT VALIGN=MIDDLE class=\"text ".$sep."\">\n";
}
#change

$promofail = '';
if($_POST['promocode'] != '' && $promo_applied_discount == ''){
	$promofail = 'fail';
}


if($promo_applied_discount != ''){
	$HTML .= "<tr><TD colspan=4 ALIGN=RIGHT VALIGN=MIDDLE class=\"text ".$sep."\">\n";
	$HTML .= ucwords(strtolower($_SESSION['promo_code'])).' '.lang("Promo Discount")."</B>:\n";
	$HTML .= "</TD>\n";
	$HTML .= "<TD colspan=4 ALIGN=LEFT VALIGN=MIDDLE class=\"text ".$sep."\">\n";
	$display_subtotal = $display_subtotal + ($promo_applied_discount * -1);
	$HTML .= '- '.$dSign."<U>".sprintf("%01.2f", $promo_applied_discount)."</U>\n";
	//$HTML .= $dSign."<U>".sprintf("%01.2f", $promo_applied_discount)."</U>\n";
	$HTML .= "</TD>\n";
	$HTML .= "</TR>\n";
//	echo testArray($findcode);	
}

$HTML .= "<tr><TD colspan=4 ALIGN=RIGHT VALIGN=MIDDLE class=\"text ".$sep."\">\n";
$HTML .= lang("Sub-Total")."</B>:\n";
$HTML .= "</TD>\n";
$HTML .= "<TD colspan=4 ALIGN=LEFT VALIGN=MIDDLE class=\"text ".$sep."\">\n";

$display_subtotal = sprintf ("%01.2f", $display_subtotal);

$HTML .= $dSign."<U>$display_subtotal</U>\n";
$HTML .= "</TD>\n";

$HTML .= "</TR>\n";
#change
// ========================================================================================
// DISPLAY TAX TOTAL
// ========================================================================================

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Read Tax Table to determine if current ship-to state/country needs
// to be charged sales tax
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//Make sure tax variables are upper case
$taxpref = new userdata("tax_rate_options");
if ( $taxpref->get("taxby") == "" ) { $taxpref->set("taxby", "SCOUNTRY"); }

$BSTATE = strtoupper($BSTATE);
$SSTATE = strtoupper($SSTATE);
$BCOUNTRY = strtoupper($BCOUNTRY);
$SCOUNTRY = strtoupper($SCOUNTRY);
$state_to_tax = ${str_replace('COUNTRY', 'STATE', $taxpref->get("taxby"))};
$stax_perc = "$stax_perc";
$result = mysql_query("SELECT * FROM cart_tax");
while ($TAX = mysql_fetch_array($result)) {
	if($state_to_tax == $TAX['state']) {
		$stax_perc = $TAX['rate'];
	} elseif($OPTIONS['DISPLAY_STATE']=='tfield'){
		$txstate = explode(' - ', $TAX['state']);
		if($state_to_tax == $txstate['0']){
			$stax_perc = $TAX['rate'];	
		} elseif($state_to_tax == $txstate['1']){
			$stax_perc = $TAX['rate'];
		}
	}
}
//======================
//Check for VAT tax
//======================
$taxpref = new userdata("tax_rate_options");
# Make sure there's a VAT number
if ($OPTIONS['CHARGE_VAT'] == "yes" && $OPTIONS['VAT_REG'] != "vatnum") {
   $ctax_perc = "$ctax_perc";
   $result = mysql_query("SELECT * FROM cart_vat");

	# Set default if no value
	if ( $taxpref->get("taxby") == "" ) { $taxpref->set("taxby", "BCOUNTRY"); }
	
	while ( $VAT = mysql_fetch_assoc($result) ) {
		if ( ${$taxpref->get("taxby")} == $VAT['country'] ) {
		 	$ctax_perc = $VAT['rate'];
		}
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// If so, calculate tax
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
if ( ($stax_perc != "") || ($ctax_perc != "") ) {
	$staxrate = $stax_perc;
	$ctaxrate = $ctax_perc;
	$scalcTax = $staxrate / 100;
	$ccalcTax = $ctaxrate / 100;
	$exempt = 0;                     // Bugzilla #38
	$vatSub = 0;                     // Euro Stuff - 040408
	$numSkus = count($tmp_skuno);    // Bugzilla #38

// Account for "tax exempt" items in calculation - Bugzilla #38
// 2004-04-08: Also account for items with special VAT rate
// 2004-08-02: Check new "SPECIAL_TAX" field and leave SHIPC alone

	for ($m=0;$m<=$numSkus;$m++) {
	   $result = mysql_query("SELECT * FROM cart_products WHERE PROD_SKU = '$tmp_skuno[$m]'");
      $getProd = mysql_fetch_array($result);
	   if ($getProd[OPTION_CHARGETAX] == "N" ) {
	      $exempt += $tmp_sub[$m];
	   }
	   if ($getProd[SPECIAL_TAX] != "") {
	      $vatRate = $getProd[SPECIAL_TAX];
	      $calcVat = $vatRate / 100;
	      $vatSub += $calcVat * $tmp_sub[$m];

	   }
	}

	$tax_subtotal = $display_subtotal - $exempt;  // Bugzilla #38

	$sthisTax = $tax_subtotal * $scalcTax;  // Bugzilla #38
	$cthisTax = $tax_subtotal * $ccalcTax;  // Bugzilla #38

	$stax = number_format($sthisTax,2);
	$ctax = number_format($cthisTax,2);

	$staxrate = "( $stax_perc% )";

	if ( $ctax_perc == "" ){
	   $ctaxrate = "( 0% )";
	} else {
	   $ctaxrate = "( $ctax_perc% )";
	}


} else {
	$staxrate = "";
	$ctaxrate = "";

// Account for Special Tax items when no tax is charged - Bugzilla #38
	$numSkus = count($tmp_skuno);    // Bugzilla #38
	for ($s=0;$s<=$numSkus;$s++) {
	   $result = mysql_query("SELECT * FROM cart_products WHERE PROD_SKU = '$tmp_skuno[$s]'");
      $getSpecial = mysql_fetch_array($result);
	   if ($getSpecial[SPECIAL_TAX] != "") {
	      $vatRate = $getSpecial[SPECIAL_TAX];
	      $calcVat = $vatRate / 100;
	      $vatSub += $calcVat * $tmp_sub[$s];
	   }
	}

	//$sdisplay_tax = "0.00";
	//$cdisplay_tax = "0.00";
}

###########################################################################################
// DISPLAY SHIPPING TOTAL
###########################################################################################
#change
$HTML .= " <TR>\n";
$HTML .= "  <TD ALIGN=RIGHT VALIGN=MIDDLE class=\"text ".$sep."\" COLSPAN=4>\n";
$HTML .= "   $SHIPPING_INSTRUCTIONS ".lang("Shipping & Handling")."</B>:\n";
$HTML .= "  </TD>\n";
#change
$HTML .= "  <TD ALIGN=LEFT VALIGN=MIDDLE class=\"text ".$sep."\">\n";
$sshiptax = 0;
$cshiptax = 0;

// Do not echo price value if using Offline Shipping
// ===================================================
if ( $SHIPPING_OPTS[SHIP_METHOD] == "Offline" ) {
   // Pending Calculation
   $HTML .= "   <font color=\"#d70000\">Pending Calculation</font>\n";
} else {

   # Account for shipping option to disable charging shipping for individual products
   # Note: Only for non-subtotal shipping charge method. SubTotal-based shipping charges calculated in pgm-checkout.php:825
   if ( $SHIPPING_OPTS['SHIP_METHOD'] != "SubTotal" ) {
   	for ($m=0;$m<=$numSkus;$m++) {

   	   $result = mysql_query("SELECT * FROM cart_products WHERE PROD_SKU = '$tmp_skuno[$m]'");
         $getProd = mysql_fetch_array($result);

   				if ($getProd['OPTION_CHARGESHIPPING'] == "N") {
                  $amoutToDel = $getProd['PROD_SHIPA']*$tmp_qty[$m];
                  $SHIPPING_TOTAL = $SHIPPING_TOTAL - $amoutToDel;

//                  # Testing - Items with disabled shipping
//                  echo "shipping for sku num(".$getProd[PROD_SKU].") and prikey (".$getProd[PRIKEY].") is (".$getProd[PROD_SHIPA].")<br>";
//                  echo "The current row is (".$m.")<br>";
//                  echo "qty in session (".$tmp_qty[$m].")<br>";
//                  echo "This is the amount to delete (".$amoutToDel.")<br>";

   				}

   		}
   }
   $SHIPPING_TOTAL = sprintf ("%01.2f", $SHIPPING_TOTAL);
   $HTML .= "   ".$dSign.$SHIPPING_TOTAL."\n";
	$SHIPPING_TAX = $SHIPPING_TOTAL;
	//$SHIPPING_TAX = $SHIPPING_TOTAL * .125;
}

$HTML .= "  </TD>\n";
$HTML .= " </TR>\n";

# Country tax
if ( $cthisTax != "" && $cthisTax > 0 ) {
   $HTML .= "<TR>\n";
//   $HTML .= "<TD ALIGN=LEFT VALIGN=MIDDLE class=\"text ".$sep."\">\n";
//   $HTML .= "&nbsp;\n";
//   $HTML .= "</TD>\n";

   //$HTML .= "<TD ALIGN=RIGHT VALIGN=MIDDLE class=\"text ".$sep."\" COLSPAN=2>\n";

   $HTML .= "<TD ALIGN=RIGHT VALIGN=MIDDLE class=\"text ".$sep."\" COLSPAN=4>\n";
   $HTML .= " ".${$taxpref->get("taxby")}." Tax ".$ctaxrate."</b>:\n";
   $HTML .= "</TD>\n";


   $HTML .= "<TD ALIGN=LEFT VALIGN=MIDDLE class=\"text ".$sep."\">\n";

   $cthisTax = sprintf ("%01.2f", $cthisTax);
	if($taxpref->get("taxwhen") != "aftershipping"){
		$cthisTax = $cthisTax;
	} else {
		$cthisTax = $cthisTax + ($SHIPPING_TAX * $ccalcTax);
	}
   $ttax = $cthisTax;
   //$ttax = $cthisTax + ($SHIPPING_TAX * $ccalcTax);

   $HTML .= $dSign.sprintf("%01.2f", $ttax)."\n";
   $HTML .= "</TD>\n";
   $HTML .= "</TR>\n";
}

# Vat item tax
if ( $vatSub != "" && $vatSub != "0.00" ){
   $HTML .= "<TR>\n";
//   $HTML .= "<TD ALIGN=LEFT VALIGN=MIDDLE class=\"text ".$sep."\">\n";
//   $HTML .= "<FONT SIZE=1 FACE=ARIAL></FONT>\n";
//   $HTML .= "</TD>\n";


   $HTML .= "<TD ALIGN=RIGHT VALIGN=MIDDLE class=\"text ".$sep."\" COLSPAN=4>\n";
   $HTML .= "Special Tax ".$getVat[SPECIAL_TAX]."%</B>: \n";
   $HTML .= "</TD>\n";

   $HTML .= "<TD ALIGN=LEFT VALIGN=MIDDLE class=\"text ".$sep."\">\n";

   $vatSub = sprintf ("%01.2f", $vatSub);


   $HTML .= $dSign."".sprintf ("%01.2f", $vatSub)."\n";
   $HTML .= "</TD>\n";
   $HTML .= "</TR>\n";
}


# State tax: Process here here if "taxwhen" option is set to charge tax AFTER shipping
if ( $sthisTax != "" && $sthisTax != "0.00") {
   $HTML .= "<TR>\n";
//   $HTML .= "<TD ALIGN=LEFT VALIGN=MIDDLE class=\"text ".$sep."\">\n";
//   $HTML .= "&nbsp;\n";
//   $HTML .= "</TD>\n";
   $HTML .= "<TD colspan=\"4\" ALIGN=RIGHT VALIGN=MIDDLE class=\"text ".$sep."\">\n";
   $HTML .= $state_to_tax." ".lang("Tax")." ".$staxrate."</B>: \n";
   $HTML .= "</TD>\n";
   $HTML .= "<TD ALIGN=LEFT VALIGN=MIDDLE class=\"text ".$sep."\">\n";

   //$sthisTax =  $sthisTax);
	if($taxpref->get("taxwhen") != "aftershipping"){
		$sthisTax = $sthisTax;
	} else {
		$sthisTax = $sthisTax + ($SHIPPING_TAX * $scalcTax);
	}
   $HTML .= $dSign."<span id=\"state_tax-total\">".sprintf ("%01.2f", $sthisTax)."</span>\n";
   $HTML .= "</TD>\n";
   $HTML .= "</TR>\n";
}

//# State tax: Process here here if "taxwhen" option is set to charge tax AFTER shipping
//if ( $sthisTax != "" && $sthisTax != "0.00" && $taxpref->get("taxwhen") == "aftershipping" ) {
//   $HTML .= "<TR>\n";
////   $HTML .= "<TD ALIGN=LEFT VALIGN=MIDDLE class=\"text ".$sep."\">\n";
////   $HTML .= "&nbsp;\n";
////   $HTML .= "</TD>\n";
//   $HTML .= "<TD colspan=\"4\" ALIGN=RIGHT VALIGN=MIDDLE class=\"text ".$sep."\">\n";
//   $HTML .= $BSTATE." ".lang("Tax")." ".$staxrate."</B>: \n";
//   $HTML .= "</TD>\n";
//   $HTML .= "<TD ALIGN=LEFT VALIGN=MIDDLE class=\"text ".$sep."\">\n";
//
//   $sthisTax = sprintf ("%01.2f", $sthisTax);
//
//   $HTML .= $dSign."<span id=\"state_tax-total\">".$sthisTax."</span>\n";
//   $HTML .= "</TD>\n";
//   $HTML .= "</TR>\n";
//}
# Update tax total to include shipping?
# Include shipping charges in tax calculation?
//echo $taxpref->get("taxwhen")."[$cthisTax]";
//if ( $taxpref->get("taxwhen") == "aftershipping" && $cthisTax > 0) {
//   $tax_subtotal = $tax_subtotal + $SHIPPING_TOTAL;
//	$sthisTax = $ccalcTax * $tax_subtotal;
//	$taxtype = 'country';
//
//   
//   //$HTML .= " <br/>New taxed total: [".$tax_subtotal."]<br/>";
//   //$HTML .= " <br/>New tax amount: [".$taxed_shipping."]<br/>";
//
//   # Display tax total after shipping charges
//   $HTML .= " <tr>\n";
//   $HTML .= "  <td colspan=\"4\" align=\"right\" valign=\"middle\" class=\"text ".$sep."\">\n";
//
//
//	$HTML .= $BCOUNTRY." ".lang("Tax")." ".$ctaxrate."</b>: \n";
//
//   $HTML .= "  </td>\n";
//   $HTML .= "  <td align=\"left\" valign=\"middle\" class=\"text ".$sep."\">\n";
//   $sthisTax = sprintf ("%01.2f", $sthisTax);
//   $HTML .= $dSign."<span id=\"state_tax-total\">".sprintf ("%01.2f", $sthisTax)."</span>\n";
//   $HTML .= "  </td>\n";
//   $HTML .= " </tr>\n";
//}


###########################################################################################
// DISPLAY TOTAL SALE COST
###########################################################################################

//more testing
//echo "<B>SHIPPING_TOTAL >> ($SHIPPING_TOTAL)</B><BR>\n";

//$ORDER_TOTAL = $display_subtotal + $sthisTax + $cthisTax + $vatSub + $SHIPPING_TOTAL;
	
if ( $taxpref->get("taxwhen") == "aftershipping" && ($sthisTax > 0 || $cthisTax > 0)) {
	$ORDER_TOTAL = $display_subtotal + $sthisTax + $cthisTax + $vatSub + $SHIPPING_TOTAL;
} else {
	$ORDER_TOTAL = $display_subtotal + $sthisTax + $cthisTax + $vatSub + $SHIPPING_TOTAL;
}

//echo 'disp_sub'.$display_subtotal."<br/>";
//echo 'statetax'.$sthisTax."<br/>";
//echo 'countrytax'.$cthisTax."<br/>";
//echo 'vat'.$vatSub."<br/>";
//echo 'shipping'.$SHIPPING_TOTAL."<br/>";
//echo 'order'.$ORDER_TOTAL."<br/>";
//echo "ORDER_TOTAL = display_subtotal($display_subtotal) + display_tax($display_tax) + SHIPPING_TOTAL($SHIPPING_TOTAL)<BR>\n";

$ORDER_TOTAL = sprintf ("%01.2f", $ORDER_TOTAL);

//echo "FINAL ORDER_TOTAL ----->> $ORDER_TOTAL\n";

$HTML .= "<TR>\n";
$HTML .= "<TD ALIGN=LEFT VALIGN=MIDDLE class=\"text ".$sep."\">\n";
$HTML .= "<FONT SIZE=1 FACE=ARIAL></FONT>\n";
$HTML .= "</TD>\n";
#change
$HTML .= "<TD ALIGN=RIGHT VALIGN=MIDDLE class=\"text ".$sep."\" COLSPAN=3>\n";
//$ORDER_TOTAL = $ORDER_TOTAL + $discounttotal;
#change
$HTML .= "<B>".lang("Total")."</B>:\n";
$HTML .= "</TD>\n";

$HTML .= "<TD ALIGN=LEFT VALIGN=MIDDLE class=\"text ".$sep."\">\n";
$ORDER_TOTAL = sprintf ("%01.2f", $ORDER_TOTAL);
$HTML .= "<FONT COLOR=MAROON><U>".$dSign.$ORDER_TOTAL."</U></FONT>\n";
$HTML .= "</TD>\n";

$HTML .= "</TR>\n";

// Register ORDER_TOTAL with our session for databasing and payment processing
// -------------------------------------------------------------------------------------


if($ORDER_TOTAL!=''){
	$_SESSION['ORDER_TOTAL'] = $ORDER_TOTAL;
}


$HTML .= "</TABLE>\n\n";

echo $HTML;

?>
