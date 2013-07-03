<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

session_start();
$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../program/includes/product_gui.php');
chdir($curdir);
# smt_userdata
# The place for all random data used by smaller features and plugins
# Cuts down on the need for one-row tables.
# Works with:
# userdata_mode(), get_userdata(), set_userdata(), delete_userdata
#--------------------------------------------------------------------
if(!table_exists("smt_userdata")){
	create_table("smt_userdata");
	$nowwws = preg_replace('/^www\./i', '', $_SESSION['this_ip']);
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('admin','wizard','show')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('webmaster_pref','mm_shortcuts','on')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('webmaster_pref','forgotpw','yes')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('webmaster_pref','f2login','window')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('customfonts','fontfams','Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sand;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('blog_comment','require_approval','yes')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('blog_comment','emailto','".$_SESSION['PHP_AUTH_USER']."')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('seolink','pref','yes')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('faq','sort','asc')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('updateprefs','testing_builds','no')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('updateprefs','chmod_after','no')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('updateprefs','ignore_shellexec','no')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('wap_template','template','WAP-minimal-none')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('calendar','linebreaks','no')");
	
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','thumb_width','95')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','paypal_cc_logos','no')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','eway_uk_or_nz','nz')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','ewayuk_testmode','off')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','ewayuk_companyname','')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','ewayuk_companylogo','')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','ewayuk_pagebanner','')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','transactium_username','')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','transactium_password','')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','transactium_tag','')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','paypal_btn_text','Complete Order >>')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','checkorcheque','check')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','check-sendemail','yes')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','paypal_notify_url','enabled')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','paypal_testmode','off')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','dps-logo-display','white')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','other_policy_title','Other Policies')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','fullimg_maxwidth','550')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','custom_addcartbutton','Add to Cart >>')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','more_information_display','extended')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','skip_billingform_ifdone','no')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','no_pobox_msg','no')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','invoice_viewedit_link','no')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','invoice_custom_message','no')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','invoice_custom_message_text','')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','more-info-link','default')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','nopobox_msg','no')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','disable_shipping','no')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('cart','findsku_loadthumbs','onload')");
	
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('sitepal','df_width','150')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('sitepal','df_height','225')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('sitepal','df_bgcolor','ffffff')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('backup','direct-link','')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('global','utf8','on')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('global','custom_login_email','Hi [OWNER_NAME],\n\nWe have created a member account for you so you can access protected areas of our website. See below for your username and password.\n\nUsername: [USERNAME]\nPassword: [PASSWORD]\n\n\nSee our website for more...\nhttp://".$nowwws."\n\nSincerely,\n".$nowwws." Staff\n')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('global','login-email-from','noreply@".$nowwws."')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('global','login-email-subject','Here is your member login information')");

	if($spanish=='yes' || $_SESSION['spanish']=='yes'){
		mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('global','goog_trans','on')");
	} else {
		mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('global','goog_trans','off')");
	}		
	
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('global','goog_trans_website','off')");
	
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('global','google_analytics_non','')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('global','google_analytics_secure','')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('global','member-email-on-create','on')");
	
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('webmaster','replace_homelinks','yes')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('tax_rate_options','taxby','BCOUNTRY')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('tax_rate_options','taxwhen','aftershipping')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('tax_rate_options','vat-or-gst','GST')");
	
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('newsletter','default_emailfrom','webmaster@".$nowwws."')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('newsletter','default_emailfrom_display','webmaster@".$nowwws."')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('newsletter','newsletter_send_to_address','list@".$nowwws."')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('newsletter','newsletter_send_to_name','".$nowwws." subscriber')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('newsletter','tablename_filter','')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('newsletter','hide_systemtables','no')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('newsletter','smtp_custom','no')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('newsletter','default_template','')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('newsletter','pagename_filter','')");
	
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('internetsecure','acctid','')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('internetsecure','acctkey','')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('nochex','acctid','')");
	
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('forms','include-captcha','on')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('forms','block-links','on')");
	mysql_query("insert into smt_userdata (plugin,fieldname,data) VALUES ('forms','spam-trap-message','Sorry for the inconvenience, but we cannot process your form submission because our system thinks (based on the format of your message) you are a spammer.')");
}

