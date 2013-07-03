<?php
error_reporting('341');
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
session_start();
require_once("../../../includes/product_gui.php");
create_table('cart_products_usersignup');
//echo "(".get_magic_quotes_gpc().")"; exit;
// // 
######################################################
### SET PRODUCT DEFAULT VALUES IN CASE OF NEW PRODUCT
### ADDITION
######################################################

$SKU['PROD_CATEGORY1'] = "0";
$SKU['PROD_CATEGORY2'] = "0";

$SKU['PROD_THUMBNAIL'] = " ";
$SKU['PROD_FULLIMAGENAME'] = " ";

$SKU['OPTION_CHARGETAX'] = "Y";
$SKU['OPTION_CHARGESHIPPING'] = "Y";
$SKU['OPTION_SECURITYCODE'] = "Public";
$SKU['OPTION_DETAILPAGE'] = " ";
$SKU['OPTION_SHOWATEDIT'] = "N";
$SKU['OPTION_FORMDATA'] = " ";
$SKU['OPTION_DOWNLOADFILE'] = " ";
$SKU['OPTION_DISPLAY'] = "Y";
$SKU['PRODUCT_CATEGORY3'] = "0";
$SKU['OPTION_INVENTORY_NUM'] = "50000";
$THUMB_JAVA = " ";
$FULL_JAVA = " ";

######################################################
### START SAVE/UPDATE ACTION FOR SINGLE PRODUCT
######################################################

$update_completed = 0;

# To escape or not to escape?
# Designed to address gpc_magic_quotes problem (as in, how some have it on and some have it off)
function db_string_format($string) {
   if ( !get_magic_quotes_gpc() ) {
      return mysql_real_escape_string($string);
   } else {
      return $string;
   }
}

$cartpref = new userdata("cart");

/*---------------------------------------------------------------------------------------------------------*
   ____
  / __/___ _ _  __ ___
 _\ \ / _ `/| |/ // -_)
/___/ \_,_/ |___/ \__/
/*---------------------------------------------------------------------------------------------------------*/
if ($ACTION == "SAVEIT") {
	$num_variants = $_POST['num_variants'];
	$prod_shipc = $_POST['ship_length'].';'.$_POST['ship_width'].';'.$_POST['ship_height'];
	$_POST['prod_shipc'] = $prod_shipc;
	unset($_POST['ship_length']);
	unset($_POST['ship_width']);
	unset($_POST['ship_height']);

	eval(hook("products.php:postvars"));
	$min_qty = '';
	$max_qty = '';
	$disc = '';
	
	$_POST['min_qty']['0'] = $_POST['required_qty'];
	foreach($_POST['min_qty'] as $pvar=>$pval) {
		if($pval == '') {
			$_POST['min_qty'][$pvar] = 1;
		}
	}
	$_POST['max_qty'][$pvar]='9999999';
	if($_POST['required_qty'] == ''){
		$_POST['required_qty'] = 1;
	}
	foreach($_POST['max_qty'] as $mvar=>$mval) {
		if($mval == '') {
			$_POST['max_qty'][$mvar]= 9999999;
		}
	}
	foreach($_POST['disc'] as $dvar=>$dval) {
		if($dval == '') {
			$_POST['disc'][$dvar] = 0;
		}
	}
	
	$min_qty = serialize($_POST['min_qty']);
	$max_qty = serialize($_POST['max_qty']);
	$disc = serialize($_POST['disc']);
	$camqry = mysql_query('select sku from qty_discounts where sku=\''.$_POST["prod_sku"].'\'');
	$camnum = mysql_num_rows($camqry);
	if($camnum > 0) {
		mysql_query('update qty_discounts set sku=\''.$_POST['prod_sku'].'\', disc_type=\''.$_POST['disc_type'].'\', required_qty=\''.$_POST['required_qty'].'\', min_qty=\''.$min_qty.'\', max_qty=\''.$max_qty.'\', disc=\''.$disc.'\' where sku=\''.$_POST["prod_sku"].'\'');
	} else {
		$data = array();
		$data['sku'] = $_POST['prod_sku'];
		$data['disc_type'] = $_POST['disc_type'];
		$data['required_qty'] = $_POST['required_qty'];
		$data['min_qty'] = $min_qty;
		$data['max_qty'] = $max_qty;
		$data['disc'] = $disc;
		$myqry = new mysql_insert("qty_discounts", $data);
		$myqry->insert();
	}
	// ----------------------------------------------------------
	// Prepare all data for database input
	// ----------------------------------------------------------
	reset($_POST);
	while (list($name, $value) = each($_POST)) {

	   if ( $name != "full_desc" ) {
   		$value = stripslashes($value);					// Strip all slashes from data for HTML exekution
   		$value = str_replace("&", "&amp;", $value);	// Mantis #288
   		$value = eregi_replace("\"", "&quot;", $value);	// Replace Quotes with HTML Special Chars
   		$value = eregi_replace("'", "&rsquo;", $value);	// Replace Quotes with HTML Special Chars
   		$value = addslashes($value);					// Make sure all vars have slashes when inserting into table

   	} elseif ( $name == "full_desc" ) {
   	   # Format full description
   	   if ( get_magic_quotes_gpc() ) {
   	      $value = stripslashes($value);
   	   }
   	   $value = base64_encode($value);
   	}

   	$_POST[$name] = $value;
   	${$name} = $value;
	}

	// -----------------------------------------------------------
	// Parse Dollar Signs from pricing fields to account for
	// user error/confusion
	// -----------------------------------------------------------

	$prod_unitprice = str_replace("\$", "", $prod_unitprice);
	$prod_unitprice = str_replace(",", "", $prod_unitprice);
	$prod_shipa = str_replace("\$", "", $prod_shipa);
	if ( strlen($prod_shipa) > 1 ) { $prod_shipa = trim($prod_shipa); } // Spaces to add up well in ship calc. (Mantis #0000010)

	# Now strip problem characters from variant prices and names
	for ( $v = 1; $v <= $num_variants; $v++ ) {
	   $_POST['variant_name'.$v] = str_replace("&quot;", "in.", $_POST['variant_name'.$v]);
//	   $_POST['variant_name'.$v] = str_replace("&amp;", "", $_POST['variant_name'.$v]);
	   $_POST['variant_price'.$v] = str_replace("\$", "", $_POST['variant_price'.$v]);
	   $_POST['variant_price'.$v] = str_replace(",", "", $_POST['variant_price'.$v]);
	}

	$prod_name = str_replace("&amp;", "&", $prod_name);

   # Put together array of price variations and sub categories
   $sub_cats = array();
   $variant_names = array();
   $variant_prices = array();
   for ( $v = 1; $v <= $num_variants; $v++ ) {
      $sub_cats[$v] = $_POST['sub_cat'.$v];
      $variant_names[$v] = $_POST['variant_name'.$v];
      $variant_prices[$v] = $_POST['variant_price'.$v];
   }
   $sub_cats = serialize($sub_cats);
   $variant_names = serialize($variant_names);
   $variant_prices = serialize($variant_prices);

	// -----------------------------------------------------------
	// Parse out any width/height corrections that were added
	// to the drop down selection values to compensate for
	// large image files
	// -----------------------------------------------------------

	$tmp = split(" ", $prod_thumbnail);
	$prod_thumbnail = $tmp[0];
	$prod_thumbnail = rtrim($prod_thumbnail);

	$tmp = split(" ", $prod_fullimagename);
	$prod_fullimagename = $tmp[0];
	$prod_fullimagename = rtrim($prod_fullimagename);

	// -----------------------------------------------------------
	// If the security code is blank for some reason, make it
	// Public so the cart will operate correctly
	// -----------------------------------------------------------

	if ($option_securitycode == "" || $option_securitycode == " ") { $option_securitycode = "Public"; }

	// -----------------------------------------------------------
	// If this is a new addition, INSERT the new data
	// -----------------------------------------------------------

	if ($PriKey == "ADDNEW") {

	   # Build cart_products insert
	   $data = array();
      $data['PROD_CATEGORY1'] = $prod_category1;
      $data['PROD_CATEGORY2'] = $prod_category2;
      $data['PROD_CATEGORY3'] = $prod_category3;
      $data['PROD_SKU'] = $prod_sku;
      $data['PROD_CATNO'] = $prod_catno;
      $data['PROD_UNITPRICE'] = $prod_unitprice;
      $data['PROD_NAME'] = $prod_name;
      $data['PROD_DESC'] = $prod_desc;
      $data['PROD_SHIPA'] = $prod_shipa;
      $data['PROD_SHIPB'] = $prod_shipb;
      $data['PROD_SHIPC'] = $prod_shipc;
      $data['PROD_THUMBNAIL'] = $prod_thumbnail;
      $data['PROD_FULLIMAGENAME'] = $prod_fullimagename;
      $data['OPTION_DISPLAY'] = $option_display;
      $data['OPTION_CHARGETAX'] = $option_chargetax;
      $data['OPTION_CHARGESHIPPING'] = $option_chargeshipping;
      $data['OPTION_KEYWORDS'] = $option_keywords;
      $data['OPTION_SECURITYCODE'] = $option_securitycode;
      $data['OPTION_DETAILPAGE'] = $option_detailpage;
      $data['OPTION_RECOMMENDSKU'] = $option_recommendsku;
      $data['OPTION_SHOWATEDIT'] = $option_showatedit;
      $data['OPTION_FORMDATA'] = $option_formdata;
      $data['OPTION_FORMDISPLAY'] = $option_formdisplay;
      $data['OPTION_DOWNLOADFILE'] = $option_downloadfile;
      $data['OPTION_INVENTORY_NUM'] = $option_inventory_num;
      $data['SPECIAL_TAX'] = $special_tax;
      $data['sub_cats'] = $sub_cats;
      $data['variant_names'] = $variant_names;
      $data['variant_prices'] = $variant_prices;
      $data['num_variants'] = $num_variants;
      $data['full_desc'] = $full_desc;
			$data['other_images'] = $other_images;
      $myqry = new mysql_insert("cart_products", $data);
      $myqry->insert();

	if($_POST['option_usersignup']!=''){
		$datasecsign=array();
		$datasecsign['product_id'] = mysql_insert_id();
		$datasecsign['groups'] = $_POST['option_usersignup_group'];
		$datasecsign['expiration'] = $_POST['expiration'];		
      	$myqrysec = new mysql_insert("cart_products_usersignup", $datasecsign);
      	$myqrysec->insert();
	}

//		# Do not go to fresh add new product form
//		$edit_key = mysql_insert_id();
	} else {
		if($_POST['option_usersignup']!='' && $PriKey!=''){
			mysql_query("delete from cart_products_usersignup where product_id='".$PriKey."'");
			$datasecsign=array();
			$datasecsign['product_id'] = $PriKey;
			$datasecsign['groups'] = $_POST['option_usersignup_group'];
			$datasecsign['expiration'] = $_POST['expiration'];		
	      	$myqrysec = new mysql_insert("cart_products_usersignup", $datasecsign);
	      	$myqrysec->insert();
		}
		
   	// -----------------------------------------------------------
   	// Else, If update of existing Sku, perform Update
   	// -----------------------------------------------------------

//	   echo "restored unserialized()...<br/>";
//	   echo testArray(unserialize(serialize($sub_cats))); exit;

		$qry = "UPDATE cart_products SET

			PROD_CATEGORY1 = '$prod_category1',
			PROD_CATEGORY2 = '$prod_category2',
			PROD_CATEGORY3 = '$prod_category3',
			PROD_SKU = '$prod_sku',
			PROD_CATNO = '$prod_catno',
			PROD_UNITPRICE = '$prod_unitprice',
			PROD_NAME = '$prod_name',
			PROD_DESC = '$prod_desc',
			PROD_SHIPA = '$prod_shipa',
			PROD_SHIPB = '$prod_shipb',
			PROD_SHIPC = '$prod_shipc',
			PROD_THUMBNAIL = '$prod_thumbnail',
			PROD_FULLIMAGENAME = '$prod_fullimagename',

			OPTION_DISPLAY = '$option_display',
			OPTION_CHARGETAX = '$option_chargetax',
			OPTION_CHARGESHIPPING = '$option_chargeshipping',
			OPTION_KEYWORDS = '$option_keywords',
			OPTION_SECURITYCODE = '$option_securitycode',
			OPTION_DETAILPAGE = '$option_detailpage',
			OPTION_RECOMMENDSKU = '$option_recommendsku',
			OPTION_SHOWATEDIT = '$option_showatedit',
			OPTION_FORMDATA = '$option_formdata',
			OPTION_FORMDISPLAY = '$option_formdisplay',
			OPTION_DOWNLOADFILE = '$option_downloadfile',
			OPTION_INVENTORY_NUM = '$option_inventory_num',

			SPECIAL_TAX = '$special_tax',

			sub_cats = '$sub_cats',
			variant_names = '$variant_names',
			variant_prices = '$variant_prices',
			num_variants = '$num_variants',
			full_desc = '$full_desc',
	  		other_images = '$other_images'

			WHERE PRIKEY = '$PriKey'";

		mysql_query($qry);


		$edit_key = $PriKey;

	} // End if PriKey

	// -----------------------------------------------------------

	eval(hook("products.php:saveaddnprodinfo"));
	
	updateCartCatCount();
	
	$update_completed = 1;

} // End of Save/Update Action




