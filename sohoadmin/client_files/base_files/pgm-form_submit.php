<?php

error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


################################################################################
## Soholaunch(R) Site Management Tool
## Version 	4.6
## Revised	4.9.3 r33
##
## Author: 			Mike Johnston [mike.johnston@soholaunch.com]
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugzilla.soholaunch.com
## Release Notes:	sohoadmin/build.dat.php
################################################################################

##############################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2003 Soholaunch.com, Inc. and Mike Johnston
## Copyright 2003-2007 Soholaunch.com, Inc.
## All Rights Reserved.
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
###############################################################################

session_start();
error_reporting(0);
track_vars;

/**
* Need to know database name; UN;PW; etc in order to operate
* the real-time exekution. This is configured in the isp.conf
* file.
*/
	
	include_once("sohoadmin/client_files/pgm-site_config.php");
	include_once("sohoadmin/program/includes/shared_functions.php");

	include_once("sohoadmin/program/includes/smt_functions.php");
	eval(hook("pgm-form_submit.php:top-before-page-processing"));
	
	$globalprefObj = new userdata('global');
	$formpref = new userdata('forms');
	$SLASH = DIRECTORY_SEPARATOR;
	if($_POST['PAGEREQUEST'] == '' || !isset($_POST['PAGEREQUEST'])){
		header("Location: http://".$_SESSION['this_ip']."/index.php");
		exit;
	}
	if($_POST['CUST_FILENAME'] != ''){
		$_POST['CUST_FILENAME'] = $_SESSION['doc_root'].$SLASH.'media'.$SLASH.$_POST['CUST_FILENAME'];
		$CUST_FILENAME = $_POST['CUST_FILENAME'];
		$_REQUEST['CUST_FILENAME'] = $_POST['CUST_FILENAME'];
	}
	
	if($_POST['EMAILTO'] != ''){
		$_POST['EMAILTO'] = str_replace('>', '@', $_POST['EMAILTO']);
		$_REQUEST['EMAILTO'] = $_POST['EMAILTO'];
		$EMAILTO = $_POST['EMAILTO'];
	}
	
	if($formpref->get('include-captcha')=='on' && isset($_POST) && ($_POST['capval'] == '' || !isset($_POST['capval']))){
		$getpagecont = include_r("http://".$_SESSION['this_ip']."/".str_replace(' ', '_', $_POST['PAGEREQUEST']).".php");
		if(preg_match('/name="capval"/m', $getpagecont) && ($_POST['capval'] == '' || !isset($_POST['capval']))){
			echo "<script type=\"text/javascript\"> \n";
			echo "document.location='http://".$_SESSION['this_ip']."/index.php?pr=".str_replace(' ', '_', $_POST['PAGEREQUEST'])."'; \n";
			echo "</script> \n";
			exit;	
		}
		unset($getpagecont);
	}
	
	if(array_key_exists('capval', $_POST) || array_key_exists('cap', $_POST)){
		$form_verificationk = '';
		$form_verificationk = $_SESSION['form_verification'];
		unset($_SESSION['form_verification']);
		if($form_verificationk != md5(strtoupper($_POST['cap'])) || $form_verificationk == '') {
			header("Location: http://".$_SESSION['this_ip']."/index.php?pr=".$_POST['PAGEREQUEST']);
			echo "<script type=\"text/javascript\"> \n";
			Echo "document.location='http://".$_SESSION['this_ip']."/index.php?pr=".$_POST['PAGEREQUEST']."'; \n";
			Echo "</script> \n";
			exit;
		}
		
		if($_POST['capval'] != '' && $_POST['cap'] != '' && $_POST['capval'] == md5(strtoupper($_POST['cap']))) {
			$_SESSION['form_verification'] = $form_verificationk;			
		} else {
			header("Location: http://".$_SESSION['this_ip']."/index.php?pr=".$_POST['PAGEREQUEST']);
			echo "<script type=\"text/javascript\"> \n";
			Echo "document.location='http://".$_SESSION['this_ip']."/index.php?pr=".$_POST['PAGEREQUEST']."'; \n";
			Echo "</script> \n";
			exit;
		}
		unset($_POST['capval']);
		unset($_POST['cap']);
	}

	$refer = str_replace('http://','',$_SERVER['HTTP_REFERER']);
	$refer = str_replace('www.','',$refer);
	$check_ip = eregi_replace("pgm-form_submit.php","",$this_ip);
	$check_ip1 = eregi_replace("www.","",$check_ip);

	if(count($_POST) < 1){ 
		header("Location: index.php");
		exit;
	}//endif
	
	if(count($_FILES) >= 1){
		$filesuploaded = '';
		foreach($_FILES['fileupload']['tmp_name'] as $filnum=>$fildat) {
			$filesuploaded .= $_FILES['fileupload']['name'][$filnum].', ';
		}
		$filesuploaded = preg_replace('/, $/', '', $filesuploaded);
		$_POST['files_uploaded'] = $filesuploaded;
//		echo testArray($_POST);
//		echo testArray($_FILES);

	}//endif
	
	$dot_com = $this_ip;
	
	$REL12FIX = $RESPONSEFILE;			// Instant Fix after release
	$ERROR_READ_FILE = $CUST_FILENAME;		// Leave this value untainted by processing


	if(!function_exists("is_utf8")){	
		function is_utf8($str) {
		    $c=0; $b=0;
		    $bits=0;
		    $len=strlen($str);
		    for($i=0; $i<$len; $i++){
		        $c=ord($str[$i]);
		        if($c > 128){
		            if(($c >= 254)) return false;
		            elseif($c >= 252) $bits=6;
		            elseif($c >= 248) $bits=5;
		            elseif($c >= 240) $bits=4;
		            elseif($c >= 224) $bits=3;
		            elseif($c >= 192) $bits=2;
		            else return false;
		            if(($i+$bits) > $len) return false;
		            while($bits > 1){
		                $i++;
		                $b=ord($str[$i]);
		                if($b < 128 || $b > 191) return false;
		                $bits--;
		            }
		        }
		    }
		    return true;
		}
	}

	if (!function_exists(mb_list_encodings)) {
		function mb_list_encodings(){
			$list_encoding = array("pass", "auto", "wchar", "byte2be", "byte2le", "byte4be", "byte4le", "BASE64", "UUENCODE", "HTML-ENTITIES", "Quoted-Printable", "7bit", "8bit", "UCS-4", "UCS-4BE", "UCS-4LE", "UCS-2", "UCS-2BE", "UCS-2LE", "UTF-32", "UTF-32BE", "UTF-32LE", "UTF-16", "UTF-16BE", "UTF-16LE", "UTF-8", "UTF-7", "UTF7-IMAP", "ASCII", "EUC-JP", "SJIS", "eucJP-win", "SJIS-win", "JIS", "ISO-2022-JP", "Windows-1252", "ISO-8859-1", "ISO-8859-2", "ISO-8859-3", "ISO-8859-4", "ISO-8859-5", "ISO-8859-6", "ISO-8859-7", "ISO-8859-8", "ISO-8859-9", "ISO-8859-10", "ISO-8859-13", "ISO-8859-14", "ISO-8859-15", "EUC-CN", "CP936", "HZ", "EUC-TW", "BIG-5", "EUC-KR", "UHC", "ISO-2022-KR", "Windows-1251", "CP866", "KOI8-R");
			return $list_encoding;
		}
	}
	
	
	if(function_exists("mb_detect_encoding")){	
		$encodings = mb_list_encodings();
		if(!function_exists("fixEncoding")){
			function fixEncoding($in_str){
				$encodings = mb_list_encodings();
				$cur_encoding = mb_detect_encoding($in_str.'a', $encodings);
				if(strtoupper($cur_encoding) == "UTF-8"){
					return $in_str;
				} else {
					return utf8_encode($in_str);
				} // fixEncoding 
			}
		}
	} else {
		$encodings = mb_list_encodings();
		if(!function_exists("fixEncoding")){
			function fixEncoding($in_str){
				if(is_utf8($in_str)){
					return $in_str;
				} else {
					return utf8_encode($in_str);
				} // fixEncoding 
			}
		}
		
	}

