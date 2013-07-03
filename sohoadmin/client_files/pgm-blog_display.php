<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


##############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.9
##
## Homepage:	 	http://www.soholaunch.com
## Release Notes:	http://wiki.soholaunch.com
###############################################################################

######################################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2007 Soholaunch.com, Inc. and Mike Johnston All Rights Reserved.
##
## This script may be used and modified in accordance to the license
## agreement attached (license.txt) except where expressly noted within
## commented areas of the code body. This copyright notice and the comments
## comments above and below must remain intact at all times.  By using this
## code you agree to indemnify Soholaunch.com, Inc, its coporate agents
## and affiliates from any liability that might arise from its use.
##
## Selling the code for this program without prior written consent is
## expressly forbidden and in violation of Domestic and International
## copyright laws.
#######################################################################################

error_reporting('0');
session_start();

require_once("sohoadmin/program/includes/SohoEmail_class/SohoEmail.php");

echo "<link href=\"sohoadmin/client_files/blog_display_css.css\" rel=\"stylesheet\" type=\"text/css\">\n</link>\n";
echo "<script type=\"text/javascript\" src=\"sohoadmin/client_files/captcha/captcha.js\"></script>\n";
echo "<script type=\"text/javascript\">\n";

echo "function chk_n_send_captcha(form_key){\n";
echo "   if(zulucrypt(form_key)){\n";
echo "   var err = '';\n";
echo "   if(document.getElementById('commentName').value.length < 1){\n";
echo "      err += 'Name,'\n";
//echo "      $('comment_name_display').style.color='red';\n";
echo "   }\n";
echo "   if(document.getElementById('commentEmail').value.length < 1){\n";
echo "      err += 'Email,'\n";
//echo "      $('emailaddr_display').style.color='red';\n";
echo "   }\n";
echo "   if(document.getElementById('commentMessage').value.length < 1){\n";
echo "      err += 'comments'\n";
//echo "      $('blog_comments_display').style.color='red';\n";
echo "   }\n";
echo "   if(err == ''){\n";
echo "      var form_name = 'add_blog_comment_form';\n";
echo "      eval('document.'+form_name+'.submit();')\n";
echo "   }else{\n";
echo "      alert('".lang("Please complete the following fields").": '+err+'.')\n";
echo "   }\n";
echo "   }\n";
echo "}\n";

echo "function chk_n_send(){\n";

echo "	var returnval=true\n";
echo "	if(document.getElementById('commentName').value == ''){\n";
echo "		alert(\"Please enter your name.\");\n";
echo "		document.getElementById('commentName').focus();	\n";
echo "		returnval=false; //disallow form submission\n";
echo "	}\n";

echo "	if(document.getElementById('commentEmail').value == '' && returnval == true){\n";
echo "		alert(\"Please enter your email address.  This will only be used for spam prevention and will not be displayed.\");\n";
echo "		document.getElementById('commentEmail').focus();	\n";
echo "		returnval=false; //disallow form submission\n";
echo "	}\n";

echo "	if(document.getElementById('commentEmail').value != '' && returnval == true){\n";
echo "		var str = document.getElementById('commentEmail').value;\n";
echo "		var at=\"@\";\n";
echo "		var dot=\".\";\n";
echo "		var comma=\",\";\n";
echo "		var lat=str.indexOf(at);\n";
echo "		var lstr=str.length;\n";
echo "		var ldot=str.indexOf(dot);\n";
echo "		if (str.indexOf(at)==-1){\n";
echo "			alert(\"Please enter a valid email address.  This will only be used for spam prevention and will not be displayed.\");\n";
echo "			document.getElementById('commentEmail').focus();\n";
echo "			returnval=false; //disallow form submission\n";
echo "			return false; \n";
echo "		}\n";
echo "		if (str.indexOf(comma)>0){\n";
echo "			alert(\"Please enter a valid email address.  This will only be used for spam prevention and will not be displayed.\");\n";
echo "			document.getElementById('commentEmail').focus();\n";
echo "			returnval=false; //disallow form submission\n";
echo "			return false; \n";
echo "		}\n";
echo "		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){\n";
echo "			alert(\"Please enter a valid email address.  This will only be used for spam prevention and will not be displayed.\");\n";
echo "			document.getElementById('commentEmail').focus();\n";
echo "			returnval=false; //disallow form submission\n";
echo "			return false; \n";
echo "		}\n";
echo "		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){\n";
echo "			alert(\"Please enter a valid email address.  This will only be used for spam prevention and will not be displayed.\");\n";
echo "			document.getElementById('commentEmail').focus();\n";
echo "			returnval=false; //disallow form submission\n";
echo "			return false; \n";
echo "		}\n";
echo "		if (str.indexOf(at,(lat+1))!=-1){\n";
echo "			alert(\"Please enter a valid email address.  This will only be used for spam prevention and will not be displayed.\");\n";
echo "			document.getElementById('commentEmail').focus();\n";
echo "			returnval=false; //disallow form submission\n";
echo "			return false; \n";
echo "		}\n";
echo "		if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){\n";
echo "			alert(\"Please enter a valid email address.  This will only be used for spam prevention and will not be displayed.\");\n";
echo "			document.getElementById('commentEmail').focus();\n";
echo "			returnval=false; //disallow form submission\n";
echo "			return false; \n";
echo "		}\n";
echo "		if (str.indexOf(dot,(lat+2))==-1){\n";
echo "			alert(\"Please enter a valid email address.  This will only be used for spam prevention and will not be displayed.\");\n";
echo "			document.getElementById('commentEmail').focus();\n";
echo "			returnval=false; //disallow form submission\n";
echo "			return false; \n";
echo "		}\n";
echo "		if (str.indexOf(\" \")!=-1){\n";
echo "			alert(\"Please enter a valid email address.  This will only be used for spam prevention and will not be displayed.\");\n";
echo "			document.getElementById('commentEmail').focus();\n";
echo "			returnval=false; //disallow form submission\n";
echo "			return false; \n";
echo "		}\n";
echo "	}\n";

echo "	if(document.getElementById('commentMessage').value == '' && returnval == true){\n";
echo "		alert(\"Please enter a comment.\");\n";
echo "		document.getElementById('commentMessage').focus();	\n";
echo "		returnval=false; //disallow form submission\n";
echo "	}\n";
		
echo "	if(returnval == true){\n";
echo "		document.add_blog_comment_form.submit();\n";
echo "	}\n";
	 
echo "}\n";

echo "</script>\n";