######################################################
### READ CART CATEGORIES INTO SELECTION BOX HTML FOR
### USE IN CATEGORY SELECTION
######################################################

$cat_selection = "     <OPTION VALUE=\"0\">N/A</OPTION>\n";
$result = mysql_query("SELECT * FROM cart_category ORDER BY category ");
while ($row = mysql_fetch_array ($result)) {
	if (strlen($row[category]) > 2) {
		$catz[$row['keyfield']]= array('name'=>$row['category'], 'level'=>$row['level'], 'subs'=>$row['subs'], 'parent'=>$row['parent']);		
	}
}

if(mysql_num_rows($result) == 0){
	header("Location: categories.php?nocats=1");	
	exit;
	
}


foreach($catz as $cvar=>$cval){
	if($catz[$cvar]['level'] == '1'){
		$cat_selection .= "     <OPTION VALUE=\"".$cvar."\">".$cval['name']."</OPTION>\n";
		if($cval['subs']!=''){
			$thesubs = explode(',',$cval['subs']);
			foreach($thesubs as $sbval){
				$cat_selection .= "     <OPTION VALUE=\"".$sbval."\">&nbsp;&nbsp;&gt;&nbsp;".$catz[$sbval]['name']."</OPTION>\n";
				if($catz[$sbval]['subs'] != ''){
					$subsubz = explode(',', $catz[$sbval]['subs']);
					foreach($subsubz as $subsubval){
						$cat_selection .= "     <OPTION VALUE=\"".$subsubval."\">&nbsp;&nbsp;&nbsp;&nbsp;&gt;&gt;&nbsp;".$catz[$subsubval]['name']."</OPTION>\n";
					}
				}
			}
		}
	}
}

######################################################
### READ CART ATTACHMENT PAGES INTO SELECTION BOX
### HTML FOR USE IN DETAILS PAGE SELECTION
######################################################

$page_selection = "     <OPTION VALUE=\" \">N/A</OPTION>\n";
$result = mysql_query("SELECT page_name, url_name, link FROM site_pages ORDER BY page_name");
while($row = mysql_fetch_array($result)) {
	if(!preg_match('/^http:/i', $row['link'])){
		$page_selection .= "     <OPTION VALUE=\"$row[page_name]\">$row[page_name]</OPTION>\n";
	}
}

######################################################
### READ ANY DOWNLOADABLE FILES INTO MEMORY; AT THIS
### POINT THERE IS NO SECURITY ON THESE FILES BECAUSE
### THEY SIMPLY EXIST ON THE SERVER IN A STANDARD
### PORT :80 ACCESSIBLE DIRECTORY.  IF SOMEONE WANTED
### TO MODIFY UPLOAD FILES TO PLACE FILES INTO A
### DATABASE; THAT WOULD SOLVE THAT PROBLEM.
### I JUST DID NOT DO IT IN THE INITIAL DESIGN.
###
### WHILE WE'RE HERE, MIGHT AS WELL POPULATE THE
### SELECTION BOX FOR CUSTOM FORM ATTACHMENT AS WELL.
######################################################

$dl_file = "     <OPTION VALUE=\" \">N/A</OPTION>\n";
$form_attach = "     <OPTION VALUE=\" \">N/A</OPTION>\n";

$count = 0;
$directory = "$doc_root/media";
if (is_dir($directory)) {
$handle = opendir("$directory");
	while ($files = readdir($handle)) {
		if (strlen($files) > 2) {
			$count++;
			$tmp = "$directory/$files";
			$tmp_space = filesize($tmp);
			$tmp_srt = ucwords($files);
			$site_file[$count] = $tmp_srt . "~~~media~~~$tmp_space~~~" . $files;
		}
	}
closedir($handle);
}

if ($count > 1) { sort($site_file); };
$file_count = count($site_file);

for ($x=0;$x<=$file_count;$x++) {

		$tmp = split("~~~", $site_file[$x]);
		$filename = $tmp[3];
		$filesize = $tmp[2];
		$filedir = $tmp[1];

		if (strlen($filename) > 2) {

			// -----------------------------------------
			// Calculate "Human" Filesize for display
			// -----------------------------------------

			if ($filesize >= 1048576) {
				$filesize = round($filesize/1048576*100)/100;
				$filesize = $filesize . "&nbsp;Mb";
			 } elseif ($filesize >= 1024) {
				$filesize = round($filesize/1024*100)/100;
				$filesize = $filesize . "&nbsp;K";
			 } else {
				$filesize = $filesize . "&nbsp;Bytes";
			 }

			$dl_file .= "     <OPTION VALUE=\"$filename\">$filename [$filesize]</OPTION>\n";
			if (eregi("\.FORM", $filename)) { $form_attach .= "     <OPTION VALUE=\"$filename\">$filename</OPTION>\n"; }

		}

}

##################################################################################
### READ CURRENT SKU DATA INTO MEMORY IF THIS IS AN EDIT RECORD REQUEST
##################################################################################

if ($edit_key != "") {

	$MOD_TITLE = lang("Shopping Cart: Edit Product");
	$result = mysql_query("SELECT * FROM cart_products WHERE PRIKEY = '$edit_key'");
	while ($ary = mysql_fetch_assoc($result)) {
		while (list($key,$val) = each($ary)) {
			if ($val == "") { $val = " "; }					// Make sure Null Values Show up as spaces for proper Javascript operation

			if ( $key != "sub_cats" && $key != "variant_prices" && $key != "variant_names" ) {
			   $val = eregi_replace("\"", "&quot;", $val);		// Make sure Quotes show up in display
			   $val = stripslashes($val);						// Kill Display Errors
			}
			$SKU[$key] = $val;
		}
	}


} else {

	$MOD_TITLE = "".lang("Shopping Cart: Add New Product")."";

}


# Hard-code default num variants if none set
if ( $SKU['num_variants'] < 1 ) {
   $num_variants = 12;
} else {
   $num_variants = $SKU['num_variants'];
}


##################################################################################
### READ IMAGE FILES INTO MEMORY
##################################################################################

$img_selection = "     <OPTION VALUE=\" \">[".lang("No Image")."]</OPTION>\n";

$count = 0;
$directory = "$doc_root/images";
$handle = opendir("$directory");
	while ($files = readdir($handle)) {
		if (strlen($files) > 2) {
			$count++;
			$imageFile[$count] = ucwords($files) . "~~~" . $files;
		}
	}
$numImages = $count;
closedir($handle);

if ($count != 0) {
	sort($imageFile);
	if ($count == 1) {
		$imageFile[0] = $imageFile[1];
	}
	$numImages--;
}


if ( $cartpref->get('manual_image_mode') != 'enabled' ) {
	for ($x=0;$x<=$numImages;$x++) {
	
		$thisImage = split("~~~", $imageFile[$x]);
		if (file_exists("$directory/$thisImage[1]")) {
	//		$tempArray = getImageSize("$directory/$thisImage[1]");
			$origW = $tempArray[0];
			$origH = $tempArray[1];
			$oW = "";
			$oH = "";
	
			if ($origH > 300) {
					$oH = "HEIGHT=300 ";
			}
	
			if ($origW > 275) {
				$oW = "WIDTH=275";
			}
	
			$WH = "$oW $oH ";
		}
	
		$place_value = "$thisImage[1] $WH";
		$place_value = $thisImage[1];
		$place_value = rtrim($place_value);
		$place_value = ltrim($place_value);
	
			if ($thisImage[1] == $SKU['PROD_THUMBNAIL']) {
				$THUMB_JAVA = $place_value;
			}
			if ($thisImage[1] == $SKU['PROD_FULLIMAGENAME']) {
				$FULL_JAVA = $place_value;
			}
	
		$img_selection .= "     <OPTION VALUE=\"$place_value\">$thisImage[0]</OPTION>\n";
	}
}


$k=1;
$c=1;


#######################################################
### START HTML/JAVASCRIPT CODE			  			###
#######################################################

ob_start();
echo "	<script type=\"text/javascript\" src=\"".httpvar().$_SESSION['this_ip'].'/sohoadmin/program/modules/includes/ajaxfileupload.js">'."</script>\n";


?>
<script type="text/javascript">
	
function ajaxFileUpload(){
	$("#loading")
	.ajaxStart(function(){
		//$(this).show();
		$("#buttonUpload").hide();
		$("#loading").show();
	})
	.ajaxComplete(function(){
		//$(this).hide();
		$("#loading").hide();
		$("#buttonUpload").show();
	});

	$.ajaxFileUpload
	(
		{
			url:'../../includes/doajaxfileupload.php?father=cart_product',
			secureuri:false,
			fileElementId:'fileToUpload',
			dataType: 'json',
			data:{name:'logan', id:'id'},
			success: function (data, status){
				if(typeof(data.error) != 'undefined'){
					if(data.error != ''){
						alert(data.error);
					}else{
						alert(data.msg+' uploaded!');						
						//$("#prod_thumbnail option:eq(3)").before('<option value='+data.msg+'>'+data.msg+'</option>');
						$("#prod_thumbnail").prepend('<option value='+data.msg+'>'+data.msg+'</option>');
						//$("#prod_thumbnail").val(data.msg).attr("selected", "selected")						
						$("#prod_fullsize").prepend('<option value='+data.msg+'>'+data.msg+'</option>');
						$("#add_img").prepend('<option value='+data.msg+'>'+data.msg+'</option>');
					}
				}
			},
			error: function (data, status, e){
				alert(e);
			}
		}
	)
	return false;
}
</script>

