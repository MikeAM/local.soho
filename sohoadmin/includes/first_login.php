<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


#================================================================================
# DO THIS STUFF ON FIRST LOGIN
# This script is included the first time a user logs-in on a fresh install
# Originally: included by update_client.php if nowiz.txt does not exist
#================================================================================

# Check/Create system folders
include_once("includes/create_system_folders.inc.php");

# Check/Create system database tables
include_once("includes/create_system_tables.inc.php");

# Preserve existing index.html if found in docroot
if (file_exists("../index.html")) {
   rename("../index.html","../old_index.html");
}

# Try to make sure template folders are writeable
testWrite("sohoadmin/program/modules/site_templates/pages", true);

# Download/install factory template library(s) as specified in Branding Controls
include_once("download_templates.inc.php");


?>