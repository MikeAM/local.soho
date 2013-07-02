<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch(R) Pro Edition Site Builder
## Version 4.6
##
## Author: 			Mike Johnston [mike.johnston@soholaunch.com]
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugzilla.soholaunch.com
## Release Notes:	http://forums.soholaunch.com
##############################################################################

##############################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2006 Soholaunch.com, Inc. and Mike Johnston
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

//
//
// Note: IF YOU ARE SEEING THIS IN A BROWSER WINDOW WHEN TRYING TO ACCESS
// THE PRODUCT; YOU DO NOT HAVE PHP RUNNING. PLEASE INSTALL PHP AND TRY
// THIS PROGRAM AGAIN.
//
//
//

error_reporting(E_PARSE);
//if(count($_POST) > 0){
//	include('program/includes/shared_functions.php');
//	echo testArray($_POST);
//	exit;
//}
// If setup file has been executed already, insert security
// procedures to eliminate hacker attempts at changing db
/*-----------------------------------------------------------*/
if (file_exists("config/isp.conf.php") && $STEP == "" ) {
//	include("includes/config.php");
//	include("includes/login.php");
	echo "<script language=\"javascript\"> \n";
	echo "location.href = \"index.php\"; \n";
	echo "</script> \n";
	exit;
}
error_reporting(E_ERROR || E_PARSE);
/*-----------------------------------------------------------*/

// Make "v" inactive by default (link to 'killer v' function if soho domain
$kv = "v";

include("includes/emulate_globals.php");
$OS = strtoupper(PHP_OS);
/*=================================================================================*
## Click 'killer v' to delete isp.conf.php and .lic files
    _       _________ _        _        _______  _______
   | \    /\\__   __/( \      ( \      (  ____ \(  ____ )  |\     /|
   |  \  / /   ) (   | (      | (      | (    \/| (    )|  | )   ( |
   |  (_/ /    | |   | |      | |      | (__    | (____)|  | |   | |
   |   _ (     | |   | |      | |      |  __)   |     __)  ( (   ) )
   |  ( \ \    | |   | |      | |      | (      | (\ (      \ \_/ /
   |  /  \ \___) (___| (____/\| (____/\| (____/\| ) \ \__    \   /
   |_/    \/\_______/(_______/(_______/(_______/|/   \__/     \_/
/*---------------------------------------------------------------------------------*/
if ( eregi(".soholaunch.com",$_SERVER['SERVER_NAME']) ) {

   $kv = "<a href=\"http://$SERVER_NAME/sohoadmin/setup.php?killconf=yes\">v</a>";

   if ( $killconf == "yes" ) {

      ##******************************************************
      ## READ ISP.CONF.PHP CONFIGURATION VARIABLE FILE
      ##******************************************************
      $filename = "config/isp.conf.php";		// This server should be setup; if not; let's set it up.

      if ($file = fopen("$filename", "r")) {
      	$body = fread($file,filesize($filename));
      	$lines = split("\n", $body);
      	$numLines = count($lines);

      	for ($x=2;$x<=$numLines;$x++) {

      		// Register all Variables contained inside isp.conf file
      		//================================================================
      		if (!eregi("#", $lines[$x])) {
      			$variable = strtok($lines[$x], "=");
      			$value = strtok("\n");
      			$value = rtrim($value);

      			${$variable} = $value;
      		}
      	}

      	fclose($file);

      	$user_table = "login";
      	$com_key = "pro";



      	if (eregi("WIN", $OS) && isset($windir)) {
      		$WINDIR = $windir;
      		if ( !session_is_registered("WINDIR") ) { session_register("WINDIR"); }
      	}

      } // End If File Open


      $link = mysql_connect("$db_server", "$db_un","$db_pw") || die("Could not connect to database '$db_name' ($db_server). Your database server may be down or your database setup may be wrong.");
      $sel = mysql_select_db("$db_name");
      $result = mysql_list_tables("$db_name");

      ## DROP THESE TABLES
      ##XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
      $drop_tables = "login::site_specs::cart_options::";

      $i = 0;
      while ($i < mysql_num_rows ($result)) {
      	$tb_names[$i] = mysql_tablename ($result, $i);

      	if ( eregi("$tb_names[$i]::", $drop_tables) ) {
      	   mysql_query("DROP TABLE $tb_names[$i]") || die ("Could not drop table $tb_names[$i]");
      	}

      	$i++;
      }

      ## KILL THESE FILES
      ##XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
      unlink("config/isp.conf.php");
      unlink("config/host.conf.php");
      unlink("filebin/soholaunch.lic");
      unlink("filebin/type.lic");
      unlink("filebin/nowiz.txt");
      
	$owner_email = $_POST['SETUP_DFUSER'];
	$SETUP_DOMAIN = str_replace("http://", "", $_POST['SETUP_DOMAIN']);
	$nowww = preg_replace("/^www\./i", "", $SETUP_DOMAIN);	
	$password = $_POST['SETUP_DFPASS'];
	mysql_db_query("$sqlUser","CREATE TABLE login (PriKey INT NOT NULL AUTO_INCREMENT PRIMARY KEY, Owner CHAR(150), First_Name CHAR(75), Last_Name CHAR(75), Email CHAR(150), Username CHAR(50), Password CHAR(50), Rank CHAR(255))");
	mysql_query("INSERT INTO login VALUES('NULL','".$nowww."','WEBMASTER','".$_POST['first_name']." ".$_POST['last_name']."','".$owner_email."','".$owner_email."','".$password."','".md5($password)."')");
	
	include_once('includes/mysql_insert.class.php');
	mysql_query('CREATE TABLE site_specs ( prikey int(50) NOT NULL auto_increment primary key, df_company varchar(255) default NULL, df_address1 varchar(255) default NULL, df_address2 varchar(255) default NULL, df_city varchar(100) default NULL, df_state varchar(100) default NULL, df_zip varchar(25) default NULL, df_country varchar(50) default NULL, df_phone varchar(50) default NULL, df_email varchar(255) default NULL, df_domain varchar(255) default NULL, df_page varchar(50) default NULL, df_logo varchar(255) default NULL, df_lang varchar(50) default NULL, news_cat varchar(10) default NULL, promo_cat varchar(10) default NULL, copyright blob, df_misc1 blob, df_misc2 blob, df_hdrtxt varchar(255) default NULL, df_slogan varchar(255) default NULL, dev_mode varchar(255) default NULL, startpage varchar(255) default NULL, df_fax varchar(50) default NULL)');
	// End if blog table exists
	$sql_prob = 0; // Error counter
	
	$headertext = "Welcome";
	$data = array();
	$data['df_company'] = $_POST['first_name']." ".$_POST['last_name'];
	$data['df_address1'] = '';
	$data['df_address2'] = '';
	$data['df_city'] = '';
	$data['df_state'] = '';
	$data['df_zip'] = '';
	$data['df_country'] = '';
	$data['df_phone'] = '';
	$data['df_email'] = $owner_email;
	$data['df_domain'] = $SETUP_DOMAIN;
	$data['df_page'] = 'Home';
	$data['df_logo'] = '';
	$data['df_lang'] = 'english.php';
	$data['news_cat'] = '1';
	$data['promo_cat'] = '2';
	$data['copyright'] = date('Y');
	$data['df_misc1'] = '';
	$data['df_misc2'] = '';
	$data['df_hdrtxt'] = 'Welcome';
	$data['df_slogan'] = '';
	$data['dev_mode'] = '';
	$data['startpage'] = 'Home';
	$data['df_fax'] = '';
	$myqry = new mysql_insert("site_specs", $data);
	if(!$myqry->insert()){
		echo mysql_error();
	}
	      
      

   } // End if killer v activated

} // End if soho domain
/*=================================================================================*/



// Get post data if necessary (If register globals off)
// -----------------------------------------------------
while (list($name, $value) = each($_POST)) {
	${$name} = $value;
}

if ( $STEP == "LICBACK" ) {
   ############################################################################################
   ## ----------------------------------------------------------------------------------------
   // Auto-licence response recieved! What's it say?
   ## ----------------------------------------------------------------------------------------
   ############################################################################################

   $prob = ""; // Detailed error string

   if ( eregi("gravy", $DEALYO) ) {
      $STEP = "GOODLIC";
      $msg = "<font class=\"text_msg\" style=\"color: #339959\">Domain licensed successfully!</font>\n";

//   	// Write Key File Local
//   	// -------------------------------------
//   	$filename = "soholaunch.lic";
//   	$file = fopen("$filename", "w");
//   		fwrite($file, "$KEY");
//   	fclose($file);
//   	sleep(1);	// Give Local Installations Time Enough to Set Key File
//
//   	// Write temporary product name file (killed on first login)
//   	// -----------------------------------------------------------
//   	$filename = "type.lic";
//   	$file = fopen("$filename", "w");
//   		fwrite($file, "::::$disProd:::::");
//   	fclose($file);
//   	sleep(1);	// Give Local Installations Time Enough to Set File

   } elseif ( eregi("badlogin", $DEALYO) ) {
      $STEP = "AUTOLIC";
      $msg = "<center><font class=\"text_msg\"><b>ERROR!</B> - the username and password you specified do not appear to be valid.</font></center><br>\n";
      $prob .= "partUser;partPass;";

   } elseif ( eregi("beendone", $DEALYO) ) {
      $STEP = "HASLIC";
      $msg = "<center><font class=\"text_msg\" style=\"color: #339959\">A license already exists for this domain!</font></center><br>\n";

   } elseif ( eregi("noserver", $DEALYO) ) {
      $STEP = "AUTOLIC";
      $msg = "<font class=\"text_msg\"><b>ERROR!</B> - Unable to locate server license for ".$SERVER_ADDR."!</font><br>\n";
      $msg = "<font class=\"text_msg\">Please make sure that you have selected the correct license type.</font><br>\n";
      $prob .= "disProd;";

   } // End if response recieved

}



