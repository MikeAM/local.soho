<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.7
##
## Author:        Mike Morrison
## Homepage:      http://www.soholaunch.com
## Bug Reports:   http://bugz.soholaunch.com
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


/*------------------------------------------------------------------------------------------------------------------------------------------*
....########....###.....#######.....##.....##....###....##....##....###.....######...########.########.......................................
....##.........##.##...##.....##....###...###...##.##...###...##...##.##...##....##..##.......##.....##......................................
....##........##...##..##.....##....####.####..##...##..####..##..##...##..##........##.......##.....##......................................
....######...##.....##.##.....##....##.###.##.##.....##.##.##.##.##.....##.##...####.######...########.......................................
....##.......#########.##..##.##....##.....##.#########.##..####.#########.##....##..##.......##...##........................................
....##.......##.....##.##....##.....##.....##.##.....##.##...###.##.....##.##....##..##.......##....##.......................................
....##.......##.....##..#####.##....##.....##.##.....##.##....##.##.....##..######...########.##.....##......................................
/*------------------------------------------------------------------------------------------------------------------------------------------*/

session_start();
error_reporting(E_PARSE);

# Primary interface include
require_once("../includes/product_gui.php");

//echo testArray($_GET); exit;

/*---------------------------------------------------------------------------------------------------------*
    ___        __     __   ______ ___    ____
   /   |  ____/ /____/ /  / ____//   |  / __ \
  / /| | / __  // __  /  / /_   / /| | / / / /
 / ___ |/ /_/ // /_/ /  / __/  / ___ |/ /_/ /
/_/  |_|\__,_/ \__,_/  /_/    /_/  |_|\___\_\

# Save new FAQ action
/*---------------------------------------------------------------------------------------------------------*/
if ( $ball == "addfaq" ) {
   if ( ($FAQ_QUESTION != "") && ($FAQ_ANSWER != "") && ($SORT_NUM != "") ) {

      # Format values for db insert
      $FAQ_QUESTION = eregi_replace("\n", "<br>", $FAQ_QUESTION);
      $FAQ_QUESTION = addslashes($FAQ_QUESTION);
      $FAQ_ANSWER = eregi_replace("\n", "<br>", $FAQ_ANSWER);
      $FAQ_ANSWER = addslashes($FAQ_ANSWER);

      # Add leading zeros to ensure proper sorting
      //echo $SORT_NUM."--->";
      $db_sortnum = sprintf("%05.1f", $SORT_NUM);
      //echo $db_sortnum."<br>"; exit;

      $MY_SQL = "INSERT INTO faq_content VALUES('','".$db_sortnum."','$show_cat','$FAQ_QUESTION','$FAQ_ANSWER')";
      if ( !mysql_query($MY_SQL) ) {
         echo mysql_error();
      }
      $again = "2";
      $choices = "Added FAQ.  You can add another new FAQ or go back to the category's page.";
      $type = "Adding New FAQ to Category ".$gbak['CAT_NAME']."";
      $do = "add";

   } else {
      $err_qas = "Please fill in all fields, delete or select Back to Category's.";
      $quest = $FAQ_QUESTION;
      $answ = $FAQ_ANSWER;
      $sort = $SORT_NUM;
      $do = "add";
   }
}

