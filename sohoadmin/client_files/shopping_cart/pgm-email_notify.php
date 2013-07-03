<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.5
##
## Homepage:	 	http://www.soholaunch.com
###############################################################################

##############################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2013 Soholaunch.com, Inc.
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

//############################################################################
// NOTE: The sole purpose of this script is to notify the site owner that
// a customer has completed their checkout process.
//############################################################################

############################################################################
#### NOW BUILD HTML EMAIL RECEIPT FOR CLIENT AND SEND COPY TO OWNER OF SITE
############################################################################
error_reporting('E_PARSE');
session_start();
require_once("../sohoadmin/program/includes/SohoEmail_class/SohoEmail.php");

$result = mysql_query("SELECT * FROM cart_options");
$BIZ = mysql_fetch_array($result);

$email_notice_flag = "off";


if ($BIZ['BIZ_EMAIL_NOTICE'] != "") {
	$from_name = $BIZ['BIZ_PAYABLE'];
	// Parse Items that cause wierd email display issues for customer
	$from_name = eregi_replace("\.", " ", $from_name);
	$from_name = eregi_replace(",", "", $from_name);
	if(eregi(',', $BIZ['BIZ_EMAIL_NOTICE'])){
		$multiple = explode(',', $BIZ['BIZ_EMAIL_NOTICE']);
		$from_email = $multiple['0'];
	} else {
		$from_email = $BIZ['BIZ_EMAIL_NOTICE'];
	}
	$email_notify = $BIZ['BIZ_EMAIL_NOTICE'];
	if($from_name!=''){
		$from_email = $from_name.'<'.str_replace(' ', '', $from_email).'>';
	} else {
		$from_email = str_replace(' ', '', $from_email);
	}
	$email_notice_flag = "on";
	
} else {
	$from_email = 'noreply@'.$_SESSION['this_ip'];
	$email_notify = 'webmaster@'.$_SESSION['this_ip'];
	$from_name = 'noreply@'.$_SESSION['this_ip'];
}

$email_header = "";
# <this thing> Doesn't work on WIN servers (v4.9.2 r14)
if ( eregi('WIN', PHP_OS) ) {
   $email_header .= "From: $from_email\r\n";
} else {
   $email_header .= "From: $from_name <$from_email>\r\n";
}

//$from_email = $email_notify;
//$from_name = $from_email;
$email_header .= "Content-Type: text/html; charset=iso-8859-1; name=\"final_invoice.html\"\r\n";
$email_header .= "Content-Transfer-Encoding: 7bit\r\n";
$email_header .= "Content-Disposition: inline;\n";
$email_header .= " filename=\"final_invoice.html\"\r\n";


$EMAIL_HTML = "<!-- \n\n";
$EMAIL_HTML .= "$SERVER_NAME HTML Purchase Receipt ($ORDER_DATE $ORDER_TIME)\n\n";
$EMAIL_HTML .= "If you are viewing this, your email client is not capable\n";
$EMAIL_HTML .= "of seeing HTML. Please open the attached HTML file inside\n";
$EMAIL_HTML .= "of a browser to view.  Thank you for your purchase!\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n -->\n\n";

$EMAIL_HTML .= "<HTML>\n<HEAD>\n<TITLE>Purchase $ORDER_DATE $ORDER_TIME</TITLE>\n\n";
$EMAIL_HTML .= "<LINK rel=\"stylesheet\" href=\"http://".$this_ip."/runtime.css\" type=\"text/css\">\n\n";
$EMAIL_HTML .= "</HEAD>\n\n<BODY BGCOLOR=WHITE LINK=RED ALINK=RED VLINK=RED>\n\n<CENTER>\n\n";
$EMAIL_HTML .= "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=612 ALIGN=CENTER>\n";
$EMAIL_HTML .= " <TR>\n";
$EMAIL_HTML .= "  <TD ALIGN=left VALIGN=TOP>\n";
$EMAIL_HTML .= "   <b>".$BIZ['BIZ_INVOICE_HEADER']."<br><br></b>\n";
$EMAIL_HTML .= "  </td>\n";
$EMAIL_HTML .= " </TR>\n";
$EMAIL_HTML .= " <TR>\n";
$EMAIL_HTML .= "  <TD ALIGN=left VALIGN=TOP>\n";
$EMAIL_HTML .= "   <b>Order Number:</b> ($ORDER_NUMBER)\n";
$EMAIL_HTML .= "  </td>\n";
$EMAIL_HTML .= " </tr>\n";
$EMAIL_HTML .= " <TR>\n";
$EMAIL_HTML .= "  <TD ALIGN=CENTER VALIGN=TOP>\n";

