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

session_start();
require_once("../../../includes/product_gui.php");
$offline_notice = "Please note that applicable shipping charges are not included in the total shown below. ";
$offline_notice .= "Your final invoice, including shipping costs, will be emailed to you shortly after you complete your purchase. ";
$offline_notice .= "Once you have approved the final total, your order will be processed immediately.";
$check_table = mysql_query("select * from cart_shipping_opts");
if(mysql_num_rows($check_table) == 0){
	mysql_query("drop table cart_shipping_opts");
	create_table('cart_shipping_opts');
	$qry = "INSERT INTO cart_shipping_opts (SHIP_METHOD, ST_GTHAN1, NOTICE, order_type)";
	$qry_vals_local = " VALUES('Standard', '0.01', '".$offline_notice."', 'local')";
	$qry_vals_intl = " VALUES('Standard', '0.01', '".$offline_notice."', 'intl')";
	mysql_query($qry.$qry_vals_local);
	mysql_query($qry.$qry_vals_intl);
}

if(!table_exists('cart_shipping_opts')){
	create_table('cart_shipping_opts');
	$qry = "INSERT INTO cart_shipping_opts (SHIP_METHOD, ST_GTHAN1, NOTICE, order_type)";
	$qry_vals_local = " VALUES('Standard', '0.01', '".$offline_notice."', 'local')";
	$qry_vals_intl = " VALUES('Standard', '0.01', '".$offline_notice."', 'intl')";
	mysql_query($qry.$qry_vals_local);
	mysql_query($qry.$qry_vals_intl);
}

error_reporting(0);	// Turn on so that SQL error does not appear.  This is normal because if the dbtable has
				// not been created on first run, an error will show while trying to read settings data


# Restore price variation arrays
	$THIS_DISPLAY2 .= "<script language=\"JavaScript\"> \n";
	$THIS_DISPLAY2 .= "var countenstuff=Number(0); \n";

	$THIS_DISPLAY2 .= "function showdivlayerz(divnum) { \n";

