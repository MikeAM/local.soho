<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


##############################################################################
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
## Copyright 1999-2007 Soholaunch.com, Inc. and Mike Johnston
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
include_once("../../../includes/product_gui.php");

# Set userdata mode, make sure defaults are set
$taxpref = new userdata("tax_rate_options");

# DEFAULT: Charge tax by bill-to country
if ( $taxpref->get("taxby") == "" ) {
   $taxpref->set("taxby", "BCOUNTRY");
}
# DEFAULT: Calculate tax based on order sub-total (before shipping)
if ( $taxpref->get("taxwhen") == "" ) {
   $taxpref->set("taxwhen", "beforeshipping");
}


# Update taxby preference
if ( isset($_POST['taxby']) ) {
   $taxpref->set("taxby", $_POST['taxby']);
}
# Update taxwhen preference
if ( isset($_POST['taxby']) ) {
   $taxpref->set("taxwhen", $_POST['taxwhen']);
}

if ( $_POST['vat-or-gst'] != '' ) {
	$taxpref->set("vat-or-gst", $_POST['vat-or-gst']);
	$report[] = 'Tax display preferece set to: '.$_POST['vat-or-gst'];
}

if ( $taxpref->get("vat-or-gst") == '' ) {
	$taxpref->set("vat-or-gst", 'VAT');
}


# PROCESS: Delete tax rule
if ( $_GET['killrule'] != "" ) {
   $rulestate = eregi_replace("_", " ", $_GET['killrule']);
   if ( $_GET['killtype'] == "country" ) {
      $qry = "DELETE FROM cart_vat WHERE country = '".$rulestate."'";
   } else {
      $qry = "DELETE FROM cart_tax WHERE state = '".$rulestate."'";
   }

   $rez = mysql_query($qry);
   $report[] = "Tax rule for ".$rulestate." deleted!";
}

#######################################################
### IF ACTION = ADD STATE TAX RATE    				    ###
#######################################################
$update_complete = 0;

