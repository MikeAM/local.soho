<?php
error_reporting('341');

if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###===================================================================================================
### Core Functions and Classes - Included in all product files
###===================================================================================================



###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.7
##
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugz.soholaunch.com
## Community:     http://forum.soholaunch.com
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

# filpslashes()
# Used to convert old-style win/iis path with problematic backslases to new-style win/iis forward-slash-based path (which works way, way better and quirk-free)
function flipslashes($string) {
   $string = str_replace("\\", "/", $string);
   return $string;
}


function check_writeable($check_dir) {
   $check_file = $check_dir.'/test.txt';
   $file_stuff = fopen($check_file, "w");
   if(!fwrite($file_stuff, 'testing')) {
      fclose($file_stuff);
      return false;
   } else {
      fclose($file_stuff);
      unlink($check_file);
      return true;
   }
}

/*--------------------------------------------------------------------------------------------------------------------
 _         _         _
(_)_ _  __| |_  _ __| |___   __	 _ _
| | ' \/ _| | || / _` / -_) |__|	| '_|
|_|_||_\__|_|\_,_\__,_\___| 		|_|

Workaround for url-fopen
----------------------------------------------------------------------------------------------------------------------*/
if(!function_exists('include_r')){
	function include_r($url) {
		$req = $url;
		$pos = strpos($req, '://');
		$protocol = strtolower(substr($req, 0, $pos));
		$req = substr($req, $pos+3);
		$pos = strpos($req, '/');
		
		if($pos === false) {
			$pos = strlen($req);
		}
		
		$host = substr($req, 0, $pos);
		
		if(strpos($host, ':') !== false) {
			list($host, $port) = explode(':', $host);
		} else {
			$host = $host;
			$port = ($protocol == 'https') ? 443 : 80;
		}
		
		$uri = substr($req, $pos);
		if($uri == '') {
			$uri = '/';
		}
		
		$crlf = "\r\n";
		// generate request
		$req = 'GET ' . $uri . ' HTTP/1.0' . $crlf
		.    'Host: ' . $host . $crlf
		.    $crlf;
		
		// fetch
		$fp = fsockopen(($protocol == 'https' ? 'ssl://' : '') . $host, $port);
		fwrite($fp, $req);
		while(is_resource($fp) && $fp && !feof($fp)) {
			$response .= fread($fp, 1024);
		}
		fclose($fp);
		
		// split header and body
		$pos = strpos($response, $crlf . $crlf);
		if($pos === false) {
			return($response);
		}
		$header = substr($response, 0, $pos);
		$body = substr($response, $pos + 2 * strlen($crlf));
		
		// parse headers
		$headers = array();
		$lines = explode($crlf, $header);
		foreach($lines as $line) {
			if(($pos = strpos($line, ':')) !== false) {
				$headers[strtolower(trim(substr($line, 0, $pos)))] = trim(substr($line, $pos+1));
			}
		}
		// redirection?
		if(isset($headers['location'])) {
			//echo include_r($headers['location']);
			return(include_r($headers['location']));
		} else {
			//echo $body;
			return($body);
		}
	}	// End include_r function
}

if(!function_exists('include_R')){
	function include_R($url) {
		return include_r($url);
	}
}


/*------------------------------------------------------------------------------------------------------------------------*
 _               _
| |_   ___  ___ | |__
| ' \ / _ \/ _ \| / /
|_||_|\___/\___/|_\_\

# Create new hook that extensions can attach their includes to
# Hook ID's should be unique...
# ...I think there's a good chance hook_attach() will just require a hook id (no filename) in the future -MM 2.24.2006
# ...On second thought: requiring hook_file makes it easier to add hooks (since name can be in context of file)
# ...On thrid thought: Started building filename into hook id like "file.php:hookid"
/*------------------------------------------------------------------------------------------------------------------------*/
function hook($hook_id, $hook_file) {

   # Select rows matching passed hook_id from system_hook
   $select_qry = "select * from system_hook_attachments where";
   $select_qry .= " HOOK_ID = '".$hook_id."'";
   //$select_qry .= " and HOOK_FILE = '".$hook_file."'";

   # Run select query
   if ( !$result = mysql_query($select_qry) ) {
      //echo "MySQL Erorr ".__FILE__.": ".__LINE__." -- '".mysql_error()."'";  exit;
   }

   # Will contain php include statments for each hook attachment found
   $includes = "";

   # Build list of hook attachments as include statements
   while ( $getHooked = mysql_fetch_array($result) ) {
      $mod_file = $_SESSION['docroot_path']."/sohoadmin/plugins/".$getHooked['PLUGIN_FOLDER']."/".$getHooked['MOD_FILE'];
      $includes .= "include('".$mod_file."');\n";
   }

   # Return include statments
   return $includes;

} // End hook() function


