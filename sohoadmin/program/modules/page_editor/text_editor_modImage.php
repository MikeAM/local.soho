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

error_reporting(E_PARSE);

# Emulate register_globals=1
//include("../../../includes/emulate_globals.php");
require_once("../../includes/product_gui.php");

session_start();

/// Ensure 100% legit docroot var is available
###-------------------------------------------------------------------------------------
if ( !is_dir($_SESSION['docroot_path']) ) {

   # Known aspects of path
   $clipknown = DIRECTORY_SEPARATOR."sohoadmin".DIRECTORY_SEPARATOR."program".DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."page_editor".DIRECTORY_SEPARATOR.basename(__FILE__);

   # Strip away fluff and presto: garaunteed-accurate docroot path
   $_SESSION['docroot_path'] = str_replace( $clipknown, "", __FILE__);

   # Define domain root path (for html stuff)
   $_SESSION['docroot_url'] = $_SERVER['HTTP_HOST'].str_replace( $clipknown, "", $_SERVER['PHP_SELF'] );

   # Define path to interface graphics directory
   $_SESSION['icon_dir'] = $_SESSION['docroot_url'].DIRECTORY_SEPARATOR."program".DIRECTORY_SEPARATOR."includes".DIRECTORY_SEPARATOR."display_elements".DIRECTORY_SEPARATOR."graphics".DIRECTORY_SEPARATOR;
}


#######################################################
### READ IMAGE FILES INTO MEMORY					###
#######################################################

$count = 0;
$directory = "../../../../images";
$handle = opendir("$directory");
	while ($files = readdir($handle)) {
		if (strlen($files) > 2) {
			$count++;
			$imageFile[$count] = ucwords($files) . "~~~" . $files;
		}
	}
$numImages = $count;
closedir($handle);

if ($count != 0) {
	sort($imageFile);
	if ($count == 1) {
		$imageFile[0] = $imageFile[1];
	}
	$numImages--;

}

# Include shared functions script - just need lang()
# Should eliminate session passing issues
include_once("../../includes/shared_functions.php"); // Mantis #350

?>

<HTML>
<HEAD>
<TITLE>Modify Image Properties</TITLE>

<link rel="stylesheet" href="soholaunch.css">

