<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.7
##
## Author: 			Joe Lain
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugz.soholaunch.com
###############################################################################

##############################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2005 Soholaunch.com, Inc.  All Rights Reserved.
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


#######################################################
### Connect to database                             ###
#######################################################
if(!include("../../../includes/config.php")){echo "Cannot include config!";}
if(!include("../../../includes/db_connect.php")){echo "Cannot include db connect!";}
include("../../includes/product_gui.php");
#######################################################
### Get passed vars                                 ###
#######################################################
$box = $_GET['box'];
$cont = $_GET['cont'];
$disp = $_GET['disp'];

#######################################################
### Get all information about this box              ###
#######################################################
if ( !$result = mysql_query("SELECT * FROM promo_boxes WHERE BOX = '$box'") ){
   echo "Cannot select from UDT_PROMO_BOX table<br>";
	echo "Mysql says: ".mysql_error();
	exit;
}
$BOX_SETTINGS = mysql_fetch_array($result);

$settings['CONTENT'] = unserialize($BOX_SETTINGS['CONTENT']);
$settings['NUM_DISPLAY'] = unserialize($BOX_SETTINGS['NUM_DISPLAY']);
$settings['DISP_TITLE'] = unserialize($BOX_SETTINGS['DISP_TITLE']);
$settings['DISP_CONTENT'] = unserialize($BOX_SETTINGS['DISP_CONTENT']);
$settings['DISP_DATE'] = unserialize($BOX_SETTINGS['DISP_DATE']);
$settings['DISP_MORE'] = unserialize($BOX_SETTINGS['DISP_MORE']);
$settings['SETTINGS'] = unserialize($BOX_SETTINGS['SETTINGS']);
$settings['FUTURE1'] = unserialize($BOX_SETTINGS['FUTURE1']);



#######################################################
### Enable/Disable options                          ###
#######################################################

// Display Type/Number entries
$num_display= $settings['NUM_DISPLAY']['blog'];
$num_limit= $settings['NUM_DISPLAY']['chars'];
$num_displayOff = "#FFFFFF";

# Box display type?
if ( $settings['CONTENT']['type'] == "muli" ) {
   # Multiple entries
   $latestSel = "";
   $multiSel = "selected";
   $randomSel = "";
   $numDisp = "";

} elseif ( $settings['CONTENT']['type'] == "random" ) {
   # One entry selected at random
   $latestSel = "";
   $multiSel = "";
   $randomSel = "selected";
   $numDisp = "disabled";
   $num_displayOff = "#CCCCCC";

} else {
   # Latest entry only (default)
   $latestSel = "selected";
   $multiSel = "";
   $randomSel = "";
   $numDisp = "disabled";
   $num_displayOff = "#CCCCCC";
}

// Character Limit
$num_limitOff = "#FFFFFF";
if($settings['NUM_DISPLAY']['chars'] == ""){
   $numChar = "disabled";
   $chkChar = "";
   $num_limitOff = "#CCCCCC";
}else{
   $numChar = "";
   $chkChar = "checked";
}


#######################################################
### Title display settings                          ###
#######################################################


// Display Title
$chkTitle = "checked";
$headDisp = "block";
if($settings['DISP_TITLE']['display'] == ""){
   $chkTitle = "";
   $headDisp = "none";
}
// Title Border
$chkTitleBorder = "checked";
$titleBorder = "1px solid #666666";

if($settings['DISP_TITLE']['border'] == ""){
   $TborderStyle = "";
   $chkTitleBorder = "";
   $titleBorder = "";
}
// Title Align
$chkTitleAlignLeft = "";
$chkTitleAlignCenter = "selected";
$chkTitleAlignRight = "";
$titleAlign = "center";
if($settings['DISP_TITLE']['align'] == "left"){
   $chkTitleAlignLeft = "selected";
   $chkTitleAlignCenter = "";
   $chkTitleAlignRight = "";
   $titleAlign = "left";
}elseif($settings['DISP_TITLE']['align'] == "right"){
   $chkTitleAlignLeft = "";
   $chkTitleAlignCenter = "";
   $chkTitleAlignRight = "selected";
   $titleAlign = "right";
}
// Title Weight
$chkTitleWeightNormal = "";
$chkTitleWeightBold = "selected";
$chkTitleWeight = "bold";
if($settings['DISP_TITLE']['weight'] == "normal"){
   $chkTitleWeightNormal = "selected";
   $chkTitleWeightBold = "";
   $chkTitleWeight = "normal";
}

