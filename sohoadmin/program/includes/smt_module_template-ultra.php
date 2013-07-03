<?php 
############################################################################################
error_reporting('341');

if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();
require_once("product_gui.php");
############################################################################################


if($_SESSION['product_mode']=='suspended' || $_SESSION['product_mode']=='frozen' || $_SESSION['product_mode']=='orphan'){
	header("Location: ".httpvar().$_SESSION['this_ip']."/sohoadmin/program/modules/help_center/help_center.php?go=support");
	exit;
}

function admin_nav_link($path_from_program_dir){
	return httpvar().$_SESSION['docroot_url'].'/sohoadmin/'.$path_from_program_dir;
}
if(preg_match('/MSIE/i', $_SERVER['HTTP_USER_AGENT'])){
	header('Content-type: text/html; charset=UT'.'F-8');
	header("X-XSS-Protection: 0");
	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
//	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";	
//	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Frameset//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd\">\n";
} else {
	header('Content-type: text/html; charset=UT'.'F-8');
	header("X-XSS-Protection: 0");
	//echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/DTD/strict.dtd\">\n";
	//echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";	
	//echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/DTD/strict.dtd\">\n";
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Frameset//EN\" \"http://www.w3.org/TR/html4/frameset.dtd\">\n";
}


//echo "<html xmlns=\"http://www.w3.org/1999/xhtml\"   dir=\"ltr\">\n";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" dir=\"ltr\" style=\"\">\n";
echo "<head>\n";
if(preg_match('/MSIE/i',$_SERVER['HTTP_USER_AGENT'])){
	echo "	<meta content=\"IE=8\" http-equiv=\"X-UA-Compatible\" />\n";	
}
echo "<title>#META_TITLE#</title>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UT".'F-8'."\">\n";
echo "<meta http-equiv=\"Pragma\" content=\"no-cache\">\n";
echo "<meta http-equiv=\"Expires\" content=\"-1\">\n";
echo "<meta name=\"SKYPE_TOOLBAR\" content=\"SKYPE_TOOLBAR_PARSER_COMPATIBLE\"/>\n";

echo "<script type=\"text/javascript\" src=\"".httpvar().$_SESSION['docroot_url']."/sohoadmin/client_files/jquery.min.js\"></script>
<script type=\"text/javascript\" src=\"".httpvar().$_SESSION['docroot_url']."/sohoadmin/client_files/jquery-ui.min.js\"></script>
<script type=\"text/javascript\" src=\"".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/includes/display_elements/js_functions.php\"></script>\n";
?>
<script type="text/javascript"> 
// Hide top frame if coming from page editor
if ( parent.document.getElementById('master_frameset') ) {
	//parent.document.getElementById('master_frameset').rows = '1,*,1,19';
	parent.document.getElementById('master_frameset').rows = '0,*,1';
}

<?php
echo "function reloadDDmenu(){\n";
echo "	parent.frames.ultramenu.$('#ddpagediv').load('".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/sitepages-dd.inc.php');\n";
echo "}\n\n";
?>

