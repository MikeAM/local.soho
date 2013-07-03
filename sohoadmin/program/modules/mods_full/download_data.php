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


require_once("../../includes/product_gui.php");

#######################################################
### DEAL WITH "EMPTY" TABLE REQUEST		 		    ###
#######################################################

# Clear recent page list
if ( $_GET['todo'] == "clear_recent" ) {
   $_SESSION['recent_tables'] = null;
}
unset($_SESSION['recent_tables']['']);


$THIS_DISPLAY .= "<html>\n";
$THIS_DISPLAY .= "<head>\n";

$globalprefObj = new userdata('global');
if ( $globalprefObj->get('utf8') == 'on' ) {
	$THIS_DISPLAY .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UT"."F-8\">\n";
} else {
	//$THIS_DISPLAY .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
	$THIS_DISPLAY .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UT"."F-8\">\n";
}

$THIS_DISPLAY .= "<META HTTP-EQUIV=\"Pragma\" CONTENT=\"no-cache\">\n";
$THIS_DISPLAY .= "<META HTTP-EQUIV=\"Expires\" CONTENT=\"-1\">\n";
$THIS_DISPLAY .= "</head>\n";

if ($action == "empty") {

	$THIS_DISPLAY .= "<form method=\"post\" ACTION=\"download_data.php\">\n";
	$THIS_DISPLAY .= "<input type=\"hidden\" name=\"action\" value=\"empty2\">\n";
	$THIS_DISPLAY .= "<input type=\"hidden\" name=\"TABLE_NAME\" value=\"$table\">\n";

	$THIS_DISPLAY .= "<table border=\"0\" cellpadding=10 cellspacing=\"0\" class=\"text\" width=\"100%\" height=100% bgcolor=RED style='BORDER: 1px inset black;'>\n";
	$THIS_DISPLAY .= "<tr>\n";
	$THIS_DISPLAY .= "<td align=\"center\" valign=\"middle\" class=\"text\">\n";

	$THIS_DISPLAY .= "<font COLOR=WHITE FACE=VERDANA SIZE=4><b>!! ".lang("WARNING")." !!</font><BR><BR><font COLOR=WHITE SIZE=2><b>\n";
	$THIS_DISPLAY .= "".lang("You have selected to clear the data from table")." \"$table\".\n";
	$THIS_DISPLAY .= "<BR>".lang("This process is irreversible and will delete all data contained in this table").".\n";
	$THIS_DISPLAY .= "<BR><BR>".lang("Are you sure you wish to continue")."?<BR><BR>\n";
	$THIS_DISPLAY .= "</td></tr></table>\n";
	$THIS_DISPLAY .= "<br><br><div align=\"center\">\n";
	$THIS_DISPLAY .= "<input TYPE=SUBMIT value=\" ".lang("Continue")." \" class=FormLt1 style='width: 100px;' >\n";
	$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
	$THIS_DISPLAY .= "<input type=\"button\" value=\"  ".lang("Cancel")."  \" class=FormLt1 style='width: 100px;' onclick=\"cancel();\">\n";
	$THIS_DISPLAY .= "</div><BR></form>\n";

} // End Empty STEP 1

if ($action == "empty2") {

	mysql_query("DELETE FROM $TABLE_NAME");
	$action = "";

}


/*---------------------------------------------------------------------------------------------------------*
 ___                         _           _
|_ _| _ __   _ __  ___  _ _ | |_   ___  / |
 | | | '  \ | '_ \/ _ \| '_||  _| |___| | |
|___||_|_|_|| .__/\___/|_|   \__|       |_|
            |_|
# IMPORT DATA TO TABLE ROUTINE (STEP 1: Choose csv file)
/*---------------------------------------------------------------------------------------------------------*/
if ($action == "import") {

   # Log in recent table list for quick links elsewhere
   $_SESSION['recent_tables'][strtolower($_REQUEST['table'])] = $_REQUEST['table']; // strtolower necc for ksort()

	// ------------------------------------------------------
	// Step 1: First, Read all CSV files from user directory
	// ------------------------------------------------------
	$CSV_OPTIONS = "      <option value=\"NONE\" style='color: #999999'>".lang("CSV Filenames").": </option>\n";

	$directory = "$doc_root/media";

	if (is_dir($directory)) {
		$handle = opendir("$directory");
		while ($files = readdir($handle)) {
			if (strlen($files) > 2 && eregi("\.csv", $files)) {
				if ($tmp == "#EFEFEF") { $tmp = "WHITE"; } else { $tmp = "#EFEFEF"; }
				$d = strtoupper($files);
				$CSV_OPTIONS .= "      <option value=\"$doc_root/media/$files\" style='background: $tmp'>$d </option>\n";
			}
		}
		closedir($handle);
	}

	// ------------------------------------------------------
	// Step 2: Let user choose the filename to import
	// ------------------------------------------------------

	$THIS_DISPLAY .= "<form method=\"post\" ACTION=\"download_data.php\">\n";
	$THIS_DISPLAY .= "<input type=\"hidden\" name=\"action\" value=\"import2\">\n";
	$THIS_DISPLAY .= "<input type=\"hidden\" name=\"TABLE_NAME\" value=\"$table\">\n";

	$THIS_DISPLAY .= "<table border=\"0\" cellpadding=5 cellspacing=\"0\" class=\"text\" width=100% bgcolor=#708090 style='BORDER: 1px inset black;'>\n";
	$THIS_DISPLAY .= "<tr>\n";
	$THIS_DISPLAY .= "<td align=\"left\" valign=\"middle\" class=\"text\">\n";
	$THIS_DISPLAY .= "<font style='font-family: Arial; font-size: 9pt; color: white;'><b>".lang("Select the CSV file that you wish to import").":</font>\n";
	$THIS_DISPLAY .= "</td></tr><tr><td align=\"center\" valign=\"top\" bgcolor=WHITE class=\"text\">\n";

		$THIS_DISPLAY .= "<BR>CSV Filename: \n";
		$THIS_DISPLAY .= "<select name=\"CSV_FILENAME\" style='FONT-FAMILY: Arial; FONT-SIZE: 8pt; WIDTH: 200px;'>$CSV_OPTIONS</select>\n";

		$THIS_DISPLAY .= "<BR><BR><font COLOR=#999999>".lang("Please note that you can only upload comma or semi-colon delimited CSV files").".<BR>".lang("If you need to upload your csv file").", <a href=\"../upload_files.php?=SID\">".lang("click here")."</a>.</font>";

		$THIS_DISPLAY .= "<BR><BR><BR><div align=\"right\"><input type=\"button\" value=\" ".lang("Cancel")." \" class=FormLt1 onclick=\"cancel();\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input TYPE=SUBMIT value=\" Next >> \" class=FormLt1></DIV>\n\n";

	$THIS_DISPLAY .= "</td></tr></table>\n";

	$THIS_DISPLAY .= "</form>";

}

