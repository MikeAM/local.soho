<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


error_reporting(E_PARSE);

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
include_once("sohoadmin/client_files/pgm-site_config.php");
include_once("sohoadmin/program/includes/shared_functions.php");
$statsObj = new userdata('global');


if($statsObj->get('disable-stats')!='disable'){	
	# EXPLOIT PREVENTION: Skip all of this if page request is url
	if ( !eregi("^http", $_REQUEST['pr']) ) {
	
	// **********************************************************
	// STEP 1: SETUP MONTH & TIME VARIABLES FOR THIS TRANSACTION
	// **********************************************************
	
	$st_db_tDATE = date("Y-m-d");
	$st_db_tMONTH = date("F");
	$st_db_tHOUR = date("H");
	$st_db_tDOW = date("j");
	$st_db_tYEAR = date("Y");
	
	// **********************************************************
	// STEP 2: READ CLIENT DATA WHEN AVAILABLE
	// **********************************************************
	
	// ------------------------------------------------------------------------------------
	// $statpage :: Tells the system WHAT to log as a hit; unique or otherwise.  This is
	//              mostly for use within other modules.  Oddly enough, we must compensate
	//              for database searches and calendar searches (via includes) through the
	//              normal page builder pgm.  So let's make sure it takes precedence over
	//              the "$pageRequest" variable.
	// ------------------------------------------------------------------------------------
	
	if ($statpage == "") {	$st_db_pgNAME = $pageRequest; } else { $st_db_pgNAME = $statpage; }
	
	$st_db_ipADDR = $REMOTE_ADDR;			// Site Visitor IP
	$st_db_REFER = $HTTP_REFERER;			// Refering Website
	$st_db_tBROWSER = $HTTP_USER_AGENT;		// Browser In Use
	
	$real_date = date("Y-m-d");				// Format for SQL date field (today)
	
	if (eregi("$dot_com", $st_db_REFER)) { $st_db_REFER = "(Internal)"; }		// If referer contains dot_com var, this was an internal link
	if (eregi("$this_ip", $st_db_REFER)) { $st_db_REFER = "(Internal)"; }		// If referer contains server ipaddr, this was an internal link
	if ($st_db_REFER == "") { $st_db_REFER = "(Direct)"; } 				// If no referer var is present; must have typed URL in browser
	if ($st_db_pgNAME == "") { $st_db_pgNAME = startpage(false); } 				// If not pr var was passed; must have been direct entry
	$st_db_pgNAME = eregi_replace("_", " ", $st_db_pgNAME);				// Replace any underscores in Page Name Var
	
		###########################################
		## SPLIT REFERER URL INTO MAIN DNS FOR LOG
		###########################################
	
		// Commented out for V4 - We want to see what "Searches" were performed
		// When coming from search engines
	
	
		// if (eregi("http://", $st_db_REFER)) {
		// 	$st_db_tmp = split("/", $st_db_REFER);
		// 	$st_db_REFER = $st_db_tmp[0] . "//" . $st_db_tmp[2];
		// }
	
	
	
	// **********************************************************
	// STEP 3: IF PAGE WAS ACCESSED WITH #; CONVERT TO NAME
	// **********************************************************
	
		$l = strlen($st_db_pgNAME);
		$zz = 0;
		$st_db_tmpman = 0;
		while($zz != $l) {
			$stdb_temp = substr($st_db_pgNAME, $zz, 1);
			if (eregi("[0-9]", $stdb_temp)) { $st_db_tmpman++; }
			$zz++;
		}
	
		if ($st_db_tmpman == $l) {
			$realname = mysql_query("SELECT page_name, url_name FROM site_pages WHERE link = '$st_db_pgNAME'");
			while ($joe = mysql_fetch_array ($realname)) {
				$st_db_pgNAME = $joe["page_name"];
			}
		}
	
	// *****************************************************************
	// STEP 4: HAS THE NEW DATATABLES BEEN CREATED? IF NOT, CREATE THEM
	// *****************************************************************
	
		// Version 4.0 adds the SESSION field to our log tables for more
		// acurate tracking.  This is the first version to utilize sessions
		// to track movement throughout the entire web site.
	
		// mysql_query("DROP TABLE stats_top25");
		// mysql_query("DROP TABLE stats_byday");
		// mysql_query("DROP TABLE stats_byhour");
		// mysql_query("DROP TABLE stats_refer");
		// mysql_query("DROP TABLE stats_unique");
		// mysql_query("DROP TABLE stats_browser");
	
	//	$st_db_result = mysql_list_tables($db_name);
	//	$st_db_match = 0;
	//	$stdb_i = 0;
	//	while ($stdb_i <= mysql_num_rows ($st_db_result)) {
	//		$tb_names[$stdb_i] = mysql_tablename($st_db_result, $stdb_i);
	//		if ($tb_names[$stdb_i] == "stats_top25") { $st_db_match++; }
	//		if ($tb_names[$stdb_i] == "stats_byday") { $st_db_match++; }
	//		if ($tb_names[$stdb_i] == "stats_byhour") { $st_db_match++; }
	//		if ($tb_names[$stdb_i] == "stats_refer") { $st_db_match++; }
	//		if ($tb_names[$stdb_i] == "stats_unique") { $st_db_match++; }
	//		if ($tb_names[$stdb_i] == "stats_browser") { $st_db_match++; }
	//		$stdb_i++;
	//	}
	//
	//	if ($st_db_match != 6) {
	//
	//	mysql_db_query("$db_name","CREATE TABLE stats_top25 (PriKey INT NOT NULL AUTO_INCREMENT PRIMARY KEY,Month CHAR(25),Year INT(4),Page CHAR(25), Hits INT(25), SESSION CHAR(255), Real_Date DATE)");
	//	mysql_db_query("$db_name","CREATE TABLE stats_byday (PriKey INT NOT NULL AUTO_INCREMENT PRIMARY KEY,Month CHAR(25),Year INT(4),Day CHAR(25), Hits INT(25), SESSION CHAR(255), Real_Date DATE)");
	//	mysql_db_query("$db_name","CREATE TABLE stats_byhour (PriKey INT NOT NULL AUTO_INCREMENT PRIMARY KEY,Month CHAR(25),Year INT(4),Hour CHAR(25), Hits INT(25), SESSION CHAR(255), Real_Date DATE)");
	//	mysql_db_query("$db_name","CREATE TABLE stats_refer (PriKey INT NOT NULL AUTO_INCREMENT PRIMARY KEY,Month CHAR(25),Year INT(4),Refer CHAR(255), Hits INT(25), SESSION CHAR(255), Real_Date DATE)");
	//	mysql_db_query("$db_name","CREATE TABLE stats_unique (PriKey INT NOT NULL AUTO_INCREMENT PRIMARY KEY,Month CHAR(25),Year INT(4), IP CHAR(25),Hour CHAR(25), Hits INT(25), Browser CHAR(100), SESSION CHAR(255), Real_Date DATE)");
	//	mysql_db_query("$db_name","CREATE TABLE stats_browser (PriKey INT NOT NULL AUTO_INCREMENT PRIMARY KEY,Month CHAR(25),Year INT(4),Browser CHAR(255), Hits INT(25), SESSION CHAR(255), Real_Date DATE)");
	//
	//	}
	if(!table_exists('stats_top25')){
		create_table('stats_top25');
	}
	if(!table_exists('stats_byday')){
		create_table('stats_byday');
	}
	if(!table_exists('stats_byhour')){
		create_table('stats_byhour');
	}
	if(!table_exists('stats_refer')){
		create_table('stats_refer');
	}
	if(!table_exists('stats_unique')){
		create_table('stats_unique');
	}
	if(!table_exists('stats_browser')){
		create_table('stats_browser');
	}
	
	
	######## IF Logged into Editor Do not add to stats
	if(!isset($_SESSION['PHP_AUTH_USER'])){
		
		//#+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++#
		//# INSERTED: START of modified code to add max, min and avg users per hour
		//#+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++#
		//	$fields = mysql_list_fields($db_name, 'stats_byhour');
		//	$columns = mysql_num_fields($fields);
		//	for ($i = 0; $i < $columns; $i++) {$field_array[] = mysql_field_name($fields, $i);}
		//
		//	if (!in_array('Max_Users', $field_array))
		//	{
		//		$result = mysql_query("ALTER TABLE stats_byhour ADD Min_Users INT(8)");
		//		$result = mysql_query("ALTER TABLE stats_byhour ADD Max_Users INT(8)");
		//		$result = mysql_query("ALTER TABLE stats_byhour ADD Avg_Users FLOAT");
		//	}
		#+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++#
		# END of modified code to add max, min and avg users per hour
		#+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++#
		
		// *****************************************************************
		// STEP 5: CALCULATE AND dB TOP 25 PAGE TABLE
		// *****************************************************************
		
			$st_db_pgNAME = substr($st_db_pgNAME, 0, 25);
		
			## FIRST, PULL THIS MONTH AND THIS PAGENAME TOTAL HITS
			#######################################################
		
			$st_db_result = mysql_query("SELECT PriKey, Hits FROM stats_top25 WHERE Page = '$st_db_pgNAME' AND Month = '$st_db_tMONTH' AND Year = '$st_db_tYEAR'");
			$st_db_rtn = mysql_num_rows($st_db_result);
		
			## IF THIS PAGE EXISTS IN TABLE, UPDATE RECORD TO REFLECT NEW HIT
			##################################################################
		
			if ($st_db_rtn > 0) {
				while ($st_db_row = mysql_fetch_array ($st_db_result)) {
					$stdb_PriKey = $st_db_row[PriKey];
					$dB_Cur_Num = $st_db_row[Hits];
				}
				$dB_Cur_Num++;
		
				mysql_query("UPDATE stats_top25 SET Hits = '$dB_Cur_Num' WHERE PriKey = '$stdb_PriKey'");
		
			} else {
		
			## IF PAGE DOES NOT EXIST, CREATE NEW RECORD ENTRY AND COUNT
			#############################################################
		
			mysql_query("INSERT INTO stats_top25 VALUES('NULL','$st_db_tMONTH','$st_db_tYEAR','$st_db_pgNAME','1','$PHPSESSID','$real_date')");
		
			}
		
		// *****************************************************************
		// STEP 6: CALCULATE AND dB BY DAY STATS TABLE
		// *****************************************************************
		
			## FIRST, PULL THIS MONTH AND THIS DAY TOTAL HITS
			#######################################################
		
			$st_db_result = mysql_query("SELECT PriKey, Hits FROM stats_byday WHERE Day = '$st_db_tDOW' AND Month = '$st_db_tMONTH' AND Year = '$st_db_tYEAR'");
			$st_db_rtn = mysql_num_rows($st_db_result);
		
			## IF THIS DAY EXISTS IN TABLE, UPDATE RECORD TO REFLECT NEW HIT
			##################################################################
		
			if ($st_db_rtn > 0) {
				while ($st_db_row = mysql_fetch_array ($st_db_result)) {
					$stdb_PriKey = $st_db_row[PriKey];
					$dB_Cur_Num = $st_db_row[Hits];
				}
				$dB_Cur_Num++;
				mysql_query("UPDATE stats_byday SET Hits = '$dB_Cur_Num' WHERE PriKey = '$stdb_PriKey'");
			} else {
		
			## IF DAY DOES NOT EXIST, CREATE NEW RECORD ENTRY AND COUNT
			#############################################################
		
			mysql_query("INSERT INTO stats_byday VALUES('NULL','$st_db_tMONTH','$st_db_tYEAR','$st_db_tDOW','1','$PHPSESSID','$real_date')");
		
			}
		
		// *****************************************************************
		// STEP 7: CALCULATE AND dB BY HOUR STATS TABLE
		// *****************************************************************
		
			## FIRST, PULL THIS MONTH AND THIS HOUR TOTAL HITS
			#######################################################
		
		//	$st_db_result = mysql_query("SELECT * FROM stats_byhour WHERE Hour = '$st_db_tHOUR' AND Month = '$st_db_tMONTH' AND Year = '$st_db_tYEAR'");
			$st_db_result = mysql_query("SELECT * FROM stats_byhour WHERE Hour = '$st_db_tHOUR' AND Real_Date = '$st_db_tDATE'");
			$st_db_rtn = mysql_num_rows($st_db_result);
		
			## IF THIS HOUR EXISTS IN TABLE, UPDATE RECORD TO REFLECT NEW HIT
			##################################################################
		
			if ($st_db_rtn > 0) {
				while ($st_db_row = mysql_fetch_array ($st_db_result)) {
					$stdb_PriKey = $st_db_row[PriKey];
					$dB_Cur_Num = $st_db_row[Hits];
		
		#+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++#
		# REPLACED: START of replacement code to add max, min and avg users per hour
		#+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++#
		
				$db_Avg_Users = (( $st_db_row[Avg_Users] * $st_db_row[Hits] ) + $useronline ) / ( $st_db_row[Hits] + 1 );
				$db_Min_Users = min($useronline, $st_db_row[Min_Users]);
				$db_Max_Users = max($useronline, $st_db_row[Max_Users]);
			}
			$dB_Cur_Num++;
			mysql_query("UPDATE stats_byhour SET Hits = '$dB_Cur_Num', Min_Users = '$db_Min_Users',
						Max_Users = '$db_Max_Users', Avg_Users = '$db_Avg_Users' WHERE PriKey = '$stdb_PriKey'") or die (mysql_error());
			} else {
		
			## IF HOUR DOES NOT EXIST, CREATE NEW RECORD ENTRY AND COUNT
			#############################################################
		
			mysql_query("INSERT INTO stats_byhour VALUES('NULL','$st_db_tMONTH','$st_db_tYEAR','$st_db_tHOUR','1','$PHPSESSID','$real_date','$useronline','$useronline','$useronline')") or die (mysql_error());
		
		#+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++#
		# END of replacement code to add max, min and avg users per hour
		#+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++#
		
			}
		
		// *****************************************************************
		// STEP 8: CALCULATE AND dB REFERRER STATS TABLE
		// *****************************************************************
		
			## FIRST, PULL THIS MONTH AND THIS REFERRERS TOTAL HITS
			########################################################
		
			$st_db_result = mysql_query("SELECT PriKey, Hits FROM stats_refer WHERE Refer = '$st_db_REFER' AND Month = '$st_db_tMONTH' AND Year = '$st_db_tYEAR'");
			$st_db_rtn = mysql_num_rows($st_db_result);
		
			## IF THIS REF EXISTS IN TABLE, UPDATE RECORD TO REFLECT NEW HIT
			##################################################################
		
			if ($st_db_rtn > 0) {
				while ($st_db_row = mysql_fetch_array ($st_db_result)) {
					$stdb_PriKey = $st_db_row[PriKey];
					$dB_Cur_Num = $st_db_row[Hits];
				}
				$dB_Cur_Num++;
				mysql_query("UPDATE stats_refer SET Hits = '$dB_Cur_Num' WHERE PriKey = '$stdb_PriKey'");
		
			} else {
		
			## IF PAGE DOES NOT EXIST, CREATE NEW RECORD ENTRY AND COUNT
			#############################################################
		
			mysql_query("INSERT INTO stats_refer VALUES('NULL','$st_db_tMONTH','$st_db_tYEAR','$st_db_REFER','1','$PHPSESSID','$real_date')");
		
			}
		
		// *****************************************************************
		// STEP 9: CALCULATE AND dB UNIQE VISITOR NUMBERS
		// *****************************************************************
		
		
			## FIRST, PULL THIS MONTH AND THIS REFERRERS TOTAL HITS
			########################################################
		
			$st_db_result = mysql_query("SELECT PriKey, Hits FROM stats_unique WHERE IP = '$st_db_ipADDR' AND Month = '$st_db_tMONTH' AND Year = '$st_db_tYEAR' AND Hour = '$st_db_tHOUR'");
			$st_db_rtn = mysql_num_rows($st_db_result);
		
			## IF THIS REF EXISTS IN TABLE, UPDATE RECORD TO REFLECT NEW HIT
			##################################################################
		
			if ($st_db_rtn > 0) {
				while ($st_db_row = mysql_fetch_array ($st_db_result)) {
					$stdb_PriKey = $st_db_row[PriKey];
					$dB_Cur_Num = $st_db_row[Hits];
				}
				$dB_Cur_Num++;
				mysql_query("UPDATE stats_unique SET Hits = '$dB_Cur_Num' WHERE PriKey = '$stdb_PriKey'");
		
			} else {
		
			## IF PAGE DOES NOT EXIST, CREATE NEW RECORD ENTRY AND COUNT
			#############################################################
		
			mysql_query("INSERT INTO stats_unique VALUES('NULL','$st_db_tMONTH','$st_db_tYEAR','$st_db_ipADDR','$st_db_tHOUR','1','$st_db_tBROWSER','$PHPSESSID','$real_date')");
		
			}
		
		// *****************************************************************
		// STEP 10: CALCULATE AND dB BROWSER NUMBERS
		// *****************************************************************
		
		
			## FIRST, PULL THIS MONTH AND THIS REFERRERS TOTAL HITS
			########################################################
		
			$st_db_result = mysql_query("SELECT PriKey, Hits FROM stats_browser WHERE Browser = '$st_db_tBROWSER' AND Month = '$st_db_tMONTH' AND Year = '$st_db_tYEAR'");
			$st_db_rtn = mysql_num_rows($st_db_result);
		
			## IF THIS REF EXISTS IN TABLE, UPDATE RECORD TO REFLECT NEW HIT
			##################################################################
		
			if ($st_db_rtn > 0) {
				while ($st_db_row = mysql_fetch_array ($st_db_result)) {
					$stdb_PriKey = $st_db_row[PriKey];
					$dB_Cur_Num = $st_db_row[Hits];
				}
				$dB_Cur_Num++;
				mysql_query("UPDATE stats_browser SET Hits = '$dB_Cur_Num' WHERE PriKey = '$stdb_PriKey'");
		
			} else {
		
			## IF PAGE DOES NOT EXIST, CREATE NEW RECORD ENTRY AND COUNT
			#############################################################
		
			mysql_query("INSERT INTO stats_browser VALUES('NULL','$st_db_tMONTH','$st_db_tYEAR','$st_db_tBROWSER','1','$PHPSESSID','$real_date')");
		
			}
				
		}
		### End Authorized User Check
	
	} // End if !eregi(http, pr)
}
?>