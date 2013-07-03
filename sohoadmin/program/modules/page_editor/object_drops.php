
// ------------------------------------------------------------------
// -- General functions
// ------------------------------------------------------------------

// Creates all useable portions of the drop process
//    -droppedItem div
//    -sets global current_editing_area
// Takes
//    -objName
//    -txtHead
//    -objHeight (optional)
// Returns
//    -objParent
function objTemplate(objName, txtHead, objHeight) {

   var obj_items = new Array();
   var TextHeader = "";
	if(!objHeight){
		objHeight = 120;
	}
   
	d = new Date();
	RandNum = objName;
	RandNum += d.getUTCHours();
	RandNum += d.getUTCMinutes();
	RandNum += d.getUTCSeconds();
	RandNum += d.getUTCMilliseconds();
	RandNum = RandNum.toString();
	

   obj_items[0] = RandNum
	
	// Is drop area empty?
	var dataTrue = dataData.search("pixel.gif");
	
	//alert(objHeight)
	
	if(txtHead){
		TextHeader = "<img src=\"images/text_header.gif\" width=\"199\" height=\"15\" border=\"0\" align=\"center\" vspace=\"0\" hspace=\"0\" style='cursor: move;'><BR CLEAR=ALL>";
	}
	var TableStart = "<div id=\""+RandNum+"\" class=\"droppedItem\" style=\"height: "+objHeight+"px;\">"+TextHeader;
   var TableEnd = "</div><!-- ~~~ -->";
   
	
	if (dataTrue > 0) {
	   obj_items[1] = TableStart+'##OBJ_DISPLAY##'+TableEnd;
	}else{
	   obj_items[1] = dataData+TableStart+'##OBJ_DISPLAY##'+TableEnd;
	}
	
	return obj_items;
}

// Load tinymce editor
function startEditor(editorID){   
	if(document.getElementById(editorID).innerHTML=='<blink>Click here to add content.</blink>'){
		document.getElementById(editorID).innerHTML='<blink> </blink>';
	}
   
	// Set global curtext
	current_editing_area = editorID
	show_hide_icons();
	
	// Flip header nav to PAGE_EDITOR_LAYER_NO_SAVE buttons
	parent.header.flip_header_nav('PAGE_EDITOR_LAYER_NO_SAVE');
	toggleEditor('tiny_editor');
}

// Account for old editor function calls
function newEdit(curId,textId){
   // Set global curtext
   current_editing_area = curId
   show_hide_icons();
   
   // Flip header nav to PAGE_EDITOR_LAYER_NO_SAVE buttons
   parent.header.flip_header_nav('PAGE_EDITOR_LAYER_NO_SAVE');
   toggleEditor('tiny_editor');
}

function textEdit(curId,textId){
   alert('setting')
   // Set global curtext
   current_editing_area = curId
   show_hide_icons();
   
   // Flip header nav to PAGE_EDITOR_LAYER_NO_SAVE buttons
   parent.header.flip_header_nav('PAGE_EDITOR_LAYER_NO_SAVE');
   toggleEditor('tiny_editor');
}
<?
# place_sitepal()
include_once("../sitepal/page_editor/props_dialog-javascript.php");
?>

// =========================================================
// =========== Text Area ===================================
// =========================================================

function loadEditor(mode) {
   var finalObj,RandNum;
   var tmplt = objTemplate('NEWOBJ', true);
   
   RandNum = tmplt[0];
   editorID = RandNum.replace("NEWOBJ", "EDITOBJ");
   
   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<div id="+editorID+" class=\"TXTCLASS\" align=\"left\" onclick=\"startEditor(this.id);\">&nbsp;</div>");
   
   document.getElementById(ColRowID).innerHTML= finalObj;
   setTimeout("checkRow(ColRowID)",1500)
   //checkRow(ColRowID);
   startEditor(editorID)
   
   
}

//function setHtml(curtext,cont){
//   document.getElementById(curtext).innerHTML=cont;
//   var RawCode = document.getElementById(curtext).innerHTML;
//   var billy = document.getElementById(curtext).innerHTML='<blink>'+RawCode+'</blink>';
//   disable_links()
//}
//
//function setHtmlfirst(cont){
//   document.getElementById(random).innerHTML= cont
//   disable_links()
//}


// =========================================================
// =========== My Images ===================================
// =========================================================

function getImageData() {
   
   var finalObj,RandNum;
   var tmplt = objTemplate('IMGOBJ', true, 120);
   
   RandNum = tmplt[0];
   imgID = RandNum.replace("IMGOBJ", "NEWIMGOBJ");
   
   disOne = $('oSel').selectedIndex;
	tImage = eval("$('oSel').options["+disOne+"].value");
   
   <? echo ("tImage = \"http://$this_ip/images/\" + tImage;\n"); ?>
   
   // finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<img src="+tImage+" border=1 class=tHead>");
   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<!-- ##IMAGE;"+tImage+";"+imgID+"## --><img src="+tImage+" border=0 id="+imgID+" class=tHead onClick=\"linkImage('"+imgID+"');\">");
   
   
   if (tImage != "NONE") {
      document.getElementById(ColRowID).innerHTML= finalObj;
   }
   
	//alert('checking rows..')
	checkRow(ColRowID)
}

// =========================================================

function OkImageData() {
   
   var finalObj,RandNum;
   var tmplt = objTemplate('IMGOBJ', true, 120);
   
   RandNum = tmplt[0];
   imgID = RandNum.replace("IMGOBJ", "NEWIMGOBJ");
   
   disOne = $('oSel').selectedIndex;
	tImage = eval("$('oSel').options["+disOne+"].value");
   
   <? echo ("tImage = \"http://$this_ip/images/\" + tImage;\n"); ?>
   
   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<!-- ##IMAGE;"+tImage+";"+imgID+"## --><img src="+tImage+" border=0 id="+imgID+" class=tHead onClick=\"linkImage('"+imgID+"');\">");
   
   if (tImage != "NONE") {
      document.getElementById(ColRowID).innerHTML= finalObj;
   }
   
	$('oSel').selectedIndex = 0;
	checkRow(ColRowID)
}

// =========================================================
// =========== documentS ===================================
// =========================================================

