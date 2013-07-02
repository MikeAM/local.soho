<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


error_reporting(E_PARSE);

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
## Copyright 1999-2003 Soholaunch.com, Inc.  All Rights Reserved.
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

##################################################################################
### GET PAGE PROPERTY DATA
### In late updates to the product, the "site_pages" table was utilized to hold
### data that was not indicated by the original fieldnames.  This routine
### pulls data such as the security code and page type into memory.
###
### The "password" field houses Keywords and Gateway HTML code seperated by
### the phrase "~~~SEP~~~".
##################################################################################

$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../../includes/product_gui.php');
chdir($curdir);
$globalprefObj = new userdata('global');

$pagePossible = $currentPage;
$gatewayKeyPage = str_replace("_", " ", $currentPage);

$result = mysql_query("SELECT prikey, page_name, url_name, type, custom_menu, sub_pages, sub_page_of, password, main_menu, link, username, splash, bgcolor, title, description, template FROM site_pages WHERE page_name = '$gatewayKeyPage'");
$tmp = mysql_num_rows($result); // In case there is an underscore that exists in the page name
if ($tmp <= 0) {
	$gatewayKeyPage = $pagePossible;
	$result = mysql_query("SELECT prikey, page_name, url_name, type, custom_menu, sub_pages, sub_page_of, password, main_menu, link, username, splash, bgcolor, title, description, template FROM site_pages WHERE page_name = '$gatewayKeyPage'");
}

// Build page properties
// --------------------------------------------------------
$row = mysql_fetch_array ($result);

	$PROP_name = $row["page_name"];
	$PROP_splash = $row["splash"];
	$prop_bgcolor = $row["bgcolor"];
	$PROP_sec_code = $row["username"];
	$PROP_pagetype = $row["type"];
   $CUR_TEMPLATE = $row["template"];
	$tmp = $row["password"];
	$key_gate = split("~~~SEP~~~", $tmp);

	$prop_title = $row[title];
	$KEYwords = $key_gate[0];
	$prop_desc = $row[description];
	$GATEway = $key_gate[1];

	$result = mysql_query("SELECT * FROM sec_codes");
		$numberRows = mysql_num_rows($result);
		$a=0;
		while ($row = mysql_fetch_array ($result)) {
			$a++;
			$PROP_CODES[$a] = $row["security_code"];
		}

	$PROP_NUMCODES = $a;

##################################################################################
### Set shopping cart "object" flag to on.  In earlier versions, this would
### detect if the cart data table existed and only display the object on the
### object bar if it did.  It was taking up "load time" so it was deleted under
### the info that 98% of clients using this system where using a cart.
##################################################################################

$shoppingcartdb = 1;


// Set newsletter object to on -- assuming everyone has access to newseletter system
// Customers who don't have the newsletter can still use the signup object.
// This creates another reason for them to call our you (our client) and purchase an upgrade ;-)
// 1. End-user made money from the newsletter campaign they didn't know could be so easy...
// 2. Our client made money by selling end-user the 'newsletter package' upgrade
// 3. We made money by selling our client the license for the newsletter module
//
// ....and all because we set $newsletterflag=1 by default :)

$newsletterflag = 1;

##################################################################################
### READ MEDIA FILES INTO MEMORY
##################################################################################

$flashmedia = 0;
$mp3media = 0;
$pdfmedia = 0;
$videomedia = 0;
$custommedia = 0;
$msword = 0;
$msexcel = 0;
$mspowerpoint = 0;
$memberBases = 0;
$zip_files = 0;

