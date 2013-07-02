<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
 
###############################################################################
## Soholaunch(R) Studio Edition
## Version 1.0
##      
## Author: 			Mike Johnston [mike.johnston@soholaunch.com]                 
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugzilla.soholaunch.com
###############################################################################

##############################################################################
## COPYRIGHT NOTICE                                                     
## Copyright 1999-2004 Soholaunch.com, Inc.  All Rights Reserved.       
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

if ( !function_exists("lang") ) {
   include("../../includes/product_gui.php");
}

if ($change == "") { $change = "site"; }

?>

<HTML>
<HEAD>
<TITLE>Web Studio Templates</TITLE>
<LINK rel="stylesheet" href="soholaunch.css" type="text/css">
<SCRIPT LANGUAGE=Javascript>
	// Center New Window on Screen
	// ----------------------------------------
//	var width = (screen.width/2) - (650/2);
//	var height = (screen.height/2) - (450/2);
//	window.moveTo(width,height);
//	window.focus();
	
	function change_category(v) {
		cat.CURRENT_CATEGORY.value = v;
		cat.submit();
	}
	
<?	
# Called as a new window (ie open pages) or a div-iframe setup (ie template manager)?
if ( $cMode == "win" ) {

   echo "   function choose_temp(v) {\n";
   echo "      if(document.all){\n";   
   echo "         window.opener.ctemplate.".$change.".options.value = v;\n";
   echo "         window.opener.show_template(v);\n";
   echo "         self.close();\n";
   echo "      }else{\n";
   echo "         window.opener.setDrop('".$change."',v);\n";
   echo "         window.opener.show_template(v);\n";
   echo "         window.close();\n";
   echo "      }\n";
   echo "   }\n";
   
   echo "   function closeMe(){\n";
   echo "      if(document.all){\n";
   echo "         self.close();\n";
   echo "      }else{\n";
   echo "         window.close();\n";
   echo "      }\n";
   echo "   }\n";  
   
} else {
   echo "   function choose_temp(v) {\n";
   echo "      parent.set_basetpl(v);\n";
   echo "      parent.show_template(v);\n";
   echo "      parent.closeit();\n";
   echo "   }\n";

   echo "   function closeMe(){\n";
   echo "      parent.closeit();\n";
   echo "   }\n";  
}
?>
   
	
</SCRIPT>
<STYLE>
	a.title:link { color: darkblue;font-size: 9pt;cursor: hand;font-weight:bold; text-decoration: none; }
	a.title:visited { color: darkblue;font-size: 9pt;cursor: hand;font-weight:bold; text-decoration: none; }
	a.title:hover { color: #336699; cursor: hand; font-size: 9pt;font-weight:bold; text-decoration: none; }
</STYLE>

</HEAD>
<BODY BGCOLOR="#FFFFFF" TEXT="#333333" LINK="#FF0000" VLINK="#FF0000" ALINK="#FF0000" LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0">

		<FORM NAME=cat METHOD=POST ACTION="pgm-browse_templates.php?cMode=<? echo $cMode; ?>">
		<INPUT TYPE=HIDDEN NAME=CURRENT_CATEGORY VALUE="">
		<INPUT TYPE=HIDDEN NAME=change VALUE="<? echo $change; ?>">
		</FORM>
		
		<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100%>
  		<TR> 
    	<TD ALIGN=CENTER VALIGN=TOP><table width="100%" border="0" cellspacing="0" cellpadding="4" align=center class=text>
        <tr> 
		<td align="left" valign="top" class="text" bgcolor="#336699" style="border-bottom: 1px solid black;"> 
		<B><font size=2 color=white>Browse Website Templates</font></B>
		</td><td align="right" valign="top" class="text" bgcolor="#336699" style="border-bottom: 1px solid black;">
		<input type=button class=mbutton value="<? echo lang("Close Window"); ?>" onclick="closeMe();">&nbsp;&nbsp;&nbsp;&nbsp;
		<?
		echo '<select name="pTemplate" onchange="change_category(this.value);" style="width: 150px; font-family: Tahoma; font-size: 8pt; background: #EFEFEF;">';
		$CATONLY = "on";
		include("pgm-read_templates.php");
		echo '</select>';
		?>				
		</td>
		</tr>
		<tr> 
		<td colspan=2 align="center" valign="middle" class="text">
		
		<?
		
		if ($CURRENT_CATEGORY == "") { $CURRENT_CATEGORY = $default_category; }
		
		$directory = "pages";	// Set Template Directory
		$handle = opendir("$directory");

		$a=0;

		echo "<table border=0 cellpadding=8 cellspacing=0 align=center width=75%>";
		$dCat = eregi_replace("_", " ", $CURRENT_CATEGORY);
		echo "<tr><td align=left valign=middle class=text colspan=2><font color=darkblue size=4>Browsing Category: <B>".ucwords($dCat)."</font></B><BR>";
		
		echo "<font color=#999999>Select a category to browse from the drop down box above. When your find a template you like, simply click the template to continue.</font></td>\n";
			
		while ($files = readdir($handle)) {
			if (strlen($files) > 2 && $files != "default" && eregi("$CURRENT_CATEGORY", $files)) {
				$a++;
				if ($a == 1) { echo "<tr>\n"; }
								
				echo "<td align=center valign=top class=text><a href=\"#\" class=title onclick=\"choose_temp('$files');\"><img src=\"pages/$files/screenshot.jpg\" WIDTH=200 HEIGHT=137 BORDER=0 ALIGN=ABSMIDDLE style=\"border: 1px solid black;\" hspace=2 vspace=2>";
				echo "<BR CLEAR=all>";
				
					$template_name = eregi_replace("_", " ", $files);
					$template_name = strtolower($template_name);
					
					$tmp = split("-", $template_name);
					$tmp_display = ucwords($tmp[1]);		
					if (!eregi("none", $tmp[2])) { $tmp_display .= "  (".ucwords($tmp[2]).")"; }
					
					echo "$tmp_display</a><BR>&nbsp;</td>\n";
					
				if ($a == 2) { echo "</tr>\n"; $a=0; }
			} // End If
		} // End While
		closedir($handle);
		
		if ($a != 0) {
			while($a != 2) {
				$a++;
				echo "<td class=text align=left valign=top>&nbsp;</td>"; 
			}
			echo "</tr>";
		}
		
		echo "</table>\n\n";		
		
		?>
		
		</td>
		</tr>
		</table></td>
		</tr>
		</table>

</BODY></HTML>