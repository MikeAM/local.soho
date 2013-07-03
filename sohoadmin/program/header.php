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

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// By License you may not modify any portion of this script. This particular
// script has dependancies and programming that can not be modified.
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


track_vars;
session_start();

# Include core interface files!
require_once("includes/product_gui.php");

###############################################################################
function enc($v) {
	$v = md5($v);
	return $v;
}
$SECURE_MOD_LICENSE = 0;
$tmp = eregi_replace("tmp_content", "", $cgi_bin);
$filename = $tmp."filebin/soholaunch.lic";
$file = fopen("$filename", "r");
	$data = fread($file,filesize($filename));
fclose($file);
$keydata = split("\n", $data);
// Security
$check_sum = enc("secure");
if (trim($keydata[7]) == $check_sum) {
	$SECURE_MOD_LICENSE = 1;
} else {
	$SECURE_MOD_LICENSE = 0;
}
###############################################################################
if($_GET['logout'] == 'logout'){
	session_destroy();
	echo "<script language=\"JavaScript\">\n";
	echo "parent.close();";
	echo "</script>\n";
}
?>

<html>
<head>
<title><? echo "Site: ".$_SESSION['this_ip']; ?></title>
<style>.pagedd { width: 130px; } </style>
<link rel="stylesheet" href="<?php echo httpvar().$_SESSION['docroot_url']; ?>/sohoadmin/program/includes/product_buttons-ultra.css">

<script type="text/javascript" src="<?php echo httpvar().$_SESSION['docroot_url']; ?>/sohoadmin/client_files/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo httpvar().$_SESSION['docroot_url']; ?>/sohoadmin/client_files/jquery-ui.min.js"></script>

<script language="JavaScript">
var loadedPage;
function killErrors() {
	return true;
}
window.onerror = killErrors;

function MM_findObj(n, d) { // v3.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
}

function MM_showHideLayers() { // v3.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
    obj.visibility=v; }
}

function SOHO_openBrWindow(theURL,winName,features) {
  window.open(theURL,winName,features);
}

// #################################################################################

function SOHO_Alert(alertVar) {
	alert(alertVar);
}
</script>

<!-- ----------------------------------------------------------------------------- -->
<!-- CREATE CUSTOM JScript FUNCTIONS TO SAVE PAGE DATA IN "MAIN EDIT" WINDOW FRAME -->
<!-- ----------------------------------------------------------------------------- -->

<SCRIPT LANGUAGE="javascript">