/*---------------------------------------------------------------------------------------------------------*
   __  __            __        __           ______ ___    ____
  / / / /____   ____/ /____ _ / /_ ___     / ____//   |  / __ \
 / / / // __ \ / __  // __ `// __// _ \   / /_   / /| | / / / /
/ /_/ // /_/ // /_/ // /_/ // /_ /  __/  / __/  / ___ |/ /_/ /
\____// .___/ \__,_/ \__,_/ \__/ \___/  /_/    /_/  |_|\___\_\
     /_/

# Save changes to existing FAQ
/*---------------------------------------------------------------------------------------------------------*/
if ( $ball == "updatefaq" ) {
   if ( ($FAQ_QUESTION != "") && ($FAQ_ANSWER != "") && ($SORT_NUM != "") ) {

      # Format values for db insert
      $FAQ_QUESTION = eregi_replace("\n", "<br>", $FAQ_QUESTION);
      $FAQ_QUESTION = addslashes($FAQ_QUESTION);
      $FAQ_ANSWER = eregi_replace("\n", "<br>", $FAQ_ANSWER);
      $FAQ_ANSWER = addslashes($FAQ_ANSWER);

      # Add leading zeros to ensure proper sorting
      //echo $SORT_NUM."--->";
      $db_sortnum = sprintf("%05.1f", $SORT_NUM);
      //echo $db_sortnum."<br>"; exit;

      $MY_SQL = "UPDATE faq_content SET SORT_NUM='".$db_sortnum."', FAQ_QUESTION='".$FAQ_QUESTION."', FAQ_ANSWER='".$FAQ_ANSWER."' WHERE prikey='".$questid."'";
      if ( !mysql_query($MY_SQL) ) {
         echo mysql_error();
         //exit;
      }
      $again = "1";
      $quest = $FAQ_QUESTION;
      $answ = $FAQ_ANSWER;
      $sort = $SORT_NUM;
      $choices = "Changes Saved.  You can edit this FAQ again, add a new FAQ or go back to the category's page.";
      $type = "Editing FAQ # ".$gbak['$SORT_NUM']." in Category ".$gbak['CAT_NAME']."";
      $do = "edit";

   } else {
      $err_qas = "Please fill in all fields, add a new FAQ, delete or select Back to Category's.";
      $quest = $FAQ_QUESTION;
      $answ = $FAQ_ANSWER;
      $sort = $SORT_NUM;
      $do = "edit";
   }
}


/*---------------------------------------------------------------------------------------------------------*
    ____         __       __           ______ ___    ____
   / __ \ ___   / /___   / /_ ___     / ____//   |  / __ \
  / / / // _ \ / // _ \ / __// _ \   / /_   / /| | / / / /
 / /_/ //  __// //  __// /_ /  __/  / __/  / ___ |/ /_/ /
/_____/ \___//_/ \___/ \__/ \___/  /_/    /_/  |_|\___\_\
/*---------------------------------------------------------------------------------------------------------*/
if( $dokill == "kill" ) {
   if (!mysql_query("delete from faq_content WHERE prikey='$questid'") ) {
      echo mysql_error();
   }
   header("location:faq_manager.php?show_cat=".$show_cat);
}


/*---------------------------------------------------------------------------------------------------------*
 ______     _  _  _     ______        ____
|  ____|   | |(_)| |   |  ____|/\    / __ \
| |__    __| | _ | |_  | |__  /  \  | |  | |
|  __|  / _` || || __| |  __|/ /\ \ | |  | |
| |____| (_| || || |_  | |  / ____ \| |__| |
|______|\__,_||_| \__| |_| /_/    \_\\___\_\

# Add new FAQ or edit existing FAQ
/*---------------------------------------------------------------------------------------------------------*/
$rez = mysql_query("SELECT * FROM faq_content WHERE CAT_NAME='$show_cat' ORDER BY ROUND(SORT_NUM, 3) ASC");
$billy = mysql_num_rows($rez);

if( $do == "add" ) {
   //ADD TEXT
   $ball = "addfaq";
   $buttontext = "Add FAQ";
   $rez = mysql_query("SELECT * FROM faq_category WHERE prikey='$show_cat'");
   $gbak = mysql_fetch_array($rez);
   $type = "Adding New FAQ to Category ".$gbak['CAT_NAME']."";
   //$sort = sprintf("%04s", (($billy + 1) * 10));
   $sort = $highest_sortnum + 1;


} elseif ($do == "edit") {

   # UPDATE TEXT
   $ball = "updatefaq";
   $buttontext = "Save Changes";

   # Pull category data
   $rez = mysql_query("SELECT * FROM faq_category WHERE prikey='$show_cat'");
   $gbak = mysql_fetch_array($rez);

   # Pull data for this question
   $rez = mysql_query("SELECT * FROM faq_content WHERE prikey='$questid'");
   $getQues = mysql_fetch_array($rez);

   # Pull existing sort number
   //$sort = $getQues['SORT_NUM'];
   $sort = $getQues['SORT_NUM'];

   $type = "Editing FAQ # ".$sort." in Category ".$gbak['CAT_NAME']."";
}