/*---------------------------------------------------------------------------------------------------------*
 ___                         _           ___
|_ _| _ __   _ __  ___  _ _ | |_   ___  |_  )
 | | | '  \ | '_ \/ _ \| '_||  _| |___|  / /
|___||_|_|_|| .__/\___/|_|   \__|       /___|
            |_|
# IMPORT DATA TO TABLE ROUTINE (STEP 2: Match fields w/ column headings)
/*---------------------------------------------------------------------------------------------------------*/
if ($action == "import2") {

	if ($CSV_FILENAME == "NONE") {
		header ("Location: download_data.php?table=$TABLE_NAME&action=import&err=1&=SID");
		exit;
	}

	// ------------------------------------------------------
	// Step 1: Get Import Table Field Names to Match with CSV
	// ------------------------------------------------------

	$result = mysql_query("SELECT * FROM $TABLE_NAME");
	$NUM_TABLE_FIELDS = mysql_num_fields($result) - 1;

	$THIS_KEY_FIELD = "";

	for ($x=0;$x<=$NUM_TABLE_FIELDS;$x++) {
		$TABLE_FIELD[$x] = mysql_field_name($result, $x);
		$TABLE_FIELD_TYPE[$x] = mysql_field_type($result, $x);
	} // End For Loop

	// ------------------------------------------------------
	// Step 2: Grab the first line of the CSV file as the
	// field names for matching
	// ------------------------------------------------------

	$fp = fopen("$CSV_FILENAME", "r");
		$csv_binary = fread($fp,filesize($CSV_FILENAME));
	fclose($fp);

	$csv_line = split("\n", $csv_binary);

	// Auto Determine the field delimter

	if (eregi(";", $csv_line[0])) {
		$csv_field_data = split(";", $csv_line[0]);
		$delimeter = ";";
	} else {
		$csv_field_data = split(",", $csv_line[0]);
		$delimeter = ",";
	}

	$NUM_CSV_FIELDS = count($csv_field_data) - 1;
	$csv_column = array();
	$CSV_OPTIONS = " <option value=\"DEFAULT\" style=\"color: #999999;\">".lang("Use Default Value")." </option>\n";

	# Build drop down options for csv columns
	for ($x=0;$x<=$NUM_CSV_FIELDS;$x++) {
	   $d = trim($csv_field_data[$x]);

	   $csv_column[$d] = $x; // Store csv field names and matching dd value numbers in array for auto-matching

		// Modified for labels to be greater than 1 (2003-03-25)
		if (strlen($d) > 1) {
			$CSV_OPTIONS .= " <option value=\"".$x."\">".$d."</option>\n";
		}
	}

	// ------------------------------------------------------
	// Step 3: Setup Field Matching GUI for pre-import setup
	// ------------------------------------------------------

	$THIS_DISPLAY .= "<form method=\"post\" ACTION=\"download_data.php\">\n";
	$THIS_DISPLAY .= "<input type=\"hidden\" name=\"action\" value=\"import3\">\n";
	$THIS_DISPLAY .= "<input type=\"hidden\" name=\"TABLE_NAME\" value=\"$TABLE_NAME\">\n";
	$THIS_DISPLAY .= "<input type=\"hidden\" name=\"CSV_FILENAME\" value=\"$CSV_FILENAME\">\n";
	$THIS_DISPLAY .= "<input type=\"hidden\" name=\"delimeter\" value=\"$delimeter\">\n";

	$THIS_DISPLAY .= "<table border=\"0\" cellpadding=6 cellspacing=\"0\" width=95% class=\"text\" bgcolor=#708090 style='BORDER: 1px inset black;'>\n";
	$THIS_DISPLAY .= " <tr>\n";
	$THIS_DISPLAY .= "  <td align=\"left\" valign=\"middle\" class=\"text\">\n";
	$THIS_DISPLAY .= "   <font style='font-family: Arial; font-size: 9pt; color: white;'><b>".lang("Select which fields in the CSV file to place into the existing table fields").":</font>\n";
	$THIS_DISPLAY .= "  </td>\n";
	$THIS_DISPLAY .= " </tr>\n";
	$THIS_DISPLAY .= " <tr>\n";
	$THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\" bgcolor=WHITE class=\"text\"><BR>\n";
	$THIS_DISPLAY .= "   <input TYPE=CHECKBOX CHECKED name=FIELDNAMES value=ON> ".lang("First record of CSV data contains field names. Do not import.")."<BR><BR>\n";

   $THIS_DISPLAY .= "   <table border=\"1\" cellpadding=\"5\" cellspacing=\"0\" align=\"center\">\n";
   $THIS_DISPLAY .= "    <tr>\n";
   $THIS_DISPLAY .= "     <td align=\"center\" valign=\"middle\" class=\"text col_title\" width=\"100\"><b>".lang("Table Field Name")."</td>\n";
   $THIS_DISPLAY .= "     <td align=\"center\" valign=\"middle\" class=\"text col_title\"><b>".lang("CSV Field Name")."</td>\n";
   $THIS_DISPLAY .= "     <td align=\"center\" valign=\"middle\" class=\"text col_title\"><b>".lang("Default Import Value")."</td>\n";
   $THIS_DISPLAY .= "    </tr>\n";

   # Loop through table fields and spit out table row with dropdown and default value textfield
   for ($x=0;$x<=$NUM_TABLE_FIELDS;$x++) {

      if ($tmp == "WHITE") { $tmp = "#EFEFEF"; } else { $tmp = "WHITE"; }

      $db_field_name = $TABLE_FIELD[$x]; // Readability
      $this_type = strtoupper($TABLE_FIELD_TYPE[$x]);

      $THIS_DISPLAY .= "    <tr>\n";

      # DB Field
      $THIS_DISPLAY .= "     <td align=\"right\" valign=\"middle\" class=\"text\" bgcolor=\"".$tmp."\">\n";
      $THIS_DISPLAY .= "      ".$TABLE_FIELD[$x]." <font COLOR=#999999>[".$this_type."]</font>:\n";
      $THIS_DISPLAY .= "     </td>\n";

      # Matching CSV field
      $THIS_DISPLAY .= "     <td align=\"center\" valign=\"middle\" bgcolor=\"".$tmp."\">\n";
      $THIS_DISPLAY .= "      <select name=\"IMPORT".$x."\" id=\"".$TABLE_FIELD[$x]."_dd\" style='FONT-FAMILY: Arial; FONT-SIZE: 8pt; WIDTH: 200px;'>\n";
      $THIS_DISPLAY .= "       ".$CSV_OPTIONS."\n";
      $THIS_DISPLAY .= "      </select>\n";

      # auto-select csv field if name match found
      if ( isset($csv_column[$db_field_name]) ) {
         $THIS_DISPLAY .= "     <script type=\"text/javascript\">document.getElementById('".$TABLE_FIELD[$x]."_dd').value = '".$csv_column[$db_field_name]."';</script>\n";
      }

      $THIS_DISPLAY .= "     </td>\n";

      # Default import value
      $THIS_DISPLAY .= "     <td align=\"center\" valign=\"middle\" bgcolor=\"".$tmp."\">\n";
      $THIS_DISPLAY .= "      <input type=\"text\" name=\"DEFAULT".$x."\" value=\"\" style=\"FONT-FAMILY: Arial; FONT-SIZE: 8pt; WIDTH: 200px;\">\n";
      $THIS_DISPLAY .= "     </td>\n";
      $THIS_DISPLAY .= "    </tr>\n";

   }
   $THIS_DISPLAY .= "   </table>\n";

   $THIS_DISPLAY .= "   <div style=\"text-align: left;\">\n";
   $THIS_DISPLAY .= "    <BR><font COLOR=#999999>".lang("If a field name from your csv file is matched to the PriKey field of the table")."</font>\n";
	$THIS_DISPLAY .= "    <br/>\n";
	$THIS_DISPLAY .= "    <label for=\"leave_default_alone\"><input type=\"checkbox\" id=\"leave_default_alone\" name=\"leave_default_alone\" value=\"yes\">\n";
	$THIS_DISPLAY .= "   ".lang("Checking this option effectively changes the result of the \"Use Default Value\" option to:");
	$THIS_DISPLAY .= "    \"".lang("Leave existing field data alone instead of over-riding with the Default Import Value (which is usually 'nothing').")."\"</label>\n";
	$THIS_DISPLAY .= "   </div>\n";



   $THIS_DISPLAY .= "   <BR><BR><BR><div align=\"right\"><input type=\"button\" value=\" Cancel \" class=FormLt1 onclick=\"cancel();\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input TYPE=SUBMIT value=\" ".lang("Import Data Now")." \" class=FormLt1 onclick=\"build();\"></DIV>\n\n";

	$THIS_DISPLAY .= "  </td>\n";
	$THIS_DISPLAY .= " </tr>\n";
	$THIS_DISPLAY .= "</table>\n";

	$THIS_DISPLAY .= "</form>";

} // End Import Step 2


