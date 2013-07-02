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
<TITLE>Browser and Operating Systems</TITLE>
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

	echo "<H5><FONT FACE=VERDANA><U>".$lang["BROWSER AND OPERATING SYSTEMS USED"]."</U></FONT></H5>\n";

	$result = mysql_query("SELECT Month, Year, Real_Date FROM stats_browser group by Month UNION SELECT DISTINCT Month, Year, Real_Date FROM stats_browser_archive group by Month ORDER BY Real_Date DESC");

	while($ALL_MONTHS = mysql_fetch_array($result)) {



			####################################################################
			#### START CALCULATION FOR BROWSERS USED TO ACCESS SITE 	     ###
			####################################################################

			$a=1;
			$a_cnt=0;

			$db_result = mysql_query("SELECT * FROM stats_browser WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' UNION SELECT * FROM stats_browser_archive WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' ORDER BY Hits DESC");

			while ($row = mysql_fetch_array($db_result)) {

				$a_cnt = $a_cnt + $row[Hits];

				$mflag = 0;
				$nflag = 0;

				$tmp_data = $row[Browser];

				if (eregi("MSIE", $tmp_data)) {

					$tmp_array = split(";", $tmp_data);
					$tmp_array[1] = eregi_replace(")", "", $tmp_array[1]);
					$tmp_array[1] = eregi_replace("b", "", $tmp_array[1]);
					$tmp_array[1] = eregi_replace("MSIE\.", "MSIE ", $tmp_array[1]);

					$eflag=0;

					for ($y=1;$y<=$a;$y++) {
						if ($Browser[$y] == $tmp_array[1]) {
							$Usage[$y] = $Usage[$y] + $row[Hits];
							$eflag=1;
						}
					}

					if ($eflag != 1) {
						$Browser[$a] = $tmp_array[1];
						$Usage[$a] = $row[Hits];
						$a++;
					}
					$mflag = 1;
				}

				if (eregi("Netscape", $tmp_data)) {

					$eflag=0;

					for ($y=1;$y<=$a;$y++) {
						if ($Browser[$y] == "Netscape Navigator") {
							$Usage[$y] = $Usage[$y] + $row[Hits];
							$eflag=1;
						}
					}

					if ($eflag != 1) {
						$Browser[$a] = "Netscape Navigator";
						$Usage[$a] = $row[Hits];
						$a++;
					}
						$nflag = 1;
				}

				if ($nflag == 0 && $mflag == 0) {

					$eflag=0;

					for ($y=1;$y<=$a;$y++) {
						if ($Browser[$y] == "Other (Linux/Sun/Lynx)") {
							$Usage[$y] = $Usage[$y] + $row[Hits];
							$eflag=1;
						}
					}

					if ($eflag != 1) {
						$Browser[$a] = "Other (Linux/Sun/Lynx)";
						$Usage[$a] = $row[Hits];
						$a++;
					}
				}
			}

		$total_browsers = $a - 1;


		echo "<DIV CLASS=text><B><U>$ALL_MONTHS[Month] $ALL_MONTHS[Year]</U></B></DIV>\n";

        echo "<TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4 STYLE='border: 1px solid black; background: #708090;' ALIGN=LEFT>
          	<TR>
            <TD ALIGN=\"CENTER\" VALIGN=\"TOP\" BGCOLOR=\"#EFEFEF\" WIDTH=150 class=text><B>".$lang["Browser"]."</B></TD>
            <TD ALIGN=\"CENTER\" VALIGN=\"TOP\" WIDTH=\"500\" BGCOLOR=\"#EFEFEF\" class=text><B>".$lang["Usage Data"]."</B></TD>
          	</TR>\n";



		  $color = "white";
		  $perc_total = 0;
		  for ($x=1;$x<=$total_browsers;$x++) {

			$perc = $Usage[$x]/$a_cnt;
			$perc = $perc*100;
			$perc = number_format($perc,2);

			$perc_total = $perc_total + $perc;

			$Browser[$x] = eregi_replace("MSIE", "Internet Explorer", $Browser[$x]);

			$lwidth = ceil($perc);
			$line_chart = "<table border=0 cellpadding=0 cellspacing=0 class=allBorder width=".$lwidth."% height=10 align=LEFT><tr><td class=htext bgcolor=darkgreen align=right><B><FONT COLOR=white>$perc%</font></B></td></tr></table>";

		  	echo ("<TR BGCOLOR=\"$color\">\n");
	            echo ("<TD ALIGN=\"RIGHT\" VALIGN=\"TOP\" class=text WIDTH=\"150\">$Browser[$x]</TD>\n");
	            echo ("<TD ALIGN=\"LEFT\" VALIGN=\"TOP\" classs=text WIDTH=\"500\">$line_chart</TD>\n");
			echo ("</TR>\n");

		  }

		  echo "</TABLE><BR CLEAR=ALL>\n";

			####################################################################
			#### START CALCULATION FOR OPERATING SYSTEMS USED TO ACCESS SITE ###
			####################################################################

			$a=1;
			$a_cnt = 0;

			$db_result = mysql_query("SELECT * FROM stats_browser WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' UNION SELECT * FROM stats_browser_archive WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' ORDER BY Hits DESC");

			while ($row = mysql_fetch_array($db_result)) {

				$a_cnt = $a_cnt + $row[Hits];

				$winflag=0;
				$macflag=0;

				$tmp_data = $row[Browser];

				// *************************************
				// ** Look for MAC OS
				// *************************************

				if (eregi("Mac", $tmp_data)) {

					$this_OS = "Apple Macintosh";

					$eflag=0;

					for ($y=1;$y<=$a;$y++) {
						if ($OSSYS[$y] == $this_OS) {
							$HITS[$y] = $HITS[$y] + $row[Hits];
							$eflag = 1;
						}
					}

					if ($eflag != 1) {
						$OSSYS[$a] = $this_OS;
						$HITS[$a] = $row[Hits];
						$a++;
					}

					$macflag = 1;

				}

				// *************************************
				// ** Finnally - Look for Windows OS
				// *************************************

				if (eregi("Win", $tmp_data)) {

					// *** DETERMINE IF WINDOWS NT OR NOT **

					if (eregi("NT", $tmp_data)) {

						$s = split("NT", $tmp_data);
						$right = eregi_replace(" ", "", $s[1]);
						$right = substr($right, 0, 3);

						if (substr($right, 0, 1) == "5") {
							$this_OS = "Windows XP";
						} else {
							$this_OS = "Windows NT " . $right;

							// *** Is there a version number here? ***
							// ***************************************

							$l = strlen($this_OS);
							$zz = 0;
							$vflag = 0;
							while($zz != $l) {
								$tmp = substr($this_OS, $zz, 1);
								if (eregi("[0-9]", $tmp)) { $vflag++; }
								$zz++;
							}

							if ($vflag == 0) {
								$this_OS = "Windows NT";
							}

						}

					} else {

						$s = split("Windows", $tmp_data);
						$right = eregi_replace(" ", "", $s[1]);
						$right = substr($right, 0, 2);

						$this_OS = "Windows " . $right;

						// *** Is there a version number here? ***
						// ***************************************

						$l = strlen($this_OS);
						$zz = 0;
						$vflag = 0;
						while($zz != $l) {
							$tmp = substr($this_OS, $zz, 1);
							if (eregi("[0-9]", $tmp)) { $vflag++; }
							$zz++;
						}

						// *** If not, it's probably a WIN notation
						// *****************************************
						if ($vflag == 0) {
							$s = split("Win", $tmp_data);
							$right = eregi_replace(" ", "", $s[1]);
							$right = substr($right, 0, 2);
							$this_OS = "Windows " . $right;
						}

						// *** NOW, Is there a version number here? ***
						// ********************************************

						$l = strlen($this_OS);
						$zz = 0;
						$vflag = 0;
						while($zz != $l) {
							$tmp = substr($this_OS, $zz, 1);
							if (eregi("[0-9]", $tmp)) { $vflag++; }
							$zz++;
						}

						// ** If not, assume Win 95 - Come on Bill!
						// *********************************************

							if ($vflag == 0) {
								$this_OS = "Windows 95";
							}

					}

					// *** PLACE IN SYSTEM AS A SPECIFIC OS ***

					$eflag=0;

					for ($y=1;$y<=$a;$y++) {
						if ($OSSYS[$y] == $this_OS) {
							$HITS[$y] = $HITS[$y] + $row[Hits];
							$eflag = 1;
						}
					}

					if ($eflag != 1) {
						$OSSYS[$a] = $this_OS;
						$HITS[$a] = $row[Hits];
						$a++;
					}

					$winflag = 1;

				} // End Win OS Look up


				// ***************************************
				// ** Default everything else to OTHER
				// ***************************************

				if ($macflag == 0 && $winflag == 0) {
					$this_OS = "Other (Linux/Sun/BeOS)";

					$eflag=0;

					for ($y=1;$y<=$a;$y++) {
						if ($OSSYS[$y] == $this_OS) {
							$HITS[$y] = $HITS[$y] + $row[Hits];
							$eflag = 1;
						}
					}

					if ($eflag != 1) {
						$OSSYS[$a] = $this_OS;
						$HITS[$a] = $row[Hits];
						$a++;
					}
				}

			}

		$total_os = $a - 1;

	 	 echo "<BR><TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4 STYLE='border: 1px solid black; background: #708090;' ALIGN=LEFT>
				<TR>
				<TD ALIGN=\"CENTER\" VALIGN=\"TOP\" BGCOLOR=\"#EFEFEF\" WIDTH=150 class=text><B>Operating System</B></TD>
				<TD ALIGN=\"CENTER\" VALIGN=\"TOP\" WIDTH=\"500\" BGCOLOR=\"#EFEFEF\" class=text><B>Usage Data</B></TD>
				</TR>\n";

		  $color = "WHITE";
		  $perc_total = 0;

		  for ($x=1;$x<=$total_os;$x++) {


			$perc = $HITS[$x]/$a_cnt;
			$perc = $perc*100;
			$perc = number_format($perc,2);

			$perc_total = $perc_total + $perc;

			$lwidth = ceil($perc);
			$line_chart = "<table border=0 cellpadding=0 cellspacing=0 class=allBorder width=".$lwidth."% height=10 align=LEFT><tr><td class=htext bgcolor=darkgreen align=right><B><FONT COLOR=white>$perc%</font></B></td></tr></table>";

		  	echo ("<TR BGCOLOR=\"white\">\n");
	            echo ("<TD ALIGN=\"right\" VALIGN=\"TOP\" WIDTH=\"150\" class=text>$OSSYS[$x]</TD>\n");
	            echo ("<TD ALIGN=\"left\" VALIGN=\"TOP\" class=text>$line_chart</TD>\n");
			echo ("</TR>\n");


		  }

		  echo "</TABLE><BR CLEAR=ALL><BR><BR>\n\n";

	} // End Monthly While Loop

?>

</BODY>
</HTML>