/*---------------------------------------------------------------------------------------------------------------------------------*
                    _        _   _               _
 ___ _ __  ___  __ (_) __ _ | | | |_   ___  ___ | |__
(_-<| '_ \/ -_)/ _|| |/ _` || | | ' \ / _ \/ _ \| / /
/__/| .__/\___|\__||_|\__,_||_| |_||_|\___/\___/|_\_\
    |_|

# For special plugin functionality we pre-build to make things easier on developers
# ...and to help prevent multiple extensions breaking some area of the product (ie add-on page editor objects)
#
# >> ACCEPTS: String hook type..which is just the unique string used to identify that particular hook
#
# DOES STUFF:
# -selects all records from hook table matching passed hook type
# -pulls and restores their serialized data array from HOOK_DATA field
# -places data array for each attachment into multidimensional parent array
#
# << RETURNS: Multidimensional parent array containing restored data array for each extension ustilizing passed special hook name
/*---------------------------------------------------------------------------------------------------------------------------------*/
function special_hook($hook_type) {

   # Select rows matching passed hook_id from system_hook
   $select_qry = "select * from system_hook_attachments where";
   $select_qry .= " HOOK_TYPE = '".$hook_type."'";

   # Run select query
   if ( !$result = mysql_query($select_qry) ) {
      //echo "MySQL Erorr ".__FILE__.": ".__LINE__." -- '".mysql_error()."'";  exit;
   }

   # Will contain data array for each attachment found
   $addon_data = array();
   $k = 0; // Array key number

   # Build list of hook attachments as include statements
   while ( $getHooked = mysql_fetch_array($result) ) {
      $addon_data[$k] = unserialize($getHooked['HOOK_DATA']);
      $addon_data[$k]['plugin_folder'] = $getHooked['PLUGIN_FOLDER'];
      $k++;
   }

   # Return include statments
   return $addon_data;

} // End hook() function


/*---------------------------------------------------------------------------------------------------------*
 _             _      _
| |_  ___  ___| |_   /_\   _ _  _ _  __ _  _  _
|  _|/ -_)(_-<|  _| / _ \ | '_|| '_|/ _` || || |
 \__|\___|/__/ \__|/_/ \_\|_|  |_|  \__,_| \_, |
                                           |__/
# Ouputs contents of passed array in html table
/*---------------------------------------------------------------------------------------------------------*/
function testArray($array, $fixedheight = false) {
   $arrTable = "";
   $arrTable .= "<b>testArray output...</b><br>\n";
   if ( $fixedheight ) {
      $containerstyle = "height: ".$fixedheight."px;overflow: auto;";
   }
   $arrTable .= "<div style=\"".$containerstyle."\">\n";
   $arrTable .= "<table class=\"content\" border=\"0\" cellspacing=\"0\" cellpadding=\"8\" style=\"font: 10px verdana; border: 1px solid #000;\">\n";

   # Loop through array
   foreach ( $array as $var=>$val ) {

      # Alternate background colors
      if ( $bg == "#FFFFFF" ) { $bg = "#EFEFEF"; } else { $bg = "#FFFFFF"; }

      # Prevent empty table cells
      if ( $val == "" ) { $val = "&nbsp;"; }

      # Format long strings into scrollable div boxes
      if ( strlen($val) > 40 ) {
         $val = "<div style=\"width: 400px; height: 60px; overflow: scroll; color: red;\">".$val."</div>\n";
      }

      # Try to bust out sub-arrays
      if ( is_array($val) ) {
         $showVal = "";

         foreach ( $val as $vKey=>$vVal ) {
            $showVal .= "<span style=\"color: #2E2E2E;\">".$vKey."</span> = <span style=\"color: #F75D00;\">".$vVal."</span><br>";
         }
         $val = $showVal;
      }

      # Spit out table row
      $arrTable .= " <tr>\n";
      $arrTable .= "  <td style=\"vertical-align: top;background-color:".$bg.";\" align=\"left\"><b>".$var."</b></td>\n";
      $arrTable .= "  <td style=\"background-color:".$bg.";\"><span style=\"color: red;\">".$val."</span></td>\n";
      $arrTable .= " </tr>\n";
   }
   $arrTable .= "</table>";
   $arrTable .= "</div>\n";

   return $arrTable;
}


/*---------------------------------------------------------------------------------------------------------*
 _             _      _                           ___
| |_  ___  ___| |_   /_\   _ _  _ _  __ _  _  _  |_  )
|  _|/ -_)(_-<|  _| / _ \ | '_|| '_|/ _` || || |  / /
 \__|\___|/__/ \__|/_/ \_\|_|  |_|  \__,_| \_, | /___|
                                           |__/
# Ouputs contents of passed array in html table COLUMNS - testArray() outputs in rows
/*---------------------------------------------------------------------------------------------------------*/
function testArray2 ( $array, $exclude_numeric_keys = "no" ) {
   $arrTable = "";
   $arrTable .= "<hr>\n";
   $arrTable .= "<b>testArray2 output...</b><br>\n";
   $arrTable .= "<table class=\"content\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\" style=\"font: 10px verdana; border: 1px solid #000;\">\n";

   # Header row for key names, data row for values
   $row1 = " <tr>\n";
   $row2 = " <tr>\n";

   # Loop through array
   foreach ( $array as $var=>$val ) {

      # Exclude numeric keys? (like from mysql_fetch_array)
      if ( ($exclude_numeric_keys == "yes" && !is_numeric($var)) || $exclude_numeric_keys != "yes" ) {

         # Prevent empty table cells
         if ( $val == "" ) { $val = "&nbsp;"; }

         # Format long strings into scrollable div boxes
         if ( strlen($val) > 40 ) { $val = "<div style=\"width: 100px; height: 60px; overflow: scroll; color: red;\">".$val."</div>\n"; }

         # Add column to header row
         $row1 .= "  <td style=\"background-color: #CCC;\" align=\"left\"><b>".$var."</b></td>\n";

         # Try to bust out sub-arrays
         if ( is_array($val) ) {
            $showVal = "";

            foreach ( $val as $vKey=>$vVal ) {
               $showVal .= "<span style=\"color: #2E2E2E;\">".$vKey."</span> = <span style=\"color: #F75D00;\">".$vVal."</span><br>";
            }
         } else {
            $showVal = $val;
         }

         # Add column to data row
         $row2 .= "  <td style=\"background-color:".$bg.";\"><span style=\"color: red;\">".$showVal."</span></td>\n";

      } // End if not numeric key or if numerics allowed

   }

   # Close header & data rows
   $row1 .= " </tr>\n";
   $row2 .= " </tr>\n";

   # Add rows to table html
   $arrTable .= $row1;
   $arrTable .= $row2;

   $arrTable .= "</table>";
   $arrTable .= "<hr>\n";

   return $arrTable;
}

