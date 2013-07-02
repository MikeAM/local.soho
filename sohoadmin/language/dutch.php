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
## Copyright 1999-2003 Soholaunch.com, Inc.  All Rights Reserved.
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

// Main Menu
$lang["Open Page"] = "Pagina(s) Opmaken";
$lang["Main Menu"] = "Hoofd Menu";
$lang["View Site"] = "Bekijk Website";
$lang["Webmaster"] = "Webmaster";
$lang["Logout"] = "Logout";

// Page Editor
$lang["Save Page"] = "Pagina Opslaan";
$lang["Save As"] = "Opslaan Als";
$lang["Preview Page"] = "Bekijk Pagina";
$lang["Page Properties"] = "Pg. Eigenschappen";

// Feature Menus
$lang['Shopping Cart Menu'] = "Shopping Cart Menu";
$lang['Calendar Menu'] = "Agenda Menu";
$lang['eNewsletter Menu'] = "eNieuwsbrief Menu";
$lang['Database Menu'] = "Database Menu";


#################################################
## STATUS BAR (footer)
#################################################
$lang["Product Build"] = "Product Build";


#################################################
## FEATURE PROMO / LICENSE UPGRAGE PAGE
## - When user clicks on 'disabled' feature
#################################################
$lang["Feature Upgrade Required"] = "Feature Upgrade Vereist";
$lang["Your current license does not allow you to access this feature."] = "Uw huidige rechten staan toegang tot dit programmaonderdeel niet toe.";
$lang["In order to activate it, please contact :HOSTCO_NAME: and request an upgrade."] = "Om het te activeren, neem a.u.b. contact op met:HOSTCO_NAME: en vraag een upgrade aan."; // :HOSTCO_NAME: replaced with host-configured data
$lang["Once you are notified that the new feature(s) have been activated, return to this screen and click the &quot;Upgrade License&quot; button. This will download and install your new license and components automatically."] = "Als u bericht hebt ontvangen dat de nieuwe feauture is geactiveerd, ga dan terug naar dit scherm en klik op de &quot;Upgrade License&quot; button. Hiermee download and installeert u uw gebruikersrechten en componenten automatisch.";

// Live progress report (while getting new key)
$lang['promo']['locating license'] = "Zoek huidige gebruikersrechten";
$lang['promo']['license downloaded'] = "Nieuwe gebruikersrechten gedownload";
$lang['promo']['installing license'] = "Installeert actieve rechten matrix voor "; // ' $_SERVER['HTTP_HOST']
$lang['promo']['please hold'] = "Please Hold";
$lang['promo']['features upgraded'] = "Site features upgraded";

$lang['Upgrade License'] = "Waardeer gebruikersrechten op"; // Button


#################################################
## MAIN MENU
#################################################

// General Titles and Notifications
$lang["Site Visitor(s) online"] = "Site bezoeker(s) Online";
$lang["NOTE: Any data outstanding will not be saved."] = "NOTE: Niet opgeslagen data zullen verloren gaan.";

// Basic Features Group
$lang["Basic Features Group"] = "Basis Modules";
$lang["Create New Pages"] = "Creëer Nieuwe Pagina(s)";
$lang["Edit Pages"] = "Openen/Opmaak Pagina(s)";
$lang["Menu Display"] = "Navigatie Menu";
$lang["File Manager"] = "File Manager";
$lang["Template Manager"] = "Template Manager";
$lang["Forms Manager"] = "Formulieren Manager";

// Advanced Features Group
$lang["Advanced Features Group"] = "Geavanceerde Modules";

$lang["Shopping Cart"] = "Winkelwagen";
$lang["Event Calendar"] = "Agenda";
$lang["eNewsletter"] = "eNieuwsbrief";
$lang["Database Tables"] = "Database Tabellen";
$lang["Site Data Tables"] = "Site Tabellen";
$lang["Database Table Manager"] = "Database Tabel Manager";
$lang["Secure Users"] = "Veilige Gebruikers";
$lang["Member Logins"] = "Veilige Gebruikers";
$lang["Photo Albums"] = "Foto Albums";
$lang["Site Statistics"] = "Site Statistieken";
$lang["Blog Manager"] = "Blog Manager";

// Javascript Alerts

$lang["Select a menu option from the main menu sections to get started."] = "Selecteer een menu optie in een van de Hoofdmenu secties om te starten.";
$lang["You do not have access to this option."] = "U heeft geen toegang tot deze optie.";

// Footer Assets

$lang["About"] = "About";

#################################################
## CREATE NEW PAGES MODULE					        ##
#################################################

$lang["Page Name"] = "Pagina Naam";
$lang["Page Type"] = "Pagina Type";
$lang["Create New Pages"] = "Creëer Nieuwe Pagina(s)";
$lang["Menu Page"] = "Menu Pagina";
$lang["Add to Menu?"] = "In Menu?";
$lang["yes"] = "ja";
$lang["no"] = "nee";
$lang["Newsletter"] = "Nieuwsbrief";
$lang["Calendar Attachment"] = "Agenda Toevoeging";
$lang["Shopping Cart Attachment"] = "Shopping Cart Toevoeging";
$lang["Create More Pages"] = "Creëer Meer Pagina's";
$lang["You may create up to 10 new pages at a time."] = "U kunt tot 10 nieuwe pagina's in een keer creëren.";
$lang["Please only use alpha-numerical characters and spaces."] = " Gebruik a.u.b. alleen alfanumerieke karakters en spaties.";
$lang["Your new pages have been created!"] = "Uw nieuwe pagina's zijn gecreëerd!\\n\\nU kunt nu starten met het opmaken door het openen van de pagina\\of nog meer nieuwe pagina's creëren.";
$lang["Could Not Create the Following Pages because they already exist on the system:"] = "Kan de volgende pagina's niet creëren omdat ze al bestaan:";


#################################################
## OPEN PAGE MODULE							        ##
#################################################
$lang["Edit Content"] = "Inhoud Opmaken";
$lang["Menu Status"] = "Menu Status";
$lang["Parent Page"] = "Parent Pagina";
$lang["Page Template"] = "Pagina Template";
$lang["Delete Page"] = "Pagina Verwijderen";
$lang["Off Menu"] = "Niet in Menu";
$lang["On Menu"] = "In Menu";
$lang["site base template"] = "site basis template";
$lang["Assigned template"] = "Toegewezen template";
$lang["Browse"] = "Bladeren";
$lang["Edit"] = "Opmaken";
$lang["Delete"] = "Delete";
$lang["Number Skus"] = "Nummer Skus";
$lang["Articles"] = "Artikelen";
$lang["Latest News"] = "Laatste Niews";

$lang["Click on the page name that you wish to edit"] = "Klik op de naam van de pagina die u wilt opmaken.";
$lang["Are you sure you wish to delete this page"] = "Weet u zeker dat u deze pagina wilt verwijderen? U kunt dit niet meer ongedaan maken!";

#################################################
## page_editor.php
#################################################
$lang["Click on an object below and drag it onto a drop zone for page placement."] = "Klik op een object hieronder en sleep het naar een van de zones, om het in de pagina te plaatsen.";


#################################################
## MENU DISPLAY MODULE     						  ##
#################################################
$lang["You have already used this page in your menu system."] = "U heeft deze pagina reeds gebruikt in uw menu.";
$lang["You can only use pages one time on your auto-menu system."] = "U kunt pagina's maar één keer gebruiken in uw auto-menu systeem.";
$lang["Auto-Menu Display Type"] = "Soort Auto-Menu";
$lang["Text Links"] = "Tekst Links";
$lang["Buttons"] = "Buttons";
$lang["Edit Button Colors"] = "Button Kleur Wijzigen";
$lang["Text Menu Display"] = "Tekst Menu";
$lang["Yes"] = "Ja";
$lang["No"] = "Nee";
$lang["Available Pages"] = "Beschikbare Pagina's";
$lang["Current Menu"] = "Huidige Menu";

$lang["Select a page from your available site pages."] = "Selecteer een pagina uit uw Beschikbare Pagina's.";
$lang["Then, choose to add it to the bottom<BR>of your 'live' menu as a Main Menu Item or a Sub-Page of a Main Menu Item."] = "Kies vervolgens om het onderaan<BR>in uw 'actieve' menu als een Hoofd Menu Item of een Sub-Pagina toe te voegen.";

$lang["To Delete a page on the current menu"] = "Om een pagina in het huidige menu te verwijderen";
$lang["select the page from the available pages"] = "selecteer de pagina uit de beschikbare pagina's";
$lang["that already appear on your current"] = "deze komt al voor in uw huidge";
$lang["menu, then click 'Delete Page'."] = "menu, klik vervolgens op 'Delete Page'.";

$lang["Auto-Menu Button Colors"] = "Auto-Menu Button Kleuren";
$lang["Current Button Color Scheme"] = "Huidige Button Kleuren";
$lang["Button Color"] = "Button Kleur";
$lang["Button Text Color"] = "Button Tekst Kleur";
$lang["Hex Color"] = "Hex Kleur";
$lang["About Us"] = "Over Ons";
$lang["Save Button Colors"] = "Sla Button Kleuren Op";
$lang["Auto-Menu Setup"] = "Auto-Menu Setup";
$lang["This is a text representation of the color scheme"] = "Dit is een voorbeeld button die de gekozen kleuren\\in uw huidige navigatie menu systeem weergeeft.";

//Buttons
$lang["Add Main"] = "Hoofd Menu Item";
$lang["Add Sub"] = "Sub Menu Item";
$lang["Clear Menu"] = "Maak Leeg";
$lang["Save Menu System"] = "Sla Menu Systeem op";



#################################################
## FILE MANAGER MODULE     						  ##
#################################################
$lang["File Name"] = "File Naam";
$lang["File Size"] = "File Omvang";
$lang["Image files can be viewed and saved by clicking the preview icon next to the filename."] = "Afbeeldingen kunnen bekeken en opgeslagen worden door te klikken op het 'preview' icoon naast de filenaam.";
$lang["Indicates an image that should be reduced in filesize. This file causes slow load-times when viewing your web site."] = "Geeft een afbeelding aan waarvan de file verkleind moet worden. Dit file veroorzaakt vertraging bij het laden van de pagina in de browser.";
$lang["Upload New Files"] = "Upload Nieuwe Files";
$lang["Remember"] = "Let Op!";
$lang["Changes and deletions are final and can not be undone."] = "Veranderingen en verwijderingen zijn definitief en niet omkeerbaar.";
$lang["Update File Changes"] = "Update File Wijzigingen";

// Upload New Files
// -------------------------------------------
$lang["Upload Files"] = "Upload Files";
$lang["Select the <U>Browse</U> button next to each filename to locate your local file for upload. When ready to start the upload operation, select <U>Upload Files</U>."] = "Selecteer de <U>Browse</U> button naast elke filenaam om het te uploaden file te zoeken. Als u klaar bent om het uploaden te starten, selecteer <U>Upload Files</U>.";
$lang["Filename"] = "Filenaam";
$lang["Upload of files completed."] = "Upload van files gereed.";
$lang["Current Site Files"] = "Huidige Site Files";
$lang["View Current Site Files"] = "Bekijk Huidige Site Files";
$lang["Upload Custom Template HTML"] = "Upload Custom Template HTML";
$lang["Upload More Files"] = "Upload Meer Files";
$lang["Success"] = "Succes!";
$lang["Did not upload"] = "Niet Ge-upload";
$lang["File update completed."] = "File update gereed.";
$lang["Filename already exists"] = "Filenaam bestaat reeds";
$lang["File is not an accepted file format"] = "File is geen geaccepteerd file format";
$lang["Below is a report of the files that were uploaded during this operation"] = "Hieronder een overzicht van de ge-uploade files tijdens deze sessie";
$lang["Upload Complete"] = "Upload Gereed";
$lang["Open/Edit Page(s)"] = "Pagina's Openen/Opmaken";

#################################################
## SITE TEMPLATE MODULE						   ##
#################################################

// Template Mangager
$lang["Base Site Template"] = "Basis Site Template:";
$lang["The base site template"] = "De basis site template zal automatisch worden toegepast op alle pagina's<br>waar geen specifiek template is aangegeven via Pagina Eigenschappen.";
$lang["Browse Templates by Screenshot"] = "Bekijk Templates via Screenshot";
$lang["Save Changes"] = "Sla Wijzigingen Op";
$lang["Custom Template Builder"] = "Custom Template Builder";
$lang["Upload Custom Template HTML file(s)"] = "Upload Custom Template HTML file(s)";
$lang["Upload Template File(s)"] = "Upload Template File(s)";
$lang["If you are utilizing a built-in template"] = "Als u een standaard meegeleverd template gebruikt, kunt u de 'header' tekst opmaken weergegeven in uw template hieronder.";
$lang["Built-In Template Header"] = "Standaard Meegeleverd Template Header";
$lang["Enter your template header line"] = "Voer uw template header tekst in";
$lang["Save Header"] = "Sla Header Op";
$lang["Save Settings"] = "Sla Instellingen Op";
$lang["Company Slogan or Motto"] = "Bedrijfs Slogan of Motto";


// Custom Template Builder
$lang["Template Builder"] = "Template Bouwer";
$lang["Template Name"] = "Template Naam";
$lang["Template Image"] = "Template Afbeelding";
$lang["Preview Design"] = "Bekijk Ontwerp";
$lang["Save Design"] = "Sla Ontwerp Op";
$lang["Image Preview Area"] = "Bekijk Afbeelding Hier";
$lang["Image must be 204px Wide x 106px High"] = "Afbeelding moet 204px Breed x 106px Hoog zijn";
$lang["Template Style"] = "Template Stijl";
$lang["Blank"] = "Blanko";
$lang["Left Bar"] = "Balk Links";
$lang["L-Shape"] = "L-Vorm";
$lang["U-Shape"] = "U-vorm";
$lang["Pro"] = "Pro";
$lang["Foreground"] = "Voorgrond";
$lang["Background"] = "Achtergrond";
$lang["Title"] = "Titel";
$lang["Text"] = "Tekst";
$lang["Links"] = "Links";

#################################################
## FORMS MANAGER MODULE						        ##
#################################################
$lang["Current Forms"] = "Huidige Formulieren";
$lang["Custom Forms"] = "Custom Formulieren";
$lang["New Form Creation Wizard"] = "Nieuw Formulier Ontwerp Wizard";
$lang["To create a new form, enter the name"] = "Om een nieuw formulier te ontwerpen, voeg naam in en klik op button 'Maak Nieuw Formulier'";
$lang["Build New Form"] = "Maak Nieuw Formulier";
$lang["Preview"] = "Bekijk";
$lang["Add New Fields"] = "Voeg Nieuwe Velden Toe";
$lang["Add Fields"] = "Voeg Velden Toe";
$lang["Edit Form"] = "Maak Formulier Op";
$lang["Delete Form"] = "Verwijder Formulier";
$lang["Form Name"] = "Formulier Naam";
$lang["PREVIEW WINDOW"] = "VOORBEELD SCHERM";
$lang["You must enter a form name that is at least 3 characters long."] = "U moet een formuliernaam invoeren van tenminste drie karakters.";

//Form Builder Wizard

$lang["Building"] = "Bezig";
$lang["Form Field"] = "Formulier Veld";
$lang["Field Label"] = "Veld Label";
$lang["Required Field"] = "Verplicht Veld";
$lang["What is your site visitor supposed to enter or select for this field"] = "Deze tekst is te zien op uw webstie.  Wat moet uw bezoeker invullen of selecteren in dit veld";
$lang["Field Type"] = "Veld Type";
$lang["Field Name"] = "Veld Naam";
$lang["Text Box"] = "Tekst Box";
$lang["Text Area (Multi-Line)"] = "Tekst Gebied (Meerregelig)";
$lang["Drop Down Box"] = "Drop Down Box";
$lang["Radio Buttons"] = "Radio Buttons";
$lang["Checkboxes"] = "Checkboxes";
$lang["What is the Name of this field"] = "De <u>Naam</u> van dit veld wordt opgeroepen bij het verwerken van dit forumulier<br>in  email of database interactie.  Gebruik <i>emailaddr</i> voor email velden  die u aan de bezoeker wilt mailen.";
$lang["Field Values"] = "Veld Waarden";
$lang["Enter selectable options separated by commas"] = "Voeg selecteerbare opties in, onderbroken door komma's";
$lang["Drop Down Boxes, Radio Buttons, and Checkboxes Only"] = "Alleen Drop Down Boxes, Radio Buttons, en Checkboxes";
$lang["[Save] Finish Form"] = "[Bewaar] Sluit Formulier";
$lang["Add Next Field"] = "Add Next Field";

#################################################
## SITE STATISTICS MODULE					        ##
#################################################

// Main Stats Display

$lang["Unique Visitors"] = "Unieke Bezoekers";
$lang["Top 25 Pages"] = "Top 25 Pagina's";
$lang["Views By Day"] = "Bezoeken Per Dag";
$lang["Views By Hour"] = "Bezoeken Per Uur";
$lang["Referer Sites"] = "Bezoek Afkomstig Van";
$lang["Browser/OS"] = "Browser/OS";
$lang["You should empty your log tables at least every six months are so depending on traffic."] = "U moet uw logvelden tenminste elke zes maanden schonen, afhankelijk van hoeveelheid bezoek.";
$lang["If you experience slowness<BR>in loading reports, your log tables have probably gone unattended for some time."] = "Als rapporten langzaam laden<BR>dan heeft u uw logtabellen een tijdje verwaarloosd.";

// statistics/includes/unique.php

$lang["UNIQUE VISITOR TREND"] = "UNIEKE BEZOEKER TREND";
$lang["Total Unique Visitors"] = "Totaal Unieke Bezoekers";
$lang["Total Page Views"] = "Totaal Paginea Bezoeken";
$lang["Visit Frequency"] = "Bezoek Frequentie";
$lang["Avg Pages Per Visit"] = "Gem Pag's Per Bezoek";

// statistics/includes/top25.php

$lang["TOP 25 SITE PAGES/SITE MODULES"] = "TOP 25 SITE PAGINA'S/SITE MODULES";
$lang["Rank"] = "Rangorde";
$lang["Page Views"] = "Pagina Bezoeken";

// statistics/includes/byday.php

$lang["PAGE VIEWS BY DAY"] = "PAGINA BEZOEK PER DAG";
$lang["Total Page Views for"] = "Totaal Pagina Bezoeken in";
$lang["Page Views Per Day Totals"] = "Pagina Bezoeken Per Dag Totalen";
$lang["Mouseover a Selected day for actual total"] = "Ga met Muis over een Geselecteerde dag voor om feitelijke totalen te zien";

// statistics/includes/byhour.php

$lang["PAGE VIEWS BY HOUR"] = "PAGINA BEZOEKEN PER UUR";
$lang["Most active hour of the day"] = "Meest drukke uur van de dag";
$lang["Mouseover a Selected Hour for actual total"] = "Ga met muis over een Geselecteerd uur om feitelijke totalen te zien";

// statistics/includes/refer.php

$lang["REFERER SITES"] = "VERWIJZENDE SITES";
$lang["Referals (per)"] = "Verwijzingen (per)";
$lang["Referal Site"] = "Verwijzende Site";

// statistics/includes/browser.php

$lang["BROWSER AND OPERATING SYSTEMS USED"] = "GEBRUIKTE BROWSER EN OPERATING SYSTEMS";
$lang["Browser"] = "Browser";
$lang["Usage Data"] = "Gebruiksgegevens";


#################################################
## PHOTO ALBUM MODULE					           ##
#################################################

// photo_album/photo_album.php
$lang["Photo Album"] = "Foto Album";
$lang["Create New Album"] = "Creëer Nieuw Album";
$lang["Enter Album Name"] = "Voer Albumnaam in";
$lang["Current Photo Albums"] = "Huidige Foto Albums";
$lang["Select Album"] = "Selecteer Album";

// photo_album/edit_album.php
$lang["Edit Album"] = "Maak Album Op";
$lang["Image Preview"] = "Miniatuur Afbeelding";
$lang["Details"] = "Details";
$lang["Image"] = "Afbeelding";
$lang["Caption"] = "Onderschrift";
$lang["Link"] = "Link";
$lang["Save Album"] = "Sla Album Op";
$lang["Cancel Edit"] = "Opmaken Afbreken";

