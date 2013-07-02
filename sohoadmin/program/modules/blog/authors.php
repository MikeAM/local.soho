<?php
error_reporting(E_PARSE);
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

error_reporting(E_PARSE);
session_start();

# Include core files
require_once("../../includes/product_gui.php");

$blogWebmaster = new userdata('blog');



//if($blogWebmaster->get('blog_webmaster_picture') == ''){
//	$blogWebmaster->set('blog_webmaster_picture', '');
//}

include_once('blog.class.php');
include_once('blog-styles.php');
# So you can write straight HTML without having to build every line into a container var (i.e. $disHTML .= "another line of html")
ob_start();
$sohoBlog = new sohoBlog;
$authorMode = "Blog Authors";
//if ($_POST['todo'] == 'newAuthor'){
//	$pkg = array('firstname' => $_POST['firstname'], 'lastname' => $_POST['lastname'], 'email' => $_POST['emailaddr'], 'password' => $_POST['password'], 'passwordCheck' => $_POST['passwordCheck']);
//
//	try
//	{
//		$sohoBlog->addAuthor($pkg);
//	} catch(Exception $e) {
//		$report[] = $e->getMessage();
//	}
//}

if($_POST['authID']!=''){
	$getauthq = mysql_query("select * from login where PriKey='".$_POST['authID']."' limit 1");
	$getauth_ar = mysql_fetch_assoc($getauthq);	
	if($getauth_ar['First_Name']=='WEBMASTER'){
		$blogWebmaster->set('blog_webmaster_picture', $_POST['authImg'.$_POST['authID']]);
	} else {
		mysql_query("update user_access_rights set PICTURE='".$_POST['authImg'.$_POST['authID']]."' where LOGIN_KEY='".$_POST['authID']."'");
	}
	if(strlen($_POST['new_auth_name']) > 1){
		mysql_query("update login set Last_Name='".$_POST['new_auth_name']."' where PriKey='".$_POST['authID']."'");
	}
}
echo "<style>\n";
echo ".authorEmail {\n";
echo "	font-size:14px;\n";

echo "}\n";

echo ".authorName {\n";
echo "	font-size:14px;\n";
echo "	font-weight:bold;\n";
echo "}	\n";
echo "</style>\n";

echo "<script>\n";
echo "function getImageData(imgdID, imgPath){\n";
//echo "	alert('http://".$this_ip."/'+imgPath);\n";
echo "	if(imgPath == ''){\n";
echo "		imgPath='sohoadmin/program/spacer.gif';\n";
echo "	}\n";
echo "	document.getElementById(imgdID).src='http://".$this_ip."/'+imgPath;\n";
echo "}\n";
?>
	