# Duct-tape: Auto-increment sort order
//if ( $sort == "" ) { $sort = sprintf("%03s", ($billy + 1)); }

//###::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//### NEW MODULE OBJECT
//###::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//$dMod = new feature_module("", "webmaster");

# Declare main html var
$disHTML = "";

$disHTML .= "<script language=\"javascript\">\n";
$disHTML .= "  function navto(here){\n";
$disHTML .= "     window.location=here\n";
$disHTML .= "  }\n";
$disHTML .= "</script>\n";


$disHTML .= "<style type=\"text/css\">\n";
$disHTML .= "<!--\n";
$disHTML .= "\n";
$disHTML .= "#zclass { font-family: \"Courier New\", Courier, mono; font-size: 11px; font-weight: bold; text-align: right; }\n";
$disHTML .= ".style8 {color: #D70000}\n";
$disHTML .= ".tfield {\n";
$disHTML .= "  font-family: verdana, arial, helvetica, sans-serif;\n";
$disHTML .= "  font-size: 10px;\n";
$disHTML .= "}\n";
//$disHTML .= ".fsub_col {\n";
//$disHTML .= "  font-family: verdana, arial, helvetica, sans-serif;\n";
//$disHTML .= "  font-size: 10px;\n";
//$disHTML .= "  font-weight: bold;\n";
//$disHTML .= "  padding: 2px;\n";
//$disHTML .= "  border: 1px solid #B5B5B5;\n";
//$disHTML .= "  border-style: solid solid solid solid;\n";
//$disHTML .= "  color: #000000;\n";
//$disHTML .= "  background: #E7EFF5;\n";
//$disHTML .= "}\n";
$disHTML .= ".tboxjoe {\n";
$disHTML .= "  font-family: verdana, arial, helvetica, sans-serif;\n";
$disHTML .= "  font-size: 10px;\n";
$disHTML .= "  background-color: #E8ECEF;\n";
$disHTML .= "  border: #000000;\n";
$disHTML .= "  border-style: solid;\n";
$disHTML .= "  border-top-width: thin;\n";
$disHTML .= "  border-right-width: thin;\n";
$disHTML .= "  border-bottom-width: thin;\n";
$disHTML .= "  border-left-width: thin;\n";
$disHTML .= "  height: 100px;\n";
$disHTML .= "  width: 500px;\n";
$disHTML .= "  overflow: scroll;\n";
$disHTML .= "}\n";
$disHTML .= ".style9 {\n";
$disHTML .= "  font-family: Arial;\n";
$disHTML .= "  font-weight: bold;\n";
$disHTML .= "  font-size: smaller;\n";
$disHTML .= "}\n";

# Q & A text box styles
$disHTML .= "#questionbox {\n";
$disHTML .= "  border: 1px solid #339959;\n";
$disHTML .= "  height: 50px;\n";
$disHTML .= "  width: 550px;\n";
$disHTML .= "}\n";
$disHTML .= "#answerbox {\n";
$disHTML .= "  border: 1px solid #980000;\n";
$disHTML .= "  height: 200px;\n";
$disHTML .= "  width: 550px;\n";
$disHTML .= "}\n";

$disHTML .= "-->\n";
$disHTML .= "</style>\n\n";



# (?) Help popup: FAQ Sort Number
#--------------------------------------------------------
$pophelp_close = "document.getElementById('pophelp_sortnum').style.display='none';"; // Readibility and quicker copy-paste-modify process when duplicating elsewhere