/*=============================================================================================*
 ___                       ___  _       _     _
| __| _ _  _ _  ___  _ _  | __|(_) ___ | | __| | ___
| _| | '_|| '_|/ _ \| '_| | _| | |/ -_)| |/ _` |(_-<
|___||_|  |_|  \___/|_|   |_|  |_|\___||_|\__,_|/__/

- Checks list of error fields generated by unsucessful form submit and highlights them
/*=============================================================================================*/
function errchk($chkfor, $display, $errvar = "noDice") {
   global ${$errvar};
   $eVar = ${$errvar};

   if ( eregi($chkfor, $eVar) ) {
      $rezult = "<span id=\"nodice\">".$display."</span>";
   } else {
      $rezult = $display;
   }
   return $rezult;

}


/*=============================================================================================*
 ___                   _  _                _
| __|___  _ _  _ __   | || | ___  __ _  __| | ___  _ _
| _|/ _ \| '_|| '  \  | __ |/ -_)/ _` |/ _` |/ -_)| '_|
|_| \___/|_|  |_|_|_| |_||_|\___|\__,_|\__,_|\___||_|

- Accepts form name, action, hidden field name=>value array, and optionally an ID and method
- Returns <form> tag and hidden <input> fields
- Note: This function is a tad overzealous and will probably go away in the future
/*=============================================================================================*/
function formtop($name, $action, $hidn, $idtag = "", $method = "post") {

   if ( $idtag != "" ) { $idtag = " id=\"".$idtag."\""; }

   $formhdr = "<form name=\"".$name."\" action=\"".$action."\" method=\"".$method."\"".$idtag.">\n";

   foreach ( $hidn as $nam=>$val ) {
      $formhdr .= "<input type=\"hidden\" name=\"".$nam."\" value=\"".$val."\">";
   }

   return $formhdr;

}


/*=============================================================================================*
 ___       _           _      ___                           _
/ __| ___ | | ___  __ | |_   / __|_  _  _ _  _ _  ___  _ _ | |_
\__ \/ -_)| |/ -_)/ _||  _| | (__| || || '_|| '_|/ -_)| ' \|  _|
|___/\___||_|\___|\__| \__|  \___|\_,_||_|  |_|  \___||_||_|\__|

>> Accepts name of variable to check (i.e. field name), semicolon-delim values and labels
<< Outputs <option> tags and sets 'selected' based on current value of field variable
/*=============================================================================================*/
function mkopts($field,$values,$labels,$default = "") {
   global ${$field};
   $options = ""; // Will contain html <option>'s

   # What value should this list check against?
   if ( ${$field} == "" ) { $chkfor = $default; } else { $chkfor = ${$field}; }

   # Convert option data to arrays
   $val = explode(";", $values);
   $lab = explode(";", $labels);

   # Build html option list
   for ( $s = 0; $s < count($lab); $s++ ) {
      if ( $val[$s] == $chkfor ) {
         $options .= "<option value=\"".$val[$s]."\" selected>".$lab[$s]."</option>\n"; // Selected option
      } else {
         $options .= "<option value=\"".$val[$s]."\">".$lab[$s]."</option>\n";
      }
   }

   return $options;

}


/*=============================================================================================*
    _                   ___           _        _       _    _            _
 _ | | __ _ __ __ __ _ / __| __  _ _ (_) _ __ | |_    /_\  | | ___  _ _ | |_
| || |/ _` |\ V // _` |\__ \/ _|| '_|| || '_ \|  _|  / _ \ | |/ -_)| '_||  _|
 \__/ \__,_| \_/ \__,_||___/\__||_|  |_|| .__/ \__| /_/ \_\|_|\___||_|   \__|
                                        |_|

- Returns self-contained code for javascript alert box containing the passed string.
/*=============================================================================================*/
function js_alert($text = "something") {
   $box = "\n<script language=\"javascript\">";
   $box .= "window.alert('".$text."');";
   $box .= "</script>\n";

   return $box;
}


/*=============================================================================================*
 __  __  _                 _                                _        _
|  \/  |(_) ___ __      _ | | __ _ __ __ __ _  ___ __  _ _ (_) _ __ | |_
| |\/| || |(_-</ _| _  | || |/ _` |\ V // _` |(_-</ _|| '_|| || '_ \|  _|
|_|  |_||_|/__/\__|(_)  \__/ \__,_| \_/ \__,_|/__/\__||_|  |_|| .__/ \__|
                                                              |_|

- Returns passed string wrapped in script tags.
/*=============================================================================================*/
function jscall($stuff) {
   $js = "\n<script language=\"javascript\">";
   $js .= " ".$stuff;
   $js .= "</script>\n";

   return $js;
}