function savePage(redirect) {
	var confirm_save = 1;

	if (redirect != "page_editor.php") {
		var tiny = top.confirm('<? echo lang("Do you wish to save the changes you have made"); ?>?\n\n<? echo lang("Click \"OK\" to Save changes now OR"); ?>\n<? echo lang("Click \"Cancel\" to discard changes"); ?>.');
		if (tiny != false) { var confirm_save = 1; } else { var confirm_save = 0; }
	}else{
	   var confirm_save = 1;
	}
//alert(confirm_save+'hi');

	var pgboxcount=parent.body.document.getElementById('boxcount').value;
	//alert(parent.body.TouchMe('TDB1')+' hi '+pgboxcount);
   if(!document.all){
   	
//      is_save = redirect.search("editor");
//      if(is_save>0){
//         redirect='page_editor.php';
//      }
// 

   	if (confirm_save == 1) {   		
         //show_hide_layer('ProgressBarSave?body','','show');			// Display Save Progress Bar in Edit Window
   		//parent.footer.CURPAGENAME.innerHTML = 'Saving Changes...';	// Update Status Bar
   		var saveText = "";
   		var tempValue = "";
   		//var saveText = parent.body.saveForm.innerHTML;			// Get information from Page Editor
   		var saveText = parent.body.GetSaveForm();			// Get information from Page Editor
		saveText = saveText.replace('<img class="blockerimg" src="whitespace.gif">','');
		//alert('hi '+pgboxcount);
////// sidebar stuff
		saveText = saveText+"<input type=hidden name='pgboxcount' value='"+pgboxcount+"'>\n";
		if(pgboxcount > 0){						
			for (var tbx=1;tbx<=pgboxcount;tbx++){
				tempValue = parent.body.TouchMe('TDB'+tbx);				
				//saveText = saveText+"<TEXTAREA NAME='SB"+tbx+"' STYLE=display: none>"+tempValue+"</TEXTAREA>";
				
				is_txtarea = tempValue.search('<TEXTAREA');
				if(is_txtarea>0){
					var textArr = tempValue.split('<TEXTAREA')
					var textLen = textArr.length
					for(var x=0; x<textLen; x++){
						tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
						tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
					}
				}
				if(parent.body.document.getElementById('sidebaroption'+tbx).checked==true){
					saveText = saveText+"<input type=hidden name='copybox"+tbx+"' value='"+parent.body.document.getElementById('copybox'+tbx).value+"'>\n";
					saveText = saveText+"<input type=hidden name='defaultbox"+tbx+"' value='false'>\n";
					saveText = saveText+"<TEXTAREA NAME='SB"+tbx+"' STYLE=display: none></TEXTAREA>";
				} else {
					saveText = saveText+"<input type=hidden name='copybox"+tbx+"' value=''>\n";
					saveText = saveText+"<input type=hidden name='defaultbox"+tbx+"' value='"+parent.body.document.getElementById('defaultbox'+tbx).checked+"'>\n";
					saveText = saveText+"<TEXTAREA NAME='SB"+tbx+"' STYLE=display: none>"+tempValue+"</TEXTAREA>";
				}
				
			}
	
			//alert(saveText);
		}
		
///// End sidebar stuff




   		<?php

   		###############################################
   		## BUILD JSCRIPT ROW/COLUMN ARRAY FOR SAVING ##
   		###############################################

   		for ($x=1;$x<=10;$x++) {
   			for ($y=1;$y<=3;$y++) {
   				$thisVar = "R" . $x . "C" . $y;
   				//echo ("                 tempValue = parent.body.TD$thisVar.innerHTML;\n");
   				echo ("                   tempValue = parent.body.TouchMe('TD$thisVar');\n");
				
			      echo ("							is_txtarea = tempValue.search('<textarea');\n");
			      echo ("							if(is_txtarea>0){\n");
			      echo ("								var textArr = tempValue.split('<textarea')\n");
			      echo ("								var textLen = textArr.length\n");
			      //echo ("								alert(tempValue)\n");
			      echo ("								for(var x=0; x<textLen; x++){\n");
			      echo ("									tempValue = tempValue.replace('<textarea','<sohotextarea');\n");
			      echo ("									tempValue = tempValue.replace('</textarea>','</sohotextarea>');\n");
			      echo ("								}\n");
			      //echo ("								alert(tempValue)\n");
			      echo ("							}\n");
   				//echo ("                   tempValue = encodeURI(tempValue)\n");


   				echo ("                   saveText = saveText+\"<TEXTAREA NAME=$thisVar STYLE=display: none>\"+tempValue+\"</TEXTAREA>\";\n");
   			}
   		}

   		echo "            saveText = saveText+\"<input type=hidden name=redirect value=\"+redirect+\"><input type=hidden name=serial_number value='$serial_number'><input type=hidden name=dot_com value='$dot_com'>\";\n";
   		?>
		
		saveText = saveText.replace('<img class="blockerimg" src="whitespace.gif">','');
		 
   		//parent.body.saveForm.innerHTML = saveText;			// Finalize "Save Data"
   		parent.body.SendSaveText(saveText);			// Finalize "Save Data"
   		
   		parent.body.GoToSave();							// Save current Page

   	} else {
         //parent.body.ShowNoSave();
   //      show_hide_layer('NOSAVE_LAYER','','show');			// Display NO SAVE Loading image in Edit Window
         if(redirect == "preview"){
         		
            var daPage = parent.frames.body.sendPageName();
            
            newwindow=window.open('javascript:void window.focus()', '_newtab');
            newwindow.close();
            newwindow=window.open('../../'+daPage.replace(/[ ]/g,'_')+'.php','_newtab');
            newwindow.focus();
            
            //if(window.focus) { previewwindow.focus(); }
            //var prev_path = "modules/page_editor/page_editor.php?previewWindow=1&currentPage="+daPage+"&=SID";
   		   //parent.body.location.href=prev_path;
   		}else{
   			window.focus();
   		   //parent.body.location.href="modules/page_editor/"+redirect;
		
   		  	if(redirect.search("ttp://") > 0 || redirect.search("ttps://") > 0){
   		  		 parent.body.location.href=redirect;
   			} else {
   				<?php echo "   		   parent.body.location.href='".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/modules/page_editor/'+redirect;\n"; ?>
   			}
   		   
   		}
   	}
   }else{
 
	if (confirm_save == 1) {
        MM_showHideLayers('ProgressBarSave?body','','show');			// Display Save Progress Bar in Edit Window
		parent.frames.footer.CURPAGENAME.innerHTML = 'Saving Changes...';	// Update Status Bar
		var saveText = "";
		var tempValue = "";
		var saveText = parent.frames.body.saveForm.innerHTML;			// Get information from Page Editor
		var daPage = parent.frames.body.sendPageName();

		saveText = saveText.replace('<img class="blockerimg" src="whitespace.gif">','');

////// sidebar stuff
//alert(pgboxcount+' yoyo');
		saveText = saveText+"<input type=hidden name='pgboxcount' value='"+pgboxcount+"'>\n";
		if(pgboxcount > 0){
			for (var tbx=1;tbx<=pgboxcount;tbx++){
				tempValue = parent.body.TouchMe('TDB'+tbx);			
				
				//saveText = saveText+"<TEXTAREA NAME='SB"+tbx+"' STYLE=display: none>"+tempValue+"</TEXTAREA>";
				
				is_txtarea = tempValue.search('<TEXTAREA');
				if(is_txtarea>0){
					var textArr = tempValue.split('<TEXTAREA')
					var textLen = textArr.length
					for(var x=0; x<textLen; x++){
						tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
						tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
					}
				}
				
				
				if(parent.body.document.getElementById('sidebaroption'+tbx).checked==true){
					saveText = saveText+"<input type=hidden name='copybox"+tbx+"' value='"+parent.body.document.getElementById('copybox'+tbx).value+"'>\n";
					saveText = saveText+"<input type=hidden name='defaultbox"+tbx+"' value='false'>\n";
					saveText = saveText+"<TEXTAREA NAME='SB"+tbx+"' STYLE=display: none></TEXTAREA>";
				} else {
					saveText = saveText+"<input type=hidden name='copybox"+tbx+"' value=''>\n";
					saveText = saveText+"<input type=hidden name='defaultbox"+tbx+"' value='"+parent.body.document.getElementById('defaultbox'+tbx).checked+"'>\n";
					saveText = saveText+"<TEXTAREA NAME='SB"+tbx+"' STYLE=display: none>"+tempValue+"</TEXTAREA>";
				}
				
				//saveText = saveText+"<TEXTAREA NAME='SB"+tbx+"' STYLE=display: none>"+tempValue+"</TEXTAREA>";
			}
	
			//alert(saveText);
		}
		
///// End sidebar stuff




		<?php

		###############################################
		## BUILD JSCRIPT ROW/COLUMN ARRAY FOR SAVING ##
		###############################################

		for ($x=1;$x<=10;$x++) {
			for ($y=1;$y<=3;$y++) {
				$thisVar = "R" . $x . "C" . $y;
				echo ("                    tempValue = parent.frames.body.TD$thisVar.innerHTML;\n");

		      echo ("							is_txtarea = tempValue.search('<TEXTAREA');\n");
		      echo ("							if(is_txtarea>0){\n");
		      echo ("								var textArr = tempValue.split('<TEXTAREA')\n");
		      echo ("								var textLen = textArr.length\n");
		      echo ("								for(var x=0; x<textLen; x++){\n");
		      echo ("									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');\n");
		      echo ("									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');\n");
		      echo ("								}\n");
		      echo ("							}\n");

				echo ("                 saveText = saveText+\"<TEXTAREA NAME=$thisVar STYLE=display: none>\"+tempValue+\"</TEXTAREA>\";\n");
			}
		}

		echo "            saveText = saveText+\"<input type=hidden name=redirect value=\"+redirect+\"><input type=hidden name=serial_number value='$serial_number'><input type=hidden name=dot_com value='$dot_com'>\";\n";
		//echo "         alert(saveText)\n";


		?>

		parent.frames.body.saveForm.innerHTML = saveText;			// Finalize "Save Data"
		parent.frames.body.save.submit();							// Save current Page

	} else {

        //MM_showHideLayers('NOSAVE_LAYER?body','','show');			// Display NO SAVE Loading image in Edit Window
		//parent.frames.body.location.href="main_menu.php";
		//parent.body.location.href="modules/page_editor/"+redirect;
         if(redirect == "preview"){

            var daPage = parent.frames.body.sendPageName();
            
            newwindow=window.open('javascript:void window.focus()', '_newtab');
            newwindow.close();            
            
            newwindow=window.open('../../'+daPage.replace(/[ ]/g,'_')+'.php','_newtab');
            newwindow.focus();
            
            //if(window.focus) { previewwindow.focus(); }
            //var prev_path = "modules/page_editor/page_editor.php?previewWindow=1&currentPage="+daPage+"&=SID";
   		   //parent.body.location.href=prev_path;
   		}else{
   			window.focus();
   		   //parent.body.location.href="modules/page_editor/"+redirect;
		
   		  	if(redirect.search("ttp://") > 0 || redirect.search("ttps://") > 0){
   		  		 parent.body.location.href=redirect;
   			} else {
   				<?php echo "   		   parent.body.location.href='".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/modules/page_editor/'+redirect;\n"; ?>
   			}
   		   
   		}
	}
}

} // End Save Page Function

