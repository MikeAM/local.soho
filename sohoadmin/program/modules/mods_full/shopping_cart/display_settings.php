<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.6b
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


error_reporting(0);
session_start();

include_once("../../../includes/product_gui.php");
//echo testArray($_POST); exit;
###################################################
### READ NETSCAPE COLOR TABLE CSV DATA INTO VAR ###
###################################################

$filename = "shared/color_table.dat";
$file = fopen("$filename", "r");
   $data = fread($file,filesize($filename));
fclose($file);

$tData = split("\n", $data);
$tLoop = count($tData);

$numcolors = 0;

for ($x=0;$x<=$tLoop;$x++) {
   $temp = split(",", $tData[$x]);
   if ($temp[0] != "") {
      $numcolors++;
      $color_name[$numcolors] = $temp[0];
      $color_hex[$numcolors] = '#'.eregi_replace("\r", '', $temp[1]);
   }
}

# Restore misc prefs
$cartprefs = new userdata("cart");

# DEFAULTS: 650px width for fullsize, 95x95 for thumbnails, Add to Cart >> for btn text
if ( $cartprefs->get("fullimg_maxwidth") == "" ) { $cartprefs->set("fullimg_maxwidth", "650"); }
if ( $cartprefs->get("thumb_width") == "" ) { $cartprefs->set("thumb_width", "95"); }
if ( $cartprefs->get("custom_addcartbutton") == "" ) { $cartprefs->set("custom_addcartbutton", "Add to Cart &gt;&gt;"); }

#######################################################
### PERFORM SAVE ACTION : UPDATE DATA TABLE TO REFLECT
### CHANGES MADE BY USER
#######################################################

$update_complete = 0;

if ($ACTION == "updatedisplay") {

   # Update prefs
   foreach ( $_POST['mprefs'] as $pref=>$setting ) {
      $cartprefs->set($pref, $setting);
   }

//   echo "<div style=\"height: 400px;overflow: auto;\">".testArray($_POST['mprefs'])."</div>"; exit;

   // Split up local country array
   //--------------------------------------------------
   $local_nats = "";
   foreach ( $local_country as $localNat ) {
      if ( !eregi("No Default Country", $localNat) ) {
         $local_nats .= $localNat . ":n:";
      } else {
         $local_nats = "No Default Country";
      }
   }

   // Build required field list
   //-------------------------------------------------
   foreach ( $reqfields as $checked ) {
      $display_required .= $checked;
   }

   // Format hex color values
   //--------------------------------------------------
//	$display_headerbg = str_replace("#", "", $display_headerbg);
//	$display_headertxt = str_replace("#", "", $display_headertxt);
//	$display_cartbg = str_replace("#", "", $display_cartbg);
//	$display_carttxt = str_replace("#", "", $display_carttxt);
//	$_POST['css']['table_bgcolor']=str_replace("#", "", $_POST['css']['table_bgcolor']);
//	$_POST['css']['table_textcolor']=str_replace("#", "", $_POST['css']['table_textcolor']);
	# Rebuild css array
	$cartcss = serialize($_POST['css']);
	mysql_query("UPDATE cart_options SET

			DISPLAY_HEADERBG = '$display_headerbg',
			DISPLAY_HEADERTXT = '$display_headertxt',
			DISPLAY_CARTBG = '$display_cartbg',
			DISPLAY_CARTTXT = '$display_carttxt',
			DISPLAY_WELCOME = '$display_welcome',
			DISPLAY_RESULTS = '$display_results',
			DISPLAY_COLPLACEMENT = '$display_colplacement',
			DISPLAY_SEARCH = '$display_search',
			DISPLAY_CATEGORIES = '$display_categories',
			DISPLAY_COMMENTS = '$display_comments',
			DISPLAY_EMAILFRIEND = '$display_emailfriend',
			DISPLAY_USERBUTTON = '$display_userbutton',
			DISPLAY_ADDCARTBUTTON = '$display_addcartbutton',
			DISPLAY_RESULTSORT = '$display_resultsort',
			DISPLAY_LOGINBUTTON = '$display_loginbutton',
			DISPLAY_REMEMBERME = '$display_rememberme',
			DISPLAY_STATE = '$display_state',
			DISPLAY_ZIP = '$display_zip',
			DISPLAY_REQUIRED = '$display_required',
			LOCAL_COUNTRY = '$local_nats',
			CSS = '$cartcss',
			GOTO_CHECKOUT = '$goto_checkout'

			") OR DIE ('Could Not Update Data because '.mysql_error());

	$update_complete = 1;

}

#######################################################
### READ DATABASED OPTIONS INTO MEMORY NOW
#######################################################
$result = mysql_query("SELECT * FROM cart_options");
$DISPLAY = mysql_fetch_array($result);


#######################################################
### GET COUNTRY DATA FROM FLAT FILE		             ###
#######################################################
$filename = "shared/countries.dat";
$file = fopen("$filename", "r") or DIE("Error: Could not open country data (shared/contries.dat).");
	$tmp_data = fread($file,filesize($filename));
fclose($file);

$natDat = split("\n", $tmp_data);
$numNats = count($natDat) + 1;
$natNam[0] = "No Default Country";

// natDat is T.M.I (for now) format for proper display and usage
//-----------------------------------------------------------------
for ($c=0;$c<=$numNats;$c++) {
   $v = $c + 1;
   $tmpSplt = split("::", $natDat[$c]);
   $natNam[$v] = "$tmpSplt[0] - $tmpSplt[1]";
   $natNam[$v] = strtoupper($natNam[$v]);
}


# Start buffering output
ob_start();
?>


<script language="JavaScript">
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

<?

if ($update_complete == 1) {

	echo ("alert('Your display settings have been updated.');\n");

}

?>

show_hide_layer('addCartMenu?header','','hide');
show_hide_layer('blankLayer?header','','hide');
show_hide_layer('linkLayer?header','','hide');
show_hide_layer('newsletterLayer?header','','hide');
show_hide_layer('cartMenu?header','','show');
show_hide_layer('menuLayer?header','','hide');
show_hide_layer('editCartMenu?header','','hide');


// Setup all routines for dynamically changing "Search Column" color scheme
// ------------------------------------------------------------------------

// Use this one for all new stuff, phase old method out
function set_cartcss(field_id, newval) {
   // Populate textbox with passed value
	document.getElementById(field_id).value = newval;

	var field_id_d = field_id+'_displ';
	document.getElementById(field_id_d).style.background=newval;

//	// Set color of text field to selected color for visual identification
//	if ( newval != 'transparent' ) {
//	   document.getElementById(field_id).style.color = newval;
//	} else {
//	   document.getElementById(field_id).style.color = newval;
//	}

   // Select new value from dropdown, if there's an option for it
   ddval = document.getElementById(field_id+'-dd');
   ddval.value = newval;

   // Custom option selected?
   if ( (ddval.value !== newval) || newval == 'custom' ) {
      ddval.value = 'custom';
      //document.getElementById(field_id).value = '#';
      //document.getElementById(field_id).disabled = false;
   } else {
      //document.getElementById(field_id).disabled = true;
   }
}

// Update search column preview to show new color setting
function preview_cartcss(thingid, cssprop, newval) {
   thing = document.getElementById(thingid);
   eval("thing.style."+cssprop+" = '"+newval+"'");
   //alert('['+cssprop+'] = ('+eval("thing.style."+cssprop)+')');
}

function set_headerbg(color) {
	var fullcolor = color;

	var field_id_d = 'display_headerbg_displ';
	document.getElementById(field_id_d).style.background=fullcolor;
	
	document.displaysettings.display_headerbg.value = fullcolor;
	document.getElementById('header1').style.background = fullcolor;
	document.getElementById('header2').style.background = fullcolor;
	document.getElementById('header3').style.background = fullcolor;

	// Apply colors to text input fields for easier visual identification
	//document.getElementById('display_headerbg').style.color = fullcolor;
}

function set_headertxt(color) {
	var fullcolor = color;
	document.displaysettings.display_headertxt.value = fullcolor;
	
	var field_id_d = 'display_headertxt_displ';
	document.getElementById(field_id_d).style.background=fullcolor;
		
	document.getElementById('header1').style.color = fullcolor;
	document.getElementById('header2').style.color = fullcolor;
	document.getElementById('header3').style.color = fullcolor;

	// Apply colors to text input fields for easier visual identification
	//document.getElementById('display_headertxt').style.color = fullcolor;
}

function set_cartbg(color) {
	var fullcolor = color;
	

	var field_id_d = 'SELCLRCARTBG_displ';
	document.getElementById(field_id_d).style.background=fullcolor;
		
	document.displaysettings.display_cartbg.value = fullcolor;
	document.getElementById('cartarea').style.background = fullcolor;
	document.getElementById('cartarea').style.background = fullcolor;
	document.getElementById('cartarea').style.background = fullcolor;

	// Apply colors to text input fields for easier visual identification
//	document.getElementById('display_cartbg').style.color = fullcolor;
}

function set_carttxt(color) {
	var fullcolor = color;
	document.displaysettings.display_carttxt.value = fullcolor;
	
	var field_id_d = 'SELCLRCARTTXT_displ';
	document.getElementById(field_id_d).style.background=fullcolor;

	
	//document.getElementById('cartarea').style.color = fullcolor;
	//document.getElementById('cartarea').style.color = fullcolor;
	document.getElementById('cartarea').style.color = fullcolor;

	// Apply colors to text input fields for easier visual identification
	//document.getElementById('display_carttxt').style.color = fullcolor;
}

function help_login() {

	      var str = "The 'Client Login' button is used when you are using\n";
	var str = str + "security codes to restrict access to certain products.\n\n";
	var str = str + "Your clients (customers) will need to login with the\n";
	var str = str + "proper un/pw to have access to those products and this\n";
	var str = str + "is how they would do that.\n\n";
	var str = str + "Alternatively, they could login via the 'Secure Login'\n";
	var str = str + "button placed on a regular page as well.  Once logged in\n";
	var str = str + "at either point, the session will follow them throughout\nthe site.";

	alert(str);

}

function set_scheme(sel) {

	if (sel == "AM") {
		var one = "#FF0000";
		var two = "#FFFFFF";
		var three = "#0000FF";
		var four = "#FFFFFF";
	}

	if (sel == "CL") {
		var one = "#708090";
		var two = "#F5F5F5";
		var three = "#EFEFEF";
		var four = "#000000";
	}

	if (sel == "EA") {
		var one = "#8B4513";
		var two = "#FDF5E6";
		var three = "#F4A460";
		var four = "#8B4513";
	}

	if (sel == "NE") {
		var one = "#006400";
		var two = "#00FF00";
		var three = "#2E8B57";
		var four = "#90EE90";
	}

	if (sel == "SP") {
		var one = "#6495ED";
		var two = "#000000";
		var three = "#FFD700";
		var four = "#8B0000";
	}

	if (sel == "MO") {
		var one = "#800000";
		var two = "#FFFFFF";
		var three = "#FDF5E6";
		var four = "#000000";
	}

	if (sel != "NULL") {

		set_headerbg(one);
		document.displaysettings.SELCLRHEADBG.value = one;

		set_headertxt(two);
		document.displaysettings.SELCLRHEADTXT.value = two;

		set_cartbg(three);
		document.displaysettings.SELCLRCARTBG.value = three;

		set_carttxt(four);
		document.displaysettings.SELCLRCARTTXT.value = four;

	}


}
</script>

<style>
span.pref_label {
   display: block;
   margin-top: 6px;
}
#fullimg_maxwidth, #thumb_width, #thumb_height {
   width: 50px;
   font-family: Courier New, courier, mono;
   font-size: 11px;
}
span.unit {
   font-size: 90%;
   color: #595959;
}
#misc_prefs h3 {
   font-size: 11px;
   margin-bottom: 0;
}

