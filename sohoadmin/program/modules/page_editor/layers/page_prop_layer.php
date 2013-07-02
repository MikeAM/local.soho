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
## Copyright 1999-2006 Soholaunch.com, Inc. and Mike Johnston
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
$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../../../includes/product_gui.php');
chdir($curdir);

$templateName = eregi_replace("_"," ",$currentPage);
//$templateName = chop($templateName);

if ($currentPage == "Home" || $CUR_USER_ACCESS != "WEBMASTER") {
	$disable = "DISABLED";
} else {
	$disable = "";
}

$isUp = 0;

if ( !$result = mysql_query("SELECT prikey, page_name, url_name, type, custom_menu, sub_pages, sub_page_of, password, main_menu, link, username, splash, bgcolor, title, description, template FROM site_pages WHERE page_name = '$templateName'") ){
   echo "Cannot select from site_pages<br>";
	echo "Mysql says: ".mysql_error();
	exit;
}
$DIS_ARRAY = mysql_fetch_array($result);
$selThisTemp = $DIS_ARRAY['template'];

foreach($DIS_ARRAY as $var=>$val){
   //echo "var = (".$var.") val = (".$val.")<br>";
   if($var == "template"){
      $isUp = 1;
   }
}

//if($isUp == 0){
//   mysql_query("alter table site_pages add column template varchar(100)");
//   //echo "template col added!";
//}

if(eregi("tCustom",$selThisTemp)){
   $selThisTemp = $doc_root."/".$selThisTemp;
}

$temp_opts = '';

$temp_opts .= '<select id="pTemplate" name="pTemplate" style="width: 250px; font-family: Arial; font-size: 8pt;">';
$USEDIR = $doc_root."/sohoadmin/program/modules/site_templates/";
	//$this_page = "Latest_News";
	$this_template = $selThisTemp;
ob_start();
	include("../site_templates/pgm-read_templates.php");
	$temp_opts .= ob_get_contents();
ob_end_clean();
$temp_opts .= '</select>';

//echo "this is the opts (".$temp_opts.")<br>";
//exit;

#######################################################
### BUILD COLOR OPTIONS FOR SPLASH BG
#######################################################

$filename = "data/color_table.dat";
$fp = fopen("$filename", "r");
	$colors = fread($fp, filesize("$filename"));
fclose($fp);

$color = split("\n", $colors);
$clr_cnt = count($color);
$sel = "";
$ccFlag = 1; // For detecting whether custom hex is used

$COPTIONS = "<select name=\"prop_splash_bg\" onchange=\"change_color(this.value,'prop_bgcolor');\">\n";

if ( $prop_bgcolor == "" ) { $sel = " selected"; }
$COPTIONS .= "<option value=\"\"".$sel.">Select color...</option>\n";

for ($x=0;$x<=$clr_cnt;$x++) {
   $sel = ""; // Determine which to select first

	$tmp = split(",", $color[$x]);
	if ($tmp[0] != "") {
		$tmp[1] = chop($tmp[1]);
		if ( $tmp[1] == $prop_bgcolor ) { $sel = " selected"; $ccFlag = 0;}
		$COPTIONS .= "<OPTION VALUE=\"$tmp[1]\"".$sel.">$tmp[0]</OPTION>\n";
	}

}

if ( $ccFlag == 1 ) { $sel = " selected"; } else { $sel = ""; }
$COPTIONS .= "<option value=\"custom\"".$sel.">Custom Color</option>\n";
$COPTIONS .= "</select>\n";


?>

<script language="javascript">


function delete_this_page() {
	var tiny = window.confirm('Are you sure you wish to delete this page?\n\nYou can not undo this event!');
	if (tiny != false) {
		MM_showHideLayers('DELETEPAGE','','show','pageproperties','','hide');
		window.location = "delete_page.php?currentPage=<? echo $currentPage; ?>&<?=SID?>";
	} else {
		// OK With client
	}
}

function change_color(value, what) {
	if ( value != "custom" ) {
	   var value = "#"+value;
	}
	eval("document.save."+what+".value = '"+value+"';");
	eval("document.save."+what+".style.color = '"+value+"';");
	eval("document.save."+what+".style.border = '1px solid "+value+"';");

	var demo = what+"_VIEW";
	eval(demo+".style.background = '"+value+"';");
}