// <!-- ----------------------------------------------------------------------------- -->
// <!-- ----------------------------------------------------------------------------- -->

function StatusReset() {
<?php
echo ("parent.footer.CURPAGENAME.innerHTML = '';\n");
echo ("parent.footer.SUBPAGEOF.innerHTML = \"\";\n");
echo ("parent.footer.PAGESTAT.innerHTML = '';\n");
?>
}

function redirect() {
<? echo("strLink = \"main_menu.php?=SID\";\n"); ?>
   parent.footer.orboff();
   parent.body.location.href=strLink;
}

function navigateHome() {
<? echo("strLink = \"main_menu.php?=SID\";\n"); ?>
   parent.body.location.href=strLink;
   var p = 'Main Menu';
   parent.frames.footer.setPage(p);
}

function navigateOpen() {
<? echo("strLink = \"modules/open_page.php?=".SID."\";\n"); ?>
   parent.body.location.href=strLink;
}

function logout() {
<? echo("strLink2 = \"header.php?logout=logout\";\n"); ?>
	parent.body.location.href=strLink2;
	//parent.close();
}

function logoutEditor() {
 parent.close();
}

function open_new_window(theURL,winName,features) {
	
	window.open(theURL,winName,features);
}

function viewsite() {
	
	open_new_window('<?php echo httpvar().$this_ip; ?>/index.php?nosessionkill=1','_newtab','width=980,height=800, scrollbars=yes,resizable=yes,toolbar=yes');
}

function page_properties() {
   if(!document.all){
	   parent.body.ShowPageProps();
   }else{
      MM_showHideLayers('pageproperties?body','','show','saveaslayer?body','','hide');
      parent.frames.body.save.style.display = '';
   }
}

function save_as_layer() {
   if(!document.all){
      parent.body.ShowSaveAs();
   }else{
      MM_showHideLayers('pageproperties?body','','hide','saveaslayer?body','','show');
      parent.frames.body.save.style.display = '';
   }
}

function cartdo(value) {

	var doTest = 0;

	if (value == "userprefs") {
		strLink = "webmaster/webmaster.php?<?=SID?>";
		var doTest = 1;
	}

	if (value == "aupdate") {
		strLink = "webmaster/software_updates.php?<?=SID?>";
		var doTest = 1;
	}

	if (value == "bakrest") {
		strLink = "webmaster/backup_restore.php?<?=SID?>";
		var doTest = 1;
	}

	if (value == "metadata") {
		strLink = "webmaster/meta_data.php?<?=SID?>";
		var doTest = 1;
	}

	if (value == "globalset") {
		strLink = "webmaster/global_settings.php?<?=SID?>";
		var doTest = 1;
	}

	if (value == "siteback") {
		strLink = "webmaster/backup_restore.php?<?=SID?>";
		var doTest = 1;
	}

	if (value == "newsletter") {
		strLink = "modules/mods_full/enewsletter.php?<?=SID?>";
		parent.body.location.href=strLink;
		var doTest = 1;
	}

	if (value == "calendar") {
		strLink = "modules/mods_full/event_calendar.php?<?=SID?>";
		parent.body.location.href=strLink;
		var doTest = 1;
	}

	if (value == "shopping_cart") {
		strLink = "modules/mods_full/shopping_cart.php?<?=SID?>";
		parent.body.location.href=strLink;
		var doTest = 1;
	}

	if (value == "database") {
		strLink = "modules/mods_full/database_tables.php?<?=SID?>";
		parent.body.location.href=strLink;
		var doTest = 1;
	}

	if (value == "secure_users") {
		strLink = "modules/mods_full/security.php?<?=SID?>";
		parent.body.location.href=strLink;
		var doTest = 1;
	}


	if ( doTest == 1 ) {
		parent.body.location.href=strLink;
	} else {
		alert(value);
	}

}

