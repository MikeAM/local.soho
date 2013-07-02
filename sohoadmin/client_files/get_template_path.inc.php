<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

#======================================================#=================================================================================
# get_template_path.inc.php
# -Determine current template set for this page
# -Define $template_path & $template_folder
#
# This file is included in checkout system files that don't include pgm-template_builder.php until
# the bottom of the file, which means the core of the file doesn't have access (if not including this file) to the template folder path
# for things like detecting whether the template includes a custom css file (as with pgm-more_information.php and shopping_cart.css)
#======================================================#=================================================================================

// Read Template Config File
//$filename = $_SESSION['docroot_path']."/template/template.conf";

//$filename = "sohoadmin/tmp_content/template.conf";
//if(!file_exists($filename)){
//	$filename	 = "../sohoadmin/tmp_content/template.conf";
//}
//
//// 2004-08-01: Create and select default template if none specified (fixes blank screen problem)
//
//if ( !file_exists($filename) ) {
//   $file = fopen("$filename", "w");
//   	fwrite($file, "$default_template");
//   fclose($file);
//   chmod($filename, 0755);
//}
//
//$file = fopen("$filename", "r");
//	$what_template = fread($file,filesize($filename));
//fclose($file);
$default_template = "Professional-Cutting_Edge-blue"; // Dedicated to Mark Reedy ;-)
$globalprefObj = new userdata('global');
$what_template = $globalprefObj->get('site_base_template');
if($what_template == ''){
	$globalprefObj->set('site_base_template', $default_template);
	$what_template = $default_template;
}

$base_template = $what_template;	// In case of individual page definitions

$single_template_change = 0;


//// Does this page have a specific template specified for it? [CUSTOM TEMPLATE]
//// --------------------------------------------------------------------------------
//$filename = $_SESSION['docroot_path']."/media/page_templates.txt";
//
//if ( file_exists($filename) ) {
//   $single_template_change = 1;
//   $file = fopen("$filename", "r");
//   	$template_vars = fread($file,filesize($filename));
//   fclose($file);
//
//   $tmp = split("\n", $template_vars);
//   $tmp_cnt = count($tmp);
//   // $this_page = eregi_replace(" ", "_", $pageRequest);
//   $this_page = "Shopping_Cart";	// Set the "page call" for shopping cart (We can now select a specific template for just cart)
//
//   for ($CTC = 0; $CTC <= $tmp_cnt; $CTC++) {
//   	$this_var = split("=", $tmp[$CTC]);
//   	if ($this_var[0] == $this_page) {
//   		$what_template = $this_var[1];
//   		if ($this_var[1] == "") { $what_template = $base_template; }
//   	}
//   } // End for
//
//} else {
//   $what_template = $base_template; // Just use base template
//}
$what_template = $globalprefObj->get('shopping_cart_base_template');
if($what_template==''){
	$what_template = $base_template;		
}
$Shopping_Cart = $what_template;


$single_template_change = 1;

// Determine the directory where we will find our template HTML and open it; parse image data and move on
$stock_dir = $_SESSION['docroot_path']."/sohoadmin/program/modules/site_templates/pages/";

if (eregi("tCustom", $what_template)) {

   $tmp_split = split("/", $what_template);
   $num_split = count($tmp_split) - 1;
   
	// This is a custom template.
	$what_template = "/tCustom";
	$template_dir = $_SESSION['docroot_path'];
	$CustomFlag = 1;
	$filename = $tmp_split[$num_split];
	$automenu = "pgm-auto_menu.php"; // Use standard auto-menu

} else {
	$template_dir = $_SESSION['docroot_path']."/template/index.html";
	if ($single_template_change == 1) {
		$template_dir = $_SESSION['docroot_path']."/sohoadmin/program/modules/site_templates/pages/";

		// Allow for custom cart templates
		$cTemplate = $template_dir.$what_template."/cart.html";
		if ( file_exists($cTemplate) ) {
			 $promoFile = "cart";
		   $template = $what_template."/cart.html";
		} else {
			 $promoFile = "index";
		   $template = $what_template."/index.html";
		}
	}
}

# Stick entire path to template folder in one var (vs. doing $template_dir.$filename over and over)
$template_fullpath = $template_dir.$what_template;
$template_foldername = $what_template;

?>