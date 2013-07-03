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
$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../../../includes/product_gui.php');
chdir($curdir);


//mysql_query("alter table cart_category add column prod_count varchar(255)");
//mysql_query("alter table cart_category drop column prod_count");
if(!table_exists("cart_category")){
	create_table("cart_category");
}


$result = mysql_query("SHOW COLUMNS FROM cart_category LIKE 'sortorder'");
if(mysql_num_rows($result)==0){
	mysql_query("alter table cart_category add column sortorder int(20) not null default '999' after product_count");	
}

#########################################################
### IF FORM SUBMITTED TO SAVE CATEGORY DATA, PERFORM
### SAVE/UPDATE ROUTINE AND RETURN WITH NOTIFICATION
#########################################################
$cartopts = new userdata("cart");

if($cartopts->get("category_customsort")!='custom'){
	$alphacheck='checked';
}

$display_cat_prodcount = '';
if($cartopts->get("hide_cat_prodcount")=='hide'){
	$display_cat_prodcount = 'hide';
}

if($_POST['category_sort']=='category_sort'){
	if($_POST['display_cat_prodcount']=='show'){
		$cartopts->set("hide_cat_prodcount",'');
		$display_cat_prodcount = '';
	} else {
		$cartopts->set("hide_cat_prodcount",'hide');
		$display_cat_prodcount = 'hide';
	}
	
	if($_POST['category_customsort']=='alpha'){
		$cartopts->set("category_customsort",'');
		$alphacheck='checked';
	} else {
		$cartopts->set("category_customsort",'custom');
		$alphacheck='';
		
		if($_POST['newCatOrder'] != ''){
			$_POST['newCatOrder']=preg_replace('/^,/', '', $_POST['newCatOrder']);
		}
		$newcatsort_ar = explode(',', $_POST['newCatOrder']);
		$arsortc=1;
		foreach($newcatsort_ar as $valz){
			mysql_query("update cart_category set sortorder='".$arsortc."' where keyfield='".$valz."'");
			++$arsortc;
		}
	}
}

# Save category name edit action
if ( $_POST['action'] == "savecat" && $_POST['cat_name'] != "" ) {
	if($_POST['newMenu'] != ''){
		$_POST['newMenu']=preg_replace('/^,/', '', $_POST['newMenu']);
	}
	$nm_ar = explode(',', $_POST['newMenu']);
	$mainsubs = '';
	$thissubmenu = '';
	$main_subs_par = '';
	if($_POST['cat_id']!=''){
		$clearq = '';
		$thiscatsubs = '';
		$par_q = mysql_query("select * from cart_category where parent='".$_POST['cat_id']."'");
		while($par_ar=mysql_fetch_assoc($par_q)){
			$thiscatsubs .= $par_ar['subs'].',';
		}
		$thiscatsubs_ar = explode(',', preg_replace('/,$/', '', $thiscatsubs));
		foreach($thiscatsubs_ar as $czsubs){
			if($czsubs > 0){
				$clearq .= "keyfield=".$czsubs." or ";	
			}
		}
		$clearq .= "parent='".$_POST['cat_id']."' or ";
		$clearq = preg_replace('/ or $/', '', $clearq);
		//echo "update cart_category set subs='', parent='', level='2' where ".$clearq;
		mysql_query("update cart_category set subs='', parent='', level='2' where ".$clearq);
	}
	
	foreach($nm_ar as $nmv){
		if(preg_match('/>/', $nmv)){
			$nmv_ar = explode('>', $nmv);
			$subcnt = 0;
			$mainsubitem = $nmv_ar['0'];
			$thissubmenu = '';
			$thissubmenu_q = '';
			foreach($nmv_ar as $submnu){
				if($subcnt > 0){
					$thissubmenu .= $submnu.',';
					$thissubmenu_q .= "keyfield='".$submnu."' or ";
				}
				++$subcnt;
			}
			$thissubmenu = preg_replace('/,$/', '', $thissubmenu);
			$thissubmenu = preg_replace('/ $/', '', $thissubmenu);
			$thissubmenu_q = preg_replace('/ or $/', '', $thissubmenu_q);
			mysql_query("update cart_category set subs='".$thissubmenu."' where keyfield='".$mainsubitem."'");
			mysql_query("update cart_category set level='3', parent='".$mainsubitem."' where ".$thissubmenu_q);
			$mainsubs .= $mainsubitem.',';
			$main_subs_par .= 'keyfield='.$mainsubitem.' or ';
		} else {
			$mainsubs .= $nmv.',';
			$main_subs_par .= 'keyfield='.$nmv.' or ';
		}
	}
	$main_subs_par = preg_replace('/ or $/', '', $main_subs_par);
	$mainsubs = preg_replace('/,$/', '', $mainsubs);
	mysql_query("update cart_category set parent='".$_POST['cat_id']."' where ".$main_subs_par);
	//echo "update cart_category set parent='".$_POST['cat_id']."' where ".$main_subs_par;
	mysql_query("update cart_category set subs='".$mainsubs."' where keyfield='".$_POST['cat_id']."'");
	//echo testArray($_POST); exit;
	$qry = "UPDATE cart_category SET category = '".$_POST['cat_name']."' WHERE keyfield = '".$_POST['cat_id']."'";
	mysql_query($qry);
	if($_POST['newsub']!=''){
		mysql_query("INSERT INTO cart_category (category,level,subs,parent) VALUES('".$_POST['newsub']."','2','','')");
		updateCartCatCount();
		header("Location: categories.php?edit_cat=".$_POST['cat_id']);
		exit;
	}
	if($_POST['delete_sub']!=''){
		//$ffnd = mysql_query("select * from cart_category where parent='".$_POST['delete_sub']."'");
		//"update cart_category set level='2', subs='', parent='' where parent='".$_POST['delete_sub']."'"
		mysql_query("update cart_category set level='2', subs='', parent='' where parent='".$_POST['delete_sub']."'");
		mysql_query("delete from cart_category where keyfield='".$_POST['delete_sub']."'");
		updateCartCatCount();
		header("Location: categories.php?edit_cat=".$_POST['cat_id']);		
		exit;
	}
	
	updateCartCatCount();
}

////$_POST['newsub'] != ''
//if ( $_POST['action'] == "savecat" && $_POST['cat_name'] != "" ) {
//   //echo testArray($_POST); exit;
//   $qry = "UPDATE cart_category SET category = '".$_POST['cat_name']."' WHERE keyfield = '".$_POST['cat_id']."'";
//   mysql_query($qry);
//}

