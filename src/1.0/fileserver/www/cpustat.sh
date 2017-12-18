#!/bin/sh 
idle=`sar  -u 1 3 | grep Average | awk '{print $6}'` 
used=`echo "101 - $idle" | bc -l -s` 
echo $used 
echo $idle