if ($ACTION == "add_state") {

	for ($x=1;$x<=$numcurrent;$x++) {
		$temp = "currentstate" . $x;
		$currentstate[$x] = ${$temp};
		$temp = "currentrate" . $x;
		$currentrate[$x] = ${$temp};
	}

	// ------------------------------------------
	// Do we need to update or create data table
	// ------------------------------------------

	$tablename = "cart_tax";

	$result = mysql_list_tables("$db_name");
	$i = 0;
	$match = 0;
	while ($i < mysql_num_rows ($result)) {
		$tb_names[$i] = mysql_tablename ($result, $i);
		if ($tb_names[$i] == $tablename) { $match = 1; }
		$i++;
	}

	if ($match != 1) {

		mysql_db_query("$db_name","CREATE TABLE $tablename (state CHAR(255),rate CHAR(7))");

	} else {

		mysql_query("DELETE FROM cart_tax");

		// --------------------------------------------------------------
		// Update states that are currently in use (in case of deletion)
		// --------------------------------------------------------------

		if ($numcurrent != 0) {
			for ($z=1;$z<=$numcurrent;$z++) {
				if ($currentstate[$z] != $state) {
					mysql_query("INSERT INTO cart_tax VALUES('$currentstate[$z]','$currentrate[$z]')");
				}
			}
		}

	} // End Match Statement

	// ------------------------------------
	// Add new tax rate if sent
	// ------------------------------------

	if (strlen($taxrate) >= 1) {
		$taxrate = str_replace("%", "", $taxrate);	// In case % sign in input box, delete it!
		mysql_query("INSERT INTO cart_tax VALUES('$state','$taxrate')");
	}

	$update_complete = 1;


#######################################################
### IF ACTION = UPDATE VAT OPTIONS     				 ###
#######################################################

} elseif ($ACTION == "update_vat") {

	// ----------------------------------------------
	// Do we need to create biz info fields?
	// ----------------------------------------------

	$selTbl = mysql_query("SELECT * from cart_options");
	$fetch = mysql_fetch_array($selTbl);
	$validVat = "";

	//for testing
	if ($vatNum == "killcols") {
	   mysql_query("ALTER TABLE cart_options DROP COLUMN PAYMENT_CURRENCY_TYPE");
	   mysql_query("ALTER TABLE cart_options DROP COLUMN PAYMENT_CURRENCY_SIGN");
	   mysql_query("ALTER TABLE cart_options DROP COLUMN LOCAL_COUNTRY");
	   mysql_query("ALTER TABLE cart_options DROP COLUMN DISPLAY_STATE");
	   mysql_query("ALTER TABLE cart_options DROP COLUMN BIZ_COUNTRY");
	   mysql_query("ALTER TABLE cart_options DROP COLUMN CHARGE_VAT");
	   mysql_query("ALTER TABLE cart_options DROP COLUMN VAT_REG");
	} elseif ($vatNum == "killopts") {
	   mysql_query("DROP TABLE cart_options");
	} elseif ($vatNum == "killvat") {
	   mysql_query("DROP TABLE cart_vat");
	} elseif ($vatNum == "killtax") {
	   mysql_query("DROP TABLE cart_tax");
	}

	if ($charge_vat == "yes") {
	   if (strlen($vatNum) >= 5) {
	      $vatNum = str_replace("#", "", $vatNum);	// If somebody puts # sign in input box, delete it.
	      mysql_query("UPDATE cart_options SET CHARGE_VAT='$charge_vat',VAT_REG='$vatNum'");
	      $update_complete = 1;
	   } else {
	      $validVat = "border: 1px solid #ff0000;";
	      $update_complete = 0;
	   }
	} elseif ($charge_vat == "no") {
	   mysql_query("UPDATE cart_options SET CHARGE_VAT='$charge_vat',VAT_REG='vatnum'");
	   $vatNum = "";
	   $update_complete = 1;
	}



#######################################################
### IF ACTION = ADD VAT RATE          				    ###
#######################################################

} elseif ($ACTION == "add_vat") {

	for ($x=1;$x<=$numNats;$x++) {
		$temp = "currentNat" . $x;
		$currentNat[$x] = ${$temp};
		$temp = "currentVat" . $x;
		$currentVat[$x] = ${$temp};
	}

	// ------------------------------------------
	// Do we need to update or create data table
	// ------------------------------------------

	$tablename = "cart_vat";

	$result = mysql_list_tables("$db_name");
	$i = 0;
	$match = 0;
	while ($i < mysql_num_rows ($result)) {
		$tb_names[$i] = mysql_tablename ($result, $i);
		if ($tb_names[$i] == $tablename) { $match = 1; }
		$i++;
	}

	if ($match != 1) {

		mysql_db_query("$db_name","CREATE TABLE $tablename (country CHAR(25),rate CHAR(7))");

	} else {

		mysql_query("DELETE FROM cart_vat");

		// --------------------------------------------------------------
		// Update countries that are currently in use (in case of deletion)
		// --------------------------------------------------------------

		if ($numNats != 0) {
			for ($z=1;$z<=$numNats;$z++) {
				if ($currentNat[$z] != $country) {
					mysql_query("INSERT INTO cart_vat VALUES('$currentNat[$z]','$currentVat[$z]')");
				}
			}
		}

	} // End Match Statement

	// ------------------------------------
	// Add new tax rate if sent
	// ------------------------------------

	if (strlen($vatTax) >= 1) {
		$vatTax = str_replace("%", "", $vatTax);	// In case somebody puts % sign in input box, delete it!
		mysql_query("INSERT INTO cart_vat VALUES('$country','$vatTax')");
	}

	$update_complete = 1;

} // End update action

#######################################################
### READ CURRENT SALES TAX RATE TABLE IF EXISTS	    ###
#######################################################

