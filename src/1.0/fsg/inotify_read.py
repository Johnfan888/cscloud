#!/usr/bin/python
# coding:utf-8
# author: xjl
import os
import sys
import commands


from inotify_conf import ms_ip


method = sys.argv[1]
filename = sys.argv[2]
print "读取中请稍后.................."
print filename

EXEC_status, EXEC_output = commands.getstatusoutput(
    "python /csc/inotify_download.py 'Get' '%s' '下载' " % (
         filename))
if EXEC_status==0:

    os.system('%s %s'%(method,filename))#进程阻塞中

    f = open(filename, 'r+')
    f.truncate()  # 清空文件内容
    f.close()
else:
    print "读取失败"


# with open('/csc/xjl_test/ttt.txt', "r") as f_read:


# f.close() #关闭文件



# print number,type(number)
