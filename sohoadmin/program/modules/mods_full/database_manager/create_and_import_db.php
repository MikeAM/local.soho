<?php
error_reporting(E_PARSE & E_ERROR);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.9
##
## Author: 			Cameron Allen [cameron.allen@soholaunch.com]
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
## 
###############################################################################

session_cache_limiter('none');
session_start();
require_once('../../../includes/product_gui.php');
error_reporting(E_PARSE & E_ERROR);
$MOD_TITLE = lang("Table Manager: Import and Create Database Table");


$THIS_DISPLAY .= "<html>\n";
$THIS_DISPLAY .= "<head>\n";

$globalprefObj = new userdata('global');
if ( $globalprefObj->get('utf8') == 'on' ) {
	$THIS_DISPLAY .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
} else {
	$THIS_DISPLAY .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
}

$THIS_DISPLAY .= "<META HTTP-EQUIV=\"Pragma\" CONTENT=\"no-cache\">\n";
$THIS_DISPLAY .= "<META HTTP-EQUIV=\"Expires\" CONTENT=\"-1\">\n";
$THIS_DISPLAY .= "</head>\n";


function sterilize_char($sterile_var) {
	$sterile_var = stripslashes($sterile_var);
	$sterile_var = eregi_replace(" ", "_", $sterile_var);
	$st_l = strlen($sterile_var);
	$st_a = 0;
	$tmp = "";
	while($st_a != $st_l) {
		$temp = substr($sterile_var, $st_a, 1);
		if (eregi("[0-9a-z_]", $temp)) { $tmp .= $temp; }
		$st_a++;
	}
	$sterile_var = $tmp;
	return $sterile_var;
}

function sterilize ($sterile_var) {
	$sterile_var = stripslashes($sterile_var);
	$st_l = strlen($sterile_var);
	$st_a = 0;
	$tmp = "";
	while($st_a != $st_l) {
		$temp = substr($sterile_var, $st_a, 1);
		if (eregi("[.0-9a-z_-]", $temp)) { $tmp .= $temp; }
		$st_a++;
	}
	$sterile_var = $tmp;
	return $sterile_var;
}


#######################################################
### START HTML/JAVASCRIPT CODE			   			###
#######################################################
$javascript = "<script language=\"JavaScript\">\n";
$javascript .= "<!--\n";

$javascript .= "function cancel() {\n";
$javascript .= "	show_hide_layer('LOAD_LAYER','','show');\n";
$javascript .= "	show_hide_layer('userOpsLayer','','hide');\n";
$javascript .= "	window.location = '../download_data.php';\n";
$javascript .= "}\n";

$javascript .= "//-->\n";
$javascript .= "</script>\n";


/*---------------------------------------------------------------------------------------------------------*

# SELECT DATA TO TABLE IMPORT (STEP 1: Choose csv file)
/*---------------------------------------------------------------------------------------------------------*/
if ($_GET['action'] == "start" || $_POST['action'] == '') {

	$THIS_DISPLAY .= $javascript;
	$THIS_DISPLAY .= "<table border=\"0\" cellpadding=5 cellspacing=\"0\" class=\"text\" width=100% bgcolor=#708090 style='BORDER: 1px inset black;'>\n";
	$THIS_DISPLAY .= "<tr>\n";
	$THIS_DISPLAY .= "<td align=\"left\" valign=\"middle\" class=\"text\">\n";
	$THIS_DISPLAY .= "<font style='font-family: Arial; font-size: 9pt; color: white;'><b>".lang("Step 1: Choose database name").".</font>\n";
	$THIS_DISPLAY .= "</td></tr><tr><td align=\"center\" valign=\"top\" bgcolor=WHITE class=\"text\">\n";



	$THIS_DISPLAY .= "<form name=\"name_db\" method=\"post\" action=\"create_and_import_db.php\" style=\"display: inline;\">\n";		
	$THIS_DISPLAY .= "<input type=\"hidden\" name=\"action\" value=\"2\">\n";	
	$THIS_DISPLAY .= "<br/>".lang("New Database Name").":&nbsp;";
	$THIS_DISPLAY .= "<input type=\"text\" name=\"new_table_name\" style=\"position: relative; display:inline; WIDTH: 295px;\" value=\"".$_GET['new_table_name']."\"/>\n";
	//$THIS_DISPLAY .= "<button class=\"nav_main\" onMouseover=\"this.className='nav_mainon';\" onMouseout=\"this.className='nav_main';\">Select File</button>\n";

	$THIS_DISPLAY .= "</td></tr><tr><td align=\"center\" valign=\"top\" bgcolor=WHITE class=\"text\">\n";
	
	if($_GET['error']=='name'){
		$THIS_DISPLAY .= "<br/><font color=red>".lang("Error: Illegal Database Name!")." ".lang("Database names must be between 3 and 50 characters")."</font>\n";
	}

	$THIS_DISPLAY .= "<BR><BR><BR><div style=\"width:400px;\" align=\"right\"><input type=\"button\" value=\" ".lang("Cancel")." \" class=FormLt1 onclick=\"cancel();\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input TYPE=SUBMIT value=\" Next >> \" class=FormLt1></DIV>\n\n";
	$THIS_DISPLAY .= "</form>";
	$THIS_DISPLAY .= "<br/><br/><br/>\n";
	$THIS_DISPLAY .= "</td></tr></table>\n";



}