#################################################
## SITE DATA TABLES MODULE					        ##
#################################################

// download_data.php

$lang["Manage/Backup Site Data Tables"] = "Manage/Backup Site Data Tabellen";
$lang["View"] = "Bekijk";
$lang["Download"] = "Download";
$lang["Import"] = "Importeer";
$lang["Empty"] = "Legen";
$lang["Database table"] = "Database tabel";
$lang["View All Data Tables"] = "Bekijk Alle Data Tabellen";
$lang["WARNING"] = "WAARSCHUWING";
$lang["You have selected to clear the data from table"] = "U heeft gekozen om de data van de tabel te verwijderen.";
$lang["This process is irreversible and will delete all data contained in this table"] = "Dit proces is onomkeerbaar en zal alle data in deze tabel verwijderen";
$lang["Are you sure you wish to continue"] = "Bent u zeker dat u wilt doorgaan";
$lang["Continue"] = "Doorgaan";
$lang["Cancel"] = "Afbreken";
$lang["CSV Filenames"] = "CSV Filenamen";
$lang["Select the CSV file that you wish to import"] = "Selecteer het CSV file dat u wilt importeren";
$lang["Please note that you can only upload comma or semi-colon delimited CSV files"] = "Wees erop bedacht dat u alleen komma or puntkomma gescheiden CSV files kunt uploaden";
$lang["If you need to upload your csv file"] = "Als u uw csv file moet uploaden";
$lang["click here"] = "klik hier";
$lang["Use Default Value"] = "Gebruik Standaard Waarde";
$lang["Select which fields in the CSV file to place into the existing table fields"] = "Selecteer welke velden in het CSV file te plaatsen in de bestaande tabel velden";
$lang["First record of CSV data contains field names. Do not import."] = "De eerste rij van de CSV data bevat veldnamen. Niet importeren.";
$lang["Table Field Name"] = "Tabel Veld Naam";
$lang["CSV Field Name"] = "CSV Veld Naam";
$lang["Default Import Value"] = "Importeer Default Waarde";
$lang["If a field name from your csv file is matched to the PriKey field of the table"] = "Als een veldnaam van uw csv file is gekoppeld aan het 'PriKey' veld van de tabel, dan zullen de de csv file data<BR>bestaande sleutelwaarden 'Updaten' en nieuwe records toevoegen die niet overeenkomen met bestaande sleutelwaarden.";
$lang["Import Data Now"] = "Importeer Data Nu";
$lang["IMPORT OF CSV DATA TO"] = "IMPORTEREN VAN CSV DATA NAAR";
$lang["COMPLETE!"] = "GEREED!";
$lang["Records imported successfully"] = "Records Succesvol geïmporteerd";
$lang["Records were modified"] = "Records werden gemodificeerd";
$lang["View all Tables"] = "Bekijk alle Tabellen";


#################################################
## BLOG MANAGER MODULE					           ##
#################################################

$lang["Blog Subjects"] = "Blog Onderwerpen";
$lang["New Subject"] = "Nieuw Onderwerp";
$lang["Add New"] = "Voeg Toe";
$lang["Existing Subjects"] = "Bestaande Onderwerpen";
$lang["View"] = "Bekijk";
$lang["Create a new blog entry by entering your data in the text editor below"] = "Creëer een nieuwe blog bijdrage door uw gegevens in de Tekst Editor hieronder in te voeren";
$lang["Then choose the subject that this blog should be assigned to and click Post Blog to continue"] = "Kies dan het onderwerp waaraan deze blog moet worden toegevoegd en klik op<i>Voeg Blog Toe</i> om door te gaan.";
$lang["Blog Title"] = "Blog Titel";
$lang["Please choose a subject to post this blog to"] = "Kies a.u.b. een onderwerp om deze blog aan toe te voegen.";
$lang["Please choose a title for this post"] = "Kies a.u.b. een titel voor deze bijdrage";
$lang["Post Blog to"] = "Voeg Blog Toe Aan";
$lang["Choose Subject"] = "Kies Onderwerp";
$lang["Post"] = "Voeg Toe";
$lang["Update Complete"] = "Update Gereed";
$lang["Can not delete this subject.  Blog data exists"] = "Kan dit onderwerp niet verwijderen.  Bevat Blogs";
$lang["Latest News"] = "Laatste Nieuws";
$lang["Special Promotions"] = "Speciale Aanbevelingen";

#################################################
## SHOPPING CART MODULE					           ##
#################################################

// shopping_cart.php
// --------------------------------------
$lang["Shopping Cart: Main Menu"] = "Shopping Cart: Hoofd Menu";

// These three make up the sentence "You currently have [NUMBER] products in [NUMBER] categories"
$lang["You currently have"] = "Op dit moment heeft u";
$lang["products in"] = "producten in";
$lang["categories"] = "categoriën";


$lang["Category Names"] = "Categorie Namen";
$lang["Add New Products"] = "Voeg Nieuwe Producten Toe";
$lang["Find/Edit Current Products"] = "Zoek/Wijzig Bestaande Producten";
$lang["Shipping Options"] = "Verzendopties";
$lang["Tax Rate Options"] = "Belastingopties";
$lang["Payment Options"] = "Betalingsopties";
$lang["Business Information"] = "Bedrijfsinformatie";
$lang["Display Settings"] = "Presentatie Instellingen";
$lang["Privacy Policy"] = "Privacybeleid";
$lang["Shipping Policy"] = "Verzendbeleid";
$lang["Returns/Exchanges Policy"] = "Retouren/Ruilbeleid";
$lang["Other Policies"] = "Ander Beleid";
$lang["View Online Orders/Invoices"] = "Bekijk Online Orders/Facturen";

// categories.php
// ---------------------------------
$lang["Shopping Cart: Category Setup"] = "Shopping Cart: Categorie-indeling";
$lang["Current Categories"] = "Huidge Categoriën";
$lang["Add New Category"] = "Voeg Nieuwe Categorie Toe";
$lang["New Category Name"] = "Namme Nieuwe Categorie";
$lang["Add Category"] = "Voeg Categorie Toe";
$lang["To delete a category"] = "Om een categoriey te verwijderen, klik op [ delete ]  naast de naam van het product in de  'Huidige Categoriën' box aan de linker kant.";


// products.php
// ---------------------------------
$lang["Shopping Cart: Add New Product"] = "Shopping Cart: Voeg Nieuw Product Toe";
$lang["No Image"] = "Geen Afbeelding";
$lang["SAVE PRODUCT"] = "SLA PRODUCT OP";
$lang["PRODUCT INFO"] = "PRODUCT INFO";
$lang["PRODUCT IMAGES"] = "PRODUCT AFBEELDINGEN";
$lang["PRICE VARIATION"] = "PRIJC VARIATIE";
$lang["ADVANCED OPTIONS"] = "GEADVANCEERDE OPTIES";
$lang["Part No. (SKU Number):"] = "Onderdeel No. (SKU Nummer):";
$lang["Unit Price:"] = "Stuksprijs:";
$lang["Part Name (Title):"] = "Onderdeel Naam (Titel):";
$lang["Catalog Ref Number:"] = "Catalogus Ref Nummer:";
$lang["Description:"] = "Bescrijving:";
$lang["Main Category:"] = "Hoofd Categorie:";
$lang["Shipping Charge (A):"] = "Vervoerskosten:";
$lang["Secondary Category:"] = "Secundaire Category:";
$lang["If you are using standard shipping"] = "Als u standaard vervoer gebruikt, dan moet de waarde van de Vervoerskosten (A) het bedrag in US dollars zijn dat in rekening gebracht wordt bij de aankoop van deze individuele sku - per hoeveelheid besteld.";
$lang["Shopping Cart: Edit Product"] = "Shopping Cart: Wijzig Product";
$lang["Search Products"] = "Zoek Producten";

//Product Images
$lang["Select the thumbnail and full image that you wish to associate with this Sku Number."] = "Selecteer the thumbnail en de grotere afbeelding die u wilt koppelen aan dit Sku Nummer.";
$lang["If you are not using thumbnails, do not worry, the system will automatically resize your full size image to the appropriate scale when applicable. However, image quality of the scaled thumbnail may suffer."] = "Maakt u zich niet ongerust als u geen thumbnails gebruikt, het systeem zal indien nodig uw grote afbeeldingen automatisch aanpassen aan de juiste grootte. De beeldkwaliteit kan hier echter onder lijden.";
$lang["Thumbnail Image:"] = "Thumbnail Afbeelding:";
$lang["Full Size Image:"] = "Volledige Afbeelding:";
$lang["Note: Thumbnail images should be no more than 125px wide."] = "N.B.: De Thumbnail Afbeeldingen moeten niet breder zijn dan 125px.";
$lang["Full Size Images should be no more than 275px wide for optimal display within your web site."] = "Grotere afbeeldingen moeten niet breder zijn dan 275px voor optimale weergave op uw website.";
$lang["Image height is flexible."] = "De Afbeeldingshoogte is flexibel.";
$lang["Image Preview Window"] = "Afbeelding Bekijken";

//Price Variation
$lang["Sub-Category"] = "Sub-Categorie";
$lang["Variant"] = "Variant";
$lang["Show me what this looks like in operation and how the variant set-up works."] = "Laat met zien hoe dit er in de praktijk uitziet en hoe de Variant opmaak werkt.";

//Advanced Options
$lang["Charge Tax for this product?"] = "BTW berekenen voor dit product?";
$lang["Charge Shipping for this product?"] = "Vervoerskosten berekenen voor dit product?";
$lang["Security Code:"] = "Veiligheidscode:";
$lang["Public"] = "Publiek";
$lang["Attachment Page (Detail Page):"] = "Bijlage Pagina (Detail Pagina):";
$lang["Recommend this product"] = "Dit product aanbevelen tijdens 'Bekijk Winkenwagen'?";
$lang["Recommended Products like this one:"] = "Beveel producten aan als deze:";
$lang["Enter multiple sku numbers separated by comma"] = "Voer meerdere sku nummers in onderbroken door een komma.";
$lang["When customers add this product to thier cart, require Form Data from:"] = "Als klanten dit product aan hun winkelwagen toevoegen, zijn de volgende gegevens vereist:";
$lang["Per Qty"] = "Per Hoeveelheid";
$lang["Ignore Qty"] = "Hoeveelheid negeren";
$lang["Purchase of this Sku allows your customer to download the following file:"] = "Aankoop van deze Sku stelt uw klanten in staat het volgende file te downloaden:";
$lang["Display this Product"] = "Dit Product Tonen";
$lang["Inventory Count:"] = "Voorraad Teller:";
$lang["Additional Category Association:"] = "Koppeling met aanvullende Categorie:";
$lang["Special Tax Rate:"] = "Speciaal BTW Tarief:";
$lang["Searchable Keywords"] = "Opzoekbare Keywords (Niet getoond aan bezoeker; bedoeld voor product keyword searches in zoekmachines):";


// search_products.php
// ---------------------------------

$lang["Shopping Cart: Find/Edit Product"] = "Shopping Cart: Zoek/Wijzig Product";
$lang["Edit/Search For Products"] = "Wijzig/Zoek Producten";
$lang["Edit Sku"] = "Wijzig Sku";
$lang["Find"] = "Zoek";
$lang["Search For"] = "Zoek naar";
$lang["Search Results"] = "Zoekresultaten";
$lang["Edit Product Data"] = "Wijzig Product Gegevens";
$lang["Delete Product"] = "Verwijder Product";


// shipping_options.php
// ---------------------------------
$lang["Shopping Cart: Shipping Options"]  = "Shopping Cart: Verzend Opties";
$lang["Choose the Shipping Option you wish to utilize for this shopping cart system:"] = "Kies de vervoers optie die u wilt gebruiken voor deze webwinkel:";
$lang["Standard Shipping (Per Sku)"] = "Standaard Vervoerskosten (Per Sku)";
$lang["Charge By Order Sub-Total"] = "Rekening Op Order Sub-Totaal";
$lang["Use Custom PHP Include"] = "Gebruik Custom PHP Include";
$lang["Offline/Manual Calculation"] = "Offline/Handmatige Calculatie";
$lang["Save Shipping Options"] = "Sla Vervoersopties op";
$lang["SET PRICING GRID, IF ORDER SUB-TOTAL IS..."] = "BEPAAL PRIJSKADER, ALS HET ORDER SUB-TOTAL IS...";
$lang["Greater Than"] = "Hoger dan";
$lang["And Less Than"] = "En Lager Dan";
$lang["Shipping Price"] = "Vervoerskosten";

// tax_rates.php
// ---------------------------------

$lang["Shopping Cart: Tax Rate Options"] = "Shopping Cart: Belastingopties";
$lang["To Add a tax rate"] = "Om een belastingtarief toe te voegen, selecteer een staat, provincie, en/of land en vul het belasting\\npercentage 'sales tax' of BTW in dat in rekening moet worden gebracht voor items\\nverzonden naar die staat.\\n\\nOm een belastingtarief te verwijderen, selecteer een in gebruik zijnde \\nstaat en laat het belastingtarief open.";

//One sentence split into three parts
$lang["When visitors purchase items from your site"] = "Als bezoekers iterms via uw site kopen";
$lang["and select delivery to any of the below-listed areas,"] = "en aflevering op een van de onderstaande plaatsen selecteren,";
$lang["they will be charged the tax percentages you specified."] = "zal het door u aangegeven belastingpercentage in rekening worden gebracht.";

$lang["United States"] = "United States";
$lang["Canada"] = "Canada";
$lang["Add/Delete Tax:"] = "VoegToe/Verwijder Belasting:";
$lang["Tax Rate"] = "Belastingpercentage";
$lang["Add/Delete Tax Rate"] = "VoegToe/Verwijder Belastingpercentage";
$lang["State/Province"] = "Staat/Provincie";
$lang["There are currently no states in use."] = "Er worden op dit moment geen staten gebruikt.";
$lang["International Taxes"] = "Internationale Belastingen";
$lang["Note: You must enter a valid VAT/GST registration number to charge and collect VAT/GST taxes."] = "N.B.: U moet een geldig BTW/VAT/GST registratie nummer invoere om BTW/VAT/GST in rekening te kunen brengen.";
$lang["Registration Number:"] = "BTW nummer:";
$lang["Save Tax Options"] = "Sla Belastingopties Op";
$lang["Tax Rate Table Updated."] = "Belastingtarief Tabel ge-update.";
$lang["Country"] = "Land";
$lang["There are currently no countries in use."] = "Er worden op dit moment geen landen gebruikt.";

// payment_options.php
// ---------------------------------

$lang["Shopping Cart: Payment Options"] = "Shopping Cart: Betalingsopties";
$lang["What type of payment processing will you utilize"] = "Welk type betalingsafhandeling wilt u gebruiken";
$lang["PayPal"] = "PayPal";
$lang["VeriSign"] = "VeriSign";
$lang["WorldPay"] = "WorldPay";
$lang["Live Credit Card Processing"] = "Directe Credit Card Betaling";
$lang["None"] = "Geen";
$lang["Offline Credit Card"] = "Offline Credit Card";
$lang["Check / Money Order"] = "Cheque / Bank Overschrijving";
$lang["If using credit card processing, select which cards you will accept:"] = "Als u credit card betalingen gebruikt, selecteer welke cards u wilt accepteren:";
$lang["Choose Currency Type and Symbol"] = "Kies Valuta type en Symbool";
$lang["Currency Type:"] = "Valuta:";
$lang["Currency Symbol:"] = "Valuta Symbool:";
$lang["Select Payment System (Online Processing)"] = "Selecteer Betalaingssyteem (Online Afhandeling)";
$lang["If you are using online credit card processing"] = "als u online credit card afhandeling gebruikt, kunt u betalingen laten plaatsvinden via de volgende populaire processors:";

$lang["WorldPay Payment System"] = "WorldPay Payment System";
$lang["How to configure WorldPay for use with your site"] = "Hoe moet WorldPay voor gebruik op uw site worden geconfigureerd";
$lang["Installation ID:"] = "Installation ID:";
$lang["Fix Currency Type"] = "Fix Currency Type";
$lang["Test Mode:"] = "Test Mode:";
$lang["PayPal Email:"] = "PayPal Email:";
$lang["How to configure VeriSign Payflow Link for use with your site"] = "Hoe moet VeriSign Payflow Link worden geconfigureerd voor gebruik op uw site";
$lang["VeriSign Partner ID:"] = "VeriSign Partner ID:";
$lang["VeriSign Login ID:"] = "VeriSign Login ID:";
$lang["Innovative Gateway Solutions"] = "Innovative Gateway Solutions";
$lang["Innovative Gateway"] = "Innovative Gateway";
$lang["Username"] = "Gebruikersnaam";
$lang["Password"] = "Password";

$lang["I want to use online processing but I have a custom PHP include payment gateway"] = "Ik wil van online processing gebruik maken maar ik heb een custom PHP include payment gateway";
$lang["system that I want to use in place of the others listed"] = "systeem dat ik wil gebruiken in plaats van de hier genoemde";

$lang["This will over-ride all processing for credit cards."] = "Dit zal alle credit card processing diskwalificeren. Het systeem zal eenvoudigweg de controle aan het script overlaten en het is de verantwoordelijkheid van de auteur om terug te koppelen naar het systeem na de betalingsafhandeling.";

$lang["I am using an SSL Certificate with my web site and when going to the checkout"] = "Ik gebruik een SSL Certificaat op mijn website. Als de klant naar de chekout gaat";
$lang["the following https:// call should be made to the scripts"] = "dan moet de volgende https:// call gemaakt worden naar de scripts";
$lang["to invoke the SSL Cert."] = "om het SSL Cert. op te roepen";

//Full Sentence = "For example if you must use https://secure.[domain.com] to activate your SSL certificate, type https://secure.[domain.com] in the field above. DO NOT ADD ANY TRAILING FORWARD SLASHES. If you are unsure, consult your web developer."
$lang["For example if you must use <U>https://secure."] = "U moet bijvoorbeel <U>https://secure.";
$lang["</U> to activate your SSL certificate, type"] = "gebruiken </U> om uw SSL certificaat te activeren, type";
$lang["<B>https://secure."] = "<B>https://secure.";
$lang[".com</B> in the field above. DO NOT ADD ANY TRAILING FORWARD SLASHES. If you are unsure, consult your web developer.</B>"] = " in het veld hierboven. VOEG GEEN FORWARD - OF TRAILING SLASHES TOE. Als u niet zeker bent van uw zaak, raadpleeg dan uw web developer.";

$lang["When displaying the final invoice to my customer, I want to execute a custom PHP include"] = "Wanneer ik de uiteindelijk factuur aan mijn klant laat zien, will ik een custom PHP include uitvoeren";
$lang["that processes data when the invoice is displayed."] = "dat de data verwerkt als de fuctuur wordt getoond.";


$lang["Custom Include File:"] = "Custom Include File:";
$lang["This include can be used to create custom processes that execute after products have been purchased from your system."] = "Deze include kan worden gebruikt om custom processen te creëren die worden uitgevoerd nadat de producten zijn aangeschaft via uw systeem.";
$lang["For example, you may wish to assign a new user automatically with a generated username and password to the Secure Users table after a membership payment."] = "Bijvoorbeeld, u uw wilt een nieuwe gebruiker automatisch met een gegenereerde Gebruikersnaam en password toewijzen aan de 'Veilige Gebruikers' tabel na een betaling voor een lidmaatschap.";
$lang["Save Payment Options"] = "Sla Betalingsopties Op";


// business_information.php
// ---------------------------------

$lang["You will need to enter the address, phone number and whom to make a <U>check or money order</U>"] = "U moet het adres, telefoonnummer en aan wie een <u>cheque of bankoverschrijving</u> betaalbaar moet worden gesteld,";
$lang["payable to for your online store.  This will display to your site visitors at checkout time."] = "voor uw online winkel, invoeren.  Dit zien uw bezoekers tijdens de checkout.";
$lang["Make Payable To:"] = "Betaalbaar Stellen Aan:";
$lang["Address:"] = "Addres:";
$lang["City"] = "Plaats";
$lang["State/Province:"] = "Provincie:";
$lang["Zip/Postal Code:"] = "Postcode:";
$lang["Country:"] = "Land:";
$lang["Phone Number:"] = "Telefoonnummer:";
$lang["Statistics have shown that displaying this information on your site will increase trust<BR>among shoppers and therefore produce better sales results."] = "De statistieken laten zien dat het tonen van deze informatie op uw site vertrouwen wekt<BR>bij uw bezoekers en daardoor verkoopbevorderend werkt.";

