//misc page editor javascript functions


var sImageURL;
var ColRow;
var WhatObj;
var WhatSpan;
var InObj;
var d;
var oTemp;
var TableStart;
var TableEnd;
var RandNum;
var doOperation;
var TextHeader;
var curId;
var ColumnDrop;
var tImage;
var Remember;
var oldImageData;
var textId;
var imagePass;
var tempImage;
var selectedformname='';
// Flip header nav to page editor buttons
parent.header.flip_header_nav('PAGE_EDITOR_LAYER');


// ------------------------------------------------------------------
// Kill any Javascript error notifications that may occur.
// This is important in IE5 because the drag and drop functions
// will kickback return codes for success or failure operations.
// -- This is a shortcut in order not to deal with codes that are
//    unimportant to getting the job done.
// ------------------------------------------------------------------

function killErrors() {
	return true;
}

window.onerror = killErrors;

// ------------------------------------------------------------------

function MM_findObj(n, d) {
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
}

function MM_openBrWindow(theURL,winName,features) {
  window.open(theURL,winName,features);
}

function MM_showHideLayers() {
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
    obj.visibility=v; }
}

function disable_links(){
	var linksTotal = document.links.length;
	//alert(linksTotal);
	for(var x=0;x<linksTotal;x++){
	   var link = document.links[x];
      if (link.onclick)
         link.oldOnClick = link.onclick;
      link.onclick = cancelLink;
	}
}

function cancelLink () {
  return false;
}

function replaceImageData() {
   document.getElementById(ColRowID).innerHTML= dataData;
   checkRow(ColRowID)
}

function KillWindow() {
   document.getElementById('DispNow').style.display= 'none';
}

function ShowSaveAs() {
   document.getElementById('save').style.display = '';
	show_hide_layer('pageproperties','','hide','saveaslayer','','show');
}

function ShowPageProps() {
   document.getElementById('save').style.display = '';
	show_hide_layer('saveaslayer','','hide','pageproperties','','show');
}

function BGchange(curCell) {
   //alert(curCell);
   DeleteMe = document.getElementById(curCell).innerHTML;
}

function BGchangeAgain(curCell) {
   document.getElementById(curCell).style.backgroundColor= "#FFFFFF";
}
function getCord(cord) {
   var dispCord = cord.getAttribute('id');
}

function changedesc(dt) {
   //alert(dt);
	// Change discriptive text in invisible header - kinda pointless, it seems
	document.getElementById('desctext').innerHTML= dt;

	// Show footer description of currently highlighted object
	//parent.frames.footer.PAGESTAT.innerHTML = dt;
}

function resetdesc() {
   // Reset discriptive text in invisible header - kinda pointless, it seems
	desctext.innerHTML = 'Click on an object above and drag it onto a drop zone for page placement.';

	// Reset footer description of currently highlighted object
   parent.frames.footer.PAGESTAT.innerHTML = '';

   window.event.dataTransfer.clearData();
}

// Scrolling buttons for individual cells

function scroll_down(scrollThis) {
   var cur_pos = document.getElementById(scrollThis).scrollTop;
   var cur_pos = Number(cur_pos);
   var posi = (cur_pos+40);
   document.getElementById(scrollThis).scrollTop= posi;
}

function scroll_up(scrollThis) {
   var cur_pos = document.getElementById(scrollThis).scrollTop;
   var cur_pos = Number(cur_pos);
   var posi = (cur_pos-40);
   document.getElementById(scrollThis).scrollTop= posi;
}

function GetInnerSize () {
	var x,y;
	if (self.innerHeight) // all except Explorer
	{
		x = self.innerWidth;
		y = self.innerHeight;
	}
	else if (document.documentElement && document.documentElement.clientHeight)
		// Explorer 6 Strict Mode
	{
		x = document.documentElement.clientWidth;
		y = document.documentElement.clientHeight;
	}
	else if (document.body) // other Explorers
	{
		x = document.body.clientWidth;
		y = document.body.clientHeight;
	}
	return [x,y];
}

//---------------------------------------------------------------------------------------------------------
//      _      _   _   __  __
//     /_\  _ | | /_\  \ \/ /
//    / _ \| || |/ _ \  >  <
//   /_/ \_\\__//_/ \_\/_/\_\
//
//---------------------------------------------------------------------------------------------------------
// The following script (as commonly seen in other AJAX javascripts) is used to detect which browser the client is using.
// If the browser is Internet Explorer we make the object with ActiveX.
// (note that ActiveX must be enabled for it to work in IE)
//function makeObject() {
//   var x;
//   var browser = navigator.appName;
//
//   if ( browser == "Microsoft Internet Explorer" ) {
//      x = new ActiveXObject("Microsoft.XMLHTTP");
//   } else {
//      x = new XMLHttpRequest();
//   }
//
//   return x;
//}

