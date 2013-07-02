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

session_start();
include_once("../../../includes/product_gui.php");
error_reporting(E_PARSE);

# Restore cart preferences
$cartpref = new userdata("cart");


# PROCESS: Delete sku
if($_REQUEST['deleteflag'] == "yes" && $_REQUEST['del_key'] > 0) {
	mysql_query("DELETE FROM cart_products WHERE PRIKEY = '".$_REQUEST['del_key']."'");
	header("Location: search_products.php");
	exit;
}

# PROCESS: Set thumbnail load pref
if ( $_GET['findsku_loadthumbs'] != "" ) {
   $cartpref->set("findsku_loadthumbs", $_GET['findsku_loadthumbs']);
}

# PROCESS: Set manual image pref
if ( $_GET['manual_image_mode'] != "" ) {
   $cartpref->set("manual_image_mode", $_GET['manual_image_mode']);
}

#######################################################
### START HTML/JAVASCRIPT CODE			    ###
#######################################################

ob_start();

?>

<STYLE>

	.edit {
		background-color: green;
		color: #FFFFFF;
		font-size: 8pt;
		cursor: hand;
		border: inset darkgreen 2px;
		}

	.delete {
		background-color: red;
		color: #FFFFFF;
		font-size: 8pt;
		cursor: hand;
		border: inset darkred 2px;
		}

</STYLE>

<script type="text/javascript">
	
function deletethis(prodname,prodkey){
	var yourstate=window.confirm("Are you sure you want to delete "+prodname+"?");
	if(yourstate){
		//alert("deleting "+prodname+" "+prodkey);
		document.location.href='search_products.php?deleteflag=yes&del_key='+prodkey;
	}
}
<!--
function SV2_findObj(n, d) { //v3.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=SV2_findObj(n,d.layers[i].document); return x;
}
function SV2_showHideLayers() { //v3.0
  var i,p,v,obj,args=SV2_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=SV2_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
    obj.visibility=v; }
}
function SV2_popupMsg(msg) { //v1.0
  alert(msg);
}
function SV2_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

SV2_showHideLayers('addCartMenu?header','','hide');
SV2_showHideLayers('blankLayer?header','','hide');
SV2_showHideLayers('linkLayer?header','','hide');
SV2_showHideLayers('newsletterLayer?header','','hide');
SV2_showHideLayers('cartMenu?header','','show');
SV2_showHideLayers('menuLayer?header','','hide');
SV2_showHideLayers('editCartMenu?header','','hide');

function verify_del(key) {

	var fname = "DEL"+key;
	var tiny = window.confirm("!!WARNING!! YOU HAVE SELECTED TO DELETE THIS PRODUCT.\n\n        DELETING OF A PRODUCT CAN NOT BE REVERSED!\n\n                  Press 'OK' to delete this product now.");

	if (tiny != false) { eval("document."+fname+".submit();"); }

}

//-->


// Display thumbnail preview image for sku onmouseover (vs loading them all...slow on big carts)
function prev_sku(counter_num, imgfile) {
   if ( document.getElementById('prev-'+counter_num).innerHTML == '' ) {
      if ( imgfile != "none" ) {
         document.getElementById('prev-'+counter_num).innerHTML = '<img src="http://<? echo $_SESSION['docroot_url']; ?>/images/'+imgfile+'" width="40">';
      } else {
         document.getElementById('prev-'+counter_num).innerHTML = '<span class="font90 gray_33">[No Img]</span>';
      }
   }
}

function manual_image_mode() {
	if ( document.getElementById('manual_image_mode').checked == true ) {
		document.location.href='search_products.php?manual_image_mode=enabled';
	} else {
		document.location.href='search_products.php?manual_image_mode=disable';
	}
}
</script>
<?

$THIS_DISPLAY = "";

$THIS_DISPLAY = "<!-- Search for existing sku numbers -->";

####################################################################
### DISPLAY SKU DROP DOWN AND KEYWORD SEARCH HEADER
####################################################################
$dropdown_options = "";

