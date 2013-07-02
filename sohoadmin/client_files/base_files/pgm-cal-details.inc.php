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
	error_reporting(0);
	include_once("sohoadmin/client_files/pgm-site_config.php");
	include_once("sohoadmin/program/includes/shared_functions.php");

	$calpref = new userdata("calendar");
	
	if(!preg_match('/^[a-z0-9]{1,}$/i', $id) && $id!=""){
		exit;
	}

	$myresult = mysql_query("SELECT * FROM calendar_display");
	$DISPLAY = mysql_fetch_array($myresult);

	$myresult = mysql_query("SELECT * FROM calendar_events WHERE PRIKEY = '$id'");
	$DATA = mysql_fetch_array($myresult);


	// Configure Displayable Date and Time Settings

	$tmp = split("-", $DATA[EVENT_DATE]);
	$display_date = date("F j, Y", mktime(0,0,0,$tmp[1],$tmp[2],$tmp[0]));

	$tmps = split(":", $DATA[EVENT_START]);
	$start_time = date("g:ia", mktime($tmps[0],$tmps[1],$tmps[2],$tmp[1],$tmp[2],$tmp[0]));

	if ($DATA[EVENT_END] != "00:00:00") {
		$tmps = split(":", $DATA[EVENT_END]);
		$end_time = date("g:ia", mktime($tmps[0],$tmps[1],$tmps[2],$tmp[1],$tmp[2],$tmp[0]));
		$start_time .= " - $end_time";
	}

	if (($DATA[EVENT_START] == "00:00:00") && ($DATA[EVENT_END] == "00:00:00"))
	{
	   $start_time = lang("Not Specified");
	}

	$display_title = strtoupper($DATA[EVENT_TITLE]);

	?>

<HTML>
<HEAD>
<TITLE>Event Details : <? echo $DATA[EVENT_TITLE]; ?></TITLE>
<LINK HREF="runtime.css" REL="stylesheet" TYPE="text/css">
</HEAD>

<BODY BGCOLOR="#EFEFEF" TEXT="#000000" LINK="#FF0000" VLINK="#FF0000" ALINK="#FF0000" LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0">
<TABLE WIDTH="99%" BORDER="0" ALIGN="CENTER" CELLSPACING="0" CELLPADDING="5" CLASS=text STYLE='border: 1px solid black;'>
  <TR>
    <TD ALIGN=LEFT VALIGN=MIDDLE BGCOLOR=<? echo $DISPLAY[BACKGROUND_COLOR]; ?>><FONT COLOR=<? echo $DISPLAY[TEXT_COLOR]; ?>>Event:</FONT>
	</TD><TD ALIGN=RIGHT VALIGN=MIDDLE BGCOLOR=<? echo $DISPLAY[BACKGROUND_COLOR]; ?>><INPUT TYPE="BUTTON" NAME="Button" CLASS="FormLt1" VALUE=" <? echo $lang["Print Details"]; ?> " onclick="javascript: window.print();">&nbsp;&nbsp;<INPUT TYPE="BUTTON" NAME="Button" CLASS="FormLt1" VALUE="<? echo $lang["Close Window"]; ?>" onclick="javascript: self.close();">
	</TD>
  </TR>
  <TR><TD COLSPAN=2 ALIGN=LEFT VALIGN=TOP BGCOLOR="WHITE"><B><FONT SIZE=3 FACE=VERDANA><? echo $display_title; ?></FONT></B></TD></TR>
  <TR>
    <TD WIDTH="50%" BGCOLOR="WHITE"><B><U><? echo lang("Event Date"); ?></U>:</B> <? echo $display_date; ?></TD>
    <TD BGCOLOR="WHITE"><B><U><? echo lang("Event Time"); ?></U>:</B> <? echo $start_time; ?></TD>
  </TR>
  </TABLE>