/*=======================================================================================================================*
 _     _      _     ___   _                _
| |   (_) ___| |_  |   \ (_) _ _  ___  __ | |_  ___  _ _  _  _
| |__ | |(_-<|  _| | |) || || '_|/ -_)/ _||  _|/ _ \| '_|| || |
|____||_|/__/ \__| |___/ |_||_|  \___|\__| \__|\___/|_|   \_, |
                                                          |__/
<< Returns multi-dem assoc array describing all files (and directories) below the directory passed in $path
/*=======================================================================================================================*/
function dirlist($path, $static_output = false ) {
   $infolder = array();

   # Open dir and loop through files
   if ($dirhan = opendir($path)) {
      while (($file = readdir($dirhan)) != false) {
         $fpath = $path."/".$file;

         # Index as 'dirs' or as 'files'?
         if ( filetype($fpath) == "dir" ) { $index = "dirs"; } elseif ( filetype($fpath) == "file" ) { $index = "files"; }

         # Store stat information in info array
         $mdate = getdate(filemtime($fpath));
         $perms = substr(sprintf('%o', fileperms($fpath)), -4);
         $fsize = filesize($fpath);
         $fsize = $fsize / 1024;
         $fsize = sprintf("%01.2f", $fsize);
         $fsize .= " KB";
         $infolder[$index][$file] = array ( 'name'=>$file, 'size'=>$fsize, 'date'=>$mdate, 'perms'=>$perms, 'path'=>$fpath );

      } // End loop through dir contents
      closedir($dirhan);

   } else {
      //$infolder = "invalid directory path";

   } // End dir read attempt


   /// Return data in requested format
   ###=================================================================
   if ( !$static_output ) {

      // Return array of organized dir contents
      //=============================================
      return $infolder;

   } else {

      // Return in formatted html table
      //=============================================
      $output = "<table border=\"0\" cellpadding=\"8\" cellspacing=\"0\" style=\"background-color: #E8ECEF;\">\n";
      $output .= " <tr>\n";
      $output .= "  <td style=\"border-bottom: 1px solid #2E2E2E; font-style: bold;\">\n";
      $output .= "   Name\n";
      $output .= "  </td>\n";
      $output .= "  <td style=\"border-bottom: 1px solid #2E2E2E; font-style: bold;\">\n";
      $output .= "   Size\n";
      $output .= "  </td>\n";
      $output .= "  <td style=\"border-bottom: 1px solid #2E2E2E; font-style: bold;\">\n";
      $output .= "   Date\n";
      $output .= "  </td>\n";
      $output .= "  <td style=\"border-bottom: 1px solid #2E2E2E; font-style: bold;\">\n";
      $output .= "   Permissions\n";
      $output .= "  </td>\n";
      $output .= " </tr>\n";

      # Show directories at top in blue
      #---------------------------------------------------
      foreach( $infolder['dirs'] as $dir ) {
         $output .= " <tr>\n";
         $output .= "  <td style=\"color: #0066FF;\">\n";
         $output .= "   / ".$dir['name']."\n";
         $output .= "  </td>\n";
         $output .= "  <td style=\"font-style: italic; color: #B9BEC1;\">\n";
         $output .= "   ".$dir['size']."\n";
         $output .= "  </td>\n";
         $output .= "  <td style=\"font-style: italic; color: #B9BEC1;\">\n";
         $output .= "   ".$dir['date']['month']." ".$dir['date']['mday'].", ".$dir['date']['year']."\n";
         $output .= "  </td>\n";
         $output .= "  <td style=\"font-style: italic; color: #2E2E2E;\">\n";
         $output .= "   ".$dir['perms']."\n";
         $output .= "  </td>\n";
         $output .= " </tr>\n";
      }

      # Show files below directores
      #---------------------------------------------------
      foreach( $infolder['files'] as $file ) {
         $output .= " <tr>\n";
         $output .= "  <td style=\"color: #000000;\">\n";
         $output .= "   ".$file['name']."\n";
         $output .= "  </td>\n";
         $output .= "  <td style=\"font-style: italic; color: #B9BEC1;\">\n";
         $output .= "   ".$file['size']."\n";
         $output .= "  </td>\n";
         $output .= "  <td style=\"font-style: italic; color: #B9BEC1;\">\n";
         $output .= "   ".$file['date']['month']." ".$file['date']['mday'].", ".$file['date']['year']."\n";
         $output .= "  </td>\n";
         $output .= "  <td style=\"font-style: italic; color: #2E2E2E;\">\n";
         $output .= "   ".$file['perms']."\n";
         $output .= "  </td>\n";
         $output .= " </tr>\n";
      }

      $output .= "</table>\n";

      return $output;

   } // End if return type == whatever

}


/*---------------------------------------------------------------------------------------------------------*
  _          _     _                  _      _
 | |_  __ _ | |__ | | ___   ___ __ __(_) ___| |_  ___
 |  _|/ _` || '_ \| |/ -_) / -_)\ \ /| |(_-<|  _|(_-<
  \__|\__,_||_.__/|_|\___| \___|/_\_\|_|/__/ \__|/__/

# ACCEPTS: Name of MySQL DB Table
# RETURNS: true/false based on whether table exists in database
/*---------------------------------------------------------------------------------------------------------*/
function table_exists($tablename) {
   global $db_name;

   # Select all db tables
   $result = mysql_list_tables($db_name);

   # Loop through table names and listen for match
   for ( $i = 0; $i < mysql_num_rows($result); $i++ ) {
      if ( mysql_tablename($result, $i) == $tablename ) {
         return true;
      }
   }
   return false;
}


/*=======================================================================================================================*
 _     _      _       ___   ___     _____       _     _
| |   (_) ___| |_    |   \ | _ )   |_   _|__ _ | |__ | | ___  ___
| |__ | |(_-<|  _|   | |) || _ \     | | / _` || '_ \| |/ -_)(_-<
|____||_|/__/ \__|   |___/ |___/     |_| \__,_||_.__/|_|\___|/__/
<< Returns array of table names (raw or indexed by prefix - raw/pre)
/*=======================================================================================================================*/
function dbtables($ind = "") {
   global $db_name;
   $result = mysql_list_tables($db_name);

   for ( $i = 0; $i < mysql_num_rows($result); $i++ ) {
      $tbl = mysql_tablename($result, $i);

      # Index by prefix
      if ( $ind == "pre" ) {
         if ( eregi("^[a-zA-Z]*_", $tbl, $pre) ) {
            $prefix = str_replace("_", "", $pre[0]);
            $prefix = strtolower($prefix);
            $tables[$prefix] = $tbl;
            //echo "[".$prefix."] - ".$tbl."<br>";
         }

      # Unfiltered numerical index
      } else {
         $tables[] = $tbl;
      }
   }
   return $tables;
}