$lang["When orders are placed on your website, they are saved in your order/invoice area."] = "Als er order worden geplaatst op uw website, worden ze opgeslagen onder order/factuur.";
$lang["The system will automatically send you an <U>email notifing you of new orders</U>.  Please "] = "Het systeeem zal u automatisch een <u>email sturen om u te informeren over nieuwe orders</U>.  Vult u ";
$lang["enter the email address where you wish these notifications to be sent. (Multiple email"] = "a.u.b. het emailadres in waar deze mededelingen naartoe moeten worden gestuurd. (Meerdere emailadressen";
$lang["addresses can be entered separated by a comma)"] = "moeten worden gesepareerd dooe een komma.)";

$lang["Notification Email Address:"] = "Informatie Emailadres:";
$lang["If you are using the \"Allow Product Comments\" option, when <U>users submit comments</U>"] = "Indien u gebruik maakt van de \"Sta Commentaar Toe\" optie: Als <u>gebruikers commentaar geven</U>";
$lang["about your products, the comments will be saved and an email generated to the email"] = "op uw producten, wordt dat opgeslagen en een bericht gegenereerd voor het emailadres";
$lang["address below for verification. If the comments meet your approval, you can then allow"] = "hieronder voor verificatie. Als u het eens bent met het commentaar kunt u het vervolgens toestaan";
$lang["the comments to be made visible by the public.  This is done to prevent unsavory or"] = "dat het commentaar zichtbaar wordt voor het publiek.  Dit is gedaan om te voorkomen dat ongewenst ";
$lang["lude comments from being posted without your knowledge."] = "of grof commentaar wordt gegeven zonder dat u daarvan op de hoogte bent.";
$lang["Verification Email Address:"] = "Verificatie Emailadres:";

$lang["After your customers purchase products from your site, they will receive an <U>email"] = "After your customers purchase products from your site, they will receive an <U>email";
$lang["invoice</U> of the order for their records. The default header text is a simple thank"] = "invoice</U> of the order for their records. The default header text is a simple thank";
$lang["you and is provided below.  You may modify this to say anything you wish.  The actual"] = "you and is provided below.  You may modify this to say anything you wish.  The actual";
$lang["invoice with pricing breakdowns, tax, shipping, etc. will appear below this header text."] = "invoice with pricing breakdowns, tax, shipping, etc. will appear below this header text.";

$lang["Save Business Info"] = "Save Business Info";


// display_settings.php
// ---------------------------------
$lang["Shopping Cart: Display Settings"] = "Shopping Cart: Weergave Instellingen";

$lang["Shopping Cart Feature Options"] = "Shopping Cart Feature Options";
$lang["Page Header:"] = "Page Header:";
$lang["Welcome To..."] = "Welcome To...";
$lang["Show 'Client Login' Button in search column"] = "Show 'Client Login' Button in search column";
$lang["Allow 'Email to Friend' feature"] = "Allow 'Email to Friend' feature";
$lang["Allow 'Remember Me' feature"] = "Allow 'Remember Me' feature";
$lang["Display Search Box"] = "Display Search Box";
$lang["Place 'Search Column' on which side of page"] = "Place 'Search Column' on which side of page";
$lang["Left"] = "Left";
$lang["Right"] = "Right";
$lang["Display 'text linked' categories"] = "Display 'text linked' categories";
$lang["Allow users to add product comments"] = "Allow users to add product comments";
$lang["If using this option, place an email address to verify submissions in the 'Business Information' section."] = "If using this option, place an email address to verify submissions in the 'Business Information' section.";
$lang["International Options:"] = "International Options:";
$lang["Choose State/Province Display Type:"] = "Choose State/Province Display Type:";
$lang["U.S. States"] = "U.S. States";
$lang["Canadian Provinces"] = "Canadian Provinces";
$lang["U.S. and Canada"] = "U.S. and Canada";
$lang["Text Field"] = "Text Field";
$lang["Do Not Display"] = "Do Not Display";
$lang["Specify Default 'Local' Countries:"] = "Specify Default 'Local' Countries:";

$lang["By specifying a defualt, or 'local' country, customers will not be able to choose a country"] = "By specifying a defualt, or 'local' country, customers will not be able to choose a country";
$lang["for their billing and shipping addresses. Instead, your shopping cart will assume that all customer orders are placed"] = "for their billing and shipping addresses. Instead, your shopping cart will assume that all customer orders are placed";
$lang["from the country you specify. To prevent confusion, you should make prominent mention of this on your website."] = "from the country you specify. To prevent confusion, you should make prominent mention of this on your website.";


$lang["Search Result Settings"] = "Zoekresultaat Instellingen";
$lang["User Defined Button:"] = "User Defined Button:";
$lang["This button links to the 'More Information' page.  Leaving this blank will not show the button at all."] = "This button links to the 'More Information' page.  Leaving this blank will not show the button at all.";
$lang["Show 'Add to Cart' button under thumbnail images instead of 'Buy Now!' on initial searches"] = "Show 'Add to Cart' button under thumbnail images instead of 'Buy Now!' on initial searches";

$lang["How should initial searches sort data"] = "How should initial searches sort data";
$lang["Sku Number"] = "Sku Number";
$lang["Catalog Ref Number"] = "Catalog Ref Number";
$lang["Product Name"] = "Product Name";
$lang["Product Price"] = "Product Price";
$lang["Shipping Variable (B)"] = "Shipping Variable (B)";
$lang["Shipping Variable (C)"] = "Shipping Variable (C)";


$lang["Number of results to display on searches"] = "Number of results to display on searches";
$lang["Search Product"] = "Search Product";
$lang["Browse Categories"] = "Browse Categories";
$lang["Category"] = "Category";
$lang["Product"] = "Product";
$lang["Sub-Total"] = "Sub-Total";
$lang["Checkout Now"] = "Checkout Now";
$lang["Search Column Color Scheme"] = "Search Column Color Scheme";
$lang["Header Background"] = "Header Background";
$lang["Header Text"] = "Header Text";
$lang["Shopping Cart Background"] = "Shopping Cart Background";
$lang["Shopping Cart Text"] = "Shopping Cart Text";
$lang["Or choose a pre-defined color scheme"] = "Or choose a pre-defined color scheme";
$lang["Choose Scheme"] = "Choose Scheme";
$lang["America"] = "America";
$lang["Classic"] = "Classic";
$lang["Earth"] = "Earth";
$lang["Movies"] = "Movies";
$lang["Neon Green"] = "Neon Green";
$lang["Sports"] = "Sports";
$lang["Save Display Settings"] = "Sla Weergaveinstellingen Op";


// privacy_policy.php
// ---------------------------------
$lang["Shopping Cart: Privacy Policy"] = "Shopping Cart: Privacy Policy";

$lang["Standardized eCommerce systems use a privacy policy to disclose how systems operate. The one provided here is generic"] = "Standardized eCommerce systems use a privacy policy to disclose how systems operate. The one provided here is generic";
$lang["and covers all technical issues regarding the operation of this shopping cart system such as session management and cookies."] = "and covers all technical issues regarding the operation of this shopping cart system such as session management and cookies.";
$lang["You may wish to modify this policy statement to your particular business needs. It should disclose all information pertaining"] = "You may wish to modify this policy statement to your particular business needs. It should disclose all information pertaining";
$lang["to the use and storage of all data gathered from the checkout process."] = "to the use and storage of all data gathered from the checkout process.";

$lang["Save Privacy Statement"] = "Save Privacy Statement";


// shipping_policy.php
// ---------------------------------
$lang["Shopping Cart: Shipping Policy"] = "Shopping Cart: Shipping Policy";

$lang["Your shipping policy informs your customers of how and when you ship the items that they purchase."] = "Your shipping policy informs your customers of how and when you ship the items that they purchase.";
$lang["Be as detailed as possible here and note any special charges that may occur."] = "Be as detailed as possible here and note any special charges that may occur.";

$lang["Save Shipping Policy"] = "Save Shipping Policy";


// returns_policy.php
// ---------------------------------
$lang["Shopping Cart: Returns/Exchanges Policy"] = "Shopping Cart: Returns/Exchanges Policy";

$lang["If your customers wish to return of exchange an item purchased online"] = "If your customers wish to return of exchange an item purchased online, please detail the process<BR>they must adhere to. If all sales are final, say that here.";
$lang["Save Returns/Exchanges Policy"] = "Save Returns/Exchanges Policy";


// other_policies.php
// ---------------------------------
$lang["Shopping Cart: Other Policies"] = "Shopping Cart: Other Policies";

$lang["Use this section to list other types of policies that you may have for your site."] = "Use this section to list other types of policies that you may have for your site.";
$lang["Remember to title each policy as it will displayed as is."] = "Remember to title each policy as it will displayed as is.";
$lang["Save Policy Statement"] = "Save Policy Statement";


// view_orders.php
// ---------------------------------
$lang["View/Retrieve Orders"] = "Bekijken/Ophalen Orders";


$lang["Displaying order numbers"] = "Displaying order numbers";
$lang["Search results for"] = "Search results for";
$lang["Displaying all orders between"] = "Displaying all orders between";
$lang["Download Results"] = "Download Results";
$lang["Print Results"] = "Print Results";
$lang["New Search"] = "New Search";
$lang["Order Number"] = "Order Number";
$lang["Order Date"] = "Order Date";
$lang["Order Time"] = "Order Time";
$lang["Customer"] = "Customer";
$lang["Payment Method"] = "Payment Method";
$lang["Status"] = "Status";
$lang["Total Sale"] = "Total Sale";
$lang["Transaction ID"] = "Transaction ID";
$lang["Invoice"] = "Invoice";
$lang["No invoices where found matching your search. Please try again."] = "No invoices where found matching your search. Please try again.";


// search.inc
// ---------------------------------
$lang["Search Orders"] = "Search Orders";
$lang["Select your prefered search method"] = "Select your prefered search method";
$lang["Show order numbers"] = "Show order numbers";
$lang["From"] = "From";
$lang["To"] = "To";
$lang["Select how results should be sorted for viewing"] = "Select how results should be sorted for viewing";
$lang["Sort by"] = "Sort by";
$lang["Order Date"] = "Order Date";
$lang["Order Number"] = "Order Number";
$lang["Order by"] = "Order by";
$lang["Customer Name"] = "Customer Name";
$lang["Total Sale"] = "Total Sale";
$lang["Payment Method"] = "Payment Method";
$lang["Status"] = "Status";
$lang["Transaction ID"] = "Transaction ID";
$lang["Ascending"] = "Ascending";
$lang["Descending"] = "Descending";
$lang["Date range"] = "Date range";
$lang["Format"] = "Format";
$lang["Search for keywords"] = "Search for keywords";


// view_invoice.php
// ---------------------------------

$lang["PURGE"] = "PURGE";
$lang["PRINT"] = "PRINT";
$lang["EXIT"] = "EXIT";
$lang["Payment Method"] = "Payment Method";
$lang["Order Status"] = "Order Status";


#################################################
## EVENT CALENDAR    					           ##
#################################################

// event_calendar.php
// ---------------------------------

$lang["Event Calendar: Main Menu"] = "Agenda: Hoofd Menu";
$lang["Search Events"] = "Zoek Agenda Items";
$lang["Display Settings"] = "Weergave Instellingen";
$lang["Category Setup"] = "Categorie-indeling";
$lang["Edit View"] = "Bewerk Getoond";

// add_event.php
// ---------------------------------
$lang["Add Calendar Event"] = "Voeg Een Gebeurtenis Toe";

// build_month.php
// ---------------------------------

$lang["Add Event"] = "Voeg Toe";

// category_setup.php
// ---------------------------------
$lang["Add/Modify Calendar Categories"] = "Voeg Agenda Categorie Toe/Bewerk Categorie";
$lang["Create New Category"] = "Creëer Nieuwe Categorie";
$lang["Add Category"] = "Voeg Categorie Toe";
$lang["Current Categories"] = "Bestaande Categorieën";

// display_settings.php
// ---------------------------------
$lang["Calendar Display Settings"] = "Agenda Weergaveinstellingen";

// search_events.php
// ---------------------------------
$lang["Search Event Calendar"] = "Zoek Gebeurtenis Agenda";

// "Found [X] events that match your search criteria."
$lang["Found"] = "Gevonden";
$lang["events that match your search criteria"] = "Gebeurtenissen die aan uw zoekcriteria voldoen";

$lang["Sorry, no events where found for your search. Please try again."] = "Sorry, geen gezochte gebeurtenissen gevonden. Probeer a.u.b. opnieuw.";


// add_events_form.php
// ---------------------------------

$lang["Apply To"] = "Pas Toe Op";
$lang["THIS EVENT ONLY"] = "ALLEEN DEZE GEBEURTENIS";
$lang["All occurrences of this event"] = "Alle gelijke gebeurtenissen";
$lang["Save Event"] = "Sla Gebeurtenis Op";
$lang["Event Date"] = "Datum Gebeurtenis";
$lang["Start Time"] = "Aanvangs Tijd";
$lang["Event Title"] = "Titel Gebeurtenis";
$lang["Event Details (Description)"] = "Datails van Gebeurtenis (Beschrijving)";
$lang["Event Category"] = "Gebeurtenis Categorie";
$lang["All"] = "Alle";
$lang["Security Code (Group)"] = "Veiligheidscode (Groep)";
$lang["Public"] = "Publiek";
$lang["When saving or changing this event, email a notice to the following email addresses"] = "Bij opslaan of wijzigen van deze gebeurtenis, stuur email bericht aan de volgende emailadressen (gescheiden door komma's)";
$lang["Event Recurrence"] = "Herhaling Gebeurtenis";
$lang["No Recurrence"] = "Geeb Herhaling";
$lang["Daily"] = "Dagelijks";
$lang["Weekly"] = "Wekelijks";
$lang["Monthly"] = "Maandelijks";
$lang["Yearly"] = "Jaarlijks";
$lang["Daily Pattern"] = "Dagelijks Patroon";

//full sentence = "This event should re-occur every [number] days"
$lang["This event should re-occur every"] = "Deze Gebeurtenis moet herhaald worden om de";
$lang["days"] = "dagen";

$lang["Weekly Pattern"] = "Wekelijks Patroon";

//full sentence = "This event should re-occur every [number] weeks on"
$lang["This event should re-occur every"] = "Deze Gebeurtenis moet herhaald worden om de";
$lang["weeks on"] = "week/weken op";


$lang["Sunday"] = "Zondag";
$lang["Monday"] = "Maandag";
$lang["Tuesday"] = "Dinsdag";
$lang["Wednesday"] = "Woensdag";
$lang["Thursday"] = "Donderdag";
$lang["Friday"] = "Vrijdag";
$lang["Saturday"] = "Zaterdag";
$lang["Monthly Pattern"] = "Maandelijks Patroon";
$lang["This event should re-occur on the"] = "Deze Gebeurtenis moet herhaald worden op de";
$lang["of each month"] = "van elke maand";
$lang["Yearly Pattern"] = "Jaarlijks Patroon";
$lang["You have selected for this event to occurr every year on"] = "U heeft gekozen deze Gebeurtenis elk jaar te laten plaatsvinden op"; // "every year on [X month]"

$lang["This event will start on the date of the selected 'Event Date' and continue for how long"] = "Deze Gebeurtenis zal starten op de geselecteerde datum en hoe lang gecontinueerd worden"; //"?"
$lang["No End Date"] = "Geen Einddatum";

//"End after [X] occurences."
$lang["End after"] = "Beëindigen na";
$lang["occurrences"] = "keer";


// calendar_settings_form.php
// ---------------------------------

$lang["Color Scheme"] = "Kleur Samenstelling";
$lang["Header Text"] = "Header Tekst";
$lang["Select Text Color"] = "Selecteer Testkleur";
$lang["Header Background"] = "Header Achtergrond";
$lang["Select Background Color"] = "Selecteer Achtergrondkleur";
$lang["Pre-Defined Schemes"] = "Vooringestelde Kleuren";
$lang["Color Schemes"] = "Kleur Samenstellingen";
$lang["Default Standard"] = "Default Standaard";
$lang["Reds"] = "Rode Kleuren";
$lang["Allow authorized users to maintain personal calendars"] = "Sta geautoriseerde gebruikers toe persoonlijke aganda's bij te houden"; // "?"

$lang["Initial Calendar Display Layout"] = "Initiële Agenda Layout";
$lang["Monthly"] = "Maandelijks";
$lang["Weekly"] = "Wekelijks";
$lang["Allow the public to submit events for inclusion"] = "Sta publiek toe gebeurtenissen aan de agenda toe te voegen voor opname"; // "?"
$lang["If so, where should confirmations be emailed to"] = "Indien ja, waar moeten bevestigingen naartoe gemaild worden"; // "?"
$lang["Color Preview"] = "Bekijk Kleuren";
$lang["Calendar Header"] = "Agenda Header";
$lang["Event Dates"] = "Data Gebeurtenissen";
$lang["Save Display Settings"] = "Sla Weergaveinstellingen Op";


// event_search_form.php
// ---------------------------------
$lang["Search Event Calendar"] = "Zoek in Agenda";
$lang["Search for Keywords"] = "Zoek naar Zoekwoorden";
$lang["Search in Month/Year"] = "Zoek in Maand/Jaar";
$lang["Search In Category"] = "Zoek in Categorie";


// update_events_form.php
// ---------------------------------
$lang["Apply To"] = "Pas Toe Op";
$lang["THIS INDIVIDUAL EVENT ONLY"] = "ALLEEN DEZE INDIVIDUELE GEBEURTENIS";
$lang["ALL OCCURRENCES OF THIS EVENT"] = "ALLE GELIJKE GEBEURTENISSEN";
$lang["Event Date"] = "Datum Gebeurtenis";
$lang["Start Time"] = "Aanvangstijd";
$lang["End Time"] = "Eindtijd";
$lang["Security Code (Group)"] = "Veiligheidscode (Groep)";
$lang["Use commas to seperate multiple email addresses"] = "Gebruik komma's om meerdere emailadressen te scheiden";
$lang["Event Recurrence"] = "Herhaling Gebeurtenis";
$lang["No Recurrence"] = "Geen Herhaling";
$lang["Daily Pattern"] = "Dagelijks Patroon";

// "This event is a part of [X] other recursive events."
$lang["This event is a part of"] = "Deze Gebeurtenis is onderdeel van";
$lang["other recursive events"] = "andere zich herhalende Gebeurtenissen";

$lang["Master Event"] = "Hoofd Gebeurtenis";
$lang["Recursive Event"] = "Zich Herhalende Gebeurtenis";

#################################################
## E-NEWSLETTER    					              ##
#################################################
// enewsletter.php
// ---------------------------------

$lang["eNewsletter System: Main Menu"] = "eNiewsbrief Systeem: Hoofd Menu";

// "You have selected to delete the campaign [X]. Do you wish to continue with this action?"
$lang["You have selected to delete the campaign"] = "U heeft gekozen de verzending ";
$lang["Do you wish to continue with this action"] = "te verwijderen. Wilt u doorgaan met deze handeling";

// "You have selected to send the campaign [X] to [X] people total. Do you wish to continue with this action?"
$lang["You have select to send the campaign"] = "U heeft gekozen de verzending";
$lang["to"] = "aan in totaal";
$lang["people total.  Do you wish to continue with this action"] = "mensen te verzenden. Wilt u hiermee doorgaan";

