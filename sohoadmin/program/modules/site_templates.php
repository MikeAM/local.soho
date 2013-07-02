<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


####################################################################################
## Soholaunch(R) Site Management Tool
## Version Ultra
## Homepage:      http://www.soholaunch.com
## Bug Reports:   http://bugz.soholaunch.com
## Release Notes: http://wiki.soholaunch.com
####################################################################################

###################################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2011 Soholaunch.com, Inc. All Rights Reserved.
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
####################################################################################

session_start();

require_once("../includes/product_gui.php");

# Make sure template-related db tables exist
include_once("site_templates/dbtable_check-templates.inc.php");

if(!table_exists('site_specs')){
	create_table('site_specs');
}

$globalprefObj = new userdata('global');
if($_POST['actions']=='tab2'){
	if($_POST['df_favicon'] == 'favicon.ico'){
		$globalprefObj->set('site_favicon', $_POST['df_favicon']);
	} else {
		$globalprefObj->set('site_favicon', $_POST['df_favicon']);	
	}
}

$site_favicon = $globalprefObj->get('site_favicon');
$df_favicon = $site_favicon;

# Define different html layout files to check for in a given template foder
# ...set globally here for use in various loops
$layout_files = array("index.html", "home.html", "cart.html");

# Defined globally here so you don't have to type out this path over and over again
$tpl_base_path = $_SESSION['docroot_path']."/sohoadmin/program/modules/site_templates/pages";

# Fix Internal Server Error
if ( $_GET['todo'] == "fix_suexec_error" ) {
   chmod("site_templates", 0755);
   shell_exec("chmod -R 0755 site_templates");
   $suexec_error_fixed = true;
}

# Restore template prefs
$tplpref = new userdata("template");

# Save user image settings
if ( $_POST['todo'] == "save_userimg" ) {
   foreach ( $_POST['userimg'] as $key=>$value ) {
      $tplpref->set($key, $value);
   }
}

if($_POST['wap_template'] != ''){
	if($_POST['wap_template'] == 'yes'){
		$wapsetting = new userdata('wap_template');
		$wapsetting->set('template', 'WAP-minimal-none') ;
	} else {
		$wapsetting = new userdata('wap_template');
		$wapsetting->set('template', '') ;
	}	
}

# unset_pagetemplate - Unset page template assignment
if ( $_GET['unset_pagetemplate'] != "" ) {
   $qry = "update site_pages set template = '' where page_name = '".$_GET['unset_pagetemplate']."'";
   $rez = mysql_query($qry);
   $report[] = $_GET['unset_pagetemplate']." ".lang("template assignment unset").". ".lang("This page will now use your Site Base Template").".";
}

# Delete template action
if ( $_POST['todo'] == "delete_template" && count($_POST['killthis_template']) > 0 ) {
   $dead_templates = "";
   $dead_templates .= "<div id=\"dead_templates\" class=\"hand bg_gray_df\" onclick=\"hideid('dead_templates');\" style=\"position: static;z-index: 5;height: 100px;overflow: auto;\">\n";
   foreach ( $_POST['killthis_template'] as $key=>$template ) {
      $path_to_target_template = $_SESSION['docroot_path']."/sohoadmin/program/modules/site_templates/pages/".$template;
      if ( is_dir($path_to_target_template) ) {
         rmdirr($path_to_target_template);

         if ( !is_dir($path_to_target_template) ) {
            $dead_templates .= "<span class=\"red\">".lang("Template")." <b>".$template."</b> ".lang("deleted")."!</span><br/>\n";
         } else {
            $dead_templates .= lang("Could not delete template")." <b>".$template."</b>";
         }
      }
   }
   $dead_templates .= "</div>\n";
//   $dead_templates .= "<div id=\"hide-dead_templates\" class=\"hand right white\" onclick=\"hideid('dead_templates');hideid('hide-dead_templates');\" style=\"width: 650px;background-color: red;\"><b>Click to close this report</b></div>\n";
}


$temp_set = 0;
if($_POST['action'] == "template"){

   ########################################################
   ## SET TEMPLATE STORAGE LOCATIONS                     ##
   ########################################################

   $template_dir = "$doc_root/template";
   $new_template_dir = $_POST['site'];

   #######################################################
   ### Delete Old Template Files in Root Dir        ###
   #######################################################

   $directory = $template_dir;
   if (is_dir($directory)) {
      $handle = opendir("$directory");
      while ($files = readdir($handle)) {
         if (strlen($files) > 2) {
            $deleteit = $directory."/".$files;
            @unlink($deleteit);
         }
      }
      closedir($handle);
   }

   ######################################################
   ## Copy New Template Files to User Directory     ##
   ######################################################

   if (!eregi("tCustom", $new_template_dir)) {

      // If this is NOT a custom uploaded template then copy runtime files to template dir
      $directory = "site_templates/pages/".$new_template_dir;
      // Open Built-In Directory and copy files to "client" side
      $handle = opendir("$directory");
      while ($files = readdir($handle)) {
         if (strlen($files) > 2) {
            $basefile = "$directory/".$files;
            $clientfile = $template_dir."/".$files;
           // @copy($basefile, $clientfile);
         }
      }
      closedir($handle);

      // Now write template.conf file so that we can load the current
      // template from memory next time user enters "Site Templates" option

//      $filename = $doc_root."/sohoadmin/tmp_content/template.conf";
//      $file = fopen("$filename", "w");
//         fwrite($file, "$new_template_dir");
//      fclose($file);
      
	$globalprefObj->set('site_base_template', $new_template_dir);	
	$globalprefObj->set('what_next_select_template', 'hide');
      

   } else {
//	$filename = $doc_root."/sohoadmin/tmp_content/template.conf";
//
//      $file = fopen("$filename", "w");
//         fwrite($file, "$new_template_dir");
//      fclose($file);
	$globalprefObj->set('site_base_template', $new_template_dir);
	$globalprefObj->set('what_next_select_template', 'hide');
   }


   ##########################################################
   ## UPDATE CONTENT AREA SETTINGS                    ##
   ##########################################################

   $filename = $cgi_bin . "/contentarea.conf";
   $file = fopen("$filename", "w");
      fwrite($file, "$CONTENTAREA");
   fclose($file);
   $temp_set = 1;
}

      
//      if(isset($_POST['df_logo'])){
	if($_POST['actions']=='tab2'){
      	$df_logo = $_POST['df_logo'];
      	mysql_query("UPDATE site_specs SET df_logo = '$df_logo'");
         $msg = "<center><h2 class=\"green\" style=\"padding: 10px; background: #FFFFFF; border: 1px solid #666666;\">".lang("Logo Setting Saved")."<i>!</i></h2></center>";
      }
// Upload Logo
if($_POST['action'] == "upload_logo"){

   if($_FILES['FILE1']['name'] != ""){

      $uploadDir = $doc_root."/images/";
      $uploadFile = $uploadDir . str_replace(' ', '_', $_FILES['FILE1']['name']);
      $this_logo = $_FILES['FILE1']['name'];

      if (is_uploaded_file($_FILES['FILE1']['tmp_name'])) {

         if (!copy($_FILES['FILE1']['tmp_name'], $uploadFile)){
            $msg = "<center><h3 class=\"red\" style=\"padding: 10px; background: #FFFFFF; border: 1px solid #666666;\"><strong>".lang("Error").":</strong> ".lang("Unable to save logo file")."... <br><strong>".lang("Possible Solution")>": </strong>chmod the<strong> /media</strong> directory to <strong>777</strong></h3></center>";
         }else{
            $msg = "<center><h2 class=\"green\" style=\"padding: 10px; background: #FFFFFF; border: 1px solid #666666;\">".lang("Logo Uploaded")."<i>!</i></h2></center>";
         }

      }else{
        switch($HTTP_POST_FILES['FILE1']['error']){
         case 0: //no error; possible file attack!
           echo lang("There was a problem with your upload.");
           break;
         case 1: //uploaded file exceeds the upload_max_filesize directive in php.ini
           echo lang("The file you are trying to upload is too big.");
           break;
         case 2: //uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
           echo lang("The file you are trying to upload is too big.");
           break;
         case 3: //uploaded file was only partially uploaded
           echo lang("The file you are trying upload was only partially uploaded.");
           break;
         case 4: //no file was uploaded
           echo lang("You must select an image for upload.");
           break;
         default: //a default error, just in case!  :)
           echo lang("There was a problem with your upload.");
           break;
         }
      }
   }
}

#######################################################
### ACTION SAVE HEADER AND SLOGAN
#######################################################

//if($_POST['action'] == "head_slogan"){
if($_POST['actions']=='tab2'){
   $logo_text = htmlentities($_POST['df_hdrtxt'], ENT_QUOTES);
   $slogan_text = htmlentities($_POST['df_slogan'], ENT_QUOTES);
//   $logo_text = $_POST['df_hdrtxt'];
//   $slogan_text = $_POST['df_slogan'];
   if(mysql_query("UPDATE site_specs SET df_hdrtxt = '$logo_text', df_slogan = '$slogan_text'")){
      $msg = "<center><h2 class=\"green\" style=\"padding: 10px; background: #FFFFFF; border: 1px solid #666666;\">".lang("Logo / Slogan Saved")."<i>!</i></h2></center>";
   }else{
      $msg = "<center><h2 class=\"red\" style=\"padding: 10px; background: #FFFFFF; border: 1px solid #666666;\">".lang("Error Saving Logo / Slogan")."<i>!</i></h2></center>";
   }

//}

#######################################################
### ACTION SAVE BUSINESS INFO
#######################################################


	
   $address1 = $_POST['df_address1'];
   $city = $_POST['df_city'];
   $state = $_POST['df_state'];
   $zip = $_POST['df_zip'];
   $country = $_POST['df_country'];
   $phone = $_POST['df_phone'];
   $copyright = $_POST['copyright'];
   $company = $_POST['df_company'];
   $insThis = "df_address1 = '$address1', df_city = '$city', df_state = '$state', df_zip = '$zip',";
   $insThis .= "df_country = '$country', df_phone = '$phone', copyright = '$copyright', df_company = '$company'";
   if(mysql_query("UPDATE site_specs SET $insThis")){
      $msg = "<center><h2 class=\"green\" style=\"padding: 10px; background: #FFFFFF; border: 1px solid #666666;\">".lang("Business Information Saved")."<i>!</i></h2></center>";
   }else{
      $msg = "<center><h2 class=\"red\" style=\"padding: 10px; background: #FFFFFF; border: 1px solid #666666;\">".lang("Error Saving Business Information")."<i>!</i></h2></center>";
   }
}