/**
* Insert function to kill all non alpha/numeric characters from data
* for database storage
*/

	function sterilize_char ($sterile_var) {
	
		$sterile_var = stripslashes($sterile_var);
		$sterile_var = eregi_replace(";", ",", $sterile_var);
		$sterile_var = eregi_replace(" ", "_", $sterile_var);
	
		$st_l = strlen($sterile_var);
		$st_a = 0;
		$tmp = "";
	
		while($st_a != $st_l) {
			$temp = substr($sterile_var, $st_a, 1);
			if (eregi("[0-9a-z_]", $temp)) { $tmp .= $temp; }
			$st_a++;
		}//endwhile
	
		$sterile_var = $tmp;
		return $sterile_var;
	
	}//sterilize_char

/**
* Insert validate email function
*/

	function email_is_valid ($email) {
	   if (eregi("^[0-9a-z]([+-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,4}$", $email, $check)) {
	      return TRUE;
	   }//endif
	
	   return FALSE;
	
	}//email_is_valid

/**
* Setup known variable array; unknowns are form generated
*/

	$SOHO_VAR = ";EMAILTO;PAGEREQUEST;DATABASE;PAGEGO;RESPONSEFROM;SUBJECTLINE;RESPONSEFILE;";
	$SOHO_VAR .= "REQUIRED FIELDS;SELFCLOSE;CUST FILENAME;CUSTOMERNUMBER;UNIQUETOKEN;";

/**
* Expected Variable Listing and what they tell this process
* ----------------------------------------------------------------------------------------
* var emailto			Who (Site Owner) to email results of this form to (Email Address)
* var pageRequest		Page from which this form was submitted
* var database			Name of data table for this data to create (un parsed w/space, etc.)
* var pagego				Page to redirect site visitor after form is processed
* var RESPONSEFROM		Email address to send auto-email FROM
* var subjectline		Subject Line of auto-email
* var RESPONSEFILE		Text file containing content of auto-email (DEFAULT2020202024452345.TXT)
* var required_fields	array (; delimited) containing required field names from form
* var selfclose			if (yes) send javascript close window command and exit;
* var cust_filename      filename of form file
* var customernumber     system assigned customer id
* var UNIQUETOKEN       uber-secure form validation method for Mantis #412 for future utilization
* 
* THE FOLLOWING VARS MUST BE THE "NAME" OF THE INPUT FIELDS IN THE FORM FOR THE PROPER
* PROCESSING TO WORK:
* 
* var emailaddr			Customers Email Address to send confirmation email
*/

	$PAGEREQUEST = eregi_replace(' ', '_', $PAGEREQUEST);
	$filename = "$cgi_bin/".$PAGEREQUEST.".con";
	
	chdir($cgi_bin);
	
	$handle = fopen( $filename, "r");
	
	if ( $handle ){
	   while (!feof($handle)){
	      $buffer = fgets($handle, 4096);
	      $daForm .= $buffer;
	   }//endwhile
	   fclose($handle);
	}else{
		$filename = htmlspecialchars($filename);		
	   die("fopen failed for $filename. You may be pointing your form to a page that does not exist.");
	}//endif
	
	$tOutput = explode("name=EMAILTO value=\"", $daForm);
	
	$firstForm = $tOutput[1];
	$secondForm = $tOutput[2];
	
	if($tOutput['1'] == ''){
	
		$tOutput = explode("Email To: ", $daForm);
		$firstForm = $tOutput[1];
		$secondForm = $tOutput[2];
	
		if(!eregi($CUST_FILENAME,$secondForm)){
		   $tOutput = explode("</font>", $firstForm);
			$tmpFinal = $tOutput[0];
		}else{
		   $tOutput = explode("</font>",$secondForm);
			$tmpFinal = $tOutput[0];
		}//endif
	
	}else{
	
		if(!eregi($CUST_FILENAME,$secondForm)){
		   $tOutput = explode("\"\>", $firstForm);
			$tmpFinal = "1".$tOutput[0];
		}else{
		   $tOutput = explode("\">", $secondForm);
			$tmpFinal = "2".$tOutput[0];
		}//endif
	
	}//endif

