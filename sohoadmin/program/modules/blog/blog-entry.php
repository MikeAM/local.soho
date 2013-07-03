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
include_once("blog.class.php");


if ($_GET['todo'] == 'removeEntry' && $_GET['id'] > 0){
	$blogDelete = new blogEntry($_GET['id']);
	$blogDelete->destroyPost($_GET['id']);	
}
$blogWebmaster = new userdata('blog');

$getwebmaster = mysql_query("select * from login where First_Name='WEBMASTER' limit 1");
$getwebmaster_ar = mysql_fetch_assoc($getwebmaster);
$author_array[$getwebmaster_ar['PriKey']]=array('name'=>$getwebmaster_ar['Last_Name'],'email'=>$getwebmaster_ar['Email'],'picture'=>$blogWebmaster->get('blog_webmaster_picture'));

$authqry =  mysql_query("SELECT login.prikey, login.Last_Name, login.Email, user_access_rights.LOGIN_KEY, user_access_rights.PICTURE FROM login INNER JOIN user_access_rights ON user_access_rights.LOGIN_KEY=login.prikey order by login.prikey");
while($auth_ar = mysql_fetch_assoc($authqry)){	
	$author_array[$auth_ar['prikey']]['name']=$auth_ar['Last_Name'];
	$author_array[$auth_ar['prikey']]['email']=$auth_ar['Email'];
	$author_array[$auth_ar['prikey']]['picture']=$auth_ar['PICTURE'];
}


# So you can write straight HTML without having to build every line into a container var (i.e. $disHTML .= "another line of html")
ob_start();
include_once('blog-styles.php');
?>

<script type="text/javascript">
$('document').ready(function(){
	$('span.inputField > input').click(function(){
		
	})
	$('li.tag').hover(
		function(){
			$('#' + this.id + ' span.removeTag').toggle('slow');
		}
	)
});
function deletePost(cat, id, postTitle) {
	var delPost = confirm("Do you really want to permanently delete the post: "+postTitle+" ?");
	if (delPost){
		document.location = "blog-entry.php?todo=removeEntry&catId="+cat+"&id="+id;
	}
}

function deleteThisCat(catformid, catName) {
	var delCat = confirm("Do you really want to permanently delete the "+catName+" Category?");
	if (delCat){
		catformid.submit();
	}
}

function deleteTag(tagNum, blogId){
	var delTag = confirm("Do you want to delete this tag?");
	if (delTag){
		document.location = "blog-actions.php?todo=removeTag&tag="+tagNum+"&id="+blogId;		
	}
}

function add_new_cat(){
	if(document.getElementById('new_blog_cat').value!='' && document.getElementById('new_blog_cat').value!='Category Name'){
		document.newcatForm.submit();
	} else {
		alert('Please enter a name for the new category.');
	}
}
</script>

<?php

if($_POST['todo']=='deletecat' && $_POST['delcat'] > 0){
	mysql_query("delete from blog_category where prikey='".$_POST['delcat']."'");
}

if($_POST['todo']=='newCat' && $_POST['new_blog_cat'] != ''){
	$findcat = mysql_query("select * from blog_category where category_name='".slashthis($_POST['new_blog_cat'])."'");
	if(mysql_num_rows($findcat) > 0){
	// cat already exists	
	} else {
		mysql_query("insert into blog_category (category_name) values('".slashthis($_POST['new_blog_cat'])."')");
	}
}

if($_POST['action']=='renamecat' && $_POST['catid'] > 0){
	mysql_query("update blog_category set category_name='".slashthis($_POST['cat'])."' where prikey='".$_POST['catid']."'");
}

$blog = new sohoBlog();
//($_GET['id'] != "") ? $numEntry = $blog->entryIds($id) : $numEntry = $blog->getAllIds();
//$numEntry = $blog->getAllIds();

if($_SESSION['CUR_USER_ACCESS']=='WEBMASTER'){
	$blogEs = mysql_query("SELECT prikey, blog_category, blog_title, blog_data, blog_date, blog_tags, blog_author, timestamp, live, allow_comments FROM blog_content order by blog_date");
} else {
	$blogEs = mysql_query("SELECT prikey, blog_category, blog_title, blog_data, blog_date, blog_tags, blog_author, timestamp, live, allow_comments FROM blog_content WHERE blog_author='".$_SESSION['CUR_USER_KEY']."' order by blog_date");	
}