<STYLE>

	.save {
		background-color: green;
		color: #FFFFFF;
		font-size: 8pt;
		cursor: hand;
		border: inset darkgreen 2px;
		}

</STYLE>


<script language="JavaScript">
	
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

function help_variant() {

	window.open("help_variant.html", "varianthelp", "statusbar=no,locationbar=no, width=525, height=500");
}

SV2_showHideLayers('addCartMenu?header','','hide');
SV2_showHideLayers('blankLayer?header','','hide');
SV2_showHideLayers('linkLayer?header','','hide');
SV2_showHideLayers('newsletterLayer?header','','hide');
SV2_showHideLayers('cartMenu?header','','show');
SV2_showHideLayers('menuLayer?header','','hide');
SV2_showHideLayers('editCartMenu?header','','hide');

function showtab(onlayer) {
   var layers = new Array("PRODINFO", "PRODIMAGES", "PRODVAR", "PRODDISC", "PRODOPT");
   var tabs = new Array("INFOBUT", "IMAGEBUT", "VARBUT", "DISCBUT", "OPTBUT");
   for ( x = 0; x < layers.length; x++ ) {
      layerid = layers[x];
      tabid = tabs[x];

      if ( layerid == onlayer ) {
         setClass(tabid, "tab-on");
         showid(layerid);
      } else {
         setClass(tabid, "tab-off");
         hideid(layerid);
      }
   }
}

function help_catno() {
	alert('The Catalog Reference Number is simply a reference field\nthat will appear on your invoices along with this products Part (SKU)\nnumber for easier order fullfillment.');
}

function help_seccat() {
	alert('The secondary category selection is used to associate this\nproduct with a second category if necessary.  It is not required\nand a third association can be made in \'Advanced Options\'.');
}

function help_inventory() {
	alert('Each time this product is ordered the qty is substracted from\nthe inventory number. When the inventory number reaches\nzero (0), the "Display this product?" option will automatically\nswitch to "No".');
}

function prev_image(imagename, txt) {

	var img_link = "<? echo httpvar().$this_ip; ?>/images/"+imagename;
	var str = "<IMG SRC="+img_link+" BORDER=0 VSPACE=0 HSPACE=0><BR CLEAR=ALL><BR>[ "+txt+" ]";

	if (imagename == " ") { var str = "Image Preview Window"; }

	IMGPREVIEW.innerHTML = str;

}

function save_product() {

   //alert('something');
   var c = 1;
	var error = 0;
	var editing = '<? echo $MOD_TITLE; ?>';
	var editTF = editing.search("Edit");
	var sku = document.P.prod_sku.value;
	var name = document.P.prod_name.value;
	var price = document.P.prod_unitprice.value;
	var cat1 = document.getElementById('prod_category1').value;
	
	document.getElementById('REQSKU').style.color = 'black';
	document.getElementById('cat1_td').style.color = 'black';
	document.getElementById('REQNAME').style.color = 'black';
	document.getElementById('REQPRICE').style.color = 'black';
	
	if (sku == "") {
		var error = 1;
		document.getElementById('REQSKU').style.color = 'red';
	}

	if (name == "") {
		var error = 1;
		document.getElementById('REQNAME').style.color = 'red';
	}

	if (price == "") {
		var error = 1;
		document.getElementById('REQPRICE').style.color = 'red';
	}
	
	if(cat1 == 0){
		var error = 1;
		document.getElementById('cat1_td').style.color = 'red';
		
	}


	if (error == 1) {
		showtab('PRODINFO');
		alert('You have left a required data value blank.\n\nPlease correct and re-save.  No data has been saved for this product.');
	}

	if (error == 0) {
		document.P.submit();
	}

}

// Used only for default thumbnail and fullsize previews
function df_img_swap(filename, idprefix, maxwidth) {
   // hide img tag for saved settting preview
   
   document.getElementById(idprefix+'-img').style.display = 'none';

   // Hide img info <ul>, etc
   document.getElementById(idprefix+'-info').style.display = 'none';

   // Show cancel button
   document.getElementById(idprefix+'-cancel').style.display = 'block';

   if ( filename.length < 5 ) {
      document.getElementById(idprefix+'-preview').innerHTML = '[<? echo lang("No Image Selected"); ?>]';

   } else {
      // Format filename
      filename = filename.split(" ");

      // Set innerHTML of preview output div to selected image (or 'No Image')
      if ( filename[0] == "" ) {
         document.getElementById(idprefix+'-preview').innerHTML = '[Image Preview Area]';
      } else {
         document.getElementById(idprefix+'-preview').innerHTML = '<img src="<? echo httpvar().$_SESSION['docroot_url']; ?>/images/'+filename[0]+'" width="'+maxwidth+'">';
      }
   }
}
// "Cancels" alterations made by df_img_swap() function
function df_img_restore(idprefix) {
   // Clear preview output box
   document.getElementById(idprefix+'-preview').innerHTML = '';

   // Hide cancel button
   document.getElementById(idprefix+'-cancel').style.display = 'none';

   // Show default set image info (show/ide image comes later in this function)
   document.getElementById(idprefix+'-info').style.display = 'block';

   // Reset dropdown back to saved setting
   if ( idprefix == "thumbnail" ) {
      document.getElementById('prod_'+idprefix).value = '<? echo $SKU['PROD_THUMBNAIL']; ?>';
   } else if ( idprefix == "fullsize" ) {
      document.getElementById('prod_'+idprefix).value = '<? echo $SKU['PROD_FULLIMAGENAME']; ?>';
   }

   // Hide image preview if no image defined (otherwise you get broken image)
   if ( document.getElementById('prod_'+idprefix).value == '' || document.getElementById('prod_'+idprefix).value == ' ' ) {
      document.getElementById(idprefix+'-img').style.display = 'none';
   } else {
      document.getElementById(idprefix+'-img').style.display = 'block';
   }

}

// Preview passed image filename in passed preview box
function preview_image(filename, preview_box) {
//   alert(filename.length);
   var thebox = document.getElementById(preview_box);
   if ( filename.length < 5 ) {
      thebox.innerHTML = '[<? echo lang("No Image Selected"); ?>]';
   } else {
      // Format filename (raw dd value passed with width value)
      filename = filename.split(" ");

      // Preview area in popup is bigger and more accomadating
      if ( preview_box == 'addimg-preview_area' ) {
         var previewSize = '';
      } else {
         var previewSize = 'width="175"';
      }
      thebox.innerHTML = '<img src="<? echo httpvar().$_SESSION['docroot_url']; ?>/images/'+filename[0]+'" "'+previewSize+'">';
   }
}

// Called from add image popup
// Adds selected image filename to hidden field, formatted for db field other_images
function addimg_choose() {
   // Format filename (strip width)
   var filename = document.getElementById('add_img').value;
   filename = filename.split(" ");

   // Format filename (strip width)
   document.getElementById('other_images').innerHTML = document.getElementById('other_images').innerHTML + filename[0] + ';';

   show_other_images();
}

// Generates visual list of thumbnails based on value of hidden other_images field
function show_other_images() {
   // Get db-formatted list of other_images
   imglist = document.getElementById('other_images').innerHTML;
   imgs = imglist.split(";");
   var imgdisplay = '';
   for ( i = 0; i < imgs.length; i++ ) {
      if ( imgs[i].length > 5 ) {
         optionsidname = 'thumb_options-'+i;
         imgdisplay += '<div class="other_image_thumb" style="position: relative;" onmouseover="showid(\''+optionsidname+'\');" onmouseout="hideid(\''+optionsidname+'\');">';
         imgdisplay += ' <img src="<? echo httpvar().$_SESSION['docroot_url']; ?>/images/'+imgs[i]+'" width="99">';
         imgdisplay += ' <div id="'+optionsidname+'" style="display: none;">';
         imgdisplay += '  <div class="other_image-killx" onclick="remove_other_image(\''+imgs[i]+'\');">[x]</div>';
         imgdisplay += '  <div class="other_image-mvleft" onclick="mv_other_image(\''+imgs[i]+'\', \'left\');">[&lt;]</div>';
         imgdisplay += '  <div class="other_image-mvright" onclick="mv_other_image(\''+imgs[i]+'\', \'right\');">[&gt;]</div>';
         imgdisplay += ' </div>';
         imgdisplay += '</div>';
      }
   }
   document.getElementById('box-other_images-output').innerHTML = imgdisplay;
}

// Moves a thumbnail to the left in the list of other_images
function mv_other_image(filename, direction) {
   imglist = document.getElementById('other_images').innerHTML;
   imgs = imglist.split(";");

   // Figure out image positions to the left and right
   for ( i = 0; i < imgs.length; i++ ) {
      if ( imgs[i] == filename ) {
         l = (i - 1); // l = left
         r = (i + 1); // r = right
      }
   }

   // Filenames of images directly to left/right of target image in list
   img_before = imgs[l];
   img_after = imgs[r];

   // Remove target file from list
   // Swap in before/after neighbor image
   // Don't do any of this if trying to move first image left or last image right
   if ( direction == "left" && img_before.length > 5 ) {
      imglist = imglist.replace(filename+";", "");
      imglist = imglist.replace(img_before, filename+";"+img_before);
   } else if ( direction == "right" && img_after.length > 5 ) {
      imglist = imglist.replace(filename+";", "");
      imglist = imglist.replace(img_after, img_after+";"+filename);
   }

   document.getElementById('other_images').innerHTML = imglist; // Set hidden field to new value
   show_other_images(); // Refresh image thumbnail list
}

// Sets passed image filename as the default
function df_other_image(filename) {
   document.getElementById('prod_fullsize').value = filename;
}

// Removes an image from the list of other_images
function remove_other_image(filename) {
   var thefield = document.getElementById('other_images');
   var imglist = thefield.innerHTML;
   imglist = imglist.replace(filename+';', "");

   thefield.innerHTML = imglist;

   show_other_images(); // Refresh thumbnail list display
}
</script>

<style>
.other_image-killx, .other_image-mvleft, .other_image-mvright {
   position: absolute;
   /*z-index: 2;*/
   cursor: pointer;
}
.other_image-killx {
   right: 3px;
   top: 3px;
   color: #ff0000;
}

.other_image-mvleft {
   left: 3px;
   bottom: 3px;
   font-weight: bold;
   color: #339959;
}
.other_image-mvright {
   right: 3px;
   bottom: 3px;
   font-weight: bold;
   color: #339959;
}