/************************************************************************
*
* STEP ONE: FORMAT ALL PASSED VARIABLES FOR DATA MANIPULATION
*
************************************************************************/

	/**
	 * Clear vars
	 */
	
		$EMAILTO = "";
		$EMAILADDR = "";
		$spamflagBool = false;
	
		foreach ( $_POST as $name=>$value ) {
	
	/**
	 * Convert array to list
	 */
	
		if ( is_array($value) ) {
			$value = implode(", ", $value);
		}//endif
	
		$value = stripslashes($value);
		$value = eregi_replace("\n", " ", $value);
		$value = eregi_replace("\r", "", $value);
	
		$name = stripslashes($name);
		$name = strtoupper($name);
		$name = sterilize_char($name);
	
		$value = htmlspecialchars($value);
		
		if ( $formpref->get('block-links') == 'on' && eregi('http.*http', $value) ) {
			$spamflagBool = true;
		}//endif
	
		${$name} = $value;
	
		}//endforeach
	
	/**
	 * Spammer rejection message goes here
	 */
	
		if ( $spamflagBool == true ) {
		     echo '<div style="width: 500px;background: #efefef;font: 12px Trebuchet MS, verdana, arial, sans-serif;padding: 15px;position: absolute; left:30%; top: 40%; border: 1px dotted red;">'."\n";
		     echo $formpref->get('spam-trap-message');
		     echo "&nbsp;&nbsp;&nbsp;\n<a href=\"#\" onClick=\"history.go(-1)\">".lang('Return to Previous Page')."</a>\n";
		     echo '</div>'."\n";
		     exit;
		}//endif
	
		$sendto_email_orig = $EMAILTO;
	
	/**
	 * Contrict EMAILTO and EMAILADDR to 40 chars
	 */
	
		$sendto_email = str_replace(" ", "", $EMAILTO);
		$sendto_email = split(",", $sendto_email);
		$good_emailto = "";
	
	/**
	 * Limit to one email address
	 */
	
		for ( $e = 0; $e < 10; $e++ ) {
		   if ( strlen($sendto_email[$e]) < 50 ) {
		      $good_emailto .= $sendto_email[$e];
		   }//endif
		}//endfor
	
		$EMAILTO = $good_emailto;
		$EMAILADDR = eregi_replace(",.*", "", $EMAILADDR);

