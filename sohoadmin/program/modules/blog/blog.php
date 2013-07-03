<?php
error_reporting('341');
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '' || $_REQUEST['_SESSION'] != '') { exit; }

#=====================================================================================
# Soholaunch(R) Site Management Tool
#
# Author:        Chris Neitzer
# Homepage:      http://www.soholaunch.com
# Release Notes: http://wiki.soholaunch.com
#
# This Script: Simple example module to illustrate how to create a new
# module and keep it's look consistent with the rest of the product
#=====================================================================================

session_start();


# Include core files
require_once("../../includes/product_gui.php");

if(!mysql_query("select blog_category,blog_title,blog_data,blog_date,blog_tags,blog_author,timestamp,live,allow_comments from blog_content limit 1")){
	if($tstqry=mysql_query("select * from blog_content limit 1")){
		if(mysql_num_rows($tstqry) == 0){
			mysql_query("drop table blog_content");
			create_table("blog_content");
		}
	}
	//echo mysql_error();	
}


create_table("blog_content");
create_table("blog_category");
create_table("blog_comments");
# So you can write straight HTML without having to build every line into a container var (i.e. $disHTML .= "another line of html")
ob_start();
echo "<script language=\"javascript\" type=\"text/javascript\" src=\"../tiny_mce/tiny_mce.js\"></script>\n";
echo "<script language=\"javascript\" type=\"text/javascript\" src=\"../tiny_mce/plugins/media/js/embed.js\"></script>\n";
//echo "<script type=\"text/javascript\" src=\"../tiny_mce/plugins/media/js/embed.js\"></script>\n";
include_once('blog-styles.php');
?>

<style type="text/css">

.unnamed1 {
	padding-top: 30px;
}
.unnamed2 {
	padding-top: 15px;
	font-size: 8pt;
	font-family: Arial;
}

</style>
<?php

include_once('tinyInc.php');


include_once("blog.class.php");
$blog = new sohoBlog();
$authors = $blog->getAuthors();
//echo testArray($authors);

if ($_POST['todo'] != ""){
	if($_POST['edit_post']!=''){
		$id = $_POST['edit_post'];
		$postCat = $_POST['del_subj'];
		$postTitle = $_POST['postTitle'];
		$postBody = $_POST['tiny_editor'];
		$postTags = $_POST['postTags'];
		$postTags=str_replace('comma, separated, keywords','',$postTags);
		$postStatus = $_POST['status'];
		$postAuthor = $_POST['sel_author'];
		$allow_comments = $_POST['allow_comments'];
		$postPkg = array("id"=>$id, "del_cat" => $postCat, "postTitle" => $postTitle, "postBody" => $postBody, "postTags" => $postTags, "blog_author"=>$postAuthor, "live"=>$postStatus, "allow_comments"=>$allow_comments);
		$edit_post = $blog->updateEntry($postPkg);
		$reportz = "Article Saved!";

	} else {
		$postCat = $_POST['del_subj'];
		$postTitle = $_POST['postTitle'];
		$postBody = $_POST['tiny_editor'];
		$postTags = $_POST['postTags'];
		$postTags=str_replace('comma, separated, keywords','',$postTags);
		$postStatus = $_POST['status'];
		$postAuthor = $_POST['sel_author'];
		$allow_comments = $_POST['allow_comments'];
		$postPkg = array("del_cat" => $postCat, "postTitle" => $postTitle, "postBody" => $postBody, "postTags" => $postTags, "blog_author"=>$postAuthor, "live"=>$postStatus, "allow_comments"=>$allow_comments);
		$edit_post = $blog->saveEntry($postPkg);
		$reportz = "Article Saved!";
		$_REQUEST['edit_post'] = $edit_post;
	}
}
?>
					

	<script language="javascript" type="text/javascript">
		function validTitle()
		{
			if ($('#postTitle').val() == '' || $('#postTitle').val() == 'Blog Title')
			{
				$('#postTitle').addClass('error');
				$('#title_error').text('Please enter a title for this post.');
				$('#title_error').addClass('errorText');
				return false;
			} else {
				
				$('#title_error').text('');
				$('#title_error').removeClass('errorText');
				$('#postTitle').removeClass('error');
				return true;
			}
		}
		function validCategory()
		{
//				alert($('#del_subj option:selected').val());
				//alert($('select#del_subj').val());
			if ($('#del_subj option:selected').val() == '' || $('#del_subj option:selected').val() == 'Select A Category ...')
			{
				$('select#del_subj').after('<span id="categoryError" style="display: inline;" class="errorText">Please select a category for this post.</span>');
				$('select#del_subj').addClass('error');
				return false;
			} else {
				
				$('#categoryError').hide();
				$('select#del_subj').removeClass('error');
				return true;
			}
		}
		function checkBlogContent(){
			if(tinyMCE.activeEditor.getContent()==''){
				alert('You must enter some content for the article.');
				return false;
			} else {
				return true;	
			}
		}
		function postBlog(){
			if(validTitle() && validCategory() && checkBlogContent()){
				document.newPostForm.submit();
			} else {
				//alert('noo go');
				return false;
			}
			//document.newPostForm.submit();
		}
		
	   $('document').ready(function(){

		//$('li#postTitle > input').blur();
		//$('select#del_subj').blur();
	   	$('p.blog_labels').click(function(){
			if ($('button.delImage').css('display') == 'none'){
				$('button.delImage').fadeIn("fast");
				$('p.blog_labels').text('Done');
			} else {
				$('button.delImage').fadeOut("fast");
				$('p.blog_labels').text('Edit');
			}
		});
		$('li.catNames > button').click(function(){
			var thing = $($(this).parent());
			var removeCategory = ($(this).next().html());
			thing.remove();
			window.location = "blog-actions.php?cat=" + encodeURI(removeCategory);
		})		
		$( function(){
			$( 'input[type="text"]' ).each( function(){
				//$(this).attr( 'title', $(this).val() )
				$(this).css({'color':'#222'});
				$(this).css({'background-color':'#FFFFFF'});
				$(this).css({'font-style':'normal'});
				$(this).focus( function(){
					if ( $(this).val() == $(this).attr('title') ) {
						$(this).val( '' );
						$(this).css({'color':'#222'});
						$(this).css({'font-style':'normal'});
					}
				} ).blur( function(){
					if ( $(this).val() == '' || $(this).val() == ' ' ) {
						$(this).val( $(this).attr('title') );
						$(this).css({'color':'#888'});
					} else {
						$(this).css({'color':'#222'});
						$(this).css({'font-style':'normal'});						
					}
				} );
			} );
		} );
	   });
	</script>