if(!function_exists('microtime_float')){
	function microtime_float(){
		list($usec, $sec) = explode(" ", microtime());
		 $newval = (float)$usec + (float)$sec;
		 return(str_replace('.', '', $newval));
	}
}
$blogWebmaster = new userdata('blog');
$commentmicrotime = microtime_float();


$getBlogSubjects_qry = mysql_query("SELECT blog_content.blog_category, blog_category.prikey, blog_category.category_name FROM blog_content INNER JOIN blog_category ON blog_category.prikey=blog_content.blog_category WHERE blog_content.live='publish' group by blog_category.category_name");
while($getBlogSubjects = mysql_fetch_assoc($getBlogSubjects_qry)){
	$BlogSubjects[$getBlogSubjects['blog_category']]=$getBlogSubjects['category_name'];
}


unset($authorid_array);
$getwebmaster = mysql_query("select * from login where First_Name='WEBMASTER' limit 1");
$getwebmaster_ar = mysql_fetch_assoc($getwebmaster);
$author_array[$getwebmaster_ar['PriKey']]=array('name'=>$getwebmaster_ar['Last_Name'],'email'=>$getwebmaster_ar['Email'],'picture'=>$blogWebmaster->get('blog_webmaster_picture'));

$authorid_arrayemail[$getwebmaster_ar['PriKey']] = $getwebmaster_ar['Email'];
$authorid_array[$getwebmaster_ar['Last_Name']] = $getwebmaster_ar['PriKey'];
$authorid_arrayimg[$getwebmaster_ar['PriKey']] = $blogWebmaster->get('blog_webmaster_picture');

$authqry =  mysql_query("SELECT login.PriKey, login.First_Name, login.Last_Name, login.Email, user_access_rights.LOGIN_KEY, user_access_rights.PICTURE FROM login INNER JOIN user_access_rights ON user_access_rights.LOGIN_KEY=login.PriKey order by login.PriKey");
while($auth_ar = mysql_fetch_assoc($authqry)){	
	$author_array[$auth_ar['PriKey']]['name']=$auth_ar['Last_Name'];
	$author_array[$auth_ar['PriKey']]['email']=$auth_ar['Email'];
	$author_array[$auth_ar['PriKey']]['picture']=$auth_ar['PICTURE'];
	$authorid_arrayemail[$auth_ar['PriKey']] = $auth_ar['Email'];
	$authorid_array[$auth_ar['Last_Name']] = $auth_ar['PriKey'];
	$authorid_arrayimg[$auth_ar['PriKey']] = $auth_ar['PICTURE'];
}

$authorid_arraykey = array_flip($authorid_array);
$authorid_flippedemail = array_flip($authorid_arrayemail);
# Pull blog comment settings
$blog_comment_settings = new userdata("blog_comment");
//
//if(!$blog_comment_settings->get("allow_comments") || $blog_comment_settings->get("allow_comments") == "no"){
//   $is_allowed = "no";
//}else{
//   $is_allowed = "yes";
//}
if(preg_match('/^[0-9]+$/', $_REQUEST['id'])){
	$allow_comments_q = mysql_query("select allow_comments from blog_content where prikey='".$_REQUEST['id']."'");
	$comments_rez_ar = mysql_fetch_assoc($allow_comments_q);
	$comments_rez = $comments_rez_ar['allow_comments'];
	if($comments_rez=='yes'){
		$is_allowed = "yes";	
	} else {
		$is_allowed = "no";	
	}
}



if( !$blog_comment_settings->get("require_approval") ){
   $blog_comment_settings->set("require_approval", "yes");
}
if($_SESSION['PHP_AUTH_USER']!='' && $_SERVER['REMOTE_ADDR']!=''){
	$author_ip = $_SERVER['REMOTE_ADDR'];	
}
if($_REQUEST['deny_comment'] != '' && is_numeric($_REQUEST['deny_comment']) && $_REQUEST['key'] != '' && is_numeric($_REQUEST['key'])){
	$findbadcommentq = mysql_query("select prikey, ip_address from blog_comments where prikey='".$_REQUEST['deny_comment']."' and micro='".$_REQUEST['key']."' limit 1");
	if(mysql_num_rows($findbadcommentq) == 1){
		$findbadcomment = mysql_fetch_assoc($findbadcommentq);
		mysql_query("update blog_comments set status='denied' where prikey='".$_REQUEST['deny_comment']."' and micro='".$_REQUEST['key']."'");
		if($findbadcomment['ip_address'] != '' && $findbadcomment['ip_address'] != $author_ip){	
			## Make sure webmaster doesn't accidently ban their IP
			$findgoodip = mysql_query("select ip_address from login_history where ip_address = '".$findbadcomment['ip_address']."' limit 1");
			if(mysql_num_rows($findgoodip)==0){
				$findbadip = mysql_query("SELECT prikey, ip_address, ban_expires FROM ip_bans where ip_address='".$findbadcomment['ip_address']."' and ( ban_expires > '".time()."' or ban_expires='') limit 1");
				//$findbadip = mysql_query("select * from ip_bans where ip_address = '".$findbadcomment['ip_address']."' limit 1");
				if(mysql_num_rows($findbadip) == 0){
					$ban_expires=(time()+(3600*48));
					mysql_query("insert into ip_bans (ip_address, time, ban_expires, reason) values('".$findbadcomment['ip_address']."', '".time()."', '".$ban_expires."', 'spam')");
				}
			}
		}
		echo "<script type=\"text/javascript\">\n";
		echo "alert('This comment has been marked as spam');\n";		
		echo "	document.location='".$pr.".php?id=".$_REQUEST['id']."'; \n";
		echo "</script>\n";
	}
} elseif($_REQUEST['allow_comment'] != '' && is_numeric($_REQUEST['allow_comment']) && $_REQUEST['key'] != '' && is_numeric($_REQUEST['key'])){
	$findbadcommentq = mysql_query("select prikey, ip_address from blog_comments where prikey='".$_REQUEST['allow_comment']."' and micro='".$_REQUEST['key']."' limit 1");
	if(mysql_num_rows($findbadcommentq) == 1){
		$findbadcomment = mysql_fetch_assoc($findbadcommentq);
		mysql_query("update blog_comments set status='approved' where prikey='".$_REQUEST['allow_comment']."' and micro='".$_REQUEST['key']."'");
		echo "<script type=\"text/javascript\">\n";
		echo "alert('".lang("This comment has been approved")."');\n";
		echo "	document.location='".$pr.".php?id=".$_REQUEST['id']."'; \n";
		echo "</script>\n";
	}
}

