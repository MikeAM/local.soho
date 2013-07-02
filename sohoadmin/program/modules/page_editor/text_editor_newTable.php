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
error_reporting(E_PARSE);

?>

<HTML>
<HEAD>
<TITLE>Insert New Table</TITLE>

<link rel="stylesheet" href="soholaunch.css">

<STYLE>
	SELECT {font:7pt Verdana;background:#FFFFFF;}
	.txtbox {font:7pt Verdana;background:#FFFFFF}
	.toolbar {margin-bottom:3pt;height:28;overflow:hidden;background:white;border:0px none solid}
	.mode LABEL {font:8pt Arial}
	.mode .current {font:bold 8pt Arial;color:darkblue}
	.heading {color:navy;background:#FFFFFF}
	.tblEdit { BORDER-RIGHT: black 1px dashed; BORDER-TOP: black 1px dashed; BORDER-LEFT: black 1px dashed; BORDER-BOTTOM: black 1px dashed; }
</STYLE>

<SCRIPT>

	// Center New Window on Screen
	// ----------------------------------------
	var width = (screen.width/2) - (600/2);
	var height = (screen.height/2) - (40/2);
	window.moveTo(width,height);

	// Place Text Editor Window on top
	// ----------------------------------------
	window.focus();
	
	
	//This variable needs to have global scope for the callColorDlg function to persist the chosen color
	var sInitColor = null;
	function callColorDlg(forWhat){
		//if sInitColor is null, the color dialog box has not yet been called
		if (sInitColor == null) 
			var sColor = dlgHelper.ChooseColorDlg();
		else
			//call the dialog box and initialize the color to the color previously chosen
			var sColor = dlgHelper.ChooseColorDlg(sInitColor);
			//change the return value from a decimal value to a hex value and make sure the value has 6
			//digits to represent the RRGGBB schema required by the color table
			sColor = sColor.toString(16);
		if (sColor.length < 6) {
			var sTempString = "000000".substring(0,6-sColor.length);
			sColor = sTempString.concat(sColor);
		}
		
		if (forWhat == "txt") {
			format('forecolor', sColor)
		}
		
		if (forWhat == "tbl") {
			TBLBACKDISPLAY.style.background = sColor;
			TBLBACK.value = sColor;
		}
		
		if (forWhat == "tblb") {
			TBLBORDERDISPLAY.style.background = sColor;
			TBLBORDERCLR.value = sColor;
		}
	
		//set the initialization color to the color chosen
		sInitColor = sColor;
		
	 }	
	 
	
	function placeTable() {

		var oControlRange = window.opener.idEdit.document.selection.createRange();
		
		var sText = "";
		var tblColumns = TBLCOLS.value;
		var tblRows = TBLROWS.value;
		var tblWidth = TBLWIDTH.value;
		var tblBackground = TBLBACK.value;
		var tblPadding = TBLPAD.value;
		var tblSpacing = TBLSPACE.value;
		var tblBorder = TBLBORDER.value;
		var tblBorderColor = TBLBORDERCLR.value;
	
		var displayBorder = tblBorder;
		if (tblBorder < 1) { tblBorder = 1; }
		
			// Assign Unique ID tag to this object	
			d = new Date(); 
			RandNum = "oTBL";
			RandNum += d.getUTCHours(); 
			RandNum += d.getUTCMinutes(); 
			RandNum += d.getUTCSeconds(); 
			RandNum += d.getUTCMilliseconds();
			RandNum = RandNum.toString();
		
		sText = "<TABLE ID="+RandNum+" BID='border:"+displayBorder+"px solid #"+tblBorderColor+";background:#"+tblBackground+";' CELLPADDING="+tblPadding+" CELLSPACING="+tblSpacing;
		sText += " WIDTH="+tblWidth+" STYLE='border:"+tblBorder+"px solid #"+tblBorderColor+";background:#"+tblBackground+"'>";
	
		for (i=1; i<=tblRows; i++) { 
			sText += "<TR>";
			for (x=1; x<=tblColumns; x++) { 
				sText += "<td align=left valign=top STYLE='border: 1px dashed BLACK'>&nbsp;</td>"; 
			}
			sText += "</TR>";
		}
			
		sText += "</TABLE>";
		
		oControlRange.pasteHTML(sText);		
		self.close();
	
} // End Place New Table

</SCRIPT>

</HEAD>

<BODY STYLE="margin:5pt;padding:0pt;cursor:default;background:#ECE9D8;">
	<TABLE class=toolbar CELLSPACING=0 CELLPADDING=0 STYLE="background:#ECE9D8;height:65px" WIDTH="100%">
		<TR>
		<TD COLSPAN=2 ALIGN=LEFT VALIGN=TOP CLASS="text">
		<B>Enter the required information and click "Ok" to insert a table within the text area.<BR>
		Click the "Cancel" Button to close this window.</B><BR>
		</TD></TR><TR>
		<TD ALIGN=RIGHT VALIGN=MIDDLE NOWRAP>
			<TABLE BORDER=0 CELLPADDING=3 CELLSPACING=0 ALIGN=CENTER>
			<TR>
			<TD ALIGN=RIGHT VALIGN=MIDDLE CLASS="text">
				Columns: <input type=text name=TBLCOLS size=5 class="txtbox" VALUE="2"> 
			</TD><TD ALIGN=RIGHT VALIGN=MIDDLE CLASS="text">	
				Rows: <input type=text name=TBLROWS size=5 class="txtbox" VALUE="2"> 
			</TD><TD ALIGN=RIGHT VALIGN=MIDDLE CLASS="text">
				Width: <input type=text name=TBLWIDTH size=5 class="txtbox" VALUE="100%"> 
			</TD><TD ALIGN=RIGHT VALIGN=TOP CLASS="text">
				Background Color: <SPAN id=TBLBACKDISPLAY ONCLICK="callColorDlg('tbl');" STYLE='width:20px;height:20px;border:1px solid black;background:white;cursor:hand;'></SPAN>
				<input type=hidden name=TBLBACK value="FFFFFF">
			</TD>
			</TR><TR>
			<TD ALIGN=RIGHT VALIGN=MIDDLE CLASS="text">
				Padding: <input type=text name=TBLPAD size=5 class="txtbox" VALUE="2"> 
			</TD><TD ALIGN=RIGHT VALIGN=MIDDLE CLASS="text">	
				Spacing: <input type=text name=TBLSPACE size=5 class="txtbox" VALUE="0"> 
			</TD><TD ALIGN=RIGHT VALIGN=MIDDLE CLASS="text">
				Border: <input type=text name=TBLBORDER size=5 class="txtbox" VALUE="0"> 
			</TD><TD ALIGN=RIGHT VALIGN=MIDDLE CLASS="text">
				Border Color: <SPAN id=TBLBORDERDISPLAY ONCLICK="callColorDlg('tblb');" STYLE='width:20px;height:20px;border:1px solid black;background:white;cursor:hand;'></SPAN>
				<input type=hidden name=TBLBORDERCLR value="FFFFFF">
			</TD></TR></TABLE>

		</TD><TD ALIGN=LEFT VALIGN=MIDDLE NOWRAP>

			<input type=button value="Ok" style='width:75px;' onclick="placeTable();" class="FormLt1">
			<input type=button value="Cancel" style='width:75px;' onclick="self.close();" class="FormLt1">

		</TD>
		</TR></TABLE>

	<OBJECT id=dlgHelper CLASSID="clsid:3050f819-98b5-11cf-bb82-00aa00bdce0b" width="0px" height="0px"></OBJECT>   

</body>