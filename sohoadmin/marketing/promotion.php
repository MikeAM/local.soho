<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


############################################################################################
## Soholaunch(R) Site Management Tool
## Version 4.8
##
## Author: 			Mike Johnston & Mike Morrison
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugz.soholaunch.com
############################################################################################

############################################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2005 Soholaunch.com, Inc. and Mike Johnston.  All Rights Reserved.
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
#############################################################################################

session_start();
error_reporting(E_PARSE);
include_once("../program/includes/product_gui.php"); // Include core files
include_once("../program/includes/multi_user.php"); // Determine module icons, etc


# Include core js functions
echo "<script language=\"javascript\" src=\"../program/includes/display_elements/js_functions.php\"></script>\n\n\n";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../program/product_gui.css\">\n\n\n";

# Uncomment to display var info for testing
/*---------------------------------------------*
echo "\$mod == [".$mod."]<br>";
echo "\$_GET['mod'] == [".$_GET['mod']."]<br>";
/*---------------------------------------------*/

# Format brandable content
# NOTE: The '$hostco_' variables are read into session from host.conf.php at login
# NOTE: The '$_SESSION['hostco'] array is read into session from hostco.conf.php at login

# Branded via old method (host.conf.php)
if ( $hostco_name != "" && !isset($_SESSION['hostco']) ) {
   # Upgrade instructions
   $how_upgrade = " ".str_replace(":HOSTCO_NAME:", "<u>".$hostco_name."</u>", lang("In order to activate it, please contact :HOSTCO_NAME: and request an upgrade."))."<br>\n";

   # Sales email
   if ( $hostco_email != "sales@domain.com" && $hostco_email != "" ) {
      $email = " ".lang("Email").": <a href=\"mailto:".$hostco_email."\">".$hostco_email."</a><br>";
   } else {
      $email = "";
   }

   # Phone Number
   if ( $hostco_phone != "555.555.5555" && $hostco_phone != "" ) {
      $phone = " ".lang("Phone").": (<font color=\"#339959\">".$hostco_phone."</font>)<br>";
   } else {
      $phone = "";
   }

# Branded via new method (include custom promo url)
} elseif ( isset($_SESSION['hostco']) ) {
   $remote_promo = ereg_replace("MODNAME", $mod, $_SESSION['promo_page_url']);
   $hostco_name = $_SESSION['hostco']['company_name'];
   $sitebuilder_name = $_SESSION['hostco']['sitebuilder_name'];

# Not branded (include Soholaunch promo url)
} else {
   $hostco_name = "Web Host";
   $sitebuilder_name = "Soholaunch Pro Edition";

}

# LEARN MORE BUTTON - COMMENTED OUT
#   It's probably better to just stick any 'more info' pages
#   on whatever website the Buy Now button links to
/*-------------------------------------------------------------------------------------*
# LEARN MORE: Custom link
if ( $_SESSION['hostco']['upgrades'] != "soholaunch" && $_SESSION['hostco']['learn_more'] == "custom" ) {
   $learn_more_goto = "window.open('http://".$_SESSION['hostco']['learn_more_url']."');";

# LEARN MORE: Soholaunch website
} elseif ( $_SESSION['hostco']['upgrades'] == "soholaunch" || $_SESSION['hostco']['learn_more'] == "soholaunch" ) {
   $learn_more_goto = "window.open('http://info.soholaunch.com/index.php?pr=Learn_More');";

# LEARN MORE: Do not display
} else {
   $learn_more_goto = "";
}

echo "  <td width=\"100%\" align=\"right\" style=\"background-color: #F9F8FD;\">\n";

# Show Learn More button?
if ( $learn_more_goto != "" ) {
   # Use span w/bg image so we can lay translatable text on top of the graphic
   echo "   <span class=\"CTA_btn CTA_learn_more-off\" ";
   echo "onClick=\"".$learn_more_goto."\" ";
   echo "onMouseover=\"this.className='CTA_btn CTA_learn_more-on'\" ";
   echo "onMouseout=\"this.className='CTA_btn CTA_learn_more-off'\">\n";
   echo "    ".lang("Learn More")."\n";
   echo "   </span>\n";
} else {
   echo "&nbsp;";
}

echo "  </td>\n";
/*-------------------------------------------------------------------------------------*/

/*---------------------------------------------------------------------------------------------------------*
 ___               _  _
| _ ) _  _  _  _  | \| | ___ __ __ __
| _ \| || || || | | .` |/ _ \\ V  V /
|___/ \_,_| \_, | |_|\_|\___/ \_/\_/
            |__/
/*---------------------------------------------------------------------------------------------------------*/
# BUY NOW: Custom link
if ( $_SESSION['hostco']['upgrades'] != "soholaunch" && $_SESSION['hostco']['buy_now'] == "custom" ) { // Custom link
   $buy_now_goto = "window.open('http://".$_SESSION['hostco']['buy_now_url']."');";

# BUY NOW: Soholaunch website
} elseif ( ($_SESSION['hostco']['upgrades'] == "soholaunch" || $_SESSION['hostco']['buy_now'] == "soholaunch") || ($_SESSION['hostco']['buy_now'] == "" && ($hostco_email == "" || $hostco_email == "sales@domain.com")) ) {
   $buy_now_goto = "window.open('http://buysingle.soholaunch.com/index.php?user_domain=".$_SERVER['HTTP_HOST']."');";

# BUY NOW: Upgrade Request form
} elseif ( $_SESSION['hostco']['buy_now'] == "upgrade_form" || ($hostco_email != "" && $hostco_email != "sales@domain.com") ) { // Upgrade form

   # Make sure we've got an email to send form data to
   if ( $_SESSION['hostco']['upgrade_request_email'] != "" ) {
      $form_goes_to = $_SESSION['hostco']['upgrade_request_email'];
   } else {
      $form_goes_to = $hostco_email;
   }

   $buy_now_goto = "parent.body.location.href='promotion.php?todo=upgrade_form&sendto=".$form_goes_to."&mod=$mod';";
}


