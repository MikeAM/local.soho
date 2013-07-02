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

#############################################
### FORMAT DATABASE VARIABLES FOR DISPLAY
#############################################

	$FORM_NAME = eregi_replace(" ", "", $PROD[PROD_SKU]);		// Must give each sku a FORM tag for Netscape

	// ----------------------------------------------------------------------------
	// Remember, The product description is is managed via a TEXTAREA.  Therefore,
	// we need to trim all extra spaces and CR/LF from the data and then replace
	// them with <BR> tags for proper display.
	// ----------------------------------------------------------------------------

	$PROD[PROD_DESC] = rtrim($PROD[PROD_DESC]);
	$PROD[PROD_DESC] = chop($PROD[PROD_DESC]);
	$PROD[PROD_DESC] = eregi_replace("\n", "<BR>", $PROD[PROD_DESC]);
	$PROD[PROD_DESC] = eregi_replace("&quot;", "\"", $PROD[PROD_DESC]);

   # Restore price variation arrays
   $PROD['sub_cats'] = unserialize($PROD['sub_cats']);
   $PROD['variant_names'] = unserialize($PROD['variant_names']);
   $PROD['variant_prices'] = unserialize($PROD['variant_prices']);

   # Restore cart preference data
   $cartpref = new userdata("cart");

?>

<!-- #################################################################### -->
<!-- BEGIN PRODUCT DISPLAY  -->
<!-- #################################################################### -->

<FORM NAME="<? echo $FORM_NAME; ?>">

  <!-- Main Table Width 98% so we dont run close to the search column and look
       like hell. -->
<?php
echo " <table width=\"98%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\" class=\"shopping-selfcontained_box\">\n";
echo "  <tr> \n";
echo "   <th>".$PROD[PROD_NAME]."</th>\n";
echo "  </tr>\n";
echo "  <tr> \n";
echo "   <td> \n";
echo "    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">\n";
echo "     <tr>\n";
?>
<?php

			// -------------------------------------------------------------------------------
			// If a thumbnail is present, use it, otherwise, scale the fullimage to a width
			// of 150px width max.  This keeps our cool display consistant.
			// -------------------------------------------------------------------------------

			$THIS_IMAGE = "";

			if (strlen($PROD[PROD_THUMBNAIL]) > 2) {

				if ( !eregi('http://', $PROD['PROD_THUMBNAIL']) ) { // Allow for external images, v4.9.3 r36
					$THIS_IMAGE = "../images/$PROD[PROD_THUMBNAIL]";
					$TEST_IMAGE = "$doc_root/images/$PROD[PROD_THUMBNAIL]";
				} else {
					$THIS_IMAGE = $PROD['PROD_THUMBNAIL'];
					$TEST_IMAGE = $PROD['PROD_THUMBNAIL'];
				}
				
				
//				if ( $_SERVER['REMOTE_ADDR'] == '70.89.253.74' ) { echo '['.$THIS_IMAGE.']'; exit; }

			} else {

				if ( !eregi('http://', $PROD['PROD_FULLIMAGENAME']) ) { // Allow for external images, v4.9.3 r36
					$THIS_IMAGE = "../images/$PROD[PROD_FULLIMAGENAME]";
					$TEST_IMAGE = $doc_root."/images/$PROD[PROD_FULLIMAGENAME]";
				} else {
					$THIS_IMAGE = "$PROD[PROD_FULLIMAGENAME]";
					$TEST_IMAGE = "$PROD[PROD_FULLIMAGENAME]";
				}

//				if ( $_SERVER['REMOTE_ADDR'] == '70.89.253.74' ) { echo '['.$THIS_IMAGE.']'; exit; }

			}

			$WH = "";	// Set width/height var to nothing

			if (file_exists("$TEST_IMAGE")) {

				$tempArray = getImageSize($TEST_IMAGE);
				$origW = $tempArray[0];
				$origH = $tempArray[1];
				$oW = $origW;			// Set new W/H to real image size
				$oH = $origH;

				$WH = " width=".$origW." height=".$origH;

				if ( $origW > 114 ) {		// If width > 114px; scale to 114px proportionally
					$calc = 114 / $origW;
					$hcalc = $origH * $calc;
					$nheight = round($hcalc);
					$WH = "width=\"115\" height=\"".$nheight."\"";
				}

				//$THIS_IMAGE .= $WH;		// Add W/H calculation to image tag
			}

			// -------------------------------------------------------------------------------
			// If the imagename listed in the database is not there, dont show an image
			// -------------------------------------------------------------------------------

