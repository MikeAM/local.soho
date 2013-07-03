<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.9
##
## Authors: 			Joe Lain, Mike Morrison
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugz.soholaunch.com
## Release Notes:	http://wiki.soholaunch.com
###############################################################################

####################################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2007 Soholaunch.com, Inc.  All Rights Reserved.
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
####################################################################################

###############################################################################
## This script pulls from the blog dB tables to create the promo and newsbox
## features enabled by the precense of particular variables in template HTML
###############################################################################

$newsbox = "";
$newsbox_flex = "";

$promohdr1 = "";
$promotxt1 = "";

$promohdr2 = "";
$promotxt2 = "";

$promohdr3 = "";
$promotxt3 = "";

//$news_cat = $getSpec['news_cat'];
//$promo_cat = $getSpec['promo_cat'];

if ( $news_cat == "" ) { $news_cat = 1; }
if ( $promo_cat == "" ) { $promo_cat = 2; }

$box1 = "";
$box2 = "";
$box3 = "";
$box4 = "";

$boxArray = split(";", $boxCheck);
$arrLen = count($boxArray);
//$box1 .= "(".$arrLen.")<br>";


if(!preg_match('/^(\d)+$/', $_REQUEST['bShow']) && $_REQUEST['bShow']!=""){
	exit;	
}

if(!preg_match('/^(\d)+$/', $_REQUEST['news_cat']) && $_REQUEST['news_cat']!=""){
	exit;	
}

if(!preg_match('/^(\d)+$/', $_REQUEST['promo_cat']) && $_REQUEST['promo_cat']!=""){
	exit;	
}

if(!preg_match('/^(\d)+$/', $_REQUEST['cat']) && $_REQUEST['cat']!=""){
	exit;	
}



# Maintis #250
if ( !function_exists("lang") ) {
   include("sohoadmin/program/includes/shared_functions.php");
}
require_once("sohoadmin/program/includes/SohoEmail_class/SohoEmail.php");
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
			if( $recType == '' ) $recType = "MX";
			// Fix added by cameron 08/01/08
				/////exe c("nslookup -type=$recType $hostName", $result);
				//      // check each line to find the one that starts with the host
				//      // name. If it exists then the function succeeded.
				//      foreach ($result as $line) {
				//        if(eregi("^$hostName",$line)) {
				//          return true;
				//        }
				//      }
				//      // otherwise there was no mail handler for the domain
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


# Pull blog comment settings
$blog_comment_settings = new userdata("blog_comment");

if(!$blog_comment_settings->get("allow_comments") || $blog_comment_settings->get("allow_comments") == "no"){
   $is_allowed = "no";
}else{
   $is_allowed = "yes";
}

if( !$blog_comment_settings->get("require_approval") ){
   $blog_comment_settings->set("require_approval", "yes");
}


#######################################################
### Build #BOX# display     	                      ###
#######################################################
# Process blog comment
if($_REQUEST['process'] == "blog_comment"){

	if($blog_comment_settings->get("captcha") == "yes"){
	
		$bkey = $_REQUEST['blog_key'];
		$ccapval = $_REQUEST['capval_'.$bkey];
		$ccapans = $_REQUEST['cap_'.$bkey];
		
		
		$form_verificationk = '';
		$form_verificationk = $_SESSION['form_verification_blog'][$bkey];
		unset($_SESSION['form_verification_blog']);
		if($form_verificationk != md5(strtoupper($ccapans)) || $form_verificationk == '') {
		  header("Location: http://".$_SESSION['this_ip']."/".pagename($_POST['pr']));
		  echo "<script type=\"text/javascript\"> \n";
		  Echo "document.location='http://".$_SESSION['this_ip']."/".pagename($_POST['pr'])."'; \n";
		  Echo "</script> \n";
		  exit;
		}
		
		if($ccapval != '' && $ccapans != '' && $ccapval == md5(strtoupper($ccapans)) && $form_verificationk == md5(strtoupper($ccapans))) {
		  $_SESSION['form_verification_blog'] = $form_verificationk;     
		} else {
		  header("Location: http://".$_SESSION['this_ip']."/".pagename($_POST['pr']));
		  echo "<script type=\"text/javascript\"> \n";
		  Echo "document.location='http://".$_SESSION['this_ip']."/".pagename($_POST['pr'])."'; \n";
		  Echo "</script> \n";
		  exit;
		}
		
		unset($_POST['capval']);
		unset($_POST['cap']);
		unset($_SESSION['form_verification_blog']);
	
	}
	

   ## ====================================================
   ## DOES blog_comments TABLE EXSIST?
   ## ====================================================
if(!table_exists("blog_comments")){
	create_table("blog_comments");
}
//   $match = 0;
//   
//   $result = mysql_list_tables("$db_name");
//   $i = 0;
//   while ($i < mysql_num_rows ($result)) {
//   	$tb_names[$i] = mysql_tablename ($result, $i);
//   	if (strtolower($tb_names[$i]) == "blog_comments") { $match = 1; }
//   	$i++;
//   }
//   
//   // DOES NOT EXIST; CREATE TABLE NOW
//   ## ====================================================
//   if ($match != 1) {
//   	$qry = "CREATE TABLE blog_comments (";
//   	$qry .= " PRIKEY INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,";
//   	$qry .= " BLOG_KEY INT,";
//   	$qry .= " NAME VARCHAR(255),";
//   	$qry .= " EMAIL BLOB,";
//   	$qry .= " COMMENTS BLOB,";
//   	$qry .= " COMMENT_DATE DATETIME,";
//   	$qry .= " STATUS VARCHAR(255)";
//      $qry .= ")";
//      //ECHO $qry."<br/>";
//   	if (!mysql_db_query("$db_name",$qry)){
//   		echo lang("Could not create table")." blog_comments!<br>";
//   		echo lang("Mysql says")." (".mysql_error().")";
//   		exit;
//   	}
//   }
   
   
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
   $blog_comments = htmlspecialchars(stripslashes(htmlentities($blog_comments, ENT_QUOTES)), ENT_QUOTES);
   
	#CHECK EMAIL ADDRESS BEFORE INSERT
	list($userName, $mailDomain) = split("@", $emailaddr);
	
	if (!myCheckDNSRR($mailDomain,"MX")){
		
	   # CANNOT VERIFY EMAIL... ERROR
      $comment_error = lang("The Email Address you specified could not be verified").".\n";
	}else{
	   # EMAIL VERIFIED... CONTINUE WITH INSERT AND EMAIL
	   
	   # BUT FIRST MAKE SURE THIS IS NOT A RE-POST WITHIN 60 SEC
	   $find_recent = "SELECT PRIKEY, BLOG_KEY, NAME, EMAIL, COMMENT_DATE FROM blog_comments WHERE NAME = '".$comment_name."' AND EMAIL = '".$emailaddr."' ORDER BY COMMENT_DATE DESC LIMIT 1";
      $result = mysql_query($find_recent);
      $PREV_COMMENT = mysql_fetch_array($result);
      
      $timestamp = strtotime($PREV_COMMENT['COMMENT_DATE']) + 60;
      if(mysql_num_rows($result) > 0 && $timestamp > time()){
         $time_error = lang("Were sorry, for security purposes you must wait at least 60 seconds before you post another comment.");
      }else{
         # Test: compare times
         //echo "(".$PREV_COMMENT['PRIKEY'].")(".date('D-j-Y g:ia', $timestamp).") < (".date('D-j-Y g:ia', time()).")<br/>\n";
         //exit;
         
         # NOT A RE-POST... CONTINUE WITH INSERT AND EMAIL
      	$comment_date = time();
      	
      	# Require approval or auto accept comment?
         if( !$blog_comment_settings->get("require_approval") || $blog_comment_settings->get("require_approval") == "yes" ){
            $status = "new";
         }else{
            $status = "approved";
         }
      	
         $blogQry = "INSERT INTO blog_comments VALUES('NULL', '".$blog_key."', '".$comment_name."', '".$emailaddr."', '".$blog_comments."', NOW(), '".$status."')";
         //echo "(".$blogQry.")";
         if ( !mysql_query($blogQry) ) {
            $comment_error = lang("Unable to post blog comment").".  ".lang("Please contact the site webmaster").".";
         }else{
            
            # Approval text displayed depending on status
            if($status == "new"){
               $comment_result_text = lang("Your comment has been posted but will not be displayed until the webmaster has approved it").". ".lang("Thank you").".";
            }else{
               $comment_result_text = lang("Your comment has been posted")."! ".lang("Thank you").".";
            }
            
            //echo "Emailing to (".$SITE_SPECS['df_email'].")<br/>\n";
            
            $result = mysql_query("SELECT df_email FROM site_specs LIMIT 1");
            $SITE_SPECS = mysql_fetch_array($result);
            
            $admin_email = $SITE_SPECS['df_email'];
            
            if(!$blog_comment_settings->get("emailto")){
               $blog_comment_settings->set("emailto", $admin_email);
            }else{
               $admin_email = $blog_comment_settings->get("emailto");
            }
            
            $to_email = $admin_email;
            $boundary = md5(uniqid(time()));
            
            # Thank you email to buyer
            $email_header = "";
            $email_header .= "From: noreply@".$_SESSION['this_ip']."\n";
            $email_header .= "MIME-Version: 1.0\n";
            $email_header .= "Content-Type: multipart/alternative;\n";
            $email_header .= "	boundary=\"=".$boundary."\"\n";

            $email_content = "";
            $email_content .= "--=".$boundary."\n";
            $email_content .= "Content-Type: text/plain; charset=\"ISO-8859-1\"\n";
            $email_content .= "Content-Transfer-Encoding: 7bit\n";
            $email_content .= "\n";
            $email_content .= $_SESSION['this_ip']." - ".lang("New blog comment")."\n";
            $email_content .= "---------------------------------------------------------------------------------\n\n";
            $email_content .= lang("A user on your site")." ".$_SESSION['this_ip'].", ".lang("has posted a comment for blog subject")." ".$BLOG_CATEGORY_NAME."\n\n";
            $email_content .= lang("Details of this comment")."...\n";
            $email_content .= "> ".lang("Name").": ".stripslashes($comment_name)."\n";
            $email_content .= "> ".lang("Email").": ".stripslashes($emailaddr)."\n";
            $email_content .= "> ".lang("Comments").": ".nl2br(stripslashes(html_entity_decode($blog_comments, ENT_QUOTES)))."\n";
            $email_content .= "> ".lang("Comment Time").": ".date('D-j-Y g:ia', $comment_date)."\n\n";
            # Instructions on comment status
            if($status == "new"){
               $email_content .= lang("Please login to your site and navigate to")." ".lang("Main Menu")." > ".lang("Blog Manager")." > ".lang("Blog Comments")." ".lang("to approve or deny this comment").".\n\n";
            }else{
               $email_content .= lang("This comment has been automatically approved and will display in the blog comments").".  ".lang("If you wish to require approval before displaying comments, please login to your site and navigate to")." ".lang("Main Menu")." > ".lang("Blog Manager")." > ".lang("Blog Comments")." > ".lang("Settings and check the 'Require webmaster approval' box").".\n\n";
            }
            $email_content .= lang("Thank you")."!\n\n";
            
            $email_content .= "--=".$boundary."\n";
            $email_content .= "Content-Type: text/html; charset=\"ISO-8859-1\"\n";
            $email_content .= "Content-Transfer-Encoding: quoted-printable\n";
            
            $email_content .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
            $email_content .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
            $email_content .= "<head>\n";
            $email_content .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n";
            $email_content .= "<title>Untitled Document</title>\n";
            $email_content .= "</head>\n";
            $email_content .= "<style type=\"text/css\">\n";
            $email_content .= "a {\n";
            $email_content .= "   color: #6699CC;\n";
            $email_content .= "   text-decoration:none;\n";
            $email_content .= "}\n";
            $email_content .= "h2 {\n";
            $email_content .= "	font-size: 15px;\n";
            $email_content .= "}\n";
            $email_content .= ".style1 {color: #990000}\n";
            $email_content .= "</style>\n";
            $email_content .= "<body>\n";
            $email_content .= "<div align=\"center\" style=\"padding: 10px;\">\n";
            $email_content .= " <div align=\"left\" style=\"width: 600px; padding: 10px; border: 1px solid #666666; font-family: 'Trebuchet MS', Arial; font-size:12px;\">\n";
            $email_content .= "  <h2>".lang("New blog comment")."</h2>\n";
            $email_content .= "  <p>".lang("A user on your site")." ".$_SESSION['this_ip'].", ".lang("has posted a comment for blog subject")." ".$BLOG_CATEGORY_NAME.".\n";
            $email_content .= "  </p>\n";
            $email_content .= "  <p>".lang("Details of this comment")."...</p>\n";
            $email_content .= " <ul>\n";
            $email_content .= "  <li><b>".lang("Name").":</b> ".stripslashes($comment_name)."</li>\n"; 
            $email_content .= "  <li><b>".lang("Email").":</b> ".stripslashes($emailaddr)."</li>\n";
            $email_content .= "  <li><b>".lang("Comments").":</b> ".nl2br(stripslashes(html_entity_decode($blog_comments, ENT_QUOTES)))."</li>\n";
            $email_content .= "  <li><b>".lang("Comment Time").":</b> ".date('D-j-Y g:ia', $comment_date)."</li>\n";
            $email_content .= " </ul>\n";
            
            # Instructions on comment status
            if($status == "new"){
               $email_content .= "  <p>".lang("Please login to your site and navigate to")." ".lang("Main Menu")." > ".lang("Blog Manager")." > ".lang("Blog Comments to approve or deny this comment").".</p>\n";
            }else{
               $email_content .= "  <p>".lang("This comment has been automatically approved and will display in the blog comments").".  ".lang("If you wish to require approval before displaying comments, please login to your site and navigate to")." ".lang("Main Menu")." > ".lang("Blog Manager")." > ".lang("Blog Comments")." > ".lang("Settings and check the 'Require webmaster approval' box").".</p>\n";
            }
            
            $email_content .= "  <p>Thank you!</p>\n";
            $email_content .= " </div>\n";
            $email_content .= "</div>\n";
            $email_content .= "</body>\n";
            $email_content .= "</html>\n";
            if(!SohoEmail($to_email, "noreply@".$_SESSION['this_ip'], 'New Blog Comment', "<html><body>".str_replace("\n","<br/>",$email_content)."</body></html>")){
            	mail("$to_email", "New Blog Comment", "$email_content", $email_header);
		  }
            
         }
      }
   }
}




