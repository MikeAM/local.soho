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
$bakfile = 'transfer_backup_file.zip';
$OS = strtoupper(PHP_OS);
$backup_info_file = "bakupinfo.php";
$current_dir = getcwd();

if($_GET['dl'] == 'yes') {
	chdir($_SESSION['doc_root']);
	header("Content-disposition: attachment; filename=site_transfer.phps");
	header("Content-type: application/octet-stream");
	readfile("$backup_info_file");	
	chdir($current_dir);
//	echo "<script language=\"javascript\"> \n";
//	echo "location.href = \"movesite-main.php?success=yes\"; \n";
//	echo "</script> \n";
} else {

	chdir($_SESSION['doc_root']);
	unset($_SESSION['error_rep']);
	///testwrite
	$testfile = "test.txt";
	$file = fopen($testfile, "w");
	if(!fwrite($file, "test")){
		$_SESSION['error_rep'] = '';
		$error_rep = "<strong><font color=\"red\">".lang("Unable to install")."! </strong></font> ".lang("The folder").", <strong><font color=\"blue\">".$_SESSION['doc_root']."</strong></font>, ".lang("must be writable inorder to install").".<br><strong><font color=\"red\"><br>Solution:</strong></font> ";
		$error_rep .= lang("Change the permissions on the")." <strong><font color=\"blue\">".$_SESSION['doc_root']."</strong></font> ".lang("folder so that php has write access").".  ".lang("You may need to contact your host in order to do this").".";
		$_SESSION['error_rep']['backup'] = $error_rep;
		fclose($file);
		echo "<script language=\"javascript\"> \n";
		echo "location.href=\"movesite-main.php?error=yes\"; \n";
		echo "</script> \n";
		exit;
	} else {
		fclose($file);
		$deleted = unlink("test.txt");
	}

///end testwrite
	$dbbackfile = "databasebk.sql";
	$windoc = $_SESSION['doc_root']."\sohoadmin\program\includes\untar\\";

	if( eregi("WIN", $OS) ) {
		$doc_root = eregi_replace('/', "\\", $_SESSION['doc_root']);
	}
	$dbtable = dbtables();

	# Add desired target tables to dump command
	$baktbls = "";
	foreach($dbtable as $key=>$tblname) {
		$baktbls .= " ".$tblname;
	}

//	if(eregi("WIN", $OS) ) {
//		$dumpcom = $doc_root."\sohoadmin\program\webmaster\mysqldump.exe --add-drop-table --all --complete-insert --force -h ".$_SESSION['db_server']." -u ".$_SESSION['db_un']." -p".$_SESSION['db_pw']." ".$_SESSION['db_name'].$baktbls." > ".$_SESSION['doc_root']."\\".$dbbackfile;
//	} else {
//		$dumpcom = "mysqldump --add-drop-table --all --complete-insert --force -h ".$_SESSION['db_server']." -u ".$_SESSION['db_un']." -p".$_SESSION['db_pw']." ".$_SESSION['db_name'].$baktbls." > ".$dbbackfile;
//	}
	
	unlink($dbbackfile);
	unlink($backup_info_file);
	//exec($dumpcom);
	mysqlBackup($dbbackfile);
	if(file_exists($dbbackfile)) {
//		$tarcom = "tar -czvf ".$bakfile." *";
//		if ( eregi("WIN", $OS) ) {
//			//$tarcom = $_SESSION['doc_root']."\sohoadmin\program\includes\untar\tar.exe cvf - | ".$_SESSION['doc_root']."\sohoadmin\program\includes\untar\gzip.exe > * ".$bakfile;
//			$tarcom = 'sohoadmin\program\includes\untar\tar.exe cvf - ';
//			foreach(glob('*') as $afilename){
//				$tarcom .= $afilename." ";	
//			}
//			$tarcom .= "| sohoadmin\program\includes\untar\gzip.exe > ".$bakfile;
//		}
		unlink($bakfile);
//		exec($tarcom);
		soho_create_zip($bakfile, '*');
	} else {
		$error_rep = lang("Could not export database.");
		$_SESSION['error_rep']['backup'] = $error_rep;
		echo "<script language=\"javascript\"> \n";
		echo "location.href=\"movesite-main.php?error=yes\"; \n";
		echo "</script> \n";
		exit;
	}

	if(file_exists($bakfile)) {
		chmod($bakfile, 0755);
		unlink($dbbackfile);
		$stripedfile = eregi_replace('\.zip', '', $bakfile);
		unlink($stripedfile.'.php');
		$backupfilesize = filesize($bakfile);
		//if(rename($bakfile, $stripedfile.'.php')) {
		$accessstring = md5(microtime());
		$baklink = 'http://'.$_SESSION['this_ip'].'/'.$bakfile;
		$baklink2 = 'http://'.$_SESSION['this_ip'].'/'.$backup_info_file;
		
		if ( $_SERVER['SERVER_ADDR'] != "" ) {
			$old_ip_addr = $_SERVER['SERVER_ADDR'];
		} else {
			$old_ip_addr = gethostbyname(php_uname(n));
		}
		
		if ( $_SERVER['SERVER_PORT'] != "" ) {
			$old_ip_addr = $old_ip_addr.':'.$_SERVER['SERVER_PORT'];
		} else {
			$old_ip_addr = $old_ip_addr.':80';
		}
		
		$infocontent  = '<?php'."\n error_reporting(E_PARSE); \n";
//		$infocontent .= 'if($_GET[\'access_string\'] == \''.$accessstring.'\') { #'."\n";
//		$infocontent .= '	if($_GET[\'action\'] == \'delete\') { #'."\n";
//		$infocontent .= '	unlink(\''.$stripedfile.".tgz'); # \n echo 'deleted'; # \n";
//		$infocontent .= '	unlink(\''.$stripedfile.".php'); # \n echo 'deleted'; # \n";
//		$infocontent .= '	unlink(\'bakupinfo.php'."'); # \n echo 'deleted'; # \n";
//		$infocontent .= '	} else { #'."\n";
//		$infocontent .= '		rename(\''.$stripedfile.'.php\', \''.$bakfile.'\'); #'."\n echo '".$baklink."'; # \n";
//		$infocontent .= '	} #'."\n";
//		$infocontent .= '} #'."\n";
		$infocontent .= '	exit;'."\n";
		$infocontent .= '?> '."\n\n";
		$infocontent .= 'old_this_ip='.$_SESSION['this_ip']."\n";
		$infocontent .= 'old_final_ip='.$_SESSION['final_ip']."\n";
		$infocontent .= 'old_doc_root='.$_SESSION['doc_root']."\n";
		$infocontent .= 'old_ip_addr='.$old_ip_addr."\n";
		$infocontent .= 'backup_link='.$baklink2.'?access_string='.$accessstring."\n";
		$infocontent .= 'backup_file=http://'.$_SESSION['this_ip'].'/'.$bakfile."\n";
		$infocontent .= 'backup_filesize='.$backupfilesize."\n";
		
		
		unlink($backup_info_file);
		$infofile = fopen($_SESSION['doc_root'].'/'.$backup_info_file, "w");
		fwrite($infofile, $infocontent);
		fclose($infofile);
		chmod($backup_info_file, 0755);
		
		if(file_exists($stripedfile.".zip")){
			echo "<script language=\"javascript\"> \n";
			echo "location.href = \"movesite-main.php?dl=yes\"; \n";
			echo "</script> \n";
		}
		//}
	} else {
		$error_rep = lang("Could create backup TGZ file.  Make sure shell_exec is enabled.");
		$_SESSION['error_rep']['backup'] = $error_rep;
		echo "<script language=\"javascript\"> \n";
		echo "location.href=\"movesite-main.php?error=yes\"; \n";
		echo "</script> \n";
		exit;
	}
	chdir($current_dir);
}
?>