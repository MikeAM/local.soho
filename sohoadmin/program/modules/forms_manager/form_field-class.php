<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


session_start();
error_reporting(E_PARSE);
//echo "This is: ".$_SERVER['SCRIPT_NAME']."<br>"; exit;
/*=============================================================================================*
              _      _            ______     _  _  _      ______  _        _      _ 
    /\       | |    | |          |  ____|   | |(_)| |    |  ____|(_)      | |    | |
   /  \    __| |  __| |  ______  | |__    __| | _ | |_   | |__    _   ___ | |  __| |
  / /\ \  / _` | / _` | |______| |  __|  / _` || || __|  |  __|  | | / _ \| | / _` |
 / ____ \| (_| || (_| |          | |____| (_| || || |_   | |     | ||  __/| || (_| |
/_/    \_\\__,_| \__,_|          |______|\__,_||_| \__|  |_|     |_| \___||_| \__,_| 

/*=============================================================================================*/
class form_field {
   var $db_props = array(); // Field data assoc array, indexed by field names from form_fields
   var $db_cols = array(); // Array of field names, numerical index
   var $form_id; // ID of form that this field is associated with
   var $doval; // Target process of any forms outputted by object
   
   function form_field($form_id = "", $doval = "") {
      # Set form id
      $this->form_id = $form_id;
      $this->doval = $doval;
      
      # Pull field property names from db
      $colrez = mysql_query("SELECT * FROM form_fields LIMIT 1");
      $colcnt = mysql_num_fields($colrez);
      for ( $c = 0; $c < $colcnt;  $c++ ) {
         $col = mysql_field_name($colrez, $c);
         $this->db_cols[] = $col;
      }
      
   } // End field constructor
   
   
   function get_props($field_id) {
      # Pull field data from db by passed id
      $fldrez = mysql_query("SELECT * FROM form_fields WHERE FIELD_ID = '$field_id'");
      $fldnum = mysql_num_rows($fldrez);
      if ( $fldnum > 0 ) {
         while ( $getFld = mysql_fetch_array($frmrez) ) {
            $this->db_props = $getFld;
         }
      }
   }
   
   function set_props($data) {
      # Populate prop data with passed array
      foreach ( $data as $nam=>$val ) {
         $this->db_props[$nam] = $val;
      }
   }
   
