<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.5
##
## Author: 			Mike Johnston [mike.johnston@soholaunch.com]
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugzilla.soholaunch.com
## Release Notes:	sohoadmin/build.dat.php
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

#################################################
## UPPER NAV BAR
#################################################

// Upper Bar - Main Menu
$lang["Open Page"] = "Rediger Side";
$lang["Main Menu"] = "Hovedmeny";
$lang["View Site"] = "Vis nettsted";
$lang["Webmaster"] = "Webmaster";
$lang["Logout"] = "Logg ut";

// Upper Bar - Page Editor
$lang["Save Page"] = "Lagre Side";
$lang["Save As"] = "Lagre Som";
$lang["Preview Page"] = "Forhåndsvis";
$lang["Page Properties"] = "Side Egenskaper";

// Feature Menus
$lang['Shopping Cart Menu'] = "Handlevogn Meny";
$lang['Calendar Menu'] = "Kalender Meny";
$lang['eNewsletter Menu'] = "E-Nyhetsbrev Meny";
$lang['Database Menu'] = "Database Meny";


#################################################
## STATUS BAR (footer)
#################################################
$lang["Product Build"] = "Produkt Versjon";


#################################################
## FEATURE PROMO / LICENSE UPGRAGE PAGE
## - When user clicks on 'disabled' feature
#################################################
$lang['promo']['box title'] = "Dette krever oppgradering av denne module";
$lang['promo']['access denied'] = "Lisens du har gi deg ikke tilgang til denne module.";
$lang['promo']['contact host'] = "Hvis du ønsker å aktivere denne module, vennligst kontakt"; // Followed by either host.conf.php info
$lang['promo']['how to upgrade'] = "Etter du har mottat beskjed at den nye module(r) er aktivert, kommer du tilbake her og klikke du &quot;Oppgrader Lisens&quot; knappen. Automatisk blir din nye lisens lastet ned og installert.";
$lang['promo']['upgrade installed'] = "Aktiv domene lisens lastet ned og installert for"; // " $_SERVER[SERVER_NAME]"
$lang['promo']['features ready'] = "Din nye moduler er aktivert.";

// Live progress report (while getting new key)
$lang['promo']['locating license'] = "Sjekker lisens";
$lang['promo']['license downloaded'] = "Ny lisens lastet ned";
$lang['promo']['installing license'] = "Installerer aktiv lisens matrix for "; // ' $_SERVER['HTTP_HOST']
$lang['promo']['please hold'] = "Vennligst vent";
$lang['promo']['features upgraded'] = "Modul(er) er oppgradert";

$lang['Upgrade License'] = "Oppgrader Lisens"; // Button

#################################################
## MAIN MENU SYSTEM							        ##
#################################################

// General Titles and Notifications
$lang["Site Visitor(s) online"] = "Besøkende på ditt nettsted";
$lang["NOTE: Any data outstanding will not be saved."] = "NB!: Data som ikke er ført vil ikke bli lagret.";

// Basic Features Group
$lang["Basic Features Group"] = "Standard modul gruppe";
$lang["Create New Pages"] = "Opprett nye sider";
$lang["Edit Pages"] = "Åpne/rediger Side(r)";
$lang["Menu Display"] = "Auto-Meny System";
$lang["File Manager"] = "Filer";
$lang["Template Manager"] = "Mal(er) for Nettsiden";
$lang["Forms Manager"] = "Form/Skjemaer";

// Advanced Features Group
$lang["Advanced Features Group"] = "Ekstra moduler";

$lang["Shopping Cart"] = "Handlevogn";
$lang["Event Calendar"] = "Kalender";
$lang["eNewsletter"] = "E-nyhetsbrev";
$lang["Site Data Tables"] = "Nettsidens data tabeller";
$lang["Database Table Manager"] = "Database tabell manager";
$lang["Secure Users"] = "Sikre Brukere";
$lang["Photo Albums"] = "Bildegalleri";
$lang["Site Statistics"] = "Nettside statistikk";
$lang["Blog Manager"] = "Blog håndtering";

// Javascript Alerts

$lang["Select a menu option from the main menu sections to get started."] = "Velg en meny mulighet fra Hoved Meny seksjonen for å komme i gang.";
$lang["You do not have access to this option."] = "Du har ikke tilgang til dette valget.";

// Footer Assets

$lang["About"] = "Om";

#################################################
## CREATE NEW PAGES MODULE					   ##
#################################################

$lang["Page Name"] = "Side Navn";
$lang["Page Type"] = "Side Type";
$lang["Create New Site Pages"] = "Lag sider for NY nettside";
$lang["Menu Page"] = "Meny side";
$lang["Newsletter"] = "Nyhetsbrev";
$lang["Calendar Attachment"] = "Kalender tilbehør";
$lang["Shopping Cart Attachment"] = "Handlevogn tilbehør";
$lang["Create More Pages"] = "Lag flere sider";
$lang["You may create up to 10 new pages at a time."] = "Du kan lage opp til 10 nye sider om gangen. Vær sikker på at du velger om disse sidene skal være klassifisert som <U>Hoved Meny</U> sider eller <U>Tilbehør</U> Sider.";
$lang["Your new pages have been created!"] = "Dine nye sider har blitt laget!\\n\\nDu kan begynne å redigere dem ved å åpne siden nå\\eller velge å lage flere nye sider.";
$lang["Could Not Create the Following Pages because they already exist on the system:"] = "Kan Ikke opprette følgende sidene fordi de allerede finnes på systemet:";


#################################################
## OPEN PAGE MODULE							   ##
#################################################

$lang["Edit Content"] = "Rediger Innhold";
$lang["Menu Status"] = "Meny Status";
$lang["Parent Page"] = "Underside";
$lang["Page Template"] = "Side mal";
$lang["Delete Page"] = "Slett Side";
$lang["Off Menu"] = "Av Meny";
$lang["On Menu"] = "På Meny";
$lang["Site base template"] = "Basis nettside mal";
$lang["Browse"] = "Browse";
$lang["Edit"] = "Rediger";
$lang["Delete"] = "Slett";
$lang["Number Skus"] = "Nummer Skus";
$lang["Articles"] = "Articles";
$lang["Latest News"] = "Latest News";

$lang["Click on the page name that you wish to edit"] = "Klikk på side navn som du ønsker å redigere.";
$lang["Are you sure you wish to delete this page"] = "Er Du sikkert å slette denne siden? Du kan ikke angre på dette valg!";

#################################################
## MENU DISPLAY MODULE     						  ##
#################################################

$lang["You have already used this page in your menu system."] = "Du har allerede brukt denne siden i meny.";
$lang["You can only use pages one time on your auto-menu system."] = "Du kan bare bruk en side en gang på ditt meny";
$lang["Auto-Menu Display Type"] = "Auto-meny visnings Type";
$lang["Text Links"] = "Tekst Linker";
$lang["Buttons"] = "Knapper";
$lang["Edit Button Colors"] = "Rediger Knapp Farger";
$lang["Text Menu Display"] = "Tekst Meny Oversikt";
$lang["Yes"] = "Ja";
$lang["No"] = "Nei";
$lang["Available Pages"] = "Tilgjengelige Sider";
$lang["Current Menu"] = "Aktiv Menu";

$lang["Select a page from your available site pages."] = "Velg en side fra din tilgjengelige sidene.";
$lang["Then, choose to add it to the bottom<BR>of your 'live' menu as a Main Menu Item or a Sub-Page of a Main Menu Item."] = "Etterpå velg denne for å legge denne nederst<BR>på din ’live’ meny sov Hovedmenlink y eller Underlink-side.";

$lang["To Delete a page on the current menu"] = "Slette en side på den aktive menyen";
$lang["select the page from the available pages"] = "utvalgt siden fra de tilgjengelige sidene";
$lang["that already appear on your current"] = "det kommer allerede fram i ditt aktive";
$lang["menu, then click 'Delete Page'."] = "Menu, klikk 'Slett Side'.";

$lang["Auto-Menu Button Colors"] = "Auto-meny knapp Farger";
$lang["Current Button Color Scheme"] = "Aktive knappe farge";
$lang["Button Color"] = "Knappe farge";
$lang["Button Text Color"] = "KnapptekstFarge";
$lang["Hex Color"] = "SekskantFarge";
$lang["About Us"] = "Om Oss";
$lang["Save Button Colors"] = "Lagre Knapp Farger";
$lang["Auto-Menu Setup"] = "Auto-meny Arrangement";
$lang["This is a text representation of the color scheme"] = "Dette er en tekst som representerer farge skjema.";

//Buttons
$lang["Add Main"] = "Legg til Hovedlink";
$lang["Add Sub"] = "Legg til Underlink";
$lang["Clear Menu"] = "Tøm Meny";
$lang["Save Menu System"] = "Lagre Meny System";



#################################################
## FILE MANAGER MODULE     						  ##
#################################################
$lang["File Name"] = "Filnavn";
$lang["File Size"] = "Fil Størrelse";
$lang["Image files can be viewed and saved by clicking<BR>the preview icon"] = "bilde filer kan vises eller lagret ved å klikke på <BR>forhåndsvis ikon";
$lang["next to the filename."] = "ved siden av filnavnet.";
$lang["Indicates an image that should be reduced in filesize.<BR>This file causes slow load-times when viewing your web Site."] = "Angir et avbilde at er redusert i filesize.<BR>Dette arkivet forårsaker langsome last-tider ved visning av Deres nettsted.";
$lang["Upload New Files"] = "Last opp nye filer";
$lang["Remember"] = "Husk";
$lang["Changes and deletions are final and can not be undone."] = "Forandringer og slettinger er endelige og ikke mulig å reversere.";
$lang["Update File Changes"] = "Oppdater Fil endringer";

// Upload New Files
// -------------------------------------------
$lang["Upload Files"] = "Last opp filer";
$lang["Select the <U>Browse</U> button next to each filename to locate your local file for upload. When ready to start the upload operation, select <U>Upload Files</U>."] = "Velg <U>Browse</U> knappen ved siden av hvert filnavn for å finne din lokale fil som du skal laste opp. Når du er klar til å starte opplastingen, velg <U>Last Opp Filer</U>";
$lang["Filename"] = "Filnavn";
$lang["Upload of files completed."] = "Opplasting fullført";
$lang["Current Site Files"] = "filene på nåværende nettside";
$lang["View Current Site Files"] = "Se filene på nåværende nettside";
$lang["Upload Custom Template HTML"] = "Last opp egen HTML mal";
$lang["Upload More Files"] = "Last opp flere filer";
$lang["Success"] = "Vellykket!";
$lang["Did not upload"] = "Ble ikke lastet opp";
$lang["File update completed."] = "Arkivoppdatering fullført.";
$lang["Filename already exists"] = "Filnavnet finnes allerede";
$lang["File is not an accepted file format"] = "Filen er ikke en godkjent fil-format";
$lang["Below is a report of the files that were uploaded during this operation"] = "Under er en rapport av filene som ble opplastet under denne operasjonen";
$lang["Upload Complete"] = "Opplasting fullført";
$lang["Open/Edit Page(s)"] = "Åpne/redigere Side(r)";

#################################################
## Site TEMPLATE MODULE						   ##
#################################################

// Template Mangager
$lang["Base Site Template"] = "Base Site Template:";
$lang["The base Site template"] = "Base Site template gjelder for alle sider og alle sider som ikke har en mal forhånds definert";
$lang["Browse Templates by Screenshot"] = "Bla gjennom maler med Screenshot";
$lang["Save Changes"] = "Lagre endringer";
$lang["Custom Template Builder"] = "Custom Template Builder";
$lang["Upload Custom Template HTML file(s)"] = "Last opp Custom Template HTML fil(er)";
$lang["Upload Template File(s)"] = "Last opp Template fil(er)";
$lang["If you are utilizing a built-in template"] = "Hvis du bruker en innebygd mal, redigerer du header informasjonen i mal som vist nedenfor.";
$lang["Built-In Template Header"] = "Built-In Template Header";
$lang["Enter your template header line"] = "Skriv inn din template header line";
$lang["Save Header"] = "Lagre Header";
$lang["Save Settings"] = "Lagre Header";
$lang["Company Slogan or Motto"] = "Company Slogan or Motto";

// Custom Template Builder
$lang["Template Builder"] = "Template Builder";
$lang["Template Name"] = "Template navn";
$lang["Template Image"] = "Template bilde";
$lang["Preview Design"] = "Forhåndsvis Design";
$lang["Save Design"] = "Lagre Design";
$lang["Image Preview Area"] = "Bilde forhåndsvis område";
$lang["Image must be 204px Wide x 106px High"] = "Bilde er 204px Vid x 106px Høy";
$lang["Template Style"] = "Mal Stil";
$lang["Blank"] = "Tomrom";
$lang["Left Bar"] = "Venstre Bar";
$lang["L-Shape"] = "L-form";
$lang["U-Shape"] = "U-form";
$lang["Pro"] = "Pro";
$lang["Foreground Color"] = "Forgrunns Farge";
$lang["Background Color"] = "Bakgrunn Farge";
$lang["Title Color"] = "Titel Farge";
$lang["Text Color"] = "Tekst Farge";
$lang["Link Color"] = "Link Farge";

#################################################
## FORMS MANAGER MODULE						        ##
#################################################

$lang["Current Forms"] = "Aktive Skjemaer";
$lang["New Form Creation Wizard"] = "Ny skjema Wizard";
$lang["To create a new form, enter the name"] = "For å opprette et nytt form/skjema, skriv inn navnet for denne skjema og klikk, Neste";
$lang["Build New Form"] = "Opprett ny form";
$lang["Preview"] = "Forhåndsvis";
$lang["Add New Fields"] = "Legg til nye felter";
$lang["Form Name"] = "Form navn";
$lang["PREVIEW WINDOW"] = "FORHÅNDSVIS VINDU";
$lang["You must enter a form name that is at least 3 characters long."] = "Du må skrive inn i et skjemanavn som er minst 3 bokstave lang.";

//Form Builder Wizard

$lang["Building"] = "Jobber";
$lang["Form Field"] = "Form Felt";
$lang["Field Label"] = "Felt navn";
$lang["Required Field"] = "Påkrevd Felt";
$lang["What is your Site visitor supposed to enter or select for this field"] = "Hva er nettstedbesøkende antatt å skriv inn eller velg for dette feltet";
$lang["Field Type"] = "Felt type";
$lang["Field Name"] = "Felt navn";
$lang["Text Box"] = "Tekst Felt";
$lang["Text Area (Multi-Line)"] = "TekstOmråde (Flerlinjet)";
$lang["Drop Down Box"] = "Drop Down box";
$lang["Radio Buttons"] = "Radio Knapper";
$lang["Checkboxes"] = "Checkboxes";
$lang["What is the Name of this field"] = "Hva er <u>Navnet</u> på dette feltet når prosess denne formen <br>For email eller databasevekselvirkning?";
$lang["Field Values"] = "Field Values";
$lang["Enter selectable options seperated by commas"] = "Gå inn i utvelgingsalternativer seperated ved kommaer";
$lang["Drop Down Boxes, Radio Buttons, and Checkboxes Only"] = "Bare for Drop Down Boxes, radio knapper, og Checkboxes";
$lang["[Save] Finish Form"] = "[Lagre] Avslutte Form";
$lang["Add Next Field"] = "Legg til Neste Felt";

#################################################
## Site STATISTICS MODULE					        ##
#################################################

// Main Stats Display
$lang["Unique Visitors"] = "Unike Besøkende";
$lang["Top 25 Pages"] = "Topp 25 Sider";
$lang["Views By Day"] = "Visninger pr. dag";
$lang["Views By Hour"] = "Visninger pr. Time";
$lang["Referer Sites"] = "Referanser";
$lang["Browser/OS"] = "Nettlesere/OS";
$lang["You should empty your log tables at least every six months are so depending on traffic."] = "Tøm din loggtabeller minst en gang pr. seks måneder slik er avhengige av trafikk.";
$lang["If you experience slowness<BR>in loading reports, your log tables have probably gone unattended for some time."] = "Om Du opplever at det går treg<BR>I innlastings rapporter Dine loggtabeller dradd sannsynlig ubemannet en stund.";

// statistics/includes/unique.php

$lang["UNIQUE VISITOR TREND"] = "ENESTÅENDE BESØKENDETREND";
$lang["Total Unique Visitors"] = "Totale Unike Besøkende";
$lang["Total Page Views"] = "Total side visninger";
$lang["Visit Frequency"] = "Besøk Frekvens";
$lang["Avg Pages Per Visit"] = "Avg Sider pr. besøk";

// statistics/includes/top25.php

$lang["TOP 25 Site PAGES/Site MODULES"] = "TOPP 25 Steder SIDER/PLASSERER MODULER";
$lang["Rank"] = "Rang";
$lang["Page Views"] = "Side visninger";

// statistics/includes/byday.php

$lang["PAGE VIEWS BY DAY"] = "SIDE VISNINGER PR DAG";
$lang["Total Page Views for"] = "Total Side visninger for";
$lang["Page Views Per Day Totals"] = "Side visninger Pr. Dag Total";
$lang["Mouseover a Selected day for actual total"] = "Hold mus over en valgt dag for aktuell total";

// statistics/includes/byhour.php

$lang["PAGE VIEWS BY HOUR"] = "SIDE VISNINGER PR TIMER";
$lang["Most active hour of the day"] = "Mest aktiv time av dagen";
$lang["Mouseover a Selected Hour for actual total"] = "Hold mus over en valgt dag for aktuell total";

// statistics/includes/refer.php


$lang["REFERER SiteS"] = "REFERANSE sider";
$lang["Referals (per)"] = "Referanser (pr.)";
$lang["Referal Site"] = "Referanse Side";

// statistics/includes/browser.php