#############################################################################


if(!table_exists("site_specs")) {
   // Read user.conf.php for generic info
   // =============================================
   $filename = "config/user.conf.php";
   if ($file = fopen("$filename", "r")) {
   	$body = fread($file,filesize($filename));
   	$lines = split("\n", $body);
   	$numLines = count($lines);
   	for ($x=0;$x<=$numLines;$x++) {
   		// Register all variables inside user.conf file
   		// --------------------------------------------------------------
   		if (!eregi("#", $lines[$x])) {
   			$variable = strtok($lines[$x], "=");
   			$value = strtok("\n");
   			$value = rtrim($value);
   			$_SESSION[$variable] = $value;
   			${$variable} = $value;
   		}
   	}
   	fclose($file);
   } // End If File Open
   // Set language to english if empty
   // =========================================
	if ( $lang_set == "" ) {
		if($spanish=='yes' || $_SESSION['spanish']=='yes'){
			$lang_set = "spanish.php";
		} else {
			$lang_set = "english.php";	
		}      
	}
   // Read HDRTXT and SLOGAN text if available
   // ============================================
   $logoconf = "$cgi_bin/logo.conf";
   if (file_exists("$logoconf")) {
    	$file = fopen("$logoconf", "r");
   	$body = fread($file,filesize($logoconf));
   	fclose($file);
   	$lines = split("\n", $body);
   	$numLines = count($lines);
   	for ($x=0;$x<=$numLines;$x++) {
   		$temp = split("=", $lines[$x]);
   		$variable = $temp[0];
   		$value = $temp[1];
   		${$variable} = $value;
   	}
   } else {
   	$headertext = "";
   	$subheadertext = "";
   }

	if(table_exists("blog_category")){
		// Try to pull news_cat and promo_cat from blog_category table
		// ==============================================================
		$newscat = 1;
		$promocat = 2;
		$blogrez = mysql_query("SELECT * FROM blog_category");
		while ( $getBlog = mysql_fetch_array($blogrez) ) {
		   if ( $getBlog['CATEGORY_NAME'] == "Latest News" ) {
		      $newscat = $getBlog['PRIKEY'];
		   }
		   if ( $getBlog['CATEGORY_NAME'] == "Special Promotions" ) {
		      $promocat = $getBlog['PRIKEY'];
		   }
		}

	} 
	// End if blog table exists
	##============================================================
	// Construct and execute database queries
	##============================================================
	$sql_prob = 0; // Error counter
	$nowww = eregi_replace("^www\.", "", $this_ip);
	if ( $headertext == "" ) { $headertext = "Welcome"; }
	$dfemail = "webmaster@".$nowww;
//	# Build INSERT data
//	$nDis = "'$dfuser_company','$dfuser_address','$dfuser_aptnum','$dfuser_city',";
//	$nDis .= "'$dfuser_state','$dfuser_zip','$dfuser_country',";
//	$nDis .= "'$dfuser_phone','".$dfemail."','$this_ip',";
//	$nDis .= "'Home','','$lang_set',";
//	$nDis .= "'$newscat','$promocat',";
//	$nDis .= "'".date('Y')." $dfuser_company','','',"; // Clear through df_misc2
//	$nDis .= "'$headertext','$subheadertext','', 'Home', ''"; // Clear through df_fax
//	if ( !table_exists("site_specs") ) {
//		create_table("site_specs");		
//		mysql_query("INSERT INTO site_specs VALUES($nDis)");
//	}
	
	$data = array();
	if($dfuser_company ==''){
		$dfuser_company = $_SESSION['this_ip'];
	}
	$data['df_company'] = $dfuser_company;
	$data['df_address1'] = $dfuser_address;
	$data['df_address2'] = $dfuser_aptnum;
	$data['df_city'] = $dfuser_city;
	$data['df_state'] = $dfuser_state;
	$data['df_zip'] = $dfuser_zip;
	$data['df_country'] = $dfuser_country;
	$data['df_phone'] = $dfuser_phone;
	$data['df_email'] = $_SESSION['PHP_AUTH_USER'];
	$data['df_domain'] = $_SESSION['this_ip'];
	$data['df_page'] = 'Home';
	$data['df_logo'] = '';
	$data['df_lang'] = $lang_set;
	$data['news_cat'] = $newscat;
	$data['promo_cat'] = $promocat;
	$data['copyright'] = date('Y');
	$data['df_misc1'] = '';
	$data['df_misc2'] = '';
	$data['df_hdrtxt'] = 'Welcome';
	$data['df_slogan'] = '';
	$data['dev_mode'] = '';
	$data['startpage'] = 'Home';
	$data['df_fax'] = '';
	create_table('site_specs');
	$myqry = new mysql_insert("site_specs", $data);
	if(!$myqry->insert()){
	//	echo mysql_error();
	}

	
	
	if ( $sql_prob == 0 ) { unlink($logoconf); }

} // End if site_specs table exists