if ($_POST['action'] == "2") {
	if(strlen($_POST['new_table_name']) <= 2 || strlen($_POST['new_table_name']) > 49){
		header("Location: create_and_import_db.php?action=start&error=name&new_table_name=".$_POST['new_table_name']);
		exit;
	}
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
	$THIS_DISPLAY .= $javascript;

	$THIS_DISPLAY .= "<table border=\"0\" cellpadding=5 cellspacing=\"0\" class=\"text\" width=100% bgcolor=#708090 style='BORDER: 1px inset black;'>\n";
	$THIS_DISPLAY .= "<tr>\n";
	$THIS_DISPLAY .= "<td align=\"left\" valign=\"middle\" class=\"text\">\n";
	$THIS_DISPLAY .= "<font style='font-family: Arial; font-size: 9pt; color: white;'><b>".lang("Step 2: Upload or Select the .CSV file that you want to import into the")." ".$_POST['new_table_name']." ".lang("Database Table").".</font>\n";
	$THIS_DISPLAY .= "</td></tr><tr><td align=\"center\" valign=\"top\" bgcolor=WHITE class=\"text\">\n";



	$THIS_DISPLAY .= "<form id=\"upload_csv\" name=\"upload_csv\" enctype=\"multipart/form-data\" method=\"post\" action=\"create_and_import_db.php\" style=\"display: inline;\">\n";
	$THIS_DISPLAY .= "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"2000000\">\n";
	$THIS_DISPLAY .= "<input type=\"hidden\" name=\"new_table_name\" value=\"".$_POST['new_table_name']."\">\n";	
	$THIS_DISPLAY .= "<input type=\"hidden\" name=\"action\" value=\"upload\">\n";	
	$THIS_DISPLAY .= lang("Upload a .CSV File").":&nbsp;";
	$THIS_DISPLAY .= "<input type=\"file\" name=\"csv_upload\"  style=\"position: relative; display:inline;\" size=32 OnChange=\"document.upload_csv.submit();\"/>\n";
	//$THIS_DISPLAY .= "<button class=\"nav_main\" onMouseover=\"this.className='nav_mainon';\" onMouseout=\"this.className='nav_main';\">Select File</button>\n";
	$THIS_DISPLAY .= "</form>\n";
	
	
	
	
	
	$THIS_DISPLAY .= "</td></tr>\n";

	$THIS_DISPLAY .= "<tr><td align=\"center\" valign=\"top\" bgcolor=WHITE class=\"text\">\n";
	$THIS_DISPLAY .= lang("Or")."\n";
	$THIS_DISPLAY .= "</td></tr>\n";
	
	$THIS_DISPLAY .= "<tr><td align=\"center\" valign=\"top\" bgcolor=WHITE class=\"text\">\n";
	$THIS_DISPLAY .= "<form method=\"post\" action=\"create_and_import_db.php\">\n";
	$THIS_DISPLAY .= "<input type=\"hidden\" name=\"new_table_name\" value=\"".$_POST['new_table_name']."\">\n";	
	$THIS_DISPLAY .= "<input type=\"hidden\" name=\"action\" value=\"3\">\n";
	//$THIS_DISPLAY .= "<BR>".lang("CSV Filename").": \n";
	$THIS_DISPLAY .= "<BR>".lang("Select a .CSV File").":&nbsp;\n";
	$THIS_DISPLAY .= "<select name=\"CSV_FILENAME\" style='FONT-FAMILY: Arial; FONT-SIZE: 8pt; WIDTH: 295px;'>$CSV_OPTIONS</select>\n";
	$THIS_DISPLAY .= "<BR><BR><BR><div style=\"width:400px;\" align=\"right\"><input type=\"button\" value=\" ".lang("Cancel")." \" class=FormLt1 onclick=\"cancel();\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input TYPE=SUBMIT value=\" Next >> \" class=FormLt1></DIV>\n\n";

	$THIS_DISPLAY .= "</td></tr><tr><td align=\"center\" valign=\"top\" bgcolor=WHITE class=\"text\">\n";

	$THIS_DISPLAY .= "<BR><BR><font COLOR=#999999>".lang("Please note that you can only use comma or semi-colon delimited .CSV files").".</font>";


	$THIS_DISPLAY .= "</td></tr></table>\n";

	$THIS_DISPLAY .= "</form>";

}


