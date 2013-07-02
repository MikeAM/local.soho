<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
ini_set("max_execution_time", "120000");
ini_set("default_socket_timeout", "120000");
ini_set("max_post_size", "900M");
ini_set("max_input_time", "120000");
ini_set("memory_limit", "512M");
set_time_limit(0);
session_start();
include_once("../includes/product_gui.php");


if($_POST['action'] == 'upload_transfer') {
	include_once("sohoadmin/program/includes/remote_actions/class-file_download.php");	
	$current_dir = getcwd();
	chdir($_SESSION['doc_root']);
	unset($_SESSION['error_rep']);
	$this_zip = $_FILES['FILE1']['name'];
	$this_zip = eregi_replace(" ", "_", $this_zip);
	$php_suexec = strtoupper(php_sapi_name());
	$Gateway = strtoupper(php_sapi_name());
	unlink('bakupinfo.php');
	if(move_uploaded_file($_FILES['FILE1']['tmp_name'], 'bakupinfo.php')) {
		$filename = "bakupinfo.php";
		$file = fopen($filename, "r");
		$bodyz = fread($file,filesize($filename));
		$lines = split("\n", $bodyz);
		$numLines = count($lines);
		
		for ($x=2;$x<=$numLines;$x++) {
			if (!eregi("#", $lines[$x])) {
				$variable = strtok($lines[$x], "=");
				$value = strtok("\n");
				$value = rtrim($value);			
				if($value != ''){
					${$variable} = $value;
				}
			}
		}	
		fclose($file);
		if($backup_file==''){
			$error_rep = lang("Invalid transfer file. You may need to re-create the transfer file on the site you are transfering.");
			$_SESSION['error_rep']['transfer'] = $error_rep;
			echo "<script language=\"javascript\"> \n";
			echo "location.href=\"movesite-main.php?error=yes\"; \n";
			echo "</script> \n";	
			exit;
		}
		$filenameisp = "sohoadmin/config/isp.conf.php";
		$fileisp = fopen($filenameisp, "r");
		$body = fread($fileisp,filesize($filenameisp)); 
		fclose($fileisp);
		$Killit = 'final_ip='.$_SESSION['final_ip'];
		$body = eregi_replace($Killit, '', $body);
		
//		$ch = curl_init($backup_link);
//		curl_setopt($ch, CURLOPT_HEADER, 0);
//		curl_setopt($ch, CURLOPT_TIMEOUT, 1200);
//		curl_setopt($ch, CURLOPT_PROXY, $old_ip_addr);
//		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
//		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//		$tgzlink = curl_exec($ch);
//		curl_close($ch);
//		
		$tgzlink = $backup_file;
		unlink($_SESSION['doc_root']."/site_transfer.zip");	   
		
		$ch = curl_init($tgzlink);
		$fp = fopen($_SESSION['doc_root']."/site_transfer.zip", "w");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 36200);
		curl_setopt($ch, CURLOPT_PROXY, $old_ip_addr);
		curl_setopt($ch, CURLOPT_FILE, $fp); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
		
//		// stream_set_blocking($a,0);
//		echo filesize($_SESSION['doc_root']."/site_transfer.zip");
//		echo "<br/>";
//		echo $backup_filesize;
//		exit;
		if(!file_exists($_SESSION['doc_root']."/site_transfer.zip")) {
			$error_rep = lang("Failed to retrieve remote transfer file. You may need to re-create the transfer file on the site you are transfering.");
			$_SESSION['error_rep']['transfer'] = $error_rep;
			echo "<script language=\"javascript\"> \n";
			echo "location.href=\"movesite-main.php?error=yes\"; \n";
			echo "</script> \n";
			exit;
		} else {
			/////////
			rename('sohoadmin/config/isp.conf.php', 'sohoadmin/config/isp.conf.php.bak');
//			if ( eregi("WIN", $OS) ) {
//				$extract = "";				
//				$extract = shell_exec($_SESSION['doc_root']."\sohoadmin\program\includes\untar\gunzip.exe -d site_transfer.zip"); sleep("5");
//				$extract = shell_exec($_SESSION['doc_root'].'\sohoadmin\program\includes\untar\tar.exe -xvf site_transfer.tar'); sleep("5");	      	      
//			} else {
//				$extract = "";
//				$extract = shell_exec("tar -xzvf site_transfer.zip");
//			}
			$extract = soho_unzip('site_transfer.zip');
			sleep('1');
			rename('sohoadmin/config/isp.conf.php.bak', 'sohoadmin/config/isp.conf.php');
			if ( is_array($extract)) {	
				$extractlist = "</font><br><font size=\"3\" face=\"Times New Roman, Times, serif\" color=#FDB417><strong>".lang("Site Transfered and Extracted Successfuly")."!</strong></font><br><font size=\"3\" face=\"Times New Roman, Times, serif\" color=\"#0006ef\"><strong>".lang("Files Extracted")." ...</font></strong>";
				$extracttable = "<div id=ouput style=\"height:166; width:426; z-index:5; overflow:auto;\"".$extractlist."<pre>".$extract."</pre></div>";					
				
				if($php_suexec == "CGI"){
					shell_exec("chmod -R 0755 *");
				}
				
			} else {
				$error_rep = lang("Unable to extract the transfer file.  Please ensure that shell_exec is enabled on this server.");
				$_SESSION['error_rep']['transfer'] = $error_rep;
				echo "<script language=\"javascript\"> \n";
				echo "location.href=\"movesite-main.php?error=yes\"; \n";
				echo "</script> \n";
				exit;
			}
			
			
			$tgzlink2 = include_r($backup_link."&action=delete");
			
			echo $extracttable;
			unlink("site_transfer.zip");
			unlink("transfer_backup_file.zip");
			if($extracttable != '') {									
				$fileispw = fopen($filenameisp, "w+");
				$newisp = eregi_replace('this_ip='.$_SESSION['this_ip'], 'this_ip='.$old_this_ip."\n", $body);
				$_SESSION['this_ip'] = $old_this_ip;
				$_SESSION['final_ip'] = '';
				
				fwrite($fileispw, $newisp);
				fclose($fileispw); 
			}
			
			$bsql = "databasebk.sql";
			if(file_exists($bsql) ) {
//				if (eregi("WIN", $OS) ) {
//					exec($_SESSION['doc_root']."\sohoadmin\program\webmaster\mysql.exe -h ".$_SESSION['db_server']." -u ".$db_un." -p".$db_pw." ".$db_name." < ".$_SESSION['doc_root'].'\\'.$bsql);
//				} else {
//					exec("mysql -h ".$_SESSION['db_server']." -u ".$_SESSION['db_un']." -p".$_SESSION['db_pw']." ".$_SESSION['db_name']." < ".$bsql);
//				}
				mysqlImport($bsql);
				unlink($bsql);
			}	
			session_destroy();
			echo "<script language=\"javascript\"> \n";
			echo "parent.location.href = \"../../../sohoadmin/index.php\" \n";
			echo "</script> \n";
			exit;			
		}
	} else {
		$error_rep = lang("Could write to")." ".$_SESSION['doc_root'].".  ".lang("Make sure that")." ".$_SESSION['doc_root']." ".lang("is writable").".";
		$_SESSION['error_rep']['transfer'] = $error_rep;
		echo "<script language=\"javascript\"> \n";
		echo "location.href=\"movesite-main.php?error=yes\"; \n";
		echo "</script> \n";
		exit;
	}
	chdir($current_dir);
} else {	
	if (function_exists('curl_init')) {		
		$tabledisplay = "<html>\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n<body bgcolor=\"blue\">\n</head>\n";
		$tabledisplay .= "<script type=\"text/javascript\" src=\"../includes/display_elements/window/prototype.js\"></script>\n";
		$tabledisplay .= "<script type=\"text/javascript\" src=\"../includes/display_elements/window/window.js\"></script>\n";
		$tabledisplay .= "<script type=\"text/javascript\" src=\"../includes/display_elements/window/effects.js\"></script>\n";
		$tabledisplay .= "<link href=\"../includes/display_elements/window/default.css\" rel=\"stylesheet\" type=\"text/css\"></link>\n";
		$tabledisplay .= "<link href=\"../includes/display_elements/window/alert_lite.css\" rel=\"stylesheet\" type=\"text/css\"></link>\n";
		$tabledisplay .= "<script language=\"javascript\">\n";
		$tabledisplay .= "function openInfoDialog(dmsg) {\n";
		//$tabledisplay .= "dmsg";
		
		$tabledisplay .= " Dialog.info(\"<br>\"+dmsg+\"...\", {windowParameters: {className: \"alert_lite\",width:350, height:100}, showProgress: true});\n";
		//$tabledisplay .= " Dialog.info(\"<br>Retrieving & Restoring Website ...\", {windowParameters: {className: \"alert_lite\",width:250, height:100}, showProgress: true});\n";
		$tabledisplay .= "}\n";
		$tabledisplay .= "</script>\n";
		$tabledisplay .= "<script language=\"javascript\">\n";
		$tabledisplay .= "   document.getElementById('modal_dialog_scroll').innerHTML += '".$title."<br/>'\n";
		$tabledisplay .= "   var cur_pos = document.getElementById('modal_dialog_scroll').scrollTop;\n";
		$tabledisplay .= "   var cur_pos = Number(cur_pos);\n";
		$tabledisplay .= "   var posi = (cur_pos+200);\n";
		$tabledisplay .= "   document.getElementById('modal_dialog_scroll').scrollTop= posi;\n";
		$tabledisplay .= "</script>\n";
		$tabledisplay .= "</html>\n";	
		echo $tabledisplay;	
	
		$transferform = "<script language=\"javascript\"> \n";
		$transferform .= "function showUpload(){ \n";
		$transferform .= "   document.movesite_transfer.uptransfer.style.display=\"block\" \n";
		$transferform .= "} \n";
		$transferform .= "</script> \n";
		
		$transferform .= "<form enctype=\"multipart/form-data\" method=\"POST\" action=\"movesite-transfer.php\" name=\"movesite_transfer\" id=\"movesite_transfer\">\n";
		$transferform .= "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"300000\">\n";
		$transferform .= "<input type=\"hidden\" name=\"action\" value=\"upload_transfer\">\n";
		$transferform .= "<input type=\"hidden\" name=\"showTab\" value=\"tab2\">\n";
		$transferform .= "<table width=\"685\"  border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"feature_sub\" style=\"border-bottom: 1px solid #000000;\">\n";
		$transferform .= "	<tr>\n";
		$transferform .= "		<td>\n";
		$transferform .= "		".lang("To move a remote website to this location, upload the")." \"site_transfer.phps\" ".lang("file from the remote site").".\n";
		$transferform .= "		</td>\n";
		$transferform .= "		</tr>\n";
		$transferform .= "	<tr>\n";
		$transferform .= "		<td>\n";
		$transferform .= "			<input type=\"file\" name=\"FILE1\" class=\"tfield\" style=\"width: 350px;\" accept=\"application/x-httpd-php-source\" value=\"bakupinfo.phps\" onChange=\"showUpload()\"><br/><br/>\n";
		$transferform .= "			<input type=\"submit\" id=\"uptransfer\" style=\"display: none;\" value=\"".lang(" Upload ")."\" onClick=\"openInfoDialog('Retrieving & Restoring Website'); document.getElementById('uptransfer').disabled=true\"><br/> \n";
		$transferform .= "		</td>\n";
		$transferform .= "	</tr>\n";
		$transferform .= "</table>\n";
		$transferform .= "</form>\n";
				
	} else {
		$transferform = " \n";
		$transferform .= "<table width=\"685\"  border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"feature_sub\" style=\"border-bottom: 1px solid #000000;\">\n";
		$transferform .= "	<tr>\n";
		$transferform .= "		<td>".lang("It appears that this server does not have the libcurl package installed for php.  The libcurl library is required inorder to move a site to this domain, however you can still move this site to a different server.")." \n";
		$transferform .= "		</td>\n";
		$transferform .= "	</tr>\n";
		$transferform .= "</table>\n";
	}
	echo $transferform;
}


?>