<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.5
##
## Author:        Mike Johnston [mike.johnston@soholaunch.com]
## Homepage:      http://www.soholaunch.com
## Bug Reports:   http://bugzilla.soholaunch.com
## Release Notes: sohoadmin/build.dat.php
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

session_start();

# Include core files
require_once("../includes/product_gui.php");


// while developing module template
//header("location: sample_module.php");



#######################################################
### PROCESS ACTUAL CREATION OF PAGES                ###
#######################################################
if ( $_POST['action'] == "process" ) {

//  Testing for pagenames, and values(ONMENU)
// foreach ( $_POST as $fname=>$val ) {
// echo "<b>".$fname."</b>: (".$val.")<br>\n";
// }
// exit;

   include ("includes/add_pages.inc.php");

   // Take User Directly to Open Page Area for Editing
   // User has option to create more pages if he/she desires

   header("Location: open_page.php?cnew=1&problems=".base64_encode(implode(",", $errors)));
   exit;

}

#######################################################
### START HTML/JAVASCRIPT CODE             ###
#######################################################

# Start buffering output
ob_start();
?>


<script language="javascript">

   function SV2_findObj(n, d) { //v3.0
     var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
       d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
     if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
     for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=SV2_findObj(n,d.layers[i].document); return x;
   }

   function SV2_showHideLayers() { //v3.0
     var i,p,v,obj,args=SV2_showHideLayers.arguments;
     for (i=0; i<(args.length-2); i+=3) if ((obj=SV2_findObj(args[i]))!=null) { v=args[i+2];
       if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
       obj.visibility=v; }
   }

   function SV2_popupMsg(msg) { //v1.0
     alert(msg);
   }

   function SV2_openBrWindow(theURL,winName,features) { //v2.0
     window.open(theURL,winName,features);
   }


</script>


<?
//echo "<body bgcolor=white text=black link=red vlink=red alink=red leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 onLoad=\"show_hide_layer('Layer1','','hide','userOpsLayer','','show');\">\n";
//
////<!-- ============================================================ -->
////<!-- ============= LOAD MODULE DISPLAY LAYER ==================== -->
////<!-- ============================================================ -->
//
//echo " <div id=\"Layer1\" style=\"position:absolute; left:0px; top:40%; width:100%; height:110px; z-index:100; border: 2px none #000000; visibility: visible; overflow: hidden\">\n";
//echo "   <table border=0 cellpadding=0 width=100% height=100% bgcolor=WHITE>\n";
//echo "     <tr>\n";
//echo "       <td align=center valign=middle class=text>Loading...<br/>\n";
//echo "     <img src=\"../../icons/ajax-loader2.gif\" width=60 height=30 border=0>\n";
//echo "       </td>\n";
//echo "     </tr>\n";
//echo "   </table>\n";
//echo " </div>\n";
//
//
//echo ("<div id=\"userOpsLayer\" style=\"position:absolute; visibility: hidden; left:0px; top:0; width:100%; height:100%; z-index:1; overflow: auto; border: 1px none #000000\">\n");

echo "<form name=\"makepages\" method=post action=\"create_pages.php\">\n";
echo "<input type=hidden name=action value=\"process\">\n";


echo "         <table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\" align=\"center\">\n";

# Do not display add to menu option if no access
if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_MENUSYS;", $CUR_USER_ACCESS)) {
   echo "          <tr>\n";
   echo "           <td colspan=\"3\" align=\"right\" style=\"color: #CC0000;\">".lang("Add to Menu")."?</td>\n";
   echo "           <td colspan=\"3\" align=\"right\" style=\"color: #CC0000;\">".lang("Add to Menu")."?</td>\n";
   echo "          </tr>\n";
} // End if allowed to access menu navigation features

$row_cnt = 0;
$pagelimit = pageLimit();

for ($x=1;$x<=10;$x++) {

   $tmp = lang("Page Name") . " (#)";
   $tmp = eregi_replace("#", "$x", $tmp); // Place Number in appropriate spot based on international language

   if ($row_cnt == 0) { echo "          <tr>"; }
   $row_cnt++;

   echo "           <td align=\"left\" valign=\"top\" style=\"padding-left: 20px;\">\n";
   echo "            <font style=\"font-family: Arial; font-size: 8pt;\">".$tmp.":</font>\n";
   echo "           </td>\n";
   echo "           <td align=left valign=\"top\" style=\"padding-right: 20px;\">\n";
	if($x > $pagelimit){
		$disable_createpage = ' disabled="disabled"';
	} else {
		$disable_createpage = '';
	}
   echo "            <input class=\"text\" type=\"text\" size=\"25\" ".$disable_createpage ." name=\"new_page_name".$x."\">\n";
   echo "           </td>\n";

   # Do not display add to menu option if no access
   if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_MENUSYS;", $CUR_USER_ACCESS)) {
      echo "           <td align=\"left\" style=\"padding-right: 20px;\">\n";
      echo "            <select name=\"ONMENU".$x."\">\n";
      echo "             <option value=\"Yes\">yes</option>\n";
      echo "             <option value=\"No\" selected>no</option>\n";
      echo "            </select>\n";
      echo "           </td>\n";
   }

   if ($row_cnt == 2) { echo "          </tr>"; $row_cnt = 0; }

            /*
            echo ("<td align=left valign=top><FONT STYLE='font-family: Arial; font-size: 8pt;'>".$lang["Page Type"].": <SELECT name=subpage$x><option value=\"Main\">".$lang["Menu Page"]."</option>");

            if (!eregi("Lite", $version)) {

               if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_NEWSLETTER;", $CUR_USER_ACCESS)) {
                  echo ("<option value=\"newsletter\">".$lang["Newsletter"]."</option>\n");
               }
               if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_CALENDAR;", $CUR_USER_ACCESS)) {
                  echo ("<option value=\"calendar\">".$lang["Calendar Attachment"]."</option>\n");
               }
               if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_SHOPPING_CART;", $CUR_USER_ACCESS)) {
                  echo ("<option value=\"cart\">".$lang["Shopping Cart Attachment"]."</option>\n");
               }

            }

            echo ("</select></td></tr>\n");
            */

} // End For Loop

echo "          <tr>\n";
echo "           <td colspan=\"6\" align=\"center\">\n";
echo '			<button type=button class="greenButton" onclick="document.makepages.submit();"><span><span>'.lang("Create New Pages").'</span></span></button>';
//echo "            <input type=\"submit\" class=\"btn_save\" value=\"".lang("Create New Pages")."\" onMouseover=\"this.className='btn_saveon';\" onMouseout=\"this.className='btn_save';\">\n";
echo "           </td>\n";
echo "          </tr>\n";

if($disable_createpage!=''){
	echo "          <tr>\n";
	echo "           <td colspan=\"6\" align=\"center\" style=\"color:#CC0000;font-size:13px;\">\n";
	echo "		  You have created created ".($_SESSION['page_limit']-$pagelimit)." pages.\n";
	echo "		  To create more than ".$_SESSION['page_limit']." pages you must upgrade to the full version.\n";
	
	echo "           </td>\n";
	echo "          </tr>\n";
	
}


echo "         </table>\n";


# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("You may create up to 10 new pages at a time.");
$instructions .= lang("Please only use alpha-numerical characters and spaces.");

# Build into standard module template
$module = new smt_module($module_html);
$module->meta_title = "Create New Pages";
$module->add_breadcrumb_link("Create New Pages", "program/modules/create_pages.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/full_size/create_pages-enabled.gif";
$module->heading_text = "Create New Pages";
$module->description_text = $instructions;
$module->good_to_go();
?>
