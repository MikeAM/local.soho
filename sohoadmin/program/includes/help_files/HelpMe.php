<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
<link href="../../product_gui.css" rel="stylesheet" type="text/css">

<style type="text/css">
<!--
.bordTop {
	border-top: 1px solid #666666;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	padding-left: 12px;
}
.bordBott {
	border-top: 1px solid #666666;
}
.bordRight {
	border-top: 1px solid #666666;
}
.bordLeft {
	border-top: 1px solid #666666;
}
-->
</style>

<script language="javascript">

function showLayer(daLayer){
	if(daLayer == 'diag'){
		document.getElementById(daLayer).style.display='block';
		document.getElementById('videos').style.display='none';
		document.getElementById('faq').style.display='none';
	}
	if(daLayer == 'videos'){
		document.getElementById(daLayer).style.display='block';
		document.getElementById('diag').style.display='none';
		document.getElementById('faq').style.display='none';
	}
	if(daLayer == 'faq'){
		document.getElementById(daLayer).style.display='block';
		document.getElementById('diag').style.display='none';
		document.getElementById('videos').style.display='none';
	}
}

function showGlobals(daType){
	if(daType == 'globals'){
		if(document.getElementById(daType).style.display == 'block'){
			document.getElementById(daType).style.display='none';
		}else{
			document.getElementById(daType).style.display='block';
			document.getElementById('perms').style.display='none';
		}
	}
	if(daType == 'perms'){
		if(document.getElementById(daType).style.display == 'block'){
			document.getElementById(daType).style.display='none';
		}else{
			document.getElementById(daType).style.display='block';
			document.getElementById('globals').style.display='none';
		}
	}
}



</script>


</head>

