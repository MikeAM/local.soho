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
include("../../includes/product_gui.php");

//include("../../../includes/emulate_globals.php");

#######################################################
### IF THE 'campaign_manager' TABLE DOES NOT EXIST;
### CREATE IT NOW
#######################################################

		$match = 0;
		$tablename = "campaign_manager";

		$result = soho_list_tables();
		$i = 0;
		while ($i < mysql_num_rows ($result)) {
			$tb_names[$i] = mysql_tablename ($result, $i);
			if ($tb_names[$i] == $tablename) {
				$match = 1;
			}
			$i++;
		}

		// if ($match == 1) { mysql_query("DROP TABLE $tablename"); }

		if ($match != 1) {

			mysql_db_query("$db_name","CREATE TABLE $tablename (

				PRIKEY INT NOT NULL AUTO_INCREMENT PRIMARY KEY,

				STATUS CHAR(15),
				CAMPAIGN_NAME CHAR(50),
				TABLE_NAME CHAR(50),
				HTML_EMAIL_ADDR BLOB,
				TEXT_EMAIL_ADDR BLOB,
				FROM_ADDR CHAR(255),
				SUBJECT_LINE CHAR(255),
				HTML_CONTENT BLOB,
				IMAGE_ARRAY BLOB,
				WIZ_VARS BLOB,
				TEXT_CONTENT BLOB,
				NUM_HTML_CNT INT(20),
				NUM_TEXT_CNT INT(20),
				SEND_DATE DATE,
				CLICK_THRU_CNT INT(50),
				ARCHIVE_FLAG CHAR(1)

				)");

		} // End if Match != 1

#######################################################
### IF THE 'UNSUBSCRIBE' TABLE DOES NOT EXIST;
### CREATE IT NOW
#######################################################

		$match = 0;
		$tablename = "unsubscribe";

		$result = soho_list_tables();
		$i = 0;
		while ($i < mysql_num_rows ($result)) {
			$tb_names[$i] = mysql_tablename ($result, $i);
			if ($tb_names[$i] == $tablename) {
				$match = 1;
			}
			$i++;
		}

		// if ($match == 1) { mysql_query("DROP TABLE $tablename"); }

		if ($match != 1) {

			mysql_db_query("$db_name","CREATE TABLE $tablename (

				PRIKEY INT NOT NULL AUTO_INCREMENT PRIMARY KEY,

				UNSUB_EMAIL_ADDR CHAR(255),
				UNSUB_DATE DATE

				)");

			$today = date("Y-m-d");
			mysql_query("INSERT INTO $tablename VALUES(0,'default@my-domain.com','$today')");

		} // End if Match != 1



#######################################################
### START HTML/JAVASCRIPT CODE					    ###
#######################################################

$MOD_TITLE = "eNewsletter System : Main Menu";
$BG = "shared/enews_bg.jpg";

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

function SV2_popupMsg(msg) { //v1.0
  alert(msg);
}
function SV2_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

show_hide_layer('MAIN_MENU_LAYER?header','','hide');
show_hide_layer('CART_MENU_LAYER?header','','hide');
show_hide_layer('DATABASE_LAYER?header','','hide');
show_hide_layer('NEWSLETTER_LAYER?header','','show');
//var p = "eNewsletter";
//parent.frames.footer.setPage(p);

function preview(a) {
	if (a != "") {
		SV2_openBrWindow("enewsletter/preview.php?v="+a,"newsprev","width=750, height=450, scrollbars=yes, locationbar=no, statusbar=no");
	}
}

function delete_camp(name,key) {
		var tiny = window.confirm('<? echo lang("You have selected to delete the campaign"); ?> "'+name+'".\n\n<? echo lang("Do you wish to continue with this action"); ?>?');
		if (tiny != false) {
			// OK Redirect to Send Routine
			window.location = "enewsletter/delete_campaign.php?id="+key+"&<?=SID?>";
		}
}

function confirm_send(name,users,key){
	var testemailaddress=window.prompt("Enter the email address to send the \""+name+"\" test to.")
	if(testemailaddress!='' || testemailaddress==null){
		build();
		window.location = "enewsletter/send_now.php?id="+key+"&testemail="+testemailaddress+"&usertotal="+users+"&<?=SID?>";
	} else {
		alert('Please enter a Valid test Email Address!');
		//confirm_send(name,users,key);
		//var testemailaddress=window.prompt("Please enter a Valid test Email Address! Enter the email address to send the \""+name+"\" test to.")	
	}
}

<? if ($sendflag == 1) { echo "alert('Your campaign has been sent!');"; } ?>

function build() {
	LOAD_LAYER.style.visibility = '';
}

//-->
</script>


<DIV ID="LOAD_LAYER" style="position:absolute; left:0px; top:0px; width:100%; height:98%; z-index:100; border: 2px none #000000; visibility: hidden; overflow: hidden">
<table border=0 cellpadding=0 width=100% height=100% bgcolor=WHITE>
    <tr>
      <td align=center valign=middle class=text>
		<h4><? echo lang("SENDING CAMPAIGN"); ?>.</h4><FONT COLOR=#999999><? echo lang("This may take up to 30 seconds"); ?>...</FONT>
      </td>
    </tr>
  </table>
