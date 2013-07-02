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
track_vars;

##########################################################################
### INSERT FUNCTION TO KILL ALL NON ALPHA/NUMERIC CHARACTERS FROM DATA
### FOR DATABASE STORAGE
##########################################################################
require_once('../../../../includes/product_gui.php');
function sterilize_char ($sterile_var) {

	$sterile_var = stripslashes($sterile_var);
	$sterile_var = eregi_replace(";", ",", $sterile_var);
	$sterile_var = eregi_replace(" ", "_", $sterile_var);

	$st_l = strlen($sterile_var);
	$st_a = 0;
	$tmp = "";

	while($st_a != $st_l) {
		$temp = substr($sterile_var, $st_a, 1);
		if (eregi("[0-9a-z_]", $temp)) { $tmp .= $temp; }
		$st_a++;
	}

	$sterile_var = $tmp;
	return $sterile_var;

}

###########################################################################
### Processes the "Complete Form" Directive Now
###########################################################################

if (isset($COMPLETE_FORM)) {

	$required_str = "";	
	$FORM_NAME = stripslashes($FORM_NAME);
	$FORM_NAME = eregi_replace(" ", "_", $FORM_NAME);
	
	$HTML_OUTPUT = "<FORM NAME=\"$FORM_NAME\" METHOD=POST ACTION=\"pgm-form_submit.php\">\n\n";
	$HTML_OUTPUT .= "<TABLE ALIGN=CENTER BORDER=0 CELLPADDING=6 CELLSPACING=0 STYLE='BACKGROUND: #EFEFEF; BORDER: 1PX SOLID BLACK' CLASS=text>\n\n##REQ##\n\n";
		
	for ($x=1;$x<=$COUNTER;$x++) {
	
		$label = "LABEL_".$x;
		$label = ${$label};
		
		$label = eregi_replace(":", "", $label);
		
		if (strlen($label) < 2 && $COUNTER == 1) {
			header("Location: ../forms.php?dropArea=$dropArea&selkey=Forms&=SID");
			exit;			
		}
		
		$ftype = "FIELD_TYPE_".$x;
		$ftype = ${$ftype};
		
		$req_chk = "REQ_".$x;
		$req_chk = ${$req_chk};
		
		$fname = "FIELD_NAME_".$x;
		$fname = ${$fname};
		
		$fname = eregi_replace(" ", "_", $fname);
		$fname = stripslashes($fname);
		
		$fvalue = "FIELD_VALUES_".$x;
		$fvalue = ${$fvalue};
		
		$label = stripslashes($label);
		$fname = sterilize_char($fname);
		
		if (strlen($label) > 2) {
		
					$REQ_DISPLAY = "";
					
					if ($req_chk == "Y" && $ftype != "CHECKBOX") {
							$required_str .= "$fname;";
							$REQ_DISPLAY = "<FONT COLOR=RED>*</FONT>";
					}
					
					if ($ftype == "TEXT") {
						$HTML_OUTPUT .= "\n<TR><TD ALIGN=RIGHT VALIGN=TOP WIDTH=>\n";
						$HTML_OUTPUT .= "$REQ_DISPLAY<font size=2 face=\"Verdana, Arial, Helvetica, sans-serif\">$label:</font></TD>\n<TD ALIGN=LEFT VALIGN=TOP CLASS=text>";
						$HTML_OUTPUT .= "<INPUT TYPE=TEXT NAME=\"$fname\" CLASS=text STYLE='width: 275px;'></TD></TR>\n";
					}
					
					if ($ftype == "TEXTAREA") {
						$HTML_OUTPUT .= "\n<TR><TD ALIGN=RIGHT VALIGN=TOP>\n";
						$HTML_OUTPUT .= "$REQ_DISPLAY<font size=2 face=\"Verdana, Arial, Helvetica, sans-serif\">$label:</font></TD>\n<TD ALIGN=LEFT VALIGN=TOP CLASS=text>";
						$HTML_OUTPUT .= "<TEXTAREA WRAP=VIRTUAL NAME=\"$fname\" CLASS=text ROWS=5 STYLE='width: 275px;'></TEXTAREA></TD></TR>\n";
					}
					
					if ($ftype == "DROPDOWN") {
						$HTML_OUTPUT .= "\n<TR><TD ALIGN=RIGHT VALIGN=TOP>\n";
						$HTML_OUTPUT .= "$REQ_DISPLAY<font size=2 face=\"Verdana, Arial, Helvetica, sans-serif\">$label:</font></TD>\n<TD ALIGN=LEFT VALIGN=TOP CLASS=text>";
						$HTML_OUTPUT .= "<SELECT NAME=\"$fname\" CLASS=text STYLE='width: 275px;'>\n";
						
							$tmp = split(",", $fvalue);
							$tmp_cnt = count($tmp);
							for ($y=0;$y<=$tmp_cnt;$y++) {
								if (strlen($tmp[$y]) > 2) {
									$tmp[$y] = rtrim($tmp[$y]);
									$tmp[$y] = ltrim($tmp[$y]);
									$HTML_OUTPUT .= "     <OPTION VALUE=\"$tmp[$y]\">$tmp[$y]</OPTION>\n";
								}
							}
										
						$HTML_OUTPUT .= "</TD></TR>\n";
					}
					
					if ($ftype == "RADIO") {
					
						$HTML_OUTPUT .= "\n<TR><TD ALIGN=RIGHT VALIGN=TOP>\n";
						$HTML_OUTPUT .= "$REQ_DISPLAY<font size=2 face=\"Verdana, Arial, Helvetica, sans-serif\">$label:</font></TD>\n<TD ALIGN=LEFT VALIGN=TOP CLASS=text WIDTH=275>";
						
							$tmp = split(",", $fvalue);
							$tmp_cnt = count($tmp);
							$checker = 1;
							for ($y=0;$y<=$tmp_cnt;$y++) {
								if (strlen($tmp[$y]) > 2) {
									$tmp[$y] = rtrim($tmp[$y]);
									$tmp[$y] = ltrim($tmp[$y]);
									$dis = $tmp[$y];
									$tmp[$y] = eregi_replace(" ", "_", $tmp[$y]);
									if ($checker == 1) { $CHK_ME = "CHECKED"; } else { $CHK_ME = ""; }
									$HTML_OUTPUT .= "<INPUT TYPE=RADIO NAME=\"$fname\" VALUE=\"$tmp[$y]\" $CHK_ME><font size=2 face=\"Verdana, Arial, Helvetica, sans-serif\">$dis</font>&nbsp;&nbsp;\n";
									$checker++;
								}
							}
										
						$HTML_OUTPUT .= "</TD></TR>\n";
					}
					
					if ($ftype == "CHECKBOX") {
						$HTML_OUTPUT .= "\n<TR><TD ALIGN=RIGHT VALIGN=TOP>\n";
						$HTML_OUTPUT .= "$REQ_DISPLAY<font size=2 face=\"Verdana, Arial, Helvetica, sans-serif\">$label:</font></TD>\n<TD ALIGN=LEFT VALIGN=TOP CLASS=text WIDTH=275>";

						
							$tmp = split(",", $fvalue);
							$tmp_cnt = count($tmp);
							for ($y=0;$y<=$tmp_cnt;$y++) {
								if (strlen($tmp[$y]) > 2) {
									$tmp[$y] = rtrim($tmp[$y]);
									$tmp[$y] = ltrim($tmp[$y]);
									$dis = $tmp[$y];
									$tmp[$y] = eregi_replace(" ", "_", $tmp[$y]);
									$HTML_OUTPUT .= "<INPUT TYPE=CHECKBOX NAME=\"$tmp[$y]\" VALUE=\"CHECKED\"><font size=2 face=\"Verdana, Arial, Helvetica, sans-serif\">$dis</font>&nbsp;&nbsp;\n";
								}
							}
										
						$HTML_OUTPUT .= "</TD></TR>\n";
					}
					
			} // End IF LABEL EXISTS


	} // End For Loop
	
	if ($required_str != "") {
	
		$t = strlen($required_str);
		$v = $t - 1;
		$required_str = substr($required_str, 0, $v);	
		$required_str = "<INPUT TYPE=HIDDEN NAME=\"required_fields\" VALUE=\"$required_str\">\n";
	} 
	
	$HTML_OUTPUT = eregi_replace("##REQ##", $required_str, $HTML_OUTPUT);
	
			$HTML_OUTPUT .= "\n\n<!-- #ADDFIELD# -->\n\n";
		
	$HTML_OUTPUT .= "\n<TR><TD ALIGN=CENTER VALIGN=TOP COLSPAN=2>\n";
	$HTML_OUTPUT .= "<BR><INPUT TYPE=SUBMIT CLASS=FormLt1 NAME=\"SUBMIT\" VALUE=\"Submit Form\">\n</TD></TR>\n";
			
	$HTML_OUTPUT .= "</TABLE>\n\n</FORM>\n\n";
	
	
	// ============================================================================
	// WRITE FINAL FORM HTML OUTPUT TO .FORM FILE IN THE MEDIA FOLDER OF DOC ROOT
	// ============================================================================
	
	if ($FORM_NAME == "UNTITLED") {
		$date = date("y-m-d");
		$FORM_NAME = "UNTITLED_$date";
	}
	
	$filename = "$doc_root/media/$FORM_NAME.form";
	$file = fopen("$filename", "w");
	fwrite($file, "$HTML_OUTPUT");
	fclose($file);
	
	// REDIRECT BACK TO FORMS LIBRARY NOW
	// ============================================================================

	header("Location: ../forms.php?dropArea=$dropArea&selkey=Forms&=SID");
	exit;

}

