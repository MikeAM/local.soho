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
require_once('../../../includes/product_gui.php');
//echo $_SESSION['product_gui'];
error_reporting(E_PARSE);
#######################################################
### PERFORM SAVE ACTION : UPDATE DATA TABLE TO REFLECT
### CHANGES MADE BY USER
#######################################################

$update_complete = 0;
$biz_country = rtrim($biz_country);

if ($ACTION == "updatebiz") {
//   echo "biz_country = ($biz_country)";
//   exit;

	mysql_query("UPDATE cart_options SET

			BIZ_PAYABLE = '".slashthis($biz_payable)."',
			BIZ_ADDRESS_1 = '".slashthis($biz_address_1)."',
			BIZ_ADDRESS_2 = '".slashthis($biz_address_2)."',
			BIZ_CITY = '".slashthis($biz_city)."',
			BIZ_STATE = '".slashthis($biz_state)."',
			BIZ_POSTALCODE = '".slashthis($biz_postal)."',
			BIZ_PHONE = '".slashthis($biz_phone)."',
			BIZ_VERIFY_COMMENTS = '".slashthis($biz_verify_comments)."',
			BIZ_EMAIL_NOTICE = '".slashthis($biz_email_notice)."',
			BIZ_INVOICE_HEADER = '".slashthis($biz_invoice_header)."',
			BIZ_COUNTRY = '".slashthis($biz_country)."'

			") OR DIE ('Could Not Update Data');

	$update_complete = 1;

}

#######################################################
### READ DATABASED OPTIONS INTO MEMORY NOW
#######################################################

$result = mysql_query("SELECT * FROM cart_options");
$BIZ = mysql_fetch_array($result);

#######################################################
### GET COUNTRY DATA FROM FLAT FILE		             ###
#######################################################

$filename = "shared/countries.dat";
$file = fopen("$filename", "r") or DIE("Error: Could not open country data (shared/contries.dat).");
	$tmp_data = fread($file,filesize($filename));
fclose($file);

$natDat = split("\n", $tmp_data);
$numNats = count($natDat);

//natDat: T.M.I (for now) format for proper display and usage
$natNam = "";
for ($f=0; $f < $numNats; $f++) {
   $tmpSplt = split("::", $natDat[$f]);
   $natNam[$f] = "$tmpSplt[0] - $tmpSplt[1]";
   $natNam[$f] = strtoupper($natNam[$f]);
}


#######################################################
### START HTML/JAVASCRIPT CODE			    ###
#######################################################

if (strlen($BIZ[BIZ_INVOICE_HEADER]) < 5) {
	$BIZ[BIZ_INVOICE_HEADER] = "Thank you for order today. We have received your online order for the following items:";
}

ob_start();

?>
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

SV2_showHideLayers('addCartMenu?header','','hide');
SV2_showHideLayers('blankLayer?header','','hide');
SV2_showHideLayers('linkLayer?header','','hide');
SV2_showHideLayers('newsletterLayer?header','','hide');
SV2_showHideLayers('cartMenu?header','','show');
SV2_showHideLayers('menuLayer?header','','hide');
SV2_showHideLayers('editCartMenu?header','','hide');


<?

if ($update_complete == 1) {

	echo ("alert('Your business options have been updated.');\n");

}

?>

//-->
</script>
<link rel="stylesheet" href="shopping_cart.css">

<?

$THIS_DISPLAY = "\n";

$THIS_DISPLAY .= "<FORM NAME=bizinfo METHOD=POST ACTION=\"business_information.php\">\n";
$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=\"ACTION\" VALUE=\"updatebiz\">\n\n";

// ---------------------------------------------------------------------------
// Get Business Mailing Address and Phone Number Information
// ---------------------------------------------------------------------------

$THIS_DISPLAY .= "\n\n<table border=\"0\" align=\"center\" cellpadding=\"4\" cellspacing=\"0\" width=\"75%\" class=\"feature_group\">\n";
$THIS_DISPLAY .= "<TR>\n\n";
$THIS_DISPLAY .= "<TD ALIGN=left VALIGN=TOP COLSPAN=4>\n";

$THIS_DISPLAY .= lang("You will need to enter the address, phone number and whom to make a <U>check or money order</U>")." \n";
$THIS_DISPLAY .= lang("payable to for your online store.  This will display to your site visitors at checkout time.")."<BR><BR>\n";


$THIS_DISPLAY .= "</TD></TR><TR>\n\n";

$THIS_DISPLAY .= "<TD ALIGN=RIGHT VALIGN=MIDDLE WIDTH=200><b>".lang("Make Payable To:")."</b> </TD>\n";
$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE COLSPAN=3><INPUT TYPE=TEXT CLASS=text NAME=\"biz_payable\" VALUE=\"$BIZ[BIZ_PAYABLE]\" STYLE='width: 375px;'></TD>\n\n";

$THIS_DISPLAY .= "</TR><TR>\n\n";

$THIS_DISPLAY .= "<TD ALIGN=RIGHT VALIGN=MIDDLE><b>".lang("Address:")."</b> </TD>\n";
$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE COLSPAN=3><INPUT TYPE=TEXT CLASS=text NAME=\"biz_address_1\" VALUE=\"$BIZ[BIZ_ADDRESS_1]\" STYLE='width: 375px;'></TD>\n\n";

$THIS_DISPLAY .= "</TR><TR>\n\n";

$THIS_DISPLAY .= "<TD ALIGN=RIGHT VALIGN=MIDDLE>&nbsp;</TD>\n";
$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE COLSPAN=3><INPUT TYPE=TEXT CLASS=text NAME=\"biz_address_2\" VALUE=\"$BIZ[BIZ_ADDRESS_2]\" STYLE='width: 375px;'></TD>\n\n";

$THIS_DISPLAY .= "</TR><TR>\n\n";

$THIS_DISPLAY .= "<TD ALIGN=RIGHT VALIGN=MIDDLE><b>".lang("City").":</b> </TD>\n";
$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE><INPUT TYPE=TEXT CLASS=text NAME=\"biz_city\" VALUE=\"$BIZ[BIZ_CITY]\" STYLE='width: 100px;'></td>\n";
$THIS_DISPLAY .= "<TD ALIGN=RIGHT VALIGN=MIDDLE><b>".lang("State/Province:")."</b> </TD>\n";
$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE><INPUT TYPE=TEXT CLASS=TEXT NAME=\"biz_state\" VALUE=\"$BIZ[BIZ_STATE]\" STYLE='width: 110px;'></td>\n";

$THIS_DISPLAY .= "</TR><TR>\n\n";

$THIS_DISPLAY .= "<TD ALIGN=RIGHT VALIGN=MIDDLE><b>".lang("Zip/Postal Code:")."</b> </TD>\n";
$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE><INPUT TYPE=TEXT CLASS=text NAME=\"biz_postal\" VALUE=\"$BIZ[BIZ_POSTALCODE]\" STYLE='width: 83px;'></td>\n\n";
$THIS_DISPLAY .= "<TD ALIGN=RIGHT VALIGN=MIDDLE><b>".lang("Country:")."</b> </TD>\n";
$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE WIDTH=150>\n";

$THIS_DISPLAY .= "<SELECT NAME=\"biz_country\" STYLE='font-family: Arial; font-size: 10px; width: 145px;'>\n";

//Build country list and select current
for ($n=0;$n < $numNats;$n++) {
	$sel = "";
	if ($natNam[$n] == $BIZ[BIZ_COUNTRY]) { $sel = "selected"; }
	$THIS_DISPLAY .= "    <OPTION VALUE=\"$natNam[$n]\" $sel>$natNam[$n]</OPTION>\n";
}


$THIS_DISPLAY .= "</SELECT></TD>\n";



$THIS_DISPLAY .= "</TR><TR>\n\n";

$THIS_DISPLAY .= "<TD ALIGN=RIGHT VALIGN=MIDDLE><b>".lang("Phone Number:")."</b> </TD>\n";
$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE COLSPAN=3><INPUT TYPE=TEXT CLASS=text NAME=\"biz_phone\" VALUE=\"$BIZ[BIZ_PHONE]\" STYLE='width: 100px;'></TD>\n\n";

$THIS_DISPLAY .= "</TR>\n\n";

$THIS_DISPLAY .= "<TR><TD ALIGN=CENTER VALIGN=MIDDLE COLSPAN=4 style=\"background-color: F5F5F5; border-top: 1px solid #333333;\"><FONT COLOR=#333333>".lang("Statistics have shown that displaying this information on your site will increase trust<BR>among shoppers and therefore produce better sales results.")."</FONT></TD></TR>\n";

$THIS_DISPLAY .= "</TABLE>\n\n";

// ---------------------------------------------------------------------------
// Get Email Addresses to send email notifications of orders
// ---------------------------------------------------------------------------

$THIS_DISPLAY .= "<br><br>\n\n";

$THIS_DISPLAY .= "\n\n<table border=\"0\" align=\"center\" cellpadding=\"4\" cellspacing=\"0\" width=\"75%\" class=\"feature_group\">\n";
$THIS_DISPLAY .= "<TR>\n\n";
$THIS_DISPLAY .= "<TD VALIGN=TOP COLSPAN=2>\n";

$THIS_DISPLAY .= lang("When orders are placed on your website, they are saved in your order/invoice area.")." \n";
$THIS_DISPLAY .= lang("The system will automatically send you an <U>email notifing you of new orders</U>.  Please ")."\n";
$THIS_DISPLAY .= lang("enter the email address where you wish these notifications to be sent. (Multiple email")." \n";
$THIS_DISPLAY .= lang("addresses can be entered seperated by a comma)")."<BR><BR>\n";

$THIS_DISPLAY .= "</TD></TR><TR>\n";

$THIS_DISPLAY .= "<TD ALIGN=RIGHT VALIGN=MIDDLE><b>".lang("Notification Email Address:")."</b> </TD>\n";
$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE><INPUT TYPE=TEXT CLASS=text NAME=\"biz_email_notice\" VALUE=\"$BIZ[BIZ_EMAIL_NOTICE]\" STYLE='width: 250px;'></TD>\n\n";

$THIS_DISPLAY .= "</TR></TABLE>\n\n";

// ------------------------------------------------------------------------------------
// If using "Allow Comments" options, what email address do we verify these comments?
// ------------------------------------------------------------------------------------

$THIS_DISPLAY .= "<br><br>\n\n";


$THIS_DISPLAY .= "\n\n<table border=\"0\" align=\"center\" cellpadding=\"4\" cellspacing=\"0\" width=\"75%\" class=\"feature_group\">\n";
$THIS_DISPLAY .= "<tr>\n\n";
$THIS_DISPLAY .= "<td valign=top colspan=2>\n";

$THIS_DISPLAY .= lang("If you are using the \"Allow Product Comments\" option, when <U>users submit comments</U>")." \n";
$THIS_DISPLAY .= lang("about your products, the comments will be saved and an email generated to the email")." \n";
$THIS_DISPLAY .= lang("address below for verification. If the comments meet your approval, you can then allow")." \n";
$THIS_DISPLAY .= lang("the comments to be made visible by the public.  This is done to prevent unsavory or")." \n";
$THIS_DISPLAY .= lang("lewd comments from being posted without your knowledge.")."<BR><BR>\n";

$THIS_DISPLAY .= "</td></tr><tr>\n";

$THIS_DISPLAY .= "<td align=right valign=middle><b>".lang("Verification Email Address:")."</b> </TD>\n";
$THIS_DISPLAY .= "<td align=left valign=middle><input type=text class=text name=\"biz_verify_comments\" VALUE=\"$BIZ[BIZ_VERIFY_COMMENTS]\" STYLE='width: 250px;'></TD>\n\n";

$THIS_DISPLAY .= "</TR></TABLE>\n\n";

// ------------------------------------------------------------------------------------
// Allow user-edit of the invoice email header sent to customers
// ------------------------------------------------------------------------------------

$THIS_DISPLAY .= "<br><br>\n\n";

$THIS_DISPLAY .= "\n\n<table border=\"0\" align=\"center\" cellpadding=\"4\" cellspacing=\"0\" width=\"75%\" class=\"feature_group\">\n";
$THIS_DISPLAY .= "<TR>\n\n";
$THIS_DISPLAY .= "<TD VALIGN=TOP>\n";

$THIS_DISPLAY .= lang("After your customers purchase products from your site, they will receive an <U>email")." \n";
$THIS_DISPLAY .= lang("invoice</U> of the order for their records. The default header text is a simple thank")." \n";
$THIS_DISPLAY .= lang("you and is provided below.  You may modify this to say anything you wish.  The actual")." \n";
$THIS_DISPLAY .= lang("invoice with pricing breakdowns, tax, shipping, etc. will appear below this header text.")."<BR><BR>\n";

$THIS_DISPLAY .= "</TD></TR><TR>\n";

$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE>\n";

	$THIS_DISPLAY .= "     <TEXTAREA NAME=biz_invoice_header style='background: WHITE; width: 350px; height: 100px; font-family: Arial; font-size: 8pt;'>$BIZ[BIZ_INVOICE_HEADER]</TEXTAREA>\n\n";

$THIS_DISPLAY .= "</TD>\n\n";

$THIS_DISPLAY .= "</TR></TABLE>\n\n";

$THIS_DISPLAY .= "<br><br>\n\n";

$THIS_DISPLAY .= "<div style=\"text-align: center;\"><button type=\"button\" class=\"greenButton\" onClick=\"document.bizinfo.submit();\"><span><span>".lang("Save Business Info")."</span></span></button></FORM>\n";

echo $THIS_DISPLAY;

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$module = new smt_module($module_html);
$module->add_breadcrumb_link("Shopping Cart Menu", "program/modules/mods_full/shopping_cart.php");
$module->add_breadcrumb_link("Business Information", "program/modules/mods_full/shopping_cart/business_information.php");
$module->icon_img = "program/includes/images/shopping-icon-med.png";
$module->heading_text = "Business Information";
$module->description_text = "Specify what information should display to your customers as your business' mailing address, etc. This is also the place to fill-in the email address that you'd like new purchase notifications sent to (presumably your own).";
$module->good_to_go();
?>
