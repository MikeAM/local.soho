<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


session_start();
error_reporting(E_ALL);

######################################################################################################
/// Form Template Object - Builds Form as user adds fields and then outputs to db and .form file
###===================================================================================================
// form_template('form id') - Called when user clicks to add new form or edit a form's properties
# - Populate prop[] from form_properties (name, header, bg color, sub hdr notes, etc. -- emailto, reply, etc. to be added later)
# - Populate field[] from form_fields
# - Set these to blank or don't even touch them for new form

// add_field($data[])
//----------------------------------------------------------------------------------------------------
# - Push submitted data for each field as data array 'row' to $field[]

#######################################################################################################

class form_manage {
   var $prop = array(); // Mirror of assoc db array (structure-wise)
   var $cols = array(); // Names of each field in form_fields table (no data)
   var $field = array(); // Data array for each form field, indexed by field_id

   var $nCols; // Total number of field data headers (should typically be the same number)
   var $nRows; // Total number of form fields (incremented on add)

   var $disOut = array(); // Eeach assoc index will contain output html from the relevant method



   /// Compile all available form and field data
   ###:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
   function form_manage($frmid) {

      # Pull current form properties
      $frmrez = mysql_query("SELECT * FROM form_properties WHERE FORM_ID = '$frmid'");
      if ( !$this->prop = mysql_fetch_array($frmrez) ) {
         echo "Could not fetch prop array<br>Because: ".mysql_error(); exit;
      }

      # Pull field data headers
      $colrez = mysql_query("SELECT * FROM form_fields LIMIT 2");
      $colcnt = mysql_num_fields($colrez);
      for ( $c = 0; $c < $colcnt;  $c++ ) {
         $this->cols[] = mysql_field_name($colrez, $c);
      }

      # Pull current field data (if any)
      $fldrez = mysql_query("SELECT * FROM form_fields WHERE FORM_ID = '$frmid'");
      $fldnum = mysql_num_rows($fldrez);
      if ( $fldnum > 0 ) {
         while ( $getFld = mysql_fetch_array($frmrez) ) {
            $key = $getFld['FIELD_ID'];
            $this->field[$key] = $getFld;
         }
      }

      # Compile javascript functions for output in hdr
      //$this->build_jstuff();

   } // End data-gathering constructor


   /// Build javascript functions used by this object
   // Any javascript functions utilized in output methods
   ###================================================================================
   function build_jscript() {
      $this->disOut[jscript] = "<script language=\"javascript\">\n";
      //$this->disOut[jscript] .= "   window.alert('field_type = ');\n";
      // Show relavent option fields when field type selected
      /*-----------------------------------------------------------*/
      $this->disOut[jscript] .= " function chkop(frm, fld) { \n";
      $this->disOut[jscript] .= "   op_labels.style.display = 'none';\n"; // Hide extra option field
      $this->disOut[jscript] .= "   var rad = 'document.'+frm+'.'+fld;\n";
      $this->disOut[jscript] .= "   var cnt = eval('document.'+frm+'.'+fld+'.length');\n";
      $this->disOut[jscript] .= "   for (i=0; i<cnt; i++) {\n";
      $this->disOut[jscript] .= "      chk = eval(rad+'['+i+'].checked');\n";
      $this->disOut[jscript] .= "      vlu = eval(rad+'['+i+'].value');\n";
      $this->disOut[jscript] .= "      if ( chk == true && (vlu == 'select' || vlu == 'radio' || vlu == 'checkboxes') ) {\n";
      $this->disOut[jscript] .= "         op_labels.style.display = 'block';\n"; // Show extra option field
      //$this->disOut[jscript] .= "         eval('op_'+vlu+'.style.display') = 'block';\n"; // Type-specific layer ON
      $this->disOut[jscript] .= "      } else {\n";
      //$this->disOut[jscript] .= "         eval('op_'+vlu+'.style.display') = 'none';\n"; // Type-specific layer OFF
      $this->disOut[jscript] .= "      }\n";
      $this->disOut[jscript] .= "   }\n";
      $this->disOut[jscript] .= " }\n";
      /*-----------------------------------------------------------*/

      $this->disOut[jscript] .= "</script>\n\n";

   }


/*#############################################################################################*
 ___                   ___
| __|___  _ _  _ __   / __| _  _  _ _  __ _  ___  _ _  _  _
| _|/ _ \| '_|| '  \  \__ \| || || '_|/ _` |/ -_)| '_|| || |
|_| \___/|_|  |_|_|_| |___/ \_,_||_|  \__, |\___||_|   \_, |
                                      |___/            |__/
/// Compile form html display for editing - // Overview of form fields w/ edit options
/*#############################################################################################*/

