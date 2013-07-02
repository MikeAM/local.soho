<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


#############################################################################################
## Soholaunch(R) Site Management Tool
## Version 4.9
##
## Original Author: 			Mike Johnston [mike.johnston@soholaunch.com]
## Updates Since 2004:     Mike Morrison
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugz.soholaunch.com
## Release Notes:	http://wiki.soholaunch.com/index.php?title=New_Stuff_in_v4.9
#############################################################################################

#############################################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2007 Soholaunch.com, Inc. and Mike Johnston All Rights Reserved.
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
##############################################################################################

session_cache_limiter('none');
session_start();




# Make sure product_gui is included
require_once("../../../includes/product_gui.php");

# Account for tables with keyfields named something besides "PRIKEY"
# Just pull first field
$qry = "SELECT * FROM $mt LIMIT 1";
$rez = mysql_query($qry);
$KEYFIELD = mysql_field_name($rez, 0);


ob_start();
echo "<div id=\"db-maindiv\" style=\"height:80%; width:100%;border:0px solid red;overflow: auto; white-space:nowrap;\">\n";
/*---------------------------------------------------------------------------------------------------------*
 ___                 _        _     _        _    _
/ __| _ __  ___  __ (_) __ _ | |   /_\   __ | |_ (_) ___  _ _   ___
\__ \| '_ \/ -_)/ _|| |/ _` || |  / _ \ / _||  _|| |/ _ \| ' \ (_-<
|___/| .__/\___|\__||_|\__,_||_| /_/ \_\\__| \__||_|\___/|_||_|/__/
     |_|
/*---------------------------------------------------------------------------------------------------------*/
# kill_lastsearch
if ( isset($_GET['kill_lastsearch']) ) {
   $_SESSION[$_REQUEST['mt']]['last_search_term'] = NULL;
   $_SESSION[$_REQUEST['mt']]['searchin'] = NULL;
}


# orderby
if ( isset($_GET['orderby']) ) {
   $_SESSION[$_REQUEST['mt']]['orderby'] = $_GET['orderby'];
   $_SESSION[$_REQUEST['mt']]['orderhow'] = $_GET['orderhow'];
}
# kill_orderby
if ( isset($_GET['kill_orderby']) ) {
   $_SESSION[$_REQUEST['mt']]['orderby'] = NULL;
   $_SESSION[$_REQUEST['mt']]['orderhow'] = NULL;
}
# DEFAULT: order by $KEYFIELD
if ( !isset($_SESSION[$_REQUEST['mt']]['orderby']) ) {
   $_SESSION[$_REQUEST['mt']]['orderby'] = $KEYFIELD;
   $_SESSION[$_REQUEST['mt']]['orderhow'] = "asc";
}


# hide_multi_fields
if ( $_POST['todo'] == "hide_multi_fields" ) {

	$qry = "SHOW COLUMNS FROM ".$mt;
	$fieldrez = mysql_query($qry);
	while ( $getCol = mysql_fetch_assoc($fieldrez) ) {
	   if ( in_array($getCol['Field'], $_POST['multi_hide_fields']) ) {
	      $_SESSION['dtm_hidden_fields'][$_REQUEST['mt']][$getCol['Field']] = "valuedoesntmatter";
	   } else {
	      unset($_SESSION['dtm_hidden_fields'][$_REQUEST['mt']][$getCol['Field']]);
	   }
	}
}

# format_timestamps
if ( $_POST['todo'] == "format_timestamps" ) {
	$qry = "SHOW COLUMNS FROM ".$mt;
	$fieldrez = mysql_query($qry);
	while ( $getCol = mysql_fetch_assoc($fieldrez) ) {
	   if ( in_array($getCol['Field'], $_POST['timestamp_fields']) ) {
	      $_SESSION['timestamp_fields'][$_REQUEST['mt']][$getCol['Field']] = "valuedoesntmatter";
	   } else {
	      unset($_SESSION['timestamp_fields'][$_REQUEST['mt']][$getCol['Field']]);
	   }
	}
	$_SESSION['timestamp_date_format'][$_REQUEST['mt']] = $_POST['timestamp_date_format'];
}

# format_encoded
if ( $_POST['todo'] == "format_decode" ) {
	$qry = "SHOW COLUMNS FROM ".$mt;
	$fieldrez = mysql_query($qry);
	while ( $getCol = mysql_fetch_assoc($fieldrez) ) {
	   if ( in_array($getCol['Field'], $_POST['decode_fields']) ) {
	      $_SESSION['decode_fields'][$_REQUEST['mt']][$getCol['Field']] = "valuedoesntmatter";
	   } else {
	      unset($_SESSION['decode_fields'][$_REQUEST['mt']][$getCol['Field']]);
	   }
	}
	//$_SESSION['decode_format'][$_REQUEST['mt']] = $_POST['timestamp_date_format'];
}

# format_serialized
if ( $_POST['todo'] == "format_serialized" ) {
	$qry = "SHOW COLUMNS FROM ".$mt;
	$fieldrez = mysql_query($qry);
	while ( $getCol = mysql_fetch_assoc($fieldrez) ) {
	   if ( in_array($getCol['Field'], $_POST['serialized_fields']) ) {
	      $_SESSION['serialized_fields'][$_REQUEST['mt']][$getCol['Field']] = "valuedoesntmatter";
	   } else {
	      unset($_SESSION['serialized_fields'][$_REQUEST['mt']][$getCol['Field']]);
	   }
	}
}

# run_qry
# Accepts custom qry in post or hard-coded query link via get
if ( $_POST['todo'] == "run_qry" ) {
   # Store query in session for quick access to last run queries
   # MD5 qry string as array key to prevent duplicates
   $qrykey = md5(trim($_POST['runthis']));
   $_SESSION['dtm_custom_qry'][$qrykey] = $_POST['runthis'];

   # SELECT queries are special
   if ( eregi("^SELECT", $_POST['runthis']) ) {
      $customselectqry = true;
   }

   # Run query
   if ( !mysql_query(stripslashes($_REQUEST['runthis'])) ) {
      echo mysql_error(); exit;

   } else {
//      echo "Query run successfully (or so it appears). This was the query...<br/>\n";
//      echo "<span class=\"mono bold\">".stripslashes($_POST['runthis'])."</span><br/>";
   }
}

# killqry
# Remove custom query string from history (when they click the [x] in the custom query popup layer)
if ( $_REQUEST['todo'] == "killqry" ) {
   $_SESSION['dtm_custom_qry'][$_GET['qryid']] = NULL;
}

# dtm_viewmode
# Change record view mode upon request (i.e. they click the 'hide blob data' link)
# Do this on a per-table basis
if ( isset($_GET['dtm_viewmode']) ) {
   $_SESSION['dtm_viewmode'][$_REQUEST['mt']] = $_GET['dtm_viewmode'];
}

# dtm_collapse
# Show/hide collapse/expand links for each column heading
if ( isset($_GET['dtm_collapse']) ) {
   $_SESSION['dtm_collapse_option'][$_REQUEST['mt']] = $_GET['dtm_collapse'];
}

# collapse_field
# Add to collapsed field array (so column data will be hidden)
if ( isset($_GET['collapse_field']) ) {
   $_SESSION['dtm_collapse_fields'][$_REQUEST['mt']][$_GET['collapse_field']] = "valuedoesntmatter";
}

# expand_field
# Remove from collapsed field array (so column data will be shown)
if ( isset($_GET['expand_field']) ) {
   unset($_SESSION['dtm_collapse_fields'][$_REQUEST['mt']][$_GET['expand_field']]);
}

# expand_all
# Expand any/all collapsed columns, show any hidden columns
if ( $_GET['todo'] == "expand_all" ) {
   unset($_SESSION['dtm_collapse_fields'][$_REQUEST['mt']]);
   unset($_SESSION['dtm_hidden_fields'][$_REQUEST['mt']]);
}

# collapse_all
# Collapse all fields
if ( $_GET['todo'] == "collapse_all" ) {
   $qry = "select * from ".$_REQUEST['mt']." limit 1";
   $rez = mysql_query($qry);
   $numberFields = mysql_num_fields($rez);

	for ( $x = 0; $x <= $numberFields; $x++ ) {
	   $thefieldname = mysql_field_name($rez, $x);
	   $_SESSION['dtm_collapse_fields'][$_REQUEST['mt']][$thefieldname] = "valuedoesntmatter";
	}
}

# hide_field
# Hide field entirely (do not show column)
if ( isset($_GET['hide_field']) ) {
   $_SESSION['dtm_hidden_fields'][$_REQUEST['mt']][$_GET['hide_field']] = "valuedoesntmatter";
}
# show_field
# Restore hidden field and show as normal
if ( isset($_GET['show_field']) ) {
   unset($_SESSION['dtm_hidden_fields'][$_REQUEST['mt']][$_GET['show_field']]);
}

#######################################################
### Process "SAVE RECORD" Action		            ###
#######################################################
if ($ACTION == "DELETE_RECORD_NOW") {

	mysql_query("DELETE FROM $mt WHERE $KEYFIELD = '$ID'");
	$ACTION = "";
	unset($_GET);
	foreach($_SESSION[$_REQUEST['mt']]['last_page_view'] as $mo=>$ma){
		$_GET[$mo]=$ma;
		${$mo}=$ma;
	}

} // End Delete Record Action

#######################################################
### Process "SAVE RECORD" Action		            ###
#######################################################

if ($ACTION == "SAVE_NEW") {

	$SQL_STRING = "INSERT INTO $mt VALUES(";
	$tmp_date = "";								// Prepare our tmp date string register
	$tmp_time = "";								// Prepare our tmp time string register as well

	reset($HTTP_POST_VARS);
	while (list($name, $value) = each($HTTP_POST_VARS)) {

		$value = stripslashes($value);		// First strip all slashes for insurance and refreshes
		$value = addslashes($value);		// Now add slashes for proper mysql data storage

		if (ereg("VALUE_", $name) && !ereg("_DATEYEAR", $name) && !ereg("_DATEMONTH", $name) && !ereg("_DATEDAY", $name) && !ereg("_TIMEHOUR", $name) && !ereg("_TIMEMIN", $name)) {		// This is a proper value
			$SQL_STRING .= "'$value', ";
		}

		if (ereg("_DATE", $name)) {

			if (ereg("_DATEMONTH", $name)) { $tmp_date .= "$value-"; }
			if (ereg("_DATEDAY", $name)) { $tmp_date .= "$value"; }
			if (ereg("_DATEYEAR", $name)) {
				$tmp_date = "$value-" . $tmp_date;
				$SQL_STRING .= "'$tmp_date', ";		// Now add to SQL string for processing
				$tmp_date = "";						// Reset tmp date string in case of a second date value
			}
		}

		if (ereg("_TIME", $name)) {
			if (ereg("_TIMEHOUR", $name)) { $tmp_time .= "$value:"; }
			if (ereg("_TIMEMIN", $name)) {
				$tmp_time .= "$value:00"; 				// Add minute to time string
				$SQL_STRING .= "'$tmp_time', ";			// Now add to SQL string for processing
				$tmp_time = "";							// Reset tmp time string in case of a second time value
			}
		}

	} // End WHILE loop

	// A bi-product of this loop method is the extra comma we
	// get at the end of our new sql_string.  Let's remove it.

	$tmp = strlen($SQL_STRING);
	$new = $tmp - 2;
	$SQL_STRING = substr($SQL_STRING, 0, $new);

	$SQL_STRING .= ")";					// Add closing insert bracket

	// echo $SQL_STRING;
	// exit;

	mysql_query("$SQL_STRING");			// Insert the new data now

	$ACTION = "";						// Force return to display page

} // End Save Record Action