#######################################################
### GET COUNTRY DATA FROM FLAT FILE
$filename = "../webmaster/shared/countries.dat";
$file = fopen("$filename", "r") or DIE(lang("Error").": ".lang("Could not open country data")." (../webmaster/shared/contries.dat).");
   $tmp_data = fread($file,filesize($filename));
fclose($file);

$natDat = split("\n", $tmp_data);
$numNats = count($natDat);

//natDat: T.M.I (for now) format for proper display and usage
$natNam = "";
for ($f=0; $f < $numNats; $f++) {
   $tmpSplt = split("::", $natDat[$f]);
   $natNam[$f] = "$tmpSplt[0] - $tmpSplt[1]";
   $natNam[$f] = strtoupper($natNam[$f]);
}
###
#######################################################

# READ TEMPLATE FILES INTO MEMORY
# pages/
$templatecount = 0;

$newscount = 0;
$directory = "site_templates/pages";

if (is_dir($directory)) {
	$handle = opendir("$directory");
	while ($files = readdir($handle)) {	
		if(is_dir($directory.'/'.$files)){
			if (strlen($files) > 2) {
				eval(hook("site_templates.php:template-file-loop"));
				if(file_exists($directory.'/'.$files.'/index.html') || file_exists($directory.'/'.$files.'/cart.html') || file_exists($directory.'/'.$files.'/news.html') || file_exists($directory.'/'.$files.'/home.html')){
					$templatecount++;
					$templateFile[$templatecount] = $files;
				}
			}
		}      
	}
	closedir($handle);
}


# remote template_lib
$local_directory = $_SESSION['docroot_path']."/sohoadmin/program/modules/site_templates/pages";
$remote_directory = $_SESSION['template_lib'];
$remoteFile = array();



if ( is_dir($remote_directory) && $remote_directory != $local_directory ) {
   $handle = opendir($remote_directory);
   while ($files = readdir($handle)) {
      if (strlen($files) > 2) {
         $remoteFile[] = $files;
      }
   }
   closedir($handle);
}

if ($templatecount > 1) {	
	//sort($templateFile);
   $numTemplates = $templatecount;
   $newsTemplates = $newscount;
	$numTemplates--;
}

$CUR_TEMPLATE = rtrim($globalprefObj->get('site_base_template'));

#######################################################
### READ CONTENT AREA SETTING                ###
#######################################################
$filename = $cgi_bin . "/contentarea.conf";
if (file_exists("$filename")) {
   $file = fopen("$filename", "r");
   $CONTENTAREA_VAR = fread($file,filesize($filename));
   fclose($file);
} else {
   $CONTENTAREA_VAR = "FIXED";
}
$CONTENTAREA_VAR = chop($CONTENTAREA_VAR);


#######################################################
### Read current info from site_specs
#######################################################
$spcRez = mysql_query("SELECT * from site_specs");
$pullSpec = mysql_fetch_array($spcRez);

$headertext = $pullSpec['df_hdrtxt'];
$subheadertext = $pullSpec['df_slogan'];
$df_logo = $pullSpec['df_logo'];
$df_company = $pullSpec['df_company'];
$df_address1 = $pullSpec['df_address1'];
$df_address2 = $pullSpec['df_address2'];
$df_state = $pullSpec['df_state'];
$df_zip = $pullSpec['df_zip'];
$df_country = $pullSpec['df_country'];
$df_phone = $pullSpec['df_phone'];
$df_email = $pullSpec['df_email'];
$copyright = $pullSpec['copyright'];
$df_fax = $pullSpec['df_fax'];
$df_city = $pullSpec['df_city'];


########################################################################################
## Build Template Selection Box
########################################################################################

$SELECT_OPTS = "<OPTION VALUE=\"\" style='font-family: Tahoma; font-size: 8pt;'>".lang("Select Base Template")."...</OPTION>\n";
natcasesort($templateFile);
$sorted_template = $templateFile;
unset($templateFile);

foreach($sorted_template as $val){
	$templateFile[] = $val;
}

for ($x=0;$x<=$numTemplates;$x++) {

   if (eregi("CUSTOM", $templateFile[$x])) {
      # Custom HTML templates (uploaded via old template manager...depreciated)
      $custarray = split("~~~", $templateFile[$x]);
      if ( count($custarray) == 1 ) {
         $tmp = split("-", $templateFile[$x]);
         $tCategory = strtoupper($tmp[0]);
         $tmp[1] = eregi_replace("_", " ", $tmp[1]);
         $display = "$tCategory  > $tmp[1] ";
         if ( !eregi("none", $tmp[2]) && trim($tmp[2]) != '' ) { $display .= "($tmp[2])"; }
         if ($templateFile[$x] == $CUR_TEMPLATE) { $isSel = " selected"; } else { $isSel = ""; }
         $SELECT_OPTS .= "<OPTION VALUE=\"".$templateFile[$x]."\" style=\"font-family: Tahoma; font-size: 8pt;\"".$isSel.">".$display."</OPTION>\n";

      } else {
         $display = "CUSTOM > ".$custarray[1];
         $thisFile = $doc_root."/tCustom/".$custarray[1];

            // Check for Win32/IIS Directory Formating
            if (eregi("IIS", $SERVER_SOFTWARE)) {
               $thisFile = eregi_replace("/", "\\", $thisFile);
            }

         if ($thisFile == $CUR_TEMPLATE) { $isSel = " selected"; } else { $isSel = ""; }
         $SELECT_OPTS .= "<OPTION VALUE=\"$thisFile\" style=\"font-family: Tahoma; font-size: 8pt;\"".$isSel.">$display</OPTION>\n";
      }

   } elseif ( !eregi("unzips", $templateFile[$x]) ) {
      # Factory templates (from /pages dir)
      $tmp = split("-", $templateFile[$x]);
      $tCategory = strtoupper($tmp[0]);
      $tmp[1] = eregi_replace("_", " ", $tmp[1]);
      $display = "$tCategory";
      if ( strlen($tmp[1]) > 1 ) { $display .= "  > $tmp[1] "; }
      if ( !eregi("none", $tmp[2]) && strlen(trim($tmp[2])) > 1 ) { $display .= "($tmp[2])"; }
      if ($templateFile[$x] == $CUR_TEMPLATE) { $isSel = " selected"; } else { $isSel = ""; }
      //if ( $tmp[1] != "" ) {
         $SELECT_OPTS .= "<OPTION VALUE=\"".$templateFile[$x]."\" style=\"font-family: Tahoma; font-size: 8pt;\"".$isSel.">".$display."</OPTION>\n";
      //}

   } // End If CUSTOM

} // End For Loop


// Working but hesitent to turn on until tested and refined a bit more (like explained more...maybe an optoin to show that "templates from remote library" thing in branding controls)
//# Add remote templates to options?
//if ( count($remoteFile) > 1 ) {
//   $SELECT_OPTS .= "<option value=\"\" style=\"font-weight: bold;\">Templates from remote library...</option>\n";
//   $max = count($remoteFile);
//   for ( $r=0; $r < $max; $r++ ) {
//      $tmp = split("-", $remoteFile[$r]);
//      $tCategory = strtoupper($tmp[0]);
//      $tmp[1] = eregi_replace("_", " ", $tmp[1]);
//      $display = "$tCategory  > $tmp[1] ";
//      if ($remoteFile[$r] == $CUR_TEMPLATE) { $isSel = " selected"; } else { $isSel = ""; }
//      $SELECT_OPTS .= "<option value=\"".$remoteFile[$r]."\" ".$isSel.">".$display."</option>\n";
//   }
//}
error_reporting(E_ERROR | E_WARNING | E_PARSE);

if ( !$result = mysql_query("SELECT prikey, page_name, url_name, template FROM site_pages") ){
   echo lang("Cannot select from site_pages table");
   echo "<br>";
   //echo "Cannot select from site_pages table<br>";
   echo "Mysql says: ".mysql_error();
   exit;
}
while($page_template = mysql_fetch_array($result)){
   if ( $page_template['template'] != "" && $page_template['template'] != $CUR_TEMPLATE ) {
      $page_temps[] = $page_template['template'];
      $page_names[] = eregi_replace("_"," ",$page_template['page_name']);
   }
}

$indiv_temp_count = count($page_temps);

for($j = 0; $j < $indiv_temp_count; $j++){
   $filename = "http://".$this_ip."/sohoadmin/program/modules/site_templates/pages/".$page_temps[$j]."/index.html";
   $filename2 = $doc_root."/sohoadmin/program/modules/site_templates/pages/".$page_temps[$j]."/index.html";
   $SUB_HTML_TEMP[$j] = eregi_replace("[_-]"," ", $page_temps[$j]);
   $SUB_TEMP_NAME[$j] = $page_names[$j];
   ob_start();
      if(!include($filename2)){
         echo include_r($filename);
      }
      $SUB_HTML[$j] = ob_get_contents();
   ob_end_clean();
}