# Process blog comment
if($_REQUEST['process'] == "blog_comment" && $is_allowed == "yes"){
	
	if(!function_exists('db_string_format')){
		function db_string_format($string) {
	   	if ( !get_magic_quotes_gpc() ) {
	      	return mysql_real_escape_string($string);
	   	} else {
	      	return $string;
	   	}
		}
	}
	
	if(!function_exists('myCheckDNSRR')){
		function myCheckDNSRR($hostName, $recType = ''){
			if(!empty($hostName)) {
				if( $recType == '' ){ $recType = "MX"; }
				if(eregi('WIN', PHP_OS)){
					if(gethostbyname($hostName) != $hostName){
						return true;
					} else {
						return false;
					}
				} else {
					getmxrr($hostName, $mx_arr);
					if(count($mx_arr) > 0){
						return true;
					} else {
						if(gethostbyname($hostName) != $hostName){
							return true;
						} else {
							return false;
						}
					}
				}	
			}
		}
	}
	
	# Clean all non alphanumeric characters and strip extra space
	if(!function_exists('cleanString')){
	   function cleanString($wild) {
	      $wild = ereg_replace("[^[:alnum:]+]"," ",$wild);
	      return ereg_replace("^[ \t\r\n]+|[ \t\r\n]+$","",$wild);
	   }
	}	

	$comment_error = '';
	if($blog_comment_settings->get("captcha") == "yes"){
		$bkey = $_REQUEST['blog_key'];
		$ccapval = $_REQUEST['capval_'.$bkey];
		$ccapans = $_REQUEST['cap_'.$bkey];
		$form_verificationk = '';
		$form_verificationk = $_SESSION['form_verification_blog'][$bkey];
		unset($_SESSION['form_verification_blog']);
		if($form_verificationk != md5(strtoupper($ccapans)) || $form_verificationk == '') {
			$comment_error .= "Image verification field incorrect.\n";
//			echo 'does not match'; exit;
//			header("Location: http://".$_SESSION['this_ip']."/".$pr.'.php?id='.$bkey);
//			echo "<script type=\"text/javascript\"> \n";
//			Echo "document.location='http://".$_SESSION['this_ip']."/".$pr.".php?id=".$bkey."'; \n";
//			Echo "</script> \n";
//			exit;
		}
		
		if($ccapval != '' && $ccapans != '' && $ccapval == md5(strtoupper($ccapans)) && $form_verificationk == md5(strtoupper($ccapans))) {
			$_SESSION['form_verification_blog'] = $form_verificationk;     
		} else {
			$comment_error .= "Image verification field incorrect.\n";
//			echo 'does not match2'; exit;
//			header("Location: http://".$_SESSION['this_ip']."/".$pr.".php?id=".$bkey);
//			echo "<script type=\"text/javascript\"> \n";
//			Echo "document.location='http://".$_SESSION['this_ip']."/".$pr.".php?id=".$bkey."'; \n";
//			Echo "</script> \n";
//			exit;
		}
		unset($_POST['capval']);
		unset($_POST['cap']);
		unset($_SESSION['form_verification_blog']);		
	}

	$blog_comments = $_POST['commentMessage'];
	$comment_name = $_POST['commentName'];
	$emailaddr = $_POST['commentEmail'];
	
	# Loop through REQUEST vars
	$blog_key = $_REQUEST['blog_key'];
	$find_em = array("comment_name", "emailaddr", "blog_comments");
	foreach($_REQUEST as $var=>$val){
		foreach($find_em as $name){
			//echo "name = (".$name.") var = (".$var.")<br/>\n";
			if(eregi($name, $var)){
				${$name} = db_string_format($val);
				//echo "(".$name.")=(".${$name}.")<br/>\n";
			}
		}
	}
	$comment_name = stripslashes(cleanString($comment_name));
	$blog_comments = stripslashes(htmlentities($blog_comments, ENT_QUOTES));
	
	#CHECK email ADDRESS BEFORE INSERT
	list($userName, $mailDomain) = split("@", $emailaddr);
	if($comment_error == ''){
		
		if (!myCheckDNSRR($mailDomain,"MX")){
			# CANNOT VERIFY email... ERROR
			$comment_error = lang("The Email Address you specified could not be verified").".\n";
		}else{
			# email VERIFIED... CONTINUE WITH INSERT AND email			
			# BUT FIRST MAKE SURE THIS IS NOT A RE-POST WITHIN 60 SEC		
			$oneday = date('Y-m-d H:i:s', time()-(24 * 60 * 60));
			$find_recent = "SELECT prikey, blog_key, name, email, comment_date FROM blog_comments WHERE ip_address = '".$_SERVER['REMOTE_ADDR']."' AND blog_key = '".$blog_key."' AND comment_date > '".$oneday."' ORDER BY comment_date DESC LIMIT 2";			
			$result = mysql_query($find_recent);				
			$PREV_COMMENT = mysql_fetch_array($result);
			
			$timestamp = strtotime($PREV_COMMENT['comment_date']) + 60 ;
//			if(mysql_num_rows($result) > 0 && $timestamp > time()){
			if(mysql_num_rows($result) > 2 && !isset($_SESSION['PHP_AUTH_USER'])){
				$time_error = lang("Were sorry, but you must wait at least 24 hours before posting another comment on this article.");
				$comment_error .= $time_error;
			}else{
				# NOT A RE-POST... CONTINUE WITH INSERT AND email
				$comment_date = time();
				
				# Require approval or auto accept comment?
				if( !$blog_comment_settings->get("require_approval") || $blog_comment_settings->get("require_approval") == "yes" ){
					$status = "new";
				}else{
					$status = "approved";
				}
				$spamfail = '';
				if(preg_match('/http:\/\//i', $blog_comments) || preg_match('/mymail-in\.net/i', $emailaddr) || preg_match('/mail\.ru/i', $emailaddr) || preg_match('/(fuck|porn|p0rn|pron|viagra|fetish|slut|cunt)/i', $blog_comments)){
					$spamfail = 'yes';
					$status = "new";
				}
				
				$blogQry = "INSERT INTO blog_comments VALUES('NULL', '".$blog_key."', '".$comment_name."', '".$emailaddr."', '".$blog_comments."', '".$comment_date."', '".$status."', '".$_SERVER['REMOTE_ADDR']."', '".$commentmicrotime."')";
				//echo "(".$blogQry.")";
				if ( !mysql_query($blogQry) ) {
					$comment_error = lang("Unable to post blog comment").".  ".lang("Please contact the site webmaster").".";
//					echo $comment_error." ".mysql_error(); exit;
				}else{
					$comment_insert_id = mysql_insert_id();					 					
					# Approval text displayed depending on status
					if($status == "new"){
						$comment_result_text = lang("Your comment has been posted but will not be displayed until it has been approved").". ".lang("Thank you").".";
					}else{
						$comment_result_text = lang("Your comment has been posted")."! ".lang("Thank you").".";
					}
					
					$blogowner = mysql_fetch_assoc(mysql_query("SELECT blog_author, blog_title FROM blog_content WHERE prikey = '".$blog_key."'"));				
					//$blog_author_email = $authorid_arrayemail[$authorid_array[$blogowner['blog_author']]];
					$blog_author_email = $author_array[$blogowner['blog_author']]['email'];
					$blog_title = $blogowner['blog_title'];
					
					$result = mysql_query("SELECT df_email FROM site_specs LIMIT 1");
					$SITE_SPECS = mysql_fetch_array($result);
					
					$admin_email = $SITE_SPECS['df_email'];
					
					if(!$blog_comment_settings->get("emailto")){
						$blog_comment_settings->set("emailto", $admin_email);
					}else{
						$admin_email = $blog_comment_settings->get("emailto");
					}
					//$admin_email = $blog_author_email;
					$to_email = $admin_email.','.$blog_author_email;
															
					$boundary = md5(uniqid(time()));
					if($spamfail=='yes'){														
						$subjectline = 'New Blog Comment Flagged As Spam';
					} else {
						$subjectline = 'New Blog Comment';
					}
										
					# Thank you email to buyer
					$email_header = "";
					$email_header .= "From: noreply@".$_SESSION['this_ip']."\n";
					$email_header .= "MIME-Version: 1.0\n";
					$email_header .= "Content-Type: multipart/alternative;\n";
					$email_header .= "	boundary=\"=".$boundary."\"\n";
					$email_content = "";
					$email_content .= "--=".$boundary."\n";
					$email_content .= "Content-Type: text/plain; charset=\"utf-8\"\n";
					$email_content .= "Content-Transfer-Encoding: 7bit\n";
					$email_content .= "\n";
					$email_content .= $_SESSION['this_ip']." - ".$subjectline."\n";
					$email_content .= "---------------------------------------------------------------------------------\n\n";
					$email_content .= lang("A user on your site")." ".$_SESSION['this_ip'].", ".lang("has posted a comment for the article titled:")." ".htmlspecialchars(nl2br(stripslashes(html_entity_decode($blog_title, ENT_QUOTES))))."\n\n";
					$email_content .= lang("Details of this comment")."...\n";
					$email_content .= "> ".lang("Name").": ".htmlspecialchars(stripslashes($comment_name))."\n";
					$email_content .= "> ".lang("Comment").": <b>".htmlspecialchars(nl2br(stripslashes(html_entity_decode($blog_comments, ENT_QUOTES))))."</b><br/><br/>\n";
					$email_content .= "> ".lang("Email").": ".stripslashes($emailaddr)."\n";					
					$email_content .= "> ".lang("Comment time").": ".date('D-j-Y g:ia', $comment_date)."\n\n";
					# Instructions on comment status
					if($status == "new"){
						if($spamfail=='yes'){
							$email_content .= lang("A new comment was flagged as a suspected spam post and is awaiting your approval.  Please login to your site and navigate to")." ".lang("Main Menu")." > ".lang("Blog Manager")." > ".lang("Blog comments")." ".lang("to approve or deny this comment").".\n\n";
						} else {
							$email_content .= lang("Please login to your site and navigate to")." ".lang("Main Menu")." > ".lang("Blog Manager")." > ".lang("Blog comments")." ".lang("to approve or deny this comment").".\n\n";
						}
					}else{
						$email_content .= lang("This comment has been automatically approved and will display in the blog comments").".  ".lang("If you wish to require approval before displaying comments, please login to your site and navigate to")." ".lang("Main Menu")." > ".lang("Blog Manager")." > ".lang("Blog comments")." > ".lang("Settings and check the 'Require webmaster approval' box").".\n\n";
					}
					$email_content .= lang("Thank you")."!\n\n";
					
					$email_content .= "--=".$boundary."\n";
					$email_content .= "Content-Type: text/html; charset=\"utf-8\"\n";
					//$email_content .= "Content-Transfer-Encoding: quoted-printable\n";
					//$email_content .= "Content-Transfer-Encoding: base64\n";
					$email_content = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
					$email_content .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
					$email_content .= "<head>\n";
					$email_content .= "<blog-meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
					$email_content .= "<title>Untitled Document</title>\n";
					$email_content .= "</head>\n";
					$email_content .= "<style type=\"text/css\">\n";
//					$email_content .= "a {\n";
//					$email_content .= "   color: #6699CC;\n";
//					$email_content .= "   text-decoration:none;\n";
//					$email_content .= "}\n";
					$email_content .= "h2 {\n";
					$email_content .= "	font-size: 15px;\n";
					$email_content .= "}\n";
					$email_content .= ".style1 {color: #990000}\n";
					$email_content .= "</style>\n";
					$email_content .= "<body>\n";
					$email_content .= "<div align=\"center\" style=\"padding: 10px;\">\n";
					$email_content .= " <div align=\"left\" style=\"width: 600px; padding: 10px; border: 1px solid #666666; font-family: 'Trebuchet MS', Arial; font-size:12px;\">\n";
					$email_content .= "  <h2>".$subjectline;
					$email_content .= " ".lang("for the article").": ".htmlspecialchars(nl2br(stripslashes(html_entity_decode($blog_title, ENT_QUOTES))));
					$email_content .= "</h2>\n";
					$email_content .= "  <p>".htmlspecialchars(stripslashes($comment_name))." ".lang("has posted a comment for the article:")." <b>".htmlspecialchars(nl2br(stripslashes(html_entity_decode($blog_title, ENT_QUOTES))))."</b> on ".$_SESSION['this_ip'].".\n";
					$email_content .= "  </p>\n";
					$email_content .= "  <p>".lang("Comment Details")."...</p>\n";
					$email_content .= " <ul>\n";
					$email_content .= "  <li><b>".lang("Name").":</b> ".htmlspecialchars(stripslashes($comment_name))."</li>\n"; 
					$email_content .= "  <li><b>".lang("Email").":</b> ".stripslashes($emailaddr)."</li>\n";
					$email_content .= "  <li><b>".lang("Comment").":</b> ".htmlspecialchars(nl2br(stripslashes(html_entity_decode($blog_comments, ENT_QUOTES))))."</li>\n";
					$email_content .= "  <li><b>".lang("Comment time").":</b> ".date('D-j-Y g:ia', $comment_date)."</li>\n";
					$email_content .= "  <li><b>".lang("IP").":</b> ".$_SERVER['REMOTE_ADDR']."</li>\n";
					$email_content .= " </ul>\n";
					
					# Instructions on comment status					
					if($status == "new"){
						if($spamfail=='yes'){							
							$email_content .= "  <p>".lang("This comment was flagged for suspected spam and is awaiting your approval.")."<br/>\n";
							$email_content .= "<a href=\"http://".$_SESSION['this_ip']."/".$pr.'.php?id='.$blog_key.'&deny_comment='.$comment_insert_id."&key=".$commentmicrotime."\">".lang("click here if this is SPAM to BAN offending I.P.")."</a>";
							$email_content .= " &nbsp;&nbsp;&nbsp;".lang("or")."&nbsp;&nbsp;&nbsp; <a href=\"http://".$_SESSION['this_ip']."/".$pr.'.php?id='.$blog_key.'&allow_comment='.$comment_insert_id."&key=".$commentmicrotime."\">".lang("click here to APPROVE this comment")."</a>";
							$email_content .= "</p>\n";
						} else {							
							$email_content .= "  <p>".lang("This comment is awaiting approval.")."<br/>\n";
							$email_content .= "<a href=\"http://".$_SESSION['this_ip']."/".$pr.'.php?id='.$blog_key.'&deny_comment='.$comment_insert_id."&key=".$commentmicrotime."\">".lang("click here if this is SPAM to BAN offending I.P.")."</a>";
							$email_content .= " &nbsp;&nbsp;&nbsp;".lang("or")."&nbsp;&nbsp;&nbsp; <a href=\"http://".$_SESSION['this_ip']."/".$pr.'.php?id='.$blog_key.'&allow_comment='.$comment_insert_id."&key=".$commentmicrotime."\">".lang("click here to APPROVE this comment")."</a>";
							$email_content .= "</p>\n";
						}						
					}else{
						$email_content .= "  <p>".lang("This comment has been automatically approved and will display in the blog comments").".<br/>\n";
						$email_content .= "<a href=\"http://".$_SESSION['this_ip']."/".$pr.'.php?id='.$blog_key.'&deny_comment='.$comment_insert_id."&key=".$commentmicrotime."\">".lang("click here if this is SPAM to BAN offending I.P.")."</a><br/>";
						$email_content .= lang("If you wish to require approval before displaying comments, please login to your site and navigate to")." ".lang("Main Menu")." > ".lang("Blog Manager")." > ".lang("Blog comments")." > ".lang("Settings and check the 'Require webmaster approval' box").".";
						$email_content .= "</p>\n";
					}
					
					$email_content .= "  <p>Thank you!</p>\n";
					$email_content .= " </div>\n";
					$email_content .= "</div>\n";
					$email_content .= "</body>\n";
					$email_content .= "</html>\n";
					
					if(!SohoEmail($to_email, "noreply@".preg_replace('/^www\./i','',$_SESSION['this_ip']), $subjectline, $email_content)){
						mail("$to_email", $subjectline, "$email_content", $email_header);
					}

					echo "<script type=\"text/javascript\">\n";
					echo "	alert('".$comment_result_text."');\n";
					echo "	document.location='http://".$_SESSION['this_ip']."/".$pr.".php?id=".$blog_key."'; \n";
					echo "</script>\n";
				}
			}
		}
	}
	if(strlen($comment_error) > 1){	
		unset($_SESSION['form_verification_blog']);						
	}
}