$box_title = $settings['FUTURE1']['title'];


#######################################################
### Content display settings                        ###
#######################################################


// Display Content
$chkCont = "checked";
$contDisp = "block";
if($settings['DISP_CONTENT']['display'] == ""){
   $chkCont = "";
   $contDisp = "none";
}
// Content Border
$chkContBorder = "checked";
$contDispBorder = "1px solid #666666";
if($settings['DISP_CONTENT']['border'] == ""){
   $CborderStyle = "";
   $chkContBorder = "";
   $contDispBorder = "";
}


#######################################################
### Read More display settings                      ###
#######################################################


// Display More
$chkMore = "checked";
$MoreDisp = "block";
if($settings['DISP_MORE']['display'] == ""){
   $chkMore = "";
   $MoreDisp = "none";
}
// More Border
$chkMoreBorder = "checked";
$MoreBorder = "1px solid #666666";
if($settings['DISP_MORE']['border'] == ""){
   $MborderStyle = "";
   $chkMoreBorder = "";
   $MoreBorder = "";
}
// More Align
$chkMoreAlignLeft = "";
$chkMoreAlignCenter = "selected";
$chkMoreAlignRight = "";
$MoreAlign = "center";
if($settings['DISP_MORE']['align'] == "left"){
   $chkMoreAlignLeft = "selected";
   $chkMoreAlignCenter = "";
   $chkMoreAlignRight = "";
   $MoreAlign = "left";
}elseif($settings['DISP_MORE']['align'] == "right"){
   $chkMoreAlignLeft = "";
   $chkMoreAlignCenter = "";
   $chkMoreAlignRight = "selected";
   $MoreAlign = "right";
}
// More Weight
$chkMoreWeightNormal = "";
$chkMoreWeightBold = "selected";
$chkMoreWeight = "bold";
if($settings['DISP_MORE']['weight'] == "normal"){
   $chkMoreWeightNormal = "selected";
   $chkMoreWeightBold = "";
   $chkMoreWeight = "normal";
}
// More text
$moreText = $settings['DISP_MORE']['text'];
//echo "(".$MborderStyle.")";


#######################################################
### Date display settings                           ###
#######################################################


// Display Date
$chkDate = "checked";
if($settings['DISP_DATE']['display'] == ""){
  $chkDate = "";
}
// Display Date type
$DateDispBott = "none";
$DateDispTop = "none";
$DateFirst = "none";
$DateLast = "none";
$alignBordDisp = "none";

if($settings['DISP_DATE']['position'] == "dateFirst"){
	$chkFirst = "selected";
}else{
	$chkFirst = "";
}
if($settings['DISP_DATE']['position'] == "dateLast"){
	$chkLast = "selected";
}else{
	$chkLast = "";
}
if($settings['DISP_DATE']['position'] == "dateTop"){
	$chkBoxTop = "selected";
	$alignBordDisp = "block";
}else{
	$chkBoxTop = "";
}
if($settings['DISP_DATE']['position'] == "dateBottom"){
	$chkBoxBott = "selected";
	$alignBordDisp = "block";
}else{
	$chkBoxBott = "";
}


