<?php
############################################################################################
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
require_once("product_gui.php");
############################################################################################

function admin_nav_link($path_from_program_dir) {
	return 'http://'.$_SESSION['docroot_url'].'/sohoadmin/program/'.$path_from_program_dir;
}
exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>#META_TITLE#</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="-1">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://<? echo $_SESSION['docroot_url']; ?>/sohoadmin/program/includes/display_elements/js_functions.php"></script>
<script type="text/javascript">
// Hide top frame if coming from page editor
if ( parent.document.getElementById('master_frameset') ) {
	parent.document.getElementById('master_frameset').rows = '1,*,1,19';
}

jQuery(document).ready(function(){
	$('.nav .navheading').click(function() {
		if ( $(this).hasClass('collapsed') ) { show_or_hide = 'show'; } else { show_or_hide = 'hide'; }
		$(this).next().toggle('blind');
		var heading_id = this.id;
		$(this).toggleClass('collapsed');
		$('#jqresult').load('http://<? echo $_SESSION['docroot_url']; ?>/sohoadmin/program/includes/preference-saver.ajax.php?heading_id='+heading_id+'&show_or_hide='+show_or_hide);
		return false;
	});
});
</script>

<?php
$dev_revamped_modules_array = array('open_page');
$dev_current_module_file = str_replace('.php', '', basename($_SERVER['PHP_SELF']));
//if ( !in_array($dev_current_module_file, $dev_revamped_modules_array) ) {
?>
<link rel="stylesheet" href="http://<? echo $_SESSION['docroot_url']; ?>/sohoadmin/program/product_gui.css">
<?php
//} // end if module not revamped yet

$global_admin_prefs = new userdata('admin');
$nav_heading_array = $global_admin_prefs->get('nav_heading_array');
//echo testArray($nav_heading_array);
?>

<link rel="stylesheet" href="http://<? echo $_SESSION['docroot_url']; ?>/sohoadmin/program/includes/product_interface-ultra.css">
</head>
<body>
<div class="container">
	<div class="top">
		<div class="name">
			<h2><a href="<?= admin_nav_link('modules/dashboard.php'); ?>">Widgets, Inc.</a></h2>
			<h3 class="breadcrumb"><a href="#">Main Menu</a> > #BREADCRUMB_LINKS#</h3>
		</div>
		<div class="account">
			<h3>Hello, <span>Admin</span></h3>			
			<a href="#" class="logout" >view website</a>
			<a href="#" class="help">Help</a><a href="#" class="logout">Log Out</a>
			
		</div>
<!--
		<div class="search">
			<input type="text" class="srchtxt" value="Search" /><input type="submit" class="submitbtn" />
		</div>
-->
		<div class="clear"></div>
	</div>

	<div class="main-container">
		<div class="left-panel">
			<div id="jqresult">ajax results go here</div>
			<ul class="nav">
				<li><a href="#" id="dashboard-heading" class="dashboard navheading"><strong>Main Menu<span>>></span></strong></a>
			    	<ul id="nav-dashboard">
			        	<li><a href="<?= admin_nav_link('modules/dashboard.php'); ?>">Main Menu</a></li>
			      </ul>
			    </li>
			    <li><a href="#" id="pages-heading" class="pages navheading"><strong>Pages<span>>></span></strong></a>
			    	<ul id="nav-pages">
			        	<li><a href="<?= admin_nav_link('modules/open_page.php'); ?>">Edit</a></li>
			        	<li><a href="<?= admin_nav_link('modules/create_pages.php'); ?>">Add New</a></li>
			        </ul>
			    </li>			    
				<li><a href="#" id="lookandfeel-heading" class="lookandfeel navheading"><strong>Look &amp; Feel<span>>></span></strong></a>
			    	<ul id="nav-lookandfeel">
			        	<li><a href="<?= admin_nav_link('modules/site_templates.php'); ?>">Overall Site Template</a></li>