<STYLE>
	SELECT {font:7pt Verdana;background:#FFFFFF;}
	.txtbox {font:7pt Verdana;background:#FFFFFF}
	.toolbar {margin-bottom:3pt;height:28;overflow:hidden;background:white;border:0px none solid}
	.mode LABEL {font:8pt Arial}
	.mode .current {font:bold 8pt Arial;color:darkblue}
	.heading {color:navy;background:#FFFFFF}
	.tblEdit { BORDER-RIGHT: black 1px dashed; BORDER-TOP: black 1px dashed; BORDER-LEFT: black 1px dashed; BORDER-BOTTOM: black 1px dashed; }
</STYLE>

<SCRIPT>

	// Center New Window on Screen
	// ----------------------------------------
	var width = (screen.width/2) - (600/2);
	var height = (screen.height/2) - (40/2);
	window.moveTo(width,height);

	// Place Text Editor Window on top
	// ----------------------------------------
	window.focus();

	function dosize() {
		var imagename = IMAGESELECT.options(IMAGESELECT.selectedIndex).value;
		imagename = imagename.toString();
		var array = imagename.split("~");
		var tmpw = array[1];
		var tmph = array[2];
		IMGW.value = tmpw;
		IMGH.value = tmph;
	}

	function setValues() {
		var oControlRange = window.opener.idEdit.document.selection.createRange();

		var imgSrc = oControlRange(0).src;
		var imgW = oControlRange(0).width;
		var imgH = oControlRange(0).height;
		var imgAlign = oControlRange(0).align;
		var imgAlt = oControlRange(0).alt;

		IMGW.value = imgW;
		IMGH.value = imgH;
		document.getElementById('IMGALT').value = imgAlt;
		imgalign.options.value = imgAlign;

		var tmpImgName = imgSrc+"~"+imgW+"~"+imgH;
		var tmpImgName = tmpImgName.replace(/http:\/\/<? echo $_SESSION['docroot_url']; ?>\/images\//gi, "");
		IMAGESELECT.options.value = tmpImgName;
	}

	window.onload = setValues;

	function updateImage() {

		var alignimg = "";
		var imagename = IMAGESELECT.options(IMAGESELECT.selectedIndex).value;
		//alert('['+imgalign.selectedIndex+']');
		if ( imgalign.selectedIndex >= 0 ) { var alignment = imgalign.options(imgalign.selectedIndex).value; }
		imagename = imagename.toString();
		if ( imgalign.selectedIndex >= 0 ) { alignment = alignment.toString(); }
		var imgw = IMGW.value;
		var imgh = IMGH.value;
		var imgalt = IMGALT.value;
		if (imgh != "") { var heightins = " HEIGHT="+imgh; }
		if (imgw != "") { var widthins = " WIDTH="+imgw; }
		if ( imgalign.selectedIndex >= 0 ) { var alignimg = " ALIGN="+alignment; }
		var tmp = imagename.split("~");
		var sname = tmp[0];

		// v4.7 r041004 - Causes problems if full domain not yet resolved (raised by WebFarm)
		var thisImage = "http://<? echo $_SESSION['docroot_url']; ?>/images/"+sname;
		//var thisImage = "<? echo $doc_root; ?>/images/"+sname;


		IMAGESELECT.selectedIndex=0;
		if ( imgalign.selectedIndex >= 0 ) { imgalign.selectedIndex=0; }
		IMGW.value = "";
		IMGH.value = "";

		var oControlRange = window.opener.idEdit.document.selection.createRange();
		oControlRange(0).src = thisImage;
		oControlRange(0).width = imgw;
		oControlRange(0).height = imgh;
		//if (alignment != "") { oControlRange(0).align = alignment; }
		oControlRange(0).alt = imgalt;
		self.close();

	} // End Image Placement Function

</SCRIPT>

</HEAD>

<BODY STYLE="margin:5pt;padding:0pt;cursor:default;background:#ECE9D8;">
		<TABLE class=toolbar CELLSPACING=0 CELLPADDING=4 STYLE="background:#ECE9D8;height:50px;" WIDTH="100%">
		<TR>
		<TD ALIGN=CENTER VALIGN=TOP NOWRAP CLASS="text">
			Image: <select name="IMAGESELECT" id="IMAGESELECT" onchange="dosize();" style='width: 200px;'>
			<option value="NONE" selected>Choose One...</option>
			<?
			for ($x=0;$x<=$numImages;$x++) {
				$thisImage = split("~~~", $imageFile[$x]);
				if (file_exists("$directory/$thisImage[1]")) {
					$tempArray = getImageSize("$directory/$thisImage[1]");
					$origW = $tempArray[0];
					$origH = $tempArray[1];
				}
				echo ("<option value=\"$thisImage[1]~$origW~$origH\">$thisImage[0]</option>\n");
			}
			?>

			</SELECT>

        	W: <input type=text name=IMGW size=5 class="txtbox">
			H: <input type=text name=IMGH size=5 class="txtbox"><BR><BR>
			Align: <SELECT NAME="imgalign" class="ctableopt" id="imgalign">
				<option value="" selected>Default</option>
				<option value="left"><? echo lang("Left"); ?></option>
				<option value="right"><? echo lang("Right"); ?></option>
				<option value="top"><? echo lang("Top"); ?></option>
				<option value="middle"><? echo lang("Middle"); ?></option>
				<option value="bottom"><? echo lang("Bottom"); ?></option>
				<option value="absMiddle"><? echo lang("Absolute Middle"); ?></option>
				</select>

			Alt: <input type=text name=IMGALT size=35 class="txtbox" id="IMGALT">

			</TD><TD ALIGN=LEFT VALIGN=MIDDLE NOWRAP CLASS="text">

			<input type=button value="Ok" style='width:50px;' onclick="updateImage();" class="FormLt1">
			<input type=button value="Cancel" style='width:50px;' onclick="self.close();" class="FormLt1">

		</TD>
		</TR></TABLE>

</body>