$disHTML .= "<!--- ======================================================================================================== --->\n";
$disHTML .= "<!--- ======================================= (?) HELP POPUP ================================================= --->\n";
$disHTML .= "<!--- ======================================================================================================== --->\n";
$disHTML .= "<div id=\"pophelp_sortnum\" style=\"width: 500px; padding: 0px; position: absolute; left: 20%; top: 22%; z-index: 15; display: none;\">\n";
$disHTML .= " <table class=\"feature_gray\" cellspacing=\"0\" cellpadding=\"8\">\n";
$disHTML .= "  <tr>\n";
$disHTML .= "   <td class=\"fsub_title\"><img border=\"0\" src=\"../includes/display_elements/graphics/help_icon-fsub_title.gif\"></td>\n";
$disHTML .= "   <td class=\"fsub_title\" width=\"100%\">FAQ Sort Number</td>\n";
$disHTML .= "   <td class=\"fsub_title\" width=\"100%\">[<span class=\"red hand\" onclick=\"".$pophelp_close."\">X</span>]</td>\n";
$disHTML .= "  </tr>\n";
$disHTML .= "  <tr>\n";
$disHTML .= "   <td colspan=\"3\">\n";
$disHTML .= "    <p>The number in this box will determine the order in which this FAQ is listed when displayed on your website (relative to the other FAQs in this category).</p>\n";

$disHTML .= "    <p><b>Do I have to mess with this?</b><br>\n";
$disHTML .= "    No. There's absolutely no harm in leaving this number alone unless you specifically want to change its display order.</p>\n";

//$disHTML .= "    <p><b>Why the leading zeros?</b><br>\n";
//$disHTML .= "    Most web servers treat '2' as a higher number than '10', which often leads to improper ordering of your FAQs when they're shown on your website.\n";
//$disHTML .= "    Adding the leading zeros (so '2' becomes '002') resolves this problem.</p>\n";

$disHTML .= "    <p><b>What's the decimal place for?</b><br>\n";
$disHTML .= "    The decimal place allows you to fine-tune the order in which your FAQs display.<br><br>\n";
$disHTML .= "    For example, you could make FAQ #25.0 appear on your website between FAQ #3.0 and FAQ #4.0\n";
$disHTML .= "    by changing its FAQ Sort Number from '25.0' to '3.1'.<br>\n";
$disHTML .= "   </td>\n";
$disHTML .= "  </tr>\n";
$disHTML .= "  <tr>\n";
$disHTML .= "   <td colspan=\"2\" align=\"right\">[ <span class=\"hand red uline\" onclick=\"".$pophelp_close."\">Close Window</span> ]</td>\n";
$disHTML .= "  </tr>\n";
$disHTML .= " </table>\n";
$disHTML .= "</div>\n";

$disHTML .= "<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">\n";

$rez = mysql_query("SELECT * FROM faq_category WHERE prikey='$show_cat'");
$gbak = mysql_fetch_array($rez);

$disHTML .= " <tr>\n";
$disHTML .= "  <td colspan=\"2\">\n";
$disHTML .= "   <b>".$type."</b>\n";

# Total FAQ's in category
$disHTML .= "   <span class=\"unbold\">(FAQs in category: <span class=\"red\">".$billy."</span>)</span>\n";

$disHTML .= "  </td>\n";
$disHTML .= " </tr>\n";

$disHTML .= " <tr>\n";

# Delete button - only show if editing existing faq (not when adding a new one)
if ( $do == "edit" ) {
   $disHTML .= "  <td align=\"right\">\n";
   $disHTML .= "   <button name=\"Submit\" type=\"button\" onclick=\"navto('faq_managerED.php?dokill=kill&questid=$questid&do=$gohome&show_cat=".$show_cat."')\" class=\"redButton\"><span><span>".lang('Delete This FAQ')."</span></span></button>\n";
   $disHTML .= "  </td>\n";
} else {
   $disHTML .= "  <td align=\"right\">&nbsp;</td>\n";
}