$directory = "$doc_root/media";
$handle = opendir("$directory");
	while ($files = readdir($handle)) {
		if (strlen($files) > 2) {
			if (eregi("\.avi", $files) || eregi("\.mov", $files) || eregi("\.mpeg", $files) || eregi("\.mpg", $files) || eregi("\.asf", $files) || eregi("\.wmv", $files) || eregi("\.asx", $files) || eregi("\.wmv", $files) || eregi("\.js", $files) || eregi("\.rm", $files) || eregi("\.ipx", $files)) {
				$videomedia++;
				$videofile[$videomedia] = $files;
			}
			if (eregi("\.mp3", $files) || eregi("\.mp4", $files) || eregi("\.wav", $files) || eregi("\.wma", $files) || eregi("\.mid", $files) || eregi("\.midi", $files)) {
				$mp3media++;
				$mp3file[$mp3media] = $files;
			}
			if (eregi("\.zip", $files) || eregi("\.tar", $files) || eregi("\.tgz", $files) || eregi("\.exe", $files) || eregi("\.rpm", $files)) {
				$zip_files++;
				$compressed_files[$zip_files] = $files;
			}
			if (eregi("\.xls", $files) || eregi("\.csv", $files) ) {
				$msexcel++;
				$excelfile[$msexcel] = $files;
			}
			if ( eregi("\.doc", $files) || eregi("\.txt", $files) ) {
				$msword++;
				$wordfile[$msword] = $files;
			}
			if (eregi("\.ppt", $files) || eregi("\.pps", $files)) {
				$mspowerpoint++;
				$pptfile[$mspowerpoint] = $files;
			}
			if (eregi("\.pdf", $files)) {
				$pdfmedia++;
				$pdffile[$pdfmedia] = $files;
			}
			if (eregi("\.htm", $files) || eregi("\.html", $files) || eregi("\.inc", $files) || eregi("\.nc", $files) || eregi("\.php", $files)) {
				$custommedia++;
				$customfile[$custommedia] = $files;
			}
			if (eregi("\.swf", $files) || eregi("\.flv", $files)) {
				$flashmedia++;
				$flashfile[$flashmedia] = $files;
			}
			if (eregi("udt-", $files)) {
				$memberBases++;
				$memberdatabase[$memberBases] = $files;
			}
		}
	}
closedir($handle);

##################################################################################
### READ IMAGE FILES INTO MEMORY
##################################################################################

$count = 0;
$directory = "$doc_root/images";
$handle = opendir("$directory");
	while ($files = readdir($handle)) {

		if (strlen($files) > 2) {
			$count++;
			$imageFile[$count] = $files . "~~~" . $files;
		}
	}
$numImages = $count;
closedir($handle);

//natcasesort($imageFile);

if ($count != 0) {
	natcasesort($imageFile);
	if ($count == 1) {
		$imageFile[0] = $imageFile[1];
	}
	$numImages--;
}
//echo testArray($imageFile); exit;

##################################################################################
### GET CURRENT SITE PAGES FROM DATABASE (For Linking Purposes)
##################################################################################


$result = mysql_query("SELECT prikey, page_name, url_name, link FROM site_pages WHERE type = 'Main' ORDER BY page_name");

$numberRows = mysql_num_rows($result);
$a=0;
while ($row = mysql_fetch_array ($result)) {
	$a++;
	$page_name[$a] = $row["page_name"];
	$pageLink[$a] = $row["link"];
}

$numSitePages = $a;

$result = mysql_query("SELECT prikey, page_name, url_name, link FROM site_pages WHERE type = 'newsletter' AND page_name = '$currentPage' ORDER BY page_name");

$numberNewsRows = mysql_num_rows($result);
if ($numberNewsRows != 0) {
	$theNewsFlag = 1;
} else {
	$theNewsFlag = 0;
}

$thisPage = "$currentPage.regen";
$thisPage = eregi_replace(" ", "_", $thisPage);

##################################################################################
### READ CURRENT PAGE INTO MEMORY
### Please note that when pages are saved in this system, two text files are
### created.  One holds the final HTML to be displayed by the web site (.con), the other
### is the same name with a .regen extenstion.  This .regen file holds the text
### that is reinterpreted here for use in the editor.  IF YOU MODIFY THIS, YOU BEST
### know what you are doing first!
##################################################################################