.tab-off, .tab-on {
   /*margin-bottom: 5px;*/
   font-family: Trebuchet MS, arial;
   font-size: 11px;
   text-align: center;
   width: 110px;
   height: 22px;
   vertical-align: top;
   padding: 0;
   background-color: #efefef;
   border: 1px solid #ccc;
   border-top: 3px solid #ccc;
   border-bottom: 0;
   color: #2e2e2e;
   cursor: pointer;
}

.tab-on {
   color: #000;
   background-color: #efefef;
   border-top: 3px solid #EAA510;
   font-weight: bold;
}


/* PRODIMAGES tab */
#column-thumbandfull {
   float: left;
}
#column-additional {
   float: left;
   MARGIN-LEFT: 10PX;
}
#box-thumbnail {
   width: 300px;
   margin-bottom: 10px;
   border: 1px solid red;
}
#box-fullsize {
   width: 300px;
   border: 1px solid red;
}
#box-additional {
   border: 1px solid red;
   margin-left: 15px;
}
#box-thumbnail, #box-fullsize, #column-additional {
   border: 1px dashed #efefef;
   padding: 10px;
}
#PRODIMAGES h3 {
   font-size: 12px;
   margin-top: 0;
   margin-bottom: 0;
}
#box-thumbnail select, #box-fullsize select {
   /*display: none;*/
   margin-bottom: 4px;
}
#box-thumbnail img.main_img, #box-fullsize img.main_img {
   float: left;
}
/* Image information (caption for thumb and fullsize images) */
ul.img_info {
   /*clear: left;*/
   margin-top: 0;
   list-style-type: none;
   padding-left: 0px;
}
ul.img_info li {
   padding-bottom: 2px;
}
span.info-label {
   /*display: block;*/
}

/* Thumbnail specific */
#box-thumbnail ul {
   margin-left: 100px;
}


/* Fullsize-specific */


#box-fullsize ul {
   clear: left;
}

#column-additional h4 {
   margin-bottom: 0;
}

.preview_area {
   border: 1px dashed #6699cc;
   padding: 5px;
   background-color: #fff;
}
#addimg-preview_area {
   width: 450px;
   height: 150px;
   overflow: auto;
}

/* Container populated by show_other_images() js function */
#box-other_images {
   /*border: 1px solid red;*/
   width: 320px;
}
.other_image_thumb {
   float: left;
   width: 99px;
   height: 99px;
   overflow: hidden;
   border: 1px dashed #efefef;
   background-image: url('<? echo httpvar().$_SESSION['docroot_url']; ?>/sohoadmin/icons/web20_bg.gif');
}

#popup-add_image label {
   display: block;
   margin-top: 10px;
}
</style>

<?
# popup-full_desc
$popup = "";
$popup .= "<p>If you choose to fill-in a detailed description for this sku, it will display when your visitor views the 'More Information' page\n";
$popup .= "for this item.</p>\n";
$popup .= "<p>If you do not fill anything in for the detailed description, the text in the 'Short Description' will be used</p>\n";
$popup .= "<p>If you only want to have one piece of description text for this item (as in, not separate short/full descriptions),\n";
$popup .= "just fill-in the 'Short Description' field and leave the 'Detailed Description' field empty.</p>\n";
echo help_popup("popup-full_desc", "Short/Detailed Description", $popup, "left: 10%;top: 10%;width: 600px;");

# popup-num_variants
$popup = "";
$popup .= "<p>The number in this box should be the total number of sub-category/price-affecting \n";
$popup .= "variant fields to be displayed for this particular cart product. Default is '12' but you can change that by putting\n";
$popup .= "a different (presumably higher) number in this field and clicking 'Save Product'.</p>\n";
$popup .= "<p>Note: You come back and can change this number whenever you want.</p>\n";
$popup .= "<p>Also Note: Technically, this number only determines how many fields display right here on this add/edit product screen.\n";
$popup .= "If you put '25' in this field you'll have 25 rows of sub-category/variant fields. That doesn't mean you have to fill-in all 25 of them.\n";
$popup .= "If you filled-in only 14 of the 25 available options, your visitor/customer will only see the 14 you filled\n";
$popup .= "something in for.</p>\n";
echo help_popup("popup-num_variants", "Add more price sub-category/price variation options to this product", $popup, "left: 10%;top: 10%;width: 600px;");

# [Save Product] button
$save_product_btn = "<button type=\"button\" onclick=\"save_product();\" class=\"greenButton\"><span><span>".lang("SAVE PRODUCT")." &gt;&gt;</span></span></button>";

# [Cancel] button - Add or Edit mode?
if ( $edit_key != "" || $_GET['backto'] == "search_products.php" ) { $cancel_onclick = "search_products.php"; } else { $cancel_onclick = "../shopping_cart.php"; }
$cancel_btn = "<button type=\"button\" onclick=\"document.location.href='".$cancel_onclick."';\" class=\"grayButton\" style=\"margin-right: 15px;\"><span><span>".lang("CANCEL")."</span></span></button>";


$required = "<font COLOR=RED><SUP>*</SUP></font>";


$THIS_DISPLAY = "\n\n";

$THIS_DISPLAY .= "<script type=\"text/javascript\">\n";
$THIS_DISPLAY .= "function expand_height(thingid, addpx) {\n";
$THIS_DISPLAY .= "   isnow = document.getElementById(thingid).style.height;\n";
$THIS_DISPLAY .= "   newval = isnow.replace('px', '');\n";
$THIS_DISPLAY .= "   newval = parseInt(newval)\n";
$THIS_DISPLAY .= "   newval = newval + parseInt(addpx);\n";
$THIS_DISPLAY .= "   newval = newval+'px';\n";
$THIS_DISPLAY .= "   document.getElementById(thingid).style.height = newval;\n";
$THIS_DISPLAY .= "}\n";
$THIS_DISPLAY .= "</script>\n";

//$THIS_DISPLAY .= "<form name=P method=\"post\" ACTION=\"products.php\">\n\n";
//$THIS_DISPLAY .= "   <input type=\"hidden\" name=\"ACTION\" value=\"DELETEIT\">\n";
//$THIS_DISPLAY .= "   <div style=\"float: right;\">Delete Product</div>\n";
//$THIS_DISPLAY .= "</form>\n";

$Upload_Image_Form = "		<div style=\"width:100%;padding:4px;overflow: hidden; position: relative; border: 0px solid; display: block;\"><img id=\"loading\" src=\"../../includes/loading.gif\" style=\"display:none;z-index:2;\">\n";
//$Upload_Image_Form .= "			<form name=\"form\" action=\"\" method=\"POST\" style=\"display:inline;\" enctype=\"multipart/form-data\">\n";
$Upload_Image_Form .= "			<input name=\"MAX_FILE_SIZE\" value=\"88000000\" type=\"hidden\">\n";
$Upload_Image_Form .= "			<input id=\"fileToUpload\" type=\"file\" name=\"fileToUpload\" class=\"input\" style=\"position: relative; border:1px solid red; display: inline;  z-index: 3; opacity: 0; background-color: transparent; cursor: pointer; width:111px;\" onchange=\"return ajaxFileUpload();\">\n";
//$Upload_Image_Form .= "			</form>\n";
$Upload_Image_Form .= "			<div style=\"overflow: hidden; position: absolute; top: 0px; left: 0px; z-index: 1; border: 0px solid; display: inline;\">\n";
$Upload_Image_Form .= "			<button id=\"buttonUpload\" class=\"blueButton\" style=\"cursor: pointer;\"><span><span>Upload Image</span></span></button>\n";
$Upload_Image_Form .= "			</div>\n";
$Upload_Image_Form .= "		</div>\n";



$THIS_DISPLAY .= "<form name=P method=\"post\" ACTION=\"products.php\" enctype=\"multipart/form-data\">\n\n";
$THIS_DISPLAY .= "     <input type=\"hidden\" name=\"ACTION\" value=\"SAVEIT\">\n";

if ($edit_key != "") {
	$THIS_DISPLAY .= "     <input type=\"hidden\" name=\"PriKey\" value=\"$edit_key\">\n\n";
} else {
	$THIS_DISPLAY .= "     <input type=\"hidden\" name=\"PriKey\" value=\"ADDNEW\">\n\n";
}

$THIS_DISPLAY .= "<table border=\"0\" cellpadding=0 cellspacing=\"0\" width=\"100%\">\n";
$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\" colspan=\"".$tabspan."\">\n";

// The product option tabs
// -------------------------------------
$THIS_DISPLAY .= "   <input id=\"INFOBUT\" type=\"button\" value=\"".lang("Product Info")."\" class=\"tab-off\" onclick=\"showtab('PRODINFO');\">&nbsp;\n";
$THIS_DISPLAY .= "   <input id=\"IMAGEBUT\" type=\"button\" value=\"".lang("Product Images")."\" class=\"tab-off\" onclick=\"showtab('PRODIMAGES');\">&nbsp;\n";
$THIS_DISPLAY .= "   <input id=\"VARBUT\" type=\"button\" value=\"".lang("Price Variation")."\" class=\"tab-off\" onclick=\"showtab('PRODVAR');\">&nbsp;\n";
$THIS_DISPLAY .= "   <input id=\"DISCBUT\" type=\"button\" value=\"".lang("Quantity Discounts")."\" class=\"tab-off\" onclick=\"showtab('PRODDISC');\" style=\"width: 120px;\">&nbsp;\n";
$THIS_DISPLAY .= "   <input id=\"OPTBUT\" type=\"button\" value=\"".lang("Advanced Options")."\" class=\"tab-off\" onclick=\"showtab('PRODOPT');\">&nbsp;\n";

$THIS_DISPLAY .= "  </td>\n";


$THIS_DISPLAY .= "  <td align=\"right\" valign=\"top\">".$save_product_btn."</td>\n";

$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n";

// ---------------------------------------------------------
// Start Input Layers Now
// ---------------------------------------------------------


/*---------------------------------------------------------------------------------------------------------*
 ___               _            _     ___         __
| _ \ _ _  ___  __| | _  _  __ | |_  |_ _| _ _   / _| ___
|  _/| '_|/ _ \/ _` || || |/ _||  _|  | | | ' \ |  _|/ _ \
|_|  |_|  \___/\__,_| \_,_|\__| \__| |___||_||_||_|  \___/

/*---------------------------------------------------------------------------------------------------------*/
$THIS_DISPLAY .= "<div ID=PRODINFO>\n";
$THIS_DISPLAY .= " <table border=\"0\" cellpadding=5 cellspacing=\"0\" width=\"100%\" class=\"feature_sub\" style=\"border: 1px solid #ccc;\">\n";
$THIS_DISPLAY .= "  <tr>\n\n";
$THIS_DISPLAY .= "   <td align=\"left\" valign=\"top\">\n";

$THIS_DISPLAY .= "    <table border=\"0\" cellpadding=2 cellspacing=\"0\" class=\"text\" width=\"100%\">\n";