jQuery(document).ready(function(){
//	$('.rollup-button').click(function() {
//		if ( $(this).parent().hasClass('collapsed') ) { show_or_hide = 'show'; } else { show_or_hide = 'hide'; }
////		$(this).next().stop(true, true).toggle('blind');
//		var section_id = this.id.replace('-rollup', '');
////		alert(section_id+show_or_hide);
//		$(this).parent().next().stop(true, true).toggle('blind', 'fast');
//		$(this).parent().toggleClass('collapsed');
//		$('#jqresult').load('http:// echo $_SESSION['docroot_url']; /sohoadmin/program/includes/preference-saver.ajax.php?section_id='+section_id+'&show_or_hide='+show_or_hide);
//		return false;
//	});
//
//	$('.nav-heading-link').click(function() {
//		show_or_hide = 'show';
//		var section_id = $(this).next('span').attr("id").replace('-rollup', '');
//		if ( $(this).parent().hasClass('collapsed') ) {
////			$(this).parent().next().stop(true, true).show('blind');
//		}
//		$('#jqresult').load('http:// echo $_SESSION['docroot_url']; /sohoadmin/program/includes/preference-saver.ajax.php?section_id='+section_id+'&show_or_hide='+show_or_hide, function() {
//			return true;
//		});
//	});

<?php
	echo "if ( parent.frames.ultramenu.$('li > a').hasClass('selected') ) {\n";
	echo '	parent.frames.ultramenu.$(\'li > a\').removeClass(\'selected\');'."\n";
	echo "}\n";	
	echo "if ( parent.frames.ultramenu.$('div > a').hasClass('selected') ) {\n";
	echo '	parent.frames.ultramenu.$(\'div > a\').removeClass(\'selected\');'."\n";
	echo "}\n";
	echo "if ( parent.frames.ultramenu.$('ul > li').hasClass('selectedbox') ) {\n";
	echo '	parent.frames.ultramenu.$(\'ul > li\').removeClass(\'selectedbox\');'."\n";
	echo "}\n";

	if($_GET['menu']!=''){
		echo '	parent.frames.ultramenu.$(\'li > a[href$="'.$_SERVER["SCRIPT_NAME"].'?menu='.$_GET['menu'].'"]\').parent().parent().parent().addClass(\'selectedbox\');'."\n";
		echo '	parent.frames.ultramenu.$(\'div > a[href$="'.$_SERVER["SCRIPT_NAME"].'?menu='.$_GET['menu'].'"]\').parent().parent().addClass(\'selectedbox\');'."\n";
	} else {
		echo '	parent.frames.ultramenu.$(\'li > a[href$="'.$_SERVER["SCRIPT_NAME"].'"]\').parent().parent().parent().addClass(\'selectedbox\');'."\n";
		echo '	parent.frames.ultramenu.$(\'div > a[href$="'.$_SERVER["SCRIPT_NAME"].'"]\').parent().parent().addClass(\'selectedbox\');'."\n";		
	}

	
	if(basename(($_SERVER['SCRIPT_NAME']) != 'statistics.php' && $_GET['menu']=='') && basename($_SERVER['SCRIPT_NAME']) != 'shopping_cart.php'){
		echo '	parent.frames.ultramenu.$(\'li > a[href$="'.$_SERVER["SCRIPT_NAME"].'"]\').addClass(\'selected\');'."\n";
	} elseif(basename($_SERVER['SCRIPT_NAME']) == 'shopping_cart.php'){
		echo '	parent.frames.ultramenu.$(\'div > a[href$="'.$_SERVER["SCRIPT_NAME"].'"]\').addClass(\'selected\');'."\n";
	} else {
		if($_GET['menu']!=''){
			echo '	parent.frames.ultramenu.$(\'li > a[href$="'.$_SERVER["SCRIPT_NAME"].'?menu='.$_GET['menu'].'"]\').addClass(\'selected\');'."\n";	
		} else {
			echo '	parent.frames.ultramenu.$(\'li > a[href$="'.$_SERVER["SCRIPT_NAME"].'?'.$_SERVER['QUERY_STRING'].'"]\').addClass(\'selected\');'."\n";	
		}
	}
?>
reloadDDmenu();
	
});

var newWin = null;
function openHelp(openLink){
	if (!newWin || newWin.closed){
		newWin=window.open(openLink,'HelpCenter','');
	} else {
		newWin.close();
		newWin=window.open(openLink,'HelpCenter','');
	}
}
</script>

<?php
$dev_revamped_modules_array = array('open_page');
$dev_current_module_file = str_replace('.php', '', basename($_SERVER['PHP_SELF']));

echo "<link rel=\"stylesheet\" href=\"".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/product_gui.css?load=".time()."\">\n";

$global_admin_prefs = new userdata('admin');
if(!is_array($_SESSION['nav_heading_array'])){
	$nav_heading_array = $global_admin_prefs->get('nav_heading_array');
	$_SESSION['nav_heading_array'] = $nav_heading_array;
} else {
	$nav_heading_array = $_SESSION['nav_heading_array'];
}

echo "<link rel=\"stylesheet\" href=\"".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/includes/product_interface-ultra.css?load=".time()."\">\n";
echo "<link rel=\"stylesheet\" href=\"".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/includes/product_buttons-ultra.css?load=".time()."\">\n";
echo "</head>\n";

