#!/bin/bash

echo "Welcome to the FARMDATA installation script.  Press ^C (Control-C)"
echo "to exit the installation at any point."
echo ""

echo "FARMDATA is licensed under the Gnu Public License, version 3.0.  A"
echo "copy of this license is available in file gpl-3.0.txt in this directory."
echo "Do you agree to the terms of the license agreement?  (y/n)"
read AGREE
if [ $AGREE = Y ]; then
  AGREE=y
fi

if [ $AGREE != y ]; then
   echo "Exiting FARMDATA installation."
   exit 0
fi


echo "Does your web server provide Secure Sockets Layer (SSL) access via an"
echo "SSL certificate? (y/n)"
read SSL
if [ $SSL = Y ]; then
  SSL=y
fi

UPGRADE=n
if [ -f config ]; then
   echo "Existing FARMDATA installation detected!"
   txt=$(< config)
   ARR=(${txt//:/ })
   USERDB="${ARR[0]}"
   USERUSER="${ARR[1]}"
   USERPASS="${ARR[2]}"
   FARMDB="${ARR[3]}"
   FDIR="${ARR[4]}"
   DOMAIN="${ARR[5]}"
   FULLPATH="${ARR[6]}"
   if [ -z $FULLPATH ]; then
      echo "Config file corrupted - please continue with new installation or"
      echo "press ^C to terminate installation."
   else 
      echo "FARMDATA URL: "$DOMAIN/$FDIR
      echo "Installation directory: "$FULLPATH
      echo ""
      echo "Do you wish to upgrade the existing installation?"
      echo "(Enter y to upgrade, n to proceed with new installation)."
      read UPGRADE
      if [ $UPGRADE = Y ]; then
        UPGRADE=y
      fi
      if [ $UPGRADE = y ]; then
         echo "Proceeding with FARMDATA upgrade."
      else
         echo "Proceeding with new FARMDATA installation."
         UPGRADE=n
      fi
   fi
fi

if [ $UPGRADE = n ]; then
   FIN=0
   while [ $FIN -eq 0 ]; do
     echo "Enter your domain name (i.e. the hostname of your web server):"
     read DOMAIN
     if [[ $DOMAIN == *\ * ]]; then
       echo "ERROR: domain name must not contain spaces."
     else 
       FIN=1
     fi
   done

   FIN=0
   while [ $FIN -eq 0 ]; do
     echo "Enter the full path to the document root directory for your web server:"
     read DRPATH
     if [[ $DRPATH != /* ]]; then
       echo "ERROR: full path name must start with /"
     elif [[ $DRPATH == *\ * ]]; then
       echo "ERROR: path name must not contain spaces."
     else 
       FIN=1
     fi
   done

   if [[ $DRPATH != */ ]]; then
      DRPATH=$DRPATH/
   fi

   FIN=0
   while [ $FIN -eq 0 ]; do
     echo "Enter the subdirectory of $DRPATH in which to install FARMDATA"
     echo "(hit Return to install directly in $DRPATH):"
     read FDIR
     if [[ $FDIR == /* ]]; then
       echo "ERROR: subdirectory name must not start with /"
     elif [[ $FDIR == *\ * ]]; then
       echo "ERROR: subdirectory name must not contain spaces."
     else 
       FIN=1
     fi
   done

   FULLPATH=$DRPATH$FDIR
   if [[ $FULLPATH != */ ]]; then
      FULLPATH=$FULLPATH/
   fi
fi

if [ -d $FULLPATH ]; then
  echo "Installing in existing directory: " $FULLPATH
  tmp="tmp"
  touch $FULLPATH$tmp || { echo "Unable to write to directory "$FULLPATH "- ";
                           echo "exiting install!"; exit 1; }
  rm $FULLPATH$tmp
else 
  echo "Creating directory: " $FULLPATH
  mkdir -p $FULLPATH || { echo "Directory creation failed - exiting install!"; 
                          exit 1; }
fi

S=""
if [ $SSL == y ]; then
  S=s
fi

echo "The URL for your FARMDATA installation is: http$S://$DOMAIN/$FDIR"

if [ $UPGRADE = n ]; then
# :<<'QWERTY'
   echo "If you wish to create the necessary MySQL databases yourself, please do"
   echo "so before continuing with FARMDATA installation."
   echo ""
   echo "Do you want the installation procedure to create the MySQL databases"
   echo "for FARMDATA?  (y/n)"
   read CREATE
   if [[ $CREATE == y || $CREATE == Y ]]; then
     CREATE=y
     echo "Enter username for MySQL admin account (not stored after installation exits): "
     read ADMINUSER
     echo "Enter password for MySQL admin account (not stored after installation exits):"
     read ADMINPASS
     echo ""
     echo "Creating databases ..."
     USERDB=users
     USERUSER=`tr -cd '[:alnum:]' < /dev/urandom | fold -w10 | head -n1`
     # USERUSER=usercheck
     USERPASS=`tr -cd '[:alnum:]' < /dev/urandom | fold -w10 | head -n1`
     FARMDB=`tr -cd '[:alnum:]' < /dev/urandom | fold -w10 | head -n1`
     FARMUSER=`tr -cd '[:alnum:]' < /dev/urandom | fold -w10 | head -n1`
     FARMPASS=`tr -cd '[:alnum:]' < /dev/urandom | fold -w10 | head -n1`
   
     mysql -u $ADMINUSER -p$ADMINPASS -Bse "create database $USERDB;" || { 
         echo "Database creation failed.  Exiting FARMDATA install!"; exit 1; }
     mysql -u $ADMINUSER -p$ADMINPASS -Bse "create user $USERUSER identified by '$USERPASS';" || { 
         echo "User creation failed.  Exiting FARMDATA install!"; exit 1; }
     mysql -u $ADMINUSER -p$ADMINPASS -Bse "use $USERDB; 
            grant select, insert, update on $USERDB.* to $USERUSER;" || { 
         echo "Granting privileges to user failed.  Exiting FARMDATA install!"; exit 1; }
     mysql -u $ADMINUSER -p$ADMINPASS -Bse "create database $FARMDB;" || { 
         echo "Database creation failed.  Exiting FARMDATA install!"; exit 1; }
     mysql -u $ADMINUSER -p$ADMINPASS -Bse "create user $FARMUSER identified by '$FARMPASS';" || { 
         echo "User creation failed.  Exiting FARMDATA install!"; exit 1; }
     mysql -u $ADMINUSER -p$ADMINPASS -Bse "use $FARMDB; 
            grant select, delete, insert, update, show view on $FARMDB.* to $FARMUSER;" || { 
         echo "Granting privileges to user failed.  Exiting FARMDATA install!"; exit 1; }
     UU=$ADMINUSER
     UP=$ADMINPASS
     FU=$ADMINUSER
     FP=$ADMINPASS
     echo "Database creation successful!"
   else 
     echo "Enter the name of the FARMDATA users database: "
     read USERDB
     echo "Enter the name of the users database user: "
     read USERUSER
     echo "Enter the password of the users database user: "
     read USERPASS
     echo "Enter the name of the FARMDATA farm information database: "
     read FARMDB
     echo "Enter the name of the farm information database user: "
     read FARMUSER
     echo "Enter the password of the users database user: "
     read FARMPASS
     UU=$USERUSER
     UP=$USERPASS
     FU=$FARMUSER
     FP=$FARMPASS
   fi
   echo "Enter username for initial FARMDATA user account:";
   read FIRSTUSER
   echo "Enter password for initial FARMDATA user account:";
   read FIRSTPASS
   FIRSTPASS=`php -r "print crypt('$FIRSTPASS', '123salt');"`
   
   mysql -u $UU -p$UP -Bse "use $USERDB; source tables/userTables.txt;
        insert into farms values('$FARMDB', '$FARMPASS', '$FARMUSER');
        insert into users values('$FIRSTUSER', '$FIRSTPASS', '$FARMDB', 1, 1);" || { 
         echo "Setting up user database failed.  Exiting FARMDATA install!"; exit 1; }

   mysql -u $FU -p$FP -Bse "use $FARMDB; source tables/baseTables.txt;
         source tables/dfTables.txt;" || { 
         echo "Setting up farm database failed.  Exiting FARMDATA install!"; exit 1; }
   
   echo "Database table creation successful!"
