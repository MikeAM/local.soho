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

function admin_nav_link($path_from_program_dir) {
	return httpvar().$_SESSION['docroot_url'].'/sohoadmin/'.$path_from_program_dir;
}
if(preg_match('/MSIE/i', $_SERVER['HTTP_USER_AGENT'])){
	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
//	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";	
//	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Frameset//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd\">\n";
} else {
	//echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/DTD/strict.dtd\">\n";
	//echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";	
	//echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/DTD/strict.dtd\">\n";
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Frameset//EN\" \"http://www.w3.org/TR/html4/frameset.dtd\">\n";
}
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" dir=\"ltr\" style=\"width:100%;height:100%;background:none;background-color:#D6D6D6;\">\n";

echo "<head>\n";
$globalprefObj = new userdata('global');
if($globalprefObj->get('goog_trans')!='off'){
	//echo display_google_translate();
}

echo "<title>#META_TITLE#</title>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UT".'F-8'."\">\n";
echo "<meta http-equiv=\"Pragma\" content=\"no-cache\">\n";
echo "<meta http-equiv=\"Expires\" content=\"-1\">\n";

if(preg_match('/(?i)msie [0-9]{1,2}/i',$_SERVER['HTTP_USER_AGENT'])){
	echo "<meta content=\"IE=8\" http-equiv=\"X-UA-Compatible\" />\n";
}

echo "<script type=\"text/javascript\" src=\"".httpvar().$_SESSION['docroot_url']."/sohoadmin/client_files/jquery.min.js\"></script>
<script type=\"text/javascript\" src=\"".httpvar().$_SESSION['docroot_url']."/sohoadmin/client_files/jquery-ui.min.js\"></script>
<script type=\"text/javascript\" src=\"".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/includes/display_elements/js_functions.php\"></script>\n";
?>
<script type="text/javascript"> 
// Hide top frame if coming from page editor
//if ( parent.document.getElementById('master_frameset') ) {
//	parent.document.getElementById('master_frameset').rows = '1,*,1,19';
//}

jQuery(document).ready(function(){
	$('.rollup-button').click(function() {
		if ( $(this).parent().hasClass('collapsed') ) { var show_or_hide = 'show';  } else { var show_or_hide = 'hide'; }
//		$(this).next().stop(true, true).toggle('blind');
		var section_id = this.id.replace('-rollup', '');
		//alert(section_id+show_or_hide);
		$(this).parent().next().stop(true, true).toggle('blind', 'fast');
		$(this).parent().toggleClass('collapsed');
		$('#jqresult').load('<? echo httpvar().$_SESSION['docroot_url']; ?>/sohoadmin/program/includes/preference-saver.ajax.php?section_id='+section_id+'&show_or_hide='+show_or_hide);
		return false;
	});

	$('.nav-heading-link').click(function() {
		var show_or_hide = 'show';
		
//		if ( $(this).parent().hasClass('collapsed') ) {
//			alert($(this).attr("id")+' '+$(this).parent().className+' '+$(this).parent().attr("id")+' collapsed');
//		}
		
		var section_id = $(this).next('span').attr("id").replace('-rollup', '');
		if ( $(this).parent().hasClass('collapsed') ) {
			$(this).parent().toggleClass('collapsed');
			$(this).parent().next().stop(true, true).show('blind');
		}
		//alert(section_id+show_or_hide);
		$('#jqresult').load('<? echo httpvar().$_SESSION['docroot_url']; ?>/sohoadmin/program/includes/preference-saver.ajax.php?section_id='+section_id+'&show_or_hide='+show_or_hide, function() {
			return true;
		});
	});
	
		
		function ConfirmLink(linktogo){	
			var nn = parent.frames['body'].document.location+"";
			if(nn.search("sohoadmin/program/modules/page_editor/page_editor.php") > 0){
				if(top.header.loadedPage==top.header.getPageEditorContents()){
					return true;
				} else {
					top.header.savePage(linktogo);
					return false;
				}
			} else {
				if( nn.search("sohoadmin/program/modules/blog/blog.php") > 0){
					
					if(window.confirm('<? echo lang("Do you wish to save the changes you have made"); ?>?\n\n<? echo lang("Click \"OK\" to Save changes now OR"); ?>\n<? echo lang("Click \"Cancel\" to discard changes"); ?>.')){
						top.body.postBlog();
						return false;
					} else {
						return true;
					}
				} else {
					//return confirm("Are you sure?");
					return true;
				}
			}
		}
		
		var allLinks = document.getElementsByTagName('a');
		for (var i=0; i < allLinks.length; i++) {
			//alert(allLinks[i].href);
			if(allLinks[i].href+''!='javascript:void(0);'){
				allLinks[i].onclick = function () {	
					return ConfirmLink(this.href);
				}
			}
		}
		document.getElementById('dashboarddivv').onclick = function () {	
<?php 
echo "			if(ConfirmLink('".admin_nav_link("program/modules/dashboard.php")."')){\n";
echo "				parent.frames['body'].document.location='".admin_nav_link('program/modules/dashboard.php')."';\n";
echo "			}\n";
?>
		}

});

