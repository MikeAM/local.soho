<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

require_once('includes/product_gui.php');

# Possible 'go' values
$go_vals = array('cart', 'templates', 'enewsletter', 'pagesfiles', 'secure', 'blogs', 'calendars', 'dbtables');

if ( $go == "" ) {
   $gonum = rand(0, count($go_vals));
   $go = $go_vals[$gonum];
}  

if ( $go == "cart" ) {
   $main_screenshot = "template_manager";
   $subscreen[0] = "template_manager";
   $subscreen[1] = "page_objects";
   $subscreen[2] = "template_builder";
}

if ( $go == "cart" ) {
   /*------------------------------------------------------*
     ___             _   
    / __| __ _  _ _ | |_ 
   | (__ / _` || '_||  _|
    \___|\__,_||_|   \__|  
   /*------------------------------------------------------*/   
   # screenshots
   $main_screenshot = "shopping_cart";
   $main_description = "Soholaunch Shopping Cart helps you develop and manage all types of eCommerce applications, regardless of scope. Effortlessly configure product categories, shipping methods, tax rates, payment options, exchange policies, and much more; all without typing a single line of code.";
   $subscreen[0] = "sku_images";
   $subscreen[1] = "payment_methods";
   $subscreen[2] = "display_options";
   
   # Unique features available to each individual item
   $subheading[0] = "Unique features available to each individual item";
   $subpoints[0][] = "Add an unlimited number of items to your eCommerce store";
   $subpoints[0][] = "Define keywords for product searches";
   $subpoints[0][] = "Assign thumbnail and full size product images";
   $subpoints[0][] = "Specify price variations for different product-specific options";
   $subpoints[0][] = "Create descriptions and detail pages";
   $subpoints[0][] = "Require form data when item is purchased";
   $subpoints[0][] = "Promote with related items and accessories at checkout";
   
   # Popular payment methods for painless transactions
   $subheading[1] = "Popular payment methods for painless transactions";
   $subpoints[1][] = " Choose from online and offline processing methods";
   $subpoints[1][] = "Use any combination of payment methods you wish";
   $subpoints[1][] = "Accept specific credit cards with offline or online processing";
   $subpoints[1][] = "Accept cusomter payments through PayPal (no merchant account required), Verisign, and other gateways";
   $subpoints[1][] = "Secure shopping cart orders with an SSL certificate";
   $subpoints[1][] = "Charge shipping by item and sub-total";
   $subpoints[1][] = "Apply state taxes based on shipping address";
   $subpoints[1][] = "Display privacy, shipping, return/exchange, and other policies";
   
   # Plenty of excellent checkout options
   $subheading[2] = "Plenty of excellent checkout options";
   $subpoints[2][] = "Create and name unlimited site templates";
   $subpoints[2][] = "Organize products into multiple categories";
   $subpoints[2][] = "Automatically decrement inventory upon purchase";
   $subpoints[2][] = "Choose, create, or customize colors and store layout";
   $subpoints[2][] = "Create product searches and upselling associations";
   $subpoints[2][] = "Remember repeat customer information automatically";
   $subpoints[2][] = "Charge shipping by item and sub-total";
   $subpoints[2][] = "Allow site visitors to make product comments";
   $subpoints[2][] = "Restrict access to specified products and/or bulk pricing";


} elseif ( $go == "templates" ) {
   /*------------------------------------------------------*
    _____                   _        _            
   |_   _|___  _ __   _ __ | | __ _ | |_  ___  ___
     | | / -_)| '  \ | '_ \| |/ _` ||  _|/ -_)(_-<
     |_| \___||_|_|_|| .__/|_|\__,_| \__|\___|/__/
                     |_|   
   /*------------------------------------------------------*/
   # screenshots
   $main_screenshot = "template_manager";
   $main_description = "Once you have chosen, created, and/or uploaded your site's template(s), or consistent graphical layout, through the Site Templates Manager, you can then assign them to your entire website in a single click. For sites that warrant the use of several different visual themes to separate large sections, templates can be specified on a per-page basis.";
   $subscreen[0] = "template_manager";
   $subscreen[1] = "page_objects";
   $subscreen[2] = "template_builder";
   
   # manage templates and design elements
   $subheading[0] = "Manage all your templates and design elements";
   $subpoints[0][] = "Choose from pre-installed templates";
   $subpoints[0][] = "Upload and use custom templates";
   $subpoints[0][] = "Assign different templates to different pages";
   $subpoints[0][] = "Specify header text for built-in templates";
   $subpoints[0][] = "Specify reply-to address, subject, and content of email receipt";
   $subpoints[0][] = "Enable and customize automatic email receipts for submissions";
   
   # drag and drop stuff onto pages
   $subheading[1] = "Drag-and-drop advanced features onto the pages";
   $subpoints[1][] = "Linked images";
   $subpoints[1][] = "Text content";
   $subpoints[1][] = "Online forms";
   $subpoints[1][] = "Documents and PDF files";
   $subpoints[1][] = "MOV, .MPG, and .AVI video files";
   $subpoints[1][] = "Automatic date stamps";
   $subpoints[1][] = "Printable page view links";
   $subpoints[1][] = "Hit counters";
   
   # use template builder to create your own designs
   $subheading[2] = "Create your own designs with Template Wizard";
   $subpoints[2][] = "Create and name unlimited site templates";
   $subpoints[2][] = "Choose from thousands of different color combinations";
   $subpoints[2][] = "Specify color name or hex values to match to company logo exactly";
   $subpoints[2][] = "Integrate images and logos with each template";
   $subpoints[2][] = "Apply your template to your whole site, or create several and assign them to individual pages or sections";
   $subpoints[2][] = "Specify reply-to address, subject, and content of email receipt";
   
} elseif ( $go == "enewsletter" ) {
   /*------------------------------------------------------*
         _  _                   _       _    _             
    ___ | \| | ___ __ __ __ ___| | ___ | |_ | |_  ___  _ _ 
   / -_)| .` |/ -_)\ V  V /(_-<| |/ -_)|  _||  _|/ -_)| '_|
   \___||_|\_|\___| \_/\_/ /__/|_|\___| \__| \__|\___||_|  
   /*------------------------------------------------------*/
   # screenshots
   $main_screenshot = "template_manager";
   $main_description = "Newsletters are a great way to generate repeat business. Simply select your desired newsletter, which you can build in the Page Editor, and then specify who you would like to send it to, such as your shopping cart customers.";
   $subscreen[0] = "enewsletter";
   
   # Foster repeat business with email marketing campaigns
   $subheading[0] = "Foster repeat business with email marketing campaigns";
   $subpoints[0][] = "Choose from pre-installed templates";
   $subpoints[0][] = "Create, send, and manage unlimited text and html campaigns";
   $subpoints[0][] = "Design dynamic eNewsletters in the Page Editor";
   $subpoints[0][] = "Populate send-to list with any data table";
   $subpoints[0][] = "Create personalized greetings for thousands of subscribers automatically";
   $subpoints[0][] = "Send eNewsletters to Shopping Cart customers";
   $subpoints[0][] = "Include site pages, Shopping Cart items, Event Calendars, and Web Blogs eNewsletter campaigns";
   $subpoints[0][] = "Unsubscribe email addresses manually or automatically";
   
} elseif ( $go == "pagesfiles" ) {
   /*------------------------------------------------------*
    ___                        ___  _  _          
   | _ \ __ _  __ _  ___  ___ | __|(_)| | ___  ___
   |  _// _` |/ _` |/ -_)(_-< | _| | || |/ -_)(_-<
   |_|  \__,_|\__, |\___|/__/ |_|  |_||_|\___|/__/
              |___/    
   /*------------------------------------------------------*/
   # screenshots
   $main_screenshot = "template_manager";
   $main_description = "The Page Editor is the heart of Pro Edition. What once required many lines of html coding can now be done by draging and dropping items through the page editor tool. This tool makes customizing each page a simple task.";
   $subscreen[0] = "page_editor";
   $subscreen[1] = "page_objects";
   $subscreen[2] = "forms_library";
   $subscreen[3] = "form_builder";
   
   # Add rich, dynamic content to your site pages
   $subheading[0] = "Add rich, dynamic content to your site pages";
   $subpoints[0][] = "Choose from pre-installed templates";
   $subpoints[0][] = "Copy and paste text and formatting from Microsoft Word documents";
   $subpoints[0][] = "Create tables for layouts and charts with borders and background colors";
   $subpoints[0][] = "Create multi-level lists with auto-numbering, bullet points, and indentions";
   $subpoints[0][] = "Control alignment of text, images, tables, and other objects";
   $subpoints[0][] = "Link text and images to pages, files, email addresses, and other websites";
   $subpoints[0][] = "Visually separate sections of content with horizontal dividers";
   $subpoints[0][] = "Wrap paragraphs and text around images and tables";
   $subpoints[0][] = "Change font types, colors, sizes, and styles";
   
   # Drag-and-drop advanced features onto the pages
   $subheading[1] = "Drag-and-drop advanced features onto the pages";
   $subpoints[1][] = "Linked images";
   $subpoints[1][] = "Text content";
   $subpoints[1][] = "Online forms";
   $subpoints[1][] = "Documents and PDF files";
   $subpoints[1][] = "MOV, .MPG, and .AVI video files";
   $subpoints[1][] = "Automatic date stamps";
   $subpoints[1][] = "Printable page view links";
   $subpoints[1][] = "Hit counters";
   $subpoints[1][] = "Maps and directions";
   $subpoints[1][] = ".WAV and .MP3 audio files";
   $subpoints[1][] = "PlugIn linkns with icons";
   $subpoints[1][] = "Shopping Cart promotions";
   $subpoints[1][] = "Photo albums with links";
   $subpoints[1][] = "PopUp Windows";
   
   # Collect visitor feedback with online forms
   $subheading[2] = "Collect visitor feedback with online forms";
   $subpoints[2][] = "Choose from pre-installed email feedback forms";
   $subpoints[2][] = "Upload and use custom forms";
   $subpoints[2][] = "Email collected data to a specified address(s)";
   $subpoints[2][] = "Redirect site visitor upon form submission";
   $subpoints[2][] = "Enable and customize automatic email receipts for submissions";
   $subpoints[2][] = "Specify reply-to address, subject, and content of email receipt";
   
   # Web form builder
   $subheading[3] = "Web form builder";
   $subpoints[3][] = "Create online forms to collect visitor information";
   $subpoints[3][] = "Use Forms Library functions with created forms";
   $subpoints[3][] = "Collect data through single and multi-line text areas";
   
} elseif ( $go == "secure" ) {
   /*------------------------------------------------------*
    ___                          
   / __| ___  __  _  _  _ _  ___ 
   \__ \/ -_)/ _|| || || '_|/ -_)
   |___/\___|\__| \_,_||_|  \___|
   /*------------------------------------------------------*/
   # screenshots
   $main_screenshot = "template_manager";
   $main_description = "Protecting portions of your website from public access is one of the most common tasks in creating advanced web sites and web applications. Aside from extensive user-management functions, the Secure Users features also allows you to create multiple levels of access through user-defined security codes, or \"groups\".";
   $subscreen[0] = "template_manager";
   $subscreen[1] = "user_info";
   $subscreen[2] = "secure_user";
   
   # Issue members and clients a "VIP pass" to your website
   $subheading[0] = "Issue members and clients a \"VIP pass\" to your website";
   $subpoints[0][] = "Grant Secure Users access to special content, features, and pricing";
   $subpoints[0][] = "Billing and shipping data can be automatically \"remembered\" when Secure Users make Shopping Cart purchases";
   $subpoints[0][] = "Allow Secure Users to manage their own information through your website";
   $subpoints[0][] = "Create custom searches for rosters, member directories, etc, that allow users to edit their personal data when viewing their own record in the search results";
   $subpoints[0][] = "Provide secure users with personal Events Calendar that they can maintain";
   
   # Administrative tools for managing a community of customers
   $subheading[1] = "Administrative tools for managing a community of customers";
   $subpoints[1][] = "Restrict access to virtually anything: Site Templates, Shopping Cart products and categories, Event Calendars, and all features therein";
   $subpoints[1][] = "Organize users into groups with different \"clearance levels\"";
   $subpoints[1][] = "Create an unlimited number of secure users and groups";
   $subpoints[1][] = "Batch authenticate thousands of users at once with Database Table Manager";
   $subpoints[1][] = "Give users access to multiple clearance levels on an individual basis";
   
} elseif ( $go == "blogs" ) {
   /*------------------------------------------------------*
    ___  _                
   | _ )| | ___  __ _  ___
   | _ \| |/ _ \/ _` |(_-<
   |___/|_|\___/\__, |/__/
                |___/ 
   /*------------------------------------------------------*/
   # screenshots
   $main_screenshot = "template_manager";
   $main_description = "Essentially the digital incarnation of newspaper and magazaine columns, Web Blogs are quickly gaining popularity with budding writers and experienced journalists everywhere! With a host of user-friendly features, the Soholaunch Blog Manager eliminates the technical barriers of Web Blogging, and lets you concentrate on what matters most - your opinion.";
   $subscreen[0] = "blog_manager";
   
   # Just think of its as your own "Digital Soapbox"
   $subheading[0] = "Just think of its as your own \"Digital Soapbox\"";
   $subpoints[0][] = "Create unlimited Web Blogs to cover a infinite range of topics";
   $subpoints[0][] = "Compose and post unlimited entries to each subject";
   $subpoints[0][] = "Enhance your Blog with links, images, tables, bullet lists, and text formatting";
   $subpoints[0][] = "Automatically organize blog entries by the date you post them";
   $subpoints[0][] = "All entries filed in Blog Archives automatically";
   $subpoints[0][] = "Drag-and-drop Web Blogs onto site pages";
   
} elseif ( $go == "calendars" ) {
   /*------------------------------------------------------*
     ___        _                _                
    / __| __ _ | | ___  _ _   __| | __ _  _ _  ___
   | (__ / _` || |/ -_)| ' \ / _` |/ _` || '_|(_-<
    \___|\__,_||_|\___||_||_|\__,_|\__,_||_|  /__/
   /*------------------------------------------------------*/
   # screenshots
   $main_screenshot = "template_manager";
   $main_description = "Especially useful for membership-based websites, the Event Calendar Module makes it easy to create and manage interactive event calendars for your website. When used in conjunction with the Secure Login System, you can specify the extent to which users can view and/or edit calendars and events.";
   $subscreen[0] = "calendar_menu";
   
   # Searchable event calendars keep everyone up-to-date
   $subheading[0] = "Searchable event calendars keep everyone up-to-date";
   $subpoints[0][] = "Create unlimited searchable event calendars, organized into categories";
   $subpoints[0][] = "Allow site visitors to submit events to calendars they have access to";
   $subpoints[0][] = "Allow registered Secure Users to create and maintain private event calendars";
   $subpoints[0][] = "Describe events with titles, short descriptions, and detail pages";
   $subpoints[0][] = "Create individual and recurring events";
   $subpoints[0][] = "Include site pages, Shopping Cart items, Event Calendars, and Web Blogs eNewsletter campaigns";
   $subpoints[0][] = "Unsubscribe email addresses manually or automatically";
   
} elseif ( $go == "dbtables" ) {
   /*------------------------------------------------------*
    ___         _           _____       _     _          
   |   \  __ _ | |_  __ _  |_   _|__ _ | |__ | | ___  ___
   | |) |/ _` ||  _|/ _` |   | | / _` || '_ \| |/ -_)(_-<
   |___/ \__,_| \__|\__,_|   |_| \__,_||_.__/|_|\___|/__/
   /*------------------------------------------------------*/
   # screenshots
   $main_screenshot = "template_manager";
   $main_description = "Managing MySQL database tables has never been so easy. The Database Table Manager gives you total control over you're MySQL database tables. You can change and add fields, edit individual entries, and also search your tables within Soholaunch Pro Edition.<br><br>";
   $main_description .= "Should you need to download your order tables, customer lists, or other data files from your website, you can do so through the View / Download Site Data Module. Database tables can be downloaded and modified through Microsoft Excel, or other such programs, then imported back into website data tables.";
   $subscreen[0] = "db_search";
   $subscreen[1] = "db_edit";
   
   # Easily create robust, database-driven features for your site
   $subheading[0] = "Easily create robust, database-driven features for your site";
   $subpoints[0][] = "Create and delete user database tables";
   $subpoints[0][] = "Add, delete, and modify individual records";
   $subpoints[0][] = "Rename, delete, and add fields to your data tables";
   $subpoints[0][] = "Batch authenticate thousands of secures users in only a few clicks";
   $subpoints[0][] = "Create custom searches for rosters, member directories, etc, that allow secure users to edit their personal data when viewing their own record";
   
   # Save countless hours by updating parts of your site all-at-once
   $subheading[1] = "Save countless hours by updating all-at-once";
   $subpoints[1][] = "View and empty all site data tables, including system tables";
   $subpoints[1][] = "Download data tables in .csv format and modify them as spreadsheets";
   $subpoints[1][] = "Import .csv data from spreadsheets (i.e. Excel) into shopping cart, secure user, and other system data tables";
   $subpoints[1][] = "Add and update thousands of Shopping Cart products in less than one minute";
   
}

