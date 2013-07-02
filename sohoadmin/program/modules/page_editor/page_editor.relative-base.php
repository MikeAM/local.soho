<?php
//apd_set_pprof_trace();

error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


session_start();
require_once("../../includes/product_gui.php");

//echo "Sever Name: (".$_SERVER[HTTP_HOST].")\n"; exit;
$dis_site = $_SESSION['docroot_url'];

//=========================================================================
//			 ___     _               _
//			| __|_ _| |_ ___ _ _  __(_)___ _ _   __ ____ _ _ _ ___
//			| _|\ \ /  _/ -_) ' \(_-< / _ \ ' \  \ V / _` | '_(_-<
//			|___/_\_\\__\___|_||_/__/_\___/_||_|  \_/\__,_|_| /__/
//
//=========================================================================

//============================================================
//================= Pull All Extension Vars ==================
//============================================================
$mod_props = special_hook('page_editor_object');

# UNCOMMENT TO TEST ADDON VALUES PULLED FROM INSTALL MANIFEST / system_hook_attachments TABLE
//foreach($mod_props as $var=>$val){
//   //echo "var = (".$var.") val = (".$val.")<br>";
//   foreach($val as $var1=>$val1){
//
//   	if($var1 == "draggable_object_image"){
//   		$varText[] = "<b>draggable_object_image = (".$val1.")</b><br/>Draggable image shown on the Addons menu.";
//   		$varDisplay[] = "<img align=\"absmiddle\" src=\"../../../plugins/#PLUGIN_FOLDER#/".$val1."\" width=\"80\" height=\"18\" vspace=\"1\" hspace=\"1\" style=\"cursor: hand;\">";
//   		$folder_name[] = "";
//   	}
//   	if($var1 == "draggable_object_id"){
//   		$varText[] = "<b>draggable_object_id  = (".$val1.")</b><br/>Unique id for your draggable image.  When your plugin is dropped on the page, this is how we know its yours.";
//   		$varDisplay[] = $val1;
//   		$folder_name[] = "";
//   	}
//   	if($var1 == "properties_dialog_id"){
//   		$varText[] = "<b>properties_dialog_id = (".$val1.")</b><br/>Unique id for this plugins properites layer.  When this plugin is dropped on the page, we 'show' (".$val1.").";
//   		$varDisplay[] = $val1;
//   		$folder_name[] = "";
//   	}
//   	if($var1 == "plugin_folder"){
//   		$varText[] = "<b>plugin_folder / mod_folder = (".$val1.")</b><br/>Name of the plugin folder.";
//   		$varDisplay[] = $val1;
//   		$folder_name[] = $val1;
//   	}
//   }
//	$varText[] = "<b>-----------------------------------------------------------------------------------------</b>";
//	$varDisplay[] = "<b>-------------------------------------------------</b>";
//}
//
//$num_mods = count($varText);
//for($x=0;$x<$num_mods;$x++){
//	if($folder_name[$x] != ""){ $folder = $folder_name[$x]; }
//	//if($folder_name[$x] != ""){ echo "(".$folder_name[$x].")<br/>"; }
//	//echo "(".$folder_name[$x].")";
//	$testHTML .= "  <tr>\n";
//	$testHTML .= "    <td bgcolor=\"#336699\" style=\"width:60%; color:#FFFFFF; border: 1px solid #CCCCCC;\">".$varText[$x]."</td>\n";
//	$testHTML .= "    <td bgcolor=\"#CCCCCC\">".$varDisplay[$x]."</td>\n";
//	$testHTML .= "  </tr>\n";
//	if($folder != ""){
//		$testHTML = eregi_replace("#PLUGIN_FOLDER#", $folder, $testHTML);
//		$folder = "";
//	}
//}
//echo "<h3>ADDON VALUES PULLED FROM INSTALL MANIFEST / system_hook_attachments TABLE</h3>\n";
//echo "<div style=\"width:100%; height:80%; overflow: scroll;\">\n";
//echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"overflow: scroll;\">\n";
//echo $testHTML;
//echo "</table>\n";
//echo "</div>\n";
//exit;

// Count extensions
$extend_count = count($mod_props);


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
## Copyright 1999-2003 Soholaunch.com, Inc.  All Rights Reserved.
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

track_vars;

// Control Initial Background Colors, Etc.
// ---------------------------------------------------------------------

$header_background="menu";
$header_text="menutext";
$background="oldlace";

$option_background="white";
$text="black";
$link="lightskyblue";
$mouseover="buttonhighlight";

##########################################################################################
## If this script is opened with out a current page to edit, redirect
## user to open page script to select a page to edit.
##
## DEVNOTE: All redirects must occur before any headers are sent to browser
##########################################################################################

if ( $_GET['currentPage'] == "" ) {
	header ("Location: ../open_page.php?=SID");
	exit;
}



##########################################################################################
## INCLUDE: initialize.php
##
## The initialize.php script handles reading of the current setup into memory and
## the setting up of the current page HTML as the editor will see it.
##########################################################################################

include("initialize.php");

##########################################################################################
## Do not remove Comment tags in the HTML HEAD of this document.  It is restricted by
## your license agreement.  Please read in full before using.
##########################################################################################

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Page Editor</title>
<?php
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UT".'F-8">';
?>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<base href="http://<?php echo $this_ip; ?>/" id="ui-base">


<?php
//
//<link rel="stylesheet" type="text/css" href="../../product_gui.css">
//<link rel="stylesheet" type="text/css" href="includes/page_editor.css">
//<script language="JavaScript" src="../../includes/display_elements/js_functions.php"></script>
//<script language="javascript" type="text/javascript" src="../tiny_mce/tiny_mce.js"></script>
//<script language="javascript" type="text/javascript" src="includes/drop_cell.js"></script>
//<script language="javascript" type="text/javascript" src="includes/mootools.v1.1.js"></script>
//<script language="javascript" type="text/javascript" src="includes/general.js"></script>
//<script type="text/javascript" src="../tiny_mce/plugins/media/jscripts/embed.js"></script>
?>


<link rel="stylesheet" type="text/css" href="sohoadmin/program/modules/page_editor/../../product_gui.css">
<link rel="stylesheet" type="text/css" href="sohoadmin/program/modules/page_editor/includes/page_editor.css">
<script language="JavaScript" src="sohoadmin/program/modules/page_editor/../../includes/display_elements/js_functions.php"></script>
<script language="javascript" type="text/javascript" src="sohoadmin/program/modules/page_editor/../tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript" src="sohoadmin/program/modules/page_editor/includes/drop_cell.js"></script>
<script language="javascript" type="text/javascript" src="sohoadmin/program/modules/page_editor/includes/mootools.v1.1.js"></script>
<script language="javascript" type="text/javascript" src="sohoadmin/program/modules/page_editor/includes/general.js"></script>
<script type="text/javascript" src="sohoadmin/program/modules/page_editor/../tiny_mce/plugins/media/jscripts/embed.js"></script>

<script type="text/javascript">
// Bring our header nav back
parent.document.getElementById('master_frameset').rows = '29,*,1,19';
</script>

<!--
**************************************************************************************************
** PAGE EDITING SCRIPT AND WORD PROCESSOR BY MIKE JOHNSTON and modified by Joe Lain             **
** Email: joe.lain@soholaunch.com                                                               **
**                                                                                              **
**************************************************************************************************
Modified by Joe Lain for Firefox Compatibility and PinEdit text editor and then
again for TinyMCE editor, cell expandability and visual display effects.
-->


<script language="javascript">

<?php

##########################################################################################
## If preview page was selected from the main menu, open another browser window
## and call this specific page to view
##########################################################################################
$thisPage = eregi_replace(" ", "_", $_GET['currentPage']);
if ($previewWindow == 1) {
	$previewWindow = 0;
	echo "MM_openBrWindow('http://$this_ip/".pagename($thisPage, "&")."nosessionkill=1','prevwindow','width=790,height=450, status=yes, scrollbars=yes,resizable=yes,toolbar=yes');\n\n";
}

