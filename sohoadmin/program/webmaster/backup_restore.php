<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.7
##
## Author: 			Mike Morrison
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugz.soholaunch.com
###############################################################################

##############################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2005 Soholaunch.com, Inc.  All Rights Reserved.
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

/*------------------------------------------------------------------------------------------------------------------------------------------*
..######..####.########.########.....########.....###.....######..##....##.##.....##.########...............................................
.##....##..##.....##....##...........##.....##...##.##...##....##.##...##..##.....##.##.....##..............................................
.##........##.....##....##...........##.....##..##...##..##.......##..##...##.....##.##.....##..............................................
..######...##.....##....######.......########..##.....##.##.......#####....##.....##.########...............................................
.......##..##.....##....##...........##.....##.#########.##.......##..##...##.....##.##.....................................................
.##....##..##.....##....##...........##.....##.##.....##.##....##.##...##..##.....##.##.....................................................
..######..####....##....########.....########..##.....##..######..##....##..#######..##.....................................................
/*------------------------------------------------------------------------------------------------------------------------------------------*/


error_reporting(E_PARSE);
session_start();

require_once("../includes/product_gui.php");
include("webmaster_dbchk_inc.php"); // Check for Webmaster tables
$globalprefObj = new userdata('global');
# Primary interface include
//include_once("../includes/product_gui.php");
//include($doc_root."/sohoadmin/program/includes/shared_functions.php");
//echo testArray($_SESSION); EXIT;

$backprefObj = new userdata('backup');

if ( $_GET['direct-download'] != "" ) {
	$backprefObj->set('direct-download-link', $_GET['direct-download']);
	$report[] = 'Direct download preference set to: ['.$_GET['direct-download'].']';
}

# Start buffering output
ob_start();

# Make sure backup dir exists
$backup_dir = $_SESSION['docroot_path'].DIRECTORY_SEPARATOR."sohoadmin".DIRECTORY_SEPARATOR."program".DIRECTORY_SEPARATOR."webmaster".DIRECTORY_SEPARATOR."backups".DIRECTORY_SEPARATOR;
if ( !is_dir($backup_dir) ) {
   if ( !mkdir($backup_dir, 0755) ) { echo js_alert("".lang("Could not create dir")."!"); }
}

if ( !file_exists($backup_dir.'/index.html') ) {
   # Write index.html file so 
   $contentsHTML = '<html><h1>You cannot view this page</h1><p>Please log-in to access.</p></html>';
   $fp = fopen($backup_dir.'index.html', 'w');
   fwrite($fp, $contentsHTML);
   fclose($fp);
}


$OS = strtoupper(PHP_OS);
$windoc = "..\..\includes\untar\\";
	if ( eregi("WIN", $OS) ) {
		$doc_root = eregi_replace('/', "\\", $doc_root);
	}
/*------------------------------------------------------------------------------------------------------------------------------------------*/