function findit() {
	alert('Search Working');
}


<?php
# Where should the [?] icon link to?
if ( $_SESSION['hostco']['help_icon'] == "custom" && $_SESSION['hostco']['help_icon_url'] != "" ) { // Pull link from branding options array
   $helpicon_goto = $_SESSION['hostco']['help_icon_url'];

} elseif ( strlen($users_man) > 10 && $_SESSION['hostco']['help_icon'] == "" ) {
   $helpicon_goto = $users_man; // Account for old branding method (host.conf.php)

} else {
   $helpicon_goto = "manual.soholaunch.com"; //  Go to Soholaunch Online Manual
}

$helpicon_goto = str_replace("https://", "", str_replace("http://", "", $helpicon_goto));

# Pass 'from product' var so manual script knows to scroll
if ( eregi("\?", $helpicon_goto) ) {
   $helpicon_goto .= "&p=1";
} else {
   $helpicon_goto .= "?p=1";
}

?>

function view_docs() {
   parent.footer.orboff();
	strLink = "<?php echo httpvar().$helpicon_goto; ?>";
	parent.body.location.href=strLink;
}

function showHelp(){
	//alert('something');

<?php
	if($_SESSION['help_link']!=''){						
		echo "window.open('".$_SESSION['help_link']."','billy','width=800, height=600, scrollbars=auto');\n";
	} else {		
		echo "window.open('modules/help_center/help_center.php','billy','width=800, height=600, scrollbars=auto');\n";
	}
?>

	
	//window.open('includes/help_files/HelpMe_V2.php','billy','width=800, height=600, scrollbars=auto');
}

var newWin = null;
function openHelp(openLink){
	if (!newWin || newWin.closed){
		newWin=window.open(openLink,'HelpCenter','');
	} else {
		newWin.close();
		newWin=window.open(openLink,'HelpCenter','');
	}
}
<?php


/*---------------------------------------------------------------------------------------------------------*
 _  _            _
| || | ___  ___ | |__
| __ |/ _ \/ _ \| / /
|_||_|\___/\___/|_\_\

/*---------------------------------------------------------------------------------------------------------*/
eval(hook("header.php:top_javascript", basename(__FILE__)));
?>

</SCRIPT>
<style>
html,body {
 background-color:#DEDEDE;
}
</style>
</head>
<!--- <link rel="stylesheet" href="product_gui.css" type="text/css"> --->
<link rel="stylesheet" href="includes/display_elements/product_gui-v2.css" type="text/css">



<body style="background-color:#DEDEDE;" background="includes/images/top-bg.png" text="black" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
//<body style="background-color:#DEDEDE;" bgcolor="#EFEFEF" background="includes/images/top-bg.png" text="black" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
/// Determine current 'User Mode'
###===============================================================
$usrmde = "";
if ( eregi("php4evr", $getSpec["dev_mode"]) ) {
   $usrmde = "<font style=\"color: #D70000;\">Custom Mod</font>";

} elseif ( eregi("imadev", $getSpec["dev_mode"]) ) {
   $usrmde = "<font style=\"color: #FF6600;\">Advanced</font>";

} elseif ( eregi("devlite", $getSpec["dev_mode"]) ) {
   $usrmde = "<font style=\"color: #6699cc;\">Intermediate</font>";
}

//if ( $usrmde != "" ) {
//   echo "  <td align=\"left\" valign=\"middle\" class=\"text\" style=\"padding-left: 10px;\">\n";
//   echo "   <b>User Mode:</b> ".$usrmde."\n";
//   echo "  </td>\n";
//}


######################################################################################################
# Define button arrangements for feature modules
# NOTE: All upper menus have Main Menu and logout buttons
#
# mkbutton(id, display text, css class, onclick)
#=====================================================================================================
$upnav = array(); // Will contain each menu layer and its buttons - indexed by div id

## MAIN MENU
##===============================================
if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_EDIT_PAGES;", $CUR_USER_ACCESS)) {
   $upnav['MAIN_MENU_LAYER'][] = mkbutton( "openpage", "Open Page", "nav_main", "navigateOpen()" );
}
$upnav['MAIN_MENU_LAYER'][] = mkbutton( "viewsite", "View Site", "nav_main", "viewsite();" );
if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_WEBMASTER;", $CUR_USER_ACCESS)) {
   $upnav['MAIN_MENU_LAYER'][] = mkbutton( "webmaster", "Webmaster", "nav_main", "cartdo('userprefs');" );
}