###############################
# Create blog tables
if(!table_exists("blog_category")){
	create_table("blog_category");
	$newscat = lang("Latest News");
	$newscat = trim($newscat);
	$promocat = lang("Special Promotions");
	$promocat = trim($promocat);
	$nboxcat = 0;
	$pboxcat = 0;
	$catRez = mysql_query("SELECT * FROM blog_category");
	while ( $catScan = mysql_fetch_array($catRez) ) {
	   $disCat = trim($catScan[CATEGORY_NAME]);
	   if ( $disCat == $newscat ) { $nboxcat++; }
	   if ( $disCat == $promocat ) { $pboxcat++; }
	}
	if ( $nboxcat == 0 ) {
	   // Insert news box category
	   // --------------------------------------------
	   mysql_query("INSERT INTO blog_category VALUES('','$newscat')");
	}
	if ( $pboxcat == 0 ) {
	   // Insert promo box category
	   // --------------------------------------------
	   mysql_query("INSERT INTO blog_category VALUES('','$promocat')");
	}
	if ( ($nboxcat + $pboxcat) < 1 ) {
	   // Scan again - Pull prikey values for site_specs table
	   // -----------------------------------------------------
	   $newsKey = "";
	   $promoKey = "";
	   $catRez = mysql_query("SELECT * FROM blog_category WHERE CATEGORY_NAME = '$newscat' OR CATEGORY_NAME = '$promocat'");
	   while ( $catScan = mysql_fetch_array($catRez) ) {
	      $disCat = trim($catScan[CATEGORY_NAME]);
	      if ( $disCat == $newscat ) { $newsKey = $catScan[PRIKEY]; }
	      if ( $disCat == $promocat ) { $promoKey = $catScan[PRIKEY]; }
	   }
	
	   // Record promo and newsbox category IDs in site_specs
	   // ===========================================================
	   mysql_query("UPDATE site_specs SET news_cat = '$newsKey',promo_cat = '$promoKey'");
	
	} // End if creating news and promo categories
}

if(!table_exists("blog_content")){
	create_table("blog_content");
}

if(!table_exists("blog_comments")){
	create_table("blog_comments");
}


