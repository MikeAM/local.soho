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
## French translation by :   Noel Nguessan  [espacemultimedia at yahoo.com]
## Homepage :                http://www.arobasenet.com
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
$lang["Open Page"] = "Editer Page(s)"; 
$lang["Main Menu"] = "Menu";
$lang["View Site"] = "Visualiser Site";
$lang["Webmaster"] = "Webmaster";
$lang["Logout"] = "Déconnexion";

// Page Editor
$lang["Save Page"] = "Sauvegarder";
$lang["Save As"] = "Sauvegarder Sous";
$lang["Preview Page"] = "Visualiser Page";
$lang["Page Properties"] = "Propiriétés Page";

// Feature Menus
$lang['Shopping Cart Menu'] = "Menu Création Boutique";
$lang['Calendar Menu'] = "Menu Calendrier";
$lang['eNewsletter Menu'] = "Menu Newsletter";
$lang['Database Menu'] = "Menu Base de Données";


#################################################
## STATUS BAR (footer)
#################################################
$lang["Product Build"] = "Product Build";


#################################################
## FEATURE PROMO / LICENSE UPGRAGE PAGE
## - When user clicks on 'disabled' feature
#################################################
$lang["Feature Upgrade Required"] = "Option à Commander";
$lang["Your current license does not allow you to access this feature."] = "La licence de votre pack ne vous autorise pas cette option.";
$lang["In order to activate it, please contact :HOSTCO_NAME: and request an upgrade."] = "Pour accéder à cette option, veuillez contacter :HOSTCO_NAME: et demandez un ajout d'option."; // :HOSTCO_NAME: replaced with host-configured data
$lang["Once you are notified that the new feature(s) have been activated, return to this screen and click the Mise à Jour Licence button. This will download and install your new license and components automatically."] = "Une fois reçue la confirmation de l'activation de vos nouvelles options, retournez à votre espace de création de site et cliquez sur le bouton &quot;Mise à Jour Licence&quot;. Ce qui autorisera un téléchargement automatique de votre nouvelle licence avec ses composants.";

// Live progress report (while getting new key)
$lang['promo']['locating license'] = "Licence Actuelle";
$lang['promo']['license downloaded'] = "Nouvelle Licence Téléchargée";
$lang['promo']['installing license'] = "Installation Licence pour "; // ' $_SERVER['HTTP_HOST']
$lang['promo']['please hold'] = "Veuillez attendre"; 
$lang['promo']['features upgraded'] = "Options Mises à Jour";

$lang['Upgrade License'] = "Mettre à Jour Licence"; // Button


#################################################
## MAIN MENU
#################################################

// General Titles and Notifications
$lang["Site Visitor(s) online"] = "Visiteur(s) en Ligne";
$lang["NOTE: Any data outstanding will not be saved."] = "NOTE: Aucune donnée ne sera sauvegardée.";

// Basic Features Group
$lang["Basic Features Group"] = "Options Basic";
$lang["Create New Pages"] = "Créer Nouvelle(s) Page(s)";
$lang["Edit Pages"] = "Ouvrir/Editer Page(s)";
$lang["Menu Display"] = "Menu Affichage";
$lang["File Manager"] = "Gestionnaire Fichier";
$lang["Template Manager"] = "Gestionnaire Thèmes Graphiques";
$lang["Forms Manager"] = "Gestionnaire Formulaires";

// Advanced Features Group
$lang["Advanced Features Group"] = "Options Evoluées";

$lang["Shopping Cart"] = "Boutique";
$lang["Event Calendar"] = "Calendrier";
$lang["eNewsletter"] = "Newsletter";
$lang["Site Data Tables"] = "Bases de Donn&eacuees Créées";
$lang["Database Table Manager"] = "Gestionnaire Base de Donnés";
$lang["Secure Users"] = "Accès Membres";
$lang["Photo Albums"] = "Albums Photos";
$lang["Site Statistics"] = "Statistiques du Site";
$lang["Blog Manager"] = "Gestionnaire de Blog";

// Javascript Alerts

$lang["Select a menu option from the main menu sections to get started."] = "Choisir une option sur la page principale pour commencer.";
$lang["You do not have access to this option."] = "Vous n'avez pas accès à cette option.";

// Footer Assets

$lang["About"] = "A propos...";

#################################################
## CREATE NEW PAGES MODULE					        ##
#################################################

$lang["Page Name"] = "Page Name";
$lang["Page Type"] = "Page Type";
$lang["Create New Site Pages"] = "Créer Nouvelle Page";
$lang["Menu Page"] = "Menu Page";
$lang["Newsletter"] = "Newsletter";
$lang["Calendar Attachment"] = "Agenda";//////////////////////////////////////////////
$lang["Shopping Cart Attachment"] = "Boutique";//////////////////////////////////////////////////////
$lang["Create More Pages"] = "Créer Plus de Pages";
$lang["You may create up to 10 new pages at a time."] = "Vous pouvez créer jusqu'à 10 nouvelles pages en une seule fois.";
$lang["Your new pages have been created!"] = "Nouvelles pages créées avec succès !\\n\\nVous pouvez maintenant éditer la nouvelle page\\nou choisissez de créer d'autres nouvelles pages.";
$lang["Could Not Create the Following Pages because they already exist on the system:"] = "Impossible de créer ces pages car elles existent déjà dans le système";


#################################################
## OPEN PAGE MODULE							        ##
#################################################
$lang["Edit Content"] = "Editer Contenu";
$lang["Menu Status"] = "Statut Affichage";
$lang["Parent Page"] = "Page Parent";
$lang["Page Template"] = "Page Modèle";
$lang["Delete Page"] = "Supprimer Page";
$lang["Off Menu"] = "Non";
$lang["On Menu"] = "Oui";
$lang["site base template"] = "Thème Graphique";
$lang["Browse"] = "Parcourir";
$lang["Edit"] = "Editer";
$lang["Delete"] = "Supprimer";
$lang["Number Skus"] = "Numéro Référence";
$lang["Articles"] = "Articles";
$lang["Latest News"] = "Dernières Nouvelles";

$lang["Click on the page name that you wish to edit"] = "Cliquez sur le nom de la page que vous souhaitez éditer.";
$lang["Are you sure you wish to delete this page"] = "Etes-vous s&circur de vouloir supprimer cette page ? Cette suppression sera définitive!";

#################################################
## page_editor.php
#################################################
$lang["Click on an object below and drag it onto a drop zone for page placement."] = "Cliquez sur l'un de ces objets et glissez-le dans une des zones ci-dessous de la page éditée.";


#################################################
## MENU DISPLAY MODULE     						  ##
#################################################
$lang["You have already used this page in your menu system."] = "Cette page a déjà été utilisée dans le sommaire de votre site.";
$lang["You can only use pages one time on your auto-menu system."] = "Un seul et m&ecircme nom de page autorisé dans un sommaire.";
$lang["Auto-Menu Display Type"] = "Auto-Menu Display Type";
$lang["Text Links"] = "Texte des Liens";
$lang["Buttons"] = "Boutons";
$lang["Edit Button Colors"] = "Editer Couleurs du Bouton";
$lang["Text Menu Display"] = "Affichage Menu du Texte";
$lang["Yes"] = "Oui";
$lang["No"] = "Non";
$lang["Available Pages"] = "Pages Disponibles";
$lang["Current Menu"] = "Menu Actuel";

$lang["Select a page from your available site pages."] = "Choisir une des pages de votre site.";
$lang["Then, choose to add it to the bottom<BR>of your 'live' menu as a Main Menu Item or a Sub-Page of a Main Menu Item."] = "Ensuite, l'ajouter en bas de page<BR>du menu actuel en tant que Menu Principal ou Sous-page.";//////////////////////////////////////////////////////

$lang["To Delete a page on the current menu"] = "Pour supprimer une page du menu actuel";
$lang["select the page from the available pages"] = "Choisir la page parmi les pages disponibles";
$lang["that already appear on your current"] = "Déjà présent";
$lang["menu, then click 'Delete Page'."] = "menu, puis cliquer sur 'Supprimer Page'.";

$lang["Auto-Menu Button Colors"] = "Menu Bouton Couleurs";
$lang["Current Button Color Scheme"] = "Bouton Palette Couleur";
$lang["Button Color"] = "Bouton Couleur";
$lang["Button Text Color"] = "Bouton Couleur Texte";
$lang["Hex Color"] = "Couleur Hexdecimal";
$lang["About Us"] = "A propos de nous";
$lang["Save Button Colors"] = "Sauvegarder Bouton Couleurs";
$lang["Auto-Menu Setup"] = "Installation Auto-Menu";
$lang["This is a text representation of the color scheme"] = "Ceci est un exemple de bouton représentant la palette couleur\\nactuellement sélectionné pour le système de navigation de votre menu.";

//Buttons
$lang["Add Main"] = "Ajouter au Menu Principal";
$lang["Add Sub"] = "Ajouter au Sous-Menu";
$lang["Clear Menu"] = "Effacer Menu";
$lang["Save Menu System"] = "Sauvegarder Système Menu";



#################################################
## FILE MANAGER MODULE     						  ##
#################################################
$lang["File Name"] = "Nom Fichier";
$lang["File Size"] = "Taille Fichier";
$lang["Image files can be viewed and saved by clicking the preview icon next to the filename."] = "Fichier image pouvant &ecirctre visualisé et sauvegardé en cliquant sur le picto next après le nom fichier.";
$lang["Indicates an image that should be reduced in filesize. This file causes slow load-times when viewing your web site."] = "Indiquer un fichier image dont il faut réduire la taille. Ce fichier causant un ralentissement lors de la visite du site web.";
$lang["Upload New Files"] = "Télécharger Nouveau(x) Fichier(s)";
$lang["Remember"] = "Mémoriser";
$lang["Changes and deletions are final and can not be undone."] = "Toute modification ou suppression reste définitive.";
$lang["Update File Changes"] = "Mettre à Jour";

// Upload New Files
// -------------------------------------------
$lang["Upload Files"] = "Télécharger";
$lang["Select the <U>Browse</U> button next to each filename to locate your local file for upload. When ready to start the upload operation, select <U>Upload Files</U>."] = "Sélectionner le bouton <U>Visualiser</U> pour trouver le fichier local à t&eacuelécharger. Une fois pr&ecirct, cliquer sur <U>Tél&eactecharger</U>.";
$lang["Filename"] = "Nom Fichier";
$lang["Upload of files completed."] = "Téléchargement Réssi.";
$lang["Current Site Files"] = "Fichiers actuels";
$lang["View Current Site Files"] = "Voir les Fichiers";
$lang["Upload Custom Template HTML"] = "Télécharger un modèle graphique personnalisé";
$lang["Upload More Files"] = "Autres téléchargements";
$lang["Success"] = "Succès!";
$lang["Did not upload"] = "Echec téléchargement";
$lang["File update completed."] = "Fichier mis à Jour avec succès.";
$lang["Filename already exists"] = "Nom de fichier existe déjà";
$lang["File is not an accepted file format"] = "Format du Fichier pas accepté";
$lang["Below is a report of the files that were uploaded during this operation"] = "Ci-dessous la liste des fichiers téléchargés durant cette opération";
$lang["Upload Complete"] = "Téléchargement Complet";
$lang["Open/Edit Page(s)"] = "Ouvrir/Editer Page(s)";

#################################################
## SITE TEMPLATE MODULE						   ##
#################################################

// Template Mangager
$lang["Base Site Template"] = "Modèle du Site:";
$lang["The base site template"] = "Le modèle de base du site sera appliqué par défaut à toutes les pages<br> où aucun modèle n'a ètè choisi à via Propriétés de la Page.";
$lang["Browse Templates by Screenshot"] = "Visualiser le modèles";
$lang["Save Changes"] = "Sauvegarder";
$lang["Custom Template Builder"] = "Modifier modèle";
$lang["Upload Custom Template HTML file(s)"] = "Tél&eacuecharger fichiers HTML modèles personnalisés)";
$lang["Upload Template File(s)"] = "Télécharger Fichier(s) Modèle";
$lang["If you are utilizing a built-in template"] = "Si vous utilisez une création personnaliée de modèle, vous devez éditer le haut de page de votre modèle ci-dessous.";
$lang["Built-In Template Header"] = "Modifier Haut de Page du Modèle";
$lang["Enter your template header line"] = "Entrer Nom du Site";
$lang["Save Header"] = "Sauvegarder";
$lang["Save Settings"] = "Sauvegarder";
$lang["Company Slogan or Motto"] = "Slogan de votre société ou site";


// Custom Template Builder
$lang["Template Builder"] = "Créer Modèles";
$lang["Template Name"] = "Nom Modèle";
$lang["Template Image"] = "Image du Modèle";
$lang["Preview Design"] = "Prévisualiser";
$lang["Save Design"] = "Sauvegarder";
$lang["Image Preview Area"] = "Zone de prévisualisation Image";
$lang["Image must be 204px Wide x 106px High"] = "Taille Max Image 204px Largeur x 106px Hauteur";
$lang["Template Style"] = "Style Modèle";
$lang["Blank"] = "Vide";
$lang["Left Bar"] = "Côté Gauche";
$lang["L-Shape"] = "L-Shape";
$lang["U-Shape"] = "U-Shape";
$lang["Pro"] = "Pro";
$lang["Foreground"] = "Plan-Avant";
$lang["Background"] = "Arrière-Plan";
$lang["Title"] = "Titre";
$lang["Text"] = "Texte";
$lang["Links"] = "Liens";

#################################################
## FORMS MANAGER MODULE						        ##
#################################################
$lang["Current Forms"] = "Formulaires Disponibles";
$lang["Custom Forms"] = "Modifier Formulaires";
$lang["New Form Creation Wizard"] = "Outil de Création Nouveau Formulaire";
$lang["To create a new form, enter the name"] = "Pour créer un nouveau formulaire, entrer un nom et cliquer sur Créer Fotmulaire";
$lang["Build New Form"] = "Créer Fotmaulaire";
$lang["Preview"] = "Previsualiser";
$lang["Add New Fields"] = "Ajouter des Champs";
$lang["Add Fields"] = "Ajouter des champs";
$lang["Edit Form"] = "Editer Formulaire";
$lang["Delete Form"] = "Supprimer Formulaire";
$lang["Form Name"] = "Nom du Formulaire";
$lang["PREVIEW WINDOW"] = "FENETRE de PREVISUALISATION";
$lang["You must enter a form name that is at least 3 characters long."] = "Vous devez saisir un nom de formaire ayant au moins 3 caractères.";

//Form Builder Wizard

$lang["Building"] = "Créer";
$lang["Form Field"] = "Champs Formulaire";
$lang["Field Label"] = "Nom du Champ";
$lang["Required Field"] = "Champ Obligatoire";
$lang["What is your site visitor supposed to enter or select for this field"] = "Ce texte s'affiche sur le site.  Qu'est supposé saisir votre visiteur ou choisir pour ce champ";
$lang["Field Type"] = "Type de Champ";
$lang["Field Name"] = "Nom du Champ";
$lang["Text Box"] = "Champ Texte";
$lang["Text Area (Multi-Line)"] = "Champ Rédactionnel (Multi-Ligne)";
$lang["Drop Down Box"] = "Menu déroulant";
$lang["Radio Buttons"] = "Boutons Radio";
$lang["Checkboxes"] = "Champs à Cocher";
$lang["What is the Name of this field"] = "Le <u>Nom</u> de ce champ est appelé lorsque le formulaire est envoyé <br>à votre email ou dans la base de données.  Veuillez utiliser <i>emailaddr</i> pour les champs email que vous souhaitez envoyer au visiteur du site.";
$lang["Field Values"] = "Valeurs du Champ";
$lang["Enter selectable options separated by commas"] = "Entrer les options à choisir en les séparant par une virgule";
$lang["Drop Down Boxes, Radio Buttons, and Checkboxes Only"] = "Menus déroulants, Boutons Radio, et Champs à Cocher uniquement";
$lang["[Save] Finish Form"] = " [Sauvegarder Fromulaire] ";
$lang["Add Next Field"] = "Champ Suivant";

#################################################
## SITE STATISTICS MODULE					        ##
#################################################

// Main Stats Display

$lang["Unique Visitors"] = "Visiteurs Uniques";
$lang["Top 25 Pages"] = "Top 25 Pages";
$lang["Views By Day"] = "Visites par Jour";
$lang["Views By Hour"] = "Visites par Heure";
$lang["Referer Sites"] = "Sites Référants";
$lang["Browser/OS"] = "Navigateur/OS";
$lang["You should empty your log tables at least every six months are so depending on traffic."] = "Vous devez vider votre fichier log au moins une fois tous les 6 mois en fonction de votre trafic.";
$lang["If you experience slowness<BR>in loading reports, your log tables have probably gone unattended for some time."] = "En cas d'affichage très lent<BR>des rapports de stats, votre fichier log doit certainement &ecirctre trop lourd.";

// statistics/includes/unique.php

