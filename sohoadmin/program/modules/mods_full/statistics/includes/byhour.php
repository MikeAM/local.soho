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
<TITLE>Page Views by Hour</TITLE>
<?php echo "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=UT"."F-8\">\n"; ?>
<LINK REL="stylesheet" HREF="../shared/soholaunch.css" TYPE="TEXT/CSS">
<script language="JavaScript">


window.focus();

function MM_callJS(jsStr) { //v2.0
  return eval(jsStr)
}

function show_num(M,d,hit) {
	if (hit == "") { hit = 0; }
	displayvar = M+"_DNUM";
	document.getElementById(displayvar).innerHTML = "Page Views for "+M+" "+d+": "+hit;
}


</script>
</HEAD>

<BODY BGCOLOR="#FFFFFF" TEXT="#000000" LINK="#FF0000" VLINK="#FF0000" ALINK="#FF0000" LEFTMARGIN="10" TOPMARGIN="10" MARGINWIDTH="10" MARGINHEIGHT="10">
<?php

	// First; find out what the first month logged in the system is and let's loop in DESC order
	// Through each month and display all stats to date
	// ------------------------------------------------------------------------------------------

	echo "<H5><FONT FACE=VERDANA><U>".$lang["PAGE VIEWS BY HOUR"]."</U></FONT></H5>\n";
	$used=array();
	//$result = mysql_query("SELECT DISTINCT Month, Year FROM stats_byhour ORDER BY Real_Date DESC");
	$result = mysql_query("SELECT Month, Year, Real_Date FROM stats_byhour GROUP by Real_Date UNION SELECT Month, Year, Real_Date FROM stats_byhour_archive GROUP by Real_Date ORDER BY Real_Date DESC");

	while($ALL_MONTHS = mysql_fetch_assoc($result)) {
		if(!in_array($ALL_MONTHS['Month'].$ALL_MONTHS['Year'], $used)){
			$used[]=$ALL_MONTHS['Month'].$ALL_MONTHS['Year'];
			$db_result = mysql_query("SELECT * FROM stats_byhour WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' UNION SELECT * FROM stats_byhour_archive WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]'");
//			$db_result = mysql_query("SELECT Month, Year, SUM(Hits) FROM stats_byhour WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' AND Hour = '$x_hr'");

			$tHITS = 0;	// Calculate Total Page Views
			$large_num = 0; // Calculate Largest Hour Total
			$thehours = '';
			$thehours = array();

			while ($row = mysql_fetch_assoc($db_result)) {
				$thishour = $row['Hour'];
				$thehours[$thishour] = $row['Hits'] + $thehours[$thishour];
				$row['Hits'] = $thehours[$thishour];
				if ($vMON == "") {
					$tmp = split("-", $row[Real_Date]);
					$vMON = $tmp[1];
					$vYEAR = $tmp[0];
				}

				$tHITS = $tHITS + $row[Hits];
				if ($row[Hits] > $large_num) { $large_num = $row[Hits]; $active_hour = $row[Hour]; }

			}

			$quick_disp = date("g:i A", mktime($active_hour,0,0,$vMON,1,$vYEAR));
			echo ("<DIV CLASS=text><B><U>".$ALL_MONTHS['Month']." ".$ALL_MONTHS['Year']."</U></B><BR>".$lang["Most active hour of the day"].":  $quick_disp ($large_num ".$lang["Page Views"].")</DIV>\n");


        	echo "<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=3 STYLE='border: 1px solid black; background: #708090;' ALIGN=LEFT>\n";

			echo ("<TR>\n");
		 	echo ("<TD ALIGN=\"RIGHT\" VALIGN=\"TOP\" class=smtext bgcolor=#EFEFEF>$large_num -\n");

		 	for ($x=0;$x<=23;$x++) {

				$x_hr = date("H", mktime($x,5,1,$vMON,1,$vYEAR));
				if ($x_hr == "") { $x_hr = "00"; }

				// echo "<font color=red>$x_hr</font><BR>";

				$db_result = mysql_query("SELECT * FROM stats_byhour WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' AND Hour = '$x_hr' UNION SELECT * FROM stats_byhour_archive WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' AND Hour = '$x_hr'");
				$thehits = 0;
				while($row = mysql_fetch_assoc($db_result)){
					$thehits = $row['Hits'] + $thehits;
				}
				$row['Hits'] = $thehits;
				$tmp = mysql_num_rows($db_result);

				// echo "<!-- $x_hr:$row[Hour] --- $tmp -->\n\n";

				$act_num[$x] = $row[Hits];

				$tmp_calc = $row[Hits]/$large_num;
				$lWIDTH = $tmp_calc*100;
				$lWIDTH = ceil($lWIDTH);

				$line_chart = "<table border=0 cellpadding=0 cellspacing=0 class=allBorder width=100% height=$lWIDTH align=center><tr><td class=htext bgcolor=darkgreen> </td></tr></table>";

				$d = $row[Hits];
				if ($d == "") { $d = "0"; }

	            echo ("<TD ROWSPAN=2 ALIGN=\"center\" VALIGN=\"bottom\" class=htext width=10 bgcolor=white>$line_chart</TD>\n");

			} // End header Calling 24 Hour Chart Display


			echo ("</TR>\n");

		 	echo ("<TR><TD ALIGN=\"RIGHT\" VALIGN=\"bottom\" class=smtext bgcolor=#EFEFEF>0 -</TD></TR>\n");

			echo ("<TR>\n");
			echo ("<TD bgcolor=#EFEFEF class=smtext>Hour of Day</td>\n");

		 	for ($x=0;$x<=23;$x++) {

					if ($x >= 12) {
						$x_disp = date("g-a", mktime($x,0,0,$vMON,1,$vYEAR));
					} else {
						$x_disp = date("g~a", mktime($x,0,0,$vMON,1,$vYEAR));
					}

					$java_disp = date("gA", mktime($x,0,0,$vMON,1,$vYEAR));

					$x_disp = str_replace("-", "<BR><FONT COLOR=darkblue>", $x_disp);
					$x_disp = str_replace("~", "<BR><FONT COLOR=red>", $x_disp);

					echo ("<TD ALIGN=\"CENTER\" VALIGN=\"TOP\" class=smtext style='background: #EFEFEF; cursor: pointer'; onmouseover=\"show_num('".$ALL_MONTHS['Month']."','".$java_disp."','".$act_num[$x]."');\">".$x_disp."</TD>\n");
				}

			echo ("</TR>\n");
			echo "</TABLE><BR CLEAR=ALL>\n";
			echo "<DIV align=left class=text style='padding: 5px;'>\n";
			echo " [ <SPAN id=".$ALL_MONTHS[Month]."_DNUM>".$lang["Mouseover a Selected Hour for actual total"]."</SPAN> ]\n";
			echo "</DIV>\n";
			echo "</TABLE><BR CLEAR=ALL><BR>\n";

	
		}
	} // End While Month Loop

?>

</BODY>
</HTML>