if($settings['DISP_DATE']['display'] == "on"){
	if($settings['DISP_DATE']['position'] == "dateFirst"){
		$DateDispBott = "none";
		$DateFirst = "inline";
	}elseif($settings['DISP_DATE']['position'] == "dateLast"){
		$DateDispBott = "none";
		$DateLast = "inline";
	}elseif($settings['DISP_DATE']['position'] == "dateTop"){
		$DateDispBott = "none";
		$DateDispTop = "block";
	}elseif($settings['DISP_DATE']['position'] == "dateBottom"){
		$DateDispBott = "block";
		$DateDispTop = "none";
	}

}
// Date Italic
$chkDateItalic = "";
$dateItalic = "";
$dateItalicFirst = "";
$dateItalicLast = "";
if($settings['DISP_DATE']['fontStyle'] == "on"){
	$dateItalic = "italic";
	$dateItalicFirst = "italic";
	$dateItalicLast = "italic";
	$chkDateItalic = "checked";
}
// Date Format
$dateFull = "";
$dateHalf = "";
$dateAll = "selected";
$dateFull2 = "";
$dateHalf2 = "";
$dateAll2 = "";
$dateDispFirst = "2006-1-1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$dateDispLast = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2006-1-1";
$dateDispBott = "2006-1-1";
if($settings['DISP_DATE']['format'] == "full"){
	$dateFull = "selected";
	$dateHalf = "";
	$dateAll = "";
	$dateFull2 = "";
	$dateHalf2 = "";
	$dateAll2 = "";
	$dateDispFirst = "January 1 2006&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	$dateDispLast = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;January 1 2006";
	$dateDispBott = "January 1 2006";
}elseif($settings['DISP_DATE']['format'] == "full2"){
	$dateFull = "";
	$dateHalf = "";
	$dateAll = "";
	$dateFull2 = "selected";
	$dateHalf2 = "";
	$dateAll2 = "";
	$dateDispFirst = "1 January 2006&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	$dateDispLast = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1 January 2006";
	$dateDispBott = "1 January 2006";
}elseif($settings['DISP_DATE']['format'] == "half"){
	$dateFull = "";
	$dateHalf = "selected";
	$dateAll = "";
	$dateFull2 = "";
	$dateHalf2 = "";
	$dateAll2 = "";
	$dateDispFirst = "Jan 1 2006&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	$dateDispLast = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jan 1 2006";
	$dateDispBott = "Jan 1 2006";
}elseif($settings['DISP_DATE']['format'] == "half2"){
	$dateFull = "";
	$dateHalf = "";
	$dateAll = "";
	$dateFull2 = "";
	$dateHalf2 = "selected";
	$dateAll2 = "";
	$dateDispFirst = "1 Jan 2006&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	$dateDispLast = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1 Jan 2006";
	$dateDispBott = "1 Jan 2006";
}elseif($settings['DISP_DATE']['format'] == "allNum"){
	$dateFull = "";
	$dateHalf = "";
	$dateAll = "selected";
	$dateFull2 = "";
	$dateHalf2 = "";
	$dateAll2 = "";
}elseif($settings['DISP_DATE']['format'] == "allNum2"){
	$dateFull = "";
	$dateHalf = "";
	$dateAll = "";
	$dateFull2 = "";
	$dateHalf2 = "";
	$dateAll2 = "selected";
	$dateDispFirst = "1-1-2006&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	$dateDispLast = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1-1-2006";
	$dateDispBott = "1-1-2006";
}

// Date Border
$chkDateBorder = "checked";
$DateBorder = "1px solid #666666";
if($settings['DISP_DATE']['border'] == ""){
   $DborderStyle = "";
   $chkDateBorder = "";
   $DateBorder = "";
}
// Date Align
$chkDateAlignLeft = "";
$chkDateAlignCenter = "selected";
$chkDateAlignRight = "";
$DateAlign = "center";
if($settings['DISP_DATE']['align'] == "left"){
   $chkDateAlignLeft = "selected";
   $chkDateAlignCenter = "";
   $chkDateAlignRight = "";
   $DateAlign = "left";
}elseif($settings['DISP_DATE']['align'] == "right"){
   $chkDateAlignLeft = "";
   $chkDateAlignCenter = "";
   $chkDateAlignRight = "selected";
   $DateAlign = "right";
}
// Date Weight
$chkDateWeightNormal = "";
$chkDateWeightBold = "selected";
$chkDateWeight = "bold";
if($settings['DISP_DATE']['weight'] == "normal"){
   $chkDateWeightNormal = "selected";
   $chkDateWeightBold = "";
   $chkDateWeight = "normal";
}

# Format box type for display
if( $BOX_SETTINGS['FILE'] != "" ) {
   if ( eregi("index", $BOX_SETTINGS['FILE']) ) {
      $template = "Default Layout";
      $boxnum = eregi_replace("index", "", $BOX_SETTINGS['FILE']);
   } elseif ( eregi("home", $BOX_SETTINGS['FILE']) ) {
      $template = "Home Page Layout";
      $boxnum = eregi_replace("home", "", $BOX_SETTINGS['FILE']);
   } elseif ( eregi("cart", $BOX_SETTINGS['FILE']) ) {
      $template = "Shopping Cart Layout";
      $boxnum = eregi_replace("cart", "", $BOX_SETTINGS['FILE']);
   } elseif ( eregi("news", $BOX_SETTINGS['FILE']) ) {
      $template = "News Article Layout";
      $boxnum = eregi_replace("news", "", $BOX_SETTINGS['FILE']);
   }
}

