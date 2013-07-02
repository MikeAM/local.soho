<?php

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
include_once('pull-policies.inc.php');
$result = mysql_query("SELECT * FROM cart_paypal");
$PAYPAL = mysql_fetch_array($result);

$cartpref = new userdata("cart");

// *********************************************************************
// ** SOHOLAUNCH OPEN SOURCE CODE CONTENT MANAGEMENT SYSTEM           **
// **                                                                 **
// ** Author: Mike Johnston                                           **
// **  Email: mike@soholaunch.com; mike@mikejsolutions.com            **
// **                                                                 **
// ** Portions of the overall system code are copyrighted and patented**
// ** by Soholaunch.com, Inc.  Please read and agree to all license   **
// ** agreements before modifing or utilizing this program.           **
// **                                                                 **
// *********************************************************************

	// ----------------------------------------------------
	// Start Search Column even with first sku result
	// We'll place a date stamp in there for looks, but
	// it could be an nbsp; if desired.  The date gives
	// the illusion that everything is "current".
	// However, if we are using security and the user has
	// selected to display a client login button, that will
	// over-ride normal display
	// ----------------------------------------------------




	// ----------------------------------------------------
	// Start Search Column HTML
	// ----------------------------------------------------
	$date = date("F j, Y");

   # Display Date, Login button, or Welcome text?
   echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" id=\"searchcolumn-login_or_date\">\n";
   echo " <tr>\n";
   echo "  <td style=\"padding:0px;margin:0px;\" valign=\"top\">\n";

	# PREF: Show login button?
	if ( !eregi("Y", $OPTIONS['DISPLAY_LOGINBUTTON']) ) {
	   # NO: "Welcome..." or date
		if ( isset($_SESSION['SOHO_AUTH']) && isset($_SESSION['SOHO_PW']) && isset($_SESSION['OWNER_NAME']) ) {
			$dis = "".lang("Welcome").", <BR><B>".$OWNER_NAME."</B>";
			$dis .= "(<a href=\"../pgm-secure_login.php?todo=logout&backto=".base64_encode($_SERVER['FULL_URL'])."\">Log-out</a>)\n";
		} else {
			$dis = $date;
			$dis = "&nbsp;";
		}

		echo "   ".$dis."\n";

	} else {
		# YES: "Welcome..." or [Client Login]
		if ( isset($_SESSION['SOHO_AUTH']) && isset($_SESSION['SOHO_PW']) && isset($_SESSION['OWNER_NAME']) ) {
		   # "Welcome..."
			echo "".lang("Welcome").", <br/><b>".$OWNER_NAME."</b>\n";
			echo "(<a href=\"../pgm-secure_login.php?todo=logout&backto=".base64_encode($_SERVER['FULL_URL'])."\">".lang("Log-out")."</a>)\n";

		} else {
		   # [Client Login]
			echo "<form name=\"LOGINBUT\" method=\"post\" action=\"../pgm-secure_login.php\">\n";
			echo "<input type=\"hidden\" name=\"sc\" value=1>\n";
			echo "<input TYPE=\"SUBMIT\" value=\"".lang("Client Login")."\" class=\"FormLt1\" style='height: 19px; font-size: 7pt;'></form>\n";
		}

	} // End Display Login Button

   echo "  </td>\n";
   echo " </tr>\n";
   echo "</table>\n\n";


	echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" align=\"center\" width=\"100%\" class=\"shopping-selfcontained_box\" id=\"seach-column-main\">\n";

	// ----------------------------------------------------
	// Is the Search Box Display On or Off
	// ----------------------------------------------------

	if (eregi("Y", $OPTIONS[DISPLAY_SEARCH])) {


		echo " <tr>\n";
		echo "  <th>\n";
		echo "   <form action=\"start.php\" method=\"post\">\n";
		echo "   <input type=\"hidden\" name=\"find\" value=\"1\">\n";
		echo "   ".lang("Search Products")."<br/>\n";
		echo "   <input type=\"text\" class=\"text\" style='width: 140px;' name=\"searchfor\" size=\"15\">\n";
		echo "   <img src=\"spacer.gif\" border=\"0\" width=\"140\" HEIGHT=\"2\"><br/><input type=\"submit\" value=\"".lang("Find Now")."\" class=\"FormLt1\">\n";
		echo "  </th>\n";
		echo " </tr>\n";

		echo " <tr>\n";
		echo "  <th align=\"center\" valign=\"middle\">\n";
		echo "   </form>&nbsp;";
		echo "  </th>\n";
		echo " </tr>\n";

	} // End Display Search Option

	// ----------------------------------------------------
	// Is the Cateogry Links Display On or Off
	// ----------------------------------------------------

	if (eregi("Y", $OPTIONS[DISPLAY_CATEGORIES])) {
		if(!is_array($catz)){
			$getcats = mysql_query("SELECT * FROM cart_category ORDER BY category");
			while ($rowz = mysql_fetch_array ($getcats)) {
				if (strlen($rowz['category']) > 2) {
					$catz[$rowz['keyfield']]= array('name'=>$rowz['category'], 'level'=>$rowz['level'], 'subs'=>$rowz['subs'], 'parent'=>$rowz['parent'], 'product_count'=>$rowz['product_count']);
				}
			}
		}



		echo "<tr>\n";
		echo " <th class=\"browse_categories\">\n";
		echo "<a class=\"browse_categories\" href=\"start.php\" style=\"color:inherit;\">".lang("Browse Categories")."</a><br/>\n";
		echo " </th>\n";
		echo "</tr>\n";

		echo "<tr>\n";
		echo " <td class=\"category_list\">\n";
		if($_REQUEST['cat'] != ''){
			$cat = $_REQUEST['cat'];
		} else {
			if($_REQUEST['id'] != ''){
				$fnd_ary = mysql_query("select PROD_CATEGORY1 from cart_products where PRIKEY='".$_REQUEST['id']."'");
				$getct = mysql_fetch_assoc($fnd_ary);
				$cat = $getct['PROD_CATEGORY1'];	
			}
		}
		
		foreach($catz as $cvar=>$cval){
			if($catz[$cvar]['level'] == '1' && $cval['product_count'] > 0){
				echo " &nbsp;<a href=\"start.php?browse=1&cat=".$cvar."&=SID\">".$cval['name']."&nbsp;(".$cval['product_count'].")</a><br/>\n";
				if($cval['subs']!=''){
					$thesubs = explode(',',$cval['subs']);
					foreach($thesubs as $sbval){						
						if(($cat == $cvar || $cat == $sbval || $sbval == $catz[$cat]['parent']) && $catz[$sbval]['product_count'] > 0){
							echo " &nbsp;&nbsp;&nbsp;&gt;&nbsp;<a href=\"start.php?browse=1&cat=".$sbval."&=SID\">".$catz[$sbval]['name']."&nbsp;(".$catz[$sbval]['product_count'].")</a><br/>\n";
							if($catz[$sbval]['subs'] != ''){
								$subsubz = explode(',', $catz[$sbval]['subs']);
								foreach($subsubz as $subsubval){
									if($catz[$cat]['level'] > 1 && $catz[$subsubval]['product_count'] > 0 && ($catz[$subsubval]['parent'] == $cat || $cat == $subsubval)){
										echo " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&gt;&gt;&nbsp;<a href=\"start.php?browse=1&cat=".$subsubval."&=SID\">".$catz[$subsubval]['name']."&nbsp;(".$catz[$subsubval]['product_count'].")</a><br/>\n";
									}
								}
							}
						}
						

					}
				}
			}
		}

//		$catlist = mysql_query("SELECT * FROM cart_category where level='1' ORDER BY category");
//		while ($thiscat = mysql_fetch_array($catlist)) {
//			if (strlen($thiscat['category']) > 2) {	// Category names should be more than 2 chars long
//				echo " &nbsp;<a href=\"start.php?browse=1&cat=".$thiscat['keyfield']."&=SID\">".$thiscat['category']."</a><br/>\n";
//				
//				if($catz[$_REQUEST['cat']]['parent'] == $thiscat['keyfield']){
//					echo " &nbsp;&nbsp;&gt;&nbsp;<a href=\"start.php?browse=1&cat=".$_REQUEST['cat']."&=SID\">".$catz[$_REQUEST['cat']]['name']."</a><br/>\n";					
//				}
//				
//				if($_REQUEST['cat'] == $thiscat['keyfield'] && $catz[$_REQUEST['cat']]['subs'] != ''){
//					$thiscatsubs = explode(',', $catz[$_REQUEST['cat']]['subs']);
//					foreach($thiscatsubs as $thiscatval){
//						echo " &nbsp;&nbsp;&gt;&nbsp;<a href=\"start.php?browse=1&cat=".$thiscatval."&=SID\">".$catz[$thiscatval]['name']."</a><br/>\n";
//					}
//				}
//				
//				
//			}
//		}

		echo " </td>\n";
		echo "</tr>\n";

		echo "<tr>\n";
		echo " <td align=\"center\" valign=\"middle\">\n";
		echo " &nbsp;";
		echo " </td>\n";
		echo "</tr>\n";

	}

	// ----------------------------------------------------
	// Is the Catalog Only feature On or Off
	// ----------------------------------------------------

	if (!eregi("Y", $OPTIONS[PAYMENT_CATALOG_ONLY])) {


	?>

	<!-- #################################################################### -->
	<!-- CURRENT SHOPPING CART -->
	<!-- #################################################################### -->

	<?

		echo "<tr>\n";
		echo " <th align=\"center\" valign=\"middle\" bgcolor=\"#".$OPTIONS[DISPLAY_HEADERBG]."\">\n";
		echo "  ".lang("Shopping Cart")."<br/>\n";
		echo " </th>\n";
		echo "</tr>\n";

		echo "<tr>\n";
		echo " <td id=\"searchcolumn-items_in_cart\">\n";

		if ($CART_KEYID == "") {
			echo "<br/><div align=\"center\">".lang("Your cart is empty.")."</div><br/>\n";

		} else {

			$tmp_qty = split(";", $CART_QTY);
			$tmp_name = split(";", $CART_PRODNAME);
			$tmp_price = split(";", $CART_UNITPRICE);
			$tmp_sub = split(";", $CART_UNITSUBTOTAL);
			$tmp_subcat = split(";", $CART_SUBCAT);

			$display_subtotal = 0;			// Reset Display sub_total; we'll calculate that on the fly
			$line_items = count($tmp_qty);	// Count the number of array vars we have after split
			$line_items = $line_items - 2;	// Subtract 2 because (a)we start count at 0 (b) we always have a trailing semi-colon;

			for ($z=0;$z<=$line_items;$z++) {

				if ($tmp_subcat[$z] != "") { $tmp_subcat[$z] = "- $tmp_subcat[$z]"; }
				echo "&nbsp;($tmp_qty[$z]) $tmp_name[$z] $tmp_subcat[$z]<BR>\n";
				$display_subtotal = $display_subtotal + $tmp_sub[$z];

			}

			$display_subtotal = number_format($display_subtotal, 2 );
			echo "<BR>".lang("Sub-Total").": <U>".$dSign."$display_subtotal</U><BR><BR>\n";

   		# Got to https url for checkout?
   		if (strlen($OPTIONS[PAYMENT_SSL]) > 4) {
   			$OPTIONS[PAYMENT_SSL] = chop($OPTIONS[PAYMENT_SSL]);
   			$OPTIONS[PAYMENT_SSL] = ltrim($OPTIONS[PAYMENT_SSL]);
   			$OPTIONS[PAYMENT_SSL] = rtrim($OPTIONS[PAYMENT_SSL]);
   			$SSL_VARIABLE = $OPTIONS[PAYMENT_SSL] . "/shopping/";
   			$SSL_CHECKOUT_LINK = $SSL_VARIABLE."pgm-checkout.php?sid=".session_id();

   		} else {
   			$SSL_VARIABLE = "";
   			$SSL_CHECKOUT_LINK = "pgm-checkout.php";
   		}

			echo "<DIV ALIGN=CENTER>[ <A HREF=\"pgm-add_cart.php?ACTION=view&=SID\">".lang("VIEW OR EDIT CART")."</A> ]\n";

			$pass_prod = eregi_replace("\"", "#Q#", $CART_PRODNAME); // Make sure quotes get passed to SSL

			# ( (->) Checkout ]
			$checkout_button = "<FORM METHOD=POST ACTION=\"".$SSL_CHECKOUT_LINK."\">\n";
			$checkout_button .= "<INPUT TYPE=HIDDEN NAME=CART_KEYID VALUE=\"$CART_KEYID\">\n";
			$checkout_button .= "<INPUT TYPE=HIDDEN NAME=CART_SKUNO VALUE=\"$CART_SKUNO\">\n";
			$checkout_button .= "<INPUT TYPE=HIDDEN NAME=CART_CATNO VALUE=\"$CART_CATNO\">\n";
			$checkout_button .= "<INPUT TYPE=HIDDEN NAME=CART_PRODNAME VALUE=\"$pass_prod\">\n";
			$checkout_button .= "<INPUT TYPE=HIDDEN NAME=CART_SUBCAT VALUE=\"$CART_SUBCAT\">\n";
			$checkout_button .= "<INPUT type=\"hidden\" name=\"CART_FORMDATA\" value=\"".$_SESSION['CART_FORMDATA']."\">\n";
			$checkout_button .= "<INPUT TYPE=HIDDEN NAME=CART_VARNAME VALUE=\"$CART_VARNAME\">\n";
			$checkout_button .= "<INPUT TYPE=HIDDEN NAME=CART_UNITPRICE VALUE=\"$CART_UNITPRICE\">\n";
			$checkout_button .= "<INPUT TYPE=HIDDEN NAME=CART_QTY VALUE=\"$CART_QTY\">\n";
			$checkout_button .= "<INPUT TYPE=HIDDEN NAME=CART_UNITSUBTOTAL VALUE=\"$CART_UNITSUBTOTAL\">\n";
			$checkout_button .= "<INPUT TYPE=HIDDEN NAME=WIN_FULL_PATH VALUE=\"$WIN_FULL_PATH\">\n";
			$checkout_button .= "<INPUT TYPE=IMAGE SRC=\"checkout_button.gif\" WIDTH=106 HEIGHT=25 ALIGN=ABSMIDDLE BORDER=0 STYLE='cursor: hand;'>\n";
			$checkout_button .= "</FORM>\n";

			echo $checkout_button;

			echo "</DIV>\n";

		} // End else-if $CART_KEYID != ""

		echo "</TD>\n";
		echo "</TR>\n";

	} // End Catalog Ony Test

	// --------------------------------------------------------------
	// If Business Phone Number Has been Given for telephone orders
	// --------------------------------------------------------------

	if ($OPTIONS['BIZ_PHONE'] != "") {


	?>

	<!-- #################################################################### -->
	<!-- DISPLAY TELEPHONE NUMBER -->
	<!-- #################################################################### -->

	<?


		echo "<tr>\n";
		echo " <td align=\"center\" valign=\"middle\">\n";
		echo " &nbsp;";
		echo " </td>\n";
		echo "</tr>\n";

		echo "<tr>\n";
		echo " <th align=\"center\" valign=\"middle\" bgcolor=\"#".$OPTIONS['DISPLAY_HEADERBG']."\">\n";
		echo "  ".lang("Telephone Orders")."<br/>\n";
		echo " </th>\n";
		echo "</tr>\n";

		echo "<tr>\n";
		echo " <td align=\"center\" valign=\"middle\" class=\"text\">\n";
		echo "  <b>".$OPTIONS['BIZ_PHONE']."</b>\n";
		echo " </td>\n";
		echo "</tr>\n";

	}

	// -----------------------------------------------------------
	// Display Credit Card Images if Selected in Payment Options
	// -----------------------------------------------------------

	if ($OPTIONS['PAYMENT_CREDIT_CARDS'] != "" && !eregi("Y", $OPTIONS['PAYMENT_CATALOG_ONLY']) && !eregi("Y", $OPTIONS['PAYMENT_CHECK_ONLY'])) {

	?>

	<!-- #################################################################### -->
	<!-- CREDIT CARD DISPLAY -->
	<!-- #################################################################### -->

	<?

		$CARDNAME = split(";", $OPTIONS['PAYMENT_CREDIT_CARDS']);	// Split Field into individual card names
		$NUMCARDS = count($CARDNAME);						// How many cards are accepted?
		$NUMCARDS--;								// Subtract 1 from total because we start at zero

		$tmp_rowcount = 0;							// We only want to place two images on a row

		echo "<TR>\n";
		echo "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=text><BR>".lang("We Accept").":<BR>\n";


		for ($z=0;$z<=$NUMCARDS;$z++) {

			if ($CARDNAME[$z] != "") {					// Make sure we don't count blanks

				$THISCARD = strtolower($CARDNAME[$z]);		// Make cardname lower case (match filename)

				echo "<IMG SRC='".$THISCARD.".gif' HSPACE=2 VSPACE=2 BORDER=0 ALIGN=ABSMIDDLE>";

				$tmp_rowcount++;						// Increment Row Count

				if ($tmp_rowcount == 2) {
					echo "<BR CLEAR=ALL>"; 				// If two images are on row, place a <BR> tag
					$tmp_rowcount = 0; 				// Reset Row Count
				}

			} // End Blank If

		} // End For Loop


		// -------------------------------------------------------------------------------------
		// If we're taking credit cards through VeriSign(tm), then lets display the approved
		// VeriSign(tm) Image to our shoppers.  Again, a sign that employees vendor trust.
		// -------------------------------------------------------------------------------------
      
		if ($OPTIONS['PAYMENT_VLOGINID'] != "" && $OPTIONS['PAYMENT_VPARTNERID'] != "" && eregi("verisign", $OPTIONS['PAYMENT_PROCESSING_TYPE'])) {
			echo "<BR><A HREF=\"http://www.verisign.com\" target=\"_blank\"><IMG SRC='paypal_logo.gif' WIDTH=86 HEIGHT=36 BORDER=0 HSPACE=2 VSPACE=2></A>\n";
		}

		if (eregi("use_paypal",$OPTIONS['PAYMENT_PROCESSING_TYPE'])) {
			echo "<br/><a href=\"http://www.paypal.com\" target=\"_blank\"><img src='paypal_logo.gif' border=\"0\"></a><br/>\n";
		} else {
			echo "<br>";	// For proper display formating (I like it, you may not)
		}


		echo "</TD>\n";
		echo "</TR>\n";

		if(!eregi("Y",$OPTIONS['PAYMENT_CHECK_ONLY']) && eregi("use_check",$OPTIONS['PAYMENT_PROCESSING_TYPE'])){
			echo "<TR>\n";
			echo "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=smtext>\n";
			if ( $cartpref->get("checkorcheque") == "" ) { $cartpref->set("checkorcheque", "check"); }
			echo "<IMG SRC='pay-".$cartpref->get("checkorcheque").".gif' HSPACE=2 VSPACE=2 BORDER=0 ALIGN=ABSMIDDLE>";
			echo "</TD></TR>\n";
		}

	} else {

		// ---------------------------------------------------------------------
		// If no credit cards are being taken because of "Catalog Only" option
		// or check/money order only option, then process visual display here
		// ---------------------------------------------------------------------

		if (eregi("Y", $OPTIONS['PAYMENT_CATALOG_ONLY'])) {

		?>

		<!-- #################################################################### -->
		<!-- CATALOG ONLY DISPLAY -->
		<!-- #################################################################### -->

		<?


			echo "<TR>\n";
			echo "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=smtext>\n";
			echo "&nbsp;\n";
			echo "</TD></TR>\n";

			echo "<TR>\n";
			echo "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=smtext BGCOLOR=#EFEFEF>\n";
			echo lang("We are currently not accepting online orders.")."\n";
			echo "</TD></TR>\n";

			echo "<TR>\n";
			echo "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=smtext>\n";
			echo "&nbsp;\n";
			echo "</TD></TR>\n";

		} // End if Catalog Only

		if (eregi("Y", $OPTIONS['PAYMENT_CHECK_ONLY'])) {

		?>

		<!-- #################################################################### -->
		<!-- CHECK/MONEY ORDER ONLY DISPLAY -->
		<!-- #################################################################### -->

		<?
          // Check/Money Order Only
			echo "<TR>\n";
			echo "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=smtext>\n";
			echo "&nbsp;\n";
			echo "</TD></TR>\n";

			echo "<TR>\n";
			echo "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=smtext>\n";
			if ( $cartpref->get("checkorcheque") == "" ) { $cartpref->set("checkorcheque", "check"); }
			echo "<IMG SRC='pay-".$cartpref->get("checkorcheque").".gif' HSPACE=2 VSPACE=2 BORDER=0 ALIGN=ABSMIDDLE><br/>";
			echo lang("We are currently only accepting check or money orders online.")."\n";
			echo "</TD></TR>\n";

//			echo "<TR>\n";
//			echo "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=smtext BGCOLOR=#EFEFEF>\n";
//			echo "<IMG SRC='pay-check.gif' HSPACE=2 VSPACE=2 BORDER=0 ALIGN=ABSMIDDLE><br>";
//			echo lang("We are currently only accepting check or money orders online.")."\n";
//			echo "</TD></TR>\n";

			echo "<TR>\n";
			echo "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=smtext>\n";
			echo "&nbsp;\n";
			echo "</TD></TR>\n";

		}else{
		   
         // No credit card displays, not catalog display, not check only
   		echo "<TR>\n";
   		echo "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=text>\n";

   		// Verisign
   		if ($OPTIONS['PAYMENT_VLOGINID'] != "" && $OPTIONS['PAYMENT_VPARTNERID'] != "" && eregi("verisign", $OPTIONS['PAYMENT_PROCESSING_TYPE'])) {
   		   echo "<BR><A HREF=\"http://www.verisign.com\" target=\"_blank\"><IMG SRC='verisign.gif' WIDTH=86 HEIGHT=36 BORDER=0 HSPACE=2 VSPACE=2></A>\n";
   		}

         // PayPal   
   		if (eregi("use_paypal",$OPTIONS[PAYMENT_PROCESSING_TYPE])) {
   			echo "<br/><a href=\"http://www.paypal.com\" target=\"_blank\"><img src='paypal_logo.gif' border=\"0\"></a><br/>\n";
   		} else {
   			echo "<br>";	// For proper display formating (I like it, you may not)
   		}
   		
   		echo "</TD>\n";
   		echo "</TR>\n";
   
   		if(!eregi("Y",$OPTIONS['PAYMENT_CHECK_ONLY']) && eregi("use_check",$OPTIONS['PAYMENT_PROCESSING_TYPE'])){
   			echo "<TR>\n";
   			echo "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=smtext>\n";
   			if ( $cartpref->get("checkorcheque") == "" ) { $cartpref->set("checkorcheque", "check"); }
   			echo "<IMG SRC='pay-".$cartpref->get("checkorcheque").".gif' HSPACE=2 VSPACE=2 BORDER=0 ALIGN=ABSMIDDLE>";
   			echo "</TD></TR>\n";
   		}
		}

	} // End Credit Cards

	// -------------------------------------------------------------------
	// Display Links to Shipping, Privacy and other policies regardless
	// unless "Catalog Only" if selected in Payment Options
	// -------------------------------------------------------------------


	?>

	<!-- #################################################################### -->
	<!-- DISPLAY POLICY LINKS -->
	<!-- #################################################################### -->

	<?

	echo "<TR>\n";
	echo "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=smtext>\n";
	if ( $privacy_policy_definedBool ) {
		echo "<A HREF=\"start.php?policy=privacy&=SID\">".lang("Privacy Policy")."</a>";
	}
	echo "</TD></TR><TR>\n";

	if (!eregi("Y", $OPTIONS[PAYMENT_CATALOG_ONLY])) {	// Only display shipping/returns if using cc processing
		echo "<TR>\n";
		echo "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=smtext>\n";
		if ( $cartpref->get("disable_shipping") != "yes" ) {
			if ( $shipping_policy_definedBool ) {
				echo "<A HREF=\"start.php?policy=shipping&=SID\">".lang("Shipping Information")."</a>";				
			}
		}
		echo "</TD></TR><TR>\n";
		
		if ( $return_policy_definedBool ) {
			echo "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=smtext>\n";
			echo "<A HREF=\"start.php?policy=returns&=SID\">".lang("Returns & Exchanges")."</a>";
			echo "</TD></TR>\n";
		}

	}

	// ------------------------------------------------------------------
	// Only display "other policies" link if text exists for the policy
	// ------------------------------------------------------------------

	if ( $other_policy_definedBool ) {
		echo "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=smtext>\n";
		echo "<A HREF=\"start.php?policy=other&=SID\">".$cartpref->get("other_policy_title")."</a>";
		echo "</TD>\n";
		echo "</TR>\n";
	}

	// ------------------------------------------------------------------

	echo "</TABLE>\n\n";

?>


<!-- #################################################################### -->
<!-- END SEARCH COLUMN DISPLAY -->
<!-- #################################################################### -->

