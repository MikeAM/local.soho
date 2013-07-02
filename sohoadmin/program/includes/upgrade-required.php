<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();
require_once("product_gui.php");

$linkincludefile=$_SESSION['doc_root'].'/sohoadmin/config/ultrahost.link.php';
if(file_exists($linkincludefile)){
	include_once($linkincludefile);	
} else {
	$upgrade_ultra_link = 'http://www.soholaunch.com/Contact_Us.php?Message=I would like to get more information about upgrading my site, '.$_SESSION['this_ip'].', to the full version of Ultra.';
}
if($_GET['show']=='summary' && $module_section=='cart'){
	$module_section='summary';
}
$module_promo_array = array();


# blog
$module_promo_array['blog']['heading_text'] = 'Blogging is one of the best ways to build loyalty and attract new visitors.';
$module_promo_array['blog']['subheading_text'] = 'Upgrade to the full version and start blogging now.';
$module_promo_array['blog']['icon'] = "http://".$_SESSION['docroot_url']."/sohoadmin/program/includes/images/blog-icon-large.png";
$module_promo_array['blog']['bullets'][] = 'Create unlimited blog posts with images and video.';
$module_promo_array['blog']['bullets'][] = 'Organize your posts into categories.';
$module_promo_array['blog']['bullets'][] = 'Add accounts for guest authors and other writers.';
$module_promo_array['blog']['bullets'][] = 'Interact with your readers through blog comments.';

# cart
$module_promo_array['cart']['heading_text'] = 'Ready to make money with your website?';
$module_promo_array['cart']['subheading_text'] = 'The shopping cart is one of several amazing features in the full version.';
$module_promo_array['cart']['icon'] = "http://".$_SESSION['docroot_url']."/sohoadmin/program/includes/images/shopping-icon-large.png";
$module_promo_array['cart']['bullets'][] = 'Create unlimited shopping cart products with images, descriptions, and color variations (optional).';
$module_promo_array['cart']['bullets'][] = 'Accept payments from all major credit cards, PayPal, and over a dozen other popular payment gateways.';
$module_promo_array['cart']['bullets'][] = 'Automatic UPS shipping rate calculation makes shipping charges easy.';
$module_promo_array['cart']['bullets'][] = 'Encourage visitors to buy more with coupons and volume discounts.';

# calendar
$module_promo_array['calendar']['heading_text'] = 'Keep visitors informed about upcoming events.';
$module_promo_array['calendar']['subheading_text'] = 'Upgrade to the full version and start creating Event Calendars now.';
$module_promo_array['calendar']['icon'] = "http://".$_SESSION['docroot_url']."/sohoadmin/program/includes/images/calendar-icon-large.png";
$module_promo_array['calendar']['bullets'][] = 'Add weekly and monthly event calendars to your website.';
$module_promo_array['calendar']['bullets'][] = 'Create one-time and recurring events.';
$module_promo_array['calendar']['bullets'][] = 'Make some events only visible to memebers.';
$module_promo_array['calendar']['bullets'][] = 'Easily link calendar events to pages with extra details.';

# database_manager
$module_promo_array['database_manager']['heading_text'] = 'Powerful tools to manage your site data';
$module_promo_array['database_manager']['subheading_text'] = 'The Database Table Manager is one of several amazing features in the full version.';
$module_promo_array['database_manager']['icon'] = "http://".$_SESSION['docroot_url']."/sohoadmin/program/includes/images/data-tables-icon-large.png";
$module_promo_array['database_manager']['bullets'][] = 'Import members, products, and other data from MS Excel';
$module_promo_array['database_manager']['bullets'][] = 'Download all shopping cart order data as a spreadsheet.';
$module_promo_array['database_manager']['bullets'][] = 'Create custom searches (e.g., "search our members").';
$module_promo_array['database_manager']['bullets'][] = 'Bulk import new members into the Member Logins feature';
$module_promo_array['database_manager']['bullets'][] = 'Manage Newsletter mailing lists';

# memeber logins
$module_promo_array['security']['heading_text'] = 'Create Members-Only areas to protect sensitive info.';
$module_promo_array['security']['icon'] = "http://".$_SESSION['docroot_url']."/sohoadmin/program/includes/images/member-logins-icon-large.png";
$module_promo_array['security']['subheading_text'] = 'Upgrade to the full version and start creating Members-Only areas now!';
$module_promo_array['security']['bullets'][] = 'Create unlimited memebers with unique contact info and pictures.';
$module_promo_array['security']['bullets'][] = 'Assign different clearance levels for diffiferent members (e.g., "Secret" and "Top Secret").';
$module_promo_array['security']['bullets'][] = 'Password-protect shopping cart items, calendars, pages, and more.';
$module_promo_array['security']['bullets'][] = 'Import thousands of members at once from a spreadsheet.';

# newsletter
$module_promo_array['newsletter']['heading_text'] = 'Communicate your message to thousands of prospects.';
$module_promo_array['newsletter']['subheading_text'] = 'Upgrade to the full version and start sending Email Newsletters now.';
$module_promo_array['newsletter']['icon'] = "http://".$_SESSION['docroot_url']."/sohoadmin/program/includes/images/newsletter-icon-large.png";
$module_promo_array['newsletter']['bullets'][] = 'Create lists from website contacts, shopping cart customers, and more.';
$module_promo_array['newsletter']['bullets'][] = 'Custom mailing lists easily for special campaigns.';
$module_promo_array['newsletter']['bullets'][] = 'Personalize messages (e.g., "Dear Bill,")';
$module_promo_array['newsletter']['bullets'][] = 'Built-in unsubscribe feature keeps you compliant with anti-spam regulations.';


