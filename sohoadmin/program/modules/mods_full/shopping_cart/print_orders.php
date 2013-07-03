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
error_reporting(0);
require_once("../../../includes/product_gui.php");
# Make sure session is restored (Mantis #4)
if ( strlen(lang("Order Date")) < 4 ) {
   include("includes/config-global.php"); // Re-registers all global & session info
}

# Make sure shared functions is included
//include_once($_SESSION['docroot_path']."/sohoadmin/program/includes/shared_functions.php");

# Make sure db connection is resotred -- particularly important with shared SSL users (Mantis #285)
$dbcon_inc = "../../../../includes/db_connect.php"; // Mantis #285
if ( !include($dbcon_inc) ) {
   echo "Error: Unable to include db connect script (".realpath($dbcon_inc).")";
   exit;
}

#######################################################
### LOOK FOR SSL CERT SETUP IN CART OPTIONS
#######################################################

$result = mysql_query("SELECT * FROM cart_options");
$OPTIONS = mysql_fetch_array($result);

$get_md5 = mysql_query("SELECT Rank FROM login WHERE PriKey = '1'");
$tmp = mysql_fetch_array($get_md5);
$MD5MATCH = $tmp[Rank];

if (strlen($OPTIONS[PAYMENT_SSL]) > 4) {
	$SECURE_SITE_LINK = $OPTIONS[PAYMENT_SSL] . $PHP_SELF;
	$SECURE_SITE_LINK = eregi_replace("view_orders.php", "view_invoice.php?allow=$MD5MATCH", $SECURE_SITE_LINK);
} else {
   $SECURE_SITE_LINK = "view_invoice.php?allow=$MD5MATCH";
}

$SECURE_SITE_LINK = "view_invoice.php?allow=$MD5MATCH";

#######################################################
### START HTML/JAVASCRIPT CODE			    		###
#######################################################

$MOD_TITLE = $lang["View/Retrieve Orders"];


# Defaults for testing
if ( $sortby == "" ) { $sortby = "ORDER_NUMBER"; }
if ( $sortby_dir == "" ) { $sortby_dir = "ASC"; }
if ( $keyword_splitby == "" ) { $keyword_splitby = "exact"; }

?>

<HTML>
<HEAD>
<TITLE>SHOPPING CART ORDERS</TITLE>

<META HTTP-EQUIV="Content-Type" content="text/html; charset=iso-8859-1">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">

<link rel="stylesheet" href="../../../product_gui.css">


<script language="JavaScript">
<!--
function SV2_findObj(n, d) { //v3.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=SV2_findObj(n,d.layers[i].document); return x;
}
function SV2_showHideLayers() { //v3.0
  var i,p,v,obj,args=SV2_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=SV2_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
    obj.visibility=v; }
}
function SV2_popupMsg(msg) { //v1.0
  alert(msg);
}
function SV2_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function view_invoice(key) {
	SV2_openBrWindow("<? echo $SECURE_SITE_LINK; ?>&id="+key+"&<?=SID?>","INVOICE","status=yes, scrollbars=yes, width=775, height=450");
}

SV2_showHideLayers('addCartMenu?header','','hide');
SV2_showHideLayers('blankLayer?header','','hide');
SV2_showHideLayers('linkLayer?header','','hide');
SV2_showHideLayers('newsletterLayer?header','','hide');
SV2_showHideLayers('cartMenu?header','','show');
SV2_showHideLayers('menuLayer?header','','hide');
SV2_showHideLayers('editCartMenu?header','','hide');

function dl_results() {
	window.document.download.submit();
}

function setStatus(h,status){
   document.getElementById(h).innerHTML=status;
}

function setRow(disRow){
   document.getElementById(disRow).style.color='#CCCCCC';
}

//-->
</script>

<link rel="stylesheet" href="../../../smt_module.css">
</head>

<!---Module heading--->
<table border="0" align="center" cellspacing="0" class="module_container">
 <tr>
  <td colspan="2" valign="top" class="nopad">
   <table width="100%" border="0" cellspacing="0" cellpadding="5" class="feature_module_heading">
    <tr>
     <td colspan="2" class="fgroup_title">
      <a href="../../../main_menu.php">Main Menu</a> &gt;
      <a href="../shopping_cart.php">Shopping Cart Menu</a> &gt;
      <a href="<? echo $_SERVER['PHP_SELF']; ?>" class="bold">View Online Orders/Invoices</a>
     </td>

     <td class="fgroup_title right" style="padding-right: 15px;">
      &nbsp;
     </td>
    </tr>
    <tr>
     <!---Module icon--->
     <td align="center">
      <a href="../shopping_cart.php"><img src="http://<? echo $_SESSION['docroot_url']; ?>/sohoadmin/skins/<? echo $_SESSION['skin']; ?>/icons/full_size/shopping_cart-enabled.gif" border="0"></a>
     </td>

     <!---Module title and description--->
     <td width="100%"><h1>View Online Orders/Invoices</h1>
      <p>Here you can review/manage orders placed through your website's checkout system.</p></td>

     <!---spacer-->
     <td>&nbsp;</td>
    </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td valign="top" class="module_body_area">