$numEntry = mysql_num_rows($blogEs);
//echo "<div id=\"CatBar\" class=\"feature_module\">\n";
//echo "<h2 class=\"blog_labels\">".$blog->getCatName($id)."</h2>\n";
//echo "</div>\n";

echo "<div style=\"padding:5px;\">\n";
echo "							<span style=\"float:right;margin-right:20px;\"><a href=\"blog.php\" class=\"greenButton\"><span>Create New Blog Entry</span></a></span>\n";
echo "<h3>Blog Entries</h3>\n";
//echo "<p id=\"module_description_text\">Click the title of an entry to edit it.)</p>\n";
echo "	<table class=\"postTable\" cellspacing=\"0\" cellpadding=\"0\">\n";
echo "		<tr>\n";
echo "			<th>Title</th>\n";
echo "			<th>Author</th>\n";
echo "			<th>Category</th>\n";
echo "			<th>Date</th>\n";
echo "			<th>Status</th>\n";
echo "			<th>&nbsp;</th>\n";
echo "		</tr>\n";

	$thisEntry = "";
	$colorClass = "light";
	if ($numEntry < 1){
		$thisEntry = "<td colspan=\"5\"><p class=\"blog_labels\" style=\"text-shadow: none; text-align: center; font-size: 24px;\">No Posts!</p></td>";
	} else {
		//for ($i = 0; $i < count($numEntry); $i++){
		while($disBlog = mysql_fetch_assoc($blogEs)){
			($colorClass != "light") ? $colorClass = "light" : $colorClass = "dark";
			$thisBlog = new blogEntry($disBlog['prikey']);
			$thisEntry .= "\t\t<tr class=\"".$colorClass."\">\n";
			$thisEntry .= "\t\t\t<td><a href=\"blog.php?edit_post=".$thisBlog->getId()."\">".$thisBlog->title."</a></td>\n";
			$thisEntry .= "\t\t\t<td>".$author_array[$thisBlog->blog_author]['name']."</td>\n";
			$thisEntry .= "\t\t\t<td>".$thisBlog->getCategory()."</td>\n";
			$thisEntry .= "\t\t\t<td>".$thisBlog->getTimestamp('semiFormal')."</td>\n";
			$thisEntry .= "\t\t\t<td>".$thisBlog->status_display."</td>\n";
			$thisEntry .= "\t\t\t<td><button type=\"button\" class=\"redButton\" onclick=\"deletePost(".$thisBlog->getCatId().", ".$thisBlog->getId().", '".str_replace("'", "\'", $thisBlog->title)."');\"><span><span>".lang("Delete")."</span></span></button></td>\n";
			$thisEntry .= "\t\t</tr>\n";
		}
	}
	echo $thisEntry."</table>\n</div>\n";
//	echo "	<td></td>\n";	
echo "<div style=\"padding:30px 5px 5px 5px;\">\n";

echo "<h3>Categories\n";


echo "<span style=\"float:right;font-style:normal;margin-right:0px;font-size:14px;padding:0 25px 0 0;\">\n";
echo "	<form style=\"display:inline;\" method=\"post\" action=\"blog-entry.php\" name=\"newcatForm\">\n";
echo "		<input type=\"hidden\" name=\"todo\" value=\"newCat\" />\n";
echo "		Create Category:&nbsp;<input class=\"input\" style=\"width:150px;padding:2px;\" type=\"text\" name=\"new_blog_cat\" id=\"new_blog_cat\" value=\"Category Name\" onfocus=\"if(this.value == 'Category Name') this.value=''\" onblur=\"if(this.value=='') this.value='Category Name'\" onkeydown=\"this.style.color='#222'\" value=\"Category Name\"/>				&nbsp;&nbsp;&nbsp;<button type=\"button\" name=\"ADD\" class=\"greenButton\" onClick=\"add_new_cat();\" /><span><span>".lang("Add Category")."</span></span></button>\n";
echo "	</form>\n";
echo "</span>\n";

echo "</h3>\n";

echo "	<table  class=\"postTable\" cellspacing=\"0\" cellpadding=\"0\">\n";
echo "			<th>Category Name\n";
echo "			</th>\n";

