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
# Forms Manager Module v2.0 - Add field popup layer content
#
# NOTES:
# Included by edit_form.php like so...
# <div id="addfield_buttons-container">
#  include("addfield_popup.inc.php");
# </div>
#===============================================================================================
?>
 <h1>Add form element</h1>
 <p>Click on the element you want to add.</p>

 <h2>Standard form field elements</h2>
 <div id="addbtns-container">
  <!--- <div class="addbtn" onclick="addField('paragraph');">paragraph</div> -->

  <!---text-->
  <div class="addbtn" id="addbtn-text" onclick="addField('text');">
   <input type="text" style="width: 50px;font-size: 8px;" value="The ubiquitous form field"/><br/>
   <span>Single-line Text</span>
  </div>

  <div class="addbtn" id="addbtn-textarea" onclick="addField('textarea');">
   <textarea style="width: 75px;font-size: 5px;height: 35px;">This is usually used for collecting detailed comments from visitors.</textarea>
   <span>Multi-line Text</span>
  </div>

  <div class="addbtn" id="addbtn-select" onclick="addField('select');">
   <select style="width: 80px;font-size: 8px;">
    <option value="">First Option</option>
    <option value="">Second Option</option>
   </select>
   <span>Drop-down</span>
  </div>

  <div class="addbtn" id="addbtn-checkbox" onclick="addField('checkbox');">
   <input type="checkbox"/><br/>
   <span>Checkboxes</span>
  </div>

  <!---radios-->
  <div class="addbtn" id="addbtn-radio" onclick="addField('radio');">
   <input type="radio"/><br/>
   <span>Radios</span>
  </div>

  <div class="ie_cleardiv"></div>
 </div>

<!---Special Elements-->
 <h2>Special Elements</h2>
 <div id="addbtns-container">

  <!---Section Heading-->
  <div class="addbtn" id="addbtn-heading" onclick="addField('heading');"><span>Section Heading</span></div>

  <!---Email Address-->
  <div class="addbtn" id="addbtn-email" onclick="addField('email');"><span>Email Address<br/> (for auto-reply)</span></div>

  <!---File-->
  <div class="addbtn" id="addbtn-upload" onclick="addField('upload');"><span>File Attachment</span></div>

  <div class="ie_cleardiv"></div>
 </div>
 <div id="addfield_buttons-container-closebar" onclick="hideid('addfield_buttons-container');" onmouseover="setClass(this.id, 'hand bg_red_d7 white right');" onmouseout="setClass(this.id, 'hand bg_red_98 white right');" class="hand bg_red_98 white right" style="padding: 3px;margin-top: 10px;margin: 10px -10px -10px;">[x] close</div>