# site_pages
//#---------------------------------------------------------------------
if ( !table_exists("site_pages")){
	create_table("site_pages");
	mysql_query("INSERT INTO site_pages (page_name,url_name,type,custom_menu,sub_pages,sub_page_of,password,main_menu,link,username,splash,bgcolor,title,description,template,content,content_regen) VALUES('Home','Home','Main','','','','~~~SEP~~~CON','1','2459444338','','','','Welcome to $this_ip','$this_ip','','','')");
	mysql_query("INSERT INTO site_pages (page_name,url_name,type,custom_menu,sub_pages,sub_page_of,password,main_menu,link,username,splash,bgcolor,title,description,template,content,content_regen) VALUES('Search','http://".$_SESSION['this_ip']."/search.php','menu','','','','','0','http://".$_SESSION['this_ip']."/search.php','','','','','','','','')");
	if(!file_exists($_SESSION['doc_root'].'/sohoadmin/tmp_content/Home.regen')){
		$homeregencontent = '<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG style="CURSOR: move" height=15 hspace=0 src="images/text_header.gif" width=199 align=left border=0><BR clear=all>
<TABLE style="BORDER-RIGHT: black 1px inset; BORDER-TOP: black 1px inset; BORDER-LEFT: black 1px inset; BORDER-BOTTOM: black 1px inset" cellSpacing=0 cellPadding=1 border=0>
<TBODY>
<TR>
<TD vAlign=top align=middle width=199>
<DIV class=TXTCLASS id=NEWOBJ12911554 onclick="startEditor(this.id);" align=left><blink>
<P><FONT face=geneva,arial,sans-serif color=#008000><FONT color=#000080><FONT face="georgia, geneva, arial, sans-serif" color=#0080ff size=4><STRONG>Welcome to your new website!</STRONG> </FONT></FONT></P><STRONG>
<HR>
</STRONG></FONT>
<P><FONT face=verdana,geneva,arial,sans-serif color=#804000><FONT size=2><FONT face=geneva,arial,sans-serif color=#000000>Each of the pages you selected in the Wizard have been created and are now ready for you to edit. In many cases, pages have been populated with generic text and styles to help give you a head-start on formatting your content.</FONT> </FONT></FONT></P>
<P align=center><FONT color=#ff0000><FONT face="georgia, geneva, arial, sans-serif">If this will be your first website, now is the perfect time to experiment and get acquainted with the various features at your disposal.</FONT> </FONT></P></blink></DIV></TD></TR></TBODY></TABLE><!-- ~~~ -->!~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
<IMG height="100%" src="pixel.gif" width=199 border=0> !~!
0!~!
';
		$rr = fopen($_SESSION['doc_root'].'/sohoadmin/tmp_content/Home.regen', 'w+');
		fwrite($rr, $homeregencontent);
		fclose($rr);
	}
	
	if(!file_exists($_SESSION['doc_root'].'/sohoadmin/tmp_content/Home.con')){
		$homeconcontent = '<table border="0" cellpadding="1" cellspacing="0" width="100%" align="center">
 
<!-- Content Row 2 ----------------------------------------- -->

<tr>
     <td align=center valign=top width=100% colspan=3><div align=center>

	<table border=0 cellpadding=0 cellspacing=0 width=100% align=center>
	 <tr>
	  <td align=left valign=top class=sohotext width=100%>   	
		<P><FONT face=geneva,arial,sans-serif color=#008000><FONT color=#000080><FONT face="georgia, geneva, arial, sans-serif" color=#0080ff size=4><STRONG>Welcome to your new website!</STRONG> </FONT></FONT></P><STRONG>
		<HR>
		</STRONG></FONT>
		<P><FONT face=verdana,geneva,arial,sans-serif color=#804000><FONT size=2><FONT face=geneva,arial,sans-serif color=#000000>Each of the pages you selected in the Wizard have been created and are now ready for you to edit. In many cases, pages have been populated with generic text and styles to help give you a head-start on formatting your content.</FONT> </FONT></FONT></P>
		<P align=center><FONT color=#ff0000><FONT face="georgia, geneva, arial, sans-serif">If this will be your first website, now is the perfect time to experiment and get acquainted with the various features at your disposal.</FONT> </FONT></P>
	  </td>
	 </tr>
	</table>
          

</div>
</td>
</tr>
        
</table>
';
		$rr = fopen($_SESSION['doc_root'].'/sohoadmin/tmp_content/Home.con', 'w+');
		fwrite($rr, $homeconcontent);
		fclose($rr);
	}
	
}

if(!table_exists('form_submissions')){
	create_table('form_submissions');
}

if(!table_exists('calendar_category')){
	create_table('calendar_category');
}

if(!table_exists('calendar_display')){
	create_table('calendar_display');
	$data = array();
	$data['TEXT_COLOR'] = '#FFFFFF';
	$data['BACKGROUND_COLOR'] = '#708090';
	$data['ALLOW_PERSONAL_CALENDARS'] = 'N';
	$data['DISPLAY_STYLE'] = 'M';
	$data['ALLOW_PUBLIC_SUBMISSIONS'] = 'N';
	$data['EMAIL_CONFIRMATION'] = '';
	$data['FUTURE1'] = '';
	$data['FUTURE2'] = '';
	$myqry = new mysql_insert("calendar_display", $data);
	if(!$myqry->insert()){
	//	echo mysql_error();
	}

}

if(!table_exists('calendar_events')){
	create_table('calendar_events');
}

