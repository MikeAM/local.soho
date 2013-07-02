<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


##########################################################################################
##########################################################################################
###Cams allow_url_fopen bypass############################################################
###########################################################################################
##The remote_include Function allows you to include serialized arrays, or a pages output###
###########################################################################################
$clazzes = get_declared_classes();
if(!in_array("HttpRequest", $clazzes)) {
	   class HTTPRequest
	{
	   var $_fp;        // HTTP socket
	   var $_url;        // full URL
	   var $_host;        // HTTP host
	   var $_protocol;    // protocol (HTTP/HTTPS)
	   var $_uri;        // request URI
	   var $_port;        // port

	   // scan url
	   function _scan_url()
	   {
	       $req = $this->_url;

	       $pos = strpos($req, '://');
	       $this->_protocol = strtolower(substr($req, 0, $pos));

	       $req = substr($req, $pos+3);
	       $pos = strpos($req, '/');
	       if($pos === false)
	           $pos = strlen($req);
	       $host = substr($req, 0, $pos);

	       if(strpos($host, ':') !== false)
	       {
	           list($this->_host, $this->_port) = explode(':', $host);
	       }
	       else
	       {
	           $this->_host = $host;
	           $this->_port = ($this->_protocol == 'https') ? 443 : 80;
	       }

	       $this->_uri = substr($req, $pos);
	       if($this->_uri == '')
	           $this->_uri = '/';
	   }

	   // constructor
	   function HTTPRequest($url)
	   {
	       $this->_url = $url;
	       $this->_scan_url();
	   }

	   // download URL to string
	   function DownloadToString()
	   {
	       $crlf = "\r\n";

	       // generate request
	       $req = 'GET ' . $this->_uri . ' HTTP/1.0' . $crlf
	           .    'Host: ' . $this->_host . $crlf
	           .    $crlf;

	       // fetch
	       $this->_fp = fsockopen(($this->_protocol == 'https' ? 'ssl://' : '') . $this->_host, $this->_port);
	       fwrite($this->_fp, $req);
	       while(is_resource($this->_fp) && $this->_fp && !feof($this->_fp))
	           $response .= fread($this->_fp, 1024);
	       fclose($this->_fp);

	       // split header and body
	       $pos = strpos($response, $crlf . $crlf);
	       if($pos === false)
	           return($response);
	       $header = substr($response, 0, $pos);
	       $body = substr($response, $pos + 2 * strlen($crlf));

	       // parse headers
	       $headers = array();
	       $lines = explode($crlf, $header);
	       foreach($lines as $line)
	           if(($pos = strpos($line, ':')) !== false)
	               $headers[strtolower(trim(substr($line, 0, $pos)))] = trim(substr($line, $pos+1));

	       // redirection?
	       if(isset($headers['location']))
	       {
	           $http = new HTTPRequest($headers['location']);
	           return($http->DownloadToString($http));
	       }
	       else
	       {
	           return($body);
	       }
	   }
	}
}
#################################
##//End remote_include Function###
##################################


/*---------------------------------------------------------------------------------------------------------*
 _           _             _     ___        _  _     _
| |    __ _ | |_  ___  ___| |_  | _ ) _  _ (_)| | __| |
| |__ / _` ||  _|/ -_)(_-<|  _| | _ \| || || || |/ _` |
|____|\__,_| \__|\___|/__/ \__| |___/ \_,_||_||_|\__,_|

# RETURNS: Info array of latest stable build available
/*---------------------------------------------------------------------------------------------------------*/
function latest_build() {

	$installed = build_info();

	# Pull remote info on latest stable
	$stable_avail = 1;
   
	$stable_update = include_r("http://update.securexfer.net/public_builds/api-ultra_build_info-latest.php");
   	$stable_update = unserialize($stable_update);
   
	return $stable_update;

} // End update_avail() function


/*---------------------------------------------------------------------------------------------------------*
 _   _           _        _                _    _  _                       _
| | | | _ __  __| | __ _ | |_  ___  ___   /_\  | || | ___ __ __ __ ___  __| |
| |_| || '_ \/ _` |/ _` ||  _|/ -_)(_-<  / _ \ | || |/ _ \\ V  V // -_)/ _` |
 \___/ | .__/\__,_|\__,_| \__|\___|/__/ /_/ \_\|_||_|\___/ \_/\_/ \___|\__,_|
       |_|

# Checks branding options and various server config settings
# to see whether conditions allow for software updates
# RETURNS: 'true' on success, '[error array]' on failure --- based on whether auto updates are allowed or not
#
# TESTING NOTE: return $error array during development to see details
/*---------------------------------------------------------------------------------------------------------*/
function updates_allowed($geterrors = false) {

	# Make sure this site isn't running in 'Live Demo' mode
	if ( $_SESSION['demo_site'] == "yes" ) {
		$errors[] = "This site is configured for demo use only, and cannot access automatic version updates.";
	}
	
	
	# Check for safe_mode (because of shell_exec)
	if ( ini_get('safe_mode') == 1 || ini_get('safe_mode') == 'on' || ini_get('safe_mode') == 'On' || ini_get('safe_mode') == 'ON' ) {
		$errors[] = "<b>safe_mode = [".settype(ini_get('safe_mode'), "string")."]</b>: Cannot extract new build files when the php.ini directive 'safe_mode' is disabled.";
	}
	
	//   # Check allow_url_fopen
	//   if ( ini_get('allow_url_fopen') != 1 ) {
	//      $errors[] = "<b>allow_url_fopen = [".ini_get('allow_url_fopen')."]</b>: Cannot download new build files unless the php.ini directive allow_url_fopen is enabled.";
	//   }
	
	# Check branding options
	if ( $_SESSION['hostco']['software_updates'] == "OFF" ) {
		$errors[] = "<b>software_updates = ['".$_SESSION['hostco']['software_updates']."']</b>: Software Updates are disabled.";
	}
	
	if(!preg_match('/tra\.soholaunch\.com\/htdocs/', $_SESSION['docroot_path'])){
		# Return true if no errors
		if ( count($errors) > 0 ) {
			if ( $geterrors ) {
				return $errors;
			} else {
				return false;
			}
		} else {
			return true;
		}
   	} else {
		return false;
	}

} // End update_avail() function

