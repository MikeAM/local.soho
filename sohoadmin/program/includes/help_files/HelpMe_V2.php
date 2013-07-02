<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }



###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.7
##
## Author:        Joe Lain
## Homepage:      http://www.soholaunch.com
## Bug Reports:   http://bugz.soholaunch.com
###############################################################################

##############################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2005 Soholaunch.com, Inc.  All Rights Reserved.
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


/*------------------------------------------------------------------------------------------------------------------------------------------*
				 _    _      _          _____           _            
				| |  | |    | |        / ____|         | |           
				| |__| | ___| |_ __   | |     ___ _ __ | |_ ___ _ __ 
				|  __  |/ _ \ | '_ \  | |    / _ \ '_ \| __/ _ \ '__|
				| |  | |  __/ | |_) | | |___|  __/ | | | ||  __/ |   
				|_|  |_|\___|_| .__/   \_____\___|_| |_|\__\___|_|   
				              | |                                    
				              |_|                                    
/*------------------------------------------------------------------------------------------------------------------------------------------*/

session_start();
error_reporting(E_PARSE);

# Primary interface include
include_once("../product_gui.php");
include_once("../autoupdate_functions.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Help Center</title>
<link href="../../product_gui.css" rel="stylesheet" type="text/css">
<script src="includes/prototype.js" type="text/javascript"></script>
<script src="includes/scriptaculous.js" type="text/javascript"></script>

<?
# Basic styles
echo "<style>\n";
echo ".text {\n";
echo "   font-family: verdana, arial, helvetica, sans-serif;\n";
echo "   font-size: 11px;\n";
echo "   color: #000000;\n";
echo "}\n\n";

echo "table.blue_fbox { \n";
echo "   border: 0px solid #6699CC;\n";
echo "   border-style: solid solid solid solid;\n";
echo "   font-family: verdana, arial, helvetica, sans-serif;\n";
echo "   font-size: 12px;\n";
echo "   background: #f8f9fd;\n";
echo "}\n";

echo "td.sub_hdr {\n";
echo "   font-family: tahoma, verdana, arial, helvetica, sans-serif;\n";
echo "   font-size: 11px;\n";
echo "   color: #000000;\n";
echo "   font-weight: 600;\n";
echo "   letter-spacing: 1px;\n";
echo "   border-bottom: 1px solid #6699CC;\n";
echo "   background: #DFECF6;\n";
echo "   padding: 4px;\n";
echo "}\n";

echo ".noDec {\n";
echo "	text-decoration: none;\n";
echo "}\n";
echo "a.noDec {\n";
echo "	text-decoration: none;\n";
echo "	color: #000000;\n";
echo "}\n";

echo "a:link { color: #666666; text-decoration: none; }\n";
echo "a:visited { color: #666666; text-decoration: none; }\n";
echo "a:hover { color: #018313; text-decoration: none; }\n";
echo "a:active { color: #a5c6e6; text-decoration: none; }\n";




echo "</style>\n";



   /*--------------------------------------------------------------------------------------------------------------------------------------*
       ___      _     _              ___                   _          _
      | __|___ | | __| | ___  _ _   | _ \ ___  _ _  _ __  (_) ___ ___(_) ___  _ _   ___
      | _|/ _ \| |/ _` |/ -_)| '_|  |  _// -_)| '_|| '  \ | |(_-<(_-<| |/ _ \| ' \ (_-<
      |_| \___/|_|\__,_|\___||_|    |_|  \___||_|  |_|_|_||_|/__//__/|_|\___/|_||_|/__/

   /*--------------------------------------------------------------------------------------------------------------------------------------*/
   ########################################################################################################
   # Function to check permisions and ownership of key write directories
   ########################################################################################################
   function chkDir($dir) {
   	
   		$php_user = shell_exec("whoami");
   		$php_user = rtrim($php_user);
   		$php_suexec = php_sapi_name();
   	

      // Build 'chk' array of raw data ['data'] and individual problem-potenial rating ['color']
      //================================================================================================      
      
      # Permissions
      $chk['perms']['data'] = substr(sprintf('%o', fileperms($dir)), -3);

   		$owner_num = $chk['perms']['data']{0};
   		$group_num = $chk['perms']['data']{1};
   		$other_num = $chk['perms']['data']{2};
      //echo "file owner (".$php_user.")<br>";
//      echo "php owner (".$php_user.")<br>";
//      echo "phpsuexec (".$php_suexec.")<br>";
      
      # Owner
      $chk['owner']['data'] = posix_getpwuid(fileowner($dir));
      $chk['owner']['data'] = $chk['owner']['data']['name'];
      
      # Group
      $chk['group']['data'] = posix_getpwuid(filegroup($dir));
      $chk['group']['data'] = $chk['group']['data']['name'];

      if($chk['perms']['data'] < 777){
      	if($owner_num < 7){
      		if(!eregi($php_user, $chk['owner']['data'])){
      			$chk['owner']['color'] = "#F75D00";
      		}else{
      			$chk['owner']['color'] = "#339959";
      		}
      	}
      	if($group_num < 7){
      		if(!eregi($php_user, $chk['group']['data'])){
      			$chk['group']['color'] = "#F75D00";
      		}else{
      			$chk['group']['color'] = "#339959";
      		}
      	}
      	if($other_num < 7 ){
      		if(eregi($php_user, $chk['owner']['data']) ){
      			$chk['owner']['color'] = "#339959";
      			$chk['group']['color'] = "#339959";
      		}elseif(eregi($php_user, $chk['group']['data']) ){
      			$chk['group']['color'] = "#339959";
      			$chk['owner']['color'] = "#339959";
      		}else{
      			$chk['owner']['color'] = "#F75D00";
      			$chk['group']['color'] = "#F75D00";
      			$chk['permsReg']['error'] = 1;
      		}
      	}
      }else{
      	if($other_num == 7){
         $chk['owner']['color'] = "#339959";
         $chk['group']['color'] = "#339959";
      	}
      }

      # Writablility
      if ( is_writable($dir) ) {
         $chk['write']['data'] = "yes";
         $chk['write']['color'] = "#339959";
      } else {
         $chk['write']['data'] = "no";
         $chk['write']['color'] = "#D70000";
      }
      

      // Detect possible conflicts with data and build note array
      //=====================================================================================
      #-----------------------------------------------------------
      # Make sure OWNER is root, apache, or nobody if
      # permissions do not allow for GROUP write ability
      #-----------------------------------------------------------
      
      if ( $chk['perms']['data'] < 775 && !eregi($php_user, $chk['owner']['data']) ) {
         //$chk['notes'][] = "Non-standard <b style=\"color: #D70000;\">owner</b> may conflict with lack of <b style=\"color: #D70000;\">group write permissions</b>.";
         $chk['ogReg']['error'] = 1;
         $chk['notes'][] = "Failed Owner";
      }

      #-----------------------------------------------------------
      # Make sure GROUP is root, apache, or nobody if
      # permissions do not allow for PUBLIC write ability
      #-----------------------------------------------------------
      if ( $chk['perms']['data'] < 777 && !eregi($php_user, $chk['group']['data']) ) {
         //$chk['notes'][] = "Non-standard <b style=\"color: #D70000;\">group</b> may conflict with lack of <b style=\"color: #D70000;\">public write permissions</b>.";
         $chk['ogReg']['error'] = 1;
         $chk['notes'][] = "Failed Group";
      }
      if($php_suexec == "cgi"){
      	//echo $owner_num."<br>";
      	$chk['ogReg']['error'] = 0;
      	$chk['permsReg']['error'] = 0;
      	
      	if($chk['perms']['data'] > 755 || $chk['perms']['data'] < 744){
          $chk['write']['data'] = "no";
          $chk['write']['color'] = "#D70000";
          $chk['perms']['error'] = 1;
          $chk['perms']['color'] = "#D70000";
        }else{
         	$chk['write']['data'] = "yes";
         	$chk['write']['color'] = "#339959";
         	$chk['perms']['color'] = "#339959";
        }
        

      	# Make sure owner and group match
      	if(!eregi($php_user, $chk['owner']['data']) || !eregi($php_user, $chk['group']['data'])){
          $chk['write']['data'] = "no";
          $chk['write']['color'] = "#D70000";
      		$chk['og']['error'] = 1;
      	}
      }

      return $chk;
   }
   
   ########################################################################################################
   # Function to strip full path from dirnames (for clean display)
   ########################################################################################################
   function killpath($dir) {
      # Strip path from root to docroot
      $clipped = str_replace($_SESSION['doc_root'], "", $dir);
      
      if(strlen($clipped) < 2){
      	$clipped = "Root Directory (public_html)";
      }

      # Strip /sohoadmin
      //$clipped = str_replace("sohoadmin", "", $clipped);

      return $clipped;
   }


# Where should the tutorials link to?
if ( $_SESSION['hostco']['help_icon'] == "custom" && $_SESSION['hostco']['help_icon_url'] != "" ) { // Pull link from branding options array
   $helpicon_goto = $_SESSION['hostco']['help_icon_url'];

} elseif ( strlen($users_man) > 10 && $_SESSION['hostco']['help_icon'] == "" ) {
   $helpicon_goto = $users_man; // Account for old branding method (host.conf.php)

} else {
   $helpicon_goto = "manual.soholaunch.com"; //  Go to Soholaunch Online Manual
}


## Test host options
foreach($_SESSION['hostco'] as $var=>$val){
   echo "var = (".$var.") val = (".$val.")<br>";
}

##############################
# Install Flash Tutorials
##############################

if($_POST['do'] == "install"){
	
  # Download tutorials
  $dlUpdate = new file_download("http://update.securexfer.net/media/soho-demodemo_tutorials.zip", "../../../filebin/myfile.zip");
  
  # Extract tutorials
  $cwd = getcwd();
  chdir("../../../filebin");
  shell_exec("unzip myfile.zip");
  
  # Create myTutorials.txt
  $tutorials = "myTutorials.txt";
	if ( !file_exists($tutorials) ) {
	  $file = fopen("$tutorials", "w");
	  	fwrite($file, "DemoDemo");
	  fclose($file);	  
	}
	
	# Delete zip
	unlink("myfile.zip");	
	chdir($cwd);
  
}

if($_POST['do'] == "fixPerms"){

	$dirs[] = $doc_root;
	$dirs[] = $doc_root.DIRECTORY_SEPARATOR."images";
	$dirs[] = $doc_root.DIRECTORY_SEPARATOR."media";
	$dirs[] = $doc_root.DIRECTORY_SEPARATOR."template";
	$dirs[] = $doc_root.DIRECTORY_SEPARATOR."tCustom";
	$dirs[] = $doc_root.DIRECTORY_SEPARATOR."sohoadmin".DIRECTORY_SEPARATOR."tmp_content";
	$dirs[] = $doc_root.DIRECTORY_SEPARATOR."sohoadmin".DIRECTORY_SEPARATOR."filebin";
	$dirs[] = $doc_root.DIRECTORY_SEPARATOR."sohoadmin".DIRECTORY_SEPARATOR."program".DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."site_templates".DIRECTORY_SEPARATOR."pages";
	
	$myDir = getcwd();
	$chmodResult = "";
	
	foreach ( $dirs as $dir ) {
		chdir($dir);
		//echo "(".$dir.")<br>";
		if($_POST['type'] == "suexec"){
			if(!chmod($dir, 0755)){
				$chmodResult .= "Could not change permissions of (".$dir.")!<br>";
			}else{
				$chmodResult .= "Changed permissions of (".$dir.") to 755!<br>";
			}
		}else{
//			if(!chgrp($dir, "apache")){
//				echo "something<br>";
//			}else{
//				echo "soemthing else<br>";
//			}
//			if(!chmod($dir, 0777)){
//				echo "Could not change permissions of (".$dir.") to 777!<br><br>";
//			}else{
//				echo "Changed permissions of (".$dir.") to 777!<br><br>";
//			}
		}		
	}
	chdir($myDir);
	
//	echo "<script language=\"javascript\">\n";
//	echo "	$('videos').style.display = 'none';\n";
//	echo "	$('diag').style.display = 'block';\n";
//	echo "	$('titleDisp').innerHTML = 'Diagnostic';\n";
//	echo "</script>\n";
	
}
    
	



$flash_demo_type = $_SESSION['hostco']['flash_demo_type'];
$flash_demo_path = $_SESSION['hostco']['flash_demo_path'];
$flash_demo = $_SESSION['hostco']['flash_demos'];

if(strlen($flash_demo_path) > 3 && strlen($flash_demo_type) > 3 && $flash_demo == "on"){
	
	# Host has specified path and type
	//echo "Demo values set in hostco.<br>";
	$install_disp = 0;
}elseif($flash_demo == "off"){
	
	# Host has not specified path or type
	# Check to see if demos have been downloaded already
	//echo "Demo values not set.<br>";
	$cwd = getcwd();
	chdir("../../../filebin");
	$tutorials = "myTutorials.txt";
	if ( file_exists($tutorials) ) {
		
		# Tutorials installed
		$install_disp = 0;
	  $file = fopen("$tutorials", "r");
	  	$flash_demo_type = fread($file,filesize($tutorials));
	  fclose($file);
	}else{
		
		# Tutorials not installed
		$install_disp = 1;
	}
	chdir($cwd);
	

}
	

?>




<script language="javascript">

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}