function OkWordData() {
   
   var finalObj,RandNum;
   var tmplt = objTemplate('WORDFILE', true, 50);
   
   RandNum = tmplt[0];
   docID = RandNum.replace("WORDFILE", "DOCOBJ");
   
	disOne = $('mswordname').selectedIndex;
	tWordFile = eval("$('mswordname').options["+disOne+"].value");
   
   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<div ID="+docID+"><img src='client/download_icon.gif' align=absmiddle vspace=0 hspace=2 border=0 width=20 height=19> <font face=Arial style='font-size: 8pt;'><U>"+tWordFile+"</U></font></div><!-- ##MSWORD;"+tWordFile+"## -->");
   
   if (tWordFile != "NONE") {
      document.getElementById(ColRowID).innerHTML= finalObj;
   }
   //alert('ok')
   
	$('mswordname').selectedIndex = 0;
	checkRow(ColRowID)

}

// =========================================================
// =========== Hit counter =================================
// =========================================================

function pageCounter() {
   
   var finalObj,RandNum;
   var tmplt = objTemplate('COUNTER', true, 50);
   
   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<img src='client/counter_tool.gif' width=90 height=20 border=0 alt=\"Hit Counter\"><!-- ##COUNTER## -->");
   document.getElementById(ColRowID).innerHTML= finalObj;
   
	setTimeout("checkRow(ColRowID)",1500)
}


// =========================================================
// =========== Auth Login ==================================
// =========================================================

function OkLoginData() {

   var finalObj,RandNum;
   var tmplt = objTemplate('AUTHOBJ', true, 80);

   RandNum = tmplt[0];
   authID = RandNum.replace("AUTHOBJ", "SECURELOGIN");

	tloginbutton = $('loginbutton').value;
	tloginbutton = tloginbutton.toString();

   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<button type=\"button\" class=\"blueButton\" id="+authID+"><span><span>"+tloginbutton+"</span></span></button><BR><BR><FONT FACE=ARIAL STYLE='FONT-SIZE: 7pt;'><b><?php echo lang("Forgotten your password?"); ?> <U><FONT COLOR=BLUE><?php echo lang("Click Here"); ?></FONT></U>.</b></font><!-- ##SECURELOGIN;"+tloginbutton+"## -->");

   if (tloginbutton != "") {
      document.getElementById(ColRowID).innerHTML= finalObj;
   }

	checkRow(ColRowID)

}


// =========================================================
// =========================================================
// ===============  New Cust Include  ======================
// =========================================================
// =========================================================

function loadNewCust(type) {

   document.getElementById('objectbar').style.display='none';
   show_hide_layer('objectbar','','hide','customlayer_new_custinc','','show');
   
}


function loadNewCustom(filename) {
	var url = "../simple_editor.php?file="+filename;
	if ( $('simple_editor_container') ) {
		$('simple_editor_container').style.display = 'block';
	}
	show_hide_layer('objectbar','','hide','simple_editor_container','','show');
	$('simple_editor_container').src = url;
  ajaxDo('../simple_editor.php?file='+filename, 'simple_editor_container');
	//window.setTimeout("CodePress()",1200)
}



function saveCustomsimple(filename, real_content) {

	var url = "../simple_editor_save.php";
	var other = "file="+filename+"&realcontent="+real_content;
	if ( $('simple_editor_container') ) {
		$('simple_editor_container').style.display = 'block';
	}
	
	show_hide_layer('objectbar','','hide','simple_editor_container','','show');
	$('simple_editor_container').src = url;
  ajaxDocc(url, other, 'simple_editor_container_save');
	show_hide_layer('objectbar','','show','simple_editor_container','','hide');
	$('simple_editor_container').style.display = 'none';
	$('simple_editor_container_save').style.display = 'none';
}




// =========================================================
// =========== Custom Code =================================
// =========================================================


function OkCustomData() {

   var finalObj,RandNum;
   var tmplt = objTemplate('CUSTOMOBJ', true, 80);

   RandNum = tmplt[0];
   customID = RandNum.replace("CUSTOMOBJ", "NEWCUSTOMOBJ");

	disOne = $('customname').selectedIndex;
	tCustom = eval("$('customname').options["+disOne+"].value");


   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<div ID="+customID+" align=center><font style='font-family: Arial; font-size: 8pt;'><B>Custom Code Include:</B></font><BR>[ "+tCustom+" ]<br/><br/><button onClick=\"loadNewCustom('"+tCustom+"');\" misc=\"loadNewCustom('"+tCustom+"');\" type=\"button\" class=\"blueButton\"><span><span>Edit</span></span></button></div><!-- ##CUSTOMHTML;"+tCustom+"## -->");
//   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<div ID="+customID+" align=center><font style='font-family: Arial; font-size: 8pt;'><B>Custom Code Include:</B></font><BR>[ "+tCustom+" ]<br/><br/><a href=\"javascript:loadNewCustom('"+tCustom+"');\" onclick=\"loadNewCustom('"+tCustom+"');\" class=\"blueButton\"><span>Edit</span></a></div><!-- ##CUSTOMHTML;"+tCustom+"## -->");

   if (tCustom != "NONE") {
      document.getElementById(ColRowID).innerHTML= finalObj;
   }

	checkRow(ColRowID)

}


// =========================================================
// =========== Shopping Cart ===============================
// =========================================================

function OkCartSku() {
   var finalObj,RandNum;   
   if(arguments.length > 0){
      // Place cart search
      var tmplt = objTemplate('CARTSEARCHOBJ', true, 100);      
      finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<img src='client/cartsearch_tool.gif' width=199 height=71 border=0 class=tHead vspace=0 hspace=0><!-- ##CARTSEARCH## -->");      
      document.getElementById(ColRowID).innerHTML= finalObj;
   }else{
      // Place single sku
      var tmplt = objTemplate('SKUOBJ', true, 140);      
      RandNum = tmplt[0];
      skuID = RandNum.replace("SKUOBJ", "SKUPROMO");   
   	disOne = $('SINGLESKU').selectedIndex;
   	var tSingleSku2 = eval("$('SINGLESKU').options["+disOne+"].value");
   	var tSingleSku2 = tSingleSku2.toString();
	var cart_split=tSingleSku2.split("~~");
	var tSingleSku = cart_split[0];
	var tSinglePname = cart_split[1];
	if(cart_split[2]==''){
		var tSingleProdimg = "client/singlesku.gif";
	} else {
		var tSingleProdimg = "../../../../images/"+cart_split[2];
	}	
      finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<div ID="+skuID+" align=center><img class=tHead src="+tSingleProdimg+" width=199 height=176 border=1><BR clear=all><font face=Arial size=1><B>"+tSinglePname+"<br/>Par No. (sku): <U>"+tSingleSku+"</U></b></font></div><!-- ##SINGLESKU;"+tSingleSku+"## -->");      
      if (tSingleSku != "NONE") {
         document.getElementById(ColRowID).innerHTML= finalObj;
      }
      $('SINGLESKU').selectedIndex = 0;
   }
	checkRow(ColRowID)
}