$lang["BROWSER AND OPERATING SYSTEMS USED"] = "BROWSER OG OPERATIVSYSTEMER BRUKT";
$lang["Browser"] = "Browser";
$lang["Usage Data"] = "Bruker data";


#################################################
## PHOTO ALBUM MODULE					           ##
#################################################

// photo_album/photo_album.php
$lang["Photo Album"] = "Bildegalleri";
$lang["Create New Album"] = "Opprett nytt Galleri";
$lang["Enter Album Name"] = "Skriv inn album navn";
$lang["Current Photo Albums"] = "Aktive bildegalleri";
$lang["Select Album"] = "Valgt galleri";

// photo_album/edit_album.php
$lang["Edit Album"] = "Rediger Galleri";
$lang["Image Preview"] = "Bilde Forhåndsvsn.";
$lang["Details"] = "Detaljer";
$lang["Image"] = "Bilde";
$lang["Caption"] = "Bildetekst";
$lang["Link"] = "Link";
$lang["Save Album"] = "Lagre Galleri";
$lang["Cancel Edit"] = "Angre Redigerer";

#################################################
## Site DATA TABLES MODULE					        ##
#################################################

// download_data.php

$lang["Manage/Backup Site Data Tables"] = "Forvalter/ oppbakkingssted data Tabeller";
$lang["View"] = "til";
$lang["Download"] = "Download";
$lang["Import"] = "Import";
$lang["Empty"] = "Tom";
$lang["Database table"] = "Database tabell";
$lang["View All Data Tables"] = "Vis Alle Database tabeller";
$lang["WARNING"] = "VARSLING";
$lang["You have selected to clear the data from table"] = "Du valgt ut rydde dataene fra tabell";
$lang["This process is irreversible and will delete all data contained in this table"] = "Dunne prosessen er ugjenkallelig og sletter alle data som inneholdt i denne tabellen";
$lang["Are you sure you wish to continue"] = "Er Du sikker Du ønsker fortsette";
$lang["Continue"] = "Fortsett";
$lang["Cancel"] = "Angre";
$lang["CSV Filenames"] = "CSV Filnavn";
$lang["Select the CSV file that you wish to import"] = "Utvalgt CSV arkiv som Du ønsker importere";
$lang["Please note that you can only upload comma or semi-colon delimited CSV files"] = "Behag anmerkning som Du kan bare upload komma eller semikolon avgrenset CSV arkiver";
$lang["If you need to upload your csv file"] = "Hvis du trenger å laste opp din csv fil";
$lang["click here"] = "klikk her";
$lang["Use Default Value"] = "Bruk Standard verdi";
$lang["Select which fields in the CSV file to place into the existing table fields"] = "Utvalgt hvilke felt i CSV arkiv plassere inn i det finnesende tabellfelt";
$lang["First record of CSV data contains field names. Do not import."] = "Først inneholder protokoll av CSV data feltnavn. Importer ikke.";                                                                                                                                                                                                                                                                                                       
$lang["Table Field Name"] = "TabellfeltNavn";                                                                                                                                                                                                                                                                                                                                                                                                     
$lang["CSV Field Name"] = "CSV feltNavn";                                                                                                                                                                                                                                                                                                                                                                                                         
$lang["Default Import Value"] = "Standard import Verdi";                                                                                                                                                                                                                                                                                                                                                                                            
$lang["If a field name from your csv file is matched to the PriKey field of the table"] = "Hvis et feltnavn fra din csv fil overenstemmer med Prikey felt av tabellen, csv arkivdata vil<BR>'Finner oppdaterings' nøkler og 'legger til' nye protokoller som ikke finner en nøkkelverdi.";                                                                                                                                                         
$lang["Import Data Now"] = "Importer Data Nå";                                                                                                                                                                                                                                                                                                                                                                                                    
$lang["IMPORT OF CSV DATA TO"] = "IMPORT AV CSV DATA TIL";                                                                                                                                                                                                                                                                                                                                                                                        
$lang["COMPLETE!"] = "KOMPLETT! ";                                                                                                                                                                                                                                                                                                                                                                                                                
$lang["Records imported successfully"] = "Protokoller importert vellykket";                                                                                                                                                                                                                                                                                                                                                                       
$lang["Records were modified"] = "Protokoller modifiserte";                                                                                                                                                                                                                                                                                                                                                                                       
$lang["View all Tables"] = "Vis alle Tabeller";                                                                                                                                                                                                                                                                                                                                                                                                  
                                                                                                                                                                                                                                                                                                                                                                                                                                                  
                                                                                                                                                                                                                                                                                                                                                                                                                                                  
#################################################                                                                                                                                                                                                                                                                                                                                                                                                 
## BLOG MANAGER MODULE					           ##                                                                                                                                                                                                                                                                                                                                                                             
#################################################                                                                                                                                                                                                                                                                                                                                                                                                 

$lang["Blog Subjects"] = "Blog Emner";
$lang["New Subject"] = "Ny Emne";
$lang["Add New"] = "Legg til ny";
$lang["Existing Subjects"] = "Eksisterende Emner";
$lang["View"] = "Vis";
$lang["Create a new blog entry by entering your data in the text editor below"] = "Opprett en ny blog tilgang ved å gå inn i Din data i tekstredaktøren under";
$lang["Then choose the subject that this blog should be assigned to and click Post Blog to continue"] = "Velg da emnet som denne blog tildeler til og klikk <i>Post Blog</i> fortsette";
$lang["Blog Title"] = "Blog Tittel";
$lang["Please choose a subject to post this blog to"] = "Vennligst velg et emne å poste denne blog til";
$lang["Please choose a title for this post"] = "Vennligst velg en tittel for denne posten";
$lang["Post Blog to"] = "Post Blog til";
$lang["Choose Subject"] = "Velg emne";
$lang["Post"] = "Post";
$lang["Update Complete"] = "Oppdatering Fullført";
$lang["Can not delete this subject.  Blog data exists"] = "Kan ikke slette dette emne. Blog data finnes";


#################################################
## SHOPPING CART MODULE					           ##
#################################################

// shopping_cart.php
// --------------------------------------
$lang["Shopping Cart: Main Menu"] = "Handlevogn: HovedMenu";

// These three make up the sentence "You currently have [NUMBER] products in [NUMBER] categories"
$lang["You currently have"] = "Du har for tiden";
$lang["products in"] = "produkter i";
$lang["categories"] = "kategorier";


$lang["Category Names"] = "Kategori navn";
$lang["Add New Products"] = "Legg til nye produkter";
$lang["Find/Edit Current Products"] = "Søk/rediger aktive produkter";
$lang["Shipping Options"] = "Transport alternativer";
$lang["Tax Rate Options"] = "Skattesats alternativer";
$lang["Payment Options"] = "Betalings alternativer";
$lang["Business Information"] = "Forretnings Informasjon";
$lang["Display Settings"] = "Visnings Innstillinger";
$lang["Privacy Policy"] = "Kjøpsvilkår";
$lang["Shipping Policy"] = "Leveringsvilkår";
$lang["Returns/Exchanges Policy"] = "Angrefrist/Returvilkår";
$lang["Other Policies"] = "Andre Vilkår";
$lang["View Online Orders/Invoices"] = "Vis On-line Ordre/Fakturaer";

// categories.php
// ---------------------------------
$lang["Shopping Cart: Category Setup"] = "Handlevogn: kategori oppsett";
$lang["Current Categories"] = "Aktive kategorier";
$lang["Add New Category"] = "Legg til ny kategori";
$lang["New Category Name"] = "Ny kategori navn";
$lang["Add Category"] = "Legg til kategori";
$lang["To delete a category"] = "For å¨slette en kategori, klikk på [ slett ] knappen på side av navnet i 'Aktive kategorier' boksen på venstre.";


// products.php
// ---------------------------------
$lang["Shopping Cart: Add New Product"] = "Handlevogn: Legg til Nytt Produkt";
$lang["No Image"] = "Ingen bilde";
$lang["SAVE PRODUCT"] = "LAGRE PRODUKT";
$lang["PRODUCT INFO"] = "PRODUKT INFO";
$lang["PRODUCT IMAGES"] = "PRODUKT BILDER";
$lang["PRICE VARIATION"] = "PRIS VARIASJON";
$lang["ADVANCED OPTIONS"] = "AVANSERTE ALTERNATIVER";
$lang["Part No. (SKU Number):"] = "Del Nr. (SKU Antall) :";
$lang["Unit Price:"] = "Enhetspris:";
$lang["Part Name (Title):"] = "Del# Navn (Tittel) :";
$lang["Catalog Ref Number:"] = "Katalog Ref Nummer:";
$lang["Duscription:"] = "Beskrivelse:";
$lang["Main Category:"] = "Hovedkategori:";
$lang["Shipping Charge (A):"] = "Fraktpris (A):";
$lang["Secondary Category:"] = "Sekundær Kategori:";
$lang["If you are using standard shipping"] = "Om Du bruker vanlig frakt, da det 'Fraktpris (A)' verdi er mengden i kroner som lader for anskaffelse av denne enkele sku - pr. qty bestilt. som. ";
$lang["Shopping Cart: Edit Product"] = "Handlevogn: Rediger Produkt";
$lang["Search Products"] = "Søk i produkter";

//Product Images
$lang["Select the thumbnail and full image that you wish to associate with this Sku Number."] = "Velg thumbnail og fult bilde som Du ønsker å forbinde med dette Sku Nummer.";
$lang["If you are not using thumbnails, do not worry, the system will automatically resize your full size image to the appropriate scale when applicable. However, image quality of the scaled thumbnail may suffer."] = "Om Du ikke bruker thumbnails, ikke bekymrer seg, system vil automatisk endre størrelse av Deres fult bilde størrelses til den passende målestokken når det er aktuelt. Imidlertid lider bildekvalitet fra målestokks thumbnail.";
$lang["Thumbnail Image:"] = "Thumbnail bilde:.";
$lang["Full Size Image:"] = "Fult bilde størrelses:";
$lang["Note: Thumbnail images should be no more than 125px wide."] = "Anmerkning: er Thumbnail avbilder ikke mer enn 125px vid.";
$lang["Full Size Images should be no more than 275px wide for optimal display within your web Site."] = "Full størrelses av bilder må ikke være størrer en 275px i vidde for å få beste resultat.";
$lang["Image height is flexible."] = "Bilde høyde er fleksibel.";
$lang["Image Preview Window"] = "Forhåndsvis Vindu";

//Price Variation
$lang["Sub-Category"] = "Sub-Kategori";
$lang["Variant"] = "Variant";
$lang["Show me what this looks like in operation and how the variant set-up works."] = "Show me what this looks like in operation and how the variant set-up works.";

//Advanced Options
$lang["Charge Tax for this product?"] = "beregn mva for dette produktet?";
$lang["Charge Shipping for this product?"] = "Fraktpris for dette produktet?";
$lang["Security Code:"] = "Sikkerhetskode:";
$lang["Public"] = "Offentlig";
$lang["Attachment Page (Detail Page):"] = "Vedlegg side (detalje side) :";
$lang["Recommend this product"] = "Anbefal dette produktet i løpet av<BR>vis/redigerer handlevogn?:";
$lang["Recommended Products like this one:"] = "Anbefal Produkter som denne:";
$lang["Enter multiple sku numbers seperated by comma"] = "Legg inn i flere sku nummer separert med komma";
$lang["When customers add this product to their cart, require Form Data from:"] = "Når kunder legger til dette produktet til deres handlevogn, krever dette form Data fra:";
$lang["Per Qty"] = "Pr. Qty";
$lang["Ignore Qty"] = "Ignorerer Qty";
$lang["Purchase of this Sku allows your customer to download the following file:"] = "Bestilling av denne Sku gi kunde mulighet å laste ned følgende fil:";
$lang["Display this Product"] = "Vis dette Produktet";
$lang["Inventory Count:"] = "Inventar Opptelling:";
$lang["Additional Category Association:"] = "Ytterligere kategori forbindelse:";
$lang["Special Tax Rate:"] = "Spesiel moms sats:";
$lang["Searchable Keywords"] = "Søkbar nøkkelord (vises Ikke besøkende; brukt for produkt stikkordletinger) :";


// search_products.php
// ---------------------------------

$lang["Shopping Cart: Find/Edit Product"] = "Handlevogn: Søk/Redigerer Produkt";
$lang["Edit/Search For Products"] = "Redigerer/Søk for Produkter";
$lang["Edit Sku"] = "Redigerer Sku";
$lang["Find"] = "Søk";
$lang["Search For"] = "Leting For";
$lang["Search Results"] = "Søk resultater";
$lang["Edit Product Data"] = "Redigerer produkt Data";
$lang["Delete Product"] = "Slett Produkt";


// shipping_options.php
// ---------------------------------
$lang["Shopping Cart: Shipping Options"]  = "Handlevogn: Frakt Alternativer";
$lang["Choose the Shipping Option you wish to utilize for this shopping cart system:"] = "Velger transportav Alternativet som Du ønsker utnytte for dette handlende kjerresystem:";
$lang["Standard Shipping (Per Sku)"] = "Standard Frakt (Pr. Sku)";
$lang["Charge By Order Sub-Total"] = "Beregn etter Sub-Total av ordre";
$lang["Use Custom PHP Include"] = "Bruk Custom PHP Include";
$lang["Offline/Manual Calculation"] = "Offline/Manual Calculation";
$lang["Save Shipping Options"] = "Lagre frakt alternativer";
$lang["SET PRICING GRID, IF ORDER SUB-TOTAL IS..."] = "SETTER PRIS OVERSIKT, OM ORDRE SUB-TOTAL ER...";
$lang["Greater Than"] = "Størrer en";
$lang["And Less Than"] = "Og Mindre En";
$lang["Shipping Price"] = "Frakt pris";

// tax_rates.php
// ---------------------------------

$lang["Shopping Cart: Tax Rate Options"] = "Handlevogn: moms alternativer";
$lang["To Add a tax rate"] = "Legg til en momssats, velg en fylke, provins, og/eller land og går inn i database momssats av omsetningsavgift lade for itemsdbsdbsnshipped til den statlig.\\N\\NTO Sletter en skattesats, velger ut en for tiden useddbsdbsnstate og forlater skattesatstomromet.";

//One sentence split into three parts
$lang["When visitors purchase items from your Site"] = "Når besøkende kjøper ting fra din Side";
$lang["and select delivery to any of the below-listed areas,"] = "og velger levering til noe av de under-opplistete områdene,";
$lang["they will be charged the tax percentages you specified."] = "blir moms persentage du har spesifisert bruk.";

$lang["United States"] = "USA";
$lang["Canada"] = "Canada";
$lang["Add/Delete Tax:"] = "Legg til/Slet moms:";
$lang["Tax Rate"] = "Momssats";
$lang["Add/Delete Tax Rate"] = "Legg til/Slett Momssats";
$lang["State/Province"] = "Fylke";
$lang["There are currently no states in use."] = "Det er ingen fylke i bruk.";
$lang["International Taxes"] = "Internasjonale Skatter";
$lang["Note: You must enter a valid VAT/GST registration number to charge and collect VAT/GST taxes."] = "Anmerkning: går Du inn i et gyldig VAT/GSTregistreringsantall lade og samler VAT/GSTskatter.";
$lang["Registration Number:"] = "registrerings nummer:";
$lang["Save Tax Options"] = "Lagre skatt instillinger";
$lang["Tax Rate Table Updated."] = "Skattesats tabell er Oppdatert.";
$lang["Country"] = "Land";
$lang["There are currently no countries in use."] = "Det er ingen land i bruk.";

// payment_options.php
// ---------------------------------

$lang["Shopping Cart: Payment Options"] = "Handlevogn: betalings alternativer";
$lang["What type of payment processing will you utilize"] = "Hvilken type betalings måte ønsker du å aktivere";
$lang["PayPal"] = "PayPal";
$lang["VeriSign"] = "VeriSign";
$lang["WorldPay"] = "WorldPay";
$lang["Live Credit Card Processing"] = "Live Card Processing";
$lang["None"] = "None";
$lang["Offline Credit Card"] = "Offline (Credit Card)";
$lang["Check / Money Order"] = "Ordre med Sjekk/Penger";
$lang["No Processing (Catalog Only)"] = "Ingen betaling (Bare Katalog)";
$lang["Check/Money Order is included with credit card processing."] = "Ordre betalt kontakt er inkludert i kredittkortprosess. Sjekk<BR> om du har alle informasjon fylt ut korrekt i Firmainfo skjemael.";
$lang["If using credit card processing, select which cards you will accept:"] = "Om bruke kredittkortprosess, velger ut hvilke kort Du aksepterer:";
$lang["Choose Currency Type and Symbol"] = "Velg Valuta Type og Symbol";
$lang["Currency Type:"] = "valuta Type:";
$lang["Currency Symbol:"] = "valuta Symbol:";
$lang["Select Payment System (Online Processing)"] = "Velg betalingsSystem (On-line Prosess)";
$lang["If you are using online credit card processing"] = "Hvis du bruker et on-line kredittkortprosess, Samler du betaling fra kunder gjennom det følgende populære betalingssystemer:";

$lang["WorldPay Payment System"] = "Worldpay betalingssystem";
$lang["How to configure WorldPay for use with your site"] = "Hvordan still inn Worldpay for bruk med Deres Handlevogn";
$lang["Installation ID:"] = "Installasjon ID:";
$lang["Fix Currency Type"] = "Fix Currency Type";
$lang["Test Mode:"] = "Test Mode:";
$lang["PayPal Email:"] = "Paypal Epost:";
$lang["How to configure VeriSign Payflow Link for use with your site"] = "Hvordan forme Verisign Payflow Ledd for bruk med deres Sted";
$lang["VeriSign Partner ID:"] = "VeriSign Partner ID:";
$lang["VeriSign Login ID:"] = "VeriSign Login ID:";
$lang["Innovative Gateway Solutions"] = "Innovative Gateway Solutions";
$lang["Innovative Gateway"] = "Innovative Gateway";
$lang["Username"] = "Username";
$lang["Password"] = "Password";

