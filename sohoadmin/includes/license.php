<?php
//error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


//error_reporting(E_PARSE);

# Uncomment and run once if you have to upload key files via ftp
//chmod("filebin", 0777);

############################################################################################
## Soholaunch(R) Site Management Tool
## Version 4.8
##
## Author: 			Mike Johnston & Mike Morrison
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugz.soholaunch.com
############################################################################################

############################################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2005 Soholaunch.com, Inc. and Mike Johnston.  All Rights Reserved.
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
#############################################################################################
/*#############################################################################
----------------------------
Script Outline
----------------------------
1) Check for soholaunch.lic && type.lic
YES: Read type.lic & go to step 2
NO: Ping server, [over]write soholaunch.lic & type.lic

2a) type.lic = Orphan License
    >> Proceed into product (with buy now/upgade button)

2b) type.lic = Lifetime License
    >> Proceed into product

2c) type.lic = proserver
    >> include("pulse.php")
    >> ping server for key file
    >> overwrite if found

/*#############################################################################*/
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// By License you may not modify any portion of this script. This particular
// script has dependancies and programming that can not be modified.
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

# Define involved files and folders
$filebin = "filebin";
$filebin_path = "sohoadmin/filebin";
$soholaunch_lic = "filebin/soholaunch.lic";
$type_lic = "filebin/type.lic";
$eula_lic = "filebin/eula.lic";

# filebin should exist -- if not check all the system folders
$filebin_fullpath = $_SESSION['docroot_path']."/sohoadmin/filebin";
if ( !is_dir($filebin_fullpath) ) {
   include("includes/create_system_folders.inc.php");
}

# Make sure fielbin is writeable
if ( !testWrite($filebin_fullpath, true) ) {
   echo "cannot make filebin writeable!";
}

# Does local license files exist, or is this the first login?
if ( !file_exists($soholaunch_lic) ) {
   $firstlogin = true;
} else {
   $firstlogin = false;
}

# Pull license type from type.lic
if ( !file_exists($type_lic) ) {
   $version = "unknown";
} else {
   $file = fopen($type_lic, "r");
   $prod = fread($file,filesize($type_lic));
   fclose($file);
   $prodnam = split("::::", $prod);
   $version = $prodnam[1];
}


# Attempt to ping licensing server and grab current key
$host = "securexfer.net";
$licensing_api_script = "product_reports/api-generate_pro_license-ultra.php";
$connect_error = 0;



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

# Qry string for remote licensing script
$data = "version=".$version."&dom=".$_SESSION['this_ip']."&ip=".$disIP."&sname=".$disHname."&dkey=".$_SESSION['key'];
if($_SESSION['final_ip']!=''){
	$data .= "&final_ip=".$_SESSION['final_ip'];
}

# Will contain api output
$buf = "";

# Try curl fist
if ( function_exists("curl_setopt") ) {
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $host."/".$licensing_api_script);
   curl_setopt($ch, CURLOPT_HEADER, 1);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_POST, 1);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
   $buf = curl_exec($ch);
   curl_close($ch);

}

# Try fsockopen if curl failed
if ( trim($buf) == "" && $fp = fsockopen($host,80)) {
   # Pull license for this domain
   fputs($fp, "POST /product_reports/api-generate_pro_license-ultra.php HTTP/1.1\n");
   fputs($fp, "Host: $host\n");
   fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
   fputs($fp, "Content-length: " . strlen($data) . "\n");
   fputs($fp, "User-Agent: MSIE\n");
   fputs($fp, "Connection: close\n\n");
   fputs($fp, $data);

   $buf = "";

   # Read key data into container var
   while (!feof($fp)) {
      $buf .= fgetss($fp,128);
   }

   fclose($fp);
} // End if $fp

