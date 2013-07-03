<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.9
##
## Author: 			Mike Morrison
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugz.soholaunch.com
## Release Notes:	http://wiki.soholaunch.com
###############################################################################
##############################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2007 Soholaunch.com, Inc.  All Rights Reserved.
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

require_once("../../includes/product_gui.php");
?>

<html>
<head>
<title>Form Preview</title>

<style>
* {
   font-family: Trebuchet MS, arail, helvetica, sans-serif;
}
body {
   background-color: #fff;
}
#preview-container {
   padding: 5px 50px;
   text-align: center;
}
</style>

</head>

<link rel="stylesheet" type="text/css" href="../../smt_module.css"/>

<body>
<div id="preview-container">
 <table border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
   <td align="center">
<?
# Read passed form file and display
if ( $_GET['formfile'] != "" ) {
   $formHTML = file_get_contents($_GET['formfile']);
	$formHTML = str_replace("pgm-form_submit.php", "preview_form.php", $formHTML);
	echo $formHTML;

} else {
   echo "Form Preview Window";
}
?>
   </td>
  </tr>
 </table>
</div>
</body>
</html>