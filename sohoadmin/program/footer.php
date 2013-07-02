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

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// By License you may not modify any portion of this script. This particular
// script has dependancies and programming that can not be modified.
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

session_start();
error_reporting(E_PARSE);


# Include core interface files!
if ( !include("includes/product_gui.php") ) {
   echo "\n\n<!--------\n\n\n\n <!---Could not include this file:<br>[".$product_gui."]---> \n\n\n\n-------->\n\n";
}

# Restore build info array
$binfo = build_info();


/*----------------------------------------------------------------------------------------------------
 _____                             _____  _  _
|  __ \                           / ____|(_)| |
| |  | |  ___  _ __ ___    ___   | (___   _ | |_  ___
| |  | | / _ \| '_ ` _ \  / _ \   \___ \ | || __|/ _ \
| |__| ||  __/| | | | | || (_) |  ____) || || |_|  __/
|_____/  \___||_| |_| |_| \___/  |_____/ |_| \__|\___|

/*---------------------------------------------------------------------------------------------------*/
//if ( $_SESSION['demo_site'] == "yes" ) {
//
//   # Do we need to create the demo_timer table?
//   $match = 0;
//   $result = mysql_list_tables("$db_name");
//   $i = 0;
//   while ($i < mysql_num_rows ($result)) {
//   	$tb_names[$i] = mysql_tablename ($result, $i);
//   	if ($tb_names[$i] == "demo_timer") { $match = 1; }
//   	$i++;
//   }
//
//   # Create table now?
//   if ($match != 1) {
//   	if ( !mysql_db_query("$db_name","CREATE TABLE demo_timer (PriKey INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, last_demo VARCHAR(255), site_active VARCHAR(255))") ) {
//   	   //echo "could not create demo_timer table on $db_name!";
//   	}
//   	mysql_query("INSERT INTO demo_timer VALUES('','','')");
//
////      # Update demo_timer table with current timestamp and 'in use' status
////      $tStamp = time();
////      mysql_query("UPDATE demo_timer SET last_demo = '$tStamp', site_active = 'yes'");
//   }
//
//   # Make sure this demo site is available (in case of bookmarks, etc)
//   $timeRez = mysql_query("SELECT * FROM demo_timer");
//   $getDemo = mysql_fetch_array($timeRez);
//   $demo_inuse = $getDemo['site_active'];
//
//   if ( $getDemo['site_active'] == "yes" ) {
//      $inuse_alert = "<script language=\"javascript\">\n";
//      $inuse_alert .= " var bye=window.alert(\"This demo site is currently in use.\\n Please try again later, or select a different demo site.\");\n";
//      $inuse_alert .= " window.close();\n";
//      $inuse_alert .= "</script>\n\n";
//
//   } else {
//
//      # Update demo_timer table with current timestamp and 'in use' status
//      $tStamp = time();
//      mysql_query("UPDATE demo_timer SET last_demo = '$tStamp', site_active = 'yes'");
//
//   }
//
//} // End if demo site
//

$thisYear = date("Y");

/// Build HTML for footer frame
###==================================================================================
# This really should to be a div instead of frame (todo for v5)

$disHTML = "<HTML><HEAD><TITLE>STATUS BAR</TITLE>\n";
$disHTML .= "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=iso-8859-1\">\n";
$disHTML .= "<link rel=\"stylesheet\" href=\"product_gui.css\">\n";
$disHTML .= "<link rel=\"stylesheet\" href=\"includes/display_elements/product_gui-v2.css\">\n";

# Causes lots of problems if included here
//$disHTML .= "<script language=\"javascript\" src=\"includes/display_elements/js_functions.php\"></script>\n";

$disHTML .= "\n";
$disHTML .= "<script language=\"javascript\">\n";