##################################################################################
### READ IMAGE FILES INTO MEMORY
##################################################################################
//$img_selection = "     <OPTION VALUE=\" \">[".lang("No Image")."]</OPTION>\n";
$count = 0;
$directory = "$doc_root/images";
$handle = opendir("$directory");
	while ($files = readdir($handle)) {
		if (strlen($files) > 2) {
			$count++;
			//$imageFile[$count] = ucwords($files) . "~~~" . $files;
			$imageFile[$count] = $files . "~~~" . $files;
		}
	}
$numImages = $count;
closedir($handle);
if ($count != 0) {
	sort($imageFile);
	natcasesort($imageFile);
	$imageFile = array_values($imageFile);

	if ($count == 1) {
		$imageFile[0] = $imageFile[1];
	}
	$numImages--;
}

for ($x=0;$x<=$numImages;$x++) {
	$thisImage = split("~~~", $imageFile[$x]);
	if (file_exists("$directory/$thisImage[1]")) {
//		$tempArray = getImageSize("$directory/$thisImage[1]");
		$origW = $tempArray[0];
		$origH = $tempArray[1];
		$oW = "";
		$oH = "";
		if ($origH > 300) {
				$oH = "HEIGHT=300 ";
		}
		if ($origW > 275) {
			$oW = "WIDTH=275";
		}
		$WH = "$oW $oH ";
	}

	if ( $thisImage[1] == $df_logo ) {
	   $img_selection .= "     <option value=\"".$thisImage[1]."\" selected>".$thisImage[0]."</option>\n";
	} else {
	   $img_selection .= "     <option value=\"".$thisImage[1]."\">".$thisImage[0]."</option>\n";
	}
   
	if(preg_match('/\.(ico|gif|png)$/i', $thisImage[1])){
		$thisImage[1] = 'images/'.$thisImage[1];
		if ( $thisImage[1] == $site_favicon ) {
			$favicon_selection .= "     <option value=\"".$thisImage[1]."\" selected>".$thisImage[0]."</option>\n";
		} else {
			$favicon_selection .= "     <option value=\"".$thisImage[1]."\">".$thisImage[0]."</option>\n";
		}
	}
	//$img_selection .= "     <option value=\"".$thisImage[0]."\">".$thisImage[0]."</option>\n";
}

# Include template-related php functions
include_once("site_templates/template_functions.inc.php");

# Start buffering output
ob_start();
?>
<link href="../includes/display_elements/window/default.css" rel="stylesheet" type="text/css"></link>
<link href="../includes/display_elements/window/alert_lite.css" rel="stylesheet" type="text/css"></link>
<script language="javascript">
//---------------------------------------------------------------------------------------------------------
//      _      _   _   __  __
//     /_\  _ | | /_\  \ \/ /
//    / _ \| || |/ _ \  >  <
//   /_/ \_\\__//_/ \_\/_/\_\
//
//---------------------------------------------------------------------------------------------------------
// The following script (as commonly seen in other AJAX javascripts) is used to detect which browser the client is using.
// If the browser is Internet Explorer we make the object with ActiveX.
// (note that ActiveX must be enabled for it to work in IE)
function makeObject() {
   var x;
   var browser = navigator.appName;

   if ( browser == "Microsoft Internet Explorer" ) {
      x = new ActiveXObject("Microsoft.XMLHTTP");
   } else {
      x = new XMLHttpRequest();
   }

   return x;
}

// The javascript variable 'request' now holds our request object.
// Without this, there's no need to continue reading because it won't work ;)
var request = makeObject();

function ajaxDo(qryString, boxid) {
   //alert(qryString+', '+boxid);
   rezBox = boxid; // Make global so parseInfo can get it
   // The function open() is used to open a connection. Parameters are 'method' and 'url'. For this tutorial we use GET.
   request.open('get', qryString);
   // This tells the script to call parseInfo() when the ready state is changed
   request.onreadystatechange = parseInfo;
   // This sends whatever we need to send. Unless you're using POST as method, the parameter is to remain empty.
   request.send('');
}

function parseInfo() {
   // Loading
   if ( request.readyState == 1 ) {
      document.getElementById(rezBox).innerHTML = '<?php echo lang("Loading"); ?>...';
   }
   // Finished
   if ( request.readyState == 4 ) {
      var answer = request.responseText;
      document.getElementById(rezBox).innerHTML = answer;
   }
}

function MM_popupMsg(msg) { //v1.0
  alert(msg);
}

function MM_findObj(n, d) { //v3.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
}

parent.header.flip_header_nav('TEMPLATE_MANAGER');
//parent.header.flip_header_nav('VIASTEPPHOTOGALLERY');
</script>

<script language="javascript">
var p = "Template Manager";
parent.frames.footer.setPage(p);
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
function preview (image) {
   eval("MM_openBrWindow('prev_image.php?image="+image+"&dot_com=<?echo $dot_com; ?>','imagepreview','width=450,height=300,scrollbars=yes,resizable=yes');");
}
function saving_updates() {
   document.ctemplate.submit();
//   $('#change-template-form').submit();
//   openInfoDialogSaveTemplate()
   //show_hide_layer('Layer2','','hide','Layer1','','hide','Layer3','','show');
}

function openInfoDialog() {
   Dialog.info("<? echo lang("Uploading Image"); ?>...", {windowParameters: {className: "alert_lite",width:250, height:50}, showProgress: true});
}

function openInfoDialogUpTemplate() {
   Dialog.info("<? echo lang("Uploading Template"); ?>...", {windowParameters: {className: "alert_lite",width:250, height:50}, showProgress: true});
}

function upload_files() {
<?php
# Disable template upload for demo sites
if ( $_SESSION['demo_site'] == "yes" ) {
   echo "   alert('".lang("Template upload is not available in demo mode").".');\n";
} else {
   //echo "   openInfoDialogUpTemplate()\n";
   echo "   document.FormUpload.submit();\n";
}
?>
} // End upload_files() function

function upload_logo() {
   //openInfoDialog()
   document.logo_form.submit();
}

function upload_logo_favicon() {
   //openInfoDialog()
   document.favicon_form.submit();
}

function showHelp(){
   window.open('help_center/help_center.php','billy','width=800, height=600, scrollbars=auto');
}

function show_template(v) {

   var customCheck = v.search("tCustom");
   if (v == "" || customCheck > 0) {
      var str = "site_templates/no_screenshot.gif";
   } else {
      var str = "site_templates/pages/"+v+"/screenshot.jpg";
   }
   var str = "<IMG SRC="+str+" WIDTH=200 HEIGHT=137 BORDER=0 ALIGN=ABSMIDDLE>";
   document.getElementById('prevshot').innerHTML = str;
   var cur_tmp = document.getElementById('current_template').value

   ajaxDo('site_templates/template_features.php?template='+v+'&cur_tmp='+cur_tmp, 'template_info');
}

// Selects passed template in "choose site template" dropdown, then calls show_template to update screenshot and feature list
// Originally built when adding Cancel button to Template Features box (template_features.php)
function select_template(template_folder) {
   document.getElementById('base_sel').value = template_folder;

   show_template(template_folder);
}


function popSave(){
   document.ctemplate.submit();
}

function browse_templates() {
   eval("MM_openBrWindow('site_templates/pgm-browse_templates.php','browsetemplates','width=650,height=400,scrollbars=yes,resizable=yes');");
}

function set_basetpl(tpl) {
   document.ctemplate.site.value = tpl;
}

function promo_mgr(){
   window.location = "promo_boxes/promo_boxes.php";
}


function findimgstuff2(){
	var pic = document.getElementById('daImage');
	var pichidden = document.getElementById('daImagehidden');
	if(pichidden.offsetWidth > 150){
		pic.style.width=150;
	} else {
		pic.style.width=pichidden.offsetWidth;
	}
	if(pichidden.offsetHeight > 100){
		pic.style.height=100;
	} else {
		pic.style.height=pichidden.offsetHeight;
	}
	//alert(pichidden.offsetWidth+' '+pichidden.offsetHeight);
}

function showPreview(ele){
	if(ele != ""){
		
		document.getElementById('daImage').src= 'http://<?php echo $this_ip; ?>/images/'+ele
		
		var pic = document.getElementById('daImage');
		var pichidden = document.getElementById('daImagehidden');
		pichidden.src= '';
		pichidden.src= 'http://<?php echo $this_ip; ?>/images/'+ele;
		setTimeout("findimgstuff2()", 500);
		document.getElementById('daImage').src= 'http://<?php echo $this_ip; ?>/images/'+ele;
		
	} else {
		document.getElementById('daImage').src= 'http://<?php echo $this_ip; ?>/sohoadmin/program/modules/spacer.gif'
	}
}
function findimgstuff(){
	var pic = document.getElementById('daFavicon');
	var pichidden = document.getElementById('daFaviconhidden');
	if(pichidden.offsetWidth > 32){
		pic.style.width=32;
	} else {
		pic.style.width=pichidden.offsetWidth;
	}
	if(pichidden.offsetHeight > 32){
		pic.style.height=32;
	} else {
		pic.style.height=pichidden.offsetHeight;
	}
	//alert(pichidden.offsetWidth+' '+pichidden.offsetHeight);
}

function showPreviewFav(ele){
	if(ele != ""){
		var pic = document.getElementById('daFavicon');
		var pichidden = document.getElementById('daFaviconhidden');
		pichidden.src= '';
		pichidden.src= 'http://<?php echo $this_ip; ?>/'+ele;
		setTimeout("findimgstuff()", 500);
		document.getElementById('daFavicon').src= 'http://<?php echo $this_ip; ?>/'+ele;
	} else {
		document.getElementById('daFavicon').src= 'http://<?php echo $this_ip; ?>/sohoadmin/program/modules/spacer.gif'
	}
}

