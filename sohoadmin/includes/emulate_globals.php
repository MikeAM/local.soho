<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


# Catch hack attack
if ( $_GET['_SESSION'] != "" ) {
   exit;
}

// Emulate register_globals on
if ( !ini_get('register_globals') ) {
   $superglobals = array($_SERVER, $_ENV, $_FILES, $_COOKIE, $_POST, $_GET);

   if ( isset($_SESSION) ) {

      # Extract session vars as refferences
      foreach ( $_SESSION as $var=>$val ) {
         global ${$var};
         ${$var} = &$_SESSION[$var];
      }

   }

   foreach ( $superglobals as $superglobal ) {
       extract($superglobal, EXTR_SKIP);
   }
}

if ( !ini_get('register_long_arrays') ) {
   $HTTP_POST_VARS = &$_POST;
   $HTTP_GET_VARS = &$_GET;
   $HTTP_SESSION_VARS = &$_SESSION;
   $HTTP_POST_FILES = &$_FILES;
}

//header("location:../index.php");

?>