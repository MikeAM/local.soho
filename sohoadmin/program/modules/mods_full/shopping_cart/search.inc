<script type="text/javascript">
// Selects date range radio when user clicks one of the date range dropdowns
// Saves them a click and avoids them trying the search and thinking it's not working because their date range doesn't apply (because the didn't select the radio)
function pick_date_range() {
//   radio-daterange
}
</script>


<FORM METHOD=POST ACTION="view_orders.php" id="search-orders-form">
<INPUT TYPE=HIDDEN NAME=ACTION VALUE="PROCESS">

  <TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="5" class="feature_sub">
    <TR>
      <TD COLSPAN="2" class="fsub_title">
       <? echo lang("Search Orders"); ?>
      </TD>
    </TR>
    <TR>
      <TD ALIGN="LEFT" VALIGN="TOP"> <B>1. <? echo lang("Select your preferred search method"); ?>:</B><BR>
        <BR>
        <INPUT TYPE="radio" NAME="search" VALUE="ordernumbers" id="radio-ordernumbers" CHECKED>
        <? echo lang("Show order numbers"); ?>:<BR>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo lang("From"); ?>:
        <INPUT TYPE="text" NAME="start_num" CLASS=text STYLE='width: 75px;' onfocus="$('radio-ordernumbers').checked = true;">
        <? echo lang("To"); ?>:
        <INPUT TYPE="text" NAME="end_num" CLASS=text STYLE='width: 75px;' onfocus="$('radio-ordernumbers').checked = true;">
        <BR>
      </TD>
      <TD ALIGN="LEFT" VALIGN="TOP"><B>2. <? echo lang("Select how results should be sorted for viewing"); ?>: </B><BR>
        <BR>
        &nbsp;&nbsp;<? echo lang("Sort by"); ?>:
        <SELECT NAME="sortby" class=text style='width: 125px;'>
          <OPTION VALUE="ORDER_DATE"><? echo lang("Order Date"); ?></OPTION>
          <OPTION VALUE="ORDER_NUMBER" SELECTED><? echo lang("Order Number"); ?></OPTION>
          <OPTION VALUE="BILLTO_LASTNAME"><? echo lang("Customer Name"); ?></OPTION>
          <OPTION VALUE="TOTAL_SALE"><? echo lang("Total Sale"); ?></OPTION>
          <OPTION VALUE="PAY_METHOD"><? echo lang("Payment Method"); ?></OPTION>
          <OPTION VALUE="TRANSACTION_STATUS"><? echo lang("Status"); ?></OPTION>
          <OPTION VALUE="TRANSACTION_ID"><? echo lang("Transaction ID"); ?></OPTION>
        </SELECT>
        <BR>
        <BR>
        <? echo lang("Order by"); ?>:
        <SELECT NAME="sortby_dir" class=text style='width: 100px;'>
          <OPTION VALUE="ASC"><? echo lang("Ascending"); ?></OPTION>
          <OPTION VALUE="DESC" SELECTED><? echo lang("Descending"); ?></OPTION>
        </SELECT>

        <div id="show_incomplete-container"><input type="checkbox" id="show_incomplete" name="show_incomplete" value="yes"><label for="show_incomplete">Show incomplete orders?</label></div>
      </TD>
    </TR>
    <TR>
      <TD>
        <INPUT TYPE="radio" NAME="search" VALUE="daterange" id="radio-daterange">
        <? echo lang("Date range"); ?>: <B><FONT FACE="Arial, Helvetica, sans-serif" SIZE="1" COLOR="#999999">
        </FONT></B><BR>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo lang("From"); ?>:
        <?php
        echo "<select CLASS=text name=\"start_date_month\" id=\"start_date\" onfocus=\"$('radio-daterange').checked = true;\">\n";
        echo "<option value=\"01\" selected=\"selected\">Jan</option>\n";
        echo "<option value=\"02\">Feb</option>\n";
        echo "<option value=\"03\">Mar</option>\n";
        echo "<option value=\"04\">Apr</option>\n";
        echo "<option value=\"05\">May</option>\n";
        echo "<option value=\"06\">Jun</option>\n";
        echo "<option value=\"07\">Jul</option>\n";
        echo "<option value=\"08\">Aug</option>\n";
        echo "<option value=\"09\">Sept</option>\n";
        echo "<option value=\"10\">Oct</option>\n";
        echo "<option value=\"11\">Nov</option>\n";
        echo "<option value=\"12\">Dec</option>\n";
        echo "</select>\n";


        echo "<select CLASS=text name=\"start_date_day\" id=\"start_date_day\" onfocus=\"$('radio-daterange').checked = true;\">\n";
        $xox=01;
        while($xox <= 31){

        	echo "<option value=\"".sprintf("%02s", $xox)."\">".sprintf("%02s", $xox)."</option>\n";
        	++$xox;
        }
        echo "</select>\n";

        $xox=0;
        echo "<select CLASS=text name=\"start_date_year\" id=\"start_date_year\" onfocus=\"$('radio-daterange').checked = true;\">\n";
        while($xox <= 20){

        	echo "<option value=\"".sprintf("%04s", (date(Y) - $xox))."\">".sprintf("%04s", (date(Y) - $xox))."</option>\n";
        	++$xox;
        }
        echo "</select>\n";