   /*#############################################################################################*
       _       _     _     ___  _       _     _ 
      /_\   __| | __| |   | __|(_) ___ | | __| |
     / _ \ / _` |/ _` |   | _| | |/ -_)| |/ _` |
    /_/ \_\\__,_|\__,_|   |_|  |_|\___||_|\__,_|
    // Form through which fields are added and edited
   /*#############################################################################################*/
   function add_edit() {
      global $btn_build;
      global $btn_save;
      global $noDice;
      
      $frm_name = "add_new_field";
      $req_fields = "FIELD_LABEL;FIELD_NAME;FIELD_TYPE";
      $css_table = "feature_sub";
      $css_title = "fsub_title";
      
      // Show relavent option fields when field type selected
      /*-----------------------------------------------------------*/
      $html_output = "\n\n";
      $html_output .= "<script language=\"javascript\">\n";  
      $html_output .= " function chkop(frm, fld) { \n";
      $html_output .= "   op_labels.style.display = 'none';\n"; // Hide extra option field
      $html_output .= "   var rad = 'document.'+frm+'.'+fld;\n";
      $html_output .= "   var cnt = eval('document.'+frm+'.'+fld+'.length');\n";
      $html_output .= "   for (i=0; i<cnt; i++) {\n";
      $html_output .= "      chk = eval(rad+'['+i+'].checked');\n";
      $html_output .= "      vlu = eval(rad+'['+i+'].value');\n";
      $html_output .= "      if ( chk == true && (vlu == 'select' || vlu == 'radio' || vlu == 'checkboxes') ) {\n";
      $html_output .= "         op_labels.style.display = 'block';\n"; // Show extra option field
      //$html_output .= "         eval('op_'+vlu+'.style.display') = 'block';\n"; // Type-specific layer ON
      $html_output .= "      } else {\n";
      //$html_output .= "         eval('op_'+vlu+'.style.display') = 'none';\n"; // Type-specific layer OFF
      $html_output .= "      }\n";
      $html_output .= "   }\n";
      $html_output .= " }\n";
      $html_output .= "</script>\n\n";    
      /*-----------------------------------------------------------*/
      
      $html_output .= "<form name=\"".$frm_name."\" method=\"post\" action=\"form_builder.php\">\n";
      $html_output .= "<input type=\"hidden\" name=\"do\" value=\"".$this->doval."\">\n";
      $html_output .= "<input type=\"hidden\" name=\"form_id\" value=\"".$this->form_id."\">\n";
      $html_output .= "<input type=\"hidden\" name=\"field_id\" value=\"".$this->db_props['FIELD_ID']."\">\n";
      $html_output .= "<input type=\"hidden\" name=\"db_cols\" value=\"".implode(";", $this->db_cols)."\">\n";
      $html_output .= "<input type=\"hidden\" name=\"req_fields\" value=\"".$req_fields."\">\n";
            
      $html_output .= "<table width=\"100%\"  border=\"0\" cellpadding=\"4\" cellspacing=\"0\" class=\"".$css_table."\">\n";
      $html_output .= " <tr>\n";
      $html_output .= "  <td colspan=\"4\" class=\"".$css_title."\">Add New Field to Form</td>\n"; // 'Add New Field to Form'
   
      # [ Save New Field ]
      $html_output .= "  <td colspan=\"2\" align=\"right\" class=\"".$css_title."\" style=\"padding-right: 20px;\">\n";
      $html_output .= "   <input type=\"submit\" value=\"Save New Field\"".$btn_save."\">\n";
      $html_output .= "  </td>\n";
      
      $html_output .= " </tr>\n";
      $html_output .= " <tr>\n";
      
      # FIELD_LABEL
      $html_output .= "  <td width=\"11%\">\n";
      $html_output .= "   Field Label:\n";
      $html_output .= "  </td>\n";
      $html_output .= "  <td width=\"22%\">";
      $html_output .= "   ".errchk("FIELD_LABEL", "<input name=\"FIELD_LABEL\" value=\"".$this->db_props['FIELD_LABEL']."\" type=\"text\" class=\"tfield\" style=\"width:125;\">");
      $html_output .= "  </td>\n";
      
      # FIELD_NAME
      $html_output .= "  <td width=\"14%\" align=\"right\">\n";
      $html_output .= "   Field Name:\n";
      $html_output .= "  </td>\n";
      $html_output .= "  <td width=\"27%\">";
      $html_output .= "   ".errchk("FIELD_NAME", "<input name=\"FIELD_NAME\" value=\"".$this->db_props['FIELD_NAME']."\" type=\"text\" class=\"tfield\" style=\"width:125;\">");
      $html_output .= "  </td>\n";
      
      # REQUIRE
      $html_output .= "  <td width=\"10%\" align=\"right\">Required: </td>\n";
      $html_output .= "  <td width=\"16%\">\n";
      $html_output .= "   <select name=\"REQUIRE\">\n";
      $html_output .= "    ".mkopts("REQUIRE", "no;yes", "No;Yes", "no");
      //function mkopts($field,$values,$labels,$default = "") {
      $html_output .= "   </select>\n";
      $html_output .= "  </td>\n";
      $html_output .= " </tr>\n";
      
      $html_output .= " <tr>\n";
      $html_output .= "  <td>\n";
      $html_output .= "   Field Type:\n";
      $html_output .= "  </td>\n";
      $html_output .= "  <td colspan=\"5\">\n";
      $html_output .= "   ".errchk("FIELD_TYPE", "<table  border=\"0\" cellpadding=\"4\" cellspacing=\"0\" class=\"text\">\n");
      $html_output .= "    <tr>\n";
      
      ## Build field type array (for id)...id not really needed with new js method
      ##----------------------------------------------------------------------------------
      $fld_typ[] = array(disp=>"Text Box", val=>"text", chk=>"checked");
      $fld_typ[] = array(disp=>"Text Area (Multi-Line)", val=>"textarea", chk=>"");
      $fld_typ[] = array(disp=>"Drop-Down", val=>"select", chk=>"");
      $fld_typ[] = array(disp=>"Radio Buttons", val=>"radio", chk=>"");
      $fld_typ[] = array(disp=>"Checkboxes", val=>"checkboxes", chk=>"");
      
      # FIELD_TYPE
      for ( $i = 0; $i < count($fld_typ); $i++ ) {
         $fty = $fld_typ[$i];
         if ( $this->db_props['FIELD_TYPE'] == $fty['val'] ) { $fty['chk'] = " checked"; } else { $fty['chk'] = ""; }
         $html_output .= "     <td align=\"right\" style=\"padding-left: 10px;\">\n";
         $html_output .= "      <input name=\"FIELD_TYPE\" type=\"radio\" value=\"".$fty[val]."\" onclick=\"chkop('".$frm_name."','FIELD_TYPE')\"".$fty[chk].">\n";
         $html_output .= "     </td>\n";
         $html_output .= "     <td style=\"padding-left: 0px;\">".$fty[disp]."</td>\n";
      }
         
      
      $html_output .= "    </tr>\n";
      $html_output .= "   </table>\n";
      $html_output .= "  </td>\n";
      $html_output .= " </tr>\n";
      
      ## hidden option list field (op_labels)
      ##===============================================
      $html_output .= " <tr id=\"op_labels\" style=\"display: none;\">\n";
      $html_output .= "  <td colspan=\"6\">\n";
      $html_output .= "   <table width=\"100%\"  border=\"0\" cellpadding=\"4\" cellspacing=\"0\" class=\"text\">\n";
      $html_output .= "    <tr>\n";
      $html_output .= "     <td style=\"padding-left: 0px;\" width=\"42%\" valign=\"top\">\n";
      $html_output .= "      Enter selectable options seperated by semicolons:\n";
      $html_output .= "     </td>\n";
      $html_output .= "     <td width=\"58%\">&nbsp;</td>\n";
      $html_output .= "    </tr>\n";
      
      # OPT_LABLES
      $html_output .= "    <tr>\n";
      $html_output .= "     <td colspan=\"2\" style=\"padding-left: 0px;\">\n";
      $html_output .= "      ".errchk("OPT_LABELS", "<textarea name=\"OPT_LABELS\" class=\"tfield\" style=\"width: 640px; height: 40px;\">".$this->db_props['OPT_LABELS']."</textarea>\n");
      $html_output .= "     </td>\n";
      $html_output .= "    </tr>\n";
      $html_output .= "   </table>\n";
      $html_output .= "  </td>\n";
      $html_output .= " </tr>\n";
      
      
      /* Footer row with inactive buttons */
      /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*
      $html_output .= " <tr>\n";
      $html_output .= "  <td colspan=\"3\" align=\"center\" class=\"fgrn_title\">\n";
      $html_output .= "   <input type=\"button\" value=\"[Save] Finish Form\"".$btn_save.">\n";
      $html_output .= "  </td>\n";      
      $html_output .= "  <td colspan=\"3\" align=\"center\" class=\"fgrn_title\">\n";
      $html_output .= "   <input type=\"button\" value=\"^^ Add New Field ^^\"".$btn_build.">\n";
      $html_output .= "  </td>\n";
      $html_output .= "</tr>\n";
      /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
      $html_output .= "</form>\n";
      $html_output .= "</table>\n";
      
      # Make sure option box appears if editing applicable field types
      $html_output .= "<script language=\"javascript\">chkop('".$frm_name."','FIELD_TYPE');</script>\n";
      
      return $html_output;
      
   } // End Add Form method
   
   
} // End Form Field class
/*=============================================================================================*/



?>