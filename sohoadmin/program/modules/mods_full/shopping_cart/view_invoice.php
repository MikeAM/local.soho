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

include('../../../includes/product_gui.php');
//include($_SESSION['doc_root'].'/sohoadmin/program/includes/shared_functions.php');
# Hardcode lang function here because screen does not include product_gui.php
//function lang($text) {
//   if ( isset($lang[$text]) ) {
//      return $lang[$text];
//   } else {
//      return $text;
//   }
//}

// ----------------------------------------------------------------------
// We Can't Include the login.php script here because if we are using
// an SSL cert; the session does not carry over; so we must do some
// tricky MD5 checking to confirm this is the "real user" of the system
// ----------------------------------------------------------------------

// Mantis #0000004
if ( strlen($lang["Order Date"]) < 4 ) {
   if ( !include("includes/config-global.php") ) {
      echo "Cannot include config script!"; // Re-registers all global & session info
      exit;
   }
}

include("../../../../includes/db_connect.php");	// Must be able to connect to system db for confirmation


if ( !$get_md5 = mysql_query("SELECT Rank FROM login WHERE PriKey = '1'") ) {
   echo mysql_error(); exit;
}
$tmp = mysql_fetch_array($get_md5);
$this_pw = $tmp[Rank];

if ($this_pw != $allow) {

	echo "<H1>Error 401: Authorization Required</H1>\n";
	echo "[".$this_pw."] - this_pw <br>\n";
	echo "[".$allow."] - allow<br>";
	exit;

}


// ----------------------------------------------------------------------


#######################################################
### Perform document Purge :: We don't want to delete
### this order, because as soon as we do, somebody will
### delete the wrong thing.  This way, we can always
### get the order back from View/Download Data.
#######################################################

if ($purge != "") {


	mysql_query("UPDATE cart_invoice SET TRANSACTION_STATUS = 'Purged' WHERE ORDER_NUMBER = '$purge'");
	echo "<SCRIPT LANGUAGE=JAVASCRIPT> window.self.close(); </SCRIPT>\n";
	exit;

} // End Purge Routine


### Order status updating
///----------------------------------------------------

# Close
if ($close_order != "") {

   # Pull and re-format cc number
   $selCC = mysql_query("SELECT CC_NUM FROM cart_invoice WHERE ORDER_NUMBER = '$close_order'");
   $getCC = mysql_fetch_array($selCC);

   # Format cc num for display
   $cc_postfix = substr($getCC['CC_NUM'], -4);
   $cc_prefix = eregi_replace("[0-9]", "X", substr($getCC['CC_NUM'], 0, (strlen($getCC['CC_NUM']) - 4)));
   $cc_safe = $cc_prefix.$cc_postfix;

   # Show cc update string for testing
   //echo "<SCRIPT LANGUAGE=JAVASCRIPT> window.alert('cc_safe: ($cc_safe)'); </SCRIPT>\n";

   # Update db with safe cc info and 'Closed' status
	mysql_query("UPDATE cart_invoice SET TRANSACTION_STATUS = 'Closed', CC_NUM = '$cc_safe', CC_AVS = '' WHERE ORDER_NUMBER = '$close_order'");
	echo "<SCRIPT LANGUAGE=JAVASCRIPT> window.self.close(); </SCRIPT>\n";
	exit;
}

# Cancel
if ($cancel_order != "") {
	mysql_query("UPDATE cart_invoice SET TRANSACTION_STATUS = 'Cancelled' WHERE ORDER_NUMBER = '$cancel_order'");
	echo "<SCRIPT LANGUAGE=JAVASCRIPT> window.self.close(); </SCRIPT>\n";
	exit;
}

# Pending
if ($pending_order != "") {
	mysql_query("UPDATE cart_invoice SET TRANSACTION_STATUS = 'Pending' WHERE ORDER_NUMBER = '$pending_order'");
	echo "<SCRIPT LANGUAGE=JAVASCRIPT> window.self.close(); </SCRIPT>\n";
	exit;
}

#######################################################
### PULL THIS ORDER DATA INTO MEMORY
#######################################################