function showUpload(){
   document.getElementById('upLogo').style.display="block"
}

function showUploadFavicon(){
   document.getElementById('upFavicon').style.display="block"
}

function show_features(templt){
   ajaxDo('site_templates/template_features.php?template='+templt, 'template_info');
}


</script>

<style type="text/css">
body {
   font-family : sans-serif;
}
.dojoTabPaneWrapper {
  padding : 10px 10px 10px 10px;
}

#main {
   width: 375px;
}
.label {
   border: 1px solid black;
   background: #232323;
   color: #fff;
   font-weight: bold;
}

.label :hover {
   cursor: pointer;
}

.accBody {
   background: #ededed;
   overflow: auto;
}

.promo-on {
   position: absolute;
   top: -35px;
   z-index: 2;
   vertical-align: top;
   font-weight: bold;
   padding-top: 15px;
   padding-bottom: 5px;
   margin-right: 15px;
   color: #D75B00;
}

.promo-on {
   position: absolute;
   top: -35px;
   z-index: 2;
   vertical-align: top;
   font-weight: bold;
   padding-top: 15px;
   padding-bottom: 5px;
   margin-right: 15px;
   color: #D75B00;
}

.tab-off, .tab-on {
   position: absolute;
   top: -25px;
   z-index: 2;
   text-align: center;
   width: 125px;
   /*height: 25px;*/
   vertical-align: top;
   font-weight: bold;
   padding-top: 5px;
   padding-bottom: 5px;
   margin-right: 15px;
   background-color: #efefef;
   border: 1px solid #ccc;
   border-top: 3px solid #ccc;
   color: #595959;
   cursor: pointer;
}

.tab-on {
   color: #000;
   background-color: #efefef;
   border-top: 3px solid #EAA510;
   font-weight: bold;
}

/* Table containing content for each tab */
table.tab_content {
   /*margin: 40px 5px 20px 5px;*/
   border: 1px solid #ccc;
   margin-left: 5px;
   /*width: 100%;*/
   /*position: relative;*/
}

#layout_tab1 { left: 5px; }
#layout_tab2 { left: 140px; }
#layout_tab3 { left: 275px; }
#layout_tab4 { left: 610px; }
#layout_tab5 { left: 410px; }

#delete_template_layer {
   font-size: 11px;
   width: 650px;
   padding: 0px;
   position: absolute;
   left: 5%;
   top: -5%;
   z-index: 5;
   background-color: #efefef;
   border: 1px solid #980000;
}
</style>

<div style="height: 100%;position: relative;">

<div id="delete_template_layer" style="display: none;">
 <div style="padding: 15px;">
  <form name="killtemplate_form" action="site_templates.php" method="post">
  <input type="hidden" name="todo" value="delete_template">
  <p><b class="red"><?php echo lang("WARNING"); ?>:</b> <?php echo lang("This action is permanent and cannot be un-done. It is meant mainly for developers to use during testing/development,
  though it can also be used to clean out all the templates you don't want just to get them out of your way.
  If you delete a template that you're currently using somewhere on your website, it's going to cause a bunch of broken display issues
  (though these can be fixed by simply assigning a different template)"); ?>".</p>

  <p><b><?php echo lang("Choose template to delete"); ?>:</b></p>

  <select name="killthis_template[]" style="font-family: Tahoma; font-size: 8pt; visibility: visible;height: 140px;" multiple>
   <?php echo $SELECT_OPTS; ?>
  </select>

  <button type="button" class="redButton" onclick="document.killtemplate_form.submit();"><span><span><?php echo lang("Delete selected template(s)"); ?></span></span></button>
  </form>

<?php echo $dead_templates; ?>
 </div>

 
 <div id="killtemplate_closebar" onclick="hideid('delete_template_layer');toggleid('base_sel', 'visibility');" onmouseover="setClass(this.id, 'hand bg_red_d7 white right');"  onmouseout="setClass(this.id, 'hand bg_red_98 white right');" class="hand bg_red_98 white right" style="padding: 3px;">[x] close</div>
</div>



<div id="pophelp_base_template" style="width: 500px; padding: 0px; position: absolute; left: 20%; top: 22%; z-index: 15; display: none;">
 <table class="feature_gray" cellspacing="0" cellpadding="8">
  <tr>
   <td class="fsub_title"><img border="0" src="../includes/display_elements/graphics/help_icon-fsub_title.gif"></td>
   <td class="fsub_title" width="100%">Site Base Template</td>
  </tr>
  <tr>
   <td colspan="2">
    <p><?php echo lang("The base site template will be applied by default to all pages."); ?></p>
    <p><?php echo lang("You may override this setting and assign a unique template to an individual site page through the 'Page Properties' menu in the Page Editor."); ?></p>
    <p><?php echo lang("Select a template from the drop-down list, or click 'Browse Templates by Screenshot' to select a template. The image above the drop-down box shows a screenshot of the selected template."); ?></p>
   </td>
  </tr>
  <tr>
   <td colspan="2" align="right">[ <span class="hand red uline" onclick="document.getElementById('pophelp_base_template').style.display='none';toggleid('base_sel', 'visibility');">Close Window</span> ]</td>
  </tr>
 </table>
</div>

<?php
//echo "<!---Delete template | Get more templates-->\n";

if(eregi('Gecko', $_SERVER['HTTP_USER_AGENT'])){
   echo "<div style=\"display: block; position: absolute; top: -45px; right: 25px;\">\n";
}else{
   echo "<div style=\"display: block; position: absolute; top: -7px; right: 25px;\">\n";
}


# Disable delete option in demo site mode
if ( $_SESSION['demo_site'] != "yes" ) {
   echo " <span class=\"red hand uline\" onclick=\"showid('delete_template_layer');toggleid('base_sel', 'visibility');\">Delete template(s)</span>\n";
}

# Show get more templates link?
if ( plugins_allowed() && $_SESSION['hostco']['get_more_templates_link'] != "off" ) {
   echo "| <span onclick=\"window.open('http://".$_SESSION['hostco']['get_more_templates_url']."/Templates.php','Soholaunch_Addons', 'width='+screen.availWidth+', height='+screen.availHeight+', location, status, toolbar, resizeable, menubar, scrollbars');\" class=\"hand orange normal unbold font90 uline\" style=\"font-size: 90%; letter-spacing: normal;\">".lang("Get more templates")."</span>\n";
}

# Show custom template PDF link?
if ( $_SESSION['hostco']['company_name'] == "Soholaunch" ) {
   echo "| <a href=\"http://addons.soholaunch.com/media/Creating_Custom_Template_for_SOHO.zip\" class=\"normal unbold font90\" style=\"font-size: 90%; letter-spacing: normal;\">".lang("Developers: Custom template how-to")."</a>\n";
}

echo "</div>\n";

?>

   

    
    <div id="pref" style="border: 0px solid red;"></div>

    
    <div id="tab_interface_container" style="display: block; width: 100%;margin: 40px 5px 20px 5px;position: relative; border: 0px solid red;">

     
     <div id="layout_tab1" class="tab-on" onclick="showid('tab1-content');hideid('tab2-content');hideid('tab3-content');setClass('layout_tab1', 'tab-on');setClass('layout_tab2', 'tab-off');setClass('layout_tab3', 'tab-off');">
      <?php echo lang("Select Template"); ?>
     </div>

     <div id="layout_tab2" class="tab-off" onclick="showid('tab2-content');hideid('tab1-content');hideid('tab3-content');setClass('layout_tab2', 'tab-on');setClass('layout_tab1', 'tab-off');setClass('layout_tab3', 'tab-off');">
      <?php echo lang("Settings"); ?>
     </div>

     <div id="layout_tab3" class="tab-off" onclick="showid('tab3-content','table');hideid('tab1-content');hideid('tab2-content');setClass('layout_tab1', 'tab-off');setClass('layout_tab2', 'tab-off');setClass('layout_tab3', 'tab-on');">
      <?php echo lang("Template Upload"); ?>
     </div>



      <table id="tab1-content" border="0" cellspacing="0" cellpadding="0" class="feature_sub tab_content" style="display: table;">
         <tr>
            <td>


   <table width="300" border="0" cellpadding="6" cellspacing="0" class="feature_sub" style="float: left; margin: 10px;border: none;">
    <tr>
     <td valign="top" style="border: 0px solid green;">

      <form name="ctemplate" method="post" action="site_templates.php" id="change-template-form">
      <input type="hidden" name="action" value="template">
      <table width="300" border="0" cellpadding="6" cellspacing="0" class="feature_sub" style="float: left; margin: 10px;">
       <tr>
        <td align="left" class="fsub_title"><?php echo lang("Choose Site Template"); ?></td>


        <td align="right" class="fsub_title">
<?php
# Build help icon onclick action here (dev readability)
$onevent_bsthelp = "";
$onevent_bsthelp .= "toggleid('pophelp_base_template');";
//$onevent_bsthelp .= "toggleid('logo_image_select', 'visibility');";
$onevent_bsthelp .= "toggleid('base_sel', 'visibility');";
?>
         <img border="0" src="../includes/display_elements/graphics/help_icon-fsub_title.gif" onClick="<?php echo $onevent_bsthelp; ?>" class="hand">
        </td>
       </tr>
       <tr>
        <td align="center" valign="middle" class="text">
         <table border="0" cellpadding=0 cellspacing=0 width=200 height=137 style="border: 3px dashed #336699;">
          <tr>
           <td align="center" valign="middle" class="text" bgcolor="#EFEFEF">
            <span id="prevshot"><B><?php echo lang("Screenshot"); ?></B></SPAN>
           </td>
          </tr>
         </table>
        </td>
       </tr>
       <tr>
        <td align="center" valign="middle" class="text">
         <select id="base_sel" name="site" onkeydown="show_template(this.value);" onchange="show_template(this.value);" style="font-family: Tahoma; font-size: 8pt; visibility: visible;">
          <?php echo $SELECT_OPTS; ?>
         </select>
         <BR>
         <input type="hidden" name="newdesign" value="1">

         <?php
         if ($CONTENTAREA_VAR == "FIXED") { $tF = "CHECKED"; $tL = ""; }
         if ($CONTENTAREA_VAR == "LIQUID") { $tF = ""; $tL = "CHECKED"; }
         ?>

         <BR>
         <div align=center>

         

         <?php echo lang("Don't see a template you like"); ?>?<br/>
         [ <a href="site_templates/browse_templates/browse_templates.php"><?php echo lang("Browse Our Template Archive"); ?></a> ]
          
          <br/><br/>
<?php
# Show get more templates link?


if ( plugins_allowed() && $_SESSION['hostco']['get_more_templates_link'] != "off" ) {
   echo "<p style=\"margin: 0;text-align: right;\"><span onclick=\"window.open('http://".$_SESSION['hostco']['get_more_templates_url']."/Templates.php','Soholaunch_Addons', 'width='+screen.availWidth+', height='+screen.availHeight+', location, status, toolbar, resizeable, menubar, scrollbars');\" class=\"hand orange normal unbold font90 uline\" style=\"font-size: 90%; letter-spacing: normal;\">".lang("Get more templates")."</span></p>\n";
}
?>

            <?php
            if(isset($_GET['success']) && $_GET['success'] == 1){
               echo "<center><h2 class=\"green\" style=\"padding: 10px; background: #FFFFFF; border: 1px solid #666666;\">".lang("Template Uploaded")>"<i>!</i></h2></center>\n";
            }elseif(isset($_GET['success']) && $_GET['success'] == 0){
               echo "<center><h2 class=\"red\" style=\"padding: 10px; background: #FFFFFF; border: 1px solid #666666;\">".lang("Template Upload Error")."<i>!</i></h2></center>\n";
            }
            if($temp_set == 1){
               echo "<center><h2 class=\"green\" style=\"padding: 10px; background: #FFFFFF; border: 1px solid #666666;\">".lang("Template Set")."<i>!</i></h2></center>\n";
            }
//          if(isset($_GET['success'])){
//             echo "<center><h1 class=\"green\" style=\"padding: 10px;\">Template Uploaded!</h1></center>\n";
//          }
            ?>
          
         </DIV>
        </td>
       </tr>
      </table>
      </form>
   </td>
   <td style="vertical-align: top;padding-top: 16px;">
   

      <input type="hidden" id="current_template" value="<?php echo $CUR_TEMPLATE; ?>" />
<?php
   if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) ){
      echo "<div id=\"template_info\" style=\"width: 350px;\"></div>\n";
   }else{
      echo "<div id=\"template_info\" style=\"width: 365px;\"></div>\n";
   }