########################################################################################################
#      ____                __                    _   __                 __
#     / __ ) ____ _ _____ / /__ __  __ ____     / | / /____  _      __ / /
#    / __  |/ __ `// ___// //_// / / // __ \   /  |/ // __ \| | /| / // /
#   / /_/ // /_/ // /__ / ,<  / /_/ // /_/ /  / /|  // /_/ /| |/ |/ //_/
#  /_____/ \__,_/ \___//_/|_| \__,_// .___/  /_/ |_/ \____/ |__/|__/(_)
#                                  /_/
########################################################################################################
if ( $do == "new_backup" ) {

   // Format backup title
   $bak_title = trim($bak_title);
   if ( $bak_title == "" ) { $bak_title = "N/A"; }

   // Declare report var
   $jsrep = "";

   /// 1. Creating folder for this backup...
   ###=================================================================================
   $bakdir = $backup_dir.$bakfile."/";
//   echo $bakfile; exit;
   if ( mkdir($bakdir, 0755) ) {
      $jsrep .= "bakstat('1','done');\n";
      $step = 2;
   } else {
      $jsrep .= "bakstat('1','err');\n";
      //echo "PHP Says: [".$php_errormsg."]<br>"; exit;
   }


   /// 2. Writing backup info to text file...
   ###=================================================================================
   if ( $step == 2 ) {

      // Build file contents
      $infotxt = "BAK_ID~~=~~".$bakfile."\n";
      $infotxt .= "BAK_DATE~~=~~".$dbdate."\n";
      $infotxt .= "BAK_TIME~~=~~".$dbtime."\n";
      $infotxt .= "BAK_TITLE~~=~~".$bak_title."\n";
      $infotxt .= "BAK_NOTES~~=~~".$bak_notes;

      // Create text file and write data
      $textfile = $backup_dir."import.txt";
      $btfile = fopen($textfile, "w");
      if ( fwrite($btfile, $infotxt) ) {
         $jsrep .= "bakstat('2','done');\n";
         $step = 3;
      } else {
         $jsrep .= "bakstat('2','err');\n";
      }
      fclose($btfile);
   }


   /// 3. Archiving site content and files...
   ###=============================================================================
   if ( $step == 3 ) {
      # Store current dir
      $origdir = getcwd();

      # Switch dir to docroot
      chdir($_SESSION['docroot_path']);

      ## Build tar command
      ##=================================================
      //$tarcom = "tar -czvf ".$bakdir."site_files.tgz";
      $tardir = array();
//      $tardir[] = $bakdir."site_files.zip";
//			if ( eregi("WIN", $OS) ) {
//			$bakdir = "sohoadmin\program\webmaster\backups\\".$bakfile."\\";
//			$tarcom = 'sohoadmin\program\includes\untar\tar.exe cvf - ';
//			}
      // Build array of directories to tar
      #[========================================================================]
      $tardir[] = "images";
      $tardir[] = "media";
      $tardir[] = "tCustom";
      $tardir[] = "template";
      $tardir[] = "sohoadmin/tmp_content";
      if(is_dir('sohoadmin/plugins')) {
      	$tardir[] = "sohoadmin/plugins";
      }
      // Add currently in use template dirs to tar list
      //-----------------------------------------------------------
      $pagesdir = "sohoadmin/program/modules/site_templates/pages/";

//      # Site Base Template
//      $filename = "sohoadmin/tmp_content/template.conf";
//      $file = fopen("$filename", "r");
//      $what_template = fread($file,filesize($filename));
//      fclose($file);
      $what_template = $globalprefObj->get('site_base_template');
      
      $tardir[] = $pagesdir.$what_template;

      # Page Template Assignments
//      $filename = "media/page_templates.txt";
      $qry = "SELECT template FROM site_pages where template != '' GROUP BY template";
      $rez = mysql_query($qry);

      while ( $getPage = mysql_fetch_array($rez) ) {

         $tpl_dir = $pagesdir.$getPage['template'];
         if ( strlen($getPage['template']) > 2 && is_dir($tpl_dir) && !in_array($tpl_dir) ) {
            $tardir[] = $tpl_dir;
         }

      } // end while

//      echo testArray($tardir);

$tarcom = soho_create_zip($bakdir."site_files.zip", $tardir);
//      # Add target dirs to tar command
//      foreach ( $tardir as $key=>$dir ) {
//         $tarcom .= " ".$dir;
//      }
//      if ( eregi("WIN", $OS) ) {
//			$tarcom .= " | sohoadmin\program\includes\untar\gzip.exe > ".$bakdir."site_files.tgz";
//			}
//			//echo $tarcom;
//      # Create tgz file from docroot
//      //chdir($doc_root);
//      exec($tarcom, $out);

      # Switch back to original dir
      chdir($origdir);

      # Update status display
      $jsrep .= "bakstat('3','done');\n";
      $step = 4;

   }


   /// 4. Creating data table restoration file...
   ###-----------------------------------------------------------------------------
   if ( $step == 4 ) {
      # List all table names in array
      $dbtable = dbtables();

      # Tables to exclude from backup 
      $nobakdb = "login;site_backup;stats_browser;stats_byday;stats_byhour;stats_refer;stats_top25;stats_unique;";

      # Add desired target tables to dump command
      $baktbls = "";
      $bakuptbles = array();
      foreach ( $dbtable as $key=>$tblname ) {
         if ( !eregi(strtolower($tblname), $nobakdb) ) {
            $baktbls .= " ".$tblname;
            $bakuptbles[] = $tblname;
         }
      }
//
//      # Build and execute mysqldump command
//      $dumpcom = "mysqldump --add-drop-table --all --complete-insert --force -h ".$db_server." -u ".$db_un." -p".$db_pw." ".$db_name.$baktbls." > ".$bakdir."data_tables.sql";
//			//echo $bakfile; exit;
//			if ( eregi("WIN", $OS) ) {
//				chdir($doc_root);
//				$dumpcom = "sohoadmin\program\webmaster\mysqldump.exe --add-drop-table --all --complete-insert --force -h ".$db_server." -u ".$db_un." -p".$db_pw." ".$db_name.$baktbls." > ".$bakdir."data_tables.sql";
//			}
//      exec($dumpcom, $wtf, $dumperr);
//      if ( eregi("WIN", $OS) ) {
//      	chdir("sohoadmin\program\webmaster");
//			}
//			//echo getcwd()."<br>".$dumpcom; exit;
      # Update status display
	mysqlBackup($bakdir."data_tables.sql", $bakuptbles);
      
      $jsrep .= "bakstat('4','done');\n";
      $step = 5;

   }


   /// 5. Creating downloadable archive file...
   ###-----------------------------------------------------------------------------
   if ( $step == 5 ) {

      # Save current dir
      $origdir = getcwd();

      # Switch dir to docroot
      chdir($backup_dir);


      ## Build tar command
      ##=================================================

//      $tarcom = "tar -czvf site_backup-".date("m-d-Y-s", $bakfile).".tgz ".$bakfile." import.txt";
//			if ( eregi("WIN", $OS) ) {
//			$windoc = "..\..\includes\untar\\";
//			$tarcom = $windoc."tar.exe cvf - ".$bakfile." import.txt | ".$windoc."gzip.exe > site_backup-".date("m-d-Y-s", $bakfile).".tgz";
//			}
			     // echo $tarcom; echo "<br>"; echo getcwd(); echo "moo"; exit;
      # Create tgz file from docroot
      
      //exec($tarcom, $out);

	$tarcom = soho_create_zip("site_backup-".date("m-d-Y-s", $bakfile).".zip", array($bakfile,"import.txt"));
			//echo filesize("site_backup-".$bakfile.".tgz");
      # Kill import.txt (just needed it for .tgz)
      @unlink("import.txt");
		@unlink($bakfile.'/data_tables.sql');
		@unlink($bakfile.'/site_files.zip');
		rmdir($bakfile);
//		unlink($bakfile);
      # Switch back to original dir
      chdir($origdir);

      # Update status display
      $jsrep .= "bakstat('5','done');\n";
      $step = 6;

   }


   /// 6. Inserting backup record into site log...
   ###-----------------------------------------------------------------------------
   if ( $step == 6 ) {
      // Build db insert array
      #[---------------------------------------------]

      # BAK_ID
      $dbBak['BAK_ID'] = $bakfile;

      # BAK_DATE
      $dbBak['BAK_DATE'] = $dbdate;

      # BAK_TIME
      $dbBak['BAK_TIME'] = $dbtime;

      # BAK_TITLE
      $dbBak['BAK_TITLE'] = $bak_title;

      # BAK_NOTES
      $dbBak['BAK_NOTES'] = $bak_notes;

      # BAK_FILE
      $dbBak['BAK_FILE'] = "site_backup-".date("m-d-Y-s", $bakfile).".zip";

//      $dbQry = new mysql_insert("site_backup", $dbBak);
//      $dbQry->insert();

      # Update status display
      $jsrep .= "bakstat('6','done');\n";

   }

} elseif ( $do == "kill_bak" && $bakid != "" ) {
########################################################################################################
#     ____         __       __
#    / __ \ ___   / /___   / /_ ___
#   / / / // _ \ / // _ \ / __// _ \
#  / /_/ //  __// //  __// /_ /  __/
# /_____/ \___//_/ \___/ \__/ \___/
#
########################################################################################################
   // 1. Kill backup directory
   $bdir = $backup_dir.$bakid."/";
   if ( is_dir($bdir) ) { //exec("rm -rf ".$bdir); }

		
		@unlink($bdir.'/data_tables.sql');
		@unlink($bdir.'/site_files.zip');
		@unlink($bdir);
		rmdir($bdir);
	}
   // 2. Kill downloadable backup file
	$bfle = $backup_dir.$_GET['bakfile'];
   if ( file_exists($bfle) ) { unlink($bfle); }

   // 3. Kill db table record
   $krez = mysql_query("DELETE FROM site_backup WHERE BAK_ID = '$bakid'");


} elseif ( $do == "restore_bak" && $bakid != "" ) {
########################################################################################################
#     ____               __
#    / __ \ ___   _____ / /_ ____   _____ ___
#   / /_/ // _ \ / ___// __// __ \ / ___// _ \
#  / _, _//  __/(__  )/ /_ / /_/ // /   /  __/
# /_/ |_| \___//____/ \__/ \____//_/    \___/
########################################################################################################

   # Backup directory
	$bdir = $backup_dir.$bakid."/";

//	echo testArray($_REQUEST); exit;
	$bakfile = str_replace('.zip', '', $backup_dir.$_GET['bakfile']);
   # Extract archived site files
   $btgz = $bdir."site_files.zip";
   if ( file_exists($bakfile.'.zip') ) {
      $origdir = getcwd();
      chdir($_SESSION['docroot_path']);
      //rmdirr($doc_root."\media");
      //rmdirr($doc_root."\images");
      rmdirr($doc_root."\tCustom");
      rmdirr($doc_root."\template");
      chdir("sohoadmin");
      //rmdirr($doc_root."\tmp_content");
      chdir($_SESSION['docroot_path']);

//			if ( eregi("WIN", $OS) ) {
//				//echo $btgz; exit;
////				chdir($doc_root);
//				//exec("copy sohoadmin\program\webmaster\backups\\".$bakid."\site_files.tgz site_files.tgz");
//				
////				exec('sohoadmin\program\includes\untar\tar.exe -xvf sohoadmin\program\webmaster\backups\\'.$bakfile.".tgz");
////				exec('sohoadmin\program\includes\untar\gunzip.exe -d sohoadmin\program\webmaster\backups\\'.$bakfile.".tar");
////				$shellwin2 = 'sohoadmin\program\includes\untar\tar.exe -xvf sohoadmin\program\webmaster\backups\\'.$bakid."\site_files.tar";
////				$shellwin1 = "sohoadmin\program\includes\untar\gunzip.exe -d sohoadmin\program\webmaster\backups\\".$bakid."\site_files.tgz";
////				$extract = exec($shellwin1);
////				$extract = exec($shellwin2);
//				//echo $shellwin1."<br>";
//				//echo $shellwin2."<br>"; exit;
//			} else {
//				$origdir2 = getcwd();
//				chdir($backup_dir);
//				//exec('tar -xzvf '.$_GET['bakfile']);
//				unZip($_GET['bakfile']);
//				chdir($origdir2);
//				//exec("tar -xzvf ".$btgz);
//				unZip($btgz);
//				
//				
//
//				$php_suexec = strtoupper(php_sapi_name());
//			    if($php_suexec == "CGI"){
//			    	shell_exec("chmod -R 0755 $doc_root");
//			    }
//			}
	$origdir2 = getcwd();
	chdir($backup_dir);
	//exec('tar -xzvf '.$_GET['bakfile']);
	unZip($_GET['bakfile']);
	chdir($origdir2);
	//exec("tar -xzvf ".$btgz);
	unZip($btgz);
	
	

	$php_suexec = strtoupper(php_sapi_name());
    if($php_suexec == "CGI"){
    	shell_exec("chmod -R 0755 $doc_root");
    }
      chdir($origdir);
   }

   # Import SQL dump file
   $bsql = $bdir."data_tables.sql";
//   echo "mysql -h ".$db_server." -u ".$db_un." -p".$db_pw." ".$db_name." < ".$bsql; exit;
	if ( file_exists($bsql) ) {
//			if ( eregi("WIN", $OS) ) {
//				$currdir = getcwd();
//				chdir($doc_root."\sohoadmin\program\webmaster");
//				exec("mysql.exe -h ".$db_server." -u ".$db_un." -p".$db_pw." ".$db_name." < backups\\".$bakid."\data_tables.sql");
//				chdir($currdir);
//			} else {
//			//	echo "mysql -h ".$db_server." -u ".$db_un." -p".$db_pw." ".$db_name." < ".$bsql; exit;
//				exec("mysql -h ".$db_server." -u ".$db_un." -p".$db_pw." ".$db_name." < ".$bsql);
//			}
		mysqlImport($bsql);
	}

		
   echo js_alert("".lang("Site backup restored")."!");

} elseif ( $_POST['todo'] == "manual_import" || ($do == "import_bak" && $_FILES['upbak']['name'] != "" && preg_match("/zip/", $_FILES['upbak']['name'])) ) {
########################################################################################################
#     ____                                __
#    /  _/____ ___   ____   ____   _____ / /_
#    / / / __ `__ \ / __ \ / __ \ / ___// __/
#  _/ / / / / / / // /_/ // /_/ // /   / /_
# /___//_/ /_/ /_// .___/ \____//_/    \__/
#                /_/
########################################################################################################
   $upbak_path = $backup_dir.$_FILES['upbak']['name'];
   $upbak_name = $_FILES['upbak']['name'];

   # Pull backup file name from selected file or from uploaded file?
   if ( $_POST['todo'] == "manual_import" ) {
      # From drop-down selection
      if ( $_POST['backup_filename'] == "" ) {
         $nocpy = lang("Please select a backup file to import.");
      }
//      $upbak_path = $backup_dir.$_POST['backup_filename'];
//      $upbak_name = $_POST['backup_filename'];

   } else {
      # From uploaded file
      # Copy uploaded file to backups directory
      if ( !copy($_FILES['upbak']['tmp_name'], $upbak_path) ) {
         $nocpy = "".lang("Unable to copy file")."!\\n";
         $nocpy .= "$upbak_path\\n\\n";
         $nocpy .= "".lang("It is possible that the file size may exceed the amount allowed for by your site or server configuration").".\\n\\n";
         $nocpy .= "".lang("Note to server administrator").": \\n";
         $nocpy .= "".lang("upload_max_filesize=")."".ini_get('upload_max_filesize')."\\n\\n";
         $nocpy .= "".lang("Depending on your server setup, this setting may be defined server-wide")."";
         $nocpy .= "".lang("in the php.ini file or on a per-domain level either through the <VirtualHost> entry for this domain (httpd.conf)")." ";
         $nocpy .= "".lang("or through an .htaccess file").".";
         //echo js_alert("something");
      }
   }

   # Proceed with import if no error
   if ( $nocpy == "" ) {
      /// 1. Extract uploaded tgz file to re-create backup folder
      ###================================================================
      $odir = getcwd();
      chdir($backup_dir);
			if ( eregi("WIN", $OS) ) {
//				$windoc = "..\..\includes\untar\\";
//				exec($windoc."gunzip.exe -d ".$upbak_name);
//				$upbak_name1 = eregi_replace('tgz', 'tar', $upbak_name);
//				exec($windoc."tar.exe -xvf ".$upbak_name1);
				unZip($upbak_name);
			} else {
				//exec("tar -xzvf ".$upbak_name);
				unZip($upbak_name);
			}
      chdir($odir);


      /// 2. Read info txt file and build db insert array
      ###================================================================
      $dbData = array();

      // Format various file vars
      //-------------------------------------------------
      $tfile = $backup_dir."import.txt";

      if ( $tfhand = fopen($tfile, "r") ) {
         $body = fread($tfhand, filesize($tfile));
      	$line = split("\n", $body);

         // Split lines of txt file into vars and vals
         //--------------------------------------------------
      	for ($t = 0; $t <= count($line); $t++) {
            if ( eregi("BAK_", $line[$t]) ) {
               $tmp = split("~~=~~", $line[$t]);
               $dbData[$tmp[0]] = $tmp[1];
               //echo $tmp[0].": ".$tmp[1]."<br>\n";
            }
      	}

   	   fclose($tfhand);
   	   $error = 0;

   	} else {
   	   echo js_alert("".lang("Unable to read file")."!\\n\\n".lang("The following file may not be a valid backup file").":\\n$tfile");
   	   $error = 1;
   	}

   	# Kill import text filed
 		//@unlink($tfile);

   	/// 3. Insert imported backup info into database
      ###================================================================
      if ( $error < 1 ) {
         $dbgo = new mysql_insert("site_backup", $dbData);
         $dbgo->insert();
      }

   }else{ // End if backup file copied to server successfully
      echo js_alert($nocpy);
   }

}




