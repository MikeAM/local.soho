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
error_reporting(E_PARSE);

require_once("../../includes/product_gui.php");



if ( !isset($db_name) ) {

   # SESSION IS BAD: Pop up alert box with helpful instructions then close window
   $jsAlert = "WARNING: This feature does not appear to be working properly.\\n";
   $jsAlert .= "Typically, this problem is caused by your browser\\'s cookie settings.\\n\\n";
   $jsAlert .= "You may be able to correct it by following these steps...\\n";
   $jsAlert .= "1. Open up a new Microsoft Internet Explorer browser window.\\n";
   $jsAlert .= "2. From the top menu select \"Tools\" > \"Internet Options\".\\n";
   $jsAlert .= "3. Click on the \"Privacy\" tab.\\n";
   $jsAlert .= "4. Click on the \"Advanced\" button.\\n";
   $jsAlert .= "5. Check the box next to \"Override automatic cookie handling\".\\n";
   $jsAlert .= "6. Check to \"Accept\" both First and Third-party Cookies\".\\n";
   $jsAlert .= "7. Check to \"Always allow session cookies\".\\n";
   $jsAlert .= "8. Click \"OK\"\" and then \"OK\" again.\\n";
   $jsAlert .= "9. Close all browser windows.\\n";
   $jsAlert .= "10. Open a new Internet Explorer browser window.\\n";
   $jsAlert .= "11. Log back in to your website and try inserting a link again.\\n";

   echo "<script language=\"javascript\">\n";
   echo "alert('".$jsAlert."');\n";
   echo "window.close();\n";
   echo "</script>\n";

} else {

   # SESSION IS GOOD: Include core files an proceed with normal operation
   //include("../../includes/product_gui.php");
}

#######################################################
### PUT ALL SITE PAGES INTO MEMORY					###
#######################################################

$result = mysql_query("SELECT prikey, page_name, url_name, type, custom_menu, sub_pages, sub_page_of, password, main_menu, link, username, splash, bgcolor, title, description, template FROM site_pages ORDER BY page_name");
$numberRows = mysql_num_rows($result);
$a=0;

# Pop up alert box if now pages found (likely having problems passing session)
//if ( !$numberRows > 0 ) {
//   $jsAlert = "WARNING: This feature does not appear to be working properly.\\n";
//   $jsAlert .= "Typically, this problem is caused by your browser\\'s cookie settings.\\n\\n";
//   $jsAlert .= "You may be able to correct it by following these steps...\\n";
//   $jsAlert .= "1. Open up a new Microsoft Internet Explorer browser window.\\n";
//   $jsAlert .= "2. From the top menu select \"Tools\" > \"Internet Options\".\\n";
//   $jsAlert .= "3. Click on the \"Privacy\" tab.\\n";
//   $jsAlert .= "4. Click on the \"Advanced\" button.\\n";
//   $jsAlert .= "5. Check the box next to \"Override automatic cookie handling\".\\n";
//   $jsAlert .= "6. Check to \"Accept\" both First and Third-party Cookies\".\\n";
//   $jsAlert .= "7. Check to \"Always allow session cookies\".\\n";
//   $jsAlert .= "8. Click \"OK\"\" and then \"OK\" again.\\n";
//   $jsAlert .= "9. Close all browser windows.\\n";
//   $jsAlert .= "10. Open a new Internet Explorer browser window.\\n";
//   $jsAlert .= "11. Log back in to your website and try inserting a link again.\\n";
//   echo js_alert($jsAlert);
//}

while ($row = mysql_fetch_array ($result)) {
	$a++;
	$page_name[$a] = $row["page_name"];
	$page_type[$a] = $row["type"];
	$page_link[$a] = $row["link"];
}


?>

<HTML>
<HEAD>
<TITLE>Create Link</TITLE>

<link rel="stylesheet" href="soholaunch.css">