$lang["UNIQUE VISITOR TREND"] = "VISITEUR UNIQUE";
$lang["Total Unique Visitors"] = "Total des Visiteurs Uniques";
$lang["Total Page Views"] = "Total Pages Vues";
$lang["Visit Frequency"] = "Durée des Visites";
$lang["Avg Pages Per Visit"] = "Nombres de Pages par Visite";

// statistics/includes/top25.php

$lang["TOP 25 SITE PAGES/SITE MODULES"] = "TOP 25 PAGES/SITE MODULES";
$lang["Rank"] = "Rank";
$lang["Page Views"] = "Pages Vues";

// statistics/includes/byday.php

$lang["PAGE VIEWS BY DAY"] = "PAGES VUES PAR JOUR";
$lang["Total Page Views for"] = "Total Pages Vues pour";
$lang["Page Views Per Day Totals"] = "Totaux des Pages Vues par Jour";
$lang["Mouseover a Selected day for actual total"] = "Sélectionner un jour à l'aide la souris pour afficher son total";

// statistics/includes/byhour.php

$lang["PAGE VIEWS BY HOUR"] = "PAGE VUES PAR HEURE";
$lang["Most active hour of the day"] = "Heure la plus active de la journée";
$lang["Mouseover a Selected Hour for actual total"] = "Sélectionner une heure à de la souris pour afficher son total";

// statistics/includes/refer.php

$lang["REFERER SITES"] = "SITES REFERANTS";
$lang["Referals (per)"] = "Référants (par)";
$lang["Referal Site"] = "Site Pointant";

// statistics/includes/browser.php

$lang["BROWSER AND OPERATING SYSTEMS USED"] = "NAVIGATEURS ET SYSTEMES INFORMATIQUES UTILISES";
$lang["Browser"] = "Navigateur";
$lang["Usage Data"] = "Trafic";


#################################################
## PHOTO ALBUM MODULE					           ##
#################################################

// photo_album/photo_album.php
$lang["Photo Album"] = "Album Photo";
$lang["Create New Album"] = "Créer Nouvel Album";
$lang["Enter Album Name"] = "Entrer Nom Album";
$lang["Current Photo Albums"] = "Albums Photos Disponibles";
$lang["Select Album"] = "Choisir Album";

// photo_album/edit_album.php
$lang["Edit Album"] = "Editer Album";
$lang["Image Preview"] = "Prévisualiser Image";
$lang["Details"] = "Détails";
$lang["Image"] = "Image";
$lang["Caption"] = "Commentaire";
$lang["Link"] = "Lien";
$lang["Save Album"] = "Sauvegarder";
$lang["Cancel Edit"] = "Supprimer Editer";

#################################################
## SITE DATA TABLES MODULE					        ##
#################################################

// download_data.php

$lang["Manage/Backup Site Data Tables"] = "Gérer/Sauvegarder Base de Données";
$lang["View"] = "Visualiser";
$lang["Download"] = "Télécharger";
$lang["Import"] = "Importer";
$lang["Empty"] = "Vide";
$lang["Database table"] = "Table Base de Données";
$lang["View All Data Tables"] = "Visualiser toutes les Tables";
$lang["WARNING"] = "ATTENTION !";
$lang["You have selected to clear the data from table"] = "Vous allez supprimer les données de cette table";
$lang["This process is irreversible and will delete all data contained in this table"] = "Suppression irréversible: toutes les données de cette table n'existeront plus";
$lang["Are you sure you wish to continue"] = "Etes-vous s&ucircr de vouloir continuer ?";
$lang["Continue"] = "Continuer";
$lang["Cancel"] = "Supprimer";
$lang["CSV Filenames"] = "Fichiers CSV";
$lang["Select the CSV file that you wish to import"] = "Sélectionner le fichier CSV que vous souhaitez importer";
$lang["Please note that you can only upload comma or semi-colon delimited CSV files"] = "Veuillez noté que vous ne pouvez télécharger que des fichiers CSV comportant des virgules ou semi-barres comme séparateurs";
$lang["If you need to upload your csv file"] = "Si vous voulez télécharger votre fichier csv";
$lang["click here"] = "cliquez ici";
$lang["Use Default Value"] = "Utiliser la Valeur par Défaut";
$lang["Select which fields in the CSV file to place into the existing table fields"] = "Définir quels champs du fichier csv placer dans les champs de la table existante";
$lang["First record of CSV data contains field names. Do not import."] = "Premier enregistrement(ligne) des données CSV contient des noms de champs. Ne pas l'importer.";
$lang["Table Field Name"] = "Nom de la Table";
$lang["CSV Field Name"] = "Nom du Fichier CSV";
$lang["Default Import Value"] = "Valeur par Défaut Import";
$lang["If a field name from your csv file is matched to the PriKey field of the table"] = "Si un nom de champ de votre fichier csv correspondait à la clé primaire(PriKey) de la table, les données du fichier csv<BR>'Mettront à Jour' les clés existantes et 'Attribueront' aux nouveaux enregistrements qui ne correspondent pas une valeur à une clé.";
$lang["Import Data Now"] = "Importer Maintenant";
$lang["IMPORT OF CSV DATA TO"] = "IMPORTER CSV VERS";
$lang["COMPLETE!"] = "SUCCES !";
$lang["Records imported successfully"] = "Enregistrements importés";
$lang["Records were modified"] = "Enregistrements modifiés";
$lang["View all Tables"] = "Voir toutes les Tables";


#################################################
## BLOG MANAGER MODULE					           ##
#################################################

$lang["Blog Subjects"] = "Catégories Blog";
$lang["New Subject"] = "Nouvelle Catégorie";
$lang["Add New"] = "Ajouter";
$lang["Existing Subjects"] = "Catégories";
$lang["View"] = "Visualiser";
$lang["Create a new blog entry by entering your data in the text editor below"] = "Créez un nouveau blog en tapant votre texte dans le cadre ci-dessous";
$lang["Then choose the subject that this blog should be assigned to and click Post Blog to continue"] = "Ensuite choisissez le sujet auquel il sera assigné et cliquez <i>Poster Blog</i> pour continuer";
$lang["Blog Title"] = "Titre du Blog";
$lang["Please choose a subject to post this blog to"] = "Veuillez choisir une catégorie où poster votre blog";
$lang["Please choose a title for this post"] = "Veuillez choisir un titre pour votre blog";
$lang["Post Blog to"] = "Poster le Blog dans ";
$lang["Choose Subject"] = "Choisir une Catégorie";
$lang["Post"] = "Poster";
$lang["Update Complete"] = "Mise à Jour réussie !";
$lang["Can not delete this subject.  Blog data exists"] = "Impossible de supprimer cette catégorie. Des articles y sont.";
$lang["Latest News"] = "Dernières Nouvelles";
$lang["Special Promotions"] = "Promotions Spéciales";

#################################################
## SHOPPING CART MODULE					           ##
#################################################

// shopping_cart.php
// --------------------------------------
$lang["Shopping Cart: Main Menu"] = "e-Commerce: Menu Principal";

// These three make up the sentence "You currently have [NUMBER] products in [NUMBER] categories"
$lang["You currently have"] = "Vous avez actuellement";
$lang["products in"] = "produits";
$lang["categories"] = "catégories";


$lang["Category Names"] = "Noms Catégories";
$lang["Add New Products"] = "Ajouter Catégorie";
$lang["Find/Edit Current Products"] = "Chercher/Editer Produits Actuels";
$lang["Shipping Options"] = "Options d'Expédition";
$lang["Tax Rate Options"] = "Options TVA";
$lang["Payment Options"] = "Options Paiements";
$lang["Business Information"] = "Information de votre Société";
$lang["Display Settings"] = "Afficher les Paramètres";
$lang["Privacy Policy"] = "Politique de Confidentialité";
$lang["Shipping Policy"] = "Conditions Générales de Vente";
$lang["Returns/Exchanges Policy"] = "Conditions de Retours et d'Echanges";
$lang["Other Policies"] = "Conditions Diverses";
$lang["View Online Orders/Invoices"] = "Voir les Commandes/les Factures";

// categories.php
// ---------------------------------
$lang["Shopping Cart: Category Setup"] = "e-Commerce: Créer une Catégorie";
$lang["Current Categories"] = "Catégories Disponibles";
$lang["Add New Category"] = "Ajouter une Catégorie";
$lang["New Category Name"] = "Nom Categorie";
$lang["Add Category"] = "Créer";
$lang["To delete a category"] = "Pour supprimer une catégorie, cliquer sur le lien [ supprimer ] situé après le nom catégorie dans l\'espace 'Catégories Disponibles' .";


// products.php/////////////////////////////////////////
// ---------------------------------
$lang["Shopping Cart: Add New Product"] = "e-Commerce: Ajouter Nouveau Produit";
$lang["No Image"] = "Aucune Image";
$lang["SAVE PRODUCT"] = "SAUVEGARDER";
$lang["PRODUCT INFO"] = "DETAILS PRODUIT";
$lang["PRODUCT IMAGES"] = "IMAGES PRODUIT";
$lang["PRICE VARIATION"] = "VARIATION de PRIX";
$lang["ADVANCED OPTIONS"] = "OPTIONS AVANCEES";
$lang["Part No. (SKU Number):"] = "Part No. (SKU Number):";//////////////////////////////////////////////////
$lang["Unit Price:"] = "Prix Unitaire:";
$lang["Part Name (Title):"] = "Part Name (Titre):";
$lang["Catalog Ref Number:"] = "Numéro Ref Catalogue:";
$lang["Description:"] = "Description:";
$lang["Main Category:"] = "Catégorie Principale:";
$lang["Shipping Charge (A):"] = "Frais de Port (A):";
$lang["Secondary Category:"] = "Catégorie Secondaire:";
$lang["If you are using standard shipping"] = "Si vous utilisez un expédition standard, la valeur des 'Frais de Port (A)' sera le montant total du produit unitaire multipliée par la quantité commandée.";/////////////////////////////////////
$lang["Shopping Cart: Edit Product"] = "e-Commerce: Editer Produit";
$lang["Search Products"] = "Rechercher Produits";

//Product Images
$lang["Select the thumbnail and full image that you wish to associate with this Sku Number."] = "Selectionner la petite image et la grande image que vous souhaitez associées à ce produit.";/////////////////////////////////////////////
$lang["If you are not using thumbnails, do not worry, the system will automatically resize your full size image to the appropriate scale when applicable. However, image quality of the scaled thumbnail may suffer."] = "Si vous n'utilisez pas de petite image, aucune inquiétude, le système réduira automatiquement votre grande image en vignette. Néanmoins, la vignette peut ne pas être de grande qualité.";
$lang["Thumbnail Image:"] = "Petite Image:";
$lang["Full Size Image:"] = "Image Normale:";
$lang["Note: Thumbnail images should be no more than 125px wide."] = "Note: Taille maximum des petites images 125px par c&ocircté.";
$lang["Full Size Images should be no more than 275px wide for optimal display within your web site."] = "Taille maximum des images normales : 275px par côcircté pour un affichage optimal.";
$lang["Image height is flexible."] = "La hauteur de l'image est flexible.";
$lang["Image Preview Window"] = "Fen&ecirctre de Prévisualisation Image";

//Price Variation
$lang["Sub-Category"] = "Sous-Catégorie";
$lang["Variant"] = "Variable";
$lang["Show me what this looks like in operation and how the variant set-up works."] = "Montrez-moi à quoi cela ressemblera sur le site.";

//Advanced Options
$lang["Charge Tax for this product?"] = "TVA pour ce produit?";
$lang["Charge Shipping for this product?"] = "Frais de port pour ce produit?";
$lang["Security Code:"] = "Code Sécurité:";
$lang["Public"] = "Public";
$lang["Attachment Page (Detail Page):"] = "Page de Téléchargement (Detail Page):";
$lang["Recommend this product"] = "Recommander ce produit pendant<BR>voir/editer panier?";
$lang["Recommended Products like this one:"] = "Produits Similaires Recommandés:";
$lang["Enter multiple sku numbers separated by comma"] = "Saisir plusieurs références fournisseur séparées par des virgules";
$lang["When customers add this product to thier cart, require Form Data from:"] = "Lorsque les clients ajoutent ce produit à leur panier, obliger le formulaire à partir de:";
$lang["Per Qty"] = "Par Qté";
$lang["Ignore Qty"] = "Ignorer Qté";
$lang["Purchase of this Sku allows your customer to download the following file:"] = "La commande de cette référence fournisseur autorise le client à télécharger le fichier suivant:";
$lang["Display this Product"] = "Afficher ce produit";
$lang["Inventory Count:"] = "Stock disponible:";
$lang["Additional Category Association:"] = "Catégorie Associée:";
$lang["Special Tax Rate:"] = "Taxe Spéciale supplémentaire";
$lang["Searchable Keywords"] = "Mots-Clés (Ne sont pas affichés sur la page visiteurs; juste pour la recherche des produits dans la base):";


// search_products.php
// ---------------------------------

$lang["Shopping Cart: Find/Edit Product"] = "e-Commerce: Rechercher/Editer Produit";
$lang["Edit/Search For Products"] = "Editer/Rechercher Produits";
$lang["Edit Sku"] = "Editer Numéro Réf. Sku";
$lang["Find"] = "Trouver";
$lang["Search For"] = "Rechercher";
$lang["Search Results"] = "Résultats de Recherche";
$lang["Edit Product Data"] = "Editer Produit";
$lang["Delete Product"] = "Supprimer Produit";


// shipping_options.php
// ---------------------------------
$lang["Shopping Cart: Shipping Options"]  = "e-Commerce: Options Expédition";
$lang["Choose the Shipping Option you wish to utilize for this shopping cart system:"] = "Choisir le moyen d'expédition que vous souhaitez utiliser pour cette boutique:";
$lang["Standard Shipping (Per Sku)"] = "Expédition Standard (Par Réf)";
$lang["Charge By Order Sub-Total"] = "Frais sur le Sous-Total";
$lang["Use Custom PHP Include"] = "Inté un code personnalisé en PHP";
$lang["Offline/Manual Calculation"] = "Calcul Manuel ou Non";
$lang["Save Shipping Options"] = "Sauvegarder";
$lang["SET PRICING GRID, IF ORDER SUB-TOTAL IS..."] = "GRISER LE PRIX, SI LE SOUS-TOTAL EST...";
$lang["Greater Than"] = "Plus Grand Que";
$lang["And Less Than"] = "Et Plus Petit Que";
$lang["Shipping Price"] = "Frais de Port";

// tax_rates.php
// ---------------------------------

$lang["Shopping Cart: Tax Rate Options"] = "e-Commerce: Taux TVA";
$lang["To Add a tax rate"] = "Pour ajouter un taux de TVA, choisir un Etat(USA), une région, et/ou un pays et taper\\nle taux de TVA par article vendu\\net expédié vers ce pays.\\n\\nPour supprimer une TVA, sélectionner un pays déjà utilisé\\net laisser l'espace du taux vierge.";

//One sentence split into three parts
$lang["When visitors purchase items from your site"] = "Lorsqu'un visiteur passe commande à partir de votre site";
$lang["and select delivery to any of the below-listed areas,"] = "et choisir une zone d'expédition dans la liste ci-dessous,";
$lang["they will be charged the tax percentages you specified."] = "ils auront à payer la TVA spécifiée pour ce pays.";

$lang["United States"] = "Etats-Unis";
$lang["Canada"] = "Canada";
$lang["Add/Delete Tax:"] = "Ajouter/Supprimer TVA:";
$lang["Tax Rate"] = "Taux TVA";
$lang["Add/Delete Tax Rate"] = "Ajouter/Supprimer Taux TVA";
$lang["State/Province"] = "Etat/Province";
$lang["There are currently no states in use."] = "Il n'y a pas d'Etat choisi (FR pour France).";
$lang["International Taxes"] = "Taxes Internationales";
$lang["Note: You must enter a valid VAT/GST registration number to charge and collect VAT/GST taxes."] = "Note: Vous devez saisir un numéro valide de Registre de Commerce ou Intracommunautaire.";
$lang["Registration Number:"] = "N° Intra-Communautaire ou RCS:";
$lang["Save Tax Options"] = "Sauvegarder";
$lang["Tax Rate Table Updated."] = "TVA sauvegardée.";
$lang["Country"] = "Pays";
$lang["There are currently no countries in use."] = "Vous devez choisir un Pays.";

// payment_options.php
// ---------------------------------

