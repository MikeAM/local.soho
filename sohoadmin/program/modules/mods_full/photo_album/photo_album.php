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
require_once("../../../includes/product_gui.php");

#######################################################
### PROCESS "DELETE ALBUM" ACTION				    ###	
#######################################################

if ($ACTION == "DEL") {
	mysql_query("DELETE FROM photo_album WHERE prikey = '$id'");
}

#######################################################
### PROCESS "ADD NEW ALBUM" ACTION				    ###	
#######################################################

if ($ACTION == "NG") {

		// Check for duplicates and don't allow

		$ef = 0;
		//$NEWGROUP = ucwords($NEWGROUP);		
		$NEWGROUP = stripslashes($NEWGROUP);
		$NEWGROUP = addslashes($NEWGROUP);

		$result = mysql_query("SELECT * FROM photo_album");
		$num_groups = mysql_num_rows($result); 

		if ($num_groups > 0) {
			while($GROUP = mysql_fetch_array($result)) {
				if ($GROUP[album_name] == $NEWGROUP) { $ef = 1; }
			}
		}

		if ($NEWGROUP != "" && $ef != 1) {			
			mysql_query("INSERT INTO photo_album (album_name) VALUES('".$NEWGROUP."')");
			echo mysql_error(); 
		}

}


#######################################################
### IF THE 'photo_album' TABLE DOES NOT EXIST; 
### CREATE NOW 
#######################################################

if(!table_exists("photo_album")){
	mysql_query("CREATE TABLE photo_album (
		prikey INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
		album_name CHAR(255))");
} // End if Match != 1


if(!table_exists("photo_album_images")){
	mysql_query("CREATE TABLE photo_album_images (
		prikey INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
		album_id int(64), image_order int(64), image_name varchar(255), caption BLOB)");
} // End if Match != 1


#######################################################
### START HTML/JAVASCRIPT CODE					    ###	
#######################################################

# Start buffering output
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

function SV2_popupMsg(msg) { //v1.0
  alert(msg);
}
function SV2_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function delete_album() {

	// What album is selected?
	var sel = document.getElementById('albid').value;
	

	if (sel != "") {
	
		var tiny = window.confirm('Are you sure you wish to delete this Album?');
		if (tiny != false) { 
			window.location = 'photo_album.php?id='+sel+'&ACTION=DEL&<?=SID?>';
		}
	
	} // End If ""

}




//-->
</script>



<style>

form {
   margin:0;
}

.feature_contain {
	padding: 0;
	margin: 0;
	border-left: 1px solid #A2ADBC;
	border-bottom: 1px solid #A2ADBC;
	font: 12px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	color: #33393F;
	text-align: center;
	background-color: #fff;
}

.feature_contain th {
	font: bold 12px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	background: #D9E2E1;
	border-right: 1px solid #A2ADBC;
	border-bottom: 1px solid #A2ADBC;
	border-top: 1px solid #A2ADBC;
	padding:2;
}

.feature_contain td {
   padding-bottom:7px;
}

.cal_btn {
   margin:0;
   text-align: center;
   border: 2px outset #CFCFCF;
   cursor: pointer;
   background: #A7DFAF;
}

.cal_btn_over {
   text-align: center;
   border: 2px outset #AFFFBA;
   cursor: pointer;
   background: #6FDF7E;
}

.cal_del_btn {
   margin:0;
   text-align: center;
   border: 2px outset #CFCFCF;
   cursor: pointer;
   background: #FF0000;
   color: #FFFFFF;
}

.cal_del_btn_over {
   text-align: center;
   border: 2px outset #CCCCCC;
   cursor: pointer;
   background: #FF4F4F;
   color: #FFFFFF;
}


</style>

