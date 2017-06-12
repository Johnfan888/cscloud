#!/bin/sh
/bin/df -m |grep /data|awk '{print $3}'
/bin/df -m |grep /data|awk '{print $4}'
