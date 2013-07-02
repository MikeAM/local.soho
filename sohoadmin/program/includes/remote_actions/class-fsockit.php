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
    ____                    __
   / __/_____ ____   _____ / /__ ____   ____   ___   ____
  / /_ / ___// __ \ / ___// //_// __ \ / __ \ / _ \ / __ \
 / __/(__  )/ /_/ // /__ / ,<  / /_/ // /_/ //  __// / / /
/_/  /____/ \____/ \___//_/|_| \____// .___/ \___//_/ /_/
                                    /_/

>> Accepts: "target host", "path to target script", "array of vars and vals to post", "include standard delim vars (yes/no)"
-^- Opens socket connection and posts given data to given site
<< Returns: Response array indexed by optional formats (at minimum ['raw'] = raw response)
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

class fsockit {
   var $host; // Domain or IP of remote server
   var $script; // Path to target script on remote server
   var $pairs; // var=value pairs for passed url string
   var $rez = array(); // Will contain output in diff formats
   var $outer_delimiter; // Separates output from headers & other chunks
   var $inner_delimiter; // Typically used to delimit array elements

   ###################################################################################
   /// Compile essential connection info
   ###================================================================================
   function fsockit($site = "", $file = "", $data = "", $delims = "yes") {
      $this->outer_delimiter = "-~-OUTPUT-~-";
      $this->inner_delimiter = "~-~";
      $this->host = $site;
      $this->script = $file;

      // Format data array if passed
      //==========================================================
      if ( is_array($data) ) {

         // Include delimiter vars with passed data?
         if ( $delims == "yes" ) {
            $data['oDelim'] = $this->outer_delimiter;
            $data['iDelim'] = $this->inner_delimiter;
         }

         // Convert array into url string
         $this ->mkpairs($data);
      }
   }

   ###################################################################################
   /// Convert data array into var=val url string
   ###================================================================================
   function mkpairs($data = "") {
      if ( is_array($data) ) {
         foreach ( $data as $var=>$val ) {
            $this->pairs .= "&".$var."=".$val;
         }
      }
   }

   ###################################################################################
   /// Initiate database backup action on remote site (Experimental)
   ###================================================================================
   function dosoho( $sqlnam = "bak-whatev", $dotgz = "no", $subdom = "promanual" ) {
      $this ->host = $subdom.".soholaunch.com";
      $this ->script = "/sohoadmin/update/process_remote.php";

      # Build data array
      $data = array( surfto=>"dbdump", mktar=>$dotgz, outFile=>$sqlnam, oDelim=>$this->outer_delimiter, iDelim=>$this->inner_delimiter );

      # Format data as url string
      $this ->mkpairs($data);
   }

   ###################################################################################
   // Use SOCKET connection to post data
   ###================================================================================
   function sockput($port = 80) {

      if ( $fp = fsockopen($this->host, $port) ) {
         fputs($fp, "POST ".$this->script." HTTP/1.1\n");
         fputs($fp, "Host: ".$this->host."\n");
         fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
         fputs($fp, "Content-length: " . strlen($this->pairs) . "\n");
         fputs($fp, "User-Agent: MSIE\n");
         fputs($fp, "Connection: close\n\n");
         fputs($fp, $this->pairs);

         // Read output for raw response
         while ( !feof($fp) ) { $this->rez['raw'] .= fgets($fp,128); }

         // Close remote socket
         fclose($fp);

      } else {
         echo "<b>Error: Unable to connect to socket:</b><br>Host: ".$this->host."<br>Script:".$this->script."<br>URL: ".$this->pairs."<br>Response: ".$this->rez['raw']."\n"; exit;

      } // End socket connection


      /// Break apart response into segments by type
      ###=============================================================================
      $outSplit = split($this->outer_delimiter, $this->rez['raw']);

      # HTTP Header information
      #============================================================
      $this->rez['headers'] = $outSplit[0];

      # Standard response string using passed delimiters
      #============================================================
      $this->rez['delimited'] = $outSplit[1];

      # Split by passed delims and format with HTML <br> tags
      $this->rez['br'] = str_replace($this->inner_delimiter, "<br>", $outSplit[1]);


      # Serialized version of original result array
      #============================================================
      $this->rez['serialized'] = unserialize($outSplit[2]);

      # Fully-restored result array from remote script
      $this->rez['restored'] = unserialize($outSplit[2]);

      return $this->rez;

   } // End sockput method


   ###################################################################################
   // Use CURL to post data
   ###================================================================================
   function curlput( $proto = "http" ) {

      # URL of gateway for cURL to post to
      $Url = $proto."://".$this->host.$this->script;

      # Initiate curl connection
      $ch = curl_init($Url);

      # Workaround from php.net user notes for SSL certificate error (happens with authorize.net transactions sometimes)
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

      curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
      curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $this->pairs, "& " )); // use HTTP POST to send form data

      # TESTING: un-comment this to view errors in browser
//      curl_setopt($ch, CURLOPT_VERBOSE, 1);

      $this->rez['raw'] =  curl_exec($ch); //execute post and get results
      curl_close ($ch);
      return $this->rez;

   } // End curl post method

} // End fsockit class

?>