$THIS_DISPLAY .= "     <tr>\n";
$THIS_DISPLAY .= "      <td align=\"left\" valign=\"middle\" width=50% ID=REQSKU>\n";
$THIS_DISPLAY .= $required . lang("Part No. (SKU Number):")."<br>\n";
$THIS_DISPLAY .= "       <input type=\"text\" class=\"tfield\" style='width: 250px;' name=\"prod_sku\" value=\"".$SKU[PROD_SKU]."\">\n";
$THIS_DISPLAY .= "      </td>\n";
$THIS_DISPLAY .= "      <td align=\"left\" valign=\"middle\" width=50% ID=REQPRICE>\n";
$THIS_DISPLAY .= $required . lang("Unit Price:")."<br>\n";
	$paysignq = mysql_query("select PAYMENT_CURRENCY_SIGN from cart_options");
	while($ps = mysql_fetch_array($paysignq)) {
		$dsign = $ps['PAYMENT_CURRENCY_SIGN'];
	}

$THIS_DISPLAY .= "       ".$dsign."<input type=\"text\" class=\"tfield\" style='width: 75px;' name=\"prod_unitprice\" value=\"$SKU[PROD_UNITPRICE]\" ALIGN=ABSMIDDLE>\n";
$THIS_DISPLAY .= "      </td>\n";
$THIS_DISPLAY .= "     </tr>\n";

$THIS_DISPLAY .= "     \n<tr>\n";
$THIS_DISPLAY .= "      <td align=\"left\" valign=\"middle\" ID=REQNAME>\n";
$THIS_DISPLAY .= $required . lang("Part Name (Title):")."<br>\n";
$THIS_DISPLAY .= "       <input type=\"text\" class=\"tfield\" style='width: 250px;' name=\"prod_name\" value=\"$SKU[PROD_NAME]\">\n";
$THIS_DISPLAY .= "      </td>\n";
$THIS_DISPLAY .= "      <td align=\"left\" valign=\"middle\">\n";
$THIS_DISPLAY .= "       <IMG SRC='help.gif' HSPACE=2 VSPACE=2 ALIGN=ABSMIDDLE border=\"0\" style='cursor: hand;' onClick=\"help_catno();\">".lang("Catalog Ref Number:")." </b>\n";
$THIS_DISPLAY .= "       <input type=\"text\" class=\"tfield\" style='width: 250px;' name=\"prod_catno\" value=\"$SKU[PROD_CATNO]\" ALIGN=ABSMIDDLE>\n";
$THIS_DISPLAY .= "      </td>\n";
$THIS_DISPLAY .= "     </tr>\n";

# prod_category1
$THIS_DISPLAY .= "     <tr>\n";
$THIS_DISPLAY .= "      <td align=\"left\" valign=\"middle\" id=\"cat1_td\">\n";
$THIS_DISPLAY .= $required . lang("Category:")."<br>\n";
$THIS_DISPLAY .= "       <select style='width: 250px;' id=\"prod_category1\" name=\"prod_category1\">\n";
$THIS_DISPLAY .= $cat_selection;
$THIS_DISPLAY .= "       </select>\n";
$THIS_DISPLAY .= "      </td>\n";
$THIS_DISPLAY .= "      <td align=\"left\" valign=\"middle\">\n";
$THIS_DISPLAY .= lang("Shipping Charge (A):")."<br>\n";
$THIS_DISPLAY .= "       <input type=\"text\" class=\"tfield\" style='width: 100px;' name=\"prod_shipa\" value=\"$SKU[PROD_SHIPA]\" ALIGN=ABSMIDDLE>\n";
$THIS_DISPLAY .= "      </td>\n";
$THIS_DISPLAY .= "     </tr>\n";

//# prod_category2
$THIS_DISPLAY .= "     <tr>\n";
$THIS_DISPLAY .= "      <td align=\"left\" valign=\"middle\">\n";
$THIS_DISPLAY .= "       <IMG SRC='help.gif' HSPACE=2 VSPACE=2 ALIGN=ABSMIDDLE border=\"0\" style='cursor: hand;' onClick=\"help_seccat();\">".lang("Secondary Category:")."<br>\n";
$THIS_DISPLAY .= "       <select style='width: 250px;' name=\"prod_category2\">\n";
$THIS_DISPLAY .= $cat_selection;
$THIS_DISPLAY .= "       </select>\n";
$THIS_DISPLAY .= "      </td>\n";
$THIS_DISPLAY .= "      <td align=\"center\" valign=\"middle\" style=\"border: 1px solid #000000;\">\n";
$THIS_DISPLAY .= "       <font COLOR=#666666>".lang("If you are using standard shipping")."\n";
$THIS_DISPLAY .= "      </td>\n";
$THIS_DISPLAY .= "     </tr>\n";


# prod_desc
$THIS_DISPLAY .= "     <tr>\n";
$THIS_DISPLAY .= "      <td align=\"left\" valign=\"middle\" colspan=\"2\">\n";
$THIS_DISPLAY .= lang("Short Description (teaser)").":<br>\n";
$THIS_DISPLAY .= "       <textarea name=\"prod_desc\" class=\"tfield\" style='width: 578px; height: 50px;'>".$SKU['PROD_DESC']."</textarea>\n";
$THIS_DISPLAY .= "      </td>\n";
$THIS_DISPLAY .= "     </tr>\n";

# full_desc
$THIS_DISPLAY .= "     <tr>\n";
$THIS_DISPLAY .= "      <td colspan=\"2\" align=\"left\" valign=\"middle\">\n";
$THIS_DISPLAY .= "      <span class=\"help_link\" onclick=\"toggleid('popup-full_desc');\">[?]</span>\n";
$THIS_DISPLAY .= lang("Detailed Description")." (".lang("Optional, HTML allowed")."):<br>\n";
$THIS_DISPLAY .= "       <textarea id=\"full_desc\" name=\"full_desc\" class=\"tfield\" style='width: 675px; height: 100px;'>".base64_decode($SKU['full_desc'])."</textarea>\n";
$THIS_DISPLAY .= "       (<span class=\"blue uline hand\" onclick=\"expand_height('full_desc', 200);\">expand description field</span>)\n";
$THIS_DISPLAY .= "      </td>\n";
$THIS_DISPLAY .= "     </tr>\n";

$THIS_DISPLAY .= "    </table>\n\n";



$THIS_DISPLAY .= "   </td>\n";
$THIS_DISPLAY .= "  </tr>\n";
$THIS_DISPLAY .= " </table>\n\n";
$THIS_DISPLAY .= "</div>\n\n";
// ---------------------------------------------------------


/*---------------------------------------------------------------------------------------------------------*
 ___               _            _     ___
| _ \ _ _  ___  __| | _  _  __ | |_  |_ _| _ __   __ _  __ _  ___  ___
|  _/| '_|/ _ \/ _` || || |/ _||  _|  | | | '  \ / _` |/ _` |/ -_)(_-<
|_|  |_|  \___/\__,_| \_,_|\__| \__| |___||_|_|_|\__,_|\__, |\___|/__/
                                                       |___/

/*---------------------------------------------------------------------------------------------------------*/
# build paths to imgs..
$thumbnail_path = $_SESSION['docroot_path']."/images/".$SKU['PROD_THUMBNAIL'];
$fullsize_path = $_SESSION['docroot_path']."/images/".$SKU['PROD_FULLIMAGENAME'];
$thumbnail_url = httpvar().$_SESSION['docroot_url']."/images/".$SKU['PROD_THUMBNAIL'];
$fullsize_url = httpvar().$_SESSION['docroot_url']."/images/".$SKU['PROD_FULLIMAGENAME'];

# Get image info
$thumbImg = getimagesize($thumbnail_path);
$fullImg = getimagesize($fullsize_path);

# Define max widths (maybe allow user to set these values in the future)
$thumb_maxwidth = 115;
$fullsize_maxwidth = 275;

$THIS_DISPLAY .= "<div id=\"PRODIMAGES\" style=\"display: none;border: 1px solid #ccc;padding: 10px;\">\n";

# popup-add_image
$popup = "";
$popup .= "<label>Available images...</label>\n";
if ( $cartpref->get('manual_image_mode') != 'enabled' ) {
	$popup .= "<select id=\"add_img\" onchange=\"preview_image(this.value, 'addimg-preview_area');\" onkeyup=\"preview_image(this.value, 'addimg-preview_area');\">\n";
	$popup .= $img_selection;
	$popup .= "</select>\n";
}
$popup .= "<label>Preview of selected image...</label>\n";
$popup .= "<div class=\"preview_area\" id=\"addimg-preview_area\"></div>\n";
$popup .= "<div style=\"padding-top: 10px;text-align: right;\">\n";
$popup .= " <input type=\"button\" value=\"Add &gt;&gt;\" onclick=\"addimg_choose();hideid('popup-add_image');\">\n";
$popup .= "</div>\n";
$THIS_DISPLAY .= help_popup("popup-add_image", "Choose image to add...", $popup, "top: 15%;left: 15%;");

# box-thumbnail
if ( $thumbImg[0] > $thumb_maxwidth ) { $forcewidth = " width=\"".$thumb_maxwidth."\""; } else { $forcewidth = ""; }

# Get resized height value for display
if ( $forcewidth != "" ) {
   $resize_ratio = ($thumb_maxwidth / $thumbImg[0]);
   $resize_height = $thumbImg[1] * $resize_ratio;
   $resize_height = round($resize_height);
}
$THIS_DISPLAY .= $Upload_Image_Form;
$THIS_DISPLAY .= " <div id=\"column-thumbandfull\">\n";



$THIS_DISPLAY .= "  <div id=\"box-thumbnail\">\n";
$THIS_DISPLAY .= "   <h3>".lang("Thumbnail Image")."</h3>\n";
# prod_thumbnail
if ( $cartpref->get('manual_image_mode') == 'enabled' ) {
	$THIS_DISPLAY .= "   <input type=\"text\" id=\"prod_thumbnail\" name=\"prod_thumbnail\" class=\"text\" style='width: 250px;' onkeyup=\"df_img_swap(this.value, 'thumbnail', '".$thumb_maxwidth."');\">\n";
} else {
	$THIS_DISPLAY .= "   <select id=\"prod_thumbnail\" name=\"prod_thumbnail\" class=\"text\" style='width: 250px;' onkeyup=\"df_img_swap(this.value, 'thumbnail', '".$thumb_maxwidth."');\" onchange=\"df_img_swap(this.value, 'thumbnail', '".$thumb_maxwidth."');\">\n";
	$THIS_DISPLAY .= $img_selection;
	$THIS_DISPLAY .= "   </select>\n\n";
}
$THIS_DISPLAY .= "   <div id=\"thumbnail-preview\"></div>\n";
$THIS_DISPLAY .= "   <div id=\"thumbnail-cancel\" style=\"display: none;\" onclick=\"df_img_restore('thumbnail');\">\n";
$THIS_DISPLAY .= "    [<span class=\"red uline hand\">cancel - go back to current setting</span>]</div>\n";
# Image defined?
if ( !file_exists($thumbnail_url) ) {
   # No
   $THIS_DISPLAY .= "   <img src=\"".$thumbnail_url."\"".$forcewidth." id=\"thumbnail-img\" class=\"main_img\" style=\"display: none;\"/>\n";
   $THIS_DISPLAY .= "   <ul id=\"thumbnail-info\" class=\"img_info\" style=\"margin-left: 0;\">\n";
   $THIS_DISPLAY .= "    <li>[".lang("No thumbnail image set")."]</li>\n";
} else {
   # Yes - show image info
   $THIS_DISPLAY .= "   <img src=\"".$thumbnail_url."\"".$forcewidth." id=\"thumbnail-img\" class=\"main_img\"/>\n";
   $THIS_DISPLAY .= "   <ul id=\"thumbnail-info\" class=\"img_info\">\n";
   $THIS_DISPLAY .= "    <li><span class=\"info-label\">FileName:</span> ".$SKU['PROD_THUMBNAIL']."</li>";
   $THIS_DISPLAY .= "    <li><span class=\"info-label\">FileSize:</span> ".human_filesize($thumbnail_path)."</li>";
   $THIS_DISPLAY .= "    <li><span class=\"info-label\">Width & Height:</span> ".$thumbImg[0]."px X ".$thumbImg[1]."px</li>";
}
//# Force width?
//if ( $forcewidth != "" ) {
//   $THIS_DISPLAY .= "    <li><span class=\"info-label\">Squeezed To:</span> ".$thumb_maxwidth."px X ".$resize_height."px</li>";
//}
$THIS_DISPLAY .= "   </ul>\n";
$THIS_DISPLAY .= "   <div style=\"clear: both;\"></div>\n"; // Float container border hack
$THIS_DISPLAY .= "  </div>\n"; // End box-thumbnail