if($BLOG_CATEGORY_NAME == 'ALL'){
	$catKeyQry = "blog_category!='0' ";
} else {
	$catKeyQry = "blog_category='".$BLOG_CATEGORY_NAME."' ";
}
//$catKey = '1';

// Convert date to usable format
if(!function_exists('dateConvert')){
	function dateConvert($date, $type) {
		if ($type == 'month') {
			$b = 'M';
		} elseif($type!='') {
			$b = $type;
		} else {
			$b = 'j';
		}
		$utime = strtotime($date);
		return date($b, $utime);
	}
}
$authorSelect = "";

ksort($authorid_array);
foreach($authorid_array as $akey=>$aval){
	$authorSelect .= "<li><a href=\"".$pr.'.php?author='.$aval."\">".$akey."</a></li>";
}

foreach($BlogSubjects as $bkey=>$bval){
	$subjectSelect .= "<li><a href=\"".$pr.'.php?subject='.$bkey."\">".$bval."</a></li>";
}


if($_GET['id']!=''){
	if(preg_match('/^[0-9]+$/', $_GET['id'])){
		$blogConentQry = mysql_query("SELECT prikey, blog_category, blog_title, blog_data, blog_date, blog_tags, blog_author, timestamp, live, allow_comments FROM blog_content WHERE prikey='".$_GET['id']."' and live='publish'");
//		$blogConentQry = mysql_query("SELECT prikey, blog_category, blog_title, blog_data, blog_date, blog_tags, blog_author, timestamp, live, allow_comments FROM blog_content WHERE prikey='".$_GET['id']."' and live='publish' and blog_category = '".$catKey."'");
		$blogConent = mysql_fetch_assoc($blogConentQry);	
	} else {
##CHANGE BACK TO DESC AFTER S BLOG CLEARS CYCLE
		$blogConentQry = mysql_query("SELECT prikey, blog_category, blog_title, blog_data, blog_date, blog_tags, blog_author, timestamp, live, allow_comments FROM blog_content WHERE ".$catKeyQry." and live='publish' ORDER BY blog_date DESC limit 1");
		$blogConent = mysql_fetch_assoc($blogConentQry);	
	}
	
} elseif($_REQUEST['author'] != ''){
	if(preg_match('/^[0-9]+$/', $_REQUEST['author'])){
		$authorid = $_REQUEST['author'];
	} else {
		$blogConentQry = mysql_query("SELECT prikey, blog_category, blog_title, blog_data, blog_date, blog_tags, blog_author, timestamp, live, allow_comments FROM blog_content WHERE ".$catKeyQry." and live='publish' ORDER BY blog_date DESC limit 1");
		$blogConent = mysql_fetch_assoc($blogConentQry);	
	}

} elseif(preg_match('/^[0-9]+$/', $_REQUEST['subject'])){
	$subjectid = $_REQUEST['subject'];

} else {
##CHANGE BACK TO DESC AFTER  S BLOG CLEARS CYCLE
	$blogConentQry = mysql_query("SELECT prikey, blog_category, blog_title, blog_data, blog_date, blog_tags, blog_author, timestamp, live, allow_comments FROM blog_content WHERE ".$catKeyQry." and live='publish' ORDER BY blog_date DESC limit 1");

	$blogConent = mysql_fetch_assoc($blogConentQry);
		
}

