<?php
error_reporting('341');
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.5
##
## Author:        Mike Johnston [mike.johnston@soholaunch.com]
## Homepage:      http://www.soholaunch.com
## Bug Reports:   http://bugzilla.soholaunch.com
## Release Notes: sohoadmin/build.dat.php
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

# Include core files
require_once("../../includes/product_gui.php");

## ====================================================
## DOES BLOG_COMMENTS TABLE EXSIST?
## ====================================================

//blog_comments
create_table('blog_comments');

#######################################################
### START HTML/JAVASCRIPT CODE             ###
#######################################################

# Start buffering output
ob_start();

# Show display settings
if(!$_SESSION['display_type'] && !$_REQUEST['display_type']){
   //echo "here1";
   $_SESSION['display_type'] = "show_new";
   $display_new = "checked";
   $display_denied = "";
   $display_approved = "";
}else{
   //echo "here2";
   //echo "(".$_SESSION['display_type'].")<br/>\n";
   if($_REQUEST['display_type']){
      $_SESSION['display_type'] = $_REQUEST['display_type'];
   }

   if(eregi("show_new", $_SESSION['display_type'])){ $display_new = "checked"; }else{ $display_new = ""; }
   if(eregi("show_denied", $_SESSION['display_type'])){ $display_denied = "checked"; }else{ $display_denied = ""; }
   if(eregi("show_approved", $_SESSION['display_type'])){ $display_approved = "checked"; }else{ $display_approved = ""; }
}
//echo "(".$_SESSION['display_type'].")<br/>\n";

//   echo "(".$display_new.")<br/>\n";
//   echo "(".$display_denied.")<br/>\n";
//   echo "(".$display_approved.")<br/>\n";

# Pull default email for site
$result = mysql_query("SELECT df_email FROM site_specs LIMIT 1");
$SITE_SPECS = mysql_fetch_array($result);
$admin_email = $SITE_SPECS['df_email'];

# Pull blog comment settings
$blog_comment_settings = new userdata("blog_comment");
//   $is_allowed = "";
//   $display_category = "none";
//if(!$blog_comment_settings->get("allow_comments") || $blog_comment_settings->get("allow_comments") == "no"){
//   $is_allowed = "";
//   $display_category = "none";
//}else{
//   $is_allowed = "checked";
//   $display_category = "block";
//}

if(!$blog_comment_settings->get("emailto")){
   $blog_comment_settings->set("emailto", $admin_email);
}else{
   $admin_email = $blog_comment_settings->get("emailto");
}

if(!$blog_comment_settings->get("captcha") || $blog_comment_settings->get("captcha") == "no"){
   $is_captcha = "";
}else{
   $is_captcha = "checked";
}

if( !$blog_comment_settings->get("require_approval") ){
   $blog_comment_settings->set("require_approval", "yes");
   $is_required = "checked";
}elseif( $blog_comment_settings->get("require_approval") == "yes" ){
   $is_required = "checked";
}elseif( $blog_comment_settings->get("require_approval") == "no" ){
   $is_required = "";
}

?>

<style>

.comment_container {
   /*border: 1px dashed green;*/
}

.a_comment {
   /*width: 100%;*/
   margin: 10px;
   border: 1px solid #000000;
   background-color: #cccccc;
   font-family: Trebuchet MS, arial, helvetica, sans-serif;
   font-size: 11px;
}

.a_comment div.post_name {
   margin-left: 10px;
   font-weight: bold;
   font-size: 13px;
   /*color: blue;*/
}

.a_comment span {
   margin-left: 10px;
   /* font-style: italic; */
}

.a_comment p {
   margin-left: 10px;
   margin-right: 10px;
   background-color: #efefef;
   padding: 5px;
   border: 1px dashed #999999;
}

.result_select {
   float: right;
   width: 125px;
   /*background-color: #dfdfdf;*/
   border: 0px solid #000000;
   border-style: none none solid solid;
   /*display: inline;*/
}

.result_select div {
   text-align: left;
   padding-left: 10px;
   font-size: 12px;
   /*display: inline;*/
}