// Template style
$chkTmpltStyle = "";
if($settings['SETTINGS']['template'] == "on"){
	$chkTmpltStyle = "checked";
}

?>
<form name="setPrefs" method="post" action="promo_boxes.php">
<input type="hidden" name="do" value="saveBox">
<input type="hidden" name="box" value="<? echo $box; ?>">
<input type="hidden" name="CONTENT[content]" value="<? echo $cont; ?>">
<input type="hidden" name="CONTENT[display]" value="<? echo $disp; ?>">

<!---<div id="styles" style="position: absolute; left: 287px; top: 385px;"><img id="tab5" src="images/settings_tabs-style.gif" width="118" height="28" onClick="swapImg('tab5');" class="hand"></div>--->
<?
if(eregi('Gecko', $_SERVER['HTTP_USER_AGENT'])){
   $top = "280px";
}else{
   $top = "290px";
}
?>
<div id="moreSettings" style="background-color: #FFFFFF; <? echo $disOpac; ?> position: absolute; left: 45px; top: <? echo $top; ?>; height: 170px; width: 360px;">
   <table border="0" cellspacing="0" cellpadding="0">
     <tr>
        <td height="30" valign="bottom"><img id="tab1" src="images/settings_tabs_front.gif" width="104" height="25" onClick="swapImg('tab1');" class="hand"></td>
        <td height="30" valign="bottom"><img id="tab2" src="images/settings_tabs_middle-cont.gif" width="80" height="25" onClick="swapImg('tab2');" class="hand"></td>
        <td height="30" valign="bottom"><img id="tab3" src="images/settings_tabs_middle-read.gif" width="80" height="25" onClick="swapImg('tab3');" class="hand"></td>
        <td height="30" valign="bottom"><img id="tab4" src="images/settings_tabs_back.gif" width="96" height="25" onClick="swapImg('tab4');" class="hand"></td>
     </tr>
   </table>

                                             <!---Title Options Div--->

        <div id="tabTitle" height="30" valign="bottom" style="display: block;">
        <table width="100%" height="140" border="0" cellspacing="0" cellpadding="5" background="images/settings_tabs-BG.gif" style="background-color: #68AAE5;">
           <tr>
             <td align="center" width="50%"><font style="font-family: Arial; font-size: 9pt;"><? echo lang("Display title"); ?>:</font> </td>
             <td>
               <input <? echo $chkTitle; ?> onClick="createTable('DISP_TITLE', 'head');" id="DISP_TITLE" type="checkbox" name="DISP_TITLE[display]">
             </td>
           </tr>
           <tr>
             <td align="center"><font style="font-family: Arial; font-size: 9pt;"><? echo lang("Border"); ?>:</font> </td>
             <td>
               <input <? echo $chkTitleBorder; ?> onClick="BorderPreview('head', '1px solid rgb(102, 102, 102)');" id="titleBorder" type="checkbox" name="DISP_TITLE[border]">
             </td>
           </tr>
           <tr>
             <td align="center"><font style="font-family: Arial; font-size: 9pt;"><? echo lang("Align"); ?>:</font> </td>
             <td>
               <select id="titleAlign" name="DISP_TITLE[align]" onChange="AlignPreview('head', this.value);" class="thinBox">
                 <option value="left" <? echo $chkTitleAlignLeft; ?>><? echo lang("Left"); ?></option>
                 <option value="center" <? echo $chkTitleAlignCenter; ?>><? echo lang("Center"); ?></option>
                 <option value="right" <? echo $chkTitleAlignRight; ?>><? echo lang("Right"); ?></option>
               </select>
             </td>
           </tr>
           <tr>
             <td align="center"><font style="font-family: Arial; font-size: 9pt;"><? echo lang("Font Weight"); ?>:</font> </td>
             <td>
               <select id="titleWeight" name="DISP_TITLE[weight]" onChange="FontPreview('head', this.value);" class="thinBox">
                 <option value="normal" <? echo $chkTitleWeightNormal; ?>><? echo lang("Normal"); ?></option>
                 <option value="bold" <? echo $chkTitleWeightBold; ?>><? echo lang("Bold"); ?></option>
               </select>
             </td>
           </tr>
        </table>
        </div>

                                             <!---Content Options Div--->

        <div id="tabCont" height="30" valign="bottom" style="display: none;">
        <table width="100%" height="140" border="0" cellspacing="0" cellpadding="0" background="images/settings_tabs-BG.gif" style="background-color: #68AAE5;">
           <tr>
             <td align="center" width="50%"><font style="font-family: Arial; font-size: 9pt;"><? echo lang("Display content"); ?>:</font> </td>
             <td>
               <input <? echo $chkCont; ?> onClick="createTable('DISP_CONT', 'cont');" id="DISP_CONT" type="checkbox" name="DISP_CONTENT[display]">
             </td>
           </tr>
           <tr>
             <td align="center"><font style="font-family: Arial; font-size: 9pt;"><? echo lang("Border"); ?>:</font> </td>
             <td>
               <input <? echo $chkContBorder; ?> onClick="BorderPreview('cont', '1px solid rgb(102, 102, 102)');" id="contBorder" type="checkbox" name="DISP_CONTENT[border]">
             </td>
           </tr>
        </table>
        </div>

                                             <!---Read More Options Div--->

        <div id="tabRead" height="30" valign="bottom" style="display: none;">
        <table width="100%" height="140" border="0" cellspacing="0" cellpadding="0" background="images/settings_tabs-BG.gif" style="background-color: #68AAE5;">
           <tr>
             <td align="center" width="50%"><font style="font-family: Arial; font-size: 9pt;"><? echo lang("Display 'Read More' link"); ?>:</font> </td>
             <td>
               <input <? echo $chkMore; ?> onClick="createTable('DISP_MORE', 'more');" id="DISP_MORE" type="checkbox" name="DISP_MORE[display]">
             </td>
           </tr>
           <tr>
             <td align="center"><font style="font-family: Arial; font-size: 9pt;"><? echo lang("Border"); ?>:</font> </td>
             <td>
               <input <? echo $chkMoreBorder; ?> onClick="BorderPreview('more', '1px solid rgb(102, 102, 102)');" id="moreBorder" type="checkbox" name="DISP_MORE[border]">
             </td>
           </tr>
           <tr>
             <td align="center"><font style="font-family: Arial; font-size: 9pt;"><? echo lang("Change Text"); ?>:</font> </td>
             <td>
               <input id="moreEdit" type="text" onKeyUp="showText(this.value);" name="DISP_MORE[text]" class="thinBox" value="<? echo $moreText; ?>">
             </td>
           </tr>
           <tr>
             <td align="center"><font style="font-family: Arial; font-size: 9pt;"><? echo lang("Align"); ?>:</font> </td>
             <td>
               <select id="titleAlign" name="DISP_MORE[align]" onChange="AlignPreview('more', this.value);" class="thinBox">
                 <option value="left" <? echo $chkMoreAlignLeft; ?>><? echo lang("Left"); ?></option>
                 <option value="center" <? echo $chkMoreAlignCenter; ?>><? echo lang("Center"); ?></option>
                 <option value="right" <? echo $chkMoreAlignRight; ?>><? echo lang("Right"); ?></option>
               </select>
             </td>
           </tr>
           <tr>
             <td align="center"><font style="font-family: Arial; font-size: 9pt;"><? echo lang("Font Weight"); ?>:</font> </td>
             <td>
               <select id="titleAlign" name="DISP_MORE[weight]" onChange="FontPreview('more', this.value);" class="thinBox">
                 <option value="normal" <? echo $chkMoreWeightNormal; ?>><? echo lang("Normal"); ?></option>
                 <option value="bold" <? echo $chkMoreWeightBold; ?>><? echo lang("Bold"); ?></option>
               </select>
             </td>
           </tr>
        </table>
        </div>

                                             <!---Date Options Div--->

        <div id="tabDate" height="30" valign="bottom" style="display: none;">
        <table width="100%" height="140" border="0" cellspacing="0" cellpadding="0" background="images/settings_tabs-BG.gif" style="background-color: #68AAE5;">
           <tr>
             <td align="center" width="50%"><font style="font-family: Arial; font-size: 9pt;"><? echo lang("Display Date"); ?>:</font> </td>
             <td>
               <input <? echo $chkDate; ?> onClick="LocNow('all');" id="DISP_DATE" type="checkbox" name="DISP_DATE[display]">
             </td>
           </tr>
           <tr>
             <td align="center"><font style="font-family: Arial; font-size: 9pt;"><? echo lang("Italic"); ?>:</font> </td>
             <td>
               <input <? echo $chkDateItalic; ?> onClick="dateStyle();" id="styleDate" type="checkbox" name="DISP_DATE[fontStyle]">
             </td>
           </tr>
           <tr>
             <td align="center"><font style="font-family: Arial; font-size: 9pt;"><? echo lang("Font Weight"); ?>:</font> </td>
             <td>
               <select id="dateWeight" name="DISP_DATE[weight]" onChange="FontPreview('dateBottom', this.value);" class="thinBox">
                 <option value="normal" <? echo $chkDateWeightNormal; ?>><? echo lang("Normal"); ?></option>
                 <option value="bold" <? echo $chkDateWeightBold; ?>><? echo lang("Bold"); ?></option>
               </select>
             </td>
           </tr>
           <tr>
             <td align="center"><font style="font-family: Arial; font-size: 9pt;"><? echo lang("Date Format"); ?>:</font></td>
             <td>
               <select id="dateFormat" name="DISP_DATE[format]" onChange="formatNow(this.value);" class="thinBox">
                 <option value="full" <? echo $dateFull; ?>><? echo lang("January 1 2006"); ?></option>
                 <option value="full2" <? echo $dateFull2; ?>><? echo lang("1-January-2006"); ?></option>
                 <option value="half" <? echo $dateHalf; ?>><? echo lang("Jan 1 2006"); ?></option>
                 <option value="half2" <? echo $dateHalf2; ?>><? echo lang("1-Jan-2006"); ?></option>
                 <option value="allNum" <? echo $dateAll; ?>>2006-1-1</option>
                 <option value="allNum2" <? echo $dateAll2; ?>>1-1-2006</option>
               </select>
             </td>
           </tr>
           <tr>
             <td align="center"><font style="font-family: Arial; font-size: 9pt;"><? echo lang("Select Date Location"); ?>:</font> </td>
             <td>
               <select id="dateLoc" name="DISP_DATE[position]" onChange="LocNow(this.value);" class="thinBox">
                 <option value="dateFirst" <? echo $chkFirst; ?>><? echo lang("Left of title"); ?></option>
                 <option value="dateLast" <? echo $chkLast; ?>><? echo lang("Right of title"); ?></option>
                 <option value="dateTop" <? echo $chkBoxTop; ?>><? echo lang("Above Title"); ?></option>
                 <option value="dateBottom" <? echo $chkBoxBott; ?>><? echo lang("Below Read More"); ?></option>
               </select>
             </td>
           </tr>
           <tr>
             <td align="center"><font style="font-family: Arial; font-size: 9pt;">
             <div id="AlignDate1" style="display: <? echo $alignBordDisp; ?>;"><? echo lang("Align"); ?>:</div></font> </td>
             <td><div id="AlignDate2" style="display: <? echo $alignBordDisp; ?>;">
               <select id="DateAlign" name="DISP_DATE[align]" onChange="AlignPreview('date', this.value);" class="thinBox">
                 <option value="left" <? echo $chkDateAlignLeft; ?>><? echo lang("Left"); ?></option>
                 <option value="center" <? echo $chkDateAlignCenter; ?>><? echo lang("Center"); ?></option>
                 <option value="right" <? echo $chkDateAlignRight; ?>><? echo lang("Right"); ?></option>
               </select></div>
             </td>
           </tr>
	           <tr>
	             <td align="center"><font style="font-family: Arial; font-size: 9pt;">
	             <div id="BordDate1" style="display: <? echo $alignBordDisp; ?>;"><? echo lang("Border"); ?>:</div></font> </td>
	             <td><div id="BordDate2" style="display: <? echo $alignBordDisp; ?>;">
	               <input <? echo $chkDateBorder; ?> onClick="BorderPreview('date', '1px solid rgb(102, 102, 102)');" id="DISP_DATE" type="checkbox" name="DISP_DATE[border]">
	               </div>
	             </td>
	           </tr>
        </table>
        </div>