<?php
echo "<div id=\"main\">\n";
echo "<span style=\"color:green;font-weight:bold;font-size:14px;\">".$reportz."</span>\n";
echo "		<div id=\"newPost\">\n";
if($_REQUEST['edit_post']!=''){
	echo "<form name=\"newPostForm\" id=\"newPostForm\" action=\"blog.php?edit_post=".$_REQUEST['edit_post']."\" method=\"post\" style=\"display:inline;\">\n";
	$getPostq = mysql_query("select * from blog_content where prikey='".$_REQUEST['edit_post']."'");
	$getPost = mysql_fetch_assoc($getPostq);
	echo "			<input type=\"hidden\" name=\"edit_post\" value=\"".$_REQUEST['edit_post']."\">\n";
	$thisContent = $getPost['blog_data'];
	$btitle = $getPost['blog_title'];
} else {
	$btitle = 'Blog Title';
	echo "<form name=\"newPostForm\" id=\"newPostForm\" action=\"blog.php\" method=\"post\" style=\"display:inline;\">\n";	
}
			
echo "			<div class=\"fsub_title\"><input style=\"width:500px;\" class=\"input\" type=\"text\" name=\"postTitle\" id=\"postTitle\" value=\"".$btitle."\" title=\"Blog Title\" onBlur=\"validTitle();\" />\n";

echo "			<span id=\"title_error\" ></span>	\n";
echo "			</div>\n";
echo "			<div class=\"fsub_body\">\n";
echo "					<input type=\"hidden\" name=\"todo\" value=\"quikPost\" />\n";
if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) || eregi("opera", $_SERVER['HTTP_USER_AGENT']) ) {
	$editorHeight = "450px";
}else{
	if(eregi("Firefox/3", $_SERVER['HTTP_USER_AGENT'])){
			$editorHeight = "435px";
	}else{
		$editorHeight = "475px";
	}
}
if(isset($thisContent)){
	$tinyContent = $thisContent;
} else {
	$tinyContent = "";
}

	$selecteddisplay1 = ' '; $selecteddisplay2 = ' '; $selectedallow_comments1 = ' '; $selectedallow_comments2 = ' ';
	if($_REQUEST['edit_post']!=''){
		if(strtolower($getPost['live'])=='publish'){
			$selecteddisplay1 = ' selected selected="selected"';
		} else {
			$selecteddisplay2 = ' selected selected="selected"';
		}
		if(strtolower($getPost['allow_comments'])=='yes'){
			$selectedallow_comments1 = ' selected selected="selected"';
		} else {
			$selectedallow_comments2 = ' selected selected="selected"';
		}


	//	echo testArray($getPost);
	}

	echo "<div id=\"tiny_editor_container\" style=\"height:100%border: 0px solid green; z-index:1000; display: block;\">\n";
	echo "	<!--- Editor Textarea -->\n";
	echo "	<textarea id=\"tiny_editor\" name=\"tiny_editor\" style=\"position:relative;min-height:500px;width: 100%; border: 1px dotted red;\">".$tinyContent."</textarea>\n";
	echo "	<!--- Cancel / Done buttons -->\n";
	//echo "<!---<div id=\"saveIt\" style=\"position:absolute; bottom: 1px; right: 15px; z-index:1000; display:block;\">-->\n";
	//echo "	<div id=\"saveIt\" style=\"position:relative;bottom: 1px; margin: 0 auto; text-align: center; z-index:1002; display:block;\">\n";