///<INPUT TYPE="text" NAME="start_date" CLASS=text STYLE='width: 75px;'>
////<INPUT TYPE="text" NAME="end_date" CLASS=text STYLE='width: 75px;'>
				echo "&nbsp;".lang("To")."&nbsp;";

        echo "<select CLASS=text name=\"end_date_month\" id=\"start_date\" onfocus=\"$('radio-daterange').checked = true;\">\n";
        echo "<option value=\"01\" selected=\"selected\">Jan</option>\n";
        echo "<option value=\"02\">Feb</option>\n";
        echo "<option value=\"03\">Mar</option>\n";
        echo "<option value=\"04\">Apr</option>\n";
        echo "<option value=\"05\">May</option>\n";
        echo "<option value=\"06\">Jun</option>\n";
        echo "<option value=\"07\">Jul</option>\n";
        echo "<option value=\"08\">Aug</option>\n";
        echo "<option value=\"09\">Sept</option>\n";
        echo "<option value=\"10\">Oct</option>\n";
        echo "<option value=\"11\">Nov</option>\n";
        echo "<option value=\"12\">Dec</option>\n";
        echo "</select>\n";


        echo "<select CLASS=text name=\"end_date_day\" id=\"start_date_day\" onfocus=\"$('radio-daterange').checked = true;\">\n";
        $xox=01;
        while($xox <= 31){

        	echo "<option value=\"".sprintf("%02s", $xox)."\">".sprintf("%02s", $xox)."</option>\n";
        	++$xox;
        }
        echo "</select>\n";

        $xox=0;
        echo "<select CLASS=text name=\"end_date_year\" id=\"start_date_year\" onfocus=\"$('radio-daterange').checked = true;\">\n";
        while($xox <= 20){

        	echo "<option value=\"".sprintf("%04s", (date(Y) - $xox))."\">".sprintf("%04s", (date(Y) - $xox))."</option>\n";
        	++$xox;
        }
        echo "</select>\n";

///<INPUT TYPE="text" NAME="start_date" CLASS=text STYLE='width: 75px;'>
////<INPUT TYPE="text" NAME="end_date" CLASS=text STYLE='width: 75px;'>
        ?>

        <BR>
        <BR>
      </TD>
      <TD ALIGN="LEFT" VALIGN="TOP">&nbsp;</TD>
    </TR>
    <TR>
      <TD>
        <INPUT TYPE="radio" NAME="search" VALUE="keywords" id="opt-bykeywords">
        <? echo lang("Search for keywords"); ?>:<BR>
        <INPUT TYPE="text" NAME="keywords" CLASS=text onfocus="document.getElementById('opt-bykeywords').checked=true;" style="width: 250px;margin-left: 25px;"/><br/>
        <div style="margin-left: 25px;">
         <? echo lang("Multiple keywords separated by"); ?>:
         <select name="keyword_splitby" id="keyword_splitby">
          <option value="exact" selected><? echo lang("N/A - Exact phrase match"); ?> (<? echo lang("default"); ?>)</option>
          <option value=" "><? echo lang("Spaces"); ?></option>
          <option value=","><? echo lang("Commas"); ?> (,)</option>
          <option value=";"><? echo lang("Semicolon"); ?> (;)</option>
         </select>
        </div>

      </TD>
      <TD ALIGN="CENTER" VALIGN="TOP">
        <button type="button" class="blueButton" onclick="$('#search-orders-form').submit();"><span><span><?php echo lang("Search Now"); ?></span></span></button>
      </TD>
    </TR>
  </TABLE>

</FORM>