function showLayer(daLayer){
	
	if(daLayer == 'diag'){
		if($(daLayer).style.display == 'block'){
			$(daLayer).style.display == 'none';
		}else{
			$('titleDisp').innerHTML = 'Diagnostic';
			$(daLayer).style.display = 'block';
			$('videos').style.display = 'none';
			$('faq').style.display = 'none';
		}
	}
	if(daLayer == 'videos'){
		if($(daLayer).style.display == 'block'){
			$(daLayer).style.display == 'none';
		}else{
			$('titleDisp').innerHTML = 'Flash Tutorials';
			$(daLayer).style.display = 'block';
			$('diag').style.display = 'none';
			$('faq').style.display = 'none';
		}
	}
	if(daLayer == 'faq'){
		if($(daLayer).style.display == 'block'){
			$(daLayer).style.display == 'none';
		}else{
			$(daLayer).style.display = 'block';
			$('titleDisp').innerHTML = 'Users Manual';
			$('videos').style.display = 'none';
			$('diag').style.display = 'none';
		}
	}
}

function showGlobals(daType){
	if(daType == 'globals'){
		if(document.getElementById('globalDisp').value == '1'){
			document.getElementById('globalDisp').value = '0';
			new Effect.BlindUp(document.getElementById('globals'));
		}else{
			document.getElementById('globalDisp').value = '1';
			new Effect.BlindDown(document.getElementById('globals'));
			new Effect.BlindUp(document.getElementById('perms'));
			new Effect.BlindUp(document.getElementById('server'));
		}
		document.getElementById('permDisp').value = '0';
		document.getElementById('serverDisp').value = '0';
	}
	if(daType == 'perms'){
		if(document.getElementById('permDisp').value == '1'){
			document.getElementById('permDisp').value = '0';
			new Effect.BlindUp(document.getElementById('perms'));
		}else{
			document.getElementById('permDisp').value = '1';
			new Effect.BlindDown(document.getElementById('perms'));
			new Effect.BlindUp(document.getElementById('globals'));
			new Effect.BlindUp(document.getElementById('server'));
		}
		document.getElementById('globalDisp').value = '0';
		document.getElementById('serverDisp').value = '0';
	}
	if(daType == 'server'){
		if(document.getElementById('serverDisp').value == '1'){
			document.getElementById('serverDisp').value = '0';
			new Effect.BlindUp(document.getElementById('server'));
		}else{
			document.getElementById('serverDisp').value = '1';
			new Effect.BlindDown(document.getElementById('server'));
			new Effect.BlindUp(document.getElementById('globals'));
			new Effect.BlindUp(document.getElementById('perms'));
		}
		document.getElementById('permDisp').value = '0';
		document.getElementById('globalDisp').value = '0';
	}
}