# Delete action
if ($_REQUEST['del'] == "yes" && $_REQUEST['key'] > 0) {
	$gkey = mysql_query("select * from cart_category where parent = '".$_REQUEST['key']."'");
	while($cat_q = mysql_fetch_assoc($gkey)){
		if($cat_q['subs']!=''){
			$csbs = explode(',', $cat_q['subs']);
			foreach($csbs as $cval){
				//echo "update cart_category set parent='', level='2' where parent='".$cval."'";
				mysql_query("update cart_category set parent='', level='2' where keyfield='".$cval."'");
			}
		}
	}
	mysql_query("update cart_category set parent='', level='2', subs='' where parent='".$_REQUEST['key']."'");	
	mysql_query("DELETE FROM cart_category WHERE keyfield = '".$_GET['key']."'");
	mysql_query("delete from site_pages where page_name='cartid:".$_REQUEST['key'].":'");
	updateCartCatCount();

}


if ($ACTION == "ADDCAT") {

	#######################################################
	### START DATABASE UPDATE/CREATE				    ###
	#######################################################

	$match = 0;
	$tablename = "cart_category";

	if(!table_exists("cart_category") ) {
		create_table("cart_category");
	}

	mysql_query("INSERT INTO cart_category (category, level, subs, parent) VALUES('".$ADDCATEGORY."', '1', '', '')");
	$lastinsid=mysql_insert_id();
	mysql_query("INSERT INTO site_pages (page_name, url_name, type, main_menu, link) VALUES('cartid:".$lastinsid.":', 'http://cart:".$lastinsid.":', 'menu', '0', 'http://shopping/start.php?browse=1&cat=".$lastinsid."')");


	updateCartCatCount();
	
} // End Category Add

#######################################################
### START HTML/JAVASCRIPT CODE			  			###
#######################################################

ob_start();

?>


