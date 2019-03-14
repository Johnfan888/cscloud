#!/usr/bin/python
# coding:utf-8
# author: xjl
# 切分大文件
import sys
import logging
import datetime
import commands
import urllib
import os
import re
# 导入配置文件
import ConfigParser
import os
import multiprocessing
import inotify_getcsc
cf = ConfigParser.ConfigParser()
cf.read('/csc/csc.conf')
obs = cf.get('csc', 'obs')
obs = obs.split(',')
ms_ip = cf.get('csc', 'ms_ip')
pool = int(cf.get('csc', 'pool'))
small_size = int(cf.get('csc', 'smallfile_size'))
limit_oid_big = int(cf.get('csc', 'limit_oid'))


#拆分大文件，并标注
def split():
    SP_status, SP_output = commands.getstatusoutput(
        "mkdir '%s/%s' &&  split -b %s '%s' '%s/%s/%s'_split   " % (filepath,fname,small_size, filename,filepath,fname,fullflname))
    if SP_status == 0:
        print "拆分成功"
        SP1_status, SP1_output = commands.getstatusoutput(
            "setfattr -n user.event -v 'splited' %s/%s/* " % (filepath,fname))
        if SP1_status == 0:
            print "文件标记为拆分文件"
        SP2_status, SP2_output = commands.getstatusoutput(
            "setfattr -n user.file -v 'big' '%s'" % (filename))
        if SP2_status == 0:
            print "文件标记为大文件"
        # SP3_status, SP3_output = commands.getstatusoutput(
        #     "setfattr -n user.file -v 'big' %s/%s/* " % (filepath,fname))
        # if SP3_status == 0:
        #     print "拆分文件标记为大文件"
#计算几个文件轮询，几个文件选择最小
def count():

    a=(file_size+small_size-1)/small_size#向上取整，即算出切分了多少个文件
    # b=file_size%small_size#求余数，、
    b=a/len(DS)#轮询几次，做选择oid
    c=a%len(DS)#剩下几个，做指定oid
    return a,b,c


def filename_small():
    EX_status, EX_output = commands.getstatusoutput(
        "readlink -f %s/%s/*" % (filepath, fname))
    # print EX_output
    if EX_status == 0:
        filename_s = EX_output.split('\n')  # 所有的切分文件，（输出会是换行，所以分割成list）
        # print EX_output
    return filename_s