# Declare main html var
$disHTML = "";
//$disHTML .= "<div style=\"position:relative;display:block;width:100%;height:40px;border:1px solid red;\">\n";
include_once("webmaster_nav_buttons.inc.php");
$disHTML .= $THIS_DISPLAY;
//$disHTML .= "</div><br/>\n";
###############################################################################
/// Update live backup status
#(----------------------------------------------------------------------------)
$tmpjs .= "<script language=\"javascript\">\n";
$tmpjs .= "function bakstat(n, status) {\n";
$tmpjs .= "  var desc = 'step'+n+'_desc';\n";
$tmpjs .= "  var stat = 'step'+n+'_stat';\n\n";

// Change step text from grayed-out to normal
$tmpjs .= "  document.getElementById(desc).className='text';\n\n";

// Determine formatted result string for passed status
$tmpjs .= "  if ( status == 'doing' ) {\n";
$tmpjs .= "     dstat='<i class=\"fademe\">".lang("In progress")."</i>';\n";
$tmpjs .= "  } else if ( status == 'done' ) {\n";
$tmpjs .= "     dstat='<b class=\"done\">".lang("Complete")."</b>';\n";
$tmpjs .= "  } else if ( status == 'err' ) {\n";
$tmpjs .= "     dstat='<b class=\"nodice\">".lang("Error")."</b>';\n";
$tmpjs .= "  }\n\n";