$(document).bind("dragstart", function(e) {
     //if (e.target.nodeName.toUpperCase() == "IMG") {
         return false;
    // }
});

function nav_collapse_all() {
	$('.subnav').hide('blind', 'fast');
	$('.nav-heading').addClass('collapsed');
	$('.subnav').each(function() {
		section_id = this.id.replace('subnav-', '');
		$('#jqresult').load('<? echo httpvar().$_SESSION['docroot_url']; ?>/sohoadmin/program/includes/preference-saver.ajax.php?section_id='+section_id+'&show_or_hide=hide');
	});
}

function nav_expand_all() {
	$('.subnav').show('blind', 'fast');
	$('.nav-heading').removeClass('collapsed');
	$('.subnav').each(function() {
		section_id = this.id.replace('subnav-', '');
		$('#jqresult').load('<? echo httpvar().$_SESSION['docroot_url']; ?>/sohoadmin/program/includes/preference-saver.ajax.php?section_id='+section_id+'&show_or_hide=show');
	});
}

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

echo "<link rel=\"stylesheet\" href=\"".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/product_gui.css?rel=".time()."\">\n";

$global_admin_prefs = new userdata('admin');
if(!is_array($_SESSION['nav_heading_array'])){
	$nav_heading_array = $global_admin_prefs->get('nav_heading_array');
	$_SESSION['nav_heading_array'] = $nav_heading_array;
} else {
	$nav_heading_array = $_SESSION['nav_heading_array'];
}

echo "<link rel=\"stylesheet\" href=\"".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/includes/product_interface-ultra.css?rel=".time()."\">\n";
echo "<link rel=\"stylesheet\" href=\"".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/includes/product_interface-ultra-left_menu.css?rel=".time()."\">\n";

	echo "<style>\n";
if($_SESSION['language']=='spanish.php'){
echo "li div.nav-heading a { font-size:12px; white-space:nowrap; }\n";
echo "div a:link { white-space:nowrap; }\n";
echo ".left-panel li li a {  white-space:nowrap;font-size:11px; }\n";
}
echo "</style>\n";
//echo "<link rel=\"stylesheet\" href=\"".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/includes/product_buttons-ultra.css\">\n";
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


echo "<body style=\"height:99%;background-color:#D6D6D6;overflow:visible;\" >\n";
echo "<div id=\"tleft\"></div>\n";

echo "<div>\n";


 

//echo "	<div class=\"main-container\" id=\"main-container\">\n";
echo "	<div>\n";
echo "		<div id=\"leftmenupanel\" class=\"left-panel\" style=\"background-image:none!important;height:100%;width:95%!important;overflow-x:auto!important;\">\n";
//echo "<div style=\"overflow:display;left:0;top:0;z-index:0;height:35px;width:100%;position:absolute;background-position:14px 10px;background-image: url('".admin_nav_link('program/includes/images/sohoadmin2.png')."')!important;background-repeat:no-repeat;\"></div>\n";
echo "	<div style=\"position:absolute; top:36px; left:10px; white-space:none;z-index:99999;width:178px; text-align:center;\"><a href=\"javascript:void(0);\" onclick=\"nav_collapse_all();\">".lang('Collapse All')."</a> | <a href=\"javascript:void(0);\" onclick=\"nav_expand_all();\">".lang('Expand All')."</a></div>\n";

echo "			<div id=\"jqresult\" style=\"display: none;\">ajax results go here</div>\n";