//	$THIS_DISPLAY2 .= "  alert( document.getElementById(min_qty7).value ) \n";
	$THIS_DISPLAY2 .= "	var divnum = Number(divnum)+Number(countenstuff); \n";
	//$THIS_DISPLAY2 .= "	alert(divnum); \n";
	$THIS_DISPLAY2 .= "	var divnumo = Number(divnum); \n";
	$THIS_DISPLAY2 .= "	var divnumo = Number(divnumo) - 1; \n";

	$THIS_DISPLAY2 .= "	var min_qtyzo=\"min_qty\"+divnumo; \n";
	$THIS_DISPLAY2 .= "	var max_qtyzo=\"max_qty\"+divnumo; \n";
	//$THIS_DISPLAY2 .= "	alert(document.getElementById(min_qtyzo).value); \n";
	$THIS_DISPLAY2 .= "	var newmaxval = Number(document.getElementById(min_qtyzo).value) + 1; \n";
	$THIS_DISPLAY2 .= "  document.getElementById(max_qtyzo).value = newmaxval; \n";

	$THIS_DISPLAY2 .= "  document.getElementById(max_qtyzo).disabled = false; \n";

	//$THIS_DISPLAY2 .= "	alert(divnumo); \n";
	$THIS_DISPLAY2 .= "	var min_qtyz=\"min_qty\"+divnum; \n";
	$THIS_DISPLAY2 .= "	var discz=\"disc\"+divnum; \n";
	$THIS_DISPLAY2 .= "	var max_qtyz=\"max_qty\"+divnum; \n";
	$THIS_DISPLAY2 .= "	var divname=\"qtyfield\"+divnum; \n";


	$THIS_DISPLAY2 .= "	var newnewmaxval = Number(newmaxval) + 1; \n";
	$THIS_DISPLAY2 .= "  document.getElementById(min_qtyz).value=newnewmaxval;  \n";

	$THIS_DISPLAY2 .= "  document.getElementById(divname).style.display='block'; \n";
	$THIS_DISPLAY2 .= "  document.getElementById(min_qtyz).disabled = false; \n";
	$THIS_DISPLAY2 .= "  document.getElementById(max_qtyz).disabled = true; \n";
	$THIS_DISPLAY2 .= "  document.getElementById(discz).disabled = false; \n";
	$THIS_DISPLAY2 .= "countenstuff++; \n";

	$THIS_DISPLAY2 .= "} \n";


	$THIS_DISPLAY2 .= "function removedivlayerz(divrem) { \n";
	$THIS_DISPLAY2 .= "	var dcount = Number(divrem) + Number(countenstuff); \n";
	$THIS_DISPLAY2 .= "	var divrem = Number(divrem); \n";
	$THIS_DISPLAY2 .= "	if(dcount > 1) { \n";
	$THIS_DISPLAY2 .= "		countenstuff--; \n";

	$THIS_DISPLAY2 .= "		var divrem = Number(divrem)+Number(countenstuff); \n";

	$THIS_DISPLAY2 .= "		var min_qtyzz=\"min_qty\"+divrem; \n";
	$THIS_DISPLAY2 .= "		var max_qtyzz=\"max_qty\"+divrem; \n";
	$THIS_DISPLAY2 .= "		var divnamez=\"qtyfield\"+divrem; \n";
	$THIS_DISPLAY2 .= "		var disczz=\"disc\"+divrem; \n";
	$THIS_DISPLAY2 .= "		var divremnewmax = Number(divrem) - 1; \n";
	$THIS_DISPLAY2 .= "		var newmax=\"max_qty\"+divremnewmax; \n";
	$THIS_DISPLAY2 .= "		document.getElementById(newmax).value = 'or more'; \n";
	$THIS_DISPLAY2 .= "		document.getElementById(newmax).disabled = true; \n";

	$THIS_DISPLAY2 .= "		document.getElementById(divnamez).style.display='none'; \n";
	$THIS_DISPLAY2 .= "		document.getElementById(min_qtyzz).disabled = true; \n";
	$THIS_DISPLAY2 .= "		document.getElementById(max_qtyzz).disabled = true; \n";
	$THIS_DISPLAY2 .= "		document.getElementById(disczz).disabled = true; \n";
	$THIS_DISPLAY2 .= "	} \n";
	$THIS_DISPLAY2 .= "} \n";


	$THIS_DISPLAY2 .= "function decimal(num) { \n";
	$THIS_DISPLAY2 .= "	string = \"\" + num; \n";
	$THIS_DISPLAY2 .= "	if (string.indexOf('.') == -1) \n";
	$THIS_DISPLAY2 .= "	return string + '.00'; \n";
	$THIS_DISPLAY2 .= "	seperation = string.length - string.indexOf('.'); \n";
	$THIS_DISPLAY2 .= "	if (seperation > 3) \n";
	$THIS_DISPLAY2 .= "	return string.substring(0,string.length-seperation+3); \n";
	$THIS_DISPLAY2 .= "	else if (seperation == 2) \n";
	$THIS_DISPLAY2 .= "	return string + '0'; \n";
	$THIS_DISPLAY2 .= "	return string; \n";
	$THIS_DISPLAY2 .= "} \n";


	$THIS_DISPLAY2 .= "function fix_min(fnum,lastmax) { \n";
	//$THIS_DISPLAY2 .= "	alert(fnum);\n";
	//$THIS_DISPLAY2 .= "	var lastmax = Number(lastmax) + 1; \n";
	$THIS_DISPLAY2 .= "	var lastmin = decimal(Number(lastmax)); \n";
	$THIS_DISPLAY2 .= "	var lastmax = decimal(Number(lastmax) - 0.01); \n";
	$THIS_DISPLAY2 .= "	var fnum = Number(fnum); \n";
	$THIS_DISPLAY2 .= "	var fminnum = Number(fnum) + 1; \n";
	$THIS_DISPLAY2 .= "	var min_qtyval=\"min_qty\"+fminnum; \n";
	$THIS_DISPLAY2 .= "	var divname=\"qtyfield\"+fnum; \n";
	$THIS_DISPLAY2 .= "	var max_qtyval=\"max_qty\"+fnum; \n";
	$THIS_DISPLAY2 .= "  if(lastmin <= 0){ \n";
	$THIS_DISPLAY2 .= "  	document.getElementById(max_qtyval).value = ''; \n";
	$THIS_DISPLAY2 .= "  	return false; \n";
	$THIS_DISPLAY2 .= "  }\n";
	$THIS_DISPLAY2 .= "  document.getElementById(max_qtyval).value = lastmin; \n";
	$THIS_DISPLAY2 .= "  document.getElementById(min_qtyval).value = lastmax; \n";
	$THIS_DISPLAY2 .= "} \n";

	$THIS_DISPLAY2 .= "function fix_max(fnum,lastmax) { \n";
	//$THIS_DISPLAY2 .= "	alert(fnum);\n";

	$THIS_DISPLAY2 .= "	var lastmin = decimal(Number(lastmax)); \n";
	$THIS_DISPLAY2 .= "	var lastmax = decimal(Number(lastmax) + 0.01); \n";
	$THIS_DISPLAY2 .= "	var fnum = Number(fnum); \n";
	$THIS_DISPLAY2 .= "	var fminnum = Number(fnum) - 1; \n";
	$THIS_DISPLAY2 .= "	var max_qtyval=\"max_qty\"+fminnum; \n";
	$THIS_DISPLAY2 .= "	var min_qtyval=\"min_qty\"+fnum; \n";
	$THIS_DISPLAY2 .= "	var divname=\"qtyfield\"+fnum; \n";

	$THIS_DISPLAY2 .= "  if(lastmin <= 0){ \n";
	$THIS_DISPLAY2 .= "  	document.getElementById(min_qtyval).value = ''; \n";
	$THIS_DISPLAY2 .= "  	return false; \n";
	$THIS_DISPLAY2 .= "  }\n";

	$THIS_DISPLAY2 .= "  document.getElementById(min_qtyval).value = lastmin; \n";
	$THIS_DISPLAY2 .= "  document.getElementById(max_qtyval).value = lastmax; \n";
	$THIS_DISPLAY2 .= "} \n";

	$THIS_DISPLAY2 .= "</script>";

	echo $THIS_DISPLAY2;
#######################################################
### DO SAVE ACTION HERE     				             ###
#######################################################

# set_country
if ( $_POST['todo'] == "set_country" ) {
   $qry = "update cart_shipping_opts set local_country = '".$_POST['country']."'";
   mysql_query($qry);
}

# unset_country
if ( $_GET['todo'] == "unset_country" ) {
   $qry = "update cart_shipping_opts set local_country = ''";
   mysql_query($qry);
}

// ----------------------------------------------------------
// IF THE 'cart_shipping_opts' TABLE DOES NOT EXIST; CREATE
// ----------------------------------------------------------
$tablename = "cart_shipping_opts";
//	mysql_query("DROP TABLE cart_shipping_opts");
if ( !table_exists($tablename) ) {

   # Create table qry
   $qry = "CREATE TABLE cart_shipping_opts";
   $qry .= " (SHIP_METHOD VARCHAR(50)";

   # Account for 8 subtotal break points
   for ( $x = 1; $x <= 8; $x++ ) {
      $qry .= ", ST_GTHAN".$x." VARCHAR(50)";
      $qry .= ", ST_LTHAN".$x." VARCHAR(50)";
      $qry .= ", ST_SHIP".$x." VARCHAR(50)";
   }

   $qry .= ", INC_FILENAME VARCHAR(50)";
   $qry .= ", NOTICE BLOB";
   $qry .= ", order_type VARCHAR(50)";
   $qry .= ", local_country VARCHAR(255)";
   $qry .= ")";

	if ( !mysql_db_query($db_name, $qry) ) { echo "Could not create shipping options table.<br/>".mysql_error(); exit; }

	# Insert default data
	$offline_notice = "Please note that applicable shipping charges are not included in the total shown below. Your final invoice, including shipping costs, will be emailed to you shortly after you complete your purchase. Once you have approved the final total, your order will be processed immediately.";
	$qry = "INSERT INTO cart_shipping_opts (SHIP_METHOD, ST_GTHAN1, NOTICE, order_type)";
	$qry_vals_local = " VALUES('Standard', '0.01', '".$offline_notice."', 'local')";
	$qry_vals_intl = " VALUES('Standard', '0.01', '".$offline_notice."', 'intl')";
	mysql_query($qry.$qry_vals_local);
	mysql_query($qry.$qry_vals_intl);
}

