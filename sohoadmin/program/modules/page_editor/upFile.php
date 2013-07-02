<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

error_reporting(E_PARSE);
session_start();

# Include essential interface files
require_once("../../includes/product_gui.php");

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../product_gui.css\">\n";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"includes/page_editor.css\">\n";
echo "<style>\n";
echo "body {\n";
echo "	margin-left: 0px;\n";
echo "	margin-top: 0px;\n";
echo "	margin-right: 0px;\n";
echo "	margin-bottom: 0px;\n";
echo "}\n";
echo "</style>\n";


echo "<script language=Javascript>\n";
echo "function hideshow(obj){\n";
echo "        if (obj.style.display==\"none\")\n";
echo "             obj.style.display=\"\";\n";
echo "        else\n";
echo "             obj.style.display=\"none\";\n";
echo "    }\n";
echo "function SendVID() {\n";
echo "   var disOne = videoname.selectedIndex;\n";
echo "   var tImage = eval(\"videoname.options[\"+disOne+\"].value\");\n";
echo "	vidw = videow.value;\n";
echo "	vidw = vidw.toString();\n";
echo "	vidh = videoh.value;\n";
echo "	vidh = vidh.toString();\n";
echo "   parent.OkVideoDataUP(tImage,vidw,vidh);\n";
echo "}\n";
echo "function SendMP3() {\n";
echo "   var disOne = mp3name.selectedIndex;\n";
echo "   var tImage = eval(\"mp3name.options[\"+disOne+\"].value\");\n";
echo "   parent.OkMP3DataUP(tImage);\n";
echo "}\n";
echo "function SendCUST() {\n";
echo "   var disOne = customname.selectedIndex;\n";
echo "   var tImage = eval(\"customname.options[\"+disOne+\"].value\");\n";
echo "   parent.OkCustomDataUP(tImage);\n";
echo "}\n";
echo "function SendDOC() {\n";
echo "   var disOne = mswordname.selectedIndex;\n";
echo "   var tImage = eval(\"mswordname.options[\"+disOne+\"].value\");\n";
echo "   parent.OkWordDataUP(tImage);\n";
echo "}\n";
echo "function SendPDF() {\n";
echo "   var disOne = pdfname.selectedIndex;\n";
echo "   var tImage = eval(\"pdfname.options[\"+disOne+\"].value\");\n";
echo "   parent.OkPDFDataUP(tImage);\n";
echo "}\n";
echo "function SendDis() {\n";
echo "   var disOne = oSelUP.selectedIndex;\n";
echo "   var tImage = eval(\"oSelUP.options[\"+disOne+\"].value\");\n";
echo "   parent.getImageDataUP(tImage);\n";
echo "}\n";
echo "function SendDisFinal() {\n";
echo "   var disOne = oSelUP.selectedIndex;\n";
echo "   var tImage = eval(\"oSelUP.options[\"+disOne+\"].value\");\n";
echo "   parent.OkImageDataUP(tImage);\n";
echo "}\n";

echo "function vidSize(ddval) {\n";
echo "   var valStuff = ddval.split(';');\n";
echo "   document.getElementById('videow').value=valStuff[1];\n";
echo "   document.getElementById('videoh').value=valStuff[2];\n";
echo "}\n";

echo "</script>\n";