/*=======================================================================================================================*
 _                        _____           _
| |    __ _  _ _   __ _  |_   _|___ __ __| |_
| |__ / _` || ' \ / _` |   | | / -_)\ \ /|  _|
|____|\__,_||_||_|\__, |   |_| \___|/_\_\ \__|
                  |___/
<< Returns passed string in context of selected language (via Global Settings)
/*=======================================================================================================================*/

if($_SERVER['REMOTE_ADDR']=='24.126.234.235' || $_SERVER['REMOTE_ADDR']=='24.126.234.203'){

	function lang($string) {
	   if ( $_SESSION['lang'][$string] != "" ) {
	      # String defined in language file
	      $thisstring = "<font color=#FF8FA3>".$_SESSION['lang'][$string]."</font>";
	      return $thisstring;
	
	   } else {
	      # String not defined, return raw passed string
	      $thisstring = "<font color=#CF00CF><blink>".$string."</blink></font>";
	      return $thisstring;
	   }
	}
	
} else {
	
	function lang($string) {
	   if ( $_SESSION['lang'][$string] != "" ) {
	      # String defined in language file
	      return $_SESSION['lang'][$string];
	
	   } else {
	      # String not defined, return raw passed string
	      return $string;
	   }
	}
}

/*=======================================================================================================================*
 __  __          _    _     _
|  \/  | ___  __| |  | |   (_) __  ___  _ _   ___ ___  ___
| |\/| |/ _ \/ _` |  | |__ | |/ _|/ -_)| ' \ (_-</ -_)(_-<
|_|  |_|\___/\__,_|  |____||_|\__|\___||_||_|/__/\___|/__/

>> Accepts: Module string to check against key file
<< Returns: true/false if module is/isn't licensed (enabled)
/*=======================================================================================================================*/
function hasMod($module) {
   if ( eregi(md5($module), $_SESSION['soholaunchlic']) ) {
      return true;
   } else {
      return false;
   }
}

/*---------------------------------------------------------------------------------------------------------*
   _       _     _              _     _                             _
  /_\   __| | __| | ___  _ _   | |   (_) __  ___  _ _   ___ ___  __| |
 / _ \ / _` |/ _` |/ _ \| ' \  | |__ | |/ _|/ -_)| ' \ (_-</ -_)/ _` |
/_/ \_\\__,_|\__,_|\___/|_||_| |____||_|\__|\___||_||_|/__/\___|\__,_|
>> Accepts: Addon folder name
<< Returns: true/false if addon is or isn't licensed
/*---------------------------------------------------------------------------------------------------------*/
function addon_licensed($addon_folder) {
   # Ping addon licensing API
   $api_qry = "http://securexfer.net/product_reports/verify_addon_lic.php?addon=".$addon_folder."&domain=".$_SESSION['this_ip']."&hostname=".php_uname(n);
   //echo "api_qry = '".$api_qry."'"; exit;
   $api_response = file_get_contents($api_qry);

   # Kill folder and redirict to plugin manager with error message if not licensed
   if ( trim($api_response) == "false" ) {
      return false;
   } else {
      return true;
   }
}


/*---------------------------------------------------------------------------------------------------------*
 ___   _       __      __ _           _
|   \ (_)__ __ \ \    / /(_) _ _   __| | ___ __ __ __
| |) || |\ V /  \ \/\/ / | || ' \ / _` |/ _ \\ V  V /
|___/ |_| \_/    \_/\_/  |_||_||_|\__,_|\___/ \_/\_/

>> Accepts: Nothing
>> Returns: HTML <div> layer controlled by several js routines found in js_functions.php
/*---------------------------------------------------------------------------------------------------------*/
function div_window() {
   $win = "<!---Begin popup div window--->\n";
   $win .= "<div id=\"dwindow\" style=\"position:absolute; background-color:#EBEBEB; cursor:hand; left:0px; top:0px; display:none; z-index: 500;\" onMousedown=\"initializedrag(event)\" onMouseup=\"stopdrag()\" onSelectStart=\"return false\">\n";
   $win .= "\n";
   $win .= " <!---Maximize / Close Window--->\n";
   $win .= " <div align=\"right\" class=\"fsub_title\" style=\"padding: 5px; border: 1px solid #2E2E2E; border-style: solid solid none solid;\">\n";
   $win .= "  <img src=\"http://".$_SESSION['docroot_url']."/sohoadmin/program/includes/display_elements/graphics/icon-maximize.gif\" id=\"maxname\" onClick=\"maximize()\">\n";
   $win .= "  <img src=\"http://".$_SESSION['docroot_url']."/sohoadmin/program/includes/display_elements/graphics/icon-close_window-off.gif\" onClick=\"closeit()\">\n";
   $win .= " </div>\n";
   $win .= " \n";
   $win .= " <!---Content frame--->\n";
   $win .= " <div id=\"dwindowcontent\" style=\"height:100%; vertical-align: top; border: 1px solid #2E2E2E; border-style: none solid solid solid;\">\n";
   $win .= "  <iframe id=\"cframe\" src=\"\" width=\"100%\" height=\"100%\"></iframe>\n";
   $win .= " </div>\n";
   $win .= "</div>\n";
   $win .= "<!---End popup div window--->\n";

   return $win;
}