function clearEffects(){
	new Effect.Appear(document.getElementById('toolsImg'));
	new Effect.Appear(document.getElementById('wheelImg'));
	new Effect.Appear(document.getElementById('checkImg'));
}

function showInfo(infoDiv, infoType, infoImg){
	if(infoType == 'flash'){
		document.getElementById(infoDiv).innerHTML = 'View flash tutorials.';
		document.getElementById('wheelTD').style.background = 'url(images/green/icon-BG.gif)';
		document.getElementById('wheelIMG').src = 'images/green/wheel-icon.gif';
		document.getElementById('wheelTXT').src = 'images/green/flash-text.gif';
	}
	if(infoType == 'diag'){
		document.getElementById(infoDiv).innerHTML = 'The diagnostic report can help pinpoint issues with your installation.';
		document.getElementById('toolsTD').style.background = 'url(images/green/icon-BG-23.gif)';
		document.getElementById('toolsIMG').src = 'images/green/tools-icon.gif';
		document.getElementById('toolsTXT').src = 'images/green/diag-text.gif';
	}
	if(infoType == 'manual'){
		document.getElementById(infoDiv).innerHTML = 'View our online users manual.';
		document.getElementById('checkTD').style.background = 'url(images/green/icon-BG-23.gif)';
		document.getElementById('checkIMG').src = 'images/green/check-icon.gif';
		document.getElementById('checkTXT').src = 'images/green/manual-text.gif';
	}
	if(infoType == 'home'){
		//alert(infoImg);
		document.getElementById(infoDiv).innerHTML = 'Welcome to the Help Center!  Here you can watch flash tutorials, view diagnostic reports and refrence our online users manual.';
		if(infoImg == 'wheel'){
			document.getElementById('wheelTD').style.background = 'url(images/green/icon-BG-grey.gif)';
			document.getElementById('wheelIMG').src = 'images/green/wheel-icon-grey.gif';
			document.getElementById('wheelTXT').src = 'images/green/flash-text-grey.gif';
		}
		if(infoImg == 'tools'){
			document.getElementById('toolsTD').style.background = 'url(images/green/icon-BG-23-grey.gif)';
			document.getElementById('toolsIMG').src = 'images/green/tools-icon-grey.gif';
			document.getElementById('toolsTXT').src = 'images/green/diag-text-grey.gif';
		}
		if(infoImg == 'check'){
			document.getElementById('checkTD').style.background = 'url(images/green/icon-BG-23-grey.gif)';
			document.getElementById('checkIMG').src = 'images/green/check-icon-grey.gif';
			document.getElementById('checkTXT').src = 'images/green/manual-text-grey.gif';
		}
		
	}
	//alert($(disInfo).innerHTML);
}

</script>

</head>

<body onload="MM_preloadImages('images/green/check-icon.gif','images/green/check-icon-grey.gif','images/green/diag-text.gif','images/green/diag-text-grey.gif','images/green/flash-text.gif','images/green/flash-text-grey.gif','images/green/head-main-grey.gif','images/green/icon-BG-23.gif','images/green/icon-BG-23-grey.gif','images/green/icon-BG.gif','images/green/icon-BG-grey.gif','images/green/manual-text.gif','images/green/manual-text-grey.gif','images/green/tools-icon.gif','images/green/tools-icon-grey.gif','images/green/wheel-icon.gif','images/green/wheel-icon-grey.gif')">

<?



$daHtml = "";

$daHtml .= "<input type=\"hidden\" id=\"globalDisp\" value=\"1\">\n";
$daHtml .= "<input type=\"hidden\" id=\"permDisp\" value=\"0\">\n";
$daHtml .= "<input type=\"hidden\" id=\"serverDisp\" value=\"0\">\n";

$daHtml .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
$daHtml .= "  <tr>\n";
$daHtml .= "    <td width=\"15%\" align=\"right\" valign=\"top\" style=\"border: 1px solid #666666; border-style: solid solid none solid;\">\n";

#################################
###		Icon Table
#################################