############################
## HELP BUTTON MANUAL Links
## NEEDS BETTER LINKS
$help_link_ar = array();
$help_link_ar['view_orders.php']='Shopping_Cart.php';
$help_link_ar['display_settings.php']='Shopping_Cart.php';
$help_link_ar['backup_restore.php']='Global_Settings.php';
## Site Pages
$help_link_ar['open_page.php']='Opening_and_Editing_Pages.php';
$help_link_ar['create_pages.php']='Opening_and_Editing_Pages.php';
$help_link_ar['auto_menu_system.php']='Menu_Navigation_System.php';
## Site Files
$help_link_ar['site_files.php']='Uploading_and_Managing_Files.php';
$help_link_ar['upload_files.php']='Uploading_and_Managing_Files.php';
$help_link_ar['resize_image.php']='Image_Editor.php';
## CART
$help_link_ar['shopping_cart.php']='Shopping_Cart.php';
$help_link_ar['search_products.php']='Adding_New_Products.php';
$help_link_ar['products.php']='Adding_New_Products.php';
$help_link_ar['coupon_codes.php']='Coupon_Codes.php';
$help_link_ar['shipping_options.php']='Shipping_Options.php';
$help_link_ar['tax_rates.php']='Tax_Rate_Options.php';
$help_link_ar['payment_options.php']='Payment_Options.php';
$help_link_ar['business_information.php']='Business_Information.php';
$help_link_ar['categories.php']='Category_Setup.php';
$help_link_ar['privacy_policy.php']='Policies.php';
$help_link_ar['shipping_policy.php']='Policies.php';
$help_link_ar['returns_policy.php']='Policies.php';
$help_link_ar['other_policies.php']='Policies.php';
## Calendar
$help_link_ar['event_calendar.php']='Calendar.php';
$help_link_ar['category_setup.php']='Calendar_Categories.php';
$help_link_ar['cal_display_settings.php']='Calendar_Display_Settings.php';
$help_link_ar['add_event.php']='Adding_Events.php';
$help_link_ar['edit_event.php']='Editing_Events.php';
## Newsletter
//$help_link_ar['enewsletter.php']='';
## Photo Albums
$help_link_ar['photo_album.php']='Photo_Albums.php';
$help_link_ar['edit_album.php']='Edit_a_Photo_Album.php';
//## Web Forms
//$help_link_ar['']='';
## Site Search
$help_link_ar['what_gets_searched.php']='Site_Search.php';
$help_link_ar['search_display_settings.php']='Site_Search.php';
$help_link_ar['search_statistics.php']='Site_Search.php';
## Database Table Manager
$help_link_ar['download_data.php']='Site_Data_Tables.php';
$help_link_ar['create_table.php']='Site_Data_Tables.php';
$help_link_ar['create_and_import_db.php']='Site_Data_Tables.php';
$help_link_ar['delete_table.php']='Site_Data_Tables.php';
$help_link_ar['wizard_start.php']='Database_Search.php';
$help_link_ar['auth_users.php']='Site_Data_Tables.php';
$help_link_ar['enter_edit_data.php']='Managing_Table_Data.php';
$help_link_ar['modify_table.php']='Site_Data_Tables.php';
## Traffic Stats
$help_link_ar['statistics.php']='Unique_Visitors.php';
## Global Settings
$help_link_ar['global_settings.php']='Global_Settings.php';
$help_link_ar['webmaster.php']='Administrative_Users.php';
$help_link_ar['business_info.php']='Default_Contact_Information.php';
$help_link_ar['meta_data.php']='Search_Engine_Optimization.php';
$help_link_ar['software_updates.php']='Version_Updates.php';
## Template Manager
$help_link_ar['site_templates.php']='Site_Template_Manager.php';
$help_link_ar['template_images.php']='Template_Manager_Settings.php';
$help_link_ar['template_images-edit.php']='Template_Manager_Settings.php';
if($help_link_ar[basename($_SERVER['SCRIPT_NAME'])]!=''){
	$man_help_link = 'help_center.php?man='.$help_link_ar[basename($_SERVER['SCRIPT_NAME'])];
} else {
	$man_help_link = 'help_center.php';
}
$framez=1;

echo "<body style=\"width:100%;height:99%;background:url('".admin_nav_link('program/includes/images/vert-bg.png')."') repeat-y; background-attachment:scroll;background-repeat:repeat-y;background-position:-218px 0px;overflow:visible;\"  >\n";
$qrystring = '';
  