function makeObject() {
   var httpRequest;

   if (window.XMLHttpRequest) { // Mozilla, Safari, ...
      httpRequest = new XMLHttpRequest();
      if (httpRequest.overrideMimeType) {
          httpRequest.overrideMimeType('text/xml');
          // Or else you get 'object required' error in IE and it doesn't work
      }
   } else if (window.ActiveXObject) { // IE
      try {
//          httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
          httpRequest = new ActiveXObject("MicrosoftXMLDOM");
      } catch (e) {
          try {
              httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
          } catch (e) {}
      }
   }

   return httpRequest;
}

// The javascript variable 'request' now holds our request object.
// Without this, there's no need to continue reading because it won't work ;)
var request = makeObject();

function ajaxDo(qryString, boxid) {
   //alert(qryString+', '+boxid);

   rezBox = boxid; // Make global so parseInfo can get it

   // The function open() is used to open a connection. Parameters are 'method' and 'url'. For this tutorial we use GET.
   request.open('get', qryString);

   // This tells the script to call parseInfo() when the ready state is changed
   request.onreadystatechange = parseInfo;

   // This sends whatever we need to send. Unless you're using POST as method, the parameter is to remain empty.
   request.send('');

}

function parseInfo() {
   // Loading
   if ( request.readyState == 1 ) {
      document.getElementById(rezBox).innerHTML = 'Loading...';
   }

   // Finished
   if ( request.readyState == 4 ) {
      var answer = request.responseText;
      document.getElementById(rezBox).innerHTML = answer;
      //alert(rezBox)
      if(rezBox == "form_display"){
         var selectionUrl = "formlib/selection.php?dropArea="+ColRowID+"&selkey="+formType;
         ajaxDo(selectionUrl, 'selection')
      }else if(rezBox == "simple_editor_container"){
         //alert('howdy');
         CodePress();
      }else if(rezBox == "selection"){      	
		if(selectedformname!='' && editType!='NULL'){
			var previewUrl = "formlib/preview.php?dropArea="+ColRowID+"&lookat="+selectedformname;
		} else {
			var previewUrl = "formlib/preview.php?dropArea="+ColRowID;
		}
         
         ajaxDo(previewUrl, 'preview')
      }else if(editType && editType != 'NULL'){
         // editType string
         //RandNum+';'+tEmailFormTo+';'+tDataName+';'+formName+';'+tEmailFormFrom+';'+tSubjectLine+';'+tResponseFile+';'+tCloseWin+';'+tPageGo
         //alert('something')
         formVals = editType.split(';');
         $('oldFormVals').value = formVals[0];
         $('availforms').value = formVals[3]
         $('emailaddr').value = formVals[1]
         $('savedbtable').value = formVals[2]
         $('emailfrom').value = formVals[4]
         $('subjectline').value = formVals[5]
         $('responsefile').value = formVals[6]
         if(formVals[7] == "yes"){
            //alert('checking yes')
            $('closewinY').checked
         }else{
            $('closewinN').checked
            $('pagego').value = formVals[8]
         }
         editType = 'NULL';
      }else if(rezBox == "upload_display"){
         alert('adding event..')
         $('upitnow').addEvent('submit', function(e) {
         	new Event(e).stop();
         	var log = $('clear_joe').empty();
         	this.send({
         	   method: 'post',
         		update: log,
         		onComplete: function() {
         			alert('done');
         		}
         	});
         });
         alert('added event..')
      }
      
   }
}






//#############################################################
//########custom include editing ajax
//#############################################################
function makeObjectcc() {
   var httpRequest;

   if (window.XMLHttpRequest) { // Mozilla, Safari, ...
      httpRequest = new XMLHttpRequest();
      if (httpRequest.overrideMimeType) {
          httpRequest.overrideMimeType('text/html');
          // Or else you get 'object required' error in IE and it doesn't work
      }
   } else if (window.ActiveXObject) { // IE
      try {
//          httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
          httpRequest = new ActiveXObject("MicrosoftXMLDOM");
      } catch (e) {
          try {
              httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
          } catch (e) {}
      }
   }

   return httpRequest;
}

// The javascript variable 'request' now holds our request object.
// Without this, there's no need to continue reading because it won't work ;)
var request = makeObjectcc();

