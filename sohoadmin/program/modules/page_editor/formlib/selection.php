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
## Copyright 1999-2003 Soholaunch.com, Inc.  All Rights Reserved.
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
error_reporting(0);

require_once('../../../includes/product_gui.php');


#######################################################
### READ CUSTOM USER FORMS & LIBRARY INTO MEMORY    ###
#######################################################

if (eregi("IIS", $SERVER_SOFTWARE)) {
	$da_root = addslashes($doc_root);
	$full_path = $da_root . "/sohoadmin/program/modules/page_editor/formlib/";
	$cdirectory = $da_root . "/media";
}else{
	$full_path = $doc_root . "/sohoadmin/program/modules/page_editor/formlib/";
	$cdirectory = "$doc_root/media";
}

if ($selkey == "Forms") {		// Page Editor indicates this is a "Forms Library Request"

	$FORM_OPTIONS = "<OPTION VALUE=\"\" STYLE='color: #999999;'>Available Forms:</OPTION>\n";
	//$cdirectory = "$doc_root/media";
	$handle = opendir("$cdirectory");
	while ($files = readdir($handle)) {
		if (strlen($files) > 2 && eregi("\.form", $files)) {
			$FORM_OPTIONS .= "<OPTION VALUE=\"$cdirectory/$files\">$files</OPTION>\n";
		}
	}
	closedir($handle);

	$directory = "forms";
	$handle = opendir("$directory");
	while ($files = readdir($handle)) {
		if (strlen($files) > 2 && eregi("\.form", $files)) {
			$FORM_OPTIONS .= "<OPTION VALUE=\"$full_path$directory/$files\">$files</OPTION>\n";
		}
	}
	closedir($handle);

}

if ($selkey == "Newsletter") {		// Page Editor indicates this is a "Newsletter Form" Request
	$FORM_OPTIONS = "<OPTION VALUE=\"\" STYLE='color: #999999;'>Newsletter Forms:</OPTION>\n";
	$directory = "newsletter";
	$handle = opendir("$directory");
	while ($files = readdir($handle)) {
		if (strlen($files) > 2 && eregi("\.form", $files)) {
			$FORM_OPTIONS .= "<OPTION VALUE=\"$full_path$directory/$files\">$files</OPTION>\n";
		}
	}
	closedir($handle);
}

#######################################################
### READ AVAILABLE TEXT FILES (RESPONSE INSERTS)    ###
#######################################################

	$TEXTFILE_OPTIONS = "<OPTION VALUE=\"DEFAULT2020202024452345.TXT\" STYLE='color: #999999;'>[ Default ]</OPTION>\n";

	$cdirectory = "$doc_root/media";
	$handle = opendir("$cdirectory");
	while ($files = readdir($handle)) {
		if (strlen($files) > 2 && eregi("\.txt", $files) && $files != "page_templates.txt") { //Bugzilla #73
			$TEXTFILE_OPTIONS .= "<OPTION VALUE=\"$cdirectory/$files\">$files</OPTION>\n";
		}
	}
	closedir($handle);

##########################################################
### READ ALL SITE PAGES INTO VAR FOR REDIRECT OPTION   ###
##########################################################

$SITEPAGES_OPTIONS = '';

//	$SITEPAGES_OPTIONS = "<OPTION VALUE=\"".startpage(false)."\">".startpage()."</OPTION>\n";

	$result = mysql_query("SELECT page_name, link FROM site_pages WHERE type = 'Main' ORDER BY page_name");
	while ($row = mysql_fetch_array ($result)) {
		$SITEPAGES_OPTIONS .= "<OPTION VALUE=\"".$row['link']."\">".$row['page_name']."</OPTION>\n";
	}

##########################################################

?>

<script language=javascript>






function form_builder() {
	parent.window.location='builder/form_build.php?dropArea=<? echo $dropArea; ?>&selkey=Forms&<?=SID?>';
}

function edit_form() {
		var oVar = formselect.availforms.options(formselect.availforms.selectedIndex).value;
		if (oVar != "") {
			parent.window.location='builder/form_add.php?lookat='+oVar+'&dropArea=<? echo $dropArea; ?>&selkey=Forms&<?=SID?>';
		} else {
			alert('Please select a form from the drop down list first.');
		}
}



</script>

<form name="formselect">