$daHtml .= "        <table width=\"780\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
$daHtml .= "          <tr>\n";
$daHtml .= "            <td width=\"500\" height=\"70\"><img src=\"images/green/head-main-grey.gif\" width=\"481\" height=\"70\" /></td>\n";
$daHtml .= "            <td id=\"wheelTD\" align=\"center\" valign=\"bottom\" onclick=\"showLayer('videos');\" onMouseOver=\"showInfo('tellMeNow', 'flash', 'none');\" onMouseOut=\"showInfo('tellMeNow', 'home', 'wheel');\" style=\"background:url(images/green/icon-BG-grey.gif);\"><img id=\"wheelIMG\" src=\"images/green/wheel-icon-grey.gif\" onclick=\"showLayer('videos');\" onMouseOver=\"showInfo('tellMeNow', 'flash', 'none');\" onMouseOut=\"showInfo('tellMeNow', 'home', 'wheel');\" style=\"cursor:pointer;\" width=\"62\" height=\"61\" /></td>\n";
$daHtml .= "            <td id=\"toolsTD\" align=\"center\" valign=\"bottom\" onclick=\"showLayer('diag');\" onMouseOver=\"showInfo('tellMeNow', 'diag', 'none');\" onMouseOut=\"showInfo('tellMeNow', 'home', 'tools');\" background=\"images/green/icon-BG-23-grey.gif\"><img id=\"toolsIMG\" src=\"images/green/tools-icon-grey.gif\" onclick=\"showLayer('diag');\" onMouseOver=\"showInfo('tellMeNow', 'diag', 'none');\" onMouseOut=\"showInfo('tellMeNow', 'home', 'tools');\" style=\"cursor:pointer;\" width=\"60\" height=\"61\" /></td>\n";
$daHtml .= "            <td id=\"checkTD\" align=\"center\" valign=\"bottom\" onclick=\"showLayer('faq');\" onMouseOver=\"showInfo('tellMeNow', 'manual', 'none');\" onMouseOut=\"showInfo('tellMeNow', 'home', 'check');\" background=\"images/green/icon-BG-23-grey.gif\"><img id=\"checkIMG\" src=\"images/green/check-icon-grey.gif\" onclick=\"showLayer('faq');\" onMouseOver=\"showInfo('tellMeNow', 'manual', 'none');\" onMouseOut=\"showInfo('tellMeNow', 'home', 'check');\" style=\"cursor:pointer;\" width=\"62\" height=\"61\" /></td>\n";
$daHtml .= "          </tr>\n";
$daHtml .= "          <tr>\n";
$daHtml .= "            <td height=\"36\" align=\"left\" valign=\"top\" background=\"images/green/head-info-grey.gif\" style=\"padding-left: 20px; padding-right: 10px;\">\n";
$daHtml .= "        			<div id=\"tellMeNow\" style=\"font-size: 10px; font-family: verdana, arial, helvetica, sans-serif; font-weight: bold; color: #666666;\">\n";
$daHtml .= "        				Welcome to the Help Center!  Here you can watch flash tutorials, view diagnostic reports and refrence our online users manual.\n";
$daHtml .= "        			</div>\n";
$daHtml .= "        		</td>\n";
$daHtml .= "            <td><img id=\"wheelTXT\" src=\"images/green/flash-text-grey.gif\" onclick=\"showLayer('videos');\" onMouseOver=\"showInfo('tellMeNow', 'flash', 'none');\" onMouseOut=\"showInfo('tellMeNow', 'home', 'wheel');\" style=\"cursor:pointer;\" width=\"100\" height=\"36\" /></td>\n";
$daHtml .= "            <td><img id=\"toolsTXT\" src=\"images/green/diag-text-grey.gif\" onclick=\"showLayer('diag');\" onMouseOver=\"showInfo('tellMeNow', 'diag', 'none');\" onMouseOut=\"showInfo('tellMeNow', 'home', 'tools');\" style=\"cursor:pointer;\" width=\"100\" height=\"36\" /></td>\n";
$daHtml .= "            <td><img id=\"checkTXT\" src=\"images/green/manual-text-grey.gif\" onclick=\"showLayer('faq');\" onMouseOver=\"showInfo('tellMeNow', 'manual', 'none');\" onMouseOut=\"showInfo('tellMeNow', 'home', 'check');\" style=\"cursor:pointer;\" width=\"100\" height=\"36\" /></td>\n";
$daHtml .= "          </tr>\n";
$daHtml .= "          <tr>\n";
$daHtml .= "            <td id=\"titleDisp\" align=\"right\" height=\"31\" valign=\"middle\" style=\"font-size: 14px; background:url(images/green/head-bott-left.gif); font-family: verdana, arial, helvetica, sans-serif; font-weight: bold; color: #079118; background-repeat: no-repeat; padding-right: 50px;\">Flash Videos</td>\n";
$daHtml .= "            <td colspan=\"3\" align=\"right\"><img src=\"images/green/head-bott-right.gif\" width=\"65\" height=\"31\" /></td>\n";
$daHtml .= "          </tr>\n";
$daHtml .= "        </table>\n";

//
//$daHtml .= "	<table width=\"400\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
//$daHtml .= "      <tr>\n";
//
//$daHtml .= "        <td width=\"15%\" align=\"center\" style=\"padding-top: 20px;\">\n";
//$daHtml .= "        	<img id=\"wheelImg\" src=\"images/wheel-blue-v2-light.gif\" style=\"cursor:pointer;\" onclick=\"showLayer('videos');\" width=\"80\" height=\"80\"><br>Flash Tutorials</td>\n";
//
//$daHtml .= "        <td width=\"15%\" align=\"center\" style=\"padding-top: 20px;\">\n";
//$daHtml .= "        <img id=\"toolsImg\" src=\"images/tools-blue-v2-light.gif\" style=\"cursor:pointer;\" onclick=\"showLayer('diag');\" width=\"80\" height=\"85\"><br>Diagnostic</td>\n";
//
//$daHtml .= "        <td width=\"15%\" align=\"center\" style=\"padding-top: 20px;\">\n";
//$daHtml .= "        <img id=\"checkImg\" src=\"images/check-blue-v2-light.gif\" style=\"cursor:pointer;\" onclick=\"showLayer('faq');\" width=\"80\" height=\"77\"><br>Users Manual</td>\n";
//
//$daHtml .= "      </tr>\n";
//$daHtml .= "    </table>\n";
$daHtml .= "	</td>\n";
$daHtml .= "	</tr>\n";

#################################
###		Begin Display Area
#################################

$daHtml .= "	<tr>\n";
$daHtml .= "    <td colspan=\"2\" valign=\"top\" style=\"border: 1px solid #666666; border-style: none solid solid solid;\">\n";

#################################
###		Videos Display
#################################

$daHtml .= "			<div id=\"videos\" style=\"display:block;\">\n";

