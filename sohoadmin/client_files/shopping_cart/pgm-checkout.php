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

session_cache_limiter('none');

# Restore session from before https crossover?
if ( isset($_GET['sid']) ) {
   session_id($_GET['sid']);
}

session_start();

$THIS_DISPLAY = "";
$updated_text = "";

include_once("pgm-cart_config.php");
include('../sohoadmin/client_files/check_min.php');
// Re-registers all global & session info
if ( strlen(lang("Order Date")) < 4 ) {
//   if ( !include("../sohoadmin/program/modules/mods_full/shopping_cart/includes/config-global.php") ) {
//      echo "Could not include config script!"; exit;
//   }
	include("../sohoadmin/includes/db_connect.php");
      
	$selSpecs = mysql_query("SELECT * FROM site_specs");
	$getSpec = mysql_fetch_array($selSpecs);
	
	if ( $getSpec[df_lang] == "" ) {
		$language = "english.php";
	} else {
		$language = $getSpec[df_lang];
	}
	
	if ( $lang_dir != "" ) {
		$lang_include = $lang_dir."/".$language;
	} else {
		$lang_include = "../sohoadmin/language/$language";
	}
	
	include_once("$lang_include");
	
	$_SESSION['getSpec'] = $getSpec;
	$_SESSION['language'] = $language;
	foreach($lang as $lvar=>$lval){
		$_SESSION['lang'][$lvar]=$lval;
	}
   
}

# Echo session vars for testing
//foreach ( $_SESSION as $var=>$val ) {
//   echo "[$var] = ($val)<br>";
//}

// Include shared functions file
##=====================================
$fun_inc = "../sohoadmin/program/includes/shared_functions.php";
include_once($fun_inc);

# restore prefs
$cartprefs = new userdata("cart");
$cartpref = new userdata("cart"); // Emergency justincase ducttape...should be able to kill this
$taxpref = new userdata("tax_rate_options");
eval(hook("pgm-checkout.php:initial_data"));

# Clear bad session values
// Clear cart data since items are bought and paid for
// ----------------------------------------------------
$cart_vars = array();
$cart_vars[] = "CART_KEYID";
$cart_vars[] = "CART_SKUNO";
$cart_vars[] = "CART_CATNO";
$cart_vars[] = "CART_PRODNAME";
$cart_vars[] = "CART_SUBCAT";
$cart_vars[] = "CART_FORMDATA";
$cart_vars[] = "CART_VARNAME";
$cart_vars[] = "CART_UNITPRICE";
$cart_vars[] = "CART_QTY";
$cart_vars[] = "CART_UNITSUBTOTAL";
$cart_vars[] = "ORDER_NUMBER";
$cart_vars[] = "ORDER_TIME";
$cart_vars[] = "ORDER_TOTAL";
$cart_vars[] = "SHIPPING_TOTAL";
$cart_vars[] = "INVOICE";
$cart_vars[] = "BFIRSTNAME";
$cart_vars[] = "BLASTNAME";
$cart_vars[] = "BCOMPANY";
$cart_vars[] = "BADDRESS1";
$cart_vars[] = "BADDRESS2";
$cart_vars[] = "BCITY";
$cart_vars[] = "BZIPCODE";
$cart_vars[] = "BSTATE";
$cart_vars[] = "BCOUNTRY";
$cart_vars[] = "BPHONE";
$cart_vars[] = "BEMAILADDRESS";
$cart_vars[] = "SFIRSTNAME";
$cart_vars[] = "SLASTNAME";
$cart_vars[] = "SCOMPANY";
$cart_vars[] = "SADDRESS1";
$cart_vars[] = "SADDRESS2";
$cart_vars[] = "SCITY";
$cart_vars[] = "SZIPCODE";
$cart_vars[] = "SSTATE";
$cart_vars[] = "SCOUNTRY";
$cart_vars[] = "SPHONE";
$cart_vars[] = "ORDER_DATE";

# Clear session data related to this order
# Addresses wierd quirk of '0' appearing in some billing/shipping fields
foreach ( $cart_vars as $key=>$var ) {
   if ( $_SESSION[$var] === 0 ) {
      $_SESSION[$var] = "";
   }
}


# Pull other polices
//$filename = "$cgi_bin/other_policies.txt";
//$other_policy_definedBool = false;
//if (file_exists($filename)) {
//	$file = fopen("$filename", "r") or DIE(lang("Error").": ".lang("Could not open privacy policy")." ($filename)");
//	$OTHER_POLICY = fread($file,filesize($filename));
//	fclose($file);
//	if (strlen($OTHER_POLICY) > 10) { $other_policy_definedBool = true; }
//}
include_once('pull-policies.inc.php');


######################################################################################################
/// Check for lang and spec arrays to help prevent 'no text' prob, esp. with SSL // Mantis #0000001
###===================================================================================================
if ( strlen(lang("New Customer")) < 4 ) {

   // ----------------------------------------------------
   // Configure System Variables for Language Version
   // ----------------------------------------------------

   // Register default settings from site_specs table
   // ------------------------------------------------
   $selSpecs = mysql_query("SELECT * FROM site_specs");
   $getSpec = mysql_fetch_array($selSpecs);

   if ( $getSpec[df_lang] == "" ) {
      $language = "english.php";
      //echo "getSpec[df_lang] = ($getSpec[df_lang])\n";
      //exit;

   } else {
      $language = $getSpec[df_lang];
   }

   if ( $lang_dir != "" ) {
      $lang_include = $lang_dir."/".$language;
   } else {
      $lang_include = "../sohoadmin/language/$language";
   }

   include_once("$lang_include");

	$_SESSION['getSpec'] = $getSpec;
	$_SESSION['language'] = $language;
	foreach($lang as $lvar=>$lval){
		$_SESSION['lang'][$lvar]=$lval;
	}

   $refreshme = $_SERVER['PHP_SELF'];
   header("location:$refreshme");

}

##########################################################################
### Register the session for secure socket if needed
##########################################################################

if (!isset($_SESSION["CART_KEYID"])) {
	$_SESSION['CART_KEYID'] = $CART_KEYID;
	$_SESSION['CART_SKUNO'] = $CART_SKUNO;
	$_SESSION['CART_CATNO'] = $CART_CATNO;
	$_SESSION['CART_PRODNAME'] = $CART_PRODNAME;
	$_SESSION['CART_SUBCAT'] = $CART_SUBCAT;
	$_SESSION['CART_VARNAME'] = $CART_VARNAME;
	$_SESSION['CART_FORMDATA'] = $CART_FORMDATA;
	$_SESSION['CART_UNITPRICE'] = $CART_UNITPRICE;
	$_SESSION['CART_QTY'] = $CART_QTY;
	$_SESSION['CART_UNITSUBTOTAL'] = $CART_UNITSUBTOTAL;
	$_SESSION['WIN_FULL_PATH'] = $WIN_FULL_PATH;
}

reset($_POST);
//while (list($name, $value) = each($_POST)) {
foreach($_POST as $name=>$value){
	if (preg_match("/^CART_/", $name)) {
		$value = htmlspecialchars($value);	// Bugzilla #32 (This line was not here for #13)
		//$value = str_replace("&amp;", "&", $value);
		${$name} = slashthis($value);
	} else {
		$value = htmlspecialchars($value);	// Bugzilla #13
		
		${$name} = slashthis($value);
	}
	
}
##########################################################################
### WE WILL NEED TO KNOW THE DATABASE NAME; UN; PW; ETC TO OPERATE THE
### REAL-TIME EXECUTION.  THIS IS CONFIGURED IN THE isp.conf FILE
##########################################################################

$dot_com = $this_ip;	// Assign dot_com variable to configured ip address

##########################################################################
### Verify that this is not a hacker attempt to distort cart pricing
### Bugzilla #11
### Modified further to accomodate Bugzilla #32 && Bugzilla #20 (Dup)
### Modified even further to accomodate Bugzilla #36
##########################################################################


if ($CART_SKUNO != "" && $CART_UNITPRICE != "") {
	$hacker = 0;	// Setup Hacker Flag to Zero
	$CART_VARNAME = htmlspecialchars_decode($CART_VARNAME);

   $skuCheck = split(";", htmlspecialchars_decode($CART_SKUNO));
   $priceCheck = split(";", $CART_UNITPRICE);
   $varCheck = split(";", $CART_VARNAME); // Bugzilla #36
   $subtotalCheck = split(";", $CART_UNITSUBTOTAL);
   $qtyCheck = split(";", $CART_QTY);

//   Un-Comment to test variables
//   echo "cartsku - $CART_SKUNO | cartunitpr - $CART_UNITPRICE | cartunitsbttl - $CART_UNITSUBTOTAL | cartqty - $CART_QTY<br>cartvarnam - $CART_VARNAME<br><br>\n";
//   echo "skucheck - $skuCheck[0] | pricechk - $priceCheck[0] | subcheck - $subtotalCheck[0] | qtychk - $qtyCheck[0] | varchk - $varCheck[0]<br><br>\n";
//   exit;

	$UPDATE_PRICING = "";		// Bugzilla #32
	$UPDATE_SUBTOTAL = "";		// Bugzilla #32

		$tmp = count($skuCheck);	// How many sku's have been added to cart for checkout?

		for ($x=0;$x<=$tmp;$x++) {

			if ($skuCheck[$x] != "") {
			   $qry = "SELECT * FROM cart_products WHERE PROD_SKU = '".htmlspecialchars($skuCheck[$x])."'";
				if ( !$newSkuTest = mysql_query($qry) ) {
				  // echo mysql_error();
				   exit;
				}
            if ($qtyCheck[$x] < 1) { $qtyCheck[$x] = 1; }   //Bugzilla #36
				$validateTest = mysql_num_rows($newSkuTest);		// Bugzilla #32
				if ($validateTest < 1) { $hacker = 1; }				// Reflects that given key sku does not exist in database; therefore a hacker has tampered with form data
				// Please note; this will also bomb if a given sku is deleted during a checkout
				// process.  The odds of this happening or slim, but do exist.  I would say that
				// it is a good practice to remove sku's from "display" for 48 hours before deleteing
				// them from the data table if this becomes an issue.

			// Set real price for sku from database -- hackers can not change prices
			$dbValue = mysql_fetch_array($newSkuTest);

      	# Re-restore price variation data for double checking
         # Restore price variation arrays
         $dbValue['sub_cats'] = unserialize($dbValue['sub_cats']);
         $dbValue['variant_names'] = unserialize($dbValue['variant_names']);
         $dbValue['variant_prices'] = unserialize($dbValue['variant_prices']);

         // Check for price variants --- Bugzilla #36
         if ($varCheck[$x] != "") {
            //Moved Subtotal Calculation into variant check --- Bugzilla #36

            for ( $v = 1; $v <= $dbValue['num_variants']; $v++ ) {
               if ($varCheck[$x] == htmlspecialchars_decode($dbValue['variant_names'][$v])) {
                  $UPDATE_PRICING .= $dbValue['variant_prices'][$v] . ";";
                  $UPDATE_SUBTOTAL .= $qtyCheck[$x]*$dbValue['variant_prices'][$v];
                  $UPDATE_SUBTOTAL .= ";";
               }
            }
         } else {
            $UPDATE_PRICING .= $dbValue[PROD_UNITPRICE] . ";";
            $UPDATE_SUBTOTAL .= $qtyCheck[$x]*$dbValue[PROD_UNITPRICE];
            $UPDATE_SUBTOTAL .= ";";
         }



		} // End if No Val

	} // End Next loop

	if ($hacker != 0) {
		echo "<CENTER><H1><FONT COLOR=RED>".lang("ILLEGAL PRODUCT ADDITION DETECTED.")."</FONT></H1>";
		exit;
	} else {
		if($UPDATE_PRICING > 0 && $UPDATE_PRICING != '' && $UPDATE_SUBTOTAL > 0 && $UPDATE_SUBTOTAL != ''){
			if(substr_count($CART_UNITPRICE, ';') == substr_count($UPDATE_PRICING, ';')){
				$CART_UNITPRICE = $UPDATE_PRICING;		// Re-Create Sku Prices based on latest database values; not form values or hacker attempts (Bugzilla #32)
			}
			if(substr_count($CART_UNITSUBTOTAL, ';') == substr_count($UPDATE_SUBTOTAL, ';')){
				$CART_UNITSUBTOTAL = $UPDATE_SUBTOTAL;	// Bugzilla #32 Fix (Subs)
			}
		}
	}

} // End If Sku and Unit Price Var Set