/*---------------------------------------------------------------------------------------------------------*
 _   _           _        _            _              _  _        _     _
| | | | _ __  __| | __ _ | |_  ___    /_\ __ __ __ _ (_)| | __ _ | |__ | | ___
| |_| || '_ \/ _` |/ _` ||  _|/ -_)  / _ \\ V // _` || || |/ _` || '_ \| |/ -_)
 \___/ | .__/\__,_|\__,_| \__|\___| /_/ \_\\_/ \__,_||_||_|\__,_||_.__/|_|\___|
       |_|

# The sole purpose of this function is to check whether any 'new' updates are available
# Note: Only checks for new stable build
# Returns: true/false based on whether any new updates are available
/*---------------------------------------------------------------------------------------------------------*/
function update_avail() {
	
	$installed = build_info();
	
	# Pull remote info on latest stable
	$stable_avail = 1;
	
	$stable_update = include_r("http://update.securexfer.net/public_builds/api-ultra_build_info-stable.php");
	//			$filename = "http://update.securexfer.net/public_builds/api-ultra_build_info-stable.php";
	//			$r = new HTTPRequest("$filename");
	//echo $r->DownloadToString();
	//   	}
	
	$stable_update = unserialize($stable_update);
	if(updates_allowed()){
		# Check release dates of available updates against date of installed build (make sure update is newer)
		if ( $stable_avail == 1 && $installed['build_date'] < $stable_update['build_date'] ) {
			return $stable_update;
		} else {
			return false;
		}
	} else {
		return false;
	}

} // End update_avail() function
/*---------------------------------------------------------------------------------------------------------*
           _                   _      _
 ___ __ __| |_  _ _  __ _  __ | |_   | |_  __ _  ___
/ -_)\ \ /|  _|| '_|/ _` |/ _||  _|  |  _|/ _` ||_ /
\___|/_\_\ \__||_|  \__,_|\__| \__|___\__|\__, |/__|
                                  |___|   |___/

# PURPOSE: Extract pro edition install file (or any other tgz)...however it has to be done.
# ACCEPTS: Path to tgz file
# NOTE: Requires shell_exec to operate (so safe_mode has to be off)
/*--------------------------------------------------------------------------------------------------------*/
function extract_tgz($pathtofile) {

   # Preserve original working dir
   $odir = getcwd();

   # Switch wroking dir to tgz parent dir
   $parent_dir = dirname($pathtofile);
   chdir($parent_dir);

   # Make sure safe_mode is off (won't work on either OS without shell_exec)
   if ( ini_get('safe_mode') ) {
      $errors[] = "<b>safe_mode = [".settype(ini_get('safe_mode'), "string")."]</b>: Cannot extract file because the php.ini directive 'safe_mode' is enabled.";
   }

   # Normal Linux method or Windows work-around?
   if ( !eregi("WIN", PHP_OS) ) {
      # Extract via standard tar command
      $command_output = shell_exec("tar -xzvf ".$pathtofile);

      # Switch back to original working directory
      chdir($odir);

      if ( $command_output != "" ) {
         return true;
      } else {
         return false;
      }


   } else { // Windows

      # Build path to bundled .exe command files
      $SLASH = DIRECTORY_SEPARATOR; // Readability
      $GUNZIP_EXE = $_SESSION['docroot_path'].$SLASH."sohoadmin".$SLASH."program".$SLASH."includes".$SLASH."untar".$SLASH."gunzip.exe";
      $TAR_EXE = $_SESSION['docroot_path'].$SLASH."sohoadmin".$SLASH."program".$SLASH."includes".$SLASH."untar".$SLASH."tar.exe";

      # Decompress tgz file, Extract tar file
      $extract = "";
      $extract .= shell_exec($GUNZIP_EXE." -d ".$pathtofile);
      $extract .= shell_exec($TAR_EXE.' -xvf '.str_replace(".tgz", ".tar", $pathtofile));

      # Switch back to original working directory
      chdir($odir);

      # Did extract command succeed?
      if ( $extract == "" ) {
         return false;
      } else {
         return true;
      }

   } // End if Linux/Win

} // End extract tgz function




/*---------------------------------------------------------------------------------------------------------*
 ___  _                        ___            _              _
| _ \| | ___  __ _  ___ ___   / __| ___  _ _ | |_  __ _  __ | |_
|  _/| |/ -_)/ _` |(_-</ -_) | (__ / _ \| ' \|  _|/ _` |/ _||  _|
|_|  |_|\___|\__,_|/__/\___|  \___|\___/|_||_|\__|\__,_|\__| \__|

# Called mid-sentence by various error messages, etc
# when we want to direct the user to their webhost for help.
# RETURNS: Specific Company name as definied in Branding Controls OR generic 'your web hosting provider'
/*---------------------------------------------------------------------------------------------------------*/
function please_contact() {
   if ( $_SESSION['hostco']['company_name'] != "" && $_SESSION['hostco']['company_name'] != "Soholaunch" ) {
      return $_SESSION['hostco']['company_name'];
   } else {
      return "your web hosting provider";
   }
}

