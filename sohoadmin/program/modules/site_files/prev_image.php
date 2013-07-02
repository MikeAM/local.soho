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

?>

<html>
<head>

<? $show = eregi_replace("images/", "", $image); ?>

<title><? echo $show; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../soholaunch.css">

<?

$tmp_file = "$doc_root/$image";
$size_array = getImageSize("$tmp_file");
$IW = $size_array[0];
$IH = $size_array[1];

$pw = $IW;
$ph = $IH;

echo ("\n\n<!-- w: $IW  h: $IH -->\n\n");

$IW = $IW + 50;
$IH = $IH + 110;

?>

<script language="JavaScript">

	// ----------------------------------------------------------
	// Center Product Window and Expand to 100%
	// ----------------------------------------------------------

		var testw = <? echo $IW; ?>;
		var testh = <? echo $IH; ?>;
		var width = (screen.width);
		var height = (screen.height - 25);

		if (testw > width) { testw = width; }
		if (testh > height) { testh = height; }

		var centerleft = (width/2) - (testw/2);
		var centertop = (height/2) - (testh/2);
		var width=testw;
		var height=testh;
 		window.resizeTo(width, height);
 		window.moveTo(centerleft,centertop);

		window.focus();

	// ----------------------------------------------------------

</script>
</head>

<body bgcolor="#EFEFEF" text="black" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div id="imagelayer" style="position:absolute; left:0px; top:0px; width:100%; height:100%; z-index:5; overflow: auto;"> 

<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%" align="center" VSPACE="0" HSPACE="0">
  <tr> 
    <td align=left valign=top> 
	 <table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%" align="center" bgcolor="#EFEFEF" VSPACE="0" HSPACE="0">
	   
	   <tr> 
		<td valign="top" align="center"> <! -- ## HEADER SETUP ## --> 
		  <table cellpadding="10" cellspacing="0" width="100%" height="100%">
		    <tr align="CENTER" valign="top"> 
			 <td width="100%" colspan="2" class=catselect> 
			   <div align="center">
			    <? 
			    if ( $pw != "" && $ph != "" ) {
			       echo "<img src=\"http://$this_ip/".$image."\" width=$pw height=$ph border=1>\n"; 
			    } else {
			       echo "<img src=\"http://$this_ip/".$image."\" border=\"1\">\n";
			    }
			    ?>
			    <BR clear=all>			
				 <BR>
				 <INPUT TYPE="button" VALUE="Close" CLASS="FormLt1" onclick="javascript:self.close();">
			   </div>
			 </td>
		    </tr>
		  </table>
		</td>
	 </table>
  </tr>
</table>

</div>
</body>
</html>
