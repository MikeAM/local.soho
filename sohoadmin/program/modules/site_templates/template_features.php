<?php
error_reporting('341');

if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

session_start();


$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../../includes/product_gui.php');
chdir($curdir);



$filename = $_SESSION['docroot_path']."/sohoadmin/program/modules/site_templates/pages/".$_GET['template'];

//echo $filename; exit;

# Combine html from all included template html files into one big searchable container var ($THIS_HTML)
$templatefils_ar=array('index.html','home.html','cart.html','news.html','index.php','home.php','cart.php','news.php');
$THIS_HTML = '';
foreach($templatefils_ar as $tfileinc){
	if(file_exists($filename.'/'.$tfileinc)){
		if(array_pop(explode('.',basename($tfileinc))) == 'php'){
			$curdirr = getcwd();
			ob_start();
			chdir($filename);
			include($tfileinc);
			$THIS_HTML .= ob_get_contents();
			ob_end_clean();
			chdir($curdirr);
		} else {
			$THIS_HTML .= file_get_contents($filename."/".$tfileinc);
		}
	}	
}

if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) ){
	echo "<table width=\"350\" cellspacing=\"0\" cellpadding=\"6\" border=\"0\" class=\"feature_sub\">\n";
}else{
	echo "<table width=\"365\" cellspacing=\"0\" cellpadding=\"6\" border=\"0\" class=\"feature_sub\">\n";
}

?>
  <tbody>
    <tr>
      <td align="left" class="fsub_title">Template Features</td>
    </tr>
    <tr>
      <td valign="middle" align="left" class="text">
      Each template in our library has different features. Below is a list of features for the selected template.
      </td>
    </tr>
  <?php
  	$is_soho = 0;
	if(eregi("#CONTENT#", $THIS_HTML)){
		$is_soho = 1;
		echo "	<tr>\n";
		echo "  	<td valign=\"middle\" align=\"left\" class=\"text\"><b>Content Area</b> - Add content by going to <a href=\"open_page.php\">Edit Pages</a></td>\n";
		echo "	</tr>\n";
	}
	if(eregi("#VMENU#", $THIS_HTML) || eregi("#VMAINS#", $THIS_HTML) || eregi("#VSUBS#", $THIS_HTML) ||  eregi("#VFLYOUTMENU#", $THIS_HTML)){
		$is_soho = 1;
		echo "	<tr>\n";
		echo "  	<td valign=\"middle\" align=\"left\" class=\"text\"><b>Vertical menu</b> - Edit the menu layout in <a href=\"auto_menu_system.php\">Menu Navigation</a></td>\n";
		echo "	</tr>\n";
	}
	if(eregi("#HMENU#", $THIS_HTML) || eregi("#HMAINS#", $THIS_HTML) || eregi("#HSUBS#", $THIS_HTML) ||  eregi("#FLYOUTMENU#", $THIS_HTML)){
		$is_soho = 1;
		echo "	<tr>\n";
		echo "  	<td valign=\"middle\" align=\"left\" class=\"text\"><b>Horizontal menu system</b> - Edit the menu layout in <a href=\"auto_menu_system.php\">Menu Navigation</a></td>\n";
		echo "	</tr>\n";
	}
	
	if(eregi("#TMENU#", $THIS_HTML)){
		$is_soho = 1;
		echo "	<tr>\n";
		echo "  	<td valign=\"middle\" align=\"left\" class=\"text\"><b>Text link menu</b> - Turn this on/off via <a href=\"auto_menu_system.php\">Menu Navigation</a></td>\n";
		echo "	</tr>\n";
	}
	if(eregi("#USERSONLINE#", $THIS_HTML)){
		$is_soho = 1;
		echo "	<tr>\n";
		echo "  	<td valign=\"middle\" align=\"left\" class=\"text\"><b>Users Online</b> - Displays number of users currently online.</td>\n";
		echo "	</tr>\n";
	}
	if(eregi("#LOGO#", $THIS_HTML)){
		$is_soho = 1;
		echo "	<tr>\n";
		echo "  	<td valign=\"middle\" align=\"left\" class=\"text\"><b>Header logo text</b> - Edit via <a href=\"javascript:showid('tab2-content');hideid('tab1-content');hideid('tab3-content');setClass('layout_tab2', 'tab-on');setClass('layout_tab1', 'tab-off');setClass('layout_tab3', 'tab-off');\">".lang("Template Settings")."</a> tab.</td>\n";
		echo "	</tr>\n";
	}
	if(eregi("#LOGOIMG#", $THIS_HTML)){
		$is_soho = 1;
		echo "	<tr>\n";
		echo "  	<td valign=\"middle\" align=\"left\" class=\"text\"><b>Logo image</b> - Edit via <a href=\"javascript:showid('tab2-content');hideid('tab1-content');\">".lang("Template Settings")."</a> tab.</td>\n";
		echo "	</tr>\n";
	}
	if(eregi("#BIZ-PHONE#", $THIS_HTML)){
		$is_soho = 1;
		echo "	<tr>\n";
		echo "  	<td valign=\"middle\" align=\"left\" class=\"text\"><b>Phone Number</b> - Edit via <a href=\"javascript:showid('tab2-content');hideid('tab1-content');\">".lang("Template Settings")."</a> tab.</td>\n";
		echo "	</tr>\n";
	}
	if(eregi("#SLOGAN#", $THIS_HTML)){
		$is_soho = 1;
		echo "	<tr>\n";
		echo "  	<td valign=\"middle\" align=\"left\" class=\"text\"><b>Slogan Text</b> - Edit slogan on the <a href=\"javascript:showid('tab2-content');hideid('tab1-content');\">".lang("Template Settings")."</a> tab.</td>\n";
		echo "	</tr>\n";
	}
	if(preg_match("/#BOX[0-9]+#/i", $THIS_HTML)){
		$is_soho = 1;
		echo "   <tr>\n";
		//echo "   <td valign=\"middle\" align=\"left\" class=\"text\"><b>".lang("Template Sidebar")."<font color=\"#f7941d\" size=\"1\"><sup><i>".lang("NEW!")."</i></sup></font></b></td>\n";
		echo "   <td valign=\"middle\" align=\"left\" class=\"text\"><b>".lang("Template Sidebar")."<font color=\"#f7941d\" size=\"1\"><sup><i>".lang("NEW!")."</i></sup></font></b> - Edit the Sidebar in the Page Editor</td>\n";
		echo "   </tr>\n";
	}