/*---------------------------------------------------------------------------------------------------------*
 ___               _  _
| _ ) _  _  _  _  | \| | ___ __ __ __
| _ \| || || || | | .` |/ _ \\ V  V /
|___/ \_,_| \_, | |_|\_|\___/ \_/\_/
            |__/
# Returns url for buy now buttons that appear throughout the product when all mods are not enabled
/*---------------------------------------------------------------------------------------------------------*/
function buynow_onclick() {
   # BUY NOW: Custom link
   if ( $_SESSION['hostco']['upgrades'] != "soholaunch" && $_SESSION['hostco']['buy_now'] == "custom" ) { // Custom link
      $buy_now_goto = "window.open('http://".$_SESSION['hostco']['buy_now_url']."');";

   # BUY NOW: Soholaunch website
   } elseif ( ($_SESSION['hostco']['upgrades'] == "soholaunch" || $_SESSION['hostco']['buy_now'] == "soholaunch") || ($_SESSION['hostco']['buy_now'] == "" && ($hostco_email == "" || $hostco_email == "sales@domain.com")) ) {
      $buy_now_goto = "window.open('http://buysingle.soholaunch.com/index.php?user_domain=".$_SERVER['HTTP_HOST']."');";

   # BUY NOW: Upgrade Request form
   } elseif ( $_SESSION['hostco']['buy_now'] == "upgrade_form" || ($hostco_email != "" && $hostco_email != "sales@domain.com") ) { // Upgrade form

      # Make sure we've got an email to send form data to
      if ( $_SESSION['hostco']['upgrade_request_email'] != "" ) {
         $form_goes_to = $_SESSION['hostco']['upgrade_request_email'];
      } else {
         $form_goes_to = $hostco_email;
      }

      $buy_now_goto = "parent.body.location.href='../marketing/promotion.php?todo=upgrade_form&sendto=".$form_goes_to."&mod=$mod';";
   }

   return $buy_now_goto;

} // End buynow_onclick() function


/*---------------------------------------------------------------------------------------------------------*
 ___       _  _  __   __              _
| __|_  _ | || | \ \ / /___  _ _  ___(_) ___  _ _
| _|| || || || |  \ V // -_)| '_|(_-<| |/ _ \| ' \
|_|  \_,_||_||_|   \_/ \___||_|  /__/|_|\___/|_||_|

# RETURNS: true/false depending on whether all advanced modules are enabled (licensed)
/*---------------------------------------------------------------------------------------------------------*/
function full_version() {

   $donthave = 0;

   # Check that each mod is enabled
   foreach ( $_SESSION['keyfile'] as $mod=>$status ) {
      if ( $status == "disabled" ) { $donthave++; }
   }

   if ( $donthave === 0 ) {
      return true;
   } else {
      return false;
   }
}


/*---------------------------------------------------------------------------------------------------------*
 ___  _              _               _    _  _                       _
| _ \| | _  _  __ _ (_) _ _   ___   /_\  | || | ___ __ __ __ ___  __| |
|  _/| || || |/ _` || || ' \ (_-<  / _ \ | || |/ _ \\ V  V // -_)/ _` |
|_|  |_| \_,_|\__, ||_||_||_|/__/ /_/ \_\|_||_|\___/ \_/\_/ \___|\__,_|
              |___/

# CHECKS STUFF
# > Plugins not disabled in branding options
#
# RETURNS: true/false depending on whether conditions are met to allow for plugins
/*---------------------------------------------------------------------------------------------------------*/
function plugins_allowed() {

   $problems = 0;

//   # All standard modules enabled?
//   if ( !full_version() ) {
//      $problems++;
//   }

   if ( $_SESSION['hostco']['plugins'] == "OFF" ) {
      $problems++;
   }

   if ( $problems === 0 ) {
      return true;
   } else {
      return false;
   }
}

function supersearch_allowed() {
	return true;
}

/*---------------------------------------------------------------------------------------------------------*
          _                               _                  _
 __ _  __| |__ __ __ _  _ _   __  ___  __| |  _ __   ___  __| | ___
/ _` |/ _` |\ V // _` || ' \ / _|/ -_)/ _` | | '  \ / _ \/ _` |/ -_)
\__,_|\__,_| \_/ \__,_||_||_|\__|\___|\__,_| |_|_|_|\___/\__,_|\___|

# Starting to call this instead of checking getSpec[dev_mode] directly
# ...ultimately this kind of stuff should handled through a user prefs menu
/*---------------------------------------------------------------------------------------------------------*/
function advanced_mode() {
   if ( eregi("\.soholaunch.com$", $_SERVER['SERVER_NAME']) || eregi("imadev", $_SESSION['getSpec']['dev_mode']) ) {
      return true;
   } else {
      return false;
   }
}

# Enables various dev options, error message display, etc
# Created for Soholaunch staff use -- may turn into a documented tool for addon developers later on
function debug_mode() {
   if ( eregi("soholaunch.com$", $_SERVER['SERVER_NAME']) ) {
      return true;
   } else {
      return false;
   }
}


/*---------------------------------------------------------------------------------------------------------*
  __  _           _     _             _
 / _|(_) _ _  ___| |_  | | ___  __ _ (_) _ _
|  _|| || '_|(_-<|  _| | |/ _ \/ _` || || ' \
|_|  |_||_|  /__/ \__| |_|\___/\__, ||_||_||_|
                               |___/
# Is user logging-in for the first time on a brand-new installation?
# The basic idea of this function is to try to prove that this is NOT the first login
/*---------------------------------------------------------------------------------------------------------*/
function first_login() {
   # Preserve original working dir
   $odir = getcwd();

   # Has QuickStart Wizard been run/skipped?
   if ( file_exists($_SESSION['document_root']."sohoadmin/filebin/nowiz.txt") ) {
      return false;
   }

   # Have main system tables already been created?
   if ( table_exists("site_specs") || table_exists("site_pages") ) {
      return false;
   }

   # Shouldn't get to this line unless all attempts to prove that this is NOT the first login failed
   return true;
}


