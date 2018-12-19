#!/usr/bin/python
# coding:utf-8
# author: xjl
# 下载文件
import sys
import logging
import datetime
import commands
import urllib

logging.basicConfig(level=logging.INFO, filename='/var/log/csc/cscfsg_download.log')  # 日志目录


method = sys.argv[1]
filename = sys.argv[2]
item_event = sys.argv[3]
ms_ip = sys.argv[4]
oid = "needless"  # 不需要这个变量，但是api会需要接收

print item_event, ":", filename
print "开始下载 下载参数:", method, filename, oid, ms_ip
logging.info("%s:%s %s" % (item_event, filename, datetime.datetime.now()))
logging.info("开始下载 %s %s " % (filename, datetime.datetime.now()))
status, output = commands.getstatusoutput(
    "php /csc/csc_client_api.php '%s' '%s' '%s' '%s'" % (
        method, urllib.quote(filename), oid, ms_ip))  # urllib.quote(self.filename)
if status == 0:
    print "下载结束",output
    DL_status, DL_output = commands.getstatusoutput(
        "setfattr -n user.event -v 'downloaded' '%s'" % (filename))
    if DL_status == 0:
        print "下载文件，属性标记为：downloaded"
    logging.info("上传结束 %s %s %s" % (filename, output, datetime.datetime.now()))
else:
    print "下载失败"
    logging.info("上传失败 %s %s %s" % (filename, output, datetime.datetime.now()))