# Inlude ajax fucntions from js_functions.php so we can reset demo sites on logout
ob_start();
?>
//---------------------------------------------------------------------------------------------------------
//      _      _   _   __  __
//     /_\  _ | | /_\  \ \/ /
//    / _ \| || |/ _ \  >  <
//   /_/ \_\\__//_/ \_\/_/\_\
//
//---------------------------------------------------------------------------------------------------------
// The following script (as commonly seen in other AJAX javascripts) is used to detect which browser the client is using.
// If the browser is Internet Explorer we make the object with ActiveX.
// (note that ActiveX must be enabled for it to work in IE)
function makeObject() {
   var x;
   var browser = navigator.appName;

   if ( browser == "Microsoft Internet Explorer" ) {
      x = new ActiveXObject("Microsoft.XMLHTTP");
   } else {
      x = new XMLHttpRequest();
   }

   return x;
}

// The javascript variable 'request' now holds our request object.
// Without this, there's no need to continue reading because it won't work ;)
var request = makeObject();

function ajaxDo(qryString, boxid) {
   //alert(qryString+', '+boxid);

   rezBox = boxid; // Make global so parseInfo can get it

   // The function open() is used to open a connection. Parameters are 'method' and 'url'. For this tutorial we use GET.
   request.open('get', qryString);

   // This tells the script to call parseInfo() when the ready state is changed
   request.onreadystatechange = parseInfo;

   // This sends whatever we need to send. Unless you're using POST as method, the parameter is to remain empty.
   request.send('');

}

function parseInfo() {
   // Loading
   if ( request.readyState == 1 ) {
      document.getElementById(rezBox).innerHTML = '<?echo lang("Loading"); ?>...';
   }

   // Finished
   if ( request.readyState == 4 ) {
      var answer = request.responseText;
      document.getElementById(rezBox).innerHTML = answer;
   }
}
<?
$disHTML .= ob_get_contents();
ob_end_clean();

// Make sure
$disHTML .= "function checkup(chkid) {\n";
$disHTML .= "  var bodfra = eval('parent.body.location.pathname');\n";
//$disHTML .= "  alert('Body path: -'+bodfra+'- .');\n";
$disHTML .= "  if ( bodfra.search(/user_options/) != -1 ) {\n";
$disHTML .= "     parent.body.toggle(chkid);\n";
$disHTML .= "  }\n";
$disHTML .= "}\n";

# This function is standard in module object.
# Kill it once footer is a div and all areas
# use feature_module class
$disHTML .= "function showme(divid) {\n";
$disHTML .= "  document.getElementById(divid).style.display='block';\n";
$disHTML .= "}\n";

$disHTML .= "function setPage(curPage) {\n";
$disHTML .= "  document.getElementById('CURPAGENAME').innerHTML=curPage;\n";
$disHTML .= "}\n";

$disHTML .= "function hideme(divid) {\n";
$disHTML .= "  document.getElementById(divid).style.display='none';\n";
$disHTML .= "}\n";

$disHTML .= "function orboff() {\n";
$disHTML .= "  document.getElementById('versioninfo').style.display='none';\n";
$disHTML .= "  document.getElementById('updateorb').style.display='none';\n";
$disHTML .= "}\n";

$disHTML .= "function MM_openBrWindow(theURL,winName,features) { //v2.0\n";
$disHTML .= "  window.open(theURL,winName,features);\n";
$disHTML .= "}\n";
$disHTML .= "function killErrors() { \n";
$disHTML .= "	return true; \n";
$disHTML .= "} \n";
$disHTML .= "window.onerror = killErrors;\n";

# check_session()
# Called when user attempts to logout or close tool window
$disHTML .= "function check_session() {\n";
# Reset demo sites on logout
//if ( $_SESSION['demo_site'] == "yes" ) {
//
//   if ( $demo_inuse != "yes" ) {
//      # Redirect parent window to marketing page (currently happens for Soho-branded demo sites only)
//      if ( $_SESSION['hostco']['upgrades'] == "soholaunch" ) {
//   	   $disHTML .= "		var logout = 'http://info.soholaunch.com/index.php?pr=Demo_Login&demo_num=".$_SESSION['demo_num']."&demo_user=".$_SESSION['demo_user']."&demo_userid=".$_SESSION['demo_email']."';\n";
//   	   $disHTML .= "		parent.touchThis(logout);\n";
//   	}
//
//   	# Reset demo site
//      //$disHTML .= "     alert('Resetting site now!');\n";
//      //$disHTML .= "     ajaxDo('reset_demosite.php', 'nomatter');\n";
//   }
//   $disHTML .= "     window.close();\n";
//
//} else {
//   # Default, non-demo mode: Alert about saved data b4 logout
//   $disHTML .= "	event.returnValue = \"".lang("NOTE: Any data outstanding will not be saved.")."\";\n";
//}
$disHTML .= "	event.returnValue = \"".lang("NOTE: Any data outstanding will not be saved.")."\";\n";
$disHTML .= "}\n";