$lang["Your campaign has been sent"] = "U Verzending is verstuurd"; // "!"
$lang["SENDING CAMPAIGN"] = "BEZIG MET VERZENDING";
$lang["This may take up to 30 seconds"] = "Dit kan tot 30 secondeb duren";
$lang["Create New Campaign"] = "Creëer Nieuwe Verzending";
$lang["HTML Emails"] = "HTML Emails";
$lang["TEXT Emails"] = "TEKST Emails";
$lang["Sent Date"] = "Datum Verz.";
$lang["Campaign Name"] = "Naam Verzending";
$lang["Data Table"] = "Data Tabel";
$lang["Recipients"] = "Ontvangers";
$lang["Views"] = "Gezien";
$lang["Status"] = "Status";
$lang["View"] = "Bekijken";
$lang["Action"] = "Actie";
$lang["Pending"] = "Afwachting";
$lang["SENT"] = "WEG";
$lang["View"] = "Bekijk";
$lang["Send Now"] = "Verstuur";
$lang["Manually Unsubscribe Email Addresses"] = "Emailadresen Handmatig Verwijderen";
$lang["Change to 'Send through hostname' only if you're getting an error when you try to send newsletters"] = "Wijziging 'Verzenden via hostname' alleen als u een foutmelding ontvangt wanneer u probeert te nieuwsbrieven verzenden";
$lang["SMTP Hostname"] = "SMTP Hostnaam";
$lang["SMTP Username"] = "SMTP Gebruikersnaam";
$lang["SMTP Password"] = "SMTP Wachtwoord";
$lang["SMTP Port"] = "SMTP Haven";
$lang["Default"] = "Standaard";
$lang["Custom"] = "Gebruik";

// create_campaign.php
// ---------------------------------

$lang["eNewsletter Campaign Setup Wizard"] = "eNewsletter Verzending Setup Wizard";
$lang["Please select a table name to use for this campaign"] = "Selecteer a.u.b een tabelnaam voor deze Verzending";
$lang["Please enter a valid campaign name before continuing"] = "Kies a.u.b. een geldige naam voor deze Verzending voor u verder gaat";
$lang["You need to select a template and content file in order to preview"] = "U moet een template en een file voor de inhoud kiezen om te kunnen bekijken";
$lang["You need to select a template and content file in order to continue"] = "U moet een template en een file voor de inhoud kiezen om door te kunnen gaan";
$lang["This may take a few seconds"] = "Dit kan enkele seconden duren";
$lang["STEP"] = "STEP";
$lang["ASSIGN CAMPAIGN NAME"] = "GEEF VERZENDING EEN NAAM";
$lang["A. Give this new campaign a name for easy identification on the campaign manager page"] = "A. Geef deze nieuwe Verzending een naam voor eenvoudige identificatie op de Verzendingen Manager pagina";
$lang["B. Choose a database table that contains the email addresses for this campaign:"] = "B. Kies een database tabel die de emailadressen bevat voor deze Verzending:";
$lang["Next"] = "Volgende";
$lang["Field Names"] = "Veldnamen";
$lang["MATCH REQUIRED FIELD DATA"] = "KOPPEL DE VEREISTE VELD DATA";

// "In order to build this campaign using ["X" dB Table], you will need to tell..."
$lang["In order to build this campaign using"] = "Om deze Verzending gereed te maken met behulp van de tabel";
$lang["you will need to tell the system which fields in the table correspond to the data needed by the eNewsletter system when sending this campaign"] = "moet u het systeem vertellen welke velden in de tabel corresponderen met de data die het eNieuwsbrief systeem nodig heeft om deze verzendig te versturen";


$lang["A. Field containing <U>FIRST NAME</U> data"] = "A. Veld waar de <U>VOORNAAM</U> in staat";
$lang["B. Field containing <U>EMAIL ADDRESS</U> data"] = "B. Veld met het <U>EMAILADRES</U>";
$lang["C. Field containing the <U>EMAIL TYPE</U> data"] = "C. Veld met de <U>EMAIL TYPE</U> gegevens";
$lang["If the user has HTML or TEXT preference"] = "Of de gebruiker een voorkeur heeft voor HTML of TEKST";
$lang["OWNER INFORMATION"] = "INFORMATIE EIGENAAR";
$lang["This campaign will arrive as an email to your list."] = "This campaign will arrive as an email to your list.";
$lang["Please indicate what email address it will<BR>come from and the subject line"] = "Please indicate what email address it will<BR>come from and the subject line"; // ":"

$lang["A. <U>From</U> email address"] = "A. <U>Afzender</U> emailadres";
$lang["B. <U>Subject Line</U> of this campaign"] = "B. <U>Onderwerp Regel</U> van deze Verzending";
$lang["Next"] = "Volgende";
$lang["Newsletter Content Pages"] = "eNiewsbrief Inhoud Pagina's";
$lang["[NONE] Template Contains Content"] = "[GEEN] Template Bevat Inhoud";
$lang["HTML CONTENT"] = "HTML INHOUD";
$lang["Please select the template file and page name which contains the enewsletter content for<BR>sending the HTML version of this campaign"] = "Selecteer a.u.b. de template file en de pagina waarop de inhoud staat van de Enieuwsbrief<BR>voor de HTML versie van deze Verzending";
$lang["Select the template to use with this campaign"] = "Selecteer de te gebruiken template voor deze Verzending";
$lang["Browse Templates"] = "Doorzoek Templates";
$lang["Select a page to use for your content"] = "Selecteer de pagina waarop de inhoud voor deze Verzending staat";

// "For those users that have selected to receive text only campaigns, please create the text that will..."
$lang["For those users that have selected to receive text only campaigns"] = "Creëer a.u.b voor die gebruikers die hebben gekozen voor het ontvangen van uitsluitend tekst emails";
$lang["please create the text that will be sent to those users as well as embedded in the header of the HTML newsletter in case of errors"] = "de tekst die verzonden zal worden aan die gebruikers almede de kop zoals in de header van de HTML versie staat voor het geval er zich fouten voordoen.";

$lang["Creating the campaign does NOT send emails now."] = "Het creëren van de Verzending leidt NU niet tot het versturen van emails.";

$lang["Error: This campaign does not appear to have any email addresses to send to"] = "FOUT: Deze Verzending lijkt geen emailadressen te bevatten om de Verzending naartoe te sturen";
$lang["HTML Types found"] = "HTML Types gevonden";
$lang["TEXT Types found"] = "TEXT Types gevonden";
$lang["DevString"] = "DevString";
$lang["DevString"] = "DevString";
$lang["HTML"] = "HTML";
$lang["TEXT"] = "TEKST";
$lang["Error Writing to Data Table (Could not create campaign): This is a programming error, consult with your webmaster."] = "FOUT bij wegschrijven naar Data Tabel(Kon geen Verzending Creëren): Dit is een programmeerfout, neem contact op met uw webmaster.";
$lang["Campaign Created"] = "Verzending Gecreëerd";
$lang["Campaign Manager"] = "Verzendingen Manager";

$lang["Your campaign has been added with pending status. You may now preview or"] = "Uw Verzending is toegevoegd met de In Afwachting status. U kunt hem nu bekijken of";
$lang["SEND your campaign from the \"Campaign Manager\" Interface."] = "VERSTUUR uw Verzending via het\"Verzendingen Manager\" Scherm.";


// news-browse_templates.php
// ---------------------------------
$lang["Browse Website Templates"] = "Zoek in Website Templates";
$lang["Select a category to browse from the drop down box above. When your find a template you like, simply click the template to continue."] = "Selecteer een categorie om te doorzoeken in de 'drop down box' hierboven. Als u een template vind dat u aanstaat, klik dan eenvoudig op de template om door te gaan.";


// preview.php
// ---------------------------------

$lang["View HTML Preview"] = "Bekijk HTML Voorbeeld";
$lang["View TEXT Preview"] = "Bekijk TEKST Voorbeeld";
$lang["Close Preview Window"] = "Sluit Voorbeeldvenster";

// send_now.php
// ---------------------------------
$lang["If you do not wish to receive this email, unsubscribe to this service now."] = "Als u deze email niet wenst te ontvangen, schrijf u dan nu uit.";

// view_setup.php
// ---------------------------------
$lang["Visit our Website"] = "Bezoek onze Website";


#################################################
## DATABASE TABLE MANAGER   		              ##
#################################################
// database_tables.php
// ---------------------------------

$lang["Database Table Manager: Main Menu"] = "Database Tabel Manager: Hoofd Menu";
$lang["Create New Data Table"] = "Creëer Nieuwe Data Tabel";
$lang["Create a Search"] = "Creëer een Zoekopdracht";
$lang["Delete a Table"] = "Verwijder Tabel";
$lang["Modify Selected Table"] = "Bewerk Geselecteerde Tabel";
$lang["Enter/Edit Record Data"] = "VoerIn/Bewerk Gegevens";
$lang["Please select a user data table."] = "Selecteer a.u.b. een gebruikers data tabel.";
$lang["Batch Authenticate Users"] = "Authenticatie Gebruikers";


// auth_users.php
// ---------------------------------

$lang["Authenticate Users : Add Authorized Users via Data Table"] = "Authenticatie Gebruikers: Voeg Geautoriseerde Gebruikers Toe Via Data Tabel";
$lang["You must select a field name for all red selection boxes."] = "U moet een veldnaam selecteren voor alle rode selectie boxen.";
$lang["The second selection under 'user/company full name' is optional."] = "De tweede selectie onder 'gebruiker/bedrijf volledige naam' is optioneel.";
$lang["This may take a few seconds..."] = "Dit kan enkele seconden duren ...";
$lang["CAN NOT AUTHENTICATE USERS VIA TABLE"] = "KAN GEBRUIKERS NIET VIA TABEL AUTHENTICEREN";

$lang["This would indicate that you have not set-up a security code (group) OR"] = "Het lijkt erop dat u geen Veiligheidscode (groep) hebt toegewezen OF";
$lang["you have not created at least (1) authorized user."] = "u heeft niet tenminste 1 geautoriseerde gebruiker aangemaakt.";

$lang["You will need to do these things before adding authenticated users via a table dump."] = "U moet deze zaken regelen alvorens geauthenticeerde gebruikers toe te voegen via een 'tabel dump'.";
$lang["Current UDT Tables..."] = "Bestaande UDT Tabellen...";
$lang["SELECT DATA TABLE USAGE"] = "SELECTEER GEBRUIK DATA TABEL";
$lang["Select the User Defined Table (UDT) that you wish to use as your authenticated user data:"] = "Selecteer de 'User Defined Table' (UDT= door gebruiker gemaakte tabel) die u wilt gebruiken voor uw geauthenticeerde Gebruikers gegevens:";
$lang["Select Field Name"] = "Selecteer Veldnaam";

// "CONFIGURE AUTHENTICATION DATA (AUTORIZE [X] USERS)."
$lang["CONFIGURE AUTHENTICATION DATA"] = "CONFIGUREER AUTHENTICATIE GEGEVENS";
$lang["AUTHORIZE"] = "AUTHORISEER";
$lang["USERS"] = "GEBRUIKERS";

// "For each field needed to register an authenticated user, match the field name in [TABLE NAME] to the<BR>required authenticated user fields."
$lang["For each field needed to register an authenticated user, match the field name in "] = "Voor elk veld dat nodig is om een geauthenticeerde gebruiker te registreren, koppel de veldnaam in ";
$lang["to the<BR>required authenticated user fields."] = "aan de vereiste<BR>Geauthenticeerde Gebruikers velden.";

$lang["Next"] = "Volgende";
$lang["New Authenticated Users Added"] = "Nieuwe Geauthenticeerde Gebruikers Toegevoegd";
$lang["Database Menu"] = "Database Menu";
$lang["You can view and/or edit individual user settings through<BR>the Secure Users feature."] = "U kunt de individuele gebruikers instellingen bekijken of bewerken via het<BR>'Veilige Gebruikers' scherm.";


// create_table.php
// ---------------------------------
$lang["Table Manager: Create New Table"] = "Tabel Manager: Creëer Nieuwe Tabel";
$lang["Error"] = "FOUT";
$lang["BACK TO TABLE BUILD"] = "TERUG NAAR TABEL ONTWERP";

$lang["1. What is the name for this table"] = "1. Wat is de naam voor deze tabel";
$lang["NOTE: Do not use numbers or spaces in names; these are invalid"] = "N.B.: Gebruik geen nummers of spaties in namen; deze zijn ongeldig";
$lang["SQL table names. You may use underscores to represent spaces."] = "SQL tabel namen. U mag 'underscores' (_) gebruiken in plaats van spaties.";
$lang["Table Name"] = "Tabel Naam";
$lang["Invalid Table Name"] = "Ongeldige Tabel Naam";
$lang["2. How many fields will this table contain"] = "2. Hoeveel velden moet deze tabel hebben"; //"?"

$lang["The data you have entered is not formated properly"] = "De data die u heeft ingevoerd zijn niet het in juiste formaat.";
$lang["in order to create your table. Please check your"] = "om uw tabel te creëren controleer a.u.b. uw instellingen";
$lang["setup and try again."] = "en probeer het opnieuw.";
$lang["The last error calculation occurred on line item"] = "De laatste fout berekening vond plaats op regel item";

$lang["Create Table"] = "Creëer Tabel";
$lang["NOTE"] = "N.B.";
$lang["Do not use numbers or spaces in names; these are invalid SQL field names."] = "Gebruik geen nummers of spaties in namen; dit zijn ongeldige SQL veldnamen.";
$lang["You may use underscores(_) to represent spaces."] = "U mag 'underscores'(_) gebruiken in plaats van spaties.";
$lang["Novices who are unsure about what some of these options mean, simply input your field names leaving the default selection as is."] = "Onervaren gebruikers die niet weten wat sommige opties inhouden, voer eenvoudig uw veldnamen in en laat de 'default' instelling gewoon staan.";
$lang["This will insure proper operation."] = "Dit garandeert een juiste werking.";
$lang["By default, a Primary Key field and Image field will also be added automatically to your table."] = "Een 'Primare Sleutel' veld en een Afbeelding veld worden ook automatisch aan uw tabel toegevoegd.";
$lang["Field Name"] = "Veldnaam";
$lang["Field Type"] = "Veldtype";
$lang["Field Length"] = "Veldlengte";
$lang["Default Value"] = "Default Waarde";


// delete_table.php
// ---------------------------------

$lang["Table Manager: Delete Table"] = "Tabel Manager: Verwijder Tabel";
$lang["WARNING"] = "WAARSCHUWING";

// "YOU ARE ABOUT TO DELETE THE TABLE [TABLE NAME] AND LOSE ALL RECORD DATA CONTAINED INSIDE OF IT."
$lang["YOU ARE ABOUT TO DELETE THE TABLE"] = "U STAAT OP HET PUNT DE TABEL TE VERWIJDEREN";
$lang["AND LOSE ALL RECORD DATA"] = "EN ZULT ALLE RECORD GEGEVENS KWIJT RAKEN";
$lang["CONTAINED INSIDE OF IT."] = "DIE HIJ BEVAT.";
$lang["Are you sure you wish to do this now"] = "Weet u zeker dat u dit nu wilt doen"; //"?"
$lang["You did not select a table to delete."] = "U heeft geen tabel geselecteerd om te verwijderen.";
$lang["NOTE"] = "N.B.";
$lang["THIS PROCESS CAN NOT BE REVERSED ONCE COMPLETED."] = "DIT PROCES KAN NIET WORDEN TERUGGEDRAAID ALS HET EENMAAL IS AFGEROND.";
$lang["ALL DATA WILL BE LOST WHEN THIS TABLE IS DELETED."] = "ALLE DAT ZULLEN WEG ZIJN ALS DEZE TABEL WORDT VERWIJDERD.";
$lang["YOU WILL HAVE ONE CHANCE TO CONFIRM, BUT ONCE YOU 'OK' THE CONFIRMATION, THE TABLE WILL BE DELETED"] = "U HEEFT 1 KANS OM TE BEVESTIGEN, MAAR ALS U OP 'OK' KLIKT ZAL DE TABEL WORDEN VERWIJDERD"; //"!"
$lang["Delete Table"] = "Verwijder Tabel";
$lang["Delete Selected Table"] = "Verwijder Geselecteerde Tabel";
$lang["Cancel Delete"] = "Annuleer Verwijderen";

// enter_edit_data.php
// ---------------------------------
$lang["Table Manager: Enter/Edit Record Data"] = "Tabel Manager: VoerIn/Wijzig Record Gegevens";
$lang["You have selected to delete this record."] = "U heeft gekozen dit record te verwijderen.";
$lang["You will not be able to undo this choice."] = "U kunt deze keuze niet ongedaan maken.";
$lang["Do you wish to continue with this action"] = "Wilt u doorgaan met deze handeling"; //"?"

$lang["Find Record"] = "Zoek Record";
$lang["ADD_NEW"] = "VOEG NIEUW TOE";
$lang["Add New Record"] = "Voeg Nieuwe Record Toe";
$lang["Total Number of Records in Table"] = "Totaal aantal Records in Tabel";
$lang["Number of Records Found in Search"] = "Aantal gevonden Records bij Zoeken";
$lang["OPTION"] = "OPTIE";
$lang["Previous"] = "Vorige";


// modify_table.php
// ---------------------------------

$lang["Table Manager: Modify Table"] = "Table Manager: Tabel Aanpassen";
$lang["Modify Table"] = "Tabel Aanpassen";
$lang["Update Complete"] = "Update Gereed";
$lang["Field Name"] = "Veldnaam";
$lang["Field Type"] = "Veldtype";
$lang["Field Length"] = "Veldlengte";
$lang["INT"] = "INT";
$lang["DATE"] = "DATUM";
$lang["Update Table"] = "Update Tabel";
$lang["The data you have entered is not formated properly."] = "De data die u heeft ingevoerd zijn niet het in juiste formaat.";
$lang["Please check your setup and try again."] = "Controleer a.u.b. uw instellingen en probeer het opnieuw.";
$lang["Add New Field to"] = "Voeg Nieuw Veld Toe Aan"; // "[TABLE NAME]"
$lang["Field Name"] = "Veldnaam";
$lang["Field Type"] = "Veldtype";
$lang["Field Length"] = "Veldlengte";
$lang["Default Value"] = "Default Waarde";
$lang["Rename Table"] = "Hernoem Tabel";