echo "	<div style=\"font-size:16px;\">\n";
$getcatsq = mysql_query("select * from blog_category");
while($getcats=mysql_fetch_assoc($getcatsq)){
	($colorClass != "light") ? $colorClass = "light" : $colorClass = "dark";
	
	echo "		<tr class=\"".$colorClass."\"><td>\n";
	
	echo "		<div style=\"padding:6px;\">\n";

	echo "			<form name=\"deletecatform".$getcats['prikey']."\" method=\"post\" action=\"blog-entry.php\" style=\"display:inline;\">\n";
	echo "			<input type=\"hidden\" name=\"todo\" value=\"deletecat\">\n";
	echo "			<input type=\"hidden\" name=\"delcat\" value=\"".$getcats['prikey']."\">\n";
	echo "			</form>\n";
	
	echo "			<form name=\"cat".$getcats['prikey']."\" method=\"post\" action=\"blog-entry.php\" style=\"display:inline;\">";
	echo "				<div id=\"catzdiv".$getcats['prikey']."\" style=\"display:none;\">\n";
	echo "					<input type=\"hidden\" name=\"catid\" value=\"".$getcats['prikey']."\" />\n";
	echo "					<input type=\"hidden\" name=\"action\" value=\"renamecat\" />\n";
	echo "					<input type=\"text\" id=\"catz".$getcats['prikey']."\" name=\"cat\" value=\"".$getcats['category_name']."\" />\n";
	echo "					<button type=\"button\" class=\"greenButton\" onClick=\"document.cat".$getcats['prikey'].".submit();\" /><span><span>Rename Category</span></span></button>\n";
	
	echo "				</div>\n";
	echo "				<a href=\"javascript:void(0);\" onClick=\"document.getElementById('catz".$getcats['prikey']."').type='text';document.getElementById('catzdiv".$getcats['prikey']."').style.display='block';this.style.display='none';document.getElementById('delbutdiv".$getcats['prikey']."').style.display='none';\">".$getcats['category_name']."</a>\n";
	echo "				<div id=\"delbutdiv".$getcats['prikey']."\" style=\"float:right;clear:right;\"><button type=\"button\" class=\"redButton\" onClick=\"deleteThisCat(document.deletecatform".$getcats['prikey'].",'".str_replace("'", "\'", $getcats['category_name'])."');\" /><span><span>Delete Category</span></span></button></div>\n";	
	echo "			</form>\n";	
	
	echo "		</div>\n";
	
	
	echo "		</td></tr>\n";
}
($colorClass != "light") ? $colorClass = "light" : $colorClass = "dark";
//echo "		<tr class=\"".$colorClass."\"><td>\n";
//echo "<div style=\"font-style:normal;float:right;margin-right:0px;font-size:14px;padding-top:15px;\">\n";
//echo "	<form style=\"display:inline;\" method=\"post\" action=\"blog-entry.php\" name=\"newcatForm\">\n";
//echo "		<input type=\"hidden\" name=\"todo\" value=\"newCat\" />\n";
//echo "		Create Category:&nbsp;<input class=\"input\" style=\"width:350px;padding:2px;\" type=\"text\" name=\"new_blog_cat\" id=\"new_blog_cat\" value=\"Category Name\" onfocus=\"if(this.value == 'Category Name') this.value=''\" onblur=\"if(this.value=='') this.value='Category Name'\" onkeydown=\"this.style.color='#222'\" value=\"Category Name\"/>				&nbsp;&nbsp;&nbsp;<button type=\"button\" name=\"ADD\" class=\"greenButton\" onClick=\"document.newcatForm.submit();\" /><span><span>".lang("Add Category")."</span></span></button>\n";
//echo "	</form>\n";
//echo "</div>\n";
//
//echo "		</td></tr>\n";
echo "		</table>\n";
echo "	</div>\n";


echo "</div>\n";



# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$module = new smt_module($module_html);
$module->add_breadcrumb_link(lang("Blog Manager"), "program/modules/blog/blog-entry.php");
//$module->add_breadcrumb_link($blog->getCatName($id), "program/modules/blog/blog-entry.php?id=".$id);
$module->icon_img = "skins/".$_SESSION['skin']."/icons/full_size/blog_manager-enabled.gif";
$module->heading_text = lang("Blog Manager");
$module->description_text = lang("Manage your site's blog entries.  Click the title of an entry to edit it.");
$module->good_to_go();
?>
