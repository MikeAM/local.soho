################################################################
## Soholaunch(R) Pro Edition Site Builder
## Version 4.6
##      
## Author: 		Mike Johnston [mike.johnston@soholaunch.com]                 
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugzilla.soholaunch.com
## Release Notes:	http://forums.soholaunch.com
################################################################

################################################################
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
################################################################

Server Requirements:
-------------------------------------------------------------
1. mySQL database server (3.23 or Better)
2. PHP 4.0 or better
3. Linux/Apache Webserver OR Windows/IIS Webserver
-------------------------------------------------------------

#############################################################
### SOHOLAUNCH SMT 4.5 SETUP AND CONFIGURATION
#############################################################


1. Confirm that PHP and mySQL or installed in your server
   environment.  Without these installed and configured
   properly the Soholaunch SMT will not operate.
   

2. Create a mySQL database for your website to utilize. For
   Linux environments, Telnet or SSH into your server with 
   "root" access and create a mySQL database.  For Windows
   environments, start "mysql.exe".

   Assign any database name, username and password you wish 
   and remember this data. YOU MUST COMPLETE THIS STEP
   BEFORE CONTINUING! (See how to setup a mySQL database below)
   

3. Modify directory permissions for the "sohoadmin" directory:

   For Linux Machines: ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
      chmod -R a+rw sohoadmin
   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


   For Windows Machines: ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
      Right click on the Sohoadmin folder and select
      "properties".  When the dialog box appears select
      the "security" tab and set the permissions for this
      directory for "Everyone" to "Allow Full Control".
   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

   This allows the application full read/write access to this
   directory. Don't worry, all components of the SMT have
   its own built-in security.


4. Make sure that "index.php" is added to your server
   configuration as a default document. In most cases
   where your server is already configured to run PHP,
   this step will be done for you.

   For Apache Servers: ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
      Locate the "httpd.conf" file and open it using
      a text editor such as vi.  Modify the line that
      contains "DirectoryIndex".  It should be modified
      to resemble the following:

      DirectoyIndex   index.php index.html index.htm 
   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

   For IIS Servers: ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
      Open the Internet Services Manager and Select the
      host folder that is using the Soholaunch SMT.
      Right-Click on the folder and select "properties".
      When the dialog box appears, select the "Documents"
      option.  If "index.php" does not appear in the
      default documents box, Choose "Add" and type 
      "index.php".
   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 

5. Via a Microsoft Internet Explorer Browser running on a
   Windows(TM) based PC, goto:

   http://[site_url]/sohoadmin/index.php
   
   This will prompt you to enter setup data regarding the
   database you created in step 1 and your server
   environment.  Most information should be self
   explanitory.  If you have any questions, consult the
   users guide and documentation located at 
   http://www.soholaunch.com.

   Troubleshooting Step 3: ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
   If you are having problems getting past this step,
   please consult the troubleshooting steps below first.
   If this doesn't solve your problem contact support at 
   support@soholaunch.com.
   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


6. When completed with step 5, you will be prompted with
   a login screen. Your initial username and password is:

   un: admin    
   pw: admin

   (This can be changed inside the tool under webmaster
    settings.  Usernames and Passwords are NOT case
    sensitive.)

7. Enter your license key information and you are done!
   To access the SMT in the future, got to the site address
   stated in step 5.  This will automatically bring you
   to the login screen from this point forward.

Thank you for using the Soholaunch SMT site builder and
management tool.


#############################################################
### OPERATIONAL NOTES
#############################################################

You must access the tool via Internet Explorer 5.1 or better
through a Windows(TM) based machine. It WILL NOT WORK in 
Netscape or Mozilla browsers OR from a Linux Desktop! All
sites created with the SMT work with all browsers 3.0 and
up; only the SMT interface requires IE.

Also, the system utilizes cookies and session ID's for
security so these features should be turned on for proper
use. (See the "System Requirements" link at the login
screen)


#############################################################
### HOW TO SETUP A mySQL DATABASE 
#############################################################

To create a database in mySQL, you must first access the
mySQL interface.

LINUX MACHINES:
-------------------------------------------------------------
Via TELNET or SSH with "root" access, type the following
at the command line:

#> mysql


WINDOWS MACHINES:
-------------------------------------------------------------
Locate the mysql.exe file and execute it.  This is generally 
installed by default in c:\mysql\bin\mysql.exe.  This will 
open a "DOS" type window running the mySQL interface. This
is the same interface used within Linux as well.


