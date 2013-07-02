<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

include_once("../program/includes/product_gui.php");
/*---------------------------------------------------------------------------------------------------------*
   _____                   __   ____                                   __
  / ___/ ___   ____   ____/ /  / __ \ ___   ____ _ __  __ ___   _____ / /_
  \__ \ / _ \ / __ \ / __  /  / /_/ // _ \ / __ `// / / // _ \ / ___// __/
 ___/ //  __// / / // /_/ /  / _, _//  __// /_/ // /_/ //  __/(__  )/ /_
/____/ \___//_/ /_/ \__,_/  /_/ |_| \___/ \__, / \__,_/ \___//____/ \__/
                                            /_/
/*---------------------------------------------------------------------------------------------------------*/
if ( isset($_POST['upgrade']) ) {

   # Declare error vars
   $noMods = "";
   $errNo = 1;

   # Make sure contact email submitted
   if ( $upgrade['email'] == "" ) {
      $noEmail = "border: 1px solid #D70000;";
      $noMods .= " <tr><td colspan=\"2\" class=\"bg_gray red\"><b>Error ".$errNo.":</b> <span class=\"nobold\">You must provide a valid email address.</span><br></td></tr>";
      $errNo++;
   }

   # Make sure at least one mod choosen
   if ( count($_POST['upgrade_mods']) < 1 ) {
      $noMods .= " <tr><td colspan=\"2\" class=\"bg_gray red\"><b>Error ".$errNo.":</b> <span class=\"nobold\">You must select at least one upgrade module.</span><br></td></tr>";
      $errNo++;
   }

   # Send or show errors?
   if ( $noMods == "" ) { // Good to go!

      # Build email notifiaction
      $reqEmail = "";
      $reqEmail .= "<div style=\"width: 80%; border: 0px solid #6699cc; padding: 15px;\">\n";
      $reqEmail .= " One of your domain accounts has requested a feature upgrade through \n";
      $reqEmail .= " the ".$sitebuilder_name." interface, and is awaiting your response.<br><br>\n";

      $reqEmail .= " <b><u>Account Information</u></b><br>\n";
      $reqEmail .= " Contact Email: <span style=\"color: #D70000;\">".$upgrade['email']."</span><br>\n";
      $reqEmail .= " Domain Name: <span style=\"color: #D70000;\">".$upgrade['domain']."</span>\n<br>";
      $reqEmail .= " Server Host: <span style=\"color: #980000;\">".php_uname(n)."</span><br>\n";
      $reqEmail .= " IP Address: <span style=\"color: #980000;\">".$_SERVER['SERVER_ADDR']."</span><br>\n";

      $reqEmail .= " <br><b><u>Feature upgrades requested:</u></b><br>\n";

      # List modules to enable
      foreach ( $_POST['upgrade_mods'] as $modname ) {
         $reqEmail .= " [<span style=\"color: #D70000;\"><b>X</b></span>] ".$modname."<br>\n";
      }

      $reqEmail .= " <br><br>";
      $reqEmail .= " When you're ready to enable the features, \n";
      $reqEmail .= " log-in to the <a href=\"http://partner.soholaunch.com\" target=\"_blank\">Soholaunch Partner Area</a> and edit the domain license for '".$upgrade['domain']."'.\n<br><br>";

      $reqEmail .= " <span style=\"color: #CCC; font-style: italic;\">\n";
      $reqEmail .= "  NOTE: You are receiving this email because you have configured your branding options\n";
      $reqEmail .= "  so that end-user upgrade requests are emailed directly to you.\n";
      $reqEmail .= "  You may change these settings by logging-in to the Soholaunch Partner Area \n";
      $reqEmail .= "  and selecting 'Branding Options' from the menu (under Account Settings).\n";
      $reqEmail .= " </span>\n";

      $reqEmail .= "</div>\n";

      # Build email header info
      $email_header = "From: sales@soholaunch.com\n";
      $email_header .= "Content-Type: text/html; charset=us-ascii;\n";
      $email_header .= "Content-Transfer-Encoding: 7bit;\n";
      $email_header .= "Content-Disposition: inline;\n\n";

      # Display email contents for testing
      //echo $reqEmail;

      # Send email to host now!
      mail($sendto, "UPGRADE REQUEST: ".$upgrade['domain'], $reqEmail, $email_header);

      # Display 'Thank You' message
      echo "<div class=\"green\" style=\"width: 80%; position: absolute; top: 100px; left: 75px; font-stretch: condensed; font: 18px italic arial; text-align: center; border: 1px solid #6699cc; padding: 15px;\">\n";
      echo " Thank you for submitting your upgrade request!<br><br>\n";
      echo " <div class=\"gray\" style=\"text-align: justify; font: 12px arial;\">\n";
      echo "  Once we receive your request a customer service representative will contact you at the email address you provided to confirm your account information and answer any questions you may have.<br><br>\n";
      echo "  When we have comfirmed and processed your upgrade request, the new features will be available the next time you log in.<br><br>\n";
      echo " </div>\n";
      echo " <div class=\"gray\" style=\"text-align: right; font: 12px arial;\">\n";
      echo "  <a href=\"../program/user_options_46.php\">Return to Main Menu</a>\n";
      echo " </div>\n";
      echo "</div>\n";
      exit;


   } else { // Show error messages
      $error_table = "<table width=\"550\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"feature_red\" align=\"center\">\n";
      $error_table .= $noMods;
      $error_table .= "</table>\n";
   }

} // End form submit action