echo "<div class=\"blog-container\">\n";


echo "<div class=\"blog-right-panel\" style=\"z-index:23;\">\n";
echo "<ul class=\"headingrighttop\">\n";
echo "	<li>\n";
echo "    	<h2>Latest Articles</h2>\n";
echo "		<ul>\n";
//$lastblogs = mysql_query("SELECT prikey, blog_title, blog_date FROM blog_content WHERE blog_category = '3' ORDER BY blog_date desc limit 6");
//$lastblogs = mysql_query("SELECT prikey, blog_title, blog_date FROM blog_content WHERE blog_category = '3' ORDER BY blog_date desc limit 6");
$lastblogs = mysql_query("SELECT prikey, blog_title, blog_date FROM blog_content WHERE ".$catKeyQry." and live='publish' ORDER BY blog_date desc limit 6");
while($lasts = mysql_fetch_assoc($lastblogs)){	
	echo "			<li style=\"position:relative;\">";				
	echo "			<a href=\"".$pr.'.php?id='.$lasts['prikey']."&art=".$lasts['blog_title']."\" style=\"padding-right:30px;\">".$lasts['blog_title'];
	echo "			<div style=\"position:absolute; top:0px; right:-6px;\" class=\"blog-meta3\">\n";
	echo "			<p><span>".dateConvert($lasts['blog_date'], 'j')."</span>".dateConvert($lasts['blog_date'], 'M')."</p>\n";
	echo "			</div>\n";
	//echo  " <span style=\"color:#505050!important;\">".dateConvert($lasts['blog_date'], 'm/d/Y')."</span>";
	echo "			</a>\n";
	echo "			</li>\n";
}
echo "        </ul>\n";
echo "    </li>\n";