/*---------------------------------------------------------------------------------------------------------*
           _____
 _  _  _ _|_   _|__ _  _ _
| || || ' \ | | / _` || '_|
 \_,_||_||_||_| \__,_||_|

# PURPOSE: Extract archive file...however it has to be done.
# ACCEPTS: Path to tar/tgz/zip file
# NOTE: Requires shell_exec to operate (so safe_mode has to be off)
/*---------------------------------------------------------------------------------------------------------*/
function untar($pathtofile) {

   //return getcwd();
   //return $_SESSION['docroot_path'];

   # Make sure safe_mode is off (won't work on either OS without shell_exec)
   if ( ini_get('safe_mode') ) {
      $errors[] = "<b>safe_mode = [".settype(ini_get('safe_mode'), "string")."]</b>: Cannot extract file because the php.ini directive 'safe_mode' is enabled.";
   }

   # Normal Linux method or Windows work-around?
   if ( strtoupper(PHP_OS) == "LINUX" ) {

      # Extract via standard tar command
      $command_output = shell_exec("tar -xzvf ".$pathtofile);

   } elseif ( strtoupper(PHP_OS) == "WIN" ) {

      # Run tar command from bundled .exe file
      $SLASH = DIRECTORY_SEPARATOR; // Readability
      $tarcom = $_SESSION['docroot_path'].$SLASH."sohoadmin".$SLASH."program".$SLASH."webmaster".$SLASH."backups".$SLASH."untar".$SLASH."tar -xzvf ".$pathtofile;

   } // End if Linux/Win


}


/*---------------------------------------------------------------------------------------------------------*
 __  ___  _ __  _  _  _ _
/ _|/ _ \| '_ \| || || '_|
\__|\___/| .__/ \_, ||_|
         |_|    |__/

# Copy directory and all of its contents (like, recursively)
/*---------------------------------------------------------------------------------------------------------*/
function copyr($source, $dest) {
    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        if ($dest !== "$source/$entry") {
            copyr("$source/$entry", "$dest/$entry");
        }
    }

    // Clean up
    $dir->close();
    return true;
}


/*---------------------------------------------------------------------------------------------------------*
                _  _
 _ _  _ __   __| |(_) _ _  _ _
| '_|| '  \ / _` || || '_|| '_|
|_|  |_|_|_|\__,_||_||_|  |_|

 * Delete a file or a folder and its contents
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.2
 * @param       string   $dirname    Directory to delete
 * @return      bool     Returns TRUE on success, FALSE on failure
/*---------------------------------------------------------------------------------------------------------*/
function rmdirr($dirname) {
    // Sanity check
    if (!file_exists($dirname)) {
        return false;
    }

    // Simple delete for a file
    if (is_file($dirname)) {
        return unlink($dirname);
    }

    // Loop through the folder
    $dir = dir($dirname);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Recurse
        rmdirr("$dirname/$entry");
    }

    // Clean up
    $dir->close();
    return rmdir($dirname);
}


/*---------------------------------------------------------------------------------------------------------*
 _                               __  _  _           _
| |_  _  _  _ __   __ _  _ _    / _|(_)| | ___  ___(_) ___ ___
| ' \| || || '  \ / _` || ' \  |  _|| || |/ -_)(_-<| ||_ // -_)
|_||_|\_,_||_|_|_|\__,_||_||_| |_|  |_||_|\___|/__/|_|/__|\___|

# CALCULATE USER-READABLE FILESIZE
# Accepts: int size value OR string file path[, "size" OR "flag"]
# Returns: if "size" = formatted size value ('size') | if "flag" - flag image if too big ('flag')
/*---------------------------------------------------------------------------------------------------------*/
function human_filesize($filesize, $gimme = "size") {
   # Was size value or filepath passed?
   if ( file_exists($filesize) ) { // File path
      $filesize = filesize($filesize);
   }

   # Will contain tag for 'too big' image (if it's too big)
   $red_flag = "";

   # MB
   if ( $filesize >= 1048576 ) {
      $filesize = round($filesize / 1048576 * 100) / 100;
      if ( $filesize > 1 ) {
         $red_flag = "<img src=\"site_files/red_flag.gif\" width=10 height=10 border=0 hspace=5 align=absmiddle>";
      }
      $filesize = $filesize . "&nbsp;MB";

   # KB
   } elseif ( $filesize >= 1024 ) {
      $filesize = round($filesize / 1024 * 100) / 100;
      if ( $filesize > 40 ) {
         $red_flag = "<img src=\"site_files/red_flag.gif\" width=10 height=10 border=0 hspace=5 align=absmiddle>";
      }
      $filesize = $filesize . "&nbsp;KB";

   # Bytes
   } else {
      $filesize = $filesize . "&nbsp;Bytes";
   }

   if ( $gimme == "size" ) {
      return $filesize;
   } elseif ( $gimme == "flag" ) {
      return $red_flag;
   }
}


