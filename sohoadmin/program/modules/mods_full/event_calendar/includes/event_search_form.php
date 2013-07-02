<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();

$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../../../../includes/product_gui.php');
chdir($curdir);
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
<style>

form {
   margin:0;
}

.edit_event {
   width: 15px;
   border-right: 1px solid #A2ADBC;
   border-bottom: 1px solid #A2ADBC;
   background: #E0EFE2;
   float: left;
   padding:0;
   text-align: center;
   /*display: none;*/
   margin:0;
   cursor: pointer;
}

.edit_event_over {
   width: 15px;
   border-right: 1px solid #A2ADBC;
   border-bottom: 1px solid #A2ADBC;
   background: #A7DFAF;
   float: left;
   padding:0;
   text-align: center;
   /*display: none;*/
   margin:0;
   cursor: pointer;
}

.calendar_search_contain {
	/*width: 141px;*/
	padding: 0;
	margin: 0;
	border-left: 1px solid #A2ADBC;
	border-bottom: 1px solid #A2ADBC;
	font: normal 12px/20px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	color: #33393F;
	text-align: center;
	background-color: #fff;
}

.calendar_search_contain th {
	font: bold 11px/20px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	/*color: #4D565F;*/
	background: #D9E2E1;
	border-right: 1px solid #A2ADBC;
	border-bottom: 1px solid #A2ADBC;
	border-top: 1px solid #A2ADBC;
	padding:2;
}

.calendar_search_contain td {
   padding-bottom:7px;
	/*border-right: 1px solid #A2ADBC;*/
	/*border-bottom: 1px solid #A2ADBC;*/
	/*text-align: center;*/
}

.cal_btn {
   margin:0;
   padding-top:1px;
   padding-bottom:1px;
   text-align: center;
   border: 2px outset #CFCFCF;
   /*border: 1px dashed red;*/
   cursor: pointer;
   background: #A7DFAF;
   /*width: 100%;*/
}

.cal_btn_over {
   padding-top:1px;
   padding-bottom:1px;
   text-align: center;
   border: 2px outset #AFFFBA;
   cursor: pointer;
   background: #6FDF7E;
   /*width: 100%;*/
}


</style>

<table width="750" border="0" cellspacing="0" cellpadding="5" class="calendar_search_contain">
  <tr> 
    <th colspan="3" align="left" valign="top"><? echo $lang["Search Event Calendar"]; ?>:</th>
  </tr>
  <tr> 
    <td width="50%" ><? echo $lang["Search for Keywords"]; ?>:<BR> <INPUT TYPE="text" NAME="SEARCH_KEYWORDS" CLASS="text" style='width: 350px;'> 
    </td>
    <td ><? echo $lang["Search in Month/Year"]; ?>:<BR> 
	
	<select name="SEARCH_MONTH" class="text">
        <option value="" SELECTED><? echo $lang["All"]; ?></OPTION>
		<?
		
		for ($x=1;$x<=12;$x++) {
			$val = date("m", mktime(0,0,0,$x,1,2002));
			$disp = date("F", mktime(0,0,0,$x,1,2002));
			echo "<OPTION VALUE=\"$val\">$disp</OPTION>\n";
		}
		
		?>
    </SELECT>
	
	<SELECT NAME="SEARCH_YEAR">
        <OPTION VALUE="" SELECTED><? echo $lang["All"]; ?></OPTION>
		<?
		
		for ($x=2002;$x<=2015;$x++) {
			echo "<OPTION VALUE=\"$x\">$x</OPTION>\n";
		}
		
		?>    
	</SELECT> 
	
	</TD>
 
    <TD style="border-right: 1px solid #A2ADBC;"><? echo $lang["Search In Category"]; ?>:<BR> 
	
	<SELECT NAME="SEARCH_CATEGORY" CLASS="text">
        <OPTION VALUE="" SELECTED><? echo $lang["All"]; ?></OPTION>
		<?
		
		$result = mysql_query("SELECT * FROM calendar_category ORDER BY Category_Name");
		while ($row = mysql_fetch_array($result)) {
			echo "<OPTION VALUE=\"$row[PRIKEY]\">$row[Category_Name]</OPTION>\n";
		}
		
		?>
		
    </SELECT>
	  
	 </td>
	 </tr>
	 <tr>
      <td colspan="3" align="center" style="border-right: 1px solid #A2ADBC;">
     <button onClick="document.search_events_form.submit();" type="button" class="greenButton"><span><span>Search Now</span></span></button>
      </td>
  </tr>
</table>