fi

#QWERTY

# temporary -delete after testing
#USERDB=wahlst_users
#USERUSER=wahlst_usercheck
#USERPASS=usercheckpass


echo "Configuring files - this will take a few moments."

for file in `find src -name '.svn'`; do
   rm -rf $file
done

for file in `find src -name '*.php'`; do
  sed -i "s/wahlst_users/$USERDB/" $file || { echo "Error configuring files.  Exiting FARMDATA install"; 
          exit 1; }
  sed -i "s/wahlst_usercheck/$USERUSER/" $file || { echo "Error configuring files.  Exiting FARMDATA install";
          exit 1; }
  sed -i "s/usercheckpass/$USERPASS/" $file || { echo "Error configuring files.  Exiting FARMDATA install";
          exit 1; }
done

if [[ $SSL != y ]]; then
   for file in "src/extlogin.php" "src/design.php"; do
      sed -i "s%// HTTPSON%/*%" $file || { echo "Error configuring files.  Exiting FARMDATA install";
           exit 1; }
      sed -i "s%// HTTPSOFF%*/%" $file || { echo "Error configuring files.  Exiting FARMDATA install";
           exit 1; }
   done
fi

if [[ $FDIR != "" ]]; then

for file in `find src -name '*.php'`; do
  sed -i "s%\$_SERVER\['DOCUMENT_ROOT'\]\.'/%'$FULLPATH%" $file || { echo "Error configuring files.  Exiting FARMDATA install";
          exit 1; }
  sed -i "s%/down\.php%/$FDIR/down.php%" $file || { echo "Error configuring files.  Exiting FARMDATA install";
          exit 1; }
  sed -i "s%/pure-release%/$FDIR/pure-release%" $file || { echo "Error configuring files.  Exiting FARMDATA install";
          exit 1; }