<?

	if ($search == "ordernumbers") {

		if ($start_num == "") { $start_num = 1; }
		if ($end_num == "") { $end_num = 50000000; }

		$SEARCH_DISPLAY = lang("Displaying order numbers")." $start_num - $end_num.<br/>";

		$SEARCH_STRING = "WHERE ORDER_NUMBER BETWEEN $start_num AND $end_num";
	}

	if ($search == "keywords") {


		if ($keywords == "") { $keywords = "Closed"; }

		$keywords = trim($keywords);

      # DEFAULT: Split into multiplue keywords by spaces
      if ( $_REQUEST['keyword_splitby'] == "" ) { $_REQUEST['keyword_splitby'] = " "; }

      # Split string into multiple keywords?
      if ( $_REQUEST['keyword_splitby'] != "exact" ) {

   		# Search on each term
   		$tmp = split($_REQUEST['keyword_splitby'], $keywords);
   		$tmp_cnt = count($tmp);

   		$SEARCH_STRING = "WHERE ";

   		for ($x=0;$x<=$tmp_cnt;$x++) {
   		   $tmp[$x] = trim($tmp[$x]);
   			if ($tmp[$x] != "") {
   				$string = "(INVOICE_HTML LIKE '%".$tmp[$x]."%' OR UPPER(BILLTO_FIRSTNAME) LIKE '%".strtoupper($tmp[$x])."%' OR UPPER(BILLTO_LASTNAME) LIKE '%".strtoupper($tmp[$x])."%' OR UPPER(BILLTO_PHONE) LIKE '%".strtoupper($tmp[$x])."%' OR UPPER(TRANSACTION_ID) LIKE '%".strtoupper($tmp[$x])."%')";
   				$SEARCH_STRING .= "$string OR ";
   			}
   		}
   	} else {
   	   # Exact phrase match
   		$SEARCH_STRING = "WHERE ";
         $keywords = trim($keywords);
//         $string = "(INVOICE_HTML LIKE '%".$keywords."%' OR BILLTO_FIRSTNAME LIKE '%$keywords%' OR BILLTO_LASTNAME LIKE '%$keywords%' OR BILLTO_PHONE LIKE '%$keywords%' OR TRANSACTION_ID LIKE '%$keywords%')";
         $string = "(INVOICE_HTML LIKE '%".$keywords."%' OR UPPER(BILLTO_FIRSTNAME) LIKE '%".strtoupper($keywords)."%' OR UPPER(BILLTO_LASTNAME) LIKE '%".strtoupper($keywords)."%' OR UPPER(BILLTO_PHONE) LIKE '%".strtoupper($keywords)."%' OR UPPER(TRANSACTION_ID) LIKE '%".strtoupper($keywords)."%')";
//         $string = "(UPPER(INVOICE_HTML) LIKE '%".$keywords."%' OR UPPER(BILLTO_FIRSTNAME) LIKE '%$keywords%' OR UPPER(BILLTO_LASTNAME) LIKE '%$keywords%' OR UPPER(BILLTO_PHONE) LIKE '%$keywords%' OR UPPER(TRANSACTION_ID) LIKE '%$keywords%')";
         $SEARCH_STRING .= "$string OR ";
      }


		$SEARCH_STRING .= "(ORDER_NUMBER = '$keywords')";

		$keywords = stripslashes($keywords);
		$keywords = ucwords($keywords);
		$SEARCH_DISPLAY .= lang("Search results for").": \"$keywords\"";

	}

	if ($search == "daterange") {


		$start_date = $_REQUEST['start_date_month'].'/'.$_REQUEST['start_date_day'].'/'.$_REQUEST['start_date_year'];
		$end_date = $_REQUEST['end_date_month'].'/'.$_REQUEST['end_date_day'].'/'.$_REQUEST['end_date_year'];
		
//		$SEARCH_DISPLAY = lang("Displaying all orders between")." $start_date and $end_date.";
//
//		$SEARCH_STRING = "WHERE ORDER_DATE >= '$start_date' AND ORDER_DATE <= '$end_date'";
		
		$SEARCH_DISPLAY = lang("Displaying all orders between")." $start_date and $end_date.";
		$query_start_date = split("/", $start_date);
		$query_end_date = split("/", $end_date);
		
		$int_start = $query_start_date[2].$query_start_date[0].$query_start_date[1];
		$int_end = $query_end_date[2].$query_end_date[0].$query_end_date[1];
		
		$int_start = $_REQUEST['start_date_year'].$_REQUEST['start_date_month'].$_REQUEST['start_date_day'];
		$int_end = $_REQUEST['end_date_year'].$_REQUEST['end_date_month'].$_REQUEST['end_date_day'];
	}

	// Regardless of the search specifications; set the "sort order" now
	// ------------------------------------------------------------------

	$SORT_SPEC = "ORDER BY $sortby $sortby_dir";

	// Set final sql query
	// ------------------------------------------------------------------

	$this_query = "$SEARCH_STRING $SORT_SPEC";

	$THIS_DISPLAY .= "<FORM NAME=download METHOD=POST ACTION=\"dl_view.php\">\n";
	$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=QUERY VALUE=\"$this_query\">\n";
	$THIS_DISPLAY .= "</FORM>\n";


	$THIS_DISPLAY .= "<FORM METHOD=POST ACTION=\"view_orders.php\">";

	// ---------------------------------------------------------------------
	// Display search header results; new search button
	// ---------------------------------------------------------------------

	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=4 CELLSPACING=0 WIDTH=100% ALIGN=CENTER>\n";
	$THIS_DISPLAY .= "<TR>\n";

	$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE CLASS=text><B>\n";
		$THIS_DISPLAY .= $SEARCH_DISPLAY;
	$THIS_DISPLAY .= "</B></TD>\n";

	$THIS_DISPLAY .= "<TD ALIGN=RIGHT VALIGN=MIDDLE>\n";
		//$THIS_DISPLAY .= "<INPUT TYPE=BUTTON VALUE=\" ".lang("Download Results")." \" onclick=\"dl_results();\" class=\"btn_edit\">&nbsp;&nbsp;&nbsp;&nbsp;";
		//$THIS_DISPLAY .= "<INPUT TYPE=BUTTON VALUE=\" ".lang("Print Results")." \" onclick=\"javascript: document.location='print_orders.php';\" class=\"btn_edit\">&nbsp;&nbsp;&nbsp;&nbsp;";
		//$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=ACTION VALUE=\"\"><INPUT TYPE=SUBMIT VALUE=\" ".lang("New Search")." \" class=\"btn_edit\">\n";
	$THIS_DISPLAY .= "</TD>\n";

	$THIS_DISPLAY .= "</TR></TABLE><BR>\n";

	// ---------------------------------------------------------------------

	$THIS_DISPLAY .= "<TABLE BORDER=1 CELLPADDING=4 CELLSPACING=0 WIDTH=100% ALIGN=CENTER BORDERCOLOR=BLACK>\n";
	$THIS_DISPLAY .= "<TR>\n";

	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=#CCCCCC CLASS=text><FONT COLOR=BLACK><B>".lang("Order Number")."</FONT></TD>\n";
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=#CCCCCC CLASS=text><FONT COLOR=BLACK><B>".lang("Order Date")."</FONT></TD>\n";
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=#CCCCCC CLASS=text><FONT COLOR=BLACK><B>".lang("Order Time")."</FONT></TD>\n";
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=#CCCCCC CLASS=text><FONT COLOR=BLACK><B>".lang("Customer")."</FONT></TD>\n";
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=#CCCCCC CLASS=text><FONT COLOR=BLACK><B>".lang("Payment Method")."</FONT></TD>\n";
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=#CCCCCC CLASS=text><FONT COLOR=BLACK><B>".lang("Status")."</FONT></TD>\n";
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=#CCCCCC CLASS=text><FONT COLOR=BLACK><B>".lang("Total Sale")."</FONT></TD>\n";
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=#CCCCCC CLASS=text><FONT COLOR=BLACK><B>".lang("Transaction ID")."</FONT></TD>\n";
	//$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=#CCCCCC CLASS=text><FONT COLOR=BLACK><B>".lang("Invoice")."</FONT></TD>\n";

	$THIS_DISPLAY .= "</TR>\n";

	// -------------------------------------------------------------------------------------------------
	// Pull invoice data for this month by default
	// -------------------------------------------------------------------------------------------------
	$this_query = str_replace("WHERE", "AND", $this_query); // Duct-taped
