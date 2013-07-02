// Javascript functions developed specifically for the Browse Templates module
// Included in: browse_templates.php


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

function makeObjectBrowse() {
   var httpRequest;

	if(window.XMLHttpRequest){
		httpRequest=new XMLHttpRequest();
		if(httpRequest.overrideMimeType){
			httpRequest.overrideMimeType('text/xml');
		}
	} else if(window.ActiveXObject){
		try{
			httpRequest=new ActiveXObject("Msxml2.XMLHTTP");
		} catch(e) {
			try{
				httpRequest=new ActiveXObject("Microsoft.XMLHTTP");
			} catch(e){
			}
		}
	}

	if(!httpRequest) {
		alert('Giving up :( Cannot create an XMLHTTP instance');
		return false;
	}
//   if (window.XMLHttpRequest) { // Mozilla, Safari, ...
//      httpRequest = new XMLHttpRequest();
//      if (httpRequest.overrideMimeType) {
//          httpRequest.overrideMimeType('text/xml');
//          // Or else you get 'object required' error in IE and it doesn't work
//      }
//   } else if (window.ActiveXObject) { // IE
//      try {
////          httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
//          httpRequest = new ActiveXObject("MicrosoftXMLDOM");
//      } catch (e) {
//          try {
//              httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
//          } catch (e) {}
//      }
//   }

   return httpRequest;
}

// The javascript variable 'request' now holds our request object.
// Without this, there's no need to continue reading because it won't work ;)
var request = makeObjectBrowse();

function ajaxDoBrowse(qryString, boxid) {
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
      
      // Is this the results end?
      var next_val = document.getElementById('next_start').value
      if(next_val.search("END") > 0){
         document.getElementById('next_btn').style.visibility = 'hidden';
         next_val = next_val.replace("END", "")
         document.getElementById('next_start').value = next_val
      }else{
         // Not end of template results, show next button
         document.getElementById('next_btn').style.visibility = 'visible';
      }
 
      setTimeout("setDisplay()", 1000);

      
      // Let images load for 1 sec b4 hiding load layer
      //setTimeout("Dialog.closeInfo()", 1000);
   }
}

function setDisplay(){
   
   // Pull limit_num
   var limitIndex = document.getElementById('limit_num').selectedIndex;
   var limit_num = document.getElementById('limit_num').options[limitIndex].value;
   var next_start = document.getElementById('next_start').value
   var daStart = next_start - Number(limit_num) + 1;
   if(daStart <= 1){
      daStart = 1;
      document.getElementById('prev_btn').style.visibility = 'hidden'
   }
   document.getElementById('num_results_display').innerHTML = 'Displaying '+daStart+' - '+next_start;
   //alert('something');
   
   var total_results = document.getElementById('total_results').value
   document.getElementById('num_results_display').innerHTML += '<br/>Total Results: '+total_results;
   
   document.getElementById('template_results').scrollTop = 0;
}


function getElementsByClass(searchClass,node,tag) {
	var classElements = new Array();
	if ( node == null )
		node = document;
	if ( tag == null )
		tag = '*';
	var els = node.getElementsByTagName(tag);
	var elsLen = els.length;
	var pattern = new RegExp('(^|\\\\s)'+searchClass+'(\\\\s|$)');
	for (i = 0, j = 0; i < elsLen; i++) {
		if ( pattern.test(els[i].className) ) {
			classElements[j] = els[i];
			j++;
		}
	}
	return classElements;
}

// Called when template_container box in results display is clicked
function view_template_details(addon_id) {
   // Pull Template Details for this template
   ajaxDoBrowse('template_details.ajax.php?addonid='+addon_id, 'template_details');

   // Show Template Details box
   showid('template_details');
}

function loadTemplates(sort_type){
  
//   document.getElementById('template_results').scrollTo(0,0);
   
   // Needed for post ajax call in parseInfo()
   next_display = sort_type;

   // Show loading layer
   openInfoDialog()
   
   // Pull selected category
   var catIndex = document.getElementById('category').selectedIndex;
   var showCats = eval("document.getElementById('category').options["+catIndex+"].value");
   
   // Pull selected color
   var colorIndex = document.getElementById('show_colors').selectedIndex;
   var showColor = eval("document.getElementById('show_colors').options["+colorIndex+"].value");
   
   // Pull sort by
   var sortIndex = document.getElementById('sort_by').selectedIndex;
   var sortBy = eval("document.getElementById('sort_by').options["+sortIndex+"].value");
   
   // Pull limit_num
   var limitIndex = document.getElementById('limit_num').selectedIndex;
   var limit_num = document.getElementById('limit_num').options[limitIndex].value;
   
   // Is this a next/prev request?
   if(sort_type == "next"){
      if(document.getElementById('next_start').value.length > 0){
         var next_start = document.getElementById('next_start').value
         document.getElementById('prev_btn').style.visibility = 'visible'
      }
   }else if(sort_type == "prev"){
      if(document.getElementById('next_start').value.length > 0){
         var next_start = document.getElementById('next_start').value
         next_start = next_start - (limit_num*2);
         //alert(next_start)
      }
   }else{
      // Options have changed, start count over
      var next_start = 0;
      
      document.getElementById('next_btn').style.visibility = 'visible'
      document.getElementById('prev_btn').style.visibility = 'hidden'
   }
   
   // After prev click, is start num < 0?
   // If so, start at 0
   if(next_start < 0){
      next_start = 0;
      document.getElementById('prev_btn').style.visibility = 'hidden'
   }
   
   ajaxDoBrowse('template_results.ajax.php?category='+showCats+'&next_start='+next_start+'&color='+showColor+'&sortby='+sortBy+'&limit_num='+limit_num, 'template_results');
}

function sortResults(searchThis){
   //alert('ok---'+searchThis)
   
   var category_list = searchThis.split('-');
   var cats_len = category_list.length;
   
   var templates = getElementsByClass('template_container-off');
   var num_temps = templates.length;
   for (i = 0; i < 5; i++) {
      //alert(templates[i].childNodes[3].nodeName)
      temp_name = templates[i].childNodes[3].innerHTML;
      temp_name = temp_name.toLowerCase()
      for (c = 0; c < cats_len; c++) {
         if(temp_name.search(category_list[c]) == -1){
            templates[i].style.display='none'
         }
      }
   }
   hideid('loading_overlay');
   //alert(templates.length)
}

function openInfoDialog() {
   //alert('ok')
//   Dialog.info("Loading Templates...", {windowParameters: {className: "alert_lite",width:250, height:50}, showProgress: true});
}