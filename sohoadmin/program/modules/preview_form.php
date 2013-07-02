<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.6
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
include("../includes/product_gui.php");

?>


<HTML><HEAD><TITLE><? echo lang("Form Preview Window"); ?></TITLE>
</HEAD>
<BODY BGCOLOR=#EFEFEF TEXT=BLACK >

<?

if (isset($f)) {
	$filename = "$doc_root/media/$f";
	if ($file = fopen("$filename", "r")) {
		$HTML = fread($file,filesize($filename));
		fclose($file);
	}
	$HTML = eregi_replace("pgm-form_submit.php", "preview_form.php", $HTML);
	echo $HTML;
} else {
?>

<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100% HEIGHT=100%><TR><TD ALIGN=CENTER VALIGN=MIDDLE>
<font face=Tahoma size=4><? echo lang("PREVIEW WINDOW"); ?></font>
</TD></TR></TABLE>


<?
} // End if $f is called
?>

</BODY>
</HTML>