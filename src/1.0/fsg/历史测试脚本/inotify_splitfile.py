#!/usr/bin/python
# coding:utf-8
# author: xjl
import commands
import logging
import datetime
import sys

filename = sys.argv[1]

DU_status, DU_output = commands.getstatusoutput(
    "du -b '%s' | awk '{print $1}'  " % (filename))
if DU_status==0:
    if DU_output >= 104857600 :#大于100M为大文件
        print "大文件"
        SP_status, SP_output = commands.getstatusoutput(
            "split -b 20M '%s' '%s'_s  " % (filename,filename))
        if SP_status==0:
            print "拆分成功"
            # print SP_output

    else:
        print "小文件"