/*---------------------------------------------------------------------------------------------------------*
 _____      _        _
|_   _|_ _ (_) __ _ | |
  | | | '_|| |/ _` || |
  |_| |_|  |_|\__,_||_|

# These functions apply to full version trial licenses (aka newschool orphan keys)
# They should only be called along with a check that $_SESSION['product_mode'] == "trial"
/*---------------------------------------------------------------------------------------------------------*/
# RETURNS: true/false based on whether full version trial period has
function trial_expired() {
   if ( $_SESSION['trial_expires'] < time() ) {
      return true;
   } else {
      return false;
   }
}
function trial_timeleft($biggestonly = true) {
   $units = array();
   $diff = ($_SESSION['trial_expires'] - time());

   $indays = $diff / 60 / 60 / 24;
   $units['days'] = floor($indays);
   $inhours = ($indays - $units['days']) * 24;
   $units['hours'] = floor($inhours);
   $inmins = ($inhours - $units['hours']) * 60;
   $units['minutes'] = floor($inmins);
   $insecs = ($inmins - $units['minutes']) * 60;
   $units['seconds'] = floor($insecs);

   # Return largest applicable unit of measure only (don't rush them, let them play)
   $time_string = "";

   if ( $biggestonly ) {
      # Biggest time measure only
      foreach ( $units as $unit=>$value ) {
         if ( $value > 0 ) {
            $time_string = $value." ".$unit.", ";
            break;
         }
      }
      $time_string = substr($time_string, 0, -2);

   } else {
      # Full time string
      foreach ( $units as $unit=>$value ) {
         if ( $value > 0 ) {
            # Special small font for seconds
            if ( $unit == "seconds" ) {
               $time_string .= "<span style=\"font-size: 85%;\">".$value."".substr($unit, 0, 1)."</span>";
            } else {
               $time_string .= "".$value."".substr($unit, 0, 1).":";
            }
         }
      }
//      $time_string = substr($time_string, 0, -1);
   }

   return $time_string;
}


/*=======================================================================================================================*
 __  __        _          ___        _    _
|  \/  | __ _ | |__ ___  | _ ) _  _ | |_ | |_  ___  _ _
| |\/| |/ _` || / // -_) | _ \| || ||  _||  _|/ _ \| ' \
|_|  |_|\__,_||_\_\\___| |___/ \_,_| \__| \__|\___/|_||_|

>> Accepts an id, label, css class, and onClick action
<< Returns complete html <input> tag
-----
- Primary button types: btn_goto, btn_show, btn_edit, btn_save, btn_warn, btn_delete
- Top menu bar buttons: nav_main, nav_save, nav_warn, nav_logout
/*=======================================================================================================================*/
function mkbutton( $id, $label, $class = "btn_goto", $onClick = "", $make = "button" ) {


   // Flip background image onMouseover
   //========================================================
   $imgPath = "http://".$_SESSION['docroot_url']."/sohoadmin/program/includes/display_elements/graphics/";
   $imgOn = "btn-".$class."-on.jpg";
   $imgOff = "btn-".$class."-off.jpg";

   // Rollover code and properties
   $mOver = "onmouseover=\"this.style.backgroundImage='url(".$imgPath.$imgOn.")';\"";
   $mOut = "onmouseout=\"this.style.backgroundImage='url(".$imgPath.$imgOff.")';\"";
   $rollOver = $mOver." ".$mOut;

   // Format onClick if passed
   if ( !eregi("onClick", $onClick) ) { $onClick = " onClick=\"".$onClick."\""; }

   # Internationalize text label and replace spaces with nbsp to prevent occassional buggy wrapping of button text
   $label = str_replace(" ", "&nbsp;", lang("$label"));

   $dBtn = "<!---$label--->\n";
   $dBtn .= "<div class=\"".$class."\" ".$mOver." ".$mOut." ".$onClick." ";
   $dBtn .= "style=\"background-image: url(".$imgPath.$imgOff."); display: block; height: 16px; padding: 0px 15px 0px 15px; border: 1px solid #000; border-style: none solid solid solid; margin: 0;\" nowrap>";

   $dBtn .= "<div style=\"display: block; vertical-align: top; padding-top: 2px; margin: 0;\" nowrap>".$label."</div>";
   $dBtn .= "</div>\n";


	if($class == 'nav_save'){
		$dBtn = "<a href=\"javascript:void(0);\" class=\"greenButton\" ".$onClick."><span>".$label."</span></a>\n";
		return $dBtn;
	} elseif($class == 'nav_main'){
		$dBtn = "<a href=\"javascript:void(0);\" class=\"blueButton\" ".$onClick."><span>".$label."</span></a>\n";
		return $dBtn;
	} elseif($class == 'submitBtnOff'){
		$dBtn = "<a href=\"javascript:void(0);\" class=\"redButton\" ".$onClick."><span>".$label."</span></a>\n";
		return $dBtn;
		
	} else {	
		if ( $make == "button" ) {
			return $dBtn;
		} elseif ( $make == "rollover" ) {
			return $rollOver;
		}
	}

}

# shell_exec_allowed()
# RETURNS: if no argument passed: true/false
#          if $stringreturn passed: "enabled"/"disabled"
function shell_exec_allowed($stringreturn = false) {
   $output = shell_exec('echo hi');
   if ( eregi("disabled for security", $output) || eregi('SHELL_EXEC', ini_get('disabled_functions')) ) {
      if ( $stringreturn ) { return "disabled"; } else { return false; }
   } else {
      if ( $stringreturn ) { return "enabled"; } else { return true; }
   }
}

# url_fopen_allowed()
# RETURNS: if no argument passed: true/false
#          if $stringreturn passed: "enabled"/"disabled"
function url_fopen_allowed($stringreturn = false) {
   if ( strtolower(ini_get('allow_url_fopen')) == "off" || ini_get('allow_url_fopen') == "0" ) {
      if ( $stringreturn ) { return "disabled"; } else { return false; }
   } else {
      if ( $stringreturn ) { return "enabled"; } else { return true; }
   }
}