# Are tutorials installed?
if($install_disp == 1){
	
	#Tutorials not installed, show install screen
	$daHtml .= "         <form method=post action=\"HelpMe_V2.php\">\n";
	$daHtml .= "				 <input type=\"hidden\" name=\"do\" value=\"install\">\n";
	$daHtml .= "          <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
	$daHtml .= "            <tr>\n";
	$daHtml .= "              <td colspan=\"4\" height=\"20\" style=\"padding-left:5px;\">Our collection of flash videos should help you with any questions you have about ".$_SESSION['hostco']['company_name']." </td>\n";
	$daHtml .= "            </tr>\n";
	$daHtml .= "            <tr>\n";
	$daHtml .= "              <td align=\"center\" colspan=\"4\" height=\"20\" style=\"padding-left:5px;\">\n";
	$daHtml .= "								<input type=\"submit\" value=\"Install Flash Tutorials\">\n";
	$daHtml .= "              </td>\n";
	$daHtml .= "            </tr>\n";
	$daHtml .= "          </table>\n";
	$daHtml .= "				</form>\n";	
	
}else{
	
	#Tutorials installed
	$daHtml .= "          <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
	//$daHtml .= "            <tr>\n";
	//$daHtml .= "              <td colspan=\"4\" height=\"17\" background=\"images/stripe-fill.gif\" style=\"padding-left:5px;\">\n";
	//$daHtml .= "              <img src=\"images/flash-text.gif\" width=\"112\" height=\"17\">\n";
	//$daHtml .= "              </td>\n";
	//$daHtml .= "            </tr>\n";
	$daHtml .= "            <tr>\n";
	$daHtml .= "              <td colspan=\"4\" height=\"20\" style=\"padding-left:5px;\">Our collection of flash videos should help you with any questions you have about ".$_SESSION['hostco']['company_name']." </td>\n";
	$daHtml .= "            </tr>\n";
	
	#################################
	###		DEMOSTORM VIDEOS
	#################################
	
	if($flash_demo_type == "DemoStorm"){
		$daHtml .= "                  <tr>\n";
		$daHtml .= "                    <td colspan=\"2\" class=\"noDec\" width=\"50%\" style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a class=\"noDec\" href=\"display_video.php?swffile=adminonew&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Main Menu</a></td>\n";
		$daHtml .= "                    <td colspan=\"2\" style=\"padding-left:15px;\" align=\"left\" ><a class=\"noDec\" href=\"display_video.php?swffile=texteditornew&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Text Editor</a></td>\n";
		$daHtml .= "                  </tr>\n";
		$daHtml .= "                  <tr>\n";
		$daHtml .= "                    <td colspan=\"2\" style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a class=\"noDec\" href=\"display_video.php?swffile=album_new&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Photo Album Manager</a></td>\n";
		$daHtml .= "                    <td colspan=\"2\" style=\"padding-left:15px;\" align=\"left\" ><a class=\"noDec\" href=\"display_video.php?swffile=templatenew&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Template Manager</a></td>\n";
		$daHtml .= "                  </tr>\n";
		$daHtml .= "                  <tr>\n";
		$daHtml .= "                    <td colspan=\"2\" style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a class=\"noDec\" href=\"display_video.php?swffile=backingupnewg&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Site Backup/Restore</a></td>\n";
		$daHtml .= "                    <td colspan=\"2\" style=\"padding-left:15px;\" align=\"left\" ><a class=\"noDec\" href=\"display_video.php?swffile=statisticsnew&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Site Statistics</a></td>\n";
		$daHtml .= "                  </tr>\n";
		$daHtml .= "                  <tr>\n";
		$daHtml .= "                    <td colspan=\"2\" style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a class=\"noDec\" href=\"display_video.php?swffile=blognewskip&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Blog Manager</a></td>\n";
		$daHtml .= "                    <td colspan=\"2\" style=\"padding-left:15px;\" align=\"left\" ><a class=\"noDec\" href=\"display_video.php?swffile=securenew&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Secure Users</a></td>\n";
		$daHtml .= "                  </tr>\n";
		$daHtml .= "                  <tr>\n";
		$daHtml .= "                    <td colspan=\"2\" style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a class=\"noDec\" href=\"display_video.php?swffile=Calendar_New&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Event Calendar</a></td>\n";
		$daHtml .= "                    <td colspan=\"2\" style=\"padding-left:15px;\" align=\"left\" ><a class=\"noDec\" href=\"display_video.php?swffile=quicknew&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Quick Start Wizard</a></td>\n";
		$daHtml .= "                  </tr>\n";
		$daHtml .= "                  <tr>\n";
		$daHtml .= "                    <td colspan=\"2\" style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a class=\"noDec\" href=\"display_video.php?swffile=cart2shipping&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Shopping Cart - Shipping Options</a></td>\n";
		$daHtml .= "                    <td colspan=\"2\" style=\"padding-left:15px;\" align=\"left\" ><a class=\"noDec\" href=\"display_video.php?swffile=Pageeditornew&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Page Editor</a></td>\n";
		$daHtml .= "                  </tr>\n";
		$daHtml .= "                  <tr>\n";
		$daHtml .= "                    <td colspan=\"2\" style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a class=\"noDec\" href=\"display_video.php?swffile=cartcontactinfo&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Shopping Cart - Contact Information</a></td>\n";
		$daHtml .= "                    <td colspan=\"2\" style=\"padding-left:15px;\" align=\"left\" ><a class=\"noDec\" href=\"display_video.php?swffile=newsletternew&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">eNewsletter Campaign Manager</a></td>\n";
		$daHtml .= "                  </tr>\n";
		$daHtml .= "                  <tr>\n";
		$daHtml .= "                    <td colspan=\"2\" style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a class=\"noDec\" href=\"display_video.php?swffile=cartpayment&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Shopping Cart - Payment Options</a></td>\n";
		$daHtml .= "                    <td colspan=\"2\" style=\"padding-left:15px;\" align=\"left\" ><a class=\"noDec\" href=\"display_video.php?swffile=menunew&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Auto Menu System</a></td>\n";
		$daHtml .= "                  </tr>\n";
		$daHtml .= "                  <tr>\n";
		$daHtml .= "                    <td colspan=\"2\" style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a class=\"noDec\" href=\"display_video.php?swffile=creatingpagennew&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Create Pages</a></td>\n";
		$daHtml .= "                    <td colspan=\"2\" style=\"padding-left:15px;\" align=\"left\" ><a class=\"noDec\" href=\"display_video.php?swffile=Form_Manager_new.swf&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Forms Manager</a></td>\n";
		$daHtml .= "                  </tr>\n";
		$daHtml .= "                  <tr>\n";
		$daHtml .= "                    <td colspan=\"2\" style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a class=\"noDec\" href=\"display_video.php?swffile=database_new&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Database Table Manager</a></td>\n";
		$daHtml .= "                    <td colspan=\"2\" style=\"padding-left:15px;\" align=\"left\" ><a class=\"noDec\" href=\"display_video.php?swffile=fileman_new&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">File Manager</a></td>\n";
		$daHtml .= "                  </tr>\n";
		$daHtml .= "                  <tr>\n";
		$daHtml .= "                    <td colspan=\"2\" style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a class=\"noDec\" href=\"display_video.php?swffile=faqnew&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">FAQ Manager</a></td>\n";
		$daHtml .= "                    <td colspan=\"2\" style=\"padding-left:15px;\" align=\"left\" >&nbsp;</td>\n";
		$daHtml .= "                  </tr>\n";
	}
	
	#################################
	###		DEMODEMO VIDEOS
	#################################
	
	if($flash_demo_type == "DemoDemo"){
		$daHtml .= "                  <tr>\n";
		$daHtml .= "                    <td  width=\"33%\" style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a href=\"display_video.php?swffile=sl_48_addproducttopage&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Shopping Cart - Add Product to Page</a></td>\n";
		$daHtml .= "                    <td  width=\"33%\" style=\"padding-left:15px;\" align=\"left\" ><a href=\"display_video.php?swffile=sl_48_admin&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Main Menu</a></td>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a href=\"display_video.php?swffile=sl_48_automenu&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Auto Menu System</a></td>\n";
		
		$daHtml .= "                  </tr>\n";

		$daHtml .= "                  <tr>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a href=\"display_video.php?swffile=sl_48_blogmanager&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Blog Manager</a></td>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" ><a href=\"display_video.php?swffile=sl_48_cart_addcategory&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Shopping Cart - Add Category</a></td>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a href=\"display_video.php?swffile=sl_48_cart_addproduct&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Shopping Cart - Add Product</a></td>\n";
		
		$daHtml .= "                  </tr>\n";

		$daHtml .= "                  <tr>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a href=\"display_video.php?swffile=sl_48_cart_gateway&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Shopping Cart - Payment Gateways</a></td>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" ><a href=\"display_video.php?swffile=sl_48_cart_info&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Shopping Cart - Contact Information</a></td>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a href=\"display_video.php?swffile=sl_48_cart_policies&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Shopping Cart - Policies Options</a></td>\n";
		$daHtml .= "                  </tr>\n";

		$daHtml .= "                  <tr>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a href=\"display_video.php?swffile=sl_48_cart_shipping&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Shopping Cart - Shipping Options</a></td>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" ><a href=\"display_video.php?swffile=sl_48_createpage&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Create New Pages</a></td>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a href=\"display_video.php?swffile=sl_48_deletepage&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Delete Pages</a></td>\n";
		$daHtml .= "                  </tr>\n";
		$daHtml .= "                  <tr>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a href=\"display_video.php?swffile=sl_48_emailafriend&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Page Editor - Email a Friend</a></td>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" ><a href=\"display_video.php?swffile=sl_48_enewsletter&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">eNewsletter</a></td>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a href=\"display_video.php?swffile=sl_48_eventcalendar&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Event Calendar</a></td>\n";	
		$daHtml .= "                  </tr>\n";
		$daHtml .= "                  <tr>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a href=\"display_video.php?swffile=sl_48_filemanager&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">File Manager</a></td>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" ><a href=\"display_video.php?swffile=sl_48_form&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Forms Manager</a></td>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a href=\"display_video.php?swffile=sl_48_login&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Login</a></td>\n";
		
		$daHtml .= "                  </tr>\n";
		$daHtml .= "                  <tr>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a href=\"display_video.php?swffile=sl_48_metatags&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Meta Tags</a></td>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" ><a href=\"display_video.php?swffile=sl_48_photoalbum&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Photo Albums</a></td>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" ><a href=\"display_video.php?swffile=sl_48_map&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Driving Directions</a></td>\n";
		$daHtml .= "                  </tr>\n";
		$daHtml .= "                  <tr>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" ><a href=\"display_video.php?swffile=sl_48_faqmanager&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">FAQ Manager</a></td>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a href=\"display_video.php?swffile=sl_48_plugin&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Plugin Links</a></td>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" ><a href=\"display_video.php?swffile=sl_48_popup&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Popup Windows</a></td>\n";
		$daHtml .= "                  </tr>\n";
		$daHtml .= "                  <tr>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" ><a href=\"display_video.php?swffile=sl_48_editpage&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Edit Page Content</a></td>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a href=\"display_video.php?swffile=sl_48_sitestats&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Secure Users</a></td>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" ><a href=\"display_video.php?swffile=sl_48_tablemanager&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Site Data Tables</a></td>\n";
		$daHtml .= "                  </tr>\n";
		$daHtml .= "                  <tr>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" ><a href=\"display_video.php?swffile=sl_48_cart_salestax&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Shopping Cart - Tax Options</a></td>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a href=\"display_video.php?swffile=sl_48_secureusers&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Site Statistics</a></td>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" ><a href=\"display_video.php?swffile=sl_48_sitedatatables&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Database Table Manager</a></td>\n";
		$daHtml .= "                  </tr>\n";
		$daHtml .= "                  <tr>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" ><a href=\"display_video.php?swffile=sl_48_cart_displaysettings&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Shopping Cart - Display Settings</a></td>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a href=\"display_video.php?swffile=sl_48_templatemanager&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Template Manager</a></td>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" ><a href=\"display_video.php?swffile=sl_48_texteditor&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Text Editor</a></td>\n";
		$daHtml .= "                  </tr>\n";
		$daHtml .= "                  <tr>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" ><a href=\"display_video.php?swffile=sl_48_backup&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Site Backup / Restore</a></td>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" height=\"30\"><a href=\"display_video.php?swffile=sl_48_wizard&flash_demo_path=".$flash_demo_path."\" target=\"_BLANK\" border=\"0\">Quick Start Wizard</a></td>\n";
		$daHtml .= "                    <td  style=\"padding-left:15px;\" align=\"left\" >&nbsp;</td>\n";
		$daHtml .= "                  </tr>\n";
	}
	
	$daHtml .= "          </table>\n";
	$daHtml .= "        </div>\n";
}