for($x=0;$x<$arrLen;$x++){
   if (eregi("box", $boxArray[$x]) && !eregi("news", $boxArray[$x]) && !eregi("title", $boxArray[$x])){

      $boxNum = eregi_replace("box", "", $boxArray[$x]);
      $fileBox = $promoFile.$boxNum;
      //echo "(".$fileBox.")";

      $selBox = mysql_query("SELECT * FROM promo_boxes WHERE FILE = '$fileBox'");
      $num_found = mysql_num_rows($selBox);
      
      if($num_found == 0){
         $selBox = mysql_query("SELECT * FROM promo_boxes WHERE BOX = 'box25'");
      }
      $getBox = mysql_fetch_array($selBox);

//      echo testArray($getBox);

      $settings['content_src'] = $getBox['content_src'];
      $settings['content_type'] = $getBox['content_type'];
      $settings['CONTENT'] = unserialize($getBox['CONTENT']);
      $settings['NUM_DISPLAY'] = unserialize($getBox['NUM_DISPLAY']);
      $settings['DISP_TITLE'] = unserialize($getBox['DISP_TITLE']);
      $settings['DISP_CONTENT'] = unserialize($getBox['DISP_CONTENT']);
      $settings['DISP_DATE'] = unserialize($getBox['DISP_DATE']);
      $settings['DISP_MORE'] = unserialize($getBox['DISP_MORE']);
      $settings['SETTINGS'] = unserialize($getBox['SETTINGS']);

      if ( $settings['CONTENT']['display'] == "off" ) {
      	##### BOX DISPLAY ALL OFF #####
      	${"box".$boxNum} = "";

      } else {

         # What kind of box?
         if ( $settings['content_type'] == "sitepal" ) {
            /*-------------------------------------------------------------------------------------------------------*
             ___  _  _         ___        _
            / __|(_)| |_  ___ | _ \ __ _ | |
            \__ \| ||  _|/ -_)|  _// _` || |
            |___/|_| \__|\___||_|  \__,_||_|

            # Show sitepal character in this box
            /*-------------------------------------------------------------------------------------------------------*/

            # Account for pre-register_globals=off code
            if ( $_REQUEST['pr'] == "" && $pr != "" ) { $_REQUEST['pr'] = $pr; }

            # SitePal rule for this page?
            $pr_check = str_replace("_", " ", $_REQUEST['pr']);
            $qry = "select * from smt_sitepal_rules where page_name = '".$pr_check."'";
            $rez = mysql_query($qry);
            if ( mysql_num_rows($rez) < 1 ) {
               # No page rule found, pull default
               $qry = "select * from smt_sitepal_rules where page_name = 'default'";
               $rez = mysql_query($qry);
            }
            $getSp = mysql_fetch_assoc($rez);

//            echo "<h1>[".$scene_num."]</h1>";

            $boxHTML = "";
            $boxHTML .= "\n<script language=\"JavaScript\" type=\"text/javascript\"";
            $boxHTML .= " src=\"http://vhost.oddcast.com/vhost_embed_functions.php?acc=".$getSp['account_id']."&followCursor=1&js=1\"></script>\n";
            $boxHTML .= "<script language=\"JavaScript\" type=\"text/javascript\">\n";
            $boxHTML .= " AC_VHost_Embed_".$getSp['account_id']."(".$getSp['height'].",".$getSp['width'].",'".$getSp['bgcolor']."',1,1,".$getSp['scene_id'].",0,0,0,'917d80ee584ab23dc9acd94cc1ddae0e',6);\n";
            $boxHTML .= "</script>\n";

            # Final box content output
            ${"box".$boxNum} = $boxHTML;


         } else {
            /*--------------------------------------------------------------------------------------------------------*
             ___  _
            | _ )| | ___  __ _
            | _ \| |/ _ \/ _` |
            |___/|_|\___/\__, |
                         |___/
            # Pull blog content for this box
            /*---------------------------------------------------------------------------------------------------------*/

   		   ##### BOX DISPLAY ON #####
   	      $selBlogCat = mysql_query("SELECT prikey FROM blog_category WHERE CATEGORY_NAME = '".$settings['CONTENT']['content']."'");
   	      $getBlogCat = mysql_fetch_array($selBlogCat);

   	      $selBlog = mysql_query("SELECT * FROM blog_content WHERE BLOG_SUBJECT = '".$getBlogCat['prikey']."' ORDER BY BLOG_DATE DESC LIMIT 1");
   	      $getBlog = mysql_fetch_array($selBlog);

   	      # **** TEST BOX VALUES ****
   	//      ${"box".$boxNum} .= "The box (".$boxArray[$x].")<br>";
   	//      foreach($getBox as $var=>$val){
   	//         ${"box".$boxNum} .= "var = (".$var.") val = (".$val.")<br>\n";
   	//      }


   	      #######################################################
   				### Box Settings				     	                      ###
   				#######################################################

   	      ### Border settings
   	      #######################################################
   	      if ( $settings['DISP_TITLE']['border'] == "on" ) {
   	         $Tborder = "1px solid #666666";
   	         $TborderStyle = "solid solid solid solid";
   	      } else {
   	         $Tborder = "";
   	         $Tborder = "1px solid #666666";
   	         $TborderStyle = "none none none none";
   	      }
   	      if ( $settings['DISP_CONTENT']['border'] == "on" ) {
   	      	$Cborder = "1px solid #666666";
   	      	 	if ( $settings['DISP_TITLE']['border'] == "on" ) {
   		         	$CborderStyle = "none solid solid solid";
   		        } else {
   		         	$CborderStyle = "solid solid solid solid";
   		        }
   	      } else {
   	         $Cborder = "";
   	         $CborderStyle = "none none none none";
   	      }
   	      if ( $settings['DISP_MORE']['border'] == "on" ) {
   	      	$Mborder = "1px solid #666666";
   	      	 	if ( $settings['DISP_CONTENT']['border'] == "on" ) {
   		         	$MborderStyle = "none solid solid solid";
   		        } else {
   		         	$MborderStyle = "solid solid solid solid";
   		        }
   	      } else {
   	         $Mborder = "";
   	         $MborderStyle = "none none none none";
   	      }
   	      if ( $settings['DISP_DATE']['border'] == "on" ) {
   	      	$Dborder = "1px solid #666666";
   	      	 	if ( $settings['DISP_MORE']['border'] == "on" && $settings['DISP_DATE']['position'] == "dateBottom" ) {
   		         	$DborderStyle = "none solid solid solid";
   		        }elseif ( $settings['DISP_TITLE']['border'] == "on" && $settings['DISP_DATE']['position'] == "dateTop" ) {
   		         	$DborderStyle = "solid solid none solid";
   		        } else {
   		         	$DborderStyle = "solid solid solid solid";
   		        }
   	      } else {
   	         $Dborder = "";
   	         $DborderStyle = "none none none none";
   	      }

   				### Date display settings
   				#######################################################

   	//			// Display Date type
   	//			$DateFirst = "none";
   	//			$DateLast = "none";
   	//			if ( $settings['DISP_DATE']['position'] == "dateFirst" && $settings['DISP_DATE']['display'] == "on" ) {
   	//				$DateFirst = "inline";
   	//			}elseif ( $settings['DISP_DATE']['position'] == "dateLast" && $settings['DISP_DATE']['display'] == "on" ) {
   	//				$DateLast = "inline";
   	//			}

   				// Date Italic
   				$dateItalic = "";
   				if ( $settings['DISP_DATE']['fontStyle'] == "on" ) {
   					$dateItalic = "italic";
   				}

   				// Date Format
   	      if ( $settings['DISP_DATE']['format'] == "allNum" ) {
   	      	$timestamp = strtotime($getBlog['BLOG_DATE']);
   	      	$dateDisp = date('Y-j-n', $timestamp);
   	      }elseif ( $settings['DISP_DATE']['format'] == "allNum2" ) {
   					$timestamp = strtotime($getBlog['BLOG_DATE']);
   					$dateDisp = date('n-j-Y', $timestamp);
   	      }elseif ( $settings['DISP_DATE']['format'] == "full" ) {
   					$timestamp = strtotime($getBlog['BLOG_DATE']);
   					$dateDisp = date('F-j-Y', $timestamp);
   	      }elseif ( $settings['DISP_DATE']['format'] == "full2" ) {
   					$timestamp = strtotime($getBlog['BLOG_DATE']);
   					$dateDisp = date('j-F-Y', $timestamp);
   	      }elseif ( $settings['DISP_DATE']['format'] == "half" ) {
   					$timestamp = strtotime($getBlog['BLOG_DATE']);
   					$dateDisp = date('D-j-Y', $timestamp);
   	      }elseif ( $settings['DISP_DATE']['format'] == "half2" ) {
   					$timestamp = strtotime($getBlog['BLOG_DATE']);
   					$dateDisp = date('j-M-Y', $timestamp);
   	      }

					if($getBlog['BLOG_DATE']==''){
						$timestamp = '';
						$dateDisp = '';
					}

   				// Date Weight
   				$chkDateWeightNormal = "";
   				$chkDateWeightBold = "selected";
   				$chkDateWeight = "bold";
   				if ( $settings['DISP_DATE']['weight'] == "normal" ) {
   				   $chkDateWeightNormal = "selected";
   				   $chkDateWeightBold = "";
   				   $chkDateWeight = "normal";
   				}

   	      # Pre-format news teaser text
   	      if ( $settings['NUM_DISPLAY']['chars'] != "" ) {
   		      $disTease = strip_tags($getBlog['BLOG_DATA'], '<br>');
   		      $disTease = substr("$disTease", 0, $settings['NUM_DISPLAY']['chars']);
   		    } else {
   		    	$disTease = $getBlog['BLOG_DATA'];
   		    }

   	//			${"box".$boxNum} .= $boxArray[$x]."<br><br>";
   	//      ${"box".$boxNum} .= $fileBox;
   	//      ${"box".$boxNum} .= "<b>BOX ".$boxNum."</b><br>\n";

            # LATEST, template = OFF
   	      ${"box".$boxNum} .= "\n\n\n<!--- BEGIN BOX ".$boxNum." --->\n\n\n";
   	      if ( $settings['CONTENT']['type'] == "latest" && $settings['SETTINGS']['template'] != "on" ) {

   	         ${"box".$boxNum} .= "<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">\n";
   	         if ( $settings['DISP_DATE']['position'] == "dateTop" && $settings['DISP_DATE']['display'] == "on" ) {
   	            ${"box".$boxNum} .= " <tr>\n";
   	            ${"box".$boxNum} .= "  <td align=\"".$settings['DISP_DATE']['align']."\" style=\"font-weight: ".$settings['DISP_DATE']['weight']."; font-style: ".$dateItalic."; border: ".$Dborder."; border-style: ".$DborderStyle.";\">\n";
   	            ${"box".$boxNum} .= "   ".$dateDisp."\n";
   	            ${"box".$boxNum} .= "  </td>\n";
   	            ${"box".$boxNum} .= " </tr>\n";
   	         }
   	         if ( $settings['DISP_TITLE']['display'] == "on" ) {
   	            ${"box".$boxNum} .= " <tr>\n";
   	            ${"box".$boxNum} .= "  <td align=\"".$settings['DISP_TITLE']['align']."\" style=\"font-weight: ".$settings['DISP_TITLE']['weight']."; border: ".$Tborder."; border-style: ".$TborderStyle."; \">\n";
   	            if ( $settings['DISP_DATE']['position'] == "dateFirst" && $settings['DISP_DATE']['display'] == "on" ) {
   	            	${"box".$boxNum} .= " <span id=\"dateFirst\" style=\"font-style: ".$dateItalic."; font-weight: ".$chkDateWeight."; font-size: 11px;\">".$dateDisp."</span>\n";
   	            }
   	            ${"box".$boxNum} .= " ".stripslashes($getBlog['BLOG_TITLE'])."\n";
   	            if ( $settings['DISP_DATE']['position'] == "dateLast" && $settings['DISP_DATE']['display'] == "on" ) {
   								${"box".$boxNum} .= " <span id=\"dateLast\" style=\"font-style: ".$dateItalic."; font-weight: ".$chkDateWeight."; font-size: 11px;\">".$dateDisp."</span>\n";
   							}
   	            ${"box".$boxNum} .= " </td></tr>\n";
   	         }
   	         if ( $settings['DISP_CONTENT']['display'] == "on" ) {
   	            ${"box".$boxNum} .= " <tr>\n";
   	            ${"box".$boxNum} .= "  <td style=\"border: ".$Cborder."; border-style: ".$CborderStyle.";\">\n";
   	            ${"box".$boxNum} .= "   ".stripslashes($disTease)."\n";
   	            ${"box".$boxNum} .= "  </td>\n";
   	            ${"box".$boxNum} .= " </tr>\n";
   	         }   	         
   	         if ( $settings['DISP_MORE']['display'] == "on" && $disTease != '') {
   	            ${"box".$boxNum} .= " <tr>\n";
   	            ${"box".$boxNum} .= "  <td align=\"".$settings['DISP_MORE']['align']."\" style=\"font-weight: ".$settings['DISP_MORE']['weight']."; font-size: 10px; border: ".$Mborder."; border-style: ".$MborderStyle.";\">\n";
   	            ${"box".$boxNum} .= "   <a href=\"index.php?bShow=".$getBlog['PRIKEY']."&cat=".$getBlogCat['prikey']."\">".stripslashes($settings['DISP_MORE']['text'])."</a>\n";
   	            ${"box".$boxNum} .= "  </td>\n";
   	            ${"box".$boxNum} .= " </tr>\n";
   	         }
   	         if ( $settings['DISP_DATE']['position'] == "dateBottom" && $settings['DISP_DATE']['display'] == "on" ) {
   	            ${"box".$boxNum} .= " <tr>\n";
   	            ${"box".$boxNum} .= "  <td align=\"".$settings['DISP_DATE']['align']."\" style=\"font-weight: ".$settings['DISP_DATE']['weight']."; font-style: ".$dateItalic."; border: ".$Dborder."; border-style: ".$DborderStyle.";\">\n";
   	            ${"box".$boxNum} .= "   ".$dateDisp."\n";
   	            ${"box".$boxNum} .= "  </td>\n";
   	            ${"box".$boxNum} .= " </tr>\n";
   	         }
   	         ${"box".$boxNum} .= "</table>\n";
   	      }

            # LATEST, template = ON
   	      //${"box".$boxNum} .= "\n\n\n<!--- BEGIN BOX ".$boxNum." --->\n\n\n";
   	      if ( $settings['CONTENT']['type'] == "latest" && $settings['SETTINGS']['template'] == "on" ) {

   	         ${"box".$boxNum} .= "<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">\n";
   	         if ( $settings['DISP_DATE']['position'] == "dateTop" && $settings['DISP_DATE']['display'] == "on" ) {
   	            ${"box".$boxNum} .= " <tr>\n";
   	            ${"box".$boxNum} .= "  <td class=\"boxDate".$boxNum."\">\n";
   	            ${"box".$boxNum} .= "   ".$dateDisp."\n";
   	            ${"box".$boxNum} .= "  </td>\n";
   	            ${"box".$boxNum} .= " </tr>\n";
   	         }
   	         if ( $settings['DISP_TITLE']['display'] == "on" ) {
   	            ${"box".$boxNum} .= " <tr>\n";
   	            ${"box".$boxNum} .= "  <td class=\"boxTitle".$boxNum."\">\n";
   	            if ( $settings['DISP_DATE']['position'] == "dateFirst" && $settings['DISP_DATE']['display'] == "on" ) {
   	            	${"box".$boxNum} .= " <span class=\"boxDateTitle".$boxNum."\">".$dateDisp."</span>\n";
   	            }
   	            ${"box".$boxNum} .= " ".stripslashes($getBlog['BLOG_TITLE'])."\n";
   	            if ( $settings['DISP_DATE']['position'] == "dateLast" && $settings['DISP_DATE']['display'] == "on" ) {
   								${"box".$boxNum} .= " <span class=\"boxDateTitle".$boxNum."\">".$dateDisp."</span>\n";
   							}
   	            ${"box".$boxNum} .= " </td></tr>\n";
   	         }
   	         if ( $settings['DISP_CONTENT']['display'] == "on" ) {
   	            ${"box".$boxNum} .= " <tr>\n";
   	            ${"box".$boxNum} .= "  <td class=\"boxContent".$boxNum."\">\n";
   	            ${"box".$boxNum} .= "   ".stripslashes($disTease)."\n";
   	            ${"box".$boxNum} .= "  </td>\n";
   	            ${"box".$boxNum} .= " </tr>\n";
   	         }
   	         if ( $settings['DISP_MORE']['display'] == "on" && $disTease != '') {
   	            ${"box".$boxNum} .= " <tr>\n";
   	            ${"box".$boxNum} .= "  <td class=\"boxReadMore".$boxNum."\">\n";
   	            ${"box".$boxNum} .= "   <a href=\"index.php?bShow=".$getBlog['PRIKEY']."&cat=".$getBlogCat['prikey']."\">".stripslashes($settings['DISP_MORE']['text'])."</a>\n";
   	            ${"box".$boxNum} .= "  </td>\n";
   	            ${"box".$boxNum} .= " </tr>\n";
   	         }
   	         if ( $settings['DISP_DATE']['position'] == "dateBottom" && $settings['DISP_DATE']['display'] == "on" ) {
   	            ${"box".$boxNum} .= " <tr>\n";
   	            ${"box".$boxNum} .= "  <td class=\"boxDate".$boxNum."\">\n";
   	            ${"box".$boxNum} .= "   ".$dateDisp."\n";
   	            ${"box".$boxNum} .= "  </td>\n";
   	            ${"box".$boxNum} .= " </tr>\n";
   	         }
   	         ${"box".$boxNum} .= "</table>\n";
   	      }


            /*---------------------------------------------------------------------------------------------------------*
             __  __        _  _    _
            |  \/  | _  _ | || |_ (_)
            | |\/| || || || ||  _|| |
            |_|  |_| \_,_||_| \__||_|

            # Build display for multiple blog output
            /*---------------------------------------------------------------------------------------------------------*/
            if ( $settings['CONTENT']['type'] == "muli" ) {
      	      $blogCnt = 1;
      	      ${"box".$boxNum} .= "<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">\n";
      	      $selBlog = mysql_query("SELECT * FROM blog_content WHERE BLOG_SUBJECT = '".$getBlogCat['prikey']."' ORDER BY BLOG_DATE DESC LIMIT ".$settings['NUM_DISPLAY']['blog']."");
      	      while($getBlog = mysql_fetch_array($selBlog) ) {
      				// Date Format
         	      if ( $settings['DISP_DATE']['format'] == "allNum" ) {
         	      	$timestamp = strtotime($getBlog['BLOG_DATE']);
         	      	$dateDisp = date('Y-j-n', $timestamp);
         	      } elseif ( $settings['DISP_DATE']['format'] == "allNum2" ) {
         			   $timestamp = strtotime($getBlog['BLOG_DATE']);
         				$dateDisp = date('n-j-Y', $timestamp);
         	      } elseif ( $settings['DISP_DATE']['format'] == "full" ) {
         				$timestamp = strtotime($getBlog['BLOG_DATE']);
         				$dateDisp = date('F-j-Y', $timestamp);
         	      } elseif ( $settings['DISP_DATE']['format'] == "full2" ) {
         				$timestamp = strtotime($getBlog['BLOG_DATE']);
         				$dateDisp = date('j-F-Y', $timestamp);
         	      } elseif ( $settings['DISP_DATE']['format'] == "half" ) {
         				$timestamp = strtotime($getBlog['BLOG_DATE']);
         				$dateDisp = date('D-j-Y', $timestamp);
         	      } elseif ( $settings['DISP_DATE']['format'] == "half2" ) {
         				$timestamp = strtotime($getBlog['BLOG_DATE']);
         				$dateDisp = date('j-M-Y', $timestamp);
         	      }

      	         # Pre-format news teaser text
      	         if ( $settings['NUM_DISPLAY']['chars'] != "" ) {
      		         $disTease = strip_tags($getBlog['BLOG_DATA'], '<br>');
      		         $disTease = substr("$disTease", 0, $settings['NUM_DISPLAY']['chars']);
      		      } else {
      		    	   $disTease = $getBlog['BLOG_DATA'];
      		      }


      				######################################################################
      				### Build display for multiple blog output(user settings)          ###
      				######################################################################
      	         if ( $settings['CONTENT']['type'] == "muli" && $settings['SETTINGS']['template'] != "on" ) {
                     if ( $settings['DISP_DATE']['position'] == "dateTop" && $settings['DISP_DATE']['display'] == "on" ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td align=\"".$settings['DISP_DATE']['align']."\" style=\"font-weight: ".$settings['DISP_DATE']['weight']."; font-style: ".$dateItalic."; border: ".$Dborder."; border-style: ".$DborderStyle.";\">\n";
                        ${"box".$boxNum} .= "   ".$dateDisp."\n";
                        ${"box".$boxNum} .= "  </td>\n";
                        ${"box".$boxNum} .= " </tr>\n";
                     }
                     if ( $settings['DISP_TITLE']['display'] == "on" ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td align=\"".$settings['DISP_TITLE']['align']."\" style=\"font-weight: ".$settings['DISP_TITLE']['weight']."; border: ".$Tborder."; border-style: ".$TborderStyle."; \">\n";
                        if ( $settings['DISP_DATE']['position'] == "dateFirst" && $settings['DISP_DATE']['display'] == "on" ) {
                              ${"box".$boxNum} .= " <span id=\"dateFirst\" style=\"font-style: ".$dateItalic."; font-weight: ".$chkDateWeight."; font-size: 11px;\">".$dateDisp."</span>\n";
                        }
                        ${"box".$boxNum} .= " ".stripslashes($getBlog['BLOG_TITLE'])."\n";
                        if ( $settings['DISP_DATE']['position'] == "dateLast" && $settings['DISP_DATE']['display'] == "on" ) {
                              ${"box".$boxNum} .= " <span id=\"dateLast\" style=\"font-style: ".$dateItalic."; font-weight: ".$chkDateWeight."; font-size: 11px;\">".$dateDisp."</span>\n";
                         }
                        ${"box".$boxNum} .= " </td></tr>\n";
                     }
                     if ( $settings['DISP_CONTENT']['display'] == "on" ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td style=\"border: ".$Cborder."; border-style: ".$CborderStyle.";\">\n";
                        ${"box".$boxNum} .= "   ".stripslashes($disTease)."\n";
                        ${"box".$boxNum} .= "  </td>\n";
                        ${"box".$boxNum} .= " </tr>\n";
                     }
                     if ( $settings['DISP_MORE']['display'] == "on" ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td align=\"".$settings['DISP_MORE']['align']."\" style=\"font-weight: ".$settings['DISP_MORE']['weight']."; font-size: 10px; border: ".$Mborder."; border-style: ".$MborderStyle.";\">\n";
                        ${"box".$boxNum} .= "   <a href=\"index.php?bShow=".$getBlog['PRIKEY']."&cat=".$getBlogCat['prikey']."\">".stripslashes($settings['DISP_MORE']['text'])."</a>\n";
                        ${"box".$boxNum} .= "  </td>\n";
                        ${"box".$boxNum} .= " </tr>\n";
                     }
                     if ( $settings['DISP_DATE']['position'] == "dateBottom" && $settings['DISP_DATE']['display'] == "on" ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td align=\"".$settings['DISP_DATE']['align']."\" style=\"font-weight: ".$settings['DISP_DATE']['weight']."; font-style: ".$dateItalic."; border: ".$Dborder."; border-style: ".$DborderStyle.";\">\n";
                        ${"box".$boxNum} .= "   ".$dateDisp."\n";
                        ${"box".$boxNum} .= "  </td>\n";
                        ${"box".$boxNum} .= " </tr>\n";
                     }
                     if ( $settings['NUM_DISPLAY']['blog'] > $blogCnt ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td>\n";
                        ${"box".$boxNum} .= "   &nbsp;\n";
                        ${"box".$boxNum} .= "  </td>\n";
                        ${"box".$boxNum} .= " </tr>\n";
                        $blogCnt++;
                     }
      	         }


      				##########################################################################
      				### Build display for multiple blog output(template settings)          ###
      				##########################################################################
      	         if ( $settings['CONTENT']['type'] == "muli" && $settings['SETTINGS']['template'] == "on" ) {
                     if ( $settings['DISP_DATE']['position'] == "dateTop" && $settings['DISP_DATE']['display'] == "on" ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td class=\"boxDate".$boxNum."\">\n";
                        ${"box".$boxNum} .= "   ".$dateDisp."\n";
                        ${"box".$boxNum} .= "  </td>\n";
                        ${"box".$boxNum} .= " </tr>\n";
                     }
                     if ( $settings['DISP_TITLE']['display'] == "on" ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td class=\"boxTitle".$boxNum."\">\n";
                        if ( $settings['DISP_DATE']['position'] == "dateFirst" && $settings['DISP_DATE']['display'] == "on" ) {
                           ${"box".$boxNum} .= " <span class=\"boxDateTitle".$boxNum."\">".$dateDisp."</span>\n";
                        }
                        ${"box".$boxNum} .= " ".stripslashes($getBlog['BLOG_TITLE'])."\n";
                        if ( $settings['DISP_DATE']['position'] == "dateLast" && $settings['DISP_DATE']['display'] == "on" ) {
                           ${"box".$boxNum} .= " <span class=\"boxDateTitle".$boxNum."\">".$dateDisp."</span>\n";
                        }
                        ${"box".$boxNum} .= " </td></tr>\n";
                     }
                     if ( $settings['DISP_CONTENT']['display'] == "on" ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td class=\"boxContent".$boxNum."\">\n";
                        ${"box".$boxNum} .= "   ".stripslashes($disTease)."\n";
                        ${"box".$boxNum} .= "  </td>\n";
                        ${"box".$boxNum} .= " </tr>\n";
                     }
                     if ( $settings['DISP_MORE']['display'] == "on" ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td class=\"boxReadMore".$boxNum."\">\n";
                        ${"box".$boxNum} .= "   <a href=\"index.php?bShow=".$getBlog['PRIKEY']."&cat=".$getBlogCat['prikey']."\">".stripslashes($settings['DISP_MORE']['text'])."</a>\n";
                        ${"box".$boxNum} .= "  </td>\n";
                        ${"box".$boxNum} .= " </tr>\n";
                     }
                     if ( $settings['DISP_DATE']['position'] == "dateBottom" && $settings['DISP_DATE']['display'] == "on" ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td class=\"boxDate".$boxNum."\">\n";
                        ${"box".$boxNum} .= "   ".$dateDisp."\n";
                        ${"box".$boxNum} .= "  </td>\n";
                        ${"box".$boxNum} .= " </tr>\n";
                     }
                     if ( $settings['NUM_DISPLAY']['blog'] > $blogCnt ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td class=\"boxSpacer".$boxNum."\">\n";
                        ${"box".$boxNum} .= "   &nbsp;\n";
                        ${"box".$boxNum} .= "  </td>\n";
                        ${"box".$boxNum} .= " </tr>\n";
                        $blogCnt++;
                     }
      	         } // End if type = "muli" and template = "on"

      	      } // End while loop through multiple entries
      	      ${"box".$boxNum} .= "</table>\n";

      	   } // End if content type = "muli" (multi)


            /*---------------------------------------------------------------------------------------------------------*
             ___                 _
            | _ \ __ _  _ _   __| | ___  _ __
            |   // _` || ' \ / _` |/ _ \| '  \
            |_|_\\__,_||_||_|\__,_|\___/|_|_|_|
            # Build display type: One entry selected at random
            /*---------------------------------------------------------------------------------------------------------*/
            if ( $settings['CONTENT']['type'] == "random" ) {
      	      $blogCnt = 1;
      	      ${"box".$boxNum} .= "<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">\n";
      	      $selBlog = mysql_query("SELECT * FROM blog_content WHERE BLOG_SUBJECT = '".$getBlogCat['prikey']."' ORDER BY RAND() LIMIT 1");
      	      while( $getBlog = mysql_fetch_array($selBlog) ) {
      				// Date Format
         	      if ( $settings['DISP_DATE']['format'] == "allNum" ) {
         	      	$timestamp = strtotime($getBlog['BLOG_DATE']);
         	      	$dateDisp = date('Y-j-n', $timestamp);
         	      } elseif ( $settings['DISP_DATE']['format'] == "allNum2" ) {
         			   $timestamp = strtotime($getBlog['BLOG_DATE']);
         				$dateDisp = date('n-j-Y', $timestamp);
         	      } elseif ( $settings['DISP_DATE']['format'] == "full" ) {
         				$timestamp = strtotime($getBlog['BLOG_DATE']);
         				$dateDisp = date('F-j-Y', $timestamp);
         	      } elseif ( $settings['DISP_DATE']['format'] == "full2" ) {
         				$timestamp = strtotime($getBlog['BLOG_DATE']);
         				$dateDisp = date('j-F-Y', $timestamp);
         	      } elseif ( $settings['DISP_DATE']['format'] == "half" ) {
         				$timestamp = strtotime($getBlog['BLOG_DATE']);
         				$dateDisp = date('D-j-Y', $timestamp);
         	      } elseif ( $settings['DISP_DATE']['format'] == "half2" ) {
         				$timestamp = strtotime($getBlog['BLOG_DATE']);
         				$dateDisp = date('j-M-Y', $timestamp);
         	      }

      	         # Pre-format news teaser text
      	         if ( $settings['NUM_DISPLAY']['chars'] != "" ) {
      		         $disTease = strip_tags($getBlog['BLOG_DATA'], '<br>');
      		         $disTease = substr("$disTease", 0, $settings['NUM_DISPLAY']['chars']);
      		      } else {
      		    	   $disTease = $getBlog['BLOG_DATA'];
      		      }

      		      # RANDOM - template = OFF
      	         if ( $settings['CONTENT']['type'] == "random" && $settings['SETTINGS']['template'] != "on" ) {
                     if ( $settings['DISP_DATE']['position'] == "dateTop" && $settings['DISP_DATE']['display'] == "on" ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td align=\"".$settings['DISP_DATE']['align']."\" style=\"font-weight: ".$settings['DISP_DATE']['weight']."; font-style: ".$dateItalic."; border: ".$Dborder."; border-style: ".$DborderStyle.";\">\n";
                        ${"box".$boxNum} .= "   ".$dateDisp."\n";
                        ${"box".$boxNum} .= "  </td>\n";
                        ${"box".$boxNum} .= " </tr>\n";
                     }
                     if ( $settings['DISP_TITLE']['display'] == "on" ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td align=\"".$settings['DISP_TITLE']['align']."\" style=\"font-weight: ".$settings['DISP_TITLE']['weight']."; border: ".$Tborder."; border-style: ".$TborderStyle."; \">\n";
                        if ( $settings['DISP_DATE']['position'] == "dateFirst" && $settings['DISP_DATE']['display'] == "on" ) {
                              ${"box".$boxNum} .= " <span id=\"dateFirst\" style=\"font-style: ".$dateItalic."; font-weight: ".$chkDateWeight."; font-size: 11px;\">".$dateDisp."</span>\n";
                        }
                        ${"box".$boxNum} .= " ".stripslashes($getBlog['BLOG_TITLE'])."\n";
                        if ( $settings['DISP_DATE']['position'] == "dateLast" && $settings['DISP_DATE']['display'] == "on" ) {
                              ${"box".$boxNum} .= " <span id=\"dateLast\" style=\"font-style: ".$dateItalic."; font-weight: ".$chkDateWeight."; font-size: 11px;\">".$dateDisp."</span>\n";
                         }
                        ${"box".$boxNum} .= " </td></tr>\n";
                     }
                     if ( $settings['DISP_CONTENT']['display'] == "on" ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td style=\"border: ".$Cborder."; border-style: ".$CborderStyle.";\">\n";
                        ${"box".$boxNum} .= "   ".stripslashes($disTease)."\n";
                        ${"box".$boxNum} .= "  </td>\n";
                        ${"box".$boxNum} .= " </tr>\n";
                     }
                     if ( $settings['DISP_MORE']['display'] == "on" ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td align=\"".$settings['DISP_MORE']['align']."\" style=\"font-weight: ".$settings['DISP_MORE']['weight']."; font-size: 10px; border: ".$Mborder."; border-style: ".$MborderStyle.";\">\n";
                        ${"box".$boxNum} .= "   <a href=\"index.php?bShow=".$getBlog['PRIKEY']."&cat=".$getBlogCat['prikey']."\">".stripslashes($settings['DISP_MORE']['text'])."</a>\n";
                        ${"box".$boxNum} .= "  </td>\n";
                        ${"box".$boxNum} .= " </tr>\n";
                     }
                     if ( $settings['DISP_DATE']['position'] == "dateBottom" && $settings['DISP_DATE']['display'] == "on" ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td align=\"".$settings['DISP_DATE']['align']."\" style=\"font-weight: ".$settings['DISP_DATE']['weight']."; font-style: ".$dateItalic."; border: ".$Dborder."; border-style: ".$DborderStyle.";\">\n";
                        ${"box".$boxNum} .= "   ".$dateDisp."\n";
                        ${"box".$boxNum} .= "  </td>\n";
                        ${"box".$boxNum} .= " </tr>\n";
                     }
                     if ( $settings['NUM_DISPLAY']['blog'] > $blogCnt ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td>\n";
                        ${"box".$boxNum} .= "   &nbsp;\n";
                        ${"box".$boxNum} .= "  </td>\n";
                        ${"box".$boxNum} .= " </tr>\n";
                        $blogCnt++;
                     }
      	         }


      				# RANDOM - template = ON
      	         if ( $settings['CONTENT']['type'] == "random" && $settings['SETTINGS']['template'] == "on" ) {
                     if ( $settings['DISP_DATE']['position'] == "dateTop" && $settings['DISP_DATE']['display'] == "on" ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td class=\"boxDate".$boxNum."\">\n";
                        ${"box".$boxNum} .= "   ".$dateDisp."\n";
                        ${"box".$boxNum} .= "  </td>\n";
                        ${"box".$boxNum} .= " </tr>\n";
                     }
                     if ( $settings['DISP_TITLE']['display'] == "on" ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td class=\"boxTitle".$boxNum."\">\n";
                        if ( $settings['DISP_DATE']['position'] == "dateFirst" && $settings['DISP_DATE']['display'] == "on" ) {
                           ${"box".$boxNum} .= " <span class=\"boxDateTitle".$boxNum."\">".$dateDisp."</span>\n";
                        }
                        ${"box".$boxNum} .= " ".stripslashes($getBlog['BLOG_TITLE'])."\n";
                        if ( $settings['DISP_DATE']['position'] == "dateLast" && $settings['DISP_DATE']['display'] == "on" ) {
                           ${"box".$boxNum} .= " <span class=\"boxDateTitle".$boxNum."\">".$dateDisp."</span>\n";
                        }
                        ${"box".$boxNum} .= " </td></tr>\n";
                     }
                     if ( $settings['DISP_CONTENT']['display'] == "on" ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td class=\"boxContent".$boxNum."\">\n";
                        ${"box".$boxNum} .= "   ".stripslashes($disTease)."\n";
                        ${"box".$boxNum} .= "  </td>\n";
                        ${"box".$boxNum} .= " </tr>\n";
                     }
                     if ( $settings['DISP_MORE']['display'] == "on" ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td class=\"boxReadMore".$boxNum."\">\n";
                        ${"box".$boxNum} .= "   <a href=\"index.php?bShow=".$getBlog['PRIKEY']."&cat=".$getBlogCat['prikey']."\">".stripslashes($settings['DISP_MORE']['text'])."</a>\n";
                        ${"box".$boxNum} .= "  </td>\n";
                        ${"box".$boxNum} .= " </tr>\n";
                     }
                     if ( $settings['DISP_DATE']['position'] == "dateBottom" && $settings['DISP_DATE']['display'] == "on" ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td class=\"boxDate".$boxNum."\">\n";
                        ${"box".$boxNum} .= "   ".$dateDisp."\n";
                        ${"box".$boxNum} .= "  </td>\n";
                        ${"box".$boxNum} .= " </tr>\n";
                     }
                     if ( $settings['NUM_DISPLAY']['blog'] > $blogCnt ) {
                        ${"box".$boxNum} .= " <tr>\n";
                        ${"box".$boxNum} .= "  <td class=\"boxSpacer".$boxNum."\">\n";
                        ${"box".$boxNum} .= "   &nbsp;\n";
                        ${"box".$boxNum} .= "  </td>\n";
                        ${"box".$boxNum} .= " </tr>\n";
                        $blogCnt++;
                     }
      	         }

      	      } // End while loop through random blog entry
      	      ${"box".$boxNum} .= "</table>\n";

      	   } // end if content type = "random"

   	      ${"box".$boxNum} .= "\n\n\n<!--- END BOX ".$boxNum." --->\n\n\n";

   	   } // End else content_type = blog

	    } // End else box not disabled
   }
}