// =========================================================
// =========== Social Media  ===============================
// =========================================================

function OkSocialMedia() {
	var finalObj,RandNum;
	var tmplt = objTemplate('SOCIALOBJ', true, 60);
	RandNum = tmplt[0];
	socialmediaID = RandNum.replace("SOCIALOBJ", "SocialMedia");
	var writethis = '';
	var socBase = '';
	var showsoccount = '';
	if(document.getElementById('sel_social_count').checked==true){		
		writethis += '##SOC_SHOWCOUNT##';
		
	}
	if(document.getElementById('sel_facebook').checked==true){
		writethis += '##SOC_FACEBOOK##';
		socBase += '<img src="images/soc_facebook.png">';
	}
	if(document.getElementById('sel_twitter').checked==true){
		writethis += '##SOC_TWITTER##';
		socBase += '<img src="images/soc_twitter.gif">';
	}
	if(document.getElementById('sel_google').checked==true){
		writethis += '##SOC_GOOGLE##';
		socBase += '<img src="images/soc_google.png">';
	}
	if(document.getElementById('sel_stumble').checked==true){
		writethis += '##SOC_STUMBLE##';
		socBase += '<img src="images/soc_stumble.png">';
	}
	//disOne = $('dbaseSelect').selectedIndex;
	//tdBase = eval("$('dbaseSelect').options["+disOne+"].value");

	finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<div id="+socialmediaID+"><div align=center valign=middle><font face=Arial size=1><B>"+socBase+"</B></font></div></tt></div><!--"+writethis+" -->");

	if (socBase != "") {
		document.getElementById(ColRowID).innerHTML= finalObj;
	}

	checkRow(ColRowID)
}



// =========================================================
// =========== Table Search ================================
// =========================================================

function okMembership() {
   var finalObj,RandNum;
   var tmplt = objTemplate('TBLSEARCHOBJ', true, 60);

   RandNum = tmplt[0];
   tblsearchID = RandNum.replace("TBLSEARCHOBJ", "MEMDATA");

	disOne = $('dbaseSelect').selectedIndex;
	tdBase = eval("$('dbaseSelect').options["+disOne+"].value");

   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<div id="+tblsearchID+"><div align=center valign=middle><font face=Arial size=1><B>Search: "+tdBase+"</B></font></div></tt></div><!-- ##MEMBERSHIP;"+tdBase+"## -->");

   if (tdBase != "NONE") {
      document.getElementById(ColRowID).innerHTML= finalObj;
   }

	checkRow(ColRowID)
}

// =========================================================
// =========== Sign-Up =====================================
// =========================================================

function OkNewsletterData() {
   var finalObj,RandNum;
   var tmplt = objTemplate('SIGNUPOBJ', true, 60);

   RandNum = tmplt[0];
   signupID = RandNum.replace("SIGNUPOBJ", "NEWSLETTER");

	tNewsCat = $('newsSel').options($('newsSel').selectedIndex).value;
	tNewsCat = tNewsCat.toString();
	
   tContestVar = "no";
   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<div ID="+signupID+" class=datetext><div align=center valign=middle><font face=Arial size=1><B>Category: "+tNewsCat+"</div></tt></div><!-- ##NEWSLETTER;"+tNewsCat+";"+tContestVar+"## -->");

   if (tNewsCat != "NONE") {
      document.getElementById(ColRowID).innerHTML= finalObj;
   }

  	$('newsSel').selectedIndex = 0;
	checkRow(ColRowID)
}

// =========================================================
// =========== Calendar ====================================
// =========================================================

function do_calendar() {
   
   var finalObj,RandNum;
   var tmplt = objTemplate('CALOBJ', true, 120);

   disOne = $('calcat').selectedIndex;
	var calcategory = eval("$('calcat').options["+disOne+"].value");
	calcategory = calcategory.toString();
	
   disOne2 = $('syscalcat').selectedIndex;
	var syscalcategory = eval("$('syscalcat').options["+disOne2+"].value");
	syscalcategory = syscalcategory.toString();
	
	CalCode = "<DIV ALIGN=CENTER STYLE='font-family: arial; font-size: 7pt; width: 199px;'>";

	if ($('caltypeW').checked) {
		var ctype = "W";
		CalCode = CalCode + "<B><FONT SIZE=2 FACE=VERDANA><U>THIS WEEK CALENDAR</U></FONT></B><BR><BR>When viewing the page, you will see";
		CalCode = CalCode + " events for the current week in the '<U>"+calcategory+"</U>'.<BR>(7 days; Sun-Sat).";
	}

	if ($('caltypeM').checked) {
		var ctype = "M";
		CalCode = CalCode + "<B><FONT SIZE=2 FACE=VERDANA><U>THIS MONTH CALENDAR</U></FONT></B><BR><BR>When viewing the page, you will see";
		CalCode = CalCode + " this month's events ONLY from the '<U>"+calcategory+"</U>' category, displayed in a monthly view display.<BR><BR>Please note: this will take up a<br>large majority of the page.";
	}

	if ($('caltypeS').checked) {
		var ctype = "SYS";
		CalCode = CalCode + "<B><FONT SIZE=2 FACE=VERDANA><U>CALENDAR SYSTEM OBJECT</U></FONT></B><BR><BR>When viewing the page, you will see";
		CalCode = CalCode + " the event calendar interface. All search options etc. will be available.<BR><BR>Please note: This should be on a page by itself for optimal operation.";
		if(syscalcategory != 'All'){
			ctype = "SINGLE";
			calcategory = syscalcategory;
		}
	}

	CalCode = CalCode + "</DIV>";
	
   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", CalCode+"<!-- ##CALENDAR;"+ctype+";"+calcategory+"## -->");

   document.getElementById(ColRowID).innerHTML= finalObj;

	checkRow(ColRowID)
   
}

// =========================================================
// =========== Directions ==================================
// =========================================================

