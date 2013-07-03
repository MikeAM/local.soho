<?php
error_reporting('341');
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

# Get misc preferences for FAQ feature
$faqpref = new userdata("faq");

# DEFAULT: Sort faqs in ascending order
if ( $faqpref->get("sort") == "" ) { $faqpref->set("sort", "asc"); }

# SET SORT ORDER: triggered onchange on sort order dropdown
if ( $_GET['set_sortorder'] != "" ) { $faqpref->set("sort", $_GET['set_sortorder']); }

if($_REQUEST['highest_sortnum']!=''){
$highest_sortnum = $_REQUEST['highest_sortnum'];
}
#######################################################
### READ CURRENT CATEGORIES INTO MEMORY             ###
#######################################################
$match = 0;
$tablename = "faq_content";

$result = soho_list_tables();
$i = 0;
while ($i < mysql_num_rows ($result)) {
   $tb_names[$i] = mysql_tablename ($result, $i);
   if ($tb_names[$i] == $tablename) {
      $match = 1;
   }
   $i++;
}

if ($match == 1) {
   $catFlag = 1;
   $result = mysql_query("SELECT category FROM faq_content");
   $numberRows = mysql_num_rows($result);
   $a=0;
   $catcount=0;
   while ($row = mysql_fetch_array ($result)) {
      $a++;
      $dbcats[$a] = $row["category"];
      if (strlen($dbcats[$a]) > 2) {
         $catcount++;
      }
   }
} else {
   $catFlag = 0;
   $catcount = 0;
}


#######################################################
### Locate Number of Sku's In database        ###
#######################################################

$match = 0;
$tablename = "faq_content";

$result = soho_list_tables();
$i = 0;
while ($i < mysql_num_rows ($result)) {
   $tb_names[$i] = mysql_tablename ($result, $i);
   if ($tb_names[$i] == $tablename) {
      $match = 1;
   }
   $i++;
}

if ($match == 1) {
   $result = mysql_query("SELECT PRIKEY FROM faq_content");
   $numProducts = mysql_num_rows($result);
} else {
   $numProducts = 0;
}


#######################################################
### IF THE 'FAQ_MANAGER' TABLE DOES NOT EXIST; CREATE
### NOW SO THAT THE OPTIONS CAN BE SAVE WITHOUT THIS
### ROUTINE IN EVERY SINGLE MODULES
#######################################################

$match = 0;
$tablename = "faq_category";

$result = soho_list_tables();
$i = 0;
while ($i < mysql_num_rows ($result)) {
   $tb_names[$i] = mysql_tablename ($result, $i);
   if ($tb_names[$i] == $tablename) {
      $match = 1;
   }
   $i++;
}

if ($match != 1) {

   $qry = "CREATE TABLE faq_category (";
   $qry .= " prikey INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,";
   $qry .= " CAT_NAME CHAR(30)";
  $qry .= " )";


   if ( mysql_db_query("$db_name",$qry) ) {
       //echo " Wokring";

   } else {
      echo "this->(";
      echo mysql_error().")";
     // echo " Not working";
      exit;
   }

} // End if no match for faq_category

$match = 0;
$tablename = "faq_content";

$result = soho_list_tables();
$i = 0;
while ($i < mysql_num_rows ($result)) {
   $tb_names[$i] = mysql_tablename ($result, $i);
   if ($tb_names[$i] == $tablename) {
      $match = 1;
   }
   $i++;
}

if ($match != 1) {
   $qry = "CREATE TABLE faq_content (";
   $qry .= " prikey INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,";
   $qry .= " SORT_NUM CHAR(30),";
   $qry .= " CAT_NAME CHAR(30),";
   $qry .= " FAQ_QUESTION BLOB,";
   $qry .= " FAQ_ANSWER BLOB";
   $qry .= " )";

   if ( mysql_db_query("$db_name",$qry) ) {
      // echo " Wokring";

   } else {
      echo "this->(";
      echo mysql_error().")";
      //echo " Not working";
      exit;
   }

} // End if no match for faq_content


/*---------------------------------------------------------------------------------------------------------*
   ______ __                                  _   __
  / ____// /_   ____ _ ____   ____ _ ___     / | / /____ _ ____ ___   ___
 / /    / __ \ / __ `// __ \ / __ `// _ \   /  |/ // __ `// __ `__ \ / _ \
/ /___ / / / // /_/ // / / // /_/ //  __/  / /|  // /_/ // / / / / //  __/
\____//_/ /_/ \__,_//_/ /_/ \__, / \___/  /_/ |_/ \__,_//_/ /_/ /_/ \___/
                           /____/
/*---------------------------------------------------------------------------------------------------------*/
if ( $ball == "saveCatName" ) {
   foreach($_POST as $var=>$val){
      if(eregi("ORG",$var)){
         $orgName = $val;
      }
      if(strlen($val) > 1 && !eregi("ORG",$var) && !eregi("saveCatName",$val)){
//         echo "setting catname =(".$val.") where catname =(".$orgName.")<br><br>";
         $MY_SQL = "UPDATE faq_category SET CAT_NAME = '$val' WHERE CAT_NAME = '$orgName'";
//       echo "this string (".$MY_SQL.")";
         if(!mysql_query($MY_SQL)) {
            echo "".lang("Could not update category name")."! <br> ".lang("mysql says")." ".mysql_error();
            exit;
         }
      }
   }
}

