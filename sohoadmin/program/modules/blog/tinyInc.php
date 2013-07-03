<?php
error_reporting('341');
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

session_start();
$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once("../../includes/product_gui.php");
chdir($curdir);
?>

<script type="text/javascript">
	var blogstuff = 1;
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


	function createCookie(name,value,days)
	{
		if (days)
		{
			var date = new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			var expires = "; expires="+date.toGMTString();
		}
		else
		{
			var expires = "";
		}
		document.cookie = name+"="+value+expires+"; path=/";
	}

	function readCookie(name)
	{
		var nameEQ = name + "=";
		var ca = document.cookie.split(";");
		for(var i=0;i < ca.length;i++)
		{
			var c = ca[i];
			while (c.charAt(0)==" ") c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
		return null;
	}

	function disBlog(mode){
		var frmNm = AfrmNm;
		var textId = AtextId;
		var boxId = AboxId;
		var savebtn = Asavebtn;
		if(document.getElementById("remember").checked){
			createCookie("editorMode",mode,90);
			document.getElementById("chooseMode").style.display="none";
			alert("Setting saved!  To reset this option, go to webmaster and click Clear Editor Mode");
		}
		document.getElementById("chooseMode").style.display="none";
		document.getElementById("remember").checked=false;

		eval ("var result = MM_openBrWindow('loadEditor_Blog.php?mod=blog&type="+mode+"&savebtn="+savebtn+"&blogForm="+frmNm+"&curtext="+textId+"&blogBox="+boxId+"&dotcom=<? echo $dis_site; ?>&=SID','blogEdit','width=790, height=550, resizable=1');");
	}

	function SetBlog() {

		var blogCont = tinyMCE.getContent();
		//alert(blogCont)
		is_txtarea = blogCont.search("<textarea");
		if(is_txtarea>0){
			var textArr = blogCont.split("<textarea")
			var textLen = textArr.length
			for(var x=0; x<textLen; x++){
				blogCont = blogCont.replace("<textarea","<SOHOtextarea");
				blogCont = blogCont.replace("</textarea","</SOHOtextarea");
			}
		}
		var textImages = blogCont.split("src=\"images/")
		var textImagesLen = textImages.length
		for(var x=0; x<textImagesLen; x++){
			blogCont = blogCont.replace("src=\"images/", "src=\"http://"+dot_com+"/images/");
		}
			   
//		alert(current_editing_area)
//		alert(current_saving_area)
//		alert(current_saving_button)

		img = tinyMCE.getParam("theme_href") + '/images/spacer.gif';
		NewFinal = blogCont.replace(/<script[^>]*>\s*write(Flash|ShockWave|WindowsMedia|QuickTime|RealMedia)\(\{([^\)]*)\}\);\s*<\/script>/gi, '<img class="mceItem$1" title="$2" src="'+img+'" />');
			   
		//alert(blogCont)
		//alert(NewFinal)
			   
		document.getElementById(current_saving_area).innerHTML= NewFinal;
		document.getElementById(current_editing_area).value= blogCont;
		document.getElementById(current_saving_button).style.display= "block";
		toggleEditor("tiny_editor");
	}

	function getHtml(thisBox) {
		var boxHtml = document.getElementById('tiny_editor').value;
		//alert(boxHtml)
		is_txtarea = boxHtml.search("<SOHOtextarea");
		if(is_txtarea>0){
			var textArr = boxHtml.split("<SOHOtextarea")
			var textLen = textArr.length
			for(var x=0; x<textLen; x++){
				boxHtml = boxHtml.replace("<SOHOtextarea","<textarea");
				boxHtml = boxHtml.replace("</SOHOtextarea","</textarea");
			}
		}
		return boxHtml;
	}

	function loadBlog(frmNm,textId,boxId,savebtn){
		current_editing_area = boxId;
		current_saving_area = textId;
		current_saving_button = savebtn;
		toggleEditor("tiny_editor");

	}

	function textEdit(frmNm,textId,boxId) {
		//alert("something");
		eval ("var result = MM_openBrWindow('../page_editor/text_editor_45.php?blogForm="+frmNm+"&curtext="+textId+"&blogBox="+boxId+"&dotcom=<? echo $dis_site; ?>&=SID','textEditorWin','width=750,height=450');");
	}

	function save_blog(formNm,divId,boxId) {
		window.location = "blog.php?ACTION=dSave&subj="+subj+"&id="+key+"&='.SID.'";
	}

	function del_blog(key,subj) {
		window.location = "blog.php?ACTION=dREMOVE&subj="+subj+"&id="+key+"&='.SID.'";
	}


	     
         //################################################
         //       _____ _          __  __  ___ ___ 
         //      |_   _(_)_ _ _  _|  \/  |/ __| __|
         //        | | | | ' \ || | |\/| | (__| _| 
         //        |_| |_|_||_\_, |_|  |_|\___|___|
         //                   |__/                 Stuff
         //################################################
         
         //Define global variables
         var dot_com = '<? echo $this_ip; ?>'
         
         var current_editing_area = '';
         var current_saving_area = '';
         var current_saving_button = '';

<?php
include_once('../page_editor/tiny_init.php');
//echo "alert('(".$nn.")(".$rel_path.'sohoadmin/program/modules/tiny_mce/custom-css.php?pr='.base64_encode('Home').")(".$rel_path.")');\n";
//echo "alert(dot_com);\n";

   
   // updates tiny's font dropdown
   // font_num - index of font posistion
   // font_text - option display text
   // font_value - option value
 
//   function resetFontsNow(){
//      var inst = tinyMCE.activeEditor;
//      var editorId = inst.editorId;
//      var formElementName = editorId+"_fontNameSelect";
//		document.getElementById(formElementName).length = 0
//   }
//   function updateFontsNow(font_num, font_text, font_value){
//      var inst = tinyMCE.activeEditor;
//      var editorId = inst.editorId;
//      var formElementName = editorId+"_fontNameSelect";
//      document.getElementById(formElementName).options[font_num] = new Option(font_text,font_value);
//   }
  ?>

         // Gets content from editor and places it in editor
         // Called by setupcontent_callback within tinyMCE.init
	function pullHTML(editor_id, body, doc){
		//alert(current_editing_area)
		var html = getHtml(current_editing_area);
		//alert(html);
            
		var inst = tinyMCE.getInstanceById(tinyMCE.selectedInstance.editorId);
//		var newHtml = TinyMCE_MediaPlugin.cleanup('insert_to_editor',html,inst);
		var newHtml = html;          
		//alert(newHtml);
            
		body.innerHTML = newHtml;
	}

	// Hide / show / load / unload editor within spcified id (div or textarea)
	function toggleEditor(id) {
		var elm = document.getElementById(id);
         
		if (tinyMCE.getInstanceById(id) == null){
			tinyMCE.execCommand('mceAddControl', false, id);
			//$('#tiny_editor_container').css({'display':'block'});
			
			setTimeout("tinyMCE.execInstanceCommand('tiny_editor','mceToggleVisualAid',false);tinyMCE.execInstanceCommand('tiny_editor','mceToggleVisualAid',false);tinyMCE.activeEditor.setContent(tinyMCE.activeEditor.getContent());",1000);
		}else{
			tinyMCE.execCommand('mceRemoveControl', false, id);
			//$('#tiny_editor_container').style.display='none';
		}
	}
toggleEditor('tiny_editor');
</script>