$match = 0;
$tablename = "cart_tax";
$result = mysql_list_tables("$db_name");
$i = 0;
while ($i < mysql_num_rows ($result)) {
	$tb_names[$i] = mysql_tablename ($result, $i);
	if ($tb_names[$i] == $tablename) {
		$match = 1;
	}
	$i++;
}
# Build tax rule list
if ($match == 1) {
	$result = mysql_query("SELECT * FROM cart_tax order by state");
	$numberRows = mysql_num_rows($result);
	$a=0;
	while ($row = mysql_fetch_array ($result)) {
		$a++;
		$currentstate[$a] = $row["state"];
		$currentrate[$a] = $row["rate"];
	}
	$numcurrent = $a;

} else {

	$numcurrent = 0;

}

#######################################################
### READ CURRENT VAT INFO IF IT EXISTS       	    ###
#######################################################

$selTbl = mysql_query("SELECT * from cart_options");
$fetch = mysql_fetch_array($selTbl);
$charge_vat = $charge_vat;
$vatNum = $vatNum;
$vatNo = "";
$vatYes = "";

if ($ACTION != "update_vat") {
   $vatNum = $fetch[VAT_REG];
   $charge_vat = $fetch[CHARGE_VAT];
}

if ($vatNum == "vatnum") {
   $vatNum = "";
}

if ($charge_vat == "no") {
   $vatNo = "selected";
} elseif ($charge_vat == "yes") {
   $vatYes = "selected";
}

#######################################################
### READ CURRENT VAT TAX RATE TABLE IF EXISTS	    ###
#######################################################

$match = 0;
$tablename = "cart_vat";
$result = mysql_list_tables("$db_name");
$i = 0;
while ($i < mysql_num_rows ($result)) {
	$tb_names[$i] = mysql_tablename ($result, $i);
	if ($tb_names[$i] == $tablename) {
		$match = 1;
	}
	$i++;
}

if ($match == 1) {
	$result = mysql_query("SELECT * FROM cart_vat order by country");
	$numberRows = mysql_num_rows($result);
	$a=0;
	while ($row = mysql_fetch_array ($result)) {
		$a++;
		$currentNat[$a] = $row["country"];
		$currentVat[$a] = $row["rate"];
	}
	$numNats = $a;

} else {

	$numNats = 0;

}

#######################################################
### GET US STATE DATA FROM FLAT FILE		    ###
#######################################################

$filename = "shared/us_states.dat";
$file = fopen("$filename", "r") or DIE("Error: Could not open us states data (shared/us_states.dat).");
	$tmp_data = fread($file,filesize($filename));
fclose($file);

$STATE = split("\n", $tmp_data);


#######################################################
### GET COUNTRY DATA FROM FLAT FILE		             ###
#######################################################

$filename = "shared/countries.dat";
$file = fopen("$filename", "r") or DIE("Error: Could not open country data (shared/contries.dat).");
	$tmp_data = fread($file,filesize($filename));
fclose($file);

$natDat = split("\n",$tmp_data);
$vatTmp = count($natDat);
$vatCountry = "";
for ($f=0;$f < $vatTmp;$f++) {
   $natNam = split("::",$natDat[$f]);
   $vatCountry[$f] = "$natNam[0] - $natNam[1]";
   $vatCountry[$f] = strtoupper($vatCountry[$f]);
}

#######################################################
### START HTML/JAVASCRIPT CODE			    ###
#######################################################

# So you can write straight HTML without having to build every line into a container var (i.e. $disHTML .= "another line of html")
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

SV2_showHideLayers('addCartMenu?header','','hide');
SV2_showHideLayers('blankLayer?header','','hide');
SV2_showHideLayers('linkLayer?header','','hide');
SV2_showHideLayers('newsletterLayer?header','','hide');
SV2_showHideLayers('cartMenu?header','','show');
SV2_showHideLayers('menuLayer?header','','hide');
SV2_showHideLayers('editCartMenu?header','','hide');

function help() {
   alert('<?php echo lang("To Add a tax rate"); ?>');
}