#################################
###		Diagnostic Display
#################################

$daHtml .= "              <div id=\"diag\" style=\"display:none;\">\n";
$daHtml .= "                <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
//$daHtml .= "            <tr>\n";
//$daHtml .= "              <td colspan=\"3\" height=\"17\" background=\"images/stripe-fill.gif\" style=\"padding-left:5px;\">\n";
//$daHtml .= "              <img src=\"images/diag-text.gif\" width=\"112\" height=\"17\">\n";
//$daHtml .= "              </td>\n";
//$daHtml .= "            </tr>\n";
$daHtml .= "                  <tr>\n";
$daHtml .= "                    <td colspan=\"3\" height=\"20\" style=\"padding-left:5px;\">If you are experiencing trouble with ".$_SESSION['hostco']['company_name'].", here are a list of items you may want to check.</td>\n";
$daHtml .= "                  </tr>\n";

#################################
###		Global Settings
#################################

$daHtml .= "									<!-- GLOBAL SETTINGS -->\n";
$daHtml .= "\n";
$daHtml .= "                  <tr>\n";
$daHtml .= "                    <td colspan=\"4\" class=\"fsub_title\" style=\"cursor: pointer; background:url(images/green/diag-title-row.gif);\" onclick=\"showGlobals('globals');\">Global Settings (isp.conf.php) </td>\n";
$daHtml .= "                  </tr>\n";
$daHtml .= "                  <tr>\n";
$daHtml .= "                    <td colspan=\"4\" align=\"center\">\n";
$daHtml .= "					<div id=\"globals\" style=\"display:block;\">\n";
$daHtml .= "						<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">\n";
$daHtml .= "                          <tr>\n";
$daHtml .= "                            <td align=\"left\" class=\"col_sub\">Setting</td>\n";
$daHtml .= "                            <td align=\"left\" width=\"50%\" class=\"col_sub\">Value</td>\n";
$daHtml .= "                            <td align=\"center\" class=\"col_sub\">Status</td>\n";
$daHtml .= "                          </tr>\n";
$daHtml .= "                          <tr>\n";

								###		this_ip   ###
								
$daHtml .= "                            <td align=\"left\">this_ip</td>\n";
$daHtml .= "                            <td align=\"left\">".$this_ip."</td>\n";
if(strlen($this_ip) < 2){
	$ip_status = "Failed";
}else{
	$ip_status = "OK";
}
$daHtml .= "                            <td align=\"center\">".$ip_status."</td>\n";
$daHtml .= "                          </tr>\n";
$daHtml .= "                          <tr>\n";

								###		cgi_bin   ###
								
$daHtml .= "                            <td align=\"left\">cgi_bin</td>\n";
$daHtml .= "                            <td align=\"left\">".$cgi_bin."</td>\n";
if(strlen($cgi_bin) < 2){
	$cgi_status = "Failed";
}else{
	$cgi_status = "OK";
}
$daHtml .= "                            <td align=\"center\">".$cgi_status."</td>\n";
$daHtml .= "                          </tr>\n";
$daHtml .= "                          <tr>\n";

								###		doc_root   ###
								
