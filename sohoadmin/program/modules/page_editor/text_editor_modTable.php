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
<TITLE>Modify Table Properties</TITLE>

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
	 
	 function setValues() {
	 
		var oControlRange = window.opener.idEdit.document.selection.createRange();
		
		tblPadding = oControlRange(0).cellPadding;
		tblSpacing = oControlRange(0).cellSpacing;
		tblWidth = oControlRange(0).width;
		bid = oControlRange(0).BID;

		var tmp = bid.replace(/border:/gi,"");
		var tmp = tmp.replace(/px solid/gi,"");
		var tBor = tmp.split(" ");
		tblBorder = tBor[0];
		
		var tmpColors = tBor[1];
		var tmpColors = tmpColors.replace(/background:/gi,"");
		var tCol = tmpColors.split(";");
			
		TBLWIDTH.value = tblWidth;
		TBLPAD.value = tblPadding;
		TBLSPACE.value = tblSpacing;
		TBLBORDER.value = tblBorder;
		
		TBLBACKDISPLAY.style.background = tCol[1];
		TBLBACK.value = tCol[1];
		TBLBORDERDISPLAY.style.background = tCol[0];
		TBLBORDERCLR.value = tCol[0];
		
		


	}
		
	window.onload = setValues;
	
	function updateTable() {

		var oControlRange = window.opener.idEdit.document.selection.createRange();

		oControlRange(0).removeAttribute('STYLE',0);
		
		var tblWidth = TBLWIDTH.value;
		var tblBackground = TBLBACK.value;
		var tblPadding = TBLPAD.value;
		var tblSpacing = TBLSPACE.value;
		var tblBorder = TBLBORDER.value;
		var tblBorderColor = TBLBORDERCLR.value;

		var displayBorder = tblBorder;
		if (tblBorder < 1) { tblBorder = 1; }
		
		tblBorderColor = tblBorderColor.replace(/#/gi, "");
		tblBackground = tblBackground.replace(/#/gi, "");
		
		var bidUpdate = "border:"+displayBorder+"px solid #"+tblBorderColor+";background:#"+tblBackground+";";
		
		var updateTmp = "BORDER-RIGHT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BACKGROUND: #c0c0c0; BORDER-LEFT: #000000 1px solid; BORDER-BOTTOM: #000000 1px solid";
		var updateTmp = updateTmp.replace(/000000/gi, tblBorderColor);
		var updateTmp = updateTmp.replace(/c0c0c0/gi, tblBackground);

			oControlRange(0).cellPadding = tblPadding;
	        oControlRange(0).cellSpacing = tblSpacing;
			oControlRange(0).width = tblWidth;
			oControlRange(0).BID = bidUpdate;
			
	        oControlRange(0).setAttribute('STYLE',updateTmp);
			
			window.opener.setMode(false);
			window.opener.setMode(true);
		
		self.close();
	
} // End Place New Table

function insertRow() {
	
	var oControlRange = window.opener.idEdit.document.selection.createRange();
	var totalCells = oControlRange(0).cells.length;
	var totalRows = oControlRange(0).rows.length;
	var numCells = totalCells/totalRows;
	oControlRange(0).insertRow();
	for (i=1;i<=numCells;i++) {
		eval("newCell = oControlRange(0).rows("+totalRows+").insertCell();");
		newCell.setAttribute('STYLE','BORDER-RIGHT: black 1px dashed; BORDER-TOP: black 1px dashed; BORDER-LEFT: black 1px dashed; BORDER-BOTTOM: black 1px dashed');
	}
	window.opener.setMode(false);
	window.opener.setMode(true);
	self.close();
} // End Insert Row Function

</SCRIPT>

</HEAD>

<BODY STYLE="margin:5pt;padding:0pt;cursor:default;background:#ECE9D8;">
	<TABLE class=toolbar CELLSPACING=0 CELLPADDING=0 STYLE="background:#ECE9D8;height:65px" WIDTH="100%">
		<TR>
		<TD COLSPAN=2 ALIGN=LEFT VALIGN=TOP CLASS="text">
		<B>Enter the required information and click "Ok" to modify the selected table properties.<BR>
		Click the "Cancel" Button to close this window.</B><BR>
		</TD></TR><TR>
		<TD ALIGN=RIGHT VALIGN=MIDDLE NOWRAP>
			<TABLE BORDER=0 CELLPADDING=3 CELLSPACING=0 ALIGN=CENTER>
			<TR>
			<TD ALIGN=RIGHT VALIGN=MIDDLE CLASS="text" COLSPAN=2>
				<input type=button value="Insert New Row" style='width:125px;' onclick="insertRow();" class="FormLt1">
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

			<input type=button value="Ok" style='width:75px;' onclick="updateTable();" class="FormLt1">
			<input type=button value="Cancel" style='width:75px;' onclick="self.close();" class="FormLt1">

		</TD>
		</TR></TABLE>

	<OBJECT id=dlgHelper CLASSID="clsid:3050f819-98b5-11cf-bb82-00aa00bdce0b" width="0px" height="0px"></OBJECT>   

</body>