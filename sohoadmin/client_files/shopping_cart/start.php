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


error_reporting('341');
session_cache_limiter('none');
session_start();
track_vars;

include_once("pgm-cart_config.php");
$dot_com = $this_ip;	// Assign dot_com variable to configured ip address

foreach($_REQUEST as $name=>$value){
	$value = htmlspecialchars($value);	// Bugzilla #13
	${$name} = $value;	
}

if($cat != ''){
	if(preg_match('/[^0-9]/', $cat)){
		$cat = '';
		$_REQUEST['cat'] = '';
		$_GET['cat'] = '';
		$_POST['cat'] = '';
	}	
}


//if($_REQUEST['cat'] != ''){
//	$cat = htmlspecialchars($_REQUEST['subcat']);
//	$_POST['subcat'] = $cat;
//	$_GET['subcat'] = $cat;
//	$_REQUEST['cat'] = $cat;
//}

$THIS_DISPLAY = "";
$instant_browse_flag = 0;	// Used if Keyword Search returns 0 matches

if($_REQUEST['cat'] == '' && $_REQUEST['browse'] == '' && $_REQUEST['searchfor'] == '' && $_REQUEST['policy'] == ''){
	$browse = 1;
}

include_once('pull-policies.inc.php');

##########################################################################
### LET'S MAKE SURE TO RETURN END-USER BACK TO PREVIOUS SEARCH REQUEST
### WHEN THEY ADD SOMETHING TO THEIR CART AND DECIDE TO "CONTINUE
### SHOPPING" AGAIN... THIS HAS BEEN AN OVERWHELMING REQUEST FROM SHOPPERS
##########################################################################
$_SESSION['cont_shopping_string'] = "?";

reset($_POST);
while (list($name, $value) = each($_POST)) {
   $_SESSION['cont_shopping_string'] .= "$name=$value&";
}
reset($_GET);
while (list($name, $value) = each($_GET)) {
   $_SESSION['cont_shopping_string'] .= "$name=$value&";
}

$cartpref = new userdata("cart");
$category_customsort='';
if($cartpref->get("category_customsort")=='custom'){
	$category_customsort='custom';
}

if($category_customsort=='custom'){
	$getcats = mysql_query("SELECT * FROM cart_category ORDER BY sortorder ASC, category ASC");
} else {
	$getcats = mysql_query("SELECT * FROM cart_category ORDER BY category ASC");
}

//$getcats = mysql_query("SELECT * FROM cart_category ORDER BY category");
while ($rowz = mysql_fetch_array ($getcats)) {
	if (strlen($rowz['category']) > 2) {
		$catz[$rowz['keyfield']]= array('name'=>$rowz['category'], 'level'=>$rowz['level'], 'subs'=>$rowz['subs'], 'parent'=>$rowz['parent'], 'product_count'=>$rowz['product_count']);
	}
}


##########################################################################
### STEP 1:
### WE WILL NEED TO KNOW THE DATABASE NAME; UN; PW; ETC TO OPERATE THE
### REAL-TIME EXECUTION.  THIS IS CONFIGURED IN THE isp.conf FILE
##########################################################################

$test_lang = lang("Search Results For");

if ( strlen($test_lang) < 4 ) { // $lang array is either empty or about to display arifacts, so reload it

   // Register language settings from site_specs table
   // ----------------------------------------------------
   $selSpecs = mysql_query("SELECT * FROM site_specs");
   $getSpec = mysql_fetch_array($selSpecs);

   if ( $getSpec[df_lang] == "" ) {
      $language = "english.php";
      //echo "getSpec[df_lang] = ($getSpec[df_lang])\n";
      //exit;

   } else {
      $language = $getSpec[df_lang];
   }

   if ( $lang_dir != "" ) {
      $lang_include = $lang_dir."/".$language;
   } else {
      $lang_include = "../sohoadmin/language/$language";
   }

   include_once("$lang_include");

	$_SESSION['getSpec'] = $getSpec;
	$_SESSION['language'] = $language;
	foreach($lang as $lvar=>$lval){
		$_SESSION['lang'][$lvar]=$lval;
	}

}


##########################################################################
### STEP 2:
### READ DATABASED OPTIONS INTO MEMORY NOW
##########################################################################

$result = mysql_query("SELECT * FROM cart_options");
$OPTIONS = mysql_fetch_array($result);

//040406 - Pull Currency Info
$dSign = $OPTIONS[PAYMENT_CURRENCY_SIGN];
$dType = $OPTIONS[PAYMENT_CURRENCY_TYPE];

# Restore css styles array
$getCss = unserialize($OPTIONS['CSS']);

##########################################################################
### STEP 3:
### IS THIS A KEYWORD SEARCH?  IF SO, PROCESS REQUEST NOW
##########################################################################