echo "<script type=\"text/javascript\">
function getPageEditorContents(){
	var curpageval='';
	var nn = parent.frames['body'].document.location+\"\";
	if(nn.search(\"sohoadmin/program/modules/page_editor/page_editor.php\") > 0){
		for(var x=1; x<=10; x++){
			for(var y=1; y<=3; y++){
				curpageval = curpageval+''+parent.frames.body.document.getElementById('TDR'+x+'C'+y).innerHTML;
			}
		}
		return curpageval;
	}
}\n";


 
echo "	function edit_thispage(v) { 
		parent.body.focus();
		if(v.length > 0){			
			var nocache = '".time()."';
			var curpageval='';
			var nn = parent.frames['body'].document.location+\"\";
			if(nn.search(\"sohoadmin/program/modules/page_editor/page_editor.php\") > 0){
				if(window.loadedPage){
					if(window.loadedPage == getPageEditorContents() && getPageEditorContents()!=''){
						if(top.body.document.getElementById('currentPage').value.replace(/[ ]/g,'+')!=v){						
							parent.frames['body'].document.location = '".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/modules/page_editor/page_editor.php?currentPage='+v+'&nocache='+nocache;
						}					
					} else {
						savePage('page_editor.php?currentPage='+v.replace(/[ ]/g,'+')+'&nocache='+nocache);
					}
				} else {
					//document.getElementById('jump_menupg').selectedIndex=0;
					//top.ultramenu.document.getElementById('jump_menupg').selectedIndex=0;
					//document.getElementById('jump_menupg').selectedIndex=0;
					//savePage('page_editor.php?currentPage='+v.replace(/[ ]/g,'+')+'&nocache='+nocache);
					//alert(top.body.document.getElementById('currentPage').value+'  '+v);
					parent.frames['body'].document.location = '".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/modules/page_editor/page_editor.php?currentPage='+v+'&nocache='+nocache;
				//	alert('hi');
					//savePage(v);
				}

			} else {
				parent.frames['body'].document.location = '".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/modules/page_editor/page_editor.php?currentPage='+v+'&nocache='+nocache;
			}

		}
	}
		
		

</script>\n";



$dddswitch = "<div style=\"position:absolute;top:4px;left:4px;height:0px;width:100%;z-index:9999;\">";
$dddswitch .= "<div id=\"jumpmenudiv\" style=\"width:130px;float:left;\">";
//$dddswitch .= "		<select id=\"jump_menupg1\" class=\"pagedd\" name=\"jump_menupg\" onchange=\"edit_thispage(this.options[this.selectedIndex].value);\">\n";

$page_editordd=1;
ob_start();
include('sitepages-dd.inc.php');
$dddswitch .= ob_get_contents();
ob_end_clean();
//include('modules/sitepage_dropdown.inc.php');
$dddswitch .= "</div>";


//$dddswitch .= "<div style=\"width:190px;float:right;text-align:right;margin-right:6px;margin-top:0px;\">";
//$dddswitch  .= "<span style=\"margin-right:8px;margin-left:5px;\"><a href=\"javascript:void(0);\" class=\"grayButton\" onclick=\"savePage('../../main_menu.php');\"><span>Main&nbsp;Menu</span></a></span>";

//$dddswitch .= "</div>";


$dddswitch .= "<div style=\"width:230px;float:right;text-align:right;margin-right:6px;margin-top:0px;\">";

	if($_SESSION['help_link']!=''){						
		//$dddswitch .= "	<span onclick=\"openHelp('".$_SESSION['help_link']."');\" style=\"cursor:pointer;position:relative;margin:0px 5px 0px 5px;height:22px;float:right;\"><div style=\"position:absolute;left:0;top:2px;margin:0;height:20px;width:19px;background-image:url('".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/includes/images/help4.png');background-repeat:no-repeat;\">&nbsp;</div><a href=\"javascript:void(0);\" class=\"grayButton\" onclick=\"openHelp('".$_SESSION['help_link']."');\"><span>&nbsp;&nbsp;<strong>Get Support</strong></span></a></span>\n";
		$dddswitch .= "	<span style=\"cursor:pointer;position:relative;margin:0px 5px 0px 5px;height:22px;float:right;\"><div style=\"position:absolute;left:0;top:2px;margin:0;height:20px;width:19px;background-image:url('".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/includes/images/help4.png');background-repeat:no-repeat;\">&nbsp;</div><a href=\"".$_SESSION['help_link']."\" target=\"_BLANK\" class=\"grayButton\"><span>&nbsp;&nbsp;<strong>Get Support</strong></span></a></span>\n";
	} else {		
		//$dddswitch .= "	<span onclick=\"openHelp('".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/modules/help_center/help_center.php?man=Page_Editor.php');\" style=\"cursor:pointer;position:relative;margin:0px 5px 0px 5px;height:22px;float:right;\"><div style=\"position:absolute;left:0;top:2px;margin:0;height:20px;width:19px;background-image:url('".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/includes/images/help4.png');background-repeat:no-repeat;\">&nbsp;</div><a href=\"javascript:void(0);\" class=\"grayButton\" onclick=\"openHelp('".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/modules/help_center/help_center.php');\"><span>&nbsp;&nbsp;<strong>Get Support</strong></span></a></span>\n";
		$dddswitch .= "	<span style=\"cursor:pointer;position:relative;margin:0px 5px 0px 5px;height:22px;float:right;\"><div style=\"position:absolute;left:0;top:2px;margin:0;height:20px;width:19px;background-image:url('".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/includes/images/help4.png');background-repeat:no-repeat;\">&nbsp;</div><a href=\"".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/modules/help_center/help_center.php\" target=\"_BLANK\"  class=\"grayButton\"><span>&nbsp;&nbsp;<strong>Get Support</strong></span></a></span>\n";
	}

//$dddswitch  .= "<span style=\"margin-right:8px;margin-left:5px;\"><a href=\"javascript:void(0);\" class=\"grayButton\" onclick=\"savePage('../../main_menu.php');\"><span>Main&nbsp;Menu</span></a></span>";



$dddswitch .= "</div>";
//
//$dddswitch .= "<div style=\"width:190px;float:right;text-align:right;margin-right:6px;margin-top:0px;\">";

////$dddswitch .= "	<span style=\"float:right;\"><a style=\"margin-top:19px;\" class=\"grayButton\" href=\"http://ultra.soholaunch.com\" target=\"_BLANK\" title=\"View Website\"><span><strong>View Website</strong></span></a></span>\n";
//$dddswitch .= "</div>";

echo $dddswitch .= "</div>";

$dddswitch = '';



 
	 


$main_and_help_buts = "<div style=\"position:absolute;width:190px;left:99%;top:4px;text-align:right;\">";
//$main_and_help_buts .= "<div style=\"padding-left:450px;position:relative;\">";
//$main_and_help_buts .= "<div style=\"float:right;margin-right:8px;margin-top:4px;width:190px!important;\">\n";
$main_and_help_buts .= "<span style=\"margin-right:8px;margin-left:5px;\"><a href=\"javascript:void(0);\" class=\"grayButton\" onclick=\"savePage('../../main_menu.php');\"><span>Main&nbsp;Menu</span></a></span>";


	if($_SESSION['help_link']!=''){		
		$main_and_help_buts .= "<a onclick=\"openHelp('".$_SESSION['help_link']."');\" href=\"javascript:void(0);\" class=\"help\" style=\"padding:3px 15px 6px 25px; text-decoration:none;line-height:13px;width:25px;height:13px;font-family:Verdana,Geneva,sans-serif;font-size:11px;color:#ECECEC;background:url('".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/includes/images/hlp.png') no-repeat; \">Help</a>";
	} else {
		$main_and_help_buts .= "<a onclick=\"openHelp('".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/modules/help_center/help_center.php?man=Page_Editor.php');\" href=\"javascript:void(0);\" class=\"help\" style=\"padding:3px 15px 6px 25px; text-decoration:none;line-height:13px;width:25px;height:13px;font-family:Verdana,Geneva,sans-serif;font-size:11px;color:#ECECEC;background:url('".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/includes/images/hlp.png') no-repeat; \">Help</a>";
	}	

//$main_and_help_buts .= "</div>\n";
//$main_and_help_buts .= "</div>";
$main_and_help_buts .= "</div>";



//$main_and_help_buts = "<div style=\"position:absolute;width:190px;left:99%;top:4px;text-align:right;\">";
//$main_and_help_buts .= "	<span onclick=\"openHelp('http://ultra.soholaunch.com/sohoadmin/program/modules/help_center/help_center.php');\" style=\"cursor:pointer;position:relative;margin:20px 5px 0px 5px;height:22px;float:right;\"><div style=\"position:absolute;left:0;top:2px;margin:0;height:20px;width:19px;background-image:url('http://ultra.soholaunch.com/sohoadmin/program/includes/images/help4.png');background-repeat:no-repeat;\">&nbsp;</div><a href=\"javascript:void(0);\" class=\"grayButton\" onclick=\"openHelp('http://ultra.soholaunch.com/sohoadmin/program/modules/help_center/help_center.php');\"><span>&nbsp;&nbsp;<strong>Get Support</strong></span></a></span>\n";
//$main_and_help_buts .= "	<span style=\"float:right;\"><a style=\"margin-top:19px;\" class=\"grayButton\" href=\"http://ultra.soholaunch.com\" target=\"_BLANK\" title=\"View Website\"><span><strong>View Website</strong></span></a></span>\n";
//$main_and_help_buts .= "</div>";

//$upnav['PAGE_EDITOR_LAYER'][]=$dddswitch;



//$upnav['PAGE_EDITOR_LAYER'][]= $main_and_help_buts;

//$upnav['PAGE_EDITOR_LAYER'][] = mkbutton( "mainmenu", "<b>".lang("Main Menu")."</b>", "nav_gray", "savePage('../../main_menu.php');" );



## PAGE EDITOR
##===============================================


$upnav['PAGE_EDITOR_LAYER'][]= "<div style=\"width:90px;\">&nbsp;</div>";

//$upnav['PAGE_EDITOR_LAYER'][] = "<a href=\"header.php\">ref</a>";


//$upnav['PAGE_EDITOR_LAYER'][] = mkbutton( "openpage", "Open Page", "nav_main", "savePage('../open_page.php');" );
$upnav['PAGE_EDITOR_LAYER'][] = mkbutton( "savepage", "Save Page", "nav_save", "savePage('page_editor.php');" );
//$upnav['PAGE_EDITOR_LAYER'][] = mkbutton( "viewsite", "View Site", "nav_main", "viewsite();" );
if($_SESSION['product_mode']!='trial'){	
	$upnav['PAGE_EDITOR_LAYER'][] = mkbutton( "saveas", "Copy Page", "nav_save", "save_as_layer();" );
}

$upnav['PAGE_EDITOR_LAYER'][] = mkbutton( "previewpage", "View Page", "nav_gray", "savePage('preview');" );



//$upnav['PAGE_EDITOR_LAYER'][]= "<div style=\"width:30px;\">&nbsp;</div>";


$upnav['PAGE_EDITOR_LAYER'][]= "<div style=\"width:6px;\">&nbsp;</div>";


$upnav['PAGE_EDITOR_LAYER'][]= "<div id=\"sidebarbuttndiv\" onClick=\"parent.body.togglesidebar();\" style=\"position:relative;display:none;\"><a class=\"blueButton\" href=\"javascript:void(0);\"><span id=\"sidebartoggletext\">Edit Sidebar</span></a></div>\n";

$upnav['PAGE_EDITOR_LAYER'][] = mkbutton( "prop", "Page Properties", "nav_main", "page_properties();");


$upnav['PAGE_EDITOR_LAYER'][]= "<div style=\"width:90px;\">&nbsp;</div>";


if ( $CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_SITE_FILES;", $CUR_USER_ACCESS)) {
   //$upnav['PAGE_EDITOR_LAYER'][] = mkbutton( "uploadfiles", "Upload Files", "nav_main", "savePage('../upload_files.php');" );
}


## PAGE EDITOR NO SAVE/PREVIEW
##===============================================
$upnav['PAGE_EDITOR_LAYER_NO_SAVE'][] = "";
//$upnav['PAGE_EDITOR_LAYER_NO_SAVE'][] = mkbutton( "openpage", "Open Page", "nav_main", "savePage('../open_page.php');" );
//if ( $CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_SITE_FILES;", $CUR_USER_ACCESS)) {
//   $upnav['PAGE_EDITOR_LAYER_NO_SAVE'][] = mkbutton( "uploadfiles", "Upload Files", "nav_main", "savePage('../upload_files.php');" );
//}


# EDIT_PAGES_LAYER
$upnav['EDIT_PAGES_LAYER'][] = mkbutton("openpage", "Open Page", "nav_main", "navigateOpen()");
$upnav['EDIT_PAGES_LAYER'][] = mkbutton( "viewsite", "View Site", "nav_main", "viewsite();" );
if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_TEMPLATES;", $CUR_USER_ACCESS) ) {
   $upnav['EDIT_PAGES_LAYER'][] = mkbutton("template_manager", "Template Manager", "nav_main", "parent.body.location.href='modules/site_templates.php';");
}
if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_MENUSYS;", $CUR_USER_ACCESS) ) {
   $upnav['EDIT_PAGES_LAYER'][] = mkbutton("automenu", "Menu Navigation", "nav_main", "parent.body.location.href='modules/auto_menu_system.php';");
}