/*---------------------------------------------------------------------------------------------------------*
 ___                         _           ____
|_ _| _ __   _ __  ___  _ _ | |_   ___  |__ /
 | | | '  \ | '_ \/ _ \| '_||  _| |___|  |_ \
|___||_|_|_|| .__/\___/|_|   \__|       |___/
            |_|
# IMPORT DATA TO TABLE ROUTINE (STEP 3: Actually import data now)
/*---------------------------------------------------------------------------------------------------------*/
if ($action == "import3") {

//   echo testArray($_POST);

	$true_import_count = 0;

	// ------------------------------------------------------
	// Step 1: Get Import Table Field Names to Match with CSV
	// ------------------------------------------------------
	$result = mysql_query("SELECT * FROM $TABLE_NAME");
	$NUM_TABLE_FIELDS = mysql_num_fields($result) - 1;

	$THIS_KEY_FIELD = "";

	for ($x=0;$x<=$NUM_TABLE_FIELDS;$x++) {
		$TABLE_FIELD[$x] = mysql_field_name($result, $x);

		$meta = mysql_fetch_field($result, $x);
		if ($meta->primary_key == 1) { $THIS_KEY_FIELD = $TABLE_FIELD[$x]; }	// Located Key Field for this Table

	} // End For Loop

	$key_active = "no";							// Assume in the start that we are not modifing the key field

	// ------------------------------------------------------
	// Step 2: Grab the first line of the CSV file as the
	// field names for matching
	// ------------------------------------------------------
	$row = 0;
	$fp = fopen ($CSV_FILENAME, "r");

	while ($data = fgetcsv($fp, 3000, $delimeter)) {

		$num_fields = count ($data);
		$row++;

    	for ($c=0; $c<$num_fields; $c++) {
			$csv_field[$c] = $data[$c];
		}

		// ------------------------------------------------------
		// Reset SQL command data variable
		// ------------------------------------------------------
		$sql_build = "";
		$sql_update = "";

		// ------------------------------------------------------
		// Loop through CSV field data and format for SQL insert
		// ------------------------------------------------------
		for ($x=0;$x<=$NUM_TABLE_FIELDS;$x++) {

			$tmp = "IMPORT" . $x;
			$placement = ${$tmp};

			$tmp = "DEFAULT" . $x;
			$this_default = ${$tmp};

			if ($placement == "DEFAULT") {
				$this_data = $this_default;
			} else {
				$this_data = $csv_field[$placement];
			}
			
			// Is data a serialized array?
			// If so remove the last (")...
			// For some reason the last quote is not always removed by fgetcsv
			
			if(substr($this_data, -1) == "\"" && $this_data{0} == "\""){
			   $this_data = substr($this_data, 1, -1);
			}

			$this_data = stripslashes($this_data);
			$this_data = addslashes($this_data);

			if($TABLE_FIELD[$x] == 'full_desc' && $TABLE_NAME == 'cart_products'){
				$this_data = base64_encode($this_data);
			}
			// ------------------------------------------------------
			// Is this a key update or add
			// ------------------------------------------------------
			if ($TABLE_FIELD[$x] == $THIS_KEY_FIELD && $this_data != "NULL") {		// This is an edit record check
				$key_active = "yes";
				$check_key_value = $this_data;

//				# TESTING
//				$testouput .= "<br/><strong>(".$TABLE_FIELD[$x]." == ".$THIS_KEY_FIELD." && ".$this_data." != NULL) - key_active = yes!</strong>";

			} else {
			   # Do not update value of this field with blank value if option checked to leave fields alone instead of using default value
			   if ( $this_data != "" || $_POST['leave_default_alone'] != "yes"  ) {
			     $this_data = str_replace(":semi:", ";", $this_data);
				   $sql_update .= "$TABLE_FIELD[$x] = '$this_data', ";
				}

//				# TESTING
//				$testouput .= "<br/>[".$TABLE_FIELD[$x]."] = '".$this_data."' - key_active = no :-(";
			}

			$sql_build .= "'$this_data', ";

		} // end $x loop

		// Delete Last Comma From sql_build string
		// ----------------------------------------
		$len_tmp = strlen($sql_build);
		$v = $len_tmp - 2;
		$sql_build = substr($sql_build, 0, $v);

		$len_tmp = strlen($sql_update);
		$v = $len_tmp - 2;
		$sql_update = substr($sql_update, 0, $v);
		
		$sql_build = str_replace(":semi:", ";", $sql_build);

		// ------------------------------------------------------------------------------------------
		// Insert CSV data into table and assume first row contains field names in CSV
		// ------------------------------------------------------------------------------------------
		if ($row != 1 && $key_active == "no") {
			mysql_query("INSERT INTO $TABLE_NAME VALUES($sql_build)");
		 	$true_import_count++;
		}

		if ($row != 1 && $key_active == "yes") {
			$test = mysql_query("SELECT $THIS_KEY_FIELD FROM $TABLE_NAME WHERE $THIS_KEY_FIELD = '$check_key_value'");
			$exist_flag = mysql_num_rows($test);

			if ($exist_flag != 0) {
				mysql_query("UPDATE $TABLE_NAME SET $sql_update WHERE $THIS_KEY_FIELD = '$check_key_value'");
				$modification_count++;
			} else {
				mysql_query("INSERT INTO $TABLE_NAME VALUES($sql_build)");
		 		$true_import_count++;
			}

		} // End Key Active Check


	} // End While Loop

	fclose ($fp);

//	echo "<div style=\"font: 10px verdana;width: 700px;height: 400px;border: 1px solid red;overflow: scroll;\">".$testouput."</div>"; exit;

	// ------------------------------------------------------------
	// IMPORT COMPLETED!  DISPLAY THE NUMBER OF RECORDS IMPORTED
	// ------------------------------------------------------------

	$THIS_DISPLAY .= "<form method=\"post\" ACTION=\"download_data.php\">\n";
	$THIS_DISPLAY .= "<input type=\"hidden\" name=\"action\" value=\"\">\n";
	$THIS_DISPLAY .= "<table border=\"0\" cellpadding=6 cellspacing=\"0\" width=95% class=\"text\" bgcolor=#708090 style='BORDER: 1px inset black;'>\n";
	$THIS_DISPLAY .= "<tr>\n";
	$THIS_DISPLAY .= "<td align=\"left\" valign=\"middle\" class=\"text\">\n";
	$THIS_DISPLAY .= "<font style='font-family: Arial; font-size: 9pt; color: white;'><b>".lang("IMPORT OF CSV DATA TO")." \"$TABLE_NAME\" ".lang("COMPLETE!")."</font>\n";
	$THIS_DISPLAY .= "</td></tr><tr><td align=\"center\" valign=\"top\" bgcolor=WHITE class=\"text\"><BR>\n";

	if ($true_import_count != 0) {
		$THIS_DISPLAY .= "<font FACE=VERDANA SIZE=2><b>[ $true_import_count ] ".lang("Records imported successfully").".</font><BR>\n";
	}

	if ($modification_count != 0) {
		$THIS_DISPLAY .= "<font FACE=VERDANA SIZE=2><b>[ $modification_count ] ".lang("Records were modified").".</font><BR>\n";
	}

	$THIS_DISPLAY .= "<BR><BR><BR><input TYPE=SUBMIT value=\" ".lang("View all Tables")." \" class=FormLt1></DIV>\n\n";

	$THIS_DISPLAY .= "</td></tr></table></form>\n";

} // End Import Step 3