//			if (!file_exists($TEST_IMAGE) || $THIS_IMAGE == "../images/") {
//				$THIS_IMAGE = "\"../spacer.gif\"";
//			}

			// -------------------------------------------------------------------------------
			// Define the "text" within the button below the image only if NOT catalog only
			// -------------------------------------------------------------------------------


			if (!eregi("Y", $OPTIONS[PAYMENT_CATALOG_ONLY])) {

				$button_value = lang("Buy Now")."!";

				# "Buy Now" or "Add to Cart" or [Custom]?
				if (eregi("Y", $OPTIONS['DISPLAY_ADDCARTBUTTON'])) {
					$button_value = lang("Add to Cart")."";
				} elseif ( eregi("C", $OPTIONS['DISPLAY_ADDCARTBUTTON'])) {
				   $button_value = $cartpref->get("custom_addcartbutton");
				}

			} else {

				$button_value = lang("Details");

			}


         echo '<TD ALIGN="CENTER" VALIGN="TOP" WIDTH="150">';

         # Compiling for email to friend?
         if ( basename($_SERVER['PHP_SELF']) == "pgm-email_friend.php" ) {
            # YES - Absolute link on image and button ($link_to_sku defined in parent file)
            echo "<a href=\"".$link_to_sku."\"><img src=\"".$THIS_IMAGE."\"".$WH."vspace=\"2\" hspace=\"5\" alt=\"".$PROD['PROD_NAME']."\" border=\"0\"></a><br/>";
            echo "[ <a href=\"".$link_to_sku."\">Buy Now</a> ]";
         } else {
            # NO - Standard javascript action on image and button
            echo "<A HREF=\"#\" onclick=\"userbutton('".$PROD['PRIKEY']."');\"><img src=\"".$THIS_IMAGE."\"".$WH."VSPACE=\"2\" HSPACE=\"5\" ALT=\"".$PROD[PROD_NAME]."\" BORDER=\"0\"></A><BR>";
            echo "<INPUT TYPE=\"BUTTON\" VALUE=\"".$button_value."\" class=\"text\" style=\"cursor: hand;\" onclick=\"userbutton('".$PROD[PRIKEY]."');\">";
         }

         echo '<BR>';

			// -----------------------------------------------------
			// Calculate Pricing to Display (Variants)
			// We are assuming here that price variant 6 will be
			// the highest dollar amount in the listing
			// -----------------------------------------------------

		$qtydiscq = mysql_query("select * from qty_discounts where sku='".$PROD['PROD_SKU']."'");
		$qtyarray = mysql_fetch_array($qtydiscq);
		$disc = unserialize($qtyarray['disc']);
		$lastval = '';

		if($qtyarray['disc_type'] == '%'){
			foreach($disc as $lastval){
				$lastval = $lastval * .01;
			}
		} else {
			foreach($disc as $lastval){
				
			}
		}

			//Pull currency info
			$dSign = $OPTIONS[PAYMENT_CURRENCY_SIGN];
			$dType = $OPTIONS[PAYMENT_CURRENCY_TYPE];

			if($qtyarray['disc_type'] == '%'){
				$PRICE_DISPLAY = "$dSign" . sprintf ("%01.2f", $PROD[PROD_UNITPRICE] - ($PROD[PROD_UNITPRICE] * $lastval));
				$END_PRICE = "";
			} else {
				$PRICE_DISPLAY = "$dSign" . sprintf ("%01.2f", ($PROD[PROD_UNITPRICE] - $lastval));
				$END_PRICE = "";
			}
			if($qtyarray['disc_type'] != ''){
				if(sprintf("%01.2f", $PROD[PROD_UNITPRICE]) > sprintf("%01.2f", ($PROD[PROD_UNITPRICE] - $lastval))){
					$END_PRICE = " - ".$dSign.$PROD[PROD_UNITPRICE];
				}
			}