/*---------------------------------------------------------------------------------------------------------*
 ___                   _          _
| _ \ ___  _ _  _ __  (_) ___ ___(_) ___  _ _   ___
|  _// -_)| '_|| '  \ | |(_-<(_-<| |/ _ \| ' \ (_-<
|_|  \___||_|  |_|_|_||_|/__//__/|_|\___/|_||_|/__/

# Attempt to overcome permissions problems via Cameron's method
# ACCEPTS: Path from docroot to target folder
# RETURNS: true on success, false on failure
/*---------------------------------------------------------------------------------------------------------*/
function cam_perm_fix($folder) {

   # FTP method possible?
   if ( check_ftp() ) {
      cam_perm_fix_ftp();
   }

   if ( testWrite($folder) ) {
      return true;

   } else {
      # No ftp info - use copy/rename method

      # Preserver original working dir
      $orig_workingdir = getcwd();

      # Work from docroot
      chdir($_SESSION['docroot_path']);

      # Make sure folder exists (create if not)
      if ( !is_dir($folder) ) {
         mkdir($folder, 0755);

         if ( !is_dir($folder) ) {
            chdir($orig_workingdir);
            return false;
         } else {
            chdir($orig_workingdir);
            return true;
         }


      } else {

         # Build duplicate folder name
         $folder2 = $folder."2";
         $folderbak = $folder."-unwriteable";

         # Duplicate folder as folder2
         copyr($folder, $folder2);

         # Rename folder as folder-unwriteable
         rename($folder, $folderbak);

         # Rename folder2 as folder
         rename($folder2, $folder);

         # chmod -R 0755 folder
         shell_exec("chmod -R 0755 ".$folder);

         # Attempt to delete unwriteable copy of backup folder
         # ...not sure how many catastrophes this might cause if copy step doesn't work right
            # ...plus it usually won't let you kill something (a folder) you can't write too anyway (only tested this on non-phpsuexec though)
         //shell_exec("rm -rf ".$folderbak);

         if ( is_writeable($folder) ) {
            chdir($orig_workingdir);
            return true;
         } else {
            rmdirr($folder2); // Kill duplicate
            chdir($orig_workingdir);
            return false;
         }

      } // end fix perms if folder doesn't need to be created

   } // End else no ftp so use copy/rename method

} // End cam_perm_fix() function

# cam_perm_fix_ftp
function cam_perm_fix_ftp($testfolder) {
   include($_SESSION['docroot_path']."/sohoadmin/program/modules/help_center/ftpchmod.php");
//   return true;
}



/*---------------------------------------------------------------------------------------------------------*
 _             _  __      __     _  _
| |_  ___  ___| |_\ \    / /_ _ (_)| |_  ___
|  _|/ -_)(_-<|  _|\ \/\/ /| '_|| ||  _|/ -_)
 \__|\___|/__/ \__| \_/\_/ |_|  |_| \__|\___|

# Test folder writeability --- for real
/*---------------------------------------------------------------------------------------------------------*/
function testWrite($folder, $autofix = false) {
   # Preserver original working dir
   $orig_workingdir = getcwd();

   # Work from docroot
   chdir($_SESSION['docroot_path']);

   # Write temp file to folder
   $tempfile = $folder."/testwrite.txt";
   $handle = fopen($tempfile, "w+");
   fclose($handle);

   if ( file_exists($tempfile) ) {
      unlink($tempfile);
      chdir($orig_workingdir);
      return true;

   } else {
      # Try to fix?
      if ( $autofix ) {
         cam_perm_fix($folder);
         chdir($orig_workingdir);
         return testWrite($folder);
      } else {
         chdir($orig_workingdir);
         return false;
      }
   }

} // End testWrite() function


# Get startpage name
# Call this function instead of $_SESSION['getSpec']['startpage'] because setting may be stored differently/somewhere else in the future
# If you call startpage() then the returned page name is formated like it is for pr= stuff, etc (i.e. 'Home_Page')
# If you call startpage(false) then the returned page name is straight from db (formatted more for display than queries -- i.e. 'Home Page')
function startpage($format = true) {
   $startpage = $_SESSION['getSpec']['startpage'];

   # Try to pull from database if not set
   if ( $startpage == "" ) {
      $qry = "SELECT startpage FROM site_specs";
      $rez = mysql_query($qry);
      $startpage = mysql_result($rez, 0);
   }

   # Set to default if db pull didn't work
   if ( $startpage == "" ) {
      $startpage = "Home";
   }

   if ( $format ) {
      return str_replace(" ", "_", $startpage);
   } else {
      return $startpage;
   }
}


# Build popup help layer
function help_popup($idname, $title, $popup_content, $style = "", $other = array()) {
   $help_popup = "";

   # POPUP: Pricing notes
   $help_popup .= " <div class=\"help_popup\" id=\"".$idname."\" style=\"display: none;".$style."\">\n";

   # Message text
   $help_popup .= "  <div id=\"".$idname."-content\" style=\"padding: 5px 10px 10px 10px; margin-bottom: 0px;\">\n";
   $help_popup .= "   <h1 id=\"".$idname."-title\">".$title."</h1>\n";
   $help_popup .= "   ".$popup_content."\n";
   $help_popup .= "  </div>\n";

   # Closebar
   $help_popup .= "  <div id=\"".$idname."-closebar\" onclick=\"hideid('".$idname."');".$other['onclose']."\" onmouseover=\"setClass(this.id, 'hand bg_red_d7 white right');\"  onmouseout=\"setClass(this.id, 'hand bg_red_98 white right');\" class=\"hand bg_red_98 white right\" style=\"padding: 3px;\">[x] close</div>\n";
   $help_popup .= " </div>\n";

   return $help_popup;
}


# Check whether this server is running phpsuexec
function php_suexec($stringreturn = false) {
   $php_suexec = strtoupper(php_sapi_name());
   if ( $php_suexec == "CGI" ) {
      if ( $stringreturn ) { return "enabled"; } else { return true; }
   } else {
      if ( $stringreturn ) { return "disabled"; } else { return false; }
   }
}


