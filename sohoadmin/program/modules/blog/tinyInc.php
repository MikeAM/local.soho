<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

session_start();
$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once("../../includes/product_gui.php");
chdir($curdir);
?>

<script type="text/javascript">

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
         var dot_com = '<? echo $_SESSION["docroot_url"]; ?>'
         
         var current_editing_area = '';
         var current_saving_area = '';
         var current_saving_button = '';

<?php

if (function_exists('curl_init')) {
	$spellchecker = ',spellchecker';
} else {
	$spellchecker = '';
}
$spellchecker = '';
/////////////////////


//	if(eregi("contentz", $cap_display)){

$cfonts = new userdata("customfonts");
if($cfonts->get("fontfams") == "") {
	$customfonts = "Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sand;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats";
	$cfonts->set("fontfams", $customfonts);		
}
$cfonts = $cfonts->get("fontfams");
$cfonts = explode(';', $cfonts);
usort($cfonts, "strnatcasecmp");
$finalfonts = '';
foreach($cfonts as $fvals){
	$finalfonts .= $fvals.';';
}
$finalfonts = eregi_replace(';$', '', $finalfonts);
//content_css : "../../../../sohoadmin/program/modules/tiny_mce/custom-css.php?cust_temp= echo base64_encode($CUR_TEMPLATE); &style="+ Base64.encode(stylem) +"&tags="+ Base64.encode(tagtypes) +"&ids="+ Base64.encode(csid) +"&classes="+ Base64.encode(classes) +"&pr= echo base64_encode($_GET['currentPage']); ", 



$rel_path = "../../../";
//echo "alert('(".$_SERVER['PHP_SELF'].")(".$rel_path.")');\n";

?>

function urlConverterCallback(strUrl, node, on_save) {
    // Don't convert anything at all
    return strUrl;
}