$boxArray = split(";", $boxCheck);
$arrLen = count($boxArray);

#######################################################
### Build #BOX-TITLE# display	                      ###
#######################################################

for($x=0;$x<$arrLen;$x++ ) {
	if ( eregi("box-title([0-9])", $boxArray[$x], $snip) ) {
		$daBox = $snip[1];
		$fileBox = $promoFile.$daBox;

      $selBox = mysql_query("SELECT * FROM promo_boxes WHERE FILE = '$fileBox'");
      $getBox = mysql_fetch_array($selBox);

      $settings['CONTENT'] = unserialize($getBox['CONTENT']);


      if ( $settings['CONTENT']['display'] == "off" ) {
      	##### BOX DISPLAY ALL OFF #####
      	${"box_title".$snip[1]} = "";
      } else {

		   $promo_title = mysql_query("SELECT FILE, FUTURE1 FROM promo_boxes");
		   while($box_titles = mysql_fetch_array($promo_title) ) {
		   	if ( eregi($promoFile.$daBox, $box_titles['FILE']) ) {
		   		$title_promo = unserialize($box_titles['FUTURE1']);

		   		${"box_title".$snip[1]} = $title_promo['title'];
		   		//echo "(".${"box_title".$snip[1]}.")";
					$boxCheck = eregi_replace("box-title".$snip[1].";", "", $boxCheck);
		   	}
		   }
		}
	}
}



