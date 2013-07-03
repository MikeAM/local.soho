<?php
error_reporting('341');

track_vars;

include_once("pgm-cart_config.php");
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

#==========================================================================================================================================
# Pull and compile special css rules for cart system
# Included by virtually all of the Shopping Cart's pgm-* files
# LATER: Pull custom shopping_cart.css file (i.e. if found in template folder) instead of databased values if desired...
#        ...(maybe by clicking some box that says "use template css")
#==========================================================================================================================================

//echo "included!"; exit;

# Get path to current template
//include_once("../sohoadmin/client_files/get_template_path.inc.php"); // Defines $template_fullpath and $template_foldername

# Pull cart CSS settings from db if not already set
if ( !isset($getCss) ) {
   $qry = "SELECT CSS FROM cart_options";
   $rez = mysql_query($qry);
   $getCss = unserialize(mysql_result($rez, 0));
}

# Buffer output of css styles
//$module_css = "\n\n<!---css rules for cart system-->\n";
$module_css = "";
//$module_css .= "<style>\n";

# Get cart misc prefs
$cartpref = new userdata("cart");

# DEFAULT: 95px for thumbnail images
if ( $cartpref->get("thumb_width") == "" ) { $cartpref->set("thumb_width", 95); }


if(preg_match('/(?i)msie [8-10]/i',$_SERVER['HTTP_USER_AGENT'])) {
	header("X-Content-Type-Options: nosniff");
	header("Content-type: text/css", true);	
} else {

	header("Content-Type: text/css");
}
session_cache_limiter('none');
session_start();
ob_start();

//echo "Template Path: ".$template_fullpath."<br/>";
//exit;

?>
.cartnav {
	width:100%;
	text-align:left;
	padding-bottom:3px;
	position:relative;
}
.cartnavright {
	float:right;
	text-align:right;

}

#shopping_module table {
   font-family: arial, helvetica, sans-serif;
   font-size: 11px;
   width:100%;
<?php
if($getCss['table_textcolor']!=''){
	//echo "	color: ".$getCss['table_textcolor'].";";
}
?>
}

table.parent_table {
   width: 90%;
}


/*#searchcolumn table,#moreinfo-details, ,   #moreinfo-summary */

table.shopping-selfcontained_box, #moreinfo-pricing, #moreinfo-comments, #addcart-current_cart_contents {
   border: 1px solid #ccc;
<?php
if($getCss['table_bgcolor']!=''){
	echo "background-color: ".$getCss['table_bgcolor'].";";
}
?>

}

#shopping_module th {
   /* background-color: <?php echo $OPTIONS['DISPLAY_HEADERBG']; ?>; */
   background-color:inherit;
   
/*   color: <?php echo $OPTIONS['DISPLAY_HEADERTXT']; ?>; */
   
   text-align: left;
}

/*--------------------------------------------------------
 pgm-more_information.php
--------------------------------------------------------*/
table#moreinfo-pricing, table#moreinfo-summary, table#moreinfo-details {
   /* margin-top: 15px; */
   margin-top: -4px;
   margin-left: 6px;
   cellspacing:0;
   cellpadding:0;
}

table.shopping-selfcontained_box {
	width:98%;
	margin-bottom:6px;
}

#moreinfo-pricing th {
   text-align: center;
}

table#moreinfo-comments {
   margin-top: 15px;
}
table#moreinfo-details {
   margin-top: 15px;
}


div#additional_images-container {
   /*border: 1px solid red;*/
   clear: both;
}
div#additional_images-container h4 {
   margin: 0;
}
div#additional_images-gallery_block {
   /*border: 1px solid blue;*/
   /*padding-top: 20px;*/
   margin: 10px;
   width: 100%;
}

/* Additional sku image thumbnails (i.e. "Select a picture...") */
div.additional_images-thumb {
   float: left;
   overflow: hidden;
   /*background-image: url('http://<?php echo $_SESSION['docroot_url']; ?>/sohoadmin/icons/web20_bg.gif');*/
   margin: 5px;
   height: <?php echo $cartpref->get("thumb_width"); ?>px;
}
div.additional_images-thumb img {
   border: 1px solid #efefef;
   width: <?php echo $cartpref->get("thumb_width"); ?>px;
}
/* This is the popup box that the larger images appear in on mouse-over */
#trailimageid {
	position: absolute;
	display: none;
	left: 0px;
	top: 0px;
	/*width: 286px;*/
	height: 1px;
	z-index: 1000;
}


/*--------------------------------------------------------
 prod_search_column.inc.php
--------------------------------------------------------*/
#searchcolumn th {
  /* background-color: <?php echo $OPTIONS['DISPLAY_HEADERBG']; ?>;
   color: <?php echo $OPTIONS['DISPLAY_HEADERTXT']; ?>; */
   text-align: left;
}

#searchcolumn-login_or_date td {
   padding: 5px;
   vertical-align: top;   
}

#searchcolumn-login_or_date {
   border-bottom: 0px;
   width:100%;
   background-color: transparent;
}

#searchcolumn-items_in_cart {
   /* color: <?php echo $OPTIONS['DISPLAY_CARTTXT']; ?>;
   background-color: <?php echo $OPTIONS['DISPLAY_CARTBG']; ?>; */
}


/*--------------------------------------------------------
 prod_search_template.inc
 ...controls search results/browse view/category view
--------------------------------------------------------*/
span.price_caption {
   font-weight: bold;
   /* color: #2e2e2e; */
}


/*--------------------------------------------------------
 pgm-checkout.php
--------------------------------------------------------*/
#checkout-steps th {
   text-align: center;
}


/*--------------------------------------------------------
 prod_billing_shipping.inc
--------------------------------------------------------*/
#billing_shipping_form {
   width: 90%;
}

#billing_shipping_form input.tfield, #billing_shipping_form select {
   font-family: Arial;
   font-size: 9pt;
   width: 275px;
}

td.billingform-divider {
   font-weight: bold;
   /*  background-color: #efefef; */
}


/*--------------------------------------------------------
 prod_cust_invoice.inc
--------------------------------------------------------*/
.row-normalbg { background-color: #fff; }
.row-altbg { background-color: #efefef; }
div#edit_cart_option {
   margin: 5px;
   font-size: 105%;
   text-align: left;
}
div#edit_cart_option a { font-weight: bold; }


#shopping_module th,#searchcolumn-items_in_cart {
	background-color: transparent;
	/*color:inherit!important;*/
}
#seach-column-main,#searchcolumn-login_or_date {
	border:0px!important;
	width:100%;
}
#searchcolumn {
	width:150px;
	align:center;
	text-align:left;
}



.cartText {
	padding:4px;
}
<?php
# CUSTOM TEMPLATE CSS: Use CSS file included with template? Include after, inherit new stuff
$shopping_cart_css_file = $template_fullpath."/shopping_cart.css";
if ( file_exists($shopping_cart_css_file) ) {
   include($shopping_cart_css_file);
   $module_css=str_replace('#CONTENT#', '#CONTENT #', $module_css);
}

$module_css .= ob_get_contents();
ob_end_clean();
$module_css=str_replace('#CONTENT#', '#CONTENT #', $module_css);
//$module_css .= "</style>\n";
echo $module_css;
?>