/*---------------------------------------------------------------------------------------------------------*
    ___        __     __   ______        __
   /   |  ____/ /____/ /  / ____/____ _ / /_ ___   ____ _ ____   _____ __  __
  / /| | / __  // __  /  / /    / __ `// __// _ \ / __ `// __ \ / ___// / / /
 / ___ |/ /_/ // /_/ /  / /___ / /_/ // /_ /  __// /_/ // /_/ // /   / /_/ /
/_/  |_|\__,_/ \__,_/   \____/ \__,_/ \__/ \___/ \__, / \____//_/    \__, /
                                                /____/              /____/
/*---------------------------------------------------------------------------------------------------------*/
if ( $ball == "newcats" ) {
   if ( !$CAT_NAME == "" ) {
      $err_cat = "";
      $MY_SQL = "INSERT INTO faq_category VALUES('','$CAT_NAME')";
      if ( !mysql_query($MY_SQL) ) { echo mysql_error(); }

   } else {
      $err_cat = lang("You must enter a category name");
   }
}


/*---------------------------------------------------------------------------------------------------------*
    __ __  _  __ __   ______        __
   / //_/ (_)/ // /  / ____/____ _ / /_ ___   ____ _ ____   _____ __  __
  / ,<   / // // /  / /    / __ `// __// _ \ / __ `// __ \ / ___// / / /
 / /| | / // // /  / /___ / /_/ // /_ /  __// /_/ // /_/ // /   / /_/ /
/_/ |_|/_//_//_/   \____/ \__,_/ \__/ \___/ \__, / \____//_/    \__, /
                                           /____/              /____/
/*---------------------------------------------------------------------------------------------------------*/
if ( $do == "kill" ) {
   mysql_query("delete from faq_category WHERE prikey=$show_cat");
   mysql_query("delete from faq_content WHERE CAT_NAME=$show_cat");
   $show_cat = "";
}


$rez = mysql_query("SELECT * FROM faq_category");
$billycat=mysql_num_rows($rez);

# Declare main html var
$disHTML = "";

###############################################################################

$disHTML .= "<script language=\"JavaScript\" type=\"text/javascript\" src=\"includes/faq_includes/core.js\"></script>\n";
$disHTML .= "<script language=\"JavaScript\" type=\"text/javascript\" src=\"includes/faq_includes/events.js\"></script>\n";
$disHTML .= "<script language=\"JavaScript\" type=\"text/javascript\" src=\"includes/faq_includes/css.js\"></script>\n";
$disHTML .= "<script language=\"JavaScript\" type=\"text/javascript\" src=\"includes/faq_includes/coordinates.js\"></script>\n";
$disHTML .= "<script language=\"JavaScript\" type=\"text/javascript\" src=\"includes/faq_includes/drag.js\"></script>\n";
$disHTML .= "<script language=\"JavaScript\" type=\"text/javascript\" src=\"includes/faq_includes/dragsort.js\"></script>\n";

$disHTML .= "<script language=\"JavaScript\"><!--\n";
$disHTML .= "var ESCAPE = 27\n";
$disHTML .= "var ENTER = 13\n";
$disHTML .= "var TAB = 9\n";

$disHTML .= "var coordinates = ToolMan.coordinates()\n";
$disHTML .= "var dragsort = ToolMan.dragsort()\n";


## Added to bottom of script insted of onload.
## Was screwing with module template body onload=show_hide_layer()
//$disHTML .= "window.onload = function() {\n";
//$disHTML .= "  join(\"a3\")\n";
//$disHTML .= "  join(\"b3\")\n";
//$disHTML .= "  join(\"c3\")\n";
//$disHTML .= "  join(\"d3\")\n";
//$disHTML .= "  join(\"e3\")\n";
//$disHTML .= "  join(\"f3\")\n";
//$disHTML .= "}\n";


$disHTML .= "function setHandle(item) {\n";
$disHTML .= "  item.toolManDragGroup.setHandle(findHandle(item))\n";
$disHTML .= "}\n";
$disHTML .= "\n";
$disHTML .= "function findHandle(item) {\n";
$disHTML .= "  var children = item.getElementsByTagName(\"div\")\n";
$disHTML .= "  for (var i = 0; i < children.length; i++) {\n";
$disHTML .= "     var child = children[i]\n";
$disHTML .= "\n";
$disHTML .= "     if (child.getAttribute(\"class\") == null) continue\n";
$disHTML .= "\n";
$disHTML .= "     if (child.getAttribute(\"class\").indexOf(\"handle\") >= 0)\n";
$disHTML .= "        return child\n";
$disHTML .= "  }\n";
$disHTML .= "  return item\n";
$disHTML .= "}\n";
$disHTML .= "\n";
$disHTML .= "function join(name, isDoubleClick) {\n";