#######################################################
### Process "SAVE UPDATE" Action		            ###
#######################################################

if ($ACTION == "SAVE_UPDATE") {

	$SQL_STRING = "UPDATE $mt SET ";
	$tmp_date = "";								// Prepare our tmp date string register
	$tmp_time = "";								// Prepare our tmp time string register as well

	reset($HTTP_POST_VARS);
	while (list($name, $value) = each($HTTP_POST_VARS)) {

		$value = stripslashes($value);	// First strip all slashes for insurance and refreshes
		$value = addslashes($value);		// Now add slashes for proper mysql data storage

		if (ereg("VALUE_", $name) && !ereg("_DATEYEAR", $name) && !ereg("_DATEMONTH", $name) && !ereg("_DATEDAY", $name) && !ereg("_TIMEHOUR", $name) && !ereg("_TIMEMIN", $name)) {		// This is a proper value
			$name = ereg_replace("VALUE_", "", $name);
			$SQL_STRING .= "$name = '$value', ";
		}

		if (ereg("_DATE", $name)) {

			if (ereg("_DATEMONTH", $name)) { $tmp_date .= "$value-"; }
			if (ereg("_DATEDAY", $name)) { $tmp_date .= "$value"; }
			if (ereg("_DATEYEAR", $name)) {
				$tmp_date = "$value-" . $tmp_date;

				$name = ereg_replace("VALUE_", "", $name);
				$name = ereg_replace("_DATEYEAR", "", $name);

				$SQL_STRING .= "$name = '$tmp_date', ";		// Now add to SQL string for processing
				$tmp_date = "";						// Reset tmp date string in case of a second date value
			}
		}

		if (ereg("_TIME", $name)) {
			if (ereg("_TIMEHOUR", $name)) { $tmp_time .= "$value:"; }
			if (ereg("_TIMEMIN", $name)) {
				$tmp_time .= "$value:00"; 				// Add minute to time string

				$name = ereg_replace("VALUE_", "", $name);
				$name = ereg_replace("_TIMEMIN", "", $name);

				$SQL_STRING .= "$name = '$tmp_time', ";			// Now add to SQL string for processing
				$tmp_time = "";							// Reset tmp time string in case of a second time value
			}
		}

	} // End WHILE loop

	// A bi-product of this loop method is the extra comma we
	// get at the end of our new sql_string.  Let's remove it.

	$tmp = strlen($SQL_STRING);
	$new = $tmp - 2;
	$SQL_STRING = substr($SQL_STRING, 0, $new);

	$EDIT_WHERE_STRING = stripslashes($EDIT_WHERE_STRING);
	$SQL_STRING .= " WHERE ($EDIT_WHERE_STRING)";

	// echo $SQL_STRING;
	// exit;

	mysql_query("$SQL_STRING");			// Insert the new data now

	$ACTION = "";						// Force return to display page

} // End Save UPDATE Action

#######################################################
### Process "Edit Record" Action (Part 1)           ###
#######################################################

$EDIT_FLAG = "off";

if ($ACTION == "EDIT") {

	$qry = "SELECT * FROM ".$mt." WHERE ".$KEYFIELD." = '".$ID."'";
//	echo $qry; exit;
	$result = mysql_query($qry);

	$numberRows = mysql_num_rows($result);
	$numberFields = mysql_num_fields($result);
	$numberFields--;
	$row = mysql_fetch_array($result);

	if ( $numberRows < 1 ) {
//	   echo "Could not select row<br/>"; echo mysql_error(); exit;
	}

	for ($x=0;$x<=$numberFields;$x++) {
		$iDat = htmlspecialchars($row[$x]);	// Bugzilla #12
		$FIELD_DATA[$x] = $iDat;
	}

	$ACTION = "ADD_NEW";
	$EDIT_FLAG = "on";

}

###############################################################
### Process "Add New Record" Action		            		###
### >> This is also utilized in the edit routine (Part 2)   ###
###############################################################

if ($ACTION == "ADD_NEW") {

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	//  READ IMAGE FILES INTO MEMORY
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	$count = 0;
	$directory = "$doc_root/images";
	$handle = opendir("$directory");
		while ($files = readdir($handle)) {
			if (strlen($files) > 2) {
				$count++;
				$imageFile[$count] = ucwords($files) . "~~~" . $files;
			}
		}
	$numImages = $count;
	closedir($handle);

	if ($count != 0) {
		sort($imageFile);
		if ($count == 1) {
			$imageFile[0] = $imageFile[1];
		}
		$numImages--;
	}

	$IMAGE_SELECT = "<OPTION VALUE=\"NULL\">Select Image...</OPTION>\n";

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	if(count($_SESSION[$_REQUEST['mt']]['last_page_view']) > 0){
		$last_q_string = "?";
		foreach($_SESSION[$_REQUEST['mt']]['last_page_view'] as $var=>$val){
			$last_q_string .= $var."=".$val."&";
		}
		$last_q_string = eregi_replace('&$', '', $last_q_string);
	}

	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 CLASS=text style=\"width:100%;\" ALIGN=left><TR><TD>\n";

	$THIS_DISPLAY .= "<FORM name=\"editrecord\" METHOD=POST ACTION=\"enter_edit_data.php".$last_q_string."\">\n";
	$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=mt VALUE=\"$mt\">\n";

	if ($EDIT_FLAG != "on") {
		$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=ACTION VALUE=\"SAVE_NEW\">\n";
		$SEC_TITLE = "Enter New Record";
	} else {
		$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=ACTION VALUE=\"SAVE_UPDATE\">\n";
		$SEC_TITLE = "Update Record Data";
	}

	
	$THIS_DISPLAY .= "	<TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 CLASS=text  ALIGN=left>\n";
	$THIS_DISPLAY .= "	<TR>\n";
	$THIS_DISPLAY .= "	<TD ALIGN=CENTER VALIGN=MIDDLE WIDTH=75%>\n";
	$THIS_DISPLAY .= "	<FONT STYLE='font-family: Arial; font-size: 10pt;'><B>$SEC_TITLE in Table \"<FONT COLOR=MAROON>$mt</FONT>\".</B></FONT>\n";
	$THIS_DISPLAY .= "	</TD><TD ALIGN=CENTER VALIGN=MIDDLE WIDTH=25%>\n";
	//$THIS_DISPLAY .= "<INPUT TYPE=BUTTON VALUE=\" Cancel \" ".$btn_delete." onclick=\"javascript: history.back();\">\n";
	
	$THIS_DISPLAY .= "   <a class=\"redButton\" href=\"javascript:history.back();\"><span>".lang("Cancel")."</span></a>\n";
	
	
	$THIS_DISPLAY .= "	</TD></TR></TABLE>\n";
	
	$THIS_DISPLAY .= "</TD></TR>\n<TR><TD>\n\n";

	$THIS_DISPLAY .= "<TABLE BORDER=0 WIDTH=100% CELLPADDING=10 CELLSPACING=1 CLASS=text ALIGN=CENTER style='border: 1px inset #CCCCCC; background: #CCCCCC;'>\n";


	$qry = "SELECT * FROM ".$mt." WHERE ".$KEYFIELD." = '".$ID."'";
	$result = mysql_query($qry);
	$numberFields = mysql_num_fields($result);
	$numberFields--;

	$FIX_TMP = mysql_fetch_array($result);
	$edit_tmp = "";

	for ($x=0;$x<=$numberFields;$x++) {

		if ($BGCOLOR == "WHITE") { $BGCOLOR="#EFEFEF"; } else { $BGCOLOR="WHITE"; }

		$THIS_DISPLAY .= "<TR>\n";
		$THIS_DISPLAY .= "<TD ALIGN=RIGHT style=\"width:20%;\" VALIGN=TOP BGCOLOR=$BGCOLOR>\n";

		$fieldname[$x] = mysql_field_name($result, $x);
		$fieldname[$x] = strtoupper($fieldname[$x]);
		$fieldtype[$x] = mysql_field_type($result, $x);
		$fieldlength[$x] = mysql_field_len($result, $x);
		$fieldtype[$x] = strtoupper($fieldtype[$x]);

		$meta = mysql_fetch_field($result, $x);

		if ($EDIT_FLAG == "on") {
			$this_val = addslashes($FIELD_DATA[$x]);
			if ($this_val != "") {
				$edit_tmp .= "$fieldname[$x] = '$this_val' AND ";
			}
		}

		$display_fieldname = eregi_replace("_", " ", $fieldname[$x]);	// Format Field names for screen display
		$display_textbox = "MAXLENGTH=$fieldlength[$x]";     			// Make sure textbox entry can be no longer than set field length

		$THIS_DISPLAY .= "<B><U>$display_fieldname</U>&nbsp;&nbsp;<FONT STYLE='font-size: 7pt;'>($fieldtype[$x])</FONT>&nbsp;:</B>\n";
		$THIS_DISPLAY .= "</TD><TD  VALIGN=TOP ALIGN=LEFT BGCOLOR=$BGCOLOR>";

		if ($fieldtype[$x] == "STRING" || $fieldtype[$x] == "INT" || $fieldtype[$x] == "REAL") {

			if ($fieldname[$x] != "AUTO_IMAGE" && $fieldname[$x] != "AUTO_SECURITY_AUTH") {

				if ($meta->primary_key == 1) {

//					$DIS = "DISABLED";
					$this_value = $FIELD_DATA[$x];

					if ($FIELD_DATA[$x] != "") {
						$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=\"VALUE_".$fieldname[$x]."\" VALUE=\"".$FIELD_DATA[$x]."\">\n";
						$this_value = $FIELD_DATA[$x];
					} else {
						$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=\"VALUE_$fieldname[$x]\" VALUE=\"NULL\">\n";
					}

				} else {

					$DIS = "";
					$this_value = $FIELD_DATA[$x];

				}

				$THIS_DISPLAY .= "<INPUT $DIS $display_textbox TYPE=TEXT NAME=\"VALUE_$fieldname[$x]\" VALUE=\"$this_value\" CLASS=text STYLE='WIDTH: 550px;'>\n";

			} else {

				if ($fieldname[$x] == "AUTO_IMAGE") {

					$THIS_DISPLAY .= "<SELECT NAME=\"VALUE_$fieldname[$x]\" CLASS=text STYLE='WIDTH: 550px;'>$IMAGE_SELECT\n";

					for ($z=0;$z<=$numImages;$z++) {
						$tmp = split("~~~", $imageFile[$z]);
						if ($FIELD_DATA[$x] == $tmp[1]) { $SEL = "SELECTED"; } else { $SEL = ""; }
							$THIS_DISPLAY .= "<OPTION $SEL VALUE=\"$tmp[1]\">$tmp[1]</OPTION>\n";
						}
					}

					$THIS_DISPLAY .= "</SELECT>\n";
				}

				if ($fieldname[$x] == "AUTO_SECURITY_AUTH") {

					$THIS_DISPLAY .= "<FONT COLOR=#999999>For internal security use only.</FONT><INPUT TYPE=HIDDEN NAME=\"VALUE_AUTO_SECURITY_AUTH\" VALUE=\"$FIELD_DATA[$x]\">\n";

				}

			} // End Auto-Image Check

		// ---------------------------------------------------------
		// Check for BLOB Field Now
		// ---------------------------------------------------------

		if ($fieldtype[$x] == "BLOB" || $fieldtype[$x] == "LONGBLOB") {
			$THIS_DISPLAY .= "<TEXTAREA NAME=\"VALUE_$fieldname[$x]\" ROWS=15 CLASS=text STYLE='WIDTH: 550px; HEIGHT: 150px;'>$FIELD_DATA[$x]</TEXTAREA>\n";
		}

		// ---------------------------------------------------------
		// Check for Date Field
		// ---------------------------------------------------------

		if ($fieldtype[$x] == "DATE") {

			if ($EDIT_FLAG == "on") {
				$F_DATA = split("-", $FIELD_DATA[$x]);
			}

			$this_month = date("M");
			$this_day = date("d");
			$this_year = date("Y");

			$THIS_DISPLAY .= "<SELECT NAME=\"VALUE_$fieldname[$x]_DATEMONTH\" CLASS=text STYLE='WIDTH: 50px;'>\n";
			for ($z=1;$z<=12;$z++) {
				$display_month = date("M", mktime(0,0,0,$z,1,$this_year));
				$v = date("m", mktime(0,0,0,$z,1,$this_year));
				$SEL = "";
				if ($F_DATA[1] == $v) { $SEL = "SELECTED"; }
				if ($F_DATA[1] == "" && $this_month == $display_month) { $SEL = "SELECTED"; }
				$THIS_DISPLAY .= "<OPTION $SEL VALUE=\"$v\">$display_month</OPTION>\n";
			}
			$THIS_DISPLAY .= "</SELECT> ";

			$THIS_DISPLAY .= "<SELECT NAME=\"VALUE_$fieldname[$x]_DATEDAY\" CLASS=text STYLE='WIDTH: 50px;'>\n";
			for ($z=1;$z<=31;$z++) {
				$display_day = date("d", mktime(0,0,0,1,$z,$this_year));
				$SEL = "";
				if ($F_DATA[2] == $display_day) { $SEL = "SELECTED"; }
				if ($F_DATA[2] == "" && $display_day == $this_day) { $SEL = "SELECTED"; }
				$THIS_DISPLAY .= "<OPTION $SEL VALUE=\"$display_day\">$display_day</OPTION>\n";
			}
			$THIS_DISPLAY .= "</SELECT> ";

			$THIS_DISPLAY .= "<SELECT NAME=\"VALUE_$fieldname[$x]_DATEYEAR\" CLASS=text STYLE='WIDTH: 55px;'>\n";

			$end_year = $this_year + 10;
			for ($z=1960;$z<=$end_year;$z++) {
				$SEL = "";
				if ($F_DATA[0] == $z) { $SEL = "SELECTED"; }
				if ($F_DATA[0] == "" && $z == $this_year) { $SEL = "SELECTED"; }
				$THIS_DISPLAY .= "<OPTION $SEL VALUE=\"$z\">$z</OPTION>\n";
			}
			$THIS_DISPLAY .= "</SELECT>";

		} // End Date Select

		// ---------------------------------------------------------
		// Check for Time Field Now
		// ---------------------------------------------------------

		if ($fieldtype[$x] == "TIME") {

			if ($EDIT_FLAG == "on") {
				$F_DATA = split(":", $FIELD_DATA[$x]);
			}

			$THIS_DISPLAY .= "<SELECT onchange=\"set_ampm(this.value);\"NAME=\"VALUE_$fieldname[$x]_TIMEHOUR\" CLASS=text STYLE='WIDTH: 50px;'>\n";

			for ($z=0;$z<=24;$z++) {

				$v = $z;
				$v2 = $z;

				if ($z > 12) { $v = $z-12; }

				if ($v < 10) { $v = "0".$v; }
				if ($v2 < 10) { $v2 = "0".$v2; }

				if ($F_DATA[0] == $v2) { $SEL = "SELECTED"; } else { $SEL = ""; }
				$THIS_DISPLAY .= "<OPTION $SEL VALUE=\"$v2\">$v</OPTION>\n";
			}

			$THIS_DISPLAY .= "</SELECT>&nbsp;";
			$THIS_DISPLAY .= "<SELECT NAME=\"VALUE_$fieldname[$x]_TIMEMIN\" CLASS=text STYLE='WIDTH: 50px;'>\n";

			for ($z=0;$z<=59;$z++) {
				$v = $z;
				if ($z < 10) { $v = "0".$z; }
				if ($F_DATA[1] == $v) { $SEL = "SELECTED"; } else { $SEL = ""; }
				$THIS_DISPLAY .= "<OPTION $SEL VALUE=\"$v\">$v</OPTION>\n";
			}

			$THIS_DISPLAY .= "</SELECT>&nbsp;<SPAN ID=\"AMPM\">AM</SPAN>";

			if ($EDIT_FLAG == "on") {
				$THIS_DISPLAY .= "\n\n<SCRIPT LANGUAGE=JAVASCRIPT>\n\n     set_ampm($F_DATA[0]);\n\n</SCRIPT>\n\n";
			}

		} // End Time Selections

		$THIS_DISPLAY .= "</TD></TR>\n\n";

	}

	if ($EDIT_FLAG == "on") {

		$tmp = strlen($edit_tmp);
		$new = $tmp - 5;
		$edit_tmp = substr($edit_tmp, 0, $new);

		$BTN_TITLE = " Save Changed Data ";
		$THIS_DISPLAY .= "<TEXTAREA NAME=EDIT_WHERE_STRING STYLE='display: none;'>$KEYFIELD = '$FIX_TMP[$KEYFIELD]'</TEXTAREA>\n";

	} else {

		$BTN_TITLE = " Save New Record Data ";

	}

	if ($BGCOLOR == "WHITE") { $BGCOLOR="#EFEFEF"; } else { $BGCOLOR="WHITE"; }

	$THIS_DISPLAY .= "<TR>\n";
	$THIS_DISPLAY .= "<TD COLSPAN=2 ALIGN=CENTER VALIGN=TOP BGCOLOR=$BGCOLOR>\n";
	
	$THIS_DISPLAY .= "   <button class=\"greenButton\" onClick=\"document.editrecord.submit();\" type=\"button\"><span><span>".$BTN_TITLE."</span></span></button>\n";
	$THIS_DISPLAY .= "</TD></TR></TABLE></FORM>\n";
	$THIS_DISPLAY .= "	</TD></TR></TABLE>\n";

} // End Add New Record Action