//	echo "		<span style=\"float:right;margin-right:12px;margin-top:5px;\"><button onClick=\"tinyMCE.execInstanceCommand('tiny_editor','mceCodeEditor',false);\" type=\"button\" id=\"html_view\" class=\"grayButton\"><span><span>HTML View</span></span></button></span>\n";
	//echo "	</div>\n";
	echo "</div>\n";
	
	$myIds = $blog->getCatIds();
	
		
	echo "<div style=\"padding:0 0 0 5px;\">\n";	
	echo "<table cellpadding=\"0\" cellspacing=\"0\" style=\"width:100%;\">\n";
	echo "<tr><td colspan=\"2\" class=\"blogtables\">\n";

	echo "		<span style=\"float:right;margin-right:12px;margin-top:5px;\"><button onClick=\"tinyMCE.execInstanceCommand('tiny_editor','mceCodeEditor',false);\" type=\"button\" id=\"html_view\" class=\"grayButton\"><span><span>HTML View</span></span></button></span>\n";
	
	echo "<div class=\"pform\">\n";
	$coolCatList = "<b>Category:</b> <select onBlur=\"validCategory();\" id=\"del_subj\" name=\"del_subj\">\n";
	$coolCatList .= "<option value=\"\">Select A Category ...</option>\n";
	for ($i = 0; $i < count($myIds); $i++){
		$blog_cat_sel = ' ';
		if($getPost['blog_category']==$myIds[$i]['prikey']){
			$blog_cat_sel = ' selected selected="selected"';
		}
		$coolCatList .= "<option value=\"".$myIds[$i]['prikey']."\" ".$blog_cat_sel.">".$myIds[$i]['category_name']."</option>\n";
	}
	$coolCatList .= "</select>\n";
	echo $coolCatList;
	
	echo "</div>\n";
	
	
	$blog_tags = 'comma, separated, keywords';
	$blog_tags2 = $blog_tags;
	if($getPost['blog_tags']!=''){
		$blog_tags = htmlspecialchars($getPost['blog_tags']);
		$blog_tags2 = '';
	}
	echo "<div class=\"pform\" >\n";
	echo "						<b>Tags:</b> <input id=\"postTags\" style=\"width:370px;font-weight:normal;padding:1px;\" type=\"text\" name=\"postTags\" value=\"".$blog_tags."\" title=\"".$blog_tags2."\" />\n";
	echo "</div>\n";
	
//	echo "</td><td>&nbsp;</td>\n</tr>\n";
//	echo "<tr><td class=\"blogtables\">\n";
	
//	echo "</td><td class=\"blogtables\">\n";
	echo "<div style=\"clear:both;\" class=\"pform\">\n";
	$getwebmaster = mysql_query("select * from login where First_Name='WEBMASTER' limit 1");
	$getwebmaster_ar = mysql_fetch_assoc($getwebmaster);
	$author_array[$getwebmaster_ar['PriKey']]=array('name'=>$getwebmaster_ar['Last_Name'],'email'=>$getwebmaster_ar['Email']);
	$authqry =  mysql_query("SELECT login.PriKey, login.Last_Name, login.Email, user_access_rights.LOGIN_KEY, user_access_rights.PICTURE FROM login INNER JOIN user_access_rights ON user_access_rights.LOGIN_KEY=login.PriKey order by login.PriKey");	
	while($auth_ar = mysql_fetch_assoc($authqry)){
		$author_array[$auth_ar['PriKey']]['name']=$auth_ar['Last_Name'];
		$author_array[$auth_ar['PriKey']]['email']=$auth_ar['Email'];
	}

	
	echo "							<b>Author:</b> <select style=\"\" id=\"sel_author\" name=\"sel_author\">\n";
	$curblogauthorsel=' ';
	if($getPost['blog_author']==$_SESSION['CUR_USER_KEY']){ $curblogauthorsel=' selected selected="selected"'; }
	echo "							<option value=\"".$_SESSION['CUR_USER_KEY']."\" ".$curblogauthorsel.">".$author_array[$_SESSION['CUR_USER_KEY']]['name']."</option>\n";
	if($_SESSION['CUR_USER_ACCESS']=='WEBMASTER' && count($author_array) > 1){		
		foreach($author_array as $auth_ar=>$auth_ar_v){
			if($auth_ar != $_SESSION['CUR_USER_KEY']){
				$curblogauthorsel=' ';
				if($getPost['blog_author']==$auth_ar){ $curblogauthorsel=' selected selected="selected"'; }
				echo "							<option value=\"".$auth_ar."\" ".$curblogauthorsel.">".$auth_ar_v['name']."</option>\n";
			}
		}
	}
	echo "							</select>\n";
	echo "</div>\n";
