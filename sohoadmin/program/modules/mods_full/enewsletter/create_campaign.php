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
session_cache_limiter('private');
session_start();

//include("../includes/login.php");
require_once("../../../includes/product_gui.php");
//include($_SESSION['product_gui']);

# Restore newsletter prefs from db
$newspref = new userdata("newsletter");

#############################################################################################
### Sterilize Character String Function
#############################################################################################
$newspref = new userdata("newsletter");
if ( $newspref->get("default_emailfrom") == "" ) {
   $newspref->set("default_emailfrom", $_SESSION['getSpec']['df_email']);
}

if ( $newspref->get("default_emailfrom_display") == "" ) {
   $newspref->set("default_emailfrom_display", $newspref->get("default_emailfrom"));
}

if($newspref->get("newsletter_send_to_address") == "" ) {
   $newspref->set("newsletter_send_to_address", "list@".preg_replace('/^www\./', '', $this_ip));
}

if($newspref->get("newsletter_send_to_name") == "" ) {
   $newspref->set("newsletter_send_to_name", preg_replace('/^www\./', '', $this_ip)." subscriber");
}

function sterilize_char ($sterile_var) {

	$sterile_var = stripslashes($sterile_var);
	$sterile_var = eregi_replace(";", ",", $sterile_var);
	// $sterile_var = eregi_replace(" ", "_", $sterile_var);

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

#############################################################################################
### For each step we will keep building hidden vars and passing them to the next section
### in case of the end-user presses the back button.  This way it's easier to track the
### "session" without registering the session data. (Less of a memory hog)
#############################################################################################

$HIDDEN_POST = "";

if($_POST['personalize_opt'] != 'yes'){
	$_POST['USER_FIRST_NAME'] = '';
}

reset($HTTP_POST_VARS);
while (list($name, $value) = each($HTTP_POST_VARS)) {
	$value = stripslashes($value);			// Strip all slashes from data for HTML execution
	if ($name != "STEP_NUM") {
		$HIDDEN_POST .= "<INPUT TYPE=HIDDEN NAME=\"$name\" VALUE=\"$value\">\n";
		${$name} = $value;
	}
}

#############################################################################################
### START HTML/JAVASCRIPT CODE
#############################################################################################

$MOD_TITLE = "eNewsletter Campaign Setup Wizard";		// Give Mod Title to System
$TBL_HT = "400";										// For wizard, let's make our table expand height wise
$err_show = "";

#############################################################################################
### Modify Title and Basic Error Trap for Step 1.  Require a Campaign Name
#############################################################################################

$err_show = "";
$err_show1 = "";

if ($CAMPAIGN_NAME != "") {

	$CAMPAIGN_NAME = strtoupper($CAMPAIGN_NAME);
	$MOD_TITLE .= " : '$CAMPAIGN_NAME'";

}

if ($STEP_NUM == 2 && $TABLE_NAME == "") {
	$err_show1 = "<FONT COLOR=RED>*Please select a table name to use for this campaign.</font>";
	$STEP_NUM = "";
}

if ($STEP_NUM == 2 && strlen($CAMPAIGN_NAME) < 3) {
	$err_show = "<FONT COLOR=RED>*Please enter a valid campaign name before continuing.</font>";
	$STEP_NUM = "";
}



#############################################################################################

// Pre-build Mouseover script for new v4.7 buttons (because nobody likes side-scrolling)
$editOn = "class=\"btn_edit\" onMouseover=\"this.className='btn_editon';\" onMouseout=\"this.className='btn_edit';\"";
$saveOn = "class=\"btn_save\" onMouseover=\"this.className='btn_saveon';\" onMouseout=\"this.className='btn_save';\"";
$buildOn = "class=\"btn_build\" onMouseover=\"this.className='btn_buildon';\" onMouseout=\"this.className='btn_build';\"";

# Start buffering output
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

function SV2_openBrWindow(theURL,winName,features) { //v2.0
	window.open(theURL,winName,features);
}

SV2_showHideLayers('addCartMenu?header','','hide');
SV2_showHideLayers('blankLayer?header','','hide');
SV2_showHideLayers('linkLayer?header','','hide');
SV2_showHideLayers('newsletterLayer?header','','hide');
SV2_showHideLayers('cartMenu?header','','show');
SV2_showHideLayers('menuLayer?header','','hide');
SV2_showHideLayers('editCartMenu?header','','hide');

function build() {
	LOAD_LAYER.style.visibility = '';
	userOpsLayer.style.visibility = 'hidden';
}

function prev_sel() {

	var template = field_data.TEMPLATE_NAME.value;
	var page_name = field_data.PAGE_NAME.value;

	if (template != "" && page_name != "") {
		SV2_openBrWindow("view_setup.php?t="+template+"&p="+page_name+"&=SID", "quickview", "locationbar=no, scrollbars=yes, statusbar=no, width=1070, height=750");
	} else {
		alert('You need to select a template and content file in order to preview.');
	}

}

function step4() {

	var template = field_data.TEMPLATE_NAME.value;
	var page_name = field_data.PAGE_NAME.value;

	if (template != "" && page_name != "") {
		document.field_data.submit();
	} else {
		alert('You need to select a template and content file in order to continue.');
	}

}


function toggle_personal() {
	if(document.getElementById('tog_disp').value == 'yes'){
		document.getElementById('personal').style.display = 'block';
	} else {
		document.getElementById('personal').style.display = 'none';
	}
}


//-->
</script>

<?

// =================================================================================================
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
// Step One: This is a new "campaign" setup.
// =================================================================================================


if ($STEP_NUM == "") {

	#######################################################
	### FIND ALL WEBSITE DATA TABLES AND CREATE VAR
	### TO POPULATE "CURRENT" TABLES DROP DOWN BOX
	#######################################################

	$result = soho_list_tables();
	$i = 0;
	$numtablesinlist = 0; // Used to determine whether to show dummy "Website data tables..." option

	while ($i < mysql_num_rows ($result)) {
		$tb_names[$i] = mysql_tablename ($result, $i);
		$i++;
	}

	sort($tb_names[$i]);
	$tmp = $i - 1;
	for ($x=0;$x<=$tmp;$x++) {
		if ($row_color == "white") { $row_color = "#EFEFEF"; } else { $row_color = "white"; }

		# Filter string set?
		if ( $newspref->get("tablename_filter") != "" ) {
		   # Must contain filter string to be in list
		   if ( eregi("UDT_", $tb_names[$x]) && eregi($newspref->get("tablename_filter"), $tb_names[$x]) ) {
		      $isusertable = true;
		   } else {
		      $isusertable = false;
		   }

		} elseif ( eregi("UDT_", $tb_names[$x]) ) {
		   # No filter set, just needs UDT_
		   $isusertable = true;

      } else {
         # No filter set and no UDT_
         $isusertable = false;
      }

      $system_list_tables = array("sec_users", "cart_customers", "login");
      if ( $isusertable || (in_array($tb_names[$x], $system_list_tables) && $newspref->get("hide_systemtables") != "yes") ) {
		   $cq = mysql_query("select * from ".$tb_names[$x]." limit 2");
		   if(mysql_num_rows($cq) > 0){
		   	$CURRENT_TABLES .= "     <option value=\"".$tb_names[$x]."\" style='background: $row_color;'>".$tb_names[$x]."</option>\n";
		   	$numtablesinlist++;
		   }
		}
	}

	#######################################################

	//$THIS_DISPLAY .= "\n\n<!-- ----------------- START STEP 1 ---------------------- -->\n\n";

	$THIS_DISPLAY .= "<FORM name=\"step_one\" METHOD=POST ACTION=\"create_campaign.php\">\n";
	$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=STEP_NUM VALUE=2>\n";
	$THIS_DISPLAY .= $HIDDEN_POST;

	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=10 CELLSPACING=0 WIDTH=650 class=\"feature_sub\">\n";
	$THIS_DISPLAY .= " <TR>\n";
	$THIS_DISPLAY .= "  <TD ALIGN=LEFT VALIGN=MIDDLE class=\"fsub_title\">\n";
	$THIS_DISPLAY .= "   STEP 1 OF 5: Assign Campaign Name\n";
	$THIS_DISPLAY .= "  </TD>\n";
	$THIS_DISPLAY .= " </TR>\n";
	$THIS_DISPLAY .= " <TR>\n";
	$THIS_DISPLAY .= "  <TD ALIGN=LEFT VALIGN=TOP CLASS=text>\n";

   $THIS_DISPLAY .= "A. Give this new campaign a name for easy identification on the campaign manager page: \n";
   $THIS_DISPLAY .= "<BR><INPUT TYPE=TEXT CLASS=text id=\"CAMPAIGN_NAME\" NAME=CAMPAIGN_NAME VALUE=\"$CAMPAIGN_NAME\" STYLE='WIDTH: 200px;'>\n";
   $THIS_DISPLAY .= $err_show;
      
 
   $THIS_DISPLAY .= "<BR><BR><BR>\n";
   $THIS_DISPLAY .= "B. Choose a database table that contains the email addresses for this campaign: \n";

   # Filter string set?
   if ( $newspref->get("tablename_filter") != "" ) {
      $THIS_DISPLAY .= "<br/>Table name filter string: \"<strong>".$newspref->get("tablename_filter")."</strong>\"\n";
      $THIS_DISPLAY .= "(set in <a href=\"preferences.php\">preferences</a>)\n";
   }
   $THIS_DISPLAY .= "<BR>\n";   

	# Hide dummy option if filtered list has only one table in it
	if ( $numtablesinlist > 0 ) {
		$THIS_DISPLAY .= "<select id=\"db_table_name\" name=\"TABLE_NAME\" style='font-family: Arial; font-size: 8pt; width: 200px;'>\n";
	   	$THIS_DISPLAY .= "     <option value=\"\" style='color: #999999;' selected>Website data tables...</OPTION>\n";
   		$THIS_DISPLAY .= " ".$CURRENT_TABLES."\n";
   		$THIS_DISPLAY .= "</select>\n";

	} else {
		//$err_show1 .= "<FONT COLOR=RED>* No database tables containing email addresses where found.  Before you can create a newsletter campaign you must first create a database table containing the newsletter's recipients email addresses.  For more information see the user's manual.</font>";
		$err_show1 .= "<FONT COLOR=RED>* No database tables containing email addresses were found. Before you can create a newsletter campaign you must first create a database table containing the newsletter's recipients' email addresses. For more information see the user's manual.</font>";		
	}
   $THIS_DISPLAY .= $err_show1;


   //$THIS_DISPLAY .= "<br /><br /><br /><br /><div align=\"right\"><button onClick=\"document.step_one.submit();\" type=\"button\" class=\"blueButton\"><span><span>".lang("Next")." &gt;&gt;</span></span></span></div>\n\n";
   $THIS_DISPLAY .= "<br /><br /><br /><br /><div align=\"right\"><button onClick=\"if(document.getElementById('CAMPAIGN_NAME').value==''){ alert('Please enter a name for the campaign.'); } else { if(document.getElementById('db_table_name').options[document.getElementById('db_table_name').selectedIndex].value==''){ alert('Please select a database table for the campaign.'); } else { document.step_one.submit(); } }\" type=\"button\" class=\"blueButton\"><span><span>".lang("Next")." &gt;&gt;</span></span></span></div>\n\n";
   


	$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

	$THIS_DISPLAY .= "</FORM>";

//	$THIS_DISPLAY .= "\n\n<!-- ------------------ END STEP 1 ----------------------- -->\n\n";

} // END STEP ONE

// =================================================================================================
//
//   ########
//   ##    ##
//        ##
//       ##
//     ##
//   ##
//   ##    ##
//   ########
//
// =================================================================================================

if ($STEP_NUM == "2") {

	########################################################
	### Put all field names from selected table into var
	### for drop down box placement.  This is how we will
	### know which field's contain the data we need to
	### build a send.
	########################################################

	$result = mysql_query("SELECT * FROM $TABLE_NAME");
	$numberFields = mysql_num_fields($result);
	$numberFields--;

//	$FIELD_NAMES = "     <OPTION VALUE=\"\" STYLE='COLOR: #999999;'>Field Names:</OPTION>\n";
	$FIELD_NAMES = "";

	for ($x=0;$x<=$numberFields;$x++) {

		$fieldname = mysql_field_name($result, $x);

		if ($row_color == "white") { $row_color = "#EFEFEF"; } else { $row_color = "white"; }
			if(!eregi('prikey', $fieldname) && !eregi('auto_security_auth', $fieldname) && !eregi('auto_image', $fieldname)){
				if(eregi('mail', $fieldname)){ $selected = ' selected'; }
				$FIELD_NAMES .= "     <OPTION VALUE=\"$fieldname\" STYLE='background: $row_color;'".$selected.">$fieldname</OPTION>\n";
			}
	} // End Num Field Loop

	$FIELD_NAMES_NAME = "";

	for ($x=0;$x<=$numberFields;$x++) {

		$fieldname = mysql_field_name($result, $x);

		if ($row_color == "white") { $row_color = "#EFEFEF"; } else { $row_color = "white"; }
			if(!eregi('prikey', $fieldname) && !eregi('auto_security_auth', $fieldname) && !eregi('auto_image', $fieldname)){
				$selected = '';
				if(eregi('name', $fieldname)){ $selected = ' selected'; }
				$FIELD_NAMES_NAME .= "     <OPTION VALUE=\"$fieldname\" STYLE='background: $row_color;'".$selected.">$fieldname</OPTION>\n";
			}
	} // End Num Field Loop
	#######################################################

	//$THIS_DISPLAY .= "\n\n<!-- ----------------- START STEP 2 ---------------------- -->\n\n";

	$THIS_DISPLAY .= "<form name=\"field_data\" method=\"post\" action=\"create_campaign.php\">\n";
	$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=STEP_NUM VALUE=3>\n";
	$THIS_DISPLAY .= $HIDDEN_POST;

	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=10 CELLSPACING=0 WIDTH=650 class=\"feature_sub\">\n";
	$THIS_DISPLAY .= " <TR>\n";
	$THIS_DISPLAY .= "  <TD ALIGN=LEFT VALIGN=MIDDLE class=\"fsub_title\">\n";
	$THIS_DISPLAY .= "   STEP <span style=\"font-size: 115%;\">2</span> of <span style=\"font-size: 115%;\">5</span>: Match Required Field Data\n";
	$THIS_DISPLAY .= "  </TD>\n";
	$THIS_DISPLAY .= " </TR>\n";
	$THIS_DISPLAY .= "<TR>\n";
	$THIS_DISPLAY .= "<td align=\"left\" valign=\"top\" class=\"text\">\n";

   $THIS_DISPLAY .= "In order to build this campaign using \"$TABLE_NAME\", you will need to tell the system which fields in the table correspond to the data needed by the eNewsletter system when sending this campaign: \n";


   $THIS_DISPLAY .= "<BR><BR>A. Field containing <strong>EMAIL ADDRESS</strong> data: \n";
   $THIS_DISPLAY .= "<BR>\n";
   $THIS_DISPLAY .= "<select id=\"email_field\" name=\"USER_EMAIL_ADDRESS\" style='font-family: arial; font-size: 8pt; width: 200px;'>\n";
   $THIS_DISPLAY .= "".$FIELD_NAMES."\n";
   $THIS_DISPLAY .= "</select>\n";


	$THIS_DISPLAY .= "<BR><BR>B. Personalize Newsletter for each recipient?:&nbsp;&nbsp; \n";
	$THIS_DISPLAY .= "<SELECT ID=\"tog_disp\" name=\"personalize_opt\" STYLE='FONT-FAMILY: Arial; FONT-SIZE: 8pt; WIDTH: 250px;' onchange=\"toggle_personal();\">\n";
	$THIS_DISPLAY .= "     <OPTION VALUE=\"no\" STYLE='COLOR: #000000;' SELECTED>No (recommended )</OPTION>\n";
	$THIS_DISPLAY .= "     <OPTION VALUE=\"yes\" STYLE='COLOR: #000000;'>Yes</OPTION>\n";
   $THIS_DISPLAY .= "</select>\n";
   
	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=650>\n";
	$THIS_DISPLAY .= " <TR VALIGN=top>\n";
	$THIS_DISPLAY .= "  <TD style=\"padding-left:15;\" ALIGN=LEFT >\n";
   
   $THIS_DISPLAY .= "To Personalize your newsletter with each customers name you should have #name# in the newsletter content each place that you want their name to appear.\n";
   
   $THIS_DISPLAY .= "<br/><font color=\"red\">*</font> <font color=\"#000000\"><i>Sending Personalized newsletters make take several minutes and is not recommended when sending to a large number of recipients.</font></i>\n";
   
	$THIS_DISPLAY .= "<BR><BR>\n";
	$THIS_DISPLAY .= "  </TD></TR></TABLE>\n";
    
	$THIS_DISPLAY .= "<DIV name=\"personal\" id=\"personal\" style=\"display:none;\">\n";
	$THIS_DISPLAY .= "C. Field containing the <U>NAME</U> data: \n";
	$THIS_DISPLAY .= "<BR><SELECT NAME=\"USER_FIRST_NAME\" STYLE='FONT-FAMILY: Arial; FONT-SIZE: 8pt; WIDTH: 200px;'>$FIELD_NAMES_NAME</SELECT>\n";
   $THIS_DISPLAY .= "<br/><font color=\"red\">*</font> <font color=\"#000000\"><i>Only the first name will appear in your newsletter where you have #name# in the newsletters content.</font></i>\n";
	$THIS_DISPLAY .= "</DIV>\n";

//    $THIS_DISPLAY .= "<BR><BR>C. Field containing the <U>EMAIL TYPE</U> data: \n";
//    $THIS_DISPLAY .= "<BR><SELECT NAME=\"USER_SEND_TYPE\" STYLE='FONT-FAMILY: Arial; FONT-SIZE: 8pt; WIDTH: 200px;'>\n";
//
//       $FIELD_NAMES = eregi_replace("Field Names:", "[ AUTO ]", $FIELD_NAMES);
//
//    $THIS_DISPLAY .= "$FIELD_NAMES</SELECT>\n";
//    $THIS_DISPLAY .= "<BR><FONT COLOR=#999999>[ If the user has HTML or TEXT preference ]</FONT>\n\n";
	//$FIELD_NAMES_EMAIL
	$THIS_DISPLAY .= "<script language=javascript>\n";
	$THIS_DISPLAY .= "if(document.getElementById('tog_disp').value == 'yes'){ \n";
	$THIS_DISPLAY .= "	document.getElementById('personal').style.display = 'block'; \n";
	$THIS_DISPLAY .= "} \n";
	$THIS_DISPLAY .= "</script>\n";
	
	$THIS_DISPLAY .= "<BR><BR><BR><BR><DIV ALIGN=RIGHT><button type=\"button\" onClick=\"document.field_data.submit();\" class=\"blueButton\"><span><span>".lang("Next")." &gt;&gt;</span></span></button></DIV>\n\n";

	$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

	$THIS_DISPLAY .= "</FORM>";

//	$THIS_DISPLAY .= "\n\n<!-- ------------------ END STEP 2 ----------------------- -->\n\n";

	# Auto-detect email data field?
	$THIS_DISPLAY .= "<script language=\"javascript\">\n\n";

	$add_jscript = "";

	for ($x=0;$x<=$numberFields;$x++) {

		$fieldname = mysql_field_name($result, $x);
		if ( eregi("email", $fieldname) && $fieldname != "EMAIL_TYPE" ) { // Mantis #334
			$add_jscript .= "     $('email_field').value = '".$fieldname."'; \n";
		}

	} // End Num Field Loop

	$THIS_DISPLAY .= $add_jscript;
	$THIS_DISPLAY .= "\n\n</SCRIPT>\n\n";

} // End Step Two

// =================================================================================================
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
// =================================================================================================

if ($STEP_NUM == "3") {

	//$THIS_DISPLAY .= "\n\n<!-- ----------------- START STEP 3 ---------------------- -->\n\n";

	$THIS_DISPLAY .= "<FORM NAME=field_data METHOD=POST ACTION=\"create_campaign.php\">\n";
	$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=STEP_NUM VALUE=4>\n";
	$THIS_DISPLAY .= $HIDDEN_POST;

	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=10 CELLSPACING=0 WIDTH=650 class=\"feature_sub\">\n";
	$THIS_DISPLAY .= " <TR>\n";
	$THIS_DISPLAY .= "  <TD ALIGN=LEFT VALIGN=MIDDLE class=\"fsub_title\">\n";
	$THIS_DISPLAY .= "   STEP 3 OF 5: Owner Information\n";
	$THIS_DISPLAY .= "  </TD>\n";
	$THIS_DISPLAY .= " </TR>\n";
	$THIS_DISPLAY .= "<TR>\n";
	$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=TOP CLASS=text>\n";

   $THIS_DISPLAY .= "<b>This campaign will arrive as an email to your list. Please indicate what email address it will<BR>come from and the subject line:</b> \n";

   $THIS_DISPLAY .= "<BR><BR>A. <strong>From</strong> email address: \n";
   $THIS_DISPLAY .= "<BR><span class=\"red\"><b>This must be a valid email address to work on some servers.</b></span> \n";

   # DEFAULTS: Set default preferences, where applicable
   if ( $newspref->get("default_emailfrom") == "" ) { $newspref->set("default_emailfrom", $_SESSION['getSpec']['df_email']); }
   $THIS_DISPLAY .= "<BR><INPUT TYPE=TEXT CLASS=text NAME=FROM_EMAIL VALUE=\"".$newspref->get("default_emailfrom")."\" STYLE='WIDTH: 200px;'>\n";

   $MONTH = date("F");

   $THIS_DISPLAY .= "<BR><BR><BR>B. <strong>Subject Line</strong> of this campaign: \n";
   $THIS_DISPLAY .= "<BR><INPUT TYPE=TEXT CLASS=text NAME=SUBJECT_LINE VALUE=\"$MONTH Newsletter\" STYLE='WIDTH: 200px;'>\n";

   $THIS_DISPLAY .= "<BR><BR><BR><BR><DIV ALIGN=RIGHT><button type=\"button\" onClick=\"document.field_data.submit();\" class=\"blueButton\"><span><span>".lang("Next")." &gt;&gt;</span></span></button></DIV>\n\n";

	$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

	$THIS_DISPLAY .= "</FORM>";

//	$THIS_DISPLAY .= "\n\n<!-- ------------------ END STEP 3 ----------------------- -->\n\n";

} // End Step Three

// =================================================================================================
//
//   ##    ##
//   ##    ##
//   ##    ##
//   ########
//         ##
//         ##
//         ##
//         ##
//
// =================================================================================================
$select_page_error = 0;
//$THIS_DISPLAY .= testArray($_POST);
if($STEP_NUM == "5" && $PAGE_NAME==''){
	$STEP_NUM = "4";
	$select_page_error = 1;	
}

if ($STEP_NUM == "4") {

	#######################################################
	### POPULATE DROP DOWN VAR WITH ALL NEWSLETTER PAGES
	#######################################################

	$PAGES = "     <OPTION VALUE=\"\" STYLE='color: #999999;'>Newsletter Content Pages:</OPTION>\n";
	$PAGES .= "     <OPTION VALUE=\"NONE\" STYLE='background: #EFEFEF;'>[NONE] Template Contains Content</OPTION>\n";

	$row_color = "#EFEFEF";

	$result = mysql_query("SELECT page_name FROM site_pages");
	while ($row = mysql_fetch_array($result)) {
		if(!preg_match('/^cartid:/', $row['page_name'])){
			if ($row_color == "white") { $row_color = "#EFEFEF"; } else { $row_color = "white"; }
	
			# Filter string defined?
			if ( $newspref->get("pagename_filter") == "" || ($newspref->get("pagename_filter") != "" && eregi($newspref->get("pagename_filter"), $row['page_name'])) ) {
			   $PAGES .= "     <OPTION VALUE=\"".$row[page_name]."\" style='background: $row_color;'>".$row['page_name']."</OPTION>\n";
			}
		}
	}

	#######################################################
	### POPULATE DROP DOWN VAR AVAILABLE TEMPLATES.
	### FOR NEWSLETTERS, THERE IS ONLY  A SIGLE "STANDARD"
	### TEMPLATE INCLUDED WITH RELEASE.  MORE ARE AVAIL
	### AT THE DEVNET.
	#######################################################

	$TEMPLATES .= "     <option value=\"blank_template\" STYLE='color: #999999;'>HTML Newsletter Templates:</option>\n";

	$temp_dir = "$doc_root/sohoadmin/program/modules/site_templates/pages";
   $thandle = opendir("$temp_dir");

   while ( $tmlates = readdir($thandle) ) {
      if ( strlen($tmlates) > 2 && eregi("NEWSLETTER", $tmlates) ) {
         $disTemp = $temp_dir."/".$tmlates;
         $handle = opendir("$disTemp");
		   while ($files = readdir($handle)) {
			   if (strlen($files) > 2 && eregi("index.html", $files)) {
				   $TEMPLATES .= "     <option value=\"$tmlates\">$tmlates</option>\n";
				   break;
				}
			}
			closedir($handle);
		}
	}
	closedir($thandle);

$TEMPLATES .= "     <option value=\"blank_template\">".lang("No Template (page content only)")."</option>\n";


	#######################################################


	//$THIS_DISPLAY .= "\n\n<!-- ----------------- START STEP 4 ---------------------- -->\n\n";

	$THIS_DISPLAY .= "<FORM NAME=field_data METHOD=POST ACTION=\"create_campaign.php\">\n";
	$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=STEP_NUM VALUE=5>\n";
	$THIS_DISPLAY .= $HIDDEN_POST;

	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=10 CELLSPACING=0 WIDTH=650 class=\"feature_sub\">\n";
	$THIS_DISPLAY .= " <TR>\n";
	$THIS_DISPLAY .= "  <TD ALIGN=LEFT VALIGN=MIDDLE class=\"fsub_title\">\n";
	$THIS_DISPLAY .= "   STEP 4 OF 5: Select HTML Content\n";
	$THIS_DISPLAY .= "  </TD>\n";
	$THIS_DISPLAY .= " </TR>\n";
	$THIS_DISPLAY .= "<TR>\n";
	$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=TOP CLASS=text>\n";


	$THIS_DISPLAY .= "<b>Please select the template file and page name which contains the enewsletter content for<BR>sending the HTML version of this campaign:</b> \n";

   $THIS_DISPLAY .= "<BR><BR>A. Select the template to use with this campaign: \n";
   $THIS_DISPLAY .= "<BR><SELECT id=\"template_name\" NAME=\"TEMPLATE_NAME\" STYLE='FONT-FAMILY: Arial; FONT-SIZE: 8pt; WIDTH: 300px;'>".$TEMPLATES."</SELECT>\n";
   $THIS_DISPLAY .= "<script type=\"text/javascript\">$('template_name').value = '".$newspref->get("default_template")."';</script>\n";


   $THIS_DISPLAY .= "<BR><BR>";
   if($select_page_error == 1){
   	$THIS_DISPLAY .= "<span style=\"color:red;\">Error:</span>  You must select an attachment page.<br/> \n";	
   }
   $THIS_DISPLAY .= "B. Select the newsletter attachment page to use for content: \n";
   $THIS_DISPLAY .= "<BR><SELECT id=\"selectpage\" NAME=\"PAGE_NAME\" STYLE='FONT-FAMILY: Arial; FONT-SIZE: 8pt; WIDTH: 300px;'>".$PAGES."</SELECT> &nbsp;&nbsp; [ <a href=\"#\" onclick=\"prev_sel();\">Preview Selections</a> ]\n";

	$THIS_DISPLAY .= "<BR><BR><BR><BR><DIV ALIGN=RIGHT><button type=\"button\" onClick=\"document.field_data.submit();\" class=\"blueButton\"><span><span>".lang("Next")." &gt;&gt;</span></span></button></DIV>\n\n";

	$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

	$THIS_DISPLAY .= "</FORM>";

	//$THIS_DISPLAY .= "\n\n<!-- ------------------ END STEP 4 ----------------------- -->\n\n";



} // End Step Four

// =================================================================================================
//
//   ########
//   ##
//   ##
//   ########
//         ##
//         ##
//         ##
//   ########
//
// =================================================================================================

if ($STEP_NUM == "5") {

		########################################################################
		### SETUP DEFAULT TEXT CONTENT FOR USER TO EDIT TO HIS/HER LIKING.
		### THIS ALSO GIVES US THE CHANCE TO "SHOW" HOW THEY CAN ENTER VARIABLES
		### THAT WILL BE CHANGED BASED ON BUILD ROUTINE.
		########################################################################

		$MONTH = strtoupper(date("F"));
		$THIS_SITE = strtoupper($SERVER_NAME);

//		$DEF_TEXT = "\n\n$THIS_SITE $MONTH NEWSLETTER\n";
//		$DEF_TEXT .= "---------------------------------------------------------------------------------\n\n";
//		$DEF_TEXT .= "Apparently, your email program is incapable of viewing our newsletter, ";
//		$DEF_TEXT .= "most likely because you have selected not to receive HTML email.";
//		$DEF_TEXT .= "To view our HTML version, point your browser to:\n";
//		$DEF_TEXT .= "http://".$this_ip."/".pagename($PAGE_NAME)."\n\n";
//		$DEF_TEXT .= "If you do not wish to receive this newsletter,\nunsubscribe to this service by going to:\n";
//		$DEF_TEXT .= "http://".$this_ip."/subscription/index.php?id=unsubscribe\n\n";

#### Get Default Text
	$filename = "$TEMPLATE_NAME";
	$file = fopen("$filename", "r");
	$TEMPLATE_BODY = fread($file,filesize($filename));
	fclose($file);
	
	if ($PAGE_NAME == "NONE") {				// This is a custom template only HTML newsletter
		$HTML_TO_TEXT_CONTENT = $TEMPLATE_BODY;
	} else {
		// ------------------------------------------------------------------------
		// More complex; this is a tool generated email with template selection
		// Step 1: Read Template into tmp_variable
		// ------------------------------------------------------------------------
		$tmp = split("/", $TEMPLATE_NAME);
		$tmpc = count($tmp) - 1;
		$this_template = $tmp[$tmpc];
		$this_page = eregi_replace(" ", "_", $PAGE_NAME);
		$filename = "http://$this_ip/".pagename($this_page, "&")."nft=blank_template";
		
		
		
		$HTML_TO_TEXT_CONTENT = include_r("$filename");
		
		

		$this_title = strtoupper($SERVER_NAME);
		$HTML_TO_TEXT_CONTENT = str_replace("#TITLE#", "", $HTML_TO_TEXT_CONTENT);
		$HTML_TO_TEXT_CONTENT = str_replace("#UNSUBSCRIBE#", "news?=unsubscribe", $HTML_TO_TEXT_CONTENT);
	} // End Custom / Tool Generation Check
	
	$HTML_TO_TEXT_CONTENT = str_replace("background=\"sohoadmin", "background=\"http://$this_ip/sohoadmin", $HTML_TO_TEXT_CONTENT);
	$HTML_TO_TEXT_CONTENT = str_replace("src=\"sohoadmin", "src=\"http://$this_ip/sohoadmin", $HTML_TO_TEXT_CONTENT);
	$HTML_TO_TEXT_CONTENT = str_replace("src=\"images", "src=\"http://$this_ip/images", $HTML_TO_TEXT_CONTENT);
	$HTML_TO_TEXT_CONTENT = str_replace("<a href=\"pgm-email", "<a href=\"http://$this_ip/pgm-email", $HTML_TO_TEXT_CONTENT);
	$HTML_TO_TEXT_CONTENT = str_replace("href=\"http://".$this_ip."/".pagename($this_page)."#", "href=\"#", $HTML_TO_TEXT_CONTENT);
	$HTML_TO_TEXT_CONTENT = str_replace("href=\"".pagename($this_page)."#", "href=\"#", $HTML_TO_TEXT_CONTENT);
	$HTML_TO_TEXT_CONTENT = str_replace("href=\"index.php", "href=\"http://$this_ip/index.php", $HTML_TO_TEXT_CONTENT);

	$HTML_TO_TEXT_CONTENT = preg_replace('/\"<</', '\"', $HTML_TO_TEXT_CONTENT);
	$HTML_TO_TEXT_CONTENT = preg_replace('/>>\"/', '\"', $HTML_TO_TEXT_CONTENT);
	$HTML_TO_TEXT_CONTENT = preg_replace('/<style[^\e]*?<\/style>/i', '', $HTML_TO_TEXT_CONTENT);			
	$HTML_TO_TEXT_CONTENT = preg_replace('/<script[^\e]*?><\/script>/i', '', $HTML_TO_TEXT_CONTENT);
	$HTML_TO_TEXT_CONTENT = preg_replace('/<form[^\e]*?><\/form>/i', '', $HTML_TO_TEXT_CONTENT);
	$HTML_TO_TEXT_CONTENT = preg_replace('/<noscript>[^\e]*?<\/noscript>/i', '', $HTML_TO_TEXT_CONTENT);			
	$HTML_TO_TEXT_CONTENT = preg_replace('/<!--[^\e]*?-->/', '', $HTML_TO_TEXT_CONTENT);
	$HTML_TO_TEXT_CONTENT = preg_replace('/<[^\e]*?>/', '', $HTML_TO_TEXT_CONTENT);
	$HTML_TO_TEXT_CONTENT = eregi_replace('&nbsp;', ' ', $HTML_TO_TEXT_CONTENT);
	$HTML_TO_TEXT_CONTENT = preg_replace("/(\n\s\n)+/", "\n", $HTML_TO_TEXT_CONTENT);
	$HTML_TO_TEXT_CONTENT = preg_replace("/(\n\n)+/", "\n", $HTML_TO_TEXT_CONTENT);
	$HTML_TO_TEXT_CONTENT = preg_replace("/(\n\s\n)+/", "\n", $HTML_TO_TEXT_CONTENT);
	$HTML_TO_TEXT_CONTENT = preg_replace("/(\n\s\n)+/", "\n", $HTML_TO_TEXT_CONTENT);
	$HTML_TO_TEXT_CONTENT = preg_replace("/(\r\n)+/", "\n", $HTML_TO_TEXT_CONTENT);
	$HTML_TO_TEXT_CONTENT = preg_replace("/\n(\s*)\n/", "\n", $HTML_TO_TEXT_CONTENT); 
				
#### END Get Default Text



	//$THIS_DISPLAY .= "\n\n<!-- ----------------- START STEP 5 ---------------------- -->\n\n";

	$THIS_DISPLAY .= "<FORM NAME=field_data METHOD=POST ACTION=\"create_campaign.php\">\n";
	$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=STEP_NUM VALUE=6>\n";
	$THIS_DISPLAY .= $HIDDEN_POST;

	$THIS_DISPLAY .= "<TABLE align=\"center\" BORDER=0 CELLPADDING=10 CELLSPACING=0 WIDTH=650 class=\"feature_sub\">\n";
	$THIS_DISPLAY .= " <TR>\n";
	$THIS_DISPLAY .= "  <TD ALIGN=LEFT VALIGN=MIDDLE class=\"fsub_title\">\n";
	$THIS_DISPLAY .= "   STEP 5 OF 5: Compose Text Content\n";
	$THIS_DISPLAY .= "  </TD>\n";
	$THIS_DISPLAY .= " </TR>\n";
	$THIS_DISPLAY .= "<TR>\n";
	$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=TOP CLASS=text>\n";


	$THIS_DISPLAY .= "<center><b>For those users that have selected to receive text only campaigns, please create the text that will be sent to those users as well as embedded in the header of the HTML newsletter in case of errors:</b></center> \n";

		$THIS_DISPLAY .= "<BR><BR><TEXTAREA NAME=\"TEXT_EMAIL\" STYLE='background: #EFEFEF; width: 612; height: 225; font-family: Arial; font-size: 8pt;' WRAP=VIRTUAL>$HTML_TO_TEXT_CONTENT</TEXTAREA>\n";

	$THIS_DISPLAY .= "<BR><BR><BR><BR><DIV ALIGN=RIGHT><button type=\"button\" onClick=\"document.field_data.submit();\" class=\"greenButton\"><span><span>".lang("Create Campaign")."</span></span></button>\n";
	$THIS_DISPLAY .= "<BR><FONT COLOR=#FF0000>Creating the campaign does NOT send emails now.</FONT></DIV>\n\n";

	$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

	$THIS_DISPLAY .= "</FORM>";

	//$THIS_DISPLAY .= "\n\n<!-- ------------------ END STEP 5 ----------------------- -->\n\n";



} // End Step Five

// =================================================================================================
//
//   ########
//   ##
//   ##
//   ########
//   ##    ##
//   ##    ##
//   ##    ##
//   ########
//
// =================================================================================================

if ($STEP_NUM == "6") {

		########################################################################
		### TIME TO "BUILD" THE CAMPAIGN ::
		### Grab all the passed variable data from the wizard posts and place
		### it in a variable for save
		########################################################################

		$WIZ_VARS = "";

		reset($HTTP_POST_VARS);
		while (list($name, $value) = each($HTTP_POST_VARS)) {
			$value = stripslashes($value);			// Strip all slashes from data for insurance
			$value = addslashes($value);			// Add clean slashes for database insert
			$WIZ_VARS .= "$name=$value\n";
			// $THIS_DISPLAY .= "<B>$name</b>:<BR>$value<HR>";
		}

$tmp_query = "SELECT DISTINCT $USER_EMAIL_ADDRESS FROM $TABLE_NAME";
$result = mysql_query("$tmp_query");

$NUM_EMAILADDR = mysql_num_rows($result);

		########################################################################
		### MAKE SURE WE HAVE EMAIL ADDRESSES TO SEND TO IN THIS CAMPAIGN
		########################################################################

		if ($NUM_EMAILADDR == 0) {
				echo "<H3><br><br><center><font face=verdana color=#FF0000>Error: This campaign does not appear to have any email addresses to send to...";
				echo "<BR><BR>Addresses found: 0<BR></font></H3>";
         	echo "<FORM NAME=field_data METHOD=POST ACTION=\"create_campaign.php\">\n";
         	echo "<INPUT TYPE=HIDDEN NAME=STEP_NUM VALUE=\"\">\n";
         	echo $HIDDEN_POST;
			echo "<BR><BR><BR><BR><button type=\"button\" onClick=\"document.field_data.submit();\" class=\"blueButton\"><span><span>".lang("Create Again")." &gt;&gt;</span></span></button>\n\n";
         	echo "</center></form>\n";
				exit;
		}

		########################################################################
		### GET HTML NEWSLETTER CONTENT AND TEMPLATE
		########################################################################

		$filename = "$TEMPLATE_NAME";
		$file = fopen("$filename", "r");
			$TEMPLATE_BODY = fread($file,filesize($filename));
		fclose($file);

		if ($PAGE_NAME == "NONE") {				// This is a custom template only HTML newsletter

				$HTML_CONTENT = $TEMPLATE_BODY;

		} else {

				// ------------------------------------------------------------------------
				// More complex; this is a tool generated email with template selection
				// Step 1: Read Template into tmp_variable
				// ------------------------------------------------------------------------
				$tmp = split("/", $TEMPLATE_NAME);
				$tmpc = count($tmp) - 1;
				$this_template = $tmp[$tmpc];

				if($TEMPLATE_NAME == 'blank_template'){
					$this_template = 'blank_template';	
				}

				$this_page = eregi_replace(" ", "_", $PAGE_NAME);
				

				$filename = "http://$this_ip/".pagename($this_page, "&")."nft=$this_template";

				

				$filename = "http://$this_ip/".pagename($this_page, "&")."nft=$this_template";
				$HTML_CONTENT = include_r("$filename");
				
				

				//echo "<textarea style=\"width:400; height:400;\">".$HTML_CONTENT."</textarea><br><br>\n";
				//echo "(".$this_ip.")<br>";
//				if(eregi("www.",$this_ip)){
//				   echo $noWWW = eregi_replace("www.","",$this_ip);
//				}else{
//				   echo $noWWW = "www.".$this_ip;
//				}

				// ------------------------------------------------------------------------
				// Replace Newsletter Template Variable Data with appropriate values now
				// ------------------------------------------------------------------------

				$this_title = strtoupper($SERVER_NAME);
				$HTML_CONTENT = str_replace("#TITLE#", "$this_title", $HTML_CONTENT);
				$HTML_CONTENT = str_replace("#UNSUBSCRIBE#", "news?=unsubscribe", $HTML_CONTENT);

		} // End Custom / Tool Generation Check

//		$HTML_CONTENT = eregi_replace("http://".$this_ip."/", "", $HTML_CONTENT);	// Clean any images added to Word Processor after compiling full page // Commented-out because it was breaking absolute links back to sending website
		//$HTML_CONTENT = eregi_replace("http://$noWWW/", "", $HTML_CONTENT);	// Clean any images added to Word Processor after compiling full page
		$HTML_CONTENT = str_replace("background=\"sohoadmin", "background=\"http://$this_ip/sohoadmin", $HTML_CONTENT);
		$HTML_CONTENT = str_replace("src=\"sohoadmin", "src=\"http://$this_ip/sohoadmin", $HTML_CONTENT);
		$HTML_CONTENT = str_replace("src=\"images", "src=\"http://$this_ip/images", $HTML_CONTENT);

		// Added to fix links going straight to php files(pgm-email_friend.php, index.php) and not
		// using the domain name
		// Joe Lain 7.5.06
		$HTML_CONTENT = str_replace("<a href=\"pgm-email", "<a href=\"http://$this_ip/pgm-email", $HTML_CONTENT);
		

		
		$HTML_CONTENT = str_replace("href=\"http://".$this_ip."/".pagename($this_page)."#", "href=\"#", $HTML_CONTENT);
		$HTML_CONTENT = str_replace("href=\"".pagename($this_page)."#", "href=\"#", $HTML_CONTENT);

		$HTML_CONTENT = str_replace("href=\"index.php", "href=\"http://$this_ip/index.php", $HTML_CONTENT);

//		echo "<textarea style=\"width:700; height:400;\">".$HTML_CONTENT."</textarea>\n";

		// For Display Test Only -- Comment out for final build

		// $preview_html = eregi_replace("src=\"", "src=\"http://$this_ip/", $HTML_CONTENT);
		// $preview_html = eregi_replace("href=\"", "href=\"http://$this_ip/", $preview_html);
		// $THIS_DISPLAY .= "<br>$preview_html<hr>";

		########################################################################
		### LET'S BUILD THE IMAGE_ARRAY VAR SO WE KNOW WHAT IMAGES TO MIME
		### ENCODE WITH THE EMAIL SEND
		########################################################################

		$work_html = eregi_replace(">", ">\n", $HTML_CONTENT);		// Make sure that all image calls are on a single line by themselves
		$work_html = eregi_replace("<", "\n<", $work_html);

		$IMAGE_ARRAY = "";			// Set main array build var to empty

		$html_line = split("\n", $work_html);
		$lc = count($html_line);

		for ($x=0;$x<=$lc;$x++) {	// Start loop thru each html line

			if (eregi("\.gif", $html_line[$x]) || eregi("\.jpg", $html_line[$x])) { 	// This line contains an image filename

				$tmp = split(" ", $html_line[$x]);	// One more step; split this line by spaces
				$tmpc = count($tmp);
				$this_image = "";					// Clear var before next find

				for ($y=0;$y<=$tmpc;$y++) {

						if (eregi("\.gif", $tmp[$y]) || eregi("\.jpg", $tmp[$y])) {

							$tmp_find = eregi("src=(.*)", $tmp[$y], $out);
							$this_image = $out[1];

							if ($this_image == "") {		// If not normal img tag; must be background tag
								$tmp_find = eregi("background=\"(.*)\"", $out, $tmp[$y]);
								$this_image = $out[1];
							}

						} // End if GIF or JPG found inside HTML TAG
				} // End $y Loop

				$this_image = eregi_replace("\"", "", $this_image);	// Remove quotes from image name (bi-product of effective image find)
				$this_image = eregi_replace("'", "", $this_image);			// Remove single-quotes from image name (bi-product of effective image find)
				// $this_image = eregi_replace("/images", "", $this_image);	// Remove images folder for dumb shits like me... I goofed

				if ($this_image != "" && !eregi("$this_image", $IMAGE_ARRAY)) {
						// $IMAGE_ARRAY .= $doc_root . "/images/" . $this_image . ";";
						$this_image = eregi_replace("http://".$this_ip, '', $this_image);
						$IMAGE_ARRAY .= $doc_root .  $this_image . ";";
				}

			} // End if GIF or JPG found in this line
		} // End $x loop through each HTML line


		// The next two lines are for dev purposes and should be commented out for final build
		// $image_test = eregi_replace(";", "<BR>", $IMAGE_ARRAY);
		// echo "<BR>Images:<BR><BR>$image_test<BR>";
		// exit;

		########################################################################
		### EMBED TEXT CONTENT VALUE INTO HTML CODE [Head off MIME errors]
		########################################################################

		$HTML_CONTENT = "<!--\n\n$TEXT_EMAIL\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n -->\n\n\n\n" . $HTML_CONTENT;


//	echo "<TEXTAREA STYLE='WIDTH: 500px; HEIGHT: 300px;'>".$HTML_CONTENT."</TEXTAREA>\n";
//		exit;

		########################################################################
		### ADD CAMPAIGN TO CAMPAIN_MANAGER TABLE NOW
		########################################################################

		// ----------------------------------------------------
		// Make sure all values are insertable
		// ----------------------------------------------------

		

		$CAMPAIGN_NAME = addslashes($CAMPAIGN_NAME);
		$HTML_EMAIL_ADDR_KEY = addslashes($HTML_EMAIL_ADDR_KEY);
		$TEXT_EMAIL_ADDR = addslashes($HTML_TEXT_ADDR);
		$SUBJECT_LINE = addslashes($SUBJECT_LINE);
		$HTML_CONTENT = addslashes($HTML_CONTENT);
		$WIZ_VARS = addslashes($WIZ_VARS);
		$TEXT_EMAIL = addslashes($TEXT_EMAIL);
		$RECIPIENT_EMAIL_NAME = addslashes($_POST['USER_FIRST_NAME']);
		// ----------------------------------------------------

		$SQL_INSERT = "INSERT INTO campaign_manager VALUES('NULL', 'Pending', '$CAMPAIGN_NAME', '$TABLE_NAME', ";
		$SQL_INSERT .= "'$USER_EMAIL_ADDRESS', '$RECIPIENT_EMAIL_NAME', '$FROM_EMAIL', '$SUBJECT_LINE', '$HTML_CONTENT', ";
		$SQL_INSERT .= "'$IMAGE_ARRAY', '$WIZ_VARS', '$TEXT_EMAIL', '$NUM_EMAILADDR', '', '0000-00-00', '0', 'N')";

		// Test Line only - comment out for final build
		// $THIS_DISPLAY .= "<TEXTAREA STYLE='WIDTH: 500px; HEIGHT: 300px;'>$SQL_INSERT</TEXTAREA>\n";

		mysql_query("$SQL_INSERT") || DIE("<H4>Error Writing to Data Table (Could not create campaign): This is a programming error, consult with your webmaster.</H4>");

		########################################################################
		### AND I'M SPENT...
		########################################################################

		$THIS_DISPLAY .= "<BR><BR><BR><BR><BR><BR><H2><FONT COLOR=DARKBLUE>Campaign Created!</FONT></H2>\n\n";
		$THIS_DISPLAY .= "<FORM name=\"field_data\" METHOD=POST ACTION=\"../enewsletter.php\">\n";
		$THIS_DISPLAY .= "<button type=\"button\" onClick=\"document.field_data.submit();\" class=\"blueButton\"><span><span>".lang("Campaign Manager")."</span></span></button></FORM>\n\n";
		$THIS_DISPLAY .= "<DIV STYLE='font-size: 10pt;'>\n";
		$THIS_DISPLAY .= "Your campaign has been added with pending status.  You may now preview or<BR>SEND your campaign from the \"Campaign Manager\" Interface.\n";
		$THIS_DISPLAY .= "</DIV>\n";

} // End Step Six

$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n";

####################################################################

echo $THIS_DISPLAY;




####################################################################



# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Create a new eNewsletter campaign.");

$module = new smt_module($module_html);
$module->meta_title = "eNewsletter";
$module->add_breadcrumb_link("eNewsletter", "program/modules/mods_full/enewsletter.php");
$module->add_breadcrumb_link("Create Campaign", "program/modules/mods_full/enewsletter/create_campaign.php");
$module->icon_img = "program/includes/images/newsletter-icon-med.png";
$module->heading_text = "Create Campaign";
$module->description_text = $instructions;
$module->good_to_go();
?>
