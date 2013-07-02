<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
//
//foreach($_POST as $xa=>$xv) {
//   if(!eregi('DROPSEARCH_', $xa) && $xv == '') {
//      unset($_POST[$xa]);
//   }
//}

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

##################################################################
### FIND THE PAGE THAT THIS SCRIPT IS RUNNING ON SO THAT WE CAN
### POST ALL DATA BACK TO THE SAME PAGE.  THIS WAY THIS FUNCTION
### ONLY REQUIRES A SINGLE PAGE TO OPERATE.
##################################################################

$tmp = $PHP_SELF;
$tmp_root = split("/", $tmp);
$tmp_cnt = count($tmp_root);
$tmp_cnt--;
$link_page = $tmp_root[$tmp_cnt];

reset($HTTP_POST_VARS);
while (list($name, $value) = each($HTTP_POST_VARS)) {
		$value = htmlspecialchars($value);	// Bugzilla #13
		${$name} = $value;
}

##################################################################
### STANDARDIZE CONFIGURATION SECTION : MODIFIED BY THE SEARCH
### WIZARD WHEN OUTPUTING FINAL INCLUDE FILE.
##################################################################

#-WIZARD_VARS-#



#################################################################
### MANAGE RECORD EDIT/UPDATE NOW BEFORE WE CLOUD VARIABLE
### DATA WITH CHANGES.
#################################################################

