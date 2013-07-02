<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.5
##
## Author: 			Mike Johnston [mike.johnston@soholaunch.com]
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugzilla.soholaunch.com
## Release Notes:	sohoadmin/build.dat.php
###############################################################################

##############################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2003 Soholaunch.com, Inc. and Mike Johnston 
## Copyright 2003-2007 Soholaunch.com, Inc.
## All Rights Reserved.
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
##############################################################################

error_reporting(0);			// Leave this here, that way if no sec codes have been set, there are no errors (I'm Lazy)
session_cache_limiter('none');
session_start();

track_vars;
$pr = " ";

# ACTION: Logout link clicked?
if ( $_GET['todo'] == "logout" ) {
   session_destroy();

   # Redirect back to...
   if ( $_GET['backto'] != "" ) {
      $backtourl = base64_decode($_GET['backto']);
      header("location: ".$backtourl); exit;
   }
}

foreach ( $_REQUEST as $name=>$value ) {
		$value = htmlspecialchars($value);	// Bugzilla #13
		${$name} = $value;
}

#########################################################################
### WE WILL NEED TO KNOW THE DATABASE NAME; UN; PW; ETC TO OPERATE THE ###
### REAL-TIME EXECUTION.  THIS IS CONFIGURED IN THE isp.conf FILE      ###
##########################################################################

include_once("sohoadmin/client_files/pgm-site_config.php");
$dot_com = $this_ip;
include_once("sohoadmin/program/includes/shared_functions.php");
#########################################################
### MAKE pageRequest VAR AND pr VAR MATCH			###
#########################################################

if ($pageRequest == "" && $pr != "") { $pageRequest = $pr; }

$site_title = strtoupper($SERVER_NAME);

#######################################################################################################
###### Process "AUTHENTICATE" Request and register session variables for use with rest of system
#######################################################################################################

