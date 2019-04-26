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
import fcntl#文件锁
from inotify_conf import ms_ip,obsid_limit_small
cf = ConfigParser.ConfigParser()
cf.read('/csc/csc.conf')
# # obs = cf.get('csc', 'obs')
# # obs = obs.split(',')
# ms_ip = cf.get('csc', 'ms_ip')
# obsid_limit_small = int(cf.get('csc', 'obsid_limit_small'))

#调用数据库脚本
import inotify_http_getcsc




# len_ds为所有ds的总和
def ip(len_ds):
    with open('/csc/ip.txt', "rw+") as f:
        fcntl.flock(f, fcntl.LOCK_EX)  # 加锁，进程关闭自动解锁（也可命令fcntl.flock(f,fcntl.LOCK_UN)）
        number = int(f.read())
        n = number % (len_ds)
        # print n
        l = str(number + 1)
        f.seek(0)  # 将指针归0
        f.write(l)
        # print l
    return n

# 得到obsid数组的轮询位置
def num(i, ds_ip):
    with open('/csc/num.txt', "rw+") as f:
        fcntl.flock(f, fcntl.LOCK_EX)  # 加锁，进程关闭自动解锁（也可命令fcntl.flock(f,fcntl.LOCK_UN)）
        num = int(f.read())
        # print num
        if i == len(DS) - 1:
            l = str(num + 1)
            f.seek(0)  # 将指针归0
            f.write(l)
        if num >= get_obsid_limit(ds_ip):
            num = num % get_obsid_limit(ds_ip)
        return num
    #解锁

# 获取ip对应obs的上限
def get_obsid_limit(ds_ip):
    obsid_limit_small = int(cf.get(ds_ip, 'obsid_limit_small'))
    return obsid_limit_small

#得到轮询的obsids
def get_obsid():
    i = ip(len(DS))#得到当前对所有的ds数组轮询的位置
    # print i,type(i)
    ds_ip = DS[i]  # 轮询ds，顺序取出一个,[0]是将其转为字符串
    print ds_ip

    j = num(i, str(ds_ip))#得到此ip对应的obsid数组的轮询位置
    # print j

    # user_id = inotify_getcsc.getDSUser(ds_ip)  # 获取指定ip的DS所拥有的所有oid
    user_id = inotify_http_getcsc.main(1,"getDSUser('%s')"%(ds_ip))  # 获取指定ip的DS所拥有的所有oid
    user_id = user_id.split(",")
    print user_id

    #不会发生，user_id[j]获取不到！根据配置文件开始已经注册够
    # obsid = inotify_getcsc.getOid(user_id[j])
    obsid = inotify_http_getcsc.main(1,"getOid('%s')"%(user_id[j]))

    print obsid
    return obsid

#进行上传
def exec_api(filename,item_event,obsid):
    method="Put"
    # print(os.getpid())
    EXEC_status, EXEC_output = commands.getstatusoutput(
        "python /csc/inotify_create.py '%s' '%s' '%s' '%s' '%s' 'small'" % (
            method, filename, item_event, ms_ip, obsid))
    if EXEC_status==0:
        print EXEC_output



filename = sys.argv[1]
item_event = sys.argv[2]

# DS = inotify_getcsc.getDs()  # 获取到所有ds
DS = inotify_http_getcsc.main(1,"getDs()") # 获取到所有ds
# print type(DS)
DS=DS.split(",")
# print DS
# print DS,type(DS)
obsid=str(get_obsid())
exec_api(filename,item_event,obsid)