</DIV>

<?

// -------------------------------------------------------------------------------
// Present eNewsletter Campaign Manager
// -------------------------------------------------------------------------------

// Pre-build Mouseover script for new v4.7 buttons (because nobody likes side-scrolling)
$editOn = "class=\"btn_edit\" onMouseover=\"this.className='btn_editon';\" onMouseout=\"this.className='btn_edit';\"";
$saveOn = "class=\"btn_save\" onMouseover=\"this.className='btn_saveon';\" onMouseout=\"this.className='btn_save';\"";
$buildOn = "class=\"btn_build\" onMouseover=\"this.className='btn_buildon';\" onMouseout=\"this.className='btn_build';\"";
$deleteOn = "class=\"btn_delete\" onMouseover=\"this.className='btn_deleteon';\" onMouseout=\"this.className='btn_delete';\"";


$THIS_DISPLAY .= "<form method=\"post\" name=\"enewsletter\" action=\"enewsletter/create_campaign.php\" style=\"margin: 0;\">\n";

$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\" style=\"margin-top: 0;\">\n";
$THIS_DISPLAY .= " <tr>\n";
//$THIS_DISPLAY .= "  <td width=\"100%\">&nbsp;</td>\n";
$THIS_DISPLAY .= "  <td align=\"left\" width=\"245px;\" class=\"text\">\n";
$THIS_DISPLAY .= "   <button type=\"button\" onClick=\"document.enewsletter.submit()\" class=\"greenButton\"><span><span>".lang("Create New Campaign")." &gt;&gt;</span></span></button>\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= "  <td align=\"left\" class=\"text\">\n";
$THIS_DISPLAY .= "   <button type=\"button\" class=\"blueButton\" onclick=\"document.location.href='enewsletter/preferences.php';\"><span><span>".lang("eNewsletter Preferences")."</span></span></button>\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n\n";



$result = mysql_query("SELECT PRIKEY, SEND_DATE, CAMPAIGN_NAME, TABLE_NAME, NUM_HTML_CNT, NUM_TEXT_CNT, CLICK_THRU_CNT, STATUS FROM campaign_manager ORDER BY STATUS ASC, SEND_DATE DESC, CAMPAIGN_NAME ASC");