//			if ($PROD[VARIANT_PRICE1] != "") {
//				$PRICE_DISPLAY = "$dSign" . $PROD[VARIANT_PRICE1];
//			}
//
//			if ($PROD[VARIANT_PRICE2] != "") {
//				$END_PRICE = " - $dSign" . $PROD[VARIANT_PRICE2];
//			}
//
//			if ($PROD[VARIANT_PRICE3] != "") {
//				$END_PRICE = " - $dSign" . $PROD[VARIANT_PRICE3];
//			}
//
//			if ($PROD[VARIANT_PRICE4] != "") {
//				$END_PRICE = " - $dSign" . $PROD[VARIANT_PRICE4];
//			}
//
//			if ($PROD[VARIANT_PRICE5] != "") {
//				$END_PRICE = " - $dSign" . $PROD[VARIANT_PRICE5];
//			}
//
//			if ($PROD[VARIANT_PRICE6] != "") {
//				$END_PRICE = " - $dSign" . $PROD[VARIANT_PRICE6];
//			}

			# Newschool -- work from variation arrays
			if ( $PROD['variant_prices'][1] != "" ) {
				if($qtyarray['disc_type'] == '%'){
			  	$PRICE_DISPLAY = $dSign . sprintf ("%01.2f", $PROD['variant_prices'][1] - ($PROD['variant_prices'][1] * $lastval));
			  } else {
			  	$PRICE_DISPLAY = $dSign . sprintf ("%01.2f", ($PROD['variant_prices'][1] - $lastval));
			  }
			}

			for ( $v = 2; $v <= $PROD['num_variants']; $v++ ) {
			   if ( $PROD['variant_prices'][$v] != "" && sprintf("%01.2f", $PROD['variant_prices'][1]) != sprintf("%01.2f", ($PROD['variant_prices'][1] - $lastval))) {
			   		if($qtyarray['disc_type'] == '%' ){
			   			$END_PRICE = " - ".$dSign.sprintf("%01.2f", $PROD['variant_prices'][1]);
			   		} else {
			      	$END_PRICE = " - ".$dSign.sprintf ("%01.2f",$PROD['variant_prices'][$v]);
			    	}
			   }
			}

			if ($END_PRICE != "") { $PRICE_DISPLAY .= $END_PRICE; }

		?>


              <span class="price_caption"><? echo $PRICE_DISPLAY; ?></span></TD>
            <TD ALIGN="LEFT" VALIGN="TOP"><? echo $PROD[PROD_DESC]; ?><BR>
              &nbsp;&nbsp;&nbsp; <BR>
              <TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="4" CLASS=text>
                <TR>
                  <TD ALIGN="CENTER" VALIGN="MIDDLE">

				<?

					// ----------------------------------------------------------------
					// Only display the more information link if there is a product
					// detail page attached to this sku or if there are customer
					// comments for this sku.  Then, Only display "Email to Friend"
					// if this option has been turned on (Turned On by Default)
					// ----------------------------------------------------------------

					if ( ($PROD[OPTION_DETAILPAGE] != "" || $cartpref->get('more-info-link') == 'on') && $cartpref->get('more-info-link') != 'off' ) {
						echo "[ <A HREF=\"pgm-more_information.php?id=$PROD[PRIKEY]&=SID#MOREINFO\">".lang("More Information")."</A> ] \n";
					}

					// ----------------------------------------------------------------
					// Only place spacing HTML code if both options are displayed.
					// This way, if only one is displayed, it will be centered.
					// ----------------------------------------------------------------

					if ($PROD[OPTION_DETAILPAGE] != "" && $OPTIONS[DISPLAY_EMAILFRIEND] == "Y") {
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
					}

