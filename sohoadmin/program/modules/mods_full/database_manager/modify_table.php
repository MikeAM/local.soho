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

error_reporting(E_PARSE);
session_start();

include("../../../../includes/emulate_globals.php");
require_once("../../../includes/product_gui.php");
$THIS_DISPLAY .= "<html>\n";
$THIS_DISPLAY .= "<head>\n";

$globalprefObj = new userdata('global');
if ( $globalprefObj->get('utf8') == 'on' ) {
	$THIS_DISPLAY .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UT".'F'."-8\">\n";
} else {
	$THIS_DISPLAY .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
}

$THIS_DISPLAY .= "<META HTTP-EQUIV=\"Pragma\" CONTENT=\"no-cache\">\n";
$THIS_DISPLAY .= "<META HTTP-EQUIV=\"Expires\" CONTENT=\"-1\">\n";

$THIS_DISPLAY .= "<style>\n";

$THIS_DISPLAY .= ".deletelink {\n";
$THIS_DISPLAY .= "	color:#BA0000;\n";
$THIS_DISPLAY .= "}\n";
$THIS_DISPLAY .= "a:hover.deletelink {\n";
$THIS_DISPLAY .= "	color:red;\n";
$THIS_DISPLAY .= "}\n";
$THIS_DISPLAY .= "a.addfield {\n";
$THIS_DISPLAY .= "	color:green;\n";
$THIS_DISPLAY .= "}\n";
$THIS_DISPLAY .= "a:hover.addfield {\n";
$THIS_DISPLAY .= "	color:#EAA510;\n";
$THIS_DISPLAY .= "}\n";

$THIS_DISPLAY .= "</style>\n";

$THIS_DISPLAY .= "</head>\n";

//$THIS_DISPLAY .= testArray($_POST);

#######################################################
### INSERT STERILIZATION FUNCTION
#######################################################

function sterilize_char ($sterile_var) {
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
	//$sterile_var = strtoupper($sterile_var);
	return $sterile_var;
}

#######################################################
### PERFORM UPDATE ACTION
#######################################################

$UPDATE_FLAG = 0;

if ($ACTION == "UPDATE") {

	for ($x=0;$x<=$NUMFIELDS;$x++) {

			$old_name = "OLD_FIELD_NAME".$x;
			$old_name = ${$old_name};
			$new_name = "FIELD_NAME".$x;
			$new_name = ${$new_name};
			$new_name = sterilize_char($new_name);

			$old_type = "OLD_FIELD_TYPE".$x;
			$old_type = ${$old_type};
			$new_type = "FIELD_TYPE".$x;
			$new_type = ${$new_type};

			$old_len = "OLD_FIELD_LENGTH".$x;
			$old_len = ${$old_len};

			$old_key = "OLD_KEY_FLAG".$x;
			$old_key = ${$old_key};

			$new_len = "FIELD_LENGTH".$x;
			$new_len = ${$new_len};

		// -----------------------------------------------
		// RENAME A FIELD NAME
		// -----------------------------------------------

			$current_field_name = $old_name;

			if ($old_name != $new_name) {

				$tmp = $old_type;
				if ($old_len != "") { $tmp .= "($old_len)"; }

				$sql_statement = "ALTER TABLE $mt CHANGE $old_name $new_name $tmp";
				$current_field_name = $new_name;

				// echo $sql_statement."<BR><BR>";
				// exit;

				mysql_query("$sql_statement");

			}

		// -----------------------------------------------
		// CHANGE FIELD TYPE
		// -----------------------------------------------

			$current_field_type = $old_type;

			if ($old_type != $new_type) {

				$current_field_type = $new_type;
				$new_type = $new_type;

				if ($old_len != "" && $new_type != "BLOB" && $new_type != "DATE" && $new_type != "TIME" && $new_type != "INT NOT NULL AUTO_INCREMENT PRIMARY KEY") { $new_type .= "($old_len)"; }
				if ($old_key == 1) { $new_type .= ", DROP PRIMARY KEY"; }
				$sql_statement = "ALTER TABLE $mt MODIFY $current_field_name $new_type";

				// echo $sql_statement."<BR><BR>";
				// exit;

				mysql_query("$sql_statement");
			}

		// -----------------------------------------------
		// CHANGE FIELD LENGTH
		// -----------------------------------------------

			if ($old_len != $new_len && $current_field_type != "INT NOT NULL AUTO_INCREMENT PRIMARY KEY") {
				$new_type = $current_field_type;
				if ($new_len != "" && $new_type != "BLOB" && $new_type != "DATE" && $new_type != "TIME" && $new_type != "INT NOT NULL AUTO_INCREMENT PRIMARY KEY") { $new_type .= "($new_len)"; }
				$sql_statement = "ALTER TABLE $mt MODIFY $current_field_name $new_type";
				mysql_query("$sql_statement");


			}

	// echo "-->".$sql_statement."<BR><BR>";
	// exit;

	} // End Field Loop

	$UPDATE_FLAG = 1;

} // End Update Action

