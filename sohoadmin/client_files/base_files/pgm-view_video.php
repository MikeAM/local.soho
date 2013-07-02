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

include_once('sohoadmin/includes/emulate_globals.php');
set_time_limit(0);
error_reporting(0);
track_vars;

$tmp = split("\.", $name);
$extension = $tmp[1];
$filename = $tmp[0];

$thisFile = "media/$name";

include_once('sohoadmin/program/includes/shared_functions.php');

// Log this calendar view into stats
// -----------------------------------------------------------------
	include_once("sohoadmin/client_files/pgm-site_config.php");
	if (file_exists("sohoadmin/client_files/pgm-site_stats.inc.php")) {		// Check; this mod N/A in Lite Version
		$statpage = lang("Video").": $name";
		include ("sohoadmin/client_files/pgm-site_stats.inc.php");
	}
		
// -----------------------------------------------------------------

echo ("<HTML>\n<HEAD>\n<TITLE>".lang("Video")."</TITLE>\n</HEAD>\n");
echo '<BODY BGCOLOR="#000000" TEXT="#FFFFFF" LINK="#FFFFFF" VLINK="#FFFFFF" ALINK="#FFFFFF" LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0">';
echo ("\n\n<table border=0 cellpadding=0 cellspacing=0 width=100% height=100%>\n <tr>\n  <td align=center valign=middle>\n   <table border=1 cellpadding=0 cellspacing=0>\n    <tr>\n     <td>\n");

if (eregi("ipx", $extension) ) 
{
	echo '<OBJECT border=1 ID="IpixX1" WIDTH=' . $w . ' height=' . $h . ' CLASSID="CLSID:11260943-421B-11D0-8EAC-0000C07D88CF" CODEBASE="http://www.ipix.com/viewers/ipixx.cab#version=6,2,0,5">
	<PARAM NAME="IPXFILENAME" VALUE="' . $thisFile . '"> 
	<embed src="' . $thisFile . '" border=0 width=' . $w . ' height=' . $h . ' palette="FOREGROUND" type="application/x-ipix" pluginsPage="http://www.ipix.com/cgi-bin/download.cgi">
	</embed></OBJECT>';
}
	elseif ( eregi("avi", $extension) || eregi("mpeg", $extension) || eregi("mpg", $extension))
	{
		$droparea .= "<div align=center>$spacer";
		$droparea .= "<EMBED SRC=\"$thisFile\" WIDTH=$w HEIGHT=$h showcontrols=1 AUTOSTART=true LOOP=false>\n";
		$droparea .= "</div>\n";
		//$droparea .= "<embed SRC=\"http://".$_SESSION['docroot_url']."/".$thisFile."\" WIDTH=".$w." height=\"".$h."\" type=\"application/x-mplayer2\" name=\"MediaPlayer\" autostart=\"1\" showcontrols=\"1\" showstatusbar=\"0\" autorewind=\"1\" showdisplay=\"0\">\n";
		//$droparea .= "<EMBED SRC=\"$thisFile\" WIDTH=$w HEIGHT=$h CONTROLS=console AUTOSTART=true LOOP=false>\n";
		echo $droparea;
	} 
		elseif ( eregi("wmv", $extension) ) 
		{
			$droparea .= "<div align=center>$spacer";
			$droparea .= "<EMBED SRC=\"$thisFile\" WIDTH=$w HEIGHT=$h showcontrols=1 AUTOSTART=true LOOP=false>\n";
		   $droparea .= "</div>\n";
		   //$droparea .= "<embed SRC=\"http://".$_SESSION['docroot_url']."/".$thisFile."\" WIDTH=".$w." height=\"".$h."\" type=\"application/x-mplayer2\" name=\"MediaPlayer\" autostart=\"1\" showcontrols=\"1\" showstatusbar=\"0\" autorewind=\"1\" showdisplay=\"0\">\n";
		 	// $droparea .= "<EMBED SRC=\"$thisFile\" WIDTH=$w HEIGHT=$h CONTROLS=console AUTOSTART=true LOOP=false>\n";
			echo $droparea;
		}
			elseif ( eregi("mov", $extension) ) 
			{
				$droparea .= "<div align=center>$spacer";
				$droparea .= "<EMBED SRC=\"$thisFile\" WIDTH=$w HEIGHT=$h showcontrols=1 AUTOSTART=true LOOP=false>\n";
	  		   $droparea .= "</div>\n";
				//$droparea .= "<embed SRC=\"http://".$_SERVER['HTTP_HOST']."/".$thisFile."\" WIDTH=".$w." height=\"".$h."\" type=\"application/x-mplayer2\" name=\"MediaPlayer\" autostart=\"1\" showcontrols=\"1\" showstatusbar=\"0\" autorewind=\"1\" showdisplay=\"0\">\n";	  		   
   	  		//$droparea .= "<EMBED SRC=\"$thisFile\" WIDTH=$w HEIGHT=$h CONTROLS=console AUTOSTART=true LOOP=false>\n";
				echo $droparea;
			}
				elseif (eregi("rm", $extension) ) 
				{
	    		echo '<OBJECT ID=RVOCX CLASSID="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" WIDTH=' . $w . ' HEIGHT=' . $h . '>
	    		<PARAM NAME="SRC" VALUE="' . $thisFile . '">
	    		<PARAM NAME="CONSOLE" VALUE="one">
	    		<PARAM NAME="CONTROLS" VALUE="ImageWindow">
	    		<PARAM NAME="BACKGROUNDCOLOR" VALUE="black">
	    		<PARAM NAME="CENTER" VALUE="true">';
	    		echo '<EMBED NAME=vidfile SRC="' . $thisFile . '" WIDTH=' . $w . ' HEIGHT=' . $h . ' CONSOLE=one CONTROLS=ImageWindow BACKGROUNDCOLOR=black CENTER=true></EMBED></OBJECT><script LANGUAGE="VBScript">RVOCX.DoPlay</script>';
	    		echo '<SCRIPT language=Javascript>document.vidfile.DoPlay()</SCRIPT>'; 
				}


echo ("</td></tr></table><font face=Arial size=1><B><a href=\"javascript: self.close();\">Close Window</a></B></font></td></tr></table></body></HTML>\n");
exit;

?>