CREATE THE DATABSE:
-------------------------------------------------------------

mysql-> CREATE DATABASE [database_name];

mysql-> GRANT ALL PRIVILEGES ON *.* TO [username]@localhost
     -> IDENTIFIED BY '[password]' WITH GRANT OPTION; 


Database creation complete. Please note that the application 
will create and maintain its own table structures.


#############################################################
### Troubleshooting and Performance Tips
#############################################################

=============================================================
All I see is code when I try to run /sohoadmin/index.php?
=============================================================

This is an indication that PHP is not installed or currently
running in your server environment.  You will need to
contact your ISP or download and install PHP to your server
by going to http://www.php.net.  Follow the instructions
provided based on your operating system.  Only qualified
system adminstrators should perform this installation.


=============================================================
How to modify the PHP.INI file:
=============================================================

The PHP.INI file is the configuration file that controls how
the PHP language is used within your server environment. In
most cases you may or may not have access to this file for
modification.  If you do not have direct access to the file,
contact your ISP.  There is generally a sub-file that can
be modified to achieve the desired results needed.

The PHP.INI file is a simple text file than can be modified
from a text editor within Linux or Windows.

LINUX MACHINES: ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
The PHP.INI file is generally located in /etc/php.ini. You
can use the VI editor to edit if necessary.
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

WINDOWS MACHINES: ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
The PHP.INI file is generally located in /WindowsNT/php.ini.
You may wish to use the FIND feature to locate it.  You can
edit it in notepad or wordpad very easily.  Please note, that
in the Windows environment, PHP will automatically make a
backup of the original PHP.INI file in other locations. This
is important to have in case an error is made in the
modification.
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

=============================================================
What to modify within the PHP.INI File:
=============================================================

register_globals and short_open_tag is required to be set to
ON in order to run the SMT product.  If this is a problem,
you should be notified when running the first time setup.

In some instances, it has been necessary to modify other
variables within the PHP.INI for performance enhancements.
These generally only effect large traffic web sites. The 
following settings are recommended in this case:

     max_execution_time = 60;
     memory_limit = 20M;
     post_max_size = 20M;
     upload_max_filesize = 10M;

NOTE: These variables already have default settings within
the PHP.INI file.  You will want to find the occurrence of
these variables and modify them.  DO NOT JUST ADD THESE
TO THE FILE!

NOTE: Before changes will take effect, you must restart your
server.

=============================================================
Modifing mySQL settings:
=============================================================

The SMT takes advantage of persistant connections in order to
speed up database queries. Because of this, a server may start
experiencing connection errors with large traffic web sites
and/or numerous web sites on a single server environment.
This is easily fixed by modifing or adding the "my.cnf" file. You
can generally locate this file under /etc/my.cnf within the 
Linux environment or within the /WindowsNT folder for Windows
servers.  If the file does not already exists, you may create
the file.

Add the following lines:

     set-variable=max_connections=200
     set-variable=wait_timeout=2700

The max_connections settings tells mySQL how many concurrent
connections to the database to allow at one time and the 
wait_timeout variable tells how long to wait before closing
a persistant data connection.  By default, mySQL is set to wait
up to 8 hours before closing a connection.  This could maximize
your connections quickly with a multiple website server or
high traffic web site.

NOTE: Before changes will take effect, you must restart your
server.


=============================================================
SMTP Settings (Mail Send Issues)
=============================================================

For both Windows and Linux machines, you can modify the PHP.INI
file to reflect what SMTP server or email program to utilize.
In Linux machines, the default sendmail settings are generally
ok and shouldn't cause issues.  Windows server environments
generally require the SMTP Server to be set before proper
operation. Specifically if using another server as the SMTP
server (almost always the case in a Windows environment).

The SMT relies upon this setting to initialize and send all
email correspondance, including newsletters.  Make sure that
this is properly set if you are having any email or newsletter
sending issues.

This SMT product has been tested in numerous environments and
to date has only one known issue regarding HTML and normal text
email sends.  Some Macintosh email clients have trouble seeing
HTML email's generated from the Soholaunch SMT.  In most cases
these have been "custom HTML" newsletters as oppossed to
newsletter content created within the Soholaunch SMT.


*************************************************************
      (c)copyright 1999-2003 Soholaunch.com, Inc.
    All rights reserved. Please read and observe the
      license agreement included with this program.
*************************************************************