$lang["Shopping Cart: Payment Options"] = "e-Commerce: Options de Paiement";
$lang["What type of payment processing will you utilize"] = "Quel type de paiement souhaiterez-vous utiliser";
$lang["PayPal"] = "PayPal";
$lang["VeriSign"] = "VeriSign";
$lang["WorldPay"] = "WorldPay";
$lang["Live Credit Card Processing"] = "Demande en ligne de N° CB";
$lang["None"] = "Aucun";
$lang["Offline Credit Card"] = "N° CB par courrier ou Fax";
$lang["Check / Money Order"] = "Chèque / Mandat Cash / Western Union";
$lang["If using credit card processing, select which cards you will accept:"] = "Si vous souhaitez des paiements par CB uniquement, choisissez le type de carte:";
$lang["Choose Currency Type and Symbol"] = "Choisir la Monnaie et le Symbole";
$lang["Currency Type:"] = "Monnaie:";
$lang["Currency Symbol:"] = "Symbole:";
$lang["Select Payment System (Online Processing)"] = "Choisir le système de Paiement en Ligne)";
$lang["If you are using online credit card processing"] = "Si vous choisissez le paiement en ligne, vous devez collecter le paiement de clients à partir des meilleurs systèmes sérisés suivants:";

$lang["WorldPay Payment System"] = "WorldPay";
$lang["How to configure WorldPay for use with your site"] = "Configuration WorldPay";
$lang["Installation ID:"] = "Installation ID:";
$lang["Fix Currency Type"] = "Type de Monnaie";
$lang["Test Mode:"] = "En Mode Test:";
$lang["PayPal Email:"] = "PayPal Email:";
$lang["How to configure VeriSign Payflow Link for use with your site"] = "Configuration de VeriSign Payflow";
$lang["VeriSign Partner ID:"] = "ID Partenaire Verisign:";
$lang["VeriSign Login ID:"] = "ID Login VeriSign:";
$lang["Innovative Gateway Solutions"] = "Innovative Gateway Solutions";
$lang["Innovative Gateway"] = "Innovative Gateway";
$lang["Username"] = "Identifiant";
$lang["Password"] = "Mot de Passe";

$lang["I want to use online processing but I have a custom PHP include payment gateway"] = "J'ai mon propre script de paiement en ligne fourni par ma banque";
$lang["system that I want to use in place of the others listed"] = "Systmème que je souhaite personnellement utiliser";

$lang["This will over-ride all processing for credit cards."] = "Ceci occultera tous les autre moyens de paiement en ligne. Le systmè contr&ocirclera simplement le script mais il vous reviendra d'en assurer la configuration totale par rapport à notre base.";

$lang["I am using an SSL Certificate with my web site and when going to the checkout"] = "J'utilise un Certificat SSL(https) pour mon site ainsi que pour le processus de paiement en ligne";
$lang["the following https:// call should be made to the scripts"] = "le lien appelé par le script devra commencer par https:// ";
$lang["to invoke the SSL Cert."] = "pour invoquer le Certificat SSL.";

//Full Sentence = "For example if you must use https://secure.[domain.com] to activate your SSL certificate, type https://secure.[domain.com] in the field above. DO NOT ADD ANY TRAILING FORWARD SLASHES. If you are unsure, consult your web developer."
$lang["For example if you must use <U>https://secure."] = "Par exemple si vous devez utiliser <U>https://secure.";
$lang["</U> to activate your SSL certificate, type"] = "</U> pour activer le certificat SSL, tapez";
$lang["<B>https://secure."] = "<B>https://secure.";
$lang["</B> in the field above. DO NOT ADD ANY TRAILING FORWARD SLASHES. If you are unsure, consult your web developer."] = ".com</B> dans le champ ci-dessous. NE PAS AJOUTER DE SLASH A LA FIN. Si vous n'&ecirctes pas s&ucircr de vous, nous vous recommandons de confier la t&acircche à un webmaster.";

$lang["When displaying the final invoice to my customer, I want to execute a custom PHP include"] = "Pour afficher la facture finale de mon client, Je voudrais exécuter un include d'un scriptr PHP personnalisé";
$lang["that processes data when the invoice is displayed."] = "qui transmettra des données lorsque la facture s'affichera.";


$lang["Custom Include File:"] = "Fichier Personnel en Include:";
$lang["This include can be used to create custom processes that execute after products have been purchased from your system."] = "Cet Include peut vous permettre de provoquéer un processus interne aprè que le client ait passé commande.";
$lang["For example, you may wish to assign a new user automatically with a generated username and password to the Secure Users table after a membership payment."] = "Par exemple, créer automatiquement un identifiant et un mot de passe après paiement.";
$lang["Save Payment Options"] = "Sauvegarder";


// business_information.php
// ---------------------------------

$lang["You will need to enter the address, phone number and whom to make a <U>check or money order</U>"] = "Entrer votre adresse, téléphone ainsi que l'ordre du <U>chèque ou mandat cash</U>";
$lang["payable to for your online store.  This will display to your site visitors at checkout time."] = "Ces coordonnées s'afficheront pour les clients qui choisissent ces modes de paiement.";
$lang["Make Payable To:"] = "Payable à l'ordre de:";
$lang["Address:"] = "Adresse:";
$lang["City"] = "Ville";
$lang["State/Province:"] = "Région (FR pour France):";
$lang["Zip/Postal Code:"] = "Code Postal:";
$lang["Country:"] = "Pays:";
$lang["Phone Number:"] = "Téléphone:";
$lang["Statistics have shown that displaying this information on your site will increase trust<BR>among shoppers and therefore produce better sales results."] = "Les statistiques démontrent qu'afficher ce genre d'informations entraîcircne une confiance<BR>de la part des éventuels clients et facilite les ventes.";

$lang["When orders are placed on your website, they are saved in your order/invoice area."] = "Lorsque des commandes sont passées sur votre site, elles sont sauvegardées dans votre espace commandes/factures.";
$lang["The system will automatically send you an <U>email notifing you of new orders</U>.  Please "] = "Vous recevez alors un <U>email vous notifiant l'arrivée de nouvelles commandes</U>.  Veuillez ";
$lang["enter the email address where you wish these notifications to be sent. (Multiple email"] = "entrer l'adresse email où vous souhaitez recevoir ces notifications. (Multiple emails";
$lang["addresses can be entered separated by a comma)"] = "peuvent &ecirctre saisies en les séparant d'une virgule)";

$lang["Notification Email Address:"] = "Adresse Email de Notification:";
$lang["If you are using the \"Allow Product Comments\" option, when <U>users submit comments</U>"] = "Si vous utilisez \"Autoriser Commentaires Produits\", lorsque <U>les clients soumettent des commentaires</U>";
$lang["about your products, the comments will be saved and an email generated to the email"] = "concernant vos produits, les commentaires seront sauvegardés et un email sera envoyé à votre adresse email";
$lang["address below for verification. If the comments meet your approval, you can then allow"] = "pour vérification. Si les commentaires vous conviennent, vous pouvez autoriser";
$lang["the comments to be made visible by the public.  This is done to prevent unsavory or"] = "l'affichage des commentaires pour le public. Et ce, afin de prévenir des commentaires désagréables";
$lang["lude comments from being posted without your knowledge."] = "postés sans votre assentiment.";
$lang["Verification Email Address:"] = "Verification Adresse Email:";

$lang["After your customers purchase products from your site, they will receive an <U>email"] = "Une fois que vos clients passent commande sur votre site, ils recoivent une <U>facture";
$lang["invoice</U> of the order for their records. The default header text is a simple thank"] = "par email</U> de la commande passée. Le titre par défaut du message est un simple texte de";
$lang["you and is provided below.  You may modify this to say anything you wish.  The actual"] = "remerciement comme indiqué ci-dessous.  Vous pouvez le modifier comme vous voulez.  Cette facture";
$lang["invoice with pricing breakdowns, tax, shipping, etc. will appear below this header text."] = "reprenant les détails de la commande dont les prix des articles, la TVA, frais de port, etc. appara&icirctra juste en dessous du titre message.";

$lang["Save Business Info"] = "Sauvegarder";


// display_settings.php
// ---------------------------------
$lang["Shopping Cart: Display Settings"] = "e-Commerce: Afficher Paramètres";

$lang["Shopping Cart Feature Options"] = "Configuration Site e-Commerce";
$lang["Page Header:"] = "Page Ent&ecircte:";
$lang["Welcome To..."] = "Bienvenue sur ...";
$lang["Show 'Client Login' Button in search column"] = "Afficher module 'Accès Client' dans la colonne de recherche";
$lang["Allow 'Email to Friend' feature"] = "Autoriser option 'Envoyer à un Ami'";
$lang["Allow 'Remember Me' feature"] = "Autoriser option 'Se Souvenir de Moi'";
$lang["Display Search Box"] = "Afficher Moteur de RechercheDisplay Search Box";
$lang["Place 'Search Column' on which side of page"] = "Placer 'Colonne Moteur Recherche' de quel c&ocirct&eacue de la page";
$lang["Left"] = "à Gauche";
$lang["Right"] = "à Droite";
$lang["Display 'text linked' categories"] = "Afficher 'texte des liens' catégories";
$lang["Allow users to add product comments"] = "Autoriser les visiteurs à poster des commentaires";
$lang["If using this option, place an email address to verify submissions in the 'Business Information' section."] = "Si vous choisissez cette option, entrez une adresse email pour valider les commentaires soumis à partir de la section 'Messages'.";
$lang["International Options:"] = "Options Internationales:";
$lang["Choose State/Province Display Type:"] = "Choisir un Etat/Province (FR pour France):";
$lang["U.S. States"] = "U.S. States";
$lang["Canadian Provinces"] = "Canadian Provinces";
$lang["U.S. and Canada"] = "U.S. and Canada";
$lang["Text Field"] = "Texte";
$lang["Do Not Display"] = "Ne Pas Afficher";
$lang["Specify Default 'Local' Countries:"] = "Specifier  Pays 'local' ou par Défaut':";

$lang["By specifying a defualt, or 'local' country, customers will not be able to choose a country"] = "En spécifiant un pays 'local' ou par défaut, les clients ne pourraient plus choisir de pays (vous ne vendrez qu'à ce pays choisi)";
$lang["for their billing and shipping addresses. Instead, your shopping cart will assume that all customer orders are placed"] = "pour pouvoir vous indiquer leur adresse postale. Ce qui reviendrait donc à dire que votre boutique n'est disponible que";
$lang["from the country you specify. To prevent confusion, you should make prominent mention of this on your website."] = "pour le pays spécifié. Pour éviter toute confusion, vous devez mentionner cette particularité sur la page d'accueil de votre boutique.";


$lang["Search Result Settings"] = "Configuration Moteur de Recherche";
$lang["User Defined Button:"] = "Définir Texte ...Plus d'Info:";
$lang["This button links to the 'More Information' page.  Leaving this blank will not show the button at all."] = "Ce bouton qui est lien enverra le visiteur vers votre page 'Détails ou Information Complémentaire'. Aucun bouton ne sera affiché si vous laissez ce champ vide.";
$lang["Show 'Add to Cart' button under thumbnail images instead of 'Buy Now!' on initial searches"] = "Afficher bouton 'Ajouter au Panier' sous la petite image du produit en lieu et place du bouton 'Acheter Maintenant!' lors d'une recherche de produits";

$lang["How should initial searches sort data"] = "Ordre d'affichage des résultats de recherche";
$lang["Sku Number"] = "Sku Number - Numéro Ref Fournisseur";
$lang["Catalog Ref Number"] = "Numéro Ref votre Catalogue";
$lang["Product Name"] = "Nom Produit";
$lang["Product Price"] = "Prix Produit";
$lang["Shipping Variable (B)"] = "Variable Frais de Port (B)";
$lang["Shipping Variable (C)"] = "Variable Frais de Port (C)";


$lang["Number of results to display on searches"] = "Nombre de résultats affichés par page";
$lang["Search Product"] = "Rechercher";
$lang["Browse Categories"] = "Dans les Catégories";
$lang["Category"] = "Catégories";
$lang["Product"] = "Produit";
$lang["Sub-Total"] = "Sous-Total";
$lang["Checkout Now"] = "Payer Maintenant";
$lang["Search Column Color Scheme"] = "Couleur Style Colonne Recherche";
$lang["Header Background"] = "Couleur Arrière-Plan Ent&ecircte";
$lang["Header Text"] = "Texte Ent&ecircte";
$lang["Shopping Cart Background"] = "Arrière-Plan Boutique";
$lang["Shopping Cart Text"] = "Nom de la Boutique ou du Site";
$lang["Or choose a pre-defined color scheme"] = "Ou choisir une palette couleur pré-dédéfinie";
$lang["Choose Scheme"] = "Choisir Thème";
$lang["America"] = "America";
$lang["Classic"] = "Classic";
$lang["Earth"] = "Earth";
$lang["Movies"] = "Movies";
$lang["Neon Green"] = "Neon Green";
$lang["Sports"] = "Sports";
$lang["Save Display Settings"] = "Sauvegarder";


// privacy_policy.php
// ---------------------------------
$lang["Shopping Cart: Privacy Policy"] = "e-Commerce: Conditions Génésrales de Vente";

$lang["Standardized eCommerce systems use a privacy policy to disclose how systems operate. The one provided here is generic"] = "En général, toute boutique de vente en ligne présente une politique de confidentilité pour disculper tout malentendu sur le fonctionnement technique de la boutique. Celle que nous vous proposons des générique";
$lang["and covers all technical issues regarding the operation of this shopping cart system such as session management and cookies."] = "et couvre tous les aspects techniques concernant les segments de l'utilisation du système  tels que l'application des sessions et des cookies.";
$lang["You may wish to modify this policy statement to your particular business needs. It should disclose all information pertaining"] = "Vous pouvez modifier ce texte pour l'adapter à votre cas particulier. Il devra apporter toute information pertinente sur le procédé technique";
$lang["to the use and storage of all data gathered from the checkout process."] = "sur l'usage et le stockage des données personnelles enregistrées lors du paiement en ligne par le client.";

$lang["Save Privacy Statement"] = "Sauvegarder";


// shipping_policy.php
// ---------------------------------
$lang["Shopping Cart: Shipping Policy"] = "e-Commerce: Conditions d'Expédition";

$lang["Your shipping policy informs your customers of how and when you ship the items that they purchase."] = "Votre politique d'expédition informe les clients sur comment et quand vous leur expédiez leur commande.";
$lang["Be as detailed as possible here and note any special charges that may occur."] = "Soyez très descriptif dans cette section autant que vous le pouvez afin de vous éviter tout soucis, notamment sur les frais de port, taxes et délai d'expédition.";

$lang["Save Shipping Policy"] = "Sauvegarder";


// returns_policy.php
// ---------------------------------
$lang["Shopping Cart: Returns/Exchanges Policy"] = "e-Commerce: Conditions de Retours et/ou d'Echanges";

$lang["If your customers wish to return of exchange an item purchased online"] = "Si vos clients ne sont pas satisfaits de votre produits et souhaitent le retourner ou l'échanger, veuillez détailler <BR>les conditions d'acceptation. Si toute commande passée est définitive, d&icirctes-le leur ici.";
$lang["Save Returns/Exchanges Policy"] = "Sauvegarder";


// other_policies.php
// ---------------------------------
$lang["Shopping Cart: Other Policies"] = "e-Commerce: Conditions Diverse";

$lang["Use this section to list other types of policies that you may have for your site."] = "Utilisez cette section pour décrire toutes les autres conditions non encore mentionnés et que vous appliquez à votre site.";
$lang["Remember to title each policy as it will displayed as is."] = "N'oubliez surtout pas de mettre un titre à chacune de ces différentes conditions d'utilisation.";
$lang["Save Policy Statement"] = "Sauvegarder";


// view_orders.php
// ---------------------------------
$lang["View/Retrieve Orders"] = "Voir/Valider Commandes";


$lang["Displaying order numbers"] = "Afficher Numéro des Commandes";
$lang["Search results for"] = "Résultats de Recherche pour";
$lang["Displaying all orders between"] = "Afficher toutes les commandes entre";
$lang["Download Results"] = "Résultats Téléchargement";
$lang["Print Results"] = "Résultats Impression";
$lang["New Search"] = "Nouvelle Recherche";
$lang["Order Number"] = "Numé Commande";
$lang["Order Date"] = "Date Commande";
$lang["Order Time"] = "Heure";
$lang["Customer"] = "Client";
$lang["Payment Method"] = "Paiement";
$lang["Status"] = "Statut";
$lang["Total Sale"] = "Total Vente";
$lang["Transaction ID"] = "N° Transaction";
$lang["Invoice"] = "Facture";
$lang["No invoices where found matching your search. Please try again."] = "Aucune facture ne correspond à votre recherche. Veuillez réessayer.";


// search.inc
// ---------------------------------
$lang["Search Orders"] = "Rechercher Commandes";
$lang["Select your prefered search method"] = "Méthodes de Recherche";
$lang["Show order numbers"] = "Afficher numéros commandes";
$lang["From"] = "De";
$lang["To"] = "A";
$lang["Select how results should be sorted for viewing"] = "Ordre d'Affichage";
$lang["Sort by"] = "Par";
$lang["Order Date"] = "Date Commande";
$lang["Order Number"] = "Numéro Commande";
$lang["Order by"] = "Commander Par";
$lang["Customer Name"] = "Nom Client";
$lang["Total Sale"] = "Total Vente";
$lang["Payment Method"] = "Moyen Paiement";
$lang["Status"] = "Statut";
$lang["Transaction ID"] = "N° Transaction";
$lang["Ascending"] = "Ascendant";
$lang["Descending"] = "Descendant";
$lang["Date range"] = "Classer par Date";
$lang["Format"] = "Format";
$lang["Search for keywords"] = "Recherche par mots-clés";