// Display changes info or reload?
$page_editor_details = new userdata("page_editor_details");
$display_details = "none";
//if ( $page_editor_details->get("seen") == "1" ) {
//   $page_editor_details->set("seen", "0");
//}elseif ( $page_editor_details->get("refresh_editor") == "" ) {
//   $page_editor_details->set("refresh_editor", "1");
//   $page_editor_details->set("seen", "");
//   echo " window.setTimeout('parent.body.location.reload()', 2000);\n";
//}elseif ( $page_editor_details->get("seen") == "" ) {
//   $page_editor_details->set("seen", "1");
//   $display_details = "block";
//}elseif ( $page_editor_details->get("refresh_editor") == "2" ) {
//   $page_editor_details->set("refresh_editor", "1");
//   echo " window.setTimeout('parent.body.location.reload()', 2000);\n";
//}

?>

function show_screen() {
	//show_hide_layer('NOSAVE_LAYER','','hide');
	show_hide_layer('userOpsLayer','','show');

	var p = 'Editing Page: <? echo $_GET['currentPage']; ?>';
	parent.frames.footer.setPage(p);

	disable_links();
}

// This is the main javascript workhorse
// object_drops defines all routines that
// run when something is dropped into a cell
<?
include("object_drops.php");

?>

function show_hide_icons(){
	// HIDE OBJECT BAR DIV
	if($('objectbar').style.display=='none'){
		$('objectbar').style.display='block';
	}else{
		$('objectbar').style.display='none';
	}
}

function show_mods(){
	// HIDE OBJECT BAR DIV
	if($('objectbar').style.display=='none'){
		//alert('something');
		$('objectbar').style.display='block';
		$('objectbar_mods').style.display='none';
	}else{
		//alert('something else');
		$('objectbar').style.display='none';
		$('objectbar_mods').style.display='block';
		//alert($('objectbar_mods').style.display)
	}
}



   //################################################
   //       _____ _          __  __  ___ ___
   //      |_   _(_)_ _ _  _|  \/  |/ __| __|
   //        | | | | ' \ || | |\/| | (__| _|
   //        |_| |_|_||_\_, |_|  |_|\___|___|
   //                   |__/                 Stuff
   //################################################


</script>
<?php
	echo "<script language=\"javascript\">\n";
	include('tiny_init.php');
?>


   // Defines what happends when file specific buttons are clicked
   function fileBrowserCallBack(field_name, url, type, win) {
      //alert(type)
   	var connector = "../../../tiny_mce/file_manager.php";
   	var linkconnector = "../../../tiny_mce/link_manager.php";

   	my_field = field_name;
   	my_win = win;
   	wins_vars = "width=450,height=600";

   	switch (type) {
   		case "image":
   			connector += "?type=img&dot_com="+dot_com;
   			break;
   		case "media":
   			connector += "?type=flash&dot_com="+dot_com;
   			break;
   		case "file":
   			connector = linkconnector+"?type=files&dot_com="+dot_com;
   			wins_vars = "width=550,height=200";
   			break;
   	}
   	window.open(connector, "link_manager", wins_vars);
   	//alert('4test-'+connector+'---'+wins_vars)
   }

   // Gets content from editor and places it in editor
   // Called by setupcontent_callback within tinyMCE.init
   function pullHTML(editor_id, body, doc){
      //alert(current_editing_area)
      var html = document.getElementById(current_editing_area).innerHTML;
      
      html = html.replace('<blink>','');
      html = html.replace('</blink>','');
      html = html.replace('<BLINK>','');
      html = html.replace('</BLINK>','');


      // Convert media objects to editor readable images
      //var inst = tinyMCE.getInstanceById(tinyMCE.selectedInstance.editorId);
      var inst = tinyMCE.activeEditor; 
      var newHtml = html;
	
      body.innerHTML = newHtml;
   }

   // Hide / show / load / unload editor within spcified id (div or textarea)
   function toggleEditor(id) {
      //alert('ok')
      // Hide addon layer if visible
      $('objectbar_mods').style.display='none';
      $('objectbar').style.display='none';
   	var elm = document.getElementById(id);
      var html = document.getElementById(current_editing_area).innerHTML;

      //alert(html)

   	if (tinyMCE.getInstanceById(id) == null){
   	   //alert('ok2---'+id)
   	   //$('tiny_editor_loading').style.display='block'

   		tinyMCE.execCommand('mceAddControl', false, id);
   	   $('tiny_editor_container').style.display='block';
   	   // Fix table border display
   	   setTimeout("tinyMCE.execInstanceCommand(tinyMCE.selectedInstance.editorId,'mceToggleVisualAid',false);tinyMCE.execInstanceCommand(tinyMCE.selectedInstance.editorId,'mceToggleVisualAid',false);",1000);
   	   //alert('ok2')
   	}else{
   		tinyMCE.execCommand('mceRemoveControl', false, id);
   	   $('tiny_editor_container').style.display='none';
   	}
   }

   function testtable(){
      alert('converting tables now')
      tinyMCE.execInstanceCommand('mce_editor_0','mceToggleVisualAid',false);
      tinyMCE.execInstanceCommand('mce_editor_0','mceToggleVisualAid',false);
   }

