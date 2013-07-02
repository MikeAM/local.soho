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

#################################################
## UPPER NAV BAR
#################################################

// Main Menu
$lang["Open Page"] = "Edit Page(s)";
$lang["Main Menu"] = "Main Menu";
$lang["View Site"] = "View Website";
$lang["Webmaster"] = "Webmaster";
$lang["Logout"] = "Logout";

// Page Editor
$lang["Save Page"] = "Save Page";
$lang["Save As"] = "Save As";
$lang["Preview Page"] = "Preview Page";
$lang["Page Properties"] = "Page Properties";

// Feature Menus
$lang['Shopping Cart Menu'] = "Shopping Cart Menu";
$lang['Calendar Menu'] = "Calendar Menu";
$lang['eNewsletter Menu'] = "eNewsletter Menu";
$lang['Database Menu'] = "Database Menu";


#################################################
## STATUS BAR (footer)
#################################################
$lang["Product Build"] = "Product Build";


#################################################
## FEATURE PROMO / LICENSE UPGRAGE PAGE
## - When user clicks on 'disabled' feature
#################################################
$lang["Feature Upgrade Required"] = "Feature Upgrade Required";
$lang["Your current license does not allow you to access this feature."] = "Your current license does not allow you to access this feature.";
$lang["In order to activate it, please contact :HOSTCO_NAME: and request an upgrade."] = "In order to activate it, please contact :HOSTCO_NAME: and request an upgrade."; // :HOSTCO_NAME: replaced with host-configured data
$lang["Once you are notified that the new feature(s) have been activated, return to this screen and click the &quot;Upgrade License&quot; button. This will download and install your new license and components automatically."] = "Once you are notified that the new feature(s) have been activated, return to this screen and click the &quot;Upgrade License&quot; button. This will download and install your new license and components automatically.";

// Live progress report (while getting new key)
$lang['promo']['locating license'] = "Locating current license";
$lang['promo']['license downloaded'] = "New license downloaded";
$lang['promo']['installing license'] = "Installing active license matrix for "; // ' $_SERVER['HTTP_HOST']
$lang['promo']['please hold'] = "Please Hold";
$lang['promo']['features upgraded'] = "Site features upgraded";

$lang['Upgrade License'] = "Upgrade License"; // Button


#################################################
## MAIN MENU
#################################################

// General Titles and Notifications
$lang["Site Visitor(s) online"] = "Site Visitor(s) Online";
$lang["NOTE: Any data outstanding will not be saved."] = "NOTE: Any data outstanding will not be saved.";

// Basic Features Group
$lang["Basic Features Group"] = "Basic Features Group";
$lang["Create New Pages"] = "Create New Page(s)";
$lang["Edit Pages"] = "Open/Edit Page(s)";
$lang["Menu Display"] = "Menu Display";
$lang["File Manager"] = "File Manager";
$lang["Template Manager"] = "Template Manager";
$lang["Forms Manager"] = "Forms Manager";

// Advanced Features Group
$lang["Advanced Features Group"] = "Advanced Features Group";

$lang["Shopping Cart"] = "Shopping Cart";
$lang["Event Calendar"] = "Event Calendar";
$lang["eNewsletter"] = "eNewsletter";
$lang["Site Data Tables"] = "Site Data Tables";
$lang["Database Table Manager"] = "Database Table Manager";
$lang["Secure Users"] = "Secure Users";
$lang["Photo Albums"] = "Photo Albums";
$lang["Site Statistics"] = "Site Statistics";
$lang["Blog Manager"] = "Blog Manager";

// Javascript Alerts

$lang["Select a menu option from the main menu sections to get started."] = "Select a menu option from the main menu sections to get started.";
$lang["You do not have access to this option."] = "You do not have access to this option.";

// Footer Assets

$lang["About"] = "About";

#################################################
## CREATE NEW PAGES MODULE					        ##
#################################################

$lang["Page Name"] = "Page Name";
$lang["Page Type"] = "Page Type";
$lang["Create New Site Pages"] = "Create New Site Pages";
$lang["Menu Page"] = "Menu Page";
$lang["Newsletter"] = "Newsletter";
$lang["Calendar Attachment"] = "Calendar Attachment";
$lang["Shopping Cart Attachment"] = "Shopping Cart Attachment";
$lang["Create More Pages"] = "Create More Pages";
$lang["You may create up to 10 new pages at a time."] = "You may create up to 10 new pages at a time.";
$lang["Your new pages have been created!"] = "Your new pages have been created!\\n\\nYou can start editing by opening the page now\\nor choose to create more new pages.";
$lang["Could Not Create the Following Pages because they already exist on the system:"] = "Could Not Create the Following Pages because they already exist on the system:";


#################################################
## OPEN PAGE MODULE							        ##
#################################################
$lang["Edit Content"] = "Edit Content";
$lang["Menu Status"] = "Menu Status";
$lang["Parent Page"] = "Parent Page";
$lang["Page Template"] = "Page Template";
$lang["Delete Page"] = "Delete Page";
$lang["Off Menu"] = "Off Menu";
$lang["On Menu"] = "On Menu";
$lang["site base template"] = "site base template";
$lang["Browse"] = "Browse";
$lang["Edit"] = "Edit";
$lang["Delete"] = "Delete";
$lang["Number Skus"] = "Number Skus";
$lang["Articles"] = "Articles";
$lang["Latest News"] = "Latest News";

$lang["Click on the page name that you wish to edit"] = "Click on the page name that you wish to edit.";
$lang["Are you sure you wish to delete this page"] = "Are you sure you wish to delete this page? You can not undo this event!";

#################################################
## page_editor.php
#################################################
$lang["Click on an object below and drag it onto a drop zone for page placement."] = "Click on an object below and drag it onto a drop zone for page placement.";


#################################################
## MENU DISPLAY MODULE     						  ##
#################################################
$lang["You have already used this page in your menu system."] = "You have already used this page in your menu system.";
$lang["You can only use pages one time on your auto-menu system."] = "You can only use pages one time on your auto-menu system.";
$lang["Auto-Menu Display Type"] = "Auto-Menu Display Type";
$lang["Text Links"] = "Text Links";
$lang["Buttons"] = "Buttons";
$lang["Edit Button Colors"] = "Edit Button Colours";
$lang["Text Menu Display"] = "Text Menu Display";
$lang["Yes"] = "Yes";
$lang["No"] = "No";
$lang["Available Pages"] = "Available Pages";
$lang["Current Menu"] = "Current Menu";

$lang["Select a page from your available site pages."] = "Select a page from your available site pages.";
$lang["Then, choose to add it to the bottom<BR>of your 'live' menu as a Main Menu Item or a Sub-Page of a Main Menu Item."] = "Then, choose to add it to the bottom<BR>of your 'live' menu as a Main Menu Item or a Sub-Page of a Main Menu Item.";

$lang["To Delete a page on the current menu"] = "To Delete a page on the current menu";
$lang["select the page from the available pages"] = "select the page from the available pages";
$lang["that already appear on your current"] = "that already appears in your current";
$lang["menu, then click 'Delete Page'."] = "menu, then click 'Delete Page'.";

$lang["Auto-Menu Button Colors"] = "Auto-Menu Button Colours";
$lang["Current Button Color Scheme"] = "Current Button Colour Scheme";
$lang["Button Color"] = "Button Color";
$lang["Button Text Color"] = "Button Text Colour";
$lang["Hex Color"] = "Hex Color";
$lang["About Us"] = "About Us";
$lang["Save Button Colors"] = "Save Button Coors";
$lang["Auto-Menu Setup"] = "Auto-Menu Setup";
$lang["This is a text representation of the colour scheme"] = "This is a sample button that represents the color scheme\\ncurrently selected for your menu navigation system.";

//Buttons
$lang["Add Main"] = "Add Main";
$lang["Add Sub"] = "Add Sub";
$lang["Clear Menu"] = "Clear Menu";
$lang["Save Menu System"] = "Save Menu System";



#################################################
## FILE MANAGER MODULE     						  ##
#################################################
$lang["File Name"] = "File Name";
$lang["File Size"] = "File Size";
$lang["Image files can be viewed and saved by clicking the preview icon next to the filename."] = "Image files can be viewed and saved by clicking the preview icon next to the filename.";
$lang["Indicates an image that should be reduced in filesize. This file causes slow load-times when viewing your web site."] = "Indicates an image that should be reduced in filesize. This file causes slow load-times when viewing your web site.";
$lang["Upload New Files"] = "Upload New Files";
$lang["Remember"] = "Remember";
$lang["Changes and deletions are final and can not be undone."] = "Changes and deletions are final and can not be undone.";
$lang["Update File Changes"] = "Update File Changes";

// Upload New Files
// -------------------------------------------
$lang["Upload Files"] = "Upload Files";
$lang["Select the <U>Browse</U> button next to each filename to locate your local file for upload. When ready to start the upload operation, select <U>Upload Files</U>."] = "Select the <U>Browse</U> button next to each filename to locate your local file for upload. When ready to start the upload operation, select <U>Upload Files</U>.";
$lang["Filename"] = "Filename";
$lang["Upload of files completed."] = "Upload of files completed.";
$lang["Current Site Files"] = "Current Site Files";
$lang["View Current Site Files"] = "View Current Site Files";
$lang["Upload Custom Template HTML"] = "Upload Custom Template HTML";
$lang["Upload More Files"] = "Upload More Files";
$lang["Success"] = "Success!";
$lang["Did not upload"] = "Did Not Upload";
$lang["File update completed."] = "File update completed.";
$lang["Filename already exists"] = "Filename already exists";
$lang["File is not an accepted file format"] = "File is not an accepted file format";
$lang["Below is a report of the files that were uploaded during this operation"] = "Below is a report of the files that were uploaded during this operation";
$lang["Upload Complete"] = "Upload Complete";
$lang["Open/Edit Page(s)"] = "Open/Edit Page(s)";

#################################################
## SITE TEMPLATE MODULE						   ##
#################################################

// Template Mangager
$lang["Base Site Template"] = "Base Site Template:";
$lang["The base site template"] = "The base site template will be applied by default to all pages<br> where a specific template is not specified via Page Properties.";
$lang["Browse Templates by Screenshot"] = "Browse Templates by Screenshot";
$lang["Save Changes"] = "Save Changes";
$lang["Custom Template Builder"] = "Custom Template Builder";
$lang["Upload Custom Template HTML file(s)"] = "Upload Custom Template HTML file(s)";
$lang["Upload Template File(s)"] = "Upload Template File(s)";
$lang["If you are utilizing a built-in template"] = "If you are utilizing a built-in template, you may edit the header information displayed in your template below.";
$lang["Built-In Template Header"] = "Built-In Template Header";
$lang["Enter your template header line"] = "Enter your template header line";
$lang["Save Header"] = "Save Header";
$lang["Save Settings"] = "Save Settings";
$lang["Company Slogan or Motto"] = "Company Slogan or Motto";


// Custom Template Builder
$lang["Template Builder"] = "Template Builder";
$lang["Template Name"] = "Template Name";
$lang["Template Image"] = "Template Image";
$lang["Preview Design"] = "Preview Design";
$lang["Save Design"] = "Save Design";
$lang["Image Preview Area"] = "Image Preview Area";
$lang["Image must be 204px Wide x 106px High"] = "Image must be 204px Wide x 106px High";
$lang["Template Style"] = "Template Style";
$lang["Blank"] = "Blank";
$lang["Left Bar"] = "Left Bar";
$lang["L-Shape"] = "L-Shape";
$lang["U-Shape"] = "U-Shape";
$lang["Pro"] = "Pro";
$lang["Foreground"] = "Foreground";
$lang["Background"] = "Background";
$lang["Title"] = "Title";
$lang["Text"] = "Text";
$lang["Links"] = "Links";

#################################################
## FORMS MANAGER MODULE						        ##
#################################################
$lang["Current Forms"] = "Current Forms";
$lang["Custom Forms"] = "Custom Forms";
$lang["New Form Creation Wizard"] = "New Form Creation Wizard";
$lang["To create a new form, enter the name"] = "To create a new form, enter a name and press Build New Form";
$lang["Build New Form"] = "Build New Form";
$lang["Preview"] = "Preview";
$lang["Add New Fields"] = "Add New Fields";
$lang["Add Fields"] = "Add Fields";
$lang["Edit Form"] = "Edit Form";
$lang["Delete Form"] = "Delete Form";
$lang["Form Name"] = "Form Name";
$lang["PREVIEW WINDOW"] = "PREVIEW WINDOW";
$lang["You must enter a form name that is at least 3 characters long."] = "You must enter a form name that is at least 3 characters long.";

//Form Builder Wizard

$lang["Building"] = "Building";
$lang["Form Field"] = "Form Field";
$lang["Field Label"] = "Field Label";
$lang["Required Field"] = "Required Field";
$lang["What is your site visitor supposed to enter or select for this field"] = "This text displays on the website.  What is your site visitor supposed to enter or select for this field";
$lang["Field Type"] = "Field Type";
$lang["Field Name"] = "Field Name";
$lang["Text Box"] = "Text Box";
$lang["Text Area (Multi-Line)"] = "Text Area (Multi-Line)";
$lang["Drop Down Box"] = "Drop Down Box";
$lang["Radio Buttons"] = "Radio Buttons";
$lang["Checkboxes"] = "Checkboxes";
$lang["What is the Name of this field"] = "The <u>Name</u> of this field is called when processing this form <br>in  email or database interaction.  Use <i>emailaddr</i> for email fields that you wish to mail to the website visitor.";
$lang["Field Values"] = "Field Values";
$lang["Enter selectable options separated by commas"] = "Enter selectable options separated by commas";
$lang["Drop Down Boxes, Radio Buttons, and Checkboxes Only"] = "Drop Down Boxes, Radio Buttons, and Checkboxes Only";
$lang["[Save] Finish Form"] = "[Save] Finish Form";
$lang["Add Next Field"] = "Add Next Field";

#################################################
## SITE STATISTICS MODULE					        ##
#################################################

// Main Stats Display

$lang["Unique Visitors"] = "Unique Visitors";
$lang["Top 25 Pages"] = "Top 25 Pages";
$lang["Views By Day"] = "Views By Day";
$lang["Views By Hour"] = "Views By Hour";
$lang["Referer Sites"] = "Referrer Sites";
$lang["Browser/OS"] = "Browser/OS";
$lang["You should empty your log tables at least every six months are so depending on traffic."] = "You should empty your log tables at least every six months are so depending on traffic.";
$lang["If you experience slowness<BR>in loading reports, your log tables have probably gone unattended for some time."] = "If you experience slowness<BR>in loading reports, your log tables have probably gone unattended for some time.";

