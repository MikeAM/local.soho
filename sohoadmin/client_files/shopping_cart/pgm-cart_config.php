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

############################################################################
### Define where the site configuration file is located for all OS's	 ###
############################################################################

include("../sohoadmin/includes/config.php");
include("../sohoadmin/includes/db_connect.php");
include_once("../sohoadmin/program/includes/shared_functions.php");

if(!table_exists("cart_category")){
	create_table("cart_category");
}

$resultc = mysql_query("SHOW COLUMNS FROM cart_category LIKE 'sortorder'");
if(mysql_num_rows($resultc)==0){
	mysql_query("alter table cart_category add column sortorder int(20) not null default '999' after product_count");	
}

?>