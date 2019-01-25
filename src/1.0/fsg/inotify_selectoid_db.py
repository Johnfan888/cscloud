#!/usr/bin/python
# coding:utf-8
# author: xjl
# 选择oid---连接数据库查看
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
limit_oid = int(cf.get('csc', 'limit_oid'))

import inotify_getcsc

DS = inotify_getcsc.getDs()  # 获取到所有ds

#a为所有ds的总和
def ip(a):
    with open('/csc/ip.txt', "r") as f_read:
        number = int(f_read.read())
        n = number % (a)
        # print n
    with open('/csc/ip.txt', "w") as f_write:
        l = str(number + 1)
        f_write.write(l)
        # print l
    return n

#b为ip.txt计算的余数
def num(b):
    with open('/csc/num.txt', "r") as f_read:
        num = int(f_read.read())
        n = num % limit_oid
        # print n,limit_oid
        # print b,len(DS)
    if b == len(DS)-1:
        with open('/csc/num.txt', "w") as f_write:
            l = str(num + 1)
            f_write.write(l)
        # print l
    return n

def Oid():
    # DS = inotify_getcsc.getDs()  # 获取到所有ds
    # print DS, len(DS)
    # ds_ip = ip(len(DS))
    # print aa
    # print DS[aa][0]  # 调用一次ds，则顺序+1

    i=ip(len(DS))#为了轮询ds需要的一个标计数
    ds_ip=DS[i][0]#轮询ds，顺序取出一个,[0]是将其转为字符串
    # print ds_ip

    user_id=inotify_getcsc.getDSUser(ds_ip)#获取指定ip的DS所拥有的所有oid
    # print user_id

    j = num(i)#为了轮询ds下面的oid，需要的一个标记数
    # print user_id[j]#

    Oid=inotify_getcsc.getOid(user_id[j])
    # print Oid
    return Oid


OID=Oid()
# print type(OID[0][0]),OID[0][0]#变tuple为unicode,再转str

def exec_api(filename,item_event,oid):
    method="Put"
    print(os.getpid())
    EXEC_status, EXEC_output = commands.getstatusoutput(
        "python /csc/inotify_create.py '%s' '%s' '%s' '%s' '%s' 'small'" % (
            method, filename, item_event, ms_ip, oid))
    if EXEC_status==0:
        print EXEC_output


filename = sys.argv[1]
item_event = sys.argv[2]

exec_api(filename,item_event,str(OID[0][0]))