.tfield_hex {
   font-weight: normal;
   color:black;
}
</style>


<?

// Check checkbox?
#-------------------------------------------------------
# Determine checked val based on current db setting
function chkBox($needle, $haystack, $return = "chk") {
   if ( eregi ($needle, $haystack) ) {
      $checked = " checked";
   } else {
      $checked = "";
   }
   return "value=\"".$needle."\"".$checked;
}


$THIS_DISPLAY = "\n\n";

$THIS_DISPLAY .= "<!-- Visual settings; colors; checkout forms; etc. -->\n\n";

$THIS_DISPLAY .= "<FORM NAME=displaysettings METHOD=POST ACTION=\"display_settings.php\">\n";
$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=\"ACTION\" VALUE=\"updatedisplay\">\n\n";

/*---------------------------------------------------------------------------------------------------------*
  _____        _
 / ____|      | |
| |      ___  | |  ___   _ __  ___
| |     / _ \ | | / _ \ | '__|/ __|
| |____| (_) || || (_) || |   \__ \
 \_____|\___/ |_| \___/ |_|   |___/

# Present Visual Display of 'Search Column' and Allow user to select color
# scheme of his/her choice.
/*---------------------------------------------------------------------------------------------------------*/
# Pre-build options for color dropdowns
$color_options = "";
for ( $i=1; $i<=$numcolors; $i++ ) {
   $color_options .= "<option value=\"".$color_hex[$i]."\" style=\"background-color: #".$color_hex[$i]."\">".$color_name[$i]."</option>\n";
}

# Parent table with left cell for column and right cell for color scheme options
$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"85%\" class=\"feature_sub\" style=\"margin-bottom: 15px;\">\n";
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td class=\"fsub_title\" colspan=\"2\">\n";
$THIS_DISPLAY .= "   <span class=\"hand\" onclick=\"document.location.href='display_settings.php';\">".lang("Shopping Cart Color Scheme")."</span>\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";

$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\" width=\"150\">\n";

/*---------------------------------------------------------------------------------*
 ___                      _       ___       _
/ __| ___  __ _  _ _  __ | |_    / __| ___ | | _  _  _ __   _ _
\__ \/ -_)/ _` || '_|/ _|| ' \  | (__ / _ \| || || || '  \ | ' \
|___/\___|\__,_||_|  \__||_||_|  \___|\___/|_| \_,_||_|_|_||_||_|

# Scale demo table to 150 width because this is the width displayed
# on the client side when in actual operation
/*---------------------------------------------------------------------------------*/
$THIS_DISPLAY .= "   <table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"150\" id=\"previewtable\" style=\"background-color: transparent;\">\n";
$THIS_DISPLAY .= "    <tr>\n";
$THIS_DISPLAY .= "     <td align=\"left\" valign=\"middle\" ID=\"header1\" style=\"padding: 5px; padding-bottom: 20px;\">\n";
$THIS_DISPLAY .= "      <font face=\"verdana\" size=2><b>".lang("Search Product")."</b></font><br/>\n";
$THIS_DISPLAY .= "      <input type=\"text\" class=\"text\" style='width: 140px; background-color: #fff;' name=\"DEMOVAL\" size=\"15\" MAXLENGTH=15 DISABLED>\n";
$THIS_DISPLAY .= "      <br/><input type=\"button\" value=\"".lang("Find Now")."\" class=\"FormLt1\">\n";
$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "    </tr>\n";

//# Empty spacer row
//$THIS_DISPLAY .= "    <tr>\n";
//$THIS_DISPLAY .= "     <td>&nbsp;</td>\n";
//$THIS_DISPLAY .= "    </tr>\n";

$THIS_DISPLAY .= "    <tr>\n";
$THIS_DISPLAY .= "     <td align=\"center\" valign=\"middle\" ID=\"header2\">\n";
$THIS_DISPLAY .= "      <font face=\"verdana\" size=2><b>".lang("Browse Categories")."</b></font><br/>\n";
$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "    </tr>\n";