.delete_spacer {
   height: 20px;
   /*float: right;*/
   /*width: 75px;*/
   /*text-align: right;*/
   /*background-color: #dfdfdf;*/
   /*border: 1px solid #000000;
   border-style: none none solid solid;*/
   /*display: inline;*/
}

.delete_select {
   float: right;
   width: 125px;
   /*
   height: 20px;
   text-align: left;
   padding-left: 10px;
   font-size: 12px;
   */
   /*text-align: right;*/
   /*background-color: #dfdfdf;*/
   border: 0px solid #000000;
   border-style: solid none none solid;
   /*display: inline;*/
}



.comment_result {
   /*float: right;*/
   width: 150px;
   margin-left: 150px;
   display: inline;
   font-weight: bold;
   color: green;
}

.display_options {
   text-align: center;
   padding-top: 10px;
   /*border: 1px dashed #000000;*/
   width: 720px;
}

.tab-off, .tab-on {
   position: absolute;
   top: -25px;
   z-index: 2;
   text-align: center;
   width: 125px;
   /*height: 25px;*/
   vertical-align: top;
   font-weight: bold;
   padding-top: 5px;
   padding-bottom: 5px;
   margin-right: 15px;
   background-color: #efefef;
   border: 1px solid #ccc;
   border-top: 3px solid #ccc;
   color: #595959;
   cursor: pointer;
}

.tab-on {
   color: #000;
   background-color: #efefef;
   border-top: 3px solid #175aaa;
   font-weight: bold;
}

/* Table containing content for each tab */
table.tab_content {
   _margin: -20px 5px 20px 5px;
   border: 1px solid #ccc;
   margin-left: 5px;
   width: 98%;
   /*position: relative;*/
}

#layout_tab1 { left: 5px; }
#layout_tab2 { left: 140px; }
#layout_tab3 { left: 275px; }
#layout_tab4 { left: 610px; }
#layout_tab5 { left: 410px; }

/*  ___
   | __|__ _ _ _ __  ___
   | _/ _ \ '_| '  \(_-<
   |_|\___/_| |_|_|_/__/ */

/* Hack to fix border on floated elements in IE */
.ie_cleardiv {
   display: block;
   clear: both;
   float: none;
   margin: 0;
   /*border: 1px dotted red;*/
}

.field-container {
   display: block;
   clear: both;
   margin-bottom: 6px;
   vertical-align: top;
   /*border: 1px solid red;*/
}
.asterisk {
   color: red;
}

.instructions {
   margin-top: 0;
   color: #2e2e2e;
   font-family: Arial, helvetica, sans-serif;
   font-size: 13px;
   line-height: 1.1em !important;
}

.myform-field_title-top,
.myform-field_title-left {
   font-size: 12px;
   font-weight: bold;
   font-family: Arial, helvetica, sans-serif;
   margin-bottom: 0;
   color: #000000;
   border-width: 1px;
   border-color: #ccc;
   border-style: hidden;
   width: 150px;

}
.myform-field_title-left {
   display: block;
   float: left;
   margin-right: 15px;
   /*margin-top: 12px;*/
   margin-top: 2px;
   text-align: left;
   /*border: 1px solid red;*/
}

.myform-field_title-hidden {
   display: none;
}

.myform-input_container, .myform-formfield_container {
   display: block;
   float: left;
   margin-top: 0;
   font-size: 11px;
}

.form_body_container {
   text-align: left;
   background-color: transparent;
   margin: 0;
   padding: 5;
   width: ;
   border-style: solid;
   border-width: 0px;
   border-color: F0F8FF;
   font-family: Arial, helvetica, sans-serif;
}

.userform-submit_btn-container {
   text-align: left;
}

.submit_btn {
   font-size: 13px;
   font-weight: bold;
}

.instructions {
   margin-top: 0;
   color: #2e2e2e;
   font-family: Trebuchet MS, arial, helvetica, sans-serif;
   font-size: 12px;
   line-height: 1.1em !important;
}