// Insert applicable result string into status cell
$tmpjs .= "  document.getElementById(stat).innerHTML=dstat;\n";
$tmpjs .= "  document.getElementById(stat).style.display='block';\n";

$tmpjs .= "}\n\n"; // End backup status update function
#(----------------------------------------------------------------------------)


###############################################################################
/// Show 'pop-up' progress div and disable main module layer
#(----------------------------------------------------------------------------)
$tmpjs .= "function backupnow() {\n";
# $tmpjs .= "   backingup.style.display = 'block';\n";
$tmpjs .= "   document.getElementById('backingup').style.display = 'block';\n";
$tmpjs .= "   document.mkbak.submit();\n";
$tmpjs .= "}\n";
$tmpjs .= "function upimp() {\n";
$tmpjs .= "   var fupbox = document.upimport.upbak.value;\n";
$tmpjs .= "   if ( fupbox.length > 8 ) {\n";
$tmpjs .= "      doingstuff.style.display = 'block';\n";
$tmpjs .= "      document.upimport.submit();\n";
$tmpjs .= "   } else {\n";
$tmpjs .= "      alert('".lang("No file selected")."!\\n".lang("Please choose a backup file from your hard drive").".');\n";
$tmpjs .= "   }\n\n";
$tmpjs .= "}\n";

$tmpjs .= "// Manual import form submit action\n";
$tmpjs .= "function manimp() {\n";
$tmpjs .= "   doingstuff.style.display = 'block';\n";
$tmpjs .= "   document.manimport.submit();\n";
$tmpjs .= "}\n";
#(----------------------------------------------------------------------------)


###############################################################################
/// Write user notes to 'pop-up' div layer
#(----------------------------------------------------------------------------)
$tmpjs .= "function usrnote(ntxt) {\n";
$tmpjs .= "   document.getElementById('usernotes').style.display = 'block';\n";
$tmpjs .= "   document.getElementById('notetxt').innerHTML = ntxt;\n";
//$tmpjs .= "   var note_disp = document.getElementById('usernotes').style.display;\n";
//$tmpjs .= "   if ( note_disp == 'none' ) {\n";
//$tmpjs .= "      document.getElementById('usernotes').style.display = 'block';\n";
//$tmpjs .= "      document.getElementById('notetxt').innerHTML = ntxt;\n";
//$tmpjs .= "   } else {\n";
//$tmpjs .= "      document.getElementById('usernotes').style.display = 'none';\n";
//$tmpjs .= "   }\n";
$tmpjs .= "}\n";
$tmpjs .= "function hidenote() {\n";
$tmpjs .= "   document.getElementById('usernotes').style.display = 'none';\n";
$tmpjs .= "}\n";


$tmpjs .= "function mksure_go(msg, addr) {\n";
$tmpjs .= "   var conwin = window.confirm(msg);\n";
$tmpjs .= "   if ( conwin ) {\n";
$tmpjs .= "      window.location=addr;\n";
$tmpjs .= "   }\n";
$tmpjs .= "}\n";

#(----------------------------------------------------------------------------)
$tmpjs .= "</script>\n";

///// Add status functions to object js header
//###::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//$dMod->jscripts .= $tmpjs;
$disHTML .= $tmpjs;

