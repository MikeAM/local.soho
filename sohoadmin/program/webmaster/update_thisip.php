<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.5
##
## Author: 			Mike Johnston [mike.johnston@soholaunch.com]
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugzilla.soholaunch.com
## Release Notes:	sohoadmin/build.dat.php
###############################################################################

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
require_once("../includes/product_gui.php");

$old_ip = $_POST['thisip_val'];
$new_ip = $_POST['newip_val'];

switch($new_ip){
	case $old_ip:	$thisip_error[] = lang("Old domain name and new domain are the same."); break;
	case '':	$thisip_error[] = lang("New domain name can not be nothing."); break;
}

if(!is_array($thisip_error)){
	
	unlink($_SESSION['doc_root'].'/sohoadmin/filebin/test.txt');
	
	$serverstatus = include_r('http://update.securexfer.net/media/proxy.php?proxy=update.securexfer.net/media/serverup.php');
	if($serverstatus == 'Server Up'){
		$this_ip = $_SESSION['this_ip'];		
		$body = '';
		
		$filenameisp = $_SESSION['doc_root'].'/sohoadmin/config/isp.conf.php';
		if($fileisp = fopen($filenameisp, "r")) {
	
			if(!is_dir('filebin')) {
				mkdir('filebin', 0755);
			}
			$testfile = $_SESSION['doc_root'].'/sohoadmin/filebin/test.txt';
			if(is_file($testfile)) { unlink($testfile); }
			$testtime = base64_encode(microtime());
			if(!$twfile = fopen($testfile, "w+")) {
				$thisip_error[] = lang("Can't Write to").' '.$_SESSION['doc_root'].'/sohoadmin/filebin , '.lang("autoresolve failed").'.';
			} else {
				fwrite($twfile, $testtime);
				fclose($twfile);
				
				$this_ipurl = $new_ip."/sohoadmin/filebin/test.txt";
				$proxyz = 'http://update.securexfer.net/media/proxy.php?proxy=';
				$ddomain = $proxyz.$this_ipurl;
						
				$readtime = include_r($ddomain);
				$readtime = str_replace(' ', '', $readtime);
	
				if($testtime == $readtime){
					//New Domain name available!
					$resolved = 'hellzyes';
					#### Update license
					$sec_host = "securexfer.net";
					$target_api = "/product_reports/api-rename_domain-ultra.php";
					$update_data = "dom=".$_SESSION['this_ip']."&new_dom=".$new_ip."&dkey=".$_SESSION['key'];
					if($_SESSION['final_ip']!=''){
						$update_data .= "&final_ip=".$_SESSION['final_ip'];
					}
					$buf = "";
					// Connect to server and update key
					// -------------------------------------------------
					if ($fp = fsockopen($sec_host,80)) {
						// Update license for this domain
						// -------------------------------------------------------
						fputs($fp, "POST ".$target_api." HTTP/1.1\n");
						fputs($fp, "Host: $sec_host\n");
						fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
						fputs($fp, "Content-length: " . strlen($update_data) . "\n");
						fputs($fp, "User-Agent: MSIE\n");
						fputs($fp, "Connection: close\n\n");
						fputs($fp, $update_data);
						
						while (!feof($fp)) {
							$response .= fgets($fp,128);
						}						
						$upd_resp = split("~STAT~", $response);
						//echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$upd_resp['1']."<br/>final_ip=".$final_ip." ".$_SESSION['final_ip']."<br/>";
						fclose($fp);
					} // end if server connect successful
										
					
					//echo "<font color=\"#FFFFFF\">*</font><font color=\"#3873B9\">".$final_ip." is now resolving!<br>  changing domain to ".$final_ip." in the config file, and updating all domain paths for all site content.</font>";
					$blogquery = mysql_query('select * from blog_content');
					while($blogcont = mysql_fetch_array($blogquery)) {
					  $dapri = $blogcont['PRIKEY'];
					  $oldcontt = $blogcont['BLOG_DATA'];		   
					  $newcott = str_replace($old_ip, $new_ip, $oldcontt);
					  $newqry = "update blog_content set BLOG_DATA = '".$newcott."' where PRIKEY = '".$dapri."'";
					  mysql_query($newqry);
					}
					
					$filenameisp = $_SESSION['doc_root'].'/sohoadmin/config/isp.conf.php';
					if($fileisp = fopen($filenameisp, "r")) {
						$body = fread($fileisp,filesize($filenameisp));
						fclose($fileisp);
					}
	
					$newisp = str_replace('this_ip='.$old_ip, 'this_ip='.$new_ip, $body);
	
	        			$newisp2 = str_replace('final_ip='.$_SESSION['final_ip'], '', $newisp);
	        			$final_ip = '';
					$fileispw = fopen($filenameisp, "w");
					fwrite($fileispw, $newisp2);
					fclose($fileispw);
					$mycwd = getcwd();
					
					//fix page content files
					foreach (glob($_SESSION['doc_root'].'/sohoadmin/tmp_content/*.con') as $filename) {
					  $pagecon = $filename;
					  $filesizecon = filesize($pagecon);
					  $pagereg = str_replace('.con', '.regen', $pagecon);
					  $filesizereg = filesize($pagereg);  
					  
					  if($filesizecon > 0) {
					    $filec = fopen($pagecon, "r");      
					    $concontent = fread($filec, $filesizecon);      
					    $newconcontent = str_replace($old_ip, $new_ip, $concontent);
					    fclose($filec);
					    $filecw = fopen($pagecon, "w");
					    fwrite($filecw, $newconcontent);
					    fclose($filecw);
					  }
					            
					  if($filesizereg > 0) {
					    $filer = fopen($pagereg, "r");
					    $regcontent = fread($filer, $filesizereg);
					    $newregcontent = eregi_replace($old_ip, $new_ip, $regcontent);
					    fclose($filer);
					    $filerw = fopen($pagereg, "w");
					    fwrite($filerw, $newregcontent);
					    fclose($filerw);
					  }       
					}
					
					$thisip_success[] = lang("update successful").'!';
					$_SESSION['this_ip'] = $new_ip;  
					$this_ip = $new_ip;
					$_SESSION['docroot_url'] = $new_ip;
					$docroot_url = $new_ip;
					$_SESSION['dot_com'] = $new_ip;
					$dot_com = $new_ip;
					unset($_SESSION['final_ip']);
					unset($final_ip);
					$done = 'log em in';      			
					
				} else {
					//Cant update to new domain name it is not available.
					$thisip_error[] = lang("Can't change domain name to").' http://'.$new_ip.' '.lang("because").' http://'.$new_ip.' '.lang("is not yet resolving to this site").'.';
				}			
				unlink($testfile);      
	
			}
		}
	}
}
	
?>