# summary
$module_promo_array['summary']['heading_text'] = 'What\'s included in the full version?';
$module_promo_array['summary']['subheading_text'] = 'Get the most out of your website by upgrading to the full version.';
$module_promo_array['summary']['icon'] = "http://".$_SESSION['docroot_url']."/sohoadmin/program/includes/images/soholaunch-hand.png";
$module_promo_array['summary']['bullets'][] = 'No page limits! Create as many pages as you want.';
$module_promo_array['summary']['bullets'][] = 'Additional Features: '.ucwords(str_replace('cart','Shopping Cart', str_replace('_', ' ', implode(', ',$disabled_modules)))).'!';
$module_promo_array['summary']['bullets'][] = 'Unlimited Support! Get help straight from Soholaunch developers.';
$module_promo_array['summary']['bullets'][] = 'No Adds! Remove all ads from your site.';
# Choose current
$promoArr = $module_promo_array[$module_section];

ob_start();
?>
<link rel="stylesheet" type="text/css" href="http://<?php echo $_SESSION['docroot_url']; ?>/sohoadmin/program/includes/upgrade-required.css"/>

<div id="upgrade-required">
	<div class="logo clearfix">
<?php
//		echo "<img src=\"http://".$_SESSION['docroot_url']."/sohoadmin/program/includes/images/soholaunch-hand.png\"/>";
		echo "<img src=\"".$promoArr['icon']."\"/>";
?>
		<div>
			<h1><?php echo $promoArr['heading_text']; ?></h1>
			<h2><?php echo $promoArr['subheading_text']; ?></h2>
		</div>
	</div>
		
	<ul>
<?php
$max = count($promoArr['bullets']);
for ( $b=0; $b < $max; $b++ ) {
?>
		<li><?php echo $promoArr['bullets'][$b]; ?></li>
<?php
} // End for loop
?>
	</ul>
<?php	
echo "	<p class=\"button-container\"><a href=\"".$upgrade_ultra_link."\" target=\"_BLANK\" class=\"greenButton\"><strong><span>Upgrade to Full Version</span></strong></a></p>\n";
//$disabled_modules = array('blog','security', 'calendar', 'cart', 'newsletter', 'database_manager'); // Uncomment to test

?>
	<div id="full-features" class="clearfix">
		<strong>Full Version Features:</strong>
<?php
$disabled_modules_info['blog'] = "		<a href=\"http://".$_SESSION['docroot_url']."/sohoadmin/program/modules/blog/blog-entry.php\" title=\"blog\"><img src=\"http://".$_SESSION['docroot_url']."/sohoadmin/program/includes/images/blog-icon-large.png\"/><span>Blogs</span></a>\n";
$disabled_modules_info['security'] = "		<a href=\"http://".$_SESSION['docroot_url']."/sohoadmin/program/modules/mods_full/security.php\" title=\"security\"><img src=\"http://".$_SESSION['docroot_url']."/sohoadmin/program/includes/images/member-logins-icon-large.png\"/><span>Members Only</span></a>\n";
$disabled_modules_info['calendar'] = "		<a href=\"http://".$_SESSION['docroot_url']."/sohoadmin/program/modules/mods_full/event_calendar.php\" title=\"calendar\"><img src=\"http://".$_SESSION['docroot_url']."/sohoadmin/program/includes/images/calendar-icon-large.png\"/><span>Calendars</span></a>\n";
$disabled_modules_info['cart'] = "		<a href=\"http://".$_SESSION['docroot_url']."/sohoadmin/program/modules/mods_full/shopping_cart.php\" title=\"cart\"><img src=\"http://".$_SESSION['docroot_url']."/sohoadmin/program/includes/images/shopping-icon-large.png\"/><span>Shopping</span></a>\n";
$disabled_modules_info['newsletter'] = "		<a href=\"http://".$_SESSION['docroot_url']."/sohoadmin/program/modules/mods_full/enewsletter.php\" title=\"newsletter\"><img src=\"http://".$_SESSION['docroot_url']."/sohoadmin/program/includes/images/newsletter-icon-large.png\"/><span>Newsletters</span></a>\n";
$disabled_modules_info['database_manager'] = "		<a href=\"http://".$_SESSION['docroot_url']."/sohoadmin/program/modules/mods_full/download_data.php\" title=\"database_manager\"><img src=\"http://".$_SESSION['docroot_url']."/sohoadmin/program/includes/images/data-tables-icon-large.png\"/><span>Data Tables</span></a>\n";

echo "		<a href=\"http://".$_SESSION['docroot_url']."/sohoadmin/program/modules/mods_full/shopping_cart.php?show=summary\" title=\"summary\"><img src=\"http://".$_SESSION['docroot_url']."/sohoadmin/program/includes/images/soholaunch-hand.png\"/><span>Features</span></a>\n";

foreach($disabled_modules as $modvar){
	echo $disabled_modules_info[$modvar];
}
?>
	</div>
</div>

<script type="text/javascript">
$(document).ready (function() {
	$('#full-features a[title="<?php echo $module_section; ?>"]').addClass('current');
});
</script>
<?php
$upgrade_required_html = ob_get_contents();
ob_end_clean();
?>