/************************************************************************
*
* STEP TWO: CHECK FOR REQUIRED FIELDS
*
************************************************************************/

	$BUGZILLA26 = 0;
	if ($EMAILADDR != "" && !email_is_valid($EMAILADDR)) {
		$BUGZILLA26 = 1;
		$EMAILADDR = "";
		if (!eregi("emailaddr;", $REQUIRED_FIELDS)) { $REQUIRED_FIELDS .= ";emailaddr"; }
	}//endif
	
	if ($EMAIL_ADDRESS != "" && !email_is_valid($EMAIL_ADDRESS)) {	// Newsletter Sign-Up Form
		$BUGZILLA26 = 1;
		$EMAIL_ADDRESS = "";
		if (!eregi("EMAIL_ADDRESS;", $REQUIRED_FIELDS)) { $REQUIRED_FIELDS .= ";EMAIL_ADDRESS"; }
	}//endif
	
	if ($REQUIRED_FIELDS != "") {
	
		$REQUIRED_FIELDS = eregi_replace(" ", "_", $REQUIRED_FIELDS);
		$REQUIRED_FIELDS = str_replace("fileupload[];", "", $REQUIRED_FIELDS);
	
		$r_fields = split(";", $REQUIRED_FIELDS);
		$r_count = count($r_fields) - 1;
		$err_field = "";
	
		$i=0;
		$err=0;
	
		while($i <= $r_count) {
			$r_fields[$i] = strtoupper($r_fields[$i]);
	
			if ( $r_fields[$i] != "" && ${$r_fields[$i]} == "" ) {
				$err_field .= strtolower($r_fields[$i]).", ";
				$err = 1;
			}//endif
	
			$i++;
		}//endwhile
	
		if ($err == 1) {
	
			$tmp = strlen($err_field);
			$new = $tmp - 2;
			$err_field = substr($err_field, 0, $new);
			$err_field = eregi_replace(", ", ",", $err_field);
	
			$err_field_data = split(",", $err_field);
			$err_count = count($err_field_data);
	
			/**
			 * 1. Open form file and read into memory
			 */
	
				$filename = $ERROR_READ_FILE;
				$fp = fopen("$filename", "r");
					$FORM_CONTENT = fread($fp,filesize($filename));
				fclose($fp);
	
			/**
			 * 2. Split the data up into managable parts
			 */
	
				$work_html = $FORM_CONTENT;
				$work_html = eregi_replace(">", ">\n", $work_html);
				$work_html = eregi_replace("<", "\n<", $work_html);
				$work_html = eregi_replace("\n<option ([A-Za-z_ =\"]*)>\n([A-Za-z_ =\"]*)\n</option>\n", "<option \\1>\\2</option>", $work_html);
				$work_html = eregi_replace("\n<option", "<option", $work_html);
				$work_html = eregi_replace("\n</select>", "</select>", $work_html);
		
				$html_line = split("\n", $work_html);
				$lc = count($html_line);
		
				$NEW_FORM_DATA = "";
	
				for ($x=0;$x<=$lc;$x++) {	// Start loop thru each html line
		
					$reset = 0;
		
					for ($z=0;$z<=$err_count;$z++) {
		
					$alt_data_format = eregi_replace("_", " ", $err_field_data[$z]);
		
					if (strlen($err_field_data[$z]) > 1) { // Added for V4.6 -- > Stop wierd instance of "blank" required fields
							if (eregi("\"$err_field_data[$z]\"", $html_line[$x]) || eregi("\"$alt_data_format\"", $html_line[$x])) {
		
								if ($reset == 0) {
									$d = eregi_replace("_", " ", $err_field_data[$z]);
									if ($d == "Emailaddr") { $d = lang("Email Address"); }
									if ($d == "emailaddr") { $d = lang("Email Address"); }
		
									$NEW_FORM_DATA .= "<TR><TD ALIGN=RIGHT VALIGN=TOP><FONT FACE=Verdana SIZE=2 COLOR=BLACK><B>".ucwords($d).":</B></TD>\n";
		
									if (eregi("TEXTAREA", $html_line[$x])) {	// Fix Text Area Screw up -- Thanks Kenny H!
										$NEW_FORM_DATA .= "<TD ALIGN=LEFT VALIGN=TOP>$html_line[$x]</TEXTAREA></TD></TR>\n";
									} else {
										$NEW_FORM_DATA .= "<TD ALIGN=LEFT VALIGN=TOP>$html_line[$x]</TD></TR>\n";
									}//endif
		
									$reset = 1;
		
								}//endif
		
							}//endif
		
					}//endif
		
					}//endloop
		
				}//endloop
	
				echo "<HTML><HEAD>\n";
				echo "<TITLE>".lang("Form Input Error")."</TITLE></HEAD>\n";
				echo "<BODY BGCOLOR=#ffffff TEXT=#000000>\n\n";
				echo "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 HEIGHT=100% WIDTH=100%><TR><TD ALIGN=CENTER VALIGN=MIDDLE>\n";
				echo "\n\n<FORM METHOD=POST ACTION=\"pgm-form_submit.php\">\n\n";
	
				reset($HTTP_POST_VARS);
	
				while (list($name, $value) = each($HTTP_POST_VARS)) {
					if(!in_array($name, $err_field_data)){
						$name = strip_tags($name);
						$value = strip_tags($value);
						$value = stripslashes($value);
						echo "     <INPUT TYPE=HIDDEN NAME=\"".htmlspecialchars($name)."\" VALUE=\"".htmlspecialchars($value)."\">\n";
					}//endiff
				}//endwhile
	
				echo "\n<TABLE BORDER=0 CELLPADDING=10 CELLSPACING=0 BGCOLOR=WHITE ALIGN=CENTER WIDTH=450 STYLE='border: 5px inset black;'>\n";
				echo "<TR><TD ALIGN=CENTER VALIGN=MIDDLE COLSPAN=2><FONT COLOR=RED FACE=VERDANA SIZE=2>";
				echo "<B>";
	
				if ($BUGZILLA26 == 1) {
					echo lang("The email address you entered is invalid or")." ";
				}//endif
	
				echo lang("You left a required field or fields blank.")."  ".lang("Please enter the following data before continuing").":</B></FONT></TD></TR>\n";
				echo $NEW_FORM_DATA;
				echo "<TR><TD ALIGN=CENTER VALIGN=MIDDLE COLSPAN=2><INPUT TYPE=SUBMIT VALUE=\"Re-Submit\" STYLE='cursor: hand; font-family: Arial; font-size: 8pt;'></TD></TR>\n";
				echo "</TABLE>\n\n</FORM>\n\n";
				echo "</TD></TR></TABLE>\n\n";
				echo "</BODY></HTML>\n";
	
			exit;
	
		}//end_err_1
	
	}//end_req_field_test

