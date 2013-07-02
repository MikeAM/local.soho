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

session_start();
require_once("../../../includes/product_gui.php");


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
			if (eregi("\.xls", $files) || eregi("\.csv", $files)) {
				$msexcel++;
				$excelfile[$msexcel] = $files;
			}
			if (eregi("\.doc", $files)) {
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
	$filearrArr = array('videofile', 'mp3file', 'excelfile', 'wordfile', 'pptfile', 'pdffile', 'customfile', 'flashfile', 'memberdatabase');
	foreach ( $filearrArr as $fileArr ) {
		natcasesort(${$fileArr});
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


if($_GET['type'] == "img"){

   echo "<SELECT ID=\"oSel\" ONCHANGE=\"getImageData()\" STYLE='font-family: Arial; font-size: 8pt; width: 250px;'>\n";
   echo "<OPTION VALUE=\"NONE\" STYLE='color: #999999;'>Current Images:</OPTION>\n";
   
   foreach ( $imageFile as $imageData ) {
   
      if ($tmp == "#EFEFEF") { $tmp = "WHITE"; } else { $tmp = "#EFEFEF"; }
      
      $thisImage = split("~~~", $imageData);
      
      if (file_exists("$doc_root/images/$thisImage[1]")) {
         $tempArray = getImageSize("$doc_root/images/$thisImage[1]");
         $origW = $tempArray[0];
         $origH = $tempArray[1];
         $WH = "width=\"".$origW."\" height=\"".$origH."\"";
         if ($origW > 199) {
            $WH = "width=\"199\"";
         }
      }
      
      echo "<OPTION VALUE=\"".$thisImage[1]."\" ".$WH." STYLE='background: ".$tmp.";'>".$thisImage[0]."</OPTION>\n";
   }
   
   echo "</SELECT>\n";

}elseif($_GET['type'] == "doc"){

	echo "<select id=\"mswordname\" name=\"mswordname\" onChange=\"displayDoc()\" STYLE='font-family: Arial; font-size: 8pt; width: 250px;'>\n";
	echo "   <option value=\"NONE\" STYLE='COLOR: #999999;'>Choose Document:</option>\n";

	foreach ($pdffile as $key=>$value) {
		if ( trim($value) != '' ) {
			echo "     <option value=\"".$value."\" STYLE='COLOR: white; background: #AF0000;'>$value</option>\n";
		}
	}
	
	foreach ($wordfile as $key=>$value) {
		if ( trim($value) != '' ) {
			echo "     <option value=\"".$value."\" STYLE='COLOR: white; background: darkblue;'>".$value."</option>\n";
		}
	}
	
	foreach ($excelfile as $key=>$value) {
		if ( trim($value) != '' ) {
			echo "     <option value=\"$value\" STYLE='COLOR: white; background: darkgreen;'>$value</option>\n";
		}
	}
	
	foreach ($pptfile as $key=>$value) {
		if ( trim($value) != '' ) {
			echo "     <option value=\"$value\" STYLE='COLOR: black; background: gold;'>$value</option>\n";
		}
	}
	
	foreach ($compressed_files as $key=>$value) {
		if ( trim($value) != '' ) {
			echo "     <option value=\"$value\" STYLE='COLOR: black; background: yellow;'>$value</option>\n";
		}
	}
   
   echo "</select>\n";
   
}elseif($_GET['type'] == "mp3"){
		
		echo "Filename: &nbsp;\n";
		echo "   <SELECT id=\"mp3name\" NAME=\"mp3name\" STYLE='font-family: Arial; font-size: 8pt; width: 350px;'>\n";
		echo "      <option value=\"NONE\" style='color: #999999;'>Audio Files:</option>\n";
	natcasesort($mp3file);
	foreach($mp3file as $afile){
		//for ($a=1;$a<=$mp3media;$a++) {
			if ($tmp == "#EFEFEF") { $tmp = "WHITE"; } else { $tmp = "#EFEFEF"; }
			echo "<option value=\"$afile\" style='background: $tmp;'>$afile</option>\n";
		}

		echo "</select>\n";
		
}elseif($_GET['type'] == "video"){

		echo "<b>Video File:</b>\n";

		echo "<SELECT id=\"videoname\" NAME=\"videoname\" onChange=\"vidSize(this.value)\" STYLE=\"font-family: Arial; font-size: 8pt; width: 300px;\">\n";
		echo "<OPTION VALUE=\"NONE\" style='color: #999999;'>Video Files:</OPTION>\n";

		for ($a=1;$a<=$videomedia;$a++) {
			if (filesize("../../../../media/".$videofile[$a]) < '625000') {
				$mediaimagesize = getimagesize("../../../../media/".$videofile[$a]);
			}
         $mediaheight = $mediaimagesize[1];
         $mediawidth = $mediaimagesize[0];
			   if ($tmp == "#EFEFEF") { $tmp = "WHITE"; } else { $tmp = "#EFEFEF"; }
			   echo "<option value=\"".$videofile[$a].";".$mediawidth.";".$mediaheight."\" STYLE='background: ".$tmp.";'>".$videofile[$a]."</option>\n";
		}

		for ($a=1;$a<=$flashmedia;$a++) {
			if (filesize("../../../../media/".$flashfile[$a]) < '625000') {
				$mediaimagesize = getimagesize("../../../../media/".$flashfile[$a]);
			}
         $mediaheight = $mediaimagesize[1];
         $mediawidth = $mediaimagesize[0];
			//echo "[<b>".$flashfile[$a]."</b>] = (".$mediawidth." x ".$mediaheight.")<br>";

			   if ($tmp == "#EFEFEF") { $tmp = "WHITE"; } else { $tmp = "#EFEFEF"; }
		   	echo "<option value=\"".$flashfile[$a].";".$mediawidth.";".$mediaheight."\" STYLE='background: ".$tmp.";'>".$flashfile[$a]."</option>\n";
		}

		echo "</SELECT> &nbsp;\n";

}elseif($_GET['type'] == "custom"){

	echo "Select Include File: &nbsp;\n";

	echo "<SELECT id=\"customname\" NAME=\"customname\" STYLE='font-family: Arial; font-size: 8pt; width: 250px;'>\n";
	echo "   <option value=\"NONE\" style='color: #999999;'>Custom Code Files:</option>\n";

	natcasesort($customfile);

	foreach($customfile as $custommedia){
		if (strlen($custommedia) >= 2 && $custommedia!='index.php') {
			if ($tmp == "#EFEFEF") { $tmp = "WHITE"; } else { $tmp = "#EFEFEF"; }
			echo ("<option value=\"$custommedia\" STYLE='background: $tmp;'>$custommedia</option>\n");
		}
	}

	echo "</SELECT>\n";

}

?>