// statistics/includes/unique.php

$lang["UNIQUE VISITOR TREND"] = "UNIQUE VISITOR TREND";
$lang["Total Unique Visitors"] = "Total Unique Visitors";
$lang["Total Page Views"] = "Total Page Views";
$lang["Visit Frequency"] = "Visit Frequency";
$lang["Avg Pages Per Visit"] = "Avg Pages Per Visit";

// statistics/includes/top25.php

$lang["TOP 25 SITE PAGES/SITE MODULES"] = "TOP 25 SITE PAGES/SITE MODULES";
$lang["Rank"] = "Rank";
$lang["Page Views"] = "Page Views";

// statistics/includes/byday.php

$lang["PAGE VIEWS BY DAY"] = "PAGE VIEWS BY DAY";
$lang["Total Page Views for"] = "Total Page Views for";
$lang["Page Views Per Day Totals"] = "Page Views Per Day Totals";
$lang["Mouseover a Selected day for actual total"] = "Mouseover a Selected day for actual total";

// statistics/includes/byhour.php

$lang["PAGE VIEWS BY HOUR"] = "PAGE VIEWS BY HOUR";
$lang["Most active hour of the day"] = "Most active hour of the day";
$lang["Mouseover a Selected Hour for actual total"] = "Mouseover a Selected Hour for actual total";

// statistics/includes/refer.php

$lang["REFERER SITES"] = "REFERER SITES";
$lang["Referals (per)"] = "Referrals (per)";
$lang["Referal Site"] = "Referral Site";

// statistics/includes/browser.php

$lang["BROWSER AND OPERATING SYSTEMS USED"] = "BROWSER AND OPERATING SYSTEMS USED";
$lang["Browser"] = "Browser";
$lang["Usage Data"] = "Usage Data";


#################################################
## PHOTO ALBUM MODULE					           ##
#################################################

// photo_album/photo_album.php
$lang["Photo Album"] = "Photo Album";
$lang["Create New Album"] = "Create New Album";
$lang["Enter Album Name"] = "Enter Album Name";
$lang["Current Photo Albums"] = "Current Photo Albums";
$lang["Select Album"] = "Select Album";

// photo_album/edit_album.php
$lang["Edit Album"] = "Edit Album";
$lang["Image Preview"] = "Image Preview";
$lang["Details"] = "Details";
$lang["Image"] = "Image";
$lang["Caption"] = "Caption";
$lang["Link"] = "Link";
$lang["Save Album"] = "Save Album";
$lang["Cancel Edit"] = "Cancel Edit";

#################################################
## SITE DATA TABLES MODULE					        ##
#################################################

// download_data.php

$lang["Manage/Backup Site Data Tables"] = "Manage/Backup Site Data Tables";
$lang["View"] = "View";
$lang["Download"] = "Download";
$lang["Import"] = "Import";
$lang["Empty"] = "Empty";
$lang["Database table"] = "Database table";
$lang["View All Data Tables"] = "View All Data Tables";
$lang["WARNING"] = "WARNING";
$lang["You have selected to clear the data from table"] = "You have selected to clear the data from table";
$lang["This process is irreversible and will delete all data contained in this table"] = "This process is irreversible and will delete all data contained in this table";
$lang["Are you sure you wish to continue"] = "Are you sure you wish to continue";
$lang["Continue"] = "Continue";
$lang["Cancel"] = "Cancel";
$lang["CSV Filenames"] = "CSV Filenames";
$lang["Select the CSV file that you wish to import"] = "Select the CSV file that you wish to import";
$lang["Please note that you can only upload comma or semi-colon delimited CSV files"] = "Please note that you can only upload comma or semi-colon delimited CSV files";
$lang["If you need to upload your csv file"] = "If you need to upload your csv file";
$lang["click here"] = "click here";
$lang["Use Default Value"] = "Use Default Value";
$lang["Select which fields in the CSV file to place into the existing table fields"] = "Select which fields in the CSV file to place into the existing table fields";
$lang["First record of CSV data contains field names. Do not import."] = "First record of CSV data contains field names. Do not import.";
$lang["Table Field Name"] = "Table Field Name";
$lang["CSV Field Name"] = "CSV Field Name";
$lang["Default Import Value"] = "Default Import Value";
$lang["If a field name from your csv file is matched to the PriKey field of the table"] = "If a field name from your csv file is matched to the PriKey field of the table, the csv file data will<BR>'Update' existing keys and 'Add' new records not matching an existing key value.";
$lang["Import Data Now"] = "Import Data Now";
$lang["IMPORT OF CSV DATA TO"] = "IMPORT OF CSV DATA TO";
$lang["COMPLETE!"] = "COMPLETE!";
$lang["Records imported successfully"] = "Records imported successfully";
$lang["Records were modified"] = "Records were modified";
$lang["View all Tables"] = "View all Tables";


#################################################
## BLOG MANAGER MODULE					           ##
#################################################

$lang["Blog Subjects"] = "Blog Subjects";
$lang["New Subject"] = "New Subject";
$lang["Add New"] = "Add New";
$lang["Existing Subjects"] = "Existing Subjects";
$lang["View"] = "View";
$lang["Create a new blog entry by entering your data in the text editor below"] = "Create a new blog entry by entering your data in the text editor below";
$lang["Then choose the subject that this blog should be assigned to and click Post Blog to continue"] = "Then choose the subject that this blog should be assigned to and click <i>Post Blog</i> to continue";
$lang["Blog Title"] = "Blog Title";
$lang["Please choose a subject to post this blog to"] = "Please choose a subject to post this blog to";
$lang["Please choose a title for this post"] = "Please choose a title for this post";
$lang["Post Blog to"] = "Post Blog to";
$lang["Choose Subject"] = "Choose Subject";
$lang["Post"] = "Post";
$lang["Update Complete"] = "Update Complete";
$lang["Can not delete this subject.  Blog data exists"] = "Can not delete this subject.  Blog data exists";
$lang["Latest News"] = "Latest News";
$lang["Special Promotions"] = "Special Promotions";

#################################################
## SHOPPING CART MODULE					           ##
#################################################

// shopping_cart.php
// --------------------------------------
$lang["Shopping Cart: Main Menu"] = "Shopping Cart: Main Menu";

// These three make up the sentence "You currently have [NUMBER] products in [NUMBER] categories"
$lang["You currently have"] = "You currently have";
$lang["products in"] = "products in";
$lang["categories"] = "categories";


$lang["Category Names"] = "Category Names";
$lang["Add New Products"] = "Add New Products";
$lang["Find/Edit Current Products"] = "Find/Edit Current Products";
$lang["Shipping Options"] = "Shipping Options";
$lang["Tax Rate Options"] = "Tax Rate Options";
$lang["Payment Options"] = "Payment Options";
$lang["Business Information"] = "Business Information";
$lang["Display Settings"] = "Display Settings";
$lang["Privacy Policy"] = "Privacy Policy";
$lang["Shipping Policy"] = "Shipping Policy";
$lang["Returns/Exchanges Policy"] = "Returns/Exchanges Policy";
$lang["Other Policies"] = "Other Policies";
$lang["View Online Orders/Invoices"] = "View Online Orders/Invoices";

// categories.php
// ---------------------------------
$lang["Shopping Cart: Category Setup"] = "Shopping Cart: Category Setup";
$lang["Current Categories"] = "Current Categories";
$lang["Add New Category"] = "Add New Category";
$lang["New Category Name"] = "New Category Name";
$lang["Add Category"] = "Add Category";
$lang["To delete a category"] = "To delete a category, click the [ delete ] link next to its name in the 'Current Categories' box to the left.";


// products.php
// ---------------------------------
$lang["Shopping Cart: Add New Product"] = "Shopping Cart: Add New Product";
$lang["No Image"] = "No Image";
$lang["SAVE PRODUCT"] = "SAVE PRODUCT";
$lang["PRODUCT INFO"] = "PRODUCT INFO";
$lang["PRODUCT IMAGES"] = "PRODUCT IMAGES";
$lang["PRICE VARIATION"] = "PRICE VARIATION";
$lang["ADVANCED OPTIONS"] = "ADVANCED OPTIONS";
$lang["Part No. (SKU Number):"] = "Part No. (SKU Number):";
$lang["Unit Price:"] = "Unit Price:";
$lang["Part Name (Title):"] = "Part Name (Title):";
$lang["Catalog Ref Number:"] = "Catalogue Ref Number:";
$lang["Description:"] = "Description:";
$lang["Main Category:"] = "Main Category:";
$lang["Shipping Charge (A):"] = "Shipping Charge (A):";
$lang["Secondary Category:"] = "Secondary Category:";
$lang["If you are using standard shipping"] = "If you are using standard shipping, then the 'Shipping Charge (A)' value should be the amount in US dollars that should be charged for purchase of this individual sku - per qty ordered.";
$lang["Shopping Cart: Edit Product"] = "Shopping Cart: Edit Product";
$lang["Search Products"] = "Search Products";

//Product Images
$lang["Select the thumbnail and full image that you wish to associate with this Sku Number."] = "Select the thumbnail and full image that you wish to associate with this Sku Number.";
$lang["If you are not using thumbnails, do not worry, the system will automatically resize your full size image to the appropriate scale when applicable. However, image quality of the scaled thumbnail may suffer."] = "If you are not using thumbnails, do not worry, the system will automatically resize your full size image to the appropriate scale when applicable. However, image quality of the scaled thumbnail may suffer.";
$lang["Thumbnail Image:"] = "Thumbnail Image:";
$lang["Full Size Image:"] = "Full Size Image:";
$lang["Note: Thumbnail images should be no more than 125px wide."] = "Note: Thumbnail images should be no more than 125px wide.";
$lang["Full Size Images should be no more than 275px wide for optimal display within your web site."] = "Full Size Images should be no more than 275px wide for optimal display within your web site.";
$lang["Image height is flexible."] = "Image height is flexible.";
$lang["Image Preview Window"] = "Image Preview Window";

//Price Variation
$lang["Sub-Category"] = "Sub-Category";
$lang["Variant"] = "Variant";
$lang["Show me what this looks like in operation and how the variant set-up works."] = "Show me what this looks like in operation and how the variant set-up works.";

//Advanced Options
$lang["Charge Tax for this product?"] = "Charge Tax for this product?";
$lang["Charge Shipping for this product?"] = "Charge Shipping for this product?";
$lang["Security Code:"] = "Security Code:";
$lang["Public"] = "Public";
$lang["Attachment Page (Detail Page):"] = "Attachment Page (Detail Page):";
$lang["Recommend this product"] = "Recommend this product during<BR>view/edit cart?";
$lang["Recommended Products like this one:"] = "Recommended Products like this one:";
$lang["Enter multiple sku numbers separated by comma"] = "Enter multiple sku numbers separated by comma";
$lang["When customers add this product to thier cart, require Form Data from:"] = "When customers add this product to their cart, require Form Data from:";
$lang["Per Qty"] = "Per Qty";
$lang["Ignore Qty"] = "Ignore Qty";
$lang["Purchase of this Sku allows your customer to download the following file:"] = "Purchase of this Sku allows your customer to download the following file:";
$lang["Display this Product"] = "Display this Product";
$lang["Inventory Count:"] = "Inventory Count:";
$lang["Additional Category Association:"] = "Additional Category Association:";
$lang["Special Tax Rate:"] = "Special Tax Rate:";
$lang["Searchable Keywords"] = "Searchable Keywords (Not displayed to site visitors; used for product keyword searches):";


// search_products.php
// ---------------------------------

$lang["Shopping Cart: Find/Edit Product"] = "Shopping Cart: Find/Edit Product";
$lang["Edit/Search For Products"] = "Edit/Search For Products";
$lang["Edit Sku"] = "Edit Sku";
$lang["Find"] = "Find";
$lang["Search For"] = "Search For";
$lang["Search Results"] = "Search Results";
$lang["Edit Product Data"] = "Edit Product Data";
$lang["Delete Product"] = "Delete Product";


// shipping_options.php
// ---------------------------------
$lang["Shopping Cart: Shipping Options"]  = "Shopping Cart: Shipping Options";
$lang["Choose the Shipping Option you wish to utilize for this shopping cart system:"] = "Choose the Shipping Option you wish to utilize for this shopping cart system:";
$lang["Standard Shipping (Per Sku)"] = "Standard Shipping (Per Sku)";
$lang["Charge By Order Sub-Total"] = "Charge By Order Sub-Total";
$lang["Use Custom PHP Include"] = "Use Custom PHP Include";
$lang["Offline/Manual Calculation"] = "Offline/Manual Calculation";
$lang["Save Shipping Options"] = "Save Shipping Options";
$lang["SET PRICING GRID, IF ORDER SUB-TOTAL IS..."] = "SET PRICING GRID, IF ORDER SUB-TOTAL IS...";
$lang["Greater Than"] = "Greater Than";
$lang["And Less Than"] = "And Less Than";
$lang["Shipping Price"] = "Shipping Price";

// tax_rates.php
// ---------------------------------

$lang["Shopping Cart: Tax Rate Options"] = "Shopping Cart: Tax Rate Options";
$lang["To Add a tax rate"] = "To Add a tax rate, select a state, province, and/or country and enter the\\npercentage tax rate of sales tax to charge for items\\nshipped to that state.\\n\\nTo Delete a tax rate, select a currently used\\nstate and leave the tax rate blank.";

//One sentence split into three parts
$lang["When visitors purchase items from your site"] = "When visitors purchase items from your site";
$lang["and select delivery to any of the below-listed areas,"] = "and select delivery to any of the below-listed areas,";
$lang["they will be charged the tax percentages you specified."] = "they will be charged the tax percentages you specified.";

$lang["United States"] = "United States";
$lang["Canada"] = "Canada";
$lang["Add/Delete Tax:"] = "Add/Delete Tax:";
$lang["Tax Rate"] = "Tax Rate";
$lang["Add/Delete Tax Rate"] = "Add/Delete Tax Rate";
$lang["State/Province"] = "State/Province";
$lang["There are currently no states in use."] = "There are currently no states in use.";
$lang["International Taxes"] = "International Taxes";
$lang["Note: You must enter a valid VAT/GST registration number to charge and collect VAT/GST taxes."] = "Note: You must enter a valid VAT/GST registration number to charge and collect VAT/GST taxes.";
$lang["Registration Number:"] = "Registration Number:";
$lang["Save Tax Options"] = "Save Tax Options";
$lang["Tax Rate Table Updated."] = "Tax Rate Table Updated.";
$lang["Country"] = "Country";
$lang["There are currently no countries in use."] = "There are currently no countries in use.";