# VERSION/UPDATE FUNCTIONS
# Include this script as needed for various build info/auto-update related functions
# Maybe include this everywhere either in shared_functions or via product_gui.php?
/*---------------------------------------------------------------------------------------------------------*
 ___        _  _     _   ___         __
| _ ) _  _ (_)| | __| | |_ _| _ _   / _| ___
| _ \| || || || |/ _` |  | | | ' \ |  _|/ _ \
|___/ \_,_||_||_|\__,_| |___||_||_||_|  \___/

# Reads appropriate build info file and returns currently installed build info in an array
/*---------------------------------------------------------------------------------------------------------*/
function build_info() {

   # Updated file (written after auto-update)
   $updated_binfo_file = $_SESSION['docroot_path']."/sohoadmin/filebin/build.conf.php";

   # Default file (included in build)
   //$default_binfo_file = $_SESSION['docroot_path']."/sohoadmin/program/webmaster/sohoadmin/config/build.conf.php"; // for testing only
   $default_binfo_file = $_SESSION['docroot_path']."/sohoadmin/config/build.conf.php";

   # latest update-created file or from default file installed with site?
   if ( file_exists($updated_binfo_file) ) {
      $build_info_file = $updated_binfo_file;
   } else {
      $build_info_file = $default_binfo_file;
   }

   # Pull info about installed build from info file
   if ( !$binfo_stream = fopen($build_info_file, "r") ) {
      echo "Error: Unable to open build info file.";
   } else {
      if ( !$binfo_data = fread($binfo_stream, filesize($build_info_file)) ) {
         echo "Error: Unable to read build info file. -- $build_info_file";
      }
      fclose($binfo_stream);
   }

   # Return restored build info array
   return unserialize($binfo_data);

} // End current_build() function


# current_version()
# >> RETURNS: Installed version number (i.e. "v4.9 r47")
function current_version() {
   $binfo = build_info();
   return $binfo['build_name'];
}


# htmlspecialchars_decode()
if ( !function_exists('htmlspecialchars_decode') ) {
   function htmlspecialchars_decode($text) {
       return strtr($text, array_flip(get_html_translation_table(HTML_SPECIALCHARS)));
   }
}


# Include various global classes to be used on client-side and program-side (only available on program-side if included in product_gui.php)
# Mysql insert class
include_once($_SESSION['docroot_path']."/sohoadmin/includes/mysql_insert.class.php");

# userData manipulation class (works with misc_userdata table)
include_once($_SESSION['docroot_path']."/sohoadmin/includes/userdata.class.php");

# FULL_URL - create superglobal (handy for passing/redirecting back to last viewed page)
$_SERVER['FULL_URL'] = 'http';
if ( $_SERVER['HTTPS'] == "on" ) { $_SERVER['FULL_URL'] .=  "s"; }
$_SERVER['FULL_URL'] .=  "://";
if ( $_SERVER['SERVER_PORT'] != "80" ) {
   $_SERVER['FULL_URL'] .= $_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].$_SERVER['SCRIPT_NAME'];
} else {
   $_SERVER['FULL_URL'] .=  $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
   if ( $_SERVER['QUERY_STRING'] > " " ){ $_SERVER['FULL_URL'] .=  '?'.$_SERVER['QUERY_STRING']; }
}

# supersterilize()
# Returns sterilized version of string stablized to be db column name
# Cuts string off at 64 chars by default
function supersterilize($string, $charlimit = true) {
   # Replaces spaces with underscores
   $string = str_replace(" ", "_", $string);

   # Strip illegal characters
   $string = eregi_replace("[^A-Za-z0-9_]", "", $string);

   # Trim spaces
   $string = trim($string);

   # 64 char cutoff
   if ( $charlimit ) {
      $string = substr($string, 0, 64);
   }

   # Trim trailing underscores
   $string = eregi_replace("_$", "", $string);
   $string = eregi_replace("^_", "", $string);

   return $string;
}

# slashthis()
# To escape or not to escape?
# Designed to address gpc_magic_quotes problem (as in, how some have it on and some have it off)
function slashthis($string) {
   if ( !get_magic_quotes_gpc() ) {
	 $string = strip_slashes($string);
      return mysql_real_escape_string($string);
   } else {
      return $string;
   }
}


if (!function_exists('url_get_encoding')){
	function url_get_encoding($url_q){
		global $_SESSION;
	
		if(isset($_SESSION['char_encoding'])){
			$encoding = $_SESSION['char_encoding'];
		} else {
			if(function_exists('curl_init') && function_exists('curl_setopt') && function_exists('curl_exec')){
				$chz = curl_init();
				curl_setopt($chz, CURLOPT_URL,            $url_q);
				curl_setopt($chz, CURLOPT_HEADER,         true);
				curl_setopt($chz, CURLOPT_NOBODY,         true);
				curl_setopt($chz, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($chz, CURLOPT_TIMEOUT,        10);	
				$r = curl_exec($chz);
				curl_close($chz);
				$s = split("\n", $r);
				if(preg_match("/charset/mi", $r)){		
					foreach($s as $hval){
						if(preg_match("/charset/i", $hval)){
							if(preg_match("/utf-8/i", $hval)){
								$encoding = 'UTF-8';
							} else {
								$hvalz = preg_replace('/charset=/i', '--charset--', $hval);
								$chset = explode('--charset--', $hvalz);					
								$encoding = $chset['1'];
							}
						}
					}
				} else {
					$encoding = '';
				}
			} else {
				$encoding = ' ';
			}
			$_SESSION['char_encoding'] = strtoupper($encoding);
		}
		return $encoding;
	} 
}

eval(hook("global_function", basename(__FILE__)));

?>