if ($SAVE_UPDATED_REC == "ON") {

	$ulerr = 0;

	if ($FILE1 != "none" && $FILE1 != "" && $FILE1['name'] != '') {			// Is there even a file here to upload?

		$filename = "FILE1_name";
		$filename = ${$filename};
		$filesize = "FILE1_size";
		$filesize = ${$filesize};

		$FILE = "FILE1";
		$FILE = ${$FILE};


		// -----------------------------------------------------------------------
		// Only allow .JPG and .GIF Images Under 60K, otherwise some idiot will
		// try to upload a 300dpi scanned image of his dog or something!
		// -----------------------------------------------------------------------

		$fileok = 0;

		$checkfor = strtolower($filename);
		$filename = eregi_replace(" ", "_", $filename);
		// $filename = sterilize($filename);


		if (strstr($checkfor, ".gif") || strstr($checkfor, ".jpg") || strstr($checkfor, ".jpeg")) {

			$filename = $filename;

			$newfile = "$doc_root/images/UDT-UPLOAD_".$filename;
			if ($filesize < 60000) { $fileok = 1; }

			$DATA_TABLE_FILENAME = "UDT-UPLOAD_".$filename;		// Bad habit of reusing $filename alot!
		}

		if($fileok == 1 ) {
# Security FIX uncomment to fixcomment
//				$newfile = stripslashes($newfile);
//				$tempfile = stripslashes($FILE);
//				@unlink($newfile);
//
//				if(@copy($tempfile, $newfile)) { $ulerr = 0; } else { $ulerr = 1; }

		} else {

				$ulerr = 2;

		}

		if ($ulerr == 1) { echo "The server timed out during upload. Retry using the back button."; exit; }
		if ($ulerr == 2) { echo "This file is larger than 60k or is not a valid file type."; exit; }

	} // End if there is even a file to upload check


	// Let's Update the Data Table to Reflect the user changes.  Again, we must take the
	// long way around the bend to do this because we don't know what kind of data is
	// used in the table...

	$SQL_STRING = "UPDATE $TABLE_NAME SET ";
	$tmp_date = "";								// Prepare our tmp date string register
	$tmp_time = "";								// Prepare our tmp time string register as well

	reset($HTTP_POST_VARS);
	while (list($name, $value) = each($HTTP_POST_VARS)) {

		$value = eregi_replace("\n", " ", $value);
		$value = eregi_replace("\r", "", $value);

		$value = stripslashes($value);		// First strip all slashes for insurance and refreshes
		$value = addslashes($value);		// Now add slashes for proper mysql data storage

		if (ereg("VALUE_", $name) && !ereg("_DATEYEAR", $name) && !ereg("_DATEMONTH", $name) && !ereg("_DATEDAY", $name) && !ereg("_TIMEHOUR", $name) && !ereg("_TIMEMIN", $name)) {		// This is a proper value
			$name = ereg_replace("VALUE_", "", $name);

			if ($name == "AUTO_IMAGE" && $FILE1 != "none") {
				$value = "$DATA_TABLE_FILENAME";
				if ($DATA_TABLE_FILENAME == "") { $value = $VALUE_AUTO_IMAGE; }
			}

			if (eregi("Email", $name)) { $BRAND_NEW_EMAIL = $value; }

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
	$SQL_STRING .= " WHERE prikey = '".$recid."'";

	// echo "<TT>$SQL_STRING</TT>";
	// exit;

	if ( !mysql_query("$SQL_STRING") ) { // UPDATE DATA NOW!
	   //echo "<hr>$SQL_STRING<hr><br>";
	   echo "Unable to save record data! <br><br>\n";
	   echo "Check your information for problematic characters like single (apostrophe) and double (standard) quotes, \n";
	   echo "commas, ampersands (&amp;), semicolons, question marks, etc.<br><br>\n";
	   echo "\n\n\n<!---".mysql_error()."--->\n\n\n"; exit;
	}

	echo "<FONT COLOR=RED FACE=VERDANA SIZE=2><B>[ Record Update Complete ]</B></FONT><BR><BR>";

} // End Save/Update Rec Function

##################################################################
### CHECK SECURITY CLEARANCE
##################################################################

	$ALLOW_ACCESS_DB = 0;

	if ($GROUPS != "") {

		$grp_tmp = split(";", $GROUPS);		// Split this user's sec code groups into individual array
		$grp_cnt = count($grp_tmp);			// How Many sec groups does this user have access to?

		for ($gl=0;$gl<=$grp_cnt;$gl++) {	// Check Users Access level against required sec level for this search
			if ($grp_tmp[$gl] != "") {
				if (eregi("$SEARCH_SECURITY_CODE", $grp_tmp[$gl])) { $ALLOW_ACCESS_DB = 1; }
			}
		}

	} // End if $GROUPS isset

	if ($SEARCH_SECURITY_CODE == "Public") { $ALLOW_ACCESS_DB = 1; }

	if ($ALLOW_ACCESS_DB != 1) {
		$SDB_STEP_CONTROL = "10000";	// Just don't display anything to unauthorized user
	}

##################################################################
### START INCLUDE FORM DATA.  ITS THE SAME NO MATTER WHICH STEP
### THAT WE ARE EXECUTING.
##################################################################

echo "<FORM id=\"db_searchform\" NAME=SDB METHOD=POST ACTION=\"$link_page\">\n";
echo "<INPUT TYPE=HIDDEN NAME=pr VALUE=\"$pr\">\n";			// pr variable is provided by the template-builder program

##################################################################
//
//   ########
//   ##    ##
//       ##
//      ##
//     ##
//    ##
//    ##     ##
//    #########
//
//   INITIAL DISPLAY RESULTS (A)
##################################################################

if ($SDB_STEP_CONTROL == 2) {

	$dropdown_activation = 0;	// Zero dropdown & keyword activation flags
	$keyword_activation = 0;

	$sort_pass = "";

	// Normal Form Submit Read
	// -------------------------------------------------------

	reset($HTTP_POST_VARS);
	while (list($name, $value) = each($HTTP_POST_VARS)) {
		$value = stripslashes($value);			// Strip all slashes from passed data
		${$name} = $value;
		if (eregi("DROPSEARCH_", $name)) { $dropdown_activation = 1; }
		if (eregi("KEY_SEARCH_FOR", $name)) { $keyword_activation = 1; }
		$sort_pass .= "&$name=$value";
	}

	// In Case of Sort Command
	// -------------------------------------------------------

	reset($HTTP_GET_VARS);
	while (list($name, $value) = each($HTTP_GET_VARS)) {
		$value = stripslashes($value);			// Strip all slashes from passed data
		${$name} = $value;
		if (eregi("DROPSEARCH_", $name)) { $dropdown_activation = 1; }
		if (eregi("KEY_SEARCH_FOR", $name)) { $keyword_activation = 1; }
		$sort_pass .= "&$name=$value";
	}

	// Find which fields are to be search by keyword routine and
	// place them into $search_fields var

	$search_fields = "";
	$initital_display = "";

	$result = mysql_query("SELECT * FROM $TABLE_NAME");
	$numberFields = mysql_num_fields($result);
	$numberFields--;

	for ($x=0;$x<=$numberFields;$x++) {

		$fieldname[$x] = mysql_field_name($result, $x);
		$fieldname[$x] = $fieldname[$x];

		$fieldtype[$x] = mysql_field_type($result, $x);
		$fieldtype[$x] = strtoupper($fieldtype[$x]);

		$fieldlength[$x] = mysql_field_len($result, $x);
		$meta = mysql_fetch_field($result, $x);
		$field_keyflag[$x] = $meta->primary_key;

		$tmp = "KEYWORD_SEARCH_".$fieldname[$x];
		if (${$tmp} == "on") { $search_fields .= "$fieldname[$x],"; }

		$tmp = "DISPLAY_".$fieldname[$x];
		if (${$tmp} == "I" || ${$tmp} == "B") { $initial_display .= "$fieldname[$x],"; }

	}


	$str_tmp = strlen($search_fields);
	$str_new = $str_tmp - 1;
	$search_fields = substr($search_fields, 0, $str_new);		// Remove extra comma in string

	// In case a single field was selected to search via keywords
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	if ($KEY_FIELD_SEARCH != "all") {
		$search_fields = $KEY_FIELD_SEARCH;
		$search_fields = rtrim($search_fields);
		$search_fields = ltrim($search_fields);	// Trim extra spaces from right and left of drop down selection
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	$str_tmp = strlen($initial_display);
	$str_new = $str_tmp - 1;
	$initial_display = substr($initial_display, 0, $str_new);	// Remove extra comma in string

	$SQL_SEARCH = "";			// Clear search string

	if ($SQL_LAST_SEARCH == "") {		// Check for return from Details Page

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		//if ($SEARCH_BOOL == "" && $dropdown_activation == 1) {
		if (($SEARCH_BOOL == "" || $SEARCH_BOOL=='SELONLY') && $dropdown_activation == 1) {
			# This means that there is no keyword search activated and
			# the entire search is based on drop down selection

			// Make sure to select prikey so detail link doesn't bomb
			//===============================================================
			if ( !eregi("prikey", $initial_display) ) { $initial_display = "PRIKEY,".$initial_display; }

			$SQL_SEARCH .= "SELECT $initial_display FROM $TABLE_NAME WHERE (";
			$found1 = false;
			$drop_down_empty_search = '';
			
			reset($HTTP_POST_VARS);
			
			while (list($name, $value) = each($HTTP_POST_VARS)) {
				$value = stripslashes($value);			// Strip all slashes from passed data
				if (eregi("DROPSEARCH_", $name)) {
					$tmp = eregi_replace("DROPSEARCH_", "", $name);
					if ($value != "") {
						$SQL_SEARCH .= "$tmp LIKE '$value' AND ";
						$found1 = true;
					} else {
						// Cameron Fix so that searching all works again.
						$SQL_SEARCH_OTHER .= "$tmp <> 'NULL' OR ";
						// If you comment this back in it will cause the problem where selecting a value from one dropdown but leaving other dropdowns blank
						// will select all records because it does a "field = value or [all other dd field options] == <>"
						//						$SQL_SEARCH_OTHER .= "$tmp <> 'NULL' OR ";

					}
				}

			} // End While Loop

			if($found1==false){
				$SQL_SEARCH .= $drop_down_empty_search;
			}

      	// In Case of Sort Command
      	// -------------------------------------------------------

			reset($HTTP_GET_VARS);
			while (list($name, $value) = each($HTTP_GET_VARS)) {
				$value = stripslashes($value);			// Strip all slashes from passed data

				if (eregi("DROPSEARCH_", $name)) {
					$tmp = eregi_replace("DROPSEARCH_", "", $name);
					if ($value != "") {
						$SQL_SEARCH .= "$tmp LIKE '$value' AND ";
						$found1 = true;
					} else {
						$SQL_SEARCH_OTHER .= "$tmp <> 'NULL' OR ";
					}
				}

			} // End While Loop

			if(!$found1){
			   $SQL_SEARCH .= $SQL_SEARCH_OTHER;
			}

			$str_tmp = strlen($SQL_SEARCH);
			$str_new = $str_tmp - 4;
			$SQL_SEARCH = substr($SQL_SEARCH, 0, $str_new);	// Remove extra comma in string
			$SQL_SEARCH .= ")";

		} // End if only drop down search

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		if (($SEARCH_BOOL == "" || $SEARCH_BOOL == "KEYONLY") && $keyword_activation == 1) {

			// This means that there is no dropdown selection activated and
			// the entire search is based on a keyword search

			$SQL_SEARCH .= "SELECT * FROM $TABLE_NAME WHERE ";

			$fn_array = split(",", $search_fields);
			$fn_array_cnt = count($fn_array);

			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
			// Now Generate Search Strings
			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

			if ($KEY_SEARCH_FOR == "") {	// This must be an "ALL" search

					for ($x=0;$x<=$fn_array_cnt;$x++) {
						if ($fn_array[$x] != "") {
							$SQL_SEARCH .= "$fn_array[$x] <> 'NULL' OR ";
						}
					}

					$str_tmp = strlen($SQL_SEARCH);
					$str_new = $str_tmp - 4;
					$SQL_SEARCH = substr($SQL_SEARCH, 0, $str_new);	// Remove extra comma in string

			} else {						// Then; this means keywords have been entered

					$KEY_SEARCH_FOR = addslashes($KEY_SEARCH_FOR); 	// Some ass will use ' or " in search
					$KEY_SEARCH_FOR = strtoupper($KEY_SEARCH_FOR);	// Make search case insensitive
					$tmp = split(" ", $KEY_SEARCH_FOR);
					$key_cnt = count($tmp);

					for ($y=0;$y<=$fn_array_cnt;$y++) {				// Outer Loop for field names

					$SQL_SEARCH .= "(";

						for ($x=0;$x<=$key_cnt;$x++) {				// Inner Loop for keywords

							if ($tmp[$x] != "" && $fn_array[$y] != "") { $SQL_SEARCH .= "UPPER($fn_array[$y]) LIKE '%$tmp[$x]%' OR "; }

						} // End inner loop

						$str_tmp = strlen($SQL_SEARCH);
						$str_new = $str_tmp - 4;
						$SQL_SEARCH = substr($SQL_SEARCH, 0, $str_new);	// Remove extra OR in string


					$SQL_SEARCH .= ") OR ";

					} // End outer loop

					$str_tmp = strlen($SQL_SEARCH);
					$str_new = $str_tmp - 6;
					$SQL_SEARCH = substr($SQL_SEARCH, 0, $str_new);	// Remove extra AND in string

			} // End Search String Build

		} // End Keyword Only Search Build

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		if ($keyword_activation == 1 && $dropdown_activation == 1 && $SQL_SEARCH == "") {	// This is a dual search with a BOOL value

			if ($SEARCH_BOOL == "KEYANDSEL") {
					$BOOLEAN_OPER = "AND";
			}

			if ($SEARCH_BOOL == "KEYORSEL") {
					$BOOLEAN_OPER = "OR";
			}

			$SQL_SEARCH = "SELECT * FROM $TABLE_NAME WHERE (";

			$fn_array = split(",", $search_fields);
			$fn_array_cnt = count($fn_array);

			// ############################################
			// STEP 1: COMPILE KEYWORDS FOR SEARCH CRITERIA
			// ############################################

			if ($SEARCH_BOOL != "SELONLY") {

						if ($KEY_SEARCH_FOR == "") {	// This must be an "ALL" search

								for ($x=0;$x<=$fn_array_cnt;$x++) {
									if ($fn_array[$x] != "") {
										$SQL_SEARCH .= "$fn_array[$x] <> 'NULL' OR ";
									}
								}

								$str_tmp = strlen($SQL_SEARCH);
								$str_new = $str_tmp - 4;
								$SQL_SEARCH = substr($SQL_SEARCH, 0, $str_new);	// Remove extra OR in string

						} else {						// Then; this means keywords have been entered

								$KEY_SEARCH_FOR = addslashes($KEY_SEARCH_FOR); 	// Some ass will use ' or " in search
								$KEY_SEARCH_FOR = strtoupper($KEY_SEARCH_FOR);	// Make search case insensitive
								$tmp = split(" ", $KEY_SEARCH_FOR);
								$key_cnt = count($tmp);

								for ($y=0;$y<=$fn_array_cnt;$y++) {				// Outer Loop for field names

								$SQL_SEARCH .= "(";

									for ($x=0;$x<=$key_cnt;$x++) {				// Inner Loop for keywords

										if ($tmp[$x] != "" && $fn_array[$y] != "") { $SQL_SEARCH .= "UPPER($fn_array[$y]) LIKE '%$tmp[$x]%' OR "; }

									} // End inner loop

								$str_tmp = strlen($SQL_SEARCH);
								$str_new = $str_tmp - 4;
								$SQL_SEARCH = substr($SQL_SEARCH, 0, $str_new);	// Remove extra OR in string

								$SQL_SEARCH .= ") OR ";

								} // End outer loop

								$str_tmp = strlen($SQL_SEARCH);
								$str_new = $str_tmp - 6;
								$SQL_SEARCH = substr($SQL_SEARCH, 0, $str_new);	// Remove extra AND in string

						} // End Search String Build

						$SQL_SEARCH .= ") $BOOLEAN_OPER (";

			} // End Drop Down Only Check

			// ############################################
			// STEP 2: COMPILE DROP DOWN BOX SELECTIONS
			// Display for selection only
			// ############################################

			//echo "<TT><font color=red style='font-size: 9pt;'>0[</font>$SQL_SEARCH<font color=red>]</font></TT><BR><BR>";

			reset($HTTP_POST_VARS);
			while (list($name, $value) = each($HTTP_POST_VARS)) {
				$value = stripslashes($value);			// Strip all slashes from passed data

				if (eregi("DROPSEARCH_", $name)) {
					$tmp = eregi_replace("DROPSEARCH_", "", $name);
					if ($value != "") {
						$SQL_SEARCH .= "$tmp = '".str_replace(".", "_", $value)."' AND ";
						//echo "1(".$SQL_SEARCH.")<br/>";
						$foundVal = 1;
				   }
				}

			} // End While Loop
			//echo "<TT><font color=red style='font-size: 9pt;'>1[</font>$SQL_SEARCH<font color=red>]</font></TT><BR><BR>";
			// In Case of Sort Command
			// -------------------------------------------------------

			reset($HTTP_GET_VARS);
			while (list($name, $value) = each($HTTP_GET_VARS)) {
				$value = stripslashes($value);			// Strip all slashes from passed data

				if (eregi("DROPSEARCH_", $name)) {
					$tmp = eregi_replace("DROPSEARCH_", "", $name);
					if ($value != "") {
						$SQL_SEARCH .= "$tmp = '".str_replace(".", "_", $value)."' AND ";
						//echo "1(".$SQL_SEARCH.")<br/>";
						$foundVal = 1;
				   }
				}
			}
			//echo "<TT><font color=red style='font-size: 9pt;'>2[</font>$SQL_SEARCH<font color=red>]</font></TT><BR><BR>";

			if($foundVal == 1){
   			$str_tmp = strlen($SQL_SEARCH);
   			$str_new = $str_tmp - 4;
   			$SQL_SEARCH = substr($SQL_SEARCH, 0, $str_new);	// Remove extra comma in string
			   $SQL_SEARCH .= ")";
   		}else{
   			$str_tmp = strlen($SQL_SEARCH);
   			$str_new = $str_tmp - 8;
   			$SQL_SEARCH = substr($SQL_SEARCH, 0, $str_new);	// Remove extra comma in string
   		}
   		//echo "<TT><font color=red style='font-size: 9pt;'>3[</font>$SQL_SEARCH<font color=red>]</font></TT><BR><BR>";

		} // End Dual Search

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		// Do Search Now... echo line is for testing purposes only and should
		// remain commented out.  If you are having trouble with searches, this
		// line will print to the screen the final SQL_SEARCH string used to
		// locate client search
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		// echo "<TT><font color=red style='font-size: 9pt;'>[</font>$SQL_SEARCH<font color=red>]</font></TT><BR><BR>";

	} else { 	// End if $SQL_LAST_SEARCH is blank

		$SQL_SEARCH = stripslashes($SQL_LAST_SEARCH);

	}

	// Parse out trouble characters
	//============================================================
	$SQL_SEARCH = str_replace(".", "_", $SQL_SEARCH);
   //echo "<textarea style=\"width: 500px; height: 350px;\">$SQL_SEARCH</textarea>"; exit;

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// Build Sort Routine
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	if ($SORT_ORDER == "") {
		$SORT_ORDER = "ORDER BY PRIKEY";
	} else {
		$SORT_ORDER = "ORDER BY ".$SORT_ORDER;
	}

	// Perform actual query now
	// ------------------------------------

	echo "\n\n<!-- \n\n$SQL_SEARCH $SORT_ORDER\n\n -->\n\n";

	if(!$result = mysql_query("$SQL_SEARCH $SORT_ORDER")){
		echo "\n\n<!-- \n\n".mysql_error()."\n\n -->\n\n";
	}else{
		echo "\n\n<!-- \n\nOK!\n\n -->\n\n";
	}
	$numfound = mysql_num_rows($result);

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// Now display INITIAL results to end-user
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	if ($numfound > 0) {			// We found matches to this search

		echo "<CENTER><FONT STYLE='font-family: Arial; font-size: 9pt; color: darkblue;'>\n";
		echo "There are [$numfound] record(s) that meet your search criteria.<BR><BR></FONT>\n\n";

		echo "<INPUT TYPE=HIDDEN NAME=\"SDB_STEP_CONTROL\" VALUE=\"\">\n";
		echo "<INPUT TYPE=BUTTON VALUE=\" Print Results \" ONCLICK=\"Javascript: window.print();\" CLASS=FormLt1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
		echo "<INPUT TYPE=SUBMIT VALUE=\" New Search \" CLASS=FormLt1></FORM><BR>\n";

		echo "<TABLE BORDER=0 CELLPADDING=8 CELLSPACING=0 ALIGN=CENTER STYLE='border: 1px inset black;' class=text>\n\n";

		$fn_array = split(",", $initial_display);
		$fn_array_cnt = count($fn_array);
		$fn_array_cnt--;

		echo "<TR>\n";

		echo "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=#999999 class=smtext STYLE='border-bottom: 1px solid black;'><B><FONT COLOR=#FFFFFF>Details</FONT></B></TD>\n";

		for ($x=0;$x<=$fn_array_cnt;$x++) {
			$this_field = eregi_replace("_", " ", $fn_array[$x]);
			$this_field = strtolower($this_field);
			$this_field = ucwords($this_field);

   			if ( strtoupper($this_field) != "PRIKEY" ) {
			$SORT_LINK = "<A HREF=\"$link_page?c=d".$sort_pass."&SORT_ORDER=$fn_array[$x]\"><img src='sohoadmin/client_files/sort_image.gif' style='border: 1px dashed black;' border=0 align=absmiddle vspace=0 hspace=2 width=14 alt=\"Sort\"></a>";
			echo "<TD ALIGN=LEFT VALIGN=MIDDLE BGCOLOR=#999999 class=smtext STYLE='border-bottom: 1px solid black;'><B><FONT COLOR=#FFFFFF>".$SORT_LINK."$this_field</FONT></B></TD>\n";
      		}

		}

		echo "</TR>\n";


		/// Display initial results as form with hidden fields containing mysql search info
		###=========================================================================================
		$i = 0;			// Reset Record Count
		while ($row = mysql_fetch_array($result)) {

			echo "<TR>\n";
			if ($BGCOLOR == "WHITE") { $BGCOLOR = "#EFEFEF"; } else { $BGCOLOR = "WHITE"; }

				echo "<FORM METHOD=POST ACTION=\"$link_page\">\n";

					echo "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=$BGCOLOR class=smtext>\n";
					echo "<INPUT TYPE=HIDDEN NAME=pr VALUE=\"$pr\">\n";
					echo "<INPUT TYPE=HIDDEN NAME=\"SDB_STEP_CONTROL\" VALUE=3>\n";
					echo "<INPUT TYPE=HIDDEN NAME=\"SQL_SEARCH\" VALUE=\"$SQL_SEARCH\">\n";
					if($row['PRIKEY'] != '') {
						echo "<INPUT TYPE=HIDDEN NAME=\"ROW_NUM\" VALUE=\"".$row['PRIKEY']."\">\n";
					} elseif($row['prikey'] != '') {
						echo "<INPUT TYPE=HIDDEN NAME=\"ROW_NUM\" VALUE=\"".$row['prikey']."\">\n";
					} else {
						echo "<INPUT TYPE=HIDDEN NAME=\"ROW_NUM\" VALUE=\"".$row['0']."\">\n";
					}
					echo "<INPUT TYPE=IMAGE SRC=\"sohoadmin/client_files/vicon.gif\" ALT=\"View Details\" ALIGN=ABSMIDDLE HSPACE=2 VSPACE=2 BORDER=0 WIDTH=12 HEIGHT=14>\n";
					echo "</TD>\n"; // Edit Tab

				echo "</FORM>\n";

			for ($x=0;$x<=$fn_array_cnt;$x++) {
				$tmp = $row[$fn_array[$x]];
				if ( strtoupper($fn_array[$x]) != "PRIKEY" ) {
				echo "<TD ALIGN=LEFT VALIGN=MIDDLE BGCOLOR=$BGCOLOR class=smtext>$tmp</TD>\n";
			}
			}

			echo "</TR>\n";

			$i++;

		} // End While Loop

		echo "</TABLE>\n";

	} else {

		echo "<CENTER><H5><FONT COLOR=RED face=Verdana size=2>Sorry, there were no records found that match your search.<BR>Please try again.</FONT></H5><BR>\n\n";
		$SDB_STEP_CONTROL = "";

	}

} // End Step Two

##################################################################
//
//     #####
//        ##
//        ##
//        ##
//        ##
//        ##
//        ##
//    #########
//
##################################################################

if ($SDB_STEP_CONTROL == "") {

	echo "<INPUT TYPE=HIDDEN NAME=\"SDB_STEP_CONTROL\" VALUE=2>\n";

	// ------------------------------------------------------------
	// Get all field data about selected use table
	// ------------------------------------------------------------

	$result = mysql_query("SELECT * FROM $TABLE_NAME");
	$numberFields = mysql_num_fields($result);
	$numberFields--;

	$keyword_activation = 0;
	$dropdown_activation = 0;

	for ($x=0;$x<=$numberFields;$x++) {

		$fieldname[$x] = mysql_field_name($result, $x);
		$fieldname[$x] = $fieldname[$x];

		$fieldtype[$x] = mysql_field_type($result, $x);
		$fieldtype[$x] = $fieldtype[$x];

		$fieldlength[$x] = mysql_field_len($result, $x);

		$meta = mysql_fetch_field($result, $x);
		$field_keyflag[$x] = $meta->primary_key;

		$tmp = "KEYWORD_SEARCH_".$fieldname[$x];
		if (${$tmp} == "on") { $keyword_activation = 1; }

		$tmp = "DROPDOWNBOX_".$fieldname[$x];
		if (${$tmp} == "on") { $dropdown_activation = 1; }

	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// Start End-User Form Display
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		// Build Keyword Display
		// ---------------------------------------

		$key_opts = "<select name=\"KEY_FIELD_SEARCH\" class=text>\n<option value=\"all\">All Fields</option>\n";

		$x = 0;				// Reset Field Counter
		$keydisplay = "";	// Reset Keyword Display String
		while ($x <= $numberFields) {

			$tmp_data = "KEYWORD_SEARCH_".$fieldname[$x];
			$tmp_data = ${$tmp_data};

			if ($tmp_data == "on") {
				$tmp_disp = eregi_replace("_", " ", $fieldname[$x]);
				$tmp_disp = strtolower($tmp_disp);
				$tmp_disp = ucwords($tmp_disp);
				$key_opts .= "<option value=\"$fieldname[$x]\">$tmp_disp Only</option>\n";	// Drop Down Selection build
				$keydisplay .= "$fieldname[$x], ";
			}

			$x++;

		} // End While Loop

			$key_opts .= "</select>\n";

		$tmp = strlen($keydisplay);
		$new_tmp = $tmp - 2;
		$keydisplay = substr($keydisplay, 0, $new_tmp);

		$keydisplay = eregi_replace("_", " ", $keydisplay);	// Format Keyword display string for proper viewing
		$keydisplay = strtolower($keydisplay);
		$keydisplay = ucwords($keydisplay);

		$DSP_S = $SEARCH_NAME;
		$DSP_S = eregi_replace("_", " ", $DSP_S);

		$SEARCH_COUNT = 1;

		$USER_FORM = "";
		$USER_FORM .= "<TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 CLASS=text WIDTH=500 ALIGN=CENTER BGCOLOR=#EFEFEF STYLE='border: 1px inset black;'>\n";
		$USER_FORM .= "<TR>\n";
		$USER_FORM .= "<TD ALIGN=LEFT VALIGN=TOP CLASS=text><H4><FONT COLOR=DARKBLUE>SEARCH $DSP_S</FONT></H4>\n";

		if ($keyword_activation == 1) {

			$USER_FORM .= "<B>$SEARCH_COUNT. <U>Search by Keyword</U>: </B> <FONT COLOR=#999999>(Separate multiple keywords by spaces)</FONT><BR><FONT STYLE='font-size: 7pt;'>[ $keydisplay ]</FONT></B><BR><BR>\n";
			$USER_FORM .= "<INPUT TYPE=TEXT NAME=\"KEY_SEARCH_FOR\" SIZE=25 CLASS=text STYLE='WIDTH: 250px; COLOR: darkblue;'>&nbsp;in&nbsp;$key_opts<BR><BR>\n";
			$SEARCH_COUNT++;

		} // End, If keywords are in use

		if ($dropdown_activation == 1) {

			$USER_FORM .= "<B>$SEARCH_COUNT. <U>Detail Search</U>:</B><BR><BR>\n";
			$USER_FORM .= "<TABLE WIDTH=100% CELLPADDING=2 CELLSPACING=0 BORDER=0>\n";

			$x = 0;				// Reset our field counter
			$width_count = 0;	// Place two drop down selections per row; so lets reset

			while ($x <= $numberFields) {

					if ($width_count == 2) {
						$width_count = 0;
						$USER_FORM .= "</TR>\n";
						$spacer_flag = 1;
					}


					$tmp_data = "DROPDOWNBOX_".$fieldname[$x];
					$tmp_sort = "DROPDOWNBOX_".$fieldname[$x]."_SORTORDER";
					$tmp_sort = ${$tmp_sort};

					if (${$tmp_data} == "on") {			// Has drop down been activated for this field?

						if ($width_count == 0) {
							$USER_FORM .= "<TR>";
						}

						$this_option = "<OPTION VALUE=\"\" STYLE='COLOR: #999999;'>All</OPTION>\n";

						$result = mysql_query("SELECT DISTINCT $fieldname[$x] FROM $TABLE_NAME ORDER BY $fieldname[$x] $tmp_sort");	// Index this field
						while ($row = mysql_fetch_array($result)) {
								$v = $row[$fieldname[$x]];
								$this_option .= "<OPTION VALUE=\"$v\">$v</OPTION>\n";
						}

						$display_fn = eregi_replace("_", " ", $fieldname[$x]);
						$display_fn = strtolower($display_fn);
						$display_fn = ucwords($display_fn);

						$USER_FORM .= "<TD ALIGN=LEFT VALIGN=MIDDLE class=text>$display_fn:<br><SELECT NAME=\"DROPSEARCH_$fieldname[$x]\" CLASS=text STYLE='width: 225px;'>\n";
						$USER_FORM .= "$this_option\n";
						$USER_FORM .= "</SELECT></TD>\n";
						$width_count++;

					} // End if Drop Down activated for this field

					$x++;	// Increment our field counter

			} // End Field While Loop

			if ($width_count == 0) { $USER_FORM .= "<TD COLSPAN=3 class=text>&nbsp;</TD></TR>\n"; }
			if ($width_count == 1 && $spacer_falg == 1) { $USER_FORM .= "<TD class=text>&nbsp;&nbsp;&nbsp;&nbsp;</TD></TR>\n"; }
			if ($width_count == 1 && $spacer_flag != 1) { $USER_FORM .= "<TD width=50% class=text>&nbsp;&nbsp;&nbsp;&nbsp;</TD></TR>\n"; }
			if ($width_count == 2) { $USER_FORM .= "</TR>\n"; }

			$USER_FORM .= "</TABLE>\n";				// End Drop Down Table
			$SEARCH_COUNT++;

		} // End, If Dropdown Activation is on

		if ($keyword_activation == 1 && $dropdown_activation == 1) {

			// Show Search Detail Options
			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

			$USER_FORM .= "<BR><BR><B>$SEARCH_COUNT. <U>Define Search Method</U>:</B>&nbsp;\n";
			$USER_FORM .= "<SELECT NAME=\"SEARCH_BOOL\" CLASS=text STYLE='WIDTH: 200px;'>\n";
			$USER_FORM .= "     <OPTION VALUE=\"KEYONLY\" SELECTED>Keyword Only</OPTION>\n";
			$USER_FORM .= "     <OPTION VALUE=\"SELONLY\">Selections Only</OPTION>\n";
			$USER_FORM .= "     <OPTION VALUE=\"KEYANDSEL\">Keyword AND Selections</OPTION>\n";
			$USER_FORM .= "     <OPTION VALUE=\"KEYORSEL\">Keyword OR Selections</OPTION>\n";
			$USER_FORM .= "</SELECT>\n\n";

		} // End, If keyword + dropdown activation

		$USER_FORM .= "<DIV ALIGN=RIGHT><INPUT TYPE=SUBMIT VALUE=\" Search Now \" CLASS=FormLt1></DIV>\n\n";

		$USER_FORM .= "</TD></TR></TABLE></FORM>\n";	// End End-User Display Table

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	echo $USER_FORM;

} // End Step One

##################################################################
//
//   ########
//         ##
//         ##
//      #####
//         ##
//         ##
//         ##
//    #######
//
##################################################################

if ($SDB_STEP_CONTROL == "3") {

	echo "<INPUT TYPE=HIDDEN NAME=\"SDB_STEP_CONTROL\" VALUE=\"\">\n";

	if ($DETAILS_DISPLAY == "C") {				// If this is a custom include details page; is so show now

		include("media/$DETAILS_DISPLAY_INC");

	} else {									// Otherwise; use standard display

		echo "\n\n";
		echo "<SCRIPT LANGUAGE=Javascript>\n\n";
		echo "     function initial_results() {\n";
		echo "          window.document.BACK.submit();\n";
		echo "     }\n\n";
		echo "</SCRIPT>\n\n";

		echo "<CENTER><INPUT TYPE=BUTTON VALUE=\" << Back \" onclick=\"initial_results();\" CLASS=FormLt1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
		echo "<INPUT TYPE=SUBMIT VALUE=\" New Search \" CLASS=FormLt1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
		echo "<INPUT TYPE=BUTTON VALUE=\" Print Record \" onclick=\"javascript: window.print();\" CLASS=FormLt1>\n";
		echo "</FORM><BR>\n\n";

		$result = mysql_query("SELECT * FROM $TABLE_NAME");
		$numberFields = mysql_num_fields($result);
		$numberFields--;

		$detail_display = "";
		for ($x=0;$x<=$numberFields;$x++) {
			$fieldname[$x] = mysql_field_name($result, $x);
			$fieldname[$x] = $fieldname[$x];
			$tmp = "DISPLAY_".$fieldname[$x];
			if (${$tmp} == "D" || ${$tmp} == "B") { $detail_display .= "$fieldname[$x], "; }
		}

		$str_tmp = strlen($detail_display);
		$str_new = $str_tmp - 2;
		$detail_display = substr($detail_display, 0, $str_new);	// Remove extra comma in string

		$SQL_SEARCH = stripslashes($SQL_SEARCH);				// Remove slashes from "posted" transfer
		//$SQL_SEARCH = ereg_replace("SELECT (.*) FROM", "SELECT $detail_display, AUTO_SECURITY_AUTH FROM", $SQL_SEARCH);	// Replace previous "selected fields" with Detail fields

	 	// echo "<TT><font color=red style='font-size: 8pt;'>[</font>$SQL_SEARCH<font color=red>]</font></TT><BR><BR>";

		$EDIT_SECURITY_CHECK = "";					// Clear Security Check for Record Owner

		$result = mysql_query("SELECT * FROM $TABLE_NAME WHERE PRIKEY = '$ROW_NUM'");

		$i = $ROW_NUM;	// Set Row Counter to PriKey Value

		while ($row = mysql_fetch_array($result)) {

				if ($i == $ROW_NUM) {							// Display this record

					$EDIT_SECURITY_CHECK = $row[AUTO_SECURITY_AUTH];  // Does this record have an edit MD5 key?

					if ($row[AUTO_IMAGE] != "NULL" && eregi("AUTO_IMAGE", $detail_display)) {

						// ############################################
						// Show "Image Ready" display for this record
						// ############################################

						$tmp = split(",", $detail_display);
						$tmp_cnt = count($tmp);

						// ---------------------------------------------------------------------------
						// Get Image Data Now because we already know that AUTO_IMAGE has data in it.
						// ---------------------------------------------------------------------------

						$iname = $row[AUTO_IMAGE];
						$iname = chop($iname);
						$iname = ltrim($iname);
						$iname = rtrim($iname);

						$imagename = "$doc_root/images/$iname";

						if (file_exists("$imagename")) {

							$tempArray = getImageSize("$imagename");
							$origW = $tempArray[0];
							$origH = "HEIGHT=" . $tempArray[1];

							if ($origW > 275) { $origW = "275"; $origH = ""; }
							$WH = "WIDTH=$origW $origH";

							$THIS_IMAGE = "<IMG SRC=\"images/$iname\" $WH BORDER=1 VSPACE=1 HSPACE=1 STYLE=\"filter:progid:DXImageTransform.Microsoft.dropshadow(OffX=4, OffY=4, Color='BLACK', Positive='true')\">";

						} else {

							$THIS_IMAGE = "";

						}

						// ---------------------------------------------------------------------------


						echo "<TABLE BORDER=0 CELLPADDING=4 CELLSPACING=0 WIDTH=85% ALIGN=CENTER STYLE='border: 1px inset black; background: #EFEFEF;'>\n";
						echo "<TR><TD ALIGN=LEFT VALIGN=TOP CLASS=text bgcolor=#EFEFEF>&nbsp;</TD><TD ALIGN=LEFT VALIGN=TOP CLASS=text bgcolor=#EFEFEF>&nbsp;</TD><TD ALIGN=CENTER VALIGN=TOP BGCOLOR=#EFEFEF CLASS=text ROWSPAN=$tmp_cnt><BR><BR><BR>$THIS_IMAGE</TD></TR>\n";

						for ($x=0;$x<=$tmp_cnt;$x++) {

								// Format Field for proper match

								$this_field = chop($tmp[$x]);
								$this_field = ltrim($this_field);
								$this_field = rtrim($this_field);

								if ($this_field != "" && $this_field != "AUTO_IMAGE" && $this_field != "AUTO_SECURITY_AUTH") {	// In case of wierd split routine; bi-product of PHP

									if ($row[$this_field] == "NULL") { $display_value = "&nbsp;&nbsp;"; } else { $display_value = $row[$this_field]; }

									// Now Let's add some cool stuff like email and web linking, etc.

									if (eregi("email", $this_field)) { $display_value = "<a href=\"mailto: $display_value\">$display_value</a>"; }
									if (eregi("http://", $display_value)) { $display_value = "<a href=\"$display_value\" target=\"_blank\">$display_value</a>"; }

									$this_field = strtolower($this_field);
									$this_field = ucwords($this_field);
									echo "<tr><td align=right valign=top class=smtext><U>$this_field</U>:</td><td align=left valign=top class=smtext>$display_value</td></tr>\n";

									$tmp_row_counter++;
								}

						} // End For Loop through field names

						// echo "<TR><TD COLSPAN=2 CLASS=text>&nbsp;</TD></TR>\n";	// Visual Spacing

						echo "</TABLE>\n";

						// ======================= END IMAGE DISPLAY =========================


					} else {

						// ############################################
						// Show Non-Image display for this record
						// ############################################

						echo "<TABLE BORDER=0 CELLPADDING=4 CELLSPACING=0 WIDTH=85% ALIGN=CENTER STYLE='border: 1px inset black; background: #EFEFEF;'>\n";

						$tmp = split(",", $detail_display);
						$tmp_cnt = count($tmp);

						$tmp_row_counter = 0;

						for ($x=0;$x<=$tmp_cnt;$x++) {

								if ($tmp_row_counter == 0) { echo "<TR>\n"; }

								// Format Field for proper match

								$this_field = chop($tmp[$x]);
								$this_field = ltrim($this_field);
								$this_field = rtrim($this_field);

								// Check for an authorized user logged in as owner
								// of this record

								if ($this_field != "" && $this_field != "AUTO_IMAGE") {	// In case of wierd split routine; bi-product of PHP

									if ($row[$this_field] == "NULL") { $display_value = "&nbsp;&nbsp;"; } else { $display_value = $row[$this_field]; }

									// Now Let's add some cool stuff like email and web linking, etc.

									if (eregi("email", $this_field)) { $display_value = "<a href=\"mailto: $display_value\">$display_value</a>"; }
									if (eregi("http://", $display_value)) { $display_value = "<a href=\"$display_value\" target=\"_blank\">$display_value</a>"; }

									$this_field = strtolower($this_field);
									$this_field = ucwords($this_field);
									echo "<tr><td align=right valign=top class=text><U>$this_field</U>:</td><td align=left valign=top class=text>$display_value</td></tr>\n";

									$tmp_row_counter++;

								}

								if ($tmp_row_counter == 2) { echo "</TR>\n"; $tmp_row_counter = 0; }

						} // End For Loop through field names

						if ($tmp_row_counter == 1) { echo "<TD CLASS=text>&nbsp;</TD></TR>\n"; } // In case of ODD ending count

						echo "</TABLE>\n";

					} // End Auto_Image Check

				} // End If ROW_NUM is found

				$i++;

		} // End While Loop

	} // End Details Display Type Check

	// ------------------------------------------------------------------
	// Is the owner of the Record logged in via sec login? If so, allow
	// this user to edit record data from site now. (Cool Feature if I
	// do say so myself-- Thanks Jim!)
	// ------------------------------------------------------------------


	if ($GROUPS != "" && $MD5CODE == $EDIT_SECURITY_CHECK) {

		echo "\n\n<FORM NAME=edrec METHOD=POST ACTION=\"$link_page\">\n";
		echo "<INPUT TYPE=HIDDEN NAME=pr VALUE=\"$pr\">\n";
		echo "<INPUT TYPE=HIDDEN NAME=\"SQL_LAST_SEARCH\" VALUE=\"$SQL_SEARCH\">\n";
		echo "<INPUT TYPE=HIDDEN NAME=\"SDB_STEP_CONTROL\" VALUE=\"100\">\n";
		echo "<INPUT TYPE=HIDDEN NAME=\"EDIT_DB_RECORD\" VALUE=\"YES\">\n";
		echo "<INPUT TYPE=HIDDEN NAME=\"ROW_NUM\" VALUE=\"$ROW_NUM\">\n\n";
		echo "<INPUT TYPE=HIDDEN NAME=\"TABLE_NAME\" VALUE=\"$TABLE_NAME\">\n\n";
		echo "<INPUT TYPE=HIDDEN NAME=\"PRIKEY_DATA\" VALUE=\"$ROW_NUM\">\n\n";

		echo "\n\n<!-- LOGGEDIN: $OWNER_EMAIL | THIS RECORD: $SEC_EMAIL_CHECK -->\n\n";

		echo "<FONT COLOR=#999999 STYLE='font-family: Arial; font-size: 8pt;'>Welcome $OWNER_NAME. Since this is <U>your</U> record<BR>information, you may edit this data now.</FONT><BR><BR><INPUT TYPE=SUBMIT VALUE=\" Edit Record \" CLASS=FormLt1>\n";

		echo "</FORM>\n\n";

	}

	// ------------------------------------------------------------------

	echo "</CENTER>\n\n";

	echo "\n\n<FORM NAME=BACK METHOD=POST ACTION=\"$link_page\">\n";
	echo "<INPUT TYPE=HIDDEN NAME=pr VALUE=\"$pr\">\n";
	echo "<INPUT TYPE=HIDDEN NAME=\"SQL_LAST_SEARCH\" VALUE=\"$SQL_SEARCH\">\n";
	echo "<INPUT TYPE=HIDDEN NAME=\"SDB_STEP_CONTROL\" VALUE=2>\n";
	echo "</FORM>\n\n";


} // End Step Three

##################################################################
//
//   ########    ######        ######    ##########
//   ##          ##    ##        ##          ##
//   ##          ##     ##       ##          ##
//   #######     ##      ##      ##          ##
//   ##          ##      ##      ##          ##
//   ##          ##     ##       ##          ##
//   ##          ##    ##        ##          ##
//   ########    #######       ######        ##
//
##################################################################

if ($EDIT_DB_RECORD == "YES") {

		$result = mysql_query("SELECT * FROM $TABLE_NAME");
		$numberFields = mysql_num_fields($result);
		$numberFields--;

		$detail_display = "";
		for ($x=0;$x<=$numberFields;$x++) {
			$fieldname[$x] = mysql_field_name($result, $x);
			$fieldname[$x] = $fieldname[$x];
			$tmp = "DISPLAY_".$fieldname[$x];
			if (${$tmp} == "D" || ${$tmp} == "B") { $detail_display .= "$fieldname[$x], "; }
		}

		$str_tmp = strlen($detail_display);
		$str_new = $str_tmp - 2;
		$detail_display = substr($detail_display, 0, $str_new);	// Remove extra comma in string

		$SQL_SEARCH = stripslashes($SQL_LAST_SEARCH);				// Remove slashes from "posted" transfer
		$SQL_SEARCH = ereg_replace("SELECT (.*) FROM", "SELECT $detail_display FROM", $SQL_SEARCH);	// Replace previous "selected fields" with Detail fields

		$SEC_EMAIL_CHECK = "";					// Clear Security Check for Record Owner One More Time in case we has session thief

		$result = mysql_query("SELECT $detail_display FROM $TABLE_NAME WHERE PRIKEY = '$PRIKEY_DATA'");

		$numberFields = mysql_num_fields($result);
		$numberFields--;

		$i = $PRIKEY_DATA;	// Reset Row Counter

		while ($row = mysql_fetch_array($result)) {

				if ($i == $PRIKEY_DATA) {							// Here's our Key! (Crude but effective)

					for ($z=0;$z<=$numberFields;$z++) {
						$FIELD_DATA[$z] = $row[$z];				// Place data values into array for next section
					}

				} // End if $i

				$i++;	// Must increment count !important!

		} // End While

		// --------------------------------------------------------------------------
		// Present Edit Form for User.  Remember to utilize ENCRYPT-FORM for image
		// upload capability here.  Do we move these images to another folder? You
		// guys figure it out... I've got a deadline. :)
		// --------------------------------------------------------------------------


		echo "</FORM>\n\n";				// Close that great idea we had for all the previous steps. :)

		echo "\n\n<form NAME=\"UPDATERECFORM\" enctype=\"multipart/form-data\" action=\"$link_page\" method=\"POST\">\n\n";

		echo "<INPUT TYPE=HIDDEN NAME=pr VALUE=\"$pr\">\n";
		echo "<INPUT TYPE=HIDDEN NAME=\"SQL_LAST_SEARCH\" VALUE=\"$SQL_SEARCH\">\n";
		echo "<INPUT TYPE=HIDDEN NAME=\"customernumber\" VALUE=\"$customernumber\">\n";
		echo "<input type=hidden name=\"recid\" value=\"".$PRIKEY_DATA."\">\n\n\n";
		echo "<INPUT TYPE=HIDDEN NAME=\"SAVE_UPDATED_REC\" VALUE=\"ON\">\n\n\n";
		echo "<INPUT TYPE=HIDDEN NAME=\"TABLE_NAME\" VALUE=\"$TABLE_NAME\">\n\n\n";

		$EDIT_FLAG = "on";		// This routine was used in the tool as well; let's just keep it the same and force feed the edit

		echo "<TABLE BORDER=0 WIDTH=500 CELLPADDING=5 CELLSPACING=1 CLASS=text ALIGN=CENTER style='border: 1px inset #CCCCCC; background: #CCCCCC;'>\n";

		$edit_tmp = "";

		for ($x=0;$x<=$numberFields;$x++) {

			if ($BGCOLOR == "WHITE") { $BGCOLOR="#EFEFEF"; } else { $BGCOLOR="WHITE"; }

			echo "<TR>\n";
			echo "<TD ALIGN=RIGHT VALIGN=TOP BGCOLOR=$BGCOLOR class=smtext>\n";

			$fieldname[$x] = mysql_field_name($result, $x);
			$fieldname[$x] = $fieldname[$x];
			$fieldtype[$x] = mysql_field_type($result, $x);
			$fieldlength[$x] = mysql_field_len($result, $x);
			$fieldtype[$x] = strtoupper($fieldtype[$x]);

			$meta = mysql_fetch_field($result, $x);

			if ($EDIT_FLAG == "on") {
				$this_val = addslashes($FIELD_DATA[$x]);
				if ($this_val != "") {
					$edit_tmp .= "$fieldname[$x] LIKE '".str_replace(".", "_", $this_val)."' AND ";
				}
			}

			$display_fieldname = eregi_replace("_", " ", $fieldname[$x]);	// Format Field names for screen display
			$display_textbox = "MAXLENGTH=$fieldlength[$x]";     			// Make sure textbox entry can be no longer than set field length

			echo "<B><U>$display_fieldname</U>&nbsp;&nbsp;<FONT STYLE='font-size: 7pt;'>($fieldtype[$x])</FONT>&nbsp;:</B>\n";
			echo "</TD><TD VALIGN=TOP ALIGN=LEFT BGCOLOR=$BGCOLOR>";

			if ($fieldtype[$x] == "STRING" || $fieldtype[$x] == "INT") {

				if ($fieldname[$x] != "AUTO_IMAGE") {

					if ($meta->primary_key == 1) {

						$DIS = "DISABLED";
						$this_value = "NULL";

						if ($FIELD_DATA[$x] != "") {
							echo "<INPUT TYPE=HIDDEN NAME=\"VALUE_$fieldname[$x]\" VALUE=\"$FIELD_DATA[$x]\">\n";
							$this_value = $FIELD_DATA[$x];
						} else {
							echo "<INPUT TYPE=HIDDEN NAME=\"VALUE_$fieldname[$x]\" VALUE=\"NULL\">\n";
						}

					} else {

						$DIS = "";
						$this_value = "$FIELD_DATA[$x]";

					}

					echo "<INPUT $DIS $display_textbox TYPE=TEXT NAME=\"VALUE_$fieldname[$x]\" VALUE=\"$this_value\" CLASS=smtext STYLE='WIDTH: 200px;'>\n";

				} else {

					echo "<SELECT NAME=\"VALUE_$fieldname[$x]\" CLASS=smtext STYLE='WIDTH: 200px;'>\n";
					echo "<OPTION VALUE=\"$FIELD_DATA[$x]\" SELECTED>$FIELD_DATA[$x]</OPTION>\n";
					echo "<OPTION VALUE=\"NULL\">Do not display image</OPTION>\n";
					echo "</SELECT>\n";

				} // End Auto-Image Check

			} // End STING/INT Check

			// ---------------------------------------------------------
			// Check for BLOB Field Now
			// ---------------------------------------------------------

			if ($fieldtype[$x] == "BLOB" || $fieldtype[$x] == "LONGBLOB") {
				echo "<TEXTAREA NAME=\"VALUE_$fieldname[$x]\" ROWS=10 CLASS=smtext STYLE='WIDTH: 200px; HEIGHT: 100px;'>$FIELD_DATA[$x]</TEXTAREA>\n";
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

				echo "<SELECT NAME=\"VALUE_$fieldname[$x]_DATEMONTH\" CLASS=smtext STYLE='WIDTH: 50px;'>\n";
				for ($z=1;$z<=12;$z++) {
					$display_month = date("M", mktime(0,0,0,$z,1,$this_year));
					$v = date("m", mktime(0,0,0,$z,1,$this_year));
					$SEL = "";
					if ($F_DATA[1] == $v) { $SEL = "SELECTED"; }
					if ($F_DATA[1] == "" && $this_month == $display_month) { $SEL = "SELECTED"; }
					echo "<OPTION $SEL VALUE=\"$v\">$display_month</OPTION>\n";
				}
				echo "</SELECT> ";

				echo "<select NAME=\"VALUE_$fieldname[$x]_DATEDAY\" CLASS=smtext STYLE='WIDTH: 50px;'>\n";
				for ($z=1;$z<=31;$z++) {
					$display_day = date("d", mktime(0,0,0,1,$z,$this_year));
					$SEL = "";
					if ($F_DATA[2] == $display_day) { $SEL = "SELECTED"; }
					if ($F_DATA[2] == "" && $display_day == $this_day) { $SEL = "SELECTED"; }
					echo "<OPTION $SEL VALUE=\"$display_day\">$display_day</OPTION>\n";
				}
				echo "</SELECT> ";

				echo "<SELECT NAME=\"VALUE_$fieldname[$x]_DATEYEAR\" CLASS=smtext STYLE='WIDTH: 55px;'>\n";

				$end_year = $this_year + 10;
				for ($z=1960;$z<=$end_year;$z++) {
					$SEL = "";
					if ($F_DATA[0] == $z) { $SEL = "SELECTED"; }
					if ($F_DATA[0] == "" && $z == $this_year) { $SEL = "SELECTED"; }
					echo "<OPTION $SEL VALUE=\"$z\">$z</OPTION>\n";
				}
				echo "</SELECT>";

			} // End Date Select

			// ---------------------------------------------------------
			// Check for Time Field Now
			// ---------------------------------------------------------

			if ($fieldtype[$x] == "TIME") {

				if ($EDIT_FLAG == "on") {
					$F_DATA = split(":", $FIELD_DATA[$x]);
				}

				echo "<SELECT onchange=\"set_ampm(this.value);\"NAME=\"VALUE_$fieldname[$x]_TIMEHOUR\" CLASS=smtext STYLE='WIDTH: 50px;'>\n";

				for ($z=1;$z<=24;$z++) {

					$v = $z;
					$v2 = $z;

					if ($z > 12) { $v = $z-12; }

					if ($v < 10) { $v = "0".$v; }
					if ($v2 < 10) { $v2 = "0".$v2; }

					if ($F_DATA[0] == $v2) { $SEL = "SELECTED"; } else { $SEL = ""; }
					echo "<OPTION $SEL VALUE=\"$v2\">$v</OPTION>\n";
				}

				echo "</SELECT>&nbsp;";
				echo "<SELECT NAME=\"VALUE_$fieldname[$x]_TIMEMIN\" CLASS=smtext STYLE='WIDTH: 50px;'>\n";

				for ($z=0;$z<=59;$z++) {
					$v = $z;
					if ($z < 10) { $v = "0".$z; }
					if ($F_DATA[1] == $v) { $SEL = "SELECTED"; } else { $SEL = ""; }
					echo "<OPTION $SEL VALUE=\"$v\">$v</OPTION>\n";
				}

				echo "</SELECT>&nbsp;<SPAN ID=\"AMPM\">AM</SPAN>";

				if ($EDIT_FLAG == "on") {
					echo "\n\n<SCRIPT LANGUAGE=JAVASCRIPT>\n\n     set_ampm($F_DATA[0]);\n\n</SCRIPT>\n\n";
				}

			} // End Time Selections

			echo "</TD></TR>\n\n";

		} // End Field Loop ($x)

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		// Only Display Upload New Image Feature if "AUTO_IMAGE" is a Display Field.
		//
		// DEVNOTE:
		// If you want to disable this feature, simply comment out the following IF function
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		if (eregi("AUTO_IMAGE", $detail_display)) {

			if ($BGCOLOR == "WHITE") { $BGCOLOR="#EFEFEF"; } else { $BGCOLOR="WHITE"; }
			echo "<TR><TD ALIGN=LEFT VALIGN=TOP BGCOLOR=$BGCOLOR class=smtext COLSPAN=2>\n";
			echo "<FONT STYLE='font-size: 7pt;'><U><B>Image Upload Instructions</B></U>:<BR><FONT COLOR=DARKBLUE>If you wish to upload a new image for your record you may do so now. It must be a .GIF or .JPG image that is no \n";
			echo "larger than 60k in file size.  If you do not know what this means, please consult the webmaster for more details.</FONT>\n\n";
			echo "<BR><BR>\n";

			echo "<B><U>NEW IMAGE</U>: &nbsp;\n";
			echo "<input type=\"file\" name=\"FILE1\" STYLE='width: 275px; font-family: Arial; font-size: 7pt;'>\n";
			echo "</FONT></TD></TR>\n";

			$YES_IMAGE_UPLOAD = 1;

		}

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		// Show Submit and finsih form

		if ($BGCOLOR == "WHITE") { $BGCOLOR="#EFEFEF"; } else { $BGCOLOR="WHITE"; }

		if ($YES_IMAGE_UPLOAD == 1) {

			echo "\n\n<SCRIPT LANGUAGE=JAVASCRIPT>\n\n";
			echo "     function display_wait() {\n";
			echo "          alert('If you are uploading an image, this may take a few minutes.\\n\\nDO NOT PRESS THE SUBMIT BUTTON DURING THIS PROCESS.');\n";
			echo "          window.document.UPDATERECFORM.submit();\n\n";
			echo "          UPDATERECFORM.UPDBUT.disabled = 'true'; // Only works for IE but worth a shot for security -- must come after submit for Netscape!\n";
			echo "     }\n\n";
			echo "</SCRIPT>\n\n";

		} else {

			echo "\n\n<SCRIPT LANGUAGE=JAVASCRIPT>\n\n";
			echo "     function display_wait() {\n";
			echo "          window.document.UPDATERECFORM.submit();\n\n";
			echo "          UPDATERECFORM.UPDBUT.disabled = 'true'; // Only works for IE but worth a shot for security -- must come after submit for Netscape!\n";
			echo "     }\n\n";
			echo "</SCRIPT>\n\n";

		} // End Image Upload Check

		echo "<TR>\n";
		echo "<TD COLSPAN=2 ALIGN=CENTER VALIGN=TOP BGCOLOR=$BGCOLOR>\n";
		echo "<INPUT TYPE=button NAME=\"UPDBUT\" CLASS=FormLt1 VALUE=\" Update Data \" onclick=\"display_wait();\">\n\n";
		echo "</TD></TR></TABLE>\n<BR>\n";

		if ($EDIT_FLAG == "on") {

			$tmp = strlen($edit_tmp);
			$new = $tmp - 5;
			$edit_tmp = substr($edit_tmp, 0, $new);

			echo "\n\n<!-- Important Data -->\n\n";
			echo "<TEXTAREA NAME=EDIT_WHERE_STRING STYLE='WIDTH: 1px; height: 1px;'>$edit_tmp</TEXTAREA>\n\n\n\n";

		}

		echo "</FORM>\n";



} // End Edit Data Record

?>