function ajaxDocc(qryString, query, boxid) {
   //alert(qryString+', '+query);
	 var contentType = "application/x-www-form-urlencoded; charset=UTF-8";
   rezBox = boxid; // Make global so parseInfo can get it

   // The function open() is used to open a connection. Parameters are 'method' and 'url'. For this tutorial we use GET.
   request.open('post', qryString);
	 request.setRequestHeader("Content-Type", contentType);
   // This tells the script to call parseInfo() when the ready state is changed
   request.onreadystatechange = parseInfocc;

   // This sends whatever we need to send. Unless you're using POST as method, the parameter is to remain empty.
   request.send(query);

}

function parseInfocc() {
   // Loading
   if ( request.readyState == 1 ) {
      document.getElementById(rezBox).innerHTML = 'Loading...';
   }

   // Finished
   if ( request.readyState == 4 ) {
      var answer = request.responseText;
      document.getElementById(rezBox).innerHTML = answer;
      if(rezBox == "simple_editor_container"){
         //alert('howdy');
         CodePress();
      }
      
   }
}

//#############################################################
//########END custom include editing ajax
//#############################################################





//#############################################################
//#### SAVEING PAGE - MAIN_MENU.PHP 						     ####
//#############################################################

function TouchMe(thisVal) {
   var CellHTML = document.getElementById(thisVal).innerHTML;
return CellHTML; }

function GetSaveForm () {
   var SendSaveForm = document.getElementById('saveForm').innerHTML;
return SendSaveForm; }

function SendSaveText (SaveThis) {
   document.getElementById('saveForm').innerHTML= SaveThis;
}
function GoToSave() {
   document.save.submit();
}

function ShowNoSave() {
   show_hide_layer('userOpsLayer','','hide');
   //show_hide_layer('NOSAVE_LAYER','','show');			// Display NO SAVE Loading image in Edit Window
	window.location.href="main_menu.php";
}

//#############################################################
//#### END SAVEING PAGE - MAIN_MENU.PHP 						  ####
//#############################################################

function clearCell() {
   document.getElementById(ColRowID).innerHTML= '<IMG height=50% src=pixel.gif width=199 border=0>';
   document.getElementById(ColRowID).style.backgroundColor= "#FFFFFF";
}