</SCRIPT>
<TABLE CELLPADDING="0" CELLSPACING="0" BORDER="0" WIDTH="550" ALIGN="center" BGCOLOR="#6699CC">

  <TR>
    <TD VALIGN="top">
      <TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
        <TR>
          <TD NOWRAP BGCOLOR="#336699" VALIGN=TOP WIDTH=100% align="left"><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#FFFFFF"><B><IMG SRC="arrow.gif" WIDTH="17" HEIGHT="13" ALIGN="left">Page
            Properties </B></FONT><FONT COLOR=WHITE>&nbsp;</FONT></TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
  <TR>
    <TD VALIGN="top" ALIGN="center">
      <TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="0" HEIGHT="100%">
        <TR>
          <TD ALIGN="LEFT" BGCOLOR="#EFEFEF" class=catselect>
            <TABLE WIDTH="545" BORDER="0" CELLSPACING="0" CELLPADDING="4">
              <TR>
                <TD WIDTH="200" ALIGN="LEFT" VALIGN="TOP" class="catselect">
                  Page Name:<BR>
                  <? if ($disable == "DISABLED") { echo ("<input type=hidden name=PROP_name value=\"$PROP_name\">\n"); } ?>
                  <INPUT TYPE="text" <? echo $disable; ?> name="PROP_name" size="22" value="<? echo $PROP_name; ?>">
                  <? echo ("<input type=hidden name=PROP_KEYNAME value=\"$PROP_name\">\n"); ?><br/>
                  <span style="font-size: 90%;color: #888c8e;">Note: Please use only alpha-numeric characters and spaces in your page name.</span>
                  </TD>



                <TD  WIDTH="350" ALIGN="LEFT" VALIGN="TOP" class="catselect">
                 <table border="0" cellpadding="2" cellspacing="0" width="290">
                  <tr>
                   <td colspan="2" align="left" class="catselect">
                     Splash Page:                   </td>
                   <td align="left" valign="top" class="catselect" colspan="2" style="padding-top: 0px;">
                     <? if ($PROP_splash == "y" || $PROP_splash == "i") { $CHECKED="CHECKED"; } else { $CHECKED=""; } ?>
                     <INPUT TYPE="checkbox" NAME="PROP_splash" VALUE="y" <? echo $CHECKED; ?>>
                     (Only content displayed; no template.)                   </td>
                  </tr>
                  <tr>
                   <td align="left" class="catselect">

                   <?
                   	if($PROP_splash == "y" || $PROP_splash == ""){
                   		$bg_sel = "checked";
                  	}elseif($PROP_splash == "i"){
                  		$image_sel = "checked";
                  	}
                   ?>

                     <input name="PROP_splash_type" type="radio" value="y" <? echo $bg_sel; ?> /></td>
                   <td align="left" class="catselect"><? echo lang("Background"); ?>:</td>
                   <td align="left" class="catselect" style="padding-left: 5px;">
                     <? echo $COPTIONS; ?>                   </td>
                   <td align="left" class="catselect">
                   <?
                   	if($PROP_splash == "i"){
                   		$page_bg = "";

                  	}else{
                  		$page_bg = $prop_bgcolor;
                  	}
                   ?>
                    <input type="text" name="prop_bgcolor" value="<? echo "#".$page_bg; ?>" onfocus="change_color('custom','prop_splash_bg')" style="width: 60px;">                   </td>
                  </tr>
                  <tr>
                    <td align="left" class="catselect">
                    	<?php 
                    	sort($imageFile);
//                    	ksort($imageFile);
//                    	natcasesort($imageFile);
//                    	echo testArray($imageFile); 
                    	?>
                    	<input name="PROP_splash_type" type="radio" value="i" <? echo $image_sel; ?> /></td>
                    <td align="left" class="catselect">Background Image:</span></td>
                    <td colspan="2" align="left" class="catselect" style="padding-left: 5px;">
                      <select name="PROP_image_type" style='font-family: Arial; font-size: 8pt; width: 250px;'>
                        <option value="NONE" style='color: #999999;'>Current Images:</option>
                        <?

								for ($x=0;$x<=$numImages;$x++) {

									if ($tmp == "#EFEFEF") { $tmp = "WHITE"; } else { $tmp = "#EFEFEF"; }

									$thisImage = split("~~~", $imageFile[$x]);
									//echo "(".$thisImage.")<br/>";

									if (file_exists("$doc_root/images/$thisImage[1]")) {
										$tempArray = getImageSize("$doc_root/images/$thisImage[1]");
//										$origW = imagesx("$doc_root/images/$thisImage[1]");
//										$origH = imagesy("$doc_root/images/$thisImage[1]");
										$origW = $tempArray[0];
										$origH = $tempArray[1];
										$WH = "width=$origW height=$origH ";
										if ($origW > 199) {
											$WH = "width=199 ";
										}
									}

									if (stripslashes($prop_bgcolor) == $thisImage[1]." ".$WH){ $sel = "selected"; }else{ $sel = ""; }

									echo "<OPTION VALUE=\"".$thisImage[1]." ".$WH."\" STYLE='background: ".$tmp.";' ".$sel.">$thisImage[0]</OPTION>\n";
								}
								?>
                      </select>                    </td>
                    </tr>
                </table>                </TD>
              </TR>




              <TR>
                <TD class=catselect>
                  Security Code:<BR>
                  <SELECT class=dropdownbox NAME="PROP_sec_code">
                    <OPTION VALUE="">Public</OPTION>
                    <? 	for ($QW=1;$QW<=$PROP_NUMCODES;$QW++) {
						if ($PROP_CODES[$QW] == $PROP_sec_code) { $SELECTED="SELECTED"; } else { $SELECTED=""; }
						echo ("<OPTION VALUE=\"$PROP_CODES[$QW]\" $SELECTED>$PROP_CODES[$QW]</OPTION>\n");
					}
				?>
                  </SELECT>                </TD>