# plugin_strip_strings()
# Checks whether any strip strings are defined in host branding controls and strips them from $haystack
# $haystack could be a plugin title, main menu button caption, or even module html container
# Initially written for use in plugin_manager.php and main_menu.php
function plugin_strip_strings($haystack) {
   # Strip string(s) defined?
   if ( $_SESSION['hostco']['plugin_strip_strings'] != "" ) {
      if ( strpos($_SESSION['hostco']['plugin_strip_strings'], ";") !== false ) {
         # MULTIPLE strings separated by semicolon
         $stripstrings = explode(";", $_SESSION['hostco']['plugin_strip_strings']);
         while ( list($key, $val) = each($stripstrings) ) {
            $haystack = str_replace($val, "", $haystack);
         }

      } else {
         # SINGLE strip string
         $haystack = str_replace($_SESSION['hostco']['plugin_strip_strings'], "", $haystack);
      }
   } // End if strip strings != ""

   return $haystack;

} // End plugin_strip_strings()


# hascurl()
# Returns true/false based on whether cUrl module is installed on server
# (more and more areas of the product starting to utilize curl where possible, and some like SitePal flat-out depend on it)
function hascurl() {
   if ( !function_exists("curl_setopt") ) {
      return false;
   } else {
      return true;
   }
}




# ftp_chmod()?
# Define if not available (i.e. if not on PHP 5+)
if (!function_exists('ftp_chmod')) {
    function ftp_chmod($ftp_stream, $themode, $filename) {
        return ftp_site($ftp_stream, sprintf('CHMOD %o %s', $themode, $filename));
    }
}

# check_ftp()
# Attempts to open ftp connection using saved username/pass
# REQUIRED ARGS: None. Pulls ftp_user/pass from db by default
# OPTIONAL: Pass specific username and password
# RETURNS: true/false based on whether ftp connection succeeds
function check_ftp($username = "", $password = "") {
   # Use settings from db?
   if ( $username == "" ) {
      $webmaster_pref = new userdata("webmaster_pref");
      $username = $webmaster_pref->get("ftp_username");
      $password = $webmaster_pref->get("ftp_password");
   }

	if (!function_exists('ftp_connect') || $username == '') {
		return false;
	} else {

	   # set up basic connection
	   $conn_id = ftp_connect(basename($_SERVER['HTTP_HOST']));

	   # login with username and password
	   $login_result = ftp_login($conn_id, $username, $password);

	   # check connection
	   if ( (!$conn_id) || (!$login_result) ) {
	   	return false;
	   } else {
	      return true;
	   }
	}
}


##########################################################################
##########################################################################
##	Check zlib library dependency ######################################
if (!function_exists('check_zlib_dep')){
	function check_zlib_dep(){
		if(phpversion() >= '5.2.0'){
			if(extension_loaded('zlib')){
				return true;
			} else {
				if ((ini_get('enable_dl') != 1) || (ini_get('safe_mode') == 1)) {
					return false;
				}			
				if (OS_WINDOWS) {
					$suffix = '.dll';
				} elseif (PHP_OS == 'HP-UX') {
					$suffix = '.sl';
				} elseif (PHP_OS == 'AIX') {
					$suffix = '.a';
				} elseif (PHP_OS == 'OSX') {
					$suffix = '.bundle';
				} else {
					$suffix = '.so';
				}
				if(dl('php_zlib'.$suffix) || @dl('zlib'.$suffix)){				
				}
				if(extension_loaded('zlib')) {
					return true;
				}
			}
		} else {
			return false;
		}
	}
}
##	End zlib dependency check ##########################################
##########################################################################
##########################################################################


##########################################################################
##########################################################################
##	Soholaunch unzip .zip function  ####################################
##	(unzips a .zip file to the directory that the .zip resides in, automaticaly overwrites any existing files on extract)
##	$result = soho_unzip('file_to_extract.zip');
##	returns array of files extracted on success or returns false on failure
if (!function_exists('soho_unzip')){
	function soho_unzip($zipFile, $unzipthese = ''){
		if(check_zlib_dep()){
			$currdir = getcwd();
			if($zipFile != basename($zipFile)){
				$npath = preg_replace('/'.basename($zipFile).'$/', '', $zipFile);
				if(is_dir($npath) && file_exists($npath.'/'.basename($zipFile))){
					chdir($npath);	
				}
			}
			$zipFile = basename($zipFile);
			$zip = new ZipArchive;
			if ($zip->open($zipFile) === TRUE) {
				if(is_array($unzipthese)){
					$zip->extractTo(getcwd(), $unzipthese);
				} else {
					$zip->extractTo(getcwd());	
				}
				
				unset($zip_files_ar);
				for($i=0;$i<$zip->numFiles;$i++){
					$zip_files_ar[] = $zip->getNameIndex($i);
				}
				$zip->close();
				chdir($currdir);
				return $zip_files_ar;
			} else {
				chdir($currdir);
				return false;
			}
		} else {
			$unz_error = "The extension '$extname' couldn't be found.\n".
			"Please make sure your version of PHP was built ".
			"with '$extname' support.\n";
			return false;	
		}
	}
}
##	End of Soholaunch soho_unzip() unzip function ######################
##########################################################################
##########################################################################

