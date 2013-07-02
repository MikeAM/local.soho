
<SCRIPT LANGUAGE=Javascript>

function same_info() {

	document.CHECKOUT1.SFIRSTNAME.value = document.CHECKOUT1.BFIRSTNAME.value;
	document.CHECKOUT1.SLASTNAME.value = document.CHECKOUT1.BLASTNAME.value;	
	document.CHECKOUT1.SCOMPANY.value = document.CHECKOUT1.BCOMPANY.value;
	document.CHECKOUT1.SADDRESS1.value = document.CHECKOUT1.BADDRESS1.value;
	document.CHECKOUT1.SADDRESS2.value = document.CHECKOUT1.BADDRESS2.value;
	document.CHECKOUT1.SCITY.value = document.CHECKOUT1.BCITY.value;
	document.CHECKOUT1.SSTATE.value = document.CHECKOUT1.BSTATE.value;
	document.CHECKOUT1.SZIPCODE.value = document.CHECKOUT1.BZIPCODE.value;

	document.CHECKOUT1.SPHONE_AREACODE.value = document.CHECKOUT1.BPHONE_AREACODE.value;
	document.CHECKOUT1.SPHONE_PREFIX.value = document.CHECKOUT1.BPHONE_PREFIX.value;
	document.CHECKOUT1.SPHONE_SUFFIX.value = document.CHECKOUT1.BPHONE_SUFFIX.value;

	document.CHECKOUT1.SCOUNTRY.value = document.CHECKOUT1.BCOUNTRY.value;
	document.CHECKOUT1.SPHONE_COUNTRYCODE.value = document.CHECKOUT1.BPHONE_COUNTRYCODE.value;

}

function cancel_user() {
	window.location = 'security.php?<?=SID?>';
}

</SCRIPT>