# TEMPLATE_MANAGER
#===============================================
//$upnav['TEMPLATE_MANAGER'][] = mkbutton("upload_template", "Upload Template", "nav_main", "parent.body.location.href='modules/site_templates.php?tabnum=3';");
$upnav['TEMPLATE_MANAGER'][] = mkbutton( "openpage", "Open Page", "nav_main", "navigateOpen()" );
$upnav['TEMPLATE_MANAGER'][] = mkbutton("automenu", "Menu Navigation", "nav_main", "parent.body.location.href='modules/auto_menu_system.php';");
$upnav['TEMPLATE_MANAGER'][] = mkbutton("template_manager", "Template Manager", "nav_main", "parent.body.location.href='modules/site_templates.php';");


## WEBMASTER
##===============================================
//$upnav['WEBMASTER_MENU_LAYER'][] = mkbutton( "software_updates", "Software Updates", "nav_main", "cartdo('aupdate');" );
$upnav['WEBMASTER_MENU_LAYER'][] = mkbutton( "openpage", "Open Page", "nav_main", "navigateOpen()" );
$upnav['WEBMASTER_MENU_LAYER'][] = mkbutton( "viewsite", "View Site", "nav_main", "viewsite();" );
if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_WEBMASTER;", $CUR_USER_ACCESS)) {
   $upnav['WEBMASTER_MENU_LAYER'][] = mkbutton( "webmaster", "Webmaster", "nav_main", "cartdo('userprefs');" );
}


