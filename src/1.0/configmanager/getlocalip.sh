#!/bin/sh

file=`find /etc/sysconfig/network-scripts -name ifcfg-ens*`
adip=`grep ^IPADDR $file | awk -F '=' '{print $2}'`

echo "$adip"

exit 0