if($_SERVER['QUERY_STRING']!=''){
	$qrystring = '?'.$_SERVER['QUERY_STRING'];
}
//preg_replace('/sohoadmin/
echo "<form name=\"addframes\" action=\"".httpvar().$_SESSION['this_ip']."/sohoadmin/version.php\" method=\"POST\" style=\"display:none;\">

<input type=\"hidden\" name=\"gotopage\" value=\"".preg_replace('/^(.sohoadmin){1}\//','',$_SERVER['SCRIPT_NAME']).$qrystring."\">
</form>
<script type=\"text/javascript\"> 
if(top.location == location){
	document.addframes.submit();
}
</script>\n";
	
//} else {
//	echo "<body style=\"width:100%;height:100%;\" onload=\"if(document.getElementById('topdiv').offsetHeight==50){document.getElementById('topdiv').style.height='48px'; } \">\n";
//}

echo "<div class=\"container\" style=\"position:relative;background-attachment:scroll;background-repeat:repeat-y;background-position:-218px 0px;overflow:visible;height:99%;width:100%;margin-bottom:0px;min-width:800px!important;\">\n";
echo "			<div class=\"top-left\" style=\"top:46px;left:-238px;\"></div>\n";
echo "<div id=\"tleft2\">&nbsp;</div>\n";

echo "	<div class=\"top\" id=\"topdiv\" style=\"overflow:visible;background-attachment:scroll;width:100%;\">\n";

echo "		<h3 class=\"breadcrumb\">&gt; <a href=\"".admin_nav_link('program/modules/dashboard.php')."\">".lang('Dashboard')."</a> #BREADCRUMB_LINKS#</h3>\n";
//echo "		<h3 class=\"breadcrumb\"><input type=text value=\"".basename($_SERVER['SCRIPT_NAME'])."\">  &gt; <a href=\"".admin_nav_link('program/modules/dashboard.php')."\">Main Menu</a> #BREADCRUMB_LINKS#</h3>\n";

echo "		<div class=\"account\">			\n			";

if($_SESSION['help_link']!=''){
	//echo "<span onclick=\"openHelp('".$_SESSION['help_link']."');\" style=\"cursor:pointer;position:relative;margin:20px 5px 0px 5px;height:22px;float:right;\"><div style=\"position:absolute;left:0;top:2px;margin:0;height:20px;width:19px;background-image:url('".admin_nav_link('program/includes/images/help4.png')."');background-repeat:no-repeat;\">&nbsp;</div><a href=\"javascript:void(0);\" class=\"grayButton\" onclick=\"openHelp('".$_SESSION['help_link']."');\"><span>&nbsp;&nbsp;<strong>Get Support</strong></span></a></span>";
	echo "<span style=\"cursor:pointer;position:relative;margin:20px 5px 0px 5px;height:22px;float:right;\"><div style=\"position:absolute;left:0;top:2px;margin:0;height:20px;width:19px;background-image:url('".admin_nav_link('program/includes/images/help4.png')."');background-repeat:no-repeat;\">&nbsp;</div><a href=\"".$_SESSION['help_link']."\" target=\"_BLANK\" class=\"grayButton\"><span>&nbsp;&nbsp;<strong>Get Support</strong></span></a></span>";
} else {
	//echo "<span onclick=\"openHelp('http://".$_SESSION['docroot_url']."/sohoadmin/program/modules/help_center/".$man_help_link."');\" style=\"cursor:pointer;position:relative;margin:20px 5px 0px 5px;height:22px;float:right;\"><div style=\"position:absolute;left:0;top:2px;margin:0;height:20px;width:19px;background-image:url('".admin_nav_link('program/includes/images/help4.png')."');background-repeat:no-repeat;\">&nbsp;</div><a href=\"javascript:void(0);\" class=\"grayButton\" onclick=\"openHelp('http://".$_SESSION['docroot_url']."/sohoadmin/program/modules/help_center/".$man_help_link."');\"><span>&nbsp;&nbsp;<strong>Get Support</strong></span></a></span>";	
	echo "<span style=\"cursor:pointer;position:relative;margin:20px 5px 0px 5px;height:22px;float:right;\"><div style=\"position:absolute;left:0;top:2px;margin:0;height:20px;width:19px;background-image:url('".admin_nav_link('program/includes/images/help4.png')."');background-repeat:no-repeat;\">&nbsp;</div><a target=\"_BLANK\" href=\"".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/modules/help_center/".$man_help_link."\" class=\"grayButton\"><span>&nbsp;&nbsp;<strong>Get Support</strong></span></a></span>";	
}