function createCookie(name,value,days)
{
	if (days)
	{
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name)
{
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++)
	{
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

// =========================================================

function getCurrentTrue() {
   var curTrue = document.getElementById(ColRowID).innerHTML.search("pixel.gif");
return curTrue }

function getCurrentCont() {
   var curCont = document.getElementById(ColRowID).innerHTML;
return curCont }


// =========================================================

function ckFunt(disTing) {
   document.getElementById(ColRowID).innerHTML= disTing;
   checkRow(ColRowID)
}

// =========================================================

function cellWhite() {
   document.getElementById(ColRowID).style.backgroundColor= "#FFFFFF";
}

function makeScroll(ColRowID) {
   //alert('1')
   document.getElementById('SU'+ColRowID).style.display='block';
   document.getElementById('SD'+ColRowID).style.display='block';
   //alert('2')
}

function makeUnScroll(ColRowID) {
   var stillFull = document.getElementById(ColRowID).innerHTML;
   var yesNo = stillFull.search("pixel.gif");
   if(yesNo > 0) {
      document.getElementById('SU'+ColRowID).style.display='none';
      document.getElementById('SD'+ColRowID).style.display='none';
   }
}

function sendPageName(){
   return $('currentPage').value
}

//#############################################################
//#### Move editor content to div       						  ####
//#############################################################



function onSaveFileSOHO(){
   
   
   var NewFinal = tinyMCE.activeEditor.getContent();
   

   img = tinyMCE.activeEditor.getParam("theme_href") + '/images/spacer.gif';
 
   NewFinal = NewFinal.replace(/<script[^>]*>\s*write(Flash|ShockWave|WindowsMedia|QuickTime|RealMedia)\(\{([^\)]*)\}\);\s*<\/script>/gi, '<img class="mceItem$1" title="$2" src="'+img+'" />');
  
   //alert(NewFinal)
   var re = new RegExp("<blink>","gi");
  
   var NewFinal = NewFinal.replace(re, " ");
   var re = new RegExp("</blink>","gi");
   var NewFinal = NewFinal.replace(re, " ");
   var re = new RegExp("<BLINK>","gi");
   var NewFinal = NewFinal.replace(re, " ");
   var re = new RegExp("</BLINK>","gi");
   var NewFinal = NewFinal.replace(re, " ");
   
//   var textArr = NewFinal.split('src="images/')
//   var textLen = textArr.length
//   for(var x=0; x<textLen; x++){
//      NewFinal = NewFinal.replace('src="images/', 'src="http://'+dot_com+'/images/');
//   }
   //alert(NewFinal);
   // Test final content
   //alert(NewFinal)
   
   // Set new content
   
   
   $(current_editing_area).innerHTML= '<blink>'+NewFinal+'</blink>';
   
   // Make sure links within text areas are not active
   disable_links();
   
   // Close editor
   toggleEditor('tiny_editor');
   
   // Flip header nav to page editor buttons
   parent.header.flip_header_nav('PAGE_EDITOR_LAYER');
   
   //$('tiny_editor_loading').style.display='none'
}


//=====================================================
//                 _              _ 
//       _  _ _ __| |___  __ _ __| |
//      | || | '_ \ / _ \/ _` / _` |
//       \_,_| .__/_\___/\__,_\__,_|
//           |_|                    
//=====================================================

function closeUploadWin() {
   show_hide_layer('upload_display','','hide','objectbar','','show');
}




//=====================================================
//        __                    _         __  __ 
//       / _|___ _ _ _ __    __| |_ _  _ / _|/ _|
//      |  _/ _ \ '_| '  \  (_-<  _| || |  _|  _|
//      |_| \___/_| |_|_|_| /__/\__|\_,_|_| |_|  
//=====================================================

function placeobject() {
   
	// --------------------------------------------------------------
	// Get current selected form data values
	// --------------------------------------------------------------

   var tEmailFormTo = $('emailaddr').value;
	tEmailFormTo = tEmailFormTo.toString();

	var tEmailFormFrom = $('emailfrom').value;
	tEmailFormFrom = tEmailFormFrom.toString();

	var tSubjectLine = $('subjectline').value;
	tSubjectLine = tSubjectLine.toString();

   var respFile = $('responsefile').selectedIndex;
	var tResponseFile = eval("$('responsefile').options["+respFile+"].value");
	tResponseFile = tResponseFile.toString();

   var goPage = $('pagego').selectedIndex;
   
	var tPageGo = eval("$('pagego').options["+goPage+"].value");
	tPageGo = tPageGo.toString();
	tPageGo = $('pagego').value;
	
	if ($('closewinY').checked) { tCloseWin = "yes"; }
	if ($('closewinN').checked) { tCloseWin = "no"; }

	// --------------------------------------------------------------
	// Replace semi-colons in multiple email "to" field with comma
	// to insure proper "sendmail" operation
	// --------------------------------------------------------------

	var re = new RegExp(";","gi");
  	tEmailFormTo = tEmailFormTo.replace(re, ",");

	var tDataName = $('savedbtable').value;
	tDataName = tDataName.toString();

   DisForm = $('availforms').selectedIndex;
	var formName = eval("$('availforms').options["+DisForm+"].value");

	// --------------------------------------------------------------
	// All values in memory; let's validate and accept if required
	// fields have been filled in.
	// --------------------------------------------------------------

	doOperation = 0;		// Set operation flag to NO for now

	// --------------------------------------------------------------
	// Setup look and feel of "Page Object"
	// --------------------------------------------------------------

	d = new Date();
	RandNum = 'FORMS';
	RandNum += d.getUTCHours();
	RandNum += d.getUTCMinutes();
	RandNum += d.getUTCSeconds();
	RandNum += d.getUTCMilliseconds();
	var RandNumForm = RandNum.toString();
	
	// Remove old form if this is an edit
	var oldId = $('oldFormVals').value
	if(oldId.length > 3){
	   
	   var oldEle = $(oldId);
	   
	   // Need to set new ColRowID, this item was not dropped
	   ColRowID = oldEle.parentNode.id
	   var daArea = $(ColRowID);
	   var oldForm = $(oldId)
	   
//	   alert(oldForm.nextSibling.nodeType)
//	   alert(oldForm.nodeType)
//	   alert(oldForm.id)

	   daArea.removeChild(oldForm.nextSibling);
	   daArea.removeChild(oldForm);
	   
	   // Kill old form vars
	   editType = 'NULL'
	   formVals = 'NULL'
	}
	
   
 	TextHeader = "<img src=images/text_header.gif width=199 height=15 border=0 align=center vspace=0 hspace=0 style='cursor: move;'><BR CLEAR=ALL>";
	var TableStart = '<div id="'+RandNumForm+'" class="droppedItem" style="Height: 150px; overflow: auto;">';
   var TableEnd = "</div><!-- ~~~ -->";

   var edit_button = "<button type=\"button\" class=\"blueButton\" onClick=\"editForm(\'"+RandNumForm+";"+tEmailFormTo+";"+tDataName+";"+formName+";"+tEmailFormFrom+";"+tSubjectLine+";"+tResponseFile+";"+tCloseWin+";"+tPageGo+"\');\"><span><span>Edit</span></span></button>";
//RandNum+';'+tEmailFormTo+';'+tDataName+';'+formName+';'+tEmailFormFrom+';'+tSubjectLine+';'+tResponseFile+';'+tCloseWin+';'+tPageGo
    EmailName = "<font color=darkblue>";
	if (tEmailFormTo != "") {
		if (tEmailFormTo == "NEWSLETTER_SIGNUP_PROCESS") {
			EmailName = EmailName+"<BR><FONT COLOR=RED><B>[ Newsletter Sign-up Form ]</B></FONT>";
		} else {
		   var formNameOnly = formName.substr(formName.lastIndexOf('\/')+1)
		   // Un-comment for form name extraction values
		   // (full path---last index of---extracted form name)
		   //alert(formName+'---'+formName.lastIndexOf('\/')+'---'+formNameOnly)
		   if(formName.lastIndexOf('\\') < 3)
		      formNameOnly = formName.substr(formName.lastIndexOf('\/')+1)
		   if(formNameOnly.length > 4)
			   EmailName = EmailName+"<BR><FONT COLOR=RED><B>[ Form: "+formNameOnly+" ]</B></FONT>";
			EmailName = EmailName+"<BR>Email To: "+tEmailFormTo;
		}
	}
	if (tDataName != "") {
		EmailName = EmailName+"<BR>Create Table: "+tDataName;
	}
	EmailName = EmailName+"</font></font>";

// go get current drop zone data
   dataTrueForm = getCurrentTrue();
   dataDataForm = getCurrentCont();

	// --------------------------------------------------------------
	// Do it or return error code to end-user
	// --------------------------------------------------------------

   sText = "<img src=pixel.gif width=199 height=1 border=0>"

	if (tEmailFormTo != "" || tDataName != "") {

		if (dataTrueForm > 0) {
			sText = TableStart+TextHeader+"<font style='font-family: Arial; font-size: 7pt;'><U>CUSTOM FORM OBJECT</U><br/><br/>This object should be centered on a row alone with no objects left or right of it for optimal display.<BR>"+edit_button+"<!-- ##CONTACTFORM;"+tEmailFormTo+";"+tDataName+";"+formName+";"+tEmailFormFrom+";"+tSubjectLine+";"+tResponseFile+";"+tCloseWin+";"+tPageGo+"## -->"+EmailName+TableEnd;
			doOperation = 1;
		} else {

		   var billy = dataDataForm;
			sText = billy+TableStart+TextHeader+"<font style='font-family: Arial; font-size: 7pt;'><U>CUSTOM FORM OBJECT</U><br/><br/>This object should be centered on a row alone with no objects left or right of it for optimal display.<BR>"+edit_button+"<!-- ##CONTACTFORM;"+tEmailFormTo+";"+tDataName+";"+formName+";"+tEmailFormFrom+";"+tSubjectLine+";"+tResponseFile+";"+tCloseWin+";"+tPageGo+"## -->"+EmailName+TableEnd;
			doOperation = 1;
		}

	} else {

		alert("You did not fill in an email address or a dB name to submit this form to.");
		doOperation = 0;

	}

	//------------------------------------------------------------------------------

	if (doOperation == 1) {

		if (formName == "") {
			alert("You must choose a form to use or click cancel to close window and exit forms library.");
			doOperation = 0;
		} else {
			ckFunt(sText);
			$('form_display').style.display='none';
		}

	} // End doOperation

} // End Place Function

function preview() {
      disOne = $('availforms').selectedIndex;
   	oVar = eval("$('availforms').options["+disOne+"].value");

		if (oVar != "") {
      	var url = "formlib/preview.php?lookat="+oVar;
         formType = "Newsletter";
         justPreview = "yes";
         ajaxDo(url, 'preview');
		} else {
			//alert('Please select a form to preview first.');
		}
}

function editForm(formProps) {
   parent.header.document.getElementById('PAGE_EDITOR_LAYER').style.visibility='hidden';
   var preFormVals = formProps.split(';');
   var oldEle = $(preFormVals[0]);
   ColRowID = oldEle.parentNode.id
   
   var datype = preFormVals[1];
    selectedformname =  preFormVals[3];
   if(datype == "NEWSLETTER_SIGNUP_PROCESS"){
      formType = "Newsletter";
   }else{
      formType = "Forms";
   }
   
   $('form_display').style.display='block';
	var url = "formlib/forms.php";
   editType = formProps;
   
   ajaxDo(url, 'form_display');
}