if ($STEP == "TRY_MYSQL") {
   /*-----------------------------------------------------------------------------------------
    _                                  __             _
   (_)                                / _|           | |
    _ ___ _ __         ___ ___  _ __ | |_       _ __ | |__  _ __
   | / __| '_ \       / __/ _ \| '_ \|  _|     | '_ \| '_ \| '_ \
   | \__ \ |_) |  _  | (_| (_) | | | | |    _  | |_) | | | | |_) |
   |_|___/ .__/  (_)  \___\___/|_| |_|_|   (_) | .__/|_| |_| .__/
         | |                                   | |         | |
         |_|                                   |_|         |_|
   -----------------------------------------------------------------------------------------*/

	$err_check = 0;
	$noDice = "";
	foreach($_POST as $p1=>$p2){
		${$p1}=$p2;
	}
	reset($HTTP_POST_VARS);
	while (list($name, $value) = each($HTTP_POST_VARS)) {
		if ($value == "" && $name != "SETUP_HOSTOPS" && $name != "SETUP_AUTOLIC" && $name != "SETUP_ADVANCED") {
		   $err_check = 1;
		   $noDice .= "$name";
//		   echo "$name == ($value) nothing!<br>";
		}
	}


   //echo "nodice: ($noDice)";
   //exit;
	// Connect to database server and see what happens...
	// ----------------------------------------------------------

   # Connect to database
	if ( mysql_connect("$SETUP_DBSERVER", "$SETUP_DBUN","$SETUP_DBPW") ) {

	   # Select database
	   if ( mysql_select_db("$SETUP_DBNAME") ) {
		   $DBPASS = 1;
		}
	}


	if ($DBPASS != 1) {
		$err_check = 1;
		$noDice .= "SETUP_DB";
	}

	if ( $err_check == 0 ) {

		//$SETUP_CGI = stripslashes($SETUP_CGI);
		//$SETUP_ROOT = stripslashes($SETUP_ROOT);

		//$SETUP_DOMAIN = eregi_replace("http://www.", "", $SETUP_DOMAIN);
		$SETUP_DOMAIN = eregi_replace("http://", "", $SETUP_DOMAIN);


      // Write default data for host info and advanced options if none submitted
      // ================================================================================
      $df_hostco = 0;
      $df_advanced = 0;

      if ( $SETUP_HOSTOPS != "yes" ) {
         $df_hostco = 1;

         $SETUP_HOSTCO = "your hosting provider";
         $SETUP_HOSTPHONE = "555.555.5555";
         $SETUP_HOSTEMAIL = "sales@domain.com";
         $SETUP_MANLINK = "promanual.soholaunch.com";
         $SETUP_WPID = "71691";

      }


      $SETUP_ADVANCED = "no"; // Not enough working options yet to justify further convolution of setup routine

      if ( $SETUP_ADVANCED != "yes" ) {
         $df_advanced = 1;

   		// Build defaults for SETUP_ADVANCED data
   		// -------------------------------------------------------
         if ( $SETUP_DFUSER == "" ) { $SETUP_DFUSER = "admin"; }
         if ( $SETUP_DFPASS == "" ) { $SETUP_DFPASS = "admin"; }

         $SETUP_LANG = "english.php";
         $SETUP_MAPOBJ = "enabled";
         $SETUP_UNDERCON = "";

   		// Format language file directory path
   		// -------------------------------------------------------
		   $SETUP_LANGDIR = $SETUP_ROOT.DIRECTORY_SEPARATOR."sohoadmin".DIRECTORY_SEPARATOR."language";

   		// Format template file directory path
   		// -------------------------------------------------------
   		$SETUP_TEMPDIR = $SETUP_ROOT.DIRECTORY_SEPARATOR."sohoadmin".DIRECTORY_SEPARATOR."program".DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."site_templates".DIRECTORY_SEPARATOR."pages";

      }  // End if skipping SETUP_ADVANCED


		// Make Sure Windows IIS Directory Paths are correct
		// --------------------------------------------------------------------
		$SETUP_CGI = $SETUP_ROOT."".DIRECTORY_SEPARATOR."sohoadmin".DIRECTORY_SEPARATOR."tmp_content";


      #########################################################
      // Write ISP.CONF.PHP file and let's go!
      #########################################################

      $config_data = "<?php exit; ?>\n\n";
      $config_data .= "#########################################################\n";
      $config_data .= "### SOHOLAUNCH V4.8 CONFIGURATION SETUP\n";
      $config_data .= "#########################################################\n\n";

      // Server and Databse Info
      $config_data .= "this_ip=$SETUP_DOMAIN\n\n";
      $config_data .= "db_server=$SETUP_DBSERVER\n";
      $config_data .= "db_name=$SETUP_DBNAME\n";
      $config_data .= "db_un=$SETUP_DBUN\n";
      $config_data .= "db_pw=$SETUP_DBPW\n\n";
      
	$gta=explode(' ', microtime());
	$ultrakey =  md5($gta['1'].str_replace('0.', '', $gta['0']));
      
       $config_data .= "key=".$ultrakey."\n\n";
      
      $config_data .= "user_table=login\n";

      // Main Directories
      $config_data .= "cgi_bin=".str_replace(DIRECTORY_SEPARATOR.basename(__FILE__), "", __FILE__)."/tmp_content\n";
      $config_data .= "doc_root=$SETUP_ROOT\n\n";

      $df_advanced = 1; // Just to make sure, since it won't be an option for now

      if ( $df_advanced > 0 ) {
         $config_data .= "dflogin_user=$SETUP_DFUSER\n";
         $config_data .= "dflogin_pass=$SETUP_DFPASS\n\n\n";

         $config_data .= "lang_set=$SETUP_LANG\n";
         $config_data .= "lang_dir=$SETUP_LANGDIR\n\n";

         $config_data .= "template_lib=$SETUP_TEMPDIR\n"; // Remote template directory
         $config_data .= "undercon=$SETUP_UNDERCON\n\n"; // Custom 'under construction' page until first login

         $config_data .= "map_obj=$SETUP_MAPOBJ\n"; // Enable or Disable map object in PE (for intl clients)
         $config_data .= "df_template_cat=Neutral\n\n"; // Default template category (mainly for QS Wizard)

         /*
         $config_data .= "## DO NOT MODIFY BELOW THIS LINE\n";
         $config_data .= "version=Full\n";
         $config_data .= "com_key=SRVRSGLEINDIV789\n";
         $config_data .= "sales_plan=C\n\n";
         */

         $config_data .= "#########################################################\n\n";

      }

      if ( $df_hostco > 0 ) {

         $hostco_info = "<?php exit; ?>\n\n";
         $hostco_info .= "#########################################################\n";
         $hostco_info .= "### V4.7 HOST OPTIONS \n";
         $hostco_info .= "#########################################################\n\n";

         $hostco_info .= "hostco_name=$SETUP_HOSTCO\n";
         $hostco_info .= "hostco_phone=$SETUP_HOSTPHONE\n";
         $hostco_info .= "hostco_email=$SETUP_HOSTEMAIL\n";
         $hostco_info .= "users_man=$SETUP_MANLINK\n";
         $hostco_info .= "wpay_pid=$SETUP_WPID\n\n";

         $hostco_info .= "#########################################################\n\n";

//         // WRITE HOST.CONF.PHP
//         //==========================================================
//         $filename = "config/host.conf.php";
//         $file = fopen("$filename", "w");
//            if ( !fwrite($file, "$hostco_info") ) { $err_check = 1; $noDice .= "hostconf"; } // Show helpful advice on error (vs. no comment)
//         fclose($file);

      } // End if skipping SETUP_HOSTOPS


      // WRITE ISP.CONF.PHP
      //==========================================================
      $filename = "config/isp.conf.php";
      $file = fopen("$filename", "w");
         if ( !fwrite($file, "$config_data") ) { $err_check = 1; $noDice .= "ispconf"; } // Show helpful advice on error (vs. no comment)
      fclose($file);

      //header("Location: index.php");
      //exit;

	} // End trying to write isp.conf.php


   ## Any errors, or can we go ahead to the next step?
   ## -------------------------------------------------------------

   // Something aint right.
   // ---------------------------
   if ( $err_check != 0 ) {

      // Try to narrow down problem.
      if ( eregi("SETUP_DB", $noDice) ) {
         $msg = "<center><font class=\"text_msg\"><b>ERROR!</B> - Could not connect to MySQL database using the information you provided.<br>\n";
         $msg .= "Please check your settings and resubmit.</font></center><br>";

      } elseif ( eregi("ispconf", $noDice) ) {
         $msg = "<center><font class=\"text_msg\"><b>ERROR!</B> - Unable to write config file.<br>\n";
         $msg .= "Try setting permissions on the '/sohoadmin' directory to 777 (<font face=\"Courier New, Courier, mono\" color=\"#000000\">chmod -R a+rw sohoadmin</font>).\n";
         $msg .= "</font></center><br>";

      } else {
         $msg = "<center><font class=\"text_msg\"><b>ERROR!</B> - You have left one or more required fields blank.<br>\n";
         $msg .= "Please complete the fields and try again. (err_check - $err_check | noDice - $noDice)</font></center><br>";
      }
      $STEP = ""; // Kick back to setup form w/error msg

   // Everything checks out
   // ---------------------------
   } else {

      // Onward! To the enchanted realms of branding, advanced options and/or auto-licensing!
      if ( $SETUP_HOSTOPS == "yes" ) {
         $STEP = "HOSTOPS";

      //} elseif ( $SETUP_HOSTOPS != "yes" && $SETUP_ADVANCED == "yes" ) {
      //   $STEP = "ADVANCED";

      } elseif ( $SETUP_HOSTOPS != "yes" && $SETUP_ADVANCED != "yes" && $SETUP_AUTOLIC == "yes" ) {
         $STEP = "AUTOLIC";

      } else {
//         header("index.php");
			echo "<script language=\"javascript\"> \n";
		   echo "location.href = \"index.php\"; \n";
		   echo "</script> \n";
      }

   }

}

