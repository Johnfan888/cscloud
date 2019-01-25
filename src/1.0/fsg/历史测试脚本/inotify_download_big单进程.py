#!/usr/bin/python
# coding:utf-8
# author: xjl
# 下载大文件
import sys
import logging
import datetime
import commands
import urllib

# logging.basicConfig(level=logging.INFO, filename='/var/log/csc/cscfsg_download_big.log')  # 日志目录


method = sys.argv[1]
filename = sys.argv[2]
# item_event = sys.argv[3]
ms_ip = sys.argv[3]
oid = "needless"  # 不需要这个变量，但是api会需要接收

DE_status, DE_output = commands.getstatusoutput(
    "ls '%s'_split*" % (filename))
# print DE_output,type(DE_output)
filename_s = DE_output.split('\n')
for i in range(len(filename_s)):
    print i
    status, output = commands.getstatusoutput(
        "php /csc/csc_client_api.php '%s' '%s' '%s' '%s'" % (
            method, urllib.quote(filename_s[i]), oid, ms_ip))  # urllib.quote(self.filename)
    if status == 0:
        print "下载结束", output
status, output = commands.getstatusoutput(
    "cat '%s'_split* > '%s' " % (filename,filename))
if status==0:
    print "合并成功"