if ($find == 1) {

	// ------------------------------------------------------------------
	// This is a keyword search request ($searchfor holds keyword data)
	// First, lets parse any extra bullshit that the user may have
	// submitted along with his/her search -- they will do wierd shit
	// ------------------------------------------------------------------

	$searchfor = ltrim($searchfor);				// trim extra spaces from left of words
	$searchfor = rtrim($searchfor);				// trim extra spaces from end of words

	$searchfor = str_replace(",", " ", $searchfor);		// replace commas with a space
	$searchfor = str_replace(";", " ", $searchfor);		// replace semi-colons with a space
	$searchfor = str_replace("  ", " ", $searchfor);	// replace multiple spaces with a single space

	$SEARCH_KEYWORDS = split(" ", $searchfor);		// split keywords into an array
	$NUM_SEARCH_KEYS = count($SEARCH_KEYWORDS);		// get a count of the number of words entered

	$tmp_sql_string = "SELECT PRIKEY, PROD_SKU, PROD_CATNO, PROD_NAME, PROD_DESC, PROD_UNITPRICE, PROD_CATEGORY1, PROD_CATEGORY2, PROD_CATEGORY3, ";
	$tmp_sql_string .= "VARIANT_PRICE1, VARIANT_PRICE2, VARIANT_PRICE3, VARIANT_PRICE4, VARIANT_PRICE5, ";
	$tmp_sql_string .= "VARIANT_PRICE6, OPTION_DETAILPAGE, PROD_THUMBNAIL, PROD_FULLIMAGENAME, OPTION_KEYWORDS, ";
	$tmp_sql_string .= "OPTION_SECURITYCODE, OPTION_RECOMMENDSKU, sub_cats, variant_names, variant_prices, num_variants FROM cart_products WHERE ";

	$keyflag = 0;	// Set keyword flag to zero by default

	for ($x=0;$x<=$NUM_SEARCH_KEYS;$x++) {

		if ($SEARCH_KEYWORDS[$x] != "") {

			$SEARCH_KEYWORDS[$x] = strtoupper($SEARCH_KEYWORDS[$x]);	// Force search to be case-insensitive

			$tmp_sql_string .= "(UPPER(PROD_SKU) LIKE '%$SEARCH_KEYWORDS[$x]%' OR UPPER(PROD_CATNO) LIKE '%$SEARCH_KEYWORDS[$x]%' OR ";
			$tmp_sql_string .= "UPPER(PROD_NAME) LIKE '%$SEARCH_KEYWORDS[$x]%' OR UPPER(PROD_DESC) LIKE '%$SEARCH_KEYWORDS[$x]%' OR ";
			if(mysql_get_client_info() >= 4) {
				$tmp_sql_string .= "CAST(OPTION_KEYWORDS as char) LIKE '%$SEARCH_KEYWORDS[$x]%') AND ";
			} else {
				$tmp_sql_string .= "UPPER(OPTION_KEYWORDS) LIKE '%$SEARCH_KEYWORDS[$x]%') AND ";			
			}

			$keyflag = 1; 	// Let next routine know that we actually found keywords; in case of all search;
		}

	}

	// Kill extra "OR" or "WHERE" tag in sql string that is left over from loop or flag
	// ----------------------------------------------------------------------------------

	$tmp = strlen($tmp_sql_string);

	if ($keyflag == 1) {

		$parse = $tmp - 4;	// Parse last AND in keyword loop
		$KEYFLAGTXT = "AND";	// Adding extra control to NOT call products with no category or display off

	} else {

		$parse = $tmp - 6;	// Parse WHERE
		$KEYFLAGTXT = "WHERE"; // Adding extra control to NOT call products with no category or display off

	}

	$tmp_sql_string = substr($tmp_sql_string, 0, $parse);

	$tmp_sql_string .= " $KEYFLAGTXT (OPTION_DISPLAY <> 'N') ";

	// ----------------------------------------------------------------------------------
	// ADD SECURITY CODE [GROUPS] CONTROL OVER SEARCH RESULTS
	// ----------------------------------------------------------------------------------

	if (isset($GROUPS)) {

		$grp_check = "HAVING OPTION_SECURITYCODE IN (";

		$grp_tmp = split(";", $GROUPS);	// Split this user's sec code groups into individual array
		$grp_cnt = count($grp_tmp);		// How Many sec groups does this user have access to?

		for ($gl=0;$gl<=$grp_cnt;$gl++) {	// Start to build SQL "IN" cluster
			if ($grp_tmp[$gl] != "") {
				$grp_check .= "'$grp_tmp[$gl]', ";
			}
		}

		$grp_check .= "'Public')";

	} else {

		$grp_check = "HAVING OPTION_SECURITYCODE IN('Public')";

	}


	$tmp_sql_string .= "$grp_check";

	// ----------------------------------------------------------------------------------

	$result = mysql_query("$tmp_sql_string ORDER BY $OPTIONS[DISPLAY_RESULTSORT]");


	$TOTAL_FOUND = mysql_num_rows($result);	// Denote how many results where returned on query

	$searchfor = ucwords($searchfor);
	$display_searchfor = $searchfor;
	if ($searchfor == "") { $display_searchfor = "All Products"; }

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// Determine the Prev/Next Function
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	if ($start == "") { $start = 1; }
	if ($end == "") { $end = $OPTIONS[DISPLAY_RESULTS]; }
	if ($TOTAL_FOUND < $end) { $end = $TOTAL_FOUND; }

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	$THIS_DISPLAY .= "<CENTER><TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=0 class=text>\n";
	$THIS_DISPLAY .= "<TR><TD ALIGN=LEFT VALIGN=MIDDLE>".lang("Search Results For").": <U>$display_searchfor</U></TD>\n";
	$THIS_DISPLAY .= "<TD ALIGN=RIGHT VALIGN=MIDDLE>\n";

	if ($TOTAL_FOUND != 0) {
		$THIS_DISPLAY .= lang("Displaying")." $start-$end of ";
	}

	$THIS_DISPLAY .= "$TOTAL_FOUND ".lang("Matches Found").".\n";
	$THIS_DISPLAY .= "</TD></TR></TABLE>\n\n";

	// -----------------------------------------------------------------
	// Set up button click Javascript action for linking to the
	// "More Info" page. (Used with the User defined Button and the
	// Buy Now! button. (Buy Now button is changed to "Add to Cart"
	// if that option is on.
	// -----------------------------------------------------------------

	$THIS_DISPLAY .= "<SCRIPT LANGUAGE=Javascript>\n<!--\n\n";
	$THIS_DISPLAY .= "     function userbutton(prikey) {\n";
	$THIS_DISPLAY .= "          var strlink = \"pgm-more_information.php?id=\"+prikey+\"&=SID#MOREINFO\";\n";
	$THIS_DISPLAY .= "          window.location = strlink;\n";
	$THIS_DISPLAY .= "     }\n\n";
	$THIS_DISPLAY .= "// -->\n</SCRIPT>\n\n";

	// -----------------------------------------------------------------
	// Loop Through Query Results and place inside of the product
	// search template.
	// -----------------------------------------------------------------
	
   if($OPTIONS['DISPLAY_RESULTSORT'] != "PROD_UNITPRICE"){
      # Sorting NOT based on price
   	$count = 1;	// Keep Number to Display on Search in Count Var
   
   	while ($PROD = mysql_fetch_array($result)) {
   
			if ($count >= $start && $count <= $end) {

				ob_start();
				include("prod_search_template.inc");
				$THIS_DISPLAY .= ob_get_contents();
				ob_end_clean();

			}

			$count++;
   
   	}
   }else{
      # Sorting based on price
      # array for pre prod sort
      $unsorted_prods = array();
   	while ($PROD = mysql_fetch_array($result)) {
   	   if(strpos($PROD['PROD_UNITPRICE'],".") === false){
   	      $curr_num = $PROD['PROD_UNITPRICE'].".".$count;
   	   }else{
   	      $curr_num = $PROD['PROD_UNITPRICE'].$count;
   	   }
   	   $unsorted_prods[$curr_num] = $PROD;
   	}
   	
   	# Sort prods by key (unit price is key)
   	ksort($unsorted_prods);
   	
   	$count = 1;	// Keep Number to Display on Search in Count Var
      foreach($unsorted_prods as $k=>$v){
         $PROD = $v;
         //echo "var = (".$k.") val = (".$v.")<br>\n";
   		if ($count >= $start && $count <= $end) {
   
   			ob_start();
   			include("prod_search_template.inc");
   			$THIS_DISPLAY .= ob_get_contents();
   			ob_end_clean();
   
   		}
   
   		$count++;
      }
   }

	if ($TOTAL_FOUND == 0) {

		$THIS_DISPLAY .= "<TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=10 class=text>\n";
		$THIS_DISPLAY .= "<TR><TD ALIGN=CENTER VALIGN=MIDDLE><FONT COLOR=RED>\n";
		$THIS_DISPLAY .= "<B>".lang("Sorry, no products were found that match your search criteria.")."<BR>".lang("Please try again or browse the suggested selections below.")."\n";
		$THIS_DISPLAY .= "</TD></TR></TABLE>\n\n";

		$instant_browse_flag = 1;	// Tell Browse Function we're coming...
		$browse = 1;			// Since Nothing was found; might as well browse

	} else {

		$THIS_DISPLAY .= "<TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=5 class=text>\n";
		$THIS_DISPLAY .= "<TR><TD ALIGN=CENTER VALIGN=MIDDLE>\n";

		if ($TOTAL_FOUND != 0) {
			$THIS_DISPLAY .= lang("Displaying")." $start-$end of ";
		}

		$THIS_DISPLAY .= "$TOTAL_FOUND ".lang("Matches Found").".\n";
		$THIS_DISPLAY .= "</TD></TR></TABLE>\n\n";

		$tmp = $start;

		$THIS_DISPLAY .= "<TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=5 class=text>\n";
		$THIS_DISPLAY .= "<TR>\n";

			$tend = $start - 1;
			$tstart = $tend - ($OPTIONS[DISPLAY_RESULTS] - 1);
			if ($tmp > 1) {

				if ($tstart == 0) { $tstart = 1; }

				$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE>\n";

				$THIS_DISPLAY .= "<FORM METHOD=POST ACTION=start.php>\n";
				$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=searchfor VALUE=\"$searchfor\">\n";
				$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=find VALUE=1>\n";
				$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=start VALUE=$tstart>\n";
				$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=end VALUE=$tend>\n";
				$THIS_DISPLAY .= "<INPUT TYPE=SUBMIT VALUE=\"&lt;&lt; ".lang("PREV")."\" CLASS=FormLt1>\n";
				$THIS_DISPLAY .= "</FORM>\n";

				$THIS_DISPLAY .= "</TD>\n";

			}

			$tstart = $end + 1;
			$tend = $tstart + $OPTIONS[DISPLAY_RESULTS] - 1;

			if ($TOTAL_FOUND > $end) {

				if ($TOTAL_FOUND <= $tend) { $tend = $TOTAL_FOUND; }

				$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE>\n";

				$THIS_DISPLAY .= "<FORM METHOD=POST ACTION=start.php>\n";
				$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=searchfor VALUE=\"$searchfor\">\n";
				$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=find VALUE=1>\n";
				$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=start VALUE=$tstart>\n";
				$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=end VALUE=$tend>\n";
				$THIS_DISPLAY .= "<INPUT TYPE=SUBMIT VALUE=\"".lang("NEXT")." &gt;&gt;\" CLASS=FormLt1>\n";
				$THIS_DISPLAY .= "</FORM>\n";

				$THIS_DISPLAY .= "</TD>\n";

			}

		$THIS_DISPLAY .= "</TR></TABLE>\n\n";

	}

} // End $find == 1 Statement