echo "				<ul class=\"nav\">\n";
echo "					<li style=\"width:173px;text-align:center;z-index:0;background-position:18px 5px;background-image: url('".admin_nav_link('program/includes/images/sohoadmin2.png')."')!important;background-repeat:no-repeat;\">\n";
echo "						<div id=\"dashboarddivv\" class=\"dashboard \" style=\"text-align:center;display:inline-block;margin-bottom:15px; font:Arial,Helvetica,sans-serif;cursor:pointer;background-position:fixed!important;\"  >\n";
//onClick=\"parent.frames['body'].document.location='".admin_nav_link('program/modules/dashboard.php')."';\"

//echo "		<div class=\"name\">\n";
//echo "		<h2><a href=\"".admin_nav_link('program/modules/dashboard.php')."\"></h2>";
//echo "		</div>\n";


echo "".lang('Dashboard')."</div>\n";
echo "					</li>\n";

echo "<li class=\"last\" style=\"border-radius:10px 10px 0px 0;\" ></li>\n";
//echo "	<span class=\"lastlinks\"><a  href=\"javascript:void(0);\" onclick=\"nav_collapse_all();\">Collapse All</a> | <a href=\"javascript:void(0);\" onclick=\"nav_expand_all();\" style=\"width:auto!important;\">Expand All</a></span>\n";
//echo "</div>\n";

if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;MOD_EDIT_PAGES;/i", $_SESSION['CUR_USER_ACCESS'])){
	echo "					<li class=\"nav-section\">\n";
	echo "						<div class=\"nav-heading\" id=\"pages-nav-heading\">\n";
	echo "							<a class=\"nav-heading-link\" target=\"body\" href=\"".admin_nav_link('program/modules/open_page.php')."\">".lang('Pages')."</a>\n";
	echo "							<span id=\"pages-rollup\" class=\"rollup-button\"><a href=\"javascript:void(0);\" style=\"border:0;margin:0;padding:0;\">&gt;</a></span>\n";
	echo "						</div>\n";
	echo "						<ul id=\"subnav-pages\" class=\"subnav\">\n";
	if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;MOD_EDIT_PAGES;/i", $_SESSION['CUR_USER_ACCESS'])){
		echo "							<li style=\"position:relative;min-height:19px!important;\"><a style=\"min-height:19px!important;\" target=\"body\" href=\"".admin_nav_link('program/modules/open_page.php')."\">".lang('Edit')."</a>\n";
		echo "<div id=\"ddpagediv\" style=\"display:inline;border:0px;margin:0px;padding:0px;\">\n";
		include_once($_SESSION['doc_root']."/sohoadmin/program/sitepages-dd.inc.php");
		echo "</div>\n";
		echo "</li>\n";
	}
	if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;MOD_CREATE_PAGES;/i", $_SESSION['CUR_USER_ACCESS'])){
		echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/create_pages.php')."\">".lang('Create')."</a></li>\n";
	}
	if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;MOD_MENUSYS;/i", $_SESSION['CUR_USER_ACCESS'])){
		echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/auto_menu_system.php')."\">".lang('Menu')."</a></li>\n";
	}
	if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;MOD_EDIT_PAGES;/i", $_SESSION['CUR_USER_ACCESS'])){
		echo "							<li target=\"body\" style=\"padding:4px 5px 4px 5px; background:#FFFFFF; border-left:0px solid #CCCCCC;border-right:0px solid #CCCCCC;color:#666565;background:#FFFFFF;font:12px Arial, Helvetica, sans-serif;\">\n";
		include_once($_SESSION['doc_root']."/sohoadmin/program/sitepages-dd.inc.php");
		echo "							</li>\n";
	}
	
	echo "						</ul>\n";
	echo "					</li>\n";
}

