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
error_reporting(E_PARSE);

//# Try to get session back
//include("../../../includes/config.php");
//include("../../../includes/db_connect.php");
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

$getseoopt = mysql_query("select data from smt_userdata where plugin='seolink' and fieldname='pref'");
while($seo_optionq = mysql_fetch_assoc($getseoopt)){
     $seo_option = $seo_optionq['data'];
}
   
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Create Link</title>

<link rel="stylesheet" href="soholaunch.css">
<script language="javascript" type="text/javascript" src="../tiny_mce/tiny_mce_popup.js"></script>
<script language="javascript" type="text/javascript" src="../tiny_mce/utils/form_utils.js"></script>
<script language="javascript" type="text/javascript" src="../tiny_mce/plugins/advlink/jscripts/functions.js"></script>
<base target="_self" />
<style>
	SELECT {font:7pt Verdana;background:#FFFFFF;}
	.txtbox {font:7pt Verdana;background:#FFFFFF}
	.toolbar {margin-bottom:3pt;height:28;overflow:hidden;background:white;border:0px none solid}
	.mode LABEL {font:8pt Arial}
	.mode .current {font:bold 8pt Arial;color:darkblue}
	.heading {color:navy;background:#FFFFFF}
	.tblEdit { BORDER-RIGHT: black 1px dashed; BORDER-TOP: black 1px dashed; BORDER-LEFT: black 1px dashed; BORDER-BOTTOM: black 1px dashed; }
</style>

<script>

	function doLink() {

				//var inPage = oSelLink.options(oSelLink.selectedIndex).value;
            var disOne = oSelLink.selectedIndex;
         	var inPage = eval("oSelLink.options["+disOne+"].value");
         	var inAnchor = anchorlistcontainer.value;
				var customPage = externalLink.value;
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
				} else if(inAnchor != "#" && inAnchor != "") {
				   var str = inAnchor;
				} else {
					if (inPage != "NONE") {
<?php
if($seo_option == 'yes'){
	echo '					var str = "http://<? echo $this_ip; ?>/"+inPage;'."\n";	
} else {
	echo '					var str = "http://<? echo $this_ip; ?>/index.php?pr="+inPage;'."\n";	
}


?>
						
					}
				}
            //alert(str)

				if (emailLink != "" && emailLink != "mailto:") {
					var str = emailLink;
				}


				if (str != "null") {
				   
               //window.opener.setLink(str);
               
            	var inst = tinyMCE.getInstanceById(tinyMCE.getWindowArg('editor_id'));
            	var elm = inst.getFocusElement();
            
            	elm = tinyMCE.getParentElement(elm, "a");
            
            	tinyMCEPopup.execCommand("mceBeginUndoLevel");
            
            	// Create new anchor elements
            	if (elm == null) {
            		if (tinyMCE.isSafari)
            			tinyMCEPopup.execCommand("mceInsertContent", false, '<a href="'+str+'">' + inst.selection.getSelectedHTML() + '</a>');
            		else
            			tinyMCEPopup.execCommand("createlink", false, str);
            
            		var elementArray = tinyMCE.getElementsByAttributeValue(inst.getBody(), "a", "href", str);
            		for (var i=0; i<elementArray.length; i++) {
            			var elm = elementArray[i];
            
            			// Move cursor behind the new anchor
            			if (tinyMCE.isGecko) {
            				var sp = inst.getDoc().createTextNode(" ");
            
            				if (elm.nextSibling)
            					elm.parentNode.insertBefore(sp, elm.nextSibling);
            				else
            					elm.parentNode.appendChild(sp);
            
            				// Set range after link
            				var rng = inst.getDoc().createRange();
            				rng.setStartAfter(elm);
            				rng.setEndAfter(elm);
            
            				// Update selection
            				var sel = inst.getSel();
            				sel.removeAllRanges();
            				sel.addRange(rng);
            			}
            			elm.href=str;
            		}
            	} else{
            		elm.href=str;
            	}
               //alert('ok')
            	tinyMCE._setEventsEnabled(inst.getBody(), false);
            	tinyMCEPopup.execCommand("mceEndUndoLevel");
            	tinyMCEPopup.close();

				}else{ // End Kill Link
				   alert('Please select a site page from the drop down list, enter a link / anchor value or email value then click Ok.')
				}

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
			<TD align=left VALIGN=MIDDLE CLASS="text">
				Site Page:
		   </td>
			<TD align=left VALIGN=MIDDLE CLASS="text">
				<select ID="oSelLink" class="txtbox" style="width:300px;">
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

			</TR>
			
			<tr>
				<td class="column1"><label for="externalLink">(<I>or</I>)External Link:</label></td>
				<td align="left"><input type="text" id="externalLink" name="externalLink" size="20" class="txtbox" VALUE="http://" style="width:150px;"></td>
			</tr>
			
			<tr>
				<td class="column1"><label for="anchorlist">(<I>or</I>)Anchors:</label></td>
				<td align="left">
				   <input type="text" id="anchorlistcontainer" name="anchorlistcontainer" size="20" class="txtbox" VALUE="#" style="width:150px;">
				</td>
			</tr>
			<TR>
			<TD align=left VALIGN=MIDDLE CLASS="text">
				(<I>or</I>) Email: 
			</td>
			<TD align=left valign=middle class="text">
				<input type=text id=LINKEMAIL name=LINKEMAIL size=20 class="txtbox" VALUE="mailto:" style="width:150px;">
			</td></tr></table>

		</td></tr><tr><td align="center" valign="middle" nowrap>

			<input type=button value="Ok" style='width:75px;' onclick="doLink();" class="FormLt1">
			<input type=button value="Cancel" style='width:75px;' onclick="tinyMCEPopup.close();" class="FormLt1">

		</td>
		</tr></table>

</body>
</html>