function OkMapquestData() {
   var finalObj,RandNum;
   var tmplt = objTemplate('MAPOBJ', true, 80);

   RandNum = tmplt[0];
   mapID = RandNum.replace("MAPOBJ", "DIRECTIONS");

	tStreet = $('street').value;
	tCity = $('city').value;
	tState = $('state').value;
	tZip = $('zip').value;
	tCountry = $('country').value;

	tStreet = tStreet.toString();
	tCity = tCity.toString();
	tState = tState.toString();
	tZip = tZip.toString();
	tCountry = tCountry.toString();
	
	
	if ($('MAPLINKTOquest').checked) { var tMapLink = "MAPQUEST"; }
	if ($('MAPLINKTOyahoo').checked) { var tMapLink = "YAHOO"; }
	if ($('MAPLINKTOgoogle').checked) { var tMapLink = "GOOGLEMAPS"; }

	displayAddr = "<br><FONT COLOR=MAROON>("+tStreet+","+tCity+","+tState+","+tZip+","+tCountry+")</FONT>";
	
   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<button type=\"button\" class=\"blueButton\" id="+mapID+" style='cursor: hand;'><span><span>Get Directions</span></span></button><BR><FONT STYLE='font-family: Arial; font-size: 7pt; color: #999999;'>Courtesy of: "+tMapLink+"<SUP>TM</SUP>"+displayAddr+"</font><!-- ##MAPQUEST;"+tStreet+";"+tCity+";"+tState+";"+tZip+";"+tMapLink+";"+tCountry+"## -->");

   if (tStreet != "") {
      document.getElementById(ColRowID).innerHTML= finalObj;
   }

	checkRow(ColRowID)
}


// =========================================================
// =============== Date Stamp ==============================
// =========================================================

function OKdateStamp() {
   var finalObj,RandNum;
   var tmplt = objTemplate('DATEOBJ', true, 60);

   RandNum = tmplt[0];
   dateID = RandNum.replace("DATEOBJ", "STAMPOBJ");

	var months=new Array(13);
	months[1]="January";
	months[2]="February";
	months[3]="March";
	months[4]="April";
	months[5]="May";
	months[6]="June";
	months[7]="July";
	months[8]="August";
	months[9]="September";
	months[10]="October";
	months[11]="November";
	months[12]="December";
	var time=new Date();
	var lmonth=months[time.getMonth() + 1];
	var date=time.getDate();
	var year=time.getYear();
	if (year < 2000) year = year + 1900;

   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<div ID="+dateID+" align=center><font face=\"Verdana, Arial, Helvetica, Sans-serif\" style=\"font-size: 11px;\">"+lmonth+" "+date+", "+year+"</font></div><!-- ##DATESTAMP## -->");

   document.getElementById(ColRowID).innerHTML= finalObj;

	setTimeout("checkRow(ColRowID)",1500)
}

// =========================================================
// =============== Print Page ==============================
// =========================================================

function printButton() {
   var finalObj,RandNum;
   var tmplt = objTemplate('PRINTOBJ', true, 60);

finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<button type=\"button\" class=\"grayButton\"><span><span><?php echo lang("Printable Page"); ?></span></span></button><!-- ##PRINTTHIS## -->");

   document.getElementById(ColRowID).innerHTML= finalObj;

	setTimeout("checkRow(ColRowID)",1500)
}

// =========================================================
// =============== Email Friend ============================
// =========================================================

function emailfriend() {
   var finalObj,RandNum;
   var tmplt = objTemplate('EMAILOBJ', true, 60);

   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<font style='font-family: Arial; font-size: 8pt;'>[ <U><font color=darkblue>Email this page to a friend</font></U> ]</FONT><!-- ##EFRIEND## -->");

   document.getElementById(ColRowID).innerHTML= finalObj;

	setTimeout("checkRow(ColRowID)",1500)
}

// =========================================================
// =============== Popup Win ===============================
// =========================================================

function OkPopupData() {
	var finalObj,RandNum;
   var tmplt = objTemplate('POPUPOBJ', true, 80);

   RandNum = tmplt[0];
   popupID = RandNum.replace("POPUPOBJ", "POPOBJ");
   
   
	disOne = $('popname').selectedIndex;
	tPageName = eval("$('popname').options["+disOne+"].value");
   
	tWindowW = $('winw').value;
	tWindowW = tWindowW.toString();
   
	tWindowH = $('winh').value;
	tWindowH = tWindowH.toString();
   
   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<div ID="+popupID+" align=center><font face=Arial style='font-size: 7pt;'><U>JAVASCRIPT POPUP WINDOW</U><BR>This is a <B>hidden</B> Javascript function that will automatically spawn a "+tWindowW+"x"+tWindowH+" browser window when this page is accessed.</font></div><!-- ##POPUP;"+tPageName+";"+tWindowW+";"+tWindowH+"## -->");
   
   if (tPageName != "NONE") {
      document.getElementById(ColRowID).innerHTML= finalObj;
   }

	checkRow(ColRowID)

  	$('popname').selectedIndex = 0;
	$('winw').value = '';
	$('winh').value = '';
}

// =========================================================
// =============== Audio Files =============================
// =========================================================

function OkMP3Data() {
   
   var finalObj,RandNum;
   var tmplt = objTemplate('MP3OBJ', true, 80);

   RandNum = tmplt[0];
   audioID = RandNum.replace("MP3OBJ", "AUDIOOBJ");
   
	disOne = $('mp3name').selectedIndex;
	tMP3 = eval("$('mp3name').options["+disOne+"].value");
   displayFilename = tMP3.replace(new RegExp('[_]+', 'g'), ' ');
   audioPlaceholder = "<div id="+audioID+" align=\"center\" class=\"grid-placeholder-audio\"><span class=\"grid-filename audio\">"+displayFilename;
   audioPlaceholder = audioPlaceholder + "</span></div><!-- ##MP3;"+tMP3+"## -->";
   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", audioPlaceholder);
   
   if (tMP3 != "NONE") {
      document.getElementById(ColRowID).innerHTML= finalObj;
   }

	checkRow(ColRowID)

  	$('mp3name').selectedIndex = 0;
}

// =========================================================
// =============== Video Files =============================
// =========================================================