#settings_result {
   color: green;
   font-size: 15px;
   font-weight: bold;
}


</style>


<script language="javascript">



//---------------------------------------------------------------------------------------------------------
//      _      _   _   __  __
//     /_\  _ | | /_\  \ \/ /
//    / _ \| || |/ _ \  >  <
//   /_/ \_\\__//_/ \_\/_/\_\
//
//---------------------------------------------------------------------------------------------------------
// The following script (as commonly seen in other AJAX javascripts) is used to detect which browser the client is using.
// If the browser is Internet Explorer we make the object with ActiveX.
// (note that ActiveX must be enabled for it to work in IE)
function makeObject() {
   var x;
   var browser = navigator.appName;

   if ( browser == "Microsoft Internet Explorer" ) {
      x = new ActiveXObject("Microsoft.XMLHTTP");
   } else {
      x = new XMLHttpRequest();
   }

   return x;
}

// The javascript variable 'request' now holds our request object.
// Without this, there's no need to continue reading because it won't work ;)
var request = makeObject();

function ajaxDo(qryString, boxid) {
   //alert(qryString+', '+boxid);

   rezBox = boxid; // Make global so parseInfo can get it

   // The function open() is used to open a connection. Parameters are 'method' and 'url'. For this tutorial we use GET.
   request.open('get', qryString);

   // This tells the script to call parseInfo() when the ready state is changed
   request.onreadystatechange = parseInfo;

   // This sends whatever we need to send. Unless you're using POST as method, the parameter is to remain empty.
   request.send('');

}

function parseInfo() {
   // Loading
   if ( request.readyState == 1 ) {
      document.getElementById(rezBox).innerHTML = 'Loading...';
   }

   // Finished
   if ( request.readyState == 4 ) {
      var answer = request.responseText;
      document.getElementById(rezBox).innerHTML = answer;
   }
}

// End AJAX

function getTypes(){
   var display_string = '';
   if(document.getElementById('show_new').checked)
      display_string += 'show_new;';
   if(document.getElementById('show_denied').checked)
      display_string += 'show_denied;';
   if(document.getElementById('show_approved').checked)
      display_string += 'show_approved;';
   document.getElementById('display_type').value = display_string
   document.display_form.submit();
}

function check_n_send() {
   var email_to = document.getElementById('email_to').value;

   if(document.getElementById('captcha').checked){ var captcha = "yes"; }else{ var captcha = "no"; }
   //if($('enable').checked){ var enable = "yes"; }else{ var enable = "no"; }
   if(document.getElementById('require_approval').checked){ var require_approval = "yes"; }else{ var require_approval = "no"; }

//   var allowed_categorys = "";
//
//   var num_ele = document.comment_settings_form.elements.length
//   //alert(num_ele);
//   for(var x = 0; x < num_ele; x++){
//      if(!document.comment_settings_form.elements[x].name.search("category") && document.comment_settings_form.elements[x].checked){
//         var names = document.comment_settings_form.elements[x].name.split("___")
//         //alert(names[0]+'---'+names[1])
//
//         allowed_categorys = allowed_categorys+names[1]+";";
//         //alert(allowed_categorys)
//      }
//   }
//   //alert(allowed_categorys)

   ajaxDo('comment_result.php?process=comment_settings&email_to='+email_to+'&captcha='+captcha+'&require_approval='+require_approval, 'settings_result');
}

function check_display(ele) {
   if(ele.checked)
      $('allow_individual').style.display='block'
   else
      $('allow_individual').style.display='none'
}