$disHTML .= "  var view = document.getElementById(name + \"View\")\n";
$disHTML .= "  view.editor = document.getElementById(name + \"Edit\")\n";
$disHTML .= "  var showEditor = function(event) {\n";
$disHTML .= "     document.getElementById('saveName').style.display='block';\n";
$disHTML .= "     event = fixEvent(event)\n";
$disHTML .= "     var view = this\n";
$disHTML .= "     var editor = view.editor\n";
$disHTML .= "     if (!editor) return true\n";
$disHTML .= "     if (editor.currentView != null) {\n";
$disHTML .= "        editor.blur()\n";
$disHTML .= "     }\n";
$disHTML .= "     editor.currentView = view\n";
$disHTML .= "     var topLeft = coordinates.topLeftOffset(view)\n";
$disHTML .= "     topLeft.reposition(editor)\n";
$disHTML .= "     if (editor.nodeName == 'TEXTAREA') {\n";
$disHTML .= "        editor.style['width'] = view.offsetWidth + \"px\"\n";
$disHTML .= "        editor.style['height'] = view.offsetHeight + \"px\"\n";
$disHTML .= "     }\n";
$disHTML .= "     editor.value = view.innerHTML\n";
$disHTML .= "     editor.style['visibility'] = 'visible'\n";
$disHTML .= "     view.style['visibility'] = 'hidden'\n";
$disHTML .= "     editor.focus()\n";
$disHTML .= "     return false\n";
$disHTML .= "  }\n";
$disHTML .= "  if (isDoubleClick) {\n";
$disHTML .= "     view.ondblclick = showEditor\n";
$disHTML .= "  } else {\n";
$disHTML .= "     view.onclick = showEditor;\n";
$disHTML .= "  }\n";

$disHTML .= "  view.editor.onblur = function(event) {\n";
//$disHTML .= "        document.getElementById('saveName').style.display='block';\n";

$disHTML .= "     event = fixEvent(event)\n";
$disHTML .= "     var editor = event.target\n";
$disHTML .= "     var view = editor.currentView\n";
$disHTML .= "     if (!editor.abandonChanges) view.innerHTML = editor.value\n";
$disHTML .= "     editor.abandonChanges = false\n";
$disHTML .= "     editor.style['visibility'] = 'hidden'\n";
//$disHTML .= "      editor.value = '' // fixes firefox 1.0 bug\n";
$disHTML .= "     view.style['visibility'] = 'visible'\n";
$disHTML .= "     editor.currentView = null\n";
$disHTML .= "     return true\n";
$disHTML .= "  }\n";
$disHTML .= "  view.editor.onkeydown = function(event) {\n";
$disHTML .= "     event = fixEvent(event)\n";
$disHTML .= "     \n";
$disHTML .= "     var editor = event.target\n";
$disHTML .= "     if (event.keyCode == TAB) {\n";
$disHTML .= "        editor.blur()\n";
$disHTML .= "        return false\n";
$disHTML .= "     }\n";
$disHTML .= "  }\n";
$disHTML .= "  view.editor.onkeyup = function(event) {\n";
$disHTML .= "     event = fixEvent(event)\n";
$disHTML .= "     var editor = event.target\n";
$disHTML .= "     if (event.keyCode == ESCAPE) {\n";
$disHTML .= "        editor.abandonChanges = true\n";
$disHTML .= "        editor.blur()\n";
$disHTML .= "        return false\n";
$disHTML .= "     } else if (event.keyCode == TAB) {\n";
$disHTML .= "        return false\n";
$disHTML .= "     } else {\n";
$disHTML .= "        return true\n";
$disHTML .= "     }\n";
$disHTML .= "  }\n";
$disHTML .= "  // TODO: this method is duplicated elsewhere\n";
$disHTML .= "  function fixEvent(event) {\n";
$disHTML .= "     if (!event) event = window.event\n";
$disHTML .= "     if (event.target) {\n";
$disHTML .= "        if (event.target.nodeType == 3) event.target = event.target.parentNode\n";
$disHTML .= "     } else if (event.srcElement) {\n";
$disHTML .= "        event.target = event.srcElement\n";
$disHTML .= "     }\n";
$disHTML .= "     return event\n";
$disHTML .= "  }\n";
$disHTML .= "}\n";
$disHTML .= "//-->\n";
$disHTML .= "</script>\n";

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
$disHTML .= ".tfieldlong {\n";
$disHTML .= "  font-family: verdana, arial, helvetica, sans-serif;\n";
$disHTML .= "  font-size: 10px;\n";
$disHTML .= "  height: 65px;\n";
$disHTML .= "  width: 500px;\n";
$disHTML .= "  border: thin solid #000000;\n";
$disHTML .= "}\n";
$disHTML .= ".fsub_col {\n";
$disHTML .= "font-family: verdana, arial, helvetica, sans-serif;\n";
$disHTML .= "font-size: 10px;\n";
$disHTML .= "font-weight: bold;\n";
$disHTML .= "padding: 2px;\n";
$disHTML .= "border: 1px solid #B5B5B5;\n";
$disHTML .= "border-style: solid solid solid solid;\n";
$disHTML .= "color: #000000;\n";
$disHTML .= "background: #E7EFF5;\n";
$disHTML .= "}\n";
$disHTML .= "td.fsub_border {\n";
$disHTML .= "border: 1px solid #B5B5B5;\n";
$disHTML .= "border-style: none none solid solid;\n";
$disHTML .= "}\n";
$disHTML .= "td.fsub_border1 {\n";
$disHTML .= "border: 1px solid #B5B5B5;\n";
$disHTML .= "border-style: none solid solid none;\n";
$disHTML .= "}\n";
$disHTML .= ".tboxjoe {\n";
$disHTML .= "  font-family: verdana, arial, helvetica, sans-serif;\n";
$disHTML .= "  font-size: 10px;\n";
$disHTML .= "  background-color: #CCCCCC;\n";
$disHTML .= "  border: #000000;\n";
$disHTML .= "  border-style: solid;\n";
$disHTML .= "  border-top-width: thin;\n";
$disHTML .= "  border-right-width: thin;\n";
$disHTML .= "  border-bottom-width: thin;\n";
$disHTML .= "  border-left-width: thin;\n";
$disHTML .= "  height: 100px;\n";
$disHTML .= "  width: 500px;\n";
$disHTML .= "}\n";
$disHTML .= ".style9 {\n";
$disHTML .= "  font-family: Arial;\n";
$disHTML .= "  font-weight: bold;\n";
$disHTML .= "  font-size: smaller; color: orange;\n";
$disHTML .= "}\n";
$disHTML .= ".style13 {font-size: 12px; font-weight: bold; }\n";
$disHTML .= ".style14 {font-size: 9pt}\n";