//	echo "</td><td>&nbsp;</td>\n</tr>\n";
//	echo "<tr><td class=\"blogtables\">\n";
	echo "<div class=\"pform\">\n";
	
	echo "							<b>Visitor Comments:</b> <select style=\"width:75px;\" id=\"allow_comments\" name=\"allow_comments\">\n";
	echo "							<option value=\"yes\"".$selectedallow_comments1.">Allowed</option>\n";
	echo "							<option value=\"no\"".$selectedallow_comments2.">Disabled</option>\n";
	echo "							</select>\n";
	
	
	echo "</div>\n";
//	echo "</td>\n";
//	echo "<td class=\"blogtables\">\n";
	echo "<div class=\"pform\">\n";

	echo "							<b>Display:</b> <select style=\"width:75px;\" id=\"status\" name=\"status\">\n";
	echo "							<option value=\"hide\"".$selecteddisplay2.">Hide</option>\n";
	echo "							<option value=\"publish\"".$selecteddisplay1.">Publish</option>\n";
	echo "							</select>\n";	
	
	echo "</div>\n";
//	echo "</td>\n";
//	echo "<td style=\"width:30%;\">\n";
//	echo "						<div style=\"height:30px;margin-right:10px;float:right;text-align:right;\"><button class=\"greenButton\" onClick=\"postBlog();\" type=\"button\" value=\"Save\" /><span><span>&nbsp;&nbsp;Save&nbsp;Entry&nbsp;&nbsp;</span></span></button> </div>\n";	
echo "		<span style=\"margin-right:10px;float:right;text-align:right;\"><button class=\"greenButton\" onClick=\"postBlog();\" type=\"button\" value=\"Save\" /><span><span>&nbsp;&nbsp;Save&nbsp;Entry&nbsp;&nbsp;</span></span></button> </span>\n";	
	echo "</td>\n</tr>\n";
	
	
	echo "</table>\n";
	
	

	
	echo "					\n";
	echo "				</form>\n";
	echo "			</div>\n";
	echo "		</div>\n";
	
	
	
	echo "</div>\n";


if($_REQUEST['edit_post']!=''){

	echo "<script language=\"javascript\" type=\"text/javascript\">\n";
	echo '$(\'document\').ready(function(){'."\n";
	echo '	$(\'#del_subj\').val(\''.$getPost['blog_category'].'\');'."\n";
	echo '	$(\'#postTitle\').val(\''.str_replace("'",'\\\'',$getPost['blog_title']).'\');'."\n";
	echo '	$(\'#postTags\').val(\''.str_replace("'",'\\\'',$getPost['blog_tags']).'\');'."\n";
	echo '	$(\'#status\').val(\''.$getPost['live'].'\');'."\n";
	echo '	$(\'#sel_author\').val(\''.$getPost['blog_author'].'\');'."\n";
	echo '	$(\'#allow_comments\').val(\''.$getPost['allow_comments'].'\');'."\n";
	//echo '	$(\'#tiny_editor\').val(\''.$getPost['blog_data'].'\');'."\n";
	
	echo '	$( \'input[type="text"]\' ).css({\'color\':\'#222\'});'."\n";
	echo '	$( \'input[type="text"]\' ).css({\'font-style\':\'normal\'});'."\n";
	echo '});'."\n";
	echo "</script>\n";	
}

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$module = new smt_module($module_html);


$module->icon_img = "skins/".$_SESSION['skin']."/icons/full_size/blog_manager-enabled.gif";

$module->add_breadcrumb_link(lang("Blog Manager"), "program/modules/blog/blog-entry.php");

if($_REQUEST['edit_post']!=''){
	$module->add_breadcrumb_link(lang("Editing: ".$getPost['blog_title']), "program/modules/blog/blog.php?edit_post=".$_REQUEST['edit_post']);
	$module->heading_text = lang("Edit Blog Entry: ".$getPost['blog_title']);
	$module->description_text = lang("Editing Blog entry.  Set the display option to Publish when you are ready for the world to read your thoughts.");
} else {
	$module->add_breadcrumb_link(lang("Create Blog Entry"), "program/modules/blog/blog.php");
	$module->heading_text = lang("Create Blog Entry");
	$module->description_text = lang("Create a new Blog entry.  Set the display option to Publish when you are ready for the World to Read your thoughts.");
}

$module->good_to_go();
?>