##########################################################################
### STEP 4:
### IS THIS A "BROWSE" REQUEST? IF SO, PROCESS NOW.
###
### DEVNOTE: A BROWSE REQUEST MUST BE DENOTED BY A browse=1 GET OR POST
### VARIABLE WHEN CALLING THIS FUNCTION. OPTIONALLY, THE cat=2 VARIABLE
### CAN BE INCLUDED TO BROWSE THROUGH A SPECIFIC CATEGORY NUMBER. IF NO
### CATEGORY NUMBER IS PASSED, WE ASSUME BROWSING OF "ALL" SKUS.
##########################################################################

if ($browse == 1) {

	$tmp_sql_string = "SELECT PRIKEY, PROD_SKU, PROD_CATNO, PROD_NAME, PROD_DESC, PROD_UNITPRICE, PROD_CATEGORY1, ";
	$tmp_sql_string .= "VARIANT_PRICE1, VARIANT_PRICE2, VARIANT_PRICE3, VARIANT_PRICE4, VARIANT_PRICE5, ";
	$tmp_sql_string .= "VARIANT_PRICE6, OPTION_DETAILPAGE, PROD_THUMBNAIL, PROD_FULLIMAGENAME, OPTION_KEYWORDS, ";
	$tmp_sql_string .= "OPTION_SECURITYCODE, OPTION_RECOMMENDSKU, sub_cats, variant_names, variant_prices, num_variants FROM cart_products WHERE ";

	if ($cat != "") {

		$pass_cat = $cat; // For use with Next/Prev Buttons
		if($catz[$cat]['subs'] != ''){
			$catzsubs = explode(',', $catz[$cat]['subs']);
			$subcatsstring = '';
			foreach($catzsubs as $csubzval){
				$subcatsstring .= "PROD_CATEGORY1 = '".$csubzval."' OR ";
				$subcatsstring .= "PROD_CATEGORY2 = '".$csubzval."' OR ";
				$subcatsstring .= "PROD_CATEGORY3 = '".$csubzval."' OR ";
				if($catz[$csubzval]['subs'] != ''){
					$catzsubssubs = explode(',', $catz[$csubzval]['subs']);
					foreach($catzsubssubs as $csubsubval){
						$subcatsstring .= "PROD_CATEGORY1 = '".$csubsubval."' OR ";
						$subcatsstring .= "PROD_CATEGORY2 = '".$csubsubval."' OR ";
						$subcatsstring .= "PROD_CATEGORY3 = '".$csubsubval."' OR ";
					}
				}
			}
			$subcatsstring = preg_replace('/ OR $/', '', $subcatsstring);
			$tmp_sql_string .= "(PROD_CATEGORY1 = '$cat' OR PROD_CATEGORY2 = '$cat' OR PROD_CATEGORY3 = '$cat' OR ".$subcatsstring.") AND OPTION_DISPLAY = 'Y' ";
		} else {
			$tmp_sql_string .= "(PROD_CATEGORY1 = '$cat' OR PROD_CATEGORY2 = '$cat' OR PROD_CATEGORY3 = '$cat') AND OPTION_DISPLAY = 'Y' ";	
//			$tmp_sql_string .= "(PROD_CATEGORY1 = '$cat' OR PROD_CATEGORY2 = '$cat' OR PROD_CATEGORY3 = '$cat') AND OPTION_DISPLAY = 'Y' ";
		}

		

		// --------------------------------------------------------------
		// Also, get the category REAL NAME for display in search results
		// --------------------------------------------------------------

			$catname = mysql_query("SELECT category FROM cart_category WHERE keyfield = '$cat'");
			$this_cat = mysql_fetch_array($catname);
			
			$BROWSECAT = "&nbsp;<a href=\"start.php?browse=1\">".lang('Home')."</a>&nbsp;";

			if(preg_match('/[0-9]+/', $cat)){
				if($catz[$cat]['parent'] != ''){
					if($catz[$catz[$cat]['parent']]['parent'] != ''){
						$BROWSECAT .= "&nbsp;&gt;&nbsp;<a href=\"start.php?browse=1&cat=".$catz[$catz[$cat]['parent']]['parent']."&=SID\">".$catz[$catz[$catz[$cat]['parent']]['parent']]['name']."</a>";;
					}
					
					$BROWSECAT .= "&nbsp;&gt;&nbsp;<a href=\"start.php?browse=1&cat=".$catz[$cat]['parent']."&=SID\">".$catz[$catz[$cat]['parent']]['name']."</a>";;
				}
				
				$BROWSECAT .= "&nbsp;&gt;&nbsp;<a href=\"start.php?browse=1&cat=".$cat."&=SID\">".$this_cat['category']."</a>";;
			}
		// --------------------------------------------------------------

	} else {

		$pass_cat = ""; // For use with Next/Prev Buttons

		//$tmp_sql_string .= "PROD_CATEGORY1 <> ' ' AND OPTION_DISPLAY <> 'N' ";
		$tmp_sql_string .= "OPTION_DISPLAY <> 'N' ";
		$BROWSECAT = "Browsing Category: All";	// Because no category is defined, we are searching all

	}

	// ----------------------------------------------------------------------------------
	// ADD SECURITY CODE [GROUPS] CONTROL OVER SEARCH RESULTS
	// ----------------------------------------------------------------------------------

	if (isset($GROUPS)) {

		$grp_check = " HAVING OPTION_SECURITYCODE IN (";

		$grp_tmp = split(";", $GROUPS);	// Split this user's sec code groups into individual array
		$grp_cnt = count($grp_tmp);		// How Many sec groups does this user have access to?

		for ($gl=0;$gl<=$grp_cnt;$gl++) {	// Start to build SQL "IN" cluster
			if ($grp_tmp[$gl] != "") {
				$grp_check .= "'$grp_tmp[$gl]', ";
			}
		}

		$grp_check .= "'Public')";

	} else {

		$grp_check = " HAVING OPTION_SECURITYCODE IN ('Public')";

	}


	$tmp_sql_string .= "$grp_check";

	// --------------------------------------------------------------------------
	// Make SQL Query Now and Sort by the Field Name defined in display options
	// --------------------------------------------------------------------------

	//echo "\n\n<!-- ".$tmp_sql_string." ORDER BY ".$OPTIONS['DISPLAY_RESULTSORT']." \n\n-->\n\n";
	if($OPTIONS[DISPLAY_RESULTSORT] == 'category'){
//		echo "$tmp_sql_string ORDER BY PROD_CATEGORY1 PROD_NAME";
		$result = mysql_query("$tmp_sql_string ORDER BY PROD_CATEGORY1 PROD_NAME");	
	} else {
//		echo "$tmp_sql_string ORDER BY $OPTIONS[DISPLAY_RESULTSORT]";
		$result = mysql_query("$tmp_sql_string ORDER BY $OPTIONS[DISPLAY_RESULTSORT]");	
	}


	$TOTAL_FOUND = mysql_num_rows($result);	// Denote how many results where returned on query

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// Determine the Prev/Next Function
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	if ($start == "") { $start = 1; }
	if ($end == "") { $end = $OPTIONS[DISPLAY_RESULTS]; }
	if ($TOTAL_FOUND < $end) { $end = $TOTAL_FOUND; }

	// --------------------------------------------------------------
	// Build Display Variable
	// --------------------------------------------------------------

	if ($instant_browse_flag != 1) {	// If keyword search returns 0 matches, this flag is turned on

		//$THIS_DISPLAY .= "<CENTER><TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=0 class=text>\n";
		//$THIS_DISPLAY .= "<TR><TD style=\"padding-left:6px;\" ALIGN=LEFT VALIGN=MIDDLE>$BROWSECAT</TD>\n";
		$topnav = "<div class=\"cartnav\">\n";
		$topnav .= $BROWSECAT;
		
		//$THIS_DISPLAY .= "<TD ALIGN=RIGHT VALIGN=MIDDLE>\n";
		$topnav .= "<div class=\"cartnavright\">\n";

		if ($TOTAL_FOUND != 0) {
			$topnav .= lang("Displaying")." $start-$end ".lang("of")." ";
		}

		$topnav .= "$TOTAL_FOUND ".lang("Found").".\n";
		$topnav .= "	</div>\n";
		//$THIS_DISPLAY .= "</TD></TR></TABLE>\n\n";
		
		$topnav .= "</div>\n";
		
		
		
		

	}

	// -----------------------------------------------------------------
	// Set up button click Javascript action for linking to the
	// "More Info" page. (Used with the User defined Button and the
	// Buy Now! button. (Buy Now button is changed to "Add to Cart"
	// if that option is on.
	// -----------------------------------------------------------------

	$THIS_DISPLAY .= "<SCRIPT LANGUAGE=Javascript>\n<!--\n\n";
	$THIS_DISPLAY .= "     function userbutton(prikey) {\n";
	$THIS_DISPLAY .= "          var strlink = \"pgm-more_information.php?id=\"+prikey+\"&=SID#MOREINFO\";\n";
	$THIS_DISPLAY .= "          window.location = strlink;\n";
	$THIS_DISPLAY .= "     }\n\n";
	$THIS_DISPLAY .= "// -->\n</SCRIPT>\n\n";

	// -----------------------------------------------------------------
	// Loop Through Query Results and place inside of the product
	// search template.
	//
	// DEVNOTE: WE TURN THE "OUTPUT BUFFER" OFF WHILE WE LET THE INCLUDE
	// DO ITS WORK AND PLACE THE OUTPUT INTO THE $THIS_DISPLAY VAR.
	// THIS WAY WE CAN MODIFY OUR INCLUDE TO PERFORM JUST LIKE NORMAL
	// HTML AS MUCH AS POSSIBLE.
	// -----------------------------------------------------------------
   
   if($OPTIONS['DISPLAY_RESULTSORT'] != "PROD_UNITPRICE"){
      # Sorting NOT based on price
   	$count = 1;	// Keep Number to Display on Search in Count Var
   
   	while ($PROD = mysql_fetch_array($result)) {
   
			if ($count >= $start && $count <= $end) {

				ob_start();
				include("prod_search_template.inc");
				$THIS_DISPLAY .= ob_get_contents();
				ob_end_clean();

			}

			$count++;
   
   	}
   }else{
      # Sorting based on price
		# array for pre prod sort
		$count = 1;
		$unsorted_prods = array();
   	while ($PROD = mysql_fetch_array($result)) {
   	   if(strpos($PROD['PROD_UNITPRICE'],".") === false){
   	      $curr_num = $PROD['PROD_UNITPRICE'].".".$count;
   	   }else{
   	      $curr_num = $PROD['PROD_UNITPRICE'].$count;
   	   }
   	   $unsorted_prods[$curr_num] = $PROD;
   	   ++$count;
   	}
   	
   	# Sort prods by key (unit price is key)

   	ksort($unsorted_prods, SORT_NUMERIC);
   	$count = 1;	// Keep Number to Display on Search in Count Var
      foreach($unsorted_prods as $k=>$v){
         $PROD = $v;
         //echo "var = (".$k.") val = (".$v.")<br>\n";
   		if ($count >= $start && $count <= $end) {
   
   			ob_start();
   			include("prod_search_template.inc");
   			$THIS_DISPLAY .= ob_get_contents();
   			ob_end_clean();
   
   		}
   
   		$count++;
      }
   }

	if ($TOTAL_FOUND == 0) {

		$THIS_DISPLAY .= "<BR><BR><TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=10 class=text>\n";
		$THIS_DISPLAY .= "<TR><TD ALIGN=CENTER VALIGN=MIDDLE><FONT COLOR=RED>\n";
		$THIS_DISPLAY .= "<B>".lang("Sorry, there are currently no products in this category").".<BR><BR>".lang("Please check back soon").".\n";
		$THIS_DISPLAY .= "</TD></TR></TABLE>\n\n";

	} else {

	// ------------------------------------------------------------------
	// Display Previous And Next Buttons
	// ------------------------------------------------------------------

		$THIS_DISPLAY .= "<TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=5 class=text>\n";
		$THIS_DISPLAY .= "<TR><TD ALIGN=CENTER VALIGN=MIDDLE>\n";

		if ($TOTAL_FOUND != 0) {
			$THIS_DISPLAY .= lang("Displaying")." $start-$end ".lang("of")." ";
		}

		$THIS_DISPLAY .= "$TOTAL_FOUND ".lang("Found").".\n";
		$THIS_DISPLAY .= "</TD></TR></TABLE>\n\n";

		$tmp = $start;

		$THIS_DISPLAY .= "<TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=5 class=text>\n";
		$THIS_DISPLAY .= "<TR>\n";

			$tend = $start - 1;
			$tstart = $tend - $OPTIONS[DISPLAY_RESULTS];

			if ($tmp != 1) {

				if ($tstart == 0) { $tstart = 1; }

				$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE>\n";

				$THIS_DISPLAY .= "<FORM METHOD=POST ACTION=start.php>\n";
				$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=cat VALUE=\"$pass_cat\">\n";
				$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=browse VALUE=1>\n";
				$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=start VALUE=$tstart>\n";
				$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=end VALUE=$tend>\n";
				$THIS_DISPLAY .= "<INPUT TYPE=SUBMIT VALUE=\"&lt;&lt; ".lang("PREV")."\" CLASS=FormLt1>\n";
				$THIS_DISPLAY .= "</FORM>\n";

				$THIS_DISPLAY .= "</TD>\n";

			}

			$tstart = $end + 1;
			$tend = $tstart + $OPTIONS[DISPLAY_RESULTS] - 1;

			if ($TOTAL_FOUND > $end) {

				if ($TOTAL_FOUND <= $tend) { $tend = $TOTAL_FOUND; }

				$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE>\n";

				$THIS_DISPLAY .= "<FORM METHOD=POST ACTION=start.php>\n";
				$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=cat VALUE=\"$pass_cat\">\n";
				$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=browse VALUE=1>\n";
				$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=start VALUE=$tstart>\n";
				$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=end VALUE=$tend>\n";
				$THIS_DISPLAY .= "<INPUT TYPE=SUBMIT VALUE=\"".lang("NEXT")." >>\" CLASS=FormLt1>\n";
				$THIS_DISPLAY .= "</FORM>\n";

				$THIS_DISPLAY .= "</TD>\n";

			}

		$THIS_DISPLAY .= "</TR></TABLE>\n\n";

	} // End TOTAL_NUMBER = 0 IF


} // End $browse == 1 Statement