$lang["I want to use online processing but I have a custom PHP include payment gateway"] = "Jeg ønsker å bruke on-line prosess men jeg har et custom PHP include betaling gateway";
$lang["system that I want to use in place of the others listed"] = "bruker system som jeg i stedet for det andre listet opp";

$lang["This will over-ride all processing for credit cards."] = "Tilsidesetter Dette all prosess for kredittkort. Systemet passerer enkelt styring til denne skrifttypen og det er forfatterens ansvar knyte tilbake inn i systemet etter prosess.";

$lang["I am using an SSL Certificate with my web site and when going to the checkout"] = "jeg bruker et SSL Sertifikat med mitt vevSted og ved dra til kassen";
$lang["the following https:// call should be made to the scripts to"] = "den følgende https: // rop lager til skrifttypene til";
$lang["to involke the SSL Cert."] = "til involke SSL Cert.";

//Full Sentence = "For example if you must use https://secure.[domain.com] to activate your SSL certificate, type https://secure.[domain.com] in the field above. DO NOT ADD ANY TRAILING FORWARD SLASHES. If you are unsure, consult your web developer."
$lang["For example if you must use <U>https://secure."] = "For eksempel, hvis du bruker <U>https://secure.";
$lang["</U> to activate your SSL certificate, type"] = "</U> for å aktivere din SSL sertifikat, skriv";
$lang["<B>https://secure."] = "<B>https://secure.";
$lang["</B> in the field above. DO NOT ADD ANY TRAILING FORWARD SLASHES. If you are unsure, consult your web developer."] = ". com</B> I feltet over. SKRIV INGEN FREM SKRÅSTREKER PÅ SLUTTEN. Hvis du er usikker, ta konkakt med din webdesigner.";

$lang["When displaying the final invoice to my customer, I want to execute a custom PHP include"] = "Når jeg vise den ferdige faktura til en kunde ønsker jeg å utføre en custom PHP include";
$lang["that processes data when the invoice is displayed."] = "som sender data når faktura viser.";


$lang["Custom Include File:"] = "Custom Include Fil:";
$lang["This include can be used to create custom processes that execute after products have been purchased from your system."] = "Denne include kan blir brukt for å lage custom prosesses som blir aktivert etter produkter er bestilt fra ditt handlevogn system.";
$lang["For example, you may wish to assign a new user automatically with a generated username and password to the Secure Users table after a membership payment."] = "For eksempel, Du ønsker å tildele en ny bruker automatisk en generert brukernavn og passord til de Sikere Bruker tabell etter en medlemskaps betaling.";
$lang["Save Payment Options"] = "Lagre betalings alternativer";


// business_information.php
// ---------------------------------

$lang["You will need to enter the address, phone number and whom to make a <U>check or money order</U>"] = "Du trenger å fyll ut adresse, telefonnummer og til hvem <U>betaling blir sendt til</U>";
$lang["payable to for your online store.  This will display to your site visitors at checkout time."] = "Betalbar til for din on-line forretning. Dette viser til din besøkende når dem betaler.";
$lang["Make Payable To:"] = "Betal til:";
$lang["Address:"] = "address:";
$lang["City"] = "Sted";
$lang["State/Province:"] = "Fylke:";
$lang["Zip/Postal Code:"] = "Postnummer:";
$lang["Country:"] = "Land:";
$lang["Phone Number:"] = "Telefonnummer:";

$lang["Statistics have shown that displaying this information on your site will increase trust<BR>among shoppers and therefore produce better sales results."] = "Vist Statistikk det som vise dene opplysningene om Deres Sted øker tillit<BR>blant handlende og produserer derfor bedre omsetning resulterer.";
$lang["When orders are placed on your website, they are saved in your order/invoice area."] = "Når du har mottat en bestilling fra din hjemmeside, blir denne lagret i ordre/faktura område.";

$lang["The system will automatically send you an <U>email notifing you of new orders</U>.  Please "] = "systemet sender Du automatisk et <U>email notifing Du av nye rekkefølger</U>. Behag ";
$lang["enter the email address where you wish these notifications to be sent. (Multiple email"] = "går inn i email adresse hvor Du ønsker at disse notifications sender. (Flerfoldig email";
$lang["addresses can be entered seperated by a comma)"] = "gått adresser inn i seperated ved et komma)";

$lang["Notification Email Address:"] = "Epost adresse for advarsel:";

$lang["If you are using the \"Allow Product Comments\" option, when <U>users submit comments</U>"] = "Om Du bruker det \"Tillater produktKommentarer\" alternativ, når <U>brukere forelegger kommentarer</U>";
$lang["about your products, the comments will be saved and an email generated to the email"] = "Angående deres produkter, kommentarene blir lagret og en epost generert til epost";
$lang["address below for verification. If the comments meet your approval, you can then allow"] = "adresse nedenfor for bekreftelse. Om kommentarene er godkjent av deg, så kan du tillater at ";
$lang["the comments to be made visible by the public.  This is done to prevent unsavory or"] = "kommentarene blir synlig til besøkende. Dette er gjort for å forhindre at kommentarer";
$lang["lude comments from being posted without your knowledge."] = "som gir problemer er lagt inn uten at du har godkjent dem:";
$lang["Verification Email Address:"] = "Bekreftelse av E-post adresse:";

$lang["After your customers purchase products from your site, they will receive an <U>email"] = "Etter kunder bestiller produkter fra deres hjemmeside, motta dem en <U>epost";
$lang["invoice</U> of the order for their records. The default header text is a simple thank"] = "faktura</U> Av rekkefølgen for deres protokoller. Standard header teksten er et enkelt takker";
$lang["you and is provided below.  You may modify this to say anything you wish.  The actual"] = "Du og gir under. Du modifiserer dette si noe som helst Du ønsker. Det aktuelle";
$lang["invoice with pricing breakdowns, tax, shipping, etc. will appear below this header text."] = "faktura med prisfastsettelsesdriftstanser, skatt, transport, osv. kommer fram under denne header tekst";

$lang["Save Business Info"] = "Lagre Firma Informasjon";


// display_settings.php
// ---------------------------------
$lang["Shopping Cart: Display Settings"] = "Handlevogn: Visnings innstillinger";

$lang["Shopping Cart Feature Options"] = "Handlevogn alternativer";
$lang["Page Header:"] = "Side header:";
$lang["Welcome To..."] = "Velkommen til...";
$lang["Show 'Client Login' Button in search column"] = "Vis ’Kunde Login’ Knapp i søk kolomme";
$lang["Allow 'Email to Friend' feature"] = "Tillat ’Sednt Epost til en Venn’ mulighet";
$lang["Allow 'Remember Me' feature"] = "Tillat ’Husk Meg’ mulighet";
$lang["Display Search Box"] = "Vis Søk boks";
$lang["Place 'Search Column' on which side of page"] = "Sted ’Søk kolom’ på hvilken side av side";
$lang["Left"] = "Venstre";
$lang["Right"] = "Høyre";
$lang["Display 'text linked' categories"] = "Vis ’tekst link’ kategorier";
$lang["Allow users to add product comments"] = "Tillat bruker å legge inn kommentar om produkter";
$lang["If using this option, place an email address to verify submissions in the 'Business Information' section."] = "Om bruke dette alternativet, plasserer en email adresse bekrefte submissions i det ’forretningsInformasjon’ kapittel.";
$lang["International Options:"] = "Internasjonale Alternativer:";
$lang["Choose State/Province Display Type:"] = "Velg fylke visnings type:";
$lang["U.S. States"] = "U.S. Stater";
$lang["Canadian Provinces"] = "Canadiske Fylker";
$lang["U.S. and Canada"] = "U.S. og Canada";
$lang["Text Field"] = "tekstfelt";
$lang["Do Not Display"] = "Ikke vis";
$lang["Specify Default 'Local' Countries:"] = "Spesifiser Standardverdi ’Lokal’ Land:";

$lang["By specifying a defualt, or 'local' country, customers will not be able to choose a country"] = "Ved å spesifisere en standar eller ’lokal’ land, har kunder ikke mulighet til å velge et land";
$lang["for their billing and shipping addresses. Instead, your shopping cart will assume that all customer orders are placed"] = "for deres faktura og leverings adresse. Istedenfor, ditt handlekurv antar at all ordre kommer fra ";
$lang["from the country you specify. To prevent confusion, you should make prominent mention of this on your website."] = "landet du har spesifisert. For å ungå forvirring, skriv om dette tydlig på hjemmeside.";


$lang["Search Result Settings"] = "Søk resultat Innstillinger";
$lang["User Defined Button:"] = "Definert Bruker Knapp:";
$lang["This button links to the 'More Information' page.  Leaving this blank will not show the button at all."] = "Linker Dunne knappen til det ’Mer Informasjon’ side. Forlater dette tomromet ikke viser knappen i det hele tatt.";
$lang["Show 'Add to Cart' button under thumbnail images instead of 'Buy Now!' on initial searches"] = "Show 'Add to Cart' button under thumbnail images instead of 'Buy Now!' on initial searches";

$lang["How should initial searches sort data"] = "Hvordan skal første letinger sorterer data";
$lang["Sku Number"] = "Sku Antall";
$lang["Catalog Ref Number"] = "Katalog Ref Antall";
$lang["Product Name"] = "produkt navn";
$lang["Product Price"] = "produkt pris";
$lang["Shipping Variable (B)"] = "Frakt Variabel (B)";
$lang["Shipping Variable (C)"] = "Frakt Variabel (C)";


$lang["Number of results to display on searches"] = "antall resultater vise på letinger";
$lang["Search Product"] = "letingsProdukt";
$lang["Browse Categories"] = "Browse Kategorier";
$lang["Category"] = "Kategori";
$lang["Product"] = "Produkt";
$lang["Sub-Total"] = "Sub-Total";
$lang["Checkout Now"] = "Til Kasse Nå";
$lang["Search Column Color Scheme"] = "letingssøylefargeProsjekt";
$lang["Header Background Color"] = "header bakgrunn farge";
$lang["Header Text Color"] = "Header tekst Farge";
$lang["Shopping Cart Background Color"] = "Handlevogn bakgrunn farge";
$lang["Shopping Cart Text Color"] = "Handlevogn tekst farge";
$lang["Or choose a pre-defined color scheme"] = "Eller velger et pre-definert farge";
$lang["Choose Scheme"] = "Velg Skjema";
$lang["America"] = "Amerika";
$lang["Classic"] = "Classic";
$lang["Earth"] = "Earth";
$lang["Movies"] = "Filmer";
$lang["Neon Green"] = "Neon Grøn";
$lang["Sports"] = "Sport";
$lang["Save Display Settings"] = "Lagre visnings innstillinger";


// privacy_policy.php
// ---------------------------------
$lang["Shopping Cart: Privacy Policy"] = "Handlevogn: Kjøpsvilkår";

$lang["Standardized eCommerce systems use a privacy policy to disclose how systems operate. The one provided here is generic"] = "N0rmert eCommerce systemer bruker en uforstyrrethetspolitikk avsløre hvordan systemer driver. Dun gide er her generisk";
$lang["and covers all technical issues regarding the operation of this shopping cart system such as session management and cookies."] = "og dekker alle tekniske utgivelser angÅende driften av dette handlende kjerresystem slik som sesjonsledelse og småkaker.";
$lang["You may wish to modify this policy statement to your particular business needs. It should disclose all information pertaining"] = "Kanksje du ønsker å redigere denne vilkår til deres egen behov. Som inneholder alle nødvendige informasjon";
$lang["to the use and storage of all data gathered from the checkout process."] = "til bruken og lagring av alle data samlet fra kasseprosessen.";

$lang["Save Privacy Statement"] = "Lagre Kjøpsvilkår";


// shipping_policy.php
// ---------------------------------
$lang["Shopping Cart: Shipping Policy"] = "Handlevogn: Fraktvilkår";

$lang["Your shipping policy informs your customers of how and when you ship the items that they purchase."] = "Deres leverings vilkår informerer deres kunder hvordan og når Du frakter produkter som er bestilt.";
$lang["Be as detailed as possible here and note any special charges that may occur."] = "Er som detaljert som mulig her og bemerker noen spesielle pris at forekommer.";

$lang["Save Shipping Policy"] = "Lagre Leverings vilkår";


// returns_policy.php
// ---------------------------------
$lang["Shopping Cart: Returns/Exchanges Policy"] = "Handlekurv: Angrefrist";

$lang["If your customers wish to return of exchange an item purchased online"] = "Hvis deres kunder ønsker returnere et produkt som er bestilt på nettet";
$lang["Save Returns/Exchanges Policy"] = "Lagre Angrefrist";


// other_policies.php
// ---------------------------------
$lang["Shopping Cart: Other Policies"] = "Handlevogn: andre vilkår";

$lang["Use this section to list other types of policies that you may have for your site."] = "Bruker dette kapittelet liste opp andre typer politikker har som Du for deres Sted.";
$lang["Remember to title each policy as it will displayed as is."] = "Hus til tittel hver politikk da det vil vist som.";
$lang["Save Policy Statement"] = "Lagre kjøps vilkår";


// view_orders.php
// ---------------------------------
$lang["View/Retrieve Orders"] = "Vis/Hent Ordre";

$lang["Displaying order numbers"] = "Vis ordre nummer";
$lang["Search results for"] = "søkresultater for";
$lang["Displaying all orders between"] = "Vis alle ordre mellom";
$lang["Download Results"] = "Last ned Resultater";
$lang["Print Results"] = "Skriv ut resultater";
$lang["New Search"] = "Nyt Søk";
$lang["Order Number"] = "Ordre nummer";
$lang["Order Date"] = "Ordre dato";
$lang["Order Time"] = "Ordre tid";
$lang["Customer"] = "Kunde";
$lang["Payment Method"] = "betalingsmåte";
$lang["Status"] = "Status";
$lang["Total Sale"] = "Total Salg";
$lang["Transaction ID"] = "Transaksjon ID";
$lang["Invoice"] = "Faktura";
$lang["No invoices where found matching your search. Please try again."] = "Ingen fakturaer funnet som passe din søk. Vennligst prøv igjen.";

// search.inc
// ---------------------------------
$lang["Search Orders"] = "Søk gjennom ordre";
$lang["Select your prefered search method"] = "Velg din forutrukket søkmåte";
$lang["Show order numbers"] = "Vis ordre nummere";
$lang["From"] = "Fra";
$lang["To"] = "Til";
$lang["Select how results should be sorted for viewing"] = "Velg hvordan resultater sorteres for visning";
$lang["Sort by"] = "Sorter ved";
$lang["Order Date"] = "Ordre Dato";
$lang["Order Number"] = "Ordre nummer";
$lang["Order by"] = "Sorter Ved";
$lang["Customer Name"] = "Kunde navn";
$lang["Total Sale"] = "Total Salg";
$lang["Payment Method"] = "Betalingsmåte";
$lang["Status"] = "Status";
$lang["Transaction ID"] = "Transaksjon ID";
$lang["Ascending"] = "oppstiging";
$lang["Descending"] = "Nedover";
$lang["Date range"] = "Dato rekkevidde";
$lang["Format"] = "Format";
$lang["Search for keywords"] = "Søk etter stikkord";

// view_invoice.php
// ---------------------------------

$lang["PURGE"] = "RENSER";
$lang["PRINT"] = "SKRIV UT";
$lang["EXIT"] = "UTGANG";
$lang["Payment Method"] = "betalingsmåte";
$lang["Order Status"] = "Ordre status";


#################################################
## EVENT CALENDAR    					           ##
#################################################

// event_calendar.php
// ---------------------------------

$lang["Event Calendar: Main Menu"] = "Aktivitetskalender: HovedMeny";
$lang["Search Events"] = "Søk Aktiviteter";
$lang["Display Settings"] = "Visnings innstillinger";
$lang["Category Setup"] = "Kategori oppsett";
$lang["Edit View"] = "Redigerer visning";

// add_event.php
// ---------------------------------
$lang["Add Calendar Event"] = "Legg til kalender aktivitet";

// build_month.php
// ---------------------------------

$lang["Add Event"] = "Legg til Aktivitet";

// category_setup.php
// ---------------------------------
$lang["Add/Modify Calendar Categories"] = "Legg til/Rediger kalender kategorier";
$lang["Create New Category"] = "Opprett ny kategori";
$lang["Add Category"] = "Legg til kategori";
$lang["Current Categories"] = "Aktive kategorier";

// display_settings.php
// ---------------------------------
$lang["Calendar Display Settings"] = "kalender visnings Innstillinger";

// search_events.php
// ---------------------------------
$lang["Search Event Calendar"] = "Søk i kalender";

// "Found [X] events that match your search criteria."
$lang["Found"] = "Funnet";
$lang["events that match your search criteria"] = "aktiviteter som passer din søk kriterier";

$lang["Sorry, no events where found for your search. Please try again."] = "Beklager, ingen aktiviteter funnet for din søk. Vennligst prøv igjen.";


// add_events_form.php
// ---------------------------------

$lang["Apply To"] = "Gjelder for";
$lang["THIS EVENT ONLY"] = "BARE DENNE AKTIVITET";
$lang["All occurrences of this event"] = "Alle forekomster av denne aktiviteter";
$lang["Save Event"] = "Lagre aktivitet";
$lang["Event Date"] = "Aktivitet Dato";
$lang["Start Time"] = "Start tid";
$lang["Event Title"] = "Aktivitet tittel";
$lang["Event Details (Description)"] = "Aktivitets detaljer (beskrivelse)";
$lang["Event Category"] = "Aktivitets kategori";
$lang["All"] = "Alt";
$lang["Security Code (Group)"] = "Sikkerhetskode (gruppe)";
$lang["Public"] = "Offentlighet";
$lang["When saving or changing this event, email a notice to the following email addresses"] = "Ved spare eller forandring av denne aktiviteter, email et legger merke til til den følgende email adresser";
$lang["Event Recurrence"] = "Aktivitet Reperteres";
$lang["No Recurrence"] = "Ingen Repetisjon";
$lang["Daily"] = "Daglig";
$lang["Weekly"] = "Ukentlig";
$lang["Monthly"] = "Månedlig";
$lang["Yearly"] = "Årlig";
$lang["Daily Pattern"] = "Daglig Mønster";