$THIS_DISPLAY .= "    <tr>\n";
$THIS_DISPLAY .= "     <td align=\"left\" valign=\"middle\">\n";
$THIS_DISPLAY .= "      &nbsp;".lang("Category")." 1<br/>\n";
$THIS_DISPLAY .= "      &nbsp;".lang("Category")." 2<br/>\n";
$THIS_DISPLAY .= "      &nbsp;".lang("Category")." 3<br/>\n";
$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "    </tr>\n";

$THIS_DISPLAY .= "    <tr>\n";
$THIS_DISPLAY .= "     <td align=\"center\" valign=\"middle\" ID=\"header3\">\n";
$THIS_DISPLAY .= "      <font face=\"verdana\" size=2><b>".lang("Shopping Cart")."</b></font><br/>\n";
$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "    </tr>\n";

$THIS_DISPLAY .= "    <tr>\n";
$THIS_DISPLAY .= "     <td align=\"left\" valign=\"middle\" ID=\"cartarea\">\n";
$THIS_DISPLAY .= "      &nbsp;(1) ".lang("Product")."<br/><br/>\n";
$THIS_DISPLAY .= "      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".lang("Sub-Total").": $19.95<br/><br/>\n";
$THIS_DISPLAY .= "      <div align=\"center\"><input type=\"button\" value=\"".lang("Checkout Now")."\" class=\"FormLt1\"><br/>&nbsp;</div>\n";
$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "    </tr>\n";
$THIS_DISPLAY .= "   </table>\n\n";
$THIS_DISPLAY .= "  </td>\n";


/*---------------------------------------------------------------------------------*
 ___  _       _     _
| __|(_) ___ | | __| | ___
| _| | |/ -_)| |/ _` |(_-<
|_|  |_|\___||_|\__,_|/__/

# Form fields for altering various aspects of the cart's color scheme
/*---------------------------------------------------------------------------------*/
$THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\" width=\"90%\">\n";

####################################################################
### NORMAL (DEFAULT) TABLE CONTENT STYLES
####################################################################
$THIS_DISPLAY .= "   <table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"feature_sub\" width=\"100%\" style=\"margin-bottom: 10px;\">\n";
# Title row
$THIS_DISPLAY .= "    <tr>\n";
$THIS_DISPLAY .= "     <th colspan=\"2\" align=\"left\" valign=\"middle\">\n";
//$THIS_DISPLAY .= "      <a href=\"display_settings.php\" target=\"_self\">".lang("Normal table content")."</a>:\n";
$THIS_DISPLAY .= "      ".lang("Normal table content").":\n";
$THIS_DISPLAY .= "     </th>\n";
$THIS_DISPLAY .= "    </tr>\n";

# Table background color
$THIS_DISPLAY .= "    <tr>\n";
$THIS_DISPLAY .= "     <td align=\"right\" valign=\"middle\">\n";
$THIS_DISPLAY .= "      ".lang("Background color").":\n";
$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "     <td align=\"left\" valign=\"middle\">\n";
$THIS_DISPLAY .= "      <select id=\"table_bgcolor-dd\" class=\"text\" onchange=\"set_cartcss('table_bgcolor',this.value);preview_cartcss('previewtable', 'backgroundColor', this.value);\">\n";
$THIS_DISPLAY .= "       <option value=\"transparent\">transparent (default)</option>\n";
$THIS_DISPLAY .= "       ".$color_options;
$THIS_DISPLAY .= "       <option value=\"custom\" style=\"font-weight: bold;\">Custom</option>\n";
$THIS_DISPLAY .= "      </select>\n\n";
//$THIS_DISPLAY .= "     </td>\n";
//$THIS_DISPLAY .= "     <td align=\"left\" valign=\"middle\">\n";

$THIS_DISPLAY .= "     &nbsp;<div id=\"table_bgcolor_displ\" style=\"display:inline;margin-left:4px; margin-right:14px; width:18px;height:18px;border:1px solid #7F9DB9;\">&nbsp;&nbsp;&nbsp;&nbsp;</div>\n";

$THIS_DISPLAY .= "      CSS: <input type=\"text\" name=\"css[table_bgcolor]\" id=\"table_bgcolor\" value=\"\" class=\"tfield_hex\" style=\"width: 100px;\">\n\n\n";
$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "    </tr>\n\n";

# Text color
$THIS_DISPLAY .= "    <tr>\n";
$THIS_DISPLAY .= "     <td align=\"right\" valign=\"middle\">\n";
$THIS_DISPLAY .= "      ".lang("Text color").":\n";
$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "     <td align=\"left\" valign=\"middle\">\n";
$THIS_DISPLAY .= "      <select id=\"table_textcolor-dd\" onchange=\"set_cartcss('table_textcolor', this.value);preview_cartcss('previewtable', 'color', this.value);\" class=\"text\">\n";
//$THIS_DISPLAY .= "       <option value=\"transparent\">----- Default (black) -----</option>\n";
$THIS_DISPLAY .= "       ".$color_options;
$THIS_DISPLAY .= "       <option value=\"custom\" style=\"font-weight: bold;\">Custom</option>\n";
$THIS_DISPLAY .= "      </select>\n\n";
//$THIS_DISPLAY .= "     </td>\n";
//$THIS_DISPLAY .= "     <td align=\"right\" valign=\"middle\">\n";
$THIS_DISPLAY .= "     &nbsp;<div id=\"table_textcolor_displ\" style=\"display:inline;margin-left:4px; margin-right:14px; width:18px;height:18px;border:1px solid #7F9DB9;\">&nbsp;&nbsp;&nbsp;&nbsp;</div>\n";

$THIS_DISPLAY .= "      CSS: <input type=\"text\" name=\"css[table_textcolor]\" id=\"table_textcolor\" value=\"\" class=\"tfield_hex\" style=\"width: 100px;\">\n\n\n";
$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "    </tr>\n\n";
$THIS_DISPLAY .= "   </table>\n\n";


####################################################################
### HEADER COLORS
####################################################################
$THIS_DISPLAY .= "   <table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"feature_sub\" width=\"100%\">\n";

# Header Background table row
$THIS_DISPLAY .= "    <tr>\n";
$THIS_DISPLAY .= "     <td align=\"right\" valign=\"middle\">\n";
$THIS_DISPLAY .= "      ".lang("Header Background").":\n";
$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "     <td align=\"left\" valign=\"middle\">\n";
$THIS_DISPLAY .= "      <select name=\"SELCLRHEADBG\" class=\"text\" onchange=\"set_headerbg(this.value);\">\n";
$THIS_DISPLAY .= "       <option value=\"transparent\">transparent (default)</option>\n";
$THIS_DISPLAY .= "       ".$color_options;
$THIS_DISPLAY .= "      </select>\n\n";

$THIS_DISPLAY .= "     &nbsp;<div id=\"display_headerbg_displ\" style=\"display:inline;margin-left:4px; margin-right:14px; width:18px;height:18px;border:1px solid #7F9DB9;\">&nbsp;&nbsp;&nbsp;&nbsp;</div>\n";

$THIS_DISPLAY .= "      &nbsp;&nbsp;Hex: <input type=\"text\" size=\"7\" id=\"display_headerbg\" name=\"display_headerbg\" value=\"\" class=\"tfield_hex\">\n\n\n";
$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "    </tr>\n\n";

# Header Text table row
$THIS_DISPLAY .= "    <tr>\n";
$THIS_DISPLAY .= "     <td align=\"right\" valign=\"middle\">\n";
$THIS_DISPLAY .= "      ".lang("Header Text").":\n";
$THIS_DISPLAY .= "     </td>\n";

$THIS_DISPLAY .= "     <td align=\"left\" valign=\"middle\">\n";
$THIS_DISPLAY .= "      <select name=\"SELCLRHEADTXT\" id=\"SELCLRHEADTXT\" class=\"text\" ONCHANGE=\"set_headertxt(this.value);\">\n";
$THIS_DISPLAY .= "       ".$color_options;
$THIS_DISPLAY .= "      </select>\n\n";

$THIS_DISPLAY .= "     &nbsp;<div id=\"display_headertxt_displ\" style=\"display:inline;margin-left:4px; margin-right:14px; width:18px;height:18px;border:1px solid #7F9DB9;\">&nbsp;&nbsp;&nbsp;&nbsp;</div>\n";

