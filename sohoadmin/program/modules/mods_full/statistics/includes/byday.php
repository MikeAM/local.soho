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

$archive_exists=0;
if(table_exists('stats_byday_archive')){
	$archive_exists=1;
}
?>

<HTML>
<HEAD>
<TITLE>Page Views by Day</TITLE>
<?php echo "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=UT"."F-8\">\n"; ?>
<LINK REL="stylesheet" HREF="../shared/soholaunch.css" TYPE="TEXT/CSS">
<script language="JavaScript">

window.focus();

function MM_callJS(jsStr) { //v2.0
  return eval(jsStr)
}

function show_num(MID,M,d,hit) {
	if (hit == "") { hit = 0; }
	displayvar = MID+"_DNUM";
	document.getElementById(displayvar).innerHTML = "Page Views for "+M+" "+d+": "+hit;
}

</script>
</HEAD>

<BODY BGCOLOR="#FFFFFF" TEXT="#000000" LINK="#FF0000" VLINK="#FF0000" ALINK="#FF0000" LEFTMARGIN="10" TOPMARGIN="10" MARGINWIDTH="10" MARGINHEIGHT="10">

	<?php

	// First; find out what the first month logged in the system is and let's loop in DESC order
	// Through each month and display all stats to date
	// ------------------------------------------------------------------------------------------

	echo "<H5><FONT FACE=VERDANA><U>".$lang["PAGE VIEWS BY DAY"]."</U></FONT></H5>\n";

	$result = mysql_query("SELECT Month, Year, Real_Date FROM stats_byday GROUP BY Real_Date UNION SELECT Month, Year, Real_Date FROM stats_byday_archive GROUP BY Real_Date ORDER BY Real_Date DESC");
	$used=array();
	while($ALL_MONTHS = mysql_fetch_array($result)) {
		if(!in_array($ALL_MONTHS['Month'].$ALL_MONTHS['Year'], $used)){
			$used[]=$ALL_MONTHS['Month'].$ALL_MONTHS['Year'];
			
				$statqry21="SELECT * FROM stats_byday WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]'";
				if($archive_exists==1){
					$statqry21.=" UNION SELECT * FROM stats_byday_archive WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]'";	
				}
				$statqry21 .= "  order by Hits DESC limit 1";
				$db_result21 = mysql_query($statqry21);
				$month_ciel_ar=mysql_fetch_assoc($db_result21);
				$month_ciel=$month_ciel_ar['Hits'];
			
					$db_result = mysql_query("SELECT * FROM stats_byday WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' UNION SELECT * FROM stats_byday_archive WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]'");

					$tHITS = 0;											// Calculate Total Page Views
					while ($row = mysql_fetch_array($db_result)) {
						$tHITS = $tHITS + $row[Hits];
					}

					echo "<DIV CLASS=text><B>".$lang["Total Page Views for"]." <U>$ALL_MONTHS[Month] $ALL_MONTHS[Year]</U> = [ $tHITS ]</B><BR>\n";
					echo $lang["Page Views Per Day Totals"].":</DIV>\n";

					echo "<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=3 STYLE='BORDER: 1px SOLID BLACK;' ALIGN=LEFT BGCOLOR=#708090>\n";


					echo ("<TR>\n");
					//echo ("<TD ALIGN=\"RIGHT\" VALIGN=\"TOP\" class=smtext bgcolor=#EFEFEF>$tHITS -\n");
					echo ("<TD ALIGN=\"RIGHT\" VALIGN=\"TOP\" class=smtext bgcolor=#EFEFEF>$month_ciel -\n");

					for ($x=1;$x<=31;$x++) {

						$db_result = mysql_query("SELECT * FROM stats_byday WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' AND Day = '$x' UNION SELECT * FROM stats_byday_archive WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' AND Day = '$x'");
						$row = mysql_fetch_array($db_result);
						$num_days = mysql_num_rows($db_result);

						if ($num_days != 0) {

								$act_num[$x] = $row[Hits];

								//$tmp_calc = $row[Hits]/$tHITS;
								$tmp_calc = $row[Hits]/$month_ciel;
								$lWIDTH = $tmp_calc*100;
								$lWIDTH = ceil($lWIDTH);
								$onhover = " onmouseover=\"show_num('".$ALL_MONTHS['Month'].$ALL_MONTHS['Year']."','".$ALL_MONTHS['Month']."','".$x."','".$row[Hits]."');\" ";
								$line_chart = "<table ".$onhover." border=0 cellpadding=0 cellspacing=0 STYLE='border: 1px solid black;' width=100% height=$lWIDTH><tr><td class=htext align=right bgcolor=darkgreen> </td></tr></table>";

								$tmp = $row[Real_Date];
								$tmp = split("-", $tmp);
								$cMON = $tmp[1];
								$cYER = $tmp[0];

								if (checkdate($cMON,$x,$cYER)) {

									if ($color == "#EFEFEF") { $color = "white"; } else { $color = "#EFEFEF"; } // Stager Bg Color for legibility
									if ($row[Hits] == "") { $d = "0"; } else { $d = $row[Hits]; }
									echo ("<TD ROWSPAN=2 ALIGN=\"center\" VALIGN=\"bottom\" class=htext width=10 bgcolor=white>$line_chart</TD>\n");

								}

						 } else {

						 		echo "<TD ROWSPAN=2 ALIGN=CENTER VALIGN=bottom class=smtext width=10 bgcolor=white></TD>\n";

						}

					} // End Header Chart Display



					echo ("</TR>\n");
					echo ("<TR><TD ALIGN=\"RIGHT\" VALIGN=\"bottom\" class=smtext bgcolor=#EFEFEF>0 -</TD></TR>\n");

					echo ("<TR>\n");
					echo ("<TD bgcolor=#EFEFEF class=smtext>Day of Month</font></td>\n");

					$ac_count = 1;

					for ($x=1;$x<=31;$x++) {

						if (checkdate($cMON,$x,$cYER)) {
							$ac_count++;
							echo ("<TD ALIGN=\"CENTER\" VALIGN=\"MIDDLE\" class=smtext bgcolor=#EFEFEF style='cursor: pointer'; onmouseover=\"show_num('".$ALL_MONTHS['Month'].$ALL_MONTHS['Year']."','".$ALL_MONTHS['Month']."','".$x."','".$act_num[$x]."');\">".$x."</TD>\n");
						}

					}

					echo ("</TR>\n");
					echo "</TABLE>\n\n";

					echo "<BR CLEAR=ALL>\n";
					echo " <DIV align=left style='padding: 5px;' class=text><font color=darkblue>\n";
					//echo "  [ <span id=\"".$ALL_MONTHS[Month]."_DNUM\">".$lang["Mouseover a Selected day for actual total"]."</span> ]\n";
					echo "  [ <span id=\"".$ALL_MONTHS['Month'].$ALL_MONTHS['Year']."_DNUM\">".$lang["Mouseover a Selected day for actual total"]."</span> ]\n";
					echo " </FONT></DIV>\n";

					echo "</TD></TR></TABLE><BR CLEAR=ALL><BR>\n\n";

	
		}
	} // End Monthly Loop (WHILE)

	?>

</BODY>
</HTML>
