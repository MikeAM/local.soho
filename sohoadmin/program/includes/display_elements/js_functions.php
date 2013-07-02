<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

?>
function MM_openBrWindow(theURL,winName,features) { //v2.0
	window.open(theURL,winName,features);
}

function find_object(n, d) { //v3.0
// H O O K: Updated for IE and Mozilla
	var p,i,x;
	if(!d) d=document;
	if((p=n.indexOf("?"))>0&&parent.frames.length) {
		d=parent.frames[n.substring(p+1)].document;
		n=n.substring(0,p);
	}
	if(!(x=d[n])&&d.all) x=d.all[n];
	for (i=0;!x&&i<d.forms.length;i++)
		x=d.forms[i][n];
	for(i=0;!x&&d.layers&&i<d.layers.length;i++)
		x=find_object(n,d.layers[i].document);
	if(!x && d.getElementById)
		x=d.getElementById(n);
	return x;
}


function show_hide_layer() { //v3.0
	var i, p, v, obj, args = show_hide_layer.arguments;
	for (i=0; i<(args.length-2); i+=3) if ((obj=find_object(args[i]))!=null) { v=args[i+2];
	if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
	obj.visibility=v; }
}

function open_bar_window(theURL,winName,features) { //v2.0
	window.open(theURL,winName,features);
}



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
//window.onerror = killErrors;


if(top.location != location){
	if( !CURPAGENAME ) {
	   	var CURPAGENAME = find_object('CURPAGENAME', parent.frames.footer.document);
	}
}

//---------------------------------------------------------------------------------------------------------
//    ___   _       __      __ _           _
//   |   \ (_)__ __ \ \    / /(_) _ _   __| | ___ __ __ __ ___
//   | |) || |\ V /  \ \/\/ / | || ' \ / _` |/ _ \\ V  V /(_-<
//   |___/ |_| \_/    \_/\_/  |_||_||_|\__,_|\___/ \_/\_/ /__/
//
//    DHTML Window script- Copyright Dynamic Drive (http://www.dynamicdrive.com)
//    For full source code, documentation, and terms of usage,
//    visit http://www.dynamicdrive.com/dynamicindex9/dhtmlwindow.htm
//---------------------------------------------------------------------------------------------------------
var dragapproved=false;
var minrestore=0;
var initialwidth,initialheight;
var ie5=document.all&&document.getElementById;
var ns6=document.getElementById&&!document.all;

function iecompattest() {
   return (!window.opera && document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body;
}

function drag_drop(e) {
   if ( ie5&&dragapproved&&event.button==1 ) {
      document.getElementById("dwindow").style.left=tempx+event.clientX-offsetx+"px";
      document.getElementById("dwindow").style.top=tempy+event.clientY-offsety+"px";
   } else if ( ns6&&dragapproved ) {
      document.getElementById("dwindow").style.left=tempx+e.clientX-offsetx+"px";
      document.getElementById("dwindow").style.top=tempy+e.clientY-offsety+"px";
   }
}

function initializedrag(e){
   offsetx=ie5? event.clientX : e.clientX;
   offsety=ie5? event.clientY : e.clientY;
   document.getElementById("dwindowcontent").style.display="none"; //extra
   tempx=parseInt(document.getElementById("dwindow").style.left);
   tempy=parseInt(document.getElementById("dwindow").style.top);

   dragapproved=false;
   document.getElementById("dwindow").onmousemove=drag_drop;
}

function loadwindow(url,width,height,curobj) {
   if ( !ie5 && !ns6 ) {
      window.open(url,"","width=width,height=height,scrollbars=1");
   } else {
      document.getElementById("cframe").src=url;
      document.getElementById("dwindow").style.display='';
      document.getElementById("dwindow").style.width=initialwidth=width+"px";
      document.getElementById("dwindow").style.height=initialheight=height+"px";
      document.getElementById("dwindow").style.right=getposOffset(curobj, "right")+"px";
      document.getElementById("dwindow").style.top=getposOffset(curobj, "top")+"px";
   }
}

function loadwindowUP(url,width,height,curobj) {
   if ( !ie5 && !ns6 ) {
      window.open(url,"","width=width,height=height,scrollbars=1");
   } else {
      document.getElementById("cframe").src=url;
      document.getElementById("dwindow").style.display='';
      document.getElementById("dwindow").style.width=initialwidth=width+"px";
      document.getElementById("dwindow").style.height=initialheight=height+"px";
      document.getElementById("dwindow").style.right=getposOffset(curobj, "right")+"px";
      document.getElementById("dwindow").style.middle=getposOffset(curobj, "middle")+"px";
   }
}

function maximize() {
   if ( minrestore == 0 ) {
      minrestore=1; //maximize window
      document.getElementById("maxname").setAttribute("src","../includes/display_elements/graphics/icon-restore_window.gif");
      document.getElementById("dwindow").style.width=ns6? window.innerWidth-20+"px" : iecompattest().clientWidth+"px";
      document.getElementById("dwindow").style.height=ns6? window.innerHeight-20+"px" : iecompattest().clientHeight+"px";
   } else {
      minrestore=0; //restore window
      document.getElementById("maxname").setAttribute("src","../includes/display_elements/graphics/icon-maximize.gif");
      document.getElementById("dwindow").style.width=initialwidth;
      document.getElementById("dwindow").style.height=initialheight;
   }
   document.getElementById("dwindow").style.left=ns6? window.pageXOffset+"px" : iecompattest().scrollLeft+"px";
   document.getElementById("dwindow").style.top=ns6? window.pageYOffset+"px" : iecompattest().scrollTop+"px";
}

function closeit() {
   document.getElementById("dwindow").style.display="none";
}

function stopdrag() {
   dragapproved=false;
   document.getElementById("dwindow").onmousemove=null;
   document.getElementById("dwindowcontent").style.display=""; //extra
}


//---------------------------------------------------------------------------------------------------------
//    _  _       _         ___
//   | || | ___ | | _ __  | _ \ ___  _ __  _  _  _ __  ___
//   | __ |/ -_)| || '_ \ |  _// _ \| '_ \| || || '_ \(_-<
//   |_||_|\___||_|| .__/ |_|  \___/| .__/ \_,_|| .__//__/
//                 |_|              |_|         |_|
//    Overlapping Content link- 頄ynamic Drive (www.dynamicdrive.com)
//    This notice must stay intact for legal use.
//    Visit http://www.dynamicdrive.com/ for full source code
//---------------------------------------------------------------------------------------------------------
function getposOffset(overlay, offsettype){
   var totaloffset=(offsettype=="left")? overlay.offsetLeft : overlay.offsetTop;
   var parentEl=overlay.offsetParent;
   while (parentEl!=null) {
      totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
      parentEl=parentEl.offsetParent;
   }
   return totaloffset;
}

function overlayclose(subobj){
   document.getElementById(subobj).style.display="none"
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
   }
}