<?
	if ($DATA[EVENT_DETAILS] != "") {

		echo "<BR><TABLE WIDTH=99% BORDER=0 ALIGN=CENTER CELLSPACING=0 CELLPADDING=5 CLASS=text STYLE='border: 1px solid black;'>\n";
		echo "<TR>\n";
		echo "<TD BGCOLOR=$DISPLAY[BACKGROUND_COLOR]><FONT COLOR=$DISPLAY[TEXT_COLOR]>".lang("Event Details").":</FONT></TD>\n";
		echo "</TR>\n";
		echo "</TABLE> \n";

		# Event detail text
		echo "<div class=text style='padding: 10px;'>\n";
		# Preserve line breaks?
		if ( $calpref->get("linebreaks") == "yes" ) {
		   echo " ".nl2br($DATA['EVENT_DETAILS'])."\n";
		} else {
		   echo " ".$DATA['EVENT_DETAILS']."\n";
		}
		echo "</DIV>\n";

	}

	if ($DATA[EVENT_DETAILPAGE] != "") {

			echo "<BR><TABLE WIDTH=99% BORDER=0 CELLSPACING=0 CELLPADDING=5 ALIGN=CENTER CLASS=text STYLE='border: 1px solid black;'>\n";
			echo "<TR>\n";
			echo "<TD BGCOLOR=$DISPLAY[BACKGROUND_COLOR]><FONT COLOR=$DISPLAY[TEXT_COLOR]>".lang("More Details").":</FONT></TD>\n";
			echo "</TR>\n";
			echo "</TABLE> \n";

				$filename = "$cgi_bin/$DATA[EVENT_DETAILPAGE].con";	// .con ext is important for this
				$filename = eregi_replace(" ", "_", $filename);

				if (file_exists("$filename")) {

					$file = fopen("$filename", "r");
						$DETAILS = fread($file,filesize($filename));
					fclose($file);

					// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
					// Reformat content area of HTML to compensate for new 462 width
					// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

					$DETAILS = eregi_replace("<P>", "<div>", $DETAILS);
					$DETAILS = eregi_replace("<P ", "<div ", $DETAILS);
					$DETAILS = eregi_replace("width=612", "width=447", $DETAILS); // Overall Content Area

					$DETAILS = eregi_replace("width=199 height=21>", "width=144 height=15>", $DETAILS); // Clears Built-In Obj

					$DETAILS = eregi_replace("ID=thisflashobj WIDTH=\"597\" height=\"302\"", "ID=thisflashobj WIDTH=\"432\" height=\"220\"", $DETAILS); // Setup Flash Obj
					$DETAILS = eregi_replace("ID=thisflashobj WIDTH=\"398\" height=\"202\"", "ID=thisflashobj WIDTH=\"288\" height=\"144\"", $DETAILS); // Setup Flash Obj
					$DETAILS = eregi_replace("ID=thisflashobj WIDTH=\"199\" height=\"101\"", "ID=thisflashobj WIDTH=\"144\" height=\"72\"", $DETAILS); // Setup Flash Obj

					$DETAILS = eregi_replace("<img src=\"spacer.gif\" height=3 width=199 border=0>", "<img src=\"spacer.gif\" height=3 width=120 border=0>", $DETAILS); // Clears Built-In Obj

					$DETAILS = eregi_replace("width=597 height=", "width=432 name=", $DETAILS);
					$DETAILS = eregi_replace("width=398 height=", "width=288 name=", $DETAILS);
					$DETAILS = eregi_replace("width=199 height=", "width=144 name=", $DETAILS);

					$DETAILS = eregi_replace("width=597", "width=432", $DETAILS);
					$DETAILS = eregi_replace("width=398", "width=288", $DETAILS);
					$DETAILS = eregi_replace("width=199", "width=144", $DETAILS);
					$DETAILS = eregi_replace("width=298", "width=216", $DETAILS);

					// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
					// IF A CUSTOM PHP INCLUDE HAS BEEN PLACED ON THIS PAGE, PROCESS IT
					// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

					if (eregi("##MIKEINC;", $DETAILS)) {

						$temp = eregi("<!-- ##MIKEINC;(.*)## -->", $DETAILS, $out);
						$INCLUDE_FILE = $out[1];

						$filename = "$doc_root/media/$INCLUDE_FILE";

						ob_start();
							include("$filename");
							$output = ob_get_contents();
						ob_end_clean();

						$replicate = "<!-- ##MIKEINC;$INCLUDE_FILE## -->";

						$DETAILS = eregi_replace($replicate, "<!-- START -->\n\n$output\n\n<!-- END -->", $DETAILS);

					}

					$THIS_DISPLAY .= "<div align=left style='background: white;'><font face=Verdana, Arial, Helvetica size=2>\n";
					$THIS_DISPLAY .= "$DETAILS</div>\n";

				} // End if File Exists

			echo $THIS_DISPLAY;

	} // End if Detail Page Exists

?>


</BODY>
</HTML>