$THIS_DISPLAY .= "      &nbsp;&nbsp;Hex: <input type=\"text\" size=\"7\" id=\"display_headertxt\" name=\"display_headertxt\" value=\"\" class=\"tfield_hex\">\n\n";
$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "    </tr>\n\n";

$THIS_DISPLAY .= "   </table>\n\n";

####################################################################
### SHOPPING CART DISPLAY COLORS
####################################################################

$THIS_DISPLAY .= "   <br/><br/>\n";

$THIS_DISPLAY .= "   <table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"feature_sub\" width=\"100%\">\n";

// Shopping Cart Background table row
// ------------------------------------------------------------------

$THIS_DISPLAY .= "   <tr>\n";
$THIS_DISPLAY .= "   <td align=\"right\" valign=\"middle\">\n";

$THIS_DISPLAY .= lang("Shopping Cart Background").":\n";

$THIS_DISPLAY .= "   </td><td align=\"left\" valign=\"middle\">\n";

$THIS_DISPLAY .= "   <select name=\"SELCLRCARTBG\" class=\"text\" ONCHANGE=\"set_cartbg(this.value);\">\n";
$THIS_DISPLAY .= "       <option value=\"transparent\">transparent (default)</option>\n";
$THIS_DISPLAY .= "       ".$color_options;
$THIS_DISPLAY .= "   </select>\n\n";

$THIS_DISPLAY .= "     &nbsp;<div id=\"SELCLRCARTBG_displ\" style=\"display:inline;margin-left:4px; margin-right:14px; width:18px;height:18px;border:1px solid #7F9DB9;\">&nbsp;&nbsp;&nbsp;&nbsp;</div>\n";

$THIS_DISPLAY .= "   &nbsp;&nbsp;Hex: <input type=\"text\" size=\"7\" name=\"display_cartbg\" id=\"display_cartbg\" value=\"\" class=\"tfield_hex\">\n\n\n";

$THIS_DISPLAY .= "   </td>\n";
$THIS_DISPLAY .= "   </tr>\n\n";

// Shopping Cart Text table row
// ------------------------------------------------------------------

$THIS_DISPLAY .= "   <tr>\n";
$THIS_DISPLAY .= "   <td align=\"right\" valign=\"middle\">\n";
$THIS_DISPLAY .= lang("Shopping Cart Text").":\n";

$THIS_DISPLAY .= "   </td><td align=\"left\" valign=\"middle\">\n";

$THIS_DISPLAY .= "   <select name=\"SELCLRCARTTXT\" class=\"text\" ONCHANGE=\"set_carttxt(this.value);\">\n";
$THIS_DISPLAY .= "       ".$color_options;
$THIS_DISPLAY .= "   </select>\n\n";

$THIS_DISPLAY .= "     &nbsp;<div id=\"SELCLRCARTTXT_displ\" style=\"display:inline;margin-left:4px; margin-right:14px; width:18px;height:18px;border:1px solid #7F9DB9;\">&nbsp;&nbsp;&nbsp;&nbsp;</div>\n";

$THIS_DISPLAY .= "   &nbsp;&nbsp;Hex: <input type=\"text\" size=\"7\" id=\"display_carttxt\" name=\"display_carttxt\" value=\"\" class=\"tfield_hex\">\n\n";
$THIS_DISPLAY .= "   </td>\n";
$THIS_DISPLAY .= "   </tr>\n\n";
$THIS_DISPLAY .= "   </table>\n\n";

####################################################################
### OPTION TO SELECT PREDEFINED COLOR SCHEMES
####################################################################

$THIS_DISPLAY .= "   <br/><br/>\n";

$THIS_DISPLAY .= "   <table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"feature_sub\" width=\"100%\">\n";

$THIS_DISPLAY .= "   <tr>\n";
$THIS_DISPLAY .= "   <td align=\"right\" valign=\"middle\">\n";

$THIS_DISPLAY .= lang("Or choose a pre-defined color scheme").":\n";

$THIS_DISPLAY .= "   </td><td align=\"left\" valign=\"middle\">\n";

$THIS_DISPLAY .= "   <select name=\"SCHEME\" class=\"text\" ONCHANGE=\"set_scheme(this.value);\">\n";

$THIS_DISPLAY .= "        <option value=\"NULL\">".lang("Choose Scheme")."... </option>\n";
$THIS_DISPLAY .= "        <option value=\"AM\">".lang("America")." </option>\n";
$THIS_DISPLAY .= "        <option value=\"CL\">".lang("Classic")." </option>\n";
$THIS_DISPLAY .= "        <option value=\"EA\">".lang("Earth")." </option>\n";
$THIS_DISPLAY .= "        <option value=\"MO\">".lang("Movies")." </option>\n";
$THIS_DISPLAY .= "        <option value=\"NE\">".lang("Neon Green")." </option>\n";
$THIS_DISPLAY .= "        <option value=\"SP\">".lang("Sports")." </option>\n";
$THIS_DISPLAY .= "      </select>\n\n";

$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "    </tr>\n\n";
$THIS_DISPLAY .= "   </table>\n\n";



// -----------------------------------------------------------------------
// Now set the submit button under the color selections
// -----------------------------------------------------------------------
$THIS_DISPLAY .= "   <br/><br/><div align=\"right\">";
$THIS_DISPLAY .= "   <button type=\"button\" class=\"greenButton\" onClick=\"document.displaysettings.submit();\"><span><span>".lang("Save Display Settings")."</span></span></button>";
$THIS_DISPLAY .= "   </div>\n\n";

$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n\n";




// ---------------------------------------------------------------------------
// Configure Options For Display First
// ---------------------------------------------------------------------------
$THIS_DISPLAY .= "\n\n<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"85%\" class=\"feature_sub\">\n";
$THIS_DISPLAY .= "<tr>\n\n";
$THIS_DISPLAY .= "<td align=\"left\" valign=\"top\" colspan=2 class=\"fsub_title\">\n";

$THIS_DISPLAY .= " ".lang("Shopping Cart Feature Options").":\n";

$THIS_DISPLAY .= "</td></tr><tr>\n\n";


$THIS_DISPLAY .= "<td align=\"left\" valign=\"top\" width=50%>\n";

// Welcome Header
// -----------------------------------------------------------------

$THIS_DISPLAY .= lang("Page Header:")." <i>".lang("Welcome To...")."</i><br/>\n";
$THIS_DISPLAY .= "<input class=\"text\" type=\"text\" name=\"display_welcome\" value=\"$DISPLAY[DISPLAY_WELCOME]\" style='width: 225px;'>\n";

$THIS_DISPLAY .= "<br/><br/>\n";

// Client Login Button
// -----------------------------------------------------------------

$THIS_DISPLAY .= lang("Show 'Client Login' Button in search column")."? <IMG SRC='help.gif' VSPACE=2 HSPACE=2 BORDER=0 ALIGN=ABSMIDDLE ONCLICK=\"help_login();\" STYLE='cursor: hand;'><BR>\n";
$THIS_DISPLAY .= "<select name=\"display_loginbutton\" style='width: 75px;' class=\"text\">\n";
$THIS_DISPLAY .= "  <option value=\"Y\">".lang("Yes")." </option>\n";
$THIS_DISPLAY .= "  <option value=\"N\">".lang("No")." </option>\n";
$THIS_DISPLAY .= "</select><br/><br/>\n\n";


# Email to Friend
$THIS_DISPLAY .= lang("Allow 'Email to Friend' feature")."?<br/>\n";
$THIS_DISPLAY .= "<select name=\"display_emailfriend\" style=\"width: 75px;\" class=\"text\">\n";
$THIS_DISPLAY .= " <option value=\"Y\">".lang("Yes")." </option>\n";
$THIS_DISPLAY .= " <option value=\"N\">".lang("No")." </option>\n";
$THIS_DISPLAY .= "</select>\n";
$THIS_DISPLAY .= "<br/><br/>\n\n";