</script>


   <!---Container div-->
   <div id="tab_interface_container" style="display: block; width: 100%;margin: 40px 5px 20px 5px;position: relative; border: 0px solid red;">

      <!---================== Tabs - START ==================-->
      <div id="layout_tab1" class="tab-on" onclick="showid('tab1-content');hideid('tab2-content');setClass('layout_tab1', 'tab-on');setClass('layout_tab2', 'tab-off');">
      Blog Comments
      </div>

      <div id="layout_tab2" class="tab-off" onclick="showid('tab2-content');hideid('tab1-content');setClass('layout_tab2', 'tab-on');setClass('layout_tab1', 'tab-off');">
      Settings
      </div>

   </div>

      <table id="tab1-content" border="0" cellspacing="0" cellpadding="0" class="feature_sub tab_content" style="display: table;">
         <tr>
		<td style="width:710px;padding-left: 15px;">
               <div class="display_options">

                  <form name="display_form" action="blog_comments.php">
                     <input type="hidden" name="process" value="display_settings" />
                     <input type="hidden" id="display_type" name="display_type" value="" />
                     <b>Display Options:</b>

                     <input type="checkbox" id="show_new" name="show_new" <? echo $display_new; ?> /> <label for="show_new">Show new</label>
                     <input type="checkbox" id="show_denied" name="show_denied" <? echo $display_denied; ?> /> <label for="show_denied">Show denied</label>
                     <input type="checkbox" id="show_approved" name="show_approved" <? echo $display_approved; ?> /> <label for="show_approved">Show approved</label>
                     <button type="button" onclick="getTypes()" class="blueButton" /><span><span>Show Selected</span></span></button>
                  </form>
               </div>

<?php

   $display_what = "";
   if($display_new == "checked"){ $display_what .= "status = 'new' "; }

   if($display_denied == "checked"){
      if($display_what != ""){
         $display_what .= "OR ";
      }
      $display_what .= "status = 'denied' ";
   }
   if($display_approved == "checked"){
      if($display_new == "checked" || $display_denied == "checked"){
         $display_what .= "OR ";
      }
      $display_what .= "status = 'approved' ";
   }
   //echo "(".$display_what.")<br/>\n";

   $at_least_one = 0;

   $blogQry = "SELECT * FROM blog_category";
   $result = mysql_query($blogQry);

   while ($CATEGORY = mysql_fetch_array($result)) {
      $found_comment = 0;


      $current_category = $CATEGORY['prikey'];
      $blogQry2 = "SELECT prikey, blog_category, blog_title FROM blog_content WHERE blog_category = '".$current_category."'";
      $result2 = mysql_query($blogQry2);

      while ($CONTENT = mysql_fetch_array($result2)) {

         # Blog comments

         $comment_qry = "SELECT * FROM blog_comments WHERE blog_key = '".$CONTENT['prikey']."' AND (".$display_what.") ORDER BY comment_date DESC";
         //echo "(".$comment_qry.")<br/>\n";

         $comment_result = mysql_query($comment_qry);

         //echo "Num comments (".mysql_num_rows($comment_result).")<br/>\n";

         if(mysql_num_rows($comment_result) > 0){

            if($found_comment == 0){
               $at_least_one = 1;
               echo "<h1 style=\"margin: 5px;\"><b>Blog posts with new comments in <span class=\"blue\">".htmlspecialchars($CATEGORY['category_name'])."</span>.</b> <span class=\"text green hand\" onclick=\"toggleid('comment_container_".$CATEGORY['prikey']."');\">show/hide</span></h1>\n";
               echo "<div id=\"comment_container_".$CATEGORY['prikey']."\" style=\"display: block;\">\n";
            }

            $found_comment = 1;

            echo "<div class=\"comment_container\" style=\"margin: 10px;\">\n";
            echo "<h2><span class=\"green\">".htmlspecialchars($CONTENT['blog_title'])."</span> has ".mysql_num_rows($comment_result)." new comments.</h2>\n";

            while($COMMENTS = mysql_fetch_array($comment_result)){
               echo "<div class=\"a_comment\" style=\"\">\n";

               echo "   <div class=\"result_select\">\n";
               
               echo "      <a class=\"greenButton\" style=\"margin:4px 4px 0px 0px;float:right;cursor: pointer;\" onclick=\"ajaxDo('comment_result.php?process=comment&comment=".$COMMENTS['prikey']."&do=approved', 'comment_result_".$COMMENTS['prikey']."');\"><span>Approve</span></a>\n";
               
               echo "   </div>\n";

               echo "   <div class=\"post_name blue\">Posted by ".htmlspecialchars(stripslashes(html_entity_decode($COMMENTS['name'], ENT_QUOTES)))."\n";

//               echo "   <div class=\"comment_result\" id=\"comment_result_".$COMMENTS['prikey']."\">\n";
//               if($COMMENTS['status'] != "new"){ echo "Currently ".$COMMENTS['status']; }else{ echo "&nbsp;"; }
//               echo "   </div>\n";

               echo "   </div>\n";
               echo "   <span>".date("l M. j g:ia",strtotime($COMMENTS['comment_date']))."</span>\n";

               echo "   <div class=\"comment_result\" id=\"comment_result_".$COMMENTS['prikey']."\">\n";
               if($COMMENTS['status'] != "new"){ echo "Currently ".$COMMENTS['status']; }else{ echo "&nbsp;"; }
               echo "   </div>\n";

               echo "   <p>".htmlspecialchars(nl2br(stripslashes(html_entity_decode($COMMENTS['comments'], ENT_QUOTES))))."</p>\n";

			echo "      <a class=\"grayButton\" style=\"margin:-4px 4px 4px 0px;float:right;cursor: pointer;\" onclick=\"ajaxDo('comment_result.php?process=comment&comment=".$COMMENTS['prikey']."&do=denied', 'comment_result_".$COMMENTS['prikey']."');\"><span>Deny</span></a>\n";
               echo "   <a class=\"redButton\" style=\"margin:-4px 4px 4px 0px;float:right;cursor: pointer;\" onclick=\"ajaxDo('comment_result.php?process=delete&comment=".$COMMENTS['prikey']."', 'comment_result_".$COMMENTS['prikey']."');\"><span>Delete</span></a>\n";
               echo "   <div class=\"delete_spacer\">&nbsp;</div>\n";

               echo "</div>\n";



            }

            echo "</div>\n";
         }else{
            //echo "<p class=\"no_comments\">No Comments</p>\n";
         }
      }

      if($found_comment == 1){
         echo "</div>\n";
      }
   }

   if($at_least_one == 0){
      echo "<h1 style=\"margin: 5px;\"><b>There are no new blog comments.</b></h1>\n";
   }