window.addEvent('domready', function(){


   var inner = GetInnerSize();

   // Set tiny editor to correct size based on browser
   $('tiny_editor_container').style.height = inner[1]+"px";
   //$('tiny_editor').style.height = inner[1]+"px";
   $('tiny_editor').style.height = (inner[1]-10)+"px";
   //$('tiny_editor_container').style.height = 100+"%";
   //$('tiny_editor').style.height  = 100+"%";
   
   
   //alert(inner[1]);

   // Set objectbar to correct size based on browser
   //alert(inner[0]+'---'+$('objectbar').style.width+'---'+$('object_table').style.width)
//   $('objectbar').style.width = inner[0]+"px";
//   $('object_table').style.width = inner[0]+"px";
   //alert(inner[0]+'---'+$('objectbar').style.width+'---'+$('object_table').style.width)

   // Set objectbar_mods to correct size based on browser
//   $('objectbar_mods').style.width = inner[0]+"px";
//   $('objectmods_table').style.width = inner[0]+"px";




   var drop_height = window.getSize().size.y - $('objectbar').getSize().size.y - 20;
   var drop_width = window.getSize().size.x;
   //alert(drop_width)

   //window.setStyle('width', 750);
<?php
if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT'])) {
	echo "$('cell_container').setStyle('height', (drop_height-10));\n";	
} else {
	echo "$('cell_container').setStyle('height', drop_height);\n";	
}
?>
   
   
   $('cell_container').setStyle('width', 770);
   //$('body').setStyle('height', '100);
   //$('body').setStyle('overflow', 'hidden');
//   parent.body.style.overflow='hidden';

   //alert(window.getSize().size.x+'---'+$('objectbar').getSize().size.x+'---'+$('cell_container').getSize().size.x);

   //76 377 451
	var fx = [];

	//$('loading_overlay').style.display='none'
	var box = $('loading_box')
	var fx = box.effects({duration: 500, transition: Fx.Transitions.Quart.easeOut});

	fx.start({

	}).chain(function(){
   	this.start.delay(100, this, {
   	   'width': 0,
   		'opacity': .0
   	});
   	$('loading_text').style.display='none'
   }).chain(function(){
      <?php

   	      echo "\$('loading_overlay').style.display='none'\n";
         
      ?>
   });

//   for( var col = 1; col <= 10; col++ ){
//      myDivsCheck = 'TDR1C'+col;
//      checkRow(myDivsCheck)
//   }
   checkPageAreas('start');

//   $$('.editTable').each(function(item){
//   	item.addEvent('click', function(e) {
//   	   alert(this.getStyle('height'))
//   	});
//   });

   var pageDroppables = $$('.editTable');
   //pageDroppables.push($$('.droppedItem'))

   $$('.drag').each(function(drag){

//      drag.parentNode.style.backgroundColor='red';
//      drag.parentNode.style.padding='0px';
//      drag.parentNode.style.margin='0px';
//      drag.style.marginTop='1px';



   	new Drag.Move(drag, {
   	   overflown: [$('cell_container')],
   		droppables: pageDroppables
   	});

   	drag.addEvent('emptydrop', function(){
   	   //alert(this.id);
      	this.setStyle('top', 0);
      	this.setStyle('left', 0);
   	});

   	drag.addEvent('mousedown', function(e) {
   	   itemCords = this.getCoordinates()
   	});

   	drag.addEvent('mouseup', function(e) {
         mouseEndX = nn6 ? e.clientX : event.clientX;
         mouseEndY = nn6 ? e.clientY : event.clientY;
   	   mouseCords = this.getCoordinates()
   		//alert('mouse---'+mouseCords.top)
   	});
   });

   $$('.editTable').each(function(drop, index){
   	fx[index] = drop.effects({transition:Fx.Transitions.Circ.easeInOut});

   	drop.addEvent('mouseup', function(e) {
         mouseEndX = nn6 ? e.clientX : event.clientX;
         mouseEndY = nn6 ? e.clientY : event.clientY;
         //alert(e.clientX)
   	   mouseCords = this.getCoordinates()
   		//alert('mouse---'+mouseCords.top)
   	});

   	drop.addEvents({
   		'over': function(el, obj){
   			this.setStyle('background-color', '#3E99DF');
   		},
   		'leave': function(el, obj){
   			this.setStyle('background-color', '#F8F8FF');
   		},
   		'drop': function(el, obj){

   		   //alert(el.id+'-'+obj.id)
   		   objPos = new Array(el.getStyle('Top'), el.getStyle('Left'))
   		   //alert(objPos[0]+'-'+objPos[1])
   		   //alert(mouseCords.top+'-'+mouseCords.left)
   			//el.remove();
   			//alert(itemCords.top)
         	el.setStyle('top', 0);
         	el.setStyle('left', 0);
   //			fx[index].start({
   //				'height': this.getStyle('height').toInt() + 30,
   //				'background-color' : ['#78ba91', '#F8F8FF']
   //			});
            var moveSize = 50;
            if(el.id != "oDeleteIt"){
               // This is the action cell
      			var myEffects = new Fx.Styles(this.id, {duration: 800, transition: Fx.Transitions.Circ.easeInOut});
            	myEffects.start({
      				'height': this.getStyle('height').toInt() + moveSize,
      				'background-color' : ['#3E99DF', '#F8F8FF']
            	});
            	if(this.id.charAt(5) != 1){
         			var myEffectsRow1 = new Fx.Styles('TDR'+this.id.charAt(3)+'C1', {duration: 800, transition: Fx.Transitions.Circ.easeInOut});
               	myEffectsRow1.start({
         				'height': this.getStyle('height').toInt() + moveSize
               	});
               }
            	if(this.id.charAt(5) != 2){
         			var myEffectsRow2 = new Fx.Styles('TDR'+this.id.charAt(3)+'C2', {duration: 800, transition: Fx.Transitions.Circ.easeInOut});
               	myEffectsRow2.start({
         				'height': this.getStyle('height').toInt() + moveSize
               	});
               }
               if(this.id.charAt(5) != 3){
         			var myEffectsRow3 = new Fx.Styles('TDR'+this.id.charAt(3)+'C3', {duration: 800, transition: Fx.Transitions.Circ.easeInOut});
               	myEffectsRow3.start({
         				'height': this.getStyle('height').toInt() + moveSize
               	});
               }
            }
         	objectAction(el, el.id, this.id);
         	//alert(el+'---'+el.id+'---'+this.id)
   			//$('TDR1C2').style.height = $('TDR1C2').getStyle('height').toInt() + 30
   			//$('TDR1C3').style.height = $('TDR1C3').getStyle('height').toInt() + 30
   		}
   	});
   });

//   $$('.droppedItem').each(function(drop, index){
//   	fx[index] = drop.effects({transition:Fx.Transitions.Back.easeOut});
//   	drop.addEvents({
//   		'over': function(el, obj){
//   			this.setStyle('background-color', 'red');
//   		},
//   		'leave': function(el, obj){
//   			this.setStyle('background-color', 'pink');
//   		},
//   		'drop': function(el, obj){
//   			alert('deleted')
//   		}
//   	});
//   });

});




   function objectAction(obj, objId, area){
      $('body').setStyle('overflow', 'hidden');
      //alert(objId+'---'+area)
      ColRowID = area;
      dataData = document.getElementById(ColRowID).innerHTML;

      if(objId == 'oImage'){
         show_hide_layer('objectbar','','hide','imageDrop','','show');
         ajaxDo('sohoadmin/program/modules/page_editor/includes/fileList.php?type=img', 'imgFileList');
      }

      if (objId == "oText") loadEditor();
      if (objId == "oDocs"){
         show_hide_layer('objectbar','','hide','ULDOCLAYER','','show');
         ajaxDo('sohoadmin/program/modules/page_editor/includes/fileList.php?type=doc', 'docFileList');
      }
      if (objId == "oCounter") pageCounter();
      if (objId == "oSecureLogin") show_hide_layer('objectbar','','hide','securelayer','','show');
      if (objId == "oCustom"){
         show_hide_layer('objectbar','','hide','customlayer','','show');
         ajaxDo('sohoadmin/program/modules/page_editor/includes/fileList.php?type=custom', 'customFileList');
      }
      if (objId == "oCart") show_hide_layer('objectbar','','hide','shoppingCartLayer','','show');
      if (objId == "oTableSearch") show_hide_layer('objectbar','','hide','memDatabase','','show');
      if (objId == "oNewsletter") {
         $('form_display').style.display='block';
      	var url = "sohoadmin/program/modules/page_editor/formlib/forms.php";
         formType = "Newsletter";
         ajaxDo(url, 'form_display');
         //loadwindow('formlib/forms.php?dropArea='+ColRowID+'&selkey=Newsletter&=SID',600,500,'that');
      }
      if (objId == "oForms") {
         $('form_display').style.display='block';
      	var url = "sohoadmin/program/modules/page_editor/formlib/forms.php";
         formType = "Forms";
         ajaxDo(url, 'form_display');
      }
      if (objId == "oCalendar") show_hide_layer('objectbar','','hide','calendarlayer','','show');
      if (objId == "oDirections") show_hide_layer('objectbar','','hide','mapquest','','show');
      if (objId == "oDate") OKdateStamp();
      if (objId == "oPrint") printButton();
      if (objId == "oEmailTo") emailfriend();
      if (objId == "supersearch_obj") place_supersearch();

      
      
      if (objId == "oPopup") show_hide_layer('objectbar','','hide','popupwin','','show');
      if (objId == "oMP3"){
         show_hide_layer('objectbar','','hide','mp3layer','','show');
         ajaxDo('sohoadmin/program/modules/page_editor/includes/fileList.php?type=mp3', 'mp3FileList');
      }
      if (objId == "oVideo"){
         show_hide_layer('objectbar','','hide','videolayer','','show');
         ajaxDo('sohoadmin/program/modules/page_editor/includes/fileList.php?type=video', 'videoFileList');
      }
      if (objId == "oAdobelink") show_hide_layer('objectbar','','hide','sitelinks','','show');
      if (objId == "oPhotoAlbum") show_hide_layer('objectbar','','hide','photoLayer','','show');
      if (objId == "oBlog") show_hide_layer('objectbar','','hide','blogLayer','','show');
      if (objId == "oFaq") show_hide_layer('objectbar','','hide','faqLayer','','show');

      <?
      // Add plugin javascript call to show layer or custom function
      for( $x=0; $x < $extend_count; $x++ ) {
      	//echo "dd.elements.".$mod_props[$x]['draggable_object_id'].".hide();\n";

      	${"MYmod".$x."_image"} = $mod_props[$x]['draggable_object_image'];
      	${"MYmod".$x."_display"}=$mod_props[$x]['properties_dialog_file'];
      	${'MYmod'.$x.'_drag_id'}=$mod_props[$x]['draggable_object_id'];
      	${"MYmod".$x."_props_id"}=$mod_props[$x]['properties_dialog_id'];
      	${"MYmod".$x."_folder"}="sohoadmin/plugins/".$mod_props[$x]['plugin_folder'];

         # v4.9.2 r15: Allows for plugins with draggable objects that do not require properties dialogs
         echo "if(objId == \"".${'MYmod'.$x.'_drag_id'}."\"){\n";
         if ( $mod_props[$x]['properties_dialog_id']!='' ) {
            echo "	show_hide_layer('objectbar_mods','','hide','".$mod_props[$x]['properties_dialog_id']."','','show');\n";
         } else {
            echo "	".$mod_props[$x]['place_object_js_function_name']."();\n";
         }
        	echo "}\n";

      }
      ?>


      if (objId == "oDeleteIt") {
         //alert('ok')
         var daArea = $(ColRowID);
         var daAreaHeight = daArea.style.height;
         var daAreaTop = daArea.getTop();
         var areaRemaining = daArea.style.height;
         //var win_scroll = window.pageYOffset || document.documentElement.scrollTop;
         var win_scroll = $('cell_container').pageYOffset || $('cell_container').scrollTop;

// Variable testing
//         alert($(area).innerHTML)
//         alert(daAreaTop+'-'+areaRemaining)
//         //1038-120px
//         alert('2ok')
//         alert(mouseEndX+'-'+mouseEndY)

         var myEndY = mouseEndY;
         var myEndX = mouseEndX;
         var prevTop;

         // -287---286---573---0

         newMouseEndY = mouseEndY - daAreaTop + win_scroll;
         //alert(newMouseEndY+'---'+mouseEndY+'---'+daAreaTop+'---'+win_scroll)

         for (i=0; i<daArea.childNodes.length; i++){
            if (daArea.childNodes[i].className=="droppedItem"){
               itemHeight = daArea.childNodes[i].style.height.toInt()
               //alert(newMouseEndY+'---'+itemHeight)
               //-622-100
               if(newMouseEndY <= itemHeight){
                  var nextThing = daArea.childNodes[i].nextSibling;
                  //alert(nextThing+'---'+nextThing.id+'---'+nextThing.nodeType+'---'+nextThing.nodeValue)
                  daArea.removeChild(daArea.childNodes[i].nextSibling);
                  daArea.removeChild(daArea.childNodes[i]);

                  checkCellEmpty()
                  break;
               }else{
                  newMouseEndY = newMouseEndY - itemHeight - 20
                  //alert(mouseEndY)
               }
            }
         }
         checkRow(ColRowID)

      }
   }

   function checkCellEmpty(){
      var daArea = $(ColRowID);
      is_empty = true;
      for (i=0; i<daArea.childNodes.length; i++){
         if (daArea.childNodes[i].className=="droppedItem"){
            is_empty = false;
            //alert('found one')
         }
      }
      if(is_empty){
         daArea.innerHTML = "<img src=\"sohoadmin/program/modules/page_editor/pixel.gif\" border=\"0\" height=\"50%\" width=\"199\">";
      }
   }
   var reCheckRow = 0;
   function checkRow(boxID, isFull){
      //alert(boxID)
      var cellHeight = 0;

   	var currentRow = boxID.charAt(3)
   	if(boxID.charAt(4) == 0){
   	   currentRow = 10;
   	}

      for( var col = 1; col <= 3; col++ ){

         var cellTotals = 0;
         var myDivs = $('TDR'+currentRow+'C'+col)
         //alert($('TDR'+currentRow+'C'+col).childNodes.length)
//         if(!isFull)
//            alert('num childs---'+myDivs.childNodes.length)

         for( var nug = 0; nug < myDivs.childNodes.length; nug++ ){
//            if(!isFull)
//               alert('node type('+myDivs.childNodes[nug].nodeType+')---nodeName('+myDivs.childNodes[nug].nodeName+')---current #('+nug+')')
            //alert('ok')
            if(myDivs.childNodes[nug].className=='droppedItem'){
               //alert('found droppedItem in '+col)
               cellTotals = cellTotals + parseInt(myDivs.childNodes[nug].style.height) + 20
            }else if(myDivs.childNodes[nug].nodeType == 1 && myDivs.childNodes[nug].nodeName != "IMG" && myDivs.childNodes[nug].nodeName != "BR"){
               //alert('1111 node type('+myDivs.childNodes[nug].nodeType+')---nodeName('+myDivs.childNodes[nug].nodeName+')---current #('+nug+')')
               reCheckRow = 1;


               //alert('num childs---'+myDivs.childNodes.length)
               //alert('fixing element ('+myDivs.childNodes[nug].nodeName+') class('+myDivs.childNodes[nug].tagName+')')

               //alert(myDivs.innerHTML)

               var removeDis = myDivs.childNodes[nug];
               var removedItem = myDivs.childNodes[nug].cloneNode(true);
               //alert(myDivs.innerHTML)

               //alert('removed item')
            	var d = new Date();
            	RandNum = 'convertedPlugin';
            	RandNum += d.getUTCHours();
            	RandNum += d.getUTCMinutes();
            	RandNum += d.getUTCSeconds();
            	RandNum += d.getUTCMilliseconds();
            	RandNum = RandNum.toString();

               //alert('got rand')

               var divWrapper=document.createElement("div")
               divWrapper.setAttribute("id", RandNum)
               divWrapper.setAttribute("class", "droppedItem")
               divWrapper.setAttribute("style", "height: 150px;")

               //alert('created div')

               divWrapper.appendChild(removedItem)

               //var newtext=document.createTextNode("A new div")
               //divWrapper.appendChild(newtext)

               var nextKid = myDivs.childNodes[nug].nextSibling

               //alert(myDivs.innerHTML)
               myDivs.replaceChild(divWrapper,removeDis)
               //alert(myDivs.innerHTML)
               //alert('appended new item!')

               //setTimeout("$('"+RandNum+"').className='droppedItem'",1000)
               $(RandNum).className='droppedItem'
               $(RandNum).style.height='150px'
            }

            //alert(myDivs[nug].style.height)
            //cellTotals = cellTotals + parseInt(myDivs[nug].style.height) + 20
         }

         if(cellTotals > cellHeight){
            cellHeight = cellTotals
         }

      }
      if(cellHeight < 85)
         cellHeight = 85;

      //alert(cellHeight)
      //cellHeight = cellHeight + 40
      //alert(cellHeight)

      // This is the action cell
		var myEffects = new Fx.Styles(boxID, {duration: 500, transition: Fx.Transitions.Circ.easeInOut});
   	myEffects.start({
			'height': cellHeight,
			'background-color' : ['#3E99DF', '#F8F8FF']
   	});

   	if(boxID.charAt(5) != 1){
			var myEffectsRow1 = new Fx.Styles('TDR'+currentRow+'C1', {duration: 500, transition: Fx.Transitions.Circ.easeInOut});
      	myEffectsRow1.start({
				'height': cellHeight
      	});
      }
   	if(boxID.charAt(5) != 2){
			var myEffectsRow2 = new Fx.Styles('TDR'+currentRow+'C2', {duration: 500, transition: Fx.Transitions.Circ.easeInOut});
      	myEffectsRow2.start({
				'height': cellHeight
      	});
      }
      if(boxID.charAt(5) != 3){
			var myEffectsRow3 = new Fx.Styles('TDR'+currentRow+'C3', {duration: 500, transition: Fx.Transitions.Circ.easeInOut});
      	myEffectsRow3.start({
				'height': cellHeight
      	});
      }
      if(isFull && currentRow <= 10){
         checkPageAreas(parseInt(currentRow)+1)
      }

      if(reCheckRow == 1){
         reCheckRow = 0;
         //alert('checking again...')
         checkPageAreas('start');
      }
   }

   function checkPageAreas(numRun){

      if(numRun != 'start' && numRun <= 10){
         //alert(numRun)
         var myDivsCheck = 'TDR'+numRun+'C1'
         checkRow(myDivsCheck, true)
      }else if(numRun == 'start'){
         var myDivsCheck = 'TDR1C1'
         checkRow(myDivsCheck, true)
      }
   }

   function joeFun() {
      $('daele').value = request.readyState
   }