// payment_options.php
// ---------------------------------

$lang["Shopping Cart: Payment Options"] = "Shopping Cart: Payment Options";
$lang["What type of payment processing will you utilize"] = "What type of payment processing will you utilize";
$lang["PayPal"] = "PayPal";
$lang["VeriSign"] = "VeriSign";
$lang["WorldPay"] = "WorldPay";
$lang["Live Credit Card Processing"] = "Live Card Processing";
$lang["None"] = "None";
$lang["Offline Credit Card"] = "Offline Credit Card";
$lang["Check / Money Order"] = "Cheque / Money Order";
$lang["If using credit card processing, select which cards you will accept:"] = "If using credit card processing, select which cards you will accept:";
$lang["Choose Currency Type and Symbol"] = "Choose Currency Type and Symbol";
$lang["Currency Type:"] = "Currency Type:";
$lang["Currency Symbol:"] = "Currency Symbol:";
$lang["Select Payment System (Online Processing)"] = "Select Payment System (Online Processing)";
$lang["If you are using online credit card processing"] = "If you are using online credit card processing, you may collect payment from customers through the following popular payment systems:";

$lang["WorldPay Payment System"] = "WorldPay Payment System";
$lang["How to configure WorldPay for use with your site"] = "How to configure WorldPay for use with your site";
$lang["Installation ID:"] = "Installation ID:";
$lang["Fix Currency Type"] = "Fix Currency Type";
$lang["Test Mode:"] = "Test Mode:";
$lang["PayPal Email:"] = "PayPal Email:";
$lang["How to configure VeriSign Payflow Link for use with your site"] = "How to configure VeriSign Payflow Link for use with your site";
$lang["VeriSign Partner ID:"] = "VeriSign Partner ID:";
$lang["VeriSign Login ID:"] = "VeriSign Login ID:";
$lang["Innovative Gateway Solutions"] = "Innovative Gateway Solutions";
$lang["Innovative Gateway"] = "Innovative Gateway";
$lang["Username"] = "Username";
$lang["Password"] = "Password";

$lang["I want to use online processing but I have a custom PHP include payment gateway"] = "I want to use online processing but I have a custom PHP include payment gateway";
$lang["system that I want to use in place of the others listed"] = "system that I want to use in place of the others listed";

$lang["This will over-ride all processing for credit cards."] = "This will over-ride all processing for credit cards. The system will simply pass control to this script and it will be the author's responsibility to tie back into the system after processing.";

$lang["I am using an SSL Certificate with my web site and when going to the checkout"] = "I am using an SSL Certificate with my web site and when going to the checkout";
$lang["the following https:// call should be made to the scripts"] = "the following https:// call should be made to the scripts";
$lang["to invoke the SSL Cert."] = "to invoke the SSL Cert.";

//Full Sentence = "For example if you must use https://secure.[domain.com] to activate your SSL certificate, type https://secure.[domain.com] in the field above. DO NOT ADD ANY TRAILING FORWARD SLASHES. If you are unsure, consult your web developer."
$lang["For example if you must use <U>https://secure."] = "For example if you must use <U>https://secure.";
$lang["</U> to activate your SSL certificate, type"] = "</U> to activate your SSL certificate, type";
$lang["<B>https://secure."] = "<B>https://secure.";
$lang["</B> in the field above. DO NOT ADD ANY TRAILING FORWARD SLASHES. If you are unsure, consult your web developer."] = ".com</B> in the field above. DO NOT ADD ANY TRAILING FORWARD SLASHES. If you are unsure, consult your web developer.";

$lang["When displaying the final invoice to my customer, I want to execute a custom PHP include"] = "When displaying the final invoice to my customer, I want to execute a custom PHP include";
$lang["that processes data when the invoice is displayed."] = "that processes data when the invoice is displayed.";


$lang["Custom Include File:"] = "Custom Include File:";
$lang["This include can be used to create custom processes that execute after products have been purchased from your system."] = "This include can be used to create custom processes that execute after products have been purchased from your system.";
$lang["For example, you may wish to assign a new user automatically with a generated username and password to the Secure Users table after a membership payment."] = "For example, you may wish to assign a new user automatically with a generated username and password to the Secure Users table after a membership payment.";
$lang["Save Payment Options"] = "Save Payment Options";


// business_information.php
// ---------------------------------

$lang["You will need to enter the address, phone number and whom to make a <U>cheque or money order</U>"] = "You will need to enter the address, phone number and whom to make a <U>cheque or money order</U>";
$lang["payable to for your online store.  This will display to your site visitors at checkout time."] = "payable to for your online store.  This will display to your site visitors at checkout time.";
$lang["Make Payable To:"] = "Make Payable To:";
$lang["Address:"] = "Address:";
$lang["City"] = "City";
$lang["State/Province:"] = "State/Province:";
$lang["Zip/Postal Code:"] = "Zip/Postal Code:";
$lang["Country:"] = "Country:";
$lang["Phone Number:"] = "Phone Number:";
$lang["Statistics have shown that displaying this information on your site will increase trust<BR>among shoppers and therefore produce better sales results."] = "Statistics have shown that displaying this information on your site will increase trust<BR>among shoppers and therefore produce better sales results.";

$lang["When orders are placed on your website, they are saved in your order/invoice area."] = "When orders are placed on your website, they are saved in your order/invoice area.";
$lang["The system will automatically send you an <U>email notifing you of new orders</U>.  Please "] = "The system will automatically send you an <U>email notifying you of new orders</U>.  Please ";
$lang["enter the email address where you wish these notifications to be sent. (Multiple email"] = "enter the email address where you wish these notifications to be sent. (Multiple email";
$lang["addresses can be entered separated by a comma)"] = "addresses can be entered separated by a comma)";

$lang["Notification Email Address:"] = "Notification Email Address:";
$lang["If you are using the \"Allow Product Comments\" option, when <U>users submit comments</U>"] = "If you are using the \"Allow Product Comments\" option, when <U>users submit comments</U>";
$lang["about your products, the comments will be saved and an email generated to the email"] = "about your products, the comments will be saved and an email generated to the email";
$lang["address below for verification. If the comments meet your approval, you can then allow"] = "address below for verification. If the comments meet your approval, you can then allow";
$lang["the comments to be made visible by the public.  This is done to prevent unsavory or"] = "the comments to be made visible by the public.  This is done to prevent unsavory or";
$lang["lude comments from being posted without your knowledge."] = "lude comments from being posted without your knowledge.";
$lang["Verification Email Address:"] = "Verification Email Address:";

$lang["After your customers purchase products from your site, they will receive an <U>email"] = "After your customers purchase products from your site, they will receive an <U>email";
$lang["invoice</U> of the order for their records. The default header text is a simple thank"] = "invoice</U> of the order for their records. The default header text is a simple thank";
$lang["you and is provided below.  You may modify this to say anything you wish.  The actual"] = "you and is provided below.  You may modify this to say anything you wish.  The actual";
$lang["invoice with pricing breakdowns, tax, shipping, etc. will appear below this header text."] = "invoice with pricing breakdowns, tax, shipping, etc. will appear below this header text.";

$lang["Save Business Info"] = "Save Business Info";


// display_settings.php
// ---------------------------------
$lang["Shopping Cart: Display Settings"] = "Shopping Cart: Display Settings";

$lang["Shopping Cart Feature Options"] = "Shopping Cart Feature Options";
$lang["Page Header:"] = "Page Header:";
$lang["Welcome To..."] = "Welcome To...";
$lang["Show 'Client Login' Button in search column"] = "Show 'Client Login' Button in search column";
$lang["Allow 'Email to Friend' feature"] = "Allow 'Email to Friend' feature";
$lang["Allow 'Remember Me' feature"] = "Allow 'Remember Me' feature";
$lang["Display Search Box"] = "Display Search Box";
$lang["Place 'Search Column' on which side of page"] = "Place 'Search Column' on which side of page";
$lang["Left"] = "Left";
$lang["Right"] = "Right";
$lang["Display 'text linked' categories"] = "Display 'text linked' categories";
$lang["Allow users to add product comments"] = "Allow users to add product comments";
$lang["If using this option, place an email address to verify submissions in the 'Business Information' section."] = "If using this option, place an email address to verify submissions in the 'Business Information' section.";
$lang["International Options:"] = "International Options:";
$lang["Choose State/Province Display Type:"] = "Choose State/Province Display Type:";
$lang["U.S. States"] = "U.S. States";
$lang["Canadian Provinces"] = "Canadian Provinces";
$lang["U.S. and Canada"] = "U.S. and Canada";
$lang["Text Field"] = "Text Field";
$lang["Do Not Display"] = "Do Not Display";
$lang["Specify Default 'Local' Countries:"] = "Specify Default 'Local' Countries:";

$lang["By specifying a defualt, or 'local' country, customers will not be able to choose a country"] = "By specifying a default, or 'local' country, customers will not be able to choose a country";
$lang["for their billing and shipping addresses. Instead, your shopping cart will assume that all customer orders are placed"] = "for their billing and shipping addresses. Instead, your shopping cart will assume that all customer orders are placed";
$lang["from the country you specify. To prevent confusion, you should make prominent mention of this on your website."] = "from the country you specify. To prevent confusion, you should make prominent mention of this on your website.";


$lang["Search Result Settings"] = "Search Result Settings";
$lang["User Defined Button:"] = "User Defined Button:";
$lang["This button links to the 'More Information' page.  Leaving this blank will not show the button at all."] = "This button links to the 'More Information' page.  Leaving this blank will not show the button at all.";
$lang["Show 'Add to Cart' button under thumbnail images instead of 'Buy Now!' on initial searches"] = "Show 'Add to Cart' button under thumbnail images instead of 'Buy Now!' on initial searches";

$lang["How should initial searches sort data"] = "How should initial searches sort data";
$lang["Sku Number"] = "Sku Number";
$lang["Catalog Ref Number"] = "Catalogue Ref Number";
$lang["Product Name"] = "Product Name";
$lang["Product Price"] = "Product Price";
$lang["Shipping Variable (B)"] = "Shipping Variable (B)";
$lang["Shipping Variable (C)"] = "Shipping Variable (C)";


$lang["Number of results to display on searches"] = "Number of results to display on searches";
$lang["Search Product"] = "Search Product";
$lang["Browse Categories"] = "Browse Categories";
$lang["Category"] = "Category";
$lang["Product"] = "Product";
$lang["Sub-Total"] = "Sub-Total";
$lang["Checkout Now"] = "Checkout Now";
$lang["Search Column Color Scheme"] = "Search Column Colour Scheme";
$lang["Header Background"] = "Header Background";
$lang["Header Text"] = "Header Text";
$lang["Shopping Cart Background"] = "Shopping Cart Background";
$lang["Shopping Cart Text"] = "Shopping Cart Text";
$lang["Or choose a pre-defined color scheme"] = "Or choose a pre-defined colour scheme";
$lang["Choose Scheme"] = "Choose Scheme";
$lang["America"] = "America";
$lang["Classic"] = "Classic";
$lang["Earth"] = "Earth";
$lang["Movies"] = "Movies";
$lang["Neon Green"] = "Neon Green";
$lang["Sports"] = "Sports";
$lang["Save Display Settings"] = "Save Display Settings";


// privacy_policy.php
// ---------------------------------
$lang["Shopping Cart: Privacy Policy"] = "Shopping Cart: Privacy Policy";

$lang["Standardized eCommerce systems use a privacy policy to disclose how systems operate. The one provided here is generic"] = "Standardized eCommerce systems use a privacy policy to disclose how systems operate. The one provided here is generic";
$lang["and covers all technical issues regarding the operation of this shopping cart system such as session management and cookies."] = "and covers all technical issues regarding the operation of this shopping cart system such as session management and cookies.";
$lang["You may wish to modify this policy statement to your particular business needs. It should disclose all information pertaining"] = "You may wish to modify this policy statement to your particular business needs. It should disclose all information pertaining";
$lang["to the use and storage of all data gathered from the checkout process."] = "to the use and storage of all data gathered from the checkout process.";

$lang["Save Privacy Statement"] = "Save Privacy Statement";


// shipping_policy.php
// ---------------------------------

$lang["Shopping Cart: Shipping Policy"] = "Shopping Cart: Shipping Policy";

$lang["Your shipping policy informs your customers of how and when you ship the items that they purchase."] = "Your shipping policy informs your customers of how and when you ship the items that they purchase.";
$lang["Be as detailed as possible here and note any special charges that may occur."] = "Be as detailed as possible here and note any special charges that may occur.";

$lang["Save Shipping Policy"] = "Save Shipping Policy";


// returns_policy.php
// ---------------------------------
$lang["Shopping Cart: Returns/Exchanges Policy"] = "Shopping Cart: Returns/Exchanges Policy";

$lang["If your customers wish to return of exchange an item purchased online"] = "If your customers wish to return of exchange an item purchased online, please detail the process<BR>they must adhere to. If all sales are final, say that here.";
$lang["Save Returns/Exchanges Policy"] = "Save Returns/Exchanges Policy";


// other_policies.php
// ---------------------------------
$lang["Shopping Cart: Other Policies"] = "Shopping Cart: Other Policies";

$lang["Use this section to list other types of policies that you may have for your site."] = "Use this section to list other types of policies that you may have for your site.";
$lang["Remember to title each policy as it will displayed as is."] = "Remember to title each policy as it will displayed as is.";
$lang["Save Policy Statement"] = "Save Policy Statement";


// view_orders.php
// ---------------------------------
$lang["View/Retrieve Orders"] = "View/Retrieve Orders";


$lang["Displaying order numbers"] = "Displaying order numbers";
$lang["Search results for"] = "Search results for";
$lang["Displaying all orders between"] = "Displaying all orders between";
$lang["Download Results"] = "Download Results";
$lang["Print Results"] = "Print Results";
$lang["New Search"] = "New Search";
$lang["Order Number"] = "Order Number";
$lang["Order Date"] = "Order Date";
$lang["Order Time"] = "Order Time";
$lang["Customer"] = "Customer";
$lang["Payment Method"] = "Payment Method";
$lang["Status"] = "Status";
$lang["Total Sale"] = "Total Sale";
$lang["Transaction ID"] = "Transaction ID";
$lang["Invoice"] = "Invoice";
$lang["No invoices where found matching your search. Please try again."] = "No invoices where found matching your search. Please try again.";