$regenFile = "$cgi_bin/$thisPage";
if(file_exists("$regenFile")) {
	$file = fopen("$regenFile", "r");
		$body = fread($file,filesize($regenFile));
	fclose($file);
} else {
	$body = '      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
<div id="NEWOBJ11524644" class="droppedItem" style="height: 120px;"><img src="images/text_header.gif" style="cursor: move;" align="center" border="0" height="15" hspace="0" vspace="0" width="199"><br clear="ALL"><div id="EDITOBJ11524644" class="TXTCLASS" onclick="startEditor(\'EDITOBJ11524644\');" align="left"><blink>Click here to add content.</blink></div></div><!-- ~~~ -->!~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
      <img src="pixel.gif" border="0" height="50%" width="199">     !~!
0!~!';
}
	
	$body = preg_replace('~<div([^>]*)(class="TXTCLASS")([^>]*)(onclick="")([^>]*)>~i', '<div$1$2$3onclick="startEditor(this.id);"$5>', $body);
	//$body = preg_replace('~<div([^>]*)(class="TXTCLASS")([^>]*)(onclick="")([^>]*)>~i', '<div$1$2$3onclick="startEditor(this.id);"$5>', $body);

	
	$body = str_replace("192.168.1.102","$this_ip",$body);	// Re-Write Wizard Generated Content 4.6 Exclusive

	// Fix Wizard Bug found mostly on Windows OS (V4.6) Dec 2003
	// -------------------------------------------------------------
	$body = eregi_replace("\n","@WIZFIX@", $body);
	$body = eregi_replace("\r", "", $body);
	$body = eregi_replace("@WIZFIX@", "\r", $body);
	$regenLine = split("!~!\r", $body);

	$a=0;
	for ($x=1;$x<=10;$x++) {
		for ($y=1;$y<=3;$y++) {
			$varTemp = "R" . $x . "C" . $y;
			${$varTemp} = $regenLine[$a];
			//echo "(".${$varTemp}.")<br/><br/>";


			if (eregi("##CUSTOMHTML;", $regenLine[$a])) {

			}


			$a++;
		}
	}

	$numHidden = $regenLine[30];

	$a=0;
	for($x=1;$x<=$numHidden;$x++) {
		$thisOne = 30 + $x;
		$a++;
		$thisName = split("PICLINK", $regenLine[$thisOne]);
		$hiddenValue[$a] = "<input type=hidden name=\"PICLINK$thisName[0]\" value=\"$regenLine[$thisOne]\">\n";
	}

	$totalHidden = $a;	
//	echo "<textarea style=\"width:900px;height:500px;\">".htmlspecialchars($body)."</textarea>\n";
//	exit;
	
//
//} else {
//
//	$a=0;
//	for ($x=1;$x<=10;$x++) {
//		for ($y=1;$y<=3;$y++) {
//			$varTemp = "R" . $x . "C" . $y;
//			${$varTemp} = "<IMG height=50% src=pixel.gif width=199 border=0>";
//			$a++;
//		}
//	}
//	$totalHidden = 0;
//}

#####################################################################################
### We have just interpreted the page for use in the editor, return to main routine
######################################################################################

if($CUR_TEMPLATE == ""){

   #######################################################
   ### READ CURRENT BASE TEMPLATE NAME INTO MEM   		###
   #######################################################
//   $filename = $doc_root."/sohoadmin/tmp_content/template.conf";
//   if (file_exists("$filename")) {
//   	$file = fopen("$filename", "r");
//   	$CUR_TEMPLATE = fread($file,filesize($filename));
//   	fclose($file);
//   	$CUR_TEMPLATE = rtrim($CUR_TEMPLATE);
//   }
	$CUR_TEMPLATE = rtrim($globalprefObj->get('site_base_template'));
   
}



//echo "(".$CUR_TEMPLATE.")";

?>