if ( $_POST['PROCESS'] == "AUTHENTICATE" ) {
//   echo "here";

	$auth = 0;

	$qry = "SELECT USERNAME, PASSWORD FROM sec_users WHERE USERNAME = '".$_POST['SOHO_AUTH']."' AND PASSWORD = '".$_POST['SOHO_PW']."'";
	$result = mysql_query($qry);
	$auth = mysql_num_rows($result);
	$auth_method = "sec_users";

	if ( $auth === 0 ) {		// Let's Check the Returning Customers data table too (just to be cool)
	   $qry = "SELECT USERNAME, PASSWORD FROM cart_customers WHERE USERNAME = '".$_POST['SOHO_AUTH']."' AND PASSWORD = '".$_POST['SOHO_PW']."'";
		if ( $result = mysql_query($qry) ) {
			$auth = mysql_num_rows($result);
			$auth_method = "cart_customers";
		}
	}

	// ---------------------------------------------------------------------------
	// At this point the user has been authorized by the system (1=OK;0=FAILED)
	// ---------------------------------------------------------------------------

	if ($auth === 0) {
		header("Location: pgm-secure_login.php?err=noauth&sc=$sc&pa=$pa&=SID");
		exit;
	}
	// ---------------------------------------------------------------------------
	// Anything pass this is a go!
	// ---------------------------------------------------------------------------

   $_SESSION['username'] = $_POST['SOHO_AUTH'];
   $_SESSION['password'] = $_POST['SOHO_PW'];
   $_SESSION['SOHO_AUTH'] = $_POST['SOHO_AUTH'];
   $_SESSION['SOHO_PW'] = $_POST['SOHO_PW'];
   
   eval(hook("pgm-secure_login.php:just-authenticated"));

   if ( isset($_POST['remember']) ) {
      setcookie("cookname", $_SESSION['username'], time()+60*60*24*100, "/");
      setcookie("cookpass", $_SESSION['password'], time()+60*60*24*100, "/");
   }

	if ($auth_method == "sec_users") {			// Let's put our secure login system into order

		$qry = "SELECT * FROM sec_users WHERE USERNAME = '".$_POST['SOHO_AUTH']."' AND PASSWORD = '".$_POST['SOHO_PW']."'";
		$result = mysql_query($qry);
		$user_data = mysql_fetch_array($result);
		$numberFields = mysql_num_fields($result);
		$numberFields--;

		for ($x=0;$x<=$numberFields;$x++) {
			$field_name = mysql_field_name($result, $x);
			if ($field_name != "PRIKEY") {
				${$field_name} = $user_data[$field_name];
				if (!session_is_registered("$field_name")) { session_register("$field_name"); }
				$_SESSION[$field_name] = $user_data[$field_name];
			}

		}

		// ---------------------------------------------------------------
		// Just in case sombody selects NOTHING for the redirect page
		// ---------------------------------------------------------------

		if ($REDIRECT_PAGE == "") { $REDIRECT_PAGE = pagename(startpage()); }

		// ----------------------------------------------------------------------------------------
		// Now, let's populate the "known" shopping cart "remember me" features if this auth user
		// has that information already logged in to the system
		// ----------------------------------------------------------------------------------------
		if ( $_SESSION['OWNER_NAME'] != "" ) {
		   $_SESSION['BPASSWORD'] = $_POST['SOHO_PW'];
			$_SESSION['REPEATCUSTOMER'] = "YES";
		}

		// ----------------------------------------------------------------------------------------
		// ONE MORE THING: HAS THIS AUTH USER REACHED EXPIRATION DATE YET? IS SO, SEND BACK ERR
		// ANYWAY
		// ----------------------------------------------------------------------------------------

		if ($EXPIRATION_DATE != "0000-00-00") {			// Check for expire unless no expire date is choosen

			$m = date("m");
			$d = date("d");
			$y = date("Y");

			$U_NOW = date("U", mktime(0,0,0,$m,$d,$y));
			$tmp_time = 60*60*24;
			$NOW_DAYS = $U_NOW/$tmp_time;

			$tmp = split("-", $EXPIRATION_DATE);
			$U_EXP = date("U", mktime(0,0,0,$tmp[1],$tmp[2],$tmp[0]));
			$EXP_DAYS = $U_EXP/$tmp_time;

			if ($EXP_DAYS >= $NOW_DAYS) { 					// This user has not expired yet

				# Not expired, store days left
				$days_till_expire = ($EXP_DAYS - $NOW_DAYS) + 1;	// If you wish to use this, it tells you how many "days" until
													// the current user's access expires. I havn't had any use for
												   	// this, but I'm sure someone will.
			} else {
            # User expired, clear session data
				$auth = 0;
				if (session_is_registered("BPASSWORD")) { session_unregister("BPASSWORD"); }
				if (session_is_registered("REPEATCUSTOMER")) { session_unregister("REPEATCUSTOMER"); }
				if (session_is_registered("SOHO_AUTH")) { session_unregister("SOHO_AUTH"); }
				if (session_is_registered("SOHO_PW")) { session_unregister("SOHO_PW"); }
				if (session_is_registered("GROUPS")) { session_unregister("GROUPS"); }
				if (session_is_registered("USERNAME")) { session_unregister("USERNAME"); }
				if (session_is_registered("PASSWORD")) { session_unregister("PASSWORD"); }

				$GROUPS = ""; 		// Clear out any access that may have been registered for sure
				$SOHO_AUTH = "";
				$SOHO_PW = "";
				$REPEATCUSTOMER = "";
				$USERNAME = "";
				$PASSWORD = "";

				header("Location: pgm-secure_login.php?err=expire&sc=$sc&pa=$pa&=SID");
				exit;

			}

		} // End Expire Check

	} // End "sec_users" auth method

	######################################################################

	if ($auth_method == "cart_customers") {			// Now Let's "Remember" a user from the shopping cart

		$result = mysql_query("SELECT * FROM cart_customers WHERE USERNAME = '$SOHO_AUTH' AND PASSWORD = '$SOHO_PW'");
		$row = mysql_fetch_array($result);

		$BFIRSTNAME = $row[BILLTO_FIRSTNAME];
		$BLASTNAME = $row[BILLTO_LASTNAME];
		$BCOMPANY = $row[BILLTO_COMPANY];
		$BADDRESS1 = $row[BILLTO_ADDR1];
		$BADDRESS2 = $row[BILLTO_ADDR2];
		$BCITY = $row[BILLTO_CITY];
		$BSTATE = $row[BILLTO_STATE];
		$BCOUNTRY = $row[BILLTO_COUNTRY];
		$BZIPCODE = $row[BILLTO_ZIPCODE];
		$BEMAILADDRESS = $row[BILLTO_EMAILADDR];
		$BPHONE = $row[BILLTO_PHONE];

		$SFIRSTNAME = $row[SHIPTO_FIRSTNAME];
		$SLASTNAME = $row[SHIPTO_LASTNAME];
		$SCOMPANY = $row[SHIPTO_COMPANY];
		$SADDRESS1 = $row[SHIPTO_ADDR1];
		$SADDRESS2 = $row[SHIPTO_ADDR2];
		$SCITY = $row[SHIPTO_CITY];
		$SSTATE = $row[SHIPTO_STATE];
		$SCOUNTRY = $row[SHIPTO_COUNTRY];
		$SZIPCODE = $row[SHIPTO_ZIPCODE];
		$SPHONE = $row[SHIPTO_PHONE];


		// ----------------------------------------------------------------------
		// Register "Remember Me" data into memory now
		// ----------------------------------------------------------------------

		if (!session_is_registered("BFIRSTNAME")) { session_register("BFIRSTNAME"); }
		if (!session_is_registered("BLASTNAME")) { session_register("BLASTNAME"); }
		if (!session_is_registered("BCOMPANY")) { session_register("BCOMPANY"); }
		if (!session_is_registered("BADDRESS1")) { session_register("BADDRESS1"); }
		if (!session_is_registered("BADDRESS2")) { session_register("BADDRESS2"); }
		if (!session_is_registered("BCITY")) { session_register("BCITY"); }
		if (!session_is_registered("BSTATE")) { session_register("BSTATE"); }
		if (!session_is_registered("BCOUNTRY")) { session_register("BCOUNTRY"); }
		if (!session_is_registered("BZIPCODE")) { session_register("BZIPCODE"); }
		if (!session_is_registered("BPHONE")) { session_register("BPHONE"); }
		if (!session_is_registered("BEMAILADDRESS")) { session_register("BEMAILADDRESS"); }

		if (!session_is_registered("BPHONE_COUNTRYCODE")) { session_register("BPHONE_COUNTRYCODE"); }
		if (!session_is_registered("BPHONE_AREACODE")) { session_register("BPHONE_AREACODE"); }
		if (!session_is_registered("BPHONE_PREFIX")) { session_register("BPHONE_PREFIX"); }
		if (!session_is_registered("BPHONE_SUFFIX")) { session_register("BPHONE_SUFFIX"); }


		if (!session_is_registered("SFIRSTNAME")) { session_register("SFIRSTNAME"); }
		if (!session_is_registered("SLASTNAME")) { session_register("SLASTNAME"); }
		if (!session_is_registered("SCOMPANY")) { session_register("SCOMPANY"); }
		if (!session_is_registered("SADDRESS1")) { session_register("SADDRESS1"); }
		if (!session_is_registered("SADDRESS2")) { session_register("SADDRESS2"); }
		if (!session_is_registered("SCITY")) { session_register("SCITY"); }
		if (!session_is_registered("SSTATE")) { session_register("SSTATE"); }
		if (!session_is_registered("SCOUNTRY")) { session_register("SCOUNTRY"); }
		if (!session_is_registered("SZIPCODE")) { session_register("SZIPCODE"); }
		if (!session_is_registered("SPHONE")) { session_register("SPHONE"); }

		if (!session_is_registered("SPHONE_COUNTRYCODE")) { session_register("SPHONE_COUNTRYCODE"); }
		if (!session_is_registered("SPHONE_AREACODE")) { session_register("SPHONE_AREACODE"); }
		if (!session_is_registered("SPHONE_PREFIX")) { session_register("SPHONE_PREFIX"); }
		if (!session_is_registered("SPHONE_SUFFIX")) { session_register("SPHONE_SUFFIX"); }

		if ( !isset($_SESSION['REPEATCUSTOMER']) ) { $_SESSION['REPEATCUSTOMER'] = $REPEATCUSTOMER; }
		$REPEATCUSTOMER = "YES";

		// Now let's place variables into the sessions that "recognize" user "like" a sec login client

		if (!session_is_registered("OWNER_NAME")) { session_register("OWNER_NAME"); }
		if (!session_is_registered("GROUPS")) { session_register("GROUPS"); }
		$OWNER_NAME = $BFIRSTNAME . " " . $BLASTNAME;
		$GROUPS = "";		// This user does not have any 'private' sku or page access

		// Since he came from the cart_customers table, let's send him to the cart

		$sc = 1;

	} // End "cart_customers" auth method

// ----------------------------------------------------------------------------------------
eval(hook("pgm-secure_login.php:before-redirect"));
if ($pa != "") { $REDIRECT_PAGE = pagename($pa); }
if ($sc == "1") { $REDIRECT_PAGE = "shopping/start.php?browse=1"; }

if (!eregi("http://$this_ip", $REDIRECT_PAGE)) {
 	$REDIRECT_PAGE = "http://$this_ip/".$REDIRECT_PAGE;
}

$REDIRECT_PAGE = str_replace("pgm-secure_login.php/", "", $REDIRECT_PAGE);
header("Location: $REDIRECT_PAGE");
exit;

} // End Authenticate Process