<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="feature_group">
  <tr>
    <td class="fgroup_title">Help Center</td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="33%" align="center" class="col_sub" onclick="showLayer('videos');" style="cursor: pointer; border-right:1px solid #666666;">View Flash Videos </td>
        <td width="33%" align="center" class="col_sub" onclick="showLayer('diag');" style="cursor: pointer; border-right:1px solid #666666;">Diagnostic</td>
        <td align="center" class="col_sub" onclick="showLayer('faq');" style="cursor: pointer;">Faq's</td>
      </tr>
      <tr>
        <td align="center" colspan="3"><div id="videos" style="display:none;">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="bordTop" colspan="4" height="20">Here is a list of flash videos that should help you with any questions about #SITEBUILDER# </td>
            </tr>
            <tr>
              <td colspan="2" width="50%" style="padding-left:15px;" align="left" height="30"><a href="demos/adminonew.swf" border="0">Main Menu</a></td>
              <td colspan="2" style="padding-left:15px;" align="left" ><a href="demos/texteditornew.swf" border="0">Text Editor</a></td>
            </tr>
            <tr>
              <td colspan="2" style="padding-left:15px;" align="left" height="30"><a href="demos/album_new.swf" border="0">Photo Album Manager</a></td>
              <td colspan="2" style="padding-left:15px;" align="left" ><a href="demos/templatenew.swf" border="0">Template Manager</a></td>
            </tr>
            <tr>
              <td colspan="2" style="padding-left:15px;" align="left" height="30"><a href="demos/backingupnewg.swf" border="0">Site Backup/Restore</a></td>
              <td colspan="2" style="padding-left:15px;" align="left" ><a href="demos/statisticsnew.swf" border="0">Site Statistics</a></td>
            </tr>
            <tr>
              <td colspan="2" style="padding-left:15px;" align="left" height="30"><a href="demos/blognewskip.swf" border="0">Blog Manager</a></td>
              <td colspan="2" style="padding-left:15px;" align="left" ><a href="demos/securenew.swf" border="0">Secure Users</a></td>
            </tr>
            <tr>
              <td colspan="2" style="padding-left:15px;" align="left" height="30"><a href="demos/Calendar_New.swf" border="0">Event Calendar</a></td>
              <td colspan="2" style="padding-left:15px;" align="left" ><a href="demos/quicknew.swf" border="0">Quick Start Wizard</a></td>
            </tr>
            <tr>
              <td colspan="2" style="padding-left:15px;" align="left" height="30"><a href="demos/cart2shipping.swf" border="0">Shopping Cart - Shipping Options</a></td>
              <td colspan="2" style="padding-left:15px;" align="left" ><a href="demos/Pageeditornew.swf" border="0">Page Editor</a></td>
            </tr>
            <tr>
              <td colspan="2" style="padding-left:15px;" align="left" height="30"><a href="demos/cartcontactinfo.swf" border="0">Shopping Cart - Contact Information</a></td>
              <td colspan="2" style="padding-left:15px;" align="left" ><a href="demos/newsletternew.swf" border="0">eNewsletter Campaign Manager</a></td>
            </tr>
            <tr>
              <td colspan="2" style="padding-left:15px;" align="left" height="30"><a href="demos/cartpayment.swf" border="0">Shopping Cart - Payment Options</a></td>
              <td colspan="2" style="padding-left:15px;" align="left" ><a href="demos/menunew.swf" border="0">Auto Menu System</a></td>
            </tr>
            <tr>
              <td colspan="2" style="padding-left:15px;" align="left" height="30"><a href="demos/creatingpagennew.swf" border="0">Create Pages</a></td>
              <td colspan="2" style="padding-left:15px;" align="left" ><a href="demos/Form_Manager_new.swf" border="0">Forms Manager</a></td>
            </tr>
            <tr>
              <td colspan="2" style="padding-left:15px;" align="left" height="30"><a href="demos/database_new.swf" border="0">Database Table Manager</a></td>
              <td colspan="2" style="padding-left:15px;" align="left" ><a href="demos/fileman_new.swf" border="0">File Manager</a></td>
            </tr>
            <tr>
              <td colspan="2" style="padding-left:15px;" align="left" height="30"><a href="demos/faqnew.swf" border="0">FAQ Manager</a></td>
              <td colspan="2" style="padding-left:15px;" align="left" >&nbsp;</td>
            </tr>
          </table>
        </div>
              <div id="diag" style="display:block;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="bordTop" colspan="3" height="20">If you are experiencing trouble with #SITEBUILDER#, here are a list of items you may want to check.</td>
                  </tr>

									<!-- GLOBAL SETTINGS -->

                  <tr>
                    <td colspan="4" class="fsub_title" style="cursor: pointer;" onclick="showGlobals('globals');">Global Settings (isp.conf.php) </td>
                  </tr>
                  <tr>
                    <td colspan="4" align="center">
					<div id="globals" style="display:block;">
						<table width="100%" border="0" cellpadding="5" cellspacing="0">
                          <tr>
                            <td class="col_sub">Setting</td>
                            <td width="50%" class="col_sub">Value</td>
                            <td align="center" class="col_sub">Status</td>
                          </tr>
                          <tr>
                            <td>this_ip</td>
                            <td>joe.com</td>
                            <td align="center">OK</td>
                          </tr>
                          <tr>
                            <td>cgi_bin</td>
                            <td>home/joe/sohoadmin/tmp_content</td>
                            <td align="center">OK</td>
                          </tr>
                          <tr>
                            <td>doc_root</td>
                            <td>home/joe</td>
                            <td align="center">OK</td>
                          </tr>
                          <tr>
                            <td>dflogin_user</td>
                            <td>joe</td>
                            <td align="center">OK</td>
                          </tr>
                          <tr>
                            <td>dflogin_pass</td>
                            <td>pass</td>
                            <td align="center">OK</td>
                          </tr>
                          <tr>
                            <td>template_lib</td>
                            <td>home/joe/sohoadmin/program/modules/site_templates/pages</td>
                            <td align="center">OK</td>
                          </tr>
	                      <tr>
                    	      <td>demo_site</td>
	            	          <td>No</td>
    	    	              <td align="center">OK</td>
        	              </tr>
                   	  </table>
                    </div>
					</td>
                  </tr>

									<!-- PERMISSIONS -->

                  <tr>
                    <td colspan="4" class="fsub_title" style="cursor: pointer;" onclick="showGlobals('perms');">Permissions</td>
                  </tr>
                  <tr>
                    <td colspan="4" align="center">
					<div id="perms" style="display:none;">
						<table width="100%" border="0" cellpadding="5" cellspacing="0">
                          <tr>
                            <td width="25%" class="col_sub"><strong>Folder</strong></td>
                            <td class="col_sub"><strong>Permissions </strong></td>
                            <td class="col_sub"><strong>Owner</strong></td>
                            <td class="col_sub"><strong>Group</strong></td>
                            <td class="col_sub"><strong>Writeable</strong></td>
                            <td align="center" class="col_sub"><strong>Status</strong></td>
                          </tr>
                          <tr>
                            <td>doc root</td>
                            <td>0777</td>
                            <td>soho</td>
                            <td>root</td>
                            <td>Yes</td>
                            <td align="center">OK</td>
                          </tr>
                          <tr>
                            <td>images</td>
                            <td>0777</td>
                            <td>soho</td>
                            <td>root</td>
                            <td>Yes</td>
                            <td align="center">OK</td>
                          </tr>
                          <tr>
                            <td>media</td>
                            <td>0777</td>
                            <td>soho</td>
                            <td>root</td>
                            <td>Yes</td>
                            <td align="center">OK</td>
                          </tr>
                          <tr>
                            <td>template</td>
                            <td>0777</td>
                            <td>soho</td>
                            <td>root</td>
                            <td>Yes</td>
                            <td align="center">OK</td>
                          </tr>
                          <tr>
                            <td>tCustom</td>
                            <td>0777</td>
                            <td>soho</td>
                            <td>root</td>
                            <td>Yes</td>
                            <td align="center">OK</td>
                          </tr>
                          <tr>
                            <td>sohoadmin/tmp_content/template.conf</td>
                            <td>0777</td>
                            <td>soho</td>
                            <td>root</td>
                            <td>Yes</td>
                            <td align="center">OK</td>
                          </tr>
                          <tr>
                            <td>media/page_templates.txt</td>
                            <td>0777</td>
                            <td>soho</td>
                            <td>root</td>
                            <td>Yes</td>
                            <td align="center">OK</td>
                          </tr>
                          <tr>
                            <td>sohoadmin/tmp_content</td>
                            <td>0777</td>
                            <td>soho</td>
                            <td>root</td>
                            <td>Yes</td>
                            <td align="center">OK</td>
                          </tr>
                          <tr>
                            <td>sohoadmin/program/modules/site_templates/pages</td>
                            <td>0777</td>
                            <td>soho</td>
                            <td>root</td>
                            <td>Yes</td>
                            <td align="center">OK</td>
                          </tr>
                   	  </table>
                    </div>
					</td>
                  </tr>
                </table>
              </div>
          <div id="faq" style="display:none;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="bordTop" colspan="4" height="20">Here are a list of frequently asked questions.</td>
                  </tr>
                </table>
          </div>
		</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
