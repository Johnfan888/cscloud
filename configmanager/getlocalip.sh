#!/bin/sh

file=`find /etc/sysconfig/network -name ifcfg-eth*`
adip=`grep ^IPADDR $file | awk -F '=' '{print $2}'`

echo "$adip"

exit 0