# Allow 'Remember Me' feature?
$THIS_DISPLAY .= lang("Allow 'Remember Me' feature")."?<br/>\n";
$THIS_DISPLAY .= " <select name=\"display_rememberme\" style='width: 75px;' class=\"text\">\n";
$THIS_DISPLAY .= "  <option value=\"Y\">".lang("Yes")." </option>\n";
$THIS_DISPLAY .= "  <option value=\"N\">".lang("No")." </option>\n";
$THIS_DISPLAY .= " </select><br/><br/>\n\n";

# Go directly to checkout?
$THIS_DISPLAY .= lang("Go directly to checkout when 'Add to Cart' button is pressed on 'More Information' page")."?<br/>\n";
$THIS_DISPLAY .= " <select name=\"goto_checkout\" style=\"width: 280px;\" class=\"text\">\n";
$THIS_DISPLAY .= "  <option value=\"no\">".lang("No - Go to View/Edit Cart first (default).")." </option>\n";
$THIS_DISPLAY .= "  <option value=\"yes\">".lang("Yes - Go directly to checkout.")." </option>\n";
$THIS_DISPLAY .= "  <option value=\"skip\">".lang("Skip 'More Information' page entirely (advanced).")." </option>\n";
$THIS_DISPLAY .= " </select><br/><br/>\n\n";

$THIS_DISPLAY .= "</td>\n";

# Right-hand column
$THIS_DISPLAY .= "<td align=\"left\" valign=\"top\" width=50%>\n";


	// Display Search Box
	// -----------------------------------------------------------------

	$THIS_DISPLAY .= lang("Display Search Box")."?<BR>\n";
	$THIS_DISPLAY .= "<SELECT NAME=\"display_search\" STYLE='width: 75px;' CLASS=text>\n";
		$THIS_DISPLAY .= "     <OPTION VALUE=\"Y\">".lang("Yes")."</OPTION>\n";
		$THIS_DISPLAY .= "     <OPTION VALUE=\"N\">".lang("No")."</OPTION>\n";
	$THIS_DISPLAY .= "</SELECT><BR><BR>\n\n";

	// Search Box Placement
	// -----------------------------------------------------------------

	$THIS_DISPLAY .= lang("Place 'Search Column' on which side of page")."?<BR>\n";
	$THIS_DISPLAY .= "<SELECT NAME=\"display_colplacement\" STYLE='width: 75px;' CLASS=text>\n";
		$THIS_DISPLAY .= "     <OPTION VALUE=\"L\">".lang("Left")."</OPTION>\n";
		$THIS_DISPLAY .= "     <OPTION VALUE=\"R\">".lang("Right")."</OPTION>\n";
	$THIS_DISPLAY .= "</SELECT><BR><BR>\n\n";

	// Display Cateogries
	// -----------------------------------------------------------------

	$THIS_DISPLAY .= lang("Display 'text linked' categories")."?<BR>\n";
	$THIS_DISPLAY .= "<SELECT NAME=\"display_categories\" STYLE='width: 75px;' CLASS=text>\n";
		$THIS_DISPLAY .= "     <OPTION VALUE=\"Y\">".lang("Yes")."</OPTION>\n";
		$THIS_DISPLAY .= "     <OPTION VALUE=\"N\">".lang("No")."</OPTION>\n";
	$THIS_DISPLAY .= "</SELECT><BR><BR>\n\n";

	// Product Comments
	// -----------------------------------------------------------------

	$THIS_DISPLAY .= lang("Allow users to add product comments")."?<BR>\n";
	$THIS_DISPLAY .= "<select name=\"display_comments\" STYLE=\"width: 75px;\" class=\"text\">\n";
		$THIS_DISPLAY .= "     <option value=\"Y\">".lang("Yes")."</OPTION>\n";
		$THIS_DISPLAY .= "     <option value=\"N\">".lang("No")."</OPTION>\n";
	$THIS_DISPLAY .= "</select><br>\n";

	$THIS_DISPLAY .= "".lang("If using this option place an email address to verify submissions in the")."\n";
	$THIS_DISPLAY .= "<a href=\"business_information.php\">".lang("Business Information")."</a> ".lang("section").".\n\n";

$THIS_DISPLAY .= "</td>\n";
$THIS_DISPLAY .= "</tr>\n";
$THIS_DISPLAY .= "</table>\n\n";


/*---------------------------------------------------------------------------------------------------------*
 ___       _    _    ___         _    _
|_ _| _ _ | |_ | |  / _ \  _ __ | |_ (_) ___  _ _   ___
 | | | ' \|  _|| | | (_) || '_ \|  _|| |/ _ \| ' \ (_-<
|___||_||_|\__||_|  \___/ | .__/ \__||_|\___/|_||_|/__/
                          |_|
/*---------------------------------------------------------------------------------------------------------*/
$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"85%\" class=\"feature_sub\" style=\"margin: 15px 0;\">\n";
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td colspan=\"4\" align=\"left\" class=\"fsub_title\">\n";
$THIS_DISPLAY .= "   ".lang("International Options:")."<br></td>\n";
$THIS_DISPLAY .= " </tr>\n";

# Do not allow user to require zip/state fields if set to 'Do not display'
# Mantis 463
$THIS_DISPLAY .= "<script language=\"javascript\">\n";
$THIS_DISPLAY .= "function disable_require(selbox, chkbox) {\n";
$THIS_DISPLAY .= "   if ( document.getElementById(selbox).value == 'noshow' ) {\n";
$THIS_DISPLAY .= "      document.getElementById(chkbox).checked = false;\n";
$THIS_DISPLAY .= "      document.getElementById(chkbox).disabled = true;\n";
$THIS_DISPLAY .= "   } else {\n";
$THIS_DISPLAY .= "      document.getElementById(chkbox).disabled = false;\n";
$THIS_DISPLAY .= "   }\n";
$THIS_DISPLAY .= "}\n";
$THIS_DISPLAY .= "</script>\n";

# Display a field for Zip/Postal Code?
#---------------------------------------
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"left\" width=\"40%\">\n";
$THIS_DISPLAY .= "   ".lang("Display a field for Zip/Postal Code?")."\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= "  <td align=\"left\" class=\"text\" width=\"25%\">\n";
$THIS_DISPLAY .= "   <select id=\"display_zip\" name=\"display_zip\" style='font-family: Arial; font-size: 10px; width: 145px;' onChange=\"disable_require('display_zip', 'require_zip');\">\n";

# Build Zip/Postal Code options
#=============================================================
$state_options = lang("YES - Zip/Postal Code").";".lang("YES - Postal Code").";".lang("YES - Zip Code").";".lang("NO - Do not display");
$state_values = "zippostal;postal;zip;noshow";
$stateOpt = split(";", $state_options);
$stateVal = split(";", $state_values);
$numOpts = count($stateOpt);

for ($s=0; $s < $numOpts; $s++) {
   $sel = "";
	if ($stateVal[$s] == $DISPLAY['DISPLAY_ZIP']) { $sel = " selected"; }
	$THIS_DISPLAY .= "    <option value=\"".$stateVal[$s]."\"".$sel.">".$stateOpt[$s]."</option>\n";
}
$THIS_DISPLAY .= "   </select>\n";
$THIS_DISPLAY .= "  </td>\n";

# Required checkbox
#=======================
$THIS_DISPLAY .= "  <td valign=\"top\" align=\"left\" class=\"text\" width=\"5%\" style=\"border: 0px solid blue;\">\n";
if ( $DISPLAY['DISPLAY_ZIP'] == "noshow" ) { $disablezipStr = " disabled"; } else { $disablezipStr = ""; }
$THIS_DISPLAY .= "   <input type=\"checkbox\" id=\"require_zip\" name=\"reqfields[]\"".chkBox("~BZIPCODE~SZIPCODE~", $DISPLAY['DISPLAY_REQUIRED'])."".$disablezipStr.">\n";
$THIS_DISPLAY .= "  </td>\n";