   ###================================================================================
   function build_surgery() {
      $this->disOut[surgery] = "<table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" class=\"feature_sub\">\n";
      $this->disOut[surgery] .= " <tr>\n";
      $this->disOut[surgery] .= "  <td colspan=\"5\" class=\"fsub_title\">\n";
      $this->disOut[surgery] .= "   ".$this->prop['FORM_NAME']."\n";
      $this->disOut[surgery] .= "  </td>\n";
      $this->disOut[surgery] .= " </tr>\n";

      ## Col Headers
      ##---------------------------------------------------
      $this->disOut[surgery] .= " <tr>\n";
      $this->disOut[surgery] .= "  <td class=\"fsub_col\" style=\"border-left: 0px;\">\n";
      $this->disOut[surgery] .= "   Field Label\n";
      $this->disOut[surgery] .= "  </td>\n";
      $this->disOut[surgery] .= "  <td class=\"fsub_col\">\n";
      $this->disOut[surgery] .= "   Field Name\n";
      $this->disOut[surgery] .= "  </td>\n";
      $this->disOut[surgery] .= "  <td class=\"fsub_col\">\n";
      $this->disOut[surgery] .= "   Field Type\n";
      $this->disOut[surgery] .= "  </td>\n";
      $this->disOut[surgery] .= "  <td class=\"fsub_col\">\n";
      $this->disOut[surgery] .= "   Options\n";
      $this->disOut[surgery] .= "  </td>\n";
      $this->disOut[surgery] .= " </tr>\n";

      if ( $this->nRows > 0 ) {
         $this->disOut[surgery] .= " <tr>\n";
         $this->disOut[surgery] .= "  <td class=\"fsub_col\">\n";
         $this->disOut[surgery] .= "   Field Label\n";
         $this->disOut[surgery] .= "  </td>\n";
         $this->disOut[surgery] .= "  <td class=\"col_title\">\n";
         $this->disOut[surgery] .= "   Field Name\n";
         $this->disOut[surgery] .= "  </td>\n";
         $this->disOut[surgery] .= "  <td class=\"col_title\">\n";
         $this->disOut[surgery] .= "   Field Type\n";
         $this->disOut[surgery] .= "  </td>\n";
         $this->disOut[surgery] .= "  <td class=\"col_title\">\n";
         $this->disOut[surgery] .= "   Options\n";
         $this->disOut[surgery] .= "  </td>\n";
         $this->disOut[surgery] .= " </tr>\n";

      } else {
         $this->disOut[surgery] .= " <tr>\n";
         $this->disOut[surgery] .= "  <td colspan=\"5\" align=\"center\">\n";
         $this->disOut[surgery] .= "   This form does not yet contain any fields.\n";
         $this->disOut[surgery] .= "  </td>\n";
         $this->disOut[surgery] .= " </tr>\n";
         $this->disOut[surgery] .= " <tr>\n";
         $this->disOut[surgery] .= "  <td colspan=\"5\" align=\"center\">\n";
         $this->disOut[surgery] .= "   form_class_inc.php - Just added html for addform<br>\n";
         $this->disOut[surgery] .= "   - now make it work and put together with outer_shell!.\n";
         $this->disOut[surgery] .= "  </td>\n";
         $this->disOut[surgery] .= " </tr>\n";

      }

      // Output a row for each form field in $this->field[]

      $this->disOut[surgery] .= "</table>\n";
   }


/*#############################################################################################*
    _       _     _     ___  _       _     _
   /_\   __| | __| |   | __|(_) ___ | | __| |
  / _ \ / _` |/ _` |   | _| | |/ -_)| |/ _` |
 /_/ \_\\__,_|\__,_|   |_|  |_|\___||_|\__,_|
 // Form through which fields are added and edited
/*#############################################################################################*/
   function build_addfield() {
      global $btn_build;
      global $btn_save;
      $frm_name = "add_new_field";
      $css_table = "feature_sub";
      $css_title = "fsub_title";

      $this->addfield .= "<form name=\"".$frm_name."\" method=\"post\" action=\"form_builder.php\">\n";
      $this->addfield .= "<input type=\"hidden\" name=\"do\" value=\"add_field\">\n";
      $this->addfield .= "<input type=\"hidden\" name=\"form_id\" value=\"".$this->prop['FORM_ID']."\">\n";
      $this->addfield .= "<input type=\"hidden\" name=\"db_cols\" value=\"".implode(";", $this->cols)."\">\n";

      $this->addfield .= "<table width=\"95%\"  border=\"0\" cellpadding=\"4\" cellspacing=\"0\" class=\"".$css_table."\">\n";
      $this->addfield .= " <tr>\n";
      $this->addfield .= "  <td colspan=\"4\" class=\"".$css_title."\">Add New Field to Form</td>\n"; // 'Add New Field to Form'


      # [ Save New Field ]
      $this->addfield .= "  <td colspan=\"2\" align=\"right\" class=\"".$css_title."\" style=\"padding-right: 20px;\">\n";
      $this->addfield .= "   <input type=\"submit\" value=\"Add New Field\"".$btn_build."\">\n";
      $this->addfield .= "  </td>\n";

      $this->addfield .= " </tr>\n";
      $this->addfield .= " <tr>\n";

      # field_label
      $this->addfield .= "  <td width=\"11%\">Field Label: </td>\n";
      $this->addfield .= "  <td width=\"22%\"><input name=\"field_label\" value=\"".$field_label."\" type=\"text\" class=\"tfield\" style=\"width:125;\"></td>\n";

      # field_name
      $this->addfield .= "  <td width=\"14%\" align=\"right\">Field Name:</td>\n";
      $this->addfield .= "  <td width=\"27%\"><input name=\"field_name\" value=\"".$field_name."\" type=\"text\" class=\"tfield\" style=\"width:125;\"></td>\n";

      # require
      $this->addfield .= "  <td width=\"10%\" align=\"right\">Required: </td>\n";
      $this->addfield .= "  <td width=\"16%\">\n";
      $this->addfield .= "   <select name=\"require\" id=\"require\">\n";
      $this->addfield .= "    <option value=\"no\" selected>No</option>\n";
      $this->addfield .= "    <option value=\"yes\">Yes</option>\n";
      $this->addfield .= "   </select>\n";
      $this->addfield .= "  </td>\n";
      $this->addfield .= " </tr>\n";

      $this->addfield .= " <tr>\n";
      $this->addfield .= "  <td>Field Type:</td>\n";
      $this->addfield .= "  <td colspan=\"5\" style=\"padding-left: 0px;\">\n";
   	$this->addfield .= "   <table  border=\"0\" cellpadding=\"4\" cellspacing=\"0\" class=\"text\">\n";
      $this->addfield .= "    <tr>\n";

      ## Build field type array (for id)...id not really needed with new js method
      ##----------------------------------------------------------------------------------
      $fld_typ[] = array(disp=>"Text Box", val=>"text", chk=>"checked");
      $fld_typ[] = array(disp=>"Text Area (Multi-Line)", val=>"textarea", chk=>"");
      $fld_typ[] = array(disp=>"Drop-Down", val=>"select", chk=>"");
      $fld_typ[] = array(disp=>"Radio Buttons", val=>"radio", chk=>"");
      $fld_typ[] = array(disp=>"Checkboxes", val=>"checkboxes", chk=>"");

      # field_type
      for ( $i = 0; $i < count($fld_typ); $i++ ) {
         $fty = $fld_typ[$i];
         $this->addfield .= "     <td align=\"right\" style=\"padding-left: 10px;\">\n";
         $this->addfield .= "      <input name=\"field_type\" type=\"radio\" value=\"".$fty[val]."\" onclick=\"chkop('".$frm_name."','field_type')\"".$fty[chk].">\n";
         $this->addfield .= "     </td>\n";
         $this->addfield .= "     <td style=\"padding-left: 0px;\">".$fty[disp]."</td>\n";
      }


      $this->addfield .= "    </tr>\n";
      $this->addfield .= "   </table>\n";
      $this->addfield .= "  </td>\n";
      $this->addfield .= " </tr>\n";

      ## hidden option list field (op_labels)
      ##===============================================
      $this->addfield .= " <tr id=\"op_labels\" style=\"display: none;\">\n";
      $this->addfield .= "  <td colspan=\"6\">\n";
      $this->addfield .= "   <table width=\"100%\"  border=\"0\" cellpadding=\"4\" cellspacing=\"0\" class=\"text\">\n";
      $this->addfield .= "    <tr>\n";
      $this->addfield .= "     <td style=\"padding-left: 0px;\" width=\"42%\" valign=\"top\">Enter selectable options seperated by semicolons:</td>\n";
      $this->addfield .= "     <td width=\"58%\">&nbsp;</td>\n";
      $this->addfield .= "    </tr>\n";

      # opt_lables
      $this->addfield .= "    <tr>\n";
      $this->addfield .= "     <td colspan=\"2\" style=\"padding-left: 0px;\">\n";
      $this->addfield .= "      <textarea name=\"opt_lables\" class=\"tfield\" style=\"width: 640px; height: 40px;\">".$fopt['labels']."</textarea>\n";
      $this->addfield .= "     </td>\n";
      $this->addfield .= "    </tr>\n";
      $this->addfield .= "   </table>\n";
      $this->addfield .= "  </td>\n";
      $this->addfield .= " </tr>\n";


      /* Footer row with inactive buttons */
      /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*
      $this->addfield .= " <tr>\n";
      $this->addfield .= "  <td colspan=\"3\" align=\"center\" class=\"fgrn_title\">\n";
      $this->addfield .= "   <input type=\"button\" value=\"[Save] Finish Form\"".$btn_save.">\n";
      $this->addfield .= "  </td>\n";
      $this->addfield .= "  <td colspan=\"3\" align=\"center\" class=\"fgrn_title\">\n";
      $this->addfield .= "   <input type=\"button\" value=\"^^ Add New Field ^^\"".$btn_build.">\n";
      $this->addfield .= "  </td>\n";
      $this->addfield .= "</tr>\n";
      /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
      $this->addfield .= "</form>\n";
      $this->addfield .= "</table>\n";

      # Make sure option box appears if editing applicable field types
      $this->addfield .= "<script language=\"javascript\">chkop('".$frm_name."','field_type');</script>\n";

   } // End Add Form method


/*#############################################################################################*
 ___            _                      ___
| __|___  __ _ | |_  _  _  _ _  ___   / __| _ _  ___  _  _  _ __
| _|/ -_)/ _` ||  _|| || || '_|/ -_) | (_ || '_|/ _ \| || || '_ \
|_| \___|\__,_| \__| \_,_||_|  \___|  \___||_|  \___/ \_,_|| .__/
                                                           |_|
/// Output feature group table with compiled data
// Outer parent table containing dissect and addform
/*#############################################################################################*/
   function feature_group($title = "") {
      global $btn_build;
      global $btn_save;

      $this->disOut[fgroup] .= $this->disOut[jscript];
      $this->disOut[fgroup] .= "<br>\n";
      $this->disOut[fgroup] .= "<table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\" class=\"feature_group\">\n";
      $this->disOut[fgroup] .= " <tr>\n";
      $this->disOut[fgroup] .= "  <td colspan=\"2\" width=\"50%\" class=\"fgroup_title\">\n";
      $this->disOut[fgroup] .= "   ".$title."\n";
      $this->disOut[fgroup] .= "  </td>\n";
      $this->disOut[fgroup] .= " </tr>\n";
      $this->disOut[fgroup] .= " <tr>\n";
      $this->disOut[fgroup] .= "  <td width=\"50%\" colspan=\"2\" align=\"center\" valign=\"top\">\n";
      $this->disOut[fgroup] .= "   ".$this->addfield."\n";
      $this->disOut[fgroup] .= "  </td>\n";
      $this->disOut[fgroup] .= " </tr>\n";
      $this->disOut[fgroup] .= " <tr>\n";
      $this->disOut[fgroup] .= "  <td width=\"50%\" colspan=\"2\" align=\"center\" valign=\"top\">\n";
      $this->disOut[fgroup] .= "   ".$this->dissect."\n";
      $this->disOut[fgroup] .= "  </td>\n";
      $this->disOut[fgroup] .= " </tr>\n";
      $this->disOut[fgroup] .= "</table>\n";
   }



} // End form_build class