?>

<link rel="stylesheet" href="http://info.soholaunch.com/sohoadmin/program/modules/site_templates/pages/Soholaunch-Bare_Essentials-v2/custom.css">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>

<center>
<div style="overflow: scroll; border: 0px solid red; width: 95%; height: 490px;">
 <table width="100%"  border="0" cellpadding="8" cellspacing="0" class="blue_box" style="border: 0px;">
  <tr>
   <td bgcolor="#DFECF6" style="padding:0px;">
    <table width="100%"  border="0" cellpadding="3" cellspacing="0" class="icon_map">
     <tr>
      <td class="blue_right" style="border-bottom: 0px; background-color: #DFECF6;">
       <div align="center" class="style1"><a href="<? echo basename($_SERVER['PHP_SELF']); ?>?go=cart" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image26','','http://info.soholaunch.com/images/icon-prof-cart-on.gif',1)"><img src="http://info.soholaunch.com/images/icon-prof-cart-off.gif" name="Image26" width="37" height="37" border="0"></a></div>
      </td>
      <td class="blue_right" style="border-bottom: 0px; background-color: #DFECF6;">
       <div align="center" class="style2"><a href="<? echo basename($_SERVER['PHP_SELF']); ?>?go=templates" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image20','','http://info.soholaunch.com/images/icon-prof-tmp-on.gif',1)"><img src="http://info.soholaunch.com/images/icon-prof-tmp-off.gif" name="Image20" width="37" height="37" border="0" class="blue_right" style="border-bottom: 0px;"></a></div>
      </td>
      <td class="blue_right" style="border-bottom: 0px; background-color: #DFECF6;">
       <div align="center" class="style3"><a href="<? echo basename($_SERVER['PHP_SELF']); ?>?go=enewsletter" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image21','','http://info.soholaunch.com/images/icon-prof-news-on.gif',1)"><img src="http://info.soholaunch.com/images/icon-prof-news-off.gif" name="Image21" width="37" height="37" border="0"></a></div>
      </td>
      <td class="blue_right" style="border-bottom: 0px; background-color: #DFECF6;">
       <div align="center" class="style4"><a href="<? echo basename($_SERVER['PHP_SELF']); ?>?go=pagesfiles" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image22','','http://info.soholaunch.com/images/icon-prof-pages-on.gif',1)"><img src="http://info.soholaunch.com/images/icon-prof-pages-off.gif" name="Image22" width="37" height="37" border="0"></a></div>
      </td>
      <td class="blue_right" style="border-bottom: 0px; background-color: #DFECF6;">
       <div align="center" class="style5"><a href="<? echo basename($_SERVER['PHP_SELF']); ?>?go=secure" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image231','','http://info.soholaunch.com/images/icon-prof-secure-on.gif',1)"><img src="http://info.soholaunch.com/images/icon-prof-secure-off.gif" name="Image231" width="37" height="37" border="0" id="Image231"></a></div>
      </td>
      <td class="blue_right" style="border-bottom: 0px; background-color: #DFECF6;">
       <div align="center" class="style6"><a href="<? echo basename($_SERVER['PHP_SELF']); ?>?go=blogs" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image241','','http://info.soholaunch.com/images/icon-prof-blog-on.gif',1)"><img src="http://info.soholaunch.com/images/icon-prof-blog-off.gif" name="Image241" width="37" height="37" border="0" id="Image241"></a></div>
      </td>
      <td class="blue_right" style="border-bottom: 0px; background-color: #DFECF6;">
       <div align="center" class="style7"><a href="<? echo basename($_SERVER['PHP_SELF']); ?>?go=calendars" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image251','','http://info.soholaunch.com/images/icon-prof-cal-on.gif',1)"><img src="http://info.soholaunch.com/images/icon-prof-cal-off.gif" name="Image251" width="37" height="37" border="0" id="Image251"></a></div>
      </td>
      <td class="blue_btm" style="border-bottom: 0px; background-color: #DFECF6;">
       <div align="center"><a href="<? echo basename($_SERVER['PHP_SELF']); ?>?go=dbtables" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image271','','http://info.soholaunch.com/images/icon-prof-data-on.gif',1)"><img src="http://info.soholaunch.com/images/icon-prof-data-off.gif" name="Image271" width="37" height="37" border="0" id="Image271"></a></div>
      </td>
     </tr>
     <tr>
      <td height="20" class="gray" style="border-right: 1px solid border-bottom: #A5C6E6; 1px solid #888C8E; border-bottom: 1px solid #888C8E;">
       <div align="center">Shopping Cart</div>
      </td>
      <td class="gray" style="border-bottom: 1px solid #888C8E;">
       <div align="center">Templates</div>
      </td>
      <td class="gray" style="border-bottom: 1px solid #888C8E;">
       <div align="center">eNewsletter</div>
      </td>
      <td class="gray" style="border-bottom: 1px solid #888C8E;">
       <div align="center">Pages &amp; Files </div>
      </td>
      <td class="gray" style="border-bottom: 1px solid #888C8E;">
       <div align="center">Secure Logins</div>
      </td>
      <td class="gray" style="border-bottom: 1px solid #888C8E;">
       <div align="center">Blogs</div>
      </td>
      <td class="gray" style="border-bottom: 1px solid #888C8E;">
       <div align="center">Calendars </div>
      </td>
      <td class="gray" style="border-bottom: 1px solid #888C8E;">
       <div align="center">Data Tables </div>
      </td>
     </tr>
    </table>
   </td>
  </tr>
 </table>
             
 <table width="100%" border="0" cellpadding="0" cellspacing="0" class="text">
  <tr>
   <td colspan="2" align="left" valign="top" class="c_intro_text">
    <? echo $main_description; ?>
   </td>
  </tr> 
 <?
 
for ( $h = 0; $h < count($subheading); $h++ ) {
   
?>
     <tr>
      <td align="left" valign="top" colspan="2" class="c_sub_heading">
       <? echo $subheading[$h]; ?>
      </td>
     </tr>
     <tr>
      <td align="center" valign="middle">
       <img src="http://info.soholaunch.com/images/ss/ps-<? echo $subscreen[$h]; ?>.gif" width="250" height="188">
      </td>
      <td width="100%" class="sohotext1" valign="top">
       <ul>
        <?
        for ( $s = 0; $s < count($subpoints[$h]); $s++ ) {
        ?>
          <li class="sohotext1"><? echo $subpoints[$h][$s]; ?></li>
        <?
        }
        ?>
       </ul>
      </td>
     </tr>    
    
<?
}
?>
 </table>
</div>
</center>