function edit_rate(selectid, state, rate) {
   document.getElementById(selectid).value = state;
   document.getElementById(selectid+'-rate').value = rate;
   document.getElementById(selectid+'-save').innerHTML = '<span><span><?php echo lang("Save Changes"); ?></span></span>';
   document.getElementById(selectid+'-cancel').style.display = 'inline';
}
function cancel_edit(selectid) {
   document.getElementById(selectid).selectedIndex = 0;
   document.getElementById(selectid+'-rate').value = '';
   document.getElementById(selectid+'-save').innerHTML = '<span><span><?php echo lang("Add/Save Tax Rate"); ?></span></span>';
   document.getElementById(selectid+'-cancel').style.display = 'none';
}

//-->
</script>

<?
$THIS_DISPLAY = "";

# tax_prefs
$THIS_DISPLAY .= "<div id=\"tax_prefs\" style=\"padding-bottom: 10px;border: 0px solid red;\">\n";
$THIS_DISPLAY .= " <form name=\"taxby_form\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";

# Charge tax by...
$THIS_DISPLAY .= " <div style=\"float: left;\">\n";
$THIS_DISPLAY .= "  <b>".lang("Charge tax by").": </b><br/>\n";
$THIS_DISPLAY .= "  <select id=\"taxby\" name=\"taxby\" onchange=\"document.taxby_form.submit();\">\n";
$THIS_DISPLAY .= "   <option value=\"BCOUNTRY\" selected>Bill-to Address (default)</option>\n";
$THIS_DISPLAY .= "   <option value=\"SCOUNTRY\">Ship-to Address</option>\n";
$THIS_DISPLAY .= "  </select>\n";
$THIS_DISPLAY .= " </div>\n";

# Charge tax by...
$THIS_DISPLAY .= " <div style=\"float: left;margin-left: 20px;\">\n";
$THIS_DISPLAY .= "  <b>".lang("Include shipping charges in tax calculation")."?</b><br/>\n";
$THIS_DISPLAY .= "  <select id=\"taxwhen\" name=\"taxwhen\" onchange=\"document.taxby_form.submit();\">\n";
$THIS_DISPLAY .= "   <option value=\"beforeshipping\" selected>No - Tax SubTotal before shipping charges (default)</option>\n";
$THIS_DISPLAY .= "   <option value=\"aftershipping\">Yes - Tax total including shipping charges</option>\n";
$THIS_DISPLAY .= "  </select>\n";
$THIS_DISPLAY .= " </div>\n";

# Reselect current values
$THIS_DISPLAY .= "<script type=\"text/javascript\">\n";
$THIS_DISPLAY .= "document.getElementById('taxby').value = '".$taxpref->get("taxby")."';\n";
$THIS_DISPLAY .= "document.getElementById('taxwhen').value = '".$taxpref->get("taxwhen")."';\n";
$THIS_DISPLAY .= "</script>\n";
$THIS_DISPLAY .= " </form>\n";
//$THIS_DISPLAY .= " <div style=\"clear: both;\"></div>\n";
$THIS_DISPLAY .= "</div>\n"; // End tax_prefs



$THIS_DISPLAY .= "<table BORDER=0 CELLPADDING=5 CELLSPACING=0 WIDTH=100%>\n";


//-----------------------------------------------------------------------------------------
// Start US/Canada table
//-----------------------------------------------------------------------------------------

$THIS_DISPLAY .= "<FORM METHOD=POST ACTION=\"tax_rates.php\" name=\"add_state_tax_form\">\n";
$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=ACTION VALUE=\"add_state\">\n\n";

$THIS_DISPLAY .= "<TR><td align=left valign=top class='text' style='border-bottom:1px solid #336699; font-size: 12px; padding:5px 5px 1px 0px;'>\n";
$THIS_DISPLAY .= "<b>".lang("United States")."</b> &amp; <b>".lang("Canada")."</b><BR>\n";
$THIS_DISPLAY .= "</td></tr>\n";

$THIS_DISPLAY .= "<tr><td align=\"left\" valign=top>\n";

$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\">\n";
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"left\" valign=top class=\"text\">\n";