/*=============================================================================================*
    ____          __            ____
   / __ \ ____ _ / /_ ____ _   / __ \ __  __ ___   _____ __  __
  / / / // __ `// __// __ `/  / / / // / / // _ \ / ___// / / /
 / /_/ // /_/ // /_ / /_/ /  / /_/ // /_/ //  __// /   / /_/ /
/_____/ \__,_/ \__/ \__,_/   \___\_\\__,_/ \___//_/    \__, /
                                                      /____/
/*=============================================================================================*/
class form_qry {
   var $db_table; // Name of applicable database table
   var $db_field = array(); // Assoc array of each field name in given table
   var $db_stuff; // final query string

   /// Compile list of field names from db
   ###====================================================
   function form_qry($tablename, $mkForm) { // (releveant db table, known values indexed by field name)
      global $db_name;
      $this->db_table = $tablename;

      if ( !$get_list = mysql_query("SELECT * FROM $tablename") ) {
//         echo "Error: Unable to list fields of table '$tablename' on DB '$db_name'<br><u>Because</u>:".mysql_error(); exit;
      }
      $get_num = mysql_num_fields($get_list);

      # Add known data to qry array
      for ( $f=0; $f < $get_num; $f++ ) {
         $col = mysql_field_name($get_list, $f);
         /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
         if ( isset($mkForm[$col]) ) {
            $this->db_field[$col] = $mkForm[$col];

         } elseif ( $col == "PRIKEY" ) {
            $this->db_field[$col] = NULL;

         } else {
            $this->db_field[$col] = "";
         }
         /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
      }

      # Build db insert string
      foreach ( $this->db_field as $fld => $val ) {
         $this->db_stuff .= "'$val', ";
      }

      # Format qry string
      $this->db_stuff = trim($this->db_stuff); // Kill trailing space
      $this->db_stuff = substr($this->db_stuff, 0, -1); // Kill trailing ","

   } // End array-building form_qry constructor