function OkVideoData() {

   var finalObj,RandNum;
   var tmplt = objTemplate('VIDEOOBJ', true, 80);

   RandNum = tmplt[0];
   videoID = RandNum.replace("VIDEOBJ", "VIDOBJ");
   
	disOne = $('videoname').selectedIndex;
	tVideo = eval("$('videoname').options["+disOne+"].value");
	if ( tVideo == 'NONE' ) {
		tVideo = eval("$('videourl').value");
	}

	tVideoW = $('videow').value;
	tVideoW = tVideoW.toString();

	tVideoH = $('videoh').value;
	tVideoH = tVideoH.toString();
	
	tVideoName = tVideo.replace('http://', '');
	if ( tVideoName.match(/youtu/g) != null ) {
		tVideoName = 'YouTube Video';
	} else if ( tVideoName.match(/vimeo/g) != null ) {
		tVideoName = 'Vimeo Video';
	}
	
	if ( tVideoW == '' ) { tVideoW = 500; }
	if ( tVideoH == '' ) { tVideoH = 312; }
	tVideo = tVideo.concat(';'+tVideoW+';'+tVideoH);

	var flash_file_test = tVideo.search(".swf");
	if (flash_file_test > 0) {		// This is a FLASH file
		OkFlashData(tVideo,tVideoW,tVideoH,videoID,tmplt);
		tVideo = "NONE";
	}
   
   objCaption = "<div ID="+videoID+" align=\"center\" class=\"grid-video-placeholder\"><span class=\"grid-filename\">"+tVideoName;
   if ( tVideoW > 0 && tVideoH > 0 ) {
   	objCaption = objCaption + ' <span class="grid-video-dimensions">('+tVideoW+' &times; '+tVideoH+')</span>';
   }
   objCaption = objCaption + "</span></div><!-- ##VIDEO;"+tVideo+"## -->";
   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", objCaption);
   
   if (tVideo != "NONE") {
      document.getElementById(ColRowID).innerHTML= finalObj;
      $('videourl').value = '';
   }

	checkRow(ColRowID)

  	videoname.selectedIndex = 0;
}

// =========================================================

function OkFlashData(tFlash,tFlashW,tFlashH,tFlashID,tmplt) {

	var istVideo = tFlash.search(";;");
	if(istVideo > 0){
	   tFlash = tFlash.replace(';;',';'+tFlashW+';'+tFlashH);
	}else{
      var disTing = tFlash.indexOf(";");
      tFlash = tFlash.substring(0, disTing);
      tFlash = tFlash.concat(';'+tFlashW+';'+tFlashH);
   }
   
   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<div ID="+tFlashID+"><font style='font-family: Arial; font-size: 7pt;'><U>FLASH MOVIE DISPLAY</U><BR>The flash movie \"<U>"+tFlash+"</U>\" will be displayed here.</font></div><!-- ##FLASH;"+tFlash+";"+tFlashW+";"+tFlashH+"## -->");
   
   document.getElementById(ColRowID).innerHTML= finalObj;

  	$('videoname').selectedIndex = 0;
  	closeit();
}

// =========================================================
// =============== Plugin Links ============================
// =========================================================

function OkGrlinkData() {

	disOne = $('grlink').selectedIndex;
	tlinkBut = eval("$('grlink').options["+disOne+"].value");

	tlinkBut = tlinkBut.toString();

	eval(tlinkBut+"();");
}

// =========================================================

function adobelink() {
   var finalObj,RandNum;
   var tmplt = objTemplate('ADOBEOBJ', true, 50);

   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<img src=client/adobe_link.gif border=0 class=tHead><!-- ##ADOBELINK## -->");
   
   document.getElementById(ColRowID).innerHTML= finalObj;
	checkRow(ColRowID)
}

// =========================================================

function flashlink() {
   var finalObj,RandNum;
   var tmplt = objTemplate('FLASHLINKOBJ', true, 50);

   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<img src=client/flash_link.gif border=0 class=tHead><!-- ##FLASHLINK## -->");
   
   document.getElementById(ColRowID).innerHTML= finalObj;
	checkRow(ColRowID)
}

// =========================================================

function winamplink() {
   var finalObj,RandNum;
   var tmplt = objTemplate('WINAMPOBJ', true, 50);

   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<img src=client/winamp_link.gif border=0 class=tHead><!-- ##WINAMPLINK## -->");
   
   document.getElementById(ColRowID).innerHTML= finalObj;
	checkRow(ColRowID)
}

// =========================================================

function quicktimelink() {
   var finalObj,RandNum;
   var tmplt = objTemplate('ADOBEOBJ', true, 50);

   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<img src=client/quicktime_link.gif border=0 class=tHead><!-- ##QUICKTIMELINK## -->");
   
   document.getElementById(ColRowID).innerHTML= finalObj;
	checkRow(ColRowID)
}

// =========================================================
// =============== Photo Album =============================
// =========================================================

function photoalbum() {
   
   var finalObj,RandNum;
   var tmplt = objTemplate('PHOTOALBUMOBJ', true, 80);
   var photoalbvar = document.getElementById('photoAlb').checked;
   //alert(document.getElementById('photoDisplay').value);
//	if($('photoUser').checked){
//      finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<font style='font-family: Arial; font-size: 8pt;'><U><font color=darkblue>Photo Album</font></U></FONT><!-- ##CUSTOMHTML;pgm-photo_album.php## -->");
//	}else{
		disOne = $('photocat').selectedIndex;
		tphotocat = eval("$('photocat').options["+disOne+"].value");
		tphotocatname = eval("$('photocat').options["+disOne+"].text");
		if(tphotocat == "NULL"){
			alert('You must select a photo album, nothing will be added.');
		}else{

			tphotocat = tphotocat.toString();
		
		if(photoalbvar == true){
			finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<font style='font-family: Arial; font-size: 8pt;'><U><font color=darkblue>Photo Album : "+tphotocatname+"</font></U></FONT><!-- ##PHOTO;"+tphotocat+"## -->");
		} else {
			finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<font style='font-family: Arial; font-size: 8pt;'><U><font color=darkblue>Photo Slider : "+tphotocatname+"</font></U></FONT><!-- ##SLIDER;"+tphotocat+"## -->");
		}
         

		  	$('photocat').selectedIndex = 0;	// Reset Selection to Nothing(Null)
		}
//	}
	
   if ($('photoUser').checked || tphotocat != "NULL") {
      document.getElementById(ColRowID).innerHTML= finalObj;
   }

	checkRow(ColRowID)
}

// =========================================================

function photoDisable(){
	if($('photoUser').checked){
		$('photocat').disabled=true
	}else{
		$('photocat').disabled=false
	}
}


// =========================================================
// =============== Blogs ===================================
// =========================================================