# Start 'add/delete state tax' table
$THIS_DISPLAY .= "   <table border=\"0\" cellpadding=\"5\" cellspacing=\"0\">\n";

$THIS_DISPLAY .= "    <tr>\n";
$THIS_DISPLAY .= "     <td colspan=\"2\" align=left valign=\"top\" class=\"text\" style=\"color: 000099;\"><b>\n";
$THIS_DISPLAY .= "      ".lang("Add State/Province Tax Rule:")."</b>\n\n";
$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "    </tr>\n";

$THIS_DISPLAY .= "    <tr>\n";
$THIS_DISPLAY .= "     <td align=\"left\" valign=top class=\"text\">\n";
$THIS_DISPLAY .= "      <select id=\"state\" name=\"state\" class=\"text\" style=\"width: 175px;\">\n";

// Setup selection box options for States and Provinces
$max = count($STATE);
for ($x=1; $x<=$max; $x++) {
   $y = $x-1;
   $THIS_DISPLAY .= "        <option value=\"$STATE[$y]\">$STATE[$y]</option>\n";
}

$THIS_DISPLAY .= "      </select>\n";
$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "     <td align=\"left\" valign=top class=\"text\" style=\"color: 000099;\">\n";
$THIS_DISPLAY .= lang("Tax Rate").": <input id=\"state-rate\" class=\"text\" type=\"text\" size=5 name=\"taxrate\" value=\"\">%\n";
$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "    </tr>\n";

$THIS_DISPLAY .= "    <tr>\n";
$THIS_DISPLAY .= "     <td colspan=\"2\" align=left valign=\"top\" class=\"text\">\n";
$THIS_DISPLAY .= "      <button type=\"button\" id=\"state-save\" class=\"greenButton\" onclick=\"document.add_state_tax_form.submit();\"><span><span>".lang("Add/Save Tax Rate")."</span></span></button>\n";
$THIS_DISPLAY .= "      <button type=\"button\" id=\"state-cancel\" class=\"redButton\" onclick=\"cancel_edit('state');\" style=\"display: none;\"><span><span>".lang("Cancel Changes")."</span></span></button>\n";
$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "    </tr>\n";
$THIS_DISPLAY .= "   </table>\n";

$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= "  <td align=\"left\" valign=top class=\"text\">\n\n";

# Current tax rules table
$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\" class=\"feature_sub\">\n";
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"left\" valign=\"middle\" class=\"fsub_title\">\n";
$THIS_DISPLAY .= "   ".lang("State/Province")."\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= "  <td align=\"left\" valign=\"middle\" class=\"fsub_title\">\n";
$THIS_DISPLAY .= "   ".lang("Tax Rate")."\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= "  <td colspan=\"2\" class=\"fsub_title\">\n";
$THIS_DISPLAY .= "   &nbsp;\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n\n";

// Display current states in data table
if ($numcurrent != 0) {

   $THIS_DISPLAY .= "<input type=\"hidden\" name=numcurrent value=\"$numcurrent\">\n";

   # Edit mode?
   if ( $_GET['editrule'] != "" ) {
      $editstate = eregi_replace("_", " ", $_GET['editrule']);
   }

   for ($i=1;$i<=$numcurrent;$i++) {
      $THIS_DISPLAY .= " <tr>\n";
      $THIS_DISPLAY .= "  <td align=\"left\" valign=top bgcolor=\"#F8F9FD\">".$currentstate[$i]."</td>\n";
      $THIS_DISPLAY .= "  <td align=\"left\" valign=top bgcolor=\"#F8F9FD\">".$currentrate[$i]."%</td>\n";
      $THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\">\n";
      $THIS_DISPLAY .= "   [<strong><span class=\"blue uline hand\" onclick=\"edit_rate('state', '".$currentstate[$i]."', '".$currentrate[$i]."');\">Edit</a></strong>]\n";
      $THIS_DISPLAY .= "  </td>\n";
      $THIS_DISPLAY .= "  <td align=\"left\" valign=top bgcolor=\"#F8F9FD\">\n";
      $THIS_DISPLAY .= "   [<a href=\"tax_rates.php?killrule=".eregi_replace(" ", "_", $currentstate[$i])."\" class=\"del\">Delete</a>]\n";
      $THIS_DISPLAY .= "  </td>\n";
      $THIS_DISPLAY .= " </tr>\n";
      $THIS_DISPLAY .= " <input type=\"hidden\" name=\"currentstate$i\" value=\"$currentstate[$i]\">\n";
      $THIS_DISPLAY .= " <input type=\"hidden\" name=\"currentrate$i\" value=\"$currentrate[$i]\">\n";
   }

} else {

   $THIS_DISPLAY .= "     <tr><td align=\"center\" valign=top colspan=\"2\">".lang("There are currently no states in use.")."</td></tr>\n";

}