/************************************************************************
*
* STEP THREE: EMAIL PROCESS TO SITE OWNER IF REQUESTED
*
************************************************************************/

	if ($EMAILTO != "" && !eregi("NEWSLETTER_SIGNUP_PROCESS", $EMAILTO)) {
	
			$soho_email = "********* ".lang("Auto Generated Form Email")." **********\n\n";
			$soho_form_display = "";
	
			reset($_POST);
			while (list($name, $value) = each($_POST)) {
	
	         if ( is_array($value) ) {
	            $value = implode(", ", $value);
	         }//endif
	
				$value = stripslashes($value);
				$value = eregi_replace("\n", "", $value);
				$value = eregi_replace("\r", "", $value);
	
				if (!eregi("emailaddr", $name) && !eregi("EMAILTO", $name) && !eregi("RESPONSEFROM", $name)){
					$value = eregi_replace("_", " ", $value);
				}//endif
	
				$name = stripslashes($name);
	
				if ( $globalprefObj->get('utf8') != 'on' ) {
					$name = sterilize_char($name);
				}
	
				$name = eregi_replace("_", " ", $name);
	
				if (!eregi(";$name;", $SOHO_VAR)) {
					if (eregi("emailaddr", $name)) { 
						$name = lang("Email Address"); $value = strtolower($value); $visitor_email = strtolower($value); 
					}//endif
					$soho_email .= "> $name: $value\n";
					$soho_form_display .= "$name:  $value\n";
				}//endif
	
			}//endloop
	
			$tmp = split("/", $CUST_FILENAME);
			$tmp_cnt = count($tmp) - 1;
			$form_name = $tmp[$tmp_cnt];
	
			$soho_sub_page = ucwords($PAGEREQUEST);
	
			$soho_email .= lang("This message is auto-generated by your web site ")."(".$_SESSION['this_ip'].") ".lang("when the ")." ";
			$soho_email .= lang("form is submitted by a site visitor on page")." \"$soho_sub_page\". ".lang("No need to reply").".\n";
	
			if ($DATABASE != "") {
				$DATABASE = sterilize_char($DATABASE);
				$tmp = split("/", $DATABASE);
				$tmp_cnt = count($tmp) - 1;
				$tname = $tmp[$tmp_cnt];
				$tname = strtoupper($tname);
	
				$soho_email .= "\nFYI:\n".lang("This data has been saved to the")." \"$tname\" ".lang("database table").".";
			}//endif
	
			if (!eregi("default2020202024452345", $RESPONSEFILE)) {
			   $resp_file_name = eregi_replace($_SESSION['doc_root'], "", $RESPONSEFILE);
				$soho_email .= "\n".lang("Your site visitor received the custom response file")." \"".$resp_file_name."\".";
			}//endif
	
			$soho_email .= "\n\n\n\n";
	
			if ($SUBJECTLINE == "") { $SUBJECTLINE = "".lang("Website Form Submission").""; }

			/**
			 * Constrict EMAILTO and EMAILADDR to 40 chars
			 */
	
				$sendto_email = str_replace(" ", "", $sendto_email_orig);
				$sendto_email = split(",", $sendto_email);
				$good_emailto = "";
				$good_emailto_admin = "";

			/**
			 * Limit to one email address for now (10 later?)
			 */

				for ( $e = 0; $e < 10; $e++ ) {
					if ( strlen($sendto_email[$e]) < 50 && strlen($sendto_email[$e]) > 5 ) {
						$good_emailto .= ','.$sendto_email[$e];
						$good_emailto_admin[$e] = $sendto_email[$e];
					}
				}
				$good_emailto = ltrim($good_emailto, ',');				
			
				$EMAILTO_ADMIN = $good_emailto_admin;
				$EMAILTO = $good_emailto;

			/**
			 * Limit site visitors email address to one
			 */
	
				$EMAILADDR = eregi_replace(",.*", "", $EMAILADDR);
		
				if ( count($_FILES) > 0 ) {
		
				include_once($_SESSION['doc_root'].'/sohoadmin/program/includes/class-send_file.php');
				
				$test = new attach_mailer($name = "", $from = "$RESPONSEFROM", $to = "$EMAILTO", $cc = "", $bcc = "", $subject = "".lang("Website Form Submission")."");
				$o_dir = getcwd();
				chdir($_SESSION['doc_root'].'/sohoadmin/filebin/');
				if(is_dir('tmp_upload')){ rmdirr('tmp_upload'); }
				mkdir('tmp_upload');
				chdir('tmp_upload');
				$purefiles = array();
				foreach($_FILES['fileupload']['tmp_name'] as $filnum=>$fildat){
					if(!preg_match('/\.htaccess/i', $_FILES['fileupload']['name'][$filnum])&& !preg_match('/\.\./', $_FILES['fileupload']['name'][$filnum])){
						$_FILES['fileupload']['name'][$filnum] = str_replace(' ', '_', $_FILES['fileupload']['name'][$filnum]);
						if(move_uploaded_file($_FILES['fileupload']['tmp_name'][$filnum], $_SESSION['doc_root'].'/sohoadmin/filebin/tmp_upload/'.$_FILES['fileupload']['name'][$filnum])) {						
							if(file_exists($_SESSION['doc_root'].'/sohoadmin/filebin/tmp_upload/'.$_FILES['fileupload']['name'][$filnum])) {
								if(preg_match('/\.(gif|jpg|jpeg|png|bmp)$/i', $_FILES['fileupload']['name'][$filnum])) {
									//$test->add_html_image($_SESSION['doc_root'].'/sohoadmin/filebin/tmp_upload/'.$_FILES['fileupload']['name'][$filnum]);
									$test->add_attach_file($_SESSION['doc_root'].'/sohoadmin/filebin/tmp_upload/'.$_FILES['fileupload']['name'][$filnum]);
									$purefiles[] = $_FILES['fileupload']['name'][$filnum];
								} elseif(preg_match('/\.zip$/i', $_FILES['fileupload']['name'][$filnum])) {
									$test->add_attach_file($_SESSION['doc_root'].'/sohoadmin/filebin/tmp_upload/'.$_FILES['fileupload']['name'][$filnum]);
									$purefiles[] = $_FILES['fileupload']['name'][$filnum];
								} else {
									$SLASH = DIRECTORY_SEPARATOR;
									$zipped_file = str_replace(' ', '_', preg_replace('/\.[^\.]*$/i', '.zip', $_FILES['fileupload']['name'][$filnum]));
									soho_create_zip($zipped_file, $_FILES['fileupload']['name'][$filnum]);
									$purefiles[] = $_FILES['fileupload']['name'][$filnum];
									if(file_exists($_SESSION['doc_root'].$SLASH."sohoadmin".$SLASH."filebin".$SLASH."tmp_upload".$SLASH.$zipped_file)){
										$test->add_attach_file($_SESSION['doc_root'].$SLASH."sohoadmin".$SLASH."filebin".$SLASH."tmp_upload".$SLASH.$zipped_file);
//										unlink($_SESSION['doc_root'].$SLASH."sohoadmin".$SLASH."filebin".$SLASH.$zipped_file);
									} else {
										$test->add_attach_file($_SESSION['doc_root'].'/sohoadmin/tmp_upload/filebin/tmp_upload/'.$_FILES['fileupload']['name'][$filnum]);
									}
//								unlink($_SESSION['doc_root'].$SLASH."sohoadmin".$SLASH."filebin".$SLASH.$_FILES['fileupload']['name'][$filnum]);
								}
							}
						}
					}
				}

			$test->html_body = "<html><pre>$soho_email</pre></html>";
			$test->text_body = strip_tags($test->html_body, "<a>");
			if($test->process_mail() == true ) {
				//echo "mail sent";
			}else{
				if (strtoupper(substr(PHP_OS,0,3)=='WIN')) {
				  $eol="\r\n";
				} elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) {
				  $eol="\r";
				} else {
				  $eol="\n";
				}
				$zipped_file = 'upload_files.zip';
				unlink($zipped_file);
				soho_create_zip($zipped_file, $purefiles);

				# File for Attachment
				$letter = basename($zipped_file);
				$f_name="".$letter;    // use relative path OR ELSE big headaches. $letter is my file for attaching.
				# To Email Address

				$from_name = $RESPONSEFROM; //'noreply';
				$from_address = $RESPONSEFROM;
				# Message Subject
				//$subject="File attached".date("Y/m/d H:i:s");
				$subject = "".lang("Website Form Submission")."";
				$now = time();
				$handle=fopen($f_name, 'rb');
				$f_contents=fread($handle, filesize($f_name));
				$f_contents=chunk_split(base64_encode($f_contents));    //Encode The Data For Transition using base64_encode();
				$f_type=filetype($f_name);
				fclose($handle);
				# Common Headers
				$headers .= 'From: '.$from_name.' <'.$from_address.'>'.$eol;
				$headers .= 'Reply-To: '.$from_name.' <'.$from_address.'>'.$eol;
				$headers .= 'Return-Path: '.$from_name.' <'.$from_address.'>'.$eol;     // these two to set reply address
				$headers .= "Message-ID: <".$now." TheSystem@".$_SERVER['SERVER_NAME'].">".$eol;
				$headers .= "X-Mailer: PHP v".phpversion().$eol;           // These two to help avoid spam-filters
				# Boundry for marking the split & Multitype Headers
				$mime_boundary=md5(time());
				$headers .= 'MIME-Version: 1.0'.$eol;
				$headers .= "Content-Type: multipart/mixed; boundary=\"".$mime_boundary."\"".$eol;
				$msg = "";
				# Setup for text OR html
				$msg .= "Content-Type: multipart/alternative".$eol;
				# HTML Version
				$msg .= "--".$mime_boundary.$eol;
				$msg .= "Content-Type: text/html; charset=utf-8".$eol;
				$msg .= "Content-Transfer-Encoding: 8bit".$eol;
				//$msg .= "Content-Transfer-Encoding: quoted-printable".$eol;
				$soho_email = "<html>\n<body>\n<pre>\n".$soho_email."</pre>\n</body>\n</html>";
				$msg .= $soho_email.$eol.$eol;
				# Attachment
				$msg .= "--".$mime_boundary.$eol;
				
				$msg .= "Content-Type: application/octet-stream; name=\"".$letter."\"".$eol;   // sometimes i have to send MS Word, use 'msword' instead of 'pdf'
				$msg .= "Content-Transfer-Encoding: base64".$eol;
				$msg .= "Content-Disposition: attachment; filename=\"".$letter."\"".$eol.$eol; // !! This line needs TWO end of lines !! IMPORTANT !!
				$msg .= $f_contents.$eol.$eol;
				# Setup for text OR html
				# Finished
				$msg .= "--".$mime_boundary."--".$eol.$eol;   // finish with two eol's for better security. see Injection.
				
				# SEND THE EMAIL
				ini_set(sendmail_from, $from_address);  // the INI lines are to force the From Address to be used !
				mail("$EMAILTO", $subject, $msg, $headers);
				ini_restore(sendmail_from);
				
			}//end else
			chdir($_SESSION['doc_root'].'/sohoadmin/filebin/');
			rmdirr('tmp_upload');
			chdir($o_dir);
		}else{

			/**
			* Push out email to administrator
			* ------------------------------------------
			* Bug fixed by Phillip on 01/05/2010
			* Email headers 'from' was showing as 'apache'
			* on all incoming emails. Header specification needs
			* to be included in the FOREACH statement.
			* This foreach uses $soho_email in the mail function.
			*/

			$soho_email = fixEncoding($soho_email);
	
			if ( $visitor_email != "" ) { $emailfrom = $visitor_email; } else { $emailfrom = $RESPONSEFROM; }
			foreach($EMAILTO_ADMIN as $var=>$val){
				if(strlen($val)>5){
					//$from = $emailfrom;

					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/plain; charset=UTF-8' . "\r\n";
////					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

					if ( $formpref->get('double-email') != 'yes' ) {
						//$headers .= 'To: '. $val . "\r\n";
					}
					
					if ( $formpref->get('from-header') != 'disabled' ) {
						$headers .= 'From: '.$RESPONSEFROM . "\r\n";
						$headers .= 'Reply-To: '.$RESPONSEFROM.' <'.$RESPONSEFROM.'>'."\r\n";
					}
					mail("$val", $SUBJECTLINE, $soho_email, $headers);
				}
			}
		}
	}