#######################################################
### READ ALL CURRENT DATABASE TABLES INTO MEMORY    ###
#######################################################

$result = soho_list_tables();
$i = 0;

# Store in separate arrays so they can be organized accordingly when displayed
$udt_tables = array();
$system_tables = array();

while ($i < mysql_num_rows ($result)) {
   $tb_names[$i] = mysql_tablename ($result, $i);
 //  $display = strtoupper($tb_names[$i]);
   $display = $tb_names[$i];
   $tb_names[$i] = "$display~~~$tb_names[$i]";

   if ( eregi("^UDT_", $tb_names[$i]) ) {
      $udt_tables[] = $tb_names[$i];
   } else {
      $system_tables[] = $tb_names[$i];
   }

   $i++;
}

usort($tb_names, "strnatcasecmp");
usort($udt_tables, "strnatcasecmp");
usort($system_tables, "strnatcasecmp");
//sort($tb_names);
//sort($udt_tables);
//sort($system_tables);

#######################################################
### START HTML/JAVASCRIPT CODE					    ###
#######################################################

$MOD_TITLE = "<a href=\"download_data.php\" class=\"white noline\">".lang("Manage/Backup Site Data Tables")."</a>";

if ( $action == "view" || $action == "concise" ) {
   $title_tag = $table;
} else {
   $title_tag = lang("View All Data Tables");
}

ob_start();
?>

<script language="JavaScript">
<!--
function SV2_findObj(n, d) { //v3.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=SV2_findObj(n,d.layers[i].document); return x;
}

function SV2_popupMsg(msg) { //v1.0
  alert(msg);
}
function SV2_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

var p = "File Manager";
//parent.frames.footer.setPage(p);

function build() {
	show_hide_layer('LOAD_LAYER','','show');
	show_hide_layer('userOpsLayer','','hide');
}

function load_complete() {
	show_hide_layer('LOAD_LAYER','','hide');
	show_hide_layer('userOpsLayer','','show');
}

function cancel() {
	show_hide_layer('LOAD_LAYER','','show');
	show_hide_layer('userOpsLayer','','hide');
	window.location = 'download_data.php?action=&<?=SID?>';
}

//-->
</script>

<style>
.tab-off, .tab-on {
   text-align: center;
   width: 125px;
   height: 18px;
   vertical-align: top;
   padding: 5px 10px;
   background-color: #efefef;
   border: 1px solid #ccc;
   border-top: 3px solid #ccc;
   border-bottom: 0;
   color: #595959;
   cursor: pointer;
}

.tab-on {
   color: #000;
   background-color: #efefef;
   border-top: 3px solid #EAA510;
   font-weight: bold;
}
</style>

<?

