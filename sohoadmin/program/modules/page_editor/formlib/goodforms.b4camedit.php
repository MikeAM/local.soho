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

require_once('../../../includes/product_gui.php');
if ($selkey == "Forms") {
	$this_title = "FORMS LIBRARY";
} else {
	$this_title = "NEWSLETTER SIGNUP";
}

?>

<HTML>
<HEAD>
<TITLE><? echo "$this_title"; ?></TITLE>
</HEAD>


<script language=javascript>
	var width = (screen.width/2) - (650/2);
	var height = (screen.height/2) - (500/2);
	window.focus();
	

var browser=navigator.appName
var b_version=navigator.appVersion
var version=parseFloat(b_version)

var is_ie7 = b_version.indexOf('MSIE 7');


function goGetTrue() {
   //alert(is_ie7)
   if(document.all && is_ie7 == -1){
      dataTrueForm = window.opener.getCurrentTrue();
   }else{
      dataTrueForm = parent.getCurrentTrue();
   }
}

function goGetData() {
   if(document.all && is_ie7 == -1){ 
      dataDataForm = window.opener.getCurrentCont();
   }else{
      dataDataForm = parent.getCurrentCont();
   }
}
   
function sendFormToId(TheCont) {
   if(document.all && is_ie7 == -1){ 
      window.opener.ckFunt(TheCont);
   }else{
      parent.ckFunt(TheCont);
   }
}   

function closeMe() {
   if(document.all && is_ie7 == -1){ 
      window.close();
   }else{
      parent.closeit();
   }
}
function callCellWhite() {
   if(document.all && is_ie7 == -1){ 
      window.opener.cellWhite();
   }else{
      parent.cellWhite();
   }   
}
   
</script>

<?php

echo '
<frameset rows="350,*" cols="*" border=0> 
	<frame src="selection.php?dropArea='.$dropArea.'&selkey='.$selkey.'&'.SID.'" name="formtop" scrolling="NO" marginwidth="0" marginheight="0" leftmargin="0" topmargin="0" noresize frameborder="NO">
	<frame src="preview.php?dropArea='.$dropArea.'&'.SID.'" id="formpreview" name="formpreview" scrolling="AUTO" marginwidth="0" marginheight="0" leftmargin="0" topmargin="0" noresize frameborder="NO">
</frameset>';

?>


<noframes>
	<body bgcolor="#FFFFFF">
	</body>
</noframes>
</html>