// echo "(".$is_soho.")";
// echo "<div id=\"pop_save\" align=\"center\" style=\"display: none;\">\n";
// echo "<input type=\"button\" value=\" Save Changes \" ".$btn_save." onClick=\"popSave();\" style=\"width: 150px;\">\n";
// echo "</div>\n";






   if($indiv_temp_count != 0){
      if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) ){
         echo "<table width=\"350\" cellspacing=\"0\" cellpadding=\"6\" border=\"1\" onClick=\"toggleid('pageTemplates')\" style=\"margin-top: 10px; margin-bottom: 5px;\" class=\"feature_sub\">\n";
      }else{
         echo "<table width=\"365\" cellspacing=\"0\" cellpadding=\"6\" border=\"1\" onClick=\"toggleid('pageTemplates')\" style=\"margin-top: 10px;\" class=\"feature_sub\">\n";
      }
      echo "  <tbody>\n";
      echo "   <tr>\n";
      echo "      <td valign=\"middle\" bgcolor=\"#FFFFFF\" align=\"left\" onMouseOut=\"this.style.background='#A5C6E6'\" onMouseOver=\"this.style.background='#99BEE3'\" class=\"bg_dblue\" style=\"cursor: pointer; text-align: center; font-weight: bold;\" >\n";
      echo "         ".lang("Some pages on your site have been assigned to a specific template. Click here to view the features for these templates.")."\n";
      echo "      </td>\n";
      echo "   </tr>\n";
      echo "  </tbody>\n";
      echo "</table>\n";
   }
   echo "<div id=\"pageTemplates\" style=\"display: none;\">\n";

   if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) ){
      echo "<table width=\"350\" cellspacing=\"0\" cellpadding=\"6\" border=\"0\" style=\"margin-top: 10px; margin-bottom: 10px; margin-right: 10px;\" class=\"feature_sub\">\n";
   }else{
      echo "<table width=\"365\" cellspacing=\"0\" cellpadding=\"6\" border=\"0\" style=\"margin-top: 10px; margin-bottom: 10px; margin-right: 10px;\" class=\"feature_sub\">\n";
   }
   echo "  <tbody>\n";

   $is_soho = 0;
   for($s = 0; $s < $indiv_temp_count; $s++){
      $pageName = $SUB_TEMP_NAME[$s]; // Convenience
      $is_soho = 0;

      echo "<tr>\n";
      echo " <td align=\"left\" class=\"fsub_title\" style=\"background-color: #E0EBF1;\">\n";
      echo "  ".$SUB_HTML_TEMP[$s]." ".lang("Template Features")."<br/><font color=\"#666666\" style=\"font-size: 10px;\">(".$SUB_TEMP_NAME[$s].")</font>\n";
      echo " </td>\n";
      echo "</tr>\n";

      if(eregi("#CONTENT#", $SUB_HTML[$s])){
         $is_soho = 1;
         echo "   <tr>\n";
         echo "   <td valign=\"middle\" align=\"left\" class=\"text\"><b>".lang("Content Area")."</b> - ".lang("Add content by going to")." <a href=\"open_page.php\">".lang("Edit Pages")."</a></td>\n";
         echo "   </tr>\n";
      }
      if(eregi("#VMENU#", $SUB_HTML[$s]) || eregi("#VMAINS#", $SUB_HTML[$s]) || eregi("#VSUBS#", $SUB_HTML[$s])){
         $is_soho = 1;
         echo "   <tr>\n";
         echo "   <td valign=\"middle\" align=\"left\" class=\"text\"><b>".lang("Vertical Menu")."</b> - ".lang("Edit the menu layout in")." <a href=\"auto_menu_system.php\">".lang("Menu Navigation")."</a></td>\n";
         echo "   </tr>\n";
      }
      if(eregi("#HMENU#", $SUB_HTML[$s]) || eregi("#HMAINS#", $SUB_HTML[$s]) || eregi("#HSUBS#", $SUB_HTML[$s])){
         $is_soho = 1;
         echo "   <tr>\n";
         echo "   <td valign=\"middle\" align=\"left\" class=\"text\"><b>".lang("Horizontal Menu")."</b> - ".lang("Edit the menu layout in")." <a href=\"auto_menu_system.php\">".lang("Menu Navigation")."</a></td>\n";
         echo "   </tr>\n";
      }
      if(eregi("#TMENU#", $SUB_HTML[$s])){
         $is_soho = 1;
         echo "   <tr>\n";
         echo "   <td valign=\"middle\" align=\"left\" class=\"text\"><b>".lang("Text Menu")."</b> - ".lang("Edit the menu layout in")." <a href=\"auto_menu_system.php\">".lang("Menu Navigation")."</a></td>\n";
         echo "   </tr>\n";
      }
      if(eregi("#USERSONLINE#", $SUB_HTML[$s])){
         $is_soho = 1;
         echo "   <tr>\n";
         echo "   <td valign=\"middle\" align=\"left\" class=\"text\"><b>".lang("Users Online")."</b> - ".lang("Displays number of users currently online").".</td>\n";
         echo "   </tr>\n";
      }
      if(eregi("#LOGO#", $SUB_HTML[$s])){
         $is_soho = 1;
         echo "   <tr>\n";
         echo "   <td valign=\"middle\" align=\"left\" class=\"text\"><b>".lang("Logo Text")."</b> - ".lang("Edit logo on the")." <a href=\"javascript:showid('tab2-content');hideid('tab1-content');\">".lang("Template Settings")."</a> ".lang("tab").".</td>\n";
         echo "   </tr>\n";
      }
      if(eregi("#LOGOIMG#", $SUB_HTML[$s])){
         $is_soho = 1;
         echo "   <tr>\n";
         echo "   <td valign=\"middle\" align=\"left\" class=\"text\"><b>".lang("Logo Image")."</b> - ".lang("Edit logo image on the")." <a href=\"javascript:showid('tab2-content');hideid('tab1-content');\">".lang("Template Settings")."</a> ".lang("tab").".</td>\n";
         echo "   </tr>\n";
      }
      if(eregi("#SLOGAN#", $SUB_HTML[$s])){
         $is_soho = 1;
         echo "   <tr>\n";
         echo "   <td valign=\"middle\" align=\"left\" class=\"text\"><b>".lang("Slogan Text")."</b> - ".lang("Edit slogan on the")." <a href=\"javascript:showid('tab2-content');hideid('tab1-content');\">".lang("Template Settings")."</a> ".lang("tab").".</td>\n";
         echo "   </tr>\n";
      }
################################################
##TEMPORARILY DISABLE BLOG FEATURES#############
//      if(eregi("#BOX1#", $SUB_HTML[$s])){
//         $is_soho = 1;
//         echo "   <tr>\n";
//         echo "   <td valign=\"middle\" align=\"left\" class=\"text\"><b>".lang("Template Boxes")."<font color=\"#f7941d\" size=\"1\"><sup><i>".lang("NEW!")."</i></sup></font></b> - <a href=\"promo_boxes/promo_boxes.php\">".lang("Edit Template Boxes Now")."!</a></td>\n";
//         echo "   </tr>\n";
//      }
//      if(eregi("#PROMOTXT", $SUB_HTML[$s])){
//         $is_soho = 1;
//         echo "   <tr>\n";
//         echo "   <td valign=\"middle\" align=\"left\" class=\"text\"><b>".lang("Promotional Boxes")."</b> - <a href=\"blog.php\">".lang("Edit Promotional Boxes Now")."!</a></td>\n";
//         echo "   </tr>\n";
//      }
//      if(eregi("#NEWSBOX", $SUB_HTML[$s])){
//         $is_soho = 1;
//         echo "   <tr>\n";
//         echo "   <td valign=\"middle\" align=\"left\" class=\"text\"><b>".lang("News Boxes")."</b> - <a href=\"blog.php\">".lang("Edit News Boxes Now")."!</a></td>\n";
//         echo "   </tr>\n";
//      }
      if(eregi("#BIZ-", $SUB_HTML[$s])){
         $is_soho = 1;
         echo "   <tr>\n";
         echo "   <td valign=\"middle\" align=\"left\" class=\"text\"><b>".lang("Business Info")."</b> - ".lang("Edit Business Info on the")." <a href=\"javascript:showid('tab2-content');hideid('tab1-content');\">".lang("Template Settings")."</a> ".lang("tab").".</td>\n";
         echo "   </tr>\n";
      }
      if(eregi("#CUSTOMPHP", $SUB_HTML[$s]) || eregi("#CUSTOMINC", $SUB_HTML[$s]) || eregi("#INC-", $SUB_HTML[$s])){
         $is_soho = 1;
         echo "   <tr>\n";
         echo "   <td valign=\"middle\" align=\"left\" class=\"text\"><b>".lang("Custom Includes")."</b> - ".lang("Advanced includes add additional functionality").".</td>\n";
         echo "   </tr>\n";
      }

      # _userimg-
      if(eregi("_userimg-", $SUB_HTML[$s]) ){
         $is_soho = 1;
         echo "   <tr>\n";
         echo "    <td valign=\"middle\" align=\"left\" class=\"text\">\n";
         echo "     <b><a href=\"site_templates/template_images.php?templatefolder=".$page_temps[$s]."\">".lang("Templates Images")."</a></b> - \n";
         echo "     ".lang("Swap-out certain images within the template for others of your choosing").".\n";
         echo "    </td>\n";
         echo "   </tr>\n";
      }

      # Error and do not allow save if no pound vars found in template html
      if ( $is_soho != 1 ) {
         echo "   <tr>\n";
         echo "   <td valign=\"middle\" align=\"left\" class=\"text\">\n";
         echo "      <b style=\"color:red;\">".lang("This template does not seem to be in")." ".$_SESSION['hostco']['sitebuilder_name']." ".lang("format").".</b> -\n";
         echo "      ".lang("To change the template for this page go to 'Page Properties' in the Page Editor and select a different template").".\n";
         echo "      Click <a href=\"open_page.php\">here</a> ".lang("to go to Edit Pages").".\n";
         echo "   </td>\n";
         echo "   </tr>\n";
      }

      # Unset page assignment option
      echo "   <tr>\n";
      echo "    <td valign=\"middle\" align=\"right\" class=\"text\">\n";
      echo "	  <a href=\"site_templates.php?unset_pagetemplate=".$pageName."\" class=\"del\">".lang("Un-set this assignement")."</a>\n";
      echo "    </td>\n";
      echo "   </tr>\n";

   } // End for loop through template html

   echo "</tbody>\n";
   echo "</table>\n";
   echo "</div>\n";

   # Fix Internal Server Error
   echo "<div id=\"fix_internal_server_error_link\" class=\"right red bold\" style=\"display: none; background: #efefef; border: 1px solid #980000; position: absolute; bottom: -1px; right: 10px; padding: 10px;\">\n";
   echo " ".lang("Getting an Internal Server Error")."?\n";
   echo " <a href=\"".$_SERVER['PHP_SELF']."?todo=fix_suexec_error\">".lang("Click here to fix it").".</a>\n";
   echo "</div>\n";