#######################################################
### START HTML/JAVASCRIPT CODE			            ###
#######################################################

$MOD_TITLE = lang("Table Manager: Enter/Edit Record Data")." '$mt'";



//echo "<html>\n";
//echo "<head>\n";
//echo "<title>".$mt."</title>\n";

$globalprefObj = new userdata('global');
if ( $globalprefObj->get('utf8') == 'on' ) {
	header('Content-Type: text/html; charset=utf-8'); 
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
} else {
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
}
?>

<script type="text/javascript" src="../../../includes/display_elements/js_functions.php"></script>

<script language="JavaScript">

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


function set_ampm(v) {

	if (v > 11) {
		AMPM.innerHTML = "PM";
	} else {
		AMPM.innerHTML = "AM";
	}

	if (v == 24) {
		AMPM.innerHTML = "AM";
	}

}



function confirm_delete(table,key) {
		<? echo "var tiny = window.confirm('".lang("You have selected to delete this record.")."\\n".lang("You will not be able to undo this choice.")."\\n\\n".lang("Do you wish to continue with this action")."?');"; ?>
		if (tiny != false) {
			// OK Redirect to Send Routine
			window.location = "enter_edit_data.php?ACTION=DELETE_RECORD_NOW&ID="+key+"&mt="+table+"&<?=SID?>";
		}
}

</script>

<style>
/* Formatted timestamp values */
.formatted_timestamp {
   font-style: italic;
   font-size: 90%;
}

span.formatted_serialized {
   /*font-size: 90%;*/
   /*color: red;*/
   display: block;
   width: 450px;
   height: 175px;
   overflow: auto;
}

a.sup:link {color: #980404; text-decoration: none; font-family: Arial; font-size: 7pt;}
a.sup:visited {color: #980404; text-decoration: none; font-family: Arial; font-size: 7pt;}
a.sup:hover {color: white; text-decoration: none; font-family: Arial; font-size: 7pt; background: #980404;}

table#timstamp_field_select td input,
table#timstamp_field_select td label {
   float: left;
   /*border: 1px solid red;*/
}
table#timstamp_field_select td input {
   margin-top: 0px;
}
table#timstamp_field_select td label {
   margin-top: 1px;
   margin-right: 5px;
}

</style>
<link rel="stylesheet" href="../../../smt_module.css">

<!--
</head>

<body bgcolor=white text=black link=darkblue vlink=darkblue alink=darkblue leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 onLoad="show_hide_layer('Layer1','','hide','userOpsLayer','','show');">



<DIV ID="Layer1" style="position:absolute; left:0px; top:40%; width:100%; height:110px; z-index:100; border: 2px none #000000; visibility: visible; overflow: hidden">

  <table border=0 cellpadding=0 width=100% height=100% bgcolor=WHITE>
    <tr>
      <td align=center valign=middle class=text>Loading...<br/>
		<img src="../../../../icons/ajax-loader2.gif" width=60 height=30 border=0>
      </td>
    </tr>
  </table>

</DIV>
--->


<div id="db-table-container" style="position:relative; visibility:show; width:100%;  z-index:1; overflow: visible; border: 1px none #000000;">
	
<!---Module heading--->
<table id="db-table-htmltable" width="100%" border="0" align="center" cellpadding="4" cellspacing="0"  style="margin-top: 0px;">
<!--- <tr>
  <td colspan="2" valign="top" class="nopad">
   <table width="100%" border="0" cellspacing="0" cellpadding="5" class="feature_module_heading">
    <tr>
     <td colspan="2" class="fgroup_title">
      <a href="../../../main_menu.php">Main Menu</a> &gt;
      <a href="../download_data.php">Database Table Manager</a> &gt;
      <a href="<? echo $_SERVER['PHP_SELF']; ?>?mt=<? echo $mt; ?>" class="bold"><? echo $mt; ?></a>
     </td>
     <td class="fgroup_title right" style="padding-right: 15px;">
      &nbsp;
     </td>
    </tr>
   </table>
  </td>
 </tr>--->
 <tr>
  <td valign="top">

<?php
# Log in recent table list for quick links elsewhere
$_SESSION['recent_tables'][strtolower($_REQUEST['mt'])] = $_REQUEST['mt']; // strtolower necc for ksort()

# Readability/convenience on action link urls
$base_href = "enter_edit_data.php?mt=".$mt."&TBL_SEARCH_FOR=".$_REQUEST['TBL_SEARCH_FOR']."&amp;searchin=".$_REQUEST['searchin'];