$(document).ready(function(){
	$( function(){
		$( 'input[type="text"]' ).each( function(){
			$(this).attr( 'title', $(this).val() )
			.focus( function(){
				if ( $(this).val() == $(this).attr('title') ) {
					$(this).val( '' );
					$(this).css({'color':'#222'});
				}
			} ).blur( function(){
				if ( $(this).val() == '' || $(this).val() == ' ' ) {
					$(this).val( $(this).attr('title') );
					$(this).css({'color':'#888'});
				}
			});
		});
	});
	function validPassword()
	{
		var pass1 = $('input#password');
		var pass2 = $('input#passwordCheck');

		var regex = new RegExp("^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=]).*$", "i");

		if ( !regex.test(pass1.val()) || !regex.test(pass2.val()) )
		{
			$('li#passwordError > span.errorText').text("Your password must be 8 characters long and consist of at least one lower case letter, an uppercase letter, a number and a special character.");
			$('li#authPword > input').addClass('error');
		} else {
			$('li#passwordError > span.errorText').text("");
			$('li#authPword > input').removeClass('error');
		}

		if ( pass1.val() != pass2.val() )
		{
			$('li#passwordError > span.errorText').text("Your passwords do not match!");
			$('li#authPword > input').addClass('error');
		} else {
			$('li#passwordError > span.errorText').text("");
			$('li#authPword > input').removeClass('error');
		}
	}
	function validName()
	{
		var regex = new RegExp("^([a-zA-Z0-9-_]+)$", "i");
		var myId = "";
		
		if (this.id == "firstname")
		{
			var errMessage = "Please enter a valid first name.";
			myId = "authorFname";
		} else {
			var errMessage = "Please enter a valid last name.";
			myId = "authorLname";
		}

		if ( !regex.test(this.value) )
		{
			$("#" + myId + " > span.errorText").text(errMessage);
		} else {
			$("#" + myId + " > span.errorText").text("");
		}
	}
	function validEmail()
	{
		var email = $('li#authorEmail > input#emailaddr');
		var regex = new RegExp("[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?", "i");

		if ( !regex.test(email.val()) )
		{
			$('li#authorEmail > span.errorText').text("Invalid Email address!");
		} else {
			$('li#authorEmail > span.errorText').text("");
		}
	}
	$('input#passwordCheck').blur(validPassword);
	$('input#firstname').blur(validName);
	$('input#lastname').blur(validName);
	$('input#emailaddr').blur(validEmail);
});
</script>
<?php
$count = 0;
$imageFile = array();
foreach(glob($doc_root."/images/*") as $files){
	$files = str_replace($doc_root.'/images/', '', $files);
	if (strlen($files) > 2) {
		$imageFile[] = 'images/'.$files;
		$count++;	
	}
}
$numImages = $count;
natcasesort($imageFile);
$imgDD .= "<option value=\"\" style=\"font-style:italic;color:gray;\"><i>select author picture ...</i></option>";
foreach($imageFile as $img){
	$imgDD .= "<option value=\"".$img."\">".basename($img)."</option>";
}