// view_invoice.php
// ---------------------------------

$lang["PURGE"] = "PURGER";
$lang["PRINT"] = "IMPRIMER";
$lang["EXIT"] = "SORTIE";
$lang["Payment Method"] = "Moyen Paiement";
$lang["Order Status"] = "Statut Commande";


#################################################
## EVENT CALENDAR    					           ##
#################################################

// event_calendar.php
// ---------------------------------

$lang["Event Calendar: Main Menu"] = "Agenda: Menu Principal";
$lang["Search Events"] = "Rechercher Evènements";
$lang["Display Settings"] = "Afficher Paramètres";
$lang["Category Setup"] = "Créer Catégorie";
$lang["Edit View"] = "Editer Voir";

// add_event.php
// ---------------------------------
$lang["Add Calendar Event"] = "Ajouter un Evènement";

// build_month.php
// ---------------------------------

$lang["Add Event"] = "Ajouter";

// category_setup.php
// ---------------------------------
$lang["Add/Modify Calendar Categories"] = "Ajouter/Modifier Catégories Agenda";
$lang["Create New Category"] = "Créer Nouvelle Catégorie";
$lang["Add Category"] = "Ajouter Catégorie";
$lang["Current Categories"] = "Catégories Créées";

// display_settings.php
// ---------------------------------
$lang["Calendar Display Settings"] = "Paramètres Affichage Agenda";

// search_events.php
// ---------------------------------
$lang["Search Event Calendar"] = "Rechercher Evènement";

// "Found [X] events that match your search criteria."
$lang["Found"] = "Il y a";
$lang["events that match your search criteria"] = "évènement(s) qui correspond(ent) à vos critères de recherche";

$lang["Sorry, no events where found for your search. Please try again."] = "Désolé, aucun évènement n'a été trouvé. Veuillez réessayer.";


// add_events_form.php
// ---------------------------------

$lang["Apply To"] = "Appliquer à";
$lang["THIS EVENT ONLY"] = "CET EVENEMENT UNIQUEMENT";
$lang["All occurrences of this event"] = "toutes les occurrences de cet évènement";
$lang["Save Event"] = "Sauvegarder";
$lang["Event Date"] = "Date Evènement";
$lang["Start Time"] = "Date Début";
$lang["Event Title"] = "Tite Evènement";
$lang["Event Details (Description)"] = "Détails Evènement (Description)";
$lang["Event Category"] = "Catégorie Evènement";
$lang["All"] = "Tous";
$lang["Security Code (Group)"] = "Code Sérité (Groupe)";
$lang["Public"] = "Public";
$lang["When saving or changing this event, email a notice to the following email addresses"] = "En cas de sauvegarde ou de modification de cet évènement, un email est immédiatement envoyé aux aux adresses email suivantes (separatées par une virgule)";
$lang["Event Recurrence"] = "Recurrence Evènement";
$lang["No Recurrence"] = "Pas de Recurrence";
$lang["Daily"] = "Quotidien";
$lang["Weekly"] = "Hebdomadaire";
$lang["Monthly"] = "Mensuel";
$lang["Yearly"] = "Annuel";
$lang["Daily Pattern"] = "Modèle Quotidien";

//full sentence = "This event should re-occur every [number] days"
$lang["This event should re-occur every"] = "Cet évènement se répé tous les";
$lang["days"] = "days";

$lang["Weekly Pattern"] = "Modèle Hebdomadaire";

//full sentence = "This event should re-occur every [number] weeks on"
$lang["This event should re-occur every"] = "Cet évènement se répé toutes les";
$lang["weeks on"] = "semaine(s) sur";


$lang["Sunday"] = "Dimanche";
$lang["Monday"] = "Lundi";
$lang["Tuesday"] = "Mardi";
$lang["Wednesday"] = "Mercredi";
$lang["Thursday"] = "Jeudi";
$lang["Friday"] = "Vendredi";
$lang["Saturday"] = "Samedi";
$lang["Monthly Pattern"] = "Modèle Mensuel";
$lang["This event should re-occur on the"] = "Cet évènement se répé chaque";
$lang["of each month"] = "du mois";
$lang["Yearly Pattern"] = "Modèle Mensuel";
$lang["You have selected for this event to occurr every year on"] = "Vous avez choisi que cet évènement se renouvelle chaque année le"; // "every year on [X month]"

$lang["This event will start on the date of the selected 'Event Date' and continue for how long"] = "Cet évènement débutera à la date choisie à 'Date Evènement' et se répétera aussi longtemps..."; //"?"
$lang["No End Date"] = "Aucune Date de Fin";

//"End after [X] occurences."
$lang["End after"] = "Se terminera après";
$lang["occurrences"] = "occurrences";


// calendar_settings_form.php
// ---------------------------------

$lang["Color Scheme"] = "Couleur de Présentation";
$lang["Header Text"] = "Texte Ent&ecircte";
$lang["Select Text Color"] = "Couleur du Texte";
$lang["Header Background"] = "Arrière-Plan Ent&ecircte";
$lang["Select Background Color"] = "Couleur Arrière-Plan";
$lang["Pre-Defined Schemes"] = "Présentation Prédéfinies";
$lang["Color Schemes"] = "Couleurs de Présentation";
$lang["Default Standard"] = "Standard Par défaut";
$lang["Reds"] = "Reds";///////
$lang["Allow authorized users to maintain personal calendars"] = "Autoriser vos membres à maintenir leur propre agenda"; // "?"

$lang["Initial Calendar Display Layout"] = "Disposition Initiale Affichage Agenda";
$lang["Monthly"] = "Mensuel";
$lang["Weekly"] = "Hebdomadaire";
$lang["Allow the public to submit events for inclusion"] = "Autoriser les visiteurs à vous soumettre des évènements à insérer"; // "?"
$lang["If so, where should confirmations be emailed to"] = "Si oui, à adresse email sera envoyée la notification des soumissions"; // "?"
$lang["Color Preview"] = "Visualisation Couleur";
$lang["Calendar Header"] = "Ent&ecircte Agenda";
$lang["Event Dates"] = "Dates des Evènements";
$lang["Save Display Settings"] = "Sauvegarder";


// event_search_form.php
// ---------------------------------
$lang["Search Event Calendar"] = "Rechercher Evènement";
$lang["Search for Keywords"] = "Par Mots-Clés";
$lang["Search in Month/Year"] = "Par Mois/Année";
$lang["Search In Category"] = "Par Catégorie";


// update_events_form.php
// ---------------------------------
$lang["Apply To"] = "Appliquer à";
$lang["THIS INDIVIDUAL EVENT ONLY"] = "CET EVENEMENT UNIQUEMENT";
$lang["ALL OCCURRENCES OF THIS EVENT"] = "TOUTES LES OCCURRENCES DE CET EVENEMENT";
$lang["Event Date"] = "Date Evènement";
$lang["Start Time"] = "Date Début";
$lang["End Time"] = "Date Fin";
$lang["Security Code (Group)"] = "Code Securité (Groupe)";
$lang["Use commas to seperate multiple email addresses"] = "Insérer des virgules si plusieurs emails";
$lang["Event Recurrence"] = "Recurrence Evènement";
$lang["No Recurrence"] = "Aucune Recurrence";
$lang["Daily Pattern"] = "Modèle Quotidien";

// "This event is a part of [X] other recursive events."
$lang["This event is a part of"] = "Cet évènement fait partie de";
$lang["other recursive events"] = "autres évènements recursifs";

$lang["Master Event"] = "Evènement Majeur";
$lang["Recursive Event"] = "Evènement Recursif";

#################################################
## E-NEWSLETTER    					              ##
#################################################
// enewsletter.php
// ---------------------------------

$lang["eNewsletter System: Main Menu"] = "Lettre de Diffusion: Menu Principal";

// "You have selected to delete the campaign [X]. Do you wish to continue with this action?"
$lang["You have selected to delete the campaign"] = "Vous avez décidé de supprimer la campagne";
$lang["Do you wish to continue with this action"] = "Etes-vous s&ucirc ?";

// "You have selected to send the campaign [X] to [X] people total. Do you wish to continue with this action?"
$lang["You have select to send the campaign"] = "Vous avez décidé d'envoyer la campagne";
$lang["to"] = "à";
$lang["people total.  Do you wish to continue with this action"] = "abonnés.  Voulez-vous continuer ?";

$lang["Your campaign has been sent"] = "Votre lettre de diffusion a été envoyée !"; // "!"
$lang["SENDING CAMPAIGN"] = "ENVOYER";
$lang["This may take up to 30 seconds"] = "Ceci peut prendre jusqu'à 30 secondes";
$lang["Create New Campaign"] = "Créer une nouvelle lettre";
$lang["HTML Emails"] = "Format HTML";
$lang["TEXT Emails"] = "Format TEXTE";
$lang["Sent Date"] = "Date d'Envoi";
$lang["Campaign Name"] = "Nom de la Campagne";
$lang["Data Table"] = "Base de Données";
$lang["Recipients"] = "Receveurs";
$lang["Views"] = "Vus";
$lang["Status"] = "Statut";
$lang["View"] = "Vu";
$lang["Action"] = "Action";
$lang["Pending"] = "En Attente";
$lang["SENT"] = "ENVOYE";
$lang["View"] = "Vu";
$lang["Send Now"] = "Envoyer Maintenant";
$lang["Manually Unsubscribe Email Addresses"] = "Désabonner Manuellement des Adresses Email";

// create_campaign.php
// ---------------------------------

$lang["eNewsletter Campaign Setup Wizard"] = "Création d'une campagne de diffusion";
$lang["Please select a table name to use for this campaign"] = "Veuillez choisir une base de données pour cette campagne";
$lang["Please enter a valid campaign name before continuing"] = "Veuillez saisir un nom valide de campagne avant de continuer";
$lang["You need to select a template and content file in order to preview"] = "Veuillez sélectionner un thème graphique et un contenu avant de prévisualiser";
$lang["You need to select a template and content file in order to continue"] = "Veuillez sélectionner un thème graphique et un contenu avant de continuer";
$lang["This may take a few seconds"] = "Patientez quelques secondes ...";
$lang["STEP"] = "ETAPE";
$lang["ASSIGN CAMPAIGN NAME"] = "ASSIGNER UN NOM de CAMPAGNE";
$lang["A. Give this new campaign a name for easy identification on the campaign manager page"] = "A. Donner à votre campagne un nom facile à identifier";
$lang["B. Choose a database table that contains the email addresses for this campaign:"] = "B. Désigner une table de votre base de données contenant  les adresses emails pour cette campagne:";
$lang["Next"] = "Suivant";
$lang["Field Names"] = "Noms des Champs";
$lang["MATCH REQUIRED FIELD DATA"] = "CHAMPS OBLIGATOIRES";

// "In order to build this campaign using ["X" dB Table], you will need to tell..."
$lang["In order to build this campaign using"] = "Pour créer cette campagne qui utilise";
$lang["you will need to tell the system which fields in the table correspond to the data needed by the eNewsletter system when sending this campaign"] = "vous devez dire au système quel champs de la Table correspond aux données dont aura il aura besoin pour envoyer votre Lettre de Diffusion";


$lang["A. Field containing <U>FIRST NAME</U> data"] = "A. Champs contenant le <U>NOM</U>";
$lang["B. Field containing <U>EMAIL ADDRESS</U> data"] = "B. Champs contenant l' <U>ADRESSE EMAIL ADDRESS</U>";
$lang["C. Field containing the <U>EMAIL TYPE</U> data"] = "C. Champs contenant le <U>TYPE d'EMAIL TYPE</U>";
$lang["If the user has HTML or TEXT preference"] = "Si l'abonné a choisi le Format HTML ou le Format TEXTE";
$lang["OWNER INFORMATION"] = "INFORMATION du PROPRIETAIRE";
$lang["This campaign will arrive as an email to your list."] = "Cette campagne parviendra à votre liste de diffusion comme un email.";
$lang["Please indicate what email address it will<BR>come from and the subject line"] = "Veuillez indiquer l'adresse email <BR>expéditeur et le sujet de la Lettre"; // ":"

$lang["A. <U>From</U> email address"] = "A. <U>De</U> adresse email";
$lang["B. <U>Subject Line</U> of this campaign"] = "B. <U>Sujet</U> de la Lettre";
$lang["Next"] = "Suivant";
$lang["Newsletter Content Pages"] = "Contenus Pages de la Lettre de Diffusion";
$lang["[NONE] Template Contains Content"] = "[Aucun] Modèle existant";
$lang["HTML CONTENT"] = "CONTENU HTML";
$lang["Please select the template file and page name which contains the enewsletter content for<BR>sending the HTML version of this campaign"] = "Veuillez indiquer le fichier modèle en HTML et le nom de la page contenant la Lettre de diffusion<BR> pour l'envoi en version HTML de cette campagne";
$lang["Select the template to use with this campaign"] = "Selectionner le modèle que vous souhaitez pour votre campagne";
$lang["Browse Templates"] = "Voir les Modèles";
$lang["Select a page to use for your content"] = "Selectionner une page à utiliser pour le contenu";

// "For those users that have selected to receive text only campaigns, please create the text that will..."
$lang["For those users that have selected to receive text only campaigns"] = "Pour les abonnés ayant souhaité ne recevoir que des les lettres au format TEXTE";
$lang["please create the text that will be sent to those users as well as embedded in the header of the HTML newsletter in case of errors"] = "veuillez créer le texte à cliquer qui leur sera présenter en début de Texte HTML pour obtenir le format de leur choix";

$lang["Creating the campaign does NOT send emails now."] = "Créer une campagne ne signifie pas envoyer la Lettre de Diffusion maintenant.";

$lang["Error: This campaign does not appear to have any email addresses to send to"] = "Erreur: Cette campagne n'a aucune adresse email à expédier";
$lang["HTML Types found"] = "Types HTML trouvés";
$lang["TEXT Types found"] = "Types TEXTE trouvés";
$lang["DevString"] = "DevString";
$lang["DevString"] = "DevString";
$lang["HTML"] = "HTML";
$lang["TEXT"] = "TEXTE";
$lang["Error Writing to Data Table (Could not create campaign): This is a programming error, consult with your webmaster."] = "Erreur Lors de l'Ecriture dans la Base (Impossible de créer de campagne): Veuillez contacter le support technique.";
$lang["Campaign Created"] = "Campagne Créé";
$lang["Campaign Manager"] = "Gestionnaire Campagne";

$lang["Your campaign has been added with pending status. You may now preview or"] = "Votre campagne a été ajouté et est en attente de validation. Vous pouvez prévisualiser ou";
$lang["SEND your campaign from the \"Campaign Manager\" Interface."] = "ENVOYER votre campagne à partir de l'Interface \"Gestionnaire Campagne\".";


// news-browse_templates.php
// ---------------------------------
$lang["Browse Website Templates"] = "Thèmes Graphiques";
$lang["Select a category to browse from the drop down box above. When your find a template you like, simply click the template to continue."] = "Selectionnez une catégorie. Ensuite cliquez sur le modèle de votre choix pour continuer.";


// preview.php
// ---------------------------------

$lang["View HTML Preview"] = "Voir le Modèle HTML";
$lang["View TEXT Preview"] = "Voir le Modèle TEXTE";
$lang["Close Preview Window"] = "Fermer cette Fen&ecirctre";

// send_now.php
// ---------------------------------
$lang["If you do not wish to receive this email, unsubscribe to this service now."] = "Si vous ne souhaitez plus recevoir de message, veuillez vous désabonner maintenant.";

// view_setup.php
// ---------------------------------
$lang["Visit our Website"] = "Visiter notre Site";


#################################################
## DATABASE TABLE MANAGER   		              ##
#################################################
// database_tables.php
// ---------------------------------

$lang["Database Table Manager: Main Menu"] = "Gestionnaire Base de Données: Menu Principal";
$lang["Create New Data Table"] = "Créer une Nouvelle Table";
$lang["Create a Search"] = "Créer une Recherche";
$lang["Delete a Table"] = "Supprimer une Table";
$lang["Modify Selected Table"] = "Modifier une Table";
$lang["Enter/Edit Record Data"] = "Entrer/Editer un Enregistrement";
$lang["Please select a user data table."] = "Sélectionnez une table membres.";
$lang["Batch Authenticate Users"] = "Batch Authentification Membres";


// auth_users.php
// ---------------------------------

