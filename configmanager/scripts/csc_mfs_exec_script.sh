#!/bin/sh

DIP=$1
DPASS=$2
DTYPE=$3
FNAME=$4

INSTALL_DIR=/cscloud_install_source
ms_DESTDIR=/srv/www/htdocs
fs_DESTDIR=/srv/www/htdocs

. $INSTALL_DIR/csc_funcs

# If interrupted, clean up
trap cleanup 2 3

if [ $DTYPE == "ms" ]; then
	exec_ssh $DIP $DPASS "php $ms_DESTDIR/$FNAME" ||
	{
		echo "remote execute $FNAME on $DIP failed" && exit 4
	}
else
	exec_ssh $DIP $DPASS "php $fs_DESTDIR/www/$FNAME" ||
	{
		echo "remote execute $FNAME on $DIP failed" && exit 5
	}
fi

echo "Execute script $FNAME on $DIP successfully."

exit 0