// wizard_start.php
// ---------------------------------
$lang["Data-Table Search Wizard"] = "Data-Tabel Zoek Wizard";
$lang["This may take a few seconds..."] = "Dit kan enkele seconden duren...";
$lang["ASSIGN SEARCH NAME"] = "VOER ZOEKNAAM IN";
$lang["Give this search a name."] = "Geef deze Zoekopdracht een naam.";
$lang["This will be used as an identifier in the Page Editor, and displayed to site visitors when searching"] = "Deze zal gebruikt worden voor herkenning in de Pagina Editor, en getoond aan de bezoeker van de site als hij zoekt.";
$lang["SELECT DATA TABLE USAGE"] = "SELECTEER DATA TABEL GEBRUIK";
$lang["Select the User Defined Table (UDT) that this search will utilize"] = "Selecteer de 'User Defined Table' (UDT) die deze zoekopdracht zal gebruiken";
$lang["Back"] = "Terug";
$lang["CONFIGURE SEARCH FORM"] = "CONFIGUREER ZOEKFORMULIER";
$lang["Configure the search criteria by which site visitors will search"] = "Configureer de zoekcriteria aan de hand waarvan bezoekers zullen zoeken";
$lang["NOTE: You will be able to preview the form in the next step and make changes if you wish."] = "N.B.: U bent in staat om het zoekformulier tijdens de volgende stap te bekijken en wijzigingen aan te brengen als u dat wilt.";
$lang["You will be able to preview the form in the next step and make changes if you wish"] = "U bent in staat om het zoekformulier tijdens de volgende stap te bekijken en wijzigingen aan te brengen als u dat wilt";
$lang["If you wish to utilize a keyword search, select which fields should be searched."] = "Als u een keyword zoekopdracht wilt gebruiken, selecteer dan welke velden doorzocht moeten worden.";
$lang["DROP DOWN BOX SELECTION FIELDS"] = "DROP DOWN BOX SELECTIEVELDEN";
$lang["Fields selected here will display all records within as options in a drop down box."] = "Hier geselecteerde Velden zullen alle records daarin als optie laten zien in een 'drop down box'.";
$lang["VERIFY SEARCH FORM"] = "KEUR ZOEKFORMULIER GOED";
$lang["This is exactly the form site visitors will see when using this search."] = "Dit is precies het formulier dat bezoekers zullen zien als zij Zoeken gebruiken.";
$lang["Click the back button to make any changes."] = "Klik op de 'Terug' button om veranderingen aan te brengen.";
$lang["All Fields"] = "Alle Velden";
$lang["SEARCH"] = "ZOEK";
$lang["Search by Keyword"] = "Zoeken op Keyword";
$lang["Separate multiple keywords by spaces"] = "Scheidt meerdere Keywords door spaties";
$lang["Detail Search"] = "Details Zoeken";
$lang["Define Search Method"] = "Definieer Zoek Methode";
$lang["Keyword Only"] = "Alleen Keywords";
$lang["Selections Only"] = "Alleen Selecties";
$lang["Keyword AND Selections"] = "Keyword EN Selecties";
$lang["Keyword OR Selections"] = "Keyword OF Selecties";
$lang["Search Now"] = "Zoek Nu";
$lang["Back"] = "Terug";
$lang["SEARCH RESULTS DISPLAY"] = "ZOEKRESULTATENSCHERM";
$lang["There are two steps used when displaying the results of a search."] = "Er worden twee stappen gebruikt om de zoekresultaten te tonen.";
$lang["The first data displayed is called the 'Initial Results', and displays the selected field data in a chart format."] = "De eerste gegevens die getoond worden heten de 'Eerste Resultaten', en toont de geselecteerde veld gegevens in een overzicht.";
$lang["At that point, site visitors may select to <I>View Details</I>, which displays the 'Details Page'."] = "Op dat moment kunnen bezoekers voor <I>Toon Details</I> kiezen, hetgeen de 'Details Pagina' laat zien.";
$lang["This page shows more detailed information about the choosen record."] = "Deze pagina toont meer gedetailleerde informatie over het gekozen record.";
$lang["Select for each field when and where it's value should be displayed during the above process"] = "Selecteer voor elk veld wanneer de gegevens getoond moeten worden tijdens het bovenstaande proces";
$lang["Field Name"] = "Veldnaam";
$lang["Display Setting"] = "Toon Instellingen";
$lang["Don't Display"] = "Niet Tonen";
$lang["Initial Results"] = "Eerste Resultaten";
$lang["Details Page"] = "Details Pagina";
$lang["Display on Both"] = "Toon op Beide";
$lang["DETAIL VIEW SETUP AND SECURITY"] = "DETAIL SCHERM EN BEVEILIGING";
$lang["Select the display format (look and feel) of the 'Details Page'"] = "Selecteer het scherm format (gezicht en gevoel) van de 'Details Pagina'";
$lang["Standard (Default)"] = "Standaard (Default)";
$lang["Custom PHP Include"] = "Custom PHP Include";
$lang["Select a security code (group) required to access this search"] = "Selecteer een security code (groep) die nodig is om toegang te krijgen tot deze zoekopdracht";
$lang["Public is Default"] = "Publiek is Default";
$lang["Build Search Now"] = "Creëer Zoekopdracht Nu";
$lang["Search Creation Complete"] = "Zoekopdracht Creatie Voltooid";
$lang["Database Menu"] = "Database Menu";
$lang["Use the 'Searchabe Database' object in the page editor to place your search on a site page."] = "Gebruik het 'Doorzoek Database' object in uw Pagina Editor om uw zoekopdracht op een site pagina te plaatsen.";


#################################################
## SECURE USERS MODULE     		              ##
#################################################
// security.php
// ---------------------------------
$lang["Page/Product Security"] = "Pagina/Product Beveiliging";
$lang["Authorized Users"] = "Geauthoriseerde Gebruikers";
$lang["Create New User"] = "Creëer Nieuwe Gebruiker";
$lang["Current Authorized Users"] = "Huidge Geauthoriseerde Gebruikers";
$lang["Select User"] = "Selecteer Gebruiker";
$lang["Security Codes"] = "Beveiligings Codes";
$lang["Create New Security Code (Group)"] = "Creëer Nieuwe Beveiligingsdode (= Groep)";
$lang["Name"] = "Naam";
$lang["Create Group"] = "Creëer Groep";
$lang["ACTION"] = "ACTIE";
$lang["Current Security Codes (Groups)"] = "Huidige Beveiligingscodes(Groepen)";
$lang["Select Code"] = "Select Code";
$lang["How does this module work"] = "Hoe werkt deze module";
$lang["Click Here"] = "Klik Hier";


// security_create_user.php
// ---------------------------------

$lang["Create New Authorized User"] = "Creëer Nieuwe Geauthoriseerde Gebruiker";
$lang["You have selected to delete this authorized user."] = "U heeft gekozen deze geauthoriseerde Gebruiker te verwijderen.";
$lang["THIS PROCESS CAN NOT BE REVERSED"] = "DIT PROCES KAN NIET WORDEN TERUGGEDRAAID";
$lang["Select OK to DELETE this user now."] = "Selecteer OK om deze gebruiker nu te VERWIJDEREN.";
$lang["Save Changes"] = "SLa Wijzigingen Op";
$lang["Delete User"] = "Verwijder Gebruiker";
$lang["Authentication Info"] = "Authenticatie Info";
$lang["User Info"] = "Gebruiker Info";


// shared/sec_user_form.inc
// ---------------------------------
$lang["User/Company Full Name"] = "Gebruiker/Bedrijf Volledige Naam";
$lang["User/Company Email Address"] = "Gebruiker/Bedrijf Emailadres";
$lang["Assigned Username"] = "Toegewezen Gebruikersnaam";
$lang["Assigned Password"] = "Toegewezen Password";
$lang["Expiration Date"] = "Beëindigingsdatum";
$lang["Login Redirect Page"] = "Login Redirect Pagina";
$lang["(Module) Shopping Cart"] = "(Module) Shopping Cart";
$lang["What site page should this user be sent to upon login?"] = "Naar welke site paginaa moet deze gebruiker worden gestuurde als hij inlogt?";
$lang["Select the security codes (groups) this user should have access to"] = "Selecteer de beveiligingscodes (= groupen) waartoe deze gebruiker toegang moet hebben";
$lang["There are currently no security codes (groups) created"] = "Er zijn op dit moment geen beveiligingscodes (= groepen) gecreëerd"; //"!"

$lang["All authorized users must be associated with a security group."] = "Alle geauthoriseerde gebrukers moeten zijn toegevoegd aan een beveiligingsgroep.";


// shared/sec_user_form.inc
// ---------------------------------
$lang["(Optional) If you wish for this user to be remembered automatically when using the<BR>shopping cart system, please fill out all the customer data below."] = "(Optioneel) Als u wilt dat deze gebruiker automatisch wordt 'onthouden' als hij het<BR>shopping cart systeem gebruikt, vul dan a.u.b. alle klantgegevens hieronder in.";
$lang["Billing Information"] = "Betalings Informatie";
$lang["First Name"] = "Voornaam";
$lang["Last Name"] = "Achternaam";
$lang["Company Name"] = "Bedrijfsnaam";
$lang["Optional"] = "Optioneel";
$lang["Address"] = "Addres";
$lang["No PO Boxes"] = "Geen Postbussen";
$lang["City/Town/Locality"] = "Plaats";
$lang["Region or Province/State/District"] = "Regio/Provincie/Staat/District";

$lang["Country"] = "Land";

$lang["Postal/Zip Code"] = "Postcode";
$lang["Home Phone Number"] = "Prive Telefoonnummer";
$lang["Country Code"] = "Landcode";
$lang["Email Address"] = "Emailadres";
$lang["INVALID EMAIL ADDRESS"] = "ONGELDIG EMAILADRES";
$lang["Shipping Information"] = "Verzendinformatie";
$lang["First Name"] = "Voornaam";
$lang["Last Name"] = "Achternaam";
$lang["Company Name"] = "Bedrijfsnaam";
$lang["Optional"] = "Optioneel";
$lang["Address"] = "Adres";
$lang["No PO Boxes"] = "Geen Postbussen";
$lang["City/Town/Locality"] = "Plaats";
$lang["Region or Province/State/District"] = "Regio of Provincie/Staat/District";
$lang["State Invalid"] = "Staat Ongeldig";
$lang["Postal/Zip Code"] = "Postcode";
$lang["Ship-To Phone Number"] = "Telefoonnummer Afleveringsadres";
$lang["Country Code"] = "Landcode";




#################################################
## CLIENT-SIDE DISPLAY ELEMENTS		           ##
#################################################

// object_write.php
// ---------------------------------
$lang["Get Directions"] = "Zoek Routebeschrijving";
$lang["Courtesy of"] = "Met dank aan";
$lang["Email this page to a friend"] = "Email deze pagina aan een vriend";
$lang["Sign-up Now"] = "Schrijf Nu In";
$lang["Search Products"] = "Zoek Producten";
$lang["Browse Categories"] = "Doorzoek Categoriën";


// pgm-realtime_builder.php
// ---------------------------------

$lang["This page has been emailed to your friend"] = "Deze pagina is aan uw vriend verstuurd"; //"!"
$lang["Thank you"] = "Dank U"; //"!"
$lang["Your message has been sent. Thank you."] = "Uw bericht is verstuurd. Dank U.";


// pgm-blog_display.php
// ---------------------------------
$lang["Weblog Archives"] = "Weblog Archieven";
$lang["Archives"] = "Archieven";
$lang["January"] = "Januari";
$lang["February"] = "Februari";
$lang["March"] = "Maart";
$lang["April"] = "April";
$lang["May"] = "Mei";
$lang["June"] = "Juni";
$lang["July"] = "Juli";
$lang["August"] = "Augustus";
$lang["September"] = "September";
$lang["October"] = "October";
$lang["November"] = "November";
$lang["December"] = "December";


// pgm-email_friend.php
// ---------------------------------
$lang["I found this web site that you might be interested in"] = "I vond deze website die jou weleens zou kunnen interesseren";
$lang["so I thought I'd email it to you..."] = "dus ik dacht, ik email hem maar even...";
$lang["Just click on the link to see it!"] = "Klik op de link om hem te zien!";
$lang["I found something you might want to see..."] = "Ik vond iets dat je misschien wel wilt zien...";
$lang["Email this page to a friend"] = "Email deze pagina aan een vriend";
$lang["Your Name"] = "Uw Naam";
$lang["Your Email Address"] = "Uw Emailadres";
$lang["Friends Email Address"] = "Emailadres Vriend";
$lang["Personal Message"] = "Persoonlijk Bericht";
$lang["Send Now"] = "Verstuur Nu";


// pgm-form_submit.php
// ---------------------------------
$lang["The email address you entered is invalid or"] = "Het emailadres dat u invulde is ongeldig of";
$lang["You left a required field or fields blank."] = "u heeft verplichte velden niet ingevuld.";
$lang["Please enter the following data before continuing"] = "Vul a.u.b. de volgende gegevens in voor u doorgaat";
$lang["Auto Generated Form Email"] = "Automatisch Gegenereerde 'Formulier Email";
$lang["Email Address"] = "Emailadres";

$lang["This message is auto-generated by your web site when the"] = "Dit bericht wordt automatisch gegenereerd door uw website als het";
$lang["form is submitted by a site visitor on page"] = "formulier is verzonden door een bezoeker op de pagina"; // "[Page Name]"
$lang["No need to reply"] = "Geen antwoord nodig";

$lang["This data has been saved to the"] = "Deze gegevens zijn opgeslagen in de"; //"[Table Name]"
$lang["database table"] = "database tabel";

$lang["Your site visitor received the custom response file"] = "Uw bezoeker ontving het respons file"; // "[File Name]"
$lang["Website Form Submission"] = "Website Formulier Verzending"; // This is default subject line for form emails.
$lang["Thank you for your form submission today! This email is to confirm the reception"] = "Dank u voor het formulier dat u vandaag instuurde! Deze email is om de ontvangst te bevestigen";
$lang["of your recently submitted data."] = "van uw onlangst verstrekte gegevens.";
$lang["We received the following:"] = "We hebben het volgende ontvangen:";
$lang["Thank You"] = "Dank U";
$lang["This message is auto-generated by our web site."] = "Dit bericht is automatisch gegenereerd door onze Website.";
$lang["Please do not reply to this email."] = "Beantwoordt deze email a.u.b. niet.";


// pgm-numusers.php
// ---------------------------------
$lang["Visitors Currently Online"] = "Aantal Bezoekers Online";


// pgm-print_page.php
// ---------------------------------
$lang["THIS PAGE IS CURRENTLY UNDER CONSTRUCTION"] = "DEZE PAGINA IS OP DIT MOMENT ONDER CONSTRUCTIE OF IN ONDERHOUD";
$lang["This Week in"] = "Deze Week in"; // "[Month]"
$lang["Page Visits"] = "Pagina Bezoeken"; // ": [#]"
$lang["More Info"] = "Meer Info";


// pgm-single_sku.php
// ---------------------------------
$lang["More Information"] = "Meer Informatie";


// pgm-cal-confirm.php
// ---------------------------------
$lang["This event has been added to your calendar system."] = "Deze gebeurtenis is toegevoegd aan uw Agenda Systeem.";
$lang["It appears this event has already been added to your system."] = "Het lijkt erop dat deze gebeurtenis reeds is toegevoegd aan uw Agenda.";

// pgm-cal-details.inc.php
// ---------------------------------
$lang["Print Details"] = "Print Details";
$lang["Close Window"] = "Sluit Venster";
$lang["Event Date"] = "Datum Gebeurtenis";
$lang["Event Time"] = "Tijdstip Gebeurtenis";
$lang["Event Details"] = "Details Gebeurtenis";
$lang["More Details"] = "Meer Details";


// pgm-cal-submitevent.inc.php
// ---------------------------------
$lang["Private"] = "Privé";
$lang["Submit an Event"] = "Voeg een Gebeurtenis toe";
$lang["Your Name"] = "Uw Naame";
$lang["Your Email Address"] = "Uw Emailadres";
$lang["Event Date"] = "Datum Gebeurtenis";
$lang["Event Category"] = "Category Gebeurtenis";
$lang["Start Time"] = "Starttijd";
$lang["Event Title"] = "Titel Gebeurtenis";
$lang["Event Details"] = "Details Gebeurtenis";
$lang["Submit Event"] = "Voeg Gebeurtenis Toe";
$lang["All fields are required to submit an event except Event End Time and Event Details."] = "Alle velden zijn verplicht om een gebeurtenis toe te voegen, met uitzondering van de Eindtijd en de Details.";


// pgm-cal-system.php
// ---------------------------------

$lang["Please Setup Calendar System Display Settings."] = "Voer a.u.b. de weergaveinstellingen in voor het Agendasysteem.";
$lang["Private"] = "Privé";
$lang["Your selected event has been deleted."] = "Uw geselecteerde gebeurtenis is verwijderd.";
$lang["You did not enter one or more required fields. Please modify your submission and try again."] = "U heeft één of meer verplichte velden niet ingevuld. Pas uw toevoegin a.u.b. aan en probeer opnieuw.";
$lang["Event Added to your Calendar"] = "Gebeurtenis Toegevoegd aan uw Agenda";
$lang["The following event was submitted to your calendar. To approve this event, click the approve link below."] = "De volgende gebeurtenis is toegevoegd aan uw agenda. Klik op de 'goedkeuringslink' hieronder om goed te accorderen.";
$lang["If you do not wish to add this event to your calendar, simply disregard this email."] = "Als u deze gebeurtenis niet aan uw agenda wilt toevoegen, eenvoudigweg deze email negeren.";
$lang["Event Date"] = "Datum Gebeurtenis";
$lang["Event Category"] = "Categorie Gebeurtenis";
$lang["Event Title"] = "Titel Gebeurtenis";
$lang["Start Time"] = "Starttijd";
$lang["End Time"] = "Eindtijd";
$lang["Event Details"] = "Details Gebeurtenis";
$lang["To approve, click the link below:"] = "Om te accorderen, klik op de link hieronder:";
$lang["THIS IS AN AUTO-GENERATED EMAIL FROM YOUR WEBSITE"] = "DIT IS EEN AUTOMATISCH DOOR UW WEBSITE GEGENEREERDE EMAIL";
$lang["Your submission has been sent to our calendar manager for approval."] = "Uw toevoeging is aan onze agenda manager verzonden voor goedkeuring.";
$lang["Thank you"] = "Dank U";
$lang["Current View"] = "Huidige Scherm";
$lang["View"] = "Bekijk";
$lang["Submit an Event"] = "Voeg een Gebeurtenis Toe";
$lang["Detail Event Search"] = "Detail Zoekopdracht Gebeurtenis";
$lang["Month"] = "Maand";
$lang["Current Category"] = "Huidige Categoryie";
$lang["In Category"] = "In Categorie";
$lang["Search Now"] = "Zoek Nu";
$lang["Submit a search to change categories."] = "Geef een zoekopdracht om van categorie te wisselen.";
$lang["Events for the Week of"] = "Gebeurtenissen voor de Week van"; // "[Month DD-DD]"

$lang["Events for"] = "Gebeurtenissen in"; // "[month]"
$lang["that match your search for"] = "die met uw zoekopdracht overeenkomen naar"; // [Search Query]


$lang["your personal calendar"] = "uw persoonlijke agenda"; // [User's Name]
$lang["the category"] = "de categorie"; // [category selection]
$lang["located in"] = "gevonden in"; // located in [category]/"your personal calendar"

$lang["Edit Event"] = "Pas Gebeurtenis Aan";
$lang["Delete Event"] = "Verwijder Gebeurtenis";
$lang["This is your private event."] = "Dit is uw privé gebeurtenis.";
$lang["No details available for this event."] = "Geen details aanwezig voor deze gebeurtenis.";
$lang["in category"] = "in categorie";
$lang["There where no events found for your selection or search"] = "Er werden geen Gebeurtenissen gevonden in uw selectie of zoekopdracht";
$lang["Please search for an event or select the day or week you wish to view."] = "Zoek a.u.b. een gebeurtenis of selecteer de dag of week die u wilt zien.";
$lang["Authorized user logged in"] = "Geauthoriseerde gebruiker ingelogd";
$lang["Indicates your private event"] = "Geeft uw privé gebeurtenis aan";
$lang["No one else can view this event but"] = "Niemand kan deze gebeurtenis zien behalve"; //[user's name]


// newsletter/index.php
// ---------------------------------
$lang["Please enter the email address where you wish NOT to receive future emails"] = "Voer a.u.b. het emailadres in waar u voortaan GEEN mail wilt ontvangen";
$lang["Unsubscribe Now"] = "Schrijf u Nu uit";

$lang["UNSUBSCRIBE FROM"] = "SCHRIJF U UIT VOOR"; // [url]
$lang["EMAIL SERVICE"] = "EMAIL SERVICE";

$lang["The email address"] = "Het emailadres"; // [unsubscribed address]
$lang["is no longer subscribed to our services."] = "is niet langer in onze lijst opgenomen.";

$lang["If you need to remove another email address from our subscription system"] = "Als u nog een emailadres wilt verwijderen ons systeem";
$lang["click here"] = "klik dan HIER";

$lang["Visit"] = "Bezoek"; // [url]
$lang["now"] = "nu";


// pgm-photo_album.php
// ---------------------------------
$lang["Available Album(s)"] = "Beschikbare Album(s)";

$lang["Current Album is"] = "Huidige Album is";
$lang["Change Album"] = "Wissel Album";
$lang["To change albums, highlight your"] = "Om van album te wisselen, 'highlight' uw"; // <br>
$lang["choice and click the 'Change Album' button."] = "keuze en klik op de Wissel Album button.";

$lang["Prev"] = "Bekijk";
$lang["Next"] = "Volgende";
$lang["There are currently no images in this album."] = "Er zijn momenteel geen afbeeldingen in dit album.";



// pgm-secure_login.php
// ---------------------------------

$lang["The page you have requested requires security access."] = "De pagina die u wilt zien heeft Beveiligde Toegang.";
$lang["Please enter your username and password now."] = "Voer nu a.u.b. uw gebruikersnaam en password in.";
$lang["It appears your login does not grant you access to this page."] = "Het blijkt dan uw login u geen toegang geeft tot deze pagina..";
$lang["If you feel this is in error, please contact us for further assistance."] = "Als u van mening bent dat dit niet correct is, neem dan alstublieft contact met ons op voor assistentie.";

$lang["Click here"] = "Klik hier";
$lang["to return to the home page."] = "om terug te gaan naar de home pagina.";