<STYLE>
	SELECT {font:7pt Verdana;background:#FFFFFF;}
	.txtbox {font:7pt Verdana;background:#FFFFFF}
	.toolbar {margin-bottom:3pt;height:28;overflow:hidden;background:white;border:0px none solid}
	.mode LABEL {font:8pt Arial}
	.mode .current {font:bold 8pt Arial;color:darkblue}
	.heading {color:navy;background:#FFFFFF}
	.tblEdit { BORDER-RIGHT: black 1px dashed; BORDER-TOP: black 1px dashed; BORDER-LEFT: black 1px dashed; BORDER-BOTTOM: black 1px dashed; }
</STYLE>

<SCRIPT>

	// Center New Window on Screen
	// ----------------------------------------
	var width = (screen.width/2) - (600/2);
	var height = (screen.height/2) - (40/2);
	window.moveTo(width,height);

	// Place Text Editor Window on top
	// ----------------------------------------
	window.focus();


	function doLink() {

				//var inPage = oSelLink.options(oSelLink.selectedIndex).value;
            var disOne = oSelLink.selectedIndex;
         	var inPage = eval("oSelLink.options["+disOne+"].value");
				var customPage = LINKCUSTOM.value;
				var emailLink = LINKEMAIL.value;
				var str = "null";
				var isCustom = inPage.search('http://')
				if(isCustom == -1){
				   isCustom = inPage.search('https://')
				}

				
				if (customPage != "" && customPage != "http://") {
					var str = customPage;
				} else if(isCustom >= 0) {
				   var str = inPage;
				} else {
					if (inPage != "NONE") {
<?php
$getseoopt = mysql_query("select data from smt_userdata where plugin='seolink' and fieldname='pref'");
while($seo_optionq = mysql_fetch_assoc($getseoopt)){
     $seo_option = $seo_optionq['data'];
}
if($seo_option == 'yes'){                      
	echo '							var str = "http://<? echo $this_ip; ?>/"+inPage+".php";'."\n";
} else {
	echo '							var str = "http://<? echo $this_ip; ?>/index.php?pr="+inPage;'."\n";
     $vsub_menu .= "     window.location = 'index.php?pr='+where+'';\n";   
}

?>

					}
				}

				if (emailLink != "" && emailLink != "mailto:") {
					var str = emailLink;
				}


				if (str != "null") {
				   window.top.opener.my_win.document.getElementById(window.top.opener.my_field).value = str;

            //window.opener.setLink(str);
            self.close();

				} // End Kill Link

	} // End Func

</SCRIPT>

</HEAD>
<BODY STYLE="margin:5pt;padding:0pt;cursor:default;background:#ECE9D8;">
	<TABLE class=toolbar CELLSPACING=0 CELLPADDING=0 STYLE="background:#ECE9D8;height:65px" WIDTH="100%">
		<TR>
		<td align="left" valign="top" class="text">
		<B>Enter the required information and click "Ok" to link your selection.<BR>
		Click the "Cancel" Button to close this window.</B><BR>
		</td></tr><tr>
		<TD ALIGN=RIGHT VALIGN=MIDDLE NOWRAP>
			<TABLE BORDER=0 CELLPADDING=3 CELLSPACING=0 ALIGN=CENTER>
			<TR>
			<TD ALIGN=LEFT VALIGN=MIDDLE CLASS="text">
				Site Page: <select ID="oSelLink" class="txtbox" style="width:300px;">
				<option value="NONE" selected>Browse...</option>

				<?php

				for ($x=1;$x<=$numberRows;$x++) {
					if (strtoupper($page_type[$x]) == "MAIN") {
						$thisPage = $page_name[$x];
						$linkto = eregi_replace(" ", "_", $thisPage);
						echo ("<option value=\"$linkto\">$thisPage</option>\n");
					}
					if (strtoupper($page_type[$x]) == "MENU") {
						$thisPage = $page_name[$x];
						$linkto = $page_link[$x];
						echo ("<option value=\"$linkto\">$thisPage</option>\n");
					}
				}

				?>

				</select>

			</TD>

			</TR><TR>
			<TD ALIGN=LEFT VALIGN=MIDDLE CLASS="text">
				(<I>or</I>) Link / Anchor: <input type=text id=LINKCUSTOM name=LINKCUSTOM size=20 class="txtbox" VALUE="http://" style="width:150px;">
				(<I>or</I>) Email: <input type=text id=LINKEMAIL name=LINKEMAIL size=20 class="txtbox" VALUE="mailto:" style="width:150px;">
			</TD></TR></TABLE>

		</td></tr><tr><td align="center" valign="middle" nowrap>

			<input type=button value="Ok" style='width:75px;' onclick="doLink();" class="FormLt1">
			<input type=button value="Cancel" style='width:75px;' onclick="self.close();" class="FormLt1">

		</td>
		</tr></table>

	<OBJECT id=dlgHelper CLASSID="clsid:3050f819-98b5-11cf-bb82-00aa00bdce0b" width="0px" height="0px"></OBJECT>

</body>