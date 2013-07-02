<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


#=====================================================================================
# CREATE DB TABLE: cart_options
# This script creates the cart_options table and inserts the default data
# It is included by shopping_cart.php if cart_options table does not exist
#=====================================================================================

// =======================================================================
// No Reason for a PriKey Field. This is simply a single record table for
// storing setup options for the shopping cart display; etc. These are
// converted to actual variables in the client side runtime files. If you
// notice, we will read this table into memory and convert the field names
// into vars and that's how we do the if/then testing on the client side.
// =======================================================================

// Just used here in this file, but probably should be a global function somewhere
function quickslashthis($string) {
	if ( get_magic_quotes_runtime() == 0 ) {
		return addslashes($string);
	} else {
		return $string;
	}
}

if ( !table_exists("cart_options") ) {
	create_table("cart_options");

	
//   mysql_db_query($_SESSION['db_name'], "CREATE TABLE cart_options (
//
//      PAYMENT_CREDIT_CARDS CHAR(30),
//      PAYMENT_CHECK_ONLY CHAR(1),
//      PAYMENT_CATALOG_ONLY CHAR(1),
//      PAYMENT_PROCESSING_TYPE CHAR(7),
//      PAYMENT_CURRENCY_TYPE CHAR(5),
//      PAYMENT_CURRENCY_SIGN CHAR(12),
//      PAYMENT_VPARTNERID CHAR(50),
//      PAYMENT_VLOGINID CHAR(50),
//      PAYMENT_INCLUDE CHAR(150),
//      PAYMENT_SSL CHAR(255),
//
//      BIZ_PAYABLE CHAR(100),
//      BIZ_ADDRESS_1 CHAR(100),
//      BIZ_ADDRESS_2 CHAR(100),
//      BIZ_CITY CHAR(100),
//      BIZ_STATE CHAR(100),
//      BIZ_POSTALCODE CHAR(100),
//      BIZ_COUNTRY CHAR(100),
//      BIZ_PHONE CHAR(100),
//      BIZ_VERIFY_COMMENTS BLOB,
//      BIZ_EMAIL_NOTICE BLOB,
//      BIZ_INVOICE_HEADER BLOB,
//
//      DISPLAY_HEADERBG CHAR(7),
//      DISPLAY_HEADERTXT CHAR(7),
//      DISPLAY_CARTBG CHAR(7),
//      DISPLAY_CARTTXT CHAR(7),
//      DISPLAY_WELCOME BLOB,
//      DISPLAY_RESULTS CHAR(2),
//      DISPLAY_RESULTSORT CHAR(50),
//      DISPLAY_COLPLACEMENT CHAR(1),
//      DISPLAY_SEARCH CHAR(1),
//      DISPLAY_USERBUTTON CHAR(55),
//      DISPLAY_ADDCARTBUTTON CHAR(1),
//      DISPLAY_LOGINBUTTON CHAR(1),
//      DISPLAY_CATEGORIES CHAR(1),
//      DISPLAY_COMMENTS CHAR(1),
//      DISPLAY_EMAILFRIEND CHAR(1),
//      DISPLAY_REMEMBERME CHAR(1),
//      DISPLAY_STATE CHAR(25),
//      DISPLAY_ZIP VARCHAR(150),
//      DISPLAY_REQUIRED BLOB,
//
//      INVOICE_INCLUDE CHAR(150),
//
//      LOCAL_COUNTRY BLOB,
//      CHARGE_VAT CHAR(4),
//      VAT_REG CHAR(100),
//      CSS BLOB,
//      GOTO_CHECKOUT VARCHAR(20)
//
//      )") || die("Could not create options table in $db_name");


   // ----------------------------------------------------------
   // Since this is apparently the first time this module has
   // been access, let's populate the options table with our
   // recommended defaults for easier setup and usage
   // ----------------------------------------------------------

   // Should be 45 fields (starting at 1) to CSS

   # Build default data for css field
   $cartcss = array('table_bgcolor'=>"FFFFFF", 'table_textcolor'=>"000000");
   $cartcss = serialize($cartcss);

	# (PAYMENT_CREDIT_CARDS, PAYMENT_CHECK_ONLY, PAYMENT_CATALOG_ONLY, PAYMENT_PROCESSING_TYPE
	
   $data = array();
   $data['PAYMENT_CREDIT_CARDS'] = 'Visa;Mastercard;Amex;Discover;';
   $data['PAYMENT_CHECK_ONLY'] = '';
   $data['PAYMENT_CATALOG_ONLY'] = '';
   $data['PAYMENT_PROCESSING_TYPE'] = '';
   $data['PAYMENT_CURRENCY_TYPE'] = 'USD';
   $data['PAYMENT_CURRENCY_SIGN'] = '\$';
   $data['PAYMENT_VPARTNERID'] = '';
   $data['PAYMENT_VLOGINID'] = '';
   $data['PAYMENT_INCLUDE'] = '';
   $data['PAYMENT_SSL'] = '';
   $data['BIZ_PAYABLE'] = quickslashthis($getSpec['df_company']);
   $data['BIZ_ADDRESS_1'] = quickslashthis($getSpec['df_address1']);
   $data['BIZ_ADDRESS_2'] = quickslashthis($getSpec['df_address2']);
   $data['BIZ_CITY'] = quickslashthis($getSpec['df_city']);
   $data['BIZ_STATE'] = quickslashthis($getSpec['df_state']);
   $data['BIZ_POSTALCODE'] = $getSpec['df_zip'];
   $data['BIZ_COUNTRY'] = $getSpec['df_country'];
   $data['BIZ_PHONE'] = $getSpec['df_phone'];
   $data['BIZ_VERIFY_COMMENTS'] = $getSpec['df_email'];
   $data['BIZ_EMAIL_NOTICE'] = $getSpec['df_email'];
   $data['BIZ_INVOICE_HEADER'] = 'Thank you for your order!';
   $data['DISPLAY_HEADERBG'] = '708090';
   $data['DISPLAY_HEADERTXT'] = 'F5F5F5';
   $data['DISPLAY_CARTBG'] = 'EFEFEF';
   $data['DISPLAY_CARTTXT'] = '000000';
   $data['DISPLAY_WELCOME'] = '';
   $data['DISPLAY_RESULTS'] = '6';
   $data['DISPLAY_RESULTSORT'] = '';
   $data['DISPLAY_COLPLACEMENT'] = 'PROD_NAME';
   $data['DISPLAY_SEARCH'] = 'R';
   $data['DISPLAY_USERBUTTON'] = 'Y';
   $data['DISPLAY_ADDCARTBUTTON'] = '';
   $data['DISPLAY_LOGINBUTTON'] = 'N';
   $data['DISPLAY_CATEGORIES'] = 'N';
   $data['DISPLAY_COMMENTS'] = 'N';
   $data['DISPLAY_EMAILFRIEND'] = 'N';
   $data['DISPLAY_REMEMBERME'] = 'Y';
   $data['DISPLAY_STATE'] = 'usmenu';
   $data['DISPLAY_ZIP'] = 'zippostal';
   $data['DISPLAY_REQUIRED'] = 'yes';
   $data['INVOICE_INCLUDE'] = '';
   $data['LOCAL_COUNTRY'] = 'No Default Country';
   $data['CHARGE_VAT'] = 'no';
   $data['VAT_REG'] = 'vatnum';
   $data['CSS'] = '$cartcss';
   $data['GOTO_CHECKOUT'] = 'no';
   
	$myqry = new mysql_insert('cart_options', $data);
	$myqry->insert();
   
//   mysql_query("INSERT INTO cart_options 
//   	VALUES(
//      'Visa;Mastercard;Amex;Discover;',
//      '',
//      '',
//      'offline','USD','\$',
//      '','','','',
//      '".quickslashthis($getSpec['df_company'])."',
//      '".quickslashthis($getSpec['df_address1'])."',
//      '".quickslashthis($getSpec['df_address2'])."',
//      '".quickslashthis($getSpec['df_city'])."',
//      '".quickslashthis($getSpec['df_state'])."',
//      '$getSpec[df_zip]',
//      '$getSpec[df_country]',
//      '$getSpec[df_phone]',
//      '$getSpec[df_email]',
//      '$getSpec[df_email]',
//      'Thank you for your order!',
//      '708090','F5F5F5','EFEFEF','000000','',
//      '6','PROD_NAME','R','Y','','N',
//      'N','Y','Y','Y','Y','usmenu','zippostal','yes',
//      '','No Default Country','no','vatnum','$cartcss', 'no')") || die(mysql_error());

} // End if !table_exists

?>