if ($ACTION == "" || $ACTION == "show_all" ) {

	// Search Capability added for V4.5
	// ==============================================================================================
	$result = mysql_query("SELECT $KEYFIELD FROM $mt");
	$totalRecs = mysql_num_rows($result);

   /*---------------------------------------------------------------------------------------------------------*
                                     __                     _        _  _               _
    _ __  ___  _ __  __  ___  _ _   / _| ___  ___ ___  _ _ (_) __ _ | |(_) ___  ___  __| |
   | '_ \/ _ \| '_ \/ _|/ _ \| ' \ |  _||___|(_-</ -_)| '_|| |/ _` || || ||_ / / -_)/ _` |
   | .__/\___/| .__/\__|\___/|_||_||_|       /__/\___||_|  |_|\__,_||_||_|/__| \___|\__,_|
   |_|        |_|
   /*---------------------------------------------------------------------------------------------------------*/
	# popconfig-serialized
	$popup = "";
   $popup .= "<form name=\"mulihide_form\" method=\"post\" action=\"enter_edit_data.php\">\n";
   $popup .= "<input type=\"hidden\" name=\"ACTION\" value=\"\"/>\n";
   $popup .= "<input type=\"hidden\" name=\"mt\" value=\"".$mt."\"/>\n";
   $popup .= "<input type=\"hidden\" name=\"todo\" value=\"format_serialized\"/>\n";
   $popup .= "Note: If you're not a php programmer/developer, this is probably not of any use to you.\n";
	$popup .= "<p>Select field(s) containing serialized array data. Note that this will not actually alter the data.\n";
	$popup .= " It's just meant to display serialized array data in a more concised, readable format.</p>\n";
	$qry = "SHOW COLUMNS FROM ".$mt;
	$fieldrez = mysql_query($qry);
	$col = 1;
	$maxcols = 6;
	$counter = 0;
	while ( $getCol = mysql_fetch_assoc($fieldrez) ) {
	   $counter++;
	   $tdidname = "block_".$counter;
	   $idname = "serialchkbox_".$counter;
	   if ( $col == 1 ) {
         $popup .= "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"marginfix\" style=\"font-size: 90%;margin-top: 10px;\">\n";
         $popup .= " <tr>\n";
      }

      # checked? bolded?
      if ( isset($_SESSION['serialized_fields'][$_REQUEST['mt']][$getCol['Field']]) ) { $checked = " checked"; $unbold = ""; } else { $checked = ""; $unbold = "unbold"; }

      $onmouseover = "onmouseover=\"setClass(this.id, 'col_title font90 ".$unbold." center hand bg_yellow black');\"";
      $onmouseover .= " onmouseout=\"setClass(this.id, 'col_title font90 ".$unbold." center hand');\"";
      $onmouseover .= " onclick=\"toggle_checkbox('".$idname."');\"";

      $cbox_border = "border: 1px inset #ccc;border-style: inset none inset inset";
      $colname_border = "border: 1px inset #ccc;border-style: inset inset inset none";

      $popup .= "  <td id=\"".$tdidname."-1\" class=\"col_title ".$unbold." font90 center hand\" style=\"padding-left: 5px;".$cbox_border."\">\n";
      $popup .= "   <input type=\"checkbox\" id=\"".$idname."\" name=\"serialized_fields[]\" value=\"".$getCol['Field']."\"".$checked.">\n";
      $popup .= "  </td>\n";

      $popup .= "  <td id=\"".$tdidname."\" class=\"col_title ".$unbold." font90 center hand\" ".$onmouseover." style=\"".$colname_border."\">\n";
      if ( eregi("time|date", $getCol['Field']) ) { $display_colname = "<span class=\"bold\">".$getCol['Field']."</span>"; } else { $display_colname = $getCol['Field']; }
      $popup .= "   ".$display_colname."\n";
      $popup .= "   <input type=\"checkbox\" id=\"".$idname."\" name=\"serialized_fields[]\" value=\"".$getCol['Field']."\"".$checked." style=\"display: none;\">\n";
      $popup .= "  </td>\n";
//      $popup .= "  <td style=\"width: 40px;background-color: #f8f9fd;\">&nbsp;</td>\n";
      if ( $col == $maxcols ) {
         $popup .= " </tr>\n";
         $popup .= "</table>\n";
         $col = 1;
      } else {
         $col++;
      }
   }
   $popup .= "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"marginfix\" style=\"font-size: 90%;width: 100%;\">\n";
   $popup .= " <tr>\n";
   $popup .= "  <td align=\"right\">\n";
   $popup .= "   <script type=\"text/javascript\">\n";
   $popup .= "   function multihide_all(checkthem) {\n";
   $popup .= "      for ( x = 1; x <= ".$counter."; x++ ) {\n";
   $popup .= "         \$('chkbox_'+x).checked = checkthem;\n";
   $popup .= "      }\n";
   $popup .= "   }\n";
   $popup .= "   </script>\n";
   $popup .= "   <a href=\"#\" onclick=\"multihide_all('thisstringdoesntmatter')\">check all</a> |\n";
   $popup .= "   <a href=\"#\" onclick=\"multihide_all()\">un-check all</a>\n";
   $popup .= "  </td>\n";
   $popup .= " </tr>\n";
   $popup .= " <tr>\n";
   $popup .= "  <td align=\"right\">\n";
   $popup .= "   <input type=\"submit\" value=\"Apply Changes &gt;&gt;\" ".$btn_save.">\n";
   $popup .= "  </td>\n";
   $popup .= " </tr>\n";
   $popup .= "</table>\n";
   $popup .= "</form>\n";
   $THIS_DISPLAY .= help_popup("popconfig-serialized", "Format serialized array data for display", $popup, "left: 7%;top: 15%;width: 700px;opacity: .95;");


   /*---------------------------------------------------------------------------------------------------------*
		Base64_Decode
   /*---------------------------------------------------------------------------------------------------------*/
	# popconfig-serialized
	$popup = "";
   $popup .= "<form name=\"mulihide_form\" method=\"post\" action=\"enter_edit_data.php\">\n";
   $popup .= "<input type=\"hidden\" name=\"ACTION\" value=\"\"/>\n";
   $popup .= "<input type=\"hidden\" name=\"mt\" value=\"".$mt."\"/>\n";
   $popup .= "<input type=\"hidden\" name=\"todo\" value=\"format_decode\"/>\n";
   $popup .= "Note: Some field may store encoded data.  This tool decodes those fields.\n";
	$popup .= "<p>Select field(s) containing encoded data. Note that this will not actually alter the data.\n";
	$popup .= " It's just meant to display the encoded data in a readable format.</p>\n";
	$qry = "SHOW COLUMNS FROM ".$mt;
	$fieldrez = mysql_query($qry);
	$col = 1;
	$maxcols = 6;
	$counter = 0;
	while ( $getCol = mysql_fetch_assoc($fieldrez) ) {
	   $counter++;
	   $tdidname = "block_".$counter;
	   $idname = "serialchkbox_".$counter;
	   if ( $col == 1 ) {
         $popup .= "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"marginfix\" style=\"font-size: 90%;margin-top: 10px;\">\n";
         $popup .= " <tr>\n";
      }

      # checked? bolded?
      if ( isset($_SESSION['decode_fields'][$_REQUEST['mt']][$getCol['Field']]) ) { $checked = " checked"; $unbold = ""; } else { $checked = ""; $unbold = "unbold"; }

      $onmouseover = "onmouseover=\"setClass(this.id, 'col_title font90 ".$unbold." center hand bg_yellow black');\"";
      $onmouseover .= " onmouseout=\"setClass(this.id, 'col_title font90 ".$unbold." center hand');\"";
      $onmouseover .= " onclick=\"toggle_checkbox('".$idname."');\"";

      $cbox_border = "border: 1px inset #ccc;border-style: inset none inset inset";
      $colname_border = "border: 1px inset #ccc;border-style: inset inset inset none";

      $popup .= "  <td id=\"".$tdidname."-1\" class=\"col_title ".$unbold." font90 center hand\" style=\"padding-left: 5px;".$cbox_border."\">\n";
      $popup .= "   <input type=\"checkbox\" id=\"".$idname."\" name=\"decode_fields[]\" value=\"".$getCol['Field']."\"".$checked.">\n";
      $popup .= "  </td>\n";

      $popup .= "  <td id=\"".$tdidname."\" class=\"col_title ".$unbold." font90 center hand\" ".$onmouseover." style=\"".$colname_border."\">\n";
      if ( eregi("time|date", $getCol['Field']) ) { $display_colname = "<span class=\"bold\">".$getCol['Field']."</span>"; } else { $display_colname = $getCol['Field']; }
      $popup .= "   ".$display_colname."\n";
      $popup .= "   <input type=\"checkbox\" id=\"".$idname."\" name=\"decode_fields[]\" value=\"".$getCol['Field']."\"".$checked." style=\"display: none;\">\n";
      $popup .= "  </td>\n";
//      $popup .= "  <td style=\"width: 40px;background-color: #f8f9fd;\">&nbsp;</td>\n";
      if ( $col == $maxcols ) {
         $popup .= " </tr>\n";
         $popup .= "</table>\n";
         $col = 1;
      } else {
         $col++;
      }
   }
   $popup .= "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"marginfix\" style=\"font-size: 90%;width: 100%;\">\n";
   $popup .= " <tr>\n";
   $popup .= "  <td align=\"right\">\n";
   $popup .= "   <script type=\"text/javascript\">\n";
   $popup .= "   function multihide_all(checkthem) {\n";
   $popup .= "      for ( x = 1; x <= ".$counter."; x++ ) {\n";
   $popup .= "         \$('chkbox_'+x).checked = checkthem;\n";
   $popup .= "      }\n";
   $popup .= "   }\n";
   $popup .= "   </script>\n";
   $popup .= "   <a href=\"#\" onclick=\"multihide_all('thisstringdoesntmatter')\">check all</a> |\n";
   $popup .= "   <a href=\"#\" onclick=\"multihide_all()\">un-check all</a>\n";
   $popup .= "  </td>\n";
   $popup .= " </tr>\n";
   $popup .= " <tr>\n";
   $popup .= "  <td align=\"right\">\n";
   $popup .= "   <input type=\"submit\" value=\"Apply Changes &gt;&gt;\" ".$btn_save.">\n";
   $popup .= "  </td>\n";
   $popup .= " </tr>\n";
   $popup .= "</table>\n";
   $popup .= "</form>\n";
   $THIS_DISPLAY .= help_popup("popconfig-decode", "Decode encoded data for display", $popup, "left: 7%;top: 15%;width: 700px;opacity: .95;");



   /*---------------------------------------------------------------------------------------------------------*
    ___             _   _               _____  _                  _
   | _ \ ___  _ __ | | | | _ __   ___  |_   _|(_) _ __   ___  ___| |_  __ _  _ __   _ __  ___
   |  _// _ \| '_ \| |_| || '_ \ |___|   | |  | || '  \ / -_)(_-<|  _|/ _` || '  \ | '_ \(_-<
   |_|  \___/| .__/ \___/ | .__/         |_|  |_||_|_|_|\___|/__/ \__|\__,_||_|_|_|| .__//__/
             |_|          |_|                                                      |_|
   /*---------------------------------------------------------------------------------------------------------*/
	# popup-timestamps
	$popup = "";
   $popup .= "<form name=\"mulihide_form\" method=\"post\" action=\"enter_edit_data.php\">\n";
   $popup .= "<input type=\"hidden\" name=\"ACTION\" value=\"\"/>\n";
   $popup .= "<input type=\"hidden\" name=\"mt\" value=\"".$mt."\"/>\n";
   $popup .= "<input type=\"hidden\" name=\"todo\" value=\"format_timestamps\"/>\n";
	$popup .= "<p>Select timestamp field(s) to format as dates. Note that this will not actually alter the data.\n";
	$popup .= " It's just meant to display timestamp values as meaningful date/time strings to you can read them more easily.</p>\n";
	$qry = "SHOW COLUMNS FROM ".$mt;
	$fieldrez = mysql_query($qry);
	$col = 1;
	$maxcols = 6;
	$counter = 0;
	while ( $getCol = mysql_fetch_assoc($fieldrez) ) {
	   $counter++;
	   $tdidname = "block_".$counter;
	   $idname = "timechkbox_".$counter;
	   if ( $col == 1 ) {
         $popup .= "<table id=\"timstamp_field_select\" border=\"1\" cellpadding=\"3\" cellspacing=\"0\" class=\"marginfix\" style=\"font-size: 90%;margin-top: 10px;\">\n";
         $popup .= " <tr>\n";
      }

      # checked? bolded?
      if ( isset($_SESSION['timestamp_fields'][$_REQUEST['mt']][$getCol['Field']]) ) { $checked = " checked"; $unbold = ""; } else { $checked = ""; $unbold = "unbold"; }

      $onmouseover = "onmouseover=\"setClass(this.id, 'col_title font90 ".$unbold." center hand bg_yellow black');\"";
      $onmouseover .= " onmouseout=\"setClass(this.id, 'col_title font90 ".$unbold." center hand');\"";
//      $onmouseover .= " onclick=\"toggle_checkbox('".$idname."');\"";

//      $cbox_border = "border: 1px inset #ccc;border-style: inset none inset inset";
//      $colname_border = "border: 1px inset #ccc;border-style: inset inset inset none";

      if ( eregi("time|date", $getCol['Field']) ) { $display_colname = "<span class=\"bold\">".$getCol['Field']."</span>"; } else { $display_colname = $getCol['Field']; }
      $popup .= "  <td id=\"".$tdidname."-1\" class=\"col_title ".$unbold." font90 center\" style=\"padding-left: 5px;\">\n";
      $popup .= "   <input type=\"checkbox\" id=\"".$idname."\" name=\"timestamp_fields[]\" value=\"".$getCol['Field']."\"".$checked.">\n";
      $popup .= "   <label for=\"".$idname."\">".$display_colname."</label>\n";
      $popup .= "  </td>\n";

//      $popup .= "  <td style=\"width: 40px;background-color: #f8f9fd;\">&nbsp;</td>\n";
      if ( $col == $maxcols ) {
         $popup .= " </tr>\n";
         $popup .= "</table>\n";
         $col = 1;
      } else {
         $col++;
      }
   }
   $popup .= "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"marginfix\" style=\"font-size: 90%;width: 100%;\">\n";
   $popup .= " <tr>\n";
   $popup .= "  <td align=\"right\">\n";
   $popup .= "   <script type=\"text/javascript\">\n";
   $popup .= "   function chkall_timestamps(checkthem) {\n";
   $popup .= "      for ( x = 1; x <= ".$counter."; x++ ) {\n";
   $popup .= "         \$('timechkbox_'+x).checked = checkthem;\n";
   $popup .= "      }\n";
   $popup .= "   }\n";
   $popup .= "   </script>\n";
   $popup .= "   <a href=\"#\" onclick=\"chkall_timestamps('thisstringdoesntmatter')\">check all</a> |\n";
   $popup .= "   <a href=\"#\" onclick=\"chkall_timestamps()\">un-check all</a>\n";
   $popup .= "  </td>\n";
   $popup .= " </tr>\n";

   # Date/Time format
   $popup .= " <tr>\n";
   $popup .= "  <td align=\"right\">\n";
   $popup .= "   <table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"marginfix\" style=\"font-size: 90%;\">\n";
   $popup .= "    <tr>\n";
   $popup .= "     <td>Date/Time Format:</td>\n";
   $popup .= "     <td>\n";
   $popup .= "      <select id=\"timestamp_date_format\" name=\"timestamp_date_format\">\n";
   $popup .= "       <option value=\"M d, Y\">".date("M d, Y")."</option>\n";
   $popup .= "       <option value=\"M d, Y - g:ia\">".date("M d, Y - g:ia")."</option>\n";
   $popup .= "       <option value=\"D m/d - g:ia\">".date("D m/d - g:ia")."</option>\n";
   $popup .= "      </select>\n";
   $popup .= "      <script type=\"text/javascript\">\n";
   $popup .= "       \$('timestamp_date_format').value = '".$_SESSION['timestamp_date_format'][$_REQUEST['mt']]."';\n";
   $popup .= "      </script>\n";
   $popup .= "     </td>\n";
   $popup .= "    </tr>\n";
   $popup .= "   </table>\n";
   $popup .= "  </td>\n";
   $popup .= " </tr>\n";

   $popup .= " <tr>\n";
   $popup .= "  <td align=\"right\">\n";
   $popup .= "   <input type=\"submit\" value=\"Apply Changes &gt;&gt;\" ".$btn_save.">\n";
   $popup .= "  </td>\n";
   $popup .= " </tr>\n";
   $popup .= "</table>\n";
   $popup .= "</form>\n";
   $THIS_DISPLAY .= help_popup("popup-timestamps", "Format timestamps as human date strings", $popup, "left: 7%;top: 15%;width: 700px;opacity: .95;");



	# popup-hide_fields
	$popup = "";
   $popup .= "<form name=\"mulihide_form\" method=\"post\" action=\"enter_edit_data.php\">\n";
   $popup .= "<input type=\"hidden\" name=\"ACTION\" value=\"\"/>\n";
   $popup .= "<input type=\"hidden\" name=\"mt\" value=\"".$mt."\"/>\n";
   $popup .= "<input type=\"hidden\" name=\"todo\" value=\"hide_multi_fields\"/>\n";
	$popup .= "<p>Select fields to hide...</p>\n";
	$popup .= "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"marginfix\" style=\"font-size: 90%;\">\n";

	$qry = "SHOW COLUMNS FROM ".$mt;
	$fieldrez = mysql_query($qry);
	$col = 1;
	$maxcols = 4;
	$counter = 0;
	while ( $getCol = mysql_fetch_assoc($fieldrez) ) {
	   $counter++;
	   $idname = "chkbox_".$counter;
	   if ( $col == 1 ) {
         $popup .= " <tr>\n";
      }

      $onmouseover = "onclick=\"toggle_checkbox('".$idname."');\"";

      if ( isset($_SESSION['dtm_hidden_fields'][$_REQUEST['mt']][$getCol['Field']]) ) { $checked = " checked"; } else { $checked = ""; }
      $popup .= "  <td class=\"hand\" ".$onmouseover.">\n";
      $popup .= "   <input type=\"checkbox\" id=\"".$idname."\" name=\"multi_hide_fields[]\" value=\"".$getCol['Field']."\"".$checked.">\n";
      $popup .= "  </td>\n";
      $popup .= "  <td class=\"hand\" ".$onmouseover.">\n";
      $popup .= "   ".$getCol['Field']."\n";
      $popup .= "  </td>\n";
      $popup .= "  <td style=\"width: 50px;background-color: #f8f9fd;\">&nbsp;</td>\n";

      if ( $col == $maxcols ) {
         $popup .= " </tr>\n";
         $col = 1;
      } else {
         $col++;
      }
   }

   # check all
   $popup .= " <tr>\n";
   $popup .= "  <td colspan=\"".($maxcols * 2)."\" align=\"right\">\n";
   $popup .= "   <script type=\"text/javascript\">\n";
   $popup .= "   function multihide_all(checkthem) {\n";
   $popup .= "      for ( x = 1; x <= ".$counter."; x++ ) {\n";
   $popup .= "         \$('chkbox_'+x).checked = checkthem;\n";
   $popup .= "      }\n";
   $popup .= "   }\n";
   $popup .= "   </script>\n";
   $popup .= "   <a href=\"#\" onclick=\"multihide_all('thisstringdoesntmatter')\">check all</a> |\n";
   $popup .= "   <a href=\"#\" onclick=\"multihide_all()\">un-check all</a>\n";
   $popup .= "  </td>\n";
   $popup .= " </tr>\n";
   # [ Apply Changes >> ]
   $popup .= " <tr>\n";
   $popup .= "  <td colspan=\"".($maxcols * 2)."\" align=\"right\">\n";
   $popup .= "   <input type=\"submit\" value=\"Apply Changes &gt;&gt;\" ".$btn_save.">\n";
   $popup .= "  </td>\n";
   $popup .= " </tr>\n";
   $popup .= "</table>\n";
   $popup .= "</form>\n";
   $THIS_DISPLAY .= help_popup("popup-hide_fields", "Hide/show multiple fields", $popup, "left: 5%;top: 15%;width: 650px;opacity: .95;");


   /*---------------------------------------------------------------------------------------------------------*
    ___                ___
   | _ \ _  _  _ _    / _ \  _  _  ___  _ _  _  _
   |   /| || || ' \  | (_) || || |/ -_)| '_|| || |
   |_|_\ \_,_||_||_|  \__\_\ \_,_|\___||_|   \_, |
                                             |__/

   # popup-custom_qry - Popup layer with form to run custom query on database
   /*---------------------------------------------------------------------------------------------------------*/
   # Display popup by default if just ran a query
   if ( $_REQUEST['todo'] == "killqry" || $_REQUEST['todo'] == "run_qry" ) { $qry_display = "block"; } else { $qry_display = "none"; }
   $THIS_DISPLAY .= "<div id=\"popup-custom_qry\" style=\"opacity: 0.90;display: ".$qry_display.";width: 600px;position: absolute;left: 12%;top: 27%;border: 1px solid #ccc;z-index: 2;background-color: #f8f9fd;text-align: left;padding: 0;\">\n";
   $THIS_DISPLAY .= " <form method=\"post\" action=\"enter_edit_data.php\">\n";
   $THIS_DISPLAY .= " <input type=\"hidden\" name=\"ACTION\" value=\"\"/>\n";
   $THIS_DISPLAY .= " <input type=\"hidden\" name=\"mt\" value=\"".$mt."\"/>\n";
   $THIS_DISPLAY .= " <input type=\"hidden\" name=\"todo\" value=\"run_qry\"/>\n";
   $THIS_DISPLAY .= " <table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"marginfix\">\n";
   $THIS_DISPLAY .= "  <tr>\n";
   $THIS_DISPLAY .= "   <td colspan=\"2\">\n";
   $THIS_DISPLAY .= "    <h2 style=\"margin-bottom: 0;\">Run custom datbase query</h2>\n";
   $THIS_DISPLAY .= "    <p style=\"margin-top: 3px;\">For advanced users only.</p>\n";
   $THIS_DISPLAY .= "   </td>\n";
   $THIS_DISPLAY .= "  </tr>\n";
   $THIS_DISPLAY .= "  <tr>\n";
   $THIS_DISPLAY .= "   <td>MySQL Query String:<br/>\n";
   $THIS_DISPLAY .= "    <textarea id=\"custom_qry_string\" name=\"runthis\" style=\"opacity: 1;width: 450px; height: 65px;\"></textarea><br/>\n";
   $THIS_DISPLAY .= "   </td>\n";
   $THIS_DISPLAY .= "   <td align=\"center\"><input type=\"submit\" name=\"submit\" value=\"Run Query\"></td>\n";
   $THIS_DISPLAY .= "  </tr>\n";

   # Previous Queries:
   if ( count($_SESSION['dtm_custom_qry']) > 0 ) {
      $THIS_DISPLAY .= "  <tr>\n";
      $THIS_DISPLAY .= "   <td colspan=\"2\">\n";
      $THIS_DISPLAY .= "    <b>Previous Queries:</b> (click to put text in query box...doesn't actually run it when you click)<br/>\n";

      $THIS_DISPLAY .= "    <table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"marginfix\" style=\"font-size: 90%;\">\n";

      foreach ( $_SESSION['dtm_custom_qry'] as $qryid=>$qrystring ) {
         if ( $qrystring != "" ) {
            $THIS_DISPLAY .= "    <tr>\n";
            $THIS_DISPLAY .= "     <td>\n";
            $THIS_DISPLAY .= "      <span class=\"hand gray_31\" onclick=\"document.getElementById('custom_qry_string').innerHTML=this.innerHTML;\">";
            $THIS_DISPLAY .= stripslashes($qrystring)."\n";
            $THIS_DISPLAY .= "</span>\n";
            $THIS_DISPLAY .= "     </td>\n";

            # [x]
            $THIS_DISPLAY .= "     <td valign=\"top\">\n";
            $THIS_DISPLAY .= "      [<a href=\"enter_edit_data.php?mt=".$mt."&todo=killqry&qryid=".$qryid."\" class=\"red\">x</a>]\n"; // Remove this one from history
            $THIS_DISPLAY .= "     </td>\n";
            $THIS_DISPLAY .= "    </tr>\n";
         }
      }
      $THIS_DISPLAY .= "    </table>\n";
      $THIS_DISPLAY .= "   </td>\n";
      $THIS_DISPLAY .= "  </tr>\n";
   }

   $THIS_DISPLAY .= " </table>\n";
   $THIS_DISPLAY .= " </form><br/><br/>\n";

   # [x] close
   $THIS_DISPLAY .= " <div id=\"popup-custom_qry-closebar\" onclick=\"hideid('popup-custom_qry');\" onmouseover=\"setClass(this.id, 'hand bg_red_d7 white right');\"  onmouseout=\"setClass(this.id, 'hand bg_red_98 white right');\" class=\"hand bg_red_98 white right\" style=\"padding: 3px;\">[x] close</div>\n";

   $THIS_DISPLAY .= "</div>\n";
   #---END custom query popup layer-------------------------------------------------------------------

	# Setup Display of Record Data
	#-----------------------------------------------------------
   # Get total records in table
   $result = mysql_query("SELECT $KEYFIELD FROM $mt");
   $total_recs = mysql_num_rows($result);

	# Limit to 10 at a time by default
	if ( $ACTION != "show_all" ) { // Mantis #252
	   $num_to_show = 25;
	} else {
	   $num_to_show = $total_recs;
	}

	if ($start_show == "") { $start_show = 0; }

	$noShowFlag = 0;

	# Display full table or just search results?
	if ($TBL_SEARCH_FOR == "") {

	   # Custom SELECT via 'Run custom query'?
	   if ( $customselectqry ) {
	      # CUSTOM: Fetch array to display results of custom select query
	      if ( get_magic_quotes_gpc() ) { $qry = stripslashes($_POST['runthis']); } else { $qry = $_POST['runthis']; }
//	      echo "<textarea style=\"width: 400px;\">".$qry."</textarea>\n"; // testing

	   } else {
   	   # NORMAL: Full table (all records)
   	   $qry = "SELECT * FROM ".$_REQUEST['mt']." ORDER BY ".$_SESSION[$_REQUEST['mt']]['orderby']." ".$_SESSION[$_REQUEST['mt']]['orderhow']." LIMIT ".$start_show.", ".$num_to_show;
   	}

		$result = mysql_query($qry);

	} else {
      /*---------------------------------------------------------------------------------------------------------*
         ____                      __     ____
        / __/___  ___ _ ____ ____ / /    / __ \  ____ __ __
       _\ \ / -_)/ _ `// __// __// _ \  / /_/ / / __// // /
      /___/ \__/ \_,_//_/   \__//_//_/  \___\_\/_/   \_, /
                                                    /___/
      /*---------------------------------------------------------------------------------------------------------*/
		# search in specific field option checked?
		if ( $_REQUEST['searchin'] == "" ) {
   		# NO: Build "or [field] like.." for every field
         $qry = "SHOW COLUMNS FROM ".$_REQUEST['mt'];
         $rez = mysql_query($qry);
         $query_string = "";

         # For pulling field type below
         $qry4type = 'select * from '.$_REQUEST['mt'].' limit 1';
         $rez4type = mysql_query($qry4type);

         $xInt = 0;
         while ( $getCol = mysql_fetch_assoc($rez) ) {
            # special search qry for blog field case insensitivity
            if ( strtolower(mysql_field_type($rez4type, $xInt)) == 'blob' ) {
               # Blob search method varies by mysql version
               if( mysql_get_client_info() >= 4 ) {
                  $query_string .= "CAST(".$getCol['Field']." as char) LIKE '%".$TBL_SEARCH_FOR."%' or ";
               } else {
                  $query_string .= "lcase(".$getCol['Field'].") LIKE lcase('%".$TBL_SEARCH_FOR."%') or ";
               }
            } else {
               # not a blob, use normal qry
               $query_string .= $getCol['Field']." LIKE '%".$TBL_SEARCH_FOR."%' OR ";
            }
            $xInt++;
         }


   		$tmp = strlen($query_string);
   		$tv = $tmp - 3;
   		$query_string = substr($query_string, 0, $tv);
   		$_SESSION[$_REQUEST['mt']]['searchin'] = NULL;

   	} else {
   	   # YES: Just add one field to qry string
   	   $query_string = $_REQUEST['searchin']." LIKE '%".$TBL_SEARCH_FOR."%'";
   	   $_SESSION[$_REQUEST['mt']]['searchin'] = $_REQUEST['searchin'];
   	}

		# Actual search qry
	   $qry = "SELECT * FROM ".$_REQUEST['mt']." WHERE ".$query_string;
	   $qry .= " ORDER BY ".$_SESSION[$_REQUEST['mt']]['orderby']." ".$_SESSION[$_REQUEST['mt']]['orderhow'];
	   $result = mysql_query($qry);

		$totalRecs = mysql_num_rows($result);

		# Flag for next and previous links
		$noShowFlag = 1;

		# Save last search term in session for easy re-searching
		$_SESSION[$_REQUEST['mt']]['last_search_term'] = $TBL_SEARCH_FOR;
		$_SESSION[$_REQUEST['mt']]['last_search_results'] = $totalRecs;

	} // End Search Option

	$numberRows = mysql_num_rows($result);
	$numberFields = mysql_num_fields($result);
	$numberFields--;


   /*---------------------------------------------------------------------------------------------------------*
    ___  _          _               ___   _
   / __|| |_  __ _ | |_  _  _  ___ |   \ (_)__ __
   \__ \|  _|/ _` ||  _|| || |(_-< | |) || |\ V /
   |___/ \__|\__,_| \__| \_,_|/__/ |___/ |_| \_/
   /*---------------------------------------------------------------------------------------------------------*/
   $status_div = "<div style=\"color: #999;text-align: left; padding: 0 0 3px 8px;\">\n";

   # Total number of records found ('show all' link)
   $qry = "select ".$KEYFIELD." from ".$_REQUEST['mt'];
   $rez = mysql_query($qry);
   $total_in_table = mysql_num_rows($rez);

   if ( $_SESSION[$_REQUEST['mt']]['last_search_term'] != "" ) {
      # Last Search:
      $status_div .= "      <span style=\"margin-left: 5px;\">Last Search:</span>\n";

      # "realestate" in DOMAIN_NAME
      $status_div .= "       <a href=\"enter_edit_data.php?mt=".$mt."&TBL_SEARCH_FOR=".$_SESSION[$_REQUEST['mt']]['last_search_term']."&amp;searchin=".$_SESSION[$_REQUEST['mt']]['searchin']."\" class=\"noline\">";
      $status_div .= "&quot;".$_SESSION[$_REQUEST['mt']]['last_search_term']."&quot;";
      # searchin?
      if ( $_SESSION[$_REQUEST['mt']]['searchin'] != "" ) {
         $status_div .= "   in ".$_SESSION[$_REQUEST['mt']]['searchin'];
      } else {
         $status_div .= "   in all fields";
      }
      $status_div .= "</a>";

      $status_div .= " (<b>".$_SESSION[$_REQUEST['mt']]['last_search_results']."</b> matches out of ".$total_in_table." total records)\n";

      # [x]
      $status_div .= " <a href=\"enter_edit_data.php?mt=".$_REQUEST['mt']."&amp;kill_lastsearch=yes\" class=\"del font90\">[x]</a>\n";

   } else {
      # Total Number of Records in Table (show all)
      $status_div .= " ".lang("Total Number of Records in Table").": <b>".$total_in_table."</b>\n";
      $status_div .= " ( <a href=\"enter_edit_data.php?mt=".$mt."&ACTION=show_all\">".lang("show all")."</a> )\n";
   }


   # Sorting in effect?
   if ( $_SESSION[$_REQUEST['mt']]['orderby'] != $KEYFIELD ) {
      $status_div .= " | Sorting by ".$_SESSION[$_REQUEST['mt']]['orderby']." in ".$_SESSION[$_REQUEST['mt']]['orderhow']."ending order.";
      $status_div .= " <a href=\"".$base_href."&amp;kill_orderby=yes\" class=\"del font90\">[x]</a>\n";
   }
   $status_div .= "</div>\n";

   $THIS_DISPLAY .= $status_div;


	$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" class=smtext width=\"100%\">\n";
	$THIS_DISPLAY .= " <tr>\n\n";

	# Search form
	$THIS_DISPLAY .= "  <form name=\"searchform\" method=\"post\" action=\"enter_edit_data.php\">\n";
	$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\">\n";
   $THIS_DISPLAY .= "   <input type=\"hidden\" name=\"ACTION\" value=\"\">\n";
   $THIS_DISPLAY .= "   <input type=\"hidden\" name=\"mt\" value=\"".$_REQUEST['mt']."\">\n";

   # TBL_SEARCH_FOR
   $THIS_DISPLAY .= "   <input type=\"text\" class=\"text\" name=\"TBL_SEARCH_FOR\" style='width: 200px;'>\n";

   $THIS_DISPLAY .= "   <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n";
   $THIS_DISPLAY .= "    <tr>\n";

   # Within...
   $THIS_DISPLAY .= "     <td align=\"right\">";
   $onclick = "onclick=\"hideid('withinlink');showid('searchin');showid('withinx');\"";
   $THIS_DISPLAY .= "      <span id=\"withinlink\" style=\"display: block;font-size: 90%;\" class=\"blue uline hand\" ".$onclick.">within field...</span>\n";

   # searchin
   $THIS_DISPLAY .= "      <div id=\"searchin_opt\">\n";
   $THIS_DISPLAY .= "       <select id=\"searchin\" class=\"text\" name=\"searchin\" style=\"font-size: 90%;display: none;\">\n";
   $THIS_DISPLAY .= "        <option value=\"\">choose...</option>\n";
   $qry = "SHOW COLUMNS FROM ".$_REQUEST['mt'];
   $rez = mysql_query($qry);
   while ( $getCol = mysql_fetch_assoc($rez) ) {
      $THIS_DISPLAY .= "        <option value=\"".$getCol['Field']."\">".$getCol['Field']."</option>\n";
   }
   $THIS_DISPLAY .= "       </select>\n";
   $THIS_DISPLAY .= "      </div>\n";

   # [x]
   $onclick = "onclick=\"hideid('searchin');showid('withinlink');hideid('withinx');\"";
   $THIS_DISPLAY .= "      <span id=\"withinx\" style=\"display: none;font-size: 90%;\" class=\"red uline hand\" ".$onclick.">[x]</span>\n";

   $THIS_DISPLAY .= "     </td>\n";
   $THIS_DISPLAY .= "    </tr>\n";
   $THIS_DISPLAY .= "   </table>\n";

   $THIS_DISPLAY .= "  </td>\n";

	# [Find Record]
	$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\" style=\"padding-right: 40px;\">\n";
   $THIS_DISPLAY .= "   <button class=\"blueButton\" onClick=\"document.searchform.submit();\" type=\"button\"><span><span>".lang("Find Record")."</span></span></button>\n";
	$THIS_DISPLAY .= "  </td>\n";
	$THIS_DISPLAY .= "  </form>\n";

	# [Add New Record]
	$THIS_DISPLAY .= "  <form name=\"addrecord\" method=\"post\" action=\"enter_edit_data.php\">\n";
	$THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\">\n";
   $THIS_DISPLAY .= "   <input type=\"hidden\" name=\"mt\" value=\"$mt\">\n";
   $THIS_DISPLAY .= "   <input type=\"hidden\" name=\"ACTION\" value=\"ADD_NEW\">\n";
   //$THIS_DISPLAY .= "   <input type=\"submit\" value=\"".lang("Add New Record")."\" ".$btn_build.">\n";
   $THIS_DISPLAY .= "   <button class=\"greenButton\" onClick=\"document.addrecord.submit();\" type=\"button\"><span><span>".lang("Add New Record")."</span></span></button>\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  </form>\n";

	# Show/hide blob fields
	$THIS_DISPLAY .= "  <td valign=\"top\">\n";
	$THIS_DISPLAY .= "   <ul style=\"margin-bottom: 0;list-style-type: square;\">\n";

	if ( $_SESSION['dtm_viewmode'][$_REQUEST['mt']] == "hideblob" ) {
	   $THIS_DISPLAY .= "    <li><a href=\"".$base_href."&dtm_viewmode=default\">".str_replace(" ", "&nbsp;", lang("Show blob data"))."</a></li>\n";
	} else {
	   $THIS_DISPLAY .= "    <li><a href=\"".$base_href."&dtm_viewmode=hideblob\">".str_replace(" ", "&nbsp;", lang("Hide blob data"))."</a></li>\n";
	}


	# Show/hide collapse options
	if ( $_SESSION['dtm_collapse_option'][$_REQUEST['mt']] == "on" ) {
	   $THIS_DISPLAY .= "    <li><b><a href=\"".$base_href."&dtm_collapse=off\">".str_replace(" ", "&nbsp;", lang("Hide collapse options"))."</a></b></li>\n";

	   # Show all fields
	   if ( count($_SESSION['dtm_collapse_fields'][$_REQUEST['mt']]) > 0 ) {
	      $THIS_DISPLAY .= "    <li><a href=\"".$base_href."&amp;todo=expand_all\">".str_replace(" ", "&nbsp;", lang("Show all fields"))."</a></li>\n";
	   }

	   # Collapse all fields
	   $THIS_DISPLAY .= "    <li><a href=\"".$base_href."&amp;todo=collapse_all\">".str_replace(" ", "&nbsp;", lang("Collapse all fields"))."</a></li>\n";

	   # Hide multiple fields
	   $THIS_DISPLAY .= "    <li><a href=\"#\" onclick=\"showid('popup-hide_fields');\">".str_replace(" ", "&nbsp;", lang("Hide multiple fields"))."</a></li>\n";

	} else {
	   # Show collapse options
	   $THIS_DISPLAY .= "    <li><b><a href=\"enter_edit_data.php?mt=".$mt."&TBL_SEARCH_FOR=".$_SESSION[$_REQUEST['mt']]['last_search_term']."&dtm_collapse=on\">".str_replace(" ", "&nbsp;", lang("Show collapse options"))."</a></b></li>\n";
	}

   $THIS_DISPLAY .= "   </ul>\n";
	$THIS_DISPLAY .= "  </td>\n";

	# Run custom query
	$THIS_DISPLAY .= "  <td width=\"100%\" valign=\"top\">\n";
	$THIS_DISPLAY .= "   <ul style=\"margin-bottom: 0;list-style-type: square;\">\n";
	$THIS_DISPLAY .= "    <li><a href=\"#\" onclick=\"showid('popup-custom_qry');\" class=\"del\">".str_replace(" ", "&nbsp;", lang("Run custom query"))."</a></li>\n";
	$THIS_DISPLAY .= "   <li><a href=\"#\" onclick=\"showid('popup-timestamps');\">".str_replace(" ", "&nbsp;", lang("Format timestamps"))."</a></li>\n";
	$THIS_DISPLAY .= "   <li><a href=\"#\" onclick=\"showid('popconfig-serialized');\">".str_replace(" ", "&nbsp;", lang("Format serialized data"))."</a></li>\n";
	$THIS_DISPLAY .= "   <li><a href=\"#\" onclick=\"showid('popconfig-decode');\">".str_replace(" ", "&nbsp;", lang("Decode data"))."</a></li>\n";
	$THIS_DISPLAY .= "  </td>\n";

	$THIS_DISPLAY .= " </tr>\n";
	$THIS_DISPLAY .= "</table>\n\n";



	$THIS_DISPLAY .= "<table border=1 cellpadding=3 cellspacing=0 class=\"text\" width=\"98%\">\n\n";
	$THIS_DISPLAY .= " <tr>\n\n";
//	$THIS_DISPLAY .= "  <td class=\"col_title\" align=\"center\" valign=top><font color=white><b>".lang("Delete")."</b></font></td>\n";
//	$THIS_DISPLAY .= "  <td class=\"col_title\" align=\"center\" valign=top><font color=white><b>".lang("Edit")."</b></font></td>\n";
	$THIS_DISPLAY .= "  <td class=\"col_title\" align=\"center\" valign=top style=\"width:20px;\">&nbsp;</td>\n";
	


   /*---------------------------------------------------------------------------------------------------------*
     ___       _                      _____  _  _    _
    / __| ___ | | _  _  _ __   _ _   |_   _|(_)| |_ | | ___
   | (__ / _ \| || || || '  \ | ' \    | |  | ||  _|| |/ -_)
    \___|\___/|_| \_,_||_|_|_||_||_|   |_|  |_| \__||_|\___|
	# Field Names (column headings)
	/*---------------------------------------------------------------------------------------------------------*/
	for ($x=0;$x<=$numberFields;$x++) {
		$fieldname[$x] = mysql_field_name($result, $x);

		# Do not show if hidden by user
		if ( !array_key_exists($fieldname[$x], $_SESSION['dtm_hidden_fields'][$_REQUEST['mt']]) ) {
	   	$THIS_DISPLAY .= "  <td class=\"col_title\" align=\"left\" valign=\"top\">\n";

	   	# For [<- ->] and [v]
	   	$tinylink = "style=\"font-size: 90%;font-weight: normal;text-decoration:none;\"";

	   	# [v]
	   	$sorticon = "";
	   	$sortlink = "";
	   	if ( $_SESSION[$_REQUEST['mt']]['orderby'] == $fieldname[$x] ) {
	   	   if ( $_SESSION[$_REQUEST['mt']]['orderhow'] == "desc" ) {
	   	      $sortlink = "asc";
	   	      $sorticon = "<a href=\"".$base_href."&amp;orderby=".$fieldname[$x]."&orderhow=asc\" ".$linkstyle." class=\"white noline\">[&uarr;]</a>\n";
	   	   } else {
	   	      $sortlink = "desc";
	   	      $sorticon = "<a href=\"".$base_href."&amp;orderby=".$fieldname[$x]."&orderhow=desc\" ".$linkstyle." class=\"white noline\">[&darr;]</a>\n";
	   	   }
	   	}
		if ( $globalprefObj->get('utf8') == 'on' ) {
			$fieldname[$x] = utf8_encode($fieldname[$x]);
		}
   		# Collapsed field? (minimal display style if so)
   		if ( array_key_exists($fieldname[$x], $_SESSION['dtm_collapse_fields'][$_REQUEST['mt']]) ) {
   		   $titlestring = str_replace("_", "<br/>_", $fieldname[$x]);
   		   $titlestring = "<span style=\"font-size: 90%;font-weight: normal;\">".$titlestring."</span>";

   		} else {
   		   $titlestring = $fieldname[$x];
   		}

   		$titlestring = "<a href=\"".$base_href."&amp;orderby=".$fieldname[$x]."&orderhow=".$sortlink."\" ".$linkstyle." class=\"white noline\" title=\"".$sortlink."\">".$titlestring."</a>";

         $THIS_DISPLAY .= $titlestring;
   		$THIS_DISPLAY .= $sorticon;

   		# [<- ->] ?
   		if ( $_SESSION['dtm_collapse_option'][$_REQUEST['mt']] == "on" ) {

   		   # Collapse link or Expand link?
   		   if ( array_key_exists($fieldname[$x], $_SESSION['dtm_collapse_fields'][$_REQUEST['mt']]) ) {
   		      $THIS_DISPLAY .= "    <br/><a href=\"".$base_href."&amp;expand_field=".$fieldname[$x]."\" ".$tinylink." class=\"sav\">[&larr;|&rarr;]</a>\n";
   		   } else {
   		      $THIS_DISPLAY .= "    <br/><a href=\"".$base_href."&amp;collapse_field=".$fieldname[$x]."\" ".$tinylink." class=\"sav\">[&rarr;|&larr;]</a>\n";
   		   }
   		}
   		$THIS_DISPLAY .= "  </td>\n";

   	} // End if !array_key_exists -- field is not in hidden list
	}

	$THIS_DISPLAY .= "\n</TR>\n\n<TR>\n\n";
//	$THIS_DISPLAY .= "<TD CLASS=\"col_sub\" ALIGN=CENTER VALIGN=TOP>[&nbsp;".lang("OPTION")."&nbsp;]</TD>\n";
//	$THIS_DISPLAY .= "<TD CLASS=\"col_sub\" ALIGN=CENTER VALIGN=TOP>[&nbsp;".lang("OPTION")."&nbsp;]</TD>\n";
//	$THIS_DISPLAY .= "<TD CLASS=\"col_sub\" ALIGN=CENTER VALIGN=TOP>[&nbsp;".lang("OPTION")."&nbsp;]</TD>\n";
	$THIS_DISPLAY .= "<TD class=\"col_sub\" ALIGN=CENTER VALIGN=TOP style=\"width:120px;\">&nbsp;</TD>\n";


   /*---------------------------------------------------------------------------------------------------------*
    ___  _       _     _   _____
   | __|(_) ___ | | __| | |_   _|_  _  _ __  ___
   | _| | |/ -_)| |/ _` |   | | | || || '_ \/ -_)
   |_|  |_|\___||_|\__,_|   |_|  \_, || .__/\___|
                                 |__/ |_|
   /*---------------------------------------------------------------------------------------------------------*/
	for ($x=0;$x<=$numberFields;$x++) {
		$fieldtype[$x] = mysql_field_type($result, $x);
		$fieldtype[$x] = strtoupper($fieldtype[$x]);

		# Do not show if hidden by user
		if ( !array_key_exists($fieldname[$x], $_SESSION['dtm_hidden_fields'][$_REQUEST['mt']]) ) {

   		# Collapsed field? (minimal display style if so)
   		if ( array_key_exists($fieldname[$x], $_SESSION['dtm_collapse_fields'][$_REQUEST['mt']]) ) {
   		   $THIS_DISPLAY .= "  <td class=\"col_sub\" align=\"center\" valign=\"top\" style=\"font-size: 60%;color: #2e2e2e;\">[".$fieldtype[$x]."]</td>\n";
   		} else {
   		   $THIS_DISPLAY .= "  <td class=\"col_sub\" align=\"center\" valign=\"top\" style=\"font-size: 90%;color: #2e2e2e;\">[".$fieldtype[$x]."]</td>\n";
   		}

   	} // End if field not in hidden list

	} // End for loop through fields

	$THIS_DISPLAY .= "\n</TR>\n";


   /*---------------------------------------------------------------------------------------------------------*
    ___  _       _     _      _        _
   | __|(_) ___ | | __| |  __| | __ _ | |_  __ _
   | _| | |/ -_)| |/ _` | / _` |/ _` ||  _|/ _` |
   |_|  |_|\___||_|\__,_| \__,_|\__,_| \__|\__,_|
   /*---------------------------------------------------------------------------------------------------------*/
	$i = 0;
	while ($row = mysql_fetch_array ($result)) {

		if ($BGCOLOR == "WHITE") { $BGCOLOR="#EFEFEF"; } else { $BGCOLOR="WHITE"; }

		$edit_link = "[&nbsp;<a href=\"enter_edit_data.php?ACTION=EDIT&ID=".$row[$KEYFIELD]."&mt=$mt&=SID\">".lang("Edit")."</a>&nbsp;]";
		$del_link = "[&nbsp;<a href=\"#\" onclick=\"confirm_delete('$mt','$row[$KEYFIELD]');\" class=\"del\">".lang("Delete")."</a>&nbsp;]";
		$i++;

		$THIS_DISPLAY .= "\n <tr>\n";
      $THIS_DISPLAY .= "  <td bgcolor=\"".$BGCOLOR."\" align=\"center\" valign=top>".$del_link."&nbsp;&nbsp;".$edit_link."</td>\n";
      //$THIS_DISPLAY .= "  <td bgcolor=\"".$BGCOLOR."\" align=\"center\" valign=top>".."</td>\n";

		# Loop through fields
		for ($x=0;$x<=$numberFields;$x++) {
		   # Flags checked before trying to apply special formatting to data (ie trying unserialize truncated data)
		   $collapsed = false;
         $serialized = false;

   		# Do not show if hidden by user
   		if ( !array_key_exists($fieldname[$x], $_SESSION['dtm_hidden_fields'][$_REQUEST['mt']]) ) {
   			$tmp = $row[$x];

   			# Hide blob fields? (4.9 r23)
   			if ( $_SESSION['dtm_viewmode'][$_REQUEST['mt']] == "hideblob" && $tmp != "" && $tmp != "NULL" ) {
   			   if (strtoupper($fieldtype[$x]) == "BLOB") { $tmp = "[BLOB]"; $collapsed = true; }
   			} else {
   			   if (strtoupper($fieldtype[$x]) == "BLOB") { $tmp = $tmp; }
   			}

   			if ($tmp == "" || $tmp == "NULL") { $tmp = "&nbsp;"; }

            # Timestamp field?
            if ( array_key_exists($fieldname[$x], $_SESSION['timestamp_fields'][$_REQUEST['mt']]) ) {
               $tmp = "<span class=\"formatted_timestamp\">".date($_SESSION['timestamp_date_format'][$_REQUEST['mt']], $tmp)."</span>";
            }

            # Collapsed field? (hide field data if so)
            if ( array_key_exists($fieldname[$x], $_SESSION['dtm_collapse_fields'][$_REQUEST['mt']]) ) {
               if ( strlen($tmp) > 6 ) {
                  $tmp = substr($tmp, 0, 6)."...";
               }
               $collapsed = true;
            }

            # Serialzied data field?
            if ( array_key_exists($fieldname[$x], $_SESSION['serialized_fields'][$_REQUEST['mt']]) && !$collapsed ) {
               $displaytmp = "<span class=\"formatted_serialized\">\n";
               $displaytmp .= " ".testArray(unserialize($tmp))."\n";
               $displaytmp .= "</span>";
               $tmp = $displaytmp;
               $serialized = true;
            }

            # Encoded data field?
            if ( array_key_exists($fieldname[$x], $_SESSION['decode_fields'][$_REQUEST['mt']]) && !$collapsed ) {

               $displaytmp = "<span class=\"formatted_serialized\">\n";
               if($tmp != "&nbsp;"){
	               $displaytmp .= base64_decode($tmp)."\n";
	             } else {
								$displaytmp .= "\n";
	             }
  	           $displaytmp .= "</span>";
               $tmp = $displaytmp;
               $encoded = true;
            }

   			if ( $tmp != "&nbsp;" && !array_key_exists($fieldname[$x], $_SESSION['timestamp_fields'][$_REQUEST['mt']]) && !$serialized && !$encoded) { $tmp = htmlspecialchars($tmp); }	// Bugzilla #12

   			$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=TOP BGCOLOR=$BGCOLOR>".$tmp."</TD>\n";
   		}
		}

		$THIS_DISPLAY .= "\n</TR>\n";

	}


	# [hide]
	if ( $_SESSION['dtm_collapse_option'][$_REQUEST['mt']] == "on" ) {
      $THIS_DISPLAY .= " <tr>\n";
      $THIS_DISPLAY .= "  <td colspan=\"2\" align=\"right\" class=\"col_title\" style=\"font-weight: normal;\">Click to hide column:</td>\n";

   	for ( $x = 0; $x <= $numberFields; $x++ ) {
   		$fieldname[$x] = mysql_field_name($result, $x);

   		# Do not show if hidden by user
   		if ( !array_key_exists($fieldname[$x], $_SESSION['dtm_hidden_fields'][$_REQUEST['mt']]) ) {
   	   	$THIS_DISPLAY .= "  <td class=\"col_title\" align=\"center\" valign=\"top\" style=\"font-weight: normal;\">\n";
            $THIS_DISPLAY .= "   [<a href=\"".$base_href."&hide_field=".$fieldname[$x]."\" >hide</a>]\n";
      		$THIS_DISPLAY .= "  </td>\n";
      	} // End if !array_key_exists -- field is not in hidden list
   	}

      $THIS_DISPLAY .= " </tr>\n";

   	# hidden field1, hidden field2, etc
   	# Show list of hidden fields with option to un-hide them?
   	if ( count($_SESSION['dtm_hidden_fields'][$_REQUEST['mt']]) > 0 ) {
         $THIS_DISPLAY .= " <tr>\n";
         $THIS_DISPLAY .= "  <td colspan=\"2\" class=\"col_title\" align=\"right\" style=\"font-weight: normal;\">Restore hidden columns:</td>\n";
         $THIS_DISPLAY .= "  <td colspan=\"".$numberFields."\" class=\"col_title\">\n";
      	foreach ( $_SESSION['dtm_hidden_fields'][$_REQUEST['mt']] as $field=>$value ) {
      	   $THIS_DISPLAY .= "   <a href=\"".$base_href."&show_field=".$field."\" class=\"white\">".$field."</a> <span style=\"font-weight: normal;\">|</span> ";
      	}
      	$THIS_DISPLAY .= "  </td>\n";
      	$THIS_DISPLAY .= " </tr>\n";
      }

   } // End if collapse options = on



	$THIS_DISPLAY .= "\n</TABLE>\n\n";

	$THIS_DISPLAY .= "<BR>";

	# '<< Previous 10' and 'Next 10 >>' links for large tables
	#---------------------------------------------------------------------------
	if ($noShowFlag == 0) {

			$prev = $start_show - $num_to_show;
			if ($start_show > 0) {
				$THIS_DISPLAY .= "<a href=\"enter_edit_data.php?mt=$mt&start_show=$prev\"><< ".lang("Previous")." $num_to_show</a>";
			}

			$next = $start_show + $num_to_show;
			if ($next < $total_recs) {
				$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				$THIS_DISPLAY .= "<a href=\"enter_edit_data.php?mt=$mt&start_show=$next\">".lang("Next")." $num_to_show >></a>";
			}

			$THIS_DISPLAY .= "<BR>";

				// Build Inside Page Links
				// =======================================================

				$THIS_DISPLAY .= "<DIV ALIGN=CENTER STYLE='font-family: arial; font-size: 7pt; color: maroon; padding: 8px;'>";
				$s = 0;
				$s_display = $s+1;
				$f = $num_to_show;
				$THIS_DISPLAY .= "<a href=\"enter_edit_data.php?mt=$mt&start_show=$s\" CLASS=sup>$s_display-$f</a>&nbsp;&nbsp;";

				$tmp = $total_recs/$num_to_show;
				$tmp = round($tmp);

				$br_count = 0;

				for ($z=1;$z<=$tmp;$z++) {

					if ($br_count == 16) {
						$THIS_DISPLAY .= "<BR>";
						$br_count = 0;
					}

					if ($f < $total_recs) {
						$s = $z*$num_to_show;
						$s_display = $s+1;
						$f = $s+$num_to_show;
						if ($f > $total_recs) { $f = $total_recs; }
						$THIS_DISPLAY .= "&nbsp;&nbsp;<a href=\"enter_edit_data.php?mt=$mt&start_show=$s\" CLASS=sup>$s_display-$f</a>&nbsp;&nbsp;";
						$br_count++;
					} // End Safe Count

				}

				$THIS_DISPLAY .= "</DIV>";

	} // End No Show Flag

		// =======================================================

} // End if NO ACTION submitted