</script>

</head>



<!-- ############################################################# --
          ___  _         _             ___ _            _
         |   \(_)____ __| |__ _ _  _  / __| |_ __ _ _ _| |_
         | |) | (_-< '_ \ / _` | || | \__ \  _/ _` | '_|  _|
         |___/|_/__/ .__/_\__,_|\_, | |___/\__\__,_|_|  \__|
                   |_|          |__/
<!-- ############################################################# -->

<body id="body" style="background:#F8F8FF; " onload="show_screen();">
<div style="position:relative;padding:0;margin:0;width:790px;margin-left: auto; margin-right: auto;">


<?
//$cssThing = "computedStyles.php?pageTemp=".$CUR_TEMPLATE;
//echo "<iframe src=\"".$cssThing."\" style=\"width: 0px; height: 0px; visibility: hidden;\"></iframe>\n";
?>

<div id="loading_overlay">
   <div id="loading_box">

      <div id="loading_text">Loading Page Content...</div>
      <!--- <img src="http://demo4.soholaunch.com/sohoadmin/icons/ajax-loader2.gif" width="60" height="30" border="0"> -->
   </div>
</div>
<div id="form_display">&nbsp;</div>
<div id="upload_display">
   <iframe src="" id="upload_frame" name="upload_frame" ></iframe>
</div>

<div id="first_timer_display" style="display: <? echo $display_details; ?>;">
   <div id="first_timer_box">
      <div class="help_link" style="float: right; padding:3px;" onClick="hideid('first_timer_display');hideid('loading_overlay');">
         Close
      </div>
      <!--- <input type="button" class="mikebut" value="Close" style="float: right;" onClick="hideid('first_timer_display');hideid('loading_overlay');" /> -->
      <h1>
         Welcome to the upgraded page editor!
      </h1>

      <p>
         After much thought about changes that our page editor needed we have taken many of your ideas and added quite a bit more functionality.
         The overall look and feel of the page editor has not changed but your ability to move and modify objects on the page has.  If you are
         using any plugins that allow you to drag and drop, please check to make sure you have upgraded it to the latest version so that it
         works properly.
      </p>
      <h2>New Features</h2>
      <ul>
         <li>
            Individual objects can be moved to and from cells.
            <img src="sohoadmin/program/modules/page_editor/images/obj-move-single.jpg" border="0" />
         </li>
         <li>
            Dropped items now make the drop area expand to show all content in each box.
            <img src="sohoadmin/program/modules/page_editor/images/layer-expand.jpg" border="0" />
         </li>
         <li>
            More consistent look and feel of properties layers and their transitions.
            <img src="sohoadmin/program/modules/page_editor/images/prop-layer-prev.jpg" border="0" />
         </li>
         <li>Drag objects to the trash can or drag the trash can to an object to delete.</li>
      </ul>
      <p>
         Also, many other behind the scenes changes have been made to increase efficiency.  These enhancements along with future changes should
         continue to make the page editor a powerful page editing tool.
      </p>
      <input type="button" class="mikebut" value=" Close " onClick="hideid('first_timer_display');hideid('loading_overlay');">
      <!--- <img src="http://demo4.soholaunch.com/sohoadmin/icons/ajax-loader2.gif" width="60" height="30" border="0"> -->
   </div>
</div>

<!-- ############################################################# --
      CUSTOM FILE EDITOR
<!-- ############################################################# -->


<?php
include_once('../simple_editor_js.php');

echo "<div id=\"simple_editor_container_save\" style=\"display: none;\"></div>\n";

echo "<div id=\"simple_editor_container\" style=\"position: absolute; height: 100%; width:100%; top: 0px; left: 0px; bottom: 0px; right: 0px; border: 0px solid green; z-index:2000; display: none;\"></div>\n";


?>

<!-- ############################################################# --
             ___    _ _ _             ___ _         __  __
            | __|__| (_) |_ ___ _ _  / __| |_ _  _ / _|/ _|
            | _|/ _` | |  _/ _ \ '_| \__ \  _| || |  _|  _|
            |___\__,_|_|\__\___/_|   |___/\__|\_,_|_| |_|
<!-- ############################################################# -->

<?php
//if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) || eregi("opera", $_SERVER['HTTP_USER_AGENT']) ) {
//   $editorHeight = "450px";
//}else{
//   if(eregi("Firefox/3", $_SERVER['HTTP_USER_AGENT'])){
//      $editorHeight = "435px";
//   }else{
//      $editorHeight = "475px";
//   }
//}
?>
<div id="tiny_editor_container" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; border: 0px solid green; z-index:1000; display: none;">
   <!--- Editor Textarea -->
   <textarea id="tiny_editor" name="tiny_editor"  style="position:relative;height:100%;  width: 100%;"></textarea>

   <!--- Cancel / Done buttons -->

      <!--- Test button
      <input onClick="testtable();" type="button" id="debug_edit" value="  Table Debug  " style="width: 150px;padding: 1px;" /> -->
	<div style="position:relative;width:50%;">
    <div id="saveIt" style="position:fixed; bottom: 0px; right: 20%; z-index:1002; display:block;">

     <button onClick="tinyMCE.execInstanceCommand('tiny_editor','mceCodeEditor',false);" type="button" id="html_view" class="grayButton" style="margin-right: 10em;font-weight:bold;"><span><span>HTML View</span></span></button>

      <!--- Cancel -->
      <button onClick="toggleEditor('tiny_editor');show_hide_icons();parent.header.flip_header_nav('PAGE_EDITOR_LAYER');" type="button" id="cancel_edit" class="redButton" style="margin-right: 1em;"><span><span> [x] Cancel </span></span></button>

      <!--- Done -->
      <button onClick="onSaveFileSOHO();show_hide_icons();" type="button" id="save_content" style="font-weight: bold;" class="greenButton"><span><span>Update</span></span></button>
   </div>
</div>
</div>

<!--- EDITOR LOADING -->
<div id="overlay_edit">&nbsp;</div>
<div id="tiny_editor_loading">
   Working...
</div>

<!-- Display: none; will fix scroll problem -->

<FORM id="save" name="save" method="post" action="sohoadmin/program/modules/page_editor/save_page.php" STYLE='Display: NONE;'>

<!-- ============================================================ -->
<!-- ============= PAGE PROPERTIES LAYER ======================== -->
<!-- ============================================================ -->

<div id="pageproperties" style="position:absolute; left:0px; top:1%; width:100%; height:525px; z-index:200001; border: 0px inset black; overflow: auto; visibility: hidden;">
<table border="0" cellpadding="0" width="100%"><tr><td align="center" valign="middle">

	<?

	// For easier editing, the page properties layer was created as an include
	// In the last updates before release, this was still being modified heavily
	// and I did not want to keep updating this script. The page_editor script
	// is very sensitive
	// --------------------------------------------------------------------------

	include("layers/page_prop_layer.php");

	?>

</td></tr></table>

</div>

<!-- ============================================================ -->
<!-- ============= SAVE PAGE AS... LAYER ======================== -->
<!-- ============================================================ -->

<div id="saveaslayer" style="position:absolute; left:0px; top:40%; width:100%; height:350px; z-index:200; border: 0px inset black; overflow: auto; visibility: hidden;">
<table border="0" cellpadding="0" width="100%"><tr><td align="center" valign="middle">
	<?

	include("layers/save_as_layer.php");

	?>
</td></tr></table>

</div>

<!-- ############################################################# -->
<!-- #### START SAVE LAYER HIDDEN					  #### -->
<!-- ############################################################# -->

<div id="HiddenSaveLayer" style="position:absolute; width:100%; height:50%; z-index:1; left: 0px; top: 15%; overflow: none; visibility: hidden;">

<?

if ($totalHidden != 0) {
	for ($x=1;$x<=$totalHidden;$x++) {
		echo ("$hiddenValue[$x]");
	}
}
echo ("<SPAN id=\"saveForm\" class=\"hidden\"><input type=\"hidden\" id=\"currentPage\" name=\"currentPage\" value=\"".$_GET['currentPage']."\"><input type=\"hidden\" name=\"serial_number\" value=\"".$serial_number."\"><input type=\"hidden\" id=\"dot_com\" name=\"dot_com\" value=\"".$dis_site."\"></SPAN>\n");
?>

<span id="desctext"><? echo lang("Click on an object above and drag it onto a drop zone for page placement."); ?></span>


</div>
</FORM>



<?

// Determine which icons to pull based on language
//======================================================
if ( $getSpec['df_lang'] == "norwegian.php" ) {
   $ilng = "nor";
} else {
   $ilng = "eng";
}

// Build icon filename
//----------------------------------------
$ipre = "sohoadmin/program/modules/page_editor/obj_bar_icons/".$ilng."-icon_";
$engdash = "sohoadmin/program/modules/page_editor/obj_bar_icons/".$ilng."-"; // newschool, use this

if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) || eregi("opera", $_SERVER['HTTP_USER_AGENT']) ) {
   $barWidth = "773";
}else{
   $barWidth = "781";
}
$barWidth = "790";
?>


<div id="objectbar" style="visibility: visible; width:790px;">
   <div class="help-text">
      <? echo lang("Click on an object below and drag it onto a drop zone for page placement."); ?>
   </div>
   <div class="rightClickId" style="display:none;">
      <input type="text" id="daele" name="daele" value="daele" />
      <input type="text" id="dadrag" name="dadrag" value="dadrag" />
      <input type="text" id="dadragx" name="dadragx" value="dadragx" />
      <input type="text" id="dadragy" name="dadragy" value="dadragy" />
   </div>


   <table id="object_table" border="0" cellpadding="0" cellspacing="0" align="center" width="<? echo $barWidth; ?>" height="100%" style="border: 1px solid #245981;">

    <tr>

     <!-- Text Editor -->
     <td rowspan="3" class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';" width="80px">
     	<img class="drag" name="MYeditor" id="oText" value="oText" src="<? echo $ipre; ?>texteditor.gif" width="80px">
     </td>

     <!-- My Images -->
     <td class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';">
     	<img class="drag" name="MYimages" id="oImage" src="<? echo $ipre; ?>images.gif" width="80px" height="18px">
     </td>

     <!-- Forms -->
     <td class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';">
     <img class="drag" name="MYforms" id="oForms" src="<? echo $ipre; ?>forms.gif" width="80px" height="18px" ></td>

     <!-- Documents -->
     <td class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';">
     <img class="drag" name="MYdocs" id="oDocs" src="<? echo $ipre; ?>docs.gif" width="80px" height="18px" ></td>

     <!--- Hit Counter --->
     <td class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';">
      <img class="drag" name="MYcounter" id="oCounter" src="<? echo $ipre; ?>counter.gif" width="80px" height="18px" ></td>

     <!-- Auth Login -->
     <td class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';">
     <img class="drag" name="MYauth" id="oSecureLogin" src="<? echo $ipre; ?>login.gif" width="80px" height="18px" ></td>

     <!-- Custom Code -->
     <td class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';">
     <img class="drag" name="MYcust" id="oCustom" src="<? echo $ipre; ?>customcode.gif" width="80px" height="18px" ></td>

     <!-- Shopping -->
     <td rowspan="2" class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';">
     <img class="drag" name="MYshop" id="oCart" src="<? echo $ipre; ?>shopping.gif" width="80px"/></td>

     <!-- Delete Object -->
     <td width="81px" rowspan="3" height="60px" align="center" id="oDelete" bgcolor="#3E99DF" class="ob1">
     <img class="drag" name="MYdelete" id="oDeleteIt" src="<? echo $ipre; ?>deleteobj.gif" ></td>

     </tr>


    <tr>

     <!-- Sign-Up -->
     <td class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';">
     <img class="drag" name="MYsignup" id="oNewsletter" src="<? echo $ipre; ?>newsletter.gif" width="80px" height="18px" ></td>

     <!-- Calendar -->
     <td class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';">
     <img class="drag" name="MYcalendar" id="oCalendar" src="<? echo $ipre; ?>calendar.gif" width="80px" height="18px" ></td>

     <!-- Directions -->
<?

// Disable map object? (intl. request)
//==========================================
if ( $map_obj != "disabled" ) {
   echo "     <td class=\"ob2\" onmouseover=\"this.className='ob1';\" onmouseout=\"this.className='ob2';\">\n";
   echo "      <img class=\"drag\" id=\"oDirections\" src=\"".$ipre."directions.gif\" width=\"80\" height=\"18\"></td>\n";
} else {
   echo "     <td class=\"ob2\">&nbsp;</td>\n";
}


?>

     <!--- Date Stamp --->
     <td class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';">
      <img class="drag" name="MYdate" id="oDate" src="<? echo $ipre; ?>datestamp.gif" width="80px" height="18px" ></td>

     <!--- Print Page --->
     <td class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';">
      <img class="drag" name="MYprint" id="oPrint" src="<? echo $ipre; ?>printpage.gif" width="80px" height="18px" ></td>


     <!--- FAQs --->
     <td class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';">
      <img class="drag" name="MYfaq" id="oFaq" src="<? echo $ipre; ?>faq.gif" width="80px" height="18px" ></td>


<?

//# SitePal object?
//if ( sitepal_allowed() ) {
//   # yes
//   echo "     <!-- SitePal -->\n";
//   echo "     <td class=\"ob2\" onmouseover=\"this.className='ob1';\" onmouseout=\"this.className='ob2';\">\n";
//   echo "     <img class=\"drag\" name=\"MYsitepal\" id=\"sitepal_obj\" src=\"".$engdash."sitepal.gif\" width=\"80px\" height=\"18px\" ></td>\n";
//} else {
//   # no
//   echo "     <td width=\"81\" height=\"20\" align=\"center\" valign=\"middle\" class=\"ob2\" style=\"cursor: default;\">&nbsp;</td>\n";
//}

?>

    </tr>

    <!---###################################################################################################--->
    <!------------------------------------ Drag and Drop Icons: ROW THREE ------------------------------------->
    <!---###################################################################################################--->
    <tr>

     
     <!--- Table Search --->
     <!---
     <td class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';">
     	 <img class="drag" name="MYsearch" id="oTableSearch" src="<? echo $ipre; ?>searchdb.gif" width="80px" height="18px" >
     </td>
	--->
     <!--- PopUp Win --->
     <td class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';">
     <img class="drag" name="MYpopup" id="oPopup" src="<? echo $ipre; ?>popup.gif" width="80px" height="18px" >
     </td>

     <!--- Audio Files --->
     <td class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';">
     <img class="drag" name="MYaudio" id="oMP3" src="<? echo $ipre; ?>audio.gif" width="80px" height="18px" ></td>

     <!--- Video Files --->
     <td class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';">
     <img class="drag" name="MYvideo" id="oVideo" src="<? echo $ipre; ?>video.gif" width="80px" height="18px" ></td>

     <!--- Photo Album --->
     <td class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';">
     <img class="drag" name="MYphoto" id="oPhotoAlbum" src="<? echo $ipre; ?>photoalbum.gif" width="80px" height="18px" ></td>

     <!--- Blogs --->
     <td class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';">
     <!---<img class="drag" name="MYblog" id="oBlog" src="<? echo $ipre; ?>blog.gif" width="80px" height="18px" >--->
     <img class="drag" name="MYsearch" id="oTableSearch" src="<? echo $ipre; ?>searchdb.gif" width="80px" height="18px" >
     </td>

     <!--- Faqs --->
 <?

# SitePal object?
if ( supersearch_allowed() ) {
   # yes
   echo "     <!-- SitePal -->\n";
   echo "     <td class=\"ob2\" onmouseover=\"this.className='ob1';\" onmouseout=\"this.className='ob2';\">\n";
   echo "     <img class=\"drag\" name=\"MYsupersearch\" id=\"supersearch_obj\" src=\"".$engdash."supersearch_obj.gif\" width=\"80px\" height=\"18px\" ></td>\n";
} else {
   # no
   echo "     <td width=\"81\" height=\"20\" align=\"center\" valign=\"middle\" class=\"ob2\" style=\"cursor: default;\">&nbsp;</td>\n";
}

?>

     <!--- Addons/Mods --->
     <td class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';" onClick="show_mods();">
     <img class="drag" name="addons" id="addons" src="<? echo $ipre; ?>addons.gif" width="80px" height="18px" ></td>

<?
//eval(hook("pe_ff-draggable_page_object", basename(__FILE__)));
?>

     <!--- Blank Object Spacers --->
     <!--- <td width="81" height="20" align="center" valign="middle" class="ob2">&nbsp;</td> --->
     <!--- <td width="81" height="20" align="center" valign="middle" class="ob2">&nbsp;</td> --->
    </tr>

   </table>

</div>


    <!---###################################################################################################--->
    <!----------------------------------------- ADDONS / MODS VIEW -------------------------------------------->
    <!---###################################################################################################--->

<div id="objectbar_mods">



<!---###################################################################################################---
						             _     _                            __  __           _
						    /\      | |   | |                          |  \/  |         | |
						   /  \   __| | __| | ___  _ __  ___   ______  | \  / | ___   __| |___
						  / /\ \ / _` |/ _` |/ _ \| '_ \/ __| |______| | |\/| |/ _ \ / _` / __|
						 / ____ \ (_| | (_| | (_) | | | \__ \          | |  | | (_) | (_| \__ \
						/_/    \_\__,_|\__,_|\___/|_| |_|___/          |_|  |_|\___/ \__,_|___/

<!---###################################################################################################--->

   <div class="help-text">
      <? echo lang("Click on an object below and drag it onto a drop zone for page placement."); ?>
   </div>
   <table id="objectmods_table" border="0" cellpadding="0" cellspacing="0" align="center" width="<? echo $barWidth; ?>" style="border: 1px solid #245981;">

    <tr>

     	<td align="left" valign="top" style="width: 100%; height: 70px; background-color: #3E99DF;">

     	<?
     		$top = 15;
     		$left = 8;
     		$cntr = 0;
     		for($x=0;$x<$extend_count;$x++){
     		   //echo "(".$cntr.")<br/>\n";
     			if($cntr > 2 ){
     				$top = 15;
     				$left += 83;
     				$cntr = 1;
     			}else{
     			   $cntr++;
     			}

	     		echo "<div class=\"ob2\" onmouseover=\"this.className='ob1';\" onmouseout=\"this.className='ob2';\" style=\"position:absolute;left:".$left."px;top:".$top."px;width:81px;height:20px;\">\n";
	     		echo "	<img class=\"drag\" name=\"".$mod_props[$x]['draggable_object_id']."\" id=\"".$mod_props[$x]['draggable_object_id']."\" src=\"sohoadmin/program/modules/page_editor/../../../plugins/".$mod_props[$x]['plugin_folder']."/".$mod_props[$x]['draggable_object_image']."\" width=\"80\" height=\"18\">\n";
	     		echo "</div>\n";
	     		$top += 22;
			}

     		echo "<div id=\"MOD1\" name=\"MOD1\" class=\"ob2\" onmouseover=\"this.className='ob1';\" onmouseout=\"this.className='ob2';\" onClick=\"show_mods();checkPageAreas('start');\" style=\"float: right;width:81px;height:20px;\">\n";
     		echo "	<img align=\"absmiddle\" src=\"".$ipre."back.gif\" width=\"80\" height=\"18\" vspace=\"1\" hspace=\"1\" style=\"cursor: hand;\">\n";
     		echo "</div>\n";

     	?>
     	</td>
    </tr>
   </table>
</div>



<div id="nugget">&nbsp;</div>

<div id="cell_container">

	<?

	/**********************************************************************
      New Page Editor Drag and Drop in ie and Firefox- (Joe Lain 10-12-05)
    ----------------------------------------------------------------------
    The Page Editor's obj bar icons are draggable images that are identified
    by their name.  As soon as an icon is dragged my_PickFunc() makes the
    name of the icon available.  When the icon is dropped my_DropFunc()
    gets the coordinates of the icon and finds the cell that it was dropped
    in.
	/**********************************************************************/

	// Ouput exactly 10 rows of drop zones
	//===========================================
	for ($x=1;$x<=10;$x++) {

		// Ouput each cell with correct pre-existing content (if any)
		//---------------------------------------------------------------
		for ($y=1;$y<=3;$y++) {
		   $noBlink = 0;
		   $cellContent = "";
			$areaId = "R" . $x . "C" . $y; // Used to pull existing cell content from loaded var data of same name (Ends up as 'R2C3' for 'ROW 2, COL 3')
			$tdid = "TD".$areaId; // Used to identify the cell in js
			$contentVar = ${$areaId}; // Pull existing zone content??
                      //  echo "<textarea style=\"height: 100px; border: 1px dashed #000000;\">(".strlen($out[1]).")(".$out[1].")</textarea>\n";
//$contentVar = preg_replace('/ src="images\//', ' src="http://'.$this_ip.'/images/',  $contentVar);
$contentVar = preg_replace('/ src="images\//', 'rel="external" src="images/',  $contentVar);

			// Format cell properties (b/c nobody likes to side scroll, least of all us dev types)
			//-------------------------------------------------------------------------------------------
			$zProps = " id=\"".$tdid."\" value=\"".$tdid."\"";
			//$zProps .= " style=\"\"";

         $findThis = '[a-zA-Z0-9]';


			// Now echo drop zone cell
			// --------------------------------
			echo "     <div class=\"editTable\" valign=\"top\" align=\"center\" bgcolor=\"".$option_background."\"".$zProps.">\n";
			//echo "(".${$areaId}.")";
			$endMatches = "";
         if ( eregi("pixel.gif",$contentVar) || !eregi($findThis, $contentVar) ){
            $contentVar = "<IMG height=\"50%\" src=\"sohoadmin/program/modules/page_editor/pixel.gif\" width=\"199\" border=\"0\">";
         }else{
            //echo "<textarea style=\"height: 100px; border: 1px dashed #000000;\">".$contentVar."</textarea>\n";

            # Remove older js edit calls
            $my_rep = "textEdit\([^,]+,'([^']+)'\)";
            $contentVar = eregi_replace($my_rep, "startEditor('\\1')", $contentVar);
            $my_rep = "newEdit\([^,]+,'([^']+)'\)";
            $contentVar = eregi_replace($my_rep, "startEditor('\\1')", $contentVar);
//echo "<script>\nalert(' ".str_replace("'", '', str_replace('<', '', str_replace('>', '', $contentVar)))."');</script>\n";

            // Start obj conversion to new method
            if(!eregi("droppedItem", $contentVar)){

               $replace_end = '</td></tr>(</tbody>)?</table><!-- ~~~ -->';
               $final_display = spliti($replace_end, $contentVar);
               //echo "(".count($final_display).")\n";

               foreach($final_display as $var=>$val){
                  //echo "var = (".$var.") val = (".$val.")<br>\n";
                  $out = "";
                  $outbr = "";
                  if(strlen($val) > 0){

                     if(eregi("ADOBELINK", $val)){
                        $disFree = "<img src=sohoadmin/program/modules/page_editor/client/adobe_link.gif border=0 class=tHead><!-- ##ADOBELINK## -->";
                        $cellContent .= "<div id=\"CONVERTEDOBJ".rand(100, 10000).$y."\" class=\"droppedItem\" style=\"height: 50px;\"><img src=\"sohoadmin/program/modules/page_editor/images/text_header.gif\" style=\"cursor: move;\" align=\"middle\" border=\"0\" height=\"15\" hspace=\"0\" vspace=\"0\" width=\"199\"><br clear=\"all\">\n".$disFree."</div><!-- ~~~ -->\n";
                     }elseif(eregi("FLASHLINK", $val)){
                        $disFree = "<img src=client/flash_link.gif border=0 class=tHead><!-- ##FLASHLINK## -->";
                        $cellContent .= "<div id=\"CONVERTEDOBJ".rand(100, 10000).$y."\" class=\"droppedItem\" style=\"height: 50px;\"><img src=\"sohoadmin/program/modules/page_editor/images/text_header.gif\" style=\"cursor: move;\" align=\"middle\" border=\"0\" height=\"15\" hspace=\"0\" vspace=\"0\" width=\"199\"><br clear=\"all\">\n".$disFree."</div><!-- ~~~ -->\n";
                     }elseif(eregi("WINAMPLINK", $val)){
                        $disFree = "<img src=client/winamp_link.gif border=0 class=tHead><!-- ##WINAMPLINK## -->";
                        $cellContent .= "<div id=\"CONVERTEDOBJ".rand(100, 10000).$y."\" class=\"droppedItem\" style=\"height: 50px;\"><img src=\"sohoadmin/program/modules/page_editor/images/text_header.gif\" style=\"cursor: move;\" align=\"middle\" border=\"0\" height=\"15\" hspace=\"0\" vspace=\"0\" width=\"199\"><br clear=\"all\">\n".$disFree."</div><!-- ~~~ -->\n";
                     }elseif(eregi("QUICKTIMELINK", $val)){
                        $disFree = "<img src=client/quicktime_link.gif border=0 class=tHead><!-- ##QUICKTIMELINK## -->";
                        $cellContent .= "<div id=\"CONVERTEDOBJ".rand(100, 10000).$y."\" class=\"droppedItem\" style=\"height: 50px;\"><img src=\"sohoadmin/program/modules/page_editor/images/text_header.gif\" style=\"cursor: move;\" align=\"middle\" border=\"0\" height=\"15\" hspace=\"0\" vspace=\"0\" width=\"199\"><br clear=\"all\">\n".$disFree."</div><!-- ~~~ -->\n";
                     }else{
                        //echo "<textarea style=\"height: 100px; border: 1px dashed #000000;\">".$val."</textarea>\n";

                        $find_ends = '<br clear="?all"? ?/?>(.*)';
                        $endMatches = eregi($find_ends, $val, $out);


                        if(strlen($out[1]) > 0){      # Found <br clear="all">
					
                           $pulled_content = $out[1];
                           
                           //echo "--CURRENT CONTENT---".$out[1]."--END CURRNET---<br/>\n";

                  		   $myTable = "<TABLE";
                  		   $pos2 = strpos($out[1], $myTable);

                  		   $myTableLower = "<table";
                  		   $pos3 = strpos($out[1], $myTableLower);

                  		   if($pos2 !== false || $pos3 !== false){
                  		      // First attempt at pulling everything after blink...
                  		      // Works... but much to slow with lots o content.
                  		      // Can you make this regexp more efficient?
                              //$new_find_ends = '<div.*class="?TXTCLASS"?.*><blink>(.*)';
                              //$new_endMatches = eregi($new_find_ends, $out[1], $new_out);

                              # eregi to slow, split it up
                              $chars = spliti("<blink>", $out[1]);
                              $firstChunk = spliti("<div", $chars[0]);
                              //print_r($chars);
                              //print_r($firstChunk);

                              $pulled_content = "<div".$firstChunk[1]."<blink>".$chars[1]."\n";
                              
                              

                              if( eregi("<blink>", $chars[1]) && !eregi("</blink>", $chars[1]) ){
                                 # Add </blink> if missing one
                                 $pulled_content = $pulled_content."</blink>\n";
                              }elseif( !eregi("</blink>", $chars[1]) ){
                                 # Strip <blink>, missing closing tag in correct place
                                 $pulled_content = eregi_replace("<blink>", "", $pulled_content);
                              }

                  		   }
						
                           $cellContent .= "<div id=\"CONVERTEDOBJ".rand(100, 10000).$y."\" class=\"droppedItem\" style=\"height: 120px;\">\n<img src=\"sohoadmin/program/modules/page_editor/images/text_header.gif\" style=\"cursor: move;\" align=\"middle\" border=\"0\" height=\"15\" hspace=\"0\" vspace=\"0\" width=\"199\"><br clear=\"all\">\n".$pulled_content."\n</div><!-- ~~~ -->\n";

                        }else{                  # Didnt find <br clear="all">
//$outbr[1] = preg_replace('/ src="images\//', ' src="http://'.$this_ip.'/images/',  $outbr[1]);
                           # Check to see if there is a <br>
                           # Some old pages have <IMG style="CURSOR: move" height=15 hspace=0 src="sohoadmin/program/modules/page_editor/images/text_header.gif" width=199 align=left border=0><BR>
                           $find_ends = '<br>(.*)';
                           $endMatches = eregi($find_ends, $val, $outbr);
 
                           if(strlen($outbr[1]) > 0)
                              $cellContent .= "<div id=\"CONVERTEDOBJ".rand(100, 10000).$y."\" class=\"droppedItem\" style=\"height: 120px;\">\n<img src=\"sohoadmin/program/modules/page_editor/images/text_header.gif\" style=\"cursor: move;\" align=\"middle\" border=\"0\" height=\"15\" hspace=\"0\" vspace=\"0\" width=\"199\"><br clear=\"all\">\n".$outbr[1]."\n</div><!-- ~~~ -->\n";

                           //echo "<textarea style=\"height: 100px; border: 1px dashed #000000;\">(".strlen($outbr[1]).")(".$outbr[1].")</textarea>\n";
                        }
                     }
                  }  // End if empty
               }  // End foreach item in cell
               $cellContent = eregi_replace("sohocenter", "center", $cellContent);

               $contentVar = $cellContent;
            }  // End obj conversion
         }

         echo "      ".$contentVar;

// Show contents in textarea for testing
//		   echo "      <br>ContentVar = <br><textarea name=\"mmtestt$y\">\n";
//		   echo "--0---".$endMatches[0]."--end0---<br/>\n";
//		   echo $contentVar."\n";
//		   echo "</textarea>\n";

			echo "     </div>\n";
		}
	}

echo "</div>\n";



###########################################################################################################
### ALL LAYERS BELOW THIS, ARE LAYERS THAT APPEAR ON TOP OF THE OBJECT LAYER WHEN AN OBJECT IS DROPPED ON
### A DROP AREA. FOR INSTANCE: THESE LAYERS ARE WHERE USERS WOULD SELECT IMAGES, DATABASE TABLES, ETC.
###########################################################################################################

include("includes/layer_props.php");


# COMMENTED OUT: For some reason this must go below my_PickFunc and such or move cursor breaks for all objects
if ( supersearch_allowed() ) {
   # sitepal_dialog
	# Super Search plugin: place on page
	$onload_action = "place_supersearch();show_hide_layer('objectbar','','show','supersearch_dialog','','hide');";
	?>
	<DIV ID="supersearch_dialog" style="position:absolute; left:0px; top:0px; width:100%; height:115; z-index:4; overflow: none; background-color: oldlace; visibility: hidden" onMouseOver="HighDropZone();">
	<div style="text-align: center;padding: 30px 10px;">
	 <input type="button" onclick="<? echo $onload_action; ?>" value="Place Super Search box on this page &gt;&gt;" style="font-size: 18px;">
	 <input type="button" onclick="show_hide_layer('objectbar','','show','supersearch_dialog','','hide');replaceImageData();makeUnScroll(ColRowID);" value="Cancel" style="font-size: 18px;">
	</div>
	</div>
<?php

}

?>




<!-- ############################################################# -->
<!-- #### Progress Bar Layer:Save							  #### -->
<!-- ############################################################# -->

<div id="ProgressBarSave" style="position:absolute; width:100%; height:100%; overflow: none;ground: white; z-index:150; left: 0px; top: 0px; visibility: hidden">
	<table border=0 cellpadding="0" cellspacing=0 width=100% height="100%" bgcolor=white><tr><td align=center valign=center>
	<img src="sohoadmin/program/modules/page_editor/images/save_data.gif" WIDTH=200 HEIGHT=30 BORDER=0></td></tr></table>
</div>

<!-- ############################################################# -->
<!-- #### Progress Bar Layer:DELETE					  #### -->
<!-- ############################################################# -->

<div id="DELETEPAGE" style="position:absolute; width:100%; height:100%; z-index:160; left: 0px; top: 0px; overflow: none; visibility: hidden">
<?
echo ("<table border=0 cellpadding=0 cellspacing=0 width=100% height=100% bgcolor=maroon><tr><td align=center valign=center>\n");
echo ("<font face=Arial size=4 color=white><B>DELETING \"".$_GET['currentPage']."\" PAGE NOW...</font></b></td></tr></table>\n");
?>
</div>


<?
//###################################################################################################
//						             _     _                            __  __           _
//						    /\      | |   | |                          |  \/  |         | |
//						   /  \   __| | __| | ___  _ __  ___   ______  | \  / | ___   __| |___
//						  / /\ \ / _` |/ _` |/ _ \| '_ \/ __| |______| | |\/| |/ _ \ / _` / __|
//						 / ____ \ (_| | (_| | (_) | | | \__ \          | |  | | (_) | (_| \__ \
//						/_/    \_\__,_|\__,_|\___/|_| |_|___/          |_|  |_|\___/ \__,_|___/
//
//###################################################################################################
//include($MYmod1_display);
eval(hook("pe-properties_dialog_layer", basename(__FILE__)));

?>


<script language="javascript">

<?

if ($theNewsFlag == 1) {
	$cpDis = "Editing Newsletter: \"<U>".$_GET['currentPage']."</U>\"";
} else {
	$cpDis = "Editing: \"<U>".$_GET['currentPage']."</U>\"";
}
echo "var newStatus = '$cpDis';\n";

?>

//Define global variables
var dot_com = $('dot_com').value
//alert(dot_com)

</script>

<script type="text/javascript">


objects = document.getElementsByTagName("object");
for (var i = 0; i < objects.length; i++)
{
    objects[i].outerHTML = objects[i].outerHTML;
}

</script>
</div>
</body>
</html>