# Show 'Back to FAQ Manager' at top-right when entering edit screen from main faq screen
if ( !isset($again) ) {
   $disHTML .= "  <td align=\"right\" width=\"80%\">\n";
   $disHTML .= "   <button name=\"button\" type=\"button\" onclick=\"navto('faq_manager.php?show_cat=".$show_cat."')\" class=\"blueButton\"><span><span>".lang('Back to FAQ Manager')."</span></span></button>\n";
   $disHTML .= "  </td>\n";
}

$disHTML .= " </tr>\n";

$disHTML .= "  <tr>\n";
$disHTML .= "   <td colspan=\"2\" valign=\"top\">\n";
$disHTML .= "    <form name=\"addfaq\" method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">\n";
$disHTML .= "    <input type=\"hidden\" name=\"ball\" value=\"".$ball."\">\n";
$disHTML .= "    <input type=\"hidden\" name=\"CAT_NAME\" value=\"".$gbak['CAT_NAME']."\">\n";
$disHTML .= "    <input type=\"hidden\" name=\"questid\" value=\"$questid\">\n";
$disHTML .= "    <input type=\"hidden\" name=\"highest_sortnum\" value=\"".$sort."\">\n";
$disHTML .= "    <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"3\">\n";

# CONFIRM UPDATE
if ( $again == "1" ) {
   $disHTML .= "     <tr>\n";
   $disHTML .= "      <td align=\"center\" colspan=\"3\"><font style=\"font-family: Arial; font-size: 9pt;\"><font color=green><b>$choices</b></td>\n";
   $disHTML .= "     </tr>\n";
}

# CONFIRM ADD
if ( $again == "2" ) {
   $disHTML .= "     <tr>\n";
   $disHTML .= "      <td align=\"center\" colspan=\"3\"><font style=\"font-family: Arial; font-size: 9pt;\"><font color=green><b>$choices</b></td>\n";
   $disHTML .= "     </tr>\n";
}


# Format question and answer text
//$answ = stripslashes($answ);
$answ = eregi_replace("<br>", "\n", $getQues['FAQ_ANSWER']);
$answ = stripslashes($answ);
//$answ = eregi_replace("\"", "'", $answ);

$question_text = eregi_replace("<br>", "\n", $getQues['FAQ_QUESTION']);
$question_text = stripslashes($question_text);
//$question_text = eregi_replace("\"", "'", $question_text);

# QUESTION
$disHTML .= "     </tr>\n";
$disHTML .= "     <tr>\n";
$disHTML .= "      <td align=\"center\" valign=\"top\" class=\"fsub_col\" style=\"border: 1px solid #cccccc;\">Question:</td>\n";
$disHTML .= "      <td valign=\"center\" align=\"left\" colspan=\"2\">\n";
$disHTML .= "       <textarea name=\"FAQ_QUESTION\" class=\"tfield\" id=\"questionbox\">".$question_text."</textarea>\n";
$disHTML .= "      </td>\n";
$disHTML .= "     </tr>\n";

# ANSWER
$disHTML .= "     <tr>\n";
$disHTML .= "      <td align=\"center\" valign=\"top\" class=\"fsub_col\" style=\"border: 1px solid #cccccc;\">Answer:</td>\n";
$disHTML .= "      <td valign=\"top\" align=\"left\" colspan=\"2\"><div align=\"left\">\n";
$disHTML .= "       <input type=\"hidden\" name=\"show_cat\" value=\"$show_cat\">\n";
$disHTML .= "       <textarea name=\"FAQ_ANSWER\" id=\"answerbox\" class=\"tfield\">".$answ."</textarea>\n";
$disHTML .= "      </td>\n";
$disHTML .= "     </tr>\n";

//echo $highest_sortnum."<br>".$sort; exit;

