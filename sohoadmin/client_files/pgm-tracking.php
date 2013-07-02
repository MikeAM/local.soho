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

error_reporting(0);
track_vars;
include_once("pgm-site_config.php");
Header("Content-type: image/gif");

#############################################################################################
## First, Create Dummy Image for inclusion
#############################################################################################


$filename = "spacer.gif";
$fp = fopen($filename, 'r');
	$img_data = fread($fp, filesize($filename));
fclose($fp);

#############################################################################################
## INCREMENT CLICK-THRU COUNT BY ONE AND UPDATE DATA TABLE FOR THIS CAMPAIGN
#############################################################################################
$id = $_REQUEST['id'];
if(!preg_match('/^[0-9]{1,}$/i', $id) && $id!=""){
	exit;
}


$result = mysql_query("SELECT CLICK_THRU_CNT FROM CAMPAIGN_MANAGER WHERE PRIKEY = '$id'");
$row = mysql_fetch_array($result);

$old_count = $row[CLICK_THRU_CNT];
$new_count = $old_count + 1;

mysql_query("UPDATE CAMPAIGN_MANAGER SET CLICK_THRU_CNT = '$new_count'WHERE PRIKEY = '$id'");

#############################################################################################
## HERE YOU CAN ADD ANY STATISTICAL TRACKING DATA THAT YOU WANT
## All we know about this user is that he/she has opened the HTML newsletter and is reading
## it as we speak. -- You could get more "fonzy" like and add "last read" times, etc.
#############################################################################################



#############################################################################################

echo $img_data;

?>