##Hide the "View articles by author" menu
if($hideauthorbox=='hidden'){
	echo "	<li>\n";
	echo "    	<h2>View Articles By Author</h2>\n";
	echo "        <ul>\n";
	echo $authorSelect;
	echo "        </ul>\n";
	echo "    </li>\n";
	
}

if($BLOG_CATEGORY_NAME == 'ALL'){
	echo "	<li>\n";
	echo "    	<h2>View Articles By Subject</h2>\n";
	echo "        <ul>\n";
	echo $subjectSelect;
	echo "        </ul>\n";
	echo "    </li>\n";
	//echo "</ul>\n";
}
echo "</ul>\n";
echo "</div>\n";

echo "<div class=\"blog-left-panel\">\n";

if($authorid=='' && $subjectid==''){
//	if($_SESSION['PHP_AUTH_USER']!=''){		
//		echo "[ ";
//		$stat_rez_qa = mysql_query("select prikey, blog_key, status from blog_comments where status='new'");
//		if(mysql_num_rows($stat_rez_qa) > 0){
//			echo mysql_num_rows($stat_rez_qa)." comment(s) awaiting approval&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;";
//		}
//		$stat_rez_q = mysql_query("select ip_address, date from blog_stats where blog_id='".$blogConent['prikey']."' group by ip_address");
//		echo mysql_num_rows($stat_rez_q)." people have read this article ]";
//		
////		$fqrylikes = mysql_query("select prikey, blog_id, ip_address from BLOG_LIKES where blog_id='".$blogConent['prikey']."'");
////		if(mysql_num_rows($fqrylikes) > 0){
////			echo "&nbsp;&nbsp;-&nbsp;".mysql_num_rows($fqrylikes)." people liked this article";
////		}									
//	}

	echo "<h2 class=\"blog-title\">";
	echo "<a style=\"color:#383838;\" href=\"http://".$_SESSION['this_ip']."/".$pr.".php?id=".$blogConent['prikey']."&art=".$blogConent['blog_title']."\">\n";
	echo $blogConent['blog_title'];
	echo "</a>\n";
	echo "</h2>\n";
	echo "<div class=\"blog-meta\">\n";
	if($author_array[$blogConent['blog_author']]['picture']!='' && file_exists($author_array[$blogConent['blog_author']]['picture'])){
		echo "<a style=\"text-decoration:none;\" href=\"".$pr.'.php?author='.$blogConent['blog_author']."\">";
		echo "<img src=\"".$author_array[$blogConent['blog_author']]['picture']."\" width=\"100\" height=\"100\" align=left style=\"margin:2px 7px 7px 2px; border:1px solid #000000;\" alt=\"".$blogConent['blog_author']."\" />\n";
		echo "</a>\n";
	}
	echo "by <a class=\"authorlink\" href=\"".$pr.'.php?author='.$blogConent['blog_author']."\">".$author_array[$blogConent['blog_author']]['name']."</a> \n";

	if ($blogConent['blog_tags'] != '') {
		$tags = explode(',', $blogConent['blog_tags']);
		$tagdisplay = "&nbsp;&nbsp;tags:  ";
		foreach($tags as $tagg){
			$tagdisplay .= "<a href=\"http://".$_SESSION['this_ip']."/".$pr.".php?id=".$blogConent['prikey']."&art=".$blogConent['blog_title']."\">".$tagg."</a>, ";
		}
		$tagdisplay = preg_replace('/(, )$/', '', $tagdisplay);
		echo $tagdisplay;
		//echo " / tags:  <a href=\"#\">something</a>, <a href=\"#\">like</a>, <a href=\"#\">this</a>, <a href=\"#\">and</a>, <a href=\"#\">something</a>, <a href=\"#\">like that</a>";
	}
	
	echo "<p><span>".dateConvert($blogConent['blog_date'], 'j')."</span>".dateConvert($blogConent['blog_date'], 'M')."</p>\n</div>\n";	
	
	echo "<div class=\"entry\">\n";	
	echo $blogConent['blog_data']."\n";	
	

	if($_SESSION['PHP_AUTH_USER']!=''){
		$referer =  $_SERVER['HTTP_REFERER'];
		$findstat = mysql_query("select prikey, date, time, ip_address, count, blog_id from blog_stats where ip_address='".$_SERVER['REMOTE_ADDR']."' AND date='".date("Y/m/d")."' and blog_id='".$blogConent['prikey']."' limit 1");
		if(mysql_num_rows($findstat) == 0){
			mysql_query("insert into blog_stats (date, time, ip_address, count, blog_id, referrer, agent) values('".date("Y/m/d")."', '".time()."', '".$_SERVER['REMOTE_ADDR']."', '1', '".$blogConent['prikey']."','".$referer."', '".$_SERVER['HTTP_USER_AGENT']."')");
		} else {
			$findstat_ar = mysql_fetch_assoc($findstat);		
			mysql_query("update blog_stats set count='".($findstat_ar['count']+1)."' where prikey='".$findstat_ar['prikey']."'");
		}
	}	

	$template_header = preg_replace('/<title>[^<]+<\/title>/i', '<title>'.$blogConent['blog_title'].'</title>', $template_header);
//$template_footer = eregi_replace('iso-8859-1', 'utf-8', $template_footer);
	
} elseif($subjectid!=''){
	echo "<div style=\"align:left;padding-top:12px; margin-left:1px;\"><h2 class=\"blog-title\">".$BlogSubjects[$subjectid]."</h2></div>";	
	
	$subj_articles_qry = mysql_query("SELECT prikey, blog_category, blog_title, blog_data, blog_date, blog_tags, blog_author, timestamp, live, allow_comments FROM blog_content WHERE blog_category='".$subjectid."' and live='publish' order by blog_date DESC");
	while($subj_art = mysql_fetch_assoc($subj_articles_qry)){
		
		echo "<div style=\"clear:left; position:relative;z-index:22;\"><div style=\"cursor: pointer;\" onClick=\"document.location='".$pr.".php?id=".$subj_art['prikey']."&art=".$subj_art['blog_title']."'\" class=\"blog-meta2\">\n";		
		echo "<p><span>".dateConvert($subj_art['blog_date'], 'j')."</span>".dateConvert($subj_art['blog_date'], 'M')."</p>\n";
		echo "<div style=\"padding-top:8px; padding-left:4px; font:14px/12px Arial, Helvetica, sans-serif;\">\n";
		echo "<a href=\"".$pr.".php?id=".$subj_art['prikey']."&art=".$subj_art['blog_title']."\">".$subj_art['blog_title']."</a>\n";
		echo "</div></div></div>\n";
		echo "<br/>";
	}
	
} else {
	
	if($author_array[$authorid]['picture']!='' && file_exists($author_array[$authorid]['picture'])){
		echo "<h2 class=\"authortitle\" style=\"vertical-align: bottom; width:600px;\">";
		echo "<img src=\"".$author_array[$authorid]['picture']."\" width=\"60\" height=\"60\" align=top style=\"float:left; margin:2px 2px 2px 2px; border:1px solid #000000; z-index:9900;\" alt=\"".$author_array[$authorid]['name']."\"/>\n";	
		echo "<div style=\"padding-top:39px; margin-left:84px;\"><h2 class=\"blog-title\">Articles by ".$author_array[$authorid]['name']."</h2></div>";
		echo "</h2>\n";
	} else {
		echo "<div style=\"align:left;padding-top:12px; margin-left:1px;\"><h2 class=\"blog-title\">Articles by ".$author_array[$authorid]['name']."</h2></div>";	
	}
	$author_articles_qry = mysql_query("SELECT prikey, blog_category, blog_title, blog_date, blog_tags, blog_author FROM blog_content WHERE blog_author='".$authorid."' and live='publish' order by blog_date DESC");
	while($auths_art = mysql_fetch_assoc($author_articles_qry)){
		
		echo "<div style=\"clear:left; position:relative;z-index:22;\"><div style=\"cursor: pointer;\" onClick=\"document.location='".$pr.".php?id=".$auths_art['prikey']."&art=".$auths_art['blog_title']."'\" class=\"blog-meta2\">\n";		
		echo "<p><span>".dateConvert($auths_art['blog_date'], 'j')."</span>".dateConvert($auths_art['blog_date'], 'M')."</p>\n";
		echo "<div style=\"padding-top:8px; padding-left:4px; font:14px/12px Arial, Helvetica, sans-serif;\">\n";
		echo "<a href=\"".$pr.".php?id=".$auths_art['prikey']."&art=".$auths_art['blog_title']."\">".$auths_art['blog_title']."</a>\n";
		echo "</div></div></div>\n";
		echo "<br/>";
	}
}
//echo "</div>\n";
//echo "<div id=\"comments_div\" style=\"width:600px\">\n";
echo "</div>\n";