if ($ACTION == "SAVE") {

//   echo "<div style=\"overflow: auto;height: 500px;\">".testArray($_POST)."</div>\n";

	# Update record data
	$qry = "UPDATE cart_shipping_opts SET ";
	$qry .= " SHIP_METHOD = '".$_POST['ship_method']."'";

	# Account for 8 subtotal break points
	for ( $x = 1; $x <= 8; $x++ ) {
   	$qry .= ", ST_GTHAN".$x." = '".$_POST['st_gthan'.$x]."'";
   	$qry .= ", ST_LTHAN".$x." = '".$_POST['st_lthan'.$x]."'";
   	$qry .= ", ST_SHIP".$x." = '".$_POST['st_ship'.$x]."'";
   }

   $qry .= ", INC_FILENAME = '".$_POST['inc_filename']."'";
   $qry .= ", NOTICE = '".$_POST['offline_notice']."'";

   $qry .= " WHERE order_type = '".$_POST['order_type']."'";
   mysql_query($qry);


	// ----------------------------------------------------------
	// Populate table with data from current settings
	// ----------------------------------------------------------
	$qry = "INSERT INTO cart_shipping_opts VALUES($SQL_SAVE)";
//	echo $qry; exit;
//	mysql_query($qry);

	$update_complete = 1;

} // End Save Action

if($ACTION == "SAVE" && $_POST['ship_method'] == 'UPS'){
	$upspref = new userdata("UPS_Shipping_Options");
	$upspref->set("username", $_POST['ups_username']);
	$upspref->set("password", $_POST['ups_password']);
	$upspref->set("bulk", $_POST['ups_bulk']);
	
}



#######################################################
### START HTML/JAVASCRIPT CODE			    ###
#######################################################
$upspref = new userdata("UPS_Shipping_Options");
$upsusername = $upspref->get("username");
$upspassword = $upspref->get("password");
$upsbulk = $upspref->get("bulk");



$MOD_TITLE = lang("Shopping Cart: Shipping Options");

######################################################
### READ ANY .INC or .PHP FILES INTO MEMORY; AT THIS
### POINT THERE IS NO SECURITY ON THESE FILES BECAUSE
### THEY SIMPLY EXIST ON THE SERVER IN A STANDARD
### PORT :80 ACCESSIBLE DIRECTORY.  IF SOMEONE WANTED
### TO MODIFY UPLOAD FILES TO PLACE FILES INTO A
### DATABASE; THAT WOULD SOLVE THAT PROBLEM.
### I JUST DID NOT DO IT IN THE INITIAL DESIGN.
###
### WHILE WE'RE HERE, MIGHT AS WELL POPULATE THE
### SELECTION BOX FOR CUSTOM FORM ATTACHMENT AS WELL.
######################################################

$inc_file = "     <OPTION VALUE=\"\">N/A</OPTION>\n";

$count = 0;
$directory = "$doc_root/media";
if (is_dir($directory)) {
$handle = opendir("$directory");
	while ($files = readdir($handle)) {
		if (strlen($files) > 2) {
			if (eregi(".inc", $files) || eregi(".php", $files)) {
				$count++;
				$tmp = "$directory/$files";
				$tmp_space = filesize($tmp);
				$tmp_srt = ucwords($files);
				$site_file[$count] = $tmp_srt . "~~~media~~~$tmp_space~~~" . $files;
			}
		}
	}
closedir($handle);
}

if ($count > 1) { sort($site_file); };
$file_count = count($site_file);

for ($x=0;$x<=$file_count;$x++) {

		$tmp = split("~~~", $site_file[$x]);
		$filename = $tmp[3];
		$filesize = $tmp[2];
		$filedir = $tmp[1];

		if (strlen($filename) > 2) {

			// -----------------------------------------
			// Calculate "Human" Filesize for display
			// -----------------------------------------

			if ($filesize >= 1048576) {
				$filesize = round($filesize/1048576*100)/100;
				$filesize = $filesize . "&nbsp;Mb";
			 } elseif ($filesize >= 1024) {
				$filesize = round($filesize/1024*100)/100;
				$filesize = $filesize . "&nbsp;K";
			 } else {
				$filesize = $filesize . "&nbsp;Bytes";
			 }

			$inc_file .= "     <OPTION VALUE=\"$filename\">$filename [$filesize]</OPTION>\n";

		}

}

##############################################################
### READ CURRENT SETTINGS INTO MEMORY NOW
##############################################################

$result = mysql_query("SELECT * FROM cart_shipping_opts WHERE order_type = 'local'");
$OPTIONS = mysql_fetch_array ($result);

$result = mysql_query("SELECT * FROM cart_shipping_opts WHERE order_type = 'intl'");
$getIntl = mysql_fetch_array ($result);

ob_start();
?>
<script language="javascript">

function show_sel(what, type) {

	document.getElementById(type+'-SubTotal').style.display = 'none';
	document.getElementById(type+'-UPS').style.display = 'none';
	document.getElementById(type+'-Standard').style.display = 'none';
	document.getElementById(type+'-Custom').style.display = 'none';
	document.getElementById(type+'-Offline').style.display = 'none';

   show_idname = type+'-'+what;
	document.getElementById(show_idname).style.display = '';
}



<?

if ($update_complete == 1) {

	echo ("alert('Your shipping options have been updated.');\n");

}

?>
</script>