#######################################################
### PERFORM TABLE RENAME ACTION
#######################################################

if ($ACTION == "RENAME_TABLE") {

	$NEW_TABLE_NAME = sterilize_char($NEW_TABLE_NAME);
//	$NEW_TABLE_NAME = eregi_replace("UDT_", "", $NEW_TABLE_NAME);
//	$NEW_TABLE_NAME = "UDT_".$NEW_TABLE_NAME;

	$mt = $OLD_TABLE_NAME;	// Just in case a blank name is clicked on

	if ($NEW_TABLE_NAME != "" && $OLD_TABLE_NAME != "") {
		mysql_query("ALTER TABLE $OLD_TABLE_NAME RENAME $NEW_TABLE_NAME");
		$mt = $NEW_TABLE_NAME;
	}

	sleep(2);	// Delay for 2 seconds and give server time to catch up for next read function

	$UPDATE_FLAG = 1;

} // End Rename Table Action


/*---------------------------------------------------------------------------------------------------------*
    ___        __     __   _   __                  ______ _        __     __
   /   |  ____/ /____/ /  / | / /___  _      __   / ____/(_)___   / /____/ /
  / /| | / __  // __  /  /  |/ // _ \| | /| / /  / /_   / // _ \ / // __  /
 / ___ |/ /_/ // /_/ /  / /|  //  __/| |/ |/ /  / __/  / //  __// // /_/ /
/_/  |_|\__,_/ \__,_/  /_/ |_/ \___/ |__/|__/  /_/    /_/ \___//_/ \__,_/

/*---------------------------------------------------------------------------------------------------------*/
if ($ACTION == "ADD_NEW_FIELD") {



      $this_length = "";
      $FIELD_NAME = sterilize_char($FIELD_NAME);

	if ($FIELD_TYPE == "BLOB" || $FIELD_TYPE == "DATE" || $FIELD_TYPE == "TIME") { $FIELD_LENGTH = ""; }

      if ($FIELD_LENGTH != "") { $this_length = "($FIELD_LENGTH)"; }
      if (strlen($DEFAULT) > 0) { $this_default = "DEFAULT '$DEFAULT' NOT NULL"; }
	
      mysql_query("ALTER TABLE $mt ADD $FIELD_NAME $FIELD_TYPE".$this_length." $this_default ".$_POST['position']);

      sleep(2);   // Delay for 2 seconds and give server time to catch up for next read function

      $UPDATE_FLAG = 1;

} // End add new field action


/*---------------------------------------------------------------------------------------------------------*
    ____         __       __           ______ _        __     __
   / __ \ ___   / /___   / /_ ___     / ____/(_)___   / /____/ /
  / / / // _ \ / // _ \ / __// _ \   / /_   / // _ \ / // __  /
 / /_/ //  __// //  __// /_ /  __/  / __/  / //  __// // /_/ /
/_____/ \___//_/ \___/ \__/ \___/  /_/    /_/ \___//_/ \__,_/

/*---------------------------------------------------------------------------------------------------------*/
if ( $ACTION == "DELETE_FIELD" && strlen($_POST['field_tokill']) > 0 ) {

   $delQry = "ALTER TABLE ".$mt." DROP COLUMN ".$_POST['field_tokill'];
   mysql_query($delQry);
   $UPDATE_FLAG = 1;

} // End delete field action

#######################################################
### START HTML/JAVASCRIPT CODE			            ###
#######################################################

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