$disHTML .= ".slideshow {\n";
$disHTML .= "  list-style-type: none;\n";
$disHTML .= "  margin: 0px;\n";
$disHTML .= "  padding: 0px;\n";
$disHTML .= "}\n";
$disHTML .= "\n";
$disHTML .= ".slide {\n";
$disHTML .= "  position: relative;\n";
$disHTML .= "  float: left;\n";
$disHTML .= "  width: 172px;\n";
$disHTML .= "  margin-bottom: 10px;\n";
$disHTML .= "  margin-right: 10px;\n";
$disHTML .= "}\n";
$disHTML .= "\n";
$disHTML .= ".slide div.thumb {\n";
$disHTML .= "  background: #fff;\n";
$disHTML .= "  width: 170px;\n";
$disHTML .= "  height: 120px;\n";
$disHTML .= "  border: 1px solid #000;\n";
$disHTML .= "  font-size: 5px;\n";
$disHTML .= "  font-family: \"Times New Roman\", serif;\n";
$disHTML .= "  overflow: hidden;\n";
$disHTML .= "}\n";
$disHTML .= "\n";
$disHTML .= ".slide .view {\n";
$disHTML .= "  padding: 2px 2px;\n";
$disHTML .= "  margin: 2px 0px;\n";
$disHTML .= "  cursor: text;\n";
$disHTML .= "  border-width: 1px;\n";
$disHTML .= "  border-style: solid;\n";
$disHTML .= "  border-color: #ccc;\n";
$disHTML .= "  background-color: #eee;\n";
$disHTML .= "  height: 1em;\n";
$disHTML .= "}\n";
$disHTML .= ".view:hover {\n";
$disHTML .= "  background-color: #ffffcc;\n";
$disHTML .= "}\n";
$disHTML .= ".view, .inplace, #list5 input {\n";
$disHTML .= "  font-size: 14px;\n";
$disHTML .= "  font-family: sans-serif;\n";
$disHTML .= "}\n";
$disHTML .= "\n";
$disHTML .= ".inplace {\n";
$disHTML .= "  position: absolute;\n";
$disHTML .= "  visibility: hidden;\n";
$disHTML .= "  z-index: 10000;\n";
$disHTML .= "  font: 10px verdana;\n";
$disHTML .= "  width: 150px;\n";
$disHTML .= "}\n";
$disHTML .= ".inplace, #list5 input:hover, #list5 input:focus {\n";
$disHTML .= "  background-color: #ffffcc;\n";
$disHTML .= "}\n";
$disHTML .= "#slideEditors input.inplace {\n";
$disHTML .= "  width: 12em;\n";
$disHTML .= "  max-width: 12em;\n";
$disHTML .= "  margin-left: 1px;\n";
$disHTML .= "}\n";
$disHTML .= "#slideEditors input.inplace, #slideshow .view {\n";
$disHTML .= "  text-align: center;\n";
$disHTML .= "}\n";
$disHTML .= "\n";
$disHTML .= "#paragraphView, #paragraphEdit, #markupView, #markupEdit {\n";
$disHTML .= "  font-family: \"Times New Roman\", serif;\n";
$disHTML .= "  font-size: 14px;\n";
$disHTML .= "}\n";
$disHTML .= "#paragraphView, #markupView {\n";
$disHTML .= "  border: 1px solid #fff;\n";
$disHTML .= "  padding: 8px;\n";
$disHTML .= "  width: 400px;\n";
$disHTML .= "  max-width: 400px;\n";
$disHTML .= "}\n";
$disHTML .= "#paragraphView:hover, #markupView:hover {\n";
$disHTML .= "  background-color: #ffffcc;\n";
$disHTML .= "  border-color: #ccc;\n";
$disHTML .= "}\n";
$disHTML .= "#paragraphEdit, #markupEdit {\n";
$disHTML .= "  width: 315px;\n";
$disHTML .= "  background-color: #ffffcc;\n";
$disHTML .= "}\n";
$disHTML .= "#paragraphEdit {\n";
$disHTML .= "  height: 5em;\n";
$disHTML .= "}\n";
$disHTML .= "#markupEdit {\n";
$disHTML .= "  height: 15em;\n";
$disHTML .= "}\n";
$disHTML .= "\n";
$disHTML .= "#listExamples td {\n";
$disHTML .= "  width: 9em;\n";
$disHTML .= "  margin-right: 20px; \n";
$disHTML .= "  padding: 0px 20px;\n";
$disHTML .= "  vertical-align: top;\n";
$disHTML .= "}\n";
$disHTML .= "#listExamples th {\n";
$disHTML .= "  vertical-align: bottom;\n";
$disHTML .= "  font-weight: normal;\n";
$disHTML .= "  font-size: 14px;\n";
$disHTML .= "  padding-top: 20px;\n";
$disHTML .= "}\n";
$disHTML .= "#listExamples td.caption {\n";
$disHTML .= "  font-size: 12px;\n";
$disHTML .= "  text-align: center;\n";
$disHTML .= "}\n";
$disHTML .= "#listExamples li {\n";
$disHTML .= "  padding: 0px;\n";
$disHTML .= "  height: 20px;\n";
$disHTML .= "  min-height: 1em;\n";
$disHTML .= "  width: 120px;\n";
$disHTML .= "}\n";
$disHTML .= "#listExamples li .view {\n";
$disHTML .= "  height: 16px;\n";
$disHTML .= "  vertical-align: middle;\n";
$disHTML .= "  padding: 2px;\n";
$disHTML .= "}\n";
$disHTML .= "#list1 li:hover {\n";
$disHTML .= "  background-color: #eee;\n";
$disHTML .= "}\n";
$disHTML .= "#listExamples input.inplace {\n";
$disHTML .= "  width: 220px;\n";
$disHTML .= "  max-width: 120px;\n";
$disHTML .= "}\n";
$disHTML .= "\n";
$disHTML .= "/* BugFix: Firefox: avoid bottom margin on draggable elements */\n";
$disHTML .= "#listExamples #list4, #listExamples #list5 { margin-top: -2px; }\n";
$disHTML .= "#listExamples #list4 li, #listExamples #list5 li { margin-top: 4px; }\n";
$disHTML .= "\n";
$disHTML .= "#listExamples #list4 li { cursor: default; }\n";
$disHTML .= "#listExamples #list4 .handle,\n";
$disHTML .= "#listExamples #list5 .handle {\n";
$disHTML .= "  float: right;\n";
$disHTML .= "  background-color: #ccc;\n";
$disHTML .= "  background-image: url(common/handle.png);\n";
$disHTML .= "  background-repeat: repeat-y;\n";
$disHTML .= "  width: 7px;\n";
$disHTML .= "  height: 20px;\n";
$disHTML .= "}\n";
$disHTML .= "#listExamples #list4 li .view {\n";
$disHTML .= "  cursor: text;\n";
$disHTML .= "}\n";
$disHTML .= "#listExamples #list4Editors input.inplace, #listExamples #list5 input {\n";
$disHTML .= "  width: 104px;\n";
$disHTML .= "  max-width: 104px;\n";
$disHTML .= "}\n";
$disHTML .= "#listExamples #list4Editors>input.inplace, #listExamples #list5>li>input {\n";
$disHTML .= "  width: 111px;\n";
$disHTML .= "  max-width: 111px;\n";
$disHTML .= "}\n";
$disHTML .= "#list5 input {\n";
$disHTML .= "  background-color: #eee;\n";
$disHTML .= "}\n";
$disHTML .= ".inplace, #list5 input {\n";
$disHTML .= "  background-color: #fff;\n";
$disHTML .= "  margin: 0px;\n";
$disHTML .= "  padding-left: 1px;\n";
$disHTML .= "}\n";
$disHTML .= ".handle {\n";
$disHTML .= "  cursor: move;\n";
$disHTML .= "}\n";