//if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;(MOD_TEMPLATES|MOD_MENUSYS|MOD_SHOPPING_CART|MOD_CALENDAR);/i", $_SESSION['CUR_USER_ACCESS'])){
//	echo "					<li class=\"nav-section\">\n";
//	echo "						<div class=\"nav-heading\" id=\"lookandfeel-nav-heading\">\n";
//	if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;MOD_TEMPLATES;/i", $_SESSION['CUR_USER_ACCESS'])){
//		echo "							<a class=\"nav-heading-link\" href=\"".admin_nav_link('program/modules/site_templates.php')."\">Look &amp; Feel</a>\n";
//	} else {
//		echo "							<a class=\"nav-heading-link\" href=\"#\">Look &amp; Feel</a>\n";	
//	}
//	echo "							<span id=\"lookandfeel-rollup\" class=\"rollup-button\"><a href=\"javascript:void(0);\" style=\"margin:0;padding:0;\">&gt;</a></span>\n";
//	echo "						</div>\n";
//	echo "						<ul id=\"subnav-lookandfeel\" class=\"subnav\">\n";
//	if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;MOD_TEMPLATES;/i", $_SESSION['CUR_USER_ACCESS'])){
//		echo "							<li><a href=\"".admin_nav_link('program/modules/site_templates.php')."\">Themes</a></li>\n";
//	}
//	if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;MOD_MENUSYS;/i", $_SESSION['CUR_USER_ACCESS'])){
//		echo "							<li><a href=\"".admin_nav_link('program/modules/auto_menu_system.php')."\">Menu</a></li>\n";
//	}
//	if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;MOD_SHOPPING_CART;/i", $_SESSION['CUR_USER_ACCESS'])){
//		echo "							<li><a href=\"".admin_nav_link('program/modules/mods_full/shopping_cart/display_settings.php')."\">Shopping</a></li>\n";
//	}
//	if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;MOD_CALENDAR;/i", $_SESSION['CUR_USER_ACCESS'])){
//		echo "							<li><a href=\"".admin_nav_link('program/modules/mods_full/event_calendar/display_settings.php')."\">Calendar</a></li>\n";
//	}
//	echo "						</ul>\n";
//	echo "					</li>\n";
//}

if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;MOD_SITE_FILES;/i", $_SESSION['CUR_USER_ACCESS'])){
	echo "					<li class=\"nav-section\">\n";
	echo "						<div class=\"nav-heading\" id=\"files-nav-heading\">\n";
	echo "							<a class=\"nav-heading-link\" target=\"body\" href=\"".admin_nav_link('program/modules/site_files.php')."\">".lang('Files &amp; Images')."</a>\n";
	echo "							<span id=\"files-rollup\" class=\"rollup-button\"><a href=\"javascript:void(0);\" style=\"margin:0;padding:0;\">&gt;</a></span>\n";
	echo "						</div>\n";
	echo "						<ul id=\"subnav-files\" class=\"subnav\">\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/site_files.php')."\">".lang('Manage')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/upload_files.php')."\">".lang('Upload')."</a></li>\n";
	echo "						</ul>\n";
	echo "					</li>\n";
}

#Comment Out Blogs For Now

if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;MOD_BLOG;/i", $_SESSION['CUR_USER_ACCESS'])){
	echo "					<li class=\"nav-section\">\n";
	echo "						<div class=\"nav-heading\" id=\"blogs-nav-heading\">\n";
	echo "							<a class=\"nav-heading-link\" target=\"body\" href=\"".admin_nav_link('program/modules/blog/blog-entry.php')."\">".lang('Blogs')."</a>\n";
	echo "							<span id=\"blogs-rollup\" class=\"rollup-button\"><a href=\"javascript:void(0);\" style=\"margin:0;padding:0;\">&gt;</a></span>\n";
	echo "						</div>\n";
	echo "						<ul id=\"subnav-blogs\" class=\"subnav\">\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/blog/blog.php')."\">".lang('Create')."</a></li>\n";	
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/blog/blog-entry.php')."\">".lang('Manage')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/blog/authors.php')."\">".lang('Authors')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/blog/blog_comments.php')."\">".lang('Comments')."</a></li>\n";
	
	echo "						</ul>\n";
	echo "					</li>\n";
}


if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;MOD_SHOPPING_CART;/i", $_SESSION['CUR_USER_ACCESS'])){
	echo "					<li class=\"nav-section\">\n";
	echo "						<div class=\"nav-heading\" id=\"shoppingcart-nav-heading\">\n";
	echo "							<a class=\"nav-heading-link\" target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/shopping_cart.php')."\">".lang('Shopping Cart')."</a>\n";
	echo "							<span id=\"shoppingcart-rollup\" class=\"rollup-button\"><a href=\"javascript:void(0);\" style=\"margin:0;padding:0;\">&gt;</a></span>\n";
	echo "						</div>\n";
	echo "						<ul id=\"subnav-shoppingcart\" class=\"subnav\">\n";
	//echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/shopping_cart.php')."\">".lang('Shopping Cart Menu')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/shopping_cart/search_products.php')."\">".lang('Products')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/shopping_cart/categories.php')."\">".lang('Categories')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/shopping_cart/coupon_codes.php')."\">".lang('Coupons')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/shopping_cart/payment_options.php')."\">".lang('Payment')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/shopping_cart/shipping_options.php')."\">".lang('Shipping')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/shopping_cart/tax_rates.php')."\">".lang('Taxes')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/shopping_cart/view_orders.php')."\">".lang('Orders')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/shopping_cart/display_settings.php')."\">".lang('Appearance')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/shopping_cart/cart_comments.php')."\">".lang('Comments')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/shopping_cart/business_information.php')."\">".lang('Business Information')."</a></li>\n";	
	echo "						</ul>\n";
	echo "					</li>\n";
}

