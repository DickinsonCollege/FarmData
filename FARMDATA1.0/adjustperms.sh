#!/bin/bash
if [ "$1" == "-t" ]; then
  fileperm=644
  dirperm=755
echo "tightening permissions"
else
  fileperm=664
  dirperm=775
echo "loosen"
fi
cd src
eval chmod -f $fileperm *.php *.html *.css
eval chmod -f $dirperm Admin
cd setup
eval chmod -f $fileperm *.php *.html *.css
cd ..
cd Admin
eval chmod -f $fileperm *.php *.html *.css
eval chmod -f $dirperm Config
cd Config
eval chmod -f $fileperm *.php *.html *.css
cd ..
eval chmod -f $dirperm DBBackup 
cd DBBackup
eval chmod -f $fileperm *.php *.html *.css
cd ..
eval chmod -f $dirperm Backtracker 
cd Backtracker
eval chmod -f $fileperm *.php *.html *.css
cd ..
eval chmod -f $dirperm View
cd View
eval chmod -f $fileperm *.php *.html *.css
cd ..
eval chmod -f $dirperm Add
cd Add
eval chmod -f $fileperm *.php *.html *.css
cd ..
eval chmod -f $dirperm adminHarvest
cd adminHarvest
eval chmod -f $fileperm *.php *.html *.css
cd ..
eval chmod -f $dirperm Delete
cd Delete
eval chmod -f $fileperm *.php *.html *.css
cd ..
eval chmod -f $dirperm Sales
cd Sales
eval chmod -f $fileperm *.php *.html *.css
eval chmod -f $dirperm Packing
cd Packing 
eval chmod -f $fileperm *.php *.html *.css
cd ..
eval chmod -f $dirperm Inventory 
cd Inventory
eval chmod -f $fileperm *.php *.html *.css
cd ..
eval chmod -f $dirperm Distribution
cd Distribution
eval chmod -f $fileperm *.php *.html *.css
cd ..
eval chmod -f $dirperm Invoice
cd Invoice
eval chmod -f $fileperm *.php *.html *.css
cd ..
eval chmod -f $fileperm *.php *.html *.css
cd ../..
eval chmod -f $dirperm Harvest
cd Harvest
eval chmod -f $fileperm *.php *.html *.css
cd ..
eval chmod -f $dirperm Labor
cd Labor
eval chmod -f $fileperm *.php *.html *.css
cd ..
eval chmod -f $dirperm Notes
cd Notes
eval chmod -f $fileperm *.php *.html *.css
cd ..
eval chmod -f $dirperm Seeding
cd Seeding
eval chmod -f $fileperm *.php *.html *.css
eval chmod -f $dirperm Order
cd Order
eval chmod -f $fileperm *.php *.html *.css
cd ..
cd ..
eval chmod -f $dirperm Soil
cd Soil
eval chmod -f $fileperm *.php *.html *.css
eval chmod -f $dirperm Tspray
cd Tspray
eval chmod -f $fileperm *.php *.html *.css