if ( $_POST['showresults'] != "yes" ) { // Regular view...did NOT just run a search

   # Pull all skus from db
   $qry = "select PRIKEY, PROD_SKU, PROD_THUMBNAIL, PROD_NAME from cart_products";
   $qry .= "  order by PROD_SKU";
   $rez = mysql_query($qry);

   # DEFAULT: Load sku thumbnails onmouseover if big cart
   if ( $cartpref->get("findsku_loadthumbs") == "" ) {
      if ( mysql_num_rows($rez) > 25 ) { $cartpref->set("findsku_loadthumbs", "onmouseover"); } else { $cartpref->set("findsku_loadthumbs", "onload"); }
   }

   # Existing sku list, clickable with thumbnails
   $edit_sku_list = "";
   $edit_sku_list .= "<div id=\"edit_sku_list\" style=\"width: 350px;height: 240px;overflow: auto;\">\n";
   $edit_sku_list .= " <table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">\n";

   $edit_sku_list .= "    <tr>\n";
   $edit_sku_list .= "     <th align=\"center\" height=\"40\" style=\"font-weight: normal;color: #595959;padding: 0;\">Thumbnail</th>\n";
   $edit_sku_list .= "     <th style=\"font-weight: normal;text-align: left;color: #595959;\">Product Name / Sku number</th>\n";
   $edit_sku_list .= "     <th align=\"center\">&nbsp;</th>\n";
   $edit_sku_list .= "    </tr>\n";

   $n = 1; // For js action
   while ( $getSku = mysql_fetch_array($rez) ) {
      $testimg = $_SESSION['docroot_path']."/images/".$getSku['PROD_THUMBNAIL'];
      if ( $getSku['PROD_THUMBNAIL'] != "" && file_exists($testimg) ) {
         $img_tag = "<img src=\"http://".$_SESSION['docroot_url']."/images/".$getSku['PROD_THUMBNAIL']."\" width=\"40\">";
         $prev = $getSku['PROD_THUMBNAIL'];
      } else {
         $img_tag = "<span class=\"font90 gray_33\">[No Img]</span>";
         $prev = "none";
      }

      if ( $bg == "bg_white" ) { $bg = "bg_gray_f8"; } else { $bg = "bg_white"; }

      # Load thumbnail onload?
      if ( $cartpref->get("findsku_loadthumbs") == "onload" && $prev != "none" ) { $img_display = " <img src=\"http://".$_SESSION['docroot_url']."/images/".$prev."\" width=\"40\">"; } else { $img_display = ""; }

      $edit_sku_list .= "    <tr class=\"".$bg."\" onmouseover=\"prev_sku('".$n."', '".$prev."');this.className='bg_yellow';\" onmouseout=\"this.className='".$bg."';\">\n";
      $edit_sku_list .= "     <td align=\"center\" height=\"40\" style=\"padding: 0;\"><div id=\"prev-".$n."\">".$img_display."</div></td>\n";
      $edit_sku_list .= "     <td>".$getSku['PROD_NAME']."<br/><span class=\"font90\"><i>".$getSku['PROD_SKU']."</i></span></td>\n";
      $edit_sku_list .= "     <td align=\"center\">\n";
      $edit_sku_list .= "     	[<a href=\"products.php?edit_key=".$getSku['PRIKEY']."\">Edit</a>]\n";
      $edit_sku_list .= "     	[<a onclick=\"deletethis('".str_replace("'", '', $getSku['PROD_NAME'])."','".$getSku['PRIKEY']."');\" class=\"del\" href=\"javascript:void(0);\">Delete</a>]\n";
      $edit_sku_list .= "	</td>\n";
      $edit_sku_list .= "    </tr>\n";

      # Build options for direct-select drop-down (below)
      # Show "0001 - Super widget" but not "SuperWidget01 - Super Widget"
      if ( strlen($getSku['PROD_SKU']) < 10 ) { $option_display = $getSku['PROD_SKU']." - ".$getSku['PROD_NAME']; } else { $option_display = $getSku['PROD_SKU']; }
      $dropdown_options .= "     <OPTION VALUE=\"".$getSku['PRIKEY']."\">".$option_display."</OPTION>\n";

      $n++;
   }
   $edit_sku_list .= " </table>\n";
   $edit_sku_list .= "</div>\n";

} else {
   # Just did a search...so only need to build drop down options, not thumbnail list thing
   $qry = "select PRIKEY, PROD_SKU, PROD_THUMBNAIL, PROD_NAME from cart_products";
   $qry .= "  order by PROD_SKU";
   $rez = mysql_query($qry);
   while ( $getSku = mysql_fetch_array($rez) ) {
      # Show "0001 - Super widget" but not "SuperWidget01 - Super Widget"
      if ( strlen($getSku['PROD_SKU']) < 10 ) { $option_display = $getSku['PROD_SKU']." - ".$getSku['PROD_NAME']; } else { $option_display = $getSku['PROD_SKU']; }
      $dropdown_options .= "     <OPTION VALUE=\"".$getSku['PRIKEY']."\">".$option_display."</OPTION>\n";
   }
}