$lang["Please Login"] = "Login a.u.b.";
$lang["Username"] = "Gebruikersnaam";
$lang["Password"] = "Password";
$lang["Sorry, we do not recognize that username and password.<BR>Please check your spelling and try again."] = "Sorry, we herkennen de gebruikersnaam en het password niet.<BR>Controleer a.u.b. de spelling en probeer het opnieuw.";
$lang["It appears the username and password that you entered has expired."] = "Het blijkt dat de gebruikersnaam en het password dat u heeft ingevoerd zijn verlopen.";
$lang["Your access is no longer available."] = "U heeft niet langer toegang.";
$lang["Click here"] = "Klik HIER";
$lang["to return to the home page."] = "om terug te gaan naar de home pagina.";
$lang["Forget your password"] = "Uw password vergeten";


// pgm-secure_manage.php
// ---------------------------------
$lang["Your login password does not match"] = "Uw login password komt niet overeen met";
$lang["your verification password. Please re-enter."] = "uw verificatie password. Voer a.u.b. opnieuw in.";
$lang["One or more fields were left blank or are too short."] = "Eén of meer velden werden opengelaten of zijn te kort.";
$lang["All fields must have at least 5 characters."] = "Alle velden moeten tenminste 5 karakters hebben.";
$lang["Your authentication data has been updated"] = "Uw authenticatie gegevens zijn ge-update"; // "!"
$lang["Manage Authenticated User Account"] = "Manage Geauthenticeerd Gebruikers Account";
$lang["Your Email Address"] = "Uw Emailadres";
$lang["Login Username"] = "Login Gebruikersnaam";
$lang["Login Password"] = "Login Password";
$lang["Verify Password"] = "Verifieer Password";
$lang["Update Your Data"] = "Update Uw Gegevens";


// pgm-secure_remember.php
// ---------------------------------
$lang["Here is the username and password associated with your email address"] = "Hier zijn de gebruikersnaam en het password dat gekoppeld is aan uw emailadres";
$lang["Username"] = "Gebruikersnaam";
$lang["Password"] = "Password";
$lang["This is an automated email from"] = "Dit is een automatische email van"; // [server name]
$lang["Please DO NOT REPLY to this email."] = "BEATNWOORD deze email a.u.b. NIET.";
$lang["Customer data successfully located."] = "Klantgegevens gevonden.";
$lang["You should receive an email within the next few minutes."] = "U moet binnen enkele minuten een email ontvangen.";
$lang["Failed to locate email address; please try again."] = "Emailadres niet gevonden; probeer a.u.b. opnieuw.";
$lang["Forgotten Login"] = "Logins Vergeten";
$lang["Please <u>enter your email address</u> in the space below."] = "Voer a.u.b. <u>uw emailadres</u> in, in de ruimte hieronder.";
$lang["We will locate your username and password in our database and instantly send an email to"] = "We zullen uw gebruikersnaam en passoword in onze database opzoeken en onmiddellijk een email sturen aan";
$lang["the address that matches your input."] = "het adres dat hoort bij hetgeen u heeft ingevoerd.";
$lang["Find Now"] = "Zoek Nu";


// pgm-add_cart.php
// ---------------------------------
$lang["Please fill out the following information needed for this individual item"] = "Vul a.u.b. de volgende informatie in die nodig is voor dit individuele item";
$lang["Item"] = "Item";
$lang["Details"] = "Details";
$lang["Details"] = "Details";
$lang["Please fill out the following information regarding this product"] = "Vul a.u.b. de volgende informatien in met betrekking tot dit product";
$lang["Continue"] = "Ga Verder";
$lang["ILLEGAL PRODUCT ADDITION DETECTED."] = "NIET TOEGESTANE PRODUCT TOEVOEGING GESIGNALEERD.";
$lang["UPDATED"] = "GE-UPDATE";
$lang["Current Shopping Cart Contents"] = "Huidige Shopping Cart Inhoud";
$lang["Shipping Information"] = "Verzend Informatie";
$lang["Returns & Exchanges"] = "Retouren & Ruilen";
$lang["Privacy Policy"] = "Privacy Beleid";
$lang["Other Policies"] = "Overig Beleid";

$lang["Sub-total does not include tax"] = "Sub-totaal exclusief belasting"; // <br>
$lang["and shipping charges, if applicable."] = "en verzending, indien van toepassing.";


$lang["Sub-Total"] = "Sub-Totaal";
$lang["Your shopping cart is currently empty."] = "Uw shopping cart is op dit moment leeg.";
$lang["We also recommend the following product(s)"] = "We bevelen tevens het/de volgende product(en) aan";


// pgm-checkout.php
// ---------------------------------
$lang["Customer Sign-in"] = "Klant Registratie";
$lang["Email"] = "Email";

$lang["Billing & Shipping"] = "Facturering & Verzending"; // <br>
$lang["Information"] = "Informatie";

$lang["Shipping Options"] = "Verzend Opties";
$lang["Verify Order Details"] = "Controleer Order Gegevens";
$lang["Make Payment"] = "Betaal";

$lang["Print Final"] = "Print Resultaat"; // <br>
$lang["Invoice"] = "Factuur";


$lang["CUSTOMER SIGN-IN"] = "KLANT REGISTRATIE";
$lang["Select an option below so that we can recognize you."] = "Kies hieronder een optie zodat wij u kunnen herkennen.";
$lang["Shipping Information"] = "Verzend Informatie";
$lang["Returns & Exchanges"] = "Retouren & Ruilen";
$lang["Privacy Policy"] = "Privacy Beleid";
$lang["Other Policies"] = "Overig Beleid";
$lang["New Customer"] = "Nieuwe KLant";
$lang["If you are a first time buyer select this option."] = "Als u hier voor het eerst koopt kies dan deze optie.";
$lang["You will have the opportunity to register and become a prefered customer."] = "U heeft de mogelijkheid zich te registreren en vaste klant status te krijgen.";
$lang["New Customer"] = "Nieuwe Klant";
$lang["Existing Customers, Login Now"] = "Bestaande Klanten, Log nu in";
$lang["Username"] = "Gebruikersnaam";
$lang["Unrecognized Customer"] = "Onbekende Klant";
$lang["Try Again"] = "Probeer Opnieuw";
$lang["Verify Order"] = "Verifieer Order"; //<br>
$lang["Details"] = "Details";
$lang["STEP"] = "STAP";
$lang["BILLING AND SHIPPING INFORMATION"] = "FACTURERING EN VERZEND INFORMATIE";
$lang["Please fill out all fields"] = "Vul a.u.b. alle velden in";
$lang["You will have a chance to verify and correct this information if necessary."] = "U heeft de mogelijkheid om deze informatie te verifiëren en te corrigeren indien nodig.";
$lang["Customer Sign-in"] = "Klant Registratie";
$lang["Please double check that all information is correct."] = "Dubbel check a.u.b. dat alle informatie correct is.";
$lang["SELECT YOUR METHOD OF PAYMENT"] = "SELECTEER UW BETAALMETHODE";
$lang["Choose your method of payment by clicking on the desired button."] = "Kies uw betaalmethode door te klikken op de gewenste button.";
$lang["Currently we are only accepting Check or Money Order payments."] = "Op dit moment accepteren wij alleen Checques of Bankoverschrijvingen.";
$lang["We currently accept the following credit cards"] = "Op dit moment accepteren wij de volgende credit cards";
$lang["Mailing Address"] = "Mailing Adres";


// pgm-checkout.php
// ---------------------------------
$lang["Thanks"] = "Bedankt"; // [user name]!
$lang["Your email has been sent"] = "Uw email is verstuurd";
$lang["A cool product I found..."] = "Een Goed Product Dat Ik Heb Gevonden ..."; // Default subject line of 'email product to friend' feature
$lang["Email Product"] = "Email Product";
$lang["You have left one or more required fields blank"] = "U heeft één of meer verplichte velden niet ingevuld";
$lang["Please correct and re-submit your email"] = "Corrigeer dit a.u.b. en verstuur opnieuw";
$lang["Required Fields"] = "Verplichte Velden";
$lang["Your <u>Full</u> Name"] = "Uw <u>Volledige</u> Naam";
$lang["Your Email Address"] = "Uw Emailadres";
$lang["Friend's <u>First</u> Name"] = "<u>Voornaam Vriend</u>";
$lang["Friend's Email Address"] = "Emailadres van Vriend";
$lang["Subject Line of Email"] = "Onderwerpregel Email";
$lang["Personal Message"] = "Persoonlijk Bericht";
$lang["Email Type"] = "Email Type";
$lang["Yes, send me a copy of the email too."] = "Ja stuur mij ook een kopie van de email.";
$lang["Click Here to Return to"] = "Klik Hier om Terug Te Gaan naar"; //[product name]
$lang["Return To Checkout Login"] = "Ga Terug Naar Checkout Login";
$lang["Failed to locate email address; please try again or login as a new customer."] = "Emailadres niet gevonden.; probeer a.u.b. opnieuw of login als nieuwe klant.";
$lang["Follow the instructions below to resolve your issue quickly."] = "Volg de instructies hieronder om het probleem snel op te lossen.";
$lang["Find Username and Password for Login"] = "Zoek Gebruikersnaam en Password voor Login";
$lang["Your username and password was displayed on the invoice of your first order with us."] = "Uw gebruikersnaam en password werd getoond op de factuur van uw eerste aankoop bij ons.";
$lang["If you have the email or a printed copy handy, it may expedite your request."] = "Als u de email of een geprinte kopie bij de hand heeft kan dit uw verzoek versnellen.";
$lang["Otherwise, please enter your email address in the space below."] = "Anders, vul a.u.b. uw emailadres in, in de ruimte hieronder.";
$lang["Thank you for being a valued return customer."] = "Dank dat u een zeer gewaardeerde terugkerende klant bent.";
$lang["Find Now"] = "Zoek Nu";

$lang["We have received your request for a lost username and"] = "We hebben uw verzoek ontvangen inzake een zoekgeraakte gebruikersnaam en";
$lang["password and have located that information in our system."] = "password en we hebben de informatie in ons systeem gevonden.";
$lang["They are as follows"] = "Dit is het volgende";
$lang["Thank you for being a loyal prefered customer."] = "Dank u dat u een trouwe vaste klant bent.";
$lang["We look forward to continuing to serve you in the future."] = "Wij hopen u ook in de toekomst van dienst te kunnen zijn.";
$lang["This is an automated email from"] = "Dit is een geautomatiseerd emailformulier";
$lang["Please DO NOT REPLY to this email."] = "BEANTWOORDT deze email a.u.b. NIET.";


// pgm-more_information.php
// ---------------------------------
$lang["Email To A Friend"] = "Email Aan Een Vriend";

$lang["Add this product to your cart below"] = "Voeg dit product toe aan uw winkelwagen hieronder";
$lang["under 'ordering options'."] = "onder 'bestelopties'.";

$lang["Product"] = "Product";
$lang["Price"] = "Prijs";
$lang["Qty"] = "Aantal";
$lang["Add To Cart"] = "Voeg Toe Aan Cart";
$lang["Details specific to this item will be asked when you add this product to your cart."] = "Details specifiek voor dit item zullen u gevraad worden wanneer u dit product aan uw winkelwagen toevoegt.";
$lang["More Information"] = "Meer Informatie";
$lang["Zoom"] = "Zoom";
$lang["Customer Comments"] = "Opmerkingen Klant";

$lang["Be the first to"] = "Wees de eerste die";
$lang["write a review"] = "een recensie schrijft";
$lang["of this product for other customers"] = "over dit product voor andere klanten";

$lang["Write an online review"] = "Schrijf een online recensie";
$lang["and share your thoughts about this product with other customers."] = "en deel uw bevindingen met dit product met andere klanten.";
$lang["If you like this, you may also like"] = "Als u dit goed vindt, dan waardeert u dit wellicht ook";


// pgm-ok_comment.php
// ---------------------------------
$lang["This comment has already been added to the system or no longer exists."] = "Deze opmerking is al aan het systeem toegevoegd of bestaat niet meer.";
$lang["CUSTOMER COMMENT ADDED"] = "OPMERKING KLANT TOEGEVOEGD";


// pgm-payment_gateway.php
// ---------------------------------
$lang["Customer Registration"] = "Klant Registratie";

$lang["Thanks"] = "Bedankt";
$lang["you are now registered as a prefered customer"] = "u bent nu geregistreerd als vaste klant";

$lang["The next time you shop with us, you may login using your username and password for quicker checkout"] = "De volgende keer dat u bij ons komt shoppen kunt u inloggen met uw gebruikersnaam en password om sneller af te rekenen";
$lang["An error occurred when assigning your invoice number."] = "Een fout heeft zich voorgedaan bij het toekennen van uw factuurnummer.";
$lang["Please try again or contact the webmaster immediately."] = "Probeer het a.u.b. opnieuw of neem onmiddellijk contact op met de webmaster.";

$lang["The checkout system is configured to use a custom gateway include script named"] = "Het afreken systeem is geconfigureerd om een custom gateway include script te gebruiken genaamd"; //[filename]
$lang["but the file can not be found on the server."] = "maar het file kan niet op de server gevonden worden.";

$lang["Via 'Payment Options' in the system admin, make sure that you have a current include file selected and try again."] = "Controleer via 'Payment Options' in de systeem admin dat u een bestaand include file geselecteerd hebt en probeer opnieuw.";
$lang["Connecting To VeriSign"] = "Maakt Verbinding Met VeriSign";
$lang["Secure Server"] = "Secure Server";
$lang["Please Hold"] = "Een Moment";
$lang["If you are not connected automatically within 20 seconds"] = "Als u niet automatisch binnen 20 seconden wordt verbonden";
$lang["Click Here"] = "Klik Hier";
$lang["Connecting To PayPal"] = "Maakt Verbinding Met To PayPal";
$lang["Secure Payment Server"] = "Secure Payment Server";

$lang["The checkout system is configured to utilize online credit card processing, however, there is no VeriSign"] = "Het afrekensysteem is geconfigureerd om online credit card verwerking te gebruiken, er is echter geen VeriSign";
$lang["information setup nor is there a"] = "informatie setup noch is er een";
$lang["custom gateway specified.  One of the other must be setup through 'Payment Options' to use the online credit card checkout system."] = "custom gateway gespecificeerd.  De een of de ander moet worden ingesteld via 'Betaalopties' om het online credi card afrekensysteem systeem te gebruiken.";
$lang["If you do not know what these things mean, login to the admin system, select 'Payment Options' in the Shopping Cart module"] = "Als u niet weet wat dit betekent, login in het admin system, selecteer 'Betaalopties' in de Shopping Cart module";
$lang["and select 'Offline Processing' then save your settings."] = "en selecteer 'Offline Verwerking' sla vervolgens uw instellingen op.";
$lang["This should resolve your issue immediately."] = "Dit moet uw probleem onmiddellijk oplossen.";

// pgm-show_invoice.php
// -----------------------------------
$lang["Make Check/Money Order Payable to"] = "Stel Cheque/Bankbetaling betaalbaar aan";
$lang["Order Date"] = "Order Datum";
$lang["Order Number"] = "Order Nummer";
$lang["Mailing Address"] = "Mailing Adres";
$lang["Print this Page Now"] = "Print deze Pagina Nu";
$lang["To download and save the file to your hard-drive, 'Right-Click' on Download Button and select 'Save Target As...'."] = "Om de file op te slaan op uw harddisk, klik met de 'Rechter Muisknop' op de Download Button en selecteer 'Doel opslaan als...'.";

$lang["When the save dialog appears, make sure you"] = "Als het 'Opslaan Dialoogvenster' verschijnt, verzeker dan dat u";
$lang["remember where you save the file on your hard drive."] = "weet <u>WAAR</u> u de file opslaat op uw harddisk.";

$lang["You will also receive an HTML email receipt of this invoice that contains this link as well in case"] = "U zult ook een bevesting in een HTML email ontvangen van deze factuur die ook een link bevat voor het geval dat";
$lang["you encounter connection problems downloading the file now."] = "u nu verbindingsproblemen mocht hebben tijdens het downloaden van het file.";
$lang["This order was just placed from your website."] = "Deze order werd zojuist geplaatst via uw website.";
$lang["If you need to retrieve the credit card information, please login and do so now."] = "Als u de credit card informatie moet ophalen, log dan a.u.b. in en doe dat nu.";
$lang["CUSTOMER INVOICE COPY"] = "KOPIE FACTUUR KLANT";


// pgm-write_review.php
// ---------------------------------
$lang["CLICK HERE"] = "KLIK HIER";
$lang["TO MAKE THIS POST LIVE."] = "OM DEZE BIJDRAGE 'LIVE' TE MAKEN.";
$lang["If you do not want to display this comment, simply delete this email"] = "Als u deze opmerking niet wilt tonen, verwijder dan eenvoudig deze email";

$lang["A customer has submitted the following comments about"] = "Een klant heeft de volgende opmerkingen geplaatst over"; // <br>
$lang["the product"] = "het product";


$lang["Your comment has been submitted."] = "Uw opmerking is toegevoegd.";
$lang["Click Here to Return to"] = "Klik Hier om Terug te Gaan naar"; // [product name]

$lang["You have left one or more fields blank."] = "U heeft één of meer velden open gelaten.";
$lang["Please correct and re-submit your review."] = "Corrigeer dit a.u.b. en voeg opnieuw uw recensie toe.";
$lang["Star"] = "Ster";
$lang["Stars"] = "Sterren";
$lang["Rate this Product"] = "Waardeer dit Product";
$lang["On a scale of 1-5, with 5 being the best"] = "Op een schaal van 1-5, waarbij 5 het beste is";
$lang["Comment Title"] = "Titel Opmerking";
$lang["Your Review/Comments"] = "Uw Recensie/Commentaar";
$lang["Your Name"] = "Your Name";
$lang["Where are you in the world"] = "Waar op de wereld bent u";
$lang["our review will be submitted to our staff and should be posted within 2-3 business days."] = "onze recensie zal worden voorgelegd aan onze staf en zou binnen 3 á 4 werkdagen toegevoegd moeten worden.";
$lang["Thank you"] = "Dank U";


// prod-billing_shipping.inc
// ---------------------------------

$lang["The state you selected to ship your order to does not appear to be valid."] = "The staat die u geselcteerd heeft voor verzending van uw order lijkt niet geldig te zijn.";
$lang["Please correct and re-submit your information."] = "Corrigeer en voeg uw informatie a.u.b. opnieuw toe.";
$lang["The email address you provided is not a valid email address."] = "Het emailadres dat u heeft opgegeven is geen gelding emailadres.";
$lang["Please correct and re-submit your information."] = "Corrigeer a.u.b. en voeg uw informatie opnieuw toe.";
$lang["Customer Registration"] = "Customer Registration";
$lang["Yes, I want you to remember my Billing &amp; Shipping Information the next time I purchase something."] = "Ja, ik wil dat u mijn Facturerings- en Verzendgegevens onthoudt voor de volgende keer dat ik iets aanschaf.";
$lang["Choose a password"] = "Kies een password";
$lang["Verify your password"] = "Verifieer uw password";
$lang["The passwords that you entered do not match each other. Please check the spelling and re-submit."] = "De passwords die u heeft ingevoerd zijn niet gelijk. Controleer de spelling a.u.b. en voeg opnieuw toe.";
$lang["You have elected to register as a customer but did not choose a password for your account. Please do so now."] = "U heeft ervoor gekozen als klant geregistreerd te worden maar u heeft geen password gekozen voor uw account. Doe dat a.u.b. nu.";
$lang["If you are not using the customer registration feature, you may leave the password fields blank"] = "Als u de klantregistratie optie niet gebruikt kunt u de password velden blanco laten";
$lang["Billing Information"] = "Factureringsgegevens";
$lang["First Name"] = "Voornaam";
$lang["Last Name"] = "Achternaam";