###########################################################################
### Make sure that required entry data has been met
###########################################################################

if (!isset($COUNTER)) { 

	$COUNTER = 1;

} else {

	$err = 0;
	
	$l_chk = "LABEL_". $COUNTER;
	$l_chk = ${$l_chk};
	
	if (strlen($l_chk) < 3) { $err++; }
	
	$t_chk = "FIELD_TYPE_" . $COUNTER;
	$t_chk = ${$t_chk};
	
	$v_chk = "FIELD_VALUES_" . $COUNTER;
	$v_chk = ${$v_chk};
	
	if ($t_chk != "TEXT" && $t_chk != "TEXTAREA") {
			if (strlen($v_chk) < 3) { $err++; }
	}
	
	if ($err == 0) { 
		$COUNTER = $COUNTER + 1;
		$l_chk = "";
		$t_chk = "";
		$v_chk = "";
	}
	
}

###########################################################################
### MAKE SURE THAT ALL STEPS OF FORM POST AND GET LINKS ARE ACCOUNTED
### FOR ON EACH PASS THROUGH THE SCRIPT
###########################################################################

$HIDDEN_POST = "";

if ($FORM_NAME == "") {

	$FORM_NAME = "UNTITLED";

	$HIDDEN_POST .= "<INPUT TYPE=HIDDEN NAME=\"COUNTER\" VALUE=\"$COUNTER\">\n\n";
	$HIDDEN_POST .= "<INPUT TYPE=HIDDEN NAME=\"FORM_NAME\" VALUE=\"$FORM_NAME\">\n\n";

}

	// Get All Posted Vars to this Script and Prepare for Next Pass
	
	reset($HTTP_POST_VARS);
	while (list($name, $value) = each($HTTP_POST_VARS)) {
		$value = stripslashes($value);
		$name = stripslashes($name);
		
		if ($name == "COUNTER") { 
			$value = $COUNTER; 
		} else {
			${$name} = $value;
		}
		
		$HIDDEN_POST .= "<INPUT TYPE=HIDDEN NAME=\"$name\" VALUE=\"$value\">\n";
	}
	
	// Get All "GET" Vars submitted to this Script and Prepare for Next Pass
	
	reset($HTTP_GET_VARS);
	while (list($name, $value) = each($HTTP_GET_VARS)) {
		$value = stripslashes($value);
		$name = stripslashes($name);
		${$name} = $value;
		$HIDDEN_POST .= "<INPUT TYPE=HIDDEN NAME=\"$name\" VALUE=\"$value\">\n";
	}
	


