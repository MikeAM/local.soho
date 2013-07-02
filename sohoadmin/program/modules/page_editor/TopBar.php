
<link rel="stylesheet" href="http://ultra.soholaunch.com/sohoadmin/program/includes/product_buttons-ultra.css">
<script language="JavaScript">

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


// <!-- ----------------------------------------------------------------------------- -->
// <!-- ----------------------------------------------------------------------------- -->

function StatusReset() {
parent.footer.CURPAGENAME.innerHTML = '';
parent.footer.SUBPAGEOF.innerHTML = "";
parent.footer.PAGESTAT.innerHTML = '';
}

function redirect() {
strLink = "main_menu.php?=SID";
   parent.footer.orboff();
   parent.body.location.href=strLink;
}

function navigateHome() {
strLink = "main_menu.php?=SID";
   parent.body.location.href=strLink;
   var p = 'Main Menu';
   parent.frames.footer.setPage(p);
}

function navigateOpen() {
strLink = "modules/open_page.php?=";
   parent.body.location.href=strLink;
}

function logout() {
strLink2 = "header.php?logout=logout";
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
	open_new_window('http://ultra.soholaunch.com/index.php?nosessionkill=1','VIEWSITE','width=780,height=450, scrollbars=yes,resizable=yes,toolbar=yes');
}

function page_properties() {
   if(!document.all){
	   parent.body.ShowPageProps();
   }else{
      MM_showHideLayers('pageproperties?body','','show','saveaslayer?body','','hide');
      document.getElementById('save.style.display = '';
   }
}

function save_as_layer() {
   if(!document.all){
      parent.body.ShowSaveAs();
   }else{
      MM_showHideLayers('pageproperties?body','','hide','saveaslayer?body','','show');
      document.getElementById('save.style.display = '';
   }
}


</script>

<!-- ----------------------------------------------------------------------------- -->
<!-- CREATE CUSTOM JScript FUNCTIONS TO SAVE PAGE DATA IN "MAIN EDIT" WINDOW FRAME -->
<!-- ----------------------------------------------------------------------------- -->

<script LANGUAGE="javascript">

function savePage(redirect) {

	var confirm_save = 1;

	if (redirect != "page_editor.php") {
		var tiny = window.confirm('Do you wish to save the changes you have made?\n\nClick "OK" to Save changes now OR\nClick "Cancel" to discard changes.');
		if (tiny != false) { var confirm_save = 1; } else { var confirm_save = 0; }
	}else{
	   var confirm_save = 1;
	}

   if(!document.all){
//      is_save = redirect.search("editor");
//      if(is_save>0){
//         redirect='page_editor.php';
//      }
   	if (confirm_save == 1) {
   		alert(confirm_save);
         //show_hide_layer('ProgressBarSave?body','','show');			// Display Save Progress Bar in Edit Window
   		//parent.footer.CURPAGENAME.innerHTML = 'Saving Changes...';	// Update Status Bar
   		var saveText = "";
   		var tempValue = "";

   		var saveText = GetSaveForm();			// Get information from Page Editor
alert(saveText);

   		                   tempValue = TouchMe('TDR1C1');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R1C1 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR1C2');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R1C2 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR1C3');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R1C3 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR2C1');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R2C1 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR2C2');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R2C2 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR2C3');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R2C3 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR3C1');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R3C1 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR3C2');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R3C2 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR3C3');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R3C3 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR4C1');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R4C1 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR4C2');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R4C2 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR4C3');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R4C3 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR5C1');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R5C1 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR5C2');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R5C2 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR5C3');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R5C3 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR6C1');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R6C1 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR6C2');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R6C2 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR6C3');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R6C3 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR7C1');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R7C1 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR7C2');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R7C2 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR7C3');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R7C3 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR8C1');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R8C1 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR8C2');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R8C2 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR8C3');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R8C3 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR9C1');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R9C1 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR9C2');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R9C2 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR9C3');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R9C3 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR10C1');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R10C1 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR10C2');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R10C2 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                   tempValue = TouchMe('TDR10C3');
							is_txtarea = tempValue.search('<textarea');
							if(is_txtarea>0){
								var textArr = tempValue.split('<textarea')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<textarea','<sohotextarea');
									tempValue = tempValue.replace('</textarea>','</sohotextarea>');
								}
							}
                   saveText = saveText+"<TEXTAREA NAME=R10C3 STYLE=display: none>"+tempValue+"</TEXTAREA>";
            saveText = saveText+"<input type=hidden name=redirect value="+redirect+"><input type=hidden name=serial_number value=''><input type=hidden name=dot_com value='ultra.soholaunch.com'>";
		
   		//parent.body.saveForm.innerHTML = saveText;			// Finalize "Save Data"
   		SendSaveText(saveText);			// Finalize "Save Data"
   		GoToSave();							// Save current Page

   	} else {
         //parent.body.ShowNoSave();
   //      show_hide_layer('NOSAVE_LAYER','','show');			// Display NO SAVE Loading image in Edit Window
         if(redirect == "preview"){
            var daPage = sendPageName();
            var prev_path = "modules/page_editor/page_editor.php?previewWindow=1&currentPage="+daPage+"&=SID";
   		   parent.body.location.href=prev_path;
   		}else{
   		   parent.body.location.href="modules/page_editor/"+redirect;
   		}
   	}
   }else{
	if (confirm_save == 1) {
        MM_showHideLayers('ProgressBarSave?body','','show');			// Display Save Progress Bar in Edit Window
		parent.frames.footer.CURPAGENAME.innerHTML = 'Saving Changes...';	// Update Status Bar
		var saveText = "";
		var tempValue = "";
		var saveText = document.getElementById('saveForm').innerHTML;			// Get information from Page Editor
		var daPage = sendPageName();
		                    tempValue = document.getElementById('TDR1C1').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R1C1 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR1C2').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R1C2 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR1C3').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R1C3 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR2C1').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R2C1 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR2C2').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R2C2 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR2C3').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R2C3 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR3C1').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R3C1 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR3C2').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R3C2 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR3C3').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R3C3 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR4C1').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R4C1 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR4C2').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R4C2 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR4C3').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R4C3 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR5C1').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R5C1 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR5C2').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R5C2 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR5C3').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R5C3 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR6C1').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R6C1 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR6C2').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R6C2 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR6C3').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R6C3 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR7C1').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R7C1 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR7C2').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R7C2 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR7C3').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R7C3 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR8C1').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R8C1 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR8C2').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R8C2 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR8C3').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R8C3 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR9C1').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R9C1 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR9C2').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R9C2 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR9C3').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R9C3 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR10C1').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R10C1 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR10C2').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R10C2 STYLE=display: none>"+tempValue+"</TEXTAREA>";
                    tempValue = document.getElementById('TDR10C3').innerHTML;
							is_txtarea = tempValue.search('<TEXTAREA');
							if(is_txtarea>0){
								var textArr = tempValue.split('<TEXTAREA')
								var textLen = textArr.length
								for(var x=0; x<textLen; x++){
									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');
									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');
								}
							}
                 saveText = saveText+"<TEXTAREA NAME=R10C3 STYLE=display: none>"+tempValue+"</TEXTAREA>";
            saveText = saveText+"<input type=hidden name=redirect value="+redirect+"><input type=hidden name=serial_number value=''><input type=hidden name=dot_com value='ultra.soholaunch.com'>";

		document.getElementById('saveForm.innerHTML = saveText;			// Finalize "Save Data"
		document.getElementById('save.submit();							// Save current Page

	} else {

        MM_showHideLayers('NOSAVE_LAYER?body','','show');			// Display NO SAVE Loading image in Edit Window
		document.getElementById('location.href="main_menu.php?";
	}
}

} // End Save Page Function