$EMAIL_HTML .= "\n<!-- WEBMASTER -->\n";

$EMAIL_HTML .= $INVOICE;

/*---------------------------------------------------------------------------------------------------------*
 ___  _  _        ___                      _                _
| __|(_)| | ___  |   \  ___ __ __ __ _ _  | | ___  __ _  __| |
| _| | || |/ -_) | |) |/ _ \\ V  V /| ' \ | |/ _ \/ _` |/ _` |
|_|  |_||_|\___| |___/ \___/ \_/\_/ |_||_||_|\___/\__,_|\__,_|

# If any line items are a file to be downloaded, place link
# here to download the file now.
/*---------------------------------------------------------------------------------------------------------*/
$tmp = split(";", $_SESSION['CART_SKUNO']);	// Split the sku number data into array (from shopping cart)
$tmp_cnt = count($tmp);			// How many line items are here?

for ($x=0;$x<=$tmp_cnt;$x++) {

	if ($tmp[$x] != "") {		// Don't do blanks (remember we always have an extra with this system

		$result = mysql_query("SELECT OPTION_DOWNLOADFILE, PROD_NAME FROM cart_products WHERE PROD_SKU = '$tmp[$x]'");
		$exist_flag = mysql_num_rows($result);

		if ($exist_flag > 0) {
			$filename = mysql_fetch_array($result); 
		}

	} // End Blank Check 

	if ( strlen($filename['OPTION_DOWNLOADFILE']) > 4 && $DOWNLOAD_AVAIL != "NO") {	// Place inside of loop, there might be multiple file downloads available

		$EMAIL_HTML .= "<br/><TABLE BORDER=0 CELLPADDING=4 CELLSPACING=0 WIDTH=100% ALIGN=CENTER CLASS=text STYLE='border: 1px inset black;' bgcolor=WHITE>\n";
		$EMAIL_HTML .= "<TR><TD ALIGN=CENTER VALIGN=TOP class=text><B>The Product <U>".$filename['PROD_NAME']."</U><BR>is available to download now</B>:<BR>\n";
		$EMAIL_HTML .= "<A HREF=\"http://".$this_ip."/media/".$filename['OPTION_DOWNLOADFILE']."\" target=\"_blank\"><img src=\"http://".$this_ip."/shopping/download_button.gif\" width=127 height=22 hspace=0 vspace=5 border=0></a><BR CLEAR=ALL>\n";
		$EMAIL_HTML .= "<FONT SIZE=1><U>Filename</U>: [$filename[OPTION_DOWNLOADFILE]]<BR><BR><I>".lang("To download and save the file to your hard-drive, 'Right-Click' on Download Button and select 'Save Target As...'.")." ".lang("When the save dialog appears, make sure you")." \n";
		$EMAIL_HTML .= lang("remember where you save the file on your hard drive.")." \n";
		$EMAIL_HTML .= "<br/><A HREF=\"http://".$this_ip."/media/".$filename['OPTION_DOWNLOADFILE']."\">Alternate Download Link</a>\n";
		$EMAIL_HTML .= "</TD></TR></TABLE><BR>\n";
		$filename['OPTION_DOWNLOADFILE'] = "";	// Reset var for next loop pass

	}

} // End For Loop

$EMAIL_HTML .= "</TD></TR></TABLE>\n\n</BODY>\n</HTML>\n\n";

$THIS_URL = strtoupper($SERVER_NAME);


// ---------------------------------------------------------------------
// Send Email to Customer Now...
// ---------------------------------------------------------------------

if ( $_POST['USER4'] == "VERISIGN_GATEWAY" ) {
   $cust_email = $_POST['EMAIL'];
} elseif ($_POST['payer_email'] != '') {
	 $cust_email = $_POST['payer_email'];
} else {
   $cust_email = $_SESSION['BEMAILADDRESS'];
}