###########################################################################

?>

<HTML>
<HEAD>
<TITLE>Soholaunch Form Builder V1.0</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK HREF="../soholaunch.css" REL="stylesheet" TYPE="text/css">
</HEAD>


<script language="JavaScript" type="text/JavaScript">
<!--
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_showHideLayers() { //v6.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}
//-->
</script>

<SCRIPT Language=JScript>

function show_val() {
	valueSq.style.display = '';
}

function hide_val() {
	valueSq.style.display = 'none';
}

function cancel_build() {
	parent.window.location='../forms.php?dropArea=<? echo $dropArea; ?>&selkey=Forms&<?=SID?>';
}

function show_help() {
	MM_showHideLayers('helpLayer','','show')
	MM_showHideLayers('mainLayer','','hide')
}

function close_help() {
	MM_showHideLayers('helpLayer','','hide')
	MM_showHideLayers('mainLayer','','show')
}



</SCRIPT>
<BODY bgcolor="beige" text="black" leftmargin="0" topmargin="10" marginwidth="0" marginheight="10">

<div id="helpLayer" style="position:absolute; left:0px; top:10px; width:100%; height:100%; z-index:100; border: 1px none #000000; visibility: hidden; overflow: hidden"> 
<table width="550" border="0" align="center" cellpadding="3" cellspacing="0" style="border: 1px solid black">
  <tr bgcolor="#6699cc"> 
    <td align="center" bgcolor="#336699"><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Soholaunch 
      Form Builder Help</strong></font></td>
  </tr>
  <tr> 
    <td valign="top" bgcolor="#FFFFFF"><div align="center"> 
        <p align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Form 
          Name</strong> (i.e. &quot;My Form&quot;)<br>
          Once your form is complete, it will be selectable from the &#8220;Available 
          Forms&#8221; drop-down menu of the Forms Library, listed as the <em>form 
          name</em>.</font></p>
        <p align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <strong>Label</strong> (i.e. &quot;First Name&quot;)<br>
          The <em>label</em> tells the user what to place in, or select from a 
          form field.</font></p>
        <p align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Field 
          Name</strong> (i.e. &quot;First Name&quot;)<br>
          When form submissions are emailed to the webmaster, the <em>field name</em> 
          will be the text associated with the user's response. When form submissions 
          are placed in a databse table, the form's <em>field names</em> dictate 
          database's field names.</font></p>
        <p align="left"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Required 
          Field</font></strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><br>
          Forms cannot be submitted unless each of the <em>required fields</em> 
          have been filled out. Non-required fields, however, may be left blank.</font></p>
        <p align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Selectable 
          Options </strong>(i.e. &quot;Male,Female&quot;)</font><br>
          <font size="2" face="Verdana, Arial, Helvetica, sans-serif">Only applicable 
          to drop-downs, radio buttons, and checkboxes, <em>selectable options</em> 
          are the choices presented to the user for a particular <em>label</em>.</font></p>
		  <BR>
      </div></td>
  </tr>
</table><DIV ALIGN=CENTER><br><input type=button class="FormLt1" value="Close Help" onclick="close_help();"></div>

</DIV>

<!-- End Help Layer -->
<div id="mainLayer" style="position:absolute; left:0px; top:0px; width:100%; height:100%; z-index:100; border: 1px none #000000; visibility: visible; overflow: hidden"> 


<FORM METHOD=POST ACTION="form_build.php">
  <?php echo $HIDDEN_POST; ?> 
  <TABLE BORDER="0" ALIGN="CENTER" CELLPADDING="10" CELLSPACING="0" CLASS="text" STYLE="background: white; BORDER: 1PX SOLID BLACK">
    <TR ALIGN="RIGHT"> 
      <TD COLSPAN="2" BGCOLOR="#000066"><B><FONT COLOR="#FFFFFF">Form Name: <INPUT TYPE="text" NAME="FORM_NAME" VALUE="<?php echo $FORM_NAME; ?>" CLASS="text" STYLE='width: 150px;' MAXLENGTH=40>
        : Field <?php echo $COUNTER; ?></FONT></B>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="show_help();"><img src="../help.gif" border=0 hspace=2 vspace=2 align="absmiddle"></a></TD>
    </TR>
    <TR BGCOLOR="#EFEFEF"> 
      <TD COLSPAN="2"><FONT COLOR=RED>*</FONT><u>Label</u>:&nbsp; <INPUT TYPE="text" NAME="LABEL_<?php echo $COUNTER; ?>" CLASS="text" STYLE='width: 500px;' VALUE="<?php echo $l_chk; ?>"></TD>
    </TR>
    <TR> 
      <TD ROWSPAN="2" ALIGN="LEFT" VALIGN="TOP"><FONT COLOR=RED>*</FONT><u>Select 
        Field Type</u>:<BR> <BR> <INPUT TYPE="radio" NAME="FIELD_TYPE_<?php echo $COUNTER; ?>" VALUE="TEXT" onclick="hide_val();" CHECKED>
        Text Box<BR> <INPUT TYPE="radio" NAME="FIELD_TYPE_<?php echo $COUNTER; ?>" onclick="hide_val();" VALUE="TEXTAREA">
        Text Area (Multi-Lines)<BR> <INPUT TYPE="radio" NAME="FIELD_TYPE_<?php echo $COUNTER; ?>" onclick="show_val();" VALUE="DROPDOWN">
        Drop Down Box<BR> <INPUT TYPE="radio" NAME="FIELD_TYPE_<?php echo $COUNTER; ?>" onclick="show_val();" VALUE="RADIO">
        Radio Buttons<BR> <INPUT TYPE="radio" NAME="FIELD_TYPE_<?php echo $COUNTER; ?>" onclick="show_val();"VALUE="CHECKBOX">
        Checkboxes </TD>
      <TD ALIGN="LEFT" VALIGN="TOP"><u>Field Name</u>: 
        <INPUT TYPE="text" NAME="FIELD_NAME_<?php echo $COUNTER; ?>" CLASS="text" STYLE='width: 200px;' VALUE="FORMFIELD<?php echo $COUNTER; ?>"> 
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <INPUT TYPE="checkbox" NAME="REQ_<?php echo $COUNTER; ?>" VALUE="Y">
        Required Field?</TD>
    </TR>
    <TR> 
      <TD WIDTH="450" ALIGN="LEFT" VALIGN="TOP"><SPAN ID="valueSq" STYLE='display: none;'><FONT COLOR=RED>*</FONT>Enter 
        selectable options seperated by comma's: <BR>
        <FONT COLOR="#999999"> (Drop Down Boxes, Radio Buttons and Checkboxes 
        Only)</FONT><BR>
        <TEXTAREA NAME="FIELD_VALUES_<?php echo $COUNTER; ?>" WRAP="VIRTUAL" CLASS="text" STYLE='width: 375px; height: 50px;'></TEXTAREA>
        </SPAN></TD>
    </TR>
    <TR ALIGN="RIGHT" VALIGN="MIDDLE" BGCOLOR="#EFEFEF"> 
      <TD COLSPAN="2"><INPUT NAME="NXT" TYPE="SUBMIT" CLASS="FormLt1" VALUE="Next Field &gt;&gt;"> 
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
      </TD>
    </TR>
  </TABLE>
  <DIV ALIGN="CENTER"><BR>
    
  <?php
  
  if ($err != 0) { 
  		echo "<font color=red class=text>You did not enter all the required data for the field.</font><BR>\n";
  }
  
  if ($t_chk != "TEXT" && $t_chk != "TEXTAREA" && $err != 0) {
  		echo "<SCRIPT LANGUAGE=JScript>\n";
		echo "	valueSq.style.display = '';\n";
		echo "</SCRIPT>";
  }	
  
  ?>
  
    <BR>
    <INPUT TYPE="BUTTON" NAME="COMPLETE_FORM" CLASS="FormLt1" VALUE="Cancel Form Build" onclick="cancel_build();">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
    <INPUT TYPE="SUBMIT" NAME="COMPLETE_FORM" CLASS="FormLt1" VALUE="Form Complete">
  </DIV>
</FORM>
</div>
</BODY>
</HTML>