if(mysql_num_rows($result) > 0){
	
	$THIS_DISPLAY .= "<TABLE BORDER=1 CELLPADDING=3 CELLSPACING=0 ALIGN=CENTER WIDTH=99% style='border-color: black;'>\n";
	
	// ----------------------------------------------------------------
	// Setup Header For Campaign Manager Interface [ROW 1]
	// ----------------------------------------------------------------
	
	$THIS_DISPLAY .= "<TR>\n";
	
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=text BGCOLOR=#818181><FONT COLOR=WHITE><B>\n";
	$THIS_DISPLAY .= lang("Delete");
	$THIS_DISPLAY .= "</B></FONT></TD>\n";
	
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=text BGCOLOR=#818181><FONT COLOR=WHITE><B>\n";
	$THIS_DISPLAY .= lang("Sent Date");
	$THIS_DISPLAY .= "</B></FONT></TD>\n";
	$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE CLASS=text BGCOLOR=#818181><FONT COLOR=WHITE><B>\n";
	$THIS_DISPLAY .= lang("Campaign Name");
	$THIS_DISPLAY .= "</B></FONT></TD>\n";
	$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE CLASS=text BGCOLOR=#818181><FONT COLOR=WHITE><B>\n";
	$THIS_DISPLAY .= lang("Data Table");
	$THIS_DISPLAY .= "</B></FONT></TD>\n";
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=text BGCOLOR=#818181><FONT COLOR=WHITE><B>\n";
	$THIS_DISPLAY .= lang("Recipients");
	$THIS_DISPLAY .= "</B></FONT></TD>\n";
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=text BGCOLOR=#818181><FONT COLOR=WHITE><B>\n";
	$THIS_DISPLAY .= lang("Views");
	$THIS_DISPLAY .= "</B></FONT></TD>\n";
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=text BGCOLOR=#818181><FONT COLOR=WHITE><B>\n";
	$THIS_DISPLAY .= lang("Status");
	$THIS_DISPLAY .= "</B></FONT></TD>\n";
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=text BGCOLOR=#818181><FONT COLOR=WHITE><B>\n";
	$THIS_DISPLAY .= lang("View");
	$THIS_DISPLAY .= "</B></FONT></TD>\n";
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=text BGCOLOR=#818181><FONT COLOR=WHITE><B>\n";
	$THIS_DISPLAY .= lang("Action");
	$THIS_DISPLAY .= "</B></FONT></TD>\n";
	
	$THIS_DISPLAY .= "</TR>\n";
	
	// ----------------------------------------------------------------
	// List User Campaigns sorted by SEND_DATE [ROW 2-x]
	// ----------------------------------------------------------------
	
	
	while ($row = mysql_fetch_array($result)) {
	
		$recepients = $row[NUM_HTML_CNT];
		$total_send = $row[NUM_HTML_CNT] + $row[NUM_TEXT_CNT];
		$this_camp = addslashes($row[CAMPAIGN_NAME]);
	
		if ($row[STATUS] == "Pending") {
			$this_status = "<FONT COLOR=MAROON><B>".lang("Pending")."</B></FONT>";
		} else {
			$this_status = "<FONT COLOR=DARKGREEN><B>".lang("SENT")."</B></FONT>";
		}
	
		$del_disable = "";
	
		if ($ROW_COLOR == "WHITE") { $ROW_COLOR = "#EFEFEF"; } else { $ROW_COLOR = "WHITE"; }
		if ($row[STATUS] == "Pending") { $ROW_COLOR = "oldlace"; }
	
		$THIS_DISPLAY .= "<TR>\n";
	
		$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE CLASS=text BGCOLOR=$ROW_COLOR>\n";
		$THIS_DISPLAY .= "<button type=\"button\" $del_disable class=\"redButton\" ONCLICK=\"delete_camp('$this_camp','$row[PRIKEY]');\"><span><span>".lang("Delete")."</span></span></button>";
		$THIS_DISPLAY .= "</B></FONT></TD>\n";
	
		$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE CLASS=text BGCOLOR=$ROW_COLOR>\n";
		$THIS_DISPLAY .= $row[SEND_DATE];
		$THIS_DISPLAY .= "</B></FONT></TD>\n";
		$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE CLASS=text BGCOLOR=$ROW_COLOR style=\"color: #000099;\"><B>\n";
		$THIS_DISPLAY .= $row[CAMPAIGN_NAME];
		$THIS_DISPLAY .= "</B></FONT></TD>\n";
		$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE CLASS=text BGCOLOR=$ROW_COLOR>\n";
		$THIS_DISPLAY .= $row[TABLE_NAME];
		$THIS_DISPLAY .= "</B></FONT></TD>\n";
		$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=text BGCOLOR=$ROW_COLOR>\n";
		$THIS_DISPLAY .= "$recepients";
		$THIS_DISPLAY .= "</B></FONT></TD>\n";
		$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=text BGCOLOR=$ROW_COLOR>\n";
		$THIS_DISPLAY .= $row[CLICK_THRU_CNT];
		$THIS_DISPLAY .= "</B></FONT></TD>\n";
		$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=text BGCOLOR=$ROW_COLOR>\n";
	
		$THIS_DISPLAY .= "$this_status";
	
		$THIS_DISPLAY .= "</B></FONT></TD>\n";
		$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=text BGCOLOR=$ROW_COLOR>\n";
		$THIS_DISPLAY .= "<button type=\"button\" class=\"blueButton\" onClick=\"preview('$row[PRIKEY]');\"><span><span>".lang("View")."</span></span></button>";
		$THIS_DISPLAY .= "</B></FONT></TD>\n";
	
		$THIS_DISPLAY .= "</B></FONT></TD>\n";
		$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=text BGCOLOR=$ROW_COLOR>\n";
	
		if ($row[STATUS] == "Pending") {
			$THIS_DISPLAY .= "<button type=\"button\" class=\"greenButton\" onclick=\"confirm_send('$this_camp','$total_send','$row[PRIKEY]');\"><span><span>".lang("Send Now")."</span></span></button>";
		} else {
			$THIS_DISPLAY .= "<FONT COLOR=#999999>N/A</FONT>";
			// $THIS_DISPLAY .= "<INPUT TYPE=BUTTON class=FormLt1 STYLE='CURSOR: hand; font-family: Arial; font-size: 8pt; width: 85px;' VALUE=\"Details\">";
		}
	
		$THIS_DISPLAY .= "</B></FONT></TD>\n";
	
		$THIS_DISPLAY .= "</TR>\n";
	
	} // End While Loop
	
	// ----------------------------------------------------------------
	
	$THIS_DISPLAY .= "</TABLE>\n";

	$THIS_DISPLAY .= "<BR><BR><DIV ALIGN=CENTER><a href=\"http://$this_ip/pgm-manage_subscription.php?id=unsubscribe\">".lang("Manually Unsubscribe Email Addresses")."</a></DIV>\n";
} else {
	$THIS_DISPLAY .= "<BR><BR><br/><br/><DIV ALIGN=left>&nbsp;&nbsp;<a target=\"_BLANK\" href=\"http://$this_ip/pgm-manage_subscription.php?id=unsubscribe\">".lang("Manually Unsubscribe Email Addresses")."</a></DIV>\n";
}


$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n";

$THIS_DISPLAY .= "</FORM>\n\n";
####################################################################

echo $THIS_DISPLAY;

####################################################################


# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Manage your eNewsletter campaigns.");

$module = new smt_module($module_html);
$module->meta_title = lang("eNewsletter");
$module->add_breadcrumb_link(lang("eNewsletter"), "program/modules/mods_full/enewsletter.php");
$module->icon_img = "program/includes/images/newsletter-icon-med.png";

$module->heading_text = lang("eNewsletter");
$module->description_text = $instructions;
$module->container_css = "margin: 0;padding: 0;";
$module->good_to_go();
?>