$lang["Company Name"] = "Bedrijfsnaam";
$lang["Optional"] = "Optioneel";
$lang["Address"] = "Adres";
$lang["No PO Boxes"] = "Geen Postbussen";
$lang["City"] = "PlaatsCity";
$lang["Zip Code"] = "Postcode";
$lang["State/Province"] = "Staat/Provincie";
$lang["Country"] = "Land";
$lang["Billing Phone Number"] = "Facturering Telefoonnr.";
$lang["Email Address"] = "Emailadres";
$lang["Used to send a copy of your invoice, and also serves as your username for future purchases."] = "Gebruikt om een kopie van uw factuur te verzenden, en dient tevens als uw gebruikersnaam voor toekomstige aankopen.";
$lang["to use Billing Information. Note, we do not ship to P.O. Boxes."] = "om Fadtureringsgegevens te gebruiken. N.B., wij versturen niet aan postbussen.";
$lang["Zip Code"] = "Postcode";
$lang["Ship-To Phone Number"] = "Verzendadres Telefoonnr.";


// pgm-cust_invoice.php
// ---------------------------------
$lang["Shipping & Handling"] = "Verzending & Verwerking";
$lang["BILLING INFORMATION"] = "FACTURERINGSINFORMATIE";
$lang["SHIPPING INFORMATION"] = "VERZENDINFORMATIE";
$lang["Product Name"] = "Product Naam";
$lang["Unit Price"] = "Stuksprijs";
$lang["Quantity"] = "Quantity";
$lang["Sub-Total"] = "Sub-Totaal";
$lang["Tax"] = "Belasting";
$lang["Total"] = "Totaal";
$lang["EDIT"] = "WIJZIG";


// prod_offline_card.inc
// ---------------------------------
$lang["The total amount of your purchase"] = "Het totaalbedrag van uw aankoop"; //[total]
$lang["will be charged to your credit card."] = "zal op uw creditcard in rekening worden gebracht.";

$lang["Name as it appears on card"] = "Naam zoals vermeld op de creditcard";
$lang["Credit Card Type"] = "Creditcard Type";
$lang["Credit Card Number"] = "Creditdard Nummer";
$lang["Credit Card Expiration Date"] = "Creditcard Expiratie Datum";
$lang["Month"] = "Maand";
$lang["Security Code"] = "Veiligheidscode";
$lang["How to find your security code"] = "Hoe uw veiligheidscode te vinden";


// prod_search_column.inc
// ---------------------------------
$lang["Welcome"] = "Welkom";
$lang["Client Login"] = "Klant Login";
$lang["Find Now"] = "Zoek Nu";
$lang["Search Products"] = "Zoek Producten";
$lang["Browse Categories"] = "Doorzoek Categorieën";
$lang["Your cart is empty."] = "Uw winkelwagen is leeg.";
$lang["VIEW OR EDIT CART"] = "BEKIJK OF WIJZIG WINKELWAGEN";
$lang["Telephone Orders"] = "Telefonische Orders";
$lang["We Accept"] = "We Accepteren"; // (the following credit cards)

$lang["We are currently not accepting online orders."] = "Wij accepteren op dit moment geen online orders.";
$lang["We are currently only accepting check or money orders online."] = "Wij accepteren op dit moment alleen cheques of bankoverschrijvingen online.";
$lang["Returns & Exchanges"] = "Retouren & Ruilen";
$lang["Privacy Policy"] = "Privacy Beleid";
$lang["Other Policies"] = "Overig Beleid";


// prod_search_template.php
// ---------------------------------
$lang["Buy Now"] = "Koop Nu";
$lang["Add to Cart"] = "Voeg Toe Aan Winkelwagen";
$lang["Related Products"] = "Gerelateerde Producten";
$lang["Catalog"] = "Catalogus";
$lang["Browse Category"] = "Doorzoek Categorie"; 



// start.php
// ---------------------------------
$lang["Search Results For"] = "Zoekresultaten Voor";
$lang["Displaying"] = "Getooond";
$lang["Matches Found"] = "Overeenkomsten Gevonden"; // "[X] Matches Found"
$lang["Sorry, no products were found that match your search criteria."] = "Sorry, er werden geen producten gevonden die voloen aan uw zoekopdracht.";
$lang["Please try again or browse the suggested selections below."] = "Probeer het a.u.b. opnieuw of doorzoek te aanbevolen selecties hieronder.";
$lang["NEXT"] = "VOLGENDE";
$lang["Welcome to"] = "Welkom bij";
$lang["Mailing Address"] = "Mailing Adres";



#################################################
## WEBMASTER MENU             				     ##
#################################################

// webmaster.php
// ---------------------------------
$lang["USERNAME/PASSWORD NOT CHANGED"] = "GEBRUIKERSNAAM/PASSWORD NIET GEWIJZIGD";

$lang["Your username or password change"] = "Uw gebruikersnaam of password wijziging";
$lang["could not be verified. Please try again."] = "kon niet worden geverifieerd. Probeer het a.u.b. opnieuw";

$lang["Your Administrative Username and Password has been changed"] = "Uw Administratieve Gebruikersnaam en Password is gewijzigd";
$lang["Administration Login"] = "Administratie Login";
$lang["New Username"] = "Nieuwe Gebruikersnaam";
$lang["Verify New Username"] = "Verifieer Nieuwe Gebruikersnaam";
$lang["New Password"] = "Nieuw Password";
$lang["Verify New Password"] = "Verifieer Nieuw Password";
$lang["Change Username/Password"] = "Wijzig Gebruikersnaam/Password";
$lang["Select User"] = "Selecteer Gebruiker";
$lang["Multi-User Access"] = "Multi-Gebruikers Toegang";
$lang["Edit User"] = "Wijzig Gebruiker";
$lang["Default Meta Tag Data"] = "Default Meta Tag Data";
$lang["Restart Quickstart Wizard"] = "Herstart Restart Quickstart Wizard";
$lang["Language"] = "Taal";
$lang["Swap Language"] = "Wissel Taal";
$lang["Access Rights"] = "Toegangsrechten";
$lang["Global Settings"] = "Algemene Instellingen";
$lang["Meta Tag Data"] = "Meta Tag Data";
$lang["Miscellaneous Options"] = "Overige Opties";
$lang["Disable Developer Mode"] = "Schakel Ontwikkelaar Mode uit";
$lang["Enable Developer Mode"] = "Schakel Ontwikkelaar Mode in";


// global_settings.php
// ---------------------------------
$lang["Business Address"] = "Business Addres";
$lang["State"] = "Staat";
$lang["Postal Code"] = "Postcode";
$lang["Apt. / Suite"] = "Kantoor";
$lang["Phone Number"] = "Telefoon";


// meta_data.php
// ---------------------------------
$lang["Web Site Title"] ="Titel van de Website";
$lang["This will be displayed at the top of the browser window on all pages of your site."] = "Dit wordt bovenaan het browservenster getoond op alle pagina's van uw site.";


$lang["Web Site Description"] = "Website Beschrijving";
$lang["This is a Meta Tag that helps search engines classify your web site."] = "Dit is een Meta Tag die zoekmachines helpt om uw website te classificeren.";
$lang["This should be a small sentance that describes your site."] = "Dit moet een korte zin zijn die uw website beschrijft.";

$lang["Web Site Keywords"] = "Website Keywords";
$lang["This is a Meta Tag that some search engines use to search your site with."] = "Dit is een Meta Tag die sommige zoekmachines gebruiken om uw website mee te zoeken.";
$lang["Please enter each keyword separated by a comma."] = "Vull a.u.b. elk keyword (zoekwoord) in gescheiden door een komma.";
$lang["There is no need to use line feeds or carriage returns in the field."] = "Het is niet nodig om zgn. 'line feeds' of 'carriage returns'te gebruiken in het veld.";
$lang["Note: Individual Meta Tag Data can be edited from Page Properties while editing the page."] = "N.B.: Individuele Meta Tag Data kan gewijzigd worden via Pagina Eigenschappen bij het wijzigen/opmaken van de pagina.";
$lang["Save Meta Tag Data"] = "Sla Meta Tag Data Op";


// add_user.php
// ---------------------------------
$lang["has been added to your administrative users list."] = "is toegevoegd aan uw administratieve gebruikerslijst."; // "[Full Name] has been added to your..."
$lang["Admin User's Full Name"] = "Admin Gebruiker's Volledige Naam";
$lang["Login Username"] = "Login Gebruikersnaam";
$lang["Login Password"] = "Login Password";
$lang["Select the seperate <U>Modules</U> that this user should have access to"] = "Selecteer de afzonderlijke <U>Modules</U> waartoe de gebruiker toegang moet hebben";
$lang["Enable Basic Features"] = "Toegang Tot Basis Features";
$lang["Enable Advanced Features"] = "Toegang Tot Geadvanceerde Features";
$lang["Select each <U>Site Page</U> this user should have access to"] = "Selecteer iedere <U>Sitepagina</U> waartoe deze gebruiker toegang moet hebben";
$lang["Note: User will not be able to access these pages unless the Edit Pages module itself is enabled (above)."] = "N.B.: Gebruiker heeft geen toegang tot deze pagina's tenzij hij toegang heeft tot de 'Openen/Opmaken Pagina(s)' module zelf (boven).";
$lang["Shopping Cart access options"] = "Shopping Cart toegangsopties";
$lang["Note: User must have access to Shopping Cart module itself (above)."] = "N.B.: Gebruiker moet toegang hebben tot de 'Shopping Cart' module zelf (boven).";
$lang["View Invoices Only"] = "Alleen Facturen Bekijken";
$lang["Select each <U>User Data Table</U> this user should have access to"] = "Selecteer elke <U>User Data Table</U> (zelf gedefinieerde tabel) waar deze gebruiker this user toegang toe moet hebben";
$lang["Cancel Create"] = "Annuleren";
$lang["Create New User"] = "Creëer Nieuwe Gebruiker";


// edit_user.php
// ---------------------------------
$lang["The settings for"] = "De instellingen voor";
$lang["have been updated."] = "zijn ge-update.";

$lang["Edit Administrative User"] = "Wijzig Administratieve Gebruiker";
$lang["You have selected to delete the user"] = "U heeft gekozen deze gebruiker te verwijderen"; // [username]
$lang["Once you click OK, you can not undo this process."] = "Als u op OK klikt, kunt u de proces NIET ongedaan maken.";
$lang["Are you sure you wish to delete this user"] = "Weet u zeker dat u deze gebruiker wilt verwijderen"; // "?"
$lang["Cancel Edit"] = "Annuleer Wijziging";
$lang["Delete User"] = "Verwijder Gebruiker";
$lang["Update User"] = "Update Gebruiker";


// Random Strings
// ---------------------------------
$lang["Backup/Restore"] = "Backup/Herstel";
$lang["Secure Users Menu"] = "'Veilige Gebruikers Menu";
$lang["Site Backup / Restore"] = "Site Backup / Herstel";
$lang["Install Software Updates"] = "Installeer Software Updates";
$lang["Install Software Updates"] = "Installeer Software Updates";
$lang["Check for software updates"] = "Check voor software updates";
$lang["Current Version"] = "Huidige Versie";
$lang["Release Date"] = "Release Datum";
$lang["Changes in this build"] = "Wijzigingen in deze release";
$lang["On-Menu Pages"] = "In Menu Pagina's";
$lang["Off-Menu Pages"] = "Buiten Menu Pagina's";
$lang["Speed-Dial Pages Menu"] = "Snel Kies Pagina's Menu";
$lang["Note: You may assign a single Site Base Template that applies to your entire website via the <a href=#LINK#>Template Manager</a> feature."] = "N.B.: Via de <a href=#LINK#>Template Manager</a> kunt u 1 enkele Basis Template kiezen die geldig is voor de hele website.";
$lang["To change the template for a specific page, edit the page, select page properties, and select the template from the drop down box."] = " Om een template voor een specifieke pagina te wijzigen, Open/Wijzig de pagina, selecteer 'Pagina Eigenschappen', en selecteer een template uit de 'drop down box'.";
$lang["Printable Page"] = "Printervriendelijke Pagina";
$lang["Background"] = "Achtergrond";
$lang["Click on an object above and drag it onto a drop zone for page placement."] = "Klik op een object hierboven en sleep het naar een 'drop zone' om het in de pagina te plaatsen.";
$lang["Click on an object below and drag it onto a drop zone for page placement."] = "Klik op een object hieronder en sleep het naar een 'drop zone' om het in de pagina te plaatsen.";
$lang["Please only use Alpha Numerical characters and Underscores."] = "Gebruik a.u.b. alleen Alpha Numerieke karakters en 'Underscores' (_)";
$lang["Media, document, and code files may be downloaded by clicking on the arrow next to the filename."] = "Media, document en code files kunnen worden gedownload door op de pijl naast de filenaam te klikken.";
$lang["Image files can be viewed and saved by clicking the preview icon next to the filename."] = "Afbeeldingsfiles kunnen bekeken en opgeslagen worden door te klikken op de 'Bekijken' icoon naast de filenaam.";
$lang["Indicates an image that should be reduced in filesize. This file causes slow load-times when viewing your web site."] = "Geeft een afbeelding aan die gereduceerd moet worden in filegrootte. Deze file zorgt voor een lange laadtijd als uw pagina wordt bezocht.";
$lang["Images"] = "Afbeeldingen";
$lang["Rename"] = "Hernoem";
$lang["Documents, Presentations, and Adobe PDFs"] = "Documenten, Presentaties en Adobe PDF's";
$lang["Video Files"] = "Video Files";
$lang["Spreadsheets and CSV files"] = "Spreadsheets en CSV files";
$lang["Custom web forms and text files"] = "Zelf aangepaste webformulieren en tekst files";
$lang["Custom HTML includes"] = "Zelf aangepaste HTML includes";
$lang["Custom HTML template files"] = "Zelf aangepaste HTML template files";
$lang["Custom PHP scripts"] = "Zelf aangepaste PHP scripts";
$lang["Unclassified files"] = "Ongeclassificeerde files";
$lang["Select the <U>Browse</U> button next to each filename to locate your local file for upload. <BR>When you are ready to start the upload operation, select <U>Upload Files</U>."] = "Selecteer de <U>Doorzoeken</U> button naast elke filenaam om uw locale file te vinden om te uploaden. <BR>Als u klaar bent om het uploaden te starten, selecteer <U>Upload Files</U>.";
$lang["Upload Custom Template Folder (Zipped)"] = "Upload Zelf Aangepaste Template folder (gezipped)";
$lang["To upload a custom template"] = "Om een zelfaangepaste template te uploaden";
$lang["Place all files(images,index.html,custom.css) into a folder and name the folder like this"] = "alle files (afbeeldingen, index.html, custom.css) in een folder plaatsen en noem de folder als volgt";
$lang["Category-Sub_Category-Color"] = "Categorie-Sub_Categorie-Kleur";
$lang["Example"] = "Voorbeeld";
$lang["Zip the folder and upload it below"] = "Zip de folder en upload het hieronder";
$lang["After upload the template will be availible in the list of templates"] = "Na uploaden zal de template beschikbaar zijn in de templatelijst";
$lang["Zipped Template Folder"] = "Gezipte Template Folder";
$lang["What is your site visitor supposed to enter or select for this field"] = "Wat moet uw site bezoeker invullen of selecteren in dit veld";
$lang["In progress"] = "In werking";
$lang["Complete"] = "Voltooid";
$lang["No file selected!\nPlease choose a backup file from your hard drive."] = "Geen file geselecteerd!\nKies a.u.b. een backup file op uw harddrive";
$lang["Website backup in progress..."] = "Website backup in werking";
$lang["This process may take several moments."] = "Dit proces kan enkele ogenblikken duren...";
$lang["Importing website backup file..."] = "Website backup file aan het importeren";
$lang["This process may take several moments, depending on connection speed."] = "Dit proces kan enige tijd in beslag nemen, afhankelijk van de snelheid van de verbinding";
$lang["User notes for this backup"] = "Gebruikers aantekeningen voor deze backup";
$lang["Site backup in progress. Please hold."] = "Site backup in werking, Een ogenblik a.u.b.";
$lang["Creating folder for this backup"] = "Folder creëren voor deze backup";
$lang["Writing backup info to text file"] = "Backup info wordt in een tekstfile weggeschreven";
$lang["Archiving site content and files"] = "Site-inhoud en files archiveren";
$lang["Creating data table restoration file"] = "Datatabel herstel file creëren";
$lang["Creating downloadable archive file"] = "Downloadable archief file creëren";
$lang["Inserting backup record into site log"] = "Backup record site log invoegen";
$lang["Done"] = "Gereed";
$lang["Restore from a previous backup"] = "Herstel van een eerder backup";
$lang["Note: When downloading backups, make sure to save the file with a '.tgz' extension NOT '.gz'"] = "N.B.: Bij het downloaden van backups, denk eraan de file op te slaan met een '.tgz' extensie NIET '.gz'";
$lang["Note: After backing up your site, please download the backup and delete it here for security purposes."] = "N.B.: Na het backuppen van uw site , a.u.b. hier de backup downloaden en verwijderen om veiligheidsredenen.";
$lang["Backup Title"] = "Backuptitel";
$lang["Backup Date"] = "Backupdatum";
$lang["Backup Time"] = "Backuptijd";
$lang["Are you sure you want to permanently delete this backup?"] = "Weet u zeker dat u deze backup permanent wilt verwijderen?";
$lang["Current website will be replaced with backup data."] = "Huidge website zal worden vervangen door backup gegevens.";
$lang["All unsaved data will be lost."] = "Alle niet opgeslagen gegevens zullen verloren gaan.";
$lang["Are you sure you want to restore the backup?"] = "Weet u zeker dat u de backup wilt terugzetten?";
$lang["Upload and import site backup file"] = "Site backup file uploaden en importeren";
$lang["Import a backup file that you uploaded via FTP"] = "Importeer een backupFile dat u heeft ge-upload via FTP";
$lang["Select Backup File"] = "Selecteer Backup File";
$lang["Import Backup File"] = "Importeren Backup File";
$lang["Webmaster: Site Backup and Restoration"] = "Webmaster: Site Backup en Herstel";
$lang["Description:"] = "Beschrijving";
$lang["Note: Thumbnail images should be no more than 99px wide."] = "N.B.: Thumbnail afbeeldingen mogen niet meer dan 99px breed zijn";
$lang["Full Size Images should be no more than 275px wide for optimal display within your web site."] = "Volledige afbeeldingen moeten niet meer dan 275px breed zijn voor een optimale weergave in uw website.";
$lang["When customers add this product to thier cart, require Form Data from:"] = "Als klanten dit product toevoegen aan hun winkelwagn, vraag Formulier Data van:";
$lang["User-Defined Variable"] = "Gebruiker-Gedefinieerde Variabele";
$lang["Denotes an event that is a 'Recurrence' of an original master event."] = "Geeft aan: een gebeurtenis die een 'Herhaald Voorkomen' van een oorspronkelijke 'Hoofd' gebeurtenis is.";
$lang["Denotes the original 'Master' event within a recurring event cycle."] = "Geeft aan: een oorspronkelijke 'Hoofd' gebeurtenis in een wederkerende gebeurtenissen cyclus.";
$lang["Special Promotions"] = "Specila Promoties";
$lang["Step 1: Blog Title"] = "Stap 1: Blog Titel";
$lang["Done!"] = "Gereed!";
$lang["Step 2: Enter Content For Blog"] = "Stap 2: Voeg Content Voor Blog Toe";
$lang["Launch Editor"] = "Start Editor";
$lang["Step 3: Post Blog to"] = "Stap 3: Voeg Blog toe aan";
$lang["Delete Entry"] = "Verwijder data";
$lang["Edit Entry"] = "Wijzig Data";
$lang["Save Entry"] = "Sla Data Op";
$lang["show all"] = "Toon alles";


