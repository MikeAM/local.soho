<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

session_start();
?>

<script language="javascript">

var Base64 = { 
    // private property 
    _keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", 
    // public method for encoding 
    encode : function (input) { 
        var output = ""; 
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4; 
        var i = 0; 
        input = Base64._utf8_encode(input); 
        while (i < input.length) { 
            chr1 = input.charCodeAt(i++); 
            chr2 = input.charCodeAt(i++); 
            chr3 = input.charCodeAt(i++); 
            enc1 = chr1 >> 2; 
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4); 
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6); 
            enc4 = chr3 & 63; 
            if (isNaN(chr2)) { 
                enc3 = enc4 = 64; 
            } else if (isNaN(chr3)) { 
                enc4 = 64; 
            } 
            output = output + 
            this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) + 
            this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4); 
        } 
        return output; 
    }, 
    decode : function (input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;
        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
        while (i < input.length) {
            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));
            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;
            output = output + String.fromCharCode(chr1);
            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }
        }
        output = Base64._utf8_decode(output);
        return output;
    },
    _utf8_decode : function (utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;
        while ( i < utftext.length ) {
            c = utftext.charCodeAt(i);
            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i+1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i+1);
                c3 = utftext.charCodeAt(i+2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }
        }
        return string;
    },
    // private method for UTF-8 encoding 
    _utf8_encode : function (string) { 
        string = string.replace(/\r\n/g,"\n"); 
       var utftext = ""; 
        for (var n = 0; n < string.length; n++) { 
            var c = string.charCodeAt(n);      
            if (c < 128) { 
                utftext += String.fromCharCode(c); 
            } 
            else if((c > 127) && (c < 2048)) { 
                utftext += String.fromCharCode((c >> 6) | 192); 
                utftext += String.fromCharCode((c & 63) | 128); 
            } 
            else { 
                utftext += String.fromCharCode((c >> 12) | 224); 
                utftext += String.fromCharCode(((c >> 6) & 63) | 128); 
                utftext += String.fromCharCode((c & 63) | 128); 
            } 
        } 
        return utftext; 
    } 
} 

function camcancel() { 
	document.edit.cmd2.value = ''; 
	//location.href='simple.php'; 
} 

function camsmile() { 
	document.edit.cmd2.value = ''; 
	document.getElementById('realcontent').value = Base64.encode(document.getElementById('simplecontent').value); 
	document.edit.submit(); 
} 

function camsmiler() {	 
document.getElementById('simplecontent').focus();
	document.getElementById('getback').value = window.document.getElementById('simplecontent').scrollTop; 
	document.getElementById('realcontent').value = Base64.encode(document.getElementById('simplecontent').value); 
	document.edit.submit(); 
} 

function lcmd(command) { 
	document.getElementById('cmd').value = command; 
} 

function pncmd(command) { 
	document.getElementById('cmd').value = command; 
	document.exec.submit(); 
} 

function chdir(newdir) { 
	var tnewdir = 'cs '+newdir; 
	document.getElementById('cmd').value = tnewdir; 
	document.exec.submit(); 
} 


   var lineObjOffsetTop = 2;
   function createTextAreaWithLines(id){
      var el = document.createElement('DIV');
      
      var ta = document.getElementById(id);
      ta.parentNode.insertBefore(el,ta);
      el.appendChild(ta);
      el.className='textAreaWithLines';
      //el.id='nums';
      el.style.width = (ta.offsetWidth + 30) + 'px';
      ta.style.position = 'relative';
      ta.style.left = '30px';
      ta.style.zIndex = '900';
      el.style.zIndex = '800';
      el.style.height = (ta.offsetHeight +1) + 'px';
      el.style.overflow='hidden';
      el.style.position = 'absolute';
      el.style.width = (ta.offsetWidth + 30) + 'px';
      el.style.fontFamily = 'Courier New'; 
      el.style.fontSize = '8pt';
      
      var lineObj = document.createElement('DIV');
      lineObj.style.position = 'absolute';
      lineObj.style.top = lineObjOffsetTop + 'px';
      lineObj.style.left = '-1px';
      lineObj.style.width = '27px';
      lineObj.style.zIndex = '380';
      el.insertBefore(lineObj,ta);
      lineObj.style.height = (el.offsetHeight + 12) + 'px';
      lineObj.style.textAlign = 'right';
      lineObj.className='lineObj';
      lineObj.style.background = '#808080';
      var string = '';
      for(var no=1;no<999;no++){
         if(string.length>0)string = string + '<br>';
         string = string + no;
      }
      
      //ta.onkeydown = function() { positionLineObj(lineObj,ta); };
      ta.onmousedown = function() { positionLineObj(lineObj,ta); };
      ta.onscroll = function() { positionLineObj(lineObj,ta); };
      ta.onblur = function() { positionLineObj(lineObj,ta); };
      ta.onfocus = function() { positionLineObj(lineObj,ta); };
      ta.onmouseover = function() { positionLineObj(lineObj,ta); };
      
<?php
if(!eregi("MSIE", $_SERVER['HTTP_USER_AGENT'])){
?>      
		var myInterval = window.setInterval(function (a,b) {
			positionLineObj(lineObj,ta);
		},750);
<?php
}
?> 
      
      lineObj.innerHTML = string;      
   }
   
   function positionLineObj(obj,ta){
      obj.style.top = (ta.scrollTop * -1 + lineObjOffsetTop) + 'px';            
   }