##########################################################################
### STEP 5:
### SETUP SEARCH COLUMN HTML FOR DISPLAY (REGARDLESS OF FUNCTION CALL)
##########################################################################

$SEARCH_COLUMN = "";

ob_start();
	include("prod_search_column.inc.php");
	$SEARCH_COLUMN .= ob_get_contents();
ob_end_clean();

##########################################################################
### STEP 6:
### BUILD PRIVACY POLICY DISPLAY IF REQUESTED
##########################################################################

if ($policy == "privacy") {
	if ( $privacy_policy_definedBool ) {
		$THIS_DISPLAY .= "<BR><BR>";
		$THIS_DISPLAY .= "<TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=5 class=smtext STYLE='border: inset BLACK 1px;'>\n";
		$THIS_DISPLAY .= "<TR> \n";
		$THIS_DISPLAY .= "<TD BGCOLOR='".$OPTIONS['DISPLAY_HEADERBG']."'><FONT COLOR=".$OPTIONS['DISPLAY_HEADERTXT']."><B><FONT FACE=\"Verdana, Arial, Helvetica, sans-serif\">".lang("Privacy Policy")."</FONT></B></FONT></TD>\n";
		$THIS_DISPLAY .= "</TR>\n";
		$THIS_DISPLAY .= "<TR> \n";
		$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=TOP>\n";
	
		// ------------------------------------------
		// Read Privacy Policy Statement into Memory
		// ------------------------------------------
	
		$filename = "$cgi_bin/privacy.txt";
		if (file_exists($filename)) {
			$file = fopen("$filename", "r") or DIE(lang("Error").": ".lang("Could not open privacy policy")." ($filename)");
				$PRIVACY_POLICY = fread($file,filesize($filename));
			fclose($file);
		}
	
		// ------------------------------------------
		// Format textarea data to display as HTML
		// ------------------------------------------
	
		$PRIVACY_POLICY = chop($PRIVACY_POLICY);
		$PRIVACY_POLICY = str_replace("\n", "<BR>", $PRIVACY_POLICY);
	
		// ------------------------------------------
		// Add Policy Text to $THIS_DISPLAY
		// ------------------------------------------
	
		$THIS_DISPLAY .= $PRIVACY_POLICY;
	
	
		$THIS_DISPLAY .= "</TD></TR></TABLE>\n\n";
	}
} // End Privacy Policy Build