//full sentence = "This event should re-occur every [number] days"
$lang["This event should re-occur every"] = "Denne aktivitet skal reperteres hver";
$lang["days"] = "dager";

$lang["Weekly Pattern"] = "Ukentlig Mønster";

//full sentence = "This event should re-occur every [number] weeks on"
$lang["This event should re-occur every"] = "Denne aktivitet skal reperteres hver";
$lang["weeks on"] = "uker på";


$lang["Sunday"] = "Søndag";
$lang["Monday"] = "Mandag";
$lang["Tuesday"] = "Tirsdag";
$lang["Wednesday"] = "Onsdag";
$lang["Thursday"] = "Torsdag";
$lang["Friday"] = "Fredag";
$lang["Saturday"] = "Lørdag";
$lang["Monthly Pattern"] = "Månedlig Mønster";
$lang["This event should re-occur on the"] = "Denne aktivitet skal reperteres hver";
$lang["of each month"] = "av hver måned";
$lang["Yearly Pattern"] = "Årlig Mønster";
$lang["You have selected for this event to occurr every year on"] = "Du har valgt at denne aktivitet reperteres hvert år på"; // "every year on [X month]"

$lang["This event will start on the date of the selected 'Event Date' and continue for how long"] = "Denne aktivitet starter på den datoen av det valgte ut ’aktivitets Dato’ og fortsetter for hvor lenge"; //"?"
$lang["No End Date"] = "Ingen slutt dato";

//"End after [X] occurences."
$lang["End after"] = "Slutt etter";
$lang["occurrences"] = "forekomster";


// calendar_settings_form.php
// ---------------------------------

$lang["Color Scheme"] = "Farge skjema";
$lang["Header Text"] = "Header tekst";
$lang["Select Text Color"] = "Velg tekst farge";
$lang["Header Background"] = "Header bakgrunn";
$lang["Select Background Color"] = "Velg bakgrunn farge";
$lang["Pre-Defined Schemes"] = "Pre-Definert skjema";
$lang["Color Schemes"] = "Farge Skjemaer";
$lang["Default Standard"] = "Standard";
$lang["Reds"] = "Reds";
$lang["Allow authorized users to maintain personal calendars"] = "Tillat noen brukere å vedlikeholde personlige kalendere";

$lang["Initial Calendar Display Layout"] = "Første kalender visnings Layout";
$lang["Monthly"] = "Månedlig";
$lang["Weekly"] = "Ukentlig";
$lang["Allow the public to submit events for inclusion"] = "Tillat besøkere å legge inn aktiviteter"; // "?"
$lang["If so, where should confirmations be emailed to"] = "i så fall, hvilken epost skal bekreftelse blir sendt til"; // "?"
$lang["Color Preview"] = "Farge Forhåndsvisning";
$lang["Calendar Header"] = "kalender header";
$lang["Event Dates"] = "Aktivitets datoer";
$lang["Save Display Settings"] = "Lagre visnings innstillinger";


// event_search_form.php
// ---------------------------------
$lang["Search Event Calendar"] = "Søk i Aktivitetskalender";
$lang["Search for Keywords"] = "Søk for Stikkord";
$lang["Search in Month/Year"] = "Søk i Måned/År";
$lang["Search In Category"] = "Søk i Kategori";


// update_events_form.php
// ---------------------------------
$lang["Apply To"] = "Gjelder for";
$lang["THIS INDIVIDUAL EVENT ONLY"] = "KUN DENNE BEGIVENHET";
$lang["ALL OCCURRENCES OF THIS EVENT"] = "ALLE FOREKOMSTER AV DENNE BEGIVENHET";
$lang["Event Date"] = "aktivitetsdato";
$lang["Start Time"] = "start";
$lang["End Time"] = "slutt";
$lang["Security Code (Group)"] = "sikkerhetskode (gruppe)";
$lang["Use commas to seperate multiple email addresses"] = "bruk komma til å separere flere epost adresser";
$lang["Event Recurrence"] = "Reperterende aktivitet";
$lang["No Recurrence"] = "Ingen Gjentakelse";
$lang["Daily Pattern"] = "Daglig Mønster";

// "This event is a part of [X] other recursive events."
$lang["This event is a part of"] = "Denne aktivitet er en del av";
$lang["other recursive events"] = "andre reperterende aktiviteter";

$lang["Master Event"] = "Hoved aktivitet";
$lang["Recursive Event"] = "Reperterende aktivitet";

#################################################
## E-NEWSLETTER    					              ##
#################################################
// enewsletter.php
// ---------------------------------

$lang["eNewsletter System: Main Menu"] = "eNewsletter System: HovedMenu";

// "You have selected to delete the campaign [X]. Do you wish to continue with this action?"
$lang["You have selected to delete the campaign"] = "Du har valgt å slette denne kampanje";
$lang["Do you wish to continue with this action"] = "Gjør Du ønsker fortsette med dette tiltaket";

// "You have selected to send the campaign [X] to [X] people total. Do you wish to continue with this action?"
$lang["You have select to send the campaign"] = "har Du utvalgt sende kampagnen";
$lang["to"] = "til";
$lang["people total.  Do you wish to continue with this action"] = "antall personer. Ønsker du å fortsette med dette tiltaket";

$lang["Your campaign has been sent"] = "Din kampanje er sent"; // "!"
$lang["SENDING CAMPAIGN"] = "SENDER KAMPANJE";
$lang["This may take up to 30 seconds"] = "Dette kan ta opp til 30 sekund";
$lang["Create New Campaign"] = "Opprett ny kampanje";
$lang["HTML Emails"] = "HTML Epost";
$lang["TEXT Emails"] = "TEKST Epost";
$lang["Sent Date"] = "Sendt Dato";
$lang["Campaign Name"] = "Navn på Kampanje";
$lang["Data Table"] = "Data Tabell";
$lang["Recipients"] = "Mottakere";
$lang["Views"] = "Visninger";
$lang["Status"] = "Status";
$lang["View"] = "Vis";
$lang["Action"] = "Utført";
$lang["Pending"] = "avventer";
$lang["SENT"] = "SENDT";
$lang["View"] = "Vis";
$lang["Send Now"] = "Send nå";
$lang["Manually Unsubscribe Email Addresses"] = "Avmeld Epost adresser manuelt";

// create_campaign.php
// ---------------------------------

$lang["eNewsletter Campaign Setup Wizard"] = "eNewsletter kampanje oppsett Wizard";
$lang["Please select a table name to use for this campaign"] = "Vennligst velg et tabellnavn for denne kampanje";
$lang["Please enter a valid campaign name before continuing"] = "Vennligst oppgi et gyldig kampanjenavn før du fortsetter";
$lang["You need to select a template and content file in order to preview"] = "Du må velg en mal og fil med innhold for å bruke forhåndsvisning";
$lang["You need to select a template and content file in order to continue"] = "Du må velg en mal og fil med innhold for å kunne fortsette";
$lang["This may take a few seconds"] = "Dette ta noen få sekund";
$lang["STEP"] = "STEP";
$lang["ASSIGN CAMPAIGN NAME"] = "TILDEL KAMPANJENAVN";
$lang["A. Give this new campaign a name for easy identification on the campaign manager page"] = "A. Gi denne nye kampanjen et navn for lett identification på kampagnesjefsiden";
$lang["B. Choose a database table that contains the email addresses for this campaign:"] = "B. Velg en databasetabell som inneholder email adresser for denne kampagnen:";
$lang["Next"] = "Neste";
$lang["Field Names"] = "feltNavn";
$lang["MATCH REQUIRED FIELD DATA"] = "FYLL UT ALLE NØDVENDIG FELTER";

// "In order to build this campaign using ["X" dB Table], you will need to tell..."
$lang["In order to build this campaign using"] = "In order to build this campaign using";
$lang["you will need to tell the system which fields in the table correspond to the data needed by the eNewsletter system when sending this campaign"] = "Du forteller systemet svarer som felt i tabellen til dataene nødvendig ved eNewsletter system når sending av denne kampagnen";


$lang["A. Field containing <U>FIRST NAME</U> data"] = "A. Felt inneholder <U>FORNAVN</U> data";
$lang["B. Field containing <U>EMAIL ADDRESS</U> data"] = "B. Felt inneholder <U>E-POST ADRESSE</U> data";
$lang["C. Field containing the <U>EMAIL TYPE</U> data"] = "C. Felt inneholde det <U>E-POST TYPE</U> data";
$lang["If the user has HTML or TEXT preference"] = "Om brukeren har HTML eller TEKSTfortrinnsrett";
$lang["OWNER INFORMATION"] = "EIER INFORMASJON";
$lang["This campaign will arrive as an email to your list."] = "Denne kampanje blir lagt til som e-post til din liste.";
$lang["Please indicate what email address it will<BR>come from and the subject line"] = "vær så snill og angi hva email adresserer det vil<BR>komm fra og emneledningen"; // ":"

$lang["A. <U>From</U> email address"] = "A. <U>Fra</U> e-post adresse";
$lang["B. <U>Subject Line</U> of this campaign"] = "B. <U>Emne</U> av denne kampanjen";
$lang["Next"] = "Neste";
$lang["Newsletter Content Pages"] = "Nyhetsbrev innhold sider";
$lang["[NONE] Template Contains Content"] = "[INGEN] Mal inneholder innhold";
$lang["HTML CONTENT"] = "HTML INNHOLD";
$lang["Please select the template file and page name which contains the enewsletter content for<BR>sending the HTML version of this campaign"] = "Vennlgist velg en mal og side navn som inneholder enewsletter innhold for<BR>å sending HTML versjon av denne kampagnen";
$lang["Select the template to use with this campaign"] = "Velg mal du ønsker å bruke med denne kampagnen";
$lang["Browse Templates"] = "Bla Maler";
$lang["Select a page to use for your content"] = "Velg en side for å bruke for din innhold";

// "For those users that have selected to receive text only campaigns, please create the text that will..."
$lang["For those users that have selected to receive text only campaigns"] = "valgt For de brukerne som ut motta tekst bare kampagner";
$lang["please create the text that will be sent to those users as well as embedded in the header of the HTML newsletter in case of errors"] = "vær så snill og skap teksten som sendt til de brukerne i tillegg til nedlagt i header av HTML newsletter i tilfelle feil";

$lang["Creating the campaign does NOT send emails now."] = "Oppretting av kampanje sender INGEN e-post nå.";

$lang["Error: This campaign does not appear to have any email addresses to send to"] = "Feil: Dunne kampagnen kommer ikke fram ha noe email adresser sende til";
$lang["HTML Types found"] = "funnet HTML Typer";
$lang["TEXT Types found"] = "funnet TEKSTTyper";
$lang["DuvString"] = "Duvstring";
$lang["DuvString"] = "Duvstring";
$lang["HTML"] = "HTML";
$lang["TEXT"] = "TEKST";
$lang["Error Writing to Data Table (Could not create campaign): This is a programming error, consult with your webmaster."] = "Feil Skriver til Data Tabellfører (ikke opprett kampagne) : Dette er en programm feil, konsulterer med Din webmaster.";
$lang["Campaign Created"] = "Opprettet Kampagne";
$lang["Campaign Manager"] = "Kampagne Manager";

$lang["Your campaign has been added with pending status. You may now preview or"] = "Your campaign has been added with pending status. You may now preview or";
$lang["SEND your campaign from the \"Campaign Manager\" Interface."] = "SEND your campaign from the \"Campaign Manager\" Interface.";


// news-browse_templates.php
// ---------------------------------
$lang["Browse Website Templates"] = "Bla gjennom Website maler";
$lang["Select a category to browse from the drop down box above. When your find a template you like, simply click the template to continue."] = "Velg en kategori å bla fra drop down box. Når du har funnet en mal Du like, klikk på malen for å fortsette.";


// preview.php
// ---------------------------------

$lang["View HTML Preview"] = "Vis HTML Preview";
$lang["View TEXT Preview"] = "Vis TEKST Preview";
$lang["Close Preview Window"] = "Lukk Preview Vindu";

// send_now.php
// ---------------------------------
$lang["If you do not wish to receive this email, unsubscribe to this service now."] = "Om Du ikke ønsker motta denne epost, meldt deg av for denne tjenesten nå.";

// view_setup.php
// ---------------------------------
$lang["Visit our Website"] = "Besøk vår Nettsted";


#################################################
## DATABASE TABLE MANAGER   		              ##
#################################################
// database_tables.php
// ---------------------------------

$lang["Database Table Manager: Main Menu"] = "database tabell Manager; HovedMeny";
$lang["Create New Data Table"] = "Opprett nye Data Tabell";
$lang["Create a Search"] = "Opprett en Søk";
$lang["Delete a Table"] = "Slett en Tabell";
$lang["Modify Selected Table"] = "Modifiserer valgt Tabell";
$lang["Enter/Edit Record Data"] = "Tilgang/rediger protokollData";
$lang["Please select a user data table."] = "Vennligst velg en brukerdata tabell.";
$lang["Batch Authenticate Users"] = "Legaliserer flere brukere samtidlig";


// auth_users.php
// ---------------------------------

$lang["Authenticate Users : Add Authorized Users via Data Table"] = "Legaliserer Brukere: Legg til Tillatde Brukere via Data Tabellfører";
$lang["You must select a field name for all red selection boxes."] = "Velger Du ut et feltnavn for all rød utvelgingsesker.";
$lang["The second selection under 'user/company full name' is optional."] = "Andre utvelgingen under ’er bruker/selskap fult navn’ valgfri.";
$lang["This may take a few seconds..."] = "Dette ta et par sekunderL...L";
$lang["CAN NOT AUTHENTICATE USERS VIA TABLE"] = "LEGALISERER IKKE BRUKERE VIA TABELL";

$lang["This would indicate that you have not set-up a security code (group) OR"] = "Dette angir at Du har ikke arrangement en sikkerhetskode (gruppe) ELLER";
$lang["you have not created at least (1) authorized user."] = "Du skapt ikke minst (1) tillatd bruker.";

$lang["You will need to do these things before adding authenticated users via a table dump."] = "Gjør Du disse tingene før tilføying legalisert brukere via en tabellfylling.";
$lang["Current UDT Tables..."] = "Nåværende UDT Tabeller...";
$lang["SELECT DATA TABLE USAGE"] = "VELGER UT DATA TABELLFØRER PRAKSIS";
$lang["Select the User Dufined Table (UDT) that you wish to use as your authenticated user data:"] = "Velger ut den Brukeren-Dufinerte Tabellen (UDT) at Du ønsker bruke som deres legalisert brukerdata:";
$lang["Select Field Name"] = "Velger ut feltNavn";

// "CONFIGURE AUTHENTICATION DATA (AUTORIZE [X] USERS)."
$lang["CONFIGURE AUTHENTICATION DATA"] = "FORMER BEKREFTELSESDATA";
$lang["AUTHORIZE"] = "TILLATER";
$lang["USERS"] = "BRUKERE";

// "For each field needed to register an authenticated user, match the field name in [TABLE NAME] to the<BR>required authenticated user fields."
$lang["For each field needed to register an authenticated user, match the field name in "] = "registrerer For hver felt en legalisert bruker, passer feltnavnet i ";
$lang["to the<BR>required authenticated user fields."] = "til det<BR>krevd legalisert brukerfelt.";

$lang["Next"] = "Neste";
$lang["New Authenticated Users Added"] = "Tilføyd Nye Legaliserte Brukere";
$lang["Database Menu"] = "databaseMenu";
$lang["You can view and/or edit individual user settings through<BR>the Secure Users feature."] = "Du kan se på/eller rediger brukerinnstillinger gjennom<BR> Sikre Brukere module.";


// create_table.php
// ---------------------------------
$lang["Table Manager: Create New Table"] = "tabell Manager: Opprett Ny Tabell";
$lang["Error"] = "Feil";
$lang["BACK TO TABLE BUILD"] = "TILBAKE TIL TABELL BYGGER";

$lang["1. What is the name for this table"] = "1. Hva er navnet for denne tabellen";
$lang["NOTE: Do not use numbers or spaces in names; these are invalid"] = "ANMERKNING: bruk ikke antall eller å skille i navn; er disse ugyldig";
$lang["SQL table names. You may use underscores to represent spaces."] = "SQL tabell navn. Du bruker understrekning representere å skille.";
$lang["Table Name"] = "Tabell navn";
$lang["Invalid Table Name"] = "Ugyldig tabell navn";
$lang["2. How many fields will this table contain"] = "2. Hvor mange felt vil denne tabellen inneholder"; //"?"

$lang["The data you have entered is not formated properly"] = "gått dataene som Du inn i ikke formaterer skikkelig";
$lang["in order to create your table. Please check your"] = "for å opprette din tabell. Vennligst kontroler din";
$lang["setup and try again."] = "oppsett og prøv igjen.";
$lang["The last error calculation occurred on line item"] = "Siste feil kalkulasjon oppstått på et on-line produkt";

