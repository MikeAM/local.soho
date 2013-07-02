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

require_once('../../includes/product_gui.php');


$MENUTYPE = 'textlink';
######################################################
### READ TEXT AND ALIGNMENT SETTING INTO MEMORY    ###
######################################################

eval(hook("update_menu.php:save_menu"));

$filename = "$cgi_bin/menu.conf";
if (file_exists("$filename")) {
	$file = fopen("$filename", "r");
		$body = fread($file,filesize($filename));
	fclose($file);
	$pConfig = split("\n", $body);
	$numLines = count($pConfig);
	for ($x=0;$x<=$numLines;$x++) {
		$temp = split("=", $pConfig[$x]);
		$variable = $temp[0];
		$value = $temp[1];
		${$variable} = $value;
	}
}

$seo = new userdata("seolink");
$seo->set("pref", 'yes');


# Add new external link
if($_POST['do'] == "saveLink"){

	//$daPage = str_replace("_", " ", $_POST['disPage']);
	$daLink = $_POST['disLink'];
	if($_POST['new_window'] == 'blank'){
	   
      $daLink = $daLink."#blank";
	   //$daLink = $daLink."\" target=\"_blank";
	}
	$daPage = str_replace("_", " ", $_POST['disPage']);
//	echo "da page (".$daPage.")<br>";
//	echo "da link (".$daLink.")<br>";
//	exit;
	$SQL_INSERT = "(page_name, type, sub_page_of, password, main_menu, link, username, splash, bgcolor, title, description, template) VALUES('".$daPage."','menu','', '', '','".$daLink."','','','','','','')";
	if(!eregi('^http', $daLink) && !eregi('^mailto', $daLink)){
		echo "<script type=\"text/javascript\">alert('Could not create page link because the link (".htmlspecialchars($_POST['disLink'], ENT_QUOTES).") must start with http:// , https:// , or mailto: .');document.location.href='../auto_menu_system.php';</script>";
	} else {
		if ( !$mkLinks = mysql_query("INSERT INTO site_pages $SQL_INSERT") ) {
		   echo "<script type=\"text/javascript\">alert('Could not create page link because link text (".htmlspecialchars($daPage, ENT_QUOTES).") matches name of existing page or link.');document.location.href='../auto_menu_system.php';</script>";
		   //echo "MySQL Error: [".mysql_error()."]\n";
		   //exit;
		}
	}
	header("Location: ../auto_menu_system.php?lok=1&=SID");
	exit;
}else{

#########################################################
### FIRST, DELETE ALL MENU NUMBER REFERENCES AND      ###
### SUB PAGE REFERENCES FROM site_pages TABLE         ###
#########################################################

// The following lines were replaced by the correct SQL command that follows this comment:
/*$result = mysql_query("SELECT page_name FROM site_pages WHERE type = 'Main'");

while ($row = mysql_fetch_array ($result)) {
	mysql_query("UPDATE site_pages SET main_menu = '' WHERE page_name = '$row[page_name]'");
	mysql_query("UPDATE site_pages SET sub_page_of = '' WHERE page_name = '$row[page_name]'");
}*/
mysql_query( "UPDATE site_pages SET main_menu = '', sub_page_of = '' WHERE lower(type) = 'main' OR type = 'menu'" );

function filterList( $item )
{
    if ( $item )
        return true;
    return false;
}

#########################################################
### LOOP THRU PASSED VARS AND BUILD BASED ON EXPECTED ###
### VARIABLE DATA.  SOME PASSED VARS ARE UNIMPORTANT. ###
#########################################################

$tmp = array_filter( explode( "\n", $newMenu ), 'filterList' );



reset($HTTP_POST_VARS);

while (list($name, $value) = each($HTTP_POST_VARS)) {


	$value = stripslashes($value);
	${$name} = $value;
}

		$tmpCnt = count($tmp);

		$a=0;

		for ($x=0; $x < $tmpCnt; $x++)
		{
            if ( ! $tmp[$x] )
                continue;
			elseif ( !ereg(">", $tmp[$x]) )
			{
				$a++;

				// Update Data Table with Numeric Value $a
				// ----------------------------------------

				$page_name = chop($tmp[$x]); // Clear all extra spaces for exact page name

				mysql_query("UPDATE site_pages SET main_menu = '$a' WHERE page_name = '$page_name'");
			}
			elseif (ereg(">", $tmp[$x]))
			{
				$this_page = ereg_replace(">> ", "", $tmp[$x]);
				$this_page = chop($this_page); // Clear all extra spaces for exact page name

				// Update Data Table with "Sub Page Of" data regarding this Main Page
				// -------------------------------------------------------------------
            if ( strlen($x) < 2 ) { $y = "0".$x; } else { $y = $x; } // So sort doesn't start screwing up after 9th sub menu item
				mysql_query("UPDATE site_pages SET sub_page_of = '$page_name~~~$y' WHERE page_name = '$this_page'");

			}

		} // End For $x Loop

$filename = "$cgi_bin/menu.conf";

if ( file_exists($filename) ) {
   //echo "About to delete $filename...<br>";
   unlink($filename);
}

if ( !$file = fopen("$filename", "w") ) {
   echo "Could not open $filename for writing!"; exit;
}

	fwrite($file, "mainmenu=$horizvert\n");
	fwrite($file, "submenu=$horizvert\n");
 	fwrite($file, "locationbar=\n");
 	fwrite($file, "textmenu=$textmenu\n");
	if ( !fwrite($file, "MENUTYPE=$MENUTYPE\n") ) {
	   echo "Could not write to $cgi_bin/menu.conf!";
	   exit;
	}
fclose($file);

header("Location: ../auto_menu_system.php?ok=1&=SID");
exit;
}
?>