// search.inc
// ---------------------------------
$lang["Search Orders"] = "Search Orders";
$lang["Select your prefered search method"] = "Select your preferred search method";
$lang["Show order numbers"] = "Show order numbers";
$lang["From"] = "From";
$lang["To"] = "To";
$lang["Select how results should be sorted for viewing"] = "Select how results should be sorted for viewing";
$lang["Sort by"] = "Sort by";
$lang["Order Date"] = "Order Date";
$lang["Order Number"] = "Order Number";
$lang["Order by"] = "Order by";
$lang["Customer Name"] = "Customer Name";
$lang["Total Sale"] = "Total Sale";
$lang["Payment Method"] = "Payment Method";
$lang["Status"] = "Status";
$lang["Transaction ID"] = "Transaction ID";
$lang["Ascending"] = "Ascending";
$lang["Descending"] = "Descending";
$lang["Date range"] = "Date range";
$lang["Format"] = "Format";
$lang["Search for keywords"] = "Search for keywords";


// view_invoice.php
// ---------------------------------

$lang["PURGE"] = "PURGE";
$lang["PRINT"] = "PRINT";
$lang["EXIT"] = "EXIT";
$lang["Payment Method"] = "Payment Method";
$lang["Order Status"] = "Order Status";


#################################################
## EVENT CALENDAR    					           ##
#################################################

// event_calendar.php
// ---------------------------------

$lang["Event Calendar: Main Menu"] = "Event Calendar: Main Menu";
$lang["Search Events"] = "Search Events";
$lang["Display Settings"] = "Display Settings";
$lang["Category Setup"] = "Category Setup";
$lang["Edit View"] = "Edit View";

// add_event.php
// ---------------------------------
$lang["Add Calendar Event"] = "Add Calendar Event";

// build_month.php
// ---------------------------------

$lang["Add Event"] = "Add Event";

// category_setup.php
// ---------------------------------
$lang["Add/Modify Calendar Categories"] = "Add/Modify Calendar Categories";
$lang["Create New Category"] = "Create New Category";
$lang["Add Category"] = "Add Category";
$lang["Current Categories"] = "Current Categories";

// display_settings.php
// ---------------------------------
$lang["Calendar Display Settings"] = "Calendar Display Settings";

// search_events.php
// ---------------------------------
$lang["Search Event Calendar"] = "Search Event Calendar";

// "Found [X] events that match your search criteria."
$lang["Found"] = "Found";
$lang["events that match your search criteria"] = "events that match your search criteria";

$lang["Sorry, no events where found for your search. Please try again."] = "Sorry, no events where found for your search. Please try again.";


// add_events_form.php
// ---------------------------------

$lang["Apply To"] = "Apply To";
$lang["THIS EVENT ONLY"] = "THIS EVENT ONLY";
$lang["All occurrences of this event"] = "All occurrences of this event";
$lang["Save Event"] = "Save Event";
$lang["Event Date"] = "Event Date";
$lang["Start Time"] = "Start Time";
$lang["Event Title"] = "Event Title";
$lang["Event Details (Description)"] = "Event Details (Description)";
$lang["Event Category"] = "Event Category";
$lang["All"] = "All";
$lang["Security Code (Group)"] = "Security Code (Group)";
$lang["Public"] = "Public";
$lang["When saving or changing this event, email a notice to the following email addresses"] = "When saving or changing this event, email a notice to the following email addresses (separated by commas)";
$lang["Event Recurrence"] = "Event Recurrence";
$lang["No Recurrence"] = "No Recurrence";
$lang["Daily"] = "Daily";
$lang["Weekly"] = "Weekly";
$lang["Monthly"] = "Monthly";
$lang["Yearly"] = "Yearly";
$lang["Daily Pattern"] = "Daily Pattern";

//full sentence = "This event should re-occur every [number] days"
$lang["This event should re-occur every"] = "This event should re-occur every";
$lang["days"] = "days";

$lang["Weekly Pattern"] = "Weekly Pattern";

//full sentence = "This event should re-occur every [number] weeks on"
$lang["This event should re-occur every"] = "This event should re-occur every";
$lang["weeks on"] = "week(s) on";


$lang["Sunday"] = "Sunday";
$lang["Monday"] = "Monday";
$lang["Tuesday"] = "Tuesday";
$lang["Wednesday"] = "Wednesday";
$lang["Thursday"] = "Thursday";
$lang["Friday"] = "Friday";
$lang["Saturday"] = "Saturday";
$lang["Monthly Pattern"] = "Monthly Pattern";
$lang["This event should re-occur on the"] = "This event should re-occur on the";
$lang["of each month"] = "of each month";
$lang["Yearly Pattern"] = "Yearly Pattern";
$lang["You have selected for this event to occurr every year on"] = "You have selected for this event to occur every year on"; // "every year on [X month]"

$lang["This event will start on the date of the selected 'Event Date' and continue for how long"] = "This event will start on the date of the selected 'Event Date' and continue for how long"; //"?"
$lang["No End Date"] = "No End Date";

//"End after [X] occurences."
$lang["End after"] = "End after";
$lang["occurrences"] = "occurrences";


// calendar_settings_form.php
// ---------------------------------

$lang["Color Scheme"] = "Colour Scheme";
$lang["Header Text"] = "Header Text";
$lang["Select Text Color"] = "Select Text Colour";
$lang["Header Background"] = "Header Background";
$lang["Select Background Color"] = "Select Background Colour";
$lang["Pre-Defined Schemes"] = "Pre-Defined Schemes";
$lang["Color Schemes"] = "Colour Schemes";
$lang["Default Standard"] = "Default Standard";
$lang["Reds"] = "Reds";
$lang["Allow authorized users to maintain personal calendars"] = "Allow authorized users to maintain personal calendars"; // "?"

$lang["Initial Calendar Display Layout"] = "Initial Calendar Display Layout";
$lang["Monthly"] = "Monthly";
$lang["Weekly"] = "Weekly";
$lang["Allow the public to submit events for inclusion"] = "Allow the public to submit events for inclusion"; // "?"
$lang["If so, where should confirmations be emailed to"] = "If so, where should confirmations be emailed to"; // "?"
$lang["Color Preview"] = "Colour Preview";
$lang["Calendar Header"] = "Calendar Header";
$lang["Event Dates"] = "Event Dates";
$lang["Save Display Settings"] = "Save Display Settings";


// event_search_form.php
// ---------------------------------
$lang["Search Event Calendar"] = "Search Event Calendar";
$lang["Search for Keywords"] = "Search for Keywords";
$lang["Search in Month/Year"] = "Search in Month/Year";
$lang["Search In Category"] = "Search In Category";


// update_events_form.php
// ---------------------------------
$lang["Apply To"] = "Apply To";
$lang["THIS INDIVIDUAL EVENT ONLY"] = "THIS INDIVIDUAL EVENT ONLY";
$lang["ALL OCCURRENCES OF THIS EVENT"] = "ALL OCCURRENCES OF THIS EVENT";
$lang["Event Date"] = "Event Date";
$lang["Start Time"] = "Start Time";
$lang["End Time"] = "End Time";
$lang["Security Code (Group)"] = "Security Code (Group)";
$lang["Use commas to seperate multiple email addresses"] = "Use commas to separate multiple email addresses";
$lang["Event Recurrence"] = "Event Recurrence";
$lang["No Recurrence"] = "No Recurrence";
$lang["Daily Pattern"] = "Daily Pattern";

// "This event is a part of [X] other recursive events."
$lang["This event is a part of"] = "This event is a part of";
$lang["other recursive events"] = "other recursive events";

$lang["Master Event"] = "Master Event";
$lang["Recursive Event"] = "Recursive Event";

#################################################
## E-NEWSLETTER    					              ##
#################################################
// enewsletter.php
// ---------------------------------

$lang["eNewsletter System: Main Menu"] = "eNewsletter System: Main Menu";

// "You have selected to delete the campaign [X]. Do you wish to continue with this action?"
$lang["You have selected to delete the campaign"] = "You have selected to delete the campaign";
$lang["Do you wish to continue with this action"] = "Do you wish to continue with this action";

// "You have selected to send the campaign [X] to [X] people total. Do you wish to continue with this action?"
$lang["You have select to send the campaign"] = "You have select to send the campaign";
$lang["to"] = "to";
$lang["people total.  Do you wish to continue with this action"] = "people total.  Do you wish to continue with this action";

$lang["Your campaign has been sent"] = "Your campaign has been sent"; // "!"
$lang["SENDING CAMPAIGN"] = "SENDING CAMPAIGN";
$lang["This may take up to 30 seconds"] = "This may take up to 30 seconds";
$lang["Create New Campaign"] = "Create New Campaign";
$lang["HTML Emails"] = "HTML Emails";
$lang["TEXT Emails"] = "TEXT Emails";
$lang["Sent Date"] = "Sent Date";
$lang["Campaign Name"] = "Campaign Name";
$lang["Data Table"] = "Data Table";
$lang["Recipients"] = "Recipients";
$lang["Views"] = "Views";
$lang["Status"] = "Status";
$lang["View"] = "View";
$lang["Action"] = "Action";
$lang["Pending"] = "Pending";
$lang["SENT"] = "SENT";
$lang["View"] = "View";
$lang["Send Now"] = "Send Now";
$lang["Manually Unsubscribe Email Addresses"] = "Manually Unsubscribe Email Addresses";
$lang["Change to 'Send through hostname' only if you're getting an error when you try to send newsletters"] = "Change to 'Send through hostname' only if you're getting an error when you try to send newsletters";
$lang["SMTP Hostname"] = "SMTP Hostname";
$lang["SMTP Username"] = "SMTP Username";
$lang["SMTP Password"] = "SMTP Password";
$lang["SMTP Port"] = "SMTP Port";
$lang["Default"] = "Default";
$lang["Custom"] = "Custom";

// create_campaign.php
// ---------------------------------

$lang["eNewsletter Campaign Setup Wizard"] = "eNewsletter Campaign Setup Wizard";
$lang["Please select a table name to use for this campaign"] = "Please select a table name to use for this campaign";
$lang["Please enter a valid campaign name before continuing"] = "Please enter a valid campaign name before continuing";
$lang["You need to select a template and content file in order to preview"] = "You need to select a template and content file in order to preview";
$lang["You need to select a template and content file in order to continue"] = "You need to select a template and content file in order to continue";
$lang["This may take a few seconds"] = "This may take a few seconds";
$lang["STEP"] = "STEP";
$lang["ASSIGN CAMPAIGN NAME"] = "ASSIGN CAMPAIGN NAME";
$lang["A. Give this new campaign a name for easy identification on the campaign manager page"] = "A. Give this new campaign a name for easy identification on the campaign manager page";
$lang["B. Choose a database table that contains the email addresses for this campaign:"] = "B. Choose a database table that contains the email addresses for this campaign:";
$lang["Next"] = "Next";
$lang["Field Names"] = "Field Names";
$lang["MATCH REQUIRED FIELD DATA"] = "MATCH REQUIRED FIELD DATA";

// "In order to build this campaign using ["X" dB Table], you will need to tell..."
$lang["In order to build this campaign using"] = "In order to build this campaign using";
$lang["you will need to tell the system which fields in the table correspond to the data needed by the eNewsletter system when sending this campaign"] = "you will need to tell the system which fields in the table correspond to the data needed by the eNewsletter system when sending this campaign";


$lang["A. Field containing <U>FIRST NAME</U> data"] = "A. Field containing <U>FIRST NAME</U> data";
$lang["B. Field containing <U>EMAIL ADDRESS</U> data"] = "B. Field containing <U>EMAIL ADDRESS</U> data";
$lang["C. Field containing the <U>EMAIL TYPE</U> data"] = "C. Field containing the <U>EMAIL TYPE</U> data";
$lang["If the user has HTML or TEXT preference"] = "If the user has HTML or TEXT preference";
$lang["OWNER INFORMATION"] = "OWNER INFORMATION";
$lang["This campaign will arrive as an email to your list."] = "This campaign will arrive as an email to your list.";
$lang["Please indicate what email address it will<BR>come from and the subject line"] = "Please indicate what email address it will<BR>come from and the subject line"; // ":"

$lang["A. <U>From</U> email address"] = "A. <U>From</U> email address";
$lang["B. <U>Subject Line</U> of this campaign"] = "B. <U>Subject Line</U> of this campaign";
$lang["Next"] = "Next";
$lang["Newsletter Content Pages"] = "Newsletter Content Pages";
$lang["[NONE] Template Contains Content"] = "[NONE] Template Contains Content";
$lang["HTML CONTENT"] = "HTML CONTENT";
$lang["Please select the template file and page name which contains the enewsletter content for<BR>sending the HTML version of this campaign"] = "Please select the template file and page name which contains the enewsletter content for<BR>sending the HTML version of this campaign";
$lang["Select the template to use with this campaign"] = "Select the template to use with this campaign";
$lang["Browse Templates"] = "Browse Templates";
$lang["Select a page to use for your content"] = "Select a page to use for your content";

// "For those users that have selected to receive text only campaigns, please create the text that will..."
$lang["For those users that have selected to receive text only campaigns"] = "For those users that have selected to receive text only campaigns";
$lang["please create the text that will be sent to those users as well as embedded in the header of the HTML newsletter in case of errors"] = "please create the text that will be sent to those users as well as embedded in the header of the HTML newsletter in case of errors";

$lang["Creating the campaign does NOT send emails now."] = "Creating the campaign does NOT send emails now.";

$lang["Error: This campaign does not appear to have any email addresses to send to"] = "Error: This campaign does not appear to have any email addresses to send to";
$lang["HTML Types found"] = "HTML Types found";
$lang["TEXT Types found"] = "TEXT Types found";
$lang["DevString"] = "DevString";
$lang["DevString"] = "DevString";
$lang["HTML"] = "HTML";
$lang["TEXT"] = "TEXT";
$lang["Error Writing to Data Table (Could not create campaign): This is a programming error, consult with your webmaster."] = "Error Writing to Data Table (Could not create campaign): This is a programming error, consult with your webmaster.";
$lang["Campaign Created"] = "Campaign Created";
$lang["Campaign Manager"] = "Campaign Manager";

$lang["Your campaign has been added with pending status. You may now preview or"] = "Your campaign has been added with pending status. You may now preview or";
$lang["SEND your campaign from the \"Campaign Manager\" Interface."] = "SEND your campaign from the \"Campaign Manager\" Interface.";