$lang["Create Table"] = "Opprett Tabell";
$lang["NOTE"] = "ANMERKNING";
$lang["Do not use numbers or spaces in names; these are invalid SQL field names."] = "Bruker ikke antall eller å skille i navn; er disse ugyldig SQL feltnavn.";
$lang["You may use underscores(_) to represent spaces."] = "Bruker Du understrekning (_) representere å skille.";
$lang["Novices who are unsure about what some of these options mean, simply input your field names leaving the default selection as is."] = "Er Nybegynnere som usikre om hva noen av disse alternativene betyr, enkelt tilfører deres feltnavn forlate standardutvelgingen som.";
$lang["This will insure proper operation."] = "Dette Forsikrer sikkelig drift.";
$lang["By default, a Primary Key field and Image field will also be added automatically to your table."] = "Ved standardverdi et Primært nøkkelfelt og avbildefelt tilføyd også automatisk til deres tabell.";
$lang["Field Name"] = "felt Navn";
$lang["Field Type"] = "felt Type";
$lang["Field Length"] = "felt Lengde";
$lang["Default Value"] = "Standard verdi";


// delete_table.php
// ---------------------------------

$lang["Table Manager: Delete Table"] = "tabellSjef: Slett Tabell";
$lang["WARNING"] = "VARSLE";

// "YOU ARE ABOUT TO DELETE THE TABLE [TABLE NAME] AND LOSE ALL RECORD DATA CONTAINED INSIDE OF IT."
$lang["YOU ARE ABOUT TO DELETE THE TABLE"] = "DU SLETTER SNART TABELLENE";
$lang["AND LOSE ALL RECORD DATA"] = "OG TAPER ALL RECORD DATA";
$lang["CONTAINED INSIDE OF IT."] = "INNEHOLDT INNE I DET.";
$lang["Are you sure you wish to do this now"] = "Er Du sikker Du ønsker gjøre dette nå"; //"?"
$lang["You did not select a table to delete."] = "Du valgte ikke ut en tabell slette.";
$lang["NOTE"] = "ANMERKNING";
$lang["THIS PROCESS CAN NOT BE REVERSED ONCE COMPLETED."] = "DENNE PROSESSEN REVERSERT IKKE EN ENESTE FULLFØRT GANG.";
$lang["ALL DATA WILL BE LOST WHEN THIS TABLE IS DELETED."] = "TAPT ALLE DATA NÅR DENNE TABELLEN SLETTES.";
$lang["YOU WILL HAVE ONE CHANCE TO CONFIRM, BUT ONCE YOU 'OK' THE CONFIRMATION, THE TABLE WILL BE DELETED"] = "HAR DE EN SJANSE BEKREFTE, MEN ETTER AT DE ’BIFALLER’ BEKREFTELSEN, STRØKET TABELLEN"; //"!"
$lang["Delete Table"] = "Sletter Tabell";
$lang["Delete Selected Table"] = "Sletter valgt ut Tabell";
$lang["Cancel Delete"] = "Angre Sletting";

// enter_edit_data.php
// ---------------------------------
$lang["Table Manager: Enter/Edit Record Data"] = "tabellSjef: Går INN I/Redigerer protokollData";
$lang["You have selected to delete this record."] = "Valgt Du ut slette denne protokollen.";
$lang["You will not be able to undo this choice."] = "Du er ikke kyndig til undo dette valget.";
$lang["Do you wish to continue with this action"] = "Gjør Du ønsker fortsette med dette tiltaket"; //"?"

$lang["Find Record"] = "funnProtokoll";
$lang["ADD_NEW"] = "ADD_NEW";
$lang["Add New Record"] = "Tilføyer Ny Protokoll";
$lang["Total Number of Records in Table"] = "Total antall Protokoller i Tabell";
$lang["Number of Records Found in Search"] = "antall Protokoller som Fant i Leting";
$lang["OPTION"] = "ALTERNATIV";
$lang["Previous"] = "Forrige";


// modify_table.php
// ---------------------------------

$lang["Table Manager: Modify Table"] = "tabellSjef: Modifiser Tabell";
$lang["Modify Table"] = "Modifiserer Tabell";
$lang["Update Complete"] = "Fullfører Oppdatering";
$lang["Field Name"] = "feltNavn";
$lang["Field Type"] = "feltType";
$lang["Field Length"] = "feltLengde";
$lang["INT"] = "INT";
$lang["DATE"] = "DATO";
$lang["Update Table"] = "oppdateringsTabell";
$lang["The data you have entered is not formated properly."] = "Gått dataene som Du inn i ikke formaterer skikkelig.";
$lang["Please check your setup and try again."] = "Vennligst kontroller din konfigurasjon og forsøk igjen.";
$lang["Add New Field to"] = "Tilføyer Nytt Felt til"; // "[TABLE NAME]"
$lang["Field Name"] = "feltNavn";
$lang["Field Type"] = "feltType";
$lang["Field Length"] = "feltLengde";
$lang["Default Value"] = "Standardverdi";
$lang["Rename Table"] = "Ombenevner Tabell";


// wizard_start.php
// ---------------------------------
$lang["Data-Table Search Wizard"] = "Data-Tabellfører Leting Wizard";
$lang["This may take a few seconds..."] = "Dette ta et par sekunder....";
$lang["ASSIGN SEARCH NAME"] = "TILDELER LETINGSNAVN";
$lang["Give this search a name."] = "Gir denne letingen et navn.";
$lang["This will be used as an identifier in the Page Editor, and displayed to site visitors when searching"] = "Dette brukt som en gjennkjenner i sideRedaktøren, og vist Plassere besøkende når gjennomsøking";
$lang["SELECT DATA TABLE USAGE"] = "VELGER UT DATA TABELLFØRER PRAKSIS";
$lang["Select the User Dufined Table (UDT) that this search will utilize"] = "Velger ut den Brukeren-Dufinerte Tabellen (UDT) at denne letingen utnytter";
$lang["Back"] = "Rygger";
$lang["CONFIGURE SEARCH FORM"] = "FORMER LETINGSFORM";
$lang["Configure the search criteria by which site visitors will search"] = "Former letingskriteriene som Plasserer besøkende ved gjennomsøker";
$lang["NOTE: You will be able to preview the form in the next step and make changes if you wish."] = "ANMERKNING: er Du kyndig til preview formen i det neste trinnet og lager forandringer om Du ønsker.";
$lang["You will be able to preview the form in the next step and make changes if you wish"] = "Du er kyndig til preview formen i det neste trinnet og lager forandringer om Du ønsker";
$lang["If you wish to utilize a keyword search, select which fields should be searched."] = "Om Du ønsker utnytte en stikkordleting, velger ut hvilke felt gjennomsøker.";
$lang["DROP DOWN BOX SELECTION FIELDS"] = "DRÅPE NED ESKEUTVELGINGSFELT";
$lang["Fields selected here will display all records within as options in a drop down box."] = "Felt som valgt ut viser her alle protokoller innenfor som alternativer i en dråpe ned eske.";
$lang["VERIFY SEARCH FORM"] = "BEKREFTER LETINGSFORM";
$lang["This is exactly the form site visitors will see when using this search."] = "Er Dette nøyaktig formstedsom besøkende ser ved bruke av denne letingen.";
$lang["Click the back button to make any changes."] = "Klikker bakknappen lage noen forandringer.";
$lang["All Fields"] = "Alle Felt";
$lang["SEARCH"] = "LETING";
$lang["Search by Keyword"] = "Leting ved Stikkord";
$lang["Separate multiple keywords by spaces"] = "Separerer flerfoldige stikkord ved å skille";
$lang["Detail Search"] = "detaljert søk";
$lang["Define Search Method"] = "Definerer søk metode";
$lang["Keyword Only"] = "Stikkord Bare";
$lang["Selections Only"] = "Utvelginger Bare";
$lang["Keyword AND Selections"] = "Stikkord OG Utvelginger";
$lang["Keyword OR Selections"] = "Stikkord ELLER Utvelginger";
$lang["Search Now"] = "Leting Nå";
$lang["Back"] = "Rygger";
$lang["SEARCH RESULTS DISPLAY"] = "LETINGSRESULTATUTSTILLING";
$lang["There are two steps used when displaying the results of a search."] = "Er det to trinn som brukt ved vise av av resultatene av en leting.";
$lang["The first data displayed is called the 'Initial Results', and displays the selected field data in a chart format."] = "Heter første viste dataene de ’Første Resultatene’, og viser den valgte ut feltdata i et diagramformat.";
$lang["At that point, site visitors may select to <I>View Details</I>, which displays the 'Details Page'."] = "Velger på det tidspunkt stedbesøkende ut til <I>Vis Spesifikasjoner</I>, som viser på ’detaljeside’ .";
$lang["This page shows more detailed information about the choosen record."] = "Denne siden viser flere detaljer med informasjon om valgt record.";
$lang["Select for each field when and where it's value should be displayed during the above process"] = "Velger ut for hver felt når og hvor er det verdi viser i løpet av det ovenfor prosess";
$lang["Field Name"] = "feltNavn";
$lang["Display Setting"] = "Innstille Utstilling";
$lang["Don't Display"] = "Viser Ikke";
$lang["Initial Results"] = "Første Resultater";
$lang["Details Page"] = "detaljSide";
$lang["Display on Both"] = "Utstilling på Begge";
$lang["DETAIL VIEW SETUP AND SECURITY"] = "VIS DETALJER OPPSETT OG SIKKERHET";
$lang["Select the display format (look and feel) of the 'Details Page'"] = "Velger ut utstillingsformatet (titt og føler) av det ’detaljSide";
$lang["Standard (Default)"] = "Normal (default)";
$lang["Custom PHP Include"] = "lagetr på bestilling PHP Inkluderer";
$lang["Select a security code (group) required to access this search"] = "Velger ut en sikkerhetskode (gruppe) krevd komme til denne letingen";
$lang["Public is Default"] = "er Offentlighet Standard#";
$lang["Build Search Now"] = "Bygger Leting Nå";
$lang["Search Creation Complete"] = "Fullfører letingsSkapelse";
$lang["Database Menu"] = "databaseMenu";
$lang["Use the 'Searchabe Database' object in the page editor to place your search on a site page."] = "Bruker det ’Searchabe Database’ objekt i sideredaktøren plassere deres leting på en stedside.";


#################################################
## SECURE USERS MODULE     		              ##
#################################################
// security.php
// ---------------------------------
$lang["Page/Product Security"] = "Side/produkt sikkerhet";
$lang["Authorized Users"] = "Autoriserte brukere";
$lang["Create New User"] = "Opprett ny bruker";
$lang["Current Authorized Users"] = "Aktive autoriserte brukere";
$lang["Select User"] = "Velger ut Bruker";
$lang["Security Codes"] = "sikkerhetskoder";
$lang["Create New Security Code (Group)"] = "Opprett ny sikkerhetskode (group)";
$lang["Name"] = "Navn";
$lang["Create Group"] = "Opprett Gruppe";
$lang["ACTION"] = "TILTAK";
$lang["Current Security Codes (Groups)"] = "Nåværende sikkerhetskoder (groups)";
$lang["Select Code"] = "Velger ut Kode";
$lang["How does this module work"] = "Hvordan fungerer denne module";
$lang["Click Here"] = "Klikk Her";


// security_create_user.php
// ---------------------------------

$lang["Create New Authorized User"] = "Opprett ny autoriserte bruker";
$lang["You have selected to delete this authorized user."] = "Valgt Du ut slette denne tillatde brukeren.";
$lang["THIS PROCESS CAN NOT BE REVERSED"] = "DENNE PROSESSEN REVERSERT IKKE";
$lang["Select OK to DELETE this user now."] = "Velg OK FOR Å SLETTE denne bruker nå.";
$lang["Save Changes"] = "Lagre endringer";
$lang["Delete User"] = "Sletter Bruker";
$lang["Authentication Info"] = "Bekreftelses Info";
$lang["User Info"] = "Bruker Info";


// shared/sec_user_form.inc
// ---------------------------------
$lang["User/Company Full Name"] = "Bruker/Selskap Fult Navn";
$lang["User/Company Email Address"] = "Bruker/Selskap Email Adresse";
$lang["Assigned Username"] = "Tildelt Brukernavn";
$lang["Assigned Password"] = "Tildelt Passord";
$lang["Expiration Date"] = "Utgått dato";
$lang["Login Redirect Page"] = "Omdiriger Login Side";
$lang["(Module) Shopping Cart"] = "(module) Handlevogn";
$lang["What site page should this user be sent to upon login?"] = "Til hvilke side øsnker du å videresend denne bruker ettet han har logget seg på?";
$lang["Select the security codes (groups) this user should have access to"] = "Velg sikkerhetskodene (grupper) denne brukeren har tilgang til";
$lang["There are currently no security codes (groups) created"] = "Det er ingen sikkerhetskoder (grupper) opprettet"; //"!"

$lang["All authorized users must be associated with a security group."] = "Alle autoriserte brukere må være tilknyttet et sikkerhetsgruppe.";


// shared/sec_user_form.inc
// ---------------------------------
$lang["(Optional) If you wish for this user to be remembered automatically when using the<BR>shopping cart system, please fill out all the customer data below."] = "(Optional) Om Du ønsker for denne brukeren å huske automatisk ved bruke av det<BR>handlevogn system, fylle du ut alle kunde informasjon nederfor.";
$lang["Billing Information"] = "faktura Informasjon";
$lang["First Name"] = "fornavn";
$lang["Last Name"] = "etternavn";
$lang["Company Name"] = "firma";
$lang["Optional"] = "Valgfri";
$lang["Address"] = "Adresse";
$lang["No PO Boxes"] = "Ingen postboks";
$lang["City/Town/Locality"] = "sted/by";
$lang["Region or Province/State/District"] = "Fylke";

$lang["Country"] = "Land";

$lang["Postal/Zip Code"] = "Postnummer";
$lang["Home Phone Number"] = "Telefonnummer hjem";
$lang["Country Code"] = "landkode";
$lang["Email Address"] = "Epost adresse";
$lang["INVALID EMAIL ADDRESS"] = "UGYLDIG EPOST ADRESSE";
$lang["Shipping Information"] = "Frakt Informasjon";
$lang["First Name"] = "Fornavn";
$lang["Last Name"] = "Etternavn";
$lang["Company Name"] = "Firmanavn";
$lang["Optional"] = "Valgfri";
$lang["Address"] = "Adresse";
$lang["No PO Boxes"] = "Ingen postboks";
$lang["City/Town/Locality"] = "Sted/By";
$lang["Region or Province/State/District"] = "Fylke";
$lang["State Invalid"] = "Fylke Ugyldig";
$lang["Postal/Zip Code"] = "Postnummer";
$lang["Ship-To Phone Number"] = "Frakt til telefon nummer";
$lang["Country Code"] = "landKode";



#################################################
## CLIENT-SIDE DISPLAY ELEMENTS		           ##
#################################################
// object_write.php
// ---------------------------------
$lang["Get Directions"] = "Får Retninger";
$lang["Courtesy of"] = "Høflighet av";
$lang["Get Directions"] = "Får Retninger";
$lang["Courtesy of"] = "Høflighet av";
$lang["Email this page to a friend"] = "Email denne siden til en venn";
$lang["Sign-up Now"] = "Skilt-Opp Nå";
$lang["Search Products"] = "letingsProdukter";
$lang["Browse Categories"] = "Browse Kategorier";


// pgm-realtime_builder.php
// ---------------------------------

$lang["This page has been emailed to your friend"] = "Denne side ble sendt til din venn"; //"!"
$lang["Thank you"] = "Takk"; //"!"
$lang["Your message has been sent. Thank you."] = "Din melding er sendt. Takk.";


// pgm-blog_display.php
// ---------------------------------
$lang["Weblog Archives"] = "Weblog Arkiv";


// pgm-email_friend.php
// ---------------------------------
$lang["I found this web site that you might be interested in"] = "Jeg har funnet dette nettsted som kanskje er interessert i";
$lang["so I thought I'd email it to you..."] = "så jeg trodde at jeg kunne send en epost til deg...";
$lang["Just click on the link to see it!"] = "Bare klikk på linken for å se på dette!";
$lang["I found something you might want to see..."] = "Jeg har funnet noe som du ønsker å se på...";
$lang["Email this page to a friend"] = "Email denne siden til en venn";
$lang["Your Name"] = "Ditt navn";
$lang["Your Email Address"] = "Din E-post adresse";
$lang["Friends Email Address"] = "E-post adresse fra din venn";
$lang["Personal Message"] = "Personlig melding";
$lang["Send Now"] = "Send";


// pgm-form_submit.php
// ---------------------------------
$lang["The email address you entered is invalid or"] = "adresserer email Du som gått inn i er ugyldig eller";
$lang["You left a required field or fields blank."] = "Forlater Du et krevd felt eller felttomrom.";
$lang["Please enter the following data before continuing"] = "vær så snill og gå inn i de følgende dataene før fortsetting";
$lang["Auto Generated Form Email"] = "Generert Auto Form Email";
$lang["Email Address"] = "Email Adresse";

$lang["This message is auto-generated by your web site when the"] = "Denne melding er automatisk opprettet av ditt nettsted når det";
$lang["form is submitted by a site visitor on page"] = "sendes et skjema av en besøkende på side"; // "[Page Name]"
$lang["No need to reply"] = "Du trenger ikke å svare";

$lang["This data has been saved to the"] = "Denne informasjon er lagret i "; //"[Table Name]"
$lang["database table"] = "databasetabell";

$lang["Your site visitor received the custom response file"] = "Din besøkende på hjemmeside har mottat ditt svar fra fil"; // "[File Name]"
$lang["Website Form Submission"] = "Hjemmeside skjema forsendelse"; // This is default subject line for form emails.
$lang["Thank you for your form submission today! This email is to confirm the reception"] = "Takk for at du har fylt ut et skjema i dag! Denne e-post er en bekreftelse at vi har mottat";
$lang["of your recently submitted data."] = "denne epost.";
$lang["We received the following:"] = "Vi har mottatt følgende:";
$lang["Thank You"] = "Takk";
$lang["This message is auto-generated by our web site."] = "Denne melding er automatisk sendt ut fra vår nettsted.";
$lang["Please do not reply to this email."] = "Vennligst IKKE svar på denne epost.";