def select():
    q=count()#得到a,b,c（文件总数，轮询几次，剩下几个文件）
    print q
    p=q[1]#轮询次数
    k=0#读取切分后文件名所用（文件名是一次读取，存在list中）
    while p>=0:
        if p >= limit_oid_big:#轮询次数大于上限
            n=limit_oid_big       #取上限的值
        else:
            n=p  #取剩下几次轮询的值
        for i in range(n):
                print "=============%s==============="%(i)
                for j in DS:
                    # if m <= limit_oid_big:
                    # print str(j[0])
                    user_id = inotify_getcsc.getDSUser(str(j[0]))  # 根据ip取得此DS的所有user_id
                    # print user_id
                    try:
                        if user_id[i]:
                            oid = inotify_getcsc.getOid(user_id[i])
                            oid=str(oid[0][0])
                    except:  # oid不够了注册一个
                        ha_server_ip = DS[DS.index(j) - 1]  # 取当前ip的上一个ip作为副本文件服务器
                        # print str(j[0])
                        # print str(ha_server_ip[0])
                        EXEC_status, EXEC_output = commands.getstatusoutput(
                            "php /csc/csc_client_api_register.php 'create' '1' '%s' '%s' '%s'  " % (
                                ms_ip, str(j[0]), str(ha_server_ip[0])))
                        if EXEC_status == 0:
                            # print EXEC_output,type(EXEC_output)
                            # 这是邮箱格式的匹配，后面需要改成32位二进制数字
                            p1 = r"[0-9]*@+.*"  # 我想匹配到@后面一直到“.”之间的，在这里是hit
                            pattern1 = re.compile(p1)
                            oid_register = pattern1.findall(EXEC_output)
                            oid=oid_register[0]
                            # print oid_register[0], type(oid)
                    data = {'method': "Put", 'item_event': "创建", 'filename': filename_s[k],
                                    'oid': oid,'type': "big"}
                    print k
                    print data
                    spool.apply_async(func=exec_api, args=(data,))
                    print "===========切分上传==============="
                    k += 1
        p=p-limit_oid_big





























    # #轮询添加
    # k=0#读取切分后文件名所用（文件名是一次读取，存在list中）
    # m=0#用作每个ds上oid上限计数
    # # for i in range(q[1]):
    # print "正常上传", q[1], limit_oid_big
    # if q[1]<=limit_oid_big:#轮询没有超出上限
    #     print "正常上传", q[1], limit_oid_big
    #     for i in range(q[1]):#q[1]从1开始算，故for中减1
    #         # print i
    #         for j in DS:
    #             # if m <= limit_oid_big:
    #             # print str(j[0])
    #             user_id=inotify_getcsc.getDSUser(str(j[0]))#根据ip取得此DS的所有user_id
    #             # print user_id
    #             try:
    #                 if user_id[i]:
    #                     oid=inotify_getcsc.getOid(user_id[i])
    #
    #             except:#oid不够了注册一个
    #                 ha_server_ip= DS[DS.index(j)-1]  #取当前ip的上一个ip作为副本文件服务器
    #                 # print str(j[0])
    #                 # print str(ha_server_ip[0])
    #                 EXEC_status, EXEC_output = commands.getstatusoutput(
    #                     "php /csc/csc_client_api_register.php 'create' '1' '%s' '%s' '%s'  " % (
    #                         ms_ip,str(j[0]),str(ha_server_ip[0]) ))
    #                 if EXEC_status == 0:
    #                     # print EXEC_output,type(EXEC_output)
    #                     #这是邮箱格式的匹配，后面需要改成32位二进制数字
    #                     p1 = r"[0-9]*@+.*"  # 我想匹配到@后面一直到“.”之间的，在这里是hit
    #                     pattern1 = re.compile(p1)
    #                     oid_register= pattern1.findall(EXEC_output)
    #                     print oid_register[0],type(oid)
    #                     data = {'method': "Put", 'item_event': "创建", 'filename': filename_s[k], 'oid': oid_register[0],
    #                             'type': "big"}
    #                     print k
    #                     print data
    #                     spool.apply_async(func=exec_api, args=(data,))
    #                     print "===========切分上传==============="
    #                     k += 1
    #             # print user_id[i],oid
    #             # print filename_s[k],j
    #
    # else:#轮询超出上限
    #     m=q[1]-limit_oid_big#得出有几次轮询是从新开始的
    #     for i in range(m):  # q[1]从1开始算，故for中减1
    #         # print i
    #         for j in DS:
    #             # if m <= limit_oid_big:
    #             # print str(j[0])
    #             user_id = inotify_getcsc.getDSUser(str(j[0]))  # 根据ip取得此DS的所有user_id
    #             # print user_id
    #
    #             oid = inotify_getcsc.getOid(user_id[i])#肯定存在
    #             # print user_id[i],oid
    #
    #             # print filename_s[k],j
    #         data = {'method': "Put", 'item_event': "创建", 'filename': filename_s[k], 'oid': str(oid[0][0]),
    #                             'type': "big"}
    #         print k
    #         print data
    #          # exec_api(data)
    #         spool.apply_async(func=exec_api, args=(data,))
    #         print "===========切分上传==============="
    #         k += 1
    #
    #

    #指定添加


    DS_load=inotify_getcsc.getLoad()#获取所有ds和其负载
    print "-------------------------"
    for m in range(q[2]):
        # print m
        ip_load_min= str(DS_load[m][0])#取最小负载的ip
        # print ip_load_min
        user_id_load_min=inotify_getcsc.getuserIDLoad(ip_load_min)#取最小负载ip对应的最小userid
        # print user_id_load_min
        oid_load_min=inotify_getcsc.getOid(user_id_load_min[0][0])#取最小负载ip对应的最小userid对应的oid
        # print oid_load_min[0][0]
        data = {'method': "Put", 'item_event': "创建", 'filename': filename_s[k], 'oid': oid_load_min[0][0], 'type': "big"}
        print k
        print data
        spool.apply_async(func=exec_api, args=(data,))
        print "===========切分上传==============="
        k+=1

def exec_api(data):
    EX1_status, EX1_output = commands.getstatusoutput(
        "python /csc/inotify_create.py '%s' '%s' '%s' '%s' '%s' '%s'" % (
            data['method'],data['filename'] , data['item_event'], ms_ip, data['oid'],data['type']))
    print EX1_output

def empty():
    for i in filename_s:
        # print i
        f = open(i, 'r+')
        f.truncate()#清空文件内容
        f.close()


if __name__ == '__main__':
    filename = sys.argv[1]
    # small_size = int(sys.argv[2])
    file_size = int(sys.argv[2])

    filepath, fullflname = os.path.split(filename)
    fname, ext = os.path.splitext(fullflname)
    print filepath, fname

    DS = inotify_getcsc.getDs()  # 获取到所有ds

    split()#切分文件
    filename_s = filename_small()
    spool = multiprocessing.Pool(processes=pool)  # 设置进程池的大小
    # print pool
    select()#选择oid上传
    spool.close()
    spool.join()  # 这里要先关闭再JOIN。进程池中进程执行完后再关闭，如果注释，那么程序直接关闭
    #清空切分文件内容??是否需要
    empty()#清空切分文件

