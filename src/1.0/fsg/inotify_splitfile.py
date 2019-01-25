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

# 导入配置文件
import ConfigParser
import os
import multiprocessing

cf = ConfigParser.ConfigParser()
cf.read('/csc/csc.conf')
obs = cf.get('csc', 'obs')
obs = obs.split(',')
ms_ip = cf.get('csc', 'ms_ip')
pool = int(cf.get('csc', 'pool'))


filename = sys.argv[1]
small_size = int(sys.argv[2])
file_size = int(sys.argv[3])

filepath,fullflname = os.path.split(filename)
fname,ext = os.path.splitext(fullflname)
print filepath,fname


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
    a=(file_size+small_size-1)/small_size#向上取整，即算出切分了多少个文件,即开几个进程
    # b=file_size%small_size#求余数，、
    b=a/len(obs)#轮询几次，做选择oid
    c=a%len(obs)#剩下几个，做指定oid
    return a,b,c


def filename_small():
    EX_status, EX_output = commands.getstatusoutput(
        "readlink -f %s/%s/*" % (filepath, fname))
    # print EX_output
    if EX_status == 0:
        filename_s = EX_output.split('\n')  # 所有的切分文件，（输出会是换行，所以分割成list）
        print EX_output
    return filename_s


def select():
    # filename_s=filename_small()
    q=count()
    print q
    #轮询添加
    k=0
    # for i in range(q[1]):
    for i in range(q[1]+1):
        for j in obs:
            # print filename_s[k],j
            data={'method':"Put",'item_event':"创建",'filename':filename_s[k],'oid':j,'type':"big"}
            print data
            # exec_api(data)
            spool.apply_async(func=exec_api, args=(data,))
            print "===========切分上传==============="
            k += 1
            if k==len(filename_s):
                print k,len(filename_s)
                break
    # pool.close()
    # pool.join()  # 这里要先关闭再JOIN。进程池中进程执行完后再关闭，如果注释，那么程序直接关闭

    #指定添加
    # for i in range(q[2]):
        #获取最小负载的192.168.1.100，查看上面的oid，任意选择一个
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
    # EX1_status, EX1_output = commands.getstatusoutput(
    #     "python /csc/inotify_create.py '%s' '%s' '%s' '%s' '%s'" % (
    #         data['method'], data['filename'], data['item_event'], ms_ip, data['oid']))
    # print EX1_output

if __name__ == '__main__':
    split()
    filename_s = filename_small()
    spool = multiprocessing.Pool(processes=pool)  # 设置进程池的大小
    # print pool
    select()
    spool.close()
    spool.join()  # 这里要先关闭再JOIN。进程池中进程执行完后再关闭，如果注释，那么程序直接关闭
    #清空切分文件内容??是否需要
    empty()