?>
        </td>
       </tr>
      </table>
            <td>
         <tr>
      </table>

<script type="text/javascript">
// Only show error fix link if 'Internal Server Error' actually comes up
function internal_server_error() {
   var boxhtml = document.getElementById('template_info').innerHTML;
   var errchk = boxhtml.search('Internal Server Error');
   if ( errchk != '-1' ) {
      //alert('ERROR! ['+errchk+']');
      toggleid('fix_internal_server_error_link');
   } else {
      //alert('no error! ['+errchk+']');
   }
}

// Give AJAX time to load div contents
window.setTimeout("internal_server_error()", 3000);

<?php
# Alert if just fixed suexec error (so they don't think it just went away suspiciously)
if ( $suexec_error_fixed ) {
   echo "alert('".lang("Problem should be fixed").". ".lang("The \"Template Features\" box should appear where the error message was").".";
   echo "\\n\\n".lang("FIX DETAILS (for geeks): Attempted to tighten permissions on one of the system folders (sohoadmin/site_templates) to a suexec-approved 0755").".";
   echo "');\n";
   //echo "alert('Tightened the permissions on the sohoadmin/site_templates folder to 0755. No more Internal Server Error for you!');";
}
?>

function subAllForms(disForm){
	//subAllForms('#template1');
	$(disForm).append('<input type="hidden" name="df_hdrtxt" value="'+$("#df_hdrtxt").val()+'" />');
	$(disForm).append('<input type="hidden" name="df_slogan" value="'+$("#df_slogan").val()+'" />');
	$(disForm).append('<input type="hidden" name="df_logo" value="'+$("#df_logo").val()+'" />');
	//$(disForm).append('<input type="hidden" name="logo_image_select" value="'+$("#logo_image_select").val()+'" />');
	$(disForm).append('<input type="hidden" name="df_company" value="'+$("#df_company").val()+'" />');
	$(disForm).append('<input type="hidden" name="df_phone" value="'+$("#df_phone").val()+'" />');
	$(disForm).append('<input type="hidden" name="df_address1" value="'+$("#df_address1").val()+'" />');
	$(disForm).append('<input type="hidden" name="df_city" value="'+$("#df_city").val()+'" />');
	$(disForm).append('<input type="hidden" name="df_state" value="'+$("#df_state").val()+'" />');
	$(disForm).append('<input type="hidden" name="df_zip" value="'+$("#df_zip").val()+'" />');
	$(disForm).append('<input type="hidden" name="df_country" value="'+$("#df_country").val()+'" />');
	$(disForm).append('<input type="hidden" name="copyright" value="'+$("#copyright").val()+'" />');
	$(disForm).append('<input type="hidden" name="wap_template" value="'+$("#wap_template").val()+'" />');
	$(disForm).append('<input type="hidden" name="df_favicon" value="'+$("#df_favicon").val()+'" />');
	$(disForm).append('<input type="hidden" name="actions" value="tab2" />');
	$(disForm).append('<input type="hidden" name="showTab" value="tab2" />');
	
	$(disForm).submit();
}

</script>


<?php
/*---------------------------------------------------------------------------------------------------------*
 ___       _    _    _                   _____       _
/ __| ___ | |_ | |_ (_) _ _   __ _  ___ |_   _|__ _ | |__
\__ \/ -_)|  _||  _|| || ' \ / _` |(_-<   | | / _` || '_ \
|___/\___| \__| \__||_||_||_|\__, |/__/   |_| \__,_||_.__/
                             |___/
/*---------------------------------------------------------------------------------------------------------*/
?>

<table id="tab2-content" border="0" cellspacing="0" cellpadding="5" class="feature_sub tab_content" style="display: none;">
 <tr>
  <td>

<?php
if(isset($_REQUEST['showTab']) && $_REQUEST['showTab'] == "tab2"){
   echo $msg;
}


