<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '' || $_REQUEST['_SESSION'] != '') { exit; }

#=====================================================================================
# Soholaunch(R) Site Management Tool
#
# Author:        Mike Morrison
# Homepage:      http://www.soholaunch.com
# Release Notes: http://wiki.soholaunch.com
#
# This Script: Simple example module to illustrate how to create a new
# module and keep it's look consistent with the rest of the product
#=====================================================================================

error_reporting(E_PARSE);
session_start();

# Include core files
include_once($_SESSION['docroot_path']."/sohoadmin/program/includes/product_gui.php");
include_once($_SESSION['docroot_path']."/sohoadmin/program/includes/smt_module.class.php");

# So you can write straight HTML without having to build every line into a container var (i.e. $disHTML .= "another line of html")
ob_start();


$globalprefObj = new userdata('global');

# Save Google Webmaster Verification
if($_POST['action'] == "googleverify") {
	/*
	-check for file upload
	*/
	if ( $_FILES['google_verify']['name'] != '' ) {
		$test_location = $_SESSION['docroot_path'].'/'.$_FILES['google_verify']['name'];
		copy($_FILES['google_verify']['tmp_name'], $test_location);
		
		if ( file_exists($test_location) ) {
			$report[] = 'File succesfully uploaded to home folder! You should be ready to verify it with Google now';
			$test_url = $_SESSION['docroot_path'].'/'.$_FILES['google_verify']['name'];
			$globalprefObj->set('google_verification_file', $_FILES['google_verify']['name']);
		} else {
			$report[] = 'Something went wrong during the upload. File not found in home directory.';
		}
	} else {
		$report[] = 'No file uploaded. Did you choose a file to upload?';
	}
}
?>

<style type="text/css">
textarea#google_analytics_code {
	margin-top: 5px;
	width: 650px;
	height: 175px;
}
img + p { margin-top: 0; }
#google-tools label { display: block; font-weight: bold; font-size: 14px; }
</style>

<div id="google-tools">
	
	<!---Webmaster Tools-->
	<form action="webmaster-tools.php" enctype="multipart/form-data" method="post">
		<input type="hidden" name="action" value="googleverify"/>
		<img src="images/google-webmaster-tools.png"/>
		<p>Verifying your site with <a href="https://www.google.com/webmasters/tools/home?hl=en" target="_BLANK">Google Webmaster Tools</a> will help you monitor crawl errors that might lower your site's ranking.
			Note: Google offers several methods for verifying your site, but uploading their verification file is the most reliable.</p>
<?php
if ( $globalprefObj->get('google_verification_file') != '' ) {
	if ( !file_exists($_SESSION['docroot_path'].'/'.$globalprefObj->get('google_verification_file')) ) {
		$globalprefObj->set('google_verification_file', '');
	} else {
		$verify_file_url = 'http://'.$_SESSION['docroot_url'].'/'.$globalprefObj->get('google_verification_file');
		echo '<h2>Verification File Found:</h2><p><a href="'.$verify_file_url.'" target="_BLANK">'.$verify_file_url.'</a></p>';
	}
}
?>	
		<h2>Upload new site verification file:</h2>
		<input type="file" name="google_verify"/><br/>
		<button type="submit" class="greenButton" onclick="document.googleverify.submit();"/><span><span>Upload Site Verification File</span></span></button>
	</form>

</div>

<?php
# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

# Note: "Create Pages" used for example purposes. Replace with your own stuff.
$module = new smt_module($module_html);
$module->add_breadcrumb_link(lang("Google Tools"), "program/modules/google/webmaster-tools.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/google-icon.png";
$module->heading_text = lang("Google Webmaster Tools");
$module->description_text = lang("Verify your website with Google Webmaster Tools.");
$module->good_to_go();
?>