<style>
input {
   font-size: 10px;
   font-family: Trebuchet MS, arial;
}
</style>
<!---Module heading--->
<table border="0" align="center" cellspacing="0" class="module_container" style="border: 0px;">
 <tr>
  <td valign="top" class="module_body_area" style="border-right: 1px dashed #ccc;width: 50%;">
<?
$THIS_DISPLAY = "";


/*---------------------------------------------------------------------------------------------------------*
 _                    _
| |    ___  __  __ _ | |
| |__ / _ \/ _|/ _` || |
|____|\___/\__|\__,_||_|

# Set shipping calculation method for local orders
/*---------------------------------------------------------------------------------------------------------*/
$THIS_DISPLAY .= "   <h2>Local Orders</h2>\n";

if ( $OPTIONS['local_country'] != "" ) {
   $THIS_DISPLAY .= "   <p class=\"nomar_top\">\n";
   $THIS_DISPLAY .= "    Applies to orders shipped to your local country...<br/>\n";
   $THIS_DISPLAY .= "    ".$getIntl['local_country']."</p>\n";
   $THIS_DISPLAY .= "   <p><a href=\"".$_SERVER['PHP_SELF']."?todo=unset_country\" class=\"del\">Un-set local country</a> \n";
   $THIS_DISPLAY .= "    | <a href=\"#\" onclick=\"open_country_popup();\">Change local country</a> \n";
//   $THIS_DISPLAY .= "    to unset your local country and use settings for Local Orders on all orders no matter what country they ship to.\n";
   $THIS_DISPLAY .= "   </p>\n";
}

$THIS_DISPLAY .= "<FORM NAME=\"SHIP_local\" METHOD=POST ACTION=\"shipping_options.php\">";
$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=ACTION VALUE=\"SAVE\">";
$THIS_DISPLAY .= "<input type=\"hidden\" name=\"order_type\" value=\"local\">";
$THIS_DISPLAY .= "<table border=0 cellpadding=5 cellspacing=0>\n";
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=left valign=middle>\n\n";
$THIS_DISPLAY .= "   1. ".lang("Which method do you want to use to calculate shipping?")."<BR><BR>\n";
$THIS_DISPLAY .= "   <select id=\"ship_method-local\" name=\"ship_method\" style=\"width: 200px;\" onchange=\"show_sel(this.value, 'local');\">\n";
$THIS_DISPLAY .= "    <OPTION VALUE=\"Standard\">".lang("Standard Shipping (Per Sku)")."</OPTION>\n";
$THIS_DISPLAY .= "    <OPTION VALUE=\"UPS\">".lang("UPS realtime shipping")."</OPTION>\n";
$THIS_DISPLAY .= "    <option value=\"SubTotal\">".lang("Charge By Order Sub-Total")."</option>\n";
$THIS_DISPLAY .= "    <OPTION VALUE=\"Custom\">".lang("Use Custom PHP Include")."</OPTION>\n";
$THIS_DISPLAY .= "    <OPTION VALUE=\"Offline\">".lang("Offline/Manual Calculation")."</OPTION>\n";
eval(hook("shipping_options.php:ship-method-local"));
$THIS_DISPLAY .= "   </SELECT>\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n";


// =============================================================================
// Show "Ship by Sub-Total" calculation table
// =============================================================================
$THIS_DISPLAY .= "<DIV ID=\"local-SubTotal\" STYLE=\"display: none;padding-left: 5px;\" CLASS=text>\n";
$THIS_DISPLAY .= "<BR><BR>2. ".lang("If order sub-total is...")."<BR><BR>\n";
$THIS_DISPLAY .= "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
$THIS_DISPLAY .= " <tr> \n";
$THIS_DISPLAY .= "  <td class=\"col_title\">".lang("Greater than")."...</TD>\n";
$THIS_DISPLAY .= "  <td class=\"col_title\">".lang("And less than")."...</TD>\n";
$THIS_DISPLAY .= "  <td class=\"col_title\">".lang("Shipping price is")."...</TD>\n";
$THIS_DISPLAY .= " </tr>\n";


$discnums = 8;

for ($x=1;$x<=8;$x++) {



   $tmp = "ST_GTHAN" . $x;
   $one = $OPTIONS[$tmp];
   $tmp = "ST_LTHAN" . $x;
   $two = $OPTIONS[$tmp];
   $tmp = "ST_SHIP" . $x;
   $three = $OPTIONS[$tmp];


		 $THIS_DISPLAY .= "<div id=\"qtyfield".$x."\" name=\"qtyfield".$x."\" style=\"display:block;\">\n";
	   if ($x == 1) { $one = "0.01\" style=\"background-color: #efefef;width: 50px;\" disabled><input type=hidden name=st_gthan1 value=\"0.01"; }
	   $THIS_DISPLAY .= " <tr>\n";
	   $THIS_DISPLAY .= "  <td class=\"text\">$ \n";
	   $THIS_DISPLAY .= "   <input id=\"min_qty".$x."\" type=\"text\" name=\"st_gthan".$x."\" maxlength=\"10\" value=\"".$one."\" style=\"width: 50px;\" onblur=\"fix_max('".$x."',document.getElementById('min_qty".$x."').value);\">\n";
	   $THIS_DISPLAY .= "  </td>\n";
	   $THIS_DISPLAY .= "  <td class=\"text\">$ \n";
	   $THIS_DISPLAY .= "   <input id=\"max_qty".$x."\" type=\"text\" name=\"st_lthan".$x."\" maxlength=\"10\" value=\"".$two."\" style=\"width: 50px;\" onblur=\"fix_min('".$x."',document.getElementById('max_qty".$x."').value);\">\n";
	   $THIS_DISPLAY .= "  </td>\n";
	   $THIS_DISPLAY .= "  <td class=\"text\">$ \n";
	   $THIS_DISPLAY .= "   <input id=\"disc".$x."\" type=\"text\" name=\"st_ship".$x."\" maxlength=\"10\" value=\"".$three."\" style=\"width: 50px;\">\n";
	   $THIS_DISPLAY .= "  </td>\n";
	   $THIS_DISPLAY .= " </tr>\n";


}