$THIS_DISPLAY .= "<p style=\"margin-bottom: 0;\">\n";
$THIS_DISPLAY .= "[<a href=\"products.php?backto=search_products.php\" class=\"sav\"><strong>Create a new product</strong></a>]\n";
//$THIS_DISPLAY .= "[<a href=\"#\" class=\"del\" onclick=\"alert('To delete an item, search for it using the search box on the right.  Then choose \'Delete Product\'.'); return false;\">".lang("Delete an item")."</a>]\n";
$THIS_DISPLAY .= "</p>\n";

$THIS_DISPLAY .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">\n";
$THIS_DISPLAY .= " <tr> \n";
$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\" class=\"text\" style=\"padding-top: 3px;\">\n";
$THIS_DISPLAY .= "   <form name=\"editsku_form\" method=\"post\" action=\"products.php\">\n";
$THIS_DISPLAY .= "    <p><b>".lang("Select sku to edit by sku number or name")."...</b></p> \n";
# Edit sku drop-down
$THIS_DISPLAY .= "    <select name=edit_key style=\"text\" style=\"width: 200px;\" onchange=\"document.editsku_form.submit();\">\n";
$THIS_DISPLAY .= $dropdown_options;
$THIS_DISPLAY .= "    </select>\n";
$THIS_DISPLAY .= "   </form>\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= "  <td width=\"10%\">&nbsp;</td>\n";

# Search for
$THIS_DISPLAY .= "  <td valign=\"top\" class=\"text\" style=\"width: 15%;padding-top: 3px;\">\n";
$THIS_DISPLAY .= "   <FORM METHOD=POST ACTION=\"search_products.php\" name=\"keyword_search_form\">\n";
$THIS_DISPLAY .= "    <INPUT TYPE=HIDDEN NAME=showresults VALUE=yes>\n";
$THIS_DISPLAY .= "    <p><b>".lang("Search for product by keyword")."...</b></p>\n";
$THIS_DISPLAY .= "    <INPUT TYPE=TEXT SIZE=35 STYLE='width: 200px;' NAME=searchfor class=\"text\">\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\" class=\"text\" style=\"padding-top: 33px;\">\n";
$THIS_DISPLAY .= "    <button type=\"button\" class=\"blueButton\" onclick=\"document.keyword_search_form.submit();\"><span><span>".lang("Find")." &gt;&gt;</span></span></button>\n";
$THIS_DISPLAY .= "   </FORM>\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";

