#!/bin/bash

echo "Enter username for MySQL admin account (not stored after installation exits): "
read ADMINUSER
echo "Enter password for MySQL admin account (not stored after installation exits):"
read ADMINPASS
echo ""
echo "Creating databases ..."
echo "Enter the name of the FARMDATA users database: "
read USERDB
echo "Enter the name of the users database user: "
read USERUSER
echo "Enter the password of the users database user: "
read USERPASS
echo "Enter the name of the FARMDATA farm information database: "
read FARMDB
FARMUSER=`tr -cd '[:alnum:]' < /dev/urandom | fold -w10 | head -n1`
FARMPASS=`tr -cd '[:alnum:]' < /dev/urandom | fold -w10 | head -n1`
   
while [[ ! -z "`mysql -u $ADMINUSER -p$ADMINPASS -qfsBe "select schema_name from information_schema.schemata where schema_name='$FARMDB'" 2>&1`" ]]; do
   echo "Database $FARMDB already exists."
   echo "Enter a different name for the FARMDATA farm information database: "
   read FARMDB
done
mysql -u $ADMINUSER -p$ADMINPASS -Bse "create database $FARMDB;" || { 
    echo "Database creation failed.  Exiting FARMDATA install!"; exit 1; }
mysql -u $ADMINUSER -p$ADMINPASS -Bse "create user $FARMUSER identified by '$FARMPASS';" || { 
    echo "User creation failed.  Exiting FARMDATA install!"; exit 1; }
mysql -u $ADMINUSER -p$ADMINPASS -Bse "use $FARMDB; 
       grant select, delete, insert, update, show view on $FARMDB.* to $FARMUSER;" || { 
    echo "Granting privileges to user failed.  Exiting FARMDATA install!"; exit 1; }
echo "Database creation successful!"

UU=$ADMINUSER
UP=$ADMINPASS
FU=$ADMINUSER
FP=$ADMINPASS

echo "Enter username for initial FARMDATA user account:";
read FIRSTUSER
while [[ ! -z "`mysql -u $ADMINUSER -p$ADMINPASS -qfsBe "use $USERDB; select username from users where username='$FIRSTUSER'" 2>&1`" ]]; do
   echo "Username: $FIRSTUSER is not available."
   echo "Please enter a different username for initial FARMDATA user account:";
   read FIRSTUSER
done
echo "Enter password for initial FARMDATA user account:";
read FIRSTPASS
FIRSTPASS=`php -r "print crypt('$FIRSTPASS', '123salt');"`
   
mysql -u $UU -p$UP -Bse "use $USERDB;
     insert into farms values('$FARMDB', '$FARMPASS', '$FARMUSER');
     insert into users values('$FIRSTUSER', '$FIRSTPASS', '$FARMDB', 1, 1);" || { 
     echo "Setting up user database failed.  Exiting FARMDATA install!"; exit 1; }

mysql -u $FU -p$FP -Bse "use $FARMDB; source tables/baseTables.txt;
      source tables/dfTables.txt;" || { 
      echo "Setting up farm database failed.  Exiting FARMDATA install!"; exit 1; }

echo "Database table creation successful!"
echo "Farm added successfully!"