##########################################################################
##########################################################################
##	unzip images and stuff  ####################################
##	$result = soho_unzip('file_to_extract.zip');
##	returns array of files extracted on success or returns false on failure
if (!function_exists('unzip_files')){
	function unzip_files($zipFile, $unzipthese = ''){
		if(check_zlib_dep()){
			$currdir = getcwd();
			if($zipFile != basename($zipFile)){
				$npath = preg_replace('/'.basename($zipFile).'$/', '', $zipFile);
				if(is_dir($npath) && file_exists($npath.'/'.basename($zipFile))){
					chdir($npath);	
				}
			}
			chdir($currdir);
			$zip = new ZipArchive;
			if ($zip->open($zipFile) === true) {
				for($i = 0; $i < $zip->numFiles; $i++) {
					$filename = $zip->getNameIndex($i);
					$fileinfo = pathinfo($filename);
					$ext = end(explode(".", $fileinfo['basename']));
					if($ext=='jpg'||$ext=='jpeg'||$ext=='gif'||$ext=='png'||$ext=='bmp'||$ext=='tif'){
						unlink($_SESSION['doc_root'].'/images/'.$fileinfo['basename']);
						copy("zip://".$zipFile."#".$filename, $_SESSION['doc_root'].'/images/'.$fileinfo['basename']);
						$zip_files_ar[] = $_SESSION['doc_root'].'/images/'.$fileinfo['basename'];
					} else {
						unlink($_SESSION['doc_root'].'/media/'.$fileinfo['basename']);
						copy("zip://".$zipFile."#".$filename, $_SESSION['doc_root'].'/media/'.$fileinfo['basename']);
						$zip_files_ar[] = $_SESSION['doc_root'].'/media/'.$fileinfo['basename'];
					}
				}
				$zip->close();
				chdir($currdir);
				return $zip_files_ar;
			}
		} else {
			$unz_error = "The extension '$extname' couldn't be found.\n".
			"Please make sure your version of PHP was built ".
			"with '$extname' support.\n";
			return false;	
		}
	}
}
##	End of Soholaunch soho_unzip() unzip function ######################
##########################################################################
##########################################################################

##########################################################################
##########################################################################
##	Soholaunch Create .zip function  ###################################
##
##	example: soho_create_zip('newziptest.zip', '/folder/and/files/to/add/to/zip');
##	use * to add current directory or to add all files and folder in a directory without adding parent folder,
##	You can also pass the function an array of files/folders instead of passing a single file or folder
##	$result = ex; soho_create_zip('newziptest.zip', '/folder/and/files/to/add/to/zip/*');
##	$result = soho_create_zip('newziptest.zip', '*');
##	returns array of files added to .zip on success or returnse false on failure

if (!function_exists('soho_create_zip')){
	if(check_zlib_dep()){
		class Zipper extends ZipArchive {
			public function addDir($path){
				$added_ar[] = $path;
				$this->addEmptyDir($path);
				$nodes = array_merge(glob($path . '/.*'),glob($path.'/*'));
				foreach ($nodes as $node) {
					
					if (is_dir($node)) {
						$removedp = str_replace($path.'/', '', $node);
						if($removedp != '..' && $removedp != '.' && $removedp != '../'){
							$added_ar = array_merge($added_ar, $this->addDir($node));
						}
					} else if (is_file($node))  {
						$added_ar[] = $node;
						$this->addFile($node);
					}
				}
				return $added_ar;
			}
		} // class Zipper
	}
}

if (!function_exists('soho_create_zip')){
	function soho_create_zip($zipfilename, $directorys_to_zip){
		if(basename($zipfilename) == $zipfilename){
			$zipfilename = getcwd().'/'.$zipfilename;
		}
		if(check_zlib_dep()){

			if(file_exists($zipfilename)){	
				unlink($zipfilename);
			}
			$curdirr = getcwd();
			
			$zip = new Zipper;
			$res = $zip->open($zipfilename, ZipArchive::CREATE);
			if ($res === TRUE) {
				$files_added_list = array();
				if(is_array($directorys_to_zip)){
					$addthese = $directorys_to_zip;
				} else {
					$addthese = array();
					$directorys_to_zip = preg_replace('/\/$/', '', $directorys_to_zip);
					$addthese[] = $directorys_to_zip;
				}
				foreach($addthese as $directory_to_zip){
					chdir($curdirr);
					$directory_to_zip = preg_replace('/\/$/', '', $directory_to_zip);
					if(is_dir($directory_to_zip)){
						if(preg_match('/^\//', $directory_to_zip)){
							unset($thedir_ar);
							$thedir_ar = explode('/', $directory_to_zip);
							if(count($thedir_ar) > 1){
								chdir($directory_to_zip);
								chdir('../');
								$directory_to_zip = array_pop($thedir_ar);
							}
						}
						$files_added_list = array_merge($files_added_list, $zip->addDir($directory_to_zip));
					} else {
						unset($thedir_ar);
						$thedir_ar = explode('/', $directory_to_zip);
						if(count($thedir_ar) > 1){
							$thefile = array_pop($thedir_ar);
							if(!preg_match('/^\//', $directory_to_zip) && $thefile != '*'){
								$thefile = $directory_to_zip;
							}
						} else {
							$thefile = $directory_to_zip;
						}
						if($thefile == '*'){
							$gotodir = str_replace('*', '', $directory_to_zip);
							if(is_dir($gotodir)){
								chdir($gotodir);	
							}
							$nodes = array_merge(glob(getcwd().'/.*'),glob(getcwd().'/*'));
							foreach ($nodes as $node) {								
								$node = str_replace(getcwd().'/', '', $node);
								if($node != '..' && $node != '.' && $node != '../'){
									if(!is_dir($node)){
										$files_added_list[] = $node;
										$zip->addFile($node);
									} else {
										$files_added_list = array_merge($files_added_list, $zip->addDir($node));
									}
								}
							}
						} else {
							if(file_exists($directory_to_zip)){
								$files_added_list[] = $thefile;
								$zip->addFile($directory_to_zip, $thefile);
							} else {
								//ERROR File doesn't exist	
							}
						}
					}
				}
				$zip->close();
				chdir($curdirr);
				if(file_exists($zipfilename)){
					return $files_added_list;
				} else {
					return false;	
				}
			} else {
				return false;
			}
		} else {
			$unz_error = "The extension '$extname' couldn't be found.\n".
			"Please make sure your version of PHP was built ".
			"with '$extname' support.\n";
			return false;	
		}
	}
}
##	End of Soholaunch soho_create_zip() Create .zip function ###########
##########################################################################
##########################################################################