// --------------------------------------

$THIS_DISPLAY .= "</table>\n\n";

$THIS_DISPLAY .= "</td></tr></table>\n";
$THIS_DISPLAY .= "</td></tr>\n";
$THIS_DISPLAY .= "</form>\n";

$THIS_DISPLAY .= "<tr><td>&nbsp;</td></tr>\n";

//-----------------------------------------------------------------------------------------
// Start VAT Tax table
//-----------------------------------------------------------------------------------------



$THIS_DISPLAY .= "<TR><td align=left valign=top class='text' style='border-bottom:1px solid #336699; font-size: 12px; padding:5px 5px 1px 0px;'>\n";
$THIS_DISPLAY .= "<b>".lang("International Taxes")."</b><BR>\n";
$THIS_DISPLAY .= "</td></tr>\n";

$THIS_DISPLAY .= "<TR><td align=left valign=top class=text>\n";

//Start VAT options table
//--------------------------------------------

$THIS_DISPLAY .= "<FORM METHOD=POST ACTION=\"tax_rates.php\" name=\"vat_gst_form\">\n";
$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=ACTION VALUE=\"update_vat\">\n\n";

//For testing purposes
//$THIS_DISPLAY .= "<b><font color='#FF0000'>charge_vat = ($charge_vat) | vatNo = ($vatNo) | vatYes = ($vatYes) | vatNum = ($vatNum)</font></b><br><br>\n";

$THIS_DISPLAY .= "<font color='#FF0000'>".lang("Note: You must enter a valid VAT/GST registration number to charge and collect VAT/GST taxes.")."</font><br>\n";
$THIS_DISPLAY .= "<table width='100%' border='0' cellpadding='5' cellspacing='0' style='border:1px solid #000000;'>\n";
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td bgcolor='#F8F9FD' class='text' align='right'><b>Charge VAT/GST Tax?</b></td>\n";
$THIS_DISPLAY .= "  <td bgcolor='#F8F9FD' align='left'>\n";
$THIS_DISPLAY .= "   <select name='charge_vat' class='catselect'>\n";
$THIS_DISPLAY .= "    <option value='no' ".$vatNo.">".lang("No")."</option>\n";
$THIS_DISPLAY .= "    <option value='yes' ".$vatYes.">".lang("Yes")."</option>\n";
$THIS_DISPLAY .= "   </select></td>\n";
$THIS_DISPLAY .= "  <td bgcolor='#F8F9FD' class='text' align='right'><b>".lang("Registration Number:")."</b></td>\n";
$THIS_DISPLAY .= "  <td width='130' bgcolor='#F8F9FD' class='text'><input type='text' name='vatNum' value='".$vatNum."' class='text' style='width: 150px;".$validVat."'></td>\n";
$THIS_DISPLAY .= "  <td width='175' align='right' bgcolor='#F8F9FD' rowspan=\"2\">\n";
$THIS_DISPLAY .= "   <button type='button' class=\"greenButton\" onclick=\"document.vat_gst_form.submit();\"><span><span>".lang("Save Tax Options")."</span></span></button>\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td bgcolor='#F8F9FD' class='text' align='right'><b>Display Preference:</b></td>\n";
$THIS_DISPLAY .= "  <td colspan=\"4\" bgcolor='#F8F9FD' align='left'>\n";
$THIS_DISPLAY .= "   <select id='vat-or-gst' name='vat-or-gst' class='catselect'>\n";
$THIS_DISPLAY .= "    <option value='VAT'>VAT</option>\n";
$THIS_DISPLAY .= "    <option value='GST'>GST</option>\n";
$THIS_DISPLAY .= "   </select></td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td colspan=\"4\" bgcolor='#F8F9FD' class='text' style=\"padding: 0 0 5px 20px;\">\n";
ob_start();
?>
<script type="text/javascript">
document.getElementById('vat-or-gst').value = '<?php echo $taxpref->get("vat-or-gst"); ?>';
</script>