/*---------------------------------------------------------------------------------------------------------*
__      __ _                          _  _    _          _      _
\ \    / /(_)                        | || |  | |        | |    | |
 \ \  / /  _   ___ __      __   __ _ | || |  | |_  __ _ | |__  | |  ___  ___
  \ \/ /  | | / _ \\ \ /\ / /  / _` || || |  | __|/ _` || '_ \ | | / _ \/ __|
   \  /   | ||  __/ \ V  V /  | (_| || || |  | |_| (_| || |_) || ||  __/\__ \
    \/    |_| \___|  \_/\_/    \__,_||_||_|   \__|\__,_||_.__/ |_| \___||___/


# List all database tables with action links
/*---------------------------------------------------------------------------------------------------------*/
if ( $action == "" ) {

   /*------------------------------------*
    _____       _
   |_   _|__ _ | |__  ___
     | | / _` || '_ \(_-<
     |_| \__,_||_.__//__/
   /*------------------------------------*/
   $THIS_DISPLAY .= " <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"text\" width=\"100%\">";
   $THIS_DISPLAY .= "  <tr>\n";

   # Recent tables tab?
   if ( count($_SESSION['recent_tables']) > 0 ) {
      # Add js to account for this tab to other tabs' onclick values
      $tab_recent_js = "hideid('recent_table_list');setClass('tab-recent', 'tab-off');";

      # recent tab gets default status if it exists
      $udt_display = "none";
      $udt_onoff = "off";
      $spacer_td = "3%";

      # tab-recent
      $THIS_DISPLAY .= "   <td id=\"tab-recent\" class=\"tab-on\" onclick=\"showid('recent_table_list');setClass('tab-recent', 'tab-on');hideid('udt_table_list');hideid('system_table_list');setClass('tab-system', 'tab-off');setClass('tab-udt', 'tab-off');\">\n";
      $THIS_DISPLAY .= "    ".str_replace(" ", "&nbsp;", lang("Recent tables"))."\n";
      $THIS_DISPLAY .= "   </td>\n";

      $THIS_DISPLAY .= "   <td>&nbsp;</td>\n";
   } else {
      # tab-udt on by default if tab-recent not shown
      $udt_display = "block";
      $udt_onoff = "on";
      $spacer_td = "18%";
   }

   # tab-udt
   $THIS_DISPLAY .= "   <td id=\"tab-udt\" class=\"tab-".$udt_onoff."\" onclick=\"".$tab_recent_js."showid('udt_table_list');hideid('system_table_list');setClass('tab-system', 'tab-off');setClass('tab-udt', 'tab-on');\">\n";
   $THIS_DISPLAY .= "    ".str_replace(" ", "&nbsp;", lang("User tables"))."\n";
   $THIS_DISPLAY .= "   </td>\n";

   $THIS_DISPLAY .= "   <td>&nbsp;</td>\n";

   # tab-system
   $THIS_DISPLAY .= "   <td id=\"tab-system\" class=\"tab-off\" onclick=\"".$tab_recent_js."showid('system_table_list');hideid('udt_table_list');setClass('tab-udt', 'tab-off');setClass('tab-system', 'tab-on');\">\n";
   $THIS_DISPLAY .= "    ".str_replace(" ", "&nbsp;", lang("System tables"))."\n";
   $THIS_DISPLAY .= "   </td>\n";

   # Create search and delete options?
   if ( hasMod("dbtable") ) {
      # btn-create_search
      $THIS_DISPLAY .= "   <td style=\"padding-left: 10px;text-align: right;width: 100%;\">\n";
      $THIS_DISPLAY .= "    <a href=\"database_manager/create_table.php\" class=\"sav\">".lang("Create Table")."</a>\n";
			$THIS_DISPLAY .= "    | <a href=\"database_manager/create_and_import_db.php\" class=\"blue uline\">".lang("Create Table")." &amp; ".lang("Import CSV")."</a>\n";
			$THIS_DISPLAY .= "    | <a href=\"database_manager/delete_table.php\" class=\"del\">".lang("Delete Table")."</a><br/>\n";
      
      
      $THIS_DISPLAY .= "    <a href=\"database_manager/wizard_start.php\" class=\"sav\">".lang("Create Search Form")."</a>\n";
      $THIS_DISPLAY .= "    | <a href=\"database_manager/auth_users.php\" class=\"sav\">".lang("Batch Authenticate Users")."</a>\n";
      $THIS_DISPLAY .= "   </td>\n";
   } else {
      $THIS_DISPLAY .= "   <td width=\"100%\">&nbsp;</td>\n";
   }

   $THIS_DISPLAY .= "  </tr>\n";
   $THIS_DISPLAY .= " </table>\n";

   /*---------------------------------------------------------------------------------------------------------*
    ___                     _     _____       _     _
   | _ \ ___  __  ___  _ _ | |_  |_   _|__ _ | |__ | | ___  ___
   |   // -_)/ _|/ -_)| ' \|  _|   | | / _` || '_ \| |/ -_)(_-<
   |_|_\\___|\__|\___||_||_|\__|   |_| \__,_||_.__/|_|\___|/__/

   # Recently-accessed tables (if $_SESSION['recent_tables'] array is populated with something)
   /*---------------------------------------------------------------------------------------------------------*/
	if ( count($_SESSION['recent_tables']) > 0 ) {
	   ksort($_SESSION['recent_tables']);

	   # recent_table_list
   	$THIS_DISPLAY .= " <div id=\"recent_table_list\" style=\"display: block;\">\n";
      $THIS_DISPLAY .= "  <table border=\"0\" cellpadding=\"8\" cellspacing=\"0\" class=\"text\" width=\"100%\" style=\"border: 1px solid #ccc;\">";
      $THIS_DISPLAY .= "   <tr>\n";
      $THIS_DISPLAY .= "    <td colspan=\"5\" align=\"left\" valign=\"middle\" class=\"gray_33\">\n";
   	$THIS_DISPLAY .= "     <p>For your convenience, this tab lists tables that you've recently accessed and/or modified. Note that these tables are also listed\n";
   	$THIS_DISPLAY .= "     in their usual spot under the appropriate 'User tables' or 'System tables' tab.</p>\n";
   	$THIS_DISPLAY .= "     <p><a href=\"download_data.php?todo=clear_recent\">Clear recent table history</a> (harmless)</p>\n";
      $THIS_DISPLAY .= "    </td>\n";
      $THIS_DISPLAY .= "   </tr>\n";

      foreach ( $_SESSION['recent_tables'] as $key=>$tablename ) {
        # Count Records
        $record_count_qry = mysql_query("SELECT COUNT(*) FROM ".$tablename);
        $record_count = mysql_result($record_count_qry, 0, 0);
        $record_txt = "";
        if ($record_count < 1)
        {       
            $record_txt = "No records";
            unset($record_count);
        } elseif ($record_count == 1) { 
            $record_txt = "record";
        } else {
            $record_txt = "records";
        }
         if ( $bg == "bg_white" ) { $bg = "bg_gray_f8"; } else { $bg = "bg_white"; }

         $import_ok = " | <a href=\"download_data.php?action=import&table=".$tablename."&".SID."\" class=\"sav\">".lang("Import")."</a> ";
         $empty_ok = "[ <a href=\"download_data.php?action=empty&table=".$tablename."&".SID."\" class=\"del\">".lang("Empty")."</a>";

         # Added for Multi-User Access Check
		if($CUR_USER_ACCESS == "WEBMASTER" || preg_match("/;(MOD_ALLDBS|".$tablename.")/i", $CUR_USER_ACCESS)){
         //if ( $CUR_USER_ACCESS == "WEBMASTER" || eregi(";".$tablename.";", $CUR_USER_ACCESS) ) {
            $tdstyle =  "border-bottom: 1px dashed #ccc;";
            if ( hasMod("dbtable") ) {
               $viewonclick = "document.location.href='database_manager/enter_edit_data.php?mt=".$tablename."&".SID."';";
               $view_edit_link = lang("View")." / ".lang("Edit");
               $modify_link = " | <a href=\"database_manager/modify_table.php?mt=".$tablename."\">".lang("Modify")."</a> ";
            } else {
               $viewonclick = "document.location.href='download_data.php?action=view&amp;table=".$tablename."&".SID."';";
               $view_edit_link = lang("View");
               $modify_link = "";
            }
            $THIS_DISPLAY .= "    <tr class=\"".$bg."\" onmouseover=\"this.className='bg_yellow';\" onmouseout=\"this.className='".$bg."';\">\n";

            # Table name
            $THIS_DISPLAY .= "     <td style=\"cursor: default;".$tdstyle."\" align=\"left\" valign=\"middle\">\n";
            $THIS_DISPLAY .= "      <b>".str_replace("UDT_", "<span class=\"gray_33 unbold\">UDT_</span><b>", $tablename)."</b></td>\n";

            # View
            $THIS_DISPLAY .= "     <td class=\"hand\" onclick=\"build();".$viewonclick."\" style=\"".$tdstyle."\" align=\"center\" valign=\"middle\" onmouseover=\"this.style.backgroundColor='#FFF66F';\" onmouseout=\"this.style.backgroundColor='transparent';\">\n";
            $THIS_DISPLAY .= "      [ <span class=\"blue uline\">".$view_edit_link." ".lang("Records")."</span> ]</td>\n";

            # Download
            $THIS_DISPLAY .= "     <td style=\"".$tdstyle."\" align=\"center\" valign=\"middle\">\n";
            $THIS_DISPLAY .= "      [ <a href=\"dl_table_action.php?table=".$tablename."&".SID."\">".lang("Download")."</a>".$import_ok."]\n";
            $THIS_DISPLAY .= "     </td>\n";

            # Import
//            $THIS_DISPLAY .= "     <td style=\"".$tdstyle."\" align=\"center\" valign=\"middle\"></td>\n";

            # Empty
			$THIS_DISPLAY .= "     <td style=\"".$tdstyle."\" align=\"center\" valign=\"middle\">".$empty_ok."".$modify_link."]</td>\n";

			# Record Count
            $THIS_DISPLAY .= "      <td style=\"cursor: default;".$tdstyle."\" align=\"right\" valign=\"middle\">\n";
            $THIS_DISPLAY .= "      <span class=\"gray_33 unbold\">".$record_count."<i> ".$record_txt."</i></span></td>\n";
            $THIS_DISPLAY .= "    </tr>\n";

         } // End if webmaster or authorized admin

      } // End foreach loop through recent_tables
   	$THIS_DISPLAY .= "   </table>\n\n";
   	$THIS_DISPLAY .= "  </div>\n\n";
   } // End if recent_tables > 0


   /*---------------------------------------------------------------------------------------------------------*
    _   _  ___  _____   _          _     _
   | | | ||   \|_   _| | |_  __ _ | |__ | | ___  ___
   | |_| || |) | | |   |  _|/ _` || '_ \| |/ -_)(_-<
    \___/ |___/  |_|    \__|\__,_||_.__/|_|\___|/__/

   # User Data Tables
   /*---------------------------------------------------------------------------------------------------------*/
	# udt_table_list
	$THIS_DISPLAY .= " <div id=\"udt_table_list\" style=\"display: ".$udt_display.";\">\n";
   $THIS_DISPLAY .= "  <table border=\"0\" cellpadding=\"8\" cellspacing=\"0\" class=\"text\" width=\"100%\" style=\"border: 1px solid #ccc;\">";
   $THIS_DISPLAY .= "   <tr>\n";
   $THIS_DISPLAY .= "    <td colspan=\"5\" align=\"left\" valign=\"middle\" class=\"gray_33\">\n";
	$THIS_DISPLAY .= "     <p><b>U</b>ser <b>D</b>ata <b>T</b>ables are tables that you've created for your own uses either via the Database Table Manager feature\n";
	$THIS_DISPLAY .= "     or by dropping a web form on a page and telling it to store visitor-submited form data in a specified data table.</p>\n";
   $THIS_DISPLAY .= "    </td>\n";
   $THIS_DISPLAY .= "   </tr>\n";

   for ( $x = 0; $x < count($udt_tables); $x++ ) {

      if ( $bg == "bg_white" ) { $bg = "bg_gray_f8"; } else { $bg = "bg_white"; }
      $this_data = split("~~~", $udt_tables[$x]);
      $tablename = $this_data[1];

      // ------------------------------------------------------------------------
      // Do Not allow import or empty of specific "system" tables.
      // Overwriting data in these table could crash the entire system
      // ------------------------------------------------------------------------

      # v4.9 r54 --- allow everybody to import/empty on all tables
      $import_ok = " | <a href=\"download_data.php?action=import&table=".$tablename."&".SID."\" class=\"sav\">".lang("Import")."</a> ";
      $empty_ok = "[ <a href=\"download_data.php?action=empty&table=".$tablename."&".SID."\" class=\"del\">".lang("Empty")."</a>";

      // ------------------------------------------------------------------------
        # Count Records
        $record_count_qry = mysql_query("SELECT COUNT(*) FROM ".$tablename);
        $record_count = mysql_result($record_count_qry, 0, 0);
        $record_txt = "";
        if ($record_count < 1)
        {
            $record_txt = "No records";
            $record_count = "";
        } elseif ($record_count == 1) {
            $record_txt = "record";
        } else {
            $record_txt = "records";
		}

      if (!eregi("CTEMP_", $this_data[0])) {

         # Added for Multi-User Access Check
         if($CUR_USER_ACCESS == "WEBMASTER" || preg_match("/;(MOD_ALLDBS|".$this_data[0].")/i", $CUR_USER_ACCESS)){
         //if ( $CUR_USER_ACCESS == "WEBMASTER" || eregi(";$this_data[0];", $CUR_USER_ACCESS) ) {
            $tdstyle =  "border-bottom: 1px dashed #ccc;";
            if ( hasMod("dbtable") ) {
               $viewonclick = "document.location.href='database_manager/enter_edit_data.php?mt=".$tablename."&".SID."';";
               $view_edit_link = lang("View")." / ".lang("Edit");
               $modify_link = " | <a href=\"database_manager/modify_table.php?mt=".$tablename."\">".lang("Modify")."</a> ";
            } else {
               $viewonclick = "document.location.href='download_data.php?action=view&amp;table=".$tablename."&".SID."';";
               $view_edit_link = lang("View");
               $modify_link = "";
            }
            $THIS_DISPLAY .= "    <tr class=\"".$bg."\" onmouseover=\"this.className='bg_yellow';\" onmouseout=\"this.className='".$bg."';\">\n";

            # Table name
            $THIS_DISPLAY .= "     <td style=\"cursor: default;".$tdstyle."\" align=\"left\" valign=\"middle\"><span class=\"gray_33\">".str_replace("UDT_", "UDT_</span><b>", $this_data[0])."</b></td>\n";

            # View
            $THIS_DISPLAY .= "     <td class=\"hand\" onclick=\"build();".$viewonclick."\" style=\"".$tdstyle."\" align=\"center\" valign=\"middle\" onmouseover=\"this.style.backgroundColor='#FFF66F';\" onmouseout=\"this.style.backgroundColor='transparent';\">\n";
            $THIS_DISPLAY .= "      [ <span class=\"blue uline\">".$view_edit_link." ".lang("Records")."</span> ]</td>\n";

            # Download
            $THIS_DISPLAY .= "     <td style=\"".$tdstyle."\" align=\"center\" valign=\"middle\">\n";
            $THIS_DISPLAY .= "      [ <a href=\"dl_table_action.php?table=".$tablename."&".SID."\">".lang("Download")."</a>".$import_ok."]\n";
            $THIS_DISPLAY .= "     </td>\n";

//            # Import
//            $THIS_DISPLAY .= "     <td style=\"".$tdstyle."\" align=\"center\" valign=\"middle\">".$import_ok."</td>\n";

            # Empty
			$THIS_DISPLAY .= "     <td style=\"".$tdstyle."\" align=\"center\" valign=\"middle\">".$empty_ok."".$modify_link."]</td>\n";

			# Record Count
            $THIS_DISPLAY .= "      <td style=\"cursor: default;".$tdstyle."\" align=\"right\" valign=\"middle\">\n";
            $THIS_DISPLAY .= "      <span class=\"gray_33 unbold\">".$record_count."<i> ".$record_txt."</i></span></td>\n";
            $THIS_DISPLAY .= "    </tr>\n";

         } // End if webmaster or authorized admin

      } // End if !eregi(CTEMP_, this_data)

   } // End for loop through udt_tables

	$THIS_DISPLAY .= "   </table>\n\n";
	$THIS_DISPLAY .= "  </div>\n\n";


   /*---------------------------------------------------------------------------------------------------------*
    ___            _                 _____       _     _
   / __| _  _  ___| |_  ___  _ __   |_   _|__ _ | |__ | | ___  ___
   \__ \| || |(_-<|  _|/ -_)| '  \    | | / _` || '_ \| |/ -_)(_-<
   |___/ \_, |/__/ \__|\___||_|_|_|   |_| \__,_||_.__/|_|\___|/__/
         |__/
   # User Data Tables
   /*---------------------------------------------------------------------------------------------------------*/
	# system_table_list
	$THIS_DISPLAY .= " <div id=\"system_table_list\" style=\"display: none;\">\n";
   $THIS_DISPLAY .= "  <table border=\"0\" cellpadding=\"8\" cellspacing=\"0\" class=\"text\" width=\"100%\" style=\"border: 1px solid #ccc;\">";
   $THIS_DISPLAY .= "   <tr>\n";
   $THIS_DISPLAY .= "    <td colspan=\"5\" align=\"left\" valign=\"middle\" class=\"gray_33\">\n";
	//$THIS_DISPLAY .= "     <p>System data tables</b> - \n";
	$THIS_DISPLAY .= "     <p><b class=\"red\">WARNING: Modify system tables at your own risk.</b> Generally, you shouldn't have to mess with these unless you're developing a custom php script or troubleshooting\n";
	$THIS_DISPLAY .= "     a standard feature that doesn't seem to be working correctly. </p>\n";
   $THIS_DISPLAY .= "    </td>\n";
   $THIS_DISPLAY .= "   </tr>\n";

   for ( $x = 0; $x < count($system_tables); $x++ ) {

      if ( $bg == "bg_white" ) { $bg = "bg_gray_f8"; } else { $bg = "bg_white"; }
      $this_data = split("~~~", $system_tables[$x]);
      $tablename = $this_data[1];

      # v4.9 r54 --- allow everybody to import/empty on all tables
      $import_ok = " | <a href=\"download_data.php?action=import&table=".$tablename."&".SID."\" class=\"sav\">".lang("Import")."</a> ";
      $empty_ok = "[ <a href=\"download_data.php?action=empty&table=".$tablename."&".SID."\" class=\"del\">".lang("Empty")."</a>";

      if ( hasMod("dbtable") ) {
         $viewonclick = "document.location.href='database_manager/enter_edit_data.php?mt=".$tablename."&".SID."';";
         $view_edit_link = lang("View")." / ".lang("Edit");
         $modify_link = " | <a href=\"database_manager/modify_table.php?mt=".$tablename."\">".lang("Modify")."</a> ";
      } else {
         $viewonclick = "document.location.href='download_data.php?action=view&amp;table=".$tablename."&".SID."';";
         $view_edit_link = lang("View");
         $modify_link = "";
      }

	    # Count Records
        $record_count_qry = mysql_query("SELECT COUNT(*) FROM ".$tablename);
        $record_count = mysql_result($record_count_qry, 0, 0);
        $record_txt = "";
        if ($record_count < 1)
        {
            $record_txt = "No records";
            $record_count = "";
        } elseif ($record_count == 1) {
            $record_txt = "record";
        } else {
            $record_txt = "records";
        }

      if (!eregi("CTEMP_", $this_data[0])) {

         # Added for Multi-User Access Check
         if($CUR_USER_ACCESS == "WEBMASTER" || preg_match("/;(MOD_ALLDBS|".$this_data[0].")/i", $CUR_USER_ACCESS)){
         //if ( eregi("WEBMASTER", $CUR_USER_ACCESS) || eregi(";".$this_data[0].";", $CUR_USER_ACCESS)) {
            $tdstyle =  "border-bottom: 1px dashed #ccc;";
            $THIS_DISPLAY .= "    <tr class=\"".$bg."\" onmouseover=\"this.className='bg_yellow';\" onmouseout=\"this.className='".$bg."';\">\n";
            $THIS_DISPLAY .= "     <td style=\"cursor: default;".$tdstyle."\" align=\"left\" valign=\"middle\"><b>".$this_data[1]."</b></td>\n";

            # View
            $THIS_DISPLAY .= "     <td class=\"hand\" onclick=\"build();".$viewonclick."\" style=\"".$tdstyle."\" align=\"center\" valign=\"middle\" onmouseover=\"this.style.backgroundColor='#FFF66F';\" onmouseout=\"this.style.backgroundColor='transparent';\">\n";
            $THIS_DISPLAY .= "      [ <span class=\"blue uline\">".$view_edit_link." ".lang("Records")."</span> ]</td>\n";

            # Download
            $THIS_DISPLAY .= "     <td style=\"".$tdstyle."\" align=\"center\" valign=\"middle\">\n";
            $THIS_DISPLAY .= "      [ <a href=\"dl_table_action.php?table=".$tablename."&".SID."\">".lang("Download")."</a>".$import_ok."]\n";
            $THIS_DISPLAY .= "     </td>\n";

//            # Import
//            $THIS_DISPLAY .= "     <td style=\"".$tdstyle."\" align=\"center\" valign=\"middle\">".$import_ok."</td>\n";

            # Empty
			$THIS_DISPLAY .= "     <td style=\"".$tdstyle."\" align=\"center\" valign=\"middle\">".$empty_ok."".$modify_link."]</td>\n";

            # Record Count
            $THIS_DISPLAY .= "      <td style=\"cursor: default;".$tdstyle."\" align=\"right\" valign=\"middle\">\n";
			$THIS_DISPLAY .= "      <span class=\"gray_33 unbold\">".$record_count."<i> ".$record_txt."</i></span></td>\n";

            $THIS_DISPLAY .= "    </tr>\n";

         } // End if webmaster or authorized admin

      } // End if !eregi(CTEMP_, this_data)

   } // End for loop through system_tables
	$THIS_DISPLAY .= "   </table>\n\n";
	$THIS_DISPLAY .= "</div>\n\n";


} // End if $action == ""