echo "<a style=\"height:13px;width:44px;\" href=\"".admin_nav_link('program/modules/dashboard.php').'?logout=logout'."\" class=\"logout\" target=\"_parent\">Log Out</a>\n";
echo "		<span style=\"float:right;\"><a style=\"margin-top:19px;\" class=\"grayButton\" href=\"".httpvar().$_SESSION['this_ip']."\" target=\"_BLANK\" title=\"View Website\"><span><strong>View Website</strong></span></a></span>\n";
echo "		<div class=\"aname\"><h3 style=\"color:#818181;white-space:nowrap;\">Hello, <span>".strtolower($_SESSION['PHP_AUTH_USER'])."</span></h3></div>\n";
echo "		</div>\n";
//echo "<!--\n";
//echo "		<div class=\"search\">\n";
//echo "			<input type=\"text\" class=\"srchtxt\" value=\"Search\" /><input type=\"submit\" class=\"submitbtn\" />\n";
//echo "		</div>\n";
//echo "-->\n";
echo "		<div class=\"clear\"></div>\n";
echo "	</div>\n";

echo "<div style=\"position:relative;width:100%;height:98%;border:0px solid green;margin-bottom:2px;\">\n";

//if($framez==1){
echo "	 <div class=\"main-container\" id=\"main-container\">\n";
	//echo "		<div class=\"left-panel\" style=\"height:98%;display:none;\">\n";
//} else {
//	echo "	<div style=\"position:absolute; top:33px; left:10px; white-space:none;z-index:99999;width:172px; text-align:center;\"><a href=\"javascript:void(0);\" onclick=\"nav_collapse_all();\">Collapse All</a> | <a href=\"javascript:void(0);\" onclick=\"nav_expand_all();\">Expand All</a></div>\n";
// 	//echo "<div class=\"main-container\" id=\"main-container\" style=\"height:100%;\">\n";
//	echo "	<div class=\"main-container\" id=\"main-container\">\n";
//	echo "		<div class=\"left-panel\" style=\"height:98%;\">\n";	
//}

echo "			<div id=\"jqresult\" style=\"position:absolute;z-index:-20; display: none;\">ajax results go here</div>\n";

echo "		<div class=\"right-panel\" id=\"right-panel\" style=\"margin-left:2px;\">	\n";
//} else {
//	echo "		<div class=\"right-panel\" id=\"right-panel\">	\n";	
//}
echo "				<!---Report messages-->\n";
echo "			   <div id=\"report_messages\" style=\"display: #REPORT_DISPLAY#;cursor: pointer; float: right;\" onclick=\"hideid('report_messages');\">\n";
echo "			   	#REPORT_MESSAGES#\n";
echo "			   </div>\n";
echo "			<h3>#ICON_IMG##HEADING_TEXT#</h3>\n";
echo "			<p  style=\"width:100%;\" id=\"module_description_text\">#DESCRIPTION_TEXT#</p>\n";
echo "			<div>#MODULE_HTML#</div>\n";
echo "		</div>\n";
echo "	</div>\n";
echo "		<div class=\"clear\"></div>\n";
echo "	</div>\n";
echo "</div>\n";


//echo "<script type=\"text/javascript\">\n";
////echo "//$('#nav-shoppingcart').hide();\n";
//
//echo "</script>\n";

echo "<script type=\"text/javascript\">\n";

# show/hide menu items based on saved preferences
//foreach ( $nav_heading_array as $key=>$value ) {
//	//echo '$(\'#subnav-'.$key.'\').'.$value.'();'."\n";
//	echo 'parent.frames.ultramenu.$(\'#subnav-'.$key.'\').'.$value.'();'."\n";
//	
//	
//	
//	if ( $value == 'hide' ) {
//		//echo '$(\'#'.$key.'-nav-heading\').addClass(\'collapsed\');'."\n";
//		echo 'parent.frames.ultramenu.$(\'#'.$key.'-nav-heading\').addClass(\'collapsed\');'."\n";
//	}
//}

	echo "if(top.location != location){\n";
	echo "	parent.frames.footer.setPage('#HEADING_TEXT#');\n";
	echo "}\n";


echo "</script>\n";
echo "</body>\n";
echo "</html>\n";
?>