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
## Copyright 1999-2006 Soholaunch.com, Inc. and Mike Johnston  
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
if($_REQUEST['selkey']!=''){
	$selkey=$_REQUEST['selkey'];
}
if ($selkey == "Forms") {
	$this_title = "FORMS LIBRARY";
} else {
	$this_title = "NEWSLETTER SIGNUP";
}

?>


<script language=javascript>
	var width = (screen.width/2) - (650/2);
	var height = (screen.height/2) - (500/2);
	window.focus();
	   
	
function goGetTrue() {
   dataTrueForm = getCurrentTrue();
}

function goGetData() {
   dataDataForm = getCurrentCont();
}
   
function sendFormToId(TheCont) {
   ckFunt(TheCont);
}   

function closeMe() {
   closeit();
}

function sendEditVals(editType) {
   alert('yay');
   
}
   
</script>

   <div id="selection" style="height:50%;min-height:330px;" ><a href="#">selection</a></div>
   
   <div id="preview" style="height:50%;overflow:auto;">preview</div>


<script language="javascript">

//parent.joeFun();

</script>


<?php

//echo '
//<frameset rows="350,*" cols="*" border=0> 
//	<frame src="selection.php?dropArea='.$dropArea.'&selkey='.$selkey.'&'.SID.'" name="formtop" scrolling="NO" marginwidth="0" marginheight="0" leftmargin="0" topmargin="0" noresize frameborder="NO">
//	<frame src="preview.php?dropArea='.$dropArea.'&'.SID.'" id="formpreview" name="formpreview" scrolling="AUTO" marginwidth="0" marginheight="0" leftmargin="0" topmargin="0" noresize frameborder="NO">
//</frameset>';

?>