// Old/Orignial display style --- list all tables togoether alphabetically (system and UDT_ alike)
//if ($action == "") {
//
//	$num_tables = $i - 1;
//	$half = $num_tables / 2;
//
//	$THIS_DISPLAY .= "<table border=\"0\" cellpadding=2 cellspacing=\"0\" width=98% align=\"center\"><tr>\n";
//	$THIS_DISPLAY .= "<td align=\"left\" valign=\"top\" class=\"text\">\n";
//
//		$THIS_DISPLAY .= "\n\n<table border=1 cellpadding=8 cellspacing=\"0\" class=\"text\" width=95% >\n\n";
//
//		for ($x=0;$x<=$num_tables;$x++) {
//
//			if ($ALT == "#EFEFEF") { $ALT = "white"; } else { $ALT = "#EFEFEF"; }
//			$this_data = split("~~~", $tb_names[$x]);
//
//			// ------------------------------------------------------------------------
//			// Do Not allow import or empty of specific "system" tables.
//			// Overwriting data in these table could crash the entire system
//			// ------------------------------------------------------------------------
//
//			if ( !advanced_mode() ) {
//			   $NO_IMPORT_TABLES = "campaign_manager;cart_category;cart_invoice;cart_options;cart_shipping_opts;cart_tax;login;sec_codes;site_pages;";
//			} else {
//			   $NO_IMPORT_TABLES = ""; // v4.7 RC5 - Let developers do whatever
//			}
//
//
//			if (!eregi("$this_data[0]", $NO_IMPORT_TABLES)) {
//				$import_ok = "[ <a href=\"download_data.php?action=import&table=$this_data[1]&".SID."\" class=\"sav\">".lang("Import")."</a> ]";
//				$empty_ok = "[ <a href=\"download_data.php?action=empty&table=$this_data[1]&".SID."\" class=\"del\">".lang("Empty")."</a> ]";
//			} else {
//				$import_ok = "<font COLOR=#999999>N/A</font>\n";
//				$empty_ok = "<font COLOR=#999999>N/A</font>\n";
//			}
//
//			// ------------------------------------------------------------------------
//
//			if (!eregi("CTEMP_", $this_data[0])) {
//
//				// Added for Multi-User Access Check
//				// ----------------------------------------------
//
//				if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";$this_data[0];", $CUR_USER_ACCESS)) {
//
//					$THIS_DISPLAY .= "<tr>\n";
//					$THIS_DISPLAY .= "<td bgcolor=$ALT align=\"left\" valign=\"middle\"><b>$this_data[0]</b></td>\n";
//					$THIS_DISPLAY .= "<td bgcolor=$ALT align=\"center\" valign=\"middle\">[ <a href=\"download_data.php?action=view&table=$this_data[1]&".SID."\" onclick=\"build();\">".lang("View")."</a> ]</td>\n";
//					$THIS_DISPLAY .= "<td bgcolor=$ALT align=\"center\" valign=\"middle\">[ <a href=\"dl_table_action.php?table=$this_data[1]&".SID."\">".lang("Download")."</a> ]</td>\n";
//					$THIS_DISPLAY .= "<td bgcolor=$ALT align=\"center\" valign=\"middle\">$import_ok</td>\n";
//					$THIS_DISPLAY .= "<td bgcolor=$ALT align=\"center\" valign=\"middle\">$empty_ok</td>\n";
//					$THIS_DISPLAY .= "</tr>\n";
//
//				}
//
//			}
//
//		}
//
//	$THIS_DISPLAY .= "\n</table>\n\n";
//
//	$THIS_DISPLAY .= "</td></tr></table>\n\n";
//
//}


