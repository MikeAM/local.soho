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
<TITLE>Top 25 Site Pages</TITLE>
<?php echo "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=UT"."F-8\">\n"; ?>
<LINK REL="stylesheet" HREF="../shared/soholaunch.css" TYPE="TEXT/CSS">
</HEAD>
<BODY BGCOLOR="#FFFFFF" TEXT="#000000" LINK="#FF0000" VLINK="#FF0000" ALINK="#FF0000" LEFTMARGIN="10" TOPMARGIN="10" MARGINWIDTH="10" MARGINHEIGHT="10">

	<?php

	echo "<H5><FONT FACE=VERDANA><U>".$lang["TOP 25 SITE PAGES/SITE MODULES"]."</U></FONT></H5>\n";

	// First; find out what the first month logged in the system is and let's loop in DESC order
	// Through each month and display all stats to date
	// ------------------------------------------------------------------------------------------
	$used=array();

	$result = mysql_query("SELECT Month, Year, Real_Date FROM stats_top25 GROUP BY Real_Date UNION SELECT Month, Year, Real_Date FROM stats_top25_archive GROUP BY Month ORDER BY Real_Date DESC");

	while($ALL_MONTHS = mysql_fetch_array($result)) {
		if(!in_array($ALL_MONTHS['Month'].$ALL_MONTHS['Year'], $used)){
			$used[]=$ALL_MONTHS['Month'].$ALL_MONTHS['Year'];

			$db_result = mysql_query("SELECT * FROM stats_top25 WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' UNION SELECT * FROM stats_top25_archive WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' ORDER BY Hits DESC");


			$a=1;
			while ($row = mysql_fetch_array($db_result)) {
				$pgNAME[$a] = $row[Page];
				$pgHITS[$a] = $row[Hits];
				$a++;
			}




			echo "<table cellpadding=0 cellspacing=0 align=center width=100%><tr><td align=left valign=top colspan=2>\n";
				echo "<div class=text><B><U>$ALL_MONTHS[Month] $ALL_MONTHS[Year]</U></B></div>\n";
			echo "</td></tr><tr><td align=center valign=top>\n";

						echo "<TABLE WIDTH=100% BORDER=\"0\" CELLSPACING=\"1\" CELLPADDING=\"5\" class=text STYLE='border: 1px solid black;' BGCOLOR=#708090 ALIGN=\"CENTER\">
								<TR>
								<TD ALIGN=\"CENTER\" VALIGN=\"MIDDLE\" BGCOLOR=\"#EFEFEF\"><B>".$lang["Rank"]."</FONT></B></TD>
								<TD ALIGN=\"CENTER\" VALIGN=\"MIDDLE\" BGCOLOR=\"#EFEFEF\"><B>".$lang["Page Name"]."</FONT></B></TD>
								<TD ALIGN=\"CENTER\" VALIGN=\"MIDDLE\" BGCOLOR=\"#EFEFEF\"><B>".$lang["Page Views"]."</FONT></B></TD>
								</TR>\n";

						$color = "white";

						for ($x=1;$x<=13;$x++) {

							if ($pgNAME[$x] != "" && !eregi("https?://", $pgNAME[$x])) {
								echo ("<TR BGCOLOR=\"$color\">\n");
								echo ("<TD ALIGN=\"CENTER\" VALIGN=\"TOP\"># $x</TD>\n");
								echo ("<TD ALIGN=\"LEFT\" VALIGN=\"TOP\">$pgNAME[$x]</TD>\n");
								echo ("<TD ALIGN=\"LEFT\" VALIGN=\"TOP\">$pgHITS[$x]</TD>\n");
								echo ("</TR>\n");
							}

						}	// End For $x

						echo "</TABLE>\n";

			echo ("<td><td align=center valign=top style='width: 50%;'>\n");

						if ($x < $a) {

								echo "<TABLE WIDTH=100% BORDER=\"0\" CELLSPACING=\"1\" CELLPADDING=\"5\" class=text STYLE='border: 1px solid black;' bgcolor=#708090 ALIGN=\"CENTER\">
										<TR>
										<TD ALIGN=\"CENTER\" VALIGN=\"MIDDLE\" BGCOLOR=\"#EFEFEF\"><B>Rank</FONT></B></TD>
										<TD ALIGN=\"CENTER\" VALIGN=\"MIDDLE\" BGCOLOR=\"#EFEFEF\"><B>Page Name</FONT></B></TD>
										<TD ALIGN=\"CENTER\" VALIGN=\"MIDDLE\" BGCOLOR=\"#EFEFEF\"><B>Page Views</FONT></B></TD>
										</TR>\n";

								$color = "white";

								for ($x=14;$x<=26;$x++) {

									if ($pgNAME[$x] != "" && !eregi("https?://", $pgNAME[$x])) {
										echo ("<TR BGCOLOR=\"$color\">\n");
										echo ("<TD ALIGN=\"CENTER\" VALIGN=\"TOP\"># $x</TD>\n");
										echo ("<TD ALIGN=\"LEFT\" VALIGN=\"TOP\">$pgNAME[$x]</TD>\n");
										echo ("<TD ALIGN=\"LEFT\" VALIGN=\"TOP\">$pgHITS[$x]</TD>\n");
										echo ("</TR>\n");
									}

								} // End $x

						} else {		// End already complete check

								echo "&nbsp;</td></tr>\n";

						}

						echo "</TABLE>\n\n";

			echo "</TD></TR></TABLE><BR CLEAR=ALL><BR>\n";

	
		}
	} // END MONTH LOOP (WHILE)

	?>

</BODY>
</HTML>