## SHOPPING CART
##===============================================
$upnav['CART_MENU_LAYER'][] = mkbutton( "shoppingcart", "Shopping Cart Menu", "nav_main", "cartdo('shopping_cart');" );


## CALENDAR_MENU_LAYER
##===============================================
$upnav['CALENDAR_MENU_LAYER'][] = mkbutton( "calmenu", "Calendar Menu", "nav_main", "cartdo('calendar');" );


## NEWSLETTER
##===============================================
$upnav['NEWSLETTER_LAYER'][] = mkbutton("newsmenu", "eNewsletter Menu", "nav_main", "cartdo('newsletter');");


## DATABASE_LAYER
##===============================================
$upnav['DATABASE_LAYER'][] = mkbutton("dbmenu", "Database Menu", "nav_main", "cartdo('database');");


# SECURE_USERS_LAYER
#===============================================
$upnav['SECURE_USERS_LAYER'][] = mkbutton("securemenu", "Secure Users Menu", "nav_main", "cartdo('secure_users');");


/*---------------------------------------------------------------------------------------------------------*
 ___  _              _         ___        _    _
| _ \| | _  _  __ _ (_) _ _   | _ ) _  _ | |_ | |_  ___  _ _   ___
|  _/| || || |/ _` || || ' \  | _ \| || ||  _||  _|/ _ \| ' \ (_-<
|_|  |_| \_,_|\__, ||_||_||_| |___/ \_,_| \__| \__|\___/|_||_|/__/
              |___/

# Build and output button sets defined by plugin modules, if any
# Example button entry in install_manifest..
#    $data['button1']['button_text'] = "My Button"
#    $data['button1']['button_onclick'] = "base64_encode(dosomething();)"
#    $data['button2']['button_text'] = "My Other Button"
#    $data['button2']['button_onclick'] = base64_encode("parent.body.location.href='checkoutmysinglequotes.php';")
/*---------------------------------------------------------------------------------------------------------*/
$plugin = special_hook("header_nav_buttons");

# Loop through each plugin that utilizes this hook
for ( $p = 0; $p < count($plugin); $p++ ) {

   # Loop through each button defined for this plugin
   for ( $b = 1; $b < count($plugin[$p]); $b++ ) {
      $button_id = str_replace(" ", "", $plugin[$p]['button'.$b]['button_text']);
      $div_id = $plugin[$p]['plugin_folder'];
      $onclick = base64_decode($plugin[$p]['button'.$b]['button_onclick']);
      $upnav[$div_id][] = mkbutton($button_id, $plugin[$p]['button'.$b]['button_text'], "nav_main", $onclick);
   }
}


/*---------------------------------------------------------------------------------------------------------*
 _  _            _
| || | ___  ___ | |__
| __ |/ _ \/ _ \| / /
|_||_|\___/\___/|_\_\
/*---------------------------------------------------------------------------------------------------------*/
eval(hook("header.php:menu_button_array"));

/*---------------------------------------------------------------------------------------------------------*
 ____          _  _      _     _    _  _______  __  __  _
|  _ \        (_)| |    | |   | |  | ||__   __||  \/  || |
| |_) | _   _  _ | |  __| |   | |__| |   | |   | \  / || |
|  _ < | | | || || | / _` |   |  __  |   | |   | |\/| || |
| |_) || |_| || || || (_| |   | |  | |   | |   | |  | || |____
|____/  \__,_||_||_| \__,_|   |_|  |_|   |_|   |_|  |_||______|
/*---------------------------------------------------------------------------------------------------------*/
# Will contain comma-separated list of menu button sets for use when building menu flip javascript
$button_sets = "";

