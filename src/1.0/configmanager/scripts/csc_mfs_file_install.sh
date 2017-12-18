#!/bin/sh

INSTALL_DIR=/cscloud_install_source

. $INSTALL_DIR/csc_funcs

SFILE=$1
DIP=$2
DPASS=$3
DFILE=$4

# If interrupted, clean up
trap cleanup 2 3

echo "Copy $SFILE to $DIP ..."
exec_scp $SFILE $DIP $DPASS $DFILE ||
{
	echo "remote copy $SFILE to $DIP failed" && exit 1
}

echo "Copy $SFILE to $DIP successfully."

exit 0