$disHTML .= "-->\n";
$disHTML .= "</style>\n";


$disHTML .= "<table width=\"100%\"  border=\"0\" cellpadding=\"4\" cellspacing=\"0\">\n";
$disHTML .= " <tr>\n";
$disHTML .= "  <td>\n";
$disHTML .= "   <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"4\" cellspacing=\"0\">\n";
$disHTML .= "    <tr>\n";
$disHTML .= "     <td height=\"81\" valign=\"top\" align=\"center\" class=\"style9\">\n";
$disHTML .= "      <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">\n";
$disHTML .= "       <tr>\n";

/*---------------------------------------------------------------------------------------------------------*
  ___                  _           ___        _
 / __| _ _  ___  __ _ | |_  ___   / __| __ _ | |_  ___  __ _  ___  _ _  _  _
| (__ | '_|/ -_)/ _` ||  _|/ -_) | (__ / _` ||  _|/ -_)/ _` |/ _ \| '_|| || |
 \___||_|  \___|\__,_| \__|\___|  \___|\__,_| \__|\___|\__, |\___/|_|   \_, |
                                                       |___/            |__/

# Create a new FAQ Category
/*---------------------------------------------------------------------------------------------------------*/
$disHTML .= "        <td width=\"50%\" align=\"center\" valign=\"top\">";
$disHTML .= "         <table width=\"100%\" border=\"0\" cellpadding=\"4\" cellspacing=\"0\" class=\"feature_sub\">\n";
$disHTML .= "          <tr>\n";
$disHTML .= "           <td colspan=\"2\" class=\"fsub_title\">".lang("Create an FAQ Category")."</td>\n";
$disHTML .= "          </tr>\n";
$disHTML .= "          <tr>\n";
$disHTML .= "           <td align=\"right\" valign=\"top\" style=\"padding-top: 14px;\">\n";
$disHTML .= "            <strong>".lang("Category Name").":</strong>\n";
$disHTML .= "           </td>\n";
$disHTML .= "           <td align=\"left\" valign=\"bottom\" style=\"padding-top: 11px;\">\n";
$disHTML .= "            <form name=\"newcat\" method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">\n";
$disHTML .= "             <input type=\"hidden\" name=\"ball\" value=\"newcats\">\n";
$disHTML .= "             <input type=\"text\" name=\"CAT_NAME\" style=\"font-family: Tahoma; font-size: 8pt; width: 200px;\">\n";
$disHTML .= "            </form>\n";
$disHTML .= "           </td>\n";
$disHTML .= "          </tr>\n";
$disHTML .= "          <tr>\n";
$disHTML .= "           <td colspan=\"2\" align=\"center\" valign=\"top\" style=\"padding-bottom: 15px;\">\n";
$disHTML .= "            <button name=\"Submit\" type=\"button\" class=\"greenButton\" onclick=\"window.document.newcat.submit();\"><span><span>".lang("Add FAQ Category")."</span></span></button><font color=\"#E67300\"><br>$err_cat</font>\n";
$disHTML .= "           </td>\n";
$disHTML .= "          </tr>\n";
$disHTML .= "         </table>\n";
$disHTML .= "        </td>\n";