//---------------------------------------------------------------------------------------------------
//     _____                                 _   _    _
//    / ____|                               | | | |  | |
//   | |  __   ___  _ __    ___  _ __  __ _ | | | |  | | ___   ___
//   | | |_ | / _ \| '_ \  / _ \| '__|/ _` || | | |  | |/ __| / _ \
//   | |__| ||  __/| | | ||  __/| |  | (_| || | | |__| |\__ \|  __/
//    \_____| \___||_| |_| \___||_|   \__,_||_|  \____/ |___/ \___|
//
//---------------------------------------------------------------------------------------------------

// Flips single element on/off based on current state
// Accepts: ID of target element, whether to use visibility or display style (optional, 'display' by default)
function toggleid(targetid, fliphow) {

   if ( fliphow == "visibility" ) {
      var isnow = document.getElementById(targetid).style.visibility;
      if ( isnow == 'visible' ) {
         document.getElementById(targetid).style.visibility='hidden';
         return true;
      } else {
         document.getElementById(targetid).style.visibility='visible';
         return true;
      }


   } else {
      var isnow = document.getElementById(targetid).style.display;
      if ( isnow == 'block' ) {
         document.getElementById(targetid).style.display='none';
         return true;
      } else {
         document.getElementById(targetid).style.display='block';
         return true;
      }
   }
} // End toggleid() function

// For places that call for a bit more exacting control vs. toggleid
function hideid(thingid) {
   document.getElementById(thingid).style.display = 'none';
}
function showit(thingid) {
   document.getElementById(thingid).style.display = 'block';
}

function showid(thingid) {
   document.getElementById(thingid).style.display = 'block';
}

// Especially handy for flipping bg color of table rows onmouseover, turning one tab on and others off onclick, etc.
function setClass(thingid, new_classname) {
   document.getElementById(thingid).className = new_classname;
}


// Checks/unchecks a form checkbox field
// Optional: Pass true/false as second checkuncheck arg
function toggle_checkbox(targetid, checkuncheck) {

   if ( checkuncheck == "check" ) {
      // Set: CHECK
      document.getElementById(targetid).checked = true;
      return true;

   } else if ( checkuncheck == "uncheck" ) {
      // Set: UNCHECK
      document.getElementById(targetid).checked = false;
      return true;

   } else {
      // TOGGLE: Set to opposite of whatever it is now
      var isnow = document.getElementById(targetid).checked;
      if ( isnow == true ) {
         document.getElementById(targetid).checked = false;
         return true;
      } else {
         document.getElementById(targetid).checked = true;
         return true;
      }
   }
}