//$THIS_DISPLAY .= "  </td>\n";
//$THIS_DISPLAY .= " </tr>\n";
//echo $THIS_DISPLAY .= "</table>\n";
$THIS_DISPLAY .= "</DIV>";




###::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
### NEW MODULE OBJECT
###::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//$dMod = new feature_module("", "DATABASE_LAYER");
//$ttl = lang("Table Manager: Enter/Edit Record Data")." '".$mt."'";
//$dMod->add_fgroup($ttl, $THIS_DISPLAY);
//echo $dMod->make_module();

//echo $THIS_DISPLAY;

####################################################################
### Added feature to return display to previous display after editing or deleting a record - Cameron A
//unset($_SESSION[$_REQUEST['mt']]['last_page_view']);
if(count($_GET) > 0 && $_GET['ACTION'] == ''){
	foreach($_GET as $gvar=>$gval){
		$last_page_view[$gvar]=$gval;
	}
	$_SESSION[$_REQUEST['mt']]['last_page_view']=$last_page_view;
} elseif(count($_POST) > 0 && $_POST['ACTION'] == ''){
	foreach($_POST as $gvar=>$gval){
		$last_page_view[$gvar]=$gval;
	}
	$_SESSION[$_REQUEST['mt']]['last_page_view']=$last_page_view;
}