function OkBlog() {
   var finalObj,RandNum;
   var tmplt = objTemplate('MP3OBJ', true, 50);

	disOne = $('blogsubj').selectedIndex;
	
	tBlogSubject = eval("$('blogsubj').options["+disOne+"].value");
	tBlogSubject = tBlogSubject.toString();
	var tBlogSubjectText = eval("$('blogsubj').options["+disOne+"].text");
	tBlogSubjectText = tBlogSubjectText.toString();
   
   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<font style='font-family: Arial; font-size: 8pt;'><U><font color=darkblue>Blog : "+tBlogSubjectText+"</font></U></FONT><!-- ##BLOG;"+tBlogSubject+"## -->");
   
   if(tBlogSubject == "NULL"){
      alert('You must select a blog to display, nothing will be added');
   }else{
      document.getElementById(ColRowID).innerHTML= finalObj;
   }

	checkRow(ColRowID)
	
  	$('blogsubj').selectedIndex = 0;	// Reset Selection to Nothing(Null)
}

// =========================================================
// =============== FAQ'S ===================================
// =========================================================

function OkFaq() {
   var finalObj,RandNum;
   var tmplt = objTemplate('MP3OBJ', true, 50);

	disOne = $('faqcat').selectedIndex;
	tFaqCat = eval("$('faqcat').options["+disOne+"].value");
	tFaqCat = tFaqCat.toString();
   
   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<font style='font-family: Arial; font-size: 8pt;'><U><font color=darkblue>Faq : "+tFaqCat+"</font></U></FONT><!-- ##FAQ;"+tFaqCat+"## -->");
   
   if(tFaqCat == "NULL"){
      alert('You must select a category to display, nothing will be added');
   }else{
      document.getElementById(ColRowID).innerHTML= finalObj;
   }

	checkRow(ColRowID)

  	$('faqcat').selectedIndex = 0;	// Reset Selection to Nothing(Null)
}





// =========================================================
// =========================================================
// =========================================================
// =============== UPLOAD FILES STUFF ======================
// =========================================================
// =========================================================
// =========================================================

function loadUploadDialog(type) {
   var url = "upFile.php?todo=UPNOW&type="+type;
   show_hide_layer('objectbar','','hide','upload_display','','show');
   $('upload_frame').src = url;
   //ajaxDo(url, 'upload_display');
}



// =========================================================
// =============== IMAGE UPLOAD ============================
// =========================================================

function getImageDataUP(tImage) {
   var finalObj,RandNum;
   var tmplt = objTemplate('IMGOBJ', false);
   
   <? echo ("tImage = \"http://$this_ip/images/\" + tImage;\n"); ?>
   
   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<img src="+tImage+" border=1 class=tHead>");
   
   if (tImage != "NONE") {
      document.getElementById(ColRowID).innerHTML= finalObj;
   }
   
	checkRow(ColRowID)
}

// =========================================================

function OkImageDataUP(tImage) {
   
   
   var finalObj,RandNum;
   var tmplt = objTemplate('IMGOBJ', true);
   
   RandNum = tmplt[0];
   imgID = RandNum.replace("IMGOBJ", "NEWIMGOBJ");
   
   <? echo ("tImage = \"http://$this_ip/images/\" + tImage;\n"); ?>
   
   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<!-- ##IMAGE;"+tImage+";"+imgID+"## --><img src="+tImage+" border=0 id="+imgID+" class=tHead onClick=\"linkImage('"+imgID+"');\">");
   
   if (tImage != "NONE") {
      document.getElementById(ColRowID).innerHTML= finalObj;
   }
   
	checkRow(ColRowID)
	show_hide_layer('upload_display','','hide','objectbar','','show');
}


// =========================================================
// =============== DOCUMENT UPLOAD =========================
// =========================================================

function OkWordDataUP(tWordFile) {
   
   var finalObj,RandNum;
   var tmplt = objTemplate('WORDFILE', true, 50);
   
   RandNum = tmplt[0];
   docID = RandNum.replace("WORDFILE", "DOCOBJ");
   
   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<div ID="+docID+"><img src='client/download_icon.gif' align=absmiddle vspace=0 hspace=2 border=0 width=20 height=19> <font face=Arial style='font-size: 8pt;'><U>"+tWordFile+"</U><font color=#999999> [Size]</font></font></div><!-- ##MSWORD;"+tWordFile+"## -->");
   
   if (tWordFile != "NONE") {
      document.getElementById(ColRowID).innerHTML= finalObj;
   }
   
	checkRow(ColRowID)
   show_hide_layer('upload_display','','hide','objectbar','','show');
}

// =========================================================
// =============== AUDIO UPLOAD ============================
// =========================================================

function OkMP3DataUP(tMP3) {
   var finalObj,RandNum;
   var tmplt = objTemplate('MP3OBJ', true, 80);

   RandNum = tmplt[0];
   audioID = RandNum.replace("MP3OBJ", "AUDIOOBJ");
   
   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<div ID="+audioID+"><font style='font-family: Arial; font-size: 8pt;'><img src='client/download_icon.gif' align=absmiddle vspace=0 hspace=2 border=0 width=20 height=19><U><font color=blue>"+tMP3+"</font></u></font></div><!-- ##MP3;"+tMP3+"## -->");
   
//   audioPlaceholder = "<div ID="+audioID+" align=\"center\" class=\"grid-placeholder-audio\"><span class=\"grid-filename audio\">"+tMP3;
//   audioPlaceholder = audioPlaceholder + "</span></div><!-- ##MP3;"+tMP3+"## -->";
//   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", audioPlaceholder);
   
   if (tMP3 != "NONE") {
      document.getElementById(ColRowID).innerHTML= finalObj;
   }

	checkRow(ColRowID)
   show_hide_layer('upload_display','','hide','objectbar','','show');
}

// =========================================================
// =============== VIDEO UPLOAD ============================
// =========================================================

function OkVideoDataUP(tVideo,tVideoW,tVideoH) {
   var finalObj,RandNum;
   var tmplt = objTemplate('VIDEOOBJ', true, 80);

   RandNum = tmplt[0];
   videoID = RandNum.replace("VIDEOBJ", "VIDOBJ");

	var istVideo = tVideo.search(";;");
	if(istVideo > 0){
	   tVideo = tVideo.replace(';;',';'+tVideoW+';'+tVideoH);
	}else{
      var disTing = tVideo.indexOf(";");
      tVideo = tVideo.substring(0, disTing);
      tVideo = tVideo.concat(';'+tVideoW+';'+tVideoH);
   }

	//tVideoFull = tVideo+" "+tVideoW+"W X "+tVideoH+"H";
	var flash_file_test = tVideo.search(".swf");

	if (flash_file_test > 0) {		// This is a FLASH file
		OkFlashData(tVideo,tVideoW,tVideoH,videoID,tmplt);
		tVideo = "NONE";
	}
   
   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<div ID="+videoID+" align=center><font style='font-family: Arial; font-size: 7pt;'><INPUT TYPE=BUTTON VALUE='Play Video' CLASS=FormLt1><BR><FONT COLOR=#999999>[ "+tVideo+" ]</FONT></font></div><!-- ##VIDEO;"+tVideo+";"+tVideoW+";"+tVideoH+"## -->");
   
   if (tVideo != "NONE") {
      document.getElementById(ColRowID).innerHTML= finalObj;
   }

	checkRow(ColRowID)
   show_hide_layer('upload_display','','hide','objectbar','','show');
}