?>
            <td>
         <tr>
      </table><!---End Tab1--->

      <table id="tab2-content" border="0" cellspacing="0" cellpadding="0" class="feature_sub tab_content" style="display: none;">
         <tr>
            <td style="padding-left: 15px;width:710px;">

               <form name="comment_settings_form" id="comment_settings_form" method="post" action="blog_comments.php">

               <div id="form_body_container" style="text-align: left;background-color: transparent;margin: 10;padding: 0px;width: ;border-style: none;border-width: 0;border-color: 000;">

                  <!--- Title -->
                  <div class="field-container">
                  <h1 style="margin-top: 10px; margin-bottom: 5px;">Blog Comment Settings</h1>
                  <p class="instructions" style="color: #595959; margin-bottom: 15px;">These settings define different parts of the blog comment system.</p>
                  <div class="ie_cleardiv">
                  </div>
                  </div>

			<?php
//				echo "
//                  <!--- Allow blog comments? -->
//                  <div class=\"field-container\">
//                  <p class=\"myform-field_title-left\"><label for=\"enable\">Allow blog comments</label> :
//                  </p>
//                  <p class=\"myform-input_container\"><input onclick=\"check_display(this);\" type=\"checkbox\" name=\"enable\" id=\"enable\" style=\"margin-right: 10px;\"".$is_allowed."/></p>
//                  <p class=\"instructions\" style=\"color: #595959; padding-top: 3px;\">Should users be able to post comments about your blog posts?</p>
//                  <div class=\"ie_cleardiv\">
//                  </div>
//                  </div>
//
//                  <!--- Allow blog comments? -->
//                  <div class=\"field-container\" id=\"allow_individual\" style=\"background-color: #FFFFFF; border: 1px dashed #CCCCCC; padding: 5px; display: ".$display_category.";\">
//                  <p class=\"myform-field_title-left\">Allow comments for individual categorys :
//                  </p>
//                  <p class=\"myform-input_container\">
//";
//
//                  if(!$blog_comment_settings->get("allowed_categorys")){
//                     $allowed_categorys = "all";
//                  }else{
//                     $allowed_categorys = $blog_comment_settings->get("allowed_categorys");
//                  }
//
//                  $blogQry = "SELECT * FROM blog_category";
//                  $result = mysql_query($blogQry);
//
//                  while ($CATEGORY = mysql_fetch_array($result)) {
//                     $is_checked = "";
//                     if($allowed_categorys == "all" || eregi($CATEGORY['category_name'], $allowed_categorys)){
//                        $is_checked = "checked";
//                     }
//                     echo "<input type=\"checkbox\" name=\"category___".$CATEGORY['category_name']."\" id=\"category___".$CATEGORY['category_name']."\" style=\"margin-right: 10px;\" ".$is_checked." /> <label for=\"category___".$CATEGORY['category_name']."\">".$CATEGORY['category_name']."</label><br/>\n";
//                  }
//
//
//
//			   echo "                  </p>
//                  <p class=\"instructions\" style=\"color: #595959; padding-top: 3px;\">If you wish to disable comments for a specific blog you can do that here.</p>
//                  <div class=\"ie_cleardiv\">
//                  </div>
//                  </div>";
                  ?>

                  <!--- Send Confirmation Email? -->
                  <div class="field-container">
                     <p class="myform-field_title-left"><label for="require_approval">Require webmaster approval:</label></p>
                     <p class="myform-input_container"><input type="checkbox" name="require_approval" id="require_approval" style="margin-right: 10px;" <? echo $is_required; ?> /></p>
                     <p class="instructions" style="color: #595959; padding-top: 3px;">If you disable this, all comments will be approved and displayed when submitted.</p>
                     <div class="ie_cleardiv"></div>
                  </div>

                  <!--- Email to? -->
                  <div class="field-container">
                  <p class="myform-field_title-left"><label for="email_to">Email comments to:</label>
                  </p>
                  <p class="myform-input_container"><input type="text" name="email_to" id="email_to" style="width: 220px; margin-right: 5px;" value="<? echo $admin_email; ?>"/></p>
                  <p class="instructions" style="color: #595959; padding-top: 5px;"> Who should get notified when a comment is posted?</p>
                  <div class="ie_cleardiv">
                  </div>
                  </div>

                  <!--- Captcha? -->
                  <div class="field-container">
                     <p class="myform-field_title-left"><label for="captcha">Display captcha:</label></p>
                     <p class="myform-input_container"><input type="checkbox" name="captcha" id="captcha" style="margin-right: 10px;" <? echo $is_captcha; ?> /></p>
                     <p class="instructions" style="color: #595959; padding-top: 3px;">This adds a form verification field to the end of comment forms.  Helps combat spammers.</p>
                     <div class="ie_cleardiv"></div>
                  </div>

                  <div class="userform-submit_btn-container" style="margin-bottom: 35px;">
                  <p class="myform-field_title-left">
                    <button class="greenButton" type="button" onclick="check_n_send();"><span><span>Save &gt;&gt;</span></span></button>
                  </p>
                     <p class="myform-input_container"><div id="settings_result">&nbsp;</div></p>
                  </div>

               </div>
               </form>

            <td>
         <tr>
      </table><!---End Tab2--->

<?php

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Manage all comments posted about your blogs.");

# Build into standard module template
$module = new smt_module($module_html);
$module->meta_title = "Blog Comments";
$module->add_breadcrumb_link("Blog Manager", "program/modules/blog/blog-entry.php");
$module->add_breadcrumb_link("Blog Comments", "program/modules/blog/blog_comments.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/full_size/blog_manager-enabled.gif";
$module->heading_text = "Blog Comments";
$module->description_text = $instructions;
$module->good_to_go();
?>