if ( $todo == "upload_now" ) {
   if ( file_exists($_FILES["quick_upload"]["tmp_name"]) ) {
      $upFile_name = $_FILES["quick_upload"]["name"];
   }
   
	if (strstr($upFile_name, ".gif")) {
		$saveto_path = "../../../../images/".$upFile_name; 
		$fileok = 1;
	}
	
	if (strstr($upFile_name, ".bmp")) {
		$saveto_path = "../../../../images/".$upFile_name; 
		$fileok = 1;
	}

	if (strstr($upFile_name, ".jpg")) {
		$saveto_path = "../../../../images/".$upFile_name;
		$fileok = 1;
	}
	
	if (strstr($upFile_name, ".jpeg")) {
		$saveto_path = "../../../../images/".$upFile_name;
		$fileok = 1;
	}
	
	if (strstr($upFile_name, ".png")) {
		$saveto_path = "../../../../images/".$upFile_name;
		$fileok = 1;
	}	

	if (strstr($upFile_name, ".pdf")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}

	if (strstr($upFile_name, ".rm")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}

	if (strstr($upFile_name, ".wav")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}

	if (strstr($upFile_name, ".ipx")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}

	if (strstr($upFile_name, ".swf")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}

	if (strstr($upFile_name, ".mp3")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}

	if (strstr($upFile_name, ".avi")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}
	
	if (strstr($upFile_name, ".wmv")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}	
	
	if (strstr($upFile_name, ".asf")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}
	
	if (strstr($upFile_name, ".asx")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}
	
	if (strstr($upFile_name, ".mpg")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}
	
	if (strstr($upFile_name, ".mpeg")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}

	if (strstr($upFile_name, ".exe")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}

	if (strstr($upFile_name, ".mov")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}

	if (strstr($upFile_name, ".xls")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}

	if (strstr($upFile_name, ".doc")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}
	
	if (strstr($upFile_name, ".ppt")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}
	
	if (strstr($upFile_name, ".pps")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}

	if (strstr($upFile_name, ".css")) {
		$saveto_path = "../../../../".$upFile_name;
		$fileok = 1;
	}

	if (strstr($upFile_name, ".js")) {
		$saveto_path = "../../../../".$upFile_name;
		$fileok = 1;
	}

	if (strstr($upFile_name, ".php") || strstr($upFile_name, ".inc")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	} 

	if (strstr($upFile_name, ".html") || strstr($upFile_name, ".htm")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}

	if (strstr($upFile_name, ".psd")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}
	
	if (strstr($upFile_name, ".form")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}

	if (strstr($upFile_name, ".txt")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}

	if (strstr($upFile_name, ".exe") || strstr($upFile_name, ".zip") || strstr($upFile_name, ".tar") || strstr($upFile_name, ".tgz") || strstr($upFile_name, ".rpm")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}

	if (strstr($upFile_name, ".csv")) {
		$saveto_path = "../../../../media/".$upFile_name;
		$fileok = 1;
	}
   
	if($fileok == 1) {
   
   # Target file location
   //$saveto_path = "../../../../images/".$_FILES["quick_upload"]["name"];
   if(!copy($_FILES["quick_upload"]["tmp_name"], $saveto_path)) {
      echo "<font style=\"color: red; font-size: 10pt;\">Could not copy file to ".$saveto_path."!<br><br> Please check permissions on this folder.</font>";
   }

   if ( file_exists($saveto_path) ) {
      echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
      echo "   <tr>\n";
      echo "      <td align=\"center\" valign=\"middle\" style=\"font: 12px arial; color: #D70000; border: 1px inset black; background-color: oldlace;\">\n";
      echo "      <br><b>".$upFile_name."</b> has been uploaded!<br>";
      echo "      <form action=\"upFile.php\" method=\"post\">\n";
      echo "         <input type=\"hidden\" name=\"todo\" value=\"choose_img\">\n";
      echo "         <input type=\"hidden\" name=\"type\" value=\"".$type."\">\n";
      echo "         <input type=\"submit\" value=\" OK \">\n";
      echo "      </form>\n";
      echo "      </td>\n";
      echo "   </tr>\n";
      echo "</table>\n";
            
   }
} else {
   $Fsize = $_FILES["quick_upload"]["size"];
   $Ftype = $_FILES["quick_upload"]["type"];
   $Ferror = $_FILES["quick_upload"]["error"];

      echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
      echo "   <tr>\n";
      echo "      <td height=\"10\" bgcolor=\"#6699cc\" STYLE=\"border: 1px inset black;\">&nbsp;\n";
      echo "      </td>\n";
      echo "   </tr>\n";
      echo "   <tr>\n";
      echo "      <td align=\"center\" valign=\"middle\" STYLE=\"border: 1px inset black; background-color: oldlace;\">\n";
      echo "      <form action=\"upFile.php\" method=\"post\">\n";
      echo "         <input type=\"hidden\" name=\"todo\" value=\"UPNOW\">\n";
      echo "         <input type=\"hidden\" name=\"type\" value=\"".$type."\">\n";
      echo "         <br>Your request to upload failed, please click the back button to select a file or click cancel to close.<br>";
      echo "         <font style=\"color: red; font-size: 10pt;\">\n";
      
        switch ($_FILES['quick_upload'] ['error'])
         {  case 1:
                   print '<p> The file is bigger than this PHP installation allows</p>';
                   break;
            case 2:
                   print '<p> The file is bigger than this form allows</p>';
                   break;
            case 3:
                   print '<p> Only part of the file was uploaded</p>';
                   break;
            case 4:
                   print '<p> No file was uploaded</p>';
                   break;
         }      
      echo "         </font>\n";
      echo "         <input type=\"submit\" value=\" Back \" onClick=\"submit();\">&nbsp;&nbsp;&nbsp;&nbsp;\n";      
      echo "         <input type=\"button\" value=\" Cancel \" onClick=\"parent.closeit();\">\n";
      echo "      </form>\n";      
      echo "      </td>\n";
      echo "   </tr>\n";
      echo "</table>\n";   

}
}