/*---------------------------------------------------------------------------------------------------------*
 _   _                 ___
| | | | ___ ___  _ _  |_ _| _ __   __ _  __ _  ___  ___
| |_| |(_-</ -_)| '_|  | | | '  \ / _` |/ _` |/ -_)(_-<
 \___/ /__/\___||_|   |___||_|_|_|\__,_|\__, |\___|/__/
                                        |___/

/*---------------------------------------------------------------------------------------------------------*/
# User images?
if ( findin_template("_userimg") ) {
   $involved_templates = inuse_templates("_userimg"); // Array of all template folders containing "_userimg"
   $total_userimgs = 0;
   echo "      <div id=\"block-userimage\">\n";
   echo "      <table width=\"685\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"feature_sub\" style=\"margin-top: 5px;\">\n";
   echo "       <tr>\n";
   echo "        <td class=\"fsub_title\">".lang("User-changeable template images")."</td>\n";
   echo "       </tr>\n";
   echo "       <tr>\n";
   echo "        <td style=\"padding: 15px;\">\n";
   echo "         <p>".lang("One or more of the templates you are using allows certain images to be swapped-out for others of your choosing").".</p>\n";
   echo "         <p style=\"text-align: center;\">";
   //echo "			<input type=\"button\" value=\"".lang("Manage template images")." &gt;&gt;\" ".$_SESSION['btn_build']." onclick=\"document.location.href='site_templates/template_images.php?templatefolder=".$CUR_TEMPLATE."';\"/>\n";
   echo "			<button type=\"button\" class=\"grayButton\" onclick=\"document.location.href='site_templates/template_images.php?templatefolder=".$CUR_TEMPLATE."';\"/><span><span>".lang("Manage template images")." &gt;&gt;</span></span></button>\n";
   echo "			</p>\n";
   echo "        </td>\n";
   echo "       </tr>\n";
   echo "      </table>\n";
   echo "      </div>\n";

} // End if any userimgs exist
?>

      <form id="template1" name="template1" method="post" action="site_templates.php">
      <input type="hidden" name="action" value="head_slogan">
      <input type="hidden" name="showTab" value="tab2">
         <table width="685"  border="0" cellspacing="0" cellpadding="0" class="feature_sub" style="border-bottom: 0px; margin-top: 5px;">
          <tr>
           <td class="fsub_title"><?php echo lang("Website Title & Slogan"); ?></td>
           <td align="right" class="fsub_title">
          <button type="button" class="greenButton" id="saveHeadSlogan" onclick="subAllForms('#template1');"><span><span><?php echo lang("Save"); ?></span></span></button>
           </td>
          </tr>
         </table>
         <table width="685"  border="0" cellpadding="5" cellspacing="0" class="feature_sub">
          <tr>
           <td width="50%">
            <?php
            echo lang("Set Website Title").":<br>\n";
			if($_GET['go']=='site_titletxt'){
				echo "<input class=\"catselect\" type=\"text\" size=\"45\" name=\"df_hdrtxt\" id=\"df_hdrtxt\" style=\"border:1px solid #EAA510;\" value=\"".$headertext."\">\n";
			} else {
          		echo "<input class=\"catselect\" type=\"text\" size=\"45\" id=\"df_hdrtxt\" name=\"df_hdrtxt\" value=\"".$headertext."\">\n";	
			}
            ?>
           </td>
           <td width="50%" align="center">&nbsp;
           </td>
          </tr>
          <tr>
           <td>
            <?php 
            echo lang("Set Website Slogan").":<br>\n";
          if($_GET['go']=='site_df_slogan'){
            	echo "<input class=\"catselect\" type=\"text\" id=\"df_slogan\" name=\"df_slogan\" value=\"".$subheadertext."\" style=\"border:1px solid #EAA510; width: 350px;\">\n";
          } else {
          	echo "<input class=\"catselect\" type=\"text\" id=\"df_slogan\" name=\"df_slogan\" value=\"".$subheadertext."\" style=\"width: 350px;\">\n";	
          }            
            ?>
           </td>
           <td align="center">&nbsp;
           </td>
          </tr>
          <tr>
           <td>&nbsp;
           </td>
           <td align="center">&nbsp;
           </td>
          </tr>
         </table>
         </form>


         <form enctype="multipart/form-data" method="POST" action="site_templates.php" name="logo_form" id="logo_form">
         <input type="hidden" name="MAX_FILE_SIZE" value="3000000">
         <input type="hidden" name="action" value="upload_logo">
         <input type="hidden" name="showTab" value="tab2">
         <table width="685"  border="0" cellpadding="5" cellspacing="0" class="feature_sub" style="border-bottom: 1px;">
          <tr>
           <td class="fsub_title">
   <?php echo lang("Website Logo Image"); ?>
           </td>
           <td align="right" class="fsub_title">
               
               <button type="button" class="greenButton" id="saveLogo" onclick="subAllForms('#logo_form');"><span><span><?php echo lang("Save"); ?></span></span></button>
           </td>
          </tr>
         </table>
         <table width="685"  border="0" cellpadding="5" cellspacing="0" class="feature_sub" >
          <tr>
           <td width="50%">
            <table width="350" border="0" cellpadding="5" cellspacing="0" class="text">
             <tr>
              <td>
      <?php echo lang("Select Logo Image"); ?>:
              </td>
             </tr>
             <tr>
              <td>
               <?php
                $logo_select = "   <select name=\"df_logo\" onChange=\"showPreview(this.value)\" style=\"width: 300px; visibility: visible;\" class=\"text\" id=\"df_logo\">\n";

                // Build logo image drop-down
                // -----------------------------------
                $logo_select .= "    <OPTION VALUE=\"\">[".lang("No Image")."]</OPTION>\n";

                $logo_select .= $img_selection;

                $logo_select .= "   </select>\n";
                echo $logo_select;
               ?>
              </td>
             </tr>
             <tr>
              <td>&nbsp;
              </td>
             </tr>
             <tr>
              <td>
      <?php echo lang("Upload logo image"); ?>:
              </td>
             </tr>
             <tr>
              <td>
                  <input type="file" name="FILE1" class="tfield" style="width: 350px;" onChange="showUpload()"><br/><br/>
                  
                  <button type="button" class="greenButton" style="display:none;" id="upLogo" onClick="upload_logo();"><span><span><?php echo lang("Upload"); ?></span></span></button>
              </td>
             </tr>
            </table>
           </td>
           <td width="50%" align="center">
            <div id="logo_preview">
            <?php
            if($df_logo != ""){
               $tempArray = getImageSize($directory."/".$df_logo);
               $origW = $tempArray[0];
               $origH = $tempArray[1];
               if($origW > 150){
                  $origW = 150;           
               }
               if($origH > 100){
                  $origH = 100;
			}
               echo "   <img id=\"daImage\" style=\"padding: 10px; border: 1px solid #666666; width:".$origW."px; height:".$origH."px;\" src=\"http://".$this_ip."/images/".$df_logo."\">\n";
               echo "   <div style=\"position: absolute; z-index: -999; top:-99999px; left:-99999px; width:1500px; height:1500px;\"><img id=\"daImagehidden\" src=\"http://".$this_ip."/images/".$df_logo."\"></div>\n";
            }else{
               echo "   <img id=\"daImage\" style=\"padding: 10px; border: 1px solid #666666;\" src=\"http://".$this_ip."/sohoadmin/program/modules/spacer.gif\" width=\"150\" height=\"100\">\n";
               echo "   <div style=\"position: absolute; z-index: -999; top:-99999px; left:-99999px; width:1500px; height:1500px;\"><img id=\"daImagehidden\" src=\"http://".$this_ip."/sohoadmin/program/modules/spacer.gif\"></div>\n";
            }
            ?>

            </div>
           </td>
          </tr>
         </table>
         </form>
     

         <form id="template3" name="template3" method="post" action="site_templates.php">
         <input type="hidden" name="action" value="business">
         <input type="hidden" name="showTab" value="tab2">
            <table width="685"  border="0" cellpadding="5" cellspacing="0" class="feature_sub" style="border-bottom: 0px;">
             <tr>
              <td class="fsub_title">
      <?php echo lang("Business Information"); ?>
              </td>
              <td align="right" class="fsub_title">
               
               <button type="button" id="saveBusiness" class="greenButton" onclick="subAllForms('#template3');"><span><span><?php echo lang("Save"); ?></span></span></button>
              </td>
             </tr>
            </table>

            <table width="685"  border="0" cellpadding="5" cellspacing="0" class="feature_sub" style="margin-bottom: 10px;">
             <tr>
              <td width="15%">
      <?php echo lang("Company Name"); ?>: <br>
              </td>
              <td width="85%">
               <input name="df_company" id="df_company" type="text" class="tfield" style="width: 225px;" value="<?php echo $df_company; ?>">
              </td>
             </tr>

             <tr>
              <td>
      <?php echo lang("Phone Number"); ?>:<br>
              </td>
              <td>
               <input name="df_phone" id="df_phone" type="text" class="tfield" value="<?php echo $df_phone; ?>">
              </td>
             </tr>
             <tr>
              <td valign="top">
      <?php echo lang("Street Address"); ?>:
              </td>
              <td>
               <input name="df_address1" id="df_address1" type="text" class="tfield" style="width: 185px;" value="<?php echo $df_address1; ?>">
              </td>
             </tr>
             <tr>
              <td>
      <?php echo lang("City / Region"); ?>:
              </td>
              <td>
               <input name="df_city" id="df_city" type="text" class="tfield" value="<?php echo $df_city; ?>">
              </td>
             </tr>
             <tr>
              <td>
      <?php echo lang("State / Province"); ?>:
              </td>
              <td>
               <input name="df_state" id="df_state" type="text" class="tfield" value="<?php echo $df_state; ?>">
              </td>
             </tr>
             <tr>
              <td>
      <?php echo lang("Zip / Postal"); ?>:
              </td>
              <td>
               <input name="df_zip" id="df_zip" type="text" class="tfield" style="width: 75px;" value="<?php echo $df_zip; ?>">
              </td>
             </tr>
             <tr>
              <td>
      <?php echo lang("Country"); ?>:
              </td>
              <td>
               <select name="df_country" id="df_country" style='font-family: Arial; font-size: 10px; width: 145px;'>
               <?php
               //Build country list and select current
               for ($n=0;$n < $numNats;$n++) {
                  $sel = "";
                  if ($natNam[$n] == $df_country) { $sel = "selected"; }
                  echo "    <option value=\"$natNam[$n]\" $sel>$natNam[$n]</option>\n";
               }
               ?>
               </select>
              </td>
             </tr>
             <tr>
              <td>
               <?php echo lang("Copyright Text"); ?>:
              </td>
              <td>
               &copy; <input name="copyright" id="copyright" type="text" class="tfield" style="width: 400px;" value="<?php echo $copyright; ?>">
              </td>
             </tr>

            </table>
           </form>
  



      <form id="waptemplate" name="waptemplate" method="post" action="site_templates.php">
      <input type="hidden" name="action" value="wap_preference">
      <input type="hidden" name="showTab" value="tab2">
         <table width="685"  border="0" cellspacing="0" cellpadding="0" class="feature_sub" style="border-bottom: 0px; margin-top: 5px;">
          <tr>
           <td class="fsub_title"><?php echo lang("Mobile Device Template"); ?></td>
           <td align="right" class="fsub_title">
            <button type="button" class="greenButton" onclick="subAllForms('#waptemplate');"><span><span><?php echo lang("Save"); ?></span></span></button>
           </td>
          </tr>
         </table>
         <table width="685"  border="0" cellpadding="5" cellspacing="0" class="feature_sub">
          <tr>
           <td colspan=2>
            <?php echo lang("Use 'mobile-device optimized template' for visitors viewing the site from a mobile device?"); ?>
           </td>
          </tr>
          <tr>
           <td colspan=2>
			<select id="wap_template" name="wap_template" style="visibility: visible;" class="text">