// #PROMOHDR# and #PROMOTXT#
// ==========================================
if ( eregi("promohdr", $boxCheck) || eregi("promotxt", $boxCheck) ) {
   $selPromo = mysql_query("SELECT * FROM blog_content WHERE BLOG_SUBJECT = '$promo_cat' ORDER BY rand() DESC LIMIT 3");

   $bx = 1;
   while ( $getPromo = mysql_fetch_array($selPromo) ) {
      ${"promohdr".$bx} = stripslashes($getPromo['BLOG_TITLE']);
      ${"promotxt".$bx} = stripslashes($getPromo['BLOG_DATA']);
      $bx++;
   }
}




// #NEWSBOX# - Dates and linked titles
// ==========================================
if ( eregi("newsbox;", $boxCheck) ) {

   $selNews = mysql_query("SELECT * FROM blog_content WHERE BLOG_SUBJECT = '$news_cat' ORDER BY BLOG_DATE DESC LIMIT 5");
   $b = 0;
   while ( $getNews = mysql_fetch_array($selNews) ) {
      $disNews[$b] .= " <font class=\"newsbox_date\">".stripslashes($getNews['BLOG_DATE']).":</font><br>\n";
      $disNews[$b] .= " <a href=\"index.php?nShow=".$getNews['PRIKEY']."\" class=\"newsbox\">".stripslashes($getNews['BLOG_TITLE'])."</a><br><br>\n";
      $b++;
   }

   $cNews = count($disNews);
   for ( $n=0; $n < $cNews; $n++ ) {
      $newsbox .= "<div class=\"newsbox\">\n";
      $newsbox .= $disNews[$n];
      $newsbox .= "</div>\n";
   }
}