/*---------------------------------------------------------------------------------------------------------*
__   __ _                _____       _     _
\ \ / /(_) ___ __ __ __ |_   _|__ _ | |__ | | ___
 \ V / | |/ -_)\ V  V /   | | / _` || '_ \| |/ -_)
  \_/  |_|\___| \_/\_/    |_| \__,_||_.__/|_|\___|

# If user has selected to view this table; display data dump now
/*---------------------------------------------------------------------------------------------------------*/
if ( $action == "view" || $action == "concise" ) {

   # Log in recent table list for quick links elsewhere
   $_SESSION['recent_tables'][strtolower($_GET['table'])] = $_GET['table']; // strtolower necc for ksort()

	# Increase num shown for concise view (Mantis #251)
	if ( $action == "concise" ) {
	   $num_to_show = 50;
	} else {
	   $num_to_show = 10;
	}

	if ($s == "") { $s = 0; }

	$THIS_DISPLAY .= "<div align=\"left\"><b><font FACE=VERDANA SIZE=2>".lang("Database table")." '".$table."':</font>\n";

	$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ <a href=\"download_data.php?=SID\">".lang("View All Data Tables")."</a> ]\n\n";

	if ( $action == "view" ) {
	   $THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ <a href=\"download_data.php?table=$table&action=concise&=SID\">".lang("Concise View")."</a> ]<BR><BR>\n\n";
	} else {
	   $THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ <a href=\"download_data.php?table=$table&action=view&=SID\">".lang("Default View")."</a> ]<BR><BR>\n\n";
	}

	$result = mysql_query("SELECT * FROM $table LIMIT $s,$num_to_show");
	$numberRows = mysql_num_rows($result);
	$numberFields = mysql_num_fields($result);
	$numberFields--;

	$THIS_DISPLAY .= "</DIV><table border=1 cellpadding=4 cellspacing=\"0\" class=\"text\" align=\"left\">\n<tr>\n\n";

	for ($x=0;$x<=$numberFields;$x++) {
		$fieldname[$x] = mysql_field_name($result, $x);

		// I need to know the case_sensative normanclature of the field names for dev purposes!
		// $fieldname[$x] = strtoupper($fieldname[$x]);
		$THIS_DISPLAY .= "<td class=\"col_title\" align=\"center\" valign=\"top\">$fieldname[$x]</td>\n";
	}

	$THIS_DISPLAY .= "\n</tr>\n\n<tr>\n\n";

	for ($x=0;$x<=$numberFields;$x++) {
		$fieldtype[$x] = mysql_field_type($result, $x);
		// $fieldtype[$x] = strtoupper($fieldtype[$x]);
		$THIS_DISPLAY .= "<td class=\"col_sub\" align=\"center\" valign=\"top\"><font COLOR=#999999>[$fieldtype[$x]]</font></td>\n";

	}

	$THIS_DISPLAY .= "\n</tr>\n";

	while ($row = mysql_fetch_array ($result)) {

		$THIS_DISPLAY .= "\n<tr>\n";
		if ($ALT == "#EFEFEF") { $ALT = "white"; } else { $ALT = "#EFEFEF"; }

		for ($x=0;$x<=$numberFields;$x++) {

			$tmp = $row[$x];
			if ($tmp == "" || $tmp == "NULL") { $tmp = "&nbsp;"; }

			# Hide blog fields if concise view (Mantis #251)
			if ( $action == "concise" ) {
			   if (strtoupper($fieldtype[$x]) == "BLOB") { $tmp = "[BLOB]"; }
			}

			if ($tmp != "&nbsp;" && !eregi("&lt;", $tmp) && !eregi("&gt;", $tmp) && !eregi("&amp;", $tmp)) { $tmp = htmlspecialchars($tmp); }	// Bugzilla #12 -- Added special chars reverse check for Bugzilla #31

			$THIS_DISPLAY .= "<td align=\"left\" valign=\"top\" bgcolor=$ALT>$tmp</td>\n";

		}

		$THIS_DISPLAY .= "\n</tr>\n";

	}

	$THIS_DISPLAY .= "\n</table>\n\n";


	$THIS_DISPLAY .= "<div align=\"left\"><BR CLEAR=ALL><BR><b><font FACE=VERDANA SIZE=2>\n\n";

	# Previous 10
	if ($s != 0) {
		$newstart = $s-$num_to_show;
		$THIS_DISPLAY .= "[ <a href=\"download_data.php?action=$action&table=$table&s=$newstart\" onclick=\"build();\">Previous $num_to_show</a> ]";
	}

	# Next 10
	if ($numberRows == $num_to_show) {
		$newstart = $s+$num_to_show;
		$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ <a href=\"download_data.php?action=$action&s=$newstart&table=$table\" onclick=\"build();\">Next $num_to_show</a> ]\n";
	}

	$THIS_DISPLAY .= "</b></DIV>\n\n";

}

echo $THIS_DISPLAY;

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$module = new smt_module($module_html);
$module->add_breadcrumb_link("Database Table Manager", "program/modules/mods_full/download_data.php");
$module->icon_img = "program/includes/images/data-tables-icon-med.png";
$module->heading_text = "Database Table Manager";

$intro_text = "Manage your MySQL database tables and create db table search forms.";
$module->description_text = $intro_text;

$module->good_to_go();
?>