$disHTML .= "<!----~~~~~~~~~~~~~~~~~~~~~~~ Begin Div Layer: backingup ~~~~~~~~~~~~~~~~~~~~~~~---->\n";
$disHTML .= "<div id=\"backingup\" align=\"center\" style=\"display: none; z-index: 50; position: absolute; top: 177.5; left: 232.5; width: 325; height: 125; overflow: none; padding-top: 10px; background: #A5E6B3; border: 1px solid #2E2E2E; \">\n";
$disHTML .= " <table width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"text\">\n";
$disHTML .= "  <tr>\n";
$disHTML .= "   <td colspan=\"2\" width=\"100%\" style=\"padding-top: 30px; text-align: center;\"><b style=\"font-size: 14px;\">Website backup in progress...</b><br><br>".lang("This process may take several moments").".<br></td>\n";
$disHTML .= "\n";
$disHTML .= "  </tr>\n";
$disHTML .= " </table>\n";
$disHTML .= "</div>\n";
$disHTML .= "<!----~~~~~~~~~~~~~~~~~~~~~~~ End Div Layer: backingup ~~~~~~~~~~~~~~~~~~~~~~~---->\n";
$disHTML .= "\n";
$disHTML .= "\n";
$disHTML .= "\n";
$disHTML .= "\n";
$disHTML .= "<!----~~~~~~~~~~~~~~~~~~~~~~~ Begin Div Layer: doingstuff ~~~~~~~~~~~~~~~~~~~~~~~---->\n";
$disHTML .= "<div id=\"doingstuff\" align=\"center\" style=\"display: none; z-index: 50; position: absolute; top: 190; left: 245; width: 300; height: 100; overflow: none; padding-top: 15px; background: #A5E6B3; border: 1px solid #2E2E2E; \">\n";
$disHTML .= " <table width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"text\">\n";
$disHTML .= "  <tr>\n";
$disHTML .= "   <td colspan=\"2\" width=\"100%\" style=\"padding: 10px; text-align: left;\"><b style=\"font-size: 14px;\">Importing website backup file...</b><br><br>".lang("This process may take several moments, depending on connection speed").".<br></td>\n";
$disHTML .= "\n";
$disHTML .= "  </tr>\n";
$disHTML .= " </table>\n";
$disHTML .= "</div>\n";
$disHTML .= "<!----~~~~~~~~~~~~~~~~~~~~~~~ End Div Layer: doingstuff ~~~~~~~~~~~~~~~~~~~~~~~---->\n";
$disHTML .= "\n";
$disHTML .= "\n";
$disHTML .= "\n";
$disHTML .= "\n";
$disHTML .= "<!----~~~~~~~~~~~~~~~~~~~~~~~ Begin Div Layer: usernotes ~~~~~~~~~~~~~~~~~~~~~~~---->\n";
$disHTML .= "<div id=\"usernotes\" align=\"center\" style=\"display: none; z-index: 50; position: absolute; top: 165; left: 170; width: 450; height: 150; overflow: none; padding-top: 0px; background: #F8F9FD; border: 1px solid #2E2E2E; \">\n";
$disHTML .= " <table width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"text\">\n";
$disHTML .= "  <tr>\n";
$disHTML .= "   <td align=\"left\" class=\"fgrn_title\">\n";
$disHTML .= "\n";
$disHTML .= "    ".lang("User notes for this backup")."\n";
$disHTML .= "   </td>\n";
$disHTML .= "   <td align=\"right\" class=\"fgrn_title\">\n";
$disHTML .= "    <span class=\"text\" style=\"font-weight: normal;\">\n";
$disHTML .= "    [ <a href=\"#\" onclick=\"document.getElementById('usernotes').style.display='none'\" class=\"del bold\">Close Window</a> ]\n";
$disHTML .= "    </span>\n";
$disHTML .= "   </td>\n";
$disHTML .= "  </tr>\n";
$disHTML .= "\n";
$disHTML .= "  <tr>\n";
$disHTML .= "   <td colspan=\"2\" width=\"100%\" style=\"padding: 10px; text-align: left;\"><span id=\"notetxt\" style=\"font-size: 105%;\">&nbsp;</span></td>\n";
$disHTML .= "  </tr>\n";
$disHTML .= " </table>\n";
$disHTML .= "</div>\n";
$disHTML .= "<!----~~~~~~~~~~~~~~~~~~~~~~~ End Div Layer: usernotes ~~~~~~~~~~~~~~~~~~~~~~~---->\n";


//###############################################################################
///// Hidden div layer object for 'Backup in progress...' message
//#::--------------------------------------------------------------------------::
//$upDiv = new pop_div("backingup", "325", "125");
////$upDiv->css['display'] = "block";
//$upDiv->css['padding-top'] = "10px";
//$upDiv->css['background'] = "#A5E6B3";
//$divtxt = "<b style=\"font-size: 14px;\">".lang("Website backup in progress...")."</b><br><br>";
//$divtxt .= lang("This process may take several moments.")."<br>";
//$dMod->popdivs .= $upDiv->mkpop($divtxt, "padding-top: 30px; text-align: center;");
//#::--------------------------------------------------------------------------::
//
//
//###############################################################################
///// Hidden div layer object for upload message
//#::--------------------------------------------------------------------------::
//$upDiv = new pop_div("doingstuff", "300", "100");
//$upDiv->css['padding-top'] = "15px";
//$upDiv->css['background'] = "#A5E6B3";
//$divtxt = "<b style=\"font-size: 14px;\">".lang("Importing website backup file...")."</b><br><br>";
//$divtxt .= lang("This process may take several moments, depending on connection speed.")."<br>";
//$dMod->popdivs .= $upDiv->mkpop($divtxt);
//#::--------------------------------------------------------------------------::
//
//
//###############################################################################
///// Hidden div layer for user backup notes
//#::--------------------------------------------------------------------------::
//$unDiv = new pop_div("usernotes", "450", "150");
//$unDiv->boxtitle("User notes for this backup", "fgrn_title");
//$unDiv->boxclose("Close Window", "text");
//$unDiv->boxkill['aclass'] = "del bold";
//$divtxt = "<span id=\"notetxt\" style=\"font-size: 105%;\">&nbsp;</span>";
//$dMod->popdivs .= $unDiv->mkpop($divtxt);
//#::--------------------------------------------------------------------------::

########################################################################################################
#  _  _                ___            _
# | \| | ___ __ __ __ | _ ) __ _  __ | |__ _  _  _ __
# | .` |/ -_)\ V  V / | _ \/ _` |/ _|| / /| || || '_ \
# |_|\_|\___| \_/\_/  |___/\__,_|\__||_\_\ \_,_|| .__/
#                                               |_|
########################################################################################################
// Format date and time
//-------------------------------------------
$tdate = date("F d, Y");
$ttime = date("g:ia");
$tgdate = getdate();

$disHTML .= "<script language=\"JavaScript\">\n";
$disHTML .= "parent.header.flip_header_nav('WEBMASTER_MENU_LAYER');\n\n";

$disHTML .= "function SV2_findObj(n, d) { //v3.0\n";
$disHTML .= "  var p,i,x;  if(!d) d=document; if((p=n.indexOf(\"?\"))>0&&parent.frames.length) {\n";
$disHTML .= "    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}\n";
$disHTML .= "  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];\n";
$disHTML .= "  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=SV2_findObj(n,d.layers[i].document); return x;\n";
$disHTML .= "}\n";

$disHTML .= "function SV2_popupMsg(msg) { //v1.0\n";
$disHTML .= "  alert(msg);\n";
$disHTML .= "}\n";
$disHTML .= "function SV2_openBrWindow(theURL,winName,features) { //v2.0\n";
$disHTML .= "  window.open(theURL,winName,features);\n";
$disHTML .= "}\n";
$disHTML .= "\n";
$disHTML .= "show_hide_layer('NEWSLETTER_LAYER?header','','hide');\n";
$disHTML .= "show_hide_layer('MAIN_MENU_LAYER?header','','hide');\n";
$disHTML .= "show_hide_layer('CART_MENU_LAYER?header','','hide');\n";
$disHTML .= "show_hide_layer('DATABASE_LAYER?header','','hide');\n";
$disHTML .= "show_hide_layer('WEBMASTER_MENU_LAYER?header','','show');\n";
$disHTML .= "var p = 'Site Backup / Restore';\n";
$disHTML .= "parent.frames.footer.setPage(p);\n";
$disHTML .= "</script>\n";