$THIS_DISPLAY .= "</table>\n";
$THIS_DISPLAY .= "</div>\n";


# UPS
$THIS_DISPLAY .= "<div id=\"local-UPS\" style=\"padding: 5px; display: none;\">\n";
$THIS_DISPLAY .= " <p><b>UPS realtime shipping</b> is calculated using the UPS's realtime shipping rate API.  To use this feature you must sign up for a UPS account <a href=\"https://www.ups.com/one-to-one/register\" target=\"_BLANK\">here</a> . You must also enter the weight for each item. </p>\n";
$THIS_DISPLAY .= "<p>UPS Account Username: <input type=\"text\" name=\"ups_username\" value=\"".$upsusername."\"></p>\n";
$THIS_DISPLAY .= "<p>UPS Account Password: <input type=\"text\" name=\"ups_password\" value=\"".$upspassword."\"></p>\n";

if($upsbulk!='no'){
	$THIS_DISPLAY .= "<p>Bulk Packaging: <select name=\"ups_bulk\">\n<option value=\"yes\" SELECTED>Yes</option>\n<option value=\"no\">No</option>\n</select>\n</p>\n";
} else {
	$THIS_DISPLAY .= "<p>Bulk Packaging: <select name=\"ups_bulk\">\n<option value=\"yes\">Yes</option>\n<option value=\"no\" SELECTED>No</option>\n</select>\n</p>\n";	
}

$THIS_DISPLAY .= "</div>\n";
# Standard
$THIS_DISPLAY .= "<div id=\"local-Standard\" style=\"padding: 5px;\">\n";
$THIS_DISPLAY .= " <p><b>Standard shipping</b> is calculated using the \"Shipping Charge (A)\" field when entering a new sku. \n";
$THIS_DISPLAY .= " Whatever value is present in this field (price) will be multiplied by the QTY of the sku purchased and added together to produce a shipping total for the order.</p>\n";
$THIS_DISPLAY .= "</div>\n";


// =============================================================================
// Show Custom PHP Include selection
// =============================================================================
$THIS_DISPLAY .= "<DIV ID=\"local-Custom\" STYLE='display: none;' CLASS=text>\n";
$THIS_DISPLAY .= "<BR><BR>\n";
$THIS_DISPLAY .= "<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=3>\n";
$THIS_DISPLAY .= " <TR>\n";
$THIS_DISPLAY .= "  <TD CLASS=text>2. Select the PHP include file to use for shipping calculation.<BR><BR>\n";
$THIS_DISPLAY .= "   <SELECT NAME=\"inc_filename\" CLASS=text STYLE='width: 300px;'>\n";
$THIS_DISPLAY .= $inc_file;
$THIS_DISPLAY .= "   </SELECT>\n";
$THIS_DISPLAY .= "   <BR><BR>\n";
$THIS_DISPLAY .= "   You may need to modify and upload the include file via file upload files first.\n";
$THIS_DISPLAY .= "   Make sure you check and configure any third-party includes to confirm operation and that they do not contain malicious code.\n";
$THIS_DISPLAY .= "  </TD>\n";
$THIS_DISPLAY .= " </TR>\n";
$THIS_DISPLAY .= "</TABLE>\n";
$THIS_DISPLAY .= "</DIV>\n";


// =============================================================================
// Show Offline/Manual Calculation
// =============================================================================

// v4.7 RC3 -- Default invoice notice for 'Offline/Manual Calculation'
if ( $OPTIONS[NOTICE] == "") {
   $offline_notice = "Please note that applicable shipping charges are not included in the total shown below. ";
   $offline_notice .= "Your final invoice, including shipping costs, will be emailed to you shortly after you complete your purchase. ";
   $offline_notice .= "Once you have approved the final total, your order will be processed immediately.";
} else {
   $offline_notice = $OPTIONS[NOTICE];
}

$THIS_DISPLAY .= "<DIV ID=\"local-Offline\" STYLE='display: none;'>\n";
$THIS_DISPLAY .= "<table border=0 cellspacing=0 cellpadding=\"5\">\n";
$THIS_DISPLAY .= " <TR>\n";
$THIS_DISPLAY .= "  <TD>\n";
$THIS_DISPLAY .= "   <b>Offline/Manual Calculation</b> means that no shipping calculation is performed during the checkout process, but customer is notified on initial invoice that\n";
$THIS_DISPLAY .= "   final total including shipping charges will be emailed to them for approval before their order is processed.\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n";
$THIS_DISPLAY .= "<table border=0 cellspacing=0 cellpadding=5>\n";
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td>2. Format notification to customers regarding shipping cost calculation.<BR><BR>\n";
$THIS_DISPLAY .= "   <textarea name=\"offline_notice\" class=\"text\" style=\"font-size: 11px; width: 100%; height:90px; overflow:auto;\">\n";
$THIS_DISPLAY .= $offline_notice;
$THIS_DISPLAY .= "</textarea>\n";
$THIS_DISPLAY .= "  </TD>\n";
$THIS_DISPLAY .= " </TR>\n";
$THIS_DISPLAY .= "</TABLE>\n";
$THIS_DISPLAY .= "</DIV>\n";

# Save button
$THIS_DISPLAY .= "   <p style=\"text-align: center;\">\n";
$THIS_DISPLAY .= "    <button type=\"submit\" class=\"greenButton\" onclick=\"document.SHIP_local.submit();\"><span><span>".lang("Save Settings for Local Orders")." &gt;&gt;</span></span></button></p>\n";

$THIS_DISPLAY .= "</FORM>\n";
$THIS_DISPLAY .= "  </td>\n";


/*---------------------------------------------------------------------------------------------------------*
 ___       _                          _    _                   _
|_ _| _ _ | |_  ___  _ _  _ _   __ _ | |_ (_) ___  _ _   __ _ | |
 | | | ' \|  _|/ -_)| '_|| ' \ / _` ||  _|| |/ _ \| ' \ / _` || |
|___||_||_|\__|\___||_|  |_||_|\__,_| \__||_|\___/|_||_|\__,_||_|

# International orders
/*---------------------------------------------------------------------------------------------------------*/



