<?
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.9
##
## Author: 			Mike Morrison
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugz.soholaunch.com
## Release Notes:	http://wiki.soholaunch.com
##
## COPYRIGHT NOTICE
## Copyright 1999-2007 Soholaunch.com, Inc.  All Rights Reserved.
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
#===============================================================================================
# INFO:
# Forms Manager Module v2.0 - Form properties layer content (i.e. display settings, layout, etc)
#
# NOTES:
# Included by edit_form.php like so...
# <div id="form_properties">
#  include("form_properties.inc.php");
# </div>
#===============================================================================================



# COLORS - Pre-build options for color dropdowns (i.e. font color)
$filename = "../mods_full/shopping_cart/shared/color_table.dat";
$colordat = file_get_contents($filename);
$colorLines = split("\n", $colordat);
$max = count($colorLines);
$colors = array();
$keynum = 0;
$color_options = "";
for ($x = 0; $x <= $max; $x++ ) {
   $temp = split(",", $colorLines[$x]); // Sample line: "Red,#ff0000"
   if ($temp[0] != "") {
      $colors[$keynum]['name'] = $temp[0];
      $colors[$keynum]['hex'] = $temp[1];

      # Build dropdown options
      $color_options .= "<option value=\"".$colors[$keynum]['hex']."\" alt=\"".$colors[$keynum]['name']."\" style=\"background-color: #".$colors[$keynum]['hex']."\">".$colors[$keynum]['name']."</option>\n";
   }
}



# START html output
$disHTML = "";
ob_start();
?>
<div class="tab_content-heading">
 <h3>Form Styles<span class="help_link" onclick="toggleid('help-form_styles');">[?]</span></h3>
 <p id="help-form_styles">Customize the look and feel of your form. These settings apply to your form on the whole rather than to a certain field.</p>
</div>

<!---Form Body Container-->
<fieldset>
 <legend>Form Body Container</legend>
 <span class="row">
  <label>Width?</label>
  <input type="text" class="props-width" id="form_body_width" value="<? echo formStyle('form_body_width'); ?>" onkeyup="formStyle_sizeField(event, 'form_body_width');">
  (e.g., "400px")
 </span>
 <span class="row">
  <label>Border?</label>
 </span>
 <span class="row">
  <select id="form_border_width" onchange="formStyle('form_border_width', this.value);">
<?
# Build width options
echo "<option value=\"0px\">0</option>\n";
for ( $w = 1; $w <= 10; $w++ ) {
   echo "<option value=\"".$w."px\">".$w."</option>\n";
}
?>
  </select>
  <select id="form_border_style" onchange="formStyle('form_border_style', this.value);">
   <option value="none" selected>none</option>
   <option value="solid">solid</option>
   <option value="dashed">dashed</option>
   <option value="dotted">dotted</option>
  </select>
  <select id="form_border_color" onchange="formStyle('form_border_color', this.value);" style="width: 75px;">
   <option value="2e2e2e" style="background-color: #fffab2;font-weight: bold;">&mdash;Most Common&mdash;</option>
   <option value="595959" style="color: #595959;font-weight: bold;" selected>Dark Gray</option>
   <option value="000" style="color: #000;font-weight: bold;">Black</option>
   <option value="fff" style="background-color: #000;color: #efefef;font-weight: bold;">White</option>
   <option value="fff" style="background-color: #fffab2;font-weight: bold;">&mdash;Other Colors&mdash;</option>