if ( trim($buf) != "" ) {

   # Kill local lic files
   unlink($type_lic);
   unlink($soholaunch_lic);

   # Separate license key data from product type
   $tmp = explode("~SOHO~", $buf);
   $rawKey = trim($tmp[1]);
   $rawTyp = trim($tmp[2]);
   $updates_expiration = trim($tmp[3]);

   # Try to clean reg key line-by-line
   $klines = explode("\n", $rawKey);
   foreach ( $klines as $ind=>$con ) {
      if ( strlen($con) > 3 ) {
         $cleanKey[] = $con;
      }
   }
   $REGKEY = implode("\n", $cleanKey);

   # Try to clean product type line-by-line
   $tlines = explode("\n", $rawTyp);
   foreach ( $tlines as $ind=>$con ) {
      if ( strlen($con) > 3 ) {
         $cleanType[] = $con;
      }
   }
   $LICTYP = implode("\n", $cleanType);
   $LICTYP = "::::".$LICTYP."::::";

} else {
	if(!file_exists($soholaunch_lic)){
	   $connect_error = 1;
	
	   # Firewall is blocking the socket connection or licensing server is down
	   $licensing_error .= "<div style=\"border: 1px solid #980000; background: #F6DFDF; padding: 15px; font-face: tahoma, arial, helvetica, sans-serif; font-size: 12px;\">\n";
	   $licensing_error .= " <h1>Error: Cannot connect to licensing server.</h1>\n";
	   $licensing_error .= "</div>";
	   $licensing_error .= "<br>\n";
	   $licensing_error .= "<div style=\"border: 1px solid #980000; background: #EFEFEF; padding: 15px; font-face: tahoma, arial, helvetica, sans-serif; font-size: 12px;\">\n";
	
	   $licensing_error .= " <h2>Possible Cause A</h2>\n";
	   $licensing_error .= " <p>Certain required php functions are disabled in your server configuration. \n";
	   $licensing_error .= " Specifically, check to make sure socket connections (i.e. <b>fsockopen</b>) and the <b>shell_exec()</b> function are both enabled. \n";
	   $licensing_error .= " If this is not an option due to security restrictions, etc, then you can alternatively install php's curl library and \n";
	   $licensing_error .= " this routine will use a curl connection instead of a socket connection. The fact that you are looking at this error message means\n";
	   $licensing_error .= " the check for curl support failed AND the socket connect method failed.</p>\n";
	
	   $licensing_error .= " <h2>Possible Cause B</h2>\n";
	   $licensing_error .= " <p>The server on which your website resides has a firewall that is blocking socket connections \n";
	   $licensing_error .= " such as the one employed here to pull down the license key for your domain.</p>\n";
	   $licensing_error .= " <p>The specific domains that this software requires access to are\n";
	   $licensing_error .= " '<b>securexfer.net</b>' and '<b>auto.securexfer.net</b>'. \n";
	   $licensing_error .= " If these domains are blocked, you typically just have to add them to the firewall's \"allow list\" to elimitate this issue entirely.</p>";
	   $licensing_error .= "</div><br>";
	   echo $licensing_error; exit;
	}
} // End if socket opens

//echo "REGKEY: [".$REGKEY."]"; exit;

# Try to run from local key if connection error
# Allow connection error to bomb product only if there's no local key to run from
if ( $connect_error > 0 && (!file_exists($soholaunch_lic) || !file_exists($type_lic)) ) {

   // Connection error and no local key...yikes


# No connection error, proceed as normal
} elseif ( $REGKEY != "" ) {
   # Make sure filbin is writeable, try to fix if it's not, bomb if still not writeable
   if ( !testWrite($filebin_path, true) ) {

      # Get permissions on /sohoadmin
      $sohoPerms = substr(sprintf('%o', fileperms($filebin)), -3);

      # Error: Permissions too tight
      echo "<div style=\"border: 1px solid #980000; background: #F6DFDF; padding: 15px; font-face: tahoma, arial, helvetica, sans-serif; font-size: 12px;\">\n";
      echo " <h2>Error: Cannot write license key file.</h2>\n";
      echo "</div>";
      echo "<br>\n";
      echo "<div style=\"border: 1px solid #980000; background: #EFEFEF; padding: 15px; font-face: tahoma, arial, helvetica, sans-serif; font-size: 12px;\">\n";
      echo " <b style=\"color: #D70000;\">Cause</b>: Permissions settings on the /sohoadmin/filebin directory are too tight (".$sohoPerms.").<br><br>\n";
      echo " <b style=\"color: #339959;\">Solution</b>: Please make sure the /sohoadmin/filebin directory is writeable.<br><br>\n";
      echo "</div>";

//      echo "<h1><a href=\"version.php\">Click here</a> to continue using product with basic features only.</h1>";
      exit;

   } else {

      # Delete lic files if they already exist
      unlink($soholaunch_lic);
      unlink($type_lic);

//      echo "key dead"; exit;
//      echo nl2br($REGKEY); exit;

      # Write soholaunch.lic
      $file = fopen($soholaunch_lic, "w");
      fwrite($file, $REGKEY);
      fclose($file);
      // sleep(1); // Give local installs time to write file

      # Write type.lic
      $file = fopen($type_lic, "w");
      fwrite($file, $LICTYP);
      fclose($file);
      $_SESSION['lictype'] = eregi_replace("::::", "", $LICTYP);
   } // End write key routine

} // End if connect_error and no local keys

if($_SESSION['lictype']=='proretail' && strlen($updates_expiration) > 1){
	$expyear = date('Y', $updates_expiration);
	$expmonth = date('m', $updates_expiration);
	$expday = date('d', $updates_expiration);
	$_SESSION['updates_expiration'] = mktime(0,0,0,$expmonth,$expday,($expyear+1));
}