echo "<div id=\"authorMain\">\n";
echo "	<div class=\"fsub_body\">\n";
echo "		<div id=\"AuthorList\">\n";
if($_SESSION['CUR_USER_ACCESS']=='WEBMASTER'){
	$getwebmaster = mysql_query("select * from login where First_Name='WEBMASTER' limit 1");
	$getwebmaster_ar = mysql_fetch_assoc($getwebmaster);
	$author_array[$getwebmaster_ar['PriKey']]=array('name'=>$getwebmaster_ar['Last_Name'],'email'=>$getwebmaster_ar['Email'],'picture'=>$blogWebmaster->get('blog_webmaster_picture'));
	$authqry =  mysql_query("SELECT login.PriKey, login.Last_Name, login.Email, user_access_rights.LOGIN_KEY, user_access_rights.PICTURE FROM login INNER JOIN user_access_rights ON user_access_rights.LOGIN_KEY=login.PriKey order by login.PriKey");	
	while($auth_ar = mysql_fetch_assoc($authqry)){
		$author_array[$auth_ar['PriKey']]['name']=$auth_ar['Last_Name'];
		$author_array[$auth_ar['PriKey']]['email']=$auth_ar['Email'];
		$author_array[$auth_ar['PriKey']]['picture']=$auth_ar['PICTURE'];
	}

	//echo testArray($author_array);

	foreach($author_array as $avar=>$aval){
		if($aval['picture']==""){
			$aval['picture'] = "sohoadmin/program/spacer.gif";
		}
		echo "<div style=\"clear:left;margin:10px;padding:10px;\">\n";
		echo "<form name=\"authorsFrm".$avar."\" action=\"#\" method=\"POST\" style=\"display:inline;\">\n";
		echo "<input type=\"hidden\" name=\"authID\" value=\"".$avar."\">\n";
		echo "	<div style=\"float:left;\">\n";
		echo "		<img id=\"authorImage".$avar."\" src=\"http://".$this_ip."/".$aval['picture']."\" style=\"width:100px;height:100px; border: 1px solid #000000;\" />\n";
		echo "	</div>\n";
		
		echo "	<div style=\"padding-top:10px;padding-left:10px;float:left;\">\n";
		
		echo "		<span class=\"authorName\"><a href=\"javascript:void(0);\" onClick=\"this.style.display='none';document.getElementById('auth_newname_".$avar."').type='text';document.getElementById('auth_button_".$avar."').style.display='block';\">".$aval['name']."</a><input type=\"hidden\" style=\"background:#FFFFFF; color:black; font-weight:normal;padding:1px;border:1px solid black;\" id=\"auth_newname_".$avar."\" name=\"new_auth_name\" value=\"".$aval['name']."\"><input type=\"hidden\" name=\"new_auth_id\" value=\"".$avar."\"></span>\n";
		echo "		<br/><span class=\"authorEmail\"><i>(".$aval['email'].")</i></span><br/>\n";
		echo "		<select id=\"authImg".$avar."\" name=\"authImg".$avar."\" ONCHANGE=\"document.getElementById('auth_button_".$avar."').style.display='block';getImageData('authorImage".$avar."', this.value);\" STYLE='margin-top:5px;font-family: Arial; font-size: 8pt; width: 250px;'>\n";
		echo 		str_replace("<option value=\"".$aval['picture']."\">" , "<option value=\"".$aval['picture']."\" selected=\"selected\">", $imgDD);
		echo "		</select><br/><br/>\n";
		echo "		<span id=\"auth_button_".$avar."\" style=\"display:none;margin-left:100px;\"><button type=\"button\" class=\"greenButton\" onClick=\"document.authorsFrm".$avar.".submit();\"><span><span>Save Changes</span></span></button></span>\n";
		echo "	</div>\n";
		
		echo "</form>\n";
		echo "</div>\n";
		
	}
} else {
	//$getwebmaster = mysql_query("select * from login where First_Name='WEBMASTER' limit 1");
	//$getwebmaster_ar = mysql_fetch_assoc($getwebmaster);
	//$author_array[$getwebmaster_ar['PriKey']]=array('name'=>$getwebmaster_ar['Last_Name'],'email'=>$getwebmaster_ar['Email'],'picture'=>$blogWebmaster->get('blog_webmaster_picture'));
	$authqry =  mysql_query("SELECT login.PriKey, login.Last_Name, login.Email, user_access_rights.LOGIN_KEY, user_access_rights.PICTURE FROM login INNER JOIN user_access_rights ON user_access_rights.LOGIN_KEY=login.PriKey order by login.PriKey");	
	while($auth_ar = mysql_fetch_assoc($authqry)){
		if($auth_ar['PriKey'] == $_SESSION['CUR_USER_KEY']){
			$author_array[$auth_ar['PriKey']]['name']=$auth_ar['Last_Name'];
			$author_array[$auth_ar['PriKey']]['email']=$auth_ar['Email'];
			$author_array[$auth_ar['PriKey']]['picture']=$auth_ar['PICTURE'];
		}
	}

	//echo testArray($author_array);

	foreach($author_array as $avar=>$aval){
		if($aval['picture']==""){
			$aval['picture'] = "sohoadmin/program/spacer.gif";
		}
		echo "<div style=\"clear:left;margin:10px;padding:10px;\">\n";
		echo "<form name=\"authorsFrm".$avar."\" action=\"#\" method=\"POST\" style=\"display:inline;\">\n";
		echo "<input type=\"hidden\" name=\"authID\" value=\"".$avar."\">\n";
		echo "	<div style=\"float:left;\">\n";
		echo "		<img id=\"authorImage".$avar."\" src=\"http://".$this_ip."/".$aval['picture']."\" style=\"width:100px;height:100px; border: 1px solid #000000;\" />\n";
		echo "	</div>\n";
		
		echo "	<div style=\"padding-top:10px;padding-left:10px;float:left;\">\n";
		
		echo "		<span class=\"authorName\"><a href=\"javascript:void(0);\" onClick=\"this.style.display='none';document.getElementById('auth_newname_".$avar."').type='text';document.getElementById('auth_button_".$avar."').style.display='block';\">".$aval['name']."</a><input type=\"hidden\" style=\"background:#FFFFFF; color:black; font-weight:normal;padding:1px;border:1px solid black;\" id=\"auth_newname_".$avar."\" name=\"new_auth_name\" value=\"".$aval['name']."\"><input type=\"hidden\" name=\"new_auth_id\" value=\"".$avar."\"></span>\n";
		echo "		<br/><span class=\"authorEmail\"><i>(".$aval['email'].")</i></span><br/>\n";
		echo "		<select id=\"authImg".$avar."\" name=\"authImg".$avar."\" ONCHANGE=\"document.getElementById('auth_button_".$avar."').style.display='block';getImageData('authorImage".$avar."', this.value);\" STYLE='margin-top:5px;font-family: Arial; font-size: 8pt; width: 250px;'>\n";
		echo 		str_replace("<option value=\"".$aval['picture']."\">" , "<option value=\"".$aval['picture']."\" selected=\"selected\">", $imgDD);
		echo "		</select><br/><br/>\n";
		echo "		<span id=\"auth_button_".$avar."\" style=\"display:none;margin-left:100px;\"><button type=\"button\" class=\"greenButton\" onClick=\"document.authorsFrm".$avar.".submit();\"><span><span>Save Changes</span></span></button></span>\n";
		echo "	</div>\n";
		
		echo "</form>\n";
		echo "</div>\n";
		
	}
}
echo "		</div>\n";


