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
error_reporting(E_PARSE);

# Include shared functions script - just need lang()
include("../../includes/shared_functions.php"); // MANTIS #157

#######################################################
### READ IMAGE FILES INTO MEMORY					###
#######################################################	

$count = 0;
$directory = "../../../../images"; // Mantis #0000028

$handle = opendir($directory);
	while ($files = readdir($handle)) {
		if (strlen($files) > 2) {
			$count++;
			$imageFile[$count] = ucwords($files) . "~~~" . $files;
		}
	}
$numImages = $count;
closedir($handle);

# Were any images found?
if ($count != 0) {
	sort($imageFile);
	if ($count == 1) {
		$imageFile[0] = $imageFile[1];
	}
	$numImages--;	

}

?>

<HTML>
<HEAD>
<TITLE>Insert Image</TITLE>

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
	
	
	function placeImage() {
		var alignimg = "";
		var imagename = IMAGESELECT.options(IMAGESELECT.selectedIndex).value;
		var alignment = imgalign.options(imgalign.selectedIndex).value;
		imagename = imagename.toString();
		alignment = alignment.toString();
		var imgw = IMGW.value;
		var imgh = IMGH.value;
		var imgalt = IMGALT.value;
		if (imgh != "") { var heightins = " HEIGHT="+imgh; }
		if (imgw != "") { var widthins = " WIDTH="+imgw; }
		if (alignment != "") { var alignimg = " ALIGN="+alignment; }
		var tmp = imagename.split("~");
		var sname = tmp[0];
		
		// v4.7 r041004 - Causes problems if full domain not yet resolved (raised by WebFarm)
	   var thisImage = "http://<? echo $_SESSION['docroot_url']; ?>/images/"+sname;
		// var thisImage = "<? echo $doc_root; ?>/images/"+sname;
	   
		IMAGESELECT.selectedIndex=0;
		imgalign.selectedIndex=0;
		IMGW.value = "";
		IMGH.value = "";
		
		var oControlRange = window.opener.idEdit.document.selection.createRange();
		var sText = "<IMG SRC='"+thisImage+"' "+heightins+" "+widthins+" BORDER=0 ALT='"+imgalt+"' "+alignimg+">";
		//var sText = sText.replace("http","");
		//alert(sText);
		oControlRange.pasteHTML(sText);
		self.close();
		
	} // End Image Placement Function

</SCRIPT>

</HEAD>

<BODY STYLE="margin:5pt;padding:0pt;cursor:default;background:#ECE9D8;">
		<TABLE class=toolbar CELLSPACING=0 CELLPADDING=4 STYLE="background:#ECE9D8;height:50px;" WIDTH="100%">
		<TR>
		<TD ALIGN=CENTER VALIGN=TOP NOWRAP CLASS="text">
			Image: <select name="IMAGESELECT" onchange="dosize();" style='width: 200px;'>
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
         
        	W: <input type=text id="IMGW" name="IMGW" size=5 class="txtbox"> 
			H: <input type=text id="IMGH" name="IMGH" size=5 class="txtbox"><BR><BR>
			Align: 
			  <SELECT NAME="imgalign" id="imgalign" class=ctableopt>
				<option value="" SELECTED>Default</option>
				<option value="left"><? echo lang("Left"); ?></option>
				<option value="right"><? echo lang("Right"); ?></option>
				<option value="top"><? echo lang("Top"); ?></option>
				<option value="middle"><? echo lang("Middle"); ?></option>
				<option value="bottom"><? echo lang("Bottom"); ?></option>
				<option value="absmiddle"><? echo lang("Absolute Middle"); ?></option>
				</select>
			
			Alt: <input type=text name="IMGALT" id="IMGALT" size=35 class="txtbox"> 
			
			</TD><TD ALIGN=LEFT VALIGN=MIDDLE NOWRAP CLASS="text">
			
			<input type=button value="Ok" style='width:50px;' onclick="placeImage();" class="FormLt1">
			<input type=button value="Cancel" style='width:50px;' onclick="self.close();" class="FormLt1">
			
		</TD>
		</TR></TABLE>
	
</body>