<?
echo $color_options;
?>
  </select>
 </span>
 <!---Padding-->
 <span class="row">
  <label><span class="help_link" onclick="toggleid('help-padding');">[?]</span>Padding?</label>

  <input type="text" class="props-width" id="form_body-padding" value="<? echo formStyle('form_body-padding'); ?>" onkeyup="formStyle_sizeField(event, 'form_body-padding');">

 <!---Margin-->
  <label>Margin?</label>
  <input type="text" class="props-width" id="form_body-margin" value="<? echo formStyle('form_body-margin'); ?>" onkeyup="formStyle_sizeField(event, 'form_body-margin');">
 </span>

 <!--- help-padding -->
 <p id="help-padding" class="help_text_layer"><b>Padding - </b> If "margin" is the space between the outer edge or your form and the rest of your page content,
 then padding is like the "inside margin"...the space between the outside border of your form and the form labels and field within the form body container.
 For example, if your field labels are too close to the edge (border) or your form, you can increase the padding value here to push them over.

 <br/><br/><strong>Note:</strong> You can specify padding as either a specific number of pixels or a percentage size.
 Just make sure to use a unit value after the number (e.g., "10px" or "10%" instead of just "10").</p>

 <!---Background-->
 <span class="row">
  <label>Background color?</label>
  <select id="form_body-background-color" onchange="formStyle('form_body-background-color', this.value);" style="width: 75px;">
   <option value="" style="color: #2e2e2e;font-style: italic;" selected>None (transparent)</option>
   <option value="fff" style="background-color: #000;color: #fff;font-weight: bold;">White</option>
   <option value="595959" style="color: #595959;font-weight: bold;">Dark Gray</option>
   <option value="000" style="color: #000;font-weight: bold;">Black</option>
   <option value="fff" style="background-color: #fffab2;font-weight: bold;">&mdash;Other Colors&mdash;</option>
<?
echo $color_options;
?>
  </select>
</fieldset>


<!---Field Labels-->
<fieldset>
 <legend>Field Labels</legend>

 <span class="row">
  <label><span class="help_link" onclick="toggleid('help-label_width');">[?]</span>Fixed width?</label>
<?
# Give field label border when focusing on width field
$onfocus = "getRule('myform-field_title-left').style.borderStyle = 'dashed';";
$onblur = "getRule('myform-field_title-left').style.borderStyle = 'hidden';";
?>
  <input type="text" class="props-width" id="label_width" value="<? echo formStyle('label_width'); ?>" onclick="<? echo $onfocus; ?>" onblur="<? echo $onblur; ?>" onkeyup="formStyle_sizeField(event, 'label_width');">
 </span>

 <p id="help-label_width" class="help_text_layer"><b>Fixed width -</b> Use to unify position of fields and their labels so that all labels are the same width. Specify width in pixels or leave blank for liquid width. Tip: You can use up and down arrows to change value in this field, and you can use shift+up/down to change by 25 at a time.</p>

 <label>Font Family?</label>
 <select id="label_font" onchange="getRule('myform-field_title-top').style.fontFamily=this.value;getRule('myform-field_title-left').style.fontFamily=this.value;formStyle('label_font', this.value);">
  <option value="Trebuchet MS, arial, helvetica, sans-serif" style="font-family: Trebuchet MS;" selected>Trebuchet MS (default)</option>
  <option value="Arial, helvetica, sans-serif" style="font-family: Arial;">Arial</option>
  <option value="verdana, arial, helvetica, sans-serif" style="font-family: Verdana;">Verdana</option>
  <option value="Times New Roman" style="font-family: Times New Roman;">Times New Roman</option>
 </select>

 <span class="row">
  <label>Font Size?</label>
  <input class="props-width" type="text" id="label_fontSize" value="<? echo formStyle('label_fontSize'); ?>" onkeyup="formStyle_sizeField(event, 'label_fontSize');getRule('myform-field_title-top').style.fontSize=this.value;getRule('myform-field_title-left').style.fontSize=this.value;formStyle('label_fontSize', this.value);">
 </span>

 <label>Font Color?</label>
 <select id="label_color" onchange="getRule('myform-field_title-top').style.color='#'+this.value;getRule('myform-field_title-left').style.color='#'+this.value;formStyle('label_color', this.value);">
  <option value="2e2e2e" style="background-color: #fffab2;font-weight: bold;">&mdash;Most Common&mdash;</option>
  <option value="595959" style="color: #595959;font-weight: bold;" selected>Dark Gray</option>
  <option value="000" style="color: #000;font-weight: bold;">Black</option>
  <option value="fff" style="background-color: #000;color: #efefef;font-weight: bold;">White (if site has dark bg)</option>
  <option value="fff" style="background-color: #fffab2;font-weight: bold;">&mdash;Other Colors&mdash;</option>