/*---------------------------------------------------------------------------------------------------------*
  ___        _                                _     _      _
 / __| __ _ | |_  ___  __ _  ___  _ _  _  _  | |   (_) ___| |_
| (__ / _` ||  _|/ -_)/ _` |/ _ \| '_|| || | | |__ | |(_-<|  _|
 \___|\__,_| \__|\___|\__, |\___/|_|   \_, | |____||_|/__/ \__|
                      |___/            |__/

# Current FAQ Categories
/*---------------------------------------------------------------------------------------------------------*/
$disHTML .= "        <td width=\"50%\" align=\"center\" valign=\"top\">";
$disHTML .= "         <form name=\"saveCatName\" method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">\n";
$disHTML .= "          <table width=\"100%\" border=\"0\" cellpadding=\"4\" cellspacing=\"0\" class=\"feature_sub\">\n";
$disHTML .= "           <tr>\n";
$disHTML .= "            <td colspan=\"3\" class=\"fsub_title\">\n";
$disHTML .= "             Current FAQ Categories <span class=\"unbold\">(".lang("Click name to edit").".)</span>\n";
$disHTML .= "             <input type=\"hidden\" name=\"ball\" value=\"saveCatName\">\n";
$disHTML .= "            </td>\n";
$disHTML .= "           </tr>\n";
//$disHTML .= "           <tr>\n";
//$disHTML .= "            <td colspan=\"3\">\n";
//$disHTML .= "             Click a category name to edit.\n";
//$disHTML .= "            </td>\n";
//$disHTML .= "           </tr>\n";
$disHTML .= "           <tr>\n";
$disHTML .= "            <td width=\"50%\" align=\"center\" valign=\"top\">\n";

$disHTML .= "             <table width=\"100%\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"feature_sub\" style=\"border: 1px solid #888888;\">\n";
$disHTML .= "              <tr>\n";
$disHTML .= "               <td height=\"20\" align=\"center\" class=\"fsub_col\" style=\"border-left: none;\">Category</td>\n";
$disHTML .= "               <td width=\"105\" align=\"center\" class=\"fsub_col\">View FAQ's</td>\n";
$disHTML .= "               <td width=\"60\" align=\"center\" class=\"fsub_col\">Delete</td>\n";
$disHTML .= "              </tr>\n";

# Select data from faq_category table
if ( !$rez = mysql_query("SELECT * FROM faq_category ORDER BY prikey DESC") ) {
   echo "".lang("Cannot select from faq table")."<br>";
   echo "".lang("Mysql says").": ".mysql_error();
   exit;
}
$catCount = mysql_num_rows($rez);

# No categories message: only show if no categories exist
if ( $billycat == "0" ) {
   $disHTML .= "              <tr>\n";
   $disHTML .= "               <td align=\"center\" colspan=\"3\"><font color=orange>".lang("There are no categories").".</td>\n";
   $disHTML .= "              </tr>\n";
}

$j=1;
while ( $gbak = mysql_fetch_array($rez) ) {
   if ( $j == 1 ) { $k = "a"; }
   if ( $j == 2 ) { $k = "b"; }
   if ( $j == 3 ) { $k = "c"; }
   if ( $j == 4 ) { $k = "d"; }
   if ( $j == 5 ) { $k = "e"; }
   if ( $j == 6 ) { $k = "f"; }

   # Applies to each <td> in category row
   $rowStyle = "border-bottom: 1px solid #CCCCCC;";

   # Currently viewing this category? If so, highlight.
   if ( $show_cat == $gbak['prikey'] ) { $rowStyle .= "background-color: #FFFAB2;"; }

   $disHTML .= "              <tr>\n";
   $disHTML .= "               <input type=\"hidden\" name=\"".$k."3EditORG\" value=\"".$gbak['CAT_NAME']."\">\n";
   $disHTML .= "               <input id=\"".$k."3Edit\" name=\"".$k."3Edit\" class=\"inplace\">\n";
   $disHTML .= "               <td class=\"text\" align=\"left\" style=\"".$rowStyle."\"><div id=\"".$k."3View\" class=\"view text\">".$gbak['CAT_NAME']."</div></td>\n";
   $disHTML .= "               <td align=\"center\" style=\"".$rowStyle."\"><a href=\"faq_manager.php?show_cat=".$gbak['prikey']."\"><img src=\"".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/includes/display_elements/graphics/preview_icon.gif\" width=\"17\" height=\"13\" border=\"0\"></a></td>\n";
   $disHTML .= "               <td align=\"center\" style=\"".$rowStyle."\"><a href=\"faq_manager.php?do=kill&show_cat=".$gbak['prikey']."\"><img src=\"".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/includes/display_elements/graphics/img-del_faq_cat.gif\" width=\"15\" height=\"15\" border=\"0\"></a></td>\n";
   $disHTML .= "              </tr>\n";
   $j++;
}


