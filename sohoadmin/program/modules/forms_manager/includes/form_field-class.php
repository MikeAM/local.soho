<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


/*=============================================================================================*
              _      _            ______     _  _  _      ______  _        _      _ 
    /\       | |    | |          |  ____|   | |(_)| |    |  ____|(_)      | |    | |
   /  \    __| |  __| |  ______  | |__    __| | _ | |_   | |__    _   ___ | |  __| |
  / /\ \  / _` | / _` | |______| |  __|  / _` || || __|  |  __|  | | / _ \| | / _` |
 / ____ \| (_| || (_| |          | |____| (_| || || |_   | |     | ||  __/| || (_| |
/_/    \_\\__,_| \__,_|          |______|\__,_||_| \__|  |_|     |_| \___||_| \__,_| 

/*=============================================================================================*/
class form_field {   
   
   function form_field($field_id = "") {
      
      
   } // End field constructor
   
   
   /*#############################################################################################*
       _       _     _     ___  _       _     _ 
      /_\   __| | __| |   | __|(_) ___ | | __| |
     / _ \ / _` |/ _` |   | _| | |/ -_)| |/ _` |
    /_/ \_\\__,_|\__,_|   |_|  |_|\___||_|\__,_|
    // Form through which fields are added and edited
   /*#############################################################################################*/
   function field_form() {
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
   
   
} // End Form Field class
/*=============================================================================================*/



?>