#!/bin/sh

DISTRO_TARFILE=cscloud-1.0.tar.gz
INSTALL_DIR=/cscloud_install_source
AD_DESTDIR=/srv/www/htdocs/configmanager
WWW_DIR=/srv/www/htdocs
MS_TARFILE=cscloud_ms-1.0.tar.gz
FS_TARFILE=cscloud_fs-1.0.tar.gz

# Get default web user and group for different OS
if [ $1 == "rh" ]; then
	defaultWebUser=apache
	defaultWebGroup=apache
elif [ $1 == "suse" ]; then
	defaultWebUser=wwwrun
	defaultWebGroup=www
else
	echo "Please give the os type (rh/suse)"
	exit 1
fi
defaultWebUseGroup=$defaultWebUser.$defaultWebGroup

cleanup()
{
	echo "cleaning up ..."
	rm -rf $INSTALL_DIR
	rm -rf $AD_DESTDIR
	exit 20
}

# If interrupted, clean up
trap cleanup 2 3

# Check user
id | grep "uid=0(" > /dev/null
if [ $? != "0" ]; then
	echo "ERROR: This script requires root to run."
	exit 1
fi

echo "Installing admin node ..."
mkdir -p $INSTALL_DIR
tar -p -zxvf $DISTRO_TARFILE -C $INSTALL_DIR > /dev/null
if [ $? != "0" ]; then
	echo "ERROR: Unpackaging failed."
	cleanup
fi
cd $WWW_DIR
chown $defaultWebUseGroup * -R

cd $INSTALL_DIR
chown $defaultWebUseGroup * -R
mv cscloud/src/1.0/configmanager/scripts/csc_funcs .
mv cscloud/src/1.0/configmanager/scripts/csc_mfs_tar_install.sh .
mv cscloud/src/1.0/configmanager/scripts/csc_mfs_file_install.sh .
mv cscloud/src/1.0/configmanager/scripts/csc_mfs_exec_script.sh .
mkdir -p $AD_DESTDIR
chown $defaultWebUseGroup $AD_DESTDIR -R
cp -dpR cscloud/src/1.0/configmanager/* $AD_DESTDIR/
if [ $? != "0" ]; then
	echo "ERROR: Copy files to ad failed."
	cleanup
fi
mysql -uroot -p111111 < $AD_DESTDIR/database.sql
if [ $? != "0" ]; then
	echo "ERROR: Initialize database on ad failed."
	cleanup
fi

echo "Preparing packages for installation of other nodes ..."
cd cscloud/src/1.0/managerserver/
tar -p -zcvf $INSTALL_DIR/$MS_TARFILE * > /dev/null
if [ $? != "0" ]; then
	echo "ERROR: Packaging failed (MS)."
	cleanup
fi
cd ../fileserver/
tar -p -zcvf $INSTALL_DIR/$FS_TARFILE * > /dev/null
if [ $? != "0" ]; then
	echo "ERROR: Packaging failed (FS)."
	cleanup
fi
cd $INSTALL_DIR

# For single NIC

adip=`ip addr | grep "inet .* brd" | awk '{print $2}' | awk -F/ '{print $1}'`
#file=`find /etc/sysconfig/network-scripts -name ifcfg-ens*`
#adip=`grep ^IPADDR $file | awk -F '=' '{print $2}'`

echo "Successfully, please enter admin website with $adip"

exit 0