$disHTML .= "              </table>\n";

# Save Changes button (only appears after in-line category name edit)
$disHTML .= "              <br><button id=\"saveName\" name=\"Submit\" type=\"button\" class=\"greenButton\" onclick=\"window.document.saveCatName.submit();\"><span><span>".lang('Save Changes')."</span></span></button>\n";
$disHTML .= "              </form>\n";

# popup-sort_order
$popup = "";
$popup .= "<p>".lang("Determines the order in which FAQs are sorted relative to their sort number (left-hand column below...editable when editing the FAQ)").".</p>\n";
$popup .= "<p><b>".lang("Note").":</b> ".lang("Your sort order setting affects the way all of your FAQs in all of your categories will display").".\n";
$popup .= "".lang("If you choose Decending here as the FAQ Sort Order, your FAQs will display in descending order both here in this feature module as well as on your actual website")."\n";
$popup .= "".lang("when you drag-and-drop them on a page").".</p>";
$disHTML .= help_popup("popup-sort_order", lang("FAQ Sort Order"), $popup, "left: 10%;");

# FAQ Sort Order:
$disHTML .= "              <table width=\"100%\" border=\"0\" cellpadding=\"4\" cellspacing=\"0\">\n";
$disHTML .= "               <tr>\n";
$disHTML .= "                <td width=\"100%\" align=\"right\">\n";
$disHTML .= "                 <span class=\"help_link\" onclick=\"toggleid('popup-sort_order');\">[?]</span>\n";
$disHTML .= "                 <span class=\"bold\">".lang("FAQ Sort Order").":</span>\n";
$disHTML .= "                </td>\n";
$disHTML .= "                <td>\n";
$disHTML .= "                 <select name=\"sort_order\" id=\"sort_order\" onchange=\"document.location.href='".$_SERVER['PHP_SELF']."?set_sortorder='+this.value+'&show_cat=".$show_cat."';\">\n";
$disHTML .= "                  <option value=\"asc\" selected>".lang("Ascending (default)")."</option>\n";
$disHTML .= "                  <option value=\"desc\">".lang("Descending")."</option>\n";
$disHTML .= "                 </select>\n";
$disHTML .= "                </td>\n";
$disHTML .= "               </tr>\n";
$disHTML .= "              </table>\n";
# Re-select current setting from dropdown
$disHTML .= "              <script type=\"text/javascript\">\n";
$disHTML .= "              document.getElementById('sort_order').value = '".$faqpref->get("sort")."';\n";
$disHTML .= "              </script>\n";

$disHTML .= "             </td>\n";
$disHTML .= "            </tr>\n";
$disHTML .= "           </table>\n";
$disHTML .= "         </td>\n";
$disHTML .= "        </tr>\n";
$disHTML .= "       </table>\n";
$disHTML .= "      </td>\n";
$disHTML .= "     </tr>\n";