if(!table_exists('campaign_manager')){
	create_table('campaign_manager');
}

if(!table_exists('cart_authorize')){
	create_table('cart_authorize');
	mysql_query("INSERT INTO cart_authorize VALUES(' ',' ',' ')");
}

if(!table_exists('cart_category')){
	create_table('cart_category');
}

if(!table_exists('cart_comments')){
	create_table('cart_comments');
}

if(!table_exists('cart_coupons')){
	create_table('cart_coupons');
}

if(!table_exists('cart_customers')){
	create_table('cart_customers');
}

if(!table_exists('cart_dps')){
	create_table('cart_dps');
	mysql_query("INSERT INTO cart_dps VALUES(' ',' ',' ',' ')");
}

if(!table_exists('cart_eway')){
	create_table('cart_eway');
	mysql_query("INSERT INTO cart_eway VALUES(' ',' ',' ',' ')");
}

if(!table_exists('cart_innovgate')){
	create_table('cart_innovgate');
	mysql_query("INSERT INTO cart_innovgate VALUES(' ',' ',' ',' ')");
}

if(!table_exists('cart_invoice')){
	create_table('cart_invoice');
}

if(!table_exists('cart_options')){
	create_table('cart_options');
	$data = array();
	$data['PAYMENT_CREDIT_CARDS'] = 'Visa;Mastercard;Amex;';
	$data['PAYMENT_CHECK_ONLY'] = '';
	$data['PAYMENT_CATALOG_ONLY'] = 'y';
	$data['PAYMENT_PROCESSING_TYPE'] = '';
	$data['PAYMENT_CURRENCY_TYPE'] = 'USD';
	$data['PAYMENT_CURRENCY_SIGN'] = '$';
	$data['PAYMENT_VPARTNERID'] = '';
	$data['PAYMENT_VLOGINID'] = '';
	$data['PAYMENT_INCLUDE'] = '';
	$data['PAYMENT_SSL'] = '';
	$data['BIZ_PAYABLE'] = '';
	$data['BIZ_ADDRESS_1'] = '';
	$data['BIZ_ADDRESS_2'] = '';
	$data['BIZ_CITY'] = '';
	$data['BIZ_STATE'] = '';
	$data['BIZ_POSTALCODE'] = '';
	$data['BIZ_COUNTRY'] = 'UNITED STATES - US';
	$data['BIZ_PHONE'] = '';
	$data['BIZ_VERIFY_COMMENTS'] = $_SESSION['PHP_AUTH_USER'];
	$data['BIZ_EMAIL_NOTICE'] = $_SESSION['PHP_AUTH_USER'];
	$data['BIZ_INVOICE_HEADER'] = 'Thank you for your order!';
	$data['DISPLAY_HEADERBG'] = '#708090';
	$data['DISPLAY_HEADERTXT'] = '#F5F5F5';
	$data['DISPLAY_CARTBG'] = '#F5F5F5';
	$data['DISPLAY_CARTTXT'] = '#000000';
	$data['DISPLAY_WELCOME'] = '';
	$data['DISPLAY_RESULTS'] = '20';
	$data['DISPLAY_RESULTSORT'] = 'PROD_NAME';
	$data['DISPLAY_COLPLACEMENT'] = 'L';
	$data['DISPLAY_SEARCH'] = 'Y';
	$data['DISPLAY_USERBUTTON'] = 'More Information';
	$data['DISPLAY_ADDCARTBUTTON'] = 'Y';
	$data['DISPLAY_LOGINBUTTON'] = 'N';
	$data['DISPLAY_CATEGORIES'] = 'Y';
	$data['DISPLAY_COMMENTS'] = 'N';
	$data['DISPLAY_EMAILFRIEND'] = 'N';
	$data['DISPLAY_REMEMBERME'] = 'Y';
	$data['DISPLAY_STATE'] = 'uscanmenu';
	$data['DISPLAY_ZIP'] = 'zippostal';
	$data['DISPLAY_REQUIRED'] = '~BZIPCODE~SZIPCODE~';
	$data['INVOICE_INCLUDE'] = '';
	$data['LOCAL_COUNTRY'] = 'No Default Country';
	$data['CHARGE_VAT'] = 'no';
	$data['VAT_REG'] = 'vatnum';
	$data['CSS'] = 'a:2:{s:13:"table_bgcolor";s:11:"transparent";s:15:"table_textcolor";s:7:"#336699";}';
	$data['GOTO_CHECKOUT'] = 'no';

	$myqry = new mysql_insert("cart_options", $data);
	if( !$myqry->insert() ) {
//		echo mysql_error();
	}

}