$result = mysql_query("SELECT * FROM cart_invoice WHERE ORDER_NUMBER = '$id'");
$row = mysql_fetch_array($result);

#######################################################
### START HTML/JAVASCRIPT CODE			    ###
#######################################################

$MOD_TITLE = "INVOICE#: $row[ORDER_NUMBER]";

?>

<HTML>
<HEAD>
<TITLE>SHOPPING CART ORDERS</TITLE>

<META HTTP-EQUIV="Content-Type" content="text/html; charset=iso-8859-1">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">

<link rel="stylesheet" href="../../../product_gui.css">

<STYLE>

.smtext { font-family: Arial; font-size: 8pt; }

</STYLE>

<script language="JavaScript">
<!--

window.focus();

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

SV2_showHideLayers('addCartMenu?header','','hide');
SV2_showHideLayers('blankLayer?header','','hide');
SV2_showHideLayers('linkLayer?header','','hide');
SV2_showHideLayers('newsletterLayer?header','','hide');
SV2_showHideLayers('cartMenu?header','','show');
SV2_showHideLayers('menuLayer?header','','hide');
SV2_showHideLayers('editCartMenu?header','','hide');

function go_back() {
	window.self.close();
}

function close_order() {

	var ordernum = "<? echo $row[ORDER_NUMBER]; ?>";
	var h_string = "STATUSID"+ordernum;
	//eval("opener.top.frames.body."+h_string+".innerHTML = '<FONT COLOR=DARKGREEN>Closed</FONT>';");
	window.opener.setStatus(h_string,'<FONT COLOR=DARKGREEN>Closed</FONT>');
	window.location = 'view_invoice.php?close_order=<? echo $row[ORDER_NUMBER]; ?>&allow=<? echo $allow; ?>&<?SID?>';
}

function cancel_order() {

	var ordernum = "<? echo $row[ORDER_NUMBER]; ?>";
	var h_string = "STATUSID"+ordernum;
	//eval("opener.top.frames.body."+h_string+".innerHTML = '<FONT COLOR=RED>Cancelled</FONT>';");
	window.opener.setStatus(h_string,'<FONT COLOR=RED>Cancelled</FONT>');

	window.location = 'view_invoice.php?cancel_order=<? echo $row[ORDER_NUMBER]; ?>&allow=<? echo $allow; ?>&<?SID?>';
}

function pending_order() {

	var ordernum = "<? echo $row[ORDER_NUMBER]; ?>";
	var h_string = "STATUSID"+ordernum;
	//eval("opener.top.frames.body."+h_string+".innerHTML = '<font color=darkorange>Pending</font>';");
	window.opener.setStatus(h_string,'<font color=darkorange>Pending</font>');

	window.location = 'view_invoice.php?pending_order=<? echo $row[ORDER_NUMBER]; ?>&allow=<? echo $allow; ?>&<?SID?>';
}

function purge_order() {
   warn = "<? echo lang("Warning: Purging will essentially remove this order's record from the system.") ?>;\n\n";
   warn = warn+"<? echo lang("Retrival of purged order data is possible, but it is a fairly tedious task."); ?>\n\n";
   warn = warn+"<? echo lang("Are you sure you want to do this?"); ?>";

   usure = window.confirm(warn);

   if ( usure == true ) {
   	var btn_html = '<INPUT TYPE=BUTTON VALUE=" View " class=FormLt1 DISABLED STYLE="background: #CCCCCC; color: #999999; border-color: #999999;">';

   	var ordernum = "<? echo $row[ORDER_NUMBER]; ?>";
   	var string = "ROWID"+ordernum;
   	var h_string = "STATUSID"+ordernum;
   	var b_string = "BTNID"+ordernum;

   	//eval("opener.top.frames.body."+string+".style.color = '#CCCCCC';");
   	window.opener.setRow(string);
   	//eval("opener.top.frames.body."+h_string+".innerHTML = '<FONT COLOR=#CCCCCC>Purged</FONT>';");
   	window.opener.setStatus(h_string,'<FONT COLOR=#CCCCCC>Purged</FONT>');
   	//eval("opener.top.frames.body."+b_string+".innerHTML = '"+btn_html+"'");
   	window.opener.setStatus(b_string,btn_html);

   	window.location = 'view_invoice.php?purge=<? echo $row[ORDER_NUMBER]; ?>&allow=<? echo $allow; ?>&<?SID?>';
   }

}