echo $THIS_DISPLAY;

echo '<script type="text/javascript">'."\n";
echo '// Prevents side-scroll bars on container div from cutting off custom query popup and stuff'."\n";
echo 'var table_height = $(\'#db-table-htmltable\').height();'."\n";
echo 'if ( table_height < 410 ) {'."\n";
echo '	$(\'#db-table-container\').height(450);'."\n";
echo '}'."\n";
//echo 'var table_height2 = $(\'#left-panel\').height();'."\n";
//echo '$(\'#db-maindiv\').height(table_height2);'."\n";

echo '</script>'."\n";



echo "</div>\n";

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$module = new smt_module($module_html);
$module->add_breadcrumb_link("Database Table Manager", "program/modules/mods_full/download_data.php");
$module->add_breadcrumb_link("Table: ".$mt, "program/modules/mods_full/database_manager/enter_edit_data.php?mt=$mt&start_show=$s");
$module->icon_img = "program/includes/images/data-tables-icon-med.png";

//$THIS_DISPLAY .= "<h3>Table: ".$_REQUEST['mt']."</h3>";
if($_REQUEST['mt'] == ''){
	$module->heading_text = "Database Table Manager";	
} else {
	$module->heading_text = "Database Table Manager - ".$_REQUEST['mt'];
}

$intro_text = "Manage your MySQL database tables and create db table search forms.";
$module->description_text = $intro_text;

$module->good_to_go();
?>