<?php
$THIS_DISPLAY .= ob_get_contents();
ob_end_clean();
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n";
$THIS_DISPLAY .= "</form>\n";

if ($charge_vat == "yes" && $vatNum != "vatnum" && $vatNum != "") {

   $THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 WIDTH=100%>\n";
   $THIS_DISPLAY .= "<TR>\n";
   $THIS_DISPLAY .= "<TD ALIGN=left VALIGN=TOP CLASS=text>\n";


   //Start 'add/delete tax' table
   //--------------------------------------------
   $THIS_DISPLAY .= "<FORM METHOD=POST ACTION=\"tax_rates.php\" name=\"add_country_tax\">\n";
   $THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=ACTION VALUE=\"add_vat\">\n\n";
   $THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\">\n";
   $THIS_DISPLAY .= " <tr>\n";
   $THIS_DISPLAY .= "  <td align=\"left\" valign=top class=\"text\">\n";

   # Start 'add/delete state tax' table
   $THIS_DISPLAY .= "   <table border=\"0\" cellpadding=\"5\" cellspacing=\"0\">\n";

   $THIS_DISPLAY .= "    <tr>\n";
   $THIS_DISPLAY .= "     <td colspan=\"2\" align=left valign=\"top\" class=\"text\" style=\"color: 000099;\"><b>\n";
   $THIS_DISPLAY .= "      ".lang("Add Country-based Tax Rule:")."</b>\n\n";
   $THIS_DISPLAY .= "     </td>\n";
   $THIS_DISPLAY .= "    </tr>\n";

   $THIS_DISPLAY .= "    <tr>\n";
   $THIS_DISPLAY .= "     <td align=\"left\" valign=top class=\"text\">\n";
   $THIS_DISPLAY .= "      <select id=\"country\" name=\"country\" class=\"text\" style=\"width: 175px;\">\n";


	// Setup selection box options for Countries
	for ($x=0;$x<=$vatTmp;$x++) {;
		$THIS_DISPLAY .= "     <OPTION VALUE=\"$vatCountry[$x]\">$vatCountry[$x]</OPTION>\n";
	}

	$THIS_DISPLAY .= "</SELECT>\n";
	$THIS_DISPLAY .= "</td>\n";
   $THIS_DISPLAY .= "<td align=left valign=top class=text style=\"color: 000099;\">\n";
	$THIS_DISPLAY .= lang("Tax Rate").": <input id=\"country-rate\" class=text type=text size=5 name=vatTax value=\"\">%\n";
	$THIS_DISPLAY .= "</td></tr>\n";

   $THIS_DISPLAY .= "    <tr>\n";
   $THIS_DISPLAY .= "     <td colspan=\"2\" align=left valign=\"top\" class=\"text\">\n";
   $THIS_DISPLAY .= "      <button type=\"button\" id=\"country-save\" class=\"greenButton\" onclick=\"document.add_country_tax.submit();\"><span><span>".lang("Add/Save Tax Rate")."</span></span></button>\n";
   $THIS_DISPLAY .= "      <button type=\"button\" id=\"country-cancel\" class=\"redButton\" onclick=\"cancel_edit('country');\" style=\"display: none;\"><span><span>".lang("Cancel Changes")."</span></span></button>\n";
   $THIS_DISPLAY .= "     </td>\n";
   $THIS_DISPLAY .= "    </tr>\n";
   $THIS_DISPLAY .= "   </table>\n";

   $THIS_DISPLAY .= "</TD><TD ALIGN=left VALIGN=TOP CLASS=text>\n\n";

   # Current tax rules table
   $THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\" class=\"feature_sub\">\n";
   $THIS_DISPLAY .= " <tr>\n";
   $THIS_DISPLAY .= "  <td align=\"left\" valign=\"middle\" class=\"fsub_title\">\n";
   $THIS_DISPLAY .= "   ".lang("State/Province")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td align=\"left\" valign=\"middle\" class=\"fsub_title\">\n";
   $THIS_DISPLAY .= "   ".lang("Tax Rate")."\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= "  <td colspan=\"2\" class=\"fsub_title\">\n";
   $THIS_DISPLAY .= "   &nbsp;\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= " </tr>\n\n";


	// Display current countries in data table

	if ($numNats != 0) {

		$THIS_DISPLAY .= "<input type=hidden name=numNats value=\"$numNats\">\n";

		for ($i=1;$i<=$numNats;$i++) {
         $THIS_DISPLAY .= " <tr>\n";
         $THIS_DISPLAY .= "  <td align=\"left\" valign=top bgcolor=\"#F8F9FD\">".$currentNat[$i]."</td>\n";
         $THIS_DISPLAY .= "  <td align=\"left\" valign=top bgcolor=\"#F8F9FD\">".$currentVat[$i]."%</td>\n";
         $THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\">\n";
         $THIS_DISPLAY .= "   [<strong><span class=\"blue uline hand\" onclick=\"edit_rate('country', '".$currentNat[$i]."', '".$currentVat[$i]."');\">Edit</a></strong>]\n";
         $THIS_DISPLAY .= "  </td>\n";
         $THIS_DISPLAY .= "  <td align=\"left\" valign=top bgcolor=\"#F8F9FD\">\n";
         $THIS_DISPLAY .= "   [<a href=\"tax_rates.php?killtype=country&amp;killrule=".eregi_replace(" ", "_", $currentNat[$i])."\" class=\"del\">Delete</a>]\n";
         $THIS_DISPLAY .= "  </td>\n";
         $THIS_DISPLAY .= " </tr>\n";
         $THIS_DISPLAY .= " <input type=\"hidden\" name=\"currentNat$i\" value=\"$currentNat[$i]\">\n";
         $THIS_DISPLAY .= " <input type=\"hidden\" name=\"currentVat$i\" value=\"$currentVat[$i]\">\n";
		}

	} else {

		$THIS_DISPLAY .= "     <tr><td align=center valign=top colspan=2>".lang("There are currently no countries in use.")."</td></tr>\n";

	}

	// --------------------------------------

	$THIS_DISPLAY .= "</TABLE>\n\n";
} // End if charge_vat

$THIS_DISPLAY .= "</TD></TR></TABLE>\n";
$THIS_DISPLAY .= "</td></tr>\n";


//End International Table

$THIS_DISPLAY .= "</table>\n";
$THIS_DISPLAY .= "</FORM>\n\n";
echo $THIS_DISPLAY;


# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$taxrate_introtxt = lang("When visitors purchase items from your site")." \n";
$taxrate_introtxt .= lang("and select delivery to any of the below-listed areas,")."\n";
$taxrate_introtxt .= lang("they will be charged the tax percentages you specify here.")."\n\n";

$module = new smt_module($module_html);
$module->meta_title = "Tax Rate Options";
$module->add_breadcrumb_link("Shopping Cart Menu", "program/modules/mods_full/shopping_cart.php");
$module->add_breadcrumb_link("Tax Rate Options", "program/modules/mods_full/shopping_cart/tax_rates.php");
$module->icon_img = "program/includes/images/shopping-icon-med.png";
$module->heading_text = "Tax Rate Options";
$module->description_text = $taxrate_introtxt;
$module->good_to_go();
?>