/************************************************************************
*
* STEP FOUR: SETUP AUTO-EMAIL CONFIRMATION TO SITE VISITOR (IF NEEDED)
*
************************************************************************/

	if (($EMAILADDR != "" || $EMAIL_ADDRESS != "") && !eregi("NEWSLETTER_SIGNUP_PROCESS", $EMAILTO)) {
	
		$soho_email = lang("Thank you for your form submission today! This email is to confirm the reception")." ";
		$soho_email .= lang("of your recently submitted data.")."\n\n".lang("We received the following:")." \n\n";
	
		reset($HTTP_POST_VARS);
		while (list($name, $value) = each($HTTP_POST_VARS)) {
	
	      if ( is_array($value) ) {
	         $value = implode(", ", $value);
	      }//endif
	
			$name = stripslashes($name);
			$name = sterilize_char($name);
			$name = eregi_replace("_", " ", $name);
	
			if (!eregi(";$name;", $SOHO_VAR) && $name!='capval' && $name!='cap') {
				$soho_email .= $name.": [".$value."]\n";
			}//endif
	
		}//endloop
	
		$soho_email .= lang("Thank You")."!\n\n ** ".lang("This message is auto-generated by our web site.")." ";
		$soho_email .= lang("Please do not reply to this email.")." **\n";
	
		if (!eregi("default2020202024452345.txt", $RESPONSEFILE)) {
	
			$this_filename = eregi_replace("$doc_root/", "", $REL12FIX);
			$this_filename = ltrim($this_filename);
			$this_filename = rtrim($this_filename);
	
			$file_path = $_SESSION['docroot_path'].'/media/'.basename($RESPONSEFILE);
			$soho_email = file_get_contents($file_path);
		}//endif
	
		reset($_POST);
	
		while (list($name, $value) = each($_POST)) {
	
	      if ( is_array($value) ) {
	         $value = implode(", ", $value);
	      }//endif
	
			$value = stripslashes($value);
			$value = eregi_replace("\n", "", $value);
			$value = eregi_replace("\r", "", $value);
	
			if (!eregi("emailaddr", $name) && !eregi("EMAILTO", $name) && !eregi("RESPONSEFROM", $name) && !eregi("EMAIL_ADDRESS", $name)){
				$value = eregi_replace("_", " ", $value);	// Replace underscores with spaces Bug #0000619
			}//endif
	
			$name = stripslashes($name);

			$name = sterilize_char($name);	#this may cause issues for non-english non-utf8 chars
			if ( $globalprefObj->get('utf8') != 'on' ) {
//				$name = sterilize_char($name);
				$name = eregi_replace("_", " ", $name);
			}//endif
	
			if (eregi("emailaddr", $name)){ 
				$value = strtolower($value); 
			}//endif
	
			$soho_email = eregi_replace("\[$name\]", $value, $soho_email);		// This part should be case sensitive!
	
		}//end_while
	
		if($EMAILADDR == '' && $EMAIL_ADDRESS != ''){
			$soho_email = eregi_replace("EMAIL_ADDRESS", lang("Email Address"), $soho_email);
		} else {
			$soho_email = eregi_replace("EMAILADDR", lang("Email Address"), $soho_email);
		}//endif
	
		if ($SUBJECTLINE == ""){ 
			$SUBJECTLINE = "Website Form Submission"; 
		}//endif
	
		if ($EMAILTO == ""){ 
			$EMAILTO = "$dot_com <webmaster@$dot_com>"; 
		}//endif
	
		$sendto_email = str_replace(" ", "", $EMAILTO);
		$sendto_email = split(",", $sendto_email);
		$good_emailto = "";
	
		for ( $e = 0; $e < 10; $e++ ) {
			if ( strlen($sendto_email[$e]) < 50 ) {
				$good_emailto .= $sendto_email[$e];
			}//endif
		}//endfor
	
		$EMAILTO = $good_emailto;
	
		if($EMAILADDR == ''){
			$EMAILADDR = $EMAIL_ADDRESS;
		}//endif
	
		$EMAILADDR = eregi_replace(",.*", "", $EMAILADDR);
				if (email_is_valid($EMAILADDR)){
					$soho_email = fixEncoding($soho_email);

					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/plain; charset=UTF-8' . "\r\n";
//					$headers .= 'To: '. $EMAILADDR . "\r\n";
					$headers .= 'From: '.$RESPONSEFROM . "\r\n";
					
					mail("$EMAILADDR", "$SUBJECTLINE", "$soho_email", "$headers") || Die (lang("Not able to send client email"));
				}//endif

		}//endif