$lang["Authenticate Users : Add Authorized Users via Data Table"] = "Accès Membres : Ajouter les Membres Autorisés";
$lang["You must select a field name for all red selection boxes."] = "Vous devez seacutelectionner un nom de champs pour tous boxes rouges.";///////////////////////////////////////
$lang["The second selection under 'user/company full name' is optional."] = "La seconde sélection sous 'nom membre/société' est optionnelle.";
$lang["This may take a few seconds..."] = "Patientez quelques secondes... ";
$lang["CAN NOT AUTHENTICATE USERS VIA TABLE"] = "IMPOSSIBLE D'AUTHENTIFIER MEMBRES VIA LES TABLES";

$lang["This would indicate that you have not set-up a security code (group) OR"] = "Ceci pour vous faire comprendre que vous n'avez pas encore créer de code sécurité (groupe) OU";
$lang["you have not created at least (1) authorized user."] = "vous n'avez pas encore créé au moins (1) membre autorisé.";

$lang["You will need to do these things before adding authenticated users via a table dump."] = "Vous devez effectuer ces t&acircches avant d'ajouter des membres autorisés via la table.";/////////////////////////////
$lang["Current UDT Tables..."] = "Tables Disponibles...";
$lang["SELECT DATA TABLE USAGE"] = "SELECTIONNER UNE TABLE";
$lang["Select the User Defined Table (UDT) that you wish to use as your authenticated user data:"] = "Choisir la Table que vous souhaitez utiliser pour authentifier les membres:";
$lang["Select Field Name"] = "Selectionner le Nom du Champs";

// "CONFIGURE AUTHENTICATION DATA (AUTORIZE [X] USERS)."
$lang["CONFIGURE AUTHENTICATION DATA"] = "GESTION TABLE MEMBRES";
$lang["AUTHORIZE"] = "AUTHORISER";
$lang["USERS"] = "MEMBRES";

// "For each field needed to register an authenticated user, match the field name in [TABLE NAME] to the<BR>required authenticated user fields."
$lang["For each field needed to register an authenticated user, match the field name in "] = "Pour chaque champ dont vous aurez besoin pour enregistrer les membres, le nom de ce champ doit correspondre à ";
$lang["to the<BR>required authenticated user fields."] = "parmi<BR>les champs requis pour l'enregistrement des membres.";

$lang["Next"] = "Suivant";
$lang["New Authenticated Users Added"] = "Nouveaux Membre Ajoutés";
$lang["Database Menu"] = "Base de Données";
$lang["You can view and/or edit individual user settings through<BR>the Secure Users feature."] = "Vous pouvez visualiser et/ou éditer des paramètres individuels à partir de<BR>l'espace Membres.";


// create_table.php
// ---------------------------------
$lang["Table Manager: Create New Table"] = "Gestionnaire de Table: Créer une Nouvelle Table";
$lang["Error"] = "Erreur";
$lang["BACK TO TABLE BUILD"] = "RETOUR CREER TABLE";

$lang["1. What is the name for this table"] = "1. Nom de la table";
$lang["NOTE: Do not use numbers or spaces in names; these are invalid"] = "NOTE: Pas de chiffres ni espace!";
$lang["SQL table names. You may use underscores to represent spaces."] = "Noms de table SQL. Vous pouvez utiliser '_' à la place des espaces.";
$lang["Table Name"] = "Nom Table";
$lang["Invalid Table Name"] = "Nom Table Incorrect";
$lang["2. How many fields will this table contain"] = "2. Nombre de champs de la table"; //"?"

$lang["The data you have entered is not formated properly"] = "L'information saisie n'est pas correctement formulée";
$lang["in order to create your table. Please check your"] = "afin de créer votre table. Veuillez vérifier votre";
$lang["setup and try again."] = "configuration et essayer à nouveau.";
$lang["The last error calculation occurred on line item"] = "La dernière erreur relevée provient de la ligne n°";

$lang["Create Table"] = "Créer Table";
$lang["NOTE"] = "NOTE";
$lang["Do not use numbers or spaces in names; these are invalid SQL field names."] = "Ne pas utiliser de chiffres ou des espaces; ils sont invaldes pour les noms de champs SQL.";
$lang["You may use underscores(_) to represent spaces."] = "Vous pouvez utiliser des underscores(_) àn la place des espaces.";
$lang["Novices who are unsure about what some of these options mean, simply input your field names leaving the default selection as is."] = "Pour les novices n'ayant pas de notions concernant ces options, tapez uniquement les noms des champs en laissant la sélection par défaut.";
$lang["This will insure proper operation."] = "Ceci va créer une opération propre.";
$lang["By default, a Primary Key field and Image field will also be added automatically to your table."] = "Par défaut, une Clé primaire et une Image du champs seront automatiquement ajoutées à votre table.";
$lang["Field Name"] = "Nom de Champs";
$lang["Field Type"] = "Type de Champs";
$lang["Field Length"] = "Longueur du Champs";
$lang["Default Value"] = "Valeur per Défaut";


// delete_table.php
// ---------------------------------

$lang["Table Manager: Delete Table"] = "Gestionnaire de Table: Supprimer Table";
$lang["WARNING"] = "ATTENTION !";

// "YOU ARE ABOUT TO DELETE THE TABLE [TABLE NAME] AND LOSE ALL RECORD DATA CONTAINED INSIDE OF IT."
$lang["YOU ARE ABOUT TO DELETE THE TABLE"] = "VOUS ETES SUR LE POINT DE SUPPRIMER LA TABLE ";
$lang["AND LOSE ALL RECORD DATA"] = "ET PERDRE TOUTES LES DONNEES";
$lang["CONTAINED INSIDE OF IT."] = "QUE CONTIENT CETTE TABLE.";
$lang["Are you sure you wish to do this now"] = "Etes-vous s&ucircr de vouloir le faire maintenant"; //"?"
$lang["You did not select a table to delete."] = "Veuillez sélectionner une table à supprimer.";
$lang["NOTE"] = "NOTE";
$lang["THIS PROCESS CAN NOT BE REVERSED ONCE COMPLETED."] = "CE PROCESSUS EST IRREVERSIBLE.";
$lang["ALL DATA WILL BE LOST WHEN THIS TABLE IS DELETED."] = "TOUTES LES DONNEES SERONT PERDUES UNE FOIS LA TABLE SUPPRIMEE.";
$lang["YOU WILL HAVE ONE CHANCE TO CONFIRM, BUT ONCE YOU 'OK' THE CONFIRMATION, THE TABLE WILL BE DELETED"] = "VOUS DEVEZ CONFIRMER, MAIS UNE FOIS APPUYE LE BOUTON DE CONFIRMATION 'OK', LA TABLE SERA SUPPRIMEE"; //"!"
$lang["Delete Table"] = "Supprimer Table";
$lang["Delete Selected Table"] = "Supprimer Table Sélectionnée";
$lang["Cancel Delete"] = "Annuler Suppression";

// enter_edit_data.php
// ---------------------------------
$lang["Table Manager: Enter/Edit Record Data"] = "Gestionnaire de Table: Entrer/Editer Données Enregistrements";
$lang["You have selected to delete this record."] = "Vous avez décidé de supprimer cet enregistrement.";
$lang["You will not be able to undo this choice."] = "Vous ne pourrez plus revenir en arrière.";
$lang["Do you wish to continue with this action"] = "Voulez-vous continuer"; //"?"

$lang["Find Record"] = "Rechercher Enregistrement";
$lang["ADD_NEW"] = "ADD_NEW";
$lang["Add New Record"] = "Ajouter Nouvel Enregistrement";
$lang["Total Number of Records in Table"] = "Nombre Total d'Enregistrements";
$lang["Number of Records Found in Search"] = "Résultat de Recherche";
$lang["OPTION"] = "OPTION";
$lang["Previous"] = "Suivant";


// modify_table.php
// ---------------------------------

$lang["Table Manager: Modify Table"] = "Gestionnaire de Table: Modifier Table";
$lang["Modify Table"] = "Modifier Table";
$lang["Update Complete"] = "Mise à Jour Réussie !";
$lang["Field Name"] = "Nom Champs";
$lang["Field Type"] = "Type Champs";
$lang["Field Length"] = "Longueur Champs";
$lang["INT"] = "INT";
$lang["DATE"] = "DATE";
$lang["Update Table"] = "Mettre Table à Jour";
$lang["The data you have entered is not formated properly."] = "Les données saisies ne sont pas valides.";
$lang["Please check your setup and try again."] = "Veuillez vérifier votre configuration et essayer à nouveau.";
$lang["Add New Field to"] = "Ajouter un Nouveau Champs à "; // "[TABLE NAME]"
$lang["Field Name"] = "Nom Champs";
$lang["Field Type"] = "Type Champs";
$lang["Field Length"] = "Longueur Champs";
$lang["Default Value"] = "Valeur par Défaut";
$lang["Rename Table"] = "Renommer Table";


// wizard_start.php
// ---------------------------------
$lang["Data-Table Search Wizard"] = "Moteur de Recherche";////////////////////////
$lang["This may take a few seconds..."] = "Veuillez patientez...";
$lang["ASSIGN SEARCH NAME"] = "NOM DE VOTRE MOTEUR DE RECHERCHE";
$lang["Give this search a name."] = "Donner un nom à ce moteur de recherche.";
$lang["This will be used as an identifier in the Page Editor, and displayed to site visitors when searching"] = "Il sera utilisé comme un identifiant dans la Page Editeur, et affiché ainsi à vos visiteurs pour leur recherche interne.";
$lang["SELECT DATA TABLE USAGE"] = "SELECTION BASE DE DONNEES";
$lang["Select the User Defined Table (UDT) that this search will utilize"] = "Choisir la Base de Données qui sera utilisée par ce moteur de recherche";
$lang["Back"] = "Retour";
$lang["CONFIGURE SEARCH FORM"] = "CONFIGURER MOTEUR DE RECHERCHE";
$lang["Configure the search criteria by which site visitors will search"] = "Configurer les paramètres avec lesquels les visiteurs du site effectuerront leur recherche";
$lang["NOTE: You will be able to preview the form in the next step and make changes if you wish."] = "NOTE: Vous pourrez prévisualiser le formulaire à la page suivante et la modifier si vous le désirer.";
$lang["You will be able to preview the form in the next step and make changes if you wish"] = "Vous pourrez prévisualiser le formulaire à la page suivante et le modifier si vous le désirer";
$lang["If you wish to utilize a keyword search, select which fields should be searched."] = "Si vous souhaitez utiliser une recherche par mot-clé, sélectionnez les champs de la recherche.";
$lang["DROP DOWN BOX SELECTION FIELDS"] = "SELECTION CHAMPS DE RECHERCHE";
$lang["Fields selected here will display all records within as options in a drop down box."] = "Tous les champs sélectionnés ici afficheront tous les résultats sans autre option.";
$lang["VERIFY SEARCH FORM"] = "VERIFIER LE FORMULAIRE DE RECHERCHE";
$lang["This is exactly the form site visitors will see when using this search."] = "Ceci est exactement le formulaire que verront vos visiteurs sur le site.";
$lang["Click the back button to make any changes."] = "Cliquer sur le bouton retour pour effectuer toute modification.";
$lang["All Fields"] = "Tous les Champs";
$lang["SEARCH"] = "RECHERCHER";
$lang["Search by Keyword"] = "Recherche par Mots-clés";
$lang["Separate multiple keywords by spaces"] = "utliser une virgule en cas de pluralité de mots-clés";
$lang["Detail Search"] = "Détail Recherche";
$lang["Define Search Method"] = "Définir Méthode de Recherche";
$lang["Keyword Only"] = "Mots-Clés uniquement";
$lang["Selections Only"] = "Sélections Uniquement";
$lang["Keyword AND Selections"] = "Mots-Clés et Sélections";
$lang["Keyword OR Selections"] = "Mots-Clés ou Sélections";
$lang["Search Now"] = "Rechercher";
$lang["Back"] = "Retour";
$lang["SEARCH RESULTS DISPLAY"] = "AFFICHAGE RESULTATS DE LA RECHERCHE";
$lang["There are two steps used when displaying the results of a search."] = "Il y a 2 étapes à franchir pour afficher les résultats d'une recherche.";
$lang["The first data displayed is called the 'Initial Results', and displays the selected field data in a chart format."] = "La première donnée affichée est appelée 'Résultats Initiaux', et affiche les noms des champs sélectionnés.";
$lang["At that point, site visitors may select to <I>View Details</I>, which displays the 'Details Page'."] = "A ce stade, les visiteurs devront cliquer sur <I>Voir Détails</I>, pour voir afficher la 'Page Details'.";
$lang["This page shows more detailed information about the choosen record."] = "Cette page montre plus d'informations détaillées du lien cliqué.";
$lang["Select for each field when and where it's value should be displayed during the above process"] = "Selectionner pour chaque champs quand et o&ucirc sa valeur s'affichera pendant le processus";
$lang["Field Name"] = "Nom Champs";
$lang["Display Setting"] = "Paramètres Affichage";
$lang["Don't Display"] = "Ne Pas Afficher";
$lang["Initial Results"] = "Résultats Initiaux";
$lang["Details Page"] = "Page Détails";
$lang["Display on Both"] = "Afficher Ensemble";
$lang["DETAIL VIEW SETUP AND SECURITY"] = "VOIR PARAMETRES DETAIL ET SECURITE";
$lang["Select the display format (look and feel) of the 'Details Page'"] = "Sélectionner l'apparence de l' affichage de la 'Page Détails'";
$lang["Standard (Default)"] = "Standard (Par Défault)";
$lang["Custom PHP Include"] = "Include PHP Personnalisé";
$lang["Select a security code (group) required to access this search"] = "Selectionner un code de sécurité (groupe) requis pour avoir accès à ce moteur de recherche";
$lang["Public is Default"] = "Public Par Défaut";
$lang["Build Search Now"] = "Créer Moteur de Recherche";
$lang["Search Creation Complete"] = "Création réussie !";
$lang["Database Menu"] = "Base de Données";
$lang["Use the 'Searchabe Database' object in the page editor to place your search on a site page."] = "Cliquer-Déplacer l'objet de 'Recherche de Base de Données' dans la page Editeur pour placer votre moteur à l'endroit de votre choix.";


#################################################
## SECURE USERS MODULE     		              ##
#################################################
// security.php
// ---------------------------------
$lang["Page/Product Security"] = "Sécurité Page/Produit";
$lang["Authorized Users"] = "Membres Autorisés";
$lang["Create New User"] = "Créer Nouveau Membre";
$lang["Current Authorized Users"] = "Liste M%embres Autorisés";
$lang["Select User"] = "Selectionner Membre";
$lang["Security Codes"] = "Codes Sécurité";
$lang["Create New Security Code (Group)"] = "Créer Nouveau Code Sécurité (Groupe)";
$lang["Name"] = "Nom";
$lang["Create Group"] = "Créer Groupe";
$lang["ACTION"] = "ACTION";
$lang["Current Security Codes (Groups)"] = "Liste Codes S&eacuecurité (Groupes)";
$lang["Select Code"] = "Selectionner Code";
$lang["How does this module work"] = "Comment fonctionne ce module";
$lang["Click Here"] = "Cliquez ici";


// security_create_user.php
// ---------------------------------

$lang["Create New Authorized User"] = "Créer un Nouveau Membre";
$lang["You have selected to delete this authorized user."] = "Vous souhaiter supprimer ce membre.";
$lang["THIS PROCESS CAN NOT BE REVERSED"] = "CETTE SOPPRESSION SERA DEFINITIVE";
$lang["Select OK to DELETE this user now."] = "Cliquer sur OK pour SUPPRIMER MAINTENANT.";
$lang["Save Changes"] = "Sauvegarder";
$lang["Delete User"] = "Supprimer Membre";
$lang["Authentication Info"] = "Info Authentification";
$lang["User Info"] = "Info Membre";


// shared/sec_user_form.inc
// ---------------------------------
$lang["User/Company Full Name"] = "Membre/Société Nom";
$lang["User/Company Email Address"] = "Membre/Société Adresse Email";
$lang["Assigned Username"] = "Identifiant/Pseudo";
$lang["Assigned Password"] = "Mot de Passe";
$lang["Expiration Date"] = "Date Expiration";
$lang["Login Redirect Page"] = "Page de Redirection en d'accès";
$lang["(Module) Shopping Cart"] = "(Module) e-Commerce";
$lang["What site page should this user be sent to upon login?"] = "Sur quelle page sera redirigé l'utilisateur après s'&ecirctre identifié ?";
$lang["Select the security codes (groups) this user should have access to"] = "Seacutelectionner les codes sécurité (groupes) auxquels aura accès ce membre";
$lang["There are currently no security codes (groups) created"] = "Aucun code de sécurité (groupes) créé"; //"!"

$lang["All authorized users must be associated with a security group."] = "Tout membre doit &ecirctre associé à un groupe sécurité.";