## Is exec disabled?
## If so notify user and disable backup creation
$disabled_list = strtoupper(ini_get("disable_functions"));
//$disabled_list = "EXEC,PASSTHRU,SHELL_EXEC,CHGRP,CHOWN,DL,PROC_OPEN,PROC_CLOSE";
$disabled = split(",", $disabled_list);
if (in_array('EXEC', $disabled)){
   $disHTML .= "  <div style=\"margin-bottom: 5px; background-color: #D70000; color: #fff; padding: 5 15; font-size: 12px;\">\n";
	$disHTML .= "     <p style=\"padding: 2px; font-weight: bold; font-style: normal;\">".lang("Oops!  Your server has disabled the php function 'exec'.  Site Backup and Restore requires this function to work properly.  Please contact your host and have them enable the php function 'exec' to use this feature.")."</p>";
	$disHTML .= "  </div>\n";
}

$disHTML .= "<table width=\"100%\" border=\"0\" cellpadding=\"4\" cellspacing=\"0\" class=\"feature_sub\">\n";

# Create New Restoration Point
$disHTML .= " <tr>\n";
$disHTML .= "  <td colspan=\"2\" class=\"fsub_title\">";

$disHTML .= "<form name=\"mkbak\" method=\"post\" action=\"".$_SERVER['PHP_SELF']."\" style=\"margin: 0; padding: 0;\">\n";
$disHTML .= "<input type=\"hidden\" name=\"do\" value=\"new_backup\">\n";
$disHTML .= "<input type=\"hidden\" name=\"bakfile\" value=\"".$tgdate['0']."\">\n";
$disHTML .= "<input type=\"hidden\" name=\"dbdate\" value=\"".$tdate."\">\n";
$disHTML .= "<input type=\"hidden\" name=\"dbtime\" value=\"".$ttime."\">\n";

$disHTML .= lang("Create New Restoration Point")."</td>\n";
$disHTML .= " </tr>\n";

$disHTML .= " <tr>\n";

/// Backup title and user notes
###===================================================================================
$disHTML .= "  <td>\n";
$disHTML .= "   <table width=\"100%\"  border=\"0\" cellpadding=\"4\" cellspacing=\"0\" class=\"text\">\n";
$disHTML .= "    <tr>\n";
$disHTML .= "     <td valign=\"top\">".lang("Current Date").":</td>\n";
$disHTML .= "     <td>".$tdate."</td>\n";
$disHTML .= "    </tr>\n";

# bak_title
$disHTML .= "    <tr>\n";
$disHTML .= "     <td valign=\"top\">".lang("Backup Title").":</td>\n";
$disHTML .= "     <td><input name=\"bak_title\" type=\"text\" class=\"tfield_hex\" style=\"width: 200px; background-color: #F5F5F5;\" value=\"User Backup\" id=\"baktitle\"></td>\n";
$disHTML .= "    </tr>\n";

# bak_notes
$disHTML .= "    <tr>\n";
$disHTML .= "     <td valign=\"top\" id=\"pad_nobtm\">".lang("User Notes").":</td>\n";
$disHTML .= "     <td valign=\"top\" id=\"pad_nobtm\">\n";
$disHTML .= "      <textarea name=\"bak_notes\" class=\"tfield\" style=\"width: 270px; height: 75px; background-color: #F5F5F5;\" id=\"baknotes\"></textarea>\n";
$disHTML .= "     </td>\n";
$disHTML .= "    </tr>\n";
$disHTML .= "   </table>\n";
$disHTML .= "  </td>\n";


/// Live status table
###===================================================================================

// Build array of backup progress steps
#[----------------------------------------------------------]
$bstat[0] = lang("Site backup in progress. Please hold.");
$bstat[] = lang("Creating folder for this backup");
$bstat[] = lang("Writing backup info to text file");
$bstat[] = lang("Archiving site content and files");
$bstat[] = lang("Creating data table restoration file");
$bstat[] = lang("Creating downloadable archive file");
$bstat[] = lang("Inserting backup record into site log");

$disHTML .= "  <td width=\"333px\" class=\"fsub_border\">\n";
$disHTML .= "   <table width=\"100%\"  border=\"0\" cellpadding=\"4\" cellspacing=\"0\" class=\"text\">\n";

# Display backup progress steps as grayed out with hidden status
#---------------------------------------------------------------------------
for ( $b = 1; $b < count($bstat); $b++ ) {
   $disHTML .= "    <tr>\n";
   $disHTML .= "     <td class=\"fademe\" id=\"step".$b."_desc\">".$b.". ".lang($bstat[$b])."...</td>\n";
   $disHTML .= "     <td class=\"hideme\" id=\"step".$b."_stat\">".lang("Done")."</td>\n";
   $disHTML .= "    </tr>\n";
}

$disHTML .= "   </table>\n";
$disHTML .= "  </td>\n";
$disHTML .= " </tr>\n";

# Spacer row
$disHTML .= " <tr>\n";
$disHTML .= "  <td colspan=\"2\" align=\"center\" valign=\"top\">&nbsp;</td>\n";
$disHTML .= " </tr>\n";

# [ Back-Up Website Now! ]
$disHTML .= " <tr>\n";
$disHTML .= "  <td colspan=\"2\" align=\"center\" valign=\"top\">\n";

## Is exec disabled?
## If so notify user and disable backup creation
if (in_array('EXEC', $disabled)){
	$disHTML .= "  <p style=\"padding: 2px; font-weight: bold; font-style: normal;\">".lang("Please enable the php function 'exec' to use this feature.")."</p>";
}else{
   $disHTML .= "   <input name=\"gobutton\" type=\"button\" onClick=\"backupnow();\" value=\"Back-Up Website Now!\"".$btn_save.">\n";
}

$disHTML .= "  </td>\n";
$disHTML .= " </tr>\n";
$disHTML .= "</table></form><br>\n";

########################################################################################################
#  ___                         _      ___  _  _
# |_ _| _ __   _ __  ___  _ _ | |_   | __|(_)| | ___
#  | | | '  \ | '_ \/ _ \| '_||  _|  | _| | || |/ -_)
# |___||_|_|_|| .__/\___/|_|   \__|  |_|  |_||_|\___|
#             |_|
########################################################################################################
$disHTML .= "<table width=\"100%\"  border=\"0\" cellpadding=\"4\" cellspacing=\"0\" class=\"feature_sub\">\n";
$disHTML .= "<form name=\"upimport\" method=\"post\" action=\"".$_SERVER['PHP_SELF']."\" enctype=\"multipart/form-data\">\n";
$disHTML .= "<input type=\"hidden\" name=\"do\" value=\"import_bak\">\n";
$disHTML .= "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"50000000\">\n";
$disHTML .= " <tr>\n";
$disHTML .= "  <td colspan=\"3\" class=\"fsub_title\">".lang("Upload and import site backup file")."</td>\n";
$disHTML .= " </tr>\n";

# Echo ini settings to troubleshoot upload caps
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*
$disHTML .= " <tr>\n";
$disHTML .= "  <td>post_max_size:</td>\n";
$disHTML .= "  <td colspan=\"2\" class=\"nodice\">".ini_get('post_max_size')."</td>\n";
$disHTML .= " </tr>\n";
$disHTML .= " <tr>\n";
$disHTML .= "  <td>max_execution_time:</td>\n";
$disHTML .= "  <td colspan=\"2\" class=\"nodice\">".ini_get('max_execution_time')."</td>\n";
$disHTML .= " </tr>\n";
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