$lang["Menu Navigation"] = "Navigatiemenu";
$lang["<b>Hoofd Menu</b>"] = "Hoofdmenu";
$lang["New software updates available."] = "Nieuwe software updates beschikbaar.";
$lang["Where do I start"] = "Waar moet ik beginnen";
$lang["Select Feature"] = "Kies Programma";
$lang["Setup Options"] = "Setup Opties";
$lang["Drag-N-Drop"] = "Slepen";
$lang["Choose a feature that you would like to use from the basic, advanced or administrative feature list"] = "Kies een programmaonderdeel dat u wilt gebruiken uit de Basis -, Geavanceerde - of Administratieve Modules";
$lang["Follow the instructions to set up features specific to that module"] = "Volg de instructies op om de instellingen van die specifieke module te beheren";
$lang["Now that your feature is set up, go to Open/Edit Page(s), select a page, and drag the feature you setup to a grid square.  Done!"] = "Nu dat het onderdeel is ingesteld, ga naar Openen/Bijwerken Pagina(s), selecteer een pagina en sleep het onderdeel dat u heeft ingesteld naar een van de vakken. Klaar!";
$lang["Template Upload"] = "Template Upload";
$lang["FAQ Manager"] = "FAQ Manager";
$lang["Web Forms"] = "Web Formulieren";
$lang["New Campaign"] = "Nieuwe Verzending";
$lang["Add User"] = "Voeg Gebruiker Toe";
$lang["Create Search"] = "Creëer Zoekopdracht";
$lang["Click here to show"] = "Click hier om te tonen";
$lang["Plugin Features"] = "Plugin Programma\'s";
$lang["Plugin Feature Modules"] = "Plugin Programma Modules";
$lang["Manage Plugins"] = "Manage Plugins";
$lang["Untranslated Strings"] = "Niet Vertaalde Strings";
$lang["Administrative Features"] = "Administratieve Modules";
$lang["Traffic Statistics"] = "Bezoekersstatistieken";
$lang["Add Admin User"] = "Voeg Admin Gebruiker Toe";
$lang["Help Center"] = "Hulp Programma";
$lang["Site visitors online"] = "Bezoekers online";
$lang["English string"] = "Engelse string";
$lang["translation"] = "vertaling";
$lang["Product Categories"] = "Product Categoriën";
$lang["What type of payment processing options will you offer to your customers"] = "Welke betalingswijzen wilt u aan uw klanten aanbieden";
$lang["Send them to a third-party gateway for payment."] = "Stuur hen naar een betalingsservice provider";
$lang["Process their credit card directly on <i>your</i> website."] = "Verwerk hun credit card direct vanaf uw website.";
$lang["PayPal Website Payments"] = "Paypal Website Betalingen";
$lang["PayPro"] = "PayPro";
$lang["Paystation"] = "Paystation";
$lang["PayPoint USA"] = "PayPoint USA";
$lang["Authorize.net"] = "Authorize.net";
$lang["eWay"] = "eWay";
$lang["Payments Express"] = "Payments Express";
$lang["Fill-in neccessary info for the payment options you've chosen to offer"] = "Vul de vereiste informatie in voor de betalingswijzen die u wilt aanbieden";
$lang["PayPoint USA Quicksale"] = "PayPoint USA Quicksale";
$lang["Account ID"] = "Rekening ID";
$lang["How to configure WorldPay"] = "Hoe WorldPay te configureren";
$lang["Innovate Gateway Solutions"] = "Innovate Gateway Solutions";
$lang["Login ID"] = "Login ID";
$lang["Transaction Key"] = "Transactie Sleutel";
$lang["PayPro Merchant ID:"] = "PayPro Merchant ID:";
$lang["eWay Payments (Australian businesses only)"] = "eWay Payments (Australian businesses only)";
$lang["Username:"] = "Gebruikersnaam:";
$lang["Password:"] = "Password";
$lang["How to configure Paystation for use with your site"] = "Hoe Paystation te configurere voor gebruik op uw site";
$lang["Paystation ID:"] = "Paystation ID:";
$lang["I want to use online processing but I have a custom PHP include payment gateway "] = "Ik wil online processing gebruiken maar ik heb een custom PHP include payment gateway";
$lang["For most certificates, this is simply your domain name with 'https://' instead of 'http://' at the beginning"] = "Voor de meeste certificaten, is dit simpelweg uw domeinnaam met \'https://\' i.p.v. \'http://\' aan het begin";
$lang["Note: DO NOT ADD ANY TRAILING FORWARD SLASHES to the url that you put in this box."] = "N.B. VOEG AAN HET EINDE GEEN SLASHES toe aan de url die u in deze box zet.";
$lang["Which method do you want to use to calculate shipping?"] = "Welke methode wilt u gebruiken om verzendkosten te berekenen?";
$lang["If order sub-total is..."] = "Als het order sub-totaal is ...";
$lang["Greater than"] = "Groter dan";
$lang["And less than"] = "Maar kleiner dan";
$lang["Shipping price is"] = "Zijn de Verzendkosten";
$lang["Save Settings for Local Orders"] = "Sla instellingen op voor Lokale Orders";
$lang["Tutorials"] = "Hulpvideos";
$lang["Users Manual"] = "Handleiding";
$lang["Our collection of flash videos should help you with any questions you have about"] = "Onze collectie flash videos kan u helpen met veel vragen over ons programma. Deze videos zijn echter in het Engels. Kijk daarom ook even op de site waar u lid bent geworden op de pagina 'Videos' wellicht dat er voor uw onderwerp al een Nederlandse video beschikbaar is.";
$lang["Administrator Logins"] = "Administratie Logins";
$lang["Search Engine Ranking"] = "Zoekmachine Ranking";
$lang["Software Updates"] = "Software Updates";
$lang["Show 'Email my login info to me' option on log-in screen?"] = "Toon de \'Email mijn logininformatie naar mij\' in login scherm?";
$lang["Reset Text Editor Mode"] = "Reset Tekst ";
$lang["Site visitors online now"] = "Bezoekers online";
$lang["Average"] = "Gemiddeld";
$lang["Add Blog"] = "Voeg Blog toe";
$lang["Some templates display content from blog categories.  There are 2 types of display, newsbox and promo box"] = "Sommige templates geven de inhoud van blog categoriën weer. Er zijn twee typen weergave, nieuwsbox en promobox";
$lang["Please select a blog category to display content from for each"] = "Selecteer a.u.b. een blog categorie om de inhoud van weer te geven voor elke";
$lang["Assign Blog Category"] = "Wijs toe aan Blog Categorie";
$lang["Newsboxes should display content from which category?"] = "Nieuwsboxen moeten inhoud weergeven van welke categorie?";
$lang["Promo boxes should display content from which category?"] = "Promo boxes moeten inhoud weergeven van welke categorie?";
$lang["Save"] = "Sla op";
$lang["Create blog subjects, add/edit blog content, and assign blog content."] = "Maak blog onderwerpen, VoegToe/Wijzig blog inhoud en Wijs inhoud Toe.";
$lang["Select the separate <U>Modules</U> that this user should have access to"] = "Selecteer de afzonderlijke Modules waar deze gebruiker toegang toe moet hebben";
$lang["Add New Administrative User"] = "Voeg Nieuwe Admin Geburuiker Toe";
$lang["Create new forms and view current forms."] = "Creëer nieuwe formulieren en bekijk bestaande.";
$lang["An asterisk indicates a required field."] = "Een veld met een sterretje is verplicht.";
$lang["User-created data tables"] = "Door gebruiker gemaakte data tabellen";
$lang["System data tables - advanced users only"] = "Systeem data tabellen - alleen voor ervaren gebruikers";
$lang["Create a Table Search Form"] = "Creëer een tabel Zoekformulier";
$lang["Create Pages"] = "Creëer Paginas";
$lang["Delete Pages"] = "Verwijder Paginas";
$lang["Click the Edit button next to any page to being editing that page"] = "Click de Wijzig button naast een pagina om wijzigen";
$lang["Need to create another page? Click the 'Create New Page(s)' button at the bottom of the screen"] = "Een niewe pagina nodig? Klik op de \'Creëer Niewe Pagina(s)\' button onderaan het scherm.";
$lang["Click the Delete button next to any page to delete that page"] = "Klik op de Delete button naast een pagina om te verwijderen";
$lang["Menu status"] = "Menu Status";
$lang["Main Menu Pages"] = "Hoofd Menu Paginas";
$lang["Create new pages"] = "Creëer nieuwe pagina(s)";
$lang["Once you have created at least one product sku in the shopping cart "] = "Wanneer u tenminste 1 product sku in de shopping cart heeft aangemaakt";
$lang["a drop-down menu will appear in this space so that you may place a single sku onto the page."] = "zal er een drop-down menu verschijnen op deze plaats zodat u een enkele sku op de pagina kunt plaatsen";
$lang["Recently Created/Modified"] = "Recent Gecreëerd/Gewijzigd";
$lang["Concise View"] = "Beknopte Weergave";
$lang["Diagnostic"] = "Diagnose";
$lang["Afbeeldingen"] = "Afbeeldingen";
$lang["Documenten, Presentaties en Adobe PDF's"] = "Documenten, Presentaties en Adobe PDF\'s";
$lang["Spreadsheets en CSV files"] = "Spreadsheets en CSV files";
$lang["Zelf aangepaste webformulieren en tekst files"] = "Zelf aangepaste webformulieren en tekst files";
$lang["Zelf aangepaste HTML includes"] = "Zelf aangepaste HTML includes";
$lang["Zelf aangepaste HTML template files"] = "Zelf aangepaste HTML template files";
$lang["Zelf aangepaste PHP scripts"] = "Zelf aangepaste PHP scripts";
$lang["Ongeclassificeerde files"] = "Ongeclassificeerde files";
$lang["Media, document, and code files may be downloaded by clicking on the arrow next to the filename.<br/>"] = "Media-, document- en codefiles kunnen worden gedownload door op het pijltje naast de filenaam te klikken.";
$lang["Image files can be viewed and saved by clicking the preview icon next to the filename.<br/>"] = "Afbeeldingsfiles kunnen worden bekeken en opgeslagen door te klikken op de Bekijk icoon naast de filenaam.";
$lang["Indicates image should be reduced in filesize. This file causes slow load-times when viewing your web site.<br/>"] = "Geeft aan dat afbeelding gereduceerd moet worden in file grootte. Dit file veroorzaakt lange laadtijd van uw webpagina.";
$lang["Create new page(s)"] = "Creëer nieuwe pagina(s)";
$lang["Add to Menu"] = "Voeg toe aan Menu";
$lang["addresses can be entered seperated by a comma)"] = "adressen kunnen worden ingevoerd gescheiden door een komma)";
$lang["Preferences"] = "Voorkeuren";
$lang["Preserve line breaks in event details/description popup"] = "Behoud regelafbreking in geval van details/beschrijving popup";
$lang["Referrer Sites"] = "Verwijzende Sites";
$lang["Referrals (per)"] = "Verwijzingen (per)";
$lang["Referral Site"] = "Verwijzingssite";
$lang["Shopping Cart Color Scheme"] = "Winkelwagen Kleuren Schema";
$lang["Normal table content"] = "Normale tabel inhoud";
$lang["Background color"] = "Achtergrondkleur";
$lang["Text color"] = "Tekstkleur";
$lang["Go directly to checkout when 'Add to Cart' button is pressed on 'More Information' page"] = "Ga direct naar afrekenen als \'Voeg toe aan winkelwagen\' button wordt aangeklikt op de\'Meer informatie\' pagina";
$lang["No - Go to View/Edit Cart first (default)."] = "Nee - Ga eerst naar Bekijk/Wijzig Winkelwagen (default).";
$lang["Yes - Go directly to checkout."] = "Ja - Ga direct naar afrekenen";
$lang["Skip 'More Information' page entirely (advanced)."] = "Sla \'Meer informatie\'pagina geheel over (ervaren).";
$lang["Display a field for Zip/Postal Code?"] = "Toon een veld voor Postcode?";
$lang["YES - Zip/Postal Code"] = "Ja - Zip/Postcode";
$lang["YES - Postal Code"] = "Ja - Postcode";
$lang["YES - Zip Code"] = "Ja - Zipcode";
$lang["NO - Do not display"] = "Nee - Niet tonen";
$lang["US States"] = "USA";
$lang["US States and Territories"] = "USA en Gebiedsdelen";
$lang["US and Canada"] = "USA en Canada";
$lang["Australian States"] = "Australische Staten";
$lang["By specifying a default, or 'local' country, customers will not be able to choose a country"] = "Als u een default land kiest kunnen klanten geen land meer kiezen ";
$lang["Referrer Sites"] = "Verwijzende Sites";
$lang["Referrals (per)"] = "Verwijzignen (per)";
$lang["Referral Site"] = "Verwijzende Site";
$lang["Shopping Cart Color Scheme"] = "Winkelwagen Kleurenschema";
$lang["Normal table content"] = "Normale table inhoud";
$lang["Background color"] = "Achtergondkleur";
$lang["Fax Number"] = "Faxnummer";
$lang["OK"] = "OK";
$lang["Check for Updates Now"] = "Controleer Updates";
$lang["Plugin .zip file"] = "Plugin .zip file";
$lang["Select Base Template"] = "Selecteer Basistemplate";
$lang["The base site template will be applied by default to all pages."] = "De basis sitetemplate zal default op alle pagina\'s worden toegepast.";
$lang["You may override this setting and assign a unique template to an individual site page through the 'Page Properties' menu in the Page Editor."] = "U kunt deze instelling voor individuele pagina\'s wijzigen via \'Pag. Eigenschappen\' in de Pagina Editor.";
$lang["Select a template from the drop-down list, or click 'Browse Templates by Screenshot' to select a template. The image above the drop-down box shows a screenshot of the selected template."] = "Selecteer een template uit het drop down menu of click op \'Doorzoek Templates via Screenshot\' om een template te kiezen. Het plaatje boven de dropdown box toont een screenshot van de geselecteerde template.";
$lang[" Save "] = "Opslaan";
$lang["Enter your template header/logo line"] = "Voer uw template LogoTitel in";
$lang[" Upload "] = "Uploaden";
$lang["AUTOMOTIVE-Classic_Cars-Blue"] = "Automotive-Classic_Cars-Blue";
$lang["Template .zip file"] = "Template .zipfile";
$lang["Select a template from the drop-down list to see a preview of that template, change template settings or upload your own custom template."] = "Selecteer een template uit het drop down menu om een voorbeeld te zien, wijzig de template instellingen of upload uw eigen custom template.";
$lang["Here you can manage administrator logins, multi-user access rights, restart the quickstart wizard and reset the text editor mode."] = "Hier kunt u de administratieve logins managen, multi-gebruiker toegangsrechten, herstart van de snelstart wizard en het resetten van de text editor voorkeur.";
$lang["We are always thinking of ways to improve the product.  You can check for and download updates as we issue them."] = "We proberen contiune dit product te verbeteren. U kunt controleren of er updates zijn en ze downloaden als ze worden vrijgegeven.";
$lang["Install Now"] = "Installeer nu.";
$lang["Remote update file downloaded successfully."] = "Update file succesvol gedownload.";
$lang["Logout Now"] = "Nu Uitloggen";
$lang["Later"] = "Later";
$lang["Business Info"] = "Bedrijfsinformatie";
$lang["SitePal"] = "SitePal";
$lang["Manage your calendar by adding events, changing display settings and organizing your month."] = "Manage uw agenda door evenementen toe te voegen, de layout instellingen te wijzigen en uw maand te organiseren.";
$lang["1"] = "1";
$lang["2"] = "2";
$lang["3"] = "3";
$lang["4"] = "4";
$lang["5"] = "5";
$lang["Forget your password?"] = "Uw password vergeten?";
$lang["Developers: Custom template how-to"] = "Ontwikkelaars: hoe een  Maatwerktemplate te maken.";
$lang["Add Custom Menu Link"] = "Voeg Eigen Menu Link Toe";
$lang["Add Link"] = "Voeg Link Toe";
$lang["Customize your site menu here.  Add/Remove pages, text or buttons, button color and add custom menu links."] = "Pas uw site menu hier aan. Voeg Toe/Verwijder pagina\'s, text of buttons, button kleur en voeg eigen menu links toe.";
$lang["Create, add and manage your site FAQ list's."] = "Maak, voeg toe en manage uw site FAQ lijsten.";
$lang["User tables"] = "Gebruikers tabellen.";
$lang["System tables"] = "Systeem tabellen.";
$lang["Create Search Form"] = "Maak een zoekformulier.";
$lang["Modify"] = "Aanpassen";
$lang["Records"] = "Records";
$lang["Create and manage your site photo albums here.  After you create the album, select it from the current photo albums list and click edit."] = "Maak en manage uw site fotoalbums hier. Nadat u het album creëert, selecteert u het van de huidige albums lijst en klik wijzig.";
$lang["Manage your eNewsletter campaigns."] = "Manage uw eNieuwsbrief campagnes.";
$lang["Create a new eNewsletter campaign."] = "Creëer een nieuwe eNieuwsbrief campagne.";
$lang["Charge tax by"] = "Wijzig belasting door";
$lang["Include shipping charges in tax calculation"] = "Betrek verzendkosten in de btw berekening";
$lang["they will be charged the tax percentages you specify here."] = "hen zal het belastingpercentage in rekening worden gebracht dat u hier opgeeft.";
$lang["Setup secure users who are authorized to view certian parts of your website.  "] = "Organiseer \'Veilige gebruikers\'die geauthoriseerd zijn voor toegang tot bepaalde delen van uw website. ";
$lang["Create security codes (groups) for these users to be assigned to.  "] = "Creëer beveiligingscodes (groepen)om deze gebruikers aan toe te wijzen.";
$lang["Edit information, settings, and group associations for this member (user) account."] = "Wijzig informatie, instellingen en groepseigenschappen voor dit leden- (gebruikers) account.";
$lang["F2 key log-in shortcut opens site builder tool in..."] = "F2 verkorte login opent website-editor in ...";
$lang["Logo Image"] = "Logo Afbeelding";
$lang["Fill-in this contact info for your business. Some site template layouts pull some or all of this information and display it in dedicated area(s) within the layout."] = "Vul deze contact info in voor uw bedrijf. Sommige templates gebruiken deze informatie geheel of gedeeltelijk en tonen het in bepaalde delen in de layout.";
$lang["General Website Preferences"] = "Algemene Website Voorkeuren";
$lang["Miscellaneous preferences, options, and settings that apply to many areas within the sitebuilder admin tool and the content it creates for your website."] = "Overige voorkeuren, opties en instellingen die van invloed zijn op veel onderdelen van de website-editor en de content die het creëert voor uw website.";
$lang["Cancel template change"] = "Template niet wijzigen";
$lang["Choose an image, style and color scheme for your template"] = "Kies een afbeelding, stijl en kleurenpalet  voor  uw template";
$lang["Choose a navigation layout and setting for the footer"] = "Kies een navigatie layout en instelling voor de footer";
$lang["Customize template background colors"] = "Pas achtergrondkleuren van template aan";
$lang["Enter a site title and site slogan"] = "Voer een site titel en een slogan in";
$lang["Enter a name for this template and save"] = "Geef het template een naam en sla op";
$lang[""] = "";


$lang["Business Info"] = "Zakelijke Informatie";
$lang["F2 key log-in shortcut opens site builder tool in..."] = "F2 verkorte login opent de website-editor in ...";
$lang["Here you can manage administrator logins, multi-user access rights, restart the quickstart wizard and reset the text editor mode."] = "Hier kunt u de administratieve toegang, multi gebruiker toegangsrechten, herstart van de quickstart wizard en het resetten van de texteditor vookeur regelen.";
$lang["We are always thinking of ways to improve the product.  You can check for and download updates as we issue them."] = "Wij denken voortdurend na over manieren om dit product te verbeteren. U kunt hier controleren of er updates zijn en ze downloaden.";
$lang["Developers: Custom template how-to"] = "Ontwikkelaars: Maatwerk template, hoe moet dat";
$lang["<h3>Step 1:</h3> Choose an image, style and color scheme for your template"] = "Stap 1:

Kies een afbeelding, stijl en kleurenpalet voor uw template.";
$lang["<h3>Step 2:</h3> Choose a navigation layout and setting for the footer"] = "Stap:
Kies een navigatie layout en instelling voor de footer.";
$lang["<h3>Step 3:</h3> Customize template background colors"] = "Stap3:
Pas de achtergrondkleuren van het template aan.";
$lang["<h3>Step 4:</h3> Enter a site title and site slogan"] = "Stap 4:
Voer een website titel en een slogan in.";
$lang["<h3>Step 5:</h3> Enter a name for this template and save"] = "Stap 5:
Voer een naam in voor dit template en sla het op.";
$lang["Use Template"] = "Gebruik Template";
$lang["Which feature modules should they have access to"] = "Tot welke modules moeten zij toegang krijgen";
$lang["Click icon to enable/disable"] = "Klik op het icoon om in/uit te schakelen";
?>