<?
$THIS_DISPLAY = "";

	$THIS_DISPLAY .= "<form name=\"openalbum\" method=\"POST\" action=\"photo_album.php\">\n";
	$THIS_DISPLAY .= "<input type=\"hidden\" name=\"ACTION\" value=\"NG\">\n";

	$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"8\" cellspacing=\"0\" width=\"750\" align=\"center\" class=\"feature_contain\">\n";
   $THIS_DISPLAY .= "<tr><th align=\"left\" valign=\"top\">".lang("Create New Album")."</th></tr>\n";

	$THIS_DISPLAY .= "<tr><td align=\"left\" valign=\"top\" style=\"border-right: 1px solid #A2ADBC;\">\n";

		$THIS_DISPLAY .= "<b>".lang("Enter Album Name")."</b>:<br/><INPUT TYPE=TEXT NAME=NEWGROUP CLASS=text STYLE='width: 400px;'>&nbsp;\n";
		$THIS_DISPLAY .= "<button type=\"button\" onClick=\"document.openalbum.submit();\" class=\"greenButton\"><span><span>".lang("Create Album")."</span></span></button></FORM>\n";
		$THIS_DISPLAY .= "</td></tr>\n";


		// Pull any data from "sec_codes" table for display use

		$result = mysql_query("SELECT * FROM photo_album ORDER BY album_name");
		$num_groups = mysql_num_rows($result); 

		if ($num_groups > 0) {
		   $THIS_DISPLAY .= "<tr><td align=\"left\" valign=\"top\" style=\"border-right: 1px solid #A2ADBC;\">\n";

			$THIS_DISPLAY .= "<DIV ALIGN=LEFT><FORM NAME=DELFORM METHOD=POST ACTION=\"edit_album.php\">\n";

			$THIS_DISPLAY .= "<B>".lang("Current Photo Albums")."</B>:<br/>\n\n";
			$THIS_DISPLAY .= "<SELECT id=\"albid\" NAME=id CLASS=text STYLE='width: 325px;'>\n";
			$THIS_DISPLAY .= "     <OPTION VALUE=\"\">".lang("Select Album")."...</OPTION>\n";

			while($MIKE = mysql_fetch_array($result)) {
				$THIS_DISPLAY .= "     <OPTION VALUE=\"$MIKE[prikey]\">$MIKE[album_name]</OPTION>\n";
			}

			$THIS_DISPLAY .= "\n</SELECT>&nbsp;\n";
			//$THIS_DISPLAY .= "<INPUT TYPE=SUBMIT VALUE=\" ".lang("Edit")." \" style='width: 75px;' CLASS=\"btn_edit\" onMouseover=\"this.className='btn_editon';\" onMouseout=\"this.className='btn_edit';\">\n";
			$THIS_DISPLAY .= "<button TYPE=button CLASS=\"blueButton\" onClick=\"document.DELFORM.submit();\"><span><span>".lang("Edit")."</span></span></button>\n";
			$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;\n";
			//$THIS_DISPLAY .= "<INPUT TYPE=BUTTON VALUE=\" ".lang("Delete")." \"  style='width: 75px;'  ONCLICK=\"delete_album();\" CLASS=\"btn_delete\" onMouseover=\"this.className='btn_deleteon';\" onMouseout=\"this.className='btn_delete';\">\n";
			$THIS_DISPLAY .= "<button TYPE=button CLASS=\"redButton\" onClick=\"delete_album();\"><span><span>".lang("Delete")."</span></span></button>\n";
			
			$THIS_DISPLAY .= "</FORM></DIV>\n";

         $THIS_DISPLAY .= "</td></tr>\n";

		}

	$THIS_DISPLAY .= "</TABLE><BR><BR>\n";



$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n";


echo $THIS_DISPLAY;

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Create and manage your site photo albums here.  After you create the album, select it from the current photo albums list and click edit.");
//$instructions .= lang("Please only use alpha-numerical characters and spaces.");

# Build into standard module template
$module = new smt_module($module_html);
$module->meta_title = lang("Photo Album");
$module->add_breadcrumb_link(lang("Photo Album"), "program/modules/mods_full/photo_album/photo_album.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/full_size/photo_albums-enabled.gif";
$module->heading_text = lang("Photo Album");
$module->description_text = $instructions;
$module->good_to_go();
?>