// <!-- ----------------------------------------------------------------------------- -->
// <!-- ----------------------------------------------------------------------------- -->





</SCRIPT>

<!------------------------PAGE_EDITOR_LAYER------------------------>
<div  background="http://<?php echo $_SESSION['docroot_url']; ?>/sohoadmin/program/includes/images/top-bg.png" style="height:36px;position:relative; visibility: visible; display:block;margin: 0px; vertical-align: top;z-index:999999;">
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
   <td align="left" valign="top" style="padding-top: 5px;">
    <table border="0" cellpadding="0" cellspacing="0" class="upper_navbar">
     <tr>
      <td align="center" valign="top">
       <a href="javascript:void(0);" class="blueButton"  onClick="savePage('../open_page.php');"><span>Edit&nbsp;Page(s)</span></a>

      </td>
      <td align="center" valign="top">
       <button type="button" onClick="savePage('page_editor.php');" class="greenButton"  ><span><span>Save&nbsp;Page</span></span></button>

      </td>
      <td align="center" valign="top">
       <a href="javascript:void(0);" class="greenButton"  onClick="save_as_layer();"><span>Save&nbsp;As</span></a>

      </td>
      <td align="center" valign="top">
       <a href="javascript:void(0);" class="blueButton"  onClick="savePage('preview');"><span>Preview&nbsp;Page</span></a>

      </td>
      <td align="center" valign="top">
       <a href="javascript:void(0);" class="blueButton"  onClick="page_properties();"><span>Page&nbsp;Properties</span></a>

      </td>
      <td align="center" valign="top">
       <a href="javascript:void(0);" class="blueButton"  onClick="savePage('../upload_files.php');"><span>Upload&nbsp;Files</span></a>

      </td>
      <td align="center" valign="top">
       <a href="javascript:void(0);" class="blueButton"  onClick="savePage('../../dashboard.php');"><span><b>Main&nbsp;Menu</b></span></a>

      </td>
     </tr>
    </table>
   </td>
  </tr>
 </table>
</div>




<!------------------------PAGE_EDITOR_LAYER_NO_SAVE------------------------>
<div id="PAGE_EDITOR_LAYER_NO_SAVE" class="upper_navbar" style="margin-top: 0px; vertical-align: top;">
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
   <td align="left" valign="top" style="padding-top: 5px;">
    <table border="0" cellpadding="0" cellspacing="0" class="upper_navbar">
     <tr>
      <td align="center" valign="middle" style="font-size: 17px; font-weight: bold; color: #1A5D8F; padding-right:310px;">

       Editing Page Content...
      </td>
     </tr>
    </table>
   </td>
  </tr>
 </table>
</div>