$daHtml .= "                            <td align=\"left\">doc_root</td>\n";
$daHtml .= "                            <td align=\"left\">".$doc_root."</td>\n";
if(strlen($doc_root) < 2){
	$root_status = "Failed";
}else{
	$root_status = "OK";
}
$daHtml .= "                            <td align=\"center\">".$root_status."</td>\n";
$daHtml .= "                          </tr>\n";
$daHtml .= "                          <tr>\n";

								###		dflogin_user   ###
								
$daHtml .= "                            <td align=\"left\">dflogin_user</td>\n";
$daHtml .= "                            <td align=\"left\">".$dflogin_user."</td>\n";
if(strlen($dflogin_user) < 2){
	$user_status = "Failed";
}else{
	$user_status = "OK";
}
$daHtml .= "                            <td align=\"center\">".$user_status."</td>\n";
$daHtml .= "                          </tr>\n";
$daHtml .= "                          <tr>\n";

								###		dflogin_pass   ###
								
$daHtml .= "                            <td align=\"left\">dflogin_pass</td>\n";
$daHtml .= "                            <td align=\"left\">".$dflogin_pass."</td>\n";
if(strlen($dflogin_pass) < 2){
	$pass_status = "Failed";
}else{
	$pass_status = "OK";
}
$daHtml .= "                            <td align=\"center\">".$pass_status."</td>\n";
$daHtml .= "                          </tr>\n";
$daHtml .= "                          <tr>\n";

								###		template_lib   ###
								
$daHtml .= "                            <td align=\"left\">template_lib</td>\n";
$daHtml .= "                            <td align=\"left\">".$template_lib."</td>\n";
if(strlen($template_lib) < 2){
	$tmplt_status = "Failed";
}else{
	$tmplt_status = "OK";
}
$daHtml .= "                            <td align=\"center\">".$tmplt_status."</td>\n";
$daHtml .= "                          </tr>\n";
$daHtml .= "	                      <tr>\n";

								###		demo_site   ###
								
$daHtml .= "                    	      <td align=\"left\">demo_site</td>\n";
$daHtml .= "	            	          <td align=\"left\">\n";
if(strlen($demo_site) < 2){
	$demo_site = "no";
}
$daHtml .= "													".$demo_site."\n";
$daHtml .= "													</td>\n";
$daHtml .= "    	    	              <td align=\"center\">OK</td>\n";
$daHtml .= "        	              </tr>\n";
$daHtml .= "                   	  </table>\n";
$daHtml .= "                    </div></td>\n";
$daHtml .= "                  </tr>\n";

#################################
###		Permissions Display
#################################

$daHtml .= "									<!-- PERMISSIONS -->\n";
$daHtml .= "\n";
$daHtml .= "                  <tr>\n";
$daHtml .= "                    <td colspan=\"4\" class=\"fsub_title\" style=\"cursor: pointer; background:url(images/green/diag-title-row.gif);\" onclick=\"showGlobals('perms');\">Permissions</td>\n";
$daHtml .= "                  </tr>\n";
$daHtml .= "                  <tr>\n";
$daHtml .= "                    <td colspan=\"4\" align=\"center\">\n";
$daHtml .= "										<div id=\"perms\" style=\"display:none;\">\n";
$daHtml .= "											<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">\n";

 # Column headings
$daHtml .= " 													<tr>\n";
$daHtml .= "                            <td align=\"left\" width=\"25%\" class=\"col_sub\"><strong>Folder</strong></td>\n";
$daHtml .= "                            <td align=\"center\" class=\"col_sub\"><strong>Permissions </strong></td>\n";
$daHtml .= "                            <td align=\"center\" class=\"col_sub\"><strong>Owner</strong></td>\n";
$daHtml .= "                            <td align=\"center\" class=\"col_sub\"><strong>Group</strong></td>\n";
$daHtml .= "                            <td align=\"center\" class=\"col_sub\"><strong>Writeable</strong></td>\n";
$daHtml .= "                            <td align=\"center\" class=\"col_sub\"><strong>Status</strong></td>\n";
$daHtml .= " 													<tr>\n";


 # DIRECTORIES to check
 $dirChks[] = $doc_root;
 $dirChks[] = $doc_root.DIRECTORY_SEPARATOR."images";
 $dirChks[] = $doc_root.DIRECTORY_SEPARATOR."media";
 $dirChks[] = $doc_root.DIRECTORY_SEPARATOR."template";
 $dirChks[] = $doc_root.DIRECTORY_SEPARATOR."tCustom";
 $dirChks[] = $doc_root.DIRECTORY_SEPARATOR."sohoadmin".DIRECTORY_SEPARATOR."tmp_content";
 $dirChks[] = $doc_root.DIRECTORY_SEPARATOR."sohoadmin".DIRECTORY_SEPARATOR."filebin";
 $dirChks[] = $doc_root.DIRECTORY_SEPARATOR."sohoadmin".DIRECTORY_SEPARATOR."program".DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."site_templates".DIRECTORY_SEPARATOR."pages";


 $showFix = 0;
 $dis_user = 
 $php_user = shell_exec("whoami");
 $php_user = rtrim($php_user);
 $php_suexec = php_sapi_name();
 
 # Loop through dir list and output result rows
 #-------------------------------------------------
 foreach ( $dirChks as $dir ) {
    $chk = chkDir($dir);
    $daHtml .= " <tr>\n";

    # Directory
    $daHtml .= "  <td align=\"left\">".killpath($dir)."</td>\n";

    # Permissions
    $daHtml .= "  <td align=\"center\" style=\"color: ".$chk['perms']['color'].";\">".$chk['perms']['data']."</td>\n";

    # Owner
    $daHtml .= "  <td align=\"center\" style=\"color: ".$chk['owner']['color'].";\">".$chk['owner']['data']."</td>\n";

    # Group
    $daHtml .= "  <td align=\"center\" style=\"color: ".$chk['group']['color'].";\">".$chk['group']['data']."</td>\n";

    # Writeable
    $daHtml .= "  <td align=\"center\" style=\"color: ".$chk['write']['color'].";\">".$chk['write']['data']."</td>\n";

    # Conflict Notes
      $daHtml .= "  <td align=\"center\" style=\"color: #2E2E2E;\">\n";
			if($chk['ogReg']['error'] == 1){
				$daHtml .= "   <span style=\"color: #2E2E2E;\">Failed</span>\n";
      } elseif($chk['perms']['error'] == 1 || $chk['og']['error'] == 1){
      		$daHtml .= "   <span style=\"color: #2E2E2E;\">Failed</span>\n";
      }else{
         # Show all clear note if no conflicts detected
         $daHtml .= "   <span style=\"color: #2E2E2E;\">OK</span>\n";
      }

      $daHtml .= "  </td>\n";

    $daHtml .= " <tr>\n";

		if($chk['perms']['error'] == 1){
			$showPermFix = 1;
		}
		if($chk['permsReg']['error'] == 1){
			$showPermRegFix = 1;
		}
		if($chk['ogReg']['error'] == 1){
			$showOGError = 1;
		}
		if($chk['og']['error'] == 1){
			$showogFix = 1;
		}
 }
 