##########################################################################
### STEP 7:
### BUILD ALL OTHER POLICIES
##########################################################################

if ($policy == "shipping"){

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// DISPLAY SHIPPING POLICY #1
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	if ( $shipping_policy_definedBool ) {
		$THIS_DISPLAY .= "<BR><BR>";
		$THIS_DISPLAY .= "<TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=5 class=smtext STYLE='border: inset BLACK 1px;'>\n";
		$THIS_DISPLAY .= "<TR> \n";
		$THIS_DISPLAY .= "<TD  BGCOLOR='".$OPTIONS['DISPLAY_HEADERBG']."'><FONT COLOR=".$OPTIONS['DISPLAY_HEADERTXT']."><B><FONT FACE=\"Verdana, Arial, Helvetica, sans-serif\">".lang("Shipping Information")."</FONT></B></FONT></TD>\n";
		$THIS_DISPLAY .= "</TR>\n";
		$THIS_DISPLAY .= "<TR> \n";
		$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=TOP>\n";
	
		// ------------------------------------------
		// Read Shipping Policy Statement into Memory
		// ------------------------------------------
	
		$filename = "$cgi_bin/cart_delivery.txt";
		if (file_exists($filename)) {
			$file = fopen("$filename", "r") or DIE(lang("Error").": ".lang("Could not open privacy policy")." ($filename)");
				$SHIPPING_POLICY = fread($file,filesize($filename));
			fclose($file);
		}
	
		// ------------------------------------------
		// Format textarea data to display as HTML
		// ------------------------------------------
	
		$SHIPPING_POLICY = chop($SHIPPING_POLICY);
		$SHIPPING_POLICY = str_replace("\n", "<BR>", $SHIPPING_POLICY);
	
		// ------------------------------------------
		// Add Policy Text to $THIS_DISPLAY
		// ------------------------------------------
	
		$THIS_DISPLAY .= $SHIPPING_POLICY;
	
		$THIS_DISPLAY .= "</TD></TR></TABLE>\n\n";
		
	} // End if shipping_policy_definedBool
}