?>
<br>

<?
# Display error messages (if any)
echo $error_table;
?>

<form name="upgrade_form" action="promotion.php" method="post">
<input type="hidden" name="todo" value="upgrade_form">
<input type="hidden" name="sendto" value="<? echo $sendto; ?>">
<input type="hidden" name="mod" value="<? echo $mod; ?>">

<table width="550"  border="0" align="center" cellpadding="5" cellspacing="0" class="feature_group">
 <tr>
  <td colspan="2" class="fgroup_title">
   Feature Upgrade Request
  </td>
 </tr>
 <tr>
  <td colspan="2" class="bg_blue">
   Once we receive your request a customer service representative will contact you at the email address you provide to confirm your account information and answer any questions you may have.<br>
   <!---<br>When we have comfirmed and processed your upgrade request, the new features will be available the next time you log in.--->
  </td>
 </tr>
 <tr>
  <td colspan="2" class="col_title">
   Basic account information
  </td>
 </tr>
 <tr>
  <td width="120">
   My domain name:
  </td>
  <td width="406">
   http://<span class="red"><? echo $_SERVER['HTTP_HOST']; ?></span>
   <input name="upgrade[domain]" value="<? echo $_SERVER['HTTP_HOST']; ?>" type="hidden">
  </td>
 </tr>
 <tr>
  <td>
   My email address:
  </td>
  <td>
   <input name="upgrade[email]" value="<? echo $getSpec['df_email']; ?>" type="text" class="tfield orange" style="width: 200px;<? echo $noEmail; ?>">
  </td>
 </tr>
 <tr>
  <td colspan="2" class="col_title">
   <b>Which upgrade modules would you like to enable? </b>   </td>
 </tr>
 <tr>
  <td colspan="2" style="padding-left: 25px; padding-top: 0px;">
   <table width="100%" border="0" cellpadding="2" cellspacing="0" class="text">
    <tr>
     <td>
      <input name="upgrade_mods[]" type="checkbox" id="calendar" value="Event Calendar">
     </td>
     <td>
      <a href="promotion.php?mod=calendar">Event Calendar</a>
     </td>
    </tr>
    <tr>
     <td>
      <input name="upgrade_mods[]" type="checkbox" id="cart" value="Shopping Cart">
     </td>
     <td>
      <a href="promotion.php?mod=cart">Shopping Cart</a>
     </td>
    </tr>
    <tr>
     <td>
      <input name="upgrade_mods[]" type="checkbox" id="enewsletter" value="eNewsletter">
     </td>
     <td>
      <a href="promotion.php?mod=enewsletter">eNewsletter</a>
     </td>
    </tr>
    <tr>
     <td>
      <input name="upgrade_mods[]" type="checkbox" id="blog" value="Blog Manager">
     </td>
     <td>
      <a href="promotion.php?mod=blog">Blog Manager</a>
     </td>
    </tr>
    <tr>
     <td>
      <input name="upgrade_mods[]" type="checkbox" id="secure" value="Secure Users">
     </td>
     <td>
      <a href="promotion.php?mod=secure">Secure Users</a> + <a href="promotion.php?mod=backup">Site Backup &amp; Restore</a>
     </td>
    </tr>
    <tr>
     <td>
      <input name="upgrade_mods[]" type="checkbox" id="dbtables" value="Database Table Manager">
     </td>
     <td>
      <a href="promotion.php?mod=dbtables">Database Table Manager</a>
     </td>
    </tr>
    <tr>
     <td>&nbsp;
     </td>
     <td width="100%">&nbsp;
     </td>
    </tr>
    <tr>
     <td colspan="2" align="right">
      <input name="Submit" type="submit" class="btn_save" value="Submit Upgrade Request" onMouseOver="this.className='btn_saveon';" onMouseOut="this.className='btn_save';">
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>

</form>
<br><br><br><br>

<script language="javascript">

<?
# Build array of module names
$modz = array('calendar', 'cart', 'enewsletter', 'blog', 'secure', 'backup', 'dbTables');

# Disable checkboxes for already-licensed mods
foreach ( $modz as $modname ) {

   # Account for backup being bundled with secure users
   if ( $modname == "backup" ) { $modname = "secure"; }

   if ( eregi("promotion.php", ${$modname}['link']) ) {
      //echo "alert('".$modname.": NO');\n";
   } else {
      //echo "alert('".$modname.": YES');\n";
      echo "document.getElementById('".$modname."').disabled=true;\n";
   }

}

# Pre-check box for clicked-through module (as in, what they originally tried to click on the Main Menu)
if ( $mod == "backup" ) { $modname = "secure"; } else { $modname = $mod; }
echo "document.getElementById('".$modname."').checked=true;\n";


?>

</script>