/*---------------------------------------------------------------------------------------------------------*
    ______       __                      __     _____    _
   / ____/_  __ / /_ _____ ____ _ _____ / /_   /__  /   (_)____
  / __/  | |/_// __// ___// __ `// ___// __/     / /   / // __ \
 / /___ _>  < / /_ / /   / /_/ // /__ / /_      / /__ / // /_/ /
/_____//_/|_| \__//_/    \__,_/ \___/ \__/     /____//_// .___/
                                                       /_/
/*---------------------------------------------------------------------------------------------------------*/

function unZip($pathtofile, $unzipthese = '') {
   # Preserve original working dir
   
	return soho_unzip($pathtofile, $unzipthese);
//   
//   $odir = getcwd();
//
//
//   # Switch working directory to folder containing zip file
//	$parent_folder = eregi_replace(basename($pathtofile), "", $pathtofile);
//	chdir($parent_folder);
//	$SLASH = DIRECTORY_SEPARATOR; // Readability
//	$IISupload = $_SESSION['doc_root'].$SLASH."sohoadmin".$SLASH."program".$SLASH."modules".$SLASH."site_templates".$SLASH."unzip";
//   # Extract via WIN or LINUX method?
//   if ( eregi("WIN", PHP_OS) ) {
//      if ( !is_dir($IISupload) ) {
//
//	mkdir($IISupload);
//         $sshRez .= exec ("expand -r -F:*.* ..\unzip.cab ..\unzips\ ");
//         $sshRez .= exec ("del ..\unzip.cab");
//      }
//
//      $sshRez = shell_exec($IISupload.$SLASH."unzip.exe -o -L ".basename($pathtofile));
//
//   } else {
//      $sshRez = shell_exec("unzip -o ".basename($pathtofile));
//   }
//
//   # Switch back to original working directory
//   chdir($odir);
//
//   return $sshRez;

}

##########################################################################
##########################################################################
##	mysqlBackup Create mysql backup SQL function  ######################
##
##	backup_tables('db-backup.sql');
if (!function_exists('mysqlBackup')){
	function mysqlBackup($backupfile, $tables = '*'){
		//$tables = '*';
//		//get all of the tables
		if($tables == '*'){
			$tables = array();
			$result = mysql_query('SHOW TABLES');
			while($row = mysql_fetch_row($result)){
				$tables[] = $row[0];
			}
		} else {
			$tables = is_array($tables) ? $tables : explode(',',$tables);
		}
		
		//cycle through
		foreach($tables as $table){
			$result = mysql_query('SELECT * FROM '.$table);
			$num_fields = mysql_num_fields($result);
			$return.= 'DROP TABLE IF EXISTS `'.$table.'`;';
			$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
			$return.= "\n".$row2[1].";\n\n";
			$locktoggle = '1';
			for ($i = 0; $i < $num_fields; $i++) {
				while($row = mysql_fetch_row($result)){
					if($locktoggle == '1'){
						$return.= 'LOCK TABLES `'.$table.'` WRITE;'."\n";	
						$return.= '/*!40000 ALTER TABLE `'.$table.'` DISABLE KEYS */;'."\n";					
						$locktoggle = '0';
					}
					
					$return.= 'INSERT INTO '.$table.' VALUES(';
					for($j=0; $j<$num_fields; $j++) {
						$row[$j] = addslashes($row[$j]);
						$row[$j] = str_replace("\n",'\\n',$row[$j]);
						$row[$j] = str_replace("\r",'\\r',$row[$j]);
						if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
						if ($j<($num_fields-1)) { $return.= ','; }
					}
					$return.= ");\n";
				}
			}
			if($locktoggle == '0'){
				$return.= '/*!40000 ALTER TABLE `'.$table.'` ENABLE KEYS */;'."\n";
				$return.= 'UNLOCK TABLES;'."\n";
			}
			$return.="\n\n\n";
		}
		
		//save file
		$handle = fopen($backupfile,'w+');
		if(fwrite($handle,$return)){
			return true;	
		} else {
			return false;	
		}
		fclose($handle);
	}
}
##	End of mysqlBackup function                    ######################
##########################################################################
##########################################################################



##########################################################################
##########################################################################
##	mysqlImport Restore/Import SQL into mysql database function  #######
##
##	mysqlImport('db-backup.sql');
if (!function_exists('mysqlImport')){
	function mysqlImport($filename){
		$query = '';
		$handle = @fopen($filename, "r");
		if($handle){
			while(!feof($handle)){
				$query.= fgets($handle, 4096);
				$query = preg_replace("/^--.*/", '', $query);
				if($query = preg_replace("/^\/\*!4[0-9]{4}/", '', $query)){
					$query = str_replace(" */", '', $query);	
				}
				if(substr(rtrim($query), -1) == ';'){
					if(!mysql_query($query)){
	//					echo $query."<br/>".mysql_error()."<br/>";
					}
					$query = '';
				}
			}
			fclose($handle);
		}
		return true;
	}
}
##	End of mysqlImport function                    ######################
##########################################################################
##########################################################################



##########################################################################
##########################################################################
##	smart_resize_image function to resize images for web          #######
##	create new image smart_resize_image($img, 0, 0, false, $img2, false);
##	smart_resize_image( $imagefile, $newwidth = 0, $newheight = 0, $proportional = false, $output = 'newfilenamefile', $delete_original = true)

