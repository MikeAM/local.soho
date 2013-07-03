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
<TITLE>Unique Visitor Trend</TITLE>
<?php echo "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=UT"."F-8\">\n"; ?>
<LINK REL="stylesheet" HREF="../shared/soholaunch.css" TYPE="TEXT/CSS">
</HEAD>
<BODY BGCOLOR="#FFFFFF" TEXT="#000000" LINK="#FF0000" VLINK="#FF0000" ALINK="#FF0000" LEFTMARGIN="10" TOPMARGIN="10" MARGINWIDTH="10" MARGINHEIGHT="10">

    <?php

	// First; find out what the first month logged in the system is and let's loop in DESC order
	// Through each month and display all stats to date
	// ------------------------------------------------------------------------------------------

	echo "<H5><FONT FACE=VERDANA><U>".lang("UNIQUE VISITOR TREND")."</U></FONT></H5>\n";

	//$result = mysql_query("SELECT SELECT concat(Month, Year) as umonths,Month, Year, Real_Date FROM stats_unique group by Real_Date UNION SELECT Month, Year, Real_Date FROM stats_unique_archive group by Real_Date ORDER BY Real_Date DESC");
	$used=array();
	
	$statqry="SELECT concat(Month, Year) as umonths, Month, Year, Real_Date FROM stats_unique group by umonths";
	if($archive_exists==1){
		$statqry.=" UNION SELECT concat(Month, Year) as umonths, Month, Year, Real_Date FROM stats_unique_archive group by umonths";
	}
	$statqry.=" ORDER BY Real_Date DESC";
	$result = mysql_query($statqry);
	while($ALL_MONTHS = mysql_fetch_array($result)) {
		if(!in_array($ALL_MONTHS['Month'].$ALL_MONTHS['Year'], $used)){
		$used[]=$ALL_MONTHS['Month'].$ALL_MONTHS['Year'];
		
		
		
	      $test_ses = mysql_query("SELECT PriKey, SESSION, Real_Date, Hour FROM stats_unique WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' AND SESSION = ''");
	      while ( $joetest = mysql_fetch_assoc($test_ses) ){
			$new_ses = rand(50000,1000000);
			mysql_query("UPDATE stats_unique SET SESSION = '$new_ses' WHERE PriKey = '$joetest[PriKey]'");
	      }
		if($archive_exists==1){
			$test_ses = mysql_query("SELECT PriKey, SESSION, Real_Date, Hour FROM stats_unique_archive WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' AND SESSION = ''");
		      while ( $joetest = mysql_fetch_assoc($test_ses) ){
				$new_ses = rand(50000,1000000);
				mysql_query("UPDATE stats_unique_archive SET SESSION = '$new_ses' WHERE PriKey = '$joetest[PriKey]'");
		      }
		}
		
		
//	      $test_ses = mysql_query("SELECT PriKey, SESSION, Real_Date, Hour FROM stats_unique WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' AND SESSION = '' UNION SELECT PriKey, SESSION, Real_Date, Hour FROM stats_unique_archive WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' AND SESSION = ''");
//
//	      while ( $joetest = mysql_fetch_array($test_ses) )
//	      {
//            $new_ses = rand(50000,1000000);
//	         mysql_query("UPDATE stats_unique SET SESSION = '$new_ses' WHERE PriKey = '$joetest[PriKey]'");
//	      }

//			$db_result = mysql_query("SELECT PriKey,SESSION FROM stats_unique WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' group by SESSION UNION SELECT SESSION FROM stats_unique_archive WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' group by SESSION ");
//			$nUNIQUE = mysql_num_rows($db_result);			// Number of Unique Visitors
			
			$statqry4="SELECT Hits, PriKey FROM stats_unique WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]'";
			if($archive_exists==1){
				$statqry4.=" UNION SELECT Hits, PriKey FROM stats_unique_archive WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]'";
			}
			$db_result = mysql_query($statqry4);
			$tHITS = 0;											// Calculate Total Page Views
			while ($row = mysql_fetch_assoc($db_result)) {
				$tHITS = $tHITS + $row['Hits'];
			}

			$statqry3="SELECT SESSION, IP, PriKey FROM stats_unique WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' group by IP";
			if($archive_exists==1){
				$statqry3 .=" UNION SELECT SESSION, IP, PriKey FROM stats_unique_archive WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' group by IP";
			}
			$db_result = mysql_query($statqry3);
			$nUNIQUE = mysql_num_rows($db_result);				// Number of Unique Visitors


			$avgPV = $tHITS/$nUNIQUE;							// Calculate Average Num Pages Viewed Per Visit
			$avgPV = floor($avgPV);

			$statqry5="SELECT IP, PriKey, SESSION FROM stats_unique WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' group by SESSION";
			if($archive_exists==1){
				$statqry5.=" UNION SELECT IP, PriKey, SESSION FROM stats_unique_archive WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' group by SESSION";
			}
			$db_result = mysql_query($statqry5);
			$tmp_num = mysql_num_rows($db_result);				// Number of Unique Visitors that visited more than once in a day


			$freqPV = $tmp_num/$nUNIQUE;						// Calculate visitor frequency (Avg time a single user visits in a day)
			$freqPV = sprintf ("%01.2f", $freqPV);

			  echo "<DIV CLASS=text><B><U>$ALL_MONTHS[Month] $ALL_MONTHS[Year]</U></B></DIV><TABLE WIDTH=\"100%\" BORDER=\"0\" CELLSPACING=\"1\" CELLPADDING=\"5\" CLASS=text STYLE='border: 1px solid black; background: #708090;' ALIGN=CENTER>
					<TR BGCOLOR=\"#EFEFEF\">
					<TD ALIGN=\"CENTER\" VALIGN=\"TOP\"><B>".lang("Total Unique Visitors")."</B></TD>
					<TD ALIGN=\"CENTER\" VALIGN=\"TOP\" WIDTH=\"25%\"><B>".lang("Total Page Views")."</B></TD>
					<TD ALIGN=\"CENTER\" VALIGN=\"TOP\" WIDTH=\"25%\"><B>".lang("Visit Frequency")."</B></TD>
					<TD ALIGN=\"CENTER\" VALIGN=\"TOP\" WIDTH=\"25%\"><B>".lang("Avg Pages Per Visit")."</B></TD>
					</TR>
					<TR BGCOLOR=\"#FFFFFF\">
					<TD ALIGN=\"CENTER\" VALIGN=\"TOP\" WIDTH=\"25%\">".number_format($nUNIQUE)."</TD>
					<TD ALIGN=\"CENTER\" VALIGN=\"TOP\">".number_format($tHITS)."</TD>
					<TD ALIGN=\"CENTER\" VALIGN=\"TOP\">".$freqPV."</TD>
					<TD ALIGN=\"CENTER\" VALIGN=\"TOP\">".number_format($avgPV)."</TD>
					</TR>
					</TABLE><BR CLEAR=ALL><BR>\n";

		} // End Each Month Loop
	}

	?>
</BODY>
</HTML>