if($policy == "returns"){
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// DISPLAY RETURNS/EXCHANGES POLICY #2
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	if ( $return_policy_definedBool ) {
		$THIS_DISPLAY .= "<BR><TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=5 class=smtext STYLE='border: inset BLACK 1px;'>\n";
		$THIS_DISPLAY .= "<TR> \n";
		$THIS_DISPLAY .= "<TD  BGCOLOR='".$OPTIONS['DISPLAY_HEADERBG']."'><FONT COLOR=".$OPTIONS['DISPLAY_HEADERTXT']."><B><FONT FACE=\"Verdana, Arial, Helvetica, sans-serif\">".lang("Returns & Exchanges")."</FONT></B></FONT></TD>\n";
		$THIS_DISPLAY .= "</TR>\n";
		$THIS_DISPLAY .= "<TR> \n";
		$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=TOP>\n";
	
		// ------------------------------------------
		// Read Policy Statement into Memory
		// ------------------------------------------
	
		$filename = "$cgi_bin/cart_returns.txt";
		if (file_exists($filename)) {
			$file = fopen("$filename", "r") or DIE(lang("Error").": ".lang("Could not open privacy policy")." ($filename)");
				$RETURNS_POLICY = fread($file,filesize($filename));
			fclose($file);
		}
	
		// ------------------------------------------
		// Format textarea data to display as HTML
		// ------------------------------------------
	
		$RETURNS_POLICY = chop($RETURNS_POLICY);
		$RETURNS_POLICY = str_replace("\n", "<BR>", $RETURNS_POLICY);
	
		// ------------------------------------------
		// Add Policy Text to $THIS_DISPLAY
		// ------------------------------------------
	
		$THIS_DISPLAY .= $RETURNS_POLICY;
	
	
		$THIS_DISPLAY .= "</TD></TR></TABLE>\n\n";
	} // End if returns policy defined

}
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// DISPLAY "OTHER POLICIES" IF EXISTS #3
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
if ($policy == "other"){
	$filename = "$cgi_bin/other_policies.txt";
	if (file_exists($filename)) {

		// ------------------------------------------
		// Read Policy Statement into Memory
		// ------------------------------------------

		$file = fopen("$filename", "r") or DIE(lang("Error").": ".lang("Could not open privacy policy")." ($filename)");
			$OTHER_POLICY = fread($file,filesize($filename));
		fclose($file);

		if (strlen($OTHER_POLICY) > 10) {	// Other Policy Statement should contain more than 10 chars
			$THIS_DISPLAY .= "<br/>\n";
			$THIS_DISPLAY .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" class=smtext style='border: inset BLACK 1px;'>\n";
			$THIS_DISPLAY .= " <tr> \n";
			$THIS_DISPLAY .= "  <td bgcolor='".$OPTIONS['DISPLAY_HEADERBG']."'>\n";
			$THIS_DISPLAY .= "   <font color=".$OPTIONS['DISPLAY_HEADERTXT']."><b><font face=\"verdana, Arial, Helvetica, sans-serif\">".$cartpref->get("other_policy_title")."</font></b></font>\n";
			$THIS_DISPLAY .= "  </td>\n";
			$THIS_DISPLAY .= " </tr>\n";
			$THIS_DISPLAY .= " <tr> \n";
			$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\">\n";

			# Format textarea data to display as HTML
			$OTHER_POLICY = chop($OTHER_POLICY);
			$OTHER_POLICY = str_replace("\n", "<br/>", $OTHER_POLICY);

			# Add Policy Text to $THIS_DISPLAY
			$THIS_DISPLAY .= $OTHER_POLICY;

			$THIS_DISPLAY .= "  </td>\n";
			$THIS_DISPLAY .= " </tr>\n";
			$THIS_DISPLAY .= "</table>\n\n";

		} // End if More than 10 Chars

	} // End if File Exists

} // End Other Policy Build