// pgm-numusers.php
// ---------------------------------
$lang["Visitors Currently Online"] = "Besøkende For tiden On-line";


// pgm-print_page.php
// ---------------------------------
$lang["THIS PAGE IS CURRENTLY UNDER CONSTRUCTION"] = "VI JOBBER MED DENNE SIDEN";
$lang["This Week in"] = "Denne uke i"; // "[Month]"
$lang["Page Visits"] = "side besøk"; // ": [#]"
$lang["More Info"] = "Mer Info";


// pgm-single_sku.php
// ---------------------------------
$lang["More Information"] = "Mer Informasjon";


// pgm-cal-confirm.php
// ---------------------------------
$lang["This event has been added to your calendar system."] = "Tilføyer Dunne begivenheten til Dures kalendersystem.";
$lang["It appears this event has already been added to your system."] = "Det ser ut at denne aktivitet er allerede lagt til ditt system.";

// pgm-cal-details.inc.php
// ---------------------------------
$lang["Print Details"] = "Utskrift detaljer";
$lang["Close Window"] = "Lukk vindu";
$lang["Event Date"] = "Aktivitets Dato";
$lang["Event Time"] = "Aktivitets Tid";
$lang["Event Details"] = "Aktivitet Detaljer";
$lang["More Details"] = "Flere detaljer";


// pgm-cal-submitevent.inc.php
// ---------------------------------
$lang["Private"] = "Privat";
$lang["Submit an Event"] = "Forelegger en Begivenhet";
$lang["Your Name"] = "Ditt navn";
$lang["Your Email Address"] = "Din E-post adresse";
$lang["Event Date"] = "Aktivitet Dato";
$lang["Event Category"] = "Aktivitet Kategori";
$lang["Start Time"] = "start tid";
$lang["Event Title"] = "Aktivitet Tittel";
$lang["Event Details"] = "Aktivitet Detaljer";
$lang["Submit Event"] = "Sendt inn aktivitet";
$lang["All fields are required to submit an event except Event End Time and Event Details."] = "Krever Alle felt forelegge en begivenhet unntar begivenhetssluttTid og aktivitet Detaljer.";


// pgm-cal-system.php
// ---------------------------------

$lang["Please Setup Calendar System Display Settings."] = "Vennligst konfigurer kalender system visning Innstillinger.";
$lang["Private"] = "Privat";
$lang["Your selected event has been deleted."] = "Din valgte aktivitet er slettet.";
$lang["You did not enter one or more required fields. Please modify your submission and try again."] = "Du gikk ikke inn i en eller flere krevde felt. Vær så snill og modifiser Dures submission og forsøk igjen.";
$lang["Event Added to your Calendar"] = "Tilføyd Begivenhet til Dures Kalender";
$lang["The following event was submitted to your calendar. To approve this event, click the approve link below."] = "Forelegge Dun følgende begivenheten Dures kalender. Godkjenne denne begivenheten, klikk det godkjenner ledd under.";
$lang["If you do not wish to add this event to your calendar, simply disregard this email."] = "Om Du ikke ønsker tilføye denne begivenheten til Dures kalender, overser enkelt denne email.";
$lang["Event Date"] = "Aktivitet Dato";
$lang["Event Category"] = "Aktivitet Kategori";
$lang["Event Title"] = "Aktivitet Tittel";
$lang["Start Time"] = "startTid";
$lang["End Time"] = "sluttTid";
$lang["Event Details"] = "Aktivitet Detaljer";
$lang["To approve, click the link below:"] = "klikker godkjenne, leddet under:";
$lang["THIS IS AN AUTO-GENERATED EMAIL FROM YOUR WEBSITE"] = "ER DETTE EN AUTO-GENERERT EMAIL FRA DERES WEBSITE";
$lang["Your submission has been sent to our calendar manager for approval."] = "Sender Dures submission til vår kalendersjef for godkjenning.";
$lang["Thank you"] = "mange takk";
$lang["Current View"] = "Nåværende Visning";
$lang["View"] = "Vis";
$lang["Submit an Event"] = "Forelegger en Begivenhet";
$lang["Detail Event Search"] = "detalje aktivitets søk";
$lang["Month"] = "Måned";
$lang["Current Category"] = "Nåværende Kategori";
$lang["In Category"] = "I Kategori";
$lang["Search Now"] = "Søk nå";
$lang["Submit a search to change categories."] = "Forelegger en leting forandre kategorier.";
$lang["Events for the Week of"] = "Begivenheter for Uke av"; // "[Month DD-DD]"

$lang["Events for"] = "Begivenheter for"; // "[month]"
$lang["that match your search for"] = "passer det Dures leting for"; // [Search Query]


$lang["your personal calendar"] = "Dures personlig kalender"; // [User's Name]
$lang["the category"] = "kategorien"; // [category selection]
$lang["located in"] = "plassert i"; // located in [category]/"your personal calendar"

$lang["Edit Event"] = "Redigerer Begivenhet";
$lang["Delete Event"] = "Sletter Begivenhet";
$lang["This is your private event."] = "Dette er din private aktivitet.";
$lang["No details available for this event."] = "Ingen detaljer er tilgjengelig for denne aktivitet.";
$lang["in category"] = "i kategori";
$lang["There where no events found for your selection or search"] = "funnet Dur hvor ingen begivenheter for Dures utvelging eller leting";
$lang["Please search for an event or select the day or week you wish to view."] = "Ingen aktiviteter funnet eller valgt for dag eller uke du ønsker å se på.";
$lang["Authorized user logged in"] = "Autorisert bruker logget på";
$lang["Indicates your private event"] = "Vises din private aktivitet";
$lang["No one else can view this event but"] = "Ingen andere kan se denne aktivitet en "; //[user's name]


// newsletter/index.php
// ---------------------------------
$lang["UNSUBSCRIBE FROM $THIS_URL EMAIL SERVICE"] = "MELDER UT FRA $THIS_URL EMAIL TJENESTE";
$lang["Please enter the email address where you wish NOT to receive future emails"] = "vær så snill og gå inn i email adresse hvor Du ønsker IKKE motta fremtidig emails";
$lang["Unsubscribe Now"] = "Meldt ut nå";

$lang["UNSUBSCRIBE FROM"] = "MELDT UT FRA"; // [url]
$lang["EMAIL SERVICE"] = "EPOST TJENESTE";

$lang["The email address"] = "epost adresse"; // [unsubscribed address]
$lang["is no longer subscribed to our services."] = "er ikke mer abonnert til våre tjenester.";

$lang["If you need to remove another email address from our subscription system"] = "Om Du fjerner enda en email adresse fra vårt abonnementsystem";
$lang["click here"] = "klikk her";

$lang["Visit"] = "Visitt"; // [url]
$lang["now"] = "nå";


// pgm-photo_album.php
// ---------------------------------
$lang["Available Album(s)"] = "Tilgjengelig Album(er)";

$lang["Current Album is"] = "Aktive Album";
$lang["Change Album"] = "Rediger Album";

$lang["To change albums, highlight your"] = "For å redigere  albumer, Merk av din"; // <br>
$lang["choice and click the 'Change Album' button."] = "valg og klikk på 'Rediger Album' ’ knaenpp.";

$lang["Prev"] = "Forige";
$lang["Next"] = "Neste";
$lang["There are currently no images in this album."] = "Det er ingen bilder i dette albumet.";



// pgm-secure_login.php
// ---------------------------------

$lang["The page you have requested requires security access."] = "Denne side krever at du bruke din login informasjon.";
$lang["Please enter your username and password now."] = "Vennligst skriv inn ditt brukernavn og passord nå.";
$lang["It appears your login does not grant you access to this page."] = "Det ser ut at din login informasjon ikke tillater deg på dette område.";
$lang["If you feel this is in error, please contact us for further assistance."] = "Om Du føler at dette er en feil, vennligst ta kontakt med oss for hjelp.";

$lang["Click here"] = "Klikk her";
$lang["to return to the home page."] = "for å gå tilbake til hjemmeside.";

$lang["Please Login"] = "Vennligst Login";
$lang["Username"] = "Brukernavn";
$lang["Password"] = "Passord";
$lang["Sorry, we do not recognize that username and password.<BR>Please check your spelling and try again."] = "Beklager, vi kjenner ikke denne brukernavn og passord.<BR>Vennligst kontroller din staving og forsøk igjen.";
$lang["It appears the username and password that you entered has expired."] = "Det ser ut om ditt brukernavn og passord har gått ut.";
$lang["Your access is no longer available."] = "Du har ingen tilgang lenger.";
$lang["Click here"] = "Klikk her";
$lang["to return to the home page."] = "tilbake til startsiden.";
$lang["Forget your password?"] = "Glemt ditt passord?";


// pgm-secure_manage.php
// ---------------------------------
$lang["Your login password does not match"] = "Din login passord stemmer ikke";
$lang["your verification password. Please re-enter."] = "Ditt passord. Skriv inn en gang til.";
$lang["One or more fields were left blank or are too short."] = "Var En eller flere felt venstre tomrom eller er også kort.";
$lang["All fields must have at least 5 characters."] = "Har Alle felt minst 5 karakterer.";
$lang["Your authentication data has been updated"] = "Din godkjenningsdata er oppdatert"; // "!"
$lang["Manage Authenticated User Account"] = "Rediger Sikker bruker konto";
$lang["Your Email Address"] = "Din Epost Adresse";
$lang["Login Username"] = "Login Brukernavn";
$lang["Login Password"] = "Login Passord";
$lang["Verify Password"] = "Bekreft Passord";
$lang["Update Your Data"] = "Oppdater Din Data";


// pgm-secure_remember.php
// ---------------------------------
$lang["Here is the username and password associated with your email address"] = "Her er brukernavn og passord som er tilknyttet til ditt epost adresse";
$lang["Username"] = "Brukernavn";
$lang["Password"] = "Passord";
$lang["This is an automated email from"] = "Dette er en automatisk epost fra"; // [server name]
$lang["Please DO NOT REPLY to this email."] = "Vennligst IKKE SVAR til denne epost.";
$lang["Customer data successfully located."] = "Kundedata plassert vellykket.";
$lang["You should receive an email within the next few minutes."] = "Du vil motta en epost innen noen få minut.";
$lang["Failed to locate email address; please try again."] = "Feil med å plassere epost adresse; vennligst forsøk igjen.";
$lang["Forgotten Login"] = "Glemt Login";
$lang["Please <u>enter your email address</u> in the space below."] = "Vennligst <u>skriv inn din epost adresse</u> nedenfor.";
$lang["We will locate your username and password in our database and instantly send an email to"] = "Vi søker etter ditt brukernavn og passord i vår database og sender denne pr. epost til ";
$lang["the address that matches your input."] = "adresse som passe din info.";
$lang["Find Now"] = "Søk nå";


// pgm-add_cart.php
// ---------------------------------
$lang["Please fill out the following information needed for this individual item"] = "Vennligst fyll ut følgende informasjon nødvendig for dette produkt";
$lang["Item"] = "Produkt";
$lang["Details"] = "Detaljer";
$lang["Details"] = "Detaljer";
$lang["Please fill out the following information regarding this product"] = "Vennligst fyll ut følgende informasjon angående dette produkt";
$lang["Continue"] = "Fortsetter";
$lang["ILLEGAL PRODUCT ADDITION DETECTED."] = "OPPDAGET ULOVLIG PRODUKTTILLEGG.";
$lang["UPDATED"] = "OPPDATERT";
$lang["Current Shopping Cart Contents"] = "Strøm Handle kjerreInnhold";
$lang["Shipping Information"] = "Frakt Informasjon";
$lang["Returns & Exchanges"] = "Returer & Utveksling";
$lang["Privacy Policy"] = "Kjøps vilkår";
$lang["Other Policies"] = "Andre vilkår";

$lang["Sub-total does not include tax"] = "Sub-Total inkluderer ikke skatt"; // <br>
$lang["and shipping charges, if applicable."] = "og transport lader, hvis relevant.";


$lang["Sub-Total"] = "Sub-Total";
$lang["Your shopping cart is currently empty."] = "Din handlekurv er tom.";
$lang["We also recommend the following product(s)"] = "Vi anbefaler også den følgende product(s)";


// pgm-checkout.php
// ---------------------------------
$lang["Customer Sign-in"] = "Kunde Skilt-I";

$lang["Billing & Shipping"] = "Faktura & Frakt"; // <br>
$lang["Information"] = "Informasjon";

$lang["Shipping Options"] = "Frakt Alternativer";
$lang["Verify Order Details"] = "Bekreft ordre detaljer";
$lang["Make Payment"] = "Betal";

$lang["Print Final"] = "Skriv ut ferdige"; // <br>
$lang["Invoice"] = "Faktura";


$lang["CUSTOMER SIGN-IN"] = "KUNDE REGISTRERING";
$lang["Select an option below so that we can recognize you."] = "Velger ut et alternativ under slik at vi anerkjenner Du.";
$lang["Shipping Information"] = "Frakt Informasjon";
$lang["Returns & Exchanges"] = "Returer & Utveksling";
$lang["Privacy Policy"] = "Kjøps vilkår";
$lang["Other Policies"] = "Andre vilkår";
$lang["New Customer"] = "Ny Kunde";
$lang["If you are a first time buyer select this option."] = "Om Du er en først tidskjHar Du anledningen registrere og blir en forutrekket kunde.";
$lang["You will have the opportunity to register and become a prefered customer."] = "You will have the opportunity to register and become a prefered customer.";
$lang["New Customer"] = "Ny Kunde";
$lang["Existing Customers, Login Now"] = "Finnes Kunder, Login Nå";
$lang["Username"] = "Brukernavn";
$lang["Unrecognized Customer"] = "Unrecognized Kunde";
$lang["Try Again"] = "Forsøk Igjen";
$lang["Verify Order"] = "Bekreft ordre"; //<br>
$lang["Details"] = "Detaljer";
$lang["STEP"] = "TRINN";
$lang["BILLING AND SHIPPING INFORMATION"] = "FAKTURA OG TRANSPORTAV INFORMASJON";
$lang["Please fill out all fields"] = "Vennligst fyll ut alle felter";
$lang["You will have a chance to verify and correct this information if necessary."] = "Har Du en sjanse bekrefte og retter denne informasjonen hvis nødvendig.";
$lang["Customer Sign-in"] = "Kunde Skilt-I";
$lang["Please double check that all information is correct."] = "Kunde Skilt-I";
$lang["SELECT YOUR METHOD OF PAYMENT"] = "VELG DIN MOTE AV BETALING.";
$lang["Choose your method of payment by clicking on the desired button."] = "Velg måte av betaling ved å klikke på den ønskede knappen.";
$lang["Currently we are only accepting Check or Money Order payments."] = "For tiden aksepterer vi bare Bankoverføring.";
$lang["We currently accept the following credit cards"] = "Vi aksepterer for tiden følgende kredittkort";
$lang["Mailing Address"] = "Post adresse";


// pgm-checkout.php
// ---------------------------------
$lang["Thanks"] = "Takk"; // [user name]!
$lang["Your email has been sent"] = "Din epost er sent";
$lang["A cool product I found..."] = "funnet et kult produkt som jeg"; // Default subject line of 'email product to friend' feature
$lang["Email Product"] = "Email Produkt";
$lang["You have left one or more required fields blank"] = "har Du til venstre en eller flere krevde felttomrom";
$lang["Please correct and re-submit your email"] = "Vennligst korriger og send eposten på nytt";
$lang["Required Fields"] = "Må fylles ut";
$lang["Your <u>Full</u> Name"] = "Ditt <u>Fullt</u> Navn";
$lang["Your Email Address"] = "Ditt epost adresse";
$lang["Friend's <u>First</u> Name"] = "Venns <u>Første</u> Navn";
$lang["Friend's Email Address"] = "Venns Epost Adresse";
$lang["Subject Line of Email"] = "Emne linje i epost";
$lang["Personal Message"] = "Personlig Budskap";
$lang["Email Type"] = "Email Type";
$lang["Yes, send me a copy of the email too."] = "Ja, jeg ønsker også en kopi av denne epost.";
$lang["Click Here to Return to"] = "Klikk her for å gå tilbake til"; //[product name]
$lang["Return To Checkout Login"] = "Tilbake til Kasse Login";
$lang["Failed to locate email address; please try again or login as a new customer."] = "Failed to locate email address; please try again or login as a new customer.";
$lang["Follow the instructions below to resolve your issue quickly."] = "Følg instruksjoner nedenfor for å fikse problemer.";
$lang["Find Username and Password for Login"] = "Søk brukernavn og passord for Login";
$lang["Your username and password was displayed on the invoice of your first order with us."] = "Din brukernavn og passord er skrivet ned på første faktura du har mottat fra oss.";
$lang["If you have the email or a printed copy handy, it may expedite your request."] = "Om Du har email eller en trykt kopi hendig, ekspederer det Dures anmodning.";
$lang["Otherwise, please enter your email address in the space below."] = "Ellers vær så snill og gå inn i Dures email adresse i å skille under.";
$lang["Thank you for being a valued return customer."] = "Være mange takk for av en vurdert returkunde.";
$lang["Find Now"] = "Søk nå";

$lang["We have received your request for a lost username and"] = "Vi har mottatt Din forespørsel for et glemt brukernavn og";
$lang["password and have located that information in our system."] = "passord og har funnet denne informasjon i vårt system.";
$lang["They are as follows"] = "Denne er som følgende";
$lang["Thank you for being a loyal prefered customer."] = "mange takk for at du er en lojal kunde.";
$lang["We look forward to continuing to serve you in the future."] = "Ser Vi frem til å fortsette tjene Du i fremtid.";
$lang["This is an automated email from"] = "Dette er en automatisk epost fra";
$lang["Please DO NOT REPLY to this email."] = "Vennligst ikke svar til denne epost.";