if ($STEP == "SAVE_HOSTOPS") {

   /*-----------------------------------------------------------------------------------------
    _               _                              __             _
   | |             | |                            / _|           | |
   | |__   ___  ___| |_ ___        ___ ___  _ __ | |_       _ __ | |__  _ __
   | '_ \ / _ \/ __| __/ __|      / __/ _ \| '_ \|  _|     | '_ \| '_ \| '_ \
   | | | | (_) \__ \ |_\__ \  _  | (_| (_) | | | | |    _  | |_) | | | | |_) |
   |_| |_|\___/|___/\__|___/ (_)  \___\___/|_| |_|_|   (_) | .__/|_| |_| .__/
                                                           | |         | |
                                                           |_|         |_|
   -----------------------------------------------------------------------------------------*/

	$err_check = 0;
	$noDice .= "";


   // Make sure all vars have vals
   // ======================================
	reset($HTTP_POST_VARS);
	while (list($name, $value) = each($HTTP_POST_VARS)) {
		if ($value == "" && $name != "SETUP_AUTOLIC" ) { $err_check = 1; $noDice .= "$name"; }
	}


   // Good to go. Now write host data to dedicated config file (so we can automate easily later)
   // ==================================================================================================
	if ( $err_check == 0 ) {


      ###########################################################################################
      // Write additional settings to HOST.CONF.PHP
      ###########################################################################################

      ## Later, customers could pull default info from server automatically, which would be hella-awesome.
      ## File data could be saved in either account table or server table (my vote == server)
      ## -- MM (08.30.2004)

      $hostco_info = "<?php exit; ?>\n\n";
      $hostco_info .= "#########################################################\n";
      $hostco_info .= "### V4.7 HOST OPTIONS \n";
      $hostco_info .= "#########################################################\n\n";

      $hostco_info .= "hostco_name=$SETUP_HOSTCO\n";
      $hostco_info .= "hostco_phone=$SETUP_HOSTPHONE\n";
      $hostco_info .= "hostco_email=$SETUP_HOSTEMAIL\n";
      $hostco_info .= "users_man=$SETUP_MANLINK\n";
      $hostco_info .= "wpay_pid=$SETUP_WPID\n\n";

      $hostco_info .= "#########################################################\n\n";


      // WRITE FILE!!
      // ======================================
      $filename = "config/host.conf.php";
      $file = fopen("$filename", "w");
         if ( !fwrite($file, "$hostco_info") ) { $err_check = 1; $noDice .= "hostconf"; } // Show helpful advice on error (vs. no comment)
      fclose($file);

	} // End trying if nothing blank


   ## Any errors, or can we go ahead to the next step?
   ## -------------------------------------------------------------

   // Something aint right.
   // ---------------------------
   if ( $err_check != 0 ) {

      // Try to narrow down problem.
      if ( eregi("SETUP_DB", $noDice) ) {
         $msg = "<center><font class=\"text_msg\"><b>ERROR!</B> - Could not connect to MySQL database using the information you provided.<br>\n";
         $msg .= "Please check your settings and resubmit.</font></center><br>";

      } elseif ( eregi("conf", $noDice) ) {
         $msg = "<center><font class=\"text_msg\"><b>ERROR!</B> - Unable to write config file.</center><br>\n";
         $msg .= "<br><b>Tip:</b> Try setting permissions on the '/sohoadmin' directory to 777. - ( <font face=\"Courier New, Courier, mono\" color=\"#7D7D7D\"><b>chmod -R a+rw sohoadmin</b></font> )\n";
         $msg .= "<br><b>Tip:</b> Try setting permissions on the '/sohoadmin/config' directory to 777. - ( <font face=\"Courier New, Courier, mono\" color=\"#7D7D7D\"><b>chmod -R a+rw sohoadmin</b></font> )\n";
         $msg .= "</font><br>";

      } else {
         $msg = "<center><font class=\"text_msg\"><b>ERROR!</B> - You have left one or more required fields blank.<br>\n";
         $msg .= "Please complete the fields and try again.</font></center><br>";
      }
      $STEP = "HOSTOPS"; // Kick back to setup form w/error msg

   // Everything checks out
   // ---------------------------
   } else {

      // Onward! To the enchanted realms auto-licensing!
      if ( $SETUP_ADVANCED != "yes" && $SETUP_AUTOLIC == "yes" ) {
         $STEP = "AUTOLIC";

      //} elseif ( $SETUP_ADVANCED == "yes" ) {
      //   $STEP = "ADVANCED";

      } else {
//         header("index.php");
			echo "<script language=\"javascript\"> \n";
		   echo "location.href = \"index.php\"; \n";
		   echo "</script> \n";
      }

   }

}


// Mark problem fields on error (called by all steps)
// ==========================================================
function isbad($string) {
   global $noDice;

   if ( eregi($string, $noDice) ) {
      $redify = " style=\"border: 1px solid #D70000;\"";
   } else {
      $redify = "";
   }

   return $redify;
}