//FOR TESTING ADD devkit TO PLUGINS

   var current_editing_area = '';

   tinyMCE.init({
   	mode : "none",
   	theme : "advanced",
   	plugins : "Uploadfile,inlinepopups,style,table,advhr,advimage,advlink,emotions,insertdatetime,preview,spellchecker,media,searchreplace,print,contextmenu,paste,visualchars,xhtmlxtras<?php echo $spellchecker; ?>",
   	theme_advanced_disable: "help,cleanup,code,styleselect,tablecontrols,hr,removeformat,visualaid", 
   	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,forecolor,backcolor,styleselect,|,fontselect,fontsizeselect,formatselect,styleselect",
   	//theme_advanced_buttons1_add : ",separator,fontselect,separator,Addfontz,separator,fontsizeselect",
   	//theme_advanced_buttons2_add : "separator,forecolor,backcolor",
   	//theme_advanced_buttons2_add_before: "tablecontrols,separator,cut,copy,paste,pastetext,pasteword,separator",
   	//theme_advanced_buttons2_add_before: "tablecontrols,separator"
	//theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",

   	theme_advanced_buttons2 : "justifyleft,justifycenter,justifyright,justifyfull,|,outdent,indent,|,bullist,numlist,|,sub,sup,charmap,|,link,unlink,anchor,image,|,iespell,spellchecker,|,media,Uploadfile",

          //theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid", 	
          theme_advanced_buttons3 : "", 	




   	theme_advanced_toolbar_location : "top",
   	theme_advanced_toolbar_align : "left",
   	theme_advanced_path_location : "bottom",
   	content_css : "<?php echo $rel_path; ?>sohoadmin/program/modules/tiny_mce/custom-css.php?pr=<?php echo base64_encode($thisPage); ?>", 
      plugin_insertdate_dateFormat : "%Y-%m-%d",
      plugin_insertdate_timeFormat : "%H:%M:%S",
      paste_strip_class_attributes : "mso",
      verify_html : false,
   	convert_urls : false,
   	relative_urls : true,
   	spellchecker_languages : "+English=en,Swedish=sv",
   	urlconvertor_callback: "urlConverterCallback",
   	document_base_url : "http://<?php echo $this_ip; ?>/",
   	theme_advanced_resize_horizontal : false,
	theme_advanced_statusbar_location : "bottom",
   	theme_advanced_resizing : true,
   	nonbreaking_force_tab : true,
   	apply_source_formatting : false,
   	theme_advanced_fonts : "<?php echo $finalfonts; ?>",
   	trim_span_elements : false,
   	verify_css_classes : true,
	force_p_newlines : true,
   	visual : true,
   	valid_elements : "@[id|class|style|title|dir<ltr?rtl|lang|xml::lang|onclick|ondblclick|"
+ "onmousedown|onmouseup|onmouseover|onmousemove|onmouseout|onkeypress|"
+ "onkeydown|onkeyup],a[rel|rev|charset|hreflang|tabindex|accesskey|type|"
+ "name|href|target|title|class|onfocus|onblur],strong/b,em/i,strike,u,"
+ "#p,-ol[type|compact],-ul[type|compact],-li,br,img[longdesc|usemap|"
+ "src|border|alt=|title|hspace|vspace|width|height|align],-sub,-sup,"
+ "-blockquote,-table[border=0|cellspacing|cellpadding|width|frame|rules|"
+ "height|align|summary|bgcolor|background|bordercolor],-tr[rowspan|width|"
+ "height|align|valign|bgcolor|background|bordercolor],tbody,thead,tfoot,"
+ "#td[colspan|rowspan|width|height|align|valign|bgcolor|background|bordercolor"
+ "|scope],#th[colspan|rowspan|width|height|align|valign|scope],caption,-div,"
+ "-span,-code,-pre,address,-h1,-h2,-h3,-h4,-h5,-h6,hr[size|noshade],-font[face"
+ "|size|color],dd,dl,dt,cite,abbr,acronym,del[datetime|cite],ins[datetime|cite],"
+ "object[classid|width|height|codebase|*],param[name|value|_value],embed[type|width"
+ "|height|src|*],script[src|type],map[name],area[shape|coords|href|alt|target],bdo,"
+ "button,col[align|char|charoff|span|valign|width],colgroup[align|char|charoff|span|"
+ "valign|width],dfn,fieldset,form[action|accept|accept-charset|enctype|method],"
+ "input[accept|alt|checked|disabled|maxlength|name|readonly|size|src|type|value],"
+ "kbd,label[for],legend,noscript,optgroup[label|disabled],option[disabled|label|selected|value],"
+ "q[cite],samp,select[disabled|multiple|name|size],small,"
+ "textarea[cols|rows|disabled|name|readonly],tt,var,big",
   	media_use_script : false,
   	setupcontent_callback : "pullHTML",
   	theme_advanced_blockformats : "address,p,pre,h1,h2,h3,h4,h5,h6",
   	
   	external_image_list_url : "sohoadmin/program/modules/tiny_mce/imagelist.php",
   	media_external_list_url : "sohoadmin/program/modules/tiny_mce/medialist.php",
   	external_link_list_url : "sohoadmin/program/modules/tiny_mce/linklist.php",
   	external_link_list_media : "sohoadmin/program/modules/tiny_mce/linklistmedia.php"
   	
   });
   
   // updates tiny's font dropdown
   // font_num - index of font posistion
   // font_text - option display text
   // font_value - option value

   function resetFontsNow(){
      var inst = tinyMCE.activeEditor;
      var editorId = inst.editorId;
      var formElementName = editorId+"_fontNameSelect";
		document.getElementById(formElementName).length = 0
   }
   function updateFontsNow(font_num, font_text, font_value){
      var inst = tinyMCE.activeEditor;
      var editorId = inst.editorId;
      var formElementName = editorId+"_fontNameSelect";
      document.getElementById(formElementName).options[font_num] = new Option(font_text,font_value);
   }
   
     // Defines what happends when file specific buttons are clicked
   function fileBrowserCallBack(field_name, url, type, win) {
      //alert(type)
   	var connector = "../../../tiny_mce/file_manager.php";
   	var linkconnector = "../../../tiny_mce/link_manager.php";

   	my_field = field_name;
   	my_win = win;
   	wins_vars = "width=450,height=600";

   	switch (type) {
   		case "image":
   			connector += "?type=img&dot_com="+dot_com;
   			break;
   		case "media":
   			connector += "?type=flash&dot_com="+dot_com;
   			break;
   		case "file":
   			connector = linkconnector+"?type=files&dot_com="+dot_com;
   			wins_vars = "width=550,height=200";
   			break;
   	}
   	window.open(connector, "link_manager", wins_vars);
   	//alert('4test-'+connector+'---'+wins_vars)
   }
  
         // Gets content from editor and places it in editor
         // Called by setupcontent_callback within tinyMCE.init
	function pullHTML(editor_id, body, doc){
		//alert(current_editing_area)
		var html = getHtml(current_editing_area);
		//alert(html);
            
		var inst = tinyMCE.getInstanceById(tinyMCE.selectedInstance.editorId);
		var newHtml = TinyMCE_MediaPlugin.cleanup('insert_to_editor',html,inst);
            
		//alert(newHtml);
            
		body.innerHTML = newHtml;
	}
         
	// Hide / show / load / unload editor within spcified id (div or textarea)
	function toggleEditor(id) {
		var elm = document.getElementById(id);
         
		if (tinyMCE.getInstanceById(id) == null){
			tinyMCE.execCommand('mceAddControl', false, id);
			$('#tiny_editor_container').css({'display':'block'});
			setTimeout("tinyMCE.execInstanceCommand(tinyMCE.selectedInstance.editorId,'mceToggleVisualAid',false);tinyMCE.execInstanceCommand(tinyMCE.selectedInstance.editorId,'mceToggleVisualAid',false);",1000);
		}else{
			tinyMCE.execCommand('mceRemoveControl', false, id);
			$('#tiny_editor_container').style.display='none';
		}
	}
toggleEditor('tiny_editor');
</script>
