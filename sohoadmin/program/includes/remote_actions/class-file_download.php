<?php
//error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

##########################################################################################################################################
## Soholaunch(R) Site Management Tool
## Version 4.7
##
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugz.soholaunch.com
## Community:     http://forum.soholaunch.com
##########################################################################################################################################

##########################################################################################################################################
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
##########################################################################################################################################

/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*
    ______ _  __         ____                          __                   __
   / ____/(_)/ /___     / __ \ ____  _      __ ____   / /____   ____ _ ____/ /
  / /_   / // // _ \   / / / // __ \| | /| / // __ \ / // __ \ / __ `// __  /
 / __/  / // //  __/  / /_/ // /_/ /| |/ |/ // / / // // /_/ // /_/ // /_/ /
/_/    /_//_/ \___/  /_____/ \____/ |__/|__//_/ /_//_/ \____/ \__,_/ \__,_/

> Accepts: "target host", "path to target script"
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
class file_download {
   var $remote = array(); // Remote file data
   var $local = array(); // Local file data

   var $msg; // Specific success/failure message

   // Break full path into element arrays
   //================================================
   function file_download($rempath, $locpath, $donow = "rock") {
      $this->remote['path'] = $rempath;
      $this->remote['dir'] = dirname($rempath);
      $this->remote['file'] = basename($rempath);

      $this->local['path'] = $locpath;
      $this->local['dir'] = dirname($locpath);
      $this->local['file'] = dirname($locpath);

      # Proceed with dl unless otherwise directed
      if ( $donow == "rock" ) {
				return $this->dlnow();
				return true;
      }
   }

   /*----------------------------------------------------*/
   function dlnow() {
      error_reporting(E_PARSE);

		if ( ini_get('allow_url_fopen') != 0 ) {// Cam remote_url_fopen fix
	      // open remote file handle
	      if ( !$fp1 = fopen($this->remote['path'],"r") ) {
	         $this->msg = lang("Unable to open remote update file.");
	         return false;
	      }

	      // create local file
	      if ( !$fp2 = fopen($this->local['path'],"w+") ) {
	         $this->msg = lang("Unable to write local copy of update file.");
	         return false;
	      }

	      // read remote and write to local
	      while (!feof($fp1)) {
	           $output = fread($fp1,1024);
	           fputs($fp2,$output);
	      }

	      fclose($fp1);
	      fclose($fp2);

	      $this->msg = lang("Remote update file downloaded successfully.");

	      return true;

	    } else { 
	    	// Cam remote_url_fopen fix
	    	if ( hascurl() ) {
	    		$ch = curl_init();
				
				# Curl download script
				$timeout = 5; // set to zero for no timeout 
				curl_setopt ($ch, CURLOPT_URL, $this->remote['path']); 
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout); 
				$file_contents = curl_exec($ch); 
				curl_close($ch);
				
		      // create local file
		      if ( !$fp2 = fopen($this->local['path'],"w+") ) {
		         $this->msg = lang("Unable to write local copy of update file.");
		         return false;
		      }
	
		      // read remote and write to local
		      fwrite($fp2, $file_contents);
		      fclose($fp2);				
				
			} else {
				# Try FTP
				$HTTPHOST = $this->remote['path'];
				$file_name = basename($HTTPHOST);
				$full_url = eregi_replace($file_name, '', $HTTPHOST);
				$full_url = eregi_replace('http://', '', $full_url);
				$full_url = eregi_replace('https://', '', $full_url);
				$full_url = eregi_replace('ftp.', '', $full_url);
				$full_url = eregi_replace('ftp://', '', $full_url);
				$full_url = eregi_replace('www.', '', $full_url);
				$full_url = eregi_replace('www1.', '', $full_url);
				$full_url = eregi_replace('www2.', '', $full_url);
				$full_url = eregi_replace('www3.', '', $full_url);
				$path = explode('/', $full_url);
				//$pathcount = count($path);
				$file_path = '';
					foreach($path as $var=>$val){
						if ( $val != '') {
							$file_path .= $val."/";
						}
					}
				$full_url = $path[0];
				$file_path = eregi_replace($path[0].'/', '', $file_path);
				$ftp_server = $full_url;
				$local_file = $this->local['path'];
				$server_file = $file_path.$file_name;
				$ftp_user_name = 'public';
				$ftp_user_pass ='';

				// set up basic connection
				$conn_id = ftp_connect($ftp_server);

				// login with username and password
				$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

				// try to download $server_file and save to $local_file
				if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
					$this->msg = "Remote file downloaded successfully.";
					return true;
				} else {
					echo "There was a problem\n";
					return false;
				}

				// close the connection
				ftp_close($conn_id);
			} // End else try ftp
			
		} // End else try stuff besides fopen
		
	} // End dlnow()

}  // End remote file class
############################
?>