/************************************************************************
*
* STEP FIVE: SETUP DATABASE PROCESSES IF REQUESTED
*
************************************************************************/

	if (eregi("NEWSLETTER_SIGNUP_PROCESS", $EMAILTO)) {
		$thisYear = date("Y");
		if ($Year_Born < 1900) { $Year_Born = $thisYear; }	// No one this freakin' old is using the Internet I assure you
		$verifyAge = $thisYear - $Year_Born;
		if ($verifyAge < 14) { $DATABASE = "";	}
	} // End if this is Newsletter Form
	
	if (strlen($DATABASE) > 3) {
	
		$TABLE_NAME = sterilize_char($DATABASE);
		$TABLE_NAME = strtoupper($TABLE_NAME);
		$TABLE_NAME = "UDT_".$TABLE_NAME;
	
		$tbl_exist = 0;
	
		$result = mysql_list_tables("$db_name");
	
		$i = 0;
	
		while ($i < mysql_num_rows ($result)) {
			$tb_names[$i] = mysql_tablename($result, $i);
			if ($tb_names[$i] == $TABLE_NAME) { $tbl_exist = 1; }
			$i++;
		}
	
		if ($tbl_exist == 0) {
	
			$SQL_CREATE = "CREATE TABLE $TABLE_NAME (PRIKEY INT NOT NULL AUTO_INCREMENT PRIMARY KEY, DATE_POSTED CHAR(255), ";
	
			reset($_POST);
			while (list($name, $value) = each($_POST)) {
	
				$name = stripslashes($name);
				$name = sterilize_char($name);	#this may cause issues for non-english non-utf8 chars
				if ( $globalprefObj->get('utf8') != 'on' ) {
					//$name = sterilize_char($name);
					$name = strtoupper($name);
				}//endif
	
				$tmp_chk = eregi_replace(" ", "_", $SOHO_VAR);	// Replace spaces with underscores for form names
	
				if (!eregi(";$name;", $tmp_chk)) {
	
					$SQL_CREATE .= "$name BLOB, ";			// Create all fields as CHAR(255) by default.
																// You can change this in the "Database Table Manager"
				}//endif
	
			}//endloop
	
			$SQL_CREATE .= "AUTO_IMAGE CHAR(100), AUTO_SECURITY_AUTH CHAR(255))";				// Make sure we add the auto_image field to UDT
	
			mysql_query("$SQL_CREATE");
	
			sleep(1);
	
		}//endif
	
			reset($_POST);
			$i=0;
			while (list($name, $value) = each($_POST)) {
			if(function_exists('mb_convert_encoding')){
				$value = mb_convert_encoding($value, 'HTML-ENTITIES', 'UTF-8');
				$name = mb_convert_encoding($name, 'HTML-ENTITIES', 'UTF-8');
			}//endif
	
				$name = stripslashes($name);
				$name = sterilize_char($name);	#this may cause issues for non-english non-utf8 chars
				if ( $globalprefObj->get('utf8') != 'on' ) {
					//$name = sterilize_char($name);
					$name = strtoupper($name);
				}//endif
	
				$tmp_chk = eregi_replace(" ", "_", $SOHO_VAR);
	
				if (!eregi(";$name;", $tmp_chk)) {
					$i++;
					$PASSED_FORM_NAMES[$i] = $name;
				}//endif
	
			}//endloop
	
			$name = "DATE_POSTED";
		$value = date("Y-m-d g:ia [T]")." ".$_SERVER['REMOTE_ADDR'];
		${$name} = $value;
		$PASSED_FORM_NAMES[$i] = "DATE_POSTED";
		$i++;
		$NUM_FORM_NAMES = $i;
	
			$result = mysql_query("SELECT * FROM $TABLE_NAME");
			$numberFields = mysql_num_fields($result);
			$numberFields--;
	
			$CURRENT_FIELD_NAMES = "";
	
			for ($i=0;$i<=$numberFields;$i++) {
				$tmp = mysql_field_name($result, $i);
				$CURRENT_FIELD_NAMES .= strtoupper($tmp).";";
			}
	
			$NUM_FIELD_NAMES 	= $i;		
			$NEW_FIELDS 		= "";
	
			$i = 0;
			while ($i <= $NUM_FORM_NAMES) {
	
				$found = 0;
	
				if (eregi("$PASSED_FORM_NAMES[$i];", $CURRENT_FIELD_NAMES)) {
					$found = 1;
				} else {
					$NEW_FIELDS .= "$PASSED_FORM_NAMES[$i];";
				}//endif
	
				$i++;
	
			}//endloop
	
			if ($NEW_FIELDS != "") {		// We need to add more fields to the table
	
				$tmp = split(";", $NEW_FIELDS);
				$tmp_cnt = count($tmp) - 2;
	
				for ($x=0;$x<=$tmp_cnt;$x++) {
					mysql_query("ALTER TABLE $TABLE_NAME ADD $tmp[$x] BLOB");  // Again, Stick to our standard CHAR(255) default
					$CURRENT_FIELD_NAMES .= $tmp[$x] . ";";				    // Add this to our current db table counter.  Not done with it just yet
				}//endfor
	
			}//endif
	
			$tmp = split(";", $CURRENT_FIELD_NAMES);
			$tmp_cnt = count($tmp) - 2;
	
			$SQL_INSERT = "INSERT INTO $TABLE_NAME VALUES(";
	
			for ($x=0;$x<=$tmp_cnt;$x++) {
	
				if ($x != $tmp_cnt) {
					$SQL_INSERT .= "'NULL', ";
				} else {
					$SQL_INSERT .= "'NULL'";
				}//endif
	
			}//endfor
	
			$SQL_INSERT .= ")";
	
			mysql_query("$SQL_INSERT");
			$NEW_ID = mysql_insert_id();
	
			$SQL_UPDATE = "UPDATE $TABLE_NAME SET ";
	
			for ($x=1;$x<=$tmp_cnt;$x++) {
				$tValue = ${$tmp[$x]};
				$tValue = addslashes($tValue);
	
				if ($x != $tmp_cnt) {
					$SQL_UPDATE .= "$tmp[$x] = '$tValue', ";
				} else {
					$SQL_UPDATE .= "$tmp[$x] = '$tValue'";
				}//endif
			}//endfor
	
	
			mysql_query("$SQL_UPDATE WHERE PRIKEY = '$NEW_ID'");
	
	
	} // End Database Process

