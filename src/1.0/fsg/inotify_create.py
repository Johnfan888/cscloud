#!/usr/bin/python
# coding:utf-8
# author: xjl
# 创建新文件
import sys
import logging
import datetime
import commands
import urllib


method = sys.argv[1]
filename = sys.argv[2]
item_event = sys.argv[3]
ms_ip = sys.argv[4]
oid = sys.argv[5]
file_type=sys.argv[6]

logging.basicConfig(level=logging.INFO, filename='/var/log/csc/cscfsg_create.log')  # 日志目录
print " +++++++++++++++++++上传+++++++++++++++++++++++ "

print item_event, ":", filename
print "开始上传 上传参数:", method, filename, oid, ms_ip
logging.info("%s:%s %s" % (item_event, filename, datetime.datetime.now()))
logging.info("开始上传 %s %s " % (filename, datetime.datetime.now()))

status, output = commands.getstatusoutput(
    "php /csc/csc_client_api.php '%s' '%s' '%s' '%s'" % (
        method, urllib.quote(filename), oid, ms_ip))
if status == 0:
    print "上传结束", output
    logging.info("上传结束 %s %s %s" % (filename, output, datetime.datetime.now()))
    if file_type != "big":
        CR_status, CR_output = commands.getstatusoutput(
            "setfattr -n user.event -v 'created' '%s'" % (filename))
        if CR_status == 0:
            print "属性标记为：created"
            logging.info("属性标记为：created %s %s" % (filename, datetime.datetime.now()))
    CR1_status, CR1_output = commands.getstatusoutput(
        "setfattr -n user.oid -v '%s' '%s'" % (oid, filename))
    if CR1_status == 0:
        print "oid标记为：%s" % (oid)
        logging.info("oid标记为：%s %s  %s" % (filename, oid, datetime.datetime.now()))
else:
    print "上传失败"
    logging.info("上传失败 %s %s %s" % (filename, output, datetime.datetime.now()))