if(!table_exists("cart_products_usersignup")){
	create_table("cart_products_usersignup");
}

if(!table_exists("cart_paypal")){
	create_table("cart_paypal");
	mysql_query("INSERT INTO cart_paypal (PAYPAL_EMAIL,PAYPAL_USER1,PAYPAL_USER2,PAYPAL_USER3) VALUES(' ',' ',' ',' ')");
}

if(!table_exists('cart_paypoint')){
	create_table('cart_paypoint');
	mysql_query("INSERT INTO cart_paypoint VALUES(' ',' ',' ',' ')");
}

if(!table_exists('cart_paypro')){
	create_table('cart_paypro');
	mysql_query("INSERT INTO cart_paypro VALUES(' ',' ',' ')");
}

if(!table_exists("cart_paystation")){
	create_table("cart_paystation");
	mysql_query("INSERT INTO cart_paystation VALUES(' ',' ')");
}

if(!table_exists('cart_products')){
	create_table('cart_products');
}

if(!table_exists('cart_shipping_opts')){
	create_table('cart_shipping_opts');
	$offline_notice = "Please note that applicable shipping charges are not included in the total shown below. ";
	$offline_notice .= "Your final invoice, including shipping costs, will be emailed to you shortly after you complete your purchase. ";
	$offline_notice .= "Once you have approved the final total, your order will be processed immediately.";
	$qry = "INSERT INTO cart_shipping_opts (SHIP_METHOD, ST_GTHAN1, NOTICE, order_type)";
	$qry_vals_local = " VALUES('Standard', '0.01', '".$offline_notice."', 'local')";
	$qry_vals_intl = " VALUES('Standard', '0.01', '".$offline_notice."', 'intl')";
	mysql_query($qry.$qry_vals_local);
	mysql_query($qry.$qry_vals_intl);
}

if(!table_exists('cart_tax')){
	create_table('cart_tax');
}

if(!table_exists('cart_vat')){
	create_table('cart_vat');
}

if(!table_exists('cart_worldpay')){
	create_table('cart_worldpay');
	mysql_query("INSERT INTO cart_worldpay VALUES(' ',' ',' ',' ')");
}

if(!table_exists('faq_category')){
	create_table('faq_category');
}

if(!table_exists('faq_content')){
	create_table('faq_content');
}

if(!table_exists('form_fields')){
	create_table('form_fields');
}

if(!table_exists('form_properties')){
	create_table('form_properties');
}

if(!table_exists('ip_bans')){
	create_table('ip_bans');
}

if(!table_exists('login_attempts')){
	create_table('login_attempts');
}

if(!table_exists('login_bans')){
	create_table('login_bans');
}

if(!table_exists('login_history')){
	create_table('login_history');
}

if(!table_exists('photo_album_images')){
	create_table('photo_album_images');
}


if(!table_exists('photo_album')){
	create_table('photo_album');
}