//function displayLogin(){
//   global $logged_in;
//   if($logged_in){
//      echo "<h1>Logged In!</h1>";
//      echo "Welcome <b>$_SESSION[username]</b>, you are logged in. <a href=\"logout.php\">Logout</a>";
//   }
//   else{
//      echo "not logged in<br>";
//   }
//}

#######################################################################################################
###### IF THE SESSION VARIABLE IS NOT ALREADY SET FOR "SOHO_AUTH" AND "SOHO_PW" THIS USER
###### NEEDS TO AUTHENTICATE AND LOGIN NOW.
#######################################################################################################
$module_active = "yes";				// Make sure to leave #CONTENT# variable intact when returning header/footer var
include_once('sohoadmin/client_files/pgm-realtime_builder.php');
if (!isset($SOHO_AUTH) || !isset($SOHO_PW) || !isset($GROUPS) || $notice = "noaccess") {		// Need to login now

// Check to see if cookie has username and password value
   if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){
      $_SESSION['username'] = $_COOKIE['cookname'];
      $_SESSION['password'] = $_COOKIE['cookpass'];
      $cook_user = $_SESSION['username'];
      $cook_pw = $_SESSION['password'];
   }

	if ($notice == "required") {
		$contentarea .= "<CENTER><font color=red size=2 face=Verdana><B>".lang("The page you have requested requires security access.")."\n";
		$contentarea .= "<BR>".lang("Please enter your username and password now.")."</B></font></CENTER><BR>\n\n";
	}