################################################
##TEMPORARILY DISABLE BLOG FEATURES#############
//	if(eregi("#BOX[0-9]#", $THIS_HTML)){
//		$is_soho = 1;
//		echo "	<tr>\n";
//		echo "  	<td valign=\"middle\" align=\"left\" class=\"text\"><b>Template Boxes<font color=\"#f7941d\" size=\"1\"><sup><i>NEW!</i></sup></font></b> - \n";
//
//   	if ( $_GET['cur_tmp'] != $_GET['template'] && $_GET['template'] != "") {
//  		   echo "		Select this template to Edit!</td>\n";
//   	}else{
//   	   echo "<a href=\"promo_boxes/promo_boxes.php\">Edit Template Boxes Now!</a></td>\n";
//   	}
//		echo "	</tr>\n";
//	}
//	if(eregi("#PROMOTXT", $THIS_HTML)){
//		$is_soho = 1;
//		echo "	<tr>\n";
//		echo "  	<td valign=\"middle\" align=\"left\" class=\"text\"><b>Promotional Boxes</b> - <a href=\"blog.php\">Edit Promotional Boxes Now!</a></td>\n";
//		echo "	</tr>\n";
//	}
//	if(eregi("#NEWSBOX", $THIS_HTML)){
//		$is_soho = 1;
//		echo "	<tr>\n";
//		echo "  	<td valign=\"middle\" align=\"left\" class=\"text\"><b>News Boxes</b> - <a href=\"blog.php\">Edit News Boxes Now!</a></td>\n";
//		echo "	</tr>\n";
//	}
	if(eregi("#BIZ-", $THIS_HTML)){
		$is_soho = 1;
		echo "	<tr>\n";
		echo "  	<td valign=\"middle\" align=\"left\" class=\"text\"><b>Business Info</b> - Edit Business Info on the <a href=\"javascript:showid('tab2-content');hideid('tab1-content');\">".lang("Template Settings")."</a> tab.</td>\n";
		echo "	</tr>\n";
	}
	if(eregi("#CUSTOMPHP", $THIS_HTML) || eregi("#CUSTOMINC", $THIS_HTML) || eregi("#INC-", $THIS_HTML)){
		$is_soho = 1;
		echo "	<tr>\n";
		echo "  	<td valign=\"middle\" align=\"left\" class=\"text\"><b>Custom Includes</b> - Advanced includes add additional functionality.</td>\n";
		echo "	</tr>\n";
	}

	# _userimg-
	if(eregi("_userimg-", $THIS_HTML) ){
		$is_soho = 1;
		echo "	<tr>\n";
		echo "  	 <td valign=\"middle\" align=\"left\" class=\"text\">\n";
		echo "  	  <b><a href=\"site_templates/template_images.php?templatefolder=".$_GET['template']."\">Templates Images</a><font color=\"#f7941d\" size=\"1\"><sup><i>NEW!</i></sup></font></b> - \n";
		echo "  	  Swap-out certain images within the template for others of your choosing.\n";
		echo "  	 </td>\n";
		echo "	</tr>\n";
	}

	# Error if no pound vars found
	if ( $is_soho != 1 ) {
		echo "	<tr>\n";
		echo "  	<td align=\"left\" class=\"text\" >\n";

	   if ( $_GET['template'] == "" ) {
	      # ERROR: No template selected
   		echo "		<div class=\"bg_red_df\" style=\"padding: 4px; border: 1px solid #980000;\"><b>No template selected.</b>Please select a template from the drop-down list in the 'Choose Site Template' box to the left.</div><br/><br/>\n";
   		echo "		If you would like to learn more about ".$_SESSION['hostco']['sitebuilder_name']." templates go to the \n";
   		echo "		<span class=\"orange uline hand\" onclick=\"showHelp()\">Help Center</span> and view our documentation on Site Templates.\n";

   	} else {
   	   # ERROR: Not valid Pro Edition format
   		echo "		<b style=\"color: red;\">This template does not seem to be in ".$_SESSION['hostco']['sitebuilder_name']." format.</b><br/>\n";
   		echo "		Please select a different template, or, if you would like to learn more about ".$_SESSION['hostco']['sitebuilder_name']."\n";
   		echo "		templates go to the <span class=\"orange uline hand\" onclick=\"showHelp()\">Help Center</span> and view our documentation on Site Templates.\n";
   	}
		echo "	</td>\n";
		echo "	</tr>\n";
	}

   # testing
//   echo "<tr><td style=\"border: 1px solid red;\">[".$_GET['cur_tmp']."] != [".$_GET['template']."]</td></tr>";

	if ( $_GET['cur_tmp'] != $_GET['template'] ) {
		echo "	<tr>\n";
		echo "  	<td valign=\"middle\" align=\"center\" class=\"text\">\n";
		echo "		<button type=\"button\" class=\"grayButton\" onclick=\"select_template('".$_GET['cur_tmp']."');\"><span><span>".lang('Cancel template change')."</span></span></button>\n";

		# Do not show save button if it's a "No template selected" problem
		if ( $_GET['template'] != "" ) {
		   echo "		<button type=\"button\" class=\"greenButton\" onClick=\"saving_updates();\"><span><span>".lang('Use Template')."</span></span></button>\n";
		}
		echo "	</td>\n";
		echo "	</tr>\n";
	}


  	echo "</tbody>\n";
	echo "</table>\n";





?>