$disHTML .= "function load_credits() {\n";
$disHTML .= "	window.open(\"credits.php?<?=SID?>\",\"\",\"width=520,height=420,scroll=no,status=no\");\n";
$disHTML .= "}\n";
$disHTML .= "</SCRIPT>\n";
$disHTML .= "<STYLE>\n";
$disHTML .= ".ctable { font-family: Arial; font-size: 11px; color: #000000; letter-spacing: 1px; }\n";
$disHTML .= ".copyright { font-family: Verdana; font-size: 7pt; color: black; }\n";

# Call-to-Action (CTA) button styles
$disHTML .= ".CTA_btn {\n";
$disHTML .= "   text-align: left;\n";
$disHTML .= "   cursor: pointer;\n";
$disHTML .= "   font-size: 11px;\n";
$disHTML .= "   padding: 2px 0px 0px 40px;\n";
$disHTML .= "   width: 155px;\n";
$disHTML .= "   height: 19px;\n";
$disHTML .= "   letter-spacing: .1em;\n";
$disHTML .= "   background-image: url(../skins/".$_SESSION['skin']."/buttons/buy_now-off.gif\n";
$disHTML .= "}\n";

# Learn More button images
$disHTML .= ".CTA_learn_more-off { background-image: url(../skins/".$_SESSION['skin']."/buttons/learn_more-off.gif); }\n";
$disHTML .= ".CTA_learn_more-on { background-image: url(../skins/".$_SESSION['skin']."/buttons/learn_more-on.gif); }\n";

# Buy Now button images
$disHTML .= ".CTA_buy_now-off { background-image: url(../skins/".$_SESSION['skin']."/buttons/buy_now-off.gif); }\n";
$disHTML .= ".CTA_buy_now-on { background-image: url(../skins/".$_SESSION['skin']."/buttons/buy_now-on.gif); }\n";

$disHTML .= "</style>\n";

$disHTML .= "</HEAD>\n";
$disHTML .= "<body bgcolor=\"#EFEFEF\" background=\"includes/display_elements/graphics/ftr-bg.gif\" text=\"white\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" onUnload=\"check_session();\">\n";

# Hidden box for site reset ajax return
$disHTML .= "<span id=\"nomatter\" style=\"display: none;\">&nbsp;</span>\n";

$disHTML .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"border-top: 1px solid #000000;\">\n";
$disHTML .= " <tr>\n";
$disHTML .= "  <td align=\"left\" valign=\"top\" class=\"ctable\" style=\"padding-left: 15px; padding-top: 3px;\">\n";
$disHTML .= "   <FONT COLOR=\"#066C9F\"><span id=\"CURPAGENAME\">&nbsp;</span></FONT>\n";
$disHTML .= "  </td>\n";
$disHTML .= "  <td align=\"left\" valign=\"top\" class=\"ctable\">\n";
$disHTML .= "   <span id=\"PAGESTAT\"></span>\n";
$disHTML .= "  </td>\n";
$disHTML .= "  <td align=\"center\" valign=\"top\" class=\"copyright\">&nbsp;\n";
$disHTML .= "   <span id=\"secureman\"></span><span id=\"serverSpace\"></span>\n";
$disHTML .= "  </td>\n";