if($showOGError == 1 || $showPermFix == 1 || $showPermRegFix == 1 || $showogFix == 1){
	$daHtml .= "                          <tr>\n";
	$daHtml .= "                            <td colspan=\"6\" align=\"left\" class=\"dred\" style=\"cursor: pointer; background:url(images/green/diag-title-row.gif);\">\n";
	$daHtml .= "														<b>Error Reports</b></td>\n";
	$daHtml .= "                          </tr>\n";
}
if($showPermFix == 1){
	$daHtml .= "                          <tr>\n";
	$daHtml .= "													<form method=post action=\"HelpMe_V2.php\">\n";
	$daHtml .= "				 									<input type=\"hidden\" name=\"do\" value=\"fixPerms\">\n";
	$daHtml .= "				 									<input type=\"hidden\" name=\"type\" value=\"suexec\">\n";
	$daHtml .= "                            <td colspan=\"6\" align=\"left\" class=\"dred\">\n";
	$daHtml .= "														Your server is running PHP SuExec.<br>Permissions must be between 744 and 755 on all files and folders.<br>Click \"Fix Permissions\" to try and fix these issues.<br>\n";
	$daHtml .= "														<input type=\"submit\" value=\"Fix Permissions\">\n";
	$daHtml .= "														</td>\n";
	$daHtml .= "													</form>\n";
	$daHtml .= "                          </tr>\n";
}
if($showPermRegFix == 1){
	$daHtml .= "                          <tr>\n";
	$daHtml .= "													<form method=post action=\"HelpMe_V2.php\">\n";
	$daHtml .= "				 									<input type=\"hidden\" name=\"do\" value=\"fixPerms\">\n";
	$daHtml .= "				 									<input type=\"hidden\" name=\"type\" value=\"reg\">\n";
	$daHtml .= "                            <td colspan=\"6\" align=\"left\" class=\"dred\">\n";
	$daHtml .= "														Files and folders with <b>permissions lower than 777</b> and a <br>owner and group that <b>doesnt match (".$php_user.")</b> may cause problems.<br>\n";
	//$daHtml .= "														<input type=\"submit\" value=\"Fix Permissions\">\n";
	$daHtml .= "														</td>\n";
	$daHtml .= "													</form>\n";
	$daHtml .= "                          </tr>\n";
}
if($showogFix == 1){
	$daHtml .= "                          <tr>\n";
	$daHtml .= "                            <td colspan=\"6\" align=\"left\" class=\"dred\">Owner and Group must be <b>".$php_user."</b> to function properly.</td>\n";
	$daHtml .= "                          </tr>\n";
}
if($showOGError == 1){
	$daHtml .= "                          <tr>\n";
	$daHtml .= "                            <td colspan=\"6\" align=\"left\" class=\"dred\">\n";
	$daHtml .= "														<b>Owner must be ".$php_user."</b> with <b>755</b> permissions.<br><b>Group must be ".$php_user."</b> with <b>775</b> permissions.</td>\n";
	$daHtml .= "                          </tr>\n";
}


$daHtml .= "											</table>\n";
$daHtml .= "                    </div>\n";
$daHtml .= "									</td>\n";
$daHtml .= "                  </tr>\n";


$daHtml .= "									<!-- SERVER SETTINGS -->\n";
$daHtml .= "\n";
$daHtml .= "                  <tr>\n";
$daHtml .= "                    <td colspan=\"4\" class=\"fsub_title\" style=\"cursor: pointer; background:url(images/green/diag-title-row.gif);\" onclick=\"showGlobals('server');\">Server Settings</td>\n";
$daHtml .= "                  </tr>\n";
$daHtml .= "                  <tr>\n";
$daHtml .= "                    <td colspan=\"4\" align=\"center\">\n";
$daHtml .= "										<div id=\"server\" style=\"display:none;\">\n";
$daHtml .= "											<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">\n";
$daHtml .= "                          <tr>\n";
$daHtml .= "                            <td align=\"left\" width=\"33%\" class=\"col_sub\"><strong>Item</strong></td>\n";
$daHtml .= "                            <td align=\"center\" width=\"33%\" class=\"col_sub\"><strong>Setting</strong></td>\n";
$daHtml .= "                            <td align=\"center\" width=\"33%\" class=\"col_sub\"><strong>Recommended Setting</strong></td>\n";
$daHtml .= "                          </tr>\n";

$daHtml .= "                          <tr>\n";
$daHtml .= "                            <td align=\"left\" >Server OS</td>\n";
$daHtml .= "                            <td align=\"center\">\n";
$daHtml .= "														".strtoupper(PHP_OS)."\n";
$daHtml .= "														</td>\n";
$daHtml .= "                            <td align=\"center\">Any</td>\n";
$daHtml .= "                          </tr>\n";

$daHtml .= "                          <tr>\n";
$daHtml .= "                            <td align=\"left\" >Safe Mode</td>\n";
$daHtml .= "                            <td align=\"center\">\n";
# Check for safe_mode (because of shell_exec)
if ( ini_get('safe_mode') ) {
  $daHtml .= "														<font color=\"red\">On</font>\n";
}else{
	$daHtml .= "														Off\n";
}
$daHtml .= "														</td>\n";
$daHtml .= "                            <td align=\"center\">Off</td>\n";
$daHtml .= "                          </tr>\n";

$daHtml .= "                          <tr>\n";
$daHtml .= "                            <td align=\"left\">allow_url_fopen</td>\n";
$daHtml .= "                            <td align=\"center\">\n";
						# Check allow_url_fopen
if ( ini_get('allow_url_fopen') != 1 ) {
  $daHtml .= "														<font color=\"red\">Off</font>";
}else{
	$daHtml .= "														On";
}
$daHtml .= "														</td>\n";
$daHtml .= "                            <td align=\"center\">On</td>\n";
$daHtml .= "                          </tr>\n";

$daHtml .= "                          <tr>\n";
$daHtml .= "                            <td colspan=\"6\" align=\"center\" class=\"dred\">Items marked in red should be looked at to make sure they are not causing conflict.</td>\n";
$daHtml .= "                          </tr>\n";
$daHtml .= "                   	  </table>\n";
$daHtml .= "                    </div>\n";
$daHtml .= "									</td>\n";
$daHtml .= "                  </tr>\n";

$daHtml .= "                </table>\n";
$daHtml .= "              </div>\n";

#################################
###		FAQ Display
#################################

$daHtml .= "          <div id=\"faq\" style=\"display:none;\" valign=\"top\">\n";
$daHtml .= "                <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
//$daHtml .= "            <tr>\n";
//$daHtml .= "              <td colspan=\"3\" height=\"17\" background=\"images/stripe-fill.gif\" style=\"padding-left:5px;\">\n";
//$daHtml .= "              <img src=\"images/manual-text.gif\" width=\"112\" height=\"17\">\n";
//$daHtml .= "              </td>\n";
//$daHtml .= "            </tr>\n";
$daHtml .= "        				</table>\n";
$daHtml .= "          	<iframe id=\"myframe\" src=\"http://".$helpicon_goto."\" scrolling=\"no\" marginwidth=\"0\" marginheight=\"0\" frameborder=\"0\" vspace=\"0\" hspace=\"0\" style=\"overflow:hidden; width:100%; height:450px; display:block; border: 1px solid #666666;\"></iframe>\n";
$daHtml .= "          </div>\n";
$daHtml .= "    </td>\n";
$daHtml .= "  </tr>\n";
$daHtml .= "</table>\n";

echo $daHtml;
?>