/*---------------------------------------------------------------------------------------------------------*
 __  __                                 ___
|  \/  | ___  ___ ___ __ _  __ _  ___  | _ ) ___ __ __
| |\/| |/ -_)(_-<(_-</ _` |/ _` |/ -_) | _ \/ _ \\ \ /
|_|  |_|\___|/__//__/\__,_|\__, |\___| |___/\___//_\_\
                           |___/

# "Your current license does not allow you to access this feature."
# Show everywhere except 'thank you' page that displays after upgrade request is submitted
/*---------------------------------------------------------------------------------------------------------*/
if ( !isset($_POST['upgrade']) ) {
   echo "<table width=\"100%\" class=\"feature_sub\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" style=\"background-color: #EFEFEF;\">\n";
   echo " <tr>\n";
   echo "  <td class=\"bold red\" style=\"font: 14px arial; padding: 10px;\" align=\"center\">\n";
   echo "   <i>".str_replace("MODNAME", $mod, lang("Your current license does not allow you to access this feature."))."</i>\n";
   echo "  </td>\n";
   echo " </tr>\n";

   echo " <tr>\n";
   echo "  <td align=\"center\">\n";

   # BUY NOW: Use span w/bg image so we can lay translatable text on top of the graphic
   echo "  <span onclick=\"".$buy_now_goto."\" onMouseover=\"this.style.backgroundImage='url(../skins/".$_SESSION['skin']."/buttons/buy_now-on.gif)'\" onMouseout=\"this.style.backgroundImage='url(../skins/".$_SESSION['skin']."/buttons/buy_now-off.gif)'\" style=\"display: block; cursor: pointer; padding: 0px 0px 0px 0px; width: 160px; height: 32px; background-image: url(../skins/".$_SESSION['skin']."/buttons/buy_now-off.gif);\">\n";
   echo "   <span style=\"display: block; vertical-align: top; padding: 5px 10px 0px 10px; border: 0px solid red;\">".lang("Buy Now")."</span>\n";
   echo "  </span>\n";

   echo "  </td>\n";
   echo " </tr>\n";
   echo "</table>\n";

   # Preload button images
   echo "<span style=\"display: none; padding: 0px;\">\n";
   echo "<img src=\"../skins/".$_SESSION['skin']."/buttons/buy_now-off.gif\" width=\"1\" height=\"1\">\n";
   echo "<img src=\"../skins/".$_SESSION['skin']."/buttons/buy_now-on.gif\" width=\"1\" height=\"1\">\n";
   echo "<img src=\"../skins/".$_SESSION['skin']."/buttons/learn_more-off.gif\" width=\"1\" height=\"1\">\n";
   echo "<img src=\"../skins/".$_SESSION['skin']."/buttons/learn_more-on.gif\" width=\"1\" height=\"1\">\n";
   echo "</span>\n";

   # Content div w/height that accounts for top message header
   echo "<div style=\"overflow: scroll; width: 100%; height: 450px;\">\n";

} else {
   # Content div w/full-screen height (as in, without 'no access' header)
   echo "<div style=\"overflow: none; width: 100%; height: 550px;\">\n";
}


/*---------------------------------------------------------------------------------------------------------*
  ___            _              _
 / __| ___  _ _ | |_  ___  _ _ | |_
| (__ / _ \| ' \|  _|/ -_)| ' \|  _|
 \___|\___/|_||_|\__|\___||_||_|\__|

# Stick content in div to ensure scrollability
/*---------------------------------------------------------------------------------------------------------*/

# SHOW PROMO CONTENT
if ( $todo == "" ) {

   #----------------------------------------------------------------------------------
   # Note: If found in mod_promo_url, MODNAME will be replaced with module name...
   #       And so...
   #       http://example.com/myscript.php?show_mod=MODNAME
   #
   #       is included as...
   #       http://example.com/myscript.php?show_mod=$mod
   #----------------------------------------------------------------------------------

   # Remote promo page: default or host-specified?
   if ( $_SESSION['hostco']['mod_promo'] == "custom" && $_SESSION['hostco']['mod_promo_url'] != "" ) {
      $remote_promo = ereg_replace("MODNAME", $mod, $_SESSION['hostco']['mod_promo_url']);
		if ( !include_r($remote_promo) ) { echo "Could not include remote promo script:<br>"; echo "[".$remote_promo."]"; exit; }
   } else {
      $remote_promo = "feature_promo.php";
		if ( !include($remote_promo) ) { echo "Could not include remote promo script:<br>"; echo "[".$remote_promo."]"; exit; }
   }

   # Include promo url now

   //echo "If this loaded fast: '<u>damnit</u>'<br>";


# SHOW UPGRADE FORM
} elseif ( $todo == "upgrade_form" ) {
   include("upgrade_form.php");
}

echo "</div>\n"; // End scrollable div



?>