//-->
</script>

</head>

<body bgcolor=white text=black link=darkblue vlink=darkblue alink=darkblue leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>
<!--- <DIV ID="userOpsLayer" style="position:absolute; visibility:visible; left:0px; top:0; width:100%; height:100%; z-index:1; overflow: auto; border: 1px none #000000"> -->

<?



################################################################################################################

# Button table starts here
#-----------------------------
$THIS_DISPLAY .= "   <table cellpadding=\"4\" cellspacing=\"0\" border=\"0\">\n";
$THIS_DISPLAY .= "    <tr>\n";

# PURGE
$THIS_DISPLAY .= "     <td><INPUT TYPE=BUTTON VALUE=\"  ".lang("PURGE Order From System")."  \" onClick=\"purge_order();\" class=\"btn_delete\" onmouseover=\"this.className='btn_deleteon';\" onmouseout=\"this.className='btn_delete';\"></td>";

# CANCEL
if ( $row[TRANSACTION_STATUS] != "Cancelled" ) {
	$THIS_DISPLAY .= "     <td><INPUT TYPE=BUTTON VALUE=\" ".lang("Mark as CANCELLED")."  \" onClick=\"cancel_order();\" class=\"btn_red\"></td>";
}

# CLOSE
if ( $row[TRANSACTION_STATUS] != "Closed" ) {
   $THIS_DISPLAY .= "     <td><INPUT TYPE=BUTTON VALUE=\"  ".lang("Mark as CLOSED")."  \" onClick=\"close_order();\" class=\"btn_green\"></td>";
}

# PENDING
if ( $row[TRANSACTION_STATUS] == "Closed" || $row['TRANSACTION_STATUS'] == "Cancelled" ) {
   $THIS_DISPLAY .= "     <td><INPUT TYPE=BUTTON VALUE=\"  ".lang("Mark as PENDING")."  \" onClick=\"pending_order();\" class=\"btn_orange\"></td>";
}

# PRINT
$THIS_DISPLAY .= "     <td><input type=button value=\"  ".lang("Print")."  \" onClick=\"javascript: window.print();\" class=\"btn_build\" onmouseover=\"this.className='btn_buildon';\" onmouseout=\"this.className='btn_build';\"></td>";

# Close Window
$THIS_DISPLAY .= "     <td><input type=button value=\"  ".lang("Close Window")."  \" onClick=\"go_back();\" class=\"btn_edit\" onmouseover=\"this.className='btn_editon';\" onmouseout=\"this.className='btn_edit';\"></td>";

$THIS_DISPLAY .= "    </tr>\n";
$THIS_DISPLAY .= "   </td>\n";
$THIS_DISPLAY .= "  </table>\n";

$THIS_DISPLAY .= "<table border=0 cellpadding=2 cellspacing=0 width=100% align=center>\n";
$THIS_DISPLAY .= " <tr>\n";

$THIS_DISPLAY .= "  <td align=left valign=bottom class=text>\n";
$THIS_DISPLAY .= "   ".$lang["Order Date"].": " . $row[ORDER_DATE] . " - " . $row[ORDER_TIME];

if ($row[TRANSACTION_ID] != "NULL") {
	$THIS_DISPLAY .= "<BR>&nbsp;Transaction ID: $row[TRANSACTION_ID]\n";
}

$THIS_DISPLAY .= "  </td>\n";


$THIS_DISPLAY .= "  <td align=right valign=top>\n";





// --------------------------------------------------------------------------


$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;\n";

$THIS_DISPLAY .= "</TD>\n";

$THIS_DISPLAY .= "</TR></TABLE><BR>\n";

$THIS_DISPLAY .= $row[INVOICE_HTML];

$THIS_DISPLAY .= "<BR>";

################################################################################################################