# Required?
#-------------
$THIS_DISPLAY .= "  <td align=\"left\" width=\"30%\" style=\"border: 0px solid red;\">\n";
$THIS_DISPLAY .= "   <font color=\"#D70000;\">*</font>Require field?\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";

# Choose State/Province Display Type
#---------------------------------------
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"left\">\n";
$THIS_DISPLAY .= "   ".lang("Choose State/Province Display Type:")."</td>\n";
$THIS_DISPLAY .= "  <td align=\"left\" class=\"text\">\n";
$THIS_DISPLAY .= "   <select id=\"display_state\" name=\"display_state\" STYLE='font-family: Arial; font-size: 10px; width: 145px;' onChange=\"disable_require('display_state', 'require_state');\">\n";

# Select State/Province Display Type
#=======================================================
$state_options = lang("US States").";".lang("US States and Territories").";".lang("Canadian Provinces").";".lang("US and Canada").";".lang("Australian States").";".lang("Text Field").";".lang("Do Not Display");
$state_values = "usmenu;usterrmenu;canmenu;uscanmenu;kangaroo;tfield;noshow";
$stateOpt = split(";", $state_options);
$stateVal = split(";", $state_values);
$numOpts = count($stateOpt);

for ($s=0; $s < $numOpts; $s++) {
   $sel = "";
	if ($stateVal[$s] == $DISPLAY['DISPLAY_STATE']) { $sel = " selected"; }
	$THIS_DISPLAY .= "    <OPTION VALUE='".$stateVal[$s]."'".$sel.">".$stateOpt[$s]."</OPTION>\n";
}
$THIS_DISPLAY .= "   </select>\n";
$THIS_DISPLAY .= "  </td>\n";

# Required checkbox
#=======================
$THIS_DISPLAY .= "  <td valign=\"top\" align=\"left\" class=\"text\">\n";
$THIS_DISPLAY .= "   <input type=\"checkbox\" id=\"require_state\" name=\"reqfields[]\"".chkBox("~BSTATE~SSTATE~", $DISPLAY['DISPLAY_REQUIRED']).">\n";
$THIS_DISPLAY .= "  </td>\n";

# Required?
#-------------
$THIS_DISPLAY .= "  <td align=\"left\">\n";
$THIS_DISPLAY .= "   <font color=\"#D70000;\">*</font>Require field?\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";


// Specify Default Country
//-----------------------------------------------------------------
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=left valign='top'>\n";
$THIS_DISPLAY .= "   ".lang("Limit country options to only certain countries")."?\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= "  <td align=left valign='top' class='text' colspan=\"3\">\n";

$THIS_DISPLAY .= "   <select name=\"local_country[]\" size='5' STYLE='font-family: Arial; font-size: 10px; width: 275px;' multiple>\n";

//Get country list and select current
for ($n=0;$n < $numNats;$n++) {
	$sel = "";
	if ( eregi($natNam[$n], $DISPLAY['LOCAL_COUNTRY']) ) { $sel = "selected"; }
	if ( $natNam[$n] == "No Default Country" ) {
	   $option_text = "Show all countries. Do not limit. (default)";
	   $option_style = " style=\"border: 1px solid #ccc;border-style: solid none;\"";
	} else {
	   $option_style = ""; $option_text = $natNam[$n];
	}
	$THIS_DISPLAY .= "    <option value=\"".$natNam[$n]."\" ".$sel.$option_style.">".$option_text."</OPTION>\n";
}

$THIS_DISPLAY .= "   </select>\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td colspan=\"4\" class=\"text\" align=\"left\">\n";
$THIS_DISPLAY .= "   ".lang("By selecting one or more default countries customers will only be able to choose those countries for their billing and shipping addresses.")."\n";
$THIS_DISPLAY .= "   ".lang("This option is useful if you only ship to certain countries.")."\n";
$THIS_DISPLAY .= " </tr>\n";

$THIS_DISPLAY .= "</table>\n\n";

// -------------------------------------------------------------------------
// Allow user to define display for searches
// -------------------------------------------------------------------------

$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"85%\" class=\"feature_sub\" style=\"margin: 15px 0;\">\n";
$THIS_DISPLAY .= "<TR>\n\n";
$THIS_DISPLAY .= "<td colspan=2 align=left valign=\"top\" class=\"fsub_title\">\n";
$THIS_DISPLAY .= "  ".lang("Search Result Settings").":\n";

$THIS_DISPLAY .= "</TD></TR><TR>\n\n";


$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=TOP>\n";


	// 1. More Information link
	// -----------------------------------------------------------------
	$THIS_DISPLAY .= "   <span class=\"pref_label\">1. ".lang("More Information Link")."</span>\n";
	$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;";
	$THIS_DISPLAY .= "   <select id=\"more-info-link\" name=\"mprefs[more-info-link]\">\n";
	$THIS_DISPLAY .= "    <option value=\"default\">Auto (Default) - Displays only if detail page defined</option>\n";
	$THIS_DISPLAY .= "    <option value=\"on\">On - Displays always for all products</option>\n";
	$THIS_DISPLAY .= "    <option value=\"off\">Off - Never displays for any product</option>\n";
	$THIS_DISPLAY .= "   </select>\n";
	
	$THIS_DISPLAY .= "<BR><BR>\n";


	// 2. User-Defined Button (Links to More Info Page)
	// -----------------------------------------------------------------

	$THIS_DISPLAY .= "2. ".lang("User Defined Button:")." <BR>\n";
	$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;<INPUT CLASS=text TYPE=TEXT NAME=\"display_userbutton\" VALUE=\"$DISPLAY[DISPLAY_USERBUTTON]\" STYLE='width: 225px;'>\n";
	$THIS_DISPLAY .= "<BR>&nbsp;&nbsp;&nbsp;&nbsp;<FONT COLOR=#999999>".lang("This button links to the 'More Information' page.  Leaving this blank will not show the button at all.")."</FONT>\n";


	$THIS_DISPLAY .= "<BR><BR>\n";

	// 3. Let user decide to show "add cart" button on initial search
	// -----------------------------------------------------------------

	$THIS_DISPLAY .= "3. ".lang("Show 'Add to Cart' button under thumbnail images instead of 'Buy Now!' on initial searches")."? <BR>\n";
	$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;<SELECT id=\"display_addcartbutton\" name=\"display_addcartbutton\" STYLE='width: 75px;' class=\"text\" onchange=\"ifShow('display_addcartbutton', 'C', 'custom_addcartbutton');\">\n";
   $THIS_DISPLAY .= "     <OPTION VALUE=\"Y\">".lang("Yes")."</OPTION>\n";
   $THIS_DISPLAY .= "     <OPTION VALUE=\"N\">".lang("No")."</OPTION>\n";
   $THIS_DISPLAY .= "     <OPTION VALUE=\"C\">".lang("Custom")."</OPTION>\n";
	$THIS_DISPLAY .= "</SELECT>\n\n";

	# custom_addcartbutton - show if 'Custom' selected from add cart btn dropdown
	$THIS_DISPLAY .= "<input type=\"text\" id=\"custom_addcartbutton\" name=\"mprefs[custom_addcartbutton]\" value=\"".$cartprefs->get("custom_addcartbutton")."\" class=\"text\" style=\"margin-left: 15px;display: none;\">\n";


	$THIS_DISPLAY .= "<BR><BR>\n";

	// 4. Choose Search Results Sort Order
	// -----------------------------------------------------------------

	$THIS_DISPLAY .= "4. ".lang("How should initial searches sort data")."? <BR>\n";
	$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;<SELECT NAME=\"display_resultsort\" STYLE='width: 150px;' CLASS=text>\n";
		$THIS_DISPLAY .= "     <OPTION VALUE=\"PROD_SKU\">".lang("Sku Number")."</OPTION>\n";
		$THIS_DISPLAY .= "     <OPTION VALUE=\"PROD_CATNO\">".lang("Catalog Ref Number")."</OPTION>\n";
		$THIS_DISPLAY .= "     <OPTION VALUE=\"category\">".lang("Category and Sub-Category")."</OPTION>\n";		
		$THIS_DISPLAY .= "     <OPTION VALUE=\"PROD_NAME\">".lang("Product Name")."</OPTION>\n";
		$THIS_DISPLAY .= "     <OPTION VALUE=\"PROD_UNITPRICE\">".lang("Product Price")."</OPTION>\n";
		$THIS_DISPLAY .= "     <OPTION VALUE=\"PROD_SHIPB\">".lang("Shipping Variable (B)")."</OPTION>\n";
		$THIS_DISPLAY .= "     <OPTION VALUE=\"PROD_SHIPC\">".lang("Shipping Variable (C)")."</OPTION>\n";
	$THIS_DISPLAY .= "</SELECT>\n\n";

	$THIS_DISPLAY .= "<BR><BR>\n";

	// 5. Search Results
	// -----------------------------------------------------------------

	$THIS_DISPLAY .= "5. ".lang("Number of results to display on searches").":<BR>\n";
	$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;<SELECT NAME=\"display_results\" STYLE='width: 75px;' CLASS=text>\n";
		$THIS_DISPLAY .= "     <OPTION VALUE=\"2\">2</OPTION>\n";
		$THIS_DISPLAY .= "     <OPTION VALUE=\"4\">4</OPTION>\n";
		$THIS_DISPLAY .= "     <OPTION VALUE=\"6\">6</OPTION>\n";
		$THIS_DISPLAY .= "     <OPTION VALUE=\"8\">8</OPTION>\n";
		$THIS_DISPLAY .= "     <OPTION VALUE=\"10\">10</OPTION>\n";
		$THIS_DISPLAY .= "     <OPTION VALUE=\"12\">12</OPTION>\n";
		$THIS_DISPLAY .= "     <OPTION VALUE=\"14\">14</OPTION>\n";
		$THIS_DISPLAY .= "     <OPTION VALUE=\"16\">16</OPTION>\n";
		$THIS_DISPLAY .= "     <OPTION VALUE=\"18\">18</OPTION>\n";
		$THIS_DISPLAY .= "     <OPTION VALUE=\"20\">20</OPTION>\n";