<?
//                <TD class=catselect>
//                  <font color=#999999>Page Type: (Advanced Users)</font><BR>
//                   if ($disable == "DISABLED") { echo ("<input type=hidden name=PROP_pagetype value=\"$PROP_pagetype\">\n"); }
//                  <SELECT  echo $disable;  class=dropdownbox NAME="PROP_pagetype" style="color: #969696;">
//
//					if ($PROP_pagetype == "Main" || $PROP_pagetype == "main") { $this_display = "Menu Page"; $ff = 1; }
//
//
//
//						if ($PROP_pagetype == "cart") { $this_display = "Shopping Cart Attachment"; $ff = 2; }
//						if ($PROP_pagetype == "calendar") { $this_display = "Calendar Attachment"; $ff = 3; }
//						if ($PROP_pagetype == "newsletter") { $this_display = "Newsletter"; $ff = 4; }
//						// if ($PROP_pagetype == "database") { $this_display = "Database"; $ff = 5; }
//
//					echo ("<OPTION VALUE=\"$PROP_pagetype\" SELECTED>$this_display</OPTION>\n");
//
//					if ($ff != 1) { echo ("<OPTION VALUE=\"main\">Menu Page</OPTION>\n"); }
//
//
//					// ------------------------------------------------------------
//					// If lite version installed, do not offer attachement page
//					// options... This would be the equivilant of deleteing a page
//					// ------------------------------------------------------------
//
//					if (!eregi("Lite", $version)) {
//
//						if ($ff != 2) { echo ("<OPTION VALUE=\"cart\">Shopping Cart Attachment</OPTION>\n"); }
//						if ($ff != 3) { echo ("<OPTION VALUE=\"calendar\">Calendar Attachment</OPTION>\n"); }
//						if ($ff != 4) { echo ("<OPTION VALUE=\"newsletter\">Newsletter Content</OPTION>\n"); }
//						// if ($ff != 5) { echo ("<OPTION VALUE=\"database\">Database Attachment</OPTION>\n"); }
//
//					}
//
//
//                  </SELECT>
//                </TD>
?>
                <TD colspan="2" align="left" valign="MIDDLE" class="catselect">
                  <?
                  if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_TEMPLATES;", $CUR_USER_ACCESS)) {
                     echo "Page Template:<BR>\n";
                     echo $temp_opts;
                  }
                  ?>
               </TD>
              </TR>
              <TR>
                <TD COLSPAN="2" ALIGN=left VALIGN=MIDDLE CLASS=catselect>
                  Page Title:<font color=#999999> (For search engines)</font><BR>
                  <input type=\"text\" name="prop_title" value="<? echo $prop_title; ?>" style="width: 376px;">                </TD>
              </TR>
              <TR>
                <TD COLSPAN="2" ALIGN=left VALIGN=MIDDLE CLASS=catselect>
                  Description:<font color=#999999> (For search engines)</font><BR>
                  <textarea name="prop_desc" style="width: 475px; height: 40px;" WRAP="VIRTUAL"><? echo $prop_desc; ?></textarea>                </TD>
              </TR>
              <TR>
                <TD COLSPAN="2" ALIGN=left VALIGN=MIDDLE CLASS=catselect>
                  Keywords:<font color=#999999> (Separated by commas)</font><BR>
                  <textarea name="KEYwords" style="width: 475px; height: 40px;" WRAP="VIRTUAL"><? echo $KEYwords; ?></textarea>                </TD>
              </TR>
              <TR ALIGN="LEFT">
                <TD COLSPAN="2" VALIGN=MIDDLE CLASS=catselect>
                  Gateway HTML:<font color=#999999> (For search engines)</font><BR>
                  <TEXTAREA NAME="GATEway" style="width: 475px; height: 40px;" WRAP="VIRTUAL"><? echo $GATEway; ?></TEXTAREA>                </TD>
              </TR>
              <TR>
                <TD align="center" valign="middle">
                  <button type="button" class="redButton" <? echo $disable; ?> onclick="delete_this_page();"><span><span>Delete Page</span></span></button>                </TD>
                <TD ALIGN="CENTER" VALIGN="MIDDLE">
                  <button class="greenButton" TYPE="button" onclick="MM_showHideLayers('pageproperties','','hide'); document.save.style.display = 'none';"><span><span>Ok</span></span></button>                </TD>
              </TR>
            </TABLE>
          </TD>
        </TR>
      </TABLE>
    </TD>
</TABLE>