##########################################################################
### STEP 8:
### BUILD OVERALL TABLE TO PLACE SEARCH COLUMN TO THE LEFT OR RIGHT OF
### SEARCH RESULT DISPLAY AS DEFINED IN DISPLAY OPTIONS
##########################################################################
$FINAL_DISPLAY = $topnav;
$FINAL_DISPLAY .= "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" align=\"center\">\n";

// ----------------------------------------------------------------------------------
// If a welcome header text is present, then display it now.
//
// DEVNOTE: I have had requests to place header images at the top of each search,
// like banner ads or relevant images based on current category.  I havn't done it
// but my recommendation is that code be inserted here looking for an include file
// based on category or a banner ad rotation that display's in this section.
// The value of the DISPLAY_WELCOME field could be a filename... Hmmm.
// ----------------------------------------------------------------------------------

if (strlen($OPTIONS[DISPLAY_WELCOME]) > 3) {	// Welcome text should be longer than 3 char

	$FINAL_DISPLAY .= " <tr>\n";
	$FINAL_DISPLAY .= "  <td colspan=\"2\" align=\"left\" valign=\"middle\">\n";
	$FINAL_DISPLAY .= "   <font size=\"3\" face=\"verdana\"><b><i>".lang("Welcome to")."...</b></i></font><br/>";
	$FINAL_DISPLAY .= "   <h1>".$OPTIONS[DISPLAY_WELCOME]."</h1>";
	$FINAL_DISPLAY .= "   <hr width=\"100%\" style='height: 1px; color: ".$OPTIONS['DISPLAY_HEADERBG'].";'>";
	$FINAL_DISPLAY .= "  </td>\n";
	$FINAL_DISPLAY .= " </tr>\n\n";

}