# SORT_NUM
$disHTML .= "     <tr>\n";
$disHTML .= "      <td align=\"center\" class=\"fsub_col\">FAQ #</td>\n";
$disHTML .= "      <td valign=\"center\" align=\"left\" style=\"width: 90px;\">\n";
$disHTML .= "       <input name=\"SORT_NUM\" type=\"text\" class=\"tfield\" value=\"".sprintf("%.1f", $sort)."\" style=\"width: 60px;\">\n";
$disHTML .= "      </td>\n";

# (?) Help Icon: FAQ Sort Number
$disHTML .= "      <td align=\"left\" style=\"width: 100%; padding-top: 2px;\">\n";
$disHTML .= "       <img border=\"0\" src=\"../includes/display_elements/graphics/help_icon-f8f9fd.gif\" onClick=\"toggleid('pophelp_sortnum');\" class=\"hand\">\n";
$disHTML .= "      </td>\n";
$disHTML .= "     </tr>\n";
$disHTML .= "    </table>\n";

# Button row
$disHTML .= "    <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"3\">\n";
$disHTML .= "     <tr>\n";


# Add New FAQ button (only show as option after updating existing faq)
if($again == "1") {
   $disHTML .= "      <td align=\"left\" style=\"width: 33%;\">\n";
   $disHTML .= "       <button type=\"button\" onclick=\"navto('faq_managerED.php?show_cat=$show_cat&do=add&highest_sortnum=".$sort."');\" class=\"greenButton\" ><span><span>".lang('Add New FAQ')."</span></span></button>\n";
   $disHTML .= "      </td>\n";
} else {
   $disHTML .= "      <td align=\"left\" style=\"width: 33%;\">&nbsp;</td>\n";
}

# Add/Save FAQ button
if ( $do == "add" ) {
   $disHTML .= "      <td align=\"center\" style=\"width: 33%;\">\n";
   $disHTML .= "       <button type=\"submit\" onClick=\"document.addfaq.submit();\" class=\"greenButton\"><span><span>".lang('Add FAQ')."</span></span></button>\n";
   $disHTML .= "      </td>\n";
} else {
   $disHTML .= "      <td align=\"center\" style=\"width: 33%;\">\n";
   $disHTML .= "       <button type=\"submit\" onClick=\"document.addfaq.submit();\" class=\"greenButton\"><span><span>".lang('Save FAQ')."</span></span></button>\n";
   $disHTML .= "      </td>\n";
}

# Show bottom-right 'Back to FAQ Manager' button after save action
if ( isset($again) ) {
   $disHTML .= "      <td align=\"right\" style=\"width: 33%;\">\n";
   $disHTML .= "       <button name=\"button\" type=\"button\" onclick=\"navto('faq_manager.php?show_cat=".$show_cat."')\" class=\"blueButton\"><span><span>".lang('Back to FAQ Manager')."</span></span></button>\n";
   $disHTML .= "      </td>\n";
} else {
   $disHTML .= "      <td align=\"right\" style=\"width: 33%;\">\n";
   $disHTML .= "       &nbsp;\n";
   $disHTML .= "      </td>\n";
}

$disHTML .= "      </form>\n";
$disHTML .= "     </tr>\n";
$disHTML .= "    </table>\n";
$disHTML .= "   </tr>\n";
$disHTML .= "  </table>\n";
$disHTML .= " <br><font color=\"#E67300\">$err_qas</font>\n";
$disHTML .= " </td>\n";
$disHTML .= "</tr>\n";
$disHTML .= "</table>\n";



# Start buffering output
ob_start();

echo $disHTML;

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Create, add and manage your site FAQ list's.");
//$instructions .= lang("Please only use alpha-numerical characters and spaces.");

# Build into standard module template
$module = new smt_module($module_html);
$module->meta_title = "FAQ Manger";
$module->add_breadcrumb_link("FAQ Manger", "program/webmaster/faq_manager.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/full_size/faq_manager-enabled.gif";
$module->heading_text = "FAQ Manger";
$module->description_text = $instructions;
$module->good_to_go();
?>