<?
echo $color_options;
?>
 </select>

 <span class="row">
  <label>Bold?</label>
  <select id="label_weight" onchange="getRule('myform-field_title-top').style.fontWeight=this.value;getRule('myform-field_title-left').style.fontWeight=this.value;formStyle('label_weight', this.value);">
   <option value="normal" selected>No</option>
   <option value="bold" style="font-weight: bold;">Yes</option>
  </select>
 </span>

 <span class="row">
  <label>Label Position?</label>
  <select id="label_position" onchange="formStyle('label_position', this.value);">
   <option value="left" selected>Next to field</option>
   <option value="top">Above field</option>
  </select>
 </span>

 <span class="row">
  <label>Label Alignment?</label>
  <select id="label_textalign" onchange="formStyle('label_textalign', this.value);">
   <option value="left" selected>left (default)</option>
   <option value="right">right</option>
   <option value="center">center</option>
  </select>
 </span>
</fieldset>


<!---Submit Button-->
<fieldset>
 <legend>Submit Button</legend>

 <span id="propset-submit_btn" style="background-color: transparent;">
  <span class="row">
   <label>Button Text?</label>
   <input type="text" id="submit_btn_text" value="<? echo stripslashes(formStyle('submit_btn_text')); ?>" onkeyup="formStyle('submit_btn_text', encodeURI(this.value));">
  </span>

  <span class="row">
   <label>Font Size?</label>
   <input class="props-width" type="text" id="submit_btn-font-size" value="<? echo formStyle('submit_btn-font-size'); ?>" onkeyup="formStyle_sizeField(event, 'submit_btn-font-size');">

  <span class="row">
   <label>Bold?</label>
   <select id="submit_btn_weight" onchange="formStyle('submit_btn_weight', this.value);">
    <option value="normal" selected>No</option>
    <option value="bold" style="font-weight: bold;">Yes</option>
   </select>
  </span>

  <span class="row">
   <label>Alignment?</label>
   <select id="submit_btn_align" onchange="formStyle('submit_btn_align', this.value);">
    <option value="right" selected>Right</option>
    <option value="left">Left</option>
    <option value="center">Center</option>
   </select>
  </span>

 </span>

</fieldset>

<script type="text/javascript">
// Re-select all saved values
// form_border
$('form_border_width').value = '<? echo formStyle("form_border_width"); ?>';
$('form_border_style').value = '<? echo formStyle("form_border_style"); ?>';
$('form_border_color').value = '<? echo formStyle("form_border_color"); ?>';

// submit_btn
$('submit_btn_weight').value = '<? echo formStyle("submit_btn_weight"); ?>';
$('submit_btn_align').value = '<? echo formStyle("submit_btn_align"); ?>';

// label
$('label_position').value = '<? echo formStyle("label_position"); ?>';
$('label_color').value = '<? echo formStyle("label_color"); ?>';
$('label_weight').value = '<? echo formStyle("label_weight"); ?>';
$('label_font').value = '<? echo formStyle("label_font"); ?>';
$('label_textalign').value = '<? echo formStyle("label_textalign"); ?>';
getRule('myform-field_title-top').style.color='#<? echo formStyle("label_color"); ?>';
getRule('myform-field_title-left').style.color='#<? echo formStyle("label_color"); ?>';
getRule('myform-field_title-top').style.fontWeight='<? echo formStyle("label_weight"); ?>';
getRule('myform-field_title-left').style.fontWeight='<? echo formStyle("label_weight"); ?>';
getRule('myform-field_title-top').style.fontFamily='<? echo formStyle("label_font"); ?>';
getRule('myform-field_title-left').style.fontFamily='<? echo formStyle("label_font"); ?>';

// Don't put anything below getRule or it will bomb in IE until getRule() is updated to work in both
</script>
<?
$disHTML = ob_get_contents();
ob_end_clean();
echo $disHTML;
?>