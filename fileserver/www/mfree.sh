#!/bin/sh 
/usr/bin/free -m | grep Mem |awk '{print $4}' 
/usr/bin/free -m | grep Mem |awk '{print $2}' 