if($blogConent['prikey'] > 0){
	$get_comments_qry = mysql_query("select prikey, blog_key, name, email, comments,comment_date, status, ip_address from blog_comments where blog_key='".$blogConent['prikey']."' and status='approved' order by prikey ASC");		
	$com_count = mysql_num_rows($get_comments_qry);
	if($com_count > 0){
	echo "<div style=\"margin-top:10px; padding-top:10px; clear:both;\" id=\"comments_div\" >\n";
		echo "<h3 class=\"comment-title\">comments (<span style=\"font:20px/10px Arial, Helvetica, sans-serif;\">".$com_count."</span>)\n";
		if($is_allowed=='yes'){
			echo " <span>(<a href=\"#postcomment\">add comment</a>)</span>\n";
		} else {
			echo " <span>&nbsp;</span>\n";
		}
		echo "</h3>\n";
		echo "<ol class=\"comments\">\n";
		while($get_comments = mysql_fetch_assoc($get_comments_qry)){
			echo "	<li class=\"guest-comment\">\n";						
			echo "    	<p class=\"whosays\" style=\"margin-bottom:5px;\">";
			
			if(in_array($get_comments['email'], $authorid_arrayemail)){
				if($authorid_arrayimg[$authorid_flippedemail[$get_comments['email']]]!='' && file_exists($authorid_arrayimg[$authorid_flippedemail[$get_comments['email']]])){
					echo "<img src=\"".$authorid_arrayimg[$authorid_flippedemail[$get_comments['email']]]."\" style=\"float:left; margin-right:8px; width:30px; height:30px;\">";
				} else {
					echo "<img src=\"sohoadmin/client_files/bubble.png\" style=\"float:left; margin-right:8px; width:26px; height:21px;\">";		
				}
			
			} else {
				echo "<img src=\"sohoadmin/client_files/bubble.png\" style=\"float:left; margin-right:8px; width:26px; height:21px;\">";	
			}
			
			$dis_commentdate = date('F jS Y', $get_comments['comment_date'])." at ".date('h:i a', $get_comments['comment_date']);
			
			if(date('F j Y', $get_comments['comment_date']) == date('F j Y')){				
				$dis_commentdate = "Today at ".date('h:i a', $get_comments['comment_date']);
			} elseif(date('F j Y', $get_comments['comment_date']) == date('F j Y', strtotime('yesterday'))){
				$dis_commentdate = "Yesterday at ".date('h:i a', $get_comments['comment_date']);
			} else {
				$dis_commentdate = date('F jS Y', $get_comments['comment_date'])." at ".date('h:i a', $get_comments['comment_date']);
			}
						
			echo "<span style=\"font-weight:bold; color:#383838\">".$get_comments['name']."</span>";
			echo "		<br/><span style=\"font-size:10px; font-style:italic; color:#616161;\">posted ".$dis_commentdate."</span>";
			echo "	</p>\n";
			echo "        <div class=\"commentquote\">".html_entity_decode($get_comments['comments'])."</div>\n";
			echo "    </li>\n";
		}

		echo "</ol>\n";
		echo "</div>\n";
		echo "<br />\n";
	}
}