if(function_exists('imagecreatetruecolor') && !function_exists('smart_resize_image')){
	function smart_resize_image( $file, $width = 0, $height = 0, $proportional = false, $output = 'file', $delete_original = true, $rotate=0, $new_x=0, $new_y=0,$sel_w=0,$sel_h=0){

		
		$info = getimagesize($file);
		if ( $height <= 0 && $width <= 0 ) {
	//		return false;
			$width = $info['0'];
			$height = $info['1'];		
		}

		$image = '';
		$final_width = 0;
		$final_height = 0;
		list($width_old, $height_old) = $info;
		if ($proportional) {
			if ($width == 0){ 
				$factor = $height/$height_old;
			} elseif($height == 0){
				$factor = $width/$width_old;
			} else {
				$factor = min ( $width / $width_old, $height / $height_old);
			}
			$final_width = round ($width_old * $factor);
			$final_height = round ($height_old * $factor);		
		} else {
			$final_width = ( $width <= 0 ) ? $width_old : $width;
			$final_height = ( $height <= 0 ) ? $height_old : $height;
		}
		if($sel_w==0){
			$sel_w=$final_width;
			$sel_h=$final_height;
		}
		
		$image_resized = imagecreatetruecolor( $final_width, $final_height );
		$image_select = imagecreatetruecolor( $sel_w, $sel_h );

		switch($info[2]) {
			case IMAGETYPE_GIF:
				$image = imagecreatefromgif($file);
				break;
			case IMAGETYPE_JPEG:
				$image = imagecreatefromjpeg($file);
				break;
			case IMAGETYPE_PNG:
				$image = imagecreatefrompng($file);
				break;
			default:
				return false;
		}

		
		if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG)) {
			$trnprt_indx = imagecolortransparent($image);
			if ($trnprt_indx >= 0) {
				$trnprt_color    = imagecolorsforindex($image, $trnprt_indx);
				$trnprt_indx    = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
				$trnprt_indx2    = imagecolorallocate($image_select, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
				
				imagefill($image_resized, 0, 0, $trnprt_indx);
				imagefill($image_select, 0, 0, $trnprt_indx2);
				imagecolortransparent($image_resized, $trnprt_indx);
				imagecolortransparent($image_select, $trnprt_indx2);
			} elseif ($info[2] == IMAGETYPE_PNG) {
				imagealphablending($image_resized, false);
				imagealphablending($image_select, false);
				$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
				$color = imagecolorallocatealpha($image_select, 0, 0, 0, 127);
				imagefill($image_resized, 0, 0, $color);
				imagefill($image_select, 0, 0, $color);
				imagesavealpha($image_resized, true);
				imagesavealpha($image_select, true);
			}
		}

		imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $info['0'], $info['1']);
		
		$angle = 360 - $rotate;
		if($angle < 360 && $angle > 0){
			$image_resized = imagerotate($image_resized,$angle,-1);
			$final_width = imagesx($image_resized);
			$final_height = imagesy($image_resized);
			
		}
		if($angle < 360 && $angle > 0){
			$image_select = imagerotate($image_select,$angle,-1);
			$final_width = imagesx($image_select);
			$final_height = imagesy($image_select);
			
		}
		imagedestroy($image);
		imagecopy($image_select, $image_resized, 0, 0, $new_x, $new_y, $final_width, $final_height);



		if ($delete_original) {
			@unlink($file);
		}
		

		
		switch(strtolower($output)){
			case 'browser':
				$mime = image_type_to_mime_type($info[2]);
				header("Content-type: $mime");
				$output = NULL;
				break;
			case 'file':
				$output = $file;
				break;
			case 'return':
				return $image_select;
				break;
			default:
				@unlink($output);
				break;
		}
		switch ( $info[2] ) {
			case IMAGETYPE_GIF:
				imagegif($image_select, $output);
				break;
			case IMAGETYPE_JPEG:
				imagejpeg($image_select, $output);
				break;
			case IMAGETYPE_PNG:
				imagepng($image_select, $output);
				break;
			default:
				return false;
		}		
		
		return true;
	}
}

##	End of smart_resize_image function             ######################
##########################################################################
##########################################################################



##########################################################################
##########################################################################
##	updateCartCatCount function to update all cart categorys with their correct product count
function updateCartCatCount(){
	$getcats = mysql_query("SELECT * FROM cart_category ORDER BY category");
	while ($rowz = mysql_fetch_array ($getcats)) {
		if (strlen($rowz['category']) > 2) {
			$catz[$rowz['keyfield']]= array('name'=>$rowz['category'], 'level'=>$rowz['level'], 'subs'=>$rowz['subs'], 'parent'=>$rowz['parent']);		
		}
	}
	foreach($catz as $cvar=>$cval){		
		$catz[$cvar]['allsubs']=$cvar;		
		if($cval['subs']!=''){
			$catz[$cvar]['allsubs']= $catz[$cvar]['allsubs'].','.$cval['subs'];
			$thesubs = explode(',',$cval['subs']);
			foreach($thesubs as $sbval){
				if($catz[$sbval]['subs'] != ''){
					$catz[$cvar]['allsubs']=$catz[$cvar]['allsubs'].','.$catz[$sbval]['subs'];
				}
			}
		}
		$fq = mysql_query("select PRIKEY from cart_products where OPTION_INVENTORY_NUM > 0 and OPTION_DISPLAY='Y' and PROD_CATEGORY1 IN (".$catz[$cvar]['allsubs'].") OR PROD_CATEGORY2 IN (".$catz[$cvar]['allsubs'].") OR PROD_CATEGORY3 IN (".$catz[$cvar]['allsubs'].")");
		mysql_query("update cart_category set product_count='".mysql_num_rows($fq)."' where keyfield='".$cvar."'");
	}
	return true;
}
##	End of updateCartCatCount function             ######################
##########################################################################
##########################################################################

?>