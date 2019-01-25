#!/usr/bin/python
# coding:utf-8
# author: xjl
# 下载大文件
import sys
import logging
import datetime
import commands
import urllib
import multiprocessing
import os
import time
# logging.basicConfig(level=logging.INFO, filename='/var/log/csc/cscfsg_download_big.log')  # 日志目录

method = sys.argv[1]
filename = sys.argv[2]
item_event = sys.argv[3]




# 导入配置文件
import ConfigParser
cf = ConfigParser.ConfigParser()
cf.read('/csc/csc.conf')

ms_ip = cf.get('csc', 'ms_ip')
pool = int(cf.get('csc', 'pool'))


filepath,fullflname = os.path.split(filename)
fname, ext = os.path.splitext(fullflname)

oid = "needless"  # 不需要这个变量，但是api会需要接收


class main():
    def download(self):
        DE_status, DE_output = commands.getstatusoutput(
            "ls '%s/%s/'" % (filepath, fname))
        # "ls '%s/%s/%s*'" % (filepath, fname, fname))这样会报错ls: cannot access /cscdata/asdasd/asdasd*
        filename_s = DE_output.split('\n')
        self.n = len(filename_s)  # 有多少个文件
        p = multiprocessing.Pool(pool)
        for i in filename_s:
            # print i
            data = {'method': method, 'filename': i, 'item_event': item_event,}
            print data
            p.apply_async(exec_api, (data,))

            # t = multiprocessing.Process(target=exec_api, args=(data,))
            # res = p.apply_async(exec_api, args=(data,))  # apply_sync的结果就是异步获取func的返回值
            # res_l.append(res)  # 从异步提交任务获取结果

        p.close()
        p.join()
        print "完成下载"
        self.cat()

    def cat(self):
        status, output = commands.getstatusoutput(
            "cat '%s/%s/%s'_split* > '%s' " % (filepath, fname, fullflname, filename))
        if status == 0:
            print "合并成功"


def exec_api(data):
        print "开始下载"
        status, output = commands.getstatusoutput(
            "php /csc/csc_client_api.php '%s' '%s/%s/%s' '%s' '%s'" % (
                data['method'], filepath, fname, urllib.quote(data['filename']), oid, ms_ip))  # urllib.quote(self.filename)
        if status == 0:
            print "下载结束", output

a=main()
a.download()