//	if ($notice == "noaccess") {
//
//		$contentarea .= "<BR><font color=red size=2 face=Arial>".lang("It appears your login does not grant you access to this page")."<BR>".lang("If you feel this is in error, please contact us for further assistance.")."</font><BR>\n";
//		$contentarea .= "<BR><a href=\"index.php?pr=Home_Page&=SID\">".lang("Click here")."</a> ".lang("to return to the home page")."<br>\n\n";
//
//	} else {

   	$this_ip = str_replace("/pgm-secure_login.php", "", $this_ip);
   	$contentarea .= "<FORM METHOD=POST ACTION=\"pgm-secure_login.php\">\n";
   	$contentarea .= "\n\n\n\n\n<!---This ip: [".strip_tags($this_ip)."]--->\n\n\n\n\n";
   	$contentarea .= "<INPUT TYPE=HIDDEN NAME=PROCESS VALUE=AUTHENTICATE>\n";

   	$contentarea .= "<INPUT TYPE=HIDDEN NAME=pa VALUE=\"".strip_tags($pa)."\">\n";
   	$contentarea .= "<INPUT TYPE=HIDDEN NAME=sc VALUE=\"".strip_tags($sc)."\">\n";

   	$contentarea .= "<table id=\"login_box\" width=\"300\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" align=\"center\" class=\"border\" bgcolor=\"#FFFFFF\">\n";
   	$contentarea .= " <tr>\n";
   	$contentarea .= "  <td class=text bgcolor=#999999><font color=\"#FFFFFF\" face=Verdana><b>".lang("Please Login")."\n";
   	$contentarea .= "  </b></font></td>";
   	$contentarea .= " </tr>\n";
   	$contentarea .= " <tr align=\"center\" valign=\"top\">\n";
   	$contentarea .= "  <td bgcolor=#EFEFEF>\n";
   	$contentarea .= "   <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=txt bgcolor=#EFEFEF>\n";
   	$contentarea .= "    <tr>\n";
   	$contentarea .= "     <td valign=\"top\" align=left class=text><font color=black>".lang("Username").":</font><br>\n";
   	$contentarea .= "      <input type=\"text\" value=\"".$cook_user."\" name=\"SOHO_AUTH\" class=text style='width: 250px';>\n";
   	$contentarea .= "      <br>\n";
   	$contentarea .= "      <Font color=black>".lang("Password").":</font><br>\n";
   	$contentarea .= "      <input type=\"password\" value=\"".$cook_pw."\" name=\"SOHO_PW\" class=text style='width: 250px';>\n";
   	$contentarea .= "      <br><br>\n";
   	$contentarea .= "      <input type=\"checkbox\" name=\"remember\"> ".lang("Remember me")."!\n";
   	//$contentarea .= "      <div align=right>\n";
   	//$contentarea .= "      <img src=spacer.gif width=150 height=2 border=0><BR>\n";
   	$contentarea .= "      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
   	$contentarea .= "      <input type=submit class=FormLt1 style='cursor: hand;' value=\" ".lang("Login")." \">\n";
   	$contentarea .= "      <BR>\n";
   	$contentarea .= "     </td>\n";
   	$contentarea .= "    </tr>\n";
   	$contentarea .= "   </table>\n";
   	$contentarea .= "  </td>\n";
   	$contentarea .= " </tr>\n";
   	$contentarea .= "</table>\n";
   	$contentarea .= "<div align=center>\n";



	if ($err == "noauth") {
		$contentarea .= "<BR><font color=red size=2 face=Arial>".lang("Sorry, we do not recognize that username and password.<BR>Please check your spelling and try again")."</font><BR>\n";
	}

	if ($err == "expire") {
		$contentarea .= "<BR><font color=red size=2 face=Arial>".lang("It appears the username and password that you entered has expired.")."<BR>".lang("Your access is no longer available.")."</font><BR><BR>\n";
		$contentarea .= "<BR><a href=\"".pagename(startpage())."\">".lang("Click here")."</a> ".lang("to return to the home page.")."<BR>\n\n";
	}
