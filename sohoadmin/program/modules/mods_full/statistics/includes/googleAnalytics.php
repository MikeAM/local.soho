<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();

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

$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../../../../includes/product_gui.php');
chdir($curdir);


include_once('../../../../includes/smt_module.class.php');
#######################################################
### START HTML/JAVASCRIPT CODE			    ###
#######################################################



echo "<link rel=\"stylesheet\" href=\"http://".$_SESSION['this_ip']."/sohoadmin/program/product_gui.css\">\n";
echo "<link rel=\"stylesheet\" href=\"http://".$_SESSION['this_ip']."/sohoadmin/program/includes/product_interface-ultra.css\">\n";
echo "<link rel=\"stylesheet\" href=\"http://".$_SESSION['this_ip']."/sohoadmin/program/includes/product_buttons-ultra.css\">\n";


$MOD_TITLE = lang("Google Analytics");
$globalprefObj = new userdata('global');

# So you can write straight HTML without having to build every line into a container var (i.e. $disHTML .= "another line of html")
ob_start();

//----------------------------------------
//   _      _    _   ___     _            
//  /_\  __| |__| | | __|_ _| |_ _ _ _  _ 
// / _ \/ _` / _` | | _|| ' \  _| '_| || |
///_/ \_\__,_\__,_| |___|_||_\__|_|  \_, |
//                                   |__/ 
//----------------------------------------
if($_POST['addCode'] == "addCode") {
	$GOOGLE_CODE = slashthis($_POST['google_code_non']);
	$globalprefObj->set('google_analytics_non', $GOOGLE_CODE);
	$GOOGLE_CODE = slashthis($_POST['google_code_secure']);
	$globalprefObj->set('google_analytics_secure', $GOOGLE_CODE);
}

# Update for < Ultra v1.0 - Only non-secure is needed, so sync them
$GOOGLE_secure_CODE = trim($globalprefObj->get('google_analytics_secure'));
if ( $GOOGLE_secure_CODE != '' && trim($globalprefObj->get('google_analytics_non')) == '' ) {
	$globalprefObj->set('google_analytics_non', mysql_real_escape_string($GOOGLE_secure_CODE));
}

$GOOGLE_nonsecure_CODE = trim($globalprefObj->get('google_analytics_non'));
$GOOGLE_secure_CODE = trim($globalprefObj->get('google_analytics_secure'));
?>

<script language="javascript">
	function checkNgo(type) {
		document.googleCode.submit()
	}
</script>
<html style="background:#FFFFFF;">
<head>
<style type="text/css">
textarea#google_code_non {
	margin-top: 5px;
	width: 650px;
	height: 175px;
}
img + p { margin-top: 0; }
</style>
<body style="background: #fff;">
<table class="text" width='95%' cellspacing='0' cellpadding='0' border='0' align='center' style='font-size: 13px; overflow: scroll; margin-top: 10px;'>
   <tr>
      <td valign='top'>
      	<img src="../analytics_logo.gif"/>
         <p>Google Analyitics is powerful web stats program provided free by Google. 
         	Follow the steps below to get set up, and soon you will be learning a ton of useful information about how people find and browse your website.</p>
         <h3 style="margin-bottom: -10px;">1. How this works:</h3>
         <ol>
            <li>Create a Google Analytics account <a href="http://www.google.com/analytics" target="_BLANK">here</a>.</li>
            <li>Follow Google's directions to setup a profile for your website.</li>
            <li>Copy the tracking code Google gives you and paste it below.</li>
            <li><span class="green">Save and your done!</span></li>
            <li><b>Note:</b> Once you save your code you can test to make sure it is working by clicking 'Check Status' within <a href="google.com/analytics">Google Analytics</a>.</li>
         </ol>
         
         <form action="googleAnalytics.php" method="post" name="googleCode">
            <input type="hidden" name="addCode" value="addCode">
            <h3 style="font-size:14px;margin-bottom:0;">2. Google Analytics Tracking Code:</h3>
            <textarea id="google_code_non" class="tfield" name="google_code_non" style="overflow: auto;"><? echo $GOOGLE_nonsecure_CODE; ?></textarea>

            <textarea id="google_code_secure" class="tfield" name="google_code_secure" cols="100" rows="6" style="overflow: auto;display: none;"><? echo $GOOGLE_secure_CODE; ?></textarea>
            <p><button class="redButton" type="button" onClick="document.location.href='../../statistics.php';"><span><span>Cancel</span></span></button>&nbsp;&nbsp;&nbsp;&nbsp;<button class="greenButton" type="button" onClick="checkNgo('secure')"><span><span>Save</span></span></button></p>
         </form>
      </td>
   </tr>
</tbody>
</table>

</body>
<?php

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();
echo $module_html;
//$module = new smt_module($module_html);
//$module->meta_title = "Google Analytics Settings";
//$module->add_breadcrumb_link("Traffic Statistics", "program/modules/mods_full/statistics.php");
//$module->add_breadcrumb_link("Google Analytics Settings", "program/modules/mods_full/statistics/includes/googleAnalytics.php");
//$module->icon_img = "program/modules/mods_full/statistics/analytics_module.jpg";
//$module->heading_text = "Google Analytics Settings";
//$module->description_text = "Setup your Google site analytics here.";
//$module->good_to_go();

?>