if($_POST['action'] == 'upload'){

	$tempfile = $_FILES['csv_upload']['tmp_name'];
	
	$filename = $_FILES['csv_upload']['name'];
	$filename = eregi_replace(" ", "_", $filename);
	$filename = sterilize($filename);
	$newfile = $_SESSION['doc_root'].'/media/'.$filename;
	
	$table_name = $_POST['new_table_name'];
	$table_name = 'UDT_'.sterilize_char($table_name);

	if(filesize($tempfile) < 1){
		$error[] = lang("CSV File is empty.");
	} else {
		
		
		if(!file_exists($newfile)){
			@copy($tempfile, $newfile);							
		}
		
		
	//////////
		$fp = fopen("$tempfile", "r");
		$csv_binary = fread($fp,filesize($tempfile));
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
	
		$row = 0;
		$fp = fopen ($tempfile, "r");
	
		while ($data = fgetcsv($fp, 3000, $delimeter)) {
	
			$num_fields = count ($data);
			$row++;
			
			for ($c=0; $c<$num_fields; $c++) {
				$this_data = $data[$c];
				
				if(substr($this_data, -1) == "\"" && $this_data{0} == "\""){
					$this_data = substr($this_data, 1, -1);
				}
				
				$this_data = stripslashes($this_data);
				$this_data = addslashes($this_data);			
				$this_data = str_replace(":semi:", ";", $this_data);
				if($row == 1){
					$csv_fieldname[$c] = $data[$c];
				} else {
					$thisfield = $csv_fieldname[$c];
					$csv_field[$thisfield] = $data[$c];				
				}
			}
			
			//	$THIS_DISPLAY .= testArray($data);
			if($row == 1){
				$database_fields=$csv_fieldname;
			}	else {
				$database_values[]=$csv_field;
			}
			
		} // End While Loop
	
		fclose ($fp);
		
		$sql_qrytables = "(";
		$sql_qry = '';
		$xl=0;
		foreach($database_fields as $val){
			if($val!=''){
				++$xl;
				if(eregi('prikey', $val)){
				  $sql_qry .= $val." int(50) not null auto_increment primary key, ";
				} elseif($xl == 1) {
				  $sql_qry .= "PRIKEY int(50) not null auto_increment primary key, ";
				  $sql_qry .= $val." BLOB, ";
				} else {
				  $sql_qry .= $val." BLOB, ";
				}
				$sql_qrytables .= $val.", ";
			}
		}
		
		$sql_qrytables = eregi_replace(', $', '', $sql_qrytables);
		$sql_qrytables .= ")";
			
		$sql_qry = eregi_replace(', $', '', $sql_qry);
		$sql_qry = "create table ".$table_name." (".$sql_qry.")";
		
		if(!mysql_query($sql_qry)){
			$error[] = lang("Can't Create MYSQL Database"." :");
			$error[] = mysql_error();
		} else {
	
			foreach($database_values as $var=>$val){
				$sql_qryvals = "values(";
		
				foreach($val as $dbval){
					$sql_qryvals .= "'".$dbval."', ";
				}
				
				$sql_qryvals = eregi_replace(', $', '', $sql_qryvals);
				$sql_qryvals .= ")";
				$compdb_qry = 'insert into '.$table_name.' '.$sql_qrytables.' '.$sql_qryvals;
				mysql_query($compdb_qry);

			}			
		}

	}
	@unlink($tempfile);
}