$THIS_DISPLAY .= "  <td valign=\"top\" class=\"module_body_area\">\n";
$THIS_DISPLAY .= "   <h2>International Orders</h2>\n";

if ( $OPTIONS['local_country'] != "" ) {
   $THIS_DISPLAY .= "   <p class=\"nomar_top\">\n";
   $THIS_DISPLAY .= "    Applies to orders shipped to countries <i>other than</i> your local country<br/>\n";
   $THIS_DISPLAY .= "    (".$getIntl['local_country'].")</p>\n";
   $THIS_DISPLAY .= "   <p><a href=\"".$_SERVER['PHP_SELF']."?todo=unset_country\" class=\"del\">Un-set local country</a> \n";
   $THIS_DISPLAY .= "    | <a href=\"#\" onclick=\"open_country_popup();\">Change local country</a> \n";
//   $THIS_DISPLAY .= "    to unset your local country and use \n";
   $THIS_DISPLAY .= "   </p>\n";
}

# Show big clickable div, or display options?
if ( $OPTIONS['local_country'] == "" ) {
   $THIS_DISPLAY .= "   <div id=\"setlocal_btn\" onclick=\"open_country_popup();\" class=\"hand bg_blue_df\" style=\"padding: 10px; border: 1px dashed #ccc;\" onmouseover=\"setClass('setlocal_btn', 'bg_yellow hand');\" onmouseout=\"setClass('setlocal_btn', 'bg_blue_df hand');\">\n";
   $THIS_DISPLAY .= "    If you want to do something different for international orders, click here to set your local country.\n";
   $THIS_DISPLAY .= "    Otherwise, your settings for Local Orders will apply to all orders no matter what country they ship to.\n";
   $THIS_DISPLAY .= "   </div>\n";


} else {
   # Local country set, show international shipping options
   $THIS_DISPLAY .= "<form name=\"SHIP_intl\" method=\"post\" action=\"shipping_options.php\">";
   $THIS_DISPLAY .= "<input type=\"hidden\" name=\"ACTION\" value=\"SAVE\">";
   $THIS_DISPLAY .= "<input type=\"hidden\" name=\"order_type\" value=\"intl\">";
   $THIS_DISPLAY .= "<table border=0 cellpadding=5 cellspacing=0>\n";
   $THIS_DISPLAY .= " <tr>\n";
   $THIS_DISPLAY .= "  <td align=left valign=middle>\n\n";
   $THIS_DISPLAY .= "   1. ".lang("Which method do you want to use to calculate shipping?")."<BR><BR>\n";
//   $THIS_DISPLAY .= "   <SELECT NAME=\"ship_method\" CLASS=text style=\"width: 200px;\" onchange=\"show_sel(this.value, 'intl');\">\n";
   $THIS_DISPLAY .= "   <select id=\"ship_method-intl\" name=\"ship_method\" style=\"width: 200px;\" onchange=\"show_sel(this.value, 'intl');\">\n";
   $THIS_DISPLAY .= "    <OPTION VALUE=\"Standard\">".lang("Standard Shipping (Per Sku)")."</OPTION>\n";
   $THIS_DISPLAY .= "    <OPTION VALUE=\"UPS\">".lang("UPS realtime shipping")."</OPTION>\n";
   $THIS_DISPLAY .= "    <OPTION VALUE=\"SubTotal\">".lang("Charge By Order Sub-Total")."</OPTION>\n";
   $THIS_DISPLAY .= "    <OPTION VALUE=\"Custom\">".lang("Use Custom PHP Include")."</OPTION>\n";
   $THIS_DISPLAY .= "    <OPTION VALUE=\"Offline\">".lang("Offline/Manual Calculation")."</OPTION>\n";
eval(hook("shipping_options.php:ship-method-intl"));
   $THIS_DISPLAY .= "   </SELECT>\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= " </tr>\n";
   $THIS_DISPLAY .= "</table>\n";


   // =============================================================================
   // Show "Ship by Sub-Total" calculation table
   // =============================================================================
   $THIS_DISPLAY .= "<DIV ID=\"intl-SubTotal\" STYLE=\"display: none;padding-left: 5px;\" CLASS=text>\n";
   $THIS_DISPLAY .= "<BR><BR>2. ".lang("If order sub-total is...")."<BR><BR>\n";
   $THIS_DISPLAY .= "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
   $THIS_DISPLAY .= " <tr> \n";
   $THIS_DISPLAY .= "  <td class=\"col_title\">".lang("Greater than")."...</TD>\n";
   $THIS_DISPLAY .= "  <td class=\"col_title\">".lang("And less than")."...</TD>\n";
   $THIS_DISPLAY .= "  <td class=\"col_title\">".lang("Shipping price is")."...</TD>\n";
   $THIS_DISPLAY .= " </tr>\n";

   for ($x=1;$x<=8;$x++) {

      $tmp = "ST_GTHAN" . $x;
      $one = $getIntl[$tmp];
      $tmp = "ST_LTHAN" . $x;
      $two = $getIntl[$tmp];
      $tmp = "ST_SHIP" . $x;
      $three = $getIntl[$tmp];


      if ($x == 1) { $one = "0.01\" style=\"background-color: #efefef;width: 50px;\" disabled><input type=hidden name=st_gthan1 value=\"0.01"; }

      $THIS_DISPLAY .= "<TR>\n";
      $THIS_DISPLAY .= "<TD CLASS=text>$ \n";
      $THIS_DISPLAY .= "<INPUT id=\"min_qty".($x+8)."\" TYPE=text NAME=\"st_gthan$x\" maxlength=10 value=\"$one\" style=\"width: 50px;\" onblur=\"fix_max('".($x+8)."',document.getElementById('min_qty".($x+8)."').value);\">\n";
      $THIS_DISPLAY .= "</TD>\n";
      $THIS_DISPLAY .= "<TD CLASS=text>$ \n";
      $THIS_DISPLAY .= "<INPUT id=\"max_qty".($x+8)."\" TYPE=text NAME=\"st_lthan$x\" maxlength=10 value=\"$two\" style=\"width: 50px;\" onblur=\"fix_min('".($x+8)."',document.getElementById('max_qty".($x+8)."').value);\">\n";
      $THIS_DISPLAY .= "</TD>\n";
      $THIS_DISPLAY .= "<TD CLASS=text>$ \n";
      $THIS_DISPLAY .= "<INPUT id=\"disc".($x+8)."\" TYPE=text NAME=\"st_ship$x\" maxlength=10 value=\"$three\" style=\"width: 50px;\">\n";
      $THIS_DISPLAY .= "</TD>\n";
      $THIS_DISPLAY .= "</TR>\n";

   }

   $THIS_DISPLAY .= "</TABLE>\n";
   $THIS_DISPLAY .= "</DIV>\n";

	#UPS
	$THIS_DISPLAY .= "<div id=\"intl-UPS\" style=\"display: none; padding: 5px;\">\n";
	$THIS_DISPLAY .= " <p><b>UPS realtime shipping</b> is calculated using the UPS's realtime shipping rate API.  To use this feature you must sign up for a UPS account <a href=\"https://www.ups.com/one-to-one/register\" target=\"_BLANK\">here</a> . You must also enter the weight for each item. </p>\n";
	$THIS_DISPLAY .= "<p>UPS Account Username: <input type=\"text\" name=\"ups_username\" value=\"".$upsusername."\"></p>\n";
	$THIS_DISPLAY .= "<p>UPS Account Password: <input type=\"text\" name=\"ups_password\" value=\"".$upspassword."\"></p>\n";
	if($upsbulk!='no'){
		$THIS_DISPLAY .= "<p>Bulk Packaging: <select name=\"ups_bulk\">\n<option value=\"yes\" SELECTED>Yes</option>\n<option value=\"no\">No</option>\n</select>\n</p>\n";
	} else {
		$THIS_DISPLAY .= "<p>Bulk Packaging: <select name=\"ups_bulk\">\n<option value=\"yes\">Yes</option>\n<option value=\"no\" SELECTED>No</option>\n</select>\n</p>\n";	
	}

	$THIS_DISPLAY .= "</div>\n";

   # Standard
   $THIS_DISPLAY .= "<div id=\"intl-Standard\" style=\"padding: 5px;\">\n";
   $THIS_DISPLAY .= " <p><b>Standard shipping</b> is calculated using the \"Shipping Charge (A)\" field when entering a new sku. \n";
   $THIS_DISPLAY .= " Whatever value is present in this field (price) will be multiplied by the QTY of the sku purchased and added together to produce a shipping total for the order.</p>\n";
   $THIS_DISPLAY .= "</div>\n";


   // =============================================================================
   // Show Custom PHP Include selection
   // =============================================================================
   $THIS_DISPLAY .= "<DIV ID=\"intl-Custom\" STYLE='display: none;' CLASS=text>\n";
   $THIS_DISPLAY .= "<BR><BR>\n";
   $THIS_DISPLAY .= "<table border=0 cellspacing=0 cellpadding=5>\n";
   $THIS_DISPLAY .= " <TR>\n";
   $THIS_DISPLAY .= "  <TD CLASS=text>2. Select the PHP include file to use for shipping calculation.<BR><BR>\n";
   $THIS_DISPLAY .= "   <SELECT NAME=\"inc_filename\" CLASS=text STYLE='width: 300px;'>\n";
   $THIS_DISPLAY .= $inc_file;
   $THIS_DISPLAY .= "   </SELECT>\n";
   $THIS_DISPLAY .= "   <BR><BR>\n";
   $THIS_DISPLAY .= "   You may need to modify and upload the include file via file upload files first.\n";
   $THIS_DISPLAY .= "   Make sure you check and configure any third-party includes to confirm operation and that they do not contain malicious code.\n";
   $THIS_DISPLAY .= "  </TD>\n";
   $THIS_DISPLAY .= " </TR>\n";
   $THIS_DISPLAY .= "</TABLE>\n";
   $THIS_DISPLAY .= "</DIV>\n";


   // =============================================================================
   // Show Offline/Manual Calculation
   // =============================================================================

   // v4.7 RC3 -- Default invoice notice for 'Offline/Manual Calculation'
   if ( $getIntl['NOTICE'] == "") {
      $offline_notice = "Please note that applicable shipping charges are not included in the total shown below. ";
      $offline_notice .= "Your final invoice, including shipping costs, will be emailed to you shortly after you complete your purchase. ";
      $offline_notice .= "Once you have approved the final total, your order will be processed immediately.";
   } else {
      $offline_notice = $getIntl['NOTICE'];
   }

   $THIS_DISPLAY .= "<DIV ID=\"intl-Offline\" STYLE='display: none;'>\n";
   $THIS_DISPLAY .= "<table border=0 cellspacing=0 cellpadding=\"5\">\n";
   $THIS_DISPLAY .= " <TR>\n";
   $THIS_DISPLAY .= "  <TD>\n";
   $THIS_DISPLAY .= "   <b>Offline/Manual Calculation</b> means that no shipping calculation is performed during the checkout process, but customer is notified on initial invoice that\n";
   $THIS_DISPLAY .= "   final total including shipping charges will be emailed to them for approval before their order is processed.\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= " </tr>\n";
   $THIS_DISPLAY .= "</table>\n";
   $THIS_DISPLAY .= "<table border=0 cellspacing=0 cellpadding=5>\n";
   $THIS_DISPLAY .= " <tr>\n";
   $THIS_DISPLAY .= "  <td>2. Format notification to customers regarding shipping cost calculation.<BR><BR>\n";
   $THIS_DISPLAY .= "   <textarea name=\"offline_notice\" class=\"text\" style=\"font-size: 11px; width: 100%; height:90px; overflow:auto;\">\n";
   $THIS_DISPLAY .= $offline_notice;
   $THIS_DISPLAY .= "</textarea>\n";
   $THIS_DISPLAY .= "  </TD>\n";
   $THIS_DISPLAY .= " </TR>\n";
   $THIS_DISPLAY .= "</TABLE>\n";
   $THIS_DISPLAY .= "</DIV>\n";

   # Save button
   $THIS_DISPLAY .= "   <p style=\"text-align: center;\">\n";
   $THIS_DISPLAY .= "    <button type=\"button\" class=\"greenButton\" onclick=\"document.SHIP_intl.submit();\"><span><span>".lang("Save Settings for International Orders")." &gt;&gt;</span></span></button></p>\n";
   $THIS_DISPLAY .= "</FORM>\n";

} // End if local_country is set