show_hide_layer('addCartMenu?header','','hide');
show_hide_layer('blankLayer?header','','hide');
show_hide_layer('linkLayer?header','','hide');
show_hide_layer('newsletterLayer?header','','hide');
show_hide_layer('cartMenu?header','','show');
show_hide_layer('menuLayer?header','','hide');
show_hide_layer('editCartMenu?header','','hide');

//-->
</script>

<?php
$fields_array = array();

/*-------------------------------------------------------------------------------------*
 __  __          _  _   __         _____       _     _
|  \/  | ___  __| |(_) / _| _  _  |_   _|__ _ | |__ | | ___
| |\/| |/ _ \/ _` || ||  _|| || |   | | / _` || '_ \| |/ -_)
|_|  |_|\___/\__,_||_||_|   \_, |   |_| \__,_||_.__/|_|\___|
                            |__/
/*-------------------------------------------------------------------------------------*/
	$THIS_DISPLAY .= "<form name=UPDATEDB method=\"post\" ACTION=\"modify_table.php\">\n";
	$THIS_DISPLAY .= "<input type=\"hidden\" name=ACTION value=\"UPDATE\">\n";
	$THIS_DISPLAY .= "<input type=\"hidden\" name=mt value=\"$mt\">\n";

	$result = mysql_query("SELECT * FROM $mt");
	$numberRows = mysql_num_rows($result);
	$numberFields = mysql_num_fields($result);
	$numberFields--;

	$THIS_DISPLAY .= "<input type=\"hidden\" name=NUMFIELDS value=\"$numberFields\">\n\n";

	$THIS_DISPLAY .= "<table border=0 cellpadding=5 cellspacing=0 class=\"text\" width=700>\n";
	$THIS_DISPLAY .= " <tr>\n";
	$THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\">\n";
	$THIS_DISPLAY .= "   <div align=\"left\">\n";
	$THIS_DISPLAY .= "    <font style='font-family: Arial; font-size: 9pt;'>\n";
	$THIS_DISPLAY .= "    <b>".lang("Modify Table").": \"<font color=\"maroon\">".$mt."</font>\"</b>\n";
	$THIS_DISPLAY .= "    </font>\n";
	$THIS_DISPLAY .= "   </div>\n";

	# Show 'Update Complete' message?
	if ($UPDATE_FLAG == 1) {
		$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font COLOR=RED style='font-family: Arial; font-size: 9pt;'><b>*".$lang["Update Complete"]."*</font>";
	}

	$THIS_DISPLAY .= "  </td>\n";
	$THIS_DISPLAY .= " </tr>\n";
	$THIS_DISPLAY .= "</table>\n";

	$THIS_DISPLAY .= "<table border=0 cellpadding=5 cellspacing=0 class=\"feature_sub\" width=700>\n";

	$THIS_DISPLAY .= "<tr>\n";
	$THIS_DISPLAY .= "<td align=\"center\" valign=\"middle\" class=\"fsub_title\">&nbsp;</td>\n";
	$THIS_DISPLAY .= "<td align=\"center\" valign=\"middle\" class=\"fsub_title\">".$lang["Field Name"]."</td>\n";
	$THIS_DISPLAY .= "<td align=\"center\" valign=\"middle\" class=\"fsub_title\">".$lang["Field Type"]."</td>\n";
	$THIS_DISPLAY .= "<td align=\"center\" colspan=\"2\" valign=\"middle\" class=\"fsub_title\">".$lang["Field Length"]."</td>\n";
	$THIS_DISPLAY .= "</tr>\n";

		$TYPE_OPTS = " <option value=\"INT\">INT </option>\n";
		$TYPE_OPTS .= " <option value=\"DATE\">DATE </option>\n";
		$TYPE_OPTS .= " <option value=\"TIME\">TIME </option>\n";
		$TYPE_OPTS .= " <option value=\"CHAR\" selected>CHAR </option>\n";
		//$TYPE_OPTS .= " <option value=\"VARCHAR\">VARCHAR </option>\n";
		$TYPE_OPTS .= " <option value=\"BLOB\">BLOB </option>\n";
		// $TYPE_OPTS .= "<OPTION VALUE=\"LONGBLOB\">LONGBLOB</OPTION>\n";
		// $TYPE_OPTS .= "<OPTION VALUE=\"INT NOT NULL AUTO_INCREMENT PRIMARY KEY\">INT NOT NULL AUTO_INCREMENT PRIMARY KEY</OPTION>\n";

		$BG_COLOR = "#EFEFEF";
		$javascript_type_set = "";


	for ($x=0;$x<=$numberFields;$x++) {

		$this_field = mysql_field_name($result, $x);
		$this_type = mysql_field_type($result, $x);
		$this_len = mysql_field_len($result, $x);
		$meta = mysql_fetch_field($result, $x);

		if ($meta->primary_key == 1) { $this_type = "INT"; }

	   # No length value for these field types
		if ($this_type == "blob" || $this_type == "date" || $this_type == "time" || $meta->primary_key == 1) { $this_len = ""; }

      # Alternate row bgcolor
		if ($BG_COLOR == "#EFEFEF") { $BG_COLOR = "WHITE"; } else { $BG_COLOR = "#EFEFEF"; }	// Setup for alternating row colors

		# Row counter
		$num_display = $x + 1;

		$THIS_DISPLAY .= " <tr>\n";
		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" bgcolor=\"$BG_COLOR\">".$num_display."</td>\n";

		$fields_array[] = $this_field;
		# Cannot rename system-used fields
		if ($this_field == "PRIKEY" || $this_field == "AUTO_IMAGE" || $this_field == "AUTO_SECURITY_AUTH") { $DIS = "DISABLED"; } else { $DIS = ""; }
		$DIS = "";

		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" bgcolor=\"".$BG_COLOR."\"><input $DIS type=\"text\" name=\"FIELD_NAME$x\" value=\"$this_field\" class=\"text\" style='width: 150px;'></td>\n";
		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" bgcolor=\"".$BG_COLOR."\"><select $DIS name=\"FIELD_TYPE$x\" class=\"text\">$TYPE_OPTS</select></td>\n";
		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" bgcolor=\"".$BG_COLOR."\"><input $DIS type=\"text\" name=\"FIELD_LENGTH$x\" value=\"$this_len\" MAXLENGTH=3 class=\"text\" style='width: 50px;'></td>\n";
		// $THIS_DISPLAY .= " <td align=\"center\" valign=\"middle\" bgcolor=\"".$BG_COLOR."\"><input $DIS type=\"text\" name=\"DEFAULT$x\" class=\"text\" style='width: 100px;'></td>\n";
		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" bgcolor=\"".$BG_COLOR."\">\n<a class=\"deletelink\" style=\"cursor: pointer; text-decoration:underline;\" onClick=\"kill_field('".$this_field."');\">".lang("Delete Field")."</a>\n</td>\n";
		
		$THIS_DISPLAY .= " </tr>\n";

		if ($this_type == "string") { $this_type = "CHAR"; }
		$this_type = strtoupper($this_type);

		$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=\"OLD_FIELD_NAME$x\" VALUE=\"$this_field\">\n";
		$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=\"OLD_FIELD_TYPE$x\" VALUE=\"$this_type\">\n";
		$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=\"OLD_FIELD_LENGTH$x\" VALUE=\"$this_len\">\n";
		$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=\"OLD_KEY_FLAG$x\" VALUE=\"$meta->primary_key\">\n";

		$javascript_type_set .= "document.UPDATEDB.FIELD_TYPE$x.value = '$this_type';\n";

	}

	$THIS_DISPLAY .= "</TABLE>\n";

	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=10 CELLSPACING=0 CLASS=text WIDTH=700>\n";
	$THIS_DISPLAY .= "<TR>\n";
	$THIS_DISPLAY .= "<TD ALIGN=RIGHT VALIGN=MIDDLE>\n";
	$THIS_DISPLAY .= "<button TYPE=button class=\"greenButton\" onClick=\"document.UPDATEDB.submit();\"><span><span>Update Table</span></span></button>\n";
	$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

	$THIS_DISPLAY .= "</FORM>\n\n";

	/*----------------------------------------------------------------------------------*
      _       _     _   _  _                ___  _       _     _
     /_\   __| | __| | | \| | ___ __ __ __ | __|(_) ___ | | __| |
    / _ \ / _` |/ _` | | .` |/ -_)\ V  V / | _| | |/ -_)| |/ _` |
   /_/ \_\\__,_|\__,_| |_|\_|\___| \_/\_/  |_|  |_|\___||_|\__,_|

	/*----------------------------------------------------------------------------------*/
	$NEW_TABLE_NUM_FIELDS = 1; 		// Using same routine as "create_table.php"; let's just load the vars

	$THIS_DISPLAY .= "\n\n<SCRIPT LANGUAGE=Javascript>\n\n";

	$THIS_DISPLAY .= "     function add_field() {\n\n";
	$THIS_DISPLAY .= "          var err=0;\n";

	for ($x=1;$x<=$NEW_TABLE_NUM_FIELDS;$x++) {

		$THIS_DISPLAY .= "          if(newfield.FIELD_NAME.value == '') { err=$x; }\n";
		$THIS_DISPLAY .= "          if(newfield.FIELD_TYPE.options.value == 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY' && newfield.FIELD_LENGTH.value != '') { err=$x; }\n";
		$THIS_DISPLAY .= "          if(newfield.FIELD_TYPE.options.value == 'BLOB' && newfield.FIELD_LENGTH.value != '') { err=$x; }\n";
		$THIS_DISPLAY .= "          if(newfield.FIELD_TYPE.options.value == 'LONGBLOB' && newfield.FIELD_LENGTH.value != '') { err=$x; }\n";
		$THIS_DISPLAY .= "          if(newfield.FIELD_TYPE.options.value == 'DATE' && newfield.FIELD_LENGTH.value != '') { err=$x; }\n";
		$THIS_DISPLAY .= "          if(newfield.FIELD_TYPE.options.value == 'TIME' && newfield.FIELD_LENGTH.value != '') { err=$x; }\n\n";
		$THIS_DISPLAY .= "          if(newfield.FIELD_LENGTH.value > 255) { err=$x; }\n";
	}

	$THIS_DISPLAY .= "          if(err==0) { window.newfield.submit(); }\n\n";
	$THIS_DISPLAY .= "          if(err>0) { alert('".$lang["The data you have entered is not formated properly."]."\\n".$lang["Please check your setup and try again."]."\\n'); }\n\n";
	$THIS_DISPLAY .= "     } // End Create Table Function\n\n";
	$THIS_DISPLAY .= "</SCRIPT>\n\n\n";

	// -----------------------------------------------------------------------
	# Begin delete field form
	$THIS_DISPLAY .= "<form name=\"newfield\" method=\"post\" action=\"modify_table.php\">\n";
	$THIS_DISPLAY .= "<input type=\"hidden\" name=\"ACTION\" value=\"ADD_NEW_FIELD\">\n\n";
	$THIS_DISPLAY .= "<input type=\"hidden\" name=\"mt\" value=\"$mt\">\n\n";

   # Add new field to
	$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"text\" width=\"700\">\n";
	$THIS_DISPLAY .= " <tr>\n";
	$THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\">\n";
	$THIS_DISPLAY .= "   <div align=\"left\">\n";
	$THIS_DISPLAY .= "    <font style='font-family: Arial; font-size: 9pt;'>\n";
	$THIS_DISPLAY .= "    <b>".lang("Add new field to").":\" <font color=\"maroon\">".$mt."</font>\".</b></font>\n";
	$THIS_DISPLAY .= "  </td>\n";
	$THIS_DISPLAY .= " </tr>\n";
	$THIS_DISPLAY .= "</table>\n";

	# Begin form table
	$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" class=\"feature_sub\" bgcolor=\"#EFEFEF\" width=\"700\">\n";

	# Column title row
	$THIS_DISPLAY .= " <tr>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" class=\"fsub_title\">&nbsp;</td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" class=\"fsub_title\">".lang("Field Name")."</td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" class=\"fsub_title\">".lang("Field Type")."</td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" class=\"fsub_title\">".lang("Field Length")."</td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" class=\"fsub_title\">".lang("Default Value")."</td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" class=\"fsub_title\">".lang("Field Position")."</td>\n";
   $THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" class=\"fsub_title\">&nbsp;</td>\n";
	$THIS_DISPLAY .= " </tr>\n";

   # Build drop down list of field types
   $TYPE_OPTS = " <option value=\"INT\">INT </option>\n";
   $TYPE_OPTS .= " <option value=\"DATE\">DATE </option>\n";
   $TYPE_OPTS .= " <option value=\"TIME\">TIME </option>\n";
   $TYPE_OPTS .= " <option value=\"CHAR\" SELECTED>CHAR </option>\n";
   //$TYPE_OPTS .= " <option value=\"VARCHAR\">VARCHAR </option>\n";
   $TYPE_OPTS .= " <option value=\"BLOB\">BLOB </option>\n";
   // $TYPE_OPTS .= "<OPTION VALUE=\"LONGBLOB\">LONGBLOB</OPTION>\n";
   // $TYPE_OPTS .= "<OPTION VALUE=\"INT NOT NULL AUTO_INCREMENT PRIMARY KEY\">INT NOT NULL AUTO_INCREMENT PRIMARY KEY</OPTION>\n";

   $BG_COLOR = "#EFEFEF";

	# Display rows & field fields
	for ($x=1;$x<=$NEW_TABLE_NUM_FIELDS;$x++) {

		if ($BG_COLOR == "#EFEFEF") { $BG_COLOR = "WHITE"; } else { $BG_COLOR = "#EFEFEF"; }	// Setup for alternating row colors

		$THIS_DISPLAY .= " <tr>\n";

		# Row/field counter
		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" bgcolor=\"$BG_COLOR\">".$x."</td>\n";

		# Field Name
		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" bgcolor=\"$BG_COLOR\">\n";
		$THIS_DISPLAY .= "   <input type=\"text\" name=\"FIELD_NAME\" class=\"text\" style='width: 150px;'>\n";
		$THIS_DISPLAY .= "  </td>\n";

		# Field Type
		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" bgcolor=\"$BG_COLOR\">\n";
		$THIS_DISPLAY .= "   <select name=\"FIELD_TYPE\" class=\"text\">\n";
		$THIS_DISPLAY .= "    ".$TYPE_OPTS."\n";
		$THIS_DISPLAY .= "   </select>\n";
		$THIS_DISPLAY .= "  </td>\n";

		# Field Length
		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" bgcolor=\"$BG_COLOR\">\n";
		$THIS_DISPLAY .= "   <input type=\"text\" name=\"FIELD_LENGTH\" value=\"255\" MAXLENGTH=3 class=\"text\" style='width: 50px;'>\n";
		$THIS_DISPLAY .= "  </td>\n";

		# Default Value
		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" bgcolor=\"$BG_COLOR\">\n";
		$THIS_DISPLAY .= "   <input type=\"text\" name=\"DEFAULT\" class=\"text\" style='width: 100px;'>\n";
		$THIS_DISPLAY .= "  </td>\n";

		# Position
		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" bgcolor=\"$BG_COLOR\">\n";
		$THIS_DISPLAY .= "		<select name=\"position\" class=\"text\" style='width: 130px;'>\n";
		$THIS_DISPLAY .= "	<option value=\"FIRST\">Begining of table</option>\n";
		$fac = 0;
		$fcount = count($fields_array);
		foreach($fields_array as $fval){
			++$fac;
			$THIS_DISPLAY .= "	<option value=\" AFTER ".$fval."\"";
			if($fac == $fcount){
				
				$THIS_DISPLAY .= " SELECTED>End of table</option>\n";
			} else {
				$THIS_DISPLAY .= ">After ".$fval."</option>\n";	
			}
			
		}
		$THIS_DISPLAY .= "		</select>\n";
		$THIS_DISPLAY .= "  </td>\n";		
		
		

      # (Add Field)
   	$THIS_DISPLAY .= "  <td align=\"right\" valign=\"middle\" bgcolor=\"$BG_COLOR\">\n";
   	//$THIS_DISPLAY .= "   <input type=\"button\" value=\" ".lang("Add Field")." \" ".$btn_save." onClick=\"add_field();\">&nbsp;&nbsp;\n";
   	$THIS_DISPLAY .= "   <a href=\"javascript:void(0);\" class=\"addfield\" onClick=\"add_field();\">".lang("Add Field")."</a>\n";
   	$THIS_DISPLAY .= "  </td>\n";

		$THIS_DISPLAY .= " </tr>\n";

	}

	$THIS_DISPLAY .= "</table>\n";

	$THIS_DISPLAY .= "</form>\n\n";


	/*----------------------------------------------------------------------------------*
    ___        _       _          ___  _       _     _
   |   \  ___ | | ___ | |_  ___  | __|(_) ___ | | __| |
   | |) |/ -_)| |/ -_)|  _|/ -_) | _| | |/ -_)| |/ _` |
   |___/ \___||_|\___| \__|\___| |_|  |_|\___||_|\__,_|

	/*----------------------------------------------------------------------------------*/
	$NEW_TABLE_NUM_FIELDS = 1; 		// Using same routine as "create_table.php"; let's just load the vars

	$THIS_DISPLAY .= "\n\n";
	$THIS_DISPLAY .= "<script language=\"javascript\">\n";
	$THIS_DISPLAY .= "function kill_field(fieldname) {\n\n";
	$THIS_DISPLAY .= "   usure = window.confirm('";

	# Delete warning/confirmation
	$THIS_DISPLAY .= "WARNING!!\\n";
	$THIS_DISPLAY .= "Deleting this field may prevent web forms and custom scripts from adding data to this table successfully.\\n\\n";
	$THIS_DISPLAY .= "Are you sure you want to delete '+fieldname+'?";
	$THIS_DISPLAY .= "');\n";
	$THIS_DISPLAY .= "   if ( usure==true ) {\n";
	$THIS_DISPLAY .= "      document.getElementById('field_tokill').value=fieldname;\n";
	$THIS_DISPLAY .= "      document.delfield.submit();\n";
	$THIS_DISPLAY .= "   }\n";
	$THIS_DISPLAY .= "}\n";
	$THIS_DISPLAY .= "</script>\n\n\n";

	// -----------------------------------------------------------------------

	# Begin delete field form
	$THIS_DISPLAY .= "<form name=\"delfield\" method=\"post\" action=\"modify_table.php\">\n";
	$THIS_DISPLAY .= "<input type=\"hidden\" name=\"ACTION\" value=\"DELETE_FIELD\">\n\n";
	$THIS_DISPLAY .= "<input type=\"hidden\" name=\"mt\" value=\"".$mt."\">\n\n";
	$THIS_DISPLAY .= "<input type=\"hidden\" name=\"field_tokill\" id=\"field_tokill\" value=\"\">\n\n";