# promo_boxes
#--------------------------------------------------------------------
if(!table_exists("promo_boxes")){
	create_table("promo_boxes");
	for ( $x=1; $x<=25; $x++ ) {
	   # DEFAULT: Insert default box records
	   $data = array();
	   $box = "box".$x;
	   $content = "a:3:{s:7:\"content\";s:11:\"Latest News\";s:7:\"display\";s:2:\"on\";s:4:\"type\";s:6:\"latest\";}";
	   $data['BOX'] = $box;
	   $data['CONTENT'] = $content;
	   $data['NUM_DISPLAY'] = "a:2:{s:4:\"blog\";s:0:\"\";s:5:\"chars\";s:0:\"\";}";
	   $data['DISP_TITLE'] = "a:3:{s:7:\"display\";s:2:\"on\";s:5:\"align\";s:4:\"left\";s:6:\"weight\";s:4:\"bold\";}";
	   $data['DISP_CONTENT'] = "a:1:{s:7:\"display\";s:2:\"on\";}";
	   $data['DISP_DATE'] = "a:6:{s:7:\"display\";s:2:\"on\";s:9:\"fontStyle\";s:2:\"on\";s:6:\"weight\";s:6:\"normal\";s:6:\"format\";s:4:\"full\";s:8:\"position\";s:8:\"dateLast\";s:5:\"align\";s:4:\"left\";}";
	   $data['DISP_MORE'] = "a:4:{s:7:\"display\";s:2:\"on\";s:4:\"text\";s:12:\"Read more...\";s:5:\"align\";s:4:\"left\";s:6:\"weight\";s:6:\"normal\";}";
	   $data['SETTINGS'] = "a:1:{s:8:\"template\";s:2:\"on\";}";
	   $data['content_type'] = "blog";
	   $data['content_src'] = "Latest News";
	   $myqry = new mysql_insert("promo_boxes", $data);
	   if( !$myqry->insert() ) {
	//         echo mysql_error();
	   }
	}
	# DEFAULT: Oldschool News/promo box default category id's
	$data = array();
	$data['BOX'] = "newsbox";
	$data['CONTENT'] = "1";
	$myqry = new mysql_insert("promo_boxes", $data);
	if( !$myqry->insert() ) {
	//      echo mysql_error();
	}
	$data = array();
	$data['BOX'] = "promobox";
	$data['CONTENT'] = "2";
	$myqry = new mysql_insert("promo_boxes", $data);
	if( !$myqry->insert() ){
	//      echo mysql_error();
	}
}

if(!table_exists('qty_discounts')){
	create_table('qty_discounts');
}

# Create search options table if doesn't already exist
if(!table_exists("search_core")){
	create_table("search_core");
   # Insert default display settings
   $qry = "insert into search_core (";
   $qry .= "prikey, template, results_per_page, show_search_type, search_button_text, custom_button";
   $qry .= ", display_percent, percent_text, display_hits, hits_text, allow_template_search, min_word_chars";
   $qry .= ", style1, style2, style3, style4, search_field_label, display_search_within_results, display_match_exact_phrase, link_color";
   $qry .= ", link_hover, stats, display_number";
   $qry .= ") ";
   $qry .= "values (";
   $qry .= "'', '', '25', '', 'Search', ''";
   $qry .= ", 'yes', '', 'yes', '', '', '3'";
   $qry .= ", 'FF000', '', '', '13', '', 'no', 'yes', ''";
   $qry .= ", '', 'yes', 'yes'";
   $qry .= ")";
   if ( !mysql_query($qry) ) {
//      echo mysql_error(); exit;
   }
}

if(!table_exists("search_stats")){
	create_table("search_stats");
}

if(!table_exists("search_contents")){
	create_table("search_contents");
}

if(!table_exists("sec_codes")) {
	create_table("sec_codes");
}

if(!table_exists('sec_users')){
	create_table('sec_users');
}

if(!table_exists('simple_chat')){
	create_table('simple_chat');
}

if(!table_exists('site_backup')){
	create_table('site_backup');
}

if(!table_exists('smt_userimages')){
	create_table('smt_userimages');
}

if(!table_exists('stats_browser')){
	create_table('stats_browser');
}

if(!table_exists('stats_byday')){
	create_table('stats_byday');
}

if(!table_exists('stats_byhour')){
	create_table('stats_byhour');
}


if(!table_exists('stats_refer')){
	create_table('stats_refer');
}

if(!table_exists('stats_top25')){
	create_table('stats_top25');
}

if(!table_exists('stats_unique')){
	create_table('stats_unique');
}

if(!table_exists('system_hook_attachments')){
	create_table('system_hook_attachments');
}

if(!table_exists('system_plugins')){
	create_table('system_plugins');
}

if(!table_exists('unsubscribe')){
	create_table('unsubscribe');
}

if(!table_exists('user_access_rights')){
	create_table('user_access_rights');
}

if(!table_exists("UDT_CONTENT_SEARCH_REPLACE")){
	create_table("UDT_CONTENT_SEARCH_REPLACE");
}

?>