$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n";


/*---------------------------------------------------------------------------------------------------------*
  ___                  _
 / __| ___  _  _  _ _ | |_  _ _  _  _   _ __  ___  _ __  _  _  _ __
| (__ / _ \| || || ' \|  _|| '_|| || | | '_ \/ _ \| '_ \| || || '_ \
 \___|\___/ \_,_||_||_|\__||_|   \_, | | .__/\___/| .__/ \_,_|| .__/
                                 |__/  |_|        |_|         |_|

# Popup to select local country to allow for different settings for international orders
/*---------------------------------------------------------------------------------------------------------*/
# popup-set_country
$popup = "";
$popup .= "<form name=\"setcountry_form\" method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">";
$popup .= "<input type=\"hidden\" name=\"todo\" value=\"set_country\"/>\n";
$popup .= "<p>When a visitor buys something from your website and chooses a 'ship to' country other than the one\n";
$popup .= "you choose here as your 'local' country, then the shipping calculation that you set up under '<b>International Orders</b>' will apply.</p>\n";

$popup .= "<p>Otherwise, if your customer chooses your local country as his own 'ship to' country, then \n";
$popup .= "the shipping charge calculation method you chose under '<b>Local Orders</b>' will apply.</p>\n";

$popup .= "<select name=\"country\">\n";