<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="5" class="feature_sub">
  <TR>    
    <TD COLSPAN=2 ALIGN="CENTER" VALIGN="MIDDLE" BGCOLOR=#999999>
	<FONT COLOR=WHITE><? echo $lang["(Optional) If you wish for this user to be remembered automatically when using the<BR>shopping cart system, please fill out all the customer data below."]; ?>
    </TD>
  </TR>
  <TR> 
    <TD ALIGN="LEFT" WIDTH="100%" VALIGN="TOP" COLSPAN="2" class="fsub_title">
     <? echo $lang["Billing Information"]; ?>
    </TD>
  </TR>
  <TR> 
    <TD ALIGN="LEFT" WIDTH="50%" VALIGN="TOP">
    <B><FONT COLOR="#FF0000">*</FONT><? echo $lang["First Name"]; ?></B>:<BR>
      <INPUT TYPE="text" NAME="BFIRSTNAME" SIZE="25" STYLE='font-family: Arial; font-size: 9pt; width: 275px;' VALUE="<? echo $BFIRSTNAME; ?>">
    </TD>
    <TD ALIGN="LEFT" WIDTH="50%" VALIGN="TOP">
     <B><FONT COLOR="#FF0000">*</FONT><? echo $lang["Last Name"]; ?></B>:<BR>
      <INPUT TYPE="text" NAME="BLASTNAME" SIZE="25" STYLE='font-family: Arial; font-size: 9pt; width: 275px;' VALUE="<? echo $BLASTNAME; ?>">
    </TD>
  </TR>
  <TR> 
    <TD ALIGN="LEFT" VALIGN="TOP"><B><? echo $lang["Company Name"]; ?></B> (<? echo $lang["Optional"]; ?>):<BR>
      <INPUT TYPE="text" NAME="BCOMPANY" SIZE="25" STYLE='font-family: Arial; font-size: 9pt; width: 275px;' VALUE="<? echo $BCOMPANY; ?>">
    </TD>
    <TD ALIGN="LEFT" VALIGN="TOP"><B><FONT COLOR="#FF0000">*</FONT><? echo $lang["Address"]; ?></B>: (<? echo $lang["No PO Boxes"]; ?>)<BR>
      <INPUT TYPE="text" NAME="BADDRESS1" SIZE="25" STYLE='font-family: Arial; font-size: 9pt; width: 275px;' VALUE="<? echo $BADDRESS1; ?>">
      <BR>
      <INPUT TYPE="text" NAME="BADDRESS2" SIZE="25" STYLE='font-family: Arial; font-size: 9pt; width: 275px;' VALUE="<? echo $BADDRESS2; ?>">
    </TD>
  </TR>
  <TR> 
    <TD ALIGN="LEFT" VALIGN="TOP"><B><FONT COLOR="#FF0000">*</FONT><? echo $lang["City/Town/Locality"]; ?></B>:<BR>
      <INPUT TYPE="text" NAME="BCITY" SIZE="25" STYLE='font-family: Arial; font-size: 9pt; width: 275px;' VALUE="<? echo $BCITY; ?>">
    </TD>
    <TD ALIGN="LEFT" VALIGN="TOP"> 
      <P><B><FONT COLOR="#FF0000">*</FONT><? echo $lang["Region or Province/State/District"]; ?></B>:<BR>
        <INPUT TYPE="text" NAME="BSTATE" SIZE="25" STYLE='font-family: Arial; font-size: 9pt; width: 275px;' VALUE="<? echo $BSTATE; ?>">
      </P>
    </TD>
  </TR>
  <TR> 
    <TD ALIGN="LEFT" VALIGN="TOP"><B><FONT COLOR="#FF0000">*</FONT><? echo $lang["Country"]; ?></B>:<BR>
      <SELECT NAME="BCOUNTRY" STYLE='font-family: Arial; font-size: 9pt; width: 275px;'>
        <OPTION VALUE="United States" SELECTED>United States 
        <OPTION VALUE="Albania">Albania 
        <OPTION VALUE="Algeria">Algeria 
        <OPTION VALUE="American Samoa">American Samoa 
        <OPTION VALUE="Andorra">Andorra 
        <OPTION VALUE="Anguilla">Anguilla 
        <OPTION VALUE="Antigua & Barbuda">Antigua & Barbuda 
        <OPTION VALUE="Argentina">Argentina 
        <OPTION VALUE="Aruba">Aruba 
        <OPTION VALUE="Australia">Australia 
        <OPTION VALUE="Austria">Austria 
        <OPTION VALUE="Azores">Azores 
        <OPTION VALUE="Bahamas">Bahamas 
        <OPTION VALUE="Bahrain">Bahrain 
        <OPTION VALUE="Bangladesh">Bangladesh 
        <OPTION VALUE="Barbados">Barbados 
        <OPTION VALUE="Belgium">Belgium 
        <OPTION VALUE="Belize">Belize 
        <OPTION VALUE="Belarus">Belarus 
        <OPTION VALUE="Benin">Benin 
        <OPTION VALUE="Bermuda">Bermuda 
        <OPTION VALUE="Bolivia">Bolivia 
        <OPTION VALUE="Bonaire">Bonaire 
        <OPTION VALUE="Bosnia">Bosnia 
        <OPTION VALUE="Botswana">Botswana 
        <OPTION VALUE="Brazil">Brazil 
        <OPTION VALUE="Brunei">Brunei 
        <OPTION VALUE="Bulgaria">Bulgaria 
        <OPTION VALUE="Burkina Faso">Burkina Faso 
        <OPTION VALUE="Burundi">Burundi 
        <OPTION VALUE="Cambodia">Cambodia 
        <OPTION VALUE="Cameroon">Cameroon 
        <OPTION VALUE="Canada">Canada 
        <OPTION VALUE="Canary Islands">Canary Islands 
        <OPTION VALUE="Cape Verde Islands">Cape Verde Islands 
        <OPTION VALUE="Cayman Islands">Cayman Islands 
        <OPTION VALUE="Central African Republic">Central African Republic 
        <OPTION VALUE="Chad">Chad 
        <OPTION VALUE="Channel Islands">Channel Islands 
        <OPTION VALUE="Chile">Chile 
        <OPTION VALUE="China, Peoples Republic of">China 
        <OPTION VALUE="Colombia">Colombia 
        <OPTION VALUE="Congo">Congo 
        <OPTION VALUE="Cook Islands">Cook Islands 
        <OPTION VALUE="Costa Rica">Costa Rica 
        <OPTION VALUE="Croatia">Croatia 
        <OPTION VALUE="Curacao">Curacao 
        <OPTION VALUE="Cyprus">Cyprus 
        <OPTION VALUE="Czech Republic">Czech Republic 
        <OPTION VALUE="Denmark">Denmark 
        <OPTION VALUE="Djibouti">Djibouti 
        <OPTION VALUE="Dominica">Dominica 
        <OPTION VALUE="Dominican Republic">Dominican Republic 
        <OPTION VALUE="Ecuador">Ecuador 
        <OPTION VALUE="Egypt">Egypt 
        <OPTION VALUE="El Salvador">El Salvador 
        <OPTION VALUE="England">England 
        <OPTION VALUE="Equitorial Guinea">Equitorial Guinea 
        <OPTION VALUE="Eritrea">Eritrea 
        <OPTION VALUE="Estonia">Estonia 
        <OPTION VALUE="Ethiopia">Ethiopia 
        <OPTION VALUE="Faeroe Islands">Faeroe Islands 
        <OPTION VALUE="Fiji">Fiji 
        <OPTION VALUE="Finland">Finland 
        <OPTION VALUE="France">France 
        <OPTION VALUE="French Guiana">French Guiana 
        <OPTION VALUE="French Polynesia">French Polynesia 
        <OPTION VALUE="Gabon">Gabon 
        <OPTION VALUE="Gambia">Gambia 
        <OPTION VALUE="Georgia">Georgia 
        <OPTION VALUE="Germany">Germany 
        <OPTION VALUE="Ghana">Ghana 
        <OPTION VALUE="Gibraltar">Gibraltar 
        <OPTION VALUE="Greece">Greece 
        <OPTION VALUE="Greenland">Greenland 
        <OPTION VALUE="Grenada">Grenada 
        <OPTION VALUE="Guadeloupe">Guadeloupe 
        <OPTION VALUE="Guam">Guam 
        <OPTION VALUE="Guatemala">Guatemala 
        <OPTION VALUE="Guinea">Guinea 
        <OPTION VALUE="Guinea-Bissau">Guinea-Bissau 
        <OPTION VALUE="Guyana">Guyana 
        <OPTION VALUE="Haiti">Haiti 
        <OPTION VALUE="Holland">Holland 
        <OPTION VALUE="Honduras">Honduras 
        <OPTION VALUE="Hong Kong">Hong Kong 
        <OPTION VALUE="Hungary">Hungary 
        <OPTION VALUE="Iceland">Iceland 
        <OPTION VALUE="India">India 
        <OPTION VALUE="Indonesia">Indonesia 
        <OPTION VALUE="Israel">Israel 
        <OPTION VALUE="Italy">Italy 
        <OPTION VALUE="Ivory Coast">Ivory Coast 
        <OPTION VALUE="Jamaica">Jamaica 
        <OPTION VALUE="Japan">Japan 
        <OPTION VALUE="Jordan">Jordan 
        <OPTION VALUE="Kazakhstan">Kazakhstan 
        <OPTION VALUE="Kenya">Kenya 
        <OPTION VALUE="Kiribati">Kiribati 
        <OPTION VALUE="Kosrae">Kosrae 
        <OPTION VALUE="Kuwait">Kuwait 
        <OPTION VALUE="Kyrgyzstan">Kyrgyzstan 
        <OPTION VALUE="Laos">Laos 
        <OPTION VALUE="Latvia">Latvia 
        <OPTION VALUE="Lebanon">Lebanon 
        <OPTION VALUE="Lesotho">Lesotho 
        <OPTION VALUE="Liberia">Liberia 
        <OPTION VALUE="Liechtenstein">Liechtenstein 
        <OPTION VALUE="Lithuania">Lithuania 
        <OPTION VALUE="Luxembourg">Luxembourg 
        <OPTION VALUE="Macau">Macau 
        <OPTION VALUE="Macedonia">Macedonia 
        <OPTION VALUE="Madagascar">Madagascar 
        <OPTION VALUE="Madeira">Madeira 
        <OPTION VALUE="Malawi">Malawi 
        <OPTION VALUE="Malaysia">Malaysia 
        <OPTION VALUE="Maldives">Maldives 
        <OPTION VALUE="Mali">Mali 
        <OPTION VALUE="Malta">Malta 
        <OPTION VALUE="Marshall Islands">Marshall Islands 
        <OPTION VALUE="Martinique">Martinique 
        <OPTION VALUE="Mauritania">Mauritania 
        <OPTION VALUE="Mauritius">Mauritius 
        <OPTION VALUE="Mexico">Mexico 
        <OPTION VALUE="Moldova">Moldova 
        <OPTION VALUE="Monaco">Monaco 
        <OPTION VALUE="Montserrat">Montserrat 
        <OPTION VALUE="Morocco">Morocco 
        <OPTION VALUE="Mozambique">Mozambique 
        <OPTION VALUE="Myanmar">Myanmar 
        <OPTION VALUE="Namibia">Namibia 
        <OPTION VALUE="Nepal">Nepal 
        <OPTION VALUE="Netherlands">Netherlands 
        <OPTION VALUE="New Caledonia">New Caledonia 
        <OPTION VALUE="New Zealand">New Zealand 
        <OPTION VALUE="Nicaragua">Nicaragua 
        <OPTION VALUE="Niger">Niger 
        <OPTION VALUE="Nigeria">Nigeria 
        <OPTION VALUE="Norfolk Island">Norfolk Island 
        <OPTION VALUE="Northern Ireland">Northern Ireland 
        <OPTION VALUE="Norway">Norway 
        <OPTION VALUE="Oman">Oman 
        <OPTION VALUE="Pakistan">Pakistan 
        <OPTION VALUE="Palau">Palau 
        <OPTION VALUE="Panama">Panama 
        <OPTION VALUE="Papua New Guinea">Papua New Guinea 
        <OPTION VALUE="Paraguay">Paraguay 
        <OPTION VALUE="Peru">Peru 
        <OPTION VALUE="Philippines">Philippines 
        <OPTION VALUE="Poland">Poland 
        <OPTION VALUE="Ponape">Ponape 
        <OPTION VALUE="Portugal">Portugal 
        <OPTION VALUE="Puerto Rico">Puerto Rico 
        <OPTION VALUE="Qatar">Qatar 
        <OPTION VALUE="Republic of Ireland">Republic of Ireland 
        <OPTION VALUE="Republic of Yemen">Republic of Yemen 
        <OPTION VALUE="Reunion">Reunion 
        <OPTION VALUE="Romania">Romania 
        <OPTION VALUE="Rota">Rota 
        <OPTION VALUE="Russia">Russia 
        <OPTION VALUE="Rwanda">Rwanda 
        <OPTION VALUE="Saba">Saba 
        <OPTION VALUE="Saipan">Saipan 
        <OPTION VALUE="Saudi Arabia">Saudi Arabia 
        <OPTION VALUE="Scotland">Scotland 
        <OPTION VALUE="Senegal">Senegal 
        <OPTION VALUE="Seychelles">Seychelles 
        <OPTION VALUE="Sierra Leone">Sierra Leone 
        <OPTION VALUE="Singapore">Singapore 
        <OPTION VALUE="Slovakia">Slovakia 
        <OPTION VALUE="Slovenia">Slovenia 
        <OPTION VALUE="Solomon Islands">Solomon Islands 
        <OPTION VALUE="South Africa">South Africa 
        <OPTION VALUE="South Korea">South Korea 
        <OPTION VALUE="Spain">Spain 
        <OPTION VALUE="Sri Lanka">Sri Lanka 
        <OPTION VALUE="St. Barthelemy">St. Barthelemy 
        <OPTION VALUE="St. Christopher">St. Christopher 
        <OPTION VALUE="St. Croix">St. Croix 
        <OPTION VALUE="St. Eustatius">St. Eustatius 
        <OPTION VALUE="St. John">St. John 
        <OPTION VALUE="St. Kitts & Nevis">St. Kitts & Nevis 
        <OPTION VALUE="St. Lucia">St. Lucia 
        <OPTION VALUE="St. Maarten">St. Maarten 
        <OPTION VALUE="St. Martin">St. Martin 
        <OPTION VALUE="St. Thomas">St. Thomas 
        <OPTION VALUE="Sudan">Sudan 
        <OPTION VALUE="Suriname">Suriname 
        <OPTION VALUE="Swaziland">Swaziland 
        <OPTION VALUE="Sweden">Sweden 
        <OPTION VALUE="Switzerland">Switzerland 
        <OPTION VALUE="Syria">Syria 
        <OPTION VALUE="Tahiti">Tahiti 
        <OPTION VALUE="Taiwan">Taiwan 
        <OPTION VALUE="Tajikistan">Tajikistan 
        <OPTION VALUE="Tanzania">Tanzania 
        <OPTION VALUE="Thailand">Thailand 
        <OPTION VALUE="Tinian">Tinian 
        <OPTION VALUE="Togo">Togo 
        <OPTION VALUE="Tonga">Tonga 
        <OPTION VALUE="Tortola">Tortola 
        <OPTION VALUE="Truk">Truk 
        <OPTION VALUE="Trinidad & Tobago">Trinidad & Tobago 
        <OPTION VALUE="Tunisia">Tunisia 
        <OPTION VALUE="Turkey">Turkey 
        <OPTION VALUE="Tuvalu">Tuvalu 
        <OPTION VALUE="Uganda">Uganda 
        <OPTION VALUE="Ukraine">Ukraine 
        <OPTION VALUE="Union Island">Union Island 
        <OPTION VALUE="United Arab Emirates">United Arab Emirates 
        <OPTION VALUE="United Kingdom">United Kingdom 
        <OPTION VALUE="United States">United States 
        <OPTION VALUE="Uruguay">Uruguay 
        <OPTION VALUE="US Virgin Islands">US Virgin Islands 
        <OPTION VALUE="Uzbekistan">Uzbekistan 
        <OPTION VALUE="Vanuatu">Vanuatu 
        <OPTION VALUE="Venezuela">Venezuela 
        <OPTION VALUE="Vietnam">Vietnam 
        <OPTION VALUE="Virgin Islands">Virgin Islands 
        <OPTION VALUE="Virgin Gorda">Virgin Gorda 
        <OPTION VALUE="Wake Island">Wake Island 
        <OPTION VALUE="Wales">Wales 
        <OPTION VALUE="Western Samoa">Western Samoa 
        <OPTION VALUE="Yap">Yap 
        <OPTION VALUE="Yugoslavia">Yugoslavia 
        <OPTION VALUE="Zaire">Zaire 
        <OPTION VALUE="Zambia">Zambia 
        <OPTION VALUE="Zimbabwe">Zimbabwe 
      </SELECT>
    </TD>
    <TD><B><FONT COLOR="#FF0000">*</FONT><? echo $lang["Postal/Zip Code"]; ?></B>:<BR>
      <INPUT TYPE="text" NAME="BZIPCODE" SIZE="25" STYLE='font-family: Arial; font-size: 9pt; width: 275px;' VALUE="<? echo $BZIPCODE; ?>">
    </TD>
  </TR>
  <TR> 
    <TD><B><FONT COLOR="#FF0000">*</FONT><? echo $lang["Home Phone Number"]; ?></B>:<BR>
      <? echo $lang["Country Code"]; ?>: 
      <SELECT NAME="BPHONE_COUNTRYCODE" STYLE='font-family: Arial; font-size: 7pt; width: 200px;'>
        <OPTION VALUE="355" >355 - Albania</OPTION>
        <OPTION VALUE="213" >213 - Algeria</OPTION>
        <OPTION VALUE="684" >684 - American Samoa</OPTION>
        <OPTION VALUE="376" >376 - Andorra</OPTION>
        <OPTION VALUE="244" >244 - Angola</OPTION>
        <OPTION VALUE="809" >809 - Anguilla</OPTION>
        <OPTION VALUE="268" >268 - Antigua</OPTION>
        <OPTION VALUE="54" >54 - Argentina</OPTION>
        <OPTION VALUE="374" >374 - Armenia </OPTION>
        <OPTION VALUE="297" >297 - Aruba</OPTION>
        <OPTION VALUE="247" >247 - Ascension Island</OPTION>
        <OPTION VALUE="61" >61 - Australia</OPTION>
        <OPTION VALUE="672" >672 - Australian External Territories </OPTION>
        <OPTION VALUE="43" >43 - Austria</OPTION>
        <OPTION VALUE="994" >994 - Azerbaijan</OPTION>
        <OPTION VALUE="242" >242 - Bahamas</OPTION>
        <OPTION VALUE="246" >246 - Barbados</OPTION>
        <OPTION VALUE="973" >973 - Bahrain</OPTION>
        <OPTION VALUE="880" >880 - Bangladesh</OPTION>
        <OPTION VALUE="375" >375 - Belarus</OPTION>
        <OPTION VALUE="32" >32 - Belgium</OPTION>
        <OPTION VALUE="501" >501 - Belize</OPTION>
        <OPTION VALUE="229" >229 - Benin</OPTION>
        <OPTION VALUE="809" >809 - Bermuda</OPTION>
        <OPTION VALUE="975" >975 - Bhutan</OPTION>
        <OPTION VALUE="284" >284 - British Virgin Islands</OPTION>
        <OPTION VALUE="591" >591 - Bolivia</OPTION>
        <OPTION VALUE="387" >387 - Bosnia and Hercegovina</OPTION>
        <OPTION VALUE="267" >267 - Botswana</OPTION>
        <OPTION VALUE="55" >55 - Brazil</OPTION>
        <OPTION VALUE="284" >284 - British V.I.</OPTION>
        <OPTION VALUE="673" >673 - Brunei Darussalm</OPTION>
        <OPTION VALUE="359" >359 - Bulgaria</OPTION>
        <OPTION VALUE="226" >226 - Burkina Faso</OPTION>
        <OPTION VALUE="257" >257 - Burundi</OPTION>
        <OPTION VALUE="855" >855 - Cambodia</OPTION>
        <OPTION VALUE="237" >237 - Cameroon</OPTION>
        <OPTION VALUE="1" >1 - Canada</OPTION>
        <OPTION VALUE="238" >238 - Capr Verde Islands</OPTION>
        <OPTION VALUE="1" >1 - Caribbean Nations</OPTION>
        <OPTION VALUE="345" >345 - Cayman Islands</OPTION>
        <OPTION VALUE="238" >238 - Cape Verdi</OPTION>
        <OPTION VALUE="236" >236 - Central African Republic</OPTION>
        <OPTION VALUE="235" >235 - Chad</OPTION>
        <OPTION VALUE="56" >56 - Chile</OPTION>
        <OPTION VALUE="86" >86 - China (People's Republic)</OPTION>
        <OPTION VALUE="886" >886 - China-Taiwan</OPTION>
        <OPTION VALUE="57" >57 - Colombia</OPTION>
        <OPTION VALUE="269" >269 - Comoros and Mayotte</OPTION>
        <OPTION VALUE="242" >242 - Congo</OPTION>
        <OPTION VALUE="682" >682 - Cook Islands</OPTION>
        <OPTION VALUE="506" >506 - Costa Rica</OPTION>
        <OPTION VALUE="385" >385 - Croatia</OPTION>
        <OPTION VALUE="53" >53 - Cuba</OPTION>
        <OPTION VALUE="357" >357 - Cyprus</OPTION>
        <OPTION VALUE="420" >420 - Czech Republic</OPTION>
        <OPTION VALUE="45" >45 - Denmark</OPTION>
        <OPTION VALUE="246" >246 - Diego Garcia</OPTION>
        <OPTION VALUE="767" >767 - Dominca</OPTION>
        <OPTION VALUE="809" >809 - Dominican Republic</OPTION>
        <OPTION VALUE="253" >253 - Djibouti</OPTION>
        <OPTION VALUE="593" >593 - Ecuador</OPTION>
        <OPTION VALUE="20" >20 - Egypt</OPTION>
        <OPTION VALUE="503" >503 - El Salvador</OPTION>
        <OPTION VALUE="240" >240 - Equatorial Guinea</OPTION>
        <OPTION VALUE="291" >291 - Eritrea</OPTION>
        <OPTION VALUE="372" >372 - Estonia</OPTION>
        <OPTION VALUE="251" >251 - Ethiopia</OPTION>
        <OPTION VALUE="500" >500 - Falkland Islands</OPTION>
        <OPTION VALUE="298" >298 - Faroe (Faeroe) Islands (Denmark)</OPTION>
        <OPTION VALUE="679" >679 - Fiji</OPTION>
        <OPTION VALUE="358" >358 - Finland</OPTION>
        <OPTION VALUE="33" >33 - France</OPTION>
        <OPTION VALUE="596" >596 - French Antilles </OPTION>
        <OPTION VALUE="594" >594 - French Guiana</OPTION>
        <OPTION VALUE="241" >241 - Gabon (Gabonese Republic)</OPTION>
        <OPTION VALUE="220" >220 - Gambia</OPTION>
        <OPTION VALUE="995" >995 - Georgia</OPTION>
        <OPTION VALUE="49" >49 - Germany</OPTION>
        <OPTION VALUE="233" >233 - Ghana</OPTION>
        <OPTION VALUE="350" >350 - Gibraltar</OPTION>
        <OPTION VALUE="30" >30 - Greece</OPTION>
        <OPTION VALUE="299" >299 - Greenland</OPTION>
        <OPTION VALUE="473" >473 - Grenada/Carricou</OPTION>
        <OPTION VALUE="671" >671 - Guam</OPTION>
        <OPTION VALUE="502" >502 - Guatemala</OPTION>
        <OPTION VALUE="224" >224 - Guinea</OPTION>
        <OPTION VALUE="245" >245 - Guinea-Bissau</OPTION>
        <OPTION VALUE="592" >592 - Guyana</OPTION>
        <OPTION VALUE="509" >509 - Haiti</OPTION>
        <OPTION VALUE="504" >504 - Honduras</OPTION>
        <OPTION VALUE="852" >852 - Hong Kong</OPTION>
        <OPTION VALUE="36" >36 - Hungary</OPTION>
        <OPTION VALUE="354" >354 - Iceland</OPTION>
        <OPTION VALUE="91" >91 - India</OPTION>
        <OPTION VALUE="62" >62 - Indonesia</OPTION>
        <OPTION VALUE="98" >98 - Iran</OPTION>
        <OPTION VALUE="964" >964 - Iraq</OPTION>
        <OPTION VALUE="353" >353 - Ireland (Irish Republic; Eire)</OPTION>
        <OPTION VALUE="972" >972 - Israel</OPTION>
        <OPTION VALUE="39" >39 - Italy</OPTION>
        <OPTION VALUE="225" >225 - Ivory Coast (La Cote d'Ivoire)</OPTION>
        <OPTION VALUE="876" >876 - Jamaica</OPTION>
        <OPTION VALUE="81" >81 - Japan</OPTION>
        <OPTION VALUE="962" >962 - Jordan</OPTION>
        <OPTION VALUE="7" >7 - Kazakhstan</OPTION>
        <OPTION VALUE="254" >254 - Kenya</OPTION>
        <OPTION VALUE="855" >855 - Khmer Republic (Cambodia/Kampuchea)</OPTION>
        <OPTION VALUE="686" >686 - Kiribati Republic (Gilbert Islands)</OPTION>
        <OPTION VALUE="82" >82 - Korea Republic of (South Korea)</OPTION>
        <OPTION VALUE="850" >850 - Korea People's Republic of (North Korea)</OPTION>
        <OPTION VALUE="965" >965 - Kuwait</OPTION>
        <OPTION VALUE="996" >996 - Kyrgyz Republic</OPTION>
        <OPTION VALUE="371" >371 - Latvia</OPTION>
        <OPTION VALUE="856" >856 - Laos</OPTION>
        <OPTION VALUE="961" >961 - Lebanon</OPTION>
        <OPTION VALUE="266" >266 - Lesotho</OPTION>
        <OPTION VALUE="231" >231 - Liberia</OPTION>
        <OPTION VALUE="370" >370 - Lithuania</OPTION>
        <OPTION VALUE="218" >218 - Libya</OPTION>
        <OPTION VALUE="423" >423 - Liechtenstein</OPTION>
        <OPTION VALUE="352" >352 - Luxembourg</OPTION>
        <OPTION VALUE="853" >853 - Macao</OPTION>
        <OPTION VALUE="389" >389 - Macedonia</OPTION>
        <OPTION VALUE="261" >261 - Madagascar</OPTION>
        <OPTION VALUE="265" >265 - Malawi</OPTION>
        <OPTION VALUE="60" >60 - Malaysia</OPTION>
        <OPTION VALUE="960" >960 - Maldives</OPTION>
        <OPTION VALUE="223" >223 - Mali</OPTION>
        <OPTION VALUE="356" >356 - Malta</OPTION>
        <OPTION VALUE="692" >692 - Marshall Islands</OPTION>
        <OPTION VALUE="596" >596 - Martinique (French Antilles)</OPTION>
        <OPTION VALUE="222" >222 - Mauritania</OPTION>
        <OPTION VALUE="230" >230 - Mauritius</OPTION>
        <OPTION VALUE="269" >269 - Mayolte</OPTION>
        <OPTION VALUE="52" >52 - Mexico</OPTION>
        <OPTION VALUE="691" >691 - Micronesia (F.S. of Polynesia)</OPTION>
        <OPTION VALUE="373" >373 - Moldova</OPTION>
        <OPTION VALUE="33" >33 - Monaco</OPTION>
        <OPTION VALUE="976" >976 - Mongolia</OPTION>
        <OPTION VALUE="473" >473 - Montserrat</OPTION>
        <OPTION VALUE="212" >212 - Morocco</OPTION>
        <OPTION VALUE="258" >258 - Mozambique</OPTION>
        <OPTION VALUE="95" >95 - Myanmar (former Burma)</OPTION>
        <OPTION VALUE="264" >264 - Namibia (former South-West Africa)</OPTION>
        <OPTION VALUE="674" >674 - Nauru</OPTION>
        <OPTION VALUE="977" >977 - Nepal</OPTION>
        <OPTION VALUE="31" >31 - Netherlands</OPTION>
        <OPTION VALUE="599" >599 - Netherlands Antilles</OPTION>
        <OPTION VALUE="869" >869 - Nevis</OPTION>
        <OPTION VALUE="687" >687 - New Caledonia</OPTION>
        <OPTION VALUE="64" >64 - New Zealand</OPTION>
        <OPTION VALUE="505" >505 - Nicaragua</OPTION>
        <OPTION VALUE="227" >227 - Niger</OPTION>
        <OPTION VALUE="234" >234 - Nigeria</OPTION>
        <OPTION VALUE="683" >683 - Niue</OPTION>
        <OPTION VALUE="850" >850 - North Korea</OPTION>
        <OPTION VALUE="1 670" >1 670 - North Mariana Islands (Saipan)</OPTION>
        <OPTION VALUE="47" >47 - Norway</OPTION>
        <OPTION VALUE="968" >968 - Oman</OPTION>
        <OPTION VALUE="92" >92 - Pakistan</OPTION>
        <OPTION VALUE="680" >680 - Palau</OPTION>
        <OPTION VALUE="507" >507 - Panama</OPTION>
        <OPTION VALUE="675" >675 - Papua New Guinea</OPTION>
        <OPTION VALUE="595" >595 - Paraguay</OPTION>
        <OPTION VALUE="51" >51 - Peru</OPTION>
        <OPTION VALUE="63" >63 - Philippines</OPTION>
        <OPTION VALUE="48" >48 - Poland</OPTION>
        <OPTION VALUE="351" >351 - Portugal (includes Azores)</OPTION>
        <OPTION VALUE="1 787" >1 787 - Puerto Rico</OPTION>
        <OPTION VALUE="974" >974 - Qatar</OPTION>
        <OPTION VALUE="262" >262 - Reunion (France)</OPTION>
        <OPTION VALUE="40" >40 - Romania</OPTION>
        <OPTION VALUE="7" >7 - Russia</OPTION>
        <OPTION VALUE="250" >250 - Rwanda (Rwandese Republic)</OPTION>
        <OPTION VALUE="670" >670 - Saipan</OPTION>
        <OPTION VALUE="378" >378 - San Marino</OPTION>
        <OPTION VALUE="239" >239 - Sao Tome and Principe</OPTION>
        <OPTION VALUE="966" >966 - Saudi Arabia</OPTION>
        <OPTION VALUE="221" >221 - Senegal</OPTION>
        <OPTION VALUE="381" >381 - Serbia and Montenegro</OPTION>
        <OPTION VALUE="248" >248 - Seychelles</OPTION>
        <OPTION VALUE="232" >232 - Sierra Leone</OPTION>
        <OPTION VALUE="65" >65 - Singapore</OPTION>
        <OPTION VALUE="421" >421 - Slovakia </OPTION>
        <OPTION VALUE="386" >386 - Slovenia</OPTION>
        <OPTION VALUE="677" >677 - Solomon Islands</OPTION>
        <OPTION VALUE="252" >252 - Somalia</OPTION>
        <OPTION VALUE="27" >27 - South Africa</OPTION>
        <OPTION VALUE="34" >34 - Spain</OPTION>
        <OPTION VALUE="94" >94 - Sri Lanka</OPTION>
        <OPTION VALUE="290" >290 - St. Helena</OPTION>
        <OPTION VALUE="869" >869 - St. Kitts/Nevis</OPTION>
        <OPTION VALUE="508" >508 - St. Pierre &(et) Miquelon (France)</OPTION>
        <OPTION VALUE="249" >249 - Sudan</OPTION>
        <OPTION VALUE="597" >597 - Suriname</OPTION>
        <OPTION VALUE="268" >268 - Swaziland</OPTION>
        <OPTION VALUE="46" >46 - Sweden</OPTION>
        <OPTION VALUE="41" >41 - Switzerland</OPTION>
        <OPTION VALUE="963" >963 - Syrian Arab Republic (Syria)</OPTION>
        <OPTION VALUE="689" >689 - Tahiti (French Polynesia)</OPTION>
        <OPTION VALUE="886" >886 - Taiwan</OPTION>
        <OPTION VALUE="7" >7 - Tajikistan</OPTION>
        <OPTION VALUE="255" >255 - Tanzania (includes Zanzibar)</OPTION>
        <OPTION VALUE="66" >66 - Thailand</OPTION>
        <OPTION VALUE="228" >228 - Togo (Togolese Republic)</OPTION>
        <OPTION VALUE="690" >690 - Tokelau</OPTION>
        <OPTION VALUE="676" >676 - Tonga</OPTION>
        <OPTION VALUE="1 868" >1 868 - Trinidad and Tobago</OPTION>
        <OPTION VALUE="216" >216 - Tunisia</OPTION>
        <OPTION VALUE="90" >90 - Turkey</OPTION>
        <OPTION VALUE="993" >993 - Turkmenistan</OPTION>
        <OPTION VALUE="688" >688 - Tuvalu (Ellice Islands)</OPTION>
        <OPTION VALUE="256" >256 - Uganda</OPTION>
        <OPTION VALUE="380" >380 - Ukraine</OPTION>
        <OPTION VALUE="971" >971 - United Arab Emirates</OPTION>
        <OPTION VALUE="44" >44 - United Kingdom</OPTION>
        <OPTION VALUE="598" >598 - Uruguay</OPTION>
        <OPTION VALUE="" SELECTED>USA</OPTION>
        <OPTION VALUE="7" >7 - Uzbekistan</OPTION>
        <OPTION VALUE="678" >678 - Vanuatu (New Hebrides)</OPTION>
        <OPTION VALUE="39" >39 - Vatican City</OPTION>
        <OPTION VALUE="58" >58 - Venezuela</OPTION>
        <OPTION VALUE="84" >84 - Viet Nam</OPTION>
        <OPTION VALUE="1 340" >1 340 - Virgin Islands</OPTION>
        <OPTION VALUE="681" >681 - Wallis and Futuna</OPTION>
        <OPTION VALUE="685" >685 - Western Samoa</OPTION>
        <OPTION VALUE="381" >381 - Yemen</OPTION>
        <OPTION VALUE="967" >967 - Yemen Arab Republic (North Yemen)</OPTION>
        <OPTION VALUE="243" >243 - Zaire</OPTION>
        <OPTION VALUE="260" >260 - Zambia</OPTION>
        <OPTION VALUE="263" >263 - Zimbabwe</OPTION>
      </SELECT>
      <BR>
      ( 
      <INPUT TYPE="text" NAME="BPHONE_AREACODE" SIZE="3" MAXLENGTH="3" VALUE="<? echo $BPHONE_AREACODE; ?>">
      ) 
      <INPUT TYPE="text" NAME="BPHONE_PREFIX" SIZE="3" MAXLENGTH="3" VALUE="<? echo $BPHONE_PREFIX; ?>">
      - 
      <INPUT TYPE="text" NAME="BPHONE_SUFFIX" SIZE="4" MAXLENGTH="4" VALUE="<? echo $BPHONE_SUFFIX; ?>">
    </TD>
    <TD ALIGN="LEFT" VALIGN="TOP"><B><FONT COLOR="#FF0000">*</FONT><? echo $lang["Email Address"]; ?></B>:<BR>
      <INPUT TYPE="text" NAME="BEMAILADDRESS" SIZE="25" STYLE='font-family: Arial; font-size: 9pt; width: 275px;' VALUE="<? echo $BEMAILADDRESS; ?>">
      <BR>

	<?

		if ($email_err == 1) {
			echo "<font color=red>*".$lang["INVALID EMAIL ADDRESS"]."</font><BR>";
		}

	?>
    </TD>
  </TR>
  <TR> 
    <TD>&nbsp;</TD>
    <TD>&nbsp;</TD>
  </TR>
  <TR> 
    <TD COLSPAN=2 CLASS="fsub_title">

	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100%>
	 <TR>
	  <TD style="width:140px;" ALIGN=LEFT VALIGN=TOP >
	   <? echo $lang["Shipping Information"]; ?>
	  </TD>
	<TD ALIGN=left VALIGN=MIDDLE>
	<button TYPE=BUTTON onclick="same_info();" class="blueButton"><span><span>Copy Billing Data to Shipping Data</span></span></button>
	</TD></TR></TABLE>

    </TD>
  </TR>

  <TR> 
    <TD ALIGN="LEFT" WIDTH="50%" VALIGN="TOP">&nbsp;</TD>
    <TD ALIGN="LEFT" WIDTH="50%" VALIGN="TOP">&nbsp;</TD>
  </TR>
  <TR> 
    <TD ALIGN="LEFT" WIDTH="50%" VALIGN="TOP"><B><FONT COLOR="#FF0000">*</FONT><? echo $lang["First Name"]; ?></B>:<BR>
      <INPUT TYPE="text" NAME="SFIRSTNAME" SIZE="25" STYLE='font-family: Arial; font-size: 9pt; width: 275px;' VALUE="<? echo $SFIRSTNAME; ?>">
    </TD>
    <TD ALIGN="LEFT" WIDTH="50%" VALIGN="TOP"><B><FONT COLOR="#FF0000">*</FONT><? echo $lang["Last Name"]; ?></B>:<BR>
      <INPUT TYPE="text" NAME="SLASTNAME" SIZE="25" STYLE='font-family: Arial; font-size: 9pt; width: 275px;' VALUE="<? echo $SLASTNAME; ?>">
    </TD>
  </TR>
  <TR> 
    <TD ALIGN="LEFT" VALIGN="TOP"><B><? echo $lang["Company Name"]; ?></B> (<? echo $lang["Optional"]; ?>):<BR>
      <INPUT TYPE="text" NAME="SCOMPANY" SIZE="25" STYLE='font-family: Arial; font-size: 9pt; width: 275px;' VALUE="<? echo $SCOMPANY; ?>">
    </TD>
    <TD ALIGN="LEFT" VALIGN="TOP"><B><FONT COLOR="#FF0000">*</FONT><? echo $lang["Address"]; ?></B>: 
      (<? echo $lang["No PO Boxes"]; ?>)<BR>
      <INPUT TYPE="text" NAME="SADDRESS1" SIZE="25" STYLE='font-family: Arial; font-size: 9pt; width: 275px;' VALUE="<? echo $SADDRESS1; ?>">
      <BR>
      <INPUT TYPE="text" NAME="SADDRESS2" SIZE="25" STYLE='font-family: Arial; font-size: 9pt; width: 275px;' VALUE="<? echo $SADDRESS2; ?>">
    </TD>
  </TR>
  <TR> 
    <TD ALIGN="LEFT" VALIGN="TOP"><B><FONT COLOR="#FF0000">*</FONT><? echo $lang["City/Town/Locality"]; ?></B>:<BR>
      <INPUT TYPE="text" NAME="SCITY" SIZE="25" STYLE='font-family: Arial; font-size: 9pt; width: 275px;' VALUE="<? echo $SCITY; ?>">
    </TD>
    <TD ALIGN="LEFT" VALIGN="TOP"> 
      <P><B><FONT COLOR="#FF0000">*</FONT><? echo $lang["Region or Province/State/District"]; ?></B>:<BR>
        <INPUT TYPE="text" NAME="SSTATE" SIZE="25" STYLE='font-family: Arial; font-size: 9pt; width: 275px;' VALUE="<? echo $SSTATE; ?>">
		<?

		if ($st_err == 1) {
			echo "<BR><font color=red>".$lang["State Invalid"].".</font>";
		}

		?>

      </P>
    </TD>
  </TR>
  <TR> 
    <TD ALIGN="LEFT" VALIGN="TOP"><B><FONT COLOR="#FF0000">*</FONT><? echo $lang["Country"]; ?></B>:<BR>
      <SELECT NAME="SCOUNTRY" STYLE='font-family: Arial; font-size: 9pt; width: 275px;'>
        <OPTION VALUE="United States" SELECTED>United States 
        <OPTION VALUE="Albania">Albania 
        <OPTION VALUE="Algeria">Algeria 
        <OPTION VALUE="American Samoa">American Samoa 
        <OPTION VALUE="Andorra">Andorra 
        <OPTION VALUE="Anguilla">Anguilla 
        <OPTION VALUE="Antigua & Barbuda">Antigua & Barbuda 
        <OPTION VALUE="Argentina">Argentina 
        <OPTION VALUE="Aruba">Aruba 
        <OPTION VALUE="Australia">Australia 
        <OPTION VALUE="Austria">Austria 
        <OPTION VALUE="Azores">Azores 
        <OPTION VALUE="Bahamas">Bahamas 
        <OPTION VALUE="Bahrain">Bahrain 
        <OPTION VALUE="Bangladesh">Bangladesh 
        <OPTION VALUE="Barbados">Barbados 
        <OPTION VALUE="Belgium">Belgium 
        <OPTION VALUE="Belize">Belize 
        <OPTION VALUE="Belarus">Belarus 
        <OPTION VALUE="Benin">Benin 
        <OPTION VALUE="Bermuda">Bermuda 
        <OPTION VALUE="Bolivia">Bolivia 
        <OPTION VALUE="Bonaire">Bonaire 
        <OPTION VALUE="Bosnia">Bosnia 
        <OPTION VALUE="Botswana">Botswana 
        <OPTION VALUE="Brazil">Brazil 
        <OPTION VALUE="Brunei">Brunei 
        <OPTION VALUE="Bulgaria">Bulgaria 
        <OPTION VALUE="Burkina Faso">Burkina Faso 
        <OPTION VALUE="Burundi">Burundi 
        <OPTION VALUE="Cambodia">Cambodia 
        <OPTION VALUE="Cameroon">Cameroon 
        <OPTION VALUE="Canada">Canada 
        <OPTION VALUE="Canary Islands">Canary Islands 
        <OPTION VALUE="Cape Verde Islands">Cape Verde Islands 
        <OPTION VALUE="Cayman Islands">Cayman Islands 
        <OPTION VALUE="Central African Republic">Central African Republic 
        <OPTION VALUE="Chad">Chad 
        <OPTION VALUE="Channel Islands">Channel Islands 
        <OPTION VALUE="Chile">Chile 
        <OPTION VALUE="China, Peoples Republic of">China 
        <OPTION VALUE="Colombia">Colombia 
        <OPTION VALUE="Congo">Congo 
        <OPTION VALUE="Cook Islands">Cook Islands 
        <OPTION VALUE="Costa Rica">Costa Rica 
        <OPTION VALUE="Croatia">Croatia 
        <OPTION VALUE="Curacao">Curacao 
        <OPTION VALUE="Cyprus">Cyprus 
        <OPTION VALUE="Czech Republic">Czech Republic 
        <OPTION VALUE="Denmark">Denmark 
        <OPTION VALUE="Djibouti">Djibouti 
        <OPTION VALUE="Dominica">Dominica 
        <OPTION VALUE="Dominican Republic">Dominican Republic 
        <OPTION VALUE="Ecuador">Ecuador 
        <OPTION VALUE="Egypt">Egypt 
        <OPTION VALUE="El Salvador">El Salvador 
        <OPTION VALUE="England">England 
        <OPTION VALUE="Equitorial Guinea">Equitorial Guinea 
        <OPTION VALUE="Eritrea">Eritrea 
        <OPTION VALUE="Estonia">Estonia 
        <OPTION VALUE="Ethiopia">Ethiopia 
        <OPTION VALUE="Faeroe Islands">Faeroe Islands 
        <OPTION VALUE="Fiji">Fiji 
        <OPTION VALUE="Finland">Finland 
        <OPTION VALUE="France">France 
        <OPTION VALUE="French Guiana">French Guiana 
        <OPTION VALUE="French Polynesia">French Polynesia 
        <OPTION VALUE="Gabon">Gabon 
        <OPTION VALUE="Gambia">Gambia 
        <OPTION VALUE="Georgia">Georgia 
        <OPTION VALUE="Germany">Germany 
        <OPTION VALUE="Ghana">Ghana 
        <OPTION VALUE="Gibraltar">Gibraltar 
        <OPTION VALUE="Greece">Greece 
        <OPTION VALUE="Greenland">Greenland 
        <OPTION VALUE="Grenada">Grenada 
        <OPTION VALUE="Guadeloupe">Guadeloupe 
        <OPTION VALUE="Guam">Guam 
        <OPTION VALUE="Guatemala">Guatemala 
        <OPTION VALUE="Guinea">Guinea 
        <OPTION VALUE="Guinea-Bissau">Guinea-Bissau 
        <OPTION VALUE="Guyana">Guyana 
        <OPTION VALUE="Haiti">Haiti 
        <OPTION VALUE="Holland">Holland 
        <OPTION VALUE="Honduras">Honduras 
        <OPTION VALUE="Hong Kong">Hong Kong 
        <OPTION VALUE="Hungary">Hungary 
        <OPTION VALUE="Iceland">Iceland 
        <OPTION VALUE="India">India 
        <OPTION VALUE="Indonesia">Indonesia 
        <OPTION VALUE="Israel">Israel 
        <OPTION VALUE="Italy">Italy 
        <OPTION VALUE="Ivory Coast">Ivory Coast 
        <OPTION VALUE="Jamaica">Jamaica 
        <OPTION VALUE="Japan">Japan 
        <OPTION VALUE="Jordan">Jordan 
        <OPTION VALUE="Kazakhstan">Kazakhstan 
        <OPTION VALUE="Kenya">Kenya 
        <OPTION VALUE="Kiribati">Kiribati 
        <OPTION VALUE="Kosrae">Kosrae 
        <OPTION VALUE="Kuwait">Kuwait 
        <OPTION VALUE="Kyrgyzstan">Kyrgyzstan 
        <OPTION VALUE="Laos">Laos 
        <OPTION VALUE="Latvia">Latvia 
        <OPTION VALUE="Lebanon">Lebanon 
        <OPTION VALUE="Lesotho">Lesotho 
        <OPTION VALUE="Liberia">Liberia 
        <OPTION VALUE="Liechtenstein">Liechtenstein 
        <OPTION VALUE="Lithuania">Lithuania 
        <OPTION VALUE="Luxembourg">Luxembourg 
        <OPTION VALUE="Macau">Macau 
        <OPTION VALUE="Macedonia">Macedonia 
        <OPTION VALUE="Madagascar">Madagascar 
        <OPTION VALUE="Madeira">Madeira 
        <OPTION VALUE="Malawi">Malawi 
        <OPTION VALUE="Malaysia">Malaysia 
        <OPTION VALUE="Maldives">Maldives 
        <OPTION VALUE="Mali">Mali 
        <OPTION VALUE="Malta">Malta 
        <OPTION VALUE="Marshall Islands">Marshall Islands 
        <OPTION VALUE="Martinique">Martinique 
        <OPTION VALUE="Mauritania">Mauritania 
        <OPTION VALUE="Mauritius">Mauritius 
        <OPTION VALUE="Mexico">Mexico 
        <OPTION VALUE="Moldova">Moldova 
        <OPTION VALUE="Monaco">Monaco 
        <OPTION VALUE="Montserrat">Montserrat 
        <OPTION VALUE="Morocco">Morocco 
        <OPTION VALUE="Mozambique">Mozambique 
        <OPTION VALUE="Myanmar">Myanmar 
        <OPTION VALUE="Namibia">Namibia 
        <OPTION VALUE="Nepal">Nepal 
        <OPTION VALUE="Netherlands">Netherlands 
        <OPTION VALUE="New Caledonia">New Caledonia 
        <OPTION VALUE="New Zealand">New Zealand 
        <OPTION VALUE="Nicaragua">Nicaragua 
        <OPTION VALUE="Niger">Niger 
        <OPTION VALUE="Nigeria">Nigeria 
        <OPTION VALUE="Norfolk Island">Norfolk Island 
        <OPTION VALUE="Northern Ireland">Northern Ireland 
        <OPTION VALUE="Norway">Norway 
        <OPTION VALUE="Oman">Oman 
        <OPTION VALUE="Pakistan">Pakistan 
        <OPTION VALUE="Palau">Palau 
        <OPTION VALUE="Panama">Panama 
        <OPTION VALUE="Papua New Guinea">Papua New Guinea 
        <OPTION VALUE="Paraguay">Paraguay 
        <OPTION VALUE="Peru">Peru 
        <OPTION VALUE="Philippines">Philippines 
        <OPTION VALUE="Poland">Poland 
        <OPTION VALUE="Ponape">Ponape 
        <OPTION VALUE="Portugal">Portugal 
        <OPTION VALUE="Puerto Rico">Puerto Rico 
        <OPTION VALUE="Qatar">Qatar 
        <OPTION VALUE="Republic of Ireland">Republic of Ireland 
        <OPTION VALUE="Republic of Yemen">Republic of Yemen 
        <OPTION VALUE="Reunion">Reunion 
        <OPTION VALUE="Romania">Romania 
        <OPTION VALUE="Rota">Rota 
        <OPTION VALUE="Russia">Russia 
        <OPTION VALUE="Rwanda">Rwanda 
        <OPTION VALUE="Saba">Saba 
        <OPTION VALUE="Saipan">Saipan 
        <OPTION VALUE="Saudi Arabia">Saudi Arabia 
        <OPTION VALUE="Scotland">Scotland 
        <OPTION VALUE="Senegal">Senegal 
        <OPTION VALUE="Seychelles">Seychelles 
        <OPTION VALUE="Sierra Leone">Sierra Leone 
        <OPTION VALUE="Singapore">Singapore 
        <OPTION VALUE="Slovakia">Slovakia 
        <OPTION VALUE="Slovenia">Slovenia 
        <OPTION VALUE="Solomon Islands">Solomon Islands 
        <OPTION VALUE="South Africa">South Africa 
        <OPTION VALUE="South Korea">South Korea 
        <OPTION VALUE="Spain">Spain 
        <OPTION VALUE="Sri Lanka">Sri Lanka 
        <OPTION VALUE="St. Barthelemy">St. Barthelemy 
        <OPTION VALUE="St. Christopher">St. Christopher 
        <OPTION VALUE="St. Croix">St. Croix 
        <OPTION VALUE="St. Eustatius">St. Eustatius 
        <OPTION VALUE="St. John">St. John 
        <OPTION VALUE="St. Kitts & Nevis">St. Kitts & Nevis 
        <OPTION VALUE="St. Lucia">St. Lucia 
        <OPTION VALUE="St. Maarten">St. Maarten 
        <OPTION VALUE="St. Martin">St. Martin 
        <OPTION VALUE="St. Thomas">St. Thomas 
        <OPTION VALUE="Sudan">Sudan 
        <OPTION VALUE="Suriname">Suriname 
        <OPTION VALUE="Swaziland">Swaziland 
        <OPTION VALUE="Sweden">Sweden 
        <OPTION VALUE="Switzerland">Switzerland 
        <OPTION VALUE="Syria">Syria 
        <OPTION VALUE="Tahiti">Tahiti 
        <OPTION VALUE="Taiwan">Taiwan 
        <OPTION VALUE="Tajikistan">Tajikistan 
        <OPTION VALUE="Tanzania">Tanzania 
        <OPTION VALUE="Thailand">Thailand 
        <OPTION VALUE="Tinian">Tinian 
        <OPTION VALUE="Togo">Togo 
        <OPTION VALUE="Tonga">Tonga 
        <OPTION VALUE="Tortola">Tortola 
        <OPTION VALUE="Truk">Truk 
        <OPTION VALUE="Trinidad & Tobago">Trinidad & Tobago 
        <OPTION VALUE="Tunisia">Tunisia 
        <OPTION VALUE="Turkey">Turkey 
        <OPTION VALUE="Tuvalu">Tuvalu 
        <OPTION VALUE="Uganda">Uganda 
        <OPTION VALUE="Ukraine">Ukraine 
        <OPTION VALUE="Union Island">Union Island 
        <OPTION VALUE="United Arab Emirates">United Arab Emirates 
        <OPTION VALUE="United Kingdom">United Kingdom 
        <OPTION VALUE="United States">United States 
        <OPTION VALUE="Uruguay">Uruguay 
        <OPTION VALUE="US Virgin Islands">US Virgin Islands 
        <OPTION VALUE="Uzbekistan">Uzbekistan 
        <OPTION VALUE="Vanuatu">Vanuatu 
        <OPTION VALUE="Venezuela">Venezuela 
        <OPTION VALUE="Vietnam">Vietnam 
        <OPTION VALUE="Virgin Islands">Virgin Islands 
        <OPTION VALUE="Virgin Gorda">Virgin Gorda 
        <OPTION VALUE="Wake Island">Wake Island 
        <OPTION VALUE="Wales">Wales 
        <OPTION VALUE="Western Samoa">Western Samoa 
        <OPTION VALUE="Yap">Yap 
        <OPTION VALUE="Yugoslavia">Yugoslavia 
        <OPTION VALUE="Zaire">Zaire 
        <OPTION VALUE="Zambia">Zambia 
        <OPTION VALUE="Zimbabwe">Zimbabwe 
      </SELECT>
    </TD>
    <TD><B><FONT COLOR="#FF0000">*</FONT><? echo $lang["Postal/Zip Code"]; ?></B>:<BR>
      <INPUT TYPE="text" NAME="SZIPCODE" SIZE="25" STYLE='font-family: Arial; font-size: 9pt; width: 275px;' VALUE="<? echo $SZIPCODE; ?>">
    </TD>
  </TR>
  <TR> 
    <TD><B><FONT COLOR="#FF0000">*</FONT><? echo $lang["Ship-To Phone Number"]; ?></B>:<BR>
      <? echo $lang["Country Code"]; ?>: 
      <SELECT NAME="SPHONE_COUNTRYCODE" STYLE='font-family: Arial; font-size: 7pt; width: 200px;'>
        <OPTION VALUE="355" >355 - Albania</OPTION>
        <OPTION VALUE="213" >213 - Algeria</OPTION>
        <OPTION VALUE="684" >684 - American Samoa</OPTION>
        <OPTION VALUE="376" >376 - Andorra</OPTION>
        <OPTION VALUE="244" >244 - Angola</OPTION>
        <OPTION VALUE="809" >809 - Anguilla</OPTION>
        <OPTION VALUE="268" >268 - Antigua</OPTION>
        <OPTION VALUE="54" >54 - Argentina</OPTION>
        <OPTION VALUE="374" >374 - Armenia </OPTION>
        <OPTION VALUE="297" >297 - Aruba</OPTION>
        <OPTION VALUE="247" >247 - Ascension Island</OPTION>
        <OPTION VALUE="61" >61 - Australia</OPTION>
        <OPTION VALUE="672" >672 - Australian External Territories </OPTION>
        <OPTION VALUE="43" >43 - Austria</OPTION>
        <OPTION VALUE="994" >994 - Azerbaijan</OPTION>
        <OPTION VALUE="242" >242 - Bahamas</OPTION>
        <OPTION VALUE="246" >246 - Barbados</OPTION>
        <OPTION VALUE="973" >973 - Bahrain</OPTION>
        <OPTION VALUE="880" >880 - Bangladesh</OPTION>
        <OPTION VALUE="375" >375 - Belarus</OPTION>
        <OPTION VALUE="32" >32 - Belgium</OPTION>
        <OPTION VALUE="501" >501 - Belize</OPTION>
        <OPTION VALUE="229" >229 - Benin</OPTION>
        <OPTION VALUE="809" >809 - Bermuda</OPTION>
        <OPTION VALUE="975" >975 - Bhutan</OPTION>
        <OPTION VALUE="284" >284 - British Virgin Islands</OPTION>
        <OPTION VALUE="591" >591 - Bolivia</OPTION>
        <OPTION VALUE="387" >387 - Bosnia and Hercegovina</OPTION>
        <OPTION VALUE="267" >267 - Botswana</OPTION>
        <OPTION VALUE="55" >55 - Brazil</OPTION>
        <OPTION VALUE="284" >284 - British V.I.</OPTION>
        <OPTION VALUE="673" >673 - Brunei Darussalm</OPTION>
        <OPTION VALUE="359" >359 - Bulgaria</OPTION>
        <OPTION VALUE="226" >226 - Burkina Faso</OPTION>
        <OPTION VALUE="257" >257 - Burundi</OPTION>
        <OPTION VALUE="855" >855 - Cambodia</OPTION>
        <OPTION VALUE="237" >237 - Cameroon</OPTION>
        <OPTION VALUE="1" >1 - Canada</OPTION>
        <OPTION VALUE="238" >238 - Capr Verde Islands</OPTION>
        <OPTION VALUE="1" >1 - Caribbean Nations</OPTION>
        <OPTION VALUE="345" >345 - Cayman Islands</OPTION>
        <OPTION VALUE="238" >238 - Cape Verdi</OPTION>
        <OPTION VALUE="236" >236 - Central African Republic</OPTION>
        <OPTION VALUE="235" >235 - Chad</OPTION>
        <OPTION VALUE="56" >56 - Chile</OPTION>
        <OPTION VALUE="86" >86 - China (People's Republic)</OPTION>
        <OPTION VALUE="886" >886 - China-Taiwan</OPTION>
        <OPTION VALUE="57" >57 - Colombia</OPTION>
        <OPTION VALUE="269" >269 - Comoros and Mayotte</OPTION>
        <OPTION VALUE="242" >242 - Congo</OPTION>
        <OPTION VALUE="682" >682 - Cook Islands</OPTION>
        <OPTION VALUE="506" >506 - Costa Rica</OPTION>
        <OPTION VALUE="385" >385 - Croatia</OPTION>
        <OPTION VALUE="53" >53 - Cuba</OPTION>
        <OPTION VALUE="357" >357 - Cyprus</OPTION>
        <OPTION VALUE="420" >420 - Czech Republic</OPTION>
        <OPTION VALUE="45" >45 - Denmark</OPTION>
        <OPTION VALUE="246" >246 - Diego Garcia</OPTION>
        <OPTION VALUE="767" >767 - Dominca</OPTION>
        <OPTION VALUE="809" >809 - Dominican Republic</OPTION>
        <OPTION VALUE="253" >253 - Djibouti</OPTION>
        <OPTION VALUE="593" >593 - Ecuador</OPTION>
        <OPTION VALUE="20" >20 - Egypt</OPTION>
        <OPTION VALUE="503" >503 - El Salvador</OPTION>
        <OPTION VALUE="240" >240 - Equatorial Guinea</OPTION>
        <OPTION VALUE="291" >291 - Eritrea</OPTION>
        <OPTION VALUE="372" >372 - Estonia</OPTION>
        <OPTION VALUE="251" >251 - Ethiopia</OPTION>
        <OPTION VALUE="500" >500 - Falkland Islands</OPTION>
        <OPTION VALUE="298" >298 - Faroe (Faeroe) Islands (Denmark)</OPTION>
        <OPTION VALUE="679" >679 - Fiji</OPTION>
        <OPTION VALUE="358" >358 - Finland</OPTION>
        <OPTION VALUE="33" >33 - France</OPTION>
        <OPTION VALUE="596" >596 - French Antilles </OPTION>
        <OPTION VALUE="594" >594 - French Guiana</OPTION>
        <OPTION VALUE="241" >241 - Gabon (Gabonese Republic)</OPTION>
        <OPTION VALUE="220" >220 - Gambia</OPTION>
        <OPTION VALUE="995" >995 - Georgia</OPTION>
        <OPTION VALUE="49" >49 - Germany</OPTION>
        <OPTION VALUE="233" >233 - Ghana</OPTION>
        <OPTION VALUE="350" >350 - Gibraltar</OPTION>
        <OPTION VALUE="30" >30 - Greece</OPTION>
        <OPTION VALUE="299" >299 - Greenland</OPTION>
        <OPTION VALUE="473" >473 - Grenada/Carricou</OPTION>
        <OPTION VALUE="671" >671 - Guam</OPTION>
        <OPTION VALUE="502" >502 - Guatemala</OPTION>
        <OPTION VALUE="224" >224 - Guinea</OPTION>
        <OPTION VALUE="245" >245 - Guinea-Bissau</OPTION>
        <OPTION VALUE="592" >592 - Guyana</OPTION>
        <OPTION VALUE="509" >509 - Haiti</OPTION>
        <OPTION VALUE="504" >504 - Honduras</OPTION>
        <OPTION VALUE="852" >852 - Hong Kong</OPTION>
        <OPTION VALUE="36" >36 - Hungary</OPTION>
        <OPTION VALUE="354" >354 - Iceland</OPTION>
        <OPTION VALUE="91" >91 - India</OPTION>
        <OPTION VALUE="62" >62 - Indonesia</OPTION>
        <OPTION VALUE="98" >98 - Iran</OPTION>
        <OPTION VALUE="964" >964 - Iraq</OPTION>
        <OPTION VALUE="353" >353 - Ireland (Irish Republic; Eire)</OPTION>
        <OPTION VALUE="972" >972 - Israel</OPTION>
        <OPTION VALUE="39" >39 - Italy</OPTION>
        <OPTION VALUE="225" >225 - Ivory Coast (La Cote d'Ivoire)</OPTION>
        <OPTION VALUE="876" >876 - Jamaica</OPTION>
        <OPTION VALUE="81" >81 - Japan</OPTION>
        <OPTION VALUE="962" >962 - Jordan</OPTION>
        <OPTION VALUE="7" >7 - Kazakhstan</OPTION>
        <OPTION VALUE="254" >254 - Kenya</OPTION>
        <OPTION VALUE="855" >855 - Khmer Republic (Cambodia/Kampuchea)</OPTION>
        <OPTION VALUE="686" >686 - Kiribati Republic (Gilbert Islands)</OPTION>
        <OPTION VALUE="82" >82 - Korea Republic of (South Korea)</OPTION>
        <OPTION VALUE="850" >850 - Korea People's Republic of (North Korea)</OPTION>
        <OPTION VALUE="965" >965 - Kuwait</OPTION>
        <OPTION VALUE="996" >996 - Kyrgyz Republic</OPTION>
        <OPTION VALUE="371" >371 - Latvia</OPTION>
        <OPTION VALUE="856" >856 - Laos</OPTION>
        <OPTION VALUE="961" >961 - Lebanon</OPTION>
        <OPTION VALUE="266" >266 - Lesotho</OPTION>
        <OPTION VALUE="231" >231 - Liberia</OPTION>
        <OPTION VALUE="370" >370 - Lithuania</OPTION>
        <OPTION VALUE="218" >218 - Libya</OPTION>
        <OPTION VALUE="423" >423 - Liechtenstein</OPTION>
        <OPTION VALUE="352" >352 - Luxembourg</OPTION>
        <OPTION VALUE="853" >853 - Macao</OPTION>
        <OPTION VALUE="389" >389 - Macedonia</OPTION>
        <OPTION VALUE="261" >261 - Madagascar</OPTION>
        <OPTION VALUE="265" >265 - Malawi</OPTION>
        <OPTION VALUE="60" >60 - Malaysia</OPTION>
        <OPTION VALUE="960" >960 - Maldives</OPTION>
        <OPTION VALUE="223" >223 - Mali</OPTION>
        <OPTION VALUE="356" >356 - Malta</OPTION>
        <OPTION VALUE="692" >692 - Marshall Islands</OPTION>
        <OPTION VALUE="596" >596 - Martinique (French Antilles)</OPTION>
        <OPTION VALUE="222" >222 - Mauritania</OPTION>
        <OPTION VALUE="230" >230 - Mauritius</OPTION>
        <OPTION VALUE="269" >269 - Mayolte</OPTION>
        <OPTION VALUE="52" >52 - Mexico</OPTION>
        <OPTION VALUE="691" >691 - Micronesia (F.S. of Polynesia)</OPTION>
        <OPTION VALUE="373" >373 - Moldova</OPTION>
        <OPTION VALUE="33" >33 - Monaco</OPTION>
        <OPTION VALUE="976" >976 - Mongolia</OPTION>
        <OPTION VALUE="473" >473 - Montserrat</OPTION>
        <OPTION VALUE="212" >212 - Morocco</OPTION>
        <OPTION VALUE="258" >258 - Mozambique</OPTION>
        <OPTION VALUE="95" >95 - Myanmar (former Burma)</OPTION>
        <OPTION VALUE="264" >264 - Namibia (former South-West Africa)</OPTION>
        <OPTION VALUE="674" >674 - Nauru</OPTION>
        <OPTION VALUE="977" >977 - Nepal</OPTION>
        <OPTION VALUE="31" >31 - Netherlands</OPTION>
        <OPTION VALUE="599" >599 - Netherlands Antilles</OPTION>
        <OPTION VALUE="869" >869 - Nevis</OPTION>
        <OPTION VALUE="687" >687 - New Caledonia</OPTION>
        <OPTION VALUE="64" >64 - New Zealand</OPTION>
        <OPTION VALUE="505" >505 - Nicaragua</OPTION>
        <OPTION VALUE="227" >227 - Niger</OPTION>
        <OPTION VALUE="234" >234 - Nigeria</OPTION>
        <OPTION VALUE="683" >683 - Niue</OPTION>
        <OPTION VALUE="850" >850 - North Korea</OPTION>
        <OPTION VALUE="1 670" >1 670 - North Mariana Islands (Saipan)</OPTION>
        <OPTION VALUE="47" >47 - Norway</OPTION>
        <OPTION VALUE="968" >968 - Oman</OPTION>
        <OPTION VALUE="92" >92 - Pakistan</OPTION>
        <OPTION VALUE="680" >680 - Palau</OPTION>
        <OPTION VALUE="507" >507 - Panama</OPTION>
        <OPTION VALUE="675" >675 - Papua New Guinea</OPTION>
        <OPTION VALUE="595" >595 - Paraguay</OPTION>
        <OPTION VALUE="51" >51 - Peru</OPTION>
        <OPTION VALUE="63" >63 - Philippines</OPTION>
        <OPTION VALUE="48" >48 - Poland</OPTION>
        <OPTION VALUE="351" >351 - Portugal (includes Azores)</OPTION>
        <OPTION VALUE="1 787" >1 787 - Puerto Rico</OPTION>
        <OPTION VALUE="974" >974 - Qatar</OPTION>
        <OPTION VALUE="262" >262 - Reunion (France)</OPTION>
        <OPTION VALUE="40" >40 - Romania</OPTION>
        <OPTION VALUE="7" >7 - Russia</OPTION>
        <OPTION VALUE="250" >250 - Rwanda (Rwandese Republic)</OPTION>
        <OPTION VALUE="670" >670 - Saipan</OPTION>
        <OPTION VALUE="378" >378 - San Marino</OPTION>
        <OPTION VALUE="239" >239 - Sao Tome and Principe</OPTION>
        <OPTION VALUE="966" >966 - Saudi Arabia</OPTION>
        <OPTION VALUE="221" >221 - Senegal</OPTION>
        <OPTION VALUE="381" >381 - Serbia and Montenegro</OPTION>
        <OPTION VALUE="248" >248 - Seychelles</OPTION>
        <OPTION VALUE="232" >232 - Sierra Leone</OPTION>
        <OPTION VALUE="65" >65 - Singapore</OPTION>
        <OPTION VALUE="421" >421 - Slovakia </OPTION>
        <OPTION VALUE="386" >386 - Slovenia</OPTION>
        <OPTION VALUE="677" >677 - Solomon Islands</OPTION>
        <OPTION VALUE="252" >252 - Somalia</OPTION>
        <OPTION VALUE="27" >27 - South Africa</OPTION>
        <OPTION VALUE="34" >34 - Spain</OPTION>
        <OPTION VALUE="94" >94 - Sri Lanka</OPTION>
        <OPTION VALUE="290" >290 - St. Helena</OPTION>
        <OPTION VALUE="869" >869 - St. Kitts/Nevis</OPTION>
        <OPTION VALUE="508" >508 - St. Pierre &(et) Miquelon (France)</OPTION>
        <OPTION VALUE="249" >249 - Sudan</OPTION>
        <OPTION VALUE="597" >597 - Suriname</OPTION>
        <OPTION VALUE="268" >268 - Swaziland</OPTION>
        <OPTION VALUE="46" >46 - Sweden</OPTION>
        <OPTION VALUE="41" >41 - Switzerland</OPTION>
        <OPTION VALUE="963" >963 - Syrian Arab Republic (Syria)</OPTION>
        <OPTION VALUE="689" >689 - Tahiti (French Polynesia)</OPTION>
        <OPTION VALUE="886" >886 - Taiwan</OPTION>
        <OPTION VALUE="7" >7 - Tajikistan</OPTION>
        <OPTION VALUE="255" >255 - Tanzania (includes Zanzibar)</OPTION>
        <OPTION VALUE="66" >66 - Thailand</OPTION>
        <OPTION VALUE="228" >228 - Togo (Togolese Republic)</OPTION>
        <OPTION VALUE="690" >690 - Tokelau</OPTION>
        <OPTION VALUE="676" >676 - Tonga</OPTION>
        <OPTION VALUE="1 868" >1 868 - Trinidad and Tobago</OPTION>
        <OPTION VALUE="216" >216 - Tunisia</OPTION>
        <OPTION VALUE="90" >90 - Turkey</OPTION>
        <OPTION VALUE="993" >993 - Turkmenistan</OPTION>
        <OPTION VALUE="688" >688 - Tuvalu (Ellice Islands)</OPTION>
        <OPTION VALUE="256" >256 - Uganda</OPTION>
        <OPTION VALUE="380" >380 - Ukraine</OPTION>
        <OPTION VALUE="971" >971 - United Arab Emirates</OPTION>
        <OPTION VALUE="44" >44 - United Kingdom</OPTION>
        <OPTION VALUE="598" >598 - Uruguay</OPTION>
        <OPTION VALUE="" SELECTED>USA</OPTION>
        <OPTION VALUE="7" >7 - Uzbekistan</OPTION>
        <OPTION VALUE="678" >678 - Vanuatu (New Hebrides)</OPTION>
        <OPTION VALUE="39" >39 - Vatican City</OPTION>
        <OPTION VALUE="58" >58 - Venezuela</OPTION>
        <OPTION VALUE="84" >84 - Viet Nam</OPTION>
        <OPTION VALUE="1 340" >1 340 - Virgin Islands</OPTION>
        <OPTION VALUE="681" >681 - Wallis and Futuna</OPTION>
        <OPTION VALUE="685" >685 - Western Samoa</OPTION>
        <OPTION VALUE="381" >381 - Yemen</OPTION>
        <OPTION VALUE="967" >967 - Yemen Arab Republic (North Yemen)</OPTION>
        <OPTION VALUE="243" >243 - Zaire</OPTION>
        <OPTION VALUE="260" >260 - Zambia</OPTION>
        <OPTION VALUE="263" >263 - Zimbabwe</OPTION>
      </SELECT>
      <BR>
      ( 
      <INPUT TYPE="text" NAME="SPHONE_AREACODE" SIZE="3" MAXLENGTH="3" VALUE="<? echo $SPHONE_AREACODE; ?>">
      ) 
      <INPUT TYPE="text" NAME="SPHONE_PREFIX" SIZE="3" MAXLENGTH="3" VALUE="<? echo $SPHONE_PREFIX; ?>">
      - 
      <INPUT TYPE="text" NAME="SPHONE_SUFFIX" SIZE="4" MAXLENGTH="4" VALUE="<? echo $SPHONE_SUFFIX; ?>">
    </TD>
    <TD ALIGN="CENTER" VALIGN="TOP">&nbsp;</TD>
  </TR>
 
</TABLE>

</FORM>

<?

// Make sure drop down selection boxes show proper selected value
// ----------------------------------------------------------------

echo "<SCRIPT LANGUAGE=JAVASCRIPT>\n\n";

if ($BCOUNTRY != "") {
	echo "     document.CHECKOUT1.BCOUNTRY.value = \"$BCOUNTRY\";\n";
}
if ($SCOUNTRY != "") {
	echo "     document.CHECKOUT1.SCOUNTRY.value = \"$SCOUNTRY\";\n";
}
if ($BPHONE_COUNTRYCODE != "") {
	echo "     document.CHECKOUT1.BPHONE_COUNTRYCODE.value = \"$BPHONE_COUNTRYCODE\";\n";
}
if ($SPHONE_COUNTRYCODE != "") {
	echo "     document.CHECKOUT1.SPHONE_COUNTRYCODE.value = \"$SPHONE_COUNTRYCODE\";\n";
}

echo "\n\n</SCRIPT>\n";

?>