if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;MOD_CALENDAR;/i", $_SESSION['CUR_USER_ACCESS'])){
	echo "					<li class=\"nav-section\">\n";
	echo "						<div class=\"nav-heading\" id=\"calendar-nav-heading\">\n";
	echo "							<a class=\"nav-heading-link\" target=\"body\"  href=\"".admin_nav_link('program/modules/mods_full/event_calendar.php')."\">".lang('Calendar')."</a>\n";
	echo "							<span id=\"calendar-rollup\" class=\"rollup-button\"><a href=\"javascript:void(0);\" style=\"margin:0;padding:0;\">&gt;</a></span>\n";
	echo "						</div>\n";
	echo "						<ul id=\"subnav-calendar\" class=\"subnav\">\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/event_calendar.php')."\">".lang('Events')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/event_calendar/category_setup.php')."\">".lang('Categories')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/event_calendar/cal_display_settings.php')."\">".lang('Appearance')."</a></li>\n";
	echo "						</ul>\n";
	echo "					</li>\n";
}

if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;MOD_NEWSLETTER;/i", $_SESSION['CUR_USER_ACCESS'])){
	echo "					<li class=\"nav-section\">\n";
	echo "						<div class=\"nav-heading\" id=\"newsletter-nav-heading\">\n";
	echo "							<a class=\"nav-heading-link\" target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/enewsletter.php')."\">".lang('Email Newsletter')."</a>\n";
	echo "							<span id=\"newsletter-rollup\" class=\"rollup-button\"><a href=\"javascript:void(0);\" style=\"margin:0;padding:0;\">&gt;</a></span>\n";
	echo "						</div>\n";
	echo "						<ul id=\"subnav-newsletter\" class=\"subnav\">\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/enewsletter/create_campaign.php')."\">".lang('Create')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/enewsletter.php')."\">".lang('Manage')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/enewsletter/preferences.php')."\">".lang('Preferences')."</a></li>\n";
	echo "						</ul>\n";
	echo "					</li>\n";
}

if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;MOD_PHOTO_ALBUM;/i", $_SESSION['CUR_USER_ACCESS'])){
	echo "					<li class=\"nav-section\">\n";
	echo "						<div class=\"nav-heading\" id=\"photoalbums-nav-heading\">\n";
	echo "							<a class=\"nav-heading-link\" target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/photo_album/photo_album.php')."\">".lang('Photo Albums')."</a>\n";
	echo "							<span id=\"photoalbums-rollup\" class=\"rollup-button\"><a href=\"javascript:void(0);\" style=\"margin:0;padding:0;\">&gt;</a></span>\n";
	echo "						</div>\n";
	echo "						<ul id=\"subnav-photoalbums\" class=\"subnav\">\n";
	echo "						<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/photo_album/photo_album.php')."\">".lang('Albums')."</a></li>\n";
	echo "						<li><a target=\"body\" href=\"".admin_nav_link('program/modules/upload_files.php?menu=album')."\">".lang('Upload Images')."</a></li>\n";
	echo "						</ul>\n";
	echo "					</li>\n";
}

if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;MOD_FORMS;/i", $_SESSION['CUR_USER_ACCESS'])){
	echo "					<li class=\"nav-section\">\n";
	echo "						<div class=\"nav-heading\" id=\"webforms-nav-heading\">\n";
	echo "							<a class=\"nav-heading-link\" target=\"body\" href=\"".admin_nav_link('program/modules/forms_manager.php')."\">".lang('Web Forms')."</a>\n";
	echo "							<span id=\"webforms-rollup\" class=\"rollup-button\"><a href=\"javascript:void(0);\" style=\"margin:0;padding:0;\">&gt;</a></span>\n";
	echo "						</div>\n";
	echo "						<ul id=\"subnav-webforms\" class=\"subnav\">\n";
	echo "						<li><a target=\"body\" href=\"".admin_nav_link('program/modules/forms_manager.php')."\">".lang('Forms')."</a></li>\n";
	echo "						<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/download_data.php')."?menu=forms\">".lang('Submitted Data')."</a></li>\n";
	echo "						</ul>\n";
	echo "					</li>\n";
}