//echo "		<div id=\"newAuthor\" style=\"clear:left;margin:10px;\">\n";
//echo "			<form name=\"newAuthor\" method=\"post\" action=\"#\">\n";
//echo "				<input type=\"hidden\" name=\"todo\" value=\"newAuthor\" />\n";
//echo "				<ul>\n";
//echo "					<li id=\"authorFname\"><input class=\"blog_labels\" type=\"text\" id=\"firstname\" name=\"firstname\" value=\"First Name\" /><span class=\"errorText\"></span></li>\n";
//echo "					<li id=\"authorLname\"><input class=\"blog_labels\" type=\"text\" id=\"lastname\" name=\"lastname\" value=\"Last Name\" /><span class=\"errorText\"></span></li>\n";
//echo "					<li id=\"authorEmail\"><input class=\"blog_labels\" type=\"text\" id=\"emailaddr\" name=\"emailaddr\" value=\"Email Address\" /><span class=\"errorText\"></span></li>\n";
//echo "					<li id=\"authorPword\"><input class=\"blog_labels\" type=\"password\" id=\"password\" name=\"password\" value=\"\" /> <input class=\"blog_labels\" type=\"password\" id=\"passwordCheck\" name=\"passwordCheck\" value=\"\" /></li>\n";
//echo "					<li id=\"passwordError\"><span class=\"errorText\"></span></li>\n";
//echo "					<li><p class=\"note\">* Password must be at least 8 characters long.  Please include a lowercase character, an uppercase character, a number and a symbol.</p></li>\n";
//echo "					<li><input type=\"submit\" name=\"submit\" value=\"Add Author\" /><input type=\"reset\" name=\"reset\" value=\"Reset\" /></li>\n";
//echo "				</ul>\n";
//echo "			</form>\n";
//echo "		</div>\n";


echo "	</div>\n";
echo "</div>\n";


# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$module = new smt_module($module_html);
$module->add_breadcrumb_link(lang("Blog Manager"), "program/modules/blog/blog-entry.php");
$module->add_breadcrumb_link("Authors", "program/modules/blog/authors.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/full_size/blog_manager-enabled.gif";
$module->heading_text = lang("Blog Authors");
$module->description_text = lang("Add a new author.  Authors will have their own sohoadmin login, but will only be allowed access to the Blog Manager.");
$module->good_to_go();
?>