// news-browse_templates.php
// ---------------------------------
$lang["Browse Website Templates"] = "Browse Website Templates";
$lang["Select a category to browse from the drop down box above. When your find a template you like, simply click the template to continue."] = "Select a category to browse from the drop down box above. When your find a template you like, simply click the template to continue.";


// preview.php
// ---------------------------------

$lang["View HTML Preview"] = "View HTML Preview";
$lang["View TEXT Preview"] = "View TEXT Preview";
$lang["Close Preview Window"] = "Close Preview Window";

// send_now.php
// ---------------------------------
$lang["If you do not wish to receive this email, unsubscribe to this service now."] = "If you do not wish to receive this email, unsubscribe to this service now.";

// view_setup.php
// ---------------------------------
$lang["Visit our Website"] = "Visit our Website";


#################################################
## DATABASE TABLE MANAGER   		              ##
#################################################
// database_tables.php
// ---------------------------------

$lang["Database Table Manager: Main Menu"] = "Database Table Manager: Main Menu";
$lang["Create New Data Table"] = "Create New Data Table";
$lang["Create a Search"] = "Create a Search";
$lang["Delete a Table"] = "Delete a Table";
$lang["Modify Selected Table"] = "Modify Selected Table";
$lang["Enter/Edit Record Data"] = "Enter/Edit Record Data";
$lang["Please select a user data table."] = "Please select a user data table.";
$lang["Batch Authenticate Users"] = "Batch Authenticate Users";


// auth_users.php
// ---------------------------------

$lang["Authenticate Users : Add Authorized Users via Data Table"] = "Authenticate Users : Add Authorized Users via Data Table";
$lang["You must select a field name for all red selection boxes."] = "You must select a field name for all red selection boxes.";
$lang["The second selection under 'user/company full name' is optional."] = "The second selection under 'user/company full name' is optional.";
$lang["This may take a few seconds..."] = "This may take a few seconds...";
$lang["CAN NOT AUTHENTICATE USERS VIA TABLE"] = "CAN NOT AUTHENTICATE USERS VIA TABLE";

$lang["This would indicate that you have not set-up a security code (group) OR"] = "This would indicate that you have not set-up a security code (group) OR";
$lang["you have not created at least (1) authorized user."] = "you have not created at least (1) authorized user.";

$lang["You will need to do these things before adding authenticated users via a table dump."] = "You will need to do these things before adding authenticated users via a table dump.";
$lang["Current UDT Tables..."] = "Current UDT Tables...";
$lang["SELECT DATA TABLE USAGE"] = "SELECT DATA TABLE USAGE";
$lang["Select the User Defined Table (UDT) that you wish to use as your authenticated user data:"] = "Select the User Defined Table (UDT) that you wish to use as your authenticated user data:";
$lang["Select Field Name"] = "Select Field Name";

// "CONFIGURE AUTHENTICATION DATA (AUTORIZE [X] USERS)."
$lang["CONFIGURE AUTHENTICATION DATA"] = "CONFIGURE AUTHENTICATION DATA";
$lang["AUTHORIZE"] = "AUTHORIZE";
$lang["USERS"] = "USERS";

// "For each field needed to register an authenticated user, match the field name in [TABLE NAME] to the<BR>required authenticated user fields."
$lang["For each field needed to register an authenticated user, match the field name in "] = "For each field needed to register an authenticated user, match the field name in ";
$lang["to the<BR>required authenticated user fields."] = "to the<BR>required authenticated user fields.";

$lang["Next"] = "Next";
$lang["New Authenticated Users Added"] = "New Authenticated Users Added";
$lang["Database Menu"] = "Database Menu";
$lang["You can view and/or edit individual user settings through<BR>the Secure Users feature."] = "You can view and/or edit individual user settings through<BR>the Secure Users feature.";


// create_table.php
// ---------------------------------
$lang["Table Manager: Create New Table"] = "Table Manager: Create New Table";
$lang["Error"] = "Error";
$lang["BACK TO TABLE BUILD"] = "BACK TO TABLE BUILD";

$lang["1. What is the name for this table"] = "1. What is the name for this table";
$lang["NOTE: Do not use numbers or spaces in names; these are invalid"] = "NOTE: Do not use numbers or spaces in names; these are invalid";
$lang["SQL table names. You may use underscores to represent spaces."] = "SQL table names. You may use underscores to represent spaces.";
$lang["Table Name"] = "Table Name";
$lang["Invalid Table Name"] = "Invalid Table Name";
$lang["2. How many fields will this table contain"] = "2. How many fields will this table contain"; //"?"

$lang["The data you have entered is not formated properly"] = "The data you have entered is not formatted properly";
$lang["in order to create your table. Please check your"] = "in order to create your table. Please check your";
$lang["setup and try again."] = "setup and try again.";
$lang["The last error calculation occurred on line item"] = "The last error calculation occurred on line item";

$lang["Create Table"] = "Create Table";
$lang["NOTE"] = "NOTE";
$lang["Do not use numbers or spaces in names; these are invalid SQL field names."] = "Do not use numbers or spaces in names; these are invalid SQL field names.";
$lang["You may use underscores(_) to represent spaces."] = "You may use underscores(_) to represent spaces.";
$lang["Novices who are unsure about what some of these options mean, simply input your field names leaving the default selection as is."] = "Novices who are unsure about what some of these options mean, simply input your field names leaving the default selection as is.";
$lang["This will insure proper operation."] = "This will insure proper operation.";
$lang["By default, a Primary Key field and Image field will also be added automatically to your table."] = "By default, a Primary Key field and Image field will also be added automatically to your table.";
$lang["Field Name"] = "Field Name";
$lang["Field Type"] = "Field Type";
$lang["Field Length"] = "Field Length";
$lang["Default Value"] = "Default Value";


// delete_table.php
// ---------------------------------

$lang["Table Manager: Delete Table"] = "Table Manager: Delete Table";
$lang["WARNING"] = "WARNING";

// "YOU ARE ABOUT TO DELETE THE TABLE [TABLE NAME] AND LOSE ALL RECORD DATA CONTAINED INSIDE OF IT."
$lang["YOU ARE ABOUT TO DELETE THE TABLE"] = "YOU ARE ABOUT TO DELETE THE TABLE";
$lang["AND LOSE ALL RECORD DATA"] = "AND LOSE ALL RECORD DATA";
$lang["CONTAINED INSIDE OF IT."] = "CONTAINED INSIDE OF IT.";
$lang["Are you sure you wish to do this now"] = "Are you sure you wish to do this now"; //"?"
$lang["You did not select a table to delete."] = "You did not select a table to delete.";
$lang["NOTE"] = "NOTE";
$lang["THIS PROCESS CAN NOT BE REVERSED ONCE COMPLETED."] = "THIS PROCESS CAN NOT BE REVERSED ONCE COMPLETED.";
$lang["ALL DATA WILL BE LOST WHEN THIS TABLE IS DELETED."] = "ALL DATA WILL BE LOST WHEN THIS TABLE IS DELETED.";
$lang["YOU WILL HAVE ONE CHANCE TO CONFIRM, BUT ONCE YOU 'OK' THE CONFIRMATION, THE TABLE WILL BE DELETED"] = "YOU WILL HAVE ONE CHANCE TO CONFIRM, BUT ONCE YOU 'OK' THE CONFIRMATION, THE TABLE WILL BE DELETED"; //"!"
$lang["Delete Table"] = "Delete Table";
$lang["Delete Selected Table"] = "Delete selected Table";
$lang["Cancel Delete"] = "Cancel Delete";

// enter_edit_data.php
// ---------------------------------
$lang["Table Manager: Enter/Edit Record Data"] = "Table Manager: Enter/Edit Record Data";
$lang["You have selected to delete this record."] = "You have selected to delete this record.";
$lang["You will not be able to undo this choice."] = "You will not be able to undo this choice.";
$lang["Do you wish to continue with this action"] = "Do you wish to continue with this action"; //"?"

$lang["Find Record"] = "Find Record";
$lang["ADD_NEW"] = "ADD_NEW";
$lang["Add New Record"] = "Add New Record";
$lang["Total Number of Records in Table"] = "Total Number of Records in Table";
$lang["Number of Records Found in Search"] = "Number of Records Found in Search";
$lang["OPTION"] = "OPTION";
$lang["Previous"] = "Previous";


// modify_table.php
// ---------------------------------

$lang["Table Manager: Modify Table"] = "Table Manager: Modify Table";
$lang["Modify Table"] = "Modify Table";
$lang["Update Complete"] = "Update Complete";
$lang["Field Name"] = "Field Name";
$lang["Field Type"] = "Field Type";
$lang["Field Length"] = "Field Length";
$lang["INT"] = "INT";
$lang["DATE"] = "DATE";
$lang["Update Table"] = "Update Table";
$lang["The data you have entered is not formated properly."] = "The data you have entered is not formatted properly.";
$lang["Please check your setup and try again."] = "Please check your setup and try again.";
$lang["Add New Field to"] = "Add New Field to"; // "[TABLE NAME]"
$lang["Field Name"] = "Field Name";
$lang["Field Type"] = "Field Type";
$lang["Field Length"] = "Field Length";
$lang["Default Value"] = "Default Value";
$lang["Rename Table"] = "Rename Table";


// wizard_start.php
// ---------------------------------
$lang["Data-Table Search Wizard"] = "Data-Table Search Wizard";
$lang["This may take a few seconds..."] = "This may take a few seconds...";
$lang["ASSIGN SEARCH NAME"] = "ASSIGN SEARCH NAME";
$lang["Give this search a name."] = "Give this search a name.";
$lang["This will be used as an identifier in the Page Editor, and displayed to site visitors when searching"] = "This will be used as an identifier in the Page Editor, and displayed to site visitors when searching";
$lang["SELECT DATA TABLE USAGE"] = "SELECT DATA TABLE USAGE";
$lang["Select the User Defined Table (UDT) that this search will utilize"] = "Select the User Defined Table (UDT) that this search will utilize";
$lang["Back"] = "Back";
$lang["CONFIGURE SEARCH FORM"] = "CONFIGURE SEARCH FORM";
$lang["Configure the search criteria by which site visitors will search"] = "Configure the search criteria by which site visitors will search";
$lang["NOTE: You will be able to preview the form in the next step and make changes if you wish."] = "NOTE: You will be able to preview the form in the next step and make changes if you wish.";
$lang["You will be able to preview the form in the next step and make changes if you wish"] = "You will be able to preview the form in the next step and make changes if you wish";
$lang["If you wish to utilize a keyword search, select which fields should be searched."] = "If you wish to utilize a keyword search, select which fields should be searched.";
$lang["DROP DOWN BOX SELECTION FIELDS"] = "DROP DOWN BOX SELECTION FIELDS";
$lang["Fields selected here will display all records within as options in a drop down box."] = "Fields selected here will display all records within as options in a drop down box.";
$lang["VERIFY SEARCH FORM"] = "VERIFY SEARCH FORM";
$lang["This is exactly the form site visitors will see when using this search."] = "This is exactly the form site visitors will see when using this search.";
$lang["Click the back button to make any changes."] = "Click the back button to make any changes.";
$lang["All Fields"] = "All Fields";
$lang["SEARCH"] = "SEARCH";
$lang["Search by Keyword"] = "Search by Keyword";
$lang["Separate multiple keywords by spaces"] = "Separate multiple keywords by spaces";
$lang["Detail Search"] = "Detail Search";
$lang["Define Search Method"] = "Define Search Method";
$lang["Keyword Only"] = "Keyword Only";
$lang["Selections Only"] = "Selections Only";
$lang["Keyword AND Selections"] = "Keyword AND Selections";
$lang["Keyword OR Selections"] = "Keyword OR Selections";
$lang["Search Now"] = "Search Now";
$lang["Back"] = "Back";
$lang["SEARCH RESULTS DISPLAY"] = "SEARCH RESULTS DISPLAY";
$lang["There are two steps used when displaying the results of a search."] = "There are two steps used when displaying the results of a search.";
$lang["The first data displayed is called the 'Initial Results', and displays the selected field data in a chart format."] = "The first data displayed is called the 'Initial Results', and displays the selected field data in a chart format.";
$lang["At that point, site visitors may select to <I>View Details</I>, which displays the 'Details Page'."] = "At that point, site visitors may select to <I>View Details</I>, which displays the 'Details Page'.";
$lang["This page shows more detailed information about the choosen record."] = "This page shows more detailed information about the chosen record.";
$lang["Select for each field when and where it's value should be displayed during the above process"] = "Select for each field when and where it's value should be displayed during the above process";
$lang["Field Name"] = "Field Name";
$lang["Display Setting"] = "Display Setting";
$lang["Don't Display"] = "Don't Display";
$lang["Initial Results"] = "Initial Results";
$lang["Details Page"] = "Details Page";
$lang["Display on Both"] = "Display on Both";
$lang["DETAIL VIEW SETUP AND SECURITY"] = "DETAIL VIEW SETUP AND SECURITY";
$lang["Select the display format (look and feel) of the 'Details Page'"] = "Select the display format (look and feel) of the 'Details Page'";
$lang["Standard (Default)"] = "Standard (Default)";
$lang["Custom PHP Include"] = "Custom PHP Include";
$lang["Select a security code (group) required to access this search"] = "Select a security code (group) required to access this search";
$lang["Public is Default"] = "Public is Default";
$lang["Build Search Now"] = "Build Search Now";
$lang["Search Creation Complete"] = "Search Creation Complete";
$lang["Database Menu"] = "Database Menu";
$lang["Use the 'Searchabe Database' object in the page editor to place your search on a site page."] = "Use the 'Searchable Database' object in the page editor to place your search on a site page.";


#################################################
## SECURE USERS MODULE     		              ##
#################################################
// security.php
// ---------------------------------
$lang["Page/Product Security"] = "Page/Product Security";
$lang["Authorized Users"] = "Authorized Users";
$lang["Create New User"] = "Create New User";
$lang["Current Authorized Users"] = "Current Authorized Users";
$lang["Select User"] = "Select User";
$lang["Security Codes"] = "Security Codes";
$lang["Create New Security Code (Group)"] = "Create New Security Code (Group)";
$lang["Name"] = "Name";
$lang["Create Group"] = "Create Group";
$lang["ACTION"] = "ACTION";
$lang["Current Security Codes (Groups)"] = "Current Security Codes (Groups)";
$lang["Select Code"] = "Select Code";
$lang["How does this module work"] = "How does this module work";
$lang["Click Here"] = "Click Here";


// security_create_user.php
// ---------------------------------

