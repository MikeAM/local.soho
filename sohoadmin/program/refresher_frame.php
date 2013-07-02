<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


# The primary purpose of this frame's existence is to refresh every so often so the session doesn't expire
# It can also come in handy for quick behind-the-scense $_GET queries
# ...i.e. User checks 'do not show this again' box on trial expire popup, checkbox sends $_GET qry to this frame, this script sets the cookie

error_reporting(E_PARSE);
session_start();
include("includes/product_gui.php");

/*---------------------------------------------------------------------------------------------------------*
 ___                 _        _    ___                   _
/ __| _ __  ___  __ (_) __ _ | |  / _ \  _  _  ___  _ _ (_) ___  ___
\__ \| '_ \/ -_)/ _|| |/ _` || | | (_) || || |/ -_)| '_|| |/ -_)(_-<
|___/| .__/\___|\__||_|\__,_||_|  \__\_\ \_,_|\___||_|  |_|\___|/__/
     |_|
# Special behind-the-scenes operations initiated by various modules
/*---------------------------------------------------------------------------------------------------------*/

# Set cookie to not show a particular popup (set cookie expiration to 15 days worth of seconds)
# EXPECTED VARS: todo = suppress_popup, popup_name = [string name for identifying this cookie]
if ( $_GET['todo'] == "suppress_popup" && $_GET['popup_name'] != "" ) {
   if ( !setcookie($_GET['popup_name'], "suppress", time() + 1296000) ) {
      echo "<script language=\"javascript\">alert('".lang("Could not set cookie to suppress popup")."\\n".lang("Error Message").": ".$php_errormsg."');</script>\n";
   } else {
      echo "<script language=\"javascript\">alert('".lang("Cookie set! Popup will not display in the future").".');</script>\n";
   }
}

# Unset specified cookie
if ( $_GET['todo'] == "unsetcookie" && $_GET['cookiename'] != "" ) {
   if ( !setcookie($_GET['cookiename']) ) {
      echo "<script language=\"javascript\">alert('".lang("Could not unset cookie")." [".$_GET['cookiename']."]!\\n".lang("Error Message").": ".$php_errormsg."');</script>\n";
   } else {
      echo "<script language=\"javascript\">alert('".lang("Cookie unset")." [".$_GET['cookiename']."]!');</script>\n";
   }
}

/*---------------------------------------------------------------------------------------------------------*
 ___        __                _        _        _    _
| _ \ ___  / _| _ _  ___  ___| |_     /_\   __ | |_ (_) ___  _ _
|   // -_)|  _|| '_|/ -_)(_-<| ' \   / _ \ / _||  _|| |/ _ \| ' \
|_|_\\___||_|  |_|  \___|/__/|_||_| /_/ \_\\__| \__||_|\___/|_||_|

# Refresh every so often so the session doesn't expire
/*---------------------------------------------------------------------------------------------------------*/
# Do calculation from minutes to miliseconds
# so value can be changed without having to do the convertion in your head
$minutes = 5; // Minutes
$refresh_every = $minutes * 60 * 1000;

# Reload this frame periodically to keep session alive
echo "<script language=\"javascript\">\n";
echo "window.setTimeout(\"document.location.href='refresher_frame.php'\", ".$refresh_every.");\n";
echo "</script>\n";

?>

<body bgcolor="#dfecf6">
If you can see this it is possible that your browser does not support frames. If you think this might be the case, please upgrade to a newer web browser.
</body>