// shared/sec_user_form.inc
// ---------------------------------
$lang["(Optional) If you wish for this user to be remembered automatically when using the<BR>shopping cart system, please fill out all the customer data below."] = "(Optionel) Si vous souhaitez que ce client soit automatiquement mémorisé par le système<BR>e-Commerce, veuiilez remplir tout le formulaire ci-dessous.";
$lang["Billing Information"] = "Information Facture";
$lang["First Name"] = "Prénom";
$lang["Last Name"] = "Nom";
$lang["Company Name"] = "Société";
$lang["Optional"] = "Optionnel";
$lang["Address"] = "Adresse";
$lang["No PO Boxes"] = "Pas de Bo&icircte Postale (sauf Afrique)";
$lang["City/Town/Locality"] = "Ville";
$lang["Region or Province/State/District"] = "Région";

$lang["Country"] = "Pays";

$lang["Postal/Zip Code"] = "Code Postal";
$lang["Home Phone Number"] = "Tél Domicile";
$lang["Country Code"] = "Code Pays (FR pour France)";
$lang["Email Address"] = "Adresse Email";
$lang["INVALID EMAIL ADDRESS"] = "ADRESSE EMAIL INVALIDE";
$lang["Shipping Information"] = "Adresse d'Expédition";
$lang["First Name"] = "Prénom";
$lang["Last Name"] = "Nom";
$lang["Company Name"] = "Société";
$lang["Optional"] = "Optionnel";
$lang["Address"] = "Adresse";
$lang["No PO Boxes"] = "Pas de Bo&icircte Postale";
$lang["City/Town/Locality"] = "Ville";
$lang["Region or Province/State/District"] = "Région";
$lang["State Invalid"] = "Région Invalide";
$lang["Postal/Zip Code"] = "Code Postal";
$lang["Ship-To Phone Number"] = "Téléphone";
$lang["Country Code"] = "Code Pays (FR pour France)";




#################################################
## CLIENT-SIDE DISPLAY ELEMENTS		           ##
#################################################

// object_write.php
// ---------------------------------
$lang["Get Directions"] = "Plan d'Accès";
$lang["Courtesy of"] = "Juridiction";
$lang["Email this page to a friend"] = "Envoyer cette page à un(e) ami(e)";
$lang["Sign-up Now"] = "S'inscrire";
$lang["Search Products"] = "Rechercher Produits";
$lang["Browse Categories"] = "Parcourir Catégories";


// pgm-realtime_builder.php
// ---------------------------------

$lang["This page has been emailed to your friend"] = "Cette page a été envoyée à votre ami(e)"; //"!"
$lang["Thank you"] = "Nous vous remercions"; //"!"
$lang["Your message has been sent. Thank you."] = "Votre message a été envoyé. Merci !";


// pgm-blog_display.php
// ---------------------------------
$lang["Weblog Archives"] = "Blog Archives";
$lang["Archives"] = "Archives";
$lang["January"] = "Janvier";
$lang["February"] = "Février";
$lang["March"] = "Mars";
$lang["April"] = "Avril";
$lang["May"] = "Mai";
$lang["June"] = "Juin";
$lang["July"] = "Juillet";
$lang["August"] = "Ao&ucirct";
$lang["September"] = "Septembre";
$lang["October"] = "Octobre";
$lang["November"] = "Novembre";
$lang["December"] = "Décembre";


// pgm-email_friend.php
// ---------------------------------
$lang["I found this web site that you might be interested in"] = "J'ai trouvé un site qui pourrait t'intéresser.";
$lang["so I thought I'd email it to you..."] = "Aussi je souhaitais t'informer...";
$lang["Just click on the link to see it!"] = "Tu n'às qu'à cliquer sur ce lien pour le voir !";
$lang["I found something you might want to see..."] = "J'y ai vu un sujet vraiment intéressant pour toi...";
$lang["Email this page to a friend"] = "Envoyer cette page à un(e) ami(e)";
$lang["Your Name"] = "Votre Nom";
$lang["Your Email Address"] = "Votre Adresse Email";
$lang["Friends Email Address"] = "Adresse de Votre Ami(e)";
$lang["Personal Message"] = "Message Personnel";
$lang["Send Now"] = "Envoyer";


// pgm-form_submit.php
// ---------------------------------
$lang["The email address you entered is invalid or"] = "L'adresse email que avez saisi est incorrecte ou";
$lang["You left a required field or fields blank."] = "Vous avez laissé un champ vide.";
$lang["Please enter the following data before continuing"] = "Veuillez saisir les données suivantes avant de continuer";
$lang["Auto Generated Form Email"] = "Formulaire Envoi Automatique Email";
$lang["Email Address"] = "Adresse Email";

$lang["This message is auto-generated by your web site when the"] = "Ce message est auto-généré par votre site lorsque";
$lang["form is submitted by a site visitor on page"] = "le formulaire est soumis par un visiteur à partir de votre page "; // "[Page Name]"
$lang["No need to reply"] = "Ne répondez pas à cette lettre";

$lang["This data has been saved to the"] = "cette donnée a été sauvegardée dans la table"; //"[Table Name]"
$lang["database table"] = "base de données";

$lang["Your site visitor received the custom response file"] = "Votre visiteur a reçu la réponse personnalisée du fichier"; // "[File Name]"
$lang["Website Form Submission"] = "Formulaire de Contact"; // This is default subject line for form emails.
$lang["Thank you for your form submission today! This email is to confirm the reception"] = "Nous vous remercions pour votre email";
$lang["of your recently submitted data."] = "dont nous accusons réception.";
$lang["We received the following:"] = "Voici le message reçu:";
$lang["Thank You"] = "Merci !";
$lang["This message is auto-generated by our web site."] = "Ceci est un message automatique.";
$lang["Please do not reply to this email."] = "Veuillez ne pas répondre.";


// pgm-numusers.php
// ---------------------------------
$lang["Visitors Currently Online"] = "Visiteurs en Ligne";


// pgm-print_page.php
// ---------------------------------
$lang["THIS PAGE IS CURRENTLY UNDER CONSTRUCTION"] = "CETTE PAGE EST EN CONSTRUCTION";
$lang["This Week in"] = "Cette Semaine de"; // "[Month]"
$lang["Page Visits"] = "Pages Visitées"; // ": [#]"
$lang["More Info"] = "+ Info";


// pgm-single_sku.php
// ---------------------------------
$lang["More Information"] = "Plus d'Information";


// pgm-cal-confirm.php
// ---------------------------------
$lang["This event has been added to your calendar system."] = "Evènement ajouté à votre agenda.";
$lang["It appears this event has already been added to your system."] = "Evènement déjà ajouté.";

// pgm-cal-details.inc.php
// ---------------------------------
$lang["Print Details"] = "Imprimer";
$lang["Close Window"] = "Fermer Fen&ecirctre";
$lang["Event Date"] = "Date";
$lang["Event Time"] = "Heure";
$lang["Event Details"] = "Détails";
$lang["More Details"] = "Plus Détails";


// pgm-cal-submitevent.inc.php
// ---------------------------------
$lang["Private"] = "Privé";
$lang["Submit an Event"] = "Ajouter";
$lang["Your Name"] = "Votre Nom";
$lang["Your Email Address"] = "Votre Adresse Email";
$lang["Event Date"] = "Date";
$lang["Event Category"] = "Catégorie";
$lang["Start Time"] = "Heure Début";
$lang["Event Title"] = "Titre de l'Evènement";
$lang["Event Details"] = "Détails de l'Evènement";
$lang["Submit Event"] = "Soumettre";
$lang["All fields are required to submit an event except Event End Time and Event Details."] = "Tous les champs sont obligatoires exceptés ll'Heure de Fin de l'évènement et Détails de l'Evènement.";


// pgm-cal-system.php
// ---------------------------------

$lang["Please Setup Calendar System Display Settings."] = "Paramètres d'Affichage de l'Agenda.";
$lang["Private"] = "Privé";
$lang["Your selected event has been deleted."] = "Votre eacutevènement sélectionné à été supprimé.";
$lang["You did not enter one or more required fields. Please modify your submission and try again."] = "Tous les champs sont obligatoires. Veuillez modifier et recommencer.";
$lang["Event Added to your Calendar"] = "Evènement Ajouté";
$lang["The following event was submitted to your calendar. To approve this event, click the approve link below."] = "Cet évènement vient d'&ecirctre ajouté à votre Agenda. Pour le valider, cliquez sur le lien ci-dessous.";
$lang["If you do not wish to add this event to your calendar, simply disregard this email."] = "Si vous ne souhaitez pas valider cet évènement, oubliez cet email.";
$lang["Event Date"] = "Date Evènement";
$lang["Event Category"] = "Categorie Evènement";
$lang["Event Title"] = "Titre Evènement";
$lang["Start Time"] = "Heure Début";
$lang["End Time"] = "Heure Fin";
$lang["Event Details"] = "Détails Evènement";
$lang["To approve, click the link below:"] = "Pour valider, cliquez sur le lien ci-dessous:";
$lang["THIS IS AN AUTO-GENERATED EMAIL FROM YOUR WEBSITE"] = "CECI EST UN MESSAGE AUTOMATIQUE DE VOTRE SITE";
$lang["Your submission has been sent to our calendar manager for approval."] = "Votre soumission a bien été envoyé et est en attente de validation.";
$lang["Thank you"] = "Merci.";
$lang["Current View"] = "Actuellement";/////////////////////
$lang["View"] = "Visualisation";
$lang["Submit an Event"] = "Soumettre un Evènement";
$lang["Detail Event Search"] = "Recherche Détail Evènement";
$lang["Month"] = "Mois";
$lang["Current Category"] = "Catégories";
$lang["In Category"] = "Dans la Catégorie";
$lang["Search Now"] = "Rechercher";
$lang["Submit a search to change categories."] = "Soumettre une recherche pour modifier des catégories.";
$lang["Events for the Week of"] = "Evènements pour la semaine du"; // "[Month DD-DD]"

$lang["Events for"] = "Evènements de"; // "[month]"
$lang["that match your search for"] = "Résultat de votre recherche pour"; // [Search Query]


$lang["your personal calendar"] = "Votre agenda personnel"; // [User's Name]
$lang["the category"] = "catégorie"; // [category selection]
$lang["located in"] = "situé dans"; // located in [category]/"your personal calendar"

$lang["Edit Event"] = "Editer Evènement";
$lang["Delete Event"] = "Supprimer Evènement";
$lang["This is your private event."] = "C'est votre Evènement privé.";
$lang["No details available for this event."] = "Aucun détail disponible pour cet évènement.";
$lang["in category"] = "dans la catégorie";
$lang["There where no events found for your selection or search"] = "Aucun résultat pour cette recherche";
$lang["Please search for an event or select the day or week you wish to view."] = "Veuillez rechercher un évènement ou sélectionner le jour ou la semaine à visualiser.";
$lang["Authorized user logged in"] = "Membre connecté";
$lang["Indicates your private event"] = "Indique votre évènement privé";
$lang["No one else can view this event but"] = "Personne d'autre ne pourra voir cet évènement sauf"; //[user's name]


// newsletter/index.php
// ---------------------------------
$lang["Please enter the email address where you wish NOT to receive future emails"] = "Veuillez saisir l'adresse email où vous ne souhaitez plus recevoir nos informations";
$lang["Unsubscribe Now"] = "Se Désabonner Maintenant";

$lang["UNSUBSCRIBE FROM"] = "LIEN DE DESABONNEMENT"; // [url]
$lang["EMAIL SERVICE"] = "EMAIL SERVICE";

$lang["The email address"] = "Votre adresse email"; // [unsubscribed address]
$lang["is no longer subscribed to our services."] = "n'est plus inscrite à notre Lettre d'Information.";

$lang["If you need to remove another email address from our subscription system"] = "Si vous souhaitez supprimer une autre adresse email de notre base";
$lang["click here"] = "cliquez ici";

$lang["Visit"] = "Visitez ce lien"; // [url]
$lang["now"] = "maintenant";


// pgm-photo_album.php
// ---------------------------------
$lang["Available Album(s)"] = "Album(s) Disponible(s)";

$lang["Current Album is"] = "Album Actuel est";
$lang["Change Album"] = "Changer d'Album";

$lang["To change albums, highlight your"] = "Pour changer d'albums, sélectionnez en surlignant"; // <br>
$lang["choice and click the 'Change Album' button."] = "votre choix et cliquez sur le bouton 'Changer d'Album'.";

$lang["Prev"] = "Suivant";
$lang["Next"] = "Précédent";
$lang["There are currently no images in this album."] = "Pas d'images dans cet album.";



// pgm-secure_login.php
// ---------------------------------

$lang["The page you have requested requires security access."] = "La page que vous solliciter requière de vous identifier.";
$lang["Please enter your username and password now."] = "Veuillez entrer votre identifiant et votre mot de passe.";
$lang["It appears your login does not grant you access to this page."] = "Identifiant et/ou Mot de Passe incorrect(s).";
$lang["If you feel this is in error, please contact us for further assistance."] = "Si vous estimez qu'il y a une erreur de notre système, veuillez contacter notre assistance technique.";

$lang["Click here"] = "Cliquez ici";
$lang["to return to the home page."] = "pour retourner à la page d'accueil.";

$lang["Please Login"] = "Connexion";
$lang["Username"] = "Login";
$lang["Password"] = "Mot de Passe";
$lang["Sorry, we do not recognize that username and password.<BR>Please check your spelling and try again."] = "Désolé, login et/ou mot de passe incorrect(s).<BR>Veuillez recommencer.";
$lang["It appears the username and password that you entered has expired."] = "Identifiant et Mot de Passe ne sont plus valides.";
$lang["Your access is no longer available."] = "Vos droits d'accès ne sont plus valides.";
$lang["Click here"] = "Cliquez ici";
$lang["to return to the home page."] = "pour retourner à la page d'accueil.";
$lang["Forget your password"] = "Mot de Passe oublié ?";


// pgm-secure_manage.php
// ---------------------------------
$lang["Your login password does not match"] = "Login incorrect";
$lang["your verification password. Please re-enter."] = "Entrer à nouveau votre mot de passe.";
$lang["One or more fields were left blank or are too short."] = "Des champs sont vides ou trops courts.";
$lang["All fields must have at least 5 characters."] = "Tous les champs doivent avoir au moins 5 caractères.";
$lang["Your authentication data has been updated"] = "Vos identifiants ont été mis à jour"; // "!"
$lang["Manage Authenticated User Account"] = "Gestion des Comptes Membres";
$lang["Your Email Address"] = "Votre Adresse Email";
$lang["Login Username"] = "Login";
$lang["Login Password"] = "Mot de Passe";
$lang["Verify Password"] = "Mot de Passe à Nouveau";
$lang["Update Your Data"] = "Mettre à Jour";


// pgm-secure_remember.php
// ---------------------------------
$lang["Here is the username and password associated with your email address"] = "Voici les identifiants associés à votre adresse email";
$lang["Username"] = "Login";
$lang["Password"] = "Mot de Passe";
$lang["This is an automated email from"] = "Ceci est message automatique envoyé par notre serveur"; // [server name]
$lang["Please DO NOT REPLY to this email."] = "Veuillez NE PAS REPONDRE à ce message.";
$lang["Customer data successfully located."] = "Sauvegarde effectuée.";
$lang["You should receive an email within the next few minutes."] = "Vous recevrez un email dans quelques instants.";
$lang["Failed to locate email address; please try again."] = "Adresse email incorrecte; veuillez recommencer.";
$lang["Forgotten Login"] = "Identifiants Oubliés ?";
$lang["Please <u>enter your email address</u> in the space below."] = "Veuillez <u>entrer votre adresse email</u> dans le formulaire ci-dessous.";
$lang["We will locate your username and password in our database and instantly send an email to"] = "Nous vous enverrons dans un instant votre login et votre mot de passe à";
$lang["the address that matches your input."] = "l'adresse correspondant à votre saisie.";
$lang["Find Now"] = "Envoyer";


// pgm-add_cart.php
// ---------------------------------
$lang["Please fill out the following information needed for this individual item"] = "Veuillez remplir le formulaire suivant pour cet article";
$lang["Item"] = "Article";
$lang["Details"] = "Détails";
$lang["Details"] = "Détails";
$lang["Please fill out the following information regarding this product"] = "Veuillez fournir les informations en rapport avec le produit";
$lang["Continue"] = "Continuer";
$lang["ILLEGAL PRODUCT ADDITION DETECTED."] = "PRODUIT ILLEGAL DETECTE.";
$lang["UPDATED"] = "MIS A JOUR";
$lang["Current Shopping Cart Contents"] = "Contenu du Panier";
$lang["Shipping Information"] = "Adresse d'Expédition";
$lang["Returns & Exchanges"] = "Retours et Echanges";
$lang["Privacy Policy"] = "CGV";
$lang["Other Policies"] = "Autres Conditions";

$lang["Sub-total does not include tax"] = "Sous-Total sans TVA"; // <br>
$lang["and shipping charges, if applicable."] = "et Frais de Port, si applicable.";


