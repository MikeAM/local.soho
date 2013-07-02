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

#######################################################
### START HTML/JAVASCRIPT CODE			    ###	
#######################################################

$MOD_TITLE = "Mod Title Goes Here";

?>

<HTML>
<HEAD>
<TITLE>MODULE TEMPLATE</TITLE>

<META HTTP-EQUIV="Content-Type" content="text/html; charset=iso-8859-1">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">

<link rel="stylesheet" href="../../product_gui.css">


<script language="JavaScript">
<!--
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

SV2_showHideLayers('NEWSLETTER_LAYER?header','','hide');
SV2_showHideLayers('MAIN_MENU_LAYER?header','','show');
SV2_showHideLayers('CART_MENU_LAYER?header','','hide');
SV2_showHideLayers('DATABASE_LAYER?header','','hide');
parent.frames.footer.CURPAGENAME.innerHTML = "New Feature Module";

//-->
</script>

</head>

<body bgcolor=white text=black link=darkblue vlink=darkblue alink=darkblue leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>
<DIV ID="userOpsLayer" style="position:absolute; visibility:visible; left:0px; top:0; width:100%; height:100%; z-index:1; overflow: auto; border: 1px none #000000">

<?

####################################################################
### FOR VISUAL CONSISTANCY; WE USE AN HTML TEMPLATE BUILDER FILE 
### LOCATED IN THE /shared FOLDER.  THIS WAY ALL OF OUR MODULE 
### INTERFACES LOOK THE SAME. YOU MUST SUPPLY THE VARIABLES:
###
### $MOD_TITLE		Title of this Module
### $THIS_DISPLAY		HTML Content to display to end user
### $BG 			Background Image for content table if used
###
### THIS SAME METHOD SHOULD BE USED WHEN BUILDING ANY OF YOUR OWN 
### CUSTOM MODULES.  REMEMBER TO INCLUDE THE HEADER "INCLUDES"
### ABOVE FOR PROPER FUNCTIONALITY WITHIN THE APPLICAITON.
####################################################################

include("shared/html_build.php");

####################################################################

?>
 
</div>

</body>
<HEAD>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE"></HEAD>
</html>