# box-fullsize
if ( $fullImg[0] > $fullsize_maxwidth ) { $forcewidth = " width=\"".$fullsize_maxwidth."\""; } else { $forcewidth = ""; }

# Get resized height value for display
if ( $forcewidth != "" ) {
   $resize_ratio = ($fullsize_maxwidth / $fullImg[0]);
   $resize_height = $fullImg[1] * $resize_ratio;
   $resize_height = round($resize_height);
}

$THIS_DISPLAY .= "  <div id=\"box-fullsize\">\n";
$THIS_DISPLAY .= "   <h3>".lang("Full-Size Image")."</h3>\n";
# prod_fullimagename
if ( $cartpref->get('manual_image_mode') == 'enabled' ) {
	$THIS_DISPLAY .= "   <input type=\"text\" id=\"prod_fullsize\" name=\"prod_fullimagename\" class=\"text\" style='width: 250px;' onkeyup=\"df_img_swap(this.value, 'fullsize', '".$fullsize_maxwidth."');\">\n";
} else {
	$THIS_DISPLAY .= "   <select id=\"prod_fullsize\" name=\"prod_fullimagename\" class=\"text\" style='width: 250px;' onkeyup=\"df_img_swap(this.value, 'fullsize', '".$fullsize_maxwidth."');\" onchange=\"df_img_swap(this.value, 'fullsize', '".$fullsize_maxwidth."');\">\n";
	$THIS_DISPLAY .= $img_selection;
	$THIS_DISPLAY .= "   </select>\n\n";	
}
$THIS_DISPLAY .= "   <div id=\"fullsize-preview\"></div>\n";

# cancel - go back to current settings
$THIS_DISPLAY .= "   <div id=\"fullsize-cancel\" style=\"display: none;\" onclick=\"df_img_restore('fullsize');\">\n";
$THIS_DISPLAY .= "    [<span class=\"red uline hand\">cancel - go back to current setting</span>]</div>\n";

# Image defined?
if ( trim($SKU['PROD_FULLIMAGENAME']) == "" ) {
   # No
   $THIS_DISPLAY .= "   <img src=\"".$fullsize_url."\"".$forcewidth." id=\"fullsize-img\" class=\"main_img\" style=\"display: none;\"/>\n";
   $THIS_DISPLAY .= "   <ul id=\"fullsize-info\" class=\"img_info\">\n";
   $THIS_DISPLAY .= "    <li>[".lang("No fullsize image set")."]</li>\n";
   $THIS_DISPLAY .= "   </ul>\n";
} else {
   # Yes - show image info
   $THIS_DISPLAY .= "   <img src=\"".$fullsize_url."\"".$forcewidth." id=\"fullsize-img\" class=\"main_img\"/>\n";
   $THIS_DISPLAY .= "   <ul id=\"fullsize-info\" class=\"img_info\">\n";
   $THIS_DISPLAY .= "    <li><span class=\"info-label\">FileName:</span> ".$SKU['PROD_FULLIMAGENAME']."</li>";
   $THIS_DISPLAY .= "    <li><span class=\"info-label\">FileSize:</span> ".human_filesize($fullsize_path)."</li>";
   $THIS_DISPLAY .= "    <li><span class=\"info-label\">Width &amp; Height:</span> ".$fullImg[0]."px X ".$fullImg[1]."px</li>";
   $THIS_DISPLAY .= "   </ul>\n";
}
//# Force width?
//if ( $forcewidth != "" ) {
//   $THIS_DISPLAY .= "    <li><span class=\"info-label\">Squeezed To:</span> ".$fullsize_maxwidth."px X ".$resize_height."px</li>";
//}
$THIS_DISPLAY .= "  </div>\n"; // End box-fullsize
$THIS_DISPLAY .= " </div>\n"; // End column-thumbandfull


# help-additional_imgs
$popup = "<p>If you want to display more pictures of this product sku beyond just a thumbnail image and one full-size image, \n";
$popup .= "you can add those other images here.</p>\n";
$popup .= "<p>When your site visitors view this product's \"More Information\" page there will be a section for \"Pictures\"\n";
$popup .= "where this product's \"Full-Size Image\" will display. If you have added Additional Product Images here, they'll see a set of thumbnails \n";
$popup .= "next to the Full-Size Image that they can click on to view in larger format (vs. thumbnail size).</p>\n";
$extra = array("onclose" => "show_dropdowns();");
$THIS_DISPLAY .= help_popup("help-additional_imgs", "Additional Product Images", $popup, "", $extra);

# box-fullsize-additional
$THIS_DISPLAY .= " <div id=\"column-additional\">\n";
$THIS_DISPLAY .= "   <h3>".lang("Additional Product Images")."<span class=\"help_link unbold\" onclick=\"toggleid('help-additional_imgs');hide_dropdowns();\">[?]</span></h3>\n";

if ( $cartpref->get('manual_image_mode') == 'enabled' ) {
	$THIS_DISPLAY .= "   <p class=\"nomar_top\" style=\"width: 250px;\">Additional images disabled when manual image entry is turned on (see preference check box on main\n";
	$THIS_DISPLAY .= "    <a href=\"search_products.php\">Enter/Edit Products page</a>.</p>\n";
} else {
	$THIS_DISPLAY .= "   <p class=\"nomar_top\">[<span class=\"green uline hand\" onclick=\"toggleid('popup-add_image');\">Add Image</span>]</p>\n";
	$THIS_DISPLAY .= "   <textarea id=\"other_images\" name=\"other_images\" style=\"display: none;width: 300px;\">".trim($SKU['other_images'])."</textarea>\n";
	
	# box-other_images
	$THIS_DISPLAY .= "   <div id=\"box-other_images\">\n";
	$THIS_DISPLAY .= "    <div id=\"box-other_images-output\"></div>\n"; // Populated by show_other_images() js function
	$THIS_DISPLAY .= "    <div style=\"clear: both;\"></div>\n"; // Float container border hack
	$THIS_DISPLAY .= "   </div>\n";
	$THIS_DISPLAY .= "   <script type=\"text/javascript\">show_other_images();</script>\n";
}
$THIS_DISPLAY .= "  <div style=\"clear: both;\"></div>\n"; // Float container border hack
$THIS_DISPLAY .= " </div>\n"; // End column-additional

$THIS_DISPLAY .= "  <div style=\"clear: both;\"></div>\n"; // Float container border hack
$THIS_DISPLAY .= " </div>\n\n"; // End PRODIMAGES


/*---------------------------------------------------------------------------------------------------------*
 ___       _           __   __           _        _    _
| _ \ _ _ (_) __  ___  \ \ / /__ _  _ _ (_) __ _ | |_ (_) ___  _ _
|  _/| '_|| |/ _|/ -_)  \ V // _` || '_|| |/ _` ||  _|| |/ _ \| ' \
|_|  |_|  |_|\__|\___|   \_/ \__,_||_|  |_|\__,_| \__||_|\___/|_||_|
/*---------------------------------------------------------------------------------------------------------*/
# Restore price variation arrays
$sub_cats = unserialize($SKU['sub_cats']);
$variant_names = unserialize($SKU['variant_names']);
$variant_prices = unserialize($SKU['variant_prices']);

//echo "<pre>".$SKU['sub_cats']."</pre>";
//echo testArray(unserialize($SKU['sub_cats']));

$THIS_DISPLAY .= "  <div id=\"PRODVAR\" style='display: block;'>\n";
$THIS_DISPLAY .= "   <input type=\"hidden\" name=\"num_variants\" value=\"".$num_variants."\">\n";
$THIS_DISPLAY .= "   <table border=\"0\" cellpadding=5 cellspacing=\"0\" width=\"100%\" class=\"feature_sub\" style=\"border: 1px solid #ccc;\">\n";
$THIS_DISPLAY .= "    <tr>\n\n";
$THIS_DISPLAY .= "     <td align=\"left\" valign=\"top\">\n";

$THIS_DISPLAY .= "      <br>\n";
$THIS_DISPLAY .= "      <table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"text\" width=95% align=\"center\">\n";

$THIS_DISPLAY .= "       <tr>\n";

# Sub-Categories
$THIS_DISPLAY .= "        <td align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "         <h3>Product Sub-Categories</h3>\n";
for ( $x = 1; $x <= $num_variants; $x++ ) {
   $THIS_DISPLAY .= lang("Sub-Category")." ".$x.": <input type=\"text\" class=\"tfield\" style='width: 150px;' name=\"sub_cat".$x."\" value=\"".$sub_cats[$x]."\"><BR>\n";
}
$THIS_DISPLAY .= "        </td>\n";

# Price variations
$THIS_DISPLAY .= "        <td align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "         <h3>Price-Affecting Options</h3>\n";
for ( $x = 1; $x <= $num_variants; $x++ ) {
   $THIS_DISPLAY .= lang("Variant")." ".$x.": <input type=\"text\" class=\"tfield\" style='width: 150px;' name=\"variant_name".$x."\" value=\"".$variant_names[$x]."\"> \n";
   $THIS_DISPLAY .= "&nbsp;&nbsp;".$dsign."<input type=\"text\" class=\"tfield\" style='width: 75px;' name=\"variant_price".$x."\" value=\"".$variant_prices[$x]."\"><br>\n";

}
$THIS_DISPLAY .= "        </td>\n";
$THIS_DISPLAY .= "       </tr>\n";