# Do not show big thumbnail-style edit sku list if search was just run (gotta conserve real estate)
if ( $_POST['showresults'] != "yes" ) {
   $THIS_DISPLAY .= " <tr>\n";
   $THIS_DISPLAY .= "  <td colspan=\"2\">\n";
   $THIS_DISPLAY .= "   <b>".lang("Browse/edit by thumbnail")."</b>...<br/>\n";
   $THIS_DISPLAY .= "   ".$edit_sku_list."\n";
   $THIS_DISPLAY .= "  </td>\n";

   $THIS_DISPLAY .= "  <td colspan=\"2\" valign=\"top\" class=\"font90\">\n";
   $THIS_DISPLAY .= "   <p><b>Note:</b> If you want to delete a cart product, run a search on it via the search field above,\n";
   $THIS_DISPLAY .= "   then click the 'Delete Product' button that will appear next to it in the search results.</p>\n";

   $THIS_DISPLAY .= "   <fieldset>\n";
   $THIS_DISPLAY .= "   	<legend><b>Thumbnail Loading</b></legend>\n";
   $THIS_DISPLAY .= "   	<p>Wait until you mouseover to show sku thumbnails? (helps keep load time of this screen down if you have a lot of skus)</p>\n";
   $THIS_DISPLAY .= "   	<select id=\"findsku_loadthumbs\" onchange=\"document.location.href='search_products.php?findsku_loadthumbs='+this.value;\">\n";
   $THIS_DISPLAY .= "    		<option value=\"onmouseover\">Load thumbnail when I mouse-over that sku</option>\n";
   $THIS_DISPLAY .= "    		<option value=\"onload\">Load all thumbnails when this screen loads</option>\n";
   $THIS_DISPLAY .= "   	</select>\n";
   $THIS_DISPLAY .= "   </fieldset>\n";   
   $THIS_DISPLAY .= "   <fieldset>\n";
   $THIS_DISPLAY .= "   	<legend><b>Image Selection</b></legend>\n";
   $THIS_DISPLAY .= "   	<p><input type=\"checkbox\" id=\"manual_image_mode\" value=\"enabled\" onchange=\"manual_image_mode();\"/>\n";
   $THIS_DISPLAY .= "   	<label for=\"manual_image_mode\">Check to enable manual specification of image filenames for products (if you have over 3,000+ images and the edit product screen stops loading, this will fix the problem)</label></p>\n";
   $THIS_DISPLAY .= "   </fieldset>\n";
   
   $THIS_DISPLAY .= "<script type=\"text/javascript\">\n";
   $THIS_DISPLAY .= "document.getElementById('findsku_loadthumbs').value = '".$cartpref->get("findsku_loadthumbs")."';\n";
   
   if ( $cartpref->get("manual_image_mode") == 'enabled' ) {
   	$THIS_DISPLAY .= "document.getElementById('manual_image_mode').checked = true;\n";
   }
   $THIS_DISPLAY .= "</script>\n";
   $THIS_DISPLAY .= "  </td>\n";
   $THIS_DISPLAY .= " </tr>\n";
}
$THIS_DISPLAY .= "</table>\n\n";

####################################################################
### IF $showresults VARIABLE IS "yes"; PERFORM PRODUCT SEARCH AND
### DISPLAY THE RESULTS BASED ON KEYWORD SEARCH
####################################################################

