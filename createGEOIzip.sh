export TMPDIR=/drives/c/xampp/htdocs/Joomla-GeoI/Component/
export JBASEDIR=/drives/c/xampp/htdocs/Joomla-GeoI/Joomla/
export DESDIR=/drives/c/xampp/htdocs/Joomla-GeoI/Component/
export date=$(date +%d%m%y%H%M)
#sudo chmod -R 777 $JBASEDIR
rm -rf $TMPDIR
mkdir $TMPDIR
mkdir $TMPDIR/admin
mkdir $TMPDIR/admin/language
mkdir $TMPDIR/site
mkdir $TMPDIR/language
mkdir $TMPDIR/media
#sudo chmod -R 755 $TMPDIR

cp $JBASEDIR/administrator/components/com_geoi/geoi.xml $TMPDIR/geoi.xml
cp $JBASEDIR/administrator/components/com_geoi/geoi.xml $TMPDIR/script.php

cd $JBASEDIR/components/com_geoi/
cp -R * $TMPDIR/site/

cd $JBASEDIR/administrator/components/com_geoi/
cp -R * $TMPDIR/admin/
rm -rf $TMPDIR/admin/uploads/*
cp $TMPDIR/site/index.html $TMPDIR/admin/uploads/index.html

cp $JBASEDIR/administrator/language/en-GB/en-GB.com_geoi.sys.ini $TMPDIR/admin/language/en-GB.com_geoi.sys.ini
cp $JBASEDIR/administrator/language/en-GB/en-GB.com_geoi.ini $TMPDIR/admin/language/en-GB.com_geoi.ini
cp $JBASEDIR/administrator/language/es-ES/es-ES.com_geoi.ini $TMPDIR/admin/language/es-ES.com_geoi.ini
cp $JBASEDIR/administrator/language/es-ES/es-ES.com_geoi.sys.ini $TMPDIR/admin/language/es-ES.com_geoi.sys.ini

cp $JBASEDIR/language/en-GB/en-GB.com_geoi.ini $TMPDIR/language/en-GB.com_geoi.ini
cp $JBASEDIR/administrator/language/en-GB/en-GB.com_geoi.sys.ini $TMPDIR/language/en-GB.com_geoi.sys.ini
cp $JBASEDIR/language/es-ES/es-ES.com_geoi.ini $TMPDIR/language/es-ES.com_geoi.ini
cp $JBASEDIR/administrator/language/es-ES/es-ES.com_geoi.sys.ini $TMPDIR/language/es-ES.com_geoi.sys.ini 


cd $JBASEDIR/media/com_geoi/
cp -R * $TMPDIR/media/

#chmod -R 755 $TMPDIR
#cd $TMPDIR
#zip -r GEOI_$date *
#cp GEOI_$date.zip $DESDIR/GEOI_$date.zip 
echo HECHO!

