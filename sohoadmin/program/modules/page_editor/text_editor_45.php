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

include($_SESSION['product_gui']);
track_vars;

?>

<html>
<head>
<title>Text Editor</title>
<link rel="stylesheet" href="soholaunch.css">

<SCRIPT LANGUAGE=Javascript>

	// Center Editor Window and Make sure it is in Focus
	// ---------------------------------------------------
	var width = (screen.width/2) - (750/2);
	var height = (screen.height/2) - (500/2);
	window.moveTo(width,height);
	window.focus();

</SCRIPT>

</HEAD>
<BODY BGCOLOR="#ECE9D8" TEXT="BLACK" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100% HEIGHT=100% ALIGN=CENTER STYLE='border:1px solid black;background:#ECE9D8;'>
<TR>
<TD ALIGN=CENTER VALIGN=TOP>
	<FORM NAME="SOHOEDITOR" METHOD="post">
	<TEXTAREA NAME="message" STYLE="display: none;"></TEXTAREA>

	<script language="javascript">

	// Create Submit Form Function - Place all Data into drag-n-drop area of page editor
	function Submit() {
	  var final = document.all.editBox.html;
	  var re = /href/g; 
	  var final = final.replace(re, "SOHOLINK"); //Replace "outer" with "inner".
	  var re = new RegExp("<SPAN id=SOHOTEXTSTART>","gi"); 
	  var final = final.replace(re, " ");
	  var re = new RegExp("</SPAN>","gi");
	  var final = final.replace(re, " ");
	  var re = new RegExp("<!-- ~~~ -->","gi");
	  var final = final.replace(re, " ");
	  
	  // Mantis #333
	  //var re = new RegExp("~","gi");
	  //var final = final.replace(re, "-");
	  
	  var thisFinal = "<SPAN ID=SOHOTEXTSTART>"+final+"</SPAN>";
	  document.all.message.value = thisFinal;
	  <? 
	  echo "opener.top.frames.body.$curtext.innerHTML = document.all.message.value;\n"; 
	  
	  # Stick editor content in blog entry's textarea
	  if ( $blogForm != "" ) {
	     echo "opener.top.frames.body.document.".$blogForm.".".$blogBox.".value = document.all.message.value;";
	     echo "opener.top.frames.body.document.".$blogForm.".save_btn.style.display = 'block';";
	  }
	  
	  ?>
	  //opener.top.frames.body.document.blog_entry1.hiddenbox1.value = document.all.message.value;
	  self.close();
	}
	</SCRIPT>
	
	<SCRIPT FOR=editBox EVENT=onreadystatechange>
	if (document.all.editBox.readyState==4) {
		bLoad=true;
	}
	</SCRIPT>
	
	<? 
	
	# Main Text Editor box
	//echo "<iframe ID=\"editBox\" WIDTH=98% HEIGHT=415 src=\"text_editor_obj_45.php?blogForm=$blogFrom&blogBox=$blogBox&curtext=$curtext&=SID\"></iframe>\n"; 
	echo "<OBJECT ID=editBox WIDTH=98% HEIGHT=415 DATA=\"text_editor_obj_45.php?blogForm=$blogFrom&blogBox=$blogBox&curtext=$curtext&=SID\" TYPE=\"text/x-scriptlet\"></OBJECT>\n"; 
	
	?>
	
	<INPUT TYPE=button VALUE="Update Text" style='width: 150px;' class="FormLt1" onclick="Submit();">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<INPUT TYPE=button VALUE="Cancel Update" class="FormLt1" style='width: 150px;' onclick="javascript: self.close();">
	</FORM>
	
</TD>
</TR>
</TABLE>
</BODY></HTML>