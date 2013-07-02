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
error_reporting(E_PARSE);

$kb=480;
$max=155000;
$atime=10;			// Number of min to avg for
$filename = $_SESSION['docroot_path']."/sohoadmin/filebin/currentuser.log";

## WRITE "CURRENT" SESSION TO FILE ##


if(!isset($datei)){
$datei = dirname(__FILE__)."/$filename";
}
$time = @time();
$ip = $REMOTE_ADDR;
$string = "$ip|$time\n";
if ( !$a = fopen("$filename", "a+") ) {
   echo "Could not open ".$filename."!";
} else {
   if ( !fputs($a, $string) ) {
      echo "Could not write to ".$filename."!";
   }
   fclose($a);
   $timeout = time()-(60*$atime);
}

## READ CURRENT FILE ##

$all = "";
$i = 0;
$datei = file($filename);
for ($num = 0; $num < count($datei); $num++) {
	$pieces = explode("|",$datei[$num]);
		if ($pieces[1] > $timeout) {
			$all .= $pieces[0];
			$all .= ",";
		}
	$i++;
}
$all=substr($all,0,strlen($all)-1);
$cp="";
$arraypieces = explode(",",$all);
$useronline = count(array_flip(array_flip($arraypieces)));

$useronline = $useronline - 1; // Remove current tool log in

# Average users online
$qry = "select avg(Avg_Users) from STATS_BYHOUR where Avg_Users != ''";
$rez = mysql_query($qry);
$average = mysql_result($rez, 0);
$average = round($average);

# Ouput formatted count in table?
if ( $show_usercount != "no" ) {
   echo "<TABLE border=0 cellspacing=0 cellpadding=0 align=center><TR><TD ALIGN=CENTER>";
   echo "<font style='font-family: Tahoma; font-size: 8pt; color: #999999;'>".lang("Site visitors online now").": [".$useronline."] | ".lang("Average").": [".$average."]</font></CENTER></TD></TR></TABLE>";
}


## DEL TIMEDOUT USERS ##

$dell = "";
for ($numm = 0; $numm < count($datei); $numm++) {
	$tiles = explode("|",$datei[$numm]);
		if ($tiles[1] > $timeout) {
			$dell .= "$tiles[0]|$tiles[1]";
		}
}
if (!$datei) $datei = dirname(__FILE__)."/$filename";
$time = @time();
$ip = $REMOTE_ADDR;
$string = "$dell";
$a = fopen("$filename", "w+");
fputs($a, $string);
fclose($a);
?>