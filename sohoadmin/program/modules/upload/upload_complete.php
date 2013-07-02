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
###############################################################################

session_start();

require_once("../../includes/product_gui.php");

error_reporting(0);

###############################################################################

?>


<html>
<head>
<title><? echo lang("Upload Success"); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">

<link rel="stylesheet" href="../soholaunch.css">

<script language="JavaScript">
<!--

function killErrors() {
		return true;
	}

window.onerror = killErrors;

function MM_popupMsg(msg) { //v1.0
  alert(msg);
}

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

//-->
</script>

<script language="JScript">

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function update_status(text) {
	// STATUS UPDATE
}

function start_upload() {
	MM_showHideLayers('Layer2','','hide','Layer1','','hide','Layer3','','show');
}

</script>

</head>

<body bgcolor=white text=black link=darkblue vlink=darkblue alink=darkblue leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>

<!-- ======================================================================================================== -->
<!-- ======================================================================================================== -->
<!-- ======================================================================================================== -->


<DIV ID="Layer2" STYLE="position:absolute; left: 0px; top:0px; width:100%; height:100%; z-index:10; background-image: url(../../useropts_bg.jpg); layer-background-image: url(../../useropts_bg.jpg); overflow: auto;">



<img src="../spacer.gif" height=5 width=725>
<table width="725" border="0" cellspacing="0" cellpadding="0" align=center class=border>
  <tr>
    <td align="center" valign="top">

      <table border=0 cellpadding=0 cellspacing=0 bgcolor="#336699" width=725>
        <tr>
          <td align=left valign=top>

	<TABLE CLASS="clsNavLinks" CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="725">
	   <TR>
		<TD NOWRAP BGCOLOR="#336699" VALIGN=TOP WIDTH=100%><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#FFFFFF"><B><IMG SRC="../arrow.gif" WIDTH="17" HEIGHT="13" ALIGN="absmiddle"><? echo $lang["Upload Complete"]; ?>!</B></FONT><FONT COLOR=WHITE>&nbsp;</FONT></TD>
	   </TR>
	 </TABLE>

	</td>
        </tr>
        <tr>
          <td align=center valign=top>
            <table border="0" cellspacing="0" cellpadding="10" width="100%" bgcolor=white>
              <tr>
                <td align="center" valign="top" class=text>
                 <BR>
                  <?
                  echo lang("Below is a report of the files that were uploaded during this operation")."<br><br>\n\n";
                  ?>



<table border="0" cellspacing="5" cellpadding="5" align="center" class="allBorder">

<?

$success = stripslashes($success);

$tmp = split("\|\|", $success);
$num = count($tmp);
$num--;

		for ($x=1;$x<=$num;$x++) {
			$number = $x;
			if ($class == "class=text") { $class = "class=text"; } else { $class = "class=text"; }

			$display = split("~~~", $tmp[$x]);

				$tmpr = lang("Filename");	// International Language Support
				$tmpr = eregi_replace("#", "$number", $tmpr);

			echo (" <tr><td align=right valign=middle $class>$tmpr:</td><td align=left valign=middle $class>$display[0]</td><td align=left valign=middle $class>$display[1]</td><td align=left valign=middle $class>$display[2]</td></tr>\n");

		}

echo ("</table><BR clear=all><form method=post action=\"../upload_files.php\">\n");
echo ("<div align=center><input type=submit value=\"".lang("Upload More Files")." >>\" name=\"submit\" class=\"FormLt1\"></div>\n");
echo ("</form><BR><BR>\n");

		// Added for Multi-User Access Rights
		// ------------------------------------------------------------------------

		if ($CUR_USER_ACCESS == "WEBMASTER") {
			echo "<a href=\"../site_files.php?=SID\">[ ".lang("View Current Site Files")." ]</a>";
		}

?>

&nbsp;&nbsp;&nbsp;&nbsp;
<A href="../../main_menu.php?=SID">[ <? echo lang("Main Menu"); ?> ]</a>
&nbsp;&nbsp;&nbsp;&nbsp;
<A href="../open_page.php?=SID">[ <? echo lang("Open/Edit Page(s)"); ?> ]</a>
&nbsp;&nbsp;&nbsp;&nbsp;


<?

		// Added for V5 Multi-User Access Rights
		// ------------------------------------------------------------------------

		if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_TEMPLATES;", $CUR_USER_ACCESS)) {
			if ($language != "english.php") { echo "<BR>"; }
			echo "<a href=\"../site_templates.php?=SID\">[ ".lang("Upload Custom Template HTML")." ]</a>";
		}

?>

</td></tr></table>

 </FONT></b></td>
              </tr>
            </table>
          </td>
        </tr>
      </TABLE>
    </td>
  </tr>
</table>
</DIV>

</body>
</html>
