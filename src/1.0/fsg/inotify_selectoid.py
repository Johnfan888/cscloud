#!/usr/bin/python
# coding:utf-8
# author: xjl
# 选择oid
import sys
import logging
import datetime
import commands
import urllib
# 导入配置文件
import ConfigParser
import os
import multiprocessing

cf = ConfigParser.ConfigParser()
cf.read('/csc/csc.conf')
obs = cf.get('csc', 'obs')
obs = obs.split(',')
ms_ip = cf.get('csc', 'ms_ip')


print "=================选择oid===================="
def exec_api(filename,item_event,number):
    method="Put"
    print(os.getpid())
    EXEC_status, EXEC_output = commands.getstatusoutput(
        "python /csc/inotify_create.py '%s' '%s' '%s' '%s' '%s' 'small'" % (
            method, filename, item_event, ms_ip, obs[number]))
    if EXEC_status==0:
        print EXEC_output

def number():
    with open('/csc/number.txt', "r") as f_read:
        number = int(f_read.read())
        n=number%4
        # print n
    with open('/csc/number.txt', "w") as f_write:
        l = str(number + 1)
        f_write.write(l)
        # print l
    return n
# method=sys.argv[1]
filename = sys.argv[1]
item_event = sys.argv[2]

exec_api(filename,item_event,number())