/*---------------------------------------------------------------------------------------------------------*
 ___  _    ___    _     _      _
| __|/_\  / _ \  | |   (_) ___| |_
| _|/ _ \| (_) | | |__ | |(_-<|  _|
|_|/_/ \_\\__\_\ |____||_|/__/ \__|

# FAQ's for selected category
/*---------------------------------------------------------------------------------------------------------*/
if ( $show_cat != "" ) {

   $rez = mysql_query("SELECT * FROM faq_category WHERE prikey='$show_cat'");
   $gbak = mysql_fetch_array($rez);

   $qry = "SELECT * FROM faq_content WHERE CAT_NAME='$show_cat' ORDER BY ROUND(SORT_NUM, 3) ".$faqpref->get("sort");

   $rez = mysql_query($qry);

   $billy = mysql_num_rows($rez);

   # Place html for faq list into separate variable so we can determined the $highest_sortnum value from the faq loop
   # and then pass it with the onclick action of the 'Add New' button shown ABOVE the faq list.
   $faqList = "";

   # Show list of faqs in this cat or 'no faqs found' message?
   if ( $billy > 0 ) {
      $faqList .= "        <tr>\n";
      $faqList .= "         <td colspan=\"2\" valign=\"top\">\n";
      $faqList .= "          <table width=\"100%\" border=\"0\" cellpadding=\"8\" cellspacing=\"0\" class=\"text\">\n";
      $faqList .= "           <tr>\n";
      $faqList .= "            <td width=\"30\" align=\"center\" class=\"fsub_col\">#</td>\n";
      $faqList .= "            <td align=\"center\" class=\"fsub_col\">Question <span class=\"unbold\">(".lang("click question text to show/hide answer").")</span></td>\n";
      $faqList .= "            <td width=\"150\" align=\"center\" class=\"fsub_col\">Edit/Delete FAQ</td>\n";
      $faqList .= "           </tr>\n";

      # Loop through FAQs in this category
      while ( $getFaq = mysql_fetch_assoc($rez) ) {

//         # Uncomment for dev use
//         $update_numformat = "UPDATE faq_content SET SORT_NUM = '".sprintf("%05.1f", $getFaq['SORT_NUM'])."' WHERE prikey = '".$getFaq['prikey']."'";
//         if ( !mysql_query($update_numformat) ) { echo mysql_error()."<br><br>"; }

         $faqList .= "           <tr>\n";

         # Sort Number (display in user-friendly format)
         $faqList .= "            <td valign=\"top\" align=\"center\" style=\"border-bottom: 1px dotted #888c8e;\">".sprintf("%.1f", $getFaq['SORT_NUM'])."</td>\n";

         # Will contain first (highest) sort_num fetched in mysql select query (so we can add 1 to it for new FAQs)
		if ( !isset($highest_sortnum) ) { 
			$highest_sortnum = $getFaq['SORT_NUM']; 
		} elseif($getFaq['SORT_NUM'] > $highest_sortnum){
			$highest_sortnum = $getFaq['SORT_NUM']; 
		}

         # Question text & hidden answer text
         $faqList .= "            <td style=\"border-bottom: 1px dotted #888c8e;\">\n";
         $faqList .= "             <b><span class=\"blue hand\" onClick=\"toggleid('answertext_".$getFaq['prikey']."')\">".stripslashes($getFaq['FAQ_QUESTION'])."</span></b><br>\n";
         $faqList .= "             <span style=\"display: none;\" id=\"answertext_".$getFaq['prikey']."\">".stripslashes($getFaq['FAQ_ANSWER'])."</span>\n";
         $faqList .= "            </td>\n";

         # Edit icon/link
         $editlink = "faq_managerED.php?show_cat=".$show_cat."&questid=".$getFaq['prikey']."&do=edit";
         $faqList .= "            <td valign=\"top\" align=\"center\" style=\"border-bottom: 1px dotted #888c8e;\">\n";
         $faqList .= "             <a href=\"".$editlink."\"><img src=\"../includes/display_elements/graphics/preview_icon.gif\" width=\"17\" height=\"13\" border=\"0\"></a>";
         $faqList .= "             [ <a href=\"".$editlink."\">".lang("View/Edit")."</a> ]\n";
         $faqList .= "            </td>\n";
         $faqList .= "          </tr>\n";
         $lala[]=$getFaq;
      }
     // echo testArray($lala);

      $faqList .= "          </table>\n";
      $faqList .= "         </td>\n";
      $faqList .= "        </tr>\n";

   } else {
      # No FAQs found for this category
      $highest_sortnum = 0;
      $faqList .= "        <tr>\n";
      $faqList .= "         <td align=\"center\" valign=\"top\" colspan=\"2\"><font color=orange><br>".lang("There are currently no FAQ's in this category").".<br></td>\n";
      $faqList .= "        </tr>\n";
   }

   # Convert to whole integer (reset decimal for new faq sort number)
   $highest_sortnum = sprintf("%d", $highest_sortnum);
//echo $highest_sortnum; exit;
   # NOW Show top html for table containing $faqList
   $disHTML .= "     <tr>\n";
   $disHTML .= "      <td valign=\"top\">";
   $disHTML .= "       <table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"feature_sub\">\n";
   $disHTML .= "        <tr>\n";
   $disHTML .= "         <td align=\"left\" class=\"fsub_title\">\n";
   $disHTML .= "          ".$gbak['CAT_NAME']."\n";
   $disHTML .= "         </td>\n";

   # [ Add New FAQ ]
   $disHTML .= "         <td align=\"right\" class=\"fsub_title\">\n";
   $disHTML .= "          <button name=\"Submit\" type=\"button\" onclick=\"navto('faq_managerED.php?show_cat=$show_cat&do=add&highest_sortnum=".$highest_sortnum."');\" class=\"greenButton\"><span><span>".lang("Add New FAQ")."</span></span></button></td>\n";
   $disHTML .= "        </tr>\n";
   $disHTML .= $faqList;
   $disHTML .= "       </table>\n";
   $disHTML .= "      </td>\n";
   $disHTML .= "     </tr>\n";

   # Add New FAQ button
   $disHTML .= "     <tr>\n";
   $disHTML .= "      <td colspan=\"3\" align=\"center\">\n";
   $disHTML .= "       <button name=\"Submit\" type=\"button\" onclick=\"navto('faq_managerED.php?show_cat=$show_cat&do=add&highest_sortnum=".$highest_sortnum."');\" class=\"greenButton\"><span><span>".lang("Add New FAQ")."</span></span></button>\n";
   $disHTML .= "      </td>\n";
   $disHTML .= "     </tr>\n";
   $disHTML .= "    </table>\n";
   $disHTML .= "   </td>\n";
   $disHTML .= "  </tr>\n";
   $disHTML .= " </table>\n";

} // End if show_cat != ""

//$disHTML .= "<script language=\"JavaScript\" type=\"text/javascript\" src=\"../includes/display_elements/wz_tooltip.js\"></script>\n";

$disHTML .= "<script language=\"JavaScript\">\n";
$disHTML .= "  join(\"a3\")\n";
//$disHTML .= "  join(\"b3\")\n";
//$disHTML .= "  join(\"c3\")\n";
//$disHTML .= "  join(\"d3\")\n";
//$disHTML .= "  join(\"e3\")\n";
$disHTML .= "</script>\n";


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
$module->meta_title = lang("FAQ Manager");
$module->add_breadcrumb_link(lang("FAQ Manager"), "program/webmaster/faq_manager.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/full_size/faq_manager-enabled.gif";
$module->heading_text = lang("FAQ Manager");
$module->description_text = $instructions;
$module->good_to_go();
?>