</div>



<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td colspan="4" class="fsub_title">
     <? echo $template." > Box #".$boxnum; ?>
    </td>
  </tr>

 <!---Spacer Row--->
 <tr>
  <td colspan="4" align="center" valign="top"><img src="../spacer.gif" height="5" width="10"></td>
 </tr>

 <!---title-->
 <tr>
  <td><? echo lang("Box title"); ?>:</td>
  <td colspan="2"><input type="text" name="FUTURE1[title]" value="<? echo $box_title; ?>" style="width: 180px;" class="tfield"></td>
  <td align="center">
   <input id="disCancel" type="button" onClick="cancelEdit();" name="cancel" <? echo $_SESSION['btn_edit']; ?> value="<? echo lang("Cancel"); ?>" style="width: 100px; margin-right: 20px;">
   <input id="disSave" type="submit" name="addMe" <? echo $_SESSION['btn_save']; ?> value="<? echo lang("Save"); ?>" style="width: 100px;">
  </td>
 </tr>

  <!---Number display/Num character---->
  <tr>
    <td><? echo lang("Display type"); ?>:</td>
    <td>
      <select id="dispType" name="CONTENT[type]" onChange="dispNumEnt();" class="tfield" style="width: 195px;">
        <option value="latest" <? echo $latestSel; ?>><? echo lang("Latest blog entry only"); ?></option>
        <option value="muli" <? echo $multiSel; ?>><? echo lang("Multiple blog entries"); ?></option>
        <option value="random" <? echo $randomSel; ?>><? echo lang("One entry selected at random"); ?></option>
      </select>
    </td>
    <td><? echo lang("Number of blog entries"); ?>:</td>
    <td>
      <input id="NUM_DISPLAY" type="text" name="NUM_DISPLAY[blog]" class="thinBox" value="<? echo $num_display; ?>" style="background-color: <? echo $num_displayOff; ?>;" <? echo $numDisp; ?>>
    </td>
  </tr>
  <tr>
    <td>