//		$THIS_DISPLAY .= "     <OPTION VALUE=\"30\">30</OPTION>\n";
//		$THIS_DISPLAY .= "     <OPTION VALUE=\"40\">40</OPTION>\n";
//		$THIS_DISPLAY .= "     <OPTION VALUE=\"50\">50</OPTION>\n";
//		$THIS_DISPLAY .= "     <OPTION VALUE=\"60\">60</OPTION>\n";
	$THIS_DISPLAY .= "</SELECT><BR><BR>\n\n";

$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n\n";


/*---------------------------------------------------------------------------------------------------------*
 __  __  _           ___             __
|  \/  |(_) ___ __  | _ \ _ _  ___  / _| ___
| |\/| || |(_-</ _| |  _/| '_|/ -_)|  _|(_-<
|_|  |_||_|/__/\__| |_|  |_|  \___||_|  /__/

# Miscellaneous preferences (all these are saved via userdata)
/*---------------------------------------------------------------------------------------------------------*/
$THIS_DISPLAY .= "<table id=\"misc_prefs\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"85%\" class=\"feature_sub\" style=\"margin: 15px 0;\">\n";
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"left\" class=\"fsub_title\">\n";
$THIS_DISPLAY .= "   ".lang("Miscellaneous Checkout Preferences")."<br></td>\n";
$THIS_DISPLAY .= " </tr>\n";


// Specify Default Country
//-----------------------------------------------------------------
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=left valign='top'>\n";
$THIS_DISPLAY .= "   <h3>".lang("More Information page")."</h3>\n";
$THIS_DISPLAY .= "   <span class=\"pref_label\">Maximum width for full-size sku images? (default = 650px)</span>\n";
$THIS_DISPLAY .= "   <input type=\"text\" id=\"fullimg_maxwidth\" name=\"mprefs[fullimg_maxwidth]\" value=\"".$cartprefs->get("fullimg_maxwidth")."\"/>\n";
$THIS_DISPLAY .= "   <span class=\"unit\">px</span>\n";

$THIS_DISPLAY .= "   <span class=\"pref_label\">Thumbnail-size image width? (default = 95px)</span>\n";
$THIS_DISPLAY .= "   <input type=\"text\" id=\"thumb_width\" name=\"mprefs[thumb_width]\" value=\"".$cartprefs->get("thumb_width")."\"/>\n";
$THIS_DISPLAY .= "   <span class=\"unit\">px</span>\n";


#New More Information Display Settings
if ( $cartprefs->get("more_information_display") == "" ) { $cartprefs->set("more_information_display", "default"); } // default
if ( $cartprefs->get("more_information_display") == "extended" ) {
	$selecteddefault = "";
	$selectedextended = " selected";
} else {
	$selecteddefault = " selected";
	$selectedextended = "";
} // default
$THIS_DISPLAY .= "   <span class=\"pref_label\">".lang("Price Variation Layout Display")."</span>\n";
$THIS_DISPLAY .= "   <select id=\"more_information_display\" name=\"mprefs[more_information_display]\">\n";
$THIS_DISPLAY .= "    <option value=\"default\"".$selecteddefault.">Default</option>\n";
$THIS_DISPLAY .= "    <option value=\"extended\"".$selectedextended.">Extended</option>\n";
$THIS_DISPLAY .= "   </select>\n";


if ( $cartprefs->get("skip_billingform_ifdone") == "" ) { $cartprefs->set("skip_billingform_ifdone", "no"); } // default
if ( $cartprefs->get("no_pobox_msg") == "" ) { $cartprefs->set("no_pobox_msg", "yes"); } // default
$THIS_DISPLAY .= "   <h3>".lang("Customer Billing/Shipping Info page")."</h3>\n";

# skip_billingform_ifdone
$THIS_DISPLAY .= "   <span class=\"pref_label\">Skip billing/shipping info form if they've already filled it out? \n";
$THIS_DISPLAY .= "    As in, if they're coming back to checkout after going back to shop around a bit more.</span>\n";
$THIS_DISPLAY .= "   <select id=\"skip_billingform_ifdone\" name=\"mprefs[skip_billingform_ifdone]\">\n";
$THIS_DISPLAY .= "    <option value=\"no\" selected>No (default)</option>\n";
$THIS_DISPLAY .= "    <option value=\"yes\">Yes</option>\n";
$THIS_DISPLAY .= "   </select>\n";

# nopobox_msg
$THIS_DISPLAY .= "   <p><span class=\"pref_label\">Show 'We do not ship to P.O. Boxes' message?</span>\n";
$THIS_DISPLAY .= "   <select id=\"nopobox_msg\" name=\"mprefs[nopobox_msg]\">\n";
$THIS_DISPLAY .= "    <option value=\"yes\" selected>Yes (default)</option>\n";
$THIS_DISPLAY .= "    <option value=\"no\">No</option>\n";
$THIS_DISPLAY .= "   </select></p>\n";

# Get Shipping Info?
$THIS_DISPLAY .= "   <p><span class=\"pref_label\">Disable shipping requirement.</span>\n";
$THIS_DISPLAY .= "   <select id=\"disable_shipping\" name=\"mprefs[disable_shipping]\">\n";
$THIS_DISPLAY .= "    <option value=\"no\" selected>No (default)</option>\n";
$THIS_DISPLAY .= "    <option value=\"yes\">Yes</option>\n";
$THIS_DISPLAY .= "   </select></p>\n";

# invoice_viewedit_link
if ( $cartprefs->get("invoice_viewedit_link") == "" ) { $cartprefs->set("invoice_viewedit_link", "no"); } // default
$THIS_DISPLAY .= "   <h3>".lang("Order Confirmation page")."</h3>\n";
$THIS_DISPLAY .= "   <span class=\"pref_label\">Display link on preview invoice to view/edit their order basket?</span>\n";
$THIS_DISPLAY .= "   <select id=\"invoice_viewedit_link\" name=\"mprefs[invoice_viewedit_link]\">\n";
$THIS_DISPLAY .= "    <option value=\"no\" selected>No (default)</option>\n";
$THIS_DISPLAY .= "    <option value=\"yes\">Yes</option>\n";
$THIS_DISPLAY .= "   </select>\n";