<?php


$wapsetting = new userdata('wap_template');
//$wapsetting->set('template', 'WAP-minimal-none') ;
if($wapsetting->get('template') != ''){
	echo "			<OPTION value=\"no\">No</OPTION>\n";
	echo "			<OPTION value=\"yes\" SELECTED>Yes</OPTION>\n";
} else {
	echo "			<OPTION value=\"no\" SELECTED>No</OPTION>\n";
	echo "			<OPTION value=\"yes\">Yes</OPTION>\n";
}
?>
			</select>
           </td>
          </tr>
         </table>
      </form>




         <form enctype="multipart/form-data" method="POST" action="site_templates.php" name="favicon_form" id="favicon_form">
         <input type="hidden" name="MAX_FILE_SIZE" value="3000000">
         <input type="hidden" name="action" value="upload_logo">
         <input type="hidden" name="showTab" value="tab2">
         <table width="685"  border="0" cellpadding="5" cellspacing="0" class="feature_sub" style="border-bottom: 1px;">
          <tr>
           <td class="fsub_title">
   <?php echo lang("Website Favicon Image"); ?>
           </td>
           <td align="right" class="fsub_title">

               <button type="button" class="greenButton" onclick="subAllForms('#favicon_form');"><span><span><?php echo lang("Save"); ?></span></span></button>
           </td>
          </tr>
         </table>
         <table width="685"  border="0" cellpadding="5" cellspacing="0" class="feature_sub" >
          <tr>
           <td width="50%">
            <table width="400" border="0" cellpadding="5" cellspacing="0" class="text">
             <tr>
              <td>
      <?php echo lang("Select Favicon Image").": (".lang("recommended size is 16x16 or 32x32 pixels").")"; ?>
              </td>
             </tr>
             <tr>
              <td>
               <?php
                $logo_select = "   <select id=\"df_favicon\" name=\"df_favicon\" onChange=\"showPreviewFav(this.value)\" style=\"width: 300px; visibility: visible;\" class=\"text\" id=\"favicon_image_select\">\n";

                // Build logo image drop-down
                // -----------------------------------
                $logo_select .= "    <OPTION VALUE=\"\">[".lang("No Image")."]</OPTION>\n";

                $logo_select .= $favicon_selection;

                $logo_select .= "   </select>\n";
                echo $logo_select;
                
               ?>
              </td>
             </tr>
             <tr>
              <td>&nbsp;
              </td>
             </tr>
             <tr>
              <td>
      <?php echo lang("Upload Favicon Image"); ?>:
              </td>
             </tr>
             <tr>
              <td>
                  <input type="file" name="FILE1" class="tfield" style="width: 350px;" onChange="showUploadFavicon();"><br/><br/>
                  <button type="button" id="upFavicon" style="display:none;" class="greenButton" onClick="upload_logo_favicon();"><span><span><?php echo lang("Upload"); ?></span></span></button>
              </td>
             </tr>
            </table>
           </td>
           <td width="50%" align="center">
            <div id="favicon_preview">
            <?php
            if($df_favicon != ""){
               $tempArray = getImageSize($doc_root."/".$df_favicon);
               $origW = $tempArray[0];
               $origH = $tempArray[1];
               if($origW > 32){
                  $origW = 32;                  
               }
               if($origH > 32){
                  $origH = 32;                  
               }

               echo "   <img id=\"daFavicon\" style=\"width: ".$origW."px; height: ".$origH."px; padding: 0px; border: 0px solid #666666;\" src=\"http://".$this_ip."/".$df_favicon."\">\n";
               echo "   <div style=\"position: absolute; z-index: -999; top:-99999px; left:-99999px; width:1500px; height:1500px;\" id=\"daFaviconDiv\"><img id=\"daFaviconhidden\" src=\"http://".$this_ip."/".$df_favicon."\"></div>\n";
               
            }else{
               echo "   <img id=\"daFavicon\" style=\"padding: 0px; border: 0px solid #666666;\" src=\"http://".$this_ip."/sohoadmin/program/modules/spacer.gif\">\n";
               echo "   <div style=\"position: absolute; z-index: -999; top:-99999px; left:-99999px; width:1500px; height:1500px;\" id=\"daFaviconDiv\"><img id=\"daFaviconhidden\" src=\"http://".$this_ip."/sohoadmin/program/modules/spacer.gif\"></div>\n";                              
            }
            ?>

            </div>
           </td>
          </tr>
         </table>
         </form>
         





            <td>
         <tr>
      </table>

      <table id="tab3-content" align="center" border="0" cellspacing="0" cellpadding="5" class="feature_sub tab_content" style="display: none;">
         <tr>
            <td>



            <table cellpadding="0" cellspacing="0" border="0" width="685" align="center" class="feature_sub" style="margin-top: 5px;">
             <tr>
              <td width=100% class="fsub_title" valign="top">
               <?php echo lang("Upload Custom Template Folder (Zipped)"); ?>
              </td>
              <td class="fsub_title" valign="top" align="right" style="padding-right: 10px;">
               <img src="../includes/display_elements/graphics/help_icon-fsub_title.gif" onClick="toggleid('help_upload_template');" valign="middle" style="cursor: pointer;">
              </td>
             </tr>
             <tr>
              <td colspan="2" valign="top" align="left" style="background-color: #f8f9fd;">
               <form enctype="multipart/form-data" action="site_templates/ul_custom.php" method="POST" name="FormUpload">
               <input type="hidden" name="MAX_FILE_SIZE" value="3000000">
               
                <table border="0" cellspacing="0" cellpadding="8" width="100%" class="text">

               
                  <tr>
                   <td colspan="5" class="text gray" style="padding: 0px;">
                    <div id="help_upload_template" style="font-size: 10px; display: none; padding: 10px;">
                     <b><?php echo lang("To upload a custom template"); ?>:</b><br>
                      1. <?php echo lang("Place all files(images,index.html,custom.css) into a folder and name the folder like this"); ?> : <i><?php echo lang("Category-Sub_Category-Color"); ?></i><br>
                      <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo lang("Example"); ?> : <?php echo lang("AUTOMOTIVE-Classic_Cars-Blue"); ?></b><br>
                      2. <?php echo lang("Zip the folder and upload it below"); ?>.  <?php echo lang("After upload the template will be availible in the list of templates"); ?>.
                    </div>&nbsp;
                   </td>
                  </tr>

                  <tr>
                   <td width="30" align="center"><img src="site_templates/zip_icon-20px.gif" valign="middle"></td>
                   <td align="right" valign="middle" class="text" width="105"><?php echo lang("Template .zip file"); ?>:</td>
                   <td align=left valign="middle" class="text">
                    <input name="FILE1" type="file" >
                   </td>
                   <td align="left" valign="middle" class="text">
                    <span onclick="upload_files();" style="display: block; cursor: pointer; padding: 0px 0px 0px 0px; width: 200px; height: 32px; background-image: url('site_templates/template_upload_btn.gif');">
                     <span style="display: block; vertical-align: top; padding: 10px 10px 0px 40px; border: 0px solid red;"><?php echo lang("Upload Template File(s)"); ?></span>
                    </span>
            

                   </td>
                   </td>
                  </tr>
                  
                  <tr>
                   <td colspan="5" style="padding: 0px;">&nbsp;</td>
                  </tr>
                </table>
                </form>
              </td>
             </tr>
            </table>

            <td>
         <tr>
      </table>

   </div>
   </div>
<?php


echo "<script language=\"javascript\">\n";

echo "show_hide_layer('Layer1','','hide','userOpsLayer','','show');\n";

echo "   show_template('".$CUR_TEMPLATE."')\n";

if(isset($_REQUEST['showTab'])){
   echo "   showid('".$_REQUEST['showTab']."-content')\n";
   echo "   hideid('tab1-content')\n";
   echo "   setClass('layout_".$_REQUEST['showTab']."', 'tab-on')\n";
   echo "   setClass('layout_tab1', 'tab-off')\n";
}

//# TESTING: Force default to Settings tab
//echo "   hideid('tab1-content')\n";
//echo "   setClass('layout_tab1', 'tab-off')\n";
//echo "   showid('tab2-content')\n";
//echo "   setClass('layout_tab2', 'tab-on')\n";

# re-show delete template form after delete action?
if ( $_POST['todo'] == "delete_template" ) {
   echo "showid('delete_template_layer');toggleid('base_sel', 'visibility');";
}


if($_GET['go']=='site_titletxt'){				
	echo "$('#df_hdrtxt').focus();\n";
}
if($_GET['go']=='site_df_slogan'){
	echo "$('#df_slogan').focus();\n";
}

echo "</script>\n";

echo div_window();

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Select a template from the drop-down list to see a preview of that template, change template settings or upload your own custom template.");
//$instructions .= lang("<b>Settings</b> : Change your logo, slogan, business information.<br/>");
//$instructions .= lang("<b>Template Upload</b> : Upload your own custom template.");

$module = new smt_module($module_html);
$module->meta_title = lang("Template Manager");
$module->add_breadcrumb_link(lang("Template Manager"), "program/modules/site_templates.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/template_manager-enabled.gif";
$module->heading_text = lang("Template Manager");
$module->description_text = $instructions;
$module->good_to_go();
?>