// =========================================================
// =============== CUSTOM FILE UPLOAD ======================
// =========================================================

function OkCustomDataUP(tCustom) {
   var finalObj,RandNum;
   var tmplt = objTemplate('CUSTOMOBJ', true, 80);

   RandNum = tmplt[0];
   customID = RandNum.replace("CUSTOMOBJ", "NEWCUSTOMOBJ");

   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<div ID="+customID+" align=center><font style='font-family: Arial; font-size: 8pt;'><B>Custom Code Include:</B><BR>[ "+tCustom+" ]</font><br/><br/><button type=\"button\" class=\"blueButton\" onclick=\"loadNewCustom('"+tCustom+"');\"><span><span>Edit</span></span></button></div><!-- ##CUSTOMHTML;"+tCustom+"## -->");

   if (tCustom != "NONE") {
      document.getElementById(ColRowID).innerHTML= finalObj;
   }

	checkRow(ColRowID)
   show_hide_layer('upload_display','','hide','objectbar','','show');
}









// =========================================================

function linkImage(imagePass) {
	tempImage = imagePass;
	show_hide_layer('objectbar','','hide','imageLink','','show');
}

// =========================================================

function inputImageLink() {
	var oTemp = document.getElementById('saveForm').innerHTML;
	disOne = $('imagePageLink').selectedIndex;
	var sText = eval("$('imagePageLink').options["+disOne+"].value");
   
	if (sText == "NONE") {
		sText = $('imageUrlLink').value;
	}
	if (sText == "http://") {
		sText = "mailto:"+$('emailImageLink').value;
	}
	if (sText == "mailto:") {
		sText = "#";
	}
	
	var oTemp = oTemp + "<input type=hidden name=PICLINK"+tempImage+" value=\""+tempImage+"PICLINK"+sText+"\">";
	if (sText != "#") {
		document.getElementById('saveForm').innerHTML = oTemp;
	}
	imagePageLink.selectedIndex = 0;
	imageUrlLink.value = "http://";
	emailImageLink.value = "";
}






// =========================================================

function OkNewsArchive() {

	doOperation = 0;
	tloginbutton = "Newsletter Archives";

	TableStart = "<table border=0 cellpadding=0 cellspacing=0><tr><td width=199 height=75 align=center valign=middle>";
    	TableEnd = "</td></tr></table><!-- ~~~ -->";

	if (tloginbutton != "") { 		if (InObj.search("pixel.gif") > 0) {
			sText = TableStart+TextHeader+"<button type=\"button\" class=\"blueButton\" id=NEWSARCH vspace=2><span><span>"+tloginbutton+"</span></span></button><!-- ##NEWSARCHIVE;"+tloginbutton+"## -->"+TableEnd;
			doOperation = 1;
		} else {
		sText = oldImageData+"<BR>"+TableStart+TextHeader+"<button type=\"button\" class=\"blueButton\" id=NEWSARCH vspace=2><span><span>"+tloginbutton+"</span></span></button><!-- ##NEWSARCHIVE;"+tloginbutton+"## -->"+TableEnd;
			doOperation = 1;
		}
	} else {
		LowerDropZone();
		alert("You did not fill in a name for the button.");
		doOperation = 0;
	}

	if (doOperation == 1) {
		ColumnDrop.innerHTML = sText;
		document.getElementById(ColRowID).style.backgroundColor= "#FFFFFF";
	}
	LowerDropZone();


}



// =========================================================


<?php
eval(hook("pe-place_object_js", basename(__FILE__)));
?>

// Places supersearch object in dropzone
function place_supersearch() {
	doOperation = 0;
   var dataTrue = dataData.search("pixel.gif");




   // MoveObject_Graphic = ;
	TableStart = "<table width=\"199\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\" style=\"margin-top:25px;border: 1px inset black; background: #EFEFEF;\">\n";

	// 'Move Object' graphic
	TableStart += " <tr>\n";
	TableStart += "  <td colspan=\"2\" valign=\"top\">\n";
	TableStart += "   <img src=\"images/text_header.gif\" width=199 height=15 border=0 align=left vspace=0 hspace=0 style=\"cursor: move;\"><br clear=\"all\">";
   TableStart += "  </td>\n";
   TableStart += " </tr>\n";


	// Thumbnail Image
	TableStart += " <tr>\n";
	TableStart += "  <td valign=\"top\" width=\"64\">\n";
	TableStart += "   <img src=\"<? echo httpvar().$_SESSION['docroot_url']; ?>/sohoadmin/program/modules/super_search/page_editor/supersearch_dropzone.gif\" width=\"198\" height=\"66\" border=\"0\" style=\"cursor: move;\">";
   TableStart += "  </td>\n";

   TableStart += " </tr>\n";
   TableStart += "</table>\n";
   TableStart += "<!-- ##SUPERSEARCH## -->";

   var finalObj,RandNum;
   var tmplt = objTemplate('SUPERSEARCH', true, 50);
   
   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", TableStart);
   document.getElementById(ColRowID).innerHTML= finalObj;
   
	setTimeout("checkRow(ColRowID)",1500)


//	if (dataTrue > 0) {
//		//sContent = ;
//		sText = TableStart;
//		doOperation = 1;
//	} else {
//	   sText = dataData+"<BR>"+TableStart;
//		doOperation = 1;
//	}
//
//	if (doOperation == 1) {
//		document.getElementById(ColRowID).innerHTML= sText;
//		document.getElementById(ColRowID).style.backgroundColor= "#FFFFFF";
//	}
//   if ( window.checkPageAreas ){
//      checkPageAreas('start');
//   }
}