# Add more variations...
$THIS_DISPLAY .= "       <tr>\n";
$THIS_DISPLAY .= "        <td align=\"left\" valign=\"top\" COLSPAN=2>\n";
$THIS_DISPLAY .= "         [ <span onclick=\"toggleid('num_variants_field');\" class=\"blue uline hand\">\n";
$THIS_DISPLAY .= "         Add price variation fields</span> ]\n";
$THIS_DISPLAY .= "         <div id=\"num_variants_field\" style=\"display: none;\">\n";
$THIS_DISPLAY .= "          How many total subcategory/variant fields should there be?\n";
$THIS_DISPLAY .= "          <br/><input type=\"text\" name=\"num_variants\" class=\"tfield\" value=\"".$num_variants."\" style=\"width: 50px;\">\n";
$THIS_DISPLAY .= "          <span class=\"help_link\" onclick=\"toggleid('popup-num_variants');\">[?]</span>\n";
$THIS_DISPLAY .= "         </div>\n";
$THIS_DISPLAY .= "        </td>\n";
$THIS_DISPLAy .= "       </tr>\n";

# Show me what this looks like...
$THIS_DISPLAY .= "       <tr>\n";
$THIS_DISPLAY .= "        <td align=\"center\" valign=\"top\" COLSPAN=2>\n";
$THIS_DISPLAY .= "         <span onclick=\"help_variant();\" class=\"help_link\">\n";
$THIS_DISPLAY .= "          [?] ".lang("Show me what this looks like in operation and how the variant set-up works.")."\n";
$THIS_DISPLAY .= "         </span>\n";
$THIS_DISPLAY .= "        </td>\n";
$THIS_DISPLAy .= "       </tr>\n";

$THIS_DISPLAY .= "      </table>\n";

$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "    </tr>\n";
$THIS_DISPLAY .= "   </table>\n";
$THIS_DISPLAY .= "  </div>\n\n";
include_once('qty_disc.php');

/*---------------------------------------------------------------------------------------------------------*
   _       _                               _      ___         _    _
  /_\   __| |__ __ __ _  _ _   __  ___  __| |    / _ \  _ __ | |_ (_) ___  _ _   ___
 / _ \ / _` |\ V // _` || ' \ / _|/ -_)/ _` |   | (_) || '_ \|  _|| |/ _ \| ' \ (_-<
/_/ \_\\__,_| \_/ \__,_||_||_|\__|\___|\__,_|    \___/ | .__/ \__||_|\___/|_||_|/__/
                                                       |_|
/*---------------------------------------------------------------------------------------------------------*/
$THIS_DISPLAY .= "  <div ID=PRODOPT style='display: none;'>\n";
$THIS_DISPLAY .= "   <table border=\"0\" cellpadding=4 cellspacing=\"0\" width=\"100%\" class=\"feature_sub\" style=\"border: 1px solid #ccc;\">\n";
$THIS_DISPLAY .= "    <tr>\n\n";
$THIS_DISPLAY .= "     <td align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "      <table border=\"0\" cellpadding=8 cellspacing=\"0\" class=\"text\" width=\"100%\">\n";
$THIS_DISPLAY .= "       <tr>\n";
$THIS_DISPLAY .= "        <td align=\"left\" valign=\"top\" width=33%>\n";
$THIS_DISPLAY .= "         ".lang("Charge Tax for this product?")."<br>\n";
$THIS_DISPLAY .= "         <select name=\"option_chargetax\" class=smtext style='width: 75px;'>\n";
$THIS_DISPLAY .= "          <option value=\"Y\">".lang("Yes")." </option>\n";
$THIS_DISPLAY .= "          <option value=\"N\">".lang("No")." </option>\n";
$THIS_DISPLAY .= "         </select>\n";
$THIS_DISPLAY .= "        </td>\n";
$THIS_DISPLAY .= "        <td align=\"left\" valign=\"top\" width=33%>\n";
$THIS_DISPLAY .= "         ".lang("Charge Shipping for this product?")."<br>\n";
$THIS_DISPLAY .= "         <select name=\"option_chargeshipping\" class=smtext style='width: 75px;'>\n";
$THIS_DISPLAY .= "          <option value=\"Y\">".lang("Yes")." </option>\n";
$THIS_DISPLAY .= "          <option value=\"N\">".lang("No")." </option>\n";
$THIS_DISPLAY .= "         </select>\n";
$THIS_DISPLAY .= "        </td>\n";
$THIS_DISPLAY .= "        <td align=\"left\" valign=\"top\" width=33%>\n";
$THIS_DISPLAY .= "         ".lang("Security Code:")."<br>\n";
$THIS_DISPLAY .= "         <select name=\"option_securitycode\" class=smtext style='width: 150px;'>\n";
$THIS_DISPLAY .= "          <option value=\"Public\" SELECTED>".lang("Public")." </option>\n";

// ----------------------------------------------------------------------
// Pull Security Codes [groups] from data table for selection
// ----------------------------------------------------------------------

$result = mysql_query("SELECT * FROM sec_codes ORDER BY security_code");
$num_groups = mysql_num_rows($result);
if ($num_groups > 0) {
	while($GROUP = mysql_fetch_array($result)) {
		$THIS_DISPLAY .= "      <option value=\"$GROUP[security_code]\">$GROUP[security_code] </option>\n";
	}
}

// ----------------------------------------------------------------------

$THIS_DISPLAY .= "         </select>\n";
$THIS_DISPLAY .= "		<font style=\"font-size: 7pt;\" color=\"#999999\">(Select who can purchase this product)</font>\n";

$THIS_DISPLAY .= "        </td>\n";
$THIS_DISPLAY .= "       </tr>\n";
$THIS_DISPLAY .= "       <tr>\n";
$THIS_DISPLAY .= "        <td align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "         ".lang("Attachment Page (Detail Page):")." <BR><br>\n";

$THIS_DISPLAY .= "         <select name=\"option_detailpage\" class=smtext style='width: 150px;'>\n";
$THIS_DISPLAY .= "         ".$page_selection;
$THIS_DISPLAY .= "         </select>\n";

$THIS_DISPLAY .= "        </td>\n";
$THIS_DISPLAY .= "        <td align=left valign=TOP>\n";
$THIS_DISPLAY .= "         ".lang("Recommend this product")."<br>\n";

$THIS_DISPLAY .= "         <select name=\"option_showatedit\" class=smtext style='width: 75px;'>\n";
$THIS_DISPLAY .= "               <option value=\"Y\">".lang("Yes")." </option>\n";
$THIS_DISPLAY .= "               <option value=\"N\">".lang("No")." </option>\n";
$THIS_DISPLAY .= "         </select>\n";

$THIS_DISPLAY .= "        </td>\n";
$THIS_DISPLAY .= "        <td align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "         ".lang("Recommended Products like this one:")."<br>\n";

$THIS_DISPLAY .= "         <input type=\"text\" class=\"tfield\" name=\"option_recommendsku\" value=\"$SKU[OPTION_RECOMMENDSKU]\" ALIGN=ABSMIDDLE style='width: 175px;'>\n";

$THIS_DISPLAY .= "         <font COLOR=#999999 style='font-size: 7pt;'>(".lang("Enter multiple sku numbers seperated by comma").")</font>\n";
$THIS_DISPLAY .= "        </td>\n";

$THIS_DISPLAY .= "       </tr>\n";

$THIS_DISPLAY .= "       <tr>\n";

$THIS_DISPLAY .= "        <td align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "         ".lang("When customers purchase this product require Form Data from:")."<br/> \n";
$THIS_DISPLAY .= "         <select name=\"option_formdata\" class=smtext style='width: 150px;'>\n";
$THIS_DISPLAY .= "         ".$form_attach;
$THIS_DISPLAY .= "         </select>\n";
$THIS_DISPLAY .= "          <font size=1><input TYPE=RADIO name=option_formdisplay value=\"PERQTY\"> ".lang("Per Qty")." <input TYPE=RADIO name=option_formdisplay value=\" \"> ".lang("Ignore Qty")."\n";
$THIS_DISPLAY .= "        </td>\n";

$THIS_DISPLAY .= "        <td align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "         ".lang("Purchase of this Sku allows your customer to download the following file:")."<br>\n";
$THIS_DISPLAY .= "         <select name=\"option_downloadfile\" class=smtext style='width: 200px; font-size: 7pt;'>\n";
$THIS_DISPLAY .= "          ".$dl_file;
$THIS_DISPLAY .= "         </select>\n";
$THIS_DISPLAY .= "        </td>\n";

$THIS_DISPLAY .= "        <td align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "         ".lang("Display this Product")."?<br>\n";
$THIS_DISPLAY .= "         <select name=\"option_display\" class=smtext style='width: 75px;'>\n";
$THIS_DISPLAY .= "               <option value=\"Y\">".lang("Yes")." </option>\n";
$THIS_DISPLAY .= "               <option value=\"N\">".lang("No")." </option>\n";
$THIS_DISPLAY .= "         </select>\n";


$THIS_DISPLAY .= "        </td>\n";

$THIS_DISPLAY .= "       </tr>\n";


$THIS_DISPLAY .= "       <tr>\n";
//$THIS_DISPLAY .= "       <td>&nbsp;\n";
//$THIS_DISPLAY .= "       </td>\n";
//$THIS_DISPLAY .= "        <td align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "        <td rowspan=\"2\" style=\"line-height:13px;\" align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "         ".lang("Additional Category Association:")."<br>\n";

$THIS_DISPLAY .= "         <select name=\"prod_category3\" class=smtext style='width: 150px;'>\n";
$THIS_DISPLAY .= "         ".$cat_selection;
$THIS_DISPLAY .= "         </select><br/><br/>\n";


/////membership item

$THIS_DISPLAY .= "<script>
function checkUserSignupOpt(){
	if (document.getElementById('option_usersignup').selectedIndex==1){
		document.getElementById('securegroups_span').style.visibility='visible';
	} else {
		document.getElementById('securegroups_span').style.visibility='hidden';
		document.getElementById('option_usersignup').selectedIndex=0;
	}
}	
</script>\n";
$option_usersignup_vis = 'hidden';
$nosel=' selected="selected"';
$yessel=' ';
$secreadonly=' ';
$currentgroupchoice='';
$expdayz = 0;
if($_REQUEST['edit_key']!=''){ $edit_key = $_REQUEST['edit_key']; }
if($edit_key != ''){
	$find_secstuffq = mysql_query("select * from cart_products_usersignup where product_id='".$edit_key."' limit 1");
	if(mysql_num_rows($find_secstuffq) == 1){
		$find_secstuff = mysql_fetch_assoc($find_secstuffq);
		if($find_secstuff['groups']!=''){
			$yessel=' selected="selected"';
			$nosel=' ';
			$option_usersignup_vis = 'visible';
			$currentgroupchoice = $find_secstuff['groups'];
			$expdayz = $find_secstuff['expiration'];
		}
	}
	
}
$secq = mysql_query("SELECT * FROM sec_codes ORDER BY security_code");

//Secure user purchase


$THIS_DISPLAY .= "         ".lang("Create member login for buyer to access pages protected by security codes?")."<br/> \n";
$secgroupwarning = '';

