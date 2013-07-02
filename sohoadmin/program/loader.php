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

## Pull 100% legit docroot from path to this script
//==============================================================
if ( !is_dir($_SESSION['docroot_path']) ) {
   # Build known aspects of path to clip
   $clipknown = "/sohoadmin/program/".basename(__FILE__);

   # Define full docroot path from root (for php stuff)
   $_SESSION['docroot_path'] = str_replace( $clipknown, "", __FILE__ );

   # Define domain root path (for html stuff)
   //$_SESSION['docroot_url'] = $_SERVER['HTTP_HOST'].str_replace( $clipknown, "", $_SERVER['PHP_SELF'] );

   # Define full path to core product include script
   $_SESSION['product_gui'] = $_SESSION['docroot_path']."/sohoadmin/program/includes/product_gui.php";

}

# Include core interface files!
if ( !require_once("includes/product_gui.php") ) {
   //echo "Could not include this file:<br>[<b><font color=\"#008080\">".$_SESSION['product_gui']."</font></b>]<br>"; exit;
   exit;
}


#########################################################################
## Start quickstart wizard or have a normal login?
#########################################################################

//if ( !file_exists("../nowiz.txt") && !file_exists("../filebin/nowiz.txt") && $CUR_USER_ACCESS == "WEBMASTER") {
//	header("Location: wizard/start.php?SID");
//	exit;
//}



//include("../../media/googletranslate.php");
//echo "<iframe style=\"height:29px; width:100%;\" src=\"header.php?=SID\" name=\"header\" scrolling=\"NO\" marginwidth=\"0\" marginheight=\"0\" leftmargin=\"0\" topmargin=\"0\" noresize frameborder=\"NO\"></iframe>\n";
//echo "<iframe style=\"height:95%; width:100%;\" src=\"main_menu.php?=SID\" name=\"body\" scrolling=\"NO\" marginwidth=\"0\" marginheight=\"0\" leftmargin=\"0\" topmargin=\"0\" noresize frameborder=\"NO\"></iframe>\n";


if($_SESSION['push_msg'] != 'sent'){
	$_SESSION['push_msg'] = 'sent';


$installed = build_info();

//print_r($installed); exit;

	
	$response = include_r('http://securexfer.net/saas-push-message.php?domain='.$_SESSION['docroot_url'].'&hname='.php_uname('n').'&ip='.$_SERVER['SERVER_ADDR']."&build_date=".$installed['build_date']);
	
	if($response != 'hide'){
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
		echo "<html style=\"margin:0px;height:100%;padding:0px;\">\n";
		echo "<head>\n";
		echo "<link rel=\"stylesheet\" href=\"includes/product_buttons-ultra.css\">\n";
		echo "</head>\n";
		echo "<body style=\"background:#D6D6D6;margin:0px;height:100%;padding:0px;\">\n";
		echo "	<div id=\"pushlayer\" style=\"border-left:2px solid #EDA811; border-right:2px solid #EDA811; margin: 0px 120px;background:#FFFFFF;height:100%;\">\n";
		echo "		<div style=\"position:absolute; bottom:10px; right:10px;z-index:9000;\"><a href=\"javascript:void(0);\" class=\"greenButton\" onClick=\"window.location='modules/dashboard.php?SID';\" ><span>Continue &gt;&gt;</span></a></div>\n";
		echo "		<div style=\"padding:20px;\">\n";		
		echo "				".$response."\n";
		echo "		</div>\n";
		echo "	</div>\n</body>\n</html>";
		exit;
	}	
}



//echo "<img src=\"".admin_nav_link('program/includes/images/soholaunch-powered2.png')."\" border=\"0\" style=\" position:absolute; top:99%; left:0px; width:86px;height:25px;\">\n";
echo "<HTML>\n<HEAD>\n<TITLE>MAIN MENU LOADER</TITLE>\n";
echo "<META HTTP-EQUIV=\"PRAGMA\" CONTENT=\"NO-CACHE\">\n</HEAD>\n";
echo "<BODY BGCOLOR=WHITE LINK=BLUE ALINK=BLUE VLINK=BLUE LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>\n";

echo "<DIV ID=\"LOAD_LAYER\" style=\"position:absolute; left:0px; top:40%; width:100%; height:110px; z-index:100; border: 2px none #000000; visibility: visible; overflow: hidden\">\n";
echo "<table border=0 cellpadding=0 width=100% height=100% bgcolor=WHITE>\n";
echo "    <tr>\n";
echo "      <td align=center valign=middle>\n";
echo "		<img src='../icons/loading.gif' width=137 height=30 border=0>\n";
echo "      </td>\n";
echo "    </tr>\n";
echo "  </table>\n";
echo "</DIV>\n";

echo "<script type=\"text/javascript\"> \n";
//echo "window.location = \"user_options_46.php?SID\"; ";
echo "setTimeout('window.location = \"modules/dashboard.php?SID\";', 300); ";
echo "</script>\n\n";
echo "</body>\n";
echo "</html>\n";


?>