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
track_vars;

include($_SESSION['docroot_path']."/sohoadmin/includes/login.php");

$instructions = "Select your function from the menu system above.";
$thisTitle = "Secure Login System";

?>


<html>
<head>
<title></title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<LINK rel="stylesheet" href="../product_gui.css" type="text/css">
<script language="JavaScript">

function SV2_findObj(n, d) { //v3.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=SV2_findObj(n,d.layers[i].document); return x;
}

function SV2_showHideLayers() { //v3.0
  var i,p,v,obj,args=SV2_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=SV2_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
    obj.visibility=v; }
}

function SV2_popupMsg(msg) { //v1.0
  alert(msg);
}

function SV2_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

SV2_showHideLayers('menuLayer?header','','hide');
SV2_showHideLayers('blankLayer?header','','hide');
SV2_showHideLayers('linkLayer?header','','hide');
SV2_showHideLayers('newsletterMenu?header','','hide');
SV2_showHideLayers('cartMenu?header','','hide');
SV2_showHideLayers('calendarMenu?header','','hide');
SV2_showHideLayers('databaseMenu?header','','hide');
SV2_showHideLayers('secureMenu?header','','show');

</script>
</head>
<body bgcolor=white text=black link=red vlink=red alink=red leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>
<div id="userOpsLayer" style="position:absolute; visibility:visible; left:0px; top:0px; width:100%; height:100%; z-index:1; overflow: auto; border: 1px none #000000"> 
  <table border=0 cellpadding=1 cellspacing=2 bgcolor=white width=725 height=80 align=center>
    <tr> 
      <td align=center valign=top> 
        <table border="0" cellspacing="0" cellpadding="3" width="100%" height="60" bgcolor="white">
          <tr> 
            <td align="center" valign="middle"> 
              <DIV ALIGN="LEFT"><font face=Arial, Helvetica, sans-serif size=2 color=black> 
                All security functions work from a given security code. A security 
                code can be any combination of letters you choose and your end-user 
                never sees or needs to know these security codes. Think of them 
                as &quot;Groups&quot;.<BR>
                <BR>
                Each individual user can be assigned to a security code group. 
                This way, when assigning access to a page or shopping cart sku, 
                you will assign which security code (group) can have access to 
                that page or sku.</font></DIV>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr> 
      <td align="center" valign="middle" colspan="2"> 
        <table width="100%" height=80 border="0" cellspacing="0" cellpadding="5" bgcolor="captiontext" align=center>
          <tr> 
            <td align=center valign=middle><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><B>
              <?php echo ("<SPAN ID=oInstant>$instructions</SPAN>"); ?>
              </b></font></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</div>
</body>
<HEAD>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
</HEAD>
</html>