$FINAL_DISPLAY .= "<tr>\n";

# Where should the search column be placed?
if ( eregi("L", $OPTIONS['DISPLAY_COLPLACEMENT'] ) ) {
   $FINAL_DISPLAY .= "  <td valign=\"top\" id=\"searchcolumn\">\n";
   $FINAL_DISPLAY .= "   ".$SEARCH_COLUMN."\n";
   $FINAL_DISPLAY .= "  </td>\n";
   $FINAL_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $FINAL_DISPLAY .= "   ".$THIS_DISPLAY."\n";
   $FINAL_DISPLAY .= "  </td>\n";

} else {
   $FINAL_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
   $FINAL_DISPLAY .= "   ".$THIS_DISPLAY."\n";
   $FINAL_DISPLAY .= "  </td>\n";
   $FINAL_DISPLAY .= "  <td  valign=\"top\" id=\"searchcolumn\">\n";
   $FINAL_DISPLAY .= "   ".$SEARCH_COLUMN."\n";
   $FINAL_DISPLAY .= "  </td>\n";
}

$FINAL_DISPLAY .= "</tr>\n\n";

// ----------------------------------------------------------------------------------
// If a business address has been supplied, display at the footer of each shopping
// cart page.  This can be removed if you wish, but studies have shown it instills
// trust among consumers that wish to buy from this web site
// ----------------------------------------------------------------------------------

if ($OPTIONS[BIZ_ADDRESS_1] != "" && $OPTIONS[BIZ_POSTALCODE] != "") {

	$FINAL_DISPLAY .= "<tr><td colspan=\"2\" align=\"center\" valign=\"middle\" class=\"smtext\">\n";
	$FINAL_DISPLAY .= "<hr width=\"100%\" style='height: 1px; color: ".$OPTIONS['DISPLAY_HEADERBG'].";'>\n".lang("Mailing Address").": $OPTIONS[BIZ_ADDRESS_1], ";

		if ($OPTIONS[BIZ_ADDRESS_2] != "") {
			$FINAL_DISPLAY .= "$OPTIONS[BIZ_ADDRESS_2], ";
		}

	$FINAL_DISPLAY .= "$OPTIONS[BIZ_CITY], $OPTIONS[BIZ_STATE], $OPTIONS[BIZ_POSTALCODE]\n<hr width=\"100%\" style='height: 1px; color: ".$OPTIONS['DISPLAY_HEADERBG'].";'>";
	$FINAL_DISPLAY .= "</td></tr>\n\n";

}

// ----------------------------------------------------------------------------------

$FINAL_DISPLAY .= "</TABLE>";


###########################################################################
### THE pgm-realtime_builder.php FILE COMPILES THE TEMPLATE DATA AND PAGE
### CONTENT DATA TOGETHER AND PUTS IT OUT AS THE $template_header AND
### $template_footer VARS RESPECTIVELY.  ANY MODIFICATION TO CHANGE THE
### WAY PAGES ARE OUTPUT TO THE SITE VISITOR SHOULD BE MADE WITHIN THE
### realtime_builder.php FILE
###########################################################################

$module_active = "yes";
include ("pgm-template_builder.php");

#######################################################

echo ("$template_header\n");
	$template_footer = eregi_replace("#CONTENT#", $FINAL_DISPLAY, $template_footer);
echo ("$template_footer\n\n");
echo ("\n\n<SCRIPT language=Javascript>\n     window.focus();\n</SCRIPT>\n\n");
exit;

?>