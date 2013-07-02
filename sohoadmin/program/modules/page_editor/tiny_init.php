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



$rel_path = "../../../../";
if(eregi('blog', $_SERVER['PHP_SELF'])){
   $rel_path = "../../../";
}
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
   	plugins : "Uploadfile,inlinepopups,style,table,advhr,advimage,advlink,emotions,insertdatetime,preview,spellchecker,Addfontz,media,searchreplace,print,contextmenu,paste,visualchars,xhtmlxtras<?php echo $spellchecker; ?>",
   	theme_advanced_disable: "help,cleanup,code,styleselect",
   	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,forecolor,backcolor,styleselect,|,fontselect,fontsizeselect,formatselect,styleselect",
   	//theme_advanced_buttons1_add : ",separator,fontselect,separator,Addfontz,separator,fontsizeselect",
   	//theme_advanced_buttons2_add : "separator,forecolor,backcolor",
   	//theme_advanced_buttons2_add_before: "tablecontrols,separator,cut,copy,paste,pastetext,pasteword,separator",
   	//theme_advanced_buttons2_add_before: "tablecontrols,separator"
	//theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",

   	theme_advanced_buttons2 : "justifyleft,justifycenter,justifyright,justifyfull,|,outdent,indent,|,bullist,numlist,|,sub,sup,charmap,|,link,unlink,anchor,image,|,iespell,spellchecker,|,media,Uploadfile",
//   	theme_advanced_buttons3_add : "tablecontrols",
          theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid", 	
   	theme_advanced_toolbar_location : "top",
   	theme_advanced_toolbar_align : "center",
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
   	theme_advanced_resizing : false,
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
   
<?php
?>