//	$orderQry = "SELECT ORDER_NUMBER, ORDER_DATE, ORDER_TIME, BILLTO_FIRSTNAME, BILLTO_LASTNAME, TOTAL_SALE, PAY_METHOD, TRANSACTION_STATUS, TRANSACTION_ID FROM cart_invoice WHERE TRANSACTION_STATUS != 'Incomplete' ".$this_query;

	# Show incomplete orders?
	if ( $_REQUEST['show_incomplete'] == "yes" ) {
	   $orderQry = "SELECT * FROM cart_invoice WHERE TRANSACTION_STATUS <> '' ".$this_query;
	} else {
	   $orderQry = "SELECT * FROM cart_invoice WHERE TRANSACTION_STATUS != 'Incomplete' ".$this_query;
	}

//	echo $orderQry; exit;
	
	if ( !$result = mysql_query($orderQry) ) {
	   echo mysql_error(); exit;
	}

	$NUM_FOUND = mysql_num_rows($result);

	// -------------------------------------------------------------------------------------------------
	// Build row data
	// -------------------------------------------------------------------------------------------------

	$ALT_CLR = "WHITE";

	while($data = mysql_fetch_array($result)) {
		$record_date = split("/", $data['ORDER_DATE']);
		$int_record_date = $record_date[2].$record_date[0].$record_date[1];
		if ( ($data['TRANSACTION_STATUS'] != "Purged") ){
		   if ( $int_start <= $int_record_date && $int_end >= $int_record_date) {



					mysql_query("'$data[ORDER_DATE]' AS CHAR");

					if ($ALT_CLR == "#EFEFEF") { $ALT_CLR = "WHITE"; } else { $ALT_CLR = "#EFEFEF"; }

					$THIS_DISPLAY .= "<TR ID=\"ROWID$data[ORDER_NUMBER]\">\n";

					$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=$ALT_CLR CLASS=text>$data[ORDER_NUMBER]</TD>\n";
					$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=$ALT_CLR CLASS=text>$data[ORDER_DATE]</TD>\n";

					$tmp = split(" ", $data[ORDER_TIME]);
					$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=$ALT_CLR CLASS=text>$tmp[0]</TD>\n";

					$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=$ALT_CLR CLASS=text>$data[BILLTO_LASTNAME],<FONT COLOR=$ALT_CLR>_</FONT>$data[BILLTO_FIRSTNAME]</TD>\n";
					$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=$ALT_CLR CLASS=text>$data[PAY_METHOD]</TD>\n";

						$data[TRANSACTION_STATUS] = eregi_replace("Sent", "<FONT COLOR=DARKORANGE>Sent</FONT>", $data[TRANSACTION_STATUS]);
						$data[TRANSACTION_STATUS] = eregi_replace("Pending", "<FONT COLOR=DARKORANGE>Pending</FONT>", $data[TRANSACTION_STATUS]);
						$data[TRANSACTION_STATUS] = eregi_replace("Closed", "<FONT COLOR=DARKGREEN>Closed</FONT>", $data[TRANSACTION_STATUS]);
						$data[TRANSACTION_STATUS] = eregi_replace("Paid", "<font color=\"#339959\">Paid</font>", $data[TRANSACTION_STATUS]);
						$data[TRANSACTION_STATUS] = eregi_replace("Cancelled", "<FONT COLOR=DARKRED>Cancelled</FONT>", $data[TRANSACTION_STATUS]);

						$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=$ALT_CLR CLASS=text ID=\"STATUSID$data[ORDER_NUMBER]\">$data[TRANSACTION_STATUS]</TD>\n";


					$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=$ALT_CLR CLASS=text>$$data[TOTAL_SALE]</TD>\n";

					$data[TRANSACTION_ID] = eregi_replace("NULL", "<FONT COLOR=#999999>N/A</FONT>", $data[TRANSACTION_ID]);

					if ($data[TRANSACTION_ID] == "") { $data[TRANSACTION_ID] = "<FONT COLOR=#999999>N/A</FONT>"; }

					$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=$ALT_CLR CLASS=text>$data[TRANSACTION_ID]</TD>\n";
//					$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=$ALT_CLR CLASS=text ID=\"BTNID$data[ORDER_NUMBER]\">";
//					$THIS_DISPLAY .= "<INPUT TYPE=BUTTON VALUE=\" ".lang("View")." \" onclick=\"view_invoice('$data[ORDER_NUMBER]');\" class=\"btn_edit\" onmouseover=\"this.className='btn_editon';\" onmouseout=\"this.className='btn_edit';\">";
//					$THIS_DISPLAY .= "</TD>\n";

					$THIS_DISPLAY .= "</TR>\n";

			} // End Do not Display Purged Transactions
		}
	};

	$THIS_DISPLAY .= "</TABLE></FORM>\n\n";

	if ($NUM_FOUND == 0) {
		$THIS_DISPLAY .= "<CENTER><FONT COLOR=RED style='font-size: 9pt;'><B>".lang("No invoices where found matching your search. Please try again.")."</FONT></CENTER><BR>";
		$ACTION = "";
	}

$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n";

echo $THIS_DISPLAY;

####################################################################

?>

<script type="text/javascript">
window.print();
</script>

<HEAD>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE"></HEAD>
</html>