if($_POST['action'] == '3'){
	$tempfile = $_POST['CSV_FILENAME'];
	$table_name = $_POST['new_table_name'];
	$table_name = 'UDT_'.sterilize_char($table_name);

	if(filesize($tempfile) < 1){
		$error[] = lang("CSV File is empty.");
	} else {
				
	
	
		//////////
		$fp = fopen("$tempfile", "r");
		$csv_binary = fread($fp,filesize($tempfile));
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
	
		$row = 0;
		$fp = fopen ($tempfile, "r");
	
		while ($data = fgetcsv($fp, 3000, $delimeter)) {
	
			$num_fields = count ($data);
			$row++;
			
			for ($c=0; $c<$num_fields; $c++) {
				$this_data = $data[$c];
				
				if(substr($this_data, -1) == "\"" && $this_data{0} == "\""){
					$this_data = substr($this_data, 1, -1);
				}
				
				$this_data = stripslashes($this_data);
				$this_data = addslashes($this_data);			
				$this_data = str_replace(":semi:", ";", $this_data);
				if($row == 1){
					$csv_fieldname[$c] = $data[$c];
				} else {
					$thisfield = $csv_fieldname[$c];
					$csv_field[$thisfield] = $data[$c];				
				}
			}
			
			//	$THIS_DISPLAY .= testArray($data);
			if($row == 1){
				$database_fields=$csv_fieldname;
			}	else {
				$database_values[]=$csv_field;
			}
			
		} // End While Loop
	
		fclose ($fp);
		
		$sql_qrytables = "(";
		$sql_qry = '';
		$xl=0;
		foreach($database_fields as $val){
			if($val!=''){
				++$xl;
				if(eregi('prikey', $val)){
					$sql_qry .= $val." int(50) not null auto_increment primary key, ";
				} elseif($xl == 1) {
					$sql_qry .= "PRIKEY int(50) not null auto_increment primary key, ";
					$sql_qry .= $val." BLOB, ";
				} else {
					$sql_qry .= $val." BLOB, ";
				}
				$sql_qrytables .= $val.", ";
			}
		}

		$sql_qrytables = eregi_replace(', $', '', $sql_qrytables);
		$sql_qrytables .= ")";
			
		$sql_qry = eregi_replace(', $', '', $sql_qry);
		$sql_qry = "create table ".$table_name." (".$sql_qry.")";
		
		if(!mysql_query($sql_qry)){
			$error[] = lang("Can't Create MYSQL Database"." :");
			$error[] = mysql_error();
		} else {
	
	
			foreach($database_values as $var=>$val){
				$sql_qryvals = "values(";
		
				foreach($val as $dbval){
					$sql_qryvals .= "'".$dbval."', ";
				}
				
				$sql_qryvals = eregi_replace(', $', '', $sql_qryvals);
				$sql_qryvals .= ")";
				$compdb_qry = 'insert into '.$table_name.' '.$sql_qrytables.' '.$sql_qryvals;
				mysql_query($compdb_qry);
			}		
		}
	}
}


if($_POST['action'] == 'upload' || $_POST['action'] == '3'){
	
	$THIS_DISPLAY .= "<table border=\"0\" cellpadding=5 cellspacing=\"0\" class=\"text\" width=100% bgcolor=#708090 style='BORDER: 1px inset black;'>\n";
	$THIS_DISPLAY .= "<tr>\n";
	$THIS_DISPLAY .= "<td align=\"left\" valign=\"middle\" class=\"text\">\n";
	if(is_array($error)){
	  $THIS_DISPLAY .= "<font style='font-family: Arial; font-size: 9pt; color: white;'><b>".lang("Failed to Create Database and Import CSV").".</font>\n";
	} else {
	  $THIS_DISPLAY .= "<font style='font-family: Arial; font-size: 9pt; color: white;'><b>".lang("Success")."!</font>\n";
	}
	$THIS_DISPLAY .= "</td></tr><tr><td align=\"center\" valign=\"top\" bgcolor=WHITE class=\"text\">\n";
	
	if(is_array($error)){
	  $THIS_DISPLAY .= "<strong>".lang("The following errors occured:")."</strong><br/><br/>\n";
	  foreach($error as $val){
	    $THIS_DISPLAY .= $val."<br/>\n";
	  }
	} else {
	  $THIS_DISPLAY .= "<font color=green>".lang("The Database Table")." ".$table_name." ".lang("was created, and the .CSV was successfully imported")."</font><br/><br/>\n";
	}
	
	
	$THIS_DISPLAY .= "<a href=\"../download_data.php\">".lang("Click Here to return to the Database Table Manager")."</a><br/><br/>\n";
	$THIS_DISPLAY .= "</td></tr></table>\n";      	
	
}


$module_html = $THIS_DISPLAY;

$instructions = lang("Create a new databaste table from a CSV File.");

$module = new smt_module($module_html);
$module->meta_title = "Create Database Table &amp; Import CSV";
$module->add_breadcrumb_link("Database Table Manager", "program/modules/mods_full/download_data.php");
$module->add_breadcrumb_link("Create Database Table &amp; Import CSV", "program/modules/mods_full/database_manager/create_and_import_db.php?action=start");
$module->icon_img = "program/includes/images/data-tables-icon-med.png";
$module->heading_text = "Create Database Table &amp; Import CSV";
$module->description_text = $instructions;
$module->good_to_go();

?>