if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;MOD_EDIT_PAGES;/i", $_SESSION['CUR_USER_ACCESS'])){
	echo "					<li class=\"nav-section\">\n";
	echo "						<div class=\"nav-heading\" id=\"sitesearch-nav-heading\">\n";
	echo "							<a class=\"nav-heading-link\" target=\"body\" href=\"".admin_nav_link('program/modules/super_search/what_gets_searched.php')."\">".lang('Site Search')."</a>\n";
	echo "							<span id=\"sitesearch-rollup\" class=\"rollup-button\"><a href=\"javascript:void(0);\" style=\"margin:0;padding:0;\">&gt;</a></span>\n";
	echo "						</div>\n";
	echo "						<ul id=\"subnav-sitesearch\" class=\"subnav\">\n";
	echo "						<li><a target=\"body\" href=\"".admin_nav_link('program/modules/super_search/what_gets_searched.php')."\">".lang('What Gets Searched')."</a></li>\n";
	echo "						<li><a target=\"body\" href=\"".admin_nav_link('program/modules/super_search/search_display_settings.php')."\">".lang('Appearance')."</a></li>\n";
	echo "						<li><a target=\"body\" href=\"".admin_nav_link('program/modules/super_search/search_statistics.php')."\">".lang('Statistics')."</a></li>\n";
	echo "						</ul>\n";
	echo "					</li>\n";
}

if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;(MOD_DB_MANAGER|UDT_)/i", $_SESSION['CUR_USER_ACCESS'])){
	echo "					<li class=\"nav-section\">\n";
	echo "						<div class=\"nav-heading\" id=\"database-nav-heading\">\n";
	echo "							<a class=\"nav-heading-link\" target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/download_data.php')."\">".lang('Database Tables')."</a>\n";
	echo "							<span id=\"database-rollup\" class=\"rollup-button\"><a href=\"javascript:void(0);\" style=\"margin:0;padding:0;\">&gt;</a></span>\n";
	echo "						</div>\n";
	echo "						<ul id=\"subnav-database\" class=\"subnav\">\n";
	echo "						<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/download_data.php')."\">".lang('Edit Tables &amp; Data')."</a></li>\n";
	if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;(MOD_DB_MANAGER)/i", $_SESSION['CUR_USER_ACCESS'])){	
		echo "						<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/database_manager/create_table.php')."\">".lang('Create Table')."</a></li>\n";
		echo "						<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/database_manager/delete_table.php')."\">".lang('Delete Table')."</a></li>\n";
	}
	echo "						</ul>\n";
	echo "					</li>\n";
}


if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;MOD_SECURITY;/i", $_SESSION['CUR_USER_ACCESS'])){
	echo "					<li class=\"nav-section\">\n";
	echo "						<div class=\"nav-heading\" id=\"memberslogin-nav-heading\">\n";
	echo "							<a class=\"nav-heading-link\" target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/security.php')."\">".lang('Member Logins')."</a>\n";
	echo "							<span id=\"memberslogin-rollup\" class=\"rollup-button\"><a href=\"javascript:void(0);\" style=\"margin:0;padding:0;\">&gt;</a></span>\n";
	echo "						</div>\n";
	echo "						<ul id=\"subnav-memberslogin\" class=\"subnav\">\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/security.php')."\">".lang('Users &amp; Groups')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/security_create_user.php')."\">".lang('Add Member')."</a></li>\n";
	echo "						</ul>\n";
	echo "					</li>\n";
}

if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;MOD_FAQ;/i", $_SESSION['CUR_USER_ACCESS'])){
	echo "					<li class=\"nav-section\">\n";
	echo "						<div class=\"nav-heading\" id=\"faq-nav-heading\">\n";
	echo "							<a class=\"nav-heading-link\" target=\"body\" href=\"".admin_nav_link('program/webmaster/faq_manager.php')."\">".lang('FAQ Manager')."</a>\n";
	echo "							<span id=\"faq-rollup\" class=\"rollup-button\"><a href=\"javascript:void(0);\" style=\"margin:0;padding:0;\">&gt;</a></span>\n";
	echo "						</div>\n";
	echo "						<ul id=\"subnav-faq\" class=\"subnav\">\n";
	echo "						<li><a target=\"body\" href=\"".admin_nav_link('program/webmaster/faq_manager.php')."\">".lang("Manage FAQ's")."</a></li>\n";
	echo "						</ul>\n";
	echo "					</li>\n";
}