# upbak
if (in_array('EXEC', $disabled)){
   $disHTML .= "  <tr>\n";
   $disHTML .= "     <td colspan=\"3\">\n";
	$disHTML .= "        <p style=\"padding: 2px; font-weight: bold; font-style: normal;\">".lang("Please enable the php function 'exec' to use this feature.")."</p>";
	$disHTML .= "     </td>\n";
	$disHTML .= "  </tr>\n";
}else{
   $disHTML .= " <tr>\n";
   $disHTML .= "  <td><b>".lang("Select Backup File").":</b></td>\n";
   $disHTML .= "  <td><input name=\"upbak\" type=\"file\" class=\"tfield\" style=\"width: 300px;\"></td>\n";
   $disHTML .= "  <td><input type=\"button\" onClick=\"upimp()\" value=\"".lang("Import Backup File")."\" ".$btn_save." id=\"pressable\"></td>\n";
   $disHTML .= " </tr>\n";
}



$disHTML .= "</form>\n";
$disHTML .= "</table><br>\n";


# Manually extract backup file
//$disHTML .= "<table width=\"100%\"  border=\"0\" cellpadding=\"4\" cellspacing=\"0\" class=\"feature_sub\">\n";
//$disHTML .= "<form name=\"manimport\" method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">\n";
//$disHTML .= "<input type=\"hidden\" name=\"todo\" value=\"manual_import\">\n";
//$disHTML .= " <tr>\n";
//$disHTML .= "  <td colspan=\"3\" class=\"fsub_title\">".lang("Import a backup file that you uploaded via FTP")."</td>\n";
//$disHTML .= " </tr>\n";

# backup_filename
//$disHTML .= " <tr>\n";
//$disHTML .= "  <td><b>".lang("Select Backup File").":</b></td>\n";
//$disHTML .= "  <td>\n";
//$disHTML .= "   <select name=\"backup_filename\" style=\"width: 300px;\">\n";
//$disHTML .= "    <option value=\"\">Please select backup file...</option>\n";

# Read backups folder for .tgz backup files
if ( $handle = opendir("backups") ) {
   while ( false !== ($file = readdir($handle)) ) {
      if ( preg_match("/\.zip/", $file) ) {
        // $disHTML .= "    <option value=\"".$file."\">".$file." [".human_filesize(filesize("backups/".$file))."]</option>\n";     	
			chdir('backups');

//			if ( eregi("WIN", $OS) ) {
////				$targzfile = eregi_replace('\.tgz$', '.tar.gz', $file);
////				$tarfile = eregi_replace('\.tgz$', '.tar', $file);			
////				exec("..\..\includes\untar\gunzip.exe -d ".$file);
////				exec("..\..\includes\untar\\tar.exe -xvf ".$tarfile." import.txt");						
////				exec("..\..\includes\untar\gzip.exe ".$tarfile);
////				rename($targzfile, $file);
//				
//				
//			} else {
//				//exec("tar -xzf ".$file." import.txt");
//				$rezz = unZip($file);
//				
//			}		
			$rezz = unZip($file, array('import.txt'));
			$tfile = 'import.txt';
         if ( $tfhand = fopen($tfile, "r") ) {
            $body = fread($tfhand, filesize($tfile));
            $body = $body."\nBAK_FILE~~=~~".$file;
            $line = split("\n", $body);
   
            // Split lines of txt file into vars and vals
            //--------------------------------------------------
            for ($t = 0; $t <= count($line); $t++) {
               if ( eregi("BAK_", $line[$t]) ) {
                  $tmp = split("~~=~~", $line[$t]);
                  $dbData[$tmp[0]] = $tmp[1];
               }
            }
   
            fclose($tfhand);
            $error = 0;
   			$tfile = '';
         }
			$bak_id = $dbData['BAK_ID'];
			$bak_tgz_array[$bak_id] = $dbData;	
//			echo testArray($dbData);
			unlink('import.txt');
			chdir($_SESSION['doc_root'].DIRECTORY_SEPARATOR.'sohoadmin'.DIRECTORY_SEPARATOR.'program'.DIRECTORY_SEPARATOR.'webmaster');	      	      	
      }
   }
}


//$disHTML .= "   </select>\n";
//$disHTML .= "  </td>\n";
//$disHTML .= "  <td><input type=\"button\" onClick=\"manimp()\" value=\"".lang("Import Backup File")."\" ".$btn_save." id=\"pressable\"></td>\n";
//$disHTML .= " </tr>\n";
//
//$disHTML .= "</form>\n";
//$disHTML .= "</table><br>\n";

$backprefObj->set('direct-link', '');

//echo '<p>['.$test_backup_fileStr.']</p>';
//if ( $backprefObj->get('direct-link') == "" ) {
//	ob_start();
//	$testdownloadUrl = 'http://'.$_SESSION['docroot_url'].'/sohoadmin/program/webmaster/backup_download.php?todo=download_backup&backup_file='.$test_backup_fileStr;
//	$testdownloadOutput = include_r($testdownloadUrl);
//	ob_end_clean();
//	if ( strlen($testdownloadOutput) > 0 ) {
//		$backprefObj->set('direct-link', 'off');
//	} else {
//		$backprefObj->set('direct-link', 'on');
//	}
//}


########################################################################################################
#  ___           _                    ___       _       _
# | _ \ ___  ___| |_  ___  _ _  ___  | _ \ ___ (_) _ _ | |_  ___
# |   // -_)(_-<|  _|/ _ \| '_|/ -_) |  _// _ \| || ' \|  _|(_-<
# |_|_\\___|/__/ \__|\___/|_|  \___| |_|  \___/|_||_||_|\__|/__/
########################################################################################################
$disHTML .= "<table width=\"100%\"  border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"feature_sub\" id=\"restore_points\">\n";
$disHTML .= "<tr>\n";
$disHTML .= " <td colspan=\"7\" class=\"fsub_title\">".lang("Restore from a previous backup")."</td>\n";
$disHTML .= "</tr>\n";
$disHTML .= "<tr>\n";
$disHTML .= " <td colspan=\"7\" class=\"fsub_col\" style=\"padding: 2px; color: #D70000; font-weight: normal; font-style: normal;\">\n";
$disHTML .= "  ".lang("Note: When downloading backups, make sure to save the file with a '.zip' extension")."<br>\n";
$disHTML .= "  ".lang("Note: After backing up your site, please download the backup and delete it here for security purposes.")."<br/>\n";
$disHTML .= "  ".lang("Note: Upload large backup files using FTP to").": ".str_replace($_SESSION['doc_root'], '', getcwd()."/backups/")."<br/>\n";
$disHTML .= "  ".lang("Note:")." ".lang("Downloading and deleting backups will save you disk space.")."<br/>\n";

