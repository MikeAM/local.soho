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
require_once('../../includes/product_gui.php');
$local_name = strtoupper($localname);
$tmp = "$doc_root/$name";

echo ("<HTML><HEAD><TITLE>FILE DOWNLOAD</TITLE><SCRIPT language=Javascript> window.focus(); </SCRIPT></HEAD><BODY BGCOLOR=#EFEFEF>");
echo ("<center><font size=2 face=Verdana><B>Right click on the filename<BR>below and choose \"Save Target As...\"</B></font><BR><BR>\n\n");
echo ("<font style='font-family: Arial; font-size: 9pt;'><a href=\"http://$this_ip/$name\">$local_name</a>\n\n<BR><BR>");

echo ("<form name=bogus><input type=button style='cursor: hand;' value=\"Close Window\" onclick=\"javascript: self.close();\"></form></center>");
echo ("</body></html>");
exit;

?>