// pgm-more_information.php
// ---------------------------------
$lang["Email To A Friend"] = "Epost til en venn";

$lang["Add this product to your cart below"] = "Tilføyer dette produktet til Dures kjerre under";
$lang["under 'ordering options'."] = "under ’bestillingsav alternativer options’ .";

$lang["Product"] = "Produkt";
$lang["Price"] = "Pris";
$lang["Qty"] = "Stk";
$lang["Add To Cart"] = "Legg i handlevogn";
$lang["Details specific to this item will be asked when you add this product to your cart."] = "Spesifiserer særegent for denne tingen spurt når Du tilføyer dette produktet til Dures kjerre.";
$lang["More Information"] = "Mer Informasjon";
$lang["Zoom"] = "Zoom";
$lang["Customer Comments"] = "Kunde kommentar";

$lang["Be the first to"] = "hver første til å";
$lang["write a review"] = "skriver en anmeldelse";
$lang["of this product for other customers"] = "av dette produktet for andre kunder";

$lang["Write an online review"] = "Skriver en on-line anmeldelse";
$lang["and share your thoughts about this product with other customers."] = "og deler Dures tanker på dette produktet med andre kunder.";
$lang["If you like this, you may also like"] = "Om Du liker dette, liker du kanskje også";


// pgm-ok_comment.php
// ---------------------------------
$lang["This comment has already been added to the system or no longer exists."] = "Dunne kommentaren tilføyer allerede til systemet eller ikke mer finnes.";
$lang["CUSTOMER COMMENT ADDED"] = "KUNDE KOMMENTAR LAGT TIL";


// pgm-payment_gateway.php
// ---------------------------------
$lang["Customer Registration"] = "Kunde registrering";

$lang["Thanks"] = "Takk";
$lang["you are now registered as a prefered customer"] = "Du er nå registrert som kunde";

$lang["The next time you shop with us, you may login using your username and password for quicker checkout"] = "Neste gang du handler hos oss, bruker du din login informasjon for en rasker avhandling";
$lang["An error occurred when assigning your invoice number."] = "En feil har oppstått med tildeling av et fakturanummer.";
$lang["Please try again or contact the webmaster immediately."] = "Vennligst forsøk igjen eller ta kontakt med webmaster med en gang.";

$lang["The checkout system is configured to use a custom gateway include script named"] = "Kasse systemet er konfigurert å bruke et egen inkludert gateway script kalt"; //[filename]
$lang["but the file can not be found on the server."] = "men filen blir ikke funnet på serveren.";

$lang["Via 'Payment Options' in the system admin, make sure that you have a current include file selected and try again."] = "Via ’Betalings Alternativer' i system administrasjon, må du være sikkert å valgt et include fil og forsøk igjen.";
$lang["Connecting To VeriSign"] = "Koble til Verisign";
$lang["Secure Server"] = "Sikre Server";
$lang["Please Hold"] = "Vennligst vent";
$lang["If you are not connected automatically within 20 seconds"] = "Om Du ikke blir viderekoblet automatisk innenfor 20 sekunder";
$lang["Click Here"] = "Klikk Her";
$lang["Connecting To PayPal"] = "Koble Til Paypal";
$lang["Secure Payment Server"] = "Sikre betalings Server";

$lang["The checkout system is configured to utilize online credit card processing, however, there is no VeriSign"] = "Kasse systemet er konfigurert for å bruke et on-line kredittkort prosess, men det fins ingen Verisign";
$lang["information setup nor is there a"] = "informasjons oppsett og ingen";
$lang["custom gateway specified.  One of the other must be setup through 'Payment Options' to use the online credit card checkout system."] = "Custom gateway spesifisert. En av den andre må settes opp gjennom ’Betalings Alternativer' for å bruke det on-line kredittkort systemet.";
$lang["If you do not know what these things mean, login to the admin system, select 'Payment Options' in the Shopping Cart module"] = "Om Du ikke vet hva disse tingene betyr, login til admin system, velg ’Betalings Alternativer' i Handlevogn module";
$lang["and select 'Offline Processing' then save your settings."] = "og velg ’Offline Prosess’ og lagre din innstillinger.";
$lang["This should resolve your issue immediately."] = "Dette løser dit problem med en gang.";

// pgm-show_invoice.php
// -----------------------------------
$lang["Make Check/Money Order Payable to"] = "Skriv ut Sjekk/Kontanter til";
$lang["Order Date"] = "Bestillings dato";
$lang["Order Number"] = "bestillings nummer";
$lang["Mailing Address"] = "Postadresse";
$lang["Print this Page Now"] = "Skriv ut denne side nå!";
$lang["To download and save the file to your hard-drive, 'Right-Click' on Download Button and select 'Save Target As...'."] = "For å laste ned og lagre denne fil til din datamaskin, ’Høyre-Klikk’ på Nedlastings knapp og velg ’Lagre som...’.";

$lang["When the save dialog appears, make sure you"] = "Nor vindu for å lagre vises, vær sikkert at du";
$lang["remember where you save the file on your hard drive."] = "husk hvor du har lagret denne fil på din datamaskin.";

$lang["You will also receive an HTML email receipt of this invoice that contains this link as well in case"] = "Du også motta en kvittering pr. epost i HTML format av denne faktura som inneholder også denne link";
$lang["you encounter connection problems downloading the file now."] = "Motta du forbindelsesproblemer å laste ned denne fil nå.";
$lang["This order was just placed from your website."] = "Denne bestillingen ble nettopp generert fra din hjemmeside.";
$lang["If you need to retrieve the credit card information, please login and do so now."] = "Hvis du trenger tilgang til kredittkort informasjon, vennligst login og gjør det nå.";
$lang["CUSTOMER INVOICE COPY"] = "KUNDE - FAKTURA KOPI";


// pgm-write_review.php
// ---------------------------------
$lang["CLICK HERE"] = "KLIKK HER";
$lang["TO MAKE THIS POST LIVE."] = "PUBLISER DENNE MELDING.";
$lang["If you do not want to display this comment, simply delete this email"] = "Om du ønsker å ikke vise denne meldingen, sletter du denne epost";

$lang["A customer has submitted the following comments about"] = "En kunde har lagt inn følgende kommentare om";
$lang["the product"] = "produktet";


$lang["Your comment has been submitted."] = "Din melding er sendt.";
$lang["Click Here to Return to"] = "Klikk her for å gå tilbake til"; // [product name]

$lang["You have left one or more fields blank."] = "Noen felter er ikke fylt ut.";
$lang["Please correct and re-submit your review."] = "Vennligst rediger og sendt inn din anmeldelse på nytt.";
$lang["Star"] = "Poeng";
$lang["Stars"] = "Poeng";
$lang["Rate this Product"] = "Gi poeng til dette Produktet";
$lang["On a scale of 1-5, with 5 being the best"] = "På en målestokk av 1-5 med 5 som beste";
$lang["Comment Title"] = "Kommentar tittel";
$lang["Your Review/Comments"] = "Din Anmeldelse/Kommentar";
$lang["Your Name"] = "Ditt navn";
$lang["Where are you in the world"] = "Hvor er du i verden";
$lang["our review will be submitted to our staff and should be posted within 2-3 business days."] = "Anmeldelse blir sendt til redaktør og blir publisert innenfor 2-3 forretningsdager.";
$lang["Thank you"] = "Tusen takk";


// prod-billing_shipping.inc
// ---------------------------------

$lang["The state you selected to ship your order to does not appear to be valid."] = "Valgt staten som Du ut transportere Dures rekkefølge til ikke kommer fram være gyldig.";
$lang["Please correct and re-submit your information."] = "Vennligst rediger å sent din informasjon på nytt.";
$lang["The email address you provided is not a valid email address."] = "Adresserer email Du git er ikke en gyldig email adresse.";
$lang["Please correct and re-submit your information."] = "Behager riktig og ang.-forelegger Dures informasjon.";
$lang["Customer Registration"] = "kundeRegistrering";
$lang["Yes, I want you to remember my Billing &amp; Shipping Information the next time I purchase something."] = "Vil Ja jeg at Du hus min Faktura &amp; Transportere Informasjon det neste gang jeg anskaffer noe.";
$lang["Choose a password"] = "Velger et passord";
$lang["Verify your password"] = "Bekrefter Dures passord";
$lang["The passwords that you entered do not match each other. Please check the spelling and re-submit."] = "Gått passordene som Du inn i ikke passer hverandre. Behag kontroll stavingen og re-submit.";
$lang["You have elected to register as a customer but did not choose a password for your account. Please do so now."] = "Valgt Du registrere da en kunde men ikke valgte et passord for Dures konto. Vær så snill og gjør det nå.";
$lang["If you are not using the customer registration feature, you may leave the password fields blank"] = "Om Du ikke bruker kunderegistreringskjennetegnet, forlater Du passordfelttomromet";
$lang["Billing Information"] = "fakturaInformasjon";
$lang["$txt"] = "$txt";
$lang["$txt"] = "$txt";
$lang["First Name"] = "Fornavn";
$lang["Last Name"] = "Etternavn";

$lang["Company Name"] = "Firmanavn";
$lang["Optional"] = "Valgfri";
$lang["Address"] = "Adresse";
$lang["No PO Boxes"] = "Ingen Postboks";
$lang["City"] = "Sted";
$lang["Zip Code"] = "Postnummer";
$lang["State/Province"] = "Fylke";
$lang["Country"] = "Land";
$lang["Billing Phone Number"] = "Telefonnummer";
$lang["Email Address"] = "Epost adresse";
$lang["Used to email a copy of your invoice to you and the username for customer registration."] = "Brukt til email en kopi av Din faktura til Deg og brukernavn for kunderegistrering.";
$lang["to use Billing Information. Note, we do not ship to P.O. Boxes."] = "Bruke fakturaInformasjon. Anmerkning, vi transporterer ikke til P.O. Esker.";
$lang["Zip Code"] = "Postnummer";
$lang["Ship-To Phone Number"] = "Mottakers telefon nummer";


// pgm-cust_invoice.php
// ---------------------------------
$lang["Shipping & Handling"] = "Frakt";
$lang["BILLING INFORMATION"] = "FAKTURA INFORMASJON";
$lang["SHIPPING INFORMATION"] = "FRAKT INFORMASJON";
$lang["Product Name"] = "produktnavn";
$lang["Unit Price"] = "Enhetspris";
$lang["Quantity"] = "Antall";
$lang["Sub-Total"] = "Sub-Total";
$lang["Tax"] = "MVA";
$lang["Total"] = "Total";
$lang["EDIT"] = "EDIT";

// prod_offline_card.inc
// ---------------------------------
$lang["The total amount of your purchase"] = "Total beløp for din ordre";
$lang["will be charged to your credit card."] = "blir trekt fra ditt kredittkort.";

$lang["Name as it appears on card"] = "Navn som stå på kortet";
$lang["Credit Card Type"] = "kredittkort Type";
$lang["Credit Card Number"] = "kredittkort nummer";
$lang["Credit Card Expiration Date"] = "Dato kredittkort utgår";
$lang["Month"] = "Måned";
$lang["Security Code"] = "sikkerhetskode";
$lang["How to find your security code"] = "Hvordan finner Du din sikkerhetskode";


// prod_search_column.inc
// ---------------------------------
$lang["Welcome"] = "Velkommen";
$lang["Client Login"] = "Kunde Login";
$lang["Find Now"] = "Søk Nå";
$lang["Search Products"] = "Søk produkter";
$lang["Browse Categories"] = "Bla Kategorier";
$lang["Your cart is empty."] = "Handlevogn er tom.";
$lang["VIEW OR EDIT CART"] = "VIS ELLER REDIGER HANDLEVOGN";
$lang["Telephone Orders"] = "telefon ordre";
$lang["We Accept"] = "Vi akseptere"; // (the following credit cards)

$lang["We are currently not accepting online orders."] = "Vi aksepterer for tiden ingen bestillinger via nettet.";
$lang["We are currently only accepting check or money orders online."] = "Vi aksepterer for tiden bare bestillinger betalt kontant eller på forhånd.";
$lang["Returns & Exchanges"] = "Angrefrist/Returvilkår";
$lang["Privacy Policy"] = "Kjøpsvilkår";
$lang["Other Policies"] = "Andre vilkår";


// prod_search_template.php
// ---------------------------------
$lang["Buy Now"] = "Kjøp Nå";
$lang["Add to Cart"] = "Legg i handlevogn";
$lang["Related Products"] = "Relaterte Produkter";
$lang["Catalog"] = "Katalog";
$lang["Browse Category"] = "bla Kategori";



// start.php
// ---------------------------------
$lang["Search Results For"] = "Søk resultater for";
$lang["Displaying"] = "Vises";
$lang["Matches Found"] = "Treff funnet"; // "[X] Matches Found"
$lang["Sorry, no products were found that match your search criteria."] = "Beklager, ingen produkter funnet for søkeord du har oppgitt.";
$lang["Please try again or browse the suggested selections below."] = "Vennligst prøv igjen eller bla gjennom foreslåtte valg nedenfor.";
$lang["NEXT"] = "NESTE";
$lang["Welcome to"] = "Velkommen til";
$lang["Mailing Address"] = "Postadresse";



#################################################
## WEBMASTER MENU             				     ##
#################################################

// webmaster.php
// ---------------------------------
$lang["USERNAME/PASSWORD NOT CHANGED"] = "BRUKERNAVN/PASSORD IKKE ENDRET";

$lang["Your username or password change"] = "Din brukernavn eller passord er oppdatert";
$lang["could not be verified. Please try again."] = "er ikke Bekreftet. Vennligst prøv igjen.";

$lang["Your Administrative Username and Password has been changed"] = "Din Administrative brukernavn og Passord har endret seg";
$lang["Administration Login"] = "Administrasjon Login";
$lang["New Username"] = "Ny brukernavn";
$lang["Verify New Username"] = "Bekreft nytt brukernavn";
$lang["New Password"] = "Nytt passord";
$lang["Verify New Password"] = "Bekreft nytt passord";
$lang["Change Username/Password"] = "Rediger brukernavn/passord";
$lang["Select User"] = "Velg Bruker";
$lang["Multi-User Access"] = "Flerbrukertilgang";
$lang["Edit User"] = "Rediger";
$lang["Default Meta Tag Data"] = "Standard Meta Tag Data";
$lang["Restart Quickstart Wizard"] = "Omstart av Quickstart Wizard";
$lang["Language"] = "Språk";
$lang["Swap Language"] = "Bytt Språk";
$lang["Access Rights"] = "Tilgang Rettigheter";
$lang["Global Settings"] = "Globale Instillinger";
$lang["Meta Tag Data"] = "Meta Tag Data";
$lang["Miscellaneous Options"] = "Ekstra instillinger";
$lang["Disable Developer Mode"] = "Slå av Developer Mode";
$lang["Enable Developer Mode"] = "Slå på Developer Mode";

// global_settings.php
// ---------------------------------
$lang["Business Address"] = "Firma Adresse";
$lang["State"] = "Fylke";
$lang["Postal Code"] = "Postnummer";
$lang["Apt. / Suite"] = "Apt. / Etage";
$lang["Phone Number"] = "Telefon nummer";


// meta_data.php
// ---------------------------------
$lang["This will be displayed at the top of the browser window on all pages of your site."] = "Dette blir vist på toppet av nettleserens vindu på alle sider av Din hjemmeside.";

$lang["Web Site Description"] = "Beskrivelse av hjemmeside ";
$lang["This is a Meta Tag that helps search engines classify your web site."] = "Dette er et Meta tag som hjelper søkemotorer å klassifisere deres nettsted.";
$lang["This should be a small sentance that describes your site."] = "Dette skal være en liten tekst som beskriver ditt nettsted.";

$lang["Web Site Keywords"] = "Hjemmeside nøkkelord";
$lang["This is a Meta Tag that some search engines use to search your site with."] = "Dette er et Meta Merke som en letingsmaskiner bruker gjennomsøke Dures sted med.";
$lang["Please enter each keyword seperated by a comma."] = "Vær så snill og gå inn i hver stikkord seperated ved et komma.";
$lang["There is no need to use line feeds or carriage returns in the field."] = "Er det ingen bruker ledningsføder eller returer i feltet.";
$lang["Note: Indivdual Meta Tag Data can be edited from Page Properties while editing the page."] = "Husk: Indivduele Meta Tag Data kan endres fra Side egenskapper mens du redigere en side.";
$lang["Save Meta Tag Data"] = "Lagre Meta Tag Data";


// add_user.php
// ---------------------------------
$lang["has been added to your administrative users list."] = "er lagt til deres liste over administratorer.";
$lang["Admin User's Full Name"] = "Admin brukers fulle navn";
$lang["Login Username"] = "Login brukernavn";
$lang["Login Password"] = "Login passord";
$lang["Select the seperate <U>Modules</U> that this user should have access to"] = "Velg <U>Modulene</U> denne bruker har tilgang til";
$lang["Enable Basic Features"] = "Aktiver basis moduler";
$lang["Enable Advanced Features"] = "Aktiver avanserte moduler";
$lang["Select each <U>Site Page</U> this user should have access to"] = "Velg hver <U>Nettsted side</U> denne bruker har tilgang til";
$lang["Note: User will not be able to access these pages unless the Edit Pages module itself is enabled (above)."] = "Anmerkning: Bruker er ikke kyndig komme til disse sidene hvis ikker det Redigerer sidemodul seg muliggjører (above).";
$lang["Shopping Cart access options"] = "Handlevogn tilgangs muligheter";
$lang["Note: User must have access to Shopping Cart module itself (above)."] = "Pass: Bruk trenger FØRST tilgang til handlevogn module (overfor).";
$lang["View Invoices Only"] = "Vis bare fakturaer";
$lang["Select each <U>User Data Table</U> this user should have access to"] = "Velg hver <U>Bruker data tabell</U> denne bruker har tilgang til";
$lang["Cancel Create"] = "Avbrytt oppretting";
$lang["Create New User"] = "Opprett ny bruker";