//   # Delete a column (field) from [TABLE]
//	$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"text\" width=\"450\">\n";
//	$THIS_DISPLAY .= " <tr>\n";
//	$THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\">\n";
//	$THIS_DISPLAY .= "   <div align=\"left\">\n";
//	$THIS_DISPLAY .= "    <font style='font-family: Arial; font-size: 9pt;'>\n";
//	$THIS_DISPLAY .= "    <b><font color=\"maroon\">".lang("Delete field from")." ".$mt."</font>.</b></font>\n";
//	$THIS_DISPLAY .= "  </td>\n";
//	$THIS_DISPLAY .= " </tr>\n";
//	$THIS_DISPLAY .= "</table>\n";
//
//	# Begin form table
//	$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"feature_sub\" bgcolor=\"#EFEFEF\" width=\"450\">\n";
//
//	# Column title row
//	$THIS_DISPLAY .= " <tr>\n";
//   $THIS_DISPLAY .= "  <td align=\"left\" valign=\"middle\" class=\"fsub_title\">".lang("Field Name")."</td>\n";
//   $THIS_DISPLAY .= "  <td align=\"left\" valign=\"middle\" class=\"fsub_title\">&nbsp;</td>\n";
//	$THIS_DISPLAY .= " </tr>\n";
//
//	# Build drop down list of fields
//	$FNAME_OPTS = "<select name=\"field_tokill\" style=\"width: 250px;\">\n";
//
//	# Loop through column names to build drop down
//	for ($x=0;$x<=$numberFields;$x++) {
//		$this_field = mysql_field_name($result, $x);
//		$this_type = mysql_field_type($result, $x);
//		$this_len = mysql_field_len($result, $x);
//		$meta = mysql_fetch_field($result, $x);
//
//      # Alternate row bgcolor
//		if ($BG_COLOR == "#EFEFEF") { $BG_COLOR = "WHITE"; } else { $BG_COLOR = "#EFEFEF"; }	// Setup for alternating row colors
//
//		# Add field name to option list if not system field
//		if ( $this_field != "PRIKEY" && $this_field != "AUTO_IMAGE" && $this_field != "AUTO_SECURITY_AUTH" ) {
//   		$FNAME_OPTS .= "<option value=\"".$this_field."\" style=\"background-color: ".$BG_COLOR.";\">$this_field</option>\n";
//   	}
//	}
//
//	$FNAME_OPTS .= "</select>\n";
//
//	# Display rows & field fields
//	for ($x=1;$x<=$NEW_TABLE_NUM_FIELDS;$x++) {
//
//		if ($BG_COLOR == "#EFEFEF") { $BG_COLOR = "WHITE"; } else { $BG_COLOR = "#EFEFEF"; }	// Setup for alternating row colors
//
//		$THIS_DISPLAY .= " <tr>\n";
//
//		# Field Name
//		$THIS_DISPLAY .= "  <td align=\"left\" valign=\"middle\" bgcolor=\"$BG_COLOR\">\n";
//		$THIS_DISPLAY .= "   ".$FNAME_OPTS."\n";
//		$THIS_DISPLAY .= "  </td>\n";
//
//      # (Add Field)
//   	$THIS_DISPLAY .= "  <td align=\"right\" valign=\"middle\" bgcolor=\"$BG_COLOR\">\n";
//   	$THIS_DISPLAY .= "   <input type=\"button\" value=\" ".lang("Delete Field")." \" ".$btn_delete." onClick=\"kill_field();\">&nbsp;&nbsp;\n";
//   	$THIS_DISPLAY .= "  </td>\n";
//
//		$THIS_DISPLAY .= " </tr>\n";
//
//	}
//
//	$THIS_DISPLAY .= "</table>\n";

	$THIS_DISPLAY .= "</form>\n\n";


	// ----------------------------------------------------------------------------------
	// SHOW NEW FORM FOR RENAMING ENTIRE TABLE, ETC.
	// ----------------------------------------------------------------------------------

	$THIS_DISPLAY .= "<FORM NAME=\"RENAMEDB\" METHOD=POST ACTION=\"modify_table.php\">\n";
	$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=ACTION VALUE=\"RENAME_TABLE\">\n";
	$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=OLD_TABLE_NAME VALUE=\"".$mt."\">\n";

	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 CLASS=text WIDTH=700>\n";
	$THIS_DISPLAY .= "<TR>\n";
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE>\n";
	$THIS_DISPLAY .= "<DIV ALIGN=LEFT><FONT STYLE='font-family: Arial; font-size: 9pt;'><B>".$lang["Rename Table"].": \"<FONT COLOR=MAROON>$mt</FONT>\".</B></FONT>\n";
	$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 CLASS=text WIDTH=700 STYLE='BORDER: 1px inset black;'>\n";
	$THIS_DISPLAY .= "<TR>\n";
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=#EFEFEF>\n";
	$THIS_DISPLAY .= "<DIV ALIGN=LEFT><FONT STYLE='font-family: Arial; font-size: 9pt;'><B>New Table Name: &nbsp;&nbsp;\n";
	$THIS_DISPLAY .= "<INPUT TYPE=TEXT CLASS=text MAXLENGTH=64 NAME=\"NEW_TABLE_NAME\" VALUE=\"".$mt."\" STYLE='WIDTH: 250px;'>\n";

	$THIS_DISPLAY .= "<button TYPE=button class=\"greenButton\" onClick=\"document.RENAMEDB.submit();\"><span><span>Rename Table</span></span></button>\n";

	$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

	$THIS_DISPLAY .= "</FORM>\n";

echo $THIS_DISPLAY;
?>

<SCRIPT LANGUAGE=JAVASCRIPT>

	<? echo $javascript_type_set; ?>

</SCRIPT>

<?
# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = "Modify field structure of a database table.";
$instructions .= "You can add, rename, and delete columns as well as change their type. Intended for advanced users only.";

$module = new smt_module($module_html);
$module->add_breadcrumb_link("Database Tables", "program/modules/mods_full/download_data.php");
$module->add_breadcrumb_link("Modify Table: <span class=\"unbold\">".$mt."</span>", "program/modules/mods_full/database_manager/modify_table.php?mt=".$mt);
$module->icon_img = "program/includes/images/data-tables-icon-med.png";
$module->heading_text = "Modify Table: <span class=\"unbold\">".$mt."</span>";
$module->meta_title = "Modify Table: ".$mt;
$module->description_text = $instructions;
$module->good_to_go();

?>