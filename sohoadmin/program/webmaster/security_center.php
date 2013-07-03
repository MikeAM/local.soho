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
require_once("../includes/product_gui.php");

$MOD_TITLE = "Security Center";
#######################################################
### Read current info from site_specs
#######################################################
if($_REQUEST['remove_ban'] > 0){
	mysql_query("delete from ip_bans where prikey='".$_REQUEST['remove_ban']."'");	
}
$_POST['iptoban']=preg_replace('/[^0-9\.]/','',$_POST['iptoban']);
if(strlen($_POST['iptoban']) > 2){
	if($_POST['iptoban']==$_SERVER['REMOTE_ADDR']||$_POST['iptoban']==$_SERVER['SERVER_ADDR']){
		echo "<script>alert('You can\'t ban your own IP!');</script>\n";
	} else {
		if($_POST['ban_expires'] > 0){
			$ban_expires=time()+(86400 * $_POST['ban_expires']);
		}
		mysql_query("insert into ip_bans (ip_address, time, ban_expires, reason) values('".$_POST['iptoban']."', '".time()."', '".$ban_expires."', '".$_POST['reason']."')");
	}
}


if(!table_exists("ip_bans")){
	create_table("ip_bans");
}
if(!table_exists("login_attempts")){
	create_table("login_attempts");
}
$checkbanned = mysql_query("SELECT * FROM ip_bans where ban_expires > '".time()."' or ban_expires=''");
	
if(mysql_num_rows($checkbanned) > 0){
	while($banned_ip_info = mysql_fetch_assoc($checkbanned)){
		$banned_users[]=$banned_ip_info;
	}
}

$failedlogins = mysql_query("SELECT * FROM login_attempts where time < '".strtotime('-31 days')."' and ip_address!='".$_SERVER['REMOTE_ADDR']."'");

//$failedlogins = mysql_query("SELECT * FROM login_attempts where time < '".strtotime('-31 days')."'");
if(mysql_num_rows($failedlogins) > 0){
	while($failed_logins_ar = mysql_fetch_assoc($failedlogins)){
		$failed_logins[]=$failed_logins_ar;
	}
}

# Start buffering output
ob_start();
# Webmaster nav button row
//echo include("webmaster_nav_buttons.inc.php");

?>
<style>
.item1 td, .item2 td, th { padding:2px 6px; }
.item1 td {
	background-color:#F2F2F2;
}
.item2 td {
	background-color:#FFFFFF;
}
</style>
<form name="banipform" action="security_center.php" method="POST">
<fieldset style="width:95%;" class="all_inline_labels" id="banned_ips">
<legend>Ban An IP Address</legend>
<table cellspacing=5 cellpadding=5>
	<tr style="vertical-align:top;">
		<td>IP<br/>
		<input type="text" name="iptoban" value=""></td>
		<td>Ban Expiration in days<br/>		
			<input type="text" name="ban_expires" style="width:40px;" value=""><br/>
			<span style="font-size:10px;"><i>(leave blank for permanent ban)<i></span></td>
		<td>Reason<br/>
			<input style="width:200px;" type="text" name="reason" value="">
		</td>
		<td><br/><button type="button" class="redButton" onClick="document.banipform.submit();"><span><span>Ban IP</span></span></button></td>
	</tr>
</table>
</fieldset>
</form>
<br/>
<?php