<?
# popup-limit_chars
$content .= "<p>Selecting this option will make it so that only the first X number of characters of content \n";
$content .= "from the blog entry/news item/whatever will appear in this template box \n";
$content .= "(where X is the number you specify in the <b>'Character limit' text box</b> that becomes enabled when you check the checkbox to limit characters).";
$content .= "This can be useful when you're using a template box to show latest news articles and you want to\n";
$content .= "display a little blurb of the article's content instead of just the article title.</p>\n";

$content .= "<h2 style=\"margin-bottom: 0;\">Note about images</h2>\n";
$content .= "<p style=\"margin-top: 0;\">If you check this option and choose to limit characters any images in your blog entry text will not be displayed in the template box.\n";
$content .= "So, for example, if you've got a news article that has an image placed in between the first and second sentences, and you limit this template box\n";
$content .= "to displaying only the first 200 characters (so about two sentences), it will display the text from the first and second sentences <b>but not the image in between</b>.\n";
$content .= "Of course, once the visitor clicks the 'Read More' link to view the full article text, you image will display as normal.</p>\n";
//($idname, $title, $popup_content, $style = "")
echo help_popup("popup-limit_chars", "Limiting the amount of article content to display in this template box", $content, "top: 10px;left:25px;z-index:1000;");
echo lang("Limit number of<br>characters?");
echo "<span class=\"orange uline hand\" onclick=\"showid('popup-limit_chars');\">[?]</span>\n";
?>
    </td>

    <td>
      <input <? echo $chkChar; ?> id="limitChar" type="checkbox" onClick="dispNumChar();">
    </td>
    <td><? echo lang("Character limit"); ?>:</td>
    <td>
      <input id="NUM_LIMIT" type="text" name="NUM_DISPLAY[chars]" class="thinBox" value="<? echo $num_limit; ?>" style="background-color: <? echo $num_limitOff; ?>;" <? echo $numChar; ?>>
    </td>
  </tr>




								<!---Spacer Row--->

 <tr>
  <td colspan="4" align="center" valign="top"><img src="../spacer.gif" height="5" width="10"></td>
 </tr>

								<!---Cancel/Save buttons---->

  <tr>
  <td colspan="4">


								<!---Use template Styles---->

		<div id="styles2">
			<font style="font-family: Arial; font-size: 9pt;">
				<b><? echo lang("Use template styles"); ?></b>
				<input <? echo $chkTmpltStyle; ?> onClick="modSettings('useTemp');" id="useTempStyles" name="SETTINGS[template]" type="checkbox"><br>
			</font>
			<font color="#727272" style="font-family: Arial; font-size: 9pt;">
				<? echo lang("<b>Notice:</b> If Use template styles is selected, styles from your template will overwrite all"); ?>
				<? echo lang("styles defined below <b>other than</b> display on/off, date format and date location. It is recommended that you leave this box checked."); ?>
			</font>
		</div>

		</td>
	</tr>
	<tr>

								<!---Display Settings---->

    <td id="text1" height="40" colspan="2" align="center" style="border: 1px solid #000000; border-style: solid none none none;">
     &nbsp;
    </td>

								<!---Preview---->

    <td id="text2" colspan="2" align="center" style="border: 1px solid #000000; border-style: solid none none none;"><span class="style1"><b><? echo lang("Preview"); ?></b></span></td>
  </tr>
  <tr>
    <td height="40" align="center"></td>
	<td colspan="2" rowspan="4"><div id="prevDisp" style="height:120;">
		<font style="font-family: Arial; font-size: 8pt;">
		<div id="dateTop" align="<? echo $DateAlign; ?>" style="z-index: 5; font-weight: <? echo $chkDateWeight; ?>; font-style: <? echo $dateItalic; ?>; display:<? echo $DateDispTop; ?>; position:relative; height:20px; width:250px; top:0px; left:300px; border: <? echo $DateBorder; ?>;"><? echo $dateDispBott; ?></div></font>
		<font style="font-family: Arial; font-size: 9pt;">
		<div id="head" align="<? echo $titleAlign; ?>" style="z-index: 5; font-weight: <? echo $chkTitleWeight; ?>; display:<? echo $headDisp; ?>; position:relative; height:20px; width:250px; left:300px; border: <? echo $titleBorder; ?>;"><span id="dateFirst" style="display:<? echo $DateFirst; ?>; font-style: <? echo $dateItalicFirst; ?>; font-weight: <? echo $chkDateWeight; ?>; font-size: 11px;"><? echo $dateDispFirst; ?></span>Blog Title<span id="dateLast" style="display:<? echo $DateLast; ?>; font-style: <? echo $dateItalicLast; ?>; font-weight: <? echo $chkDateWeight; ?>; font-size: 11px;"><? echo $dateDispLast; ?></span></div>
		<div id="cont" style="z-index: 5; display:<? echo $contDisp; ?>; position:relative; height:50px; width:250px; top:0px; left:300px; border: <? echo $contDispBorder; ?>;"><? echo lang("This is my blog content. This is my blog content. This is my blog content. This is my blog content."); ?></div></font>
		<font style="font-family: Arial; font-size: 8pt;">
		<div id="more" align="<? echo $MoreAlign; ?>" style="z-index: 5; font-weight: <? echo $chkMoreWeight; ?>; display:<? echo $MoreDisp; ?>; position:relative; height:20px; width:250px; top:0px; left:300px; border: <? echo $MoreBorder; ?>;"><a href="#"><? echo $moreText; ?></a></div>
		<div id="dateBottom" align="<? echo $DateAlign; ?>" style="font-weight: <? echo $chkDateWeight; ?>; font-style: <? echo $dateItalic; ?>; display:<? echo $DateDispBott; ?>; position:relative; height:20px; width:250px; top:0px; left:300px; border: <? echo $DateBorder; ?>;"><? echo $dateDispBott; ?></div>
		</font></div><br/><br/>
	</td>


 <!---Spacer Row--->
 <tr>
  <td colspan="4" align="center" valign="top"><img src="../spacer.gif" height="5" width="10"></td>
 </tr>

</table>
</form>