if (mysql_num_rows($secq) < 1) {
//	$THIS_DISPLAY .= "          <font size=1><input TYPE=RADIO name=\"option_usersignup\" value=\"\" checked readonly> ".lang("no (default)")."<input TYPE=RADIO name=\"option_usersignup\" value=\"yes\" disabled> ".lang("yes")."\n";
	$secgroupwarning = "<br/><i>(".lang("There are currently no member login security codes (groups) created").". \n".lang("You must first create a member login security code to use this feature.").")</i>";
	$secreadonly=' disabled="disabled"';
	$nosel=' selected="selected"';
	$yessel=' ';
} 

//$THIS_DISPLAY .= "          <font size=1><input TYPE=RADIO name=\"\" onClick=\"checkUserSignupOpt();\"  value=\"\" > ".lang("no (default)")."<input ".$yessel." onClick=\"checkUserSignupOpt();\" onBlur=\"checkUserSignupOpt();\" TYPE=RADIO name=\"option_usersignup\" value=\"yes\"> ".lang("yes")."\n";

$THIS_DISPLAY .= "			<select name=\"option_usersignup\" id=\"option_usersignup\" onBlur=\"checkUserSignupOpt();\" onChange=\"checkUserSignupOpt();\" class=smtext style='width: 95px;'>\n";
$THIS_DISPLAY .= "			<OPTION VALUE=\"\" ".$nosel.">".lang('No (default)')."</OPTION>\n";
$THIS_DISPLAY .= "			<OPTION ".$secreadonly." VALUE=\"yes\" ".$yessel.">".lang('Yes')."</OPTION>\n";
$THIS_DISPLAY .= "			</select>\n";

$THIS_DISPLAY .= $secgroupwarning;

$THIS_DISPLAY .= "         <div id=\"securegroups_span\" style=\"margin-top:3px;".$option_usersignup_vis.";\">\n";

$THIS_DISPLAY .= lang('Security Code (Group)').": <select name=\"option_usersignup_group\" id=\"option_usersignup_group\" class=smtext style='width: 150px;'>\n";
while($GROUP = mysql_fetch_assoc($secq)) {
	$usethisguy = '';
	if($currentgroupchoice==$GROUP['security_code'] && $GROUP['security_code']!=''){
		$usethisguy = ' selected="selected"';	
	}
	$THIS_DISPLAY .= "           <OPTION ".$usethisguy." VALUE=\"".$GROUP['security_code']."\">".$GROUP['security_code']."</OPTION>\n";
}
$THIS_DISPLAY .= "         </select><br/>\n";

$THIS_DISPLAY .= lang("Expires after")." <input id=\"expiration\" name=\"expiration\" type=\"text\" class=\"tfield\" style=\"width: 30px;\" VALUE=\"".$expdayz."\"> ".lang('days').".\n";
$THIS_DISPLAY .= "<i>(".lang("Set to 0 for no expiration").")</i><br/>\n";



$THIS_DISPLAY .= "</div>\n";

//$THIS_DISPLAY .= "        </td>\n";
/////end membership item




$THIS_DISPLAY .= "        </td>\n";


$THIS_DISPLAY .= "        <td align=\"left\" valign=\"top\">\n";
//$THIS_DISPLAY .= "         ".lang("User-Defined Variable")." (B):<br>\n";

$localcountryq = mysql_query("select BIZ_COUNTRY from cart_options");
while($lcs = mysql_fetch_assoc($localcountryq)) {
	$bizcountry = $lcs['BIZ_COUNTRY'];
}

$THIS_DISPLAY .= "         ".lang("Product Weight")." in ";
if(!preg_match('/UNITED STATES/i', $bizcountry)){
	$THIS_DISPLAY .= "kgs:<br>\n";
} else {
	$THIS_DISPLAY .= "lbs:<br>\n";
}

$THIS_DISPLAY .= "         <input type=\"text\" class=\"tfield\" style='width: 100px;' name=\"prod_shipb\" value=\"$SKU[PROD_SHIPB]\" ALIGN=ABSMIDDLE>\n";

$THIS_DISPLAY .= "         <br/><br/>Dimension in ";

$dems = explode(';', $SKU['PROD_SHIPC']);
if(count($dems) == 3){
	$ship_length = $dems['0'];
	$ship_width = $dems['1'];
	$ship_height = $dems['2'];
} else {
	$ship_length = '';
	$ship_width = '';
	$ship_height = '';
}

if(!preg_match('/UNITED STATES/i', $bizcountry)){
	$THIS_DISPLAY .= " cm:<br/>\n";
} else {
	$THIS_DISPLAY .= " inches:<br/>\n";
}

$THIS_DISPLAY .= "         Length <input type=\"text\" class=\"tfield\" style='width: 30px;' name=\"ship_length\" value=\"$ship_length\" ALIGN=ABSMIDDLE>\n";
$THIS_DISPLAY .= "         &nbsp;&nbsp;Width <input type=\"text\" class=\"tfield\" style='width: 30px;' name=\"ship_width\" value=\"$ship_width\" ALIGN=ABSMIDDLE>\n";
$THIS_DISPLAY .= "         &nbsp;&nbsp;Height <input type=\"text\" class=\"tfield\" style='width: 30px;' name=\"ship_height\" value=\"$ship_height\" ALIGN=ABSMIDDLE>\n";


$THIS_DISPLAY .= "        </td>\n";
$THIS_DISPLAY .= "        <td align=\"left\" valign=\"top\">\n";

// Inventory Checking Addition
$THIS_DISPLAY .= "         ".lang("Inventory Count:")." <input type=\"text\" SIZE=5 class=\"tfield\" name=\"option_inventory_num\" value=\"$SKU[OPTION_INVENTORY_NUM]\"><br/><BR><BR>\n";
$THIS_DISPLAY .= "         <IMG SRC='help.gif' HSPACE=2 VSPACE=2 ALIGN=ABSMIDDLE border=\"0\" style='cursor: hand;' onClick=\"help_inventory();\">\n";


$THIS_DISPLAY .= "         ".lang("Special Tax Rate:")." <input type=\"text\" class=\"tfield\" maxlength='5' style='width: 30px;' name=\"special_tax\" value=\"$SKU[SPECIAL_TAX]\" ALIGN=ABSMIDDLE>%\n";
$THIS_DISPLAY .= "        </td>\n";

$THIS_DISPLAY .= "       </tr>\n";


$THIS_DISPLAY .= "       <tr>\n";
//$THIS_DISPLAY .= "        <td colspan=2 align=\"left\" valign=\"top\">\n";
//$THIS_DISPLAY .= "        </td>\n";
$THIS_DISPLAY .= "        <td align=\"left\" valign=\"top\" COLSPAN=2>\n";
$THIS_DISPLAY .= "         ".lang("Searchable Keywords")."<br>\n";
$THIS_DISPLAY .= "         <TEXTAREA class=\"tfield\" style='width: 425px; height: 55px;' name=\"option_keywords\">$SKU[OPTION_KEYWORDS]</TEXTAREA>\n";
$THIS_DISPLAY .= "        </td>\n";

$THIS_DISPLAY .= "       </tr>\n";




$THIS_DISPLAY .= "      </table>\n\n";

$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "    </tr>\n";
$THIS_DISPLAY .= "   </table>\n";
$THIS_DISPLAY .= "  </div>\n\n";

// ---------------------------------------------------------

eval(hook("products.php:addninfo"));

# [Save Product] button
$THIS_DISPLAY .= "  <div style=\"margin-top: 10px;text-align: right;\">".$cancel_btn."".$save_product_btn."</div>\n";

$THIS_DISPLAY .= " </form>\n\n";


// Image is too big...stretches popup closebar down below scroll fold...must cut it up later...going back to old new window popup for now
//# help-variant popup
//$popup = "<img src=\"images/variant_example.gif\">\n";;
//$THIS_DISPLAY .= help_popup("help-variant", "Price Variations", $popup, "left: 10%;top: 10%;width: 600px;");


echo $THIS_DISPLAY;

####################################################################

echo "<script language=\"javascript\" type=\"text/javascript\">\n\n";
echo "checkUserSignupOpt();\n";
echo "showtab('PRODINFO');\n";

echo "     // ----------------------------------------------------------\n";
echo "     // Update Drop Down Box Selections with Edit Value if Exists\n";
echo "     // ----------------------------------------------------------\n\n";
echo "     document.P.prod_category1.value = '$SKU[PROD_CATEGORY1]';\n";
echo "     document.P.prod_category2.value = '$SKU[PROD_CATEGORY2]';\n";

echo "     document.P.option_chargetax.value = '$SKU[OPTION_CHARGETAX]';\n";
echo "     document.P.option_chargeshipping.value = '$SKU[OPTION_CHARGESHIPPING]';\n";
echo "     document.P.option_securitycode.value = '$SKU[OPTION_SECURITYCODE]';\n";
echo "     document.P.option_detailpage.value = '$SKU[OPTION_DETAILPAGE]';\n";
echo "     document.P.option_showatedit.value = '$SKU[OPTION_SHOWATEDIT]';\n";
echo "     document.P.option_formdata.value = '$SKU[OPTION_FORMDATA]';\n";

	if ($SKU[OPTION_FORMDISPLAY] == "PERQTY") {
		echo "     document.P.option_formdisplay[0].checked = true;\n";
	} else {
		echo "     document.P.option_formdisplay[1].checked = true;\n";
	}

echo "     document.P.option_downloadfile.value = '$SKU[OPTION_DOWNLOADFILE]';\n";
echo "     document.P.option_display.value = '$SKU[OPTION_DISPLAY]';\n";
echo "     document.P.prod_category3.value = '$SKU[PROD_CATEGORY3]';\n\n";

//echo "df_img_swap(document.getElementById('prod_thumbnail').value, 'thumbnail', '".$thumb_maxwidth."');\n";
//echo "df_img_swap(document.getElementById('prod_fullsize').value, 'fullsize', '".$fullsize_maxwidth."');\n";
echo "df_img_restore('thumbnail');\n";
echo "df_img_restore('fullsize');\n";

echo "     // ----------------------------------------------------------\n\n";

?>
// Show image setting in preview box on Product Images tab
var setimg = document.getElementById('prod_fullsize').value;
if ( setimg != "" ) {
//   prev_image(setimg, 'Full-size image');
}


// TESTING: Force Product Images tab to load by default
//showtab('PRODIMAGES');
</script>


<?
# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$module = new smt_module($module_html);
$module->add_breadcrumb_link("Shopping Cart Menu", "program/modules/mods_full/shopping_cart.php");

# Add or Edit?
if ( $edit_key != "" ) {
   # Edit
   $module->heading_text = $SKU['PROD_NAME'];
   $module->add_breadcrumb_link("Find/Edit Products", "program/modules/mods_full/shopping_cart/search_products.php");
   $module->add_breadcrumb_link($SKU['PROD_NAME'], "program/modules/mods_full/shopping_cart/".basename($_SERVER['PHP_SELF'])."?edit_key=".$edit_key);

//   $save_product_btn

} else {
   # Add
   $module->heading_text = "Add New Product";
   $module->add_breadcrumb_link("Add New Product", "program/modules/mods_full/shopping_cart/".basename($_SERVER['PHP_SELF']));
}
$module->icon_img = "program/includes/images/shopping-icon-med.png";
$module->good_to_go();
?>