// #NEWSBOX-***# - Dates, linked titles, and specified amount of content
// ============================================================================
if ( eregi("newsbox-([0-9]{1,3})", $boxCheck, $snip) ) {

   $selNews = mysql_query("SELECT * FROM blog_content WHERE BLOG_SUBJECT = '$news_cat' ORDER BY BLOG_DATE DESC LIMIT 5");
   $b = 0;
   while ( $getNews = mysql_fetch_array($selNews) ) {

      # Pre-format news teaser text
      $disTease = strip_tags(stripslashes($getNews['BLOG_DATA']), '<br>');
      $disTease = substr("$disTease", 0, $snip[1]);

      # Build news items
      $disfNews[$b] .= " <font class=\"newsbox_date\">".stripslashes($getNews['BLOG_DATE']).":</font><br>\n";
      $disfNews[$b] .= " <a href=\"index.php?nShow=".$getNews['PRIKEY']."\" class=\"newsbox\">".stripslashes($getNews['BLOG_TITLE'])."</a><br>\n";
      $disfNews[$b] .= " <font class=\"newsbox_tease\">".stripslashes($disTease)."</font><br><br>\n";
      $b++;
   }

   $cNews = count($disfNews);
   for ( $n=0; $n < $cNews; $n++ ) {
      $newsbox_flex .= "<div class=\"newsbox\">\n";
      $newsbox_flex .= $disfNews[$n];
      $newsbox_flex .= "</div>\n";
   }
}