# invoice_custom_message
if ( $cartprefs->get("invoice_custom_message") == ""){ $cartprefs->set("invoice_custom_message", "no"); } // default
$THIS_DISPLAY .= "   <h3>".lang("Final Invoice Display")."</h3>\n";
$THIS_DISPLAY .= "   <span class=\"pref_label\">Display custom message on invoice?</span>\n";
$THIS_DISPLAY .= "   <select id=\"invoice_custom_message\" name=\"mprefs[invoice_custom_message]\" onchange=\"custom_msg_text()\">\n";
$THIS_DISPLAY .= "    <option value=\"no\" selected>No (default)</option>\n";
$THIS_DISPLAY .= "    <option value=\"yes\">Yes</option>\n";
$THIS_DISPLAY .= "   </select>\n";

if ( $cartprefs->get("invoice_custom_message_text") == "" ) { $cartprefs->set("invoice_custom_message_text", ""); } // default
if ( $cartprefs->get("invoice_custom_message") == "no"){
	$THIS_DISPLAY .= "   <div id=\"invoice_custom_message_text\" style=\"display:none;\">\n";
} else {
	$THIS_DISPLAY .= "   <div id=\"invoice_custom_message_text\" style=\"display:block;\">\n";
}
$THIS_DISPLAY .= "   <span class=\"pref_label\">Invoice Custom message text (html allowed)</span>\n";
$THIS_DISPLAY .= "   <textarea name=\"mprefs[invoice_custom_message_text]\" style=\"width: 400px;font-size: 11px; font-family: Courier New, Courier, mono;\"/>".$cartprefs->get("invoice_custom_message_text")."</textarea>\n";
$THIS_DISPLAY .= "   </div>\n";

# javascript to re-select saved settings
$THIS_DISPLAY .= "<script type=\"text/javascript\">\n";
$THIS_DISPLAY .= "document.getElementById('skip_billingform_ifdone').value = '".$cartprefs->get("skip_billingform_ifdone")."';\n";
$THIS_DISPLAY .= "document.getElementById('nopobox_msg').value = '".$cartprefs->get("nopobox_msg")."';\n";
$THIS_DISPLAY .= "document.getElementById('disable_shipping').value = '".$cartprefs->get("disable_shipping")."';\n";
$THIS_DISPLAY .= "document.getElementById('invoice_viewedit_link').value = '".$cartprefs->get("invoice_viewedit_link")."';\n";
$THIS_DISPLAY .= "document.getElementById('invoice_custom_message').value = '".$cartprefs->get("invoice_custom_message")."';\n";

$THIS_DISPLAY .= "function custom_msg_text() {\n";
$THIS_DISPLAY .= "	if (document.getElementById('invoice_custom_message').value == 'yes' ) { showid('invoice_custom_message_text'); } else { hideid('invoice_custom_message_text'); }\n";
$THIS_DISPLAY .= "}\n";
$THIS_DISPLAY .= "</script>\n";

$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n\n";


# [Save Display Settings]
$THIS_DISPLAY .= "<div style=\"text-align: center;margin-top: 15px;\">";
$THIS_DISPLAY .= " <button type=\"button\" class=\"greenButton\" onClick=\"document.displaysettings.submit();\"><span><span>".lang("Save Display Settings")."</span></span></button>";
$THIS_DISPLAY .= "</div>\n\n";

$THIS_DISPLAY .= "</form>\n\n";

$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n";

echo $THIS_DISPLAY;

####################################################################

# Restore css array
$cartcss = unserialize($DISPLAY['CSS']);

// ------------------------------------------------------------------
// Write Javascript Routine to update selection boxes with current
// databased selections
// ------------------------------------------------------------------
echo "<SCRIPT LANGUAGE=Javascript>\n\n";

if(strlen($cartcss['table_bgcolor']) > 1){
	echo "     set_cartcss('table_bgcolor', '".$cartcss['table_bgcolor']."');\n";
	echo "     preview_cartcss('previewtable', 'backgroundColor', '".$cartcss['table_bgcolor']."');\n";
}

if(strlen($cartcss['table_textcolor']) > 1){
	echo "     set_cartcss('table_textcolor', '".$cartcss['table_textcolor']."');\n";
	echo "     preview_cartcss('previewtable', 'color', '".$cartcss['table_textcolor']."');\n";
}

if(strlen($DISPLAY['DISPLAY_HEADERBG']) > 1){
	echo "     set_headerbg('".$DISPLAY['DISPLAY_HEADERBG']."');\n";
	echo "     document.displaysettings.SELCLRHEADBG.value = '".$DISPLAY['DISPLAY_HEADERBG']."';\n\n";
}

if(strlen($DISPLAY['DISPLAY_HEADERTXT']) > 1){
	echo "     set_headertxt('".$DISPLAY['DISPLAY_HEADERTXT']."');\n";
	echo "     document.displaysettings.SELCLRHEADTXT.value = '".$DISPLAY['DISPLAY_HEADERTXT']."';\n\n";
}

if(strlen($DISPLAY['DISPLAY_CARTBG']) > 1){
	echo "     set_cartbg('".$DISPLAY['DISPLAY_CARTBG']."');\n";
	echo "     document.displaysettings.SELCLRCARTBG.value = '".$DISPLAY['DISPLAY_CARTBG']."';\n\n";
}

if(strlen($DISPLAY['DISPLAY_CARTTXT']) > 1){
	echo "     set_carttxt('".$DISPLAY['DISPLAY_CARTTXT']."');\n\n";
	echo "     document.displaysettings.SELCLRCARTTXT.value = '".$DISPLAY['DISPLAY_CARTTXT']."';\n\n";
}

echo "     document.displaysettings.display_results.value = '$DISPLAY[DISPLAY_RESULTS]';\n\n";
echo "     document.displaysettings.display_colplacement.value = '$DISPLAY[DISPLAY_COLPLACEMENT]';\n\n";
echo "     document.displaysettings.display_search.value = '$DISPLAY[DISPLAY_SEARCH]';\n\n";
echo "     document.displaysettings.display_categories.value = '$DISPLAY[DISPLAY_CATEGORIES]';\n\n";
echo "     document.displaysettings.display_comments.value = '$DISPLAY[DISPLAY_COMMENTS]';\n\n";
echo "     document.displaysettings.display_emailfriend.value = '$DISPLAY[DISPLAY_EMAILFRIEND]';\n\n";
echo "     document.displaysettings.display_rememberme.value = '$DISPLAY[DISPLAY_REMEMBERME]';\n\n";
echo "     document.displaysettings.display_addcartbutton.value = '$DISPLAY[DISPLAY_ADDCARTBUTTON]';\n\n";
echo "     document.getElementById('more-info-link').value = '".$cartprefs->get('more-info-link')."';\n\n";
echo "     document.displaysettings.display_resultsort.value = '$DISPLAY[DISPLAY_RESULTSORT]';\n\n";
echo "     document.displaysettings.display_loginbutton.value = '$DISPLAY[DISPLAY_LOGINBUTTON]';\n\n";
echo "     document.displaysettings.goto_checkout.value = '".$DISPLAY['GOTO_CHECKOUT']."';\n\n";

echo "     ifShow('display_addcartbutton', 'C', 'custom_addcartbutton');\n";

echo "</SCRIPT>\n\n";

// ------------------------------------------------------------------





# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("This is where you set up the look and feel of your website's checkout process.");

# Build into standard module template
$module = new smt_module($module_html);
$module->meta_title = "Display Settings";
$module->add_breadcrumb_link("Shopping Cart Menu", "program/modules/mods_full/shopping_cart.php");
$module->add_breadcrumb_link("Display Settings", "program/modules/mods_full/shopping_cart/display_settings.php");
$module->icon_img = "program/includes/images/shopping-icon-med.png";
$module->heading_text = "Display Settings";
$module->description_text = $instructions;
$module->good_to_go();
?>
