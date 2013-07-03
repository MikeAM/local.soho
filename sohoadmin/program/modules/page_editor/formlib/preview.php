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

session_start();
error_reporting(0);

require_once('../../../includes/product_gui.php');

?>

<script language="JavaScript">

function killErrors() {
	return true;
}
window.onerror = killErrors;

function MM_findObj(n, d) { //v3.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
}

function MM_showHideLayers() { //v3.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
    obj.visibility=v; }
}

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

</script>

<?php
if($_GET['lookat']!=''){
	$lookat=$_GET['lookat'];
}

if ($lookat != "") {
	$action_bg = "beige";
} else {
	$action_bg = "darkblue";
}

?>

<table width="100%" border="1" cellspacing="0" cellpadding="7" STYLE='border: 0px solid green;'>
  <tr>
    <td align=center valign=middle>

    	<?php
		
		if ($lookat != "") {

			$filename = $lookat;
			if (file_exists($filename)) {
			$file = fopen("$filename", "r");
				$body = fread($file,filesize($filename));
			fclose($file);
			$lines = split("\n", $body);

			$body = eregi_replace("type=submit", "type=button", $body);
			$body = eregi_replace("type=\"submit", "type=\"button", $body);
			$body = eregi_replace("action=", "name=", $body);

			}

			echo ("$body\n");

		} else {
		   echo "<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\">\n";
		   echo "<b>[".lang("Form Preview Area")."]</b><br>\n";
		   echo lang("Choose a form from the Available Forms drop-down box above and click the Preview Selected Form button to see the selected form in this window.")."<br/><br/>\n";
		   echo "<B>".lang("NOTE: MOST FORMS NEED ALL 3 COLUMNS TO DISPLAY PROPERLY")."</b>\n";
		}


		?>

    </td>
  </tr>
</table>