done

for file in "src/extlogin.php" "src/design.php" "src/logout.php" "src/admintab.php" "src/hartab.php" "src/labortab.php" "src/notetab.php" "src/seedtab.php" "src/soiltab.php" "src/setup/setup.php" "src/setup/extlogin.php" "src/connection.php"; do
   sed -i "s%/tabs\.css%/$FDIR/tabs.css%" $file || { echo "Error configuring files.  Exiting FARMDATA install";
        exit 1; }
   sed -i "s%/mobileTable\.css%/$FDIR/mobileTable.css%" $file || { echo "Error configuring files.  Exiting FARMDATA install";
        exit 1; }
   sed -i "s%/mobileTabs\.css%/$FDIR/mobileTabs.css%" $file || { echo "Error configuring files.  Exiting FARMDATA install";
        exit 1; }
   sed -i "s%/mobileDesign2\.css%/$FDIR/mobileDesign2.css%" $file || { echo "Error configuring files.  Exiting FARMDATA install";
        exit 1; }
   sed -i "s%/tableDesign\.css%/$FDIR/tableDesign.css%" $file || { echo "Error configuring files.  Exiting FARMDATA install";
        exit 1; }
   sed -i "s%/design\.css%/$FDIR/design.css%" $file || { echo "Error configuring files.  Exiting FARMDATA install";
        exit 1; }
   sed -i "s%/design\.php%/$FDIR/design.php%" $file || { echo "Error configuring files.  Exiting FARMDATA install";
        exit 1; }
   sed -i "s%/logout\.php%/$FDIR/logout.php%" $file || { echo "Error configuring files.  Exiting FARMDATA install";
        exit 1; }
done

for file in "src/header.php" "src/logout.php" "src/connection.php"; do
   sed -i "s%\$_SERVER\['HTTP_HOST'\]\.\"%\$_SERVER\['HTTP_HOST'\]\.\"/$FDIR%" $file || { echo "Error configuring files.  Exiting FARMDATA install";
        exit 1; }