if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;MOD_STATS;/i", $_SESSION['CUR_USER_ACCESS'])){
	echo "					<li class=\"nav-section\">\n";
	echo "						<div class=\"nav-heading\" id=\"stats-nav-heading\">\n";
	echo "							<a class=\"nav-heading-link\" target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/statistics.php')."\">".lang('Traffic Statistics')."</a>\n";
	echo "							<span id=\"stats-rollup\" class=\"rollup-button\"><a href=\"javascript:void(0);\" style=\"margin:0;padding:0;\">&gt;</a></span>\n";
	echo "						</div>\n";
	echo "						<ul id=\"subnav-stats\" class=\"subnav\">\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/statistics.php')."\">".lang('Unique Visitors')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/statistics.php?TOP25=true')."\">".lang('Top 25 Pages')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/statistics.php?BYDAY=true')."\">".lang('Views By Day')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/statistics.php?BYHOUR=true')."\">".lang('Views By Hour')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/statistics.php?REFERER=true')."\">".lang('Referring Sites')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/statistics.php?BROWSERS=true')."\">".lang('Browser/OS Stats')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/statistics.php?SPIDERS=true')."\">".lang('Search Engine Spiders')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/mods_full/statistics.php?GOOG_ANALTICS=true')."\">".lang('Google Analytics')."</a></li>\n";
	echo "						</ul>\n";	
	echo "					</li>\n";
}

if($_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || preg_match("/;MOD_WEBMASTER;/i", $_SESSION['CUR_USER_ACCESS'])){
	echo "					<li class=\"nav-section\">\n";
	echo "						<div class=\"nav-heading\" id=\"settings-nav-heading\">\n";
	echo "							<a class=\"nav-heading-link\" target=\"body\" href=\"".admin_nav_link('program/webmaster/global_settings.php')."\">".lang('Global Settings')."</a>\n";
	echo "							<span id=\"settings-rollup\" class=\"rollup-button\"><a href=\"javascript:void(0);\" style=\"margin:0;padding:0;\">&gt;</a></span>\n";
	echo "						</div>\n";
	echo "						<ul id=\"subnav-settings\" class=\"subnav\">\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/site_templates.php')."\">".lang('Template Manager')."</a></li>\n";
	# Comment out for builds... <ahem>git</ahem>
	### Easier to uncomment for testing, than to merge the updated version of this file with the dev branch - see lines 84-108 *cough
	//echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/widgets/widget-manager.php')."\">".lang('Sidebar Manager')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/webmaster/global_settings.php')."\">".lang('Global Settings')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/webmaster/webmaster.php')."\">".lang('Admin Users')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/webmaster/business_info.php')."\">".lang('Default Contact Info')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/webmaster/meta_data.php')."\">".lang('Search Engine Ranking')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/webmaster/software_updates.php')."\">".lang('Software Updates')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/modules/google/webmaster-tools.php')."\">".lang('Google Webmaster Tools')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/webmaster/security_center.php')."\">".lang('Security Center')."</a></li>\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/webmaster/backup_restore.php')."\">".lang('Backup/Restore')."</a></li>\n";
	echo "						</ul>\n";
	echo "					</li>\n";
} elseif(preg_match("/;MOD_BACKUPRESTORE;/i", $_SESSION['CUR_USER_ACCESS'])){
	echo "					<li class=\"nav-section\">\n";
	echo "						<div class=\"nav-heading\" id=\"settings-nav-heading\">\n";
	echo "							<a class=\"nav-heading-link\" target=\"body\" href=\"".admin_nav_link('program/webmaster/backup_restore.php')."\">".lang('Global Settings')."</a>\n";
	echo "							<span id=\"settings-rollup\" class=\"rollup-button\"><a href=\"javascript:void(0);\" style=\"margin:0;padding:0;\">&gt;</a></span>\n";
	echo "						</div>\n";
	echo "						<ul id=\"subnav-settings\" class=\"subnav\">\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/webmaster/backup_restore.php')."\">".lang('Backup/Restore')."</a></li>\n";
	echo "						</ul>\n";
	echo "					</li>\n";
}

