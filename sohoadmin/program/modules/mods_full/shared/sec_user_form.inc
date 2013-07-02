
<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="4" class="feature_sub">
  <TR> 
    <TD ALIGN="LEFT" VALIGN="TOP" WIDTH="50%"><FONT COLOR=RED>*</FONT><? echo $lang["User/Company Full Name"]; ?>:<BR>
      <INPUT TYPE="text" NAME="OWNER_NAME" class=text style='width: 250px;' VALUE="<? echo $OWNER_NAME; ?>">
    </TD>
    <TD ALIGN="LEFT" VALIGN="TOP"><FONT COLOR=RED>*</FONT><? echo $lang["User/Company Email Address"]; ?>:<BR>
      <INPUT TYPE="text" id="OWNER_EMAIL" NAME="OWNER_EMAIL" class=text style='width: 250px;' VALUE="<? echo $OWNER_EMAIL; ?>">
      <BR>
      <B><FONT FACE="Arial, Helvetica, sans-serif" SIZE="1" COLOR="#999999">(If 
      the email is not provided, this user will not be able to utilize the &quot;forgot 
      my password&quot; function)</FONT></B> </TD>
  </TR>
  <TR> 
    <TD ALIGN="LEFT" VALIGN="TOP"><FONT COLOR=RED>*</FONT><? echo $lang["Assigned Username"]; ?>:<BR>
      <INPUT TYPE="text" NAME="USERNAME" class=text style='width: 250px;' VALUE="<? echo $USERNAME; ?>">
    </TD>
    <TD ALIGN="LEFT" VALIGN="TOP"><FONT COLOR=RED>*</FONT><? echo $lang["Assigned Password"]; ?>:<BR>
      <INPUT TYPE="text" NAME="PASSWORD" class=text style='width: 250px;' VALUE="<? echo $PASSWORD; ?>">
    </TD>
  </TR>


  <TR> 


    <TD ALIGN="LEFT" VALIGN="TOP"><? echo $lang["Expiration Date"]; ?>:<BR>

	<!-- BUILD EXPIRATION MONTH SELECTION ------------------------------------- -->

      <SELECT NAME="EXP_MONTH" class=text>

		<?
	
			echo "\n";
			echo "            <OPTION VALUE=\"00\">N/A</OPTION>\n";

			for ($x=1;$x<=12;$x++) {

				$n_month = date("M", mktime (0,0,0,$x,1,2002));
				$d_month = date("m", mktime (0,0,0,$x,1,2002));
				echo "            <OPTION VALUE=\"$d_month\">$n_month</OPTION>\n";

			}
		?>

      </SELECT>

	<!-- BUILD EXPIRATION DAY SELECTION ------------------------------------- -->

      <SELECT NAME="EXP_DAY" class=text>

		<?
			echo "\n";
			echo "            <OPTION VALUE=\"00\">N/A</OPTION>\n";

			for ($x=1;$x<=31;$x++) {

				$day = date("d", mktime (0,0,0,1,$x,2002));
				echo "            <OPTION VALUE=\"$day\">$day</OPTION>\n";

			}
		?>

      </SELECT>

	<!-- BUILD EXPIRATION YEAR SELECTION ------------------------------------- -->

      <SELECT NAME="EXP_YEAR" class=text>

		<?
			echo "\n";
			echo "            <OPTION VALUE=\"0000\">N/A</OPTION>\n";

			$this_year = date("Y");
			$max_year = $this_year + 10;

			for ($x=$this_year;$x<=$max_year;$x++) {
				echo "            <OPTION VALUE=\"$x\">$x</OPTION>\n";
			}
		?>

      </SELECT>


    </TD>



    <TD ALIGN="LEFT" VALIGN="TOP"><FONT COLOR=RED>*</FONT><? echo $lang["Login Redirect Page"]; ?>:<BR>

	<!-- BUILD CURRENT SITE PAGES SELECTION --------------------------------- -->

      <SELECT NAME="REDIRECT_PAGE" class=text style='width: 250px;'>

	      <OPTION VALUE="">---------------------------[ Site Pages ]</OPTION>

		<?php


			$result = mysql_query("SELECT page_name, url_name, link FROM site_pages ORDER BY page_name ASC");
			while ($PAGES = mysql_fetch_array($result)) {
				if(!preg_match('/^http:/i', $row['link'])){				
					$link = eregi_replace(" ", "_", $PAGES[page_name]);
					if ($link == "Home") { $SEL = "SELECTED"; } else { $SEL = ""; }
					echo "            <OPTION VALUE=\"".pagename($link)."\" $SEL>".$PAGES['page_name']."</OPTION>\n";
				}
			}

		?>

	      <OPTION VALUE="shopping/start.php?browse=1"><? echo $lang["(Module) Shopping Cart"]; ?></OPTION>


      </SELECT>


      <BR>
      <B><FONT FACE="Arial, Helvetica, sans-serif" SIZE="1" COLOR="#999999">
      (<? echo $lang["What site page should this user be sent to upon login?"]; ?>)
      </FONT></B> </TD>
  </TR>

  <TR> 
    <TD COLSPAN="2" class="fsub_title"><? echo $lang["Select the security codes (groups) this user should have access to"]; ?>:</TD>
  </TR>

	<!-- BUILD SECURITY CODE [GROUPS] SELECTIONS --------------------------------- -->

	<?

		$result = mysql_query("SELECT security_code FROM sec_codes ORDER BY security_code");
		$num_groups = mysql_num_rows($result); 

		if ($num_groups <= 0) {

			echo "<TR>\n";
			echo "<TD ALIGN=CENTER COLSPAN=2 CLASS=text>\n";

			echo "<BR><CENTER><B><FONT COLOR=RED>".$lang["There are currently no security codes (groups) created"]."!<BR><BR>\n";
			echo $lang["All authorized users must be associated with a security group."]."\n";
			echo "</FONT></B></CENTER>\n";

			echo "</TD>\n";
			echo "</TR>\n";

		} else {


			$group_num = -1;		// $x MUST START WITH NEGATIVE ONE (-1) FOR EDIT FEATURE TO WORK CORRECTLY
			$OPTIONS = "";

			while($GROUP = mysql_fetch_array($result)) {
				$OPTIONS .= "           <OPTION VALUE=\"$GROUP[security_code]\">$GROUP[security_code]</OPTION>\n";
			}

			for ($x=1;$x<=5;$x++) {			// This number can be as many as you would like, all codes are compiled into a BLOB field for easy reference
				$group_num++;			// Increment the counter once
				echo "<TR>\n";
				echo "<TD ALIGN=CENTER>\n";
				echo "      <SELECT NAME=\"SEC_GROUP$group_num\" class=text style='width: 150px;'>\n";
				echo "           <OPTION VALUE=\"\">N/A</OPTION>\n";

				echo $OPTIONS;

				echo "      </SELECT>\n";

				echo "</TD>\n";
				echo "<TD ALIGN=CENTER>\n";

				$group_num++;			// Increment the counter once

				echo "      <SELECT NAME=\"SEC_GROUP$group_num\" class=text style='width: 150px;'>\n";
				echo "           <OPTION VALUE=\"\">N/A</OPTION>\n";

				echo $OPTIONS;

				echo "      </SELECT>\n";
				echo "</TD>\n";
				echo "</TR>\n";

			} // End $x Loop

		} // End if groups exist check

	?>


  <TR>
    <TD ALIGN="CENTER">&nbsp;</TD>
    <TD ALIGN="CENTER">&nbsp;</TD>
  </TR>
</TABLE>