function newsfeedEditFB(ColRowID,fb_id_valv,fb_post_limitv,fb_show_follow_usv,fb_hide_authorv,fb_include_picturesv){
	//alert(ColRowID);
//	parent.frames.body.document.getElementById('oknewscolid').value=ColRowID;
//      var ColRowID = ColRowID;
//      var dataData = document.getElementById(ColRowID).innerHTML;
//	//var ColRowID = ColRowID;
//	//show_hide_layer('newsFeedLayer','','show','objectbar','','hide');
//	show_hide_layer('objectbar','','hide','newsFeedLayer','','show');
////	document.getElementById('twitter_options').style.display='none';
////	document.getElementById('facebook_options').style.display='block';
////	document.getElementById('facebook_id').value = fb_id_valv;
////	document.getElementById('fb_post_limit').value = fb_post_limitv;
////	document.getElementById('fb_show_follow_us').checked = fb_show_follow_usv;
////	document.getElementById('fb_hide_author').checked = fb_hide_authorv;
////	document.getElementById('fb_include_pictures').checked = fb_include_picturesv;

}





function OkNewsFeed() {	
	var finalObj,RandNum;
	var tmplt = objTemplate('NEWOBJ', true, 50);
	var tdBase = '';
	var RandNum = tmplt[0];
	var newsfeedsID = RandNum.replace("NEWOBJ", "NEWSFEEDOBJ");
	var nowgo = 0;
	
	var newsfeed_image = '';
	var newsfeed_editbutton = '';
	
   	var nwsf = $('newsfeed_sel').selectedIndex;
	var newsfeedtype = eval("$('newsfeed_sel').options["+nwsf+"].value");
	//alert(newsfeedtype+'hi');
	//var nwsf = document.getElementById('newsfeed_sel');
	//var newsfeedtype=nwsf.options[nwsf.selectedIndex].value;
	
	if(newsfeedtype==''){
		setTimeout("checkRow(ColRowID)",1000)
		show_hide_layer('objectbar','','show','newsFeedLayer','','hide');
	} else {
		if(newsfeedtype=='facebook'){
			var fb_id_val = document.getElementById('facebook_id').value;
			if(fb_id_val == '' || isNaN(fb_id_val)==true){
				if(fb_id_val == ''){
					alert('Your Facebook ID Number is required.');
				} else {
					alert('Your Facebook ID must be a number.');
				}
				
			} else {
				nowgo = 1;
				var newsfeed_image = "<img src='images/soc_facebook.png' style='margin-right:5px;margin-top:10px;width:20px;height:20px;'><br/>";								
				
				var news_feed_options = fb_id_val+'~'+document.getElementById('fb_post_limit').value;
				if(document.getElementById('fb_show_follow_us').checked==true){
					news_feed_options = news_feed_options+'~1';
					var fb_show_follow_us_val = 'true';
				} else {
					news_feed_options = news_feed_options+'~0';
					var fb_show_follow_us_val = 'false';
				}
				if(document.getElementById('fb_hide_author').checked==true){
					var fb_hide_author_val = 'true';
					news_feed_options = news_feed_options+'~1';
				} else {
					var fb_hide_author_val = 'false';
					news_feed_options = news_feed_options+'~0';
				}
				if(document.getElementById('fb_include_pictures').checked==true){
					var fb_include_pictures_val = 'true';
					news_feed_options = news_feed_options+'~1';
				} else {
					var fb_include_pictures_val = 'false';
					news_feed_options = news_feed_options+'~0';
				}
				//var newsfeed_editbutton = "<button class=\"grayButton\" onClick=\"newsfeedEditFB('"+fb_id_val+"','"+document.getElementById('fb_post_limit').value+"','"+fb_show_follow_us_val+"','"+fb_hide_author_val+"','"+fb_include_pictures_val+"');\"><span><span>edit feed</span></span></button>";
				//var newsfeed_editbutton = " onclick=\"var ColRowID=this.parentNode.parentNode.id;newsfeedEditFB(this.parentNode.parentNode.id,'"+fb_id_val+"','"+document.getElementById('fb_post_limit').value+"','"+fb_show_follow_us_val+"','"+fb_hide_author_val+"','"+fb_include_pictures_val+"');\" ";
				//var newsfeed_editbutton = "<button class=\"grayButton\" onClick=\"newsfeedEditFB('"+fb_id_val+"','"+document.getElementById('fb_post_limit').value+"','"+document.getElementById('fb_show_follow_us').checked+"','"+document.getElementById('fb_hide_author').checked+"','"+document.getElementById('fb_include_pictures').checked+"');\"><span>edit feed</span></button>";
			}
		} else {
			if(newsfeedtype=='twitter'){
				var tw_id_val = document.getElementById('twitter_id').value;
				if(tw_id_val == ''){
					alert('Your Twitter ID Number is required.');
				} else {
					nowgo = 1;					
					var newsfeed_image = "<img src='images/soc_twitter.gif' style='margin-right:5px;margin-top:10px;width:20px;height:20px;'><br/>";
					var news_feed_options = tw_id_val+'~'+document.getElementById('tw_post_limit').value;
					if(document.getElementById('tw_show_follow_us').checked==true){
						news_feed_options = news_feed_options+'~1';
					} else {
						news_feed_options = news_feed_options+'~0';
					}
				}
			} else {
				if(newsfeedtype='sohoblog'){
					var sohoblog_cat = document.getElementById('sohoblog_cat').value;
					nowgo = 1;
					newsfeedtype = 'blog';
					var newsfeed_image = "<img src='../../includes/images/blog-icon-small.png' style='margin-right:5px;margin-top:10px;width:20px;height:20px;'><br/>";
					var news_feed_options = sohoblog_cat+'~'+document.getElementById('sohoblog_post_limit').value;
					if(document.getElementById('sohoblog_timestamp').checked==true){
						news_feed_options = news_feed_options+'~1';
					} else {
						news_feed_options = news_feed_options+'~0';
					}
					if(document.getElementById('sohoblog_readmore').checked==true){
						news_feed_options = news_feed_options+'~1';
					} else {
						news_feed_options = news_feed_options+'~0';
					}
					if(document.getElementById('sohoblog_author').checked==true){
						news_feed_options = news_feed_options+'~1';
					} else {
						news_feed_options = news_feed_options+'~0';
					}
				
					
				}
			}
		}
		if(nowgo==1){
		//alert("##NEWSFEED;"+newsfeedtype+";"+news_feed_options+"##");ColRowID
			var finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<div align=center valign=middle "+newsfeed_editbutton+">"+newsfeed_image+"<font face=Arial size=1><B>News Feed: "+newsfeedtype+"</B></font></div><!-- ##NEWSFEED;"+newsfeedtype+";"+news_feed_options+"## -->");
			document.getElementById(ColRowID).innerHTML= finalObj;
			setTimeout("checkRow(ColRowID)",1000)
			show_hide_layer('objectbar','','show','newsFeedLayer','','hide');
		}
	}
}