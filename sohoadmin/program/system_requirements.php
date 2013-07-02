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
	  
	  // The only reason this file is called is for a browser error
	  #style=\"background-color: #F8F9FD; border: 1px solid #2E2E2E; font-family: arial, helvetica, sans-serif; font-size: 12px; color: #980000;\"
	  
	  echo "<table class=\"feature_red\" width=\"70%\">\n";
	  
	  echo " <tr>\n";
	  echo "  <td class=\"fred_title\">\n";
	  echo "   Browser Warning\n";
	  echo "  </td>\n";
	  echo " </tr>\n";
	  
	  echo " <tr>\n";
	  echo "  <td style=\"padding: 10px; font-size: 12px;\">\n";
	  echo "   To function properly, this product must be accessed from an IBM-Compatible PC using Internet Explorer v5.0 or higher,\n";
	  echo "   Firefox, Slimbrowser, Netscape, AvantBrowser, or from a Macintosh computer utilizing Virtual PC or other such program.  Also compatible with Mac OS X running FireFox.<br><br>";
	  echo "  </td>\n";
	  echo " </tr>\n";	  
	  
	  echo "</table>\n";
	  
	  echo "<br><br>\n";
	  
	  echo "   \n";
	  echo "</div>";
	  echo "<FONT COLOR=#999999 size=1 face=arial>($HTTP_USER_AGENT)</FONT><br><br>\n";
	  
	  ?>
	  
        <table width="300" border="0" cellspacing="0" cellpadding="4" align="center" class=border bgcolor="#FFFFFF">
          <tr> 
            <td class=text bgcolor=#8caae7><font color="#FFFFFF" face=Verdana><b>System 
              Requirements </b></font></td>
          </tr>
          <tr align="center" valign="top"> 
            <td> 
              <table align="center" border="0" cellspacing="0" cellpadding="3" class=text width="100%">
                <tr> 
                  <td valign="top" align=left>                      <p> In order to access the site management tool, the following 
                      conditions must be met by your computer system.</p>
                    
                  <ul>
                    <li> &nbsp;You must be running an IBM compatible PC, or Mac OS X running FireFox.  This 
                      &nbsp;system will not work on Macintosh computers without 
                      Virtual PC or similar program installed.<br>
                      <br>
                    </li>
                    <li> &nbsp;Windows 98 or better operating system. Will not work from Linux desktop.<br>
                      <br>
                    </li>
                    <li>&nbsp;64Mb of Ram or better.<br>
                      <br>
                    </li>
                    <li>&nbsp;<a href="http://www.microsoft.com/windows/ie/" target="_blank">Internet 
                      Explorer 5.2</a> or better, Firefox, Slimbrowser, Netscape, AvantBrowser with Javascript and Cookies enabled 
                      (<i>Javascript is generally enabled by default</i>).<br>
                      <br>
                    </li>
                    <li>&nbsp;800 x 600 Screen resolution or better.</li>
                  </ul>
                    
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <BR clear=all>
        <BR>
        
      <div align=center class=text><a href="index.php?do=letmein">Override browser restrictions</a> (unrecommended)</div>

  </table>

</body>
</html>