
<TABLE WIDTH="612" BORDER="0" CELLSPACING="0" CELLPADDING="4" class=text style='border: 1px inset black;' bgcolor=#EFEFEF>
  <TR> 
    <TD ALIGN="LEFT" VALIGN="TOP" WIDTH="50%"><FONT COLOR=RED>*</FONT>User/Company Full Name:<BR>
      <SELECT NAME="N1" CLASS=text><? echo $FIELD_OPTIONS; ?></SELECT>
	  <SELECT NAME="N2" CLASS=text><? echo $FIELD_OPTIONS; ?></SELECT>
    </TD>
    <TD ALIGN="LEFT" VALIGN="TOP"><FONT COLOR=RED>*</FONT>User/Company Email Address:<BR>
      <SELECT NAME="EMAIL" CLASS=text><? echo $FIELD_OPTIONS; ?></SELECT>
      <BR>
      <B><FONT FACE="Arial, Helvetica, sans-serif" SIZE="1" COLOR="#999999">(If 
      the email is not provided, this user will not be able to utilize the &quot;forgot 
      my password&quot; function)</FONT></B> </TD>
  </TR>
  <TR> 
    <TD ALIGN="LEFT" VALIGN="TOP"><FONT COLOR=RED>*</FONT>Assigned Username:<BR>
       <SELECT NAME="USERN" CLASS=text><? echo $FIELD_OPTIONS; ?></SELECT>
    </TD>
    <TD ALIGN="LEFT" VALIGN="TOP"><FONT COLOR=RED>*</FONT>Assigned Password:<BR>
       <SELECT NAME="PASSW" CLASS=text><? echo $FIELD_OPTIONS; ?></SELECT>
    </TD>
  </TR>


  <TR> 


    <TD ALIGN="LEFT" VALIGN="TOP">Expiration Date:<BR>

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



    <TD ALIGN="LEFT" VALIGN="TOP"><FONT COLOR=RED>*</FONT>Login Redirect Page:<BR>

	<!-- BUILD CURRENT SITE PAGES SELECTION --------------------------------- -->

      <SELECT NAME="REDIRECT_PAGE" class=text style='width: 250px;'>

	      <OPTION VALUE="">---------------------------[ Site Pages ]</OPTION>

		<?

			$result = mysql_query("SELECT page_name FROM site_pages ORDER BY page_name");
			while ($PAGES = mysql_fetch_array($result)) {
				$link = eregi_replace(" ", "_", $PAGES[page_name]);
				if ($link == "Home") { $SEL = "SELECTED"; } else { $SEL = ""; }
				echo "            <OPTION VALUE=\"".pagename($link)."\" $SEL>$PAGES[page_name]</OPTION>\n";
			}

		?>

	      <OPTION VALUE="">---------------------------[ Site Modules ]</OPTION>

	      <OPTION VALUE="shopping/start.php?browse=1">Shopping Cart</OPTION>
	      <OPTION VALUE="calendar/start.php?browse=1">Calendar View</OPTION>

      </SELECT>


      <BR>
      <B><FONT FACE="Arial, Helvetica, sans-serif" SIZE="1" COLOR="#999999">(Once 
      this user logs in and is authorized, what site page do you want to send 
      him/her directly to?)</FONT></B> </TD>
  </TR>

  <TR> 
    <TD BGCOLOR="darkblue" COLSPAN="2"><FONT COLOR="#FFFFFF"><B>Select the security codes [groups] this user should have access to:</FONT></TD>
  </TR>

	<!-- BUILD SECURITY CODE [GROUPS] SELECTIONS --------------------------------- -->

	<?

		$result = mysql_query("SELECT security_code FROM sec_codes ORDER BY security_code");
		$num_groups = mysql_num_rows($result); 

		if ($num_groups <= 0) {

			echo "<TR>\n";
			echo "<TD ALIGN=CENTER COLSPAN=2 CLASS=text>\n";

			echo "<BR><CENTER><B><FONT COLOR=RED>There are currently no security codes [groups] created!<BR><BR>In order to assign an authorized user, you need \n";
			echo "to first create at least one<BR>security code [group] that this user can be associated with for login.</FONT></B></CENTER>\n";

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