if ( $todo == "choose_img" ) {
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
		if (strlen($files) > 2  && $files!='index.php') {
			if (eregi("\.avi", $files) || eregi("\.mov", $files) || eregi("\.mpeg", $files) || eregi("\.mpg", $files) || eregi("\.asf", $files) || eregi("\.wmv", $files) || eregi("\.asx", $files) || eregi("\.wmv", $files) || eregi("\.js", $files) || eregi("\.rm", $files) || eregi("\.ipx", $files)) {
				$videomedia++;
				$videofile[$videomedia] = $files;
			}
			if (eregi("\.mp3", $files) || eregi("\.wav", $files)) {
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
			if (eregi("\.swf", $files)) {
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
   
   if ($count != 0) {
   	sort($imageFile);
   	if ($count == 1) {
   		$imageFile[0] = $imageFile[1];
   	}
   	$numImages--;	
   }

?>



<DIV ID="imageDrop">

<?

//-------------------------------------------------------
//             SELECTION LAYERS
//-------------------------------------------------------

if ($type == "img"){
?>

   <div class="prop_head">Image Selection</div>

	<table cellpadding="0" cellspacing="0" width="100%" class="prop_table">
		<tr>
			<td align="center" valign="middle">
			
   		   <font color="#0000FF">Your Image is now available to use in the current images list!</font><br><br>
				<b>Please Choose an Image:</b> 
				<SELECT ID="oSelUP" ONCHANGE="SendDis()" STYLE='font-family: Arial; font-size: 8pt; width: 250px;'>
					<OPTION VALUE="NONE" STYLE='color: #999999;'>Current Images:</OPTION>

				<?

				for ($x=0;$x<=$numImages;$x++) {
				
					if ($tmp == "#EFEFEF") { $tmp = "WHITE"; } else { $tmp = "#EFEFEF"; }
					
					$thisImage = split("~~~", $imageFile[$x]);
					
					if (file_exists("$directory/$thisImage[1]")) {
						$tempArray = getImageSize("$directory/$thisImage[1]");
						$origW = $tempArray[0];
						$origH = $tempArray[1];
						$WH = "width=$origW height=$origH ";
						if ($origW > 199) {
							$WH = "width=199 ";
						}
					}

					echo "<OPTION VALUE=\"$thisImage[1] $WH\" STYLE='background: $tmp;'>$thisImage[0]</OPTION>\n";
				}
				?>

				</SELECT>

            &nbsp; <input type=button class=mikebut onMouseOver="this.className='mikebutOn';" onMouseOut="this.className='mikebut';" value=" OK " onClick="SendDisFinal();">
				&nbsp;&nbsp; <input type=button class=mikebut onMouseOver="this.className='mikebutOn';" onMouseOut="this.className='mikebut';" value=" Cancel " onClick="parent.replaceImageData();parent.closeUploadWin();">
				<BR><FONT COLOR=#999999 SIZE=1><B><I>To link an image, place on the edit page and click the image.</I></B></FONT>
         </td>
        </tr>
      </table>
<?				
}
if ($type == "doc"){
?> 
   <div class="prop_head">Document Selection</div>

	<table cellpadding="0" cellspacing="0" width="100%" class="prop_table">
		<tr>
			<td align="center" valign="middle">
				<b><? echo lang("Select the Document you wish to place on this page for download"); ?>:</b><BR>

				<select id="mswordname" name="mswordname" STYLE='font-family: Arial; font-size: 8pt; width: 250px;'>
				     <option value="NONE" STYLE='COLOR: #999999;'>Choose Your Document:</option>

				<?
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
	
				?>

				</select>

				</td>
				<td align=center valign="bottom">
				 <input type=button value=" <? echo lang("OK"); ?> " onClick="SendDOC();" <? echo $btn_save; ?> style="width: 75px;">
				 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				 <input type="button" value=" Cancel " onClick="parent.replaceImageData();parent.closeUploadWin();" <? echo $btn_edit; ?>>
				</td>
			  </tr></table>
<?				
}
if ($type == "mp3"){
?> 				

   <div class="prop_head">Audio File Selection</div>

	<table cellpadding="0" cellspacing="0" width="100%" class="prop_table">
		<tr>
			<td align="center" valign="middle">
		<FONT COLOR=DARKBLUE>You may choose to place a .WAV or .MP3 file on this page for site visitors to download.</FONT><BR><BR>
		
		Filename: &nbsp;
		<SELECT id="mp3name" NAME="mp3name" STYLE='font-family: Arial; font-size: 8pt; width: 350px;'>
		<option value="NONE" style='color: #999999;'>Audio Files:</option>

		<?
		
		for ($a=1;$a<=$mp3media;$a++) {
			if ($tmp == "#EFEFEF") { $tmp = "WHITE"; } else { $tmp = "#EFEFEF"; }
			echo "<option value=\"$mp3file[$a]\" style='background: $tmp;'>$mp3file[$a]</option>\n";
		}
		
		?>

		</select>
		
		</td><td align=center valign=middle>
		
		<input type=button class=mikebut onMouseOver="this.className='mikebutOn';" onMouseOut="this.className='mikebut';" value=" OK " onClick="SendMP3();">
		&nbsp;&nbsp;<input type=button class=mikebut onMouseOver="this.className='mikebutOn';" onMouseOut="this.className='mikebut';" value=" Cancel " onClick="parent.replaceImageData();parent.closeUploadWin();">

		</td></tr></table>
<?				
}
if ($type == "vid"){
?>

   <div class="prop_head">Video File Selection</div>

	<table cellpadding="0" cellspacing="0" width="100%" class="prop_table">
		<tr>
			<td align="center" valign="middle">
		<FONT COLOR=DARKBLUE>Select the video file you wish to allow site visitors to view.</FONT><br>
		<FONT COLOR="#999999" SIZE=1><B>[ Acceptable file formats include: .AVI .MOV .MPEG .MPG .WMV .ASF .ASX .IPIX .SWF ]</B></FONT><BR><BR>
		
		Video File: 
		<?
		echo "<SELECT id=\"videoname\" NAME=\"videoname\" onChange=\"vidSize(this.value)\" STYLE=\"font-family: Arial; font-size: 8pt; width: 300px;\">\n";
		echo "<OPTION VALUE=\"NONE\" style='color: #999999;'>Video Files:</OPTION>\n";

		for ($a=1;$a<=$videomedia;$a++) {
			$mediaimagesize = getimagesize("../../../../media/".$videofile[$a]);
         $mediaheight = $mediaimagesize[1];
         $mediawidth = $mediaimagesize[0];
			   if ($tmp == "#EFEFEF") { $tmp = "WHITE"; } else { $tmp = "#EFEFEF"; }
			   echo "<option value=\"".$videofile[$a].";".$mediawidth.";".$mediaheight."\" STYLE='background: ".$tmp.";'>".$videofile[$a]."</option>\n";
		}
		
		for ($a=1;$a<=$flashmedia;$a++) {
			$mediaimagesize = getimagesize("../../../../media/".$flashfile[$a]);
         $mediaheight = $mediaimagesize[1];
         $mediawidth = $mediaimagesize[0];
			//echo "[<b>".$flashfile[$a]."</b>] = (".$mediawidth." x ".$mediaheight.")<br>";
         
			   if ($tmp == "#EFEFEF") { $tmp = "WHITE"; } else { $tmp = "#EFEFEF"; }
		   	echo "<option value=\"".$flashfile[$a].";".$mediawidth.";".$mediaheight."\" STYLE='background: ".$tmp.";'>".$flashfile[$a]."</option>\n";
		}
		
		?>

		</SELECT> &nbsp;
		Width: <input name="videow" id="videow" type="text" size="4" value="<? echo $mediaheight; ?>" STYLE='font-family: Arial; font-size: 8pt; '> &nbsp;
		Height: <input name="videoh" id="videoh" type="text" size="4" value="<? echo $mediawidth; ?>" STYLE='font-family: Arial; font-size: 8pt; '>
		
		</td><td align=center valign=middle>
		
		<input type=button class=mikebut onMouseOver="this.className='mikebutOn';" onMouseOut="this.className='mikebut';" value=" OK " onClick="SendVID();">
		&nbsp;&nbsp;<input type=button class=mikebut onMouseOver="this.className='mikebutOn';" onMouseOut="this.className='mikebut';" value=" Cancel " onClick="parent.replaceImageData();parent.closeUploadWin();">

		</td></tr></table>
<?				
}
if ($type == "cust"){
?>

   <div class="prop_head">Custom Code Selection</div>

	<table cellpadding="0" cellspacing="0" width="100%" class="prop_table">
		<tr>
			<td align="center" valign="middle">
	<FONT STYLE='font-size: 7pt; color: darkblue;'>The "Custom Code" object allows you to create your own HTML or PHP code; upload it via file upload and place it on any
	content page. All PHP code executes in real-time as an include when site visitors view this page. Accepted file formats are: <font color=maroon>.html .htm .php .inc </font></FONT><BR>
	
	Select Include File: &nbsp;
	
	<SELECT id="customname" NAME="customname" STYLE='font-family: Arial; font-size: 8pt; width: 250px;'>
		<option value="NONE" style='color: #999999;'>Custom Code Files:</option>

		<?php

		sort($customfile);
		$custommedia = count($customfile);
		
		for ($a=0;$a<=$custommedia;$a++) {
		
			if (strlen($customfile[$a]) >= 2) {
			
				if ($tmp == "#EFEFEF") { $tmp = "WHITE"; } else { $tmp = "#EFEFEF"; }
				echo ("<option value=\"$customfile[$a]\" STYLE='background: $tmp;'>$customfile[$a]</option>\n");
			}
			
		}
		
		?>

	</SELECT>
	
	</td><td align=center valign=middle>
	
	<input type=button class=mikebut onMouseOver="this.className='mikebutOn';" onMouseOut="this.className='mikebut';" value=" OK " onClick="SendCUST();">
	&nbsp;&nbsp;<input type=button class=mikebut onMouseOver="this.className='mikebutOn';" onMouseOut="this.className='mikebut';" value=" Cancel " onClick="parent.replaceImageData();parent.closeUploadWin();">
	</td>
	</tr>
	</table>
<?				
}

?>

</DIV>
<?


}


if ( $todo == "UPNOW" ) {
?>

   <div class="prop_head_upload">File Upload</div>

	<table cellpadding="0" cellspacing="0" width="90%" class="prop_table">
		<tr>
			<td align="center" valign="middle" width="75%" style="">
			   <div id="uping" style="display: none;"><font style="color: red; font-size: 10pt;">Uploading, please wait...</font></div>
            <b>Please select a File to upload.<br>
            This may take a few minutes depending on the size of the file.</b>
            <form enctype="multipart/form-data" name="upitnow" style="margin: 0px;" class="FormLt2" type="file" size="80" action="upFile.php" method="post">
               <!--<input type="hidden" name="MAX_FILE_SIZE" value="90000">-->
               <input type="hidden" name="todo" value="upload_now">
               <input type="hidden" name="type" value="<? echo $type; ?>">
               <input type="file" name="quick_upload" style="width: 300px; font: 11px arial;">
            </form>
         </td>
         <td valign="top" align="center" style=""><br/>
            <input type="button" value="<? echo lang("Upload File"); ?>" onClick="document.upitnow.submit();javascript:hideshow(document.getElementById('uping'));" <? echo $btn_save; ?>>
            &nbsp;&nbsp;<input type="button" value=" Cancel " onClick="parent.replaceImageData();parent.closeUploadWin();" <? echo $btn_edit; ?>>
         </td>
        </tr>
      </table>

<?
}
?>