<script type="text/javascript">
function toggleSort(){
	if(document.getElementById('category_customsort').checked==true){
		document.getElementById('customsort').style.display='none';	
	} else {
		document.getElementById('customsort').style.display='block';
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


function del_user(key, catname) {
	var tiny = window.confirm('You have selected to delete the category '+catname+'.\n\nOnce you click OK, you can not undo this process.\n\nAre you sure you wish to delete this category?');
	if (tiny != false) {
		window.location = "categories.php?del=yes&key="+key;
	} else {
		// Cancel Action
	}
}


<?php
$THIS_DISPLAY = "";


$THIS_DISPLAY .= "function save_maincatorder(){\n";
$THIS_DISPLAY .= "	var val = '';\n";
$THIS_DISPLAY .= "	if(document.getElementById('category_customsort').checked!=true){\n";
$THIS_DISPLAY .= "		var selectObj = document.catsort.SELECTMCATS;\n";
$THIS_DISPLAY .= "			for ( var i = 0; i < selectObj.options.length; i++ ){\n";
$THIS_DISPLAY .= "			if(selectObj.options[i].value.charAt(0)=='>'){\n";
$THIS_DISPLAY .= '				val += selectObj.options[i].value;'."\n";
$THIS_DISPLAY .= "			} else {\n";
$THIS_DISPLAY .= '				val += ","+selectObj.options[i].value;'."\n";
$THIS_DISPLAY .= "			}\n";

$THIS_DISPLAY .= "		}\n";
$THIS_DISPLAY .= "		document.getElementById('newCatOrder').value=val;\n";
$THIS_DISPLAY .= "	} else {\n";
$THIS_DISPLAY .= "		document.getElementById('newCatOrder').value='';\n";
$THIS_DISPLAY .= "	}\n";	
$THIS_DISPLAY .= "	document.catsort.submit();		// Submit the FORM\n";
$THIS_DISPLAY .= "}\n\n";	



$THIS_DISPLAY .= "function save_menu(){\n";
$THIS_DISPLAY .= "	var val = '';\n";
$THIS_DISPLAY .= "	subsub_start_check();\n";
$THIS_DISPLAY .= "	var selectObj = document.editcat_form.menu_order;\n";
$THIS_DISPLAY .= "	for ( var i = 0; i < selectObj.options.length; i++ ){\n";
$THIS_DISPLAY .= "	if(selectObj.options[i].value.charAt(0)=='>'){\n";
$THIS_DISPLAY .= '		val += selectObj.options[i].value;'."\n";
$THIS_DISPLAY .= "	} else {\n";
$THIS_DISPLAY .= '		val += ","+selectObj.options[i].value;'."\n";
$THIS_DISPLAY .= "	}\n";

$THIS_DISPLAY .= "	}\n";
$THIS_DISPLAY .= "	document.getElementById('newMenu').value=val;\n";
$THIS_DISPLAY .= "	document.editcat_form.submit();		// Submit the FORM\n";
$THIS_DISPLAY .= "}\n";

//
//$THIS_DISPLAY .= "	var selected = selectObj.selectedIndex;\n";
//
//$THIS_DISPLAY .= "	for ( var i = selected; i < (selectObj.options.length - 1); i++ ){\n";
//$THIS_DISPLAY .= "		swapOptions( selectObj, i, i+1 );\n";
//$THIS_DISPLAY .= "	}\n";
//$THIS_DISPLAY .= "	selectObj.options.length--;\n";



$THIS_DISPLAY .= "function load_menu(){\n";
$THIS_DISPLAY .= "	var selectObj = document.editcat_form.menu_order;\n";
$THIS_DISPLAY .= "	var selectPgs = document.editcat_form.SELECTPAGES;\n";
$THIS_DISPLAY .= "	if(selectPgs.options.length > 0 && selectObj.options.length > 0){\n";
$THIS_DISPLAY .= "		for ( var i = 0; i < selectPgs.options.length; i++ ){\n";
$THIS_DISPLAY .= "			var toAdd = selectPgs.options[i].value;\n";
$THIS_DISPLAY .= "			if ( i != -1 ){\n";
$THIS_DISPLAY .= "				var options = selectObj.options;\n";
$THIS_DISPLAY .= "				for ( var ill = 0; ill < options.length; ill++ )\n";
$THIS_DISPLAY .= "				if((options[ill].value == toAdd) || (options[ill].value == \">\" + toAdd)){\n";
$THIS_DISPLAY .= "					selectPgs.remove(i);\n";
$THIS_DISPLAY .= "					selectPgs.options.length--;\n";
//$THIS_DISPLAY .= "					alert(i+':'+options[ill].value+".'\'\n\''.");\n";
$THIS_DISPLAY .= "				} // if\n";
$THIS_DISPLAY .= "			} // if\n";
$THIS_DISPLAY .= "		} \n";
$THIS_DISPLAY .= "		if(selectPgs.selectedIndex == -1){ \n";
$THIS_DISPLAY .= "			selectPgs.selectedIndex = 0;\n";
$THIS_DISPLAY .= "		} \n";
$THIS_DISPLAY .= "	} \n";
$THIS_DISPLAY .= "}\n";



$THIS_DISPLAY .= "function del_page() {\n";
$THIS_DISPLAY .= "	var sText = document.editcat_form.SELECTPAGES.options(document.editcat_form.SELECTPAGES.selectedIndex).value;	// Select Page for Removal\n";
$THIS_DISPLAY .= "	var oldMenu = OUTPUT.innerHTML;	// Current Menu Setup\n";
$THIS_DISPLAY .= "	var NewMenu = \"\";	// Reset the Future Current Menu to Blank\n";
$THIS_DISPLAY .= "	var curMnu = oldMenu.split('<BR>');\n";
$THIS_DISPLAY .= "	var thisSel = sText.toString();\n";
$THIS_DISPLAY .= "	var thisSub = \"&gt;&gt; \"+thisSel;\n";
$THIS_DISPLAY .= "	var i = 0;\n";
$THIS_DISPLAY .= "	while(curMnu[i] != \"\") {\n";
$THIS_DISPLAY .= "		if (curMnu[i] != thisSel && curMnu[i] != thisSub) {\n";
$THIS_DISPLAY .= "			NewMenu = NewMenu+curMnu[i]+\"<BR>\";\n";
$THIS_DISPLAY .= "		}\n";
$THIS_DISPLAY .= "		i++;\n";
$THIS_DISPLAY .= "	}\n";
$THIS_DISPLAY .= "	OUTPUT.innerHTML = NewMenu;\n";
$THIS_DISPLAY .= "} // End Func\n";

$THIS_DISPLAY .= "function up(){\n";
$THIS_DISPLAY .= "	var selectObject = document.editcat_form.menu_order;\n";
$THIS_DISPLAY .= "	var index = selectObject.selectedIndex;\n";
$THIS_DISPLAY .= "	if ( index > 0 ){\n";
$THIS_DISPLAY .= "		swapOptions( selectObject, index, index - 1 );\n";
$THIS_DISPLAY .= "	} // if\n";
$THIS_DISPLAY .= "} // up( )\n";

$THIS_DISPLAY .= "function down(){\n";
$THIS_DISPLAY .= "	var selectObject = document.editcat_form.menu_order;\n";
$THIS_DISPLAY .= "	var index = selectObject.selectedIndex;\n";
$THIS_DISPLAY .= "	if ( (index >= 0) && (index != selectObject.options.length - 1)){\n";
$THIS_DISPLAY .= "		swapOptions( selectObject, index, index + 1 );\n";
$THIS_DISPLAY .= "	} // if\n";
$THIS_DISPLAY .= "} // down( )\n";


$THIS_DISPLAY .= "function mup(){\n";
$THIS_DISPLAY .= "	var selectObject = document.catsort.SELECTMCATS;\n";
$THIS_DISPLAY .= "	var index = selectObject.selectedIndex;\n";
$THIS_DISPLAY .= "	if ( index > 0 ){\n";
$THIS_DISPLAY .= "		swapOptions( selectObject, index, index - 1 );\n";
$THIS_DISPLAY .= "	} // if\n";
$THIS_DISPLAY .= "} // up( )\n";

$THIS_DISPLAY .= "function mdown(){\n";
$THIS_DISPLAY .= "	var selectObject = document.catsort.SELECTMCATS;\n";
$THIS_DISPLAY .= "	var index = selectObject.selectedIndex;\n";
$THIS_DISPLAY .= "	if ( (index >= 0) && (index != selectObject.options.length - 1)){\n";
$THIS_DISPLAY .= "		swapOptions( selectObject, index, index + 1 );\n";
$THIS_DISPLAY .= "	} // if\n";
$THIS_DISPLAY .= "} // down( )\n";


$THIS_DISPLAY .= "function swapOptions(obj,i,j){\n";
$THIS_DISPLAY .= "	var o = obj.options;\n";
$THIS_DISPLAY .= "	var i_selected = o[i].selected;\n";
$THIS_DISPLAY .= "	var j_selected = o[j].selected;\n";
$THIS_DISPLAY .= "	var temp = new Option(o[i].text, o[i].value, o[i].defaultSelected, o[i].selected);\n";
$THIS_DISPLAY .= "	var temp2= new Option(o[j].text, o[j].value, o[j].defaultSelected, o[j].selected);\n";
$THIS_DISPLAY .= "	o[i] = temp2;\n";
$THIS_DISPLAY .= "	o[j] = temp;\n";
$THIS_DISPLAY .= "	o[i].selected = j_selected;\n";
$THIS_DISPLAY .= "	o[j].selected = i_selected;\n";
$THIS_DISPLAY .= "}\n";


$THIS_DISPLAY .= "function add_main(){\n";
$THIS_DISPLAY .= "	var selectObj = document.editcat_form.menu_order;\n";
$THIS_DISPLAY .= "	var selectPgs = document.editcat_form.SELECTPAGES;\n";
$THIS_DISPLAY .= "	var toAdd = selectPgs.options[ selectPgs.selectedIndex ];\n";
//$THIS_DISPLAY .= "	alert(\" selectObj: [\"+selectObj+\"]\n selectPgs: [\"+selectPgs+\"]\n\");\n";
$THIS_DISPLAY .= "	if ( selectPgs.selectedIndex != -1 ){\n";
$THIS_DISPLAY .= "		var options = selectObj.options;		\n";
$THIS_DISPLAY .= "		for(var i = 0; i < options.length; i++ ){\n";
$THIS_DISPLAY .= "			if((options[i].value == toAdd.value) || (options[i].value == \">\" + toAdd.value)){\n";
$THIS_DISPLAY .= "				alert( \"You may only add a menu page once.\" );\n";
$THIS_DISPLAY .= "				return;\n";
$THIS_DISPLAY .= "			} // if\n";
$THIS_DISPLAY .= "		}\n";
$THIS_DISPLAY .= "		options[ options.length ] = new Option( toAdd.text, toAdd.value, toAdd.defaultSelected, toAdd.selected );\n";
$THIS_DISPLAY .= "		selectPgs.remove(selectPgs.selectedIndex);\n";
$THIS_DISPLAY .= "	} // if\n";
$THIS_DISPLAY .= "} // add_main\n";

$THIS_DISPLAY .= "function add_sub(){\n";
$THIS_DISPLAY .= "	var selectObj = document.editcat_form.menu_order;\n";
$THIS_DISPLAY .= "	var selectPgs = document.editcat_form.SELECTPAGES;\n";
$THIS_DISPLAY .= "	var toAdd = selectPgs.options[ selectPgs.selectedIndex ];\n";
$THIS_DISPLAY .= "	if ( selectPgs.selectedIndex != -1  ){\n";
$THIS_DISPLAY .= "		var options = selectObj.options;\n";
$THIS_DISPLAY .= "		for ( var i = 0; i < options.length; i++ )\n";
$THIS_DISPLAY .= "		if((options[i].value == toAdd.value) || (options[i].value == \">\" + toAdd.value)){\n";
$THIS_DISPLAY .= "			alert(\"You may only add a menu page once.\");\n";
$THIS_DISPLAY .= "			return;\n";
$THIS_DISPLAY .= "		} // if\n";
$THIS_DISPLAY .= "		options[ options.length ] = new Option( \">>\" + toAdd.text, \">\" + toAdd.value, toAdd.defaultSelected, toAdd.selected );\n";
$THIS_DISPLAY .= "		selectPgs.remove(selectPgs.selectedIndex);\n";
$THIS_DISPLAY .= "	} // if\n";
$THIS_DISPLAY .= "}\n";

$THIS_DISPLAY .= "function delete_subitem(){\n";
$THIS_DISPLAY .= "	var selectObj = document.editcat_form.menu_order;\n";
$THIS_DISPLAY .= "	var selectPgs = document.editcat_form.SELECTPAGES;\n";
$THIS_DISPLAY .= "	var toAdd = selectPgs.options[ selectPgs.selectedIndex ];\n";
$THIS_DISPLAY .= "	document.getElementById('delete_sub').value = toAdd.value;\n";
$THIS_DISPLAY .= "	save_menu();\n";
$THIS_DISPLAY .= "}\n";

$THIS_DISPLAY .= "function remove_item(){\n";
$THIS_DISPLAY .= "	var selectObj = document.editcat_form.menu_order;\n";
$THIS_DISPLAY .= "	var selectPgs = document.editcat_form.SELECTPAGES;\n";
$THIS_DISPLAY .= "	var selected = selectObj.selectedIndex;\n";
$THIS_DISPLAY .= "	if ( selected > -1 ){\n";
$THIS_DISPLAY .= "		for ( var i = selected; i < (selectObj.options.length - 1); i++ ){\n";
//$THIS_DISPLAY .= "			var options2 = selectPgs.options;		\n";
$THIS_DISPLAY .= "			swapOptions( selectObj, i, i+1 );\n";
$THIS_DISPLAY .= "		}\n";

$THIS_DISPLAY .= "			var options = selectPgs.options;		\n";
$THIS_DISPLAY .= "			var toAdd = selectObj.options[selectObj.selectedIndex];\n";
$THIS_DISPLAY .= "			options[options.length] = new Option( toAdd.text.replace(/^(>){1,2}/, ''), toAdd.value.replace(/^(>){1,2}/, ''));\n";

$THIS_DISPLAY .= "		selectObj.options.length--;\n";
$THIS_DISPLAY .= "		if (selectObj.options.length > 0){\n";
$THIS_DISPLAY .= "			selectObj.selectedIndex = ( selected < selectObj.options.length ) ? selected : selectObj.options.length - 1;\n";
$THIS_DISPLAY .= "		}\n";
$THIS_DISPLAY .= "	} \n";
$THIS_DISPLAY .= "	subsub_start_check();\n";
$THIS_DISPLAY .= "} \n";

$THIS_DISPLAY .= "function clear_menu(){\n";
$THIS_DISPLAY .= "	var selectObj = document.editcat_form.menu_order;\n";
$THIS_DISPLAY .= "	if ( confirm( 'Are you sure you wish to clear the list?')){\n";
$THIS_DISPLAY .= "		selectObj.options.length = 0;\n";
$THIS_DISPLAY .= "	}\n";
$THIS_DISPLAY .= "}\n";




$THIS_DISPLAY .= "function subsub_start_check(){\n";
$THIS_DISPLAY .= "	var selectPgs = document.editcat_form.SELECTPAGES;\n";
$THIS_DISPLAY .= "	var selectObj = document.editcat_form.menu_order;\n";
$THIS_DISPLAY .= "	if(selectObj.options.length > 0){\n";
$THIS_DISPLAY .= "		if(selectObj.options[0].value.charAt(0)=='>'){\n";
$THIS_DISPLAY .= "			var toAdd = selectObj.options[0];\n";
$THIS_DISPLAY .= "			var options = selectPgs.options;		\n";
$THIS_DISPLAY .= "			options[options.length] = new Option( toAdd.text.replace(/^(>){1,2}/, ''), toAdd.value.replace(/^(>){1,2}/, ''));\n";
$THIS_DISPLAY .= "			$('option[value='+selectObj.options[0].value+']').remove();\n";
$THIS_DISPLAY .= "			subsub_start_check();\n";
$THIS_DISPLAY .= "		}\n";
$THIS_DISPLAY .= "	}\n";
$THIS_DISPLAY .= "}\n";

$THIS_DISPLAY .= "//-->\n";
echo $THIS_DISPLAY .= "</script>\n";

$THIS_DISPLAY = "<link rel=\"stylesheet\" href=\"shopping_cart.css\">\n";

//$THIS_DISPLAY .= testArray($_POST);
$THIS_DISPLAY .= "<table border=\"0\" cellpadding=5 cellspacing=\"0\" width=\"100%\">\n\n";
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\" class=\"text\" width=\"50%\">\n";

# Edit cat form and cat list

$THIS_DISPLAY .= "   <form name=\"editcat_form\" method=\"post\" action=\"categories.php\">\n";
$THIS_DISPLAY .= "   <input type=\"hidden\" name=\"action\" value=\"savecat\">\n";
$THIS_DISPLAY .= "   <input type=\"hidden\" name=\"cat_id\" value=\"".$_GET['edit_cat']."\">\n";
$THIS_DISPLAY .= "   <table border=\"0\" cellpadding=\"5\" cellspacing=\"1\" width=\"100%\" class=\"feature_sub\">\n";
$THIS_DISPLAY .= "    <tr>\n";
$THIS_DISPLAY .= "     <td align=\"left\" valign=\"left\" id=\"header2\" colspan=\"3\" class=\"fsub_title\">\n";

if($alphacheck!='checked'){
	$result = mysql_query("SELECT * FROM cart_category where level=1 ORDER BY sortorder ASC, category ASC");
} else {
	$result = mysql_query("SELECT * FROM cart_category where level=1 ORDER BY category ASC");
}

if ( $_GET['edit_cat'] != ''){
	$rez = mysql_query("SELECT * FROM cart_category where keyfield='".$_GET['edit_cat']."'");
	$catzz = mysql_fetch_assoc($rez);
	$thiscat = $catzz['category'];

	$THIS_DISPLAY .= "      ".lang("Editing Category: ");
	$THIS_DISPLAY .= "<input type=\"text\" name=\"cat_name\" value=\"".$thiscat."\" style=\"width: 205px;\">\n";
	$THIS_DISPLAY .= "<BR>\n";		
	
	$getcats = mysql_query("SELECT * FROM cart_category ORDER BY category");
	while ($rowz = mysql_fetch_array ($getcats)) {
		if (strlen($rowz['category']) > 2) {
			$catz[$rowz['keyfield']]= array('name'=>$rowz['category'], 'level'=>$rowz['level'], 'subs'=>$rowz['subs'], 'parent'=>$rowz['parent']);		
		}
	}
	
	$cat_selection = '';
	$sub_assigned = '';
//		echo testArray($catz);
	foreach($catz as $cvar=>$cval){
		if($catz[$cvar]['level'] == '2' && $catz[$cvar]['parent'] == ''){
			$cat_selection .= "     <OPTION VALUE=\"".$cvar."\">".$cval['name']."</OPTION>\n";

		} elseif($catz[$cvar]['parent'] == $_GET['edit_cat']) {
			$sub_assigned .= "     <OPTION VALUE=\"".$cvar."\">".$catz[$cvar]['name']."</OPTION>\n";
			if($catz[$cvar]['subs']!=''){
				$thesubs = explode(',',$catz[$cvar]['subs']);					
				foreach($thesubs as $sbval){
					$sub_assigned .= "     <OPTION VALUE=\">".$sbval."\">>>".$catz[$sbval]['name']."</OPTION>\n";
				}
			}
			

		}
	}
	
	
	
} else {
	
	if($_GET['nocats']==1 && mysql_num_rows($result) == 0){
		$THIS_DISPLAY .= "<script type=\"text/javascript\">\n";
		$THIS_DISPLAY .= "      alert('You must create a Product Category before you can create products');\n";
		$THIS_DISPLAY .= "</script>\n";
	}
	
	$THIS_DISPLAY .= "      ".lang("Current Categories")."<BR>\n";	
}

$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "    </tr>\n";

while ($row = mysql_fetch_array ($result)) {
	if ( strlen($row['category']) > 2 ) {
		# Static text or editing mode?
		if ( $_GET['edit_cat'] == $row['keyfield'] ) {
			# EDIT MODE
			$THIS_DISPLAY .= "	<tr class=\"bg_green\">\n";				
			# text field
			$THIS_DISPLAY .= "		<td align=\"left\" valign=\"middle\" colspan=\"3\" style=\"width:100%;\">\n";
			//$THIS_DISPLAY .= "		<strong>Main Category</strong>: <input type=\"text\" name=\"cat_name\" value=\"".$row['category']."\" style=\"width: 205px;\"><br/>\n";
			$THIS_DISPLAY .= "		<div style=\"padding:6px;\">Select a Sub Category from the <i>Unassigned Sub Category</i> list to assign it as a sub category of <strong>".$thiscat."</strong>.<br/>  To create a new sub category click \"<i>Create New Sub Category</i>\" at the bottom of this page.</div>\n";
			$THIS_DISPLAY .= "		</td>\n";

			$THIS_DISPLAY .= "	</tr>\n";
			
			
			$THIS_DISPLAY .= "	<tr>\n";		
			$THIS_DISPLAY .= "		<td align=\"left\" valign=\"middle\" colspan=\"3\" style=\"width:100%;\">\n";
			$THIS_DISPLAY .= "		<div style=\"width:100%;\">\n<a href=\"javascript:void(0);\" style=\"text-decoration:none;\" onClick=\"document.getElementById('addsubdiv').style.display='block';\">[ <font style=\"color:#EAA510;\">+</font> Create New Sub Category ]</a>\n";
			$THIS_DISPLAY .= "  	 <div id=\"addsubdiv\" style=\"display: none;padding:6px;\">\n";
			//$THIS_DISPLAY .= "   <form name=\"addsubcat\" method=\"post\" ACTION=\"categories.php?edit_cat=".$_REQUEST['edit_cat']."\" style=\"display:inline;\">\n";
			$THIS_DISPLAY .= "  	 New Sub Category: <input type=\"text\" id=\"newsub\" name=\"newsub\" style=\"width:190px;\" value=\"\">\n";
			
			$THIS_DISPLAY .= "		&nbsp;<button type=\"button\" class=\"greenButton\" onclick=\"save_menu();\"><span><span>Add Sub Category</span></span></button>&nbsp;\n";
			
			//$THIS_DISPLAY .= "   </form>\n";
			$THIS_DISPLAY .= "  	 </div>\n";
			$THIS_DISPLAY .= "  	</div>\n";
			$THIS_DISPLAY .= "	</td></tr>\n";
			
			
			$THIS_DISPLAY .= "	<tr><td style=\"width:95%;\" colspan=\"3\">\n";

			$THIS_DISPLAY .= "<input type=\"hidden\" name=\"delete_sub\" id=\"delete_sub\" value=\"\">\n";

			
			$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\"><tbody>\n";
			$THIS_DISPLAY .= "	<tr>\n";				
			$THIS_DISPLAY .= "		<td colspan=\"3\" style=\"padding:2px;\">\n";
			
			$THIS_DISPLAY .= "		</td>\n";
			$THIS_DISPLAY .= "	</tr>\n";
			
			$THIS_DISPLAY .= "	<tr>\n";				
			$THIS_DISPLAY .= "		<td class=\"text\" align=\"center\" valign=\"top\">\n";
			$THIS_DISPLAY .= "			<table class=\"text\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\"><tbody>\n";
			$THIS_DISPLAY .= "				<tr>\n";
			$THIS_DISPLAY .= "					<td style=\"color:#000000;;\" align=\"center\">\n";
			$THIS_DISPLAY .= "						<strong>Unassigned</strong> Sub Categories\n";
			$THIS_DISPLAY .= "					</td>\n";
			$THIS_DISPLAY .= "				</tr>\n";
			$THIS_DISPLAY .= "				<tr>\n";
			$THIS_DISPLAY .= "					<td align=\"left\">\n";
			if($row['subs'] != ''){
				$dasubs = explode(',', $row['subs']);
				$dasubs_q = '';
				foreach($dasubs as $dasubsval){
					$dasubs_q .= 'parent = '.$dasubsval.' or ';
				}
				$dasubs_q = preg_replace('/ or $/', '', $dasubs_q);
				$getcats = mysql_query("select * from cart_category where level > 1 and (parent='' or parent='".$_REQUEST['edit_cat']."' or ".$dasubs_q.")");
			} else {
				$getcats = mysql_query("select * from cart_category where level > 1 and (parent='' or parent='".$_REQUEST['edit_cat']."')");	
			}
			
			$THIS_DISPLAY .= "						<select style=\"width:260px;\" name=\"SELECTPAGES\" id=\"SELECTPAGES\" size=\"13\" class=\"menupg_list\">\n";
			$THIS_DISPLAY .= "						".$cat_selection;
			$THIS_DISPLAY .= "						</select>\n";
			
			$THIS_DISPLAY .= "						<br/><br/><button type=\"button\" class=\"redButton\" onclick=\"delete_subitem();\"><span><span>[X] Delete Selected Sub Category</span></span></button>\n";
			$THIS_DISPLAY .= "					</td>\n";
			$THIS_DISPLAY .= "				</tr>\n";
			$THIS_DISPLAY .= "				<tr>\n";
			$THIS_DISPLAY .= "					<td align=\"center\">\n";
			// categories.php?edit_cat=".$_REQUEST['edit_cat']."
			//$THIS_DISPLAY .= "						<a href=\"javascript:void(0);\" onClick=\"document.getElementById('addsubdiv').style.display='block';\">[ Create New Sub Categories ]</a>\n";
			$THIS_DISPLAY .= "					</td>\n";
			$THIS_DISPLAY .= "				</tr>\n";
			$THIS_DISPLAY .= "			</tbody></table>\n";
			$THIS_DISPLAY .= "		</td>\n";
			$THIS_DISPLAY .= "		<td class=\"text\" style=\"padding-top: 45px; width: 130px;\" align=\"left\" valign=\"top\">\n";
			$THIS_DISPLAY .= "			<button type=\"button\" class=\"blueButton\" onclick=\"add_main();\"><span><span>Set as Primary Sub &gt;&gt;</span></span></button><br><br><br>\n";
			$THIS_DISPLAY .= "			<button type=\"button\" class=\"blueButton\" onclick=\"add_sub();\"><span><span>Set as Secondary Sub &gt;&gt;</span></span></button><br><br><br>\n";
			
			
			$THIS_DISPLAY .= "		</td>\n";
			$THIS_DISPLAY .= "		<td class=\"text\" align=\"center\" valign=\"top\">\n";
			$THIS_DISPLAY .= "			<table class=\"text\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\"><tbody>\n";
			$THIS_DISPLAY .= "				<tr>\n";
			$THIS_DISPLAY .= "					<td style=\"color:#000000;;\" align=\"center\">\n";
			$THIS_DISPLAY .= "						<strong>".$thiscat."</strong> Sub Categories\n";
			$THIS_DISPLAY .= "					</td>\n";
			$THIS_DISPLAY .= "				</tr>\n";
			$THIS_DISPLAY .= "				<tr>\n";
			$THIS_DISPLAY .= "					<td align=\"right\" valign=\"top\">\n";
			$THIS_DISPLAY .= "						<select name=\"menu_order\" id=\"menu_order\" size=\"13\" class=\"menupg_list\" align=\"left\" style=\"width:260px;\">\n";
			$THIS_DISPLAY .= "						".$sub_assigned;
//				$THIS_DISPLAY .= "						<option value=\"Home\">Home</option>\n";
//				$THIS_DISPLAY .= "						<option value=\"test2\">test2</option>\n";
			$THIS_DISPLAY .= "						</select>\n";
			$THIS_DISPLAY .= "						<textarea style=\"display: none;\" name=\"newMenu\" id=\"newMenu\"></textarea>\n";
			$THIS_DISPLAY .= "<br/><br/><button type=\"button\" class=\"grayButton\" onclick=\"clear_menu();\"><span><span>Clear Menu</span></span></button>\n";
			$THIS_DISPLAY .= "					</td>\n";
			$THIS_DISPLAY .= "					<td align=\"left\" valign=\"top\" style=\"padding-top: 25px;\">\n";
			$THIS_DISPLAY .= "					<button type=\"button\" class=\"blueButton\" onclick=\"up();\"><span><span>[^] Move Up</span></span></button>\n";
			$THIS_DISPLAY .= "					<br><br><button type=\"button\" class=\"blueButton\" onclick=\"down();\"><span><span>[v] Move Down</span></span></button>\n";
			$THIS_DISPLAY .= "					<br/><br/><button type=\"button\" class=\"grayButton\" onclick=\"remove_item();\"><span><span>&lt;&lt; Remove Item</span></span></button>\n";
			
			//$THIS_DISPLAY .= "					<br><br><br><br><br><br><br><br><br>\n";
			$THIS_DISPLAY .= "					</form></td>\n";
			$THIS_DISPLAY .= "				</tr>\n";
			$THIS_DISPLAY .= "			</tbody></table>\n";
			
			$THIS_DISPLAY .= "		</td>\n";
			
//				$THIS_DISPLAY .= "		</tr><tr><td style=\"width:95%;padding-top:20px;\" colspan=3>\n";
//				//$THIS_DISPLAY .= "					<div style=\"width:95%;\"><div style=\"width:220px;float:left;\"><button type=\"button\" class=\"redButton\" onclick=\"delete_subitem();\"><span><span>[X] Delete Sub Category</span></span></button></div>\n";
//				//$THIS_DISPLAY .= "					<div style=\"float:left;text-align:left;border:1px solid pink;width:50%;\"><button type=\"button\" class=\"grayButton\" onclick=\"clear_menu();\"><span><span>Clear Menu</span></span></button></div></div>\n";
//				$THIS_DISPLAY .= "		</td></tr>\n";
			
			$THIS_DISPLAY .= "		</tr><tr><td>&nbsp;</td><td style=\"padding-top:20px;padding-bottom:20px;text-align:center;align:center;\">\n";

			$THIS_DISPLAY .= "		</td>\n";
			$THIS_DISPLAY .= "		<td>&nbsp;\n";
			$THIS_DISPLAY .= "		</td>\n";
			
			$THIS_DISPLAY .= "	</tr>\n";
			$THIS_DISPLAY .= "</tbody></table>\n";
			
			$THIS_DISPLAY .= "<script type=\"text/javascript\">\n";
			$THIS_DISPLAY .= "load_menu();\n";
			$THIS_DISPLAY .= "</script>\n";
			
			$THIS_DISPLAY .= "		</td>\n";
			$THIS_DISPLAY .= "	</tr>\n";
			
			$THIS_DISPLAY .= "	<tr>\n";
			$THIS_DISPLAY .= "		<td align=\"right\" valign=\"middle\"  colspan=3 style=\"width:100%; text-align:right; padding-right:10%;\">\n";
			$THIS_DISPLAY .= "		<a href=\"categories.php\" class=\"redButton\"><span>Cancel</span></a>\n";
			$THIS_DISPLAY .= "		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
			$THIS_DISPLAY .= "		&nbsp;<button type=\"button\" class=\"greenButton\" onclick=\"save_menu();\"><span><span>Save</span></span></button>&nbsp;\n";
			$THIS_DISPLAY .= "		</td>\n";
		} elseif ( $_GET['edit_cat'] == "" ) { // Do not show the other cats other than the target if in edit mode
					
					
			$getcats = mysql_query("SELECT * FROM cart_category ORDER BY category");
			while ($rowz = mysql_fetch_array ($getcats)) {
				if (strlen($rowz['category']) > 2) {
					$catz[$rowz['keyfield']]= array('name'=>$rowz['category'], 'level'=>$rowz['level'], 'subs'=>$rowz['subs'], 'parent'=>$rowz['parent'], 'product_count'=>$rowz['product_count']);		
				}
			}
			$subcatt = '';
			foreach($catz as $cvar=>$cval){
				if($catz[$cvar]['level'] == '2' && $catz[$cvar]['parent'] == $row['keyfield']){
					if($catz[$cvar]['product_count'] > 0){ $subcat_count = " <span style=\"color:#565656;\">(".$catz[$cvar]['product_count'].")</span>"; } else { $subcat_count = ''; }
					$subcatt .= $catz[$cvar]['name'].$subcat_count.", ";
					if($catz[$cvar]['subs']!=''){
						$thesubs = explode(',',$catz[$cvar]['subs']);					
						foreach($thesubs as $sbval){
							if($catz[$sbval]['product_count'] > 0){ $subcat_count = " <span style=\"color:#565656;\">(".$catz[$sbval]['product_count'].")</span>"; } else { $subcat_count = ''; }
							$subcatt .= "<span style=\"font-size:85%;\">".$catz[$sbval]['name'].$subcat_count."</span>, ";
						}
					}
				}
			}
			if($subcatt != ''){
				$subcatt = preg_replace('/, $/', '', $subcatt);
			}
			
			
			
			# STATIC MODE
			$THIS_DISPLAY .= "    <tr>\n";
			
			# [delete]
			$THIS_DISPLAY .= "     <td align=\"center\" valign=\"middle\" bgcolor=WHITE width=5%>\n";
			$THIS_DISPLAY .= "      [&nbsp;<a onClick=\"del_user('".$row['keyfield']."', '".str_replace("'", "\'", $row['category'])."');\" href=\"javascript:void(0);\" class=\"del\">Delete</a>&nbsp;]</td>\n";
			
			# Static cat name
			$THIS_DISPLAY .= "     <td align=\"left\" valign=\"middle\" bgcolor=WHITE><font FACE=Verdana SIZE=2 color=#000099>\n";
			$THIS_DISPLAY .= "      ".$row['category']."</font>&nbsp;<span style=\"color:#565656;\">(".$row['product_count'].")</span></td>\n";
			
			# [ edit ]
			$THIS_DISPLAY .= "     <td align=\"center\" valign=\"middle\" bgcolor=WHITE width=\"170px\">\n";
			$THIS_DISPLAY .= "      [&nbsp;<a href=\"categories.php?edit_cat=".$row['keyfield']."\" class=\"edit\">Edit/Add Sub Categories</a>&nbsp;]</form></td>\n";
		}
		$THIS_DISPLAY .= "    </tr>\n";
		
		
		if($subcatt != ''){
			$THIS_DISPLAY .= "    <tr><td style=\"padding:0;line-height:8px;\">&nbsp;</td>\n";
			$THIS_DISPLAY .= "    	<td style=\"padding:0 0 0 6px; line-height:8px;\" colspan=\"2\">\n";
			$THIS_DISPLAY .= "    		".$subcatt."\n";
			$THIS_DISPLAY .= "    	</td>\n";
			$THIS_DISPLAY .= "    </tr>\n";
		}
		
	}
}

$THIS_DISPLAY .= "   </table>\n\n";

$THIS_DISPLAY .= "  </td>\n";

if($_REQUEST['edit_cat'] == ''){
	$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\" class=\"text\" width=\"50%\">\n";
	// Add new category Form
	
	$THIS_DISPLAY .= "   </form>\n<form name=CATSAVE method=\"post\" ACTION=\"categories.php\">\n";
	$THIS_DISPLAY .= "   <input type=\"hidden\" name=\"ACTION\" value=\"ADDCAT\">\n";
	$THIS_DISPLAY .= "   <table border=\"0\" cellpadding=5 cellspacing=\"0\" class=\"feature_sub\" width=\"90%\">\n";
	$THIS_DISPLAY .= "    <tr>\n";
	$THIS_DISPLAY .= "     <td align=\"center\" valign=\"middle\" ID=header2 class=\"fsub_title\">\n";
	$THIS_DISPLAY .= "      ".lang("Add New Category")."<BR>\n";
	$THIS_DISPLAY .= "     </td>\n";
	$THIS_DISPLAY .= "    </tr>\n";
	
	$THIS_DISPLAY .= "    <tr>\n";
	$THIS_DISPLAY .= "     <td align=\"left\" valign=\"middle\" bgcolor=WHITE style=\"color: #000099;\">\n";
	$THIS_DISPLAY .= "     <b>".lang("New Category Name").":</b><BR><input class=\"text\" type=\"text\" name=\"ADDCATEGORY\" size=\"23\" maxlength=\"50\" value=\"\" style='width: 150px;'>\n";
	$THIS_DISPLAY .= "     &nbsp;\n";
	$THIS_DISPLAY .= "     <button type=\"button\" class=\"blueButton\" onclick=\"document.CATSAVE.submit();\"><span><span>".lang("Add Category")."</span></span></button>\n\n";
	
	$THIS_DISPLAY .= "     <br/>&nbsp;<div align=\"center\" style=\"background-color: #F5F5F5; border-top: 1px solid #999999;\"><font COLOR=#999999>".lang("To delete a category")."</font></div>";
	
	$THIS_DISPLAY .= "     </td>\n";
	$THIS_DISPLAY .= "    </tr>\n";
	$THIS_DISPLAY .= "   </table>\n";
	$THIS_DISPLAY .= "   </form>\n\n";
	
	



	$THIS_DISPLAY .= "   <br/><form name=\"catsort\" method=\"post\" ACTION=\"categories.php\">\n";
	$THIS_DISPLAY .= "   <table border=\"0\" cellpadding=5 cellspacing=\"0\" class=\"feature_sub\" width=\"90%\">\n";
	$THIS_DISPLAY .= "    <tr>\n";
	$THIS_DISPLAY .= "     <td align=\"center\" valign=\"middle\" ID=header2 class=\"fsub_title\">\n";
	$THIS_DISPLAY .= "      ".lang("Category Display Options")."<BR>\n";
	$THIS_DISPLAY .= "     </td>\n";
	$THIS_DISPLAY .= "    </tr>\n";

#######################################################

if($display_cat_prodcount=='hide'){
	$display_cat_prodcount_check='';
} else {
	$display_cat_prodcount_check='checked';
}

	
	$THIS_DISPLAY .= "    <tr>\n";
	$THIS_DISPLAY .= "     <td align=\"left\" valign=\"middle\" bgcolor=WHITE style=\"color: #000099;\">\n";


	$THIS_DISPLAY .= "     <input type=\"checkbox\" name=\"display_cat_prodcount\"  id=\"display_cat_prodcount\" value=\"show\" ".$display_cat_prodcount_check.">&nbsp;".lang("Display product count next to category.")."<BR>\n";
	
	$THIS_DISPLAY .= "     <input type=\"hidden\" name=\"category_sort\"  value=\"category_sort\">\n";
	$THIS_DISPLAY .= "     <input onChange=\"toggleSort();\" type=\"checkbox\" name=\"category_customsort\"  id=\"category_customsort\" value=\"alpha\" ".$alphacheck.">&nbsp;".lang("Sort Main Categories Alphabetically")."<BR>\n";

	$customsortdisplay='display:block; ';	
	if($alphacheck=='checked'){
		$customsortdisplay='display:none; ';
	}		
	$THIS_DISPLAY .= "	<br/><div id=\"customsort\" style=\"".$customsortdisplay."\">\n";

	$THIS_DISPLAY .= "	<table class=\"text\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\"><tbody>\n";
	$THIS_DISPLAY .= "	<tr>\n";
	$THIS_DISPLAY .= "	<td align=\"right\" valign=\"top\">\n";	
	
	$getmcats = mysql_query("SELECT * FROM cart_category where level='1' ORDER BY sortorder ASC, category ASC");
	$mheight='';
	if(mysql_num_rows($getmcats) > 11){
		$mheight='min-height:350px;';
	} 
	$THIS_DISPLAY .= "	<select style=\"width:260px;".$mheight."\" name=\"SELECTMCATS\" id=\"SELECTMCATS\" size=\"13\" class=\"menupg_list\">\n";
	
	while ($rowz = mysql_fetch_array ($getmcats)) {
		if (strlen($rowz['category']) > 2) {
			$THIS_DISPLAY .= "     <OPTION VALUE=\"".$rowz['keyfield']."\">".$rowz['category']."</OPTION>\n";		
		}
	}
	$THIS_DISPLAY .= "	</select>\n";	

	$THIS_DISPLAY .= "	</td>\n";
	$THIS_DISPLAY .= "	<td align=\"left\" valign=\"top\" style=\"padding-top: 25px;\">\n";
	
	$THIS_DISPLAY .= "	<button type=\"button\" class=\"blueButton\" onclick=\"mup();\"><span><span>[^] Move Up</span></span></button>\n";
	$THIS_DISPLAY .= "	<br><br><button type=\"button\" class=\"blueButton\" onclick=\"mdown();\"><span><span>[v] Move Down</span></span></button>\n";	
	$THIS_DISPLAY .= "	</td></tr></table>\n";
	
	
	$THIS_DISPLAY .= "	</div>\n";
	
	$THIS_DISPLAY .= "     <BR>\n";
	$THIS_DISPLAY .= "	<textarea style=\"display: none;\" name=\"newCatOrder\" id=\"newCatOrder\"></textarea>\n";	
	
	$THIS_DISPLAY .= "     <button type=\"button\" class=\"greenButton\" onclick=\"save_maincatorder();\"><span><span>".lang("Save Changes")."</span></span></button>\n\n";
	$THIS_DISPLAY .= "     </td>\n";
	$THIS_DISPLAY .= "    </tr>\n";
	$THIS_DISPLAY .= "   </table>\n";
	$THIS_DISPLAY .= "   </form>\n\n";	
	
	$THIS_DISPLAY .= "</td>\n";
}
$THIS_DISPLAY .= "</tr></table>";

echo $THIS_DISPLAY;




# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$module = new smt_module($module_html);
$module->add_breadcrumb_link("Shopping Cart Menu", "program/modules/mods_full/shopping_cart.php");
$module->add_breadcrumb_link("Product Categories", "program/modules/mods_full/shopping_cart/categories.php");
$module->icon_img = "program/includes/images/shopping-icon-large.png";
$module->heading_text = "Product Categories";
$module->description_text = "Each of your shopping cart products (skus) must be associated with one or more product category. Examples: T-Shirts, Shoes, Hats, Widgets, Cogs, Sprockets...";
$module->good_to_go();
?>