$filename = "shared/countries.dat";
$file = fopen("$filename", "r") or DIE("Error: Could not open country data (contries.dat).");
$tmp_data = fread($file,filesize($filename));
fclose($file);
$natDat = split("\n", $tmp_data);
$numNats = count($natDat);
$natNam = "";
for ($f=0; $f < $numNats; $f++) {
   $tmpSplt = split("::", $natDat[$f]);
   $natNam[$f] = $tmpSplt[0]." - ".$tmpSplt[1];
   $natNam[$f] = strtoupper($natNam[$f]);
}
for ( $c = 0; $c < $numNats; $c++ ) {
   $sel = "";
   $popup .= "   <option value='".$natNam[$c]."'".$sel.">".$natNam[$c]."</option>\n";
}
$popup .= "</select>\n";
$popup .= " <p><input type=\"button\" value=\"Set local country &gt;&gt;\" onclick=\"document.setcountry_form.submit();\"/></p>\n";
$popup .= "</form>\n";
$THIS_DISPLAY .= help_popup("popup-set_country", "Set local country", $popup, "top: 15%;left: 15%;");


//include("shared/html_build.php");
echo $THIS_DISPLAY;
####################################################################

// -------------------------------------------------
// Set Display to Show Current Shipping Settings
// -------------------------------------------------

if ( $OPTIONS[SHIP_METHOD] != "" ) {

	echo "<SCRIPT LANGUAGE=\"javascript\">\n\n";
   echo "   // For local orders\n";
	echo "	document.getElementById('local-SubTotal').style.display = 'none';\n";
	echo "	document.getElementById('local-Standard').style.display = 'none';\n";
	echo "	document.getElementById('local-Custom').style.display = 'none';\n";
	echo "   document.getElementById('local-".$OPTIONS[SHIP_METHOD]."').style.display = '';\n\n";

	echo "   document.SHIP_local.ship_method.value = '".$OPTIONS[SHIP_METHOD]."';\n";
	echo "   document.SHIP_local.inc_filename.value = '".$OPTIONS[INC_FILENAME]."';\n";

   if ( $OPTIONS['local_country'] != "" ) {
      echo "// For intl orders\n";
   	echo "document.getElementById('intl-SubTotal').style.display = 'none';\n";
   	echo "document.getElementById('intl-Standard').style.display = 'none';\n";
   	echo "document.getElementById('intl-Custom').style.display = 'none';\n";
   	echo "document.getElementById('intl-".$getIntl['SHIP_METHOD']."').style.display = '';\n\n";

   	echo "document.SHIP_intl.ship_method.value = '".$getIntl['SHIP_METHOD']."';\n";
   	echo "document.SHIP_intl.inc_filename.value = '".$getIntl['INC_FILENAME']."';\n";

   }
   echo "</SCRIPT>\n\n";

} // End if SHIP_METHOD != ""
?>
</div>

<script type="text/javascript">
// Stupid IE infinite z-index workaround
//...have to hide/show ship method drop downs when popup opens closes or the dd's show through in IE
function open_country_popup() {
   showid('popup-set_country');
   hideid('ship_method-local');
   hideid('ship_method-intl');
}
function close_country_popup() {
   hideid('popup-set_country');
   showid('ship_method-local');
   showid('ship_method-intl');
}
var closebar = document.getElementById('popup-set_country-closebar');
closebar.onclick = close_country_popup;
</script>

<?
# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("This is where you set-up how you're going to charge for shipping when people buy things from your website.");
//$instructions .= lang("Please only use alpha-numerical characters and spaces.");

# Build into standard module template
$module = new smt_module($module_html);

$module->meta_title = "Shipping Options";
$module->add_breadcrumb_link("Shopping Cart Menu", "program/modules/mods_full/shopping_cart.php");
$module->add_breadcrumb_link("Shipping Options", "program/modules/mods_full/shopping_cart/shipping_options.php");
$module->icon_img = "program/includes/images/shopping-icon-med.png";
$module->heading_text = "Shipping Options";
$module->description_text = $instructions;

# SPECIAL (for this module) - This module needs all the space it can get
$module->container_css = "margin: 0;padding: 0;";

$module->good_to_go();
?>