/// Loop through button arrays and output divs and tables
###=========================================================================================
foreach ( $upnav as $divid=>$buttons ) {

   # Add div id of this button set to list used to build menu flip javascript
   $button_sets .= "\"".$divid."\",";

   echo "\n\n\n";
   echo "<!------------------------".$divid."------------------------>\n";
   echo "<div id=\"".$divid."\" class=\"upper_navbar\" style=\"margin-top: 0px; vertical-align: top;width:100%;overflow:hidden;;border-bottom:0px!important;\">\n";
   echo " <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
   echo "  <tr>\n";
   echo "   <td align=\"left\" valign=\"top\" style=\"padding-top: 0px;\">\n";
   echo "    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"upper_navbar\" align=\"center\" style=\"align:center;border-bottom:0px!important;\">\n";
   echo "     <tr>\n";


   // Place Main Menu, Logout, and Help [?] as standards except in Page Editor
   //--------------------------------------------------------------------------------
   if ( $divid != "PAGE_EDITOR_LAYER" && $divid != "PAGE_EDITOR_LAYER_NO_SAVE" ) {
      # Main Menu
      echo "      <td align=\"center\" valign=\"top\">\n";
      echo "       ".mkbutton( "mainmenu", "<b>".lang("Main Menu")."</b>", "nav_gray", "navigateHome();" )."\n";
      echo "      </td>\n";

      # Logout
      echo "      <td align=\"center\" valign=\"top\">\n";
      echo "       ".mkbutton( "logout", "Logout", "nav_logout", "logout();" )."\n";
      echo "      </td>\n";

      # Help Docs [?]
      echo "      <td align=\"center\" valign=\"top\">\n";
      echo "		 <img src=\"includes/display_elements/graphics/help_center_icon-off.gif\" onClick=\"showHelp();\" onMouseOver=\"this.src='includes/display_elements/graphics/help_center_icon-on.gif';\" onMouseOut=\"this.src='includes/display_elements/graphics/help_center_icon-off.gif';\" width=\"19\" height=\"17\" alt=\"Help Center\" style=\"cursor: pointer;\">\n";
      echo "      </td>\n";

   } elseif ( $divid == "PAGE_EDITOR_LAYER_NO_SAVE" ) {           // When editing text in page editor
      # Main Menu
      echo "      <td align=\"center\" valign=\"middle\" style=\"font-size: 17px; font-weight: bold; color: #000000; padding-right:310px;\">\n";
//      echo "       Editing Page Content...\n";
      echo "      </td>\n";

   } elseif ( $divid != "PAGE_EDITOR_LAYER" ) {
      # Main Menu
      echo "      <td align=\"center\" valign=\"top\">\n";
      echo "       ".mkbutton( "mainmenu", "<b>".lang("Main Menu")."</b>", "nav_gray", "savePage('../../main_menu.php');" )."\n";
      echo "      </td>\n";
      

      
   }


   // Loop through button array and output table cells
   //===============================================================
   foreach ( $buttons as $nav=>$bttn ) {
      if(strlen($bttn)>1){
         echo "      <td align=\"center\" valign=\"middle\">\n";
         echo "       ".$bttn."\n";
         echo "      </td>\n";
      }
   }


if ( $divid != "PAGE_EDITOR_LAYER_NO_SAVE" && $divid != "PAGE_EDITOR_LAYER") {
      echo "      <td align=\"center\" valign=\"baseline\" style=\"padding-top:3px;\">\n";


	if($_SESSION['help_link']!=''){				
		echo "	  <a onclick=\"openHelp('".$_SESSION['help_link']."');\" href=\"javascript:void(0);\" class=\"help\" style=\"vertical-align:baseline;padding:3px 15px 6px 25px; text-decoration:none;line-height:13px;width:25px;height:13px;font-family:Verdana,Geneva,sans-serif;font-size:11px;color:#ECECEC;background:url('".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/includes/images/hlp.png') no-repeat; \">Help</a>";
	} else {
		echo "	  <a onclick=\"openHelp('".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/modules/help_center/help_center.php?man=Page_Editor.php');\" href=\"javascript:void(0);\" class=\"help\" style=\"vertical-align:baseline;padding:3px 15px 6px 25px; text-decoration:none;line-height:13px;width:25px;height:13px;font-family:Verdana,Geneva,sans-serif;font-size:11px;color:#ECECEC;background:url('".httpvar().$_SESSION['docroot_url']."/sohoadmin/program/includes/images/hlp.png') no-repeat; \">Help</a>";
	}	 
	 
	 echo "      </td>\n";
	
}



   echo "     </tr>\n";
   echo "    </table>\n";
   echo "   </td>\n";
   echo "  </tr>\n";
   echo " </table>\n";
   echo "</div>\n";
   echo "\n\n\n";

} // End for each menu div layer
/*****************************************************************/


# Hidden div layer to preload button rollover images
echo "<div id=\"preload_btns\" style=\"display: none; z-index: -10;\">\n";
$preload_nav = array( 'warn', 'save', 'main', 'logout' );
$img_dir = "includes/display_elements/graphics/";
foreach ( $preload_nav as $btn ) {
   $imgOff = $img_dir."btn-nav_".$btn."-off.jpg";
   $imgOn = $img_dir."btn-nav_".$btn."-on.jpg";

   echo " <img src=\"".$imgOff."\" height=\"1\" width=\"1\" border=\"0\">\n";
   echo " <img src=\"".$imgOn."\" height=\"1\" width=\"1\" border=\"0\">\n";

}
echo "</div>\n";

?>

<script type="text/javascript">
// Menu flip function
function flip_header_nav(divid) {
   var menusets = new Array(<? echo substr($button_sets, 0, -1); ?>);
   var setcount = menusets.length;
   //alert("Flipping menu to ["+divid+"]");

   // Show requested menu set and hide all other sets
   for ( m=0;m<=setcount;m++ ) {
      if ( menusets[m] == divid ) {
         parent.body.show_hide_layer(menusets[m]+'?header','','show');

      } else {
         parent.body.show_hide_layer(menusets[m]+'?header','','hide');
      }
   }
}
flip_header_nav('PAGE_EDITOR_LAYER');
</script>
</BODY>
</HTML>