<!--- 			        	<li><a href="<?= admin_nav_link('modules/site_templates.php'); ?>">Logo &amp; Text Changes</a></li>
			        	<li><a href="<?= admin_nav_link('modules/site_templates/template_images.php'); ?>">Template Image Swapper</a></li>
			        	<li><a href="<?= admin_nav_link('modules/promo_boxes/promo_boxes.php'); ?>">Template Boxes &amp; Widgets</a></li> -->
			        	<li><a href="<?= admin_nav_link('modules/auto_menu_system.php'); ?>">Menu Navigation</a></li>
			        	<li><a href="<?= admin_nav_link('modules/mods_full/shopping_cart/display_settings.php'); ?>">Shopping Cart Appearance</a></li>
			        	<li><a href="<?= admin_nav_link('modules/mods_full/event_calendar/display_settings.php'); ?>">Calendar Appearance</a></li>
			        </ul>
			    </li>
			    <li><a href="#" id="files-heading" class="files navheading"><strong>Files<span>>></span></strong></a>
			    	<ul id="nav-files">
			        	<li><a href="<?= admin_nav_link('modules/site_files.php'); ?>">Files</a></li>
			        	<li><a href="<?= admin_nav_link('modules/upload_files.php'); ?>">Upload New</a></li>
			        </ul>
			    </li>
			    <li><a href="#" id="blog-heading" class="blog navheading"><strong>Blogs<span>>></span></strong></a>
			    	<ul id="nav-blog">
			    		<li><a href="<?= admin_nav_link('modules/blog.php'); ?>">Manage Posts</a></li>
			        	<li><a href="<?= admin_nav_link('modules/blog/blog_comments.php'); ?>">Comments & Settings</a></li>
			        	<li><a href="<?= admin_nav_link('modules/blog/blog_styles.php'); ?>">Stylesheet</a></li>
			        </ul>
			    </li>			    
			    <li><a href="#" id="shoppingcart-heading" class="shopping navheading"><strong>Shopping Cart<span>>></span></strong></a>
			    	<ul id="nav-shoppingcart">
			    		<li><a href="<?= admin_nav_link('modules/mods_full/shopping_cart.php'); ?>">Shopping Cart Menu</a></li>
			        	<li><a href="<?= admin_nav_link('modules/mods_full/shopping_cart/view_orders.php'); ?>">Orders</a></li>
			        	<li><a href="<?= admin_nav_link('modules/mods_full/shopping_cart/search_products.php'); ?>">Products</a></li>
			        	<li><a href="<?= admin_nav_link('modules/mods_full/shopping_cart/categories.php'); ?>">Categories</a></li>
			        	<li><a href="<?= admin_nav_link('modules/mods_full/shopping_cart/shipping_options.php'); ?>">Shipping</a></li>								           	
			         <li><a href="<?= admin_nav_link('modules/mods_full/shopping_cart/tax_rates.php'); ?>">Taxes</a></li>
			         <li><a href="<?= admin_nav_link('modules/mods_full/shopping_cart/payment_options.php'); ?>">Payment</a></li>
			        </ul>
			    </li>
			    <li><a href="#" id="webforms-heading" class="webforms navheading"><strong>Modules<span>>></span></strong></a>
			    	<ul id="nav-webforms">
			        	<li><a href="<?= admin_nav_link('modules/forms_manager.php'); ?>">Web Forms</a></li>
			        	<li><a href="<?= admin_nav_link('modules/mods_full/photo_album/photo_album.php'); ?>">Photo Albums</a></li>
			        	<li><a href="<?= admin_nav_link('webmaster/faq_manager.php'); ?>">FAQs</a></li>
			      </ul>
			    </li>
			
			    <li><a href="#" id="memberslogin-heading" class="memberslogin navheading"><strong>Member Logins<span>>></span></strong></a>
			    	<ul id="nav-memberslogin">
			        	<li><a href="<?= admin_nav_link('modules/mods_full/security.php'); ?>">Users &amp; Groups</a></li>
			        	<li><a href="<?= admin_nav_link('modules/mods_full/security_create_user.php'); ?>">Add Member</a></li>
			        	<!--- <li><a href="#">Batch Import</a></li> -->
			      </ul>
			    </li>

			    <li><a href="#" id="calendar-heading" class="calendar navheading"><strong>Event Calendar<span>>></span></strong></a>
			    	<ul id="nav-calendar">
			        	<li><a href="<?= admin_nav_link('modules/mods_full/event_calendar.php'); ?>">Calendar</a></li>
			        	<li><a href="<?= admin_nav_link('modules/mods_full/event_calendar/display_settings.php'); ?>">Display Settings</a></li>
			        	<li><a href="<?= admin_nav_link('modules/mods_full/event_calendar/category_setup.php'); ?>">Categories</a></li>
			        </ul>
			    </li>
			    
			    <li><a href="#" id="newsletter-heading" class="calendar navheading"><strong>Email Newsletter<span>>></span></strong></a>
			    	<ul id="nav-newsletter">
			        	<li><a href="<?= admin_nav_link('modules/mods_full/enewsletter.php'); ?>">Manage</a></li>
			        	<li><a href="<?= admin_nav_link('modules/mods_full/enewsletter/create_campaign.php'); ?>">New Campaign</a></li>
			        	<li><a href="<?= admin_nav_link('modules/mods_full/enewsletter/preferences.php'); ?>">Preferences</a></li>
			        </ul>
			    </li>			    
			