//if($blog_comment_settings->get("allow_comments") == "yes" && $blogConent['prikey'] > 0){

if($blogConent['allow_comments']=='yes' && $blogConent['prikey'] > 0){
	
	echo "<h3 class=\"comment-title\" style=\"margin-top:1px;\"><a name=\"postcomment\"></a>Post your comment:</h3>\n";
	echo "      <form name=\"add_blog_comment_form\" method=\"POST\" action=\"".$pr.".php?id=".$blogConent['prikey']."\">\n";
	echo "		<ul class=\"post-comment\">\n";
	

	if(isset($_SESSION['PHP_AUTH_USER'])){
		echo "		<li><label for=\"commentName\">Your Name:</label><input style=\"width:350px;\" type=\"text\" id=\"commentName\" name=\"commentName\" class=\"commentName\" value=\"".$authorid_arraykey[$authorid_flippedemail[strtolower($_SESSION['PHP_AUTH_USER'])]]."\" /></li>\n";
		echo "		<li><label for=\"commentEmail\">Your Email: <i>(for spam prevention, this will not be displayed)</i></label><input style=\"width:350px;\" type=\"text\" id=\"commentEmail\" name=\"commentEmail\" class=\"commentEmail\" value=\"".strtolower($_SESSION['PHP_AUTH_USER'])."\"/></li>\n";	
	} else {
		echo "		<li><label for=\"commentName\">Your Name:</label><input style=\"width:350px;\" type=\"text\" id=\"commentName\" name=\"commentName\" class=\"commentName\" /></li>\n";
		echo "		<li><label for=\"commentEmail\">Your Email: <i>(for spam prevention, this will not be displayed)</i></label><input style=\"width:350px;\" type=\"text\" id=\"commentEmail\" name=\"commentEmail\" class=\"commentEmail\" /></li>\n";		
	}

	echo "		<li class=\"msg\"><label for=\"commentMessage\">Your Message:</label><textarea style=\"height:120px; width:510px;\" id=\"commentMessage\" name=\"commentMessage\" class=\"commentMessage\"></textarea>\n";
	echo "		<input type=\"hidden\" name=\"process\" value=\"blog_comment\">\n";
	echo "		<input type=\"hidden\" name=\"blog_key\" value=\"".$blogConent['prikey']."\">\n";
	echo "		</li>  \n";
	
	if($blog_comment_settings->get("captcha") == "yes"){
	
		$pattern = "abdehklmprsuwx123456789";
		$synckey = '';	
		$key = "<div height=\"34\" width=\"216\">\n";
		for($i=0;$i<6;$i++){  	
			$key .= "<image src=\"sohoadmin/client_files/captcha/";
			$keyz = $pattern{rand(0,22)};
			$key .= $keyz.".gif\" width=\"36px\" height=\"34px\" style=\"border:1px solid black;\">\n";
			$synckey .= $keyz;
		}
		$synckey = strtoupper($synckey);

		$key .= "</div>\n";	
		
		echo "<li style=\"padding-top:20px;\">\n";
		echo "<input name=\"capval_".$blogConent['prikey']."\" id=\"capval_".$blogConent['prikey']."\" type=\"hidden\" value=\"".md5($synckey)."\">\n";
		echo $key;
		echo "<label for=\"cap_".$blogConent['prikey']."\">Please&nbsp;enter&nbsp;the&nbsp;phrase&nbsp;as&nbsp;it&nbsp;is&nbsp;shown&nbsp;in&nbsp;the&nbsp;box&nbsp;above:</label>\n";	
		echo "<input name=\"cap_".$blogConent['prikey']."\" id=\"cap_".$blogConent['prikey']."\" type=\"text\" size=\"6\" maxlength=\"6\" style=\"width:116px; text-align:left; font-size:18px;\">\n";
		echo "</li>\n";
		
		$_SESSION['form_verification_blog'][$blogConent['prikey']] = md5($synckey);
		
		echo "		<li>\n";
		echo "		<input type=\"button\" value=\"SUBMIT\" onclick=\"chk_n_send_captcha('".$blogConent['prikey']."')\"  class=\"\">\n";
		echo "		</li>\n";
	} else {		
		echo "		<li>\n";		
		echo "		<input type=\"button\" value=\"SUBMIT\" onclick=\"chk_n_send();\"  class=\"\">\n";	
		echo "		</li>\n";
	}
	
	
	echo "</ul>\n";
	echo "	</form>\n";
	
}

if($authorid=='' && $subjectid==''){
	echo "</div>\n";
}
//echo "<div class=\"post\">\n";

// Removed this div on Aug 26 2012

//echo "</div></div></div>\n";
if(strlen($comment_error) > 1){	
	echo "<script type=\"text/javascript\">\n";	
	echo "	alert('".addslashes($comment_error)."');\n";	
	echo "	document.getElementById('commentName').value='".$comment_name."';\n";
	echo "	document.getElementById('commentEmail').value='".$emailaddr."';\n";
	echo "	document.getElementById('commentMessage').value='".$blog_comments."';\n";		
	echo "</script>\n";
}
echo "</div>\n";
?>