<table border=0 cellpadding=2 cellspacing=0 width=100% height=100%>
<TR>
      <td align=center valign=top class="text">
        <!-- START FORM SELECTION CONTENT -->
        <TABLE BORDER="0" CELLSPACING="0" CELLPADDING="8" ALIGN=CENTER WIDTH=100%>
          <TR>
            <TD ALIGN=LEFT VALIGN=TOP CLASS=text width=50%><FONT COLOR=RED><B>*</B></FONT><STRONG>Choose the
              form you wish to use on this page</STRONG>:<BR>

			   <DIV ALIGN=CENTER STYLE='border: 1px solid BLACK;padding: 5px; background: #EFEFEF;'>

					  <SELECT id="availforms" NAME="availforms" STYLE='font-family: Arial; font-size: 8pt; width: 250px;'>
					  <? echo $FORM_OPTIONS; ?>
					  </SELECT>
					  <!-- <BR><BR>
					  <input type="button" class="FormLt1" value="Add New Fields" onclick="edit_form(this.value);">
					  &nbsp;&nbsp;&nbsp; -->
					  <BR><BR>
					  <button type="button" class="blueButton" onclick="preview();"><span><span>Preview Selected Form</span></span></button>

			   </DIV>

				<BR>

  			  <?

				if ($selkey == "Forms") {

					echo '<FONT COLOR=RED><B>*</B></FONT><STRONG>When your site visitor submits this form, what do you wish
              				to do with the data?</STRONG><BR><BR>
							a. Email the data to Email Address:<BR>
						  <INPUT TYPE="text" id="emailaddr" NAME="emailaddr" VALUE="'.$getSpec[df_email].'" STYLE="font-family: Arial; font-size: 8pt; width: 250px;">
						  <BR><FONT COLOR=MAROON><B>AND/OR</B></FONT><BR>';

				} else {

					echo '<INPUT TYPE=HIDDEN id="emailaddr" NAME="emailaddr" VALUE="NEWSLETTER_SIGNUP_PROCESS">';

					echo '<FONT COLOR=RED><B>*</B></FONT><STRONG>When this form is submitted it will create a User Data Table. What name would you
						like to assign this table?</STRONG><BR><BR>';

				}


			  if (!eregi("lite", $version)) {

			  		if ($selkey == "Newsletter") { $def_val = "my_newsletter"; $char_sel = "a"; $onoff = "none"; } else { $def_val = ""; $char_sel = "b"; $onoff = ""; }

				  echo $char_sel.'. Create a user data table called:<BR>
				  <INPUT TYPE="text" id="savedbtable" NAME="savedbtable" value="'.$def_val.'" maxlength=30 STYLE="font-family: Arial; font-size: 8pt; width: 250px;">';

			  } else {

			  		echo '<INPUT TYPE=HIDDEN id="savedbtable" NAME="savedbtable" VALUE="">';

			  }

			  ?>

			  <!-- End Write Form to DataTable Option -->

              <BR>
              <BR>
              <FONT COLOR=RED><B>*</B></FONT><STRONG>Once the form has been processed,
              how do you wish to route your site visitor?</STRONG><BR>
              <INPUT TYPE="radio" id="closewinY" NAME="closewin" VALUE="yes">
              Close the browser window and exit<BR>
              <INPUT TYPE="radio" id="closewinN" NAME="closewin" VALUE="" CHECKED>
              Redirect visitor to page:<br>
			  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<SELECT id="pagego" NAME="pagego" STYLE='font-family: Arial; font-size: 8pt; width: 225px;'>
			  	<? echo $SITEPAGES_OPTIONS; ?>
			  </SELECT>
			  <BR>
            </TD>
            <TD ALIGN=LEFT VALIGN=TOP CLASS=text WIDTH=50%><DIV STYLE='DISPLAY: <? echo $onoff; ?>'>

				<B>Auto-Email
              to site visitor setup:</B><BR><DIV STYLE='BORDER: 1px inset black; background: #EFEFEF; padding: 5px;'>
              a. Reply to Email Address: <FONT COLOR="#999999">(Who auto-email
              comes from)</FONT><BR>
              <INPUT TYPE="text" id="emailfrom" NAME="emailfrom" VALUE="noreply@<? echo eregi_replace('^www\.', '', $_SESSION['this_ip']); ?>" STYLE='font-family: Arial; font-size: 8pt; width: 250px; color: black;'>
              <BR>
              b. Subject Line of Auto-Email:<BR>
              <INPUT TYPE="text" id="subjectline" NAME="subjectline" VALUE="<? echo lang("Thank you for your inquiry"); ?>" STYLE='font-family: Arial; font-size: 8pt; width: 250px; color: black;'>
              <BR>
              c. Content of Auto-Email:<BR>
			  <SELECT id="responsefile" NAME="responsefile" STYLE='font-family: Arial; font-size: 8pt; width: 250px; color: black;'>
			  <? echo $TEXTFILE_OPTIONS; ?>
			  </SELECT>
              </DIV><BR>


			  <BR><BR><BR>

			  <!--- <DIV ALIGN=CENTER> -->
			  <input type="hidden" id="oldFormVals" name="oldFormVals" value="" >
			  <!--- </DIV>

			  <BR><BR><BR> -->

			  </DIV>

			  <DIV ALIGN=CENTER>
			  	<button type="button" class="blueButton" onclick="placeobject();"><span><span>Put Form on Page</span></span></button>
	   			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<button type="button" class="grayButton" onclick="$('form_display').style.display='none';checkRow(ColRowID);"><span><span>Cancel</span></span></button>
			  </DIV>

            </TD>
          </TR>
        </TABLE>
        <!-- END FORM SELECTION CONTENT -->
      </TD>
</TR>
</TABLE>

</FORM>