$lang["Sub-Total"] = "Sous-Total";
$lang["Your shopping cart is currently empty."] = "Votre Panier est Vide pour l'instant.";
$lang["We also recommend the following product(s)"] = "Nous vous recommandons les produit(s) suivant(s)";


// pgm-checkout.php
// ---------------------------------
$lang["Customer Sign-in"] = "S'Identifier";
$lang["Email"] = "Email";

$lang["Billing & Shipping"] = "Facturation & Expédition"; // <br>
$lang["Information"] = "Information";

$lang["Shipping Options"] = "Options Expédition";
$lang["Verify Order Details"] = "Verifier Détails Commande";
$lang["Make Payment"] = "Payer";

$lang["Print Final"] = "Imprimer"; // <br>
$lang["Invoice"] = "Facture";


$lang["CUSTOMER SIGN-IN"] = "FORMULAIRE D'IDENTIFICATION CLIENT";
$lang["Select an option below so that we can recognize you."] = "Choisir une des options ci-dessous afin que nous puissions vous identifier.";
$lang["Shipping Information"] = "Adresse d'Expédition";
$lang["Returns & Exchanges"] = "Retours & Echanges";
$lang["Privacy Policy"] = "CGV";
$lang["Other Policies"] = "Autres Conditions";
$lang["New Customer"] = "Nouveau Client";
$lang["If you are a first time buyer select this option."] = "Si c'est votre 1er achat, sélectionnez cette option.";
$lang["You will have the opportunity to register and become a prefered customer."] = "Vous aurez l'opportunité de vous inscrire et devenir client privilégié.";
$lang["New Customer"] = "Nouveau Client";
$lang["Existing Customers, Login Now"] = "Déjà Client, Identifiez-vous";
$lang["Username"] = "Identifiant";
$lang["Unrecognized Customer"] = "Client Inconnu !";
$lang["Try Again"] = "Recommencez";
$lang["Verify Order"] = "Vérifier Commande"; //<br>
$lang["Details"] = "Détails";
$lang["STEP"] = "ETAPE";
$lang["BILLING AND SHIPPING INFORMATION"] = "INFORMATIONS PERSONNELLES";
$lang["Please fill out all fields"] = "Tous les champs sont obligatoires";
$lang["You will have a chance to verify and correct this information if necessary."] = "Vous pourrez ultérieurement vérifier et corriger ces informations.";
$lang["Customer Sign-in"] = "S'Identifier";
$lang["Please double check that all information is correct."] = "Veuillez vérifier que toutes informations fournies sont correctes.";
$lang["SELECT YOUR METHOD OF PAYMENT"] = "MOYEN DE PAIEMENT";
$lang["Choose your method of payment by clicking on the desired button."] = "Cliquez sur le bouton devotre moyen de paiement.";
$lang["Currently we are only accepting Check or Money Order payments."] = "Nous n'acceptons que les paiements par Chèques ou Mandats.";
$lang["We currently accept the following credit cards"] = "Ci-dessous les cartes bancaires acceptées";
$lang["Mailing Address"] = "Adresse Postale";


// pgm-checkout.php
// ---------------------------------
$lang["Thanks"] = "Merci"; // [user name]!
$lang["Your email has been sent"] = "Votre email a été envoyé";
$lang["A cool product I found..."] = "Un super produit que je viens de découvrir..."; // Default subject line of 'email product to friend' feature
$lang["Email Product"] = "Envoyer";
$lang["You have left one or more required fields blank"] = "Tous les champs sont obligatoires";
$lang["Please correct and re-submit your email"] = "Veuillez corriger et recommencer";
$lang["Required Fields"] = "Champs Obligatoires";
$lang["Your <u>Full</u> Name"] = "Votre <u>Full</u> Nom";
$lang["Your Email Address"] = "Votre Adresse Email";
$lang["Friend's <u>First</u> Name"] = "<u>Nom</u> de votre Ami(e)";
$lang["Friend's Email Address"] = "Adresse Email de Votre Ami(e)";
$lang["Subject Line of Email"] = "Sujet de votre Email";
$lang["Personal Message"] = "Votre Message Personnel";
$lang["Email Type"] = "Type Email";
$lang["Yes, send me a copy of the email too."] = "Oui, veuillez m'adresser une copie de cet email.";
$lang["Click Here to Return to"] = "Cliquez ici pour retourner à"; //[product name]
$lang["Return To Checkout Login"] = "Retourner à l'espace Paiement";
$lang["Failed to locate email address; please try again or login as a new customer."] = "Echec connexion; veuillez recommencer ou vous inscrire en tant que nouveau client.";
$lang["Follow the instructions below to resolve your issue quickly."] = "Suivre les instructions ci-dessous pour résoudre rapidement le problème.";
$lang["Find Username and Password for Login"] = "Demandez votre Identifiant et Mot de Passe pour vous connecter";
$lang["Your username and password was displayed on the invoice of your first order with us."] = "Votre Identifiant et votre Mot de Passe sont inscrits sur la facture de votre première commande.";
$lang["If you have the email or a printed copy handy, it may expedite your request."] = "Si vous avez votre email de bienvenue ou une copie imprimé de votre facture, vous y trouverez vos identifiants.";
$lang["Otherwise, please enter your email address in the space below."] = "Dans les cas, vous pouvez entrer votre adresse email dans le formulaire ci-dessous.";
$lang["Thank you for being a valued return customer."] = "Merci de fidélité.";
$lang["Find Now"] = "Envoyer";

$lang["We have received your request for a lost username and"] = "Nous avons reçu votre demande de réexpédition de votre Login et";
$lang["password and have located that information in our system."] = "de votre mot de passe que nous avons trouvés dans notre base.";
$lang["They are as follows"] = "Voici vos identifiants";
$lang["Thank you for being a loyal prefered customer."] = "Merci et à bient&ocirct.";
$lang["We look forward to continuing to serve you in the future."] = "Nous restons à votre entière disposition.";
$lang["This is an automated email from"] = "Ceci est message automatique de";
$lang["Please DO NOT REPLY to this email."] = "Veuillez NE PAS REPONDRE.";


// pgm-more_information.php
// ---------------------------------
$lang["Email To A Friend"] = "Envoyer à un(e) Ami(e)";

$lang["Add this product to your cart below"] = "Ajouter à mon panier";
$lang["under 'ordering options'."] = "en 'Options Complémentaires'.";

$lang["Product"] = "Produit";
$lang["Price"] = "Prix";
$lang["Qty"] = "Qté";
$lang["Add To Cart"] = "Ajouter à mon Panier";
$lang["Details specific to this item will be asked when you add this product to your cart."] = "Détails spécifiques à ce produit vous seront demandés lors de l'ajout à votre panier.";
$lang["More Information"] = "Plus d'Information";
$lang["Zoom"] = "Zoom";
$lang["Customer Comments"] = "Commentaires Clients";

$lang["Be the first to"] = "Soyez le premier à";
$lang["write a review"] = "écrire un commentaire";
$lang["of this product for other customers"] = "sur ce produit pour les futurs clients";

$lang["Write an online review"] = "Poster un commentaire";
$lang["and share your thoughts about this product with other customers."] = "et partager vos points de vue avec les autres clients.";
$lang["If you like this, you may also like"] = "Produit(s) similaire(s)";


// pgm-ok_comment.php
// ---------------------------------
$lang["This comment has already been added to the system or no longer exists."] = "Ce commentaire a déjà été ajouté ou n'existe plus.";
$lang["CUSTOMER COMMENT ADDED"] = "COMMENTAIRE ENVOYE";


// pgm-payment_gateway.php
// ---------------------------------
$lang["Customer Registration"] = "Inscription Client";

$lang["Thanks"] = "Merci";
$lang["you are now registered as a prefered customer"] = "Vous &ecirctes maintenant inscrit en tant que Client privilégié";

$lang["The next time you shop with us, you may login using your username and password for quicker checkout"] = "Lors de votre prochain achat sur notre boutique, vous vous connecterez avec votre login et votre mot de passe sans fournir à nouveau vos informations personnelles";
$lang["An error occurred when assigning your invoice number."] = "Une erreur est survenue lors de l'inscription de votre numéro de facture.";
$lang["Please try again or contact the webmaster immediately."] = "Veuillez recommencer ou contacter le webmaster immédiatement.";

$lang["The checkout system is configured to use a custom gateway include script named"] = "Le système de paiement utilise un script de passerelle personnalisé dénommé "; //[filename]
$lang["but the file can not be found on the server."] = "mais ce fichier ne peut &ecirctre trouvé sur ce serveur.";

$lang["Via 'Payment Options' in the system admin, make sure that you have a current include file selected and try again."] = "Via 'Options Paiement' dans l'espace Admin, vérifiez que vous avez sélectionné un fichier include et recommencez.";
$lang["Connecting To VeriSign"] = "Connection à VeriSign";
$lang["Secure Server"] = "Serveur Sécurisé";
$lang["Please Hold"] = "Veuillez Patienter";
$lang["If you are not connected automatically within 20 seconds"] = "Si dans 20 secondes vous n'&ecirctes pas automatiquement redirigé";
$lang["Click Here"] = "Cliquez ici";
$lang["Connecting To PayPal"] = "Connection à PayPal";
$lang["Secure Payment Server"] = "Paiement Par Serveur Sérisé";

$lang["The checkout system is configured to utilize online credit card processing, however, there is no VeriSign"] = "Le système est configuré pour accept&eacue les paiements par cartes bancaires, même quand on utilise pas";
$lang["information setup nor is there a"] = "le module de VeriSign ou lorsqu'un client";
$lang["custom gateway specified.  One of the other must be setup through 'Payment Options' to use the online credit card checkout system."] = "n'a pas spécifié de choix.  Tout autre module devra &ecirctre configuré à partir de 'Options Paiement' pour pouvoir &eecirctre utilisé par le système.";
$lang["If you do not know what these things mean, login to the admin system, select 'Payment Options' in the Shopping Cart module"] = "Si toute cette explication ne vous parle pas, connectez-vous à espace Admin, sélectionnez 'Options Paiement' du module e-Commerce";
$lang["and select 'Offline Processing' then save your settings."] = "et choisissez 'Paiement en Différé' puis sauvegardez.";
$lang["This should resolve your issue immediately."] = "Ceci va résoudre le problème.";

// pgm-show_invoice.php
// -----------------------------------
$lang["Make Check/Money Order Payable to"] = "Chèque/Mandat à l'ordre de";
$lang["Order Date"] = "Date Commande";
$lang["Order Number"] = "Numéro Commande";
$lang["Mailing Address"] = "Adresse Postale";
$lang["Print this Page Now"] = "Imprimer";
$lang["To download and save the file to your hard-drive, 'Right-Click' on Download Button and select 'Save Target As...'."] = "Pour télécharger et sauvegarder le fichier sur votre disque dur, 'Clic-Droit' sur Bouton Télécharger puis sélectionnez 'Enregistrer la Cible sous...'.";

$lang["When the save dialog appears, make sure you"] = "Lorsque le menu contextuel appara&icirctra, bien noter ou mémoriser";
$lang["remember where you save the file on your hard drive."] = "l'endroit où vous aurez sauvegardé le fichier sur votre disque dur.";

$lang["You will also receive an HTML email receipt of this invoice that contains this link as well in case"] = "Vous recevrez un email contenant ce m&ecircme lien de téléchargement";
$lang["you encounter connection problems downloading the file now."] = "pour le cas où vous rencontrerez des problèmes de connection lors du téléchargement.";
$lang["This order was just placed from your website."] = "Commande sur le Site.";
$lang["If you need to retrieve the credit card information, please login and do so now."] = "Si vous voulez relever les informations de la carte bancaire, veuillez vous connecter à votre espace Admin.";
$lang["CUSTOMER INVOICE COPY"] = "COPIE FACTURE CLIENT";


// pgm-write_review.php
// ---------------------------------
$lang["CLICK HERE"] = "CLIQUER ICI";
$lang["TO MAKE THIS POST LIVE."] = "POUR METTRE EN LIGNE.";
$lang["If you do not want to display this comment, simply delete this email"] = "Si vous ne souhaitez pas mettre ce commentaire en ligne, supprimer simplement cet email";

$lang["A customer has submitted the following comments about"] = "Commentaire soumis par un client concernant"; // <br>
$lang["the product"] = "le produit";


$lang["Your comment has been submitted."] = "Votre commentaire a été envoyé.";
$lang["Click Here to Return to"] = "Retour vers"; // [product name]

$lang["You have left one or more fields blank."] = "Tous les champs sont obligatoires.";
$lang["Please correct and re-submit your review."] = "Veuillez corriger et resoumettre.";
$lang["Star"] = "Etoile";
$lang["Stars"] = "Etoiles";
$lang["Rate this Product"] = "Noter ce Produit";
$lang["On a scale of 1-5, with 5 being the best"] = "Entre 1-5, 5 étant le meilleur";
$lang["Comment Title"] = "Titre Commentaire";
$lang["Your Review/Comments"] = "Texte";
$lang["Your Name"] = "Votre Nom";
$lang["Where are you in the world"] = "Quelque soit l'endroit où vous &ecirctes dans le monde";
$lang["our review will be submitted to our staff and should be posted within 2-3 business days."] = "votre commentaire sera soumis à administrateur qui validera sous 2-3 jours ouvrés.";
$lang["Thank you"] = "Merci";


// prod-billing_shipping.inc
// ---------------------------------

$lang["The state you selected to ship your order to does not appear to be valid."] = "L'Etat choisi pour l'expédition de votre commande semble ne pas exister.";
$lang["Please correct and re-submit your information."] = "Veuillez corriger re-soumettre l'information.";
$lang["The email address you provided is not a valid email address."] = "L'adresse email fournie n'est pas valide.";
$lang["Please correct and re-submit your information."] = "Veuillez corriger re-soumettre l'information.";
$lang["Customer Registration"] = "Inscription Client";
$lang["Yes, I want you to remember my Billing &amp; Shipping Information the next time I purchase something."] = "Oui, Je souhaite que vous conserviez mes Informations Personnelles pour mes prochaines commandes.";
$lang["Choose a password"] = "Mot de Passe";
$lang["Verify your password"] = "Mot de Passe à Nouveau";
$lang["The passwords that you entered do not match each other. Please check the spelling and re-submit."] = "Les 2 mots de passe entrés sont différents l'un de l'autre. Veuillez recommencer.";
$lang["You have elected to register as a customer but did not choose a password for your account. Please do so now."] = "Vous souhaitez vous inscrire en tant que membre mais vous n'avez pas fourni de mot de passe. Fa&icirctes-le.";
$lang["If you are not using the customer registration feature, you may leave the password fields blank"] = "Si vous n'&ecirctes pas sur la page d'inscription client, vous pouvez laisser le champ mot de passe vierge";
$lang["Billing Information"] = "Information Paiement";
$lang["First Name"] = "Prénom";
$lang["Last Name"] = "Nom";

$lang["Company Name"] = "Société";
$lang["Optional"] = "Optionnel";
$lang["Address"] = "Adresse";
$lang["No PO Boxes"] = "Pas de Bo&icircte Postale. Sauf Afrique";
$lang["City"] = "Ville";
$lang["Zip Code"] = "Code Postal";
$lang["State/Province"] = "Region (FR pour France)";
$lang["Country"] = "Pays";
$lang["Billing Phone Number"] = "Téléphone";
$lang["Email Address"] = "Adresse Email";
$lang["Used to send a copy of your invoice, and also serves as your username for future purchases."] = "Utiles pour vous envoyer une copie de votre facture et aussi pour servir de login pour vos prochains achats.";
$lang["to use Billing Information. Note, we do not ship to P.O. Boxes."] = "à utiliser pour la Facturation. Notez-le, nous n'expédions pas aux Bo&icirctes Postales (Sauf Afrique).";
$lang["Zip Code"] = "Code Postal";
$lang["Ship-To Phone Number"] = "Téléphone";


// pgm-cust_invoice.php
// ---------------------------------
$lang["Shipping & Handling"] = "Expédition & Manutention";
$lang["BILLING INFORMATION"] = "INFORMATION FACTURATION";
$lang["SHIPPING INFORMATION"] = "INFORMATION EXPEDITION";
$lang["Product Name"] = "Nom Produit";
$lang["Unit Price"] = "Prix Unitaire";
$lang["Quantity"] = "Quantité";
$lang["Sub-Total"] = "Sous-Total";
$lang["Tax"] = "TVA";
$lang["Total"] = "Total";
$lang["EDIT"] = "EDITER";


// prod_offline_card.inc
// ---------------------------------
$lang["The total amount of your purchase"] = "La somme totale de votre commande"; //[total]
$lang["will be charged to your credit card."] = "sera prélevée sur votre carte bancaire.";

$lang["Name as it appears on card"] = "Nom du propriétaire de la carte";
$lang["Credit Card Type"] = "Type de Carte";
$lang["Credit Card Number"] = "Numéro de la Carte";
$lang["Credit Card Expiration Date"] = "Date Expiration";
$lang["Month"] = "Mois";
$lang["Security Code"] = "Code Sécurité (les 3 derniers chiffres au dos de la carte)";
$lang["How to find your security code"] = "Comment conna&icirctre votre code sérité";