if($globalprefObj->get('enable_plugins')=='yes'){
	echo "					<li class=\"nav-section\">\n";
	echo "						<div class=\"nav-heading\" id=\"plugins-nav-heading\">\n";
	echo "							<a class=\"nav-heading-link\" target=\"body\" href=\"".admin_nav_link('program/webmaster/plugin_manager/plugin_manager.php')."\">Plugins</a>\n";
	echo "							<span id=\"plugins-rollup\" class=\"rollup-button\"><a href=\"javascript:void(0);\" style=\"margin:0;padding:0;\">&gt;</a></span>\n";
	echo "						</div>\n";
	echo "						<ul id=\"subnav-plugins\" class=\"subnav\">\n";
	echo "							<li><a target=\"body\" href=\"".admin_nav_link('program/webmaster/plugin_manager/plugin_manager.php')."\">Manage Plugins</a></li>\n";
	echo "						</ul>\n";
	echo "					</li>\n";
}

echo "<li class=\"last\" style=\"border-radius:0px 0px 10px 10px;\" >&nbsp;</li>\n";
//echo "<li class=\"last\" style=\"background:#B7B7B7;width:173px;height:11px;border:5px solid #868686;text-align:right;\">&nbsp;</li>\n";
//echo "						<a style=\"color:black\" href=\"javascript:void(0);\">+expand all</a>\n";
echo "					</li>\n";
echo "				</ul>\n";


//echo "<div style=\"bottom: 0; position: absolute; left: 0;\">\n";
echo "<div style=\"margin-top:3px; float:left; display:static; bottom:0px; clear:both; white-space:none;z-index:99999;width:178px; text-align:center;\">\n<a href=\"javascript:void(0);\" onclick=\"nav_collapse_all();\">".lang('Collapse All')."</a> | <a href=\"javascript:void(0);\" onclick=\"nav_expand_all();\">".lang('Expand All')."</a></div>\n";
echo "<div style=\"margin-top:10px; float:left; display:static; bottom:0px; clear:both; width:100%;\">\n";
//echo "<img src=\"http://ultra.soholaunch.com/build-a-site.png\" border=\"0\" style=\"float:left;width:86;height:21px;margin-right:5px;\">\n";
//echo "<img src=\"".admin_nav_link('program/includes/images/soholaunch-powered2.png')."\" border=\"0\">\n";
echo "</div>\n";

echo "<div style=\"margin-top:3px; display:static; bottom:0px; clear:both; white-space:none;z-index:99999;width:178px; text-align:center;\">\n<a href=\"http://ultra.soholaunch.com/sohoadmin/program/modules/dashboard.php?logout=logout\" target=\"_parent\" class=\"del\">".lang('Log-Out')."</a></div>\n";
echo "		</div>\n";
		
//echo "		<div class=\"right-panel\" id=\"right-panel\" >	\n";
//echo "			<div class=\"top-left\" ></div>\n";
//echo "				<!---Report messages-->\n";
//echo "			   <div id=\"report_messages\" style=\"display: #REPORT_DISPLAY#;cursor: pointer; float: right;\" onclick=\"hideid('report_messages');\">\n";
//echo "			   	#REPORT_MESSAGES#\n";
//echo "			   </div>\n";
//echo "			<h3>#ICON_IMG##HEADING_TEXT#</h3>\n";
//echo "			<p  style=\"width:100%;\" id=\"module_description_text\">#DESCRIPTION_TEXT#</p>\n";
//echo "			<div>#MODULE_HTML#</div>\n";
//echo "		</div>\n";
echo "		<div class=\"clear\"></div>\n";
echo "	</div>\n";
echo "</div>\n";


echo "<script type=\"text/javascript\">\n";
//echo "//$('#nav-shoppingcart').hide();\n";

echo "</script>\n";

echo "<script type=\"text/javascript\">\n";

# show/hide menu items based on saved preferences
foreach ( $nav_heading_array as $key=>$value ) {
	echo '$(\'#subnav-'.$key.'\').'.$value.'();'."\n";
	if ( $value == 'hide' ) {
		echo '$(\'#'.$key.'-nav-heading\').addClass(\'collapsed\');'."\n";
	}
}

	//echo "if(top.location != location){\n";
	//echo "	parent.frames.footer.setPage('#HEADING_TEXT#');\n";
	//echo "}\n";


echo "</script>\n";
echo "</body>\n";
echo "</html>\n";
?>