$lang["Create New Authorized User"] = "Create New Authorized User";
$lang["You have selected to delete this authorized user."] = "You have selected to delete this authorized user.";
$lang["THIS PROCESS CAN NOT BE REVERSED"] = "THIS PROCESS CAN NOT BE REVERSED";
$lang["Select OK to DELETE this user now."] = "Select OK to DELETE this user now.";
$lang["Save Changes"] = "Save Changes";
$lang["Delete User"] = "Delete User";
$lang["Authentication Info"] = "Authentication Info";
$lang["User Info"] = "User Info";


// shared/sec_user_form.inc
// ---------------------------------
$lang["User/Company Full Name"] = "User/Company Full Name";
$lang["User/Company Email Address"] = "User/Company Email Address";
$lang["Assigned Username"] = "Assigned Username";
$lang["Assigned Password"] = "Assigned Password";
$lang["Expiration Date"] = "Expiration Date";
$lang["Login Redirect Page"] = "Login Redirect Page";
$lang["(Module) Shopping Cart"] = "(Module) Shopping Cart";
$lang["What site page should this user be sent to upon login?"] = "What site page should this user be sent to upon login?";
$lang["Select the security codes (groups) this user should have access to"] = "Select the security codes (groups) this user should have access to";
$lang["There are currently no security codes (groups) created"] = "There are currently no security codes (groups) created"; //"!"

$lang["All authorized users must be associated with a security group."] = "All authorized users must be associated with a security group.";


// shared/sec_user_form.inc
// ---------------------------------
$lang["(Optional) If you wish for this user to be remembered automatically when using the<BR>shopping cart system, please fill out all the customer data below."] = "(Optional) If you wish for this user to be remembered automatically when using the<BR>shopping cart system, please fill out all the customer data below.";
$lang["Billing Information"] = "Billing Information";
$lang["First Name"] = "First Name";
$lang["Last Name"] = "Last Name";
$lang["Company Name"] = "Company Name";
$lang["Optional"] = "Optional";
$lang["Address"] = "Address";
$lang["No PO Boxes"] = "No PO Boxes";
$lang["City/Town/Locality"] = "City/Town/Locality";
$lang["Region or Province/State/District"] = "Region or Province/State/District";

$lang["Country"] = "Country";

$lang["Postal/Zip Code"] = "Postal/Zip Code";
$lang["Home Phone Number"] = "Home Phone Number";
$lang["Country Code"] = "Country Code";
$lang["Email Address"] = "Email Address";
$lang["INVALID EMAIL ADDRESS"] = "INVALID EMAIL ADDRESS";
$lang["Shipping Information"] = "Shipping Information";
$lang["First Name"] = "First Name";
$lang["Last Name"] = "Last Name";
$lang["Company Name"] = "Company Name";
$lang["Optional"] = "Optional";
$lang["Address"] = "Address";
$lang["No PO Boxes"] = "No PO Boxes";
$lang["City/Town/Locality"] = "City/Town/Locality";
$lang["Region or Province/State/District"] = "Region or Province/State/District";
$lang["State Invalid"] = "State Invalid";
$lang["Postal/Zip Code"] = "Postal/Zip Code";
$lang["Ship-To Phone Number"] = "Ship-To Phone Number";
$lang["Country Code"] = "Country Code";




#################################################
## CLIENT-SIDE DISPLAY ELEMENTS		           ##
#################################################

// object_write.php
// ---------------------------------
$lang["Get Directions"] = "Get Directions";
$lang["Courtesy of"] = "Courtesy of";
$lang["Email this page to a friend"] = "Email this page to a friend";
$lang["Sign-up Now"] = "Sign-up Now";
$lang["Search Products"] = "Search Products";
$lang["Browse Categories"] = "Browse Categories";


// pgm-realtime_builder.php
// ---------------------------------

$lang["This page has been emailed to your friend"] = "This page has been emailed to your friend"; //"!"
$lang["Thank you"] = "Thank you"; //"!"
$lang["Your message has been sent. Thank you."] = "Your message has been sent. Thank you.";


// pgm-blog_display.php
// ---------------------------------
$lang["Weblog Archives"] = "Weblog Archives";
$lang["Archives"] = "Archives";
$lang["January"] = "January";
$lang["February"] = "February";
$lang["March"] = "March";
$lang["April"] = "April";
$lang["May"] = "May";
$lang["June"] = "June";
$lang["July"] = "July";
$lang["August"] = "August";
$lang["September"] = "September";
$lang["October"] = "October";
$lang["November"] = "November";
$lang["December"] = "December";


// pgm-email_friend.php
// ---------------------------------
$lang["I found this web site that you might be interested in"] = "I found this web site that you might be interested in";
$lang["so I thought I'd email it to you..."] = "so I thought I'd email it to you...";
$lang["Just click on the link to see it!"] = "Just click on the link to see it!";
$lang["I found something you might want to see..."] = "I found something you might want to see...";
$lang["Email this page to a friend"] = "Email this page to a friend";
$lang["Your Name"] = "Your Name";
$lang["Your Email Address"] = "Your Email Address";
$lang["Friends Email Address"] = "Friends Email Address";
$lang["Personal Message"] = "Personal Message";
$lang["Send Now"] = "Send Now";


// pgm-form_submit.php
// ---------------------------------
$lang["The email address you entered is invalid or"] = "The email address you entered is invalid or";
$lang["You left a required field or fields blank."] = "You left a required field or fields blank.";
$lang["Please enter the following data before continuing"] = "Please enter the following data before continuing";
$lang["Auto Generated Form Email"] = "Auto Generated Form Email";
$lang["Email Address"] = "Email Address";

$lang["This message is auto-generated by your web site when the"] = "This message is auto-generated by your web site when the";
$lang["form is submitted by a site visitor on page"] = "form is submitted by a site visitor on page"; // "[Page Name]"
$lang["No need to reply"] = "No need to reply";

$lang["This data has been saved to the"] = "This data has been saved to the"; //"[Table Name]"
$lang["database table"] = "database table";

$lang["Your site visitor received the custom response file"] = "Your site visitor received the custom response file"; // "[File Name]"
$lang["Website Form Submission"] = "Website Form Submission"; // This is default subject line for form emails.
$lang["Thank you for your form submission today! This email is to confirm the reception"] = "Thank you for your form submission today! This email is to confirm the reception";
$lang["of your recently submitted data."] = "of your recently submitted data.";
$lang["We received the following:"] = "We received the following:";
$lang["Thank You"] = "Thank You";
$lang["This message is auto-generated by our web site."] = "This message is auto-generated by our web site.";
$lang["Please do not reply to this email."] = "Please do not reply to this email.";


// pgm-numusers.php
// ---------------------------------
$lang["Visitors Currently Online"] = "Visitors Currently Online";


// pgm-print_page.php
// ---------------------------------
$lang["THIS PAGE IS CURRENTLY UNDER CONSTRUCTION"] = "THIS PAGE IS CURRENTLY UNDER CONSTRUCTION";
$lang["This Week in"] = "This Week in"; // "[Month]"
$lang["Page Visits"] = "Page Visits"; // ": [#]"
$lang["More Info"] = "More Info";


// pgm-single_sku.php
// ---------------------------------
$lang["More Information"] = "More Information";


// pgm-cal-confirm.php
// ---------------------------------
$lang["This event has been added to your calendar system."] = "This event has been added to your calendar system.";
$lang["It appears this event has already been added to your system."] = "It appears this event has already been added to your system.";

// pgm-cal-details.inc.php
// ---------------------------------
$lang["Print Details"] = "Print Details";
$lang["Close Window"] = "Close Window";
$lang["Event Date"] = "Event Date";
$lang["Event Time"] = "Event Time";
$lang["Event Details"] = "Event Details";
$lang["More Details"] = "More Details";


// pgm-cal-submitevent.inc.php
// ---------------------------------
$lang["Private"] = "Private";
$lang["Submit an Event"] = "Submit an Event";
$lang["Your Name"] = "Your Name";
$lang["Your Email Address"] = "Your Email Address";
$lang["Event Date"] = "Event Date";
$lang["Event Category"] = "Event Category";
$lang["Start Time"] = "Start Time";
$lang["Event Title"] = "Event Title";
$lang["Event Details"] = "Event Details";
$lang["Submit Event"] = "Submit Event";
$lang["All fields are required to submit an event except Event End Time and Event Details."] = "All fields are required to submit an event except Event End Time and Event Details.";


// pgm-cal-system.php
// ---------------------------------

$lang["Please Setup Calendar System Display Settings."] = "Please Setup Calendar System Display Settings.";
$lang["Private"] = "Private";
$lang["Your selected event has been deleted."] = "Your selected event has been deleted.";
$lang["You did not enter one or more required fields. Please modify your submission and try again."] = "You did not enter one or more required fields. Please modify your submission and try again.";
$lang["Event Added to your Calendar"] = "Event Added to your Calendar";
$lang["The following event was submitted to your calendar. To approve this event, click the approve link below."] = "The following event was submitted to your calendar. To approve this event, click the approve link below.";
$lang["If you do not wish to add this event to your calendar, simply disregard this email."] = "If you do not wish to add this event to your calendar, simply disregard this email.";
$lang["Event Date"] = "Event Date";
$lang["Event Category"] = "Event Category";
$lang["Event Title"] = "Event Title";
$lang["Start Time"] = "Start Time";
$lang["End Time"] = "End Time";
$lang["Event Details"] = "Event Details";
$lang["To approve, click the link below:"] = "To approve, click the link below:";
$lang["THIS IS AN AUTO-GENERATED EMAIL FROM YOUR WEBSITE"] = "THIS IS AN AUTO-GENERATED EMAIL FROM YOUR WEBSITE";
$lang["Your submission has been sent to our calendar manager for approval."] = "Your submission has been sent to our calendar manager for approval.";
$lang["Thank you"] = "Thank you";
$lang["Current View"] = "Current View";
$lang["View"] = "View";
$lang["Submit an Event"] = "Submit an Event";
$lang["Detail Event Search"] = "Detail Event Search";
$lang["Month"] = "Month";
$lang["Current Category"] = "Current Category";
$lang["In Category"] = "In Category";
$lang["Search Now"] = "Search Now";
$lang["Submit a search to change categories."] = "Submit a search to change categories.";
$lang["Events for the Week of"] = "Events for the Week of"; // "[Month DD-DD]"

$lang["Events for"] = "Events for"; // "[month]"
$lang["that match your search for"] = "that match your search for"; // [Search Query]


$lang["your personal calendar"] = "your personal calendar"; // [User's Name]
$lang["the category"] = "the category"; // [category selection]
$lang["located in"] = "located in"; // located in [category]/"your personal calendar"

$lang["Edit Event"] = "Edit Event";
$lang["Delete Event"] = "Delete Event";
$lang["This is your private event."] = "This is your private event.";
$lang["No details available for this event."] = "No details available for this event.";
$lang["in category"] = "in category";
$lang["There where no events found for your selection or search"] = "There where no events found for your selection or search";
$lang["Please search for an event or select the day or week you wish to view."] = "Please search for an event or select the day or week you wish to view.";
$lang["Authorized user logged in"] = "Authorized user logged in";
$lang["Indicates your private event"] = "Indicates your private event";
$lang["No one else can view this event but"] = "No one else can view this event but"; //[user's name]


// newsletter/index.php
// ---------------------------------
$lang["Please enter the email address where you wish NOT to receive future emails"] = "Please enter the email address where you wish NOT to receive future emails";
$lang["Unsubscribe Now"] = "Unsubscribe Now";

$lang["UNSUBSCRIBE FROM"] = "UNSUBSCRIBE FROM"; // [url]
$lang["EMAIL SERVICE"] = "EMAIL SERVICE";

$lang["The email address"] = "The email address"; // [unsubscribed address]
$lang["is no longer subscribed to our services."] = "is no longer subscribed to our services.";

$lang["If you need to remove another email address from our subscription system"] = "If you need to remove another email address from our subscription system";
$lang["click here"] = "click here";

$lang["Visit"] = "Visit"; // [url]
$lang["now"] = "now";


// pgm-photo_album.php
// ---------------------------------
$lang["Available Album(s)"] = "Available Album(s)";

$lang["Current Album is"] = "Current Album is";
$lang["Change Album"] = "Change Album";

$lang["To change albums, highlight your"] = "To change albums, highlight your"; // <br>
$lang["choice and click the 'Change Album' button."] = "choice and click the 'Change Album' button.";

$lang["Prev"] = "Prev";
$lang["Next"] = "Next";
$lang["There are currently no images in this album."] = "There are currently no images in this album.";



// pgm-secure_login.php
// ---------------------------------

$lang["The page you have requested requires security access."] = "The page you have requested requires security access.";
$lang["Please enter your username and password now."] = "Please enter your username and password now.";
$lang["It appears your login does not grant you access to this page."] = "It appears your login does not grant you access to this page.";
$lang["If you feel this is in error, please contact us for further assistance."] = "If you feel this is in error, please contact us for further assistance.";

$lang["Click here"] = "Click here";
$lang["to return to the home page."] = "to return to the home page.";

$lang["Please Login"] = "Please Login";
$lang["Username"] = "Username";
$lang["Password"] = "Password";
$lang["Sorry, we do not recognize that username and password.<BR>Please check your spelling and try again."] = "Sorry, we do not recognize that username and password.<BR>Please check your spelling and try again.";
$lang["It appears the username and password that you entered has expired."] = "It appears the username and password that you entered has expired.";
$lang["Your access is no longer available."] = "Your access is no longer available.";
$lang["Click here"] = "Click here";
$lang["to return to the home page."] = "to return to the home page.";
$lang["Forget your password"] = "Forget your password";


// pgm-secure_manage.php
// ---------------------------------
$lang["Your login password does not match"] = "Your login password does not match";
$lang["your verification password. Please re-enter."] = "your verification password. Please re-enter.";
$lang["One or more fields were left blank or are too short."] = "One or more fields were left blank or are too short.";
$lang["All fields must have at least 5 characters."] = "All fields must have at least 5 characters.";
$lang["Your authentication data has been updated"] = "Your authentication data has been updated"; // "!"
$lang["Manage Authenticated User Account"] = "Manage Authenticated User Account";
$lang["Your Email Address"] = "Your Email Address";
$lang["Login Username"] = "Login Username";
$lang["Login Password"] = "Login Password";
$lang["Verify Password"] = "Verify Password";
$lang["Update Your Data"] = "Update Your Data";