//# Show 'Signup Now!' button for demo sites
//if ( isset($_SESSION['demo_site']) || $_SESSION['demo_site'] == "yes" ) {
//
//   # SIGNUP NOW: Custom link
//   if ( $_SESSION['hostco']['signup_now'] == "custom" ) { // Custom link
//      $buynow_text = lang("Signup Now");
//      $show_signup_link = true;
//      $buy_now_goto = "http://".$_SESSION['hostco']['signup_now_url']."";
//
//   # BUY NOW: Soholaunch website
//   } elseif ( ($_SESSION['hostco']['signup_now'] == "soholaunch") || ($_SESSION['hostco']['signup_now'] == "" && ($hostco_email == "" || $hostco_email == "sales@domain.com")) ) {
//      $buynow_text = lang("Buy Now");
//      $show_signup_link = true;
//      $buy_now_goto = "http://buysingle.soholaunch.com/index.php?user_domain=".$_SERVER['HTTP_HOST'];
//
//   } else {
//      # Do not show signup button
//      $show_signup_link = false;
//   }
//
//   if ( $show_signup_link ) {
//      $disHTML .= "  <td align=\"left\" valign=\"top\" class=\"copyright\" style=\"padding: 2px 0px 0px 200px;\">\n";
//      $disHTML .= "   <a href=\"#\" onclick=\"window.open('".$buy_now_goto."','billy','width=800, height=600, resizeable=yes, scrollbars=yes, toolbar=yes, location=yes, status=yes, menubar=yes');\">";
//      $disHTML .= "<b>".$buynow_text."<i>!</i></b></a>\n";
//
//      # Show 'save progress' link if in demo mode
//      $disHTML .= "&nbsp;&nbsp;|&nbsp;&nbsp;\n";
//   //   $onclick_gotobackup = "parent.body.location.href='http://".$_SESSION['docroot_url']."/sohoadmin/program/webmaster/backup_restore.php'";
//      $onclick_gotobackup = "parent.header.cartdo('bakrest');";
//      $disHTML .= "<a href=\"#\" onclick=\"".$onclick_gotobackup."\" class=\"sav\">";
//      $disHTML .= "<b>Save&nbsp;your&nbsp;progress</b></a>\n";
//      $disHTML .= "  </td>\n";
//   }
//}

# Version number display
//$disHTML .= "  <td align=\"right\" valign=\"top\" style=\"padding-top: 3px; background:url('http://ultra.soholaunch.com/sohoadmin/program/includes/images/soholaunch-powered2.png') no-repeat 90% 0px;\">\n";
$disHTML .= "  <td align=\"right\" valign=\"top\" style=\"margin-top:0;padding-top: 3px;padding-bottom:1px; background:url('includes/images/soholaunch-powered-footer.png') no-repeat 100% 0px;\">\n";

//$disHTML .= "<img border=\"0\" src=\"http://ultra.soholaunch.com/sohoadmin/program/includes/images/soholaunch-powered2.png\" style=\"width:86px;height:25px;\">\n";

$disHTML .= "   <span class=\"gray_31 bold\" style=\"display: block;\">".$binfo['build_name']."</span>\n";
$disHTML .= "   <span style=\"display: none;\" id=\"versioninfo\">&nbsp;</span>\n";
$disHTML .= "  </td>\n";


# Software Updates link or spacer?
if ( update_avail() && $_SESSION['hostco']['software_updates'] != "OFF" && $_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" ) {
   $disHTML .= "  <td align=\"right\" valign=\"top\" width=\"15\" style=\"padding-left: 5px;\">\n";
   $disHTML .= "   <span id=\"updateorb\" style=\"cursor: pointer; display: block;\" onClick=\"parent.body.location.href='webmaster/software_updates.php?todo=checknow';\">\n";
   $disHTML .= "    <img src=\"includes/display_elements/graphics/ftr-update_orb-on.gif\" title=\"".lang("New software updates available.")."\" border=\"0\">\n";
   $disHTML .= "   </span>\n";
   $disHTML .= "  </td>\n";
} else {
   $disHTML .= "  <td align=\"right\" valign=\"top\" width=\"15\">\n";
   $disHTML .= "    &nbsp;\n";
   $disHTML .= "  </td>\n";
}



$disHTML .= " </tr>\n";
$disHTML .= "</table>\n";
$disHTML .= $inuse_alert."\n";
$disHTML .= "</BODY>\n";
$disHTML .= "</HTML>\n";


echo $disHTML;

?>