if(count($failed_logins) > 0 ){
	echo "<fieldset style=\"width:95%;\" class=\"all_inline_labels\" id=\"login_attempts\">\n";
	echo "<legend>Failed Admin Login Attempts</legend>\n";

	echo "<table cellspacing=5>\n";
	echo "	<tr>\n";
	echo "		<th style=\"text-align:left;\">&nbsp;</th>\n";
	echo "		<th style=\"text-align:left;\">IP Address</th>\n";
	echo "		<th style=\"text-align:left;\">Username</th>\n";
	echo "		<th style=\"text-align:left;\">Date</th>\n";
	if(function_exists('geoip_record_by_name')){
		echo "		<th style=\"text-align:left;\">Location</th>\n";
	}
	
	echo "	</tr>\n";
	$banned_class='item1';
	foreach($failed_logins as $val){		
		echo "<tr class=\"".$banned_class."\">\n";
		if($banned_class=='item1'){ $banned_class='item2'; } else { $banned_class='item1'; }
		echo "<td>\n";
		
		echo "<form style=\"display:inline\" name=\"banthis".$val['PRIKEY']."\" action=\"security_center.php\" method=\"POST\">\n";
		echo "<input type=\"hidden\" name=\"ban_expires\" value=\"\">\n";
		echo "<input type=\"hidden\" name=\"iptoban\" value=\"".$val['ip_address']."\">\n";
		echo "<input type=\"hidden\" name=\"reason\" value=\"failed admin login attempt\">\n";
		echo "</form>\n";
		
		echo "<a href=\"javascript:void(0);\" onClick=\"document.banthis".$val['PRIKEY'].".submit();\">ban</a>\n";
		echo "</td>\n";
		echo "<td>\n";
		echo "".$val['ip_address']."</td>\n";
		if($val['ban_expires']==''){
			$ban_expires = 'permanent';	
		} else {
			$ban_expires = date('F d Y', $val['ban_expires']);
		}
		echo "<td>".$val['username']."</td>\n";
		echo "<td>".$val['date']."</td>\n";
		if(function_exists('geoip_record_by_name')){
			$goipstuffs = geoip_record_by_name($val['ip_address']);
			$location = '';
			if($goipstuffs['country_name']!=''){
				if($goipstuffs['city']!=''){
					$location = $goipstuffs['city'].', ';
				}
				if($goipstuffs['region']!=''){
					$location .= $goipstuffs['region']." ".$goipstuffs['country_name'];
				} else {
					$location .= $goipstuffs['country_name'];
				}
			}
			echo "<td>".$location."</td>\n";
		}
		echo "</tr>\n";
	}
	echo "</table>\n";
	echo "</fieldset>\n";
}

echo "<br/>";
echo "<fieldset style=\"width:95%;\" class=\"all_inline_labels\" id=\"banned_ips\">\n";
echo "<legend>Banned IPs</legend>\n";
if(count($banned_users) == 0 ){
	echo "none";
} else {
	echo "<table cellspacing=0>\n";
	echo "	<tr>\n";
	echo "		<th style=\"text-align:left;\"></th>\n";
	echo "		<th style=\"text-align:left;\">IP Address</th>\n";
	echo "		<th style=\"text-align:left;\">Ban Expiration</th>\n";
	if(function_exists('geoip_record_by_name')){
		echo "		<th style=\"text-align:left;\">Location</th>\n";
	}
	echo "		<th style=\"text-align:left;\">Reason</th>		\n";
	echo "		<th style=\"text-align:left;\">Banned On</th>\n";
	echo "	</tr>\n";
	$banned_class='item1';
	foreach($banned_users as $val){
		echo "<tr class=\"".$banned_class."\">\n";
		if($banned_class=='item1'){ $banned_class='item2'; } else { $banned_class='item1'; }
		echo "<td><a href=\"security_center.php?remove_ban=".$val['prikey']."\">unban</a>\n";
		echo "</td>\n";
		echo "<td>\n";
		echo "".$val['ip_address']."</td>\n";
		if($val['ban_expires']==''){
			$ban_expires = 'permanent';	
		} else {
			$ban_expires = date('F d Y', $val['ban_expires']);
		}
		echo "<td>".$ban_expires."</td>\n";
		if(function_exists('geoip_record_by_name')){
			$goipstuffs = geoip_record_by_name($val['ip_address']);
			$location = '';
			if($goipstuffs['country_name']!=''){
				if($goipstuffs['city']!=''){
					$location = $goipstuffs['city'].', ';
				}
				if($goipstuffs['region']!=''){
					$location .= $goipstuffs['region']." ".$goipstuffs['country_name'];
				} else {
					$location .= $goipstuffs['country_name'];
				}
			}
			echo "<td>".$location."</td>\n";
		}
		echo "<td>".$val['reason']."</td>\n";
		echo "<td>".date('F d Y',$val['time'])."</td>\n";
		
		echo "</tr>\n";
	}
	echo "</table>\n";
}
echo "</fieldset>\n";


# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Here you can manage banned IP addresses.  Banning an IP address will prevent that IP address from viewing your website.");

# Build into standard module template
$module = new smt_module($module_html);
$module->meta_title = lang("Security Center");
$module->add_breadcrumb_link(lang("Webmaster"), "program/webmaster/webmaster.php");
$module->add_breadcrumb_link(lang("Security Center"), "program/webmaster/security_center.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/webmaster-enabled.gif";
$module->heading_text = lang("Security Center");
$module->description_text = $instructions;
$module->add_cssfile("webmaster_global_styles.css");
$module->good_to_go();
?>