// edit_user.php
// ---------------------------------
$lang["The settings for"] = "innstillinger for";
$lang["have been updated."] = "er oppdatert.";

$lang["Edit Administrative User"] = "Redigerer Administrativ Bruker";
$lang["You have selected to delete the user"] = "Du har valgt å slette denne brukeren";
$lang["Once you click OK, you can not undo this process."] = "Når Du klikker OK, kan du ikke angre denne handlingen.";
$lang["Are you sure you wish to delete this user"] = "Er du sikker på at du ønsker å slette denne brukeren?"; 
$lang["Cancel Edit"] = "Avbrytt Redigering";
$lang["Delete User"] = "Slett Bruker";
$lang["Update User"] = "Oppdater Bruker";

// Random Strings
// ---------------------------------
$lang["Backup/Restore"] = "Sikkerhetskopi";
$lang["Secure Users Menu"] = "Bruker Sikkerhet";
$lang["Site Backup / Restore"] = "Sikkerhetskopi";
$lang["Install Software Updates"] = "Innstaller oppdateringer";
$lang["Check for software updates"] = "Sjekk for oppdateringer";
$lang["Current Version"] = "Nåværende versjon";
$lang["Release Date"] = "Distribusjonsdato";
$lang["Changes in this build"] = "Endringer i denne versjonen";
$lang["On-Menu Pages"] = "På-meny sider";
$lang["Off-Menu Pages"] = "Av-meny sider";
$lang["Speed-Dial Pages Menu"] = "Hurtigvalg meny";
$lang["Note: You may assign a single Site Base Template that applies to your entire website via the <a href=#LINK#>Template Manager</a> feature."] = "Merk: du kan legge til en hovedmal for hele websiden din via <a href=#LINK#>Template Manager</a>";
$lang["To change the template for a specific page, edit the page, select page properties, and select the template from the drop down box."] = "For å endre malen for en spesifikk side, gå til endre side, velg sideegenskaper og velg malen fra nedtrekkslisten.";
$lang["Printable Page"] = "Printervennlig side";
$lang["Background"] = "Bakgrunn";
$lang["Click on an object above and drag it onto a drop zone for page placement."] = " Klikk på et objekt ovenfor og dra det inn i ett av feltene for plassering på siden.";
$lang["Click on an object below and drag it onto a drop zone for page placement."] = "Klikk på et objekt nedenfor og dra det inn i ett av feltene for plassering på siden.";
$lang["Please only use Alpha Numerical characters and Underscores."] = "Vennligst bruk kun bokstaver, tall (0-9) og (_).";
$lang["Media, document, and code files may be downloaded by clicking on the arrow next to the filename."] = "Media, dokument og filer med kode kan lastes ned ved å klikke på pilen ved siden av filnavnet.";
$lang["Image files can be viewed and saved by clicking the preview icon next to the filename."] = "Bildefiler kan forhåndsvises og lagres ved å klikke på ikonet forhåndsvis ved filnavnet.";
$lang["Indicates an image that should be reduced in filesize. This file causes slow load-times when viewing your web site."] = "Indikerer et bilde som bør reduseres i filstørrelse. Denne filen gjør det tregt å åpne websiden din.";
$lang["Images"] = "Bilder";
$lang["Rename"] = "Endre navn";
$lang["Documents, Presentations, and Adobe PDFs"] = "Dokumenter, presentasjoner og Adobe PDF";
$lang["Video Files"] = "Video Filer";
$lang["Spreadsheets and CSV files"] = "Regneark og CSV filer";
$lang["Custom web forms and text files"] = "Egendefinerte skjemaer og tekstfiler";
$lang["Custom HTML includes"] = "Egne HTML includes";
$lang["Custom HTML template files"] = "Egendefinerte HTML maler";
$lang["Custom PHP scripts"] = "Egendefinerte PHP Script";
$lang["Unclassified files"] = "Uspesifiserte filer";
$lang["Select the <U>Browse</U> button next to each filename to locate your local file for upload. <BR>When you are ready to start the upload operation, select <U>Upload Files</U>."] = "Velg knappen <u>Bla</u> ved siden av hvert filnavn for å hente fil på din maskin. Når du er klar for å starte opplastingen, velger du <u>Last opp filer</u>";
$lang["Upload Custom Template Folder (Zipped)"] = "Last opp mappe med maler (zippet)";
$lang["To upload a custom template"] = "For å laste opp egen mal";
$lang["Place all files(images,index.html,custom.css) into a folder and name the folder like this"] = "Plasser alle filer (bilder, index.html, custom.css) i en mappe og deretter navngir du folderen slik";
$lang["Category-Sub_Category-Color"] = "Kategori-Sub_Kategori-Farge";
$lang["Example"] = "Eksempel";
$lang["Zip the folder and upload it below"] = "Zip mappen og last den opp nedenfor";
$lang["After upload the template will be availible in the list of templates"] = "Etter opplasting er malen tilgjengelig i listen med maler";
$lang["Zipped Template Folder"] = "Zippet mappe for maler";
$lang["What is your site visitor supposed to enter or select for this field"] = "Hva skal dine besøkende fylle inn/velge for dette feltet";
$lang["In progress"] = "Jobber";
$lang["Complete"] = "Fullført";
$lang["No file selected!\nPlease choose a backup file from your hard drive."] = "Ingen fil valgt!\nVennligst velg en sikkerhetskopi fra din harddisk";
$lang["Website backup in progress..."] = "Backup utføres";
$lang["This process may take several moments."] = "Denne prosessen kan ta en stund";
$lang["Importing website backup file..."] = "Importerer sikkerhetskopi...";
$lang["This process may take several moments, depending on connection speed."] = "Denne prosessen kan ta noen minuter, avhengig av hastighet på forbindelse.";
$lang["User notes for this backup"] = "Brukers notater for denne sikkerhetskopien";
$lang["Site backup in progress. Please hold."] = "Sikkerhetskopiering utføres. Vennligst vent.";
$lang["Creating folder for this backup"] = "Oppretter mappe for denne sikkerhetskopien";
$lang["Writing backup info to text file"] = "Skriver informasjon til tekstfil";
$lang["Archiving site content and files"] = "Arkiverer innhold og filer";
$lang["Creating data table restoration file"] = "Oppretter data tabell for gjennoprettelsesfil";
$lang["Creating downloadable archive file"] = "Oppretter nedlastbar arkiv fil";
$lang["Inserting backup record into site log"] = "Oppretter sikkerhetskopi info i log filer";
$lang["Done"] = "Ferdig";
$lang["Restore from a previous backup"] = "Gjenopprett fra en tidligere sikkerhetskopi";
$lang["Note: When downloading backups, make sure to save the file with a '.tgz' extension NOT '.gz'"] = "Merk: Når du laster ned dikkerhetskopi, vær sikker på at du lagrer filen som '.tgz' og IKKE '.gz'";
$lang["Note: After backing up your site, please download the backup and delete it here for security purposes."] = "Merk: Etter å ha tatt sikkerhetskopi av nettsiden, må du huske å laste ned sikkerhetskopien og slette den herfra, dette av sikkerhetsmessige grunner.";
$lang["Backup Title"] = "Navn på sikkerhetskopi";
$lang["Backup Date"] = "Dato";
$lang["Backup Time"] = "Tid";
$lang["Are you sure you want to permanently delete this backup?"] = "Er du sikker på at du vil slette denne sikkerhetskopien for godt?";
$lang["Current website will be replaced with backup data."] = "Hjemmeside blir erstattet med sikkerhetskopi";
$lang["All unsaved data will be lost."] = "All data som ikke er lagret vil gå tapt.";
$lang["Are you sure you want to restore the backup?"] = "Er du sikker på at vil gjenopprette sikkerhetskopien?";
$lang["Upload and import site backup file"] = "Last opp og importer sikkerhetskopi";
$lang["Select Backup File"] = "Velg sikkerhetskopi";
$lang["Import Backup File"] = "Importer sikkerhetskopi";
$lang["Webmaster: Site Backup and Restoration"] = "Webmaster: Sikkerhetskopiering og Gjenopprettelse";
$lang["Description:"] = "Beskrivelse:";
$lang["Note: Thumbnail images should be no more than 99px wide."] = "Merk: Vidde på Thumbnail bilder skal ikke være mer enn 99px.";
$lang["Full Size Images should be no more than 275px wide for optimal display within your web site."] = "Vidden på bilder i full skala bør ikke være større enn 275px for optimal fremvisning på ditt nettsted.";
$lang["When customers add this product to their cart, require Form Data from:"] = "Når kunder legger dette produktet i handlekurven, krev Form Data fra:";
$lang["User-Defined Variable"] = "Brukerspesifisert variabel";
$lang["Denotes an event that is a 'Recurrence' of an original master event."] = "Denotes an event that is a 'Recurrence' of an original master event.";
$lang["Denotes the original 'Master' event within a recurring event cycle."] = "Denotes the original 'Master' event within a recurring event cycle.";
$lang["Special Promotions"] = "Tilbud";
$lang["Step 1: Blog Title"] = "Steg 1: Tittel";
$lang["Done!"] = "Ferdig!";
$lang["Step 2: Enter Content For Blog"] = "Steg 2: Legg inn innhold";
$lang["Launch Editor"] = "Start Editor";
$lang["Step 3: Post Blog to"] = "Steg 3: Legg til under";
$lang["Delete Entry"] = "Slett";
$lang["Edit Entry"] = "Rediger";
$lang["Save Entry"] = "Lagre";
$lang["show all"] = "vis alle";

$lang["Database Tables"] = "Database tabell";
$lang["Member Logins"] = "Bruker login";
$lang["Where do I start"] = "Hvor starter jeg";
$lang["Select Feature"] = "Velg";
$lang["Setup Options"] = "Oppsett";
$lang["Drag-N-Drop"] = "Dra-og-slipp";
$lang["Choose a feature that you would like to use from the basic, advanced or administrative feature list"] = "Velg en løsning du ønsker å bruk fra basic, avansert eller admin muligheter.";
$lang["Menu Navigation"] = "Meny navigasjon";
$lang["<b>Hovedmeny</b>"] = "Hovedmeny";
$lang["Follow the instructions to set up features specific to that module"] = "Følg instruks for å gjør endringer til moduler";
$lang["Now that your feature is set up, go to Open/Edit Page(s), select a page, and drag the feature you setup to a grid square.  Done!"] = "Module er endret, gå til Åpen/Rediger Side, velg en side, og dra module du ønsker å bruke til område nedenfor. Ferdig!";
$lang["Template Upload"] = "Template Upload";
$lang["FAQ Manager"] = "FAQ Manager";
$lang["Web Forms"] = "Web Skjemaer";
$lang["New Campaign"] = "Ny nyhetsbrev";
$lang["Create Search"] = "Opprett Søk";
$lang["A"] = "A";
$lang["Click here to show"] = "Klikk her for å vis";
$lang["Plugin Features"] = "Plugin valg";
$lang["Plugin Feature Modules"] = "Plugin valg moduler";
$lang["Manage Plugins"] = "Administrer Plugins";
$lang["Report Missing Strings"] = "Rapporter manglende oversettelse";
$lang["ViaStep Floating Menu"] = "ViaStep Flyttende meny";
$lang["Administrative Features"] = "Admin valg";
$lang["Traffic Statistics"] = "Besøk stats";
$lang["Add Admin User"] = "Legg til Admin bruker";
$lang["Help Center"] = "Hjelp Senter";
$lang["Site visitors online now"] = "Besøkere på din hjemmeside nå";
$lang["Average"] = "Gjennomsnitlig";
$lang["Select Base Template"] = "Velg standard Template";
$lang[" Save "] = "Lagre";
$lang["Enter your template header/logo line"] = "Skriv in template header/logo tekst.";
$lang[" Upload "] = "Last opp";
$lang["Template .zip file"] = "Template .zip fil";
$lang["Add Blog"] = "Legg til Blog";
$lang["Please select a blog category to display content from for each"] = "Vennligst velg blog kategori å vise innhold for hver";
$lang["Assign Blog Category"] = "Velg Blog Kategori";
$lang["AUTOMOTIVE-Classic_Cars-Blue"] = "AUTOMOTIVE-Classic_Cars-Blue";
$lang["Newsboxes should display content from which category?"] = "Nyhets boksen må vise innhold fra hvilken kategori?";
$lang["Promo boxes should display content from which category?"] = "Promo boksen må vise innhold fra hvilken kategori?";
$lang["Save"] = "Lagre";
$lang["Create blog subjects, add/edit blog content, and assign blog content."] = "Opprett blog emne, legg til/rediger blog innhold og velg blog innhold.";
$lang["The base site template will be applied by default to all pages."] = "Standard template blir valgt for alle sider.";
$lang["Create Pages"] = "Opprett sider";
$lang["Delete Pages"] = "Slett sider";
$lang["Menu status"] = "Meny status";
$lang["Assigned template"] = "Valgt template";
$lang["Main Menu Pages"] = "Hovedmeny sider";
$lang["Create new pages"] = "Opprett nye sider";
$lang["Add Link"] = "Legg til link";
$lang["Add Custom Menu Link"] = "Legg til din egen meny link";
$lang["Webmaster: FAQ Manager"] = "Webmaster: FAQ Manager";
$lang["Oppretter mappe for denne sikkerhetskopien"] = "Oppretter mappe for denne sikkerhetskopien ";
$lang["No file selected!\nPlease choose a backup file from your hard drive."] = "Ingen fil valgt!\\nVennligst velg en backup fil fra din harddisk.";
$lang["Skriver informasjon til tekstfil"] = "Skriver informasjon til tekstfil";
$lang["Click the Delete button next to any page to delete that page"] = "Klikk på Slett knappen på side av en side for å slette denne";
$lang["Arkiverer innhold og filer"] = "Arkiverer innhold og filer";
$lang["Oppretter data tabell for gjennoprettelsesfil"] = "Oppretter data tabell for gjennoprettelsesfil
 ";
$lang["Oppretter nedlastbar arkiv fil"] = "Oppretter nedlastbar arkiv fil";
$lang["Oppretter sikkerhetskopi info i log filer"] = "Oppretter sikkerhetskopi info i log filer";
$lang["Import a backup file that you uploaded via FTP"] = "Importer backup fil som du har lastet opp med FTP";
$lang["Referrer Sites"] = "Sites som linker til deg";
$lang["Referrals (per)"] = "Sites som linker (per)";
$lang["Referral Site"] = "Site som linker";
$lang["Administrator Logins"] = "Administrator Logins";
$lang["Search Engine Ranking"] = "Søkemotor rangering";
$lang["Software Updates"] = "Program oppdateringer";
$lang["Show 'Email my login info to me' option on log-in screen?"] = "Vis \'Email my login info to me\' på login side?";
$lang["Reset Text Editor Mode"] = "Angre Tekst Editor Mode";
$lang["Fax Number"] = "Faks nummer";
$lang["OK"] = "OK";
$lang["Check for Updates Now"] = "Sjekk for oppdatering nå";
$lang["Install Now"] = "Installer nå";
$lang["Product Categories"] = "Produkt kategorier";
$lang["English string"] = "English string";
$lang["translation"] = "Oversettelse";
$lang["Click the Edit button next to any page to being editing that page"] = "Klikk på Rediger knappen på side av en side for å redigere denne side";
$lang["Need to create another page? Click the 'Create New Page(s)' button at the bottom of the screen"] = "Trenger du å opprette en ny side? Klikk på \'Opprett ny side\' knappen nederst på skjermen.";
$lang["Plugin .zip file"] = "Plugin .zip fil";
$lang["Create new forms and view current forms."] = "Opprett nye skjemaer og vis aktive skjemaer.";
$lang["Add or edit fields to your form. Make sure to save your changes periodically."] = "Legg til eller rediger felter på skjema. Husk å lagre av og til.";
$lang["site base template"] = "basis mal";
$lang["Gallery Layout"] = "Gallery oppsett";
$lang["Columns"] = "Kolonner";
$lang["Rows"] = "Rader";
$lang["ViaStep SEO Sitemaps"] = "ViaStep SEO Sitemaps";
$lang["ViaStep Photo Gallery"] = "ViaStep Photo Gallery";
$lang["Untranslated Strings"] = "Tekst som ikke er oversatt";
$lang["ViaStep SEO Ranks"] = "ViaStep SEO Ranks";
$lang["Show album thumbnail before album"] = "Hvis album thumbnail før album";
$lang["Thank you for your inquiry"] = "Takk for henvendelse";
$lang["ViaStep RSS"] = "ViaStep RSS";
$lang["File Editor"] = "File Editor";
$lang["Floating Menu"] = "Floating Menu";
$lang["User-changeable template images"] = "Template bilder som kan byttes ut av bruker";
$lang["Manage template images"] = "Endre template bilder";
$lang["F2 key log-in shortcut opens admin window in..."] = "F2 tast log-in snarvei åpner admin vindu i ...";
$lang["New software updates available."] = "Ny programvare oppdatering tilgjengelig.";
$lang["Business Info"] = "Firma Informasjon";
$lang["Here you can manage administrator logins, multi-user access rights, restart the quickstart wizard and reset the text editor mode."] = "Her kan du administrer admin login, fler bruker tilgangs rettigheter, restart av quickstart wizard og reset tekst editor modus.";
$lang["General Website Preferences"] = "Generele website instillinger";
$lang["Miscellaneous preferences, options, and settings that apply to many areas within the sitebuilder admin tool and the content it creates for your website."] = "Andre instillinger for andre områder innenfor admin verktøy og innhold av hjemmeside.";
?>