<!--- 			    <li><a href="#" id="seo-heading" class="seo navheading"><strong><span>Search Engine Optimization</span></strong></a>
			    	<ul id="nav-seo">
			        	<li><a href="#">SEO Adviser</a></li>
			        	<li><a href="#">Link &amp; URL Manager</a></li>
			        	<li><a href="#">SiteMap Generator</a></li>
			        </ul>
			    </li> -->
			
				<li><a href="#" id="stats-heading" class="stats navheading"><strong>Statistics<span>>></span></strong></a>
			    	<ul id="nav-stats">
			        	<li><a href="<?= admin_nav_link('modules/mods_full/statistics.php'); ?>">Unique Visitors</a></li>
			        	<li><a href="<?= admin_nav_link('modules/mods_full/statistics.php'); ?>">Top 25 Pages</a></li>
			        	<li><a href="<?= admin_nav_link('modules/mods_full/statistics.php'); ?>">Views By Day</a></li>
			        	<li><a href="<?= admin_nav_link('modules/mods_full/statistics.php'); ?>">Views By Hour</a></li>
			        	<li><a href="<?= admin_nav_link('modules/mods_full/statistics.php'); ?>">Referring Sites</a></li>
			        	<li><a href="<?= admin_nav_link('modules/mods_full/statistics.php'); ?>">Browser/OS Stats</a></li>
			        	<li><a href="<?= admin_nav_link('modules/mods_full/statistics.php'); ?>">Serach Engine Spiders</a></li>
			        	<li><a href="<?= admin_nav_link('modules/mods_full/statistics/includes/googleAnalytics.php'); ?>">Google Analytics</a></li>
			        </ul>
			    </li>
			    
			    <li><a href="#" id="plugins-heading" class="plugins navheading"><strong>Plugins<span>>></span></strong></a>
			    	<ul id="nav-plugins">
			        	<li><a href="<?= admin_nav_link('webmaster/plugin_manager/plugin_manager.php'); ?>">Manage Plugins</a></li>
			      </ul>
			    </li>
			
			    <li><a href="#" id="settings-heading" class="settings navheading"><strong>Settings<span>>></span></strong></a>
			    	<ul id="nav-settings">
			        	<li><a href="<?= admin_nav_link('webmaster/webmaster.php'); ?>">Admin Users</a></li>
			        	<li><a href="<?= admin_nav_link('webmaster/global_settings.php'); ?>">Global Preferences</a></li>
			        	<li><a href="<?= admin_nav_link('webmaster/business_info.php'); ?>">Default Contact Info</a></li>
			        	<li><a href="<?= admin_nav_link('webmaster/meta_data.php'); ?>">Search Engine Ranking</a></li>
			        	<li><a href="<?= admin_nav_link('webmaster/software_updates.php'); ?>">Version Updates</a></li>
			      </ul>
			    </li>
			    
				<li class="last"><a href="#">last</a>
			    </li>
			</ul>
		</div>
		
		<div class="right-panel">	
		<div class="top-left"></div>
			<h3>#HEADING_TEXT#</h3>
			<p id="module_description_text">#DESCRIPTION_TEXT#</p>
			<div style="clear: left;">#MODULE_HTML#</div>
		</div>
		
		<div class="clear"></div>
	</div>
</div>

<script type="text/javascript">
//$('#nav-shoppingcart').hide();
</script>

<script type="text/javascript">
<?php
# show/hide menu items based on saved preferences
foreach ( $nav_heading_array as $key=>$value ) {
	echo '$(\'#nav-'.$key.'\').'.$value.'();'."\n";
	if ( $value == 'hide' ) {
		echo '$(\'#'.$key.'-heading\').addClass(\'collapsed\');'."\n";
	}
}
?>
</script>

</body>
</html>
