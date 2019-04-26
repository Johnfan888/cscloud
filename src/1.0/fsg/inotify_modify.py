#!/usr/bin/python
# coding:utf-8
# author: xjl
# 修改文件
import sys
import logging
import datetime
import commands
import urllib

logging.basicConfig(level=logging.INFO, filename='/var/log/csc/cscfsg_modify.log')  # 日志目录


# method = sys.argv[1]
filename = sys.argv[1]
item_event = sys.argv[2]
ms_ip = sys.argv[3]
method="Put"
obsid_status, obsid_output = commands.getstatusoutput(
    "getfattr -n user.obsid  '%s' --only-values  --absolute-names" % (filename))
if obsid_status == 0:
    obsid = obsid_output
    # print oid
else:
    print "获取oid出错"
    logging.info("获取oid出错 %s %s" % ( filename, datetime.datetime.now()))
print item_event, ":", filename
print "开始上传 上传参数:", method, filename, obsid, ms_ip
logging.info("%s:%s %s" % (item_event, filename, datetime.datetime.now()))
logging.info("开始上传 %s %s " % (filename, datetime.datetime.now()))
status, output = commands.getstatusoutput(
    "php /csc/csc_client_api.php '%s' '%s' '%s' '%s'" % (
        method, urllib.quote(filename), obsid, ms_ip))  # urllib.quote(self.filename)

if status == 0:
    print "上传结束",output
    logging.info("上传结束 %s %s %s" % (filename, output, datetime.datetime.now()))
else:
    print "上传失败"
    logging.info("上传失败 %s %s %s" % (filename, output, datetime.datetime.now()))

MODIFY1_status, MODIFY1_output = commands.getstatusoutput(
    "setfattr -n user.event -v 'modify' '%s'" % (filename))
if MODIFY1_status == 0:
    print "属性标记为：modify"
    logging.info(" 属性标记为：modify %s  %s" % (filename, datetime.datetime.now()))
else:
    print "属性标记失败"
    logging.info(" 属性标记失败 %s  %s" % (filename, datetime.datetime.now()))