if ($showresults == "yes") {

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

	$tmp_sql_string = "SELECT PRIKEY, PROD_SKU, PROD_CATNO, PROD_NAME, PROD_DESC, PROD_UNITPRICE, PROD_CATEGORY1, ";
	$tmp_sql_string .= "VARIANT_PRICE1, VARIANT_PRICE2, VARIANT_PRICE3, VARIANT_PRICE4, VARIANT_PRICE5, ";
	$tmp_sql_string .= "VARIANT_PRICE6, OPTION_DETAILPAGE, PROD_THUMBNAIL, PROD_FULLIMAGENAME, OPTION_KEYWORDS, ";
	$tmp_sql_string .= "OPTION_RECOMMENDSKU FROM cart_products WHERE ";

	$keyflag = 0;	// Set keyword flag to zero by default

	for ($x=0;$x<=$NUM_SEARCH_KEYS;$x++) {

		if ($SEARCH_KEYWORDS[$x] != "") {

			$SEARCH_KEYWORDS[$x] = strtoupper($SEARCH_KEYWORDS[$x]);	// Force search to be case-insensitive

			$tmp_sql_string .= "(UPPER(PROD_SKU) LIKE '%$SEARCH_KEYWORDS[$x]%' OR UPPER(PROD_CATNO) LIKE '%$SEARCH_KEYWORDS[$x]%' OR ";
			$tmp_sql_string .= "UPPER(PROD_NAME) LIKE '%$SEARCH_KEYWORDS[$x]%' OR UPPER(PROD_DESC) LIKE '%$SEARCH_KEYWORDS[$x]%' OR ";
			$tmp_sql_string .= "UPPER(OPTION_KEYWORDS) LIKE '%$SEARCH_KEYWORDS[$x]%') OR ";

			$keyflag = 1; 	// Let next routine know that we actually found keywords; in case of all search;
		}

	}

	// Kill extra "OR" or "WHERE" tag in sql string that is left over from loop or flag
	// ----------------------------------------------------------------------------------

	$tmp = strlen($tmp_sql_string);

	if ($keyflag == 1) {
		$parse = $tmp - 3;	// Parse last OR in keyword loop
	} else {
		$parse = $tmp - 6;	// Parse WHERE
	}

	$tmp_sql_string = substr($tmp_sql_string, 0, $parse);

	// -----------------------------------------------------------------

	$result = mysql_query("$tmp_sql_string ORDER BY PROD_NAME");

	$TOTAL_FOUND = mysql_num_rows($result);	// Denote how many results where returned on query

		$THIS_DISPLAY .= "<BR><TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=5 CLASS=allBorder>\n";
		$THIS_DISPLAY .= "<TR> \n";
		$THIS_DISPLAY .= "<TD COLSPAN=4 CLASS=text ALIGN=LEFT VALIGN=TOP BGCOLOR=DARKBLUE><FONT COLOR=WHITE><B>".$lang["Search Results"].":</TD>\n";
		$THIS_DISPLAY .= "</TR>\n";

		while ($PROD = mysql_fetch_array($result)) {

			if ($rotate == "#EFEFEF") { $rotate = "white"; } else { $rotate = "#EFEFEF"; }

			$THIS_DISPLAY .= "\n<TR> \n";

//			$THIS_DISPLAY .= "\n\n<FORM METHOD=POST ACTION=\"products.php\">\n";
			$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE CLASS=text BGCOLOR=$rotate>\n";
//			$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=edit_key VALUE=\"$PROD[PRIKEY]\">\n";
//			$THIS_DISPLAY .= "<INPUT TYPE=SUBMIT VALUE=\"".lang("Edit Product Data")."\" class=\"btn_edit\" onmouseover=\"this.className='btn_editon';\" onmouseout=\"this.className='btn_edit';\">\n";
			$THIS_DISPLAY .= "<button type=button class=\"blueButton\" onclick=\"document.location.href='products.php?edit_key=".$PROD['PRIKEY']."';\"><span><span>".lang("Edit Product Data")."</span></span></button>\n";
//			$THIS_DISPLAY .= "</FORM>\n\n\n";			
			$THIS_DISPLAY .= "</TD>\n";


			$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE CLASS=text BGCOLOR=$rotate>\n";
			$THIS_DISPLAY .= "$PROD[PROD_NAME]</TD>\n";
			$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE CLASS=text BGCOLOR=$rotate>\n";
			$THIS_DISPLAY .= "Sku Number: ".strtoupper($PROD[PROD_SKU])."</TD>\n";


			$THIS_DISPLAY .= "\n\n<FORM NAME=DEL".$PROD[PRIKEY]." METHOD=POST ACTION=\"search_products.php\">\n";
			$THIS_DISPLAY .= "<TD ALIGN=RIGHT VALIGN=MIDDLE CLASS=text BGCOLOR=$rotate>\n";
			$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=searchfor VALUE=\"$searchfor\">\n";
			$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=showresults VALUE=yes>\n";
			$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=deleteflag VALUE=yes>\n";
			$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=del_key VALUE=\"$PROD[PRIKEY]\">\n";
			$THIS_DISPLAY .= "<button type=\"button\" onclick=\"verify_del('$PROD[PRIKEY]');\" class=\"redButton\"><span><span>".lang("Delete Product")."</span></span></button>\n";
			$THIS_DISPLAY .= "</TD></FORM></TR>\n\n\n";



		}

		$THIS_DISPLAY .= "</TABLE>\n";

} // End Show Results

echo $THIS_DISPLAY;


# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$module = new smt_module($module_html);
$module->add_breadcrumb_link("Shopping Cart Menu", "program/modules/mods_full/shopping_cart.php");
$module->add_breadcrumb_link("Find/Edit Products", "program/modules/mods_full/shopping_cart/".basename($_SERVER['PHP_SELF']));
$module->icon_img = "program/includes/images/shopping-icon-med.png";
$module->heading_text = "Find/Edit Products";
$module->description_text = "This is where you can edit the products you've already created. Select the product you want to edit from the drop-down box, or do a search to find the product you're looking for (handy if you have lots of products).";
$module->good_to_go();
?>