//					if ($OPTIONS[DISPLAY_EMAILFRIEND] == "Y") {
//						echo "[ <A HREF=\"pgm-email_friend.php?id=$PROD[PRIKEY]&=SID\">".lang("Email To A Friend")."</A> ]</TD>\n";
//					}
				?>

                </TR>
                <TR>
                  <TD ALIGN="CENTER" VALIGN="MIDDLE">

			<?

				// ----------------------------------------------------------------------
				// If the "User Defined" button contains a text label, then display it
				// and link it to the more information page.
				// ----------------------------------------------------------------------
            
				if ($OPTIONS['DISPLAY_USERBUTTON'] != "" && basename($_SERVER['PHP_SELF']) != "pgm-email_friend.php" ) {
					echo "<INPUT TYPE=\"BUTTON\" VALUE=\"$OPTIONS[DISPLAY_USERBUTTON]\" CLASS=text STYLE='cursor: hand;' onclick=\"userbutton('$PROD[PRIKEY]');\">\n";
				} else {
					echo "&nbsp;";
				}

			?>

                  </TD>
                </TR>
              </TABLE>


		<?

			// -------------------------------------------------------------------------------------
			// If other skus have been recommended (You have the option to relate other skus with
			// this product) display a link to those skus now.  This will also happen in the other
			// information page and actually show the thumnail images as well (but not here)
			// -------------------------------------------------------------------------------------

			if ($PROD[OPTION_RECOMMENDSKU] != "") {

				$other_skus = split(",", $PROD[OPTION_RECOMMENDSKU]);
				$other_skus_count = count($other_skus);

				// ----------------------------------------------------------------------------------
				// ADD SECURITY CODE [GROUPS] CONTROL OVER SEARCH RESULTS
				// ----------------------------------------------------------------------------------
				if (isset($GROUPS)) {
					$grp_check = " HAVING OPTION_SECURITYCODE IN (";

					$grp_tmp = split(";", $GROUPS);	// Split this user's sec code groups into individual array
					$grp_cnt = count($grp_tmp);		// How Many sec groups does this user have access to?

					for ($gl=0;$gl<=$grp_cnt;$gl++) {	// Start to build SQL "IN" cluster
						if ($grp_tmp[$gl] != "") {
							$grp_check .= "'$grp_tmp[$gl]', ";
						}
					}

					$grp_check .= "'Public')";

				} else {

					$grp_check = " HAVING OPTION_SECURITYCODE IN ('Public')";

				}


            echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\" class=\"shopping-selfcontained_box related_products\">\n";
            echo " <tr>\n";
            echo "  <th align=\"left\" valign=\"middle\">\n";
            echo "   ".lang("Related Products").":\n";
            echo "  </th>\n";
            echo " </tr>\n";
            echo " <tr>\n";
            echo "   <td align=\"left\" valign=\"middle\" bgcolor=\"#EFEFEF\">\n";

				for ($z=0;$z<=$other_skus_count;$z++) {

					if ($other_skus[$z] != "") {

						$other_skus[$z] = ltrim($other_skus[$z]);
						$other_skus[$z] = rtrim($other_skus[$z]);

						$tsku = mysql_query("SELECT PRIKEY, PROD_NAME, OPTION_SECURITYCODE FROM cart_products WHERE PROD_SKU = '$other_skus[$z]' $grp_check");
						$avail_check = mysql_num_rows($tsku);

						if ($avail_check > 0) {
							$skuname = mysql_fetch_array($tsku);
							echo "&gt;&nbsp;<A HREF=\"pgm-more_information.php?id=$skuname[PRIKEY]&=SID\">$skuname[PROD_NAME]</A><BR>";
						}

					}

				} // End For Loop

				echo '
		                  </TD>
		                </TR>
		              </TABLE>
				';

			} // End if Recommend Additional Skus is selected

		?>



            </TD>
          </TR>
        </TABLE>
      </TD>
    </TR>
    <TR>
      <TD>

	<?

		// ------------------------------------------------------------
		// Only display the "Catalog Ref Number" if one is available
		// ------------------------------------------------------------

		if ($PROD[PROD_CATNO] != "") {
			$THIS_CAT_NO = "<U>".lang("Catalog")."</U>: " . strtoupper($PROD[PROD_CATNO]);
		} else {
			$THIS_CAT_NO = "";
		}

	?>

        <TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="2" CLASS=text>
          <TR>
            <TD ALIGN="LEFT" VALIGN="MIDDLE" WIDTH="50%"><FONT SIZE="1"><? echo "$THIS_CAT_NO"; ?>&nbsp;&nbsp;&nbsp;&nbsp;<U>Sku</U>: <? echo strtoupper($PROD[PROD_SKU]); ?>
              </FONT></TD>

		<?

			// ----------------------------------------------------------------------
			// Get the proper Category Name since the keyfield number is databased
			// for the individual sku. Link that category for browsing.
			// ----------------------------------------------------------------------

			$cat = mysql_query("SELECT category FROM cart_category WHERE keyfield = '$PROD[PROD_CATEGORY1]'");
			$this_category = mysql_fetch_array($cat);

		?>

            <TD ALIGN="RIGHT" VALIGN="MIDDLE" WIDTH="50%">
		<FONT SIZE="1"><? echo lang("Browse Category"); ?>: <A HREF="<? echo "start.php?browse=1&cat=".$PROD['PROD_CATEGORY1']."&=SID"; ?>"><? echo $this_category['category']; ?></A></FONT></TD>
          </TR>
        </TABLE>
      </TD>
    </TR>
  </TABLE>
</FORM>



<!-- #################################################################### -->
<!-- END PRODUCT DISPLAY  -->
<!-- #################################################################### -->


