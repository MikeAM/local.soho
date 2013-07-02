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
error_reporting(0);

# Include core interface files
$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../../../../includes/product_gui.php');
chdir($curdir);
#######################################################
### SET MONTHS ARRAY
#######################################################

$MONTHS[1] = "January";
$MONTHS[2] = "February";
$MONTHS[3] = "March";
$MONTHS[4] = "April";
$MONTHS[5] = "May";
$MONTHS[6] = "June";
$MONTHS[7] = "July";
$MONTHS[8] = "August";
$MONTHS[9] = "September";
$MONTHS[10] = "October";
$MONTHS[11] = "November";
$MONTHS[12] = "December";

?>

<HTML>
<HEAD>
<TITLE>Top Referal</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK REL="stylesheet" HREF="../shared/soholaunch.css" TYPE="TEXT/CSS">
<script language="JavaScript">
<!--

window.focus();

function MM_callJS(jsStr) { //v2.0
  return eval(jsStr)
}
//-->
</script>
</HEAD>

<BODY BGCOLOR="#EFEFEF" TEXT="#000000" LINK="#FF0000" VLINK="#FF0000" ALINK="#FF0000" LEFTMARGIN="10" TOPMARGIN="10" MARGINWIDTH="10" MARGINHEIGHT="10">

	<?php

	// First; find out what the first month logged in the system is and let's loop in DESC order
	// Through each month and display all stats to date
	// ------------------------------------------------------------------------------------------

	echo "<H5><FONT FACE=VERDANA><U>".$lang["REFERER SITES"]."</U></FONT></H5>\n";

	$result = mysql_query("SELECT Month, Year, Real_Date FROM stats_refer group by Month UNION SELECT Month, Year, Real_Date FROM stats_refer_archive group by Month ORDER BY Real_Date DESC");

	while($ALL_MONTHS = mysql_fetch_array($result)) {



			$db_result = mysql_query("SELECT * FROM stats_refer WHERE (Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]') AND ( Refer <> '(Internal)' AND Refer <> '(Direct)') UNION SELECT * FROM stats_refer_archive WHERE (Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]') AND ( Refer <> '(Internal)' AND Refer <> '(Direct)') ORDER BY Hits DESC");
			

			$a=1;
			while ($row = mysql_fetch_array($db_result)) {
				$pgNAME[$a] = $row[Refer];
				$pgHITS[$a] = $row[Hits];
				$a++;
			}

			echo "<DIV CLASS=text><B><U>$ALL_MONTHS[Month] $ALL_MONTHS[Year]</U></B></DIV>\n";

			echo "<TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=5 style='border: 1px solid black; background: #708090;' ALIGN=LEFT>
          			<TR>
					<TD ALIGN=CENTER VALIGN=TOP BGCOLOR=#EFEFEF class=text width=100><B># ".lang("Referrals (per)")."</FONT></B></TD>
            		<TD ALIGN=LEFT VALIGN=TOP BGCOLOR=#EFEFEF class=text><B>".lang("Referral Site")."</FONT></B></TD>
          			</TR>\n";

		  	for ($x=1;$x<=$a;$x++) {

				if ($pgNAME[$x] != "") {

					echo ("<TR BGCOLOR=\"WHITE\">\n");
					echo ("<TD ALIGN=\"CENTER\" VALIGN=\"TOP\" class=text>$pgHITS[$x]</TD>\n");
					echo ("<TD ALIGN=\"LEFT\" VALIGN=\"TOP\" class=text><a href=\"$pgNAME[$x]\" target=\"_blank\">$pgNAME[$x]</a></TD>\n");
					echo ("</TR>\n");

				}

		  	}

			echo "</TABLE><BR CLEAR=ALL><BR>\n";

	} // End While

	?>


</BODY>
</HTML>