if ( file_exists($soholaunch_lic) && !file_exists($type_lic) ) { // Entire elseif added for 11-2004 build
   // Kill key and go grab fresh copies of both .lic's
   // ===========================================================================
   unlink($soholaunch_lic); // Drastically reduces 'repeating EULA' issue (should be a permissions prob only now)

   // Hidden form to refresh the page without hitting the EULA
   //--------------------------------------------------------------------
   echo "<form name=\"skipeula\" method=\"post\" action=\"version.php\">\n";
   echo "<input type=\"hidden\" name=\"avoidwht\" value=\"dbleula\">\n";
   echo "</form>\n";

   echo "<script language=\"javascript\">\n";
   echo " window.document.skipeula.submit();\n";
   echo "</script>\n";

} // End if key file does not exist


# Did they just aggree to the EULA?
if ( $_POST['ECHK_LIC'] == "ON" ) {
   # Write user info to eula.lic
   $eula_agree = "AGREED ON: ".date("l, F jS Y (g:i:s a T)")."\n";
   $eula_agree .= "REMOTE USER: ".$_SERVER['REMOTE_HOST']." (".$_SERVER['REMOTE_ADDR'].":".$_SERVER['REMOTE_PORT'].")\n";
   $file = fopen($eula_lic, "w");
   fwrite($file, $eula_agree);
   fclose($file);
   // sleep(1); // Give local installs time to write file
}


############################################################################
### READ LICENSE.TXT FILE INTO MEMORY                                    ###
############################################################################
$filename = "license.txt";
$file = fopen("$filename", "r") or DIE("Error: Could not open license agreement.  Please make sure this is a legitimate copy of the software.<br/>".getcwd()."/".$filename);
$license = fread($file,filesize($filename));
fclose($file);
# Show license agreement form?
if ( !file_exists($eula_lic) && !file_exists("eula.lic") ) {
?>

<HTML>
<HEAD>
<TITLE>License Agreement</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<STYLE TYPE="TEXT/CSS">
<!--
.border {  border: 1px black solid; }
.txt { font-family: Arial; font-size: 9pt; }
-->
</STYLE>
<script language="JavaScript">
<!--
	function moz_reload(init) {  // reload window if Netscape resizes
	  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
	    document.tmp_pgW=innerWidth; document.tmp_pgH=innerHeight; onresize=moz_reload; }}
	  else if (innerWidth!=document.tmp_pgW || innerHeight!=document.tmp_pgH) location.reload();
	}

	moz_reload(true);


// -->
</script>
</HEAD>
<BODY bgcolor="#EFEFEF" TEXT="BLACK" LINK="red" VLINK="red" ALINK="red" LEFTMARGIN="0" TOPMARGIN="0" MARGINwidth="0" MARGINHEIGHT="0">
<table width="100%" HEIGHT="100%" border="0" cellspacing="0" cellpadding="0" ALIGN="CENTER">
 <tr>
  <td ALIGN="CENTER" VALIGN="MIDDLE">
   <table border="0" cellspacing="0" cellpadding="0" ALIGN="CENTER">
    <tr>
     <td>
      <table width="625" border="0" cellspacing="3" cellpadding="3" ALIGN="CENTER">
       <tr>
        <td ALIGN="LEFT" VALIGN="TOP" class="txt">

         <form name=lic method=post action="version.php">
          <input type=hidden name="ECHK_LIC" value="ON">
          <table width="100%" height=100% cellpadding=0 cellspacing="0" border="0">
           <tr>
            <td align=center valign=center>
             <table width="300" border="0" cellspacing="0" cellpadding="4" align="center" class=border bgcolor="#FFFFFF">
              <tr>
               <td class=txt bgcolor=#8caae7>
                <font color="#FFFFFF" face=Verdana><b>License Agreement</b></font>
               </td>
              </tr>
              <tr align="center" valign="top">
               <td>
                <table align="center" border="0" cellspacing="0" cellpadding="3" class=txt>
                 <tr>
                  <td valign="top" align="center" class=txt>
                   <font color=black>
                   <TEXTAREA name=LICAGREE READONLY style='width: 600px; height: 350px; font-family: Arial; font-size: 8pt;'><? echo $license; ?></TEXTAREA>
                   <BR>
                   <BR>
                   <input type=button class=txt style='cursor: hand; font-face: Tahoma; font-size: 8pt;' value="CANCEL" onclick="self.close();">
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                   <input type=submit class=txt style='cursor: hand; font-face: Tahoma; font-size: 8pt;' value="I ACCEPT">
                   <BR>
                   <BR>
                  </td>
                 </tr>
                </table>
               </td>
              </tr>
             </table>
          </table>
         </form>
        </td>
      </table>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</BODY>
</HTML>

<?php
} // End if eula.lic exists
?>