   /// Output as raw string and HTML table for testing
   ###----------------------------------------------------
   function test_qry() {
      echo "<div id=\"scrollLayer\" style=\"position:absolute; visibility:visible; left:0px; top:0; width:100%; height:100%; z-index:1; overflow: auto; border: 1px none #000000\">\n";
      echo "<br><u>Test Qry Output:</u><br>\n";
      echo "<textarea style=\"font-family: arial; font-size: 11px; width: 725px; height: 100px;\">".$this->db_stuff."</textarea><br><br>\n";
      echo "<table width=\"100%\" cellpadding=\"4\" cellspacing=\"0\" border=\"1\">\n";
      echo " <tr>\n";
      foreach ( $this->db_field as $col => $val ) {
         echo "  <td bgcolor=\"#000000\" style=\"font-family: arial; font-size: 11px; color: #F8F9FD;\">\n";
         echo "   <b>".$col."</b>\n";
         echo "  </td>\n";
      }
      echo " </tr>\n";
      $tFlds = explode(",", $this->db_stuff);
      echo " <tr>\n";
      foreach ( $tFlds as $val ) {
         $val = str_replace("'", "", $val);
         echo "  <td bgcolor=\"#EFEFEF\" style=\"font-family: arial; font-size: 11px; color: #000000;\">\n";
         echo "   ".$val."\n";
         echo "  </td>\n";
      }
      echo " </tr>\n";
      echo "</table>\n";
      echo "</div>\n";

   } // End test_qry method

   /// Insert data into database table
   ###vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
   function do_insert() {
      if ( !$inserted = mysql_query("INSERT INTO $this->db_table VALUES($this->db_stuff)") ) {
         return false;
         //echo "<b>Error</b>: <i>".mysql_error()."</i><br>\n";
      } else {
         return true;
      }
   }
/*=============================================================================================*/
} // End form_qry class

?>