// prod_search_column.inc
// ---------------------------------
$lang["Welcome"] = "Bienvenue";
$lang["Client Login"] = "Espace Membres";
$lang["Find Now"] = "Rechercher";
$lang["Search Products"] = "Rechercher Produits";
$lang["Browse Categories"] = "Catégories";
$lang["Your cart is empty."] = "Votre panier est vide.";
$lang["VIEW OR EDIT CART"] = "VOIR OU EDITER PANIER";
$lang["Telephone Orders"] = "Téléphone Commandes";
$lang["We Accept"] = "Nous acceptons"; // (the following credit cards)

$lang["We are currently not accepting online orders."] = "Nous n'acceptons pas les commandes en ligne pour l'instant.";
$lang["We are currently only accepting check or money orders online."] = "Nous n'acceptons que les règlements par chèque ou mandat.";
$lang["Returns & Exchanges"] = "Retours et Echanges";
$lang["Privacy Policy"] = "CGV";
$lang["Other Policies"] = "Autres Conditions";


// prod_search_template.php
// ---------------------------------
$lang["Buy Now"] = "Acheter Maintenant";
$lang["Add to Cart"] = "Ajouter au Panier";
$lang["Related Products"] = "Produits Similaires";
$lang["Catalog"] = "Catalogue";
$lang["Browse Category"] = "Catégorie";



// start.php
// ---------------------------------
$lang["Search Results For"] = "Résultats de Recherche Pour";
$lang["Displaying"] = "Affichage";
$lang["Matches Found"] = "Résultats Trouvés"; // "[X] Matches Found"
$lang["Sorry, no products were found that match your search criteria."] = "Désolé, aucun produit ne correspond à critère de recherche.";
$lang["Please try again or browse the suggested selections below."] = "Veuillez recommencer ou sélectionner les catégories ci-dessous suggérées.";
$lang["NEXT"] = "SUIVANT";
$lang["Welcome to"] = "Bienvenue sur";
$lang["Mailing Address"] = "Adresse Postale";



#################################################
## WEBMASTER MENU             				     ##
#################################################

// webmaster.php
// ---------------------------------
$lang["USERNAME/PASSWORD NOT CHANGED"] = "IDENTIFIANT/MOT DE PASSE NON MODIFIE(S)";

$lang["Your username or password change"] = "La modification de votre identifiant ou mot de passe";
$lang["could not be verified. Please try again."] = "ne peut &ecirctre vérifiée. Veuillez recommencer.";

$lang["Your Administrative Username and Password has been changed"] = "Votre Identifiant et votre Mot de Passe ont été modifiés";
$lang["Administration Login"] = "Administration Login";
$lang["New Username"] = "Nouveau Login";
$lang["Verify New Username"] = "Nouveau Login une 2ème fois";
$lang["New Password"] = "Nouveau Mot de Passe";
$lang["Verify New Password"] = "Nouveau Mot de Passe une 2ème fois";
$lang["Change Username/Password"] = "Modifier";
$lang["Select User"] = "Selectionner Membre";
$lang["Multi-User Access"] = "Accès Membres";
$lang["Edit User"] = "Editer Membre";
$lang["Default Meta Tag Data"] = "Meta Tag Per Défaut";
$lang["Restart Quickstart Wizard"] = "Recommencer Configuration Rapide du Site";
$lang["Language"] = "Langue";
$lang["Swap Language"] = "Langue supplémentaire";
$lang["Access Rights"] = "Droits d'Accès";
$lang["Global Settings"] = "Configurations Globales du Site";
$lang["Meta Tag Data"] = "Meta Tag";
$lang["Miscellaneous Options"] = "Options Diverses";
$lang["Disable Developer Mode"] = "Interdire Mode Developpeur (code source)";
$lang["Enable Developer Mode"] = "Autoriser Mode Developpeur (code source)";


// global_settings.php
// ---------------------------------
$lang["Business Address"] = "Adresse de votre Société";
$lang["State"] = "Pays";
$lang["Postal Code"] = "Code Postal";
$lang["Apt. / Suite"] = "Suite Adresse";
$lang["Phone Number"] = "Téléphone";


// meta_data.php
// ---------------------------------
$lang["This will be displayed at the top of the browser window on all pages of your site."] = "Ceci sera affiché en haut du navigateur sur toutes les pages de votre site.";


$lang["Web Site Description"] = "Description du Site";
$lang["This is a Meta Tag that helps search engines classify your web site."] = "C'est pour aider les moteurs de recherche à mieux classér votre site.";
$lang["This should be a small sentance that describes your site."] = "Description courte, en une ou deux phrases, de votre site.";

$lang["Web Site Keywords"] = "Mots-Clés du Site";
$lang["This is a Meta Tag that some search engines use to search your site with."] = "Les Mots-Clés sont utilisés par les moteurs de recherche pour retrouver votre site.";
$lang["Please enter each keyword separated by a comma."] = "Veuillez séparer les mots-clés qui définissent votre site par une virgule.";
$lang["There is no need to use line feeds or carriage returns in the field."] = "Aucun besoin d'utiliser les retours à la ligne dans ce champs.";
$lang["Note: Individual Meta Tag Data can be edited from Page Properties while editing the page."] = "Note: Chaque page de votre site peut avoir ses propres Meta Tag à partir de 'Propriétés Page' de l'espace édition des pages.";
$lang["Save Meta Tag Data"] = "Sauvegarder";


// add_user.php
// ---------------------------------
$lang["has been added to your administrative users list."] = "a été ajouté à votre liste de membres."; // "[Full Name] has been added to your..."
$lang["Admin User's Full Name"] = "Nom de l'Administrateur";
$lang["Login Username"] = "Login";
$lang["Login Password"] = "Mot de Passe";
$lang["Select the seperate <U>Modules</U> that this user should have access to"] = "Sélectionner les différents <U>Modules</U> auxquels aura droit ce membre";
$lang["Enable Basic Features"] = "Autoriser les Modules de Base";
$lang["Enable Advanced Features"] = "Autoriser les Modules Avancés";
$lang["Select each <U>Site Page</U> this user should have access to"] = "Sélectionner les <U>Pages du Site</U> auxquels ce membre aura accès";
$lang["Note: User will not be able to access these pages unless the Edit Pages module itself is enabled (above)."] = "Note: Ce membre ne pourra avoir accès à ces pages tant que le Module Editeur de Pages n'est pas autorisé (voir plus haut).";
$lang["Shopping Cart access options"] = "Options Accès e-Commerce";
$lang["Note: User must have access to Shopping Cart module itself (above)."] = "Note: Ce membre doit avoir d'abord accès au module e-Commerce (voir plus haut).";
$lang["View Invoices Only"] = "View Invoices Only";
$lang["Select each <U>User Data Table</U> this user should have access to"] = "Sélectionner les <U>Tables de la Base de Données</U> auxquelles aura accès ce membre";
$lang["Cancel Create"] = "Recommencer";
$lang["Create New User"] = "Créer Membre";


// edit_user.php
// ---------------------------------
$lang["The settings for"] = "Les paramètres de";
$lang["have been updated."] = "ont été mis à jour.";

$lang["Edit Administrative User"] = "Editer Membre";
$lang["You have selected to delete the user"] = "Vous souhaitez supprimer le membre"; // [username]
$lang["Once you click OK, you can not undo this process."] = "Cette suppression sera irréversible.";
$lang["Are you sure you wish to delete this user"] = "Etes-vous s&ucircr de vouloir le supprimer ?"; // "?"
$lang["Cancel Edit"] = "Recommencer";
$lang["Delete User"] = "Supprimer";
$lang["Update User"] = "Mettre à Jour";


// Random Strings
// ---------------------------------
$lang["Backup/Restore"] = "Sauvegarder/Restaurer";
$lang["Secure Users Menu"] = "Menu Membres";
$lang["Site Backup / Restore"] = "Sauvegarder / Restaurer Site";
$lang["Install Software Updates"] = "Installer Mises à Jour des Modules";
$lang["Install Software Updates"] = "Installer Mises à Jour des Modules";
$lang["Check for software updates"] = "Vérifier s'il y a des Mises à Jour Disponibles";
$lang["Current Version"] = "Version Actuelle";
$lang["Release Date"] = "Date";
$lang["Changes in this build"] = "Modifications";
$lang["On-Menu Pages"] = "Pages Affichées";//////////////////////////////////////////////////////////////////////////////
$lang["Off-Menu Pages"] = "Pages non-Affichées";/////////////////////////////////////////////////////////////////////////////
$lang["Speed-Dial Pages Menu"] = "Pages Dialogue en Ligne";
$lang["Note: You may assign a single Site Base Template that applies to your entire website via the <a href=#LINK#>Template Manager</a> feature."] = "Vous ne pouvez assigner qu'un seul thème graphique à votre site via le <a href=#LINK#>Gestionnaire de thème</a> .";
$lang["To change the template for a specific page, edit the page, select page properties, and select the template from the drop down box."] = "Pour choisir un thème spécifique pour une page, éditez la page en question, sélectionnez 'Propriétés Page' et choisissez un thème dans le menu déroulant.";
$lang["Printable Page"] = "Imprimer";
$lang["Background"] = "Arrière-Plan";
$lang["Click on an object above and drag it onto a drop zone for page placement."] = "Cliquez sur un des objets ci-dessus et déplacez-le en maintenant le clic droit enfoné jusque dans la zone où l'on souhaite l'afficher";
$lang["Click on an object below and drag it onto a drop zone for page placement."] = "Cliquez sur un des objets ci-dessous et déplacez-le en maintenant le clic droit enfoné jusque dans la zone où l'on souhaite l'afficher";
$lang["Please only use Alpha Numerical characters and Underscores."] = "N'utiliser que des caractères alpha numériques et '_'";
$lang["Media, document, and code files may be downloaded by clicking on the arrow next to the filename."] = "Les Fichiers Audio, Vidéo, pdf, php et autres peuvent &ecirctre téléchargés en cliquant sur la fl&ecircche";
$lang["Image files can be viewed and saved by clicking the preview icon next to the filename."] = "Les Fichiers Image peuvent &ecirctre visualisés et sauvegardés en cliquant sur le bouton 'suivant' situé après le nom du fichier";
$lang["Indicates an image that should be reduced in filesize. This file causes slow load-times when viewing your web site."] = "";
$lang["Images"] = "Images";
$lang["Rename"] = "Renommer";
$lang["Documents, Presentations, and Adobe PDFs"] = "Documents, Présentations(PowerPoint), et PDF Adobe";
$lang["Video Files"] = "Fichiers Vidéos";
$lang["Spreadsheets and CSV files"] = "Fichiers CSV ou Excel";
$lang["Custom web forms and text files"] = "Fichiers Formulaire ou Texte Personnels";
$lang["Custom HTML includes"] = "Fichiers HTML personnels";
$lang["Custom HTML template files"] = "Fichiers de Thèmes Graphiques Personnels";
$lang["Custom PHP scripts"] = "Scripts PHP Personnels";
$lang["Unclassified files"] = "Fichiers Divers";
$lang["Select the <U>Browse</U> button next to each filename to locate your local file for upload. <BR>When you are ready to start the upload operation, select <U>Upload Files</U>."] = "Sélectionner le bouton <U>Parcourir</U> en face de chaque nom fichier pour récupérer le fichier qui est sur votre disque dur. <BR>Une fois le fichier trouvé, cliquez sur <U>Télécharger</U>.";
$lang["Upload Custom Template Folder (Zipped)"] = "Télécharger Fichier Personnel Zippé";
$lang["To upload a custom template"] = "Pour télécharger un thème graphique personnel";
$lang["Place all files(images,index.html,custom.css) into a folder and name the folder like this"] = "Placez tous les fichiers(images, index.html, style.css) dans un répertoire et nommez-le comme le fichier";
$lang["Category-Sub_Category-Color"] = "";
$lang["Example"] = "Exemple";
$lang["Zip the folder and upload it below"] = "Zipper le répertoire et le télécharger";
$lang["After upload the template will be availible in the list of templates"] = "Une fois téléchargée, le thème sera disponible dans la listes des thèsmes graphiques";
$lang["Zipped Template Folder"] = "Répertoire des thèsmes zippés";
$lang["What is your site visitor supposed to enter or select for this field"] = "Qu'est-ce qu'est supposé taper ou sélectionner votre visiteur dans ce champs";
$lang["In progress"] = "En progrès";
$lang["Complete"] = "Terminé";
$lang["No file selected!\nPlease choose a backup file from your hard drive."] = "Aucun fichier sélectionné!\nVeuillez choisir un fichier de sauvegarde sur votre disque dur";
$lang["Website backup in progress..."] = "Sauvegarde du Site en progrès...";
$lang["This process may take several moments."] = "Cela peut durer quelques minutes.";
$lang["Importing website backup file..."] = "Importer un fichier sauvegardé...";
$lang["This process may take several moments, depending on connection speed."] = "Cela peut durer quelques minutes, en fonction de votre bande passante.";
$lang["User notes for this backup"] = "Note de descriptive concernant ce fichier";
$lang["Site backup in progress. Please hold."] = "Sauvegarde en progrès. Veuillez patienter.";
$lang["Creating folder for this backup"] = "Répertoire en création pour ce fichier";
$lang["Writing backup info to text file"] = "Décrire dans un fichier texte la sauvegarde";
$lang["Archiving site content and files"] = "Archiver le contenu du site et les fichiers";
$lang["Creating data table restoration file"] = "Créer une restauration de la base de données";
$lang["Creating downloadable archive file"] = "Créer fichier archives téléchargeable";
$lang["Inserting backup record into site log"] = "Insérer la sauvegarde dans les logs du site";
$lang["Done"] = "Fait";
$lang["Restore from a previous backup"] = "Restaurer à partir d'une précédente sauvegarde";
$lang["Note: When downloading backups, make sure to save the file with a '.tgz' extension NOT '.gz'"] = "Note: Lorsque vous téléchargez des sauvegardes, enregistrez-les sous des extensions .gz ou tgz";
$lang["Note: After backing up your site, please download the backup and delete it here for security purposes."] = "Note Une fois votre site sauvegardé ettélécharg&é ici et supprimer-le par mesure de sécurité";
$lang["Backup Title"] = "Nom de la Sauvegarde";
$lang["Backup Date"] = "Date Sauvegarde";
$lang["Backup Time"] = "Heure Sauvegarde";
$lang["Are you sure you want to permanently delete this backup?"] = "Etes-vous s&ucircr de vouloir supprimer définitivement cette sauvegarde ?";
$lang["Current website will be replaced with backup data."] = "Votre site actuel sera remplacé par votre sauvegarde";
$lang["All unsaved data will be lost."] = "Tous les fichiers non enregistrés seront perdus.";
$lang["Are you sure you want to restore the backup?"] = "Etes-vous s&ucircr de vouloir restaurer votre sauvegarde ?";
$lang["Upload and import site backup file"] = "Télécharger et importer fichier site sauvegardé";
$lang["Select Backup File"] = "Sélectionner Fichier Sauvegarde";
$lang["Import Backup File"] = "Importer Fichier Sauvegarde";
$lang["Webmaster: Site Backup and Restoration"] = "Webmaster: Sauvegarde et Restauration du Site";
$lang["Description:"] = "Description";
$lang["Note: Thumbnail images should be no more than 99px wide."] = "Note: Les vignettes (petites images) ne doivent pas avoir de c&ocircté de plus 99 pixels";
$lang["Full Size Images should be no more than 275px wide for optimal display within your web site."] = "Les grandes images ne doivent pas avoir de c&ocircté de plus de 275 pixels pour un affichage de qualité sur votre site.";
$lang["When customers add this product to thier cart, require Form Data from:"] = "Lorsque les clients ajoutent ce produit dans leur panier, requerir le Formulaire à partir de:";
$lang["User-Defined Variable"] = "";
$lang["Denotes an event that is a 'Recurrence' of an original master event."] = "Dénote un évè 'recurrent' d'un évènement majeur";
$lang["Denotes the original 'Master' event within a recurring event cycle."] = "Dénote l'évènement 'Majeur' d'origine sans évènement ayant un cycle recurrent.";
$lang["Special Promotions"] = "Promotions Spéciales";
$lang["Step 1: Blog Title"] = "Etape 1: Titre du Blog";
$lang["Done!"] = "Fait";
$lang["Step 2: Enter Content For Blog"] = "Etape 2: Texte";
$lang["Launch Editor"] = "Afficher Editeur Texte";
$lang["Step 3: Post Blog to"] = "Etape 3: Poster vers";
$lang["Delete Entry"] = "Supprimer";
$lang["Edit Entry"] = "Editer";
$lang["Save Entry"] = "Sauvegarder";
$lang["show all"] = "Montrer Tous";


?>