if ( $cust_email == "" ) {
   # Try to pull from invoice record if empty
   $qry = 'select BILLTO_EMAILADDR FROM cart_invoice WHERE ORDER_NUMBER = \''.$ORDER_NUMBER.'\'';
   $rez = mysql_query($qry);
   $cust_email = mysql_result($rez, 0);
}

$cust_email = eregi_replace('[^a-zA-Z0-9\.@-]', '', stripslashes($cust_email));


if ( $EMAIL_HTML != '' ) {
	if(!SohoEmail($cust_email, $from_email, "$THIS_URL PURCHASE RECEIPT", $EMAIL_HTML)){
		//mail($cust_email, "$THIS_URL PURCHASE RECEIPT", "$EMAIL_HTML", $email_header);
	}
}

// ---------------------------------------------------------------------
// Send Order Notification to webmaster if requested
// ---------------------------------------------------------------------
if ($email_notice_flag == "on") {
   
   $webmaster_details = "<font color=darkblue face=Verdana><H4>".lang("This order was just placed from your website").". ".lang("If you need to retrieve the sale information, please login and do so now").".</H4><H1><FONT COLOR=RED>".lang("CUSTOMER INVOICE COPY")."</FONT></H1></font>";
   
   if($_REQUEST['OFFLINE_FLAG'] == "1"){
      $webmaster_details .= "<TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 CLASS=text ALIGN=CENTER WIDTH=100% STYLE='border: 1px solid black;' id=\"invoice-parent\">\n";
      $webmaster_details .= "<TR>\n";
      $webmaster_details .= "<TD colspan=\"2\" ALIGN=LEFT VALIGN=TOP CLASS=text BGCOLOR=008080 WIDTH=40%><FONT COLOR=F5F5F5>\n";
      $webmaster_details .= "<B>".lang("Offline Card Details")."</b></font>\n";
      $webmaster_details .= "</TD>\n";
      $webmaster_details .= "</TR>\n";
      
      # First half of card number
      $webmaster_details .= "<TR>\n";
      $webmaster_details .= "<TD ALIGN=LEFT VALIGN=TOP class=\"text row-normalbg\">\n";
      $webmaster_details .= "<b>".lang("First half of card number")."</b>\n";
      $webmaster_details .= "</TD>\n";
      $webmaster_details .= "<TD ALIGN=LEFT VALIGN=TOP class=\"text row-normalbg\">".$cc_first."\n";
      $webmaster_details .= "</TD>\n";
      $webmaster_details .= "</TR>\n";
      
      # AVS number
      $webmaster_details .= "<TR>\n";
      $webmaster_details .= "<TD ALIGN=LEFT VALIGN=TOP class=\"text row-normalbg\">\n";
      $webmaster_details .= "<b>".lang("Security code")."</b>\n";
      $webmaster_details .= "</TD>\n";
      $webmaster_details .= "<TD ALIGN=LEFT VALIGN=TOP class=\"text row-normalbg\">".$CC_AVS."\n";
      $webmaster_details .= "</TD>\n";
      $webmaster_details .= "</TR>\n";
      
      # Details
      $webmaster_details .= "<TR>\n";
      $webmaster_details .= "<TD colspan=\"2\" align=\"left\" valign=\"top\" class=\"text row-normalbg\">\n";
      $webmaster_details .= "<b>".lang("For security purposes the other half of the customers card number is stored in the invoice section of your admin panel").".</b>\n";
      $webmaster_details .= "</TD>\n";
      $webmaster_details .= "</TR>\n";
      $webmaster_details .= "</TABLE>\n";
      
   }
   
	$EMAIL_HTML = eregi_replace("<!-- WEBMASTER -->", $webmaster_details, $EMAIL_HTML);
	$email_subject = lang("New Website Purchase").": ".lang("Order")." #".$ORDER_NUMBER;
	
	if ( $EMAIL_HTML != '' ) {
		
		if(!SohoEmail($email_notify, $from_email, $email_subject, $EMAIL_HTML)){
			//mail($from_email, $email_subject, $EMAIL_HTML, $email_header);
		}
	}

}

?>