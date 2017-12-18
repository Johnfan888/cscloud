#!/bin/sh

DIP=$1
DPASS=$2
DTYPE=$3
ZSIP=$4

INSTALL_DIR=/cscloud_install_source
ms_DESTDIR=/srv/www/htdocs
fs_DESTDIR=/srv/www/htdocs
zabbix_FILE=/etc/zabbix/zabbix-agentd.conf
zabbix_agentedserver=/etc/init.d/zabbix-agentd
stat=0
. $INSTALL_DIR/csc_funcs

# If interrupted, clean up
trap cleanup 2 3

echo "Installing $DIP ($DTYPE) ..."
if [ $DTYPE == "ms" ]; then
	DPATH=$ms_DESTDIR
else
	DPATH=$fs_DESTDIR
fi

exec_scp $INSTALL_DIR/cscloud_$DTYPE-1.0.tar.gz $DIP $DPASS $DPATH/ ||
{
	echo "remote copy package to $DIP failed" && exit 1
}
exec_ssh $DIP $DPASS "tar -zxvf $DPATH/cscloud_$DTYPE-1.0.tar.gz -C $DPATH/> /dev/null" ||
{
        echo "remote unpackage on $DIP failed" && exit 2
}
exec_ssh $DIP $DPASS "rm -f $DPATH/cscloud_$DTYPE-1.0.tar.gz" ||
{
	echo "remote remove package on $DIP failed" && exit 3
}

	
exec_ssh $DIP $DPASS "test -f /etc/init.d/zabbix-agentd  || rpm -ivh $DPATH/zabbix24-agent-2.4.3-2.1.i586.rpm > /dev/null && rm -rf $DPATH/zabbix24-agent-2.4.3-2.1.i586.rpm" ||
{
	echo "remote install zabbix-agentd failed" && exit 4
}


exec_ssh $DIP $DPASS "sed -i 's/Server=127.0.0.1/Server=$ZSIP/g' $zabbix_FILE"

exec_ssh $DIP $DPASS "sed -i 's/ServerActive=127.0.0.1/ServerActive=$ZSIP/g' $zabbix_FILE"

exec_ssh $DIP $DPASS "sed -i 's/# UnsafeUserParameters=0/UnsafeUserParameters=1/g' $zabbix_FILE"

exec_ssh $DIP $DPASS "sed -i '60a EnableRemoteCommands=1' $zabbix_FILE"

exec_ssh $DIP $DPASS "chkconfig --level 3,5 zabbix-agentd on" 

exec_ssh $DIP $DPASS "service zabbix-agentd start"

##add scripts for snmp minitoritem
if [ $DTYPE == "ms" ]; then
exec_ssh $DIP $DPASS "mkdir $ms_DESTDIR/configmanager/ms_minitoritem_scripts && chown wwwrun.www $ms_DESTDIR/configmanager/ms_minitoritem_scripts" ||
{
	echo "mkdir scripts failed" && exit 7
}
fi

echo "Install $DIP successfully."

exit 0

