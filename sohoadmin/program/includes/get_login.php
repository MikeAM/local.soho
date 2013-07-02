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

############################################################################
### DETERMINE WHAT DIRECTORY THE APPLICATION IS RUNNING IN ON THE SERVER ###
############################################################################

$directory = $_SESSION['docroot_path'];
if (is_dir($directory)) {
		$handle = opendir("$directory");
		while ($files = readdir($handle)) {
			$this_file = $files;
			$files = $directory . "/$files";
			if (is_dir($files) && strlen($files) > 2) {
				$tmp = opendir("$files");
				while ($test_files = readdir($tmp)) {
					if ($test_files == "version.php") {
						$INSTALL_DIR = $this_file;
					}
				}
			}
		}
	
		closedir($handle);
		$submit_form = "http://$this_ip/$INSTALL_DIR/index.php";
}

// Make sure user is using Windows Based IE browser (win req taken out temporarily 09-10-2004)
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
$browser_type = $HTTP_USER_AGENT;
if (!eregi("MSIE", $browser_type)) {
	header("Location: system_requirements.php?nonwin=1");
	exit;
}
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

?>

<HTML>
<HEAD>
<TITLE>Login System (4.5)</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<STYLE TYPE="TEXT/CSS">
<!--
.border {  border: 1px black solid; }
.txt { font-family: Arial; font-size: 9pt; }
-->
</STYLE>
<script language="JavaScript">
<!--
	function moz_reload(init) {  // reload window if Netscape resizes
	  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
	    document.tmp_pgW=innerWidth; document.tmp_pgH=innerHeight; onresize=moz_reload; }}
	  else if (innerWidth!=document.tmp_pgW || innerHeight!=document.tmp_pgH) location.reload();
	}

	moz_reload(true);
// -->
</script>
</HEAD>
<BODY BGCOLOR="#EFEFEF" TEXT="#FFFFFF" LINK="red" VLINK="red" ALINK="red" LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0">
<TABLE WIDTH="100%" HEIGHT="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0" ALIGN="CENTER">
  <TR> 
    <TD ALIGN="CENTER" VALIGN="MIDDLE"> 
      <TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" ALIGN="CENTER">
        <TR> 
          <TD> 
            <TABLE WIDTH="625" BORDER="0" CELLSPACING="3" CELLPADDING="3" ALIGN="CENTER">
              <TR> 
                <TD ALIGN="LEFT" VALIGN="TOP" CLASS="txt"> 
                  
                  <form name=login method=post action="<? echo $submit_form; ?>">
                    <table width=100% height=100% cellpadding=0 cellspacing=0 border=0>
                      <tr>
                        <td align=center valign=center> 
                          <table width="300" border="0" cellspacing="0" cellpadding="4" align="center" class=border bgcolor="#FFFFFF">
                            <tr> 
                              <td class=txt bgcolor=#8caae7><font color="#FFFFFF" face=Verdana><b>Please 
                                Login</b></font></td>
                            </tr>
                            <tr align="center" valign="top"> 
                              <td> 
                                <table align="center" border="0" cellspacing="0" cellpadding="3" class=txt>
                                  <tr> 
                                    <td valign="top" align=left><Font color=black><? echo lang("Username"); ?>:</font><br>
                                      <input type="text" name="PHP_AUTH_USER" class=txt style='width: 150px';>
                                      <br>
                                      <Font color=black><? echo lang("Password"); ?>:</font><br>
                                      <input type="password" name="PHP_AUTH_PW" class=txt style='width: 150px';>
                                      <br>
                                      <div align=right> 
                                        <input type=hidden name=process value=1>
                                        <input type=submit class=txt style='cursor: hand;' value="<? echo lang("Login"); ?>">
                                        <BR>
                                      </div>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          </table>
                    </table>
                  </form>
                  <center><font style='font-family: Arial; font-size: 8pt; color: black;'><a href="system_requirements.html"><? echo lang("System Requirements"); ?></a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="help.html"><? echo lang("Login Help"); ?></a></font></center>
                </TD>
            </TABLE>
          </TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
</TABLE>
</BODY>
</HTML>