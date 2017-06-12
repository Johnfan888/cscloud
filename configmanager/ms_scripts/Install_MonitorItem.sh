#/bin/sh
DIP=$1
DPASS=$2
MIBVALUE=$3
MIBNAME=$4
EXECSHELLNAME=$5
SERVERIP=$6

INSTALL_DIR=/srv/www/htdocs/configmanager/scripts
SNMP_DIR=/etc/snmp
MINITOR_DIR=/srv/www/htdocs/configmanager/ms_minitoritem_scripts

. $INSTALL_DIR/csc_funcs

exec_scp $MINITOR_DIR/$EXECSHELLNAME $DIP $DPASS $SNMP_DIR/ || {
	echo "remote copy minitoritem shell failed" && exit 1
}
exec_ssh $DIP $DPASS "sed -i 's/rocommunity public 127.0.0.1/rocommunity public $SERVERIP/g' $SNMP_DIR/snmpd.conf" 
#exec_ssh $DIP $DPASS "sed -i '20a exec $MIBVALUE $MIBNAME /bin/sh $SNMP_DIR/$EXECSHELLNAME' $SNMP_DIR/snmpd.conf"
exec_ssh $DIP $DPASS  "echo 'exec $MIBVALUE $MIBNAME /bin/sh $SNMP_DIR/$EXECSHELLNAME' >> $SNMP_DIR/snmpd.conf"
exec_ssh $DIP $DPASS "/etc/init.d/snmpd restart"