$sql = "SHOW TABLES FROM ".$_SESSION['db_name'];
$resultz = mysql_query($sql);
while($this_result = mysql_fetch_array($resultz, MYSQL_NUM)){

	if(eregi('^UDT_CART_DATA_', $this_result['0'])){
		$dbhit[] = $this_result['0'];
	}
}
foreach($dbhit as $tname){
		$checkdb = mysql_query('select * from '.$tname.' where ORDER_NUMBER=\''.$row['ORDER_NUMBER'].'\'');
		while($checkmatch = mysql_fetch_array($checkdb, MYSQL_ASSOC)){
			$match_list[] = $checkmatch;
		}
}

if(count($match_list) > 0){
	$THIS_DISPLAY .= "<div align=\"left\">\n";
	$THIS_DISPLAY .= " <table class=\"text\" style=\"border: 1px solid black;\" cellpadding=\"4\" cellspacing=\"0\" width=\"450\" >\n";
	$THIS_DISPLAY .= "  <tr>\n";
	$THIS_DISPLAY .= "   <td style=\"background-color:#708090; color: white;\" colspan=\"2\" align=\"left\">\n";
	$THIS_DISPLAY .= "    <b>".lang("Form Data")."</b>\n";
	$THIS_DISPLAY .= "   </td>\n";
	$THIS_DISPLAY .= "  </tr>\n";
	$THIS_DISPLAY .= "  <tr>\n";
	$THIS_DISPLAY .= "   <td colspan=\"2\" align=\"left\">\n";

$fieldcount = 0;
foreach($match_list as $to=>$t1){
	$fcount = 0;
	foreach($t1 as $fvar=>$fval){		
		if($fvar != 'PRIKEY' && $fvar != 'ORDER_NUMBER' && $fvar != 'PURCHASER' && $fvar != 'ORDER_DATE' &&  $fvar != 'FORM_NUMBER' && $fval != ''){
			if($fcount != 1){
				++$fieldcount;
				$fcount = 1;
				$THIS_DISPLAY .= "<strong>Form ".$fieldcount." Data:</strong><br/>";
			}
			$THIS_DISPLAY .= "<strong><font color=blue>".$fvar.":</font> <font color=green>".$fval."</font></strong><br/>";
		}
	}
	if($fcount == 1){
			$THIS_DISPLAY .= "<br/>";
		}
}
//	$THIS_DISPLAY .= "  <tr>\n";
//	$THIS_DISPLAY .= "   <td class=\"fgrn_title\" colspan=\"2\" align=\"center\">\n";
//	$THIS_DISPLAY .= "    ".$lang["Payment Method"]."\n";
//	$THIS_DISPLAY .= "   </td>\n";
//	$THIS_DISPLAY .= "  </tr>\n";
	$THIS_DISPLAY .= "   </td>\n";
	$THIS_DISPLAY .= "  </tr></table></div>\n";
}

function ENCRYPT($string){

	$ENCRYPT_KEY = ":aAb`BcVCd/eXDfEYg FZhi?jGk|HlmI,nJo@TKpqL.WMrsNt!uvwOx<yPz>0QR12~3S4;^567U89%$#*()-_=+È‚‰Â‡ÁÍÎÓÏ≈…Ê∆ÙˆÚ˚˘÷‹¢£•f·Ì«¸Ò—™∫ø¨Ωº°´ª¶'";
	$str = "";
	$val = strlen($string);
	for ($i=0;$i<$val;$i++) {
		$tmp = substr($string, $i, 1);
		$aNum = strpos($ENCRYPT_KEY, $tmp, 0);
     		$aNum =$aNum^25;
     		$str = $str . substr($ENCRYPT_KEY, $aNum, 1);
	}
	return $str;
}

################################################################################################################

