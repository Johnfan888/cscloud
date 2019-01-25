#!/usr/bin/python
# coding:utf-8
# author: xjl
# 区分大文件
import sys
import logging
import datetime
import commands
import urllib
import os
import ConfigParser
import os
import multiprocessing

cf = ConfigParser.ConfigParser()
cf.read('/csc/csc.conf')
size = int(cf.get('csc', 'size'))
small_size = int(cf.get('csc', 'smallfile_size'))
# obs = cf.get('csc', 'obs')
# obs = obs.split(',')

# method = sys.argv[1]
filename = sys.argv[1]
item_event=sys.argv[2]
# 做轮询的计数
print "------------区分大文件-----------"
CW_status, CW_output = commands.getstatusoutput(
    "getfattr -n user.event  '%s' --only-values  --absolute-names" % (filename)
    # --absolute-names：默认去掉/,加上后不显示：getfattr: Removing leading '/' from absolute path names
)
if CW_status == 0:
    if CW_output == "creating":
        # 判断是否为大文件
        DU_status, DU_output = commands.getstatusoutput(
            "du -b '%s' | awk '{print $1}'  " % (filename))
        if DU_status == 0:
            # print DU_output,size,
            # print type(DU_output),type(size)
            if int(DU_output) >= size:  # 大于100M为大文件
                print "大文件"
                data = {'method': 'Split', 'filename': filename, 'file_size': int(DU_output)}
                # #不连数据库
                # CW1_status, CW1_output = commands.getstatusoutput(
                #     "python /csc/inotify_splitfile.py '%s'  '%s'" % (
                #         data['filename'],data['file_size']))
                #连接数据库
                CW1_status, CW1_output = commands.getstatusoutput(
                    "python /csc/inotify_splitfile_db.py '%s'  '%s'" % (
                        data['filename'], data['file_size']))
                print CW1_output
                # exec_api(data)
                # print SP_output
            else:
                print "小文件"
                data = {'method': 'Oid', 'filename': filename, 'item_event': item_event}  # number做轮询的计数
                # #配置文件的轮询
                # CW2_status, CW2_output = commands.getstatusoutput(
                #     "python /csc/inotify_selectoid.py  '%s' '%s' " % (
                #          data['filename'], data['item_event']))
                #查询数据库的轮询
                CW2_status, CW2_output = commands.getstatusoutput(
                    "python /csc/inotify_selectoid_db.py  '%s' '%s' " % (
                        data['filename'], data['item_event']))
                print CW2_output

                # select_oid(data)
    # else:
    #     print "文件存在，属性不是creating"
else:
    print "文件不存在，运行失败"