////////////////remember me

	if ( strtoupper($_SESSION['REPEATCUSTOMER']) == "YES" && $_POST['EDIT_INFO'] == 'ON') {		// Note: If remember me feature has been turned off OR is in use by returning customer;								// this var will never be set
		$exist_flag = 0;
		$result = mysql_query("SELECT PRIKEY, USERNAME, PASSWORD FROM cart_customers WHERE USERNAME = '".$_SESSION['BEMAILADDRESS']."' AND PASSWORD = '".$_SESSION['BPASSWORD']."'");
		$exist_flag = mysql_num_rows($result);
		while($remem_get_qry = mysql_fetch_array($result)){
			$cart_rem_prik = $remem_get_qry['PRIKEY'];
		}
		if ($exist_flag > 0) {
			mysql_query("UPDATE cart_customers set
			BILLTO_FIRSTNAME = '".$_POST['BFIRSTNAME']."',
			BILLTO_LASTNAME = '".$_POST['BLASTNAME']."',
			BILLTO_COMPANY = '".$_POST['BCOMPANY']."',
			BILLTO_ADDR1 = '".$_POST['BADDRESS1']."',
			BILLTO_ADDR2 = '".$_POST['BADDRESS2']."',
			BILLTO_CITY = '".$_POST['BCITY']."',
			BILLTO_STATE = '".$_POST['BSTATE']."',
			BILLTO_COUNTRY = '".$_POST['BCOUNTRY']."',
			BILLTO_ZIPCODE = '".$_POST['BZIPCODE']."',
			BILLTO_PHONE = '".$_POST['BPHONE']."',
			BILLTO_EMAILADDR = '".$_POST['BEMAILADDRESS']."',

			SHIPTO_FIRSTNAME = '".$_POST['SFIRSTNAME']."',
			SHIPTO_LASTNAME = '".$_POST['SLASTNAME']."',
			SHIPTO_COMPANY = '".$_POST['SCOMPANY']."',
			SHIPTO_ADDR1 = '".$_POST['SADDRESS1']."',
			SHIPTO_ADDR2 = '".$_POST['SADDRESS2']."',
			SHIPTO_CITY = '".$_POST['SCITY']."',
			SHIPTO_STATE = '".$_POST['SSTATE']."',
			SHIPTO_COUNTRY = '".$_POST['SCOUNTRY']."',
			SHIPTO_ZIPCODE = '".$_POST['SZIPCODE']."',
			SHIPTO_PHONE = '".$_POST['SPHONE']."'
			WHERE PRIKEY='".$cart_rem_prik."'"); echo mysql_error();
		}
	}




//////////////////



##########################################################################
### READ SHOPPING CART SETUP OPTIONS
##########################################################################

$result = mysql_query("SELECT * FROM cart_options");
$OPTIONS = mysql_fetch_array($result);

# Newschool
$gateway = new userdata("gateway");

//040406 - Pull Currency Info
$dSign = $OPTIONS[PAYMENT_CURRENCY_SIGN];
$dType = $OPTIONS[PAYMENT_CURRENCY_TYPE];
$localNat = $OPTIONS[LOCAL_COUNTRY];
$dispState = $OPTIONS[DISPLAY_STATE];

# Restore css styles array
$getCss = unserialize($OPTIONS['CSS']);

$result = mysql_query("SELECT * FROM cart_paystation");
$PAYSTATION = mysql_fetch_array($result);

$result = mysql_query("SELECT * FROM cart_dps");
$DPS = mysql_fetch_array($result);

$result = mysql_query("SELECT * FROM cart_paypro");
$PAYPRO = mysql_fetch_array($result);

$result = mysql_query("SELECT * FROM cart_eway");
$EWAY = mysql_fetch_array($result);

$result = mysql_query("SELECT * FROM cart_paypal");
$PAYPAL = mysql_fetch_array($result);

$result = mysql_query("SELECT * FROM cart_worldpay");
$getWorld = mysql_fetch_array($result);


# Local or international shipping charges? - v4.9 r34
$result = mysql_query("SELECT local_country FROM cart_shipping_opts where order_type = 'local'");
$ship_local_country = mysql_result($result, 0);
if ( $ship_local_country != "" && ($ship_local_country != $_POST['SCOUNTRY'] && $ship_local_country != $_SESSION['SCOUNTRY']) ) { # Added session var to account for user, on step 1, logged in as a existing customer.. and in display settings selected yes for the "Skip billing/shipping info form if they've already filled it out" setting :)
   $local_or_intl = "intl";
} else {
   $local_or_intl = "local";
}
$qry = "SELECT * FROM cart_shipping_opts where order_type = '".$local_or_intl."'";
$result = mysql_query($qry);
$SHIPPING_OPTS = mysql_fetch_array($result);


//Fix spontaeous lower-casing of variables
$BCOUNTRY = strtoupper($BCOUNTRY);
$SCOUNTRY = strtoupper($SCOUNTRY);
$BSTATE = strtoupper($BSTATE);
$SSTATE = strtoupper($SSTATE);

##########################################################################
### INSERT VALIDATE EMAIL FUNCTION
##########################################################################

if(!function_exists('email_is_valid')){
	function email_is_valid ($email) {
		if(!function_exists('filter_var')){
			if(preg_match("/^[A-Z0-9._%-+]+@[A-Z0-9._%-+]+\.[A-Z]{2,4}$/i",$email)){
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			if(filter_var($email,FILTER_VALIDATE_EMAIL) === false){
				return FALSE;
			} else {
				return TRUE;	
			}
		}
	}	// END VALIDATE EMAIL FUNCTION
}
##########################################################################

//
//      ####
//        ##
//        ##
//        ##
//        ##
//        ##
//        ##
//     #######
//

##########################################################################
### DETERMINE IF "REMEMBER ME" IS TURNED ON; IF SO ALLOW CUSTOMER LOGIN
### NOW OR ALLOW "NEW" USER REGISTRATION.  IF NOT TURNED ON, GO DIRECTLY
### TO NEW USER REGISTRATION.
##########################################################################

if (eregi("Y", $OPTIONS[DISPLAY_REMEMBERME] ) && !eregi("Y", $customer_active) && $REPEATCUSTOMER != "YES") {		// Remember me is ON

      /*---------------------------------------------------------------------------------------------------------*
       ___  _
      / __|| |_  ___  _ __  ___
      \__ \|  _|/ -_)| '_ \(_-<
      |___/ \__|\___|| .__//__/
                     |_|
      # DISPLAY CHECKOUT ROUTINE STEPS FOR REFERENCE BY CUSTOMER
      /*---------------------------------------------------------------------------------------------------------*/
      
		# New checkout process started, clear old invoice...
		$_SESSION['final_display_reload'] = NULL;      
      
		$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\" class=\"shopping-selfcontained_box\" id=\"checkout-steps\">\n";

//		# Testing - echo Step number
//		$THIS_DISPLAY .= " <tr><td colspan=\"6\" align=\"center\">Step = (".$STEP.")</td></tr>\n";

		# Current Step
		$THIS_DISPLAY .= " <tr>\n";
 		$THIS_DISPLAY .= "  <th align=\"center\" valign=\"top\">\n";
		$THIS_DISPLAY .= "   <b>".lang("Step")." 1:<br/>".lang("Customer Sign-in")."</b>\n";
		$THIS_DISPLAY .= "  </th>\n";

		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
		$THIS_DISPLAY .= lang("Step")." 2:<br/>".lang("Billing & Shipping")."<br/>".lang("Information")."\n";
		$THIS_DISPLAY .= "  </td>\n";

		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
		$THIS_DISPLAY .= lang("Step")." 3:<br/>".lang("Shipping Options")."\n";
		$THIS_DISPLAY .= "  </td>\n";

		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
		$THIS_DISPLAY .= lang("Step")." 4:<br/>".lang("Verify Order Details")."<br/>\n";
		$THIS_DISPLAY .= "  </td>\n";

		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
		$THIS_DISPLAY .= lang("Step")." 5:<br/>".lang("Make Payment")."\n";
		$THIS_DISPLAY .= "  </td>\n";

		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
		$THIS_DISPLAY .= lang("Step")." 6:<br/>".lang("Print Final")."<br/>".lang("Invoice")."\n";
		$THIS_DISPLAY .= "  </td>\n";

		$THIS_DISPLAY .= " </tr>\n";
		$THIS_DISPLAY .= "</table>\n";
		$THIS_DISPLAY .= "<br/>\n";
		// ----------------------------------------------------------


      /*---------------------------------------------------------------------------------------------------------*
        ___           _                               _                  _
       / __|_  _  ___| |_  ___  _ __   ___  _ _   ___(_) __ _  _ _  ___ (_) _ _
      | (__| || |(_-<|  _|/ _ \| '  \ / -_)| '_| (_-<| |/ _` || ' \|___|| || ' \
       \___|\_,_|/__/ \__|\___/|_|_|_|\___||_|   /__/|_|\__, ||_||_|    |_||_||_|
                                                        |___/
      /*---------------------------------------------------------------------------------------------------------*/
		$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\" class=\"shopping-selfcontained_box\">\n";
		$THIS_DISPLAY .= " <tr>\n";
		$THIS_DISPLAY .= "  <th align=\"left\" valign=\"top\" width=\"95%\">\n";
		$THIS_DISPLAY .= "   ".lang("STEP")." 1: ".lang("CUSTOMER SIGN-IN")."<br/>\n";
		$THIS_DISPLAY .= "   <span class=\"unbold\">".lang("Select an option below so that we can recognize you.")."</span>\n";
		$THIS_DISPLAY .= "  </th>\n";
		$THIS_DISPLAY .= " </tr>\n";
		$THIS_DISPLAY .= "</table>\n";
		$THIS_DISPLAY .= "<br/>\n";

		// ----------------------------------------------------------
		// SHOW LINKS TO PRIVACY AND OTHER POLICIES HERE
		// ----------------------------------------------------------
		$THIS_DISPLAY .= "<P class=text>";
		if ( $cartpref->get("disable_shipping") != "yes" ) {
			if ( $shipping_policy_definedBool ) {
				$THIS_DISPLAY .= "<A HREF=\"start.php?policy=shipping&=SID\">".lang("Shipping Information")."</a>";	
			}
		}
		
		if ( $return_policy_definedBool ) {
			$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
			$THIS_DISPLAY .= "<A HREF=\"start.php?policy=returns&=SID\">".lang("Returns & Exchanges")."</a>";
		}
		
		if ( $privacy_policy_definedBool ) {
			$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
			$THIS_DISPLAY .= "<A HREF=\"start.php?policy=privacy&=SID\">".lang("Privacy Policy")."</a>";
		}
		
		if ( $other_policy_definedBool ) {
			$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
			$THIS_DISPLAY .= "<A HREF=\"start.php?policy=other&=SID\">".$cartpref->get("other_policy_title")."</a></p>";
		}
		// ----------------------------------------------------------

		# Outer table -- left and right container cells
		$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"text\">\n";
		$THIS_DISPLAY .= " <tr>\n";
		$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\" class=\"text\" width=\"50%\">\n";

		# Inner table - New customer
		$THIS_DISPLAY .= "   <table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"95%\" height=\"175\" class=\"shopping-selfcontained_box\">\n";
		$THIS_DISPLAY .= "    <tr>\n";
		$THIS_DISPLAY .= "     <th align=\"left\" valign=\"top\" width=\"95%\">\n";
		$THIS_DISPLAY .= "      ".lang("New Customer")."\n";
		$THIS_DISPLAY .= "     </th>\n";
		$THIS_DISPLAY .= "    </tr>\n";
		$THIS_DISPLAY .= "    <tr>\n";
		$THIS_DISPLAY .= "     <td align=\"left\" valign=\"top\" class=\"text\">\n";
		$THIS_DISPLAY .= "      ".lang("If you are a first time buyer select this option.")." ".lang("You will have the opportunity to register and become a preferred customer.")."\n";
		$THIS_DISPLAY .= "      <form method=\"post\" action=\"pgm-checkout.php\"><input type=\"hidden\" name=\"customer_active\" value=\"Y\"><div align=\"center\" style=\"padding-top:25px;\"><input type=\"submit\" value=\" ".lang("New Customer")." \" class=\"text\" style='CURSOR: HAND;'></div><br/>&nbsp;</form>\n";
		$THIS_DISPLAY .= "     </td>\n";
		$THIS_DISPLAY .= "    </tr>\n";
		$THIS_DISPLAY .= "   </table>\n";
		$THIS_DISPLAY .= "  </td>\n";

		# Inner table - Existing customer login
		$THIS_DISPLAY .= "  <td align=\"right\" valign=\"top\" width=\"50%\">\n";
		$THIS_DISPLAY .= "   <form method=\"post\" action=\"pgm-rememberme.php\">\n";
		$THIS_DISPLAY .= "   <table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"95%\" height=\"175\" class=\"shopping-selfcontained_box\">\n";
		$THIS_DISPLAY .= "    <tr>\n";
		$THIS_DISPLAY .= "     <th colspan=\"2\" align=\"left\" valign=\"top\" width=\"95%\">\n";
		$THIS_DISPLAY .= "      ".lang("Existing Customers, Login Now").":\n";
		$THIS_DISPLAY .= "     </th>\n";
		$THIS_DISPLAY .= "    </tr>\n";

      // Show error text for invalid login?
		if ($SCUN != "" || $SCPW != "" || $rem_err == 1) {
         $THIS_DISPLAY .= "    <tr>\n";
   		$THIS_DISPLAY .= "     <td colspan=\"2\" align=\"center\" valign=\"top\" class=\"text\" bgcolor=$OPTIONS[DISPLAY_HEADERBG] width=\"95%\"><font color=\"#".$OPTIONS[DISPLAY_HEADERTXT]."\">\n";
		   $THIS_DISPLAY .= "      <font color=\"RED\">".lang("Unrecognized Customer").". ".lang("Try Again").".</font>\n";
   		$THIS_DISPLAY .= "     </td>\n";
   		$THIS_DISPLAY .= "    </tr>\n";
		}

		// Email (username) field
		$THIS_DISPLAY .= "    <tr>\n";
		$THIS_DISPLAY .= "     <td align=\"right\" valign=\"top\" class=\"text\">\n";
		$THIS_DISPLAY .= "      ".lang("Email").":\n";
		$THIS_DISPLAY .= "     </td>\n";
		$THIS_DISPLAY .= "     <td align=\"left\" valign=\"top\" class=\"text\">\n";
		$THIS_DISPLAY .= "      <input type=\"text\" size=\"25\" name=\"SCUN\" class=\"text\" style='WIDTH: 200px;'>\n";
		$THIS_DISPLAY .= "     </td>\n";
		$THIS_DISPLAY .= "    </tr>\n";

		// Password field
		$THIS_DISPLAY .= "    <tr>\n";
		$THIS_DISPLAY .= "     <td align=\"right\" valign=\"top\" class=\"text\">\n";
		$THIS_DISPLAY .= "      ".lang("Password").":\n";
		$THIS_DISPLAY .= "     </td>\n";
		$THIS_DISPLAY .= "     <td align=\"left\" valign=\"top\" class=\"text\">\n";
		$THIS_DISPLAY .= "      <input type=\"PASSWORD\" name=\"SCPW\" size=\"25\" class=\"text\" style='WIDTH: 200px;'>\n";
		$THIS_DISPLAY .= "     </td>\n";
      $THIS_DISPLAY .= "    </tr>\n";

      // Login button
      $THIS_DISPLAY .= "    <tr>\n";
		$THIS_DISPLAY .= "     <th colspan=\"2\" align=\"center\" valign=\"top\" width=\"95%\">\n";
		$THIS_DISPLAY .= "      <input type=\"submit\" value=\" ".lang("Login")." \" class=\"text\" style='CURSOR: HAND;'>\n";
		$THIS_DISPLAY .= "     </th>\n";
		$THIS_DISPLAY .= "    </tr>\n";

		// Forgot password link
      $THIS_DISPLAY .= "    <tr>\n";
		$THIS_DISPLAY .= "     <th colspan=\"2\" align=\"center\" valign=\"top\" width=\"95%\">\n";
		$THIS_DISPLAY .= "      ".lang("Forget your password")."? <a href=\"pgm-get_password.php?action=FIND&=SID\">".lang("Click here")."</a>.\n";
		$THIS_DISPLAY .= "     </th>\n";
		$THIS_DISPLAY .= "    </tr>\n";
		$THIS_DISPLAY .= "   </table>\n";
      $THIS_DISPLAY .= "   </form>\n";

		$THIS_DISPLAY .= "  </td>\n";
		$THIS_DISPLAY .= " </tr>\n";
		$THIS_DISPLAY .= "</table>\n";
		$THIS_DISPLAY .= "<P class=\"text\">\n";

		// ----------------------------------------------------------
		// Give user the option to shop more if they prefer; don't
		// stop a client from buying more!
		// ----------------------------------------------------------

		//$THIS_DISPLAY .= "<DIV ALIGN=CENTER><A HREF=\"start.php?browse=1&=SID\"><IMG SRC=\"continue_button.gif\" WIDTH=161 HEIGHT=25 ALIGN=ABSMIDDLE BORDER=0></A>\n</DIV>\n";
		$THIS_DISPLAY .= "<DIV ALIGN=CENTER style=\"padding:5px 5px 10px;\"><button class=\"text\" onClick=\"window.location='start.php?browse=1&=SID';\">Continue Shopping</button>\n</DIV>\n";



} else {
	# New checkout process started, clear old invoice...
	$_SESSION['final_display_reload'] = NULL;
	$customer_active = "Y";

} // End Remember Me Routine

//
//     #######
//    ##     ##
//          ###
//         ###
//        ###
//      ###
//    ###
//    #########
//

##########################################################################
### CUSTOMER STEP 2: GET BILLING AND SHIPPING INFORMATION
##########################################################################

# Skip this step if billing info already in session (and no required data is missing...hence the err_read check...or else you get endless header loops)
# Notes on this condition: Only catch when returning to checkout after browsing cart some more
# -Skip option must be turned on, no error vars can exist in $_GET qry (as happens when submitting bad data in billing form),
# -...count($_POST) < 2 because there will be one var posted when returning to checkout (customer_active = Y)
#-$_SESSION['BEMAILADDRESS'] != "" to make sure billing data exists in session
if ( $cartprefs->get("skip_billingform_ifdone") == "yes" && count($_GET) < 1 && count($_POST) < 2 && $_SESSION['BEMAILADDRESS'] != "" ) {
   $STEP = 3; // Go straight to payment method selection
   $havebilling = true;
} else {
   $havebilling = false;
}

if ($customer_active == "Y" && $STEP == "") {

      /*---------------------------------------------------------------------------------------------------------*
       ___  _
      / __|| |_  ___  _ __  ___
      \__ \|  _|/ -_)| '_ \(_-<
      |___/ \__|\___|| .__//__/
                     |_|
      # DISPLAY CHECKOUT ROUTINE STEPS FOR REFERENCE BY CUSTOMER
      /*---------------------------------------------------------------------------------------------------------*/
		$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\" class=\"shopping-selfcontained_box\" id=\"checkout-steps\">\n";

		$THIS_DISPLAY .= " <tr>\n";
 		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
		$THIS_DISPLAY .= "   ".lang("Step")." 1:<br/>".lang("Customer Sign-in")."\n";
		$THIS_DISPLAY .= "  </td>\n";

		# Current Step
		$THIS_DISPLAY .= "  <th align=\"center\" valign=\"top\">\n";
		$THIS_DISPLAY .= lang("Step")." 2:<br/>".lang("Billing & Shipping")."<br/>".lang("Information")."\n";
		$THIS_DISPLAY .= "  </th>\n";

		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
		$THIS_DISPLAY .= lang("Step")." 3:<br/>".lang("Shipping Options")."\n";
		$THIS_DISPLAY .= "  </td>\n";

		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
		$THIS_DISPLAY .= lang("Step")." 4:<br/>".lang("Verify Order Details")."<br/>\n";
		$THIS_DISPLAY .= "  </td>\n";

		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
		$THIS_DISPLAY .= lang("Step")." 5:<br/>".lang("Make Payment")."\n";
		$THIS_DISPLAY .= "  </td>\n";

		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
		$THIS_DISPLAY .= lang("Step")." 6:<br/>".lang("Print Final")."<br/>".lang("Invoice")."\n";
		$THIS_DISPLAY .= "  </td>\n";

		$THIS_DISPLAY .= " </tr>\n";
		$THIS_DISPLAY .= "</table>\n";
		$THIS_DISPLAY .= "<br/>\n";
		// ----------------------------------------------------------


		// ----------------------------------------------------------

		$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\" class=\"shopping-selfcontained_box\">\n";
		$THIS_DISPLAY .= " <tr>\n";
		$THIS_DISPLAY .= "  <th align=\"left\" valign=\"top\" width=\"95%\">\n";
		$THIS_DISPLAY .= "   ".lang("STEP")." 2: ".lang("BILLING AND SHIPPING INFORMATION")."<br/>\n";
		$THIS_DISPLAY .= "   <span class=\"unbold\">".lang("Please fill out all fields").". ".lang("You will have a chance to verify and correct this information if necessary.")."</span>\n";
		$THIS_DISPLAY .= "  </th>\n";
		$THIS_DISPLAY .= " </tr>\n";
		$THIS_DISPLAY .= "</table>\n";
		$THIS_DISPLAY .= "<br/>\n";

		ob_start();
			include("prod_billing_shipping.inc");
			$THIS_DISPLAY .= ob_get_contents();
		ob_end_clean();



} // End process customer step 2


//
//     #######
//    ##     ##
//           ##
//          ###
//        ####
//          ###
//    ##     ##
//     #######
//

##########################################################################
### CUSTOMER STEP 3: CALCULATE SHIPPING CHARGES.
### **INCLUDE CUSTOM SHIPPING INCLUDE FOR DISPLAY IF NECESSARY
##########################################################################

if ($STEP == "3") {

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// First and foremost, register all customer data in session for easy edit
	// functions later. DO NOT WRITE THIS CUSTOMER TO CUSTOMER OR INVOICE TABLE
	// YET -- HE MIGHT LOSE CONNECTION OR OPT OUT OF FULL PURCHASE AFTER
	// SHIPPING CALCULATION.
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

// Commented out in v4.9.2 r13 --- causing problems: skip billing option "on", submit billing for with error, go back to browse, come back to billing form, rememberme box not checked


	$tmp_rem = 0;

	// Which vars should not have their case changed?
	$nochange = array('BPASSWORD', 'BVERIFYPW', 'BEMAILADDRESS', 'BCOUNTRY', 'BSTATE', 'SCOUNTRY', 'SSTATE', 'BADDRESS1', 'BADDRESS2', 'SADDRESS1', 'SADDRESS2');

	# Loop through POST array or SESSION array? (depends on whether info already set in session and form is to be skipped)
	if ( !$havebilling ) {
	   # POST
   	reset($_POST);
   	while (list($name, $value) = each($_POST)) {
   		$value = htmlspecialchars($value);	// Bugzilla #13

   		if ($tmp_rem == 1) {
   			if(!isset($_SESSION[$name])){
   				$_SESSION[$name]= $value;
   			}
   			$value = stripslashes($value);
   			//echo "(".$value.")<br>";

   			// Was lowercasing all vars.
   			// This is the reason we have to strtoupper country/state vars all over the place.
   			//$value = strtolower($value);

   			// Only ucwords vars that can be changed without issue
   			if(!in_array($name, $nochange)){
   			   $value = strtolower($value);
   				$value = ucwords($value);
   			}
   			${$name} = $value;
   			$_SESSION[$name] = $value;
   		}
   		if ($name == "STEP" && $value == "3") { $tmp_rem = 1; }

//   		if ( $name == "REMEMBERME" ) { echo "<p>REMEMBERME: [".$_POST['REMEMBERME']."]</p>"; exit; }
   	}
   } else {
      # SESSION
   	reset($_SESSION);
   	while (list($name, $value) = each($_SESSION)) {
   		$value = htmlspecialchars($value);	// Bugzilla #13

   		if ($tmp_rem == 1) {
   			$value = stripslashes($value);
   			//echo "(".$value.")<br>";

   			// Was lowercasing all vars
   			// This is the reason we have to strtoupper country/state vars all over the place.. Sheesh!
   			//$value = strtolower($value);

   			// Only ucwords vars that can be changed without issue
   			if(!in_array($name, $nochange)){
   			   $value = strtolower($value);
   				$value = ucwords($value);
   			}
   			${$name} = $value;
   			$_SESSION[$name] = $value;
   		}
   		if ($name == "STEP" && $value == "3") { $tmp_rem = 1; }
   	}
   }


	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// VERIFY REMEMBER ME PASSWORD, EMAIL ADDRESS and US STATE AFTER SESSION REGISTRATION
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	$pwerror = 0;
	$email_err = 0;
	$rm_err = 0;
	$st_err = 0;


	if ( strtoupper($SCOUNTRY) == "UNITED STATES - US" && eregi("STATE~", $OPTIONS['DISPLAY_REQUIRED']) ) {		// Verify Shipping State -- That is where we charge tax

		// Open valid us states file
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~`
		$filename = "us_states.dat";
		$file = fopen("$filename", "r");
			$states_array = fread($file,filesize($filename));
		fclose($file);
		$state = split("\n", $states_array);
		$num_states = count($state);

		$test = strtoupper($SSTATE);
		//$test = substr($test, 3, strlen($test)-1);

		$st_err = 1;

		for ($x=0;$x<=$num_states;$x++) {
		   # Do not modify these next two lines without talking to Mike about Sally Sinclair's recurring problem
		   # where putting "AL" in the shipping state field will make the system recognize the state as "AK - ALASKA" because the first to letters match
		   # It's crucial that at miniumum the "if" condition check here matches the check below for the billing state
			$tmp = split('-', strtoupper($state[$x]));
			if ( $SSTATE != "" && ( $test == trim($tmp[0]) || $test == trim($tmp[1]) || $SSTATE == trim($state[$x])) ) {
				 $st_err = 0; $SSTATE = $state[$x];
			}
		}

	} // End verify us state routine (SHIPPING)

	if ( strtoupper($BCOUNTRY) == "UNITED STATES - US" ) {		// Don't Error -- but see if we can modify billing state for display

		// Open valid us states file
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		$filename = "us_states.dat";
		$file = fopen("$filename", "r");
			$states_array = fread($file,filesize($filename));
		fclose($file);
		$state = split("\n", $states_array);
		$num_states = count($state);

		$test = strtoupper($BSTATE);

		for ( $x=0; $x<=$num_states; $x++ ) {
			# Do not modify these next two lines without talking to Mike about Sally Sinclair's recurring problem
			# where putting "AL" in the shipping state field will make the system recognize the state as "AK - ALASKA" because the first to letters match
			# It's crucial that at miniumum the "if" condition check here matches the check above for the shipping state
			$tmp = " - $state[$x] ";
			if ( $BSTATE != "" && eregi(" - ".$test." -", $tmp) ) { $BSTATE = $state[$x]; }	// Bugzilla #39
		}

	} // End verify us state routine (BILLING)

	// ----------------------------------------------------------------------------
	// Let's hard code validate all required user data submitted (MOD BUILD 10)
	// ----------------------------------------------------------------------------
	$err_read = "";

	// Check Billing Data
	// ----------------------------------------------------

	if ($BFIRSTNAME == "") {
		$err_read = $err_read."A;";
	}
	if ($BLASTNAME == "") {
		$err_read = $err_read."B;";
	}
	if (strlen($BADDRESS1) < 5) {
		$err_read = $err_read."C;";
	}
	if (strlen($BCITY) < 3) {
		$err_read = $err_read."D;";
	}

	# Only check Zip if marked as required in Display Settings
	if(eregi("~BZIPCODE~", $OPTIONS['DISPLAY_REQUIRED']) && ($BZIPCODE == "" || $BZIPCODE == " " )) {
	   $err_read = $err_read."F;";
	}

	if ( $BPHONE == "" ) {
		$err_read = $err_read."G;";
	}

	// Now Check Shipping Data
	// ----------------------------------------------------

	if ($SFIRSTNAME == "") {
		$err_read = $err_read."J;";
	}
	if ($SLASTNAME == "") {
		$err_read = $err_read."K;";
	}
	if (strlen($SADDRESS1) < 5) {
		$err_read = $err_read."L;";
	}
	if (strlen($SCITY) < 3) {
		$err_read = $err_read."M;";
	}

	# Only check Zip if marked as required in Display Settings
	if(eregi("~SZIPCODE~", $OPTIONS['DISPLAY_REQUIRED']) && ($SZIPCODE == "" || $SZIPCODE == " " )) {
	   $err_read = $err_read."N;";
	}

	if ( $SPHONE == "" ) {
		$err_read = $err_read."P;";
	}

	// ---------------------------------------------------------------------------------
	// Redirect on Error OR let pass thru for shipping calculation and order verify
	// --------------------------------------------------------------------------------

	if ($BPASSWORD != $BVERIFYPW && $REMEMBERME == "ON") { $pwerror = 1; }

	if (!eregi("WIN", $WINDIR)) {	// Only Validate Email on Linux Machines. GETMX() does not work with WIN32 PHP
		if (!email_is_valid("$BEMAILADDRESS")) { $email_err = 1; }
	}

	if ($REMEMBERME == "ON" && $BPASSWORD == "" && $BVERIFYPW == "") { $rm_err = 1; }

	if ( $pwerror != 0 || $email_err > 0 || $rm_err != 0 || $st_err != "0" || $err_read != "" ) {
		header("Location: pgm-checkout.php?EDIT_INFO=".$EDIT_INFO."&err_read=$err_read&st_err=$st_err&rm_err=$rm_err&pwerror=$pwerror&email_err=$email_err&STEP=&customer_active=Y&=SID");
		exit;
	}

	// -----------------------------------------------------------------------
	// FREE "SHIPPING_TOTAL" VAR FOR NEW CALCULATIONS
	// -----------------------------------------------------------------------

	$SHIPPING_TOTAL = "0.00";

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// CALCULATE CUSTOM INCLUDE SHIP METHOD - THIS INCLUDE MAY WAIT FOR USER
	// INPUT.  THAT'S JUST FINE, SIMPLY HAVE IT RESUBMIT TO THIS SCRIPT WITH
	// A HIDDEN POST VAR IDENTIFING STEP="3" SO THAT IT COMES BACK HERE.
	// THE SCRIPT SHOULD IDENTIFY IF USER INPUT HAS BEEN VALIDATED AND THEN
	// PROCEED, ETC.
	//
	// DEVNOTE: NO MATTER WHAT THE INCLUDE, WE ARE ASSUMING THAT AFTER THIS
	// FUNCTION EXECUTES, WE RETURN "$SHIPPING_TOTAL" TO THE MAIN SYSTEM.
	// WE WILL REDIRECT TO NEXT STEP AUTOMATICALLY IF "$THIS_DISPLAY" VAR
	// IS EMPTY.  THE ONLY TIME IT WILL NOT BE EMPTY IS WHEN YOU BUILD YOUR
	// INCLUDE TO DISPLAY SOMETHING IN ORDER TO GET CUSTOMER INPUT.
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	if ($SHIPPING_OPTS['SHIP_METHOD'] == "Custom") {

		$this_file = chop($SHIPPING_OPTS['INC_FILENAME']);
		//echo "<center style=\"color: #ff0000; font-weight: bold;\">this_file == (".$this_file.")</center>\n";
		$this_file = "../media/$this_file";

		ob_start();
			include("$this_file");
			$THIS_DISPLAY .= ob_get_contents();
		ob_end_clean();

	}
	
	
	if ($SHIPPING_OPTS['SHIP_METHOD'] == "UPS") {
		//echo testArray($_POST);
		require_once('../sohoadmin/client_files/upsClass.php');		
		ob_start();

		$accessnumber = '5C08FFB987111568';
		$upspref = new userdata("UPS_Shipping_Options");
		$upsusername = $upspref->get("username");
		$upspassword = $upspref->get("password");
		$shippernumber = '';

		$getfromaddyq = mysql_query("select BIZ_ADDRESS_1, BIZ_ADDRESS_2, BIZ_CITY, BIZ_POSTALCODE, BIZ_COUNTRY, PAYMENT_CURRENCY_SIGN from cart_options");
		$getfromaddy = mysql_fetch_assoc($getfromaddyq);
		$curr_sign = $getfromaddy['PAYMENT_CURRENCY_SIGN'];
		$fromcountr_ar = explode(' - ', $getfromaddy['BIZ_COUNTRY']);
		$fromcountry = $fromcountr_ar['1'];
		$fromzip = $getfromaddy['BIZ_POSTALCODE'];
		$tocountr_ar = explode(' - ', $_POST['SCOUNTRY']);
		$tocountry = $tocountr_ar['1'];
		$tozip = $_POST['SZIPCODE'];

		$myRate = new upsRate();
		$myRate->setCredentials($accessnumber,$upsusername,$upspassword,$shippernumber);

		$product_ar = explode(';', $_SESSION['CART_KEYID']);
		array_pop($product_ar);
		$product_qty_ar = explode(';', $_SESSION['CART_QTY']);
		array_pop($product_qty_ar);		

		$service_arr = array('UPS Ground'=>'03','UPS 3 Day Select'=>'12','UPS 2nd Day Air'=>'02','UPS Next Day Air'=>'01','UPS Worldwide Expedited'=>'08','UPS Worldwide Express'=>'07','UPS Wordwide Express Plus'=>'54');
		$upsbulk = $upspref->get("bulk");
		
		if($_POST['upsoption'] == ''){
			
			echo "<p style=\"padding-top:20%;\">&nbsp;</p><form name=\"shippingsubmit\" method=POST action=\"pgm-checkout.php\">\n";		
			foreach($_POST as $pvar=>$pval){
				echo "<input type=\"hidden\" name=\"".$pvar."\" value=\"".$pval."\">\n";	
			}
			$upshipdd = '';
			$upsoptok = 0;
			
			if($upsbulk=='yes'){
			
					foreach($product_ar as $pvar=>$pval){
						$thispq = mysql_query("select PROD_SHIPB, PROD_SHIPC from cart_products where PRIKEY='".$pval."'");
						$thisp = mysql_fetch_assoc($thispq);
						$splitdem = explode(';', $thisp['PROD_SHIPC']);
						if(count($splitdem) == 3){
							$length = $length + ($splitdem['0'] * $product_qty_ar[$pvar]);
							$width = $width + ($splitdem['1'] * $product_qty_ar[$pvar]);
							$height = $height + ($splitdem['2'] * $product_qty_ar[$pvar]);
						} else {
							$length = $length + (6 * $product_qty_ar[$pvar]);
							$width = $width + (6 * $product_qty_ar[$pvar]);
							$height = $height + (6 * $product_qty_ar[$pvar]);
						}
			
						$prod_weight = ($thisp['PROD_SHIPB'] * $product_qty_ar[$pvar]) + $prod_weight;
						//$prod_weight = $thisp['PROD_SHIPB'];
						
						if($prod_weight < .01){ $prod_weight = '0.1'; }
					
					}
					
					foreach($service_arr as $servicename=>$serviceval){
						//echo $fromcountry." ".$fromzip." ".$tocountry." ".$tozip." ".$serviceval." ".$length." ".$width." ".$height." ".$prod_weight."<br/>";
						$rrate = 0;
						$rrate = sprintf("%01.2f", $myRate->getRate($fromcountry,$fromzip,$tocountry,$tozip,$serviceval,$length,$width,$height,$prod_weight));
											
						if($rrate > 0){
							$upshipdd .= "<option value=\"".$servicename."\">".$servicename." ".$curr_sign.$rrate."</option>\n";
							++$upsoptok;
						}
					}	
				
			} else {
			
				foreach($service_arr as $servicename=>$serviceval){
					$rrate = 0;
					foreach($product_ar as $pvar=>$pval){
						$thispq = mysql_query("select PROD_SHIPB, PROD_SHIPC from cart_products where PRIKEY='".$pval."'");
						$thisp = mysql_fetch_assoc($thispq);
						$splitdem = explode(';', $thisp['PROD_SHIPC']);
						if(count($splitdem) == 3){
							$length = $splitdem['0'];
							$width = $splitdem['1'];
							$height = $splitdem['2'];
						} else {
							$length = $length + '0';
							$width = $width + '0';
							$height = $height + '0';
						}
			
						//$prod_weight = ($thisp['PROD_SHIPB'] * $product_qty_ar[$pvar]);
						$prod_weight = $thisp['PROD_SHIPB'];
											
						if($length == '0' || $width == '0' || $height == '0'){
							$length = '12';
							$width = '12';
							$height = '12';
						}
						if($prod_weight < .01){ $prod_weight = '0.1'; }
					
						$rrate = sprintf ("%01.2f", ($myRate->getRate($fromcountry,$fromzip,$tocountry,$tozip,$serviceval,$length,$width,$height,$prod_weight) + $rrate) * $product_qty_ar[$pvar]);					
					}
					if($rrate > 0){
						$upshipdd .= "<option value=\"".$servicename."\">".$servicename." ".$curr_sign.$rrate."</option>\n";
						++$upsoptok;
					}
				}
			
			}
			
			if($upsoptok > 0){
				echo "<p>Select shipping option <select name=\"upsoption\">\n";
				echo $upshipdd;
				echo "</select></p>\n";
//				echo "<p><input type=submit value=\"Next &gt;&gt;\">\n";
				echo "<a href='pgm-checkout.php?EDIT_INFO=ON&customer_active=Y&=SID'>Change Shipping Address</a>\n";
				//echo "<p><input src=\"cont_checkout.gif\" onclick=\"document.shippingsubmit.submit();\" style=\"\" border=\"0\" height=\"25\" type=\"IMAGE\" width=\"106\"></p>\n";
				echo "<p><button onclick=\"document.shippingsubmit.submit();\">Continue</button></p>\n";
				echo "</form>\n";
			} else {
				echo "<p><br/><br/></p>\n";
				echo "<p>Sorry, but we are unable to ship to the shipping address that you have chosen. </p>\n";
				echo "<input type=button onClick=\"window.location='pgm-checkout.php?EDIT_INFO=ON&customer_active=Y&=SID';\" value=\"Change Shipping Address\">\n";
			}
			echo "<p style=\"padding-bottom:20%;\">&nbsp;</p>\n";

			//$service = $service_arr['2nd Day Air'];
		} else {
			$serviceval = $service_arr[$_POST['upsoption']];
			$rrate = 0;


			if($upsbulk=='yes'){
			
				foreach($product_ar as $pvar=>$pval){
					$thispq = mysql_query("select PROD_SHIPB, PROD_SHIPC from cart_products where PRIKEY='".$pval."'");
					$thisp = mysql_fetch_assoc($thispq);
					$splitdem = explode(';', $thisp['PROD_SHIPC']);
					if(count($splitdem) == 3){
						$length = $length + ($splitdem['0'] * $product_qty_ar[$pvar]);
						$width = $width + ($splitdem['1'] * $product_qty_ar[$pvar]);
						$height = $height + ($splitdem['2'] * $product_qty_ar[$pvar]);
					} else {
						$length = $length + (6 * $product_qty_ar[$pvar]);
						$width = $width + (6 * $product_qty_ar[$pvar]);
						$height = $height + (6 * $product_qty_ar[$pvar]);
					}
		
					$prod_weight = ($thisp['PROD_SHIPB'] * $product_qty_ar[$pvar]) + $prod_weight;
					//$prod_weight = $thisp['PROD_SHIPB'];
					
					if($prod_weight < .01){ $prod_weight = '0.1'; }
				
				}
					
				$rrate = sprintf("%01.2f", $myRate->getRate($fromcountry,$fromzip,$tocountry,$tozip,$serviceval,$length,$width,$height,$prod_weight));
				
			} else {
	
				foreach($product_ar as $pvar=>$pval){
					$thispq = mysql_query("select PROD_SHIPB, PROD_SHIPC from cart_products where PRIKEY='".$pval."'");
					$thisp = mysql_fetch_assoc($thispq);
					$splitdem = explode(';', $thisp['PROD_SHIPC']);
					if(count($splitdem) == 3){
						$length = $splitdem['0'];
						$width = $splitdem['1'];
						$height = $splitdem['2'];
					} else {
						$length = $length + '0';
						$width = $width + '0';
						$height = $height + '0';
					}
					//$prod_weight = ($thisp['PROD_SHIPB'] * $product_qty_ar[$pvar]);
					$prod_weight = $thisp['PROD_SHIPB'];
											
					if($length == '0' || $width == '0' || $height == '0'){
						$length = '12';
						$width = '12';
						$height = '12';
					}
					if($prod_weight < .01){ $prod_weight = '0.1'; }
				
					$rrate = sprintf("%01.2f", ($myRate->getRate($fromcountry,$fromzip,$tocountry,$tozip,$serviceval,$length,$width,$height,$prod_weight) + $rrate) * $product_qty_ar[$pvar]);					
				}
			}
			$_SESSION['upsoption'] = $_POST['upsoption'];
			$SHIPPING_INSTRUCTIONS = $_POST['upsoption'].' -';
			$SHIPPING_TOTAL = $rrate;
			$_SESSION['SHIPPING_TOTAL'] = $rrate;
		}
			
		$THIS_DISPLAY .= ob_get_contents();
		ob_end_clean();
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// CALCULATE STANDARD SHIPPING CHARGES.  NO NEED TO PAUSE HERE.
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	if ($SHIPPING_OPTS['SHIP_METHOD'] == "Standard") {

		$tmp_prikey = split(";", $CART_KEYID);		// Place current shopping cart data PRIKEYs into array
		$tmp_cnt = count($tmp_prikey);				// How many line items are in cart?
		$tmp_qty = split(";", $CART_QTY);			// What is the qty ordered for each sku
		// $tmp_cnt = $tmp_cnt - 2;					// Take 2 off of line item count because of extra ; divisions during add routine

		for ($sc=0;$sc<=$tmp_cnt;$sc++) {

			$ship_price = "";					// Reset match calc vars.  Even though they are temporary
			$new_add = "";						// anything can happen to the math in a loop like this.

			// Pull first line item's "SHIP PRICE (A)" value from products table
			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
			$result = mysql_query("SELECT PROD_SHIPA FROM cart_products WHERE PRIKEY = '$tmp_prikey[$sc]'");
			$TVAL = mysql_fetch_array($result);
			$ship_price = $TVAL[PROD_SHIPA];

			// Multiply Individual shipping price by current sku qty
			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

			$new_add = $ship_price*$tmp_qty[$sc];

			$SHIPPING_TOTAL = $SHIPPING_TOTAL + $new_add;	// Add calculation to shipping total

		} // End $sc Loop

	} // End Standard Shipping Calc

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// CALCULATE SUB-TOTAL SHIPPING CHARGES.  NO NEED TO PAUSE HERE.
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	if ($SHIPPING_OPTS[SHIP_METHOD] == "SubTotal") {

		$cartsubtotal = "0.00";								// Clear temp var to begin new calc

		$tmp_subtotals = split(";", $CART_UNITSUBTOTAL);			// Place current line item sub totals in array
		$tmp_prikey = split(";", $CART_KEYID);		// Place current shopping cart data PRIKEYs into array
		$tmp_cnt = count($tmp_subtotals);						// How many line items are in cart?

		for ( $sc=0; $sc<=$tmp_cnt; $sc++ ) {
		   $qry = "SELECT OPTION_CHARGESHIPPING FROM cart_products WHERE PRIKEY = '".$tmp_prikey[$sc]."'";
			$result = mysql_query($qry);
			if ( strtoupper(mysql_result($result, 0)) != "N" ) {
			   $cartsubtotal = $cartsubtotal + $tmp_subtotals[$sc];		// Build temp subtotal number
			}
		}


		for ($st=1;$st<=8;$st++) {

			$tmp = "ST_GTHAN" . $st;
			$one = $SHIPPING_OPTS[$tmp];

			$tmp = "ST_LTHAN" . $st;
			$two = $SHIPPING_OPTS[$tmp];

			$tmp = "ST_SHIP" . $st;
			$three = $SHIPPING_OPTS[$tmp];

			if ($one != "" && $two != "") {
				if ($cartsubtotal > $one && $cartsubtotal < $two) {
				    $SHIPPING_TOTAL = $three;
				    }
			}
			if ($one != "" && $two == "") {
				if ($cartsubtotal > $one) { $SHIPPING_TOTAL = $three; }
			}

		} // End for $st Loop


	} // End SubTotal Ship Method

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// REGISTER "$SHIPPING_TOTAL" VARIABLE IN SESSION -- SHIPPING CALCULATED!
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	$SHIPPING_TOTAL = sprintf ("%01.2f", $SHIPPING_TOTAL);			// Make sure SHIPPING_TOTAL is formated for proper display

   	if(!isset($_SESSION['SHIPPING_TOTAL'])){
   		$_SESSION['SHIPPING_TOTAL']= $SHIPPING_TOTAL;
	}

	$SHIPPING_TOTAL = $SHIPPING_TOTAL;	// Just in case PHP doesn't propogate var value until after var registration

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// IF "$THIS_DISPLAY" var is empty, redirect to next step, we have the
	// shipping total now... else pass through and display any data from
	// include script that needs customer input.
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	if ($THIS_DISPLAY == "") {
		$STEP = "4";
	}

} // End Step 3


//
//    ##      ##
//    ##      ##
//    ##      ##
//    ##########
//            ##
//            ##
//            ##
//            ##
//

##########################################################################
### CUSTOMER STEP 4: VERIFY ORDER INVOICE AND SELECT PAYMENT METHOD
##########################################################################

if ($STEP == "4") {

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// Build INVOICE HTML -- This will be the same thing used for the
	// final customer receipt and display as well
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~



	ob_start();
	include("prod_cust_invoice.inc");
		$INVOICE = ob_get_contents();
	ob_end_clean();


   /*---------------------------------------------------------------------------------------------------------*
    ___  _
   / __|| |_  ___  _ __  ___
   \__ \|  _|/ -_)| '_ \(_-<
   |___/ \__|\___|| .__//__/
                  |_|
   # DISPLAY CHECKOUT ROUTINE STEPS FOR REFERENCE BY CUSTOMER
   /*---------------------------------------------------------------------------------------------------------*/
   $THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\" class=\"shopping-selfcontained_box\" id=\"checkout-steps\">\n";

   $THIS_DISPLAY .= " <tr>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= "   ".lang("Step")." 1:<br/>".lang("Customer Sign-in")."\n";
   $THIS_DISPLAY .= "  </td>\n";

   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 2:<br/>".lang("Billing & Shipping")."<br/>".lang("Information")."\n";
   $THIS_DISPLAY .= "  </td>\n";

   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 3:<br/>".lang("Shipping Options")."\n";
   $THIS_DISPLAY .= "  </td>\n";

   # Current Step
   $THIS_DISPLAY .= "  <th align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 4:<br/>".lang("Verify Order Details")."<br/>\n";
   $THIS_DISPLAY .= "  </th>\n";

   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 5:<br/>".lang("Make Payment")."\n";
   $THIS_DISPLAY .= "  </td>\n";

   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $THIS_DISPLAY .= lang("Step")." 6:<br/>".lang("Print Final")."<br/>".lang("Invoice")."\n";
   $THIS_DISPLAY .= "  </td>\n";

   $THIS_DISPLAY .= " </tr>\n";
   $THIS_DISPLAY .= "</table>\n";
   $THIS_DISPLAY .= "<br/>\n";
   // ----------------------------------------------------------

   $THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\" class=\"shopping-selfcontained_box\">\n";
   $THIS_DISPLAY .= " <tr>\n";
   $THIS_DISPLAY .= "  <th align=\"left\" valign=\"top\" width=\"95%\">\n";
   $THIS_DISPLAY .= "   ".lang("STEP")." 4: ".lang("VERIFY ORDER DETAILS")."<br/>\n";
   $THIS_DISPLAY .= "   <span class=\"unbold\">".lang("Please double check that all information is correct.")."</span>\n";
   $THIS_DISPLAY .= "  </th>\n";
   $THIS_DISPLAY .= " </tr>\n";
   $THIS_DISPLAY .= "</table>\n";
   $THIS_DISPLAY .= "<br/>\n";

	// ---------------------------------------------------
	// Register INVOICE with our session for databasing
	// ---------------------------------------------------
   	if($INVOICE!=''){
   		$_SESSION['INVOICE']= $INVOICE;
	}

	// ------------------------------------------------------------------------------
	// Place the edit links into the INVOICE html -- Remember, we are going to use
	// this for the email and final reciept so we don't won't edit links showing up
	// at that stage
	// ------------------------------------------------------------------------------
	$THIS_INVOICE = eregi_replace("<!-- EDIT -->", "<FONT SIZE=1 FACE=ARIAL>[ <a href=\"pgm-checkout.php?EDIT_INFO=ON&customer_active=Y&=SID\">".lang("EDIT")."</a> ]</font>", $INVOICE);

	# Show view/edit cart link?
	if ( $cartprefs->get("invoice_viewedit_link") == "yes" ) {
	   $editcart_link .= "<div id=\"edit_cart_option\">[ <a href=\"pgm-add_cart.php?ACTION=view\">".lang("Edit shopping cart contents")."</a> ]</div>";
	   $THIS_INVOICE = eregi_replace("<!-- EDITCART -->", $editcart_link, $THIS_INVOICE);
	}

	$THIS_DISPLAY .= $THIS_INVOICE;



	################################################################################################
	## =============================================================================================
	## Allow user to select payment method
	## =============================================================================================
	################################################################################################

	$padEm = "padding-top: 10px;"; // Defines cellpadding for each payment type

	$promo_enabled = '';
	$findcodeq = mysql_query("select prikey, UPPER(code), products, categories, discount_type, discount_amount, cart_min, expiration, disc_per from cart_coupons where expiration >= '".date('Y/m/d')."'");
	while($findcode = mysql_fetch_assoc($findcodeq)){
		if($promo_enabled != 'yes'){
			if(!preg_match('/;/', $findcode['products'])){
				$prod_coup_ar2[] = $findcode['products'];
				if($findcode['products'] == 'all'){
					$promo_enabled = 'yes';
				}
			} else {
				$prod_coup_arz = explode(';', $findcode['products']);
				foreach($prod_coup_arz as $arzv){
					$prod_coup_ar2[] = $arzv;
				}
			}
		}
	}
	if($promo_enabled != 'yes'){
		foreach($tmp_keyid as $tmpkkid){
			if($tmpkkid != ''){
				if(in_array($tmpkkid, $prod_coup_ar2)){
					$promo_enabled = 'yes';
				}
			}
		}
	}
	//if(in_array($tmp_keyid[$z], $prod_coup_ar) || $findcode['products'] == 'all'){

	#promocode stuff	
	if($promo_enabled == 'yes'){
		if($promofail == 'fail'){
			$THIS_DISPLAY .= "<div style=\"padding-right:5px; display: block; width: 100%; text-align: right; color: rgb(48, 111, 174); font-size: 12px;\">\n";
			$THIS_DISPLAY .= "The code you entered is invalid or has expired.  Please double check the code and try again.\n";	
			$THIS_DISPLAY .= "</div>\n";
			$THIS_DISPLAY .= "<div id=\"promocodetext\" style=\"padding-right:5px; cursor: pointer; display: none; width: 100%; text-align: right; color: rgb(48, 111, 174); font-size: 12px;\" onclick=\"document.getElementById('promocode').style.display='block'; document.getElementById('promocodetext').style.display='none';\">\n";
		} else {
			$THIS_DISPLAY .= "<div id=\"promocodetext\" style=\"padding-right:5px; cursor: pointer; display: block; width: 100%; text-align: right; color: rgb(48, 111, 174); font-size: 12px;\" onclick=\"document.getElementById('promocode').style.display='block'; document.getElementById('promocodetext').style.display='none';\">\n";	
		}

		//$THIS_DISPLAY .= "<div id=\"promocodetext\" style=\"padding-right:5px; cursor: pointer; display: block; width: 100%; text-align: right; color: rgb(48, 111, 174); font-size: 12px;\" onclick=\"document.getElementById('promocode').style.display='block'; document.getElementById('promocodetext').style.display='none';\">\n";
		$THIS_DISPLAY .= "Have a promo code?\n";
		$THIS_DISPLAY .= "</div>\n";
	
		if($promofail == 'fail'){
			$THIS_DISPLAY .= "<div id=\"promocode\" style=\"padding-right:5px; width: 100%; text-align: right; display: block;\">\n";
		} else {
			$THIS_DISPLAY .= "<div id=\"promocode\" style=\"padding-right:5px; width: 100%; text-align: right; display: none;\">\n";	
		}
		$THIS_DISPLAY .= "<form method=\"post\" action=\"pgm-checkout.php\">\n";
		$THIS_DISPLAY .= "<input name=\"validatepromo\" value=\"true\" type=\"hidden\">\n";
		foreach($_POST as $pvar=>$pval){
			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"".$pvar."\" value=\"".$pval."\">\n";
		}
		
		$THIS_DISPLAY .= "<p style=\"padding-right:5px; width: 100%; text-align: right;\">\n";
		$THIS_DISPLAY .= "Promotional Code <input name=\"promocode\" value=\"".$_POST['promocode']."\" size=\"20\" type=\"text\"> <input value=\"apply &gt;&gt;\" type=\"submit\"></p>\n";
		$THIS_DISPLAY .= "</form>\n";
		$THIS_DISPLAY .= "</div>\n";
	
		$THIS_DISPLAY .= "<p><br></p>\n";
	}
	##end promocode stuff

	$THIS_DISPLAY .= "<br>\n";
	$THIS_DISPLAY .= "<table border=0 cellpadding=4 cellspacing=0 width=100% class=text style='border: 0px'>\n";
	$THIS_DISPLAY .= " <tr>\n";
	//$THIS_DISPLAY .= "  <td align=left valign=top class=text bgcolor=\"".$OPTIONS['DISPLAY_HEADERBG']."\">\n";
	$THIS_DISPLAY .= "  <td align=left valign=top class=text>\n";
	//$THIS_DISPLAY .= "   <font color=\"".$OPTIONS['DISPLAY_HEADERTXT']."\">\n";
	
	if($OPTIONS[PAYMENT_PROCESSING_TYPE] == ''){
		$THIS_DISPLAY .= "   <B>".lang("The total is for information purposes only")."</B><BR>\n";
	} else {
		$THIS_DISPLAY .= "   <B>".lang("SELECT YOUR METHOD OF PAYMENT")."</B><BR>".lang("Choose your method of payment by clicking on the desired button.")."\n";	
	}
	
	

	// =====================================================================================
	// If this merchant is using the built-in VeriSign(TM) Payflow system, display logo
	// =====================================================================================

	$THIS_DISPLAY .= "  </td>\n";
	$THIS_DISPLAY .= " </tr>\n";
	$THIS_DISPLAY .= "</table>\n";

	// Logic:

	$THIS_DISPLAY .= "<br>\n";
	$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\" class=\"text\" align=\"left\">\n";

	// =====================================================================================
	// What payment types should be available?
	// =====================================================================================

//	// Payment Express Payments
//	// --------------------------------------
//	if ( eregi("dps", $OPTIONS['PAYMENT_PROCESSING_TYPE']) && strlen($DPS['DPS_ID']) > 4 && strlen($DPS['DPS_ACCESS_KEY']) > 4 && strlen($DPS['DPS_MAC_KEY']) > 4) {
//	   $THIS_DISPLAY .= " <form name=\"paydps\" method=\"post\" action=\"pgm-payment_gateway.php\">\n";
//	   $THIS_DISPLAY .= " <tr>\n";
//	   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" class=\"text\" style=\"".$padEm."\">\n";
//		$THIS_DISPLAY .= "   <input type=\"hidden\" name=\"PAY_TYPE\" value=\"DPS\">\n";
//		$THIS_DISPLAY .= "   <a href=\"#\" onClick=\"window.document.paydps.submit();\"><img src=\"logo-dps.gif\" border=\"0\"></a>\n";
//		$THIS_DISPLAY .= "  </td>\n";
//	   $THIS_DISPLAY .= "  <td align=\"left\" valign=\"middle\" class=\"text\" style=\"".$padEm."\">\n";
//	   $THIS_DISPLAY .= "   <b>Payment Express Payments: </b>\n";
//	   $THIS_DISPLAY .= "   Pay with your credit or debit card using the Payment Express secure\n";
//	   $THIS_DISPLAY .= "   online processing gateway.\n";
//	   $THIS_DISPLAY .= "  </td>\n";
//		$THIS_DISPLAY .= " </tr>\n";
//		$THIS_DISPLAY .= " </form>\n";
//	}

	// Paystation Payments
	// --------------------------------------
	if ( eregi("paystation", $OPTIONS['PAYMENT_PROCESSING_TYPE']) && strlen($PAYSTATION['PAYSTATION_ID']) > 3) {
	   $THIS_DISPLAY .= " <form name=\"paystation\" method=\"post\" action=\"pgm-payment_gateway.php\">\n";
	   $THIS_DISPLAY .= " <tr>\n";
	   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" class=\"text\" style=\"".$padEm."\">\n";
		$THIS_DISPLAY .= "   <input type=\"hidden\" name=\"PAY_TYPE\" value=\"".lang("PAYSTATION")."\">\n";
		$THIS_DISPLAY .= "   <a href=\"#\" onClick=\"window.document.paystation.submit();\"><img src=\"logo-paystation.jpg\" border=\"0\"></a>\n";
		$THIS_DISPLAY .= "  </td>\n";
	   $THIS_DISPLAY .= "  <td align=\"left\" valign=\"middle\" class=\"text\" style=\"".$padEm."\">\n";
	   $THIS_DISPLAY .= "   <b>".lang("Paystation Payments").": </b>\n";
	   $THIS_DISPLAY .= "   ".lang("Pay online with your credit or debit card using Paystation's")."\n";
	   $THIS_DISPLAY .= "   ".lang("payment processing gateway").".\n";
	   $THIS_DISPLAY .= "  </td>\n";
		$THIS_DISPLAY .= " </tr>\n";
		$THIS_DISPLAY .= " </form>\n";
	}

	// PayPro Payments
	// --------------------------------------
	if ( eregi("paypro", $OPTIONS['PAYMENT_PROCESSING_TYPE']) && strlen($PAYPRO['PAYPRO_ID']) > 4) {
	   $THIS_DISPLAY .= " <form name=\"paypro\" method=\"post\" action=\"pgm-payment_gateway.php\">\n";
	   $THIS_DISPLAY .= " <tr>\n";
	   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" class=\"text\" style=\"".$padEm."\">\n";
		$THIS_DISPLAY .= "   <input type=\"hidden\" name=\"PAY_TYPE\" value=\"".lang("PAYPRO")."\">\n";
		$THIS_DISPLAY .= "   <a href=\"#\" onClick=\"window.document.paypro.submit();\"><img src=\"logo-paypro.gif\" border=\"0\"></a>\n";
		$THIS_DISPLAY .= "  </td>\n";
	   $THIS_DISPLAY .= "  <td align=\"left\" valign=\"middle\" class=\"text\" style=\"".$padEm."\">\n";
	   $THIS_DISPLAY .= "   <b>".lang("PayPro Payments").": </b>\n";
	   $THIS_DISPLAY .= "   ".lang("Pay online with your credit or debit card using PayPro's")."\n";
	   $THIS_DISPLAY .= "   ".lang("payment processing gateway").".\n";
	   $THIS_DISPLAY .= "  </td>\n";
		$THIS_DISPLAY .= " </tr>\n";
		$THIS_DISPLAY .= " </form>\n";
	}

	// Verisign PayFlow
	// --------------------------------------
	if ( eregi("verisign", $OPTIONS['PAYMENT_PROCESSING_TYPE']) && $OPTIONS['PAYMENT_VLOGINID'] != "" && $OPTIONS[PAYMENT_VPARTNERID] != "" ) { // VeriSign cool
	   $THIS_DISPLAY .= " <form name=\"payvs\" method=\"post\" action=\"pgm-payment_gateway.php\">\n";
	   $THIS_DISPLAY .= " <tr>\n";
	   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" class=\"text\" style=\"".$padEm."\">\n";
		$THIS_DISPLAY .= "   <input type=\"hidden\" name=\"PAY_TYPE\" value=\"".lang("VERISIGN")."\">\n";
		$THIS_DISPLAY .= "   <input type=\"image\" src=\"pay-paypal.gif\" border=\"0\" alt=\"submit\">\n";
		$THIS_DISPLAY .= "  </td>\n";
	   $THIS_DISPLAY .= "  <td align=\"left\" valign=\"middle\" class=\"text\" style=\"".$padEm."\">\n";
	   $THIS_DISPLAY .= "   <b>".lang("Paypal Payflow Link").": </b>\n";
	   $THIS_DISPLAY .= "   ".lang("Pay with your credit or debit card using Paypal's secure")."\n";
	   $THIS_DISPLAY .= "   ".lang("online processing gateway").".\n";
	   $THIS_DISPLAY .= "  </td>\n";
		$THIS_DISPLAY .= " </tr>\n";
		$THIS_DISPLAY .= " </form>\n";
	}

	// EWAY Payments
	// --------------------------------------
	if ( eregi("EWAYATEWAY", $EWAY['EWAY_USER2']) && eregi("eway", $OPTIONS['PAYMENT_PROCESSING_TYPE']) ) {
	   $THIS_DISPLAY .= " <form name=\"payew\" method=\"post\" action=\"pgm-payment_gateway.php\">\n";
	   $THIS_DISPLAY .= " <tr>\n";
	   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" class=\"text\" style=\"".$padEm."\">\n";
		$THIS_DISPLAY .= "   <input type=\"hidden\" name=\"PAY_TYPE\" value=\"".lang("EWAYATEWAY")."\">\n";
		$THIS_DISPLAY .= "   <a href=\"#\" onClick=\"window.document.payew.submit();\"><img src=\"logo-eway.gif\" border=\"0\"></a>\n";
		$THIS_DISPLAY .= "  </td>\n";
	   $THIS_DISPLAY .= "  <td align=\"left\" valign=\"middle\" class=\"text\" style=\"".$padEm."\">\n";
	   $THIS_DISPLAY .= "   <b>".lang("eWAY Payments").": </b>\n";
	   $THIS_DISPLAY .= "   ".lang("Pay with your credit or debit card using eWAY's secure")."\n";
	   $THIS_DISPLAY .= "   ".lang("online processing gateway").".\n";
	   $THIS_DISPLAY .= "  </td>\n";
		$THIS_DISPLAY .= " </tr>\n";
		$THIS_DISPLAY .= " </form>\n";
	}
	
	// EWAY UK/NZ Payments
	// --------------------------------------
	if ( eregi("euknzway", $OPTIONS['PAYMENT_PROCESSING_TYPE']) ) {
	   $THIS_DISPLAY .= " <form name=\"payew_uk\" method=\"post\" action=\"pgm-payment_gateway.php\">\n";
	   $THIS_DISPLAY .= " <tr>\n";
	   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" class=\"text\" style=\"".$padEm."\">\n";
		$THIS_DISPLAY .= "   <input type=\"hidden\" name=\"PAY_TYPE\" value=\"EWAY_UK_NZ\">\n";
		$THIS_DISPLAY .= "   <a href=\"#\" onClick=\"window.document.payew_uk.submit();\"><img src=\"eway-logo-uk.jpg\" border=\"1\"></a>\n";
		$THIS_DISPLAY .= "  </td>\n";
	   $THIS_DISPLAY .= "  <td align=\"left\" valign=\"middle\" class=\"text\" style=\"".$padEm."\">\n";
	   $THIS_DISPLAY .= "   <b>".lang("eWAY Payments").": </b>\n";
	   $THIS_DISPLAY .= "   ".lang("Pay with your credit or debit card using eWAY's secure")."\n";
	   $THIS_DISPLAY .= "   ".lang("online processing gateway").".\n";
	   $THIS_DISPLAY .= "  </td>\n";
		$THIS_DISPLAY .= " </tr>\n";
		$THIS_DISPLAY .= " </form>\n";
	}	




	// WorldPay Payment gateway
	// =======================================
	if ( eregi("worldpay", $OPTIONS['PAYMENT_PROCESSING_TYPE']) && strlen($getWorld['WP_INSTALL_ID']) > 4) {
	   $THIS_DISPLAY .= " <form name=\"paywp\" method=\"post\" action=\"pgm-payment_gateway.php\">\n";
	   $THIS_DISPLAY .= " <tr>\n";
	   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" class=\"text\" style=\"".$padEm."\">\n";
		$THIS_DISPLAY .= "   <input type=\"hidden\" name=\"PAY_TYPE\" value=\"".lang("WORLDPAY")."\">\n";
		$THIS_DISPLAY .= "   <input type=\"image\" src=\"pay-worldpay.gif\" border=\"0\" alt=\"submit\">\n";
		$THIS_DISPLAY .= "  </td>\n";
	   $THIS_DISPLAY .= "  <td align=\"left\" valign=\"middle\" class=\"text\" style=\"".$padEm."\">\n";
	   $THIS_DISPLAY .= "   <b>".lang("WorldPay Payments").": </b>\n";
	   $THIS_DISPLAY .= "   ".lang("Pay online with your credit or debit card using WorldPay's internationally-renowned")."\n";
	   $THIS_DISPLAY .= "   ".lang("payment processing gateway").".\n";
	   $THIS_DISPLAY .= "  </td>\n";
		$THIS_DISPLAY .= " </tr>\n";
		$THIS_DISPLAY .= " </form>\n";
	}

eval(hook("pgm-checkout.php:gateway_button"));

	// Check or Money Order
	// ======================================
   if ( eregi("check", $OPTIONS['PAYMENT_PROCESSING_TYPE']) ) {
	   $THIS_DISPLAY .= " <form name=\"paycheck\" method=\"post\" action=\"pgm-payment_gateway.php\">\n";
	   $THIS_DISPLAY .= " <tr>\n";
	   $THIS_DISPLAY .= "  <td align=\"left\" width=\|145\" class=\"text\" style=\"".$padEm."\">\n";
		$THIS_DISPLAY .= "   <input type=\"hidden\" name=\"PAY_TYPE\" value=\"".lang("CHECK")."\">\n";
		if ( $cartpref->get("checkorcheque") == "" ) { $cartpref->set("checkorcheque", "check"); }
		$THIS_DISPLAY .= "   <input type=\"image\" src=\"pay-".$cartpref->get("checkorcheque").".gif\" border=\"0\" alt=\"submit\">\n";
		$THIS_DISPLAY .= "  </td>\n";
	   $THIS_DISPLAY .= "  <td align=\"left\" class=\"text\" style=\"".$padEm."\">\n";
	   $THIS_DISPLAY .= "   <b>".lang("Check/Money Order").": </b>\n";
	   $THIS_DISPLAY .= "   ".lang("Mail your check/money order payment to us directly").".\n";
	   $THIS_DISPLAY .= "   ".lang("Your order will be processed as soon as your payment is received").".\n";
	   $THIS_DISPLAY .= "  </td>\n";
		$THIS_DISPLAY .= " </tr>\n";
		$THIS_DISPLAY .= " </form>\n";
	}

	// PayPal Payments
	// --------------------------------------
	if ( eregi("paypal", $OPTIONS['PAYMENT_PROCESSING_TYPE']) && ($cartprefs->get('paypalpro_username') != '' || strlen($PAYPAL['PAYPAL_EMAIL']) > 4) ) {
		$CARDNAME = split(";", $OPTIONS['PAYMENT_CREDIT_CARDS']);	// Split Field into individual card names
		$NUMCARDS = count($CARDNAME);						// How many cards are accepted?
		$NUMCARDS--;								// Subtract 1 from total because we start at zero
		for ($z=0;$z<=$NUMCARDS;$z++) {

			if ($CARDNAME[$z] != "") {					// Make sure we don't count blanks

				$THISCARD = strtolower($CARDNAME[$z]);		// Make cardname lower case (match filename)

				if ( $linkme == 1 ) {
   				//$CC_IMGS .= "   <a href=\"#\" onClick=\"window.document.cc_img.submit()\">\n";
   				//$CC_IMGS .= "   <img src=\"".$THISCARD.".gif\" border=\"0\" align=\"left\"></a>\n";
   			} else {
   				//$CC_IMGS .= "   <a href=\"#\" onClick=\"window.document.cc_img.submit()\">\n";
   				$CC_IMGS .= "<img src=\"".$THISCARD.".gif\" border=\"0\">";
   			}


			} // End Blank If

		} // End For Loop


	   $THIS_DISPLAY .= " <form name=\"paypp\" method=\"post\" action=\"pgm-payment_gateway.php\">\n";
	   $THIS_DISPLAY .= " <tr>\n";
	   $THIS_DISPLAY .= "  <td align=\"left\" class=\"text\" style=\"".$padEm."\">\n";
		$THIS_DISPLAY .= "   <input type=\"hidden\" name=\"PAY_TYPE\" id=\"PAY_TYPE\" value=\"".lang("PAYPAL")."\">\n";
		
		if ( preg_match('/paypalpro/i', $OPTIONS['PAYMENT_PROCESSING_TYPE']) ) {
			# PayPal Pro
			$THIS_DISPLAY .= " 	<link rel=\"stylesheet\" href=\"../sohoadmin/client_files/shopping_cart/cart-defaults.css\">\n";
			$THIS_DISPLAY .= "   <div class=\"paypalpro-pay-container\">\n";
			$THIS_DISPLAY .= "   	<a href=\"#\" onclick=\"document.getElementById('PAY_TYPE').value = 'PAYPALPRO';window.document.paypp.submit();\" class=\"paypalpro-pay-btn\">Pay with PayPal Pro</a>\n";
			//$THIS_DISPLAY .= "   	<button onclick=\"document.getElementById('PAY_TYPE').value = 'PAYPALPRO';window.document.paypp.submit();\">Pay Now</button>\n";
			$THIS_DISPLAY .= "   </div>\n";
		}		

		if ( preg_match('/paypal[^pro]/i', $OPTIONS['PAYMENT_PROCESSING_TYPE']) ) {			
			# PayPal Standard
			$THIS_DISPLAY .= "   <a href=\"#\" onClick=\"document.getElementById('PAY_TYPE').value = 'PAYPAL';window.document.paypp.submit();\"><img src=\"pay-paypal.gif\" border=\"0\"></a><br/>\n";
		}	
		
		# Credit card logos
		if ( $cartprefs->get('paypal_cc_logos') != 'no' && eregi("paypal[^pro]", $OPTIONS['PAYMENT_PROCESSING_TYPE']) ) {
			$THIS_DISPLAY .= "   <a href=\"#\" onClick=\"window.document.paypp.submit();\">$CC_IMGS</a>\n";
		}
		$THIS_DISPLAY .= "  </td>\n";

//	   $THIS_DISPLAY .= "  <td align=\"left\" class=\"text\" style=\"".$padEm."\">\n";
//	   $THIS_DISPLAY .= "   <b>".lang("PayPal Payments").": </b>\n";
//	   $THIS_DISPLAY .= "   ".lang("Transfer funds directly from your checking account through your own PayPal login, or")."\n";
//	   $THIS_DISPLAY .= "   ".lang("pay online with credit/debit card").".\n";
//
//	   $THIS_DISPLAY .= "  </td>\n";
		$THIS_DISPLAY .= " </tr>\n";

		$THIS_DISPLAY .= " </form>\n";
	}
	
	
####NOCHEX
$nochex = new userdata("nochex");
$nochex_id = $nochex->get("acctid");
	if ( eregi("use_nochex", $OPTIONS['PAYMENT_PROCESSING_TYPE']) && strlen($nochex_id) > 4) {
		$CARDNAME = split(";", $OPTIONS['PAYMENT_CREDIT_CARDS']);	// Split Field into individual card names
		$NUMCARDS = count($CARDNAME);						// How many cards are accepted?
		$NUMCARDS--;								// Subtract 1 from total because we start at zero
		for ($z=0;$z<=$NUMCARDS;$z++) {

			if ($CARDNAME[$z] != "") {					// Make sure we don't count blanks

				$THISCARD = strtolower($CARDNAME[$z]);		// Make cardname lower case (match filename)

				if ( $linkme == 1 ) {
   				//$CC_IMGS .= "   <a href=\"#\" onClick=\"window.document.cc_img.submit()\">\n";
   				//$CC_IMGS .= "   <img src=\"".$THISCARD.".gif\" border=\"0\" align=\"left\"></a>\n";
   			} else {
   				//$CC_IMGS .= "   <a href=\"#\" onClick=\"window.document.cc_img.submit()\">\n";
   				$CC_IMGS .= "<img src=\"".$THISCARD.".gif\" border=\"0\">";
   			}


			} // End Blank If

		} // End For Loop


	   $THIS_DISPLAY .= " <form name=\"nochex\" method=\"post\" action=\"pgm-payment_gateway.php\">\n";
	   $THIS_DISPLAY .= " <tr>\n";
	   $THIS_DISPLAY .= "  <td align=\"left\" class=\"text\" style=\"".$padEm."\">\n";
		$THIS_DISPLAY .= "   <input type=\"hidden\" name=\"PAY_TYPE\" value=\"".lang("nochex")."\">\n";

//		 if ( eregi("offline", $OPTIONS['PAYMENT_PROCESSING_TYPE'])){
//			$THIS_DISPLAY .= "   <a href=\"#\" onClick=\"window.document.nochex.submit();\"><img src=\"pay-paypal.gif\" border=\"0\"></a>\n";
//		} else {
//			$THIS_DISPLAY .= "   <a href=\"#\" onClick=\"window.document.nochex.submit();\"><img src=\"pay-paypal.gif\" border=\"0\"></a><br/>\n";
//		}
		$THIS_DISPLAY .= "   <a href=\"#\" onClick=\"window.document.nochex.submit();\">$CC_IMGS</a>\n";
		$THIS_DISPLAY .= "  </td>\n";

	   $THIS_DISPLAY .= "  <td align=\"left\" class=\"text\" style=\"".$padEm."\">\n";
	   $THIS_DISPLAY .= "   <b>".lang("Credit/Debit Payments").": </b>\n";
	   $THIS_DISPLAY .= "   ".lang("Pay online with credit/debit card using NoChex").".\n";

	   $THIS_DISPLAY .= "  </td>\n";
		$THIS_DISPLAY .= " </tr>\n";

		$THIS_DISPLAY .= " </form>\n";
	}
	
	
	
	
	
   ############################################################################
   // Build credit card icon table if live processing is enabled
   ############################################################################
   if ( (eregi("\.PHP", $OPTIONS['PAYMENT_INCLUDE']) || eregi("\.INC", $OPTIONS['PAYMENT_INCLUDE'])) || eregi("use_protx", $OPTIONS['PAYMENT_PROCESSING_TYPE']) || eregi("innovgate", $OPTIONS['PAYMENT_PROCESSING_TYPE']) || eregi("paypoint", $OPTIONS['PAYMENT_PROCESSING_TYPE']) || eregi("authorize", $OPTIONS['PAYMENT_PROCESSING_TYPE']) || eregi("internetsecure", $OPTIONS['PAYMENT_PROCESSING_TYPE']) || eregi("eway", $OPTIONS['PAYMENT_PROCESSING_TYPE']) || eregi("dps", $OPTIONS['PAYMENT_PROCESSING_TYPE']) || eregi("transactium", $OPTIONS['PAYMENT_PROCESSING_TYPE']) ) {

      $CC_IMGS = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"text\">\n";

		$CARDNAME = split(";", $OPTIONS['PAYMENT_CREDIT_CARDS']);	// Split Field into individual card names
		$NUMCARDS = count($CARDNAME);						// How many cards are accepted?
		$NUMCARDS--;								// Subtract 1 from total because we start at zero

		$linkme = 0;
		if (eregi(".php", $OPTIONS['PAYMENT_INCLUDE']) || eregi(".inc", $OPTIONS['PAYMENT_INCLUDE'])) {
		   $CC_IMGS .= "<form name=\"cc_img\" method=\"post\" action=\"pgm-payment_gateway.php\">\n";
		   $CC_IMGS .= "<input type=\"hidden\" name=\"PAY_TYPE\" value=\"CUSTOM_INC\">\n";
		   $CC_IMGS .= "</form>\n";
		   $linkme = 1;
//
//		} elseif ( eregi("offline", $OPTIONS['PAYMENT_PROCESSING_TYPE']) ) {
//		   $CC_IMGS .= "<form name=\"cc_img\" method=\"post\" action=\"pgm-payment_gateway.php\">\n";
//		   $CC_IMGS .= "<input type=hidden name=\"PAY_TYPE\" value=\"".lang("CREDITCARD")."\">\n";
//		   $CC_IMGS .= "</form>\n";
//		   $linkme = 1;


		} elseif ( eregi("innovgate", $OPTIONS['PAYMENT_PROCESSING_TYPE']) ) {
		   $CC_IMGS .= "<form name=\"cc_img\" method=\"post\" action=\"pgm-payment_gateway.php\">\n";
		   $CC_IMGS .= "<input type=hidden name=\"PAY_TYPE\" value=\"".lang("INNOVGATE")."\">\n";
		   $CC_IMGS .= "</form>\n";
		   $linkme = 1;

		} elseif ( eregi("paypoint", $OPTIONS['PAYMENT_PROCESSING_TYPE']) ) {
		   $CC_IMGS .= "<form name=\"cc_img\" method=\"post\" action=\"pgm-payment_gateway.php\">\n";
		   $CC_IMGS .= "<input type=hidden name=\"PAY_TYPE\" value=\"".lang("PAYPOINT")."\">\n";
		   $CC_IMGS .= "</form>\n";
		   $linkme = 1;
		} elseif ( eregi("authorize", $OPTIONS['PAYMENT_PROCESSING_TYPE']) ) {
		   $CC_IMGS .= "<form name=\"cc_img\" method=\"post\" action=\"pgm-payment_gateway.php\">\n";
		   $CC_IMGS .= "<input type=hidden name=\"PAY_TYPE\" value=\"".lang("AUTHORIZENET")."\">\n";
		   $CC_IMGS .= "</form>\n";
		   $linkme = 1;
		} elseif ( eregi("internetsecure", $OPTIONS['PAYMENT_PROCESSING_TYPE']) ) {
		   $CC_IMGS .= "<form name=\"cc_img\" method=\"post\" action=\"pgm-payment_gateway.php\">\n";
		   $CC_IMGS .= "<input type=hidden name=\"PAY_TYPE\" value=\"".lang("internetsecure")."\">\n";
		   $CC_IMGS .= "</form>\n";
		   $linkme = 1;
		} elseif ( eregi("EWAYONSITE", $EWAY['EWAY_USER1']) && eregi("eway", $OPTIONS['PAYMENT_PROCESSING_TYPE']) ) {
		   $CC_IMGS .= "<form name=\"cc_img\" method=\"post\" action=\"pgm-payment_gateway.php\">\n";
		   $CC_IMGS .= "<input type=hidden name=\"PAY_TYPE\" value=\"EWAY\">\n";
		   $CC_IMGS .= "</form>\n";
		   $linkme = 1;
		} elseif ( eregi("dps", $OPTIONS['PAYMENT_PROCESSING_TYPE']) ) {
		   $CC_IMGS .= "<form name=\"cc_img\" method=\"post\" action=\"pgm-payment_gateway.php\">\n";
		   $CC_IMGS .= "<input type=hidden name=\"PAY_TYPE\" value=\"".lang("DPS")."\">\n";
		   $CC_IMGS .= "</form>\n";
		   $linkme = 1;
		} elseif ( eregi("transactium", $OPTIONS['PAYMENT_PROCESSING_TYPE']) ) {
		   $CC_IMGS .= "<form name=\"cc_img\" method=\"post\" action=\"pgm-payment_gateway.php\">\n";
		   $CC_IMGS .= "<input type=hidden name=\"PAY_TYPE\" value=\"".lang("transactium")."\">\n";
		   $CC_IMGS .= "</form>\n";
		   $linkme = 1;
		}




		$CC_IMGS .= " <tr>\n";
      $CC_IMGS .= "  <td align=\"left\" valign=\"top\" style=\"padding-left: 5px;\">\n";

		for ($z=0;$z<=$NUMCARDS;$z++) {

			if ($CARDNAME[$z] != "") {					// Make sure we don't count blanks

				$THISCARD = strtolower($CARDNAME[$z]);		// Make cardname lower case (match filename)

				if ( $linkme == 1 ) {
   				$CC_IMGS .= "   <a href=\"#\" onClick=\"window.document.cc_img.submit()\">\n";
   				$CC_IMGS .= "   <img src=\"".$THISCARD.".gif\" border=\"0\" align=\"absmiddle\"></a>\n";
   			} else {
   				$CC_IMGS .= "   <img src=\"".$THISCARD.".gif\" border=\"0\" align=\"absmiddle\">";
   			}

   			$CC_IMGS .= "&nbsp;\n";

			} // End Blank If

		} // End For Loop

      $CC_IMGS .= "  </td>\n";
		$CC_IMGS .= " </tr>\n";
		$CC_IMGS .= "</table>\n\n";
		//$CC_IMGS .= "</form>\n";

	} // End Credit "on" check

	//echo $CC_IMGS; exit;

	// Offline Credit-Card Processing
	// ------------------------------------------------------------
	if ( eregi("\.PHP", $OPTIONS['PAYMENT_INCLUDE']) || eregi("\.INC", $OPTIONS['PAYMENT_INCLUDE']) || eregi("use_innovgate", $OPTIONS['PAYMENT_PROCESSING_TYPE']) || eregi("use_paypoint", $OPTIONS['PAYMENT_PROCESSING_TYPE']) || eregi("use_authorize", $OPTIONS['PAYMENT_PROCESSING_TYPE']) || eregi("use_internetsecure", $OPTIONS['PAYMENT_PROCESSING_TYPE']) || eregi("EWAYONSITE", $EWAY['EWAY_USER1']) || eregi("dps", $OPTIONS['PAYMENT_PROCESSING_TYPE']) || eregi("transactium", $OPTIONS['PAYMENT_PROCESSING_TYPE']) ) {
	   $THIS_DISPLAY .= " <tr>\n";
	   $THIS_DISPLAY .= "  <td colspan=\"2\" align=\"left\" valign=\"middle\" class=\"text\" style=\"".$padEm."\">\n";
	   $THIS_DISPLAY .= "   <b>".lang("Credit/Debit Card Payment")." </b><br>\n";
	   $THIS_DISPLAY .= "   ".lang("We currently accept the following credit cards").":\n";
	   $THIS_DISPLAY .= "  </td>\n";
		$THIS_DISPLAY .= " </tr>\n";
	   $THIS_DISPLAY .= " <tr>\n";
	   $THIS_DISPLAY .= "  <td colspan=\"2\" align=\"left\" valign=\"middle\" class=\"text\" style=\"padding: 0px;\">\n";
      $THIS_DISPLAY .= "  ".$CC_IMGS."\n";
	   $THIS_DISPLAY .= "  </td>\n";
		$THIS_DISPLAY .= " </tr>\n";
   }
   //echo $THIS_DISPLAY; exit;
//   echo "<hr><hr><hr>\n";
//   echo "\n\n\n\n\n\n\n";
//   echo "<!---CC_IMGS OUTPUT--->";
//   echo "\n\n\n\n\n\n\n";
//   echo $CC_IMGS;
//   echo "<hr><hr><hr>\n";



	// Live Credit-Card Processing (Innovative Gateway)
	/* ------------------------------------------------------------- *
	if ( eregi("use_innovgate", $OPTIONS[PAYMENT_PROCESSING_TYPE]) ) {
	   $THIS_DISPLAY .= " <form name=\"payig\" method=\"post\" action=\"pgm-payment_gateway.php\">\n";
	   $THIS_DISPLAY .= " <tr>\n";
	   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" class=\"text\" style=\"".$padEm."\">\n";
		$THIS_DISPLAY .= "   <input type=\"hidden\" name=\"PAY_TYPE\" value=\"INNOVGATE\">\n";
		$THIS_DISPLAY .= "   <input type=\"image\" src=\"pay-check.gif\" border=\"0\" alt=\"submit\">\n";
		$THIS_DISPLAY .= "  </td>\n";
	   $THIS_DISPLAY .= "  <td align=\"left\" valign=\"middle\" class=\"text\" style=\"".$padEm."\">\n";
	   $THIS_DISPLAY .= "   <b>Credit/Debit Card Payment: </b>\n";
	   $THIS_DISPLAY .= "   Mail your check/money order payment to us directly.\n";
	   $THIS_DISPLAY .= "   Your order will be processed as soon as your payment is recieved.\n";
	   $THIS_DISPLAY .= "  </td>\n";
		$THIS_DISPLAY .= " </tr>\n";
		$THIS_DISPLAY .= " </form>\n";
   }
   /* ------------------------------------------------------------- */


	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// DEVNOTE:
	// If this is check/money order only, give notice about that fact.
	// Realisticly you could take this out if you wish, but people sometimes
	// wonder if they "missed" the chance to select "Credit Card" checkout
	// somewhere in the checkout process.
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
   if ( $OPTIONS['PAYMENT_CHECK_ONLY'] == "y" ) {
      $NOTICE_DATA = lang("Currently we are only accepting Check or Money Order payments.")."<BR><BR>";
   } else {
      $NOTICE_DATA = "";
   }

	// ----------------------------------------------------------------------------

	$THIS_DISPLAY .= "</table>\n\n";



	// ------------------------------------------------------------------------------

} // End Step 4

//	   #######   ###    ##    ####
// 	##        ####   ##    ## ##
//	   ##        ## ##  ##    ##  ##
// 	####      ##  ## ##    ##   ##
// 	##        ##   ####    ##  ##
// 	##        ##    ###    ## ##
// 	#######   ##     ##    ####

##########################################################################
### BUILD OVERALL TABLE TO PLACE FINAL OUTPUT WITHIN
##########################################################################

$FINAL_DISPLAY = "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" align=\"center\" class=\"parent_table\">\n";
$FINAL_DISPLAY .= "<TR>\n";
$FINAL_DISPLAY .= "<TD ALIGN=CENTER VALIGN=TOP>\n\n$THIS_DISPLAY\n\n</TD>\n";
$FINAL_DISPLAY .= "</TR>\n\n";

//echo "\n\n\n\n\n\n\n";
//echo "<!---START THIS_DISPLAY OUTPUT--->";
//echo "<hr><hr><hr>\n";
//echo $THIS_DISPLAY;
//echo "<hr><hr><hr>\n";
//echo "<!---END THIS_DISPLAY OUTPUT--->";
//echo "\n\n\n\n\n\n\n";
//exit;
// ----------------------------------------------------------------------------------
// If a business address has been supplied, display at the footer of each shopping
// cart page.  This can be removed if you wish, but studies have shown it instills
// trust among consumers that wish to buy from this web site
// ----------------------------------------------------------------------------------

if ($OPTIONS['BIZ_ADDRESS_1'] != "" && $OPTIONS['BIZ_POSTALCODE'] != "") {

	$FINAL_DISPLAY .= "<TR><TD ALIGN=CENTER VALIGN=MIDDLE CLASS=smtext>\n";
	$FINAL_DISPLAY .= "<HR WIDTH=100% STYLE='height: 1px; color: $OPTIONS[DISPLAY_HEADERBG];'>\n".lang("Mailing Address").": $OPTIONS[BIZ_ADDRESS_1], ";

		if ($OPTIONS['BIZ_ADDRESS2'] != "") {
			$FINAL_DISPLAY .= "$OPTIONS[BIZ_ADDRESS_2], ";
		}

	$FINAL_DISPLAY .= "$OPTIONS[BIZ_CITY], ".$OPTIONS['BIZ_STATE'].", ".$OPTIONS['BIZ_POSTALCODE']."\n<HR WIDTH=100% STYLE='height: 1px; color: $OPTIONS[DISPLAY_HEADERBG];'>";
	$FINAL_DISPLAY .= "</TD></TR>\n\n";

}

// ----------------------------------------------------------------------------------

$FINAL_DISPLAY .= "</TABLE>";

###########################################################################
### THE pgm-realtime_builder.php FILE COMPILES THE TEMPLATE DATA AND PAGE
### CONTENT DATA TOGETHER AND PUTS IT OUT AS THE $template_header AND
### $template_footer VARS RESPECTIVELY.  ANY MODIFICATION TO CHANGE THE
### WAY PAGES ARE OUTPUT TO THE SITE VISITOR SHOULD BE MADE WITHIN THE
### realtime_builder.php FILE
###########################################################################
$_SESSION['CART_KEYID'] = $CART_KEYID;
$_SESSION['CART_SKUNO'] = $CART_SKUNO;
$_SESSION['CART_CATNO'] = $CART_CATNO;
$_SESSION['CART_PRODNAME'] = $CART_PRODNAME;
$_SESSION['CART_SUBCAT'] = $CART_SUBCAT;
$_SESSION['CART_VARNAME'] = $CART_VARNAME;
$_SESSION['CART_FORMDATA'] = $CART_FORMDATA;
$_SESSION['CART_UNITPRICE'] = $CART_UNITPRICE;
$_SESSION['CART_QTY'] = $CART_QTY;
$_SESSION['CART_UNITSUBTOTAL'] = $CART_UNITSUBTOTAL;
$_SESSION['WIN_FULL_PATH'] = $WIN_FULL_PATH;
$_SESSION['ORDER_NUMBER'] = $ORDER_NUMBER;
$_SESSION['ORDER_TIME'] = $ORDER_TIME;
$_SESSION['ORDER_TOTAL'] = $ORDER_TOTAL;
$_SESSION['SHIPPING_TOTAL'] = $SHIPPING_TOTAL;
$_SESSION['INVOICE'] = $INVOICE;
$_SESSION['BFIRSTNAME'] = $BFIRSTNAME;
$_SESSION['BLASTNAME'] = $BLASTNAME;
$_SESSION['BCOMPANY'] = $BCOMPANY;
$_SESSION['BADDRESS1'] = $BADDRESS1;
$_SESSION['BADDRESS2'] = $BADDRESS2;
$_SESSION['BCITY'] = $BCITY;
$_SESSION['BZIPCODE'] = $BZIPCODE;
$_SESSION['BSTATE'] = $BSTATE;
$_SESSION['BCOUNTRY'] = $BCOUNTRY;
$_SESSION['BPHONE'] = $BPHONE;
$_SESSION['BEMAILADDRESS'] = $BEMAILADDRESS;
$_SESSION['SFIRSTNAME'] = $SFIRSTNAME;
$_SESSION['SLASTNAME'] = $SLASTNAME;
$_SESSION['SCOMPANY'] = $SCOMPANY;
$_SESSION['SADDRESS1'] = $SADDRESS1;
$_SESSION['SADDRESS2'] = $SADDRESS2;
$_SESSION['SCITY'] = $SCITY;
$_SESSION['SZIPCODE'] = $SZIPCODE;
$_SESSION['SSTATE'] = $SSTATE;
$_SESSION['SCOUNTRY'] = $SCOUNTRY;
$_SESSION['SPHONE'] = $SPHONE;


$module_active = "yes";
include ("pgm-template_builder.php");

#######################################################

echo ("$template_header\n");
	$template_footer = eregi_replace("#CONTENT#", $FINAL_DISPLAY, $template_footer);
echo ("$template_footer\n\n");
echo ("\n\n<SCRIPT language=Javascript>\n     window.focus();\n</SCRIPT>\n\n");
exit;

?>