// Use for "other (specify)" options in drop-downs and such
function ifShow(fieldid, chkvalue, boxid) {
   if ( $(fieldid).value == chkvalue ) {
      showid(boxid);
   } else {
      hideid(boxid);
   }
}

// Used originally for "if box is checked fadein else fadeout" in add/edit admin user > plugin features
function ifChecked_setClass(fieldid, boxid, onclass, offclass) {
   var isnow = $(fieldid).checked;

   if ( isnow == true ) {
      setClass(boxid, onclass);
   } else {
      setClass(boxid, offclass);
   }
}

// Loops through radio button group and returns value of checked radio
// Use: When you want to pass the radio value via js when changed but can't
//      use onchange b/c you're allowing them to click the text next to the radio as well as the radio itself
function radiovalue(formname, radiogroup) {
   var max = eval('document.'+formname+'.'+radiogroup+'.length'); // Faster defined up here...doesn't have to recaculate every loop iteration
   for ( i=0; i < max; i++ ) {
      if ( eval('document.'+formname+'.'+radiogroup+'[i].checked') == true ) {
         return eval('document.'+formname+'.'+radiogroup+'[i].value');
      }
   }
}

//---------------------------------------------
// Shortcut for document.getElementById :)
//---------------------------------------------
//function $() {
//  var elements = new Array();
//
//  for (var i = 0; i < arguments.length; i++) {
//    var element = arguments[i];
//    if (typeof element == 'string')
//      element = document.getElementById(element);
//
//    if (arguments.length == 1)
//      return element;
//
//    elements.push(element);
//  }
//
//  return elements;
//}


// Workaround for IE's infinite z-index issue
// Hide all dropdown boxes
// OPTIONAL: Pass an ids to exclude
function hide_dropdowns(exclude) {
   dropdowns = document.getElementsByTagName("select");
   if ( exclude != "" ) {
      // Test for excluded id
      for ( i = 0; i < dropdowns.length; i++ ) {
         if ( dropdowns[i].id != exclude ) {
            dropdowns[i].style.display = 'none';
         }
      }
   } else {
      // Hide all dropdowns, don't check for exception
      for ( i = 0; i < dropdowns.length; i++ ) {
         dropdowns[i].style.display = 'none';
      }
   }
}

// Show all dropdown boxes
function show_dropdowns() {
   dropdowns = document.getElementsByTagName("select");
   for ( i = 0; i < dropdowns.length; i++ ) {
      dropdowns[i].style.display = 'inline';
   }
}


/*
    Written by Jonathan Snook, http://www.snook.ca/jonathan
    Add-ons by Robert Nyman, http://www.robertnyman.com
*/
function getElementsByClassName(oElm, strTagName, oClassNames){
    var arrElements = (strTagName == "*" && oElm.all)? oElm.all : oElm.getElementsByTagName(strTagName);
    var arrReturnElements = new Array();
    var arrRegExpClassNames = new Array();
    if(typeof oClassNames == "object"){
        for(var i=0; i<oClassNames.length; i++){
            arrRegExpClassNames.push(new RegExp("(^|\\s)" + oClassNames[i].replace(/\-/g, "\\-") + "(\\s|$)"));
        }
    }
    else{
        arrRegExpClassNames.push(new RegExp("(^|\\s)" + oClassNames.replace(/\-/g, "\\-") + "(\\s|$)"));
    }
    var oElement;
    var bMatchesAll;
    for(var j=0; j<arrElements.length; j++){
        oElement = arrElements[j];
        bMatchesAll = true;
        for(var k=0; k<arrRegExpClassNames.length; k++){
            if(!arrRegExpClassNames[k].test(oElement.className)){
                bMatchesAll = false;
                break;
            }
        }
        if(bMatchesAll){
            arrReturnElements.push(oElement);
        }
    }
    return (arrReturnElements)
}

// Open a new window with standard features
// Defaults to maximized height
// popup_window(url to open [window title, [,width [,height]]]
function popup_window(theUrl, title, width, height, toolbars) {

   if ( width == "" ) { width = screen.width; }
   if ( height == "" ) { height = screen.height; }
   if ( toolbars == "" ) { toolbars = 'yes'; }

   if ( toolbars == 'yes' ) {
      // Yes, show toolbars in new window
      toolbars_str = 'location=yes, toolbar=1, status=1, menubar=1,';
   } else {
      toolbars_str = 'location=no, toolbar=0, status=0, menubar=0,';
   }

   if ( document.all ) {
      window.open(theUrl);
   } else {
      window.open(theUrl, title, 'scrollbars=yes, resizable=yes,'+toolbars_str+' width='+width+',height='+height);
   }
}