/************************************************************************
*
* STEP SIX: TAKE REDIRECT OR CLOSE ACTION BASED ON SETTINGS FOR FORM
*
************************************************************************/
$form_sub_ins = array();
if($NEW_ID != ''){
	$form_sub_ins['db_table_key'] = $NEW_ID;
}
if($TABLE_NAME != ''){
	$form_sub_ins['db_table'] = $TABLE_NAME;
}
$form_sub_ins['form_name'] = basename($CUST_FILENAME);
$form_sub_ins['referrer'] = $refer;
//$form_sub_ins['form_details'] = $soho_email;
$form_sub_ins['form_details'] = $soho_form_display;
$form_sub_ins['ip_address'] = $_SERVER['REMOTE_ADDR'];
$form_sub_ins['date'] = date('Y-m-d');
$form_sub_ins['time'] = time();
$dbqry = new mysql_insert("form_submissions", $form_sub_ins);
$dbqry->insert();



	if (eregi("yes", $SELFCLOSE)) {
		echo "<HTML><HEAD><TITLE>".lang("Form Submitted").". ".lang("Thank You")."!</TITLE></HEAD>\n";
		echo "<BODY BGCOLOR=DARKBLUE TEXT=WHITE>\n\n";
		echo "<SCRIPT language=Javascript>\n";
		echo "     alert('".lang("Thank You")."! ".lang("Your information has been submitted").".');\n";
		echo "     self.close();\n";
		echo "</SCRIPT>\n\n";
		echo "</BODY></HTML>\n";
		exit;
	}//endif

	$pgqry = mysql_query("select page_name, url_name from site_pages where link='".$PAGEGO."'");
	$cntit = mysql_num_rows($pgqry);
	if($cntit > 0) {
		while($woo = mysql_fetch_array($pgqry)) {
			$PAGEGO = $woo['page_name'];
			$PAGEGO = eregi_replace(' ', '_', $PAGEGO);
		}
	}//endif

	$PAGEGO = eregi_replace(" ", "_", $PAGEGO);

	
	header("Location: ".pagename($PAGEGO));
	exit;

?>