if ( $STEP == "" ) {

   /*--------------------------------------------------------------------------------------
   ....##.................##.....##.##....##..######...#######..##.........................
   ..####.................###...###..##..##..##....##.##.....##.##.........................
   ....##.................####.####...####...##.......##.....##.##.........................
   ....##......#######....##.###.##....##.....######..##.....##.##.........................
   ....##.................##.....##....##..........##.##..##.##.##.........................
   ....##.................##.....##....##....##....##.##....##..##.........................
   ..######...............##.....##....##.....######...#####.##.########...................
   --------------------------------------------------------------------------------------*/

   ## =========================================================================================
   // Check Server Variables for setup configuration
   ## =========================================================================================

   reset($_SERVER);
   while (list($name, $value) = each($_SERVER)) {

   	$value = eregi_replace(";", "", $value);
   	$value = rtrim($value);
   	$value = ltrim($value);

   	// What Server Software is the SMT installed on?
   	// -----------------------------------------------
   	if(eregi("WIN", $OS)) {
   		$OSTYPE = "Windows";
   		$image = "win32.gif";
   	} else {
   		$OSTYPE = "Linux";
   		$image = "linux.gif";
   	}


   	// What is the absolute document root?
   	// -----------------------------------------------
   	if (eregi("DOCUMENT_ROOT", $name)) {
   		$DOC_ROOT = $value;
   	}

   	// What is the IP address of this server?
   	// -----------------------------------------------
   	if (eregi("SERVER_ADDR", $name)) {
   		$IP_ADDR = $value;
   	}

   	// What is the name of this host?
   	// -----------------------------------------------
   	if (eregi("SERVER_NAME", $name)) {
   		$HOST_NAME = $value;
   	}

   	// This Page is called ?
   	// -----------------------------------------------
   	if (eregi("PHP_SELF", $name)) {
   		$tmp = split("/", $value);
   		$tcount = count($tmp) - 1;
   		$THIS_SCRIPT = $tmp[$tcount];
   		$THIS_SCRIPT = 'setup.php';
   	}


   } // End While

   // Check the PHP.INI Configuration
   // ---------------------------------------------------
//   $stags = ini_get("short_open_tag");
//   $rglob = ini_get("register_globals");
//   $check_sum = $stags + $rglob;
//
//   if ($check_sum != 2) {
//
//   	echo "<FONT FACE=ARIAL><h3><U>PHP.INI Configuration Needed</U>:</h3>";
//   	echo "We have detected that register_globals is turned off within the<BR>";
//   	echo "PHP.INI file.  For the product to run properly please modify the<BR>";
//   	echo "line within the PHP.INI to show <b>\"register_globals on\"</B> and<BR>";
//   	echo "<b>\"short_open_tag on\"</B>. When done, restart your server.<BR>";
//   	echo "Once this is complete, restart this script to continue with setup.<BR><BR>";
//
//   	exit;
//
//   }

   // Make setup easier for Windows NT and IIS Server
   // --------------------------------------------------
   if ($OSTYPE == "Windows" && eregi("IIS", $SERVER_SOFTWARE)) {
   	$DOC_ROOT = stripslashes($PATH_TRANSLATED);
   	$DOC_ROOT = eregi_replace("sohoadmin(.*)setup.php", "", $DOC_ROOT); // Remove Current Script Name From Root
   	$tmp = strlen($DOC_ROOT);
   	$tmp_new = $tmp - 1;
   	$DOC_ROOT = substr($DOC_ROOT, 0, $tmp_new);
   }

   // Build language options
   // ---------------------------------------------------
   function langchk($langu) {
      $lang = strtolower($langu);
      $langDir = "language";
      $fileNam = $lang.".php";
      $file = $langDir."/".$fileNam;

      if (file_exists($file)) {
         return "<option value=\"".$fileNam."\">".$langu."</option>\n";
      } else {
         return "";
      }
   }
   $langOps = "";
   $langOps .= langchk("Chinese");
   $langOps .= langchk("English");
   $langOps .= langchk("French");
   $langOps .= langchk("Japanese");
   $langOps .= langchk("Korean");
   $langOps .= langchk("Norwegian");
   $langOps .= langchk("Russian");
   $langOps .= langchk("Spanish");
   $langOps .= langchk("Vietnamese");
?>
<script language=javascript>
function camcheckrequired(theform) {
	var returnval=true; //by default, allow form submission
	var formElly=document.getElementById('SETUP_IP');
	formElly.style.border = '';
	if(formElly.value==''){
		alert("Please select enter your IP address.") //alert error message
		formElly.style.border = '1px solid red';
		formElly.focus();
		returnval=false; //disallow form submission
	}
	var formElly=document.getElementById('SETUP_DOMAIN');
	formElly.style.border = '';
	if(formElly.value=='' && returnval != false){
		alert("Please enter your domain name.") //alert error message
		formElly.style.border = '1px solid red';
		formElly.focus();
		returnval=false; //disallow form submission
	}
	var formElly=document.getElementById('SETUP_ROOT');
	formElly.style.border = '';
	if(formElly.value=='' && returnval != false){
		alert("Please enter your Document Root Path") //alert error message
		formElly.style.border = '1px solid red';
		formElly.focus();
		returnval=false; //disallow form submission
	}
	var formElly=document.getElementById('SETUP_DBSERVER');
	formElly.style.border = '';
	if(formElly.value=='' && returnval != false){
		alert("Please enter your mysql Server Location.") //alert error message
		formElly.style.border = '1px solid red';
		formElly.focus();
		returnval=false; //disallow form submission
	}
	var formElly=document.getElementById('SETUP_DBNAME');
	formElly.style.border = '';
	if(formElly.value=='' && returnval != false){
		alert("Please enter your MYSQL Database Name.") //alert error message
		formElly.style.border = '1px solid red';
		formElly.focus();
		returnval=false; //disallow form submission
	}
	var formElly=document.getElementById('SETUP_DBUN');
	formElly.style.border = '';
	if(formElly.value=='' && returnval != false){
		alert("Please enter your MYSQL Username.") //alert error message
		formElly.style.border = '1px solid red';
		formElly.focus();
		returnval=false; //disallow form submission
	}
	var formElly=document.getElementById('SETUP_DBPW');
	formElly.style.border = '';
	if(formElly.value=='' && returnval != false){
		alert("Please enter your MYSQL Database Password.") //alert error message
		formElly.style.border = '1px solid red';
		formElly.focus();
		returnval=false; //disallow form submission
	}
	var emailaddrezz = document.getElementById('SETUP_DFUSER');
	var str = document.getElementById('SETUP_DFUSER').value;
	var at="@"
	var dot="."
	var comma=","
	var lat=str.indexOf(at)
	var lstr=str.length
	var ldot=str.indexOf(dot)
	if (str.indexOf(at)==-1 && returnval != false){
		alert("Invalid E-mail Address")
		emailaddrezz.style.border = '1px solid red';
		emailaddrezz.focus();
		returnval=false //disallow form submission
		return false; 
	}
	if (str.indexOf(comma)>0 && returnval != false){
		alert("Invalid E-mail Address")
		emailaddrezz.style.border = '1px solid red';
		emailaddrezz.focus();
		returnval=false //disallow form submission
		return false; 
	}
	if ((str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr) && returnval != false){
 		alert("Invalid E-mail Address")
		emailaddrezz.style.border = '1px solid red';
		emailaddrezz.focus();
		returnval=false; //disallow form submission
		return false; 
		}
	if ((str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr) && returnval != false){
  		alert("Invalid E-mail Address")
		emailaddrezz.style.border = '1px solid red';
		emailaddrezz.focus();
		returnval=false; //disallow form submission
		return false; 
	}
	if (str.indexOf(at,(lat+1))!=-1 && returnval != false){
  		alert("Invalid E-mail Address")
		emailaddrezz.style.border = '1px solid red';
		emailaddrezz.focus();
		returnval=false; //disallow form submission
		return false; 
	}
	if ((str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot) && returnval != false){
  		alert("Invalid E-mail Address")
		emailaddrezz.style.border = '1px solid red';
		emailaddrezz.focus();
		returnval=false; //disallow form submission
		return false; 
	}
	if (str.indexOf(dot,(lat+2))==-1 && returnval != false){
  		alert("Invalid E-mail Address")
		emailaddrezz.style.border = '1px solid red';
		emailaddrezz.focus();
		returnval=false; //disallow form submission
		return false; 
	}
	if (str.indexOf(" ")!=-1 && returnval != false){
  		alert("Invalid E-mail Address")
		emailaddrezz.style.border = '1px solid red';;
		emailaddrezz.focus();
		returnval=false; //disallow form submission
		return false; 
	}
	var formElly=document.getElementById('SETUP_DFPASS');
	formElly.style.border = '';
	if(formElly.value=='' && returnval != false){
		alert("Please select a password.") //alert error message
		formElly.style.border = '1px solid red';
		formElly.focus();
		returnval=false; //disallow form submission
	}
	var formElly=document.getElementById('first_name');
	formElly.style.border = '';
	if(formElly.value=='' && returnval != false){
		alert("Please enter your first name.") //alert error message
		formElly.style.border = '1px solid red';
		formElly.focus();
		returnval=false; //disallow form submission
	}
	var formElly=document.getElementById('last_name');
	formElly.style.border = '';
	if(formElly.value=='' && returnval != false){
		alert("Please enter your last name.") //alert error message
		formElly.style.border = '1px solid red';
		formElly.focus();
		returnval=false; //disallow form submission
	}
	return returnval;
}
</script>
<?php


   ############################################################################################
   ## =========================================================================================
   // BEGIN FORM: Initial Setup and Configuration
   ## =========================================================================================
   ############################################################################################

   ## Title and description
   ## ===========================================================================
   $disForm = "      <font class=\"subhdr\"><font color=\"#980000\"><b>STEP 1</font>: Initial Setup and Configuration</b><br>\n";
   $disForm .= "      <font color=\"#565656\">Once the application has successfully connected to the mySQL database for this site, it will create a configuration \n";
   $disForm .= "      file that will allow the application to operate from this website. This \n";
   $disForm .= "      is a one-time setup operation and will not be required again.</font></FONT>\n";
   $disForm .= "      <BR>\n";
   $disForm .= "      <BR>\n";
   $disForm .= "      ".$msg."\n";

   ## Begin initial setup form
   ## ===========================================================================
   
   $disForm .= "      <FORM METHOD=POST ACTION=\"".$THIS_SCRIPT."\" onSubmit=\"return camcheckrequired(this);\">\n";
   $disForm .= "      <INPUT TYPE=HIDDEN NAME=STEP VALUE=\"TRY_MYSQL\">\n";
   $disForm .= "      <INPUT TYPE=HIDDEN NAME=OSTYPE VALUE=\"".$OSTYPE."\">\n";
   $disForm .= "      <TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"5\" BGCOLOR=\"#F8F9FD\" STYLE='border: 1px solid black;'>\n";
   $disForm .= "       <TR> \n";

   // Web Server Information
   // ===========================================================================
   $disForm .= "        <TD ALIGN=\"LEFT\" VALIGN=\"TOP\" CLASS=\"text\">\n";
   $disForm .= "         <B><u>Web Server Information</u>:</B><BR>\n";
   $disForm .= "         <BR>\n";

   // Server IP Address - SETUP_IP
   // -----------------------------------------------------
   if ( $_SERVER['SERVER_ADDR'] != "" ) {
      $sIP = $_SERVER['SERVER_ADDR'];
   } else {
      $sIP = gethostbyname($_SERVER['HTTP_HOST']);
   }

   $disForm .= "         Server IP Address:<BR> \n";
   $disForm .= "         <INPUT TYPE=TEXT NAME=\"SETUP_IP\" id=\"SETUP_IP\" VALUE=\"".$sIP."\" class=\"tfield\">\n";
   $disForm .= "         <BR>\n";
   $disForm .= "         <BR>\n";

   // Type the URL used to access this website's homepage: - SETUP_DOMAIN
   // ------------------------------------------------------------------------
   $disForm .= "         Type the URL used to access this website's homepage:<BR> \n";
   $domainurl = eregi_replace('\/sohoadmin\/setup\.php', '', $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
   $disForm .= "         <INPUT TYPE=TEXT NAME=\"SETUP_DOMAIN\" id=\"SETUP_DOMAIN\" value=\"http://".$domainurl."\" class=\"tfield\"".isbad("SETUP_DOMAIN").">\n";
   $disForm .= "         <BR>\n";
   $disForm .= "         <BR>\n";

   // Full HTML Document Root Path - SETUP_ROOT
   // -----------------------------------------------------

   # Construct path by working backwards from this file
   $clipknown = DIRECTORY_SEPARATOR."sohoadmin".DIRECTORY_SEPARATOR.basename(__FILE__);
   $DOC_PATH = str_replace($clipknown, "", __FILE__);
   $disForm .= "         Full HTML Document Root Path: <BR>\n";
   $disForm .= "         <INPUT TYPE=TEXT NAME=\"SETUP_ROOT\" id=\"SETUP_ROOT\" VALUE=\"".$DOC_PATH."\" class=\"tfield\"".isbad("SETUP_ROOT")."> \n";
   $disForm .= "         <BR><BR> \n";

   // Default End-User Login
   // -----------------------------------------------------
   $disForm .= "         Default Administrator Login (i.e., to manage website): <BR>\n";
   $disForm .= "         <table border=\"0\" cellpadding=\"3\" cellspacing=\"0\">\n";




   // Username - SETUP_DFUSER
   // ========================================================
   $disForm .= "          <tr>\n";
   $disForm .= "           <td style=\"color:#D70000;\" class=\"text\">\n";
   $disForm .= "            Email:<br>\n";
   $disForm .= "            <input type=\"text\" name=\"SETUP_DFUSER\" id=\"SETUP_DFUSER\" value=\"".$_POST['SETUP_DFUSER']."\" class=\"tfield\" style=\"width: 145px;\">\n";
   $disForm .= "           </td>\n";

   $disForm .= "           <td width=\"25\">&nbsp;</td>\n";

   // Password - SETUP_DFPASS
   // ========================================================
   $disForm .= "           <td style=\"color:#D70000;\" class=\"text\">\n";
   $disForm .= "            Password:<br>\n";
   $disForm .= "            <input type=\"text\" id=\"SETUP_DFPASS\" name=\"SETUP_DFPASS\" value=\"".$_POST['SETUP_DFPASS']."\" class=\"tfield\" style=\"width: 145px;\">\n";
   $disForm .= "           </td>\n";

   $disForm .= "          </tr>\n";
   
 
   $disForm .= "          <tr>\n";
   $disForm .= "           <td style=\"color:#D70000;\" class=\"text\">\n";
   $disForm .= "            First Name:<br>\n";
   $disForm .= "            <input type=\"text\" name=\"first_name\" id=\"first_name\" value=\"".$_POST['first_name']."\" class=\"tfield\" style=\" width: 145px;\">\n";
   $disForm .= "           </td>\n";
   $disForm .= "           <td width=\"25\">&nbsp;</td>\n";
   $disForm .= "           <td style=\"color:#D70000;\" class=\"text\">\n";
   $disForm .= "            Last Name:<br>\n";
   $disForm .= "            <input type=\"text\" name=\"last_name\" id=\"last_name\" value=\"".$_POST['last_name']."\" class=\"tfield\" style=\" width: 145px;\">\n";
   $disForm .= "           </td>\n";

   $disForm .= "          </tr>\n";
   
   
   $disForm .= "         </table>\n";
   $disForm .= "         <BR><BR> \n";
   $disForm .= "        </TD>\n";


   // MySQL Database Information
   // ===========================================================================
   $disForm .= "        <TD ALIGN=\"LEFT\" VALIGN=\"TOP\" CLASS=\"text\">\n";
   $disForm .= "         <B><u>MySQL Database Information</u>:</B><BR> \n";
   $disForm .= "         <BR>\n";

if($_POST['SETUP_DBSERVER']==''){
	$SETUP_DBSERVER = 'localhost';
} else {
	$SETUP_DBSERVER = $_POST['SETUP_DBSERVER'];
}
   // dB Server - SETUP_DBSERVER
   // -----------------------------------------------------
   $disForm .= "         Server Location:<BR> <INPUT TYPE=TEXT NAME=\"SETUP_DBSERVER\" id=\"SETUP_DBSERVER\" value=\"".$SETUP_DBSERVER."\" class=\"tfield\"> \n";
   $disForm .= "         <BR><BR>\n";

   // dB Name - SETUP_DBNAME
   // -----------------------------------------------------
   $disForm .= "         <font class=\"text_msg\">Database Name</font>:<BR> <INPUT TYPE=TEXT NAME=\"SETUP_DBNAME\" id=\"SETUP_DBNAME\" VALUE=\"".$SETUP_DBNAME."\" class=\"tfield\"".isbad("SETUP_DB").">\n";
   $disForm .= "         <BR><BR>\n";

   // dB Username - SETUP_DBUN
   // -----------------------------------------------------
   $disForm .= "         <font class=\"text_msg\">Database Username</font>:<BR> <INPUT TYPE=TEXT NAME=\"SETUP_DBUN\" id=\"SETUP_DBUN\" VALUE=\"".$SETUP_DBUN."\" class=\"tfield\"".isbad("SETUP_DB").">\n";
   $disForm .= "         <BR><BR>\n";

   // dB Password - SETUP_DBPW
   // -----------------------------------------------------
   $disForm .= "         <font class=\"text_msg\">Database Password</font>:<BR> <INPUT TYPE=TEXT NAME=\"SETUP_DBPW\" id=\"SETUP_DBPW\" VALUE=\"".$SETUP_DBPW."\" class=\"tfield\"".isbad("SETUP_DB").">\n";
   $disForm .= "         <BR><BR>\n";
   $disForm .= "        </td>\n";
   $disForm .= "       </TR>\n";




   $disForm .= "       <tr>\n";
   $disForm .= "        <td align=\"left\" valign=\"top\" class=\"optional\" colspan=\"2\">\n";
   $disForm .= "         <table width=\"100%\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">\n";



//   // Step 2. Web Host Branding Options - SETUP_HOSTOPS
//   // ===========================================================================
//   $disForm .= "          <tr>\n";
//   $disForm .= "           <td width=\"28%\" align=\"left\" class=\"optional\" style=\"padding-bottom: 2px;\">\n";
//   $disForm .= "            <u>Web Host Branding Options</u>: \n";
//   $disForm .= "           </td>\n";
//   $disForm .= "           <td width=\"15%\" align=\"left\" class=\"optional\" style=\"padding-bottom: 0px;\">\n";
//   $disForm .= "            <input type=\"checkbox\" name=\"SETUP_HOSTOPS\" value=\"yes\">\n";
//   $disForm .= "           </td>\n";
//   $disForm .= "           <td width=\"57%\" style=\"padding-bottom: 0px;\">&nbsp;</td>\n";
//   $disForm .= "          </tr>\n";
//   $disForm .= "          <tr>\n";
//   $disForm .= "           <td align=\"left\" valign=\"top\" colspan=\"3\" class=\"optional\" style=\"padding: 0px 3px 15px 3px;\">\n";
//   $disForm .= "            Customize various aspects of the product interface to reflect your coporate branding.\n";
//   $disForm .= "            Factory-set 'white-label' content will be substituted automatically if this information is ommited.";
//   $disForm .= "           </td>\n";
//   $disForm .= "          </tr>\n";

   // Step 3. Advanced and International Options  - SETUP_ADVANCED
   /* ===========================================================================
   $disForm .= "          <tr>\n";
   $disForm .= "           <td align=\"left\" class=\"optional\" style=\"padding-bottom: 2px;\">\n";
   $disForm .= "            <u>Advanced/International Options</u>: \n";
   $disForm .= "           </td>\n";
   $disForm .= "           <td align=\"left\" class=\"optional\" style=\"padding-bottom: 0px;\">\n";
   $disForm .= "            <input type=\"checkbox\" name=\"SETUP_ADVANCED\" value=\"yes\">\n";
   $disForm .= "           </td>\n";
   $disForm .= "           <td style=\"padding-bottom: 0px;\">&nbsp;</td>\n";
   $disForm .= "          </tr>\n";
   $disForm .= "          <tr>\n";
   $disForm .= "           <td align=\"left\" valign=\"top\" colspan=\"3\" class=\"optional\" style=\"padding: 0px 3px 15px 3px;\">\n";
   $disForm .= "            Intended to allow experienced international users to maintain\n";
   $disForm .= "            the server directory structures and technical deployment procedures they have already designed.\n";
   $disForm .= "            Many of these options affect areas essential to proper operation, so when it doubt, skip this step and stick\n";
   $disForm .= "            the default settings.";
   $disForm .= "           </td>\n";
   $disForm .= "          </tr>\n";
   /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

   // End option table, parent td, and parent tr
   // ===========================================================================
   $disForm .= "         </table>\n";
   $disForm .= "        </td>\n";
   $disForm .= "       </tr>\n";

   // SUBMIT BUTTON
   // ===========================================================================
   $disForm .= "       <tr align=\"center\"> \n";
   $disForm .= "        <td colspan=\"2\">\n";
   $disForm .= "         <br><input type=\"submit\" name=\"Submit\" value=\" Continue \" class=\"text\" style='cursor: hand;'>\n";
   $disForm .= "        </td>\n";
   $disForm .= "       </tr>\n";
   $disForm .= "      </table>\n";
   $disForm .= "      </form>\n";


} elseif ( $STEP == "HOSTOPS" ) {

   /*----------------------------------------------------------------------------------------------------------
   .##.....##..#######...######..########..#######..########...######..........................................
   .##.....##.##.....##.##....##....##....##.....##.##.....##.##....##.........................................
   .##.....##.##.....##.##..........##....##.....##.##.....##.##...............................................
   .#########.##.....##..######.....##....##.....##.########...######..........................................
   .##.....##.##.....##.......##....##....##.....##.##..............##.........................................
   .##.....##.##.....##.##....##....##....##.....##.##........##....##.........................................
   .##.....##..#######...######.....##.....#######..##.........######..........................................
   ----------------------------------------------------------------------------------------------------------*/

   ## Title and description
   ## ===========================================================================
   $disForm = "      <font class=\"subhdr\"><font color=\"#980000\"><b>STEP 2</font>: Additional Web Host Options</b><br>\n";
   $disForm .= "      <font color=\"#565656\">Customize various aspects of the product interface to reflect your coporate branding. \n";
   $disForm .= "      Factory-set 'white-label' content will be substituted automatically if this information is ommited.";
   $disForm .= "      </font></FONT>\n";
   $disForm .= "      <BR>\n";
   $disForm .= "      <BR>\n";
   $disForm .= "      ".$msg."\n";

   ## Begin initial setup form
   ## ===========================================================================
   $disForm .= "      <FORM METHOD=POST ACTION=\"".$THIS_SCRIPT."\">\n";
   $disForm .= "      <INPUT TYPE=HIDDEN NAME=STEP VALUE=\"SAVE_HOSTOPS\">\n";
   $disForm .= "      <INPUT TYPE=HIDDEN NAME=\"SETUP_ROOT\" VALUE=\"".$SETUP_ROOT."\">\n";
   $disForm .= "      <INPUT TYPE=HIDDEN NAME=\"SETUP_AUTOLIC\" VALUE=\"".$SETUP_AUTOLIC."\">\n";
   //$disForm .= "      <INPUT TYPE=HIDDEN NAME=\"SETUP_ADVANCED\" VALUE=\"".$SETUP_ADVANCED."\">\n";
   $disForm .= "      <INPUT TYPE=HIDDEN NAME=OSTYPE VALUE=\"".$OSTYPE."\">\n";
   $disForm .= "      <TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"5\" BGCOLOR=\"#F8F9FD\" STYLE='border: 1px solid black;'>\n";

   $disForm .= "       <tr>\n";
   $disForm .= "        <td align=\"left\" valign=\"top\" colspan=\"2\" class=\"text\">\n";
   $disForm .= "         <b><u>Additional Web Host Options (not required):</u></b><br>\n";
   $disForm .= "        </td>\n";
   $disForm .= "       </tr>\n";

   $disForm .= "       <tr>\n";

   // Company Name - SETUP_HOSTCO
   // -----------------------------------------------------
   $disForm .= "        <td colspan=\"2\" align=\"left\" valign=\"top\" class=\"text\">\n";
   $disForm .= "         To purchase upgrades, please contact...<br>\n";
   $disForm .= "         <INPUT TYPE=TEXT NAME=\"SETUP_HOSTCO\" VALUE=\"your hosting provider\" class=\"tfield\" style=\"color: gray;\">\n";
   $disForm .= "        </td>\n";
   $disForm .= "       </tr>\n";
   $disForm .= "       <tr>\n";

   // Sales Phone Number - SETUP_HOSTPHONE
   // -----------------------------------------------------
   $disForm .= "        <td align=\"left\" valign=\"top\" class=\"text\">\n";
   $disForm .= "         Sales Phone Number:<br>\n";
   $disForm .= "         <INPUT TYPE=TEXT NAME=\"SETUP_HOSTPHONE\" VALUE=\"555.555.5555\" class=\"tfield\" style=\"color: gray;\">\n";
   $disForm .= "        </td>\n";

   // Sales Email Address - SETUP_HOSTEMAIL
   // -----------------------------------------------------
   $disForm .= "        <td align=\"left\" valign=\"top\" class=\"text\">\n";
   $disForm .= "         Sales Email Address:<br>\n";
   $disForm .= "         <INPUT TYPE=TEXT NAME=\"SETUP_HOSTEMAIL\" VALUE=\"sales@domain.com\" class=\"tfield\" style=\"color: gray;\">\n";
   $disForm .= "        </td>\n";
   $disForm .= "       </tr>\n";
   $disForm .= "       <tr>\n";

   // Users Manual Link - SETUP_MANLINK
   // -----------------------------------------------------
   $disForm .= "        <td align=\"left\" valign=\"top\" class=\"text\">\n";
   $disForm .= "         Users Manual Link:<br>\n";
   $disForm .= "         <INPUT TYPE=TEXT NAME=\"SETUP_MANLINK\" VALUE=\"http://promanual.soholaunch.com\" class=\"tfield\" style=\"color: gray;\">\n";
   $disForm .= "        </td>\n";

   // WorldPay Partner ID - SETUP_WPID
   // -----------------------------------------------------
   $disForm .= "        <td align=\"left\" valign=\"top\" class=\"text\">\n";
   $disForm .= "         WorldPay Partner ID:<br>\n";
   $disForm .= "         <INPUT TYPE=TEXT NAME=\"SETUP_WPID\" VALUE=\"71691\" class=\"tfield\" style=\"color: gray;\">\n";
   $disForm .= "        </td>\n";

   $disForm .= "       </tr>\n";

   // SUBMIT BUTTON
   // -----------------------------------------------------
   $disForm .= "       <TR ALIGN=\"CENTER\" VALIGN=\"TOP\"> \n";
   $disForm .= "        <TD COLSPAN=\"2\">\n";
   $disForm .= "         <br><INPUT TYPE=\"submit\" NAME=\"Submit\" VALUE=\" Continue \" CLASS=\"text\" STYLE='CURSOR: HAND;'>\n";
   $disForm .= "        </TD>\n";
   $disForm .= "       </TR>\n";
   $disForm .= "      </TABLE>\n";
   $disForm .= "      </FORM>\n";


} elseif ( $STEP == "ADVANCED" ) {

   /*----------------------------------------------------------------------------------------------------------
      ###    ########  ##     ##    ###    ##    ##  ######  ######## ########
     ## ##   ##     ## ##     ##   ## ##   ###   ## ##    ## ##       ##     ##
    ##   ##  ##     ## ##     ##  ##   ##  ####  ## ##       ##       ##     ##
   ##     ## ##     ## ##     ## ##     ## ## ## ## ##       ######   ##     ##
   ######### ##     ##  ##   ##  ######### ##  #### ##       ##       ##     ##
   ##     ## ##     ##   ## ##   ##     ## ##   ### ##    ## ##       ##     ##
   ##     ## ########     ###    ##     ## ##    ##  ######  ######## ########
   ----------------------------------------------------------------------------------------------------------*/

   ## Title and description
   ## ===========================================================================
   $disForm = "      <font class=\"subhdr\"><font color=\"#980000\"><b>STEP 3</font>: Advanced/International Options</b><br>\n";
   $disForm .= "      <font color=\"#565656\">Intended to allow experienced international users to maintain\n";
   $disForm .= "      the server directory structures and technical deployment procedures they have already designed.\n";
   $disForm .= "      Many of these options affect areas essential to proper operation, so when it doubt, skip this step and stick\n";
   $disForm .= "      the default settings.";
   $disForm .= "      </font></FONT>\n";
   $disForm .= "      <BR>\n";
   $disForm .= "      <BR>\n";
   $disForm .= "      ".$msg."\n";

   ## Begin inner form table
   ## ===========================================================================
   $disForm .= "      <FORM METHOD=POST ACTION=\"".$THIS_SCRIPT."\">\n";
   $disForm .= "      <INPUT TYPE=HIDDEN NAME=STEP VALUE=\"SAVE_ADVANCED\">\n";
   $disForm .= "      <INPUT TYPE=HIDDEN NAME=\"SETUP_ROOT\" VALUE=\"".$SETUP_ROOT."\">\n";
   $disForm .= "      <INPUT TYPE=HIDDEN NAME=\"SETUP_AUTOLIC\" VALUE=\"".$SETUP_AUTOLIC."\">\n"; // Line #490
   $disForm .= "      <INPUT TYPE=HIDDEN NAME=OSTYPE VALUE=\"".$OSTYPE."\">\n";
   $disForm .= "      <TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"5\" BGCOLOR=\"#F8F9FD\" STYLE='border: 1px solid black;'>\n";

   ## International Options
   ## ===========================================================================
   $disForm .= "       <tr>\n";
   $disForm .= "        <td align=\"left\" valign=\"top\" colspan=\"2\" class=\"text\">\n";
   $disForm .= "         <b><u>International Options:</u></b><br>\n";
   $disForm .= "        </td>\n";
   $disForm .= "       </tr>\n";

   // Remote Language File Directory - SETUP_LANGDIR
   // =====================================================
   $disForm .= "       <tr>\n";
   $disForm .= "        <td colspan=\"2\" align=\"left\" valign=\"top\" class=\"text\">\n";
   $disForm .= "         Language Files Directory:<br>\n";
   $disForm .= "         <INPUT TYPE=TEXT NAME=\"SETUP_LANGDIR\" VALUE=\"".$SETUP_ROOT."/sohoadmin/language\" class=\"tfield_dir\">\n";
   $disForm .= "        </td>\n";
   $disForm .= "       </tr>\n";

   $disForm .= "       <tr>\n";

   // Default Language - SETUP_LANG
   // =====================================================
   $disForm .= "        <td align=\"left\" valign=\"top\" class=\"text\">\n";
   $disForm .= "         Default Language:<br>\n";
   $disForm .= "         <select name=\"SETUP_LANG\" class=\"tfield\" style=\"color: gray; border: 1px solid #000000;\">\n";
   $disForm .= "          <option value=\"english.php\">English</option>\n";
   $disForm .= "          ".$langOps."\n";
   $disForm .= "         </select>\n";
   $disForm .= "        </td>\n";

   // Directions object (Page Editor): - SETUP_MAPOBJ
   // =====================================================
   $disForm .= "        <td align=\"left\" valign=\"top\" class=\"text\">\n";
   $disForm .= "         Directions object (Page Editor):<br>\n";
   $disForm .= "         <select name=\"SETUP_MAPOBJ\" class=\"tfield\" style=\"color: gray; border: 1px solid #000000; width: 90px;\">\n";
   $disForm .= "          <option value=\"enabled\" selected>Enabled</option>\n";
   $disForm .= "          <option value=\"disabled\">Disabled</option>\n";
   $disForm .= "         </select>\n";
   $disForm .= "        </td>\n";
   $disForm .= "       </tr>\n";

   ## Miscellaneous Settings
   ## ===========================================================================
   $disForm .= "       <tr>\n";
   $disForm .= "        <td align=\"left\" valign=\"top\" colspan=\"2\" class=\"text\">\n";
   $disForm .= "         <b><u>Miscellaneous Settings:</u></b><br>\n";
   $disForm .= "        </td>\n";
   $disForm .= "       </tr>\n";

   // Remote Template Library - SETUP_TEMPDIR
   /* =====================================================*
   $disForm .= "       <tr>\n";
   $disForm .= "        <td colspan=\"2\" align=\"left\" valign=\"top\" class=\"text\">\n";
   $disForm .= "         Template Library Directory:<br>\n";

   $facTemps = "/sohoadmin/program/modules/site_templates/pages"; // Factory default dir

   $disForm .= "         <input type=text name=\"SETUP_TEMPDIR\" value=\"".$SETUP_ROOT.$facTemps."\" class=\"tfield_dir\">\n";
   $disForm .= "        </td>\n";
   $disForm .= "       </tr>\n";
   /*~~~~~~~~~~*/

   // Under Construction Page - SETUP_UNDERCON
   // =====================================================
   $disForm .= "       <tr>\n";
   $disForm .= "        <td colspan=\"2\" align=\"left\" valign=\"top\" class=\"text\">\n";
   $disForm .= "         Custom 'Under Construction' Include:<br>\n";
   $disForm .= "         <input type=text name=\"SETUP_UNDERCON\" value=\"\" class=\"tfield\" style=\"color: gray;\">\n";
   $disForm .= "        </td>\n";
   $disForm .= "       </tr>\n";


   ## SPACER ROW
   ## ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
   $disForm .= "       <tr>\n";
   $disForm .= "        <td align=\"left\" valign=\"top\" colspan=\"2\" class=\"text\">\n";
   $disForm .= "         &nbsp;\n";
   $disForm .= "        </td>\n";
   $disForm .= "       </tr>\n";


   ## Default End-User Login
   ## ===========================================================================
   $disForm .= "       <tr>\n";
   $disForm .= "        <td align=\"left\" valign=\"top\" colspan=\"2\" class=\"text\">\n";
   $disForm .= "         <b><u>Default End-User Login:</u></b><br>\n";
   $disForm .= "        </td>\n";
   $disForm .= "       </tr>\n";

   ## Username/Password
   ## ---------------------------------------------------------------------------
   $disForm .= "       <tr>\n";
   $disForm .= "        <td colspan=\"1\" align=\"left\" valign=\"top\" class=\"text\">\n";
   $disForm .= "         <table border=\"0\" cellpadding=\"3\" cellspacing=\"0\">\n";

   // Username - SETUP_DFUSER
   // ========================================================
   $disForm .= "          <tr>\n";
   $disForm .= "           <td class=\"text\"><font class=\"optional\">Username:</font></td>\n";
   $disForm .= "           <td class=\"text\"><input type=\"text\" id=\"SETUP_DFUSER\" name=\"SETUP_DFUSER\" value=\"".$_POST['SETUP_DFUSER']."\" class=\"tfield\" style=\"color: #D70000; width: 125px;\"></td>\n";
   $disForm .= "          </tr>\n";

   // Password - SETUP_DFPASS
   // ========================================================
   $disForm .= "          <tr>\n";
   $disForm .= "           <td class=\"text\"><font class=\"optional\">Password:</font></td>\n";
   $disForm .= "           <td class=\"text\"><input type=\"text\" id=\"SETUP_DFPASS\" name=\"SETUP_DFPASS\" value=\"".$_POST['SETUP_DFPASS']."\" class=\"tfield\" style=\"color: #D70000; width: 125px;\"></td>\n";
   $disForm .= "          </tr>\n";

   $disForm .= "         </table>\n";
   $disForm .= "        </td>\n";
   $disForm .= "       </tr>\n";


   // SUBMIT BUTTON
   // -----------------------------------------------------
   $disForm .= "       <tr> \n";
   $disForm .= "        <td colspan=\"2\" align=\"center\">\n";
   $disForm .= "         <br><input type=\"submit\" name=\"submit\" value=\" Continue \" class=\"text\" style='cursor: hand;'>\n";
   $disForm .= "        </td>\n";
   $disForm .= "       </tr>\n";

   $disForm .= "      </table>\n";
   $disForm .= "      </form>\n";


} elseif ( $STEP == "AUTOLIC" ) {

   /*-----------------------------------------------------------------------------------------------------
   ....###....##.....##.########..#######..##.......####..######..........................................
   ...##.##...##.....##....##....##.....##.##........##..##....##.........................................
   ..##...##..##.....##....##....##.....##.##........##..##...............................................
   .##.....##.##.....##....##....##.....##.##........##..##...............................................
   .#########.##.....##....##....##.....##.##........##..##...............................................
   .##.....##.##.....##....##....##.....##.##........##..##....##.........................................
   .##.....##..#######.....##.....#######..########.####..######..........................................
   -----------------------------------------------------------------------------------------------------*/

   ## Highlight problem fields on error
   ## ===========================================================
   function chkfor($fld, $typ) {
      global $prob;
      $mark = ""; // Will contain error style

      // Check error string for this field name
      // ---------------------------------------
      if ( eregi($fld, $prob) ) {
         if ( $typ == "txt" ) {
            $mark = " border: 1px solid #D70000;"; // Red border if text field
         } elseif ( $typ == "dd") {
            $mark = " color: #D70000;"; // Red text if select box
         }
      }
      return $mark;
   }

   ## Title and description
   ## =========================================
   $disForm = "      <font class=\"subhdr\"><font color=\"#980000\"><b>STEP 3</font>: Domain and Feature Licensing</b><br>\n";
   $disForm .= "      <font color=\"#565656\">\n";
   $disForm .= "      After defining the availability of each feature upgrade, a license key is generated for this website as well as for any enabled features.\n";
   $disForm .= "      If you've already generated a license for this site via your Partner Login, that key file will be downloaded automatically. \n";
   $disForm .= "      Otherwise, a new domain will be added to your account, and will be available to manage through your Partner Login.</font></font>\n";
   $disForm .= "      <BR>\n";
   $disForm .= "      <BR>\n";

   $disForm .= "      ".$msg."\n";

   ## Begin initial setup form
   ## ===========================================================================
   $disForm .= "      <form method=\"post\" action=\"http://securexfer.net/product_reports/pro-auto_lic-ultra.php\">\n";
   $disForm .= "      <input type=\"hidden\" name=STEP value=\"LIC_THIS\">\n";
   $disForm .= "      <input type=\"hidden\" name=OSTYPE value=\"".$OSTYPE."\">\n";
   $disForm .= "      <table width=\"100%\" border=\"0\" cellpadding=\"5\" bgcolor=\"#F8F9FD\" style='border: 1px solid black;'>\n";

   // Soholaunch Partner Login | License Information
   // ===========================================================================
   $disForm .= "       <tr>\n";
   $disForm .= "        <td colspan=\"2\" align=\"left\" valign=\"top\" class=\"text\">\n";
   $disForm .= "         <B><u>Soholaunch Partner Login</u>:</B>\n";
   $disForm .= "        </td>\n";
   $disForm .= "        <td colspan=\"2\" align=\"left\" valign=\"top\" class=\"text\">\n";
   $disForm .= "         <B><u>License Information</u>:</B>\n";
   $disForm .= "        </td>\n";
   $disForm .= "       </tr>\n";

   $disForm .= "       <tr>\n";
   ## Username - $partUser
   $disForm .= "        <td width=\"12%\" align=\"left\" class=\"text\">\n";
   $disForm .= "         Username:\n";
   $disForm .= "        </td>\n";
   $disForm .= "        <td width=\"33%\" align=\"left\">\n";
   $disForm .= "         <input type=\"text\" name=\"partUser\" value=\"".$partUser."\" class=\"tfield\" style=\"width: 200px;".chkfor("partUser","txt")."\">\n";
   $disForm .= "        </td>\n";

   ## License Type - $disProd
   $disForm .= "        <td width=\"17%\" align=\"left\" class=\"text\">\n";
   $disForm .= "         Licensed Type:\n";
   $disForm .= "        </td>\n";
   $disForm .= "        <td width=\"38%\" align=\"left\" class=\"addr\">\n";
   $disForm .= "         <select name=\"disProd\" class=\"tfield\" style=\"width: 200px;".chkfor("disProd","sel")."\">\n";
   $disForm .= "          <option value=\"proserver\" selected>Server License</option>\n";
   $disForm .= "          <option value=\"proretail\">Lifetime License</option>\n";
   $disForm .= "          <option value=\"proleased\">Leased License</option>\n";
   $disForm .= "         </select>\n";
   $disForm .= "        </td>\n";

   $disForm .= "       </tr>\n";
   $disForm .= "       <tr>\n";

   ## Password - $partPass
   $disForm .= "        <td align=\"left\" class=\"text\">\n";
   $disForm .= "         Password:\n";
   $disForm .= "        </td>\n";
   $disForm .= "        <td align=\"left\">\n";
   $disForm .= "         <input type=\"password\" name=\"partPass\" value=\"".$partPass."\" class=\"tfield\" style=\"width: 115px;".chkfor("partPass","txt")."\">\n";
   $disForm .= "        </td>\n";

   # Make sure we get an IP
   if ( $_SERVER['SERVER_ADDR'] != "" ) {
      $disIP = $_SERVER['SERVER_ADDR'];
   }elseif ( gethostbyname(php_uname(n)) != "" ) {
      $disIP = gethostbyname(php_uname(n));
   }

   # Make sure we get a host name
   if ( php_uname(n) != "" && !eregi("redhat.com", php_uname(n)) ) { // php_uname(n)
      $disHname = php_uname(n);

   } elseif ( php_uname() != "" && !eregi("redhat.com", php_uname()) ) { // php_uname() - formatted
      $string = php_uname();
      $invalid = " ";
      $tok = strtok($string, $invalid);
      while ($tok) {
         $token[]=$tok;
         $tok = strtok($invalid);
      }
      $disHname = $token[1];

   } elseif ( gethostbyaddr($_SERVER['SERVER_ADDR']) != "" ) { // Reverse lookup
      $disHname = gethostbyaddr($_SERVER['SERVER_ADDR']);
   }

   ## Licensed Server - $disServer
   $disForm .= "        <td width=\"17%\" align=\"left\" valign=\"top\" class=\"text\">\n";
   $disForm .= "         Server Address:\n";
   $disForm .= "        </td>\n";
   $disForm .= "        <td width=\"38%\" align=\"left\" class=\"addr\">\n";
   $disForm .= "         ".$disIP."<br>\n";
   $disForm .= "         <i>".$disHname."</i>\n";
   $disForm .= "         <input type=\"hidden\" name=\"disServer\" value=\"".$disIP."\">\n";
   $disForm .= "         <input type=\"hidden\" name=\"disHostname\" value=\"".$disHname."\">\n";
   $disForm .= "        </td>\n";

   $disForm .= "       </tr>\n";
   $disForm .= "       <tr>\n";

   ## Domain License - $disDom
   $disForm .= "        <td colspan=\"2\">&nbsp;</td>\n";
   $disForm .= "        <td align=\"left\" class=\"text\">\n";
   $disForm .= "         Domain Name:\n";
   $disForm .= "        </td>\n";
   $disForm .= "        <td align=\"left\" class=\"addr\">\n";
   $disForm .= "         ".$SERVER_NAME."\n";
   $disForm .= "         <input type=\"hidden\" name=\"disDom\" value=\"".$SERVER_NAME."\">\n";
   $disForm .= "        </td>\n";
   $disForm .= "       </tr>\n";


   ## Auto-Licensing Feature Options
   # ====================================================

   // Re-select chosen values on error
   // ===================================
   function modops($field) {
      global ${$field};
      $mod_opts = ""; // will contain <option> lines

      $opts = "Disabled;Enabled;Deactivated";
      $opt = split(";", $opts);

      $vals = "no;yes;noshow";
      $val = split(";", $vals);

      $cols = "#787878;#339959;#980000";
      $col = split(";", $cols);

      $cnt = count($opt);

      for ( $v=0; $v < $cnt; $v++ ) {
         if ( $val[$v] == ${$field} ) { $sel = " selected"; } else { $sel = ""; }
         $mod_opts .= "<option value=\"".$val[$v]."\" style=\"color: ".$col[$v].";\"".$sel.">".$opt[$v]."</option>\n";
      }

      return $mod_opts;
   }


   $disForm .= "       <!--------Start Auto-Licensing Options--------->\n";
   $disForm .= "       <tr>\n";
   $disForm .= "        <td align=\"left\" valign=\"top\" colspan=\"4\" class=\"text\">\n";
   $disForm .= "         <hr><b><u>Auto-Licensing Options:</u></b><br>\n";
   $disForm .= "         <font color=\"#565656\">\n";
   $disForm .= "         Note: Not neccessary if you've already generated a license for this site\n";
   $disForm .= "         in the Soholaunch Partner Area.</font>\n";
   $disForm .= "        </td>\n";
   $disForm .= "       </tr>\n";
   $disForm .= "       <tr>\n";
   $disForm .= "        <td colspan=\"4\" align=\"left\" valign=\"top\">\n";
   $disForm .= "         <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"text\">\n";
   $disForm .= "          <tr>\n";
   ## Web Blogs - $blog
   $disForm .= "           <td class=\"mod\">Web Blogs:</td>\n";
   $disForm .= "           <td class=\"mod\">\n";
   $disForm .= "            <select name=\"blog\" class=\"mod_dd\">\n";
   $disForm .= "             ".modops("blog");
   $disForm .= "            </select>\n";
   $disForm .= "           </td>\n";
   $disForm .= "           <td width=\"30\">&nbsp;</td>\n";
   ## Shopping Cart - $cart
   $disForm .= "           <td class=\"mod\">Shopping Cart:</td>\n";
   $disForm .= "           <td class=\"mod\">\n";
   $disForm .= "            <select name=\"cart\" class=\"mod_dd\">\n";
   $disForm .= "             ".modops("cart");
   $disForm .= "            </select>\n";
   $disForm .= "           </td>\n";
   $disForm .= "           <td width=\"30\">&nbsp;</td>\n";
   ## Event Calendar - $cal
   $disForm .= "           <td class=\"mod\">Event Calendar:</td>\n";
   $disForm .= "           <td class=\"mod\">\n";
   $disForm .= "            <select name=\"cal\" class=\"mod_dd\">\n";
   $disForm .= "             ".modops("cal");
   $disForm .= "            </select>\n";
   $disForm .= "           </td>\n";
   $disForm .= "          </tr>\n";
   $disForm .= "          <tr>\n";
   ## eNewsletter - $news
   $disForm .= "           <td class=\"mod\">eNewsletter:</td>\n";
   $disForm .= "           <td class=\"mod\">\n";
   $disForm .= "            <select name=\"news\" class=\"mod_dd\">\n";
   $disForm .= "             ".modops("news");
   $disForm .= "            </select>\n";
   $disForm .= "           </td>\n";
   $disForm .= "           <td width=\"30\">&nbsp;</td>\n";
   $disForm .= "           <td class=\"mod\">Database Tables:</td>\n";
   $disForm .= "           <td class=\"mod\">\n";
   ## Database Tables - $data
   $disForm .= "            <select name=\"data\" class=\"mod_dd\">\n";
   $disForm .= "             ".modops("data");
   $disForm .= "            </select>\n";
   $disForm .= "           </td>\n";
   $disForm .= "           <td width=\"30\">&nbsp;</td>\n";
   ## Secure Users - $sec
   $disForm .= "           <td class=\"mod\">Secure Users:</td>\n";
   $disForm .= "           <td class=\"mod\">\n";
   $disForm .= "            <select name=\"sec\" class=\"mod_dd\">\n";
   $disForm .= "             ".modops("sec");
   $disForm .= "            </select>\n";
   $disForm .= "           </td>\n";
   $disForm .= "          </tr>\n";
   $disForm .= "         </table>\n";
   $disForm .= "        </td>\n";
   $disForm .= "       </tr>\n";
   $disForm .= "       <TR ALIGN=\"CENTER\" VALIGN=\"TOP\"> \n";
   $disForm .= "        <TD COLSPAN=\"4\">\n";
   $disForm .= "         <br><INPUT TYPE=\"submit\" NAME=\"Submit\" VALUE=\" Continue \" CLASS=\"text\" STYLE='CURSOR: HAND;'>\n";
   $disForm .= "        </TD>\n";
   $disForm .= "       </TR>\n";
   $disForm .= "      </TABLE>\n";
   $disForm .= "      </FORM>\n";



} elseif ( $STEP == "GOODLIC" || $STEP == "HASLIC" ) {

   ############################################################################################
   ## =========================================================================================
   // STEP 4: Auto-License response recieved. Display results
   ## =========================================================================================
   ############################################################################################

   ## Pull feature lics from key field
   ## ==========================================
   function showme($mod) {
      global ${$mod};
      $var = ${$mod};

      // Feature enabled
      if ( $var == "yes" ) {
         $chk = "<font style=\"color: #339959; font-weight: normal;\">Enabled</font>\n";

      // Feature deactivated
      } elseif ( $var == "noshow" ) {
         $chk = "<font style=\"color: #980000; font-weight: normal;\">Deactivated</font>\n";

      // Feature disabled
      } else {
         $chk = "<font style=\"color: #787878; font-weight: normal;\">Disabled</font>\n";

      }
      return $chk;
   }


   ## Title and description
   ## =========================================

   // Formatted result message
   $disForm = "      <font class=\"subhdr\"><b>".$msg."</b></font><br><br>\n";

   if ( $STEP == "GOODLIC" ) {

      $disForm .= "      <TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"5\" BGCOLOR=\"#F8F9FD\" STYLE='border: 1px solid black;'>\n";

      // Soholaunch Partner Login | License Information
      // ===========================================================================
      $disForm .= "       <tr>\n";
      $disForm .= "        <td colspan=\"2\" align=\"left\" valign=\"top\" class=\"text\">\n";
      $disForm .= "         <B><u>License Information</u>:</B>\n";
      $disForm .= "        </td>\n";
      $disForm .= "       </tr>\n";

      ## Server License IP Address - $disServer
      if ( $disProd == "proserver" ) {
         $disForm .= "       <tr>\n";
         $disForm .= "        <td width=\"17%\" align=\"left\" class=\"text\">\n";
         $disForm .= "         Licensed Server:\n";
         $disForm .= "        </td>\n";
         $disForm .= "        <td width=\"83%\" align=\"left\" class=\"addr\">\n";
         $disForm .= "         ".$SERVER_ADDR."\n";
         $disForm .= "         <input type=\"hidden\" name=\"disServer\" value=\"".$SERVER_ADDR."\">\n";
         $disForm .= "        </td>\n";
         $disForm .= "       </tr>\n";
      }

      ## Domain License - $disDom
      $disForm .= "       <tr>\n";
      $disForm .= "        <td width=\"17%\" align=\"left\" class=\"text\">\n";
      $disForm .= "         Licensed Domain:\n";
      $disForm .= "        </td>\n";
      $disForm .= "        <td width=\"83%\" align=\"left\" class=\"addr\">\n";
      $disForm .= "         ".$SERVER_NAME."\n";
      $disForm .= "         <input type=\"hidden\" name=\"disDom\" value=\"".$SERVER_NAME."\">\n";
      $disForm .= "        </td>\n";
      $disForm .= "       </tr>\n";


      // Feature License Summary
      // ====================================================
      $disForm .= "       <tr>\n";
      $disForm .= "        <td colspan=\"2\" align=\"left\" valign=\"top\">\n";
      $disForm .= "         <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"text\">\n";
      $disForm .= "          <tr>\n";
      ## Web Blogs - $blog
      $disForm .= "           <td class=\"mod\">Web Blogs:</td>\n";
      $disForm .= "           <td class=\"mod\">\n";
      $disForm .= "            ".showme("blog")."\n";
      $disForm .= "           </td>\n";
      $disForm .= "           <td width=\"30\">&nbsp;</td>\n";
      ## Shopping Cart - $cart
      $disForm .= "           <td class=\"mod\">Shopping Cart:</td>\n";
      $disForm .= "           <td class=\"mod\">\n";
      $disForm .= "            ".showme("cart")."\n";
      $disForm .= "           </td>\n";
      $disForm .= "           <td width=\"30\">&nbsp;</td>\n";
      ## Event Calendar - $cal
      $disForm .= "           <td class=\"mod\">Event Calendar:</td>\n";
      $disForm .= "           <td class=\"mod\">\n";
      $disForm .= "            ".showme("cal")."\n";
      $disForm .= "           </td>\n";
      $disForm .= "          </tr>\n";
      $disForm .= "          <tr>\n";
      ## eNewsletter - $news
      $disForm .= "           <td class=\"mod\">eNewsletter:</td>\n";
      $disForm .= "           <td class=\"mod\">\n";
      $disForm .= "            ".showme("news")."\n";
      $disForm .= "           </td>\n";
      $disForm .= "           <td width=\"30\">&nbsp;</td>\n";
      $disForm .= "           <td class=\"mod\">Database Tables:</td>\n";
      $disForm .= "           <td class=\"mod\">\n";
      ## Database Tables - $data
      $disForm .= "            ".showme("data")."\n";
      $disForm .= "           </td>\n";
      $disForm .= "           <td width=\"30\">&nbsp;</td>\n";
      ## Secure Users - $sec
      $disForm .= "           <td class=\"mod\">Secure Users:</td>\n";
      $disForm .= "           <td class=\"mod\">\n";
      $disForm .= "            ".showme("sec")."\n";
      $disForm .= "           </td>\n";
      $disForm .= "          </tr>\n";
      $disForm .= "         </table>\n";
      $disForm .= "        </td>\n";
      $disForm .= "       </tr>\n";

      $disForm .= "       <tr>\n";
      $disForm .= "        <td colspan=\"2\" align=\"center\" class=\"text\">\n";
      $disForm .= "         &nbsp;\n";
      $disForm .= "        </td>\n";
      $disForm .= "       </tr>\n";

      $disForm .= "      </TABLE>\n";


   } // End if license created/found


} // End building step-specific form html


########  ######  ##     ##  #######     ##     ## ######## ##     ## ##
##       ##    ## ##     ## ##     ##    ##     ##    ##    ###   ### ##
##       ##       ##     ## ##     ##    ##     ##    ##    #### #### ##
######   ##       ######### ##     ##    #########    ##    ## ### ## ##
##       ##       ##     ## ##     ##    ##     ##    ##    ##     ## ##
##       ##    ## ##     ## ##     ##    ##     ##    ##    ##     ## ##
########  ######  ##     ##  #######     ##     ##    ##    ##     ## ########

$disHTML = "<HTML>\n";
$disHTML .= "<HEAD>\n";
$disHTML .= "<TITLE>FIRST-TIME CONFIGURATION SETUP</TITLE>\n";


##############################################################################################
// CSS style classes
##############################################################################################
$disHTML .= "<STYLE>\n";
$disHTML .= ".hdr {\n";
$disHTML .= "   font-family: Tahoma, verdana, arial, helvetica, sans-serif;\n";
$disHTML .= "   font-size: 15px;\n";
$disHTML .= "   font-weight: normal;\n";
$disHTML .= "   letter-spacing: 1px;\n";
$disHTML .= "}\n\n";

$disHTML .= ".subhdr {\n";
$disHTML .= "   font-family: Verdana, arial, helvetica, sans-serif;\n";
$disHTML .= "   font-size: 13px;\n";
$disHTML .= "   font-weight: normal;\n";
$disHTML .= "   color: #2E2E2E;\n";
$disHTML .= "}\n\n";

$disHTML .= ".subhdr_next {\n";
$disHTML .= "   font-family: Verdana, arial, helvetica, sans-serif;\n";
$disHTML .= "   font-size: 13px;\n";
$disHTML .= "   font-weight: normal;\n";
$disHTML .= "   color: #b5b5b5;\n";
$disHTML .= "}\n\n";

$disHTML .= ".text { \n";
$disHTML .= "   font-family: Verdana, Arial, Helvetica, Sans-serif;\n";
$disHTML .= "   font-size: 12px; \n";
$disHTML .= "   color: black; \n";
$disHTML .= "}\n\n";

$disHTML .= ".text_msg { \n";
$disHTML .= "   font-family: Verdana, Arial, Helvetica, Sans-serif;\n";
$disHTML .= "   font-size: 12px; \n";
$disHTML .= "   color: #D70000; \n";
$disHTML .= "   font-weight: normal;\n";
$disHTML .= "}\n\n";

$disHTML .= ".optional { \n";
$disHTML .= "   font-family: Verdana, Arial, Helvetica, Sans-serif;\n";
$disHTML .= "   font-size: 11px; \n";
$disHTML .= "   color: #595959; \n";
$disHTML .= "}\n\n";

$disHTML .= ".addr { \n";
$disHTML .= "   font-family: Courier New, Courier, mono;\n";
$disHTML .= "   font-size: 13px;\n";
$disHTML .= "   color: #980000;\n";
$disHTML .= "   font-weight: normal;\n";
$disHTML .= "   padding-bottom: 5px;\n";
$disHTML .= "}\n\n";

$disHTML .= ".tfield { \n";
$disHTML .= "   WIDTH: 300px;\n";
$disHTML .= "   color: blue;\n";
$disHTML .= "   font-size: 11px;\n";
$disHTML .= "   font-family: Verdana, Arial, Helvetica, Sans-serif;\n";
$disHTML .= "}\n\n";

// .tfield_dir
$disHTML .= ".tfield_dir { \n";
$disHTML .= "   width: 600px;\n";
$disHTML .= "   color: gray;\n";
$disHTML .= "   font-size: 10px;\n";
$disHTML .= "   font-family: Verdana, Arial, Helvetica, Sans-serif;\n";
$disHTML .= "}\n\n";

$disHTML .= ".tfield_msg {\n";
$disHTML .= "   WIDTH: 300px;\n";
$disHTML .= "   color: blue;\n";
$disHTML .= "   font-size: 11px;\n";
$disHTML .= "   font-family: Verdana, Arial, Helvetica, Sans-serif;\n";
$disHTML .= "   border: 1px solid #D70000;\n";
$disHTML .= "}\n\n";

$disHTML .= "td.mod {\n";
$disHTML .= "   padding: 3px 10px 3px 0px;\n";
$disHTML .= "   font-family: Verdana, Arial, Helvetica, Sans-serif; \n";
$disHTML .= "   font-size: 11px; \n";
$disHTML .= "   color: #336699;\n";
$disHTML .= "   font-weight: bold;\n";
$disHTML .= "}\n\n";

$disHTML .= "select.mod_dd {\n";
$disHTML .= "   width: 90px;\n";
$disHTML .= "   font-size: 10px;\n";
$disHTML .= "   font-family: verdana, arial, helvetica, sans-serif;\n";
$disHTML .= "   color: #000000;\n";
$disHTML .= "   font-weight: normal;\n";
$disHTML .= "}\n\n";

$disHTML .= "a:link {color: #CC9800; text-decoration:none; border-bottom:0px solid #004C9A;}\n";
$disHTML .= "a:visited {color: #CC9800; text-decoration:none; border-bottom:0px solid #004C9A;}\n";
$disHTML .= "a:hover {color: #FCD35C; text-decoration:none; border-bottom:0px solid #AEC9FF;}\n";
$disHTML .= "a:active {color: #7A0000; text-decoration:none; border-bottom:0px solid #AEC9FF;}\n";

$disHTML .= "</STYLE>\n";


##############################################################################################
// HTML body
##############################################################################################
$disHTML .= "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=iso-8859-1\">\n";
$disHTML .= "</HEAD>\n";
$disHTML .= "<BODY BGCOLOR=\"#565656\" LINK=BLUE ALINK=BLUE VLINK=BLUE>\n";
$disHTML .= "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100% HEIGHT=100% ALIGN=CENTER>\n";
$disHTML .= " <TR>\n";
$disHTML .= "  <TD ALIGN=CENTER VALIGN=MIDDLE CLASS=text>\n";
$disHTML .= "   <TABLE BORDER=0 CELLPADDING=10 CELLSPACING=0 WIDTH=750 ALIGN=CENTER BGCOLOR=WHITE STYLE='border: 1px inset black;'>\n";
$disHTML .= "    <TR>\n";
$disHTML .= "     <TD ALIGN=LEFT VALIGN=TOP CLASS=text><IMG SRC=\"program/soholaunch.gif\" vspace=3 hspace=3 border=0>\n";
$disHTML .= "      <br><br>\n";

// Version Title
// ---------------
$disHTML .= "      <font class=\"hdr\"><center><font color=\"#FF7700\"><b>Sohlaunch Setup </b></font></center></font>\n";

$disHTML .= "      <BR>\n";


// Plug-in appropriate form for this step
// --------------------------------------------
$disHTML .= $disForm;


// Close HTML body
// --------------------------------------------
$disHTML .= "      <BR><BR>\n";

// Click here to login now!
// ===========================================
if ( $STEP == "GOODLIC" || $STEP == "HASLIC" ) {
   $disHTML .= "      <center>\n";
   $disHTML .= "      <div align=\"center\" style=\"border: 0px solid #000000;\">\n";
   $disHTML .= "       <br><b><a href=\"http://".$SERVER_NAME."/sohoadmin/index.php\">Click here to login now!</a></b>\n";
   $disHTML .= "      </div>\n";
   $disHTML .= "      </center>\n";
}

$disHTML .= "      <BR><BR>\n";
$disHTML .= "     </TD>\n";
$disHTML .= "    </TR>\n";
$disHTML .= "   </TABLE>\n";
$disHTML .= "  </TD>\n";
$disHTML .= " </TR>\n";
$disHTML .= "</TABLE>\n";
$disHTML .= "</BODY>\n";
$disHTML .= "</HTML>\n";

echo $disHTML;


?>