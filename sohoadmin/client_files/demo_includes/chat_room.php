<?php
error_reporting('0');
session_start();

//if(!table_exists('simple_chat')){
//	mysql_query("create table simple_chat (prikey int(50) not null auto_increment primary key, chat_name varchar(255), message BLOB, date varchar(255), time int(60), ip_address varchar(255))");
//}

if(!table_exists("simple_chat")){
	create_table("simple_chat");
}

######################################################################
#######################################################################
###
### Based on Most Simple Ajax Chat Script (www.linuxuser.at)	
### by: Chris (chris[at]linuxuser.at) Contributors: Derek, BlueScreenJunky (http://forums.linuxuser.at/viewtopic.php?f=6&t=17)
###
#######################################################################
#######################################################################
$chatusername = $_COOKIE['chat_name'];
if($chatusername == ''){
	$chatusername = 'Guest';	
}

echo "<style type=\"text/css\">\n";
echo "input, textarea 	{ font-family: arial; color:black; background:#EFEFEF; font-size: 14px; }\n";
echo "#ccontent 		{ width:95%; text-align:left; margin-left:5%; }\n";
echo "#chatwindow 		{ border:1px solid #aaaaaa; padding:4px; background:#FFFFFF; color:black;  width:90%; height:410px; overflow:auto; font-family:courier new;}\n";
echo "#chatnick 		{ 	color:#0000FF; font-weight:bold; border:1px solid #aaaaaa; padding:4px; background:#EFEFEF;}\n";
echo "#chatmsg 		{ border:1px solid #aaaaaa; padding:4px; background:#EFEFEF; }\n";
echo ".chatmsgpar { 	margin:0 ; }\n";
echo ".chatnamespan { 	color:#0000FF; font-weight:bold; }\n";
echo ".chatmsgspan {	margin-left: 10px; }\n";
echo ".timespan {	margin-left: 4px; font-size: 10px; font-family:sans-serif; color: #AFAFAF; }\n";

echo "</style>\n";

echo "<div id=\"ccontent\">\n";
echo "	<p id=\"chatwindow\"> </p>\n";
echo "	<input id=\"chatnick\" type=\"text\" size=\"9\" maxlength=\"16\" value=\"".$chatusername."\">&nbsp;\n";
echo "	<input id=\"chatmsg\" type=\"text\" size=\"60\" maxlength=\"120\"  onkeyup=\"keyup(event.keyCode);\"> \n";
echo "	<input type=\"button\" value=\"send\" onclick=\"submit_msg();\" style=\"cursor:pointer;border:1px solid gray;\"><br><br>\n";
echo "	<br>\n";
echo "</div>\n";
?>


<script type="text/javascript">




/* Settings you might want to define */
	var waittime=800;		

/* Internal Variables & Stuff */
	document.getElementById("chatmsg").focus()
	document.getElementById("chatwindow").innerHTML = "loading...";

	var xmlhttp = false;
	var xmlhttp2 = false;


/* Request for Reading the Chat Content */
function ajax_read(url) {
	if(window.XMLHttpRequest){
		xmlhttp=new XMLHttpRequest();
		if(xmlhttp.overrideMimeType){
			xmlhttp.overrideMimeType('text/xml');
		}
	} else if(window.ActiveXObject){
		try{
			xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
		} catch(e) {
			try{
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			} catch(e){
			}
		}
	}

	if(!xmlhttp) {
		alert('Giving up :( Cannot create an XMLHTTP instance');
		return false;
	}

	xmlhttp.onreadystatechange = function() {
	if (xmlhttp.readyState==4) {
		var scrolbot = document.getElementById("chatwindow").scrollHeight;
		document.getElementById("chatwindow").innerHTML = xmlhttp.responseText;
		
		if(scrolbot != document.getElementById("chatwindow").scrollHeight){
			document.getElementById("chatwindow").scrollTop = document.getElementById("chatwindow").scrollHeight;
		}
		zeit = new Date(); 
		ms = (zeit.getHours() * 24 * 60 * 1000) + (zeit.getMinutes() * 60 * 1000) + (zeit.getSeconds() * 1000) + zeit.getMilliseconds(); 
		intUpdate = setTimeout("ajax_read('sohoadmin/client_files/simplechat.php?x=" + ms + "')", waittime)
		}
	}

	xmlhttp.open('GET',url,true);
	xmlhttp.send(null);
}

/* Request for Writing the Message */
function ajax_write(url){
	if(window.XMLHttpRequest){
		xmlhttp2=new XMLHttpRequest();
		if(xmlhttp2.overrideMimeType){
			xmlhttp2.overrideMimeType('text/xml');
		}
	} else if(window.ActiveXObject){
		try{
			xmlhttp2=new ActiveXObject("Msxml2.XMLHTTP");
		} catch(e) {
			try{
				xmlhttp2=new ActiveXObject("Microsoft.XMLHTTP");
			} catch(e){
			}
		}
	}

	if(!xmlhttp2) {
		alert('Giving up :( Cannot create an XMLHTTP instance');
		return false;
	}

	xmlhttp2.open('GET',url,true);
	xmlhttp2.send(null);
}

/* Submit the Message */
function submit_msg(){
	nick = document.getElementById("chatnick").value;
	msg = document.getElementById("chatmsg").value;

	if (nick == "") { 
		check = prompt("please enter username:"); 
		if (check === null) return 0; 
		if (check == "") check = "Guest"; 
		document.getElementById("chatnick").value = check;
		nick = check;
	} 

	document.getElementById("chatmsg").value = "";
	ajax_write("sohoadmin/client_files/simplechat.php?m=" + msg + "&n=" + nick);
}

/* Check if Enter is pressed */
function keyup(arg1) { 
	if (arg1 == 13) submit_msg(); 
}

/* Start the Requests! ;) */
var intUpdate = setTimeout("ajax_read('sohoadmin/client_files/simplechat.php')", waittime);

</script>