// pgm-secure_remember.php
// ---------------------------------
$lang["Here is the username and password associated with your email address"] = "Here is the username and password associated with your email address";
$lang["Username"] = "Username";
$lang["Password"] = "Password";
$lang["This is an automated email from"] = "This is an automated email from"; // [server name]
$lang["Please DO NOT REPLY to this email."] = "Please DO NOT REPLY to this email.";
$lang["Customer data successfully located."] = "Customer data successfully located.";
$lang["You should receive an email within the next few minutes."] = "You should receive an email within the next few minutes.";
$lang["Failed to locate email address; please try again."] = "Failed to locate email address; please try again.";
$lang["Forgotten Login"] = "Forgotten Login";
$lang["Please <u>enter your email address</u> in the space below."] = "Please <u>enter your email address</u> in the space below.";
$lang["We will locate your username and password in our database and instantly send an email to"] = "We will locate your username and password in our database and instantly send an email to";
$lang["the address that matches your input."] = "the address that matches your input.";
$lang["Find Now"] = "Find Now";


// pgm-add_cart.php
// ---------------------------------
$lang["Please fill out the following information needed for this individual item"] = "Please fill out the following information needed for this individual item";
$lang["Item"] = "Item";
$lang["Details"] = "Details";
$lang["Details"] = "Details";
$lang["Please fill out the following information regarding this product"] = "Please fill out the following information regarding this product";
$lang["Continue"] = "Continue";
$lang["ILLEGAL PRODUCT ADDITION DETECTED."] = "ILLEGAL PRODUCT ADDITION DETECTED.";
$lang["UPDATED"] = "UPDATED";
$lang["Current Shopping Cart Contents"] = "Current Shopping Cart Contents";
$lang["Shipping Information"] = "Shipping Information";
$lang["Returns & Exchanges"] = "Returns & Exchanges";
$lang["Privacy Policy"] = "Privacy Policy";
$lang["Other Policies"] = "Other Policies";

$lang["Sub-total does not include tax"] = "Sub-total does not include tax"; // <br>
$lang["and shipping charges, if applicable."] = "and shipping charges, if applicable.";


$lang["Sub-Total"] = "Sub-Total";
$lang["Your shopping cart is currently empty."] = "Your shopping cart is currently empty.";
$lang["We also recommend the following product(s)"] = "We also recommend the following product(s)";


// pgm-checkout.php
// ---------------------------------
$lang["Customer Sign-in"] = "Customer Sign-in";
$lang["Email"] = "Email";

$lang["Billing & Shipping"] = "Billing & Shipping"; // <br>
$lang["Information"] = "Information";

$lang["Shipping Options"] = "Shipping Options";
$lang["Verify Order Details"] = "Verify Order Details";
$lang["Make Payment"] = "Make Payment";

$lang["Print Final"] = "Print Final"; // <br>
$lang["Invoice"] = "Invoice";


$lang["CUSTOMER SIGN-IN"] = "CUSTOMER SIGN-IN";
$lang["Select an option below so that we can recognize you."] = "Select an option below so that we can recognize you.";
$lang["Shipping Information"] = "Shipping Information";
$lang["Returns & Exchanges"] = "Returns & Exchanges";
$lang["Privacy Policy"] = "Privacy Policy";
$lang["Other Policies"] = "Other Policies";
$lang["New Customer"] = "New Customer";
$lang["If you are a first time buyer select this option."] = "If you are a first time buyer select this option.";
$lang["You will have the opportunity to register and become a prefered customer."] = "You will have the opportunity to register and become a preferred customer.";
$lang["New Customer"] = "New Customer";
$lang["Existing Customers, Login Now"] = "Existing Customers, Login Now";
$lang["Username"] = "Username";
$lang["Unrecognized Customer"] = "Unrecognised Customer";
$lang["Try Again"] = "Try Again";
$lang["Verify Order"] = "Verify Order"; //<br>
$lang["Details"] = "Details";
$lang["STEP"] = "STEP";
$lang["BILLING AND SHIPPING INFORMATION"] = "BILLING AND SHIPPING INFORMATION";
$lang["Please fill out all fields"] = "Please fill out all fields";
$lang["You will have a chance to verify and correct this information if necessary."] = "You will have a chance to verify and correct this information if necessary.";
$lang["Customer Sign-in"] = "Customer Sign-in";
$lang["Please double check that all information is correct."] = "Please double check that all information is correct.";
$lang["SELECT YOUR METHOD OF PAYMENT"] = "SELECT YOUR METHOD OF PAYMENT";
$lang["Choose your method of payment by clicking on the desired button."] = "Choose your method of payment by clicking on the desired button.";
$lang["Currently we are only accepting Check or Money Order payments."] = "Currently we are only accepting Cheques or Money Order payments.";
$lang["We currently accept the following credit cards"] = "We currently accept the following credit cards";
$lang["Mailing Address"] = "Mailing Address";


// pgm-checkout.php
// ---------------------------------
$lang["Thanks"] = "Thanks"; // [user name]!
$lang["Your email has been sent"] = "Your email has been sent";
$lang["A cool product I found..."] = "A cool product I found..."; // Default subject line of 'email product to friend' feature
$lang["Email Product"] = "Email Product";
$lang["You have left one or more required fields blank"] = "You have left one or more required fields blank";
$lang["Please correct and re-submit your email"] = "Please correct and re-submit your email";
$lang["Required Fields"] = "Required Fields";
$lang["Your <u>Full</u> Name"] = "Your <u>Full</u> Name";
$lang["Your Email Address"] = "Your Email Address";
$lang["Friend's <u>First</u> Name"] = "Friend's <u>First</u> Name";
$lang["Friend's Email Address"] = "Friend's Email Address";
$lang["Subject Line of Email"] = "Subject Line of Email";
$lang["Personal Message"] = "Personal Message";
$lang["Email Type"] = "Email Type";
$lang["Yes, send me a copy of the email too."] = "Yes, send me a copy of the email too.";
$lang["Click Here to Return to"] = "Click Here to Return to"; //[product name]
$lang["Return To Checkout Login"] = "Return To Checkout Login";
$lang["Failed to locate email address; please try again or login as a new customer."] = "Failed to locate email address; please try again or login as a new customer.";
$lang["Follow the instructions below to resolve your issue quickly."] = "Follow the instructions below to resolve your issue quickly.";
$lang["Find Username and Password for Login"] = "Find Username and Password for Login";
$lang["Your username and password was displayed on the invoice of your first order with us."] = "Your username and password was displayed on the invoice of your first order with us.";
$lang["If you have the email or a printed copy handy, it may expedite your request."] = "If you have the email or a printed copy handy, it may expedite your request.";
$lang["Otherwise, please enter your email address in the space below."] = "Otherwise, please enter your email address in the space below.";
$lang["Thank you for being a valued return customer."] = "Thank you for being a valued return customer.";
$lang["Find Now"] = "Find Now";

$lang["We have received your request for a lost username and"] = "We have received your request for a lost username and";
$lang["password and have located that information in our system."] = "password and have located that information in our system.";
$lang["They are as follows"] = "They are as follows";
$lang["Thank you for being a loyal prefered customer."] = "Thank you for being a loyal preferred customer.";
$lang["We look forward to continuing to serve you in the future."] = "We look forward to continuing to serve you in the future.";
$lang["This is an automated email from"] = "This is an automated email from";
$lang["Please DO NOT REPLY to this email."] = "Please DO NOT REPLY to this email.";

$lang['Check/Money Order'] = 'Cheque/Money Order';
$lang['Mail your check/money order payment to us directly'] = 'Mail your cheque/money order payment to us directly';


// pgm-more_information.php
// ---------------------------------
$lang["Email To A Friend"] = "Email To A Friend";

$lang["Add this product to your cart below"] = "Add this product to your cart below";
$lang["under 'ordering options'."] = "under 'ordering options'.";

$lang["Product"] = "Product";
$lang["Price"] = "Price";
$lang["Qty"] = "Qty";
$lang["Add To Cart"] = "Add To Cart";
$lang["Details specific to this item will be asked when you add this product to your cart."] = "Details specific to this item will be asked when you add this product to your cart.";
$lang["More Information"] = "More Information";
$lang["Zoom"] = "Zoom";
$lang["Customer Comments"] = "Customer Comments";

$lang["Be the first to"] = "Be the first to";
$lang["write a review"] = "write a review";
$lang["of this product for other customers"] = "of this product for other customers";

$lang["Write an online review"] = "Write an online review";
$lang["and share your thoughts about this product with other customers."] = "and share your thoughts about this product with other customers.";
$lang["If you like this, you may also like"] = "If you like this, you may also like";


// pgm-ok_comment.php
// ---------------------------------
$lang["This comment has already been added to the system or no longer exists."] = "This comment has already been added to the system or no longer exists.";
$lang["CUSTOMER COMMENT ADDED"] = "CUSTOMER COMMENT ADDED";


// pgm-payment_gateway.php
// ---------------------------------
$lang["Customer Registration"] = "Customer Registration";

$lang["Thanks"] = "Thanks";
$lang["you are now registered as a prefered customer"] = "you are now registered as a preferred customer";

$lang["The next time you shop with us, you may login using your username and password for quicker checkout"] = "The next time you shop with us, you may login using your username and password for quicker checkout";
$lang["An error occurred when assigning your invoice number."] = "An error occurred when assigning your invoice number.";
$lang["Please try again or contact the webmaster immediately."] = "Please try again or contact the webmaster immediately.";

$lang["The checkout system is configured to use a custom gateway include script named"] = "The checkout system is configured to use a custom gateway include script named"; //[filename]
$lang["but the file can not be found on the server."] = "but the file can not be found on the server.";

$lang["Via 'Payment Options' in the system admin, make sure that you have a current include file selected and try again."] = "Via 'Payment Options' in the system admin, make sure that you have a current include file selected and try again.";
$lang["Connecting To VeriSign"] = "Connecting To VeriSign";
$lang["Secure Server"] = "Secure Server";
$lang["Please Hold"] = "Please Hold";
$lang["If you are not connected automatically within 20 seconds"] = "If you are not connected automatically within 20 seconds";
$lang["Click Here"] = "Click Here";
$lang["Connecting To PayPal"] = "Connecting To PayPal";
$lang["Secure Payment Server"] = "Secure Payment Server";

$lang["The checkout system is configured to utilize online credit card processing, however, there is no VeriSign"] = "The checkout system is configured to utilize online credit card processing, however, there is no VeriSign";
$lang["information setup nor is there a"] = "information setup nor is there a";
$lang["custom gateway specified.  One of the other must be setup through 'Payment Options' to use the online credit card checkout system."] = "custom gateway specified.  One of the other must be setup through 'Payment Options' to use the online credit card checkout system.";
$lang["If you do not know what these things mean, login to the admin system, select 'Payment Options' in the Shopping Cart module"] = "If you do not know what these things mean, login to the admin system, select 'Payment Options' in the Shopping Cart module";
$lang["and select 'Offline Processing' then save your settings."] = "and select 'Offline Processing' then save your settings.";
$lang["This should resolve your issue immediately."] = "This should resolve your issue immediately.";

// pgm-show_invoice.php
// -----------------------------------
$lang["Make Check/Money Order Payable to"] = "Make Cheque/Money Order Payable to";
$lang["Order Date"] = "Order Date";
$lang["Order Number"] = "Order Number";
$lang["Mailing Address"] = "Postal Address";
$lang["Print this Page Now"] = "Print this Page Now";
$lang["To download and save the file to your hard-drive, 'Right-Click' on Download Button and select 'Save Target As...'."] = "To download and save the file to your hard-drive, 'Right-Click' on Download Button and select 'Save Target As...'.";

$lang["When the save dialog appears, make sure you"] = "When the save dialogue appears, make sure you";
$lang["remember where you save the file on your hard drive."] = "remember where you save the file on your hard drive.";

$lang["You will also receive an HTML email receipt of this invoice that contains this link as well in case"] = "You will also receive an HTML email receipt of this invoice that contains this link as well in case";
$lang["you encounter connection problems downloading the file now."] = "you encounter connection problems downloading the file now.";
$lang["This order was just placed from your website."] = "This order was just placed from your website.";
$lang["If you need to retrieve the credit card information, please login and do so now."] = "If you need to retrieve the credit card information, please login and do so now.";
$lang["CUSTOMER INVOICE COPY"] = "CUSTOMER INVOICE COPY";


// pgm-write_review.php
// ---------------------------------
$lang["CLICK HERE"] = "CLICK HERE";
$lang["TO MAKE THIS POST LIVE."] = "TO MAKE THIS POST LIVE.";
$lang["If you do not want to display this comment, simply delete this email"] = "If you do not want to display this comment, simply delete this email";

$lang["A customer has submitted the following comments about"] = "A customer has submitted the following comments about"; // <br>
$lang["the product"] = "the product";


$lang["Your comment has been submitted."] = "Your comment has been submitted.";
$lang["Click Here to Return to"] = "Click Here to Return to"; // [product name]

$lang["You have left one or more fields blank."] = "You have left one or more fields blank.";
$lang["Please correct and re-submit your review."] = "Please correct and re-submit your review.";
$lang["Star"] = "Star";
$lang["Stars"] = "Stars";
$lang["Rate this Product"] = "Rate this Product";
$lang["On a scale of 1-5, with 5 being the best"] = "On a scale of 1-5, with 5 being the best";
$lang["Comment Title"] = "Comment Title";
$lang["Your Review/Comments"] = "Your Review/Comments";
$lang["Your Name"] = "Your Name";
$lang["Where are you in the world"] = "Where are you in the world";
$lang["our review will be submitted to our staff and should be posted within 2-3 business days."] = "our review will be submitted to our staff and should be posted within 2-3 business days.";
$lang["Thank you"] = "Thank you";


// prod-billing_shipping.inc
// ---------------------------------

$lang["The state you selected to ship your order to does not appear to be valid."] = "The state you selected to ship your order to does not appear to be valid.";
$lang["Please correct and re-submit your information."] = "Please correct and re-submit your information.";
$lang["The email address you provided is not a valid email address."] = "The email address you provided is not a valid email address.";
$lang["Please correct and re-submit your information."] = "Please correct and re-submit your information.";
$lang["Customer Registration"] = "Customer Registration";
$lang["Yes, I want you to remember my Billing &amp; Shipping Information the next time I purchase something."] = "Yes, I want you to remember my Billing &amp; Shipping Information the next time I purchase something.";
$lang["Choose a password"] = "Choose a password";
$lang["Verify your password"] = "Verify your password";
$lang["The passwords that you entered do not match each other. Please check the spelling and re-submit."] = "The passwords that you entered do not match each other. Please check the spelling and re-submit.";
$lang["You have elected to register as a customer but did not choose a password for your account. Please do so now."] = "You have elected to register as a customer but did not choose a password for your account. Please do so now.";
$lang["If you are not using the customer registration feature, you may leave the password fields blank"] = "If you are not using the customer registration feature, you may leave the password fields blank";
$lang["Billing Information"] = "Billing Information";
$lang["First Name"] = "First Name";
$lang["Last Name"] = "Last Name";