if ($row[CC_TYPE] != "NULL" && $row[CC_NUM] != "NULL") {		// This is an offline processing order (show cc information)

	$THIS_DISPLAY .= "<div align=\"left\">\n";
	$THIS_DISPLAY .= " <table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"450\" class=\"feature_grn\">\n";

	## Payment Details
	##----------------------------------
	$THIS_DISPLAY .= "  <tr>\n";
	$THIS_DISPLAY .= "   <td class=\"fgrn_title\" colspan=\"2\" align=\"center\">\n";
	$THIS_DISPLAY .= "    ".$lang["Payment Method"]."\n";
	$THIS_DISPLAY .= "   </td>\n";
	$THIS_DISPLAY .= "  </tr>\n";
	
	if(strlen($row['CC_NUM']) == 8){
   	$THIS_DISPLAY .= "  <tr>\n";
   	$THIS_DISPLAY .= "   <td colspan=\"2\" class=\"red\">\n";
   	$THIS_DISPLAY .= "    <b>NOTE: The card number displayed below is only the last half of the full number.  The first half, along with the card security code, has been sent via email to the address set in Shopping Cart > Business Information.</b>\n";
   	$THIS_DISPLAY .= "   </td>\n";
   	$THIS_DISPLAY .= "  </tr>\n";
   }

	$THIS_DISPLAY .= "  <tr>\n";
	$THIS_DISPLAY .= "   <td width=\"175\">\n";
	$THIS_DISPLAY .= "    <b>".$lang["Credit Card Type"]."</b>:\n";
	$THIS_DISPLAY .= "   </td>\n";
	$THIS_DISPLAY .= "   <td width=\"275\">\n";
	$THIS_DISPLAY .= "    $row[CC_TYPE]\n";
	$THIS_DISPLAY .= "   </td>\n";
	$THIS_DISPLAY .= "  </tr>\n";

	$THIS_DISPLAY .= "  <tr>\n";
	$THIS_DISPLAY .= "   <td>\n";
	$THIS_DISPLAY .= "    <b>".$lang["Credit Card Number"]."</b>:\n";
	$THIS_DISPLAY .= "   </td>\n";
	$THIS_DISPLAY .= "   <td>\n";
	$DISP_NUM = ENCRYPT($row[CC_NUM]);
	$THIS_DISPLAY .= "    $row[CC_NUM]";

   # Maintis #320
   if ($row['CC_AVS'] != "" && $row['CC_AVS'] != "NULL" ) { $THIS_DISPLAY .= " - $row[CC_AVS]"; } // Tack on sec num

	$THIS_DISPLAY .= "   </td>\n";
	$THIS_DISPLAY .= "  </tr>\n";

	$THIS_DISPLAY .= "  <tr>\n";
	$THIS_DISPLAY .= "   <td>\n";
	$THIS_DISPLAY .= "    <b>".$lang["Credit Card Expiration Date"]."</b>:\n";
	$THIS_DISPLAY .= "   </td>\n";
	$THIS_DISPLAY .= "   <td>\n";
	$THIS_DISPLAY .= "    $row[CC_DATE]\n\n</div>\n\n";
	$THIS_DISPLAY .= "   </td>\n";
	$THIS_DISPLAY .= "  </tr>\n";
	$THIS_DISPLAY .= " </table>\n";

} else {

	$THIS_DISPLAY .= "<DIV ALIGN=LEFT><U>".$lang["Payment Method"]."</U>: $row[PAY_METHOD] &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
	$THIS_DISPLAY .= "<U>".$lang["Order Status"]."</U>: $row[TRANSACTION_STATUS]</DIV>\n";

}

####################################################################
### FOR VISUAL CONSISTANCY; WE USE AN HTML TEMPLATE BUILDER FILE
### LOCATED IN THE /shared FOLDER.  THIS WAY ALL OF OUR MODULE
### INTERFACES LOOK THE SAME. YOU MUST SUPPLY THE VARIABLES:
###
### $MOD_TITLE		Title of this Module
### $THIS_DISPLAY		HTML Content to display to end user
### $BG 			Background Image for content table if used
###
### THIS SAME METHOD SHOULD BE USED WHEN BUILDING ANY OF YOUR OWN
### CUSTOM MODULES.  REMEMBER TO INCLUDE THE HEADER "INCLUDES"
### ABOVE FOR PROPER FUNCTIONALITY WITHIN THE APPLICAITON.
####################################################################

include("shared/html_build.php");

####################################################################

?>

<!--- </div> -->

</body>

</html>