if ( $backprefObj->get('direct-download-link') == "on" ) {
	$disHTML .= "  ".lang("Direct download links enabled (easier backup downloading, but problematic on some servers).")."<a href=\"backup_restore.php?direct-download=off\">".lang('Click to disable')."</a><br/>\n";
} else {
	$disHTML .= "  <a href=\"backup_restore.php?direct-download=on\">".lang("Click this link if you are having trouble downloading backups.")."<br/>\n";
}

$disHTML .= " </td>\n";
$disHTML .= "</tr>\n";
$disHTML .= "<tr>\n";
$disHTML .= " <td width=\"30%\" class=\"fsub_col\" id=\"bdr_noleft\">".lang("Backup Title")."</td>\n";
$disHTML .= " <td class=\"fsub_col\" align=\"left\" id=\"bdr_noleft\">".lang("Backup Date")."</td>\n";
$disHTML .= " <td class=\"fsub_col\" align=\"left\" id=\"bdr_noleft\">".lang("Backup Time")."</td>\n";
$disHTML .= " <td class=\"fsub_col\" id=\"bdr_noleft\">&nbsp;</td>\n";
$disHTML .= " <td class=\"fsub_col\" id=\"bdr_noleft\">&nbsp;</td>\n";
$disHTML .= " <td class=\"fsub_col\" id=\"bdr_noleft\">&nbsp;</td>\n";
$disHTML .= " <td class=\"fsub_col\" id=\"bdr_noleft\">&nbsp;</td>\n";
$disHTML .= "</tr>\n";

// Format backup file directory for d/l links
$dlbak = eregi_replace($_SESSION['docroot_path'], "", getcwd());
$dlbak .= "/backups/";

// Build js delete confirm text
$warn_del = lang("Are you sure you want to permanently delete this backup?");

// Build js restore confirm text
$warn_res = "!!--------------".lang("WARNING")."--------------!!\\n";
$warn_res .= lang("Current website will be replaced with backup data.")."\\n";
$warn_res .= lang("All unsaved data will be lost.")."\\n\\n";
$warn_res .= lang("Are you sure you want to restore the backup?");

######################################################################################################
/// Pull restore points from db table and list
###===================================================================================================
//$rez = mysql_query("SELECT * FROM site_backup ORDER BY BAK_ID DESC");
ksort($bak_tgz_array, SORT_NUMERIC);
foreach($bak_tgz_array as $gbak){
//	echo $baktgz;
//	echo testArray($bakdata);
//while ( $gbak = mysql_fetch_array($rez) ) {

   // Make sure file exists and format download link
   //------------------------------------------------------

   $chkfile = getcwd()."/backups/".$gbak['BAK_FILE'];
   if ( file_exists($chkfile) ) {
   	$tmpsize = filesize($chkfile);
   	$sizem = round(($tmpsize/1048576),4);
   	$sizem = $sizem.'M';

   	if( ini_get("memory_limit") < $sizem && ini_get("memory_limit") != '' || $backprefObj->get('direct-download-link') == 'on' ){
   		$dl_link = "<a href='http://".$_SESSION['this_ip'].'/sohoadmin/program/webmaster/backups/'.$gbak['BAK_FILE']."'>".lang("Download")."</a>\n";
   	} ELSE {
      	$dl_link = "<a href='backup_download.php?todo=download_backup&backup_file=".$gbak['BAK_FILE']."'>".lang("Download")."</a>\n";
   	}
      
      $tmpsize = $tmpsize / 1024;
      $tmpsize = sprintf("%01.2f", $tmpsize);
   } else {
      $dl_link = "<font class=\"fademe\">".lang("Download")."</font>\n";
   }

   // Build delete and restore hrefs
   //-------------------------------------------------------
   $kill_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?do=kill_bak&bakid=".$gbak['BAK_ID']."&bakfile=".$gbak['BAK_FILE'];
   $restore_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?do=restore_bak&bakid=".$gbak['BAK_ID']."&bakfile=".$gbak['BAK_FILE'];

   // Check for user notes and make link if found
   //-------------------------------------------------------
   if ( strlen($gbak['BAK_NOTES']) > 1 ) {
      $unote_link = "<a href=\"#\" onClick=\"usrnote('".eregi_replace("\"", "&quot;", $gbak['BAK_NOTES'])."');\">".lang("Notes")."</a>\n";
   } else {
      $unote_link = "<font class=\"fademe\">".lang("Notes")."</font>\n";
   }


   /// Display basic info and action links for this restore point
   ###==========================================================================
   $disHTML .= "<tr>\n";
   $disHTML .= " <td class=\"fsub_border\" id=\"bdr_noleft\">".$gbak['BAK_TITLE']."</td>\n";
   $disHTML .= " <td class=\"fsub_border\" id=\"bdr_noleft\">".$gbak['BAK_DATE']."</td>\n";
   $disHTML .= " <td class=\"fsub_border\" align=\"center\" id=\"bdr_noleft\">".$gbak['BAK_TIME']."</td>\n";

   # [ Notes ]
   $disHTML .= " <td align=\"center\" class=\"fsub_border\" id=\"bdr_noleft\">[ ".$unote_link." ]</td>\n";

   # [ Download ]
   $disHTML .= " <td align=\"center\" class=\"fsub_border\" id=\"bdr_noleft\">[ ".$dl_link." ]</td>\n";

   # [ Delete ]
   $disHTML .= " <td align=\"center\" class=\"fsub_border\" id=\"bdr_noleft\">\n";
   $disHTML .= "  [ <a href=\"#\" onclick=\"mksure_go('".$warn_del."', '".$kill_link."')\" class=\"del\">".lang("Delete")."</a> ]\n";
   $disHTML .= " </td>\n";

   # [ Restore ]
   $disHTML .= " <td align=\"center\" class=\"fsub_border\" id=\"bdr_noleft\">\n";
   $disHTML .= "  [ <a href=\"#\" onclick=\"mksure_go('".$warn_res."', '".$restore_link."')\" class=\"sav\">".lang("Restore")."</a> ]\n";
   $disHTML .= " </td>\n";
   $disHTML .= "</tr>\n";
}

$disHTML .= "</table>\n";


//// Wrap module html in standard feature table
//#::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//$ttl = lang("Webmaster: Site Backup and Restoration");
//$dMod->add_fgroup($ttl, $disHTML);
//
//
//###::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//### Output compiled module html!
//###::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//echo $dMod->make_module();
//echo jscall($jsrep);


echo $disHTML;

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Here you can backup, restore, import and download any backups on your site.");

# Build into standard module template
$module = new smt_module($module_html);
$module->meta_title = lang("Site Backup and Restore");
$module->add_breadcrumb_link(lang("Backup / Restore"), "program/webmaster/backup_restore.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/full_size/backup_restore-enabled.gif";
$module->heading_text = lang("Site Backup and Restore");
$module->description_text = $instructions;
$module->good_to_go();
?>