$lang["Company Name"] = "Company Name";
$lang["Optional"] = "Optional";
$lang["Address"] = "Address";
$lang["No PO Boxes"] = "No PO Boxes";
$lang["City"] = "City";
$lang["Zip Code"] = "Post Code";
$lang["State/Province"] = "County/State";
$lang["Country"] = "Country";
$lang["Billing Phone Number"] = "Billing Phone Number";
$lang["Email Address"] = "Email Address";
$lang["Used to send a copy of your invoice, and also serves as your username for future purchases."] = "Used to send a copy of your invoice, and also serves as your username for future purchases.";
$lang["to use Billing Information. Note, we do not ship to P.O. Boxes."] = "to use Billing Information. Note, we do not ship to P.O. Boxes.";
$lang["Zip Code"] = "Post Code";
$lang["Ship-To Phone Number"] = "Ship-To Phone Number";


// pgm-cust_invoice.php
// ---------------------------------
$lang["Shipping & Handling"] = "Shipping & Handling";
$lang["BILLING INFORMATION"] = "BILLING INFORMATION";
$lang["SHIPPING INFORMATION"] = "SHIPPING INFORMATION";
$lang["Product Name"] = "Product Name";
$lang["Unit Price"] = "Unit Price";
$lang["Quantity"] = "Quantity";
$lang["Sub-Total"] = "Sub-Total";
$lang["Tax"] = "Tax";
$lang["Total"] = "Total";
$lang["EDIT"] = "EDIT";


// prod_offline_card.inc
// ---------------------------------
$lang["The total amount of your purchase"] = "The total amount of your purchase"; //[total]
$lang["will be charged to your credit card."] = "will be charged to your credit card.";

$lang["Name as it appears on card"] = "Name as it appears on card";
$lang["Credit Card Type"] = "Credit Card Type";
$lang["Credit Card Number"] = "Credit Card Number";
$lang["Credit Card Expiration Date"] = "Credit Card Expiration Date";
$lang["Month"] = "Month";
$lang["Security Code"] = "Security Code";
$lang["How to find your security code"] = "How to find your security code";


// prod_search_column.inc
// ---------------------------------
$lang["Welcome"] = "Welcome";
$lang["Client Login"] = "Client Login";
$lang["Find Now"] = "Find Now";
$lang["Search Products"] = "Search Products";
$lang["Browse Categories"] = "Browse Categories";
$lang["Your cart is empty."] = "Your cart is empty.";
$lang["VIEW OR EDIT CART"] = "VIEW OR EDIT CART";
$lang["Telephone Orders"] = "Telephone Orders";
$lang["We Accept"] = "We Accept"; // (the following credit cards)

$lang["We are currently not accepting online orders."] = "We are currently not accepting online orders.";
$lang["We are currently only accepting cheque or money orders online."] = "We are currently only accepting cheques or money orders online.";
$lang["Returns & Exchanges"] = "Returns & Exchanges";
$lang["Privacy Policy"] = "Privacy Policy";
$lang["Other Policies"] = "Other Policies";


// prod_search_template.php
// ---------------------------------
$lang["Buy Now"] = "Buy Now";
$lang["Add to Cart"] = "Add to Cart";
$lang["Related Products"] = "Related Products";
$lang["Catalog"] = "Catalogue";
$lang["Browse Category"] = "Browse Category";



// start.php
// ---------------------------------
$lang["Search Results For"] = "Search Results For";
$lang["Displaying"] = "Displaying";
$lang["Matches Found"] = "Matches Found"; // "[X] Matches Found"
$lang["Sorry, no products were found that match your search criteria."] = "Sorry, no products were found that match your search criteria.";
$lang["Please try again or browse the suggested selections below."] = "Please try again or browse the suggested selections below.";
$lang["NEXT"] = "NEXT";
$lang["Welcome to"] = "Welcome to";
$lang["Mailing Address"] = "Mailing Address";



#################################################
## WEBMASTER MENU             				     ##
#################################################

// webmaster.php
// ---------------------------------
$lang["USERNAME/PASSWORD NOT CHANGED"] = "USERNAME/PASSWORD NOT CHANGED";

$lang["Your username or password change"] = "Your username or password change";
$lang["could not be verified. Please try again."] = "could not be verified. Please try again.";

$lang["Your Administrative Username and Password has been changed"] = "Your Administrative Username and Password has been changed";
$lang["Administration Login"] = "Administration Login";
$lang["New Username"] = "New Username";
$lang["Verify New Username"] = "Verify New Username";
$lang["New Password"] = "New Password";
$lang["Verify New Password"] = "Verify New Password";
$lang["Change Username/Password"] = "Change Username/Password";
$lang["Select User"] = "Select User";
$lang["Multi-User Access"] = "Multi-User Access";
$lang["Edit User"] = "Edit User";
$lang["Default Meta Tag Data"] = "Default Meta Tag Data";
$lang["Restart Quickstart Wizard"] = "Restart Quickstart Wizard";
$lang["Language"] = "Language";
$lang["Swap Language"] = "Swap Language";
$lang["Access Rights"] = "Access Rights";
$lang["Global Settings"] = "Global Settings";
$lang["Meta Tag Data"] = "Meta Tag Data";
$lang["Miscellaneous Options"] = "Miscellaneous Options";
$lang["Disable Developer Mode"] = "Disable Developer Mode";
$lang["Enable Developer Mode"] = "Enable Developer Mode";


// global_settings.php
// ---------------------------------
$lang["Business Address"] = "Business Address";
$lang["State"] = "State";
$lang["Postal Code"] = "Postal Code";
$lang["Apt. / Suite"] = "Apt. / Suite";
$lang["Phone Number"] = "Phone Number";


// meta_data.php
// ---------------------------------
$lang["This will be displayed at the top of the browser window on all pages of your site."] = "This will be displayed at the top of the browser window on all pages of your site.";


$lang["Web Site Description"] = "Web Site Description";
$lang["This is a Meta Tag that helps search engines classify your web site."] = "This is a Meta Tag that helps search engines classify your web site.";
$lang["This should be a small sentance that describes your site."] = "This should be a small sentence that describes your site.";

$lang["Web Site Keywords"] = "Web Site Keywords";
$lang["This is a Meta Tag that some search engines use to search your site with."] = "This is a Meta Tag that some search engines use to search your site with.";
$lang["Please enter each keyword separated by a comma."] = "Please enter each keyword separated by a comma.";
$lang["There is no need to use line feeds or carriage returns in the field."] = "There is no need to use line feeds or carriage returns in the field.";
$lang["Note: Individual Meta Tag Data can be edited from Page Properties while editing the page."] = "Note: Individual Meta Tag Data can be edited from Page Properties while editing the page.";
$lang["Save Meta Tag Data"] = "Save Meta Tag Data";


// add_user.php
// ---------------------------------
$lang["has been added to your administrative users list."] = "has been added to your administrative users list."; // "[Full Name] has been added to your..."
$lang["Admin User's Full Name"] = "Admin User's Full Name";
$lang["Login Username"] = "Login Username";
$lang["Login Password"] = "Login Password";
$lang["Select the seperate <U>Modules</U> that this user should have access to"] = "Select the seperate <U>Modules</U> that this user should have access to";
$lang["Enable Basic Features"] = "Enable Basic Features";
$lang["Enable Advanced Features"] = "Enable Advanced Features";
$lang["Select each <U>Site Page</U> this user should have access to"] = "Select each <U>Site Page</U> this user should have access to";
$lang["Note: User will not be able to access these pages unless the Edit Pages module itself is enabled (above)."] = "Note: User will not be able to access these pages unless the Edit Pages module itself is enabled (above).";
$lang["Shopping Cart access options"] = "Shopping Cart access options";
$lang["Note: User must have access to Shopping Cart module itself (above)."] = "Note: User must have access to Shopping Cart module itself (above).";
$lang["View Invoices Only"] = "View Invoices Only";
$lang["Select each <U>User Data Table</U> this user should have access to"] = "Select each <U>User Data Table</U> this user should have access to";
$lang["Cancel Create"] = "Cancel Create";
$lang["Create New User"] = "Create New User";


// edit_user.php
// ---------------------------------
$lang["The settings for"] = "The settings for";
$lang["have been updated."] = "have been updated.";

$lang["Edit Administrative User"] = "Edit Administrative User";
$lang["You have selected to delete the user"] = "You have selected to delete the user"; // [username]
$lang["Once you click OK, you can not undo this process."] = "Once you click OK, you can not undo this process.";
$lang["Are you sure you wish to delete this user"] = "Are you sure you wish to delete this user"; // "?"
$lang["Cancel Edit"] = "Cancel Edit";
$lang["Delete User"] = "Delete User";
$lang["Update User"] = "Update User";




// open_page.php
// ---------------------------------
$lang["This page will be the first page that pulls up when a visitor goes to http://"] = "This page will be the first page that pulls up when a visitor goes to http://";
$lang["Also known as: start page, index page, default page."] = "Also known as: start page, index page, default page.";


// Random Strings
// ---------------------------------
//$lang["Backup/Restore"] = "";
//$lang["Secure Users Menu"] = "";
//$lang["Site Backup / Restore"] = "";
//$lang["Install Software Updates"] = "";
//$lang["Install Software Updates"] = "";
//$lang["Check for software updates"] = "";
//$lang["Current Version"] = "";
//$lang["Release Date"] = "";
//$lang["Changes in this build"] = "";
//$lang["On-Menu Pages"] = "";
//$lang["Off-Menu Pages"] = "";
//$lang["Speed-Dial Pages Menu"] = "";
//$lang["Note: You may assign a single Site Base Template that applies to your entire website via the <a href=#LINK#>Template Manager</a> feature."] = "";
//$lang["To change the template for a specific page, edit the page, select page properties, and select the template from the drop down box."] = "";
//$lang["Printable Page"] = "";
//$lang["Background"] = "";
//$lang["Click on an object above and drag it onto a drop zone for page placement."] = "";
//$lang["Click on an object below and drag it onto a drop zone for page placement."] = "";
//$lang["Please only use Alpha Numerical characters and Underscores."] = "";
//$lang["Media, document, and code files may be downloaded by clicking on the arrow next to the filename."] = "";
//$lang["Image files can be viewed and saved by clicking the preview icon next to the filename."] = "";
//$lang["Indicates an image that should be reduced in filesize. This file causes slow load-times when viewing your web site."] = "";
//$lang["Images"] = "";
//$lang["Rename"] = "";
//$lang["Documents, Presentations, and Adobe PDFs"] = "";
//$lang["Video Files"] = "";
//$lang["Spreadsheets and CSV files"] = "";
//$lang["Custom web forms and text files"] = "";
//$lang["Custom HTML includes"] = "";
//$lang["Custom HTML template files"] = "";
//$lang["Custom PHP scripts"] = "";
//$lang["Unclassified files"] = "";
//$lang["Select the <U>Browse</U> button next to each filename to locate your local file for upload. <BR>When you are ready to start the upload operation, select <U>Upload Files</U>."] = "";
//$lang["Upload Custom Template Folder (Zipped)"] = "";
//$lang["To upload a custom template"] = "";
//$lang["Place all files(images,index.html,custom.css) into a folder and name the folder like this"] = "";
//$lang["Category-Sub_Category-Color"] = "";
//$lang["Example"] = "";
//$lang["Zip the folder and upload it below"] = "";
//$lang["After upload the template will be availible in the list of templates"] = "";
//$lang["Zipped Template Folder"] = "";
//$lang["What is your site visitor supposed to enter or select for this field"] = "";
//$lang["In progress"] = "";
//$lang["Complete"] = "";
//$lang["No file selected!\nPlease choose a backup file from your hard drive."] = "";
//$lang["Website backup in progress..."] = "";
//$lang["This process may take several moments."] = "";
//$lang["Importing website backup file..."] = "";
//$lang["This process may take several moments, depending on connection speed."] = "";
//$lang["User notes for this backup"] = "";
//$lang["Site backup in progress. Please hold."] = "";
//$lang["Creating folder for this backup"] = "";
//$lang["Writing backup info to text file"] = "";
//$lang["Archiving site content and files"] = "";
//$lang["Creating data table restoration file"] = "";
//$lang["Creating downloadable archive file"] = "";
//$lang["Inserting backup record into site log"] = "";
//$lang["Done"] = "";
//$lang["Restore from a previous backup"] = "";
//$lang["Note: When downloading backups, make sure to save the file with a '.tgz' extension NOT '.gz'"] = "";
//$lang["Note: After backing up your site, please download the backup and delete it here for security purposes."] = "";
//$lang["Backup Title"] = "";
//$lang["Backup Date"] = "";
//$lang["Backup Time"] = "";
//$lang["Are you sure you want to permanently delete this backup?"] = "";
//$lang["Current website will be replaced with backup data."] = "";
//$lang["All unsaved data will be lost."] = "";
//$lang["Are you sure you want to restore the backup?"] = "";
//$lang["Upload and import site backup file"] = "";
//$lang["Select Backup File"] = "";
//$lang["Import Backup File"] = "";
//$lang["Webmaster: Site Backup and Restoration"] = "";
//$lang["Description:"] = "";
//$lang["Note: Thumbnail images should be no more than 99px wide."] = "";
//$lang["Full Size Images should be no more than 275px wide for optimal display within your web site."] = "";
//$lang["When customers add this product to thier cart, require Form Data from:"] = "";
//$lang["User-Defined Variable"] = "";
//$lang["Denotes an event that is a 'Recurrence' of an original master event."] = "";
//$lang["Denotes the original 'Master' event within a recurring event cycle."] = "";
//$lang["Special Promotions"] = "";
//$lang["Step 1: Blog Title"] = "";
//$lang["Done!"] = "";
//$lang["Step 2: Enter Content For Blog"] = "";
//$lang["Launch Editor"] = "";
//$lang["Step 3: Post Blog to"] = "";
//$lang["Delete Entry"] = "";
//$lang["Edit Entry"] = "";
//$lang["Save Entry"] = "";
//$lang["show all"] = "";


?>