function CodePress() { 
	createTextAreaWithLines('simplecontent');
	var contentzz = document.getElementById('simplecontent').value; 
	//alert(contentzz);
	contentzz = Base64.decode(contentzz);

	document.getElementById('simplecontent').value = contentzz; 
	document.getElementById('simplecontent').focus();
} 
	
	

function tab_to_tab(e,el) {
    //A function to capture a tab keypress in a textarea and insert 4 spaces and NOT change focus.
    //9 is the tab key, except maybe it's 25 in Safari? oh well for them ...
    if(e.keyCode==9){
        var oldscroll = el.scrollTop; //So the scroll won't move after a tabbing
        e.returnValue=false;  //This doesn't seem to help anything, maybe it helps for IE
        //Check if we're in a firefox deal
      	if (el.setSelectionRange) {
      	    var pos_to_leave_caret=el.selectionStart+1;
      	    //Put in the tab
     	    el.value = el.value.substring(0,el.selectionStart) + '	' + el.value.substring(el.selectionEnd,el.value.length);
            //There's no easy way to have the focus stay in the textarea, below seems to work though
            setTimeout("var t=document.getElementById('simplecontent'); t.focus(); t.setSelectionRange(" + pos_to_leave_caret + ", " + pos_to_leave_caret + ");", 0);
      	}
      	//Handle IE
      	else {
      		// IE code, pretty simple really
      		document.selection.createRange().text='	';
      	}
        el.scrollTop = oldscroll; //put back the scroll
    }
}


function camenter(e,el) {
	if(e.shiftKey==true&&e.keyCode==13){
        var oldscroll = el.scrollTop; //So the scroll won't move after a tabbing
        e.returnValue=false;  //This doesn't seem to help anything, maybe it helps for IE
        //Check if we're in a firefox deal
      	if (el.setSelectionRange) {
      	    var pos_to_leave_caret=el.selectionStart+5;
      	    //Put in the tab
     	    el.value = el.value.substring(0,el.selectionStart) + '<br/>' + el.value.substring(el.selectionEnd,el.value.length);
            //There's no easy way to have the focus stay in the textarea, below seems to work though
            setTimeout("var t=document.getElementById('simplecontent'); t.focus(); t.setSelectionRange(" + pos_to_leave_caret + ", " + pos_to_leave_caret + ");", 0);
      	}
      	//Handle IE
      	else {
      		// IE code, pretty simple really
      		document.selection.createRange().text='	';
      	}
        el.scrollTop = oldscroll; //put back the scroll
	}
} 

function camsave(e,el) {
    //A function to capture a tab keypress in a textarea and insert 4 spaces and NOT change focus.
    //9 is the tab key, except maybe it's 25 in Safari? oh well for them ...
	if(e.ctrlKey==true&&e.keyCode==83){
   //83=s  17=ctrl        var oldscroll = el.scrollTop; //So the scroll won't move after a tabbing
        e.returnValue=false;  //This doesn't seem to help anything, maybe it helps for IE
        //Check if we're in a firefox deal
      	if (el.setSelectionRange) {
				camsmiler(); 
			} 
	} 
} 


</script> 