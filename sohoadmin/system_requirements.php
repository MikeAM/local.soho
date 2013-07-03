<?php
error_reporting('341');
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
?>

<html>
<head>
<title>Browser Error</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="program/includes/product_interface.css">
</head>

<body bgcolor="#EFEFEF" text="#000000" link="#CC0000" vlink="#CC0000" alink="#000066" leftmargin="3" topmargin="20" marginwidth="3" marginheight="20">

  <table width=100% height=100% cellpadding=0 cellspacing=0 border=0>
    <tr>
      <td align=center valign=center> 
	  
<?php

# Browser or server error?
if ( $_GET['system_problem'] == "browser" ) { // Browser Error   
   echo "<table class=\"feature_red\" width=\"80%\">\n";
   
   # Browser Warning box
   echo " <tr>\n";
   echo "  <td class=\"fred_title\">\n";
   echo "   Browser Warning\n";
   echo "  </td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo "  <td style=\"padding: 10px; font-size: 12px;\">\n";
   echo "   To function properly, this product must be accessed from an IBM-Compatible PC using Internet Explorer v5.0 or higher,\n";
   echo "   or from a Macintosh computer utilizing Virtual PC or other such program.<br><br>";
   echo "  </td>\n";
   echo " </tr>\n"; 
   echo "</table>\n";
   
   echo "<br><br>\n";
   
   # System Requirements box
   echo "<table width=\"80%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\" align=\"center\" class=\"border\" bgcolor=\"#FFFFFF\" style=\"font-family: 'Courier New', Courier, mono; font-size: 11px;\">\n";
   echo " <tr>\n";
   echo "  <td class=text bgcolor=#8caae7><font color=\"#FFFFFF\" face=Verdana><b>System Requirements </b></font></td>\n";
   echo " </tr>\n";
   echo " <tr align=\"center\" valign=\"top\">\n";
   echo "  <td><table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" class=text width=\"100%\">\n";
   echo "    <tr>\n";
   echo "     <td valign=\"top\" align=left><p> In order to access the site management tool, the following conditions must be met by your computer system.</p>\n";
   echo "      <ul>\n";
   echo "       <li> &nbsp;You must be running an IBM compatible PC. This &nbsp;system will not work on Macintosh computers without Virtual PC or similar program installed.<br>\n";
   echo "        <br>\n";
   echo "       </li>\n";
   echo "       <li> &nbsp;Windows 98 or better operating system. Will not work from Linux desktop.<br>\n";
   echo "        <br>\n";
   echo "       </li>\n";
   echo "       <li>&nbsp;64Mb of Ram or better.<br>\n";
   echo "        <br>\n";
   echo "       </li>\n";
   echo "       <li>&nbsp;<a href=\"http://www.microsoft.com/windows/ie/\" target=\"_blank\">Internet Explorer 5.2</a> or better browser with Javascript and Cookies enabled (<i>Javascript is generally enabled by default</i>). This system will not work with Netscape Navigator or other browser types.<br>\n";
   echo "        <br>\n";
   echo "       </li>\n";
   echo "       <li>&nbsp;800 x 600 Screen resolution or better.</li>\n";
   echo "      </ul></td>\n";
   echo "    </tr>\n";
   echo "   </table></td>\n";
   echo " </tr>\n";
   echo "</table>\n";

} elseif ( $_GET['system_problem'] == "globals" ) { // register_globals
   echo "<table class=\"feature_red\" width=\"80%\">\n";
   echo " <tr>\n";
   echo "  <td class=\"fred_title\">\n";
   echo "   Server Configuration Error\n";
   echo "  </td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo "  <td style=\"padding: 10px; font-size: 12px;\">\n";
   echo "   To function properly, the '<b>register_globals</b>' option must be enabled\n";
   echo "   for the domain on which this product is installed\n";
   echo "   <br><br>";
   echo "   <i>Have no idea what this is referring to?</i><br>\n";
   echo "   Contact technical support and ask them to enable 'register_globals' for your domain.\n";
   echo "   <br><br>";   
   echo "   <u>Note to server admin:</u><br>\n";
   echo "   Remember that there are several different ways to enable the 'register_globals' option, including...<br><br>\n";
   echo "   1. Server-wide via php.ini file<br><br>\n";
   echo "   2. Domain-only via .htaccess file ('<span style=\"color: #8b8b8b;\">php_flag register_globals on</span>')<br><br>\n";
   echo "   3. Domain-only via httpd.conf (Add '<span style=\"color: #8b8b8b;\">php_flag register_globals 1</span>' to the &lt;/VirtualHost&gt; entry)<br>\n";
   echo "  </td>\n";
   echo " </tr>\n";
   echo "</table>\n";
}   

echo "<br><br>\n";

# Display browser type
echo "<FONT COLOR=#999999 size=1 face=arial>(".$_SERVER['HTTP_USER_AGENT'].")</FONT><br><br>\n";
	  
?>
	  

        <BR clear=all>
        <BR>
        
      <div align=center class=text><a href="index.php?do=letmein">Override system/browser restrictions</a> (unrecommended)</div>

  </table>

</body>
</html>