if ( $nShow != "" ) {
   $selShow = mysql_query("SELECT * FROM blog_content WHERE BLOG_SUBJECT = '$news_cat' AND PRIKEY = '$nShow'");
   $getShow = mysql_fetch_array($selShow);

   $disArt = "\n\n\n<!--- BEGIN NEWS ARTICLE --->\n\n\n";
   $disArt .= "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"article_table\">\n";
   $disArt .= " <tr>\n";
   $disArt .= "  <td class=\"article_title\" align=\"left\"><font class=\"article_date\">".stripslashes($getShow['BLOG_DATE'])."</font> - ".stripslashes($getShow['BLOG_TITLE'])."</td>\n";
   $disArt .= " </tr>\n";
   $disArt .= " <tr>\n";
   $disArt .= "  <td class=\"article_content\">\n";
   $disArt .= "   ".stripslashes($getShow['BLOG_DATA'])."\n";
   $disArt .= "  </td>\n";
   $disArt .= " </tr>\n";
   $disArt .= "</table>\n";

   $disArticle = $disArt;
   $module_active = "yes";
   
	# Include captcha js
	$disArticle = "<script type=\"text/javascript\" src=\"sohoadmin/client_files/captcha/captcha.js\"></script>\n".$disArticle;   
}


if ( $bShow != "" ) {
	
	$disArt = ''; // Art stands for 'Article'
	
	if($comment_error || $comment_result_text){
	   if($comment_error){
	      $disArt .=  "<div class=\"comment_status_error\">\n";
	      $disArt .= '<p>'.$comment_error.'</p>';
	      $disArt .= "</div>\n";
	   }else{
	      $disArt .= "<div class=\"comment_status_success\">\n";
	      $disArt .= '<p>'.$comment_result_text.'</p>';
	      $disArt .= "</div>\n";
	   }
	}else if($time_error){
	   $disArt .= "<div class=\"comment_status_error\">\n";
	   $disArt .= $time_error;
	   $disArt .= "</div>\n";
	}
	
   $selShow = mysql_query("SELECT * FROM blog_content WHERE BLOG_SUBJECT = '$cat' AND PRIKEY = '$bShow'");
   $getShow = mysql_fetch_array($selShow);

   $disArt .= "\n\n\n<!--- BEGIN NEWS ARTICLE --->\n\n\n";
   
	$disArt .= "<script language=\"javascript\">\n";
	
	$disArt .= "function chk_n_send(form_key){\n";
	$disArt .= "   var err = '';\n";
	$disArt .= "   if($('comment_name_'+form_key).value.length < 1){\n";
	$disArt .= "      err += 'Name,'\n";
	$disArt .= "      $('comment_name_display_'+form_key).style.color='red';\n";
	$disArt .= "   }\n";
	$disArt .= "   if($('emailaddr_'+form_key).value.length < 1){\n";
	$disArt .= "      err += 'Email,'\n";
	$disArt .= "      $('emailaddr_display_'+form_key).style.color='red';\n";
	$disArt .= "   }\n";
	$disArt .= "   if($('blog_comments_'+form_key).value.length < 1){\n";
	$disArt .= "      err += 'Comments'\n";
	$disArt .= "      $('blog_comments_display_'+form_key).style.color='red';\n";
	$disArt .= "   }\n";
	$disArt .= "   if(err == ''){\n";
	$disArt .= "      var form_name = 'add_blog_comment_form_'+form_key;\n";
	$disArt .= "      eval('document.'+form_name+'.submit();')\n";
	$disArt .= "   }else{\n";
	$disArt .= "      alert('".lang("Please complete the following fields").": '+err+'.')\n";
	$disArt .= "   }\n";
	$disArt .= "}\n";
	
	$disArt .= "function chk_n_send_captcha(form_key){\n";
	
	$disArt .= "   if(zulucrypt(form_key)){\n";
	$disArt .= "   var err = '';\n";
	$disArt .= "   if($('comment_name_'+form_key).value.length < 1){\n";
	$disArt .= "      err += 'Name,'\n";
	$disArt .= "      $('comment_name_display_'+form_key).style.color='red';\n";
	$disArt .= "   }\n";
	$disArt .= "   if($('emailaddr_'+form_key).value.length < 1){\n";
	$disArt .= "      err += 'Email,'\n";
	$disArt .= "      $('emailaddr_display_'+form_key).style.color='red';\n";
	$disArt .= "   }\n";
	$disArt .= "   if($('blog_comments_'+form_key).value.length < 1){\n";
	$disArt .= "      err += 'Comments'\n";
	$disArt .= "      $('blog_comments_display_'+form_key).style.color='red';\n";
	$disArt .= "   }\n";
	$disArt .= "   if(err == ''){\n";
	$disArt .= "      var form_name = 'add_blog_comment_form_'+form_key;\n";
	$disArt .= "      eval('document.'+form_name+'.submit();')\n";
	$disArt .= "   }else{\n";
	$disArt .= "      alert('".lang("Please complete the following fields").": '+err+'.')\n";
	$disArt .= "   }\n";
	$disArt .= "   }\n";
	$disArt .= "}\n";
	
	$disArt .= "</script>\n";
   
   
   
   $disArt .= "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"article_table\">\n";
   $disArt .= " <tr>\n";
   $disArt .= "  <td class=\"article_title\" align=\"left\"><font class=\"article_date\">".$getShow['BLOG_DATE']."</font> - ".stripslashes($getShow['BLOG_TITLE'])."</td>\n";
   $disArt .= " </tr>\n";
   $disArt .= " <tr>\n";
   $disArt .= "  <td class=\"article_content\">\n";
   $disArt .= "   ".stripslashes($getShow['BLOG_DATA'])."\n";
   $disArt .= "  </td>\n";
   $disArt .= " </tr>\n";
   $disArt .= "</table>\n";
   
   if($is_allowed == "yes" ){
      
      # Make sure page name is set
      $pr = $_REQUEST['pr'];
      if(strlen($pr) < 1){
         $pr = "index";
      }
      
      # Add blog comment
      $disArt .= "   <p class=\"sohotext blog_comment\"><a href=\"javascript: return false;\" onClick=\"toggleid('add_blog_comment_".$getShow['PRIKEY']."');\">".lang("Add new comment")."</a></p>\n";
      $disArt .= "   <div class=\"add_blog_comment\" id=\"add_blog_comment_".$getShow['PRIKEY']."\" style=\"display: none;\">\n";
      $disArt .= "      <form name=\"add_blog_comment_form_".$getShow['PRIKEY']."\" method=\"POST\" action=\"".$pr.".php\">\n";
      $disArt .= "         <input type=\"hidden\" name=\"process\" value=\"blog_comment\" />\n";
      $disArt .= "         <input type=\"hidden\" name=\"bShow\" value=\"".$_REQUEST['bShow']."\" />\n";
      $disArt .= "         <input type=\"hidden\" name=\"cat\" value=\"".$_REQUEST['cat']."\" />\n";
      $disArt .= "         <input type=\"hidden\" name=\"blog_key\" value=\"".$getShow['PRIKEY']."\" />\n";
      
      $disArt .= "          <!---Begin form display-->\n";
      $disArt .= "          <div class=\"form_body_container\" style=\"\">\n";
      
      $disArt .= "          <div class=\"field-container\">\n";
      $disArt .= "          <p class=\"sohotext instructions\">All fields are required</p>\n";
      $disArt .= "          <div class=\"ie_cleardiv\">\n";
      $disArt .= "          </div>\n";
      $disArt .= "          </div>\n";
      
      # Name
      $disArt .= "          <div class=\"field-container\">\n";
      $disArt .= "          <p class=\"sohotext myform-field_title-left\" id=\"comment_name_display_".$getShow['PRIKEY']."\"><label for=\"comment_name_".$getShow['PRIKEY']."\">Name:</label><span class=\"asterisk\">*</span>\n";
      $disArt .= "          </p>\n";
      $disArt .= "          <p class=\"myform-input_container\"><input type=\"text\" name=\"comment_name_".$getShow['PRIKEY']."\" id=\"comment_name_".$getShow['PRIKEY']."\" style=\"width: 220px; color: #999999;\" onclick=\"if(this.value=='Alphanumeric characters only')this.value='';this.style.color='';\" value=\"Alphanumeric characters only\" /></p>\n";
      $disArt .= "          <div class=\"ie_cleardiv\">\n";
      $disArt .= "          </div>\n";
      $disArt .= "          </div>\n";
      
      # Email
      $disArt .= "          <div class=\"field-container\">\n";
      $disArt .= "          <p class=\"sohotext myform-field_title-left\" id=\"emailaddr_display_".$getShow['PRIKEY']."\"><label for=\"emailaddr_".$getShow['PRIKEY']."\">Email Address:</label><span class=\"asterisk\">*</span>\n";
      $disArt .= "          </p>\n";
      $disArt .= "          <p class=\"myform-input_container\"><input type=\"text\" name=\"emailaddr_".$getShow['PRIKEY']."\" id=\"emailaddr_".$getShow['PRIKEY']."\" style=\"width: 220px;\"/></p>\n";
      $disArt .= "          <div class=\"ie_cleardiv\">\n";
      $disArt .= "          </div>\n";
      $disArt .= "          </div>\n";
      
      # Comments
      $disArt .= "          <div class=\"field-container\">\n";
      $disArt .= "          <p class=\"sohotext myform-field_title-left\" id=\"blog_comments_display_".$getShow['PRIKEY']."\"><label for=\"blog_comments_".$getShow['PRIKEY']."\">Comments</label><span class=\"asterisk\">*</span>\n";
      $disArt .= "          </p>\n";
      $disArt .= "          <p class=\"myform-formfield_container\"><textarea name=\"blog_comments_".$getShow['PRIKEY']."\" id=\"blog_comments_".$getShow['PRIKEY']."\" style=\"width: 250px;height: 85px;\"></textarea></p>\n";
      $disArt .= "          <div class=\"ie_cleardiv\">\n";
      $disArt .= "          </div>\n";
      $disArt .= "          </div>\n";
      
      $disArt .= "          <div class=\"userform-submit_btn-container\">\n";
      
      if($blog_comment_settings->get("captcha") == "yes"){
      
         $disArt .= "            ".$captcha."\n";
         //$disArt .= "            <input id=\"userform-submit_btn\" onclick=\"return zulucrypt();\" />\n";
         
      	$pattern = "abdehklmprsuwx123456789";
      	$key = "<table cellpadding=\"0\" cellspacing=\"0\">\n";	
      	$key .= "<tr>\n";
      	$synckey = '';
      	$key .= "<td height=\"34\" cellpadding=\"0\"><table align=left height=\"34\" cellpadding=\"0\" width=\"216\" cellspacing=\"0\"><tr>\n";
      	for($i=0;$i<6;$i++){  	
      			$key .= "<td  width=\"36px\" height=\"34px\" align=\"left\"><image src=\"sohoadmin/client_files/captcha/";
      			$keyz = $pattern{rand(0,22)};
      			$key .= $keyz.".gif\" width=\"36px\" height=\"34px\" style=\"border:1px solid black;\"></td>\n";
      			$synckey .= $keyz;
      	}
      	$synckey = strtoupper($synckey);
      //	$key .= "</form>\n";
      	
      	$key .= "</tr></table></td></tr>\n";
      	$key .= "<tr><td align=\"left\" valign=top style=\"padding:0px;\">\n";
      	$key .= "<input name=\"capval_".$getShow['PRIKEY']."\" id=\"capval_".$getShow['PRIKEY']."\" type=\"hidden\" value=\"".md5($synckey)."\">\n";
      	$key .= "<label for=\"cap_".$getShow['PRIKEY']."\">Please&nbsp;enter&nbsp;the&nbsp;phrase&nbsp;as&nbsp;it&nbsp;is&nbsp;shown&nbsp;in&nbsp;the&nbsp;box&nbsp;above.&nbsp;&nbsp;&nbsp;</label>\n";
      	//$key .= "<td align=\"left\">\n";
      	$key .= "<input name=\"cap_".$getShow['PRIKEY']."\" id=\"cap_".$getShow['PRIKEY']."\" type=\"text\" size=\"6\" maxlength=\"6\" style=\"border:1px solid black; text-align:left; font-size:18px;\"></td></tr>\n";
      	$key .= "</table>";
      	$_SESSION['form_verification_blog'][$getShow['PRIKEY']] = md5($synckey);
//      	$key .= '['.$_SESSION['form_verification_blog'][$getShow['PRIKEY']].']';
      	$disArt .= $key;
      	//echo $ckey;
      //	$disArt .= "<br/>";
      //	echo $synckey;
      //	$disArt .= "<br/>";
      	//echo md5($synckey);   
         $disArt .= "          <input class=\"submit_btn\" type=\"button\" onclick=\"chk_n_send_captcha('".$getShow['PRIKEY']."')\" value=\"".lang("Submit")." >>\">\n";
      }else{
         $disArt .= "          <input class=\"submit_btn\" type=\"button\" onclick=\"chk_n_send('".$getShow['PRIKEY']."')\" value=\"".lang("Submit")." >>\">\n";
      }
      
      $disArt .= "          </div>\n";
       
      $disArt .= "          </div>\n";
      
      $disArt .= "      </form>\n";
      $disArt .= "   </div>\n";
      
      
      # Blog comments
      $comment_qry = "SELECT PRIKEY, NAME, COMMENTS, COMMENT_DATE FROM blog_comments WHERE BLOG_KEY = '".$getShow['PRIKEY']."' AND STATUS = 'approved' ORDER BY COMMENT_DATE ASC";
      $comment_result = mysql_query($comment_qry);
      
      if(mysql_num_rows($comment_result) > 0){
         $disArt .= "<p class=\"sohotext show_hide_comments\"><a href=\"javascript: return false;\" onClick=\"toggleid('comment_container_".$getShow['PRIKEY']."')\">Show/Hide Comments (".mysql_num_rows($comment_result).")</a></p>\n";
         $disArt .= "<div class=\"comment_container\" id=\"comment_container_".$getShow['PRIKEY']."\" style=\"display: none;\">\n";
         
         while($COMMENTS = mysql_fetch_array($comment_result)){
            $disArt .= "<div class=\"sohotext a_comment\">\n";
            $disArt .= "   <h3>".stripslashes($COMMENTS['NAME'])."</h3>\n";
            $disArt .= "   <span>".date("M-j g:ia",strtotime($COMMENTS['COMMENT_DATE']))."</span>\n";
            $disArt .= "   <p class=\"sohotext\">".nl2br(stripslashes(html_entity_decode($COMMENTS['COMMENTS'], ENT_QUOTES)))."</p>\n";
            $disArt .= "</div>\n";
         }
         
         $disArt .= "</div>\n";
      }else{
         $disArt .= "<p class=\"no_comments\">".lang("No Comments")."</p>\n";
      }
   }   

   $disArticle = $disArt;
   $module_active = "yes";
   
	# Include captcha js
	$disArticle = "<script type=\"text/javascript\" src=\"sohoadmin/client_files/captcha/captcha.js\"></script>\n".$disArticle;   
}
?>