done

sed -i "s%updateCrop\.php%$FDIR/updateCrop.php%" src/chooseCrop.php || { echo "Error configuring files.  Exiting FARMDATA install";
        exit 1; }
sed -i "s%/wfb\.php%/$FDIR/wfb.php%" src/admintab.php || { echo "Error configuring files.  Exiting FARMDATA install";
        exit 1; }

for file in "src/Admin/Backtracker/backtracker.php" "src/Admin/adminHarvest/harvestListAdmin.php" "src/Labor/laborReport.php" "src/hartab.php" "src/Labor/labor.php" "src/Admin/Delete/laborReport.php" "src/Admin/Delete/harvestEdit.php"; do
   sed -i "s%/Harvest/%/$FDIR/Harvest/%" $file || { echo "Error configuring files.  Exiting FARMDATA install";
        exit 1; }
done

for file in "src/Admin/Delete/coverEdit.php" "src/Admin/Delete/bsprayReport.php" "src/soiltab.php" ; do
   sed -i "s%/Soil/%/$FDIR/Soil/%" $file || { echo "Error configuring files.  Exiting FARMDATA install";
        exit 1; }
done

for file in "src/seedtab.php" "src/Admin/Delete/transEdit.php" "src/Admin/Backtracker/backtracker.php"; do
   sed -i "s%/Seeding/%/$FDIR/Seeding/%" $file || { echo "Error configuring files.  Exiting FARMDATA install";
        exit 1; }
done


sed -i "s%/Notes/%/$FDIR/Notes/%" src/notetab.php || { echo "Error configuring files.  Exiting FARMDATA install";
        exit 1; }
sed -i "s%/Admin/%/$FDIR/Admin/%" src/admintab.php || { echo "Error configuring files.  Exiting FARMDATA install";
        exit 1; }
sed -i "s%/Labor/%/$FDIR/Labor/%" src/labortab.php || { echo "Error configuring files.  Exiting FARMDATA install";
        exit 1; }

sed -i "s%/HomeMobile%/$FDIR/HomeMobile%" src/header.php || { echo "Error configuring files.  Exiting FARMDATA install";
        exit 1; }
sed -i "s%/BackArrow%/$FDIR/BackArrow%" src/header.php || { echo "Error configuring files.  Exiting FARMDATA install";
        exit 1; }
sed -i "s%/Logout%/$FDIR/Logout%" src/header.php || { echo "Error configuring files.  Exiting FARMDATA install";
        exit 1; }

for file in "src/Admin/Config/config.php"; do
   sed -i "s%exthome%$FDIR/exthome%" $file || { echo "Error configuring files.  Exiting FARMDATA install";
        exit 1; }
done

fi

for file in "src/index.html" "src/default.html" "src/redirect.html"; do
   sed -i "s%url=login\.php%url=extlogin.php%" $file || { echo "Error configuring files.  Exiting FARMDATA install";
        exit 1; }
done

rm -f src/login.php
rm -f src/guest.php
rm -rf src/files/*


# echo "Adjusting file permissions"
./adjustperms.sh -t
echo "Moving files to installation directory"
cp -r src/* $FULLPATH

chmod 777 $FULLPATH/files/
mkdir -p $FULLPATH/files/$FARMDB
chmod 777 $FULLPATH/files/$FARMDB

rm -rf src/*

if [ $UPGRADE = n ]; then
   echo "Successful FARMDATA installation!"
   echo "Please log in to: http$S://$DOMAIN/$FDIR/setup"
   echo "to complete FARMDATA setup."

   echo $USERDB:$USERUSER:$USERPASS:$FARMDB:$FDIR:$DOMAIN:$FULLPATH > config

   echo ""
   echo "Please copy file: config"
   echo "to a safe location. This file contains the information needed"
   echo "to upgrade your FARMDATA installation to a new version."
else 
   echo "Successful FARMDATA upgrade!"
fi
