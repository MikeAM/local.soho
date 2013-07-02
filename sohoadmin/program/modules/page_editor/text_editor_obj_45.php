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
error_reporting(0);
require_once("../../includes/product_gui.php");

track_vars;

?>

<link rel="stylesheet" href="soholaunch.css">

	<STYLE>
	SELECT {font:7pt Verdana;background:#FFFFFF;}
	.txtbox {font:7pt Verdana;background:#FFFFFF}
	.toolbar {margin-bottom:3pt;height:28;overflow:hidden;background:white;border:0px none solid}
	.mode LABEL {font:8pt Arial;color:black;background:#ECE9D8;padding:2px;}
	.mode .current {font:bold 8pt Arial;color:white;background:#999999;padding:2px;}
	.heading {color:navy;background:#FFFFFF}
	.tblEdit { BORDER-RIGHT: black 1px dashed; BORDER-TOP: black 1px dashed; BORDER-LEFT: black 1px dashed; BORDER-BOTTOM: black 1px dashed; }
	</STYLE>

	<SCRIPT>

	// -----------------------------------------------------------------------
	// Establish Global Variables
	// -----------------------------------------------------------------------	
	var code;
	var bLoad=false;
	var sInitColor = null;
	var public_description = new Editor;
	
	
	// -----------------------------------------------------------------------
	// Kill any rogue javascript errors that may occur
	// event working functions tend to crash sometimes
	// -----------------------------------------------------------------------	
	function killErrors() { 
		return true; 
	} 
	window.onerror = killErrors;
	
	
	// -----------------------------------------------------------------------
	// Initialize Editor Object
	// -----------------------------------------------------------------------
	function Editor() {
		this.put_html=put_html;
		this.get_html=get_html;
		// this.testHTML=testHTML
		this.bReady = false;
	}


	// -----------------------------------------------------------------------
	// Get and Put routines for switching between HTML and Design Views
	// -----------------------------------------------------------------------
	function get_html() {
		if (bMode) {
			cleanupHTML();
			return idEdit.document.body.innerHTML;
		} else {
			return idEdit.document.body.innerText;
		}
	} // End Func
	
	function put_html(sVal) {
		if (bMode) { 
			idEdit.document.body.innerHTML=sVal;
		} else { 
			idEdit.document.body.innerText=sVal;
		}
	} // End Func
	
	
	// -----------------------------------------------------------------------
	// Read HTML for display (Edit View)
	// -----------------------------------------------------------------------
	function cleanupHTML() {
  		bodyTags=idEdit.document.body.all, i
  		for (i=bodyTags.tags("FONT").length-1;i >= 0;i--) {
			if (bodyTags.tags("FONT")[i].style.backgroundColor="#ffffff") {
				bodyTags.tags("FONT")[i].style.backgroundColor="";
			}
			if (bodyTags.tags("FONT")[i].outerHTML.substring(0,6)=="<FONT>") {
				bodyTags.tags("FONT")[i].outerHTML=bodyTags.tags("FONT")[i].innerHTML;
			}
		} // End For
	} // End Func


	// -----------------------------------------------------------------------
	// Strip "pasted" word document HTML/XML and insert into text editor
	// -----------------------------------------------------------------------	
	function doCleanWord() {
		if (!bMode) {
			displayError();
			return
		}
		idEdit.focus();
		var oControlRange = idEdit.document.selection.createRange();
		if(oControlRange.parentElement) {
			TempArea = document.getElementById("myTempArea");
			TempArea.focus();
			TempArea.document.execCommand("SelectAll");
			TempArea.document.execCommand("Paste");
			var wordCode = TempArea.innerHTML;
			oControlRange.pasteHTML(doClean(wordCode));
		}
	} // End Func
	
	// Actually clean the word document code structure here
	
	function doClean(code) {    
		// removes all Class attributes on a tag eg. '<p class=asdasd>xxx</p>' returns '<p>xxx</p>'
		//code = code.replace(/<([\w]+) class=([^ |>]*)([^>]*)/gi, "<$1$3");
		// removes all style attributes eg. '<tag style="asd asdfa aasdfasdf" something else>' returns '<tag something else>'
		code = code.replace(/<([\w]+) style="([^"]*)"([^>]*)/gi, "<$1$3");
		// gets rid of all xml stuff... 
		code = code.replace(/<\\?\??xml[^>]>/gi, "");
	    // get rid of ugly colon tags <a:b> or </a:b>
		code = code.replace(/<\/?\w+:[^>]*>/gi, "");
		// removes all empty <p> tags
		code = code.replace(/<p([^>])*>(&nbsp;)*\s*<\/p>/gi,"");
		// removes all empty span tags
		code = code.replace(/<span([^>])*>(&nbsp;)*\s*<\/span>/gi,"");
		// removes non-GUI HTML formating always left by Word doc and replace with more "visually" pleasing style.
		code = code.replace(/<H1>/gi,"<B>");
		code = code.replace(/<\/H1>/gi,"</B>");
		code = code.replace(/<P>/gi,"<BR>");
		code = code.replace(/<\/P>/gi,"");
		code = code.replace(/<SPAN>/gi,"");
		code = code.replace(/<\/SPAN>/gi,"");
		return code
	}

	
	// -----------------------------------------------------------------------
	// Pull Current Text From Text Box in Page Editor and place in 
	// Text Editor
	// -----------------------------------------------------------------------
	<? 
	
	if ($BLOGON == 1) {
      $rez = mysql_query("SELECT * FROM blog_content WHERE PRIKEY = '$blogID'");
      $getBlog = mysql_fetch_array($rez);
      $showBlog = $getBlog['BLOG_TITLE'];
      //$showBlog = $getBlog['BLOG_DATA'];
		echo "var userText = ''\n";
		
	} else {
		echo "var userText = opener.top.frames.body.".$_GET['curtext'].".innerHTML;\n";
	}
		
	?>
	
	
	// -----------------------------------------------------------------------
	// Remove Soholaunch Formating for understandable HTML editing (If used)
	// -----------------------------------------------------------------------
	var re = new RegExp(" SOHOLINK=","gi"); 
    var userText = userText.replace(re, " href="); 
	var re = new RegExp("<SPAN id=SOHOTEXTSTART>","gi"); 
	var userText = userText.replace(re, " ");
	var re = new RegExp("</SPAN>","gi");
	var userText = userText.replace(re, " ");
  
  
  	// -----------------------------------------------------------------------
	// If this text area is "new", don't show the "click to edit" text
	// -----------------------------------------------------------------------
	if (userText.search("[click to edit text]") == 14) { var userText = ""; }


	// -----------------------------------------------------------------------
	// Set the text into editor now
	// -----------------------------------------------------------------------
	var sHeader="<BODY LINK=RED VLINK=RED ALINK=RED>"+userText+" ",bMode=true,sel=null
	
	
	// -----------------------------------------------------------------------
	// Create display Error Function for using toolbar formating within 
	// HTML edit mode
	// -----------------------------------------------------------------------
	function displayError() {
		alert("Toolbar not available in HTML view.");
		idEdit.focus();
	}	


	// -----------------------------------------------------------------------
	// Decimal to Hex Conversion Functions (For Color Select Box)
	// -----------------------------------------------------------------------

	// convert a single digit (0 - 16) into hex
	function enHex(aDigit) {
	    return("0123456789ABCDEF".substring(aDigit, aDigit+1))
	}

	// convert a hex digit into decimal
	function deHex(aDigit) {
	    return("0123456789ABCDEF".indexOf(aDigit))
	}

	// Convert a 24bit number to hex
	function toHex(n) {
	    return (enHex((0xf00000 & n) >> 20) +
	            enHex((0x0f0000 & n) >> 16) +
	            enHex((0x00f000 & n) >> 12) +
	            enHex((0x000f00 & n) >>  8) +
	            enHex((0x0000f0 & n) >>  4) +
	            enHex((0x00000f & n) >>  0))
	}

	// Convert a six character hex to decimal
	function toDecimal(hexNum) {
	    var tmp = ""+hexNum.toUpperCase()
	    while (tmp.length < 6) tmp = "0"+tmp
	    
	    return ((deHex(tmp.substring(0,1)) << 20) +
	            (deHex(tmp.substring(1,2)) << 16) + 
	            (deHex(tmp.substring(2,3)) << 12) +
	            (deHex(tmp.substring(3,4)) << 8) +
	            (deHex(tmp.substring(4,5)) << 4) +
	            (deHex(tmp.substring(5,6))))
	}

	// -----------------------------------------------------------------------
	// Build ActiveX Dialog Box for Color Selection
	// -----------------------------------------------------------------------
	function callColorDlg(forWhat){
	 	if (!bMode) {
	   		displayError();
	   		return;
		}
		//if sInitColor is null, the color dialog box has not yet been called
		if (sInitColor == null) {
			var sColor = dlgHelper.ChooseColorDlg();
		} else {
			//call the dialog box and initialize the color to the color previously chosen
			var sColor = dlgHelper.ChooseColorDlg(sInitColor);
			//change the return value from a decimal value to a hex value and make sure the value has 6
			//digits to represent the RRGGBB schema required by the color table
			sColor = sColor.toString(16);
		}	

		if (sColor.length < 6) {
			var sTempString = "000000".substring(0,6-sColor.length);
			sColor = sTempString.concat(sColor);
		}

		// Get Hex Format for instant update of text on screen
		var hexColor = toHex(sColor);

		// Set Color for Text/HR Selection		
		if (forWhat == "txt") {	
			format('forecolor', hexColor)
		} else {
			//set the initialization color to the color chosen
			sInitColor = sColor;
		}
		
	 } // End Func	


	// -----------------------------------------------------------------------
	// Dynamic processing for most toolbar options
	// -----------------------------------------------------------------------
	function format(what,opt) {
	if (!bMode) {
    	displayError();
    	return;
    }
 	if (bMode) {
   		if (opt==null) {
     		idEdit.document.execCommand(what);
		} else {
			idEdit.document.execCommand(what,"",opt);
			var s=idEdit.document.selection.createRange();
			var p=s.parentElement();
			idEdit.focus();
		}
	} 
 	var sel=null;
	} // End Func
	

	// -----------------------------------------------------------------------
	// Process Place Image toolbar Function
	// -----------------------------------------------------------------------
	function imageLayer() {
		if (!bMode) {
		   displayError();
		   return;
		}
		idEdit.focus();	
		var err = 0;
		var iType = 0;	
		if (idEdit.document.selection.type == "Control") {
			var iType = 1;
			var oControlRange = idEdit.document.selection.createRange();
			if (oControlRange(0).tagName.toUpperCase() == "TABLE") {
				alert("You can not put an image in place of a table.");
				var err = 1;
			}
		}
		if (err == 0 && iType == 0) {
			window.open('text_editor_newImage.php','','width=600,height=50,scrollbars=no,resizable=no,titlebar=0');
		}
		if (err == 0 && iType == 1) {
			window.open('text_editor_modImage.php','','width=600,height=50,scrollbars=no,resizable=no,titlebar=0');
		}
	} // End Func


	// -----------------------------------------------------------------------
	// Process Table toolbar Function
	// -----------------------------------------------------------------------
	function tableLayer() {
		if (!bMode) {
		   displayError();
		   return;
		}
		idEdit.focus();	
		var err = 0;
		var eType = 0;	
		if (idEdit.document.selection.type == "Control") {
			var eType = 1;
			var oControlRange = idEdit.document.selection.createRange();
			if (oControlRange(0).tagName.toUpperCase() == "IMG") {
				alert("You can not create a table in place of an image.");
				var err = 1;
			}
		}
		if (err == 0 && eType == 1) {				
			window.open('text_editor_modTable.php','','width=600,height=50,scrollbars=no,resizable=no,titlebar=0');
		}	
		if (err == 0 && eType == 0) {
			window.open('text_editor_newTable.php','','width=600,height=50,scrollbars=no,resizable=no,titlebar=0');
		}		
	} // End Func


	// -----------------------------------------------------------------------
	// Process Create Link Function
	// -----------------------------------------------------------------------
	function createLink() {
		if (!bMode) {
			displayError();
			return;
		} 
		idEdit.focus();
		var eType = 0;
		var err = 0;	
		if (idEdit.document.selection.type == "Control") {
		var eType = 1;
			var oControlRange = idEdit.document.selection.createRange();
			if (oControlRange(0).tagName.toUpperCase() == "TABLE") {
				alert("You can not link a table.");
				var err = 1;
			}
		}
		window.open('text_editor_link.php','','width=600,height=50,scrollbars=no,resizable=no,titlebar=0');
	} // End Func
	

	// -----------------------------------------------------------------------
	// Switch Between Edit View and HTML View
	// -----------------------------------------------------------------------
	function setMode(bNewMode) {
		if (bNewMode!=bMode) {
			if (bNewMode) {
				var sContents=idEdit.document.body.innerText ;
				idEdit.document.open();
				idEdit.document.write(sHeader);
				idEdit.document.close();
				idEdit.document.body.innerHTML=sContents;
			} else {
			cleanupHTML()
			var sContents=idEdit.document.body.innerHTML;
			idEdit.document.open();
			idEdit.document.write("<BODY style=\"font:9pt Verdana;\">");
			idEdit.document.close();

				var re = new RegExp(">","gi");
				var sContents = sContents.replace(re, ">\n");

				var re = new RegExp("<table","gi");
				var sContents = sContents.replace(re, "\n\n\n\n\n\n\n\n\n<table");

			idEdit.document.body.innerText=sContents;
			}
		bMode=bNewMode
		for (var i=0;i<htmlOnly.children.length;i++)
			htmlOnly.children[i].disabled=(!bMode);
		}
		modeA.className=bMode?"current":"";modeB.className=bMode?"":"current";
		idEdit.focus();
	} // End Func
	

	// -----------------------------------------------------------------------
	// Inititalization functions (Start Object)
	// -----------------------------------------------------------------------	
	function initEditor(bWhichEditor) {
		if (bWhichEditor) {
	         idEdit = EditCtrl.DOM.parentWindow;
		} else {
			EditCtrl.document.designMode="On";
			idEdit = EditCtrl;
		}
		idEdit.document.open();
	    idEdit.document.write(sHeader);
		idEdit.document.close();
		external.raiseEvent("onready",window.event);
	} // End Func
	
	function doLoad() {
		idBox.style.visibility='';
		initEditor(false);	
		bLoad=true;
		idEdit.focus();
	}
	

</SCRIPT>

<SCRIPT FOR="EditCtrl" EVENT="DocumentComplete()">bLoad=true;</SCRIPT>


<BODY ONSELECTSTART="return false" ONLOAD="doLoad()" STYLE="margin:0pt;padding:0pt;cursor:default;background:#ECE9D8">
<div style="height:1px;width:1px;overflow : auto;" id="myTempArea" contentEditable></div>
<DIV ID=idBox STYLE="width:100%;background:#ECE9D8;visibility: hidden">


	<!-- ############################################################################## -->
	<!-- ###  Start Toolbar Header Section											### -->
	<!-- ############################################################################## -->

	<TABLE ID=tb1 class=toolbar ALIGN=CENTER CELLSPACING=2 CELLPADDING=0 STYLE="background:#ECE9D8;">
	<TR>
	
	<TD VALIGN=TOP ALIGN=RIGHT NOWRAP>
	
		<SCRIPT FOR="EditCtrl" EVENT="ShowContextMenu(xPos, yPos)"></SCRIPT>
		<SCRIPT>
		// ----------------------------------------------------------------------
		// Create Left Toolbar Options
		// ----------------------------------------------------------------------
		var buttons=new Array(24,23,23,23);
		var action=new Array("Undo","Redo","Copy","Paste");
		var tooltip=new Array("Undo","Redo","Copy","Paste");
		var left=0;
		var s="";
		
		for (var i=0;i<buttons.length;i++) {
			s+="<SPAN STYLE='position:relative;height:26;width: " + buttons[i] + "'><SPAN STYLE='position:absolute;margin:0px;padding:0px;height:26;background:#EFEFEF;top:0;left:0;width:" + (buttons[i]) + ";clip:rect(0 "+buttons[i]+" 25 "+0+");overflow:hidden'><IMG BORDER=0 SRC='soho_bar1.gif' STYLE='cursor:hand;position:absolute;top:0;left:-" + left + "' ALIGN=ABSMIDDLE VSPACE=0 HSPACE=0 WIDTH=117 HEIGHT=50";
			s+=" onmouseover='this.style.top=-25' onmouseout='this.style.top=0' ONCLICK=\"";
			if (action[i]=="Undo") {
				s+= "format('Undo');this.style.top=0\" ";
			}
			if (action[i]=="Redo") {
				s+= "format('Redo');this.style.top=0\" ";
			}
			if (action[i]=="Copy") {
				s+= "format('Copy');this.style.top=0\" ";
			}
			if (action[i]=="Paste") {
				s+= "doCleanWord();this.style.top=0\" ";
			}
			// if (action[i]=="wordPaste") {
			// 	s+= "doCleanWord();this.style.top=0\" ";
			// }		
			s+="TITLE=\"" + tooltip[i] + "\"";
			s+="></SPAN></SPAN>";
			left+=buttons[i];
		}
				
		document.write(s);
		
		</SCRIPT>
	
	</TD>
	<TD ALIGN=CENTER VALIGN=TOP NOWRAP ID=htmlOnly STYLE='padding: 3px;'>
		
		<SELECT ONCHANGE="format('fontname',this[this.selectedIndex].value);this.selectedIndex=0">
		<OPTION CLASS="heading" SELECTED>Font Name</option>
		<OPTION VALUE="geneva,arial,sans-serif">Arial</option>
		<OPTION VALUE="verdana,geneva,arial,sans-serif">Verdana</option>
		<OPTION VALUE="tahoma, arial, sans-serif">Tahoma</option>
		<OPTION VALUE="times,serif">Times</option>
		<OPTION VALUE="georgia, geneva, arial, sans-serif">Georgia</option>
		<OPTION VALUE="courier, monospace">Courier</option>
		<OPTION VALUE="Lucida Handwriting, lhandw, sans-serif">Courier</option>
		</SELECT>
		<SELECT ONCHANGE="format('fontSize',this[this.selectedIndex].text);this.selectedIndex=0">
		<OPTION CLASS=heading>Size<OPTION>1<OPTION>2<OPTION>3<OPTION>4<OPTION>5<OPTION>6<OPTION>7
		</SELECT>
		
	
	</TD><TD VALIGN=TOP ALIGN=LEFT NOWRAP>
	
		<DIV ID=tb2 class=toolbar STYLE="width:359px;background:#ECE9D8" ONSELECTSTART="return false" ONDRAGSTART="return false">
	
			<SCRIPT FOR="EditCtrl" EVENT="ShowContextMenu(xPos, yPos)"></SCRIPT>
			<SCRIPT>
			// ----------------------------------------------------------------------
			// Create Rightside Toolbar Options
			// ----------------------------------------------------------------------
	
			var buttons=new Array(24,23,23,23,4,23,23,23,4,23,23,23,23,4,23,23,23,23);
			var action=new Array("fontcolor","bold","italic","underline","","justifyleft","justifycenter","justifyright","","insertorderedlist","insertunorderedlist","outdent","indent","","createLink","InsertTable","InsertImage","InsertHorizontalRule");
			var tooltip=new Array("Font Color","Bold Text","Italic Text","Underline Text","","Left Justify","Center Justify","Right Justify","","Ordered List","Unordered List","Remove Indent","Indent","","Create Hyperlink","Insert/Modify Table","Insert/Modify Image","Horizontal Rule");
			var left=0;
			var s="";
			
			for (var i=0;i<buttons.length;i++) {
	
				s+="<SPAN STYLE='position:relative;height:26;width: " + buttons[i] + "'><SPAN STYLE='position:absolute;margin:0px;padding:0px;height:26;background:#EFEFEF;top:0;left:0;width:" + (buttons[i]) + ";clip:rect(0 "+buttons[i]+" 25 "+0+");overflow:hidden'><IMG BORDER=0 SRC='soho_bar.gif' STYLE='cursor:hand;position:absolute;top:0;left:-" + left + "' ALIGN=ABSMIDDLE VSPACE=0 HSPACE=0 WIDTH=359 HEIGHT=50";
				if (buttons[i]!=4) {
					s+=" onmouseover='this.style.top=-25' onmouseout='this.style.top=0' ONCLICK=\"";
					if (action[i]!="createLink" && action[i]!="InsertTable" && action[i]!="InsertImage" && action[i]!="fontcolor") {
						s+="format('" + action[i] + "');this.style.top=0\" ";
					} else {
						if (action[i]!="InsertTable" && action[i]!="InsertImage" && action[i]!="fontcolor") {
							s+="createLink('"+ tooltip[i] + "');this.style.top=0\" ";
						} else {
							if(action[i]=="fontcolor") { s+= "callColorDlg('txt');this.style.top=0\" "; }
							if(action[i]=="InsertTable") { s+="tableLayer();this.style.top=0\" "; }
							if(action[i]=="InsertImage") { s+="imageLayer();this.style.top=0\" "; }
						}
					} // End If[2]		
					s+="TITLE=\"" + tooltip[i] + "\"";
			   } // End If[1]
			   s+="></SPAN></SPAN>";
			   left+=buttons[i] ;
			   
	  	   } // End For Loop

		   document.write(s);
		   
		   </SCRIPT>
		   
		</DIV>
		   
	</TD></TR></TABLE>  

	<!-- ############################################################################## -->
	<!-- ###  End Tool Bar Header Section // Insert IFRAME Edit Window				  ### -->
	<!-- ############################################################################## -->
	
	<iframe contenteditable FRAMEBORDER=0 STYLE='border:1px solid black' WIDTH=100% NAME=EditCtrl></IFRAME>
	
	<!-- ############################################################################## -->
	<!-- ### Setup View Switch Buttons												### -->
	<!-- ############################################################################## -->
	
	<DIV ID=tb3 class=mode STYLE='background:#ECE9D8;padding:1px' ALIGN=RIGHT>
	<LABEL ONCLICK="setMode(true)" class=current FOR=mw ID=modeA STYLE='FONT-FACE:Arial;FONT-SIZE:7pt;cursor:hand;padding:2px;'>Edit View</LABEL>
	&nbsp;&nbsp;
	<LABEL ONCLICK="setMode(false)" FOR=mH ID=modeB STYLE='FONT-FACE:Arial;FONT-SIZE:7pt;cursor:hand;padding:2px;'>HTML View</LABEL>
	</DIV>
	</DIV>
	
	
	<SCRIPT>
		// Set Object Height within main editor window
		setTimeout("document.all.EditCtrl.style.height=document.body.offsetHeight-65",0)
	</SCRIPT>
	
	<!-- Insert Active X Color Dialog Object -->
	<OBJECT id=dlgHelper CLASSID="clsid:3050f819-98b5-11cf-bb82-00aa00bdce0b" width="0px" height="0px"></OBJECT>   