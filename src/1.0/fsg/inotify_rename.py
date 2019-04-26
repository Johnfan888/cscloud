#!/usr/bin/python
# coding:utf-8
# author: xjl
#文件改名

import sys
import logging
import datetime
import commands
import urllib

logging.basicConfig(level=logging.INFO, filename='/var/log/csc/cscfsg_rename.log')  # 日志目录


method = sys.argv[1]
filename = sys.argv[2]
item_event = sys.argv[3]
filename_new = sys.argv[4]
ms_ip = sys.argv[5]
obsid = "needless"  # 不需要这个变量，但是api会需要接收

print item_event, ":", filename
print "开始上传 上传参数:", method, filename, filename_new,obsid, ms_ip
logging.info("%s:%s %s" % (item_event, filename_new, datetime.datetime.now()))
logging.info("开始上传 %s %s " % (filename_new, datetime.datetime.now()))
status, output = commands.getstatusoutput(
    "php /csc/csc_client_api.php '%s' '%s' '%s' '%s' '%s'" % (
        method, urllib.quote(filename), obsid, ms_ip,urllib.quote(filename_new)))  # urllib.quote(self.filename)
if status == 0:
    print "上传结束",output
    logging.info("上传结束 %s %s %s" % (filename_new, output, datetime.datetime.now()))
else:
    print "上传失败"
    logging.info("上传失败 %s %s %s" % (filename_new, output, datetime.datetime.now()))