$this_ip = str_replace("/pgm-secure_login.php", "", $this_ip);
	$contentarea .= "<font size=2 face=Arial, Helvetica>&nbsp;<BR>".lang("Forget your password?")." <a href=\"http://$this_ip/pgm-secure_remember.php\">".lang("Click here")."</a>.<BR></div>\n";
	$contentarea .= "</FORM>\n";

eval(hook("pgm-secure_login.php:below-forgot-password"));

} else {

#######################################################################################################
###### IF _AUTH AND _PW VARS ARE SET IN MEMORY, LET'S CHECK TO MAKE SURE THEY AUTHENTICATE AGAINST
###### THE sec_users DATA TABLE AND (IF EXISTS) THE cart_customers TABLE.
#######################################################################################################

if (!isset($REDIRECT_PAGE)) { $REDIRECT_PAGE = pagename(startpage()); }

if (!eregi("http://$this_ip", $REDIRECT_PAGE)) {
 	$REDIRECT_PAGE = "http://$this_ip/".$REDIRECT_PAGE;
}

header("Location: $REDIRECT_PAGE");
exit;

}

#######################################################################################################
##### BUILD PAGE AND DISPLAY CONTENT NOW
#######################################################################################################



echo ("$template_header\n");			// Go ahead and display header now

#######################################################################################################

// **************************************************************************
// Replace intact #CONTENT# var with $contentarea created within this script
// **************************************************************************

$template_footer = eregi_replace("#CONTENT#", $contentarea, $template_footer);

// **************************************************************************
// Display template footer var from realtime_builder and close out this page
// **************************************************************************

echo ("$template_footer\n");

exit;

?>
