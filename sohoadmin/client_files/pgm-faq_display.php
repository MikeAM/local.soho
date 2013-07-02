<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.5
##
## Author: 			Joe Lain [joe.lain@soholaunch.com]
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

# Get misc preferences for FAQ feature
$faqpref = new userdata("faq");

# DEFAULT: Sort faqs in ascending order
if ( $faqpref->get("sort") == "" ) { $faqpref->set("sort", "asc"); }

# Get category id

$result = mysql_query("SELECT PRIKEY FROM faq_category WHERE CAT_NAME = '$FAQ_CATEGORY_NAME'");
$SUBJ_KEY = mysql_fetch_array($result);
$FAQ_CATEGORY_NAME = stripslashes($FAQ_CATEGORY_NAME);

# Pull all FAQ entries for appropriate category from DB - Mantis #0000032
$qry = "SELECT * FROM faq_content WHERE CAT_NAME = '$SUBJ_KEY[PRIKEY]' ORDER BY ROUND(SORT_NUM, 3) ".$faqpref->get("sort");
$result = mysql_query($qry); // Pull entries based on pre-built query (Mantis #0000032)

echo "<script type=\"text/javascript\">\n";
echo "function toggleid(targetid) {\n";
echo "	var isnow = document.getElementById(targetid).style.display;\n";
echo "	if ( isnow == 'block' ) {\n";
echo "		document.getElementById(targetid).style.display='none';\n";
echo "	} else {\n";
echo "		document.getElementById(targetid).style.display='block';\n";
echo "	}\n";
echo "}\n";
echo "</script>\n";

//# Display "F.A.Q - [Category]" heading
//echo "<table align=\"center\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n";
//echo " <tr> \n";
//echo "  <td valign=\"top\" width=\"100%\" bgcolor=#F8F8F8>\n";
//echo "   <font FACE=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#38393A\" style=\"font-size: 13px;\">\n";
//echo "    <b>F.A.Q - </b>".$FAQ_CATEGORY_NAME."\n";
//echo "   </font>\n";
//echo "  </td>\n";
//echo " </tr>\n";
//echo "</table>\n";

if(!isset($count)){
   echo "<a name=\"faq0\"></a>\n";
   $faqNum = 0;
	$count = 0;
}else{
   echo "<a name=\"faq1\"></a>\n";
   $faqNum = 1;
}

while ( $row = mysql_fetch_array($result) ) {
   $row['FAQ_QUESTION'] = eregi_replace("SOHOLINK", "href", $row['FAQ_QUESTION']);

   echo "<table border=0 cellpadding=\"5\" cellspacing=0 width=100% align=center class=\"faqs\">\n";
   echo " <tr>\n";
   echo "  <td align=left valign=top class=text style=\"padding-bottom: 0px;\">\n";
   //echo "   <font face=arial style='font-size: 9pt;' color= \"#000000\">\n";
   //echo "   <a href=\"#faq".$faqNum."\" onClick=\"toggleid('answer$count')\" style=\"text-decoration: none !important;\"><B>".stripslashes($row['FAQ_QUESTION'])."</B></a>\n";
   //echo "   <a href=\"#faq".$faqNum."\" name=\"faq".$faqNum."\" onClick=\"toggleid('answer$count')\" style=\"text-decoration: none !important;\"><B>".stripslashes($row['FAQ_QUESTION'])."</B></a>\n";
   echo "   <a href=\"javascript:void(0)\" name=\"faq".$faqNum."\" onClick=\"toggleid('answer$count')\" style=\"text-decoration: none !important;\"><B>".stripslashes($row['FAQ_QUESTION'])."</B></a>\n";
   echo "  </td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo "  <td align=left valign=top class=text style=\"padding: 0px 5px 10px 5px;\">\n";
 //  echo "   <font style='font-family: Arial; font-size: 8pt; color: #595959;'>\n";
   echo "   <div id=\"answer$count\" style=\"display: none; margin-top: 0px; padding-top: 0px;\">\n";
   echo "    ".stripslashes($row['FAQ_ANSWER'])."</div>\n";
   echo "  </td>\n";
   echo " </tr>\n";
   echo "</table>";
   $faqNum++;
   $count++;

}

?>
